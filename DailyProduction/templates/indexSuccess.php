<!DOCTYPE html>
<html>
<?php
use_stylesheet("MissingDefect/fontawesome-all.css");
use_stylesheet("MissingDefect/fontawesome-all.min.css");

use_javascript("MissingDefect/fontawesome-all.js");
use_javascript("MissingDefect/fontawesome-all.min.js");
if (!$placeid) {

  slot('h1', '<h1> 稼働状態配置図 | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/DailyProduction?placeid=1000079">野洲工場</a>
      <a class="blue" href="/DailyProduction?placeid=1000073">山崎工場</a>
      <a class="blue" href="/DailyProduction?placeid=1000125">NPG</a>
    </div>
    ';
  return;
}
if ($sf_request->getParameter('placeid')) {
  $placeid = $sf_request->getParameter('placeid');
  $placest = $sf_request->getParameter('placeid') . "_total_st";
} else {
  $placeid = "all-total";
  $placest = "all_total_st";
}
slot('h1', '<h1>');
?>
<style type="text/css">
  body {
    background-color: #000;
    margin: 0;
    color: #ffffff;
    font-size: 50px;
  }

  #header {
    width: 0vw;
    height: 0vh;
    margin-bottom: 0;
  }

  p {
    padding: 0;
    margin: 0;
  }

  #content {
    padding-top: 0vh;
  }

  .m_cont {
    max-width: 834px;
    margin: auto;
  }

  #de_view,
  #Clock,
  #DayOfWeek,
  #now_datetime {
    display: none;
  }

  html {
    overflow-y: scroll;
  }

  .header_line {
    font-weight: bold;
    float: left;
    line-height: 1em;
    white-space: nowrap;
    overflow: hidden;
  }

  .header_line2 {
    position: fixed;
    font-size: 25px;
    float: left;
    line-height: 1em;
    border-bottom: 5px double #fff;
    width: 834px;
    font-weight: normal;
    background-color: #000;
  }

  .header_line2 button,
  .header_line2 select {
    font-size: 18px;
  }

  .position {
    line-height: 1em;
    border-bottom: 5px double #fff;
    margin-bottom: 10px;
    font-weight: normal;
  }

  .position_d {
    line-height: 1em;
    border-bottom: 5px double #fff;
    margin-bottom: 10px;
    font-weight: normal;
  }

  .setting_position {
    margin-bottom: 10px;
    font-weight: normal;
  }

  .position_contents {
    margin-left: 0.5vw;
  }

  .m_box {
    border-bottom: #ffffff 0.2px solid;
    margin-bottom: 0.02vh;
    line-height: 1em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .m_text {
    float: left;
    display: table-cell;
  }

  .next_text,
  .next_ma_text {
    float: right;
    display: table-cell;
  }


  .i_text,
  .ma_text {
    display: table-cell;
    float: left;
    margin: 5px 0 0 8px;
    white-space: nowrap;
    vertical-align: bottom;
    text-align: justify;
    text-justify: inter-ideograph;
    line-height: 1em;
  }

  .ma_text {
    display: none;
  }

  .next_ma_text {
    display: none;
  }

  .blink {
    -webkit-animation: border 0.3s ease infinite alternate;
    animation: border 0.3s ease infinite alternate;
  }

  @-webkit-keyframes blink {
    0% {
      opacity: 0;
    }

    100% {
      opacity: 1;
    }
  }

  @keyframes blink {
    0% {
      opacity: 0;
    }

    100% {
      opacity: 1;
    }
  }

  @keyframes border {
    0% {
      border: 1.5px solid #808080;
    }

    100% {
      border: 1.5px solid #FFFF00;
    }
  }

  .ui-dialog-title {
    font-size: 22px !important;
  }

  .ui-dialog-titlebar {
    padding: 5px !important;
  }

  .ui-dialog-buttons {
    font-size: 26px !important;
  }

  .ui-widget-content {
    padding: 2px !important;
  }

  /* Modal Content */
  #dialog_box {
    background-color: #000;
    color: #00ff99;
    margin: 0px;
    text-align: center;
    font-size: 24px;
    padding: 5px;
  }

  #dialog_box input[type="number"] {
    width: 37%;
    height: 26px;
    padding-left: 5px;
  }

  #dialog_box input[type="time"] {
    width: 12%;
    height: 26px;
  }

  #dialog_box button:hover {
    color: orange;
  }

  #view_calculation {
    display: block;
    position: absolute;
    width: 48%;
    height: 180px;
    background-color: #111111;
    top: 90px;
    right: 10px;
    border-radius: 10px;
    padding: 5px;
    font-size: 85%;
  }

  table {
    border: 1px;
    width: 100%;
    border-collapse: collapse;
  }

  td,
  th {
    border: 1px solid white;
    padding: 5px;
    font-size: 18px;
  }

  th {
    text-align: center;
  }

  td.noborder,
  th.noborder {
    border: 1px solid transparent;
    width: 2%;
    padding: 2px 0px 2px 3px;
    font-size: 14px;
  }

  .greenColor {
    background-color: #33CC33;
  }

  .redColor {
    background-color: #E60000;
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

  @keyframes caveat {
    0% {

      -webkit-box-shadow: 0 0 3px #004A7F;
    }

    50% {

      -webkit-box-shadow: 0 0 20px yellow;
    }

    100% {

      -webkit-box-shadow: 0 0 3px #004A7F;
    }
  }

  .error-animation {
    background-color: red;
    animation: glowing 0.5s 0.5s ease-in-out infinite alternate;
  }

  .caveat-animation {
    color: red;
    animation: caveat 3s 0.5s ease-in-out infinite alternate;
  }

  .screen-number {
    width: 150px;
    height: 50px;
  }

  .screen-datetime {
    width: 79px;
    height: 50px;
  }

  .calculation {
    height: 35px;
  }

  input:checked+label {
    color: black;
    font-weight: bolder;
  }
</style>
<script type="text/javascript">
  var ws = null;
  var limit_demand = 1050;
  var ww = $(window).width() - 20;
  var wh = $(window).height();
  if (ww > 834) {
    ww = 834;
  }
  var xwrad_data = <?php echo htmlspecialchars_decode($xwrad_data); ?>;
  var xwrad_data_return = <?php echo htmlspecialchars_decode($xwrad_data); ?>;
  var xmlhttp = new XMLHttpRequest();
  const mq = window.matchMedia("(min-width: 769px)");
  var today = fb_format_date(new Date(), "YYYY/MM/DD");
  var array_week = ["日", "月", "火", "水", "木", "金", "土"];
  var placeid = '<?php echo $placeid; ?>';
  var input_mno;
  var xwrad_change_save = {
    ADD: {},
    UPDATE: {},
    DELETE: {}
  };
  var xwrad_change_count = {
    ADD: 0,
    UPDATE: 0,
    DELETE: 0
  };
  var ajax_send;
  var ajax_send_error_count = 0;
  $(window).load(function() {
    $("button").button();
    //WebSocket
    WSOpen(); //接続
    <?php if ($_GET["placeid"] != "") { ?>
      get_input();
      updateClock();
      setInterval("updateClock()", 1000);
      if (localStorage.getItem("生産情報入力者")) {
        tanto = JSON.parse(localStorage.getItem("生産情報入力者"));
        if (tanto.today == today) {
          $("#担当者").html(tanto.name);
        }
      }
      if (localStorage.getItem("xwrad_change_save")) {
        xwrad_change_save = JSON.parse(localStorage.getItem("xwrad_change_save"));
        Object.keys(xwrad_change_save["ADD"]).forEach((value, key) => {
          xwrad_data[xwrad_change_save["ADD"][value].xwrad_mno].data[fb_get_xwrad_key(xwrad_change_save["ADD"][value])] = fb_convert_data(xwrad_change_save["ADD"][value]);
        });
        Object.keys(xwrad_change_save["UPDATE"]).forEach((value, key) => {
          xwrad_data[value.split('-')[0]].data[value.split('-')[1]] = fb_convert_data(xwrad_change_save["UPDATE"][value]);
        });
        Object.keys(xwrad_change_save["DELETE"]).forEach((value, key) => {
          xwrad_data[value.split('-')[0]].data[value.split('-')[1]].xls_id = "DELETE";
        });
      }
      fb_save_and_load_new_data_count();
      $("input[name='本日抜き']").prop('checked', localStorage.getItem("本日抜き"));
      $("input[name='自動マージ']").prop('checked', localStorage.getItem("自動マージ"));
      if (localStorage.getItem("自動マージ")) {
        $("#btn_merge_data").prop("disabled", true);
        $("#btn_merge_data").prop("aria-disabled", true);
        $("#btn_merge_data").addClass("ui-button-disabled ui-state-disabled");
      } else {
        $("#btn_merge_data").prop("disabled", false);
        $("#btn_merge_data").prop("aria-disabled", false);
        $("#btn_merge_data").removeClass("ui-button-disabled ui-state-disabled");
      }
    <?php } ?>

    $("#msg_box").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      position: ["centetr"],
      buttons: [{
        text: "閉じる",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });
    $("#screen_input").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      position: ["centetr"],
      buttons: [{
        text: "閉じる",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });
    $("#dialog_box").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      position: ["centetr"],
      buttons: [{
        text: "閉じる",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });
    $(".bars-tap").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      position: ["centetr"],
      buttons: [{
        text: "閉じる",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });
    $("input[name='本日抜き']").click(function() {
      if ($(this).is(':checked')) {
        localStorage.setItem("本日抜き", true);
      } else {
        localStorage.setItem("本日抜き", "");
      }
    });
    $("input[name='自動マージ']").click(function() {
      $(".merge_msg").html("");
      $(".span_seconds").html("");
      if ($(this).is(':checked')) {
        localStorage.setItem("自動マージ", true);
        $("#btn_merge_data").prop("disabled", true);
        $("#btn_merge_data").prop("aria-disabled", true);
        $("#btn_merge_data").addClass("ui-button-disabled ui-state-disabled");
      } else {
        localStorage.setItem("自動マージ", "");
        $("#btn_merge_data").prop("disabled", false);
        $("#btn_merge_data").prop("aria-disabled", false);
        $("#btn_merge_data").removeClass("ui-button-disabled ui-state-disabled");
      }
    });
    if (ww > 450 && wh > 450) {
      $("#dialog_box input[type=number]").focus(function() {
        var this_input = this;
        $("#screen_input").dialog({
          autoOpen: false,
          width: '540px',
          modal: true,
          title: this.name + 'を入力してください',
          position: ["center", 100],
          buttons: [{
            text: "キャンセル",
            click: function(ev) {
              $(this).dialog("close");
            }
          }, {
            text: "決定",
            click: function(ev) {
              $(this_input).val($("#view_number").html());
              $(this).dialog("close");
              fb_input_calculation();
              fb_set_focus(this_input.id);
            }
          }]
        });
        if (this.value != "0") {
          $("#view_number").html(this.value);
        } else {
          $("#view_number").html("");
        }
        $("#screen_inputdatetime").css("display", "none");
        $("#screen_inputnumber").css("display", "block");
        $("#screen_input").dialog("open");
        return false;
      });
      $("#dialog_box input[type=time]").focus(function() {
        $(this).removeClass('error-animation');
        var this_input = this;
        $("#screen_input").dialog({
          autoOpen: false,
          width: ww,
          modal: true,
          title: this.name + 'を入力してください',
          position: ["center", 100],
          buttons: [{
            text: "キャンセル",
            click: function(ev) {
              $(this).dialog("close");
            }
          }, {
            text: "決定",
            click: function(ev) {
              if (btn_set_datetime(this_input)) {
                $(this).dialog("close");
                fb_input_calculation();
                fb_set_focus(this_input.id);
              }
            }
          }]
        });
        var input_datetime;
        if (this_input.name == "開始日時") {
          input_datetime = $("#input_date").val();
          $(".start_not_enabled").css("color", "transparent");
          $(".start_not_enabled").prop("disabled", true);
        } else {
          input_datetime = $("#input_stop_date").val();
          $(".start_not_enabled").css("color", "white");
          $(".start_not_enabled").prop("disabled", false);
        }
        if (this_input.value) {
          input_datetime += " " + this_input.value + ":00";
        } else {
          if (this_input.name == "終了日時" && $("#input_start_time").val()) {
            input_datetime += " " + $("#input_start_time").val() + ":00";
          } else {
            input_datetime += " " + fb_format_date(new Date(), "hh:mm:ss");
          }
        }
        $("#save_default_input").html(input_datetime);
        fb_return_default();
        $("#screen_inputnumber").css("display", "none");
        $("#screen_inputdatetime").css("display", "block");
        $("#screen_input").dialog("open");
        return false;
      });
    } else {
      $('#dialog_box input').focusout(function(e) {
        $("span [name='" + this.name + "']").removeClass('error-animation');
        fb_input_calculation();
      });
    }
    $("#input_stop_date").change(function() {
      fb_input_calculation();
    });
  });

  function fb_show_bars_tab() {
    $(".bars_tab").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      title: '設定画面',
      position: ["center", 30],
      buttons: [{
        text: "閉じる",
        click: function(ev) {
          $(this).dialog("close");
        }
      }]
    });
    $(".bars_tab").css("display", "block");
    $(".bars_tab").dialog("open");
  }


  function fb_input_calculation() {
    var calculation_cycle;
    var comparison_cycle = "<span style='color:red'>NG</span>";
    var input_cycle = parseFloat($('#input_cycle').val());
    var input_ntab = parseInt($("#input_ntab").val());
    var das_cycle = xwrad_data[input_mno]["lot"][$("#input_lot").prop("selectedIndex")].cycle;
    $("#corner_calculation").html("");
    if ($("#input_ntab").val() && $("#input_piecis").val()) {
      if ($("#input_start_time").val() && $("#input_stop_time").val()) {
        var input_start = new Date($("#input_date").val() + " " + $("#input_start_time").val() + ':00');
        var input_stop = new Date($("#input_stop_date").val() + " " + $("#input_stop_time").val() + ':00');
        var input_time = parseInt((input_stop - input_start) / 10);
        calculation_cycle = Math.round(input_time / input_ntab) / 100;
      } else {
        calculation_cycle = Math.round(86400 * 100 / input_ntab) / 100;
      }
      if (Math.abs(input_cycle - calculation_cycle) < (input_cycle * 0.1)) {
        comparison_cycle = "OK";
      }
      $("#view_calculation").css("display", "block");
      $("#corner_calculation").html(`<table>
                                        <tr class='calculation'><td class='noborder'>※ サイクル（マスター）: ` + das_cycle + `（` + Math.round(das_cycle * 10000 / input_cycle) / 100 + `%）
                                          <button id='' onclick='$("#input_cycle").val(` + das_cycle + `); fb_input_calculation();'>取得</button></td></tr>
                                        <tr class='calculation'><td class='noborder'>※ サイクル（計算）: ` + calculation_cycle + `（` + Math.round(calculation_cycle * 10000 / input_cycle) / 100 + `%）
                                          <button id='' onclick='$("#input_cycle").val(` + calculation_cycle + `); fb_input_calculation();'>取得</button></td></tr>
                                        <tr class='calculation'><td class='noborder'>※ 判定 : ` + comparison_cycle + `</td></tr></table>`);
    } else {
      if ($("#corner_reference").html() == "") {
        $("#view_calculation").css("display", "none");
      }
    }
    $("button").button();
  }

  var timer = false;
  $(window).resize(function() {
    if (timer !== false) {
      clearTimeout(timer);
    }
    timer = setTimeout(function() {
      //ここに処理を記載
      stop_hide_show();
    }, 200);
  });

  function stop_hide_show() {
    var m_s = $(".m_s");
    for (i = 0; i < m_s.length; i++) {
      //alert(m_s[i].id+","+m_s[i].value);
      change_show();
      var mno = m_s[i].id.split("_");
      if (!mq.matches) {
        if (m_s[i].value == "stop") {
          $("#m_" + mno[1]).hide();
        } else {
          $("#m_" + mno[1]).show();
        }
      } else {
        if ($("#set_view").val() == "view_hide") {
          //change_hide();
        }
        $("#m_" + mno[1]).show();
      }
    }
  }

  function wsPing() {
    ws.send("ping");
    //console.log("ping送信"+",受信日時:"+nowDT("yd"));
  }

  // 接続イベント
  function WSOpen() {
    if (ws == null) {
      ws = new WebSocket("ws://track.yasu.nalux.local:12000"); //接続
      ws.onopen = onOpen;
      ws.onmessage = onMessage;
      ws.onclose = onClose;
      ws.onerror = onError;
    }
  }

  // 接続イベント
  function onOpen(event) {
    if (placeid == 1000073) {
      ws.send("get_status,f,1f");
      ws.send("get_status,f,3f");
      ws.send("get_status,f,4f-1");
      ws.send("get_status,f,4f-2");
    } else if (placeid == 1000079) {
      ws.send("get_status,f,ww-1f");
      ws.send("get_status,f,ww-3f");
      ws.send("get_status,f,nw-1");
      ws.send("get_status,f,nw-2");
      ws.send("get_status,f,ew-1f");
    } else {
      ws.send("get_status,p," + placeid);
    }
    //console.log("サーバ接続完了"+",受信日時:"+nowDT("yd"));
  }

  function open_ChangeColorTray(mnoswiche) {
    var s = mnoswiche.split(",");
    if (s[3] == "ON") {
      $("#m_" + s[0]).css({
        "background-color": "#00ff99"
      });
      $("#t_" + s[0]).css({
        "color": "#000"
      }); //0000ff
      $("#i_" + s[0]).css({
        "color": "#000"
      });
      $("#ma_" + s[0]).css({
        "color": "#000"
      });
      //$("#t_"+s[0]).addClass("blinking");
      $("#next_" + s[0]).text(nowDT("ti"));
      $("#next_" + s[0]).css({
        "color": "#000"
      });
      $("#next_ma" + s[0]).css({
        "color": "#000"
      });
    } else if (s[3] == "OFF") {
      CheckStatus("", s[0]);
    }
  }

  function open_ChangeColorCheck(mnoswiche) {
    var s = mnoswiche.split(",");
    if (s[2] == "ON") {
      $("#m_" + s[0]).css({
        "background-color": "#d000ff"
      });
      $("#t_" + s[0]).css({
        "color": "#000"
      }); //0000ff
      $("#i_" + s[0]).css({
        "color": "#000"
      });
      $("#ma_" + s[0]).css({
        "color": "#000"
      });
      //$("#t_"+s[0]).addClass("blinking");
      //$("#next_"+s[0]).text(nowDT("ti"));
      $("#next_" + s[0]).css({
        "color": "#000"
      });
      $("#next_ma" + s[0]).css({
        "color": "#000"
      });
    } else if (s[2] == "OFF") {
      CheckStatus("", s[0]);
    }
  }

  function open_ChangeColorALM(mnoswiche) {
    var s = mnoswiche.split(",");
    if (s[3] == "ON") {
      // 色変更と状態保存
      $("#s_temp_" + s[0]).val("mmstop");
      $('.m_box').addClass('blink');
      $(".m_box").css({
        "border-color": "#FFFF00"
      });
      $(".header_line").css({
        "color": "#FFFF00"
      });
      $(".header_line2").css({
        "color": "#FFFF00"
      });
      $(".position").css({
        "color": "#FFFF00"
      });
      //
      $("#m_" + s[0]).css({
        "background-color": "#FFFF00"
      });
      $("#t_" + s[0]).css({
        "color": "#ff0000"
      });
      $("#i_" + s[0]).css({
        "color": "#ff0000"
      });
      $("#next_" + s[0]).text(nowDT("ti"));
      $("#next_" + s[0]).css({
        "color": "#ff0000"
      });
      $("#ma_" + s[0]).css({
        "color": "#ff0000"
      });
      $("#next_ma_" + s[0]).css({
        "color": "#ff0000"
      });

    } else if (s[3] == "OFF") {
      // 色変更と状態保存
      $("#s_temp_" + s[0]).val("");
      var s_temp = $(".s_temp");
      var mms_c = 0;
      for (i = 0; i < s_temp.length; i++) {
        if (s_temp[i].value == "mmstop") {
          mms_c++;
        }
      }
      if (mms_c == 0) {
        $(".m_box").css({
          "border-color": "#fff"
        });
        $(".header_line").css({
          "color": "#fff"
        });
        $(".header_line2").css({
          "color": "#fff"
        });
        $(".position").css({
          "color": "#fff"
        });
        $('.m_box').removeClass('blink');
      }

      $("#t_" + s[0]).removeClass("blinking");
      if (s[2] == "Z") {
        if ($("#i_" + s[0]).text() == "") {
          //$("#s_"+s[0]).val('stop');
        } else {
          //$("#s_"+s[0]).val('稼働中');
        }
      }

      //console.log("表示："+s[0]+",状態,"+$("#s_"+s[0]).val()+",受日時:["+nowDT("yd")+"]");

      CheckStatus("inbox", s[0]);
    }
  }

  function CheckStatus(type, mno) {
    var date = new Date();
    var now_time = Math.floor(date.getTime() / 1000);
    switch ($("#s_" + mno).val()) {
      case '稼働中':
        $("#m_" + mno).show();
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "color": "#00ff99"
        });
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#00ff99"
        });
        $("#next_" + mno).css({
          "color": "#f5deb3"
        });
        $("#ma_" + mno).css({
          "color": "#00ff99"
        });
        $("#next_ma_" + mno).css({
          "color": "#f5deb3"
        });
        break;
      case 'stop':
        if (type == "inbox") {
          $("#t_" + mno).css({
            "color": "#ffffff"
          });
        } else {
          $("#t_" + mno).css({
            "color": "#5f5f5f"
          });
        }
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "font-weight": "normal"
        });
        $("#i_" + mno).text("");
        $("#i_" + mno).css({
          "color": "#000"
        });
        $("#next_" + mno).html("");
        $("#next_" + mno).css({
          "color": "#000"
        });
        $("#ma_" + mno).html("");
        $("#next_ma_" + mno).html("");
        break;
      case '一時停止中':
        $("#m_" + mno).css({
          "background-color": "#ff0000"
        });
        $("#t_" + mno).css({
          "color": "#ffffff"
        });
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#ffffff"
        });
        $("#next_" + mno).css({
          "color": "#ffffff"
        });
        $("#ma_" + mno).css({
          "color": "#ffffff"
        });
        $("#next_ma_" + mno).css({
          "color": "#ffffff"
        });
        break;
      case 'mmstop':
        //$("#m_"+mno).css({"background-color":"#f6bfbc"});
        $("#m_" + mno).css({
          "background-color": "#FFFF00"
        });
        $("#t_" + mno).css({
          "color": "#000"
        }); //00ff99
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#000"
        });
        $("#next_" + mno).css({
          "color": "#000"
        });
        $("#ma_" + mno).css({
          "color": "#000"
        });
        $("#next_ma_" + mno).css({
          "color": "#000"
        });
        break;
      case 'plan':
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "color": "#00ffff"
        });
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#00ffff"
        });
        $("#next_" + mno).css({
          "color": "#f5deb3"
        });
        $("#ma_" + mno).css({
          "color": "#00ffff"
        });
        $("#next_ma_" + mno).css({
          "color": "#f5deb3"
        });
        break;
      case 'trial':
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "color": "#9370db"
        });
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#9370db"
        });
        $("#next_" + mno).css({
          "color": "#f5deb3"
        });
        $("#ma_" + mno).css({
          "color": "#9370db"
        });
        $("#next_ma_" + mno).css({
          "color": "#f5deb3"
        });
        break;
      case '生産入力済':
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "color": "#4f4fef"
        });
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#4f4fef"
        });
        $("#next_" + mno).css({
          "color": "#4f4fef"
        });
        $("#ma_" + mno).css({
          "color": "#4f4fef"
        });
        $("#next_ma_" + mno).css({
          "color": "#4f4fef"
        });
        break;
      case '確認必要':
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "color": "#f7d342"
        });
        $("#t_" + mno).css({
          "font-weight": "bold"
        });
        $("#i_" + mno).css({
          "color": "#f7d342"
        });
        $("#next_" + mno).css({
          "color": "#f7d342"
        });
        $("#ma_" + mno).css({
          "color": "#f7d342"
        });
        $("#next_ma_" + mno).css({
          "color": "#f7d342"
        });
        break;
      case '':
        if (type == "inbox") {
          //$("#t_"+mno).css({"color":"#ff0000"});
          $("#t_" + mno).css({
            "color": "#ffffff"
          });
        } else {
          $("#t_" + mno).css({
            "color": "#5f5f5f"
          });
        }
        $("#m_" + mno).css({
          "background-color": "#000"
        });
        $("#t_" + mno).css({
          "font-weight": "nomal"
        });
        $("#i_" + mno).text("");
        $("#next_" + mno).text("");
        $("#next_" + mno).css({
          "color": "#000"
        });
        $("#ma_" + mno).html("");
        $("#next_ma_" + mno).html("");
        break;
    }
  }

  function orgRound(value, base) {
    return Math.round(value * base) / base;
  }

  // メッセージ受信イベント
  function onMessage(event) {
    if (event && event.data) {
      //console.log("受信："+event.data+",受日時:["+nowDT("yd")+"]");
      if (event.data.indexOf('警報発生') != -1) {
        return;
      }
      var com = event.data.split(",");
      var com_length = com.length - 1;
      if (com[0] == "run_num") {
        $("#now_datetime").text("r:" + nowDT("yd"));
        $("#rn_" + com[1]).text("稼働中【" + com[2] + "】");
        if (com[1].indexOf('-mmstop') != -1) {
          if (com[2] == '0') {
            $("#" + com[1]).hide();
          } else {
            $("#" + com[1]).show();
            $("#" + com[1]).text("（" + com[2] + "）");
          }
        }
        if (com[1].indexOf('-stan') != -1) {
          if (com[2] == '0') {
            $("#" + com[1]).hide();
          } else {
            //$("#"+com[1]).show();
            $("#" + com[1]).text("ST様【" + com[2] + "】");
          }
        } else if (com[1].indexOf('_total_st') != -1) {
          if (parseInt(com[2]) == "0" || !$("#run_" + com[1])) {
            //$(".all-st").hede();
          } else {
            //$("#run_"+com[1]).show();
            //$(".run_"+com[1]).show();
            $("#run_" + com[1]).text("ST様" + "【" + com[2] + "】");
          }
        } else if (com[1].indexOf('-total') != -1) {
          $("#run_" + com[1]).show();
          $(".run_" + com[1]).show();
          $("#run_" + com[1]).text("稼働中【" + com[2] + "】");
        }
      }

      if (com[0] == "m_status") {
        if ($("#m_" + com[1])[0]) {
          if (com[30]) {
            $("#pieces_" + com[1]).val(com[30]);
          }
          if ($("#s_" + com[1]).val() !== undefined) {
            $("#now_datetime").text("m:" + nowDT("yd"));
            if (com[4]) {
              var item = com[4] + "[" + com[5] + "]";
              $("#i_" + com[1]).html(item);
              $("#ma_" + com[1]).html(com[15]);
              /*
              if(item.length>25){
                $("#i_"+com[1]).css({"font-size":"0.6vw"});
              }
              */
            }
            //トレー予測、原料予測表示
            if (com[3] == "稼働中" || com[3] == "trial" || com[3] == "plan" || com[3] == "mmstop") {
              $("#next_" + com[1]).html(com[18]);
              $("#next_ma_" + com[1]).html(com[19]);
              $("#next_" + mno).css("color", "#000");
            } else if (com[3] == "mmstop") {
              $("#next_" + com[1]).text(com[16]);
              $("#next_" + com[1]).css("color", "#ff0000");
            } else if (com[3] == "一時停止中") {
              if (com[22] != "None" || com[22] != "") {
                $("#next_" + com[1]).text(com[22]);
                $("#next_" + com[1]).css("color", "#00FFFF");
              }
            } else {
              $("#next_" + com[1]).html("");
            }

            if (($("#s_" + com[1]).val() != com[3]) & ($("#s_" + com[1]).val() != '生産入力済') & ($("#s_" + com[1]).val() != '確認必要')) {
              $("#s_" + com[1]).val(com[3]);
              CheckStatus("m_status", com[1]);
            }
          }
        }
      }


      if ($("#m_" + com[0])[0]) {
        if (com[1] == "alm") {
          open_ChangeColorALM(event.data);
          return;
        }

        if (com[1] == "lot" && com[2] == "3") {
          $("#now_datetime").text("l3:" + nowDT("yd"));
          open_ChangeColorTray(event.data);
          return;
        }

        if (com[1] == "check") {
          $("#now_datetime").text("c:" + nowDT("yd"));
          open_ChangeColorCheck(event.data);
          return;
        }

        if (com[1] == "inbox") {
          billing(8, "t_" + com[0]);
          return;
          //alerm_view(com[0]);

          /* 19.08.09 コメントアウト 成形機データ判定利用不可
              if(com[2]=="1"){
                  $("#now_datetime").text("i1:"+nowDT("yd"));
                  open_ChangeColorALM(com[0]+",-,-,ON");
              }
              if(com[2]=="0"){
                  $("#now_datetime").text("i0:"+nowDT("yd"));
                  open_ChangeColorALM(com[0]+",-,-,OFF");
              }
            */
        }
      }

      if (com[0] == "demand" && "<?= $sf_params->get('placeid'); ?>" == "1000079") {
        var dt = com[com_length].split(" ");
        var d = dt[0].split("-");
        var t = dt[1].split(":")
        if (com[1] == "new") {
          $("#now_datetime").text("dn:" + nowDT("yd"));
          $("#t_demand").text(com[2]);
          $("#t_demand_day").text(com[com_length]);
          var t_demand_p = orgRound((com[2] / limit_demand) * 100, 100);
          $("#t_demand_p").text(t_demand_p + "%");

          if ((limit_demand * 0.95) >= com[2]) {
            //通常
            $("#de_status").css({
              "color": "#fff"
            });
            $(".m_box2").css({
              "background-color": "#000",
              "color": "#fff",
              "border": "2px solid #fff"
            });
            $("#Clock").css({
              "color": "#fff"
            });
            $("#DayOfWeek").css({
              "color": "#fff"
            });
          } else if ((limit_demand * 0.95) <= com[2] && (limit_demand * 0.98) >= com[2]) {
            //95%以上98%未満;
            $("#de_status").css({
              "color": "#ffff00"
            });
            $(".m_box2").css({
              "background-color": "#000",
              "color": "#ffff00",
              "border": "2px solid #ffff00"
            });
            $("#Clock").css({
              "color": "#ffff00"
            });
            $("#DayOfWeek").css({
              "color": "#ffff00"
            });
          } else if ((limit_demand * 0.98) <= com[2] && (limit_demand) >= com[2]) {
            //98%以上で上限以下の場合
            $("#de_status").css({
              "color": "red"
            });
            $(".m_box2").css({
              "background-color": "#000",
              "color": "red",
              "border": "2px solid red"
            });
            $("#Clock").css({
              "color": "red"
            });
            $("#DayOfWeek").css({
              "color": "red"
            });
          } else if (limit_demand < com[2]) {
            //上限を超えた場合
            $("#de_status").css({
              "color": "#fff"
            });
            $(".m_box2").css({
              "background-color": "red",
              "border": "2px solid #fff",
              "color": "#ffff00"
            });
            $("#Clock").css({
              "color": "#b877d9"
            });
            $("#DayOfWeek").css({
              "color": "#b877d9"
            });
          }

        }

        if (com[1] == "day_max") {
          $("#now_datetime").text("dm:" + nowDT("yd"));
          $("#m_demand").text(com[2]);
          $("#m_demand_day").text(t[0] + ":" + t[1]);
        }

        if (com[1] == "year") {
          $("#now_datetime").text("dym:" + nowDT("yd"));
          $("#y_demand").text(com[2]);
          $("#y_demand_day").text(d[1] + "/" + d[2]);
        }

      }
    }

    if (event.data == "view_reloads") {
      var m_s = $(".m_s");
      for (i = 0; i < m_s.length; i++) {
        var mno = m_s[i].id.split("_");
        CheckStatus("", mno[1])
        /*
        if($("#s_"+mno[1]).val()=="stop"){
          console.log(mno[1]+","+$("#s_"+mno[1]).val());
          $("m_"+mno).css({"background-color":"#000"});
        }
        */
      }
    }

    if (com[0] == "window_reload") {
      if (getGetValue("placeid") == com[1]) {
        location.reload();
      }
    }

    if ($(window).width() > "769") {
      if (com[0] == "view_show") {
        $("#set_view").val(com[0]);
        if (getGetValue("placeid") == com[1]) {
          change_show();
          //console.log("show:"+getGetValue("placeid")+":"+com[1]);
        }
      }

      if (com[0] == "view_hide") {
        $("#set_view").val(com[0]);
        if (getGetValue("placeid") == com[1]) {
          //change_hide();
          //console.log("hide:"+getGetValue("placeid")+":"+com[1]);
        }
      }
    }

    if (com[0] == "swich_materialname") {
      if (getGetValue("placeid") == com[1]) {
        //change_view();
      }
    }
    //stop_hide_show();
  }


  var ws_close_count = 0;
  // 切断イベント
  function onClose(event) {
    //("切断しました。3秒後に再接続します。(" + event.code + ")");
    ws = null;
    setTimeout("WSOpen()", 3000);
  }

  // エラーイベント
  function onError(event) {
    //console.log("エラー発生しました。3秒後に再接続します。(" + event.code + ")");
    ws = null;
    setTimeout("WSOpen()", 3000);
  }

  function billing(num, id) {
    for (var i = 0; i < num; i++) {
      $('#' + id).fadeToggle();
    }
  }

  function nowDT(str, d = null) {
    var weeks = new Array('日', '月', '火', '水', '木', '金', '土');
    var now = new Date();
    var year = now.getYear(); // 年
    var month = now.getMonth() + 1; // 月
    var day = now.getDate(); // 日
    var week = weeks[now.getDay()]; // 曜日
    var hour = now.getHours(); // 時
    var min = now.getMinutes(); // 分
    var sec = now.getSeconds(); // 秒
    if (year < 2000) {
      year += 1900;
    }
    // 数値が1桁の場合、頭に0を付けて2桁で表示する指定
    if (month < 10) {
      month = "0" + month;
    }
    if (day < 10) {
      day = "0" + day;
    }
    if (hour < 10) {
      hour = "0" + hour;
    }
    if (min < 10) {
      min = "0" + min;
    }
    if (sec < 10) {
      sec = "0" + sec;
    }
    if (str == "yd") {
      return year + "/" + month + "/" + day + " " + hour + ":" + min + ":" + sec;
    }
    if (str == "ti") {
      return hour + ":" + min;
    }
  }

  function DTEdit(str, ac = null) {
    var dt = str.split(" ");
    var d = dt[0].split("-");
    var a = d[1].slice(0, 1);
    var t = dt[1].split(":");
    if (ac === null) {
      return t[0] + ":" + t[1];
    } else {
      if (a == "0") {
        d[1] = d[1].replace("0", "");
      }
      return d[1] + "/" + d[2] + " " + t[0] + ":" + t[1];
    }
  }

  function updateClock() {
    var currentTime = new Date();
    var currentHours = currentTime.getHours();
    var currentMinutes = currentTime.getMinutes();
    var currentSeconds = currentTime.getSeconds();
    var currentDate = currentTime.getDate();
    var currentMonth = currentTime.getMonth() + 1;
    var currentYear = currentTime.getFullYear();
    var currentDOW = currentTime.getDay();

    // Pad the minutes and seconds with leading zeros, if required
    currentFormattedMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
    currentFormattedSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

    // Pad the date and month with leading zeros, if required
    currentFormattedDate = (currentDate < 10 ? "0" : "") + currentDate;
    currentFormattedMonth = (currentMonth < 10 ? "0" : "") + currentMonth;

    // Choose either "AM" or "PM" as appropriate
    var AMPM = (currentHours < 12) ? "AM" : "PM";

    // Convert the hours component to 12-hour format if needed
    //currentHours	= ( currentHours > 12 ) ? currentHours - 12 : currentHours;
    // Convert an hours component of "0" to "12"
    currentHours = (currentHours == 0) ? "00" : currentHours;

    // establish the day of the week name
    if (currentDOW == 0) {
      currentDayOfWeek = "日曜日"
    };
    if (currentDOW == 1) {
      currentDayOfWeek = "月曜日"
    };
    if (currentDOW == 2) {
      currentDayOfWeek = "火曜日"
    };
    if (currentDOW == 3) {
      currentDayOfWeek = "水曜日"
    };
    if (currentDOW == 4) {
      currentDayOfWeek = "木曜日"
    };
    if (currentDOW == 5) {
      currentDayOfWeek = "金曜日"
    };
    if (currentDOW == 6) {
      currentDayOfWeek = "土曜日"
    };

    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentFormattedMinutes + "<span class='VSEC'>:" + currentFormattedSeconds + "</span>";

    // Compose the string for display
    var currentISODateString = currentYear + "-" + currentFormattedMonth + "-" + currentFormattedDate;

    // Compose the string for display
    //var currentDateString = currentMonthName + " " + currentDate + ", " + currentYear;
    var currentDateString = currentDate + ", " + currentYear;

    // Update the time display
    document.getElementById("Clock").innerHTML = currentTimeString;
    document.getElementById("DayOfWeek").innerHTML = currentISODateString + "<br>" + currentDayOfWeek;
    //document.getElementById("ISOcalendar").innerHTML = currentISODateString;

    if ($("input[name='自動マージ']").prop('checked')) {
      $(".merge_msg").html("  --  後" + parseInt(60 - currentSeconds) + "秒 データが有れば自動マージします。");
      $(".span_seconds").html(" " + parseInt(60 - currentSeconds) + "秒");
      if (currentSeconds == 0) {
        fb_merge_data(false);
      }
    }
  }

  function change_togle() {
    if ($("#set_view").val() == "view_show") {
      $(".i_text").toggle();
      $(".ma_text").toggle();
      $(".next_text").toggle();
      $(".next_ma_text").toggle();
    }
  }

  function change_view() {
    if ($("#set_view").val() == "view_show") {
      $(".i_text").hide();
      $(".ma_text").show();
      $(".next_text").hide();
      $(".next_ma_text").show();
    } else {
      change_hide();
    }
  }

  function change_hide() {
    $(".i_text").hide();
    $(".ma_text").hide();
    $(".next_text").hide();
    $(".next_ma_text").hide();
    $(".setting_position .all-st").hide();
    $(".setting_position .st_num").hide();
  }

  function change_show() {
    $(".i_text").show();
    $(".ma_text").hide();
    $(".next_text").show();
    $(".next_ma_text").hide();
    $(".setting_position .all-st").show();
    $(".setting_position .st_num").show();
  }

  function get_input() {
    <?php foreach ($listmno as $value) { ?>
      mno = <?php echo ($value['xwrad_mno']); ?>;
      $('#s_' + mno).val('確認必要');
      CheckStatus("確認必要", mno);
    <?php } ?>
  }

  function fb_open_dialog(mno) {
    if ($("#担当者").html() == "") {
      fb_get_user();
      return;
    }
    input_mno = mno;
    $("#dialog_box input").val("");
    $('#dialog_box select').empty();
    $("#btn_execution").val("");
    $("#btn_delete").val("");
    $("#btn_execution").text("登録");
    $("#btn_execution").prop("disabled", false);
    $("#btn_execution").prop("aria-disabled", false);
    $("#btn_execution").removeClass("ui-button-disabled ui-state-disabled");
    $("#btn_delete").text("スキップ");
    $("#btn_delete").prop("disabled", false);
    $("#btn_delete").prop("aria-disabled", false);
    $("#btn_delete").removeClass("ui-button-disabled ui-state-disabled");
    $("input").removeClass("caveat-animation");
    $("input").removeClass('error-animation');
    $("#input_data").css("display", "block");
    $("#error_msg").css("display", "block");
    $("#view_calculation").css("display", "none");
    $("#corner_reference").html("");
    $("#corner_calculation").html("");
    $("#btn_return").prop("disabled", true);
    $("#btn_return").prop("aria-disabled", true);
    $("#btn_return").addClass("ui-button-disabled ui-state-disabled");
    $("#dialog_box").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      title: input_mno + '号機生産情報入力画面',
      position: ["center", 62],
      buttons: []
    });
    var array_data = [];
    var str_xwrad_date = "";
    $("#view_data").html(`<table class="view_data_table">
                            <tr>
                            <th>勤務日</th>
                            <th>成形機</th>
                            <th>打数</th>
                            <th>取数</th>
                            <th>サイクル</th>
                            <th>開始日時</th>
                            <th>終了日時</th>
                            <th class='noborder'></th>
                            </tr>
                          </table>`);
    if (xwrad_data[input_mno]) {
      if ("data" in xwrad_data[input_mno]) {
        Object.keys(xwrad_data[input_mno].data).sort(function(a, b) {
          return b - a;
        }).forEach(function(xwrad_key, xwrad_num) {
          Object.keys(xwrad_data[input_mno].data[xwrad_key]).forEach(function(item) {
            if (!xwrad_data[input_mno].data[xwrad_key][item]) {
              xwrad_data[input_mno].data[xwrad_key][item] = '';
            }
          });
          if (xwrad_num == 0) {
            array_data = xwrad_data[input_mno].data[xwrad_key];
          }
          str_xwrad_date += xwrad_data[input_mno].data[xwrad_key].xwrad_date + "|";
          $(".view_data_table").append(`<tr class="` + input_mno + `-` + xwrad_key + `"  ondblclick="fb_update_dialog('` + xwrad_key + `')">
                            <td style='text-align: right;'><span id='xwrad_date` + xwrad_data[input_mno].data[xwrad_key].xwrad_date + `'>` + fb_format_date(xwrad_data[input_mno].data[xwrad_key].xwrad_date, "YYYY/MM/DD (WW)") + `</span></td>
                            <td style='text-align: center;'>` + xwrad_data[input_mno].data[xwrad_key].xwrad_mno + `</td>
                            <td style='text-align: center;'>` + xwrad_data[input_mno].data[xwrad_key].xwrad_ntab + `</td>
                            <td style='text-align: center;'>` + xwrad_data[input_mno].data[xwrad_key].xwrad_piecis + `</td>
                            <td style='text-align: center;'>` + xwrad_data[input_mno].data[xwrad_key].xwrad_cycle + `</td>
                            <td style='text-align: right;'>` + xwrad_data[input_mno].data[xwrad_key].xwrad_start_date + `(` + array_week[xwrad_data[input_mno].data[xwrad_key].xwrad_start_week_num] + `) ` + xwrad_data[input_mno].data[xwrad_key].xwrad_start_time + `</td>
                            <td style='text-align: right;'>` + xwrad_data[input_mno].data[xwrad_key].xwrad_stop_date + `(` + array_week[xwrad_data[input_mno].data[xwrad_key].xwrad_stop_week_num] + `) ` + xwrad_data[input_mno].data[xwrad_key].xwrad_stop_time + `</td>
                            <td class='noborder'><button class="btn_` + xwrad_key + `" onclick='fb_add_dialog(event,"` + xwrad_key + `")'>+</button></td>
                          </tr>`);
          if (xwrad_data[input_mno].data[xwrad_key].xwrad_t_flg != 1) {
            if (xwrad_data[input_mno].data[xwrad_key].xls_id == "DELETE") {
              $("." + input_mno + `-` + xwrad_key).css("color", "rgb(95, 95, 95)");
              $("." + input_mno + `-` + xwrad_key).css("text-decoration", "line-through");
            } else if (!xwrad_data[input_mno].data[xwrad_key].xwrad_id) {
              $("#btn_return").css("display", "block");
              $("." + input_mno + `-` + xwrad_key).css("color", "rgb(95, 95, 95)");
            } else {
              $("." + input_mno + `-` + xwrad_key).css("color", "#66FF00");
            }
          }
        });
      }
    }
    var input_date = new Date();
    for (let i = 0; i < 30; i++) {
      if ($("input[name='本日抜き']").prop('checked') && i == 0) {} else if (str_xwrad_date.indexOf(fb_format_date(input_date, "YYYY/MM/DD")) == -1) {
        $('#input_date').append('<option value="' + fb_format_date(input_date, "YYYY/MM/DD") + '">' + fb_format_date(input_date, "YYYY/MM/DD (WW)") + '</option>');
        if (new Date($("#inputday").val()) <= input_date) {
          $("#input_date option[value='" + fb_format_date(input_date, "YYYY/MM/DD") + "']").prop('selected', true);
        }
      }
      input_date.setDate(input_date.getDate() - 1);
    }
    var input_stop_date = new Date($("#input_date").val());
    for (let i = 0; i < 7; i++) {
      $('#input_stop_date').append('<option value="' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '">' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '</option>');
      input_stop_date.setDate(input_stop_date.getDate() + 1);
    }
    if ($("#input_date").val() == today) {
      $("#error_msg").html("※注意： " + $("#inputday").val() + "には" + input_mno + "号機が稼働しません。");
    } else {
      $("#input_stop_date").prop("selectedIndex", 1);
      $("#error_msg").html("※注意： " + $("#inputday").val() + "には" + input_mno + "号機が稼働しませんでした。");
    }
    var working_date = new Date($("#input_date").val());
    if (xwrad_data[input_mno]) {
      if ("lot" in xwrad_data[input_mno]) {
        Object.keys(xwrad_data[input_mno]["lot"]).forEach(function(index) {
          var value = xwrad_data[input_mno]["lot"][index];
          $('#input_lot').append('<option value="' + value.LotID + '">' + value.LotID + '【' + value.状態 + '｜' + value.品名 + '｜' + value.lot_date + '】</option>');
          if (fb_comparison(value.lot_start_date, $("#input_date").val(), value.lot_stop_date, value.状態)) {
            $("#input_lot").prop("selectedIndex", index);
            $("#error_msg").css("display", "none");
            $("span [name='ロット管理ID']").removeClass("caveat-animation");;
            if ($("#input_date").val() != today) {
              $("span [name='勤務日']").removeClass("caveat-animation");
            }
            if (value.LotID == array_data.lot_id) {
              $("#input_piecis").val(array_data.xwrad_piecis);
              $("#input_cycle").val(array_data.xwrad_cycle);
            } else {
              $("#input_piecis").val(value.pieces);
              $("#input_cycle").val(value.cycle);
            }
          }
        });
        fb_get_input_start_time();
      }
    }
    $("#dialog_box").dialog("open");
    $("button").button();
    if (new Date($("#inputday").val()) > new Date($("#input_date").val())) {
      fb_set_focus("btn_execution");
      $("#input_data").css("display", "none");
      $("#view_data").css("max-height", parseInt(wh - 100) + "px");
    } else {
      fb_set_focus("input_date");
      $("#view_data").css("max-height", "550px");
    }
    $("#input_date").change(function() {
      fb_input_date_change();
      fb_get_input_start_time();
    });
    $("#input_lot").change(function() {
      $("span [name='ロット管理ID']").addClass("caveat-animation");;
      $("#error_msg").css("display", "block");
      $("#error_msg").html("※注意： ロット管理ID【" + xwrad_data[input_mno]["lot"][$(this).prop("selectedIndex")].LotID + "】と 勤務日【" + $("#input_date").val() + "】が異常発見しました。");
      $("#input_piecis").val(xwrad_data[input_mno]["lot"][$(this).prop("selectedIndex")].pieces);
      $("#input_cycle").val(xwrad_data[input_mno]["lot"][$(this).prop("selectedIndex")].cycle);
      var value = xwrad_data[input_mno]["lot"][$(this).prop("selectedIndex")];
      if (fb_comparison(value.lot_start_date, $("#input_date").val(), value.lot_stop_date, value.状態)) {
        $("#error_msg").css("display", "none");
        $("span [name='ロット管理ID']").removeClass("caveat-animation");;
      }
    });
  }

  function fb_get_input_start_time() {
    if (xwrad_data[input_mno]) {
      if ("data" in xwrad_data[input_mno]) {
        Object.keys(xwrad_data[input_mno].data).forEach(function(xwrad_key) {
          if ($("#input_date").val() == xwrad_data[input_mno].data[xwrad_key].xwrad_stop_fulldate) {
            $("#input_start_time").val(xwrad_data[input_mno].data[xwrad_key].xwrad_stop_time);
            return;
          }
        });
      }
      if ("lot" in xwrad_data[input_mno]) {
        if ($("#input_date").val() == fb_format_date(xwrad_data[input_mno]["lot"][0].lot_start_date, "YYYY/MM/DD")) {
          $("#view_calculation").css("display", "block");
          $("#corner_reference").html(`※ 開始日時参照: ` + xwrad_data[input_mno]["lot"][0].lot_start_date + `
                                    <button id='' onclick='$("#input_start_time").val("` + fb_format_date(xwrad_data[input_mno]["lot"][0].lot_start_date, "hh:mm") + `");'>取得</button>`);
        } else {
          $("#view_calculation").css("display", "none");
        }
      }
    }
    $("button").button();
  }

  function fb_update_dialog(xwrad_key) {
    if (!xwrad_data[input_mno].data[xwrad_key].xwrad_id || xwrad_data[input_mno].data[xwrad_key].xls_id == "DELETE") {
      $("#btn_return").prop("disabled", false);
      $("#btn_return").prop("aria-disabled", false);
      $("#btn_return").removeClass("ui-button-disabled ui-state-disabled");
      $("#btn_return").val(xwrad_key);
    }
    if (xwrad_data[input_mno].data[xwrad_key].xls_id == "DELETE" || xwrad_data[input_mno].data[xwrad_key].xwrad_t_flg == 1) {
      $("#btn_execution").prop("disabled", true);
      $("#btn_execution").prop("aria-disabled", true);
      $("#btn_execution").addClass("ui-button-disabled ui-state-disabled");
      $("#btn_delete").prop("disabled", true);
      $("#btn_delete").prop("aria-disabled", true);
      $("#btn_delete").addClass("ui-button-disabled ui-state-disabled");
      $('#input_lot option').attr('disabled', true);
    } else {
      $("#btn_execution").prop("disabled", false);
      $("#btn_execution").prop("aria-disabled", false);
      $("#btn_execution").removeClass("ui-button-disabled ui-state-disabled");
      $("#btn_delete").prop("disabled", false);
      $("#btn_delete").prop("aria-disabled", false);
      $("#btn_delete").removeClass("ui-button-disabled ui-state-disabled");
      $('#input_lot option').attr('disabled', false);
    }
    $("#btn_execution").val(xwrad_key);
    $("#btn_delete").val(xwrad_key);
    $("#btn_execution").text("更新");
    $("#btn_delete").text("削除");
    $('#input_date').empty();
    $('#input_stop_date').empty();
    $("#error_msg").css("display", "none");
    $("#input_data").css("display", "block");
    $("input").removeClass("caveat-animation");
    $("input").removeClass('error-animation');
    $("#view_data").css("max-height", "550px");
    $('#input_date').append('<option value="' + xwrad_data[input_mno].data[xwrad_key].xwrad_date + '">' + fb_format_date(xwrad_data[input_mno].data[xwrad_key].xwrad_date, "YYYY/MM/DD (WW)") + '</option>');
    $("#input_ntab").val(xwrad_data[input_mno].data[xwrad_key].xwrad_ntab);
    $("#input_piecis").val(xwrad_data[input_mno].data[xwrad_key].xwrad_piecis);
    $("#input_cycle").val(xwrad_data[input_mno].data[xwrad_key].xwrad_cycle);
    $("#input_start_time").val(xwrad_data[input_mno].data[xwrad_key].xwrad_start_time);
    $("#input_stop_time").val(xwrad_data[input_mno].data[xwrad_key].xwrad_stop_time);
    var input_stop_date = new Date($("#input_date").val());
    for (var i = 0; i < 7; i++) {
      $('#input_stop_date').append('<option value="' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '">' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '</option>');
      input_stop_date.setDate(input_stop_date.getDate() + 1);
    }
    $("#input_stop_date option[value='" + xwrad_data[input_mno].data[xwrad_key].xwrad_stop_fulldate + "']").prop('selected', true);
    $("#input_lot option[value='" + xwrad_data[input_mno].data[xwrad_key].lot_id + "']").prop('selected', true);
    if (xwrad_data[input_mno].data[xwrad_key].xwrad_t_flg == 1) $('#input_stop_date option').attr('disabled', true);
    fb_set_focus("input_date");
    fb_input_calculation();
  }

  function fb_add_dialog(e, add_id) {
    if (!e) var e = window.event; // Get the window event
    e.cancelBubble = true; // IE Stop propagation
    if (e.stopPropagation) e.stopPropagation(); // Other Broswers
    $('#input_lot option').attr('disabled', false);
    $("#dialog_box input").val("");
    $("#input_data").css("display", "block");
    $("#btn_execution").val("");
    $("#btn_delete").val("");
    $("#btn_execution").text("登録");
    $("#btn_execution").prop("disabled", false);
    $("#btn_execution").prop("aria-disabled", false);
    $("#btn_execution").removeClass("ui-button-disabled ui-state-disabled");
    $("#btn_delete").text("スキップ");
    $("#btn_delete").prop("disabled", false);
    $("#btn_delete").prop("aria-disabled", false);
    $("#btn_delete").removeClass("ui-button-disabled ui-state-disabled");
    $("#input_date").empty();
    $("#input_stop_date").empty();
    $("#error_msg").css("display", "none");
    $("input").removeClass("caveat-animation");
    $("input").removeClass('error-animation');
    $("#view_calculation").css("display", "none");
    $("#view_data").css("max-height", "550px");
    $("#input_date").append('<option value="' + xwrad_data[input_mno].data[add_id].xwrad_date + '">' + fb_format_date(xwrad_data[input_mno].data[add_id].xwrad_date, "YYYY/MM/DD (WW)") + '</option>');
    var input_stop_date = new Date($("#input_date").val());
    for (var i = 0; i < 7; i++) {
      $('#input_stop_date').append('<option value="' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '">' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '</option>');
      input_stop_date.setDate(input_stop_date.getDate() + 1);
    }
    $("#input_stop_date").prop("selectedIndex", 1);
    $("#input_lot option[value='" + xwrad_data[input_mno].data[add_id].lot_id + "']").prop('selected', true);
    $("#input_piecis").val(xwrad_data[input_mno]["lot"][$("#input_lot").prop("selectedIndex")].pieces);
    $("#input_cycle").val(xwrad_data[input_mno]["lot"][$("#input_lot").prop("selectedIndex")].cycle);
    fb_set_focus("input_date");
  };

  function fb_execution(item) {
    if (!fb_input_check("#dialog_box input[type=time]")) {
      return;
    }
    const input_start = $("#input_date").val() + " " + $('#input_start_time').val() + ":00";
    const input_stop = $("#input_stop_date").val() + " " + $('#input_stop_time').val() + ":00";
    if (input_start == input_stop) {
      $('#input_start_time').addClass('error-animation');
      $('#input_stop_time').addClass('error-animation');
      return;
    }
    $("#dialog_box input[type=time]").removeClass('error-animation');
    var input_id = null;
    var xwrad_key = null;
    if (item.innerHTML == "更新") {
      let new_data_check = {
        "lot_id": $("#input_lot").val(),
        "xwrad_ntab": $("#input_ntab").val(),
        "xwrad_piecis": $("#input_piecis").val(),
        "xwrad_cycle": $("#input_cycle").val(),
        "xwrad_start_at": input_start,
        "xwrad_stop_at": input_stop,
      };
      let is_check_new_data = false;
      Object.keys(new_data_check).forEach((val, key) => {
        if (new_data_check[val] != xwrad_data[input_mno].data[item.value][val]) {
          is_check_new_data = true;
        }
      });
      if (!is_check_new_data) {
        fb_open_dialog(input_mno);
        return;
      }
      input_id = xwrad_data[input_mno].data[item.value].xwrad_id;
      if (!confirm('本当に更新ですか。')) {
        return;
      }
      xwrad_key = item.value;
    }
    let new_data_save = {
      "xwrad_mno": input_mno,
      "xwrad_date": $("#input_date").val(),
      "lot_id": $("#input_lot").val(),
      "xwrad_ntab": $("#input_ntab").val(),
      "xwrad_piecis": $("#input_piecis").val(),
      "xwrad_cycle": $("#input_cycle").val(),
      "xwrad_placeid": placeid,
      "xwrad_start_at": input_start,
      "xwrad_stop_at": input_stop,
      "xwrad_id": input_id,
      "xwrad_name": $("#担当者").html(),
      "created_at": fb_format_date(new Date(), "YYYY/MM/DD hh:mm:ss"),
      "xwrad_key": xwrad_key,
    };
    if (input_id) {
      xwrad_change_save["UPDATE"][input_mno + "-" + xwrad_key] = new_data_save;
    } else {
      xwrad_change_save["ADD"][input_mno + "-" + fb_get_xwrad_key(new_data_save)] = new_data_save;
    }
    fb_save_and_load_new_data_count();
    if (item.innerHTML == "更新") {
      xwrad_data[input_mno].data[xwrad_key] = fb_convert_data(new_data_save);
    } else {
      xwrad_data[input_mno].data[fb_get_xwrad_key(new_data_save)] = fb_convert_data(new_data_save);
      $('#s_' + input_mno).val('生産入力済');
      CheckStatus("生産入力済", input_mno);
    }
    fb_open_dialog(input_mno);
  }

  function fb_get_xwrad_key(obj_array) {
    return obj_array.xwrad_key ?? String(fb_format_date(obj_array.xwrad_date, "YYYYMMDD")) + String(fb_format_date(obj_array.created_at, "YYYYMMDDhhmmss"));
  }

  function fb_convert_data(new_data_save, xwrad_id, xls_id) {
    let new_convert_data = {
      "created_at": new_data_save.created_at,
      "lot_id": new_data_save.lot_id,
      "xls_id": xls_id ?? null,
      "xwrad_cycle": new_data_save.xwrad_cycle,
      "xwrad_date": new_data_save.xwrad_date,
      "xwrad_date_week_num": fb_format_date(new_data_save.xwrad_date, "WN"),
      "xwrad_id": xwrad_id ?? null,
      "xwrad_mno": new_data_save.xwrad_mno,
      "xwrad_name": new_data_save.xwrad_name,
      "xwrad_ntab": new_data_save.xwrad_ntab,
      "xwrad_piecis": new_data_save.xwrad_piecis,
      "xwrad_placeid": new_data_save.xwrad_placeid,
      "xwrad_start_at": new_data_save.xwrad_start_at,
      "xwrad_start_date": fb_format_date(new_data_save.xwrad_start_at, "MM/DD"),
      "xwrad_start_fulldate": fb_format_date(new_data_save.xwrad_start_at, "YYYY/MM/DD"),
      "xwrad_start_time": fb_format_date(new_data_save.xwrad_start_at, "hh:mm"),
      "xwrad_start_week_num": fb_format_date(new_data_save.xwrad_start_at, "WN"),
      "xwrad_stop_at": new_data_save.xwrad_stop_at,
      "xwrad_stop_date": fb_format_date(new_data_save.xwrad_stop_at, "MM/DD"),
      "xwrad_stop_fulldate": fb_format_date(new_data_save.xwrad_stop_at, "YYYY/MM/DD"),
      "xwrad_stop_time": fb_format_date(new_data_save.xwrad_stop_at, "hh:mm"),
      "xwrad_stop_week_num": fb_format_date(new_data_save.xwrad_stop_at, "WN"),
      "xwrad_t_flg": 0,
    };
    return new_convert_data;
  }

  function fb_merge_data(flg_view) {
    if (Object.keys(xwrad_change_save["ADD"]).length > 0 || Object.keys(xwrad_change_save["UPDATE"]).length > 0 || Object.keys(xwrad_change_save["DELETE"]).length > 0) {
      if (ajax_send) {
        return;
      }
      if (flg_view) {
        fb_loading(true);
      }
      const xwrad_merge_array = Object.assign({}, JSON.parse(JSON.stringify(xwrad_change_save)));
      const log_count_merge = "登録: " + Object.keys(xwrad_change_save["ADD"]).length + "件 更新: " + Object.keys(xwrad_change_save["UPDATE"]).length + "件 削除: " + Object.keys(xwrad_change_save["DELETE"]).length + "件<br>";
      var re_load_mno;
      $.ajax({
        type: 'POST',
        url: "",
        dataType: 'text',
        data: {
          ac: "生産情報",
          data: xwrad_change_save
        },
        success: function(d) {
          console.log(d);
          if (d.indexOf('MERGE_OK')) {
            var flg_again_open_dialog = false;
            const obj_d = JSON.parse(d.split('|')[1]);
            const error_count = Object.keys(obj_d.ERROR_ADD).length + Object.keys(obj_d.ERROR_UPDATE).length + Object.keys(obj_d.ERROR_DELETE).length;
            let log_view = "<span style='font-weight: bold; color:";
            log_view += error_count > 0 ? "red'>マージ失敗" : "blue'>マージ正常";
            log_view += "(" + fb_format_date(new Date(), "MM/DD hh:mm:ss") + ")</span><br>";
            $("#log_view").append(log_view);
            if (flg_view) {
              alert_view = "(" + fb_format_date(new Date(), "MM/DD hh:mm:ss") + "): マージ終了 \n" +
                "【登録】OK: " + Object.keys(obj_d.OK_ADD).length + "件 --- NG: " + Object.keys(obj_d.ERROR_ADD).length + "件\n" +
                "【更新】OK: " + Object.keys(obj_d.OK_UPDATE).length + "件 --- NG: " + Object.keys(obj_d.ERROR_UPDATE).length + "件\n" +
                "【削除】OK: " + Object.keys(obj_d.OK_DELETE).length + "件 --- NG: " + Object.keys(obj_d.ERROR_DELETE).length + "件\n" +
                "ログで詳しく確認してください。";
              alert(alert_view)
            }
            if (Object.keys(obj_d.OK_ADD).length > 0) {
              obj_d.OK_ADD.forEach((value, key) => {
                delete xwrad_change_save.ADD[value];
                $("." + value).css("color", "#66FF00");
                xwrad_data[value.split('-')[0]].data[value.split('-')[1]].xwrad_id = obj_d.ADD_ID[value];
                xwrad_change_count.ADD++;
              });
            }
            fb_view_merge(xwrad_merge_array.ADD, obj_d.OK_ADD, obj_d.ERROR_ADD, "【登録】");
            if (Object.keys(obj_d.OK_UPDATE).length > 0) {
              obj_d.OK_UPDATE.forEach((value, key) => {
                delete xwrad_change_save.UPDATE[value.split('_')[0]];
                $("." + value.split('_')[0]).css("color", "#66FF00");
                xwrad_data[value.split('-')[0]].data[value.split('_')[0].split('-')[1]].xwrad_id = value.split('_')[1];
                xwrad_change_count.UPDATE++;
              });
            }
            fb_view_merge(xwrad_merge_array.UPDATE, obj_d.OK_UPDATE, obj_d.ERROR_UPDATE, "【更新】");
            if (Object.keys(obj_d.OK_DELETE).length > 0) {
              obj_d.OK_DELETE.forEach((value, key) => {
                if ($('#dialog_box').dialog('isOpen') && (input_mno == value.split('-')[0]) && ($("#btn_execution").val() == value.split('-')[1])) {
                  flg_again_open_dialog = true;
                }
                delete xwrad_change_save.DELETE[value];
                delete xwrad_data[value.split('-')[0]].data[value.split('-')[1]];
                $("." + value).css("display", "none");
                xwrad_change_count.DELETE++;
              });
            }
            fb_view_merge(xwrad_merge_array.DELETE, obj_d.OK_DELETE, obj_d.ERROR_DELETE, "【削除】");
            fb_save_and_load_new_data_count();
            if (flg_again_open_dialog) {
              fb_open_dialog(input_mno);
            }
          } else {
            if (flg_view) {
              alert("失敗しました。\n再実行して下さい。")
            }
            $("#log_view").append("<span style='color:orange'>失敗</span> (" + fb_format_date(new Date(), "MM/DD hh:mm:ss") + "): " + log_count_merge);
            fb_view_not_merge(xwrad_merge_array, "※ <span style='font-weight:bold;'>失敗されたデータ:</span> <br>");
          }
          fb_loading(false);
          ajax_send_error_count = 0;
        },
        error: function() {
          if (flg_view) {
            $("#log_view").append("<span style='color:red'>エラー</span> (" + fb_format_date(new Date(), "MM/DD hh:mm:ss") + "): " + log_count_merge);
            fb_view_not_merge(xwrad_merge_array, "※ <span style='font-weight:bold;'>エラーが発生されたデータ:</span> <br>");
            alert("エラーがあるので そのままに自動化技術課」に連絡して下さい。")
          } else {
            ajax_send_error_count++;
            if (ajax_send_error_count > 2) {
              $("input[name='自動マージ']").prop("checked", "");
              localStorage.setItem("自動マージ", "");
              $("#log_view").append("<span style='color:red'>エラー</span> (" + fb_format_date(new Date(), "MM/DD hh:mm:ss") + "): " + log_count_merge);
              fb_view_not_merge(xwrad_merge_array, "※ <span style='font-weight:bold;'>エラーが発生されたデータ:</span> <br>");
              alert("エラーがあるので そのままに自動化技術課」に連絡して下さい。\n自動マージチェックボックスが抜きました。");
            }
          }
          fb_loading(false);
        }
      });
    } else if (flg_view) {
      alert("新しいデータがありません。");
    }
  }

  function fb_del_skip(item) {
    if (item.innerHTML == "スキップ") {
      if ($("#input_date").prop("selectedIndex") == 0) {
        $("#input_data").css("display", "none");
      } else {
        $("#input_date").prop("selectedIndex", $("#input_date").prop("selectedIndex") - 1);
        fb_input_date_change();
      }
    } else {
      if (!confirm('本当に削除ですか。')) {
        return;
      }
      var xwrad_key = input_mno + "-" + item.value;
      if (xwrad_key in xwrad_change_save["ADD"]) {
        delete xwrad_change_save["ADD"][xwrad_key];
        delete xwrad_data[input_mno].data[item.value];
      } else {
        if (xwrad_key in xwrad_change_save["UPDATE"]) {
          xwrad_data[input_mno].data[item.value].xwrad_id = xwrad_change_save["UPDATE"][xwrad_key].xwrad_id;
          delete xwrad_change_save["UPDATE"][xwrad_key];
        }
        xwrad_change_save["DELETE"][xwrad_key] = xwrad_data[input_mno].data[item.value];
        xwrad_data[input_mno].data[item.value].xls_id = "DELETE";
      }
      fb_save_and_load_new_data_count();
      fb_open_dialog(input_mno);
    }
  }

  function fb_return(item) {
    var return_mnokey = input_mno + "-" + item.value;
    if (return_mnokey in xwrad_change_save.ADD) {
      delete xwrad_change_save.ADD[return_mnokey];
      delete xwrad_data[input_mno].data[item.value];
    } else {
      if (return_mnokey in xwrad_change_save.UPDATE) {
        delete xwrad_change_save.UPDATE[return_mnokey];
      } else if (return_mnokey in xwrad_change_save.DELETE) {
        delete xwrad_change_save.DELETE[return_mnokey];
      }
      xwrad_data[input_mno].data[item.value] = xwrad_data_return[input_mno].data[item.value];
    }
    fb_save_and_load_new_data_count();
    fb_open_dialog(input_mno);
  }

  function fb_all_return() {
    if (!confirm('本当に差戻ですか。')) {
      return;
    }
    xwrad_change_save = {
      ADD: {},
      UPDATE: {},
      DELETE: {}
    };
    xwrad_data = <?php echo htmlspecialchars_decode($xwrad_data); ?>;;
    fb_save_and_load_new_data_count();
  }

  function fb_save_and_load_new_data_count() {
    if (Object.keys(xwrad_change_save["ADD"]).length > 0 ||
      Object.keys(xwrad_change_save["UPDATE"]).length > 0 ||
      Object.keys(xwrad_change_save["DELETE"]).length > 0) {
      $("#btn_all_return").prop("disabled", false);
      $("#btn_all_return").prop("aria-disabled", false);
      $("#btn_all_return").removeClass("ui-button-disabled ui-state-disabled");
      $("#btn_merge_data").addClass("caveat-animation");
    } else {
      $("#btn_all_return").prop("disabled", true);
      $("#btn_all_return").prop("aria-disabled", true);
      $("#btn_all_return").addClass("ui-button-disabled ui-state-disabled");
      $("#btn_merge_data").removeClass("caveat-animation");
    }
    localStorage.setItem("xwrad_change_save", JSON.stringify(xwrad_change_save));
    $(".add_count").html("※ 登録: " + Object.keys(xwrad_change_save["ADD"]).length + "件");
    $(".update_count").html("※ 更新: " + Object.keys(xwrad_change_save["UPDATE"]).length + "件");
    $(".delete_count").html("※ 削除: " + Object.keys(xwrad_change_save["DELETE"]).length + "件");
    $(".added_count").html("※ 登録: " + xwrad_change_count.ADD + "件");
    $(".updated_count").html("※ 更新: " + xwrad_change_count.UPDATE + "件");
    $(".deleted_count").html("※ 削除: " + xwrad_change_count.DELETE + "件");
  }

  function fb_input_date_change() {
    $("span [name='勤務日']").addClass("caveat-animation");
    $("#dialog_box input").val("");
    $("#error_msg").css("display", "block");
    var input_date = new Date($("#input_date").val());
    Object.keys(xwrad_data[input_mno]["lot"]).forEach(function(key) {
      if (fb_comparison(xwrad_data[input_mno]["lot"][key].lot_start_date, $("#input_date").val(), xwrad_data[input_mno]["lot"][key].lot_stop_date, xwrad_data[input_mno]["lot"][key].状態)) {
        $("#input_lot option[value='" + xwrad_data[input_mno]["lot"][key].LotID + "']").prop('selected', true);
        $("#input_piecis").val(xwrad_data[input_mno]["lot"][key].pieces);
        $("#input_cycle").val(xwrad_data[input_mno]["lot"][key].cycle);
        $("#error_msg").css("display", "none");
        $("span [name='勤務日']").removeClass("caveat-animation");
        $("span [name='ロット管理ID']").removeClass("caveat-animation");;
      }
    });
    var input_stop_date = new Date($("#input_date").val());
    $('#input_stop_date').empty();
    for (let i = 0; i < 7; i++) {
      $('#input_stop_date').append('<option value="' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '">' + fb_format_date(input_stop_date, "YYYY/MM/DD") + '</option>');
      input_stop_date.setDate(input_stop_date.getDate() + 1);
    }
    if ($("#input_date").val() != today) {
      $("#error_msg").html("※注意： " + $("#input_date").val() + "には" + input_mno + "号機が稼働しませんでした。");
      input_date.setDate(input_date.getDate() + 1);
      $("#input_stop_date option[value='" + fb_format_date(input_date, "YYYY/MM/DD") + "']").prop('selected', true);
    } else {
      $("#error_msg").html("※注意： " + $("#input_date").val() + "には" + input_mno + "号機が稼働しません。");
    }
  }

  function fb_clear(item) {
    $("input[name='" + item.innerHTML + "']").val("");
  }

  function fb_set_focus(id) {
    var next_tabindex = parseInt($("#" + id).attr("tabindex")) + 1;
    var nextElement = $('[tabindex="' + next_tabindex + '"]');
    if (nextElement.length) {
      if ($('[tabindex="' + next_tabindex + '"]').val() == '') {
        $('[tabindex="' + next_tabindex + '"]').focus();
      } else {
        fb_set_focus($('[tabindex="' + next_tabindex + '"]').attr("id"));
      }
    }
    return;
  }

  function fb_comparison(datetime_start, datetime_subject, datetime_stop, situation) {
    var in_datetime_start = new Date(datetime_start);
    var in_datetime_subject = new Date(datetime_subject);
    var in_datetime_stop = new Date(datetime_stop);
    if ((fb_format_date(in_datetime_start, "YYYYMMDD") <= fb_format_date(in_datetime_subject, "YYYYMMDD") &&
        fb_format_date(in_datetime_subject, "YYYYMMDD") <= fb_format_date(in_datetime_stop, "YYYYMMDD")) ||
      (fb_format_date(in_datetime_start, "YYYYMMDD") <= fb_format_date(in_datetime_subject, "YYYYMMDD") && situation == "稼働中")) {
      return true;
    } else {
      return false;
    }
  }

  function fb_screensend_view() {
    localStorage.removeItem("bk_save_data");
    localStorage.removeItem("bk_data");
    localStorage.removeItem("担当者");
    window.open('DailyProduction/DailyReport?placeid=' + placeid, '_blank');
  }

  function fb_get_user() {
    $("#msg_box").dialog({
      autoOpen: false,
      width: ww,
      modal: true,
      title: '先に担当者を選択して下さい',
      position: ["center", 100],
      buttons: []
    });
    $.getJSON("/common/AjaxUserSelect?action=user&gp1=" + decodeURIComponent("山崎") + "&gp2=" + decodeURIComponent("管理技術係") /*+"&callback=?"*/ , function(data) {
      $("#message").html(data);
    });
    $("#msg_box").dialog("open");
  }

  function setUser(name) {
    $("#担当者").html(name);
    var tanto = {
      name: name,
      today: today
    }
    localStorage.setItem("生産情報入力者", JSON.stringify(tanto));
    $("#message").html('');
    $("#msg_box").dialog("close");
  }

  function fb_format_date(inputdate, format) {
    var date = new Date(inputdate);
    var weekday = ["日", "月", "火", "水", "木", "金", "土"];
    if (!format) {
      format = 'YYYY/MM/DD(WW)(WN) hh:mm:ss'
    }
    format = format.replace(/YYYY/g, date.getFullYear());
    format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
    format = format.replace(/DD/g, ('0' + date.getDate()).slice(-2));
    format = format.replace(/WW/g, weekday[date.getDay()]);
    format = format.replace(/WN/g, date.getDay());
    format = format.replace(/hh/g, ('0' + date.getHours()).slice(-2));
    format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2));
    format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
    return format;
  }

  function fb_loading(flag) {
    $('#loading').remove();
    if (!flag) return;
    $('<div id="loading" />').appendTo('body');
  }

  function fb_input_check(find_subject) {
    var is_input_check = true;
    const const_subject = $(find_subject);
    for (let i = 0; i < const_subject.length; i++) {
      if (const_subject[i].value == "") {
        $(const_subject[i]).addClass('error-animation');
        is_input_check = false;
      } else {
        $(const_subject[i]).removeClass('error-animation');
      }
    }
    return is_input_check;
  }

  function fb_btn_number_click(str) {
    var view_number = $("#view_number").html();
    if (str == "クリア") {
      $("#view_number").html("");
    } else if (str == "←") {
      $("#view_number").html(view_number.substring(0, view_number.length - 1));
    } else if (str == "←←") {
      $("#view_number").html(view_number.substring(0, view_number.length - 2));
    } else {
      $("#view_number").append(str);
    }
  }

  function fb_btn_datetime_click(str) {
    if (str == "C") {
      $("#view_date").html("");
    } else if (str == "En") {
      alert("決定" + $("#view_date").text());
      $("#view_date").html("");
    } else {
      $("#view_date").append(str);
    }
    $("#view_date").removeClass('error-animation');
  }

  function btn_set_datetime(this_input) {
    const isDate = (v) => !isNaN(new Date(v).getTime());
    var year = $("#screen_input_year").text();
    var mounth = $("#screen_input_mounth").text();
    var day = $("#screen_input_day").text();
    var houre = $("#screen_input_houre").text();
    var minits = $("#screen_input_minits").text();
    var out_date = year + "/" + mounth + "/" + day;
    var out_time = houre + ":" + minits;
    var out_datetime = out_date + " " + out_time;
    if (isDate(out_datetime)) {
      if (this_input.name == '終了日時') {
        if (!$('#input_stop_date').find("option:contains('" + out_date + "')").length) {
          $("#div_view_datatime").addClass('error-animation');
          return false;
        }
        $('#input_stop_date').val(out_date);
      }
      $(this_input).val(out_time);
      return true;
    } else {
      $("#div_view_datatime").addClass('error-animation');
      return false;
    }
  }

  function fb_set_mounth() {
    var mounth = $.trim($("#view_date").text());
    if (0 < mounth && mounth < 13) {
      $("#view_date").html("");
      $("#screen_input_mounth").text(("0" + mounth).slice(-2));
    } else {
      $("#view_date").addClass('error-animation');
    }
  }

  function fb_set_day() {
    var day = $.trim($("#view_date").text());
    if (0 < day && day < 32) {
      $("#view_date").html("");
      $("#screen_input_day").text(("0" + day).slice(-2));
    } else {
      $("#view_date").addClass('error-animation');
    }
  }

  function fb_set_houre() {
    var houre = $.trim($("#view_date").text());
    if (houre < 24) {
      $("#view_date").html("");
      $("#screen_input_houre").text(("0" + houre).slice(-2));
    } else {
      $("#view_date").addClass('error-animation');
    }
  }

  function fb_set_minits() {
    var minits = $.trim($("#view_date").text());
    if (minits < 60) {
      $("#view_date").html("");
      $("#screen_input_minits").text(("0" + minits).slice(-2));
    } else {
      $("#view_date").addClass('error-animation');
    }
  }

  function fb_set_year(str) {
    var year = $.trim($("#view_date").text());
    var this_year = new Date().getFullYear();
    if (this_year - 1 <= year && year <= this_year + 1) {
      $("#screen_input_year").html(year);
      $("#view_date").html("");
    } else {
      $("#view_date").addClass('error-animation');
    }
  }

  function fb_datetime_clear() {
    $("#view_date").html("");
    $("#screen_input_houre").html("");
    $("#screen_input_minits").html("");
  }

  function fb_return_default() {
    $("#view_date").html("");
    $("#div_view_datatime").removeClass('error-animation');
    $("#view_date").removeClass('error-animation');
    var date = new Date($("#save_default_input").html());
    var y = date.getFullYear();
    var mo = date.getMonth() + 1;
    var d = date.getDate();
    var h = date.getHours();
    var m = date.getMinutes();
    $("#screen_input_year").text(y);
    $("#screen_input_mounth").text(("0" + mo).slice(-2));
    $("#screen_input_day").text(("0" + d).slice(-2));
    $("#screen_input_houre").text(("0" + h).slice(-2));
    $("#screen_input_minits").text(("0" + m).slice(-2));
  }

  function fb_view_not_merge(view_data, view_theme) {
    if (Object.keys(view_data["ADD"]).length > 0 || Object.keys(view_data["UPDATE"]).length > 0 || Object.keys(view_data["DELETE"]).length > 0) {
      $("#log_view").append(view_theme);
      let i = 1;
      Object.keys(view_data["ADD"]).forEach((value, key) => {
        $("#log_view").append(i + ".【登録】 成形機： " + view_data["ADD"][value].xwrad_mno + "   勤務日： " + view_data["ADD"][value].xwrad_date + "<br>");
        i++;
      });
      Object.keys(view_data["UPDATE"]).forEach((value, key) => {
        $("#log_view").append(i + ".【更新】 成形機： " + view_data["UPDATE"][value].xwrad_mno + "   勤務日： " + view_data["UPDATE"][value].xwrad_date + "<br>");
        i++;
      });
      Object.keys(view_data["DELETE"]).forEach((value, key) => {
        $("#log_view").append(i + ".【削除】 成形機： " + value.split('-')[0] + "   勤務日： " + xwrad_data[value.split('-')[0]].data[value.split('-')[1]].xwrad_date + "<br>");
        i++;
      });
    }
  }

  function fb_view_merge(obj_data, obj_OK, obj_ERROR, view_theme) {
    if (Object.keys(obj_data).length > 0) {
      let i = 1;
      let log_view = "<span style='font-weight: bold;'>" + view_theme + "</span><span style='color:";
      log_view += Object.keys(obj_OK).length > 0 ? "blue" : "";
      log_view += "'>OK: " + Object.keys(obj_OK).length + "件</span>　　---　　<span style='color:";
      log_view += Object.keys(obj_ERROR).length > 0 ? "red" : "";
      log_view += "'>NG: " + Object.keys(obj_ERROR).length + "件</span><br>";
      $("#log_view").append(log_view);
      Object.keys(obj_data).forEach((value, key) => {
        $("#log_view").append("<span style='margin-left: 20px;'>" + i + ". 成形機： " + obj_data[value].xwrad_mno + "　　　勤務日： " + obj_data[value].xwrad_date + "</span><br>");
        i++;
      });
    }
  }
</script>

<div class="m_cont">
  <input type="hidden" id="set_view" value="view_hide" />
  <input type="hidden" id="set_placeid" value="<?= $sf_params->get('placeid'); ?>" />
  <div id="m_andon">
    <div class="header_line2" style="vertical-align: middle;">
      <div style="float: left;text-align: center; padding:4px">
        <label for="inputday">開始日:</label>
        <select name="inputday" id="inputday">
          <?php
          $week = ["日", "月", "火", "水", "木", "金", "土"];
          for ($x = 0; $x <= 30; $x++) {
            $day = date("Y/m/d", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
            $weeknum = date("w", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
            $weekday = $week[$weeknum];
            if ($weekday == $week[0]) {
          ?>
              <option value="<?php echo $day ?>" style="color:red"><?php echo  $day . "(" . $weekday . ")" ?></option>
            <?php
            } else if ($weekday == $week[6]) {
            ?>
              <option value="<?php echo $day ?>" style="color:blue"><?php echo  $day . "(" . $weekday . ")" ?></option>
            <?php
            } else {
            ?>
              <option value="<?php echo $day ?>"><?php echo  $day . "(" . $weekday . ")" ?></option>
          <?php
            }
          } ?>
        </select>
        <button type="button" onclick="fb_screensend_view();">転送</button>
        <button id="btn_merge_data" type="button" onclick="fb_merge_data(true);">マージ<span class="span_seconds"></span></button>
        <button type="button" onclick="fb_get_user();">担当者</button>
        <label id="担当者"></label>
      </div>
      <div style="float: right;text-align: center; margin:10px 40px 10px 0px">
        <i onclick="fb_show_bars_tab();" class="fa fa-bars" aria-hidden="true"></i>
      </div>
    </div>
    <div style="clear: both;"></div>

    <?php foreach ($machine as $keys => $mnoss) { ?>
      <?php foreach ($mnoss as $key => $mnos) { ?>
        <div id="<?= $keys ?>">
          <div class="position"><?php echo strtoupper($key); ?>
            <span class="setting_position">
              設置【<?php echo count($mnos); ?>】
              <span id="rn_<?php echo $key; ?>"></span>
              <span id="<?php echo $key; ?>-mmstop"></span>
              <label class='st_num' id='<?php echo $key . '-stan'; ?>'><span id="rn_<?php echo $key . '-stan'; ?>"></span></label>
            </span>
          </div>
          <div style="clear: both;"></div>
          <?php foreach ($mnos as $mno => $status) { ?>
            <div id="m_<?php echo $mno; ?>" class="m_box" onclick="fb_open_dialog('<?= $mno ?>','<?php echo strtoupper($key); ?>');">
              <p id="t_<?php echo $mno; ?>" class="m_text m_text_color_stop"><?php echo $mno; ?></p>
              <p id="next_<?php echo $mno; ?>" class="next_text"></p>
              <p id="next_ma_<?php echo $mno; ?>" class="next_ma_text"></p>
              <p id="i_<?php echo $mno; ?>" class="i_text"></p>
              <p id="ma_<?php echo $mno; ?>" class="ma_text"></p>
              <input type="hidden" id="s_<?php echo $mno; ?>" value="" class="m_s" />
              <input type="hidden" id="s_temp_<?php echo $mno; ?>" value="" class="s_temp" />
              <input type="hidden" id="pieces_<?php echo $mno; ?>" value="" class="s_temp" />
              <div style="clear: both;"></div>
            </div>
          <?php } ?>
        </div>
        <div style="clear: both;"></div>

      <?php }
      if ($keys == "1000079") { ?>
        <div id="de_view" class="demand_area">
          <p class="position_d" id="de_status">デマンド</p>
          <div class="m_box2" style="width:16vw;">
            <span id="t_demand_day" class="d_text_label">---</span>
          </div>
          <div class="m_box2" style="width:8vw;" id="t_demand_p"></div>
          <div class="m_box2">
            <span id="t_demand" class="d_text">---</span>
            <span style="font-size:24px">kW</span>
          </div>
          <div class="m_box2" style="width:8vw;">
            <span class="d_text_label">本日最大</span>
          </div>
          <div class="m_box2" style="width:8vw;">
            <span id="m_demand_day" class="d_text">---</span>
          </div>
          <div class="m_box2">
            <span id="m_demand" class="d_text">---</span>
            <span style="font-size:24px">kW</span>
          </div>
          <div class="m_box2" style="width:8vw;">
            <span class="d_text_label">年間最大</span>
          </div>
          <div class="m_box2" style="width:8vw;">
            <span id="y_demand_day" class="d_text">---</span>
          </div>
          <div class="m_box2">
            <span id="y_demand" class="d_text ">---</span>
            <span style="font-size:24px">kW</span>
          </div>
          <div style="clear: both;"></div>
        </div>
    <?php }
    } ?>
  </div>
  <div id="c_area">
    <p id="Clock"></p>
    <p id="DayOfWeek"></p>
  </div>
  <div id="msg_box" style="margin: auto; text-align: center; font-size:18px;">
    <div id="message"></div>
  </div>
  <div class="bars_tab" style="display: none; font-size:18px;">
    <div style="width: 20%; float:left;">
      <p style="padding: 15px 0px 0px 20px;">
        <input type="checkbox" name="本日抜き" id="checkbox_01" />
        <label style="font-size: 18px;" for="checkbox_01" name="本日抜き">本日抜き</label>
      </p>
      <p style="padding: 15px 0px 0px 20px;">
        <input type="checkbox" name="自動マージ" id="checkbox_02" />
        <label style="font-size: 18px;" for="checkbox_02" name="自動マージ">自動マージ</label>
      </p>
      <p style="padding: 5px 0px 0px 35px; font-size: 16px;" onclick="fb_view_not_merge(xwrad_change_save, '※ マージしてないデータ: <br>')" title="表示する場合クリックして下さい。">
        <label style="font-weight: bold;">未マージ <i style="margin-left: 5px;" class="fa fa-eye" aria-hidden="true"></i></label>
        <br>
        <label class="add_count"></label>
        <br>
        <label class="update_count"></label>
        <br>
        <label class="delete_count"></label>
      </p>
      <p style="padding: 5px 0px 0px 35px; font-size: 16px;">
        <label style="font-weight: bold;">マージ済合計</label>
        <br>
        <label class="added_count"></label>
        <br>
        <label class="updated_count"></label>
        <br>
        <label class="deleted_count"></label>
      </p>
      <p style="padding: 25px 0px 0px 35px; font-size: 16px;">
        <button id="btn_all_return" onclick="fb_all_return()">全部 差戻</button>
      </p>
    </div>

    <div style="width: 80%; float:left;">
      <label style="font-weight: bold;">ログ</label><label class="merge_msg"></label><br>
      <div id="log_view" style="max-height:700px; min-height:300px; overflow-y:scroll; width: 98%; float: left; border-style: double;"></div>
    </div>
  </div>
  <div id="screen_input" style="margin: auto; text-align: center;font-size:24px;">
    <div id="screen_inputnumber" style="display:none;">
      <div style="border: 4px #ccc double;width:95%;height:66px;font-size:230%;padding: 0px;background-color:#000;color:#fff;  margin: auto;" id="view_number">
      </div>
      <div style="clear:both; height: 10px"></div>
      <div style="display:block;">
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="7">7</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="8">8</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="9">9</button>
        <!-- <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="-">-</button> -->
      </div>
      <div style="clear:both; height: 8px"></div>
      <div style="display:block;">
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="4">4</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="5">5</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="6">6</button>
        <!-- <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="+">+</button>-->
      </div>
      <div style="clear:both; height: 8px"></div>
      <div style="display:block;">
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="1">1</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="2">2</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="3">3</button>
        <!-- <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="X">X</button>-->
      </div>
      <div style="clear:both; height: 8px"></div>
      <div style="display:block;">
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="0">0</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="00">00</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value=".">.</button>
        <!-- <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="En">/</button>-->
      </div>
      <div style="clear:both; height: 8px"></div>
      <div style="display:block;">
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="クリア">クリア</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="←">←</button>
        <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="←←">←←</button>
        <!-- <button type="button" class="screen-number" onclick="fb_btn_number_click(this.value);" value="En">/</button>-->
      </div>
      <div style="clear:both;"></div>
    </div>
    <div id="screen_inputdatetime" style="display:none;">
      <span id="save_default_input" style="display:none;"></span>
      <div style="float:left;width:400px;margin-top:5%;">
        <div id="screen_input_year" style="font-size:250%;"></div>
        <div style="border: 1px #ccc solid;width:80%;height:30px;padding:3%;font-size:30px;vertical-align:middle;margin: 0 0 0 20px;" id="div_view_datatime">
          <span id="screen_input_mounth"></span>月
          <span id="screen_input_day"></span>日
          <span id="screen_input_houre"></span>時
          <span id="screen_input_minits"></span>分
        </div>
        <div style="margin:10%;">
          <button type="button" style="width:150px;" onclick="fb_datetime_clear();" value="クリア">クリア</button>
          <button type="button" style="width:150px;" onclick="fb_return_default();" value="現日時">初期日時</button>
        </div>
      </div>
      <div style="float:left;width:354px;">
        <div style="float:left; border: 4px #ccc double;width:94%;height:60px;font-size:200%;padding:2px;background-color:#000;color:#fff;margin-bottom: 15px;" id="view_date">
        </div>
        <div style="float:left;width:350px;">
          <div style="display:block;float:left;">
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="7">7</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="8">8</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="9">9</button>
            <button type="button" class="screen-datetime start_not_enabled" onclick="fb_set_mounth();">月</button>
          </div>
          <div style="clear:both; height:8px;"></div>
          <div style="display:block;float:left;">
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="4">4</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="5">5</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="6">6</button>
            <button type="button" class="screen-datetime start_not_enabled" onclick="fb_set_day();">日</button>
          </div>
          <div style="clear:both; height:8px;"></div>
          <div style="display:block;float:left;">
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="1">1</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="2">2</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="3">3</button>
            <button type="button" class="screen-datetime" onclick="fb_set_houre();">時</button>
          </div>
          <div style="clear:both; height:8px;"></div>
          <div style="display:block;float:left;">
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="0">0</button>
            <button type="button" class="screen-datetime" onclick="fb_btn_datetime_click(this.value);" value="C">C</button>
            <button type="button" class="screen-datetime start_not_enabled" onclick="fb_set_year(this.value);" value="年">年</button>
            <button type="button" class="screen-datetime" onclick="fb_set_minits();">分</button>
          </div>
          <div style="clear:both; height:8px;"></div>
        </div>
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
  <div id="dialog_box">
    <div id="input_data" style="width: 100%; float: left;">
      <table style="text-align: left;">
        <tr>
          <td style="width: 18%;">勤務日</td>
          <td>
            <span name="勤務日"><select name="勤務日" id="input_date" tabindex="0"></select></span>
          </td>
        </tr>
        <tr>
          <td>ロット管理ID</td>
          <td>
            <span name="ロット管理ID"><select name="ロット管理ID" id="input_lot"></select></span>
          </td>
        </tr>
        <tr>
          <td onclick="fb_clear(this);">打数</td>
          <td>
            <input type="number" name="打数" id="input_ntab" tabindex="1" />
            <div id="view_calculation" style="display:none;">
              <div id="corner_reference" style="margin-left: 3px;"></div>
              <div id="corner_calculation" style="margin-top: 2px;"></div>
            </div>
          </td>
        </tr>
        <tr>
          <td onclick="fb_clear(this);">取数</td>
          <td><input type="number" name="取数" id="input_piecis" tabindex="2" /></td>
        </tr>
        <tr>
          <td onclick="fb_clear(this);">サイクル</td>
          <td><input type="number" name="サイクル" id="input_cycle" tabindex="3" step="0.01" /></td>
        </tr>
        <tr>
          <td onclick="fb_clear(this);">開始日時</td>
          <td>
            <input type="time" name="開始日時" id="input_start_time" tabindex="4" />
          </td>
        </tr>
        <tr>
          <td onclick="fb_clear(this);">終了日時</td>
          <td>
            <input type="time" name="終了日時" id="input_stop_time" tabindex="5" />
            <select name="" id="input_stop_date" tabindex="6" style="margin-left: 2px; width: 24%;"></select>
          </td>
        </tr>
      </table>
      <center>
        <div style="width: 100%; float: left; margin: 20px;">
          <div style="width: 25%; float: left; margin-top: 10px;">
            <button style="min-width: 50%; height: 30px; font-size: 60%; margin: auto;" id="btn_return" onclick="fb_return(this)" tabindex="9">差戻</button>
          </div>
          <div style="width: 50%; float: left;">
            <button style="min-width: 50%;height: 50px;margin: auto;" id="btn_execution" onclick="fb_execution(this)" tabindex="7">登録</button>
          </div>
          <div style="width: 25%; float: left; margin-top: 10px;">
            <button style="min-width: 50%; height: 30px; font-size: 60%; margin: auto;" id="btn_delete" onclick="fb_del_skip(this)" tabindex="8">スキップ</button>
          </div>
        </div>
      </center>
      <span id="error_msg" style="color:red; display:none; font-size: 18px; margin-bottom: 10px"></span>
    </div>
    <div id="view_data" style="max-height:550px; overflow-y:scroll; width: 100%; float: left;"></div>
  </div>
</div>

</html>
