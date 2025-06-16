<?php
    use_javascript("jsQR.min.js");
    use_stylesheet("jquery.mobile-1.4.5.min.css");

    slot('h1', '<h1 style="margin-top:2px;font-size:110%;">在庫移動処理 ｜ Nalux</h1>');
?>

<meta name="viewport" content="user-scalable=no">
<style type="text/css">
    
    .ui-dialog{background:#f9f9f9 !important;}
    #item_list{
        border: 1px solid blue;
    }
    input,label{
        font-size:16px;
        height:26px;
        font-weight:bold;
        text-align:center;
    }
    select{
        font-size: 16px;
        height: 30px;
        font-weight: bold;
        background-color: white;
        border: 1px solid #aaa;
        text-align:center;
    }
    .ipbox{float:left;margin-right:8px;text-align:center;}

    table {width:100%;border-collapse:collapse;text-align:center;line-height:1.5em;border-left:1px solid #ccc;table-layout:fixed;}
    table th {padding: 2px 4px;font-weight:bold;vertical-align:top;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table td {/*white-space:nowrap;overflow:hidden;text-overflow:ellipsis;*/}
    table td {padding: 2px 4px;vertical-align:top;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;white-space: nowrap;}
    table thead th{
        position: sticky;
        top: 0;
        background: #fff;
        box-shadow: 0 1px 2px 1px #ccc;
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
    #btn-scanqr{
        position: relative;
        top: 20px;
    }
    #btn-scanqr .ui-button-text{
        padding: 4px 10px;
    }
</style>

<script type="text/javascript">
    var video,canvasElement,canvas;
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }

    var plant_name = "<?=$sf_params->get("plant")?>";
    var plant_id = "1000079";
    var username = "<?=$sf_params->get("user")?>";
    var stock_place = JSON.parse('<?= htmlspecialchars_decode($stock_place); ?>');

    $(document).ready(function(){
        let QRd_w = $(window).width();
        $("button").button();
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
        $("#canvas").width(QRd_w/2);

        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: QRd_w/2,
            autoOpen: false,
            modal:true,
            position:["top",80],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });

        $(".ui-resizable-handle").hide();
        $(".ui-dialog-titlebar-close").hide();

        if(plant_name==""){
            var options = {"title":"工場を選択して下さい。",
                position:["center",100],
                width: '430px',
                buttons: [{ text:"野洲工場",click :function(ev) {
                    location.href="/RFIDReport/MovingItem?plant=野洲工場";
                    $( this ).dialog( "close" );
                }},{ text:"山崎工場",click :function(ev) {
                    location.href="/RFIDReport/MovingItem?plant=山崎工場";
                    $( this ).dialog( "close" );
                }}]
            };
            $(".ui-dialog-titlebar-close").hide();
            $(".ui-resizable-se").hide();
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
            $("#alert").css({"padding":"0"})
            $(".ui-dialog-buttonpane").css({"border":"none"})
            return;
        }
        if(plant_name=="山崎工場"){
            plant_id = "1000073";
        }

        if(username!=""){
            $("#担当者").val(username);
        }
        $("button").button();

        $("#documentno").on("keyup",function(e){
            if(e.keyCode==13){
                checkCode();
                return;
            }
        })
        $("#documentno").on("blur",function(e){
            if(e.target.value!=="" && !video){
                checkCode();
                return;
            }
        })

        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width:$(window).width()-40,
            modal:true,
            position:["center", 100],
            buttons: [{ text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });

        $("#移動先").on("change", function(e){
            console.log(e);
            plant_name = e.target.value;
            let select_place = `<option value="-">-</option>`;
            $.each(stock_place[plant_name], function (a, b) { 
                select_place+= `<option value="`+b.place_info+`">`+b.place_info+`</option>`;
            });
            $("#移動場所").html(select_place);
        });

        $("#qr_rfid").on("keyup",function(e){
            if(e.keyCode==13){
                getItemList(e.target.value);
            }
        });

        var plant = plant_name.substr(0,plant_name.length-2);
        var gr = "製造係_1班";
        if(plant=="山崎"){
            gr = "製造1係";
        }
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(plant)+"&gp2="+decodeURIComponent(gr)/*+"&callback=?"*/,function(data){
            $("#ap-user-select").html(data);
        });
        
        $( "#担当者" ).click(function( event ) {
            $( "#dialog_user" ).dialog( "open" );
            event.preventDefault();
        });

        var timer = false;
        $(window).resize(function() {
            if (timer !== false) {
                clearTimeout(timer);
            }
            timer = setTimeout(function() {
                resize_item_list();
            }, 100);
        });
        checkInput();
        // resize_item_list();
    });

    function changeDate(){
        checkInput();
    }

    function checkInput(){
        if($("#担当者").val()==""){
            $( "#dialog_user" ).dialog( "open" );
        }else if($("#移動先").val()==""){
            $("#移動先").focus();
        }else{
            od('qr_rfid','QRコード');
        }
    }

    function resize_item_list(){
        let wh = $(window).height();
        let mh = $("#info_menu").height();
        if($("#item_list").height() > wh-mh-110){
            $("#item_list").height(wh-mh-110)
        }
    }

    function get_data(dataAry, key, value) {
        var result = $.grep(dataAry, function (e) {
            return e[key] == value;
        });
        return result;
    }

    function goEnd(){
        $("#ip_end_time").val(nowDT("dt"));
        let sdt = new Date($("#ip_start_time").val());
        let edt = new Date($("#ip_end_time").val());
        let work_time = (edt.getTime()-sdt.getTime())/(60*1000);    //min
        $("#ip_work_time").val(work_time.toFixed(0));
        let item_num = $(".work-time").length;
        $(".work-time").html((work_time/item_num).toFixed(1));
    }

    function get_table_val(id,name_list){
        const table = $("."+id); 
        var data = [];
        $.each(table,function(k,v){
            var rows = Array.from(v.rows);
            var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
            $.each(cells,function(a,b){
                let r ={};
                $.each(name_list,function(k,v){
                    r[v]=b[k]
                })
                data.push(r)
            });
        });
        return data;
    }

    var list_itemcode = [];
    async function getItemList(rfid){
        let check = await check_dupli_rfid(rfid);
        if(check==false){
            if(video){
                video.pause();
            }
            let msg="<p>RFID：<span style='font-weight:bold;'>"+rfid+"</span></p>RFIDが重複です。";
            var options = {"title":"確認してください！！！",
                width: 550,
                position:["center",150],
                buttons: 
                    [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        if(video){
                            video.play();
                        }
                        return;
                    }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog("open");
            return;
        }
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getInfoItem",
                rfid:rfid,
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                console.log(d);
                if(d[0]=="NG"){
                    if(video){
                        video.pause();
                    }
                    let msg="<p>RFID：<span style='font-weight:bold;'>"+rfid+"</span></p>"+d[1];
                    msg+="<p>30秒以内でこのIDを再読み込み出来ません。</p>"
                    var options = {"title":"確認してください!!!",
                        width: 550,
                        position:[50,150],
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                                if(video){
                                    video.play();
                                }
                                return;
                            }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog("open");

                    //30s後でNGのIDを再スキャンできる
                    setTimeout(() => {
                        if(video){
                            video.play();
                        }
                        $("#alert").dialog("close");
                        list_code_scaned = list_code_scaned.filter(function(elem){
                            return elem != rfid; 
                        });
                    }, 30000);
                }else if(d[0]=="OK"){
                    let item_info = d[1];
                    $.each(item_info, function (a, b) { 
                        let sum_num = 0;
                        if($("#sum_num_"+b.wic_itemcode).html()!=""){
                            sum_num = parseInt($("#sum_num_"+b.wic_itemcode).html())+parseInt(b.now_inventory);
                        }else{
                            sum_num = parseInt(b.now_inventory);
                        }
                        $("#sum_num_"+b.wic_itemcode).html(sum_num);
                        let no = $(".row_no_"+b.wic_itemcode).length+1;
                        $("#sheet_num_"+b.wic_itemcode).html(no);

                        if(!list_itemcode.includes(b.wic_itemcode)){
                            list_itemcode.push(b.wic_itemcode);
                            let add_table = "";
                            add_table=`<p style="margin-left:10px;font-weight:bold;color:#153d73">品名：`+b.tag_name+`　枚数：<label id="sheet_num_`+b.wic_itemcode+`">`+no+`</label>　合計：<label id="sum_num_`+b.wic_itemcode+`" style="color:green;">`+b.now_inventory+`</label></p>
                            <table id="pick_table_item" style="font-size:80%;">
                                <thead>
                                    <tr>
                                        <th style="width:20px;">No.</th>
                                        <th style="width:240px;">RFID</th>
                                        <th style="width:50px;">管理ID</th>
                                        <th style="min-width:80px;">品名</th>
                                        <th style="width:100px;">品目コード</th>
                                        <th style="width:120px;">工程</th>
                                        <th style="width:30px;">型番</th>
                                        <th style="width:40px;">キャビ</th>
                                        <th style="width:30px;">数量</th>
                                        <th style="width:220px;">現在場所</th>
                                    </tr>
                                </thead>
                                <tbody id="item_add_`+b.wic_itemcode+`" class="item_add";
                            </table>`;
                            $("#item_list").prepend(add_table);
                        };
                        let add_item = "";

                        add_item+=`<tr class="added_row">
                            <td class="row_no_`+b.wic_itemcode+`" >`+no+`</td>
                            <td class="rfid" style="font-weight:bold;">`+b.wic_rfid+`</td>
                            <td>`+b.hgpd_ids+`</td>
                            <td>`+b.tag_name+`</td>
                            <td>`+b.wic_itemcode+`</td>
                            <td>`+b.hgpd_process+`</td>
                            <td>`+b.wic_itemform+`</td>
                            <td>`+b.wic_itemcav+`</td>
                            <td style="font-weight:bold;">`+b.now_inventory+`</td>
                            <td>`+b.wic_wherhose+`</td>
                            <td style="display:none;">`+b.wic_ids+`</td>
                            <td style="display:none;">`+b.wic_process_key+`</td>`;
                        add_item+=`</tr>`;
                        $("#item_add_"+b.wic_itemcode).prepend(add_item);
                        setTimeout(() => {
                            // od('QRコード','完成品QR');
                            $("#btn-entry").button("enable");
                        }, 2000);
                    });

                }
            }
        });

    }


    function check_dupli_rfid(rfid){
        return new Promise((resolve) => {
            let flag=true;
            let check_rfid = $(".rfid");
            $.each(check_rfid,function(k,v){
                if(v.innerHTML == rfid){
                    flag = false;
                }
            });
            resolve(flag);
        })
    }
    
    async function goEntry(){
        var name_list = ["no","rfid","hgpd_id","itemname", "itemcode","process","itemform","itemcav","num","from","wic_ids","process_key"];
        var post_data = get_table_val("item_add",name_list);
        console.log(post_data);
        if(post_data.length==0){
            alert("QRスキャンしてください。");
            return;
        }
        // loadingView(true);
        $.ajax({
            type: "POST",
            url: "",
            data: {
                ac:"Entry",
                d:{
                    // start_time:$("#ip_start_time").val(),
                    // end_time:$("#ip_end_time").val(),
                    list_item:post_data,
                    user:$("#担当者").val(),
                    plant_name:plant_name,
                    plant_id:plant_id,
                    ariver_to:$("#移動場所").val()
                }
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                if(d=="OK"){
                    let msg="登録しました。";
                    var options = {"title":"完了。",
                        width: 600,
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $("#btn-entry").button("disable");
                                $( this ).dialog( "close" );
                                clearAll(true);
                                return;
                            }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog("open");
                }
            }
        });
    }

    async function entryConfirm(){
        let ip_check = await entryCheck();
        if(ip_check>0){
            return;
        }
        let msg ="実績を登録しますか？";
        $("#message").html(msg);
        var options = {"title":"確認してください！！！",
            width: 600,
            buttons: 
                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }},{ class:"btn-right",text:"登録",click :function(ev) {
                    goEntry();
                }}]
        };
        $( "#alert" ).dialog( "option",options);
        $("#alert").dialog("open");
    }

    function entryCheck(){

        return new Promise((resolve) => {
            let check = $(".check_ip");
           
            msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
            var errc=0;

            $.each(check,function(key,val) {
                if(val.value==""){
                    msg = msg +"<li>"+val.id+"</li>\n";
                    errc++;
                }
            });
            if(errc>0){
                var options = {"title":"確認してください！！！",
                    width: 600,
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            return;
                        }}]
                };
                $("#message2").html(msg);
                $( "#alert2" ).dialog( "option",options);
                $("#alert2").dialog("open");
                return;
            }
            resolve(errc);
        })
    }

    function buildTableData() {
        const elements = [...document.querySelectorAll('tr')];
        var rt = [];
        $.each(elements,function(k,v){
            let et=[];
            const e2=[...v.children]
            e2.map(x => {
                // console.log(x);
                et.push(x.innerHTML);
            });
            rt.push(et); 
        })
        return rt;
    }     

    function od(id,name,fcmode){
        // loadingView(true);
        if(id=="documentno" && $("#movement_date").val()!=""){
            let cf = confirm("出荷伝票コードを再スキャンしますか？");
            if(!cf){
                return;
            }
        }
        $("#"+id).attr('readonly', 'readonly');

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
            // requestAnimationFrame(tick);
            tick(id);
        });

        let button = [ { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
            $("#"+id).removeAttr('readonly');
            stop_scan();
            setTimeout(() => {
                $("#"+id).focus();
            }, 200);
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
            $("#"+id).removeAttr('readonly');
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
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                        $("#"+id).val(code.data);
                        playMelody(2200);
                        await sleepy(300);
                        getItemList(code.data);
                    }else{
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                        $("#scan_msg").html("RFIDが重複");
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
            return year+"-"+month+"-"+day+" "+hour+":"+min;
        }
        if(str=="date"){
            return year+"-"+month+"-"+day;
        }
        if(str=="ti"){
            return hour+":"+min;
        }
    }

    function searchUser(){
        $.getJSON("/LotManagement/BarcodeUserSet?ac=user&code="+decodeURIComponent($("#input_name").val()),function(data){
            if(data.length>0){
                $("#担当者").val(data[0].user);
                $( "#dialog_user" ).dialog( "close" );
                $("#input_name").val("");
            }else{
                $("#input_name").val("");
            }
        });
        return false;
    }

    function setUser(name){
        $("#担当者").val(name);
        $( "#dialog_user" ).dialog( "close" );
        let plant="<?=$sf_params->get("plant")?>";
        let mode="<?=$sf_params->get("mode")?>";
        username = name;
        window.history.pushState('', 'Title', '?plant='+plant+'&mode='+mode+'&user='+name);
    }

    function clearAll(flag){
        if(!flag){
            flag = confirm("本当に入力した全てクリアしてもいいでしょうか？");
        }
        if(flag){
            list_code_scaned=[];
            list_itemcode=[];
            $("#item_list").html("");
            $("input").val("");
            $("#btn-entry").button("enable");
            $("#btn-start").button("enable");
            if(username!=""){
                $("#担当者").val(username);
            }else{
                $( "#dialog_user" ).dialog( "open" );
            }
        }
    }

</script>
<div id="main-left" style="width:auto;padding: 5px 10px;">
    <div id="info_menu" style="display: block;float:left;margin-bottom:5px;width: 100%;" >
            <div class="ipbox">
                <p><label style="">作業日</label></p>
                <input id="work_date" class="check_ip" type="date" value="<?=date("Y-m-d")?>" onchange="changeDate()" style="" ></input>
            </div>
            <div class="ipbox">
                <p><label for="担当者" style="">担当者</label></p>
                <input type="text" value="" name="担当者" id="担当者" style="width:160px;"/>
            </div>

            <div class="ipbox">
                <p><label for="移動先" style="">移動工場先</label></p>
                <select type="text" value="" name="移動先" id="移動先" class="check_ip" style="width:120px;border-radius: 4px;">
                    <option value="">-</option>
                    <option value="山崎工場">山崎工場</option>
                    <option value="野洲工場">野洲工場</option>
                </select>
            </div>
            <div class="ipbox">
                <p><label for="移動場所" style="">保管場所先</label></p>
                <select type="text" value="" name="移動場所" id="移動場所" class="check_ip" style="width:400px;border-radius: 4px;">
                    <option value="">-</option>
                    <?php foreach($all_place as $key=>$value){ ?>
                        <option value="<?= $value["place_info"] ?>"><?= $value["place_info"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="clear:both;"></div>
            <div class="ipbox" style="margin-top:4px;">
                <p><label for='QRコード' style="">QRコード</label></p>
                <input id='qr_rfid' type='tel' pattern='[0-9]*' value='' placeholder='' autocomplete='off'  style='width:400px;' />
            </div>
            <button id="btn-scanqr" type="button" onclick="od('qr_rfid','QRコード');" class="" style=""><span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
    </div>
    <div style="clear:both;"></div>
    <div id="item_list" style="overflow: scroll;margin:5px 0;"></div>
    <div style="clear:both;"></div>
    <button id="btn-clear" type="button" onclick="clearAll();" class="" style="float:left;">クリア</button>
    <button id="btn-entry" type="button" onclick="entryConfirm();" class="" style="float:right;">登録</button>
</div>

<div id="QRScan" style="padding:0;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <div id="scan_msg" style="position:absolute;bottom:0;right:0;color:red;text-shadow: 1px 0 #fff, -1px 0 #fff, 0 1px #fff, 0 -1px #fff,1px 1px #fff, -1px -1px #fff, 1px -1px #fff, -1px 1px #fff;"></div>
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
<div id="alert2">
    <div id="message2" style="text-align: center;"></div>
</div>