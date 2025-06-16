<?php

if (!$placeid) {

  slot('h1', '<h1> 不具合連絡システム | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/MissingDefect/register?placeid=1000079">野洲工場</a>
      <a class="blue" href="/MissingDefect/register?placeid=1000073">山崎工場</a>
      <a class="blue" href="/MissingDefect/register?placeid=1000125">NPG</a>
    </div>
    ';
  return;
}

use_javascript("hpro/handsontable.full.min.js");
use_javascript("hpro/languages/all.js");
use_stylesheet("hpro/handsontable.full.min.css");
use_javascript("jquery/jquery.ui.datepicker-ja.js");
use_javascript("jquery/jquery.ui.ympicker.js");

use_stylesheet("MissingDefect/validationEngine.jquery.css");
use_stylesheet("MissingDefect/print.min.css");
use_stylesheet("MissingDefect/bootstrap.min.css");
use_stylesheet("MissingDefect/bootstrap.min.css.map");

use_javascript("MissingDefect/print.min.js");

$btn = '
        <div id="h_sel" style="float:left; margin-top:1px;" >';
$btn .= '
        <input class="selgroups" type="date" name="start_sel" id="start_sel" style="float:left; margin-left:5px;" placeholder="開始発行日" autocomplete="off" value="'.date("Y-m-d", strtotime("-1 months")).'">
        <label style="float:left;" for="end_sel">~</label>
        <input class="selgroups" type="date" name="end_sel" id="end_sel" style="float:left;" placeholder="終了発行日" autocomplete="off" value="'.date("Y-m-d").'">
        <input class="selgroups" type="text" name="documentid_sel" id="documentid_sel" style="float:left; margin-left:5px;" placeholder="資料番号" list="idList" title="番号のみ検査可能" autocomplete="off">
        <datalist id="idList">';
foreach ($idlist as $item) {
  $btn .= "<option value='" . $item['wbn_id'] . "'>\n";
}
$btn .= '</datalist>
        <input class="selgroups" type="text" name="name_sel" id="name_sel" style="float:left; margin-left:5px; margin-right:5px; width: 15vw" placeholder="[品目コード]品名" list="nameList" title="品名" autocomplete="off">
        <datalist id="nameList">';
foreach ($namelist as $item) {
  $btn .= "<option value='[" . $item['wbn_item_code'] . "]" . $item['wbn_product_name'] . "'>\n";
}
$btn .= '</datalist>
        <input class="selgroups" type="text" id="sel_colname" style="float:left; margin-left:5px; margin-right:5px;" placeholder="テーブル列名へ移動" list="colname_list" oninput="fb_sel_col(this.value);" autocomplete="off">
        <datalist id="colname_list"></datalist>
        <button type="button" class="btn_def" onclick="fb_viewtable();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1838"/>検索 </button>
        <button type="button" class="btn_def" onclick="fb_download();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1840"/>ロード </button>
        <button type="button" class="btn_def" onclick="fb_graphic();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1844"/>グラフ </button>
        <button type="button" class="btn_def" onclick="fb_viewdata();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1845"/> 一覧 </button>
        <button type="button" class="btn_def" onclick="fb_get_user();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1843"/> 発見者 </button>
        
         </div>
         <div style="float:left;">
         <label id="person_lable"></label>
         <label id="person"></label>
         </div>

        <button style="float:right;" type="button" class="btn_def" onclick="window.close();return false;">閉じる</button>
        <button style="float:right; display: none;" type="button" class="btn_def input_error" id= "btn_error_view" onclick="fb_error_view();"> <i aria-hidden="true"></i> 数量エラー </button>
        <label id="conf_msg"></label>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>

<html>

<head>
  <style type="text/css">
    body,
    label {font-size: 0.8vw;}
    .form-control {font-size: 0.8vw;}
    h4 {font-size: 1.3vw;}
    button.btn_def:hover {color: orange; cursor: pointer; overflow: hidden;}
    .ui-button-text {font-size: 16px; margin: 0.1px; padding: 2px 4px 2px 4px !important;}
    div.bigclass {float: left; padding: 5px; width: 100%; border-radius: 5px; margin-bottom: 5px;}
    div.smallclass {float: left; padding-right: 5px; text-align: center;}
    div.smallsmallclass {float: left; padding: 0px; text-align: center;}
    .wd-100 {width: 100%;}
    .wd-95 {width: 95%;}
    .wd-90 {width: 90%;}
    .wd-85 {width: 85%;}
    .wd-80 {width: 80%;}
    .wd-75 {width: 75%;}
    .wd-70 {width: 70%;}
    .wd-65 {width: 65%;}
    .wd-60 {width: 60%;}
    .wd-55 {width: 55%;}
    .wd-50 {width: 50%;}
    .wd-45 {width: 45%;}
    .wd-40 {width: 40%;}
    .wd-35 {width: 35%;}
    .wd-30 {width: 30%;}
    .wd-25 {width: 25%;}
    .wd-20 {width: 20%;}
    .wd-15 {width: 15%;}
    .wd-12-5 {width: 12.5%;}
    .wd-10 {width: 10%;}
    .wd-5 {width: 5%;}
    .wd-2-5 {width: 2.5%;}
    .wd-68 {width: 68%;}
    .wd-16 {width: 16%;}
    .bd {border: 1px dotted;}
    .bgc-wh {background-color: wheat;}
    .bgc-1 {background-color: #6699CC;}
    .bgc-2 {background-color: #339933;}
    .bgc-3 {background-color: #F4A460;}
    .bgc-4 {background-color: #528B8B;}
    .bgc-5 {background-color: rgb(205 211 165);}
    .bgc-6 {background-color: #EEE9E9;}
    div.radio {padding: 0px 2px 0px 2px; border-radius: 5px; background: #DDDDDD;}
    label {margin: 0; margin-top: 5px;}
    label.radio {font-size: 0.7vw; font-weight: normal;}
    label.radio-checked {font-size: 0.7vw; font-weight: bolder;}
    #conf_msg {float: right; color: #fff; margin: 0 20px;}
    #a_type {width: 60px; padding: 1px;}
    #q {width: 90px; padding: 0.2px;}
    #input_grid {margin: 0; color: #000;}
    #grid {margin: 0; color: #000; font-size: 0.6vw;}
    .handsontable .currentRow {color: #FFF; background-color: #000;}
    .handsontable .currentCol {color: #FFF; background-color: #000;}
    .handsontable .currenteader {background-color: #000; color: #fff;}
    .handsontable .htDimmed {color: #000; background-color: #ebf4f4;}
    .handsontable td, .handsontable tr, .handsontable th {max-width: 20px; min-width: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap !important;}
    .htCore tbody tr:nth-child(even) td {background-color: lightyellow;}
    .in-wrapper .htCore tbody tr:nth-child(odd) td {background-color: white;}
    table, tr, th, td {border: 1px solid white;}
    #loading {width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center; background-repeat: no-repeat; background-attachment: fixed;}
    #loading img {max-width: 100%; height: auto;}
    .ui-button,
    .ui-button-text .ui-button {font-size: 12px !important;}
    .bc_nc {background-color: #000;}
    .bc_ch {background-color: #eee;}
    .hc_left {text-align: left; padding: 0px 0px 0px 5px;}
    .hc_right {text-align: right; padding: 0px 5px 0px 0px;}
    .hc_center {text-align: center; padding: 0px;}
    .menu {font-weight: bold; text-align: center; width: 20px; height: 20px; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px;}
    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left; padding: 0px;}
    #image {padding: 0; text-align: center; position: absolute;}
    .toolbar {visibility: visible; padding-top: 0.4vw; top: 0px; left: 5px; position: absolute; width: 15%;}
    #msgbox {position: absolute;}
    #msgbox.form-validation-field-1formError:active {display: none;}
    input.form-control {height: 28px;}
    input.selgroups {height: 28px; width: 6.5vw;}
    textarea.form-control {font-size: 0.7vw; height: 57px; background-color: white;}
    input[type='radio'] {width: 0.6vw; height: 0.6vw;}
    input.small {width: 0.6vw; height: 0.6vw;}
    input.big {width: 0.8vw; height: 0.8vw;}
    input[type=button]:hover {background-color: white; color: #04AA6D;}
    input:hover,
    textarea:hover {background-color: #FFFF99;}
    .vw08 {font-size: 0.8vw;}
    .vw07 {font-size: 0.7vw;}
    .vw06 {font-size: 0.6vw;}
    .vw05 {font-size: 0.5vw;}
    .vw04 {font-size: 0.4vw;}
    .pl5 {padding-left: 5px;}
    .pr10 {padding-right: 10px;}
    .name {font-size: 0.9vw; font-weight: bolder;}
    input[type=button],
    input[type=submit],
    input[type=reset] {background-color: #04AA6D; border: none; color: white; text-decoration: none; cursor: pointer;}
    .hide {display: none; height: 0.8vw;}
    .show {display: block; height: 0.8vw;}
    .form-control::placeholder {color: #BBBBBB;}
    .form-control:-ms-input-placeholder {color: #BBBBBB;}
    .form-control::-ms-input-placeholder {color: #BBBBBB;}
    .short {font-size: 1.1vw; margin-top: 0.4vw; margin-bottom: 0.4vw;}
    .long {font-size: 1.1vw; margin-top: 0.4vw; margin-bottom: 0.4vw;}
    #msg_view {background-color: darkgrey; border-radius: 20px; display: none; position: fixed; padding: 10px; width: 33.3%;}
    section {display: none;}
    label.tab_lb {display: inline-block; margin: 0 0 -1px; padding: 5px 15px 5px 15px; text-align: center; color: black; border: 1px solid transparent;}
    label.tab_lb:before {font-weight: normal; margin-right: 10px;}
    label.tab_lb:hover {color: #8470FF; cursor: pointer;}
    input:checked+label.tab_lb {color: #04AA6D; border: 1px solid white; font-weight: bolder; border-top: 4px solid #04AA6D; border-bottom: 2px solid wheat;}
    #tab0:checked~#content0, #tab1:checked~#content1, #tab2:checked~#content2, #tab3:checked~#content3, #tab4:checked~#content4, #tab5:checked~#content5, 
    #tab6:checked~#content6, #tab7:checked~#content7, #tab8:checked~#content8, #tab9:checked~#content9, #tab10:checked~#content10, #tab11:checked~#content11, 
    #tab12:checked~#content12, #tab13:checked~#content13, #tab14:checked~#content14, #tab15:checked~#content15, #tab16:checked~#content16, #tab17:checked~#content17, 
    #tab18:checked~#content18 {display: block;}
    label.radio {font-size: 0.6vw; display: inline-block; text-align: center; color: black;}
    label.radio:before {font-weight: normal; margin-right: 10px;}
    label.radio:hover {color: #8470FF; cursor: pointer;}
    input:checked+label.radio {color: black; font-weight: bolder;}
    input:checked {width: 0.8vw; height: 0.8vw;}
    .hanko {margin: auto; font-size: 0.6vw; border: 3px double #f00; border-radius: 50%; color: #f00; width: 4vw; height: 4vw; display: flex; flex-direction: column; justify-content: center; align-items: center;}
    .hanko hr {width: 100%; margin: 0; border-color: #f00;}
    .hanko span {font-weight: bolder;}
    .hanko2 {margin: auto; font-size: 0.58vw; border: 3px double #f00; border-radius: 50%; color: #f00; width: 3.5vw; height: 3.5vw; display: flex; flex-direction: column; justify-content: center; align-items: center;}
    .hanko2 hr {width: 100%; margin: 0; border-color: #f00;}
    .hanko2 span {font-weight: bolder;}
    @-webkit-keyframes glowing {0% {-webkit-box-shadow: 0 0 3px #004A7F;} 50% {-webkit-box-shadow: 0 0 20px red; } 100% {-webkit-box-shadow: 0 0 3px #004A7F; }}
    @-moz-keyframes glowing {0% {-moz-box-shadow: 0 0 3px #004A7F;} 50% {-moz-box-shadow: 0 0 20px red;} 100% {-moz-box-shadow: 0 0 3px #004A7F;}}
    @-o-keyframes glowing {0% {box-shadow: 0 0 3px #004A7F;} 50% {box-shadow: 0 0 20px red;} 100% {box-shadow: 0 0 3px #004A7F;}}
    @keyframes glowing {0% {box-shadow: 0 0 3px #004A7F;} 50% {box-shadow: 0 0 20px red;}100% {box-shadow: 0 0 3px #004A7F;}}
    .input_error {-webkit-animation: glowing 1500ms infinite; -moz-animation: glowing 1500ms infinite; -o-animation: glowing 1500ms infinite; animation: glowing 1500ms infinite;}
    .error_data {-webkit-animation: glowing 1500ms infinite; -moz-animation: glowing 1500ms infinite; -o-animation: glowing 1500ms infinite; animation: glowing 1500ms infinite;}
    input.btn_del {background-color: maroon;}
    .msg_input_RFID_view {background-color: wheat; padding: 5px; width: 100%; float: left; font-size: 16px;}
    .find_list_RFID {display: none;}
    .find_list_RFID input, .find_list_RFID div {margin-top: 0.5%; margin-bottom: 1%; margin-left: 1%; float: left;}
    .RFID_input_view input, .RFID_input_view div {margin-top: 1%; margin-left: 1%; float: left; text-align: center; font-size: 14px;}
    .scan_find_name {text-align: center; width: 20%;}
    .scan_find_input {text-align: center; width: 28%;}
    .scan_count {padding-top: 5px; text-align: center; width: 12%;}
    .scan_name {text-align: right; width: 16%;}
    .scan_data {width: 32%;}
    .RFID_view {width: 100%; min-height: 100px; max-height: 195px; background-color: seashell; border-radius: 20px; float: left; overflow-x: auto; padding: 6px;}
    .RFID_view .RFID_view_ALL {background-color: #BBBBBB; border-radius: 8px; padding: 2px; margin: 2px; width: 100px; float: left;}
    .RFID_view .RFID_btn {float: left;width: 93px;margin: 5px;padding-top: 6px;padding-bottom: 6px;border-radius: 8px;border: none;color: white;cursor: pointer;text-align: center;}
    .RFID_view .RFID_Alignment {background-color: #04AA6D;}
    .alert_msg {width: 100%;font-size: 120%;color: red;background: transparent;text-align: center;display: none;margin-top: 5px;}
    .stock_confirmation {font-size: 14px;}
    .stock_confirmation td {height: 27.3px;}
    .RFID_view .RFID_btn:not(.RFID_btn_checked):hover {transform: scale(1.07);}
    .RFID_btn_checked {box-shadow: 0px 5px 5px #FEF889;transform: scale(0.9);}
    .RFID_view_1 {background-color: #EEE8AA;}
		.RFID_view_2 {background-color: #87CEFA;}
		.RFID_view_3 {background-color: #6bb11a;}
		.RFID_view_4 {background-color: darkorange;}
		.RFID_view_5 {background-color: #CD853F;}
		.RFID_view_6 {background-color: #8470FF;}
		.RFID_view_7 {background-color: #98F5FF;}
		.RFID_PROCESS {margin-top: -5px;padding-bottom: -10px;}
    .RFID_view_data {padding: 5px;float: left;width: 100%;;min-height: 300px;border-radius: 10px;background-color: seashell;overflow-x: auto; margin-top: 10px;}
		.RFID_view_data div {float: left; display: inline;}
		.RFID_view_data table,.RFID_view_data tr,.RFID_view_data th,.RFID_view_data td {font-size: 14px; line-height: 1.5; vertical-align: top; border: 1px solid #ccc; border-collapse: collapse; font-family: arial, sans-serif;}
		.RFID_view_data th {padding: 4px; font-weight: bold; text-align: center;}
		.RFID_view_data td {padding: 2px 4px; text-align: left;}
		.RFID_view_data td.noborder, .RFID_view_data th.noborder {border: 1px solid transparent; padding: 2px 0px 2px 3px; font-size: 14px;}
		.RFID_view_data td.notright, .RFID_view_data th.notright {border: 1px solid transparent; padding: 2px 0px 2px 3px; font-size: 14px; border-right-color: #ccc;}
    .inspectin_view table,.inspectin_view tr,.inspectin_view td,.inspectin_view th{border: 1px solid transparent;padding: 2px 0px 2px 3px;font-size: 14px;}
    .used_num {font-weight: bold; color: blue;}
		.good_num {font-weight: bold; color: green;}
		.bad_num {font-weight: bold; color: brown;}
    tr.chil-tr {height: 26px;}
    .icon_rfid_data {left: 25px;position: relative;width: 0.3px;height: 0.3px;transform: scale(130);}
    .icon_button{width: 1vw;height: 1vw;}
  </style>
  <script type="text/javascript">
    var bk_name = "不具合登録";
    var menu = "<?php echo $menu; ?>";
    var bk_data = {};
    var lotid_list = [];
    var form_list = [];
    var itemcode, cav_no, itemid, height_save, data_error, obj_data;
    var placeid = '<?php echo $placeid; ?>';
    var documentid = '<?php echo $documentid; ?>';
    var dbug_array = <?php echo htmlspecialchars_decode($dbuglist); ?>;
    var documentnumber = '<?php echo $idmax; ?>';
    var screen_height = screen.height - screen.height * 0.17;
    var today = fb_format_date(new Date(), "YYYY-MM-DD");
    var img_bug = {};
    var select_row = 0;
    var select_col = 1;
    var form_data = new FormData();
    var bt_add_img_click = false;
    var b_input_check = true;
    var set_colname = false;
    var dialog_width = 1200;
    var obj_RFID_data = {};
    var rfid_data_count;
    var processing_position;
    var ww = $(window).width() - 5;
    $(document).ready(function() {
      fb_set_hakko('discoverer', "", "");
      fb_set_hakko('認可者', "", "");
      fb_set_hakko('品管部門者', "", "");
      fb_set_hakko('処置担当者', "", "");
      $("button").button();
      $('[tabindex="1"]').focus();
      $("#alert").dialog({
        autoOpen: false,
        modal: true,
        position: ["center"],
        buttons: []
      });
      $("input[tabindex][type!=button]").each(function() {
        $(this).on("keypress", function(e) {
          if (e.keyCode === 13) {
            var nextElement = $('[tabindex="' + (this.tabIndex + 1) + '"]');
            if (nextElement.length) {
              $('[tabindex="' + (this.tabIndex + 1) + '"]').focus();
              e.preventDefault();
            } else
              $('[tabindex="100"]').focus();
          }
        });
      });
      if (localStorage.getItem(bk_name)) {
        let bk_data = JSON.parse(localStorage.getItem(bk_name));
        if (bk_data['tanto']) {
          $("#person_lable").html("発見者: ");
          $("#person").html(bk_data['tanto']['name']);
          $("#personname").val(bk_data['tanto']['name']);
          $("#usercord").val(bk_data['tanto']['ppro']);
          $("#gp1").val(bk_data['tanto']['gp1']);
          $("#gp2").val(bk_data['tanto']['gp2']);
          //$("#discoverer").val($("#person").html());
          fb_set_hakko('discoverer', $("#person").html(), today);
          //$("#作成日時").val(today);
        }
        fb_viewtable();
      } else {
        fb_viewtable();
      }

      $(".selgroups").focusin(function() {
        $(this).val('');
      });
      $("#lotid").focusin(function() {
        $("#lotid").val('');
      });
      var ua = navigator.userAgent;
      if (ua.indexOf('Windows') < 1) {
        dialog_width = $(window).width() - 40;
        $("#productname").click(function() {
          fb_get_tablet_view();
        });
        $("#itemcode").click(function() {
          fb_get_tablet_view();
        });
      }
      $("#lotid").focusout(function() {
        if (Object.keys(lotid_list).length > 0) {
          let b_item = false;
          $.each(lotid_list, function(i, item) {
            if (item == $("#lotid").val()) {
              b_item = true;
              return;
            }
          });
          if (!b_item) {
            fb_view_error(this.id, '入力が不正です');
          }
        }
      });
      $('#imgbug').css('max-width', ' 100%').css('max-height', screen_height * 1 / 3 + 'px');
      $("#modelnumber").focusin(function() {
        $("#modelnumber").val('');
      });
      $("#modelnumber").focusout(function() {
        if (Object.keys(lotid_list).length > 0) {
          let b_item = false;
          $.each(form_list, function(i, item) {
            if (item == $("#modelnumber").val()) {
              b_item = true;
              return;
            }
          });
          if (b_item) {
            fb_get_cavino();
          } else {
            fb_view_error(this.id, '入力が不正です');
          }
        }
      });
      $("#documentnumber_focus").focus(function() {
        if ($("#documentnumber_focus").val() != $("#documentnumber").val()) {
          $("#documentnumber_focus").val($("#documentnumber").val());
          let img_html = '';
          $("#imgbug").html('');
          $("#file_information").html('');
          var list_margin_bottom = [];
          var datas = {
            ac: "GetJsonXWR",
            wbn_id: $("#documentnumber").val(),
            placeid: placeid,
            mfc_module: 'MissingDefect',
          }
          fb_loading(true);
          $.ajax({
            type: 'GET',
            url: "",
            dataType: 'json',
            data: datas,
            success: function(d) {
              obj_data = d;
              if (Object.keys(obj_data).length > 0) {
                if ('file' in obj_data) {
                  let count_img = 0;
                  let count_noimg = 0;
                  obj_data['file'].forEach((value, index) => {
                    if (index > 0) {
                      img_html += `<div class="wd-100" style="float:left;"><hr noshade></div>`;
                    }
                    if (value["mfc_type"].indexOf('image') > -1) {
                      count_img++;
                      img_html += ` <div class="wd-100 file_img_` + value["mfc_id"] + `" style="float:left;">
                                  <div class="wd-90" style="float:left;"><img style="max-width:100%; max-height:` + screen_height * 2 / 7 + `px" src="GetFile?menu=img&mfc_id=` + value["mfc_id"] + `" onclick="window.open('GetFile?menu=img&mfc_id=` + value["mfc_id"] + `', '_blank');" /></div>
                                  <div class="wd-10" style="float:left; margin-top: 20px;"><input type="button" style="width: 90%;" class="form-control hc_center btn_del" onclick="if (confirm('本当に削除ですか。') == true) {fb_del_img(` + value["mfc_id"] + `);}" value="削除" /></div>
                                </div>`;
                    } else {
                      count_noimg++;
                      img_html += ` <div class="wd-100 file_img_` + value["mfc_id"] + `" style="float:left;margin-top: 10px;">
                                  <div class="wd-20" style="float:left;">　</div>
                                  <div class="wd-70" style="float:left;"> <a style="float:left;" href="GetFile?menu=file&mfc_id=` + value["mfc_id"] + `">添付 ` + parseInt(index + 1) + `: ` + value['mfc_name'] + `</a></div>
                                  <div class="wd-10" style="float:left;"><input type="button" style="width: 90%;" class="form-control hc_center btn_del" onclick="if (confirm('本当に削除ですか。') == true) {fb_del_img(` + value["mfc_id"] + `);}" value="削除" /></div>
                                </div>`;
                    }
                  });
                  $("#file_information").html(`<spam style="font-weight: bold;">添付： ` + Object.keys(obj_data['file']).length + `件</spam><br/>画像： ` + count_img + `件<br/>他の： ` + count_noimg + `件`);
                }

                let html_tab = '';
                let html_tab_input = '<div style="text-align: left;float: left;"><label>キャビ番号：</label>';
                let html_tab_content = '';
                obj_data['data'].forEach((value, index) => {
                  let td_fh = '';
                  let td_wip = '';
                  let td_vd = '';
                  let td_wip_P = '';
                  element = value["wbn_cavino"];
                  processing_position = value["wbn_processing_position"];
                  if (value["wip_numberoftargets_P"] > 0 || value["wbn_wip_testnum_P"] > 0) {
                    if (value["wip_numberoftargets_P"] && processing_position > 5 > 0) {
                      if (value["wip_adversenumber_P"] == "") {
                        value["wip_adversenumber_P"] = 0;
                      }
                      if (value["wip_numberofgoods_P"] == "") {
                        value["wip_numberofgoods_P"] = 0;
                      }
                      if (parseInt(value["wip_numberoftargets_P"] - value["wip_numberofgoods_P"] - value["wip_adversenumber_P"]) != 0) {
                        td_wip_P = ' input_error" title="良品数: ' + value["wip_numberofgoods_P"]
                      }
                      value["afterprocess"] = '保管元：' + value["afterprocess"].split(",")[1];
                    } else {
                      //value["afterprocess"] = '保管元：削除済';
                    }
                    td_wip_P = `<tr>
                            <td class="wd-16 hc_left">仕掛品(P)</td>
                            <td><input type="text" class="form-control hc_right" value="` + value["wip_numberoftargets_P"] + `" readonly/></td>
                            <td><input type="text" class="form-control hc_right" value="` + value["wbn_wip_testnum_P"] + `" readonly/></td>
                            <td><input type="text" class="form-control hc_right" value="` + value["wip_adversenumber_P"] + `" readonly/></td>
                            <td colspan="2">
                              <input type="text" class="form-control hc_left" style="font-size: 0.6vw;" value="` + value["afterprocess"] + `" readonly/>
                            </td>
                          </tr> `;
                    list_margin_bottom[index] = 0;
                  } else {
                    td_wip_P = '';
                    list_margin_bottom[index] = 30;
                  }
                  html_tab_input += '<input style="display: none;"  class="tab' + element + '" id="tab' + index + '" type="radio" name="tabs" checked/> <label class="tab_lb label_tab' + element + '" for="tab' + index + '">#' + element + '</label>';
                  if (processing_position > 5) {
                    if (value["fh_adversenumber"] == "" && value["fh_numberoftargets"] > 0) {
                      value["fh_adversenumber"] = 0;
                    }
                    if (value["fh_numberofgoods"] == "" && value["fh_numberoftargets"] > 0) {
                      value["fh_numberofgoods"] = 0;
                    }
                    if (parseInt(value["fh_numberoftargets"] - value["fh_numberofgoods"] - value["fh_adversenumber"]) != 0 && value["fh_numberoftargets"] > 0) {
                      td_fh = ' input_error" title="良品数: ' + value["fh_numberofgoods"];
                    }
                    if (value["wip_adversenumber"] == "" && value["wip_numberoftargets"] > 0) {
                      value["wip_adversenumber"] = 0;
                    }
                    if (value["wip_numberofgoods"] == "" && value["wip_numberoftargets"] > 0) {
                      value["wip_numberofgoods"] = 0;
                    }
                    if (parseInt(value["wip_numberoftargets"] - value["wip_numberofgoods"] - value["wip_adversenumber"]) != 0) {
                      td_wip = ' input_error" title="良品数: ' + value["wip_numberofgoods"];
                    }
                    if (value["vd_adversenumber"] == "" && value["vd_numberoftargets"] > 0) {
                      value["vd_adversenumber"] = 0;
                    }
                    if (value["vd_numberofgoods"] == "" && value["vd_numberoftargets"] > 0) {
                      value["vd_numberofgoods"] = 0;
                    }
                    if (parseInt(value["vd_numberoftargets"] - value["vd_numberofgoods"] - value["vd_adversenumber"]) != 0 && value["vd_numberoftargets"] > 0) {
                      td_vd = ' input_error" title="良品数: ' + value["vd_numberofgoods"];
                    }
                  }
                  html_tab_table = `<table class="wd-100 RFID_table_data" ondblclick="fb_view_RFID_input();">
																		<tr>
																			<td class="wd-16 hc_left">在庫確認</td>
																			<td class="wd-16 hc_center">対象数</td>
																			<td class="wd-16 hc_center">検査数</td>
																			<td class="wd-16 hc_center">不良数</td>
																			<td class="wd-16 hc_center" rowspan="2">
																				数量管理部 <br>
																				門への連絡
																			</td>
																			<td class="wd-16 hc_center">発生部門係長</td>
																		</tr>
																		<tr>
																			<td class="wd-16 hc_left">完成品</td>
																			<td><input type="text" class="form-control hc_right" value="` + value["fh_numberoftargets"] + `" readonly/></td>
                                      <td><input type="text" class="form-control hc_right" value="` + value["wbn_fh_testnum"] + `" readonly/></td>
                                      <td><input type="text" class="form-control hc_right ` + td_fh + `" value="` + value["fh_adversenumber"] + `" readonly/></td>
                                      <td rowspan="3">
                                        <div class="div_licensor hc_center"></div>
                                      </td>
																		</tr>
																		<tr>
																			<td class="wd-16 hc_left">仕掛品</td>
																			<td><input type="text" class="form-control hc_right" value="` + value["wip_numberoftargets"] + `" readonly/></td>
                                      <td><input type="text" class="form-control hc_right" value="` + value["wbn_wip_testnum"] + `" readonly/></td>
                                      <td><input type="text" class="form-control hc_right ` + td_wip + ` " value="` + value["wip_adversenumber"] + `" readonly/></td>
                                      <td rowspan="2">
                                        <div class="div_quantity hc_center"></div>
                                      </td>
																		</tr>
																		<tr>
																			<td class="wd-16 hc_left">蒸着品</td>
																			<td><input type="text" class="form-control hc_right" value="` + value["vd_numberoftargets"] + `" readonly/></td>
                                      <td><input type="text" class="form-control hc_right" value="` + value["wbn_vd_testnum"] + `" readonly/></td>
                                      <td><input type="text" class="form-control hc_right ` + td_vd + ` " value="` + value["vd_adversenumber"] + `" readonly/></td>
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
                $("#imgbug").html(img_html);
                $('img').mouseover(function() {
                  this.style.cursor = 'zoom-in';
                });
                $('img').mouseout(function() {
                  this.style.cursor = 'default';
                });
                $(".stock_confirmation").html(html_tab);
                if (Object.keys(obj_data['data']).length > 0) {
                  obj_data['data'].forEach((value, index) => {
                    if (Object.keys(obj_data['data']).length == 1) {
                      $('#corrective_table').css('margin-bottom', list_margin_bottom[index] + 'px');
                    } else {
                      $('#corrective_table').css('margin-bottom', (parseInt(list_margin_bottom[index]) - 35) + 'px');
                    }
                    $('input[name="tabs"]').click(function() {
                      $('#corrective_table').css('margin-bottom', (parseInt(list_margin_bottom[this.id.replace("tab", "")]) - 35) + 'px');
                    });
                  })
                }
                fb_set_style();
                if (select_col == 0) {
                  fb_print_pdf();
                }
                const data_input = document.querySelectorAll(".RFID_table_data input");
                let flg_set_check = 0;
                for (let i = 0; i < data_input.length; i++) {
                  if ($(data_input[i]).hasClass('input_error') && flg_set_check < 2) {
                    fb_set_parent_id(data_input[i]);
                    flg_set_check = 2;
                  }
                  if (data_input[i].value > 0 && flg_set_check == 0) {
                    fb_set_check_parent_id(data_input[i]);
                    flg_set_check = 1;
                  }
                }
              }
              obj_RFID_data = [];
              $('.div_rfid_data').html('')
              if (rfid_data_count > 0){
                fb_get_RFID_data();
                $('.div_rfid_data').html('<div style="float:left; "><img class="icon_rfid_data" src="/MissingDefect/GetFile?menu=img&mfc_id=7"/></div>')
              }
              $('.div_licensor').html(fb_set_hakko2($('#発生部門係長').val(), $('#発生部門日時').val()));
              $('.div_quantity').html(fb_set_hakko2($('#数量管理者').val(), $('#数量管理日時').val(), "position:absolute; margin-top: -53px; margin-left:12px;"));
              fb_loading(false);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
              fb_loading(false);
              return;
            }
          });

        } else {
          if (select_col == 0) {
            fb_print_pdf();
          }
        }
      });
      $("input[type!=button][type!=file][id!=documentnumber_focus][id!=documentnumber][readonly!='readonly']").filter('[class!=selgroups]').focus(function() {
        $('#bt_add_img').css('display', 'none');
        $("div").remove("." + this.id);
        if ($('#addimg').val() == '') {
          $("#imgbug").html('');
        }
        if ($('#bugcontent').val() != '') {
          fb_add_attached('bugcontent');
        }
        if ($('#bt_register').val() != '更新' && $('#bt_register').val() != '登録') {
          fb_reset_input();
        }
        //$('#' + this.id).css('backgroundColor', 'transparent');
      });
      $("input[type!=button]").focusout(function() {
        if (this.id == 'cavino') {
          if (this.value != "") {
            let sp_cavino = this.value.split(",");
            sp_cavino.forEach(function(element) {
              if (element.indexOf('-') == -1) {
                if (sp_cavino.filter(x => x == element).length > 1) {
                  fb_view_error('cavino', '同じ英数字不能');
                } else if (isNaN(element)) {
                  if (!isUpperCase(element)) {
                    fb_view_error('cavino', '大文字可能');
                  } else if (element.length != 1) {
                    fb_view_error('cavino', '大文字1桁まで');
                  }
                } else {
                  if (element.length < 1 || element.length > 3) {
                    fb_view_error('cavino', '数字3桁まで');
                  } else if (!isNumberCase(element)) {
                    fb_view_error('cavino', '入力が不正です');
                  }
                }
              }
            })
          }
        } else if (this.required && this.value == "") {
          fb_view_error(this.id, '入力必要です');
        } else if (this.title == 'number') {
          if (this.value != '') {
            if (isNaN(this.value)) {
              fb_view_error(this.id, '数字入力可能');
            } else if (this.max && this.min) {
              if (parseInt(this.value) > this.max) {
                fb_view_error(this.id, '[' + this.min + '~' + this.max + ']可能');
              }
            }
          }
        }

      });
      $("input[type!=button]").change(function() {
        if (((this.id == "productname") || (this.id == "itemcode")) && (this.value == "")) {
          $('#itemcode').attr('readonly', false);
          $('#productname').attr('readonly', false);
          $('#itemcode').val('');
          $('#productname').val('');
        }
      });
      $("#grid").dblclick(function() {
        window.open('/MissingDefect/viewdata?placeid=' + placeid + '&documentid=' + $('#documentnumber').val(), '_blank');
      });
      fb_set_style();
    });

    function fb_view_RFID_input() {
      if (Object.keys(obj_RFID_data).length == 0) {
        alert('RFIDのデータがありません。');
        return;
      }
      $("#alert_dept").dialog({
        autoOpen: false,
        width: 650,
        //height: 900,
        modal: true,
        title: 'RFID連携画面',
        position: [0, 100],
        buttons: [{
          text: "閉じる",
          click: function() {
            $(this).dialog("close");
          }
        }]
      });
      $('#alert_dept').html(`			  <div style="background-color: wheat;" >
																			<div class="msg_input_RFID_view">
																				<div width: 100%;">
																					<div style=" width: 100%;">
																						<div class="find_list_RFID">
																							<div>リストアップ</div>
																							<div><input type="date" id="find_input_start" class="form-control hc___center"/></div>
																							<div> ~ </div>
																							<div><input type="date" id="find_input_end" class="form-control hc___center"/></div>
																							<div class="scan_count"></div>
																						</div>
																						<div class="RFID_view"></div>
																						<div class="alert_msg"></div>
																					</div>
																				</div>
																				<div class="RFID_view_data"><div class="RFID_view_data_1"></div><div class="RFID_view_data_2"></div></div>
																			</div>
																		</div>`);
			$("#alert_dept").dialog("open");
      fb_set_RFID_btn();
      $('.RFID_data input').prop('disabled', true);
    }

    function fb_set_style() {
      $('textarea').css('background-color', 'white');
      $('input[readonly][id!="processing_position"]').css('color', '#444444');
      $('textarea[readonly]').css('color', '#444444');
      $('input[readonly]').css('background-color', '#DDDDDD');
      $('textarea[readonly]').css('background-color', '#DDDDDD');
      $('input[disabled]').css('background-color', 'transparent');
      $('input[disabled]').css('border', 'none');
      $('textarea[disabled]').css('background-color', 'transparent');
      $('textarea[disabled]').css('border', 'none');
      $('.stock_confirmation input').css('background-color', 'transparent');
      $('.stock_confirmation input').css('border', 'none');
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
    }

    function fb_get_place(dataAry, place) {
      var result = '';
      dataAry.forEach((value, key) => {
        if (value.value.indexOf(place) > -1) {
          result = value.m_locator_id + "," + value.value + "," + value.x;
        }
      });
      return result;
    }

    function fb_set_stock_confirmation() {
			$('.stock_confirmation').html('<div class="section_confirmation wd-100" style="text-align: left;float: left;"><label>キャビタブ：</label></div>');
			obj_data['data'].forEach(function(value, index) {
				fb_add_new_section(value["wbn_cavino"]);
			});
			$('#tab0').attr('checked', true);
			if (Object.keys(obj_RFID_data).length > 0) {
				Object.keys(obj_RFID_data).forEach(function(obj_keys) {
					let obj_input = obj_RFID_data[obj_keys].input;
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
						case 'P':
							break;
						default:
							if (!$('.stock_confirmation .RFID_table' + cav_no).hasClass('RFID_input_' + obj_input.old.wic_process_key)) {
								$('.stock_confirmation .RFID_table' + cav_no).append(`
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
						$(key_rfid_input + '1' + cav_no).addClass('input_error');
						$('.label_tab' + cav_no).addClass('input_error');
						$('.tab' + cav_no).attr('checked', true);
					}
					if (obj_RFID_data[obj_keys].hasOwnProperty('output')) {
						obj_RFID_data[obj_keys].output.forEach(function(obj_output, index) {
							$(key_rfid_input + '3' + cav_no).html(fb_parseInt($(key_rfid_input + '3' + cav_no).html()) + fb_parseInt(obj_output.hgpd_difactive));
							$(key_rfid_input + '2' + cav_no).html(fb_parseInt($(key_rfid_input + '2' + cav_no).html()) + fb_parseInt(obj_output.wic_inspection));
							if (!obj_output.wic_id || !obj_output.hgpd_id) {
								$(key_rfid_input + '3' + cav_no).addClass('input_error');
								$('.label_tab' + cav_no).addClass('input_error');
								$('.tab' + cav_no).attr('checked', true);
							}
						});
					};
					if (!$('.tab_lb').hasClass('input_error')) $('.tab' + cav_no).attr('checked', true);
				});
				$(".section_confirmation .tab_lb").each(function(){
						var this_cav = $(this).text().replace("#", "");
						if (fb_parseInt($('.RFID_input_P1' + this_cav).text()) < 1) $('.RFID_input_P' + this_cav).hide();
					});
        $('.RFID_connect_view input[readonly], .RFID_connect_view td:not(:has(input))').click(function() {
          fb_view_RFID_input();
        });
      }
			fb_loading(false);
		}

    function fb_add_new_section(in_cav){
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
							<tr class="RFID_input_P` + changed_cav + `">
								<td class="RFID_input_P0` + changed_cav + ` wd-16 hc_left" >仕掛品(P)</td>
								<td class="hc_center RFID_input_P1` + changed_cav + ` das_tanto"></td>
								<td class="hc_center RFID_input_P2` + changed_cav + `"></td>
								<td class="hc_center RFID_input_P3` + changed_cav + `"></td>
								<td colspan="2">
								</td>
							</tr>
						</table>
					</section>`);
		}

    function fb_viewtable() {
      let name_sel = '';
      let code_sel = '';
      data_error = {};
      //$('#documentnumber').val("");
      var start_sel = '1900-01-01';
      var end_sel = '2999-01-01';
      if ($('#start_sel').val()) {
        start_sel = $('#start_sel').val();
      }
      if ($('#end_sel').val()) {
        end_sel = $('#end_sel').val();
      }
      if ($('#name_sel').val()) {
        code_sel = $('#name_sel').val().split("]")[0].replace("[", "");;
        name_sel = $('#name_sel').val().split("]")[1];
      }
      var sPlant = "<?= $plant_name; ?>";
      if (end_sel < start_sel) {
        alert('開始日時は終了日時より小さいです');
        return;
      }
      var datas = {
        ac: "GetJson",
        // a_type:$("#a_type").val(),
        // q:$("#q").val(),
        // sup:encodeURI($('#mPlant option:selected').val()),
        plantname: sPlant,
        placeid: placeid,
        start_sel: start_sel,
        end_sel: end_sel,
        code_sel: code_sel,
        name_sel: name_sel,
        documentid_sel: $('#documentid_sel').val(),
      }
      fb_loading(true);
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          if(d !== null){
            if (d.length > 0) {
              data_error = d.filter((v) => v[94] === "error")
              if (data_error.length > 0) {
                $("#btn_error_view").css('display', 'block');
              } else {
                $("#btn_error_view").css('display', 'none');
              }
              fb_set_table(d);
            }
          }
          fb_loading(false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          fb_loading(false);
          return;
        }
      });
    }
    var table = undefined;

    function fb_set_table(data) {
      h_num = 32;
      var grid = document.getElementById('grid');
      var columnsdata = [{'title':'指示書','readOnly':true,renderer:"html",className:'htCenter htMiddle',width:50},
        {'title':'資料番号','readOnly':true,type:'numeric',className:'htNoWrap',width:100},
        {'title':'処理状態','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'発見者','readOnly':true,type:'text',className:'htCenter',width:90},
        {'title':'発見日','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'月','readOnly':true,renderer:"text",className:'htCenter',width:40},
        {'title':'品名','readOnly':true,type:"text",className:'htCenter',width:120},
        {'title':'型番','readOnly':true,type:'numeric',className:'htCenter',width:50},
        {'title':'品目コード','readOnly':true,renderer:"text",className:'htCenter',width:100},
        {'title':'キャビ番号','readOnly':true,renderer:"numeric",className:'htCenter',width:100},
        {'title':'項目','readOnly':true,type:'text',className:'htCenter',width:150},
        {'title':'小分類','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'分類','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'不具合詳細','readOnly':true,type:'text',className:'htNoWrap',width:400},
        {'title':'成形Lot','readOnly':true,type:'numeric',className:'htCenter',width:70},
        {'title':'蒸着Lot','readOnly':true,type:'numeric',className:'htCenter',width:70},
        {'title':'成形日','readOnly':true,type:'text',className:'htRight',width:80},
        {'title':'対象数','readOnly':true,type:'numeric',className:'htCenter',width:60},
        {'title':'検査数','readOnly':true,type:'numeric',className:'htCenter',width:60},
        {'title':'不良数','readOnly':true,type:'numeric',className:'htCenter',width:60},
        {'title':'不良率','readOnly':true,type:'numeric',className:'htCenter',width:60},
        {'title':'ランク','readOnly':true,type:'numeric',className:'htCenter',width:60},
        {'title':'BU','readOnly':true,renderer:"text",className:'htCenter',width:60},
        {'title':'判定','readOnly':true,type:'text',className:'htCenter',width:60},
        {'title':'認可者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'認可日時','readOnly':true,type:'text',className:'htCenter',width:80},
        {'title':'判定根拠','readOnly':true,type:'text',className:'htLeft',width:250},
        {'title':'不具合・要望事項','readOnly':true,type:'text',className:'htLeft',width:200},
        {'title':'宛先部署','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'処置期限','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'受理日','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'欠点分類','readOnly':true,type:'text',className:'htCenter',width:60},
        {'title':'発行理由','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'処置回答指示日','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'是正・要望事項','readOnly':true,type:'text',className:'htLeft',width:300},
        {'title':'BU担当者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'BU処理日','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'修理依頼書発行','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'修理依頼書番号','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'現品確認詳細','readOnly':true,type:'text',className:'htCenter',width:250},
        {'title':'処置内容','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'完成品(対象数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'完成品(検査数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'完成品(良品数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'完成品(不良数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(対象数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(検査数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(良品数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(不良数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'蒸着品(対象数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'蒸着品(検査数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'蒸着品(良品数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'蒸着品(不良数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(P)(対象数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(P)(検査数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(P)(良品数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'仕掛品(P)(不良数)','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'処置担当者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'処置完了日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'数量管理者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'数量管理日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'発生部門係長','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'発生部門日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'現品の内容確認','readOnly':true,type:'text',className:'htLeft',width:300},
        {'title':'手直し（修理）','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'在庫処置','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'処置のみ','readOnly':true,type:'text',className:'htCenter',width:0},
        {'title':'発生・原因','readOnly':true,type:'text',className:'htLeft',width:300},
        {'title':'発生・対策','readOnly':true,type:'text',className:'htLeft',width:300},
        {'title':'流出・原因','readOnly':true,type:'text',className:'htLeft',width:300},
        {'title':'流出・対策','readOnly':true,type:'text',className:'htLeft',width:300},
        {'title':'発生.流出入力者','readOnly':true,type:'text',className:'htCenter',width:0.1},
        {'title':'検証結果の検査数','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'検証結果の不良数','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'検証結果の不良率','readOnly':true,type:'text',className:'htRight',width:100},
        {'title':'復旧確認で可','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'長期確認が必要','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'効果','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'コメント','readOnly':true,type:'text',className:'htCenter',width:300},
        {'title':'再発行・資料No','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'対策部門者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'対策部門確認日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'作成部門者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'作成部門確認日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'品管部門者','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'品管部門確認日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'確認コメント','readOnly':true,type:'text',className:'htCenter',width:150},
        {'title':'類似製品・不要','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'類似製品・必要','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'類似プロセス・不要','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'類似プロセス・必要','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'製造課長','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'製造課長確認日時','readOnly':true,type:'text',className:'htCenter',width:100},
        {'title':'メッセージ','readOnly':true,type:'text',className:'htCenter',width:300}
      ];
      var afterselect;
      var olval;
      if (table != undefined) {
        table.destroy();
      }
      table = new Handsontable(grid, {
        data: data,
        startRows: 5,
        startCols: 5,
        height: screen_height * 2 / 3,
        // width: ww * 0.645,
        minSpareRows: 1,
        licenseKey: 'cab0c-22a3a-addf1-94a2c-9202d',
        manualColumnMove: true,
        manualColumnResize: true,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 0,
        columns: columnsdata,
        columnSorting: true,
        filters: true,
        hiddenColumns: true,
        //hiddenColumns: {columns: [0]},
        language: 'ja-jp',
        exportFile: true,
        fixedColumnsLeft: 2,
        //fixedRowsBottom:1,
        hiddenColumns: {
          columns: [11],
          indicators: false
        },
        dropdownMenu: ['make_read_only', 'alignment', 'filter_by_condition', 'filter_action_bar', 'filter_by_value'],
        afterDeselect: function() {
          var cols = table.countCols();
          var rows = table.countRows();
          $("#conf_msg").text("行数: " + rows + "件");
        },
        cells(row, col, prop) {
          if (data) {
            if (data[row][94] == "error" && col > 0) {
              this.renderer = redRenderer;
            }
          }
        }, //afterInit:
        afterSelection: function(r, c, r2, c2, p, s) {
          var datanow = table.getData();
          if (datanow[r][fb_get_col_num('資料番号')] != $('#documentnumber').val()) {
            rfid_data_count = data[r][95] ;
            fb_reset_input();
            $('#bt_add_img').css('display', 'block');
            $('#documentnumber').val(datanow[r][fb_get_col_num('資料番号')]);
            $('#rank').val(datanow[r][fb_get_col_num('ランク')]);
            $('#lotid').val(datanow[r][fb_get_col_num('成形Lot')]);
            $('#lotvd').val(datanow[r][fb_get_col_num('蒸着Lot')]);
            $('#productname').val(datanow[r][fb_get_col_num('品名')]);
            $('#itemcode').val(datanow[r][fb_get_col_num('品目コード')]);
            $('#bugcontent').val(datanow[r][fb_get_col_num('項目')]);
            $('#project').val(datanow[r][fb_get_col_num('小分類')]);
            $('#classification').val(datanow[r][fb_get_col_num('分類')]);
            $('#evidence').val(datanow[r][fb_get_col_num('判定根拠')]);
            $('#moldingdate').val(datanow[r][fb_get_col_num('成形日')]);
            $('#numberoftargets').val(datanow[r][fb_get_col_num('対象数')]);
            $('#numberofinspections').val(datanow[r][fb_get_col_num('検査数')]);
            $('#rate').val(datanow[r][fb_get_col_num('不良率')]);
            $('#adversenumber').val(datanow[r][fb_get_col_num('不良数')]);
            $('#conditioncause').val(datanow[r][fb_get_col_num('不具合詳細')]);
            $('#判定').val(datanow[r][fb_get_col_num('判定')]);
            $('#modelnumber').val(datanow[r][fb_get_col_num('型番')]);
            //$('#discoverer').val(datanow[r][fb_get_col_num('不良発見者')]);
            fb_set_hakko('discoverer', datanow[r][fb_get_col_num('発見者')], datanow[r][fb_get_col_num('発見日')]);
            fb_set_hakko('認可者', datanow[r][fb_get_col_num('認可者')], datanow[r][fb_get_col_num('認可日時')], datanow[r][fb_get_col_num('判定')]);
            //$('#認可者').val(datanow[r][fb_get_col_num('認可者')]);
            //$('#認可日時').val(datanow[r][fb_get_col_num('認可日時')]);
            $('#processing_position').val(datanow[r][fb_get_col_num('処理状態')]);
            if (datanow[r][fb_get_col_num('処理状態')].indexOf("削除済") > -1) {
              $('#processing_position').css("color", "red");
              fb_add_msg('削除理由：' + datanow[r][fb_get_col_num('メッセージ')]);
            } else if (datanow[r][fb_get_col_num('処理状態')].indexOf("再発行") > -1) {
              $('#processing_position').css("color", "red");
              fb_add_msg('再発行理由：' + datanow[r][fb_get_col_num('メッセージ')]);
            } else if (datanow[r][fb_get_col_num('処理状態')].indexOf("再") > -1 || datanow[r][fb_get_col_num('処理状態')].indexOf("直し") > -1) {
              $('#processing_position').css("color", "red");
              fb_add_msg('差戻理由：' + datanow[r][fb_get_col_num('メッセージ')]);
            } else {
              $('#processing_position').css("color", '#444444');
            }
            $('#bu').val(datanow[r][fb_get_col_num('BU')]);
            $('#cavino').val(datanow[r][fb_get_col_num('キャビ番号')]);
            $('#demand').val(datanow[r][fb_get_col_num('不具合・要望事項')]);
            $('#evidence').val(datanow[r][fb_get_col_num('判定根拠')]);
            $('#作成日時').val(datanow[r][fb_get_col_num('発見日')]);
            //是正指示欄
            $('#address').val(datanow[r][fb_get_col_num('宛先部署')]);
            $('#deadline').val(datanow[r][fb_get_col_num('処置期限')]);
            $('#receipt_dt').val(datanow[r][fb_get_col_num('受理日')]);
            $('#due_dt').val(datanow[r][fb_get_col_num('処置回答指示日')]);
            set_radio('修理依頼書', datanow[r][fb_get_col_num('修理依頼書発行')]);
            set_radio('欠点分類', datanow[r][fb_get_col_num('欠点分類')]);
            set_radio('発行理由', datanow[r][fb_get_col_num('発行理由')]);
            $('#due_details').val(datanow[r][fb_get_col_num('是正・要望事項')]);

            //在庫処置欄
            $('#数量管理者').val(datanow[r][fb_get_col_num('数量管理者')]);
            $('#数量管理日時').val(datanow[r][fb_get_col_num('数量管理日時')]);
            set_radio('処置内容', datanow[r][fb_get_col_num('処置内容')]);
            set_radio('処置のみ', datanow[r][fb_get_col_num('処置のみ')]);
            // $('#処置担当者').val(datanow[r][fb_get_col_num('処置担当者')]);
            // $('#処置完了日時').val(datanow[r][fb_get_col_num('処置完了日時')]);
            fb_set_hakko('処置担当者', datanow[r][fb_get_col_num('処置担当者')], datanow[r][fb_get_col_num('処置完了日時')]);
            $('#発生部門係長').val(datanow[r][fb_get_col_num('発生部門係長')]);
            $('#発生部門日時').val(datanow[r][fb_get_col_num('発生部門日時')]);
            $('#現品の内容確認').val(datanow[r][fb_get_col_num('現品の内容確認')]);
            $('#手直し').val(datanow[r][fb_get_col_num('手直し（修理）')]);
            $('#在庫処置').val(datanow[r][fb_get_col_num('在庫処置')]);
            //是正処置欄
            $('#発生・原因').val(datanow[r][fb_get_col_num('発生・原因')]);
            $('#発生・対策').val(datanow[r][fb_get_col_num('発生・対策')]);
            $('#流出・原因').val(datanow[r][fb_get_col_num('流出・原因')]);
            $('#流出・対策').val(datanow[r][fb_get_col_num('流出・対策')]);
            set_radio('復旧確認で可', datanow[r][fb_get_col_num('復旧確認で可')]);
            set_radio('長期確認が必要', datanow[r][fb_get_col_num('長期確認が必要')]);
            $('#wbn_inspection_total').val(datanow[r][fb_get_col_num('検証結果の検査数')]);
            $('#wbn_inspection_bad').val(datanow[r][fb_get_col_num('検証結果の不良数')]);
            $('#wbn_inspection_rate').val(datanow[r][fb_get_col_num('検証結果の不良率')]);
            set_radio('効果', datanow[r][fb_get_col_num('効果')]);
            $('#effect_NG_msg').val(datanow[r][fb_get_col_num('コメント')]);
            $('#reissue_id').val(datanow[r][fb_get_col_num('再発行・資料No')]);
            //確認
            $('#対策部門者').val(datanow[r][fb_get_col_num('対策部門者')]);
            $('#対策部門確認日時').val(datanow[r][fb_get_col_num('対策部門確認日時')]);
            $('#作成部門者').val(datanow[r][fb_get_col_num('作成部門者')]);
            $('#作成部門確認日時').val(datanow[r][fb_get_col_num('作成部門確認日時')]);
            // $('#品管部門者').val(datanow[r][fb_get_col_num('品管部門者')]);
            // $('#品管部門確認日時').val(datanow[r][fb_get_col_num('品管部門確認日時')]);
            fb_set_hakko('品管部門者', datanow[r][fb_get_col_num('品管部門者')], datanow[r][fb_get_col_num('品管部門確認日時')], "", 2);
            $('#quality_control_person').val(datanow[r][fb_get_col_num('品管部門者')]);
            if (datanow[r][fb_get_col_num('確認コメント')]) {
              $('#quality_control_comment').html(datanow[r][fb_get_col_num('確認コメント')]);
            } else {
              $('#quality_control_comment').html('コメントがある場合記載');
            }
            if (datanow[r][fb_get_col_num('発生.流出入力者')]) {
              $('.発生流出').attr('title', '入力者： ' + datanow[r][fb_get_col_num('発生.流出入力者')]);
            }
            //水平展開欄
            $('#類似製品・不要').val(datanow[r][fb_get_col_num('類似製品・不要')]);
            $('#類似製品・必要').val(datanow[r][fb_get_col_num('類似製品・必要')]);
            $('#類似プロセス・不要').val(datanow[r][fb_get_col_num('類似プロセス・不要')]);
            $('#類似プロセス・必要').val(datanow[r][fb_get_col_num('類似プロセス・必要')]);
            $('#製造課長').val(datanow[r][fb_get_col_num('製造課長')]);
            $('#製造課長確認日時').val(datanow[r][fb_get_col_num('製造課長確認日時')]);
            $('#addimg').val('');

            if ($('#processing_position').val() == '完了') {
              $('#bt_register').val("再発行");
            } else if ($('#processing_position').val() == '未判定' || $('#processing_position').val() == '再判定' || $('#processing_position').val() == '再発行') {
              $('#bt_register').val("更新");
            } else {
              $('#bt_register').val("---");
            }
          }
          select_row = r;
          select_col = c;
          var sr = r2;
          var er = r;
          var sc = c2;
          var ec = c;
          var val_num = 0;
          var sel_num = 0;
          var num_num = 0;
          var msg = "";
          if (r < r2) {
            sr = r;
            er = r2;
          };
          if (c < c2) {
            sc = c;
            ec = c2;
          };
          for (var ic = sc; ic < ec + 1; ic++) {
            for (var ir = sr; ir < er + 1; ir++) {
              val = this.getDataAtCell(ir, ic);
              if (val !== "") {
                sel_num++;
              }
              val = parseFloat(val);
              if (!isNaN(val)) {
                num_num++;
                val_num += val;
                var avg_num = (val_num / num_num);
              }
              msg = "平均:" + avg_num + " データの個数:" + sel_num + " 合計:" + Math.round(val_num * 100) / 100;
              if (num_num == "0") {
                msg = "データの個数:" + sel_num;
              }
              if (sel_num == "1") {
                $("#conf_msg").text("");
              } else {
                $("#conf_msg").text(msg);
              }
            }
          }
          $('#documentnumber_focus').focus();
        }
      });
      var datanow = table.getData();
      var rows = table.countRows();
      if (rows > 0) {
        for (i = 0; i < rows; i++) {
          if (datanow[i][fb_get_col_num('資料番号')] == documentid) {
            table.selectCell(i, 1, i, 1);
            break;
          }
        }
      }
      if (!set_colname) {
        set_colname = fb_get_colname();
      }
      $("#conf_msg").text("データの個数: " + data.length + "件");
    }
    const redRenderer = function(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.color = 'red';
    };

    function fb_error_view() {
      fb_set_table(data_error);
    }

    function fb_format_date(date, format) {
      var weekday = ["日", "月", "火", "水", "木", "金", "土"];
      if (!format) {
        format = 'YYYY/MM/DD(WW) hh:mm:ss'
      }
      format = format.replace(/YYYY/g, date.getFullYear());
      format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
      format = format.replace(/DD/g, ('0' + date.getDate()).slice(-2));
      format = format.replace(/WW/g, weekday[date.getDay()]);
      format = format.replace(/hh/g, ('0' + date.getHours()).slice(-2));
      format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2));
      format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
      return format;
    }

    function fb_download() {
      table.updateSettings({
        hiddenColumns: true,
        hiddenColumns: {
          columns: [fb_get_col_num('指示書')],
          indicators: true
        },
      });
      table.getPlugin("exportFile").downloadFile("csv", {
        columnHeaders: true,
        //rowHeaders: true,
        filename: "不具合連絡書" + today
      });
      table.updateSettings({
        hiddenColumns: true,
        hiddenColumns: {
          columns: [fb_get_col_num('小分類')],
          indicators: true
        },
      });
    }

    function fb_graphic() {
      url_graphic = '/MissingDefect/graphic?placeid=' + placeid;
      //localStorage.removeItem(url_register + "data");
      //localStorage.removeItem(url_register + "person");
      window.open(url_graphic, '_blank');
    }

    function fb_viewdata() {
      url_graphic = '/MissingDefect/viewdata?placeid=' + placeid;
      //localStorage.removeItem(url_register + "data");
      //localStorage.removeItem(url_register + "person");
      window.open(url_graphic, '_blank');
    }

    function fb_clr(this_er) {
      $(this_er).closest('.formError').remove();
    }

    function fb_add_attached(id) {
      $("div").remove(".bugcontentimage");
      let margin_left = $('#' + id).width() - 70;
      $('#msgbox').append('<div class="form-validation-field-1formError parentFormformCheck formError bugcontentimage" style="opacity: 0.87; position: absolute; top: ' + $('#' + id).position()['top'] + 'px; left: ' + $('#' + id).position()['left'] + 'px; right: initial; width:70px; margin-top: -4px; margin-left: ' + margin_left + 'px; display: block;"><input type="button" id="bt_att_img" style="height:28px" class="form-control hc_center " value="添付" /></div>');
      $("#bt_att_img").click(function() {
        bt_add_img_click = false;
        $('#addimg').click();
      });
    }

    function fb_view_error(id, msg = '必須項目です') {
      $("div").remove("." + id);
      $('#msgbox').append('<div onclick="fb_clr(this);" class="form-validation-field-1formError parentFormformCheck formError classformError ' + id + '" style="opacity: 0.87; position: absolute; top: ' + $('#' + id).position()['top'] + 'px; left: ' + $('#' + id).position()['left'] + 'px; right: initial; margin-top: -37px; display: block;"><div class="formErrorContent">' + msg + '</div><div class="formErrorArrow"><div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div></div></div>');
      b_input_check = false;
    }

    function fb_add_msg(msg) {
      $("div").remove(".processing_position");
      let margin_left = $('#processing_position').width() - 10;
      $('#msgbox').append('<div class="form-validation-field-1formError parentFormformCheck formError processing_position" style="opacity: 0.87; position: absolute; top: ' + $('#processing_position').position()['top'] + 'px; left: ' + $('#processing_position').position()['left'] + 'px; right: initial; width:20px; margin-top: -15px; margin-left: ' + margin_left + 'px; display: block; color:red;"></div>');
    }
    const isUpperCase = c => {
      return /^[A-Z]+$/g.test(c)

    }
    const isNumberCase = c => {
      return /^[0-9]+$/g.test(c)
    }

    function fb_input_check() {
      $("div").remove(".classformError");
      b_input_check = true;
      const btn_defect = document.getElementsByClassName("form-control");
      for (let i = 0; i < btn_defect.length; i++) {
        if (btn_defect[i].id == 'cavino') {
          if (btn_defect[i].value != "") {
            let sp_cavino = btn_defect[i].value.split(",");
            sp_cavino.forEach(function(element) {
              if (sp_cavino.filter(x => x == element).length > 1) {
                fb_view_error('cavino', '同じ英数字不能');
              } else if (isNaN(element)) {
                if (!isUpperCase(element)) {
                  fb_view_error('cavino', '大文字？？');
                } else if (element.length != 1) {
                  fb_view_error('cavino', '大文字1桁まで');
                }
              } else {
                if (element.length < 1 || element.length > 3) {
                  fb_view_error('cavino', '数字3桁まで');
                } else if (!isNumberCase(element)) {
                  fb_view_error('cavino', '入力が不正です');
                }
              }
            })
          }
        } else if (btn_defect[i].value == "" && btn_defect[i].required) {
          fb_view_error(btn_defect[i].id);
        } else if (btn_defect[i].title == "number") {
          if (isNaN(btn_defect[i].value) && btn_defect[i].value != '') {
            fb_view_error(btn_defect[i].id, '数字入力可能');
          } else if (btn_defect[i].max && btn_defect[i].min) {
            if ((btn_defect[i].max < parseInt(btn_defect[i].value)) || (btn_defect[i].min > parseInt(btn_defect[i].value))) {
              fb_view_error(btn_defect[i].id, '[' + btn_defect[i].min + '~' + btn_defect[i].max + ']可能');
            }
          }
        }
      }
      return b_input_check;
    }


    function fb_reset_input() {
      fb_set_hakko('discoverer', $("#person").html(), today);
      fb_set_hakko('認可者', "", "");
      fb_set_hakko('品管部門者', "", "");
      fb_set_hakko('処置担当者', "", "");
      $("div").remove(".bugcontentimage");
      $("div").remove(".processing_position");
      //$("#作成日時").val(today);
      $('#bt_register').val("登録");
      $('#imgbug').html("");
      $('#bt_add_img').css('display', 'none');
      $("div").remove(".classformError");
      $('textarea').val('');
      $('input[type!=radio][type!=hidden][name!=header][type!=button][id!=documentnumber][id!=discoverer][id!=作成日時]').filter('[class!=selgroups]').val('');
      $('input').prop('checked', false);
      $('input:radio').prop('disabled', true);
      $('input:radio').prop('class', 'small');
      $('label').filter('[class=radio-checked]').prop('class', 'radio');
      $('#documentnumber').val(documentnumber);
      $('#水平展開欄').prop('class', 'smallclass wd-25 short');
      //$("#discoverer").val($("#person").html());
      $("#documentnumber_focus").val($("#documentnumber").val());
      $(".stock_confirmation").html(`<table class="wd-100 wd7">
                <tr>
                  <td class="wd-16 hc_left">在庫確認</td>
                  <td class="wd-16 hc_center">対象数</td>
                  <td class="wd-16 hc_center">検査数</td>
                  <td class="wd-16 hc_center">不良数</td>
                  <td class="wd-16" rowspan="2">
                    数量管理部 <br>
                    門への連絡
                  </td>
                  <td class="wd-16 hc_center">発生部門係長</td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">完成品</td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td rowspan="3">
                    <input type="text" class="form-control hc_center name" placeholder="未" disabled></input>
                    <input type="text" class="form-control hc_center " disabled></input>
                  </td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">仕掛品</td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td rowspan="2">
                    <input type="text" class="form-control hc_center name" placeholder="未" disabled></input>
                    <input type="text" class="form-control hc_center " disabled></input>
                  </td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">蒸着品</td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">仕掛品(P)</td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td><input type="text" class="form-control hc_right" disabled></input></td>
                  <td colspan="2">
                    <input type="text" class="form-control hc_left" style="font-size: 0.6vw;" disabled />
                  </td>
                </tr>
              </table>`);
      fb_set_style();
    }
    const get_day_of_month = (year, month) => {
      return new Date(year, month, 0).getDate();
    };

    function fb_register(item) {
      if (!$('#person').html()) {
        fb_get_user();
        return;
      }
      if (item.value == "---") {
        return;
      }
      if (fb_input_check()) {
        var rejection_reason = "";
        if (item.value == '再発行') {
          while (rejection_reason == "") {
            rejection_reason = prompt("１・ 再発行の場合必ず理由を入力し,「OK」ボタンを押してください。\n２・ キャンセルの場合「キャンセル」ボタンを押してください。");
            if (rejection_reason != "") break;
          }
          if (!rejection_reason) {
            return;
          }
        } else {
          if (confirm(' 1. 夾雑チャート\n 2. 外観規格\n 3. 限度見本\n 4. 検査基準書\n 5. 作業手順書\n 6. ロット、Cav\n 7. いつもより多い\n 8. いつもとモードが違う' + '\n上記内容を確認しましたか。\n\n 確認ができたら' + item.value + 'します。') == false) {
            return;
          }
        }
        data = [];
        data.push($('#documentnumber').val());
        data.push($('#productname').val());
        data.push($('#itemcode').val());
        data.push($('#bugcontent').val());
        data.push($('#project').val());
        data.push($('#classification').val());
        data.push($('#lotid').val());
        data.push($('#lotvd').val());
        data.push($('#moldingdate').val());
        data.push($('#conditioncause').val());
        data.push($('#numberofinspections').val());
        data.push($('#adversenumber').val()); // 10
        data.push($('#rate').val().replace("%", ""));
        data.push($('#modelnumber').val());
        data.push($('#person').html());
        data.push($('#usercord').val());
        data.push($('#gp1').val());
        data.push($('#gp2').val());
        data.push($('#bu').val());
        data.push($('#cavino').val());
        var datas = {
          ac: '登録更新',
          value: item.value,
          data: data,
          placeid: placeid,
          rejection_reason: rejection_reason,
          quality_control_person: $('#quality_control_person').val(),
        }
        fb_loading(true);
        $.ajax({
          type: 'POST',
          url: "",
          dataType: 'text',
          data: datas,
          success: function(d) {
            if ((d.indexOf("_OK_") > 0) && (d != "__OK__||")) {
              const myArray = d.split("|");
              let id = myArray[1];
              if ($("#imgbug").html()) {
                if (fb_add_image('add', id) == false) {
                  alert("登録出来ましたが、添付登録出来ませんでした。");
                  fb_viewtable();
                } else {
                  alert(item.value + "しました。");
                  fb_viewtable();
                }
              } else {
                alert(item.value + "しました。");
                fb_viewtable();
              }
              fb_reset_input();
              documentnumber = myArray[2];
              $("#documentnumber").val(documentnumber);
            } else if (d.indexOf("再発行") > -1) {
              $("#documentnumber_focus").val("");
              const myArray = d.split("|");
              documentid = myArray[1];
              alert(myArray[0]);
              fb_viewtable();
            } else {
              alert("登録失敗 (; ω ;)ｳｯ…");
              fb_loading(false);
            }
            window.opener.location.reload();
            return;
          }
        });
      }
    }
    $(function() {
      $(document).on('keyup', '#numberofinspections', function(e) {
        fb_auto_rate();
      });
      $(document).on('keyup', '#adversenumber', function(e) {
        fb_auto_rate();
      });
    });

    function fb_set_parent_id(item, words = "Add") {
      if (item.parentNode.parentNode.parentNode.parentNode.parentNode.id) {
        if (words == "Add") {
          $('label[for="tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "") + '"]').addClass('input_error');
          $('#tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "")).attr('checked', true);
        } else {
          $('label[for="tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "") + '"]').removeClass('input_error');
        }
      }
    }

    function fb_set_check_parent_id(item) {
      if (item.parentNode.parentNode.parentNode.parentNode.parentNode.id) {
        $('#tab' + item.parentNode.parentNode.parentNode.parentNode.parentNode.id.replace("content", "")).attr('checked', true);
      }
    }

    function fb_auto_rate() {
      if ($("#adversenumber").val() >= 0 && $("#numberofinspections").val() > 0) {
        $("#rate").val(Math.round($("#adversenumber").val() / $("#numberofinspections").val() * 10000) / 100 + '%')
      } else {
        $("#rate").val('');
      }
    }

    function fb_get_cavino() {
      if ($("#modelnumber").val()) {
        var datas = {
          ac: "キャビ番号取得",
          itemcode: $("#itemcode").val(),
          modelnumber: $("#modelnumber").val(),
          placeid: placeid,
        }
        //fb_loading(true);
        $.ajax({
          type: 'GET',
          url: "",
          dataType: 'text',
          data: datas,
          success: function(d) {
            $('#cavino').val(d);
            if (d != "") {
              $('[tabindex="6"]').focus();
            } else {
              $('[tabindex="5"]').focus();
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
            // fb_loading(false);
            return;
          }
        });
      } else {
        $('#cavino').val('');
      }
    }

    function fb_auto_change(this_item) {
      var sum_id = 'productnameitemcode';
      $("#bu").val("");
      $("#lotid_list").empty();
      $("#form_list").empty();
      $('#modelnumber').val('');
      $('#lotid').val('');
      $('#cavino').val('');
      lotid_list = [];
      form_list = [];
      $("#" + sum_id.replace(this_item.id, "")).val("");
      if (this_item.value != '') {
        <?php foreach ($d_item as $key => $item) { ?>
          if (this_item.value == "KEY<?php echo $key ?>") {
            $("#bu").val("<?php if ($item['BU'] != '') {
                            echo $item['BU'] . 'BU';
                          } ?>");
            $("#productname").val("<?php echo $item['品名'] ?>");
            $("#itemcode").val("<?php echo $item['品目コード'] ?>");
            $("div").remove(".productname");
            $("div").remove(".itemcode");
            $('[tabindex="' + (this_item.tabIndex + 1) + '"]').focus();
            itemcode = $("#itemcode").val();
            var datas = {
              ac: "ロット番号取得",
              itemcode: itemcode,
              placeid: placeid,
            }
            //fb_loading(true);
            $.ajax({
              type: 'GET',
              url: "",
              dataType: 'json',
              data: datas,
              success: function(d) {
                if ('ロット№' in d) {
                  $.each(d['ロット№'], function(i, item) {
                    $("#lotid_list").append($("<option>").attr('value', item));
                    lotid_list.push(item);
                  });
                  $('#lotid').val(d['ロット№'][0]);
                  $("div").remove(".lotid");
                }
                if ('型番' in d) {
                  $.each(d['型番'], function(i, item) {
                    $("#form_list").append($("<option>").attr('value', item));
                    form_list.push(item);
                  });
                  $('#modelnumber').val(d['型番'][0]);
                  $("div").remove(".modelnumber");
                }
                if ('キャビ番号' in d) {
                  $('#cavino').val(d['キャビ番号'][0]);
                  $("div").remove(".cavino");
                }
                if (!$('#modelnumber').val()) {
                  $('[tabindex="3"]').focus();
                } else if (!$('#lotid').val()) {
                  $('[tabindex="4"]').focus();
                } else {
                  $('[tabindex="5"]').focus();
                }
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
                // fb_loading(false);
                return;
              }
            });
          }
        <?php  } ?>
      }
    }

    function fb_get_col_num(titleHeader) {
      var getNum = -1;
      const ColHeader = table.getColHeader();
      ColHeader.forEach((element, index) => {
        if (element === titleHeader) {
          getNum = index;
        }
      });
      return getNum
    }

    function fb_sel_col(col_name) {
      if (fb_get_col_num(col_name) > -1) {
        table.selectCell(select_row, fb_get_col_num(col_name) + 1, select_row, fb_get_col_num(col_name) + 1);
        table.selectCell(select_row, fb_get_col_num(col_name) - 1, select_row, fb_get_col_num(col_name) - 1);
        table.selectCell(select_row, fb_get_col_num(col_name), select_row, fb_get_col_num(col_name));
      }
    }

    function fb_get_colname() {
      var arr_name = [];
      const ColHeader = table.getColHeader();
      ColHeader.forEach((element, index) => {
        $("#colname_list").append($("<option>").attr('value', element));
      });
      return true
    }

    function fb_get_user() {
      $("#alert").dialog({
        autoOpen: false,
        width: dialog_width,
        modal: true,
        title: '先に担当者を選択して下さい',
        position: ['center', 40],
        buttons: []
      });
      if (placeid == 1000073) {
        gp1 = "山崎";
        gp2 = "品質管理係";
      } else if (placeid == 1000079) {
        gp1 = "野洲";
        gp2 = "品管係";
      } else {
        gp1 = "NPG";
        gp2 = "製造グループ";
      }
      $.getJSON("/common/AjaxUserSelect?action=user&target=setUser4Data&gp1=" + decodeURIComponent(gp1) + "&gp2=" + decodeURIComponent(gp2) /*+"&callback=?"*/ , function(data) {
        $("#message").html(data);
      });
      $("#alert").dialog("open");
    }

    function fb_get_tablet_view() {
      $("#alert").dialog({
        autoOpen: false,
        width: dialog_width,
        modal: true,
        title: '品目コード・品名を選択して下さい',
        position: ['center', 40],
        buttons: []
      });
      $("#message").html(`<div style="float:left; width:100%; margin:10px; font-size:16px; text-align: center;">
                            <span>品目コード・品名検索　</span><input id="sel_tablet" oninput="fb_sel_tablet_view();"/>
                          </div>
                          <div class="tablet_div" style="float:left; overflow:auto; width:100%;">
                            <div class="tablet_col1" style="float:left; width:48%;">
                            </div>
                            <div class="tablet_col0" style="float:left; width:48%;">
                            </div>
                         </div>`);
      fb_sel_tablet_view();
      $(".tablet_div").css("max-height", (screen.height - 150) + "px");
      $("#alert").dialog("open");
    }

    function fb_sel_tablet_view() {
      $(".tablet_col0").html("");
      $(".tablet_col1").html("");
      let key = 1;
      <?php
      foreach ($d_item as $key => $val) {
      ?>
        if ('<?php echo $val["品目コード"] . $val["品名"] ?>'.toUpperCase().indexOf($("#sel_tablet").val().toUpperCase()) > -1) {
          $(".tablet_col" + (key % 2)).append('<div style="float:left; width:100%; text-align:left; margin-top:3px;"><button style="text-align:left; overflow: hidden;" class="btn_def" onclick="fb_set_itemcode_name(\'<?php echo $val["品目コード"] ?>\',\'<?php echo $val["品名"] ?>\');"><?php echo "【" . $val["品目コード"] . "】" . $val["品名"] ?></button><div>')
          key++;
        }
      <?php
      }
      ?>
      $(".btn_def").button();
      $('.tablet_div').scrollTop(0);
    }

    function fb_set_itemcode_name(itemcode, name) {
      $("#productname").val(name);
      $("#itemcode").val(itemcode);
      $("div").remove(".productname");
      $("div").remove(".itemcode");
      $("#message").html("");
      $("#alert").dialog("close");
    }

    function fb_loading(flag) {
      $('#loading').remove();
      if (!flag) return;
      $('<div id="loading" />').appendTo('body');
    }

    function fb_setUser4Data(name,ppro,gp1,gp2) {
      $("#person_lable").html("発見者: ");
      $("#person").html(name);
      $("#personname").val(name);
      $('#usercord').val(ppro);
      $('#gp1').val(gp1);
      $('#gp2').val(gp2);
      fb_set_hakko('discoverer', name, today);
      //$("#alert").html('');
      $("#alert").dialog("close");
      fb_loading(false);
    }

    function fb_get_bug() {
      $("#alert").dialog({
        autoOpen: false,
        width: dialog_width,
        modal: true,
        title: '不具合内容を選択して下さい',
        position: ['center', 40],
        buttons: []
      });

      $.getJSON("/MissingDefect/AjaxBugSelect?action=bug&placeid=" + placeid + "&gp1=" + decodeURIComponent("all_bug") /*+"&callback=?"*/ , function(data) {
        $("#message").html(data);
      });
      $("#alert").dialog("open");
    }

    function setBug(classification, project, defectivename) {
      $('#bugcontent').val(defectivename);
      $('#project').val(project);
      $('#classification').val(classification);
      $('#message').html('');
      $('#alert').dialog('close');
      fb_add_attached('bugcontent');
      $('#numberofinspections').focus();
    }

    function fb_print_pdf() {
      let sum_html = '';
      obj_data['data'].forEach((value, index) => {
        // if (value['wbn_cavino'].indexOf(",") > 0) {
        //   value['wip_numberoftargets'] = "";
        //   value['wip_numberoftargets_P'] = "";
        //   value['vd_numberoftargets'] = "";
        //   value['fh_numberoftargets'] = "";
        // }
        let print_html = `<table class="rv"> <tr> <td rowspan="2" colspan="7"> <span class="head"> 製品隔離指示書 </span> </td> <td colspan="4"><br></td> </tr> <tr> <td class="rv" colspan="2"> NO: </td> <td colspan="2"> <br> </td> </tr> <tr> <td class="rv" colspan="2">発行日</td> <td class="rv" colspan="3">` + $("#作成日時").val() + `</td> <td class="rv" colspan="2">不具合発行No</td> <td class="rv" colspan="4"><span class="thick">` + $('#documentnumber').val() + `</span></td> <tr> <td class="rv" colspan="2">品名</td> <td class="rv" colspan="3"><input type="text" value="` + $('#productname').val() + `" ></input></td> <td class="rv" colspan="2">型番</td> <td class="rv" colspan="4">` + $('#modelnumber').val() + `</td> </tr> <tr> <td class="rv" colspan="2">発生現品札No</td> <td class="rv" colspan="3"></td> <td class="rv" colspan="2">対象cav</td> <td class="rv" colspan="4">` + value['wbn_cavino'] + `</td> </tr> <tr> <td class="rv" colspan="2">完了現品札No</td> <td class="rv" colspan="3"></td> <td class="rv" colspan="2">内容（不具合理由）</td> <td class="rv" colspan="4">` + $('#bugcontent').val().substr(0, 50) + `</td> </tr> <tr> <td colspan="5">不具合処置内容</td> <td colspan="6">不具合処置結果確認</td> </tr> <tr> <td class="rv" rowspan="3" colspan="4"></td> <td class="rv center">処置者</td> <td class="rv" rowspan="3" colspan="5"></td> <td class="rv center">確認者</td> </tr> <tr> <td class="rv" rowspan="2"><br><br></td> <td class="rv" rowspan="2"><br><br></td> </tr> <tr> </tr> <tr> <td colspan="11">在庫処置者：</td> </tr> <tr> <td class="center" rowspan="2" colspan="1">数量</td> <td class="rv center" colspan="1">製造係</td> <td class="rv center" colspan="3">処置係 </td> <td class="rv" colspan="1"> <img src="http://khahoangfpt.pythonanywhere.com/static/images/gachcheo.png" alt="Smiley face" width="67" height="20"></td> <td class="rv center" colspan="1">現品札No</td> <td class="rv center" colspan="1">廃棄数</td> <td class="rv" colspan="1"> <img src="http://khahoangfpt.pythonanywhere.com/static/images/gachcheo.png" alt="Smiley face" width="67" height="20"></td> <td class="rv center" colspan="1">現品札No</td> <td class="rv center" colspan="1">廃棄数</td> </tr> <tr> <td class="rv center" colspan="1">対象数</td> <td class="rv center" colspan="2">不良数 </td> <td class="rv center" colspan="1">良品数</td> <td class="rv right" colspan="1">1</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">11</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">仕掛品 <br> <span class="thick"> M </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['wip_numberoftargets'] + `</td> <td class="rv right" rowspan="2" colspan="2"></td> <td class="rv right" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">2</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">12</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">3</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">13</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">仕掛品 <br> <span class="thick"> P </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['wip_numberoftargets_P'] + `</td> <td class="rv" rowspan="2" colspan="2"></td> <td class="rv" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">4</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">14</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">5</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">15</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">蒸着品<br> <span class="thick"> J </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['vd_numberoftargets'] + `</td> <td class="rv" rowspan="2" colspan="2"></td> <td class="rv" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">6</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">16</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">7</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">17</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">完成品<br> <span class="thick"> 00 </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['fh_numberoftargets'] + `</td> <td class="rv" rowspan="2" colspan="2"></td> <td class="rv" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">8</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">18</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">9</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">19</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv" colspan="5"></td> <td class="rv right" colspan="1">10</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">20</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> </table>`;
        sum_html += '<div>' + print_html + '<br>' + print_html + '</div>';
      });
      printJS({
        printable: sum_html,
        type: 'raw-html',
        documentTitle: '製品隔離指示書 ｜ ' + $('#documentnumber').val() + '｜' + $("#作成日時").val(),
        modalMessage: '',
        targetStyles: ['*'],
        header: '',
        honorMarginPadding: false,
        showModal: true,
        style: `body { font-size: 14px; } table { width: 100%; border-collapse: collapse; } td { width: 10%; padding-left: 3px; } tr { height: 22px; } table.rv, tr.rv, td.rv { border: 1px solid; } .right { text-align: right; padding-right: 3px; } .center { text-align: center; } .thick { font-weight: bold; } .head { font-size: 25px; } img { display: inline-block; }input{background-color:transparent;border:none;}`
      })
    }

    function fb_get_RFID_data() {
      obj_RFID_data=[];
			var datas = {
				ac: "Ajax_Get_RFID_Connect_Data",
				placeid: placeid,
				wbn_id: $('#documentnumber').val(),
			}
			$.ajax({
				type: 'GET',
        url: "/MissingDefect",
				dataType: 'json',
				data: datas,
				success: function(d) {
					if (Object.keys(d.new).length > 0) {
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
			var wic_process_count = 4;
			var obj_input_view, obj_output_view;
			if (Object.keys(obj_RFID_data).length > 0) {
				Object.keys(obj_RFID_data).forEach(function(obj_keys) {
					if (obj_RFID_data[obj_keys].hasOwnProperty('input')) {
						let obj_input = obj_RFID_data[obj_keys].input;
						if('old' in obj_input){
              $('.RFID_view').append('<div class="RFID_btn RFID_Alignment" rfid="' + obj_keys + '">' + obj_keys.substring(obj_keys.length - 8, obj_keys.length) + '</div>');
              if (!obj_input.old.wic_id) $('.RFID_Alignment[rfid="' + obj_keys + '"]').addClass('error_data');
						}
					}
				});
			}

			$('.RFID_Alignment').click(function() {
				fb_alert_msg('');
				let item = $(this).attr('rfid');
				$('.RFID_view div').removeClass('RFID_btn_checked');
				fb_get_data_scan(obj_RFID_data[item]);
				$(this).addClass('RFID_btn_checked');
				if ($(this).hasClass('error_data')) {
					if (!obj_RFID_data[item].input.old.hgpd_id) {
						fb_alert_msg("実績データがありません。確認してください。");
					} else if (!obj_RFID_data[item].input.old.wic_id) {
						fb_alert_msg("在庫管理データがありません。確認してください。");
					} else if (RFID_input_error_list.indexOf(item) > -1 && RFID_input_error_list.length > 0) {
						fb_alert_msg("上記のRFIDが登録されました。確認してください。");
					}
				}
			});
			fb_loading(false);
		}
		function fb_get_data_scan(input_item) {
			let old_item = input_item.input.old;
			let new_item = input_item.input.new;
			$('.RFID_view_data_1').html("");
			$('.RFID_view_data_2').html("");
			$('.RFID_view_data_1').html(`<table>
																<thead>
																	<tr><th colspan="2" class="` + fb_get_class_process(new_item.wic_process) + `">` + fb_get_string(old_item.wic_process, new_item.wic_process) + `</th></tr>
																</thead>
																<tbody>
																	<tr class="chil-tr"><td style='min-width: 65px'>在庫ID</td><td style='max-width: 220px; min-width: 200px'>` + fb_get_string(new_item.wic_id) + `</td></tr>
																	<tr class="chil-tr"><td>管理ID</td><td>`  + fb_get_string(old_item.hgpd_id, new_item.hgpd_id) + `</td></tr>
																	<tr class="chil-tr"><td>RFID</td><td title="` + old_item.hgpd_rfid + `">` + fb_string_hide(old_item.hgpd_rfid) + `</td></tr>
																	<tr class="chil-tr"><td>` + (old_item.wic_process.includes("成形") ? '成形日' : '作業日') + `</td><td>` + old_item.hgpd_moldday + `</td></tr>
																	<tr class="chil-tr"><td>型番</td><td>` + old_item.hgpd_itemform + `</td></tr>
																	<tr class="chil-tr"><td>成形lot</td><td>` + old_item.hgpd_moldlot + `</td></tr>
																	<tr class="chil-tr"><td>キャビ</td><td>` + old_item.hgpd_cav + `</td></tr>
																	<tr class="chil-tr"><td>生産数</td><td class="used_num">` + old_item.hgpd_quantity + `</td></tr>
																	<tr class="chil-tr"><td>良品数</td><td class="good_num">` + old_item.hgpd_qtycomplete + `</td></tr>
																	<tr class="chil-tr"><td>不良数</td><td class="bad_num">` + old_item.hgpd_difactive + `</td></tr>
																	<tr class="chil-tr"><td>担当者</td><td>` + old_item.hgpd_name + `</td></tr>
																	` + (old_item.wic_process.includes("完成") ? '' : `
																	<tr class="chil-tr"><td>開始日時</td><td>` + old_item.hgpd_start_at + `</td></tr>
																	<tr class="chil-tr"><td>終了日時</td><td>` + old_item.hgpd_stop_at + `</td></tr>
																	<tr class="chil-tr"><td>生産時間</td><td>` + fb_diff_dhm(old_item.hgpd_start_at, old_item.hgpd_stop_at) + `</td></tr>`) + `
																</tbody>
														</table>`);
      if ('output' in input_item) {
        var var_inspection_result_qty = old_item.hgpd_qtycomplete;
        var html_table = `<table style='float:left; margin-top:8px;'>`;
        input_item.output.forEach(function(value, index) {
          if (parseInt(var_inspection_result_qty) > parseInt(value.hgpd_remaining)){
            var_inspection_result_qty = parseInt(value.hgpd_remaining);
          }
          html_table += `
          <thead style='margin-top:8px;'>
            <tr><th class="notright"><span class="ui-icon ui-icon-arrowthick-1-e"></span></th><th  colspan="4" class="` + fb_get_class_process(value.wic_process) + `">` + value.wic_process + `</th></tr>
          </thead>
          <tbody>
          <tr><td class="notright" rowspan="8"></td><td>在庫ID</td><td>` + value.wic_id + `</td><td>管理ID</td><td>` + value.hgpd_id + `</td></tr>
          <tr><td>RIFD</td><td colspan="3">` + fb_string_hide(value.hgpd_rfid) + `</td></tr>
          <tr><td>生産数</td><td colspan="3" class="used_num">` + value.hgpd_quantity + `</td></tr>
          <tr><td>良品数</td><td class="good_num">` + value.hgpd_qtycomplete + `</td><td>不良数</td><td class="bad_num">` + value.hgpd_difactive + `</td></tr>
          <tr><td>検査数</td><td>` + value.wic_inspection + `</td><td>残数</td><td class="re_num">` + value.hgpd_remaining + `</td></tr>
          <tr><td>開始日時</td><td colspan="3">` + value.hgpd_start_at + `</td></tr>
          <tr><td>終了日時</td><td colspan="3">` + value.hgpd_stop_at + `</td></tr>
          <tr><td>生産時間</td><td colspan="3">` + fb_diff_dhm(value.hgpd_start_at, value.hgpd_stop_at) + `</td></tr>
          <tr style="height: 1px;"><td class="noborder" colspan="5" style="height: 1px;"></td></tr>
          </tbody>`;
        });
      }
      html_table += `</table>`;
      if(fb_parseInt(old_item.wic_inspection) > 0){
        $('.RFID_view_data_2').append(`<br><p style="color:green;">　　欠損処理完了済</p>`);
      }else if (var_inspection_result_qty > 0) {
        $('.RFID_view_data_2').append(`<br><p style="color:red;"> 　　未処置残数:` + var_inspection_result_qty + ` </p>`);
      } else {
        $('.RFID_view_data_2').append(`<br><p style="color:green;">　　保留処理完了済</p>`);
      }
      $('.RFID_view_data_2').append(html_table);
		}
    function fb_alert_msg(item_msg) {
      $('.alert_msg').html(item_msg);
      if (item_msg != '') {
        $('.alert_msg').css('display', 'block');
      } else {
        $('.alert_msg').css('display', 'none');
      }
    }
    function fb_parseInt(item) {
      if (item === undefined|| item === null){
        return 0;
      }else if (item.length === 0) {
        return 0;
      } else {
        return parseInt(item);
      }
		}
    function fb_get_class_process(item) {
			if (!item){
				return 'RFID_view_5';
			}else if (item.includes("成形")) {
				return 'RFID_view_1';
			} else if (item.includes("最終")) {
				return 'RFID_view_2';
			} else if (item.includes("完成")) {
				return 'RFID_view_3';
			} else if (item.includes("保留")) {
				return 'RFID_view_4';
			} else {
				return 'RFID_view_5';
			}
		}
    function fb_string_hide(item) {
			var return_string = 'NALUX';
			return_string += item.substring(item.length - 8, item.length);
			return return_string;
		}

    function fb_get_string(item, item2) {
			if(item  == null) return "無し";
			if(item2 == null) return item;
			if(item == item2) return item;
			return item + "→" + item2;
		}

    function fb_is_cav_rfid_in_bug(in_bug_cav, in_rfid_cav){
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

    function fb_diff_dhm(fist_day, last_day) {
      var diff_value = '';
      const fist = new Date(fist_day);
      const last = new Date(last_day);
      const diffTime = Math.abs(last - fist);
      const diff_d = Math.floor(diffTime / (1000 * 60 * 60 * 24));
      const diff_h = Math.floor((diffTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const diff_m = Math.ceil(((diffTime % (1000 * 60 * 60 * 24)) % (1000 * 60 * 60)) / (1000 * 60));
      if (diff_d > 0) diff_value += diff_d + '日'
      if (diff_h > 0) diff_value += diff_h + '時'
      if (diff_m > 0) diff_value += diff_m + '分'
      return diff_value;
    }
  </script>
</head>

<body>
  <main>
    <div id="alert" style="margin: auto; text-align: center;">
      <div id="message" style="text-align: center;"></div>
    </div>
    <div class="col-md-8 bgc-6">
      <div class="col-md-6">
        <div class="col-md-12" style="padding: 5px  5px 0px 5px;">
          <div id="msgbox"></div>
          <form action="" method="post" name="msperson" enctype="multipart/form-data">
            <div class="bigclass  bgc-wh ">
              <div class=" smallclass wd-50" style="padding-top: 10px;">
                <h4>不具合連絡書</h4>
              </div>
              <div class="smallclass wd-30">
                <label for="documentnumber">資料番号</label>
                <input type="text" class="form-control hc_center" style="font-weight: bolder;" readonly id="documentnumber" value="<?php echo $idmax; ?>" />
                <input type="hidden" id="documentnumber_focus" />
              </div>
              <div class="smallclass wd-20">
                <label for="rank">ランク</label>
                <input class="form-control hc_center" min=0 max=9 id="rank" readonly />
              </div>
              <div class=" smallclass wd-60">
                <label for="productname">品名</label>
                <input type="text" class="form-control hc_center" id="productname" name="productname" list="品名" autocomplete="off" oninput="fb_auto_change(this)" style="ime-mode:disabled;" tabindex="1" required />
                <datalist id="品名">
                  <?php foreach ($d_item as $key => $item) { ?>
                    <option value="KEY<?php echo $key ?>"><?php echo '【' . $item['品目コード'] . '】' . $item['品名'] ?>
                    <?php } ?>
                </datalist>
              </div>
              <div class="smallclass wd-25">
                <label for="itemcode">品目コード</label>
                <input type="text" class="form-control hc_center" id="itemcode" name="itemcode" list="品目コード" autocomplete="off" oninput="fb_auto_change(this)" required />
                <datalist id="品目コード">
                  <?php foreach ($d_item as $key => $item) {
                  ?>
                    <option value="KEY<?php echo $key ?>"><?php echo $item['品目コード'] ?>
                    <?php
                  }
                    ?>
                </datalist>
              </div>
              <div class="smallclass wd-15">
                <label for="bu">BU</label>
                <input class="form-control hc_center" type='text' id="bu" name="bu" readonly />
              </div>
              <div class="smallclass wd-10">
                <label for="modelnumber">型番</label>
                <input class="form-control hc_center" title="number" min=0 max=99 id="modelnumber" name="modelnumber" list='form_list' autocomplete="off" tabindex="3" required />
                <datalist id="form_list"></datalist>
              </div>
              <div class="smallclass wd-20">
                <label for="lotid">成形Lot</label>
                <input class="form-control hc_center" title="number" id="lotid" name="lotid" list='lotid_list' autocomplete="off" tabindex="4" required />
                <datalist id="lotid_list"></datalist>
              </div>
              <div class="smallclass wd-30">
                <label for="cavino">キャビNo</label>
                <input type="text" class="form-control hc_center" id="cavino" name="cavino" title="複数の場合 「,」 を入力してください「例: 1,2,4,A」数字は半角、ローマ字は半角大文字で" tabindex="5" autocomplete="off" />
              </div>
              <div class="smallclass wd-20">
                <label for="lotvd">蒸着Lot</label>
                <input type="text" class="form-control hc_right" id="lotvd" name="lotvd" autocomplete="off" tabindex="6" />
              </div>
              <div class="smallclass wd-20">
                <label for="moldingdate">成形日</label>
                <input type="date" class="form-control hc_right" id="moldingdate" name="moldingdate" autocomplete="off" tabindex="7" required />
              </div>
              <div class="smallclass wd-100" style="margin-top:5px; padding:0">
                <div class="smallclass wd-50">
                  <label for="bugcontent">不具合内容</label>
                  <input type="text" class="form-control hc_left" id="bugcontent" name="bugcontent" autocomplete="off" tabindex="8" onfocus="fb_get_bug();" required />
                  <input type="hidden" id="project" name="project" />
                  <input type="hidden" id="classification" name="classification" />
                </div>
                <div class="smallclass wd-50">
                  <div class="smallclass wd-25">
                    <label for="numberoftargets">対象数</label>
                    <input title="number" class="form-control hc_right" id="numberoftargets" autocomplete="off" tabindex="9" readonly />
                  </div>
                  <div class="smallclass wd-25">
                    <label for="numberofinspections">検査数</label>
                    <input title="number" class="form-control hc_right" id="numberofinspections" autocomplete="off" name="numberofinspections" tabindex="10" />
                  </div>
                  <div class="smallclass wd-25">
                    <label for="adversenumber">不良数</label>
                    <input title="number" class="form-control hc_right" id="adversenumber" autocomplete="off" name="adversenumber" tabindex="11" />
                  </div>
                  <div class="smallclass wd-25">
                    <label for="rate">不良率</label>
                    <input type="text" class="form-control hc_right" id="rate" name="rate" readonly />
                  </div>
                </div>
              </div>

              <div class="smallclass wd-100">
                <div class="smallclass wd-50" style="overflow-x: hidden;">
                  <input style="display: none;" type="file" id="addimg" accept="image/png, image/jpeg, application/pdf, application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/comma-separated-values, text/csv, application/csv" multiple />
                  <textarea class="form-control" id="conditioncause" name="conditioncause" rows="2" placeholder="状態・原因" title="状態・原因" autocomplete="off" tabindex="12" style="margin-top:5px"></textarea>
                  <textarea class="form-control" id="evidence" rows="2" placeholder="OKの根拠 " title="OKの根拠" style="margin-top:5px" readonly></textarea>
                </div>
                <div class="smallclass wd-50">
                  <div class=" smallclass wd-50" style="line-height: 2;">
                    <div class="smallclass wd-100"><label>発見者</label></div>
                    <div class="smallclass wd-100" id="discoverer"></div>
                    <input type="hidden" id="personname" name="personname" />
                    <input type="hidden" id="usercord" name="usercord" />
                    <input type="hidden" id="gp1" name="gp1" />
                    <input type="hidden" id="gp2" name="gp2" />
                    <input type="hidden" id="作成日時" />
                  </div>
                  <div class="smallclass wd-50" style="margin: auto;line-height: 2;">
                    <label for="認可者">認可者</label>
                    <div class="smallclass wd-100" id="認可者"></div>
                  </div>
                </div>
                <div class="smallclass wd-100">
                  <div class=" smallclass wd-50" style="padding: 0px 2px 0px 0px">
                    <textarea class="form-control" id="demand" rows="2" placeholder="要望事項" title="要望事項" style="margin-top:5px" readonly></textarea>
                  </div>
                  <div class="smallclass wd-50">
                    <div class="smallclass wd-55" style="padding: 0px 5px 0px 2px">
                      <label for="processing_position">処理状態</label>
                      <input type="text" class="form-control hc_center name" id="processing_position" readonly>
                    </div>
                    <div class="smallclass wd-30" style="margin-top: 15px;">
                      <input type="button" id="bt_register" class="form-control hc_center " tabindex="100" onclick="fb_register(this);" value="登録"></input>
                    </div>
                    <div class="smallclass wd-15" style="margin-top: 15px; padding: 0px">
                      <input type="button" class="form-control hc_center vw06" tabindex="101" onclick="if (confirm('本当にクリアですか。') == true) {fb_reset_input();}" value="クリア"></input>
                      <!-- <input type="button" class="form-control hc_center " onclick="fromsubmit('register?placeid=1000073&menu=edit')" value="登録"></input> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-12" style="padding: 0px 5px 0px 5px;">
          <div class=" bigclass bgc-wh">
            <div class=" smallclass wd-30" style="padding-top: 0.5vw;">
              <h4>是正指示欄</h4>
            </div>
            <div class="smallclass radio wd-68" style="float:right;margin-right: 4px;">
              <label>修理依頼書発行</label><br>
              <input type="radio" id="repair_sheet_1" name="修理依頼書" value="設備・治工具" disabled />
              <label class="radio" for="repair_sheet_1" name="修理依頼書">設備・治工具</label>
              <input type="radio" id="repair_sheet_2" name="修理依頼書" value="金型" disabled />
              <label class="radio" for="repair_sheet_2" name="修理依頼書">金型</label>
              <input type="radio" id="repair_sheet_3" name="修理依頼書" value="不具合連絡書にて処理" disabled />
              <label class="radio" for="repair_sheet_3" name="修理依頼書">不具合連絡書にて処理</label>
            </div>
            <div class="smallclass wd-100" style="padding: 0;">
              <div class="smallclass wd-25">
                <label for="address">宛先</label>
                <input class="form-control hc_center name" type='text' id="address" readonly />
              </div>
              <div class="smallclass wd-25">
                <label for="deadline">在庫処置期限</label>
                <input class="form-control hc_right " type="text" id="deadline" readonly />
              </div>
              <div class="smallclass wd-25">
                <label for="receipt_dt">受理日</label>
                <input class="form-control hc_right " type="text" id="receipt_dt" readonly />
              </div>
              <div class="smallclass wd-25">
                <label for="due_dt">処置回答指示日</label>
                <input class="form-control hc_right" type='text' id="due_dt" readonly />
              </div>
            </div>
            <div class="smallclass wd-100" style=" float:left; padding: 4px; margin-top: 1px;">
              <div class="smallclass radio wd-30" style="margin:auto;" disabled>
                <label>欠点</label><br>
                <input type="radio" id="defect_type_1" name="欠点分類" value="重" />
                <label class="radio" for="defect_type_1" name="欠点分類">重</label>
                <input type="radio" id="defect_type_2" name="欠点分類" value="軽" />
                <label class="radio" for="defect_type_2" name="欠点分類">軽</label>
              </div>
              <div class="smallclass radio wd-68" style="float:right">
                <label>発行理由</label><br>
                <input type="radio" id="reason_1" name="発行理由" value="ロット不合格" disabled />
                <label class="radio" for="wbn_reason_1">ロット不合格</label>
                <input type="radio" id="reason_2" name="発行理由" value="選別不良" disabled />
                <label class="radio" for="wbn_reason_2">選別不良</label>
                <input type="radio" id="reason_3" name="発行理由" value="管理不良" disabled />
                <label class="radio" for="wbn_reason_3">管理不良</label>
                <input type="radio" id="reason_4" name="発行理由" value="品質上の警告" disabled />
                <label class="radio" for="reason_4">品質上の警告</label>
              </div>
            </div>
            <div class="smallclass wd-100" style="margin-top:1px">
              <textarea class="form-control" id="due_details" rows="2" placeholder="要望事項" title="要望事項" readonly></textarea>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="padding: 0px 5px 0px 5px;">
          <div class="bigclass  bgc-wh ">
            <div id='水平展開欄' class="smallclass wd- short" style="padding-left: 1.5vw; margin-top:15px">
              水平展開欄
            </div>
            <div class="smallclass wd-100" style="margin-top:10px; margin-bottom:40px">
              <table class="wd-100 wd7">
                <tr>
                  <td class="wd-40 hc_left">類似製品に対する水平展開</td>
                  <td class="wd-40 hc_left">類似プロセスに対する水平展開</td>
                  <td class="wd-40 hc_center">製造課長</td>
                </tr>
                <tr>
                  <td><input type="text" class="form-control " id="類似製品・不要" placeholder="不要" disabled></input></td>
                  <td><input type="text" class="form-control " id="類似製品・必要" placeholder="必要" disabled></input></td>
                  <td rowspan="2">
                    <input type="text" class="form-control hc_center name" id="製造課長" placeholder="未" disabled></input>
                    <input type="text" class="form-control hc_center " id="製造課長確認日時" disabled></input>
                  </td>
                </tr>
                <tr>
                  <td><input type="text" class="form-control " id="類似プロセス・不要" placeholder="不要" disabled></input></td>
                  <td><input type="text" class="form-control " id="類似プロセス・必要" placeholder="必要" disabled></input></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-12" style="padding: 5px  5px 0px 0px;">
          <div class="bigclass  bgc-wh ">
            <div class="smallclass wd-40" style="padding-top: 15px;">
              <div class="div_rfid_data"></div>
              <h4>在庫処置欄</h4>
            </div>
            <div class="smallclass wd-60">
              <textarea class="form-control" id="現品の内容確認" rows="2" placeholder="現品の内容確認（不適合内容に対する見解）\n※ 同意・相違,異議についてコメントを記載" title="現品の内容確認" readonly></textarea>
            </div>
            <div class="stock_confirmation smallclass wd-100" style="margin-top:5px">
              <table class="wd-100 wd7">
                <tr>
                  <td class="wd-16 hc_left">在庫確認</td>
                  <td class="wd-16 hc_center">対象数</td>
                  <td class="wd-16 hc_center">検査数</td>
                  <td class="wd-16 hc_center">不良数</td>
                  <td class="wd-16" rowspan="2">
                    数量管理部 <br>
                    門への連絡
                  </td>
                  <td class="wd-16 hc_center">発生部門係長</td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">完成品</td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td rowspan="3">
                    <input type="text" class="form-control hc_center name" placeholder="未" readonly></input>
                    <input type="text" class="form-control hc_center " readonly></input>
                  </td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">仕掛品</td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td rowspan="2">
                    <input type="text" class="form-control hc_center name" placeholder="未" readonly></input>
                    <input type="text" class="form-control hc_center " readonly></input>
                  </td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">蒸着品</td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                </tr>
                <tr>
                  <td class="wd-16 hc_left">仕掛品(P)</td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td><input type="text" class="form-control hc_right" readonly></input></td>
                  <td colspan="2">
                    <input type="text" class="form-control hc_left" style="font-size: 0.6vw;" readonly />
                  </td>
                </tr>
              </table>
            </div>
            <input type="hidden" id="発生部門係長" />
            <input type="hidden" id="発生部門日時" />
            <input type="hidden" id="数量管理者" />
            <input type="hidden" id="数量管理日時" />
            <div class="smallclass wd-75" style="margin-top:5px">
              <div class="smallclass radio wd-100">
                <label>在庫品の処置(現品についても処置を行うこと)</label><br>
                <input type="radio" id="treatment_1" name="処置内容" value="廃棄" disabled>
                <label class="radio" for="treatment_1">廃棄</label>
                <input type="radio" id="treatment_2" name="処置内容" value="特採(修理あり)" disabled>
                <label class="radio" for="treatment_2">特採(修理あり)</label>
                <input type="radio" id="treatment_3" name="処置内容" value="特採(修理なし)" disabled>
                <label class="radio" for="treatment_3">特採(修理なし)</label>
                <input type="radio" id="treatment_4" name="処置内容" value="選別" disabled>
                <label class="radio" for="treatment_4">選別</label>
                <input type="radio" id="treatment_5" name="処置内容" value="手直し" disabled>
                <label class="radio" for="treatment_5">手直し</label>
                <input type="radio" id="treatment_6" name="処置内容" value="その他" disabled>
                <label class="radio" for="treatment_6">その他</label>
              </div>
              <div class="smallclass wd-100" style="margin-top: 5px; padding: 0px;">
                <textarea class="form-control" id="手直し" rows="2" placeholder="手直し（修理）の場合は処置内容を記入　\n ※ 手直し指示書を作成する事" title="手直し（修理）の場合は処置内容を記入" readonly></textarea>
              </div>
            </div>
            <div class="smallclass wd-25">
              <div class="smallclass wd-100"><label for="処置担当者">処置担当者</label></div>
              <div class="smallclass wd-100" id="処置担当者"></div>
              <!-- <input type="text" class="form-control hc_center " id="処置完了日時" disabled></input> -->
            </div>
            <div class="smallclass wd-100">
              <div class="smallclass wd-75" style="margin-top: 5px; padding: 0px;">
                <textarea class="form-control" id="在庫処置" rows="2" placeholder="在庫処置のみ及び対象在庫確定の根拠 \n（発生部門課長が判断可 右端欄にチェック、対策回答欄以下は記入不要）" title="在庫処置のみ及び対象在庫確定の根拠" readonly></textarea>
              </div>
              <div class="smallclass wd-25" style="margin-top:25px ">
                <input type="radio" id="treatment_only" name="処置のみ" value="処置のみ" disabled>
                <label class="radio" for="treatment_only">処置のみ</label><br>
              </div>
            </div>
          </div>
        </div>
        <div class=" col-md-12" style="padding: 0px 5px 5px 0px;">
          <div class="bigclass bgc-wh">
            <div class="smallclass wd-30" style="padding-top: 5px; ">
              <h4>是正処置欄</h4>
            </div>
            <div class="smallclass wd-45 vw06" style="text-align: left; ">
              ※暫定対策がある場合も記入 <br>
              ※本対策書に書ききれないときは別紙を添付の事。
            </div>
            <div class="smallclass wd-25" style="padding-top: 5px; ">
              <h4>検証結果欄</h4>
            </div>
            <table class="smallclass wd-100">
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
                <td style="text-align: center;">発生</td>
                <td style="vertical-align: top;">
                  <textarea class="form-control 発生流出" style="height: 98px;" id="発生・原因" rows="4" placeholder="" readonly></textarea>
                </td>
                <td style="vertical-align: top;">
                  <textarea class="form-control 発生流出" style="height: 98px;" id="発生・対策" rows="4" placeholder="" readonly></textarea>
                </td>
                <td rowspan="2" style="line-height: 1.1; padding:2px; vertical-align: top;">
                  <div style=" padding-top:2px;">
                    <input type="radio" id="restoration" name="復旧確認で可" value="復旧確認で可" disabled>
                    <label class="radio" for="restoration" class="vw06">復旧確認で可</label><br>
                    <input type="radio" id="confirmation" name="長期確認が必要" value="長期確認が必要" disabled>
                    <label class="radio" for="confirmation" class="vw06">長期確認が必要</label><br>
                  </div>
                  <div class="smallclass wd-100" style=" padding: 0px;">
                    <div class="smallclass wd-35" style=" padding:0px 1px 0px 0px;">
                      <label class="vw06">検査数</label>
                      <input type="text" id="wbn_inspection_total" class="form-control hc_right" style="font-size: 0.6vw;height: 1.4vw;" readonly />
                    </div>
                    <div class="smallclass wd-30" style=" padding:0px 1px 0px 0px;">
                      <label class="vw06">不良数</label>
                      <input type="text" id="wbn_inspection_bad" class="form-control hc_right" style="font-size: 0.6vw;height: 1.4vw;" readonly />
                    </div>
                    <div class="smallclass wd-35" style=" padding:0px;">
                      <label class="vw06">不良率</label>
                      <input type="text" id="wbn_inspection_rate" class="form-control hc_right" style="font-size: 0.6vw;height: 1.4vw;" readonly />
                    </div>
                  </div>
                  <div style="padding:2px 0px 2px 10px;">
                    <label class="radio vw06" for="radio_19">効果</label>
                    <input type="radio" id="radio_19" name="効果" value="効果あり" disabled />
                    <label class="radio vw06" for="radio_19">あり</label>
                    <input type="radio" id="radio_20" name="効果" value="効果なし" disabled />
                    <label class="radio vw06" for="radio_20">なし</label>
                    <br>
                  </div>
                  <label class="radio vw06">・効果なしのコメント</label>
                  <input type="text" class="form-control vw06" id="effect_NG_msg" style="height: 1.4vw;" readonly>
                  <label class="radio vw06">・再発行・資料No</label>
                  <input type="text" class="form-control" id="reissue_id" style="height: 1.4vw;" readonly>
                </td>
              </tr>
              <tr>
                <td style="text-align: center;">流出</td>
                <td style="vertical-align: top;">
                  <textarea class="form-control 発生流出" style="height: 98px;" id="流出・原因" rows="4" placeholder="" readonly></textarea>
                </td>
                <td style="vertical-align: top;">
                  <textarea class="form-control 発生流出" style="height: 98px;" id="流出・対策" rows="4" placeholder="" readonly></textarea>
                </td>
              </tr>
              <tr>
                <td style="text-align: center;">確認</td>
                <td colspan="2">
                  <div class="smallclass wd-5 vw06 " style="border: 0.5px dotted">対策部門
                  </div>
                  <div class="smallclass wd-30">
                    <input type="text" class="form-control hc_center name" id="対策部門者" disabled></input>
                    <input type="text" class="form-control hc_center " id="対策部門確認日時" disabled></input>
                  </div>
                  <div class="smallclass wd-5 vw06" style="border: 0.5px dotted">作成部門
                  </div>
                  <div class="smallclass wd-30">
                    <input type="text" class="form-control hc_center name" id="作成部門者" disabled></input>
                    <input type="text" class="form-control hc_center " id="作成部門確認日時" disabled></input>
                  </div>
                  <div class="smallclass wd-5 vw06" style="border: 0.5px dotted">品管部門
                  </div>
                  <div class="smallclass wd-25" id="品管部門者"></div>
                  <input type="hidden" id="quality_control_person" />
                </td>
                <td>
                  <div class="smallclass wd-100 vw06" id="quality_control_comment">コメントがある場合記載</div>
                </td>
              </tr>
            </table>
            <table id="corrective_table" class="wd-100 vw05 hc_center">
              <tr>
                <td colspan="3">＊標準類改訂</td>
                <td>有 / 無</td>
                <td colspan="8">有の場合は標準類Noを記入する事</td>
                <td>製造課長</td>
              </tr>
              <tr>
                <td class="wd-10">ＱＣ工程表</td>
                <td class="wd-2-5"><label for="有" name="ＱＣ工程表" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="ＱＣ工程表" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">条　件　表</td>
                <td class="wd-2-5"><label for="有" name="条　件　表" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="条　件　表" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">CP</td>
                <td class="wd-2-5"><label for="有" name="CP" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="CP" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10" rowspan="4"></td>
              </tr>
              <tr>
                <td class="wd-10">検査基準書</td>
                <td class="wd-2-5"><label for="有" name="検査基準書" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="検査基準書" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">成形ﾁｪｯｸＰ</td>
                <td class="wd-2-5"><label for="有" name="成形ﾁｪｯｸＰ" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="成形ﾁｪｯｸＰ" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">ＦＭＥＡ</td>
                <td class="wd-2-5"><label for="有" name="ＦＭＥＡ" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="ＦＭＥＡ" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10"></td>
              </tr>
              <tr>
                <td class="wd-10">作業手順書</td>
                <td class="wd-2-5"><label for="有" name="作業手順書" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="作業手順書" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">仕上ﾁｪｯｸＰ</td>
                <td class="wd-2-5"><label for="有" name="仕上ﾁｪｯｸＰ" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="仕上ﾁｪｯｸＰ" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">( )</td>
                <td class="wd-2-5"><label for="有" name="(    )" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="(    )" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10"></td>
              </tr>
              <tr>
                <td class="wd-10">限度見本</td>
                <td class="wd-2-5"><label for="有" name="限度見本" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="限度見本" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">( )</td>
                <td class="wd-2-5"><label for="有" name="(    )" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="(    )" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10">( )</td>
                <td class="wd-2-5"><label for="有" name="(    )" class="vw04">有</label></td>
                <td class="wd-2-5"><label for="無" name="(    )" class="vw04">無</label></td>
                <td class="wd-15"></td>
                <td class="wd-10"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div id="grid"></div>
      <div id="image" class="wd-100" style="text-align: center;">
        <div class="toolbar" id="bt_add_img" style="text-align: left; display:none;">
          <input type="button" onclick="{bt_add_img_click = true; $('#addimg').click();}" style="width: 100%;" class="form-control hc_center " value="添付追加" />
          <center>
            <spam id="file_information" style="width: 100%;"></spam>
          </center>
        </div>
        <div id="imgbug" style="margin-left:15%; overflow-y:scroll;">
        </div>
      </div>
    </div>
  </main>
  <div id="msg_view"></div>
  <div id="alert_dept" style="text-align: center; font-size: 16px;"></div>
  <script>
    // function fromsubmit(str) {
    //   if (!$('#person').html()) {
    //     fb_get_user();
    //     return;
    //   }
    //   if (fb_input_check()) {
    //     document.msperson.action = str;
    //     document.msperson.submit();
    //   }
    // }

    function set_radio(name, value) {
      if (isNaN(value)) {
        $('input[name="' + name + '"]').filter('[value="' + value + '"]').attr('checked', true);
        $('input[name="' + name + '"]').filter('[value="' + value + '"]').attr('disabled', false);
      }
    }
    $("#addimg").change(function() {
      if ($("#addimg").val() != '' && bt_add_img_click == true) {
        bt_add_img_click = false;
        if (confirm('本当に追加ですか。') == true) {
          fb_file_select(this);
          if (fb_add_image('add', $('#documentnumber').val()) == false) {
            alert("添付追加失敗 (´；ω；`)ｳｯ…");
          }
        }
        return;
      }
      fb_file_select(this);
    });

    function fb_add_image(menu, id) {
      $.ajax({
        url: 'Upload?menu=' + menu + '&wbn_id=' + id + '&module=MissingDefect&placeid=' + placeid + '&count=' + Array.from(form_data.keys()).length,
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        success: function(d) {
          if (d == 'OK') {
            $("#documentnumber_focus").val("");
            form_data = new FormData();
            documentid = id;
            fb_viewtable();
            return true;
          } else {
            return false;
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          return false;
        }
      });
    }

    function fb_del_img(mfc_id) {
      var datas = {
        ac: "del_img",
        placeid: placeid,
        mfc_id: mfc_id,
      }
      //fb_loading(true);
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'text',
        data: datas,
        success: function(d) {
          if (d == '___OK___') {
            $("div").remove(".file_img_" + mfc_id);
            $("#file_information").html("");
          } else {
            alert('削除失敗(´；ω；`)ｳｯ…')
          }
          return;
          // fb_loading(false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          // fb_loading(false);
          return;
        }
      });
    }

    function fb_file_select(inputElement) {
      // ファイルリストを取得
      var fileList = inputElement.files;
      // ファイルの数を取得
      var fileCount = fileList.length;
      let count_img = 0;
      let count_noimg = 0;
      let file_order = 0;
      form_data = new FormData();
      // HTML文字列の生成
      if (!bt_add_img_click) {
        $("#imgbug").html("");
      }
      // 選択されたファイルの数だけ処理する
      for (let i = 0; i < fileCount; i++) {

        // ファイルを取得
        let file = fileList[i];
        form_data.append('file' + i, file);
        let reader = new FileReader();
        reader.readAsDataURL(file);
        if (file.type.indexOf("image") > -1) {
          count_img++;
          reader.onload = function() {
            file_order++;
            $("#imgbug").append(`<img class="file_update_` + i + `" style="max-width:100%; max-height:` + screen_height * 2 / 7 + `px; margin-bottom: 5px;" src="` + reader.result + `"/>　`);
          }
        } else {
          count_noimg++;
          reader.onload = function() {
            file_order++;
            $("#imgbug").append(` <div style="width:100%; float:left; margin-bottom: 5px;">
                                      <div class="wd-20" style="float:left;">　</div>
                                      <div class="wd-60 file_update_` + i + `" style="float:left;"> <a style="float:left;">添付` + file_order + `: ` + file.name + `</a></div>
                                      <div class="wd-20" style="float:left;"></div>
                                    </div>`);
          }
        }
      }
      if (!bt_add_img_click) {
        $("#imgbug").append(`<div class="toolbar" style="text-align: left; width: 15%; ">
                              <input type="button" onclick="{form_data = new FormData(); $('#imgbug').html('');}" style="width: 100%;" class="form-control hc_center btn_del" value="全添付削除" />
                              <center>
                                <spam style="font-weight: bold;">添付： ` + fileCount + `件</spam><br/>画像： ` + count_img + `件<br/>他の： ` + count_noimg + `件
                              <center>
                            </div>`);
      }
      //結果のHTMLを流し込む
    }

    function fb_areadURL(input) {
      if (input.files && input.files[0]) {
        var file_data = input.files[0];
        form_data.append('file', file_data);
        var reader = new FileReader();
        let type = file_data.type.split("/")[0];
        reader.onload = function(e) {
          if (type == 'image') {
            $("#imgbug").html('<img style="max-width:100%;max-height:' + screen_height * 2 / 7 + 'px" src="' + e.target.result + '">');
          } else {
            $("#imgbug").html('<lable>添付ファイル名： ' + file_data.name + '</lable>');
          }
        }
        reader.readAsDataURL(file_data);
      }
    }

    var textAreas = document.getElementsByTagName('textarea');
    Array.prototype.forEach.call(textAreas, function(elem) {
      elem.placeholder = elem.placeholder.replace(/\\n/g, '\n');
      elem.value = elem.value.replace(/\\n/g, '\n');
    });

    function fb_set_hakko(div_name, name, date, judgement = "", hakko_num = "") {
      let div_hakko;
      if (!name || name == '') {
        div_hakko = ` <input type="text" class="form-control hc_center name" placeholder="未" disabled></input>
                      <input type="text" class="form-control hc_center " disabled></input>`;
      } else {
        let dept_len = name.length;
        let font_size = 0.7;
        if (hakko_num == 2) {
          if (dept_len > 6 && dept_len < 13) {
            font_size = 0.4;
          } else if (dept_len > 12) {
            font_size = 0.3;
          } else {
            font_size = 0.6;
          }
        } else {
          if (dept_len > 6 && dept_len < 13) {
            font_size = 0.5;
          } else if (dept_len > 12) {
            font_size = 0.4;
          }
        }
        if (judgement != "") {
          div_hakko = '<div class="hanko' + hakko_num + '" style="line-height: 1.7;"><span style="font-size: ' + font_size + 'vw;padding-top: 10px;"> ' + name + ' </span><hr noshade ><span> ' + date + ' </span><hr noshade style="width: 90%;"><span> ' + judgement + ' </span></div>';
        } else {
          div_hakko = '<div class="hanko' + hakko_num + '" style="line-height: 1.7;"><span style="font-size: ' + font_size + 'vw;"> ' + name + ' </span><hr noshade><span> ' + date + ' </span></div>';
        }
      }
      $("#" + div_name).html(div_hakko);
    }

    function fb_get_hakko(name, date, hakko_num = "") {
      let div_hakko;
      if (!name || name == '') {
        div_hakko = ` <input type="text" class="form-control hc_center name" placeholder="未" disabled></input>
                      <input type="text" class="form-control hc_center " disabled></input>`;
      } else {
        let dept_len = name.length;
        let font_size = 0.7;
        if (hakko_num == 2) {
          if (dept_len > 6 && dept_len < 13) {
            font_size = 0.4;
          } else if (dept_len > 12) {
            font_size = 0.3;
          } else {
            font_size = 0.6;
          }
        } else {
          if (dept_len > 6 && dept_len < 13) {
            font_size = 0.5;
          } else if (dept_len > 12) {
            font_size = 0.4;
          }
        }
        div_hakko = '<div class="hanko' + hakko_num + '" style="line-height: 1.7;"><span style="font-size: ' + font_size + 'vw; "> ' + name + ' </span><hr noshade><span> ' + date + ' </span></div>';
      }
      return div_hakko;
    }

    function fb_get_hakko2(name, date, style = "", situation = 9) {
      let div_hakko = '';
      if (!name || name == '') {
        div_hakko = '<p style="color: Gray;">未</p>';
      } else {
        let dept_len = name.length;
        let font_size = 12;
        if (dept_len > 10) {
          font_size = 8;
        } else if (dept_len > 8) {
          font_size = 9;
        } else if (dept_len > 6) {
          font_size = 10;
        } else if (dept_len > 5) {
          font_size = 11;
        }
        if (situation == "OK" || situation == "NG" || situation == "?") {
          div_hakko = '<div class="hanko"><span style="font-size: ' + font_size + 'px; padding-top: 10px;"> ' + name + ' </span><hr noshade><span> ' + date + ' </span><hr noshade  style="width: 95%;"><span> ' + situation + ' </span></div>';
        } else {
          div_hakko = '<div class="hanko" style="' + style + '"><span style="font-size: ' + font_size + 'px;"> ' + name + ' </span><hr noshade><span> ' + date + ' </span></div>';
        }
      }
      if (situation <= 3) {
        div_hakko = '<p style="color: Gray;">未</p>';
      }
      return div_hakko;
    }

    function fb_set_hakko2(name, date, style = "", situation = 9) {
      let div_hakko = '';
      if (!name || name == '') {
        div_hakko = '<p style="color: Gray;">未</p>';
      } else {
        let dept_len = name.length;
        let font_size = 12;
        if (dept_len > 6 && dept_len < 9) {
          font_size = 10;
        } else if (dept_len > 8) {
          font_size = 8;
        }
        if (!isNaN(situation)) {
          div_hakko = '<div class="hanko" style="' + style + '"><span style="font-size: ' + font_size + 'px;"> ' + name + ' </span><hr noshade><span> ' + date + ' </span></div>';
        } else {
          div_hakko = '<div class="hanko" style="' + style + '"><span style="font-size: ' + font_size + 'px; padding-top: 10px;"> ' + name + ' </span><hr noshade><span> ' + date + ' </span><hr noshade  style="width: 95%;"><span> ' + situation + ' </span></div>';
        }
      }
      if (situation == 3) {
        div_hakko = '<p style="color: Gray;">未</p>';
      }
      if (situation < 3) {
        div_hakko = '<p style="color: Gray;">未</p>';
      }
      return div_hakko;
    }

  </script>
</body>

</html>
