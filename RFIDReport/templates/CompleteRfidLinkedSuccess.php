<?php
    use_javascript("jsQR.min.js");
    use_stylesheet("jquery.mobile-1.4.5.min.css");

    slot('h1', '<h1 style="margin:5px;font-size:110%;">RFIDの連携確認 ｜ Nalux</h1>');
?>

<meta name="viewport" content="width=device-width, user-scalable=no">

<style type="text/css">
    @media screen (max-width: 767px) { 
        html{
            width:768px;
        }
    }
    @media only screen and (min-width: 768px) and (max-width: 1205px) { 
        html{
            width:1350px;
        }
    }
    @media only screen and (min-width: 1206px) { 

    }
    .ui-dialog{background:#f9f9f9 !important;}
    #canvas {
        width: 100%;
        margin: auto;
    }
    #QRScan {
        width: 500px;
        margin: auto;
    }
    #info_data{
        max-width: calc(100% - 25px);
    }
    .info-box{
        float:left;
        border: 2px solid blue;
        padding: 10px;
        max-width: 100%;
        overflow-x:scroll;
        /* width: 100%; */
    }
    input,label{
        font-size:16px;
        font-weight:bold;
    }

    table.type03 {
        margin: 0 5px 10px 5px;
        font-size:14px;
        border-collapse: collapse;
        text-align: left;
        line-height: 1.5;
        border-top: 1px solid #ccc;
        border-left: 2px solid #ccc;
        table-layout: fixed;
        box-shadow: 2px 2px 5px 2px #e1e1e1;
    }
    table.type03 th {
        padding: 2px 4px;
        font-weight: bold;
        vertical-align: middle;
        text-align:center;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
    }
    table.type03 td {
        padding: 2px 4px;
        vertical-align: middle;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        max-width: 220px;
        min-width: 60px;
    }

    .ui-dialog-buttonset{
        width: 100%;
    }
    .ui-button-text-only .ui-button-text {
        padding: 4px 12px;
    }
    .btn-left .ui-button-text,.btn-right .ui-button-text {
        padding: 4px 10px;
    }
    .btn-left{
        float:left;
    }
    .btn-right{
        float:right;
    }
    #btn-entry .ui-button-text, #btn-clear .ui-button-text{
        padding:10px 20px;
    }
    .fl-left{
        float:left;
    }
    .item-box{
        float: left;
        padding: 4px;
    }
    .rt_btn{
        padding: 4px;
        margin: 2px;
        width: 100px;
    }
    #main_info{
        padding:0 10px;
        display: flex;
    }
    #realtime_data{
        padding: 5px
        border: 1px solid #ccc;
    }
    .one_steam{
        border-radius: 4px;
        margin: 5px 0 5px 0;
        display: flex;
        border: solid 1px #444;
        padding: 10px 5px 0 5px;
    }
    input, label {
        font-size: 18px;
        height: 22px;
        font-weight: bold;
    }
    .info_lb{
        margin-right:10px;
    }
    .device{
        background-color:#FFEE6C;
    }
    .complete{
        background-color:#00B0F0;
    }
    .used_num{
        font-weight: bold;
        color:darkgreen;
    }
    .ui-btn-icon-left::after{
        display: inline-block;
        width: 34px;
        height: 34px;
        margin: -17px 0 0 0px;
    }
    .chil-td{
        width: 56px;
    }
    .next_data{
        float:left;
        height: 30px;
        position: relative;
    }
    .hide_cycle_box{
        display:none;
    }

    .next-arr-proc.ui-btn-icon-left {
        padding: 2px;
    }
    .next-arr-proc.ui-btn-icon-left::after{
        margin:-17px 0 0 -12px;left:unset;top:unset;
    }

    .ui-btn-icon-left::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .next-arr.ui-btn-icon-left {
        padding: 4px;
    }
    .next-arr.ui-btn-icon-left::after{
        left: 0;
        margin:-17px 0 0 0;
    }
    .ui-alt-icon.ui-icon-arrow-r::after{
        background-size: 50%;
    }
    .ui-icon-camera::after{
        background-size: 70%;
    }
    .bad-num-view{
        font-weight:bold;
        color:orangered;
    }

    .processing_table{
        /* border: 1px solid #444; */
        padding: 0px 5px;
        border-radius: 4px;
        box-shadow: 2px 2px 8px 2px #ccc;
    }

    .fix-btn-unable .ui-button-text{
        padding: 0px 4px;
    }

    .change-page{
        cursor: pointer;
        margin: 0 10px;
        text-decoration-line:none;
        color:blue;
        font-size: 20px;
    }
    
    .change-page:hover{
        text-decoration-line:underline;
    }

</style>

<script type="text/javascript">
    var plant_name = localStorage.getItem('sPlantName');
    var plant_id = localStorage.getItem("sPlantVal");
    var mode_view="view_last_time";
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    $(document).ready(function(){
        let QRd_w = $(window).width();
        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: QRd_w/2,
            autoOpen: false,
            modal:true,
            position:["right",30],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });
        $( "#alert" ).dialog({
            autoOpen: false,
            width:QRd_w/2,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        $( "#alert2" ).dialog({
            autoOpen: false,
            width:QRd_w/2,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        $("#btn-search").button();
        $("button").button();

        let plant =  "<?=$sf_params->get("plant")?>";

        $("#rfid_input").on("keyup",function(e){
            $("#find_id").val("");
            if(e.keyCode == 13 && $("#rfid_input").val()!=""){
                window.history.pushState('', 'Title', '?plant='+plant+'&rfid='+$("#rfid_input").val());
                getInfoData();
            }
        });
        let rfid = "<?=$sf_params->get("rfid")?>";
        let find_id = "<?=$sf_params->get("find_id")?>";
        if(rfid!="" && find_id!=""){
            $("#rfid_input").val(rfid);
            $("#find_id").val(find_id);
            setTimeout(() => {
                getInfoData();
            }, 100);
        }

    });

    function getInfoData(mode,page_num){
        let rfid = $("#rfid_input").val();
        if(rfid.indexOf("NALUX")>-1){
            rfid="4E414C555800000000000000"+rfid.substr(5,8);
        }
        // console.log(rfid);
        let find_id = $("#find_id").val();
        let plant =  "<?=$sf_params->get("plant")?>";

        // console.log(find_id);
        window.history.pushState('', 'Title', '?plant='+plant);

        // let id_type = $("#rfid_type").val();
        if(mode==null){
            mode_view="view_last_time";
        }
        $("#info_data").html("");
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getInfoData",
                rfid:rfid,
                page_num:page_num,
                find_id:find_id
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                console.log(res);
                let msg = "完成ID:「"+rfid+"」";
                let id_type = res.id_type;
                if(id_type=="device_id"){
                    // 仕掛ID
                    msg = "仕掛ID:「"+rfid+"」";
                    if(res.info.length==0){
                        openAlert(
                            "確認",
                            msg+"のデータがありません。",
                        );
                    }else{
                        if(find_id){
                            mode_view="view_all_time";
                        }
                        let round_num = 0;
                        $.each(res.info.data, function (k, v) { 
                            let hide_not_find = "view_cycle_box";
                            if(find_id!=="" && find_id!=v[0].hgpd_id){
                                hide_not_find = "hide_cycle_box";
                            }
                            let item_info_h="";
                            let sum_cpl_num = 0;
                            item_info_h+=`<div class='info-box `+hide_not_find+`' style='float:left;margin: 0 10px 10px 0;border-radius: 8px;'>
                            <div class="fl-left" style="width: calc(100% + 10px);padding:5px;margin: -10px -10px 5px -10px;background:#d1eaff;border-radius: 8px 8px 0 0;">`;
                            item_info_h+=`号機：<label class="info_lb" >`+v[0].moldmachine+`</label>`;
                            item_info_h+=`RFID：<label class="info_lb" >`+rfid+`</label>`;
                            item_info_h+=`区分：<label class="info_lb">仕掛ID</label>`;
                            if(v[0].searchtag!=""){
                                item_info_h+=`品名：<label class="info_lb" >`+v[0].searchtag+`</label>`;
                            }else{
                                item_info_h+=`品名：<label class="info_lb" >`+v[0].itemname+`</label>`;
                            }
                            item_info_h+=`品目：<label class="info_lb" >`+v[0].wic_itemcode+`</label>`;
                            item_info_h+=`状態：<label class="info_lb" >`+res.status[k]+`</label>`;
                            item_info_h+=`回数：<label class="info_lb" >`+res.cycle[k]+`/`+res.all_cycle+`</label>`;
                            item_info_h+=`</div>`
                            item_info_h+=`<div style="clear:both;"></div>`;

                            let item_info= "<div class='one_steam'>";
                            $.each(v,function (a, b) {
                                let view_type = "仕掛"
                                let view_class = "device"
                                let view_in_num = "良品数"
                                let view_work_time = "生産時間";
                                if(b.wic_complete_flag=="1"){
                                    view_type = "完成"
                                    view_class = "complete"
                                    if(b.wic_remark.indexOf("保留処理")==-1){
                                        view_in_num = "紐付数"
                                    }
                                }
                                if(b.hgpd_process.indexOf("成形")>-1){
                                    item_info+=`<div style="clear:both;"></div>`;
                                }
                                // 途中処理の未表示条件
                                if(b.wic_process.indexOf("成形")==-1 || b.hgpd_remaining==0){
                                    item_info+=`<table class="type03">`;
                                    if(b.wic_remark=="端数寄せ"){
                                        item_info+=`<thead><tr><th colspan='3' class='`+view_class+`' >`+b.wic_remark+`</th></tr></thead>`;
                                        item_info+=`<tbody>`;
                                        item_info+=`<tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+b.wic_hgpd_id+`</td><td colspan="1" title="在庫管理ID">`+b.wic_id+`</td></tr>`;
                                        item_info+=`<tr><td class="chil-td">実績ID</td><td colspan="1">`+b.xwr_id+`</td><td style="text-align:center;" ><button class="" onclick=openFix('`+b.xwr_id+`','`+b.hgpd_wherhose+`') >修正</button></td></tr>`;
                                        let main_round_class = "主ID(+)";
                                        let sub_round_class = "副ID(-)";
                                        if(parseInt(b.wic_qty_in)>0){
                                            if(round_num==0){
                                                round_num = parseInt(b.hgpd_qtycomplete) + parseInt(b.wic_qty_in);
                                            }else{
                                                round_num = round_num + parseInt(b.wic_qty_in);
                                            }
                                        }else{
                                            main_round_class = "副ID(-)";
                                            sub_round_class = "主ID(+)";
                                            if(round_num==0){
                                                round_num = parseInt(b.hgpd_qtycomplete) - parseInt(b.wic_qty_out);
                                            }else{
                                                round_num = round_num - parseInt(b.wic_qty_out);
                                            }
                                        }
                                        if(b.wic_rfid.length>24){
                                            item_info+=`<tr><td class="chil-td">`+main_round_class+`</td><td colspan="2">NALUX`+b.wic_rfid.substr(b.wic_rfid.length-8,8)+`</td></tr>`;
                                        }else{
                                            item_info+=`<tr><td class="chil-td">`+main_round_class+`</td><td colspan="2">`+b.wic_rfid+`</td></tr>`;
                                        }
                                        if(b.round_rfid.length>24){
                                            item_info+=`<tr><td class="chil-td">`+sub_round_class+`</td><td colspan="2">NALUX`+b.round_rfid.substr(b.round_rfid.length-8,8)+`</td></tr>`;
                                        }else{
                                            item_info+=`<tr><td class="chil-td">`+sub_round_class+`</td><td colspan="2">`+b.round_rfid+`</td></tr>`;
                                        }
                                        item_info+=`<tr><td class="chil-td">作業日</td><td colspan="2">`+b.hgpd_checkday+`</td></tr>`;
                                        item_info+=`<tr><td class="chil-td">型番</td><td colspan="2">`+b.wic_itemform+`</td></tr>
                                        <tr><td class="chil-td">キャビ</td><td colspan="2">`+b.wic_itemcav+`</td></tr>`;
                                        if(parseInt(b.wic_qty_in)>0){
                                            item_info+=`<tr><td class="chil-td">端数寄せ</td><td class="used_num" colspan="2">+`+parseInt(b.wic_qty_in)+`</td></tr>`;
                                            item_info+=`<tr><td class="chil-td">残数</td><td class="used_num" colspan="2">`+round_num+`</td></tr>`;
                                        }else{
                                            item_info+=`<tr><td class="chil-td">端数寄せ</td><td class="used_num"  style="color:orange;" colspan="2">-`+parseInt(b.wic_qty_out)+`</td></tr>`;
                                            item_info+=`<tr><td class="chil-td">残数</td><td class="used_num" colspan="2">`+round_num+`</td></tr>`;
                                        }
                                        item_info+=`<tr><td class="chil-td">担当者</td><td colspan="2">`+b.wic_name+`</td></tr>`;
                                        item_info+=`<tr><td class="chil-td">作業時</td><td colspan="2">`+b.wic_created_at+`</td></tr>`;
                                        item_info+=`</tbody>`;
                                    }else{
                                        if(b.hgpd_process.indexOf("保留処理")>-1){
                                            if(b.wic_remark.indexOf("廃棄")>-1){
                                                item_info+=`<thead><tr><th colspan='3' class='`+view_class+`' >廃棄</th></tr></thead>`;
                                            }else{
                                                item_info+=`<thead><tr><th colspan='3' class='`+view_class+`' >保留処理</th></tr></thead>`;
                                            }

                                            item_info+=`<tbody>
                                            <tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+b.wic_hgpd_id+`</td><td colspan="1" title="在庫管理ID">`+b.wic_id+`</td></tr>`;
                                            if(b.wic_hgpd_id=="0"){
                                                item_info+=`<tr><td class="chil-td">実績ID</td><td colspan="2">完成品処理ID無混合</td></tr>`;
                                            }else{
                                                item_info+=`<tr><td class="chil-td">実績ID</td><td colspan="1">`+b.xwr_id+`</td><td style="text-align:center;" ><button class="" onclick=openFix('`+b.xwr_id+`','`+b.hgpd_wherhose+`') >修正</button></td></tr>`;
                                            }
                                            if(b.wic_rfid.length>24){
                                                item_info+=`<tr><td class="chil-td">`+view_type+`ID</td><td colspan="2">NALUX`+b.wic_rfid.substr(b.wic_rfid.length-8,8)+`</td></tr>`;
                                            }else{
                                                item_info+=`<tr><td class="chil-td">`+view_type+`ID</td><td colspan="2">`+b.wic_rfid+`</td></tr>`;
                                            }

                                            item_info+=`<tr><td class="chil-td">作業日</td><td colspan="2">`+b.hgpd_checkday+`</td></tr>`;
                                            item_info+=`<tr><td class="chil-td">型番</td><td colspan="2">`+b.wic_itemform+`</td></tr>
                                            <tr><td class="chil-td">キャビ</td><td colspan="2">`+b.wic_itemcav+`</td></tr>`;

                                            item_info+=`<tr><td class="chil-td">受け数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>`;
                                    
                                            item_info+=`<tr><td class="chil-td">良品数</td><td class="used_num" colspan="2">`+b.wic_qty_in+`</td></tr>`;

                                            let bad_view = "";
                                            if(b.hgpd_difactive>0){
                                                bad_view = "bad-num-view";
                                                item_info+=`<tr><td class="chil-td">不良数</td><td class="`+bad_view+`" colspan="2">`+b.hgpd_difactive+`</td></tr>`;
                                            }
                             
                                            if(b.hgpd_remaining>0){
                                                item_info+=`<tr><td class="chil-td">残数</td><td colspan="2">`+b.hgpd_remaining+`</td></tr>`;
                                            }
                                            if((b.hgpd_def!=null && b.hgpd_def !="")){
                                                item_info+=`<tr><td class="chil-td">不良内容</td><td colspan="2">`+b.hgpd_def+`</td></tr>`;
                                            }
                                            // item_info+=`<tr><td class="chil-td">備考</td><td colspan="2">`+b.wic_remark+`</td></tr>`;
                                            item_info+=`<tr><td class="chil-td">担当者</td><td colspan="2">`+b.wic_name+`</td></tr>`;
                                            if(b.wic_process!="完成品処理" || b.wic_remark.indexOf("保留処理")>-1){
                                                item_info+=`<tr><td class="chil-td">開始時</td><td colspan="2">`+b.hgpd_start_at+`</td></tr>
                                                <tr><td class="chil-td">終了時</td><td colspan="2">`+b.hgpd_stop_at+`</td></tr>`;
                                            }else{
                                                item_info+=`<tr><td class="chil-td">作業時</td><td colspan="2">`+b.wic_created_at+`</td></tr>`;
                                            }

                                            if(b.hgpd_process.indexOf("成形")==-1){
                                                view_work_time="作業時間"
                                            }
                                            if(b.stopsmall){
                                                item_info+=`<tr><td class="chil-td">`+view_work_time+`</td><td>`+b.work_time+`</td><td style="text-align:center;"><button onclick=openStopsmall('`+b.stopsmall+`') >チョコ停確認</button></td></tr>`;
                                            }else if((b.work_time && b.wic_process!="完成品処理") || b.hgpd_process=="保留処理"){
                                                item_info+=`<tr><td class="chil-td">`+view_work_time+`</td><td colspan="2">`+b.work_time+`</td></tr>`;
                                            }
                                            item_info+=`</tbody>`;

                                        }else{
                                            item_info+=`<thead><tr><th colspan='3' class='`+view_class+`' >`+b.wic_process+`</th></tr></thead>`;

                                                item_info+=`<tbody>
                                                <tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+b.wic_hgpd_id+`</td><td colspan="1" title="在庫管理ID">`+b.wic_id+`</td></tr>`;
                                                if(b.wic_hgpd_id=="0"){
                                                    item_info+=`<tr><td class="chil-td">実績ID</td><td colspan="2">完成品処理ID無混合</td></tr>`;
                                                }else{
                                                    item_info+=`<tr><td class="chil-td">実績ID</td><td colspan="1">`+b.xwr_id+`</td><td style="text-align:center;" ><button class="" onclick=openFix('`+b.xwr_id+`','`+b.hgpd_wherhose+`') >修正</button></td></tr>`;
                                                }
                                                if(b.wic_rfid.length>24){
                                                    item_info+=`<tr><td class="chil-td">`+view_type+`ID</td><td colspan="2">NALUX`+b.wic_rfid.substr(b.wic_rfid.length-8,8)+`</td></tr>`;
                                                }else{
                                                    item_info+=`<tr><td class="chil-td">`+view_type+`ID</td><td colspan="2">`+b.wic_rfid+`</td></tr>`;
                                                }
                                                if(b.wic_process.indexOf("成形")>-1){
                                                    item_info+=`<tr><td class="chil-td">成形日</td><td colspan="2">`+b.hgpd_moldday+`</td></tr>`;
                                                }else{
                                                    item_info+=`<tr><td class="chil-td">作業日</td><td colspan="2">`+b.hgpd_checkday+`</td></tr>`;
                                                }
                                                item_info+=`<tr><td class="chil-td">型番</td><td colspan="2">`+b.wic_itemform+`</td></tr>
                                                <tr><td class="chil-td">キャビ</td><td colspan="2">`+b.wic_itemcav+`</td></tr>`;
                                                if(b.wic_complete_flag!="1"){
                                                    if(b.wic_process.indexOf("成形")>-1){
                                                        item_info+=`<tr><td class="chil-td">生産数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>`;
                                                    }else{
                                                        item_info+=`<tr><td class="chil-td">受け数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>`;
                                                    }
                                                }else if(b.hgpd_process.indexOf("保留処理")>-1){
                                                    item_info+=`<tr><td class="chil-td">受け数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>`;
                                                }

                                                if(b.wic_complete_flag==0){
                                                    item_info+=`<tr><td class="chil-td">`+view_in_num+`</td><td class="used_num" colspan="2">`+b.hgpd_qtycomplete+`</td></tr>`;
                                                }else{
                                                    item_info+=`<tr><td class="chil-td">`+view_in_num+`</td><td class="used_num" colspan="2">`+b.wic_qty_in+`</td></tr>`;
                                                }

                                                let bad_view = "";
                                                if(b.hgpd_difactive>0){
                                                    bad_view = "bad-num-view";
                                                }
                                                if(b.wic_complete_flag!="1" || (b.hgpd_difactive>0 && b.wic_remark.indexOf("保留処理")==-1)){
                                                    item_info+=`<tr><td class="chil-td">不良数</td><td class="`+bad_view+`" colspan="2">`+b.hgpd_difactive+`</td></tr>`;
                                                }
                                                if(b.hgpd_remaining>0){
                                                    item_info+=`<tr><td class="chil-td">残数</td><td colspan="2">`+b.hgpd_remaining+`</td></tr>`;
                                                }
                                                if((b.wic_process!="完成品処理" && b.hgpd_def!=null && b.hgpd_def !="") || (b.hgpd_difactive>0 && b.wic_remark.indexOf("保留処理")==-1)){
                                                    item_info+=`<tr><td class="chil-td">不良内容</td><td colspan="2">`+b.hgpd_def+`</td></tr>`;
                                                }
                                                // item_info+=`<tr><td class="chil-td">備考</td><td colspan="2">`+b.wic_remark+`</td></tr>`;
                                                item_info+=`<tr><td class="chil-td">担当者</td><td colspan="2">`+b.wic_name+`</td></tr>`;
                                                if(b.wic_process!="完成品処理" || b.wic_remark.indexOf("保留処理")>-1){
                                                    item_info+=`<tr><td class="chil-td">開始時</td><td colspan="2">`+b.hgpd_start_at+`</td></tr>
                                                    <tr><td class="chil-td">終了時</td><td colspan="2">`+b.hgpd_stop_at+`</td></tr>`;
                                                }else{
                                                    item_info+=`<tr><td class="chil-td">作業時</td><td colspan="2">`+b.wic_created_at+`</td></tr>`;
                                                }


                                                if(b.hgpd_process.indexOf("成形")==-1){
                                                    view_work_time="作業時間"
                                                }
                                                if(b.stopsmall){
                                                    item_info+=`<tr><td class="chil-td">`+view_work_time+`</td><td>`+b.work_time+`</td><td style="text-align:center;"><button onclick=openStopsmall('`+b.stopsmall+`') >チョコ停確認</button></td></tr>`;
                                                }else if((b.work_time && b.wic_process!="完成品処理") || b.hgpd_process=="保留処理"){
                                                    item_info+=`<tr><td class="chil-td">`+view_work_time+`</td><td colspan="2">`+b.work_time+`</td></tr>`;
                                                }
                                            item_info+=`</tbody>`;
                                        }
                        
                                    }

                                    item_info+=`</table>`;
                                    if(a<v.length-1){
                                        item_info+=`<span class="next_data"><span class="next-arr-proc ui-alt-icon ui-btn-inline ui-icon-arrow-r ui-btn-icon-left"></span></span>`;
                                    }
                                }
                            });
                            item_info+= "</div></div></div>";

                            $("#info_data").append(item_info_h+item_info);
                            if(mode_view=="view_last_time"){
                                if(res.info.data.length>1){
                                    $("#info_data").append("<div style='clear: both;'></div><button id='btn-more' type='button' onclick='changeViewMode();' class='' style=''>過去データ</button>"); 
                                    $("#btn-more").button();
                                }
                                return false;
                            }
                        });
                    
                        if(find_id){
                            $("#info_data").append("<div style='clear: both;'></div><button id='btn-more' type='button' onclick='changeViewMode();' class='' style=''>過去データ</button>"); 
                            $("#btn-more").button();
                        }
                        
                        if(mode_view=="view_all_time" && !find_id){
                            let page_change="<div style='clear: both;'><div style='height:30px;'><label>ページ：</label>";
                            for(pn=0;pn<res.page;pn++){
                                if(res.now_page==pn){
                                    page_change+="<label class='change-page' style='color:#019f37;text-decoration-line:underline;' onclick='toPage(`"+pn+"`);'>"+(pn+1)+"</label>";
                                }else{
                                    page_change+="<label class='change-page' onclick='toPage(`"+pn+"`);'>"+(pn+1)+"</label>";
                                }
                            }
                            $("#info_data").append(page_change);
                        }

                    }
                }else if(id_type=="complete_id"){
                    // 完成ID
                    if(res.cpl_item.length==0 && res.cpl_item_all.length==0){
                        openAlert(
                            "確認",
                            msg+"のデータがありません。",
                        );
                    }else{
                        if(find_id){
                            mode_view="view_all_time";
                        }
                        let item_cpl = res.cpl_item;
                        let item_device = res.device;
                        let view_work_time = "生産時間";

                        if(mode_view=="view_all_time"){
                            item_cpl = res.cpl_item_all;
                            item_device = res.device_all;
                        }
                        // console.log(item_cpl)
                        // console.log(item_device)
                        let count_cpl = 0;
                        $.each(item_cpl,function(key,val){
                            let hide_not_find = "view_cycle_box";
                            if(find_id!=="" && find_id!=val[0].wic_complete_id){
                                hide_not_find = "hide_cycle_box";
                            }
                            let fix_btn_class = "fix-btn";
                            if(res.status=="出荷リスト紐付済み" || count_cpl>0){
                                fix_btn_class = "fix-btn-unable";
                            }
                            // console.log(val);
                            let item_info= "<div class='info-box "+hide_not_find+"' style='float:left;margin: 0 10px 10px 0;border-radius:8px;'>";
                            item_info+=`<div class="fl-left" style="width: calc(100% + 10px);padding:5px;margin: -10px -10px 5px -10px;background:#d1eaff;border-radius: 8px 8px 0 0;">`;
                            item_info+=`RFID：<label class="info_lb" >`+rfid+`</label>`;
                            item_info+=`区分：<label class="info_lb" >完成ID</label>`;
                            if(val[0].searchtag!=""){
                                item_info+=`品名：<label class="info_lb" >`+val[0].searchtag+`</label>`;
                            }else{
                                item_info+=`品名：<label class="info_lb" >`+val[0].itemname+`</label>`;
                            }
                            item_info+=`品目：<label class="info_lb" >`+val[0].wic_itemcode+`</label>`;
                            let history = "";
                            history+= "<div class='processing_table' style='float:left;'>";
                 
                            let sum_cpl_num = 0;
                            $.each(val,function (k, v) {
                                let pr_class = "device";
                                if(v.wic_process=="完成品処理"){
                                    pr_class = "complete";
                                }
                                let history1 = "";
                                let history2 = "";
                                sum_cpl_num+=parseInt(v.wic_qty_in);
                                history+=`<div class="one_steam">`;
                                let round_num = 0;
                                if(item_device[v["wic_id"]]){

                                    $.each(item_device[v["wic_id"]],function (a, b) {
                                        let view_class = "device";
                                        if(b.wic_complete_flag=="1" && b.hgpd_process.indexOf("成形")==-1){
                                            view_class = "complete"
                                        }
                                        // console.log(b);
                                        history+=`<table class="type03">`;

                                        if(b.wic_remark=="端数寄せ"){
                                            let main_round_class = "主ID(+)";
                                            let sub_round_class = "副ID(-)";
                                            if(parseInt(b.wic_qty_in)>0){
                                                if(round_num==0){
                                                    round_num = parseInt(b.hgpd_qtycomplete) + parseInt(b.wic_qty_in);
                                                }else{
                                                    round_num = round_num + parseInt(b.wic_qty_in);
                                                }
                                            }else{
                                                main_round_class = "副ID(-)";
                                                sub_round_class = "主ID(+)";
                                                if(round_num==0){
                                                    round_num = parseInt(b.hgpd_qtycomplete) - parseInt(b.wic_qty_out);
                                                }else{
                                                    round_num = round_num - parseInt(b.wic_qty_out);
                                                }
                                            }

                                            history+=`<thead><tr><th colspan='3' class='device' >`+b.wic_remark+`</th></tr></thead>`;
                                            history+=`<tbody>`;
                                            history+=`<tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+b.wic_hgpd_id+`</td><td colspan="1" title="在庫管理ID">`+b.wic_id+`</td></tr>`;
                                            if(b.xwr_id=="0"){
                                                history+=`<tr><td class="chil-td">実績ID</td><td colspan="2">未登録</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">実績ID</td><td colspan="1">`+b.xwr_id+`</td><td style="text-align:center;" ><button class="`+fix_btn_class+`" onclick=openFix('`+b.xwr_id+`','`+b.hgpd_wherhose+`') >修正</button></td></tr>`;
                                            }
                                            if(b.wic_rfid.length>24){
                                                history+=`<tr><td class="chil-td">`+main_round_class+`</td><td colspan="2">NALUX`+b.wic_rfid.substr(b.wic_rfid.length-8,8)+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">`+main_round_class+`</td><td colspan="2">`+b.wic_rfid+`</td></tr>`;
                                            }
                                            if(b.round_rfid.length>24){
                                                history+=`<tr><td class="chil-td">`+sub_round_class+`</td><td colspan="2">NALUX`+b.round_rfid.substr(b.round_rfid.length-8,8)+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">`+sub_round_class+`</td><td colspan="2">`+b.round_rfid+`</td></tr>`;
                                            }
                                            history+=`<tr><td class="chil-td">作業日</td><td colspan="2">`+b.hgpd_checkday+`</td></tr>`;
                                            history+=`<tr><td class="chil-td">型番</td><td colspan="2">`+b.wic_itemform+`</td></tr>
                                            <tr><td class="chil-td">キャビ</td><td colspan="2">`+b.wic_itemcav+`</td></tr>`;
                                            if(parseInt(b.wic_qty_in)>0){
                                                history+=`<tr><td class="chil-td">端数寄せ</td><td class="used_num" colspan="2">+`+parseInt(b.wic_qty_in)+`</td></tr>`;
                                                history+=`<tr><td class="chil-td">残数</td><td class="used_num" colspan="2">`+round_num+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">端数寄せ</td><td class="used_num" colspan="2" style="color:orange;">-`+parseInt(b.wic_qty_out)+`</td></tr>`;
                                                history+=`<tr><td class="chil-td">残数</td><td class="used_num" colspan="2">`+round_num+`</td></tr>`;
                                            }
                                            history+=`<tr><td class="chil-td">担当者</td><td colspan="2">`+b.wic_name+`</td></tr>`;
                                            history+=`<tr><td class="chil-td">作業時</td><td colspan="2">`+b.wic_created_at+`</td></tr>`;
                                            history+=`</tbody>`;
                                        }else{
                                            history+=`<thead><tr><th colspan='3' class='`+view_class+`' >`+b.hgpd_process+`</th></tr></thead>`;
                                            history+=`<tbody>`;
                                            history+=`<tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+b.wic_hgpd_id+`</td><td colspan="1" title="在庫管理ID">`+b.wic_id+`</td></tr>`;
                                            if(b.xwr_id=="0"){
                                                history+=`<tr><td class="chil-td">実績ID</td><td colspan="2">未登録</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">実績ID</td><td colspan="1">`+b.xwr_id+`</td><td style="text-align:center;" ><button class="`+fix_btn_class+`" onclick=openFix('`+b.xwr_id+`','`+b.hgpd_wherhose+`') >修正</button></td></tr>`;
                                            }
                                            if(b.hgpd_rfid.length>24){
                                                history+=`<tr><td class="chil-td">仕掛ID</td><td colspan="2">NALUX`+b.hgpd_rfid.substr(b.hgpd_rfid.length-8,8)+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">仕掛ID</td><td colspan="2">`+b.hgpd_rfid+`</td></tr>`;
                                            }
                                            if(b.hgpd_process.indexOf("成形")==-1){
                                                history+=`<tr><td>作業日</td><td colspan="2">`+b.hgpd_checkday+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">成形日</td><td colspan="2">`+b.hgpd_moldday+`</td></tr>`;
                                            }
                                            history+=`<tr><td class="chil-td">型番</td><td colspan="2">`+b.hgpd_itemform+`</td></tr>
                                            <tr><td class="chil-td">キャビ</td><td colspan="2">`+b.hgpd_cav+`</td></tr>`;
                                            if(b.hgpd_process.indexOf("成形")>-1){
                                                history+=`<tr><td class="chil-td">生産数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">受け数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>`;
                                            }
                                            history+=`<tr><td class="chil-td">良品数</td><td class="used_num" colspan="2">`+b.hgpd_qtycomplete+`</td></tr>`;

                                            let bad_view = "";
                                            if(b.hgpd_difactive>0){
                                                bad_view = "bad-num-view";
                                            }
                                            history+=`<tr><td class="chil-td">不良数</td><td class="`+bad_view+`" colspan="2">`+b.hgpd_difactive+`</td></tr>`;
                                            if(b.hgpd_remaining>0){
                                                history+=`<tr><td class="chil-td">残数</td><td colspan="2">`+b.hgpd_remaining+`</td></tr>`;
                                            }
                                            if(b.hgpd_def!=null && b.hgpd_def !=""){
                                                history+=`<tr><td class="chil-td">不良内容</td><td colspan="2">`+b.hgpd_def+`</td></tr>`;
                                            }
                                            history+=`<tr><td class="chil-td">担当者</td><td colspan="2">`+b.hgpd_name+`</td></tr>`;
                                            if(b.wic_process!="完成品処理"){
                                                history+=`<tr><td class="chil-td">開始時</td><td colspan="2">`+b.hgpd_start_at+`</td></tr>
                                                <tr><td class="chil-td">終了時</td><td colspan="2">`+b.hgpd_stop_at+`</td></tr>`;
                                            }else{
                                                history+=`<tr><td class="chil-td">紐付時</td><td colspan="2">`+b.wic_created_at+`</td></tr>`;
                                            }

                                            if(b.hgpd_process.indexOf("成形")==-1){
                                                view_work_time="作業時間"
                                            }
                                            if(b.stopsmall){
                                                history+=`<tr><td class="chil-td">`+view_work_time+`</td><td>`+b.work_time+`</td><td style="text-align:center;"><button onclick=openStopsmall('`+b.stopsmall+`') >チョコ停確認</button></td></tr>`;
                                            }else if(b.work_time && b.wic_process!="完成品処理"){
                                                history+=`<tr><td class="chil-td">`+view_work_time+`</td><td colspan="2">`+b.work_time+`</td></tr>`;
                                            }
                                            history+=`</tbody>`;
                                        }
                                        history+=`</table>`;
                                        history+=`<span class="next_data"><span class="next-arr-proc ui-alt-icon ui-btn-inline ui-icon-arrow-r ui-btn-icon-left" style=""></span></span>`;
                                    });

                                }

                                history+=`<table class="type03" style="" >`;
                                history+=`<thead><tr><th colspan='3' class='complete' >`+v.wic_process+`</th></tr></thead>`;
                                // <tr><td>在庫ID</td><td colspan="2">`+v.wic_id+`</td></tr>
                                history+=`<tbody>`;
                                    history+=`<tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+v.wic_hgpd_id+`</td><td colspan="1" title="在庫管理ID">`+v.wic_id+`</td></tr>`;
                                    if(v.xwr_id=="0"){
                                        history+=`<tr><td class="chil-td">実績ID</td><td colspan="2">未登録</td></tr>`;
                                    }else{
                                        history+=`<tr><td class="chil-td">実績ID</td><td colspan="1">`+v.xwr_id+`</td><td style="text-align:center;" ><button class="`+fix_btn_class+`" onclick=openFix('`+v.xwr_id+`','`+v.hgpd_wherhose+`') >修正</button></td></tr>`;
                                    }
                                    if(v.wic_rfid.length>24){
                                        history+=`<tr><td>完成ID</td><td colspan="2">NALUX`+v.wic_rfid.substr(v.wic_rfid.length-8,8)+`</td></tr>`;
                                    }else{
                                        history+=`<tr><td>完成ID</td><td colspan="2">`+v.wic_rfid+`</td></tr>`;
                                    }
                                    history+=`<tr><td>型番</td><td colspan="2">`+v.wic_itemform+`</td></tr>
                                    <tr><td>キャビ</td><td colspan="2">`+v.wic_itemcav+`</td></tr>
                                    <tr><td>紐付数</td><td class="used_num" colspan="2">`+v.wic_qty_in+`</td></tr>`;
                                    if(v.wic_remark=="完成品処理ID無混合"){
                                        history+=`<tr><td>備考</td><td colspan="2">完成品処理ID無混合</td></tr>`;
                                    }
                                    history+=`<tr><td>担当者</td><td colspan="2">`+v.wic_name+`</td></tr>
                                    <tr><td class="chil-td">紐付時間</td><td colspan="2">`+v.wic_created_at+`</td></tr>
                                </tbody>`;
                                history+=`</table>`;
                                history+=`</div>`;
                            });
                            history+=`</div>`;
                            
                            //完成品からの処理------------------------------------------------------------------------------------
                            //出荷された
                            if(res.ship_all[count_cpl+res.now_page*res.max_one_page]){
                                let item_ship = res.ship_all[count_cpl+res.now_page*res.max_one_page][0]
                                // console.log(item_ship);
                                history+=`<span class="next_data vertical-set-middle-`+count_cpl+`" style="width: 34px;" ><span class="next-arr ui-alt-icon ui-btn-inline ui-icon-arrow-r ui-btn-icon-left"></span></span>`;
                                history+=`<table class="type03 shipped-tab-`+count_cpl+`" style="height: fit-content;">`;
                                if(item_ship.wic_remark=="廃棄"){
                                    history+=`<thead><tr><th colspan='3' style="background-color:orange;"  >廃棄</th></tr></thead>`;
                                }else{
                                    history+=`<thead><tr><th colspan='3' class='complete' >出荷</th></tr></thead>`;
                                }
                                history+=`<tbody>
                                <tr><td>在庫ID</td><td colspan="2">`+item_ship.wic_id+`</td></tr>`;
                                if(item_ship.wic_rfid.length>24){
                                    history+=`<tr><td>完成ID</td><td colspan="2">NALUX`+item_ship.wic_rfid.substr(item_ship.wic_rfid.length-8,8)+`</td></tr>`;
                                }else{
                                    history+=`<tr><td>完成ID</td><td colspan="2">`+item_ship.wic_rfid+`</td></tr>`;
                                }
                                history+=`<tr><td>型番</td><td colspan="2">`+item_ship.wic_itemform+`</td></tr>
                                <tr><td>キャビ</td><td colspan="2">`+item_ship.wic_itemcav+`</td></tr>`;
                                if(item_ship.wic_remark=="廃棄"){
                                    history+=`<tr><td>廃棄数</td><td class="bad-num-view" colspan="2">`+item_ship.wic_qty_out+`</td></tr>`;
                                    history+=`<tr><td>不良内容</td><td colspan="2">`+item_ship.hgpd_def+`</td></tr>`;
                                    history+=`<tr><td>担当者</td><td colspan="2">`+item_ship.wic_name+`</td></tr>
                                    <tr><td class="chil-td">作業日</td><td colspan="2">`+item_ship.wic_date+`</td></tr>
                                    </tbody>`;
                                }else{
                                    history+=`<tr><td>出荷数</td><td class="used_num" colspan="2">`+item_ship.wic_qty_out+`</td></tr>`;
                                    history+=`<tr><td>担当者</td><td colspan="2">`+item_ship.wic_name+`</td></tr>
                                    <tr><td class="chil-td">出荷日</td><td colspan="2">`+item_ship.wic_date+`</td></tr>
                                    </tbody>`;
                                }
                                history+=`</table>`;
                            }

                            //完成品の不具合処理
                            if(res.process_return[count_cpl+res.now_page*res.max_one_page]){
                                // console.log(res.process_return[count_cpl])
                                let item_collect = res.process_return[count_cpl+res.now_page*res.max_one_page].rt_time;
                                let next_item = res.process_return[count_cpl+res.now_page*res.max_one_page].next_process;
                                history+= "<div class='proc_return_table' style='float:left;'>";

                                if(item_collect){
                                    history+=`<span class="next_data vertical-set-middle-`+count_cpl+`" style="width: 34px;" ><span class="next-arr ui-alt-icon ui-btn-inline ui-icon-arrow-r ui-btn-icon-left"></span></span>`;
                                    history+=`<table class="type03 shipped-tab-`+count_cpl+`" style="height: fit-content;">`;
                                    history+=`<thead><tr><th colspan='3' class='' style="background-color:orange;" >保留処理</th></tr></thead>`;
                                    history+=`<tbody>
                                    <tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+item_collect.wic_complete_id+`</td><td colspan="1" title="在庫管理ID">`+item_collect.wic_id+`</td></tr>
                                    <tr><td>実績ID</td><td colspan="1">`+item_collect.xwr_id+`</td><td style="text-align:center;" ><button class="`+fix_btn_class+`" onclick=openFix('`+item_collect.xwr_id+`','`+item_collect.hgpd_wherhose+`') >修正</button></td></tr>`;
                                    if(item_collect.wic_rfid.length>24){
                                        history+=`<tr><td>完成ID</td><td colspan="2">NALUX`+item_collect.wic_rfid.substr(item_collect.wic_rfid.length-8,8)+`</td></tr>`;
                                    }else{
                                        history+=`<tr><td>完成ID</td><td colspan="2">`+item_collect.wic_rfid+`</td></tr>`;
                                    }
                                        history+=`<tr><td>型番</td><td colspan="2">`+item_collect.wic_itemform+`</td></tr>
                                        <tr><td>キャビ</td><td colspan="2">`+item_collect.wic_itemcav+`</td></tr>
                                        <tr><td>良品数</td><td class="used_num" colspan="2">`+item_collect.hgpd_qtycomplete+`</td></tr>
                                        <tr><td>不良数</td><td class="bad-num-view" colspan="2">`+item_collect.hgpd_difactive+`</td></tr>
                                        <tr><td>担当者</td><td colspan="2">`+item_collect.wic_name+`</td></tr>
                                        <tr><td class="chil-td">開始時</td><td colspan="2">`+item_collect.hgpd_start_at+`</td></tr>
                                        <tr><td class="chil-td">終了時</td><td colspan="2">`+item_collect.hgpd_stop_at+`</td></tr>
                                        <tr><td class="chil-td">作業日</td><td colspan="2">`+item_collect.wic_date+`</td></tr>
                                    </tbody>`;
                                    history+=`</table>`;
                                    history+= "</div>";
                                                         
                                    history+=`<span class="next_data vertical-set-middle-`+count_cpl+`" style="width: 34px;position: relative;" ><span class="next-arr ui-alt-icon ui-btn-inline ui-icon-arrow-r ui-btn-icon-left"></span></span>`;
                                    
                                    history+= "<div class='return-table-"+count_cpl+"' style='float:left;height: fit-content;'>";
                                }

                                if(next_item){
                                    $.each(next_item, function(sk,sv){
                                        history+=`<table class="type03" style="">`;
                                        //完成品の保留処理
                                        history+=`<thead><tr><th colspan='3' class='device' >再検査</th></tr></thead>`;
                                        history+=`<tbody>
                                        <tr><td class="chil-td">在庫ID</td><td colspan="1" title="レアルタイムID">`+sv.wic_complete_id+`</td><td colspan="1" title="在庫管理ID">`+sv.wic_id+`</td></tr>
                                        <tr><td>実績ID</td><td colspan="1">`+sv.xwr_id+`</td><td style="text-align:center;" ><button class="`+fix_btn_class+`" onclick=openFix('`+sv.xwr_id+`','`+sv.hgpd_wherhose+`') >修正</button></td></tr>`;
                                        if(sv.hgpd_rfid.length>24){
                                            history+=`<tr><td>仕掛ID</td><td colspan="2">NALUX`+sv.hgpd_rfid.substr(sv.hgpd_rfid.length-8,8)+`</td></tr>`;
                                        }else{
                                            history+=`<tr><td>仕掛ID</td><td colspan="2">`+sv.hgpd_rfid+`</td></tr>`;
                                        }
                                        history+=`<tr><td>型番</td><td colspan="2">`+sv.wic_itemform+`</td></tr>
                                            <tr><td>キャビ</td><td colspan="2">`+sv.wic_itemcav+`</td></tr>
                                            <tr><td>受け数</td><td colspan="2">`+sv.hgpd_quantity+`</td></tr>
                                            <tr><td>良品数</td><td class="used_num" colspan="2">`+sv.hgpd_qtycomplete+`</td></tr>
                                            <tr><td>不良数</td><td class="bad-num-view" colspan="2">`+sv.hgpd_difactive+`</td></tr>
                                            <tr><td>不良内容</td><td class="" colspan="2">`+sv.hgpd_def+`</td></tr>
                                            <tr><td>担当者</td><td colspan="2">`+sv.wic_name+`</td></tr>
                                            <tr><td class="chil-td">開始時</td><td colspan="2">`+sv.hgpd_start_at+`</td></tr>
                                            <tr><td class="chil-td">終了時</td><td colspan="2">`+sv.hgpd_stop_at+`</td></tr>
                                            <tr><td class="chil-td">作業時間</td><td colspan="2">`+sv.work_time+`</td></tr>
                                        </tbody>`;
                                        history+=`</table>`;
                                    })
                                }
                 
                                history+=`</div>`;
                            }

                            //出荷の終わりーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

                            item_info+=`数量：<label class="info_lb" >`+sum_cpl_num+`</label>`;
                            if(count_cpl==0){
                                item_info+=`状態：<label class="info_lb" >`+res.status+`</label>`;
                            }else if(res.ship_all[count_cpl+res.now_page*res.max_one_page]){
                                if(res.ship_all[count_cpl+res.now_page*res.max_one_page][0].hgpd_process=="保留処理"){
                                    item_info+=`状態：<label class="info_lb" >保留処理済み</label>`;
                                }else{
                                    item_info+=`状態：<label class="info_lb" >出荷リスト紐付済み</label>`;
                                }
                            }
                            item_info+=`回数：<label class="info_lb" >`+res.cycle[count_cpl]+`/`+res.all_cycle+`</label>`;
                            item_info+=`</div>`;
                            item_info+=`<div style="clear: both;"></div>`;
                            item_info+=`<div class="tag-area-`+count_cpl+`" style="display:flex">`;
                            history+=`</div>`;
                            
                            $("#info_data").append(item_info+history);
                            
                            $("#info_data").append('<div style="clear: both;"></div>');

                            // console.log("count: "+count_cpl+" , "+$(".tag-area-"+count_cpl).height())
                            let bw = $(".tag-area-"+count_cpl).height()/2;
                            let half_shiped_tab_height = $(".shipped-tab-"+count_cpl).height()/2;
                            let return_table_height = $(".return-table-"+count_cpl).height()/2;

                            $("#info_data .vertical-set-middle-"+count_cpl).css({"position":"relative","top":bw-11})
                            $("#info_data .shipped-tab-"+count_cpl).css({"position":"relative","top":bw-half_shiped_tab_height})
                            $("#info_data .return-table-"+count_cpl).css({"position":"relative","top":bw-return_table_height})

                            count_cpl++;
                        });

                        if(Object.keys(res.cpl_item_all).length>Object.keys(res.cpl_item).length){
                            if(mode_view=="view_last_time" || find_id){
                                $("#info_data").append("<div style='clear: both;'></div><button id='btn-more' type='button' onclick='changeViewMode();' class='' style=''>過去データ</button>"); 
                                $("#btn-more").button();
                            }
                        }
                        if(mode_view=="view_all_time" && !find_id){
                            let page_change="<div style='clear: both;'><div style='height:30px;'><label>ページ：</label>";
                            for(pn=0;pn<res.page;pn++){
                                if(res.now_page==pn){
                                    page_change+="<label class='change-page' style='color:#019f37;text-decoration-line:underline;' onclick='toPage(`"+pn+"`);'>"+(pn+1)+"</label>";
                                }else{
                                    page_change+="<label class='change-page' onclick='toPage(`"+pn+"`);'>"+(pn+1)+"</label>";
                                }
                            }
                            $("#info_data").append(page_change);
                        }
                    }
                }else{
                    openAlert(
                        "確認",
                        "RFID:「"+rfid+"」のデータがありません。",
                    );
                }
                // $(".fix-btn-unable").hide();
                $(".fix-btn-unable").button();
                $(".fix-btn-unable").button("disable");
            }
        });
    }

    function toPage(pg){
        getInfoData("view_all_time",pg);
    }

    function openStopsmall(ids){
        let url="/LotManagement/StopSmallList?ids="+ids;
        window.open(url);
    }

    function changeViewMode(){
        mode_view = "view_all_time";
        $("#find_id").val("");
        getInfoData(mode_view);
    }

    function openAlert(title,msg,btn,callback){
        if(!btn){
            btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
                return;
            }}];
        }
        var options = {
            "title":title,
            width: 600,
            buttons:btn
        };
        $("#message").html(msg);
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
        return false;
    }

    function openCam(){
        od("rfid_input","RFID");
    }

    function od(id,name,fcmode){
        loadingView(true);
        // $("#"+id).attr('readonly','readonly');
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:600 } }).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            // requestAnimationFrame(tick);
            tick(id);
        });

        let button = [ { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
            stop_scan();
            // $("#"+id).removeAttr('readonly');
            $("#"+id).select();
        }},{ class:"btn-right",html:"<span class='dialog_btn ui-icon-recycle ui-btn-icon-right'>カメラ切替</span>",click :function() {
            if(fcmode=="user"){
                fcmode="environment";
            }else{
                fcmode="user";
            }
            localStorage.setItem("sFcmode",fcmode);
            stop_scan();
            setTimeout(() => {
                od(id,name,fcmode);
            }, 100);
        }},{ class:"btn-left",text:"閉じる",click :function() {
            stop_scan();
        }}]

        var options = {"title":name+"をスキャンしてください。",
            width:$(window).width()*.55,
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        loadingView(false);
        $("#QRScan").dialog( "open" );
        // if($("#"+id).val()!==""){
        //     $("#"+id).select();
        // }
        // $("#"+id).focus();
    }

    const sleepy = ms => new Promise(resolve => setTimeout(resolve, ms));
    async function tick(id) {
        let camera_off=false;
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvasElement.hidden = false;
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });
            if (code) {
                if(code.data){
                    playMelody(2200);
                    drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                    drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                    drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                    drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                    $("#"+id).val(code.data);
                    // stop_scan();
                    setTimeout(() => {
                        $("#find_id").val("");
                        getInfoData();
                    }, 100);
                    await sleepy(300);
                    stop_scan();
                }
            }
        }
        if(camera_off===false){
            setTimeout(function(){
                if(video!==null){
                    tick(id);
                }else{
                    stop_scan();
                }
            },30);
        }
    }

    // 指定周波数音を出す
    let audioCtx = new AudioContext();
    const playMelody = (hz) => {
        let osc = audioCtx.createOscillator();
        osc.frequency.value = hz;
        let audDes = audioCtx.destination;
        osc.connect(audDes);
        osc.start = osc.start || osc.noteOn;
        osc.start();
        setTimeout(function() { osc.stop(0);}, 200);
    }

    function drawLine(begin, end, color) {
        canvas.beginPath();
        canvas.moveTo(begin.x, begin.y);
        canvas.lineTo(end.x, end.y);
        canvas.lineWidth = 4;
        canvas.strokeStyle = color;
        canvas.stroke();
    }

    function stop_scan(){
        canvasElement.hidden = true;
        if(video){
            video.pause();
            video.srcObject = null;
            video=null;
        }
        if(localStream){
            localStream.getTracks().forEach(function(track) {
                track.stop();
            });
            localStream = null;
        }
        $("#QRScan").dialog( "close" );
    }

    function openFix(xwr_id,this_plant){
        let url = "/LaborReport/Correction?plant="+this_plant+"&id="+xwr_id;
        window.open(url);
    }

</script>

<div id="main_search" style="width:auto;padding:0 0 10px 0;">
    <div style="clear:both;"></div>
    <p style="margin-top:5px;">
        <label style="margin-left: 10px;">RFID：</label><input id="rfid_input" class="check_ip" style="width:450px;margin:0 0 0 5px;height:26px;" ></input>
        <!-- <label style="margin-left: 10px;">区分：</label>
        <select id="rfid_type" style="margin:0 10px 0 0;height:30px;font-weight:bold;">
            <option value="complete_id" >完成ID</option>
            <option value="device_id" >仕掛ID</option>
        </select> -->
        <button id="btn-search" type="button" onclick="getInfoData();" class="" style="">検索</button>
        <button type="button" onclick="openCam();" class="btn_ditemset btn_topmenu" style="width:60px;"><span class="ui-icon-camera ui-btn-icon-left" ></span></button>
        <input id="find_id" class="" style="width:100px;margin:0 10px 0 0;height:26px;float:right;" ></input><label style="margin-top: 3px;float:right;">表示ID：</label>
    </p>
    <!-- <hr style="margin:10px 0;"> -->
</div>

<div style="clear:both;"></div>

<div id="main_info">
    <div style="clear:both;"></div>
    <div id="info_data" style="min-height:32px;float:left;"></div>
</div>

<div style="clear:both;"></div>

<div id="QRScan" style="padding:0;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>
<div id="alert2">
    <div id="message2" style="text-align: center;"></div>
</div>