<?php
    use_javascript("jsQR.min.js");
    slot('h1', '<h1 style="margin: 2px 0 0 5px;">RFID解放‐修正 ｜ Nalux</h1>');
?>

<meta name="viewport" content="user-scalable=no">
<style type="text/css">
    @media screen (max-width: 767px) { 
    html{
        width:768px;
    }
    }
    @media only screen and (min-width: 768px) and (max-width: 1205px) { 
        html{
            width:1300px;
        }
    }
    #item_list{
        border: 1px solid blue;
    }
    input,label{
        font-size:18px;
        height:30px;
        font-weight:bold;
    }
    label {
        /* margin: 4px 0 0 0; */
    }
    p{
        /* padding: 4px 0; */
    }
    table {width:100%;font-size: 80%;border-collapse:collapse;text-align:center;line-height:1.5em;border-left:1px solid #ccc;table-layout:fixed;}
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
        padding: 6px 12px;
    }
    .btn-left{
        float:left;
    }
    .btn-right{
        float:right;
    }
    .btn-left .ui-button-text, .btn-right .ui-button-text{
        padding: 4px 10px;
    }
    .btn-confirm{
        float:right;
    }
    .btn-confirm .ui-button-text{
        padding: 8px 20px;
    }
    #btn-entry .ui-button-text, #btn-clear .ui-button-text{
        padding:10px 20px;
        color:aqua;
    }
    #confirm-entry{
        color:aqua;
        margin-right:5px;
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
                    location.href="/RFIDReport/RFIDRecycle?plant=野洲工場";
                    $( this ).dialog( "close" );
                }},{ text:"山崎工場",click :function(ev) {
                    location.href="/RFIDReport/RFIDRecycle?plant=山崎工場";
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
        // $("#btn-scanqr").button("disable");

        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width:$(window).width()-40,
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
        
        $( "#担当者" ).click(function( event ) {
            $( "#dialog_user" ).dialog( "open" );
            event.preventDefault();
        });

        $("#itemcode").on("change",function(e){
            var ip_text = null;
            // console.log(e)
            $('#product_list').find('option[value="'+e.target.value+'"]').filter(function(){
                // console.log(this);
                $("#item_name_lable").val(this.attributes.text.value);
                if($("#id_type").val()=="仕掛ID"){
                    $("#around_num").val(this.attributes.roundnum.value);
                }else if($("#id_type").val()=="完成ID"){
                    $("#around_num").val(this.attributes.fprnum.value);
                }else{
                    $("#around_num").val("");
                }
            })
        })

        goStart();

        var timer = false;
        $(window).resize(function() {
            if (timer !== false) {
                clearTimeout(timer);
            }
            timer = setTimeout(function() {
                resize_item_list();
            }, 100);
        });
        resize_item_list();

    });

    function resize_item_list(){
        let wh = $(window).height();
        let mh = $("#info_menu").height();
        let ilh = wh-mh-90;
        $("#item_list").height(ilh);
    }


    function goStart(){
        if($("#担当者").val()==""){
            $( "#dialog_user" ).dialog( "open" );
        }else{
            od('QRコード','RFID');
        }
    }

    function updateConfirm(){

        var name_list = ["no","rfid"];
        var post_data = get_table_val("item_add",name_list);
        // console.log(post_data);

        if(post_data.length==0){
            alert("QRスキャンしてください。");
            return;
        }

        var c={};
        c["担当者"]=($("#担当者").val());
        c["区分"]=$("#id_type").val();
        c["品目コード"]=$("#itemcode").val();
        c["まるめ数"]=$("#around_num").val();
        let err = false;
        let msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
        $.each(c,function(k,v){
            if(v == ""){
                err = true;
                msg+="<li>"+k+"</li>\n";
            }
        });

        if(err){
            openAlert("確認",msg)
            return false;
        }

        let btn = [{ class:"btn-confirm",text:"キャンセル",click :function(ev) {
            $( this ).dialog( "close" );
        }},{ class:"btn-confirm confirm-entry",text:"確定",click :function(ev) {
            $( this ).dialog( "close" );
            updateListRfid(post_data);
        }}];
        openAlert("確認！！！","変更内容を登録しますか？",btn);
        $(".confirm-entry").css({"color":"aqua"});
    }

    async function updateListRfid(post_data){
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "",
            data: {
                ac:"update",
                d:{
                    user:$("#担当者").val(),
                    plant_name:plant_name,
                    plant_id:plant_id,
                    list_item:post_data,
                    new_id_type:$("#id_type").val(),
                    new_itemcode:$("#itemcode").val(),
                    new_around_num:$("#around_num").val(),
                    new_user:$("#担当者").val(),
                }
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                if(d=="OK"){
                    openAlert("完了！","登録しました。");
                    clearAll(true);
                }else{
                    openAlert("登録できません。",d);
                }
            }
        });
    }

    function get_table_val(id,name_list){
        const table = $("."+id); 
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
                ac:"getItem",
                rfid:rfid
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                // console.log(d);
                if(d[0]=="NG"){
                    if(video){
                        video.pause();
                    }
                    let msg="<p>RFID：<span style='font-weight:bold;'>"+rfid+"</span></p>"+d[1];
                    // msg+="<p>30秒以内でこのIDを再読み込み出来ません。</p>"
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
                    // console.log(item_info)
                    add_item(item_info);
                    // if($("#sum_num_"+item_info.wim_itemcode).html()!=""){
                    //     sum_num = parseInt($("#sum_num_"+item_info.wim_itemcode).html())+parseInt(item_info.complete_num);
                    // }else{
                    //     sum_num = parseInt(item_info.complete_num);
                    // }
                }
            }
        });

    }

    function add_item(item_info){
        let no = $(".row_no_"+item_info.wim_itemcode).length+1;
        $("#sheet_num_"+item_info.wim_itemcode).html(no);

        if(!list_itemcode.includes(item_info.wim_itemcode)){
            list_itemcode.push(item_info.wim_itemcode);
            let add_table = "";
            //梱包作業
            add_table=`<p style="margin-left:10px;font-weight:bold;color:#153d73">品名：`+item_info.itemname+`　枚数：<label id="sheet_num_`+item_info.wim_itemcode+`">`+no+`</label></p>
            <table id="pick_table_item" style="">
                <thead>
                    <tr >
                        <th style="width:30px;">No.</th>
                        <th style="width:260px;">RFID</th>
                        <th style="width:100px;">区分</th>
                        <th style="width:100px;">連携製品</th>
                        <th style="width:100px;">まるめ</th>
                        <th style="width:100px;">状態</th>
                        <th style="width:100px;">作業者</th>
                        <th style="width:160px;">作成時間</th>
                    </tr>
                </thead>
                <tbody id="item_add_`+item_info.wim_itemcode+`" class="item_add">
                </tbody>
            </table>`;
            $("#item_list").prepend(add_table);
        }

        setTimeout(() => {
            let add_item = "";
            add_item+=`<tr class="added_row">
                <td class="row_no_`+item_info.wim_itemcode+`" >`+no+`</td>
                <td class="rfid" style="font-weight:bold;">`+item_info.rfid+`</td>
                <td class="" style="">`+item_info.wim_class+`</td>
                <td>`+item_info.wim_itemcode+`</td>
                <td>`+item_info.wim_number+`</td>
                <td>`+item_info.wim_status+`</td>
                <td class="" >`+item_info.wim_username+`</td>
                <td class="" >`+item_info.wim_created_at+`</td>
                `;
            add_item+=`</tr>`;
            $("#item_add_"+item_info.wim_itemcode).prepend(add_item);
            $("button").button();
        }, 0);

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

    function qr_input(ac){
        var msg="<label for='QRコード'>QRコード：</label><input id='QRコード' type='text' value='' placeholder='QRコード' autocomplete='off'  style='width:450px;font-size:16px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='QRコード'>QRコード：</label><input id='QRコード' type='tel' pattern='[0-9]*' value='' placeholder='QRコード' autocomplete='off'  style='width:450px;font-size:16px;'/>";
        }
        var options = {"title":"RFIDのQRコードを入力",
            position:["center",100],
            width: 600,
            buttons: [
                { class:"btn-right",text:"カメラで入力",click :function(ev) {
                    $("#alert").dialog( "close" );
                    od('QRコード','QRコード');
                }},
                { class:"btn-right",text:"OK",click :function(ev) {
                    var code = $("#QRコード").val();
                    if(code!==""){
                        getItemList(code);
                        $( this ).dialog( "close" );
                    }else{
                        alert("QRコードを入力してください。")
                    }
                }},
                { class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }}
            ]
        };
        $("#message").html(msg);
        
        $("#QRコード").on('keyup', function(e) {
            if(e.which == 13){
                $("#alert").dialog( "close" );
                getItemList($("#QRコード").val());
            }
        });

        $( "#alert" ).dialog( "option",options);
        $("#alert").dialog( "open" );

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
            $("#"+id).removeAttr('readonly');
            stop_scan();
            if(id!=="documentno"){
                qr_input();
            }
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
                        //RFIDスキャン
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
                goStart();
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
        username = name;
        window.history.pushState('', 'Title', '?plant='+plant+'&user='+name);
        goStart();
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
            if(username!=""){
                $("#担当者").val(username);
                // goStart();
            }else{
                $( "#dialog_user" ).dialog( "open" );
            }
        }
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
        $("#message2").html(msg);
        $( "#alert2" ).dialog( "option",options);
        $( "#alert2" ).dialog( "open" );
        return false;
    }


</script>

<fieldset id="info_menu" style="float: left;margin: 5px;border-radius: 6px;">
    <legend style="font-weight:bold;margin: 0 15px;">変更内容</legend>
    <div id="ip_section" style="float: left;padding: 0 0 4px 0;">
        <div style="float:left;margin:0 5px 0 10px;text-align:center;">
            <p><label for="id_type" style="margin-right:3px;font-weight:bold;vertical-align: middle;">区分</label></p>
            <select id='id_type' class="main-ip" value ='' style='text-align:center;width:90px;height:34px;font-weight:bold;font-size: 100%;' >
                <option value=""></option>
                <option value="仕掛ID">仕掛ID</option>
                <option value="完成ID">完成ID</option>
            </select>
        </div>
        <div style="float:left;margin:0 5px;text-align:center;">
            <p><label for="itemcode" style="margin-right:3px;font-weight:bold;vertical-align: middle;">品目コード</label></p>
            <input id='itemcode' class="main-ip" list="product_list" value ='' autocomplete="on" style='text-align:center;width:150px;' />
            <datalist id="product_list">
                <?php foreach($product_list as $ilk=>$ilv){ ?>
                    <option value="<?=$ilv["itempprocord"]?>" text="<?=$ilv["itemname"]?>" roundnum="<?=$ilv["round_num"]?>" fprnum="<?=$ilv["fpr_num"]?>"><?=$ilv["itempprocord"]?></option>
                <?php } ?>   
            </datalist>
        </div>
        <div style="float:left;margin:0 5px;text-align:center;">
            <p><label for="item_name_lable" style="margin-right:3px;font-weight:bold;vertical-align: middle;">品名</label></p>
            <input id='item_name_lable' class="main-ip" value ='' style='text-align:left;width:320px;' readonly="readonly" />
        </div>
        <div style="float:left;margin:0 5px;text-align:center;">
            <p><label for="around_num" style="margin-right:3px;font-weight:bold;vertical-align: middle;">まるめ数</label></p>
            <input id='around_num' class="main-ip" value ='' style='text-align:center;width:80px;' />
        </div>
        <!-- <button id="btn-entry" type="button" onclick="entrySetID();" class="" style="float:right;margin:8px 10px;">設定</button> -->
        <div style="float:right;margin:0 10px 0 5px;text-align:center;">
            <p><label for="担当者">担当者</label></p>
            <input type="text" class="main-ip" value="" name="担当者" id="担当者" readonly="readonly" style="width:150px;text-align:center;"/>
        </div>
    </div>
    <button id="btn-entry" type="button" onclick="updateConfirm();" class="" style="float:right;margin:14px 15px 10px 20px;">登録</button>
    <!-- <button id="btn-clear" type="button" onclick="clearAll();" class="" style="float:right;margin-left:20px;">クリア</button> -->

</fieldset>

<div style="float:right;margin: 15px 10px 0 0;">
    <button id="btn-scanqr" type="button" onclick="od('QRコード','RFID');" class="" style="padding:4px;" >QRスキャン</button>
</div>

<div style="clear:both;"></div>
<!-- <p>RFIDリスト</p> -->
<div id="item_list" style="overflow: scroll;margin:5px;padding:4px;"></div>
<div style="clear:both;"></div>

<!-- 
<div style="display:inline-block;float:right;margin-right:10px;">
    <button id="btn-entry" type="button" onclick="entryConfirm();" class="" style="float:right;">登録</button>
    <button id="btn-clear" type="button" onclick="clearAll();" class="" style="float:right;margin-left:20px;">クリア</button>
</div> -->

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