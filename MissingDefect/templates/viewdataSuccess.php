<?php

if (!$placeid) {

  slot('h1', '<h1> 稼働状態配置図 | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/MissingDefect/viewdata?placeid=1000079">野洲工場</a>
      <a class="blue" href="/MissingDefect/viewdata?placeid=1000073">山崎工場</a>
      <a class="blue" href="/MissingDefect/viewdata?placeid=1000125">NPG</a>
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
        <div id="h_sel" style="float:left;margin-top:1px;" >';
$btn .= '
        <input class="selgroups" type="date" name="start_sel" id="start_sel" style="float:left;  margin-left:5px;" placeholder="開始発行日" autocomplete="off" value="'.date("Y-m-d", strtotime("-6 months")).'">
        <label  style="float:left;" for="end_sel">~</label>
        <input class="selgroups" type="date" name="end_sel" id="end_sel" style="float:left;" placeholder="終了発行日" autocomplete="off" value="'.date("Y-m-d").'">
        <input class="selgroups" type="text" name="documentid_sel" id="documentid_sel" style="float:left; margin-left:5px;" placeholder="資料番号" list="idList" title="番号のみ検査可能" autocomplete="off">
        <datalist id="idList">';
foreach ($idlist as $item) {
  $btn .= "<option value='" . $item['wbn_id'] . "'>\n";
}
$btn .= '</datalist>
        <input class="selgroups" type="text" id="sel_colname" style="float:left; margin-left:5px; margin-right:5px;" placeholder="テーブル列名へ移動" list="colname_list" oninput="fb_sel_col(this.value);" autocomplete="off">
        <datalist id="colname_list"></datalist>
        <button type="button" class="btn_def" onclick="fb_viewtable();"> <img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1838"/>検索 </button>
        <button type="button" class="btn_def" onclick="fb_download();"> <img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1840"/> ロード </button>
        </div>

        <button style="float:right;" type="button" class="btn_def" onclick="window.close();return false;">閉じる</button>
        <button style="float:right; display: none;" type="button" class="btn_def errorview" id= "btn_error_view" onclick="fb_error_view();"> <i aria-hidden="true"></i> 数量エラー </button>
        <label id="conf_msg"></label>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>

<html>

<head>
  <style type="text/css">
 body,label {font-size: 0.8vw;}
    .form-control {font-size: 0.8vw;}
    .selgroups {height: 28px;}
    h4 {font-size: 1.3vw;}
    .ui-button-text {font-size: 16px; margin: 0.1px; padding: 2px 4px 2px 4px !important;}
    button.btn_def:hover {color: orange; cursor: pointer;}
    div.bigclass {float: left; padding: 5px; width: 100%; border-radius: 5px; margin-bottom: 5px;}
    div.smallclass {float: left; padding-right: 5px; text-align: center;}
    .handsontable td,.handsontable tr,.handsontable th {max-width: 20px; min-width: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap !important;}
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
    div.radio {padding: 0px 20px 0px 20px; border-radius: 5px; background: #DDDDDD;}
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
    .htCore tbody tr:nth-child(even) td {background-color: lightyellow;}
    .in-wrapper .htCore tbody tr:nth-child(odd) td {background-color: white;}
    table,tr,th,td {border: 1px solid white;}
    #loading {width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center; background-repeat: no-repeat; background-attachment: fixed;}
    #loading img {max-width: 100%; height: auto;}
    .ui-button,.ui-button-text .ui-button {font-size: 12px !important;}
    .bc_nc {background-color: #000;}
    .bc_ch {background-color: #eee;}
    .hc_left {text-align: left; padding: 0px 0px 0px 5px;}
    .hc_right {text-align: right; padding: 0px 5px 0px 0px;}
    .hc_center {text-align: center; padding: 0px;}
    .menu {font-weight: bold; text-align: center; width: 20px; height: 20px; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px;     }
    .col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12 {float: left; padding: 0px;}
    #image {padding: 0; text-align: center; position: absolute;}
    .toolbar {visibility: visible; padding-top: 0.4vw; top: 0px; right: 0px; position: absolute; width: 100%;}
    #msgbox {position: absolute;     }
    #msgbox.form-validation-field-1formError:active {display: none;}
    input.form-control {height: 30px;}
    input[type='radio'] {width: 0.6vw; height: 0.6vw;}
    input.small {width: 0.6vw; height: 0.6vw;}
    input.big {width: 0.8vw; height: 0.8vw;}
    input[type=button]:hover {background-color: white; color: #04AA6D;}
    input:hover,textarea:hover {background-color: #FFFF99;}
    textarea {background-color: white;}
    .vw08 {font-size: 0.8vw;}
    .vw07 {font-size: 0.7vw;}
    .vw06 {font-size: 0.6vw;}
    .vw05 {font-size: 0.5vw;}
    .vw04 {font-size: 0.4vw;}
    .pl5 {padding-left: 5px;}
    .pr10 {padding-right: 10px;}
    .name {font-size: 0.9vw; font-weight: bolder;}
    input[type=button],input[type=submit],input[type=reset] {background-color: #04AA6D; border: none; color: white; text-decoration: none; cursor: pointer;}
    .hide {display: none; height: 0.8vw;}
    .show {display: block; height: 0.8vw;}
    .form-control::placeholder {color: #BBBBBB;}
    .form-control:-ms-input-placeholder {color: #BBBBBB;}
    .form-control::-ms-input-placeholder {color: #BBBBBB;}
    .short {font-size: 1.1vw; margin-top: 0.4vw; margin-bottom: 0.4vw;}
    .long {font-size: 1.1vw; margin-top: 0.4vw; margin-bottom: 0.4vw;}
    #msg_view {background-color: darkgrey; border-radius: 20px; display: none; position: fixed; padding: 10px; width: 33.3%;}
    @-webkit-keyframes glowing {0% {-webkit-box-shadow: 0 0 3px #004A7F;} 50% {-webkit-box-shadow: 0 0 20px red;} 100% {-webkit-box-shadow: 0 0 3px #004A7F; }}
    @-moz-keyframes glowing {0% {-moz-box-shadow: 0 0 3px #004A7F;} 50% {-moz-box-shadow: 0 0 20px red;} 100% {-moz-box-shadow: 0 0 3px #004A7F;}}    
    @-o-keyframes glowing {0% {  box-shadow: 0 0 3px #004A7F; }50% {  box-shadow: 0 0 20px red; }100% {  box-shadow: 0 0 3px #004A7F; }}
    @keyframes glowing {0% {  box-shadow: 0 0 3px #004A7F; }50% {  box-shadow: 0 0 20px red; }100% {  box-shadow: 0 0 3px #004A7F; }}
    .errorview {-webkit-animation: glowing 1500ms infinite; -moz-animation: glowing 1500ms infinite; -o-animation: glowing 1500ms infinite; animation: glowing 1500ms infinite;}
    .icon_button{width: 1vw;height: 1vw;}
  </style>
  <script type="text/javascript">
    var placeid = '<?php echo $placeid; ?>';
    var screen_height = screen.height - screen.height * 0.17;
    var today = fb_format_date(new Date(), "YYYY-MM-DD");
    var select_row = 0;
    var set_colname = false;
    var select_id = '<?php echo $documentid; ?>';
    var data_error;
    $(document).ready(function() {
      $("button").button();
      fb_viewtable();
      $("#start_sel").focusin(function() {
        $("#start_sel").val('');
      });
      $("#end_sel").focusin(function() {
        $("#end_sel").val('');
      });
      $("#sel_colname").focusin(function() {
        $("#sel_colname").val('');
      });
      $("#documentid_sel").focusin(function() {
        $("#documentid_sel").val('');
      });
      $("#grid").dblclick(function() {
        window.open('/MissingDefect/register?placeid=' + placeid + '&documentid=' + select_id, '_blank');
      });
    });

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

    function get_placelist(dataAry, place) {
      var result = '';
      dataAry.forEach((value, key) => {
        if (value.value.indexOf(place) > -1) {
          result = value.m_locator_id + "," + value.value + "," + value.x;
        }
      });
      return result;
    }

    function fb_viewtable() {
      var start_sel = '1900-01-01';
      var end_sel = '2999-01-01';
      data_error = {};
      if ($('#start_sel').val()) {
        start_sel = $('#start_sel').val();
      }
      if ($('#end_sel').val()) {
        end_sel = $('#end_sel').val();
      }
      var sPlant = "<?= $plant_name; ?>";
      if (end_sel < start_sel) {
        alert('開始日時は終了日時より小さいです。');
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
        documentid_sel: $('#documentid_sel').val(),
      }
      fb_loading(true);
      $.ajax({
        type: 'GET',
        url: "/MissingDefect/register?placeid=" + placeid,
        dataType: 'json',
        data: datas,
        success: function(d) {
          data_error = d.filter((v) => v[94] === "error")
          if (data_error.length > 0) {
            $("#btn_error_view").css('display', 'block');
          } else {
            $("#btn_error_view").css('display', 'none');
          }
          table_set(d);
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

    function table_set(data) {
      h_num = 32;
      var grid = document.getElementById('grid');
      var columnsdata=[{'title':'指示書','readOnly':true,renderer:"html",className:'htCenterhtMiddle',width:50},
        {'title':'資料番号','readOnly':true,type:'numeric',className:'htNoWrap',width:100},
        {'title':'処理状態','readOnly':true,type:'text',className:'htCenter',width:150},
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
        {'title':'BU','readOnly':true,type:'text',className:'htCenter',width:60},
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
        {'title':'発生.流出入力者','readOnly':true,type:'text',className:'htCenter',width:120},
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
        {'title':'メッセージ','readOnly':true,type:'text',className:'htCenter',width:300}];
      var ww = $(window).width() - 5;
      var wh = $(window).height() - h_num;
      var afterselect;
      var olval;
      if (table != undefined) {
        table.destroy();
      }
      table = new Handsontable(grid, {
        data: data,
        startRows: 5,
        startCols: 5,
        height: wh,
        width: ww,
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
        language: 'ja-jp',
        exportFile: true,
        fixedColumnsLeft: 8,
        autoRowSize: false,
        rowHeight: function(row) {
          return 50;
        },
        defaultRowHeight: 50,
        hiddenColumns: {
          columns: [11],
          indicators: true
        },
        dropdownMenu: ['make_read_only', 'alignment', 'filter_by_condition', 'filter_action_bar', 'filter_by_value'],
        cells(row, col, prop) {
          if (data) {
            if (data[row][94] == "error" && col > 0) {
              this.renderer = RedRenderer;
            }
          }
        },
        afterDeselect: function() {
          var cols = table.countCols();
          var rows = table.countRows();
          $("#conf_msg").text("行数: " + rows + "件");
        },
        afterSelection: function(r, c, r2, c2, p, s) {
          if (c == 0) {
            var datanow = table.getData();
            fb_load_pdf(datanow[r][fb_get_col_num('資料番号')], datanow[r][fb_get_col_num('発見日')], datanow[r][fb_get_col_num('品名')], datanow[r][fb_get_col_num('項目')], datanow[r][fb_get_col_num('型番')]);
          }
          select_row = r;
          select_id = table.getData()[r][fb_get_col_num('資料番号')];
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
        }
      });
      if (!set_colname) {
        set_colname = fb_get_colname();
      }
      var datanow = table.getData();
      var rows = table.countRows();
      if (rows > 0) {
        for (i = 0; i < rows; i++) {
          if (datanow[i][fb_get_col_num('資料番号')] == select_id) {
            table.selectCell(i, 1, i, 1);
            break;
          }
        }
      }
      $("#conf_msg").text("データの個数: " + datanow.length + "件");
    }
    const RedRenderer = function(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.color = 'red';
    };

    function fb_error_view() {
      table_set(data_error);
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

    function getNumColHeader(titleHeader) {
      var getNum = -1
      const ColHeader = table.getColHeader();
      ColHeader.forEach((element, index) => {
        if (element === titleHeader) {
          getNum = index;
        }
      });
      return getNum
    }

    function fb_get_col_num(titleHeader) {
      var getNum = -1
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
        table.selectCell(select_row, fb_get_col_num(col_name) + 5, select_row, fb_get_col_num(col_name) + 5);
        table.selectCell(select_row, fb_get_col_num(col_name) - 5, select_row, fb_get_col_num(col_name) - 5);
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

    function fb_loading(flag) {
      $('#loading').remove();
      if (!flag) return;
      $('<div id="loading" />').appendTo('body');
    }

    function fb_entry(item) {
      let msg_bugconten = '';
      let bugnum = 0;
      dbug_array.forEach((value, key) => {
        if (parseInt($("#bug_" + value.num).val()) > 0) {
          msg_bugconten += value.item + '=>' + $("#bug_" + value.num).val() + ',';
          bugnum += parseInt($("#bug_" + value.num).val());
        }
      });
      if (msg_bugconten.length > 0) {
        msg_bugconten = msg_bugconten.substring(0, msg_bugconten.length - 1)
      }
      item.value = bugnum;
      msgbug_add(item.id, msg_bugconten);
      $('#' + item.id + '_msg').val(msg_bugconten);
    }

    function fb_load_pdf(wbn_id, created_at, wbn_product_name, wbn_defect_item, wbn_kataban) {
      var datas = {
        ac: "GetJsonXWR",
        wbn_id: wbn_id,
        placeid: placeid,
      }
      fb_loading(true);
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          if (Object.keys(d).length > 0) {
            fb_print_pdf(wbn_id, created_at, wbn_product_name, wbn_defect_item, wbn_kataban, d);
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

    function fb_print_pdf(wbn_id, created_at, wbn_product_name, wbn_defect_item, wbn_kataban, obj_data) {
      let sum_html = '';
      obj_data.forEach((value, index) => {
        // if (value['wbn_cavino'].indexOf(",") > 0) {
        //   value['wip_numberoftargets'] = "";
        //   value['wip_numberoftargets_P'] = "";
        //   value['vd_numberoftargets'] = "";
        //   value['fh_numberoftargets'] = "";
        // }
        let print_html = `<table class="rv"> <tr> <td rowspan="2" colspan="7"> <span class="head"> 製品隔離指示書 </span> </td> <td colspan="4"><br></td> </tr> <tr> <td class="rv" colspan="2"> NO: </td> <td colspan="2"> <br> </td> </tr> <tr> <td class="rv" colspan="2">発行日</td> <td class="rv" colspan="3">` + created_at + `</td> <td class="rv" colspan="2">不具合発行No</td> <td class="rv" colspan="4"><span class="thick">` + wbn_id + `</span></td> <tr> <td class="rv" colspan="2">品名</td> <td class="rv" colspan="3"><input type="text" value="` + wbn_product_name + `" ></input></td> <td class="rv" colspan="2">型番</td> <td class="rv" colspan="4">` + wbn_kataban + `</td> </tr> <tr> <td class="rv" colspan="2">発生現品札No</td> <td class="rv" colspan="3"></td> <td class="rv" colspan="2">対象cav</td> <td class="rv" colspan="4">` + value['wbn_cavino'] + `</td> </tr> <tr> <td class="rv" colspan="2">完了現品札No</td> <td class="rv" colspan="3"></td> <td class="rv" colspan="2">内容（不具合理由）</td> <td class="rv" colspan="4">` + wbn_defect_item + `</td></tr> <tr> <td colspan="5">不具合処置内容</td> <td colspan="6">不具合処置結果確認</td> </tr> <tr> <td class="rv" rowspan="3" colspan="4"></td> <td class="rv center">処置者</td> <td class="rv" rowspan="3" colspan="5"></td> <td class="rv center">確認者</td> </tr> <tr> <td class="rv" rowspan="2"><br><br></td> <td class="rv" rowspan="2"><br><br></td> </tr> <tr> </tr> <tr> <td colspan="11">在庫処置者：</td> </tr> <tr> <td class="center" rowspan="2" colspan="1">数量</td> <td class="rv center" colspan="1">製造係</td> <td class="rv center" colspan="3">処置係 </td> <td class="rv" colspan="1"> <img src="http://khahoangfpt.pythonanywhere.com/static/images/gachcheo.png" alt="Smiley face" width="67" height="20"></td> <td class="rv center" colspan="1">現品札No</td> <td class="rv center" colspan="1">廃棄数</td> <td class="rv" colspan="1"> <img src="http://khahoangfpt.pythonanywhere.com/static/images/gachcheo.png" alt="Smiley face" width="67" height="20"></td> <td class="rv center" colspan="1">現品札No</td> <td class="rv center" colspan="1">廃棄数</td> </tr> <tr> <td class="rv center" colspan="1">対象数</td> <td class="rv center" colspan="2">不良数 </td> <td class="rv center" colspan="1">良品数</td> <td class="rv right" colspan="1">1</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">11</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">仕掛品 <br> <span class="thick"> M </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['wip_numberoftargets'] + `</td> <td class="rv right" rowspan="2" colspan="2"></td> <td class="rv right" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">2</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">12</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">3</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">13</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">仕掛品 <br> <span class="thick"> P </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['wip_numberoftargets_P'] + `</td> <td class="rv" rowspan="2" colspan="2"></td> <td class="rv" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">4</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">14</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">5</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">15</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">蒸着品<br> <span class="thick"> J </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['vd_numberoftargets'] + `</td> <td class="rv" rowspan="2" colspan="2"></td> <td class="rv" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">6</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">16</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">7</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">17</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv center" rowspan="2" colspan="1">完成品<br> <span class="thick"> 00 </span> </td> <td class="rv center" rowspan="2" colspan="1">` + value['fh_numberoftargets'] + `</td> <td class="rv" rowspan="2" colspan="2"></td> <td class="rv" rowspan="2" colspan="1"></td> <td class="rv right" colspan="1">8</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">18</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv right" colspan="1">9</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">19</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> <tr> <td class="rv" colspan="5"></td> <td class="rv right" colspan="1">10</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> <td class="rv right" colspan="1">20</td> <td class="rv" colspan="1"></td> <td class="rv" colspan="1"></td> </tr> </table>`;
        sum_html += '<div>' + print_html + '<br>' + print_html + '</div>';
      });
      printJS({
        printable: sum_html,
        type: 'raw-html',
        documentTitle: '製品隔離指示書 ｜ ' + wbn_id + '｜' + created_at,
        modalMessage: '',
        targetStyles: ['*'],
        header: '',
        honorMarginPadding: false,
        showModal: true,
        style: `body { font-size: 14px; } table { width: 100%; border-collapse: collapse; } td { width: 10%; padding-left: 3px; } tr { height: 22px; } table.rv, tr.rv, td.rv { border: 1px solid; } .right { text-align: right; padding-right: 3px; } .center { text-align: center; } .thick { font-weight: bold; } .head { font-size: 25px; } img { display: inline-block; }input{background-color:transparent;border:none;}`
      })
    }
  </script>
</head>

<body>
  <div id="grid"></div>
</body>

</html>
