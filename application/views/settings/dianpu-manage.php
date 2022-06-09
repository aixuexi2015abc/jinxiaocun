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

<style>
body{background: #fff;}
.manage-wrap{margin: 20px auto 10px;width: 400px;}
.manage-wrap .ui-input{width: 220px;font-size:14px;}
.mod-form-rows .label-wrap{width:80px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="dianpumc">店铺名称:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="dianpumc" id="dianpumc" placeholder="店铺名称，必填"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="suoshuqy">店铺地址:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="suoshuqy" id="suoshuqy" placeholder="店铺地址，必填"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="suoshuqy">所属仓库:</label></div>
				<div class="ctn-wrap">
					<div id="divcangku"></div>
				</div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="zhuyaofzr">主要负责人:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="zhuyaofzr" id="zhuyaofzr"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="lianxifs">联系方式:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="lianxifs" id="lianxifs"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="paixu">排序:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="paixu" id="paixu" placeholder="排序值，必填"></div>
			</li>
			
		</ul>
	</form>
</div>
<script src="<?php echo base_url()?>/statics/js/dist/dianpuManage.js"></script>
</body>
</html>
 