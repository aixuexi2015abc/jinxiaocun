!
	function (a) {
		function q(){
				userName = getUrlParam("userName");
				a.ajax({
					url: '../right/queryUserByName?userName='+userName,
					type: 'POST',
					dataType: 'json',
					success: function (data) {
						if (data.status == 200) {
							a_data = data.data,userName=a_data.userName,realName=a_data.name,dianpumc=a_data.dianpumc,dianpuid=a_data.dianpuid,userMobile=a_data.userMobile,uid=a_data.userId;
							a("#userName").val(userName);
							a("#realName").val(realName);
							a("#userMobile").val(userMobile);
							a("#inputUID").val(uid);
							a("#inputDianpumc").val(dianpumc);
							a("#inputDianpuid").val(dianpuid);
							dianPuList(dianpuid);
						} else {
							return null;
						}
					},
					error: function () {
						return null;
					}
				});
		}
		function b() {
			a.ajax({
				url: "../right/isMaxShareUser",
				dataType: "json",
				type: "POST",
				success: function (a) {
					if (200 == a.status) {
						var b = a.data;
						if (b.shareTotal >= b.totalUserNum) return Public.tips({
							type: 2,
							content: "共享用户已经达到上限值：" + b.totalUserNum
						}), !1
					}
				}
			})
		}
		function c() {
			var b = a.trim(a("#userName").val()),
				c = a('<span class="loading"><i class="ui-incon ui-icon-loading"></i>检查用户名...</span>').insertAfter(a("#userName"));
			a("#registerForm").data("onPost", !0), a.ajax({
				url: "../right/queryUserByName?userName=" + b,
				dataType: "json",
				type: "POST",
				success: function (b) {
					c.remove(), a("#registerForm").data("onPost", !1), 200 == b.status ? (a("#userName").data("valid", !1), h(a("#userName"), !1, "该用户名已被占用")) : (a("#userName").data("valid", !0), h(a("#userName"), !0))
				}
			})
		}
		function d() {
			var b = {
				userName: a.trim(a("#realName").val()),
				userNumber: a.trim(a("#userName").val()),
				userMobile: a.trim(a("#userMobile").val()),
				dianpuid: a.trim(dianpuCombo.getValue()),
				dianpumc: a.trim(dianpuCombo.getText()),
				uid: a.trim(a("#inputUID").val())
			};
			a("#registerForm").data("onPost", !0), a.ajax({
				url: "../right/editUser",
				data: b,
				type: "POST",
				dataType: "json",
				success: function (c) {
					// a("#registerForm").data("onPost", !1), 200 == c.status ? window.location.href = "authority_setting?userName=" + b.userNumber + "&right=0" : 
					Public.tips({
						type: 1,
						content: c.msg
					});
					
					window.location.href= "../settings/authority";
				},
				error: function () {
					a("#registerForm").data("onPost", !1), Public.tips({
						type: 1,
						content: "创建用户失败！请重试"
					})
				}
			})
		}
		function e(a) {
			var b, c = 0,
				d = a.length;
			return /\d/.test(a) && c++, /[a-z]/.test(a) && c++, /[A-Z]/.test(a) && c++, /[^a-zA-Z0-9]/.test(a) && c++, 6 > d ? b = 0 : d >= 6 && (b = c), b
		}
		function f(a) {
			for (var b = a.find("input:visible"), c = !0, d = 0, e = b.length; e > d; d++) {
				var f = b.eq(d);
				"undefined" != typeof f.data("valid") ? f.data("valid") || (c = !1, f.addClass("input-error")) : g(f) || (c = !1)
			}
			return c
		}
		function g(b) {
			var c = b.attr("id"),
				d = j[c];
			if (d && d.required) {
				var e = a.trim(b.val());
				for (var f in d) {
					var g, i = !0;
					if ("min" == f) {
						var m = d[f];
						m > e.length && (i = !1)
					} else if ("max" == f) {
						var m = d[f];
						m < e.length && (i = !1)
					} else if ("length" == f) {
						var m = d[f];
						m != e.length && (i = !1)
					} else if ("equalTo" == f) {
						var n = a.trim(a(d[f]).val());
						e != n && (i = !1)
					} else if (l[f]) l[f].test(e) || (i = !1);
					else if ("required" == f) e || (i = !1);
					else if (a.isFunction(d[f])) {
						var o = d[f];
						i = o()
					} else if ("ajaxValid" == f) var p = d[f];
					if (!i) return g = k[c][f], h(b, i, g), b.data("valid", !1), !1
				}
				return p ? a.ajax({
					type: "POST",
					dataType: "json",
					url: p.url,
					success: function (a) {
						return (i = p.success(a)) ? (h(b, i), b.data("valid", !0), !0) : (g = k[c][f], h(b, i, g), b.data("valid", !1), void 0)
					}
				}) : (h(b, i), b.data("valid", !0)), !0
			}
		}
		function h(b, c, d) {
			var e = b.parent().find(".valid-msg");
			0 == e.length && (e = a('<span class="valid-msg"><i /><span /></span>').insertAfter(b)), d = c ? "" : d, c ? (e.addClass("valid-success").removeClass("valid-error"), b.removeClass("input-error")) : (e.addClass("valid-error").removeClass("valid-success"), b.addClass("input-error")), e.show().find(">span").text(d)
		}
		function i(a) {
			a.parent().find("span.valid-msg").hide(), a.removeClass("input-error")
		}
		a(document).ready(function () {
			b(),q()
		});
		var j = {
			userName: {
				required: !0,
				min: 4,
				max: 20,
				userName: !0
			},
			dianpuid: {
				required: !0,
				dianpuid: !0
			},
			realName: {
				required: !0,
				realName: !0
			},
			userMobile: {
				required: !0,
				mobile: !0
			}
		},
			k = {
				userName: {
					required: "请输入用户名",
					min: "用户名长度应该为4-20位",
					max: "用户名长度应该为4-20位",
					userName: "用户名应该由英文字母或数字组成"
				},
				dianpuid: {
					required: "请选择店铺名称",
					dianpuid: "请选择店铺名称，如果还没有添加店铺，请先进入店铺管理模块添加店铺。"
				},
				realName: {
					required: "请输入真实姓名",
					realName: "请输入真实姓名"
				},
				userMobile: {
					required: "请输入常用手机",
					mobile: "请输入正确的手机号码"
				}
			},
			l = {
				userName: /^[a-zA-Z0-9]{4,20}$/,
				mobile: /^(13|15|18)[0-9]{9}$/,
				notAllNum: /[^0-9]+/,
				realName: /^[A-Za-z\u4e00-\u9fa5]+$/
			};
		a("#registerForm input").on("focus", function () {
			a(this).parent().find(".msg").addClass("msg-focus"), i(a(this)) && a("#pswStrength").show()
		}).on("blur", function () {
			"undefined" == typeof a(this).data("valid") ? (g(a(this)), "userName" == a(this).attr("id") && a(this).data("valid") && (a(this).removeData("valid"), i(a(this)), c())) : (a(this).data("valid") === !1 && a(this).addClass("input-error"), a(this).parent().find(".valid-msg").show()), a(this).parent().find(".msg").removeClass("msg-focus") && a("#pswStrength").hide()
		}).on("change", function () {
			a(this).removeData("valid")
		}), a("#registerBtn").on("click", function (b) {
			//b.preventDefault(), f(a("#registerForm")) && 
			!a("#registerForm").data("onPost") && d()
		})
	}(jQuery);
//用户信息中店铺列表绑定
$(document).ready(function () {
	// userName = getUrlParam("userName");
	// $.ajax({
	// 	url: '../right/queryUserByName?userName='+userName,
	// 	type: 'POST',
	// 	dataType: 'json',
	// 	success: function (data) {
	// 		if (data.status == 200) {
	// 			a = data.data,userName=a.userName,realName=a.name,dianpumc=a.dianpumc,dianpuid=a.dianpuid,userMobile=a.userMobile,uid=a.userId;
	// 			$("#userName").val(userName);
	// 			$("#realName").val(realName);
	// 			$("#userMobile").val(userMobile);
	// 			$("#inputUID").val(uid);
	// 			$("#inputDianpumc").val(dianpumc);
	// 			$("#inputDianpuid").val(dianpuid);
	// 			dianPuList(dianpuid);
	// 		} else {
	// 			return null;
	// 		}
	// 	},
	// 	error: function () {
	// 		return null;
	// 	}
	// });
});

//获取url中的参数
function getUrlParam(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = window.location.search.substr(1).match(reg); //匹配目标参数
	if (r != null) return unescape(r[2]); return null; //返回参数值
}

function dianPuList(dianpu) {
	$.ajax({
		url: '../right/findDianPu?action=findDianPu',
		type: 'POST',
		dataType: 'json',
		success: function (data) {
			if (data.status == 200) {
				var d = data.data;
				var index = 0;
				for (var i = 0; i < d.length; i++) {
					if (d[i].id == dianpu) { index = i; }
				}
				dianpuCombo = $("#dianpulist").combo({
					data: d,
					value: "id",
					text: "name",
					width: 332,
					defaultSelected: index,
					cache: !1,
					editable: !1,
					emptyOptions: !1
				}).getCombo();
			} else {
				return null;
			}
		},
		error: function () {
			return null;
		}
	});
}