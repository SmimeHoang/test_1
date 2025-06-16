
<?php
    use_javascript("jquery/jqplot/jquery.jqplot.min.js");
    use_javascript("jsQR.min.js");
    
    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");

    slot('h1', '<h1 class="header-text" style="margin:0 auto;">成形工程 生産数カウンター | Nalux '.$sf_request->getParameter('plant').' <span id="mode_view"></span> </h1>');
    $btn='<div style="float:right;" id="h_button">';
    $btn.='<label>プリンター：</label><button type="button" id="btn_change_printer" class="btn-header" onclick="change_printer()"></button>';
    // $btn.='<button type="button" id="re_print" class="btn-header" onclick="rePrintCam(`reprint_rfid`)">再印刷</button>';
    if($sf_request->getParameter('mode')=="conveyor"){
        $btn.='<label>画面モード：</label><button type="button" id="btn_change_mode" class="btn-header" onclick="change_mode()"></button></div>';
    }else{
        $btn.='<label>画面モード：</label><button type="button" id="btn_change_mode" class="btn-header" onclick="change_mode()"></button></div>';
    }
    // <button type="button" class="btn-header" onclick="change_stock()">トレー/束</button>
    slot('cd',$btn);
 ?>
<meta name="viewport" content="initial-scale=.8">

<style type="text/css">
    #content {width:100%; font-size:18px; text-align;margin:auto;}
    table.floor_item {width:100%;font-size:18px;border-collapse:collapse;text-align:center;line-height:2em;border-top:1px solid #ccc;border-left:1px solid #ccc;table-layout:fixed;}
    table.floor_item th {padding:3px;font-weight:bold;vertical-align:top;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table.floor_item td {vertical-align:top;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;}
    #th_item {margin-top:10px;width:100%;font-size:18px;border-collapse:collapse;text-align:center;border-top:1px solid #ccc;border-left:1px solid #ccc;table-layout:fixed;}
    #th_item th {padding:3px;font-weight:bold;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;line-height:2em;}
    #th_item td {padding:3px;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;}
    #tab_item .ui-button .ui-button-text {width:25px;height:25px;padding:0;}
    #bnt_diff span {padding:0;}
    .fl { float: left;}
    .fr { float: right;}
    #ditemsetpop { padding-top:2px;}
    #ditemsetpop .set {font-size:90%; line-height:1em;vertical-align: middle;display: block;width:185px; border: 1px #FFF solid;margin-left: -1px;margin-top: -1px;padding: 2px 3px ; }
    .box{ width:114px; text-align:center;float:left;margin:0 2px 2px 0;background-color: #ddd; }
    .b_line{ text-align:center;font-size:15px;font-weight:bold; }
    #main_menu input, #item_area input{padding:1px 0;outline: none;text-align:center;font-weight:bold;font-size:18px;}
    .n_input{border:none;width:92%;margin:3px;font-size:18px;text-align:center;font-weight:bold;color: #999;background-color: #ddd;}
    #addUser{font-size:80%;padding:0.1em 0.8em;margin: 0 0 0 5px;}
    #entryData span, #状態 span, .btn_ditemset .ui-button-text, #OpenSTD span {padding: 0;}
    #dialog hr {margin:5px 0 5px 0;}
    #user-list {margin-bottom:5px;}
    .main_cont {padding:5px;border:1px solid #00f;border-radius:5px;}
    .chk_proc .ui-button-text {padding: 1px 10px;}
    .ipbox{float:left;margin-right:8px;text-align:center;}
    .ui-widget{font-size:18px;}
    #year{text-align:center;}
    #dialog_user .ui-button-text{
        padding: 0px 5px;
    }
    #item_area .sum_value{width:60px;text-align:left;padding-left:5px;margin-right:5px;}

    .inserted-click{
        background-color: #75c100;
    }
    .items_info_ip {
        font-size:16px !important;
        height: 28px;
    }
    .ui-state-active{
        background: palegreen;
        border: 2px solid green;
        /* border: 2px solid #ccc;
        animation-name: anim-border;
        animation-duration: 2s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite; */
        /* -webkit-animation: anim-border 2s ease-in-out infinite; */
    }

    .btn-label .ui-button-text{
        padding: 4px 12px;
        font-weight: bold;
    }
    .ui-dialog{background:#f9f9f9 !important;}
    .btn-cav{
        padding:3px 12px 2px 12px;
    }
    .btn-cav .ui-button-text{
        padding: 0;
        font-weight: bold;
    }
    #sample_hide_view{
        margin-top:5px;
        padding:3px 12px 2px 12px;
    }
    #sample_hide_view .ui-button-text{
        padding: 0;
        font-weight: bold;
    }
    .btn-header{
        margin-right:5px;
    }
    .btn-header .ui-button-text{
        padding: 1px 4px;
        font-weight: bold;
    }
    .ui-dialog-buttonset{
        width: 100%;
    }

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
        white-space: nowrap;
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

</style>

<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
    <script type="text/javascript">
    </script>
</div>

<script type="text/javascript">
    var s = {},d = {},post_data = {},d_unit={},lot_info = {};
    var item_list = [];
    var all_cav = [];
    var lot_mno = "<?=$sf_params->get('lot_mno')?>";
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

    var access_entry = false;
    var sample_view=false;
    var tray_type = "トレー";
    var client_ip = "<?= $client_ip ?>";
    var client_os = "<?= $client_os ?>";

    $(document).ready(function(){
        var plant_name = $("#plant").val();
        var plant_id = $("#plantid").val();
        $("#btn_change_mode").button();
        if(mode=="cminus"){
            if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
                $("#btn_change_mode .ui-button-text").html('【－】ダウン');
            }else{
                $("#btn_change_mode .ui-button-text").html('GCM');
            }
            $("#mode_view").html("【－】");
        }else if(mode=="cplus"){
            if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
                $("#btn_change_mode .ui-button-text").html('【✚】アップ');
            }else{
                $("#btn_change_mode .ui-button-text").html('カット');
            }
            $("#mode_view").html("【✚】");
        }
    
        let ww = $(window).width();
        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width: ww-100,
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
            user_gr="製造係";
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
            searchUserCav('担当者','');
        });

        $("#打数").on('keyup',function(e){
            if(e.which===13){
                if($("#打数").val()!==""){
                    $("#打数").focusout();
                }
            }
        });

        $("#打数").on('focusout',function(e){
            if($("#打数").val()!==""){
                $("#打数").css({"background-color": ""});
                focus_control();
            }
        });

        $( '#input_name' ).on('keyup',function ( e ) {
            if ( e.which == 13 ) {
                searchUserCav('担当者','');
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

        // $("#entryData").button("disable");

        $("#OpenSTD").button('option', 'disabled', true);
    });

    async function change_mode(){
        let confirm_act = await openAlert("confirm","確認","計算モードを変換しますか？");
        if(confirm_act==false){
            return;
        }
        post_data={};
        s={};
        // mode = $("#select_mode").val();
        // setTimeout(() => {
        //     window.history.pushState('', 'Title', '?lot_mno='+lot_mno+'&mode='+mode);
        //     location.reload();
        // }, 0); 
        let check_user =$(".check_user_cav");
        let err = 0;
        $.each(check_user,function(k,v) {
            if(v.classList.value.indexOf("inserted-click")>-1){
                err++;
            }
        })
        if(err>0){
            var msg="途中でモード切り替わり出来ません。";
            var options = {"title":"確認してください！！！",
                width: 600,
                buttons: 
                    [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
            return;
        }

        if(mode=="cminus"){
            mode="cplus"
            if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
                $("#btn_change_mode .ui-button-text").html('【✚】アップ');
                // change_stock();
            }else{
                $("#btn_change_mode .ui-button-text").html("カット");
            }
            $("#mode_view").html("【✚】");
            // let_start(mode);
        }else{
            mode="cminus"
            if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
                $("#btn_change_mode .ui-button-text").html('【－】ダウン');
                // change_stock();
            }else{
                $("#btn_change_mode .ui-button-text").html('GCM');
            }
            $("#mode_view").html("【－】");
            // let_start(mode);
        }
        localStorage.setItem("mc_mode",mode);
        let list_code = $("#item_list").val().split(",");
        $.each(list_code,function(k,v){
            reloadCavTable(v,'');
        });
    }

    async function let_start(ac){
        $("#item_area").html("");
        let lot_mno = "<?=$sf_params->get('lot_mno')?>";
        let all_cav="";
        $.ajax({
            type: "GET",
            url: "",
            dataType: 'json',
            async:true,
            data:{
                "ac":"lot_info",
                "lot_mno":lot_mno,
                "lot_past":"<?= $_GET["lot_past"]?>"
            },
            success: function(d){       
                // console.log(d);                
                lot_info=d;
                if(lot_info == null || lot_info.info.length==0){
                    var msg="ロット情報はありません。";
                    var options = {"title":"確認してください！！！",
                        width: 600,
                        buttons: 
                            [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                                window.close();
                                return;
                            }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $( "#alert" ).dialog( "open" );
                    return;
                }
                if(lot_info.last_witem){
                    $('input[name=work_process]').val([lot_info.last_witem]).button( "refresh" );
                }
                mold = lot_info["info"][0]["lot_mno"];
                var item_list=[];
                if(lot_info.lasttime){
                    $("#開始日時").val(lot_info.lasttime.replace(/-/g , "/"));
                }
                $('select[name=place]').val(lot_info.lastplace);
                $("#item_list").val(lot_info.item_list);
                $.each(lot_info.info, function(key,item){
                    let item_infos = lot_info[item.item_code].item_info;
                    // let Std_digitization = lot_info[item.item_code].item_info.Std_digitization;
                    // if(Std_digitization.std_full_path!="err"){
                    //     $("#OpenSTD").button('option', 'disabled', false);
                    // }
                    let client_folder = lot_info[item.item_code].ms_item_info.client_folder;
                    $("#client_folder").val(client_folder);
                    if(item_infos==null){
                        let msg="<span style='color:red'>製品マスター</span>の<span style='color:red'>型別情報</span>を確認してください！";
                        let options = {"title":"製品の型別情報が足りない！",
                            position:["center",170],
                            width:450,
                            buttons: [{ text:"追加",class:'btn-confirm',click :function(ev) {
                                let url = "/MasterEdit/MoldItem?id="+lot_info[item.item_code].ms_item_info.id;
                                window.open(url);
                                return;
                            }},{ text:"閉じる",class:'btn-cancel',click :function(ev) {
                                $(this).dialog("close");
                                window.close();
                                return;
                            }}]
                        };
                        $("#message").html(msg);
                        $( "#alert" ).dialog( "option",options);
                        $("#alert").dialog( "open" );
                        return;
                    }else{
                        if(item_infos.cav_tray_input && item_infos.tray_num>0 && item_infos.tray_stock>0){
                            let send_cav = item_infos.cav_tray_input;
                            let shotnum = parseInt((item_infos.tray_num*item_infos.tray_stock));
                            // $("#打数").val(shotnum);
                            item_infos["serial"]=lot_info[item.item_code].serial;
                            item_infos["materialslot"]=lot_info["lastmetalot"];
                            if(item_infos.bundle_flg=="0"){
                                tray_type="トレー";
                            }else{
                                tray_type="束";
                            }
                            set_mold_table(item_infos);
                            if(lot_info.lasttime==null || lot_info.lasttime==false){
                                $("#開始日時").click();
                            }
                        }else if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
                            if(item_infos.cav_tray_input==""){
                                item_infos.cav_tray_input="キャビ分けなし"
                            }
                            let shotnum = parseInt((item_infos.tray_num*item_infos.tray_stock));
                            // $("#打数").val(shotnum);
                            item_infos["serial"]=lot_info[item.item_code].serial;
                            item_infos["materialslot"]=lot_info["lastmetalot"];
                            if(item_infos.bundle_flg=="0"){
                                tray_type="トレー";
                            }else{
                                tray_type="束";
                            }
                            set_mold_table(item_infos);
                        }else{
                            let msg="";
                            if(item_infos.tray_num>0 && item_infos.tray_stock>0){
                                msg="トレー内に<span style='color:red;'>キャビ混合</span>する場合「品目マスター修正」ボタンを押してください。";
                            }else{
                                msg="<span style='color:red;'>１トレーの個数</span>と<span style='color:red;'>トレー纏め数</span>を設定してください。";
                            }
                            let options = {"title":"トレー内の組合せ確認！",
                                position:["center",170],
                                width:600,
                                buttons: [{ text:"品目マスター修正",class:'btn-right',click :function(ev) {
                                    let url = "http://track.yasu.nalux.local/MasterEdit/MoldItem?id="+item_infos.id;
                                    window.open(url);
                                    window.close();
                                    return;
                                }},{ text:"閉じる",class:'btn-cancel',click :function(ev) {
                                    // set_mold_table(item_infos,null);
                                    // $.each(lot_info.info, function(k,i){
                                    //     tem_infos = lot_info[i.item_code].item_info;
                                    //     let totalnum = parseInt((item_infos.tray_num*item_infos.tray_stock)/item_infos.pieces);
                                    //     $("#打数").val(totalnum);
                                    //     item_infos["serial"]=lot_info[item.item_code].serial;
                                    //     if(item_infos.cav_items){
                                    //         item_infos["materialslot"]=lot_info["lastmetalot"];
                                    //         if(item_infos.bundle_flg=="0"){
                                    //             tray_type="トレー";
                                    //         }else{
                                    //             tray_type="束";
                                    //         }
                                    //         set_mold_table(item_infos,item_infos.cav_items);
                                    //     }else{
                                    //         alert("キャビ情報がない！！！　\n製品マスターの型別情報を確認してください！");
                                    //         let url = "/MasterEdit/MoldItem?id="+lot_info[item.item_code].ms_item_info.id;
                                    //         window.open(url);
                                    //         return false;
                                    //     }
                                    //     $("#alert").dialog("close");
                                    // });
                                    $("#alert").dialog("close");
                                    window.close()
                                }}],
                            };
                            $("#message").html(msg);
                            $( "#alert" ).dialog( "option",options);
                            $("#alert").dialog( "open" );
                        }
                    }
                });
                openDigital(false);
            }
        });
    }

    function set_running(item_code,cb){
        let running = lot_info[item_code].running;
        let run_stage_now = 1;
        if(running.length==0){
            $('.dan_'+item_code+'_'+run_stage_now).addClass("now-run-click");
            // $('.dan_'+item_code+'_'+run_stage_now).removeClass("un_mold_unit");
        }else{
            $.each(running,function(kr,vr){
                let r_posi = item_code+"_"+vr.hrut_cav+"_"+vr.hrut_stages;    
                s["hrutid_"+r_posi]=vr.hrut_id;
                s["sum_"+r_posi]=vr.hrut_g_num;
                s["usercav_"+item_code+"_"+vr.hrut_stages]=vr.hrut_user;
                s["traytime_"+item_code+"_"+vr.hrut_stages]=vr.hrut_e_datetime.substr(0,vr.hrut_e_datetime.length-3).replaceAll("-","/");
                if(vr.hrut_d_contents!=""){
                    let r_ditem = vr.hrut_d_contents.split(",");
                    $.each(r_ditem,function(krd,vrd){
                        let vrd_num = vrd.split("=>");
                        s[vrd_num[0]+"_"+r_posi]=vrd_num[1];
                    });
                }
                $('.dan_'+item_code+'_'+vr.hrut_stages).removeClass("un_mold_unit");
                $('.dan_'+item_code+'_'+vr.hrut_stages).addClass("inserted-click"); 
            });
            run_stage_now = parseInt(running[0].hrut_stages)+1;
            $('.dan_'+item_code+'_'+run_stage_now).addClass("now-run-click");
            // $('.dan_'+item_code+'_'+run_stage_now).removeClass("un_mold_unit");
        }
        $('.inserted-click').on('click',function(e){
            var options = {"title":"確認",
                position:["center",170],
                width:600,
                buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                    $( this ).dialog( "close" );
                    let tid = e.target.id.split("_");
                    if(tid[0]=="traytime" || tid[0]=="usercav"){
                        children_Entry('usercav_'+tid[1]+'_'+tid[2],"children_Update");
                    }else{
                        // check_running(tid[1]+'_'+tid[2]+'_'+tid[3]);
                        open_diff_floor(tid[1]+'_'+tid[2]+'_'+tid[3],true)
                    }
                }},{ text:"閉じる",class:'btn-cancel',click :function(ev) {
                    $( this ).dialog( "close" );
                }}],
            };
            var msg="保存した不良内容を修正してもよろしでしょうか?";
            $("#alert").css({"padding":"10px"});
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog( "open" );

        });

        $('.now-run-click').on('click',function(e){
            let tid = e.target.id.split("_");
            if(tid[0]=="traytime"){
                searchUserCav('usercav_'+tid[1]+'_'+tid[2],'children_Entry');
            }else if(tid[0]=="usercav"){
                searchUserCav(e.target.id,'children_Entry');
            }else{
                // check_running(tid[1]+'_'+tid[2]+'_'+tid[3]);
                open_diff_floor(tid[1]+'_'+tid[2]+'_'+tid[3],false)
            }
        });
        // $(".un_mold_unit").unbind();

        setTimeout(() => {
            set_diff();
            cb();
        }, 0);
    }

    function set_mold_table(item_infos){
        // console.log(item_infos);
        let item_code = item_infos.itemcord;
        $("#cycle").val(item_infos["cycle"]);
        get_bad_list(item_code);
        let item_name = item_infos["itemname"]
        if(item_infos["searchtag"]){
            item_name=item_infos["searchtag"];
        }
        let form_num = item_infos["form_num"];
        // let machine_stock = item_infos["machine_stock"];
        let serial_num = item_infos["serial"];
        if (serial_num==undefined || serial_num==0){
            serial_num = 1;
        }
        let cavs = item_infos.cav_tray_input;
        //トーレ中の数
        let tray_num = item_infos["tray_num"];
        //段数
        let tray_stock = item_infos["tray_stock"];
        let mold_num = parseInt(tray_num)*parseInt(tray_stock);

        //不良
        cav = cavs.split(",");

        line='<div id="form_'+item_code+'" class="main_cont" style="display:block;font-weight:bold;margin-bottom:10px;">';
        line+=`<div style="float:right;">`;
        if(item_infos.moldet_undetected_load=="1"){
            line+=`<label style="color:white;background:#00B0F0;padding:4px 10px;border-radius:5px;">成形未検出荷</label>`;
        }
        line+= '<div style="clear:both;"></div>';
        line+=`<button id="sample_hide_view" type="button" onclick="sample_hide_view();" class="" style="font-weight:bold;float:right;">サンプル✚</button>`;
        line+=`</div>`;
        line+=`<div style="display:inline-block;margin-bottom: 4px;"><div class='ipbox'>
            <p><label for="`+item_code+`_itemcode">品目コード</label></p>
            <input id="`+item_code+`_itemcode" class="items_info_ip readonly-input" name="`+item_code+`][品目コード" value="`+item_code+`" readonly="readonly" style="width:130px"/>
        </div>
        <div class='ipbox'>
            <p><label for="`+item_code+`_form_num">型番</label></p>
            <input id="`+item_code+`_form_num" class="items_info_ip readonly-input" name="`+item_code+`][form_num" value="`+form_num+`" readonly="readonly" style="width:40px" />
            <div style="clear:both;"></div>
        </div>
        <div class='ipbox'>
            <p><label for="`+item_code+`_itemname">品名</label></p>
            </lable><input id="`+item_code+`_itemname" class="items_info_ip readonly-input" name="" value="`+item_name+`" readonly="readonly" style="width:240px;" />
        </div>
        <input type="hidden" name="`+item_code+`][item_name" class="items_info_ip" value="`+item_infos["itemname"]+`" />
        <div class='ipbox'>
            <p><label for="`+item_code+`_pieces">取数</label></p>
            <input id="`+item_code+`_pieces" class="items_info_ip readonly-input" name="`+item_code+`][pieces" value="`+item_infos["pieces"]+`" readonly="readonly" style="width:45px" />
        </div>
        <div class='ipbox'>
            <p><label for="`+item_code+`_serial">通しNo</label></p>
            <input id="`+item_code+`_serial" class="items_info_ip readonly-input" name="`+item_code+`][serial" value="`+serial_num+`" readonly="readonly" style="width:65px" />
        </div>
        `;
        if(tray_type=="束"){
            line+=`<div class='ipbox'>
                <p><label for="`+item_code+`_traynum">1<span id="label_`+item_code+`_traynum">束</span>(個数)</label></p>
                <input id="`+item_code+`_traynum" class="items_info_ip stock-info change-stock" name="`+item_code+`][tray_num" value="`+tray_num+`" title="1束の数、値が変更できます。" style="width:100px" />
                <div style="clear:both;"></div>
            </div>`;
            line+=`<div class='ipbox'>
                <p><label for="`+item_code+`_traystock"><span id="label_`+item_code+`_traystock">束</span>纏め数</label></p>
                <input id="`+item_code+`_traystock" class="items_info_ip stock-info change-stock" name="`+item_code+`][tray_stock" value="`+tray_stock+`" title="1箱の完成品数、値が変更できます。" style="width:100px" />
            </div>`;
        }else{
            line+=`<div class='ipbox'>
                <p><label for="`+item_code+`_traynum">1<span id="label_`+item_code+`_traynum">トレー</span>(個数)</label></p>
                <input id="`+item_code+`_traynum" class="items_info_ip stock-info" name="`+item_code+`][tray_num" value="`+tray_num+`" readonly="readonly" title="値がマスターで指定されます。" style="width:120px" />
            </div>`;
            line+=`<div class='ipbox'>
                <p><label for="`+item_code+`_traystock"><span id="label_`+item_code+`_traystock">トレー</span>纏め数</label></p>
                <input id="`+item_code+`_traystock" class="items_info_ip stock-info" name="`+item_code+`][tray_stock" value="`+tray_stock+`" title="値がマスターで指定されます。" readonly="readonly" style="width:120px" />
            </div>`;
        }
        line+=`<div class='ipbox wi_cav'>
            <p><label for="`+item_code+`_cavs">キャビ内訳</label></p>
            <input type="" id="`+item_code+`_cavs" class="change-cav" name="`+item_code+`][cavs" value="`+cavs+`" placeholder="カンマ区切り" style="width:160px" />
        </div>`;
        line+=`<div class='ipbox'>
            <p><label for="`+item_code+`_traystock">キャビ分け</label></p>
            <input type="radio" name="cav_`+item_code+`" class="cav-sp_`+item_code+` cav-sp" id="a1_`+item_code+`" value="あり" /><div class="anim-border"></div><label for="a1_`+item_code+`" class="btn-cav" style="height:26px;" >あり</label>
            <input type="radio" name="cav_`+item_code+`" class="cav-sp_`+item_code+` cav-sp" id="a2_`+item_code+`" value="なし" /><label for="a2_`+item_code+`" class="btn-cav" style="height:26px;margin-left: -7px;" >なし</label>
        </div>`;
        // line+= '<input type="hidden" id="'+item_code+'_cavs" name="'+item_code+'][cavs" value="'+cavs+'" />';
        line+= '<input type="hidden" id="'+item_code+'_mscavs" name="'+item_code+'][mscavs" value="'+item_infos["cav_items"]+'" />';
        line+= '<input type="hidden" id="'+item_code+'_materialcode" name="'+item_code+'][materialcode" value="'+item_infos["materialcode"]+'" />';
        line+= '<input type="hidden" id="'+item_code+'_matername" name="'+item_code+'][matername" value="'+item_infos["materialsname"]+'" />';
        line+= '<input type="hidden" id="'+item_code+'_materoneshot" name="'+item_code+'][materoneshot" value="'+item_infos["materials1used"]+'" />';
        line+= '<input type="hidden" id="'+item_code+'_materialslot" name="'+item_code+'][materialslot" value="'+item_infos["materialslot"]+'" />';
        line+= '<input type="hidden" id="'+item_code+'_price" name="'+item_code+'][price" value="'+item_infos["price"]+'" /></div>';

        line+= '<div style="clear:both;"></div>';
        line+='<table id="'+item_code+'_count" class="floor_item" style="margin:0;">';
        line+='<tbody><tr>';
        line+='<th style="width:90px;">キャビNo.</th>';
        line+='<th class="plus_sample"><span class="dan-label-'+item_code+'">サンプル</span></th>';
        for(var i = 1;i<=tray_stock;i++){
            if(tray_type=="トレー"){
                line+= '<th><span class="dan-label-'+item_code+'">段</span>'+i+'<label class="btn_set_round" onclick="openFixDialog(`'+item_code+'_'+i+'`)" style="">丸め</label></th>';
            }else{
                line+= '<th><span class="dan-label-'+item_code+'">束</span>'+i+'<label class="btn_set_round" onclick="openFixDialog(`'+item_code+'_'+i+'`)" style="">丸め</label></th>';
            }
            line+= '<input type="hidden" id="'+item_code+'_'+i+'_traynum" value="'+tray_num+'" class="" title="" />';
        }
        line+="</tr></tbody>";
        line+='<tbody class="cavs">';

        $.each(cav, function(k,v) {
            let sum_id = item_code+'_'+v;
            line+= '<tr id="cav_'+v+'">';
            line+= '<td id="'+sum_id+'_cav_view" style="color:darkorange;overflow:hidden;white-space:nowrap;" title="#'+v+'" onclick="openCamScan(`'+sum_id+'_rfid`,`'+v+'`);">#'+v+'</td>';
            // line+= '<td><input id="sum_'+sum_id+'" name="sum]['+item_code+']['+v+'" value="'+mold_num+'" onclick="open_diff(`'+sum_id+'`)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            // line+= '<td><input id="user_'+sum_id+'_'+i+'" class="cav_user" name="user]['+item_code+']['+v+'" value="" onclick="searchUserCav(this.id)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            line+= '<td class="plus_sample"><input id="sum_'+sum_id+'_sample" class="dan_'+item_code+'_sample fix-able un_mold_unit sample_input" name="sum]['+item_code+']['+cav[k]+'][sample" value="0" onclick="open_sample_count(`'+sum_id+'_sample`);" readonly="readonly" style="border:none;width:99%;height:32px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            for(var i = 1;i<=tray_stock;i++){
                // if(mode=="cminus"){
                    line+= '<td><input id="sum_'+sum_id+'_'+i+'" class="dan_'+item_code+'_'+i+' fix-able un_mold_unit" name="sum]['+item_code+']['+cav[k]+']['+i+'" value="'+tray_num+'" readonly="readonly" style="border:none;width:99%;height:32px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
                // }else{
                //     line+= '<td><input id="sum_'+sum_id+'_'+i+'" class="dan_'+item_code+'_'+i+'" name="sum]['+item_code+']['+cav[k]+']['+i+'" value="'+tray_num+'" onclick="open_diff_floor(`'+sum_id+'_'+i+'`)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
                // }
                line+= '<input type="hidden" id="hrutid_'+sum_id+'_'+i+'" class="" name="" value="" />';
            }
            
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_mold_num" name="'+item_code+']['+v+'][生産数" value="'+mold_num+'" />';
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_good_num" class="check_cav_gnum" name="'+item_code+']['+v+'][良品数" value="'+mold_num+'" />';
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_bad_num" name="'+item_code+']['+v+'][不良数" value="0" />';
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_rfid" class="check_rfid" name="'+item_code+']['+v+'][rfid" value="" />';
            line+= '</tr>';
            line+= '<tr>';
        });
        line+= '</tr><tr>';
        line+= '<td>担当者</td>';
        line+= '<td class="plus_sample"></td>';
        for(var i = 1;i<=tray_stock;i++){
            // if(mode=="cminus"){
                line+= '<td><input id="usercav_'+item_code+'_'+i+'" name="tuser]['+item_code+']['+i+'" class="item_'+item_code+' dan_'+item_code+'_'+i+' check_user_cav un_mold_unit" value="" readonly="readonly" style="border:none;width:99%;height:32px;font-size:16px;text-align:center;font-weight:bold;" /></td>';
            // }else{
            //     line+= '<td><input id="usercav_'+item_code+'_'+i+'" name="tuser]['+item_code+']['+i+'" class="dan_'+item_code+'_'+i+' check_user_cav" value="" onclick="searchUserCav(this.id)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            // }
        }
        line+= '</tr>';
        line+='<tr><td>確認日時</td>';
        line+= '<td class="plus_sample"></td>';
        for(var i = 1;i<=tray_stock;i++){
            // if(mode=="cminus"){
                line+= '<td><input id="traytime_'+item_code+'_'+i+'" class="dan_'+item_code+'_'+i+' un_mold_unit" name="tray_time]['+item_code+']['+i+'" value="" readonly="readonly" style="border:none;width:99%;height:32px;font-size:16px;text-align:center;font-weight:bold;" /></td>';
            // }else{
            //     line+= '<td><input id="traytime_'+item_code+'_'+i+'" class="dan_'+item_code+'_'+i+'" name="tray_time]['+item_code+']['+i+'" value="" onclick="searchUserCav(`usercav_'+item_code+'_'+i+'`)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            // }
        }
        line+='</tr>';
        line+='</tbody>';
        line+='</table>';
        line+='<p style="margin:5px 0;">';
        line+='<lable >生産数:</lable><input id="all_molding_'+item_code+'" class="sum_value" value="0" name="'+item_code+'][生産数" readonly="readonly" style="text-align:center;" /><lable id="lable_all_mold_'+item_code+'">/'+tray_num*tray_stock*cav.length+'</lable>';
        line+='<lable style="margin-left:20px;">良品数:</lable><input id="all_good_'+item_code+'" class="sum_value" value="0" name="'+item_code+'][良品数" readonly="readonly" style="text-align:center;" />';
        line+='<lable style="margin-left:20px;">不良数:</lable><input id="all_bad_'+item_code+'" class="sum_value" value="0" name="'+item_code+'][不良数" readonly="readonly" style="text-align:center;" />';
        line+='</p>';
        line+='<p><textarea id="'+item_code+'_remark" class="sum_value" value="" name="'+item_code+'][remark" placeholder="備考" style="width:100%;padding:0;font-size:18px;height:28px;" /></p>';
        line+='</div>';
        line+='<div style="clear:both;"></div>';
        $("#item_area").append(line);
        
        $(".plus_sample").hide();
        $(".cav-sp").button();
        $("#sample_hide_view").button();
        $("#"+item_code+"_bundle").on("keyup",function(e){
            if(e.target.value!=""){
                setTimeout(() => {
                    let_start(mode);
                }, 1000);
            }
        });
        $(".need_input").on('keypress', function(e) {
            if(e.keyCode===13){
                if($(':focus')[0].value!=""){
                    $("#"+$(':focus')[0].id).css({"background-color":"white"});
                    focusControl(item_list);
                    // sum_all(item_list)
                }else{
                    $("#"+$(':focus')[0].id).css({"background-color":"red"});
                }
            }
        });

        if(tray_stock == 0 || tray_num == 0 || "<?=$sf_request->getParameter('mode')?>"=="conveyor"){
            $("#"+item_code+"_traynum").addClass("change-stock");
            $("#"+item_code+"_traynum").attr('readonly',false);
            $("#"+item_code+"_traystock").addClass("change-stock");
            $("#"+item_code+"_traystock").attr('readonly',false);
        }

        $(".change-cav").on('keyup',function(e){
            let itc = e.currentTarget.id.split("_")[0];
            if(e.keyCode==13){
                reloadCavTable(itc,'');
            }
        })

        $(".change-cav").on("focusout",function(e){
            let itc = e.currentTarget.id.split("_")[0];
            reloadCavTable(itc,'');
        })

        $(".change-stock").on('keyup',function(e){
            let itc = e.currentTarget.id.split("_")[0];
            setTimeout(() => {
                reloadCavTable(itc,cavs);
            }, 0);
        });

        $(".change-stock").on('focus',function(e){
            $("#"+e.currentTarget.id).select();
        });

        if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
            $(".wi_cav").show();
        }else{
            $(".wi_cav").hide();
        }
        $(".cav-sp_"+item_code).button();
        $(".cav-sp_"+item_code).on('change', function(e) {
            var options = {"title":"確認",
                width:500,
                position:["center",170],
                buttons: [{ text:"はい",class:'btn-confirm',click :function(ev) {
                    $( this ).dialog( "close" );
                    if(lot_info[item_code].running.length>0){
                        if(e.target.value=="あり"){
                            $('input[name=cav_'+item_code+']').val(["なし"]).button( "refresh" );
                        }else{
                            $('input[name=cav_'+item_code+']').val(["あり"]).button( "refresh" );
                        }
                        var msg="途中でモード切り替わり出来ません。";
                        var options = {"title":"確認してください！！！",
                            width: 600,
                            buttons: 
                                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                    $( this ).dialog( "close" );
                                    return;
                                }}]
                        };
                        $("#message").html(msg);
                        $( "#alert" ).dialog( "option",options);
                        $( "#alert" ).dialog( "open" );
                        return false;
                    }
                    if(mode=="cplus"){
                        // return false;
                    }
                    tray_stock=$("#"+item_code+"_traystock").val();
                    tray_num = $("#"+item_code+"_traynum").val();
                    mold_num= parseInt(tray_stock)*parseInt(tray_num);
                    if(e.target.value=="あり"){
                        $('input[name=cav_'+item_code+']').val(["あり"]).button( "refresh" );
                        cavs=item_infos.cav_tray_input
                        if(cavs==null){
                            $('input[name=cav_'+item_code+']').val(["なし"]).button( "refresh" );
                            return;
                        }
                        cav=cavs.split(",")
                    }else{
                        $('input[name=cav_'+item_code+']').val(["なし"]).button( "refresh" );
                        cavs = "キャビ分けなし";
                        cav = new Array("キャビ分けなし");
                        // let_start("キャビ分けなし")
                    }

                    $('#'+item_code+'_cavs').val(cavs)

                    reloadCavTable(item_code,cavs);

                    setTimeout(() => {
                        // if(mode=="cminus"){
                        set_running(item_code,function(){
                            sum_all(item_code,cav);
                        });
                        // }
                    }, 0);
                }},{ text:"いいえ",class:'btn-cancel',click :function(ev) {
                    $( this ).dialog( "close" );
                    if(cavs=="キャビ分けなし"){  
                        $('input[name=cav_'+item_code+']').val(["なし"]).button( "refresh" );
                    }else{
                        $('input[name=cav_'+item_code+']').val(["あり"]).button( "refresh" );
                    }
                    return false; 
                }}]
            };
            $("#message").html("本当にキャビ分けモードを変更しますか？");
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
            return false; 
        });
        if(cavs=="キャビ分けなし"){  
            $('input[name=cav_'+item_code+']').val(["なし"]).button( "refresh" );
        }else{
            $('input[name=cav_'+item_code+']').val(["あり"]).button( "refresh" );
        }
        setTimeout(() => {
            // if(mode=="cminus"){
            set_running(item_code,function(){
                sum_all(item_code);
            })
        }, 0);
        
        // プリンター設定のチェック
        if(!printer_ip){
            $("#btn_change_printer span").html("未設定");
            change_printer();
        }else{
            $("#printer_ip").val(printer_ip[1]);
            $("#btn_change_printer span").html(printer_ip[0]);
        }
    }

    function reloadCavTable(item_code,cavs,cb){
        s={};
        post_data={};
        item_list=[];
        // $("#uncut_mold_"+item_code).html("");
        let tray_stock = $("#"+item_code+"_traystock").val();
        let tray_num = $("#"+item_code+"_traynum").val();
        if(tray_stock == "0"|| tray_num == "0" && mode == "cminus"){
            if(lot_info){
                tray_stock=lot_info[item_code].item_info.tray_stock;
                $("#"+item_code+"_traystock").val(tray_stock);
                tray_num=lot_info[item_code].item_info.tray_num;
                $("#"+item_code+"_traynum").val(tray_num);
            }else{
                let_start();
            }
        }
        let mold_num = parseInt(tray_num)*parseInt(tray_stock);

        let line = '';
        line+='<table id="'+item_code+'_count" class="floor_item" style="margin: 10px 0;">';
        line+='<tbody><tr>';
        line+='<th style="width:90px;">キャビNo.</th>';
        line+='<th class="plus_sample"><span class="dan-label-'+item_code+'">サンプル</span></th>';
        for(var i = 1;i<=tray_stock;i++){
            if(tray_type=="トレー"){
                line+= '<th><span class="dan-label-'+item_code+'">段</span>'+i+'<button class="btn_set_round" onclick="openFixDialog(`'+item_code+'_'+i+'`)" >丸め</button></th>';
            }else{
                line+= '<th><span class="dan-label-'+item_code+'">束</span>'+i+'<button class="btn_set_round" onclick="openFixDialog(`'+item_code+'_'+i+'`)" >丸め</button></th>';
            }
            line+= '<input type="hidden" id="'+item_code+'_'+i+'_traynum" value="'+tray_num+'" class="" title="" />';
        }
        line+="</tr></tbody>";
        
        line+='<tbody class="cavs">';
        if(cavs!=""){
            cav = cavs.split(",");
        }else{
            cavs = $('#'+item_code+'_cavs').val()
            cav=cavs.split(",");
        }
        $('#'+item_code+'_cavs').val(cavs);
        $.each(cav, function(k,v) {
            let sum_id = item_code+'_'+v;
            line+= '<tr id="cav_'+v+'">';
            line+= '<td id="'+sum_id+'_cav_view" style="color:darkorange;overflow:hidden;white-space:nowrap;" title="#'+v+'" onclick="openCamScan(`'+sum_id+'_rfid`,`'+v+'`);">#'+v+'</td>';
            // line+= '<td><input id="sum_'+sum_id+'" name="sum]['+item_code+']['+v+'" value="'+mold_num+'" onclick="open_diff(`'+sum_id+'`)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            // line+= '<td><input id="user_'+sum_id+'_'+i+'" class="cav_user" name="user]['+item_code+']['+v+'" value="" onclick="searchUserCav(this.id)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            line+= '<td class="plus_sample"><input id="sum_'+sum_id+'_sample" class="dan_'+item_code+'_sample fix-able un_mold_unit sample_input" name="sum]['+item_code+']['+cav[k]+'][sample" value="0" onclick="open_sample_count(`'+sum_id+'_sample`);" readonly="readonly" style="border:none;width:99%;height:32px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            for(var i = 1;i<=tray_stock;i++){
                // line+= '<td><input id="sum_'+sum_id+'_'+i+'" class="dan_'+item_code+'_'+i+'" name="sum]['+item_code+']['+cav[k]+']['+i+'" value="'+tray_num+'"  onclick="open_diff_floor(`'+sum_id+'_'+i+'`)"  readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
                line+= '<td><input id="sum_'+sum_id+'_'+i+'" class="dan_'+item_code+'_'+i+' fix-able un_mold_unit" name="sum]['+item_code+']['+cav[k]+']['+i+'" value="'+tray_num+'" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
                line+= '<input type="hidden" id="hrutid_'+sum_id+'_'+i+'" class="" name="" value="" />';
            }
            
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_mold_num" name="'+item_code+']['+v+'][生産数" value="'+mold_num+'" />';
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_good_num" class="check_cav_gnum" name="'+item_code+']['+v+'][良品数" value="'+mold_num+'" />';
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_bad_num" name="'+item_code+']['+v+'][不良数" value="0" />';
            line+= '<input type="hidden" id="'+item_code+'_'+v+'_rfid" class="check_rfid" name="'+item_code+']['+v+'][rfid" value="" />';
            line+= '</tr>';
            line+= '<tr>';
        });
        line+= '</tr><tr>';
        line+= '<td>担当者</td>';
        line+= '<td class="plus_sample"></td>';
        for(var i = 1;i<=tray_stock;i++){
            line+= '<td><input id="usercav_'+item_code+'_'+i+'" name="tuser]['+item_code+']['+i+'" class="item_'+item_code+' dan_'+item_code+'_'+i+' check_user_cav un_mold_unit" value="" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
            // line+= '<td><input id="usercav_'+item_code+'_'+i+'" name="tuser]['+item_code+']['+i+'" class="dan_'+item_code+'_'+i+' check_user_cav" value="" onclick="children_Entry(this.id)" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
        }
        line+= '</tr>';
        line+='<tr><td>確認日時</td>';
        line+= '<td class="plus_sample"></td>';
        for(var i = 1;i<=tray_stock;i++){
            line+= '<td><input id="traytime_'+item_code+'_'+i+'" class="dan_'+item_code+'_'+i+' un_mold_unit" name="tray_time]['+item_code+']['+i+'" value="" readonly="readonly" style="border:none;width:100%;height:34px;font-size:18px;text-align:center;font-weight:bold;" /></td>';
        }
        line+='</tr>';
        line+='</tbody>';
        line+='</table>';
        $("#"+item_code+"_count").html(line);
        if(!sample_view){
            $(".plus_sample").hide();
        }
        $("#lable_all_mold").html('/'+tray_num*tray_stock*cav.length)
        // $("#all_molding_"+item_code).val(0);
        // $("#all_good_"+item_code).val(0);
        // $("#all_bad_"+item_code).val(0);
        set_running(item_code,function(){
            sum_all(item_code);
        });
        if("<?=$sf_request->getParameter('mode')?>"=="conveyor"){
            $(".wi_cav").show();
        }else{
            $(".wi_cav").hide();
        }
        if(cb){
            cb();
        }
    }

    var lc = "";
    var entry_update = "";
    function searchUserCav(id,ac){
        let sp_id = id.split("_");
        lc=id;
        entry_update = ac;
        var options = {
            position:["center",170],
            width: $(window).width()-100,
            buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                $( this ).dialog( "close" )
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };
        if(id!=="担当者"){
            $('.dan_'+sp_id[1]+'_'+sp_id[2]).removeClass("un_mold_unit");
            sum_all(sp_id[1]);
            options = {
                position:["center",170],
                width: $(window).width()-100,
                buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                    $( this ).dialog( "close" )
                }}],
                open:function(e,ui){ $(".ui-dialog-titlebar-close").show(); }
            };
        }

        $( "#dialog_user" ).dialog( "option",options);
        $( "#dialog_user" ).dialog( "open" );
        
        //社員番号無効するように
        // $.getJSON("/LotManagement/BarcodeUserSet?ac=user&code="+decodeURIComponent($("#input_name").val()),function(data){
        //     if(data.length>0){
        //         $("#"+id).val(data[0].user);
        //         $( "#dialog_user" ).dialog( "close" );
        //         $("#input_name").val("");
        //     }else{
        //         $("#input_name").val("");
        //     }
        // });
        return false;
    }

    function setUser(name){
        if(lc){
            if(lc=="btn_change_printer"){
                lc="";
                $("#担当者").val(name);
                $( "#dialog_user" ).dialog( "close" );
                change_printer();
                return;
            }else{
                $("#"+lc).val(name);
                if(lc=="担当者"){
                    $(".cav_user").val(name);
                }
                $("#"+lc).css({"background-color": ""});
            }
        }
        $( "#dialog_user" ).dialog( "close" );
        if(lc=="担当者"){
            focus_control();
            lc = "";
        }else{
            // console.log(lc);
            let sp_lc=lc.split("_");
            $("#traytime_"+sp_lc[1]+"_"+sp_lc[2]).val(nowDT("dhm"));
            // sum_all(sp_lc[1]);
            setTimeout(() => {
                // if()
                // children_Entry(lc,null);
                children_Entry(lc,entry_update);   
                lc = "";
            }, 0); 
        }
        return false;
    }

    function openFixDialog(posi){
        var msg=`<div style=""><label>まるめ数：</label>
            <input type="number" id="round_number" value="" placeholder="" style="width:60px;text-align:right;" ></input>
        </div>`;
        let danname = posi.split("_")[1];
        var options = {"title":"「段"+danname+"」のまるめ数を入力してください。",
            position:["center",170],
            width: 400,
            buttons: 
                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                    return;
                }},{ class:"btn-right",text:"確定",click :function(ev) {
                    let rn = $("#round_number").val();
                    $( this ).dialog( "close" );
                    if (rn == 0 || rn == ""){
                        return;
                    }else{
                        fixCavNum(posi,rn);
                        return;
                    }
                }}]
        };
        $("#message").html(msg);
        $(".ui-resizable-handle").hide();
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
    }

    function fixCavNum(posi,num){
        // console.log(posi);
        let spp = posi.split("_");
        let public_traynum = $("#"+spp[0]+"_traynum").val();
        let old_num =  parseInt($("#"+posi+"_traynum").val());
        $("#"+posi+"_traynum").val(num);
        $.each($(".dan_"+posi+".fix-able"),function(k,v){
            // let new_val = parseInt(v.value)-(old_num-parseInt(num));
            let new_val = parseInt(v.value)-(old_num-parseInt(num));
            $("#"+v.id).val(new_val);
        })
        setTimeout(() => {
            sum_all(spp[0]);
        }, 100);
    }

    async function openCamScan(id,name,fcmode){
        // console.log(id);
        let sp_this_id = id.split("_");
        if(parseInt($("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_good_num").val())==0){
            $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"gray"});
            var options = {"title":"キャビ「＃"+name+"」はID紐づけ出来ないです。",
                position:["center",170],
                width: 600,
                buttons: [{ class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                    return;
                }}]
            };
            $("#message").html("良品数が【0】個なので、ID連携必要ないです。");
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
           return; 
        }else{
            $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"darkorange"});
        }

        let rffc = await rfid_fix_confirm(id);
        if(!rffc){
            return false;
        }
        let rfc = await rfid_check(id);
        if(!rfc){
            return false;
        }
        let sp_id = id.split("_");
        $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").addClass("toggle-color");
        // loadingView(true);
        $("#"+document.activeElement.id).attr('readonly', 'readonly');
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:600} }).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            tick(id);
        });

        let button = [
            { html:"<span class='dialog_btn ui-icon-edit ui-btn-icon-right'>入力</span>",class:"btn-right", click :function() {
                qr_reader="scaner";
                stop_scan();
                input_mode(id,name,fcmode);
                $("#"+id).focus();
                return;
            }},{ html:"<span class='dialog_btn ui-icon-recycle ui-btn-icon-right'>カメラ切替</span>",class:"btn-right",click :function() {
                if(fcmode=="user"){
                    fcmode="environment";
                }else{
                    fcmode="user";
                }
                localStorage.setItem("sFcmode",fcmode);
                stop_scan();
                setTimeout(() => {
                    openCamScan(id,name,fcmode);
                }, 200);
            }},
            { html:"<span class='dialog_btn ui-icon-delete ui-btn-icon-right'>閉じる</span>",class:"btn-left",click :function() {
                stop_scan();
            }}
        ];

        var options = {"title":"#"+name+"のQRコードをスキャンしてください。",
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        loadingView(false);
        $("#QRScan").dialog( "open" );
        $("#"+id+"_rfid").focus();
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
                    console.log(code.data);
                    stop_scan();
                    if(id=="reprint_rfid"){
                        re_print(code.data);
                    }else{
                        let errc=0;
                        let ci = $(".check_rfid");
                        let er_id = "";
                        let multiple_id = "";
                        $.each(ci, function(k,item){
                            if(item.value==code.data){
                                if(errc==0){
                                    er_id=item.id;
                                }
                                multiple_id=item.id.split("_")[1];
                                errc++;
                            }
                        });
                        if(errc===0){
                            rfid_status(sp_id[0],code.data,"cam",function(d,type){
                                // console.log(d);
                                if(d){
                                    $("#"+id).val(code.data);
                                    $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").removeClass("toggle-color");
                                    $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").css({"color":"blue"});
                                    // rfid_check();
                                    // printIt(id,code);
                                    $("#alert").dialog( "close" );
                                    setTimeout(() => {
                                        goContinue();
                                    }, 200);
                                }else{
                                    alert("スキャンしたRFIDはDASで利用中です。");
                                    return;
                                }
                            });
                        }else{
                            var msg="RFIDは[<b style='color:blue;'>#"+multiple_id+"</b>]で利用中です。";
                            var options = {"title":"確認してください！！！",
                                width: 600,
                                buttons: 
                                    [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                        $( this ).dialog( "close" );
                                        return;
                                    }}]
                            };
                            $("#message").html(msg);
                            $( "#alert" ).dialog( "option",options);
                            $( "#alert" ).dialog( "open" );
                        }
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
        $(".toggle-color").removeClass("toggle-color");
        $("#QRScan").dialog( "close" );
    }

    function check_running(posi){
        var sp_id=posi.split("_");
        if($("#usercav_"+sp_id[0]+"_"+sp_id[2]).val()!=""){
            let c_flag = false;
            open_diff_floor(posi,true);
            sum_all(sp_id[0]);
        }else{
            open_diff_floor(posi,false);
        }
    }

    function open_diff_floor(posi,fix){
        if(fix==null){
            fix=false;
        }
        var sp_id=posi.split("_");
        // $('.dan_'+sp_id[0]+'_'+sp_id[2]).removeClass("un_mold_unit");
        let itemlist=$("#item_list").val().split(",");
        $.each(itemlist,function(key,item){
            $('.dan_'+item+'_'+sp_id[2]).removeClass("un_mold_unit");
        })
        // $('.dan_'+sp_id[0]+'_'+sp_id[2]).addClass("now-run-click");
        var workitem_name = $('input[name=work_process]:checked').val()
        if(workitem_name=="-"){
            var msg="工程を選択してください。";
            var options = {"title":"確認してください！！！",
                width: 600,
                buttons: 
                    [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
            return;
        }
        let dw = $(window).width()-20;
        var options = {
            "title":sp_id[0]+"　#"+sp_id[1]+"　"+sp_id[2]+"段目　　不良カウンター",
            position:["center",175],
            width: dw,
            buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                $("#diff_item").html("");
                $("#dialog").dialog( "close" );
                if(fix){
                    setTimeout(() => {
                        children_Entry("usercav_"+sp_id[0]+"_"+sp_id[2],'children_Update');
                    }, 0);
                }
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };

        $("#dialog").dialog( "option",options);
        $("#dialog").dialog( "open" );

        now_data = [];
    
        var sum = $("#sum_"+posi).val();
        let num = 0;
        let tray_num = $("#"+sp_id[0]+"_traynum").val();
        let f_item='';

        if(tray_num!=0){
            f_num = tray_num - sum;
        }else{
            f_num = 0;
        }
        sum = $("#sum_"+posi).val();
        f_num=0;
        tray_num = parseInt($("#"+sp_id[0]+"_traynum").val());
        let round_num = parseInt($("#"+sp_id[0]+"_"+sp_id[2]+"_traynum").val())
        $.each(s,function(key,item){
            let spk = key.split("_");
            if(key.indexOf(posi)!==-1 && spk[0] != "hrutid" && spk[0] != "sum" && spk[0] != "usercav" && spk[0] != "traytime"){
                f_num+= parseInt(item);
            }
        });
        if(mode=="cminus"){
            sum=round_num-f_num;
        }else{
            round_num+=f_num;
        }

        f_item+='<div style="display:block;margin-bottom:10px;">';

        f_item+='<lable >生産数：</lable><input id ="生産数_'+posi+'" readonly="readonly" class="receive_num" name="'+sp_id[0]+']['+sp_id[1]+']['+sp_id[2]+'][生産数" value="'+(round_num)+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >良品数：</lable><input id ="良品数_'+posi+'" readonly="readonly" class="receive_num" name="'+sp_id[0]+']['+sp_id[1]+']['+sp_id[2]+'][良品数" value="'+sum+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >不良数：</lable><input id ="不良数_'+posi+'" readonly="readonly" class="receive_num" name="'+sp_id[0]+']['+sp_id[1]+']['+sp_id[2]+'][不良数" value="'+f_num+'" style="color:#000;width:100px;" />';  
        f_item+='<button type="button" value="'+sp_id[0]+'" onclick="defactitemadd(this.value);" class="btn_ditemset" style="float:right;padding:0.1em 1em">不良設定</button></div>';
        f_item+='<input type="hidden" id="posi" value="'+posi+'" />';
        // sum_STAN0030-00_キャビ分けなし_2
        let ditem_list = $("#bad_list_"+sp_id[0]).val();
        if(ditem_list==""){
            get_bad_list(sp_id[0]);
            return;
        }
        var sp_ditem = ditem_list.split(",");
        //$("#tab_ditem_"+kind).html("");
        $.each(sp_ditem,function(key,val){
            // deff][品目コード][def][段][defname
            var k = val+'_'+posi;
            var n = "def]["+sp_id[0]+"]["+sp_id[1]+"]["+sp_id[2]+"]["+val;
            var ip_class = "";
            var num = 0;
            if (now_data[k]){
                num = now_data[k];
            }
            f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
            f_item+='<p class="b_line">'+val+'</p>';
            f_item+='<p class="btn" onclick="c_up(`'+val+'`,`'+posi+'`)">+</p>';
            f_item+='<p class="btn" onclick="c_dw(`'+val+'`,`'+posi+'`)">-</p>';
            f_item+='<input type="text" name="'+n+'" value="'+num+'" class="n_input '+ip_class+'" readonly="readonly" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/>';
            f_item+='</div>';
            //console.log(f_item);
        });
        $("#diff_item").html(f_item);

        // $("#tab_ditem_"+form_num).append("<div style='clear:both;'></div>");
        $(".btn").button();
        $(".btn_ditemset").button();
        $(".n_input").on("click",function(e){
            open_num_key(e.target.id);
        });
        $(".box input").focusin(function(e){
            // $(this).css({'background-color':'#ccc'});
            if(parseInt($(this).val())>0 && $(this).attr('name').indexOf('def') != -1){
                $(this).css({'color':'#ff0000'});
            }else{
                $(this).css({'color':'#000'});
            }
        })
        
        $(".box input").focusout(function(e) {
            // $(this).css({'background-color':'','color':''});
            if(parseInt($(this).val())>0){
                $(this).css({'color':'#000'});
            }else{
                $(this).css({'color':''});
            }
        });
        $(".btn").removeClass("ui-resizable");
        set_diff();
        setTimeout(() => {
            sum_ditem(posi);
            sum_all(sp_id[0]);
        }, 0);
        $("#sum_"+posi).css({"background-color": "#75c100;"});
    }

    function open_sample_count(posi,fix){
        if(fix==null){
            fix=false;
        }
        var sp_id=posi.split("_");
        // $('.dan_'+sp_id[0]+'_'+sp_id[2]).removeClass("un_mold_unit");
        let itemlist=$("#item_list").val().split(",");
        $.each(itemlist,function(key,item){
            $('.dan_'+item+'_'+sp_id[2]).removeClass("un_mold_unit");
        })
        // $('.dan_'+sp_id[0]+'_'+sp_id[2]).addClass("now-run-click");
        var workitem_name = $('input[name=work_process]:checked').val()
        if(workitem_name=="-"){
            var msg="工程を選択してください。";
            var options = {"title":"確認してください！！！",
                width: 600,
                buttons: 
                    [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $( "#alert" ).dialog( "open" );
            return;
        }
        let dw = $(window).width()-20;
        var options = {
            "title":sp_id[0]+"　#"+sp_id[1]+"　サンプル取り　　不良カウンター",
            position:["center",175],
            width: dw,
            buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                $("#diff_item").html("");
                $("#dialog").dialog( "close" );
                if(fix){
                    setTimeout(() => {
                        children_Entry("usercav_"+sp_id[0]+"_"+sp_id[2],'children_Update');
                    }, 0);
                }
            }}],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };

        $("#dialog").dialog( "option",options);
        $("#dialog").dialog( "open" );

        now_data = [];
        let sum = $("#sum_"+posi).val();
    
        let f_item='';

        f_item+='<div style="display:block;margin-bottom:10px;">';

        f_item+='<lable >生産数：</lable><input id ="生産数_'+posi+'" readonly="readonly" class="receive_num" name="'+sp_id[0]+']['+sp_id[1]+']['+sp_id[2]+'][生産数" value="'+sum+'" style="color:#000;width:100px;" />';  
        f_item+='<lable >良品数：</lable><input id ="良品数_'+posi+'" readonly="readonly" class="receive_num" name="'+sp_id[0]+']['+sp_id[1]+']['+sp_id[2]+'][良品数" value="0" style="color:#000;width:100px;" />';  
        f_item+='<lable >不良数：</lable><input id ="不良数_'+posi+'" readonly="readonly" class="receive_num" name="'+sp_id[0]+']['+sp_id[1]+']['+sp_id[2]+'][不良数" value="'+sum+'" style="color:#000;width:100px;" />';  
        f_item+='<button type="button" value="'+sp_id[0]+'" onclick="defactitemadd(this.value);" class="btn_ditemset" style="float:right;padding:0.1em 1em">不良設定</button></div>';
        f_item+='<input type="hidden" id="posi" value="'+posi+'" />';
        // sum_STAN0030-00_キャビ分けなし_2
        let ditem_list = $("#bad_list_"+sp_id[0]).val();
        if(ditem_list==""){
            get_bad_list(sp_id[0]);
            return;
        }
        var sp_ditem = ditem_list.split(",");
        //$("#tab_ditem_"+kind).html("");
        $.each(sp_ditem,function(key,val){
            // deff][品目コード][def][段][defname
            var k = val+'_'+posi;
            var n = "def]["+sp_id[0]+"]["+sp_id[1]+"]["+sp_id[2]+"]["+val;
            var ip_class = "";
            var num = 0;
            if (now_data[k]){
                num = now_data[k];
            }
            f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
            f_item+='<p class="b_line">'+val+'</p>';
            f_item+='<p class="btn" onclick="c_up_sample(`'+val+'`,`'+posi+'`)">+</p>';
            f_item+='<p class="btn" onclick="c_dw_sample(`'+val+'`,`'+posi+'`)">-</p>';
            f_item+='<input type="text" name="'+n+'" value="'+num+'" class="n_input '+ip_class+' sample_input" readonly="readonly" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/>';
            f_item+='</div>';
        });
        $("#diff_item").html(f_item);

        // $("#tab_ditem_"+form_num).append("<div style='clear:both;'></div>");
        $(".btn").button();
        $(".btn_ditemset").button();
        $(".n_input").on("click",function(e){
            open_num_key(e.target.id);
        });
        $(".box input").focusin(function(e){
            // $(this).css({'background-color':'#ccc'});
            if(parseInt($(this).val())>0 && $(this).attr('name').indexOf('def') != -1){
                $(this).css({'color':'#ff0000'});
            }else{
                $(this).css({'color':'#000'});
            }
        })
        
        $(".box input").focusout(function(e) {
            // $(this).css({'background-color':'','color':''});
            if(parseInt($(this).val())>0){
                $(this).css({'color':'#000'});
            }else{
                $(this).css({'color':''});
            }
        });
        $(".btn").removeClass("ui-resizable");
        set_diff();
        setTimeout(() => {
            sum_ditem(posi);
            sum_all(sp_id[0]);
        }, 0);
        $("#sum_"+posi).css({"background-color": "orange"});
    }

    function update_bad_list(){
        let list_code = $("#item_list").val().split(",");
        $.each(list_code,function(k,v){
            get_bad_list(v);
        });
    }

    function get_bad_list(itemcode){
        let num = 0;
        let process = $('input[name=work_process]:checked').val()
        $.ajax({
            type: 'GET',
            url: "/LaborReport/BadItemList",
            dataType: 'html',
            data:{num:num,
                itemcord:itemcode,
                plant:encodeURIComponent($("#plant").val()),
                workitem:encodeURIComponent(process),
                'ac':'Count'
            },
            success: function(d) {
                let sp_ditem_check = new Set(d.split(","));
                let new_bab_list = [...sp_ditem_check];
                $("#bad_list_"+itemcode).val(new_bab_list);
            }
        });
    }

    function set_diff(){
        $.each(s,function(key,item){
            $("#"+key).val(item);
            if($("#"+key).attr('name')){
                if($("#"+key).attr('name').indexOf('def][')>-1 && item > 0){
                    $("#"+key).css({"color":"#000"})
                }
            }
        });
    }

    function defactitemadd(item){
        let process = $('input[name=work_process]:checked').val()
        $.ajax({
            type: 'GET',
            url: "/LaborReport/Ditemset?num=0",
            data:{
                itemcode:item,
                workitem: encodeURIComponent(process),
                plant_id: $("#plantid").val()
            },
            dataType: 'html',
            success: function(d) {
                let ww = $(window).width();
                var options = {"title":"追加する項目にチェックを入れて「項目を反映」ボタンを実行して下さい。",
                    position:["center",50],
                    width:ww-20,
                    buttons: [{ text:"閉じる",class:'btn-confirm',click :function(ev) {
                        $( this ).dialog( "close" )
                    }}],
                };
                var msg=d;
                $("#alert").css({"padding":"10px"});
                $("#message").html(msg);
                $("#btn_entrydefactiv").button();
                $( "#alert" ).dialog( "option",options);
                $("#alert").dialog( "open" );
            }
        });
    }

    function entry_ditem(){
        itemcode = $("#ui-id-1").html();
        itemcode = itemcode.split("　");
        let process = $('input[name=work_process]:checked').val()
        var check = $('[class="dedit_item"]:checked').map(function(){
              return $(this).val();
        }).get();
        $.ajax({
            type: 'GET',
			url: "/LaborReport/Ditemset?ac=Entry",
            dataType: 'text',
            data:{
                itemcode:itemcode[0],
                workitem: encodeURIComponent(process),
                inidata:check
            },
            success: function(d) {
                let num = 0;
                $.ajax({
                    type: 'GET',
                    url: "/LaborReport/BadItemList",
                    dataType: 'html',
                    data:{num:num,
                        itemcord:itemcode[0],
                        workitem:encodeURIComponent(process),
                        'ac':'Count'
                    },
                    success: function(d2) {
                        $("#bad_list_"+itemcode[0]).val(d2);
                        posi = $("#posi").val();
                        $("#alert").dialog( "close" );
                        open_diff_floor(posi);
                    }
                });
         
            }
        });
    }

    function c_up(dname,posi){
        let val=parseInt($("#"+dname+"_"+posi).val());
        let sp_id=posi.split("_");
        let itemcode = sp_id[0];
        if(parseInt($("#sum_"+posi).val()) > 0){
            if(mode=="cminus"){
                $("#"+dname+"_"+posi).val(val+1);
                let gnum= parseInt($("#良品数_"+posi).val())-1;
                let dnum= parseInt($("#不良数_"+posi).val())+1;
                $("#良品数_"+posi).val(gnum);
                // $("#"+sp_id[0]+"_"+sp_id[2]+"_good_num").val(gnum);
                $("#不良数_"+posi).val(dnum);
                // $("#"+sp_id[0]+"_"+sp_id[2]+"_bad_num").val(dnum);
                $("#sum_"+posi).val(gnum);
            }else{
                $("#"+dname+"_"+posi).val(val+1);
                let mnum= parseInt(parseInt($("#生産数_"+posi).val())+1);
                let dnum= parseInt($("#不良数_"+posi).val())+1;
                $("#生産数_"+posi).val(mnum);
                $("#不良数_"+posi).val(dnum);
            }
            sum_ditem(posi);
            sum_all(itemcode);
        }
    }

    function c_dw(dname,posi){
        let val=parseInt($("#"+dname+"_"+posi).val());
        let sp_id=posi.split("_");
        let itemcode = sp_id[0];
        if(parseInt($("#sum_"+posi).val()) > 0){
            val=val-1;
            if(val==-1){
                return;
            }else{
                if(mode=="cminus"){
                    $("#"+dname+"_"+posi).val(val);
                    let gnum= parseInt($("#良品数_"+posi).val())+1;
                    let dnum= parseInt($("#不良数_"+posi).val())-1;
                    $("#良品数_"+posi).val(gnum);
                    // $("#"+sp_id[0]+"_"+sp_id[2]+"_good_num").val(gnum);
                    $("#不良数_"+posi).val(dnum);
                    // $("#"+sp_id[0]+"_"+sp_id[2]+"_bad_num").val(dnum);
                    $("#sum_"+posi).val(gnum);
                    $("#"+posi).val(val);
                }else{
                    $("#"+dname+"_"+posi).val(val);
                    let mnum= parseInt($("#生産数_"+posi).val())-1;
                    let dnum= parseInt($("#不良数_"+posi).val())-1;
                    $("#生産数_"+posi).val(mnum);
                    // $("#"+sp_id[0]+"_"+sp_id[2]+"_good_num").val(gnum);
                    $("#不良数_"+posi).val(dnum);
                    // $("#"+sp_id[0]+"_"+sp_id[2]+"_bad_num").val(dnum);
                    // $("#sum_"+posi).val(mnum);
                }
                sum_ditem(posi);
                sum_all(itemcode);
            }
        }

    }

    function c_up_sample(dname,posi){
        let val=parseInt($("#"+dname+"_"+posi).val());
        let sp_id=posi.split("_");
        let itemcode = sp_id[0];
        $("#"+dname+"_"+posi).val(val+1);
        let mnum= parseInt(parseInt($("#生産数_"+posi).val())+1);
        let dnum= parseInt($("#不良数_"+posi).val())+1;
        $("#生産数_"+posi).val(mnum);
        $("#不良数_"+posi).val(dnum);
        $("#sum_"+posi).val(mnum);

        sum_ditem(posi);
        sum_all(itemcode);
    }

    function c_dw_sample(dname,posi){
        let val=parseInt($("#"+dname+"_"+posi).val());
        let sp_id=posi.split("_");
        let itemcode = sp_id[0];
        if(parseInt($("#sum_"+posi).val()) > 0){
            val=val-1;
            if(val==-1){
                return;
            }else{
                $("#"+dname+"_"+posi).val(val);
                let mnum= parseInt($("#生産数_"+posi).val())-1;
                let dnum= parseInt($("#不良数_"+posi).val())-1;
                $("#生産数_"+posi).val(mnum);
                // $("#"+sp_id[0]+"_"+sp_id[2]+"_good_num").val(gnum);
                $("#不良数_"+posi).val(dnum);
                // $("#"+sp_id[0]+"_"+sp_id[2]+"_bad_num").val(dnum);
                $("#sum_"+posi).val(mnum);
                sum_ditem(posi);
                sum_all(itemcode);
            }
        }
    }

    var val = 0;
    
    function sum_ditem(id){
        var sp_id = id.split("_");
        d = $(".n_input");
        var num = 0;
        let cav_content="";
        $.each(d, function(k,item){
            if(item.name.indexOf("def][") != -1){
                s[item.id]=item.value;
                let sp_name=item.id.split("_")
                if(parseInt(item.value)>0){
                    cav_content=sp_name[0]+"=>"+item.value;
                    post_data[item.name]=item.value;
                    d_unit[item.name]=item.value;
                }else{
                    cav_content=cav_content+","+sp_name[0]+"=>"+item.value;
                }
            }
        });
    }

    function sum_defective(kind=null){

        if (kind==null){ return false; }
        var kind = kind;
        if ( kind.indexOf('_') != -1 ) {
            fr = kind.split("_")[0];
            kind = kind.split("_").slice(-1)[0]; 
        }
        var line = $("#tab_ditem_"+kind+" input");
        var num = 0;
        var ditem ="";
        $.each(line,function(key,val){
            if(val.value>0){
                num += parseInt(val.value);
                ditem += val.id+"=>"+val.value+",";
            }
        });
        $("#廃棄数_"+kind).val(num);
        //良品数の算出

        if( $("#受入数").val()!="" && $("#受入数").val() !="0" ){
            num = num - parseInt($("#員数ミス_"+kind).val());
            tab_num = parseInt($("#受入数_"+kind).val());
            var gnum = tab_num - num;
            $("#完成数_"+kind).val(gnum);
        }
    }

    function sum_all(code,cav){
        let d = $("input");
        // return;
        let list_code = [];
        if(!$.isArray(code)){
            list_code.push(code);
        }else{
            list_code=code;
        }
        // post_data={};
        $.each(list_code,function(key,item_code){
            tray_num = $("#"+item_code+"_traynum").val();
            if(tray_num==""){
                tray_num=0;
            }
            tray_stock = $("#"+item_code+"_traystock").val();
            if(tray_stock==""){
                tray_stock=0;     
            }

            let cavs = $("#"+item_code+"_cavs").val();
            if($('input[name=cav_'+item_code+']:checked').val()=="なし"){
                cavs="キャビ分けなし";
            }
            let sp_cavs = cavs.split(",");
            let cav_num = sp_cavs.length;

            let val = 0;
            let now_val = 0;
            let now_dnum = 0;
            let out_defec_allnum =0;
            if(mode=="cminus"){
                let now_tray_stock=0;
                $.each(sp_cavs, function(kc,vc){
                    // tray_stock+=1;
                    let dan_num = 0;
                    let cav_val = 0;
                    let cav_dnum = 0;
                    let out_defec_cavnum =0;
                    $.each(d, function(k,item){
                        if(item.name.indexOf("基本][") != -1 && item.value != "0" && item.value != ""){
                            post_data[item.name]=item.value;
                        }
                        if(item.name.indexOf("tuser]["+item_code) != -1 && item.name.indexOf("sample") == -1 && item.value != "0" && item.classList.value!="" && item.classList.value.indexOf('un_mold_unit') == -1){
                            dan_num++;
                            now_val = parseInt(item.value) + parseInt(now_val);
                        }
                        // if(item.value != "0" && item.value != "" && item.classList.value!="" && item.classList.value.indexOf('un_mold_unit') == -1){
                        if(item.value != "" && item.classList.value!="" && item.classList.value.indexOf('un_mold_unit') == -1){
                            
                            // if(item.name.indexOf("sum]["+item_code) != -1){
                            //     if(parseInt(item.value) > 0){
                            //         val = parseInt(item.value) + parseInt(val);
                            //     }
                            // }
                    
                            if(item.name.indexOf("sum]["+item_code+"]["+vc+"][") != -1 && item.name.indexOf("sum]["+item_code+"]["+vc+"][sample") == -1){
                                s[item.id]=item.value;
                                if(parseInt(item.value) >= 0){
                                    let this_dan = item.name.split("][")[3];
                                    cav_val = parseInt(item.value) + parseInt(cav_val);
                                    val = parseInt(item.value) + parseInt(val);
                                    let now_tray_num = parseInt($("#"+item_code+"_"+this_dan+"_traynum").val());
                                    cav_dnum += parseInt(tray_num)-now_tray_num;
                                    now_dnum += parseInt(tray_num)-now_tray_num;
                                }
                            }else if(item.name.indexOf("sum]["+item_code+"]["+vc+"][sample") != -1){
                                out_defec_cavnum+=parseInt(item.value);
                                out_defec_allnum+=parseInt(item.value);
                            }
                            if(item.value != "0"){
                                post_data[item.name]=item.value;
                            }
                        }else if(item.name.indexOf("sum]["+item_code+"]["+vc+"][") != -1 ){
                            s[item.id]=item.value;
                        }
                    });
                    if(dan_num==0){
                        dan_num=1;  
                    }
                    now_tray_stock=dan_num;

                    $("#"+item_code+"_"+vc+"_mold_num").val(parseInt(tray_num)*parseInt(now_tray_stock)-cav_dnum+out_defec_cavnum);
                    $("#"+item_code+"_"+vc+"_good_num").val(cav_val);
                    $("#"+item_code+"_"+vc+"_bad_num").val(parseInt(tray_num)*parseInt(now_tray_stock)-cav_val-cav_dnum+out_defec_cavnum);
                });
                let all_mold_num = parseInt(tray_num)*parseInt(now_tray_stock)*cav_num-now_dnum+out_defec_allnum;
                $("#all_molding_"+item_code).val(all_mold_num);
                $("#lable_all_mold_"+item_code).html("/"+(parseInt(tray_num)*parseInt(tray_stock)*cav_num-now_dnum+out_defec_allnum));
                $("#all_good_"+item_code).val(parseInt(val));
                $("#all_bad_"+item_code).val(all_mold_num-parseInt(val));
            }else{
                let f_all_num =0;
                $.each(sp_cavs, function(kc,vc){
                    let dan_num = 0;
                    let cav_val = 0;
                    let f_num=0; 
                    let cav_dnum = 0;
                    let out_defec_cavnum =0;
                    $.each(d, function(k,item){
                        if(item.value != "0" && item.value != ""){
                            post_data[item.name]=item.value;
                        }
                        if(item.name.indexOf("tuser]["+item_code) != -1 && item.name.indexOf("sample") == -1 && item.value != "0" && item.classList.value!="" && item.classList.value.indexOf('un_mold_unit') == -1){
                            dan_num++;
                            now_val = parseInt(item.value) + parseInt(now_val);
                        }
                        if(item.value != "0" && item.value != "" && item.classList.value!="" && item.classList.value.indexOf('un_mold_unit') == -1){
                            if(item.name.indexOf("sum]["+item_code+"]["+vc+"][") != -1 && item.name.indexOf("sum]["+item_code+"]["+vc+"][sample") == -1){
                                if(parseInt(item.value) > 0){
                                    cav_val = parseInt(item.value) + parseInt(cav_val);
                                    val = parseInt(item.value) + parseInt(val);
                                    let this_dan = item.name.split("][")[3];
                                    let now_tray_num = parseInt($("#"+item_code+"_"+this_dan+"_traynum").val());
                                    cav_dnum += parseInt(tray_num)-now_tray_num;
                                    now_dnum += parseInt(tray_num)-now_tray_num;
                                }
                            }
                        }
                    });
                    $.each(s,function(key,item){
                        let spk = key.split("_");
                        if(key.indexOf(item_code+"_"+vc)!==-1 && parseInt(item)>0 && spk[0] != "hrutid" && spk[0] != "sum" && spk[0] != "usercav" && spk[0] != "traytime") {
                            f_num= parseInt(item) + f_num;
                            f_all_num += parseInt(item);
                        }
                    });
             
                    if(dan_num==0){
                        dan_num=1;  
                    }
                    now_tray_stock=dan_num;
                    $("#"+item_code+"_"+vc+"_mold_num").val(parseInt(tray_num)*parseInt(now_tray_stock)+f_num-cav_dnum);
                    $("#"+item_code+"_"+vc+"_good_num").val(cav_val-cav_dnum);
                    $("#"+item_code+"_"+vc+"_bad_num").val(f_num);
                });
                $("#all_molding_"+item_code).val(parseInt(tray_num)*parseInt(now_tray_stock)*cav_num+f_all_num-now_dnum);
                $("#lable_all_mold_"+item_code).html("/"+parseInt(parseInt(tray_num)*parseInt(now_tray_stock)*cav_num+f_all_num-now_dnum));
                $("#all_good_"+item_code).val(parseInt(tray_num)*parseInt(now_tray_stock)*cav_num-now_dnum);
                $("#all_bad_"+item_code).val(parseInt(f_all_num));
            }
        });
        //キャビの数のチェック
        setTimeout(() => {
            let check_cav_gnum = $(".check_cav_gnum")
            $.each(check_cav_gnum, function (k, v) { 
                let sp_this_id = v.id.split("_");
                if(parseInt(v.value)==0){
                    $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"gray"});
                    $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_rfid").val("");
                }else{
                    if($("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_rfid").val()==""){
                        $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"darkorange"});
                    }else{
                        $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"blue"});
                    }
                }
            }); 
        }, 0);
    }

    function sample_hide_view(){
        if(sample_view){
            sample_view=false;
            $(".sample_input").val(0);
            $(".plus_sample").hide();
            let list_code = $("#item_list").val().split(",");
            sum_all(list_code);
            let sc = s;
            $.each(sc, function (a, b) { 
                if(a.indexOf("_sample")!=-1){
                    delete s[a]; 
                }
            });
            $(".sample_input").css({"background-color":"white"})
        }else{
            sample_view=true; 
            $(".plus_sample").show();
        }
    }

    function sum_running(){
        // let inserted_data = $(".inserted-click");
        // console.log(inserted_data);
        let d = $("input,textarea");
        let list_code = $("#item_list").val().split(",");
        // return;
        post_data={};
     
        $.each(list_code,function(key,item_code){
            let cavs = $("#"+item_code+"_cavs").val();
            let sp_cavs = cavs.split(",");
            let cav_num = sp_cavs.length;
            let cav_val=0;
            $.each(sp_cavs, function(kc,vc){
                let val = 0;
                cav_val=0;
                let dan_num = 0;
                $.each(d, function(k,item){
                    if(item.name.indexOf("基本][") != -1 && item.value != "0" && item.value != ""){
                        post_data[item.name]=item.value;
                    }
                    if(item.name.indexOf("tuser]["+item_code) != -1 && item.value != "0" && item.value != ""){
                        dan_num++;
                    }
                    if(item.value != "0" && item.value != "" && item.classList.value!="" && item.classList.value.indexOf('un_mold_unit') == -1){
                        
                        if(item.name.indexOf("sum]["+item_code) != -1){
                            if(parseInt(item.value) > 0){
                                val = parseInt(item.value) + parseInt(val);
                            }
                        }
                  
                        if(item.name.indexOf("sum]["+item_code+"]["+vc+"][") != -1){
                            if(parseInt(item.value) > 0){
                                cav_val = parseInt(item.value) + parseInt(cav_val);
                            }
                        }
                        post_data[item.name]=item.value;
                    }
                });
                tray_stock=dan_num+1;
                $("#"+item_code+"_"+vc+"_mold_num").val(parseInt(tray_num)*parseInt(tray_stock));
                $("#"+item_code+"_"+vc+"_good_num").val(cav_val);
                $("#"+item_code+"_"+vc+"_bad_num").val(parseInt(tray_num)*parseInt(tray_stock)-cav_val);
                $("#all_molding_"+item_code).val(parseInt(tray_num)*parseInt(tray_stock)*cav_num);
                $("#all_good_"+item_code).val(parseInt(val));
                $("#all_bad_"+item_code).val(parseInt($("#all_molding_"+item_code).val())-parseInt(val));
            });
        });
        // console.log(post_data);
    }


    function check_EntryAuto(){
        return new Promise((cb) => {
            let msg ="<b><span style='color:red;'>未入力</span>の項目を入力してください。！</b>\n"; 
            let msg_nr ="<b><span style='color:red;'>未入力</span>の項目を入力してください。！</b>\n"; 
            var erc=0;
            var sae=0;
            var now_run_err = 0;
            let set_end_time = "";
            let set_end_user = "";

            let erc0=0;
            let c= {};
            c["作業工程"]=$('input[name=work_process]:checked').val();
            c["打数"]=$('#打数').val();
            let msg0 ="<b><span style='color:red;'>未入力</span>の項目を入力してください。！</b>\n"; 
            $.each(c,function(key) {
                if($.trim(c[key])==""){
                    $("#"+key).css({"background-color":"red"});
                    msg0 +="<li style='color:red;'>"+key+"</li>\n";
                    erc0++;
                }
            });
            if(erc0!==0){
                var options = {"title":"確認",
                    width:400,
                    position:["center",170],
                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                        $( this ).dialog( "close" );
                        // focus_control();
                    }}]
                };
                $("#message").html(msg0);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                return;
            }

            let itemlist=$("#item_list").val().split(",");
            $.each(itemlist,function(key,item){
                let check_user =$(".item_"+item);
                $.each(check_user,function(k,v) {
                    let sp = v.id.split("_");
                    if(k==0 && v.classList.value.indexOf("un_mold_unit")>-1){
                        msg+="<li style='color:red;'>担当者-束"+sp[2]+"</li>\n";
                        $("#"+v.id).css({"background-color":"red"});
                        erc++;
                    }else if(v.classList.value.indexOf("un_mold_unit")==-1 && v.classList.value.indexOf("now-run-click")>-1 && v.value==""){
                        msg_nr+="<li style='color:red;'>担当者-束"+sp[2]+"</li>\n";
                        $("#"+v.id).css({"background-color":"red"});
                        now_run_err++
                    }else if(v.classList.value.indexOf("inserted-click")>-1){
                        stop_msg ="<span style='color:red;'>途中</span>で作業を終わらせて<span style='color:red;'>実績登録</span>してもよろしでしょうか？";
                        sae++;
                        let sp_id=v.id.split("_");
                        set_end_user=v.value;
                        set_end_time=$("#traytime_"+sp_id[1]+"_"+sp_id[2]).val();
                    }
                    // else{
                    //     msg=msg+"<li style='color:red;'>担当者-束"+sp[2]+"</li>\n";
                    //     now_run_err++;
                    // }
                });
            })

            let check_all_user =$(".check_user_cav");

            if(now_run_err==0){
                if(sae>0){
                    if(sae==check_all_user.length){
                        access_entry=true;
                        if($("#終了日時").val()==''){
                            set_end_time=set_end_time.replaceAll("-","/");
                            $("#終了日時").val(set_end_time);
                            // $("#担当者").val(set_end_user);
                            let list_code = $("#item_list").val().split(",");
                            $.each(list_code,function(a,b){
                                sum_all(b);
                                // $('.un_mold_unit').removeClass("now-run-click");
                                $('.un_mold_unit').css({"background-color":"#FFF"});
                            });
                        }
                        if($("#担当者").val()==''){
                            // focus_control();
                            $("#担当者").val(set_end_user);
                        }
                        setTimeout(() => {
                            // $("#entryLabel").html("登録ボタンを押してください→");
                            cb(true); 
                        }, 0);
                    }else{
                        var options = {"title":"確認",
                            width:600,
                            position:["center",170],
                            buttons: [{ text:"はい",class:'btn-confirm',click :function(ev) {
                                access_entry=true;
                                if($("#終了日時").val()==''){
                                    set_end_time=set_end_time.replaceAll("-","/");
                                    $("#終了日時").val(set_end_time);
                                    let list_code = $("#item_list").val().split(",");
                                    $.each(list_code,function(a,b){
                                        sum_all(b);
                                        // $('.un_mold_unit').removeClass("now-run-click");
                                        $('.un_mold_unit').css({"background-color":"#FFF"});
                                    });
                                }
                                if($("#担当者").val()==''){
                                    // focus_control();
                                    $("#担当者").val(set_end_user);
                                }
                                setTimeout(() => {
                                    cb(true); 
                                }, 0);
                   
                            }},{ text:"いいえ",class:'btn-cancel',click :function(ev) {
                                $("#alert").dialog( "close" );
                                access_entry=false;
                                cb(false);
                            }}]
                        };
                        $("#message").html(stop_msg);
                        $( "#alert" ).dialog( "option",options);
                        $( "#alert" ).dialog( "open" );
                    }
                }else{
                    var options = {"title":"確認",
                        width:400,
                        position:["center",170],
                        buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                            $( this ).dialog( "close" );
                            // focus_control();
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $( "#alert" ).dialog( "open" );
                    cb(false);
                }
            }else{
                var options = {"title":"未登録のデータを確認してください。",
                    width:400,
                    position:["center",170],
                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                        $( "#alert" ).dialog( "close" );
                    }}]
                };
                $("#message").html(msg_nr);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                cb(false);
            }
        });
    }

    function check_Entry(){
        return new Promise((cb) => {
            let c= {}
            c["開始日時"]=$("#開始日時").val();
            c["終了日時"]=$("#終了日時").val();
            c["担当者"]=$("#担当者").val();
            // c["打数"]=$("#打数").val();
            c["作業工程"]=$('input[name=work_process]:checked').val();
            let msg ="<b><span style='color:red;'>未入力</span>の項目を入力してください。！</b>\n"; 
            let msg_nr ="<b><span style='color:red;'>未入力</span>の項目を入力してください。！</b>\n"; 
            var erc=0;
            var sae=0;
            var now_run_err = 0;
            $.each(c,function(key) {
                if($.trim(c[key])==""){
                    $("#"+key).css({"background-color":"red"});
                    msg +="<li style='color:red;'>"+key+"</li>\n";
                    erc++;
                }
            });
            let itemlist=$("#item_list").val().split(",");
            $.each(itemlist,function(key,item){
                let check_user =$(".item_"+item);
                $.each(check_user,function(k,v) {
                    let sp = v.id.split("_");
                    if(k==0 && v.classList.value.indexOf("un_mold_unit")>-1){
                        msg+="<li style='color:red;'>担当者-束"+sp[2]+"</li>\n";
                        $("#"+v.id).css({"background-color":"red"});
                        erc++;
                    }else if(v.classList.value.indexOf("un_mold_unit")==-1 && v.classList.value.indexOf("now-run-click")>-1 && v.value==""){
                        msg_nr+="<li style='color:red;'>担当者-束"+sp[2]+"</li>\n";
                        $("#"+v.id).css({"background-color":"red"});
                        now_run_err++
                    }else if(v.classList.value.indexOf("inserted-click")>-1){
                        stop_msg ="<span style='color:red;'>途中</span>で作業を終わらせて<span style='color:red;'>実績登録</span>してもよろしでしょうか？";
                        sae++;
                    }
                    // else{
                    //     msg=msg+"<li style='color:red;'>担当者-束"+sp[2]+"</li>\n";
                    //     now_run_err++;
                    // }
                });
            })

            let check_all_user =$(".check_user_cav");
            if(erc!==0){
                var options = {"title":"確認",
                    width:400,
                    position:["center",170],
                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                        $( this ).dialog( "close" );
                        focus_control();
                    }}]
                };
                $("#message").html(msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                cb(false);
            }else if(now_run_err==0){
                if(sae>0){
                    if(sae==check_all_user.length){
                        // $("#entryLabel").html("登録ボタンを押してください→");
                        cb(true);
                    }else{
                        var options = {"title":"確認",
                            width:600,
                            position:["center",170],
                            buttons: [{ text:"はい",class:'btn-confirm',click :function(ev) {
                                access_entry=true;
                                let list_code = $("#item_list").val().split(",");
                                $.each(list_code,function(a,b){
                                    sum_all(b);
                                    // $('.un_mold_unit').removeClass("now-run-click");
                                    $('.un_mold_unit').css({"background-color":"#FFF"});
                                });
                                $("#alert").dialog( "close" );
                                cb(true);
                            }},{ text:"いいえ",class:'btn-cancel',click :function(ev) {
                                $("#alert").dialog( "close" );
                                access_entry=false;
                                cb(false);
                            }}]
                        };
                        $("#message").html(stop_msg);
                        $( "#alert" ).dialog( "option",options);
                        $( "#alert" ).dialog( "open" );
                    }
                }else{
                    var options = {"title":"確認",
                        width:400,
                        position:["center",170],
                        buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                            $( this ).dialog( "close" );
                            focus_control();
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $( "#alert" ).dialog( "open" );
                    cb(false);
                }
            }else{
                var options = {"title":"未登録のデータを確認してください。",
                    width:400,
                    position:["center",170],
                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                        $( "#alert" ).dialog( "close" );
                    }}]
                };
                $("#message").html(msg_nr);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                cb(false);
            }
        });
    }

    function children_Entry(posi,ac){
        // usercav_STAN0035-00_1
        let item_list=$("#item_list").val().split(",");
        let now_k_item = 1;
        let sp = posi.split("_");
        $.each(item_list,function(k,item){
            if(item==sp[1]){
                now_k_item=now_k_item+k; 
            }
        })
        let msg2='';
        let d = $("input,textarea");
        // let type = "children_Entry";
        
        let cavs = $("#"+sp[1]+"_cavs").val().split(",");
        let tray_num = $("#"+sp[1]+"_traynum").val();
        let c_data = {};
        c_data["基本][cavs"]=$("#"+sp[1]+"_cavs").val();
        c_data["基本][itemcode"]=$("#"+sp[1]+"_itemcode").val();
        c_data["基本][pieces"]=$("#"+sp[1]+"_pieces").val();
        c_data["基本][serial"]=$("#"+sp[1]+"_serial").val();
        c_data["基本][tuser"]=$("#"+posi).val();
        let df_time = $("#開始日時").val();
        if(df_time==""){
            $("#開始日時").focus();
        }else{
            if(sp[2]!=1){
                df_time = $("#traytime_"+sp[1]+"_"+(parseInt(sp[2])-1)).val();
            }
        }
        msg2+='<p><b>担当者：<input type="text" value="'+c_data["基本][tuser"]+'" name="" id="tuser_'+sp[1]+'_'+sp[2]+'" readonly="readonly" onclick="searchUserCav(`usercav_'+sp[1]+'_'+sp[2]+'`,`'+ac+'`);" style="width:170px;"/>　';
        msg2+='開始：<input type="text" value="'+df_time+'" name="" id="danstart_'+sp[1]+'_'+sp[2]+'" readonly="readonly" onclick="datetime(this.id);" style="width:170px;"/>　';
        if(ac=="children_Entry"){
            msg2+='終了：<input type="text" value="'+nowDT("dhm")+'" name="" id="danend_'+sp[1]+'_'+sp[2]+'" readonly="readonly" onclick="datetime(this.id);" style="width:170px;"/>　</b></p>';
        }else{
            msg2+='終了：<input type="text" value="'+$('#traytime_'+sp[1]+'_'+sp[2]).val()+'" name="" id="danend_'+sp[1]+'_'+sp[2]+'" readonly="readonly" onclick="datetime(this.id);" style="width:170px;"/>　</b></p>';
        }
        // console.log(d);
        
        msg2+='<table id="th_item" style="font-weight:bold;">';
        msg2+='<tbody><tr>';
        msg2+='<th>キャビ</th><th rowspan="">生産数</th><th rowspan="">良品数</th><th rowspan="">不良数</th><th rowspan="">不良内容</th>';
        msg2+= '</tr></tbody>';
        msg2+='<tbody id ="serial">';
        $.each(cavs,function(kc,vc){
            // "STAN0035-00][11][1"
            cp_key=sp[1]+"]["+vc+"]["+sp[2];
            n_key= vc+"]["+sp[2];
            // if($("#hrutid_"+sp[1]+"_"+vc+"_"+sp[2]).val() != ""){
            //     type = "children_Update";
            // }
            $.each(d, function(k,item){
                if(item.value != "" ){
                    if(item.name.indexOf("基本") > -1 && item.id != "開始日時"){
                        c_data[item.name]=item.value;
                    }else if("sum_"+sp[1]+"_"+vc+"_"+sp[2] == item.id){
                        c_data[cp_key+"][gnum"] = item.value;
                        // if(item.value<tray_num){
                        //     c_data[cp_key+"][bnum"] = tray_num - item.value;
                        // }else{
                        //     c_data[cp_key+"][bnum"] = 0;
                        // }
                    }
                }
            });

            let dtext ="";
            let bnum = 0
            $.each(s,function(ditem,dval){
                sp_ditem=ditem.split("_");
                if(sp_ditem[0]!="sum" && sp_ditem[0]!="traytime" && sp_ditem[0]!="usercav" && sp_ditem[0]!="hrutid" && parseInt(dval)>0){
                    if(sp_ditem[1]==sp[1] && sp_ditem[2]==vc && sp_ditem[3]==sp[2]){
                        dtext+=sp_ditem[0]+"=>"+dval+",";
                        bnum=parseInt(dval)+bnum;
                    }
                }
            });
            dtext=dtext.substr(0,dtext.length-1);
            c_data[cp_key+"][ditem"]=dtext;
            c_data[cp_key+"][bnum"] = bnum;
            c_data[cp_key+"][mnum"] = bnum+parseInt(c_data[cp_key+"][gnum"]);

            // c_data[cp_key+"][etime"]=$("#danend_"+sp[1]+"_"+sp[2]).val();
            c_data[cp_key+"][hrut_id"]=$("#hrutid_"+sp[1]+"_"+vc+"_"+sp[2]).val();

            msg2+= '<tr>';
            msg2+= '<td style="color:blue;">#'+vc+'</td>';
            msg2+= '<td>'+c_data[cp_key+"][mnum"]+'</td>';
            msg2+= '<td>'+c_data[cp_key+"][gnum"]+'</td>';
            msg2+= '<td>'+c_data[cp_key+"][bnum"]+'</td>';
            msg2+= '<td style="font-size:14px;font-weight:normal">'+dtext+'</td>';
            msg2+= '</tr>';
        });
        msg2+='</tbody>';
        msg2+='</table>';
        $("#message").html(msg2);

        let dw = $(window).width()-100;
        let title = "登録してもよろしいですか？";
        let text = "登録";
        if(ac=="children_Update"){
            title="下の内容を更新してもよろしいですか？"
            text = "更新";
        }

        let button_set=[{ text:text,class:'btn-confirm',click :function(ev) {
            c_data["基本][開始"]=$("#danstart_"+sp[1]+"_"+sp[2]).val();
            c_data["基本][終了"]=$("#danend_"+sp[1]+"_"+sp[2]).val();
            loadingView(true);
            $.ajax({
                type: 'POST',
                url: "",
                dataType: 'json',
                data:{
                    ac:ac,
                    c_data:c_data,
                    mode:mode,
                    tray_type:tray_type,
                },
                success: function(d){
                    loadingView(false)
                    $("#alert").dialog( "close" );
                    if(d=="OK"){
                        if(ac=="children_Entry"){
                            $("#終了日時").val($("#danend_"+sp[1]+"_"+sp[2]).val());
                            $("#担当者").val($("#"+posi).val());
                            $(".dan_"+sp[1]+"_"+sp[2]).css({"background-color": "#75c100;"});
                            $(".dan_"+sp[1]+"_"+sp[2]).removeClass("now-run-click");
                            $(".dan_"+sp[1]+"_"+sp[2]).unbind("click");
                            $(".dan_"+sp[1]+"_"+sp[2]).addClass("inserted-click");
                            $('.inserted-click').on('click',function(e){
                                var options = {"title":"確認",
                                    position:["center",170],
                                    width:600,
                                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                                        $( this ).dialog( "close" );
                                        let tid = e.target.id.split("_");
                                        if(tid[0]=="traytime" || tid[0]=="usercav"){
                                            children_Entry('usercav_'+tid[1]+'_'+tid[2],"children_Update");
                                        }else{
                                            // check_running(tid[1]+'_'+tid[2]+'_'+tid[3]);
                                            open_diff_floor(tid[1]+'_'+tid[2]+'_'+tid[3],true)
                                        }
                                    }},{ text:"閉じる",class:'btn-cancel',click :function(ev) {
                                        $( this ).dialog( "close" );
                                    }}],
                                };
                                var msg="保存した不良内容を修正してもよろしでしょうか?";
                                $("#alert").css({"padding":"10px"});
                                $("#message").html(msg);
                                $( "#alert" ).dialog( "option",options);
                                $("#alert").dialog( "open" );

                            });
                            let next_col = parseInt(sp[2])+1;
                            $(".dan_"+sp[1]+"_"+next_col).addClass("now-run-click");
                            // $(".dan_"+sp[1]+"_"+next_col).removeClass("un_mold_unit");
                            $('.now-run-click').on('click',function(e){
                                let tid = e.target.id.split("_");
                                if(tid[0]=="traytime"){
                                    searchUserCav('usercav_'+tid[1]+'_'+tid[2],'children_Entry');
                                }else if(tid[0]=="usercav"){
                                    searchUserCav(e.target.id,'children_Entry');
                                }else{
                                    // check_running(tid[1]+'_'+tid[2]+'_'+tid[3]);
                                    open_diff_floor(tid[1]+'_'+tid[2]+'_'+tid[3],false)
                                }
                            });
                        }else{
                            // location.reload();
                            // let_start();
                        }
                    }else{
                        var options = {"title":"エーラーが生されました。",
                            position:["center",170],
                            width: 900,
                            buttons: [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                                location.reload();
                                let_start(); 
                                return;
                            }}]
                        };
                        let msg="<b>もう一度再登録をしてください。またエーラー発生すればシステムの管理者に連絡してください。</b><br><br>エーラー内容："
                        $("#message").html(msg+d);
                        $( "#alert" ).dialog( "option",options);
                        $( "#alert" ).dialog( "open" );
                    }
                }
            })
        }}]
        if(ac=="children_Entry"){
            button_set.push({ text:"キャンセル",class:'btn-cancel',click :function(ev) {
                if(ac=="children_Entry"){
                    $("#"+posi).val("");
                    $("#traytime_"+sp[1]+"_"+sp[2]).val("");
                }else{
                    // let_start();
                }
                $( this ).dialog( "close" );
            }});
        }
        var options = {"title":title,
            position:["center",170],
            width: dw,
            buttons: button_set,
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        }
        $("#alert").dialog( "option",options);
        $("#alert").dialog( "open" );
    }

    async function entryData(){
        let check = await check_EntryAuto();
        if(check==false){
            return;
        }else{
            let errc=0;
            let ci = $(".check_rfid");
            let er_id = "";
            let rfid_ids = [];
            $.each(ci, function(k,item){
                if(item.value==""){
                    let sp_this_id = item.id.split("_");
                    let this_cav_gnum = $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_good_num").val();
                    if(parseInt(this_cav_gnum)>0){
                        let sp_er_id = item.id.split("_");
                        $("#"+sp_er_id[0]+"_"+sp_er_id[1]+"_cav_view").addClass("toggle-color");
                        if(errc==0){
                            er_id=item.id;
                        }
                        errc++;
                    }else{
                        $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"gray"});
                    }
                }else{
                    rfid_ids.push(item.value);
                }
            });
            if(errc>0){
                let rfc_msg = "RFIDを紐づけてください。";
                var options = {"title":"確認",
                    width:400,
                    position:["center",170],
                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                        $( "#alert" ).dialog( "close" );
                        // openCamScan(er_id,er_id.split("_")[1]);
                    }}]
                };
                $("#message").html(rfc_msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                setTimeout(() => {
                    $( "#alert" ).dialog( "close" );
                    openCamScan(er_id,er_id.split("_")[1]);
                }, 1000);
                return;
            }

            post_data={};
            // console.log(post_data);
            $("#alert").dialog( "close" );
            lc="";
            //日付の確認
            let st = Date.parse($("#開始日時").val().replace(/-/g , "/"));
            let et = Date.parse($("#終了日時").val().replace(/-/g , "/"));
            // 実サイクル ＝ (終了日時 - 開始日時)秒 / 打数
            let time_mold= (et -st) / 1000;
            let true_cycle = (time_mold/parseInt($("#打数").val())).toFixed(2);
            $("#cycle").val(true_cycle);

            let item_list=$("#item_list").val().split(",");
            let d = $("input,textarea");
            $.each(d, function(k,item){
                if(item.value != "0" && item.value != ""){
                    post_data[item.name]=item.value;
                }
            });
            post_data["基本][time_mold"]=time_mold;
            post_data["基本][cycle"]=true_cycle;
            post_data["基本][作業工程"] = $('input[name=work_process]:checked').val();
            post_data["基本][置き場所"] = $('select[name=place] option:selected').val();
            post_data["item_list"]=$("#item_list").val();
            post_data["基本][printer_ip"]=$("#printer_ip").val();

            let msg2='',m_line='',g_line='',d_line='',ditem_line='';
            // msg2+='<p><b>開始：'+post_data["基本][開始"]+'　終了：'+post_data["基本][終了"]+'　時間：'+(time_mold/60)+'分　サイクル：'+true_cycle+'</b></p>';
            msg2+='<p><b>開始：'+post_data["基本][開始"]+'　終了：'+post_data["基本][終了"]+'　時間：'+(time_mold/60)+'分</b></p>';
            msg2+='<table id="th_item" style="font-weight:bold;">';
            msg2+='<tbody><tr>';
            if(item_list.length>1){
                msg2+='<th>製品</th>';
            }
            msg2+='<th style="width:330px;">RFID</th><th>キャビ</th><th rowspan="">生産数</th><th rowspan="">良品数</th><th rowspan="">不良数</th><th rowspan="">不良内容</th>';
            msg2+= '</tr></tbody>';
            msg2+='<tbody id ="serial">';
            $.each(item_list,function(key,item){
                let d_list_sum = {};
                let all_ditem="";
                let item_cavs = $("#"+item+"_cavs").val().split(",");
                let flag;
                $.each(item_cavs, function(k,value) {
                    let cav_ditem = {};
                    let dtext ="";
                    $.each(s,function(ditem,dval){
                        if(parseInt(dval)>0){
                            sp_ditem=ditem.split("_");
                            if(sp_ditem[0] != "sum" && sp_ditem[0] != "traytime" && sp_ditem[0]!="usercav" && sp_ditem[0]!="hrutid"){
                                if(sp_ditem[1]==item && sp_ditem[2]==value){
                                    if(cav_ditem[sp_ditem[0]]){
                                        cav_ditem[sp_ditem[0]]=parseInt(cav_ditem[sp_ditem[0]])+parseInt(dval);
                                    }else{
                                        cav_ditem[sp_ditem[0]]=parseInt(dval);
                                    }
                                    if(d_list_sum[sp_ditem[0]]){
                                        d_list_sum[sp_ditem[0]]=parseInt(d_list_sum[sp_ditem[0]])+parseInt(dval);
                                    }else{
                                        d_list_sum[sp_ditem[0]]=parseInt(dval);
                                    }
                                }
                            }
                        }
                    });
                    $.each(cav_ditem,function(k,v){
                        dtext+=k+"=>"+v+",";
                    });
                    dtext=dtext.substr(0,dtext.length-1);
                    post_data[item+"]["+value+"][tditem"]=dtext;
                    msg2+= '<tr>';
                    if(flag!=key && item_list.length>1){
                        msg2+= '<td rowspan="'+item_cavs.length+'" style="color:blue;">'+$("#"+item+"_itemname").val()+'</td>';
                    }
                    msg2+= '<td>'+$("#"+item+"_"+value+"_rfid").val()+'</td>';
                    msg2+= '<td style="color:blue;">#'+value+'</td>';
                    msg2+= '<td>'+$("#"+item+"_"+value+"_mold_num").val()+'</td>';
                    msg2+= '<td>'+$("#"+item+"_"+value+"_good_num").val()+'</td>';
                    msg2+= '<td>'+$("#"+item+"_"+value+"_bad_num").val()+'</td>';
                    msg2+= '<td style="font-size:14px;font-weight:normal">'+dtext+'</td>';
                    msg2+= '</tr>';
                    flag=key;
                });
                $.each(d_list_sum,function(k,v){
                    all_ditem+=k+"=>"+v+",";
                });
                post_data[item+"][all_ditem"]=all_ditem.substr(0,all_ditem.length-1);

            })
            msg2+='</tbody>';
            msg2+='</table>';
            console.log(post_data);
            // return;
            let dw = $(window).width()-100;
            var options = {"title":"登録してもよろしいですか?",
                position:["center",170],
                width: dw,
                buttons: [{ text:"登録",class:'btn-confirm',click :function(ev) {
                    $( this ).dialog( "close" );
                    let host = window.location.origin;
                    let status_url = new URL(host+"/RFIDReport/RFIDStatusCheck?site=MoldingCounter&plant=<?=$sf_params->get("plant")?>&user="+$("#担当者").val());
                    status_url.searchParams.set("ids", rfid_ids);
                    // window.open(url);
                    // return;
                    loadingView(true);
                    $.ajax({
                        type: 'POST',
                        url: "",
                        dataType: 'json',
                        data:{
                            entry_data:post_data,
                            ac:"data_entry",
                            mode:mode,
                        },
                        success: function(d){
                            loadingView(false);
                            if(d == "OK"){
                                $("#作業時間").val(0);
                                var options = {"title":"登録完了しました。",
                                    width: 400,
                                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                                        $( this ).dialog( "close" );
                                        localStorage.setItem("mc_mode","cminus");
                                        location.reload();
                                    }}],
                                    open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
                                };
                                $("#message").html("登録完了しました。");
                                $( "#alert" ).dialog( "option",options);
                                $("#alert").dialog( "open" );
                            }else{
                                var options = {"title":"登録出来ません。",
                                    width: 400,
                                    buttons: [{ text:"OK",class:'btn-confirm',click :function(ev) {
                                        $( this ).dialog( "close" );
                                    }}]
                                };
                                $("#message").html(d);
                                $( "#alert" ).dialog( "option",options);
                                $("#alert").dialog( "open" );
                            }
                            // setTimeout(() => {
                            //     window.open(status_url, '_blank');
                            // },0);
                        }
                    })
                }},{ text:"キャンセル",class:'btn-cancel',click :function(ev) {
                    $( this ).dialog( "close" )
                }}],
                open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
            };
            $("#message").html(msg2);
            $("#alert").dialog( "option",options);
            $("#alert").dialog( "open" );
        }
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

    function datetime(id){
        let set_title = "【"+id+"】を入力してください。";
        let sp_id=id.split("_");
        if(id.indexOf("danstart_")>-1){
            set_title = "【開始】を入力してください。";
        }
        if(id.indexOf("danend_")>-1){
            set_title = "【終了】を入力してください。";
        }
        $.ajax({
            type: 'GET',
            url: "/LotManagement/DatetimeKeyBord",
            dataType: 'html',
            success: function(data) {
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
                    let set_this_time = btn_entry();
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
                                focus_control();
                            }
                        }else{
                            $("span.ui-dialog-title").text("【"+id+"】もう一度入力してください。"); 
                            $("span.ui-dialog-title").css({"color": "red"});
                            $("#"+id).css({"background-color": "red"});
                        }
                    }else{
                        if(id.indexOf("danstart_")>-1){
                            s_time = btn_entry();
                            e_time = $("#danend_"+sp_id[1]+"_"+sp_id[2]).val();
                        }else{
                            s_time = $("#danstart_"+sp_id[1]+"_"+sp_id[2]).val();   
                            e_time = btn_entry();
                        }
                     
                        if(Date.parse(e_time)>Date.parse(s_time)){
                            $("#stoptod").focus();
                            $("#"+id).val(set_this_time);
                            $("#traytime_"+sp_id[1]+"_"+sp_id[2]).val(set_this_time);
                            $("#"+id).css({"background-color": ""});
                            $("span.ui-dialog-title").css({"color": ""});
                            $( this ).dialog( "close" );
                        }else{
                            $("span.ui-dialog-title").text(set_title); 
                            $("span.ui-dialog-title").css({"color": "red"});
                            $("#"+id).css({"background-color": "red"});
                        }
                    }
                }}]
                };
                $("button").button();
                $("#messagedt").html(data);
                if($("#"+id).val()!==""){
                    datetimefix(id);
                }
                $( "#alertdt" ).dialog( "option",options);
                $( "#alertdt" ).dialog( "open" );
                return false;
            }
        });
    }

    function focus_control(){
        let c= {}
        // c["打数"]=$("#打数").val();
        c["担当者"]=$("#担当者").val();
        c["終了日時"]=$("#終了日時").val();
        c["開始日時"]=$("#開始日時").val();
        let flag=0;
        let fc_key="";
        $.each(c,function(key) {
            if($.trim(c[key])==""){
                flag++;
                fc_key=key;
            }
        });
        if(flag==0){
            $("#entryData").focus();
        }else{
            $("#"+fc_key).focus();
        }
    }

    function rfid_fix_confirm(id){
        return new Promise((cb) => {
            if($("#"+id).val()!==""){
                var msg="紐づいたRFID[<b style='color:blue;'>"+$("#"+id).val()+"</b>]を修正してもよろしでしょうか?";
                var options = {"title":"確認してください！！！",
                    width: 600,
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            cb(false);
                        }},{ class:"btn-right",text:"OK",click :function(ev) {
                            $( this ).dialog( "close" );
                            $("#"+id).val("");
                            let sp_this_id = id.split("_");
                            $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"orange"});
                            cb(true);
                        }}]
                };
                $("#message").html(msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }else{
                cb(true);
            }
        })
    }

    function rfid_check(){
        return new Promise((cb) => {
            let check_user =$(".check_user_cav");
            let rnc = 0;
            $.each(check_user,function(k,v) {
                if(v.classList.value.indexOf("inserted-click")>-1){
                    rnc++;
                }
            })
            if(rnc==0){
                let rfc_msg = "まず、不良内容を確認してください。";
                var options = {"title":"確認",
                    width:600,
                    position:["center",170],
                    buttons: [{ text:"閉じる",class:'btn-right',click :function(ev) {
                        cb(false);
                        $( this ).dialog( "close" );
                    }}]
                };
                $("#message").html(rfc_msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }else if(rnc>0 && rnc < check_user.length && access_entry==false){
                let rfc_msg = "<span style='color:red;'>途中</span>で作業を終わらせて<span style='color:red;'>実績登録</span>してもよろしでしょうか？";
                var options = {"title":"確認",
                    width:600,
                    position:["center",170],
                    buttons: [{ text:"はい",class:'btn-confirm',click :function(ev) {
                        $( this ).dialog( "close" );
                        access_entry=true;
                        cb(true);
                    }},{ text:"いいえ",class:'btn-left',click :function(ev) {
                        $( this ).dialog( "close" );
                        access_entry=false;
                        cb(false);
                    }}]
                };
                $("#message").html(rfc_msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }else{
                cb(true);
            }

        })
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

    function rfid_status(itemcode,rfid,type,cb){
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
                    item:itemcode,
                },
                success: function(d){
                    loadingView(false);
                    if(d==true){
                        cb(d,type);
                    }else{
                        alert(d[1]);
                        return false;  
                    }
                }
            });
        } 
    }

    function goContinue(){
        let linked_id = $(".check_rfid");
        let c_flag = false;
        let rf_id="";
        for(i=0;i<linked_id.length;i++){
            let sp_this_id = linked_id[i].id.split("_");
            let this_cav_gnum = $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_good_num").val();
            if(parseInt(this_cav_gnum)>0){
                if(linked_id[i].value==""){
                    c_flag = true;
                    rf_id = linked_id[i].id;
                    break;
                }
            }else{
                $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_cav_view").css({"color":"gray"});
                $("#"+sp_this_id[0]+"_"+sp_this_id[1]+"_rfid").val("");
            }
        }
        if(c_flag && rf_id!=""){
            let sp_rfid = rf_id.split("_");
            openCamScan(rf_id,sp_rfid[1])
        }else{
            entryData(); 
        }
    }

    function printIt(id,rfid) {
        // console.log(id);
        // 印刷内容はまだ決まってない。
        let sp_id = id.split("_");
        var win = window.open();
        win.document.open();

        let printThis=`<style type="text/css">
            body{
                margin: auto;
                width: 320px;
                font-family: arial,sans-serif;
            }
            p{
                margin:0 0;
            }
            table.type03 {
                font-size:13px;
                border-collapse: collapse;
                text-align: center;
                line-height: 1.5;
                border-top: 1px solid #ccc;
                border-left: 1px solid #ccc;
                table-layout: fixed;
            }
            table.type03 th {
                padding: 3px;
                font-weight: bold;
                vertical-align: top;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            table.type03 td {
                padding: 3px;
                vertical-align: top;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                text-align:center;
            }
            .text_decor{
                position: relative;
                border-bottom: 1px solid;
                text-align: center;
            }
            .m_info{
                font-weight:bold;
            }
            .box_info{
                float:left;
            }
        </style>`;
        
        let itemcode = sp_id[0];
        let moldlot = $("#lot_no").val();
        let itemtag = $("#"+sp_id[0]+"_itemname").val();
        let itemform = $("#"+sp_id[0]+"_form_num").val();
        let cav = sp_id[1];
        let QRcode = itemcode+"=>"+moldlot+"=>"+itemform+"=>"+cav;
        printThis+=`<p class="text_decor">RFID:`+rfid+`</p>`;
        printThis+=`<div style="float:left;"><img src="https://chart.googleapis.com/chart?cht=qr&chs=170x170&chl=`+rfid+`&choe=UTF-8" alt="" style="float:left;margin: -15px -10px -10px -20px;" >`;
        printThis+=`<div style="float:left;width: 180px"><p>品目コード</p>`;
        printThis+=`<p class="text_decor m_info" style="margin-left:20px;">`+itemcode+`</p>`;
        printThis+=`<p>通称</p>`;
        printThis+=`<p class="text_decor m_info" style="margin-left:20px">`+itemtag+`</p>`;
        printThis+=`<div><div class="box_info"><p>ロットNo.</p><p class="text_decor m_info">`+moldlot+`</p></div>`;
        printThis+=`<div class="box_info" style="margin: 0 12px;"><p>型番</p><p class="text_decor m_info">`+itemform+`</div>`;
        printThis+=`<div class="box_info"><p>キャビ</p><p class="text_decor m_info">`+cav+`</p></div></div></div>`;
        printThis+=`<div style ="clear:both;"></div>`;
        printThis+=`<p style="float: left;margin-top: -10px;">QRコード</p>`;
        printThis+=`<div style ="clear:both;"></div>`;
        printThis+=`<p class="text_decor m_info">`+QRcode+`</p>`;

        win.document.write(printThis);
        win.document.close();
        win.print();
        setTimeout(() => {
            win.close();
        }, 1000);
    }

    function input_mode(id,name,fcmode){
        let sp_id = id.split("_");

        if($("#"+sp_id[0]+"_"+sp_id[1]+"_good_num").val()==0){
            $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").removeClass("toggle-color");
            $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").css({"color":"blue"});
            return;
        }else{
            $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").css({"color":"darkorange"});
        }

        var msg="<label for='QRコード'>QRコード：</label><input id='QRコード' type='text' value='' placeholder='QRコード' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='QRコード'>QRコード：</label><input id='QRコード' type='tel' pattern='[0-9]*' value='' placeholder='QRコード' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        }
        msg+="<div id='err_msg'></div>"
        $("#message").html(msg);

        $("#QRコード").on('keyup', function(e) {
            if(e.which == 13){
                let code = $("#QRコード").val();
                rfid_status(sp_id[0],code,"input",function(d,type){
                    if(d){
                        $("#"+id).val(code);
                        $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").removeClass("toggle-color");
                        $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").css({"color":"blue"});
                        // printIt(id,code);
                        $("#alert").dialog( "close" );
                    }else{
                        $("#err_msg").html("<p style='margin-top:10px;color:red;'>RFIDは利用中です。</p>");
                        del_msg();
                        return false;
                    }
                });
            }
        });
        var options = {"title":"紐づけのRFIDコードを入力してください。",
            position:["center",170],
            width: 500,
            buttons: [
                { text:"OK",class:"btn-right",click :function(ev) {
                    var code = $("#QRコード").val();
                    rfid_status(sp_id[0],code,"input",function(d,type){
                        if(d){
                            let c_flag = false;
                            let linked_id = $(".check_rfid");
                            $.each(linked_id,function(key,val){
                                if(val.value==code){
                                    c_flag = true;
                                }
                            })
                       
                            if(c_flag==false){
                                $("#"+id).val(code);
                                $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").removeClass("toggle-color");
                                $("#"+sp_id[0]+"_"+sp_id[1]+"_cav_view").css({"color":"blue"});
                                // rfid_check();
                                // printIt(id,code);
                                $("#alert").dialog( "close" );
                            }else{

                            }
                        }else{
                            if(type=="input"){
                                $("#err_msg").html("<p style='margin-top:10px;color:red;'>RFIDは利用中です。</p>");
                                del_msg();
                                return false;
                            }else{
                                alert("RFIDは利用中です。");
                                return false;
                            }
                            return false;
                        }
                    });
                }},{ text:"カメラで入力",class:"btn-right",click :function(ev) {
                    openCamScan(id,name,fcmode);
                    $( this ).dialog( "close" );
                }},
                { text:"閉じる",class:"btn-left",click :function(ev) {
                    $( this ).dialog( "close" );
                }}
            ]
        };
        $( "#alert" ).dialog( "option",options);
        $("#alert").dialog( "open" );
    }

    function del_msg(){
        setTimeout(() => {
            $("#err_msg").html("");
        }, 10000);
    }

    function change_stock(){
        let view = $(".stock-info");
        if(tray_type == "トレー"){
            tray_type="束";
        }else{
            tray_type="トレー";
        }
        $.each(view,function(k,v){
            let itc = v.id.split("_");
            // $("#label_"+v.id).html(tray_type);
            if(tray_type=="トレー"){
                // $(".dan-label-"+itc[0]).html("段");
                $("#"+v.id).removeClass("change-stock");
                $("#"+v.id).attr('readonly',true);
                if(v.value==0){
                    // $("#"+v.id).addClass("change-stock");
                    // $("#"+v.id).attr('readonly',false);
                    reloadCavTable(itc[0],'');
                }
            }else{
                $("#"+itc[0]+"_traynum").val(0);
                $("#"+itc[0]+"_traystock").val(1);
                // $(".dan-label-"+itc[0]).html(tray_type);
                $("#"+v.id).addClass("change-stock");
                $("#"+v.id).attr('readonly',false);
                setTimeout(() => {
                    reloadCavTable(itc[0],'',function(){
                        $("#"+itc[0]+"_traynum").select();
                    });
                }, 0);
            }
        });
        setTimeout(() => {
            if(tray_type == "束"){
                $(".change-stock").on('focus',function(e){
                    $("#"+e.currentTarget.id).select();
                });
                $(".change-stock").on('keyup',function(e){
                    let itc = e.currentTarget.id.split("_")[0];
                    setTimeout(() => {
                        reloadCavTable(itc,'');
                    }, 0);
                }); 
            }
        }, 0);
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

    function btn_control(ac){
        let kind=$("#num_in_tab").val();
        let sp_kind = kind.split("_");
        let posi = sp_kind[1]+"_"+sp_kind[2]+"_"+sp_kind[3]

        if($("#"+kind).val()==""){
            $("#"+kind).val(0);
        }

        let d = $(".n_input");
        let mnum=parseInt($("#生産数_"+posi).val());
        let gnum=parseInt($("#良品数_"+posi).val());
        let dnum=parseInt($("#不良数_"+posi).val());
        let num = 0;
        $.each(d, function(k,item){
            if(item.name.indexOf("def][") != -1){
                if(parseInt(item.value)>0){
                    num = num + parseInt(item.value);
                }
            }
        });

        if(mode=="cminus"){
            if(mnum-num<0){
                var msg="入力数値がオーバーされました。";
                var options = {"title":"確認してください！！！",
                    width: 600,
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            return;
                        }}]
                };
                $("#message").html(msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
                $("#"+kind).val(0);
                return false;
            }
            gnum = mnum-num;
            dnum = num;
            $("#良品数_"+posi).val(gnum);
            $("#不良数_"+posi).val(dnum);
            $("#sum_"+posi).val(gnum);
        }else{
            dnum = num;
            mnum = gnum+dnum;
            $("#生産数_"+posi).val(mnum);
            $("#不良数_"+posi).val(dnum);
        }
        setTimeout(() => {
            sum_ditem(kind);
            sum_all(kind.split("_")[1]);
        }, 0);
        $("#num_in_tab").val("");
        $("#number_in").dialog( "close" );
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

    function printKanbanApi(data) {
        // var win = window.open();
        // win.document.open();
        let printThis=`<style type="text/css">
            p{margin:2px 0;float:left;}
            table.type03 {
                font-size:13px;
                border-collapse: collapse;
                text-align: center;
                line-height: 1.5;
                border-top: 1px solid #ccc;
                border-left: 1px solid #ccc;
                table-layout: fixed;
            }
            table.type03 th {
                padding: 3px;
                font-weight: bold;
                vertical-align: top;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            table.type03 td {
                padding: 3px;
                vertical-align: top;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                text-align:center;
            }
        </style>`;
        printThis+=`<a id="close_print" href="javascript: window.close()">Close</a>`;
        printThis+=`<div style="width:300px;text-align:left;float:left;"><p>Hello</p></div>`;
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "/api/Printkanban",
            data: {
                ac:"print",
                data:data,
                ip_addr:"10.0.10.202"
            },
            dataType: "json",
            success: function (response) {
                loadingView(false);
                console.log(response);
            }
        });

        // win.document.write(printThis);
        // win.document.close();
        // win.print();
        setTimeout(() => {
            // win.close();
        }, 1000);
    }

    function printer_menu(){
        var msg='<label>再印刷のRFID：</label><input id="reprint_rfid" class="" value ="" ></input>';
        var options = {"title":"機能を選択してください。",
            position:["center",170],
            width: 500,
            buttons:[{class:"btn-left",text:"キャンセル",click :function(ev) {
                $( this ).dialog( "close" );
                return;
            }},{class:"btn-left",text:"再印刷へ",click :function(ev) {
                $( this ).dialog( "close" );
                return;
            }}]
        };

        $("#message").html(msg);
        $("button").button();
        $(".ui-resizable-handle").hide();
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
        setTimeout(() => {
            rePrintCam("reprint_rfid");
        }, 0);
    }

    
    async function rePrintCam(id){
        let print_ip = $("#printer_ip").val();
        if(print_ip==""){
            change_printer();
            return;
        }
        video = document.createElement("video");
        canvasElement = document.getElementById("canvas");
        canvas = canvasElement.getContext("2d");
        // Use facingMode: environment to attemt to get the front camera on phones
        if(!fcmode){
            fcmode=localStorage.getItem("sFcmode")
        }
        navigator.mediaDevices.getUserMedia({ video: { facingMode:fcmode, width:900, height:600} }).then(function(stream) {
            localStream = stream;
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            tick(id);
        });

        let button = [
            { html:"<span class='dialog_btn ui-icon-recycle ui-btn-icon-right'>カメラ切替</span>",class:"btn-right",click :function() {
                if(fcmode=="user"){
                    fcmode="environment";
                }else{
                    fcmode="user";
                }
                localStorage.setItem("sFcmode",fcmode);
                stop_scan();
                setTimeout(() => {
                    rePrintCam(id);
                }, 200);
            }},
            { html:"<span class='dialog_btn ui-icon-delete ui-btn-icon-right'>閉じる</span>",class:"btn-left",click :function() {
                stop_scan();
            }}
        ];

        var options = {"title":"再印刷のQRコードをスキャンしてください。",
            position:["right",30],
            buttons: button
        };
        $("#QRScan").dialog( "option",options);
        loadingView(false);
        $("#QRScan").dialog( "open" );
        $("#"+id+"_rfid").focus();
    }

    function re_print(rfid){
        loadingView(true);
        let print_ip = $("#printer_ip").val();
        let print_name = $("#btn_change_printer span").html();
        if(print_ip==""){
            change_printer();
        }
        $.ajax({
            type: "GET",
            url: "RePrintKanban",
            data: {
                ac:"kanban_reprint",
                rfid:rfid,
                process:"成形",
                print_ip:print_ip
            },
            dataType: "json",
            success: function (response) {
                loadingView(false);
                console.log(response);
                if(response=="OK"){
                    openAlert("print","完了","「<b>"+print_name+"</b>」プリンターで<b>再印刷</b>完了!");
                }else{
                    openAlert("print","確認",response);
                }
            }
        });
    }

    function change_printer(){
        // alert("select printer");

        if($("#担当者").val()==""){
            lc="btn_change_printer";
            $( "#dialog_user" ).dialog( "open" );
            return;
        }
        var old_name = $("#btn_change_printer span").html();
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getPrinterIp",
                plant:$("#plant").val()
            },
            dataType: "json",
            success: function (response) {
                loadingView(false);
                var msg = '<div style="text-align:left;">';
                $.each(response, function (k, v) { 
                    let add_btn_class = "";
                    if(v.wim_res_name==old_name){
                        add_btn_class = "btn_disable";
                    }
                    msg+='<button class="btn_printer '+add_btn_class+'" onclick=confirm_printer(`'+v.wim_ipaddr+'`,`'+v.wim_res_name+'`,`'+old_name+'`)>'+v.wim_res_name+'</button>';
                });
                msg+='<p id="printer_msg" style="margin-top:10px;color:red;"></p></div>';
                var options = {"title":"プリンターを選択してください。",
                    position:["center",170],
                    width: 960,
                    buttons:[
                        { class:"btn-left",text:"閉じる",click :function(ev) {
                            if(old_name=="" || old_name == "未設定"){
                                $("#printer_msg").html("プリンターを選択してください。");
                                return; 
                            }else{
                                $( this ).dialog( "close" );
                                return; 
                            }
                        }},
                        { class:"btn-left",text:"印刷しない",click :function(ev) {
                            openAlert(
                                "callback",
                                "確認！",
                                "カンバンを<b style='color:red;'>印刷しない</b>ようにしますか？",
                                [
                                    { class:"btn-right",text:"いいえ",click :function(ev) {
                                        $("#printer_msg").html("プリンターを選択してください。<br><span style='color:#000;'>変更がない場合は「<b>閉じる</b>」ボタンを押してください。<span>");
                                        $( this ).dialog( "close" );
                                        return;
                                    }},
                                    { class:"btn-right",text:"はい",click :function(ev) {
                                        if(old_name=="" || old_name == "未設定"){
                                            $( this ).dialog( "close" );
                                            $( "#alert" ).dialog( "close" );
                                            return;
                                        }else{
                                            localStorage.removeItem("sPrinter");
                                            $("#printer_ip").val("");
                                            $("#btn_change_printer span").html("未設定");
                                            $( "#alert" ).dialog( "close" );
                                            $( this ).dialog( "close" );
                                            // change_printer("");
                                            $.ajax({
                                                type: "POST",
                                                url: "UserEventLog",
                                                data: {
                                                    d:{
                                                        item:"プリンター",
                                                        user:$("#担当者").val(),
                                                        plant:$("#plant").val(),
                                                        client_ip:client_ip,
                                                        log:"["+old_name+"]から[未設定]に変更",
                                                        remark:"端末:"+client_os,
                                                    }
                                                },
                                                dataType: "json",
                                                success: function (res) {
                                                    console.log(old_name+"=>"+name);
                                                }
                                            });
                                            return;
                                        }
                                    }}
                                ]
                            );
                        }},
                    ]
                };
                $("#message").html(msg);
                $(".btn_printer").button();
                $(".btn_disable").button("disable");
                $(".ui-resizable-handle").hide();
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" );
            }
        });
    }

    function confirm_printer(ip,name,old_name){
        let json_prt = [name,ip];
        localStorage.setItem("sPrinter",JSON.stringify(json_prt));
        $("#printer_ip").val(ip);
        $("#btn_change_printer span").html(name);
        $( "#alert" ).dialog( "close" );
        $.ajax({
            type: "POST",
            url: "UserEventLog",
            data: {
                d:{
                    item:"プリンター",
                    user:$("#担当者").val(),
                    plant:$("#plant").val(),
                    client_ip:client_ip,
                    log:"["+old_name+"]から["+name+"]に変更",
                    remark:"端末:"+client_os,
                }
            },
            dataType: "json",
            success: function (res) {
                console.log(old_name+"=>"+name);
            }
        });
    }

    function openAlert(type,title,msg,alert_btn,callback){
		return new Promise((resolve) => {
            if(!alert_btn){
                if(type=="confirm"){
                    alert_btn = [{class:"btn-right",text:"確定",click :function(ev) {
                        $( this ).dialog( "close" );
                        resolve(true);
                    }},{class:"btn-right",text:"キャンセル",click :function(ev) {
                        $( this ).dialog( "close" );
                        resolve(false);
                    }}];
                }else{
                    alert_btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        if(callback){
                            callback();
                        }
                        return;
                    }}]
                }
            }
            var options = {"title":title,
                position:["center", 170],
                width: 600,
                buttons:alert_btn
            };
            $("#msg").html(msg);
            $( "#openAlert" ).dialog( "option",options);
            $( "#openAlert" ).dialog( "open" );
        });
    }
    
    function Std_digitization(){
        let itemlist=$("#item_list").val().split(",");
        // let workitem = $('input[name=work_process]:checked').val(); // 工程
        let workitem = "成形"; // 工程
        let b_folder = "/files/yasu-fs01/DAS連携/PDFデータ/pdf標準類/";
        let c_folder = $("#client_folder").val();
        $.each(itemlist,function(key,item){
            uri = b_folder+c_folder+"_"+encodeURIComponent(item)+"_"+encodeURIComponent(workitem)+".pdf";
            window.open(uri);
        });
    }

    // デジタルのファイル対応(false="check",true="open")
    function openDigital(flag){
        let itemlist=$("#item_list").val().split(",");
        // let workitem = $('input[name=work_process]:checked').val(); // 工程
        let workitem = "成形"; // 工程
        let c_folder = $("#client_folder").val();
        $.each(itemlist,function(key,item){
            let file_name = c_folder+"_"+item+"_"+workitem+".pdf";
            Std_digitization_uri_check('OpenSTD',file_name,flag);
        });
    }

    // デジタルのファイルのチェック
    function Std_digitization_uri_check(id,val,open_flag){
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

</script>

<div id="main_menu" style="font-weight:bold;text-align:center;width:fit-content;margin:auto;">
    <div class='ipbox'>
        <p><lable >号機</lable></p>
        <input id="mold" class="readonly-input" name="基本][mold" value="<?=$lot_info[0]["lot_mno"]?>" readonly="readonly" style="width:60px;" />
    </div>
    <div class='ipbox'>
        <p><lable >ロット№</lable></p>
        <input id="lot_no" class="readonly-input" name="基本][LotNo" value="<?=$lot_info[0]["lot_num"]?>" readonly="readonly" style="width:80px;" />
        <input type="hidden" id="lot_id" name="基本][LotId" value="<?=$lot_info[0]["lot_id"]?>" />
    </div>      
    <div class='ipbox'>
        <p><label for="開始日時">開始日時</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][開始" id="開始日時" readonly="readonly" onclick="datetime(this.id);" style="width:175px;"/>
    </div>      
    <div class='ipbox'>
        <p><label for="終了日時">終了日時</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][終了" id="終了日時" readonly="readonly" onclick="datetime(this.id);" style="width:175px;"/>
    </div>      
    <!-- <div class='ipbox'>
        <p><label for="作業時間">作業時間</label></p>
        <input type="text" value="" name="基本][作業時間" id="作業時間" style="width:80px;"/>
        <div style="clear:both;"></div>
    </div> -->
    <div class='ipbox'>
        <p><label for="担当者">担当者</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][担当者" id="担当者" onclick="searchUserCav(this.id,'');" readonly="readonly" style="width:180px;"/>
    </div>
    <div class='ipbox'>
        <p><label for="打数">打数</label></p>
        <input type="number" class="items_info_ip" value="" name="基本][打数" id="打数" style="width:50px;"/>
    </div>
    <div class='ipbox'>
        <p><label for="cycle">サイクル</label></p>
        <input type="number" class="items_info_ip" value="" name="基本][cycle" id="cycle" readonly="readonly" style="width:60px;"/>
    </div>
    <div class='ipbox'>
        <p><lable style="">成形工程</lable></p>
        <!-- <select class="sl-box" id="work_process" style="">
            <option value="-">-</option>
            <option value="成形+GC">成形+GC</option>
            <option value="成形">成形</option>
        </select> -->
        <input type="radio" name="work_process" class="work_process" id="r_mold" value="成形" /><label for="r_mold" class="btn-cav" style="height:26px;" >成形</label>
        <input type="radio" name="work_process" class="work_process" id="r_mold_cut" value="成形+GC" /><label for="r_mold_cut" class="btn-cav" style="height:26px;margin-left: -7px;" >成形+GC</label>
    </div>

    <div class='ipbox'>
        <p><label for="place">置場所 </label></p>
        <select name="place" id="place" class="sl-box" style="width:200px;font-size: 17px;">
        <?php foreach($plant as $plants){?>
            <option value="<?= $plants["val"];?>"><?= $plants["view_name"]?></option>
        <?php }?>
        </select>
        <span id="selectplace" style="font-size: 18px;"></span>
    </div>
    <input type="hidden" id="plant" name="基本][工場" value="<?=$plant[0]["name"]?>" />
    <input type="hidden" id="plantid" name="基本][工場ID" value="<?=$lot_info[0]["placeid"]?>" />
    <input type="hidden" id="item_list" name="" value="" />
    <input type="hidden" id="cavs_list" name="" value="" />
    <input type="hidden" id="printer_ip" name="printer_ip" value="" />
    <input type="hidden" id="client_folder" name="client_folder" value="" />
    <div style="clear:both;"></div>
</div>
<div id="item_area" style="font-weight:bold;margin-top:10px;padding: 5px;">
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>

<div id="alertdt">
    <div id="messagedt" style="text-align: center;"></div>
</div>

<div id="dialog" style="">
    <div id="diff_item" style="font-weight:bold;"></div>
    <div style="clear:both;"></div>
</div>
<label id="msgLabel" style="float:left;font-size:18px;"></label>
<p>
    <label id="site_help" style="float:left;font-size:16px;">*<b>キャビ</b>のID連携の表現：
        <span class="color-box" style="background:darkorange;"></span><span style="color:darkorange;margin:0 10px 0 26px;">未連携</span>
        <span class="color-box" style="background:blue;"></span><span style="color:blue;margin:0 10px 0 26px;">連携済み</span>
        <span class="color-box" style="background:gray;"></span><span style="color:gray;margin:0 10px 0 26px;">連携不可</span>
    </label>
    <button id="entryData" type="button" onclick="entryData();" class="" style="float:right;font-size:22px;font-weight:bold;padding:.5em 1em;">登録</button>
    <button id="OpenSTD" type="button" onclick="openDigital(true);" class="" style="float:right;font-size:22px;font-weight:bold;padding:.5em .5em;">チェックポイント</button>
</p>
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
<?php foreach($lot_info as $k=>$v){ ?>
    <input type="text" id="bad_list_<?=$v["item_code"]?>" name="" value="" style="display:none;" />
<?php } ?>