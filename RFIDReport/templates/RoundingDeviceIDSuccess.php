<?php
    use_javascript("jquery/jquery.dump.js");
    use_javascript("jsQR.min.js");

    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");

    slot('h1', '<h1 style="margin:0 auto;font-size:100%;">仕掛IDの端数寄せ作業 | Nalux ');
?>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1, user-scalable=no ">
<style type="text/css">
    html{
        touch-action: manipulation;
    }
    body{
        padding:4px;
    }
    .ui-dialog{background:#f9f9f9 !important;}
    table.type03 { width:48%;max-width:510px; font-size:14px; border-collapse: collapse; text-align: left; line-height: 1.5; border-top: 1px solid #ccc; border-left: 5px solid #ccc; table-layout: fixed; }
    table.type03 th { padding: 2px 4px; font-weight: bold; vertical-align: middle; text-align:center; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
    table.nowarp td{ white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    table.type03 td { padding: 2px 4px; vertical-align: middle; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }

    .ui-btn-theme-custom{
        background-color: #333;
        border-color: #1f1f1f;
        color: #fff;
        text-shadow: 0 1px 0 #111;
    }
    .table-th{
        width:70px;
    }

    .cam-icon{
        position: absolute;
        float: left;
        margin: 0px 0px 0px -12px;
    }
    .cam-icon::after{
    }
    input {
        font-size: 14px;
        line-height: 1;
    }
    .ui-btn-icon-left::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .ui-btn-icon-right::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .ui-icon-camera::after{
        background-size: 85%;
    }
</style>

<script type="text/javascript">	
    var video,canvasElement,canvas;
    var qr_reader = "camera"
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    $(document).ready(function(){
        $("button").button();

        let QRd_w = $(window).width()/2;
        // $("#wb").val($(window).width());
        $("#canvas").width(QRd_w);
        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: QRd_w,
            autoOpen: false,
            modal:true,
            position:["right",30],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });

        $( "#alert" ).dialog({
            autoOpen: false,
            width:QRd_w,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide();$('button').blur(); }
        });

        $(".ui-resizable-handle").hide();
        $(".ui-dialog-titlebar-close").hide();

        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width:$(window).width()-40,
            modal:true,
            position:["center", 100],
            buttons: [{ text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        let user = "<?=$sf_params->get("user")?>";
        if(user!=""){
            $("#user").val(user);
        }

        let plant = "<?=$sf_params->get("plant")?>";
        plant=plant.substr(0,plant.length-2);
        var gr = "製造係_1班";
        if(plant=="山崎"){
            gr = "製造1係";
        }
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(plant)+"&gp2="+decodeURIComponent(gr)/*+"&callback=?"*/,function(data){
            $("#ap-user-select").html(data);
        });

        $( "#user" ).click(function( event ) {
            $( "#dialog_user" ).dialog( "open" );
            // event.preventDefault();
        });

        $(".scan_id").on('focus',function(e){
            if(!video && qr_reader=="camera"){
                setTimeout(() => {
                    od(e.target.id,e.target.title);
                }, 100);
            }
        });

        $(".scan_id").on('keyup',function(e){
            if(e.keyCode==13){
                console.log(e);
                viewInfoId(e.target.id,e.target.value,e.target.title)
            }
        });

        var tapedTwice = false;
        document.getElementById("content").addEventListener("touchstart", function(e){
            if(!tapedTwice) {
                tapedTwice = true;
                setTimeout( function() { tapedTwice = false; }, 300 );
                return false;
            }
            e.preventDefault();
            //action on double tap goes below
        });
        controlFocus();
    });

    function controlFocus(){
        if($("#user").val()==""){
            $( "#dialog_user" ).dialog( "open" );
        }else if($("#main_rfid").val()==""){
            if(!video){
                od('main_rfid','主RFID');
            }
        }else{
            if(!video){
                od('sub_rfid','副RFID');
            };
        }
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
        let plant="<?=$sf_params->get("plant")?>";
        username = name;
        window.history.pushState('', 'Title', '?plant='+plant+'&user='+name);
    }
    
    function getInfoId(id,code){
        return new Promise((resolve) => {
            $.ajax({
                type: 'GET',
                url: "",
                async : false,
                dataType: 'json',
                data:{
                    "ac":"getRfid",
                    "rfid":code
                },
                success: function(d) {
                    console.log(d);
                    resolve(d);
                }
            })
        });
    }

    async function viewInfoId(id,code,name){
        let check_rfid = await getInfoId(id,code);
        if(check_rfid.data.length==0){
            let msg = "<p>RFID: <b>"+code+"</b></p>"+check_rfid.status;
            openAlert("確認",msg,null,function(){
                if(!video){
                    od(id,name);
                }
            });
            list_code_scaned = list_code_scaned.filter(function(elem){
                return elem != code; 
            });
            return false;
        }else{
            if(id=="main_rfid"){
                $("#main_rfid").val(check_rfid.data.hgpd_rfid);
                $("#main_hgpd_id").val(check_rfid.data.last_id);
                let round_num = check_rfid.data.tray_num*check_rfid.data.tray_stok
                $("#round_num").val(round_num);
                $("#main_process").html(check_rfid.data.last_process);
                $("#main_itemcode").html(check_rfid.data.hgpd_itemcode);
                $("#main_lot").html(check_rfid.data.hgpd_moldlot);
                $("#main_itemform").html(check_rfid.data.hgpd_itemform);
                $("#main_cav").html(check_rfid.data.hgpd_cav);
                $("#main_num").val(check_rfid.data.inv_num);
                
                if(check_rfid.data.inv_num>=round_num){
                    openAlert("確認!!!","まるめ数を確認してください。");
                    return;
                }
            }else{
                if( $("#main_itemcode").html() != check_rfid.data.hgpd_itemcode){
                    openAlert("確認!!!","副IDの製品が違います。");
                    return;
                }else{
                    $("#sub_rfid").val(check_rfid.data.hgpd_rfid);
                    $("#sub_hgpd_id").val(check_rfid.data.last_id);
                    $("#sub_process").html(check_rfid.data.last_process);
                    $("#sub_itemcode").html(check_rfid.data.hgpd_itemcode);
                    $("#sub_lot").html(check_rfid.data.hgpd_moldlot);
                    $("#sub_itemform").html(check_rfid.data.hgpd_itemform);
                    $("#sub_cav").html(check_rfid.data.hgpd_cav);
                    $("#sub_num").val(check_rfid.data.inv_num);
                }
            }
        }
        setTimeout(() => {
            if($("#main_rfid").val()!="" && $("#sub_rfid").val()!=""){
                roundItem();
            }
        }, 100);
    }

    var cr = true;
    async function roundItem(){
        console.log("round")

        if($("#main_rfid").val()=="" || $("#sub_rfid").val()==""){
            openAlert("確認!!!","RFIDをスキャンしてください。")
            cr = false;
            return;
        }
        if($("#main_itemcode").html() =="" || $("#sub_itemcode").html() == ""){
            cr = false;
            return;
        }
        if($("#main_itemcode").html() != $("#sub_itemcode").html()){
            openAlert("確認!!!","副IDの製品が違います。");
            cr = false;
            return;
        }

        if($("#main_process").html() != $("#sub_process").html()){
            openAlert("確認!!!","同じ工程しかないで端数寄せできます。");
            cr = false;
            return;
        }

        $("#after_main_rfid").html($("#main_rfid").val());
        $("#after_sub_rfid").html($("#sub_rfid").val());
        let before_main_num = parseInt($("#main_num").val());
        let before_sub_num = parseInt($("#sub_num").val());
        let need_num = parseInt($("#round_num").val())-before_main_num;
        console.log(need_num);
        if(before_sub_num>need_num || before_sub_num==need_num){
            $("#rounding_num").val(need_num);
            $("#after_main_num").val($("#round_num").val());
            $("#after_sub_num").val(before_sub_num-need_num);
            $("#main_def").html("(+<span style='color:darkgreen;'>"+need_num+"</span>)");
            $("#sub_def").html("(-<span style='color:red;'>"+need_num+"</span>)");
        }else{
            $("#rounding_num").val(before_sub_num);
            $("#after_main_num").val(before_main_num+before_sub_num);
            $("#after_sub_num").val(0); 
            $("#main_def").html("(+<span style='color:darkgreen;'>"+before_sub_num+"</span>)");
            $("#sub_def").html("(-<span style='color:red;'>"+before_sub_num+"</span>)");
        }
        
        return new Promise((resolve) => {
            resolve(cr);
        })

    }
    
    async function entryData(){
        let wr = await roundItem();
        if(wr){
            loadingView(true);
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    ac:"entry",
                    d:{
                        user:$("#user").val(),
                        main_hgpd_id:$("#main_hgpd_id").val(),
                        sub_hgpd_id:$("#sub_hgpd_id").val(),
                        main_num:$("#main_num").val(),
                        sub_num:$("#sub_num").val(),
                        rounding_num:$("#rounding_num").val()
                    }
                },
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    loadingView(false);
                    if(res=="OK"){
                        openAlert("完了","登録しました。");
                        clearAll(true);
                    }else{
                        openAlert("エーラー",res);
                    }
                }
            });
        }
    }

    function od(id,name,fcmode){
        qr_reader="camera";
        if($("#main_rfid").val()=="" && id=="sub_rfid"){
            openAlert("確認!!!","まず、主IDをスキャンしてください。");
            return;
        }
        loadingView(true);
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        // $("#"+id).attr('readonly', 'readonly');
        $("#main_rfid").attr('readonly', 'readonly');
        $("#sub_rfid").attr('readonly', 'readonly');
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:600 }}).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            tick(id,name);
        });
        
        let button = [
            { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
                qr_reader="scaner";
                stop_scan();
                // $("#"+id).removeAttr('readonly');
                $("#main_rfid").removeAttr('readonly');
                $("#sub_rfid").removeAttr('readonly');
                setTimeout(() => {
                    // $("#"+id).focus();                    
                    $("#"+id).focus();                 
                }, 100);
            }},{ class:"btn-right",html:"<span class='dialog_btn ui-icon-recycle ui-btn-icon-right'>カメラ切替</span>",click :function() {
                qr_reader="camera";
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
            }},
            { class:"btn-left",html:"<span class='dialog_btn ui-icon-delete ui-btn-icon-right'>閉じる</span>",click :function() {
                stop_scan();
            }}
        ];
        // if(id=="Lotコード"){
        //     button = [{ text:"閉じる",click :function() {
        //         stop_scan();
        //     }}];
        // }
        var options = {"title":name+"をスキャンしてください。",
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        loadingView(false);
        $("#QRScan").dialog( "open" );
        $("#"+id).focus();
        // setTimeout(function(){
        //     stop_scan();
        // }, 300000);
    }

    const sleepy = ms => new Promise(resolve => setTimeout(resolve, ms));
    var list_code_scaned = [];
    async function tick(id,name) {
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
                        list_code_scaned.push(code.data);
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                        playMelody(2200);
                        await sleepy(300);
                        // $("#"+id).val(code.data);
                        viewInfoId(id,code.data,name);
                        stop_scan();
                    }else{
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                    }
                }
            }
        }
        if(camera_off===false){
            setTimeout(function(){
                if(video!==null){
                    tick(id,name);
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

    async function stop_scan(){
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

    function openAlert(title,msg,btn,callback){
        if(!btn){
            btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
                if(callback){
                    callback();
                }
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

    function clearAll(flag){
        if(flag!==true){
            flag = confirm("本当に入力した全てクリアしてもいいでしょうか？");
        }
        if(flag){
            list_code_scaned=[];
            list_itemcode=[];
            $(".td-val").html("");
            $("input").val("");
            if(username!=""){
                $("#user").val(username);
            }else{
                $( "#dialog_user" ).dialog( "open" );
            }
        }
    }

    function closeTab(){
        if($("#main_frid").val()!="" && $("#sub_rfid").val()!=""){
            let cf = confirm("登録せずに閉じても宜しいですか？");
            if(cf){
                window.close();
            }
        }else{
            window.close();
        }
    }

</script>

<p style="text-align:left;margin:10px 0;font-weight:bold;">
    <label style="margin-right:5px;">まるめ数：</label><input type="number" id="round_num" value="" style="text-align:center;width:80px;height:28px;font-size:16px;margin-right:15px;font-weight:bold;" />
    <input type="hidden" id="rounding_num" value="" />
    <label for="user" style="">担当者：</label><input type="text" value="" name="user" id="user" readonly="readonly" style="width:180px;height:28px;font-size:16px;font-weight:bold;"/>
    <!-- <label for="wb" style="">width：</label><input type="text" value="" name="wb" id="wb" readonly="readonly" style="width:180px;font-weight:bold;"/> -->
</p>
<div style="float:left;">
    <div style="display: inline-block;">
        <table class='type03' style='float:left;margin:0;'>
            <tr>
                <td class="table-th" style="">主RFID</td>
                <td style="text-align:center;">
                    <input id="main_rfid" class="scan_id" value="" placeholder="主RFIDをスキャン" title="主RFID" dir="rtl" style="float:left;width:calc( 100% - 50px );height:28px;font-size:12px;font-weight:bold;text-align:center;" readonly="readonly" />
                    <span class='cam-icon ui-btn ui-shadow ui-corner-all ui-icon-camera ui-btn-icon-notext ui-btn-inline ui-btn-theme-custom' onclick="od(`main_rfid`,`主RFID`)" style=""></span>
                    <input type="hidden" id="main_hgpd_id" value="" />
                </td>
            </tr>
            <tr><td class="table-th">工程</td><td id="main_process" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">品目コード</td><td id="main_itemcode" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">成形ロット</td><td id="main_lot" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">型番</td><td id="main_itemform" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">キャビ</td><td id="main_cav" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">残数</td>
                <td style="text-align:center;">
                    <input id="main_num" value="" title="" style="float:left;width:100%;font-weight:bold;text-align:center;border:none;outline:none;" readonly="readonly" />
                </td>
            </tr>

        </table>
        <!-- <span class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-l ui-btn-icon-notext ui-btn-inline ui-btn-theme-custom" style='float:left;margin:10px 10px;'></span> -->
        <span class="" style='float:left;margin: 15px;'></span>

        <table class='type03' style='float:left;margin:0;'>
            <tr>
                <td class="table-th">副RFID</td><td style="text-align:center;">
                    <input id="sub_rfid" class="scan_id" value="" placeholder="副RFIDをスキャン" title="副RFID" dir="rtl" style="float:left;width:calc( 100% - 50px );height:28px;font-size:12px;font-weight:bold;text-align:center;" readonly="readonly" />
                    <span class='cam-icon ui-btn ui-shadow ui-corner-all ui-icon-camera ui-btn-icon-notext ui-btn-inline ui-btn-theme-custom' onclick="od(`sub_rfid`,`副RFID`)" style=""></span>
                    <input type="hidden" id="sub_hgpd_id" value="" />
                </td>
            </tr>
            <tr><td class="table-th">工程</td><td id="sub_process" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">品目コード</td><td id="sub_itemcode" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">成形ロット</td><td id="sub_lot" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">型番</td><td id="sub_itemform" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">キャビ</td><td id="sub_cav" class="td-val" style="text-align:center;"></td></tr>
            <tr><td class="table-th">残数</td>
                <td style="text-align:center;">
                    <input id="sub_num" value="" title="" style="float:left;width:100%;font-weight:bold;text-align:center;border:none;outline:none;" readonly="readonly" />
                </td>
            </tr>
        </table>
    </div>
    <p style="text-align:center;">
        <span class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-d ui-btn-icon-notext ui-btn-inline ui-btn-theme-custom" style='margin: 5px 0 5px -10px;'></span>
    </p>
    <div style="display: inline-block;">
        <table class='type03' style='float:left;margin:0;'>
            <tr><td class="table-th">主RFID</td><td id="after_main_rfid" class="td-val" style="text-align:center;width:calc( 100% - 50px );font-weight:bold;"></td></tr>
            <tr>
                <td class="table-th">残数</td>
                <td style="text-align:center;">
                    <input id="after_main_num" value="" title="主RFID" dir="rtl" style="float:left;width:50%;margin-right:4px;font-weight:bold;text-align:right;border:none;outline:none;" readonly="readonly" />
                    <span id="main_def" class="td-val" style="float:left;"></span>
                </td>
            </tr>
        </table>
        <span class="" style='float:left;margin:15px;'></span>
        <table class='type03' style='float:left;margin:0;'>
            <tr><td class="table-th">副RFID</td><td id="after_sub_rfid" class="td-val" style="text-align:center;width:calc( 100% - 50px );font-weight:bold;"></td></tr>
            <tr>
                <td class="table-th">残数</td>
                <td style="text-align:center;">
                    <input id="after_sub_num" value="" title="副RFID" dir="rtl" style="float:left;width:50%;margin-right:4px;font-weight:bold;text-align:right;border:none;outline:none;" readonly="readonly" />
                    <span id="sub_def" class="td-val" style="float:left;"></span>
                </td>
            </tr>
        </table>
    </div>
    <div style="clear:both;"></div>
    <div style="float:left;padding:10px 0 0 0;" class="btn_control">
        <button type="button" id="closeTab" onclick="closeTab();" >閉じる</button>
        <button type="button" id="clear_all" onclick="clearAll();" >クリア</button>
    </div>
    <div style="float:right;padding:10px 4px 0 4px;" class="btn_control">
        <button type="button" id="re_rounding" onclick="roundItem();" >再計算</button>
        <button type="button" id="re_entry" onclick="entryData();">登録</button>
    </div>
</div>
<div id="dialog_user" title="作業者を選択してください" style="display:none;">
    <div id="ap-user-select"></div>
    <hr style="margin:5px 0 5px 0;color:#ECEADB;">
    コード入力：<input type="text" id="input_name" name="name" value="" style="margin-top:10px;" />
    <button id="addUser" type="button" onclick="searchUser();" style="ime-mode: disabled;">決定</button>
</div>
<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>
<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <script type="text/javascript">
    </script>
</div>
