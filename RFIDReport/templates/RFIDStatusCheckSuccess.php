<?php
    use_javascript("jsQR.min.js");
    use_stylesheet("jquery.mobile-1.4.5.min.css");

    slot('h1', '<h1 style="margin:5px;font-size:110%;">RFIDの状態確認 ｜ Nalux</h1>');
?>

<!-- <meta name="viewport" content="minimum-scale=0.8, user-scalable=no"> -->
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
    /* #QRScan {
        width: 500px;
        margin: auto;
    } */
    .info-box{
        float:left;
        border: 2px solid blue;
        padding: 10px;
        max-width: 98%;
    }
    input{
        font-size: 18px;
        height: 30px;
        font-weight: bold;
    }
    label {
        font-size: 20px;
        height: 30px;
        font-weight: bold;  
    }
    table.type03 {
        float:left;
        /* margin: 0 5px 0px 5px; */
        font-size:14px;
        border-collapse: collapse;
        text-align: center;
        line-height: 1.5;
        border-top: 1px solid #ccc;
        border-left: 2px solid blue;
        /* table-layout: fixed; */
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
        overflow: hidden;
        white-space: nowrap;
    }

    .ui-button-text-only .ui-button-text {
        padding: 4px 12px;
        font-size: 18px !important;
    }
    #user-list button{
        font-size: 18px !important;
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
        margin:0 10px;
        border: 1px solid #ccc;
        overflow: scroll;
    }
    #realtime_data{
        padding: 5px;
        border: 1px solid #ccc;
    }
    .one_steam{
        margin: 0 0 10px 0;
        display: flow-root;
        border: solid 1px #444;
        padding: 10px 5px 0 5px;
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
    }
    .ok_alert{
        background:greenyellow;
    }
    .ng_alert{
        background:yellow;
    }

    .re_work_alert{
        background:#00B0F0;
    }

    .none_alert{
        background:#e8e8e8;
    }

    .text_ng_alert, .text_none_alert{
        font-weight:bold;
        color:red;
    }
    .text_ok_alert, .text_re_work_alert{
        font-weight:bold;
    }

    .color-box{
        position: absolute;
        /* margin:2px 0 0 0; */
        height:16px;
        width:20px;
        border:1px solid #000;
    }

</style>

<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="" id="canvas" hidden></canvas>
    <div id="scan_msg" style="position:absolute;bottom:0;right:0;color:red;text-shadow: 1px 0 #fff, -1px 0 #fff, 0 1px #fff, 0 -1px #fff,1px 1px #fff, -1px -1px #fff, 1px -1px #fff, -1px 1px #fff;"></div>
</div>

<script type="text/javascript">
    var plant_name = localStorage.getItem('sPlantName');
    if(!plant_name){
        plant_name="<?=$sf_params->get("plant")?>";
        localStorage.setItem('sPlantName',plant_name);
    }
    var user = "<?=$sf_params->get("user")?>";
    var plant_id = localStorage.getItem("sPlantVal");
    var mode_view="view_last_time";
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "environment";
        localStorage.setItem("sFcmode",fcmode);
    }
    $(document).ready(function(){
        let QRd_w = $(window).width()*.5;
        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width:QRd_w,
            autoOpen: false,
            modal:true,
            position:["left",30],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });
        $( "#alert" ).dialog({
            autoOpen: false,
            width:QRd_w,
            modal:false,
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        $( "#alert2" ).dialog({
            autoOpen: false,
            width:QRd_w,
            modal:false,
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        $("#btn-search").button();
        $("button").button();

        $("#rfid_input").on("keyup",function(e){
            if(e.keyCode == 13 && $("#rfid_input").val()!=""){
                getInfoData();
            }
        });
        setTimeout(() => {
            $( "#dialog_user" ).dialog({
                autoOpen: false,
                width:$(window).width()-50,
                modal:true,
                position:["center", 100],
                buttons: [{ text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }}]
            });

            var plant = plant_name.substr(0,plant_name.length-2);
            var gr = "製造係_1班";
            if(plant=="山崎"){
                gr = "製造1係";
            }
            $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(plant)+"&gp2="+decodeURIComponent(gr)/*+"&callback=?"*/,function(data){
                $("#ap-user-select").html(data);
            });
            
            $( "#user" ).click(function( event ) {
                $( "#dialog_user" ).dialog( "open" );
                event.preventDefault();
            });
            $(".ui-dialog-titlebar-close").hide();
            if(user==""){
                // $( "#dialog_user" ).dialog( "open" );
            }else{
               $("#user").val(user);
            }
        }, 0);
        let searchParams = new URLSearchParams(window.location.search)
        let ids = searchParams.get('ids')
       
        console.log(ids)

        if(ids && ids!=""){
            $("#btn_entry").show();
            getInfoData(ids);
        }else{
            $("#btn_entry").hide();
        }
    });

    var RFID = "4E414C55580000000000000000000001";
    // 10文字取得
    var RFID_pre  = RFID.slice( 0, 10 );

    function t2enc(text) {
        let encText = "";
        for (let i = 0; i < 10; i++) {
            encText += text.charCodeAt(i).toString(16);
        }
        return encText;
    }

    function t2dec(encText) {
        let decText = "";
        for (let i = 0; i < 10; i += 2) {
            decText += String.fromCharCode(parseInt(encText.slice(i, i + 2), 16));
        }
        return decText+encText.slice(-8);
    }

    async function getInfoData(ids){
        console.log("get data");
        let mode = "view_only";
        if(ids){
            mode = "res_entry";
        }else{
            ids=$("#rfid_input").val();
        }
        ids=ids.split(",");
        if(mode=="res_entry"){
            $("#main_search").hide();
        }
        // let rfid = $("#rfid_input").val();
        // let id_type = $("#rfid_type").val();
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getIds",
                ids:ids,
                site:"<?=$sf_params->get("site")?>"
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                console.log(res);
                let id_type = res.id_type;
                $.each(res, function (k, v) { 
                    let id_type = v.id_type;
                    let msg ="";
                    let view_type="仕掛ID";
                    let tr_alert="ok_alert";
                    if(v.info.check_val=="NG") {
                        tr_alert="ng_alert";
                    }else{
                        if(v.status=='完成処理待ち'){
                            tr_alert="re_work_alert";
                        }
                    }
                    if(id_type=="device_id"){
                        // 仕掛ID
                    }else if(id_type=="complete_id"){
                        // 完成ID
                        view_type="完成ID";
                    }else{
                        // 未登録ID
                        view_type="未登録";
                        tr_alert="none_alert";
                    }
        
                    let row_num = get_table_val("checked_data",["row_num"]);
                        
                    let add_tr = "";

                    if(view_type=="未登録"){
                        add_tr+="<tr class='"+tr_alert+"'>";
                        add_tr+="<td>"+(row_num.length+1)+"</td>"            //No
                        add_tr+="<td class='text_"+tr_alert+"'>"+v.info.check_val+"</td>"              //結果
                        add_tr+="<td>"+view_type+"</td>"                     //区分
                        add_tr+="<td>0</td>"                                //回数
                        add_tr+="<td>未使用</td>"                            //状態
                        add_tr+="<td></td>"                                 //実績ID
                        add_tr+="<td></td>"                                 //在庫ID
                        add_tr+="<td style='display:none;'>"+v.info.wic_rfid+"</td>"              //RFID
                        if(v.info.wic_rfid.length>24){
                            add_tr+="<td>"+t2dec(v.info.wic_rfid)+"</td>"       //RFIDの表現
                        }else{
                            add_tr+="<td>"+v.info.wic_rfid+"</td>"              //RFID
                        }
                        add_tr+="<td></td>"                                 //成形日
                        add_tr+="<td></td>"                                 //品目
                        add_tr+="<td></td>"                                 //工程
                        add_tr+="<td></td>"                                 //型番
                        add_tr+="<td></td>"                                 //Cav
                        add_tr+="<td>0</td>"                                //数
                        add_tr+="<td></td>"                                 //品名
                        add_tr+="<td></td>"                                 //担当者
                        add_tr+="<td></td>"                                 //作業日
                        add_tr+="</tr>";
                    }else{
                        add_tr+="<tr class='"+tr_alert+"'>";
                        add_tr+="<td>"+(row_num.length+1)+"</td>"               //No
                        add_tr+="<td class='text_"+tr_alert+"'>"+v.info.check_val+"</td>"               //結果
                        add_tr+="<td>"+view_type+"</td>"                        //区分
                        add_tr+="<td>"+v.info.number_used+"</td>"             //回数
                        add_tr+="<td>"+v.status+"</td>"                       //状態
                        if(v.info.hgpd_ids){
                            add_tr+="<td>"+v.info.hgpd_ids+"</td>"             //実績ID
                        }else{
                            add_tr+="<td>"+v.info.wic_hgpd_id+"</td>"             //実績ID
                        }
                        add_tr+="<td title='"+v.info.wic_ids+"'>"+v.info.wic_ids+"</td>"                  //在庫ID
                        add_tr+="<td style='display:none;'>"+v.info.wic_rfid+"</td>"                //RFID
                        if(v.info.wic_rfid.length>24){
                            add_tr+="<td>"+t2dec(v.info.wic_rfid)+"</td>"       //RFIDの表現
                        }else{
                            add_tr+="<td>"+v.info.wic_rfid+"</td>"   
                        }
                        if(v.info.hgpd_moldday){
                            add_tr+="<td>"+v.info.hgpd_moldday+"</td>"        //成形日
                        }else{
                            add_tr+="<td></td>"                                 //成形日
                        }
                        add_tr+="<td>"+v.info.wic_itemcode+"</td>"            //品目
                        add_tr+="<td>"+v.info.wic_process+"</td>"             //工程
                        add_tr+="<td>"+v.info.wic_itemform+"</td>"            //型番
                        add_tr+="<td>"+v.info.wic_itemcav+"</td>"             //Cav
                        add_tr+="<td>"+v.info.sum_inv+"</td>"                 //数
                        if(v.info.searchtag!=""){
                            add_tr+="<td>"+v.info.searchtag+"</td>"           //名簿
                        }else{
                            add_tr+="<td>"+v.info.itemname+"</td>"            //品名
                        }
                        add_tr+="<td>"+v.info.wic_name+"</td>"                //担当者
                        add_tr+="<td>"+v.info.wic_date+"</td>"                //作業日
                        add_tr+="</tr>";
                    }
                    $("#checked_data").prepend(add_tr);
                });
            
            }
        });
    }

    function entryCheckRes(){
        if($("#user").val()==""){
            $("#user").click();
            return;
        }

        var name_list = ["row_num","result", "type", "used_num", "status", "hgpd_id", "wic_id", "rfid", "rfid_view", "mold_date", "itemcode", "process", "formnum", "cav", "num", "itemname", "work_user", "work_date"];
        var post_data = get_table_val("checked_data",name_list);
        if(post_data.length==0){
            return;
        }
        console.log(post_data);
        $.ajax({
            type: "POST",
            url: "",
            data: {
                ac:"entryCheckResult",
                user:$("#user").val(),
                data:post_data
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if(response=="OK"){
                    list_code_scaned=[];
                    // $("#checked_data").html("");
                    openAlert(
                        "完了！", 
                        "登録しました。",
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            window.close();
                        }}]
                    );
                }else{
                    openAlert("登録出来ません。", response);
                }
                setTimeout(() => {
                    $( "#alert" ).dialog( "close" );
                    window.close();
                }, 1000);
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

    function get_table_val(id,name_list){
        const table = $("#"+id); 
        var data = [];
        $.each(table,function(k,v){
            var rows = Array.from(v.rows);
            var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
            $.each(cells,function(a,b){
                // if(a>0){
                    let r ={};
                    $.each(name_list,function(k,v){
                        r[v]=b[k]
                    })
                    data.push(r)
                // }
            });
        });
        return data;
    }

    function openCam(){
        od("rfid_input","RFID");
    }

    function od(id,name,fcmode){
        // loadingView(true);
        // $("#"+id).attr('readonly','readonly');
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode");
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
            width:$(window).width()*.5,
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        loadingView(false);
        $("#QRScan").dialog( "open" );
        if($("#"+id).val()!==""){
            // $("#"+id).select();
        }
        // $("#"+id).focus();
    }
    const sleepy = ms => new Promise(resolve => setTimeout(resolve, ms));
    var list_code_scaned = [];
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
                    if(!list_code_scaned.includes(code.data)){
                        $("#scan_msg").html("");
                        list_code_scaned.push(code.data);
                        playMelody(2200);
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                        $("#"+id).val(code.data);
                        // camera_off=true;
                        setTimeout(() => {
                            // stop_scan();
                            getInfoData();
                        },100);
                        await sleepy(300);
                    }else{
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                        drawScanMsg("RFIDが重複");
                    }
                }
            }else{
                $("#scan_msg").html("");
            }
        }
        if(camera_off===false){
            setTimeout(function(){
                if(video!==null){
                    tick(id);
                    // requestAnimationFrame(tick);
                }else{
                    stop_scan();
                }
            },16);
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

    async function drawScanMsg(msg){
        $("#scan_msg").html(msg);
    }

    function stop_scan(){
        canvasElement.hidden = true;
        // list_code_scaned = [];
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

    function searchUser(){
        $.getJSON("/LotManagement/BarcodeUserSet?ac=user&code="+decodeURIComponent($("#input_name").val()),function(data){
            if(data.length>0){
                $("#user").val(data[0].user);
                $( "#dialog_user" ).dialog( "close" );
                $("#input_name").val("");
            }else{
                $("#input_name").val("");
            }
        });
        return false;
    }

    function setUser(name){
        $("#user").val(name);
        $( "#dialog_user" ).dialog( "close" );
    }

</script>

<div id="main_search" style="width:auto;padding:0 0 10px 0;">
    <div style="clear:both;"></div>
    <p style="margin-top:5px;">
        <label style="float: left;margin: 6px 0 0 10px;">RFID：</label><input id="rfid_input" class="check_ip" style="width:450px;margin:0 0 0 5px;" ></input>
        <!-- <label style="margin-left: 10px;">区分：</label>
        <select id="rfid_type" style="margin:0 10px 0 0;height:30px;font-weight:bold;">
            <option value="complete_id" >完成ID</option>
            <option value="device_id" >仕掛ID</option>
        </select> -->
        <button id="btn-search" type="button" onclick="getInfoData();" class="" style="">検索</button>
        <button type="button" onclick="openCam();" class="btn_ditemset btn_topmenu" style="width:60px;"><span class="ui-icon-camera ui-btn-icon-left" ></span></button>
        <input type="text" value="" name="担当者" id="user" style="width:220px;float:right;margin:0 10px 0 5px;text-align: left;"/><label for="user" style="float:right;margin-top:4px;">担当者：</label>
    </p>
    <!-- <hr style="margin:10px 0;"> -->
</div>

<div style="clear:both;"></div>

<div id="main_info" style="display:block;">
    <div style="clear:both;"></div>
    <div id="info_data" style="min-height:32px;">
        <table id="view_table" class="type03" style="width:100%">
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th style="width:40px;">結果</th>
                    <th style="width:50px;">区分</th>
                    <th style="width:40px;">回数</th>
                    <th style="width:100px;">状態</th>
                    <th style="width:60px;">実績ID</th>
                    <th style="width:60px;">在庫ID</th>
                    <th style="width:200px;">RFID</th>
                    <th style="width:80px;">成形日</th>
                    <th style="width:100px;">品目</th>
                    <th style="width:100px;">前工程</th>
                    <th style="width:40px;">型番</th>
                    <th style="width:40px;">Cav</th>
                    <th style="width:50px;">残数</th>
                    <th style="width:100px;">品名</th>
                    <th style="width:80px;">担当者</th>
                    <th style="width:80px;">作業日</th>
                </tr>
            </thead>
            <tbody id="checked_data">
            </tbody>
        </table>
    </div>
</div>
<div style="clear:both;"></div>
<div id="site_help" style="float:left;font-size:16px;margin:10px;">
    <p>
        <b>色の表現 : </b>
        <span class="color-box" style="background:greenyellow;"></span><span style="color:#000;margin:0 15px 0 26px;">次工程に流せる</span>
        <span class="color-box" style="background:#00B0F0;"></span><span style="color:#000;margin:0 15px 0 26px;">完成処理進める</span>
        <span class="color-box" style="background:yellow;"></span><span style="color:#000;margin:0 15px 0 26px;">次工程に流せない</span>
        <span class="color-box" style="background:#e8e8e8;"></span><span style="color:#000;margin:0 15px 0 26px;">未使用</span>
    </p>
</div>
<button id="btn_entry" type="button" onclick="entryCheckRes();" class="" style="float:right;padding:4px;margin:5px 10px 0 0;">記録</button>
<div style="clear:both;"></div>

<div id="dialog_user" title="作業者を選択してください" style="display:none;">
    <div id="ap-user-select"></div>
    <hr style="margin:5px 0 5px 0;color:#ECEADB;">
    コード入力：<input type="text" id="input_name" name="name" value="" style="margin-top:10px;" />
    <button id="addUser" type="button" onclick="searchUser();" style="ime-mode: disabled;">決定</button>
</div>
<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>
<div id="alert2">
    <div id="message2" style="text-align: center;"></div>
</div>