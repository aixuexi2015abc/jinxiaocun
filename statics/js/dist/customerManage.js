function init() {
	void 0 !== cRowId ? Public.ajaxPost("../basedata/contact/query?action=query", {
		id: cRowId
	}, function(a) {
		200 == a.status ? (rowData = a.data, initField(), initEvent(), initGrid(rowData.links)) : parent.$.dialog({
			title: "系统提示",
			content: "获取客户数据失败，暂不能修改客户，请稍候重试",
			icon: "alert.gif",
			max: !1,
			min: !1,
			cache: !1,
			lock: !0,
			ok: "确定",
			ok: function() {
				return !0
			},
			close: function() {
				api.close()
			}
		})
	}) : (initField(), initEvent(), initGrid())
}
function initPopBtns() {
	var a = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm",
		name: a[0],
		focus: !0,
		callback: function() {
			return cancleGridEdit(), $_form.trigger("validate"), !1
		}
	}, {
		id: "cancel",
		name: a[1]
	})
}
function initValidator() {
	$_form.validator({
		rules: {
			type: [/^[a-zA-Z0-9\-_]*$/, "编号只能由数字、字母、-或_等字符组成"],
			unique: function(a, b, c) {
				var d = $(a).val();
				return $.ajax({
					url: "../basedata/contact/checkName?action=checkName",
					type: "get",
					data: "name=" + d,
					dataType: "json",
					success: function(a) {
						return -1 != a.status ? !0 : void parent.parent.Public.tips({
							type: 2,
							content: "存在相同的客户名称！"
						})
					}
				})
			},
			myRemote: function(a, b, c) {
				return c.old.value === a.value || $(a).data("tip") === !1 && a.value.length > 1 ? !0 : $.ajax({
					url: "../basedata/contact/getNextNo?action=getNextNo&type=-10",
					type: "post",
					data: "skey=" + a.value,
					dataType: "json",
					success: function(b) {
						if (b.data && b.data.number) {
							var c = a.value.length;
							a.value = b.data.number;
							var d = a.value.length;
							if (a.createTextRange) {
								var e = a.createTextRange();
								e.moveEnd("character", d), e.moveStart("character", c), e.select()
							} else a.setSelectionRange(c, d), a.focus();
							$(a).data("tip", !0)
						} else $(a).data("tip", !1)
					}
				})
			}
		},
		messages: {
			required: "请填写{0}"
		},
		fields: {
			number: {
				rule: "add" === oper ? "required; type; myRemote" : "required; type",
				timely: 3
			},
			name: "required;"
		},
		display: function(a) {
			return $(a).closest(".row-item").find("label").text()
		},
		valid: function(a) {
			var b = $.trim($("#name").val());
			Public.ajaxPost("../basedata/contact/checkName?action=checkName", {
				name: b,
				id: cRowId
			}, function(a) {
				-1 == a.status ? parent.$.dialog.confirm('客户名称 "' + b + '" 已经存在！是否继续？', function() {
					postCustomerData()
				}, function() {}) : postCustomerData()
			})
		},
		ignore: ":hidden",
		theme: "yellow_bottom",
		timely: 1,
		stopOnError: !0
	})
}
function postCustomerData() {
	var a = "add" == oper ? "新增客户" : "修改客户",
		b = getCustomerData(),
		c = b.firstLink || {};
	delete b.firstLink, Public.ajaxPost("../basedata/contact/" + ("add" == oper ? "add" : "update"), b, function(d) {
		if (200 == d.status) {
			if (parent.parent.Public.tips({
				content: a + "成功！"
			}), callback && "function" == typeof callback) {
				var e = d.data.id;
				d = b, d.id = e, d.customerType = d.cCategoryName, d.contacter = c.linkName || "", d.place = c.linkPlace || "", d.mobile = c.linkMobile || "", d.telephone = c.linkPhone || "", d.linkIm = c.linkIm || "", d.deliveryAddress = c.address || "", d.province = c.province || "", d.city = c.city || "", d.county = c.county || "", callback(d, oper, window)
			}
		} else parent.parent.Public.tips({
			type: 1,
			content: a + "失败！" + d.msg
		})
	})
}
function getCustomerData() {
	var a = getEntriesData(),
		b = a.entriesData,
		c = {
			id: cRowId,
			number: $.trim($("#number").val()),
			name: $.trim($("#name").val()),
			cCategory: categoryCombo.getValue(),
			cCategoryName: categoryCombo.getText(),
			cLevel: levelCombo.getValue(),
			cLevelName: levelCombo.getText(),
			beginDate: $("#date").val(),
			amount: Public.currencyToNum($("#receiveFunds").val()),
			periodMoney: Public.currencyToNum($("#periodReceiveFunds").val()),
			linkMans: JSON.stringify(b),
			remark: $("#note").val() == $("#note")[0].defaultValue ? "" : $("#note").val()
		};

	return c.firstLink = a.firstLink, c
}
function getEntriesData() {
	for (var a = {}, b = [], c = $grid.jqGrid("getDataIDs"), d = !1, e = 0, f = c.length; f > e; e++) {
		var g, h = c[e],
			i = $grid.jqGrid("getRowData", $grid.jqGrid("getDataIDs")[-1])[e];
		if ("" == i.name) break;
		g = {
			linkName: i.name,
			linkMobile: i.mobile,
			linkPhone: i.phone,
			linkPlace: i.place,
			linkIm: i.im,
			address: i.addressStr,
			linkFirst: "是" == i.first ? 1 : 0
		};
		var j = $("#" + h).data("addressInfo") || {};
		if(j.province == undefined)
		{
			var lianxidizhi = i.lianxidizhi;
			var lxdzArry = lianxidizhi.split(',');
			//省
			j.province = lxdzArry[0];
			//市
			j.city = lxdzArry[1];
			//区
			j.county = lxdzArry[2];
			//街道
			j.address = lxdzArry[3];
		}
		g.province = j.province, g.city = j.city, g.county = j.county, g.address = j.address, "edit" == oper ? g.id = -1 != $.inArray(h, linksIds) ? h : 0 : g.id = 0, "是" == i.first && (d = !0, a.firstLink = g), b.push(g)
	}
	return !d && b.length > 0 && (b[0].linkFirst = 1, a.firstLink = b[0]), a.entriesData = b, a
}
function getTempData(a) {
	for (var b, c = $.extend({
		contacter: "",
		mobile: "",
		place:"",
		telephone: "",
		linkIm: ""
	}, a), d = c.links, e = 0; e < d.length; e++) if (d[e].first) {
		b = d[e];
		break
	}
	return c.customerType = categoryData[c.cCategory] && categoryData[c.cCategory].name || "", c.firstLink = b, c
}
function initField() {
	if ($("#note").placeholder(), "edit" == oper) {
		//if ($("#number").val(rowData.number), $("#name").val(rowData.name), $("#category").data("defItem", ["id", rowData.cCategory + ""]), rowData.beginDate) {
		if ($("#number").val(rowData.number), $("#name").val(rowData.name), $("#category").data("defItem", ["id", rowData.cCategory]), rowData.beginDate) {
			var a = new Date(rowData.beginDate),
				b = a.getFullYear(),
				c = 1 * a.getMonth() + 1,
				d = a.getDate();
			if(b=="1970") {$("#date").val("");}
			else
			{$("#date").val(b + "-" + c + "-" + d);}
		}
		void 0 != rowData.amount && $("#receiveFunds").val(Public.numToCurrency(rowData.amount)), void 0 != rowData.periodMoney && $("#periodReceiveFunds").val(Public.numToCurrency(rowData.periodMoney)), rowData.remark && $("#note").val(rowData.remark);
		if($("#note").val() != "添加备注信息" && $("#note").val() != "")
		{
			$("#note").removeClass("ui-input-ph");
		}
	} else $("#date").val(parent.parent.SYSTEM.startDate);
	api.opener.parent.SYSTEM.isAdmin || api.opener.parent.SYSTEM.rights.AMOUNT_OUTAMOUNT || ($("#receiveFunds").closest("li").hide(), $("#periodReceiveFunds").closest("li").hide());
	var e = rowData.cLevel;
	levelCombo = $("#customerLevel").combo({
		data: [{
			id: 0,
			name: "零售客户"
		}, {
			id: 1,
			name: "批发客户"
		}, {
			id: 2,
			name: "VIP客户"
		}, {
			id: 3,
			name: "折扣等级一"
		}, {
			id: 4,
			name: "折扣等级二"
		}],
		value: "id",
		text: "name",
		width: 210,
		defaultSelected: e || 0
	}).getCombo()
}
function initEvent() {
	var a = "customertype";
	categoryCombo = Business.categoryCombo($("#category"), {
		defaultSelected: $("#category").data("defItem") || void 0,
		editable: !0,
		trigger: !0,
		width: 210,
		ajaxOptions: {
			formatData: function(b) {
				categoryData = {};
				var c = Public.getDefaultPage();
				if (200 == b.status) {
					for (var d = 0; d < b.data.items.length; d++) {
						var e = b.data.items[d];
						categoryData[e.id] = e
					}
					return c.SYSTEM.categoryInfo = c.SYSTEM.categoryInfo || {}, c.SYSTEM.categoryInfo[a] = b.data.items, b.data.items.unshift({
						id: 0,
						name: "（空）"
					}), b.data.items
				}
				return []
			}
		}
	}, a);
	var b = $("#date");
	b.blur(function() {
		"" == b.val() && b.val(parent.parent.SYSTEM.startDate)
	}), b.datepicker({
		onClose: function(a, c) {
			var d = /^\d{4}-((0?[1-9])|(1[0-2]))-\d{1,2}/;
			d.test(b.val()) || b.val("")
		}
	}), $("#receiveFunds").keypress(Public.numerical).focus(function() {
		this.value = Public.currencyToNum(this.value), $(this).select()
	}).blur(function() {
		this.value = Public.numToCurrency(this.value)
	}), $("#periodReceiveFunds").keypress(Public.numerical).focus(function() {
		this.value = Public.currencyToNum(this.value), $(this).select()
	}).blur(function() {
		this.value = Public.numToCurrency(this.value)
	}), $(".grid-wrap").on("click", ".ui-icon-ellipsis", function(a) {
		a.preventDefault();
		var b = $(this).siblings(),
			c = $(this).closest("tr"),
			d = c.data("addressInfo");
		if(d == undefined)
		{
			//已有地址显示
			var yysj = $(this).parent().parent().next().attr("title");
			if(yysj != "")
			{
				var lianxidizhi = yysj;
				var lxdzArry = lianxidizhi.split(',');
				d = {
					province: lxdzArry[0],
					city: lxdzArry[1],
					county: lxdzArry[2],
					address: lxdzArry[3]
				};
			}
		}
		parent.$.dialog({
			title: "联系地址",
			content: "url:../settings/addressManage",
			data: {
				rowData: d,
				callback: function(a, d) {
					if (a) {
						var e = {};
						e.province = a.province || "", e.city = a.city || "", e.county = a.area || "", e.address = a.address || "", b.val(e.province + e.city + e.county + e.address), c.data("addressInfo", e)
					}
					d.close()
				}
			},
			width: 640,
			height: 210,
			min: !1,
			max: !1,
			cache: !1,
			lock: !0
		})
	}), $(document).on("click.cancle", function(a) {
		var b = a.target || a.srcElement;
		!$(b).closest("#grid").length > 0 && cancleGridEdit()
	}), bindEventForEnterKey(), initValidator()
}
function addCategory() {
	Business.verifyRight("BUTYPE_ADD") && parent.$.dialog({
		title: "新增客户类别",
		content: "url:../settings/customer_category_manage",
		data: {
			oper: "add",
			callback: function(a, b, c) {
				categoryCombo.loadData("../basedata/assist?action=list&typeNumber=customertype", ["id", a.id]), c && c.api.close()
			}
		},
		width: 400,
		height: 100,
		max: !1,
		min: !1,
		cache: !1,
		lock: !1
	})
}
function bindEventForEnterKey() {
	Public.bindEnterSkip($("#base-form"), function() {
		$("#grid tr.jqgrow:eq(0) td:eq(0)").trigger("click")
	})
}
function initGrid(a) {
	if (a || (a = []), a.length < 3) for (var b = 3 - a.length, c = 0; b > c; c++) a.push({});
	
	a.push({}), $grid.jqGrid({
		data: a,
		datatype: "local",
		width: 598,
		gridview: !0,
		onselectrow: !1,
		colModel: [{
			name: "name",
			label: "联系人",
			width: 60,
			title: !1,
			editable: !0
		}, {
			name: "mobile",
			label: "手机",
			width: 80,
			title: !1,
			editable: !0
		}
		, {
			name: "phone",
			label: "座机",
			width: 80,
			title: !1,
			editable: !0
		}, {
			name: "place",
			label: "职位",
			width: 80,
			title: !1,
			editable: !0
		}, 
		{
			name: "im",
			label: "QQ/MSN",
			width: 82,
			title: !1,
			editable: !0
		}, 
		{
			name: "addressStr",
			label: "联系地址",
			width: 140,
			title: !0,
			formatter: addressFmt,
			classes: "ui-ellipsis",
			editable: !0,
			edittype: "custom",
			editoptions: {
				custom_element: addressElem,
				custom_value: addressValue,
				handle: addressHandle,
				trigger: "ui-icon-ellipsis"
			}
		},
		{
			name: "lianxidizhi",
			label: "联系地址",
			width: 140,
			title: !0,
			formatter: addressFmt2,
			editable: !1,
			hidden: !0
		},
		 {
			name: "first",
			label: "首要联系人",
			width: 70,
			title: !1,
			formatter: isFirstFormate,
			editable: !0,
			edittype: "select",
			editoptions: {
				value: {
					1: "是",
					0: "否"
				}
			}
		}
	]
		,
		cmTemplate: {
			sortable: !1
		},
		shrinkToFit: !0,
		forceFit: !0,
		cellEdit: true,
		cellsubmit: "clientArray",
		localReader: {
			root: "items",
			records: "records",
			repeatitems: !0
		},
		loadComplete: function(a) {
			if ($grid.height() > 124 ? $grid.setGridHeight("124") : $grid.setGridHeight("auto"), $grid.setGridWidth(598), "add" != oper) {
				if (!a || !a.items) return void(linksIds = []);
				linksIds = [];
				for (var b = a.items, c = 0; c < b.length; c++) {
					var d = b[c];
					if (d.id || (d.address != "" && typeof(d.address) != undefined && d.address != "undefined")) {
						linksIds.push(d.id + "");
						var e = {
							province: d.province,
							city: d.city,
							county: d.county,
							address: d.address
						};
						$("#" + d.id).data("addressInfo", e)
					}
				}
			}
		},
		afterEditCell: function(a, b, c, d, e) {
			$("#" + a).find("input").val(c);
		},
		afterSaveCell: function(a, b, c, d, e) {
			if ("first" == b && (c = "boolean" == typeof c ? c ? "1" : "0" : c, "1" === c)) for (var f = $grid.jqGrid("getDataIDs"), g = 0; g < f.length; g++) {
				var h = f[g];
				h != a && $grid.jqGrid("setCell", h, "first", "0")
			}
		}
	})
}
function addressFmt(a, b, c) {
	if (a) return a;
	var d = {};
	return d.province = c.province || "", d.city = c.city || "", d.county = c.county || "", d.address = c.address || "", $("#" + c.id).data("addressInfo", d), d.province + d.city + d.county + d.address || "&#160;"
}
function addressFmt2(a, b, c) {
	if (a) return a;
	var d = {};
	return d.province = c.province || "", d.city = c.city || "", d.county = c.county || "", d.address = c.address || "", $("#" + c.id).data("addressInfo", d), d.province +','+ d.city  +','+  d.county  +','+  d.address || "&#160;"
}
function addressElem(a, b) {
	var c = $(".address")[0];
	return c
}
function addressValue(a, b, c) {
	if ("get" === b) {
		var d = $.trim($(".address").val());
		return "" !== d ? d : ""
	}
	"set" === b && $("input", a).val(c)
}
function addressHandle() {
	$(".hideFile").append($(".address").val("").unbind("focus.once"))
}
function phoneCheck(a, b) {
	var c = /^(13|18|15|14)[\d]{9}$/;
	return c.test(a) ? [!0, ""] : [!1, "请填写正确的手机号码"]
}
function telephoneCheck(a, b) {
	var c = /^([\d]+-){1,2}[\d]+$/;
	return c.test(a) ? [!0, ""] : [!1, "请填写正确的座机号码，格式为0754-1234567或086-0754-1234567。"]
}
function isFirstFormate(a, b, c) {
	return a = "boolean" == typeof a ? a ? "1" : "0" : a, "1" === a ? "是" : "&#160;"
}
function cancleGridEdit() {
	null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(a) {
	var b = [{}, {}, {}, {}];
	$("#name").val(""), $("#date").val(""), $("#receiveFunds").val(""), $("#note").val(""), $("#periodReceiveFunds").val(""), $grid.jqGrid("clearGridData").jqGrid("setGridParam", {
		data: b
	}).trigger("reloadGrid"), $("#number").val(Public.getSuggestNum(a.number)).focus().select()
}
var curRow, curCol, curArrears, api = frameElement.api,
	oper = api.data.oper,
	cRowId = api.data.rowId,
	rowData = {},
	linksIds = [],
	callback = api.data.callback,
	defaultPage = Public.getDefaultPage(),
	categoryCombo, levelCombo, categoryData = {},
	$grid = $("#grid"),
	$_form = $("#manage-form");
initPopBtns(), init();