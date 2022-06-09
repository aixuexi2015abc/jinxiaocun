<?php $this->load->view('header');?>
 
<script type="text/javascript">
var DOMAIN = document.domain;
var WDURL = "";
var SCHEME= "<?php echo sys_skin()?>";
try{
	document.domain = '<?php echo base_url()?>';
}catch(e){
}
//ctrl+F5 增加版本号来清空iframe的缓存的
$(document).keydown(function(event) {
	/* Act on the event */
	if(event.keyCode === 116 && event.ctrlKey){
		var defaultPage = Public.getDefaultPage();
		var href = defaultPage.location.href.split('?')[0] + '?';
		var params = Public.urlParam();
		params['version'] = Date.parse((new Date()));
		for(i in params){
			if(i && typeof i != 'function'){
				href += i + '=' + params[i] + '&';
			}
		}
		defaultPage.location.href = href;
		event.preventDefault();
	}
});
</script>


<script>
function validMaxForShare(){
    window.location.href='../settings/authority_new';
}
</script>
</head>
<body>
<div class="wrapper">
    <div class="mod-toolbar-top">
       <a href="javascript:validMaxForShare();" class="ui-btn ui-btn-sp mrb">新建用户</a> 
    </div> 
    <div id="helpDIV" class="bg-success hide"></div>   
    <div class="grid-wrap">
      <table id="grid">
      </table>
      <div id="page"></div>
    </div>
</div>
<script>
  (function($){
    var totalUser, usedTotal, leftTotal;
    initGrid();  

    //重置用户密码
    $('.grid-wrap').on('click', '.reSetPassword', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
      $.ajax({
        url: '../right/reSetUserPassword?action=reSetUserPassword&userName=' + userName,
        type: 'POST',
        dataType: 'json',
        success: function(data){
          if (data.status == 200) {
            $("#helpDIV").html("密码重置成功，"+rowData.realName+"的最新密码： <b>"+data.data+"</b>&nbsp;&nbsp;请使用最新密码登录。");
            $("#helpDIV").show();
          } else {
            parent.Public.tips({type: 1, content: '用户密码重置失败！' + data.msg});
          }
        },
        error: function(){
           parent.Public.tips({content:'用户密码重置失败！请重试。', type: 1});
        }
      });
    });

    //修改用户信息 
    $('.grid-wrap').on('click', '.eidtUser', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
      window.location.href='../settings/authority_edit?userName=' + userName;
    });

    $('.grid-wrap').on('click', '.delete', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
      $.ajax({
        url: '../right/auth2UserCancel?action=auth2UserCancel&userName=' + userName,
        type: 'POST',
        dataType: 'json',
        success: function(data){
          if (data.status == 200) {
            parent.Public.tips({content: '取消用户授权成功！'});
            usedTotal--;
            leftTotal++;
            showShareCount();
            if (rowData.isCom) {
                rowData.share = false;
                $("#grid").jqGrid('setRowData', id, rowData);
            } else {
                $("#grid").jqGrid('delRowData',id);
            }
           
          } else {
            parent.Public.tips({type: 1, content: '取消用户授权失败！' + data.msg});
          }
        },
        error: function(){
           parent.Public.tips({content:'取消用户授权失败！请重试。', type: 1});
        }
      });
    });

    $('.grid-wrap').on('click', '.authorize', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
       $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../right/auth2User?action=auth2User&userName=' + userName,
        success: function(data){
          if (data.status == 200) {
            parent.Public.tips({content : '授权成功！'});
            rowData.share = true;
            $("#grid").jqGrid('setRowData', id, rowData);
            usedTotal++;
            leftTotal--;
            showShareCount();
            //window.location.href = 'authority-setting.jsp?userName=' + userName + '&right=0';
          } else {
            parent.Public.tips({type:1, content : data.msg});
          }
        },
        error: function(){
          parent.Public.tips({type:1, content : '用户授权失败！请重试。'});
        }
      });
    });
   
    function initGrid(){
      $('#grid').jqGrid({
        url: '../right/queryAllUser?action=queryAllUser',
        datatype: 'json',
        height: Public.setGrid().h,
        colNames:['用户', '真实姓名','手机号','所属店铺', '重置密码','修改','功能授权','数据授权','启用授权'],
        colModel:[
          {name:'userName',index:'userName', width:150},
          {name:'realName', index:'realName', width:150},
          {name:'mobile', index:'mobile', width:200},
          {name:'dianpumc', index:'dianpumc', width:300},
          {name:'isCom', index:'isCom', width:100, align:"center", title:false, formatter: settingPasswordFormatter},
		  {name:'edit', index:'edit', width:100, align:"center", title:false, formatter: editDataFormatter},
          {name:'setting', index:'setting', width:100, align:"center", title:false, formatter: settingFormatter},
		  //{name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter},
		  {name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter},
		  {name:'share', index:'share', width:100, align:"center", title:false, formatter: shareFormatter}
        ],
        altRows:true,
        gridview: true,
        page: 1,
        scroll: 1,
        autowidth: true,
        cmTemplate: {sortable:false}, 
        rowNum:150,
        shrinkToFit:false,
        forceFit:false,
        pager: '#page',
        viewrecords: true,
        jsonReader: {
          root: 'data.items', 
          records: 'data.totalsize',  
          repeatitems : false,
          id: 'userId'
        },
        loadComplete: function(data){
          if (data.status == 200) {
            data = data.data;
            totalUser = data.totalUserNum;
            usedTotal = data.shareTotal;
            leftTotal = totalUser - usedTotal;
            showShareCount();
            $('#shareInfo').show();
          } else {
        	  parent.Public.tips({type: 1, content: data.msg});
          }
          
        },
        loadonce: true
      });
    }

    function showShareCount(){
        $('#totalUser').text(totalUser);
        $('#usedTotal').text(usedTotal);
        $('#leftTotal').text(leftTotal);
    }
	
  //pndc 
	function settingPasswordFormatter(val, opt, row) {
     if (row.admin) {
              return '';
          } else {
            return '<div class="operating" data-id="' + row.userId + '"><i class="reSetPassword iconfont icon-password" title="重置密码"></i></div>';
          }
  };
  //修改用户信息
  function editDataFormatter(val, opt, row)
  {
    if (row.admin) {
              return '';
          } else {
            return '<div class="operating" data-id="' + row.userId + '"><i class="eidtUser ui-icon ui-icon-pencil" title="修改用户信息"></i></div>';
          }
  }
	function shareFormatter(val, opt, row) {
        if (val || row.admin) {
          if (row.admin) {
              return '管理员';
          } else {
               return '<div class="operating" data-id="' + row.userId + '"><span class="delete ui-label ui-label-success">已启用</span></div>';
          }
        } else {
          return '<p class="operate-wrap"><span class="authorize ui-label ui-label-default">已停用</span></p>';
        }
    };
    function settingFormatter(val, opt, row) {
		if (row.admin || row.share === false) {
			return '&nbsp;';
		} else {
			return '<div class="operating" data-id="' + row.userId + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="../settings/authority_setting?userName=' + row.userName + '"></a></div>';
		}
    };
    function settingDataFormatter(val, opt, row) {
		if (row.admin || row.share === false) {
			return '&nbsp;';
		} else {
			return '<div class="operating" data-id="' + row.userId + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="../settings/authority_setting_data?userName=' + row.userName + '"></a></div>';
		}
    };
	
  })(jQuery)
  
  $(window).resize(function(){
	  Public.resizeGrid();
  });
</script>
</body>
</html>