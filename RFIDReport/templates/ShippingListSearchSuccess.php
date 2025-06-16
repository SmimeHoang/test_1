<?php
    use_javascript("jsQR.min.js");

    slot('h1', '<h1 style="margin:5px;font-size:110%;">出荷リストから製品検索 ｜ Nalux</h1>');
?>

<meta name="viewport" content="user-scalable=no">
<style type="text/css">
    
    .info-box{
        float:left;
        border: 1px solid blue;
        padding: 0 10px;
        max-width: 98%;
    }
    input,label{
        font-size:16px;
        font-weight:bold;
    }

    table.type03 {
        float:left;
        margin: 0 5px 10px 5px;
        font-size:14px;
        border-collapse: collapse;
        text-align: left;
        line-height: 1.5;
        border-top: 1px solid #ccc;
        border-left: 2px solid #ccc;
        table-layout: fixed;
    }
    table.type03 th {
        padding: 2px 4px;
        font-weight: bold;
        vertical-align: top;
        text-align:center;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
    }
    table.type03 td {
        padding: 2px 4px;
        vertical-align: top;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        max-width: 220px;
    }

    .ui-dialog-buttonset{
        width: 100%;
    }
    .ui-button-text-only .ui-button-text {
        padding: 0px 12px;
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
    }
    #realtime_data{
        padding: 5px
        border: 1px solid #ccc;
    }
    .one_steam{
        margin: 0 0 10px 0;
        display: flow-root;
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
    .next_data{
        float:left;
    }
    .arlet_btn{
        background-color:orange;
    }
    .color-box{
        position: absolute;
        /* margin:2px 0 0 0; */
        height:20px;
        width:20px;
        border:1px solid #000;
    }
</style>

<script type="text/javascript">
    var plant_name = localStorage.getItem('sPlantName');
    var plant_id = localStorage.getItem("sPlantVal");
    $(document).ready(function(){
        let QRd_w = $(window).width();
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

    });

    function getCustomer(){
        let check={};err = 0;msg="下の項目を入力してください。";
        // check["得意先コード"]=$("#customer").val();
        check["出荷日"]=$("#ship_time").val();
        $.each(check,function(key) {
            if($.trim(check[key])==""){
                msg +="<li style='color:red;'>"+key+"</li>\n";
                err++;
            }
        });
        if(err>0){
            openAlert(
                "確認",
                msg,
            );
            $("#realtime_data").html("");
            $("#complete_item").html("");
        }else{
            loadingView(true);
            $.ajax({
                type: "GET",
                url: "",
                data: {
                    ac:"getCustomer",
                    customer:$("#customer").val(),
                    moving_day:$("#ship_time").val(),
                    wica_documentno:$("#wica_documentno").val(),
                },
                dataType: "json",
                success: function (res) {
                    // console.log(res);
                    $("#realtime_data").html("");
                    $("#complete_item").html("");
                    $("#info_data").html("");
                    $("#cpl_tag_num").html("");
                    $("#cpl_item_num").html("");
                    $("#help_label").hide();
                    loadingView(false);
                    if(res=="NG"){
                        openAlert(
                            "確認",
                            "データがない！",
                        );
                    }else{
                        $("#wica_documentno").val(res[0].wica_documentno);
                        $("#wica_delivery").val(res[0].wica_delivery)
                        let body_cus = "";
                        $.each(res, function (a, b) { 
                            body_cus+=`<button id="cust_wica_`+b.hgpd_itemcode+`_`+b.wica_id+`" class="rt_btn cust_btn" onclick="getCompleteItem('`+b.wicr_ids+`')">`+b.hgpd_itemcode+`</button>`;
                        });
                        $("#realtime_data").html(body_cus);
                        $(".cust_btn").on("click",function(e){
                            $(".cust_btn").css({"background":""});
                            setTimeout(() => {
                                $("#"+e.target.id).css({"background":"turquoise"});
                            }, 0);
                        });
                    }
                }
            });
        }

    }

    function getCompleteItem(id){
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getCompleteItem",
                ship_id:id,
            },
            dataType: "json",
            success: function (res) {
                // console.log(res);
                let body_com = "";
                let help_flag = false;
                let item_all_num = 0;
                $.each(res, function (a, b) {
                    let short_rfid = b.wicr_rfid.substring(b.wicr_rfid.length-6);
                    if(parseInt(b.tray_num)*parseInt(b.tray_stok) == parseInt(b.snum) || parseInt(b.fpr_num) == parseInt(b.snum)){
                        body_com+=`<button id="info_`+b.wicr_rfid+`" class="rt_btn info_btn great_btn" onclick="getInfoData('`+b.rtl_id+`','`+b.wicr_rfid+`','`+b.moldet_undetected_load+`')" title="RFID: `+b.wicr_rfid+`, 実績ID: `+b.rtl_id+`, 管理ID:`+b.wicr_id+`" >...`+short_rfid+`</button>`;
                    }else{
                        help_flag=true;
                        body_com+=`<button id="info_`+b.wicr_rfid+`" class="rt_btn info_btn arlet_btn" onclick="getInfoData('`+b.rtl_id+`','`+b.wicr_rfid+`','`+b.moldet_undetected_load+`')" title="RFID: `+b.wicr_rfid+`, 実績ID: `+b.rtl_id+`" style="" >...`+short_rfid+`</button>`;
                    }
                    item_all_num+=parseInt(b.snum);
                });
                $("#complete_item").html(body_com);
                $("#cpl_tag_num").html("件数: <b>"+res.length+"</b>");
                $("#cpl_item_num").html("数量: <b>"+item_all_num+"</b>");
                if(help_flag){
                    $("#help_label").show();
                }else{
                    $("#help_label").hide();
                }
                $(".info_btn").on("click",function(e){
                    $(".great_btn").css({"background":""});
                    $(".arlet_btn").css({"background":"orange"});
                    setTimeout(() => {
                        $("#"+e.target.id).css({"background":"turquoise"});
                    }, 0);
                });
            }
        });
    }

    function getInfoData(id,rfid,moldet_undetected_load){
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getInfoData",
                last_id:id,
                rfid:rfid,
                moldet_undetected_load:moldet_undetected_load
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                // console.log(res);
                if(res.cpl_process.length==0){
                    openAlert(
                        "確認",
                        "データがありません。",
                    );
                }else{
                    let item_info= "<div class='info-box'>";
                    item_info+=`<div class="fl-left" style="padding:5px;">`;
                    item_info+=`完成ID：<label class="info_lb" >`+rfid+`</label>`;
                    if(res.cpl_process[0].searchtag!=""){
                        item_info+=`品名：<label class="info_lb" >`+res.cpl_process[0].searchtag+`</label>`;
                    }else{
                        item_info+=`品名：<label class="info_lb" >`+res.cpl_process[0].itemname+`</label>`;
                    }
                    item_info+=`品目：<label class="info_lb" >`+res.cpl_process[0].wic_itemcode+`</label>`;
                    // history+=`<p><label>ID：`+b[0].date+`</label></p>`;
                    
                    let history = ""
                    var sum_cpl_num = 0;
                    let view_work_time = "生産時間";
                    $(res["cpl_process"]).each(function (k, v) {
                        sum_cpl_num+=parseInt(v.wic_qty_in);
                        history+=`<div class="one_steam">`;
                        if(v.wic_remark!=="完成品処理ID無混合"){
                            $.each(res.device[v["wic_id"]], function (a, b) {
                                history+=`<table class="type03">`;
                                history+=`<thead><tr><th colspan='3' class='device' >`+b.hgpd_process+`</th></tr></thead>`;
                                history+=`<tbody>
                                    <tr><td class="chil-td">実績ID</td><td colspan="2">`+b.hgpd_id+`</td></tr>
                                    <tr><td class="chil-td">仕掛ID</td><td colspan="2">`+b.hgpd_rfid+`</td></tr>`;
                                    if(b.hgpd_process.indexOf("成形")==-1){
                                        history+=`<tr><td>作業日</td><td colspan="2">`+b.hgpd_checkday+`</td></tr>`;
                                    }else{
                                        history+=`<tr><td class="chil-td">成形日</td><td colspan="2">`+b.hgpd_moldday+`</td></tr>`;
                                    }
                                    history+=`<tr><td class="chil-td">型番</td><td colspan="2">`+b.hgpd_itemform+`</td></tr>
                                    <tr><td class="chil-td">キャビ</td><td colspan="2">`+b.hgpd_cav+`</td></tr>
                                    <tr><td class="chil-td">受け数</td><td colspan="2">`+b.hgpd_quantity+`</td></tr>
                                    <tr><td class="chil-td">良品数</td><td class="used_num" colspan="2">`+b.hgpd_qtycomplete+`</td></tr>
                                    <tr><td class="chil-td">不良数</td><td colspan="2">`+b.hgpd_difactive+`</td></tr>`;
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
                                history+=`</tbody></table>`;
                                history+=`<label class="next_data"><span class="ui-icon ui-icon-arrowthick-1-e"></span></label>`;
                            });
                        }
                        history+=`<table class="type03">`;
                        history+=`<thead><tr><th colspan='2' class='complete' >`+v.wic_process+`</th></tr></thead>`;
                        history+=`<tbody>
                            <tr>
                                <td>実績ID</td>`;
                                if(v.hgpd_before_id==0){
                                    history+=`<td>完成品処理ID無混合</td>`;
                                }else{
                                    history+=`<td>`+v.wic_hgpd_id+`</td>`;
                                }
                                
                            history+=`</tr>
                            <tr>
                                <td>完成ID</td>
                                <td>`+v.wic_rfid+`</td>
                            </tr>
                            <tr>
                                <td>紐付数</td>
                                <td class="used_num" >`+v.wic_qty_in+`</td>
                            </tr>
                            <tr>
                                <td>型番</td>
                                <td>`+v.wic_itemform+`</td>
                            </tr>
                            <tr>
                                <td>キャビ</td>
                                <td>`+v.wic_itemcav+`</td>
                            </tr>
                            <tr>
                                <td>担当者</td>
                                <td>`+v.wic_name+`</td>
                            </tr>
                            <tr>
                                <td>日付</td>
                                <td>`+v.wic_created_at+`</td>
                            </tr>
                        </tbody>`;
                        history+=`</table>`;

                        history+=`</div>`;
                    });

                    item_info+=`数量：<label class="info_lb" >`+sum_cpl_num+`</label>`;
                    item_info+=`</div>`;
                    item_info+=`<div style="clear: both;"></div>`;
                    
                    $("#info_data").html(item_info+history);
                    let mt_num = parseInt(res.cpl_process[0].tray_num)*parseInt(res.cpl_process[0].tray_stok);
                    if(sum_cpl_num!=mt_num){

                    }
                }
            }
        });
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

    function openStopsmall(ids){
        let url="/LotManagement/StopSmallList?ids="+ids;
        window.open(url);
    }

</script>

<div id="main_search" style="width:auto;padding: 10px 10px 0 10px;">
    <p>
        <label style="float:left;margin-left:10px;">得意先</label><input id="customer" class="" type=" text" style="float:left;margin-left:23px;margin-right:15px;width:200px;" ></input>
        <label style="float:left;margin-left:10px;">出荷日</label><input id="ship_time" class="check_ip" type="date" style="float:left;margin-right:15px;margin-left:5px;" ></input>
        <button id="btn-search" type="button" onclick="getCustomer();" class="" style="float:left;">検索</button>
    </p>
    <div style="clear:both;"></div>
    <p style="margin-top:5px;">
        <label style="margin-left: 10px;">伝票番号</label><input id="wica_documentno" class="check_ip" style="width:200px;margin-left:5px;" ></input>
        <label style="margin-left: 18px;">出荷先</label><input id="wica_delivery" class="check_ip" style="width:calc(100% - 550px);margin-left:5px;" ></input>
    </p>
    <hr style="margin:10px 0;">
</div>

<div style="clear:both;"></div>

<div id="main_info">
    <label>出荷リスト</label><label id="ship_date_view"></label>
    <div id="realtime_data" style="min-height:32px;"></div>
    <hr style="margin:10px 0;">
    <div style="clear:both;"></div>
    <label>完成RFID</label><label id="cpl_tag_num" style="margin-left:15px;font-weight:normal;"></label><label id="cpl_item_num" style="margin-left:15px;font-weight:normal;"></label><label id="help_label" style="margin-left:10px;display:none;"><span class="color-box" style="background:orange;"></span><span style="color:#000;margin:0 15px 0 26px;font-size:16px;font-weight:normal;">まるめ数合わせない</span></label>
    <div id="complete_item" style="min-height:32px;"></div>
    <hr style="margin:10px 0;">
    <div style="clear:both;"></div>
    <label>詳細</label>
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