<?php
    use_javascript("jsQR.min.js");
    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");

    slot('h1', '<h1 class="header-text" style="margin:0 auto;"> カンバン再印刷 | Nalux <span id="mode_view"></span> </h1>');
    $btn = '<select id="sPlant" type="text" value="" placeholder="" autocomplete="off" style="float:left;margin-left:10px;padding:0 4px;border:1px solid #8c8c8c;background-color: #FFF;border-radius: 4px;z-index:1000;" >
        <option value="山崎工場">山崎工場</option>
        <option value="野洲工場">野洲工場</option>
    </select>';
    // <button type="button" class="btn-header" onclick="change_stock()">トレー/束</button>
    slot('cd',$btn);
 ?>

<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

<style type="text/css">
    #canvas{
        max-width:900px;
    }
    .ui-dialog{
        max-width:900px;
    }
    table.type04 {width:99%;font-size:16px;border-collapse:collapse;text-align:center;line-height:1.5em;border-top:1px solid #ccc;border-left:2px solid #369;table-layout:fixed;margin: auto;}
    table.type04 th {padding:3px;height:24px;font-weight:bold;vertical-align:top;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table.type04 td {padding:3px;height:24px;vertical-align:top;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;white-space: nowrap;}
    table.type04 input {border:none;width:100%;font-size:16px;}
    #btn-scanqr .ui-button-text{
        padding: 4px 12px;
    }
    .ui-dialog{background:#f9f9f9 !important;}

    .ui-dialog-buttonset{
        width:100%;
    }
    .dialog_btn {
        padding-right: 20px;
    }
    .btn-left{
        float:left;
    }
    .btn-right{
        float:right;
    }
</style>

<script type="text/javascript">
    var video,canvasElement,canvas =null;
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    var printer_ip = JSON.parse(localStorage.getItem("sPrinter"));

    $(document).ready(function(){

        var plant = "<?=$sf_request->getParameter('plant')?>";
        if(plant){
            $("#sPlant").val(plant);
        }
        $("button").button();
        $("#sPlant").on("change",function(e){
            location.href="/RFIDReport/RePrintKanban?plant="+e.target.value;
            return;
        });
        let qr_read_width = $(window).width()/1.5;
        $("#canvas").width(qr_read_width);
        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: qr_read_width,
            autoOpen: false,
            modal:true,
            position:["right",30],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });

        $( "#openAlert" ).dialog({
            autoOpen: false,
            width:600,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });

        $("#qr_rfid").on("keyup",function(e){
            if(e.keyCode===13){
                getPrintData(e.target.value)
            }
        });

        $(".ui-resizable-handle").hide();
        $(".ui-dialog-titlebar-close").hide();

    });

    function getPrintData(rfid){
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "RePrintKanban",
            data: {
                ac:"kanban_reprint",
                rfid:rfid
            },
            dataType: "json",
        })
        .done(function(d,ts){
            // console.log(d);
            // console.log(ts);
            if(d.length>0){
                // openAlert("完了","「<b>"+print_name+"</b>」プリンターで<b>再印刷</b>完了!");
                let add_item = "";
                let added_flag = false;
                $.each(d,function(a,b){
                    if(b.hgpd_process.indexOf("成形")>-1 || b.hgpd_process.indexOf("完成")>-1 ){
                        add_item+=`<tr id="row_`+b.hgpd_id+`" class="added_row">
                            <td style="text-align:center;"><input type="checkbox" class="print_check" value="`+b.hgpd_id+`" /></td>
                            <td>`+b.hgpd_id+`</td>
                            <td class="rfid" style="font-weight:bold;">`+b.hgpd_rfid+`</td>
                            <td>`+b.hgpd_itemcode+`</td>
                            <td>`+b.hgpd_process+`</td>
                            <td>`+b.hgpd_moldlot+`</td>
                            <td title="`+b.hgpd_moldday+`">`+b.hgpd_moldday+`</td>
                            <td>`+b.hgpd_itemform+`</td>
                            <td>`+b.hgpd_cav+`</td>
                            <td>`+b.hgpd_qtycomplete+`</td>
                            <td>`+b.hgpd_name+`</td>
                            <td>`+b.hgpd_start_at.substr(0,b.hgpd_start_at.length-3)+`</td>
                            <td>`+b.hgpd_stop_at.substr(0,b.hgpd_stop_at.length-3)+`</td>
                            <td>`+b.hrut_serial_num+`</td>
                            <td style="display:none;">`+b.dateto4+`</td>
                            <td style="display:none;">`+b.mold_datetime_print+`</td>
                            <td style="display:none;">`+b.mold_serial_print+`</td>`;
                        add_item+=`</tr>`;
                        added_flag = true;
                    }
                });
                $("#table_content").append(add_item);
                $(".print_check").on("change",function(e){
                    if(e.target.checked){
                        $("#row_"+e.target.value).css({"background":"#c4e9ff"});
                    }else{
                        $("#row_"+e.target.value).css({"background":"#FFF"});
                    }
                });
                if(!added_flag){
                    openAlert("確認","印刷工程データが無いです。");
                }
            }else{
                openAlert("確認","データが無いです。");
            }
        })
        .fail(function(jqXHR,ts,err){
            console.log("fail");
            console.log(jqXHR);
            console.log(ts);
            console.log(err);
        }).always(function(){
            loadingView(false);
        });
    }

    function reprint_post(){
        var name_list = ["check_val","hgpd_id","rfid","itemcode", "process","moldlot","moldday","form","cav","inv_num","user","start_at","stop_at","serial_num","dateto4","mold_datetime_print","mold_serial_print"];
        var get_data = get_table_val("item_add",name_list);
        var printer_ip = $("#print_ip").val();
        if(printer_ip==""){
            openAlert("確認","プリンターを選択してください。");
            return;
        }

        var checked_obj = $(".print_check");
        var checked_list = [];
        $.each(checked_obj, function (a, b) { 
            if(b.checked===true){
                checked_list.push(b.value);
            }
        });

        var print_data = [];
        var process="";
        //4桁ロット印刷判定 ⁼ judge_p = 0(印刷品目), 1(印刷無し）
        var judge_p = 0;
        var judge_cmt_dt = 0;
        var judge_cmt_serial = 0;
        var printed = "";
        $.each(get_data, function (k, v) { 
            if($.inArray(v.hgpd_id,checked_list)>-1 && printed != v.hgpd_id){
                printed = v.hgpd_id;
                this_serial = "";
                // if()
                print_data.push({
                    form:v.form,
                    cav:v.cav,
                    lot:v.moldlot,
                    id:v.hgpd_id,
                    m_date:v.moldday,
                    start_at:v.start_at,
                    stop_at:v.stop_at,
                    serial_num:v.serial_num,
                });
                process=v.process;
                judge_p=v.dateto4;
                judge_cmt_dt = v.mold_datetime_print;
                judge_cmt_serial = v.mold_serial_print;
            }
        });

        if(print_data.length==0){
            openAlert("確認","再印刷のカンバンをチェックしてください。");
            return;
        }
        
        // console.log(print_data);

        //完成かんばん、仕掛かんばん判定 : judge_card_type =1(仕掛り）＝０（完成)
        var judge_card_type = 0;
        if(process.indexOf("成形")>-1){
            judge_card_type = 1;
        }else{
            judge_p=0;
            judge_cmt_dt=0;
            judge_cmt_serial=0;
        }
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "/RFIDReport/Printkanban?ac=print",
            data: {
                d:{
                    ip:printer_ip,
                    judge_p:judge_p,
                    judge_card_type:judge_card_type,
                    judge_cmt_dt:judge_cmt_dt,
                    judge_cmt_serial:judge_cmt_serial,
                    kanban:print_data
                }
            },
            dataType: "html"
        })
        .done(function(res){
            if(res=="OK"){
                openAlert("完了","再印刷完了！")
            }
        })
        .fail(function(res, textStatus, errorThrown){
            console.log("fail");
            console.log(res);
            console.log(textStatus);
            console.log(errorThrown);
        })
        .always(function(res, textStatus, errorThrown){
            loadingView(false);
        })
    }

    // カメラを起動
    function od(id,name,fcmode){
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        $("#"+id).attr('readonly', 'readonly');
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:600 }}).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            tick(id);
        });
        
        let button = [
            { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
                qr_reader="scaner";
                stop_scan();
                $("#"+id).removeAttr('readonly');
                $("#"+id).focus();
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
            }},
            { class:"btn-left",html:"<span class='dialog_btn ui-icon-delete ui-btn-icon-right'>閉じる</span>",click :function() {
                stop_scan();
            }}
        ];
        var options = {"title":name+"をスキャンしてください。",
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        $("#QRScan").dialog( "open" );
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
                        list_code_scaned.push(code.data);
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                        playMelody(2200);
                        await sleepy(300);
                        $("#"+id).val(code.data);
                        $("#"+id).attr('readonly', 'readonly');
                        getPrintData(code.data);
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
        $( "#openAlert" ).dialog( "option", options);
        $( "#openAlert" ).dialog( "open" );
        return false;
    }

    function closeTab(){
        window.close();
    }

</script>

<div id="info_menu" style="display: block;float:left;margin-bottom:5px;width: 100%;" >
    <div class="ipbox" style="float:left;margin:4px 10px;">
        <label for='print_ip' style="font-weight:bold;">プリンター名</label>
        <select id='print_ip' type='text' value='' placeholder='' autocomplete='off'  style='width:145px;text-align:center;height:30px;border:1px solid #8c8c8c;border-radius: 4px;background: #FFF;' >
            <option value="">－選択－</option>
            <?php foreach($printer_list as $key=>$value){ ?>
                <option value="<?=$value["wim_ipaddr"]?>"><?=$value["wim_res_name"]?></option>
            <?php } ?>
        </select>
    </div>
    <div class="ipbox" style="float:left;margin:4px 10px;">
        <label for='qr_rfid' style="font-weight:bold;">QRコード</label>
        <input id='qr_rfid' type='tel' pattern='[0-9]*' value='' placeholder='' autocomplete='off'  style='width:400px;height:30px;' />
        <button id="btn-scanqr" type="button" onclick="od('qr_rfid','QRコード');" class="" style=""><span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
    </div>
</div>
<div id="rfid_view">
    <table class='type04' style="font-size:90%;" >
        <thead style="background:#e8e8e8;">
            <th style="width:30px;">印刷</th>
            <th style="width:70px;">実績ID</th>
            <th class="td-rfid" style="width:270px;">RFID</th>
            <th style="width:100px;">品目</th>
            <th style="width:100px;">工程</th>
            <th style="width:60px;">ロット</th>
            <th style="width:80px;">成形日</th>
            <th style="width:30px;">型</th>
            <th style="width:45px;">キャビ</th>
            <th style="width:40px;">数量</th>
            <th style="width:80px;">担当者</th>
            <th style="width:140px;">開始時間</th>
            <th style="width:140px;">終了時間</th>
            <th style="width:60px;">通しNo.</th>
        </thead>
        <tbody id='table_content' class='item_add'>
        </tbody>
    </table>
</div>
<div style="clear:both;"></div>
<button id="btn-close" type="button" onclick="closeTab();" class="" style="float:left;margin:10px 5px;">閉じる</button>
<button id="btn-print" type="button" onclick="reprint_post();" class="" style="float:right;margin:10px 5px;">再印刷</button>

<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <div id="scan_msg" style="position:absolute;bottom:0;right:0;color:red;text-shadow: 1px 0 #fff, -1px 0 #fff, 0 1px #fff, 0 -1px #fff,1px 1px #fff, -1px -1px #fff, 1px -1px #fff, -1px 1px #fff;"></div>
    <script type="text/javascript">
    </script>
</div>

<div id="openAlert">
    <div id="msg" style="text-align: center;"></div>
</div>



