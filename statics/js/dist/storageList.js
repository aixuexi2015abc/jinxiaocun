function initEvent() {
	$("#btn-add").click(function(a) {
		a.preventDefault(), Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
	}), $("#btn-disable").click(function(a) {
		a.preventDefault();
		var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
		return b && 0 != b.length ? void handle.setStatuses(b, !0) : void parent.Public.tips({
			type: 1,
			content: " 请先选择要禁用的仓库！"
		})
	}), $("#btn-enable").click(function(a) {
		a.preventDefault();
		var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
		return b && 0 != b.length ? void handle.setStatuses(b, !1) : void parent.Public.tips({
			type: 1,
			content: " 请先选择要启用的仓库！"
		})
	}), $("#btn-import").click(function(a) {
		a.preventDefault()
	}), $("#btn-export").click(function(a) {
		a.preventDefault()
	}), $("#btn-print").click(function(a) {
		a.preventDefault()
	}), $("#btn-refresh").click(function(a) {
		a.preventDefault(), $("#grid").trigger("reloadGrid")
	}), $("#grid").on("click", ".operating .ui-icon-pencil", function(a) {
		if (a.preventDefault(), Business.verifyRight("INVLOCTION_UPDATE")) {
			var b = $(this).parent().data("id");
			handle.operate("edit", b)
		}
	}), $("#grid").on("click", ".operating .ui-icon-trash", function(a) {
		if (a.preventDefault(), Business.verifyRight("INVLOCTION_DELETE")) {
			var b = $(this).parent().data("id");
			handle.del(b)
		}
	}), $("#grid").on("click", ".set-status", function(a) {
		if (a.preventDefault(), Business.verifyRight("INVLOCTION_UPDATE")) {
			var b = $(this).data("id"),
				c = !$(this).data("delete");
			handle.setStatus(b, c)
		}
	}), $(window).resize(function() {
		Public.resizeGrid()
	})
}
function initGrid() {
	var a = ["操作", "仓库编号", "仓库名称", "状态"],
		b = [{
			name: "operate",
			width: 60,
			fixed: !0,
			align: "center",
			formatter: Public.operFmatter
		}, {
			name: "locationNo",
			index: "locationNo",
			width: 150
		}, {
			name: "name",
			index: "name",
			width: 350
		}, {
			name: "delete",
			index: "delete",
			width: 100,
			formatter: statusFmatter,
			align: "center"
		}];
	$("#grid").jqGrid({
		//url: "../basedata/invlocation.do?action=list&isDelete=2",
		url: "../basedata/invlocation?action=list&isDelete=2",
		datatype: "json",
		height: Public.setGrid().h,
		altRows: !0,
		gridview: !0,
		colNames: a,
		colModel: b,
		autowidth: !0,
		pager: "#page",
		viewrecords: !0,
		cmTemplate: {
			sortable: !1,
			title: !1
		},
		page: 1,
		rowNum: 100,
		rowList: [100, 200, 500],
		shrinkToFit: !1,
		cellLayout: 8,
		jsonReader: {
			root: "data.rows",
			records: "data.records",
			total: "data.total",
			repeatitems: !1,
			id: "id"
		},
		loadComplete: function(a) {
			if (a && 200 == a.status) {
				var b = {};
				a = a.data;
				for (var c = 0; c < a.rows.length; c++) {
					var d = a.rows[c];
					b[d.id] = d
				}
				$("#grid").data("gridData", b), 0 == a.rows.length && parent.Public.tips({
					type: 2,
					content: "没有仓库数据！"
				})
			} else parent.Public.tips({
				type: 2,
				content: "获取仓库数据失败！" + a.msg
			})
		},
		loadError: function() {
			parent.Public.tips({
				type: 1,
				content: "操作失败了哦，远程服务器数据获取失败！"
			})
		}
	})
}
function statusFmatter(a, b, c) {
	var d = a === !0 ? "已禁用" : "已启用",
		e = a === !0 ? "ui-label-default" : "ui-label-success";
	return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + "</span>"
}
var handle = {
	operate: function(a, b) {
		if ("add" == a) var c = "新增仓库",
			d = {
				oper: a,
				callback: this.callback
			};
		else var c = "修改仓库",
			d = {
				oper: a,
				rowData: $("#grid").data("gridData")[b],
				callback: this.callback
			};
		$.dialog({
			title: c,
			//content: "url:storage-manage.jsp",
			content: "url:storage_manage",
			data: d,
			width: 400,
			height: 160,
			max: !1,
			min: !1,
			cache: !1,
			lock: !0
		})
	},
	callback: function(a, b, c) {
		var d = $("#grid").data("gridData");
		d || (d = {}, $("#grid").data("gridData", d)), d[a.id] = a, "edit" == b ? ($("#grid").jqGrid("setRowData", a.id, a), c && c.api.close()) : ($("#grid").jqGrid("addRowData", a.id, a, "last"), c && c.resetForm(a))
	},
	del: function(a) {
		$.dialog.confirm("删除的仓库将不能恢复，请确认是否删除？", function() {
			Public.ajaxPost("../basedata/invlocation/delete", {
				locationId: a
			}, function(b) {
				b && 200 == b.status ? (parent.Public.tips({
					content: "仓库删除成功！"
				}), $("#grid").jqGrid("delRowData", a)) : parent.Public.tips({
					type: 1,
					content: "仓库删除失败！" + b.msg
				})
			})
		})
	},
	setStatus: function(a, b) {
		a && Public.ajaxPost("../basedata/invlocation/disable", {
			locationId: a,
			disable: Number(b)
		}, function(c) {
			c && 200 == c.status ? (parent.Public.tips({
				content: "仓库状态修改成功！"
			}), $("#grid").jqGrid("setCell", a, "delete", b)) : parent.Public.tips({
				type: 1,
				content: "仓库状态修改失败！" + c.msg
			})
		})
	},
	setStatuses: function(a, b) {
		a && 0 != a.length && Public.ajaxPost("../basedata/invlocation/disable", {
			locationIds: JSON.stringify(a),
			disable: Number(b)
		}, function(c) {
			if (c && 200 == c.status) {
				parent.Public.tips({
					content: "仓库状态修改成功！"
				});
				for (var d = 0; d < a.length; d++) {
					var e = a[d];
					$("#grid").jqGrid("setCell", e, "delete", b)
				}
			} else parent.Public.tips({
				type: 1,
				content: "仓库状态修改失败！" + c.msg
			})
		})
	}
};
initEvent(), initGrid();