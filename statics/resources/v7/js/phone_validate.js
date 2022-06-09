var intervalTime;

function checkRealName(){
	var realName = $('#real-name'),
		val = $.trim(realName.val());
	if(val.length == 0 || val == realName[0].defaultValue){
		showTip(realName,'真实姓名不能为空',0);
		return false;
	}
	showTip(realName,'');
	return true;
}

function checkPhone(){
	var phone = $('#phone'),
		val = $.trim(phone.val()),
		reg = /^(13|15|17|18)(\d){9}$/;
	if(val.length == 0){
		showTip(phone,'手机号码不能为空',0);
		return false;
	}
	if(!reg.test(val)){
		showTip(phone,'请填写正确的手机号码',0);
		return false;
	}
	showTip(phone,'');
	return true;
}

function checkActiveCode(){
	var activeCode = $('#active-code'),
		val = $.trim(activeCode.val()),
		flag = true;
	if(val.length == 0){
		showTip(activeCode,'验证码不能为空',0);
		return false;
	}
	if(val.length < 6){
		showTip(activeCode,'请填写正确的验证码',0);
		return false;
	}

	return flag;
}

function getUrlParams() {
   var param, url = location.search, theRequest = {};
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0, len = strs.length; i < len; i ++) {
		 param = strs[i].split("=");
		 param[0] && (theRequest[param[0]]=decodeURIComponent(decodeURIComponent(param[1])));
      }
   }
   return theRequest;
}

function getActiveCode(obj){

}

function countDown(time){
	clearInterval(intervalTime);
	time = time || 60;
	var btn = $('#get-code');
	btn.addClass('ui-btn-dis').html(time + '秒后可以重发');
	intervalTime = setInterval(function(){
		time--;
		if(time > 0){
			btn.html(time + '秒后可以重发');
		}else{
			clearInterval(intervalTime);
			btn.removeClass('ui-btn-dis').html('免费获取验证码');
			var _obj = $('.timeoutToShowCode');
			var _code = _obj.data('code');
			_obj && _code && showTip(_obj,'当前验证码为：'+_code,0);
			
		}
	},1000);
}

function showTip(obj,ctn,type){
	obj = $(obj);
	var tip = obj.siblings('.tip').eq(0);
	if(tip.length == 0){
		tip = $('<p></p>').appendTo(obj.parents('li'));
		tip.addClass('tip');
	}
	if(type == 0){
		tip.html(ctn).addClass("tip-error").removeClass('tip-correct').show();
	}else{
		tip.removeClass("tip-error").addClass('tip-correct');
		ctn == '' ? tip.hide() : tip.html(ctn).show();
	}
}

function checkPwd(){
	var password = $('#password'),
		val = password.val(),
		level = checkPassword.level(val);
	if(level < 5){
		showTip(password,checkPassword.txt[level],0);
		return false;
	}
	showTip(password,'');
	return true;
}

function checkConfirmPwd(){
	var confirmPwd = $('#confirm-password'),
		val = confirmPwd.val(),
		passwordVal = $('#password').val();
	if(val.length == 0){
		showTip(confirmPwd,'确认密码不能为空',0);
		return false;
	}
	if(val != passwordVal){
		showTip(confirmPwd,'两次输入的密码不一致',0);
		return false;
	}
	showTip(confirmPwd,'');
	return true;
}