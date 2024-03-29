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
<link href="<?php echo base_url()?>statics/css/<?php echo sys_skin()?>/bills.css" rel="stylesheet" type="text/css">
 
<style>
#bottomField{line-height:30px;}
#bottomField label{width: 75px;display: inline-block;}
.con-footer{padding:10px 0 0 0;}
.con-footer .pl0 {padding-left: 0;}
</style>
</head>

<body>
<div class="wrapper">
  <span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
  <div class="mod-toolbar-top mr0 cf dn" id="toolTop"></div>
  <div class="bills">
    <div class="con-header">
      <dl class="cf">
        <dd class="pct30">
          <label>供应商：</label>
          <span class="ui-combo-wrap" id="customer">
          <input type="text" name="" class="input-txt" autocomplete="off" value="" data-ref="date">
          <i class="ui-icon-ellipsis"></i></span></dd>
        <dd class="pct25 tc">
          <label>单据日期：</label>
          <input type="text" id="date" class="ui-input ui-datepicker-input" value="2015-08-25">
        </dd>
        <dd id="identifier" class="pct25 tc">
          <label>单据编号：</label>
          <span id="number"><?php echo str_no('QTZC')?></span></dd>
      </dl>
    </div>
    <div class="grid-wrap">
      <table id="grid">
      </table>
      <div id="page"></div>
    </div>
    <div class="con-footer cf">
      <ul id="amountArea" class="cf">
        <li id="accountWrap" class="dn pl0">
          <label>结算账户:</label>
          <span class="ui-combo-wrap" id="account" style="padding:0;">
            <input type="text" class="input-txt" autocomplete="off"><i class="trigger"></i>
          </span>
          <a id="accountInfo" class="ui-icon ui-icon-folder-open" style="display:none;"></a>
        </li>
        <li>
          <label>付款金额:</label>
          <input type="text" id="amount" class="ui-input ui-input-dis" disabled>
        </li>
      </ul>
      <ul class="c999 cf">
        <li>
          <label class="dn">制单人:</label>
          <span id="userName"></span>
        </li>
        <!-- <li>
          <label>审核人:</label>
          <span id="checkName"></span>
        </li> -->
        <!-- <li>
          <label class="dn">最后修改时间:</label>
          <span id="modifyTime"></span>
        </li> -->
      </ul>
    </div>
    <div class="cf" id="bottomField">
    	<div class="fr" id="toolBottom"></div>
    </div>
  </div>
  
  <div id="initCombo" class="dn">
    <input type="text" class="textbox categoryAuto" name="category" autocomplete="off">
  </div>
</div>
<script src="<?php echo base_url()?>statics/js/dist/other-expense.js"></script>
</body>
</html>

 