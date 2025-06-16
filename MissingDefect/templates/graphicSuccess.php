<?php
if (!$placeid) {

  slot('h1', '<h1> 稼働状態配置図 | Nalux');
  print '
    <div id="bot">
      <a class="blue" href="/MissingDefect/graphic?placeid=1000079">野洲工場</a>
      <a class="blue" href="/MissingDefect/graphic?placeid=1000073">山崎工場</a>
      <a class="blue" href="/MissingDefect/graphic?placeid=1000125">NPG</a>
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
use_javascript("MissingDefect/canvasjs.min.js");
use_javascript("MissingDefect/chart.min.js");
use_javascript("MissingDefect/chart380.min.js");
$btn = '<div style="float:left; margin-top:0.5px;" >';
$btn .= ' <input class="selgroups" type="text" name="start_sel" id="start_sel" style="float:left; margin-left:5px;" placeholder="開始" list="ymList" autocomplete="off" value="'.date("Y-m", strtotime("-12 months")).'">
          <label  style="float:left; " for="end_sel">~</label>
          <input class="selgroups" type="text" name="end_sel" id="end_sel" style="float:left;" placeholder="終了" list="ymList" autocomplete="off" value="'.date("Y-m").'">
          <datalist id="ymList">';
foreach ($list_date as $item) {
  $btn .= "<option value='" . $item['date'] . "'>\n";
}
$btn .= ' </datalist>
          <button type="button" onclick="view_chart();">検索</button>
        </div>';
$btn .= '<div style="float:right; margin-top:0.5px;">
          <button type="button" class="fb_model btn_checked" >不具合書Aの不良内訳</button>
          <button type="button" class="fb_model">損失・保留金額内訳</button>
          <button type="button" onclick="window.close();return false;" >閉じる</button>
          <label id="conf_msg"></label>
        </div>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>

<html>

<head>
  <style type="text/css">
    body,label {font-size: 16px;}
    .ui-button-text {font-size: 16px;  margin: 0.1px;  padding: 2px 4px 2px 4px !important;}
    div.bigclass {float: left;  padding: 5px;  width: 100%;  border-radius: 5px;  margin-bottom: 5px;}
    div.smallclass {float: left;  padding: 0px 3px 0px 3px;}
    div.radio {padding: 0px 20px 0px 20px;  border-radius: 5px;  background: lightyellow;  border: 1px dotted;}
    label.radio {font-size: 80%;}
    #conf_msg {float: right;  color: #fff;  margin: 0 20px;}
    #input_grid {margin: 0;  color: #000;}
    #grid {margin: 0;  color: #000;}
    .handsontable {font-size: 90%;}
    .handsontable .currentRow {color: #FFF;  background-color: #000;}
    .handsontable .currentCol {color: #FFF;  background-color: #000;}
    .handsontable .currenteader {background-color: #000;  color: #fff;}
    .handsontable .htDimmed {color: #000;  background-color: #ebf4f4;}
    .htCore tbody tr:nth-child(even) td {background-color: lightyellow;}
    .in-wrapper .htCore tbody tr:nth-child(odd) td {background-color: white;}
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
    .wd-10 {width: 10%;}
    .wd-8 {width: 8%;}
    .wd-5 {width: 5%;}
    td.top {vertical-align: top;}
    td.middle {vertical-align: middle;}
    td.bottom {vertical-align: bottom;}
    .btn_checked{color: orange;}
    #loading {width: 100%;  height: 100%;  z-index: 9999;  position: fixed;  top: 0;  left: 0; background-color: #ccc;  filter: alpha(opacity=85);  -moz-opacity: 0.85;  -khtml-opacity: 0.85;  opacity: 0.85;  background-image: url(/images/loading-1.gif);  background-position: center center;  background-repeat: no-repeat;  background-attachment: fixed;}
    #loading img {max-width: 100%;  height: auto;}
    .ui-button,.ui-button-text .ui-button {font-size: 12px !important;}
    .bc_nc {background-color: #000;}
    .bc_ch {background-color: #eee;}
    .hc_left {text-align: left;}
    .hc_right {text-align: right;}
    .hc_center {text-align: center;}
    .fl_left {float: left;}
    .fl_right {float: right;}
    .menu { font-weight: bold; text-align: center; width: 20px;  height: 20px;border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px;}
    .col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12 {float: left;  padding: 0px;}
    div.graphname {visibility: visible;  width: 100%;  text-align: center;  padding: 5px;  top: 0px;  left: 0px;  font-weight: bold;  background-color: transparent; position: absolute;}
    div.chartview .toolbar {visibility: hidden;  width: 270px;  padding: 5px;  top: 0px;  right: 0px;  background-color: transparent; position: absolute;}
    div.chartview:hover .toolbar {visibility: visible;}
    #msgbox {position: absolute;}
    #msgbox.form-validation-field-1formError:active {display: none;}
    input.form-control {height: 30px;}
    .bgc-1 {background-color: #6699CC;}
    .bgc-2 {background-color: #339933;}
    .bgc-3 {background-color: rgb(156 156 156);}
    .bgc-4 {background-color: rgb(205 201 165);}
    .bgc-5 {background-color: rgb(205 211 165);}
    .bgc-6 {background-color: rgb(205 201 115);}
  </style>
  <script type="text/javascript">
    var placeid = '<?php echo $placeid; ?>';
    var placename = '<?php echo $placename; ?>';
    // var placelist = <?php echo htmlspecialchars_decode($placelist); ?>;
    // var fh_palce = get_placelist(placelist, '完成');
    // var wip_palce = get_placelist(placelist, '仕掛');
    // var vd_palce = get_placelist(placelist, '蒸着');
    // var oh_palce = get_placelist(placelist, '保留');
    var obj_data = [];
    $(document).ready(function() {
      load_data();
      $("button").button();
      $(".btn_checked").attr('disabled','disabled');
      $("#alert").dialog({
        autoOpen: false,
        //width: '600px',
        modal: true,
        position: ["centetr"],
        buttons: [{
          text: "閉じる",
          click: function() {
            $(this).dialog("close");
          }
        }]
      });
      $('input[readonly]').css('background-color', 'Silver');
      $('.fb_model').click(function(){
        $('.fb_model').removeClass('btn_checked');
        $('.fb_model').removeAttr('disabled');
        $(this).addClass('btn_checked');
        $(this).attr('disabled','disabled');
        if ($('#defect_breakdown').css('display') == 'none') {
          $('#defect_breakdown').css('display','block')
          $('#loss_holding').css('display', 'none');
        }else{
          $('#defect_breakdown').css('display', 'none');
          $('#loss_holding').css('display', 'block');
        }
      });
    });

    // function get_placelist(dataAry, place) {
    //   var result = '';
    //   dataAry.forEach((value, key) => {
    //     if(value.ad_org_id == placeid){
    //       if (value.value.indexOf(place) > -1) {
    //         result = value.m_locator_id + "," + value.value + "," + value.x;
    //       }
    //     }
    //   });
    //   return result;
    // }
    function get_label_chart(value){
      return value.length > 7 ?`${value.substring(0, 4)}...` :value
    }
  </script>
</head>

<body>
  <div id="defect_breakdown" class="col-md-12">
    <div class="col-md-12">
      <div class="chartview col-md-6">
        <canvas id="bug_chart"></canvas>
        <p style="text-align: center;">不具合書Aの不良内訳</p>
        <!-- <div class="toolbar">
          <div style="border-radius: 5px;background: #ECECEC; float:left">
            <input type="radio" id="good" name="type_item" value="良品数">
            <label class="radio" for="good">良品数</label>
            <input type="radio" id="bad" name="type_item" value="不良数" checked>
            <label class="radio" for="bad">不良数</label>
          </div>
          <div style="border-radius: 5px;background: #ECECEC; float:left; margin-left:5px;">
            <input type="radio" id="line" name="type_chart" value="line">
            <label class="radio" for="line">Line</label>
            <input type="radio" id="bar" name="type_chart" value="bar" checked>
            <label class="radio" for="bar">Bar</label>
          </div>
        </div> -->
      </div>
    </div>
    <div id="smallchart" class="col-md-12"></div>
  </div>
  <div id="loss_holding" class="col-md-12" style="display: none;">
    <table class="wd-100">
      <tr>
        <td class="wd-40">
          <canvas id="loss_chart"></canvas>
          <p style="text-align: center;" class="loss_chart">損失金額</p>
        </td>
        <td class="bottom wd-30">
          <canvas id="loss_chart_1year"></canvas>
          <p style="text-align: center;" class="loss_chart_1year">年度件数</p>
        </td>
        <td class="top wd-30">
          <div id="loss_holding_table" style="margin-top: 20px;"></div>
        </td>
      </tr>
      <tr>
        <td class="wd-40">
          <canvas id="holding_chart"></canvas>
          <p style="text-align: center;" class="holding_chart">保留金額</p>
        </td>
        <td class="bottom wd-30">
          <canvas id="holding_chart_1year"></canvas>
          <p style="text-align: center;" class="holding_chart_1year">年度金額</p>
        </td>
        <td class="top wd-30">
            <div id="loss_table"></div>
        </td>
      </tr>
    </table>
  </div>
  <div id="alert">
    <div id="message" style="text-align: center;"></div>
  </div>
  <script>
    var itemcord = '';
    const new_year = new Date();
    var now_month = new_year.getMonth() + 1;
    var gr_data;
    var item_no = 2;
    var loss_holding_table = undefined;
    var loss_table = undefined;
    var holding_table = undefined;
    var screen_height = screen.height - screen.height * 0.17;
    var screen_width = screen.width - 100;

    function set_loss_holding_table(data) {
      h_num = 32;
      var columnsdata = [
        {'title': '年月', 'readOnly': true, renderer: "text", className: 'htRight', width: 120},
        {'title': '損失件数', 'readOnly': true, type: 'numeric', className: 'htRight', width: 100, numericFormat: {pattern: '0,0'}},
        {'title': '損失金額', 'readOnly': true, type: 'numeric', className: 'htRight', width: 100, numericFormat: {pattern: "0,0.00", culture: 'ja-JP',}},
        {'title': '保留件数', 'readOnly': true, type: "numeric", className: 'htRight', width: 100, numericFormat: {pattern: '0,0'}},
        {'title': '保留金額', 'readOnly': true, type: "numeric", className: 'htRight', width: 100, numericFormat: {pattern: "0,0.00", culture: 'ja-JP',}}
      ];
      var afterselect;
      var olval;
      if (loss_holding_table != undefined) {
        loss_holding_table.destroy();
      }
      loss_holding_table = new Handsontable(document.getElementById('loss_holding_table'), {
        data: data,
        startRows: 5,
        startCols: 5,
        height: screen_height * 1 / 2,
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
        hiddenColumns: false,
        language: 'ja-jp',
        exportFile: true,
        fixedColumnsLeft: 6,
        dropdownMenu: ['make_read_only', 'alignment', 'filter_by_condition', 'filter_action_bar', 'filter_by_value'],
        afterDeselect: function() {
          var cols = loss_holding_table.countCols();
          var rows = loss_holding_table.countRows();
          $("#conf_msg").text("行数: " + rows + "件");
        },
        afterSelection: function(r, c, r2, c2, p, s) {
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
    }

    function set_loss_table(data) {
      h_num = 32;
      var columnsdata = [
        {'title': '品名', 'readOnly': true, renderer: "text", className: 'htNoWrap', width: 120},
        {'title': '損失件数', 'readOnly': true, type: 'numeric', className: 'htRight', width: 100, numericFormat: {pattern: '0,0'}},
        {'title': '損失金額', 'readOnly': true, type: 'numeric', className: 'htRight', width: 100, numericFormat: {pattern: '0,0.00', culture: 'ja-JP'}},
        {'title': '保留件数', 'readOnly': true, type: 'numeric', className: 'htRight', width: 100, numericFormat: {pattern: '0,0'}},
        {'title': '保留金額', 'readOnly': true, type: 'numeric', className: 'htRight', width: 100, numericFormat: {pattern: '0,0.00', culture: 'ja-JP'}}];
        
      var afterselect;
      var olval;
      if (loss_table != undefined) {
        loss_table.destroy();
      }
      loss_table = new Handsontable(document.getElementById('loss_table'), {
        data: data,
        startRows: 5,
        startCols: 5,
        height: screen_height * 1 / 2,
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
        fixedColumnsLeft: 6,
        dropdownMenu: ['make_read_only', 'alignment', 'filter_by_condition', 'filter_action_bar', 'filter_by_value'],
        afterDeselect: function() {
          var cols = loss_table.countCols();
          var rows = loss_table.countRows();
          $("#conf_msg").text("行数: " + rows + "件");
        },
        afterSelection: function(r, c, r2, c2, p, s) {
          if ($('#担当者').html() == '') {
            UserDialog();
            return;
          }
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
    }

    function set_holding_table(data) {
      h_num = 32;
      var columnsdata = [
        {'title': '品名', 'readOnly': true, renderer: "text", className: 'htNoWrap', width: 120},
        {'title': '保留金額', 'readOnly': true, type: 'numeric', className: 'htRight', width: 88, numericFormat: {pattern: '0,0.00', culture: 'ja-JP',}},
      ];
      var afterselect;
      var olval;
      if (holding_table != undefined) {
        holding_table.destroy();
      }
      holding_table = new Handsontable(document.getElementById('holding_table'), {
        data: data,
        startRows: 5,
        startCols: 5,
        height: screen_height * 1 / 2,
        width: screen_width * 15 / 100,
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
        fixedColumnsLeft: 6,
        dropdownMenu: ['make_read_only', 'alignment', 'filter_by_condition', 'filter_action_bar', 'filter_by_value'],
        afterDeselect: function() {
          var cols = holding_table.countCols();
          var rows = holding_table.countRows();
          $("#conf_msg").text("行数: " + rows + "件");
        },
        afterSelection: function(r, c, r2, c2, p, s) {
          if ($('#担当者').html() == '') {
            UserDialog();
            return;
          }
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
    }



    function formatDate(date) {
      month = '' + (date.getMonth() + 1);
      day = '' + date.getDate();
      year = date.getFullYear();

      if (month.length < 2)
        month = '0' + month;

      return [year, month].join('-');
    }

    function load_data() {
      $('#defect_breakdown').css('display', 'block');
      $('#loss_holding').css('display', 'none');
      var datas = {
        ac: "グラフ表示",
        itemcord: itemcord,
        placeid: placeid,
        placename: placename,
      }
      fb_loading(true);
      $.ajax({
        type: 'POST',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          if (d != '[]') {
            obj_data = d;
          } else {
            obj_data = [];
          }
          view_chart();
          fb_loading(false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          fb_loading(false);
          return;
        }
      });
    }
    $('input[name=list_defect_year]').click(function() {
      if ($(this).is(':checked')) {
        year_array.push(parseInt($(this).val()));
        $('input[name=list_loss_year][value=' + $(this).val() + ']').prop('checked', true);
      } else {
        $('input[name=list_loss_year][value=' + $(this).val() + ']').prop('checked', false);
        for (var i = 0; i < year_array.length; i++) {

          if (year_array[i] == $(this).val()) {

            year_array.splice(i, 1);
          }

        }
      }
      if (year_array.length == 0) {
        alert('年度を必ず選択してください。')
        return;
      }
      load_data();
    });
    $('input[name=type_chart]').click(function() {
      var $name = $('input[name=type_chart]:checked').val();
      bug_chart.data.datasets[1].type = $name;
      bug_chart.data.datasets[2].type = $name;
      bug_chart2.data.datasets[1].type = $name;
      bug_chart2.data.datasets[2].type = $name;
      bug_chart3.data.datasets[1].type = $name;
      bug_chart3.data.datasets[2].type = $name;
      bug_chart4.data.datasets[1].type = $name;
      bug_chart4.data.datasets[2].type = $name;

      if ($name == 'line') {
        bug_chart.data.datasets[0].borderDash = [5, 5];
        bug_chart2.data.datasets[0].borderDash = [5, 5];
        bug_chart3.data.datasets[0].borderDash = [5, 5];
        bug_chart4.data.datasets[0].borderDash = [5, 5];
      } else {
        bug_chart.data.datasets[0].borderDash = [];
        bug_chart2.data.datasets[0].borderDash = [];
        bug_chart3.data.datasets[0].borderDash = [];
        bug_chart4.data.datasets[0].borderDash = [];
      }
      bug_chart.update();
      bug_chart2.update();
      bug_chart3.update();
      bug_chart4.update();
    });

    $('input[name=type_item]').click(function() {
      var $name = $('input[name=type_item]:checked').val();
      bug_chart.data.datasets[2].label = $name;
      bug_chart2.data.datasets[2].label = $name;
      bug_chart3.data.datasets[2].label = $name;
      bug_chart4.data.datasets[2].label = $name;
      if ($name == '良品数') {
        item_no = 3;
      } else {
        item_no = 2;
      }
      bug_chart.data.datasets[2].data = gr_data[item_no];
      bug_chart.update();
      bug_chart2.data.datasets[2].data = gr_data[item_no + 5];
      bug_chart2.update();
      bug_chart3.data.datasets[2].data = gr_data[item_no];
      bug_chart3.update();
      bug_chart4.data.datasets[2].data = gr_data[item_no];
      bug_chart4.update();
    });
    var ctx = document.getElementById('bug_chart');
    var bug_chart = new Chart(ctx, {
      data: {
        labels: [],
        datasets: [{
          label: '項目数',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'Teal',
          backgroundColor: 'Teal',
        }],
      },
      options: {
        responsive: true,
        scales: {
          x: {
              ticks: {
                callback: function(value, index, ticks) {
                  return get_label_chart(this.getLabelForValue(value));
                }
              }
            },
          y: {
            type: 'linear',
            position: 'left',
            // ticks: {
            //   beginAtZero: true,
            //   color: 'blue',
            // },
            // min: 0,
            // max: 70, // Hide grid lines, otherwise you have separate grid lines for the 2 y axes grid: { display: false }
          },
          // B: {
          //   type: 'linear',
          //   position: 'right',
          //   ticks: {
          //     beginAtZero: true,
          //     color: 'green',
          //     callback: function(value) {
          //       return value + '%';
          //     }
          //   },
          //   // min: 0,
          //   // max: 50,
          //   grid: {
          //     display: false
          //   }
          // },
          // x: {
          //   ticks: {
          //     beginAtZero: true
          //   }
          // }
        }
      }
    });
    var loss = document.getElementById('loss_chart');
    var loss_chart = new Chart(loss, {
      data: {
        labels: [],
        datasets: [{
          label: '損失件数',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'Teal',
          backgroundColor: 'Teal',
        },{
          label: '保留件数',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'orange',
          backgroundColor: 'orange',
        }],
      },
      options: {
        scales: {
          x: {
            ticks: {
              callback: function(value, index, ticks) {
                return get_label_chart(this.getLabelForValue(value));
              }
            }
          },
        },
        plugins: {
          title: {
            display: true,
            text: '件',
            align: 'end',
          }
        }
      }
    });
    var loss_1year = document.getElementById('loss_chart_1year');
    var loss_chart_1year = new Chart(loss_1year, {
      data: {
        labels: [],
        datasets: [{
          label: '損失件数',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'Teal',
          backgroundColor: 'Teal',
        },{
          label: '保留件数',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'orange',
          backgroundColor: 'orange',
        }],
      },
      options: {
        scales: {
          x: {
            ticks: {
              callback: function(value, index, ticks) {
                return get_label_chart(this.getLabelForValue(value));
              }
            }
          },
        },
        plugins: {
          title: {
            display: true,
            text: '件',
            align: 'end',
          }
        }
      }
    });
    var holding = document.getElementById('holding_chart');
    var holding_chart = new Chart(holding, {
      data: {
        labels: [],
        datasets: [{
          label: '損失金額',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'Teal',
          backgroundColor: 'Teal',
        },{
          label: '保留金額',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'orange',
          backgroundColor: 'orange',
        }],
      },
      options: {
        scales: {
          x: {
            ticks: {
              callback: function(value, index, ticks) {
                return get_label_chart(this.getLabelForValue(value));
              }
            }
          },
        },
        plugins: {
          title: {
            display: true,
            text: '千円',
            align: 'end',
          }
        }
      }
    });
    var holding_1year = document.getElementById('holding_chart_1year');
    var holding_chart_1year = new Chart(holding_1year, {
      data: {
        labels: [],
        datasets: [{
          label: '損失金額',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'Teal',
          backgroundColor: 'Teal',
        },{
          label: '保留金額',
          type: 'bar',
          yAxisID: 'y',
          data: [],
          borderColor: 'orange',
          backgroundColor: 'orange',
        }],
      },
      options: {
        scales: {
          x: {
            ticks: {
              callback: function(value, index, ticks) {
                return get_label_chart(this.getLabelForValue(value));
              }
            }
          },
        },
        plugins: {
          title: {
            display: true,
            text: '千円',
            align: 'end',
          }
        }
      }
    });

		function fb_parseFloat(item) {
			if (item === undefined || item === null){
        return 0;
      }else if (item.length === 0 ) {
				return 0;
			} else {
				return parseFloat(item);
			}
		}
    function fb_parseInt(item) {
			if (item === undefined || item === null){
        return 0;
      }else if (item.length === 0 ) {
				return 0;
			} else {
				return parseInt(item);
			}
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

    function view_chart() {
      const var_start_sel = $("#start_sel").val();
      const var_end_sel = $("#end_sel").val();
      var dt = new Date(var_end_sel);
      dt.setMonth(dt.getMonth() - 12);
      var obj = [];
      obj_data.wbn.forEach(function(value, key){
        let obj_key = value.wbn_item_code + value.wbn_form_no + value.YM;
        obj[obj_key] ??= [];
        obj[obj_key].HM = value.wbn_product_name + '-' + value.wbn_form_no;
        obj[obj_key].YM = value.YM;
        obj[obj_key].horyu ??= 0;
        obj[obj_key].horyu += (fb_parseFloat(value["01"]) - fb_parseFloat(value["02"]) - fb_parseFloat(value["03"]));
        obj[obj_key].horyu += (fb_parseFloat(value["M1"]) - fb_parseFloat(value["M2"]) - fb_parseFloat(value["M3"]));
        obj[obj_key].horyu += (fb_parseFloat(value["J1"]) - fb_parseFloat(value["J2"]) - fb_parseFloat(value["J3"]));
        obj[obj_key].horyu += (fb_parseFloat(value["P1"]) - fb_parseFloat(value["P2"]) - fb_parseFloat(value["P3"]));
        obj[obj_key].sonshitsu ??= 0;
        obj[obj_key].sonshitsu += fb_parseFloat(value["03"]);
        obj[obj_key].sonshitsu += fb_parseFloat(value["M3"]);
        obj[obj_key].sonshitsu += fb_parseFloat(value["J3"]);
        obj[obj_key].sonshitsu += fb_parseFloat(value["P3"]);
      });
      obj_data.hr.forEach(function(value, key){
        if(value.wicbn_id) {
          let obj_key = value.wbn_item_code + value.wbn_form_no + value.YM;
          if(value.hgpd_process == "保留処理"){
            obj[obj_key].horyu -= fb_parseFloat(value.hgpd_qtycomplete);
            obj[obj_key].horyu -= fb_parseFloat(value.hgpd_difactive);
            obj[obj_key].sonshitsu += fb_parseFloat(value.hgpd_difactive);
          }else{
            obj[obj_key].horyu += fb_parseFloat(value.hgpd_qtycomplete);
          }
        }
      });
      obj_data.vas.forEach(function(value, key){
        let obj_key = value.品目コード + value.型番 + value.集計タイプ ;
        if(obj_key in obj){
          if (value.単価 > 0){
            obj[obj_key].horyu_money = obj[obj_key].horyu * value.単価/1000 ;
            //obj[obj_key].horyu_money = obj[obj_key].horyu_money / 100;
            obj[obj_key].sonshitsu += fb_parseFloat(value.後不数) ;
            obj[obj_key].sonshitsu_money = obj[obj_key].sonshitsu * value.単価/1000 ;
            //obj[obj_key].sonshitsu_money = obj[obj_key].sonshitsu_money / 100;
          }else{
            obj[obj_key].horyu_money = 0 ;
            obj[obj_key].sonshitsu_money = 0 ;
          }
        }
      });
      var exp_obj = [];
      exp_obj.YM = [];
      exp_obj.HM = [];
      exp_obj.BUG = [];
      obj_data.bug.forEach(function(value, key){
        if (var_start_sel <=value.YM && value.YM <=var_end_sel){
          exp_obj.BUG[value.wbn_classification]??= [] ;
          exp_obj.BUG[value.wbn_classification].count??= 0;
          exp_obj.BUG[value.wbn_classification].count++;
          exp_obj.BUG[value.wbn_classification].data??= [];
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project]??= [] ;
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].count??= 0;
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].count++;
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].bug??=[];
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].bug[value.wbn_defect_item]??= 0;
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].bug[value.wbn_defect_item]++;
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].name??=[];
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].name[value.wbn_product_name]??= 0;
          exp_obj.BUG[value.wbn_classification].data[value.wbn_project].name[value.wbn_product_name]++;
        }
      });
      const sortedObject_bug = Object.fromEntries(Object.entries(exp_obj.BUG).sort(([, a], [, b]) => b.count - a.count));
      exp_obj.BUG = sortedObject_bug;
      Object.keys(obj).forEach(function(obj_key){
        if (var_start_sel <= obj[obj_key].YM && obj[obj_key].YM <=var_end_sel){
          exp_obj.YM[obj[obj_key].YM] ??= [];
          exp_obj.YM[obj[obj_key].YM].horyu_count ??= 0;
          exp_obj.YM[obj[obj_key].YM].horyu_count += fb_parseFloat(obj[obj_key].horyu);
          exp_obj.YM[obj[obj_key].YM].horyu_money ??= 0;
          exp_obj.YM[obj[obj_key].YM].horyu_money += fb_parseFloat(obj[obj_key].horyu_money);
          exp_obj.YM[obj[obj_key].YM].sonshitsu_count ??=0;
          exp_obj.YM[obj[obj_key].YM].sonshitsu_count += fb_parseFloat(obj[obj_key].sonshitsu);
          exp_obj.YM[obj[obj_key].YM].sonshitsu_money ??=0;
          exp_obj.YM[obj[obj_key].YM].sonshitsu_money += fb_parseFloat(obj[obj_key].sonshitsu_money);
        }
        if (var_start_sel <= obj[obj_key].YM && obj[obj_key].YM <= var_end_sel){
          exp_obj.HM[obj[obj_key].HM] ??= [];
          exp_obj.HM[obj[obj_key].HM].horyu_count ??=0;
          exp_obj.HM[obj[obj_key].HM].horyu_count += fb_parseFloat(obj[obj_key].horyu);
          exp_obj.HM[obj[obj_key].HM].horyu ??=0;
          exp_obj.HM[obj[obj_key].HM].horyu += fb_parseFloat(obj[obj_key].horyu_money);
          exp_obj.HM[obj[obj_key].HM].sonshitsu_count ??=0;
          exp_obj.HM[obj[obj_key].HM].sonshitsu_count += fb_parseFloat(obj[obj_key].sonshitsu);
          exp_obj.HM[obj[obj_key].HM].sonshitsu ??=0;
          exp_obj.HM[obj[obj_key].HM].sonshitsu += fb_parseFloat(obj[obj_key].sonshitsu_money);
        }
      });
      const sortedObject_HM = Object.fromEntries(Object.entries(exp_obj.HM).sort(([, a], [, b]) => b.sonshitsu_count - a.sonshitsu_count));
      exp_obj.HM = sortedObject_HM;
      // const sortedObject_sonshitsu = Object.fromEntries(Object.entries(exp_obj.HM.sonshitsu).sort(([, a], [, b]) => b - a));
      // exp_obj.HM.sonshitsu = sortedObject_sonshitsu;
      Object.keys(exp_obj.YM).forEach(function(obj_key){
        exp_obj.YM[obj_key].horyu_money = exp_obj.YM[obj_key].horyu_money;
        exp_obj.YM[obj_key].sonshitsu_money = exp_obj.YM[obj_key].sonshitsu_money;
      });
      Object.keys(exp_obj.HM).forEach(function(obj_key){
        exp_obj.HM[obj_key].horyu_count = exp_obj.HM[obj_key].horyu_count;
        exp_obj.HM[obj_key].sonshitsu_count = exp_obj.HM[obj_key].sonshitsu_count;
        exp_obj.HM[obj_key].horyu = exp_obj.HM[obj_key].horyu;
        exp_obj.HM[obj_key].sonshitsu = exp_obj.HM[obj_key].sonshitsu;
      });
      $('#smallchart').html('');
      set_chart(bug_chart, exp_obj.BUG, 'count');
      set_chart(loss_chart, exp_obj.HM, 'sonshitsu_count','horyu_count');
      $('.loss_chart').html("件数 品名ラベル("+ $("#start_sel").val() + " ~ "+ $("#end_sel").val()+")");
      set_chart(holding_chart, exp_obj.HM, 'sonshitsu', 'horyu');
      $('.holding_chart').html("金額 品名ラベル("+ $("#start_sel").val() + " ~ "+ $("#end_sel").val()+")");
      set_chart(loss_chart_1year, exp_obj.YM, 'sonshitsu_count', 'horyu_count');
      $('.loss_chart_1year').html("件数合計("+ $("#start_sel").val() + " ~ "+ $("#end_sel").val()+")");
      set_chart(holding_chart_1year, exp_obj.YM, 'sonshitsu_money', 'horyu_money');
      $('.holding_chart_1year').html("保留合計("+ $("#start_sel").val() + " ~ "+ $("#end_sel").val()+")");
      var data_table= [];
      Object.keys(exp_obj.HM).forEach(function(obj_key){
        data_table.push([obj_key, exp_obj.HM[obj_key].sonshitsu_count, exp_obj.HM[obj_key].sonshitsu, exp_obj.HM[obj_key].horyu_count, exp_obj.HM[obj_key].horyu]);
      });
      set_loss_table(data_table);
      data_table= [];
      Object.keys(exp_obj.YM).forEach(function(obj_key){
        data_table.push([obj_key, exp_obj.YM[obj_key].sonshitsu_count, exp_obj.YM[obj_key].sonshitsu_money, exp_obj.YM[obj_key].horyu_count, exp_obj.YM[obj_key].horyu_money]);
      });
      set_loss_holding_table(data_table);

      smallchart_html = ``;
      if ('BUG' in exp_obj) {
        Object.keys(exp_obj.BUG).forEach(function(name) {
          smallchart_html += `<div class="col-md-12" style="overflow-x:scroll; height: 310px;">
                              <table>
                                <tr>
                                  <td>
                                    <div class="chartview">
                                      <canvas style="height:250px; width:400px;" id="` + name + `"></canvas>
                                        <p style="text-align: center;">` + name + `</p>
                                    </div>
                                  </td>`;
          exp_obj.BUG[name].value = [];
          const sortedObject_project = Object.fromEntries(Object.entries(exp_obj.BUG[name].data).sort(([, a], [, b]) => b.count - a.count));
          exp_obj.BUG[name].data= sortedObject_project;
          Object.keys(exp_obj.BUG[name].data).forEach(function(key) {
            const sortedObject_project_bug = Object.fromEntries(Object.entries(exp_obj.BUG[name].data[key].bug).sort(([, a], [, b]) => b - a));
            exp_obj.BUG[name].data[key].bug= sortedObject_project_bug;
            const sortedObject_project_name = Object.fromEntries(Object.entries(exp_obj.BUG[name].data[key].name).sort(([, a], [, b]) => b - a));
            exp_obj.BUG[name].data[key].name= sortedObject_project_name;
            exp_obj.BUG[name].value.push(exp_obj.BUG[name].data[key].count);
            smallchart_html += ` <td>
                                <div class="chartview">
                                    <canvas style="height:250px; width:400px;" id="` + name + key + `name"></canvas>
                                      <p style="text-align: center;">` + key + `_層別_製品名</p>
                                  </div>
                                </td>`;
            smallchart_html += `  <td>
                                    <div class="chartview">
                                    <canvas style="height:250px; width:400px;" id="` + name + key + `defect"></canvas>
                                      <p style="text-align: center;">` + key + `_層別_項目名</p>
                                  </div>
                                </td>`;
            exp_obj.BUG[name].data[key].bug_value = [];
            Object.keys(exp_obj.BUG[name].data[key].bug).forEach(function(bug_key) {
              exp_obj.BUG[name].data[key].bug_value.push(exp_obj.BUG[name].data[key].bug[bug_key]);
            });
            exp_obj.BUG[name].data[key].name_value = [];
            Object.keys(exp_obj.BUG[name].data[key].name).forEach(function(name_key) {
              exp_obj.BUG[name].data[key].name_value.push(exp_obj.BUG[name].data[key].name[name_key]);
            });
          });
          smallchart_html += `
                                </tr>
                              </table>
                            </div>`;

        });

        $('#smallchart').html(smallchart_html);
        Object.keys(exp_obj.BUG).forEach(function(name) {
          var ctx = document.getElementById(name);
          var bug_chart2 = new Chart(ctx, {
            data: {
              labels: Object.keys(exp_obj.BUG[name].data),
              datasets: [{
                label: '項目数',
                type: 'bar',
                yAxisID: 'y',
                data: exp_obj.BUG[name].value,
                borderColor: 'blue',
                backgroundColor: 'blue',
              }],
            },
            options: {
              responsive: true,
              scales: {
                x: {
                  ticks: {
                    callback: function(value, index, ticks) {
                      return get_label_chart(this.getLabelForValue(value));
                    }
                  }
                },
                y: {
                  type: 'linear',
                  position: 'left',
                  ticks: {
                  },
                  // min: 0,
                  // max: 70, // Hide grid lines, otherwise you have separate grid lines for the 2 y axes grid: { display: false }
                },
              }
            }
          });
          if (true) {
            Object.keys(exp_obj.BUG[name].data).forEach(function(key) {
              var ctx = document.getElementById(name + key + "name");
              var bug_chart2 = new Chart(ctx, {
                data: {
                  labels: Object.keys(exp_obj.BUG[name].data[key].name),
                  datasets: [{
                    label: '項目数',
                    type: 'bar',
                    yAxisID: 'y',
                    data: exp_obj.BUG[name].data[key].name_value,
                    borderColor: '#EECFA1',
                    backgroundColor: '#EECFA1',
                  }],
                },
                options: {
                  responsive: true,
                  scales: {
                    x: {
                      // title: {
                      //   color: 'red',
                      //   display: true,
                      //   text: 'Month'
                      // },
                      ticks: {
                        callback: function(value, index, ticks) {
                          return get_label_chart(this.getLabelForValue(value));
                        }
                      }
                    },
                    y: {
                      type: 'linear',
                      position: 'left',
                      scaleLabel: {
                          display: false,
                          labelString: 12
                      },
                      ticks: {

                      },
                      // min: 0,
                      // max: 70, // Hide grid lines, otherwise you have separate grid lines for the 2 y axes grid: { display: false }
                    },
                  }
                }
              });

              var ctx = document.getElementById(name + key + "defect");
              var bug_chart2 = new Chart(ctx, {
                data: {
                  labels: Object.keys(exp_obj.BUG[name].data[key].bug),
                  datasets: [{
                    label: '項目数',
                    type: 'bar',
                    yAxisID: 'y',
                    data: exp_obj.BUG[name].data[key].bug_value,
                    borderColor: '#79CDCD',
                    backgroundColor: '#79CDCD',
                  }],
                },
                options: {
                  responsive: true,
                  scales: {
                    x: {
                      ticks: {
                        callback: function(value, index, ticks) {
                          return get_label_chart(this.getLabelForValue(value));
                        }
                      }
                    },
                    y: {
                      type: 'linear',
                      position: 'left',
                      ticks: {
                      },
                    },
                  }
                }
              });
            });
          }
        });
      }
    }

    function fb_loading(flag) {
      $('#loading').remove();
      if (!flag) return;
      $('<div id="loading" />').appendTo('body');
    }

    function set_chart(this_chart, this_data, this_key = null, this_key_1 = null) {
      this_chart.data.labels = new Array;
      this_chart.data.datasets[0].data = new Array;
      if(this_key_1 !== null){
        this_chart.data.datasets[1].data = new Array;
      }
      let i = 0;
      Object.keys(this_data).forEach(function(key){
        if (i < 24){
          this_chart.data.labels.push(key);
          if(this_key == null){
            this_chart.data.datasets[0].data.push(this_data[key]);
          }else{
            this_chart.data.datasets[0].data.push(this_data[key][this_key]);
          }
          if(this_key_1 !== null){
            this_chart.data.datasets[1].data.push(this_data[key][this_key_1]);
          }
        }
        i++;
      });
      this_chart.update();
    }

  </script>
</body>

</html>
