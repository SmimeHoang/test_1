<?php
if (!$placeid) {

  slot('h1', '<h1> 不具合連絡システム | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/MissingDefect?placeid=1000079">野洲工場</a>
      <a class="blue" href="/MissingDefect?placeid=1000073">山崎工場</a>
      <a class="blue" href="/MissingDefect?placeid=1000125">NPG</a>
      <a class="blue" href="./files/yasu-fs01/Yasu-doc/標準書・手順書/三次文書/野洲工場手順書管理台帳/★2017年/や品 - H17001外観検査手順書/付表①不良項目一覧表.pdf" target="_blank">付表①不良項目一覧表</a>
    </div>
    ';
  return;
}
use_javascript("jsQR.min.js");
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

$btn = '<div id="header_sel" style="float:left; margin-top:1px;" >';
$btn .= '
        <input class="selgroups" type="text" name="start_sel" id="start_sel" style="float:left; margin-left:5px;" placeholder="開始発行日" list="ymList" autocomplete="off">
        <label  style="float:left; " for="end_sel">~</label>
        <input class="selgroups" type="text" name="end_sel" id="end_sel" style="float:left;" placeholder="終了発行日" list="ymList" autocomplete="off">
        <datalist id="ymList">';
foreach ($sdate as $item) {
  $btn .= "<option value='" . $item['date'] . "'>\n";
}
$btn .= '</datalist>
        <input class="selgroups" type="text" name="dept_sel" id="dept_sel" style="float:left; margin-left:5px;" placeholder="宛先部署" list="DeptList" autocomplete="off">
        <datalist id="DeptList">';
foreach ($dept_array as $item) {
  if ($item != $placename) {
    $btn .= "<option value='" . $item . "'>\n";
  }
}
$btn .= '</datalist>
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
        <button type="button" class="btn_def" onclick="get_data(true);"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1838"/>検索</button>
        <button type="button" class="btn_def" onclick="fb_register();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1839"/>登録</button>
        <button type="button" class="btn_def" onclick="fb_graphic();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1844"/>グラフ</button>
        <button type="button" class="btn_def" onclick="fb_viewdata();"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1845"/>一覧</button>
        </div>
        <div style="float:left;">
        <label style="font-size: 0.8vw;" id="lb_person" onclick="fb_get_user();"></label>

        <input type="hidden" id="login_person"/>
        <input type="hidden" id="input_das_person"/>
        <input type="hidden" id="usercord"/>
        <input type="hidden" id="gp1"/>
        <input type="hidden" id="gp2"/>
        </div>
        <div style="float:right; ">
          <label style="font-size: 0.8vw;" id="lb_login_person"></label>
          <label style="font-size: 0.8vw;" style="color: khaki;">表示パターン</label>
          <input type="checkbox" id="checkbox_101" name="表示パターン" value="品名"/>
          <label style="font-size: 0.8vw;" class="radio9" for="checkbox_101" name="表示パターン">品名</label>
          <input type="checkbox" id="checkbox_102" name="表示パターン" value="発見日"/>
          <label style="font-size: 0.8vw;" class="radio9" for="checkbox_102" name="表示パターン">発見日</label>
        </div>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>
<html>

<head>
  <style>
     * {      box-sizing: border-box;    }
    body {font-size: 14px;}
    #header_sel input, #header_sel label, #header_sel button, #header_sel i {font-size: 0.8vw;height: 28px;}
    #divbox, #msgbox {position: absolute;}
    .form-validation-field-1formError {color: white;}
    .form-control {font-size: 14px;}
    .selgroups {height: 28px;width: 6.5vw;}
    h1 {font-size: 180%;}
    h2 {font-size: 160%;}
    h3 {font-size: 140%;}
    h4 {font-size: 130%;}
    .ui-button-text {font-size: 16px;margin: 0.1px;padding: 2px 4px 2px 4px !important;}
    .ui-button,.ui-button-text .ui-button {font-size: 14px !important;}
    .btn_defect {width: 100%;margin: 2px 0;padding: 0;text-align: left;border-color: white;position: relative;font-size: 0.8vw;}
    .btn_defect:hover {border-color: transparent;font-size: 0.9vw;}
    .toolbar {visibility: visible;padding-top: 0.2vw;top: 0.1vw;right: 0.1vw;position: absolute;border-radius: 50%;box-shadow: 0.375em 0.375em 0 0 rgba(15, 28, 63, 0.125);width: 2vw;height: 2vw;text-align: center;font-size: 130%;color: white;}
    .toolbar2 {visibility: visible;padding-top: 0.2vw;bottom: 0.05vw;right: 0vw;position: absolute;border-radius: 50%;box-shadow: 0.375em 0.375em 0 0 rgba(15, 28, 63, 0.125);width: 1.1vw;height: 1.1vw;text-align: center;font-size: 80%;color: white;}
    .toolbar3 {visibility: visible;padding-top: 0.2vw;bottom: 0.05vw;right: 1vw;position: absolute;border-radius: 50%;box-shadow: 0.375em 0.375em 0 0 rgba(15, 28, 63, 0.125);width: 1.1vw;height: 1.1vw;text-align: center;font-size: 80%;color: white;}
    .bgc-wh {background-color: wheat;}
    .bgc-0 {background-color: #FFFFE0;}
    .bgc-1 {background-color: #9AFF9A;}
    .bgc-2 {background-color: #7FFFD4;}
    .bgc-3 {background-color: #CAE1FF;}
    .bgc-4 {background-color: #836FFF;}
    .bgc-5 {background-color: #00BFFF;}
    .bgc-6 {background-color: #CAFF70;}
    .pd02 {padding: 0px 2px 0px 0px;}
    .bgc-7 {background-color: #CAE5E8;}
    /* Style the buttons */
    .btn {border: none;outline: none;padding: 12px 16px;background-color: #f1f1f1;cursor: pointer;}
    .btn:hover {background-color: #ddd;}
    .btn:active {background-color: #666;color: white;}
    .wrap {padding: 16px;}
    a {display: inline-block;color: #fff;text-decoration: none;padding: 8px 32px;margin: 16px;background: #666;}
    .wbn_defect_item_short {display: none;}
    #msg_view {background-color: darkgrey;border-radius: 20px;display: none;position: fixed;padding: 10px;min-width: 500px;}
    .customers {      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;width: 100%;background-color: #ddd;    }
    .customers td, .customers th {border: 1px solid #ddd;padding: 8px;}
    .customers td .defectivesitem {background-color: #f2f2f2;}
    .customers tr:nth-child(even) {background-color: #f2f2f2;}
    .customers tr:hover {background-color: wheat;}
    .customers th {padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #04AA6D;color: white;min-width: 100px;}
    div.bigclass {float: left;padding: 10px;width: 100%;border-radius: 5px;margin-bottom: 5px;}
    div.smallclass {float: left;padding: 5px;text-align: center;}
    #judgement_view {padding-bottom: 10px;font-size: 16px;}
    #judgement_NG {margin-top: 10px;}
    .number {height: 35px;}
    .form-control {width: 100%;}
    .form-control:active {background: rgb(255 228 181);}
    div.bigclass {float: left;padding: 5px;width: 100%;margin-bottom: 5px;}
    div.smallclass {float: left;padding: 0px 3px 0px 3px;text-align: center;}
    input.form-control {font-size: 14px;height: 28px;}
    textarea.form-control {font-size: 12px;height: 55px;}
    input[type='radio'], input[type='checkbox'] {width: 12px;height: 12px;}
    input[type=button],input[type=submit],input[type=reset] {border: none;color: white;text-decoration: none;cursor: pointer;height: 40px;}
    .btn_attached {background-color: #04AA6D;}
    .btn_next_OK {background-color: #04AA6D;}
    .btn_back_NG {background-color: red;}
    .btn_save {background-color: #00BFFF;}
    .btn_A1 { background-color: #ECAB53;}
    button.btn_def:hover, label.radio9:hover {color: orange; cursor: pointer;}
    input:hover, textarea:hover {background-color: khaki;font-weight: bolder;}
    input[type=button]:hover {color: #04AA6D;}
    .fa-spin {-webkit-animation: fa-spin 2s infinite linear;animation: fa-spin 2s infinite linear;}
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
    .wd-33 {width: 33.333%;}
    .wd-30 {width: 30%;}
    .wd-25 {width: 25%;}
    .wd-20 {width: 20%;}
    .wd-16 {width: 16.6%;}
    .wd-15 {width: 15%;}
    .wd-12-5 {width: 12.5%;}
    .wd-10 {width: 10%;}
    .wd-5 {width: 5%;}
    .wd-29 {width: 29%;}
    .wd-2-5 {width: 2.5%;}
    .wd-68 {width: 68%;}
    .bd {border: 1px dotted;}
    .bgcrank-1 {background-color: #AFD788;}
    .bgcrank-2 {background-color: #C8E2B1;}
    .bgcrank-3 {background-color: #E6F1D8;}
    .bgcrank-4 {background-color: #FFFAB3;}
    .bgcrank-5 {background-color: #FEF889;}
    .bgcrank-6 {background-color: #F6B297;}
    .bgcrank-7 {background-color: #EB7153;}
    .bgcrank-8 {background-color: #8E1E20; }
    div.radio, div.checkbox {padding: 0px; border-radius: 5px; background: white; }
    div.radioreadonly, div.checkboxreadonly {padding: 0px; border-radius: 5px; background: #DDDDDD; }
    label { margin: 0; margin-top: 5px; font-size: 14px;}
    #conf_msg { float: right; color: #fff; margin: 0 20px;}
    #a_type {width: 60px; padding: 1px;}
    #q {width: 90px; padding: 0.2px; }
    .handsontable .currentRow {color: #FFF;background-color: #000;}
    .handsontable .currentCol {color: #FFF;background-color: #000;}
    .handsontable .currenteader {background-color: #000;color: #fff;}
    .handsontable .htDimmed {color: #000;background-color: #ebf4f4;}
    .htCore tbody tr:nth-child(even) td {background-color: lightyellow;}
    .in-wrapper .htCore tbody tr:nth-child(odd) td {background-color: white;}
    table, tr, th, td {border: 1px solid white;}
    #loading { width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center;background-repeat: no-repeat; background-attachment: fixed;}
    #loading img {max-width: 100%; height: auto;}
    .ui-button, .ui-button-text .ui-button {font-size: 12px !important;}
    .bc_nc {background-color: blueviolet;}
    .bc_ch {background-color: #eee;}
    .hc_left {text-align: left;padding: 0px 0px 0px 5px;}
    .hc_right {text-align: right; padding: 0px 5px 0px 0px;}
    .hc_center {text-align: center; padding: 0px;}
    .menu {font-weight: bold; text-align: center; width: 20px; height: 20px; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px;}
    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 { float: left; padding: 0px;}
    .vw08 {font-size: 14px;}
    .vw07 {font-size: 13px;}
    .vw06 {font-size: 11px;}
    .vw05 {font-size: 10px;}
    .vw04 {font-size: 8px;}
    .pl5 {padding-left: 5px;}
    .pr10 {padding-right: 10px;}
    .name {font-size: 18px;font-weight: bolder;}
    .form-control::placeholder {color: #AAAAAA;}
    .form-control:-ms-input-placeholder {/* Internet Explorer 10-11 */color: #AAAAAA;}
    .form-control::-ms-input-placeholder {/* Microsoft Edge */color: #AAAAAA;}
    label.radio {font-size: 12px;display: inline-block;text-align: center;color: black;}
    label.radio:before {font-weight: normal;margin-right: 10px;}
    label.radio:hover {font-weight: bolder;cursor: pointer;}
    input:checked+label.radio {color: black;font-weight: bolder;font-size: 14px;}
    label.radio9 {font-size: 12px;display: inline-block;text-align: center;color: white;}
    label.radio9:before {font-weight: normal;margin-right: 10px;}
    input:checked+label.radio9 {color: chocolate;font-weight: bolder;font-size: 14px;}
    input:checked {width: 16px;height: 16px;}
    .hanko {margin: auto;font-size: 12px;border: 3px double #f00;border-radius: 50%;color: #f00;width: 80px;height: 80px;display: flex;flex-direction: column;justify-content: center;align-items: center;line-height: 1.7;}
    .hanko hr {width: 100%;margin: 0;border-color: #f00;}
    .hanko span {font-weight: bolder;}
    .stock_confirmation_index {font-size: 14px;}
    .stock_confirmation_index td {height: 27.3px;}

    @-webkit-keyframes glowing {0% {-webkit-box-shadow: 0 0 3px #004A7F;} 50% {-webkit-box-shadow: 0 0 20px red;} 100% {-webkit-box-shadow: 0 0 3px #004A7F;}}
    @-moz-keyframes glowing {0% {-moz-box-shadow: 0 0 3px #004A7F;} 50% {-moz-box-shadow: 0 0 20px red;} 100% {-moz-box-shadow: 0 0 3px #004A7F;}}
    @-o-keyframes glowing {0% {box-shadow: 0 0 3px #004A7F;} 50% {box-shadow: 0 0 20px red;} 100% {box-shadow: 0 0 3px #004A7F;}}
    @keyframes glowing {0% {box-shadow: 0 0 3px #004A7F;} 50% {box-shadow: 0 0 20px red;} 100% {box-shadow: 0 0 3px #004A7F;}}

    .input_error {-webkit-animation: glowing 1500ms infinite; -moz-animation: glowing 1500ms infinite; -o-animation: glowing 1500ms infinite; animation: glowing 1500ms infinite;}
    .error_data {-webkit-animation: glowing 1500ms infinite; -moz-animation: glowing 1500ms infinite; -o-animation: glowing 1500ms infinite; animation: glowing 1500ms infinite;}
    .icon_rfid_data {left: 25px;position:absolute; width: 35px; height: 35px;}
    .icon_button{width: 1vw;height: 1vw;}
  </style>
  <script>
    var wbn_id;
    var hovFlag = false;
    var placeid = '<?php echo $placeid; ?>';
    var placename = '<?php echo $placename; ?>';
    var document_number = '<?php echo $document_number; ?>';
    var worklist = <?php echo htmlspecialchars_decode($worklist); ?>;
    var dept_array = <?php echo htmlspecialchars_decode($deptlist); ?>;
    var listevidence = <?php echo htmlspecialchars_decode($listevidence); ?>;
    var placelist = <?php echo htmlspecialchars_decode('[{&quot;m_locator_id&quot;:1000041,&quot;value&quot;:&quot;ALBU倉庫_一般&quot;,&quot;x&quot;:9006,&quot;ad_org_id&quot;:1000117},{&quot;m_locator_id&quot;:1000039,&quot;value&quot;:&quot;BCRU倉庫_一般&quot;,&quot;x&quot;:9005,&quot;ad_org_id&quot;:1000115},{&quot;m_locator_id&quot;:1000037,&quot;value&quot;:&quot;CIBU倉庫_一般&quot;,&quot;x&quot;:9003,&quot;ad_org_id&quot;:1000113},{&quot;m_locator_id&quot;:1000042,&quot;value&quot;:&quot;DRD室倉庫_一般&quot;,&quot;x&quot;:9007,&quot;ad_org_id&quot;:1000118},{&quot;m_locator_id&quot;:101,&quot;value&quot;:&quot;Default HQ Locator&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:11},{&quot;m_locator_id&quot;:102,&quot;value&quot;:&quot;Default Store Locator&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:12},{&quot;m_locator_id&quot;:50001,&quot;value&quot;:&quot;Fertilizer&quot;,&quot;x&quot;:&quot;Fertilizer&quot;,&quot;ad_org_id&quot;:50001},{&quot;m_locator_id&quot;:50007,&quot;value&quot;:&quot;Fertilizer Transit&quot;,&quot;x&quot;:&quot;Fertilizer Transit&quot;,&quot;ad_org_id&quot;:50001},{&quot;m_locator_id&quot;:50004,&quot;value&quot;:&quot;Furniture&quot;,&quot;x&quot;:&quot;Furniture&quot;,&quot;ad_org_id&quot;:50000},{&quot;m_locator_id&quot;:50008,&quot;value&quot;:&quot;Furniture Transit&quot;,&quot;x&quot;:&quot;Furniture Transit&quot;,&quot;ad_org_id&quot;:50000},{&quot;m_locator_id&quot;:1000038,&quot;value&quot;:&quot;GOBU倉庫_一般&quot;,&quot;x&quot;:9004,&quot;ad_org_id&quot;:1000114},{&quot;m_locator_id&quot;:50000,&quot;value&quot;:&quot;HQ Transit&quot;,&quot;x&quot;:&quot;HQ Transit&quot;,&quot;ad_org_id&quot;:11},{&quot;m_locator_id&quot;:1000036,&quot;value&quot;:&quot;LSBU倉庫_一般&quot;,&quot;x&quot;:9002,&quot;ad_org_id&quot;:1000112},{&quot;m_locator_id&quot;:1000109,&quot;value&quot;:&quot;NNI在庫置場&quot;,&quot;x&quot;:3011,&quot;ad_org_id&quot;:1000125},{&quot;m_locator_id&quot;:1000012,&quot;value&quot;:&quot;Standard&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:1000009},{&quot;m_locator_id&quot;:50005,&quot;value&quot;:&quot;Store East&quot;,&quot;x&quot;:&quot;Store East&quot;,&quot;ad_org_id&quot;:50005},{&quot;m_locator_id&quot;:50002,&quot;value&quot;:&quot;Store North&quot;,&quot;x&quot;:&quot;Store North&quot;,&quot;ad_org_id&quot;:50002},{&quot;m_locator_id&quot;:50003,&quot;value&quot;:&quot;Store South&quot;,&quot;x&quot;:&quot;Store South&quot;,&quot;ad_org_id&quot;:50004},{&quot;m_locator_id&quot;:50006,&quot;value&quot;:&quot;Store West&quot;,&quot;x&quot;:&quot;Store West&quot;,&quot;ad_org_id&quot;:50006},{&quot;m_locator_id&quot;:1000050,&quot;value&quot;:&quot;オプト課倉庫_一般&quot;,&quot;x&quot;:6301,&quot;ad_org_id&quot;:1000105},{&quot;m_locator_id&quot;:1000137,&quot;value&quot;:&quot;カフィール_原料置場&quot;,&quot;x&quot;:3189,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000111,&quot;value&quot;:&quot;ガラス成形置場&quot;,&quot;x&quot;:3010,&quot;ad_org_id&quot;:1000125},{&quot;m_locator_id&quot;:1000127,&quot;value&quot;:&quot;ガラス成形開発置場&quot;,&quot;x&quot;:3010,&quot;ad_org_id&quot;:1000126},{&quot;m_locator_id&quot;:1000055,&quot;value&quot;:&quot;ナノ加工課UPM係倉庫_一般&quot;,&quot;x&quot;:7201,&quot;ad_org_id&quot;:1000091},{&quot;m_locator_id&quot;:1000056,&quot;value&quot;:&quot;ナノ加工課リソグラフィー係倉庫_一般&quot;,&quot;x&quot;:7102,&quot;ad_org_id&quot;:1000092},{&quot;m_locator_id&quot;:1000022,&quot;value&quot;:&quot;ナノ加工課倉庫_一般&quot;,&quot;x&quot;:7101,&quot;ad_org_id&quot;:1000090},{&quot;m_locator_id&quot;:1000128,&quot;value&quot;:&quot;メッシュ(樹脂成形)置場&quot;,&quot;x&quot;:3010,&quot;ad_org_id&quot;:1000127},{&quot;m_locator_id&quot;:1000130,&quot;value&quot;:&quot;モジュール課倉庫_一般&quot;,&quot;x&quot;:6901,&quot;ad_org_id&quot;:1000124},{&quot;m_locator_id&quot;:1000043,&quot;value&quot;:&quot;モノづくり特プロ倉庫_一般&quot;,&quot;x&quot;:9008,&quot;ad_org_id&quot;:1000119},{&quot;m_locator_id&quot;:1000004,&quot;value&quot;:&quot;京都保管場所&quot;,&quot;x&quot;:3,&quot;ad_org_id&quot;:1000005},{&quot;m_locator_id&quot;:1000007,&quot;value&quot;:&quot;伊丹保管場所&quot;,&quot;x&quot;:6,&quot;ad_org_id&quot;:1000008},{&quot;m_locator_id&quot;:1000060,&quot;value&quot;:&quot;品質保証課倉庫_一般&quot;,&quot;x&quot;:5501,&quot;ad_org_id&quot;:1000068},{&quot;m_locator_id&quot;:1000135,&quot;value&quot;:&quot;品質保証部倉庫_一般&quot;,&quot;x&quot;:9701,&quot;ad_org_id&quot;:1000069},{&quot;m_locator_id&quot;:1000133,&quot;value&quot;:&quot;営業部倉庫_一般&quot;,&quot;x&quot;:9601,&quot;ad_org_id&quot;:1000106},{&quot;m_locator_id&quot;:1000002,&quot;value&quot;:&quot;大阪保管場所&quot;,&quot;x&quot;:1,&quot;ad_org_id&quot;:1000003},{&quot;m_locator_id&quot;:1000031,&quot;value&quot;:&quot;大阪営業課倉庫_一般&quot;,&quot;x&quot;:5201,&quot;ad_org_id&quot;:1000109},{&quot;m_locator_id&quot;:1000099,&quot;value&quot;:&quot;大阪工場_CZN&quot;,&quot;x&quot;:2106,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000098,&quot;value&quot;:&quot;大阪工場_SMC&quot;,&quot;x&quot;:2105,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000107,&quot;value&quot;:&quot;大阪工場_保留品倉庫&quot;,&quot;x&quot;:2107,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000020,&quot;value&quot;:&quot;大阪工場_製造課_一般&quot;,&quot;x&quot;:2101,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000052,&quot;value&quot;:&quot;大阪工場_製造課_工務&quot;,&quot;x&quot;:2102,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000053,&quot;value&quot;:&quot;大阪工場_製造課_検査&quot;,&quot;x&quot;:2103,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000054,&quot;value&quot;:&quot;大阪工場_製造課_蒸着&quot;,&quot;x&quot;:2104,&quot;ad_org_id&quot;:1000085},{&quot;m_locator_id&quot;:1000011,&quot;value&quot;:&quot;大阪工場保管場所&quot;,&quot;x&quot;:9,&quot;ad_org_id&quot;:1000034},{&quot;m_locator_id&quot;:1000006,&quot;value&quot;:&quot;奈良保管場所&quot;,&quot;x&quot;:5,&quot;ad_org_id&quot;:1000007},{&quot;m_locator_id&quot;:1000153,&quot;value&quot;:&quot;山崎 自動化技術課倉庫&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:1000132},{&quot;m_locator_id&quot;:1000148,&quot;value&quot;:&quot;山崎工場_CZN&quot;,&quot;x&quot;:1109,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000108,&quot;value&quot;:&quot;山崎工場_SMC&quot;,&quot;x&quot;:1106,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000101,&quot;value&quot;:&quot;山崎工場_仕掛品置場&quot;,&quot;x&quot;:1103,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000069,&quot;value&quot;:&quot;山崎工場_保留品置場&quot;,&quot;x&quot;:1102,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000103,&quot;value&quot;:&quot;山崎工場_大阪出荷品置場&quot;,&quot;x&quot;:1104,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000104,&quot;value&quot;:&quot;山崎工場_完成品置場&quot;,&quot;x&quot;:1105,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000018,&quot;value&quot;:&quot;山崎工場_資材置場&quot;,&quot;x&quot;:1101,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000112,&quot;value&quot;:&quot;山崎工場_野洲出荷品置場&quot;,&quot;x&quot;:1107,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000009,&quot;value&quot;:&quot;山崎工場保管場所&quot;,&quot;x&quot;:7,&quot;ad_org_id&quot;:1000021},{&quot;m_locator_id&quot;:1000140,&quot;value&quot;:&quot;山崎工場＿蒸着置場&quot;,&quot;x&quot;:1108,&quot;ad_org_id&quot;:1000073},{&quot;m_locator_id&quot;:1000023,&quot;value&quot;:&quot;技術1課倉庫_一般&quot;,&quot;x&quot;:7501,&quot;ad_org_id&quot;:1000094},{&quot;m_locator_id&quot;:1000057,&quot;value&quot;:&quot;技術2課倉庫_一般&quot;,&quot;x&quot;:7601,&quot;ad_org_id&quot;:1000122},{&quot;m_locator_id&quot;:1000058,&quot;value&quot;:&quot;技術課倉庫_一般&quot;,&quot;x&quot;:7502,&quot;ad_org_id&quot;:1000123},{&quot;m_locator_id&quot;:1000139,&quot;value&quot;:&quot;技術開発課倉庫_一般&quot;,&quot;x&quot;:7801,&quot;ad_org_id&quot;:1000130},{&quot;m_locator_id&quot;:1000131,&quot;value&quot;:&quot;技術開発部倉庫_一般&quot;,&quot;x&quot;:8101,&quot;ad_org_id&quot;:1000128},{&quot;m_locator_id&quot;:1000000,&quot;value&quot;:&quot;日本産業保管場所&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:1000000},{&quot;m_locator_id&quot;:1000030,&quot;value&quot;:&quot;東京営業課倉庫_一般&quot;,&quot;x&quot;:5301,&quot;ad_org_id&quot;:1000108},{&quot;m_locator_id&quot;:1000008,&quot;value&quot;:&quot;標準&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:1000012},{&quot;m_locator_id&quot;:1000001,&quot;value&quot;:&quot;標準&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:1000002},{&quot;m_locator_id&quot;:1000017,&quot;value&quot;:&quot;標準倉庫_一般&quot;,&quot;x&quot;:9999,&quot;ad_org_id&quot;:1000061},{&quot;m_locator_id&quot;:1000129,&quot;value&quot;:&quot;測定課倉庫_一般&quot;,&quot;x&quot;:7801,&quot;ad_org_id&quot;:1000124},{&quot;m_locator_id&quot;:1000005,&quot;value&quot;:&quot;滋賀保管場所&quot;,&quot;x&quot;:4,&quot;ad_org_id&quot;:1000006},{&quot;m_locator_id&quot;:1000003,&quot;value&quot;:&quot;神戸保管場所&quot;,&quot;x&quot;:2,&quot;ad_org_id&quot;:1000004},{&quot;m_locator_id&quot;:1000044,&quot;value&quot;:&quot;経営のしくみ革新室倉庫_一般&quot;,&quot;x&quot;:9009,&quot;ad_org_id&quot;:1000120},{&quot;m_locator_id&quot;:1000035,&quot;value&quot;:&quot;経営企画室倉庫_一般&quot;,&quot;x&quot;:9001,&quot;ad_org_id&quot;:1000111},{&quot;m_locator_id&quot;:1000045,&quot;value&quot;:&quot;経営支援部倉庫_一般&quot;,&quot;x&quot;:5001,&quot;ad_org_id&quot;:1000062},{&quot;m_locator_id&quot;:1000027,&quot;value&quot;:&quot;薄膜生産技術課倉庫_一般&quot;,&quot;x&quot;:7701,&quot;ad_org_id&quot;:1000097},{&quot;m_locator_id&quot;:1000134,&quot;value&quot;:&quot;製造部倉庫_一般&quot;,&quot;x&quot;:9101,&quot;ad_org_id&quot;:1000098},{&quot;m_locator_id&quot;:1000150,&quot;value&quot;:&quot;設計開発課倉庫_一般&quot;,&quot;x&quot;:5100,&quot;ad_org_id&quot;:1000131},{&quot;m_locator_id&quot;:1000132,&quot;value&quot;:&quot;設計開発部倉庫_一般&quot;,&quot;x&quot;:9301,&quot;ad_org_id&quot;:1000098},{&quot;m_locator_id&quot;:1000028,&quot;value&quot;:&quot;評価技術課倉庫_一般&quot;,&quot;x&quot;:6801,&quot;ad_org_id&quot;:1000100},{&quot;m_locator_id&quot;:1000021,&quot;value&quot;:&quot;購買課倉庫_一般&quot;,&quot;x&quot;:4001,&quot;ad_org_id&quot;:1000072},{&quot;m_locator_id&quot;:1000152,&quot;value&quot;:&quot;野洲 自動化技術課倉庫&quot;,&quot;x&quot;:0,&quot;ad_org_id&quot;:1000133},{&quot;m_locator_id&quot;:1000141,&quot;value&quot;:&quot;野洲工場_1番地(OR①)&quot;,&quot;x&quot;:3151,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000142,&quot;value&quot;:&quot;野洲工場_2番地(OR②)&quot;,&quot;x&quot;:3152,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000071,&quot;value&quot;:&quot;野洲工場_3棟1階成形現場&quot;,&quot;x&quot;:3103,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000125,&quot;value&quot;:&quot;野洲工場_3棟3F廊下&quot;,&quot;x&quot;:3132,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000070,&quot;value&quot;:&quot;野洲工場_3棟3階成形現場&quot;,&quot;x&quot;:3102,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000143,&quot;value&quot;:&quot;野洲工場_3番地(OR以外①)&quot;,&quot;x&quot;:3153,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000144,&quot;value&quot;:&quot;野洲工場_4番地(OR以外②)&quot;,&quot;x&quot;:3154,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000145,&quot;value&quot;:&quot;野洲工場_5番地(生地)&quot;,&quot;x&quot;:3155,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000146,&quot;value&quot;:&quot;野洲工場_6番地(ﾘﾌﾚｸﾀ)&quot;,&quot;x&quot;:3156,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000075,&quot;value&quot;:&quot;野洲工場_EW3F&quot;,&quot;x&quot;:3107,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000073,&quot;value&quot;:&quot;野洲工場_FA&quot;,&quot;x&quot;:3105,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000076,&quot;value&quot;:&quot;野洲工場_FA(仕掛品)&quot;,&quot;x&quot;:3108,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000077,&quot;value&quot;:&quot;野洲工場_FA(完成品)&quot;,&quot;x&quot;:3109,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000147,&quot;value&quot;:&quot;野洲工場_FA(長期在庫)&quot;,&quot;x&quot;:3157,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000074,&quot;value&quot;:&quot;野洲工場_ME&quot;,&quot;x&quot;:3106,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000080,&quot;value&quot;:&quot;野洲工場_NW1階&quot;,&quot;x&quot;:3111,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000081,&quot;value&quot;:&quot;野洲工場_NW2階/仕掛品&quot;,&quot;x&quot;:3112,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000118,&quot;value&quot;:&quot;野洲工場_NW2階/完成品&quot;,&quot;x&quot;:3127,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000117,&quot;value&quot;:&quot;野洲工場_仕上エリア(北)/仕掛品&quot;,&quot;x&quot;:3126,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000082,&quot;value&quot;:&quot;野洲工場_仕上エリア(南)/仕掛品&quot;,&quot;x&quot;:3113,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000083,&quot;value&quot;:&quot;野洲工場_仕上エリア(南)/完成品&quot;,&quot;x&quot;:3114,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000092,&quot;value&quot;:&quot;野洲工場_仕上室2階/不動品&quot;,&quot;x&quot;:3122,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000090,&quot;value&quot;:&quot;野洲工場_仕上室2階/完成品&quot;,&quot;x&quot;:3121,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000093,&quot;value&quot;:&quot;野洲工場_仕上室2階/生地完成品&quot;,&quot;x&quot;:3123,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000120,&quot;value&quot;:&quot;野洲工場_仕上室2階：4番地&quot;,&quot;x&quot;:3129,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000121,&quot;value&quot;:&quot;野洲工場_仕上室2階：5番地&quot;,&quot;x&quot;:3130,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000122,&quot;value&quot;:&quot;野洲工場_仕上室2階：6番地&quot;,&quot;x&quot;:3131,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000072,&quot;value&quot;:&quot;野洲工場_仕掛品置場&quot;,&quot;x&quot;:3104,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000105,&quot;value&quot;:&quot;野洲工場_保留品倉庫&quot;,&quot;x&quot;:3199,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000126,&quot;value&quot;:&quot;野洲工場_出荷エリア&quot;,&quot;x&quot;:3133,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000138,&quot;value&quot;:&quot;野洲工場_副資材置場&quot;,&quot;x&quot;:3170,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000136,&quot;value&quot;:&quot;野洲工場_原料置場&quot;,&quot;x&quot;:3180,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000096,&quot;value&quot;:&quot;野洲工場_完成品置場&quot;,&quot;x&quot;:3124,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000084,&quot;value&quot;:&quot;野洲工場_工務室：1番地1列目&quot;,&quot;x&quot;:3115,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000085,&quot;value&quot;:&quot;野洲工場_工務室：1番地2列目&quot;,&quot;x&quot;:3116,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000086,&quot;value&quot;:&quot;野洲工場_工務室：1番地3列目&quot;,&quot;x&quot;:3117,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000087,&quot;value&quot;:&quot;野洲工場_工務室：1番地4列目&quot;,&quot;x&quot;:3118,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000088,&quot;value&quot;:&quot;野洲工場_工務室：1番地5列目&quot;,&quot;x&quot;:3119,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000089,&quot;value&quot;:&quot;野洲工場_工務室：1番地6列目&quot;,&quot;x&quot;:3120,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000097,&quot;value&quot;:&quot;野洲工場_工務室：2番地&quot;,&quot;x&quot;:3125,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000119,&quot;value&quot;:&quot;野洲工場_工務室：3番地&quot;,&quot;x&quot;:3128,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000078,&quot;value&quot;:&quot;野洲工場_旧棟出荷場&quot;,&quot;x&quot;:3110,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000019,&quot;value&quot;:&quot;野洲工場_製造課_一般&quot;,&quot;x&quot;:3101,&quot;ad_org_id&quot;:1000079},{&quot;m_locator_id&quot;:1000010,&quot;value&quot;:&quot;野洲工場保管場所&quot;,&quot;x&quot;:8,&quot;ad_org_id&quot;:1000028},{&quot;m_locator_id&quot;:1000059,&quot;value&quot;:&quot;金型課倉庫_一般&quot;,&quot;x&quot;:7301,&quot;ad_org_id&quot;:1000093}]'); ?>;
    var fh_palce = get_placelist(placelist, '完成');
    var wip_palce = get_placelist(placelist, '仕掛');
    var vd_palce = get_placelist(placelist, '蒸着');
    var oh_palce = get_placelist(placelist, '保留');
    var screen_height = screen.height - screen.height * 0.17;
    var backup_data = "";
    var document_number_check = false;
    var judgement_click = false,
      kannri_id_view;
    var obj_RFID_data_index;
    var ww = $(window).width() - 5;
    var wh = $(window).height() - 40;
    var gp1, gp2;
    const isNumberCase = c => {
				return /^[0-9]+$/g.test(c)
			}
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
    $(".btn_def").button();
    
    $(window).load(function() {
      if (localStorage.getItem("backup_data")) {
        backup_data = localStorage.getItem("backup_data");
      }
      kannri_id_view = localStorage.getItem("kannri_id_view");
      kannri_id_view??= "管理ナンバー表示";
      get_data(true);
      setInterval(function() {
        if(!$("#alert").dialog('isOpen')){
          location.reload()
          }
      }, 300000);
      $("#alert").dialog({
        autoOpen: false,
        modal: true,
        position: ["center", 40],
        buttons: [{
          text: "閉じる",
          click: function() {
            $(this).dialog("close");
          }
        }]
      });
      $("#alert_dept").dialog({
        autoOpen: false,
        modal: true,
        position: ["center", 40],
        buttons: []
      });
      $(".selgroups").focusin(function() {
        $(this).val('');
      });
      $("input[name='表示パターン']").click(function() {
        if ($(this).is(':checked')) {
          backup_data += $(this).val();
        } else {
          backup_data = backup_data.replace($(this).val(), "");
        }
        localStorage.setItem("backup_data", backup_data);
        if (backup_data.indexOf('品名') > -1) {
          $(".div_product_name").css("display", "block");
        } else {
          $(".div_product_name").css("display", "none");
        }
        if (backup_data.indexOf('発見日') > -1) {
          $(".div_date_of_discovery").css("display", "block");
        } else {
          $(".div_date_of_discovery").css("display", "none");
        }
      });
    });


    function get_placelist(dataAry, place) {
      var result = '';
      dataAry.forEach((value, key) => {
        if(value.ad_org_id == placeid){
          if (value.value.indexOf(place) > -1) {
            result = value.m_locator_id + "," + value.value + "," + value.x;
          }
        }
      });
      return result;
    }

    function get_data(flg_load) {
      let name_sel = '';
      let code_sel = '';
      let start_sel = '1900-01-01';
      let end_sel = '2999-01-01';
      let dept_sel = $('#dept_sel').val();
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
      if (start_sel > end_sel) {
        alert('開始日時は終了日時より小さいです。');
        return;
      }
      var datas = {
        ac: "GetJson",
        placename: placename,
        placeid: placeid,
        start_sel: start_sel,
        end_sel: end_sel,
        dept_sel: dept_sel,
        wip_palce: wip_palce,
        fh_palce: fh_palce,
        vd_palce: vd_palce,
        oh_palce: oh_palce,
        documentid_sel: $('#documentid_sel').val(),
        code_sel: code_sel,
        name_sel: name_sel,
        dept_array: dept_array
      }
      fb_loading(flg_load);
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          $("button").remove(".btn_defect");
          view_data(d);
          fb_loading(false);
          if (document_number) {
            if (document_number_check) {
              fb_view_judgement(document_number);
            } else {
              alert(document_number + "の資料番号は判定画面が存在しないので\n完了や削除済などデータを確認してください。")
            }
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          fb_loading(false);
          return;
        }
      });
    }

    function fb_diff_day(fist_day, last_day) {
      const fist = new Date(fist_day);
      const last = new Date(last_day);
      const diffTime = Math.abs(last - fist);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    }
    function fb_diff_min(fist_day, last_day) {
      const fist = new Date(fist_day);
      const last = new Date(last_day);
      const diffTime = Math.abs(last - fist);
      const diffDays = Math.ceil(diffTime / (1000 * 60));
      return diffDays;
    }
    function fb_diff_sec(fist_day, last_day) {
      const fist = new Date(fist_day);
      const last = new Date(last_day);
      const diffTime = Math.abs(last - fist);
      const re_diff = Math.ceil(diffTime / (1000));
      return re_diff;
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

    function view_data(obj) {
      var i = 0;
      var color_list = [
        ['#FFFFE0', '#EEEED1', '#CDCDB4', '#8B8B7A', 'rgb(220,0,0)'],
        ['#9AFF9A', '#90EE90', '#7CCD7C', '#548B54', 'rgb(220,0,0)'],
        ['#7FFFD4', '#76EEC6', '#66CDAA', '#458B74', 'rgb(220,0,0)'],
        ['#CAE1FF', '#BCD2EE', '#A2B5CD', '#6E7B8B', 'rgb(220,0,0)'],
        ['#836FFF', '#7A67EE', '#6959CD', '#473C8B', 'rgb(220,0,0)'],
        ['#00BFFF', '#00B2EE', '#009ACD', '#00688B', 'rgb(220,0,0)'],
        ['#CAFF70', '#BCEE68', '#A2CD5A', '#6E8B3D', 'rgb(220,0,0)'],
        ['#CAE5E8', '#99D1D3', '#6EC3C9', '#00A6AD', 'rgb(220,0,0)']
      ];
      var diff;

      Object.keys(obj).forEach(function(name) {
        obj[name].forEach((value, key) => {
          now = new Date();
          let choki = '';

          switch (parseInt(value.wbn_processing_position)) {
            case 0:
              diff = fb_diff_day(value.date, now) - 1;
              break;
            case 1:
              diff = fb_diff_day(value.wbn_decision_date, now) - 1;
              break;
            case 2:
              diff = fb_diff_day(value.wbn_isolation_decision_date, now) - 1;
              break;
            case 3:
              if (value.wbn_rank == 3) {
                diff = fb_diff_day(value.wbn_decision_date, now) - 1;
              } else {
                diff = fb_diff_day(value.wbn_production_system_date, now) - 1;
              }
              break;
            case 4:
              if (value.wbn_manufacturing_date) {
                diff = fb_diff_day(value.wbn_manufacturing_date, now) - 1;
              } else {
                diff = fb_diff_day(value.wbn_BU_decision_date, now) - 1;
              }
              break;
            case 5:
              diff = fb_diff_day(value.wbn_licensor_date, now) - 1;
              break;
            case 6:
              diff = fb_diff_day(value.wbn_treatment_date, now) - 1;
              break;
            case 7:
              if (value.wbn_rank == 3) {
                diff = fb_diff_day(value.wbn_treatment_date, now) - 1;
              } else if (value.wbn_rank == 1) {
                diff = fb_diff_day(value.wbn_BU_decision_date, now) - 1;
              } else {
                diff = fb_diff_day(value.wbn_quantity_date, now) - 1;
              }
              if (value.wbn_confirmation == "長期確認が必要") {
                choki = '(長期)';
                diff = 3;
              }
              break;
            default:
              diff = fb_diff_day(value.date, now) - 1;
          }

          if (document_number) {
            if (value.wbn_id == document_number) {
              document_number_check = true;
            }
          }
          if (diff > 4) {
            diff = 4;
          }
          let saihako = '';
          if (value.wbn_alignment.indexOf("再発行") > -1) {
            saihako = ';border: thick double yellow';
          }
          if (value.error == "error") {
            saihako = ';border: thick double red';
          }
          var h3_str_color = "#000";
          var bg_color = color_list[value.wbn_processing_position][diff];
          // var hit_color =["#8B0000","#00688B","#5D478B","#548B54","#009ACD"];
          // if(hit_color.indexOf(bg_color) >= 0){
          //   h3_str_color = "#c0c0c0";
          // }
          // console.log(hit_color.indexOf(h3_bg_color)+","+h3_bg_color+","+h3_str_color);
          name_html = `<div> <button id="` + value.wbn_id + `" class="btn_defect" style="background-color: ` + bg_color + saihako + `;" onclick="fb_view_judgement(this.id);"><h3 style="color:` + h3_str_color + `;">` + value.wbn_id + choki + `</h3>
          <div class="div_defect_item" style="color:` + h3_str_color + `;">` + value.wbn_defect_item + `</div>
          <div class="div_product_name" style="color:` + h3_str_color + `;">` + value.wbn_product_name + ` - ` + value.wbn_form_no + `</div>
          <div class="div_date_of_discovery" style="color:` + h3_str_color + `;">` + value.date + `</div>`;
          //name_html += `</p>`;
          Object.keys(value).forEach(function(item) {
            if (value[item]) {
              value_item = value[item];
            } else if (value[item] == 0) {
              value_item = value[item];
            } else {
              value_item = '';
            }
            if (item == 'wbn_bad_rate') {
              value_item = value[item] + '%';
            }
            name_html += `<input type="hidden" id="` + item + value.wbn_id + `" value="` + value_item + `" />`;
          });
          if (value.omit != '') {
            if (value.position.indexOf("再") > -1 || value.position.indexOf("直し") > -1) {
              name_html += `<div class="toolbar"style="background-color: ` + value.bg + `; color: red;"> ` + value.omit + ` </div>`;
            } else {
              name_html += `<div class="toolbar"style="background-color: ` + value.bg + `;"> ` + value.omit + ` </div>`;
            }
          } else {
            if (value.position.indexOf("再") > -1 || value.position.indexOf("直し") > -1) {
              name_html += `<div class="toolbar"style="background-color: white; color: red;"> 戻 </div>`;
            }
          }
          if (value.count_RFID > 0) {
            name_html += `<div class="toolbar3"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=7"/></div>`;
          }
          if (value.mfc_count > 0) {
            name_html += `<div class="toolbar2"><img class="icon_button" src="/MissingDefect/GetFile?menu=img&mfc_id=1841"/></div>`;
          }
          name_html += `</button></div>`;
          $('.' + name).append(name_html);
          $('.count' + name).html(" (" + obj[name].length + ")");
          if (backup_data.indexOf('品名') > -1) {
            set_checkbox('表示パターン', "品名");
            $(".div_product_name").css("display", "block");
          } else {
            $(".div_product_name").css("display", "none");
          }
          if (backup_data.indexOf('発見日') > -1) {
            set_checkbox('表示パターン', "発見日");
            $(".div_date_of_discovery").css("display", "block");
          } else {
            $(".div_date_of_discovery").css("display", "none");
          }
        });
      });
      //リンクへ吸い付く処理
      //マウスホバー時
      $('.btn_defect').hover(
        function() {
          if (judgement_click) {
            return;
          }
          wbn_id = this.id;
          var msg_jquery = document.getElementById('msg_view');
          var div_wip = '';
          var margin_bot = 10;
          var total_time = parseFloat($('#totaltime' + wbn_id).val()) > 0 ? $('#totaltime' + wbn_id).val() + '時' : '';
          hovFlag = true;
          let saihako = '';
          if ($('#wbn_alignment' + wbn_id).val().indexOf("再発行") > -1) {
            saihako = '<span style="color:red"> （再発行）</span>';
          }
          let re_judgement = '';
          if ($('#position' + wbn_id).val().indexOf("再") > -1 || $('#position' + wbn_id).val().indexOf("直し") > -1) {
            re_judgement = ' style="color:red;"';
          }
          if ($('#wip_numberoftargets_P' + wbn_id).val() > 0) {
            div_wip = `   <tr>
                          <td class="wd-16 hc_left">仕掛品(P)</td>
                          <td><input type="text" class="form-control hc_right" value = "` + $("#wip_numberoftargets_P" + wbn_id).val() + `" disabled></input></td>
                          <td><input type="text" class="form-control hc_right" value = "` + $("#wbn_wip_testnum_P" + wbn_id).val() + `" disabled></input></td>
                          <td><input type="text" class="form-control hc_right" value = "` + $("#wip_adversenumber_P" + wbn_id).val() + `" disabled></input></td>
                          <td colspan="2">
                            <label class=" vw06">保管元：` + $('#afterprocess' + wbn_id).val().split(",")[1] + `</label>
                          </td>
                        </tr>  `;
            margin_bot = 34;
          }
          var msg_view;
          msg_view = `
                    <div class="col-md-6"  style="padding: 0px">
                      <div class="col-md-12" style="padding: 5px 5px 0px 5px;">
                        <div class="bigclass  bgc-wh ">
                          <div id="divbox"></div>
                          <div class=" smallclass wd-50" style="padding-top: 10px;">
                            <h3>不具合連絡書</h3>
                          </div>
                          <div class="smallclass wd-30">
                            <label >資料番号` + saihako + `</label>
                            <input type="text" class="form-control hc_center" style="font-weight: bolder;" value="` + wbn_id + `" readonly/>
                          </div>
                          <div class="smallclass wd-20">
                            <label >ランク</label>
                            <input class="form-control hc_center" value = '` + $('#wbn_rank' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class=" smallclass wd-60">
                            <label >品名</label>
                            <input type="text" class="form-control hc_center" style="ime-mode:disabled;" value = '` + $('#wbn_product_name' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-25">
                            <label >品目コード</label>
                            <input type="text" class="form-control hc_center" value = '` + $('#wbn_item_code' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-15">
                            <label >BU</label>
                            <input class="form-control hc_center" type='text' value = '` + $('#wbn_bu' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-10">
                            <label >型番</label>
                            <input class="form-control hc_center" value = '` + $('#wbn_form_no' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-20">
                            <label >成形Lot</label>
                            <input name="number" class="form-control hc_center" value = '` + $('#wbn_lot_no' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-30">
                            <label >キャビNo</label>
                            <input name="number" class="form-control hc_center" value = '` + $('#CAVno' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-20">
                            <label >蒸着Lot</label>
                            <input name="number" class="form-control hc_center" value = '` + $('#wbn_vd_dt' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-20">
                            <label >成形日</label>
                            <input type="text" class="form-control hc_right"  value = '` + $('#wbn_mold_dt' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-50" style="margin:5px 0px 5px 0px">
                            <textarea class="form-control" id="idbugcontent" rows="2" placeholder="不具合内容" title="不具合内容" readonly>` + $('#wbn_defect_item' + wbn_id).val() + `</textarea>
                            <textarea class="form-control" rows="2" placeholder="状態・原因" title="状態・原因" tabindex="11" style="margin-top:5px" readonly>` + $('#wbn_defect_details' + wbn_id).val() + `</textarea>
                            <textarea class="form-control" rows="2" placeholder="OKの根拠 " title="OKの根拠" style="margin-top:5px" readonly>` + $('#wbn_decisive_evidence' + wbn_id).val() + `</textarea>
                            <textarea class="form-control" rows="2" placeholder="要望事項" title="要望事項" style="margin-top:5px" readonly>` + $('#wbn_decision_demand' + wbn_id).val() + `</textarea>
                          </div>
                          <div class="smallclass wd-12-5">
                            <label>対象数</label>
                            <input name="number" class="form-control hc_right pd02"  value = '` + $('#wbn_qty' + wbn_id).val() + `' readonly/>
                          </div>
                          <div class="smallclass wd-12-5">
                            <label>検査数</label>
                            <input name="number" class="form-control hc_right pd02" value = '` + $('#wbn_insp_qty' + wbn_id).val() + `'readonly/>
                          </div>
                          <div class="smallclass wd-12-5">
                            <label>不良数</label>
                            <input name="number" class="form-control hc_right pd02"  value = '` + $('#wbn_bad_qty' + wbn_id).val() + `'readonly/>
                          </div>
                          <div class="smallclass wd-12-5">
                            <label>不良率</label>
                            <input type="text" class="form-control hc_right pd02" value = '` + $('#wbn_bad_rate' + wbn_id).val() + `' readonly/>
                          </div>

                          <div class=" smallclass wd-25" style="line-height: 3;">
                            <label for="discoverer">発見者</label>
                            ` + fb_get_hakko($('#wbn_discoverer' + wbn_id).val(), $('#date' + wbn_id).val()) + `
                          </div>
                          <div class="smallclass wd-25" style="line-height: 3;">
                            <label for="認可者">認可者</label>
                            ` + fb_get_hakko($('#wbn_decision_person' + wbn_id).val(), $('#wbn_decision_date' + wbn_id).val(), "", $('#wbn_decision' + wbn_id).val()) + `
                          </div>
                          <div class=" smallclass wd-50" style="margin-top:20px">
                            <div class=" smallclass wd-50"><label for="processing_position">処理状態</label></div>
                            <div class=" smallclass wd-50"><input type="text" class="form-control hc_center name" ` + re_judgement + ` value="` + $('#position' + wbn_id).val() + `" readonly/></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12" style="padding: 0px  5px 0px 5px;">
                        <div class=" bigclass bgc-wh">
                          <div class=" smallclass wd-30" style="padding-top: 0.5vw;">
                            <h3>是正指示欄</h3>
                          </div>
                          <div class="smallclass radioreadonly wd-68" style="float:right; margin-right: 4px;">
                            <label>修理依頼書</label><br>
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
                              <input class="form-control hc_center name" type="text" value = "` + $("#wbn_dept" + wbn_id).val() + `" readonly/>
                            </div>
                            <div class="smallclass wd-25 vw07">
                              <label for="deadline" >在庫処置期限</label>
                              <input class="form-control hc_right " type="text"  value = "` + $("#wbn_deadline" + wbn_id).val() + `" readonly/>
                            </div>
                            <div class="smallclass wd-25 vw07">
                              <label for="receipt_dt">受理日</label>
                              <input class="form-control hc_right" type="text"  value = "` + $("#wbn_receipt_dt" + wbn_id).val() + `" readonly/>
                            </div>
                            <div class="smallclass wd-25 vw07">
                              <label for="due_dt">処置回答指示日</label>
                              <input class="form-control hc_right " type="text"  value = "` + $("#wbn_due_dt" + wbn_id).val() + `" readonly/>
                            </div>
                          </div>
                          <div class="smallclass wd-100" style=" float:left; padding: 4px 0px 4px 4px; margin-top: 1px;">
                            <div class="smallclass radioreadonly wd-20" >
                              <label>欠点</label><br>
                              <input type="radio" id="defect_type_1" name="欠点分類" value="重" />
                              <label class="radio" for="defect_type_1" name="欠点分類" >重</label>
                              <input type="radio" id="defect_type_2" name="欠点分類" value="軽" />
                              <label class="radio" for="defect_type_2" name="欠点分類" >軽</label>
                            </div>
                            <div class="smallclass wd-80" style="float:right">
                              <div class="smallclass radioreadonly wd-100">
                                <label>発行理由</label><br>
                                <input type="radio" id="reason_1" name="発行理由" value="ロット不合格" disabled/>
                                <label class="radio" for="wbn_reason_1" >ロット不合格</label>
                                <input type="radio" id="reason_2" name="発行理由" value="選別不良" disabled/>
                                <label class="radio" for="wbn_reason_2" >選別不良</label>
                                <input type="radio" id="reason_3" name="発行理由" value="管理不良" disabled/>
                                <label class="radio" for="wbn_reason_3" >管理不良</label>
                                <input type="radio" id="reason_4" name="発行理由" value="品質上の警告" disabled/>
                                <label class="radio" for="reason_4" >品質上の警告</label>
                              </div>
                            </div>
                          </div>
                          <div class="smallclass wd-100" >
                            <textarea class="form-control" rows="3" placeholder="要望事項" title="要望事項" readonly>` + $("#wbn_due_details" + wbn_id).val() + `</textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12" style="padding: 0px 5px 0px 5px;">
                        <div class="bigclass  bgc-wh ">
                          <div id="水平展開欄" class="smallclass wd-25 short" style="margin-top:29px">
                            <h4>水平展開欄</h3>
                          </div>
                          <div class="smallclass wd-100" style="margin-top: 15px; margin-bottom: ` + margin_bot + `px;">
                            <table class="wd-100">
                              <tr>
                                <td class="wd-40 hc_left">類似製品に対する水平展開</td>
                                <td class="wd-40 hc_left">類似プロセスに対する水平展開</td>
                                <td class="wd-40 hc_center">製造課長</td>
                              </tr>
                              <tr>
                                <td><input type="text" class="form-control " value = "` + $("#wbn_products_nowant" + wbn_id).val() + `" placeholder="不要" disabled></input></td>
                                <td><input type="text" class="form-control " value = "` + $("#wbn_products_want" + wbn_id).val() + `" placeholder="必要" disabled></input></td>
                                <td rowspan="2">
                                  <input type="text" class="form-control hc_center name" value = "` + $("#wbn_manufacturing_person" + wbn_id).val() + `" placeholder='未' disabled></input>
                                  <input type="text" class="form-control hc_center " value = "` + $("#wbn_manufacturing_date" + wbn_id).val() + `" disabled></input>
                                </td>
                              </tr>
                              <tr>
                                <td><input type="text" class="form-control " value = "` + $("#wbn_process_nowant" + wbn_id).val() + `" placeholder="不要" disabled></input></td>
                                <td><input type="text" class="form-control " value = "` + $("#wbn_process_want" + wbn_id).val() + `" placeholder="必要" disabled></input></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6"  style="padding: 0px">
                      <div class="col-md-12" style="padding: 5px  5px 0px 0px;">
                        <div class="bigclass  bgc-wh ">
                          <div class="smallclass wd-40" style="padding-top: 15px;">
                          `+(parseInt($("#count_RFID" + wbn_id).val()) > 0?`<div style="float:left; "><img class="icon_rfid_data" src="/MissingDefect/GetFile?menu=img&mfc_id=7"/></div>`:"")+`
                            <h3>在庫処置欄</h3>
                          </div>
                          <div class="smallclass wd-60">
                            <textarea class="form-control" rows="2" placeholder="現品の内容確認（不適合内容に対する見解）\n※ 同意・相違,異議についてコメントを記載" title="現品の内容確認" readonly>` + $("#wbn_content_confirmation" + wbn_id).val() + `</textarea>
                          </div>
                          <div class="stock_confirmation_index smallclass wd-100" style="margin-top:5px">
                            <table class="wd-100">
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
                                <td><input type="text" class="form-control hc_right" value = "` + $("#fh_numberoftargets" + wbn_id).val() + `" disabled></input></td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#wbn_fh_testnum" + wbn_id).val() + `" disabled></input></td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#fh_adversenumber" + wbn_id).val() + `" disabled></input></td>
                                <td rowspan="3">
                                  <div id="div_licensor"></div>
                                </td>
                              </tr>
                              <tr>
                                <td class="wd-16 hc_left">仕掛品</td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#wip_numberoftargets" + wbn_id).val() + `" disabled></input></td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#wbn_wip_testnum" + wbn_id).val() + `" disabled></input></td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#wip_adversenumber" + wbn_id).val() + `" disabled></input></td>
                                <td rowspan="2">
                                  <div id="div_quantity"></div>
                                </td>
                              </tr>
                              <tr>
                                <td class="wd-16 hc_left">蒸着品</td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#vd_numberoftargets" + wbn_id).val() + `" disabled></input></td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#wbn_vd_testnum" + wbn_id).val() + `" disabled></input></td>
                                <td><input type="text" class="form-control hc_right" value = "` + $("#vd_adversenumber" + wbn_id).val() + `" disabled></input></td>
                              </tr>
                              ` + div_wip + `
                            </table>
                          </div>
                          <div class="smallclass wd-75" style="margin-top:5px">
                            <div class="smallclass radioreadonly wd-100">
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
                              <textarea class="form-control" rows="2" placeholder="手直し（修理）の場合は処置内容を記入　\n ※ 手直し指示書を作成する事" title="手直し（修理）の場合は処置内容を記入" readonly>` + $("#wbn_rework_instructions" + wbn_id).val() + `</textarea>
                            </div>
                          </div>
                          <div class="smallclass wd-25" style="line-height: 2;">
                            <label for="処置担当者">処置担当者</label>
                            ` + fb_get_hakko($('#wbn_treatment_person' + wbn_id).val(), $('#wbn_treatment_date' + wbn_id).val()) + `
                          </div>
                          <div class="smallclass wd-100">
                            <div class="smallclass wd-75" style="padding: 0px 2px 0px 0px;">
                              <textarea class="form-control" rows="2" style="margin-top: 5px;" placeholder="在庫処置のみ及び対象在庫確定の根拠 \n（発生部門課長が判断可 右端欄にチェック、対策回答欄以下は記入不要）" title="在庫処置のみ及び対象在庫確定の根拠" readonly>` + $("#wbn_inventory_processing" + wbn_id).val() + `</textarea>
                            </div>
                            <div class="smallclass wd-25" style="margin-top: 5px;">
                              <input type="radio" id="treatment_only" name="処置のみ" value="処置のみ" disabled>
                              <label class="radio" for="treatment_only">処置のみ</label><br>
                              <div class="smallclass wd-100">
                                <div class="smallclass wd-40">
                                  <label style="font-size: 11px;"> 検査時間</label>
                                </div>
                                <div class="smallclass wd-60">
                                  <input type="text" class="form-control hc_center" id="total_time" value="` + total_time + `" readonly></input>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class=" col-md-12" style="padding: 0px 5px 5px 0px;">
                        <div class="bigclass bgc-wh">
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
                                <textarea class="form-control" style="height: 95px;" rows="4" placeholder="" readonly>` + $("#wbn_cause" + wbn_id).val().replace("入力必要", "") + `</textarea>
                              </td>
                              <td style="vertical-align: top;">
                                <textarea class="form-control" style="height: 95px;" rows="4" placeholder="" readonly>` + $("#wbn_countermeasures" + wbn_id).val().replace("入力必要", "") + `</textarea>
                              </td>
                              <td rowspan="2" style="line-height: 1; padding:2px; vertical-align: top;">
                                <div style=" padding-top:2px;">
                                  <input type="radio" id="restoration" name="復旧確認で可" value="復旧確認で可" disabled>
                                  <label class="radio" for="restoration" class="vw06">復旧確認で可</label><br>
                                  <input type="radio" id="confirmation" name="長期確認が必要" value="長期確認が必要" disabled>
                                  <label class="radio" for="confirmation" class="vw06">長期確認が必要</label><br>
                                </div>
																<div class="smallclass wd-100" style=" padding: 0px;">
																	<div class="smallclass wd-35" style=" padding:0px 1px 0px 0px;">
																		<label  class="vw06">検査数</label>
																		<input name="number" class="form-control hc_right" style="font-size: 11px;" value="` + $("#wbn_inspection_total" + wbn_id).val() + `" readonly>
																	</div>
																	<div class="smallclass wd-30" style=" padding:0px 1px 0px 0px;">
																		<label class="vw06">不良数</label>
																		<input name="number" class="form-control hc_right" style="font-size: 11px;" value="` + $("#wbn_inspection_bad" + wbn_id).val() + `" readonly>
																	</div>
																	<div class="smallclass wd-35" style=" padding:0px;">
																		<label class="vw06">不良率</label>
																		<input type="text" class="form-control hc_right" style="font-size: 11px;" value="` + $("#wbn_inspection_rate" + wbn_id).val() + `" readonly>
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
                                <label name="効果なし" class="vw06 hide">・コメント</label>
                                <input type="text" class="form-control vw06 hide" name="効果なし" value="` + $("#wbn_effect_NG_msg" + wbn_id).val() + `" readonly>
                                <label name="効果なし" class="vw06 hide">・再発行・資料No</label>
                                <input type="text" class="form-control hide" name="効果なし" value="` + $("#wbn_reissue_id" + wbn_id).val() + `" readonly>
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: center;">流出</td>
                              <td style="vertical-align: top;">
                                <textarea class="form-control" style="height: 95px;" rows="4" placeholder="" readonly>` + $("#wbn_outflow_cause" + wbn_id).val().replace("入力必要", "") + `</textarea>
                              </td>
                              <td style="vertical-align: top;">
                                <textarea class="form-control" style="height: 95px;" rows="4" placeholder="" readonly>` + $("#wbn_outflow_countermeasures" + wbn_id).val().replace("入力必要", "") + `</textarea>
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: center;">確認</td>
                              <td colspan="2">
                                <div class="smallclass wd-5 vw06 " style="border: 0.5px dotted">対策部門
                                </div>
                                <div class="smallclass wd-30">
                                  <input type="text" class="form-control hc_center name"  value = "` + $("#wbn_countermeasure_person" + wbn_id).val() + `"  disabled></input>
                                  <input type="text" class="form-control hc_center "  value = "` + $("#wbn_countermeasure_date" + wbn_id).val() + `"  disabled></input>
                                </div>
                                <div class="smallclass wd-5 vw06" style="border: 0.5px dotted">作成部門
                                </div>
                                <div class="smallclass wd-30">
                                  <input type="text" class="form-control hc_center name"  value = "` + $("#wbn_reation_person" + wbn_id).val() + `"  disabled></input>
                                  <input type="text" class="form-control hc_center "  value = "` + $("#wbn_reation_date" + wbn_id).val() + `"  disabled></input>
                                </div>
                                <div class="smallclass wd-5 vw06" style="border: 0.5px dotted">品管部門
                                </div>
                                <div class="smallclass wd-25">
                                  <div id="div_quality_control"></div>
                                </div>
                              </td>
                              <td>
                                <div class="smallclass wd-100 vw06">コメントがある場合記載</div>
                              </td>
                            </tr>
                          </table>
                          <table class="wd-100 vw05 hc_center">
                            <tr>
                              <td colspan="3">＊標準類改訂</td>
                              <td>有 / 無</td>
                              <td colspan="8">有の場合は標準類Noを記入する事</td>
                              <td>製造課長</td>
                            </tr>
                            <tr>
                              <td class="wd-10">ＱＣ工程表</td>
                              <td class="wd-2-5"><label for="有" name="ＱＣ工程表" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="ＱＣ工程表" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">条　件　表</td>
                              <td class="wd-2-5"><label for="有" name="条　件　表" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="条　件　表" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">CP</td>
                              <td class="wd-2-5"><label for="有" name="CP" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="CP" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10" rowspan="4"></td>
                            </tr>
                            <tr>
                              <td class="wd-10">検査基準書</td>
                              <td class="wd-2-5"><label for="有" name="検査基準書" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="検査基準書" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">成形ﾁｪｯｸＰ</td>
                              <td class="wd-2-5"><label for="有" name="成形ﾁｪｯｸＰ" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="成形ﾁｪｯｸＰ" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">ＦＭＥＡ</td>
                              <td class="wd-2-5"><label for="有" name="ＦＭＥＡ" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="ＦＭＥＡ" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10"></td>
                            </tr>
                            <tr>
                              <td class="wd-10">作業手順書</td>
                              <td class="wd-2-5"><label for="有" name="作業手順書" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="作業手順書" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">仕上ﾁｪｯｸＰ</td>
                              <td class="wd-2-5"><label for="有" name="仕上ﾁｪｯｸＰ" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="仕上ﾁｪｯｸＰ" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">( )</td>
                              <td class="wd-2-5"><label for="有" name="(    )" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="(    )" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10"></td>
                            </tr>
                            <tr>
                              <td class="wd-10">限度見本</td>
                              <td class="wd-2-5"><label for="有" name="限度見本" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="限度見本" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">( )</td>
                              <td class="wd-2-5"><label for="有" name="(    )" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="(    )" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10">( )</td>
                              <td class="wd-2-5"><label for="有" name="(    )" class="vw05">有</label></td>
                              <td class="wd-2-5"><label for="無" name="(    )" class="vw05">無</label></td>
                              <td class="wd-15"></td>
                              <td class="wd-10"></td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>`;
          $('#msg_view').css('top', '30px');
          $('#msg_view').html(msg_view);
          $('#msg_view').css('width', '1344px');
          $('#msg_view').css('display', 'block');
          switch (parseInt($('#wbn_processing_position' + wbn_id).val())) {
            case 0:
            case 1:
            case 2:
            case 3:
              $('#msg_view').css('left', ((ww / 8) * $('#wbn_processing_position' + wbn_id).val() + this.offsetWidth + 25) + 'px');
              break;
            case 4:
            case 5:
            case 6:
            case 7:
              $('#msg_view').css('left', ((ww / 8) * ($('#wbn_processing_position' + wbn_id).val()) - msg_jquery.offsetWidth - 5) + 'px');
              break;
            default:
              $('#msg_view').css('left', ((ww / 8) * ($('#wbn_processing_position' + wbn_id).val()) - msg_jquery.offsetWidth - 5) + 'px');
          }
          if (parseInt($("#count_RFID" + wbn_id).val()) > 0) {
            fb_set_RFID_stock_confirmation();
          }
          set_checkbox('修理依頼書', $("#wbn_repair_sheet" + wbn_id).val());
          set_checkbox('欠点分類', $("#wbn_defect_type" + wbn_id).val());
          set_checkbox('発行理由', $("#wbn_reason" + wbn_id).val());
          set_checkbox('処置内容', $("#wbn_treatment" + wbn_id).val());
          set_checkbox('処置のみ', $("#wbn_treatment_only" + wbn_id).val());
          set_checkbox('効果', $("#wbn_effect" + wbn_id).val());
          set_checkbox('復旧確認で可', $("#wbn_restoration" + wbn_id).val());
          set_checkbox('長期確認が必要', $("#wbn_confirmation" + wbn_id).val());
          $('#div_quantity').html(fb_set_hakko("wbn_quantity", "position:absolute; margin-top: -53px; margin-left:12px;"));
          $('#div_licensor').html(fb_set_hakko("wbn_licensor"));
          $('#div_quality_control').html(fb_set_hakko('wbn_quality_control', "position:absolute;margin-top: -6px;margin-left:12px;"));
          if ($('#mfc_count' + wbn_id).val() > 0) {
            fb_is_attached('idbugcontent');
          }
          fb_set_input();
        },
        function() {
          $('#msg_view').css('display', 'none');
        }
      );
    }

    function fb_set_RFID_stock_confirmation() {
      $('.stock_confirmation_index').html('');
      obj_RFID_data_index = {};
      var datas = {
        ac: "Ajax_Get_RFID_Connect_Data",
        placename: placename,
        placeid: placeid,
        wbn_id: wbn_id,
      }
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          if (Object.keys(d.new).length > 0) {
            d.new.forEach((value, key) => {
              value.wicbn_rfid ??= value.hgpd_rfid;
              obj_RFID_data_index[value.wicbn_rfid] ??= {};
              obj_RFID_data_index[value.wicbn_rfid].input ??= [];
              obj_RFID_data_index[value.wicbn_rfid].output ??= [];
              if (value.wicbn_id && value.hgpd_process != "保留処理") {
								obj_RFID_data_index[value.wicbn_rfid].input = value;
							} else if (value.wicbn_id && value.hgpd_process == "保留処理"){
                let flg_check = true;
                if(obj_RFID_data_index[value.wicbn_rfid].output.length > 0){
                  obj_RFID_data_index[value.wicbn_rfid].output.forEach((val, k) => {
                    if(val.hgpd_id == value.hgpd_id) {
                      flg_check = false;
                      obj_RFID_data_index[value.wicbn_rfid].output[k] = value;
                    }
                  });
                }
                if (flg_check) obj_RFID_data_index[value.wicbn_rfid].output.push(value);
              }else if (!value.wicbn_id && value.hgpd_process == "保留処理"){
                obj_RFID_data_index[value.wicbn_rfid].input = value;
              }
            });
            fb_view_stock_confirmation();
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
        }
      });
    }

    function fb_view_stock_confirmation() {
      $('.stock_confirmation_index').html(`
														<table class="RFID_table wd-100 RFID_connect_view">
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
                                <td class="` + wbn_id + `RFID_input_00 wd-16 hc_left">完成品</td>
                                <td class="hc_center ` + wbn_id + `RFID_input_01"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_02"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_03"></td>
                                <td rowspan="3">
                                  <div class="div_licensor hc_center"></div>
                                </td>
                              </tr>
                              <tr>
                                <td class="` + wbn_id + `RFID_input_M0 wd-16 hc_left">仕掛品</td>
                                <td class="hc_center ` + wbn_id + `RFID_input_M1"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_M2"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_M3"></td>
                                <td rowspan="2" class="hc_center">
                                  <div class="div_quantity hc_center"></div>
                                </td>
                              </tr>
                              <tr>
                                <td class="` + wbn_id + `RFID_input_J0 wd-16 hc_left">蒸着品</td>
                                <td class="hc_center ` + wbn_id + `RFID_input_J1"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_J2"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_J3"></td>
                              </tr>
                              <tr class="` + wbn_id + `RFID_input_P">
																<td class="` + wbn_id + `RFID_input_P0 wd-16 hc_left">仕掛品(P)</td>
																<td class="hc_center ` + wbn_id + `RFID_input_P1"></td>
                                <td class="hc_center ` + wbn_id + `RFID_input_P2"></td>
																<td class="hc_center ` + wbn_id + `RFID_input_P3"></td>
																<td colspan="2">
																</td>
															</tr>
                            </table>`);
      var group_cav_process_key = [];
      let out_total_time = 0;
      Object.keys(obj_RFID_data_index).forEach(function(obj_keys) {
        let obj_input = obj_RFID_data_index[obj_keys].input;
        let cav_process_key = obj_input.wic_process_key + '_';
        switch (obj_input.wic_process_key) {
          case '0':
          case 'M':
          case 'J':
          case 'P':
            break;
          default:
            if (!group_cav_process_key.includes(cav_process_key)) {
              $('.stock_confirmation_index .RFID_table').append(` <tr class="RFID_input_` + obj_input.wic_process_key + `">
																												<td class="` + wbn_id + `RFID_input_` + obj_input.wic_process_key + `0 wd-16 hc_left">` + obj_input.wic_process_key + `</td>
																												<td class="hc_center ` + wbn_id + `RFID_input_` + obj_input.wic_process_key + `1"></td>
                                                        <td class="hc_center ` + wbn_id + `RFID_input_` + obj_input.wic_process_key + `2"></td>
																												<td class="hc_center ` + wbn_id + `RFID_input_` + obj_input.wic_process_key + `3"></td>
																												<td colspan="2">
																												</td>
																											</tr>`);
            }
            break;
        }
        $('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '1').html($('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '1').html() == '' ? obj_input.hgpd_qtycomplete : fb_parseInt($('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '1').html()) + parseInt(obj_input.hgpd_qtycomplete));
        if (!obj_input.wic_id || !obj_input.hgpd_id) {
          $('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '3').addClass('input_error');
        }
        if (obj_RFID_data_index[obj_keys].hasOwnProperty('output')) {

          obj_RFID_data_index[obj_keys].output.forEach(function(obj_output, index) {
            if(parseInt($('#wbn_processing_position' + wbn_id).val()) > 4){
              $('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '3').html($('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '3').html() == '' ? obj_output.hgpd_difactive : (fb_parseInt($('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '3').html()) + parseInt(obj_output.hgpd_difactive)));
            }
            if (!obj_output.wic_id || !obj_output.hgpd_id) {
              $('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '3').addClass('input_error');
            }
            $('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '2').html(fb_parseInt($('.' + wbn_id + 'RFID_input_' + obj_input.wic_process_key + '2').html()) + fb_parseInt(obj_output.wic_inspection));
            out_total_time += fb_diff_min(obj_output.hgpd_start_at, obj_output.hgpd_stop_at);
          });

        };
        if (!group_cav_process_key.includes(cav_process_key)) group_cav_process_key.push(cav_process_key);
      });
      if (!group_cav_process_key.includes("P_")) $('.' + wbn_id + 'RFID_input_P').css('display', 'none');
      $('.div_quantity').html(fb_set_hakko("wbn_quantity", "position:absolute; margin-top: -53px; margin-left:12px;"));
      $('.div_licensor').html(fb_set_hakko("wbn_licensor"));
      $('#total_time').val(out_total_time + "分");
    }
		function fb_parseInt(item) {
			if (item === undefined || item === null){
        return 0;
      }else if (item.length === 0) {
				return 0;
			} else {
				return parseInt(item);
			}
		}
    function fb_set_input() {
      $('textarea').css('background-color', 'white');
      $('input[type=text]').css('background-color', 'white');
      //$('input[readonly]').css('color', '#444444');
      $('textarea[readonly]').css('color', '#444444');
      $('input[readonly]').css('background-color', '#DDDDDD');
      $('textarea[readonly]').css('background-color', '#DDDDDD');
      $('input[disabled]').not('.RFID_data').css('background-color', 'transparent');
      $('input[disabled]').not('.RFID_data').css('border', 'none');
      $('.RFID_data input').css('background-color', 'white');
      $('.RFID_data input').css('border', 'block');
      $('textarea[disabled]').css('background-color', 'transparent');
      $('textarea[disabled]').css('border', 'none');
      $('.RFID_connect_view input[readonly]').css('background-color', 'transparent');
      $('.RFID_connect_view input[readonly]').css('border', 'none');
      $("input:radio[name!='tabs']").click(function() {
        let value = this.value;
        let name = this.name;
        $('input:radio[name="' + name + '"]').removeAttr('checked');
        $('input:radio[name="' + name + '"]').filter('[value="' + value + '"]').prop('checked', true);
      });
    }

    function fb_view_judgement(itemid) {
      judgement_click = true;
      wbn_id = itemid;
      let login_person = "";
      let title = "不具合判定画面 ー " + $('#position' + wbn_id).val();
      switch (parseInt($('#wbn_processing_position' + wbn_id).val())) {
        case 1:
          login_person = $('#wbn_decision_person' + wbn_id).val();
          break;
        case 2:
          login_person = $('#wbn_isolation_decision_login' + wbn_id).val();
          break;
        case 3:
          if (parseInt($('#wbn_rank' + wbn_id).val()) == 3) {
            login_person = $('#wbn_decision_person' + wbn_id).val();
          } else {
            login_person = $('#wbn_production_system_person' + wbn_id).val();
          }
          break;
        case 4:
          login_person = $('#wbn_BU_decision_person' + wbn_id).val();
          break;
        case 5:
          login_person = $('#wbn_licensor_person' + wbn_id).val();
          break;
        case 6:
          login_person = $('#wbn_treatment_login' + wbn_id).val();
          break;
        case 7:
          if (parseInt($('#wbn_rank' + wbn_id).val()) == 1) {
            login_person = $('#wbn_BU_decision_person' + wbn_id).val();
          } else if (parseInt($('#wbn_rank' + wbn_id).val()) == 3) {
            login_person = $('#wbn_treatment_login' + wbn_id).val();
          } else {
            login_person = $('#wbn_quantity_person' + wbn_id).val();
          }
          break;
      }

      $.ajax({
        type: 'GET',
        url: "MissingDefect/judgement",
        dataType: 'html',
        success: function(data) {
          var options = {
            title: title,
            autoOpen: false,
            width: "1344px",
            position: ["center", 30],
            buttons: [{
              text: "修正",
              id: "btn_fix",
              click: function(ev) {
                if (login_person == $('#login_person').val()) {
                  fb_back(id);
                } else {
                  switch (parseInt($('#wbn_processing_position' + wbn_id).val())) {
                    case 2:
                      alert("コメント修正必要の場合は「" + $('#wbn_isolation_decision_person' + wbn_id).val() + "」さんに連絡して下さい。\n\n ※　ログインアカウント：　" + login_person);
                      break;
                    case 6:
                      alert("コメント修正必要の場合は「" + $('#wbn_treatment_person' + wbn_id).val() + "」さんに連絡して下さい。\n\n ※　ログインアカウント：　" + login_person);
                      break;
                    default:
                      alert("コメント修正必要の場合は「" + login_person + "」さんに連絡して下さい。");
                  }
                }
              }
            }, {
              text: "削除",
              click: function(ev) {
                fb_detele();
              }
            }, {
              text: "閉じる",
              click: function(ev) {
                $(this).dialog("close");
              }
            }]
          }
          $("#message").html(data);
          $("#alert").dialog("option", options);
          $("#alert").dialog("open");
          judgement_click = false;
          return;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("ログインエラーですので、ブラウザを再起動してください。 ");
          return;
        }
      });
    }

    function fb_is_attached(itemid) {
      $("div").remove(".bugcontentimage");
      let margin_left = $('#' + itemid).width() - 35;
      $('#divbox').append('<div class="form-validation-field-1formError parentFormformCheck formError bugcontentimage" style="opacity: 0.87; position: absolute; top: ' + $('#' + itemid).position()['top'] + 'px; left: ' + $('#' + itemid).position()['left'] + 'px; right: initial; width:70px; margin-top: -8px; margin-left: ' + margin_left + 'px; display: block;"><input type="button" onclick="fb_get_image();" style="border-radius: 50%;box-shadow: 0.375 em 0.375 em 0 0 rgba(15, 28, 63, 0.125);text-align: center; width: 50px; height: 50px;font-size: 12px;" class="form-control hc___center btn_attached" value="添付" /></div>');
    }

    function fb_get_user() {
      var ww = $(window).width() - 5;
      var width = 980;
      $("#alert_dept").dialog({
        autoOpen: false,
        width: width,
        modal: true,
        title: 'DAS入力担当者を選択して下さい。',
        position: [((ww - width) / 2), 40],
        buttons: []
      });
      $.getJSON("/common/AjaxUserSelect?action=user&target=setUser4Data&gp1=" + decodeURIComponent(gp1) + "&gp2=" + decodeURIComponent(gp2) /*+"&callback=?"*/ , function(data) {
        $("#alert_dept").html(data);
      });
      $("#alert_dept").dialog("open");
    }

    function fb_format_date(date, format) {
      if (!date || date == '') return '';
      var newdate = new Date(date);
      var weekday = ["日", "月", "火", "水", "木", "金", "土"];
      if (!format) {
        format = 'YYYY/MM/DD(WW) hh:mm:ss'
      }
      format = format.replace(/YYYY/g, newdate.getFullYear());
      format = format.replace(/MM/g, ('0' + (newdate.getMonth() + 1)).slice(-2));
      format = format.replace(/DD/g, ('0' + newdate.getDate()).slice(-2));
      format = format.replace(/WW/g, weekday[newdate.getDay()]);
      format = format.replace(/hh/g, ('0' + newdate.getHours()).slice(-2));
      format = format.replace(/mm/g, ('0' + newdate.getMinutes()).slice(-2));
      format = format.replace(/ss/g, ('0' + newdate.getSeconds()).slice(-2));
      return format;
    }

    function fb_setUser4Data(name,ppro,gp1,gp2) {
      $("#lb_person").html(name);
      $("#input_das_person").val(name);
      if ($('#html_treatment').length && $("#wbn_processing_position" + wbn_id).val() == 5) {
        $('#html_treatment').html(fb_get_hakko($("#input_das_person").val(), today));
      }
      $('#usercord').val(ppro);
      $('#gp1').val(gp1);
      $('#gp2').val(gp2);
      $("#alert_dept").html('');
      $("#alert_dept").dialog("close");
      fb_loading(false);
    }

    function fb_register() {
      url_register = '/MissingDefect/register?placeid=' + placeid;
      window.open(url_register, '_blank');
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

    function set_checkbox(name, value) {
      if (isNaN(value)) {
        $('input[name="' + name + '"]').filter('[value="' + value + '"]').prop('checked', true);
        $('input[name="' + name + '"]').filter('[value="' + value + '"]').prop('disabled', false);
      }
    }

    function fb_loading(flag) {
      $('#loading').remove();
      if (!flag) return;
      $('<div id="loading" />').appendTo('body');
    }

    function fb_get_hakko(name, date, style = "", situation = 9) {
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

    function fb_set_hakko(name_part, style = "", situation = 9) {
      let name = $("#" + name_part + "_person" + wbn_id).val();
      let date = $("#" + name_part + "_date" + wbn_id).val();
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

    function fb_setget_hakko(name_part, situation, style = "") {
      let div_hakko = '';
      let set_name = $("#" + name_part + "_person" + wbn_id).val();
      let set_date = $("#" + name_part + "_date" + wbn_id).val();
      if (set_name) {
        div_hakko = '<div class="hanko" style="' + style + '; "><span padding-top: 10px;> ' + set_name + ' </span><hr noshade style="width: 95%;"><span> ' + set_date + ' </span><hr noshade  style="width: 95%;"><span> ' + situation + ' </span></div>';
      } else {
        div_hakko = '';
      }
      return div_hakko;
    }
  </script>
</head>

<body>
  <div id="alert" style="text-align: center; font-size: 16px; ">
    <div id="message"></div>
  </div>
  <div id="alert_dept" style="text-align: center; font-size: 16px;">
  </div>
  <div class="bigclass">
    <?php foreach ($list_place as $key => $value) { ?>
      <div class="smallclass <?php echo $value ?> wd-12-5 bgc-<?php echo $key ?>">
        <h1 style="font-size: 1.4vw;"><?php echo $list_place_name_H1[$key] ?></h1>
        <h4 style="font-size: 1vw;"><?php echo $list_place_name_H4[$key] ?><span class="count<?php echo $value ?>"></span></h4>
      </div>
    <?php } ?>
    <div id="msg_view"></div>
  </div>
</body>

</html>
