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

<script src="<?php echo skin_url()?>/js/common/plugins/jquery.spinbox.js">
</script>
<style>
body{background: #fff;}
.manage-wrap{margin: 20px auto 10px;width: 300px;}
.manage-wrap .ui-input{width: 200px;font-size:14px;}
.mod-form-rows .tips {color:#555;font-size:14px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="name">名称：</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="name" id="name"></div>

			</li>
			<li class="row-item">
    			<div class="label-wrap"><label for="group">所属组：</label></div>
    			<div class="ctn-wrap"><span id="group"></span></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><a class="tips" title="（基本单位:个）" >换算关系：</a></div>
				<div class="ctn-wrap">
					<input type="text" name="rate" id="rate" class="ui-input ui-input-dis" disabled/>
				</div>
			</li>
		</ul>
	</form>
</div>
<script src="<?php echo base_url()?>statics/js/dist/unitManage.js"></script>
</body>
</html>
