<?php
    use_javascript("jquery/jquery.dump.js");
    use_javascript("jsQR.min.js");
    use_javascript("molding_info.js");

    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");
?>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style type="text/css">
    html {
        -webkit-text-size-adjust: none;
        touch-action: manipulation;
    }
    body {background-color:#000;color:#FFF;}
    #content{ padding-top: 0vh;}
    #ui {height:100%}
    .ui-dialog,.ui-widget-content,.ui-tabs-nav, .foo, .ui-draggable, .ui-resizable, .ui-dialog-titlebar { background:#000 !important;}
    .t_cont_out{ margin: 0 auto; width: 1000px;}
    .t_cont_inn{ max-width: 100%;height:100%;overflow:scroll; }/* min-height:326px;max-height:326px; */
    input { background-color:#000;color:#FFF;border:none;}
    /* .b_data {min-height:140px;max-height:140px;} */
    .b_data ,.b_data input{ font-size:16px;font-weight: bold;color:#FFF;margin-right:5px;}
    .b_data .ui-button-text {color:#FFF;font-family:arial,sans-serif;font-size:18px!important;font-weight:bold;line-height:1.0 !important;padding:8px 8px !important; }
    .btn_ditemset{ font-size:18px;}
    .tab_content label ,.tab_content input {color:#FFF;font-size:18px;font-weight:bold;margin-top: 12px;}
    .tab_content input { width:80px;text-align:center;}
    input {border-radius:5px;}
    .box{ margin:auto; width:135px; text-align:center;float:left;margin:2px;background-color:#1d1d1f; }
    .box .box_content{float: left;}
    .b_line{ color:#FFF;text-align:center;font-size:18px;font-weight:bold; }
    .n_input{ outline: none;border:none;width:45px;height:32px;margin:3px;font-size:18px !important;text-align:center;font-weight:bold;background-color:#1d1d1f;}
    .btn .ui-button-text{ font-family:arial,sans-serif;font-size:30px !important;line-height:1.0 !important;padding:10px 16px !important;}
    .fl { float: left;}
    .fr { float: right;}
    #ditemsetpop { padding-top: 2px;}
  	#ditemsetpop .set { line-height:1em;vertical-align: middle;display: block;width: 228px; border: 1px #FFF solid;margin-left: -1px;margin-top: -1px;padding: 2px 3px ; }
  	#ditemsetpop input { float: left; }
  	#ditemsetpop label { float: left; font-size:18px; }
    #message { color:#FFF;}
    .remark{ resize: none;width:100%;height:30px;background-color:#000;color:#FFF;border:0.1pt solid #ffffff;}
    #状態{ width:100px;}
    #QRコード{border:0.1pt solid #ffffff;}
    .ui-tabs .ui-tabs-nav li a{padding: .3em .8em;}
    .ui-tabs .ui-tabs-panel{padding: .4em .4em;}
    .ui-widget-content {color:#fff;}
    table.type03 { margin-left:5px; font-size:14px; border-collapse: collapse; text-align: left; line-height: 1.5; border-top: 1px solid #ccc; border-left: 5px solid #ccc; table-layout: fixed; }
    table.type03 th { padding: 2px 4px; font-weight: bold; vertical-align: top; text-align:center; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
    table.nowarp td{ white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    table.type03 td { padding: 2px 4px; vertical-align: top; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
    #number_in{ word-spacing: -5px; }
    #number_in .num_btn { width:60px; height:60px; padding:1px; margin: 2px 2px; border-radius: 25px; }
    #number_in .num_btn span{ padding:18px 0 0 0; font-weight: bold; }
    .btn, .num_btn{ -khtml-user-select: none; -moz-user-select: none; -webkit-touch-callout: none; -webkit-user-select: none; /* Safari */ -ms-user-select: none; /* IE 10 and IE 11 */ user-select: none; /* Standard syntax */ }

    #ui .btn_plus{ width:80px; height:80px; }

    #ui .btn_minus{ width:45px; height:45px; float:right; }

    #ui .btn_plus .ui-button-text{ padding: 16px !important; font-size: 50px !important; }

    #ui .btn_minus .ui-button-text{ padding:0 !important; font-size: 40px !important; }

    .btn_control .ui-button-text{ padding: 15px 8px !important; }

    .ui-dialog{ padding:4px 2px 4px 4px; }
    .view_only{ position: absolute; top: 45px; left: 0; width:100%; height:calc(100% - 45px); background: #aaa; opacity: .2; z-index: 1000; }

    .view_pause{ position: absolute; top: 115px; left: 0; width:100%; height:calc(100% - 120px); background: #000; font-size:30px; font-weight:bold; text-align:center; z-index: 1000; vertical-align:middle; }
    #ditemsetpop .set{ width:205px; }

    #ui .btn_continue{ width:230px; height:100px; border-radius: 15px; font-weight:bold; color:#000; background:#fff; }
    #ui .btn_continue .ui-button-text{ font-size:40px !important; }
    .ui-dialog-buttonset{ width: 100%; text-align: center; }
    .btn-left .ui-button-text,.btn-right .ui-button-text { padding: 4px 10px; }
    .btn-left{ /* width:115px; */ float:left; }
    .btn-right{ /* width:115px; */ float:right; }
    .btn-red .ui-button-text { color:red; }
    .btn-confirm .ui-button-text{ color:aqua; }
    .btn-parts .ui-button-text{ font-size:18px; padding: 5px 10px; font-weight:bold; }

    #ui .btn_continue{
        width:230px;
        height:100px;
        border-radius: 15px;
        font-weight:bold;
        color:#000;
        background:#fff;
    }
    #ui .btn_continue .ui-button-text{
        font-size:40px !important;
    }
    .ui-dialog-buttonset{
        width: 100%;
        text-align: center;
    }
    .btn-left .ui-button-text,.btn-right .ui-button-text {
        padding: 4px 10px;
    }
    .btn-left{
        /* width:115px; */
        float:left;
    }
    .btn-right{
        /* width:115px; */
        float:right;
    }
    .btn-red .ui-button-text {
        color:red;
    }
    .btn-confirm .ui-button-text{
        color:aqua;
    }
    .btn-parts .ui-button-text{
        font-size:18px;
        padding: 5px 10px;
        font-weight:bold;
    }
    .btn-confirm{
        background:rgb(120, 235, 255);
        color:#000;
    }

    .ui-btn-theme-custom{
        background-color: #333;
        border-color: #1f1f1f;
        color: #fff;
        text-shadow: 0 1px 0 #111;
    }

    .mes_app_gr{
        display:none;
    }

    .to_link_id{
        display:none;
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

<script type="text/javascript">
    var aw = 1080,view_location="";
    var video,canvasElement,canvas;
    var fcmode = localStorage.getItem("sFcmode");
    if(!fcmode){
        fcmode= "user";
        localStorage.setItem("sFcmode",fcmode);
    }
    var counter_mode = "count_down";
    $(document).ready(function(){
        //$('#ui-tab').tabs();
        var bk_mode = localStorage.getItem("counter_mode");
        if(bk_mode){
            counter_mode = bk_mode;
        }
        $("button").button();
        $(".num_btn").button();
        $( "#alert" ).dialog({
            autoOpen: false,
            width:aw,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        $( "#alert2" ).dialog({
            autoOpen: false,
            width:aw,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        $(".ui-dialog-titlebar-close").hide();
        $( "#number_in" ).dialog({
            autoOpen: false,
            width:205,
            modal:true,
            resizable: false,
        });
        let QRd_w = $(window).width();
        $("#canvas").width(QRd_w/2);

        $( "#QRScan" ).dialog({
            title:"QRコードをスキャンしてください。",
            width: QRd_w/2,
            autoOpen: false,
            modal:true,
            position:["right",30],
            buttons: [{ text: "閉じる", click: function() {
                stop_scan();
            }}]
        });
        // $("#num_dialog .ui-dialog-titlebar").hide();
        $("#number_in").siblings('div.ui-dialog-titlebar').remove();

        //sum_tab_all();
        window.onbeforeunload = function() {
            if(localStorage.getItem("bk_data")){
                // return "このページから移動しますか？ 入力した情報は保存されません。";
            }
        }

        resize();
        var timer = false;
        $(window).resize(function() {
            if (timer !== false) {
                clearTimeout(timer);
            }
            timer = setTimeout(function() {
                resize();
            }, 100);
        });

        if(check_Storage()){
            ditemListView("reload");
        }else{
            ditemListView("firstload");
        }

        // 目視検査アプリの使うの交換
        $("#mes_app_set").on("change",function(e){
            let flag = e.target.checked;
            let msg= '<span style="">機械学習機能を<b>無効</b>にしますか？<span>';
            if(flag==true){
                msg= '<span style="">機械学習機能を<b>有効</b>にしますか？<span>'; 
            }
            var options = {"title":"確認してください！！！",
                width: 400,
                position:["centetr",100],
                buttons: [{ class:"btn-right",text:"はい",click :function(ev) {
                    change_machine_learning_mode(flag)
                    $( this ).dialog( "close" );
                }},{ class:"btn-left",text:"いいえ",click :function(ev) {
                    change_machine_learning_mode(!flag)
                    $( this ).dialog( "close" );
                }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog( "open" );
        });
    });
    
    // 準備したファイルからデータ収得
    function get_data(dataAry, key, value) {
        var result = $.grep(dataAry, function (e) {
            return e[key] == value;
        });
        return result;
    }

    // 変数の中に値を取る
    function get_group_by_name(dataAry, name){
        var result=$.map(dataAry, function( n, i ){
            return n[name];
        });
        result=[...new Set(result)];
        return result;
    }

    function resize(){
        wh = $(window).height() - 300;
        if($(window).width()>1200){
            $(".t_cont_out").css({"width":"80%"});
            $(".t_cont_inn").css({"width":"100%"});
        }else{
            aw = $(window).width() - 50;
            $(".t_cont_out").css({"width":"99%"});
            $(".t_cont_inn").css({"width":"100%"});
        }
        // $(".t_cont_inn").height(wh);
    }

    // 開始/停止ボタンの機能
    var count_time;
    function status_btn(cm){
        var tab = $(".ui-state-active a span").html();
        var d={};
        var msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
        var errc=0;
        var id = "";
        $.each(d,function(key) {
            if($.trim(d[key])==""){
                msg = msg +"<li>"+key+"</li>\n";
                errc++;
                id = key;
            }
        });
        if(counter_mode=="count_down"){
            if($("#受入数_"+tab).val() == 0){
                msg = msg +"<li>受入数</li>\n";
            };
        }else{
            if($("#完成数_"+tab).val() == 0){
                msg = msg +"<li>完成数</li>\n";
            };
        }

        if($("#成形日_"+tab).val() == ""){
            msg = msg +"<li>成形日</li>\n";
        };
        if(errc===0){
            var tab = $(".ui-state-active a span").html();

            if(counter_mode=="count_down"){
                if($("#受入数_"+tab).val() > 0){
                    if ($("#状態").button( "option", "label" ) == "再開"){
                        //count_start();
                        count_time = setInterval("counttime()", 1000 );
                        $("#状態").button({ label: "一時停止"} );
                        $("#view_pause_"+tab).removeClass("view_pause");
                        $("#view_pause_"+tab).hide();
                    }else if ( $("#状態").button( "option", "label" ) == "一時停止"){
                        clearInterval(count_time);
                        $("#状態").button({ label: "再開"} );
                        $("#view_pause_"+tab).show();
                        $("#view_pause_"+tab).addClass("view_pause");
                        $("#view_pause_"+tab).height($("#item_"+tab).height());
                        $("#view_pause_"+tab).css({"top":"50px"});
                    }else if ( $("#状態").button( "option", "label" ) == "開始"){
                        count_time = setInterval("counttime()", 1000 );
                        $("#状態").button({ label: "一時停止"} );
                    }
                }else{
                    var msg ="<b><b style='color:red;'>【受入数】</b>を入力してください。！</b>\n";
                    var options = {"title":"確認",
                        width:aw,
                        buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            $("#受入数").focus();
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog( "open" );
                }
            }else{
                if($("#完成数_"+tab).val() > 0){
                    if ($("#状態").button( "option", "label" ) == "再開"){
                        //count_start();
                        count_time = setInterval("counttime()", 1000 );
                        $("#状態").button({ label: "一時停止"} );
                        $("#view_pause_"+tab).removeClass("view_pause");
                        $("#view_pause_"+tab).hide();
                    }else if ( $("#状態").button( "option", "label" ) == "一時停止"){
                        clearInterval(count_time);
                        $("#状態").button({ label: "再開"} );
                        $("#view_pause_"+tab).show();
                        $("#view_pause_"+tab).addClass("view_pause");
                        $("#view_pause_"+tab).height($("#item_"+tab).height());
                        $("#view_pause_"+tab).css({"top":"50px"});
                    }else if ( $("#状態").button( "option", "label" ) == "開始"){
                        count_time = setInterval("counttime()", 1000 );
                        $("#状態").button({ label: "一時停止"} );
                    }
                }else{
                    var msg ="<b><b style='color:red;'>【完成数】</b>を入力してください。！</b>\n";
                    var options = {"title":"確認",
                        width:aw,
                        buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            $("#完成数").focus();
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog( "open" );
                }
            }

        }else{
            var options = {"title":"確認",
                buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog( "open" );
        }
    }

    // 条件が満たされた場合：自動開始
    function setupTab(id) {
        id = id.split("_");
        //$("#キャビ入数_"+id).val($("#受入数_"+id).val());
        if($("#作業工程").val().indexOf("組立")>-1){
            focus_assembly("auto");
        }else{
            if($("#Lotコード").val() != ""){
                if($("#受入数_"+id[1]).val() > 0){
                    if(parseInt($("#完成数_"+id[1]).val())==0){
                        $("#完成数_"+id[1]).val($("#受入数_"+id[1]).val());
                    }else{
                        sum_this_tab(id[1]);
                    }
                    if ($("#状態").button( "option", "label" ) == "再開"){
                        count_time = setInterval("counttime()", 1000 );
                        $("#状態").button({ label: "一時停止"} );
                    }else if ( $("#状態").button( "option", "label" ) == "開始"){
                        count_time = setInterval("counttime()", 1000 );
                        $("#状態").button({ label: "一時停止"} );
                    }
                    //sendMes("Item,");
                }
            }else{
                var msg ="<b>Lotコード取得の画面で【開始】ボタンを押してください。！</b>\n";
                var options = {"title":"確認",
                    width:aw,
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                    }}]
                };
                $("#message").html(msg);
                $( "#alert" ).dialog( "option",options);
                $("#alert").dialog( "open" );
            }
        }
    }

    // 作業時間の数える
    function counttime() {
        data_backup();
        var sec = parseInt($("#作業時間").val())+1;
        $("#作業時間").val(sec);
        var view_time = Math.floor(sec / 60);
        if(0==view_time){
            view_time =  1;
        }
        $("#作業時間表示").html(view_time+"分");
    }

    // 不良品カウンターのcontent
    function ditemListView(ac){
        bk_dataSet();
        // 不良内容の取得
        //$("#ui-tab").html("");
        var tab_set = "";
        var bk_data =JSON.parse(localStorage.getItem("bk_data"));
        console.log(bk_data);
        tab_set = JSON.parse(localStorage.getItem("Tabs"));
        //$("#ui-tab").append("<div style='clear:both;'></div>")
        tab_set = tab_set.split(",");
        $('#ui').html("");

        var ui_item='';
        var t_item='';

        ui_item+='<div id="ui-tab">';
        ui_item+='</div>'
        $("#ui").append(ui_item);
        t_item+='<ul>';
        $.each(tab_set,function(key,item){
            t_item+='<li class="tabs" name="'+item+'" id="ontab_'+item+'" onclick="switch_tab();"><a id="tab_'+item+'" href="#item_'+item+'"><span>'+item+'</span></a></li>';
        });
        t_item+='</ul>';
        let first_item=tab_set[0];
        let last_item="";
        $.each(tab_set,function(key,item){
            last_item=item;
            let ip_class= "input quantity";
            if(key!==0){
                ip_class = "input quantity quantity_input";
            }
            let mold_date = "";
            if($("#成形日").val()!=""){
                mold_date = $("#成形日").val().replace( /-/g,'/').substr(2);
            }
            t_item+=`<div id="item_`+item+`" >
                <div class="tab_content">`;
                if($("#作業工程").val().indexOf("組立")>-1){
                    if(bk_data.list_parts!==""){
                        let list_parts=bk_data.list_parts.split(",");
                        $.each(list_parts,function(a,b){
                            t_item+=`
                            <p>
                                <button style="" type="button" onclick="add_parts_cam('rfid_`+item+`_`+b+`');" class="btn-parts">部品かんばん✚</button>
                                <label for="rfid_`+item+`_`+b+`">RFID：</label><input type="text" id="rfid_`+item+`_`+b+`" class="add-parts" value ="" name="数基-本][`+b+`][rfid" placeholder="" readonly="readonly" style="width:140px;ime-mode: disabled;" />
                                <label for="品目コード_`+item+`_`+b+`">部品`+(a+1)+`：</label><input type="text" id="品目コード_`+item+`_`+b+`" value ="`+b+`" name="数基-本][`+b+`][品目コード" placeholder="" style="width:140px;ime-mode: disabled;" readonly="readonly" />
                                <label for="成形日_`+item+`_`+b+`">成形日：</label><input type="text" id="成形日_`+item+`_`+b+`" value ="`+mold_date+`" name="数基-本][`+b+`][成形日" placeholder="" style="width:120px;ime-mode: disabled;" readonly="readonly" />
                                <label for="受入数_`+item+`_`+b+`">受入数：</label><input id="受入数_`+item+`_`+b+`" type="tel" class="parts-num" pattern="[0-9]*" value="0" name="数基-本][`+b+`][受入数" placeholder="" readonly="readonly" />
                            </p>`;
                            t_item+=`<input type="text" value="" id="原料ロット_`+item+`_`+b+`" name="カンバン][`+b+`][原料ロット" readonly="readonly" style="display:none;" />
                            <input type="text" value="" id="前工程ID_`+item+`_`+b+`" name="カンバン][`+b+`][前工程ID" readonly="readonly" style="display:none;"/>`;
                        })
                    }

                    t_item+=`
                    <label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" type="tel" pattern="[0-9]*" value="" name="数基-本][`+item+`][受入数" class=""  placeholder=""  readonly="readonly" />
                    <label for="完成数_`+item+`">完成数：</label><input id="完成数_`+item+`"  class="`+ip_class+`" type="text" value="" name="数基-本][`+item+`][完成数" class="perfect"  placeholder="" readonly="readonly"  />
                    <label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste" placeholder="" readonly="readonly" />`;
                }else{
                    t_item+=`<label for="成形日_`+item+`">成形日：</label><input type="text" id="成形日_`+item+`" value ="`+mold_date+`" name="数基-本][`+item+`][成形日" placeholder="" style="width:120px;ime-mode: disabled;" readonly="readonly" />`;
                    if($("#link_to_proc").val()=="1"){
                        t_item+=`<label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" onblur="setupTab(this.id);" type="tel" pattern="[0-9]*" value="`+bk_data["前良品_"+item]+`" name="数基-本][`+item+`][受入数" class="`+ip_class+`"  placeholder="" readonly="readonly" />`;
                        t_item+=`<label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste"  placeholder="" readonly="readonly" />
                        <label for="完成数_`+item+`">完成数</label><input id="完成数_`+item+`" type="text" value="`+bk_data["前良品_"+item]+`" name="数基-本][`+item+`][完成数" class="perfect"  placeholder="" readonly="readonly"  />
                        <div style="clear:both;"></div>
                        <label class="to_link_id">仕掛IDと連携：</label><input type="text" value="" id="仕掛ID連携_`+item+`" class="to_link_id" style="width:400px;"  name="基本][`+item+`][仕掛ID連携" readonly="readonly"/>
                        <button type="button" class="to_link_id" onclick="od('仕掛ID連携_`+item+`','紐付の仕掛ID');" class="btn_ditemset btn_topmenu" style="width:45px;"><span class="ui-icon-camera ui-btn-icon-left" ></span></button>
                        `;
                    }else{
                        if(counter_mode=="count_down"){
                            t_item+=`
                            <label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" onblur="setupTab(this.id);" type="tel" pattern="[0-9]*" value="`+$("#前良品").val()+`" name="数基-本][`+item+`][受入数" class="`+ip_class+`"  placeholder="" readonly="readonly" />
                            <label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste"  placeholder="" readonly="readonly" />
                            <label for="完成数_`+item+`">完成数</label><input id="完成数_`+item+`" type="text" value="`+$("#前良品").val()+`" name="数基-本][`+item+`][完成数" class="perfect"  placeholder="" readonly="readonly"  />`;
                        }else{
                            t_item+=`
                            <label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" type="tel" pattern="[0-9]*" value="`+$("#前良品").val()+`" name="数基-本][`+item+`][受入数" class="" placeholder="" readonly="readonly" />
                            <label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste" placeholder="" readonly="readonly" />
                            <label for="完成数_`+item+`">完成数</label><input id="完成数_`+item+`"  class="`+ip_class+`" type="text" value="`+$("#前良品").val()+`" onblur="setupTab(this.id);" name="数基-本][`+item+`][完成数" class="perfect" placeholder="" readonly="readonly"  />`;
                        }
                    }
                    t_item+=`<input type="hidden" value="`+$("#原料ロット").val()+`" id="原料ロット_`+item+`" name="カンバン][`+item+`][原料ロット" readonly="readonly"/>
                    <input type="hidden" value="`+$("#rfid").val()+`" id="RFID_`+item+`" name="カンバン][`+item+`][RFID" readonly="readonly"/>
                    <input type="hidden" value="`+$("#前工程ID").val()+`" id="前工程ID_`+item+`" name="カンバン][`+item+`][前工程ID" readonly="readonly"/>`;
                }
                        
                    t_item+=`
                        <input class="fr" type="text" value="0" id="員数ミス_`+item+`" name="数基-本][`+item+`][員数ミス" readonly="readonly"  />
                        <button class="fr btn" onclick="c_miss_dw('`+item+`');" style="width:50px;height:50px;">-</button>
                        <button class="fr btn" onclick="c_miss_up('`+item+`');" value="`+item+`" style="width:50px;height:50px;" >+</button>
                        <label class="fr" style="margin: 12px 5px 0 0">員数ミス</label>
                        <div style="clear:both;"></div>
                    </div>
                    <hr style="margin:8px 0 10px 0;"/>
                    <div id="tab_ditem_`+item+`" class="t_cont_inn"></div>
                    <hr style="margin:5px 0 10px 0;"/>
                    <textarea id="備考_`+item+`" placeholder="備考" class="remark" name="数データ][`+item+`][備考"></textarea>
                    <div id="view_only_`+item+`" class=""></div>
                    <div id="view_pause_`+item+`" class="" style="display:none;">
                        <p style="padding-top:50px;"><ruby>一時停止中<rt>いちじていしちゅう</rt></ruby></p>
                        <p style=""><button class="btn_continue" onclick="status_btn();" >再開</button></p>
                        <p style="color:#ffff00;margin-top:10px;"><ruby>再開後<rt>さいかいご</rt></ruby>は「<ruby>員数<rt>かず</rt></ruby>」を<ruby>確認<rt>かくにん</rt>！</ruby></p>
                    </div>
                    <input id="entry_status_`+item+`" class="entry_status" value="not_done" style="display:none;"></div>
                </div>
            </div>`;
        });
        
        $("#ui-tab").append(t_item);
        bk_dataSet();
        let lot_str = $("#Lotコード").val();
        let min_date = "20"+lot_str.slice(0,2)+"/"+lot_str.slice(2,4)+"/"+lot_str.slice(4);
        if(lot_str==""){
            min_date="<?=date("Y/m/d",strtotime("-1 year"))?>";
        }
        $.each(tab_set,function(key,item){
            if($("#作業工程").val().indexOf("組立")>-1){
                $(".add-parts").on("keyup",function(e){
                    if(e.keyCode===13){
                        // getAssemblyInfo(e.target.value,e.target.id);
                        getPartsRfidInfo(e.target.value);
                    }
                });
                // $(".add-parts").on("focusout",function(e){
                //     if(e.target.value!==""){
                //         getAssemblyInfo(e.target.value,e.target.id);
                //     }
                // });

                $(".add-parts").on("click",function(e){
                    if(e.target.value=="" && !video){
                        let this_id = e.target.id;
                        od(this_id,"部品");
                    }
                });

                let list_parts=bk_data.list_parts.split(",");
                $.each(list_parts,function(a,b){
                    $("#成形日_"+item+"_"+b).datepicker({
                        minDate:min_date,
                        maxDate:"<?= date("Y/m/d"); ?>",
                        onSelect: function(dateText, inst) {
                            var lot = dateText.replace( /-/g,"").substr(2);
                            $("#成形日_"+item+"_"+b).val(lot);
                            // focus_control();
                            //$("#moldlot").val(lot);
                        }
                    });
                });
                // focus_assembly("auto");
            }else{
                $("#成形日_"+item).datepicker({
                    minDate:min_date,
                    maxDate:"<?= date("Y/m/d"); ?>",
                    onSelect: function(dateText, inst) {
                        var lot = dateText.replace( /-/g,"").substr(2);
                        $("#成形日_"+item).val(lot);
                        focus_control();
                        //$("#moldlot").val(lot);
                    }
                });
                if(counter_mode=="count_down"){
                    $("#受入数_"+item).on('keypress',function(e){
                        if(e.keyCode===13){
                            let check_time=$("#状態").button( "option", "label" );
                            focus_control()
                        }
                    });
                }else{
                    $("#完成数_"+item).on('keypress',function(e){
                        if(e.keyCode===13){
                            let check_time=$("#状態").button( "option", "label" );
                            focus_control()
                        }
                    });
                }
            }
    
        });
        // $("#item_"+last_item).focus();

        $(".tab_content input").on("focusin",function(e){
            $(this).css({'background-color':'#ccc'});
            $(this).css({'color':'#000'});
            setTimeout(() => {
                if(e.target.className.indexOf("quantity")>-1){
                    open_num_key(e.target.id);
                    $(this).css({'background-color':'#ccc'});
                    $(this).css({'color':'#000'});
                }
            }, 0);
        });

        $(".tab_content input").on("focusout",function(e){
            $(this).css({'background-color':''});
            $(this).css({'color':'#fff'});
        });

        var ditem_val = $("#ui-tab input");
        var now_data = [];
        $.each(ditem_val,function(key,item){
            if(item.value>0){
                now_data[item.id]=item.value;
            }
        });
        $("#ditem_list").val("");
        if(ac=="firstload" || $("#ditem_list").val()==""){
            get_ditem(ac,null);
        }else if(ac=="reload"){
            add_bad_list($("#ditem_list").val(),ac)
        }
        $('#ui-tab').tabs();
        $("button").button();
        $(".btn").button();
        $(".btn").removeClass("ui-resizable");
        if(view_location){
            $("#"+view_location).click();
        }else{
            if(ac=="firstload"){
                $("#tab_"+first_item).click();
            }else{
                $("#tab_"+last_item).click();
                $("#view_only_"+last_item).css({"display":"none"});
            }
        }
        setTimeout(() => {
            let tabs=$(".entry_status");
            let c=0;
            for(i=0;i<tabs.length;i++){
                if(tabs[i].value!="done"){
                    c++;
                }
            }
            if(c==0){
                kanban_exchange('camera'); 
            }
        }, 100);
        setTimeout(() => {
            openDigital(false);
        }, 0);
        if($("#link_to_proc").val()=="1"){
            to_proc_id_show();
        }
	}

    function to_proc_id_show(){
        $(".to_link_id").show();
    }

    // 組立対応のfocusイベント
    function focus_assembly(ac,id){
        let tab_set = JSON.parse(localStorage.getItem("Tabs"));
        tab_set = tab_set.split(",");
        let bk_data =JSON.parse(localStorage.getItem("bk_data"));
        let list_parts=bk_data.list_parts.split(",");
        let ww = $(window).width();
        let cflg = false;
        if(ac=="auto"){
            $.each(tab_set,function(key,item){
                if($("#完成数_"+item).val()=="" || $("#完成数_"+item).val()==0){
                    $("#完成数_"+item).focus();
                    // return;
                }else{
                    for(i=0;i<list_parts.length;i++){
                        if($("#rfid_"+item+"_"+list_parts[i]).val()==""){
                            cflg=true;
                            if(!video){
                                od("rfid_"+item+"_"+list_parts[i],list_parts[i]);
                            }
                            break;
                        }
                    }
                }
         
            });
            if(!cflg){
                if(id){
                    let sp_id = id.split("_");
                    let after_assem = 0;
                    $("#受入数_"+sp_id[1]).val($("#完成数_"+sp_id[1]).val());
                    $("#状態").focus();
                    $("#状態").click();
                }
          
            }
            return false;
        }else if(ac == "manual"){
            if(id){
                let sp_id = id.split("_");
                if(!video){
                    od(id,"部品"+sp_id.slice(-1));
                }
            }
        }
  
    }

    // カウンターTAB作成
    function addListItem(item){
        var t_item='';
        var ul_item='';
        ul_item+='<li class="tabs" name="'+item+'" id="ontab_'+item+'" onclick="switch_tab();"><a id="tab_'+item+'" href="#item_'+item+'"><span>'+item+'</span></a></li>';
        $("#ui-tab ul").append(ul_item);
        let mold_date = "";
        if($("#成形日").val()!=""){
            mold_date = $("#成形日").val().replace( /-/g,'/').substr(2);
        }
        let ip_class = "input quantity quantity_input";    
        t_item+=`<div id="item_`+item+`">
            <div class="tab_content">`;
                if($("#作業工程").val().indexOf("組立")>-1){
                    if(bk_data.list_parts!==""){
                        let list_parts=bk_data.list_parts.split(",");
                        $.each(list_parts,function(a,b){
                            t_item+=`
                            <p>
                                <button style="" type="button" onclick="add_parts_cam('rfid_`+item+`_`+b+`');" class="btn-parts">部品かんばん✚</button>
                                <label for="rfid_`+item+`_`+b+`">RFID：</label><input type="text" id="rfid_`+item+`_`+b+`" class="add-parts" value ="" name="数基-本][`+b+`][rfid" placeholder="" readonly="readonly" style="width:140px;ime-mode: disabled;" />
                                <label for="品目コード_`+item+`_`+b+`">部品`+(a+1)+`：</label><input type="text" id="品目コード_`+item+`_`+b+`" value ="`+b+`" name="数基-本][`+b+`][品目コード" placeholder="" style="width:140px;ime-mode: disabled;" readonly="readonly" />
                                <label for="成形日_`+item+`_`+b+`">成形日：</label><input type="text" id="成形日_`+item+`_`+b+`" value ="`+mold_date+`" name="数基-本][`+b+`][成形日" placeholder="" style="width:120px;ime-mode: disabled;" readonly="readonly" />
                                <label for="受入数_`+item+`_`+b+`">受入数：</label><input id="受入数_`+item+`_`+b+`" type="tel" class="parts-num" pattern="[0-9]*" value="0" name="数基-本][`+b+`][受入数" placeholder="" readonly="readonly" />
                            </p>`;
                            t_item+=`<input type="text" value="" id="原料ロット_`+item+`_`+b+`" name="カンバン][`+b+`][原料ロット" readonly="readonly" style="display:none;" />
                            <input type="text" value="" id="前工程ID_`+item+`_`+b+`" name="カンバン][`+b+`][前工程ID" readonly="readonly" style="display:none;"/>`;
                        })
                    }

                    t_item+=`
                    <label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" type="tel" pattern="[0-9]*" value="" name="数基-本][`+item+`][受入数" class=""  placeholder=""  readonly="readonly" />
                    <label for="完成数_`+item+`">完成数：</label><input id="完成数_`+item+`"  class="`+ip_class+`" type="text" value="" name="数基-本][`+item+`][完成数" class="perfect"  placeholder="" readonly="readonly"  />
                    <label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste" placeholder="" readonly="readonly" />`;
                }else{
                    t_item+=`<label for="成形日_`+item+`">成形日：</label><input type="text" id="成形日_`+item+`" value ="`+mold_date+`" name="数基-本][`+item+`][成形日" placeholder="" style="width:120px;ime-mode: disabled;" readonly="readonly" />`;
                    if(counter_mode=="count_down"){
                        t_item+=`
                        <label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" onblur="setupTab(this.id);" type="tel" pattern="[0-9]*" value="`+$("#前良品").val()+`" name="数基-本][`+item+`][受入数" class="`+ip_class+`" placeholder="" readonly="readonly" />
                        <label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste"  placeholder="" readonly="readonly" />
                        <label for="完成数_`+item+`">完成数</label><input id="完成数_`+item+`" type="text" value="`+$("#前良品").val()+`" name="数基-本][`+item+`][完成数" class="perfect"  placeholder="" readonly="readonly"  />`;
                    }else{
                        t_item+=`
                        <label for="受入数_`+item+`">受入数：</label><input id="受入数_`+item+`" type="tel" pattern="[0-9]*" value="`+$("#前良品").val()+`" name="数基-本][`+item+`][受入数" class="" placeholder="" readonly="readonly" />
                        <label for="廃棄数_`+item+`">廃棄数</label><input id="廃棄数_`+item+`" type="text" value="0" name="数基-本][`+item+`][廃棄数" class="waste" placeholder="" readonly="readonly" />
                        <label for="完成数_`+item+`">完成数</label><input id="完成数_`+item+`"  class="`+ip_class+`" type="text" value="`+$("#前良品").val()+`" onblur="setupTab(this.id);" name="数基-本][`+item+`][完成数" class="perfect" placeholder="" readonly="readonly"  />`;
                    }
                    t_item+=`<input type="hidden" value="`+$("#原料ロット").val()+`" id="原料ロット_`+item+`" name="カンバン][`+item+`][原料ロット" readonly="readonly"/>
                    <input type="hidden" value="`+$("#rfid").val()+`" id="RFID_`+item+`" name="カンバン][`+item+`][RFID" readonly="readonly"/>
                    <input type="hidden" value="`+$("#前工程ID").val()+`" id="前工程ID_`+item+`" name="カンバン][`+item+`][前工程ID" readonly="readonly"/>`;
                }
                        
                    t_item+=`
                        <input class="fr" type="text" value="0" id="員数ミス_`+item+`" name="数基-本][`+item+`][員数ミス" readonly="readonly"  />
                        <button class="fr btn" onclick="c_miss_dw('`+item+`');" style="width:50px;height:50px;">-</button>
                        <button class="fr btn" onclick="c_miss_up('`+item+`');" value="`+item+`" style="width:50px;height:50px;" >+</button>
                        <label class="fr" style="margin: 12px 5px 0 0">員数ミス</label>
                        <div style="clear:both;"></div>
                    </div>
                    <hr style="margin:8px 0 10px 0;"/>
                    <div id="tab_ditem_`+item+`" class="t_cont_inn"></div>
                    <hr style="margin:5px 0 10px 0;"/>
                    <textarea id="備考_`+item+`" placeholder="備考" class="remark" name="数データ][`+item+`][備考"></textarea>
                    <div id="view_only_`+item+`" class=""></div>
                    <div id="view_pause_`+item+`" class="" style="display:none;">
                        <p style="padding-top:50px;">一時停止中</p>
                        <p style=""><button class="btn_continue" onclick="status_btn();" >再開</button></p>
                    </div>
                    <input id="entry_status_`+item+`" class="entry_status" value="not_done" style="display:none;"></div>
                </div>
            </div>
        `;

        $("#ui-tab").append(t_item);
        // $(".quantity_input").on('focusin',function(){
        //     open_num_key($(this).attr('id'));
        // });
        let lot_str = $("#Lotコード").val();
        let min_date = "20"+lot_str.slice(0,2)+"/"+lot_str.slice(2,4)+"/"+lot_str.slice(4);

        if(lot_str==""){
            min_date="<?=date("Y/m/d",strtotime("-1 year"))?>";
        }

        if($("#作業工程").val().indexOf("組立")>-1){
            let list_parts=bk_data.list_parts.split(",");
            $.each(list_parts,function(a,b){
                $("#成形日_"+item).datepicker({
                    minDate:min_date,
                    maxDate:"<?= date("Y/m/d"); ?>",
                    onSelect: function(dateText, inst) {
                        var lot = dateText.replace( /-/g,"").substr(2);
                        $("#成形日_"+item).val(lot);
                    }
                });
                $("#受入数_"+item).on('keypress',function(e){
                    if(e.keyCode===13){
                        let check_time=$("#状態").button( "option", "label" );
                    }
                });
            });
        }else{
            $("#成形日_"+item).datepicker({
                minDate:min_date,
                maxDate:"<?= date("Y/m/d"); ?>",
                onSelect: function(dateText, inst) {
                    var lot = dateText.replace( /-/g,"").substr(2);
                    $("#成形日_"+item).val(lot);
                    focus_control();
                    //$("#moldlot").val(lot);
                }
            });
            $("#受入数_"+item).on('keypress',function(e){
                if(e.keyCode===13){
                    let check_time=$("#状態").button( "option", "label" );
                    focus_control();
                }
            });
        }

        $(".tab_content input").on("focusin",function(e){
            $(this).css({'background-color':'#ccc'});
            $(this).css({'color':'#000'});
            setTimeout(() => {
                if(e.target.className.indexOf("quantity")>-1){
                    open_num_key(e.target.id);
                    $(this).css({'background-color':'#ccc'});
                    $(this).css({'color':'#000'});
                }
            }, 0);
        });

        $(".tab_content input").on("focusout",function(e){
            $(this).css({'background-color':''});
            $(this).css({'color':'#fff'});
        });

        var ditem_val = $("#ui-tab input");
        var now_data = [];
        $.each(ditem_val,function(key,item){
            if(item.value>0){
                now_data[item.id]=item.value;
            }
        });
        var num = 0;
        if($("#ditem_list").val()==""){
            get_ditem("add",item);
        }else{
            add_bad_list($("#ditem_list").val(),"add",item)
        }
     
        $("#ui-tab").tabs("destroy");
        $('#ui-tab').tabs();
        $("button").button();
        $(".btn").button();
        setTimeout(() => {
            $("#tab_"+item).click();
        }, 0);
	}

    // 不良品の内容作成
    function add_bad_list(d,ac,add_item){
        // console.log(d);
        let tab_set = JSON.parse(localStorage.getItem("Tabs"));
        tab_set = tab_set.split(",");
        let sp_ditem_check = new Set(d.split(","));
        let sp_ditem = [...sp_ditem_check];
        let ditem_val = $("#ui-tab input");
        let now_data = [];
        $.each(ditem_val,function(key,item){
            if(item.value>0){
                now_data[item.id]=item.value;
            }
        });
        if(ac=="firstload" || ac=="reload"){
            $.each(tab_set,function(key,kind){
                //$("#tab_ditem_"+kind).html("");
                $.each(sp_ditem,function(key,val){
                    var k = val+'_'+kind;
                    var n = "数データ]["+kind+"]["+val+'';
                    var num = 0;
                    if (now_data[k]){
                        num = now_data[k];
                    }
                    var f_item='';
                    f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
                    f_item+='<p class="b_line">'+val+'</p>';
                    f_item+='<p class="btn btn_plus box_content" onclick="c_up(`'+k+'`)">+</p>';
                    f_item+='<p class="btn btn_minus box_content" onclick="c_dw(`'+k+'`)">-</p>';
                    f_item+='<p class="box_content" ><input type="text" name="'+n+'" value="'+num+'" placeholder="" readonly="readonly" class="n_input" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/></p>';
                    f_item+='</div>';
                    $("#tab_ditem_"+kind).append(f_item);
                });
                $("#tab_ditem_"+kind).append("<div style='clear:both;'></div>");
            });
        }else if(ac=="add"){
            let sp_ditem_check = new Set(d.split(","));
            let sp_ditem = [...sp_ditem_check];
            let kind =  add_item;
            $("#tab_ditem_"+kind).html("");
            $.each(sp_ditem,function(key,val){
                var k = val+'_'+kind;
                var n = "数データ]["+kind+"]["+val+'';
                var num = 0;
                if (now_data[k]){
                    num = now_data[k];
                }
                var f_item='';
                f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
                f_item+='<p class="b_line">'+val+'</p>';
                f_item+='<p class="btn btn_plus box_content" onclick="c_up(`'+k+'`)">+</p>';
                f_item+='<p class="btn btn_minus box_content" onclick="c_dw(`'+k+'`)">-</p>';
                f_item+='<p class="box_content" ><input type="text" name="'+n+'" value="'+num+'" placeholder="" readonly="readonly" class="n_input" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/></p>';
                f_item+='</div>';
                $("#tab_ditem_"+kind).append(f_item);
            });
            $("#tab_ditem_"+kind).append("<div style='clear:both;'></div>");
        }else if(ac=="reload_ditem"){
            let sp_ditem_check = new Set(d.split(","));
            let sp_ditem = [...sp_ditem_check];
            let kind =  add_item;
            $("#tab_ditem_"+kind).html("");
            $.each(sp_ditem,function(key,val){
                var k = val+'_'+kind;
                var n = "数データ]["+kind+"]["+val+'';
                var num = 0;
                if (now_data[k]){
                    num = now_data[k];
                }
                var f_item='';
                f_item+='<div class="box" onclick="$(`#'+k+'`).focus();">';
                f_item+='<p class="b_line">'+val+'</p>';
                f_item+='<p class="btn btn_plus box_content" onclick="c_up(`'+k+'`)">+</p>';
                f_item+='<p class="btn btn_minus box_content" onclick="c_dw(`'+k+'`)">-</p>';
                f_item+='<p class="box_content" ><input type="text" name="'+n+'" value="'+num+'" placeholder="" readonly="readonly" class="n_input" style="ime-mode: disabled;" autocomplete="off" id="'+k+'"/></p>';
                f_item+='</div>';
                $("#tab_ditem_"+kind).append(f_item);
            });
            $("#tab_ditem_"+kind).append("<div style='clear:both;'></div>");
        }
        $(".btn").button();
        var num;
        $(".n_input").on("click",function(e){
            open_num_key(e.target.id);
        });
        $(".n_input").focusin(function(e){
            $(this).css({'background-color':'#ccc'});
            if(parseInt($(this).val())>0 && $(this).attr('name').indexOf('数データ') != -1){
                $(this).css({'color':'#ff0000'});
            }else{
                $(this).css({'color':'#000'});
            }
        })
        
        $(".n_input").focusout(function(e) {
            $(this).css({'background-color':'','color':''});
            if(parseInt($(this).val())>0 && $(this).attr('name').indexOf('数データ') != -1){
                $(this).css({'color':'#ffff00'});
            }else{
                $(this).css({'color':''});
            }
        });
        $(".btn").removeClass("ui-resizable");
        bk_dataSet();
    }

    // ???
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

    // 不良数計算
    function sum_defective(kind=null){
        if (kind==null){ return false; }
        var kind = kind;
        if ( kind.indexOf('_') != -1 ) {
            fr = kind.split("_")[0];
            kind = kind.split("_").slice(-1)[0]; 
        }
        var line = $("#tab_ditem_"+kind+" input");
        var num = 0;
        let ditem ="";
        $.each(line,function(key,val){
            if(val.value>0){
                num += parseInt(val.value);
                ditem += val.id+"=>"+val.value+",";
            }
        });
        $("#廃棄数_"+kind).val(num);

        if($("#作業工程").val().indexOf("組立")>-1){
            let tab_num = parseInt($("#完成数_"+kind).val());
            if(parseInt($("#員数ミス_"+kind).val())>0){
                num = num + parseInt($("#員数ミス_"+kind).val());
            }
            let anum = tab_num + num;
            $("#受入数_"+kind).val(anum);
        }else{
            //良品数の算出
            if(counter_mode=="count_down"){
                if( $("#受入数").val()!="" && $("#受入数").val() !="0" ){
                    if(parseInt($("#員数ミス_"+kind).val())>0){
                        num = num + parseInt($("#員数ミス_"+kind).val());
                    }
                    let tab_num = parseInt($("#受入数_"+kind).val());
                    let gnum = tab_num - num;
                    $("#完成数_"+kind).val(gnum);
                }
            }else{
                if(parseInt($("#員数ミス_"+kind).val())>0){
                     num = num + parseInt($("#員数ミス_"+kind).val());
                }
                let tab_num = parseInt($("#完成数_"+kind).val());
                let anum = tab_num + num;
                $("#受入数_"+kind).val(anum);
            }
        }
        data_backup(); 
    }

    // 表示されたTABで数を集計
    function sum_this_tab(id){
        if (id==null){ return false; }
        var line = $("#tab_ditem_"+id+" input");
        var num = 0;
        let ditem ="";
        $.each(line,function(key,val){
            if(val.value>0){
                num += parseInt(val.value);
                ditem += val.id+"=>"+val.value+",";
            }
        });
        $("#廃棄数_"+id).val(num);

        //良品数の算出
        if(counter_mode=="count_down"){
            if( $("#受入数").val()!="" && $("#受入数").val() !="0" ){
                // num = num + parseInt($("#員数ミス_"+id).val());
                let tab_num = parseInt($("#受入数_"+id).val());
                var gnum = tab_num - num;
                $("#完成数_"+id).val(gnum);
            }
        }else{
            // num = num + parseInt($("#員数ミス_"+id).val());
            let tab_num = parseInt($("#完成数_"+id).val());
            var anum = tab_num + num;
            $("#受入数_"+id).val(anum);
        }
        data_backup();
    }

    //アプリを呼び出さないの不具合項目
    var ml_d_exit = ["収納ミス","自損","サンプル"];
    // プラスのclick
    function c_up(id){
        if(check_status()==true){
            let sp_btn_id = id.split("_");
            if($("#mes_app_set")[0].checked==true && ml_d_exit.indexOf(sp_btn_id[0])==-1){
                tray_posi(sp_btn_id[0]);
            }
            var val=parseInt($("#"+id).val());
            $("#"+id).val(val+1);
            sum_defective(id);
        }
    }

    // マイナスのclick
    function c_dw(id){
        if(check_status()==true){
            var val=parseInt($("#"+id).val())-1;
            if (val < 0) val=0;
            $("#"+id).val(val);
            sum_defective(id);
        }
    }

    // 員数ミス(プラス)のclick
    function c_miss_up(kind=null){
        if(check_status()==true){
            if (kind==null){ return false; }
            var kind = kind;
            var val=parseInt($("#受入数_"+kind).val());
            var cpl_num=parseInt($("#完成数_"+kind).val());
            if(counter_mode=="count_down"){
                if($("#員数ミス_"+kind).val()<0){
                    $("#受入数_"+kind).val(val-1);
                }
                $("#完成数_"+kind).val(cpl_num-1);
            }else{
                $("#受入数_"+kind).val(val-1);
            }
            // $("#受入数_"+kind).val(parseInt($("#受入数_"+kind).val())+1);
            var vals=parseInt($("#員数ミス_"+kind).val());
            $("#員数ミス_"+kind).val(vals+1);
            if ($("#員数ミス_"+kind).val() > 0) {
                $("#員数ミス_"+kind).css({'color':'#ffff00'})
            } else if($("#員数ミス_"+kind).val() == 0) {
                $("#員数ミス_"+kind).css({'color':''})
            }else{
                $("#員数ミス_"+kind).css({'color':'#ff0000'})
            }
            data_backup();
            //sendMes("Item,");
        }
    }
    
    // 員数ミス(マイナス)のclick
    function c_miss_dw(kind=null){
        if(check_status()==true){
            if (kind==null){ return false; }
            var kind = kind;
            var val=parseInt($("#受入数_"+kind).val());
            var cpl_num=parseInt($("#完成数_"+kind).val());
            // if(counter_mode=="count_down"){
            //     $("#完成数_"+kind).val(cpl_num+1);
            // }else{
            //     $("#受入数_"+kind).val(val+1);
            // }
            if($("#員数ミス_"+kind).val()<=0){
                $("#受入数_"+kind).val(val+1);
            }
            $("#完成数_"+kind).val(cpl_num+1);
            // $("#受入数_"+kind).val(parseInt($("#受入数_"+kind).val())+1);
            // $("#受入数_"+kind).val(parseInt($("#受入数_"+kind).val())-1);
            var vals=parseInt($("#員数ミス_"+kind).val());
            $("#員数ミス_"+kind).val(vals-1);
            if ($("#員数ミス_"+kind).val() < 0) {
                $("#員数ミス_"+kind).css({'color':'#ff0000'})
            } else if($("#員数ミス_"+kind).val() == 0) {
                $("#員数ミス_"+kind).css({'color':''})
            }else{
                $("#員数ミス_"+kind).css({'color':'#ffff00'})
            }
            data_backup();
            //sendMes("Item,");
        }
    }

    // 不良項目の追加
    function defactitemadd(){
        let workitem=$("#作業工程").val().split(":");
        let search_key=workitem.slice(1).join(":");
        let ww = $(window).width()-10;
        loadingView(true);
        $.ajax({
            type: 'GET',
            url: "/LaborReport/Ditemset?num=0",
            // url: "/LaborReport/BadItemList",
            // async : false,
            // timeout:5000,
            data:{
                itemcode:$("#案件コード").val(),
                workitem:encodeURIComponent(search_key),
                plant_id:$("#工場ID").val()
            },
            dataType: 'html',
            success: function(d) {
                var options = {"title":"追加する項目にチェックを入れて「項目を反映」ボタンを実行して下さい。",
                    width: ww,
                };
                var msg=d;
                $("#message").html(msg);
                $("#btn_entrydefactiv").button();
                $( "#alert" ).dialog( "option",options);
                loadingView(false);
                $("#alert").dialog( "open" );
            },
            error: function (xhr, textStatus, errorThrown) { 
                // エラーと判定された場合
                loadingView(false);
                if(textStatus === 'timeout'){     
                    alert("通信が実施されていない！！！\nもう一度やり直してください。");
                }else{
                    alert(xhr);
                }
            }
        });
    }

    // 現在の不良品項目内容を保存
    function entry_ditem(){
        let workitem=$("#作業工程").val().split(":");
        let search_key=workitem.slice(1).join(":");
		var check = $('[class="dedit_item"]:checked').map(function(){
  		    return $(this).val();
		}).get();
        loadingView(true);
		$.ajax({
			type: 'GET',
			url: "/LaborReport/Ditemset?ac=Entry",
			dataType: 'text',
            // async : false,
            // timeout:5000,
			data:{
				itemcode:$("#案件コード").val(),
                workitem:encodeURIComponent(search_key),
				inidata:check
			},
			success: function(d) {
                let this_tab = $(".ui-state-active").attr('id');
                let this_id = this_tab.split("_")[1];
                get_ditem('add',this_id);
                $("#alert").dialog( "close" );
                loadingView(false);
            },
            error: function (xhr, textStatus, errorThrown) { 
                // エラーと判定された場合
                loadingView(false);
                if(textStatus === 'timeout'){     
                    alert("通信が実施されていない！！！\nもう一度やり直してください。");
                }else{
                    alert(xhr);
                }
            }
		});

	}

    // 不良項目内容を抽出
    async function get_ditem(ac,kind){
        let workitem=$("#作業工程").val().split(":");
        let search_key=workitem.slice(1).join(":");
        let num = 0;
        loadingView(true);
        $.ajax({
            type: 'GET',
            url: "/LaborReport/BadItemList",
            //async : false,
            dataType: 'html',
            // timeout:5000,
            data:{num:num,
                itemcord:$("#案件コード").val(),
                workitem:encodeURIComponent(search_key),
                'ac':'Count'
            },
            success: function(d) {
                // console.log(d);
                loadingView(false);
                bk_dataSet();
                $("#ditem_list").val(d);
                add_bad_list(d,ac,kind);
                setTimeout(() => {
                    data_backup();  
                }, 0);
            },
            error: function (xhr, textStatus, errorThrown) { 
                // エラーと判定された場合
                loadingView(false);
                if(textStatus === 'timeout'){     
                    alert("通信が実施されていない！！！\nもう一度やり直してください。");
                }else{
                    alert(xhr);
                }
            }
        });
    }

    // ブラウザのローカルストレージでデータを保存
    var bk_data={};
    function data_backup(){
        if($("#ditem_list").val()==""){
            return;
        }
        var d = $("input,textarea");
        $.each(d,function(key,data){
            if(data.value>0 || data.value!="" || data.id=="rounding_rfid"){
                bk_data[data.id] = data.value;
            }
        });
        bk_data["view_location"]=$(".ui-state-active").attr('aria-labelledby');
        var bk_data_old =JSON.parse(localStorage.getItem("bk_data"));
        if(bk_data_old){
            if(bk_data_old["開始時間"] != undefined){
                if($("#作業時間").val()=="0"){
                    bk_data["開始時間"] = nowDT("dt");
                }else{
                    bk_data["開始時間"] = bk_data_old["開始時間"];
                }
            }else{
                bk_data["開始時間"] = nowDT("dt");
            }
            localStorage.setItem("bk_data",JSON.stringify(bk_data));
            localStorage.setItem("counter_mode",counter_mode);
        }
    }

    function bk_dataSet(){
        var bk_data =JSON.parse(localStorage.getItem("bk_data"));
        if(bk_data){
            $.each(bk_data,function(key,item){
                // console.log(key+":"+item)
                $("#"+key).val(item);
                if($("#"+key).attr('name')){
                    if($("#"+key).attr('name').indexOf('数データ][')>-1 && item > 0){
                        $("#"+key).css({"color":"yellow"})
                    }
                }
            });
        }
        //現状TZAAの2型だけ対応するので、一旦固定されています。
        if($("#品名").val()=="TZAA" && $("#型番").val()=="2"){
            $(".mes_app_gr").show();
            if($("#ml_flg").val()=="1"){
                $("#mes_app_set").prop("checked",true);
                $("#btn_change_tray").button("enable");
            }else{
                $("#mes_app_set").prop("checked",false);
                $("#btn_change_tray").button("disable");
            }
        }else{
            $("#mes_app_set").prop("checked",false);
            $("#btn_change_tray").hide();
            $(".mes_app_gr").hide();
        }
    }

    function change_machine_learning_mode(flag){
        if(flag==true){
            $("#ml_flg").val("1");
            $("#mes_app_set").prop("checked",true);
            $("#btn_change_tray").button("enable");
        }else{
            $("#ml_flg").val("0");
            $("#mes_app_set").prop("checked",false);
            $("#btn_change_tray").button("disable");
        }
    }

    // 作業の終了の処理
    function end_work(act){
        loadingView(true);
        var complete_flag = false;
        var middle_stop = false;
        let tabs = JSON.parse(localStorage.getItem("Tabs"));
        tabs=tabs.split(",");
        let this_tab = $(".ui-state-active");
        let this_id = this_tab[0].attributes.id.value;
        sp_this_id=this_id.split("_");
        let check_val="continue";
        if(act!="re_entry"){
            check_val=check_entry_tabs();
        }
        if(check_val=="continue"){
            //普通の処理
        }else if(check_val=="add_tab"){
            check_entry("add_tab");
            return;
        }else if(check_val=="goto_end"){
            let msg = "このデータが登録しました。<br>最新TABに戻します。";
            let aw = $( window ).width()-200;
            var options = {"title":"登録出来ません。",
                width: aw,
                position:["center",100],
                buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                    $("#tab_"+tabs[tabs.length-1]).click();
                }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            loadingView(false);
            $("#alert").dialog( "open" );
            return;
        }else{
            check_entry(check_val);
            return;
        }


        var s ={},all_sum_tab={};
        var d =$("input,textarea");
        var defective = "",all_defective="";
        var f = [];

        let c={};
        let get_total =JSON.parse(localStorage.getItem("totalling"));

        if($("#作業工程").val().indexOf("組立")==-1){
            //組立以外の際
            c["受入数"]=$("#受入数_"+sp_this_id[1]).val();
            c["成形日"]=$("#成形日_"+sp_this_id[1]).val();
            if($("#link_to_proc").val()=="1" && $("#完成数_"+sp_this_id[1]).val()!="0"){
                c["仕掛ID連携"]=$("#仕掛ID連携_"+sp_this_id[1]).val();
            }
            check_msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
            var errc=0;
            $.each(c,function(key) {
                if($.trim(c[key])==""||$.trim(c[key])=="0"){
                    check_msg = check_msg +"<li>"+key+"</li>\n";
                    errc++;
                }
            });
            let ww = $(window).width()-40;
            if(errc>0){
                var options = {"title":"確認",
                    position:["center",50],
                    width:ww,
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
                };
                $("#message").html(check_msg);
                $( "#alert" ).dialog( "option",options);
                loadingView(false);
                $( "#alert" ).dialog( "open" )
                return;
            }

            // console.log(sp_this_id);
            console.log(d);
            $.each(d,function(k,item){
                let sp_id = item.id.split("_");
                if(sp_id[1]==sp_this_id[1] && item.value!="0" && item.value!=""){
                    if(item.name.indexOf('仕掛ID連携') !== -1){
                        s['仕掛ID連携']=item.value;
                    }else{
                        s[sp_id[0]]=item.value;
                        if(item.name.indexOf('数基-本') == -1 && item.name.indexOf('成形日') == -1 && item.name.indexOf('備考') == -1 && item.name.indexOf('カンバン') == -1){
                            defective+= sp_id[0]+"=>"+item.value+",";
                        }
                    }
                }
                if( item.name.indexOf('基本') !== -1 && item.name.indexOf('仕掛ID連携')==-1) {
                    s[item.name]=item.value;
                }
                // 複数tab登録の処理
                if( item.name.indexOf('数基-本') !== -1){
                    if((parseInt(item.value)>0 || item.name.indexOf('員数ミス')!=-1) && item.name.indexOf('成形日')===-1){
                        all_sum_tab[item.name]=item.value;
                        all_sum_tab[sp_id[0]] = sum_tabs(sp_id[0]+"_");
                    }else if(item.name.indexOf('成形日')!=-1){
                        all_sum_tab[item.name]=item.value;
                    }
                }else if ( item.name.indexOf('基本') !== -1 ) {
                    if(item.name.indexOf('キャビ')==-1){
                        all_sum_tab[item.name]=item.value;
                        // t[item.name]=item.value;
                    }
                }else if ( item.name.indexOf('備考') != -1 && item.value!="") {
                    all_sum_tab[item.name]=item.value;
                    all_sum_tab[sp_id[0]] = sum_tabs(sp_id[0]+"_");
                }else if(item.name!="" && item.value!="" && parseInt(item.value) > 0 && item.name.indexOf('カンバン') === -1){
                    all_sum_tab[item.name]=item.value;
                    if(jQuery.inArray(sp_id[0],f) === -1){
                        all_defective+= sp_id[0]+"=>"+sum_tabs(sp_id[0]+"_")+",";
                        f.push(sp_id[0]);
                    }
                }
            });

            // 複数tab登録の処理
            // var tabs = JSON.parse(localStorage.getItem("Tabs"));
            all_sum_tab["キャビ"] = tabs;

            s["不良"] = defective.substr(0, defective.length - 1);
            all_sum_tab["不良"] = all_defective.substr(0, all_defective.length - 1);
            var bk_data =JSON.parse(localStorage.getItem("bk_data"));
            if(bk_data){
                s["開始"] = bk_data["開始時間"];
                // if(bk_data["rfid"]){
                //     s["RFID"] = bk_data["rfid"];
                // }
                // if(bk_data["前工程ID"]){
                //     s["前工程ID"] = bk_data["前工程ID"];
                // }
                all_sum_tab["開始"]=bk_data["開始時間"];
            }
            if(get_total){
                if(get_total["基本][作業時間"]){
                    all_sum_tab["基本][作業時間"]=parseInt(get_total["基本][作業時間"])+parseInt($("#作業時間").val());
                }
                all_sum_tab["開始"]=get_total["開始"];
            }

            s["終了"] = nowDT("dt");
            all_sum_tab["終了"] = nowDT("dt");

            let width ="49";
            // if(act!=="かんばん交換"){
            //     width ="32"
            // }
            let cofirm_msg='<div>'
            let msg_table1="<table class='type03' style='float:left;width:"+width+"%;'><thead><tr><th colspan='2'>Lot情報</th></tr></thead>";
            let msg_table2="<table class='type03' style='float:left;width:"+width+"%;'><thead><tr><th>各キャビ</th><th>個数</th></tr></thead>";
            let msg_table3="<table id='calc_table' class='type03' style='float:left;width:"+width+"%;'><thead><tr><th colspan='2'>計算</th></tr></thead>";
            $.each(s,function(name,value){
                sp_name=name.split("][");
                if(sp_name[0]=="基本"){
                    msg_table1+='<tr><td>'+sp_name[1]+'</td><td>'+value+'</td></tr>'
                }else if(sp_name[0]=="RFID" || sp_name[0]=="前工程ID" || sp_name[0]=="原料ロット"){
                    msg_table1+='<tr><td>'+name+'</td><td>'+value+'</td></tr>'
                }else if(sp_name[0]=="数データ" || name=="数基-本"){
                    msg_table2+='<tr><td>'+sp_name[1]+'_'+sp_name[2]+'</td><td style="text-align:right">'+value+'</td></tr>'
                }else if(sp_name[0]=="仕掛ID連携" && value != ""){
                    msg_table1+='<tr><td>仕掛ID連携</td><td>'+value+'</td></tr>'
                }else{
                    msg_table3+='<tr><td>'+name+'</td><td>'+value+'</td></tr>'
                }
            });
            
            msg_table1+='</table>';
            msg_table2+='</table>';
            msg_table3+='</table>';
            cofirm_msg+=msg_table1+msg_table3+'</div><div style="clear:both;"></div>';

            $("#message").html(cofirm_msg);
        }else{
            //組立の際----------------------------------------------------------------------------------------------------------------------------------------------------
            $.each(d,function(k,item){
                let sp_id = item.id.split("_");
                if(sp_id[1]==sp_this_id[1] && item.value!="0" && item.value!="" && sp_id.length<3){
                    s[sp_id[0]]=item.value;
                    if(item.name.indexOf('数基-本') == -1 && item.name.indexOf('成形日') == -1 && item.name.indexOf('備考') == -1 && item.name.indexOf('カンバン') == -1){
                        defective+= sp_id[0]+"=>"+item.value+",";
                    }
                }
                
                // 複数tab登録の処理
                if( item.name.indexOf('数基-本') !== -1){
                    s[item.name]=item.value;
                    if(parseInt(item.value)>0 && item.name.indexOf('成形日')===-1){
                        all_sum_tab[item.name]=item.value;
                        all_sum_tab[sp_id[0]] = sum_tabs(sp_id[0]+"_");
                    }else if(item.name.indexOf('成形日')!=-1){
                        all_sum_tab[item.name]=item.value;
                    }
                }else if( item.name.indexOf('基本') !== -1 && item.name.indexOf('数基-本')==-1) {
                    s[item.name]=item.value;
                    if(item.name.indexOf('キャビ')==-1){
                        all_sum_tab[item.name]=item.value;
                        // t[item.name]=item.value;
                    }
                }else if ( item.name.indexOf('備考') != -1 && item.value!="") {
                    all_sum_tab[item.name]=item.value;
                    all_sum_tab[sp_id[0]] = sum_tabs(sp_id[0]+"_");
                }else if(item.name!="" && item.value!="" && parseInt(item.value) > 0 && item.name.indexOf('カンバン') === -1){
                    all_sum_tab[item.name]=item.value;
                    if(jQuery.inArray(sp_id[0],f) === -1){
                        all_defective+= sp_id[0]+"=>"+sum_tabs(sp_id[0]+"_")+",";
                        f.push(sp_id[0]);
                    }
                }
            });

            // 複数tab登録の処理
            // var tabs = JSON.parse(localStorage.getItem("Tabs"));
            all_sum_tab["キャビ"] = tabs;

            s["不良"] = defective.substr(0, defective.length - 1);
            all_sum_tab["不良"] = all_defective.substr(0, all_defective.length - 1);
            var bk_data =JSON.parse(localStorage.getItem("bk_data"));
            if(bk_data){
                s["開始"] = bk_data["開始時間"];
                // if(bk_data["rfid"]){
                //     s["RFID"] = bk_data["rfid"];
                // }
                // if(bk_data["前工程ID"]){
                //     s["前工程ID"] = bk_data["前工程ID"];
                // }
                all_sum_tab["開始"]=bk_data["開始時間"];
            }
            if(get_total){
                if(get_total["基本][作業時間"]){
                    all_sum_tab["基本][作業時間"]=parseInt(get_total["基本][作業時間"])+parseInt($("#作業時間").val());
                }
                all_sum_tab["開始"]=get_total["開始"];
            }

            s["終了"] = nowDT("dt");
            all_sum_tab["終了"] = nowDT("dt");

            let width ="49";
            // if(act!=="かんばん交換"){
            //     width ="32"
            // }
            let cofirm_msg='<div>'
            let msg_table1="<table class='type03' style='float:left;width:"+width+"%;'><thead><tr><th colspan='2'>Lot情報</th></tr></thead>";
            let msg_table2="<table class='type03' style='float:right;width:"+width+"%;'><thead><tr><th colspan='2'>部品</th></tr></thead>";
            let msg_table3="<table class='type03' id='calc_table' style='float:right;width:"+width+"%;'><thead><tr><th colspan='2'>完成品</th></tr></thead>";
            $.each(s,function(name,value){
                sp_name=name.split("][");
                if(value!=""){
                    if(sp_name[0]=="基本"){
                        msg_table1+='<tr><td>'+sp_name[1]+'</td><td>'+value+'</td></tr>'
                        if(sp_name[1]=="案件コード"){
                            msg_table3+='<tr><td>'+name+'</td><td>'+value+'</td></tr>'
                        }
                    }else if(sp_name[0]=="RFID" || sp_name[0]=="前工程ID" || sp_name[0]=="原料ロット"){
                        msg_table1+='<tr><td>'+name+'</td><td>'+value+'</td></tr>'
                    }else if(sp_name[0]=="数基-本"){
                        $.each(bk_data.list_parts.split(","),function(a,b){
                            let i = a+1;
                            if(sp_name[1]==b){
                                msg_table2+='<tr><td>部品'+i+'_'+sp_name[2]+'</td><td style="text-align:right">'+value+'</td></tr>'
                            }
                        })
                    }else if(sp_name[0]=="数データ"){
                        msg_table2+='<tr><td>'+sp_name[1]+'_'+sp_name[2]+'</td><td style="text-align:right">'+value+'</td></tr>'
                    }else{
                        msg_table3+='<tr><td>'+name+'</td><td>'+value+'</td></tr>'
                    }
                }
            });
            
            msg_table1+='</table>';
            msg_table2+='</table>';
            msg_table3+='</table>';
            cofirm_msg+=msg_table1+msg_table2+msg_table3+'</div><div style="clear:both;"></div>';

            $("#message").html(cofirm_msg);
   
        }
        console.log(s);
        // console.log(all_sum_tab);
        var options = {"title":"登録を実行しますか？",
            width: aw,
            position:["centetr",20],
            buttons: [{ class:"btn-right btn-confirm",text:"登録",click :function(ev) {
                if(complete_flag){
                    //RFID check
                    if($("#rfid_complete").val()==""){
                        alert("完成品のRFIDをスキャンしてください。")
                        return;
                    }else{
                        s["rfid_complete"]=$("#rfid_complete").val();
                    }
                }
                if(middle_stop && parseInt($("#num_middle_stop").val())>0){
                    //RFID check
                    // if($("#rfid_middle_stop").val()==""){
                    //     alert("完成品のRFIDをスキャンしてください。")
                    //     return;
                    // }else{
                    //     s["rfid_middle_stop"]=$("#rfid_complete").val();
                    // }

                    //数値のオーバーcheck
                    if(parseInt($("#num_middle_stop").val())>parseInt(s.完成数)){
                        var msg="入力数値がオーバーされました。";
                        var options = {"title":"確認してください！！！",
                            width: 600,
                            position:["centetr",100],
                            buttons: 
                                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                                    $( this ).dialog( "close" );
                                    return;
                                }}]
                        };
                        $("#message2").html(msg);
                        $( "#alert2" ).dialog( "option",options);
                        $( "#alert2" ).dialog( "open" );
                        return;
                    }else{
                        var num_remaining = parseInt($("#num_middle_stop").val());
                        s["残数"]=num_remaining;
                        s.完成数 = parseInt(s.完成数) - num_remaining;
                        all_sum_tab["残数"]=num_remaining;
                        all_sum_tab.完成数 = parseInt(all_sum_tab.完成数) - num_remaining;
                    }
                }
                $( this ).dialog( "close" );
                if ( $("#状態").button( "option", "label" ) == "一時停止"){
                    clearInterval(count_time);
                    $("#状態").button({ label: "再開"} );               
                }
                // console.log(s);
                // console.log(all_sum_tab);
                // return;
                loadingView(true);
                setTimeout(function() {
                    //DASにデータを登録
                    $.ajax({
                        type: 'POST',
                        url: "/RFIDReport/TallyCounter",
                        // url: "/RFIDReport/AssemblyCounterTest",
                        data:{ ac:"Entry",
                            bom_mode:$("#bom_mode").val(),
                            data:s 
                        },
                        dataType: 'json',
                        //async : false,
                        //タイムアウト: 60s
                        timeout:60000,
                        success: function(d) {
                            // console.log(d);
                            if(d && d[0]=="OK"){
                                $("#message").html("");
                                loadingView(false);
                                let this_tab_data = [$("#itemname").val()]
                                let hgpd_id_str="";
                                if(get_total){
                                    if(get_total.hgpd_id){
                                        hgpd_id_str=get_total.hgpd_id+","+d.hgpd_id;
                                    }
                                }else{
                                    hgpd_id_str=d.hgpd_id;
                                }
                                all_sum_tab["hgpd_id"]=hgpd_id_str;
                                let totalling=all_sum_tab;
                                localStorage.setItem("totalling",JSON.stringify(totalling));
                                // return;
                                // setStorageWithExpiry("entry_status",sp_this_id[1],24*3600*1000);  //期間24H
                                let tab = $(".ui-state-active a span").html();
                                $("#entry_status_"+tab).val("done");
                                $("#view_only_"+tab).addClass("view_only");
                                $("#tray_log_stack").val(0);
                                data_backup();

                                var user = $("#担当者").val();
                                sessionStorage.setItem("user",JSON.stringify(user));
                                //console.log(s);
        
                                let check=check_entry_tabs();
                                if(act == "終了"){
                                    if(sessionStorage.getItem("LotCode")!= null){
                                        var result_table = JSON.parse(sessionStorage.getItem("LotCode")).split(",");                                    
                                        var item_list = JSON.parse(sessionStorage.getItem("item_list"));
                                        var id = $("#Lotコード").val()+"-"+$("#型番").val()+"-"+$("#案件コード").val();
                                        if(item_list==null){
                                            item_list = {};
                                            item_list[id+'][受入数'] = parseInt(res_get(all_sum_tab["受入数"]));
                                            item_list[id+'][廃棄数'] = parseInt(res_get(all_sum_tab["廃棄数"]));
                                            item_list[id+'][員数ミス'] = parseInt(res_get(all_sum_tab["員数ミス"]));
                                            item_list[id+'][完成数'] = parseInt(res_get(all_sum_tab["完成数"]));
                                            item_list[id+'][作業時間'] = parseInt(res_get(all_sum_tab["基本][作業時間"]));
                                            item_list[id+'][状態'] = "未登録";
                                        }else{
                                            if(result_table.length > 1){
                                                $.each(result_table, function(key,value){
                                                    if(value==id){
                                                        item_list[value+'][受入数'] += parseInt(res_get(all_sum_tab["受入数"]));
                                                        item_list[value+'][廃棄数'] += parseInt(res_get(all_sum_tab["廃棄数"]));
                                                        item_list[value+'][員数ミス'] += parseInt(res_get(all_sum_tab["員数ミス"]));
                                                        item_list[value+'][完成数'] += parseInt(res_get(all_sum_tab["完成数"]));
                                                        item_list[value+'][作業時間'] += parseInt(res_get(all_sum_tab["基本][作業時間"]));
                                                        item_list[value+'][状態'] = "未登録";
                                                    }
                                                });
                                            }else{
                                                $.each(result_table, function(key,value){
                                                    item_list[value+'][受入数'] += parseInt(res_get(all_sum_tab["受入数"]));
                                                    item_list[value+'][廃棄数'] += parseInt(res_get(all_sum_tab["廃棄数"]));
                                                    item_list[value+'][員数ミス'] += parseInt(res_get(all_sum_tab["員数ミス"]));
                                                    item_list[value+'][完成数'] += parseInt(res_get(all_sum_tab["完成数"]));
                                                    item_list[value+'][作業時間'] += parseInt(res_get(all_sum_tab["基本][作業時間"]));
                                                    item_list[value+'][状態'] = "未登録";
                                                });
                                            }
                                        }
                                        sessionStorage.setItem("item_list",JSON.stringify(item_list));
                                    }
                                    clearInterval(count_time);
                                    $("#状態").button({ label: "再開"} );
                                    localStorage.removeItem("bk_data");
                                    localStorage.removeItem("entry_status");
                                    localStorage.removeItem("Tabs");
                                    sessionStorage.removeItem("user");
                                    sessionStorage.removeItem("counter_mode");
                                    var url = "/RFIDReport/SetBase?plant="+$("#工場").val()+"&personname="+user;
                                    location.href = url;
                                    //window.open(url);
                                }else if(act == "製品交換"){
                                    clearInterval(count_time);
                                    $("#状態").button({ label: "再開"} );
                                    localStorage.removeItem("bk_data");
                                    localStorage.removeItem("entry_status");
                                    localStorage.removeItem("Tabs");
                                    var url = "/RFIDReport/SetBase?plant="+$("#工場").val()+"&personname="+user;
                                    location.href = url;
                                    //window.open(url);
                                }else if(act == "かんばん交換"){
                                    clearInterval(count_time);
                                    // kanban_exchange('camera');
                                    loadingView(false);
                                    location.reload();
                                }else if(act == "re_entry"){
                                    $("#re_entry").css({"display":"none"});
                                    $("#tab_"+tabs[tabs.length-1]).click();
                                    loadingView(false);
                                    // location.reload(); 
                                }
                            }else{
                                let aw = $( window ).width()-200;
                                var options = {"title":"登録出来ません。",
                                    width: aw,
                                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                        $( this ).dialog( "close" );
                                    }}]
                                };
                                $("#message").html("データが<span style='color:red;'>登録出来ません。</span><br>管理者に連絡してください。<br>"+d);
                                $( "#alert" ).dialog( "option",options);
                                loadingView(false);
                                $("#alert").dialog( "open" );
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            loadingView(false);
                            if(ajaxOptions === 'timeout'){     
                                // alert("通信が実施されていない！！！ \n もう一度やり直してください。");
                                var options = {"title":"通信タイムアウトです。",
                                    width: aw,
                                    position:["centetr",100],
                                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                        $( this ).dialog( "close" );
                                    }}]
                                };
                                $("#message").html("通信が実施されていない！！！<br>もう一度登録をやり直してください。");
                                $( "#alert" ).dialog( "option",options);
                                $("#alert").dialog( "open" );
                            }else{
                                var options = {"title":"AJAX Err:通信ができません。<br>管理者に連絡してください。",
                                    width: aw,
                                    position:["centetr",100],
                                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                        $( this ).dialog( "close" );
                                    }}]
                                };
                                $("#message").html(JSON.stringify(xhr));
                                $( "#alert" ).dialog( "option",options);
                                $("#alert").dialog( "open" );
                            }
                        }
                    });
                },100);
            }},
            { class:"btn-center entry-control",text:"途中処理",click :function(ev) {
                // 途中処理対応
                middle_stop = true;
                if($("#rfid_middle_stop").length==0){
                    let middle_stop_area= `<hr style ="margin:20px 0;"></hr>`;
                
                    if($("#作業工程").val().indexOf("組立")>-1){
                        let list_parts=bk_data.list_parts.split(",");
                        $.each(list_parts,function(a,b){
                            middle_stop_area+=`<div style="padding: 4px;">
                                <label>【`+b+`】の残数：</label><input id="num_middle_stop_`+b+`" value="" placeholder="" style="margin-right:20px;text-align:right;width:100px;height:30px;border: 1px solid #FFF;margin-left:10px;" ></input>
                                <label>RFID：</label><input id="rfid_middle_stop_`+b+`" value="" placeholder="" style="width: 400px;height:30px;border: 1px solid #FFF;margin-left:10px;" ></input>`;
                            middle_stop_area+=`</div>`;  
                        })
                    }else{
                        middle_stop_area+=`<div style="padding: 4px;">
                        <label>【`+$("#案件コード").val()+`】の残数：</label><input id="num_middle_stop" value="" placeholder="残る数" style="margin-right:5px;text-align:right;font-weight:bold;width:100px;height:30px;border: 1px solid #FFF;background-color:#ccc;color:#000;"></input>個`;
                        middle_stop_area+=`<button style="margin-left:20px;" onclick="goRecalc();">確定</button>`;
                        middle_stop_area+=`</div>`;
                    }

                    $("#message").append(middle_stop_area);
                    $("button").button();
                    $(ev.target).css({"color":"lime"})
                    $(".entry-control").button("disable");
                    $(".ui-state-disabled, .ui-widget-content .ui-state-disabled .entry-control").css({"opacity":"0.7","border":"none"});
                }
            }},
            // 完成品処理はセット画面でするの為
            // { class:"btn-center entry-control",text:"完成品QR",click :function(ev) {
            //     // 完成のRFID入力の為(まだ利用してない)
            //     complete_flag = true;
            //     console.log($("#rfid_complete"));
            //     if($("#rfid_complete").length==0){
            //         let complete_qr= `<hr style ="margin:20px 0;"></hr>
            //             <div style="float:left;"><label>完成品のRFID：</label>
            //             <input id="rfid_complete" value="" placeholder="完成品のRFIDをスキャン" style="width: 400px;height:30px;border: 1px solid #FFF;" ></input>
            //             <button type="button" onclick="openCam();" class="btn_ditemset" style="margin-left:10px;width:50px;height:32px;background-color:#00c3ff;border:1px solid #FFF;border-radius: 4px;">
            //             <span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
            //         </div>`;
            //         $("#message").append(complete_qr)
            //         $(".entry-control").button("disable");
            //         $(ev.target).css({"color":"lime"})
            //         $(".entry-control").button("disable");
            //         $(".ui-state-disabled, .ui-widget-content .ui-state-disabled .entry-control").css({"opacity":"0.7","border":"none"});
            //     }
            // }},
            { class:"btn-left",text:"キャンセル",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        };
        $( "#alert" ).dialog( "option",options);
        //$("#btn_entrydefactiv").button();
        loadingView(false);
        $("#alert").dialog( "open" );
    }

    // 途中処理：残数入力後で表示された結果再集計
    function goRecalc(){
        if(parseInt($("#num_middle_stop").val())>0){
            let rc_s ={},all_sum_tab={};
            let rc_d =$("input,textarea");
            let rc_defective = "";
            let get_total =JSON.parse(localStorage.getItem("totalling"));

            $.each(rc_d,function(k,item){
                let sp_id = item.id.split("_");

                if(sp_id[1]==sp_this_id[1] && item.value!="0" && item.value!=""){
                    rc_s[sp_id[0]]=item.value;
                    if(item.name.indexOf('数基-本') == -1 && item.name.indexOf('成形日') == -1 && item.name.indexOf('備考') == -1 && item.name.indexOf('カンバン') == -1){
                        rc_defective+= sp_id[0]+"=>"+item.value+",";
                    }
                }

                if( item.name.indexOf('基本') !== -1) {
                    rc_s[item.name]=item.value;
                }

            });

            // 複数tab登録の処理
            // var tabs = JSON.parse(localStorage.getItem("Tabs"));
            rc_s["不良"] = rc_defective.substr(0, rc_defective.length - 1);
            var bk_data =JSON.parse(localStorage.getItem("bk_data"));
            if(bk_data){
                rc_s["開始"] = bk_data["開始時間"];
            }

            rc_s["終了"] = nowDT("dt");

            let width ="49";
            // if(act!=="かんばん交換"){
            //     width ="32"
            // }
            let msg_table3="<table class='type03' style='float:left;width:"+width+"%;'><thead><tr><th colspan='2'>計算</th></tr></thead>";
            
            //残数引き算
            if(parseInt($("#num_middle_stop").val())>parseInt(rc_s.完成数)){
                var msg="入力数値がオーバーされました。";
                var options = {"title":"確認してください！！！",
                    width: 600,
                    position:["centetr",100],
                    buttons: 
                        [{ class:"btn-left",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            return;
                        }}]
                };
                $("#message2").html(msg);
                $( "#alert2" ).dialog( "option",options);
                $( "#alert2" ).dialog( "open" );
                return;
            }else{
                let num_remaining = parseInt($("#num_middle_stop").val());
                rc_s["残数"]=num_remaining;
                rc_s.完成数 = parseInt(rc_s.完成数) - num_remaining;
            }

            $.each(rc_s,function(name,value){
                sp_name=name.split("][");
                if(sp_name[0]!="基本" && sp_name[0]!="RFID" && sp_name[0]!="前工程ID" && sp_name[0]!="原料ロット" && sp_name[0]!="数データ" && name!="数基-本" ){
                    msg_table3+='<tr><td>'+name+'</td><td>'+value+'</td></tr>'
                }
            });

            $("#calc_table").html(msg_table3);

        }else{
            return false;
        }
    }

    // null/undefined => 0
    function res_get(name){
        if(name){
            return name;
        }else{
            return 0;
        }
    }

    // ローカルストレージのデータのチェック
    function check_Storage(){
        let bk_data = JSON.parse(localStorage.getItem("bk_data"));
        if(bk_data){
            console.log("データある");
            if(bk_data.view_location){
                view_location=bk_data.view_location;
            }
            if(bk_data.ditem_list){
                $("#ditem_list").val(bk_data.ditem_list);
                return true;
            }else{
                return false;
            }
        }else{
            alert("仕掛品の情報がないので、設置画面へ行きます。");
            var url = "/RFIDReport/SetBase/";
            location.href = url;
            return false;
        }
 
    }
    
    // 組立工程の製品情報を抽出
    function getAssemblyInfo(code,id){
        let process=$("#作業工程").val().split(":");
        let search_wi = process.slice(1).join(":");
        $.ajax({
            type: "GET",
            url: "/RFIDReport/SetBase/",
            data: {
                ac:"getAssemblyInfo",
                item_code:code,
                process:search_wi
            },
            dataType: "json",
            success: function (d) {
                if(d.assembly_info.length>0){
                    if(code==$("#案件コード").val()){
                        // getAssemblyInfo(code);
                        var lot_tabs = JSON.parse(localStorage.getItem("Tabs"));
                        lot_tabs = lot_tabs+","+(lot_tabs.length+1);
                        localStorage.setItem("Tabs",JSON.stringify(lot_tabs));
                        $("#alert").dialog( "close" );
                        addListItem(lot_tabs.split(",").length);
                    }else{
                        $("#alert").dialog( "close" );
                        var msg = "製品変わる場合は「終了」ボタンを押してください。";
                        var options = {"title":"注意!!!",
                            position:["center",100],
                            width: 500,
                            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                            }}]
                        };
                        $("#message").html(msg);
                        $( "#alert" ).dialog( "option",options);
                        $("#alert").dialog( "open" )
                    }
                }else{
                    $("#alert").dialog( "close" );
                        var msg = "品目コードは見つかりません。";
                        var options = {"title":"注意!!!",
                            position:["center",100],
                            width: 500,
                            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                focus_assembly("manual",id)
                                $( this ).dialog( "close" );
                            }}]
                        };
                        $("#message").html(msg);
                        $( "#alert" ).dialog( "option",options);
                        $("#alert").dialog( "open" )
                }

            }
        });
    }

    // RFIDの情報を抽出
    function getRfidInfo(code){
        return new Promise((resolve) => {
            let process=$("#作業工程").val().split(":");
            let search_wi = process.slice(1).join(":");
            let bom_mode = $("#bom_mode").val();
            $.ajax({
                type: 'GET',
                url: "/RFIDReport/SetBase/",
                async : false,
                dataType: 'json',
                data:{
                    "ac":"getRfid",
                    "code":code,
                    "process":search_wi,
                    "bom_mode":bom_mode
                },
                success: function(d) {
                    // console.log(d);
                    resolve(d);
                }
            })
        });
    }

    function getPartsRfidInfo(code){
        return new Promise((resolve) => {
            let process=$("#作業工程").val().split(":");
            let search_wi = process.slice(1).join(":");
            let bom_mode = $("#bom_mode").val();
            $.ajax({
                type: 'GET',
                url: "/RFIDReport/SetBase/",
                async : false,
                dataType: 'json',
                data:{
                    "ac":"getPartsRfid",
                    "code":code,
                    "process":search_wi,
                    "bom_mode":bom_mode
                },
                success: function(d) {
                    console.log(d);
                    resolve(d);
                }
            })
        });
    }
    

    // QRコードからデータを抽出
    async function getLotCodeJsonFile(code,ip_id,jac){

        if($("#作業工程").val().indexOf("組立")>-1){
            //組立工程の場合
            if(code==$("#案件コード").val()){
                // getAssemblyInfo(code);
                var lot_tabs = JSON.parse(localStorage.getItem("Tabs"));
                lot_tabs = lot_tabs+","+(lot_tabs.length+1);
                localStorage.setItem("Tabs",JSON.stringify(lot_tabs));
                $("#alert").dialog( "close" );
                addListItem(lot_tabs.split(",").length);
            }else{
                $("#alert").dialog( "close" );
                var msg = "製品変わる場合は「終了」ボタンを押してください。";
                var options = {"title":"注意!!!",
                    position:["center",100],
                    width: 500,
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                    }}]
                };
                $("#message").html(msg);
                $( "#alert" ).dialog( "option",options);
                $( "#alert" ).dialog( "open" )
            }
        }else{
            //組立以外の場合
            if(ip_id=="QRコード"){
                // メインRFIDの情報を抽出
                loadingView(true);
                let get_info = [],sp_code=[];

                let new_code = code;
                sp_code = new_code.split("=>");
                let q="";
                for(i=0;i<=2;i++) { 
                    q+= sp_code[i]+"=>"; 
                }
                get_info = get_data(molding_info,'search_key',q)
                if(sp_code[3]==undefined){
                    sp_code[3]="";
                }
                $("#キャビ").val(sp_code[3]);

                if(get_info.length!==1){
                    // let check_rfid = get_data(rfid_molding_info,'search_rfid',code);
                    let check_rfid = await getRfidInfo(code);
                    // return;

                    if(check_rfid[0]=="NG"){
                        // alert("=0")
                        loadingView(false);
                        var msg = "「"+code+"」"+check_rfid[1];
                        var options = {"title":"確認!!!",
                            width: 500,
                            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                $( this ).dialog( "close" );
                                kanban_exchange("camera");
                            }}]
                        };
                        $("#message").html(msg);
                        $( "#alert" ).dialog( "option",options);
                        $( "#alert" ).dialog( "open" )
                        loadingView(false);
                        return;  
                    }
                    get_info=check_rfid[1];
                    new_code = get_info[0]["itemcord"]+"=>"+get_info[0]["moldlot"]+"=>"+get_info[0]["itemform"]+"=>"+get_info[0]["hgpd_cav"];
                    $("#Lotコード").val(get_info[0]["moldlot"]);
                    $("#キャビ").val(get_info[0]["hgpd_cav"]);
                    sp_code = new_code.split("=>");
                }
      
                // if(sp_code.length<2 || sp_code[0]!=$("#案件コード").val() || sp_code[1]!=$("#Lotコード").val()){
                
                // console.log(get_info);
                // if($("#Lotコード").val()==sp_code[1] && $("#案件コード").val()==sp_code[0]){
                if(get_info.length==1){
                    loadingView(false);
                    if(sp_code.length<2){
                        alert(`QRコードの様が不合！！！ \n もう一度スキャンしてください。`);
                        $("#QRコード").select();
                        return;
                    }else if(sp_code[0]!==$("#案件コード").val() || sp_code[1]!==$("#Lotコード").val() || sp_code[2]!==$("#型番").val()){
                        var msg = "製品交換またはロットNo.変更したら「終了」ボタンを押してください。";
                        if(sp_code[2]!==$("#型番").val()){
                            msg="型番【"+$("#型番").val()+"】→【"+sp_code[2]+"】の変更の為、「終了」ボタンを押してください。";
                        }
                        var options = {"title":"注意!!!",
                            width: aw,
                            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                                $("#QRコード").select();
                                $( this ).dialog( "close" );
                            }}]
                        };
                        $("#message2").html(msg);
                        $( "#alert2" ).dialog( "option",options);
                        $("#alert2").dialog( "open" );
                        return;
                    }
                    $("#alert").dialog( "close" );
                    if($("#案件コード").val()!=sp_code[0]){
                        $("#案件コード").val(get_info[0]["itemcord"]);
                    }
                    if($("#Lotコード").val()!=sp_code[1]){
                        $("#Lotコード").val(get_info[0]["moldlot"]);
                    }
                    localStorage.removeItem("entry_status");
                    var cav_no = sp_code.slice(-1);
                    $("#キャビ").val(cav_no);
                    $("#型番").val(get_info[0]["itemform"]);
                    $("#原料名").val(get_info[0]["materialsname"]);
                    $("#原料コード").val(get_info[0]["materialcode"]);
                    $("#原料ロット").val(get_info[0]["materialslot"]);
                    $("#成形日").val(get_info[0]["hgpd_moldday"]);
                    $("#前良品").val(get_info[0]["wic_qty_in"]);
                    $("#前工程ID").val(get_info[0]["hgpd_id"]);
                    $("#rfid").val(get_info[0]["search_rfid"]);
                    data_backup();
                    let id = sp_code[1]+"-"+sp_code[2]+"-"+sp_code[0];
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
                    var lot_tabs = JSON.parse(localStorage.getItem("Tabs"));
                    if(lot_tabs.indexOf(cav_no) == -1){
                        lot_tabs = lot_tabs+","+cav_no;
                        localStorage.setItem("Tabs",JSON.stringify(lot_tabs));
                        addListItem(cav_no);
                    }else{
                        count = 1;
                        pos = lot_tabs.indexOf(cav_no);
                        while ( pos != -1 ) {
                            count++;
                            pos = lot_tabs.indexOf(cav_no, pos + 1);
                        }
                        lot_tabs = lot_tabs+","+cav_no+"-"+count;
                        localStorage.setItem("Tabs",JSON.stringify(lot_tabs));
                        addListItem(cav_no+"-"+count);
                    }
                    $("#作業時間").val(0);
                    clearInterval(count_time);
                    $("#状態").button({ label: "開始"} );
                    loadingView(false);
                }else{
                    // var msg = "製品交換またはロットNo.変更したら「終了」ボタンを押してください。";
                    var msg = "品目を見つけません。";
                    var options = {"title":"注意!!!",
                        width: aw,
                        buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            kanban_exchange("かんばん交換");
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    loadingView(false);
                    $("#alert").dialog( "open" );
                }
            }else{
                // サブRFIDの情報を抽出(端数寄せの為)
                let get_info = [],sp_code=[];
                // let check_rfid = get_data(rfid_molding_info,'search_rfid',code);
                let check_rfid = await getRfidInfo(code);
                // return;
                let new_code=code;
                if(check_rfid.length==1){
                    get_info=check_rfid;
                    new_code = get_info[0]["itemcord"]+"=>"+get_info[0]["moldlot"]+"=>"+get_info[0]["itemform"]+"=>"+get_info[0]["hgpd_cav"];
                    sp_code = new_code.split("=>");
                }
                let sp_id=ip_id.split("_");
                if(get_info.length==1){
                    loadingView(false);
                    let lot_date = get_info[0]["hgpd_moldday"].replace( /-/g,"/").substr(2);
                    $("#成形日_"+sp_id[1]+"_"+sp_id[2]).val(lot_date);
                    $("#受入数_"+sp_id[1]+"_"+sp_id[2]).val(get_info[0]["wic_qty_in"]);
                    focus_assembly("auto",ip_id)
                }else{
                    $("#rfid_"+sp_id[1]+"_"+sp_id[2]).val("");
                    var msg = "部品を見つけません。";
                    var options = {"title":"注意!!!",
                        width: aw,
                        buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                            focus_assembly("manual",ip_id)
                            return false;
                        }}]
                    };
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    loadingView(false);
                    $("#alert").dialog( "open" );
                }
            }
        }

    }

    // RFID連携されない製品情報を抽出
    function getLotCode(code){
        if($("#Lotコード").val()==code[1] && $("#案件コード").val()==code[0]){
            localStorage.removeItem("entry_status");
            var cav_no = code.slice(-1)[0];
            $("#キャビ").val(cav_no);
            id = code[1]+"-"+code[2]+"-"+code[0];
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
            var lot_tabs = JSON.parse(localStorage.getItem("Tabs"));
            if(lot_tabs.indexOf(cav_no) == -1){
                lot_tabs = lot_tabs+","+cav_no;
                localStorage.setItem("Tabs",JSON.stringify(lot_tabs));
                addListItem(cav_no);
            }else{
                count = 1;
                pos = lot_tabs.indexOf(cav_no);
                while ( pos != -1 ) {
                    count++;
                    pos = lot_tabs.indexOf(cav_no, pos + 1);
                }
                lot_tabs = lot_tabs+","+cav_no+"-"+count;
                localStorage.setItem("Tabs",JSON.stringify(lot_tabs));
                addListItem(cav_no+"-"+count);
            }
            $("#作業時間").val(0);
            $("#状態").button({ label: "開始"} );
        }else{
            var msg = "製品交換したら「終了」ボタンを押してください。";
            var options = {"title":"注意!!!",
                width: aw,
                buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog( "open" );
        }
    }

    // 組立の対応：各部品情報を抽出
    function add_parts(ac){
        var msg="<label for='add_parts'>部品コード：</label><input id='add_parts' type='text' value='' placeholder='部品のQRコード' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='add_parts'>部品コード：</label><input id='add_parts' type='tel' pattern='[0-9]*' value='' placeholder='部品のQRコード' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        }
        $("#message").html(msg);
        var options = {"title":"部品スキャン",
            position:["center",100],
            width: aw,
            buttons: [
                { class:"btn-right",text:"カメラで入力",click :function(ev) {
                    focus_assembly('auto');
                }},
                { class:"btn-right",text:"OK",click :function(ev) {
                    var code = $("#add_parts").val();

                    getLotCodeJsonFile(code,"add_parts");
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

    // 組立の対応：各部品情報追加
    function add_parts_cam(id){
        $("#"+id).val("");
        focus_assembly("manual",id);
    }

    // カンバン交換機能
    function kanban_exchange(ac){
        if(ac=="camera"){
            od('QRコード','QRコード');
            setTimeout(function(){
                stop_scan();
            }, 300000);
            return;
        }
        var msg="<label for='QRコード'>RFID：</label><input id='QRコード' type='text' value='' placeholder='RFID入力' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='QRコード'>RFID：</label><input id='QRコード' type='tel' pattern='[0-9]*' value='' placeholder='RFID入力' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        }
        $("#message").html(msg);

        $("#QRコード").on('keyup', function(e) {
            if(e.which == 13){
                let check_code = $("#QRコード").val();
                if($(check_code.indexOf("^")!==-1)){
                    newcode=check_code.replace(/\^/g,"=");
                    $("#QRコード").val(newcode);
                }
                var code = $("#QRコード").val().split("=>");
                $("#alert").dialog( "close" );
                // getLotCode(code);
                getLotCodeJsonFile($("#QRコード").val(),"QRコード");
            }
        });
        var options = {"title":"かんばん交換",
            position:["center",100],
            width: aw,
            buttons: [
                { class:"btn-right",text:"カメラで入力",click :function(ev) {
                    kanban_exchange('camera');
                }},
                { class:"btn-right",text:"OK",click :function(ev) {
                    var code = $("#QRコード").val();
                    getLotCodeJsonFile(code,"QRコード");
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
    
    // 全部TAB集計
    function sum_tabs(id){
        tabs = $(".tabs");
        var n = 0;
        if(id=="備考_"){
            n= "";
            if(tabs[0] != ""){
                $.each(tabs,function(k,val){
                    tab_num = val.id.split("_");
                    if($("#"+id+tab_num[1]).val() != ""){
                        n = n+$("#"+id+tab_num[1]).val()+",";
                    }
                });
                n = n.substr(0, n.length - 1);
            }else{
                n = $("#"+id+tab_num[1]).val();
            }
        }else{
            $.each(tabs,function(k,val){
                tab_num = val.id.split("_");
                n = n + parseInt($("#"+id+tab_num[1]).val());
            });
        }
        return n;
    }

    // 今のTAB集計
    function sum_one_tabs(id){
        let sp_id=id.split("_");
        var n = 0;
        n = parseInt($("#"+id).val());
        return n;
    }

    // カウンター開始の状態のチェック
    function check_status(){
        if ($("#状態").button( "option", "label" ) == "一時停止"){
            if($(".quantity").val()==""){
                msg = $(".quantity span").html();
                $("#message").html("【"+msg+"】入力して。");
                options = {"title":"注意",
                    width:aw,
                    position:["center",115],
                    buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                    }}]
                };
                $( "#alert" ).dialog( "option",options);
                $("#alert").dialog( "open" );
                return false;
            }else{
                return true;
            }
        }else{
            msg = $("#状態 span").html();
            $("#message").html("【"+msg+"】ボタンを押してください。");
            options = {"title":"注意",
                width:aw,
                position:["center",115],
                buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }}]
            };
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog( "open" );
            return false;
        }
    }

    // クリア機能
    function del_ls(){
        let msg= '<span style="color:red;">結果が戻せませんよ！！！<br>本当に削除しますか？<span>';
        var options = {"title":"削除をしますか？",
            width: 400,
            position:["centetr",100],
            buttons: [{ class:"btn-right",text:"OK",click :function(ev) {
                let user = $("#担当者").val();
                $(".b_data input").val("");
                $("#作業日").val("<?=date("n/j")?>");
                $("#作業時間").val(0);
                $("#状態").button({ label: "開始"} );
                $("#ui-tab input").val(0);
                $("#ui-tab input").css({"color":""});
                $(".remark").val("");
                clearInterval(count_time);
                localStorage.removeItem("bk_data");
                localStorage.removeItem("Tabs");
                localStorage.removeItem("entry_status");
                localStorage.removeItem("counter_mode");
                // check_Storage();
                location.href = "/RFIDReport/SetBase?plant="+$("#工場").val()+"&personname="+user;
            }},{ class:"btn-left",text:"キャンセル",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        };
        $("#message").html(msg);
        $( "#alert" ).dialog( "option",options);
        loadingView(false);
        $("#alert").dialog( "open" );


    }
    function item_exchange(){
        focus_control();
    }

    // TAB切り替わりの機能
    function switch_tab(){
        $("#num_in_tab").val("");
        $("#number_in").dialog( "close" );
        let tab_set = JSON.parse(localStorage.getItem("Tabs"));
        tab_set = tab_set.split(",");
        var tab = $(".ui-state-active a span").html();
        if($("#entry_status_"+tab).val()=="done"){
            $("#re_entry").css({"display":"none"});
            $("#view_only_"+tab).addClass("view_only");
        }else{
            $("#view_only_"+tab).removeClass("view_only");
        }
        if($("#entry_status_"+tab).val()!=="done"){
            if(tab!==tab_set[tab_set.length-1]){
                $("#re_entry").css({"display":""});
            }else{
                $("#re_entry").css({"display":"none"});
            }
            if($("#作業工程").val().indexOf("組立")>-1){
                focus_assembly("auto");
            }else{
                focus_control();
            }
        }
        if($("#link_to_proc").val()=="1"){
            setTimeout(() => {
                $("#キャビ").val(tab);
            }, 0);
        }

    }

    // サーバーの時間を取る
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
    }

    // TOUCH キーボード
    function open_num_key(id){
        if($("#"+id).hasClass("n_input")){
            if(check_status()==false){
                return;
            }
        }

        $("#num_in_tab").val(id);
        // sp_id=id.split("_");
        // $("#num_in_tab").val(sp_id[1]);
        let input_id= "#"+id;
        if($(input_id).val()!=""){
            // $(input_id).select();
        }
        let ip_top = $(input_id).position().top;
        let ip_left = $(input_id).position().left;
        var options = {
            // position:{my: "left top", at: "left bottom", of: input_id},
            position:{my: "left", at: "right", of: input_id},
        }

        if((ip_left+260)>$(window).width()){
            options = {
                position:{my: "right", at: "left", of: input_id},
            } 
        }
        $( "#number_in" ).dialog( "option",options);
        $( "#number_in" ).dialog( "open" );

        $(".ui-widget-overlay").css({"opacity":"0"});
        $(".ui-widget-overlay").on('click',function(){
            $("#number_in").dialog( "close" );
            $(input_id).focusout();
            // $(input_id).css({'background-color':''});
            // $(input_id).css({'color':'#fff'});
            if($("#作業工程").val().indexOf("組立")>-1){
                // focus_assembly("auto");
            }else{
                focus_control();
            }
        });
    }

    function btn_click(num){
        let kind=$("#num_in_tab").val();
        let val= $("#"+kind).val();
        if((val=="" || val=="0") && parseInt(num)==0){
            return;
        }

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
        if(counter_mode!=="count_down"){
            val= $("#"+kind).val();
        }
        if(val!==""){
            // $("#受入数_"+kind).val(val.substr(0,val.length-1));
            $("#"+kind).val(0);
            // $("#"+kind).attr("placeholder", val);
        }
        // $("#受入数_"+kind).focus();
    }

    function btn_control(ac){
        let kind=$("#num_in_tab").val();
        if($("#"+kind).val()==""){
            $("#"+kind).val("0");
        }
        let sp_kind = kind.split("_");
        sum_this_tab(sp_kind[1])
        $("#"+kind).focusout();
        $("#num_in_tab").val("");
        $("#number_in").dialog( "close" );
        if(!$("#"+kind).hasClass("n_input")){
            if($("#作業工程").val().indexOf("組立")>-1){
                focus_assembly("auto");
            }else{
                focus_control();
            }
        }
    }

    // focusイベント
    function focus_control(){
        var tab = $(".ui-state-active a span").html();
        if(counter_mode=="count_down"){
            if($("#成形日_"+tab).val() == ""){
                $("#成形日_"+tab).focus();
            }else if($("#受入数_"+tab).val()=="" || $("#受入数_"+tab).val()=="0"){
                $("#受入数_"+tab).focus();
            }else{
                change_status();
            }
        }else{
            if($("#成形日_"+tab).val() == ""){
                $("#成形日_"+tab).focus();
            }else if($("#完成数_"+tab).val()=="" || $("#完成数_"+tab).val()=="0"){
                $("#完成数_"+tab).focus();
            }else{
                change_status();
            }  
        }

    }

    function change_status(){
        if ($("#状態").button( "option", "label" ) !== "一時停止"){
            $("#状態").click();
        }else{
            $("#状態").focus();
        }
    }

    // ローカルストレージの有効時間セット
    function setStorageWithExpiry(key, val, ttl) {
        const now = new Date()
        const item = {
            value: val,
            expiry: now.getTime() + ttl,
        }
        localStorage.setItem(key, JSON.stringify(item))
    }

    // ローカルストレージの有効時間チェック
    function getStorageWithExpiry(key) {
        const itemStr = localStorage.getItem(key)
        if (!itemStr) {
            return null
        }
        const item = JSON.parse(itemStr)
        const now = new Date()
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(key)
            return null
        }
        return item.value
    }

    // 登録状態のチェック
    function check_entry_tabs(){
        let tabs=$(".entry_status");
        let c=0;
        let str= "";
        let last_tab=tabs[tabs.length-1].value;

        let this_tab = $(".ui-state-active");
        let this_id = this_tab[0].attributes.id.value;
        sp_this_id=this_id.split("_");

        for(i=0;i<tabs.length-1;i++){
            //登録してない場合
            if(tabs[i].value!=="done"){
                c=1;
                if(str==""){
                    str= tabs[i].id.split("_")[2];
                }else{
                    str+= ","+tabs[i].id.split("_")[2];
                }
            }
        }

        if(c!==0){
            //前のtab登録してない場合
            if(last_tab =="done" ){
                //最後tab登録した場合
                return str;
            }else{
                //最後tab登録しない場合
                str+= ","+tabs[tabs.length-1].id.split("_")[2];
                return str;
            }
        }else{
            //前のtab登録した場合:今のTAB確認
            if(tabs[tabs.length-1].id.split("_")[2] == sp_this_id[1]){
                //最後TAB：
                if(last_tab === "done"){
                    //全部登録した
                    return "add_tab";
                }else{
                    //普通の処理
                    return "continue";
                }
            }else{
                //最後TABではない：
                return "goto_end";
            }
        }
    }

    //登録の前に大切な情報確認
    function check_entry(ac){
        if(ac=="add_tab"){
            var msg="前回のかんばん交換処理でQRコードがスキャンされませんでした。<br> 下のかんばん交換ボタンを実行すると作業を再開できます。<br> 終了する際は右下の終了ボタンを実行してください。"
            var options = {"title":"確認してください！！！",
                width: 800,
                buttons: 
                    [
                    { class:"btn-right",html:"<span style='color:red;'>終了</span>",click :function(ev) {
                        $( this ).dialog( "close" );
                        localStorage.removeItem("bk_data");
                        localStorage.removeItem("entry_status");
                        localStorage.removeItem("Tabs");
                        var url = "/RFIDReport/SetBase?plant="+$("#工場").val()+"&personname="+$("#担当者").val();
                        location.href = url;
                    }},{ class:"btn-center",text:"かんばん交換",click :function(ev) {
                        $( this ).dialog( "close" );
                        kanban_exchange('camera');
                    }},
                    { class:"btn-left",text:"キャンセル",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            loadingView(false);
            $("#alert").dialog( "open" );
        }else{
            let sp_ac=ac.split(",");
            let list_tab = "";
            $.each(sp_ac,function(k,v){
                list_tab+= "「"+v+"」,"
            });
            list_tab=list_tab.substr(0,list_tab.length-1);
            var msg="保存してない結果があります。<br>TAB"+list_tab+"を確認してください。<br>問題が無ければ「登録」ボタンを押してください。";
            var options = {"title":"確認してください！！！",
                width: 600,
                buttons: 
                    [{ class:"btn-right",text:"再確認",click :function(ev) {
                        $( this ).dialog( "close" );
                        $("#entry_status_"+sp_ac[0]).val("not_done")
                        $("#tab_"+sp_ac[0]).click();
                        $("#view_only_"+sp_ac[0]).removeClass("view_only");
                        $("#re_entry").css({"display":""});
                        return;
                    }},
                    { class:"btn-left",text:"キャンセル",click :function(ev) {
                        $( this ).dialog( "close" );
                        return;
                    }}]
            };
            $("#message").html(msg);
            $( "#alert" ).dialog( "option",options);
            loadingView(false);
            $("#alert").dialog( "open" );
        }
    }

    //カメラを起動
    function od(id,name,fcmode){
        loadingView(true);
        $("#"+id).attr('readonly','readonly');
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
            stop_scan();
            if(id=="QRコード"){
                kanban_exchange();
            }else{
                $("#"+id).removeAttr('readonly');
                $("#"+id).select();
                return false;
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
                if(!list_code_scaned.includes(code.data)){
                    list_code_scaned.push(code.data);
                    drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#00f200");
                    drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#00f200");
                    drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#00f200");
                    drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#00f200");
                    playMelody(2200);
                    await sleepy(300);
                    if(code.data){
                        // getLotCode(code.data.split("=>"));
                        if(id.indexOf("仕掛ID連携")>-1){
                            rfid_status(id,code.data);
                        }else if(id!="rfid_complete"){
                            $("#"+id).val(code.data);
                            getLotCodeJsonFile(code.data,id);
                        }
                        // camera_off=true;
                        stop_scan();
                    }
                }else{
                    drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                    drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                    drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                    drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
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


    function rfid_status(id,rfid){
        let itcode = $("#案件コード").val();
        // rfid_status(id,itcode,rfid);
        $.ajax({
            type: "GET",
            url: "/RFIDReport/RfidCheckStatus",
            data: {
                rfid:rfid,
                item:itcode,
            },
            dataType: "json",
            success: function (res) {
                console.log(res)
                if(res===true){
                    $("#"+id).val(rfid);
                }else{
                    alert(res[1])
                    $("#"+id).val("");
                }
            }
        });
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

    //カメラ閉じる
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

    function openCam(){
        od('rfid_complete','完成品コード');
    }

    function counter_mode_change(){
        if(check_Storage()){
            alert("まず、現在の検査結果を登録してください。");
            ditemListView();
            // return;
        }else{
            if(counter_mode=="count_down"){
                counter_mode="count_up";
                $("#change_mode_btn span").html("「ー」モード");
                localStorage.setItem("counter_mode","count_up");
            }else{
                counter_mode="count_down";
                $("#change_mode_btn span").html("「✚」モード");
                localStorage.setItem("counter_mode","count_down");
            }
            ditemListView("firstload");
        }
        // console.log(counter_mode);
    }

    // 端数寄せの連携
    function open_device_rounding(ac){
        let url = "/RFIDReport/RoundingDeviceID?user="+$("#担当者").val();
        window.open(url,"_blank");
    }

    function device_rounding(ac){
        var msg="<label for='rounding_id'>RFID：</label><input id='rounding_id' type='text' value='' placeholder='RFID入力' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        if(localStorage.getItem("client_app")=="Safari"){
            msg="<label for='rounding_id'>RFID：</label><input id='rounding_id' type='tel' pattern='[0-9]*' value='' placeholder='RFID入力' autocomplete='off'  style='width:400px;font-size:18px;'/>";
        }
        $("#message").html(msg);

        if(ac=="camera"){
            $(".rounding_obj").show();

            od('rounding_rfid','端数寄せの為の追加RFID');
            setTimeout(function(){
                stop_scan();
            }, 300000);
            return;
        }else{
            var options = {"title":"端数寄せの為の追加RFID",
                position:["center",250],
                width: 600,
                buttons: [
                    { class:"btn-right",text:"カメラで入力",click :function(ev) {
                        device_rounding('camera');
                    }},
                    { class:"btn-right",text:"OK",click :function(ev) {
                        var code = $("#QRコード").val();
                        getLotCodeJsonFile(code,"rounding_id","rounding");
                    }},
                    { class:"btn-left",text:"閉じる",click :function(ev) {
                        $( this ).dialog( "close" );
                    }}
                ]
            };
            $( "#alert" ).dialog( "option",options);
            $("#alert").dialog( "open" );
        }
    }

    // ダイヤログで端数寄せ(無効)
    async function viewRounding(code,ip_id,jac,get_info){
        console.log(code);
        console.log(ip_id);
        console.log(jac);
        console.log(get_info);

        let this_tab = $(".ui-state-active");
        let this_tab_id = this_tab[0].attributes.id.value;
        let this_tab_num=this_tab_id.split("_")[1];
        console.log(this_tab_num);
        let sub_info = {};

        if(jac=="checked"){
            $.ajax({
                type: "GET",
                url: "",
                data: {
                    ac:"getInfoId",
                    rfid:code,
                },
                dataType: "json",
                success: function (response) {
                    
                }
            });
        }else{
            sub_info = get_info[0];
        }

        
        let sub_round_num = parseInt(sub_info.tray_num)*parseInt(sub_info.tray_stok);

        let need_num = sub_round_num - parseInt($("#完成数_"+this_tab_num).val());
        let remaining_num = parseInt(sub_info.wic_qty_in) - need_num;
        if(remaining_num<0){
            alert("オーバー！！！");
            return;
        }

        var msg=`
            <p style="text-align:left;margin-bottom:10px;">
                <label style="margin-right:5px;">まるめ数</label><input type="number" id="round_num" value="`+sub_round_num+`" style="text-align:center;width:80px;border: 1px solid #FFF;" />
            </p>
            <div style="display: inline-block;">
                <table class='type03' style='float:left;margin:0;'>
                    <tr><td>主RFID</td><td style="text-align:center">`+$("#rfid").val()+`</td></tr>
                    <tr><td>残数</td><td style="text-align:center">`+$("#完成数_"+this_tab_num).val()+`</td></tr>
                </table>
                <span class="" style='float:left;margin:10px;'></span>
                <table class='type03' style='float:left;margin:0;'>
                    <tr><td>副RFID</td><td style="text-align:center">`+code+`</td></tr>
                    <tr></tr>
                    <tr><td>残数</td><td style="text-align:center">`+sub_info.wic_qty_in+`</td></tr>
                </table>
            </div>
            <p>
                <span class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-d ui-btn-icon-notext ui-btn-inline ui-btn-theme-custom" style='margin: 10px;'></span>
            </p>
            <div style="display: inline-block;">
                <table class='type03' style='float:left;margin:0;'>
                    <tr><td>主RFID</td><td style="text-align:center">`+$("#rfid").val()+`</td></tr>
                    <tr><td>残数</td><td style="text-align:center">`+sub_round_num+`(<span style="color:lime;">+`+need_num+`<span>)</td></tr>
                </table>
                <span class="" style='float:left;margin:10px;'></span>
                <table class='type03' style='float:left;margin:0;'>
                    <tr><td>副RFID</td><td style="text-align:center">`+code+`</td></tr>
                    <tr><td>残数</td><td style="text-align:center">`+remaining_num+`(<span style="color:red;">-`+need_num+`<span>)</td></tr>
                </table>
            </div>
        `;

        let msg2=`
            <table class='type03' style='float:left;margin:0;'>
                <tr><td>副RFID</td><td style="text-align:center">`+code+`</td></tr>
                <tr><td>まるめ数</td><td style="text-align:center">`+sub_round_num+`</td></tr>
                <tr><td>残数</td><td style="text-align:center">`+sub_info.wic_qty_in+`</td></tr>
            </table>
            // <span class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-l ui-btn-icon-notext ui-btn-inine ui-btn-theme-custom" style='float:left;margin:10px 10px;'></span>
            <p>
                <label for='main_rfid'>主RFID：</label><input id='main_rfid' type='text' value='' autocomplete='off'  style='width:200px;font-size:18px;'/>
                <label for='main_total_num'>受け数：</label><input id='main_total_num' type='text' value='' autocomplete='off'  style='width:40px;font-size:18px;'/>
                <label for='main_total_num'>良品数数：</label><input id='main_good_num' type='text' value='' autocomplete='off'  style='width:40px;font-size:18px;'/>
                <label>+</label>
                <label for='main_rfid'>副RFID：</label><input id='sub_rfid' type='text' value='' autocomplete='off'  style='width:200px;font-size:18px;'/>
                <label for='main_total_num'>受け数：</label><input id='sub_total_num' type='text' value='' autocomplete='off'  style='width:40px;font-size:18px;'/>
                <label for='main_total_num'>良品数数：</label><input id='sub_good_num' type='text' value='' autocomplete='off'  style='width:40px;font-size:18px;'/>
            </p>
            <label class="ui-shadow-icon ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-left">|→</label>
            <p>
                <label for='main_rfid'>RFID：</label><input id='final_rfid' type='text' value='' autocomplete='off'  style='width:200px;font-size:18px;'/>
                <label for='main_total_num'>受け数：</label><input id='final_total_num' type='text' value='' autocomplete='off'  style='width:40px;font-size:18px;'/>
                <label for='main_total_num'>良品数数：</label><input id='final_good_num' type='text' value='' autocomplete='off'  style='width:40px;font-size:18px;'/>
            </p>
        `;

        var options = {"title":"端数寄せの確認",
            position:["center",250],
            width: "auto",
            buttons: [
                { class:"btn-right",text:"OK",click :function(ev) {
                    
                }},
                { class:"btn-left",text:"閉じる",click :function(ev) {
                    $("#alert").dialog( "close" );
                }}
            ],
            open:function(e,ui){ $('input,button').blur(); }
        };
        $("#message").html(msg);
        $( "#alert" ).dialog( "option",options);
        $("#alert").dialog( "open" );

    }
    
    // デジタルのファイル対応
    function openDigital(flag){
        let itemcode = $("#案件コード").val();
        let work_process = $("#作業工程").val().split(":")[1];
        //工程名は同一してないので、「最終検査」固定する(削除)
        // let work_process = "最終検査";
        let file_name = itemcode.substr(0,4)+"_"+itemcode+"_"+work_process+".pdf";
        Std_digitization('btn_digital',file_name,flag);
    }

    // デジタルのファイルのチェック
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

    function end_tray(){
        let productType = $("#品名").val()+"_"+$("#型番").val();
        let inspecter = $("#担当者").val();
        let progressRFID = $("#rfid").val();
        let cavNumber = $("#キャビ").val();

        let url = `https://rabbitmes.azurewebsites.net/#/ahj/trayLabel?productType=`+productType+`&inspecter=`+inspecter+`&progressRFID=`+progressRFID+`&cavNumber=`+cavNumber;
        window.open(encodeURI(url));
        $("#tray_log_stack").val(parseInt($("#tray_log_stack").val())+1);
    }

    function tray_posi(defectType){
        let productType = $("#品名").val()+"_"+$("#型番").val();
        let inspecter = $("#担当者").val();
        let cavNumber = $("#キャビ").val();
        
        let url = `https://rabbitmes.azurewebsites.net/#/ahj/visualExamination?productType=`+productType+`&defectType=`+defectType+`&inspecter=`+inspecter+`&cavNumber=`+cavNumber;
        // console.log(encodeURI(url));
        window.open(encodeURI(url));
    }

</script>

<div class="t_cont_out">
    <div class="b_data">
        
        <input type="text" value="" id="工場ID" style="display:none;" name="基本][工場ID" readonly="readonly"/>
        <input type="text" value="" id="工場" style="display:none;" name="基本][工場" readonly="readonly"/>
        <input type="text" value="" id="号機" style="display:none;" name="基本][号機" readonly="readonly"/>
        <input type="text" value="" id="原料名" style="display:none;" name="基本][原料名" readonly="readonly"/>
        <input type="text" value="" id="原料コード" style="display:none;" name="基本][原料コード" readonly="readonly"/>
        <input type="text" value="" id="原料ロット" style="display:none;" name="" readonly="readonly"/>
        <input type="text" value="" id="成形日" style="display:none;" name="" readonly="readonly"/>
        <input type="text" value="" id="前良品" style="display:none;" name="" readonly="readonly"/>
        <input type="text" value="" id="前工程ID" style="display:none;" name="" readonly="readonly"/>
        <input type="text" value="" id="list_parts" style="display:none;" name="" readonly="readonly"/>
        <input type="text" value="" id="ditem_list" style="display:none;" name="ditem_list" readonly="readonly"/>
        <input type="text" value="on" id="bom_mode" style="display:none;" name="bom_mode" readonly="readonly"/>
        <input type="text" value="0" id="ml_flg" style="display:none;" name="machine_learn" readonly="readonly"/>
        <input type="text" value="false" id="link_to_proc" style="display:none;" name="" readonly="readonly"/>

        <p style="float:left; width:78%;">
            <label for="作業日">作業日：</label><input type="text" value="<?=date("n/j")?>" id="作業日" style="width:60px;" name="基本][作業日" readonly="readonly"/>
            <label for="Lotコード">Lot№：</label><input type="text" value="" id="Lotコード" style="width:70px;" name="基本][Lotコード" readonly="readonly"/>
            <label for="作業工程">作業工程：</label><input type="text" value="" id="作業工程" style="width:200px;" name="基本][作業工程" readonly="readonly"/>
            <label for="担当者">担当者：</label><input type="text" value="" id="担当者" style="width:155px;" name="基本][担当者" readonly="readonly"/>
        </p>

        <p class="fr" style="width:200px;">
            <!-- <button type="button" onclick="defactitemadd();" class="btn_ditemset" style="width:95px;">不良設定</button> -->
            <button style="" type="button" onclick="open_device_rounding();">端数寄せ</button>
            <button id="del_ls" style="float:right;width:95px;" type="button" onclick="del_ls();">クリア</button>
        </p>
        
        <p class="fl" style="float:left;width:650px;">
            <label>ID：</label><input type="text" value="" id="rfid" style="width:400px;" name="" readonly="readonly"/>
            <label for="tray_log_stack" class="mes_app_gr" style="">トレイ交換押す：</label><input id="tray_log_stack" class="mes_app_gr" type="text" value="0"  placeholder="" readonly="readonly" style="width:40px;text-align:center;" /><label class="mes_app_gr">回</label>
            <!-- <label class="rounding_obj">寄せの為ID：</label><input type="text" value="" id="rounding_rfid" class="rounding_obj" style="width:300px;" name="" readonly="readonly"/> -->
        </p>

        <div style="clear:both;"></div>
        
        <p class="fl">
            <label for="案件コード">コード：</label><input type="text" value="" id="案件コード" style="width:130px;"  name="基本][案件コード" readonly="readonly"/>
            <label for="品名">品名：</label><input type="text" value="" id="品名" style="width:320px;" name="" readonly="readonly"/>
            <input type="hidden" value="" id="itemname" style="" name="基本][品名" readonly="readonly"/>
            <label for="型番">型番：</label><input type="text" value="" id="型番" style="width:35px;" name="基本][型番" readonly="readonly"/>
            <label for="キャビ">キャビNo：</label><input type="text" value="" id="キャビ" style="width:40px;" name="基本][キャビ" readonly="readonly"/>
            <input type="text" value="" id="単価" style="display:none;" name="基本][単価" readonly="readonly"/>
            <p style="float:right;color:#fff;" id="作業時間表示"></p>
        </p>
        <div style="clear:both;"></div>
        <!-- <p class="fl">
            <label class="to_link_id">仕掛IDと連携：</label><input type="text" value="" id="link_process_id" class="to_link_id" style="width:400px;"  name="基本][仕掛ID連携" readonly="readonly"/>
            <button type="button" class="to_link_id" onclick="openProcCam();" class="btn_ditemset btn_topmenu" style="width:45px;"><span class="ui-icon-camera ui-btn-icon-left" ></span></button>
        </p>
        <div style="clear:both;"></div> -->
        <div style="float:left;padding:4px;" class="btn_control">
            <button id="btn_digital" style="" type="button" onclick="openDigital(true);" >チェックポイント</button>
            <input id="mes_app_set" class="mes_app_gr" type="checkbox" style="" ></input><label for="mes_app_set" class="mes_app_gr">追加入力(AI判断との比較)</label>
        </div>
        <div style="float:right;padding:4px;" class="btn_control">
            <button style="" type="button" onclick="status_btn();" id="状態">開始</button>
            <span style="color:#000;"><label style="font-size: 20px;" for="作業時間">時間：</label><input type="text" value="0"  name="基本][作業時間" id="作業時間" style="width:50px;font-size: 30px;color:#000;" readonly="readonly" /></span>
            <button id="re_entry" style="display:none;" type="button" onclick="end_work('re_entry');">登録</button>
            <!-- <button style="float:right;" type="button" onclick="end_work('製品交換');">製品交換</button> -->
            <button id="btn_change_tray" style="" type="button" onclick="end_tray();">トレイ交換</button>
            <button style="" type="button" onclick="end_work('かんばん交換');">かんばん交換</button>
            <button style="width:100px;" class="btn-red" type="button" onclick="end_work('終了');" >終了</button>
        </div>
        <div style="clear:both;"></div>
    </div>
    <div id = "ui">
    </div>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>
<div id="alert2">
    <div id="message2" style="text-align: center;"></div>
</div>
<div id="num_dialog">
    <div id="number_in" style="display:none;padding:0;">
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="btn_click(7);">7</p>
            <p class="num_btn" onclick="btn_click(8);">8</p>
            <p class="num_btn" onclick="btn_click(9);">9</p>
        </div>
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="btn_click(4);">4</p>
            <p class="num_btn" onclick="btn_click(5);">5</p>
            <p class="num_btn" onclick="btn_click(6);">6</p>
        </div>
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="btn_click(1);">1</p>
            <p class="num_btn" onclick="btn_click(2);">2</p>
            <p class="num_btn" onclick="btn_click(3);">3</p>
        </div>
        <div style="display:block;margin:auto;">
            <p class="num_btn" onclick="btn_click(0);">0</p>
            <p class="num_btn" onclick="btn_del('del');">C</p>
            <p class="num_btn" onclick="btn_control('ok');">OK</p>
        </div>
        <input id="num_in_tab" style="display:none;" value=""></input>
    </div>
</div>
<div id="QRScan" style="padding:0;margin: auto;">
    <canvas style="margin:auto;" id="canvas" hidden></canvas>
</div>