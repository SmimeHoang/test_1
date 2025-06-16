<?php

//$bclass='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only';
$bclass = '';
$style = 'style="width:75px;height:75px;font-size:150%;"';

?>
<html>

<head>
	<style>
		section {display: none;}label.tab_lb { display: inline-block; margin: 0 0 -1px; padding: 5px 15px 5px 15px; text-align: center; color: black; border: 1px solid transparent;}
		label.tab_lb:before { font-weight: normal; margin-right: 10px;}
		label.tab_lb:hover { color: #8470FF; cursor: pointer;}
		input:checked+label.tab_lb { color: #04AA6D; border: 1px solid white; font-weight: bolder; border-top: 4px solid #04AA6D; border-bottom: 2px solid wheat;}
		#tab0:checked~#content0,#tab1:checked~#content1,#tab2:checked~#content2,#tab3:checked~#content3,#tab4:checked~#content4,#tab5:checked~#content5,#tab6:checked~#content6,#tab7:checked~#content7,#tab8:checked~#content8,#tab9:checked~#content9,#tab10:checked~#content10,#tab11:checked~#content11,#tab12:checked~#content12,#tab13:checked~#content13,#tab14:checked~#content14,#tab15:checked~#content15,#tab16:checked~#content16,#tab17:checked~#content17,#tab18:checked~#content18 { display: block;}
		.ccenter { text-align: center; padding: 2px; font-size: 14px;}
		.hc___left { text-align: left; padding-left: 4px; font-size: 14px;}
		.hide { display: none; height: 14px;}
		.show { display: block; height: 14px;}
		#judgement_NG { margin-bottom: 10px;}
		h3 { font-size: 130%;}
		#msgbug { position: absolute;}
		div.radio,div.checkbox { padding: 0px; border-radius: 5px; background: white;}
		div.radioreadonly,div.checkboxreadonly { padding: 0px; border-radius: 5px; background: #DDDDDD;}
		#msg_box_view { background-color: khaki; border-radius: 20px; border-style: dotted; display: none; position: absolute; text-align: left; padding: 20px; min-width: 500px; z-index: 2;}
		.msg_input_RFID_view { background-color: wheat; padding: 5px; float: left;}.msg_input_RFID_view div { float: left;}
		.find_list_RFID input { font-weight: bold;}.find_list_RFID div { padding: 0px 5px 5px 5px;}
		.RFID_input_view input,.RFID_input_view div { margin-top: 1%; margin-left: 1%; float: left;}
		.scan_find_name { text-align: center;}
		.scan_find_input { text-align: center;}
		.scan_count { padding-top: 5px; text-align: center;}
		.RFID_view { width: 100%; height: 195px; background-color: seashell; border-radius: 20px; float: left; overflow-x: auto; padding: 6px;}
		.RFID_view fieldset { display: block; padding: 8px 0; border: 0; border-top: 1px solid #DDD; width: 98%;}
		.RFID_view fieldset:last-of-type { margin-bottom: 0px;}
		.RFID_view legend { display: table; width: auto; position: relative; margin: auto; padding: 3px 10px; font-size: 14px; text-align: center; border-radius: 5px;}
		.RFID_view_1 { background-color: #EEE8AA;}
		.RFID_view_2 { background-color: #87CEFA;}
		.RFID_view_3 { background-color: #6bb11a;}
		.RFID_view_4 { background-color: darkorange;}
		.RFID_view_5 { background-color: #CD853F;}
		.RFID_view_6 { background-color:rgb(0, 0, 0); color: white;}
		.RFID_view_7 { background-color: #98F5FF;}
		.RFID_PROCESS { margin-top: -5px; padding-bottom: -10px;}
		.RFID_view .RFID_btn { float: left; width: 93px; margin: 5px; padding-top: 6px; padding-bottom: 6px; border-radius: 8px; border: none; color: white; cursor: pointer; text-align: center; font-size: 14px;}
		.RFID_view .RFID_btn:not(.RFID_btn_checked):hover { transform: scale(1.07);}
		.RFID_btn_checked { box-shadow: 0px 5px 5px #550000;transform: scale(1.2);}
		.RFID_view .RFID_Alignment_FIND { background-color: Gray;}
		.RFID_view .RFID_Alignment { background-color: #04AA6D;}
		.RFID_view_data { padding: 5px; float: left; min-height: 300px; border-radius: 10px; background-color: seashell; overflow-x: auto; width: 100%;}
		.RFID_view_data div { float: left; display: inline;}
		.RFID_view_data table,.RFID_view_data tr,.RFID_view_data th,.RFID_view_data td { font-size: 14px; line-height: 1.5; vertical-align: top; border: 1px solid #ccc; border-collapse: collapse; font-family: arial, sans-serif;}
		.RFID_view_data th { padding: 4px; font-weight: bold;}
		.RFID_view_data td { padding: 2px 4px; text-align: left;}.inspectin_view table,.inspectin_view tr,.inspectin_view td,.inspectin_view th{ border: 1px solid transparent; padding: 2px 0px 2px 3px; font-size: 14px;}
		.RFID_view_data td.noborder,.RFID_view_data th.noborder { border: 1px solid transparent; padding: 2px 0px 2px 3px; font-size: 14px;}
		.RFID_view_data td.notright,.RFID_view_data th.notright { border-right-color: #ccc;}
		.RFID_view_data .used_num { font-weight: bold; color: blue;}
		.RFID_view_data .good_num { font-weight: bold; color: green;}
		.RFID_view_data .bad_num { font-weight: bold; color: brown;}
		.RFID_view_data .rem_num { font-weight: bold; color: blueviolet;}
		tr.chil-tr { height: 26px;}
		.RFID_view_data_2 table,.RFID_view_data_2 tr,.RFID_view_data_2 th,.RFID_view_data_2 td,.RFID_view_data_3 table,.RFID_view_data_3 tr,.RFID_view_data_3 th,.RFID_view_data_3 td {font-size: 12px;height: 23px;padding: 1px 4px;}.RFID_view_data_2 th, .RFID_view_data_3 th {text-align: center;}.RFID_view_data_3{float: left; margin-left: 5px;}.RFID_view_data_3 p{font-weight: bold;}.btn_add_del { top: -17px; right: 3px; position: relative; width: 0.1px; height: 0.1px; transform: scale(130);}
		.btn_add_del:hover { transform: scale(200);}
		.group_process { top: -30px; right: 3px; position: relative; width: 0.1px; height: 0.1px; transform: scale(130);}
		.alert_msg { width: 100%; font-size: 120%; color: red; background: transparent; text-align: center; display: none; margin-top: 5px;}
		#canvas { height: auto; width: 100%; background-color: silver;}
		.stock_confirmation { font-size: 14px;}
		.stock_confirmation td { height: 27.3px;}
		.btn_inspection_start { width: 100%; margin: 2px; text-align: center; padding: 2px; font-size: 14px; background-color: #04AA6D;}
		.btn_inspection_end { width: 100%; margin: 2px; text-align: center; padding: 2px; font-size: 14px; background-color: Maroon;}
		.btn_inspection_result_remaining { width: 100%; margin: 2px; text-align: center; padding: 2px; font-size: 14px; background-color: #CD853F;}.td_inspectin_cav div{ width: 18%; padding: 2px;}.td_inspectin_cav input{ height: 28px; background-color: #04AA6D;}.inspectin_cav_checked{ box-shadow: 0px 5px 5px #FEF889; transform: scale(0.9);}
		.img-zoom-container {position: relative;}
		.img-zoom-container div {float: left;}
		.img-zoom-container img {width: 300px; max-height: 400px;}
		.btn_zoom_img {margin-bottom: 5px;}
		.btn_zoom_img button{ text-align: center; padding: 5px 10px; cursor: pointer; outline: none; color: #fff;  border: none; border-radius: 15px; margin: 0 5px 5px 5px; width: 100px;}
		.btn_not_check{background-color: #04AA6D;box-shadow: 0 9px #999;}
		.btn_checked{ background-color: #2196F3; box-shadow: 0 5px #666; transform: translateY(4px);}
		.img-zoom-lens {position: absolute; border: 1px solid red; width: 40px; height: 40px;}
		.img-zoom-result {border: 1px solid #d4d4d4; width: 400px; height: 400px;}
	</style>
	<script type="text/javascript">
		const judgement_id = wbn_id;
		var tabindex_no = 0,
			list_margin_bottom = [],
			today = fb_format_date(new Date(), "YYYY-MM-DD"),
			xlsnum = "",
			workitem_name = "",
			workitem_class = "",
			outbreak_outflow = "必要",
			processing_position = parseInt($("#wbn_processing_position" + judgement_id).val()),
			bl_change = false,
			height_save, wh = $(window).height() - 160,
			ww = $(window).width() - 5,
			cav_no, is_scan = false,
			obj_data = {},
			obj_RFID_data = {},
			obj_find_list_RFID_data = {},
			array_molding_id = [],
			find_input_start = null,
			find_input_end = null,
			RFID_input_check = false,
			RFID_input_error_list = "",
			sum_hgpd_rfid = 0,
			inspection_result_bad_now_view,
			inspection_result_bad_key,
			html_tab;
		worklist.forEach((value, key) => {
			if (value.workitem_name == "保留処理") {
				xlsnum = value.workitem_no;
				workitem_name = value.workitem_name;
				workitem_class = value.workitem_class;
			}
		});
		$(document).ready(function() {
			if ($('#username').val()) {
				var datas = {
					ac: "GetDataTab",
					placename: placename,
					placeid: placeid,
					wbn_id: judgement_id,
					mfc_module: 'MissingDefect',
					cavno: $("#CAVno" + wbn_id).val(),
					wip_palce: wip_palce,
					fh_palce: fh_palce,
					vd_palce: vd_palce,
					oh_palce: oh_palce,
				}
				obj_data = {};
				fb_loading(true);
				$.ajax({
					type: 'GET',
					url: "",
					dataType: 'json',
					data: datas,
					success: function(d) {
						obj_data = d;
						view_judgeent();
						fb_loading(false);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
						fb_loading(false);
						return;
					}
				});
			}
		});
		$(function() {
			$(document).on('keyup', '#inspection_total', function(e) {
				fb_auto_rate();
			});
			$(document).on('keyup', '#inspection_bad', function(e) {
				fb_auto_rate();
			});
		});

		function view_judgeent() {
			$("#lb_login_person").html("ログイン者: " + $('#username').val() + "　|　");
			$("#login_person").val($('#username').val());
			tabindex_no = 0;
			let msg_box;
			let due_dt_date = new Date($('#date' + judgement_id).val());
			due_dt_date.setDate(due_dt_date.getDate() + 14);
			due_dt_date = due_dt_date.toLocaleDateString('ja-JP');
			if (bl_change) {
				$("#btn_fix").hide();
				if ($("#wbn_rank" + judgement_id).val() == 1) {
					if (processing_position == 7) {
						processing_position = 3;
					}
				} else if ($("#wbn_rank" + judgement_id).val() == 3) {
					if (processing_position == 3) {
						processing_position = 0;
					} else if (processing_position == 7) {
						processing_position = 5;
					} else {
						processing_position = processing_position - 1;
					}
				} else {
					processing_position = processing_position - 1;
				}
			} else if ($("#wbn_rank" + judgement_id).val() != 3 && $("#wbn_rank" + judgement_id).val() != 1) {
				if (processing_position == 0 || processing_position == 3 || processing_position == 7) {
					$("#btn_fix").hide();
				}
			}
			//html_tab = '';
			let html_tab_input = '<div style="text-align: left;float: left;"><label>キャビ番号：</label>';
			let html_tab_content = '';
			if (Object.keys(obj_data['data']).length > 0) {
				obj_data['data'].forEach((value, index) => {
					let td_fh = '';
					let td_wip = '';
					let td_vd = '';
					let td_wip_P = '';
					let error_fh = '';
					let error_wip = '';
					let error_vd = '';
					let error_wip_P = '';
					cav_no = Object.keys(obj_data['data']).length == 1 ? '' : value["wbn_cavino"];
					if (value["fh_numberoftargets"] == "") {
						value["wbn_fh_testnum"] = '';
					}
					if (value["wip_numberoftargets"] == "") {
						value["wbn_wip_testnum"] = '';
					}
					if (value["wip_numberoftargets_P"] == "") {
						value["wbn_wip_testnum_P"] = '';
					}
					if (value["vd_numberoftargets"] == "") {
						value["wbn_vd_testnum"] = '';
					}
					if (processing_position == 1 && !bl_change) {
						td_fh = `	<td class="das_tanto"><input title="number" class="form-control hc_right class_RFID_input" id= "fh_numberoftargets` + cav_no + `" value="` + value["fh_numberoftargets"] + `" tabindex="` + fb_get_tabindex_no() + `"/></td>
											<td><input class="form-control hc_right" readonly/></td>
											<td><input class="form-control hc_right" readonly/></td>`;
						td_wip = `<td  class="das_tanto"><input title="number" class="form-control hc_right class_RFID_input" id= "wip_numberoftargets` + cav_no + `" value="` + value["wip_numberoftargets"] + `" tabindex="` + fb_get_tabindex_no() + `"/></td>
											<td><input class="form-control hc_right" readonly/></td>
											<td><input class="form-control hc_right" readonly/></td>`;
						td_vd = `<td class="das_tanto"><input title="number" class="form-control hc_right class_RFID_input" id= "vd_numberoftargets` + cav_no + `" value="` + value["vd_numberoftargets"] + `" tabindex="` + fb_get_tabindex_no() + `"/></td>
												<td><input class="form-control hc_right" readonly/></td>
												<td><input class="form-control hc_right" readonly/></td>`;
						td_wip_P = `<tr>
													<td class="wd-16 hc___left">仕掛品(P)</td>
													<td class="das_tanto"><input title="number" id="wip_numberoftargets_P` + cav_no + `" class="form-control hc_right pd02 class_RFID_input" value="` + value["wip_numberoftargets_P"] + `" tabindex="` + fb_get_tabindex_no() + `"/></td>
													<td colspan="4">
													<select class="wd-100" style="font-size: 0.8vw; display: none; margin-bottom: 0px;" id="storage_source` + cav_no + `">`;
						td_wip_P += `<option value="">保管元を選択してください。</option>`;
						placelist.forEach((value, key) => {
							if (value.ad_org_id == placeid){
								result = value.m_locator_id + "," + value.value + "," + value.x;
								td_wip_P += `<option value="` + result + `">` + result + `</option>`;
							}
						});
						td_wip_P += `</select></td></tr>`;
					} else {
						if (value["fh_numberofgoods"] > 0 && value["fh_adversenumber"] == '' && processing_position > 3) {
							value["fh_adversenumber"] = 0;
						}
						if (value["wip_numberofgoods"] > 0 && value["wip_adversenumber"] == '' && processing_position > 3) {
							value["wip_adversenumber"] = 0;
						}
						if (value["vd_numberofgoods"] > 0 && value["vd_adversenumber"] == '' && processing_position > 3) {
							value["vd_adversenumber"] = 0;
						}
						if (processing_position == 5 && $("#position" + judgement_id).val() != '発生・流出書き直し' && $("#wbn_rank" + judgement_id).val() != 3 && !bl_change) {
							if (value["fh_numberoftargets"] > 0) {
								if (!value["wbn_fh_testnum"]) {
									value["wbn_fh_testnum"] = value["fh_numberoftargets"];
								}
								conditions = ' min="0" max="' + value["fh_numberoftargets"] + '" tabindex="' + fb_get_tabindex_no() + '" required';
							} else {
								conditions = ' readonly ';
							}
							td_fh = `	<td class="das_tanto"><input title="number" class="form-control hc_right" id= "fh_numberoftargets` + cav_no + `" value="` + value["fh_numberoftargets"] + `" readonly/></td>
												<td><input title="number" class="form-control hc_right " id= "fh_testnum` + cav_no + `" value="` + value["wbn_fh_testnum"] + `" ` + conditions + `"/></td>
												<td><input title="number" class="form-control hc_right class_RFID" id= "fh_adversenumber` + cav_no + `" value="` + value["fh_adversenumber"] + `"` + conditions + `/></td>`;
							if (value["wip_numberoftargets"] > 0) {
								if (!value["wbn_wip_testnum"]) {
									value["wbn_wip_testnum"] = value["wip_numberoftargets"];
								}
								conditions = ' min="0" max="' + value["wip_numberoftargets"] + '" tabindex="' + fb_get_tabindex_no() + '" required';
							} else {
								conditions = ' readonly ';
							}
							td_wip = `<td class="das_tanto"><input title="number" class="form-control hc_right" id= "wip_numberoftargets` + cav_no + `" value="` + value["wip_numberoftargets"] + `" readonly/></td>
												<td><input title="number" class="form-control hc_right " id= "wip_testnum` + cav_no + `" value="` + value["wbn_wip_testnum"] + `" ` + conditions + `/></td>
												<td><input title="number" class="form-control hc_right class_RFID" id= "wip_adversenumber` + cav_no + `" value="` + value["wip_adversenumber"] + `" ` + conditions + `/></td>`;
							if (value["vd_numberoftargets"] > 0) {
								if (!value["wbn_vd_testnum"]) {
									value["wbn_vd_testnum"] = value["vd_numberoftargets"];
								}
								conditions = ' min="0" max="' + value["vd_numberoftargets"] + '" tabindex="' + fb_get_tabindex_no() + '" required';
							} else {
								conditions = ' readonly ';
							}
							td_vd = ` <td class="das_tanto"><input title="number" class="form-control hc_right" id= "vd_numberoftargets` + cav_no + `" value="` + value["vd_numberoftargets"] + `" readonly/></td>
												<td><input title="number" class="form-control hc_right " id= "vd_testnum` + cav_no + `" value="` + value["wbn_vd_testnum"] + `" ` + conditions + `/></td>
												<td><input title="number" class="form-control hc_right class_RFID" id= "vd_adversenumber` + cav_no + `" value="` + value["vd_adversenumber"] + `" ` + conditions + ` /></td>`;
							if (value["wip_numberoftargets_P"] > 0) {
								if (!value["wbn_wip_testnum_P"]) {
									value["wbn_wip_testnum_P"] = value["wip_numberoftargets_P"];
								}
								conditions = ' min="0" max="' + value["wip_numberoftargets_P"] + '" tabindex="' + fb_get_tabindex_no() + '" required';
								td_wip_P = `<tr>
														<td class="wd-16 hc___left">仕掛品(P)</td>
														<td class="das_tanto"><input title="number" class="form-control hc_right" id= "wip_numberoftargets_P` + cav_no + `" value="` + value["wip_numberoftargets_P"] + `" readonly/></td>
														<td><input title="number" class="form-control hc_right " id= "wip_testnum_P` + cav_no + `" value="` + value["wbn_wip_testnum_P"] + `" ` + conditions + `/></td>
														<td><input title="number" class="form-control hc_right class_RFID" id= "wip_adversenumber_P` + cav_no + `" value="` + value["wip_adversenumber_P"] + `" ` + conditions + `/></td>
														<td colspan="2">
															<input type="text" class="form-control hc___left vw05" style="font-size: 0.6vw;" value="保管元：` + value["afterprocess"].split(",")[1] + `" readonly/>
															<input type="hidden" id="storage_source` + cav_no + `" value="` + value["afterprocess"] + `"/>
														</td>
													</tr> `;
								list_margin_bottom[index] = 40;
							} else {
								td_wip_P = '';
								list_margin_bottom[index] = 67;
							}
						} else {
							if (value["wbn_processing_position"] > 3) {
								if (value["fh_adversenumber"] == "" && value["fh_numberofgoods"] > 0) {
									value["fh_adversenumber"] = 0;
								}
								if (value["fh_numberofgoods"] == "" && value["fh_adversenumber"] > 0) {
									value["fh_numberofgoods"] = 0;
								}
								if ((parseInt(value["fh_numberoftargets"] - value["fh_numberofgoods"] - value["fh_adversenumber"]) != 0 || value["wic_data_null_count"] > 0) && value["fh_numberoftargets"] > 0 && value["wbn_processing_position"] > 5) {
									error_fh = ' input_error" title="良品数: ' + value["fh_numberofgoods"]
								}
								if (value["wip_adversenumber"] == "" && value["wip_numberofgoods"] > 0) {
									value["wip_adversenumber"] = 0;
								}
								if (value["wip_numberofgoods"] == "" && value["wip_adversenumber"] > 0) {
									value["wip_numberofgoods"] = 0;
								}
								if ((parseInt(value["wip_numberoftargets"] - value["wip_numberofgoods"] - value["wip_adversenumber"]) != 0 || value["wic_data_null_count"] > 0) && value["wip_numberoftargets"] > 0 && value["wbn_processing_position"] > 5) {
									error_wip = ' input_error" title="良品数: ' + value["wip_numberofgoods"]
								}
								if (value["vd_adversenumber"] == "" && value["vd_numberofgoods"] > 0) {
									value["vd_adversenumber"] = 0;
								}
								if (value["vd_numberofgoods"] == "" && value["vd_adversenumber"] > 0) {
									value["vd_numberofgoods"] = 0;
								}
								if ((parseInt(value["vd_numberoftargets"] - value["vd_numberofgoods"] - value["vd_adversenumber"]) != 0 || value["wic_data_null_count"] > 0) && value["vd_numberoftargets"] > 0 && value["wbn_processing_position"] > 5) {
									error_vd = ' input_error" title="良品数: ' + value["vd_numberofgoods"]
								}
							}
							td_fh = `	<td class="das_tanto"><input type="text" class="form-control hc_right"  id= "fh_numberoftargets` + cav_no + `" value="` + value["fh_numberoftargets"] + `" readonly/></td>
												<td><input type="text" class="form-control hc_right" value="` + value["wbn_fh_testnum"] + `" readonly/></td>
												<td><input type="text" class="form-control hc_right` + error_fh + `" value="` + value["fh_adversenumber"] + `" readonly/></td>`;
							td_wip = `<td class="das_tanto"><input type="text" class="form-control hc_right"  id= "wip_numberoftargets` + cav_no + `" value="` + value["wip_numberoftargets"] + `" readonly/></td>
												<td><input type="text" class="form-control hc_right" value="` + value["wbn_wip_testnum"] + `" readonly/></td>
												<td><input type="text" class="form-control hc_right` + error_wip + `" value="` + value["wip_adversenumber"] + `" readonly/></td>`;
							td_vd = `	<td class="das_tanto"><input type="text" class="form-control hc_right"  id= "vd_numberoftargets` + cav_no + `" value="` + value["vd_numberoftargets"] + `" readonly/></td>
												<td><input type="text" class="form-control hc_right" value="` + value["wbn_vd_testnum"] + `" readonly/></td>
												<td><input type="text" class="form-control hc_right` + error_vd + `" value="` + value["vd_adversenumber"] + `" readonly/></td>`;
							if (value["wip_numberoftargets_P"] > 0) {
								if (value["wbn_processing_position"] > 3) {
									if (value["wip_adversenumber_P"] == "" && value["wip_numberofgoods_P"] > 0) {
										value["wip_adversenumber_P"] = 0;
									}
									if (value["wip_numberofgoods_P"] == "" && value["wip_adversenumber_P"] > 0) {
										value["wip_numberofgoods_P"] = 0;
									}
									if (parseInt((value["wip_numberoftargets_P"] - value["wip_numberofgoods_P"] - value["wip_adversenumber_P"]) != 0 || value["wic_data_null_count"] > 0) && value["wbn_processing_position"] > 5) {
										error_wip_P = ' input_error" title="良品数: ' + value["wip_numberofgoods_P"]
									}
								}
								td_wip_P = `<tr>
															<td class="wd-16 hc___left">仕掛品(P)</td>
															<td class="das_tanto"><input type="text" class="form-control hc_right" id= "wip_numberoftargets_P` + cav_no + `" value="` + value["wip_numberoftargets_P"] + `" readonly/></td>
															<td><input type="text" class="form-control hc_right" value="` + value["wbn_wip_testnum_P"] + `" readonly/></td>
															<td><input type="text" class="form-control hc_right` + error_wip_P + `" value="` + value["wip_adversenumber_P"] + `" readonly/></td>
															<td colspan="2">
																<input type="text" class="form-control hc___left" style="font-size: 0.6vw;" value="保管元：` + value["afterprocess"].split(",")[1] + `" readonly/>
															</td>
														</tr> `;
								list_margin_bottom[index] = 40;
							} else {
								td_wip_P = '';
								list_margin_bottom[index] = 67;
							}
						}
					}
					html_tab_input += '<input style="display: none;" id="tab' + index + '" type="radio" name="tabs"/> <label class="tab_lb" name="tab' + cav_no + '" for="tab' + index + '">#' + cav_no + '</label>';
					html_tab_table = `<table class="wd-100 RFID_connect_view">
																		<tr>
																			<td class="wd-16 hc___left">在庫確認</td>
																			<td class="wd-16 ccenter das_tanto">対象数<div class="das_tanto_view` + index + `"></div></td>
																			<td class="wd-16 ccenter">検査数</td>
																			<td class="wd-16 ccenter">不良数</td>
																			<td class="wd-16 ccenter" rowspan="2">
																				数量管理部 <br>
																				門への連絡
																			</td>
																			<td class="wd-16 ccenter">発生部門係長</td>
																		</tr>
																		<tr>
																			<td class="wd-16 hc___left td_` + index + `_1">完成品</td>
																			` + td_fh + `
																			<td rowspan="3">
																				<div id="html_licensor` + index + `" class="ccenter"></div>
																			</td>
																		</tr>
																		<tr>
																			<td class="wd-16 hc___left td_` + index + `_2">仕掛品</td>
																			` + td_wip + `
																			<td rowspan="2" id="html_quantity` + index + `" class="ccenter">
																			</td>
																		</tr>
																		<tr>
																			<td class="wd-16 hc___left td_` + index + `_3">蒸着品</td>
																			` + td_vd + `
																		</tr>
																		` + td_wip_P + `
																	</table>`;
					html_tab_content += '<section id="content' + index + '">' + html_tab_table + '</section>';
				})
				if (Object.keys(obj_data['data']).length == 1) {
					html_tab = html_tab_table;
				} else {
					html_tab = html_tab_input + html_tab_content + '</div>';
				}
			}
			let saihako = '';
			if ($('#wbn_alignment' + judgement_id).val().indexOf("再発行") > -1) {
				saihako = '<span style="color:red"> （再発行）</span>';
			}
			$('#judgement_view').html(`<div id="div_overflow" style="max-height:` + wh + `px; overflow-y:scroll;">
																	<div class="col-md-6">
																		<div class="col-md-12" style="padding: 5px 5px 0px 5px;">
																			<div id="msgbox"></div>
																			<div class="bigclass bgc-wh ">
																				<div class=" smallclass wd-50" style="padding-top: 10px;">
																					<h3>不具合連絡書</h3>
																				</div>
																				<div class="smallclass wd-30">
																					<label>資料番号` + saihako + `</label>
																					<input type="text" id="wbn_id" class="form-control ccenter" style="font-weight: bolder;" value="` + judgement_id + `" readonly />
																				</div>
																				<div class="smallclass wd-20">
																					<label>ランク</label>
																					<input class="form-control ccenter" value = '` + $('#wbn_rank' + judgement_id).val() + `' readonly>
																				</div>
																				<div class=" smallclass wd-60">
																					<label>品名</label>
																					<input type="text" class="form-control ccenter" style="ime-mode:disabled;" value = '` + $('#wbn_product_name' + judgement_id).val() + `' readonly />
																				</div>
																				<div class="smallclass wd-25">
																					<label>品目コード</label>
																					<input type="text" class="form-control ccenter" value = '` + $('#wbn_item_code' + judgement_id).val() + `' readonly />
																				</div>
																				<div class="smallclass wd-15">
																					<label>BU</label>
																					<input class="form-control ccenter" type='text' value = '` + $('#wbn_bu' + judgement_id).val() + `' readonly />
																				</div>
																				<div class="smallclass wd-10">
																					<label >型番</label>
																					<input class="form-control ccenter" value = '` + $('#wbn_form_no' + judgement_id).val() + `' readonly/>
																				</div>
																				<div class="smallclass wd-20">
																					<label >成形Lot</label>
																					<input class="form-control ccenter" value = '` + $('#wbn_lot_no' + judgement_id).val() + `' readonly/>
																				</div>
																				<div class="smallclass wd-30">
																					<label >キャビNo</label>
																					<input class="form-control ccenter" value = '` + $('#CAVno' + judgement_id).val() + `' readonly/>
																				</div>
																				<div class="smallclass wd-20">
																					<label >蒸着Lot</label>
																					<input class="form-control ccenter" value = '` + $('#wbn_vd_dt' + judgement_id).val() + `' readonly/>
																				</div>
																				<div class="smallclass wd-20">
																					<label >成形日</label>
																					<input type="text" class="form-control hc_right"  value = '` + $('#wbn_mold_dt' + judgement_id).val() + `' readonly/>
																				</div>
																				<div class="smallclass wd-50" style="margin:5px 0px 5px 0px">
																					<textarea id="bugcontent" class="form-control" rows="2" placeholder="不具合内容" title="不具合内容" readonly>` + $('#wbn_defect_item' + judgement_id).val() + `</textarea>
																					<textarea class="form-control" rows="2" placeholder="状態・原因" title="状態・原因" id="defect_details" style="margin-top:5px" readonly>` + $("#wbn_defect_details" + judgement_id).val() + `</textarea>
																					<textarea class="form-control" id="evidence" rows="2" placeholder="OKの根拠 " title="OKの根拠" style="margin-top:5px" readonly>` + $('#wbn_decisive_evidence' + judgement_id).val() + `</textarea>
																					<textarea class="form-control" id="demand" rows="2" placeholder="要望事項" title="要望事項" style="margin-top:5px" readonly>` + $('#wbn_decision_demand' + judgement_id).val() + `</textarea>
																				</div>
																				<div class="smallclass wd-50" style="margin:5px 0px 5px 0px; padding: 0px;">
																					<div class="smallclass wd-25">
																						<label>対象数</label>
																						<input class="form-control hc_right pd02" value = '` + $('#wbn_qty' + judgement_id).val() + `' readonly />
																					</div>
																					<div class="smallclass wd-25">
																						<label>検査数</label>
																						<input class="form-control hc_right pd02" value = '` + $('#wbn_insp_qty' + judgement_id).val() + `'readonly />
																					</div>
																					<div class="smallclass wd-25">
																						<label>不良数</label>
																						<input class="form-control hc_right pd02" value = '` + $('#wbn_bad_qty' + judgement_id).val() + `'readonly />
																					</div>
																					<div class="smallclass wd-25">
																						<label>不良率</label>
																						<input type="text" class="form-control hc_right pd02" style="font-size: 80%" value = '` + $('#wbn_bad_rate' + judgement_id).val() + `' readonly />
																					</div>
																					<div class=" smallclass wd-50" style="line-height: 3;">
																						<label>発見者</label>
																						` + fb_get_hakko($('#wbn_discoverer' + judgement_id).val(), $('#date' + judgement_id).val()) + `
																					</div>
																					<div class="smallclass wd-50"style="line-height: 3;">
																						<label>認可者</label>
																						<div id="html_decision">` + fb_set_hakko('wbn_decision', "", $('#wbn_decision' + judgement_id).val()) + `</div>
																					</div>
																					<div class=" smallclass wd-100" style="margin-top:20px">
																						<div class=" smallclass wd-50"><label for="processing_position">処理状態</label></div>
																						<div class=" smallclass wd-50"><input id="processing_state" type="text" class="form-control ccenter name" value="` + $('#position' + judgement_id).val() + `" onclick="fb_show_rejection_reason();" title="メッセージ：` + $('#wbn_rejection_reason' + judgement_id).val() + `" readonly/></div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-12" style="padding: 0px 5px 0px 5px;">
																			<div class=" bigclass bgc-wh div_BU_tanto">
																				<div class="smallclass wd-30" style="padding-top: 1vw;">
																					<h3>是正指示欄</h3>
																				</div>
																				<div groups="是正指示欄" class="smallclass radio radioreadonly wd-68" style="float:right;margin-right: 4px;margin-top: 7px;">
																					<label>修理依頼書</label><br>
																					<input type="radio" id="radio_1" name="修理依頼書" value="設備・治工具" disabled />
																					<label class="radio" for="radio_1" name="修理依頼書">設備・治工具</label>
																					<input type="radio" id="radio_2" name="修理依頼書" value="金型" disabled />
																					<label class="radio" for="radio_2" name="修理依頼書">金型</label>
																					<input type="radio" id="radio_3" name="修理依頼書" value="不具合連絡書にて処理" disabled />
																					<label class="radio" for="radio_3" name="修理依頼書">不具合連絡書にて処理</label>
																				</div>
																				<div groups="是正指示欄" class="smallclass wd-100" style="padding: 0;">
																					<div class="smallclass wd-25">
																						<label for="address">宛先</label>
																						<input class="form-control ccenter name" type='text' id="dept" onclick="fb_get_dept();" value = '` + $('#wbn_dept' + judgement_id).val() + `' readonly/>
																					</div>
																					<div class="smallclass wd-25">
																						<label for="deadline" >在庫処置期限</label>
																						<input class="form-control hc_right" type="date" id="deadline" value = '` + $('#wbn_deadline' + judgement_id).val() + `' readonly/>
																					</div>
																					<div class="smallclass wd-25">
																						<label for="receipt_dt">受理日</label>
																						<input class="form-control hc_right" type='date' id="receipt_dt" value = '` + $('#wbn_receipt_dt' + judgement_id).val() + `' readonly/>
																					</div>
																					<div class="smallclass wd-25">
																						<label for="due_dt">処置回答指示日</label>
																						<input class="form-control hc_right " type="text" id="due_dt" value = '` + due_dt_date + `' readonly />
																						<div class="smallclass div_BU_tanto_view"></div>
																					</div>
																				</div>
																				<div class="smallclass wd-100" style=" float:left; margin: 5px 0px 5px 0px">
																					<div groups="是正指示欄" class="smallclass radio radioreadonly wd-30">
																						<label>欠点</label><br>
																						<input type="radio" id="radio_4" name="欠点分類" value="重" disabled />
																						<label class="radio" for="radio_4" name="欠点分類">重</label>
																						<input type="radio" id="radio_5" name="欠点分類" value="軽" disabled />
																						<label class="radio" for="radio_5" name="欠点分類">軽</label>
																					</div>
																					<div groups="是正指示欄" class="smallclass radio radioreadonly" style="width:69%; margin-left: 1%;">
																						<label>発行理由</label><br>
																						<input type="radio" id="radio_6" name="発行理由" value="ロット不合格" disabled/>
																						<label class="radio" for="radio_6" >ロット不合格</label>
																						<input type="radio" id="radio_7" name="発行理由" value="選別不良" disabled/>
																						<label class="radio" for="radio_7" >選別不良</label>
																						<input type="radio" id="radio_8" name="発行理由" value="管理不良" disabled/>
																						<label class="radio" for="radio_8" >管理不良</label>
																						<input type="radio" id="radio_9" name="発行理由" value="品質上の警告" disabled/>
																						<label class="radio" for="radio_9" >品質上の警告</label>
																					</div>
																				</div>
																				<div class="smallclass wd-100" style="margin-top:1px">
																					<textarea class="form-control" rows="2" placeholder="要望事項" title="要望事項" id="due_details" readonly>` + $("#wbn_due_details" + judgement_id).val() + `</textarea>
																				</div>
																			</div>
																			</div><div class="col-md-12" style="padding: 0px 5px 0px 5px;">
																			<div class="bigclass bgc-wh ">
																				<div class="smallclass wd-25 short"style="margin-top:5px">
																					<h3>水平展開欄</h>
																				</div>
																				<div class="smallclass wd-100" style="margin-bottom:14px">
																					<table class="wd-100">
																						<tr>
																							<td class="wd-40 hc___left">類似製品に対する水平展開</td>
																							<td class="wd-40 hc___left">類似プロセスに対する水平展開</td>
																							<td class="wd-40 ccenter">製造課長</td>
																						</tr>
																						<tr>
																							<td><input type="text" class="form-control " value="` + $("#wbn_products_nowant" + judgement_id).val() + `" placeholder="不要" disabled/></td>
																							<td><input type="text" class="form-control " value="` + $("#wbn_products_want" + judgement_id).val() + `" placeholder="必要" disabled/></td>
																							<td rowspan="2">
																								<input type="text" class="form-control ccenter name" value="` + $("#wbn_manufacturing_person" + judgement_id).val() + `" placeholder="未" disabled/>
																								<input type="text" class="form-control ccenter " value="` + $("#wbn_manufacturing_date" + judgement_id).val() + `" disabled/>
																							</td>
																						</tr>
																						<tr>
																							<td><input type="text" class="form-control " value="` + $("#wbn_process_nowant" + judgement_id).val() + `" placeholder="不要" disabled/></td>
																							<td><input type="text" class="form-control " value="` + $("#wbn_process_want" + judgement_id).val() + `" placeholder="必要" disabled/></td>
																						</tr>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div id="msgbug"></div>
																		<div class="col-md-12" style="padding: 5px 5px 0px 0px;">
																			<div class="bigclass bgc-wh ">
																				<div class="smallclass wd-40" style="padding-top: 15px;">
																					`+(parseInt($("#count_RFID" + judgement_id).val()) > 0?`<div style="float:left; "><img class="icon_rfid_data" onclick="fb_get_RFID_data(2);" src="/MissingDefect/GetFile?menu=img&mfc_id=7"/></div>`:"")+`
																					<h3>在庫処置欄</h3>
																				</div>
																				<div class="smallclass wd-60">
																					<textarea class="form-control" id="content_confirmation" rows="2" placeholder="現品の内容確認（不適合内容に対する見解）\n※ 同意・相違,異議についてコメントを記載" title="入力された現品の内容確認を参考する場合は「@」を入力してください。" readonly>` + $("#wbn_content_confirmation" + judgement_id).val() + `</textarea>
																				</div>
																				<div class="stock_confirmation smallclass wd-100" style="margin-top:5px;">
																				` + html_tab + `
																				</div>
																				<div class="smallclass wd-75" style="margin-top:5px">
																					<div name="処置内容" class="smallclass radio radioreadonly wd-100">
																						<label>在庫品の処置(現品についても処置を行うこと)</label><br>
																						<input type="radio" id="radio_30" name="処置内容" value="廃棄" disabled/>
																						<label class="radio" for="radio_30">廃棄</label>
																						<input type="radio" id="radio_31" name="処置内容" value="特採(修理あり)" disabled/>
																						<label class="radio" for="radio_31">特採(修理あり)</label>
																						<input type="radio" id="radio_32" name="処置内容" value="特採(修理なし)" disabled/>
																						<label class="radio" for="radio_32">特採(修理なし)</label>
																						<input type="radio" id="radio_33" name="処置内容" value="選別" disabled/>
																						<label class="radio" for="radio_33">選別</label>
																						<input type="radio" id="radio_34" name="処置内容" value="手直し" disabled/>
																						<label class="radio" for="radio_34">手直し</label>
																						<input type="radio" id="radio_35" name="処置内容" value="その他" disabled/>
																						<label class="radio" for="radio_35">その他</label>
																					</div>
																					<div class="smallclass wd-100" style="margin-top: 5px; padding: 0px;">
																						<textarea class="form-control" rows="2" id="rework_instructions" placeholder="手直し（修理）の場合は処置内容を記入　\n ※ 手直し指示書を作成する事" title="手直し（修理）の場合は処置内容を記入" readonly>` + $("#wbn_rework_instructions" + judgement_id).val() + `</textarea>
																					</div>
																				</div>
																				<div class="smallclass wd-25">
																					<div class="smallclass wd-100"><label for="処置担当者">処置担当者</label></div>
																					<div class="smallclass wd-100" id="html_treatment" onclick="fb_get_user();">` + fb_set_hakko('wbn_treatment', "", processing_position) + `</div>
																					<div class="smallclass wd-100" id="html_treatment_only"></div>
																				</div>
																				<div class="smallclass wd-100" style="margin-top:5px ">
																					<div class="smallclass wd-75" style="padding: 0px 2px 0px 0px;">
																						<textarea class="form-control" rows="3" id="inventory_processing" placeholder="在庫処置のみ及び対象在庫確定の根拠 \n発生部門課長が判断可 右端欄にチェック、対策回答欄以下は記入不要" title="在庫処置のみ及び対象在庫確定の根拠" readonly>` + $("#wbn_inventory_processing" + judgement_id).val() + `</textarea>
																					</div>
																					<div class="smallclass wd-25" style="padding-top: 5px;" id="html_button">
																					<div class="smallclass wd-50 ccenter">
																							<input type="button" class="form-control ccenter wd-100 btn_back_NG" onclick="btn_judgement(this);" value="NG"/>
																						</div>
																						<div class="smallclass wd-50 ccenter">
																							<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" value="OK"/>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class=" col-md-12" style="padding: 0px 5px 5px 0px;">
																			<div class="bigclass bgc-wh">
																				<div class="smallclass wd-100" style="margin-top:12px">
																					<div class="smallclass wd-30" style="padding-top: 5px; ">
																						<h3>是正処置欄</h3>
																					</div>
																					<div class="smallclass wd-45 vw06" style="text-align: left; ">
																						※暫定対策がある場合も記入 <br>
																						※本対策書に書ききれないときは別紙を添付の事。
																					</div>
																					<div class="smallclass wd-25" style="padding-top: 5px; ">
																						<h3>検証結果欄</h3>
																					</div>
																				</div>
																				<table id="corrective_table" class="smallclass wd-100 hc___left" style="margin-bottom:35px">
																					<tr>
																						<td class="wd5">　</td>
																						<td class="vw05 wd-35 pl5">
																							原因<br>
																							管理、技術的な原因　なぜの深堀 <br>
																							５つの観点(材料､測定､方法､機械､人) <br>
																							なぜなぜ分析,QC7つ道具,新QC7つ道具等活用
																						</td>
																						<td class="vw05 wd-35 pl5">
																							対策<br>
																							原因対応日（予定、実施）記入 <br>
																							<br>
																							思考展開図,QC7つ道具,新QC7つ道具等活用
																						</td>
																						<td class="vw05 wd-25 pl5">
																							有効性・効果確認結果
																						</td>
																					</tr>
																					<tr>
																						<td style="text-align: center;">発生<label groups="発生" hidden class="vw04" style="color: red;" >(必要)</label></td>
																					<td style="vertical-align: top;">
																						<textarea class="form-control 発生流出" id="発生・原因" groups="発生" style="height: 95px;">` + $("#wbn_cause" + judgement_id).val().replace("入力必要", "") + `</textarea>
																					</td>
																					<td style="vertical-align: top;">
																						<textarea class="form-control 発生流出" id="発生・対策" groups="発生" style="height: 95px;">` + $("#wbn_countermeasures" + judgement_id).val().replace("入力必要", "") + `</textarea>
																					</td>
																						<td rowspan="2" style="line-height: 1; padding:2px; vertical-align: top;">
																							<div style=" padding-top:2px;" id="html_verification">
																								<div groups="検証結果欄" class="smallclass wd-100 radio radioreadonly" style="padding:0; text-align: left;" >
																									<input type="radio" id="radio_304" name="復旧確認で可" value="復旧確認で可" disabled/>
																									<label class="radio vw06" for="radio_304">復旧確認で可</label>
																								</div>
																								<div groups="検証結果欄" class="smallclass wd-100 radio radioreadonly" style="padding:0; text-align: left;" >
																									<input type="radio" id="radio_305" name="長期確認が必要" value="長期確認が必要" disabled/>
																									<label class="radio vw06" for="radio_305" >長期確認が必要</label>
																								</div>
																							</div>
																							<div groups="検証結果欄" class="smallclass wd-100" style=" padding:0px 1px 0px 0px;">
																								<div class="smallclass wd-35" style=" padding:1px;">
																									<label class="vw06"for="inspection_total">検査数</label>
																									<input class="form-control hc_right" style="font-size: 0.6vw;" id="inspection_total" value="` + $("#wbn_inspection_total" + judgement_id).val() + `" readonly/>
																								</div>
																								<div class="smallclass wd-30" style=" padding:2px;">
																									<label class="vw06" for="inspection_bad">不良数</label>
																									<input title="number" class="form-control hc_right" style="font-size: 0.6vw;" id="inspection_bad" value="` + $("#wbn_inspection_bad" + judgement_id).val() + `" readonly/>
																								</div>
																								<div class="smallclass wd-35" style=" padding:2px;">
																									<label class="vw06" for="inspection_rate">不良率</label>
																									<input type="text" class="form-control hc_right" style="font-size: 0.5vw;" id="inspection_rate" value="` + $("#wbn_inspection_rate" + judgement_id).val() + `" readonly />
																								</div>
																							</div>
																							<div groups="検証結果欄" class="smallclass wd-100 radio radioreadonly" style="padding:0;" >
																								<label class="radio" for="radio_37" class="vw05">効果</label>
																								<input type="radio" id="radio_36" name="効果" value="効果あり" disabled/>
																								<label class="radio" for="radio_36" class="vw05">あり</label>
																								<input type="radio" id="radio_37" name="効果" value="効果なし" disabled/>
																								<label class="radio" for="radio_37" class="vw05">なし</label>
																							</div>
																							<label name="効果なし" class="vw06">・効果なしのコメント</label>
																							<input type="text" class="form-control" name="効果なし" style="font-size: 0.6vw;" id="effect_NG_msg" value="` + $("#wbn_effect_NG_msg" + judgement_id).val() + `" readonly/>
																							<label name="効果なし" class="vw06">・再発行・資料No</label>
																							<input type="text" class="form-control" name="効果なし" id="reissue_id" value="` + $("#wbn_reissue_id" + judgement_id).val() + `" readonly/>
																						</td>
																					</tr>
																					<tr>
																						<td style="text-align: center;">流出<label groups="流出" hidden class="vw04" style="color: red;" >(必要)</label></td>
																						<td style="vertical-align: top;">
																							<textarea class="form-control 発生流出" id="流出・原因" groups="流出" style="height: 95px;">` + $("#wbn_outflow_cause" + judgement_id).val().replace("入力必要", "") + `</textarea>
																						</td>
																						<td style="vertical-align: top;">
																							<textarea class="form-control 発生流出" id="流出・対策" groups="流出" style="height: 95px;">` + $("#wbn_outflow_countermeasures" + judgement_id).val().replace("入力必要", "") + `</textarea>
																						</td>
																					</tr>
																					<tr>
																						<td style="text-align: center;">確認</td>
																						<td colspan="2">
																							<div class="smallclass wd-5 vw06 " style="border: 0.5px dotted">対策部門
																							</div>
																							<div class="smallclass wd-30">
																								<input type="text" class="form-control ccenter name" value="` + $("#wbn_countermeasure_person" + judgement_id).val() + `" disabled/>
																								<input type="text" class="form-control ccenter " value="` + $("#wbn_countermeasure_date" + judgement_id).val() + `" disabled/>
																							</div>
																							<div class="smallclass wd-5 vw06" style="border: 0.5px dotted">作成部門
																							</div>
																							<div class="smallclass wd-30">
																								<input type="text" class="form-control ccenter name" value="` + $("#wbn_reation_person" + judgement_id).val() + `" disabled/>
																								<input type="text" class="form-control ccenter " value="` + $("#wbn_reation_date" + judgement_id).val() + `" disabled/>
																							</div>
																							<div class="smallclass wd-5 vw06" style="border: 0.5px dotted">品管部門
																							</div>
																							<div class="smallclass wd-25" id="html_quality_control">
																							</div>
																						</td>
																						<td>
																							<div class="smallclass wd-100 vw06 html_quality_comment">コメントがある場合記載</div>
																						</td>
																					</tr>
																				</table>
																			</div>
																		</div>
																	</div>
															</div>
														  <div id="msg_box_view">
															</div>`);
			$('#html_quality_control').html(fb_set_hakko("wbn_quality_control", "position:absolute;margin-top: -6px;margin-left:12px;"));
			if ($("#wbn_rejection_reason" + judgement_id).val()) {
				$('.html_quality_comment').html($("#wbn_quality_control_comment" + judgement_id).val());
			}
			switch (processing_position) {
				case 0:
					$('div[groups="是正指示欄"]').removeClass('radioreadonly');
					$('div[groups="是正指示欄"]').find(":input").prop('disabled', false);
					$('div[groups="是正指示欄"]').find(":input[id!='due_dt']").prop('readonly', false);
					msg_box = "※ " + $("#place_name" + judgement_id).val() + `の確認内容 <br><br>  1. スタートサンプル <br> 2. 前回ロット<br> 3. 過去の発生状況(新規or再発）<br> 4. 関係部門の関連性確認(トラブル、生産状況）<br> 5. 発生状況(発生率、発生傾向、成形機）<br> 6. 対象範囲 <br> 7. 技術課や他課からの依頼がある場合は依頼内容を確認`;
					$('#evidence').prop('readonly', false);
					$('#demand').prop('readonly', false);
					// $('[groups="発生"]').prop('readonly', true);
					// $('[groups="流出"]').prop('readonly', true);
					$('#html_decision').html(fb_get_hakko($("#login_person").val(), today, "", "?"));
					break;
				case 1:
					$('div[groups="是正指示欄"]').removeClass('radioreadonly');
					$('div[groups="是正指示欄"]').find(":input").prop('disabled', false);
					$('div[groups="是正指示欄"]').find(":input[id!='due_dt']").prop('readonly', false);
					$('#dept').prop('required', true);
					$('#defect_details').prop('readonly', false);
					//$('.class_RFID_input').prop('readonly', true);
					$('#html_button').html(`<div class="smallclass wd-40" style="padding: 5px;">
																		<input type="button" class="form-control ccenter wd-100 btn_save" onclick="btn_judgement(this);" value="保存" />
																	</div>
																	<div class="smallclass wd-60" style="padding: 5px;">
																		<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" value="次へ" tabindex="100"/>
																	</div>`);
					break;
				case 2:
					$('#html_button').html(`<div class="smallclass wd-40" style="padding: 5px;">
																		<input type="button" class="form-control ccenter wd-100 btn_save"  style="background-color: #00BFFF;" onclick="btn_judgement(this);" value="保存" />
																	</div>
																	<div class="smallclass wd-60" style="padding: 5px;">
																		<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" value="次へ" tabindex="100"/>
																	</div>`);
					break;
				case 3:
					msg_box = "※ " + $("#place_name" + judgement_id).val() + `の確認内容 <br><br>    1. 報告内容にて判断 <br>   2. 生産停止指示<br>   3. 生産処置の決定・指示<br>   4. 対策について検討、指示<br>   5. 選別対応時に予測不可<br>   6. 選別応先との協議 <br>   7. 納期影響の確認・考慮 <br>   8. 製品使用用途を考慮 <br>   9. 他製品での実績 <br> 10. 関係部門との連携`;
					$('div[groups="是正指示欄"]').removeClass('radioreadonly');
					$('div[groups="是正指示欄"]').find(":input").prop('disabled', false);
					$('div[groups="是正指示欄"]').find(":input[id!='due_dt']").prop('readonly', false);
					$('#dept').prop('required', true);
					$('#due_details').prop('readonly', false);
					$('#html_treatment_only').html(`<div class="smallclass radio wd-100" style="margin-top: 0px;">
																						<label style="font-size: 12px;">是正処置入力必要</label><br>
																						<input type="checkbox" id="checkbox_01" name="発生流出" value="発生"/>
																						<label class="radio" for="checkbox_01" name="発生流出">発生</label>
																						<input type="checkbox" id="checkbox_02" name="発生流出" value="流出"/>
																						<label class="radio" for="checkbox_02" name="発生流出">流出</label>
																					</div>`);
					$('#html_button').html(`<div class="smallclass wd-25" style="padding: 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_save" onclick="btn_judgement(this);" value="保存"/>
																	</div>
																	<div class="smallclass wd-25" style="padding: 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_back_NG" onclick="fb_BU_return();" value="差戻"/>
																	</div>
																	<div class="smallclass wd-25" style="padding: 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_A1" onclick="if (confirm('A1に判定ですか。') == true) {btn_judgement(this);}" value="A1"/>
																	</div>
																	<div class="smallclass wd-25" style="padding: 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" value="次へ"/>
																	</div>`);
					break;
				case 4:
					if ($("#processing_state").val().indexOf("書き直し") > -1) {
						$('#dept').prop('readonly', false);
						$('#dept').prop('required', true);
					}
					msg_box = "※ " + $("#place_name" + judgement_id).val() + `の確認内容 <br><br>  1. NGと判断し納期に影響が出る場合報告 <br>  2. 社内特採で使用する場合報告申請<br>  3. 顧客への連絡が必要な場合報告申請<br>  4. 顧客特採についての処置を決定<br>  5. 顧客納品済品についての処置を決定<br>  6. 特採規定に基づき1週間以内 `;
					$('#content_confirmation').prop('readonly', false);
					$('#content_confirmation').prop('required', true);
					$('#html_button').html(`<div class="smallclass wd-50" style="padding: 10px 2px 5px 2px;">
																		<input type="radio" id="radio_16" name="処置のみ" value="処置のみ" disabled />
																		<label class="radio" for="radio_16" name="処置のみ"> 処置のみ</label><br>
																	</div>
																	<div class="smallclass wd-25" style="padding: 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_save" onclick="btn_judgement(this);" value="保存"/>
																	</div>
																	<div class="smallclass wd-25" style="padding: 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" tabindex="101" value="次へ"/>
																	</div>`);
					break;
				case 5:
					$('div[name="処置内容"]').removeClass('radioreadonly');
					$('input[name="処置内容"]').prop('disabled', false);
					$('#inventory_processing').prop('readonly', false);
					if ($("#position" + judgement_id).val() != '発生・流出書き直し' && $("#wbn_rank" + judgement_id).val() != 3 && !bl_change) {
						$('#html_treatment').html(fb_get_hakko($("#input_das_person").val(), today));
					}
					let qty;
					qty = parseInt($("#fh_numberoftargets" + judgement_id).val() + $("#wip_numberoftargets" + judgement_id).val() + $("#vd_numberoftargets" + judgement_id).val() + $("#wip_numberoftargets_P" + judgement_id).val());
					if (qty > 0 && $("#position" + judgement_id).val() != '発生・流出書き直し' && $("#wbn_rank" + judgement_id).val() != 3 && !bl_change) {
						$('#html_button').html(` <div class="smallclass wd-45" style="padding: 0px;">
																				<input type="checkbox" id="checkbox_03" name="処置のみ" value="処置のみ"/>
																				<label class="radio" style="font-size: 10px;" for="checkbox_03"> 処置のみ</label><br>
																				<input title="text" class="form-control hc_right" style="font-size: 8px;" id= "testtime" placeholder="検査時間(時)" tabindex="100" required/>
																			</div>
																			<div class="smallclass wd-25" style="padding: 5px 2px 5px 2px;">
																				<input type="button" class="form-control ccenter wd-100 btn_save" onclick="btn_judgement(this);" title="DASの数以外を保存出来ます" value="保存"/>
																			</div>
																			<div class="smallclass wd-30" style="padding: 5px 0px 5px 0px;">
																				<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" tabindex="101" value="次へ"/>
																			</div>`);
					} else if (parseInt($("#count_RFID" + judgement_id).val()) > 0 && $("#position" + judgement_id).val() != '発生・流出書き直し' && $("#wbn_rank" + judgement_id).val() != 3 && !bl_change) {
						$('#html_button').html(` <div class="smallclass wd-45" style="padding: 0px;">
																				<input type="checkbox" id="checkbox_03" name="処置のみ" value="処置のみ"/>
																				<label class="radio" style="font-size: 10px;" for="checkbox_03"> 処置のみ</label><br>
																				<input title="text" class="form-control hc_right" style="font-size: 8px;" id= "testtime" placeholder="検査時間" readonly/>
																			</div>
																			<div class="smallclass wd-25" style="padding: 5px 2px 5px 2px;">
																				<input type="button" class="form-control ccenter wd-100 btn_save" onclick="btn_judgement(this);" title="DASの数以外を保存出来ます" value="保存"/>
																			</div>
																			<div class="smallclass wd-30" style="padding: 5px 0px 5px 0px;">
																				<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" tabindex="101" value="次へ"/>
																			</div>`);
					} else {
						$('#html_button').html(` <div class="smallclass wd-45" style="padding-top: 10px;">
																				<input type="checkbox" id="checkbox_03" name="処置のみ" value="処置のみ"/>
																				<label class="radio" style="font-size: 10px;" for="checkbox_03"> 処置のみ</label><br>
																			</div>
																			<div class="smallclass wd-25" style="padding: 5px 2px 5px 2px;">
																				<input type="button" class="form-control ccenter wd-100 btn_save" onclick="btn_judgement(this);" title="DASの数以外を保存出来ます" value="保存"/>
																			</div>
																			<div class="smallclass wd-30" style="padding: 5px 0px 5px 0px;">
																				<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" tabindex="101" value="次へ"/>
																			</div>`);
					}
					break;
				case 6:
					$('[groups="発生"]').prop('readonly', true);
					$('[groups="流出"]').prop('readonly', true);
					if (parseInt($("#wbn_qty" + judgement_id).val()) > 0 || parseInt($("#count_RFID" + judgement_id).val()) > 0) {
						$('#html_button').html(`<div class="smallclass wd-70" style="padding: 0px 5px 0px 2px;">
																		<input type="radio" id="radio_16" name="処置のみ" value="処置のみ" disabled />
																		<label class="radio" for="radio_16" name="処置のみ"> 処置のみ</label><br>
																		<input title="text" class="form-control hc_right" style="font-size: 8px;" id= "testtime" placeholder="検査時間" value="検査時間: ` + $("#totaltime" + judgement_id).val() + `時" tabindex="100" readonly/>
																	</div>
																	<div class="smallclass wd-30" style="padding: 5px 0px 5px 0px;">
																		<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" tabindex="101" value="次へ"/>
																	</div>`);
					} else {
						$('#html_button').html(`<div class="smallclass wd-70" style="padding: 10px 5px 0px 2px;">
																		<input type="radio" id="radio_16" name="処置のみ" value="処置のみ" disabled />
																		<label class="radio" for="radio_16" name="処置のみ"> 処置のみ</label><br>
																	</div>
																	<div class="smallclass wd-30" style="padding: 5px 0px 5px 0px;">
																		<input type="button" class="form-control ccenter wd-100 btn_next_OK" onclick="btn_judgement(this);" tabindex="101" value="次へ"/>
																	</div>`);
					}
					break;
				case 7:
					$('[groups="発生"]').prop('readonly', true);
					$('[groups="流出"]').prop('readonly', true);
					$('div[groups="検証結果欄"]').removeClass('radioreadonly');
					$('div[groups="検証結果欄"]').find(":input").prop('disabled', false);
					$('div[groups="検証結果欄"]').find(":input[id!='inspection_rate']").prop('readonly', false);
					$('#dept').prop('required', true);
					$('#html_verification').html(`<div groups="検証結果欄" class="smallclass wd-100 radio" style="padding:0; text-align: left;" >
																						<input type="checkbox" id="checkbox_04" name="復旧確認で可" value="復旧確認で可"/>
																						<label class="radio vw06" for="checkbox_04">復旧確認で可</label>
																					</div>
																					<div groups="検証結果欄" class="smallclass wd-100 radio" style="padding:0; text-align: left;" >
																						<input type="checkbox" id="checkbox_05" name="長期確認が必要" value="長期確認が必要"/>
																						<label class="radio vw06" for="checkbox_05" >長期確認が必要</label>
																					</div>`);
					if ($("#wbn_rank" + judgement_id).val() != 1) {
						$('#html_button').html(`<div class="smallclass wd-45" style="padding-top: 10px;">
																		<input type="radio" id="checkbox_03" name="処置のみ" value="処置のみ" disabled/>
																		<label class="radio" style="font-size: 0.5vw;" for="checkbox_03"> 処置のみ</label><br>
																	</div>
																	<div class="smallclass wd-25" style="padding: 5px 0px 5px 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_back_NG" onclick="fb_quality_control_return();"  value="差戻"/>
																	</div>
																	<div class="smallclass wd-30" style="padding: 5px 0px 5px 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_quality_control btn_next_OK" onclick="if (confirm('内容に不備はありませんか。') == true) {btn_judgement(this);}" value="確認"/>
																	</div>`);
					} else {
						$('#html_button').html(`<div class="smallclass wd-60" style="padding-top: 10px;">
																		<input type="radio" id="checkbox_03" name="処置のみ" value="処置のみ" disabled/>
																		<label class="radio" style="font-size: 0.5vw;" for="checkbox_03"> 処置のみ</label><br>
																	</div>
																	<div class="smallclass wd-40" style="padding: 5px 0px 5px 2px;">
																		<input type="button" class="form-control ccenter wd-100 btn_quality_control btn_next_OK" onclick="if (confirm('内容に不備はありませんか。') == true) {btn_judgement(this);}" value="確認"/>
																	</div>`);
					}
					$('.html_quality_comment').html(`<textarea class="form-control" id="quality_control_comment" style="height: 100%;" placeholder="コメントがある場合記載">` + $("#wbn_quality_control_comment" + judgement_id).val() + `</textarea>`);
					if ($('#wbn_confirmation' + judgement_id).val() == "長期確認が必要") {
						$('.btn_quality_control').val("保存");
						$('.btn_quality_control').css("background-color", "#00BFFF;");
					}
					$('#html_quality_control').html(fb_get_hakko($("#login_person").val(), today, "position:absolute;margin-top: -6px;margin-left:12px;"));
					break;
			}
			if (parseInt($("#count_RFID" + judgement_id).val()) > 0) {
				$('.class_RFID_input').prop('readonly', true);
				fb_get_RFID_data(2);
			}
			if ($('#position' + judgement_id).val().indexOf("再") > -1 || $('#position' + judgement_id).val().indexOf("直し") > -1) {
				$('#processing_state').css('color', 'red')
			}
			if ($("#wbn_cause" + judgement_id).val().indexOf("入力必要") > -1) {
				set_checkbox('発生流出', '発生');
				$('label[groups="発生"]').prop('hidden', false);
				outbreak_outflow += "発生"
				if (processing_position == 5) {
					$('textarea[groups="発生"]').prop('required', true);
				}
			}
			if ($("#wbn_outflow_cause" + judgement_id).val().indexOf("入力必要") > -1) {
				set_checkbox('発生流出', '流出');
				$('label[groups="流出"]').prop('hidden', false);
				outbreak_outflow += "流出"
				if (processing_position == 5) {
					$('textarea[groups="流出"]').prop('required', true);
				}
			}
			if (bl_change) {
				$('#html_button').html(` <div class="smallclass wd-15" style="padding: 5px;"></div>
																		<div class="smallclass wd-70" style="padding: 5px;">
																			<input type="button" class="form-control ccenter wd-100" onclick="btn_judgement(this);" value="修正確定"/>
																		</div>
																	<div class="smallclass wd-15" style="padding: 5px;"></div>`);
				if (processing_position == 5) {
					$('#html_button').html(` <div class="smallclass wd-60" style="padding: 10px 2px 5px 2px;">
																			<input type="checkbox" id="checkbox_03" name="処置のみ" value="処置のみ"/>
																			<label class="radio" for="checkbox_03"> 処置のみ</label><br>
																		</div>
																		<div class="smallclass wd-40" style="padding: 5px 0px 5px 0px;">
																			<input type="button" class="form-control ccenter wd-100" onclick="btn_judgement(this);" value="修正確定"/>
																		</div>`);
				}
			}
			$('#div_overflow').scrollTop(0);
			$(".btn_def").button();
			$('#tab0').attr('checked', true);
			$('[tabindex="1"]').focus();
			if (Object.keys(obj_data['data']).length > 0) {
				if (Object.keys(obj_data['data']).length > 1 && $('#wbn_processing_position' + judgement_id).val() == 1) {
					$('#corrective_table').css('margin-bottom', '0px');
				}
				$('.div_BU_tanto').mouseover(function() {
					$('.div_BU_tanto_view').html(fb_setget_hakko("wbn_BU_decision", "BU担当", "position:absolute;margin-top: 35px;margin-left:60px;"));
				});
				$('.div_BU_tanto').mouseout(function() {
					$('.div_BU_tanto_view').html("");
				});
				obj_data['data'].forEach((value, index) => {
					$('#html_licensor' + index).html(fb_set_hakko("wbn_licensor"));
					$('#html_quantity' + index).html(fb_set_hakko("wbn_quantity", "position:absolute; margin-top: -53px; margin-left:12px;"));
					switch (processing_position) {
						case 4:
							$('#html_licensor' + index).html(fb_get_hakko($("#login_person").val(), today, ""));
							break;
						case 6:
							$('#html_quantity' + index).html(fb_get_hakko($("#login_person").val(), today, "position:absolute; margin-top: -53px; margin-left:12px;"));
							break;
					}
					if (processing_position > 1) {
						$('.das_tanto').mouseover(function() {
							$('.das_tanto_view' + index).html(fb_setget_hakko("wbn_isolation_decision", "範囲決定", "position:absolute; margin-top: 3px; margin-left:8px;"));
						}).mouseout(function() {
							$('.das_tanto_view' + index).html("");
						}).click(function() {
						});
					}
					cav_no = Object.keys(obj_data['data']).length == 1 ? '' : value["wbn_cavino"];
					if (Object.keys(obj_data['data']).length == 1) {
						$('#corrective_table').css('margin-bottom', list_margin_bottom[0] + 'px');
					} else {
						$('#corrective_table').css('margin-bottom', (parseInt(list_margin_bottom[0]) - 33) + 'px');
					}
					$("#wip_numberoftargets_P" + cav_no).focusout(function() {
						const this_cav = this.id.replace("wip_numberoftargets_P", "");
						if (parseInt(this.value) > 0) {
							$("#storage_source" + this_cav).css('display', 'block');
							$("#storage_source" + this_cav).focus();
						} else {
							$("#storage_source" + this_cav).css('display', 'none');
						}
					});
					$("#storage_source" + cav_no).click(function() {
						$(this).removeClass('input_error');
						$('label[name="tab' + cav_no + '"]').removeClass('input_error');
					});
				});
			}
			$("input[tabindex], textarea[tabindex]").each(function() {
				$(this).on("keypress", function(e) {
					if (e.keyCode === 13) {
						var nextElement = $('[tabindex="' + (this.tabIndex + 1) + '"]');
						if (nextElement.length) {
							fb_set_parent_focus(nextElement.attr('id'));
							nextElement.focus();
							e.preventDefault();
						} else
							$('[tabindex="100"]').focus();
					}
				});
			});
			$('input[type!=button][readonly!=readonly]').click(function() {
				$(this).removeClass('input_error');
				fb_set_parent_id(this, "CLR");
				if (this.name == "効果") {
					if (this.value == "効果なし") {
						$('input[name=効果なし]').prop('readonly', false);
					} else {
						$('input[name=効果なし]').prop('readonly', true);
						$('input[name=効果なし]').val('');
					}
				} else if (this.name == "処置のみ") {
					if (this.checked) {
						$('.発生流出').val('');
						$('.発生流出').prop('readonly', true);
						$('.発生流出').removeClass('input_error');
					} else {
						$('.発生流出').prop('readonly', false);
					}
				} else if (this.name == "処置内容") {
					if (this.value == "手直し") {
						$('#rework_instructions').prop('readonly', false);
					} else {
						$('#rework_instructions').prop('readonly', true);
						$('#rework_instructions').val('');
					}
				} else if (this.name == "長期確認が必要") {
					if (this.checked) {
						$('.btn_quality_control').val("保存");
						$('.btn_quality_control').css("background-color", "#00BFFF");
					} else {
						$('.btn_quality_control').val("確認	");
						$('.btn_quality_control').css("background-color", "#04AA6D");
					}
				}
				fb_set_input();
			});
			$("textarea[type!=button]").click(function() {
				$(this).removeClass('input_error');
			});
			$("label.tab_lb").click(function() {
				$(this).removeClass('input_error');
			});
			$('input[name="tabs"]').click(function() {
				$('#corrective_table').css('margin-bottom', (parseInt(list_margin_bottom[this.id.replace("tab", "")]) - 33) + 'px');
			});
			$('textarea').mouseover(function() {
				if (this.scrollHeight > this.offsetHeight) {
					height_save = this.offsetHeight;
					$(this).height(this.scrollHeight);
				}
			});
			$('textarea').mouseout(function() {
				if (height_save) {
					if (height_save < this.offsetHeight) {
						$(this).outerHeight(height_save);
						height_save = 0;
					}
				}
			});
			$('input[type="button"]').mouseover(function(e) {
				if (msg_box) {
					hovFlag = true;
					$('#msg_box_view').css('top', $(this).position().top - 100 + 'px');
					$('#msg_box_view').css('left', 520 + 'px');
					$('#msg_box_view').html(msg_box);
					$('#msg_box_view').css('display', 'block');
				}
			});
			$('input[type="button"]').mouseout(function() {
				hovFlag = false;
				$('#msg_box_view').css('display', 'none');
			});
			$('.RFID_connect_view').dblclick(function() {
				if (processing_position == 1 || parseInt($("#count_RFID" + judgement_id).val()) > 0) {
					fb_view_RFID_input();
				}
			});
			$('.RFID_connect_view input[readonly], .RFID_connect_view td:not(:has(input), :has(select))').click(function() {
				if (processing_position == 1 || parseInt($("#count_RFID" + judgement_id).val()) > 0) fb_view_RFID_input();
			});
			$(document).mousemove(function() {
				if (!hovFlag) {
					$('#msg_box_view').css('display', 'none');
				}
			});
			set_checkbox('修理依頼書', $("#wbn_repair_sheet" + judgement_id).val());
			set_checkbox('欠点分類', $("#wbn_defect_type" + judgement_id).val());
			set_checkbox('発行理由', $("#wbn_reason" + judgement_id).val());
			set_checkbox('処置内容', $("#wbn_treatment" + judgement_id).val());
			set_checkbox('処置のみ', $("#wbn_treatment_only" + judgement_id).val());
			set_checkbox('効果', $("#wbn_effect" + judgement_id).val());
			set_checkbox('復旧確認で可', $("#wbn_restoration" + judgement_id).val());
			set_checkbox('長期確認が必要', $("#wbn_confirmation" + judgement_id).val());
			fb_set_input();
			fb_set_autocomplete('content_confirmation', listevidence);
			if ('file' in obj_data) {
				fb_add_attached('bugcontent');
			}
			if ($('#position' + judgement_id).val().indexOf("再") > -1 || $('#position' + judgement_id).val().indexOf("直し") > -1) {
				fb_add_msg('processing_state');
			}
			if ($('#wbn_corrective_input_person' + judgement_id).val()) {
				$('.発生流出').attr('title', '入力者：' + $('#wbn_corrective_input_person' + judgement_id).val());
			}
			if (placeid != 1000073) {
				$('#bugcontent').attr('title', '不具合内容 \n\n項目	: ' + $('#wbn_defect_item' + judgement_id).val() + '\n小分類	: ' + $('#wbn_project' + judgement_id).val() + '\n分類	: ' + $('#wbn_classification' + judgement_id).val());
			}
		}

		function fb_view_RFID_input() {
			if (!$('#input_das_person').val() && (processing_position == 1 || processing_position == 5 )) {
				fb_get_user();
				return;
			}
			var sum_input = 0;
			$('.class_RFID_input:not([readonly])').each(function() {
				if (parseInt($(this).val()) > 0) {
					sum_input += parseInt($(this).val()); // Or this.innerHTML, this.innerText
				}
			});
			if (sum_input > 0) {
				alert('数を入力しましたので、RFIDを使わずになりました。')
				return;
			}
			$("#alert_dept").dialog({
				autoOpen: false,
				width: 900,
				//height: 900,
				modal: true,
				title: processing_position == 1 ? 'QRコードスキャン画面' : 'RFID連携画面',
				position: [10, 30],
				buttons: [{
					text: kannri_id_view,
					click: function() {
						if (kannri_id_view=='丸めの8形表示') {
							kannri_id_view='管理ナンバー表示';
						} else {
							kannri_id_view='丸めの8形表示';
						}
						$(".my-custom-button-class > .ui-button-text").text(kannri_id_view);
						localStorage.setItem("kannri_id_view", kannri_id_view);
						fb_set_RFID_btn();
					},
					'class': 'my-custom-button-class'
				}, {
					text: "実績入力画面",
					click: function() {
						url_graphic = 'https://track.yasu.nalux.local/RFIDReport/SetBase?plant=' + placename;
						window.open(url_graphic, '_blank');
					}
				}, {
					text: "閉じる",
					click: function() {
						if (!$(".btn_inspection_end").prop('disabled') && $(".btn_inspection_end").length > 0) {
							if (confirm('入力中のデータを削除しますか？') == false) return;
							$('.RFID_input_' + obj_RFID_data[$('#QR_code').attr('placeholder')].input.old.wic_process_key + '3' + cav_no).html(inspection_result_bad_now_view);
						};
						$(this).dialog("close");
					}
				}, ]
			});
			$('#alert_dept').html(`			  <div style="background-color: wheat;" >
																			<div class="msg_input_RFID_view">
																				<div width: 100%;">
																					<div style=" width: 60%;">
																						<div class="find_list_RFID">
																							<div>リストアップ</div>
																							<div><input type="date" id="find_input_start" class="form-control ccenter" /></div>
																							<div> ~ </div>
																							<div><input type="date" id="find_input_end" class="form-control ccenter" /></div>
																							<div class="scan_count"></div>
																						</div>
																						<div class="RFID_view"></div>
																						<div class="RFID_input_view" style="width: 99%; height: 42px;">
																							<div style="width: 13%;"><label>QRコード</label></div>
																							<div style="width: 83%;"><input type="text" id="QR_code" class="form-control ccenter" style="font-weight: bolder;" tabindex="1" ></input></div>
																						</div>
																						<div class="alert_msg"></div>
																					</div>
																					<div class="div_canvas" style="width:40%; padding-left: 5px;" ><canvas id="canvas"></canvas></div>
																				</div>
																				<div class="RFID_view_data"><div class="RFID_view_data_1"></div><div class="RFID_view_data_2"></div><div class="RFID_view_data_3"></div></div>
																			</div>
																		</div>`);
			if (processing_position == 1) {
				$('.find_list_RFID').show();
			} else {
				$('.find_list_RFID').hide();
			}
			$("#alert_dept").dialog("open");
			const video = document.createElement("video");
			const canvas = document.getElementById("canvas");
			const ctx = canvas.getContext("2d");
			$("#alert_dept").dialog({
				open: function(event) {

				},
				close: function(event) {
					if(inspection_result_bad_key){
						$(inspection_result_bad_key).html(inspection_result_bad_now_view);
					}
					if (video.srcObject) {
						const stream = video.srcObject;
						const tracks = stream.getTracks();
						tracks.forEach(function(track) {
							track.stop();
						});
					}
					if (Object.keys(obj_RFID_data).length == 0) {
						$('.stock_confirmation').html(html_tab);
						if (Object.keys(obj_data['data']).length > 1) {
							$('#tab0').attr('checked', true);
						} else {
							$('#content0').css('display', 'block');
						}
						fb_set_input();
						$('.RFID_connect_view').dblclick(function() {
							if (processing_position == 1 || parseInt($("#count_RFID" + judgement_id).val()) > 0) {
								fb_view_RFID_input();
							}
						});
						$('.RFID_connect_view input[readonly], .RFID_connect_view td:not(:has(input))').click(function() {
							if (processing_position == 1 || parseInt($("#count_RFID" + judgement_id).val()) > 0) fb_view_RFID_input();
						});
					}
				}
			});
			is_scan = false;
			$('#find_input_start').val(find_input_start);
			$('#find_input_end').val(find_input_end);
			fb_alert_msg('');
			fb_set_RFID_btn();
			$('.RFID_view_data input').prop('disabled', true);
			fb_cam_open();
			$('#QR_code').prop('disabled', false);
			$('#QR_code').focus();
			$("#QR_code").keypress(function(e) {
				if (e.keyCode == 13) {
					fb_get_data_RFID($(this).val());
					return false;
				}
			}).focusout(function(e) {
				fb_get_data_RFID($(this).val());
			}).click(function() {
				fb_alert_msg('');
			});

			function drawRect(location) {
				drawLine(location.topLeftCorner, location.topRightCorner);
				drawLine(location.topRightCorner, location.bottomRightCorner);
				drawLine(location.bottomRightCorner, location.bottomLeftCorner);
				drawLine(location.bottomLeftCorner, location.topLeftCorner);
			}

			function drawLine(begin, end) {
				ctx.lineWidth = 4;
				ctx.strokeStyle = "#FF3B58";
				ctx.beginPath();
				ctx.moveTo(begin.x, begin.y);
				ctx.lineTo(end.x, end.y);
				ctx.stroke();
			}
			function startTick() {
				if (video.readyState === video.HAVE_ENOUGH_DATA) {
					canvas.height = video.videoHeight;
					canvas.width = video.videoWidth;
					ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
					let img = ctx.getImageData(0, 0, canvas.width, canvas.height);
					let code = jsQR(img.data, img.width, img.height, {
						inversionAttempts: "dontInvert"
					});
					if (code && is_scan) {
						if (code.data) {
							//playCode();
							drawRect(code.location); // Rect
							//if (code.data != $('#QR_code').attr('placeholder')) {
								if ($(".btn_inspection_start").prop('disabled') && $(".btn_inspection_start").length > 0) {
									$('.new_rfid_device input').val(code.data);
									$('.new_rfid_device input').attr('disabled', true);
									$('.new_rfid_device input').removeClass('input_error');
									$('.new_rfid_device input').focus();
									if($('.inspectin_cav_checked').hasClass('cav_division')){
									$('.cav_all').attr('disabled', true);
										obj_RFID_data[$('#QR_code').attr('placeholder')].input.in_division_cav[$('.inspectin_cav_checked').attr('key')].new_rfid = $('.new_rfid_device input').val();
									}else{
										$(".cav_division").attr('disabled', true);
										obj_RFID_data[$('#QR_code').attr('placeholder')].input.in_all_cav[0].new_rfid = $('.new_rfid_device input').val();
									}
									$(".btn_inspection_end").attr('disabled', false);
								}else{
									$("#QR_code").val(code.data);
									fb_get_data_RFID(code.data);
								}

							//}
						}
					}
				}
				setTimeout(startTick, 50);
			}

			function fb_cam_open() {
				navigator.mediaDevices.getUserMedia({
					video: {
						facingMode: "environment"
					}
				}).then((stream) => {
					video.srcObject = stream;
					video.setAttribute("playsinline", true);
					video.play();
					startTick();
				});
			}

			function fb_cam_close() {
				const tracks = stream.getTracks();
				tracks.forEach(function(track) {
					track.stop();
				});
				video.srcObject = null;
			}

			$('.find_list_RFID input').on('input', function(event) {
				if (Object.keys(obj_RFID_data).length != parseInt($("#count_RFID" + judgement_id).val()) && Object.keys(obj_RFID_data).length > 0) {
					if (confirm('再リストアップを検索しますか。') == false) {
						return false;
					}
				}
				fb_loading(true);
				$('.find_list_RFID input').removeClass('error_data');
				find_input_start = $('#find_input_start').val();
				find_input_end = $('#find_input_end').val();
				var datas = {
					ac: "Ajax_Find_List_RFID_Data",
					placename: placename,
					placeid: placeid,
					wbn_id: judgement_id,
					itemcode: $('#wbn_item_code' + judgement_id).val(),
					itemform: $('#wbn_form_no' + judgement_id).val(),
					itemcav: $('#CAVno' + judgement_id).val(),
					lot_no: $('#wbn_lot_no' + judgement_id).val(),
					find_input_start: $('#find_input_start').val() ? $('#find_input_start').val() : '2023-01-01',
					find_input_end: $('#find_input_end').val() ? $('#find_input_end').val() : today,
				}
				$.ajax({
					type: 'POST',
					url: "",
					dataType: 'json',
					data: datas,
					success: function(d) {
						obj_find_list_RFID_data = {};
						if('old' in d) {
							array_molding_id = d.old;
						}
						if('new' in d){
							d.new.forEach((value, key) => {
								if(fb_ischeck_cav($('#CAVno' + judgement_id).val(),value.hgpd_cav)){
									if(value.hgpd_rfid !== null){
										if(value.hgpd_rfid.length > 0){
										obj_find_list_RFID_data[value.hgpd_rfid] ??= {};
										obj_find_list_RFID_data[value.hgpd_rfid].input ??= {};
										obj_find_list_RFID_data[value.hgpd_rfid].input.old = value;
										obj_find_list_RFID_data[value.hgpd_rfid].input.new = value;
										}
									}
								}
							});
						}
						RFID_input_check = false;
						fb_alert_msg('');
						fb_set_RFID_btn();
						$('.scan_count').html(Object.keys(obj_find_list_RFID_data).length + "件数");
						if (Object.keys(d.new).length == 0) {
							fb_alert_msg("成形日にとしてリストアップのデータがありません。");
							$('.scan_count').html("");
						}

					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
						fb_loading(false);
						return;
					}
				});
			});
		}

		function fb_ischeck_cav(in_bug_cav, in_rfid_cav){
			if (!in_bug_cav || !in_rfid_cav) return false;
			var list_bug_cav = [], list_rfid_cav = [];
			var flg_is_check;
			in_bug_cav.split(",").forEach(function(value, index){
				if(value.split("-").length === 2){
					list_bug_cav = list_bug_cav.concat(fb_split_cav(value, "-"));
				}else if(value.split("~").length === 2){
					list_bug_cav = list_bug_cav.concat(fb_split_cav(value, "~"));
				}else{
					if(isNumberCase(value)){
						list_bug_cav.push(fb_parseInt(value));
					}else{
						list_bug_cav.push(value);
					}
				}
			});
			in_rfid_cav.split(",").forEach(function(value, index){
				if(value.split("-").length === 2){
					list_rfid_cav = list_rfid_cav.concat(fb_split_cav(value, "-"));
				}else if(value.split("~").length === 2){
					list_rfid_cav = list_rfid_cav.concat(fb_split_cav(value, "~"));
				}else{
					if(isNumberCase(value)){
						list_rfid_cav.push(fb_parseInt(value));
					}else{
						list_rfid_cav.push(value);
					}
				}
			});
			flg_is_check = true;
			list_bug_cav.forEach(function(value, index){
				if(!list_rfid_cav.includes(value)) flg_is_check = false;
			});
			if(flg_is_check) return true;
			flg_is_check = true;
			list_rfid_cav.forEach(function(value, index){
				if(!list_bug_cav.includes(value)) flg_is_check = false;
			});
			return flg_is_check;
		}

		function fb_is_cav_rfid_in_bug(in_bug_cav, in_rfid_cav){
			if (!in_bug_cav || !in_rfid_cav) return false;
			var list_bug_cav = [], list_rfid_cav = [];
			var flg_is_check = true;
			in_bug_cav.split(",").forEach(function(value, index){
				if(value.split("-").length === 2){
					list_bug_cav = list_bug_cav.concat(fb_split_cav(value, "-"));
				}else if(value.split("~").length === 2){
					list_bug_cav = list_bug_cav.concat(fb_split_cav(value, "~"));
				}else{
					if(isNumberCase(value)){
						list_bug_cav.push(fb_parseInt(value));
					}else{
						list_bug_cav.push(value);
					}
				}
			});
			in_rfid_cav.split(",").forEach(function(value, index){
				if(value.split("-").length === 2){
					list_rfid_cav = list_rfid_cav.concat(fb_split_cav(value, "-"));
				}else if(value.split("~").length === 2){
					list_rfid_cav = list_rfid_cav.concat(fb_split_cav(value, "~"));
				}else{
					if(isNumberCase(value)){
						list_rfid_cav.push(fb_parseInt(value));
					}else{
						list_rfid_cav.push(value);
					}
				}
			});
			list_rfid_cav.forEach(function(value, index){
				if(!list_bug_cav.includes(value)) flg_is_check = false;
			});
			return flg_is_check;
		}

		function fb_split_cav(value, key){
			var ref_array = [];
			if(isNumberCase(value.split(key)[0]) && isNumberCase(value.split(key)[1])){
				for(i = fb_parseInt(value.split(key)[0]) ; i<=fb_parseInt(value.split(key)[1]); i++){
					ref_array.push(fb_parseInt(i));
				}
			} else if(!isNumberCase(value.split(key)[0]) && !isNumberCase(value.split(key)[1]) && value.split(key)[0].length == 1 && value.split(key)[1].length == 1){
				for(i = value.split(key)[0].charCodeAt(0); i<=value.split(key)[1].charCodeAt(0); i++){
					ref_array.push(String.fromCharCode(i));
				}
			} else{
				ref_array.push(value);
			}
			return ref_array;
		}

		function fb_get_data_RFID(item) {
			if (item != "") {
				fb_alert_msg('');
				var var_in_inspectin_data;
				var RFID_id = toHalfWidth(item).replace(/\s+/g, '');
				$('#QR_code').val('');
				const placeholder_rfid = $('#QR_code').attr('placeholder');
				$('#QR_code').attr('placeholder', RFID_id);
				if (processing_position == 5) {
					if (!$(".btn_inspection_end").prop('disabled') && $(".btn_inspection_end").length > 0) {
						if (confirm('入力中のデータを削除しますか？') == false) return;
						$('.RFID_input_' + obj_RFID_data[placeholder_rfid].input.old.wic_process_key + '3' + cav_no).html(inspection_result_bad_now_view);
					};
					if (RFID_id in obj_RFID_data) {
						var var_RFID_data = obj_RFID_data[RFID_id].input;
						cav_no = Object.keys(obj_data['data']).length == 1 ? '' : var_RFID_data.old.hgpd_cav;
						inspection_result_bad_now_view = fb_parseInt($('.RFID_input_' + var_RFID_data.old.wic_process_key + '3' + cav_no).html());
						inspection_result_bad_key = '.RFID_input_' + var_RFID_data.old.wic_process_key + '3' + cav_no;
						fb_get_data_scan(obj_RFID_data[RFID_id]);
						$('.RFID_view_data_3').html('<div style="float: left;" class="inspectin_view"></div>');
						var_RFID_data.in_all_cav=[];
						var_RFID_data.in_all_cav[0]={};
						var_RFID_data.in_all_cav[0].result_qty = var_RFID_data.new.hgpd_qtycomplete;
						var_RFID_data.in_all_cav[0].result_in = var_RFID_data.new.hgpd_qtycomplete;
						var_RFID_data.in_all_cav[0].insert_cav = var_RFID_data.old.hgpd_cav;
						var_RFID_data.new.alignment_hgpd_id = var_RFID_data.new.hgpd_id;
						if ("output" in obj_RFID_data[RFID_id]){
							obj_RFID_data[RFID_id].output.forEach(function(value, index) {
								if (parseInt(var_RFID_data.in_all_cav[0].result_qty) >= parseInt(value.hgpd_remaining)) {
									var_RFID_data.in_all_cav[0].result_qty = parseInt(value.hgpd_remaining);
									var_RFID_data.new.alignment_hgpd_id = value.hgpd_id;
								}
							});
						}
						if (var_RFID_data.in_all_cav[0].result_qty == 0) {
							$('.RFID_view_data_3').html(`<br><p style="color:green;">　　保留処理完了済</p>`);
						} else {
							$('.inspectin_view').html(`
								<table style='float:left; margin-top:8px;'>
									<thead>
										<tr><th  colspan="2" class="RFID_view_1">検査結果</th><th></th></tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="2">
											<div style="float: left; width: 40%"><input type="button" class="form-control btn_inspection_start" onclick="" value="開始"/></div></td>
											<td></td>
										</tr>
										<tr class="inspection_result_start"><td>開始日時</td><td style="float: right;"><input type="datetime-local"  readonly/></td><td></td></tr>
										<tr class="tr_inspectin_cav"><td  colspan="2" class="td_inspectin_cav"></td><td></td></tr>
										<tr class="inspection_result_qty"><td style="width: 70px;">生産数</td><td style="width: 250px"><input type="text" class="form-control ccenter used_num"" style="font-weight: bolder;" value="` + var_RFID_data.in_all_cav[0].result_qty + `" readonly></input></td><td></td></tr>
										<tr class="inspection_result_in"><td>検査数</td><td><input type="text" class="form-control ccenter input_inspection" style="font-weight: bolder;" readonly></input></td><td></td></tr>
										<tr class="inspection_result_good"><td>良品数</td><td><input type="text" class="form-control ccenter good_num" style="font-weight: bolder;" readonly></input></td><td></td></tr>
										<tr class="inspection_result_bad"><td>不良数</td><td><input type="text" min="0" max="` + var_RFID_data.in_all_cav[0].result_qty + `" class="form-control ccenter input_inspection bad_num" style="font-weight: bolder;" readonly></input></td><td></td><td></td></tr>
										<tr class="inspection_result_remaining"><td>残数</td><td><input type="text" min="0" max="` + var_RFID_data.in_all_cav[0].result_qty + `" class="form-control ccenter input_inspection" style="font-weight: bolder;" ></input></td><td></td></tr>
										<tr class="new_rfid_device"><td colspan="3" style="color: red; font-weight: bolder;">完成品から仕掛品に入庫する為 新しい仕掛品のQRコードをスキャンしてください。</td></tr>
										<tr class="new_rfid_device"><td>仕掛RFID</td><td colspan="2"><input type="text" class="form-control ccenter" style="font-weight: bolder; width: 300px" placeholder="確定をすると「ENTER」キーを押して下さい" title="記入確定をすると「ENTER」キーを押してください。"></input></td></tr>
										<tr class="inspection_result_end"><td>終了日時</td><td style="float: right;"><input type="datetime-local" id="in_datetime_end" readonly/></td><td></td></tr>
										<td colspan="2">
											<div style="float: left; width: 40%"><input type="button" class="form-control btn_inspection_result_remaining" onclick="" value="中断" disabled /></div>
											<div style="float: right; width: 40%"><input type="button" class="form-control btn_inspection_end" onclick="fb_rfid_pending_processing(this);" value="終了" disabled /></div>
										</td><td></td>
									</tbody>
								</table>`);
							$(".new_rfid_device").hide();
							$(".inspection_result_remaining").hide();
						}
						if(var_RFID_data.old_data.length > 1){
							$('.td_inspectin_cav').append('<div><input type="button" class="form-control ccenter cav_all" onclick="fb_inspectin_view();" value="#'+var_RFID_data.old.hgpd_cav+'" disabled/><div>');
							let var_check_qty_in = 0;
							var_RFID_data.in_division_cav=[];
							var_RFID_data.old_data.forEach((value, key) => {
								var_check_qty_in+=parseInt(value.wic_qty_in);
								var_RFID_data.in_division_cav[key]={};
								var_RFID_data.in_division_cav[key].insert_cav = value.wic_itemcav;
								var_RFID_data.in_division_cav[key].result_qty = value.wic_qty_in;
								var_RFID_data.in_division_cav[key].result_in = value.wic_qty_in;
								var_RFID_data.in_division_cav[key].new_rfid = '';
								$('.td_inspectin_cav').append('<div><input type="button" class="form-control ccenter cav_division" onclick="fb_inspectin_view('+key+');" key="'+key+'" value="#'+value.wic_itemcav+'" disabled/><div>');
							});
							if(parseInt(var_check_qty_in) !== parseInt(var_RFID_data.new.hgpd_qtycomplete)){
								$('div[rfid="' + RFID_id + '"]').addClass('input_error');
							}
						}else{
							$(".tr_inspectin_cav").hide();
						}
						$(".btn_def").button();
						$('.RFID_view div').removeClass('RFID_btn_checked');
						$('div[rfid="' + RFID_id + '"]').addClass('RFID_btn_checked');
						$('.tab' + cav_no).attr('checked', true);
						$(".input_inspection").on('input', function() {
							$('.inspectin_cav_checked').removeClass("input_error");
							if($('.inspection_result_bad input').val() != "")$('.inspection_result_good input').val(parseInt($('.inspection_result_qty input').val() - $('.inspection_result_bad input').val()- $('.inspection_result_remaining input').val()));
							$('.RFID_input_' + var_RFID_data.old.wic_process_key + '3' + cav_no).html(inspection_result_bad_now_view + fb_parseInt($('.inspection_result_bad input').val()));
							$('.tab' + cav_no).attr('checked', true);
							if (parseInt($('.inspection_result_good input').val()) < 0) {
								$(this).addClass('input_error');
								$(".btn_inspection_end").attr('disabled', true);
							} else {
								$(this).removeClass('input_error');
								$(".btn_inspection_end").attr('disabled', false);
							}
							if (parseInt($('.inspection_result_in input').val()) > parseInt($('.inspection_result_qty input').val() - $('.inspection_result_remaining input').val())) {
								$('.inspection_result_in input').addClass('input_error');
							} else {
								$('.inspection_result_in input').removeClass('input_error');
							}
							if($('.inspectin_cav_checked').hasClass('cav_division')){
								$('.cav_all').attr('disabled', true);
								var_in_inspectin_data = obj_RFID_data[RFID_id].input.in_division_cav[$('.inspectin_cav_checked').attr('key')];
							}else{
								$(".cav_division").attr('disabled', true);
								var_in_inspectin_data = obj_RFID_data[RFID_id].input.in_all_cav[0];
							}
							var_in_inspectin_data.result_in = fb_parseInt($('.inspection_result_in input').val());
							var_in_inspectin_data.result_good = fb_parseInt($('.inspection_result_good input').val());
							var_in_inspectin_data.result_bad = fb_parseInt($('.inspection_result_bad input').val());
							var_in_inspectin_data.result_remaining = fb_parseInt($('.inspection_result_remaining input').val());
							if (obj_RFID_data[$("#QR_code").attr('placeholder')].input.old.wic_process.includes("完成") && (parseInt($('.inspection_result_good input').val())>0) && (parseInt($('.inspection_result_bad input').val())>0)) {
								$(".new_rfid_device").show();
								$(".new_rfid_device input").prop('disabled', false);
								$(".btn_inspection_end").attr('disabled', true);
								if($(".new_rfid_device input").val() == "" || $(".new_rfid_device input").val() == $("#QR_code").attr('placeholder')){
									$(".new_rfid_device input").val("");
									$(".new_rfid_device input").addClass('input_error');
								}
							}else{
								$(".new_rfid_device").hide();
								$(".new_rfid_device input").val($("#QR_code").attr('placeholder'));
								var_in_inspectin_data.new_rfid = $('.new_rfid_device input').val();
							}
						});
						$(".td_inspectin_cav input").click(function(e) {
							$('.td_inspectin_cav input').removeClass('inspectin_cav_checked');
							$(this).addClass('inspectin_cav_checked');
						});
						$(".btn_inspection_start").click(function(e) {
							$(".btn_inspection_start").attr('disabled', true);
							$(".td_inspectin_cav input").attr('disabled', false);
							$(".cav_all").addClass('inspectin_cav_checked');
							$('.inspection_result_in input').val($('.inspection_result_qty input').val());
							$('.inspection_result_in input').prop('readonly', false);
							$('.inspection_result_bad input').prop('readonly', false);
							$('.inspection_result_start input').prop('readonly', false);
							$('.inspection_result_end input').prop('readonly', false);
							$(".btn_inspection_result_remaining").attr('disabled', false);
							$(".inspection_result_start input").val(fb_format_date(new Date(), "YYYY-MM-DD hh:mm:ss"));
							$('.inspection_result_in input').focus();
						});
						$(".btn_inspection_result_remaining").click(function(e) {
							if(this.value == "修正"){
								$('.inspection_result_in input').prop('readonly', false);
								$('.inspection_result_bad input').prop('readonly', false);
								$('.inspection_result_remaining input').prop('readonly', false);
								$('.new_rfid_device input').prop('readonly', false);
								$(".btn_inspection_end").val("終了");
								$('.inspection_result_in input').focus();
								$(".new_rfid_device").hide();
								this.value = "中断";
							}else{
								$(".inspection_result_remaining").show();
								$(this).attr('disabled', true);
							}
						});
						$(".new_rfid_device input").keypress(function(e) {
						if (e.keyCode == 13) {
							if($(".new_rfid_device input").val() == "" || $(".new_rfid_device input").val() == $("#QR_code").attr('placeholder')){
								$(".new_rfid_device input").addClass('input_error');
								$(".btn_inspection_end").attr('disabled', true);
								return;
							}
							$('.new_rfid_device input').removeClass('input_error');
							$(".btn_inspection_end").attr('disabled', false);
							var_in_inspectin_data.new_rfid = $('.new_rfid_device input').val();
						}
					});
					} else {
						fb_alert_msg("リストアップ以外RFIDをスキャンしました。");
						return;
					}
				} else if (processing_position == 1) {
					if (RFID_id in obj_RFID_data) {
						fb_alert_msg("このRFIDはスキャンしました。");
						return;
					}
					is_scan = false;
					if (Object.keys(obj_find_list_RFID_data).length == 0) {
						fb_alert_msg("リストアップを検索してください。");
						return;
					}
					if (!obj_find_list_RFID_data.hasOwnProperty(RFID_id)) {
						fb_alert_msg("リストアップ以外RFIDをスキャンしました。");
						return;
					}
					fb_get_data_scan(obj_find_list_RFID_data[RFID_id]);
					if (!obj_find_list_RFID_data[RFID_id].input.old.wic_id) {
						fb_alert_msg("在庫管理データがありません。確認してください。");
						return;
					}
					obj_RFID_data[RFID_id] = {};
					obj_RFID_data[RFID_id].input = obj_find_list_RFID_data[RFID_id].input;
					fb_set_RFID_btn();
					if (Object.keys(obj_data['data']).length > 1) $('.tab' + obj_find_list_RFID_data[RFID_id].hgpd_cav).attr('checked', true);
				}else{
					if (!$(".btn_inspection_end").prop('disabled') && $(".btn_inspection_end").length > 0) {
						if (confirm('入力中のデータが削除しますか？') == false) return;
						$('.RFID_input_' + var_RFID_data.old.wic_process_key + '3' + cav_no).html(inspection_result_bad_now_view);
					};
					fb_alert_msg('');
					$('.RFID_view div').removeClass('RFID_btn_checked');
					fb_get_data_scan(obj_RFID_data[RFID_id]);
					$(this).addClass('RFID_btn_checked');
					$('#QR_code').focus();
					if ($(this).hasClass('error_data')) {
						if (!var_RFID_data.hgpd_id) {
							fb_alert_msg("実績データがありません。確認してください。");
						} else if (!var_RFID_data.old.wic_id) {
							fb_alert_msg("在庫管理データがありません。確認してください。");
						} else if (!obj_find_list_RFID_data.hasOwnProperty(RFID_id)) {
							fb_alert_msg("リストアップ以外データ<br>リストアップを再検索してください。");
						} else if (RFID_input_error_list.indexOf(RFID_id) > -1 && RFID_input_error_list.length > 0) {
							fb_alert_msg("上記のRFIDが登録されました。確認してください。");
						}
					}
				}
				is_scan = true;
			}
		}

		function fb_inspectin_view(item){
			var var_in_inspectin_data;
			const placeholder_rfid = $('#QR_code').attr('placeholder');
			$('.inspectin_view input').removeClass('input_error');
			if (item === undefined){
        var_in_inspectin_data = obj_RFID_data[placeholder_rfid].input.in_all_cav[0];
      }else{
				var_in_inspectin_data = obj_RFID_data[placeholder_rfid].input.in_division_cav[item];
			}
			$('.inspection_result_in input').val(var_in_inspectin_data.result_in);
			$('.inspection_result_good input').val(var_in_inspectin_data.result_good);
			$('.inspection_result_bad input').val(var_in_inspectin_data.result_bad);
			$('.inspection_result_remaining input').val(var_in_inspectin_data.result_remaining);
			$('.inspection_result_qty input').val(var_in_inspectin_data.result_qty);
			if (obj_RFID_data[$("#QR_code").attr('placeholder')].input.old.wic_process.includes("完成") && (parseInt($('.inspection_result_good input').val())>0) && (parseInt($('.inspection_result_bad input').val())>0)) {
				$(".new_rfid_device").show();
				$('.new_rfid_device input').val(var_in_inspectin_data.new_rfid);
				if ($('.btn_inspection_end').val() != "登録"){
					$(".new_rfid_device input").focus();
					$(".new_rfid_device input").prop('disabled', false);
					$(".btn_inspection_end").attr('disabled', true);
					if($(".new_rfid_device input").val() == "" || $(".new_rfid_device input").val() == $("#QR_code").attr('placeholder')){
						$(".new_rfid_device input").val("");
						$(".new_rfid_device input").addClass('input_error');
					}
				}else{
					$(".new_rfid_device").hide();
					$(".new_rfid_device input").prop('disabled', true);
				}
			}else{
				$(".new_rfid_device").hide();
				$(".new_rfid_device input").val($("#QR_code").attr('placeholder'));
			}
		}

		function fb_rfid_pending_processing(item) {
			var var_in_inspectin_data;
			var item_input;
			var rf_check = true;
			const RFID_placeholder = $("#QR_code").attr('placeholder');
			if($('.inspection_result_bad input').val() == "" || $('.inspection_result_bad input').val() == null ){
				$('.inspection_result_bad input').addClass('input_error');
				return;
			}
			if(fb_parseInt($('.inspection_result_in input').val()) < 1 ){
				$('.inspection_result_in input').addClass('input_error');
				return;
			}
			if($('.inspectin_cav_checked').hasClass('cav_division')){
				var_in_inspectin_data = obj_RFID_data[RFID_placeholder].input.in_division_cav;
			}else{
				var_in_inspectin_data = obj_RFID_data[RFID_placeholder].input.in_all_cav;
			}
			var_in_inspectin_data.forEach((value, key) => {
					if(fb_parseInt(value.result_good) + fb_parseInt(value.result_bad) < 1){
						if($('.inspectin_cav_checked').hasClass('cav_division')){
							$('.td_inspectin_cav input[key="'+key+'"]').addClass('input_error');
						}else{
							$('.cav_all').addClass('input_error');
						}
						rf_check = false;
					}
				});
			if (rf_check == false){
				return;
			}
			if(item.value == "終了"){
				$('.inspection_result_in input').prop('readonly', true);
				$('.inspection_result_bad input').prop('readonly', true);
				$('.inspection_result_remaining input').prop('readonly', true);
				$(".btn_inspection_result_remaining").val("修正");
				$(".btn_inspection_result_remaining").attr('disabled', false);
				$("#in_datetime_end").focus();
				$(".inspection_result_end input").val(fb_format_date(new Date(), "YYYY-MM-DD hh:mm:ss"));
				item.value = "登録";
			}else{
				if (confirm('入力中のデータが登録しますか？') == false) return;
				var info = {};
				info['moldplaceid'] = placeid;
				info['moldplace'] = placename;
				info['xlsnum'] = xlsnum;
				info['workkind'] = workitem_class;
				info['date'] = today;
				info['username'] = $('#input_das_person').val();
				info['usercord'] = $('#usercord').val();
				info['usergp1'] = $('#gp1').val();
				info['usergp2'] = $('#gp2').val();
				info['workitem'] = workitem_name;
				info['itemname'] = $('#wbn_product_name' + judgement_id).val();
				info['itemcord'] = $('#wbn_item_code' + judgement_id).val();
				info['itemform'] = $('#wbn_form_no' + judgement_id).val();
				info['pdate'] = $('#wbn_mold_dt' + judgement_id).val();
				info['moldlot'] = $('#wbn_lot_no' + judgement_id).val();
				obj_RFID_data[RFID_placeholder].input.new.単価 = 0;
				obj_RFID_data[RFID_placeholder].input.new.wbn_id = judgement_id;
				obj_RFID_data[RFID_placeholder].input.new.作業時間 = fb_diff_sec($(".inspection_result_start input").val(),$(".inspection_result_end input").val());
				var input_data= {
						inspectin_data: var_in_inspectin_data,
						in_dt_start: $(".inspection_result_start input").val(),
						in_dt_end: $(".inspection_result_end input").val(),
						defect_item: $('#wbn_defect_item' + judgement_id).val(),
						old_wic_wherhose: obj_RFID_data[RFID_placeholder].input.old.wic_wherhose,
						old_hgpd_process: obj_RFID_data[RFID_placeholder].input.old.hgpd_process,
						old_hgpd_rfid: obj_RFID_data[RFID_placeholder].input.old.hgpd_rfid,
						rfid_data_new: obj_RFID_data[RFID_placeholder].input.new,
					};
				fb_loading(true);
				$.ajax({
					type: 'POST',
					url: "",
					dataType: 'html',
					data: {
						ac: "保留処理実行",
						info: info,
						oh_palce: oh_palce,
						input_data: input_data},
					success: function(d) {
						$('.RFID_view_data_3').html('');
						inspection_result_bad_now_view = $(inspection_result_bad_key).html();
						//$("#alert_dept").dialog("close");
						fb_get_RFID_data(3, RFID_placeholder);
						fb_loading(false);
						return;
					},
					error: function() {
						fb_loading(false);
						alert("エラー");
					}
				});
			}

		}

		function fb_get_RFID_data(instructions_mode, ref_RFID_placeholder) {
			var json_string_obj_RFID_data = JSON.stringify(Object.keys(obj_RFID_data).sort());
			var datas = {
				ac: "Ajax_Get_RFID_Connect_Data",
				placename: placename,
				placeid: placeid,
				wbn_id: judgement_id,
			}
			if (instructions_mode != 1) fb_loading(true);
			$.ajax({
				type: 'GET',
				url: "",
				dataType: 'json',
				data: datas,
				success: function(d) {
					if (Object.keys(d.new).length > 0) {
						obj_RFID_data = [];
						d.new.forEach((value, key) => {
							value.wicbn_rfid ??= value.hgpd_rfid;
							obj_RFID_data[value.wicbn_rfid] ??= {};
							obj_RFID_data[value.wicbn_rfid].input ??= [];
							obj_RFID_data[value.wicbn_rfid].input.old ??= [];
							obj_RFID_data[value.wicbn_rfid].input.new ??= [];
							obj_RFID_data[value.wicbn_rfid].input.old_data ??= [];
							obj_RFID_data[value.wicbn_rfid].output ??= [];
							if(value.wicbn_id && value.hgpd_process != "保留処理") {
								obj_RFID_data[value.wicbn_rfid].input.old = value;
								obj_RFID_data[value.wicbn_rfid].input.old_data.push(value);
							}else if(value.wicbn_id && value.hgpd_process == "保留処理"){
								let flg_check = true;
								if(obj_RFID_data[value.wicbn_rfid].output.length > 0){
									obj_RFID_data[value.wicbn_rfid].output.forEach((val, k) => {
										if(val.hgpd_id == value.hgpd_id) {
											flg_check = false;
											obj_RFID_data[value.wicbn_rfid].output[k] = value;
										}
									});
								}
								if (flg_check) obj_RFID_data[value.wicbn_rfid].output.push(value);
							}else if(!value.wicbn_id && value.hgpd_process == "保留処理"){
								obj_RFID_data[value.wicbn_rfid].input.new = value;
							}
						});
						fb_set_stock_confirmation();
						fb_set_RFID_btn();
						if (instructions_mode == 2){
							fb_alert_msg('データが更新されました');
						}else if (instructions_mode == 3){
							$('.RFID_Alignment[rfid="' + ref_RFID_placeholder + '"]').click();
						}
						fb_loading(false);
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
					fb_loading(false);
				}
			});
		}

		function fb_set_RFID_btn() {
			$('.RFID_view').html("");
			var obj_input_view, obj_output_view;
			// $('.RFID_view').css('height', '100px');
			if (Object.keys(obj_find_list_RFID_data).length > 0) {
				Object.keys(obj_find_list_RFID_data).forEach(function(obj_keys) {
					let obj_input = obj_find_list_RFID_data[obj_keys].input;
					if('old' in obj_input){
						obj_input.old.wic_inventry_num ??= 0;
						//$('.RFID_view').append('<div class="RFID_btn RFID_Alignment_FIND" rfid="' + obj_keys + '">' + obj_input.old.hgpd_id + '</div>');
						if (!$('.RFID_view .RFID_' + obj_input.old.wic_process).length) {
							$('.RFID_view').append('<fieldset><legend class="' + fb_get_class_process(obj_input.old) + '">' + obj_input.old.wic_process + '</legend><div class="RFID_PROCESS RFID_' + obj_input.old.wic_process + '"></div></fieldset>');
						}
						$('.RFID_view .RFID_' + obj_input.old.wic_process).append('<div class="RFID_btn RFID_Alignment_FIND" rfid="' + obj_keys + '">' + (kannri_id_view=='丸めの8形表示'? obj_input.old.hgpd_id : obj_keys.substring(obj_keys.length - 8, obj_keys.length)) + '</div>');
						if (!obj_input.old.wic_id) $('div[rfid="' + obj_keys + '"]').addClass('error_data');
						// if (!obj_RFID_data.hasOwnProperty(obj_keys)) {
						// 	$('div[rfid="' + obj_keys + '"]').append('<div style="float:right;"><img onclick="fb_get_data_RFID(\'' + obj_keys + '\')" class="btn_add_del" src="/MissingDefect/GetFile?menu=img&mfc_id=3"/></div>');
						// }
					}
					if (!obj_RFID_data.hasOwnProperty(obj_keys) && RFID_input_check) $('div[rfid="' + obj_keys + '"]').addClass('error_data');
				});
			}
			if (Object.keys(obj_RFID_data).length > 0) {
				var check_number;
				Object.keys(obj_RFID_data).forEach(function(obj_keys) {
					if (obj_RFID_data[obj_keys].hasOwnProperty('input')) {
						let obj_input = obj_RFID_data[obj_keys].input;
						if('old' in obj_input){
							check_number = obj_input.old.hgpd_qtycomplete;
							obj_input.old.wic_inventry_num ??= 0;
							if (obj_find_list_RFID_data.hasOwnProperty(obj_keys)) {
								$('div[rfid="' + obj_keys + '"]').removeClass("RFID_Alignment_FIND").addClass('RFID_Alignment');
								if (!obj_input.old.work_bug_notification_id) {
									$('div[rfid="' + obj_keys + '"]').append('<div style="float:right;"><img onclick="fb_del_RFID(\'' + obj_keys + '\')" class="btn_add_del" src="/MissingDefect/GetFile?menu=img&mfc_id=2"/></div>');
									if (RFID_input_error_list.indexOf(obj_keys) > -1 && RFID_input_error_list.length > 0) $('div[rfid="' + obj_keys + '"]').addClass('error_data');
								}
							} else {
								$('.RFID_view').append('<div class="RFID_btn RFID_Alignment" rfid="' + obj_keys + '">' + (kannri_id_view=='丸めの8形表示' ? obj_input.old.hgpd_id : obj_keys.substring(obj_keys.length - 8, obj_keys.length)) + '</div>');
								if (Object.keys(obj_find_list_RFID_data).length > 0) $('.RFID_Alignment[rfid="' + obj_keys + '"]').addClass('error_data');
							}
							if (!obj_input.old.wic_id) $('.RFID_Alignment[rfid="' + obj_keys + '"]').addClass('error_data');
						}
					}
					if (obj_RFID_data[obj_keys].hasOwnProperty('output')) {
						obj_RFID_data[obj_keys].output.forEach((val, k) => {
							check_number -= fb_parseInt(val.hgpd_difactive) + fb_parseInt(val.hgpd_qtycomplete);
						});
					}
					if(check_number == 0){
						$('div[rfid="' + obj_keys + '"]').css("background-color","blue");
					}
				});
			}

			$('.RFID_Alignment_FIND').click(function() {
				fb_alert_msg('');
				let item = $(this).attr('rfid');
				$('.RFID_view div').removeClass('RFID_btn_checked');
				fb_get_data_scan(obj_find_list_RFID_data[item]);
				$(this).addClass('RFID_btn_checked');
				$('#QR_code').focus();
				if (!obj_find_list_RFID_data[item].input.old.wic_id) {
					fb_alert_msg("在庫管理データがありません。確認してください。");
				} else if ($(this).hasClass('error_data')) {
					if ((!obj_RFID_data.hasOwnProperty(item))) {
						fb_alert_msg("上記のRFIDをスキャンしてください。");
					}
				}
			});
			$('.RFID_Alignment').click(function() {
				if (!$(".btn_inspection_end").prop('disabled') && $(".btn_inspection_end").length > 0) {
					if (confirm('入力中のデータを削除しますか？') == false) return;
					$('.RFID_input_' + obj_RFID_data[$('#QR_code').attr('placeholder')].input.old.wic_process_key + '3' + cav_no).html(inspection_result_bad_now_view);
				};
				fb_alert_msg('');
				let item = $(this).attr('rfid');
				$('.RFID_view div').removeClass('RFID_btn_checked');
				fb_get_data_scan(obj_RFID_data[item]);
				$(this).addClass('RFID_btn_checked');
				$('#QR_code').focus();
				if ($(this).hasClass('error_data')) {
					if (!obj_RFID_data[item].input.old.hgpd_id) {
						fb_alert_msg("実績データがありません。確認してください。");
					} else if (!obj_RFID_data[item].input.old.wic_id) {
						fb_alert_msg("在庫管理データがありません。確認してください。");
					} else if (!obj_find_list_RFID_data.hasOwnProperty(item)) {
						fb_alert_msg("リストアップ以外データ<br>リストアップを再検索してください。");
					} else if (RFID_input_error_list.indexOf(item) > -1 && RFID_input_error_list.length > 0) {
						fb_alert_msg("上記のRFIDが登録されました。確認してください。");
					}
				}
			});
			if (processing_position == 1) {
				fb_set_stock_confirmation();
			}
			fb_loading(false);
		}

		function fb_get_data_scan(input_item) {
			let old_item = input_item.input.old;
			let new_item = input_item.input.new;
			$('.RFID_view_data_1').html("");
			$('.RFID_view_data_2').html("");
			$('.RFID_view_data_3').html("");
			$('#QR_code').attr('placeholder', old_item.hgpd_rfid);
			$('.RFID_view_data_1').html(`<table>
																<thead>
																	<tr><th colspan="2" class="` + fb_get_class_process(old_item) + `">` + old_item.hgpd_process + `</th></tr>
																</thead>
																<tbody>
																	<tr class="chil-tr"><td style='min-width: 65px'>在庫ID</td><td style='max-width: 220px; min-width: 150px'>` + fb_get_string(new_item.wic_id) + `</td></tr>
																	<tr class="chil-tr"><td>管理ID</td><td>`  + fb_get_string(old_item.hgpd_id) + `</td></tr>
																	<tr class="chil-tr"><td>RFID</td><td title="` + old_item.hgpd_rfid + `">` + fb_string_hide(old_item.hgpd_rfid) + `</td></tr>
																	<tr class="chil-tr"><td>` + (old_item.wic_process.includes("成形") ? '成形日' : '作業日') + `</td><td>` + old_item.hgpd_moldday + `</td></tr>
																	<tr class="chil-tr"><td>型番</td><td>` + old_item.hgpd_itemform + `</td></tr>
																	<tr class="chil-tr"><td>成形lot</td><td>` + old_item.hgpd_moldlot + `</td></tr>
																	<tr class="chil-tr"><td>キャビ</td><td>` + old_item.hgpd_cav + `</td></tr>
																	<tr class="chil-tr"><td>生産数</td><td class="used_num">` + old_item.hgpd_quantity + `</td></tr>
																	<tr class="chil-tr"><td>良品数</td><td class="good_num">` + old_item.hgpd_qtycomplete + `</td></tr>
																	<tr class="chil-tr"><td>不良数</td><td class="bad_num">` + old_item.hgpd_difactive + `</td></tr>
																	<tr class="chil-tr"><td>残数</td><td class="rem_num">` + old_item.hgpd_remaining + `</td></tr>
																	<tr class="chil-tr"><td>担当者</td><td>` + old_item.hgpd_name + `</td></tr>
																	` + (old_item.wic_process.includes("完成") ? '' : `
																	<tr class="chil-tr"><td>開始日時</td><td>` + old_item.hgpd_start_at + `</td></tr>
																	<tr class="chil-tr"><td>終了日時</td><td>` + old_item.hgpd_stop_at + `</td></tr>
																	<tr class="chil-tr"><td>生産時間</td><td>` + fb_diff_dhm(old_item.hgpd_start_at, old_item.hgpd_stop_at) + `</td></tr>`) + `
																</tbody>
														</table>`);
			if (processing_position == 1) {
				if ('old_hgpd_id_data' in old_item) {
					var html_table = `<table style='float:left; margin-top:8px;'>`;
					old_item.old_hgpd_id_data.forEach(function(value, index) {
					html_table += `
					<thead>
						<tr><th class="noborder notright"><span class="ui-icon ui-icon-arrowthick-1-w"></span></th><th  colspan="2" class="` + fb_get_class_process(value) + `">` + value.hgpd_process + (array_molding_id.includes(value.hgpd_id) ? '' : '<span style="color: red;">　(対象以外)</span>') + `</th></tr>
					</thead>
					<tbody>
						<tr><td class="noborder notright"></td><td style="min-width: 60px;">管理ID</td><td>` + value.hgpd_id + `</td></tr>
						<tr><td class="noborder notright"></td><td>RFID</td><td title="` + value.hgpd_rfid + `">` + fb_string_hide(value.hgpd_rfid) + `</td></tr>
						<tr><td class="noborder notright"></td><td>成形日</td><td>` + value.hgpd_moldday + `</td></tr>
						<tr><td class="noborder notright"></td><td>担当者</td><td>` + value.hgpd_name + `</td></tr>
						<tr><td class="noborder notright"></td><td>開始日時</td><td>` + value.hgpd_start_at + `</td></tr>
						<tr><td class="noborder notright"></td><td>終了日時</td><td>` + value.hgpd_stop_at + `</td></tr>
						<tr><td class="noborder notright"></td><td>生産時間</td><td>` + fb_diff_dhm(value.hgpd_start_at, value.hgpd_stop_at) + `</td></tr>
					</tbody>`;
					});
					html_table += `</table>`;
					$('.RFID_view_data_2').append(html_table);
				}
			} else {
				$('.RFID_view_data_2').html(`
				<table style='float:left; margin-top:8px;'>
					<thead>
							<tr><th class="noborder notright"><span class="ui-icon ui-icon-arrowthick-1-e"></span></th><th  colspan="2" class="` + fb_get_class_process(new_item) + `">` + new_item.hgpd_process + `</th></tr>
						</thead>
						<tbody>
							<tr><td class="noborder notright"></td><td style="min-width: 60px;">在庫ID</td><td>` + new_item.wic_id + `</td></tr>
							<tr><td class="noborder notright"></td><td style="min-width: 60px;">管理ID</td><td>` + new_item.hgpd_id + `</td></tr>
							<tr><td class="noborder notright"></td><td>RFID</td><td  title="` + new_item.hgpd_rfid + `">` + fb_string_hide(new_item.hgpd_rfid)+ `</td></tr>
							<tr><td class="noborder notright"></td><td>担当者</td><td>` + new_item.hgpd_name + `</td></tr>
							<tr><td class="noborder notright"></td><td>生産数</td><td class="used_num">` + new_item.hgpd_quantity + `</td></tr>
							<tr><td class="noborder notright"></td><td>良品数</td><td class="good_num">` + new_item.hgpd_qtycomplete + `</td></tr>
							<tr><td class="noborder notright"></td><td>不良数</td><td class="bad_num">` + new_item.hgpd_difactive + `</td></tr>
							<tr><td class="noborder notright"></td><td>保留日時</td><td>` + new_item.hgpd_start_at + `</td></tr>
						</tbody>
				</table>`);
			}
			if (processing_position >= 5) {
				var result_qty = old_item.hgpd_qtycomplete;
				var html_table = `<table style='float:left; margin-top:8px;'>`;
				if ('output' in input_item) {
					input_item.output.forEach(function(value, index) {
						if (parseInt(result_qty) > parseInt(value.hgpd_remaining)){
							result_qty = parseInt(value.hgpd_remaining);
						}
						html_table += `
						<thead style='margin-top:8px;'>
							<tr><th class="noborder notright"><span class="ui-icon ui-icon-arrowthick-1-e"></span></th><th  colspan="4" class="` + fb_get_class_process(value) + `">` + ((value.hgpd_qtycomplete == 0) ? "廃棄": value.wic_process) + `</th></tr>
						</thead>
						<tbody>
						<tr><td class="noborder notright"></td><td>在庫ID</td><td>` + value.wic_id + `</td><td>管理ID</td><td>` + value.hgpd_id + `</td></tr>
						<tr><td class="noborder notright"></td><td>RIFD</td><td colspan="3" title="` + value.hgpd_rfid + `">` + fb_string_hide(value.hgpd_rfid) + `</td></tr>
						<tr><td class="noborder notright"></td><td>生産数</td><td class="used_num">` + value.hgpd_quantity + `</td><td>キャビ</td><td class="re_num">` + value.hgpd_cav + `</td></tr>
						<tr><td class="noborder notright"></td><td>良品数</td><td class="good_num">` + value.hgpd_qtycomplete + `</td><td>検査数</td><td>` + value.wic_inspection + `</td></tr>
						<tr><td class="noborder notright"></td><td>不良数</td><td class="bad_num">` + value.hgpd_difactive + `</td><td>残数</td><td class="re_num">` + value.hgpd_remaining + `</td></tr>
						<tr><td class="noborder notright"></td><td>開始日時</td><td colspan="3">` + value.hgpd_start_at + `</td></tr>
						<tr><td class="noborder notright"></td><td>終了日時</td><td colspan="3">` + value.hgpd_stop_at + `</td></tr>
						<tr><td class="noborder notright"></td><td>生産時間</td><td colspan="3">` + fb_diff_dhm(value.hgpd_start_at, value.hgpd_stop_at) + `</td></tr>
						<tr style="height: 1px;"><td class="noborder" colspan="5" style="height: 1px;"></td></tr>
						</tbody>`;
					});
				}
				html_table += `</table>`;
				if (result_qty > 0) {
					$('.RFID_view_data_3').html(`<br><p style="color:red;"> 未処置残数:` + result_qty + ` </p><p>　入力の場合はQRコードをスキャンして下さい</p>`);
					$('#QR_code').prop('disabled', false);
					$('#QR_code').focus();
				} else {
					$('#QR_code').prop('disabled', true);
				}
				$('.RFID_view_data_3').append(html_table);
			}
		}

		function fb_get_class_process(item) {
			if (!item.wic_process){
				return 'RFID_view_1';
			} else if (item.hgpd_qtycomplete == 0) {
				return 'RFID_view_6';
			} else if (item.wic_process.includes("成形")) {
				return 'RFID_view_1';
			} else if (item.wic_process.includes("最終")) {
				return 'RFID_view_2';
			} else if (item.wic_process.includes("完成")) {
				return 'RFID_view_3';
			} else if (item.wic_process.includes("保留")) {
				return 'RFID_view_4';
			} else {
				return 'RFID_view_5';
			}
		}

		function fb_get_string(item, item2) {
			if(item  == null) return "無し";
			if(item2 == null) return item;
			if(item == item2) return item;
			return item + "→" + item2;
		}

		function fb_string_hide(item) {
			//if (processing_position != 1 && processing_position != 5) return item;
			var return_string = 'NALUX';
			return_string += item.substring(item.length - 8, item.length);
			return return_string;
		}

		function fb_set_stock_confirmation() {
			$('.btn_next_OK').show();
			$('.stock_confirmation').html('<div class="section_confirmation wd-100" style="text-align: left;float: left;"><label>キャビタブ：</label></div>');
			obj_data['data'].forEach(function(value, index) {
				fb_add_new_section(value["wbn_cavino"]);
			});
			$('#tab0').attr('checked', true);
			if (processing_position > 1) {
				$('.das_tanto').mouseover(function() {
					$('.das_tanto_view').html(fb_setget_hakko("wbn_isolation_decision", "範囲決定", "position:absolute; margin-top: 3px; margin-left:8px;"));
				}).mouseout(function() {
					$('.das_tanto_view').html("");
				}).click(function() {
				});
			}
			if (Object.keys(obj_RFID_data).length > 0) {
				// ここまで修正
				var out_total_time = 0;
				var out_remaining_number = 0;
				Object.keys(obj_RFID_data).forEach(function(obj_keys) {
					let obj_input = obj_RFID_data[obj_keys].input;
					out_remaining_number += parseInt(obj_input.old.hgpd_qtycomplete)
					let is_cav_in_bug = false;
					cav_no = obj_input.old.hgpd_cav;
					obj_data['data'].forEach(function(value, index) {
						if(fb_is_cav_rfid_in_bug(value.wbn_cavino, obj_input.old.hgpd_cav)){
							cav_no = value.wbn_cavino;
						}
					});
					$(".section_confirmation .tab_lb").each(function(){
						if($(this).text() == "#" + cav_no){
							is_cav_in_bug = true;
						}
					});
					if(!is_cav_in_bug){
						fb_add_new_section(cav_no);
					}
					cav_no = cav_no.replace(/,|-|~/gi, function (x) {return "";});
					switch (obj_input.old.wic_process_key) {
						case '0':
						case 'M':
						case 'J':
							break;
						default:
							if (!$('.RFID_table' + cav_no + " tr").hasClass('RFID_input_' + obj_input.old.wic_process_key)) {
								$('.RFID_table' + cav_no + " tbody").append(`
								<tr class="RFID_input_` + obj_input.old.wic_process_key + `">
									<td class="RFID_input_` + obj_input.old.wic_process_key + '0' + cav_no + ` wd-16 hc_left">` + obj_input.old.wic_process_key + `</td>
									<td class="hc_center RFID_input_` + obj_input.old.wic_process_key + '1' + cav_no + ` das_tanto"></td>
									<td class="hc_center RFID_input_` + obj_input.old.wic_process_key + '2' + cav_no + `"></td>
									<td class="hc_center RFID_input_` + obj_input.old.wic_process_key + '3' + cav_no + `"></td>
									<td colspan="2"></td>
								</tr>`);
							}
							break;
					}
					const key_rfid_input = '.RFID_input_' + obj_input.old.wic_process_key;
					$(key_rfid_input + '1' + cav_no).html(fb_parseInt($(key_rfid_input + '1' + cav_no).html()) + fb_parseInt(obj_input.old.hgpd_qtycomplete));
					if ((!obj_input.old.wic_id)|| !obj_input.old.hgpd_id) {
						$('.btn_next_OK').hide();
						$(key_rfid_input + '1' + cav_no).addClass('input_error');
						$('.label_tab' + cav_no).addClass('input_error');
						$('.tab' + cav_no).attr('checked', true);
					}
					if (obj_RFID_data[obj_keys].hasOwnProperty('output')) {
						obj_RFID_data[obj_keys].output.forEach(function(obj_output, index) {
							out_remaining_number -= parseInt(obj_output.hgpd_qtycomplete) + parseInt(obj_output.hgpd_difactive) ;
							out_total_time += fb_diff_min(obj_output.hgpd_start_at, obj_output.hgpd_stop_at);
							$(key_rfid_input + '3' + cav_no).html(fb_parseInt($(key_rfid_input + '3' + cav_no).html()) + fb_parseInt(obj_output.hgpd_difactive));
							$(key_rfid_input + '2' + cav_no).html(fb_parseInt($(key_rfid_input + '2' + cav_no).html()) + fb_parseInt(obj_output.wic_inspection));
							if (!obj_output.wic_id || !obj_output.hgpd_id) {
								$('.btn_next_OK').hide();
								$(key_rfid_input + '3' + cav_no).addClass('input_error');
								$('.label_tab' + cav_no).addClass('input_error');
								$('.tab' + cav_no).attr('checked', true);
							}
						});
					};
					if (!$('.tab_lb').hasClass('input_error')) $('.tab' + cav_no).attr('checked', true);
				});
				if(out_remaining_number != 0 && processing_position == 5) $('.btn_next_OK').hide();
				$('#testtime').val("検査時間:" + out_total_time + "分");
				$(".section_confirmation .tab_lb").each(function(){
						var this_cav = $(this).text().replace("#", "");
						if (fb_parseInt($('.RFID_input_P1' + this_cav).text()) < 1) $('.RFID_input_P' + this_cav).hide();
					});
			}
			$('.div_quantity').html(fb_set_hakko("wbn_quantity", "position:absolute; margin-top: -53px; margin-left:12px;"));
			$('.div_licensor').html(fb_set_hakko("wbn_licensor"));
			$('.RFID_connect_view input[readonly], .RFID_connect_view td:not(:has(input))').click(function() {
				fb_view_RFID_input();
			});
			if (Object.keys(obj_RFID_data).length > 0) {
				$('.class_RFID_input').prop('readonly', true);
			} else {
				$('.class_RFID_input').prop('readonly', false);
			}
			if (RFID_input_check && Object.keys(obj_find_list_RFID_data).length == 0) {
				fb_alert_msg("リストアップを検索してください。");
				$('.find_list_RFID input').addClass('error_data');
			}
			if (JSON.stringify(Object.keys(obj_find_list_RFID_data).sort()) == JSON.stringify(Object.keys(obj_RFID_data).sort())) {
				RFID_input_check = false;
			}
			fb_set_input();
			fb_loading(false);
		}

		function fb_add_new_section(in_cav){
			if(!in_cav) return false;
			const changed_cav = in_cav.replace(/,|-|~/gi, function (x) {return "";});
			let index = $('.section_confirmation section').length;
			$('.section_confirmation label:last').after('<input style="display: none;" class="tab' + changed_cav + '" id="tab' + index + '" type="radio" name="tabs"/> <label class="tab_lb label_tab' + changed_cav + '" for="tab' + index + '">#' + in_cav + '</label>');
			$('.section_confirmation').append(`
					<section id="content` + index + `" class="wd-100">
						<table class="RFID_table` + changed_cav + ` wd-100 RFID_connect_view">
							<tr>
								<td class="wd-16 hc_left">在庫確認</td>
								<td class="wd-16 hc_center das_tanto">対象数<div class="das_tanto_view"></div></td>
								<td class="wd-16 hc_center">検査数</td>
								<td class="wd-16 hc_center">不良数</td>
								<td class="wd-16 hc_center" rowspan="2">
									数量管理部 <br>
									門への連絡
								</td>
								<td class="wd-16 hc_center">発生部門係長</td>
							</tr>
							<tr>
								<td class="RFID_input_00` + changed_cav + ` wd-16 hc_left">完成品</td>
								<td class="hc_center RFID_input_01` + changed_cav + ` das_tanto"></td>
								<td class="hc_center RFID_input_02` + changed_cav + `"></td>
								<td class="hc_center RFID_input_03` + changed_cav + `"></td>
								<td rowspan="3">
									<div class="div_licensor hc_center"></div>
								</td>
							</tr>
							<tr>
								<td class="RFID_input_M0` + changed_cav + ` wd-16 hc_left">仕掛品</td>
								<td class="hc_center RFID_input_M1` + changed_cav + ` das_tanto"></td>
								<td class="hc_center RFID_input_M2` + changed_cav + `"></td>
								<td class="hc_center RFID_input_M3` + changed_cav + `"></td>
								<td rowspan="2">
									<div class="div_quantity hc_center"></div>
								</td>
							</tr>
							<tr>
								<td class="RFID_input_J0` + changed_cav + ` wd-16 hc_left">蒸着品</td>
								<td class="hc_center RFID_input_J1` + changed_cav + ` das_tanto"></td>
								<td class="hc_center RFID_input_J2` + changed_cav + `"></td>
								<td class="hc_center RFID_input_J3` + changed_cav + `"></td>
							</tr>
						</table>
					</section>`);
		}

		function fb_del_RFID(item) {
			let del_cav_no = Object.keys(obj_data['data']).length == 1 ? '' : obj_RFID_data[item].input.hgpd_cav;
			delete obj_RFID_data[item];
			$('#QR_code').attr('placeholder', '');
			$('.RFID_view_data input').val("");
			fb_set_RFID_btn();
			if (Object.keys(obj_data['data']).length > 1) $('.tab' + del_cav_no).attr('checked', true);
			fb_alert_msg('');
		}

		function toHalfWidth(str) {
			// 全角英数字を半角に変換
			str = str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
				return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
			});
			return str;
		}

		function fb_alert_msg(item_msg) {
			is_scan = true;
			$('.alert_msg').html(item_msg);
			if (item_msg != '') {
				$('.alert_msg').css('display', 'block');
			} else {
				$('.alert_msg').css('display', 'none');
			}
			return (async () => {
				await delay(0.01);
				$('#QR_code').val("");
				$('#QR_code').focus();
			})();
		}

		function delay(seconds) {
			return new Promise(function(resolve) {
				setTimeout(resolve, seconds * 1000);
			});
		}

		function fb_set_autocomplete(item, tagState) {
			function split(val) {
				return val.split(/,\s*/);
			}

			function extractLast(term) {
				return split(term).pop();
			}
			$("#" + item).autocomplete({
				minLength: 0,
				autoFocus: true,
				source: function(request, response) {
					lastEntry = extractLast(request.term);
					var filteredArray = $.map(tagState, function(item) {
						if (item.indexOf(lastEntry) === 0) {
							return item;
						} else {
							return null;
						}
					});
					response($.ui.autocomplete.filter(filteredArray, lastEntry));
				},
				focus: function() {
					return false;
				},
				select: function(event, ui) {
					var terms = split(this.value);
					terms.pop();
					terms.push(ui.item.value);
					terms.push("");
					this.value = terms.join("").replace("@", "");
					return false;
				}
			}).on("keydown", function(event) {
				if (event.keyCode === $.ui.keyCode.TAB) {
					event.preventDefault();
					return;
				}
			});
		}

		function fb_auto_rate() {
			if ($("#inspection_bad").val() > 0 && $("#inspection_total").val() > 0) {
				$("#inspection_rate").val(Math.round($("#inspection_bad").val() / $("#inspection_total").val() * 10000) / 100 + '%')
			} else {
				$("#inspection_rate").val('');
			}
		}

		function fb_get_dept() {
			if ($("#dept").prop("readonly")) {
				return;
			}
			var width = 980;
			$("#alert_dept").dialog({
				autoOpen: false,
				width: width,
				modal: true,
				title: '宛先部署を選択して下さい。',
				position: [((ww - width) / 2), 40],
				buttons: []
			});
			$.getJSON("/common/AjaxUserSelect?action=user&target=department&gp1=" + decodeURIComponent(gp1) /*+"&callback=?"*/ , function(data) {
				$("#alert_dept").html(data);
			});
			$("#alert_dept").dialog("open");
		}

		function fb_set_dept(place, department) {
			$("#dept").val(department);
			$("#alert_dept").html('');
			$("#alert_dept").dialog("close");
		}

		function fb_add_attached(itemid) {
			$("div").remove(".bugcontentimage");
			let margin_left = $('#' + itemid).width() - 35;
			$('#msgbox').append('<div class="form-validation-field-1formError parentFormformCheck formError bugcontentimage" style="opacity: 0.87; position: absolute; top: ' + $('#' + itemid).position()['top'] + 'px; left: ' + $('#' + itemid).position()['left'] + 'px; right: initial; width:70px; margin-top: -2px; margin-left: ' + margin_left + 'px; display: block;"><input type="button" onclick="fb_get_image();" style="border-radius: 50%;box-shadow: 0.375 em 0.375 em 0 0 rgba(15, 28, 63, 0.125);text-align: center; width: 50px; height: 50px;font-size: 12px;" class="form-control ccenter btn_attached" value="添付" /></div>');
		}

		function fb_add_msg(itemid) {
			$("div").remove(".processing_state");
			let margin_left = $('#' + itemid).width() - 7;
			$('#msgbox').append('<div class="form-validation-field-1formError parentFormformCheck formError processing_state" style="opacity: 0.87; z-index: 1; position: absolute; top: ' + $('#' + itemid).position()['top'] + 'px; left: ' + $('#' + itemid).position()['left'] + 'px; right: initial; width:70px; margin-top: -15px; margin-left: ' + margin_left + 'px; display: block; color:red;"></div>');
		}

		function fb_show_rejection_reason() {
			alert("差戻理由：" + $("#wbn_rejection_reason" + judgement_id).val());
		}

		function fb_get_image() {

			var flg_img_zoom, width = 750;
			$("#alert_dept").dialog({
				autoOpen: false,
				width: width,
				// height: 900,
				modal: true,
				title: '不具合添付',
				position: ["center", 40],
				buttons: [{
					text: "閉じる",
					click: function() {
						$(this).dialog("close");
					}
				}]
			});
			$("#alert_dept").html('<div  class="wd-100" id="bug_temp" style="max-height:' + wh + 'px; overflow-y:scroll;"></div>');
			$("#alert_dept").dialog("open");
			if ('file' in obj_data) {
				
				obj_data['file'].forEach((value, index) => {
					if (index > 0) {
						$("#bug_temp").append('<div class="wd-100" style="float:left;"><hr noshade></div>');
					}
					if (value["mfc_type"].indexOf('image') > -1) {
						if (!flg_img_zoom){
							flg_img_zoom = true;
							$("#alert_dept").prepend('<div class="btn_zoom_img" ><button zoom_in=0.5 class="btn_not_check">x5</button><button zoom_in=1 class="btn_not_check btn_checked">x10</button><button zoom_in=2 class="btn_not_check">x20</button><button zoom_in=5 class="btn_not_check">x50</button></div>');
						}
						$("#bug_temp").append(`<div class="img-zoom-container"><div><img id="myimage` + value["mfc_id"] + `" src="MissingDefect/GetFile?menu=img&mfc_id=` + value["mfc_id"] + `"></div> <div id="myresult` + value["mfc_id"] + `" class="img-zoom-result" onclick="window.open('/MissingDefect/GetFile?menu=img&mfc_id=` + value["mfc_id"] + `', '_blank');"></div></div>`);
						fb_imageZoom("myimage"+ value["mfc_id"], "myresult"+ value["mfc_id"]);
					} else {
						$("#bug_temp").append(` <div class="wd-100 file_img_` + value["mfc_id"] + `" style="float:left; margin-bottom: 5px;">
														<div class="wd-20" style="float:left;">　</div>
														<div class="wd-70" style="float:left;"> <a style="float:left; background-color: transparent;" href="MissingDefect/GetFile?menu=file&mfc_id=` + value["mfc_id"] + `">添付 ` + parseInt(index + 1) + `: ` + value['mfc_name'] + `</a></div>
													</div>`);
					}
				});
			}
			$('.btn_zoom_img button').click(function() {
				$('.btn_zoom_img button').removeClass('btn_checked');
				$(this).addClass('btn_checked');
				const x = $(this).attr('zoom_in');
				$('.img-zoom-lens').width(40/x + "px");
				$('.img-zoom-lens').height(40/x + "px");
			});
			$('.img-zoom-result').mouseover(function() {
				this.style.cursor = 'zoom-in';
			});
			$('.img-zoom-result').mouseout(function() {
				this.style.cursor = 'default';
			});
			
		}
		
		function fb_imageZoom(imgID, resultID) {
			var img, lens, result, cx, cy;
			img = document.getElementById(imgID);
			result = document.getElementById(resultID);
			/*create lens:*/
			lens = document.createElement("DIV");
			lens.setAttribute("class", "img-zoom-lens");
			img.parentElement.insertBefore(lens, img);
			result.style.backgroundImage = "url('" + img.src + "')";
			result.style.backgroundSize = (300 * 10) + "px " + (240 * 10) + "px";
			/*execute a function when someone moves the cursor over the image, or the lens:*/
			lens.addEventListener("mousemove", moveLens);
			img.addEventListener("mousemove", moveLens);
			/*and also for touch screens:*/
			lens.addEventListener("touchmove", moveLens);
			img.addEventListener("touchmove", moveLens);
			function moveLens(e) {
				const img_x = img.width;
				const img_y = img.height;
				/*calculate the ratio between result DIV and lens:*/
				cx = result.offsetWidth / lens.offsetWidth;
				cy = result.offsetHeight / lens.offsetHeight;
				/*set background properties for the result DIV:*/
				
				result.style.backgroundSize = (img_x * cx) + "px " + (img_y * cy) + "px";
				var pos, x, y;
				/*prevent any other actions that may occur when moving over the image:*/
				e.preventDefault();
				/*get the cursor's x and y positions:*/
				pos = getCursorPos(e);
				/*calculate the position of the lens:*/
				x = pos.x - (lens.offsetWidth / 2);
				y = pos.y - (lens.offsetHeight / 2);
				/*prevent the lens from being positioned outside the image:*/
				if (x > img_x - lens.offsetWidth) {x = img_x - lens.offsetWidth;}
				if (x < 0) {x = 0;}
				if (y > img_y - lens.offsetHeight) {y = img_y - lens.offsetHeight;}
				if (y < 0) {y = 0;}
				/*set the position of the lens:*/
				lens.style.left = img.offsetLeft + x + "px";
				lens.style.top = img.offsetTop + y + "px";
				/*display what the lens "sees":*/
				result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
			}
			function getCursorPos(e) {
				var a, x = 0, y = 0;
				e = e || window.event;
				/*get the x and y positions of the image:*/
				a = img.getBoundingClientRect();
				/*calculate the cursor's x and y coordinates, relative to the image:*/
				x = e.pageX - a.left;
				y = e.pageY - a.top;
				/*consider any page scrolling:*/
				x = x - window.pageXOffset;
				y = y - window.pageYOffset;
				return {x : x, y : y};
			}
		}
				
		function fb_input_check() {
			var b_input_check = true;
			const btn_defect = document.getElementsByClassName("form-control");
			for (let i = 0; i < btn_defect.length; i++) {
				$(btn_defect[i]).removeClass('input_error');
				if (!$(btn_defect[i]).val()) {
					if ((btn_defect[i].required) && ($(btn_defect[i]).attr('readonly') != 'readonly')) {
						$(btn_defect[i]).addClass('input_error');
						fb_set_parent_id(btn_defect[i]);
						b_input_check = false;
					}
				} else if (isNaN(btn_defect[i].value) && (btn_defect[i].title == 'number')) {
					$(btn_defect[i]).addClass('input_error');
					fb_set_parent_id(btn_defect[i]);
					b_input_check = false;
				} else if (btn_defect[i].max || btn_defect[i].min) {
					if ((parseInt(btn_defect[i].max) < parseInt(btn_defect[i].value)) || (parseInt(btn_defect[i].min) > parseInt(btn_defect[i].value))) {
						$(btn_defect[i]).addClass('input_error');
						fb_set_parent_id(btn_defect[i]);
						b_input_check = false;
					}
				}
			}
			if ($('#wbn_processing_position' + judgement_id).val() == 1) {
				obj_data['data'].forEach((value, index) => {
					cav_no = Object.keys(obj_data['data']).length == 1 ? '' : value["wbn_cavino"];
					if ($('#wip_numberoftargets_P' + cav_no).val() > 0 && $('#storage_source' + cav_no).val() == "") {
						$('#storage_source' + cav_no).addClass('input_error');
						$('label[for="tab' + index + '"]').addClass('input_error');
						b_input_check = false;
					}
				});
			}
			return b_input_check;
		}

		function fb_set_parent_id(item, words = "Add") {
			if (item.parentNode.parentNode.parentNode.parentNode.parentNode.id) {
				if (words == "Add") {
					$('label[for="tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "") + '"]').addClass('input_error');
				} else {
					$('label[for="tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "") + '"]').removeClass('input_error');
				}
			}
		}

		function fb_set_error_data(item, words = "Add") {
			if (item.parentNode.parentNode.parentNode.parentNode.parentNode.id) {
				if (words == "Add") {
					$('label[for="tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "") + '"]').addClass('error_data');
				} else {
					$('label[for="tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "") + '"]').removeClass('error_data');
				}
			}
		}

		function fb_set_parent_focus(item) {
			if ($('#' + item).parent().parent().parent().parent().parent().attr('id')) {
				$('#tab' + $('#' + item).parent().parent().parent().parent().parent().attr('id').replace("content", "")).attr('checked', true);
			}
		}

		function fb_get_tabindex_no() {
			tabindex_no = tabindex_no + 1;
			return tabindex_no;
		}

		function fb_detele() {
			var rejection_reason = "";
			while (rejection_reason == "") {
				rejection_reason = prompt("１・ 削除の場合必ず理由を入力し,「OK」ボタンを押してください。\n２・ キャンセルの場合「キャンセル」ボタンを押してください。");
				if (rejection_reason != "") break;
			}
			if (!rejection_reason) {
				return;
			}
			var hgpd_old_return = [], hgpd_new_delete = [], xls_new_delete = [];
			Object.keys(obj_RFID_data).forEach(function(obj_keys) {
				hgpd_old_return.push(obj_RFID_data[obj_keys].input.old.hgpd_id);
				hgpd_new_delete.push(obj_RFID_data[obj_keys].input.new.hgpd_id);
				xls_new_delete.push(obj_RFID_data[obj_keys].input.new.xwr_id);
				if ("output" in obj_RFID_data[obj_keys]){
					obj_RFID_data[obj_keys].output.forEach(function(out_value) {
						hgpd_new_delete.push(out_value.hgpd_id);
						xls_new_delete.push(out_value.xwr_id);
					});
				}
			});
			var ajax_data = {};
				ajax_data['judgement_id'] = judgement_id;
				ajax_data['RFID_count'] = Object.keys(obj_RFID_data).length;
				ajax_data['hgpd_old_return'] = hgpd_old_return;
				ajax_data['hgpd_new_delete'] = hgpd_new_delete;
				ajax_data['xls_new_delete'] = xls_new_delete;
				ajax_data['login_person'] = $('#login_person').val();
				ajax_data['rejection_reason'] = rejection_reason;
			var datas = {
				ac: "削除",
				ajax_delete_data: ajax_data,
			}
			fb_loading(true);
			$.ajax({
				type: 'POST',
				url: "",
				dataType: 'html',
				data: datas,
				success: function(d) {
					if (d == 'OK') {
						$("#alert").dialog("close");
						get_data();
					} else {
						fb_loading(false);
						alert("差戻失敗 (; ω ;)ｳｯ…");
					}
					return;
				},
				error: function() {
					fb_loading(false);
					alert("エラー");
				}
			});
		}

		function btn_judgement(item) {
			if (!$('#input_das_person').val() && !bl_change && $('#processing_state').val() != "発生・流出書き直し" && (processing_position == 1 || (processing_position == 5 && item.value != '保存'))) {
				fb_get_user();
				return;
			}
			var sum_input = 0;
			// $('.class_RFID_input:not([readonly])').each(function() {
			// 	if (parseInt($(this).val()) > 0) {
			// 		sum_input += parseInt($(this).val()); // Or this.innerHTML, this.innerText
			// 	}
			// });
			if (item.value == '次へ' && Object.keys(obj_RFID_data).length > 0) {
				if( processing_position == 1){
					if (JSON.stringify(Object.keys(obj_find_list_RFID_data).sort()) != JSON.stringify(Object.keys(obj_RFID_data).sort())) {

					let instructions_check = "全部のまるめを選択・登録しなくても次へ進みませんか？";
					if (confirm(instructions_check) == false) return;
				}
				}else if(processing_position == 5){
					Object.keys(obj_RFID_data).forEach(function(obj_keys) {
						var check_number = fb_parseInt(obj_RFID_data[obj_keys].input.old.hgpd_qtycomplete);
						if('output' in obj_RFID_data[obj_keys]){
							obj_RFID_data[obj_keys].output.forEach(function(value, key){
								check_number -= (fb_parseInt(value.hgpd_difactive) + fb_parseInt(value.hgpd_qtycomplete));
							});
						}
						if(check_number != 0){
							RFID_input_check = true;
							fb_view_RFID_input();
						}
					});
				}
			}
			var rejection_reason = "";
			if (item.value == '差戻') {
				if (!$('#input_das_person').val() && processing_position == 3) {
					fb_get_user();
					return;
				}
				while (rejection_reason == "") {
					rejection_reason = prompt("１・ 差戻の場合必ず理由を入力し,「OK」ボタンを押してください。\n２・ キャンセルの場合「キャンセル」ボタンを押してください。");
					if (rejection_reason != "") break;
				}
				if (!rejection_reason) {
					return;
				}
			}
			if (item.value != '保存' && item.value != '差戻') {
				if (!fb_input_check()) {
					return;
				}
			}
			let inspection_rate;
			if ($('#inspection_rate').val() && $('#inspection_rate').val() != '') {
				inspection_rate = $('#inspection_rate').val().replace("%", "");
			}
			var treatment_only = $('input:checkbox[name="処置のみ"]:checked').val();
			var restoration = $('input:checkbox[name="復旧確認で可"]:checked').val();
			var confirmation = $('input:checkbox[name="長期確認が必要"]:checked').val();
			if (processing_position == 3) {
				outbreak_outflow = "必要";
				$('input:checkbox[name="発生流出"]:checked').each(function() {
					outbreak_outflow += $(this).val();
				});
			}
			var corrective_input_person;
			if ($("#wbn_cause" + judgement_id).val().replace("入力必要", "") != $('#発生・原因').val() ||
				$("#wbn_countermeasures" + judgement_id).val().replace("入力必要", "") != $('#発生・対策').val() ||
				$("#wbn_outflow_cause" + judgement_id).val().replace("入力必要", "") != $('#流出・原因').val() ||
				$("#wbn_outflow_countermeasures" + judgement_id).val().replace("入力必要", "") != $('#流出・対策').val()) {
				corrective_input_person = $('#login_person').val();
			}

			var info = {};
			info['moldplaceid'] = placeid;
			info['moldplace'] = placename;
			info['xlsnum'] = xlsnum;
			info['workkind'] = workitem_class;
			info['date'] = today;
			info['username'] = $('#input_das_person').val();
			info['usercord'] = $('#usercord').val();
			info['usergp1'] = $('#gp1').val();
			info['usergp2'] = $('#gp2').val();
			info['workitem'] = workitem_name;
			info['itemname'] = $('#wbn_product_name' + judgement_id).val();
			info['itemcord'] = $('#wbn_item_code' + judgement_id).val();
			info['itemform'] = $('#wbn_form_no' + judgement_id).val();
			info['pdate'] = $('#wbn_mold_dt' + judgement_id).val();
			info['moldlot'] = $('#wbn_lot_no' + judgement_id).val();

			var data_input = {};
			obj_data['data'].forEach((value, index) => {
				cav_no = Object.keys(obj_data['data']).length == 1 ? '' : value["wbn_cavino"];
				data_input['fh_numberoftargets' + cav_no] = $('#fh_numberoftargets' + cav_no).val();
				data_input['fh_testnum' + cav_no] = $('#fh_testnum' + cav_no).val();
				data_input['fh_adversenumber' + cav_no] = $('#fh_adversenumber' + cav_no).val();

				data_input['wip_numberoftargets' + cav_no] = $('#wip_numberoftargets' + cav_no).val();
				data_input['wip_testnum' + cav_no] = $('#wip_testnum' + cav_no).val();
				data_input['wip_adversenumber' + cav_no] = $('#wip_adversenumber' + cav_no).val();

				data_input['vd_numberoftargets' + cav_no] = $('#vd_numberoftargets' + cav_no).val();
				data_input['vd_testnum' + cav_no] = $('#vd_testnum' + cav_no).val();
				data_input['vd_adversenumber' + cav_no] = $('#vd_adversenumber' + cav_no).val();

				data_input['wip_numberoftargets_P' + cav_no] = $('#wip_numberoftargets_P' + cav_no).val();
				data_input['wip_testnum_P' + cav_no] = $('#wip_testnum_P' + cav_no).val();
				data_input['wip_adversenumber_P' + cav_no] = $('#wip_adversenumber_P' + cav_no).val();
				data_input['wip_storage_source' + cav_no] = $('#storage_source' + cav_no).val();
			});
			var datas = {
				ac: "判定",
				wbn_id: judgement_id,
				info: info,
				obj_RFID_data: obj_RFID_data,
				processing_position: processing_position,
				cavno: $('#CAVno' + judgement_id).val(),
				value: item.value,
				placeid: placeid,
				processing_state: $('#processing_state').val(),
				defect_item: $('#wbn_defect_item' + judgement_id).val(),
				input_das_person: $('#input_das_person').val(),
				login_person: $('#login_person').val(),
				rejection_reason: rejection_reason,
				// 置き場
				wip_palce: wip_palce,
				fh_palce: fh_palce,
				vd_palce: vd_palce,
				oh_palce: oh_palce,
				// データ
				data_input: data_input,
				// 未判定
				rank: $('#wbn_rank' + judgement_id).val(),
				evidence: $('#evidence').val(),
				demand: $('#demand').val(),
				// 発見者上長
				defect_details: $('#defect_details').val(),
				work_in_process: $('#work_in_process').val(),
				completed: $('#completed').val(),
				evaporated: $('#evaporated').val(),
				repair_sheet: $('input[name="修理依頼書"]:checked').val(),
				defect_type: $('input[name="欠点分類"]:checked').val(),
				reason: $('input[name="発行理由"]:checked').val(),
				dept: $('#dept').val(),
				deadline: $('#deadline').val(),
				receipt_dt: $('#receipt_dt').val(),
				due_dt: $('#due_dt').val(),
				// BU担当
				due_details: $('#due_details').val(),
				outbreak_outflow: outbreak_outflow,
				// 発生部門認可
				content_confirmation: $('#content_confirmation').val(),
				treatment_only: treatment_only,
				cause: $('#発生・原因').val(),
				countermeasures: $('#発生・対策').val(),
				outflow_cause: $('#流出・原因').val(),
				outflow_countermeasures: $('#流出・対策').val(),
				corrective_input_person: corrective_input_person,
				restoration: restoration,
				confirmation: confirmation,
				inspection_total: $('#inspection_total').val(),
				inspection_bad: $('#inspection_bad').val(),
				inspection_rate: inspection_rate,
				effect: $('input[name=効果]:checked').val(),
				effect_NG_msg: $('#effect_NG_msg').val(),
				reissue_id: $('#reissue_id').val(),
				testtime: $('#testtime').val(),
				// 発生部門処置担当
				treatment: $('input[name=処置内容]:checked').val(),
				rework_instructions: $('#rework_instructions').val(),
				inventory_processing: $('#inventory_processing').val(),
				quality_control_comment: $('#quality_control_comment').val(),
				// RFIDバージョン追加
				wbn_count: Object.keys(obj_data['data']).length,
				
			}
			// console.log(datas);
			// return;
			fb_loading(true);
			$.ajax({
				type: 'POST',
				url: "",
				dataType: 'html',
				data: datas,
				success: function(d) {
					if (d == 'OK') {
						$("#alert").dialog("close");
						get_data();
					} else if (d.indexOf('RFID_ERROR_INPUT') > -1) {
						fb_loading(false);
						RFID_input_error_list = d;
						fb_view_RFID_input();
					} else {
						fb_loading(false);
						alert("処理失敗 (; ω ;)ｳｯ…");
					}
					return;
				},
				error: function() {
					fb_loading(false);
					alert("エラー");
				}
			});
		}
		
		function fb_back(id) {
			$('#judgement_view').html('');
			bl_change = true;
			view_judgeent();
		}
		
		function fb_BU_return (){
			var rejection_reason = "";
			while (rejection_reason == "") {
				rejection_reason = prompt("１・ 差戻の場合必ず理由を入力し,「OK」ボタンを押してください。\n２・ キャンセルの場合「キャンセル」ボタンを押してください。");
				if (rejection_reason != "") break;
			}
			if (!rejection_reason) {
				return;
			}
			var corrective_input_person;
			if ($("#wbn_cause" + judgement_id).val().replace("入力必要", "") != $('#発生・原因').val() ||
				$("#wbn_countermeasures" + judgement_id).val().replace("入力必要", "") != $('#発生・対策').val() ||
				$("#wbn_outflow_cause" + judgement_id).val().replace("入力必要", "") != $('#流出・原因').val() ||
				$("#wbn_outflow_countermeasures" + judgement_id).val().replace("入力必要", "") != $('#流出・対策').val()) {
				corrective_input_person = $('#login_person').val();
			}
			var hgpd_old_return = [], hgpd_new_delete = [], xls_new_delete = [];
			Object.keys(obj_RFID_data).forEach(function(obj_keys) {
				hgpd_old_return.push(obj_RFID_data[obj_keys].input.old.hgpd_id);
				hgpd_new_delete.push(obj_RFID_data[obj_keys].input.new.hgpd_id);
				xls_new_delete.push(obj_RFID_data[obj_keys].input.new.xwr_id);
			});
			var ajax_data = {};
				ajax_data['judgement_id'] = judgement_id;
				ajax_data['RFID_count'] = Object.keys(obj_RFID_data).length;
				ajax_data['hgpd_old_return'] = hgpd_old_return;
				ajax_data['hgpd_new_delete'] = hgpd_new_delete;
				ajax_data['xls_new_delete'] = xls_new_delete;
				ajax_data['login_person'] = $('#login_person').val();
				ajax_data['due_details'] = $('#due_details').val();
				ajax_data['cause'] = $('#発生・原因').val();
				ajax_data['countermeasures'] = $('#発生・対策').val();
				ajax_data['outflow_cause'] = $('#流出・原因').val();
				ajax_data['outflow_countermeasures'] = $('#流出・対策').val();
				ajax_data['outbreak_outflow'] = outbreak_outflow;
				ajax_data['corrective_input_person'] = corrective_input_person;
				ajax_data['dept'] = $('#dept').val();
				ajax_data['deadline'] = $('#deadline').val();
				ajax_data['receipt_dt'] = $('#receipt_dt').val();
				ajax_data['repair_sheet'] = $('input[name="修理依頼書"]:checked').val();
				ajax_data['defect_type'] = $('input[name="欠点分類"]:checked').val();
				ajax_data['reason'] = $('input[name="発行理由"]:checked').val();
				ajax_data['rejection_reason'] = rejection_reason;
			var datas = {
				ac: "BU_差戻",
				ajax_BU_data: ajax_data,
			}
			fb_loading(true);
			$.ajax({
				type: 'POST',
				url: "",
				dataType: 'html',
				data: datas,
				success: function(d) {
					if (d == 'OK') {
						$("#alert").dialog("close");
						get_data();
					} else if (d.indexOf('RFID_ERROR_INPUT') > -1) {
						fb_loading(false);
						RFID_input_error_list = d;
						fb_view_RFID_input();
					} else {
						fb_loading(false);
						alert("処理失敗 (; ω ;)ｳｯ…");
					}
					return;
				},
				error: function() {
					fb_loading(false);
					alert("エラー");
				}
			});
		}
	</script>
</head>

<body>
	<form autocomplete="off">
		<div id="judgement_view"></div>
	</form>
</body>

</html>
