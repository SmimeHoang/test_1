<?php
    slot('h1', '<h1 style="margin:0 auto;font-size:100%;">ID発注台帳 | Nalux | '.$sf_params->get("plant").'</h1>');
?>

<style type="text/css">
    #content{
        padding:35px 5px 5px 5px;
        width: auto;
    }
    #ip_section{
        /* display: -webkit-box; */
        float:left;
        margin: 10px;
        padding:10px;
        border: solid 1px #ccc;
    }
    .main-ip{
        height:28px;
        font-size:16px;
        font-weight:bold;
    }
    #btn-entry .ui-button-text{
        padding:2px 15px;
    }
    .btn_opls .ui-button-text{
        padding:1px 15px;
    }
    table.type03 {font-size:16px;border-collapse:collapse;text-align:left;line-height:1.5em;border-top:1px solid #ccc;border-left:6px solid #369;table-layout:fixed;margin: 5px 0 5px 5px;}
    table.type03 th {padding:3px;font-weight:bold;text-align:center;vertical-align:middle;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;}
    table.type03 td {padding:3px;vertical-align:middle;border-right:1px solid #ccc;border-bottom:1px solid #ccc;overflow:hidden;white-space: nowrap;}
    table.type03 input {border:none;width:130px;font-size:16px;}
</style>

<script type="text/javascript">
    var plant_name = "<?=$sf_params->get("plant")?>";
    var user = "<?=$sf_params->get("user")?>";

    $(document).ready(function(){
        $("button").button();

        $( "#alert" ).dialog({
            autoOpen: false,
            width:900,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });

        $( "#dialog_user" ).dialog({
            autoOpen: false,
            width:$(window).width()-40,
            modal:true,
            position:["center", 100],
            buttons: [{ text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });
        
        if(user!=""){
            $("#user").val(user);
        }

        var plant = plant_name.substr(0,plant_name.length-2);
        var gr = "製造係_1班";
        if(plant=="山崎"){
            gr = "製造1係";
        }
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+decodeURIComponent(plant)+"&gp2="+decodeURIComponent(gr)/*+"&callback=?"*/,function(data){
            $("#ap-user-select").html(data);
        });
        
        $( "#user" ).click(function( event ) {
            $( "#dialog_user" ).dialog( "open" );
            event.preventDefault();
        });

        if($("#user").val()==""){
            $( "#dialog_user" ).dialog( "open" );
        }

        $("#add_num").on("focus",function(e){
            if($("#add_num").val()!="" || $("#add_num").val()!="0"){
                $("#add_num").select();
            }
        });

        $("#start_num").on("focus",function(e){
            if($("#start_num").val()!="" || $("#start_num").val()!="0"){
                $("#start_num").select();
            }
        })

        getHistory();
    });

    function getHistory(){
        let plant = "<?=$sf_params->get("plant")?>";
        if(plant==""){
            alert("工場を選択してください。");
            location.href="/Top/realtime";
            return;
        }
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getHistory",
                plant:plant
            },
            dataType: "json",
            success:function (d) {
                if(d["last_item"].length>0){
                    let st_num = parseInt(d.last_item[0].wiol_start_num)+parseInt(d.last_item[0].wiol_o_number);
                    $("#start_num").val(st_num);
                    $("#add_num").val(0);
                }else{
                    $("#start_num").val(1);
                    $("#add_num").val(0);
                }
                if(d["history_list"].length>0){
                    let hc = "";
                    $.each(d["history_list"], function (a, b) { 
                        hc+="<tr>";
                        hc+="<td>"+b.wiol_id+"</td>";
                        hc+="<td>"+b.wiol_o_date+"</td>";
                        hc+="<td style='text-align:right;'>"+b.wiol_start_num+"</td>";
                        hc+="<td style='text-align:right;'>"+b.wiol_o_number+"</td>";
                        hc+="<td>"+b.wiol_username+"</td>";
                        hc+="<td>"+b.wiol_plant+"</td>";
                        hc+="<td>"+b.wiol_created_at+"</td>";
                        if(plant==b.wiol_plant){
                            hc+="<td style='text-align:center;'><button class='btn_opls' onclick='openLinkSite(`"+b.wiol_id+"`);' style='font-size:16px;'>品目紐付</button></td>";
                        }else{
                            hc+="<td></td>";
                        }
                        hc+="</tr>";
                    });
                    $("#history_content").html(hc);
                    $(".btn_opls").button();
                }
            }
        });
    }

    function entryData(){

        var c={};
        c["発注日"]=$("#work_day").val();
        c["開始番号"]=$("#start_num").val();
        c["発注数"]=$("#add_num").val();
        c["担当者"]=$("#user").val();
        
        msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
        var errc=0;

        $.each(c,function(key) {
            if($.trim(c[key])=="" || $.trim(c[key])=="0"){
                msg = msg +"<li>"+key+"</li>\n";
                errc++;
            }
        });

        if(errc>0){
            openAlert("確認",msg);
            return;
        }else{
            loadingView(true);
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    ac:"entryData",
                    q:{
                        work_day:$("#work_day").val(),
                        start_num:$("#start_num").val(),
                        add_num:$("#add_num").val(),
                        user:$("#user").val(),
                        plant:"<?=$sf_params->get("plant")?>"
                    }
                },
                dataType: "json",
                success: function (d) {
                    loadingView(false);
                    if(d=="OK"){
                        openAlert("登録完了！","登録しました。")
                        getHistory();
                    }else{
                        openAlert("確認",d);
                    }
                }
            });
        }
    }

    function searchUser(){
        $.getJSON("/LotManagement/BarcodeUserSet?ac=user&code="+decodeURIComponent($("#input_name").val()),function(data){
            if(data.length>0){
                $("#user").val(data[0].user);
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
        $("#user").val(name);
        window.history.pushState('', 'Title', '?plant=<?=$sf_params->get("plant")?>&user='+name);
        $( "#dialog_user" ).dialog( "close" );
    }

    function openLinkSite(id){
        console.log(id);
        if($("#user").val()==""){
            openAlert("確認","まず、担当者を選択してください。");
            return;
        }else{
            let url = "/RFIDReport/RfidManager?plant=<?=$sf_params->get("plant")?>&order_id="+id+"&user="+$("#user").val();
            window.open(url);
        }
    }

    function openAlert(title,msg,btn,callback){
        if(!btn){
            btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
                return;
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

</script>

<div id="ip_section">
    <div style="float:left;margin-right:20px;"><label for="work_day" style="margin-right:3px;font-weight:bold;">発注日</label><input id='work_day' type="date" class="main-ip" value ='<?=date("Y/m/d");?>' style='text-align:right;width:135px;' /></div>
    <div style="float:left;margin-right:20px;"><label for="start_num" style="margin-right:3px;font-weight:bold;">開始番号</label><input id='start_num' class="main-ip" value ='' style='text-align:right;width:80px;' /></div>
    <div style="float:left;margin-right:20px;"><label for="add_num" style="margin-right:3px;font-weight:bold;">発注数</label><input id='add_num' class="main-ip" value ='' style='text-align:right;width:80px;' /></div>
    <div style="float:left;margin-right:20px;"><label for="user" style="margin-right:3px;font-weight:bold;">担当者</label><input type="text" value="" name="user" id="user" class="main-ip" readonly="readonly" style="width:180px;font-weight:bold;"/></div>
    <button id="btn-entry" type="button" onclick="entryData();" class="" style="float:right;">登録</button>
</div>
<div style="clear:both;"></div>

<label style="margin-left:5px;font-weight:bold;">注文履歴</label>
<div id="history_section">
    <table class="type03">
        <thead>
            <tr>
                <th style="width:60px;">管理ID</th>
                <th style="width:100px;">発注日</th>
                <th style="width:100px;">開始番号</th>
                <th style="width:100px;">発注数</th>
                <th style="width:100px;">担当者</th>
                <th style="width:100px;">工場</th>
                <th style="width:160px;">作成日付</th>
                <th style="width:115px;">メニュー</th>
            </tr>
        </thead>
        <tbody id="history_content">
        </tbody>
    </table>
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