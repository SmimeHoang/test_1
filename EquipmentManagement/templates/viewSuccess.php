<?php
if (!$placeid) {

  slot('h1', '<h1> 稼働状態配置図 | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/EquipmentManagement/view?placeid=1000079">野洲工場</a>
      <a class="blue" href="/EquipmentManagement/view?placeid=1000073">山崎工場</a>
      <a class="blue" href="/EquipmentManagement/view?placeid=1000125">NPG</a>
    </div>
    ';
  return;
}
if (!$host) {
  slot('h1', '<h1> 温度・湿度監視システム | Nalux');
  $temp_hum = '';
  $electricity = '';
  foreach ($setting as $key => $value) {
    if(strpos($value['ems_host'], "SB")){
      $temp_hum .= '<a class="blue" href="'.$_SERVER['REQUEST_URI'].'&host=' . $value['ems_host'] . '">温度・湿度 ' . $value['ems_no'] . '</a>';
    }else if(strpos($value['ems_host'], "EL")){
      $electricity .=  '<a class="blue" href="'.$_SERVER['REQUEST_URI'].'host=' . $value['ems_host'] . '">静電気 ' . $value['ems_no'] . '</a>';
    }
  }
  print '<div id="bot">';
  print $temp_hum;
  print '</div>';
  print '<div id="bot">';
  print $electricity;
  print '</div>';
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

use_javascript("EquipmentManagement/chart.min.js");
use_javascript("EquipmentManagement/loader.js");

$btn = '<div class="header_sel" style="float:left; margin-left:10px;" >';
$btn .= '<label for="sel_start">表示する成形機</label>
         <select id="sl_molding_machine">';
foreach ($emd_data as $key => $value) {
  $btn .= "<option value='" . $value['emd_no'] . "'>" . $value['emd_no'] . "/".$value['emd_name'] ."</option>\n";
}
$btn .= '</select><label for="sel_start">を選択して下さい。</label>
        </div>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>【NALUX】温度湿度監視システム</title>

  <style>
    * {box-sizing: border-box;}
    body {font-size: 0.8vw;}
    .header_sel select {width: 150px;margin: 1px 0;display: inline-block;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box;height: 28px;}
    .main input[type=text],
    .main input[type=date],
    .main select {width: 100%; padding: 12px 20px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;}
    .enable_btn {color: white; width: 90%; padding: 14px 20px; margin: 8px 0;border: none; border-radius: 4px;cursor: pointer;text-align: center;}
    .enable_btn:hover {color: orange; font-weight: bold;}
    .btn_button {background-color: #4CAF50; width: 90%; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer; text-align: center;}
    #btn_sel {background-color: #4CAF50;}
    #btn_setting {background-color: Teal;}
    #btn_download {background-color: Maroon;}
    #btn_clean {background-color: Navy;}
    .head_find {border-radius: 5px; background-color: #f2f2f2; padding-left: 20px; width: 35%; float: left; border-style: groove; height: 105px;}
    .head_set {border-radius: 5px; padding-left: 20px; background-color: #f2f2f2; width: 65%; float: left; border-style: groove; height: 105px;}
    .w3_sel {border-radius: 5px; padding: 5px; width: 33%; float: left; text-align: center;}
    .transfer {border-radius: 5px; padding: 5px; width: 30%; float: left; text-align: center;}
    #loading {
      width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0;
      background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85;
      background-image: url(http://track.yasu.nalux.local/images/loading-1.gif);
      background-position: center center; background-repeat: no-repeat; background-attachment: fixed;}
    #temperature {
      font-size: 50px; width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0;
      background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85;
      opacity: 0.85; background-color: aliceblue; background-position: center center; background-repeat: no-repeat;
      background-attachment: fixed; text-align: center; vertical-align: middle; font-weight: bolder; padding: 5%;}
    #loading img {max-width: 100%; height: auto;}
    .icon_settiing {right: 0px; position: fixed; text-align: center; margin: 10px 20px 10px 0px; cursor: pointer;}
    .icon_settiing img{width: 40px;height: 40px;}
    .tab_settiing {right: 0px; position: fixed; width: 600px; height: 400px; margin: 10px 20px 10px 0px; background-color: #ccc; display: none;}
    .gauge {display: inline-block;}
    @keyframes glowing {0% {-webkit-box-shadow: 0 0 3px #004A7F;}50% {-webkit-box-shadow: 0 0 20px red;}100% {-webkit-box-shadow: 0 0 3px #004A7F;}}
    .error-animation {/* background-color: red; */animation: glowing 0.5s 0.5s ease-in-out infinite alternate;}
  </style>
</head>

<body>
  <div class="main" onmousemove="mouseMove()">
    <div class="head_find">
      <div class="w3_sel">
        <label for="sel_start">開始日時</label>
        <input type="date" id="sel_start" />
      </div>
      <div class="w3_sel">
        <label for="sel_end">終了日時</label>
        <input type="date" id="sel_end" />
      </div>
      <div class="w3_sel" style="padding: 15px;">
        <input type="submit" id="btn_sel" class="enable_btn" value="表示">
      </div>
    </div>
    <div class="head_set">
      <div class="w3_sel" style="width:10%;">
        <label for="machine_n0">成形機</label>
        <input type="text" class="setting" id="machine_no" />
      </div>
      <div class="w3_sel" style="width:20%;">
        <label for="machine_name">品名</label>
        <input type="text" class="setting" id="machine_name" />
      </div>
      <div class="w3_sel" style="width:10%;">
        <label for="timer_transfer">転送タイマー</label>
        <select id="timer_transfer" class="setting">
          <option value="1">1分</option>
          <option value="2">2分</option>
          <option value="3">3分</option>
          <option value="4">4分</option>
          <option value="5">5分</option>
          <option value="6">6分</option>
          <option value="7">7分</option>
          <option value="8">8分</option>
          <option value="9">9分</option>
          <option value="10">10分</option>
          <option value="11">11分</option>
          <option value="12">12分</option>
          <option value="13">13分</option>
          <option value="14">14分</option>
          <option value="15">15分</option>
          <option value="16">16分</option>
          <option value="17">17分</option>
          <option value="18">18分</option>
          <option value="19">19分</option>
          <option value="20">20分</option>
        </select>
      </div>
      <div class="w3_sel" style="width:15%;">
        <label>　</label><br>
        <input type="submit" id="btn_setting" class="setting enable_btn" value="設定">
      </div>
      <div class="w3_sel" style="width:15%;">
        <label>データベース</label><br>
        <input type="submit" class="enable_btn" id="btn_download" value="ロード">
      </div>
      <!-- <div class="w3_sel" style="width:10%;">
        <label>データクリア</label><br>
        <input type="submit" id="btn_clean" value="クリア">
      </div> -->
      <div class="w3_sel" style="width:30%; padding: 10px; text-align: center;">
        <label id="now_time"></label><br>
        <label id="waiting_time" style="margin-top: 5px;"></label><br>
        <label id="memory" style="margin-top: 5px;"></label>
      </div>
    </div>
    <div style="height:400px; width:100%; float:left;  padding:0.5%">
      <canvas id="my_chart" width="95%" height="400"></canvas>
    </div>
    <div style="height:400px; width:100%; float:left;  padding:0.5%">
      <div style="height:100%; width:50%; float: left;">
        <canvas id="my_chart_sps30_1" width="95%" height="100%"></canvas>
      </div>
      <div style="height:100%; width:50%; float:left;  padding:0.5%">
        <canvas id="my_chart_sps30_2" width="95%" height="100%"></canvas>
      </div>
    </div>
  </div>
  <div class="icon_settiing">
    <img onclick="fb_show_bars_tab();" class="icon_rfid_data" src="/MissingDefect/GetFile?menu=img&mfc_id=11"/>
  </div>
  <div class="tab_settiing">
    <div class="w3_sel" style="width:50%;">
      <center>
        <h3>
          チャート設定
        </h3>
      </center>
      <div class="w3_sel" style="width:100%;">
        <label for="time_stepSize">目盛り線の間の刻み幅</label>
        <select id="time_stepSize">
          <option value="1">1 Step</option>
          <option value="2">2 Step</option>
          <option value="3">3 Step</option>
          <option value="4">4 Step</option>
          <option value="5">5 Step</option>
          <option value="6">6 Step</option>
          <option value="7">7 Step</option>
          <option value="8">8 Step</option>
          <option value="9">9 Step</option>
          <option value="10">10 Step</option>
          <option value="11">11 Step</option>
          <option value="12">12 Step</option>
          <option value="13">13 Step</option>
          <option value="14">14 Step</option>
          <option value="15">15 Step</option>
          <option value="16">16 Step</option>
          <option value="17">17 Step</option>
          <option value="18">18 Step</option>
          <option value="19">19 Step</option>
          <option value="20">20 Step</option>
        </select>
      </div>
      <div class="w3_sel" style="width:100%;">
        <label>データのプロット方法</label><br>
        <input type="radio" id="distribution_linear" name="distribution" value="linear" checked />
        <label class="radio" for="distribution_linear" name="distribution">linear</label>
        <input type="radio" id="distribution_series" name="distribution" value="series" />
        <label class="radio" for="distribution_series" name="distribution">series</label>
      </div>
      <div class="w3_sel" style="width:100%;">
        <label id="report_label">レポートグラフを作成</label><br>
        <input type="number" style="font-size: 0.8vw; height:32px; text-align:right;" id="chartjs_offset_date" />　分
        <input type="submit" class="btn_button" id="btn_chartjs_start" onclick="fb_chartjs_start();" value="新規作成">
        <input type="submit" class="btn_button" id="btn_chartjs_view" onclick="fb_chartjs_view();" value="表示" disabled>
      </div>
      <!-- <div class="w3_sel" style="width:100%;">
      <label>目盛り刻み生成方法</label><br>
      <input type="radio" id="source_auto" name="ticks_source" value="auto" checked />
      <label class="radio" for="source_auto" name="ticks_source">auto</label>
      <input type="radio" id="source_data" name="ticks_source" value="data" />
      <label class="radio" for="source_data" name="ticks_source">data</label>
      <input type="radio" id="source_labels" name="ticks_source" value="labels" />
      <label class="radio" for="source_labels" name="ticks_source">labels</label>
      </div> -->

    </div>
    <div class="w3_sel" style="width:50%;">
      <center>
        <h3>
          埃 単位
        </h3>
      </center>
      <div class="w3_sel" style="width:100%; text-align: left; margin-left: 10px;">
        <label style="font-size: 150%;">埃（PM）- 質量 (μg/m<sup>3</sup>)</label><br>
        <div style="margin-left: 10px;">
          <label>PM1.0 : 0.3μm ～ 1.0μm</label><br>
          <label>PM2.5 : 0.3μm ～ 2.5μm</label><br>
          <label>PM4.0 : 0.3μm ～ 4.0μm</label><br>
          <label>PM10.0: 0.3μm ～ 10.0μm</label><br>
        </div>
        <label style="font-size: 150%;">埃（NC）- 数 (粒子/cm<sup>3</sup>)</label><br>
        <div style="margin-left: 10px;">
          <label>NC0.5 : 0.3μm ～ 0.5μm</label><br>
          <label>NC1.0 : 0.3μm ～ 1.0μm</label><br>
          <label>NC2.5 : 0.3μm ～ 2.5μm</label><br>
          <label>NC4.0 : 0.3μm ～ 4.0μm</label><br>
          <label>NC10.0: 0.3μm ～ 10.0μm</label><br>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  var array_data = {
    "DATE": [],
    "TEMP": [],
    "HUM": [],
    "PM1": [],
    "PM2": [],
    "PM4": [],
    "PM10": [],
    "NC05": [],
    "NC1": [],
    "NC2": [],
    "NC4": [],
    "NC10": []
  };
  var array_save_data = {
    "DATE": [],
    "TEMP": [],
    "HUM": [],
    "PM1": [],
    "PM2": [],
    "PM4": [],
    "PM10": [],
    "NC05": [],
    "NC1": [],
    "NC2": [],
    "NC4": [],
    "NC10": []
  };
  var placeid = '<?php echo $placeid; ?>';
  var ems_data = <?php echo htmlspecialchars_decode($ems_data); ?>;
  var var_start_save_data = 0,
    int_save_time = 0,
    int_chartjs_offset_date = 0,
    waiting_temperature = 0,
    waitingtime, sendtime;
  var temperature_data = 30.5,
    humidity_data = 60.35;
  var setting_password = "<?php echo $manager_password; ?>",
    setting_password_clean = "no_password",
    input_password = "no_password";
  // WebSocket 通信を開始する

  $(window).load(function() {
    fb_loading(true);
    const sel_start_date = new Date();
    sel_start_date.setFullYear(sel_start_date.getFullYear(), sel_start_date.getMonth() - 1);
    $('#sel_start').val(fb_format_date(sel_start_date,"YYYY-MM-DD"));
    $('#sel_end').val(fb_format_date(new Date(),"YYYY-MM-DD"));
    $('.btn_def').button();
    setInterval("fb_get_setting();", 2000);
    setInterval("fb_waitingtime();", 1000);
    if (Object.keys(ems_data).length > 0) {
      $('#machine_no').val(ems_data.ems_no);
      $('#machine_name').val(ems_data.ems_name);
      $("#timer_transfer").val(parseInt(ems_data.ems_run_time))
    }
    if (localStorage.getItem("chartjs_data_save" + ems_data.ems_host)) {
      array_save_data = JSON.parse(localStorage.getItem("chartjs_data_save" + ems_data.ems_host));
      $("#btn_chartjs_view").removeClass("hide_btn").addClass("enable_btn");
      $("#btn_chartjs_view").prop("disabled", false);
      $("#btn_chartjs_view").val("(" + fb_format_date(new Date(array_save_data.DATE[0]), "YYYY/MM/DD") + ")保存済のグラフを表示");
      $("#btn_chartjs_start").removeClass("hide_btn").addClass("enable_btn");
    }
    $('.main').on('click', function() {
      $('.tab_settiing').css('display', 'none');
    });
    $(document).on('input', '#chartjs_offset_date', function(e) {
      $(this).removeClass('error-animation');
    });
    $('#time_stepSize').change(function() {
      my_chart.options.scales.xAxes[0].time.stepSize = $('#time_stepSize').val();
      my_chart_sps30_1.options.scales.xAxes[0].time.stepSize = $('#time_stepSize').val();
      my_chart_sps30_2.options.scales.xAxes[0].time.stepSize = $('#time_stepSize').val();
      my_chart.update();
      my_chart_sps30_1.update();
      my_chart_sps30_2.update();
    });
    $('input[name="distribution"]:radio').change(function() {
      my_chart.options.scales.xAxes[0].distribution = $(this).val();
      my_chart_sps30_1.options.scales.xAxes[0].distribution = $(this).val();
      my_chart_sps30_2.options.scales.xAxes[0].distribution = $(this).val();
      my_chart.update();
      my_chart_sps30_1.update();
      my_chart_sps30_2.update();
    });
    $('input[name="ticks_source"]:radio').change(function() {
      my_chart.options.scales.xAxes[0].ticks.source = $(this).val();
      my_chart_sps30_1.options.scales.xAxes[0].ticks.source = $(this).val();
      my_chart_sps30_2.options.scales.xAxes[0].ticks.source = $(this).val();
      my_chart.update();
      my_chart_sps30_1.update();
      my_chart_sps30_2.update();
    });
    $('#machine_no').change(function() {
      $("#btn_setting").css("background-color", "Teal");
      $("#btn_setting").prop("disabled", false);
    });
    $('#timer_transfer').change(function() {
      $("#btn_setting").css("background-color", "Teal");
      $("#btn_setting").prop("disabled", false);
    });
    $('#sl_molding_machine').change(function() {
      if (ems_data.ems_no == $('#sl_molding_machine').val()) {
        $("#btn_setting").css("background-color", "Teal");
        $(".setting").prop("disabled", false);
      } else {
        $("#btn_setting").css("background-color", "transparent");
        $(".setting").prop("disabled", true);
      }
      fb_environment_dataload($('#sl_molding_machine').val());
    });
    $('#btn_download').on('click', function() {
      if (fb_is_password()) {
        var csvContent = "data:text/csv;charset=utf-8,";
        Object.keys(array_data).forEach(function(name, index) {
          dataString = name + "," + array_data[name].join(",");
          csvContent += index <= Object.keys(array_data).length ? dataString + "\n" : dataString;
        });
        var encodedUri = encodeURI(csvContent);
        window.open(encodedUri);
      }
    });
    $('#btn_sel').on('click', function() {
      if (var_start_save_data == 2) {
        var_start_save_data = 1;
        $("#btn_chartjs_view").removeClass("hide_btn").addClass("enable_btn");
        $("#btn_chartjs_view").prop("disabled", false);
      }
      var sel_start = new Date("1900/01/01");
      var sel_end = new Date();
      if ($('#sel_start').val() != "") {
        sel_start = new Date($('#sel_start').val() + " 00:00:01");
      }
      if ($('#sel_end').val() != "") {
        sel_end = new Date($('#sel_end').val() + " 23:59:59");
      }
      chart_clean();
      for (let i = 0; i < array_data.DATE.length; i++) {
        if ((sel_start <= new Date(array_data.DATE[i])) && (new Date(array_data.DATE[i]) <= sel_end)) {
          my_chart.data.labels.push(array_data.DATE[i]);
          my_chart.data.datasets[0].data.push(array_data.TEMP[i]);
          my_chart.data.datasets[1].data.push(array_data.HUM[i]);
          my_chart_sps30_1.data.labels.push(array_data.DATE[i]);
          my_chart_sps30_1.data.datasets[0].data.push(array_data.PM1[i]);
          my_chart_sps30_1.data.datasets[1].data.push(array_data.PM2[i]);
          my_chart_sps30_1.data.datasets[2].data.push(array_data.PM4[i]);
          my_chart_sps30_1.data.datasets[3].data.push(array_data.PM10[i]);
          my_chart_sps30_2.data.labels.push(array_data.DATE[i]);
          my_chart_sps30_2.data.datasets[0].data.push(array_data.NC05[i]);
          my_chart_sps30_2.data.datasets[1].data.push(array_data.NC1[i]);
          my_chart_sps30_2.data.datasets[2].data.push(array_data.NC2[i]);
          my_chart_sps30_2.data.datasets[3].data.push(array_data.NC4[i]);
          my_chart_sps30_2.data.datasets[4].data.push(array_data.NC10[i]);
        }
      }
      update_chart();
    });
    $('#btn_setting').on('click', function() {
      if (fb_is_password()) {
        if (!confirm('本当に設定ですか。')) {
          $("#timer_transfer").val(sendtime);
          return;
        }
        //waitingtime = parseInt(waitingtime - (sendtime - parseInt($("#timer_transfer").val() * 60)));
        sendtime = parseInt($("#timer_transfer").val());
        var datas = {
          ac: "Ajax_Set",
          placeid: placeid,
          host: ems_data.ems_host,
          no: $('#machine_no').val(),
          name: $('#machine_name').val(),
          run_time: $("#timer_transfer").val(),
        }
        $.ajax({
          type: 'GET',
          url: "",
          dataType: 'TEXT',
          data: datas,
          success: function(d) {
            if (d != "OK") {
              alert("設定できません");
              return;
            } else {
              $("#btn_setting").css("background-color", "transparent");
              $("#btn_setting").prop("disabled", true);
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
            return;
          }
        });
      }
    });
    $('#btn_clean').on('click', function() {
      if (fb_is_password()) {
        if (!confirm('本当にデータベースをクリアですか。')) {
          $("#timer_transfer").val(sendtime);
          return;
        }
        array_data = {
          "DATE": [],
          "TEMP": [],
          "HUM": [],
          "PM1": [],
          "PM2": [],
          "PM4": [],
          "PM10": [],
          "NC05": [],
          "NC1": [],
          "NC2": [],
          "NC4": [],
          "NC10": []
        };
        chart_clean();
        update_chart();
      }
    });
  });

  function fb_is_password() {
    if (setting_password != input_password) {
      input_password = prompt("設定のパスワードを入力してください。");
      if (setting_password != input_password) {
        alert("パスワードが間違っています。");
        return false;
      } else {
        return true;
      }
    } else {
      return true;
    }
  }

  function fb_environment_dataload(item) {
    array_data = {
      "DATE": [],
      "TEMP": [],
      "HUM": [],
      "PM1": [],
      "PM2": [],
      "PM4": [],
      "PM10": [],
      "NC05": [],
      "NC1": [],
      "NC2": [],
      "NC4": [],
      "NC10": []
    };
    var datas = {
      ac: "Ajax_GetData",
      host: ems_data.ems_host,
      no: item,
      placeid: placeid,
    }
    $.ajax({
      type: 'GET',
      url: "",
      dataType: 'json',
      data: datas,
      success: function(d) {
        my_chart.data.labels = {};
        my_chart.data.datasets[0].data = {};
        my_chart.data.datasets[1].data = {};
        my_chart_sps30_1.data.labels = {};
        my_chart_sps30_1.data.datasets[0].data = {};
        my_chart_sps30_1.data.datasets[1].data = {};
        my_chart_sps30_1.data.datasets[2].data = {};
        my_chart_sps30_1.data.datasets[3].data = {};
        my_chart_sps30_2.data.labels = {};
        my_chart_sps30_2.data.datasets[0].data = {};
        my_chart_sps30_2.data.datasets[1].data = {};
        my_chart_sps30_2.data.datasets[2].data = {};
        my_chart_sps30_2.data.datasets[3].data = {};
        my_chart_sps30_2.data.datasets[4].data = {};
        d.forEach((value, key) => {
          array_data.DATE.push(value["emd_dt"]);
          array_data.TEMP.push(value["emd_temp"]);
          array_data.HUM.push(value["emd_hum"]);
          array_data.PM1.push(value["emd_PM_1p0"] ??= 0);
          array_data.PM2.push(value["emd_PM_2p5"] ??= 0);
          array_data.PM4.push(value["emd_PM_4p0"] ??= 0);
          array_data.PM10.push(value["emd_PM_10p0"] ??= 0);
          array_data.NC05.push(value["emd_NC_0p5"] ??= 0);
          array_data.NC1.push(value["emd_NC_1p0"] ??= 0);
          array_data.NC2.push(value["emd_NC_2p5"] ??= 0);
          array_data.NC4.push(value["emd_NC_4p0"] ??= 0);
          array_data.NC10.push(value["emd_NC_10p0"] ??= 0);
        });
        console.log(array_data);
        if (var_start_save_data < 2) {
          if (array_data.DATE.length > 0) {
            my_chart.data.labels = array_data.DATE;
            my_chart.data.datasets[0].data = array_data.TEMP;
            temperature_data = parseFloat(array_data.TEMP[array_data.TEMP.length - 1]);
            my_chart.data.datasets[1].data = array_data.HUM;
            humidity_data = parseFloat(array_data.HUM[array_data.HUM.length - 1]);
            my_chart_sps30_1.data.labels = array_data.DATE;
            my_chart_sps30_1.data.datasets[0].data = array_data.PM1;
            my_chart_sps30_1.data.datasets[1].data = array_data.PM2;
            my_chart_sps30_1.data.datasets[2].data = array_data.PM4;
            my_chart_sps30_1.data.datasets[3].data = array_data.PM10;
            my_chart_sps30_2.data.labels = array_data.DATE;
            my_chart_sps30_2.data.datasets[0].data = array_data.NC05;
            my_chart_sps30_2.data.datasets[1].data = array_data.NC1;
            my_chart_sps30_2.data.datasets[2].data = array_data.NC2;
            my_chart_sps30_2.data.datasets[3].data = array_data.NC4;
            my_chart_sps30_2.data.datasets[4].data = array_data.NC10;
          }
        }
        update_chart();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
        return;
      }
    });
    fb_loading(false);
  }

  function mouseMove() {
    waiting_temperature = 0;
    fb_temperature(false);
  }
  var graph_data = {
    labels: [], // X軸のデータ (時間)
    datasets: [{
        type: 'line',
        data: [],
        label: '温度',
        fill: false,
        yAxisID: 'y-axis-1',
        borderColor: "#ff0000",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: '湿度',
        fill: false,
        yAxisID: 'y-axis-2',
        borderColor: "Blue",
        borderWidth: 2,
        radius: 0,
      }
    ]
  };
  var graph_options = {
    title: {
      display: true,
      text: '温度・湿度'
    },
    maintainAspectRatio: false,
    scales: {
      yAxes: [{
          id: 'y-axis-1',
          type: 'linear',
            position: 'left',
          ticks: {
            beginAtZero: true,
            color: 'blue',
            labelString: '温度(℃)',
            color: 'blue',
            callback: (value) => `${value}℃`,
          }

        },
        {
          id: 'y-axis-2',
          ticks: {
            labelString: '湿度(%)',
            beginAtZero: true,
            color: "#ff0000",
            callback: (value) => `${value}%`,
          },
          position: 'right',
          gridLines: {
            display: false,
          }
        },
      ],
      xAxes: [{
        type: 'time',
        distribution: 'linear',
        ticks: {
          source: 'auto'
        },
      }],
    }
  };
  var ctx_temp = document.getElementById("my_chart").getContext('2d');
  var my_chart = new Chart(ctx_temp, {
    type: Object,
    data: graph_data,
    options: graph_options
  });

  var graph_data_sps30_1 = {
    labels: [], // X軸のデータ (時間)
    datasets: [{
        type: 'line',
        data: [],
        label: 'PM 1.0',
        fill: false,
        borderColor: "Maroon",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: 'PM 2.5',
        fill: false,
        borderColor: "Green",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: 'PM 4.0',
        fill: false,
        borderColor: "Blue",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: 'PM 10.0',
        fill: false,
        borderColor: "#F4A460",
        borderWidth: 2,
        radius: 0,
      }
    ]
  };
  var graph_options_sps30_1 = {
    maintainAspectRatio: false,
    title: {
      display: true,
      text: '埃（PM）- 質量  (μg/m3)'
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          callback: (value) => `${value} μg/m3`,
        }
      }, ],
      xAxes: [{
        type: 'time',
        distribution: 'linear',
        ticks: {
          source: 'auto'
        },
      }],
    }
  };
  var ctx_temp_sps30_1 = document.getElementById("my_chart_sps30_1").getContext('2d');
  var my_chart_sps30_1 = new Chart(ctx_temp_sps30_1, {
    type: Object,
    data: graph_data_sps30_1,
    options: graph_options_sps30_1
  });

  var graph_data_sps30_2 = {
    labels: [], // X軸のデータ (時間)
    datasets: [{
        type: 'line',
        data: [],
        label: 'NC 0.5',
        fill: false,
        borderColor: "Navy",
        borderWidth: 2,
        radius: 0,
      }, {
        type: 'line',
        data: [],
        label: 'NC 1.0',
        fill: false,
        borderColor: "rgba(254,97,132,0.8)",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: 'NC 2.5',
        fill: false,
        borderColor: "#ECAB53",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: 'NC 4.0',
        fill: false,
        borderColor: "#800080",
        borderWidth: 2,
        radius: 0,
      },
      {
        type: 'line',
        data: [],
        label: 'NC 10.0',
        fill: false,
        borderColor: "#00FFFF",
        borderWidth: 2,
        radius: 0,
      }
    ]
  };
  var graph_options_sps30_2 = {
    maintainAspectRatio: false,
    title: {
      display: true,
      text: '埃（NC）- 数  (粒子/cm3)'
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          callback: (value) => `${value}粒/cm3`,
        }
      }, ],
      xAxes: [{
        type: 'time',
        distribution: 'linear',
        ticks: {
          source: 'auto'
        },
      }],
    }
  };
  var ctx_temp_sps30_2 = document.getElementById("my_chart_sps30_2").getContext('2d');
  var my_chart_sps30_2 = new Chart(ctx_temp_sps30_2, {
    type: Object,
    data: graph_data_sps30_2,
    options: graph_options_sps30_2
  });

  function chart_clean() {
    my_chart.data.labels = [];
    my_chart.data.datasets[0].data = [];
    my_chart.data.datasets[1].data = [];
    my_chart_sps30_1.data.labels = [];
    my_chart_sps30_1.data.datasets[0].data = [];
    my_chart_sps30_1.data.datasets[1].data = [];
    my_chart_sps30_1.data.datasets[2].data = [];
    my_chart_sps30_1.data.datasets[3].data = [];
    my_chart_sps30_2.data.labels = [];
    my_chart_sps30_2.data.datasets[0].data = [];
    my_chart_sps30_2.data.datasets[1].data = [];
    my_chart_sps30_2.data.datasets[2].data = [];
    my_chart_sps30_2.data.datasets[3].data = [];
    my_chart_sps30_2.data.datasets[4].data = [];
  }

  function fb_loading(flag) {
    $('#loading').remove();
    if (!flag) return;
    $('<div id="loading" />').appendTo('body');
  }

  function fb_show_bars_tab() {
    $('.tab_settiing').css('display', 'block');
  }

  function update_chart() {
    var date_max = new Date(my_chart.data.labels[my_chart.data.labels.length - 1]);
    var date_min = new Date(my_chart.data.labels[0]);
    if (date_max.getFullYear() - date_min.getFullYear() > 1) {
      my_chart.options.scales.xAxes[0].time.unit = 'year';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'year';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'year';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'year': 'YYYY年',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'year': 'YYYY年',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'year': 'YYYY年',
      };
    } else if (date_max.getFullYear() - date_min.getFullYear() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'month';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'month';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'month';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
    } else if (date_max.getMonth() - date_min.getMonth() > 1) {
      my_chart.options.scales.xAxes[0].time.unit = 'month';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'month';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'month';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
    } else if (date_max.getMonth() - date_min.getMonth() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'day';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'day';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'day';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
    } else if (date_max.getDate() - date_min.getDate() > 1) {
      my_chart.options.scales.xAxes[0].time.unit = 'day';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'day';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'day';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
    } else if (date_max.getDate() - date_min.getDate() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'hour';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'hour';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'hour';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'D日 H時',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'D日 H時',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'D日 H時',
      };
    } else if (date_max.getHours() - date_min.getHours() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'hour';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'hour';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'hour';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'H時',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'H時',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'H時',
      };
    } else if (date_max.getMinutes() - date_min.getMinutes() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'minute';
      my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'minute';
      my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'minute';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'minute': 'H時m',
      };
      my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
        'minute': 'H時m',
      };
      my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
        'minute': 'H時m',
      };
    } else {
      my_chart.options = graph_options;
      my_chart_sps30_1.options = graph_options_sps30_1;
      my_chart_sps30_2.options = graph_options_sps30_2;
    }
    my_chart.options.title.text = '温度・湿度';
    my_chart.update();
    my_chart_sps30_1.update();
    my_chart_sps30_2.update();
  }

  function fb_waitingtime() {
    waiting_temperature++;
    $('#now_time').html(fb_format_date(new Date(), "YYYY/MM/DD(WW) hh:mm:ss"));
    waitingtime = (sendtime * 60) - (parseInt(fb_format_date(new Date(), "mm")) % sendtime * 60 + parseInt(fb_format_date(new Date(), "ss")));
    if (ems_data.ems_no == $('#sl_molding_machine').val()) {
      if (waitingtime > 1) {
        $('#waiting_time').html("後" + waitingtime + "秒 チャートを更新する");
      } else {
        $('#waiting_time').html("チャートを更新する");
        fb_environment_dataload(ems_data.ems_no);
      }
    } else {
      $('#waiting_time').html("別の成形機の選択中");
      fb_loading(false);
    }

    if (waiting_temperature > 60) {
      fb_temperature(true);
      $('#now_time_temp').html(fb_format_date(new Date(), "YYYY/MM/DD(WW) hh:mm:ss"));
    }
    if (var_start_save_data > 0) {
      int_save_time++;
      $("#report_label").html("レポートグラフを作成中 (" + Math.floor(int_save_time / 60) + "分" + int_save_time % 60 + "秒)");
    }
    if (int_save_time > int_chartjs_offset_date) {
      var_start_save_data = 0;
      int_save_time = 0;
      $("#chartjs_offset_date").prop("disabled", false);
      $("#btn_chartjs_view").removeClass("hide_btn").addClass("enable_btn");
      $("#btn_chartjs_view").prop("disabled", false);
      $("#btn_chartjs_view").val("作成されたグラフィックを表示");
      $("#btn_chartjs_start").removeClass("hide_btn").addClass("enable_btn");
      $("#btn_chartjs_start").prop("disabled", false);
      $("#report_label").html("レポートグラフを作成済");
      localStorage.setItem("chartjs_data_save" + ems_data.ems_host, JSON.stringify(array_save_data));
      $(window).off('beforeunload');
    }
  }

  function fb_get_setting() {
    var datas = {
      ac: "Ajax_GetSetting",
      host: ems_data.ems_host,
      placeid: placeid,
    }
    $.ajax({
      type: 'GET',
      url: "",
      dataType: 'json',
      data: datas,
      success: function(d) {
        temperature_data = parseFloat(d.ems_temp);
        humidity_data = parseFloat(d.ems_hum);
        if (var_start_save_data > 0) {
          var now_datetime = fb_format_date(new Date(), "YYYY-MM-DD hh:mm:ss");
          array_save_data.DATE.push(now_datetime);
          array_save_data.TEMP.push(temperature_data);
          array_save_data.HUM.push(humidity_data);
          array_save_data.PM1.push(parseFloat(d.ems_PM_1p0));
          array_save_data.PM2.push(parseFloat(d.ems_PM_2p5));
          array_save_data.PM4.push(parseFloat(d.ems_PM_4p0));
          array_save_data.PM10.push(parseFloat(d.ems_PM_10p0));
          array_save_data.NC05.push(parseFloat(d.ems_NC_0p5));
          array_save_data.NC1.push(parseFloat(d.ems_NC_1p0));
          array_save_data.NC2.push(parseFloat(d.ems_NC_2p5));
          array_save_data.NC4.push(parseFloat(d.ems_NC_4p0));
          array_save_data.NC10.push(parseFloat(d.ems_NC_10p0));
          if (var_start_save_data == 2) {
            my_chart.data.labels.push(now_datetime);
            my_chart.data.datasets[0].data.push(temperature_data);
            my_chart.data.datasets[1].data.push(humidity_data);
            my_chart_sps30_1.data.labels.push(now_datetime);
            my_chart_sps30_1.data.datasets[0].data.push(parseFloat(d.ems_PM_1p0));
            my_chart_sps30_1.data.datasets[1].data.push(parseFloat(d.ems_PM_2p5));
            my_chart_sps30_1.data.datasets[2].data.push(parseFloat(d.ems_PM_4p0));
            my_chart_sps30_1.data.datasets[3].data.push(parseFloat(d.ems_PM_10p0));
            my_chart_sps30_2.data.labels.push(now_datetime);
            my_chart_sps30_2.data.datasets[0].data.push(parseFloat(d.ems_NC_0p5));
            my_chart_sps30_2.data.datasets[1].data.push(parseFloat(d.ems_NC_1p0));
            my_chart_sps30_2.data.datasets[2].data.push(parseFloat(d.ems_NC_2p5));
            my_chart_sps30_2.data.datasets[3].data.push(parseFloat(d.ems_NC_4p0));
            my_chart_sps30_2.data.datasets[4].data.push(parseFloat(d.ems_NC_10p0));
            fb_chartjs_update();
          }
        }
        if (parseInt(d.ems_run_time) != sendtime) {
          sendtime = parseInt(d.ems_run_time);
          $("#timer_transfer").val(sendtime);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
        return;
      }
    });
  }

  function fb_temperature(flag) {
    $('#temperature').remove();
    if (!flag) return;
    $(`<div id="temperature" onclick="mouseMove()">
      <h1 id="now_time_temp" ></h1>
      <div style="width:100%;" style="font-size: 100px; margin-top:10px;">
          <label>温度・湿度</label>
          <br>
          <center>
            <div id="chart_div">
                <div class="gauge" id="chart_temp"></div>
                <div class="gauge" id="chart_hum"></div>
            </div>
          </center>
      </div>
    </div>`).appendTo('body');
    google.charts.load('current', {
      'packages': ['gauge']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data_temp = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['温度(℃)', temperature_data],
      ]);
      var data_hum = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['湿度(%)', humidity_data],
      ]);

      var options_temp = {
        width: 800,
        height: 320,
        redFrom: 50,
        redTo: 60,
        yellowFrom: 30,
        yellowTo: 50,
        greenFrom: 10,
        greenTo: 30,
        minorTicks: 10,
        min: 0,
        max: 60
      };
      var options_hum = {
        width: 800,
        height: 320,
        redFrom: 85,
        redTo: 100,
        yellowFrom: 60,
        yellowTo: 85,
        greenFrom: 15,
        greenTo: 60,
        minorTicks: 5
      };
      var chart_temp = new google.visualization.Gauge(document.getElementById('chart_temp'));
      var chart_hum = new google.visualization.Gauge(document.getElementById('chart_hum'))
      chart_temp.draw(data_temp, options_temp);
      chart_hum.draw(data_hum, options_hum);
    }
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

  function fb_chartjs_start() {
    if ($('#chartjs_offset_date').val() <= 0) {
      $('#chartjs_offset_date').focus();
      $('#chartjs_offset_date').addClass('error-animation');
      return;
    }
    int_save_time = 0;
    int_chartjs_offset_date = parseInt(parseFloat($('#chartjs_offset_date').val()) * 60);
    var_start_save_data = 2;
    array_save_data = {
      "DATE": [],
      "TEMP": [],
      "HUM": [],
      "PM1": [],
      "PM2": [],
      "PM4": [],
      "PM10": [],
      "NC05": [],
      "NC1": [],
      "NC2": [],
      "NC4": [],
      "NC10": []
    };
    chart_clean();
    $(window).on('beforeunload', function(e) {
      // ウィンドウを閉じる時にメッセージを表示する.
      let result = confirm('');
      return result;
    });
    $("#btn_chartjs_start").addClass("enable_btn");
    $("#btn_chartjs_start").prop("disabled", true);
    $("#btn_chartjs_view").val("表示");
    $("#btn_chartjs_view").addClass("enable_btn");
    $("#btn_chartjs_view").prop("disabled", true);
    $("#report_label").html("作成中");
    $('#chartjs_offset_date').prop("disabled", true);
  }

  function fb_chartjs_view() {
    if (var_start_save_data == 1) {
      var_start_save_data = 2;
    }
    if (array_save_data.DATE.length > 0) {
      if (fb_format_date(new Date(array_save_data.DATE[0]), "YYYY/MM/DD") != fb_format_date(new Date(), "YYYY/MM/DD")) {
        my_chart.options.title.text = '温度・湿度  (作成日：' + fb_format_date(new Date(array_save_data.DATE[0]), "YYYY/MM/DD") + ')';
      }
    }
    chart_clean();
    for (let i = 0; i < array_save_data.DATE.length; i++) {
      my_chart.data.labels.push(array_save_data.DATE[i]);
      my_chart.data.datasets[0].data.push(array_save_data.TEMP[i]);
      my_chart.data.datasets[1].data.push(array_save_data.HUM[i]);
      my_chart_sps30_1.data.labels.push(array_save_data.DATE[i]);
      my_chart_sps30_1.data.datasets[0].data.push(array_save_data.PM1[i]);
      my_chart_sps30_1.data.datasets[1].data.push(array_save_data.PM2[i]);
      my_chart_sps30_1.data.datasets[2].data.push(array_save_data.PM4[i]);
      my_chart_sps30_1.data.datasets[3].data.push(array_save_data.PM10[i]);
      my_chart_sps30_2.data.labels.push(array_save_data.DATE[i]);
      my_chart_sps30_2.data.datasets[0].data.push(array_save_data.NC05[i]);
      my_chart_sps30_2.data.datasets[1].data.push(array_save_data.NC1[i]);
      my_chart_sps30_2.data.datasets[2].data.push(array_save_data.NC2[i]);
      my_chart_sps30_2.data.datasets[3].data.push(array_save_data.NC4[i]);
      my_chart_sps30_2.data.datasets[4].data.push(array_save_data.NC10[i]);
    }
    fb_chartjs_update();
  }

  function fb_chartjs_update() {
    my_chart.options.scales.xAxes[0].time.unit = 'second';
    my_chart_sps30_1.options.scales.xAxes[0].time.unit = 'second';
    my_chart_sps30_2.options.scales.xAxes[0].time.unit = 'second';
    my_chart.options.scales.xAxes[0].time.displayFormats = {
      'second': 'H:mm:ss',
    };
    my_chart_sps30_1.options.scales.xAxes[0].time.displayFormats = {
      'second': 'H:mm:ss',
    };
    my_chart_sps30_2.options.scales.xAxes[0].time.displayFormats = {
      'second': 'H:mm:ss',
    };
    my_chart.update();
    my_chart_sps30_1.update();
    my_chart_sps30_2.update();
  }
</script>

</html>
