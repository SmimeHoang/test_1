<?php
    slot('h1','<h1 style="margin:0 auto;">QR棚卸 | Nalux</h1>');
    use_javascript("jsQR.min.js");
?>

<style type="text/css">
    table.type03 { /* width:70%; */ font-size:13px; border-collapse: collapse; text-align: center; line-height: 1.5; border-top: 2px solid #ccc; border-left: 3px solid #369; table-layout: fixed; margin-right:0.2em; margin-left:1em; }
    table.type03 th { padding: 1px 2px; font-weight: bold; vertical-align: top; color: #153d73; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
    table.type03 td { padding: 1px 2px; vertical-align: top; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; white-space: nowrap; }
    table caption { font-size:16px; text-align: left; font-weight: bold; color:#0d2e59; }
    #loading { width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; /* 背景関連の設定 */ background-color: #ccc; filter: alpha(opacity=85); opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center; background-repeat: no-repeat; background-attachment: fixed; }
    #loading img{ max-width: 100%; height:auto; }
    .ui-button{ font-size: 12px; padding: 0; }
    #rfid_value{ font-size: 15px; margin: 3px 4px; width:220px; ime-mode: inactive; }
    .del_btn {font-size:8px; }
</style>

<script type="text/javascript">
    var video,canvasElement,canvas;
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }

    $(document).ready(function(){
        let QRd_w = $(window).width();
        $("#rfid_value").on("keyup",function(e){
            if(e.keyCode==13){
                getItemList();
            }
        });
        $("button").button();
        $("#qr_cam").button({
            icons: {
            primary: 'ui-icon-tag'
            },
            text: false
        });
        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: 640,
            autoOpen: false,
            modal:true,
            position:["top",80],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });
    });
    
    var list_data = [];

    function getItemList(ac=null){
        if(ac==null){
            if($("#rfid_value").val()==""){
                // alert("値が入っていません。");
                return;
            }
        }
        
        // console.log(list_data);
        loadingView(true);
        var rfid = $("#rfid_value").val().trim();
        if(!list_data.includes(rfid)){
            list_data.push(rfid);
        }
        
        $.ajax({
            type: 'POST',
            url: "",
            dataType: 'json',
            data:{"ac":"getRFID","rfid_value":list_data},
            success: function(d) {
                $("#list_view").html(d);
                $("#rfid_value").val("");
                
                $(".del_btn").button({
                    icons: {
                    primary: 'ui-icon-circle-close'
                    },
                    text: false
                });
                setTimeout(() => {
                    od('rfid_value','IDをスキャン');
                }, 1000);
            }
        });
        
        loadingView(false);
    }
    
    function del_line(rfid){
        console.log(list_data);
        var index = list_data.indexOf(rfid);
        list_data.splice(index, 1);
        getItemList("del_line");
    }

    function loadingView(flag) {
        $('#loading').remove();
        if(!flag) return;
        $('<div id="loading" />').appendTo('body');
    }

    function od(id,name,fcmode){
        loadingView(true);
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:720,height:360 } }).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            // requestAnimationFrame(tick);
            tick();
        });

        let button = [ { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
            stop_scan();
            if(id!=="documentno"){
                qr_input();
            }
            $("#"+id).focus();
            // getItemList("rfidcpl1");

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
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        loadingView(false);
        $("#QRScan").dialog( "open" );
        if($("#"+id).val()!==""){
            $("#"+id).select();
        }
        $("#"+id).focus();
    }

    function tick() {
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
                    camera_off=false;
                    playMelody(2200);
                    drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                    drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                    drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                    drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                    // getLotCode(code.data.split("=>"));
                    $("#"+document.activeElement.id).val(code.data);
                    getItemList();
                    // if(document.activeElement.id!=="documentno"){
                    //     getItemList(code.data);
                    // }else{
                    //     rfid_check();
                    // }
                    stop_scan();
                }
            }
        }
        if(camera_off===false){
            setTimeout(function(){
                if(video!==null){
                    tick();
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
</script>
<p style="float: left;">
    <label for="rfid_value">IDスキャン</label>
    <input type="text" autocomplete="off" value="" name="rfid_value" id="rfid_value"/>
    <button id="search" type="button" onclick="getItemList();">検索</button>
    <button id="qr_cam" type="button" onclick="od('rfid_value','IDをスキャン');">QR</button>
</p>
<p style="float: right;">
    <button type="button" onclick="window.location.reload();">クリア</button>
</p>

<div style="clear: both;" id="list_view"></div>

<div id="QRScan" style="padding:0;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
</div>