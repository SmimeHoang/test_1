<?php
    use_javascript("jquery/jqplot/jquery.jqplot.min.js");
    use_javascript("jquery/jqplot/plugins/jqplot.pieRenderer.min.js");
    // use_javascript("jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js");
    use_javascript("jsQR.min.js");
    use_javascript("molding_info.js");
    use_javascript("molding_info_var.js");
    // molding_info = 生産情報
    // ditem = 不良項目マスター
    // worklist = 工程名称マスター
    // userlist = 作業者マスター
    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");
?>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

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
    @media only screen and (min-width: 1206px) { 

    }
    #content{ padding-top: 0vh;width: 100%;}
    .ui-dialog{background:#f9f9f9 !important;}
    .input{ime-mode: disabled;}
    .btn_ditemset {width:70px;height: 40px;}
    .btn_ditemset .ui-button-text{font-weight:bold;line-height:1.0 !important;padding:4px 2px !important; }
    .btn_select.ui-button-text{line-height:1.0 !important;padding:6px 17px !important;}
    #user-list button{padding:2px;font-size:100% !important;}
    #user-list button span{padding:0;font-size:120%;}
    .btn-vh .ui-button-text{font-size:14px;padding:2px 5px;}
    .ui-widget .ui-button{font-size:16px;}
    .menu {font-size:120%;}
    .menu label .ui-button-text{padding:2px 5px;}
    .menu label{margin:0 5px 0 0;font-weight:bold;}
    .menu input{font-size:100%;font-weight:bold;border:1px solid #ccc;border-radius:5px;padding: 2px;}
    .menu p{padding:5px;margin-left: -5px;}
    fieldset {padding:5px 5px 10px 5px;margin-bottom:5px;text-align:center;}
    fieldset legend{text-align:left;}
    fieldset p{width: 100%;}
    .btn{margin:5px 0 0 0;}
    .main_cont{overflow:scroll;border:1px solid #999;}
    #right_site{overflow:scroll;border:1px solid #999;}
    table.type03 {width:99%;font-size:16px;border-collapse:collapse;text-align:left;line-height:1.5em;border-top:1px solid #ccc;border-left:3px solid #369;table-layout:fixed;margin: auto;white-space: nowrap;}
    table.type03 th {padding:3px;font-weight:bold;vertical-align:top;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table.type03 td {padding:3px;vertical-align:top;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;}
    table.type03 input {border:none;width:130px;font-size:16px;}

    table.type04 {width:99%;font-size:16px;border-collapse:collapse;text-align:center;line-height:1.5em;border-top:1px solid #ccc;border-left:2px solid #369;table-layout:fixed;margin: auto;}
    table.type04 th {padding:3px;font-weight:bold;vertical-align:top;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table.type04 td {padding:3px;vertical-align:top;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;}
    table.type04 input {border:none;width:130px;font-size:16px;}
    #dialog_user hr {margin:5px 0 5px 0;}
    #addUser{font-size:80%;height:29px;width:60px;margin: 0 0 0 5px;}
    #btn_start{margin-bottom:5px;}
    #btn_start .ui-button-text, #xls_entry .ui-button-text, #btn_complete .ui-button-text,#btn_item_round .ui-button-text, #btn_digital .ui-button-text{padding:2px 5px;}
    .tb_total_table{
        font-size: 11px;
        border: 1px #BBB solid;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
    }
    .tb_total_th{
        white-space: nowrap;
        border: #BBB solid;
        border-width: 0 0 1px 1px;
        text-align: center;
    }
    .tb_total_td{
        border: 1px #BBB solid;
        border-width: 0 0 1px 1px;
        vertical-align: middle;
        padding: 2px;
        min-height: 1.5em;
    }
    #canvas {
        width: 480px;
        margin: auto;
    }
    #QRScan {
        width: 500px;
        margin: auto;
    }
    .retry::placeholder {
        color: red;
    }
    #user-list button{
        padding: 4px;
        height: 2em;
        margin: 2px 1px 2px 1px;
        text-justify: inter-ideograph;
    }

    #user-list hr{
        margin: 2px 0 2px 0;
        color :#ECEADB;
    }

    #user-list button p{
        margin-top:3px;
        vertical-align: middle;
        text-decoration:underline;
        color : #bbb;
    }
    .ui-btn-icon-left::after{
        display: inline-block;
        width: 34px;
        height: 34px;
        margin: -17px 0 0 0px;
    }
    .ui-btn-icon-right::after{
        margin-top: -17px;
        margin-right: -4px;
        width: 34px;
        height: 34px;
    }
    .dialog_btn.ui-btn-icon-right{
        padding-right: 30px;
    }

    #add_right_load{
        position: fixed;
        top: 0;
        width: 100%;
        height: 100%;
        background: #8e8e8e;
        opacity: 0.7;
        z-index: 1000;
    }

    .right_loader {
        position:absolute;
        top: 50%;
        left:25%;
        width: 48px;
        height: 48px;
        border: 5px solid #FFF;
        border-bottom-color: transparent;
        border-radius: 50%;
        display: inline-block;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    .ui-dialog-buttonset{
        width: 100%;
    }
    
    .btn-menu{
        padding: 6px 15px;
        font-weight:bold; 
    }

    .btn_topmenu{
        width:70px;
        height:45px;
    }

    .btn-left .ui-button-text,.btn-right .ui-button-text {
        padding: 6px 15px;
        font-size:130%;
    }
    .btn-left{
        /* width:115px; */
        float:left;
    }
    .btn-right{
        /* width:115px; */
        float:right;
    }

    .btn-del .ui-button-text{
        color:red;
    }

    .btn-ent .ui-button-text{
        color:aqua;
    }
    
    .used-num{
        color:red;
    }

    .have-num{
        color:green;
    }

    .entring{
        color:red;
        font-weight:bold;
    }

    .entred{
        color:green;
        font-weight:bold;
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    } 

    .ui-btn-icon-left::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .ui-btn-icon-right::after{
        background-color: rgba(255, 255, 255, 0);
    }

    .ui-icon-camera::after{
        background-size: 80%;
    }

</style>

<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <script type="text/javascript">
    </script>
</div>

<script type="text/javascript">	
    // console.log(molding_info);
    // console.log(assembly_info);
    // console.log(rfid_molding_info);
    // console.log(molding_info_ver);
    var video,canvasElement,canvas =null,qr_reader="camera";
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    var gp1 =[],gp2=[],gp_item={},workitem_list=[],user_obj={};

    $(document).ready(function(){
        $("button").button();
        $("#assembly_setting").hide();
        $( "#alert" ).dialog({
            autoOpen: false,
            width: '980px',
            modal:true,
            position:["center",100],
            buttons: [{ text: "閉じる", click: function() {
                $( this ).dialog( "close" );
            }}]
        });

        var personname = '<?=$sf_params->get("personname")?>';
        if(!personname){
            personname="";
        }

        var plant = '<?=$sf_params->get("plant")?>';
        var plant_id="";

        if(!plant){
            var options = {"title":"工場を選択して下さい。",
                position:["center",100],
                width: '430px',
                buttons: [{ text:"野洲工場",click :function(ev) {
                    location.href="/RFIDReport/SetBase?plant=野洲工場&personname="+personname;
                    localStorage.setItem('sPlantName','野洲工場');
                    localStorage.setItem('sPlantVal','1000079');
                    plant_id="1000079";
                    $( this ).dialog( "close" );
                }},{ text:"山崎工場",click :function(ev) {
                    location.href="/RFIDReport/SetBase?plant=山崎工場&personname="+personname;
                    localStorage.setItem('sPlantName','山崎工場');
                    localStorage.setItem('sPlantVal','1000073');
                    plant_id="1000073";
                    $( this ).dialog( "close" );
                }},{ text:"NPG",click :function(ev) {
                    location.href="/RFIDReport/SetBase?plant=NPG&personname="+personname;
                    localStorage.setItem('sPlantName','NPG');
                    localStorage.setItem('sPlantVal','1000125');
                    plant_id="1000125";
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
        }else{
            plant_id=localStorage.getItem('sPlantVal');
        }

        if(plant_id=="" || !plant_id){
            if(plant=="野洲工場"){
                localStorage.setItem('sPlantName','野洲工場');
                localStorage.setItem('sPlantVal','1000079');
                plant_id="1000079";
            }else if(plant=="山崎工場"){
                localStorage.setItem('sPlantName','山崎工場');
                localStorage.setItem('sPlantVal','1000073');
                plant_id="1000073";
            }else if(plant=="NPG"){
                localStorage.setItem('sPlantName','NPG');
                localStorage.setItem('sPlantVal','1000125');
                plant_id="1000125";
            }
        }
        
        gp1 = get_group_by_name(userlist,'usergp1');
        var gp_item={}
        gp2 = $.map(userlist, function( n, i ){
            return [n.usergp1+","+n.usergp2];
        });
        gp2=[...new Set(gp2)];
        $.each(gp2,function(k,v){
            let sp_v=v.split(",");
            gp_item[k]={gp1:sp_v[0],gp2:sp_v[1]}
        });
        gp2=gp_item;

        $.each(gp1,function(kgp1,vgp1){
            user_obj[vgp1]={};
        });
        $.each(gp2,function(kgp2,vgp2){
            user_obj[vgp2.gp1][vgp2.gp2]=[];
            $.each(userlist,function(kuser,user){
                if(user.usergp2==vgp2.gp2&&user.usergp1==vgp2.gp1){
                    user_obj[vgp2.gp1][vgp2.gp2].push(user.label);
                }
            });
        });

        var user_plant = plant.substr(0,plant.length-2);
        // set_user_dialog(user_plant);
        var user_gr = "製造係_1班";
        if(user_plant=="山崎"){
            user_gr="製造係";
        }
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(user_plant)+"&gp2="+decodeURIComponent(user_gr),function(data){
            $("#ap-user-select").html(data);
        });


        $("input[type='radio']").button();

        getProcess();

        $("#personname").on('focus',function(){
            if($("#personname").val()){
                checkUser();
            }else{
                setTimeout(() => {
                    if(!video && qr_reader=="camera"){
                        od('personname','作業者のコード');
                    } 
                }, 100);
            }
        });

        $("#personname").on('focusout',function(){
            if($("#personname").val() && qr_reader=="camera"){
                searchUserCam($("#personname").val());
            }
        });

        $("#personname").on('click',function(){
            if(!video && $("#personname").val()==""){
                if(qr_reader=="camera"){
                    $("#personname").focus();
                }else{
                    $( "#dialog_user" ).dialog( "open" );
                    $( "#input_name" ).focus();
                }
            }
        });

        $("#作業工程").on('focus',function(){
            // $("#作業工程").bind('focusout');
            if(!video && qr_reader=="camera" && $("#作業工程").val()==""){
                od('作業工程','作業工程のコード');
            }
        });

        $("#作業工程").on('focusout',function(){
            val = document.getElementById("作業工程").value;
            if(parseInt(val)<10){
                val="0"+parseInt(val);
            }
            var opts = document.getElementById("work_list").options;
            let flag = 0;
            for (var i = 0; i < opts.length; i++) {
                let barcode = opts[i].text;
                if(parseInt(barcode)<10){
                    barcode="0"+barcode;
                }
                if (opts[i].value == val || barcode == val) {
                    $("#作業工程").val(opts[i].value);
                    // $("#作業工程").unbind('focusout');
                    flag = 1;
                    if(opts[i].value.indexOf("組立")>-1){
                        $("#counter_mode").val("count_up");
                        // $('#counter_mode option[value=count_down]').remove();
                        $("#master_setting").hide();
                        $("#assembly_setting").show();
                    }else{
                        // $("#counter_mode").append('<option value="count_down">[－]</option>');
                        $("#counter_mode").val("count_down");
                        $("#master_setting").show();
                        $("#assembly_setting").hide();
                    }
                    break;
                }
            }
            if(val!=""){
                if(flag===0){
                    $("#作業工程").val("");
                    $("#作業工程").addClass('retry');
                    setTimeout(()=>{
                        if(!video && qr_reader=="camera"){
                            od('作業工程','作業工程のコード')
                        }
                    }, 500);
                }else{
                    setTimeout(()=>{focus_control()}, 500);
                }
            }
        });

        $("#Lotコード").on('focus',function(){
            if($("#Lotコード").val()=="" && !video && qr_reader=="camera"){
                od('Lotコード','QRコード');
            }
        });

        $("#Lotコード").on('click',function(){
            $("#Lotコード").focus();
        });

        $("#Lotコード").on('dblclick',function(){
            $("#Lotコード").focus()
            if(!video){
                qr_reader="camera";
                od('Lotコード','QRコード');
            }
        });

        $("#Lotコード").on('keydown', function(e) {
            if(e.which === 13){
                let code = $("#Lotコード").val();
                newcode=code.replace(/\^/g,"=");
                $("#Lotコード").val(newcode);
                if($("#Lotコード").val()){
                    // getLotCode();
                    getLotCodeJsonFile();
                }
            }
        });

        $("#作業工程").keypress(function(e) {
            if (e.keyCode == 13) {
                $("#作業工程").focusout();
            }
        });

        $("label[for='作業日']").width();
        $('input[type="text"]').each(function(){
            var f_width = 370;
            var l_width=$("label[for='"+this.id+"']").width();
            var i_width=f_width-l_width;
        });

        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width: '1200px',
            modal:true,
            position:["center",100],
            buttons: [{ text: "閉じる", click: function() {
                $( this ).dialog( "close" );
            }}]
        });
        let QRd_w = $("#right_site").width()+100;
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
        $(".ui-dialog-titlebar-close").hide();
        // if(sessionStorage.getItem("user") != null && sessionStorage.getItem("user") != "undefined"){
        //    var user =JSON.parse(sessionStorage.getItem("user"));
        //    $( "#personname" ).val(user);
        // }
        let cplw = $(window).width()-20;
        $( "#checked_list" ).dialog({
            autoOpen: false,
            width: cplw,
            modal:true,
            position:["center",100],
            buttons: [{ text: "閉じる", click: function() {
                $( this ).dialog( "close" );
            }}]
        });

        if(sessionStorage.getItem("LotCode") != null && sessionStorage.getItem("LotCode") != "undefined" && sessionStorage.getItem("item_list") != null){
            add_working();
        }
        let first_check=check_entry_status();
        if(first_check[0]===false){
            open_check_dialog(first_check[1]);
        }else{
            $("#xls_entry").prop("disabled", true);
            checkUser();
            focus_control();
        }
        if($("#personname").val()!=""){
            getLastHgpdData();
        }

        var timer = false;
        $(window).resize(function(){
            if (timer !== false) {
                clearTimeout(timer);
            }
            timer = setTimeout(() => {
                resize_page();
            }, 200);
        });
        setTimeout(() => {
            resize_page();
        }, 100);
        $("#btn_digital").button("disable");
    });

    function add_working(){
        let lot_code = JSON.parse(sessionStorage.getItem("LotCode"));
        let item_list = JSON.parse(sessionStorage.getItem("item_list"));
        lot_code = lot_code.split(",");
        $.each(lot_code,function(k,v){
            check_lot = true;
            if(item_list[v+"][受入数"]>0){
                let sp_v = v.split("-");
                let total = item_list[v+"][完成数"]+item_list[v+"][廃棄数"];
                let table = "";
                table+='<tr class="data" id="'+v+'" value="'+v+'">'
                table+='<td>'+sp_v[2]+"-"+sp_v[3]+'</td>'
                table+='<td>'+sp_v[0]+'</td>'
                //table+='<td>'+$("#品名").val()+'</td>'
                //table+='<td id="キャビ_'+v+'" value="'+code[3]+'">'+code[3]+'</td>'
                table+='<td id="受入数_'+v+'">'+item_list[v+"][受入数"]+'</td>'
                table+='<td id="廃棄数_'+v+'">'+item_list[v+"][廃棄数"]+'</td>'
                table+='<td id="員数ミス_'+v+'">'+item_list[v+"][員数ミス"]+'</td>'
                table+='<td id="完成数_'+v+'">'+item_list[v+"][完成数"]+'</td>'
                table+='<td id="良品率_'+v+'">'+(item_list[v+"][完成数"]*100/total).toFixed(2)+'</td>'
                table+='<td id="作業時間_'+v+'">'+item_list[v+"][作業時間"]+'</td>'
                table+='<td id="出来高_'+v+'">'+(item_list[v+"][完成数"]/(item_list[v+"][作業時間"]/3600)).toFixed(2)+'</td>'
                let stt_class = "entring";
                if(item_list[v+"][状態"]=="登録済み"){
                    stt_class = "entred";
                }
                table+='<td id="状態_'+v+'" class="'+stt_class+'">'+item_list[v+"][状態"]+'</td>'
                table+='</tr>'
                $("#molds").append(table);
            }
        });
    }

    function resize_page(){
        var wh = $(window).height()-20;
        $(".main_cont").height(wh-$(".menu").height());
        $("#right_site").height(wh-277);
    }

    function check_entry_status(){
        let get_total =JSON.parse(localStorage.getItem("totalling"));
        let bk_data =JSON.parse(localStorage.getItem("bk_data"));
        let flag = true,text="";
        if(get_total){
            flag = false;
            text = "setbase";
            let lot_code = JSON.parse(sessionStorage.getItem("LotCode"));
            let item_list = JSON.parse(sessionStorage.getItem("item_list"));
            if(!lot_code || !item_list){
                console.log("add lot")
                let nlc = get_total["基本][Lotコード"]+"-"+get_total["基本][型番"]+"-"+get_total["基本][案件コード"];
                if(!lot_code){
                    sessionStorage.setItem("LotCode",JSON.stringify(nlc));
                }
                if(!item_list){
                    let item_list={};
                    item_list[nlc+'][受入数'] = parseInt(get_total["受入数"]);
                    if(get_total["廃棄数"]){
                        item_list[nlc+'][廃棄数'] = parseInt(get_total["廃棄数"]);
                    }else{
                        item_list[nlc+'][廃棄数'] = 0;
                    }
                    item_list[nlc+'][員数ミス'] = parseInt(get_total["員数ミス"]);
                    item_list[nlc+'][完成数'] = parseInt(get_total["完成数"]);
                    item_list[nlc+'][作業時間'] = parseInt(get_total["基本][作業時間"]);
                    item_list[nlc+'][状態'] = "未登録";
                    sessionStorage.setItem("item_list",JSON.stringify(item_list));
                }
                setTimeout(() => {
                    add_working();
                }, 0);
            }
        }
        if(bk_data){
            flag = false;
            text = "counter";
        } 
        return [flag,text];
    }

    function open_check_dialog(page){
        if(page=="setbase"){
            let msg = "<b style='color:darkorange;'>実績登録しますか？</b>\n"
            var options = {"title":"確認",
                position:["center",50],
                buttons: [{ class:"btn-right btn-ent",text:"実績登録",click :function(ev) {
                    $( this ).dialog( "close" );
                    let totalling=JSON.parse(localStorage.getItem("totalling"));
                    getXlsEntry();
                    // window.open(url);
                }},{ class:"btn-left",text:"キャンセル",click :function(ev) {
                    $( this ).dialog( "close" );
                    // focus_control();
                }}
                // ,{ class:"btn-left btn-del",text:"データ削除",click :function(ev) {
                //     localStorage.removeItem("totalling");
                //     sessionStorage.removeItem("item_list");
                //     location.reload();
                // }}
                ]
            };

            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
        }
        if(page=="counter"){
            let msg = "<b style='color:darkorange;'>登録していないデータがあります。カウンター画面へ移動しますか？</b>\n"
            var options = {"title":"注意！！！",
                position:["center",50],
                buttons: [{ class:"btn-right btn-ent",text:"カウンター画面へ",click :function(ev) {
                    $( this ).dialog( "close" );
                    var url = "/RFIDReport/TallyCounter/"
                    location.href = url;
                    // window.open(url);
                }},{ class:"btn-left",text:"キャンセル",click :function(ev) {
                    $( this ).dialog( "close" );
                    focus_control();
                }}
                // ,{ class:"btn-left btn-del",text:"データ削除",click :function(ev) {
                //     localStorage.removeItem("bk_data");
                //     $( this ).dialog( "close" );
                //     location.reload();
                // }}
                ]
            };

            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
        } 
    }

    function get_data(dataAry, key, value) {
        return new Promise((resolve) => {
            var result = $.grep(dataAry, function (e) {
                return e[key] == value;
            });
            resolve(result);
        })
    }

    function get_rfid_data(dataAry, key, value) {
        var result =[];
        for(i=0;i<dataAry.length;i++){
            if(dataAry[i][key]!=null && dataAry[i][key].indexOf(value)!==-1){
                // console.log(i)
                result.push(dataAry[i]);
                break;
            }
        };
        return result; 
    }

    function get_group_by_name(dataAry, name){
        var result=$.map(dataAry, function( n, i ){
            return n[name];
        });
        result=[...new Set(result)];
        return result;
    }

    async function getProcess(){
        let process_str ="";
        let plant = '<?=$sf_params->get("plant")?>';
        workitem_list = await get_data(worklist,'workitem_plant_name',plant);
        $.each(workitem_list, function(key,item){
            process_str+='<option value="'+item["workitem_no"]+':'+item["workitem_name"]+'" label="'+item["workitem_name"]+'">'+item["workitem_barcode"]+'</option>'
            // process_str+='<option value="'+item["workitem_name"]+'" label="'+item["workitem_name"]+'">'+item["workitem_barcode"]+'</option>'
        });
        $("#work_list").html(process_str);
    }

    function getRfidInfo(code){
        return new Promise((resolve) => {
            let process=$("#作業工程").val().split(":");
            let search_wi = process.slice(1).join(":");
            let bom_mode = $("input[name=ms_bom]:checked").val();
            if(bom_mode!=="on"){
                bom_mode="off";
            }

            $.ajax({
                type: 'GET',
                url: "",
                async : false,
                dataType: 'json',
                data:{
                    "ac":"getRfid",
                    "bom_mode":bom_mode,
                    "code":code,
                    "process":search_wi
                },
                success: function(d) {
                    // if(d=="")
                    resolve(d);
                }
            })
        });
    }

    function checkBomInfo(itemcode){
        return new Promise((resolve) => {
            let process=$("#作業工程").val().split(":");
            let search_wi = process.slice(1).join(":");
            $.ajax({
                type: "GET",
                url: "",
                data: {
                    "ac":"checkBomInfo",
                    "itemcode":itemcode,
                    "process":search_wi
                },
                dataType: "json",
                success: function (d) {
                    resolve(d);
                }
            });
        })
    }

    async function getLotCodeJsonFile(){
        let code = $("#Lotコード").val();
        // if($("#作業工程").val().indexOf("組立")>-1){
        //     code = $("#部品1").val();
        // }
        let get_info = [],sp_code=[];
        // let check_rfid = await get_data(rfid_molding_info,'search_rfid',code);
        
        if($("#作業工程").val()==""){
            openAlert("確認","工程を入力してください。");
            setTimeout(() => {
                $("#作業工程").focus();
            }, 0);
            return;
        }

        if($("#作業工程").val().indexOf("組立")>-1){
            getAssemblyInfo(code);
            return;
        }else{    
            sp_code = code.split("=>");
            $("#qrCode").val(code);
            let q="";
            for(i=0;i<=2;i++) { q+= sp_code[i]+"=>"; }
            if(sp_code[3]===undefined) sp_code[3]="";
            $("#キャビ").val(sp_code[3]);
            get_info = await get_data(molding_info,'search_key',q);
            // sessionStorage.setItem("LotInfoAllItem",JSON.stringify(get_info));
        }

        console.log(get_info);

        if(get_info.length!==1){
            let check_rfid = await getRfidInfo(code);
            if(check_rfid[0]=="OK"){
                // if(check_rfid[0]=="0"){
                //     openAlert("確認","入力したのRFIDは検査済です。");
                //     $(".ms_info").val("");
                //     return;  
                // }else if(check_rfid[0]=="none"){
                //     openAlert("確認","入力したのRFIDは未登録です。<br>生産情報がありません。");
                //     return;  
                // }
                // let check = await rfid_status(code);
                // // true:在庫０->利用出来る
                // // false:在庫有->利用出来ない
                // if(check==true){
                //     alert("RFIDは未利用です。")
                //     return;
                // }
                get_info=check_rfid[1];
                sessionStorage.setItem("LotInfoAllItem",JSON.stringify(get_info));
                // console.log(get_info)
                code = get_info[0]["itemcord"]+"=>"+get_info[0]["moldlot"]+"=>"+get_info[0]["itemform"]+"=>"+get_info[0]["hgpd_cav"];
                // $("#Lotコード").val(code);
                $("#qrCode").val(code);
                $("#キャビ").val(get_info[0]["hgpd_cav"]);
                $("#lotNum").val(get_info[0]["moldlot"]);
                sp_code = code.split("=>");
            }else{
                var options = {"title":"確認",
                    position:["center",50],
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $("#Lotコード").focus();
                        $("#Lotコード").select();
                        $( "#alert" ).dialog( "close" );
                        return;
                    }}],
                    open: function(event, ui) {
                        $('.ui-dialog :button').blur();
                    }
                };
                $("#message").html(check_rfid[1]);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                return;
            }
 
        }

        info_data=get_info[0];

        if($("input[name=ms_bom]:checked").val()=="on"){
            let bom_check = await checkBomInfo(info_data.itemcord);
            if(bom_check!=="OK"){
                var options = {"title":"確認",
                    position:["center",50],
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $( "#alert" ).dialog( "close" );
                        return;
                    }}],
                    open: function(event, ui) {
                        $('.ui-dialog :button').blur();
                    }
                };
                $("#message").html(bom_check);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" )
                return;
            }
        }

        $("#btn_start").focus();
        $("#right_site").html("");
        setTimeout(() => {
            get_plot_data(null,null,null,sp_code); 
        }, 0);
        setTimeout(() => {
            loadingViewPlot(false);
        }, 10000);

        if(info_data.searchtag && info_data.searchtag!=""){
            $("#品名").val(info_data.searchtag);
        }else{
            $("#品名").val(info_data.itemname);
        }
        $("#itemname").val(info_data.itemname);
        $("#品目コード").val(info_data.itemcord);
        $("#型番").val(info_data.itemform);
        if(get_info.length>1){
            $("#数量").val(info_data.hgpd_qtycomplete);
        }else{
            $("#数量").val(info_data.wic_qty_in);
        }
        $("#wi_cav").val(info_data.cav_items);
        $("#wi_in").val(info_data.pieces);
        $("#wi_pakn").val(info_data.tray_stok);
        $("#wi_tin").val(info_data.tray_num);
        $("#bf_code").val(info_data.hgpd_id);

        if(info_data.cav_items){
            $('input[name="cav"]').val(['あり']).button( "refresh" );
        }else{
            $('input[name="cav"]').val(['なし']).button( "refresh" );
        }

        // let w_list="";
        // $.each(d.workitem,function(key,val){
        //     let wi=val.workitem_no+":"+val.workitem_name;
        //     w_list+="<option value='"+wi+"' label='"+val.workitem_name+"'>"+val.workitem_barcode+"</option>";
        //     if(val.workitem=="成形+GC"){
        //         $('input[name="cav"]').val(['あり']).button( "refresh" );
        //     }
        //     if(val.workitem=="成形"){
        //         $('input[name="cav"]').val(['なし']).button( "refresh" );
        //     }
        // });
        // $("#work_list").html(w_list);

        // id = sp_code[1]+"-"+sp_code[2]+"-"+sp_code[0];
        let id = info_data.moldlot+"-"+info_data.itemform+"-"+info_data.itemcord;
        let lot_code = "";
        lot_code =JSON.parse(sessionStorage.getItem("LotCode"));
        var all_lot = "";
        var check_lot =false;
        if(lot_code != null){
            lot_code = lot_code.split(",");
            $.each(lot_code,function(k,v){
                if(v==id){
                    check_lot = true;
                }
            });
            setTimeout(() => {
                if(check_lot == false){
                    all_lot = lot_code+","+id;
                    sessionStorage.setItem("LotCode",JSON.stringify(all_lot));
                }
            }, 0);
        }else{
            sessionStorage.setItem("LotCode",JSON.stringify(id));
        }
        if(check_lot == false || !$("#"+id).attr("value")){
            var table = "";
            table+='<tr class="data" id="'+id+'" value="'+id+'">'
            table+='<td>'+info_data.itemcord+'</td>'
            table+='<td>'+info_data.moldlot+'</td>'
            // table+='<td>'+$("#品名").val()+'</td>'
            //table+='<td id="キャビ_'+id+'" value="'+code[3]+'">'+code[3]+'</td>'
            table+='<td id="受入数_'+id+'"></td>'
            table+='<td id="廃棄数_'+id+'"></td>'
            table+='<td id="員数ミス_'+id+'"></td>'
            table+='<td id="完成数_'+id+'"></td>'
            table+='<td id="良品率_'+id+'"></td>'
            table+='<td id="作業時間_'+id+'"></td>'
            table+='<td id="出来高_'+id+'"></td>'
            table+='<td id="状態_'+id+'" class="entring" >未検査</td>'
            table+='</tr>'
            $("#molds").append(table);
        }
        //データ一時保存
        sessionStorage.setItem("LotInfoItem",JSON.stringify(info_data));

        //デジタルのチェック
        if($("#personname").val()==""){
            $("#personname").focus();
        }else if($("#作業工程").val()==""){
            $("#作業工程").focus();
        }else if($("#Lotコード").val()==""){
            $("#Lotコード").focus();
        }else{
            $("#btn_start").focus();
        }
        setTimeout(() => {
            openDigital(false);
        }, 0);
   
    }

    function getAssemblyInfo(code){
        let process=$("#作業工程").val().split(":");
        let search_wi = process.slice(1).join(":");
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getAssemblyInfo",
                item_code:code,
                process:search_wi
            },
            dataType: "json",
            success: function (d) {
                // console.log(d);
                if(d["assembly_info"].length>0){
                    $("#btn_start").focus();
                    $("#right_site").html("");
                    setTimeout(() => {
                        //get_plot_data(null,null,null,sp_code); 
                    }, 0);
                    setTimeout(() => {
                        loadingViewPlot(false);
                    }, 10000);
                    if(d["item_info"] && d["item_info"].searchtag!=""){
                        $("#品名").val(d["item_info"].searchtag);
                    }else{
                        $("#品名").val(d["item_info"].itemname);
                    }
                    $("#itemname").val(d["item_info"].itemname);
                    $("#品目コード").val(d["item_info"].itempprocord);
                    $("#型番").val(d["item_info"].form);
                    let add_material ="";
                    
                    $.each(d["assembly_info"],function(a,b){
                        let it_name = b.itemname
                        if(b.searchtag!=""){
                            it_name = b.searchtag
                        }
                        add_material+=`<p style="">
                            <label for="品目コード_`+a+`">部品`+(a+1)+`</label><input type="text" value="`+b.itempprocord+`" id="品目コード_`+a+`" class="parts_item" placeholder="品目コード" autocomplete="off" style="width:145px;" />
                            <label for="品名_`+a+`">品名</label><input type="text" value="`+it_name+`" id="品名_`+a+`" placeholder="品名" autocomplete="off" style="width:255px;" />
                        </p>`
                    });
                    $("#parts_info").html(add_material);
                }else{
                    var msg = "品目を見つけません。";
                    var options = {"title":"注意!!!",
                        width: 500,
                        buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    loadingView(false);
                    $("#alert").dialog( "open" );
                }
            }
        });
    }

    var d={};
    function getLotCode(){
        //loadingView(true);
        var code = $("#Lotコード").val();
        var sp_code = code.split("=>");
        $("#キャビ").val(sp_code.slice(-1)[0]);
        $.ajax({
            type: 'GET',
            url: "",
            async : false,
            dataType: 'json',
            data:{
                "ac":"getInfo",
                "code":code,
                "itemcord":sp_code[0],
                "lot":sp_code[1],
                "itemform":sp_code[2],
                "cavNo":sp_code[3],
                "username":$("#personname").val()
            },
            success: function(d) {
                if(d=="NG"){
                    var options = {"title":"確認",
                        position:["center",50],
                        buttons: [{ text:"OK",click :function(ev) {
                            $("#Lotコード").focus();
                            $("#Lotコード").select();
                            $( this ).dialog( "close" );
                        }}]
                    };
                    $("#message").html("もう一度スキャンしてください。");
                    $( "#alert" ).dialog( "option",options);
                    $( "#alert" ).dialog( "open" ); 
                    return;
                }
                if(d.info){
                    $("#btn_start").focus();
                    get_plot_data();
                    $("#品名").val(d.info.itemname);
                    $("#品目コード").val(sp_code[0]);
                    $("#型番").val(sp_code[2]);
                    $("#wi_cav").val(d.item_info.cav_items);
                    $("#wi_in").val(d.item_info.pieces);
                    if (d.item.length > 0){
                        $("#wi_pakn").val(d.item[0].tray_stok);
                        $("#wi_tin").val(d.item[0].tray_num);
                    }
                    let w_list="";
                    // console.log(d.workitem);
                    $.each(d.workitem,function(key,val){
                        let wi=val.workitem_no+":"+val.workitem_name;
                        w_list+="<option value='"+wi+"' label='"+val.workitem_name+"'>"+val.workitem_barcode+"</option>";
                        if(val.workitem=="成形+GC"){
                            $('input[name="cav"]').val(['あり']).button( "refresh" );
                        }
                        if(val.workitem=="成形"){
                            $('input[name="cav"]').val(['なし']).button( "refresh" );
                        }
                    });
                    $("#work_list").html(w_list);

                    id = sp_code[1]+"-"+sp_code[2]+"-"+sp_code[0];
                    lot_code = "";
                    lot_code =JSON.parse(sessionStorage.getItem("LotCode"));
                    var all_lot = "";
                    var check_lot =false;
                    if(lot_code != null){
                        lot_code = lot_code.split(",");
                        $.each(lot_code,function(k){
                            if(lot_code[k] == id){
                                check_lot = true;
                            }
                        });
                        if(check_lot == false){
                            all_lot = lot_code+","+id;
                            // sessionStorage.setItem("LotCode",JSON.stringify(all_lot));
                        }
                    }else{
                        // sessionStorage.setItem("LotCode",JSON.stringify(id));
                    }
                    if(check_lot == false || $("#"+id).attr("value") == null){
                        var table = "";
                        table+='<tr class="data" id="'+id+'" value="'+id+'">'
                        table+='<td>'+id+'</td>'
                        // table+='<td>'+$("#品名").val()+'</td>'
                        //table+='<td id="キャビ_'+id+'" value="'+code[3]+'">'+code[3]+'</td>'
                        table+='<td id="受入数_'+id+'"></td>'
                        table+='<td id="廃棄数_'+id+'"></td>'
                        table+='<td id="員数ミス_'+id+'"></td>'
                        table+='<td id="完成数_'+id+'"></td>'
                        table+='<td id="良品率_'+id+'"></td>'
                        table+='<td id="作業時間_'+id+'"></td>'
                        table+='<td id="出来高_'+id+'"></td>'
                        table+='<td id="状態_'+id+'"></td>'
                        table+='</tr>'
                        $("#molds").append(table);
                    }
                    //データ一時保存
                    sessionStorage.setItem("LotInfoItem",JSON.stringify(d));
                    if($("#personname").val()==""){
                        $("#personname").focus();
                    }else if($("#作業工程").val()==""){
                        $("#作業工程").focus();
                    }else{
                        $("#btn_start").focus();
                    }
                }else{
                    var options = {"title":"確認",
                        position:["center",50],
                        buttons: [{ text:"OK",click :function(ev) {
                            $("#Lotコード").focus();
                            $("#Lotコード").select();
                            $( this ).dialog( "close" );
                        }}]
                    };
                    $("#message").html("品目を見つけません。");
                    $( "#alert" ).dialog( "option",options);
                    $( "#alert" ).dialog( "open" );
                }
                //loadingView(false);
            }
        });
    }

    function focus_control(){
        setTimeout(() => {
            if($("#personname").val()==""){
                $("#personname").focus();
            }else if($("#作業工程").val()==""){
                $("#作業工程").focus();
            // }else{
            //     if($("#作業工程").val().indexOf("組立")>-1){
            //         if($("#部品1").val()==""){
            //             $("#部品1").focus();
            //         }else if($("#部品2").val()==""){
            //             $("#部品2").focus();
            //         }
            //     }else{
            //         if($("#Lotコード").val()==""){
            //             $("#Lotコード").focus();
            //         }
            //     }
            // }
            }else if($("#Lotコード").val()==""){
                $("#Lotコード").focus();
            }
        }, 100);
    }

    function checkUser(){
        let flag=false;
        if($("#personname").val()){
            $.each(userlist,function(k,data){
                if(data.label==$("#personname").val()){
                    flag=true;
                    $("#personname").unbind("focusout");
                    return;
                }
            });
        }
        if(flag==false){
            $("#personname").val("");
            $("#personname").addClass('retry');
            if(!video && qr_reader=="camera"){
                $("#personname").focus();
            }
        }else{
            return;
        }
    }

    function searchUser(){
        $.getJSON("/LotManagement/BarcodeUserSet?ac=user&code="+decodeURIComponent($("#input_name").val()),function(data){
            if(data.length>0){
                $("#personname").val(data[0].user);
                $( "#dialog_user" ).dialog( "close" );
                $("#input_name").val("");
                setTimeout(()=>{focus_control()}, 500);
                document.activeElement.blur();
            }else{
                $("#input_name").val("");
                $("#input_name").focus();
            }
        });
        return false;
    }

    function searchUserCam(user_code){
        let flag=false;
        let plant="<?=$sf_params->get("plant")?>";
        $.each(userlist,function(k,data){
            if(data.usercord==user_code){
                flag=true;
                $("#personname").val(data.label);
                getLastHgpdData();
                $("#personname").attr('readonly', 'readonly');
                $("#personname").unbind('focusout');
                window.history.pushState('', 'Title', '?plant='+plant+'&personname='+data.label);
                setTimeout(()=>{focus_control()}, 500);
                document.activeElement.blur();
            }
        });
        if(flag==false){
            $("#personname").val("");
            $("#personname").addClass('retry');
            setTimeout(()=>{
                if(!video  && qr_reader=="camera"){
                    od('personname','作業者のコード');
                    return;
                }
            }, 500);
        }
    }

    function setUser(name){
        $( "#dialog_user" ).dialog( "close" );
        $("#personname").val(name);
        $("#personname").attr('readonly', 'readonly');
        let plant="<?=$sf_params->get("plant")?>";
        window.history.pushState('', 'Title', '?plant='+plant+'&personname='+name);
        getLastHgpdData();
        setTimeout(()=>{focus_control()}, 500);
    }

    function getLastHgpdData(){
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getLastHgpdData",
                user:$("#personname").val()
            },
            dataType: "json",
            success: function (res) {
                let in_table="";
                $.each(res, function (k, v) { 
                    let rfid = v.hgpd_rfid;
                    if(rfid.length>26){
                        rfid = "NALUX"+rfid.slice(-8);
                    }
                    in_table+=`<tr>
                        <td>`+v.hgpd_id+`</td>
                        <td>`+rfid+`</td>
                        <td>`+v.hgpd_itemcode+`</td>
                        <td>`+v.hgpd_process+`</td>
                        <td style="text-align:center;">`+v.hgpd_itemform+`</td>
                        <td style="text-align:center;">`+v.hgpd_cav+`</td>
                        <td style="text-align:right;">`+v.hgpd_quantity+`</td>
                        <td style="text-align:right;">`+v.hgpd_qtycomplete+`</td>
                        <td style="text-align:right;">`+v.hgpd_difactive+`</td>
                        <td>`+v.hgpd_name+`</td>
                        <td>`+v.created_at+`</td>
                    </tr>`
                });
                $("#realtime_data").html(in_table);
            }
        });
    }

    function getStart(){
        if(localStorage.getItem("totalling")){
            console.log(JSON.parse(localStorage.getItem("totalling")));
            let msg = "<b style='color:darkorange;'>実績登録しますか？</b>\n"
            var options = {"title":"注意！！！",
                position:["center",50],
                buttons: [{ class:"btn-right btn-ent",text:"実績登録",click :function(ev) {
                    $( this ).dialog( "close" );
                    getXlsEntry();
                    // window.open(url);
                }},{ class:"btn-left",text:"キャンセル",click :function(ev) {
                    $( this ).dialog( "close" );
                    focus_control();
                }}
                // ,{ class:"btn-left btn-del",text:"データ削除",click :function(ev) {
                //     localStorage.removeItem("totalling");
                //     location.reload();
                // }}
                ]
            };

            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
        }else if(localStorage.getItem("bk_data")){
            let msg = "<b style='color:red;'>登録していないデータがあります。カウンター画面へ移動します！</b>\n"
            var options = {"title":"注意！！！",
                position:["center",50],
                buttons: [{ class:"btn-right",text:"移動する",click :function(ev) {
                    $( this ).dialog( "close" );
                    var url = "/RFIDReport/TallyCounter/"
                    location.href = url;
                    // window.open(url);
                }}]
            };

            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
        } else {
            var sp_lotdata = $("#qrCode").val().split("=>");
            var lotNum = sp_lotdata[1];
            if(sp_lotdata.length==1){
                //rfid
                lotNum=$("#lotNum").val();
            }
            var d={};
            d["Lotコード"]=($("#Lotコード").val());
            d["作業者"]=($("#personname").val());
            d["作業工程"]=$("#作業工程").val();
            d["品名"]=$("#品名").val();
            d["品目コード"]=$("#品目コード").val();
            if($("#作業工程").val().indexOf("組立")==-1){
                //組立以外
                d["型番"]=$("#型番").val(); 
                d["取数"]=$("#wi_in").val();
                if(document.getElementById('a1').checked){
                    d["キャビ内訳"]=$("#wi_cav").val();
                }
                d["収納数"]=$("#wi_tin").val();
                d["梱包数"]=$("#wi_pakn").val();
            }
            msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
            var errc=0;
            $.each(d,function(key) {
                if($.trim(d[key])==""){
                    msg = msg +"<li>"+key+"</li>\n";
                    errc++;
                }
            });
            if(errc===0){
                var bk_data={};
                if(localStorage.getItem("bk_data") == null || localStorage.getItem("bk_data") == "undefined"){

                    if($("#作業工程").val().indexOf("組立")>-1){
                        //組立場合
                        let parts_item = $(".parts_item");
                        console.log(parts_item);
                        let list_parts="";
                        $.each(parts_item,function(a,b){
                            list_parts+=b.value+",";
                        })
                        list_parts=list_parts.substr(0,list_parts.length-1);
                        bk_data["list_parts"] = list_parts;
                        bk_data["担当者"] = $("#personname").val();
                        bk_data["担当者"] = $("#personname").val();
                        bk_data["作業工程"] = $("#作業工程").val();
                        bk_data["品名"] = $("#品名").val();
                        bk_data["itemname"]=$("#itemname").val();
                        bk_data["案件コード"] = $("#品目コード").val();
                        bk_data["キャビ"] = $("#キャビ").val();
                    }else{
                        //組立以外
                        bk_data["担当者"] = $("#personname").val();
                        bk_data["作業工程"] = $("#作業工程").val();
                        bk_data["品名"] = $("#品名").val();
                        bk_data["itemname"]=$("#itemname").val();
                        bk_data["案件コード"] = $("#品目コード").val();
                        bk_data["型番"] = $("#型番").val();
                        bk_data["Lotコード"] = lotNum;

                        let info_item = JSON.parse(sessionStorage.getItem("LotInfoItem"));
                        let info_all_item = JSON.parse(sessionStorage.getItem("LotInfoAllItem"));
                        console.log(info_all_item);
                        // return;
                        bk_data["link_to_proc"]=false;

                        if(info_all_item != null && info_all_item.length>1 && $("#作業工程").val().indexOf("保留処理")>-1 && info_item.workitem=="保留処理"){
                            bk_data["link_to_proc"]="1";
                            bk_data["all_cav"] = info_item["hgpd_cav"].replace(/-/,",");
                            bk_data["キャビ"] = bk_data["all_cav"].split(",")[0];
                            $.each(info_all_item,function(aik,aiv){
                                let bf_num_cav_id = "前良品_"+aiv.wic_itemcav;
                                bk_data[bf_num_cav_id] = parseInt(aiv.wic_qty_in);
                            })
                        }else{
                            bk_data["link_to_proc"]="0";
                            if(info_item["hgpd_cav"]){
                                bk_data["all_cav"] = info_item["hgpd_cav"];
                            }else{
                                bk_data["all_cav"] = $("#キャビ").val();
                            }
                            bk_data["キャビ"] = $("#キャビ").val();
                        }

                        if($("#作業工程").val().indexOf("保留処理")>-1 && info_all_item.length>1){
                            // bk_data["キャビ"] = $("#キャビ").val().replace(/-/,",");
                            bk_data["前良品"] = info_item["hgpd_qtycomplete"];
                        }else{
                            bk_data["前良品"] = info_item["wic_qty_in"];
                        }
                 
                        bk_data["原料名"] = info_item["materialsname"];
                        bk_data["原料コード"] = info_item["materialcode"];
                        bk_data["原料ロット"] = info_item["materialslot"];
                        bk_data["号機"] = info_item["moldmachine"];
                        bk_data["単価"] = info_item["adm_price_std"];
                        bk_data["成形日"] = info_item["hgpd_moldday"];
                        bk_data["前工程ID"] = info_item["hgpd_id"];
                        bk_data["rfid"] = info_item["search_rfid"];
                        // bk_data["ml_flg"] = info_item["ml_flg"];
                    }
                    if(bk_data["キャビ"]!=""){
                        localStorage.setItem("Tabs",JSON.stringify(bk_data["all_cav"]));
                    }else{
                        localStorage.setItem("Tabs",JSON.stringify("1"));
                    }

                    let plant_id=localStorage.getItem('sPlantVal');
                    let plant = '<?=$sf_params->get("plant")?>';
                    bk_data["工場ID"] = plant_id;
                    bk_data["工場"] = plant;
                    let bom_mode = $("input[name=ms_bom]:checked").val();
                    if(bom_mode!=="on"){
                        bom_mode="off";
                    }
                    bk_data["bom_mode"] = bom_mode;
                    localStorage.setItem("bk_data",JSON.stringify(bk_data));
                    let info_item = JSON.parse(sessionStorage.getItem("LotInfoItem"));
                    let id = info_item.moldlot+"-"+info_item.itemform+"-"+info_item.itemcord;
              
                    lot_code = "";
                    lot_code =JSON.parse(sessionStorage.getItem("LotCode"));
                    var all_lot = "";
                    var check_lot =false;
                    if(lot_code != null){
                        lot_code = lot_code.split(",");
                        $.each(lot_code,function(k){
                            if(lot_code[k] == id){
                                check_lot = true;
                            }
                        });
                        if(check_lot == false){
                            all_lot = lot_code+","+id;
                            sessionStorage.setItem("LotCode",JSON.stringify(all_lot));
                        }
                    }else{
                        sessionStorage.setItem("LotCode",JSON.stringify(id));
                    }
                    // console.log(bk_data);
                    counter_mode=$("#counter_mode").val();
                    localStorage.setItem("counter_mode",counter_mode);

                    var url = "/RFIDReport/TallyCounter/"
                    location.href = url;
                    // window.open(url);
                }else{
                    //console.log(localStorage.getItem("bk_data"));
                    //bk_data = JSON.parse(localStorage.getItem("bk_data"));
                    var msg = "製品交換前にデータを登録してください。";
                    var options = {"title":"注意!!!",
                        width: '600px',
                        buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog( "open" );
                }
            }else{
                var options = {"title":"確認",
                    position:["center",50],
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        $("#view_ms_name").focus();
                    }}]
                };
                $("#message").html(msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }

        }   
    }

    function rfid_status(rfid){
        return new Promise((cb) => {
            let linked_id = $(".check_rfid");
            let c_flag = false;
            $.each(linked_id,function(key,val){
                if(val.value==rfid){
                    c_flag = true;
                }
            })
            if(c_flag){
                $("#err_msg").html("<p style='margin-top:10px;color:red;'>RFIDは利用中です。</p>");
                del_msg();
                return false;
            }else{
                loadingView(true);
                $.ajax({
                    type: "GET",
                    url: "/RFIDReport/RfidCheckStatus",
                    dataType: "json",
                    data: {
                        rfid:rfid,
                    },
                    success: function(d){
                        loadingView(false);
                        cb(d);
                    }
                });
            } 
        })
    }

    function sumCav(d){
        lot_code =JSON.parse(sessionStorage.getItem("LotCode"));
        lot_code = lot_code.split(",");
        n = 0;
        $.each(lot_code,function(k){
            id = d+lot_code[k];
            n = n + parseInt($("#"+id).html());
        });
        return n;
    }

    function check_Storage(){
        if(localStorage.getItem("bk_data")){
            console.log("データある");
            return true;
        }else{
            console.log("データない");
            return false;
        }
    }

    function sumItem(id,num){
        var sum = 0;
        if($(id).html() != ""){
            sum = parseInt($(id).html()) + parseInt(num);
        }else{
            sum = parseInt(num);
        }
        return sum;
    }

    function getEntry(){
        var data = $(".data").html();
    }

    // 現在時刻組み立て
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
            return year+"/"+month+"/"+day+" "+hour+":"+min+":"+sec;
        }
        if(str=="ti"){
            return hour+":"+min;
        }
    }
    function validate(evt) {
        var theEvent = evt || window.event;
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
        // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]/;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    function get_plot_data(ac,user,mno,code){
        //loadingView(true);
        loadingViewPlot(true);
        if(!code||code==""){
            code = $("#qrCode").val().split("=>");
        }
        if(!user){
            user ="%";
        }
        var machine= "%";
        if(mno){
            machine= mno;
        }
        $.ajax({
            type: 'GET',
            url: "/RFIDReport/SetBase",
            dataType: 'json',
            // timeout:10000,
            data:{
                ac:"getInfo",
                mode:"getPlotData",
                itemcode:code[0],
                moldlot:code[1],
                itemform:code[2],
                username:user,
                mno:machine
            },
            success: function(d) {
                // alert(JSON.stringify(d));
                loadingViewPlot(false);
                add_right_site(d)
            },
            error: function (xhr, textStatus, errorThrown) { 
                // エラーと判定された場合
                loadingViewPlot(false);
                if(textStatus === 'timeout'){     
                    alert("通信問題のせいで、製品検索履歴表示出来ません。");
                }
            }
        }).done(function() {
            loadingViewPlot(false);
            // alert("完了");
        }).fail(function( jqXHR, textStatus ) {
            loadingViewPlot(false);
            // alert( "Request failed: " + textStatus );
        });
    }

    function add_right_site(d){
        let rs=`<p>製品検索履歴 <button type="button" onclick="get_plot_data('','','');" class="btn-vh"  style="margin-bottom:5px;" >戻る</button><p>
        <div id="table_plot">
            <div id="mold_list"></div>
            <table class="tb_total_table">
                <thead>
                    <tr>
                    <th class="tb_total_th" style="width: 200px;">工程</th>
                    <th class="tb_total_th" style="width: 80px;">合計時間</th>
                    <th class="tb_total_th" style="width: 90px;">1個/秒</th>
                    <th class="tb_total_th" style="width: 90px;">生産数</th>
                    <th class="tb_total_th" style="width: 90px;">良品数</th>
                    <th class="tb_total_th" style="width: 80px;">不良数</th>
                    <th class="tb_total_th" style="width: 80px;">不良率</th>
                    <th class="tb_total_th" style="width: 40px;">数</th>
                    <th class="tb_total_th" style="width: 60px;">詳細</th>
                    </tr>
                </thead>
                <tbody id="plot_table_body">
                </tbody>
            </table>
            <div>
                <div class="plot_width" id="plot_area"></div>
            </div>
        </div>`;
        $("#right_site").html(rs);

        let i=0;
        let mold_list=`<p style="font-size:14px;font-weight: none;padding:0 0 4px 0;" class="view_none">成形機No.`;
            if(d["moldmachinearray"]){
                $.each(d["moldmachinearray"],function(mno_key,mno){
                    mold_list+=`<button type="button" value="`+mno+`" onclick="get_plot_data('','','`+mno+`')" class="btn-vh" >`+mno+`</button>`;
                });
            }
        mold_list+=`</p>`;
        $("#mold_list").html(mold_list);
        $.each(d.total,function(key,reslt){
            i++; 
            let infokey = "key"+i; 
            let td_class="hide-td";
            let this_itemcord=reslt["xlsnum"]+":"+key;
            if($("#作業工程").val()==this_itemcord){
                td_class= "view-td";
            }
            let table_plit=`<tr>
                <th class="tb_total_td" style="text-align: left;">`;
                    if(reslt["xlsnum"]!=""){
                        table_plit+=reslt["xlsnum"]+":"+key
                    }else{
                        table_plit+=key
                    }
                table_plit+=`</th>`;

                let spp="0";
                if (reslt["goodnum"]=="0"){
                    spp = "0";
                }else{ 
                    spp= (reslt["totaltime"]/reslt["goodnum"]*100).toFixed(2);
                }
                let bad_percent="0";
                if(reslt["totalnum"]!=="0" && reslt["badnum"]>0){ 
                    bad_percent= (reslt["badnum"]/reslt["totalnum"]*100).toFixed(2);
                }
                table_plit+=`<td class="tb_total_td" style="text-align:center;">`+parseFloat(reslt["totaltime"])+`分</td>
                <td class="tb_total_td" style="text-align: center;">`+spp+`秒</td>
                <td class="tb_total_td" style="text-align: center;">`+parseFloat(reslt["totalnum"])+`</td>
                <td class="tb_total_td" style="text-align: center;">`+parseFloat(reslt["goodnum"])+`</td>
                <td class="tb_total_td" style="text-align: center;">`+parseFloat(reslt["badnum"])+`</td>
                <td class="tb_total_td" style="text-align: center;">`+bad_percent+`%</td>
                <td class="tb_total_td" style="text-align: center;">`+reslt["count"]+`</td>

                <td class="tb_total_td" style="text-align: center;">`;
                if(reslt["badnum"]!="0" || reslt["username"]!=""){ 
                    table_plit+=`<button type="button" value="" onclick="showdefectivesitem('id`+infokey+`')" class="btn-vh" >表示</button>`;
                }
                table_plit+=`</td>
            </tr>
            <tr class="none id`+infokey+` `+td_class+`" style="">
                <td class="tb_total_td" colspan="8" style="padding:3px;">`;
                    if(reslt["badnum"]!="0"){ 
                        table_plit+=`<div id="total_d_`+infokey+`" style=""></div>`;
                    } 
                    let user_list = reslt.usernameArray.join(" , ");
                    // user_list =user_list.replace(/\,/g," , ");
                    table_plit+=`
                    <p style="font-size: 12px;">担当者：`+reslt["usernameArray"].length+` 名： [ `;
                        $.each(reslt["usernameArray"],function(k,username){
                            table_plit+=`<span onclick="get_plot_data('','`+username+`','')" style="text-decoration: none;color:slategray;padding-left:2px;padding-right:2px;cursor:pointer"">`+username+`</span>　`;
                        });
                    table_plit+=` ]</p>
                </td>
                <td class="tb_total_td" style="text-align: center;">
                    <button type="button" value="" onclick="hidedefectivesitem('id`+infokey+`')" class="btn-vh" >隠す</button>
                </td>
            </tr>`;
            $("#plot_table_body").append(table_plit);
            plot_add(infokey,reslt);
        });
        $(".hide-td").css({"display":"none"});
        $("button").button();
        // loadingView(false);
    }

    function plot_add(infokey,reslt){
     
        if(reslt.defectivesitem){
            $('.total_d_'+infokey).html("");
            let defectivesitem = {};

            $.each(reslt.defectivesitem,function(key,row) {
                defectivesitem[key]=row;
            });

            let pilotdata =[],pilotlabel=[],shortdata=[];

            $.each(defectivesitem,function(kr,dnum){
                pilotdata.push([kr+":["+dnum+"]:("+((dnum/reslt["badnum"])*100).toFixed(1)+"%)",dnum]);
                shortdata.push([kr+":("+((dnum/reslt["badnum"])*100).toFixed(1)+"%)",dnum]);
                // shortdata.push([kr+":["+dnum+"]",dnum]);
                pilotlabel.push([kr+":("+((dnum/reslt["badnum"])*100).toFixed(1)+"%)"]);
            });

            let clocount = 1 ;
            let legend_row =Object.keys(reslt.defectivesitem).length;
            let legend_font = "12px";
            let data = pilotdata;
            if(legend_row>9){
                clocount = 2;
                legend_font = "8px";
            } 
            if(legend_row>21){
                clocount = 3;
                data=shortdata;
            } 
    
            if(plot){
                plot.draw();
            }
            var plot = $.jqplot('total_d_'+infokey,[data],
                {
                    seriesDefaults: {
                        renderer: jQuery.jqplot.PieRenderer,
                        rendererOptions: {
                            padding: 4,
                            //dataLabels: 'percent',
                            dataLabels: pilotlabel,
                            showDataLabels: true,
                            // startAngle: -90,
                            textColor:'#000',
                            fontSize: '10px'
                        }
                    },
                    legend: {
                        show: true,
                        location: 'w',
                        rendererOptions: {
                            numberColumns: clocount,
                            textColor:'#000',
                            fontSize: legend_font
                        }
                    },
                    grid: {
                        borderColor: '#000',    // 枠の色
                        background: '#FFF',		// 背景の色
                        borderWidth: 0,
                        shadow: false
                    }

                }
            );

        }
    }

    function showdefectivesitem(str){
        $("."+str).css({"display":""});
    }

    function hidedefectivesitem(str){
        $("."+str).css({"display":"none"});
    }

    function allshow(){
        $(".none").css({"display":""});
    }

    function allhide(){
        $(".none").css({"display":"none"});
    }

    function CloseView(){
        if (/Chrome/i.test(navigator.userAgent)) {
        window.close();
        } else {
        window.open('about:blank', '_self').close();
        }
    }

    function go_previous(){
        window.history.back();
    }


    function od(id,name,fcmode){
        //loadingView(true);
        $("#"+id).attr('readonly', 'readonly');
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:600 }}).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            tick();
        });
        
        let button = [
            { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
                qr_reader="scaner";
                if(id=="personname"){
                    stop_scan();
                    $("#dialog_user").dialog( "open" );
                    $("#input_name").focus();
                    return;
                }else if(id=="作業工程"){
                    stop_scan();
                    $("#作業工程").removeAttr('readonly');
                    $("#作業工程").focus();
                    return;
                }else if(id=="Lotコード"){
                    stop_scan();
                    $("#Lotコード").removeAttr('readonly');
                    // $("#Lotコード").unbind('focus');
                    $("#Lotコード").focus();
                    return;
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
        // //loadingView(false);
        $("#QRScan").dialog( "open" );
        $("#"+id).focus();
        setTimeout(function(){
            stop_scan();
        }, 300000);
    }
    const sleepy = ms => new Promise(resolve => setTimeout(resolve, ms));
    async function tick() {
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

            if(code){
                drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                playMelody(2200);
                await sleepy(300);
                if(code.data){
                    // camera_off=true;
                    $("#"+document.activeElement.id).val(code.data);
                    if(document.activeElement.id=="Lotコード"){
                        getLotCodeJsonFile();
                    }
                    $("#"+document.activeElement.id).focusout();
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

    async function stop_scan(){
        // console.log()
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

    function menuClear(){
        let plant = '<?=$sf_params->get("plant")?>';
        let url = "/RFIDReport/SetBase?plant="+plant+"&personname=";
        location.href = url;
    }

    function openCam(){
        qr_reader="camera";
        focus_control();
    }

    function getXlsEntry(){
        let totalling=JSON.parse(localStorage.getItem("totalling"));
        if(totalling){
            $("#xls_entry").prop("disabled", true);
            let now_lot_id = totalling["基本][Lotコード"]+"-"+totalling["基本][型番"]+"-"+totalling["基本][案件コード"];
            loadingView(true);
            $.ajax({
                type: 'POST',
                url: "/RFIDReport/SetBase",
                data:{ ac:"XlsEntry", data:totalling },
                dataType: 'json',
                // async : false,
                //タイムアウト: 10s
                // timeout:10000,
                success: function(d) {
                    loadingView(false);
                    if(d=="OK"){
                        let item_list = JSON.parse(sessionStorage.getItem("item_list"));
                        item_list[now_lot_id+"][状態"]="登録済み";
                        sessionStorage.setItem("item_list",JSON.stringify(item_list));
                        $("#状態_"+now_lot_id).removeClass("entring").addClass("entred");
                        $("#状態_"+now_lot_id).html("登録済み");
                        localStorage.removeItem("totalling");
                        openAlert("完了！","登録しました。",null,function(){focus_control()});
                    }else{
                        openAlert("登録できません。！",d,null);
                        $("#xls_entry").prop("disabled", false);
                    }
                },
                error: function (xhr, textStatus, errorThrown) { 
                    // エラーと判定された場合
                    loadingView(false);
                    $("#xls_entry").prop("disabled", false);
                    if(textStatus === 'timeout'){
                        // openAlert("タイムアウト","通信が実施されていない！！！<br>もう一度やり直してください。",null);
                        openAlert("タイムアウト","通信が実施されていない！！！<br>もう一度やり直してください。<br>Status: "+xhr.status+"<br>StatusText: "+xhr.statusText+"<br>"+errorThrown,null);
                    }else{
                        openAlert("登録できません。！","不明のエーラが発生しました。<br>管理者に連絡してください。<br>Status: "+xhr.status+"<br>StatusText: "+xhr.statusText+"<br>"+errorThrown,null);
                    }
                }
            });
        }else{
            $("#xls_entry").prop("disabled", true);
        }
    }

    function loadingViewPlot(flag) {
        $('#add_right_load').remove();
        if(!flag) return;
        let wh = $(window).height()-297;
        $('#right_site').prepend('<div id="add_right_load" style="height:'+wh+'px;" ><span class="right_loader"></span></div>');
    }

    var all_checked_list =[];
    function getCheckedSetData(rfid){
        let totalling=JSON.parse(localStorage.getItem("totalling"));
        var box_num = 50;
        var pick_num = get_table_val("table_content",["rfid"]).length + 1;
       
        $("#cpl_max_num").val(box_num);

        //検査済のデータ収得
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getCheckedSetData",
                rfid:rfid
            },
            dataType: "json",
            success: function (d) {
                // console.log(d);
                if(d){
                    all_checked_list.push(d);
                    console.log(all_checked_list);
                }else{
                    console.log(all_checked_list);
                    return;
                }

                var checked_table = "";
                var confirm_table = "";
                var sum_cpl_num = 0;
                var have_num = 0;

                checked_table+="<tr>";
                checked_table+="<td>"+d.wic_rfid+"</td>";
                checked_table+="<td>"+d.wic_itemcode+"</td>";
                checked_table+="<td>"+d.wic_itemform+"</td>";
                checked_table+="<td>"+d.hgpd_moldlot+"</td>";
                checked_table+="<td>"+d.hgpd_moldday+"</td>";
                checked_table+="<td>"+d.hgpd_cav+"</td>";
                checked_table+="<td>"+d.hgpd_id+"</td>";
                checked_table+="<td>"+d.wic_qty_in+"</td>";
                checked_table+="</tr>";

                confirm_table+="<tr>";
                confirm_table+="<td class='used-num'>0</td>";
                confirm_table+="<td class='have-num'>"+d.wic_qty_in+"</td>";
                confirm_table+="</tr>";

                $("#table_content").append(checked_table);
                $("#confirm_content").append(confirm_table);
                let pick_list = get_table_val("table_content",["rfid","itemcode","itemform","moldlot","moldday","cav","hgpd_id","num"]);
                var sum_pick = 0
                $.each(pick_list, function (indexInArray, valueOfElement) { 
                    sum_pick+=parseInt(valueOfElement.num);
                });
                $("#pick_num").val(sum_pick);
                $("#pick_complete").val(parseInt(sum_pick/box_num));
                
            }
        });
    }

    function goCounter(rfid){
        var cpl_num = get_table_val("complete_content",["no","rfid"]).length + 1;
        var max_cpl_num =$("#pick_complete").val();
        if(cpl_num>max_cpl_num){
            alert("仕掛品数が足りません！！！");
            return;
        }
        var box_num = $("#cpl_max_num").val();
        var need_num = parseInt(box_num)*cpl_num;
        var now_need = need_num;
        var no_reset_num = 0;
        $("#cpl_need_num").val(need_num);
        var complete_table = "<tr><td>"+cpl_num+"</td><td>"+rfid+"</td></tr>";

        $("#complete_content").append(complete_table);

        var confirm_table = "";
        var sum_cpl_num = 0;
        var have_num = 0;
        $.each(all_checked_list, function (key, value) {
            sum_cpl_num += parseInt(value.hgpd_qtycomplete)+have_num;
            confirm_table+="<tr>";
            if(no_reset_num==need_num){
                confirm_table+="<td class='used-num'>"+value.hgpd_qtycomplete+"</td>";
                confirm_table+="<td class='have-num'>0</td>";
            }else if(no_reset_num<need_num){
                let try_num = now_need - parseInt(value.hgpd_qtycomplete);
                if(try_num>0){
                    now_need=try_num;
                    confirm_table+="<td class='used-num'>"+value.hgpd_qtycomplete+"</td>";
                    confirm_table+="<td class='have-num'>0</td>";
                }else{
                    confirm_table+="<td class='used-num'>"+now_need+"</td>";
                    confirm_table+="<td class='have-num'>"+(parseInt(value.hgpd_qtycomplete)-now_need)+"</td>"; 
                }
            }else{
                confirm_table+="<td class='used-num'>0</td>";
                confirm_table+="<td class='have-num'>"+value.hgpd_qtycomplete+"</td>";
            }

            no_reset_num += parseInt(value.hgpd_qtycomplete);

            confirm_table+="</tr>";
        });

        $("#confirm_content").html(confirm_table);

    }

    function openScanChecked(id,name){
        od(id)
    }

    function cancel_complete(){
        all_checked_list =[];
        $( "#checked_list" ).dialog( "close" );;
    }

    function goComplete(){
        let totalling=JSON.parse(localStorage.getItem("totalling"));
        console.log(totalling);
        var checked_table = "";
        checked_table+=`<div style="">
            <div style="float:left;margin-right:20px;"><b>まるめ数：</b><input id='cpl_max_num' value ='0' style='text-align:right;width:80px;' />個</div>
            <div style="float:left;margin-right:20px;"><b>受け数：</b><input id='pick_num' value ='0' style='text-align:right;width:80px;' />個</div>
            <div style="float:left;margin-right:20px;"><b>最大完成ID：</b><input id='pick_complete' value ='0' style='text-align:right;width:80px;' />枚</div>
        </div>`;
        checked_table+="<div style='float:left;width:100%;'>"
        checked_table+= `
            <div style="float:left;margin:10px 0;"><label>仕掛品のRFID：</label>
                <input id="rfid_device" value="" placeholder="仕掛品のRFIDをスキャン" style="width: 400px;height:28px;" ></input>
                <button type="button" onclick="od('rfid_device','仕掛品のRFID');" class="btn_ditemset" style="margin-left:10px;width:50px;height:35px;background-color:#00c3ff;">
                <span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
            </div>`;
        checked_table+= `<div style='float:right;width:25%;margin-top:32px;'>計算</div>`
        checked_table+= "<div style='margin:10px 0;float:left;width:74%;'>"
        

        checked_table+="<table class='type04' ><tr><th>仕掛ID</th><th>品名</th><th>型</th><th>成形ロット</th><th>成形日</th><th>キャビ</th><th>管理No</th><th>検査済数量</th></tr>";
        checked_table+="<tbody id='table_content'>";
        checked_table+="</tbody></table>";
        // checked_table+='<button id="add_checked" type="button" onclick="openScanChecked()" class="btn-menu" style="float:left;margin-top:5px;padding:5px 12px;">仕掛品IDスキャン</button>';

        checked_table+= '<hr style ="width:100%;margin:20px 0 15px 0;"></hr>';
        
        checked_table+= `
            <div style="float:left;margin:10px 0;"><label>完成品のRFID：</label>
                <input id="rfid_complete" value="" placeholder="完成品のRFIDをスキャン" style="width: 400px;height:28px;" ></input>
                <button type="button" onclick="od('rfid_complete','完成品のRFID');" class="btn_ditemset" style="margin-left:10px;width:50px;height:35px;background-color:#00c3ff;">
                <span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
            </div>
            <div style="float:right;margin:10px 0;">合計数：<input id='cpl_need_num' value ='0' style='text-align:right;width:80px;' />個</div>
        `;
        checked_table+= "<table class='type04' ><tr><th>No.</th><th>完成品ID</th></tr>";
        checked_table+="<tbody id='complete_content'>";
        checked_table+="</tbody></table>";
        checked_table+='</div>';

        checked_table+="<div style='margin:10px 0;float:right;width:25%;'><table class='type04'><tr><th>ID登録数</th><th>残り</th></tr>";
        checked_table+="<tbody id='confirm_content'>";
        checked_table+="</tbody></table>";
        checked_table+='</div>';
        checked_table+='</div>';
        // checked_table+='<button id="add_complete" type="button" onclick="getCompleteId()" class="btn-menu" style="float:left;margin-top:5px;padding:5px 12px;">完成IDスキャン</button></div></div>';
        
        checked_table+='<button id="" type="button" onclick="" class="btn-menu" style="float:right;margin-top:5px;padding:5px 12px;">登録</button>';
        checked_table+='<button id="" type="button" onclick="cancel_complete();" class="btn-menu" style="float:left;margin-top:5px;padding:5px 12px;">閉じる</button>';

        var options = {
            position:["center",50],
            buttons: []
        };
        $("#checked_table").html(checked_table);

        $("#rfid_device").on("keyup",function(e){
            if(e.keyCode==13){
                getCheckedSetData(e.target.value);
            }
        })
        $("#rfid_complete").on("keyup",function(e){
            if(e.keyCode==13){
                goCounter(e.target.value);
            }
        })

        $( "#checked_list" ).dialog( "option",options);
        $( "#checked_list" ).dialog( "open" );
        $(".ui-resizable-handle").hide();
        $(".ui-icon-closethick").hide();
        $("#cpl_max_num").blur();
        $("button").button();
    }

    function get_table_val(id,name_list){
        const table = document.querySelector("#"+id); 
        var rows = Array.from(table.rows);
        var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
        var data = [];
        $.each(cells,function(a,b){
            // if(a>0){
                let r ={};
                $.each(name_list,function(k,v){
                    r[v]=b[k]
                })
                data.push(r)
            // }
        })
        return data;
    }

    function openSiteComplete(){
        x = parseInt((screen.width) * 0.98);
        y = parseInt((screen.height) * 0.84);
        //w = parseInt((screen.width-x)/2);
        h = parseInt((screen.height-y)/2);
        w = window.parent.screen.width;
        let plant = "<?=$sf_params->get("plant")?>";
        let user = $("#personname").val();
        var url= "/RFIDReport/ItemCompleteProcess?plant="+plant+"&user="+user;
        console.log(x+","+y+","+w+","+h+","+window.screenTop);
        window.open(url,'完成品処理','left='+w+',top='+h+',width='+x+',height='+y+',scrollbars=0,toolbar=0,menubar=0,staus=0,resizable=0');
        $("#menu_dialog").dialog( "close" );
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

    function openDigital(flag){
        let itemcode = $("#品目コード").val();
        // 選択された工程通りでチェック
        let work_process = $("#作業工程").val().split(":")[1];
        //工程名は同一してないので、「最終検査」固定する
        // let work_process = "最終検査";
        let file_name = itemcode.substr(0,4)+"_"+itemcode+"_"+work_process+".pdf";
        Std_digitization('btn_digital',file_name,flag);
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
                    $("#"+id).button("enable");
                }
            }
        }).done(function() {
            if(open_flag && open_flag===true) window.open(uri);
        });
    } 

    function goItemRound(){
        let url = "/RFIDReport/RoundingDeviceID?user="+$("#personname").val();
        window.open(url);
    }

</script>

<div id="left_site" style="width:47%;float:left;padding:5px 5px 0 5px;">
    <div class="menu" style="">
        <fieldset>
            <legend>作業段取り・設定画面</legend>
            <p style="">
                <label for="作業日">作業日</label><input id="作業日" type="text" value="<?= $data["日付"]?>" placeholder="作業日" style="width:65px;text-align:center;"/>
                <label for="勤務"> 勤務</label><input id="勤務" type="text" value="<?= $data["勤務"]?>" placeholder="勤務" style="width:65px;text-align:center;"/>
                <label for="personname"> 作業者</label><input type="text" id="personname" name="personname" placeholder="作業者" value="<?php echo $sf_params->get('personname')?>" class="ime-off" readonly="readonly" style="width:240px;" />
            </p>
            <p style="">
                <label for="作業工程"> 工程</label><input id="作業工程" type="tel" value="" placeholder="作業工程" list="work_list" autocomplete="off" style="ime-mode:disabled;width:220px;margin-left: 5px;margin-right:5px;"/>
                <datalist id="work_list">
                </datalist>
                <label for="counter_mode">モード</label>
                <select id="counter_mode" style='background-color: #fff;border: 1px solid #ccc;border-radius: 4px;' >
                    <option value="count_down" >[－] </option>
                    <option value="count_up" >[✚] </option>
                </select>
                <button type="button" onclick="openCam();" class="btn_ditemset btn_topmenu" style="width:60px;"><span class="ui-icon-camera ui-btn-icon-left" ></span></button>
                <button type="button" onclick="menuClear();" class="btn_ditemset btn_topmenu" style=";">クリア</button>
            </p>
            <p style="">
                <!-- <label for="Lotコード">Lotコード</label><input id="Lotコード" type="text" value="STAN0047-00=>220228=>7=>1,2" placeholder="ロットコード" autocomplete="off"  style="width:250px;font-size:16px;font-weight:bold;"/> -->
                <label for="Lotコード" style="margin-right:2px">QRコード</label>
                <input id="Lotコード" type="tel" pattern="[0-9]*" value="" placeholder="ロットコード" autocomplete="off" dir="rtl" style="ime-mode:disabled;width: 388px;height:30px;font-size: 16px;font-weight:bold;margin-right:2px;"/>
                <button type="button" onclick="getLotCodeJsonFile();" class="btn_ditemset btn_topmenu" style="">検索</button>
            </p>
        </fieldset>

        <fieldset>
            <legend>ロット管理データ情報</legend>
            <p style="">
                <label for="品目コード">品目コード</label><input type="text" value="" id="品目コード" class="ms_info" placeholder="品目コード" autocomplete="off" style="width:200px;" />
                <label for="型番">型番</label><input type="text" value="" id="型番" class="ms_info" placeholder="型番" autocomplete="off" style="width:55px;"/>
                <label for="キャビ">キャビ№</label><input type="text" value="" id="キャビ" class="ms_info" placeholder="№" autocomplete="off"  style="width:55px;"/>
                <input type="hidden" value="" id="lotNum" placeholder="" class="ms_info" autocomplete="off"  style=""/>
                <input type="hidden" value="" id="qrCode" placeholder="" class="ms_info" autocomplete="off"  style=""/>
            </p>
                        
            <p style="">
                <label for="数量">数</label><input type="text" value="" id="数量" placeholder="数" autocomplete="off"  style="width:55px;"/>
                <label for="品名">品名</label><input type="text" value="" id="品名" placeholder="品名" autocomplete="off" style="max-width: 425px;min-width: 300px;width:100%;" />
                <input type="hidden" value="" id="itemname" placeholder="" autocomplete="off" style="width:470px;" />
            </p>
        </fieldset>
        
        <fieldset id="master_setting">
            <legend>作業マスター設定</legend>
            <p style="">
                <label>キャビ分け</lable>
                <input type="radio" name="cav" class="cav" id="a1" class="btn-menu" value="あり" /><label for="a1">あり</label>
                <input type="radio" name="cav" class="cav" id="a2" class="btn-menu" value="なし" /><label for="a2" style="margin-left:-10px;">なし</label>
                <label for="wi_cav">キャビ内訳</label><input type="text" value="" id="wi_cav" class="ms_info" autocomplete="off" placeholder="カンマ区切り" style="width:240px" />
            </p>
            <p style="">
                <input type="checkbox" id="ms_bom" class="" name="ms_bom" checked="checked" style="" /><label for="ms_bom">BOM連携</label>
                <label for="wi_in" style="margin:0 10px 0 0;">取数</label><input type="text" value="" id="wi_in" class="ms_info" autocomplete="off" placeholder="" style="width:75px" />
                <label for="wi_tin" style="margin:0 10px 0 10px;">収納数</label><input type="text" value="" id="wi_tin" class="ms_info" autocomplete="off" placeholder="1トレー" style="width:75px" />
                <label for="wi_pakn" style="margin:0 10px 0 10px;">梱包数</label><input type="text" value="" id="wi_pakn" class="ms_info" autocomplete="off" placeholder="1梱包" style="width:75px" />
            </p>
        </fieldset>

        <fieldset id="assembly_setting">
            <legend>組立の部品情報</legend>
            <div id="parts_info"></div>
        </fieldset>
        <button id="btn_start" type="button" onclick="getStart();" class="btn-menu" style="padding:6px 15px;">開始</button>
        <button id="btn_digital" type="button" onclick="openDigital(true);" class="btn-menu" style="padding:6px 6px;">ﾁｪｯｸﾎﾟｲﾝﾄ</button>
        <button id="xls_entry" type="button" onclick="getXlsEntry();" class="btn-menu" style="float:right;padding:6px 12px;">実績登録</button>
        <button id="btn_complete" type="button" onclick="openSiteComplete();" class="btn-menu" style="float:right;padding:6px 12px;">完成ID登録</button>
        <button id="btn_item_round" type="button" onclick="goItemRound();" class="btn-menu" style="float:right;padding:6px 6px;">端数寄せ</button>
        
    </div>
    <div style="clear:both;"></div>
    <div class="main_cont" style="">
        <p>作業実績</p>
        <!-- <span>作業日</span>
        <span>作業者</span>
        <span>ajaxで作り直し：作業者入力確認後</span> -->
        <table class="type03" style="text-align:center;font-size:70%;font-weight:bold;">
            <tr>
                <th style="width:70px;">製品</th>
                <th style="width:40px;">成形Lot</th>
                <!-- <th>キャビ</th> -->
                <th style="width:40px;">受入数</th>
                <th>廃棄数</th>
                <th>員数ミス</th>
                <th>完成数</th>
                <th>良品率(%)</th>
                <th>作業時間</th>
                <th>出来高</th>
                <th>状態</th>
            </tr>
            <tbody id = "molds">
            </tbody>
        </table>
    </div>
</div>
<div style="float:left;width:51%;margin-top:15px;">
    <div id="right_site" style="">
    </div>
    <div id="right_history" style="padding:5px 0 0 0;">
        <p style="margin:5px 0 5px 4px;">実績登録履歴</p>
        <table id="view_table" class="type03" style="width:100%;font-size:60%;">
            <thead style="text-align:center;">
                <tr>
                    <th style="width:40px;">ID</th>
                    <th style="">RFID</th>
                    <th style="width:70px;">品目</th>
                    <th style="width:50px;">工程</th>
                    <th style="width:20px;">型番</th>
                    <th style="width:20px;">Cav</th>
                    <th style="width:20px;">受け</th>
                    <th style="width:20px;">良品</th>
                    <th style="width:20px;">廃棄</th>
                    <th style="width:60px;">担当者</th>
                    <th style="width:100px;">日付</th>
                </tr>
            </thead>
            <tbody id="realtime_data">
            </tbody>
        </table>
    </div>
</div>
<div id="show_dialog">
    <div id="alert" style="">
        <div id="message" style="text-align: center;"></div>
    </div>
</div>
<div id="dialog_user" title="作業者を選択してください" style="">
    <div id="ap-user-select"></div>
    <hr style="margin:5px 0 5px 0;color:#ECEADB;">
    コード入力：<input type="text" id="input_name" name="name" value="" style="margin-top:10px;" />
    <button id="addUser" type="button" onclick="searchUser();" style="ime-mode: disabled;">決定</button>
</div>

<div style="display:block;">
    <div id="QR_code_camera"></div>
</div>

<div id="checked_list" title="完成品登録" style="">
    <div id="checked_table"></div>
</div>