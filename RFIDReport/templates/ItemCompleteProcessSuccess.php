<?php
    use_javascript("jsQR.min.js");
    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");
    
    slot('h1', '<h1 style="margin:5px;font-size:110%;">完成品紐づけ作業 ｜ Nalux</h1>');
    $btn='<div style="float:right;">';
    // $btn.='<label>プリンター：</label><button type="button" id="btn_change_printer" class="btn-header" onclick="change_printer()"></button>　';
    $btn.='<label for="ms_bom" style="">BOM連携</label><input type="checkbox" id="ms_bom" name="ms_bom" checked="checked" style="margin:3px;" /></div>';
    slot('cd',$btn);
?>
    
<style type="text/css">

    table.type04 {width:99%;font-size:16px;border-collapse:collapse;text-align:center;line-height:1.5em;border-top:1px solid #ccc;border-left:2px solid #369;table-layout:fixed;margin: auto;}
    table.type04 th {padding:3px;height:24px;font-weight:bold;vertical-align:top;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table.type04 td {padding:3px;height:24px;vertical-align:top;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;white-space: nowrap;}
    table.type04 input {border:none;width:100%;font-size:16px;}
    #dialog hr {margin:5px 0 5px 0;}
    .ui-dialog{background:#f9f9f9 !important;}
    .main-ip{
        font-weight:bold;
    }
    .btn_ditemset {width:70px;height: 40px;}
    .btn_ditemset .ui-button-text{font-weight:bold;line-height:1.0 !important;padding:4px 2px !important; }
    .ui-dialog-buttonset{
        width:100%;
    }
    .dialog_btn {
        padding-right: 20px;
    }
    .btn-left{
        /* width:115px; */
        float:left;
    }
    .btn-right{
        /* width:115px; */
        float:right;
    }
    
    .used-num{
        color:red;
    }

    .have-num{
        color:green;
    }

    .ip-red{
        font-weight:bold;
        color:red;
    }

    .ip-green{
        font-weight:bold;
        color:green;
    }

    .ip-blue{
        font-weight:bold;
        color:blue;
    }

    .btn-header .ui-button-text{
        padding: 0;
        font-weight: bold;
    }

    .hidden-td{
        display:none;
    }
    .del_process_row .ui-btn-icon-left{
        padding:12px;
        margin:0;
        border:none;
    }
    .del_process_row .del_row_btn::after{
        left:5px;
        background-color: orange;
    }
    .del_row_btn:hover::after{
        background-color: red;
    }
    .left-table{
        float:left;
        width: calc(100% - 155px);
    }
    .ui-dialog .ui-dialog-buttonpane button{
        margin:0;
    }

    .ui-dialog .ui-dialog-buttonpane{
        padding:8px;
    }
    .btn_printer{
        margin-right:4px;
        margin-top:4px;
        width: 150px;
    }
    .table_check_id{
        display:none;
    }

    .ui-btn-icon-left::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .ui-btn-icon-right::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .ui-icon-camera::after{
        background-size: 90%;
    }

</style>

<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <script type="text/javascript">
    </script>
</div>


<script type="text/javascript">	
    var video,canvasElement,canvas =null,qr_reader="camera";
    var fcmode = localStorage.getItem("sFcmode");
    var mix_cav = false;
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }

    var printer_ip = JSON.parse(localStorage.getItem("sPrinter"));

    $(document).ready(function(){
        $("button").button();

        let QRd_w = $(window).width()/2;
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

        $("#rfid_progress").on("keyup",function(e){
            if(e.keyCode==13){
                getCheckedData(e.target.value,false);
            }
        })

        $("#rfid_complete").on("keyup",function(e){
            if(e.keyCode==13){
                goCounter(e.target.value,false);
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
        let user = "<?=$sf_params->get("user")?>";
        if(user!=""){
            $("#user").val(user);
        }

        let plant = "<?=$sf_params->get("plant")?>";
        plant=plant.substr(0,plant.length-2);
        var gr = "製造係_1班";
        if(plant=="山崎"){
            gr = "製造係";
        }
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(plant)+"&gp2="+decodeURIComponent(gr)/*+"&callback=?"*/,function(data){
            $("#ap-user-select").html(data);
        });

        $( "#user" ).click(function( event ) {
            $( "#dialog_user" ).dialog( "open" );
            event.preventDefault();
        });

        table_resize();
        var timer = false;
        $(window).resize(function() {
            if (timer !== false) {
                clearTimeout(timer);
            }
            timer = setTimeout(function() {
                table_resize();
            }, 100);
        });

        // if(!printer_ip){
        //     change_printer();
        // }else{
        //     $("#printer_ip").val(printer_ip[1]);
        //     $("#btn_change_printer span").html(printer_ip[0]);
        // }
    });

    function del_row(id,ac){
        var cpl_data = get_table_val("complete_content",["no","rfid"])
        var cpl_num=0;
        $.each(cpl_data,function(ck,cv){
            if(cv["rfid"]!="" && cv["rfid"]!=undefined){
                cpl_num++;
            }
        });
        var max_cpl_num =$("#pick_complete").val();

        if(ac == "process"){
            max_cpl_num=max_cpl_num-1;
        }
        let new_obj = [];
        $.each(all_checked_list, function (key, value) {
            if(value.wic_rfid && value.wic_rfid!==id ){
                new_obj.push(value);
            }else{
                list_code_scaned = list_code_scaned.filter(function(elem){
                    return elem != value.wic_rfid; 
                });
            }
        })
        all_checked_list=new_obj;
        let msg="【"+id+"】を本当に削除しますか？"
        var options = {"title":"確認",
            position:["center", 100],
            width: 600,
            buttons:[{ class:"btn-left",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
                return;
            }},{class:"btn-right",text:"削除",click :function(ev) {
                $("#"+id).html("");
                setTimeout(() => {
                    $("#complete_content").html("");
                    $("#cpl_need_num").val(0);
                    setTimeout(() => {
                        counterOnly();    
                    }, 0);
                }, 0);
                $( this ).dialog( "close" );
            }}]
        };
        $("#message").html(msg);
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
        
    }

    function checkBomInfo(rfid){
        return new Promise((resolve) => {
            $.ajax({
                type: "GET",
                url: "",
                data: {
                    "ac":"checkBomInfo",
                    "rfid":rfid
                },
                dataType: "json",
                success: function (d) {
                    resolve(d);
                }
            });
        })
    }

    var all_checked_list =[];
    async function getCheckedData(rfid,status_flag){
        if(status_flag==false){
            // let check = await rfid_status(rfid);
            // // true:在庫０->利用出来ない
            // // false:在庫有->利用出来る
            // if(check == true){
            //     openAlert("確認！！！","RFIDのデータが見つかりません。")
            //     return;
            // }
            let bom_check = await checkBomInfo(rfid);
            if(bom_check!=="OK"){
                list_code_scaned = list_code_scaned.filter(function(elem){
                    return elem != rfid;
                });
                openAlert("確認！！！","RFID："+rfid+"<br>"+bom_check)
                return;  
            } 
        }
        var box_num = 0;
        var pick_num = get_table_val("table_content",["del","device_no","rfid"]).length + 1;
        if(pick_num>1){
            let t_id = $(".table_check_id");
            for(i=0;i<t_id.length;i++){
                if(t_id[i].textContent==rfid){
                    openAlert("確認！！！","RFIDは重複です。")
                    return;  
                }
            }
        }
    
        var now_itemcode = "";
        var check_itemcode = true;
        let bom_mode = $("input[name=ms_bom]:checked").val();
        if(bom_mode!=="on"){
            bom_mode="off";
        }

        loadingView(true);
        //検査済のデータ収得
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getCheckedData",
                bom_mode:bom_mode,
                rfid:rfid
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                console.log(d);
                if(d[0]=="OK"){
                    $("#add_wo_rfid").show();
                    $("#add_wo_rfid .ui-button-text").css({"padding":"4px 10px"});
                    $("#add_wo_rfid .ui-button-text").css({"font-size":"16px"});
                    
                    var item_info = d[1];
                    let open_cam_flag = true;
                    $(item_info).each(function (index, element) {
                        if(pick_num>1){
                            if(element.wic_itemcode != $("#check_itemcode").val()){
                                openAlert("確認！！！","製品が違います！！！ <br>もう一度確認してください。");
                                open_cam_flag=false;
                                return;
                            }
                            //キャビ混在チェック
                            if(element.wic_itemcav != $("#check_itemcav").val() && mix_cav == false){
                                openAlert(
                                    "確認！！！",
                                    "キャビ【<b>"+element.wic_itemcav+"</b>】と【<b>"+$("#check_itemcav").val()+"</b>】が混在しています！！！ <br>混在状態で進めてもいいですか？",
                                    [{ class:"btn-left",text:"いいえ",click :function(ev) {
                                        $( this ).dialog( "close" );
                                        return;
                                    }},{ class:"btn-right",text:"はい",click :function(ev) {
                                        $( this ).dialog( "close" );
                                        mix_cav = true;
                                        $.each(item_info,function(a,b){
                                            add_item(b,pick_num);
                                        });
                                        return;
                                    }}]
                                );
                                // open_cam_flag=false;
                                // return;
                            }else{
                                add_item(element,pick_num);
                            }
                        }else{
                            add_item(element,pick_num);
                            $("#start_time").val(nowDT("dt"));
                            $("#check_itemcode").val(element.wic_itemcode);
                            $("#check_itemcav").val(element.wic_itemcav);
                        }
                    });
                    // setTimeout(() => {
                    //     if(open_cam_flag==true){
                    //         od("rfid_progress","仕掛品のRFID");
                    //     }
                    // }, 500);
                }else{
                    list_code_scaned = list_code_scaned.filter(function(elem){
                        return elem != rfid;
                    });
                    openAlert("確認！！！","RFID："+rfid+"<br>"+d[0]);
                    $("#rfid_progress").removeAttr('readonly');
                    return;
                }
            }
        });
    }

    function add_item(element,pick_num){
        var checked_table = "";
        var confirm_table = "";
        var have_num = 0;
        box_num = parseInt(element.fpr_num);
        if(box_num==0){
            box_num=parseInt(element.tray_num)*parseInt(element.tray_stok);
        }
        // box_num=127;
        $("#cpl_max_num").val(box_num);

        all_checked_list.push(element);
        $("#rfid_progress").removeAttr('readonly');
        checked_table+="<tr id='"+element.wic_rfid+"'>";
        checked_table+="<td class='del_process_row' onclick=del_row(`"+element.wic_rfid+"`)><span class='del_row_btn ui-btn ui-icon-delete ui-btn-icon-left'></span></td>";
        checked_table+="<td>"+pick_num+"</td>";
        checked_table+="<td class='table_check_id'>"+element.wic_rfid+"</td>";
        if(element.wic_rfid.length>24){
            checked_table+="<td class='table_id_view'>"+t2dec(element.wic_rfid)+"</td>";
        }else{
            checked_table+="<td class='table_id_view'>"+element.wic_rfid+"</td>";
        }
        checked_table+="<td>"+element.wic_itemcode+"</td>";
        if(element.searchtag!=""){
            checked_table+="<td>"+element.searchtag+"</td>";
        }else{
            checked_table+="<td>"+element.itemname+"</td>";
        }
        checked_table+="<td>"+element.wic_itemform+"</td>";
        checked_table+="<td>"+element.hgpd_moldlot+"</td>";
        checked_table+="<td>"+element.hgpd_moldday+"</td>";
        checked_table+="<td>"+element.hgpd_cav+"</td>";
        checked_table+="<td>"+element.hgpd_id+"</td>";
        checked_table+="<td>"+element.wic_qty_in+"</td>";
        checked_table+="<td style='display:none;'>"+element.wic_wherhose+"</td>";
        checked_table+="<td style='display:none;'>"+element.wic_process_key+"</td>";
        checked_table+="<td style='display:none;'>"+element.wic_process+"</td>";
        checked_table+="</tr>";

        confirm_table+="<tr>";
        confirm_table+="<td class='used-num'>0</td>";
        confirm_table+="<td class='have-num'>"+element.wic_qty_in+"</td>";
        confirm_table+="</tr>";

        $("#table_content").append(checked_table);
        $("#confirm_content").append(confirm_table);
        // $(".del_row_btn").button();
        let pick_list = get_table_val("table_content",["del","device_no","rfid","rfid_view","itemcode","itemname","itemform","moldlot","moldday","cav","hgpd_id","num","wic_wherhose","wic_process_key","wic_process"]);
        var sum_pick = 0
        $.each(pick_list, function (indexInArray, valueOfElement) { 
            sum_pick+=parseInt(valueOfElement.num);
        });
        $("#pick_num").val(sum_pick);
        $("#pick_complete").val(parseInt(sum_pick/box_num));
        sum_value();
    }

    function addDialog(){
        var msg=`<div style=""><label>追加の数：</label>
            <input id="add_num" type="number" value="" placeholder="" style="width:70px;text-align:right;" ></input> 個
        </div>`;
        var options = {"title":"追加の数を入力してください。",
            position:["center", 70],
            width: 400,
            buttons: 
                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                    return;
                }},{ class:"btn-right",text:"確定",click :function(ev) {
                    // let check_none_id = $(".check_none_rfid");
                    // if(check_none_id.length>1){
                    //     $("#message").append("<p style='color:red;margin-top:5px;'>● RFIDが無い製品は限界になりました。</p>");
                    //     delMessenger()
                    //     return;
                    // }
                    let max_num = parseInt($("#cpl_max_num").val());
                    let num = parseInt($("#add_num").val());
                    if(num <= max_num){
                        if($.isNumeric(num)){
                            $( this ).dialog( "close" );
                            addItemWithoutRFID(num);
                            return;
                        }else{
                            $("#message").append("<p style='color:red;margin-top:5px;'>● 数値を入力してください。</p>");
                            delMessenger()
                            $("#add_num").focus();
                        }
                    }else{
                        $("#message").append("<p style='color:red;margin-top:5px;'>● 入力数値はまるめ数より大きいです。</p>");
                        delMessenger()
                        $("#add_num").focus();
                    }
                }}]
        };
        $("#message").html(msg);
        // $(".ui-resizable-handle").hide();
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
    }

    function delMessenger(){
        setTimeout(() => {
            var msg=`<div style=""><label>追加の数：</label>
                <input id="add_num" type="number" value="" placeholder="" style="width:70px;text-align:right;" ></input> 個
            </div>`;
            $("#message").html(msg);
        }, 5000);
    }

    function addItemWithoutRFID(num){
        let cheked_table = get_table_val("table_content",["del","device_no","rfid","rfid_view","itemcode","itemname","itemform","moldlot","moldday","cav","hgpd_id","num","wic_wherhose","wic_process_key","wic_process"]);
        let item_info=cheked_table[0]
        item_info["rfid"]="なし";
        item_info["wic_rfid"]="なし";
        item_info["num"]=parseInt(num);
        var pick_num = get_table_val("table_content",["del","device_no","rfid"]).length + 1;

        var checked_table = "";
        var confirm_table = "";
        var have_num = 0;
        box_num=$("#cpl_max_num").val();
        all_checked_list.push(item_info);
        $("#rfid_progress").removeAttr('readonly');
        checked_table+="<tr id='none_rfid'>";
        checked_table+="<td class='del_process_row' onclick=del_row(`なし`)><span class='del_row_btn ui-btn ui-icon-delete ui-btn-icon-left'></span></td>";
        checked_table+="<td>"+pick_num+"</td>";
        checked_table+="<td class='table_check_id'>なし</td>";
        checked_table+="<td class='table_id_view'>なし</td>";
        checked_table+="<td>"+item_info.itemcode+"</td>";
        checked_table+="<td>"+item_info.itemname+"</td>";
        checked_table+="<td>"+item_info.itemform+"</td>";
        checked_table+="<td>"+item_info.moldlot+"</td>";
        checked_table+="<td></td>";
        checked_table+="<td></td>";
        checked_table+="<td></td>";
        checked_table+="<td>"+parseInt(num)+"</td>";
        checked_table+="<td style='display:none;'>"+item_info.wic_wherhose+"</td>";
        checked_table+="<td style='display:none;'>"+item_info.wic_process_key+"</td>";
        checked_table+="<td style='display:none;'>"+item_info.wic_process+"</td>";
        checked_table+="</tr>";

        confirm_table+="<tr>";
        confirm_table+="<td class='used-num'>0</td>";
        confirm_table+="<td class='have-num'>"+parseInt(num)+"</td>";
        confirm_table+="</tr>";

        $("#table_content").prepend(checked_table);
        $("#confirm_content").prepend(confirm_table);
        // $(".del_row_btn").button();
        let pick_list = get_table_val("table_content",["del","device_no","rfid","rfid_view","itemcode","itemname","itemform","moldlot","moldday","cav","hgpd_id","num","wic_wherhose","wic_process_key","wic_process"]);

        var sum_pick = 0
        $.each(pick_list, function (indexInArray, valueOfElement) { 
            sum_pick+=parseInt(valueOfElement.num);
        });
        $("#pick_num").val(sum_pick);
        $("#pick_complete").val(parseInt(sum_pick/box_num));
        sum_value();
        $("#add_wo_rfid").button("disable");
    }

    async function counterOnly(){

        let checked_table="";
        let checked_table_0="";
        let sum_pick=0;
        let box_num = $("#cpl_max_num").val();
        if(all_checked_list.length==0){
            $("#table_content").html("");
            $("#confirm_content").html("");
            $("#pick_num").val(0);
            $("#add_wo_rfid").hide();
            sum_value();
            return;
        }
        var confirm_table_0 = "";
        var confirm_table = "";
        console.log(all_checked_list);
        $.each(all_checked_list, function (key, value) {
            if(value.rfid=="なし"){
                sum_pick+=parseInt(value.num);
                checked_table_0+="<tr id='none_rfid'>";
                checked_table_0+="<td class='del_process_row' onclick=del_row(`なし`)><span class='del_row_btn ui-btn ui-icon-delete ui-btn-icon-left'></span></td>";
                checked_table_0+="<td>"+(key+1)+"</td>";
                checked_table_0+="<td class='table_check_id'>なし</td>";
                checked_table_0+="<td class='table_id_view'>なし</td>";
                checked_table_0+="<td>"+value.itemcode+"</td>";
                checked_table_0+="<td>"+value.itemname+"</td>";
                checked_table_0+="<td>"+value.itemform+"</td>";
                checked_table_0+="<td>"+value.moldlot+"</td>";
                checked_table_0+="<td></td>";
                checked_table_0+="<td></td>";
                checked_table_0+="<td></td>";
                checked_table_0+="<td>"+parseInt(value.num)+"</td>";
                checked_table_0+="<td style='display:none;'>"+value.wic_wherhose+"</td>";
                checked_table_0+="<td style='display:none;'>"+value.wic_process_key+"</td>";
                checked_table_0+="<td style='display:none;'>"+value.wic_process+"</td>";
                checked_table_0+="</tr>";

                confirm_table_0+="<tr>";
                confirm_table_0+="<td class='used-num'>0</td>";
                confirm_table_0+="<td class='have-num'>"+value.num+"</td>";
                confirm_table_0+="</tr>";
            }else{
                sum_pick+=parseInt(value.wic_qty_in);
                checked_table+="<tr id='"+value.wic_rfid+"'>";
                checked_table+="<td class='del_process_row' onclick=del_row(`"+value.wic_rfid+"`,`process`)><span class='del_row_btn ui-btn ui-icon-delete ui-btn-icon-left'></span></td>";
                checked_table+="<td>"+(key+1)+"</td>";
                checked_table+="<td class='table_check_id'>"+value.wic_rfid+"</td>";
                if(value.wic_rfid.length>24){
                    checked_table+="<td class='table_id_view'>"+t2dec(value.wic_rfid)+"</td>";
                }else{
                    checked_table+="<td class='table_id_view'>"+value.wic_rfid+"</td>";
                }
                checked_table+="<td>"+value.wic_itemcode+"</td>";
                if(value.searchtag!=""){
                    checked_table+="<td>"+value.searchtag+"</td>";
                }else{
                    checked_table+="<td>"+value.itemname+"</td>";
                }
                checked_table+="<td>"+value.wic_itemform+"</td>";
                checked_table+="<td>"+value.hgpd_moldlot+"</td>";
                checked_table+="<td>"+value.hgpd_moldday+"</td>";
                checked_table+="<td>"+value.hgpd_cav+"</td>";
                checked_table+="<td>"+value.hgpd_id+"</td>";
                checked_table+="<td>"+value.wic_qty_in+"</td>";
                checked_table+="<td style='display:none;'>"+value.wic_wherhose+"</td>";
                checked_table+="<td style='display:none;'>"+value.wic_process_key+"</td>";
                checked_table+="<td style='display:none;'>"+value.wic_process+"</td>";
                checked_table+="</tr>";
                confirm_table+="<tr>";
                confirm_table+="<td class='used-num'>0</td>";
                confirm_table+="<td class='have-num'>"+value.wic_qty_in+"</td>";
                confirm_table+="</tr>";
            }

        });
        $("#table_content").html(checked_table_0+checked_table);
        $("#confirm_content").html(confirm_table_0+confirm_table);

        $("#pick_num").val(sum_pick);
        let max_item = parseInt(sum_pick)/parseInt(box_num);
        if(max_item<1){
            max_item=0;
        }
        $("#pick_complete").val(parseInt(max_item));

        setTimeout(() => {
            sum_value();
        }, 0);
        $("#add_wo_rfid").button("enable");
    }

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

    // console.log("dec: "+t2dec(RFID_pre));
    // console.log("enc: "+t2enc(t2dec(RFID_pre)));

    var list_before =[];
    async function goCounter(rfid,check_status,ac){
        var cpl_data = get_table_val("complete_content",["no","rfid"])
        var cpl_num=1;
        $.each(cpl_data,function(ck,cv){
            if(cv["rfid"]!="" && cv["rfid"]!=undefined){
                cpl_num++;
            }
        });
        var max_cpl_num =$("#pick_complete").val();
        if(cpl_num>max_cpl_num){
            list_code_scaned = list_code_scaned.filter(function(elem){
                return elem != rfid; 
            });
            openAlert("確認！！！","仕掛品数が足りません！！！");
            return;
        }

        let pick_cpl = get_table_val("complete_content",["rfid"]).length + 1;
        if(pick_cpl>1){
            let t_id = $(".cpl_check_id");
            for(i=0;i<t_id.length;i++){
                if(t_id[i].textContent==rfid){
                    openAlert("確認！！！","RFIDは重複です。");
                    return;
                }
            }
        }

        if(check_status==false){
            let check = await rfid_status(rfid,"completeId");
            // true:在庫０->利用出来る
            // false:在庫有->利用出来ない
            if(check==false){
                openAlert("確認！！！","RFIDは利用中です。")
                return false;
            }
        }

        let this_connect = {
            complete_rfid:rfid,
        }

        //まるめ
        var box_num = $("#cpl_max_num").val();

        // 必要数
        var need_num = parseInt(box_num)*cpl_num;

        var start_num = need_num-parseInt(box_num);

        // 取った数
        var no_reset_num = 0;    

        var now_need = need_num;

        $("#cpl_need_num").val(need_num);
        var before_id = "";
        var sum_cpl_num = 0;

        var confirm_table = "";
        let now_list=get_table_val("table_content",["del","device_no","rfid","rfid_view","itemcode","itemname","itemform","moldlot","moldday","cav","hgpd_id","num","wic_wherhose","wic_process_key","wic_process"]);
        let device_no_list = "";
        $.each(now_list, function (key, value) {
            $("#entry_btn").button("enable");

            sum_cpl_num+=parseInt(value.num);

            confirm_table+="<tr>";
            // if(no_reset_num==need_num){
            //     //全部使う
            //     confirm_table+="<td class='used-num'>"+value.wic_qty_in+"_"+key+"</td>";
            //     confirm_table+="<td class='have-num'>0</td>";
            //     // confirm_table+="<td class='com-ids'>"+rfid+"</td>"; 
            //     // before_id+= value.hgpd_rfid+"=>"+value.wic_qty_in+":===,";
            // }else 
            if(no_reset_num<need_num){
                //取った<必要
                let try_num = now_need - parseInt(value.num);
                if(now_need>parseInt(value.num)){
                    //今の箱と次の箱から引く
                    now_need=try_num;
                    confirm_table+="<td class='used-num'>"+value.num+"</td>";
                    confirm_table+="<td class='have-num'>0</td>";
                    if(sum_cpl_num>start_num){
                        device_no_list+= value.device_no+",";
                        if((sum_cpl_num-start_num)>parseInt(value.num)){
                            before_id+= value.hgpd_id+"=>"+value.num+",";
                        }else{
                            before_id+= value.hgpd_id+"=>"+(sum_cpl_num-start_num)+",";
                        }
                    }
                }else{
                    //この箱の一部引く
                    device_no_list+= value.device_no+",";
                    confirm_table+="<td class='used-num'>"+now_need+"</td>";
                    confirm_table+="<td class='have-num'>"+(parseInt(value.num)-now_need)+"</td>"; 
                    before_id+= value.hgpd_id+"=>"+now_need+",";
                }
            }else{
                confirm_table+="<td class='used-num'>0</td>";
                confirm_table+="<td class='have-num'>"+value.num+"</td>";
            }
            no_reset_num += parseInt(value.num);

            confirm_table+="</tr>";

        });

        $("#confirm_content").html(confirm_table);
        before_id=before_id.substr(0,before_id.length-1);
        device_no_list=device_no_list.substr(0,device_no_list.length-1);
        var complete_table = "<tr id='"+rfid+"'>";
        // complete_table+= "<td class='del_process_row' onclick=del_row(`"+rfid+"`,`complete`)><span class='del_row_btn ui-btn ui-icon-delete ui-btn-icon-left'></span></td>";
        complete_table+= "<td>"+cpl_num+"</td><td class='cpl_check_id'>"+rfid+"</td>"
        complete_table+= "<td class='before_id hidden-td' >"+before_id+"</td>"
        complete_table+= "<td>"+$("#cpl_max_num").val()+"</td>"
        complete_table+= "<td>"+device_no_list+"</td>"
        complete_table+= "</tr>";

        $("#complete_content").append(complete_table);

        setTimeout(() => {
            sum_value();
        }, 0);
        // setTimeout(() => {
        //     od("rfid_complete","完成品のRFID");
        // }, 500);
    }

    function sum_value(){
        let sum_used = $(".used-num");
        let sum_has = $(".have-num");
        let sum_used_num =0;
        let sum_has_num =0;
        $.each(sum_used, function (ku, vu) { 
            sum_used_num+=parseInt(vu.textContent)
        });
        $.each(sum_has, function (kh, vh) { 
            sum_has_num+=parseInt(vh.textContent)
        });
        $("#all_used").val(sum_used_num);
        $("#all_has").val(sum_has_num);
    }

    function entryData(){
        let all_used = $("#all_used").val();
        let all_has = $("#all_has").val();
        let cpl_max_num = $("#cpl_max_num").val();
        let rfid_ids = [];
        if(parseInt(all_has)>parseInt(cpl_max_num)){
            openAlert("確認！！！","仕掛品の数が残ってるんです。<br>完成品IDをスキャンしてください。")
            return;
        }

        let cheked_table = get_table_val("table_content",["del","device_no","rfid","rfid_view","itemcode","itemname","itemform","moldlot","moldday","cav","hgpd_id","num","wic_wherhose","wic_process_key","wic_process"]);
        let sum_table = get_table_val("confirm_content",["used","has"]);
        let complete_table = get_table_val("complete_content",["cpl_no","cpl_id","proc_ids"]);
        let out_data = [];
        if(cheked_table.length==0){
            openAlert("確認！！！","仕掛品IDをスキャンしてください。");
            return;
        }
        if(complete_table.length==0){
            openAlert("確認！！！","完成品IDをスキャンしてください。");
            return;
        }
        if($("#user").val()==""){
            openAlert("確認！！！","担当者を入力してください。",null,function(){
                $("#dialog_user").dialog("open");
            })
            return;
        }
        $.each(cheked_table, function (a, b) { 
            if(b.rfid!="なし" && rfid_ids.indexOf(b.rfid)==-1){
                rfid_ids.push(b.rfid);
            }
            let merge_data = {
                ...b,
                ...sum_table[a],
                wic_wherhose:all_checked_list[a]["wic_wherhose"],
                wic_process_key:all_checked_list[a]["wic_process_key"],
                process_name:all_checked_list[a]["wic_process"],
            }
        
            if(sum_table[a].used>0){
                out_data.push(merge_data);
            }
        });

        $.each(complete_table, function (c, d) { 
            rfid_ids.push(d.cpl_id);
        })

        let bom_mode = $("input[name=ms_bom]:checked").val();
        if(bom_mode!=="on"){
            bom_mode="off";
        }
        let host = window.location.origin;
        let url = new URL(host+"/RFIDReport/RFIDStatusCheck?site=ItemComplete&plant=<?=$sf_params->get("plant")?>&user="+$("#user").val());
        url.searchParams.set("ids", rfid_ids);
        // window.open(url);
        // return;
        loadingView(true);
        $.ajax({
            type: "POST",
            // url: "/frontend_dev.php/RFIDReport/ItemCompleteProcess",
            url: "?ac=entryData",
            data: {
                out_data:out_data,
                in_data:complete_table,
                user:$("#user").val(),
                start_time:$("#start_time").val(),
                bom_mode:bom_mode,
                printer_ip:$("#printer_ip").val(),
                plant:"<?=$sf_params->get("plant")?>"
            },
            dataType: "json",
            success: function (d) {
                loadingView(false);
                if(d=="OK"){
                    //clear
                    $("#entry_btn").button("disable");
                    $("#add_wo_rfid").button("enable");
                    mix_cav = false;
                    openAlert("完了","登録しました。");
                    cancel_complete(true);
                }else{
                    openAlert("確認！！！","登録出来ません。管理者に連絡してください。");
                }
                // setTimeout(() => {
                //     window.open(url, '_blank');
                // },0)
            }
        });
    }

    function openScanChecked(id,name){
        od(id)
    }

    function rfid_status(rfid,type){
        return new Promise((cb) => {
            let linked_id = $(".check_rfid");
            let c_flag = false;
            $.each(linked_id,function(key,val){
                if(val.value==rfid){
                    c_flag = true;
                }
            })
            if(c_flag){
                openAlert("確認！！！",d[1]);
                return false;
            }else{
                loadingView(true);
                $.ajax({
                    type: "GET",
                    url: "/RFIDReport/RfidCheckStatus?id_type="+type,
                    dataType: "json",
                    data: {
                        rfid:rfid,
                        item:$("#check_itemcode").val()
                    },
                    success: function(d){
                        loadingView(false);
                        console.log(d);
                        if(d===true){
                            cb(d);
                        }else{
                            openAlert("確認！！！",d[1]);
                            return false;
                        }
                    }
                });
            } 
        })
    }

    function cancel_complete(flag){
        if(!flag){
            flag = confirm("本当に入力した全てクリアしてもいいでしょうか？");
        }
        if(flag){
            all_checked_list = [];
            list_code_scaned = [];
            $("#add_wo_rfid").button("enable");
            $("#add_wo_rfid").hide();
            $("input").val("");
            $("#table_content").html("");
            $("#confirm_content").html("");
            $("#complete_content").html("");
        }
    }

    function od(id,name,fcmode){
        // loadingView(true);
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        $("#"+id).attr('readonly', 'readonly');
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:480 }}).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            tick(id);
        });
        
        let button = [
            { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
                qr_reader="scaner";
                if(id=="rfid_progress"){
                    stop_scan();
                    $("#"+id).removeAttr('readonly');
                    $("#"+id).focus();
                }else{
                    stop_scan();
                    $("#"+id).removeAttr('readonly');
                    $("#"+id).focus();
                }
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
        // loadingView(false);
        $("#QRScan").dialog( "open" );
        $("#"+id).focus();
        // setTimeout(function(){
        //     stop_scan();
        // }, 300000);
        position_fix();
    }

    function position_fix(){
        console.log(video.readyState)
        if(video.readyState==4){
            setTimeout(() => {
                $("#QRScan").dialog({
                    position: {my: "left top", at: "right bottom", of: window}
                });       
            }, 100);
        }else{
           setTimeout(() => {
                position_fix()
           }, 200);
        }
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
                        if(id=="rfid_progress"){
                            getCheckedData(code.data,false);
                        }else if(id=="rfid_complete"){
                            goCounter(code.data,false);
                        }
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

    function playCode(){
        var base64 ="SUQzAwAAAAAAD1RDT04AAAAFAAAAKDEyKf/6MsA3nAAAAAABLgAAACAAACXAAAAEsASxAAB4lo0MMLMKMJEIIHERAoBgsUgD8RnxWK/0AIGwQ/IQ2KCTHOTh7j35KDINh5iMBaPJRboDwNi5/049BhCUB3kh/7zxgXDQAwyW/93yg5gaAAbCzQ/+m/3UXABfAb5HUXG+g/9HZmZmU0Oi3gUDEiQCCiRIGBQkiRRNmTSz+ccca3//+jDAPGM/AAAAAS4AAAAgAAAlwoAABBgX//9RW//9SwWjv/6KKoDhx3/9SEKENjgrd4eHdQAABUCkCbSZCZgmMksDx8wQWYv//1nwiBu//06gKK3/606gHwAjN/9N6yYAAqh/9fSC2AHzv6omJiIUAAAVAChRghIGICCSSoNTUupzjf/+4aDb//QxBz//rTpBAhJm/+rmYDKN//v/+jLA6aWBgAwU/WP4loQA3Jxvu4ygAfSBohJnfyuHiIh0AAQFQCcZtiYgMSZJGJGaNU6f//6BJhHBp//nbBQS3/7FtzgSQEWX/86u54HSCWf/3es4EUBDFq//5bmIiAhQAEVVDbwdw4b8x38vVIzjf/9RbCOJD//MqQEvP//qP1BOBRP/0tEpAHmbN/79AGqB2E//+suelwZwhAAA//oywPGOeIAHWOFrxLWrUNycLbhmxWoFQBZBgLhtQ/lFk3mZv//1HwFoMn/+Y0QbGG//OG1QNXA6if/0WUkPkDFAuq/6lbsIWCnD7f/6jGHh3CkAAABQMobEIumBrKaZMagaf/9R8GIIT//SpBb+//6lYzwZR//u9Y/AO1Bf/UhpIghUHSk3//NvzMy7pmZBasAYDkggHJDAGUHCBf/6MsAsLYKAB9EFZcTOi5DnoK14mMlyihAeIHf6aUymaf5ih6pMv/7LOiyJdTOq67Ls6mNBjhfT6C3Q/3QUaArxm6C7KW6C3QW5uYA3REByOWZmpmFZCBVgBSYSBB0Th4SJCBwckD1Ya9Rcxax3MXPahLTpax89zF0USSYyw9NOsXUWpSCCOHq01cXTTVq0OC1Hq1amWmVBYOSSB6j/+jDAu66JAAfJBWXCtmuQ6SCs+HbJcrEolm6qu7uGRlABQKGBSAlZREcWGeBgcNEPlhLPO/aokmU1H/1ShoZtb/1zhcPnV/+qZEPn/+09zWJjkzd3UyyKYCCARAgMEJDQlQbQ1hEUEliz9UdOqdSoejt/81BiQFzN//sEpnZv/5EIp2fO/2MQnCmE/Hq6mZdVQAVAjZ5tEMrHBUr/+jLAUDWPAAmo4XXDDatJPRzruGQhaZhooCFMFKX5ZZvcqhrbf1pNKg9an/zjVHBKc1v69FKiSy/195QfBMjMLKLcq140c1y8ao9/r1MdrfyhITgsSikqp+3qYujKI7KdX/ZkAoy+tST/+tccJzV9dFaS0TIQgUwskhLbbREPHQjAw4rGL/Ry0lNZFYBREmHb/vRWGIXpjmo7dc2p//oywGNOg4AG/ONNww1LQOEcKLhiqWmlQbRqRKxy/9jUFYmW65y3NRzSEgAmBqVX9beu///Vs/jG1a0/xiAAkAgAV+z///9hH//////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsCfBI4ABxzjO8SI60jnHCUsFDVp/////////////wAQAABzFX3bf///+xdc///Xf///sRsZV///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jDAw2KXgAd44ylglUtIBYAiwAAABP//////////////////////////////Vv///KW0f///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAvra7AAD8AxYAAAAADQBiwAAABP////////////////+AAACAP6er////qf//p/7KlI////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywKSp+AANIAMagAAAIDaAItAAAAD///////////////////////////93/6r+z9f////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsDmc/+AEFQBFgAAAAAMAGMAAAAA/////////////////gAEAADz3//ej9vV+p3/9P//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jDARD//gA9EAxYAAAAAKoAjEAAAAP/////////////////4BIIIB2bf+/9AABAAIPf9PT//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAgj7/gBBQAxYAAAAADQAiwAAAAP////////////////////////+j//1OrP////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywHKp/4APXAMWAAAAICwAItAAAAD/////////////////////AAABAH95/47dXKWO//kv///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MMB6Yf+AD3QDFgAAAAAkACNQAAAE////////////////////////b/vq/+7r/6f////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsB7mP+AEAwDGoAAACAVgGLAAAAA////////////////////////////u/yE//T/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAg+7/gA/AARYAAAAgIABi0AAABP//////////////////////////////0f9HX/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywLFs/4APsAEWAAAAACIAYsAAAAD//////////////////////////////xf9n/+nxf/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MMAB4v+AEBgBFgAAACAPgGLAAAAA//////////////////////wACAAB4Wwrr79Po+r////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsBn9v+AEFQBFgAAACANAGLAAAAA////////////////////////////////9P//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+jLAQwb/gBB0ARYAAAAgCYBiwAAABP//////////////////////////////+f////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////oywEVy/4AP2AMWAAAAAByAItAAAAT////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MMBXrP+AEKADFgAAAAAAACMAAAAE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////6MsBqtv+AELQDFgAAACAAACXAAAAE////////////////////////////////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/+jLA6jb/gBDAAS4AAAAgAAAlwAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//oywOo2/4AQwAEuAAAAIAAAJcAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/6MMAipf+AEKABLgAAACAAACXAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==";
        var sound = new Audio("data:audio/mp3;base64," + base64);
        sound.play();
    }

    function get_table_val(id,name_list){
        const table = document.querySelector("#"+id); 
        var rows = Array.from(table.rows);
        var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
        var data = [];
        $.each(cells,function(a,b){
            // if(a>0){
                let r ={};
                // b.splice(0,1);
                $.each(name_list,function(k,v){
                    r[v]=b[k]
                })
                data.push(r)
            // }
        })
        return data;
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

    function openAlert(title,msg,btn,callback){
        let set_btn =[{ class:"btn-left",text:"閉じる",click :function(ev) {
            $( this ).dialog( "close" );
            if(callback){
                callback();
            }
            return;
        }}]
        if(btn){
            set_btn=btn;
        }
        // console.log(btn);
        var options = {"title":title,
            position:["center", 100],
            width: 600,
            buttons:set_btn
        };
        $("#message").html(msg);
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
        return false;
    }
    
    function table_resize(){
        let ww = $(window).width();
        if(ww<900){
            $(".td-rfid").width(60);
        }else{
            $(".td-rfid").width(220);
        }
    }

    function change_printer(){
        // alert("select printer");
        $("#printer_ip").val("");
        localStorage.removeItem("sPrinter");
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "/RFIDReport/MoldingCounter",
            data: {
                ac:"getPrinterIp",
                plant:"<?=$sf_params->get("plant")?>"
            },
            dataType: "json",
            success: function (response) {
                loadingView(false);
                console.log(response);
                var msg = '<div style="text-align:left;">';
                $.each(response, function (k, v) { 
                    msg+='<button class="btn_printer" onclick=confirm_printer(`'+v.wim_ipaddr+'`,`'+v.wim_res_name+'`)>'+v.wim_res_name+'</button>';
                });
                msg+='<p id="printer_msg" style="margin-top:10px;color:red;text-align:center;"></p></div>';
                var options = {"title":"プリンターを選択してください。",
                    position:["center",100],
                    width: 960,
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            if($("#printer_ip").val()==""){
                                $("#printer_msg").html("プリンターを選択してください。");
                            }else{
                                $( this ).dialog( "close" );
                                return;
                            }
                        }}]
                };
                $("#message").html(msg);
                $(".btn_printer").button();
                $(".ui-resizable-handle").hide();
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                
            }
        });
    }

    function confirm_printer(ip,name){
        let json_prt = [name,ip];
        localStorage.setItem("sPrinter",JSON.stringify(json_prt));
        $("#printer_ip").val(ip);
        $("#btn_change_printer span").html(name);
        $( "#alert" ).dialog( "close" );
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
            return year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec;
        }
        if(str=="date"){
            return year+"-"+month+"-"+day;
        }
        if(str=="time"){
            return hour+":"+min+":"+sec;
        }
    }
    
</script>

<div style="float:left;padding:10px;">
    <div style="">
        <div style="float:left;margin-right:20px;"><b>まるめ数：</b><input id='cpl_max_num' class="main-ip" value ='0' style='text-align:right;width:80px;' />個</div>
        <div style="float:left;margin-right:20px;"><b>受け数：</b><input id='pick_num' class="main-ip" value ='0' style='text-align:right;width:80px;' />個</div>
        <div style="float:left;margin-right:20px;"><b>最大完成ID：</b><input id='pick_complete' class="main-ip" value ='0' style='text-align:right;width:80px;' />枚</div>
        <div style="float:left;margin-right:20px;"><label for="user" style="font-weight:bold;">担当者：</label><input type="text" value="" name="user" id="user" readonly="readonly" style="width:180px;font-weight:bold;"/></div>
        <input type="text" value="" name="start_time" id="start_time" style="display:none;" />
    </div>

    <div style='float:left;width:auto;border:1px solid #ccc;box-shadow: 5px 5px 5px 0px #ccc;margin:10px auto;padding:10px 5px;'>
        <div class="left-table">
            <div style="float:left;margin:10px 0;"><label><b>仕掛品のRFID:</b></label>
                <input id="rfid_progress" class="main-ip" value="" placeholder="仕掛品のRFIDをスキャン" style="width: 400px;height:28px;" ></input>
                <button type="button" onclick="od('rfid_progress','仕掛品のRFID');" class="btn_ditemset" style="margin-left:10px;width:50px;height:35px;background-color:#00c3ff;">
                <span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
                <input id="check_itemcode" type="hidden" value="" />
                <input id="check_itemcav" type="hidden" value="" />
            </div>

            <div style='margin:10px 0;'>
                <table class='type04' style="font-size:90%;" >
                    <tr>
                        <th style="width:30px;">削除</th>
                        <th style="width:30px;">No.</th>
                        <th class="td-rfid">仕掛ID</th>
                        <th style="width:95px;">品目</th>
                        <th>品名</th>
                        <th style="width:25px;">型</th>
                        <th style="width:50px;">ロット</th>
                        <th style="width:75px;">成形日</th>
                        <th style="width:45px;">キャビ</th>
                        <th style="width:60px;">管理No</th>
                        <th style="width:40px;">数量</th>
                    </tr>
                    <tbody id='table_content'>
                    </tbody>
                </table>
                <hr style ="width:100%;margin:20px 0 15px 0;"></hr>
            
                <div style="float:left;margin:10px 0;"><label><b>完成品のRFID:</b></label>
                    <input id="rfid_complete" class="main-ip" value="" placeholder="完成品のRFIDをスキャン" style="width: 400px;height:28px;" ></input>
                    <button type="button" onclick="od('rfid_complete','完成品のRFID');" class="btn_ditemset" style="margin-left:10px;width:50px;height:35px;background-color:#00c3ff;">
                    <span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
                </div>

                <div style="float:right;margin:10px 5px 0 0;">合計数：<input id='cpl_need_num' class="main-ip" value ='0' style='text-align:right;width:60px;' readonly="readonly" />個</div>

                <table class='type04' >
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th style="width:220px;">完成品ID</th>
                        <th class="hidden-td">仕掛品ID</th>
                        <th style="width:60px;">数量</th>
                        <th style="width:100px;">仕掛No.</th>
                    </tr>
                    <tbody id='complete_content'>
                    </tbody>
                </table>
            </div>
        </div>

        <div style='margin:10px 0;float:right;width:150px;'>
            <div style='float:left;margin:5px 0 3px 0;'><label style="display:inline-block;margin-bottom:4px;padding:5px;font-weight:bold;font-size:18px;" >計算</label><button id="add_wo_rfid" onclick="addDialog();" style="display:none;" >✚混合</button></div>
            <table class='type04'><tr><th>ID登録数</th><th>残り</th></tr>
                <tbody id='confirm_content'>
                </tbody>
            </table>
            <br>
            <table class='type04' style="margin: 8px 0 0 0;">
                <tr><th colspan="2">合計</th></tr>
                <tr><th>完成数</th><th>残り</th></tr>
                <tbody id='sum_content'>
                    <tr>
                        <td><input id="all_used" class='ip-blue' value="0" style="text-align:center;" readonly="readonly" /></td>
                        <td><input id="all_has" class='ip-green' value="0" style="text-align:center;" readonly="readonly" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <button type="button" onclick="window.close(); return false;" style="float:left;margin-top:5px;padding:5px 12px;">閉じる</button>
    <button id="clear_btn" type="button" onclick="cancel_complete();" class="btn-menu" style="float:left;margin-top:5px;padding:5px 12px;">クリア</button>
    <button id="entry_btn" type="button" onclick="entryData();" class="btn-menu" style="float:right;margin-top:5px;padding:5px 12px;">登録</button>
    <input type="hidden" id="printer_ip" name="printer_ip" value="" />
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