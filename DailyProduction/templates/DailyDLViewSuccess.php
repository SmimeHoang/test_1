<?php
if (!$placeid) {

    slot('h1', '<h1> 稼働状態配置図 | Nalux');
    print '
                <div id="bot">
                <a class="blue" href="/DailyProduction/DailyDLView?placeid=1000079">野洲工場</a>
                <a class="blue" href="/DailyProduction/DailyDLView?placeid=1000073">山崎工場</a>
                <a class="blue" href="/DailyProduction/DailyDLView?placeid=1000125">NPG</a>
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
        <label  style="float:left;" for="start_sel">【検査】</label>
        <input type="text" name="start_sel" id="start_sel" style="float:left;font-size:18px; height:24px; margin-top:1px; width:7vw;" value="" list="ymList" autocomplete="off">
        <label  style="float:left;margin-top:5px;" for="end_sel">~</label>
        <input type="text" name="end_sel" id="end_sel" style="float:left;font-size:18px;height:24px; margin-top:1px;margin-right:5px; width:7vw;" value="" list="ymList" autocomplete="off">
        <datalist id="ymList">';
foreach ($dlist as $item) {
    $btn .= "<option value='" . $item['xwrad_date'] . "'>\n";
}
$btn .= '</datalist>
        <button type="button" style="height:28px; margin-top:1px;" onclick="view_table();">　検索　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="GetCSV();">　ロード　</button>
        <button type="button" style="height:28px; margin-top:1px;" onclick="SendData();">　転送画面　</button>
        <label id="担当者lable"></label>
        <label id="担当者"></label>
        <input type="hidden" id="usercord"/>
        <input type="hidden" id="gp1"/>
        <input type="hidden" id="gp2"/>
        </div>


        <button style="float:right; margin-top:1px;" type="button" onclick="window.close();return false;">閉じる</button>
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
    </style>
    <script type="text/javascript">
        var table = undefined;
        var placeid = '<?php echo $placeid; ?>';
        $(document).ready(function() {
            $("button").button();
            view_table();
        });

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
                    'title': '勤務日',
                    'readOnly': true,
                    renderer: "text",
                    className: 'htRight',
                    width: 100
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
                    'title': '型番',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htCenter',
                    width: 60
                },
                {
                    'title': '打数',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 60
                },
                {
                    'title': 'サイクル',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
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
                    'readOnly': true,
                    type: 'text',
                    className: 'htRight',
                    width: 130
                },
                {
                    'title': '終了日時',
                    'readOnly': true,
                    type: 'text',
                    className: 'htRight',
                    width: 130
                },
                {
                    'title': '作業時間',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 60
                },
                {
                    'title': '生産数',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 80
                },
                {
                    'title': '良品数',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 50
                },
                {
                    'title': '生産時廃棄',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 80
                },
                {
                    'title': 'サンプル',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 60
                },
                {
                    'title': '欠損品',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
                    width: 60
                },
                {
                    'title': '不良数',
                    'readOnly': true,
                    type: 'numeric',
                    className: 'htNoWrap',
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
                    'title': '不良品',
                    'readOnly': true,
                    type: 'text',
                    className: 'htNoWrap',
                    width: 300
                },
                {
                    'title': '備考',
                    'readOnly': true,
                    type: 'text',
                    className: 'htNoWrap',
                    width: 200
                },
                {
                    'title': '入力者',
                    'readOnly': true,
                    type: 'text',
                    className: 'htCenter',
                    width: 120
                },
                {
                    'title': '入力日時',
                    'readOnly': true,
                    type: 'text',
                    className: 'htRight',
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
                    className: 'htNoWrap',
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
                }
            ];
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
                //fixedRowsBottom:1,
                //hiddenColumns: {columns: col_num},
                dropdownMenu: ['make_read_only', 'alignment', 'filter_by_condition', 'filter_action_bar', 'filter_by_value'],
                afterDeselect: function() {
                    var cols = table.countCols();
                    var rows = table.countRows();
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

        function GetCSV() {
            //var table = new Handsontable(grid,table);
            table.updateSettings({
                hiddenColumns: true,
                //hiddenColumns: {columns: [0,1,4,5,9,10]},
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

        function SendData() {
            localStorage.removeItem("bk_data");
            localStorage.removeItem("担当者");
            window.open('DailyReport?placeid=' + placeid, '_blank');
        }

        function getNumColHeader(titleHeader) {
            var getNum = -1;
            const ColHeader = table.getColHeader();
            ColHeader.forEach((element, index) => {
                if (element === titleHeader) {
                    getNum = index;
                }
            });
            return getNum;
        }

        function getHeaderFromCol(getNum) {
            var titleHeader = "";
            const ColHeader = table.getColHeader();
            ColHeader.forEach((element, index) => {
                if (index === getNum) {
                    titleHeader = element;
                }
            });
            return titleHeader;
        }
    </script>

    <div id="grid" class="table"></div>
    <div id="alert" style="font-size: 22px;">
        <div id="message" style="text-align: center;"></div>
    </div>
</body>

</html>
