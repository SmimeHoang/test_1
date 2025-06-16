
<?php
    use_javascript("jquery/jqplot/jquery.jqplot.min.js");
    use_javascript("jsQR.min.js");
    
    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");

    slot('h1', '<h1 class="header-text" style="margin:0 auto;">溶着工程実績入力 | Nalux '.$sf_request->getParameter('plant').' </h1>');
    $btn='<div style="float:right;" id="h_button"><button onclick="openActualManagement()">予実管理表</button></div>';
    slot('cd',$btn);
 ?>
<meta name="viewport" content="initial-scale=.8">

<style type="text/css">
    #content {width:100%; font-size:16px; text-align;margin:auto;}

    #tab_item .ui-button .ui-button-text {width:25px;height:25px;padding:0;}

    .fl { float: left;}
    .fr { float: right;}
    #ditemsetpop { padding-top:2px;}
    #ditemsetpop .set {font-size:90%; line-height:1em;vertical-align: middle;display: block;width:185px; border: 1px #FFF solid;margin-left: -1px;margin-top: -1px;padding: 2px 3px ; }
    .box{ width:114px; text-align:center;float:left;margin:0 2px 2px 0;background-color: #ddd; }

    #main_menu input, #item_area input{padding:2px 0;text-align:center;font-weight:bold;font-size:16px;}
    .n_input{border:none;width:92%;margin:3px;font-size:16px;text-align:center;font-weight:bold;color: #999;background-color: #ddd;}
    #addUser{font-size:80%;padding:0.1em 0.8em;margin: 0 0 0 5px;}
    #entryData span, #状態 span, .btn_ditemset .ui-button-text, #OpenSTD span {padding: 0;}
    #dialog hr {margin:5px 0 5px 0;}
    #user-list {margin-bottom:5px;}
    .main_cont {padding:5px;border:1px solid #00f;border-radius:5px;}
    .chk_proc .ui-button-text {padding: 1px 10px;}
    .ipbox{float:left;margin-right:8px;text-align:center;}
    .ui-widget{font-size:16px;}

    #dialog_user .ui-button-text{
        padding: 0px 5px;
    }
    
    #item_area .sum_value{width:60px;text-align:left;padding-left:5px;margin-right:5px;}

    table.type03 {
        /* font-size:80%; */
        border-collapse: collapse;
        text-align: center;
        line-height: 1.2;
        table-layout: fixed;
        font-size: 12px; 
    }
    
    table.type03 th {
        padding: 2px;
        font-weight: bold;
        vertical-align: middle;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
        background: aliceblue;
        box-shadow: 0px 0px 0px 1px #000;
    }
    table.type03 td {
        padding: 2px;
        vertical-align: middle;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
        text-align:center;
        /* word-break: keep-all; */
        overflow: hidden;
        text-overflow: hidden;
    }

    .table-scroll{
        border: 1px solid #ccc;
    }

    #item_area{
        overflow-y: scroll;
        max-height: calc(100% - 135px);
    }

    .inserted-click{
        background-color: #75c100;
    }
    .items_info_ip {
        font-size:16px !important;
        height: 28px;
    }
    #main_menu .ui-state-active{
        background: palegreen;
        border: 2px solid green;
    }

    .ui-dialog{background:#f9f9f9 !important;}

    .btn-confirm .ui-button-text,.btn-right .ui-button-text {
        padding: 6px 10px;
    }
    .btn-confirm{
        width:115px;
        float:right;
    }
    .btn-right{
        float:right;
    }
    .btn-cancel .ui-button-text {
        padding: 6px 10px;
    }
    .btn-cancel{
        width:115px;
        float:left;
    }
    .sl-box{
        font-size: 17px;
        height:34px;
        background-color: white;
        border: 1px solid #aaa;
        border-radius: 4px;
    }
    .change-stock,.change-cav{
        color:darkorange;
    }
    
    .readonly-input{
        color:blue;
        border-top: solid 1px #FFF;
        border-left: solid 1px #FFF;
        border-right: solid 1px #FFF;
        border-bottom: solid 2px #ccc;
        border-radius: 0;
    }
    .readonly-input:focus{
        outline: none;
    }
    .readonly-input:hover{
        border-top: solid 1px #FFF;
        border-left: solid 1px #FFF;
        border-right: solid 1px #FFF;
        border-bottom: solid 2px #ccc;
        cursor: default;
    }

    .toggle-color{
        -webkit-animation: color-change 1s infinite;
    }
    .ip-color{
        color:#000;
    }
    .receive_num{
        font-weight: bold;
    }
    .num-dialog{
        margin-top:5px;
    }
    #number_in{
        word-spacing: -5px;
    }
    #number_in .num_btn {
        width:60px;
        height:60px;
        padding:1px;
        margin: 2px 2px;
        border-radius: 25px;
    }
    #number_in .num_btn span{
        padding:18px 0 0 0;
        font-weight: bold;
    }

    .ui-button-text-only .ui-button-text{
        padding: 2px 6px;
    }

    .btn, .num_btn{
        -khtml-user-select: none;
        -moz-user-select: none;
        -webkit-touch-callout: none;
        -webkit-user-select: none; /* Safari */
        -ms-user-select: none; /* IE 10 and IE 11 */
        user-select: none; /* Standard syntax */
    }


    .ui-btn-icon-right::after{
        display:block;
        margin: -17px -9px 0 0px;
        width: 34px;
        height: 34px;
    }

    .ui-btn-icon-right {
        padding-right: 25px;
    }

    .btn_set_round{
        position: initial;
        margin-left: 0;
        font-size: 15px;
        background: #ececec;
        padding: 3px;
        border: solid 1px #999;
        border-radius: 10px;
        color: #0070ff;
        font-weight: normal;
        display: inline-block;
        line-height: 1em;
        /* white-space: nowrap; */
    }

    .ui-btn-icon-left{
        /* padding-left: 12px; */
        line-height: 20px;
        padding: 0;
        width: 45px;
    }
    .ui-btn-icon-left::after {
        background-size: 85%;
        margin-top: -12px;
        left: 9px;
        width: 25px;
        height: 25px;
    }

    .tr_entred{
        background:#bbb;
    }

    @keyframes color-change {
        0% {
            color: darkorange;
        }
        50% {
            color: white;
        }
        100% {
            color: darkorange;
        }
    }

    @keyframes border {
        0% {
            border: 2px solid palegreen;
        }
        100% {
            border: 2px solid green;
        }
    }

    @keyframes anim-border {
        0% {border-right: 2px solid green;}
        25%  {border-bottom: 2px solid green;}
        50%  {border-left: 2px solid green;}
        75%  {border-top: 2px solid green;border-right: 2px solid #ccc;}
        100%  {border-right: 2px solid green;}
    }

    .btn_printer{
        margin-right:4px;
        margin-top:4px;
        width: 150px;
    }

    .color-box{
        position: absolute;
        /* margin:2px 0 0 0; */
        height:16px;
        width:20px;
        border:1px solid #000;
    }

    .entry_btn .ui-button-text{
        padding: 2px 4px;
    }

    #back_table_bd td, #front_table_bd td{
        cursor: pointer;
    }
    .hide-td{
        display: none;
    }

    .box .ui-button-text {
        padding: .4em 1em;
    }

</style>

<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <script type="text/javascript">
    </script>
</div>

<script type="text/javascript">
    var def = {};
    var mode =localStorage.getItem("mc_mode");
    if(!mode){
        mode = "cminus";
        localStorage.setItem("mc_mode",mode);
    }
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    var printer_ip = JSON.parse(localStorage.getItem("sPrinter"));

    var client_ip = "<?= $client_ip ?>";
    var client_os = "<?= $client_os ?>";

    $(document).ready(function(){
        var plant_name = $("#plant").val();
        var plant_id = $("#plantid").val();

        if(plant_name==""){
            plant_name = "野洲工場";
            plant_id = "1000079";
        }
        $("#btn_change_mode").button();
    
        let ww = $(window).width();
        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width: ww-20,
            modal:true,
            position:["center", 100],
            buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        });
        var plant = plant_name.substr(0,plant_name.length-2);
        var user_gr = "製造係_1班";
        if(plant=="山崎"){
            user_gr="製造1係";
        }
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(plant)+"&gp2="+decodeURIComponent(user_gr)/*+"&callback=?"*/,function(data){
          $("#ap-user-select").html(data);
        });
        
        $( "#alert" ).dialog({
            autoOpen: false,
            width: ww-20,
            modal:true,
            position:["center", 170],
            buttons: [{ text:"閉じる",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide();$('button').blur(); }
        });

        $( "#diff_dialog" ).dialog({
            autoOpen: false,
            width: ww-20,
            modal:true,
            position:["center", 170],
            buttons: [{ text:"閉じる",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide();$('button').blur(); }
        });

        
        $( "#alertdt" ).dialog({
            autoOpen: false,
            width: ww-20,
            modal:true,
            position:["center", 100],
            buttons: [{ text:"閉じる",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        });

        $( "#openAlert" ).dialog({
            autoOpen: false,
            width: ww-20,
            modal:true,
            position:["center"],
            buttons: [{ text:"閉じる",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" );
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide();$('button').blur(); }
        });

        let QRd_w = $(window).width()/2;
        $("#canvas").width(QRd_w);

        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: QRd_w,
            autoOpen: false,
            modal:true,
            position:["right",30],
            buttons: [{ text: "閉じる",class:'btn-confirm', click: function() {
                stop_scan();
            }}]
        });

        $( "#number_in" ).dialog({
            autoOpen: false,
            width:205,
            modal:true,
            resizable: false,
        });
        $("#number_in").siblings('div.ui-dialog-titlebar').remove();

        $(".num_btn").button();
 
        $("button").button();
        $("#担当者").on('focus',function(){
            searchUser('担当者');
        });

        $("#打数").on('keyup',function(e){
            if(e.which===13){
                if($("#打数").val()!==""){
                    $("#打数").focusout();
                }
            }
        });

        $( '#input_name' ).on('keyup',function ( e ) {
            if ( e.which == 13 ) {
                searchUser('担当者','');
            }
        });

        $("#終了日時").on('focusin',function(){
            datetime("終了日時");
        });

        $(".work_process").button();
        $(".work_process").on('change',function(e){
            update_bad_list();
        });
 
        let_start();
        // get_bad_list();

        // set_wel_table();

    });

    function weldEntryCheck(posi){
        let item_data = get_val_by_name("itemdata_"+posi,"tr");

        console.log(item_data);

        let er = 0;msg="";
        $.each(item_data, function (a, b) {
            if(a!="sample_num"){
                if(b=="NaN"){
                    msg+="<p>「部品のRFIDタグ」未スキャン</p>";
                    er++;
                }else if(b==""){
                    msg+="<p>「"+a+"」未入力</p>";
                    er++;
                }
            }
        });
        item_data["main][combi_item"]=$("#製品コード").val();

        if(er>0){
            openAlert("print","登録できません。",msg);
        }else{
            welCounterEntry(posi,item_data);
        }

    }

    function welCounterEntry(posi,data){
        console.log(data);
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=welCounterEntry",
            data: {
                post_data:data
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                if(res[0]=="OK"){
                    $("#hgpdid_"+posi).html(res[1]);
                    $("#itemdata_"+posi).addClass("tr_entred");
                    // openAlert("print","確認","登録しました。");
                    $("#menu_"+posi).off("click");
                    setTimeout(() => {
                        $("#menu_"+posi).on("click",()=>{
                            open_diff_fix(posi+","+res[1]);
                        });
                    }, 0);
                    console.log("登録しました。");
                }else{
                    openAlert("print","確認","エーラが発生！！！");
                }
            }
        });
    }

    var lc = null;
    function searchUser(id){
        lc = id;
        $("#dialog_user").dialog("open");
        return false;
    }

    function setUser(name){
        console.log(lc);
        if(lc){
            if(lc=="担当者"){
                $("#"+lc).val(name);
            }else{
                let sp_id = lc.split("_");
                $("#"+lc).html(name);
                if($("#weldet_"+sp_id[1]).html()==""){
                    $("#weldet_"+sp_id[1]).html(nowDT("dt"));
                }
                item_calculator(sp_id[1]);
                setTimeout(() => {
                    weldEntryCheck(sp_id[1]);
                }, 0);
            }
        }else{
            openAlert("print","確認","どこの担当者？");
        }
        $( "#dialog_user" ).dialog( "close" );
        return false;
    }

    async function weldEntryAllCheck(posi){
        let check = {
            "終了日時":$("#終了日時").val(),
            "担当者":$("#担当者").val()
        }
        
        let c=0;msg="下の項目を入力してください。";
        $.each(check, function (a, b) { 
            if(b==""){
                c++;
                msg+="<li><b>"+a+"</b></li>"
            }
        });

        if(c>0){
            openAlert("print","確認",msg);
            return;
        }

        let cf_en = await openAlert("confirm","確認","集計実績を登録しますか？")
        if(!cf_en){
            return;
        }
        let item_data = get_val_by_name("sum_table_bd","table");
        console.log(item_data);
        if(item_data.length>0){
            weldXlsEntry(item_data);
        }else{
            openAlert("print","確認","溶着済みのセットが未確定なので、登録できません。");
        }
        
    }

    function weldXlsEntry(data){
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=entryXlsReport",
            data: {
                data:data,
                machine:$("#wel_mc").val()
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                if(res=="OK"){
                    openAlert(
                        "print",
                        "完了",
                        "実績登録は完了しました。",
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            console.log("集計データ登録");
                            location.reload();
                            return;
                        }}]
                    )
                }
            }
        });
    }

    function confirmTime(id){
        datetime(id);
    }

    async function let_start(ac){
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            dataType: 'json',
            async:true,
            data:{
                "ac":"getWelCombi",
                "wel_mc":$("#wel_mc").val(),
                "date":$("#wel_date").val(),
                "item_code":$("#製品コード").val()
            },
            success: function(d){       
                console.log(d);
                loadingView(false);
                if(Object.keys(d.combi).length>0){
                    $("#開始日時").val(d.last_time);
                    set_wel_table(d.combi)
                }else{
                    openAlert("alert",
                    "確認",
                    "事前準備データがありません。",
                    [{ class:"btn-left",text:"計画へ",click :function(ev) {
                        $( this ).dialog( "close" );
                        let url = "WeldingCombination?mno="+$("#wel_mc").val();
                        // window.open(url);
                        location.href = url;
                        return;
                    }}]
                );
                }
            }
        });
    }

    function set_wel_table(d){

        let sum_line =``;
        let n = 1;
        $.each(d, function (k, v) { 
            if(v.wwc_hgpd_id=="0"){
                sum_line+=`<tr id="itemdata_`+v.wwc_id+`" class="">
                <td><button id="menu_`+v.wwc_id+`" class="btn_menu"  value="`+v.wwc_id+`" style="width:50px;">`+(n)+`</button></td>
                    <td><button id="qrscan_`+v.wwc_id+`" onclick="autoFocusScan(this.id,'QRコード','','welded_rfid_test');" class="ui-icon-camera ui-btn-icon-left" style="">　</button></td>
                    <td name="main][date">`+v.wwc_work_day+`</td>
                    <td id="frontitem_`+v.wwc_id+`" name="parts][front][code">`+v.front.wwcd_itemcode+`</td>
                    <td id="frontlot_`+v.wwc_id+`" name="parts][front][mold_lot">`+v.front.wwcd_mold_lot+`</td>
                    <td id="frontcav_`+v.wwc_id+`" name="parts][front][cav">`+v.front.wwcd_cav+`</td>
                    <td id="frontnum_`+v.wwc_id+`" class="innum_`+v.wwc_id+`" name="parts][front][num" ></td>
                    <td id="backitem_`+v.wwc_id+`" name="parts][back][code">`+v.back.wwcd_itemcode+`</td>
                    <td id="backlot_`+v.wwc_id+`" name="parts][back][mold_lot">`+v.back.wwcd_mold_lot+`</td>
                    <td id="backcav_`+v.wwc_id+`" name="parts][back][cav">`+v.back.wwcd_cav+`</td>
                    <td id="backnum_`+v.wwc_id+`" class="innum_`+v.wwc_id+`" name="parts][back][num" ></td>
                    <td id="combicav_`+v.wwc_id+`" name="main][cav" >`+v.front.wwcd_cav+`-`+v.back.wwcd_cav+`</td>
                    <td id="producnum_`+v.wwc_id+`" name="main][allnum" onclick=""></td>
                    <td id="goodnum_`+v.wwc_id+`" name="main][goodnum" ></td>
                    <td id="badnum_`+v.wwc_id+`" name="main][badnum" ></td>
                    <td id="badtext_`+v.wwc_id+`" name="main][badtext" style="width:120px;" ></td>`;
                    // sum_line+=`<td id="samplenum_`+v.wwc_id+`" name="main][sample" ></td>`;
                    sum_line+=`<td id="weldet_`+v.wwc_id+`" name="main][weldet" onclick="confirmTime(this.id);"></td>
                    <td id="user_`+v.wwc_id+`" name="main][user" onclick="searchUser(this.id);"></td>
                               
                    <td id="hgpdid_`+v.wwc_id+`" class="hide-td" name="main][hgpd_id" ></td>
                    <td id="wwcid_`+v.wwc_id+`" class="hide-td" name="main][wwc_id" >`+v.wwc_id+`</td>
                    <td id="frontform_`+v.wwc_id+`" class="hide-td" name="parts][front][form" ></td>
                    <td id="backform_`+v.wwc_id+`" class="hide-td" name="parts][back][form" ></td>
                    <td id="fronthgpdid_`+v.wwc_id+`" class="hide-td" name="parts][front][hgpd_id" ></td>
                    <td id="frontrfid_`+v.wwc_id+`" class="hide-td" name="parts][front][rfid" ></td>
                    <td id="frontbfwork_`+v.wwc_id+`" class="hide-td" name="parts][front][bf_work" ></td>
                    <td id="backhgpdid_`+v.wwc_id+`" class="hide-td" name="parts][back][hgpd_id" ></td>
                    <td id="backrfid_`+v.wwc_id+`" class="hide-td" name="parts][back][rfid" ></td>
                    <td id="backbfwork_`+v.wwc_id+`" class="hide-td" name="parts][back][bf_work" ></td>
                    <td id="weldedrfid_`+v.wwc_id+`" class="hide-td" name="main][weldedrfid" ></td>
                    <td id="weldst_`+v.wwc_id+`" class="hide-td" name="main][weldst" ></td>
                    <td id="partsnum_`+v.wwc_id+`" class="hide-td" name="main][partsnum" >2</td>
                </tr>`;
            }else{

                let hgpd_def = "";
                if(v.hgpd_def){
                    hgpd_def=v.hgpd_def;
                    sp_def=hgpd_def.split(",");
                    def[v.wwc_id]={}
                    $.each(sp_def, function (kd, vd) { 
                        def[v.wwc_id][vd.split("=>")[0]]=vd.split("=>")[1];
                    });
                }

                sum_line+=`<tr id="itemdata_`+v.wwc_id+`" class="tr_entred" >
                    <td><button id="menu_`+v.wwc_id+`" class="btn_menu_entred" value="`+v.wwc_id+`,`+v.hgpd_id+`" style="width:50px;">`+(n)+`</button></td>
                    <td><button id="qrscan_`+v.wwc_id+`" onclick="" class="ui-icon-camera ui-btn-icon-left" style="">　</button></td>
                    <td name="main][date">`+v.wwc_work_day+`</td>
                    <td id="frontitem_`+v.wwc_id+`" name="parts][front][code">`+v.front.wwcd_itemcode+`</td>
                    <td id="frontlot_`+v.wwc_id+`" name="parts][front][mold_lot">`+v.front.wwcd_mold_lot+`</td>
                    <td id="frontcav_`+v.wwc_id+`" name="parts][front][cav">`+v.front.wwcd_cav+`</td>
                    <td id="frontnum_`+v.wwc_id+`" class="innum_`+v.wwc_id+`" name="parts][front][num" onclick="">`+v.front.wic_qty_out+`</td>
                    <td id="backitem_`+v.wwc_id+`" name="parts][back][code">`+v.back.wwcd_itemcode+`</td>
                    <td id="backlot_`+v.wwc_id+`" name="parts][back][mold_lot">`+v.back.wwcd_mold_lot+`</td>
                    <td id="backcav_`+v.wwc_id+`" name="parts][back][cav">`+v.back.wwcd_cav+`</td>
                    <td id="backnum_`+v.wwc_id+`" class="innum_`+v.wwc_id+`" name="parts][back][num" onclick="">`+v.back.wic_qty_out+`</td>
                    <td id="combicav_`+v.wwc_id+`" name="main][cav" >`+v.front.wwcd_cav+`-`+v.back.wwcd_cav+`</td>
                    <td id="producnum_`+v.wwc_id+`" name="main][allnum" onclick="">`+v.hgpd_quantity+`</td>
                    <td id="goodnum_`+v.wwc_id+`" name="main][goodnum" >`+v.hgpd_qtycomplete+`</td>
                    <td id="badnum_`+v.wwc_id+`" name="main][badnum" >`+(v.hgpd_quantity - v.hgpd_qtycomplete)+`</td>
                    <td id="badtext_`+v.wwc_id+`" name="main][badtext" style="width:120px;" >`+hgpd_def+`</td>`;
                    // sum_line+=`<td id="samplenum_`+v.wwc_id+`" name="main][sample" ></td>`;
                    sum_line+=`<td id="weldet_`+v.wwc_id+`" name="main][weldet" onclick="">`+v.hgpd_stop_at+`</td>
                    <td id="user_`+v.wwc_id+`" name="main][user" onclick="">`+v.hgpd_name+`</td>
                    
                    <td id="hgpdid_`+v.wwc_id+`" class="hide-td" name="main][hgpd_id" >`+v.hgpd_id+`</td>
                    <td id="wwcid_`+v.wwc_id+`" class="hide-td" name="main][wwc_id" >`+v.wwc_id+`</td>
                    <td id="frontform_`+v.wwc_id+`" class="hide-td" name="parts][front][form" ></td>
                    <td id="backform_`+v.wwc_id+`" class="hide-td" name="parts][back][form" ></td>
                    <td id="fronthgpdid_`+v.wwc_id+`" class="hide-td" name="parts][front][hgpd_id" ></td>
                    <td id="frontrfid_`+v.wwc_id+`" class="hide-td" name="parts][front][rfid" ></td>
                    <td id="frontbfwork_`+v.wwc_id+`" class="hide-td" name="parts][front][bf_work" ></td>
                    <td id="backhgpdid_`+v.wwc_id+`" class="hide-td" name="parts][back][hgpd_id" ></td>
                    <td id="backrfid_`+v.wwc_id+`" class="hide-td" name="parts][back][rfid" ></td>
                    <td id="backbfwork_`+v.wwc_id+`" class="hide-td" name="parts][back][bf_work" ></td>
                    <td id="weldedrfid_`+v.wwc_id+`" class="hide-td" name="main][weldedrfid" ></td>
                    <td id="weldst_`+v.wwc_id+`" class="hide-td" name="main][weldst" ></td>
                    <td id="partsnum_`+v.wwc_id+`" class="hide-td" name="main][partsnum" >2</td>
                </tr>`;
            }
            n++;
        });

        $("#sum_table_bd").html(sum_line);
        $("button").button();
        $(".btn_menu_entred").on("click",function(){
            open_diff_fix(this.value);
        })

        $(".btn_menu").on("click",function(){
            open_diff(this.value);
        })
        // $(".tr_entred button").button("disable");

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

    function trans_to_val(cells,name_list){
        let r ={};
        $.each(name_list,function(k,v){
            r[v]=cells[k]
        })
        return r;
    }

    function get_val_by_name(id,type){

        if(!type){
            type="table";
        }

        const getObj = document.querySelector("#"+id); 
        // console.log(getObj);
        // return;
        var rows=[];cells=[];
        if(type=="table"){
            rows = Array.from(getObj.rows);
            cells = rows.map(row => Array.from(row.cells));
        }else{
            cells.push(Array.from(getObj.cells));
        }
        // console.log(rows);
        // console.log(cells);
        // return;
        var data = [];
        $.each(cells,function(a,b){
            let r ={};
            $.each(b,function(c,d){
                if(c>1 && d.textContent && d.textContent != "undefined" && d.attributes.name){
                    r[d.attributes.name.value]=d.textContent;
                }
            })
            console.log(r["main][hgpd_id"] );
            if(type=="table"){
                if(r["main][hgpd_id"]){
                    r["main][combi_item"]=$("#製品コード").val();
                    data.push(r["main][hgpd_id"]);
                }
            }else{
                data.push(r);
            }
        })
        if(type=="table"){
            return data;
        }else{
            return data[0];
        }
    }

    function sum_class(cl){
        let this_obj = $("."+cl);
        let arr = [];
        console.log(this_obj);
    }

    function get_val_of_class(cl,type){
        let this_obj = $("."+cl);
        let arr = [];
        let str = "";
        let sum_num = 0;
        $.each(this_obj, function (a, b) { 
            str+=b.textContent+",";
            arr.push(b.textContent);
            sum_num += parseInt(b.textContent);
        });
        str=str.substr(0,str.length-1);
        if(type=="str"){
            return str;
        }else if(type=="sumnum"){
            return sum_num;
        }else{
            return arr; 
        }
    }

    function min_search(arr){
        let min = parseInt(arr[0]);
        $.each(arr, function (a, b) { 
            if(parseInt(b)<min){
                min = parseInt(b);
            }
        });
        return min;
    }

    function max_search(arr){
        let max = parseInt(arr[0]);
        $.each(arr, function (a, b) { 
            if(parseInt(b)>max){
                max = parseInt(b);
            }
        });
        return max;
    }


    function item_calculator(spid){
        let name_list1=["menu","qrscan","date"];
        let name_list2=["front_name","front_lot","front_cav","front_num","back_name","back_lot","back_cav","back_num"]
        let name_list3=["combi_cav","produc_num","good_num","bad_num","bad_text","sample_num","time","user","front_item","back_item"];
        let name_list = name_list1.concat(name_list2).concat(name_list3);

        const c_this_cells = Array.from($("#itemdata_"+spid)[0].cells).map(cell => cell.textContent);
    
        const c_item_data = trans_to_val(c_this_cells,name_list);

        let parts_num = parseInt($("#partsnum_"+spid).html());
        let min_num = min_search(get_val_of_class("innum_"+spid,"array"));
        let max_num = max_search(get_val_of_class("innum_"+spid,"array"));

        if($("#weldedrfid_"+spid).html()==""){
            console.log("test mode");
            $("#weldedrfid_"+spid).html("welded_rfid_test_0001");
        }

        //受け数
        let in_num = get_val_of_class("innum_"+spid,"sumnum");
        //生産数
        // let produc_num = in_num / parts_num;
        let produc_num = min_num

        let diff_num = produc_num - min_num;
        let bad_num = diff_num;
        let old_bad_text = $("#badtext_"+spid).html();
        let new_bad_text = "";

        if(def[spid] && Object.keys(def[spid]).length>0){
            let nbn=0;nbt="";
            $.each(def[spid], function (a, b) { 
                nbn+=parseInt(b);
                nbt+=a+"=>"+b+","
            });
            bad_num = nbn;
            min_num = produc_num - bad_num;
            new_bad_text=nbt.slice(0,-1);
        }

        //良数、不良数の計算
        $("#producnum_"+spid).html(produc_num);
        $("#goodnum_"+spid).html(min_num);
        // if($("#badnum_"+spid).html()!="" && $("#badnum_"+spid).html()!="0"){
        //     bad_num = parseInt($("#badnum_"+spid).html())+diff_num;
        // }
        if(bad_num>0){
            $("#badnum_"+spid).html(bad_num);
            if(!def[spid]){
                new_bad_text="組合せ欠損=>"+bad_num;
                def[spid]={
                    "組合せ欠損":bad_num
                };
            }
        }else{
            $("#badnum_"+spid).html("0");
        }
        // if(old_bad_text!=""){
        //     $("#badtext_"+spid).html(old_bad_text+","+new_bad_text);
        // }else{
            $("#badtext_"+spid).html(new_bad_text);
        // }

        if($("#weldst_"+spid).html()==""){
            $("#weldst_"+spid).html(nowDT("dt"));
        }

        //計算後のデータを取得
        let now_cells = Array.from($("#itemdata_"+spid)[0].cells).map(cell => cell.textContent);
        let item_data = trans_to_val(now_cells,name_list);
        // console.log(item_data);

        // return;
    }

    async function rfid_status(rfid,type,cb){
        let linked_id = $(".check_rfid");
        let c_flag = false;
        let rf_id="";
        for(i=0;i<linked_id.length;i++){
            if(linked_id[i].value==rfid){
                c_flag = true;
                rf_id = linked_id[i].id;
                break;
            }
        }
        if(c_flag){
            if(type=="input"){
                $("#err_msg").html("<p style='margin-top:10px;color:red;'>RFIDは利用中です。</p>");
                del_msg();
                return false;
            }else{
                alert("RFIDは利用中です。");
                return false;
            }
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
                    cb(d,type);
                }
            });
        } 
    }

    function autoFocusScan(id){
        let sp_id = id.split("_");
        console.log("auto:"+id)
        if($("#frontrfid_"+sp_id[1]).html()==""){
            //FRONT部品
            openCamScan("frontrfid_"+sp_id[1],"部品のRFID");
        }else if($("#backrfid_"+sp_id[1]).html()==""){
            //BACK部品
            openCamScan("backrfid_"+sp_id[1],"部品のRFID");
        }else if($("#weldedrfid_"+sp_id[1]).html()==""){
            //溶着済み
            openCamScan("weldedrfid_"+sp_id[1],"溶着後のRFID");
        }else{
            openAlert("print","確認","RFID連携情報はすでにスキャンされていました。");
        }
    }
    
    async function openCompleteScanTest(id,name,fcmode,code){
        let sp_id = id.split("_");
        let check_rfid = await rfid_status(code,"input",function(d,type){
            console.log(d);
            if(d){
                $("#"+id).html(code);
                item_calculator(sp_id[1],code);
            }
        });
    }

    async function openCamScanTest(id,name,fcmode,code){
        let sp_id = id.split("_");
        let check_rfid = await get_rfid_info(code,"input",function(d,type){
            console.log(d);
            if(d[0]=="OK"){
                if($("#frontitem_"+sp_id[1]).html()==d[1][0]["itemcord"]){
                    $("#frontnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                    $("#frontrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                    $("#fronthgpdid_"+sp_id[1]).html(d[1][0].hgpd_id);
                    $("#frontbfwork_"+sp_id[1]).html(d[1][0].workitem);                    
                }else if($("#backitem_"+sp_id[1]).html()==d[1][0]["itemcord"]){
                    $("#backnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                    $("#backrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                    $("#backhgpdid_"+sp_id[1]).html(d[1][0].hgpd_id);
                    $("#backbfwork_"+sp_id[1]).html(d[1][0].workitem);
                }else{
                    openAlert("print","確認","製品コードが違います。");
                }
            }else{
                openAlert("print","確認",d[1]);
            }
        });
    }

    function openCamScan(id,name,fcmode){
        //loadingView(true);
        console.log(id);
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
            tick(id);
        });
        
        let button = [
            { class:"btn-right",html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",click :function() {
                stop_scan();
                $("#ip_rfid").focus();
                openRfidInput(id);
            }},{ class:"btn-right",html:"<span class='dialog_btn ui-icon-recycle ui-btn-icon-right'>カメラ切替</span>",click :function() {
                if(fcmode=="user"){
                    fcmode="environment";
                }else{
                    fcmode="user";
                }
                localStorage.setItem("sFcmode",fcmode);
                stop_scan();
                setTimeout(() => {
                    openCamScan(id,name,fcmode);
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
        // //loadingView(false);
        $("#QRScan").dialog( "open" );
        $("#"+id).focus();
        setTimeout(function(){
            stop_scan();
        }, 300000);
    }

    const sleepy = ms => new Promise(resolve => setTimeout(resolve, ms));
    var list_code_scaned = [];
    var list_code = [];
    async function tick(id) {
        let camera_off=false;
        let sp_id = id.split("_");

        if(sp_id[0]!="weldedrfid" && $("#frontrfid_"+sp_id[1]).html()!="" && $("#backrfid_"+sp_id[1]).html()!=""){
            if(video!==null){
                camera_off=true;
                stop_scan();
                autoFocusScan("weldedrfid_"+sp_id[1]);
                return;
            }
        }

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
                if(code.data){
                    console.log(code.data);
                    if(!list_code_scaned.includes(code.data)){
                        list_code_scaned.push(code.data);
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                        playMelody(2200);
                        await sleepy(300);
                        // stop_scan();
                        if(sp_id[0]=="weldedrfid"){
                            let check_rfid = await rfid_status(code.data,"input",function(d,type){
                                console.log(d);
                                if(d===true){
                                    $("#"+id).html(code.data);
                                    $("#wedst_"+sp_id[1]).html(nowDT("dt"));
                                    item_calculator(sp_id[1]);
                                    stop_scan();
                                    return;
                                }else{
                                    openAlert("print","確認","RFIDが利用中です。");
                                }
                            });
                        }else{
                            let check_rfid = await get_rfid_info(code.data,"input",function(d,type){
                                console.log(d);
                                if(d[0]=="OK"){
                                    let itcode =d[1][0]["itemcord"];
                                    let itcav = d[1][0]["hgpd_cav"];
                                    if(itcode==$("#frontitem_"+sp_id[1]).html()){
                                        if($("#frontrfid_"+sp_id[1]).html()!=""){
                                            openAlert("print","確認","FRONT部品のRFIDはスキャンされていました。");
                                            remove_check(code.data);
                                        }else{
                                            if(itcav==$("#frontcav_"+sp_id[1]).html()){
                                                $("#fronthgpdid_"+sp_id[1]).html(d[1][0].hgpd_id);
                                                $("#frontrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                                                $("#frontnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                                                $("#frontform_"+sp_id[1]).html(d[1][0].itemform);
                                                $("#frontbfwork_"+sp_id[1]).html(d[1][0].workitem);
                                            }else{
                                                openAlert("print","確認","FRONT部品のキャビが違います。");
                                                remove_check(code.data);
                                            }
                                        }
                                    }else if(itcode==$("#backitem_"+sp_id[1]).html()){
                                        if($("#backrfid_"+sp_id[1]).html()!=""){
                                            openAlert("print","確認","BACK部品のRFIDはスキャンされていました。");
                                            remove_check(code.data);
                                        }else{
                                            if(itcav==$("#backcav_"+sp_id[1]).html()){
                                                $("#backhgpdid_"+sp_id[1]).html(d[1][0].hgpd_id);
                                                $("#backrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                                                $("#backnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                                                $("#backform_"+sp_id[1]).html(d[1][0].itemform);
                                                $("#backbfwork_"+sp_id[1]).html(d[1][0].workitem);
                                            }else{
                                                openAlert("print","確認","BACK部品のキャビが違います。");
                                                remove_check(code.data);
                                            }
                                        }
                                    }else{
                                        openAlert("print","確認","製品コードが違います。");
                                        remove_check(code.data);
                                    }
                                }else{
                                    openAlert("print","確認",d[1]);
                                    remove_check(code.data);
                                }
                            });
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

    function remove_check(code){
        setTimeout(() => {
            list_code_scaned = $.grep(list_code_scaned, function (value) {
                return value != code;
            });
        }, 3000);
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
        $(".toggle-color").removeClass("toggle-color");
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
            return year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec;
        }
        if(str=="ti"){
            return hour+":"+min;
        }
        if(str=="dhm"){
            return year+"/"+month+"/"+day+" "+hour+":"+min;
        }
    }

    function datetime(id,type){
        let set_title = "【"+id+"】を入力してください。";
        let sp_id=id.split("_");
        if(id.indexOf("danstart_")>-1){
            set_title = "【開始】を入力してください。";
        }
        if(id.indexOf("danend_")>-1){
            set_title = "【終了】を入力してください。";
        }
        if(id.indexOf("weltime_")>-1){
            set_title = "【溶着終了時間】を入力してください。";
        }
        loadingView(true);
        $.ajax({
            type: 'GET',
            url: "/LotManagement/DatetimeKeyBord",
            dataType: 'html',
            success: function(data) {
                loadingView(false);
                var options = {"title":set_title,
                autoOpen: false,
                width: 900,
                position:["centetr",100],
                buttons: [{ text:"キャンセル",class:'btn-cancel',click :function(ev) {
                    $( this ).dialog( "close" );
                    $("#messagedt").html("");
                }},{ text:"決定",class:'btn-confirm',click :function(ev) {
                    let s_time = $("#開始日時").val();
                    let e_time = nowDT("dhm");
                    let set_this_time = btn_entry("dhms");
                    if(id=="終了日時" || id=="開始日時"){
                        if(id=="開始日時"){
                            s_time = btn_entry();
                            e_time = nowDT("dhm");
                        }else{
                            s_time = $("#開始日時").val();   
                            e_time = btn_entry();
                        }
                        if(Date.parse(e_time)>=Date.parse(s_time)){
                            $("#stoptod").focus();
                            $("#"+id).val(set_this_time);
                            $("#"+id).css({"background-color": ""});
                            $("span.ui-dialog-title").css({"color": ""});
                            $( this ).dialog( "close" );
                            $("#messagedt").html("");
                            if(id=="終了日時"){
                                // focus_control();
                            }
                        }else{
                            $("span.ui-dialog-title").text("【"+id+"】もう一度入力してください。"); 
                            $("span.ui-dialog-title").css({"color": "red"});
                            $("#"+id).css({"background-color": "red"});
                        }
                    }else{
                        console.log(id);
                        console.log(set_this_time);
                        // console.log("set");
                        // if(Date.parse(e_time)>Date.parse(s_time)){
                            $("#"+id).html(set_this_time);
                            $( this ).dialog( "close" );
                            $("#user_"+sp_id[1]).click();
                        // }else{
                        //     $("span.ui-dialog-title").text("開始時間、終了時間を確認してください。"); 
                        //     $("span.ui-dialog-title").css({"color": "red"});
                        // }
                    }
                }}]
                };
                $("button").button();
                $("#messagedt").html(data);
                if($("#"+id).val()!==""){
                    // datetimefix(id);
                }
                $( "#alertdt" ).dialog( "option",options);
                $( "#alertdt" ).dialog( "open" );
                return false;
            }
        });
    }

    function setInfo(){
        if ($("#m_qr_code").val()!="") {
            let sp_code = $("#m_qr_code").val().split(",");
            // console.log(sp_code);
            if(sp_code.length==5){
                $("#m_code").val(sp_code[0])
                $("#m_name").val(sp_code[1])
                $("#m_lot").val(sp_code[2])
                $("#m_day").val(nowDT("dt"))
                $("#area_weight").val("area_p")
                $("#plant_weight").val("all_p")
                $("#moving_weight").val(sp_code[4])
                $("#m_user").focus();
            }else{
                var result = confirm('QRコードの形が違います。再スキャンしますか? ');
                if(result) {
                    //はいを選んだときの処理
                    $("#m_qr_code").val("");
                    $("#m_qr_code").focus();
                } else {
                    //いいえを選んだときの処理
                    return;  
                }
    
            }
            return false;
        }
    }

    async function get_rfid_info(rfid,type,cb){
        let linked_id = $(".check_rfid");
        let c_flag = false;
        let rf_id="";
        for(i=0;i<linked_id.length;i++){
            if(linked_id[i].value==rfid){
                c_flag = true;
                rf_id = linked_id[i].id;
                break;
            }
        }
        if(c_flag){
            if(type=="input"){
                $("#err_msg").html("<p style='margin-top:10px;color:red;'>RFIDは利用中です。</p>");
                del_msg();
                return false;
            }else{
                alert("RFIDは利用中です。");
                return false;
            }
            return false;
        }else{
            loadingView(true);
            $.ajax({
                type: "GET",
                url: "?ac=getPartsRfid",
                dataType: "json",
                data: {
                    rfid:rfid,
                    bom_mode:"on",
                },
                success: function(d){
                    loadingView(false);
                    cb(d,type);
                }
            });
        } 
    }

    async function openRfidInput(id){
        let sp_id = id.split("_");
        var msg="<label for='ip_rfid'>RFID：</label><input id='ip_rfid' type='text' value='' placeholder='RFID入力' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='ip_rfid'>RFID：</label><input id='ip_rfid' type='tel' pattern='[0-9]*' value='' placeholder='RFID入力' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        }
        $("#message").html(msg);

        $("#ip_rfid").on('keyup', function(e) {
            if(e.which == 13){
                var code = $("#ip_rfid").val();
                if(sp_id[0]=="weldedrfid"){
                    rfid_status(code,"input",function(d,type){
                        $("#alert").dialog( "close" );
                        console.log(d);
                        if(d===true){
                            $("#"+id).html(code);
                            $("#wedst_"+sp_id[1]).html(nowDT("dt"));
                            item_calculator(sp_id[1]);
                        }else{
                            openAlert("print","確認","RFIDが利用中です。");
                        }
                    });
                }else{
                    get_rfid_info(code,"input",function(d,type){
                        console.log(d);
                        $("#ip_rfid").val("");
                        if(d[0]=="OK"){
                            let itcode =d[1][0]["itemcord"];
                            if(itcode==$("#frontitem_"+sp_id[1]).html()){
                                if($("#frontrfid_"+sp_id[1]).html()!=""){
                                    openAlert("print","確認","FRONT部品のRFIDはスキャンされていました。");
                                }else{
                                    $("#frontrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                                    $("#frontnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                                }
                            }else if(itcode==$("#backitem_"+sp_id[1]).html()){
                                if($("#backrfid_"+sp_id[1]).html()!=""){
                                    openAlert("print","確認","BACK部品のRFIDはスキャンされていました。");
                                }else{
                                    $("#backrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                                    $("#backnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                                }
                            }else{
                                openAlert("print","確認","製品コードが違います。");
                            }
                        }else{
                            openAlert("print","確認",d[1]);
                        }
                        $("#alert").dialog( "close" );
                    });
                }
            }
        });
        var options = {"title":"RFIDを入力してください。",
            position:["center",100],
            width: 600,
            buttons: [
                { class:"btn-right",text:"カメラで入力",click :function(ev) {
                    openCamScan(id,"RFID");
                }},
                { class:"btn-right",text:"OK",click :function(ev) {
                    var code = $("#ip_rfid").val();
                    if(sp_id[0]=="weldedrfid"){
                        rfid_status(code,"input",function(d,type){
                            $("#alert").dialog( "close" );
                            console.log(d);
                            if(d===true){
                                $("#"+id).html(code);
                                $("#wedst_"+sp_id[1]).html(nowDT("dt"));
                                item_calculator(sp_id[1]);
                            }else{
                                openAlert("print","確認","RFIDが利用中です。");
                            }
                        });
                    }else{
                        get_rfid_info(code,"input",function(d,type){
                            console.log(d);
                            $("#ip_rfid").val("");
                            if(d[0]=="OK"){
                                let itcode =d[1][0]["itemcord"];
                                if(itcode==$("#frontitem_"+sp_id[1]).html()){
                                    if($("#frontrfid_"+sp_id[1]).html()!=""){
                                        openAlert("print","確認","FRONT部品のRFIDはスキャンされていました。");
                                    }else{
                                        $("#frontrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                                        $("#frontnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                                        $("#frontform_"+sp_id[1]).html(d[1][0].itemform);
                                    }
                                }else if(itcode==$("#backitem_"+sp_id[1]).html()){
                                    if($("#backrfid_"+sp_id[1]).html()!=""){
                                        openAlert("print","確認","BACK部品のRFIDはスキャンされていました。");
                                    }else{
                                        $("#backrfid_"+sp_id[1]).html(d[1][0].search_rfid);
                                        $("#backnum_"+sp_id[1]).html(d[1][0].wic_qty_in);
                                        $("#backform_"+sp_id[1]).html(d[1][0].itemform);
                                    }
                                }else{
                                    openAlert("print","確認","製品コードが違います。");
                                }
                            }else{
                                openAlert("print","確認",d[1]);
                            }
                            $("#alert").dialog( "close" );
                        });
                    }
                }},
                { class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }}
            ]
        };
        $( "#alert" ).dialog( "option",options);
        loadingView(false);
        $("#alert").dialog( "open" );
    }

    function del_msg(){
        setTimeout(() => {
            $("#err_msg").html("");
        }, 10000);
    }

    function openActualManagement(){
        let url = "ActualManagement";
        window.open(url);
    }

    //Visual Keyboard 作成
    function open_num_key(id){
        $("#num_in_tab").val(id);
        let input_id = $("#num_in_tab").val();
        // $("#"+input_id).select();
        var options = {
            position:{my: "left top", at: "left bottom", of: "#"+input_id},
        }
        $( "#number_in" ).dialog( "option",options);
        $( "#number_in" ).dialog( "open" );
        $(".ui-widget-overlay").css({"opacity":"0"});
        $(".ui-widget-overlay").on('click',function(){
            let c = btn_control();
            if(c){
                $( "#number_in" ).dialog( "close" );
            }
        });
    }

    function c_up(bad_name,id){

        let sum_wel = parseInt($("#溶着数_"+id).val());
        let sum_good = parseInt($("#良品数_"+id).val());
        let sum_bad = parseInt($("#不良数_"+id).val());

        if(sum_bad<sum_wel){
            let item_val = parseInt($("#"+bad_name+"_"+id).val())+1;
            $("#"+bad_name+"_"+id).val(item_val)
            sum_ditem(id);
        }

    }

    function c_dw(bad_name,id){
        let item_val = parseInt($("#"+bad_name+"_"+id).val())-1;
        if(item_val>=0){
            $("#"+bad_name+"_"+id).val(item_val)
        }

        sum_ditem(id);
    }

    
    function btn_control(ac){
        if(ac=="ok"){
            let id=$("#num_in_tab").val();
            let sp_id = id.split("_");
            sum_ditem(sp_id[1]);
            $("#num_in_tab").val("");
            $("#number_in").dialog( "close" );
        }
    }

    function sum_ditem(id){
        def[id]={};
        let sum_wel = parseInt($("#溶着数_"+id).val());
        let sum_good = parseInt($("#良品数_"+id).val());
        let sum_bad = parseInt($("#不良数_"+id).val());

        let bad_ip = $(".n_input");
        let btext = "";bnum=0;
        $.each(bad_ip, function (a, b) { 
            if(b.value>0){
                bnum+=parseInt(b.value);
                def[id][b.name]=b.value;
                btext+=b.name+"=>"+b.value+","
            }
        });


        btext=btext.slice(0,-1);

        let fbtext = $("#badtext_"+id).html();
        sp_btext=fbtext.split(",");

        // $.each(sp_btext, function (c, d) { 
        //     if(d.indexOf("組合せ欠損")>-1){
        //         def[id][d.replaceAll("&gt;",">").split("=>")[0]]=d.replaceAll("&gt;",">").split("=>")[1];
        //         bnum+=parseInt(d.replaceAll("&gt;",">").split("=>")[1]);
        //         btext=d+","+btext;
        //     }
        // });
        // console.log(bnum);

        setTimeout(() => {
            // console.log(def);
            $("#badtext_"+id).html(btext);
            $("#不良数_"+id).val(bnum);
            $("#良品数_"+id).val(sum_wel-bnum);

            $("#badnum_"+id).html(bnum);
            $("#goodnum_"+id).html(sum_wel-bnum);

        }, 0);
    }

    function open_diff(id){
        console.log(id);

        if($("#goodnum_"+id).html()==""){
            openAlert("print","確認！！！","まず、組み合わせのRFIDをスキャンしてください。");
            return;
        }
        let name_list=["menu","qrscan","date","front_name","front_lot","front_cav","front_num","back_name","back_lot","back_cav","back_num","combi_cav","producnum","good_num","bad_num","bad_text","sample_num","time","user","front_item","back_item"];
        let now_cells = Array.from($("#itemdata_"+id)[0].cells).map(cell => cell.textContent);
        let item_data = trans_to_val(now_cells,name_list);
        // console.log(item_data);

        // let ditem_list = $("#bad_list").val();
        let ditem_list = "溶着不良,サンプル";
        // if(ditem_list==""){
        //     get_bad_list();
        //     return;
        // }
        var sp_ditem = ditem_list.split(",");
        //$("#tab_ditem_"+kind).html("");
        
        var f_item='';
        f_item+='<div style="display:block;margin-bottom:15px;">';
        f_item+='<lable >溶着数：</lable><input id ="溶着数_'+id+'" readonly="readonly" class="receive_num" name="" value="'+item_data.producnum+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >良品数：</lable><input id ="良品数_'+id+'" readonly="readonly" class="receive_num" name="" value="'+item_data.good_num+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >不良数：</lable><input id ="不良数_'+id+'" readonly="readonly" class="receive_num" name="" value="'+item_data.bad_num+'" style="color:#000;width:100px;" />';  
        f_item+='<button type="button" value="'+id+'" onclick="defactitemadd(this.value);" class="btn_ditemset" style="float:right;margin-left:30px;">不良設定</button></div>';

        $.each(sp_ditem,function(key,val){
            // def][id][defname
            var k = val+'_'+id;
            var n = "def]["+id+"]["+val;
            var ip_class = "";
            var num = 0;
            if (def[id] && def[id][val]){
                num=parseInt(def[id][val]);
            }
            f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
            f_item+='<p class="b_line">'+val+'</p>';
            f_item+='<p class="btn" onclick="c_up(`'+val+'`,`'+id+'`)">+</p>';
            f_item+='<p class="btn" onclick="c_dw(`'+val+'`,`'+id+'`)">-</p>';
            f_item+='<input type="text" name="'+val+'" value="'+num+'" class="n_input '+ip_class+' sample_input" readonly="readonly" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/>';
            f_item+='</div>';
        });
        $("#diff_item").html(f_item);

        $(".btn").button();
        $(".btn_ditemset").button();
        $(".n_input").on("click",function(e){
            open_num_key(e.target.id);
        });
        $(".box input").focusin(function(e){
            // $(this).css({'background-color':'#ccc'});
            if(parseInt($(this).val())>0 ){
                $(this).css({'color':'#ff0000'});
            }else{
                $(this).css({'color':'#000'});
            }
        })

        let dw = $(window).width()-20;
        var options = {
            "title":"操作メニュー　",
            position:["center",175],
            width: "auto",
            buttons: [{ text:"次へ",class:'btn-confirm',click :function(ev) {
                $("#diff_item").html("");
                $("#diff_dialog").dialog( "close" );
                datetime("weldet_"+id);
                // if(fix){
                //     setTimeout(() => {
                //         children_Entry("usercav_"+sp_id[0]+"_"+posi,'children_Update');
                //     }, 0);
                // }
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };


        $("#diff_dialog").dialog( "option",options);
        $("#diff_dialog").dialog( "open" );

    }

    async function open_diff_fix(grid){
  
        let sp_id = grid.split(",");
        let id = sp_id[0]; hgpd_id = sp_id[1];

        console.log(id);
        console.log(hgpd_id);

        let cf = await openAlert("confirm","確認！！！","不良内容を変更しますか？");
        if(!cf){
            return;
        }
        let name_list=["menu","qrscan","date","front_name","front_lot","front_cav","front_num","back_name","back_lot","back_cav","back_num","combi_cav","producnum","good_num","bad_num","bad_text","sample_num","time","user","front_item","back_item"];
        let now_cells = Array.from($("#itemdata_"+id)[0].cells).map(cell => cell.textContent);
        let item_data = trans_to_val(now_cells,name_list);
        // console.log(item_data);

        // let ditem_list = $("#bad_list").val();
        let ditem_list = "溶着不良,サンプル";
        // if(ditem_list==""){
        //     get_bad_list();
        //     return;
        // }
        var sp_ditem = ditem_list.split(",");
        //$("#tab_ditem_"+kind).html("");
        
        var f_item='';
        f_item+='<div style="display:block;margin-bottom:15px;">';
        f_item+='<lable >溶着数：</lable><input id ="溶着数_'+id+'" readonly="readonly" class="receive_num" name="" value="'+item_data.producnum+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >良品数：</lable><input id ="良品数_'+id+'" readonly="readonly" class="receive_num" name="" value="'+item_data.good_num+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >不良数：</lable><input id ="不良数_'+id+'" readonly="readonly" class="receive_num" name="" value="'+item_data.bad_num+'" style="color:#000;width:100px;" />';  
        f_item+='<button type="button" value="'+id+'" onclick="defactitemadd(this.value);" class="btn_ditemset" style="float:right;margin-left:30px;">不良設定</button></div>';

        $.each(sp_ditem,function(key,val){
            // def][id][defname
            var k = val+'_'+id;
            var n = "def]["+id+"]["+val;
            var ip_class = "";
            var num = 0;
            if(def[id] && def[id][val]){
                num=parseInt(def[id][val]);
            }
            f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
            f_item+='<p class="b_line">'+val+'</p>';
            f_item+='<p class="btn" onclick="c_up(`'+val+'`,`'+id+'`)">+</p>';
            f_item+='<p class="btn" onclick="c_dw(`'+val+'`,`'+id+'`)">-</p>';
            f_item+='<input type="text" name="'+val+'" value="'+num+'" class="n_input '+ip_class+' sample_input" readonly="readonly" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/>';
            f_item+='</div>';
        });
        $("#diff_item").html(f_item);

        $(".btn").button();
        $(".btn_ditemset").button();
        $(".n_input").on("click",function(e){
            open_num_key(e.target.id);
        });
        $(".box input").focusin(function(e){
            // $(this).css({'background-color':'#ccc'});
            if(parseInt($(this).val())>0 ){
                $(this).css({'color':'#ff0000'});
            }else{
                $(this).css({'color':'#000'});
            }
        })

        let dw = $(window).width()-20;
        var options = {
            "title":"操作メニュー　",
            position:["center",175],
            width: "auto",
            buttons: [{ text:"変更",class:'btn-confirm',click :function(ev) {
                loadingView(true);
                $.ajax({
                    type: "GET",
                    url: "?ac=fixDefective",
                    data: {
                        wwc_id:id,
                        hgpd_id:hgpd_id,
                        difective:def[id]
                    },
                    dataType: "json",
                    success: function (res) {
                        loadingView(false);
                        // console.log(res);
                    }
                });
                $(this).dialog("close");
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };

        $("#diff_dialog").dialog( "option",options);
        $("#diff_dialog").dialog( "open" );

    }

    function num_btn_click(num){
        let kind=$("#num_in_tab").val();
        let val = $("#"+kind).val();
        if((val=="" || val=="0") && parseInt(num)!==0){
            $("#"+kind).val(num);
        }else{
            $("#"+kind).val(val+num);
        }
        $("#"+kind).focus();
    }

    function btn_del(ac){
        let kind=$("#num_in_tab").val();
        let val= $("#"+kind).val();
        if(val!==""){
            // $("#"+kind).val(val.substr(0,val.length-1));
            $("#"+kind).val(0);
        }
        $("#"+kind).focus();
    }

    function datetimefix(id){
		var this_time = new Date($("#"+id).val());
		var y = this_time.getFullYear();
		var mo = this_time.getMonth()+1;
		var d = this_time.getDate();
		var h = this_time.getHours();
		var m = this_time.getMinutes();
		$("#year").text(y);
		$("#mounth").text(("0"+mo).slice(-2));
		$("#day").text(("0"+d).slice(-2));
		$("#houre").text(("0"+h).slice(-2));
		$("#minits").text(("0"+m).slice(-2));
	}

    function openAlert(type,title,msg,btn){
		return new Promise((resolve) => {
            if(!btn){
                if(type=="confirm"){
                    btn = [{class:"btn-right",text:"確定",click :function(ev) {
                        $( this ).dialog( "close" );
                        resolve(true);
                    }},{class:"btn-right",text:"キャンセル",click :function(ev) {
                        $( this ).dialog( "close" );
                        resolve(false);
                    }}];
                }else{
                    btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
                }
            }
            var options = {"title":title,
                position:["center", 170],
                width: "auto",
                buttons:btn
            };
            $("#msg").html(msg);
            $( "#openAlert" ).dialog( "option",options);
            $( "#openAlert" ).dialog( "open" );
        });
    }

    function plan_create(){
        url = "Planning?lot_mno=";
        // window.open(url);
        location.href = url;
    }

    function get_bad_list(){
        let num = 0;
        let process = $('#工程').val()
        loadingView(true);
        $.ajax({
            type: 'GET',
            url: "/LaborReport/BadItemList",
            dataType: 'html',
            data:{num:num,
                itemcord:$("#製品コード").val(),
                plant:encodeURIComponent($("#plant").val()),
                workitem:encodeURIComponent(process),
                'ac':'Count'
            },
            success: function(d) {
                loadingView(false);
                let sp_ditem_check = new Set(d.split(","));
                let new_bab_list = [...sp_ditem_check];
                $("#bad_list").val(new_bab_list);
            }
        });
    }

</script>

<div id="main_menu" style="font-weight:bold;text-align:center;width:fit-content;margin:auto;">
    <div class='ipbox'>
        <p><lable >号機</lable></p>
        <input id="wel_mc" class="readonly-input" name="基本][wel_mc" value="<?=$sf_params->get("mno")?>" readonly="readonly" style="width:60px;height: 28px;" />
    </div>
    <div class='ipbox'>
        <p><lable >作業日</lable></p>
        <input id="wel_date" type="date" name="基本][wel_date" value="<?=date("Y-m-d")?>" style="width:150px;height: 28px;" />
    </div>      
    <div class='ipbox'>
        <p><label for="製品コード">製品コード</label></p>
        <input type="text" class="items_info_ip" value="PL550141-00" name="基本][製品コード" id="製品コード" readonly="readonly" style="width:150px;"/>
    </div>
    <div class='ipbox'>
        <p><label for="工程">工程</label></p>
        <input type="text" class="items_info_ip" value="<?=$process?>" name="基本][工程" id="工程" readonly="readonly" style="width:100px;"/>
    </div>
    <div class='ipbox'>
        <p><label for="開始日時">開始日時</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][開始" id="開始日時" readonly="readonly" onclick="datetime(this.id);" style="width:168px;padding:2px 4px;"/>
    </div>      
    <div class='ipbox'>
        <p><label for="終了日時">終了日時</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][終了" id="終了日時" readonly="readonly" onclick="datetime(this.id);" style="width:168px;padding:2px 4px;"/>
    </div>      
    <!-- <div class='ipbox'>
        <p><label for="作業時間">作業時間</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][作業時間" id="作業時間" style="width:80px;"/>
        <div style="clear:both;"></div>
    </div> -->
    <div class='ipbox'>
        <p><label for="担当者">担当者</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][担当者" id="担当者" onclick="searchUser(this.id);" readonly="readonly" style="width:120px;"/>
    </div>

    <input type="hidden" id="plant" name="基本][工場" value="<?=$plant?>" />
    <input type="hidden" id="plantid" name="基本][工場ID" value="<?=$lot_info[0]["placeid"]?>" />
    <input type="hidden" id="bad_list" name="" value="" />
    <input type="hidden" id="item_list" name="" value="" />
    <input type="hidden" id="cavs_list" name="" value="" />
    <input type="hidden" id="printer_ip" name="printer_ip" value="" />
    <input type="hidden" id="client_folder" name="client_folder" value="" />
    <div style="clear:both;"></div>
</div>

<div id="item_area" style="display: flex;margin:10px auto;width: fit-content;border: 2px solid blue;">

    <table id="sum_table" class="type03" style="">
        <thead style="text-align:center;text-align:center;position:sticky;top:0;z-index: 1000;">
            <tr>
                <th style="width:55px;" rowspan="2" onclick="">menu</th>
                <th style="width:55px;" rowspan="2">QR</th>
                <th style="width:90px;" rowspan="2">予定日</th>
                <th style="background: #00dbff;" colspan="4">FRONT</th>
                <th style="background: #1ee118;" colspan="4">BACK</th>
                <th style="width:45px;" rowspan="2">組合せ<br>キャビ</th>
                <th style="width:45px;" rowspan="2">生産数</th>
                <th style="width:45px;" rowspan="2">良品数</th>
                <th style="width: 45px;" rowspan="2">廃棄数</th>
                <th style="width:12px;" rowspan="2">廃棄内容</th>
                <!-- <th style="width:70px;" rowspan="2">サンプル</th> -->
                <th style="width:95px;" rowspan="2">時間</th>
                <th style="width:100px;" rowspan="2">担当者</th>
            </tr>
            <tr>
                <th style="width:95px;background: #00dbff;">部品</th>
                <th style="width:65px;background: #00dbff;">成形ロット</th>
                <th style="width:45px;width:45px;background: #00dbff;">キャビ</th>
                <th style="width:45px;background: #00dbff;">投入数</th>
                <th style="width:95px;background: #1ee118;">部品</th>
                <th style="width:65px;background: #1ee118;">成形ロット</th>
                <th style="width:60px;background: #1ee118;">キャビ</th>
                <th style="width:59px;background: #1ee118;">投入数</th>
            </tr>
        </thead>
        <tbody id="sum_table_bd">

        </tbody>
    </table>

    <input type="hidden" id="item_list" name="item_list" value="溶着廃棄" />

</div>

<div>
    <button id="entryData" type="button" onclick="weldEntryAllCheck();" class="" style="float:right;margin-right:20px;font-size:22px;font-weight:bold;padding:.5em .5em;">実績登録</button>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>

<div id="alertdt">
    <div id="messagedt" style="text-align: center;"></div>
</div>

<div id="diff_dialog" style="">
    <div id="diff_item" style="font-weight:bold;"></div>
    <div style="clear:both;"></div>
</div>
<label id="msgLabel" style="float:left;font-size:18px;"></label>

<!-- <button id="testPrinter" type="button" onclick="printKanbanApi();" class="" style="float:right;font-size:22px;font-weight:bold;padding:.5em 1em;">印刷</button> -->
<label id="entryLabel" style="float:right;font-size:18px;"></label>

<div id="dialog_user" title="作業者を選択してください" style="display:none;">
    <div id="ap-user-select"></div>
    <!-- <hr>
    コード入力：<input type="text" id="input_name" name="name" value="" style="margin-top:10px;" />
    <button id="addUser" type="button" onclick="setUser();" style="ime-mode: disabled;">決定</button> -->
</div>
<div id="openAlert">
    <div id="msg" style="text-align: center;"></div>
</div>
<div class="num_dialog">
    <div id="number_in" style="display:none;padding:0;">
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="num_btn_click(7);">7</p>
            <p class="num_btn" onclick="num_btn_click(8);">8</p>
            <p class="num_btn" onclick="num_btn_click(9);">9</p>
        </div>
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="num_btn_click(4);">4</p>
            <p class="num_btn" onclick="num_btn_click(5);">5</p>
            <p class="num_btn" onclick="num_btn_click(6);">6</p>
        </div>
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="num_btn_click(`1`);">1</p>
            <p class="num_btn" onclick="num_btn_click(2);">2</p>
            <p class="num_btn" onclick="num_btn_click(3);">3</p>
        </div>
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="num_btn_click(0);">0</p>
            <p class="num_btn" onclick="btn_del('del');">Del</p>
            <p class="num_btn" onclick="btn_control('ok');">OK</p>
        </div>
        <input id="num_in_tab" style="display:none;" value=""></input>
    </div>
</div>