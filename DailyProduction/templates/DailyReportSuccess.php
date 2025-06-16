<?php
if (!$placeid) {

  slot('h1', '<h1> 稼働状態配置図 | Nalux');
  print '
                <div id="bot">
                <a class="blue" href="/DailyProduction/DailyReport?placeid=1000079">野洲工場</a>
                <a class="blue" href="/DailyProduction/DailyReport?placeid=1000073">山崎工場</a>
                <a class="blue" href="/DailyProduction/DailyReport?placeid=1000125">NPG</a>
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
use_javascript("hpro/handsontable.full.min.js");
use_javascript("hpro/languages/all.js");
use_stylesheet("hpro/handsontable.full.min.css");
use_javascript("jquery/jquery.ui.datepicker-ja.js");
use_javascript("jquery/jquery.ui.ympicker.js");

$btn = '
        <div id="h_sel" style="float:left;" >';
$btn .= '
        <label  style="float:left;margin-top:5px;" for="start_sel">【検査】</label>
        <input type="text" name="start_sel" id="start_sel" style="float:left;font-size:18px; height:24px; margin-top:1px; width:7vw;" value="" list="ymList" autocomplete="off">
        <label  style="float:left;margin-top:5px;" for="end_sel">~</label>
        <input type="text" name="end_sel" id="end_sel" style="float:left;font-size:18px;height:24px; margin-top:1px;margin-right:5px; width:7vw;" value="" list="ymList" autocomplete="off">
        <datalist id="ymList">';
foreach ($dlist as $item) {
  $btn .= "<option value='" . $item['xwrad_date'] . "'>\n";
}
$btn .= '</datalist>
        <button type="button" style="height:28px; margin-top:1px;" onclick="view_table();">　検索　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="fb_reload();">　更新　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="fb_download_CSV();">　ロード　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="fb_data_view();">　一覧　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="fb_user_dialog();">　担当者　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="if (confirm(\'転送しますか。\') == true) {fb_send_data()}">　転送　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="if (confirm(\'非表示しますか。\') == true) {fb_not_view_data()}">　非表示　</button>
        <label id="担当者lable"></label>
        <label id="担当者"></label>
        <input type="hidden" id="usercord"/>
        <input type="hidden" id="gp1"/>
        <input type="hidden" id="gp2"/>
        </div>


        <button style="float:right; height:28px; margin-top:1px;" type="button" onclick="window.close();return false;">閉じる</button>
        <label style="float:right; font-size: 0.8vw; margin-top:5px; margin-right:15px;" class="radio9" for="checkbox_101" name="入力判定">入力判定</label>
        <input style="float:right; margin-top:9px; margin-right:5px;" type="checkbox" id="checkbox_101" name="入力判定" value="品名"/>
        <div style="float:right; margin-top:5px;" id="conf_msg"></div>
        <div style="clear:both;"></div>';
slot('cd', $btn);
?>

<html>

<body>
  <style type="text/css">
    .ui-button-text {
      font-size: 16px;
      margin: 0.1px;
      padding: 2px 4px 2px 4px !important;
    }

    #conf_msg {
      float: right;
      color: #fff;
      font-size: 90%;
      margin: 0 20px;
    }

    #a_type {
      width: 60px;
      padding: 1px;
    }

    #q {
      width: 90px;
      padding: 0.2px;
    }

    #input_grid {
      margin: 0;
      color: #000;
    }

    #grid {
      margin: 0;
      color: #000;
    }

    .handsontable {
      font-size: 90%;
    }

    .handsontable .currentRow {
      color: #FFF;
      background-color: #000;
    }

    .handsontable .currentCol {
      color: #FFF;
      background-color: #000;
    }

    .handsontable .currenteader {
      background-color: #000;
      color: #fff;
    }

    .handsontable .htDimmed {
      color: #000;
      background-color: #ebf4f4;
    }

    .htCore tbody tr:nth-child(even) td {
      background-color: lightyellow;
    }

    .in-wrapper .htCore tbody tr:nth-child(odd) td {
      background-color: white;
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
      background-image: url(/images/loading-1.gif);
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    #loading img {
      max-width: 100%;
      height: auto;
    }

    .ui-button,
    .ui-button-text .ui-button {
      font-size: 12px !important;
    }

    .bc_nc {
      background-color: #000;
    }

    .bc_ch {
      background-color: #eee;
    }

    .hc_left {
      text-align: left;
    }

    .menu {
      /*font-size:80%;*/
      font-weight: bold;
      text-align: center;
      width: 20px;
      height: 20px;

      border-radius: 10px;
      /* CSS3草案 */
      -webkit-border-radius: 10px;
      /* Safari,Google Chrome用 */
      -moz-border-radius: 10px;
      /* Firefox用 */
    }

    button:hover,
    label:hover,
    input:hover {
      color: orange;
    }
  </style>
  <script type="text/javascript">
    var placeid = '<?php echo $placeid; ?>';
    var save_data;
    var table = undefined;
    var datanow;
    $(document).ready(function() {
      if (localStorage.getItem("担当者")) {
        tanto = JSON.parse(localStorage.getItem("担当者"));
        $("#担当者lable").html("転送担当者: ");
        $("#担当者").html(tanto['name']);
        $('#usercord').val(tanto['ppro']);
        $('#gp1').val(tanto['gp1']);
        $('#gp2').val(tanto['gp2']);
      }
      if (localStorage.getItem("bk_data")) {
        bk_data = JSON.parse(localStorage.getItem("bk_data"));
        save_data = JSON.parse(localStorage.getItem("bk_save_data"));
        table_set(bk_data);
      } else {
        view_table();
      }
    });

    function fb_reload() {
      $('#start_sel').val("");
      $('#end_sel').val("");
      var datas = {
        ac: "GetJson",
        placeid: placeid,
      }
      loadingView(true);
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          save_data = d;
          table_set(d);
          loadingView(false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          loadingView(false);
          return;
        }
      });
    }

    function view_table() {
      var start_sel = $('#start_sel').val();
      var end_sel = $('#end_sel').val();
      var datas = {
        ac: "GetJson",
        // a_type:$("#a_type").val(),
        // q:$("#q").val(),
        // sup:encodeURI($('#mPlant option:selected').val()),
        placeid: placeid,
        start_sel: start_sel,
        end_sel: end_sel
      }
      loadingView(true);
      $.ajax({
        type: 'GET',
        url: "",
        dataType: 'json',
        data: datas,
        success: function(d) {
          save_data = d;
          table_set(d);
          loadingView(false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("XMLHttpRequest : " + XMLHttpRequest.status + "\n" + "textStatus : " + textStatus + "\n" + "errorThrown : " + errorThrown.message);
          loadingView(false);
          return;
        }
      });
    }

    function table_set(data) {
      h_num = 32;
      var grid = document.getElementById('grid');
      var columnsdata = [{
          'title': '判定',
          'readOnly': true,
          renderer: "html",
          className: 'htCenter htMiddle',
          width: 40
        },
        {
          'title': 'ID',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 0.1
        },
        {
          'title': '編集',
          'readOnly': false,
          type: "checkbox",
          className: 'htCenter htMiddle',
          width: 40
        },
        {
          'title': '勤務日',
          'readOnly': true,
          renderer: "text",
          className: 'htRight',
          width: 80
        },
        {
          'title': '成形機',
          'readOnly': true,
          type: 'text',
          className: 'htCenter',
          width: 70
        },
        {
          'title': '品名コード',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 100
        },
        {
          'title': '品名',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': '社外品名',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 0.1
        },
        {
          'title': '型番',
          'readOnly': true,
          type: 'numeric',
          className: 'htCenter',
          width: 60
        },
        {
          'title': '打数',
          'readOnly': false,
          type: 'numeric',
          className: 'htRight',
          width: 60
        },
        {
          'title': 'サイクル',
          'readOnly': false,
          type: 'numeric',
          className: 'htRight',
          width: 60
        },
        {
          'title': '取数',
          'readOnly': true,
          type: 'numeric',
          className: 'htCenter',
          width: 40
        },
        {
          'title': '開始日時',
          'readOnly': false,
          type: 'text',
          className: 'htRight',
          width: 130
        },
        {
          'title': '終了日時',
          'readOnly': false,
          type: 'text',
          className: 'htRight',
          width: 130
        },
        {
          'title': '作業時間',
          'readOnly': true,
          type: 'numeric',
          className: 'htRight',
          width: 60
        },
        {
          'title': '生産数',
          'readOnly': true,
          type: 'numeric',
          className: 'htRight',
          width: 80
        },
        {
          'title': '良品数',
          'readOnly': false,
          type: 'numeric',
          className: 'htRight',
          width: 50
        },
        {
          'title': '生産時廃棄',
          'readOnly': true,
          type: 'numeric',
          className: 'htRight',
          width: 80
        },
        {
          'title': 'サンプル',
          'readOnly': false,
          type: 'numeric',
          className: 'htRight',
          width: 60
        },
        {
          'title': '欠損品',
          'readOnly': false,
          type: 'numeric',
          className: 'htRight',
          width: 60
        },
        {
          'title': '不良数',
          'readOnly': true,
          type: 'numeric',
          className: 'htRight',
          width: 80
        },
        {
          'title': '良品率',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 80
        },
        {
          'title': '不良率',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 80
        },
        {
          'title': '良品率(N)',
          'readOnly': true,
          type: 'numeric',
          className: 'htRight',
          width: 0.1
        },
        {
          'title': '不良率(N)',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 0.1
        },
        {
          'title': '不良品',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 300
        },
        {
          'title': '備考',
          'readOnly': false,
          type: 'text',
          className: 'htNoWrap',
          width: 200
        },
        {
          'title': '入力者',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '入力日時',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '作業コード',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 150
        },
        {
          'title': '作業区分',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '作業工程',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '転送者コード',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 150
        },
        {
          'title': '転送者',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '所属',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 100
        },
        {
          'title': '係セクション',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '工場コード',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 120
        },
        {
          'title': '工場名',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': 'ロット番号',
          'readOnly': true,
          type: 'text',
          className: 'htRight',
          width: 120
        },
        {
          'title': 'pending_num',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': 'missing_num',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': '過不足',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': '過不足メモ',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': 'defact_check',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 120
        },
        {
          'title': 'beforeprocess',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'afterprocess',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'cutmethod',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'vapordepositionlot',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'defect',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 100
        },
        {
          'title': 'pdate',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 100
        },
        {
          'title': 'dateuse',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 100
        },
        {
          'title': 'scheduled_number',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'measure',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 100
        },
        {
          'title': 'state',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 100
        },
        {
          'title': 'materialsname',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 250
        },
        {
          'title': 'materialslot',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'materialsused',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'lotstarttime',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'lotendtime',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'hour',
          'readOnly': true,
          type: 'numeric',
          className: 'htRight',
          width: 100
        },
        {
          'title': 'pproentry',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'workplankind',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'plankind',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'plantime',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'place',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 250
        },
        {
          'title': 'updating_person',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'vap_m_no',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'vap_mix',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'vap_befor_status',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'vap_ex_time',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'del_flg',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '作業段取り時間 ',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'データ確認フラグ ',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'CSV出力フラグ ',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '指図№',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': 'セット取数',
          'readOnly': true,
          type: 'numeric',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '更新日時',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '作成日時',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 150
        },
        {
          'title': '保存列目',
          'readOnly': true,
          type: 'text',
          className: 'htNoWrap',
          width: 0.1
        }
      ];
      var ww = $(window).width() - 5;
      var wh = $(window).height() - h_num;
      var afterselect;
      var olval;
      if (table != undefined) {
        table.destroy();
        table = undefined;
      }
      datanow = data;
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
        //hiddenColumns: {columns: [0]},
        language: 'ja-jp',
        exportFile: true,
        fixedColumnsLeft: 6,
        //fixedRowsBottom:1,
        //hiddenColumns: {columns: col_num},
        comments: true,
        autoRowSize: false,
        rowHeight: function(row) {
          return 50;
        },
        defaultRowHeight: 50,
        // enable the `Comments` plugin
        // and configure its settings
        afterDeselect: function() {
          var cols = table.countCols();
          var rows = table.countRows();
          $("#conf_msg").text("行数: " + rows + "件");
        },
        beforeChange: function(changes, source) {
          if (!changes) {
            return;
          }
          datanow = table.getData();
          // Manage comments programmatically:
          for (i = 0; i < changes.length; i++) {
            if (changes[i][2] !== changes[i][3]) {
              datanow[changes[i][0]][changes[i][1]] = changes[i][3];
              const ntab = datanow[changes[i][0]][getNumColHeader('打数')];
              const piecis = datanow[changes[i][0]][getNumColHeader('取数')];
              let goodnum = datanow[changes[i][0]][getNumColHeader('良品数')];
              let sample = datanow[changes[i][0]][getNumColHeader('サンプル')];
              let missing = datanow[changes[i][0]][getNumColHeader('欠損品')];

              const cycle = datanow[changes[i][0]][getNumColHeader('サイクル')];
              const starttime = datanow[changes[i][0]][getNumColHeader('開始日時')];
              const endtime = datanow[changes[i][0]][getNumColHeader('終了日時')];
              const totaltime = diff_time(starttime, endtime, "s");
              const min_cycle = cycle * 0.9;
              const max_cycle = cycle * 1.1;
              if (datanow[changes[i][0]][getNumColHeader('判定')] != '◎') {
                if (ntab && cycle && piecis && starttime && endtime) {
                  const cycle_now = Math.round((totaltime / ntab) * 100) / 100;
                  if ((min_cycle < cycle_now) && (cycle_now < max_cycle)) {
                    datanow[changes[i][0]][getNumColHeader('判定')] = '';
                  } else {
                    datanow[changes[i][0]][getNumColHeader('判定')] = 'NG';
                  }
                } else {
                  datanow[changes[i][0]][getNumColHeader('判定')] = 'NG';
                }
              }
              if ((ntab * piecis) > (goodnum + sample + missing)) {
                totalnum = ntab * piecis;
                during = totalnum - goodnum - sample - missing;
              } else {
                during = 0;
                totalnum = goodnum + sample + missing;
              }
              if (goodnum == '') {
                goodnum = 0;
                datanow[changes[i][0]][getNumColHeader('良品数')] = goodnum;
              }
              if (sample == '') {
                datanow[changes[i][0]][getNumColHeader('サンプル')] = 0;
              }
              if (missing == '') {
                missing = 0;
                datanow[changes[i][0]][getNumColHeader('欠損品')] = missing;
              }
              datanow[changes[i][0]][getNumColHeader('生産数')] = totalnum;
              datanow[changes[i][0]][getNumColHeader('生産時廃棄')] = during;
              datanow[changes[i][0]][getNumColHeader('不良数')] = totalnum - goodnum;
              if (totaltime > 0) {
                datanow[changes[i][0]][getNumColHeader('hour')] = Math.round(totalnum / totaltime * 3600 * 100) / 100;
              }
              datanow[changes[i][0]][getNumColHeader('作業コード')] = '1';
              datanow[changes[i][0]][getNumColHeader('作業区分')] = '直接作業';
              datanow[changes[i][0]][getNumColHeader('作業工程')] = '成形';
              datanow[changes[i][0]][getNumColHeader('転送者コード')] = $("#usercord").val();
              datanow[changes[i][0]][getNumColHeader('転送者')] = $("#担当者").html();
              datanow[changes[i][0]][getNumColHeader('所属')] = $("#gp1").val();
              datanow[changes[i][0]][getNumColHeader('係セクション')] = $("#gp2").val();
              var defectivesitem = '';
              if (during > 0) {
                defectivesitem += '生産時廃棄=>' + during;
              }
              if ((during > 0) && ((sample > 0) || (missing > 0))) {
                defectivesitem += ','
              }
              if (sample > 0) {
                defectivesitem += 'サンプル=>' + sample;
              }
              if ((sample > 0) && (missing > 0)) {
                defectivesitem += ','
              }
              if (missing > 0) {
                defectivesitem += '欠損品=>' + missing
              }
              datanow[changes[i][0]][getNumColHeader('不良品')] = defectivesitem;
              if (totalnum > 0) {
                const goodrate = Math.round((goodnum / totalnum) * 10000) / 100;
                const badrate = 100 - goodrate;
                datanow[changes[i][0]][getNumColHeader('良品率')] = goodrate + '%';
                datanow[changes[i][0]][getNumColHeader('不良率')] = badrate + '%';
                datanow[changes[i][0]][getNumColHeader('良品率(N)')] = goodrate / 100;
                datanow[changes[i][0]][getNumColHeader('不良率(N)')] = badrate / 100;
              } else {
                datanow[changes[i][0]][getNumColHeader('良品率')] = '';
                datanow[changes[i][0]][getNumColHeader('不良率')] = '';
                datanow[changes[i][0]][getNumColHeader('良品率(N)')] = '';
                datanow[changes[i][0]][getNumColHeader('不良率(N)')] = '';
              }
              if (datanow[changes[i][0]][getNumColHeader('判定')] != '◎') {
                datanow[changes[i][0]][getNumColHeader('編集')] = true;
              } else {
                datanow[changes[i][0]][getNumColHeader('編集')] = false;
              }
            }
          }
          table.loadData(datanow);
          localStorage.setItem("bk_data", JSON.stringify(datanow));
          localStorage.setItem("bk_save_data", JSON.stringify(save_data));
        },

        afterSelection: function(r, c, r2, c2, p, s) {
          if ($('#担当者').html() == '') {
            fb_user_dialog();
            return;
          }
          if (c == [getNumColHeader('開始日時')] || c == [getNumColHeader('終了日時')]) {
            datetime(r, c);
            return;
          }
          const ntab = datanow[r][getNumColHeader('打数')];
          const cycle = datanow[r][getNumColHeader('サイクル')];
          const piecis = datanow[r][getNumColHeader('取数')];
          const starttime = datanow[r][getNumColHeader('開始日時')];
          const endtime = datanow[r][getNumColHeader('終了日時')];
          const totaltime = diff_time(starttime, endtime, "s");
          const min_cycle = cycle - cycle * 0.1;
          const max_cycle = cycle + cycle * 0.1;
          if (ntab && cycle && starttime && endtime) {
            const cycle_now = Math.round((totaltime / ntab) * 100) / 100;
            const cycle_rate = Math.round((cycle_now / cycle) * 10000) / 100;
            commentsPlugin.removeCommentAtCell(r, getNumColHeader('サイクル'));
            commentsPlugin.setCommentAtCell(r, getNumColHeader('サイクル'), '計算サイクル: ' + cycle_now + ' \n幅比較: ' + cycle_rate + '%');
            commentsPlugin.showAtCell(r, getNumColHeader('サイクル'));
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
      const commentsPlugin = table.getPlugin('comments');
      datanow = table.getData();
      if ($("input[name='入力判定']").is(':checked')) {
        table.updateSettings({
          cells(row, col, prop) {
            if (table && datanow) {
              if (!this.readOnly && col != 2) {
                this.renderer = NotReadonlydRenderer;
                console.log(prop);
              }
              if (save_data[datanow[row][getNumColHeader('保存列目')]][col] != datanow[row][col] && col != 2 || datanow[row][col] === "NG") {
                if (!this.readOnly) {
                  this.renderer = RedRendererReadonly;
                } else {
                  this.renderer = RedRenderer;
                }
              }
              if (col == getNumColHeader('打数')) {
                if (!datanow[row][col]) {
                  this.renderer = BackgroundRedRenderer;
                }
              }
              if (datanow[row][getNumColHeader('判定')] == "◎") {
                this.readOnly = true;
              }
            }
          },
        });
      } else {
        table.updateSettings({
          cells(row, col, prop) {},
        });
      }
    }
    const RedRenderer = function(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.color = 'red';
      //td.style.fontWeight = 'bold';
    };
    const BackgroundRedRenderer = function(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.background = '#F5A89A';
      //td.style.fontWeight = 'bold';
    };
    const RedRendererReadonly = function(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.color = 'red';
      td.style.background = 'white';
    };
    const NotReadonlydRenderer = function(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.background = 'white';
    };


    function diff_time(fist_day, last_day, item) {
      const fist = new Date(fist_day);
      const last = new Date(last_day);
      const diffTime = Math.abs(last - fist);
      const diffSeconds = Math.ceil(diffTime / (1000));
      const diffMinutes = Math.ceil(diffTime / (1000 * 60));
      const diffHours = Math.ceil(diffTime / (1000 * 60 * 60));
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      let diff = 0;
      if (item == "s") {
        diff = diffSeconds;
      } else if (item == "m") {
        diff = diffMinutes;
      } else if (item == "h") {
        diff = diffHours;
      } else {
        diff = diffDays;
      }
      return diff;
    }

    function fb_download_CSV() {
      //var table = new Handsontable(grid,table);
      table.updateSettings({
        hiddenColumns: true,
        hiddenColumns: {
          columns: [
            0, 1, 2, getNumColHeader('良品率(N)'), getNumColHeader('不良率(N)'), getNumColHeader('社外品名'), getNumColHeader('保存列目')
          ]
        },
      });
      table.getPlugin("exportFile").downloadFile("csv", {
        columnHeaders: true,
        //rowHeaders: true,
        filename: "AggreView"
      });
      table.updateSettings({
        hiddenColumns: false
      });
    }

    function fb_send_data() {
      var date_name = ['date', 'moldmachine', 'itemcord', 'itemname', 'itemform', 'ntab', 'cycle', 'xstart_time1', 'xend_time1', 'totaltime', 'totalnum', 'goodnum', 'badnum', 'gootrate', 'badrate', 'defectivesitem', 'remark', 'xlsnum', 'workkind', 'workitem', 'usercord', 'username', 'usergp1', 'usergp2', 'moldplaceid', 'moldplace', 'moldlot', 'pending_num', 'missing_num', 'e_or_d', 'defact_check', 'beforeprocess', 'afterprocess', 'cutmethod', 'vapordepositionlot', 'defect', 'pdate', 'dateuse', 'scheduled_number', 'measure', 'state', 'materialsname', 'materialslot', 'materialsused', 'lotstarttime', 'lotendtime', 'hour', 'workplankind', 'plankind', 'plantime', 'place', 'updating_person', 'vap_mix', 'vap_befor_status', 'vap_ex_time', 'del_flg', 'xsetuptime', 'd_check', 'xputdata', 'item_set_num', 'created_at']
      datas = {};
      const send_data = table.getData();
      let b_check = true;
      if (send_data.length > 0) {
        send_data.forEach((value, key) => {
          if (value[getNumColHeader('編集')]) {
            if (!value[getNumColHeader('打数')]) {
              b_check = false;
            }
            sendid = value[1];
            value[getNumColHeader('作成日時')] = new Date().toLocaleString();
            value.splice(getNumColHeader('保存列目'), 1)
            value.splice(getNumColHeader('更新日時'), 1)
            value.splice(getNumColHeader('指図№'), 1)
            value.splice(getNumColHeader('vap_m_no'), 1)
            value.splice(getNumColHeader('pproentry'), 1)
            value.splice(getNumColHeader('過不足メモ'), 1)
            value.splice(getNumColHeader('入力者'), 2)
            value.splice(getNumColHeader('良品率'), 2)
            value.splice(getNumColHeader('生産時廃棄'), 3)
            value.splice(getNumColHeader('取数'), 1)
            value.splice(getNumColHeader('品名'), 1)
            value.splice(0, 3)
            var info = {};
            value.forEach((val, k) => {
              info[date_name[k]] = val;
            });
            datas[sendid] = info;
          }
        });
        if (!b_check) {
          alert("打数を入力して下さい。");
          return;
        }
        if (Object.keys(datas).length < 1) {
          alert("転送データがありません。");
          return;
        }
        console.log(datas);
        //return;
        var datas = {
          ac: "転送",
          sendata: datas,
        }
        loadingView(true);
        $.ajax({
          type: 'POST',
          url: "",
          dataType: 'text',
          data: datas,
          success: function(d) {
            loadingView(false);
            if (d == 'OK') {
              datanow = table.getData();
              datanow.forEach((value, key) => {
                if (value[getNumColHeader('編集')]) {
                  datanow[key][getNumColHeader('判定')] = '◎';
                  datanow[key][getNumColHeader('編集')] = false;
                  save_data[key] = datanow[key];
                }
              });
              localStorage.setItem("bk_data", JSON.stringify(datanow));
              localStorage.setItem("bk_save_data", JSON.stringify(save_data));
              table.loadData(datanow);
              alert('転送しました。');
            } else {
              alert("転送失敗 (; ω ;)ｳｯ…");
            }
            return;
          }
        });
      }
    }

    function fb_not_view_data() {
      const obj_data = table.getData();
      var obj_not_view_data = [];
      var obj_not_view_row = [];
      if (obj_data.length > 0) {
        obj_data.forEach((value, key) => {
          if (value[getNumColHeader('編集')]) {
            obj_not_view_data.push(value[getNumColHeader('ID')]);
            obj_not_view_row.unshift(key);
          }
        });
        if (Object.keys(obj_not_view_data).length < 1) {
          alert("非表示データがありません。");
          return;
        }
        var datas = {
          ac: "非表示",
          data: obj_not_view_data,
        }
        loadingView(true);
        $.ajax({
          type: 'POST',
          url: "",
          dataType: 'text',
          data: datas,
          success: function(d) {
            loadingView(false);
            if (d == 'OK') {
              obj_not_view_row.forEach((value, key) => {
                table.alter("remove_row", [
                  [value, 1]
                ]);
              });
              datanow = table.getData();
              localStorage.setItem("bk_data", JSON.stringify(datanow));
            } else {
              alert("非表示失敗 (; ω ;)ｳｯ…");
            }
            return;
          }
        });
      }
    }

    function getNumColHeader(titleHeader) {
      var getNum = -1;
      if (table) {
        const ColHeader = table.getColHeader();
        ColHeader.forEach((element, index) => {
          if (element === titleHeader) {
            getNum = index;
          }
        });
      }
      return getNum;
    }

    function getHeaderFromCol(getNum) {
      var titleHeader = "";
      if (table) {
        const ColHeader = table.getColHeader();
        ColHeader.forEach((element, index) => {
          if (index === getNum) {
            titleHeader = element;
          }
        });
      }
      return titleHeader;
    }

    $(function() {
      $("button").button();
      $("#alert").dialog({
        autoOpen: false,
        width: '980px',
        modal: true,
        title: '担当者を選択して下さい',
        position: ["center", 40],
        buttons: [{
          text: "閉じる",
          click: function() {
            $(this).dialog("close");
          }
        }]
      });

      $.getJSON("/common/AjaxUserSelect?action=user&gp1=" + decodeURIComponent("山崎") + "&gp2=" + decodeURIComponent("管理技術係") /*+"&callback=?"*/ , function(data) {
        $("#message").html(data);
      });
    });

    function fb_user_dialog() {
      $("#alert").dialog("open");
    }

    function loadingView(flag) {
      $('#loading').remove();
      if (!flag) return;
      $('<div id="loading" />').appendTo('body');
    }

    function setUser(name) {
      $("#担当者lable").html("転送担当者: ");
      $("#担当者").html(name);
      loadingView(true);
      $.ajax({
        type: 'GET',
        url: "/common/JSearch",
        dataType: 'json',
        data: {
          item: encodeURIComponent(name)
        },
        success: function(d) {
          $('#usercord').val(d.msPerson[0].ppro);
          $('#gp1').val(d.msPerson[0].gp1);
          $('#gp2').val(d.msPerson[0].gp2);
          var tanto = {
            name: name,
            ppro: d.msPerson[0].ppro,
            gp1: d.msPerson[0].gp1,
            gp2: d.msPerson[0].gp2
          }
          localStorage.setItem("担当者", JSON.stringify(tanto));
        }
      });
      $("#alert").dialog("close");
      loadingView(false);
    }
    $("input[name='入力判定']").click(function() {
      if ($(this).is(':checked')) {
        table.updateSettings({
          cells(row, col, prop) {
            if (table && datanow) {
              if (!this.readOnly && col != 2) {
                this.renderer = NotReadonlydRenderer;
                console.log(prop);
              }
              if (save_data[datanow[row][getNumColHeader('保存列目')]][col] != datanow[row][col] && col != 2 || datanow[row][col] === "NG") {
                if (!this.readOnly) {
                  this.renderer = RedRendererReadonly;
                } else {
                  this.renderer = RedRenderer;
                }
              }
              if (col == getNumColHeader('打数')) {
                if (!datanow[row][col]) {
                  this.renderer = BackgroundRedRenderer;
                }
              }
              if (datanow[row][getNumColHeader('判定')] == "◎") {
                this.readOnly = true;
              }
            }
          },
        });
      } else {
        table.updateSettings({
          cells(row, col, prop) {},
        });
      }
    });

    function datetime(row, col) {
      datanow = table.getData();
      $.ajax({
        type: 'GET',
        url: "DatetimeKeyBord",
        dataType: 'html',
        data: {
          "setdate": datanow[row][col]
        },
        success: function(data) {
          var options = {
            "title": "【" + getHeaderFromCol(col) + "】を入力してください。",
            autoOpen: false,
            width: 900,
            position: ["centetr", 50],
            buttons: [{
              text: "キャンセル",
              click: function(ev) {
                $(this).dialog("close");
                $("#message").html("");
              }
            }, {
              text: "決定",
              click: function(ev) {
                datanow[row][col] = btn_getvalue();
                $(this).dialog("close");
                $("#message").html("");
                table.loadData(datanow);
                // Manage comments programmatically:
                localStorage.setItem("bk_data", JSON.stringify(datanow));
                localStorage.setItem("bk_save_data", JSON.stringify(save_data));
                return;
              }
            }]
          };
          $("button").button();
          $("#message").html(data);
          $("#alert").dialog("option", options);
          $("#alert").dialog("open");
        }
      });
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

    function fb_data_view() {
      window.open('DailyDLView?placeid=' + placeid, '_blank');
    }
  </script>

  <div id="grid" class="table"></div>
  <div id="alert" style="font-size: 22px;">
    <div id="message" style="text-align: center;"></div>
  </div>
</body>

</html>
