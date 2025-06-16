<?php
if (!$host) {

  slot('h1', '<h1> 不具合連絡システム | Nalux');

  $temperature_humidity = '';
  $electricity = '';
  foreach ($setting as $key => $value) {
    if (strpos($value['host'], "SB")) {
      $temperature_humidity .= '<a class="blue" href="/EquipmentManagement/view?host=' . $value['host'] . '">' . $value['host'] . '</a>';
    } else if (strpos($value['host'], "EL")) {
      $electricity .=  '<a class="blue" href="/EquipmentManagement/electricity?host=' . $value['host'] . '">' . $value['host'] . '</a>';
    }
  }
  print '<div id="bot">';
  print $temperature_humidity;
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
$btn = '<div class="header_sel" style="float:left; margin-top:3px;  margin-left:10px;" >';
$btn .= '<label for="sel_start">表示する成形機</label>
         <select id="sl_molding_machine">';
foreach ($no as $key => $value) {
  $btn .= "<option value='" . $value['no'] . "'>" . $value['no'] . "</option>\n";
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
  <title>【NALUX】静電気監視システム</title>
  <link rel="shortcut icon" href="https://khahoangfpt.pythonanywhere.com/static/esp32/logo.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://khahoangfpt.pythonanywhere.com/static/esp32/Chart.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-size: 14px;
    }

    #header_sel input,
    #header_sel label,
    #header_sel button,
    #header_sel i {
      font-size: 0.8vw;
      height: 28px;
    }

    .header_sel select {
      width: 150px;
      margin: 1px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .main input[type=text],
    .main input[type=date],
    .main select {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .enable_btn {
      color: white;
      width: 90%;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-align: center;
    }

    .enable_btn:hover {
      color: orange;
      font-weight: bold;
    }

    .btn_button {
      background-color: #4CAF50;
    }

    #btn_sel {
      background-color: #4CAF50;
    }

    #btn_setting {
      background-color: Teal;
    }

    #btn_download {
      background-color: Maroon;
    }

    #btn_flg_effect {
      background-color: seagreen;
    }


    .head_find {
      border-radius: 5px;
      background-color: #f2f2f2;
      padding-left: 20px;
      width: 35%;
      float: left;
      border-style: groove;
      height: 105px;
    }

    .head_set {
      border-radius: 5px;
      padding-left: 20px;
      background-color: #f2f2f2;
      width: 65%;
      float: left;
      border-style: groove;
      height: 105px;
    }

    .w3_sel {
      border-radius: 5px;
      padding: 5px;
      width: 33%;
      float: left;
      text-align: center;
    }

    .transfer {
      border-radius: 5px;
      padding: 5px;
      width: 30%;
      float: left;
      text-align: center;
    }

    #loading {
      width: 100%;
      height: 100%;
      z-index: 9999;
      position: fixed;
      top: 0;
      left: 0;
      /* 背景関連の設定 */
      background-color: #ccc;
      filter: alpha(opacity=85);
      -moz-opacity: 0.85;
      -khtml-opacity: 0.85;
      opacity: 0.85;
      background-image: url(http://track.yasu.nalux.local/images/loading-1.gif);
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    #advertisement {
      font-size: 50px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      position: fixed;
      top: 0;
      left: 0;
      /* 背景関連の設定 */
      background-color: #ccc;
      filter: alpha(opacity=85);
      -moz-opacity: 0.85;
      -khtml-opacity: 0.85;
      opacity: 0.85;
      background-color: aliceblue;
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      text-align: center;
      vertical-align: middle;
      font-weight: bolder;
      padding: 5%;
    }

    #loading img {
      max-width: 100%;
      height: auto;
    }

    .icon_settiing {
      right: 0px;
      position: fixed;
      text-align: center;
      margin: 10px 20px 10px 0px;
      cursor: pointer;
    }

    .tab_settiing {
      right: 0px;
      position: fixed;
      width: 300px;
      height: 400px;
      margin: 10px 20px 10px 0px;
      background-color: #ccc;
      display: none;
    }

    .hide_btn {
      background-color: transparent;
      color: transparent;
      width: 90%;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-align: center;
    }

    .gauge {
      display: inline-block;
    }

    @keyframes glowing {
      0% {

        -webkit-box-shadow: 0 0 3px #004A7F;
      }

      50% {

        -webkit-box-shadow: 0 0 20px red;
      }

      100% {

        -webkit-box-shadow: 0 0 3px #004A7F;
      }
    }

    .error-animation {
      /* background-color: red; */
      animation: glowing 0.5s 0.5s ease-in-out infinite alternate;
    }
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
      <div class="w3_sel" style="width:17%;">
        <label for="machine_name">接続中成形機</label>
        <input type="text" class="setting" id="machine_name" />
      </div>
      <div class="w3_sel" style="width:17%;">
        <label for="timer_transfer">転送タイマー</label>
        <select id="timer_transfer" class="setting">
          <option value="1">1秒</option>
          <option value="2">2秒</option>
          <option value="3">3秒</option>
          <option value="4">4秒</option>
          <option value="5">5秒</option>
          <option value="6">6秒</option>
          <option value="7">7秒</option>
          <option value="8">8秒</option>
          <option value="9">9秒</option>
          <option value="10">10秒</option>
          <option value="11">11秒</option>
          <option value="12">12秒</option>
          <option value="13">13秒</option>
          <option value="14">14秒</option>
          <option value="15">15秒</option>
          <option value="16">16秒</option>
          <option value="17">17秒</option>
          <option value="18">18秒</option>
          <option value="19">19秒</option>
          <option value="20">20秒</option>
        </select>
      </div>
      <div class="w3_sel" style="width:12%;">
        <label>　</label><br>
        <input type="submit" id="btn_setting" class="setting enable_btn" value="設定">
      </div>
      <div class="w3_sel" style="width:12%;">
        <label id='lb_flg_effect'>データ収集</label><br>
        <input type="submit" id="btn_flg_effect" class="enable_btn" value="">
      </div>
      <div class="w3_sel" style="width:12%;">
        <label>データベース</label><br>
        <input type="submit" id="btn_download" class="enable_btn" value="ロード">
      </div>
      <!-- <div class="w3_sel" style="width:10%;">
        <label>データクリア</label><br>
        <input type="submit" id="btn_flg_effect" value="クリア">
      </div> -->
      <div class="w3_sel" style="width:30%; padding: 10px; text-align: center;">
        <label id="now_time"></label><br>
        <label id="waiting_time" style="margin-top: 5px;"></label><br>
        <label id="memory" style="margin-top: 5px;"></label>
      </div>
    </div>
    <div style="height:800px; width:100%; float:left;  padding:0.5%">
      <canvas id="my_chart" width="95%" height="400"></canvas>
    </div>
  </div>
  <div class="icon_settiing">
    <i onclick="fb_show_bars_tab();" style="font-size:24px" class="fa">&#xf0c9;</i>
  </div>
  <div class="tab_settiing">
    <div class="w3_sel" style="width:100%;">
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
      <!-- <div class="w3_sel" style="width:100%;">
        <label id="report_label">レポートグラフを作成</label><br>
        <input type="number" style="font-size: 0.8vw; height:32px; text-align:right;" id="chartjs_offset_date" />　分
        <input type="submit" class="btn_button hide_btn" id="btn_chartjs_start" onclick="fb_chartjs_start();" value="スタート">
        <input type="submit" class="btn_button hide_btn" id="btn_chartjs_view" onclick="fb_chartjs_view();" value="表示" disabled>
      </div> -->
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
    <!-- <div class="w3_sel" style="width:50%;">
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
    </div> -->
  </div>
</body>
<script>
  var array_data = {
    "DATE": [],
    "ELEC": [],
  };
  var array_save_data = {
    "DATE": [],
    "ELEC": [],
  };
  var wee_setting = <?php echo htmlspecialchars_decode($wee_setting); ?>;
  var wee_electricity = <?php echo htmlspecialchars_decode($wee_electricity); ?>;
  var var_start_save_data = 0,
    int_save_time = 0,
    int_chartjs_offset_date = 0,
    waiting_temperature = 0,
    array_chart_count = 0,
    waitingtime, sendtime, flg_effect;
  var electricity_data = 0,
    electricity_max = 0.4,
    electricity_min = -0.4,
    electricity_danger = 0,
    electricity_danger_max = 1946;
  var flg_sel_on_view = false;

  var setting_password = "<?php echo $manager_password; ?>",
    setting_password_clean = "no_password",
    input_password = "no_password";
  console.log(setting_password);
  // WebSocket 通信を開始する

  $(window).load(function() {
    fb_loading(true);
    $('.btn_def').button();
    setInterval("fb_get_setting();", 2000);
    setInterval("fb_waitingtime();", 1000);
    if (Object.keys(wee_setting).length > 0) {
      $('#machine_name').val(wee_setting.wee_no);
      $("#timer_transfer").val(parseInt(wee_setting.wee_run_time))
    }
    if (localStorage.getItem("chartjs_data_save" + wee_setting.wee_host)) {
      array_save_data = JSON.parse(localStorage.getItem("chartjs_data_save" + wee_setting.wee_host));
      $("#btn_chartjs_view").removeClass("hide_btn").addClass("enable_btn");
      $("#btn_chartjs_view").prop("disabled", false);
      $("#btn_chartjs_view").val("(" + fb_format_date(new Date(array_save_data.DATE[0]), "YYYY/MM/DD") + ")保存済のグラフを表示");
      $("#btn_chartjs_start").removeClass("hide_btn").addClass("enable_btn");
      $("#btn_chartjs_start").val("再スタート");
    }
    $('.main').on('click', function() {
      $('.tab_settiing').css('display', 'none');
    });
    $(document).on('input', '#chartjs_offset_date', function(e) {
      $(this).removeClass('error-animation');
    });
    $('#time_stepSize').change(function() {
      my_chart.options.scales.xAxes[0].time.stepSize = $('#time_stepSize').val();
      my_chart.update();
    });
    $('input[name="distribution"]:radio').change(function() {
      my_chart.options.scales.xAxes[0].distribution = $(this).val();
      my_chart.update();
    });
    $('input[name="ticks_source"]:radio').change(function() {
      my_chart.options.scales.xAxes[0].ticks.source = $(this).val();
      my_chart.update();
    });
    $('#machine_name').change(function() {
      $("#btn_setting").css("background-color", "Teal");
      $("#btn_setting").prop("disabled", false);
    });
    $('#timer_transfer').change(function() {
      $("#btn_setting").css("background-color", "Teal");
      $("#btn_setting").prop("disabled", false);
    });
    $('#sl_molding_machine').change(function() {
      if (wee_setting.wee_no == $('#sl_molding_machine').val()) {
        $("#btn_setting").css("background-color", "Teal");
        $(".setting").prop("disabled", false);
      } else {
        $("#btn_setting").css("background-color", "transparent");
        $(".setting").prop("disabled", true);
      }
      fb_work_environment_electricityload($('#sl_molding_machine').val());
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
          my_chart.data.datasets[0].data.push(array_data.ELEC[i]);
        }
      }
      update_chart();
      waiting_temperature = 0;
      flg_sel_on_view = true;
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
          wee_host: wee_setting.wee_host,
          wee_no: $('#machine_name').val(),
          wee_run_time: $("#timer_transfer").val(),
        }
        $.ajax({
          type: 'POST',
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
    $('#btn_flg_effect').on('click', function() {
      if (fb_is_password()) {
        var datas = {
          ac: "electricity_set",
          host: wee_setting.wee_host,
          flg_effect: $('#btn_flg_effect').val() == '有効' ? 1 : 0,
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
              $('#btn_flg_effect').val(datas.wee_flg_effect == 1 ? '無効' : '有効');
              $('#lb_flg_effect').html(datas.wee_flg_effect == 1 ? 'データ収集有効中' : 'データ収集無効中');
            }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
            return;
          }
        });
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

  function fb_work_environment_electricityload(item) {
    var save_wee_date;
    var sub_wee_date;
    array_data = {
      "DATE": [],
      "ELEC": [],
    };
    var datas = {
      ac: "Ajax_GetData",
      wee_host: wee_setting.wee_host,
      wee_no: item,
    }
    $.ajax({
      type: 'GET',
      url: "",
      dataType: 'json',
      data: datas,
      success: function(d) {
        if (array_chart_count != d.length) {
          array_chart_count = d.length;
          d.forEach((value, key) => {
            sub_wee_date = new Date(value["created_at"]) - new Date(save_wee_date);
            if ((sub_wee_date > 120000 || sub_wee_date < -120000) && save_wee_date) {
              array_data.DATE.push(save_wee_date);
              array_data.ELEC.push(0);
              array_data.DATE.push(value["created_at"]);
              array_data.ELEC.push(0);
              array_data.DATE.push(value["created_at"]);
              array_data.ELEC.push(fb_electricity_data_convert(value["wee_electricity"]))
            } else {
              array_data.DATE.push(value["created_at"]);
              array_data.ELEC.push(fb_electricity_data_convert(value["wee_electricity"]));
            }
            save_environment_date = value["environment_date"];
          });
          if (var_start_save_data < 2) {
            if (array_data.DATE.length > 0 && !flg_sel_on_view) {
              my_chart.data.labels = array_data.DATE;
              my_chart.data.datasets[0].data = array_data.ELEC;
              update_chart();
            }
          }
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
        return;
      }
    });
    fb_loading(false);
  }

  function fb_electricity_data_convert(electricity_input) {

    let electricity_out;
    electricity_out = Math.round((parseInt(electricity_input) - 2396) / 1700 * 0.4 * 1000) / 1000;
    return electricity_out;
  }

  function mouseMove() {
    if (fb_electricity_data_convert(electricity_data) > electricity_min && fb_electricity_data_convert(electricity_data) < electricity_max) {
      electricity_danger_max = electricity_data;
    }
    electricity_danger = 2;
    waiting_temperature = 0;
    fb_advertisement(false);
  }
  var graph_data = {
    labels: [], // X軸のデータ (時間)
    datasets: [{
      type: 'line',
      data: [],
      label: '静電気',
      fill: false,
      yAxisID: 'y-axis-1',
      borderColor: "#ff0000",
      borderWidth: 2,
      radius: 0,
    }, ]
  };
  var graph_options = {
    title: {
      display: true,
      text: '静電容量'
    },
    maintainAspectRatio: false,
    scales: {
      yAxes: [{
        id: 'y-axis-1',
        ticks: {
          labelString: '静電容量(kV)',
          beginAtZero: true,
          callback: (value) => `${value}kV`,
          min: -0.6,
          max: 0.6,
        },
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
  var ctx_temp = document.getElementById("my_chart").getContext('2d');
  var my_chart = new Chart(ctx_temp, {
    type: Object,
    data: graph_data,
    options: graph_options
  });


  function chart_clean() {
    my_chart.data.labels = [];
    my_chart.data.datasets[0].data = [];
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
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'year': 'YYYY年',
      };
    } else if (date_max.getFullYear() - date_min.getFullYear() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'month';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
    } else if (date_max.getMonth() - date_min.getMonth() > 1) {
      my_chart.options.scales.xAxes[0].time.unit = 'month';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'month': 'YYYY年M月',
      };
    } else if (date_max.getMonth() - date_min.getMonth() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'day';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
    } else if (date_max.getDate() - date_min.getDate() > 1) {
      my_chart.options.scales.xAxes[0].time.unit = 'day';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'day': 'M月D日',
      };
    } else if (date_max.getDate() - date_min.getDate() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'hour';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'D日 H時',
      };
    } else if (date_max.getHours() - date_min.getHours() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'hour';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'hour': 'H時',
      };
    } else if (date_max.getMinutes() - date_min.getMinutes() > 0) {
      my_chart.options.scales.xAxes[0].time.unit = 'minute';
      my_chart.options.scales.xAxes[0].time.displayFormats = {
        'minute': 'H時m',
      };
    } else {
      my_chart.options = graph_options;
    }
    my_chart.options.title.text = '静電容量';
    my_chart.update();
  }

  function fb_waitingtime() {
    waiting_temperature++;
    $('#now_time').html(fb_format_date(new Date(), "YYYY/MM/DD(WW) hh:mm:ss"));
    waitingtime = (sendtime) - (parseInt(fb_format_date(new Date(), "mm")) % sendtime + parseInt(fb_format_date(new Date(), "ss")));
    if (environment_setting.environment_no == $('#sl_molding_machine').val()) {
      if (waitingtime > 1) {
        $('#waiting_time').html("後" + waitingtime + "秒 チャートを更新する");
      } else {
        $('#waiting_time').html("チャートを更新する");
        fb_work_environment_electricityload(environment_setting.environment_no);
      }
    } else {
      $('#waiting_time').html("別の成形機の選択中");
      fb_loading(false);
    }
    // if (waiting_temperature > 60) {
    //   flg_sel_on_view = false;
    //   if (electricity_danger != 1) {
    //     fb_advertisement(true);
    //   }
    // }
    if (electricity_danger == 1) {
      $('.electricity_danger_max').html('静電容量MAX：' + fb_electricity_data_convert(electricity_danger_max) + '(kV)');
      $('#now_time_temp').html(fb_format_date(new Date(), "YYYY/MM/DD(WW) hh:mm:ss"));
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data_temp = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['kV', fb_electricity_data_convert(electricity_data)],
        ]);

        if (fb_electricity_data_convert(electricity_data) > 0) {
          var options_temp = {
            width: 800,
            height: 320,
            greenFrom: -0.3,
            greenTo: 0.3,
            yellowFrom: 0.3,
            yellowTo: 0.4,
            redFrom: 0.4,
            redTo: 0.6,
            min: -0.6,
            max: 0.6,
            minorTicks: 0.1,
          };
        } else {
          var options_temp = {
            width: 800,
            height: 320,
            greenFrom: -0.3,
            greenTo: 0.3,
            yellowFrom: -0.4,
            yellowTo: -0.3,
            redFrom: -0.6,
            redTo: -0.4,
            min: -0.6,
            max: 0.6,
            minorTicks: 0.1,
          };
        }
        var chart_advertisement = new google.visualization.Gauge(document.getElementById('chart_advertisement'));
        chart_advertisement.draw(data_temp, options_temp);
      }
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
      $("#btn_chartjs_view").val("表示");
      $("#btn_chartjs_start").removeClass("hide_btn").addClass("enable_btn");
      $("#btn_chartjs_start").val("再スタート");
      $("#btn_chartjs_start").prop("disabled", false);
      $("#report_label").html("レポートグラフを作成済");
      localStorage.setItem("chartjs_data_save" + environment_setting.environment_host, JSON.stringify(array_save_data));
      $(window).off('beforeunload');
    }
  }

  function fb_get_setting() {
    var datas = {
      ac: "Ajax_GetSetting",
      environment_host: environment_setting.environment_host,
    }
    $.ajax({
      type: 'GET',
      url: "",
      dataType: 'json',
      data: datas,
      success: function(d) {
        environment_flg_effect = d.environment_flg_effect;
        if (environment_flg_effect == 1) {
          $('#btn_flg_effect').val('有効');
          $('#lb_flg_effect').html('データ収集無効中');
        } else {
          $('#btn_flg_effect').val('無効');
          $('#lb_flg_effect').html('データ収集有効中');
        }
        electricity_data = parseFloat(d.wee_electricity);
        $('#memory').html("静電容量: " + fb_electricity_data_convert(electricity_data) + "(kV) / " + electricity_data);
        if ((electricity_data > electricity_danger_max && fb_electricity_data_convert(electricity_data) > electricity_max) || (electricity_data < electricity_danger_max && fb_electricity_data_convert(electricity_data) < electricity_min)) electricity_danger_max = electricity_data;
        if ((fb_electricity_data_convert(electricity_data) < electricity_min || fb_electricity_data_convert(electricity_data) > electricity_max) && electricity_danger == 0) {
          electricity_danger = 1;
          fb_advertisement(true);
        }
        if (electricity_min < fb_electricity_data_convert(electricity_data) && fb_electricity_data_convert(electricity_data) < electricity_max && electricity_danger == 2) {
          electricity_danger = 0;
        }
        if (var_start_save_data > 0) {
          var now_datetime = fb_format_date(new Date(), "YYYY-MM-DD hh:mm:ss");
          array_save_data.DATE.push(now_datetime);
          array_save_data.ELEC.push(electricity_data);
          if (var_start_save_data == 2) {
            my_chart.data.labels.push(now_datetime);
            my_chart.data.datasets[0].data.push(fb_electricity_data_convert(electricity_data));
            fb_chartjs_update();
          }
        }
        if (parseInt(d.environment_run_time) != sendtime) {
          sendtime = parseInt(d.environment_run_time);
          $("#timer_transfer").val(sendtime);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
        return;
      }
    });
  }

  function fb_advertisement(flag) {
    $('#advertisement').remove();
    if (!flag) return;
    if (electricity_danger != 0) {
      electricity_danger = 1;
      $(`<div id="advertisement" onclick="mouseMove()">
      <h1 id="now_time_temp" ></h1>
      <center>
        <div class="error-animation" style="width:50%;" style="font-size: 100px; margin-top:10px;">
          <p>静電容量危険広告</p>
          <p>発生時：` + fb_format_date(new Date(), "YYYY/MM/DD(WW) hh:mm:ss") + `</p>
          <p class="electricity_danger_max">静電容量MAX：` + fb_electricity_data_convert(electricity_danger_max) + `(kV)</p>
          <center>
            <div class="gauge" id="chart_advertisement"></div>
          </center>
        </div>
      <center>
    </div>`).appendTo('body');
    } else {
      $(`<div id="advertisement" onclick="mouseMove()">
      <h1 id="now_time_temp" ></h1>
      <center>
        <div style="width:50%;" style="font-size: 100px; margin-top:10px;">
          <label>静電容量</label>
          <br>
          <center>
            <div class="gauge" id="chart_advertisement"></div>
          </center>
        </div>
      <center>
    </div>`).appendTo('body');
    }
    $('#now_time_temp').html(fb_format_date(new Date(), "YYYY/MM/DD(WW) hh:mm:ss"));
    google.charts.load('current', {
      'packages': ['gauge']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data_temp = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['kV', fb_electricity_data_convert(electricity_data)],
      ]);
      if (fb_electricity_data_convert(electricity_data) > 0) {
        var options_temp = {
          width: 800,
          height: 320,
          greenFrom: -0.3,
          greenTo: 0.3,
          yellowFrom: 0.3,
          yellowTo: 0.4,
          redFrom: 0.4,
          redTo: 0.6,
          min: -0.6,
          max: 0.6,
          minorTicks: 0.1,
        };
      } else {
        var options_temp = {
          width: 800,
          height: 320,
          greenFrom: -0.3,
          greenTo: 0.3,
          yellowFrom: -0.4,
          yellowTo: -0.3,
          redFrom: -0.6,
          redTo: -0.4,
          min: -0.6,
          max: 0.6,
          minorTicks: 0.1,
        };
      }

      var chart_advertisement = new google.visualization.Gauge(document.getElementById('chart_advertisement'));
      chart_advertisement.draw(data_temp, options_temp);
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
      "ELEC": [],
    };
    chart_clean();
    $(window).on('beforeunload', function(e) {
      // ウィンドウを閉じる時にメッセージを表示する.
      let result = confirm('本当に閉じていいの？');
      return result;
    });
    $("#btn_chartjs_start").addClass("hide_btn").removeClass("enable_btn");
    $("#btn_chartjs_start").prop("disabled", true);
    $("#btn_chartjs_view").val("表示");
    $("#btn_chartjs_view").addClass("hide_btn").removeClass("enable_btn");
    $("#btn_chartjs_view").prop("disabled", true);
    $("#report_label").html("レポートグラフを作成中");
    $('#chartjs_offset_date').prop("disabled", true);
  }

  function fb_chartjs_view() {
    if (var_start_save_data == 1) {
      var_start_save_data = 2;
    }
    if (array_save_data.DATE.length > 0) {
      if (fb_format_date(new Date(array_save_data.DATE[0]), "YYYY/MM/DD") != fb_format_date(new Date(), "YYYY/MM/DD")) {
        my_chart.options.title.text = '静電気 (作成日：' + fb_format_date(new Date(array_save_data.DATE[0]), "YYYY/MM/DD") + ')';
      }
    }
    chart_clean();
    for (let i = 0; i < array_save_data.DATE.length; i++) {
      my_chart.data.labels.push(array_save_data.DATE[i]);
      my_chart.data.datasets[0].data.push(array_save_data.ELEC[i]);
    }
    fb_chartjs_update();
  }

  function fb_chartjs_update() {
    my_chart.options.scales.xAxes[0].time.unit = 'second';
    my_chart.options.scales.xAxes[0].time.displayFormats = {
      'second': 'H:mm:ss',
    };
    my_chart.update();
  }
</script>

</html>
