var queryConditions = {
	matchCon: "",
	outLocationId: -1,
	inLocationId: -1
},
	system = parent.SYSTEM,
	billRequiredCheck = 0,
	//billRequiredCheck = system.billRequiredCheck,
	THISPAGE = {
		init: function(a) {
			this.mod_PageConfig = Public.mod_PageConfig.init("transferList"), this.initDom(), this.loadGrid(), this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon"), this.$_beginDate = $("#beginDate").val(system.beginDate), this.$_endDate = $("#endDate").val(system.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
		},
		loadGrid: function() {
			function a(a, b, c) {
				var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
				return d
			}
			function b(a, b, c) {
				var d = a.join('<p class="line" />');
				return d
			}
			var c = Public.setGrid();
			queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val();
			var d = [{
				name: "operating",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: a,
				align: "center",
				title: !1
			}, {
				name: "billDate",
				label: "单据日期",
				width: 100,
				align: "center",
				title: !1
			}, {
				name: "billNo",
				label: "单据编号",
				width: 120,
				align: "center",
				title: !1
			}, {
				name: "goods",
				label: "商品",
				width: 200,
				formatter: b,
				title: !1
			}, {
				name: "qty",
				label: "数量",
				width: 100,
				formatter: b,
				align: "right",
				title: !1
			}, {
				name: "mainUnit",
				label: "单位",
				width: 100,
				formatter: b,
				title: !1
			}, {
				name: "outLocationName",
				label: "调出仓库",
				width: 100,
				formatter: b,
				title: !1
			}, {
				name: "inLocationName",
				label: "调入仓库",
				width: 100,
				formatter: b,
				title: !1
			}, {
				name: "checkName",
				label: "审核人",
				index: "checkName",
				width: 80,
				hidden: billRequiredCheck ? !1 : !0,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
			}, {
				name: "userName",
				label: "制单人",
				index: "userName",
				width: 80,
				fixed: !0,
				align: "center",
				title: !1
			}, {
				name: "description",
				label: "备注",
				width: 200
			}];
			this.mod_PageConfig.gridReg("grid", d), d = this.mod_PageConfig.conf.grids.grid.colModel, $("#grid").jqGrid({
				url: "../scm/invTf?action=list",
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: c.h,
				altRows: !0,
				gridview: !0,
				multiselect: !0,
				multiboxonly: !0,
				colModel: d,
				cmTemplate: {
					sortable: !1,
					title: !1
				},
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				rowNum: 100,
				rowList: [100, 200, 500],
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !0,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					repeatitems: !1,
					id: "id"
				},
				loadError: function(a, b, c) {},
				ondblClickRow: function(a, b, c, d) {
					$("#" + a).find(".ui-icon-pencil").trigger("click")
				},
				resizeStop: function(a, b) {
					THISPAGE.mod_PageConfig.setGridWidthByIndex(a, b, "grid")
				}
			}).navGrid("#page", {
				edit: !1,
				add: !1,
				del: !1,
				search: !1,
				refresh: !1
			}).navButtonAdd("#page", {
				caption: "",
				buttonicon: "ui-icon-config",
				onClickButton: function() {
					THISPAGE.mod_PageConfig.config()
				},
				position: "last"
			})
		},
		reloadData: function(a) {
			$("#grid").jqGrid("setGridParam", {
				url: "../scm/invTf?action=list",
				datatype: "json",
				postData: a
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var a = this;
			if ($(".grid-wrap").on("click", ".ui-icon-pencil", function(a) {
				a.preventDefault();
				var b = $(this).parent().data("id");
				parent.tab.addTabItem({
					tabid: "storage-transfers",
					text: "调拨单",
					url: "../scm/invTf?action=editTf&id=" + b
					//url: "../storage/transfers.jsp?id=" + b
				});
				$("#grid").jqGrid("getDataIDs");
				parent.salesListIds = $("#grid").jqGrid("getDataIDs")
			}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
				if (a.preventDefault(), Business.verifyRight("TF_DELETE")) {
					var b = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该调拨记录吗？", function() {
						Public.ajaxGet("../scm/invTf/delete?action=delete", {
							id: b
						}, function(a) {
							200 === a.status ? ($("#grid").jqGrid("delRowData", b), parent.Public.tips({
								content: "删除成功！"
							})) : parent.Public.tips({
								type: 1,
								content: a.msg
							})
						})
					})
				}
			}), $("#search").click(function() {
				queryConditions.matchCon = "请输入单据号或客户名或备注" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), queryConditions.outLocationId = -1, queryConditions.inLocationId = -1, THISPAGE.reloadData(queryConditions)
			}), $("#moreCon").click(function() {
				queryConditions.matchCon = a.$_matchCon.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), $.dialog({
					id: "moreCon",
					width: 480,
					height: 300,
					min: !1,
					max: !1,
					title: "高级搜索",
					button: [{
						name: "确定",
						focus: !0,
						callback: function() {
							queryConditions = this.content.handle(queryConditions), THISPAGE.reloadData(queryConditions), "" !== queryConditions.matchCon && a.$_matchCon.val(queryConditions.matchCon), a.$_beginDate.val(queryConditions.beginDate), a.$_endDate.val(queryConditions.endDate)
						}
					}, {
						name: "取消"
					}],
					resize: !1,
					content: "url:../storage/transfers_search?type=transfers",
					data: queryConditions
				})
			}), $("#add").click(function(a) {
				a.preventDefault(), Business.verifyRight("TF_ADD") && parent.tab.addTabItem({
					tabid: "storage-transfers",
					text: "调拨单",
					url: "../scm/invTf?action=initTf"
				})
			}), $(window).resize(function() {
				Public.resizeGrid()
			}), $(".wrapper").on("click", "#print", function(a) {
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
				c = b.join();
				// if(c != null && c == 1)
				// {
					if (!Business.verifyRight("TF_PRINT")) return void a.preventDefault();
					var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
						c = b.join(),
						d = c ? "&id=" + c : "",
						e = "../scm/invTf/toPdf?action=toPdf" + d;
					$(this).attr("href", e)
				// }
				// else
				// {
				// 	alert("请选择一条记录打印。");
				// }
			}), $(".wrapper").on("click", "#export", function(a) {
				if (!Business.verifyRight("TF_EXPORT")) return void a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join(),
					d = c ? "&id=" + c : "";
				for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);
				var f = "../scm/invTf/exportInvTf?action=exportInvTf" + d;
				$(this).attr("href", f)
			}), billRequiredCheck) {
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block");
				$(".wrapper").on("click", "#audit", function(a) {
					if (a.preventDefault(), Business.verifyRight("TF_CHECK")) {
						var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
							c = b.join();
						return c ? void Public.ajaxPost("../scm/invTf/batchCheckInvTf?action=batchCheckInvTf", {
							id: c
						}, function(a) {
							200 === a.status ? parent.Public.tips({
								content: a.msg
							}) : parent.Public.tips({
								type: 1,
								content: a.msg
							}), $("#search").trigger("click")
						}) : void parent.Public.tips({
							type: 2,
							content: "请先选择需要审核的项！"
						})
					}
				}), $(".wrapper").on("click", "#reAudit", function(a) {
					if (Business.verifyRight("TF_UNCHECK")) {
						a.preventDefault();
						var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
							c = b.join();
						return c ? void Public.ajaxPost("../scm/invTf/rsBatchCheckInvTf?action=rsBatchCheckInvTf", {
							id: c
						}, function(a) {
							200 === a.status ? parent.Public.tips({
								content: a.msg
							}) : parent.Public.tips({
								type: 1,
								content: a.msg
							}), $("#search").trigger("click")
						}) : void parent.Public.tips({
							type: 2,
							content: "请先选择需要取消审核的项！"
						})
					}
				})
			}
		}
	};
$(function() {
	THISPAGE.init()
});

