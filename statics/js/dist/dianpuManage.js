function initField() {
	rowData.id && ($("#dianpumc").val(rowData.dianpumc),
     $("#suoshuqy").val(rowData.suoshuqy),
     $("#zhuyaofzr").val(rowData.zhuyaofzr),
     $("#lianxifs").val(rowData.lianxifs),
     $("#paixu").val(rowData.paixu)
     )
}
function initEvent() {
	var a = $("#paixu"),b=$("#dianpumc");
	Public.limitInput(a, /^[a-zA-Z0-9\-_]*$/), Public.bindEnterSkip($("#manage-wrap"), postData, oper, rowData.id), initValidator(), b.focus(),rowData.id ? a.val(rowData.paixu):a.val("1")
}
function initPopBtns() {
	var a = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm",
		name: a[0],
		focus: !0,
		callback: function() {
			return postData(oper, rowData.id), !1
		}
	}, {
		id: "cancel",
		name: a[1]
	})
}
function initValidator() {
	$.validator.addMethod("paixu", function(a) {
		return /^[a-zA-Z0-9\-_]*$/.test(a)
	}), $("#manage-form").validate({
		rules: {
			dianpumc: {
				required: !0
			},
			suoshuqy: {
				required: !0
			},
			paixu: {
				required: !0,
				number: !0
			}
		},
		messages: {
			dianpumc: {
				required: "店铺名称不能为空"
			},
			suoshuqy: {
				required: "店铺地址不能为空"
			},
			paixu: {
				required: "排序值不能为空",
				number: "排序值请填写数字"
			}
		},
		errorClass: "valid-error"
	})
}
function postData(a, b) {
	if (!$("#manage-form").validate().form()) return void $("#manage-form").find("input.valid-error").eq(0).focus();
	var c = "add" == a ? "新增店铺" : "修改店铺",
		d = $.trim($("#dianpumc").val()),
		e = $.trim($("#suoshuqy").val()),
        f = $.trim($("#zhuyaofzr").val()),
        g = $.trim($("#lianxifs").val()),
        h = $.trim($("#paixu").val());
	params = rowData.id ? {   
		locationId: b,
		dianpumc: d,
        suoshuqy: e,
        zhuyaofzr: f,
        lianxifs: g,
        paixu: h,
		isDelete: rowData["delete"]
	} : {
		dianpumc: d,
        suoshuqy: e,
        zhuyaofzr: f,
        lianxifs: g,
        paixu: h,
		isDelete: !1
	}, Public.ajaxPost("../basedata/dianpu/" + ("add" == a ? "add" : "update"), params, function(b) {
		200 == b.status ? (parent.parent.Public.tips({
			content: c + "成功！"
		}), callback && "function" == typeof callback && callback(b.data, a, window)) : parent.parent.Public.tips({
			type: 1,
			content: c + "失败！" + b.msg
		})
	})
}

function resetForm(a) {
	$("#manage-form").validate().resetForm(), 
    $("#dianpumc").val("").focus(), 
    $("#suoshuqy").val(""),
    $("#zhuyaofzr").val(""),
    $("#lianxifs").val(""),
    $("#paixu").val("1");

	//仓库列表获取
	Public.ajaxPost('../dataright/dt?action=dt&userName='+userName, {}, function(data){
		if(data.status === 200) {
			if(data.data&&data.data.items.length){		
				typeList = data.data.items;
				//thisPage.init(data);
				thisPage.init(typeList);
				thisPage.eventHandle();
				thisPage.gridInit();
				$start.hide();
				$searchField.hide();
			}
		} else {
			//parent.Public.tips({type: 1, content : data.msg});
		}
	});
}


var api = frameElement.api,
	oper = api.data.oper,
	rowData = api.data.rowData || {},
	callback = api.data.callback;
initPopBtns(), initField(), initEvent();