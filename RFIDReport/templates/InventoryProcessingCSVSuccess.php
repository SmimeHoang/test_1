<?php
    slot('h1','<h1 style="margin:0 auto;">棚卸結果処理 | '.$_GET["plant"].' | Nalux</h1>');
// print "<pre>";
// print_r($data);
// print "</pre>";
?>
<style type="text/css">
    #content{height:calc(100% - 30px);}
    table.type03 { font-size:90%; border-collapse: collapse; text-align: center; line-height: 1.5;border-left: 3px solid #369; border-top: 1px solid #369;table-layout: fixed; margin-right:0.2em; }
    table.type03 th { padding: 1px 4px; font-weight: bold; vertical-align: top; color: #153d73; border-right: 1px solid #000; border-bottom: 1px solid #000;position: sticky;top: 0;background-color: white;box-shadow: 0px 1px 0px 0px #000;}
    table.type03 td { padding: 1px 4px; vertical-align: top; border-right: 1px solid #000; border-bottom: 1px solid #000; white-space: nowrap; }
    table caption { font-size:16px; text-align: left; font-weight: bold; color:#0d2e59; }
    #loading { width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; /* 背景関連の設定 */ background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center; background-repeat: no-repeat; background-attachment: fixed; }
    #loading img{ max-width: 100%; height:auto; }
    input{
        font-weight: bold;
        font-size: 100%;
        text-align:center;
    }
    .add_item input{
        font-weight: normal;
        border: none;
    }
    /* .ip-rfid{font-weight: bold;width: 330px;} */
    #csvFile{
        padding:0;
        background: #00F;
        color: #FFF;
    }
    /* .header_row{
        font-weight:bold;
        color:#153d73;
    } */
    .scaned_row{
        background:#8ce6f0;
    }
    .unscaned_row{
        background:#ff5b5b;
    }
    .unknow_row{
        background:#e6e6e6;
    }
    .complete_row{
        background:#aae6aa;
    }
</style>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/encoding-japanese/1.0.28/encoding.min.js"></script> -->
<script type="text/javascript">

    $(document).ready(function(){
        $("#csvFile").button();
        $("button").button();
        $("#date").datepicker();
        var ww = $(window).width()-20;
        $( "#alert" ).dialog({
            autoOpen: false,
            width: ww,
            buttons: [{class:"btn-left", text: "閉じる", click: function() {
                $( this ).dialog( "close" );
            }}]
        });

        $('#csvFile').on('change', () => {
            //ファイル読み取り
            readFile();
            $("#date").val(nowDT("date"));
        });
        $("#date").val(nowDT("date"));

        $( "#openAlert" ).dialog({
            autoOpen: false,
            width: ww,
            modal:true,
            position:["center"],
            buttons: [{ text:"閉じる",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide();$('button').blur(); }
        });

        window.onbeforeunload = function() {
            if(saved_flag==false){
                return "このページを再読み込みしますか？ 入力した情報は保存されません。";
            }
        }
        opend_user();

    });

    var max_id = 0;
    async function readFile(){
        const fileInput = document.querySelector('#csvFile');
        const reader = new FileReader();
        const file_name = fileInput.files[0].name.replace(".csv","");
        if(file_name.split("_")[0]=="棚卸実績"){
            max_id = file_name.split("_")[3];
        }else{
            max_id = file_name.split("_")[2];
        }
        reader.readAsText(fileInput.files[0],"Shift-JIS");
        reader.addEventListener("load",async () => {
            //ファイル読み取り後でテーブルに変換
            let csv_data = await convertToTable(reader.result,file_name);
            if(csv_data.length==0){
                openAlert("エーラが発生","ファイルのデータが読み取りできません。");
                $("#cmd_btn button").button("disable");
                $("#cmd_btn").append("<p class='fc_msg'><b style='color:red;'>ファイルのデータが読み取りできません。</b></p>");
                return;
            }else{
                $("#cmd_btn button").button("enable");
                $(".fc_msg").hide();
            }
        });
    }

    var main_list_data=[];
    var sum_data = {};

    function convertToTable(d,file_name) {
        main_list_data=[];
        sum_data = {};
        return new Promise((resolve) => {
            $("#view_content").html("");

            //Split by new line to get row
            let rows = d.split("\n");
            main_list_data=rows;
            // console.log(d);
            let rows_data = [];
            let add_table = `<table id="csv_content" class="type03" >`;

            $.each(rows,function(k,v){
                if(k>0){ 
                    let row = v.replace(`"\r`,`"`);
                    let array_row = row.substr(1,row.length-2).split(`","`);
                    // console.log(array_row);
                    // return;
                    if(array_row[0]!=""){
                        if(!sum_data[array_row[0]]){
                            sum_data[array_row[0]]={};
                            sum_data[array_row[0]]["sum_num"]=parseInt(array_row[5]);
                            sum_data[array_row[0]]["sum_no"]=1;
                        }else{
                            sum_data[array_row[0]]["sum_num"] += parseInt(array_row[5])
                            sum_data[array_row[0]]["sum_no"]++
                        }
                    }
      
                    if(array_row.length>1){
                        rows_data.push(array_row);
                        
                        let row_class = "defult_row";
                        let view_stt = "実績数"
                        let status = "未確認";
                        switch (array_row[7]){
                            case "1":
                                row_class = "defult_row";
                                status = "-";
                                view_stt = "実績数";
                                break;
                            case "2":
                                row_class = "scaned_row";
                                status = "OK";
                                view_stt = "実績数";
                                break;
                            case "0":
                                row_class = "unscaned_row";
                                status = "現物なし";
                                view_stt = "残数";
                                break;
                            case "3":
                                row_class = "unknow_row";
                                status = "リスト外";
                                view_stt = "予定外";
                                break;
                        }
                        add_table+= `<tr id="csv_tr_`+array_row[3]+`" class="`+row_class+`">`;
                        add_table+=`<td>`+k+`</td>`;
                        $.each(array_row,function(a,b){
                            if(a==2 || a==6 || a==7){
                                add_table+=`<td style="display:none;">`+b+`</td>`;
                            }else{
                                if(a==5){
                                    add_table+=`<td id="num_`+array_row[3]+`">`+b+`</td>`;
                                }else if(a==1){
                                    add_table+=`<td id="itemname_`+array_row[3]+`" style="max-width:120px;overflow: hidden;" >`+b+`</td>`;
                                }else if(a==0){
                                    add_table+=`<td id="itemcode_`+array_row[3]+`">`+b+`</td>`;
                                }else if(a==4){
                                    add_table+=`<td id="position_`+array_row[3]+`">`+b+`</td>`;
                                }else{
                                    add_table+=`<td>`+b+`</td>`;
                                }
                            }
                        });
                        add_table+=`<td id="stt_`+array_row[3]+`">`+status+`</td>`;
                        add_table+= `</tr>`;
                    }
                }
            });
            $("#view_content").html(add_table);
            cacl_sum_table();
            resolve(rows_data);
        });
    }

    function view_now_list(){
        cacl_sum_table();
    }

    var formatThousands = function(n, dp){
        if(Math.floor(n)){
            var s = ''+(Math.abs(Math.trunc(n))), d = Math.abs(n) % 1, i = s.length, r = '';
            while ( (i -= 3) > 0 ) { r = ',' + s.substr(i, 3) + r; }
            if(n<0){
                return '-'+s.substr(0, i + 3) + r + (d ? '.' + Math.round(d * Math.pow(10, dp || 2)) : '');
            }else{
                return s.substr(0, i + 3) + r + (d ? '.' + Math.round(d * Math.pow(10, dp || 2)) : '');
            }
        }else{
            return 0;  
        }

    };

    function compareFn(a, b) {
        return a-b
    }

    function sortObject(obj) {
        return Object.keys(obj).sort().reduce(function (result, key) {
            result[key] = obj[key];
            return result;
        }, {});
    }

    async function cacl_sum_table(){
        let new_rows = $("#view_content tr");
        let rows = [];
        sum_data = {};
        // let list_items = 
        $.each(new_rows, function (a, b) { 
            rows.push(b);
        });
        let name_list = ["No","品目コード","品名","グループ","RFID","保管場所","数量","スキャン場所","状態","棚卸日時","ユーザID"];

        let data = await get_table_val(rows,name_list);
        // console.log(data);
        let no_chip_data = await get_no_chip_data();
        // console.log(no_chip_data);
        $.each(data,function(k,v){
            if(v.品目コード!=""){
                if(!sum_data[v.品目コード]){
                    sum_data[v.品目コード]={};
                    sum_data[v.品目コード]["das_inv_no_rfid_num"]=0;
                    sum_data[v.品目コード]["das_inv_num"]=parseInt(v.数量);
                    sum_data[v.品目コード]["adm_search_key"]=v.品目コード;
                    sum_data[v.品目コード]["tag_name"]=v.品名;
                    sum_data[v.品目コード]["sum_no"]=1;
                }else{
                    sum_data[v.品目コード]["das_inv_num"] += parseInt(v.数量)
                    sum_data[v.品目コード]["sum_no"]++
                }
            }
        })

        $.each(no_chip_data,function(k,v){
            if(v.品目コード!=""){
                let adm_itemcode = v.wic_itemcode;
                if(v.wic_process_key!="0"){
                    adm_itemcode = v.wic_itemcode+""+v.wic_process_key;
                }
                if(!sum_data[adm_itemcode]){
                    sum_data[adm_itemcode]={};
                    sum_data[adm_itemcode]["das_inv_no_rfid_num"]=parseInt(v.sum_inv_num);
                    sum_data[adm_itemcode]["das_inv_num"]=0;
                    sum_data[adm_itemcode]["adm_search_key"]=adm_itemcode;
                    sum_data[adm_itemcode]["tag_name"]=v.tag_name;
                    sum_data[adm_itemcode]["sum_no"]="-";
                }else{
                    if(sum_data[adm_itemcode]["das_inv_no_rfid_num"]>0){
                        sum_data[adm_itemcode]["das_inv_no_rfid_num"] += parseInt(v.sum_inv_num)
                    }else{
                        sum_data[adm_itemcode]["das_inv_no_rfid_num"] = parseInt(v.sum_inv_num)
                    }
                }
            }
        })
        
        // sum_data = sortObject(sum_data);
        localStorage.setItem("inventory_cacl",JSON.stringify(sum_data));
        let sum_table_no = 1;
        let sum_table ="";
        // console.log(sum_data);
        setTimeout(() => {
            $.each(sum_data,function(k,v){
                sum_table+= `<tr>`;
                sum_table+=`<td>`+(sum_table_no++)+`</td>`;
                sum_table+=`<td>`+k+`</td>`;
                sum_table+=`<td  style="max-width:120px;overflow: hidden;">`+v.tag_name+`</td>`;
                sum_table+=`<td style="font-weight:bold;text-align:right;">`+v.sum_no+`</td>`;
                sum_table+=`<td style="font-weight:bold;text-align:right;">`+formatThousands(v.das_inv_num)+`</td>`;
                sum_table+=`<td style="font-weight:bold;text-align:right;">`+formatThousands(v.das_inv_no_rfid_num)+`</td>`;
                sum_table+=`<td style="font-weight:bold;text-align:right;">`+formatThousands(parseInt(v.das_inv_no_rfid_num)+parseInt(v.das_inv_num))+`</td>`;
                sum_table+= `</tr>`;
            });
            $("#sum_content").html(sum_table);
        }, 0);
    }

    function get_no_chip_data(){
        // console.log(max_id);

        return new Promise((resolve) => {
            $.ajax({
                type: "GET",
                url: "InventoryResults",
                dataType: "json",
                data: {
                    ac:"getInfo",
                    mode:"getNoRfid",
                    plant:"<?= $sf_params->get("plant")?>",
                    max_id:max_id
                },
            })
            .done(function(res){ 
                // console.log(res);
                // return;
                resolve(res);
            })
            .fail(function(res, textStatus, errorThrown){
                resolve(errorThrown);
                console.log("fail");
            })
            .always(function(res, textStatus, errorThrown){
                resolve(textStatus);
                // console.log(textStatus);
            })
        });
    }

    function view_data(){
        console.log(main_list_data);
        console.log(sum_data);
    }

    async function goConfirm(){
        let unscaned_row = $(".unscaned_row");
        let rows = [];
        $.each(unscaned_row, function (a, b) { 
            rows.push(b);
        });

        if(rows.length==0){
            openAlert("確認","残数のデータが無い！！！");
            return;
        }

        let name_list = ["No","品目コード","品名","グループ","RFID","保管場所","数量","スキャン場所","状態","棚卸日時","ユーザID"];
        let data = await get_table_val(rows,name_list);
        // console.log(data);
        let list_rfid = get_gr_val(data,"RFID");
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=getDeff",
            data: {
                rfids:list_rfid
            },
            dataType: "json",
        })
        .done(function(res){
            // console.log(res)
            if(res.length == 0){
                openAlert("確認","在庫のデータも無くなりました。");
                return;
            }
            let msg = `<table class='type03' style="font-size:85%">
                <thead>
                    <th style="width:40px">No</th>
                    <th style="width:80px">在庫減らす</th>
                    <th style="width:80px">管理ID</th>
                    <th style="width:80px">実績ID</th>
                    <th style="width:210px">RFID</th>
                    <th style="width:115px">作業日</th>
                    <th style="width:100px">品目コード</th>
                    <th style="width:100px">品名</th>
                    <th style="width:60px">型番</th>
                    <th style="width:60px">CAV</th>
                    <th style="width:60px">工程</th>
                    <th style="width:40px">末番</th>
                    <th style="width:40px">数量</th>
                    <th style="width:100px">作業者</th>
                    <th style="width:100px">棚卸結果</th>
                </thead>
                <tbody>`;
            $.each(res, function (a, b) { 
                if(b.inv_num>0){
                    msg+=`<tr id="check_row_`+b.wic_rfid+`" style="background:#c4e9ff;" >
                        <td>`+(a+1)+`</td>
                        <td><input type="checkbox" class="process_check" value="`+b.wic_rfid+`" checked="checked" /></td>
                        <td>`+b.wic_id+`</td>
                        <td>`+b.wic_hgpd_id+`</td>
                        <td>`+b.wic_rfid+`</td>
                        <td>`+b.wic_date+`</td>
                        <td>`+b.wic_itemcode+`</td>
                        <td>`+b.wic_itemcode+`</td>
                        <td>`+b.wic_itemform+`</td>
                        <td>`+b.wic_itemcav+`</td>
                        <td>`+b.wic_process+`</td>
                        <td>`+b.wic_process_key+`</td>
                        <td>`+b.inv_num+`</td>
                        <td>`+b.wic_name+`</td>
                        <td>見つからない</td>
                    </tr>`;
                }
            });
            msg+= "</tbody></table>";

            var options = {"title":"在庫データ確認",
                position:["center",130],
                width: "auto",
                buttons: 
                    [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                    }},{ class:"btn-right",text:"在庫調整実行",click :function(ev) {
                        inventoryUpdate();
                    }}]
            };
            $("#message").html(msg);

            $(".process_check").on("change",function(e){
                if(e.target.checked){
                    $("#check_row_"+e.target.value).css({"background":"#c4e9ff"});
                }else{
                    $("#check_row_"+e.target.value).css({"background":"#FFF"});
                }
            });

            $(".btn_printer").button();
            $(".ui-resizable-handle").hide();
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
        })
        .fail(function(res, textStatus, errorThrown){
            console.log("fail");
            // console.log(res);
            // console.log(textStatus);
            // console.log(errorThrown);
        })
        .always(function(res, textStatus, errorThrown){
            loadingView(false);
        })
        // console.log(list_rfid);
    }

    async function unknowConfirm(){
        let unknow_row = $(".unknow_row");
        let rows = [];
        $.each(unknow_row, function (a, b) { 
            rows.push(b);
        });

        if(rows.length==0){
            openAlert("確認","予定外のデータが無い！！！");
            return;
        }

        let name_list = ["No","品目コード","品名","グループ","RFID","保管場所","数量","スキャン場所","状態","棚卸日時","ユーザID"];
        let data = await get_table_val(rows,name_list);
        // console.log(data);
        let list_rfid = get_gr_val(data,"RFID");
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=getDeff&mode=unknow",
            data: {
                rfids:list_rfid
            },
            dataType: "json",
        })
        .done(function(res){
            let sp_rfid = list_rfid.split(",");
            
            if(res.length==0 || sp_rfid.length>res.length){
                let msg="下のRFIDを追加登録しますか？"
                let add_list_rfid=[];
                $.each(sp_rfid, function (a, b) { 
                    if($.inArray(b,res)==-1){
                        msg+="<li>"+b+"</li>";
                        add_list_rfid.push(b);
                    }
                });
                var options = {"title":"DAS在庫データが見つかりません。",
                    position:["center",130],
                    width: 600,
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                        }},{ class:"btn-right",text:"入力フォームへ",click :function(ev) {
                            $( this ).dialog( "close" );
                            addUnknowData(add_list_rfid.join(","));
                        }}]
                };
                $("#message").html(msg);
                $(".ui-resizable-handle").hide();
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }else{
                alert("DASのデータと合わせました。");
                return;
                let msg = `<table class='type03' style="font-size:85%">
                <thead>
                    <th style="width:40px">No</th>
                    <th style="width:80px">在庫合わせ</th>
                    <th style="width:80px">管理ID</th>
                    <th style="width:80px">実績ID</th>
                    <th style="width:210px">RFID</th>
                    <th style="width:115px">作業日</th>
                    <th style="width:100px">品目コード</th>
                    <th style="width:100px">品名</th>
                    <th style="width:60px">型番</th>
                    <th style="width:60px">CAV</th>
                    <th style="width:60px">工程</th>
                    <th style="width:40px">末番</th>
                    <th style="width:40px">数量</th>
                    <th style="width:100px">作業者</th>
                    <th style="width:100px">棚卸結果</th>
                </thead>
                <tbody>`;
                $.each(res, function (a, b) { 
                    msg+=`<tr id="check_row_`+b.wic_rfid+`" style="background:#c4e9ff;" >
                        <td>`+(a+1)+`</td>
                        <td><input type="checkbox" class="process_check" value="`+b.wic_rfid+`" checked="checked" /></td>
                        <td>`+b.wic_id+`</td>
                        <td>`+b.wic_hgpd_id+`</td>
                        <td>`+b.wic_rfid+`</td>
                        <td>`+b.wic_date+`</td>
                        <td>`+b.wic_itemcode+`</td>
                        <td>`+b.wic_itemcode+`</td>
                        <td>`+b.wic_itemform+`</td>
                        <td>`+b.wic_itemcav+`</td>
                        <td>`+b.wic_process+`</td>
                        <td>`+b.wic_process_key+`</td>
                        <td>`+b.inv_num+`</td>
                        <td>`+b.wic_name+`</td>
                        <td>DASで有り</td>
                    </tr>`;  
                });
                msg+= "</tbody></table>";

                var options = {"title":"在庫データ確認",
                    position:["center",130],
                    width: "auto",
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                        }},{ class:"btn-right",text:"DASと合わせる",click :function(ev) {
                            // inventoryUpdate();
                        }}]
                };
                $("#message").html(msg);

                $(".process_check").on("change",function(e){
                    if(e.target.checked){
                        $("#check_row_"+e.target.value).css({"background":"#c4e9ff"});
                    }else{
                        $("#check_row_"+e.target.value).css({"background":"#FFF"});
                    }
                });

                $(".btn_printer").button();
                $(".ui-resizable-handle").hide();
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }
        
        })
        .fail(function(res, textStatus, errorThrown){
            console.log("fail");
            // console.log(res);
            // console.log(textStatus);
            // console.log(errorThrown);
        })
        .always(function(res, textStatus, errorThrown){
            loadingView(false);
        })
    }

    function inventoryUpdate(){
        var checked_obj = $(".process_check");
        var checked_list = [];
        $.each(checked_obj, function (a, b) { 
            if(b.checked===true){
                checked_list.push(b.value);
            }
        });

        if(checked_list.length==0){
            openAlert("確認","対応データが無い！！！");
            return;
        }
        loadingView(true);

        $.ajax({
            type: "POST",
            url: "?ac=inventoryUpdate",
            data: {
                rfids:checked_list,
                user:$("#username").val()
            },
            dataType: "json"
        })
        .done(function(res){
            if(res=="OK"){
                $.each(checked_list, function (k, v) { 
                    $("#stt_"+v).html("OK");
                    $("#num_"+v).html("0");
                    $("#csv_tr_"+v).removeClass().addClass("complete_row");
                });
                setTimeout(() => {
                    $( "#alert" ).dialog( "close" );
                    cacl_sum_table();
                }, 0);
            }else{
                openAlert("更新出来ません。",res);
            }
        }) 
        .fail(function(res, textStatus, errorThrown){
            console.log("fail");
            // console.log(res);
            // console.log(textStatus);
            // console.log(errorThrown);
        })
        .always(function(res, textStatus, errorThrown){
            loadingView(false);
        })

    }

    function addUnknowData(list_rfid){
        // console.log(list_rfid); 
        let msg = `<table class='type03' style="font-size:85%">
                <thead>
                    <th style="">No</th>
                    <th style="">RFID</th>
                    <th style="">Lot</th>
                    <th style="">成形日</th>
                    <th style="">作業日</th>
                    <th style="">品目コード</th>
                    <th style="">品名</th>
                    <th style="">型番</th>
                    <th style="">CAV</th>
                    <th style="">工程</th>
                    <th style="">末番</th>
                    <th style="">生産数</th>
                    <th style="">良数</th>
                    <th style="">作業時間</th>
                    <th style="">不良内容</th>
                </thead>
                <tbody>`;
                $.each(list_rfid.split(","), function (a, b) { 
                    msg+=`<tr id="check_row_`+b.wic_rfid+`" class="add_item" style="background:#FFF;" >
                        <td>`+(a+1)+`</td>
                        <td><input type="text" class="ip-rfid" name="rfid" value="`+b+`" readOnly="readOnly"  style="font-weight:bold;width:250px;overflow: hidden;" /></td>
                        <td><input type="text" class="ip-moldlot" name="moldlot" value="" placeholder="yymmdd" style="width:80px;" /></td>
                        <td><input type="date" class="ip-molddate" name="molddate" value="" style="" /></td>
                        <td><input type="date" class="ip-workdate" name="workdate" value="" style="" /></td>
                        <td><input type="text" class="ip-itemcode" name="itemcode" value="" style="width:120px;" /></td>
                        <td><input type="text" class="ip-itemname" name="itemname" value="" style="width:100px;" /></td>
                        <td><input type="text" class="ip-formnum" name="formnum" value="" style="width:60px;" /></td>
                        <td><input type="text" class="ip-itemcav" name="itemcav" value="" style="width:60px;" /></td>
                        <td><input type="text" class="ip-workitem" name="workitem" value="" style="width:100px;" /></td>
                        <td><input type="text" class="ip-workitem-key" name="" value="" style="width:60px;" /></td>
                        <td><input type="text" class="ip-allnum" name="allnum" value="" style="width:60px;" /></td>
                        <td><input type="text" class="ip-goodnum" name="goodnum" value="" style="width:60px;" /></td>
                        <td><input type="text" class="ip-worktime" name="worktime" value="" style="width:60px;" /></td>
                        <td><input type="text" class="ip-badinfo" name="badinfo" value="" placeholder="不良1=>量1,不良2=>量2..." style="width:200px;" /></td>
                    </tr>`;  
                });
                msg+= "</tbody></table>";
        var options = {"title":"追加登録",
            position:["center",130],
            width: "auto",
            buttons: 
                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }},{ class:"btn-right",text:"追加登録",click :function(ev) {
                    // $( this ).dialog( "close" );
                    let add_data = {};
                    $.each($(".add_item"), function (key, val) { 
                        // var cells = val.map(row => Array.from(row.cells).map(cell => cell.textContent));
                        // console.log(val.cells);
                        let r ={};
                        $.each(val.cells,function(a,b){
                            let ip = b.firstChild;
                            if(a>0 && ip){
                                r[ip.name]=ip.value;
                            }
                        })
                        r["user_name"]=$("#username").val();
                        add_data[key]=r;
                    });
                    // addUnknowData(list_rfid);
                    setTimeout(() => {
                        entryAddData(add_data)
                    }, 0);
                }}],
            open: function() {
                $('.ui-dialog :input').blur();
                $(".ip-rfid").css({"direction": "rtl"});
            }
        };
        $("#message").html(msg);
        $(".ui-resizable-handle").hide();
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
    }

    var saved_flag = false;
    async function adempiereUpdate(){
        let list_item = [];
        let rows = [];
        // let table_data = $("#view_content tr");
        let table_data = $("#sum_content tr");
        if(table_data.length==0){
            alert("棚卸実績CSVをインプットしてください。");
            return;
        }else{
            if(confirm("棚卸結果を保存して、Adempiereとの誤差確認画面へ移動しますか?")===false){
                return;
            }
        }
        $.each(table_data, function (a, b) { 
            rows.push(b);
        });

        var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
        var data = [];
        $.each(cells,function(a,b){
            let this_item_code = b[1];
            if(this_item_code.length>11){
                this_item_code=this_item_code.substr(0,this_item_code.length-1);
            }
            if($.inArray(this_item_code, list_item)===-1){
                list_item.push(this_item_code);
            }
        })

        // list_item = list_item.substr(0,list_item.length-1);
        list_item=list_item.join(",")
        // console.log(list_item);
        // return;

        $.ajax({
            type: "GET",
            url: "InventoryResults",
            data: {
                ac:"entry",
                plant:"<?= $sf_params->get("plant") ?>",
                username:$("#username").val(),
                max_id:max_id
            },
            dataType: "json"
        }).done(function(res){
            console.log(res);
            if(res=="OK"){
                saved_flag = true;
                let url = "AdempiereInventoryUpdate?plant=<?= $sf_params->get("plant") ?>&itemcodes="+list_item+"&maxid="+max_id;
                window.open(url);
            }else{
                saved_flag = false;
                openAlert("更新出来ません。",res);
            }
        })
    }

    async function adempiereDiffView(){
        let list_item = [];
        let rows = [];
        // let table_data = $("#view_content tr");
        let table_data = $("#sum_content tr");
        if(table_data.length==0){
            alert("棚卸実績CSVをインプットしてください。");
            return;
        }
        $.each(table_data, function (a, b) { 
            rows.push(b);
        });

        var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
        var data = [];
        $.each(cells,function(a,b){
            let this_item_code = b[1];
            if(this_item_code.length>11){
                this_item_code=this_item_code.substr(0,this_item_code.length-1);
            }
            if($.inArray(this_item_code, list_item)===-1){
                list_item.push(this_item_code);
            }
        })

        // list_item = list_item.substr(0,list_item.length-1);
        list_item=list_item.join(",")
        // console.log(list_item);
        // return;

        let url = "AdempiereInventoryUpdate?mode=view&plant=<?= $sf_params->get("plant") ?>&itemcodes="+list_item+"&maxid="+max_id;
        window.open(url);
    }

    function entryAddData(add_data){
        $.ajax({
            type: "POST",
            url: "?plant=<?= $sf_params->get("plant") ?>&ac=addUnknowData",
            data: add_data,
            dataType: "json"
        })
        .done(function(res){
            if(res[0]=="OK"){
                $("#alert").dialog( "close" );
                openAlert("完了","追加登録しました。");
                if(res[1]){
                    $.each(res[1],function(k,v){
                        $("#stt_"+v.rfid).html("OK");
                        $("#itemcode_"+v.rfid).html(v.itemcode);
                        $("#itemname_"+v.rfid).html(v.itemname);
                        $("#position_"+v.rfid).html(v.position);
                        $("#num_"+v.rfid).html(v.num);
                        $("#csv_tr_"+v.rfid).removeClass().addClass("complete_row");
                    })
                }
                setTimeout(() => {
                    cacl_sum_table();
                }, 100);
            }else{
                openAlert("登録が出来ません。",res);
            }
        }) 
        .fail(function(res, textStatus, errorThrown){
            console.log("fail");
            // console.log(res);
            // console.log(textStatus);
            // console.log(errorThrown);
        })
        .always(function(res, textStatus, errorThrown){
            loadingView(false);
        }) 
    }

    function get_gr_val(arr, name) {
        let data = "";
        $.each(arr, function (a, b) { 
            data+=b[name]+",";
        });
        return data.substr(0,data.length-1);
    }

    function get_table_val(rows,name_list){
        return new Promise((resolve) => {
            var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
            var data = [];
            $.each(cells,function(a,b){
                let r ={};
                $.each(name_list,function(k,v){
                    r[v]=b[k]
                })
                data.push(r)
            })
            resolve(data);
        });
    }

    function get_table_all_val(id,name_list){
        return new Promise((resolve) => {
            const table = $("#"+id); 
            var data = [];
            $.each(table,function(k,v){
                var rows = Array.from(v.rows);
                var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
                $.each(cells,function(a,b){
                    let r =[];
                    $.each(name_list,function(k,v){
                        if(k>0){
                            r.push(b[k].replace(/,/g,"=>"));
                        }
                    })
                    data.push(r);
                });
            });
            resolve(data);
        });
    }

    function loadingView(flag) {
        $('#loading').remove();
        if(!flag) return;
        $('<div id="loading" />').appendTo('body');
    }

    function opend_user(){
        var gp1 = decodeURIComponent("<?php echo str_replace("工場","",$sf_params->get('plant'));?>");
        var gp2 = decodeURIComponent("製造係_2班");
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+gp1+"&gp2="+gp2/*+"&callback=?"*/,function(data){
            $("#message").html(data);
        });
        let ww = $(window).width()-20;
        var options = {"title":"担当者を選択してください。",
            width: ww,
            position:["centetr",50],
            modal:true,
            buttons: [],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
    }
  
    function setUser(name){
        $("#username").val(name);
        $( "#alert" ).dialog( "close" );
        getList();
	}

    function getList(){
        if($("#date").val()==""){
            return;
        }
        loadingView(true);
        $.ajax({
            type: 'GET',
            url: "",
            dataType: 'json',
            data:{
                'ac':'getList',
                'date':$("#date").val(),
                'username':$("#username").val()
            },
            success: function(d) {
                $('#view_list').html(d);
            }
        });
        loadingView(false);
    }

    async function downloadCSV() {
        //CSVデータ
        const filename = "download.csv"
        let name_list = ["No","品目コード","品名","グループ","RFID","保管場所","数量","スキャン場所","状態","棚卸日時","ユーザID"];
        const data = await get_table_all_val("view_table",name_list);
        // console.log(data);
        // return;
        //BOMを付与
        const bom = new Uint8Array([0xEF, 0xBB, 0xBF]) 
        const blob = new Blob([ bom, '"'+data.join('"\r\n"').replace(/,/g,'","').replace(/=>/g,',')+'"' ], { type : 'text/csv' }) 

        // // SHIFT-JISにエンコード
        // const sjisData = Encoding.convert(data, {to: 'SJIS', from: 'UNICODE', type: 'arraybuffer'});
        // // 先にUint16ArrayからUint8Arrayに変換する
        // const uint8Array = new Uint8Array(sjisData);
        
        // //BlobからオブジェクトURLを作成
        // const blob = new Blob([uint8Array], { type: "text/csv;charset=shift-jis;" });
        // ダウンロードリンクを作成
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;

        // ダウンロードリンクをクリック
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // 後処理
        URL.revokeObjectURL(link.href);
    }

    function childData(id){
        alert(id);
    }
    
    function nowDT(str,d=null){
        var weeks = new Array('日','月','火','水','木','金','土');
        var now = new Date();
        var year = now.getYear(); // 年
        var month = now.getMonth() + 1; // 月
        var day = now.getDate(); // 日
        var week = weeks[ now.getDay() ]; // 曜日
        var hour = now.getHours(); // 時
        var min = now.getMinutes(); // 分
        var sec = now.getSeconds(); // 秒
        if(year < 2000) { year += 1900; }
        // 数値が1桁の場合、頭に0を付けて2桁で表示する指定
        if(month < 10) { month = "0" + month; }
        if(day < 10) { day = "0" + day; }
        if(hour < 10) { hour = "0" + hour; }
        if(min < 10) { min = "0" + min; }
        if(sec < 10) { sec = "0" + sec; }
        if(str=="yd"){
        return year+"/"+month+"/"+day+" "+hour+":"+min+":"+sec+" "+week;
        }
        if(str=="dt"){
            return year+"/"+month+"/"+day+" "+hour+":"+min;
        }
        if(str=="date"){
            return year+"/"+month+"/"+day;
        }
        if(str=="ti"){
            return hour+":"+min;
        }
    }

    function openAlert(title,msg,alert_btn,callback){
        if(!alert_btn){
            alert_btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
                if(callback){
                    callback();
                }
                return;
            }}]
        }
        var options = {"title":title,
            position:["center", 170],
            width: 600,
            buttons:alert_btn
        };
        $("#msg").html(msg);
        $( "#openAlert" ).dialog( "option",options);
        $( "#openAlert" ).dialog( "open" );
        return false;
    }

</script>

<div style="margin-top:6px;font-weight: bold;">
    <label for="date">作業日</label>
    <input type="text" value="" id="date" name="date" style="padding:3px;width:100px;"/>
    <label for="username">氏名</label>
    <input onclick="opend_user();" type="text" value="" id="username" name="username" style="padding:3px;width:150px;"/>
    <input type="file" id="csvFile" accept="text/csv" style="width:600px;text-align:left;"></input>
</div>
<div style='width:auto;border:1px solid #ccc;box-shadow: 5px 5px 5px 0px #ccc;margin:5px auto;padding:5px;height:calc(100% - 65px);'>
    <div id="cmd_btn" style="display: block;margin-bottom:5px;">
        <button onclick="goConfirm();" style="margin-right:5px;" >現物なし修正</button>
        <button onclick="unknowConfirm();" style="margin-right:20px;" >リスト外登録</button>
        <button onclick="adempiereDiffView();" style="margin-right:5px;" >誤差表示</button>
        <button onclick="adempiereUpdate();" style="margin-right:20px;" >結果登録＆Adempiereに転送</button>
        <!-- <button onclick="view_now_list();" style="margin-right:20px;" >data</button> -->
        <!-- <button onclick="downloadCSV();" style="margin-right:20px;" >CSV出力</button> -->
    </div>
    <div style="clear:both;"></div>
    <!-- <pre id="CSVout"><p>File contents will appear here</p></pre> -->
    <div style='height:calc(100% - 35px);display:-webkit-box;'>
        <div id="view_list" style="width:fit-content;height: calc(100% - 15px);overflow: scroll;border: 1px solid #369;">
            <table id="view_table" class="type03"  >
                <thead>
                    <th style="width:40px">No</th>
                    <th style="width:115px">品目コード</th>
                    <th style="width:100px">品名</th>
                    <th style="display:none">グループ</th>
                    <th style="width:210px">RFID</th>
                    <th style="width:220px">保管場所</th>
                    <th style="width:40px">数量</th>
                    <th style="display:none">スキャン場所</th>
                    <th style="display:none;">状態</th>
                    <th style="width:100px">棚卸日時</th>
                    <th style="width:100px">ユーザID</th>
                    <th style="width:100px">処理状態</th>
                </thead>
                <tbody id="view_content"></tbody>
            </table>
        </div>
        <label style="position: absolute;margin: -25px 0 0 10px;font-weight:bold;">計算</label>
        <div id="sum_area" style="width:fit-content;height: calc(100% - 15px);margin-left: 10px;overflow: scroll;border: 1px solid #369;">
            <table id="sum_table" class="type03"  >
                <thead>
                    <th style="width:40px">No</th>
                    <th style="width:115px">品目コード</th>
                    <th style="width:100px">品名</th>
                    <th style="width:50px">枚数</th>
                    <th style="width:50px">数量</th>
                    <th style="width:50px">RFID無<br>数量</th>
                    <th style="width:50px">合計</th>
                </thead>
                <tbody id="sum_content"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>

<div id="openAlert">
    <div id="msg" style="text-align: center;"></div>
</div>