
<?php
    use_javascript("jquery/jqplot/jquery.jqplot.min.js");
    use_javascript("jsQR.min.js");
    
    use_stylesheet("jquery.mobile-1.4.5.min.css");
    use_stylesheet("jquery/jqplot/jquery.jqplot.min.css");

    slot('h1', '<h1 class="header-text" style="margin:0 auto;">蒸着工程組み合わせ | Nalux '.$sf_request->getParameter('plant').' </h1>');
    $btn='<div style="float:right;" id="h_button"><button onclick="openWelCounter()">実績入力画面</button></div>';
    slot('cd',$btn);
 ?>
<meta name="viewport" content="initial-scale=.8">

<style type="text/css">
    #content {width:100%; font-size:18px; text-align;margin:auto;}

    #tab_item .ui-button .ui-button-text {width:25px;height:25px;padding:0;}

    .fl { float: left;}
    .fr { float: right;}
    #ditemsetpop { padding-top:2px;}
    #ditemsetpop .set {font-size:90%; line-height:1em;vertical-align: middle;display: block;width:185px; border: 1px #FFF solid;margin-left: -1px;margin-top: -1px;padding: 2px 3px ; }
    .box{ width:114px; text-align:center;float:left;margin:0 2px 2px 0;background-color: #ddd; }

    #main_menu input, #item_area input{padding:2px 0;text-align:center;font-weight:bold;font-size:18px;}
    .n_input{border:none;width:92%;margin:3px;font-size:18px;text-align:center;font-weight:bold;color: #999;background-color: #ddd;}
    #addUser{font-size:80%;padding:0.1em 0.8em;margin: 0 0 0 5px;}
    #entryData span, #状態 span, .btn_ditemset .ui-button-text, #OpenSTD span {padding: 0;}
    #dialog hr {margin:5px 0 5px 0;}
    #user-list {margin-bottom:5px;}
    .main_cont {padding:5px;border:1px solid #00f;border-radius:5px;}
    .chk_proc .ui-button-text {padding: 1px 10px;}
    .ipbox{float:left;margin-right:8px;text-align:center;}
    .ui-widget{font-size:18px;}

    #dialog_user .ui-button-text{
        padding: 0px 5px;
    }

    #h_button .ui-button-text {
        padding: 1px 8px;
    }
    
    #item_area .sum_value{width:60px;text-align:left;padding-left:5px;margin-right:5px;}

    table.type03 {
        font-size:16px;
        border-collapse: collapse;
        text-align: center;
        line-height: 1.5;
        /* border-top: 1px solid #ccc; */
        /* border-left: 1px solid #ccc; */
        table-layout: fixed;
    }
    table.type03 th {
        padding: 3px;
        font-weight: bold;
        vertical-align: middle;
        /* border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc; */
        background: aliceblue;
        box-shadow: 0px 0px 0px 1px #ccc;
    }
    table.type03 td {
        padding: 3px;
        vertical-align: top;
        /* border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc; */
        text-align:center;
        background: #fff;
        box-shadow: 0px 0px 0px 1px #ccc;
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

    .toggle-red{
        -webkit-animation: color-change-red 1s infinite;
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

    .hide-td{
        display: none;
    }

    .table-scroll{
        border: 1px solid #ccc;
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

    @keyframes color-change-red {
        0% {
            background: white;
        }
        50% {
            background: orange;
        }
        100% {
            background: white;
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

    .now-select td{
        background: #0ff !important;
    }

    .mid-item{
        display:none;
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
            searchUser('担当者','');
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

        let mno = "<?=$sf_params->get("mno")?>";
        if(mno!=""){
            $("#wel_machine").val(mno);
        }
        $("#wel_machine").on("change",(e)=>{
            window.history.pushState('', 'Title', '?mno='+e.target.value);
        })

        
        $("#製品コード").on("input",(e)=>{
            if(e.target.value.length>="11"){
                let_start();
            }
        })

        // set_wel_table();

    });

    var lc = null;
    function searchUser(id){
        lc = id;
        $("#dialog_user").dialog("open");
        return false;
    }

    async function let_start(ac){
        if($("#製品コード").val()==""){
            console.log("品目コードを入力してください。");
            return;
        }
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            dataType: 'json',
            async:true,
            data:{
                "ac":"lot_info",
                "lot_mno":$("#wel_machine").val(),
                "item_code":$("#製品コード").val()
            },
            success: function(d){       
                loadingView(false);
                console.log(d);
                if(d=="NG"){
                    openAlert("print","確認","BOM情報がない!");
                    return;
                }else{
                    set_wel_table(d);
                }
            }
        });
    }

    var front_val = null;back_val = null;mid_val = null;
    var part_items = {};
    function set_wel_table(parts_list){
        $.each(parts_list.wel_info, function (ik, iv) { 
            if(iv.part_name=="FRONT"){
                //FRONT
                part_items.front=iv.part_name;
                $("#front_code").html(iv.itempprocord);
                let front_item = parts_list.items[iv.itempprocord];
                let front_line =``;
                $.each(front_item, function (k, v) { 
                    front_line+=`<tr class="parts-item-font">
                        <td>`+v.hgpd_moldlot+`</td>
                        <td>`+v.hgpd_cav+`</td>
                        <td>`+v.sum_inv+`</td>
                        <td>`+v.tag_num+`</td>
                        <td class="hide-td">`+v.hgpd_itemcode+`</td>
                    </tr>`;
                });
            
                $("#front_table_bd").html(front_line);
                $(".parts-item-font").on("click",(e)=>{
                    // $(".parts-item-font").css({"background":"white"});
                    $(".parts-item-font").removeClass("now-select");
                    // $(e.currentTarget).css({"background":"orange"});
                    $(e.currentTarget).addClass("now-select");

                    let cells = Array.from(e.currentTarget.cells).map(cell => cell.textContent);
                    front_val = trans_to_val(cells,["front_lot","front_cav","front_num","front_tag_num","front_code"]);
                    setTagNum();
                })

            }else if(iv.part_name=="BACK"){
                //BACK
                part_items.front=iv.part_name;
                $("#back_code").html(iv.itempprocord);
                let back_item = parts_list.items[iv.itempprocord];
                let back_line =``;
                $.each(back_item, function (k, v) { 
                    back_line+=`<tr class="parts-item-back">
                        <td>`+v.hgpd_moldlot+`</td>
                        <td>`+v.hgpd_cav+`</td>
                        <td>`+v.sum_inv+`</td>
                        <td>`+v.tag_num+`</td>
                        <td class="hide-td">`+v.hgpd_itemcode+`</td>
                    </tr>`;
                });
            
                $("#back_table_bd").html(back_line);
                $(".parts-item-back").on("click",(e)=>{
                    // $(".parts-item-back").css({"background":"white"});
                    $(".parts-item-back").removeClass("now-select");
                    $(e.currentTarget).addClass("now-select");
                    // $(e.currentTarget).css({"background":"orange"});

                    let cells = Array.from(e.currentTarget.cells).map(cell => cell.textContent);
                    back_val = trans_to_val(cells,["back_lot","back_cav","back_num","back_tag_num","back_code"]);
                    setTagNum();
                })
            }else if(iv.part_name=="MID"){
                //MID
                part_items.front=iv.part_name;
                $(".mid-item").show();
                $("#mid_code").html(iv.itempprocord);
                let mid_item = parts_list.items[iv.itempprocord];
                let mid_line =``;
                $.each(mid_item, function (k, v) { 
                    mid_line+=`<tr class="parts-item-mid">
                        <td>`+v.hgpd_moldlot+`</td>
                        <td>`+v.hgpd_cav+`</td>
                        <td>`+v.sum_inv+`</td>
                        <td>`+v.tag_num+`</td>
                        <td class="hide-td">`+v.hgpd_itemcode+`</td>
                    </tr>`;
                });
            
                $("#mid_table_bd").html(mid_line);
                $(".parts-item-mid").on("click",(e)=>{
                    // $(".parts-item-mid").css({"midground":"white"});
                    $(".parts-item-mid").removeClass("now-select");
                    $(e.currentTarget).addClass("now-select");
                    // $(e.currentTarget).css({"midground":"orange"});

                    let cells = Array.from(e.currentTarget.cells).map(cell => cell.textContent);
                    mid_val = trans_to_val(cells,["mid_lot","mid_cav","mid_num","mid_tag_num","mid_code"]);
                    setTagNum();
                })
            }
        
        });

    }

    var combi_tag_num = "";
    function setTagNum(){
        if(front_val != null && back_val != null){
            combi_tag_num = front_val.front_tag_num;
            if(parseInt(combi_tag_num)>parseInt(back_val.back_tag_num)){
                combi_tag_num = back_val.back_tag_num;
            }
            if(mid_val != null){
                if(parseInt(combi_tag_num)>parseInt(mid_val.mid_tag_num)){
                    combi_tag_num = mid_val.mid_tag_num;
                }
            }
            $("#rfid_tag_num").val(combi_tag_num)
        }
    }

    function set_combi(){
        // console.log(front_val);
        // console.log(back_val);
        // console.log(mid_val);
        if(front_val == null || back_val == null){
            openAlert("print","確認","部品を選択してください。");
            return;
        }

        if($("#rfid_tag_num").val()==""){
            openAlert("print","確認","RFIDタグ数量を入力してください。");
            return;
        }

        const merge_arr = { ...front_val, ...back_val, ...mid_val };
        // const merge_arr= Object.assign(front_val,back_val,mid_val);
        // console.log(merge_arr);

     
        let combi_num = min_search([merge_arr.front_num,merge_arr.back_num,merge_arr.mid_num]);
        // console.log(combi_num);
     
        let combi_line=`<tr class="combi-item">
            <td name="main][menu"><button>x</button></td>
            <td name="main][work_date">`+$("#work_date").val()+`</td>
            <td name="parts][front][lot">`+merge_arr.front_lot+`</td>
            <td name="parts][front][cav">`+merge_arr.front_cav+`</td>
            <td name="parts][back][lot">`+merge_arr.back_lot+`</td>
            <td name="parts][back][cav">`+merge_arr.back_cav+`</td>
            <td class="mid-item" name="parts][mid][lot">`+merge_arr.mid_lot+`</td>
            <td class="mid-item" name="parts][mid][cav">`+merge_arr.mid_cav+`</td>
            <td name="main][item_num">`+combi_num+`</td>
            <td name="main][rfid_tag_num">`+$("#rfid_tag_num").val()+`</td>
            <td class="hide-td" name="parts][front][code">`+merge_arr.front_code+`</td>
            <td class="hide-td" name="parts][back][code">`+merge_arr.back_code+`</td>
            <td class="hide-td" name="parts][mid][code">`+merge_arr.mid_code+`</td>
            <td class="hide-td" name="main][machine">`+$("#wel_machine").val()+`</td>
            <td class="hide-td" name="main][code">`+$("#製品コード").val()+`</td>
        </tr>`;
        $("#combi_table_bd").append(combi_line);
        front_val=null;back_val=null;
        if(mid_val){
            $(".mid-item").show();
            mid_val=null;
        }
        // $(".parts-item-back").css({"background":"white"});
        // $(".parts-item-font").css({"background":"white"});
        $(".now-select").addClass("slected-td");
        $(".slected-td td").css({"background":"#acacac"});
        $(".slected-td").off( "click");
        $(".slected-td").removeClass("now-select");

    }

    function min_search(arr){
        let min = parseInt(arr[0]);
        $.each(arr, function (a, b) { 
            if(min>parseInt(b)){
                min = parseInt(b);
            }
        });
        return min;
    }

    function combination_entry(){
        let check = {
            "担当者":$("#担当者").val()
        }
        let c = 0;msg="未入力の項目を入力してください。";
        $.each(check, function (a, b) { 
            if(b==""){
                c++;
                msg+="<li><b>"+a+"</b></li>";
            }
        });

        if(c>0){
            openAlert("print","確認",msg);
            return;
        }
        
        let entry_data = get_val_by_name("combi_table_bd");
        console.log(entry_data);
        // return;
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "?ac=combinationEntry",
            dataType: 'json',
            async:true,
            data:{
                "data":entry_data
            },
            success: function(d){       
                console.log(d);
                loadingView(false);
                if(d=="OK"){
                    openAlert("print","確認","登録しました。");
                    $("#combi_table_bd").html("");  
                }
            }
        });
    }

    function get_table_val(id,name_list){
        const table = document.querySelector("#"+id); 
        var rows = Array.from(table.rows);
        var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
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

    function get_val_by_name(id){
        const table = document.querySelector("#"+id); 
        var rows = Array.from(table.rows);
        // console.log(rows);
        var cells = rows.map(row => Array.from(row.cells));
        // return;
        var data = [];
        $.each(cells,function(a,b){
            let r ={};
            $.each(b,function(c,d){
                if(c>0 && d.textContent && d.textContent != "undefined"){
                    r[d.attributes.name.value]=d.textContent;
                }
                r["main][user"]=$("#担当者").val();
            })
            data.push(r)
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

    function setUser(name){
        console.log(lc);
        if(lc){
            if(lc=="担当者"){
                $("#"+lc).val(name);
            }else{
                let sp_id = lc.split("_");
                $("#"+lc).html(name);
            }
        }else{
            openAlert("print","確認","どこの担当者？");
        }
        $( "#dialog_user" ).dialog( "close" );
        return false;
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

    function rfid_status(rfid,type,cb){
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

    function del_msg(){
        setTimeout(() => {
            $("#err_msg").html("");
        }, 10000);
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
                width: 600,
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

    function openWelCounter(){
        url = "WeldingCounter?mno="+$("#wel_machine").val();
        // window.open(url);
        location.href = url;
    }

</script>

<div id="main_menu" style="font-weight:bold;text-align:center;width:fit-content;margin:auto;">
    <div class='ipbox'>
        <p><lable >号機</lable></p>
        <select id="wel_machine" name="基本][wel_machine" value="" style="width:90px;height:36px;font-weight:bold;" >
            <option value="Wel1">Wel1</option>
            <option value="Wel2">Wel2</option>
            <option value="Wel3">Wel3</option>
            <option value="Wel4">Wel4</option>
            <option value="Wel5">Wel5</option>
            <option value="Wel6">Wel6</option>
        </select>
    </div>
    <div class='ipbox'>
        <p><lable >作業日</lable></p>
        <input id="work_date" type="date" name="基本][work_date" value="<?=date("Y-m-d")?>" style="width:160px;height: 28px;font-size: 16px;" />
        <input type="hidden" id="lot_id" name="基本][LotId" value="<?=$lot_info[0]["lot_id"]?>" />
    </div>      
    <div class='ipbox'>
        <p><label for="製品コード">製品コード</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][製品コード" id="製品コード" list="list_items" style="width:175px;"/>
        <datalist id="list_items">
            <?php foreach($list_items as $key => $val){ ?>
                <option value="<?=$val["code"]?>" label="<?=$val["code"]."_".$val["itemname"]?>"><?=$val["code"]?></option>
            <?php } ?>
        </datalist>
    </div>
    <div class='ipbox'>
        <p><label for="工程">工程</label></p>
        <input type="text" class="items_info_ip" value="<?=$process?>" name="基本][工程" id="工程" readonly="readonly" style="width:100px;"/>
    </div>
    <!-- <div class='ipbox'>
        <p><label for="開始日時">開始日時</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][開始" id="開始日時" readonly="readonly" onclick="datetime(this.id);" style="width:175px;"/>
    </div>      
    <div class='ipbox'>
        <p><label for="終了日時">終了日時</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][終了" id="終了日時" readonly="readonly" onclick="datetime(this.id);" style="width:175px;"/>
    </div>
    <div class='ipbox'>
        <p><label for="作業時間">作業時間</label></p>
        <input type="text" value="" name="基本][作業時間" id="作業時間" style="width:80px;"/>
        <div style="clear:both;"></div>
    </div> -->
    <div class='ipbox'>
        <p><label for="担当者">担当者</label></p>
        <input type="text" class="items_info_ip" value="" name="基本][担当者" id="担当者" onclick="searchUser(this.id);" readonly="readonly" style="width:180px;"/>
    </div>

    <input type="hidden" id="plant" name="基本][工場" value="<?=$plant?>" />
    <input type="hidden" id="plantid" name="基本][工場ID" value="<?=$plant_id?>" />
    <input type="hidden" id="item_list" name="" value="" />
    <input type="hidden" id="cavs_list" name="" value="" />
    <input type="hidden" id="printer_ip" name="printer_ip" value="" />
    <input type="hidden" id="client_folder" name="client_folder" value="" />
    <div style="clear:both;"></div>
</div>

<div id="item_area" style="display: flex;margin:10px auto;width: fit-content;padding:10px;border:2px solid blue;border-radius:8px;height: calc(100% - 160px);">
    <div id="front_area" style="min-width:200px;">
        <label style="font-weight: bold;">在庫情報</label>
        <div class="table-scroll" style="overflow-y: scroll;height: calc(100% - 25px); ">
            <table id="front_table" class="type03" style="">
                <thead style="text-align:center;position:sticky;top:0;">
                    <tr>
                        <th style="font-weight:bold;" colspan="4">FRONT<br><span id="front_code"></span></th>
                    </tr>
                    <tr>
                        <th style="">成形ロット</th>
                        <th style="width:50px;">キャビ</th>
                        <th style="width:50px;">数量</th>
                        <th style="width:50px;">ID数</th>
                    </tr>
                </thead>
                <tbody id="front_table_bd">

                </tbody>
            </table>
        </div>
    </div>

    <div id="back_area" style="min-width:200px;margin-left:4px;">
        <label>　</label>
        <div class="table-scroll" style="overflow-y: scroll;height: calc(100% - 25px); ">
            <table id="back_table" class="type03" style="">
                <thead style="text-align:center;position:sticky;top:0;box-shadow: 0px 0px 0px 1px #ccc;">
                    <tr>
                        <th style="font-weight:bold;" colspan="4">BACK<br><span id="back_code"></span></th>
                    </tr>
                    <tr>
                        <th style="">成形ロット</th>
                        <th style="width:50px;">キャビ</th>
                        <th style="width:50px;">数量</th>
                        <th style="width:50px;">ID数</th>
                    </tr>
                </thead>
                <tbody id="back_table_bd">

                </tbody>
            </table>
        </div>
    </div>

    <div class="mid-item" id="mid_area" style="min-width:200px;margin-left:4px;">
        <label>　</label>
        <div class="table-scroll" style="overflow-y: scroll;height: calc(100% - 25px); ">
            <table id="mid_table" class="type03" style="">
                <thead style="text-align:center;position:sticky;top:0;box-shadow: 0px 0px 0px 1px #ccc;">
                    <tr>
                        <th style="font-weight:bold;" colspan="4">MID<br><span id="mid_code"></span></th>
                    </tr>
                    <tr>
                        <th style="">成形ロット</th>
                        <th style="width:50px;">キャビ</th>
                        <th style="width:50px;">数量</th>
                        <th style="width:50px;">ID数</th>
                    </tr>
                </thead>
                <tbody id="mid_table_bd">

                </tbody>
            </table>
        </div>
    </div>

    <div style="width:85px;margin: 20px 5px;text-align: center;" >
        <label>RFID枚数</label>
        <div style="clear:both;"></div>
        <input id="rfid_tag_num" value="" style="width:80%;margin:10px 0;" />
        <div style="clear:both;"></div>
        <button onclick="set_combi();" style="height:42px;margin: auto 5px;">→</button>
    </div>

    <div id="combi_area" style="min-width:500px;">
        <label style="font-weight: bold;">組合せリスト</label>
        <div class="table-scroll" style="overflow-y: scroll;height: calc(100% - 25px); ">
            <table id="combi_table" class="type03" style="">
                <thead style="text-align:center;position:sticky;top:0;box-shadow: 0px 0px 0px 1px #ccc;">
                    <tr>
                        <th style="width:60px;height: 55px;">menu</th>
                        <th style="width:60px;">日付</th>
                        <th style="">FRONT<br>成形ロット</th>
                        <th style="width:60px;">キャビ</th>
                        <th style="">BACK<br>成形ロット</th>
                        <th class="mid-item" style="width:60px;">キャビ</th>
                        <th class="mid-item" style="">MID<br>成形ロット</th>
                        <th style="width:60px;">キャビ</th>
                        <th style="width:60px;">数量</th>
                        <th style="width:60px;">RFID<br>枚数</th>
                    </tr>
                </thead>
                <tbody id="combi_table_bd">

                </tbody>
            </table>
        </div>
    </div>

</div>

<div>
    <button id="entryData" type="button" onclick="combination_entry();" class="" style="float:right;margin-right:20px;font-size:22px;font-weight:bold;padding:.5em .5em;">登録</button>
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