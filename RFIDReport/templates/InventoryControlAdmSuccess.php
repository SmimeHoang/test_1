<?php
    use_javascript("jsQR.min.js");
?>

<meta name="viewport" content="user-scalable=no">
<style type="text/css">
    
    #item_list{
        border: 1px solid blue;
    }
    input,label{
        font-size:18px;
        height:30px;
        font-weight:bold;
    }
    label {
        margin: 4px 0 0 0;
    }
    p{
        padding: 4px 0;
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
</style>

<script type="text/javascript">
    var video,canvasElement,canvas;
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    var mode = "<?=$sf_params->get("mode")?>";
    var ship_list=[];
    if(mode=="picking"){
        ship_list = <?php echo htmlspecialchars_decode($ship_list); ?>;
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
                    location.href="/RFIDReport/InventoryControlAdm?plant=野洲工場&mode="+mode;
                    $( this ).dialog( "close" );
                }},{ text:"山崎工場",click :function(ev) {
                    location.href="/RFIDReport/InventoryControlAdm?plant=山崎工場&mode="+mode;
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

        if(mode=="packing"){
            body=`
            <div id="info_menu">
            <p style="display: block;">
                <button id="btn-start" type="button" onclick="goStart();" class="" style="float:left;">開始</button>
                <label style="float:left;margin-left:10px;">開始：</label><input id="ip_start_time" class="check_ip" type="datetime-local" style="float:left;margin-right:15px;" ></input>
                <label for="担当者">担当者：</label><input type="text" value="" name="担当者" id="担当者" readonly="readonly" style="width:180px;"/>
                <button id="btn-scanqr" type="button" onclick="od('QRコード','完成品QR');" class="" style="float:right;">完成品QR</button>
            </p>
            </div>
            <div style="clear:both;"></div>
            <div id="item_list" style="overflow: scroll;margin:5px 0;"></div>
            <div style="clear:both;"></div>
            <div id="digital_area" style="display:none;height: 40px;" ><label>標準類：</label></div>
            <div style="display:inline-block;float:right;">
                <button id="btn-entry" type="button" onclick="entryConfirm();" class="" style="float:right;">登録</button>
                <button id="btn-clear" type="button" onclick="clearAll();" class="" style="float:right;margin-left:20px;">クリア</button>
                <div style="display:inline-block;margin-top:5px;">
                    <label for="" style="float:right;">分</label>
                    <input id="ip_work_time" class="check_ip" type="number" style="float:right;margin-left:10px;width:60px;text-align:right;" ></input>
                    <input id="ip_end_time" class="check_ip" type="datetime-local" style="float:right;" ></input>
                    <label for="ip_end_time" style="float:right;margin-left:10px;">終了:</label>
                    <button id="btn-end" type="button" onclick="goEnd();" class="" style="float:right;">終了</button>
                </div>
            </div>`;
            $("#main-left").html(body);
            $("#header h1").html(plant_name+" | 梱包作業実績入力 DAS | Nalux ");
        }else if(mode=="picking"){
            body=`
            <div id="info_menu">
            <p style="display: block;">
                <label style="float:left;">作業日：</label><input id="ip_start_time" class="check_ip" type="date" value="" onchange="changeDate()" style="float:left;margin-right:15px;" ></input>
                <label for="担当者">担当者：</label><input type="text" value="" name="担当者" id="担当者" style="width:180px;"/>
                <button id="btn-scanqr" type="button" onclick="od('QRコード','完成品QR');" class="" style="float:right;">完成品QR</button>
            </p>
            <div style="clear:both;"></div>
            <p style="float:left;">
                <label>出荷伝票コード：</label><input id="documentno" class="check_ip" list="ship_list" value="" style="width:305px;margin-right:10px;" ></input>`;
            body+=`<button id="btn-bancode" type="button" onclick="od('documentno','伝票のQRコード');" class="" >伝票QR</button>`;
            // body+=`<datalist id="ship_list">`;
            //     $.each(ship_list,function(k,v){
            //         let vl = v.movementdate.substring(0,10)+`__`+v.pl_name+`__`+v.bp_value;
            //         body+=`<option class="order-select" label="`+vl+`" value="`+vl+`" >`+v.documentno+`</option>`;
            //     })
            // body+=`</datalist>`;
           body+=`</p>
           <div style="clear:both;"></div>
            <p>
                <label>出荷日：</label><input id="movement_date" class="check_ip" value="" style="width:100px;" readonly="readonly" ></input>
                <label style="margin-left: 18px;">得意先コード：</label><input id="wica_code" class="check_ip" value="" style="width:120px;" readonly="readonly" ></input>
                <label style="margin-left: 18px;">出荷先：</label><input id="wica_delivery" class="check_ip" value="" style="width:calc(100% - 550px);" readonly="readonly" ></input>
            </p>
            </div>

            <div style="clear:both;"></div>
            <div id="item_list" style="overflow: scroll;margin:5px 0;">
            </div>
            <div style="clear:both;"></div>
            <button id="btn-clear" type="button" onclick="clearAll();" class="" style="float:left;">クリア</button>
            <button id="btn-entry" type="button" onclick="entryConfirm();" class="" style="float:right;">登録</button>`;
            $("#main-left").html(body);
            $("#header h1").html(plant_name+" | 出荷リスト紐づけ DAS | Nalux ");
        }

        if(username!=""){
            $("#担当者").val(username);
        }
        $("button").button();
        $("#btn-scanqr").button("disable");

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
        let dh = $("#digital_area").height();
        let ilh = wh-mh-110;
        if(mode=="packing"){
            ilh = wh-mh-150;
        }
        $("#item_list").height(ilh);
    }

    function get_data(dataAry, key, value) {
        var result = $.grep(dataAry, function (e) {
            return e[key] == value;
        });
        return result;
    }

    function checkCode(){
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getShipping",
                documentno:$("#documentno").val()
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                console.log(res);
                if(res){
                    checkInput();
                    $("#movement_date").val(res.movementdate.substr(0,10));
                    $("#wica_delivery").val(res.pl_name);
                    $("#wica_code").val(res.bp_value);
                    // $.each($(res),function(k,v){
                    //     if(v.documentno==e.target.value || v.documentno==e.target.value){
                    //         $("#movement_date").val(v.movementdate.substr(0,10));
                    //         $("#wica_delivery").val(v.pl_name);
                    //         $("#wica_code").val(v.bp_value);
                    //         // if(e.type=="input"){
                    //         //     $("#documentno").val(v.text);
                    //         // }
                    //     }
                    // });
                    $("#btn-start").focus();
                }else{
                    list_code_scaned = list_code_scaned.filter(function(elem){
                        return elem != $("#documentno").val(); 
                    });
                    let msg="伝票番号が見つかりません。";
                    var options = {"title":"確認してください！！！",
                        width: 600,
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                                $("#movement_date").val("");
                                $("#wica_delivery").val("");
                                $("#wica_code").val("");
                                $("#documentno").select();
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

    function changeDate(){
        checkInput();
    }

    function goStart(){
        if($("#担当者").val()==""){
            $( "#dialog_user" ).dialog( "open" );
        }else{
            if(mode=="packing"){
                $("#btn-scanqr").button("enable");
                $("#btn-start").button("disable");
                $("#ip_start_time").val(nowDT("dt"));
                od('QRコード','完成品QR');
            }else if(mode=="picking"){
                if(!video){
                    od('documentno','伝票のQRコード');
                }
                $("#ip_start_time").val(nowDT("date"));
                checkInput();
            }
        }
    }

    function checkInput(){
        if($("#担当者").val()==""){
            $( "#dialog_user" ).dialog( "open" );
        }else if($("#documentno").val()==""){
            $("#documentno").focus();
            if(!video){
                od('documentno','伝票のQRコード');
            }
        }else{
            $("#btn-scanqr").button("enable");
            od('QRコード','完成品QR');
        }
    }

    function goEnd(){
        $("#ip_end_time").val(nowDT("dt"));
        let sdt = new Date($("#ip_start_time").val());
        let edt = new Date($("#ip_end_time").val());
        let work_time = (edt.getTime()-sdt.getTime())/(60*1000);    //min
        // console.log(edt.getTime())
        // console.log(sdt.getTime())
        // console.log(work_time)
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
    var digital_item_list = [];
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
                    let sum_num = 0;
                    if($("#sum_num_"+item_info.wic_itemcode).html()!=""){
                        sum_num = parseInt($("#sum_num_"+item_info.wic_itemcode).html())+parseInt(item_info.complete_num);
                    }else{
                        sum_num = parseInt(item_info.complete_num);
                    }
                    $("#sum_num_"+item_info.wic_itemcode).html(sum_num);
                    let no = $(".row_no_"+item_info.wic_itemcode).length+1;
                    $("#sheet_num_"+item_info.wic_itemcode).html(no);

                    if(!list_itemcode.includes(item_info.wic_itemcode)){
                        list_itemcode.push(item_info.wic_itemcode);
                        let add_table = "";
           
                        if(mode=="picking"){
                            //出荷紐づけ
                            add_table=`<p style="margin-left:10px;font-weight:bold;color:#153d73">品名：`+item_info.tag_name+`　枚数：<label id="sheet_num_`+item_info.wic_itemcode+`">`+no+`</label>　合計：<label id="sum_num_`+item_info.wic_itemcode+`" style="color:green;">`+item_info.complete_num+`</label></p>
                            <table id="pick_table_item" style="">
                                <thead>
                                    <tr >
                                        <th style="width:30px;">No.</th>
                                        <th style="width:265px;">完成QR</th>
                                        <th>品名</th>
                                        <th style="width:100px;">品目コード</th>
                                        <th style="width:30px;">型番</th>
                                        <th style="width:40px;">キャビ</th>
                                        <th style="width:50px;">数量</th>
                                        <th style="width:100px;">倉庫</th>
                                    </tr>
                                </thead>
                                <tbody id="item_add_`+item_info.wic_itemcode+`" class="item_add">
                                </tbody>
                            </table>`;
                        }else{
                            //梱包作業
                            add_table=`<p style="margin-left:10px;font-weight:bold;color:#153d73">品名：`+item_info.tag_name+`　枚数：<label id="sheet_num_`+item_info.wic_itemcode+`">`+no+`</label>　合計：<label id="sum_num_`+item_info.wic_itemcode+`">`+item_info.complete_num+`</label></p>
                            <table id="pick_table_item" style="">
                                <thead>
                                    <tr >
                                        <th style="width:30px;">No.</th>
                                        <th style="width:260px;">完成QR</th>
                                        <th>品名</th>
                                        <th style="width:100px;">品目コード</th>
                                        <th style="width:30px;">型番</th>
                                        <th style="width:45px;">キャビ</th>
                                        <th style="width:60px;">数量</th>
                                        <th style="width:80px;">時間(分)</th>
                                    </tr>
                                </thead>
                                <tbody id="item_add_`+item_info.wic_itemcode+`" class="item_add">
                                </tbody>
                            </table>`;
                        }
                        $("#item_list").prepend(add_table);
                    }
       
                    let add_item = "";

                    add_item+=`<tr class="added_row">
                        <td class="row_no_`+item_info.wic_itemcode+`" >`+no+`</td>
                        <td class="rfid" style="font-weight:bold;">`+item_info.wic_rfid+`</td>
                        <td style="display:none;">`+item_info.itemname+`</td>
                        <td>`+item_info.tag_name+`</td>
                        <td>`+item_info.wic_itemcode+`</td>
                        <td>`+item_info.wic_itemform+`</td>
                        <td>`+item_info.itemcavs+`</td>
                        <td style="font-weight:bold;">`+item_info.complete_num+`</td>`;
                        if(mode=="packing"){
                            add_item+=`<td id="work_time_`+item_info.wic_rfid+`" class="work-time" ></td>`
                        }else if(mode=="picking"){
                            add_item+=`<td class="" >出荷準備完了</td>`
                        }
                        add_item+=`<td style="display:none;">`+item_info.hgpd_ids+`</td>`;
                        add_item+=`<td style="display:none;">`+item_info.wic_ids+`</td>`;
                        add_item+=`<td style="display:none;">`+item_info.wic_wherhose+`</td>`;
                        add_item+=`<td style="display:none;">`+item_info.wic_process_key+`</td>`;
                        add_item+=`<td style="display:none;">`+item_info.hgpd_complete_id+`</td>`;
                    add_item+=`</tr>`;
                    $("#item_add_"+item_info.wic_itemcode).prepend(add_item);
                    if(mode=="packing" && !digital_item_list.includes(item_info.wic_itemcode)){
                        digital_item_list.push(item_info.wic_itemcode);
                        $("#digital_area").append("<button id='digital_"+item_info.wic_itemcode+"' class='digital_btn' onclick='Std_digitization(`digital_"+item_info.wic_itemcode+"`,`"+item_info.wic_itemcode.substr(0,4)+"_"+item_info.wic_itemcode+"_梱包.pdf`,true);' >"+item_info.tag_name+"</button>");
                        //チェックURL
                        Std_digitization("digital_"+item_info.wic_itemcode,item_info.wic_itemcode.substr(0,4)+"_"+item_info.wic_itemcode+"_梱包.pdf",false);
                    }
                    $("#digital_area").show();
                    $("button").button();
                    $(".disable_digital").button("disable");
                    setTimeout(() => {
                        // od('QRコード','完成品QR');
                        $("#btn-entry").button("enable");
                    }, 1000);
                }
            }
        });

    }

    function Std_digitization(id,val,open_flag){
        let folder = "/files/yasu-fs01/DAS連携/PDFデータ/pdf標準類/";
        let uri = folder+encodeURIComponent(val);
        if(open_flag===true){
            window.open(uri);
            return;
        }
        $.ajax({
            url: uri, 
            type: 'GET',  
            statusCode: {
                404: function(stt) {
                    console.log("404 Not Found.\nThe requested URL was not found on this server.");
                    // alert("404 Not Found.\nThe requested URL was not found on this server.");
                    $("#"+id).button("disable");
                },
                200: function(stt){
                    console.log("Digital Check: OK");
                }
            }
        }).done(function() {
            if(open_flag && open_flag===true) window.open(uri);
        });
    } 

    function addTest(){
        let d={
            wic_rfid:"test_ship",
            itemname:"Test B",
            tag_name:"Test B",
            wic_itemcode:"1486",
            wic_itemform:"100",
            complete_num:"200",
            wic_ids:"1,2",
            wic_wherhose:"置き場A",
            wic_itemcav:"1",
            wic_process_key:"0"
        }
        let no = $(".row_no").length+1;
        let add_item = "";
        add_item+=`<tr class="added_row">
            <td class="row_no" >`+no+`</td>
            <td class="rfid">`+d.wic_rfid+`</td>
            <td style="display:none;">`+d.itemname+`</td>
            <td>`+d.tag_name+`</td>
            <td>`+d.wic_itemcode+`</td>
            <td>`+d.wic_itemform+`</td>
            <td>`+d.complete_num+`</td>`;
            if(mode=="packing"){
                add_item+=`<td id="work_time_`+d.wic_rfid+`" class="work-time" ></td>`
            }else if(mode=="picking"){
                add_item+=`<td class="" >出荷準備完了</td>`
            }
            add_item+=`<td style="display:none;">`+d.hgpd_ids+`</td>`;
            add_item+=`<td style="display:none;">`+d.wic_ids+`</td>`;
            add_item+=`<td style="display:none;">`+d.wic_wherhose+`</td>`;
            add_item+=`<td style="display:none;">`+d.wic_itemcav+`</td>`;
            add_item+=`<td style="display:none;">`+d.wic_process_key+`</td>`;
        add_item+=`</tr>`;
        $("#item_add").prepend(add_item);
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

    async function getPickingEntry(){
        var name_list = ["no","complete_code","itemname", "tag_name","itemcode","formnum","itemcav","num","remark","hgpd_ids","wic_ids","wic_wherhose","wic_process_key","hgpd_complete_id"];
        var post_data = get_table_val("item_add",name_list);
        console.log(post_data);
        if(post_data.length==0){
            alert("QRスキャンしてください。");
            return;
        }
        // return;
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=PickingEntry",
            data: {
                d:{
                    documentno:$("#documentno").val(),
                    movement_date:$("#movement_date").val(),
                    wica_delivery:$("#wica_delivery").val(),
                    wica_code:$("#wica_code").val(),
                    user:$("#担当者").val(),
                    list_item:post_data,
                }
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                $("#btn-entry").button("disable");
                $("#btn-scanqr").button("disable");
                clearAll(true);
                if(d && d=="OK"){
                    let msg="登録しました。";
                    var options = {"title":"完了",
                        width: 600,
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                                return;
                            }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog("open"); 
                }else{
                    let msg="<b>登録できません。</b><br><b style='color:red;'>再度やり直しをしてください。</b><br>詳細："+d;
                    var options = {"title":"エーラー",
                        width: 600,
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
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
    
    async function getPackingEntry(){
        var name_list = ["no","complete_code","itemname", "tag_name","itemcode","formnum","itemcav","num","time"];
        var post_data = get_table_val("item_add",name_list);
        // console.log(post_data);
        if(post_data.length==0){
            alert("QRスキャンしてください。");
            return;
        }
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=PackingEntry",
            data: {
                d:{
                    start_time:$("#ip_start_time").val(),
                    end_time:$("#ip_end_time").val(),
                    user:$("#担当者").val(),
                    plant_name:plant_name,
                    plant_id:plant_id,
                    list_item:post_data,
                }
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                $("#btn-entry").button("disable");
                $("#btn-scanqr").button("disable");
                clearAll(true);
                if(d=="OK"){
                    let msg="登録しました。";
                    var options = {"title":"完了。",
                        width: 600,
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
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
                    if(mode=="packing"){
                        $("#alert").dialog( "close" );
                        getPackingEntry();
                    }else if (mode=="picking"){
                        $("#alert").dialog( "close" );
                        getPickingEntry();
                    }
                }}]
        };
        $( "#alert" ).dialog( "option",options);
        $("#alert").dialog("open");
    }

    function entryCheck(){
        return new Promise((resolve) => {
            let check = $(".check_ip");
            var d={};
            d["開始日時"]=$("#ip_start_time").val();
            if(mode=="picking"){
                d["出荷伝票"]=($("#documentno").val());
                d["出荷日"]=$("#movement_date").val();
                d["出荷先"]=$("#wica_delivery").val();
                d["得意先コード"]=$("#wica_code").val();
            }else{
                d["終了日時"]=$("#ip_end_time").val();
                d["作業時間"]=$("#ip_work_time").val();
            }
           
            msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
            var errc=0;

            $.each(d,function(key) {
                if($.trim(d[key])==""){
                    msg = msg +"<li>"+key+"</li>\n";
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
            resolve(errc) 
        })
    }

    function qr_input(ac){
        var msg="<label for='QRコード'>完成品QR：</label><input id='QRコード' type='text' value='' placeholder='完成品のQRコード' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='QRコード'>完成品QR：</label><input id='QRコード' type='tel' pattern='[0-9]*' value='' placeholder='完成品のQRコード' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        }
        var options = {"title":"完成品のQRコードを入力",
            position:["center",100],
            width: 600,
            buttons: [
                { class:"btn-right",text:"カメラで入力",click :function(ev) {
                    $("#alert").dialog( "close" );
                    od('QRコード','完成品QR');
                }},
                { class:"btn-right",text:"OK",click :function(ev) {
                    var code = $("#QRコード").val();
                    if(code!==""){
                        getItemList(code);
                        $( this ).dialog( "close" );
                    }else{
                        alert("完成品QRを入力してください。")
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
                        if(id=="documentno"){
                            //伝票番号スキャンの場合
                            if($.isNumeric(code.data) && code.data.length < 10){
                                $("#scan_msg").html("");
                                list_code_scaned.push(code.data);
                                drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                                drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                                drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                                drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                                $("#"+id).val(code.data);
                                playMelody(2200);
                                await sleepy(300);
                                stop_scan();
                                checkCode();
                            }else{
                                drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                                drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                                drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                                drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                                $("#scan_msg").html("伝票番号の仕様が違い");
                            }
                        }else{
                            //完成IDスキャンの場合
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
                        }
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

    function playCode(){
        var base64 ="SUQzAwAAAAAAD1RDT04AAAAFAAAAKDEyKf/6MsA3nAAAAAABLgAAACAAACXAAAAEsASxAAB4lo0MMLMKMJEIIHERAoBgsUgD8RnxWK/0AIGwQ/IQ2KCTHOTh7j35KDINh5iMBaPJRboDwNi5/049BhCUB3kh/7zxgXDQAwyW/93yg5gaAAbCzQ/+m/3UXABfAb5HUXG+g/9HZmZmU0Oi3gUDEiQCCiRIGBQkiRRNmTSz+ccca3//+jDAPGM/AAAAAS4AAAAgAAAlwoAABBgX//9RW//9SwWjv/6KKoDhx3/9SEKENjgrd4eHdQAABUCkCbSZCZgmMksDx8wQWYv//1nwiBu//06gKK3/606gHwAjN/9N6yYAAqh/9fSC2AHzv6omJiIUAAAVAChRghIGICCSSoNTUupzjf/+4aDb//QxBz//rTpBAhJm/+rmYDKN//v/+jLA6aWBgAwU/WP4loQA3Jxvu4ygAfSBohJnfyuHiIh0AAQFQCcZtiYgMSZJGJGaNU6f//6BJhHBp//nbBQS3/7FtzgSQEWX/86u54HSCWf/3es4EUBDFq//5bmIiAhQAEVVDbwdw4b8x38vVIzjf/9RbCOJD//MqQEvP//qP1BOBRP/0tEpAHmbN/79AGqB2E//+suelwZwhAAA//oywPGOeIAHWOFrxLWrUNycLbhmxWoFQBZBgLhtQ/lFk3mZv//1HwFoMn/+Y0QbGG//OG1QNXA6if/0WUkPkDFAuq/6lbsIWCnD7f/6jGHh3CkAAABQMobEIumBrKaZMagaf/9R8GIIT//SpBb+//6lYzwZR//u9Y/AO1Bf/UhpIghUHSk3//NvzMy7pmZBasAYDkggHJDAGUHCBf/6MsAsLYKAB9EFZcTOi5DnoK14mMlyihAeIHf6aUymaf5ih6pMv/7LOiyJdTOq67Ls6mNBjhfT6C3Q/3QUaArxm6C7KW6C3QW5uYA3REByOWZmpmFZCBVgBSYSBB0Th4SJCBwckD1Ya9Rcxax3MXPahLTpax89zF0USSYyw9NOsXUWpSCCOHq01cXTTVq0OC1Hq1amWmVBYOSSB6j/+jDAu66JAAfJBWXCtmuQ6SCs+HbJcrEolm6qu7uGRlABQKGBSAlZREcWGeBgcNEPlhLPO/aokmU1H/1ShoZtb/1zhcPnV/+qZEPn/+09zWJjkzd3UyyKYCCARAgMEJDQlQbQ1hEUEliz9UdOqdSoejt/81BiQFzN//sEpnZv/5EIp2fO/2MQnCmE/Hq6mZdVQAVAjZ5tEMrHBUr/+jLAUDWPAAmo4XXDDatJPRzruGQhaZhooCFMFKX5ZZvcqhrbf1pNKg9an/zjVHBKc1v69FKiSy/195QfBMjMLKLcq140c1y8ao9/r1MdrfyhITgsSikqp+3qYujKI7KdX/ZkAoy+tST/+tccJzV9dFaS0TIQgUwskhLbbREPHQjAw4rGL/Ry0lNZFYBREmHb/vRWGIXpjmo7dc2p//oywGNOg4AG/ONNww1LQOEcKLhiqWmlQbRqRKxy/9jUFYmW65y3NRzSEgAmBqVX9beu///Vs/jG1a0/xiAAkAgAV+z///9hH//////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsCfBI4ABxzjO8SI60jnHCUsFDVp/////////////wAQAABzFX3bf///+xdc///Xf///sRsZV///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jDAw2KXgAd44ylglUtIBYAiwAAABP//////////////////////////////Vv///KW0f///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAvra7AAD8AxYAAAAADQBiwAAABP////////////////+AAACAP6er////qf//p/7KlI////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywKSp+AANIAMagAAAIDaAItAAAAD///////////////////////////93/6r+z9f////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsDmc/+AEFQBFgAAAAAMAGMAAAAA/////////////////gAEAADz3//ej9vV+p3/9P//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jDARD//gA9EAxYAAAAAKoAjEAAAAP/////////////////4BIIIB2bf+/9AABAAIPf9PT//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAgj7/gBBQAxYAAAAADQAiwAAAAP////////////////////////+j//1OrP////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywHKp/4APXAMWAAAAICwAItAAAAD/////////////////////AAABAH95/47dXKWO//kv///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MMB6Yf+AD3QDFgAAAAAkACNQAAAE////////////////////////b/vq/+7r/6f////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsB7mP+AEAwDGoAAACAVgGLAAAAA////////////////////////////u/yE//T/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAg+7/gA/AARYAAAAgIABi0AAABP//////////////////////////////0f9HX/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywLFs/4APsAEWAAAAACIAYsAAAAD//////////////////////////////xf9n/+nxf/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MMAB4v+AEBgBFgAAACAPgGLAAAAA//////////////////////wACAAB4Wwrr79Po+r////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsBn9v+AEFQBFgAAACANAGLAAAAA////////////////////////////////9P//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAQwb/gBB0ARYAAAAgCYBiwAAABP//////////////////////////////+f////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywEVy/4AP2AMWAAAAAByAItAAAAT////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MMBXrP+AEKADFgAAAAAAACMAAAAE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsBqtv+AELQDFgAAACAAACXAAAAE////////////////////////////////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/+jLA6jb/gBDAAS4AAAAgAAAlwAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//oywOo2/4AQwAEuAAAAIAAAJcAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/6MMAipf+AEKABLgAAACAAACXAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==";
        var sound = new Audio("data:audio/mp3;base64," + base64);
        sound.play();
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
        let mode="<?=$sf_params->get("mode")?>";
        username = name;
        window.history.pushState('', 'Title', '?plant='+plant+'&mode='+mode+'&user='+name);
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
            $("#btn-entry").button("enable");
            $("#btn-start").button("enable");
            $("#btn-scanqr").button("disable");
            if(username!=""){
                $("#担当者").val(username);
                goStart();
            }else{
                $( "#dialog_user" ).dialog( "open" );
            }
        }
    }

</script>
<div id="main-left" style="width:auto;padding: 10px;">
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