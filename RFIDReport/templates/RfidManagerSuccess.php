<?php
    $btn='<button class="btn-header" onclick="openFixRfid()" style="float:right;margin-right:5px;">品目紐付修正</button>';
    slot('h1', '<h1 style="margin:0 auto;font-size:100%;">RFID品目紐付 | Nalux </h1>'.$btn);
?>

<style type="text/css">
    #content{
        padding:35px 5px 5px 5px;
        width: auto;
    }
    label{
        font-size:16px;
    }
    #ip_section{
        float:left;
        margin: 5px;
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
    .btn_csv .ui-button-text{
        padding:1px 15px;
    }
    .btn_opls .ui-button-text{
        padding:2px 15px;
    }
    /* .ui-dialog-buttonset{
        width: 100%;
    } */
    .btn-left .ui-button-text,.btn-right .ui-button-text {
        padding: .4em 1em;
    }
    .btn-left{
        /* float:left; */
    }
    .btn-right{
        /* float:right; */
    }
    table.type03 {font-size:14px;border-collapse:collapse;text-align:left;line-height:1.5em;border-top:1px solid #ccc;border-left:6px solid #369;table-layout:fixed;white-space: nowrap;}
    table.type03 th {padding:2px 4px;font-weight:bold;text-align:center;vertical-align:middle;color:#153d73;border-right:1px solid #ccc;border-bottom:1px solid #ccc;position: sticky;top: 0;background-color: white;z-index: 1000;box-shadow:2px 2px 4px 0px #ccc}
    table.type03 td {padding:2px 4px;vertical-align:middle;border-right:1px solid #ccc;border-bottom:1px solid #ccc;text-align:center;}
    table.type03 input {border:none;width:130px;font-size:14px;}
    .menu_btn{
        margin:2px;
    }
    .ui-dialog .ui-dialog-buttonpane{
        margin:0;
    }
    .tr_blank td{
        padding: 1px 4px !important;
    }
    .btn-header .ui-button-text{
        padding: 1px 8px ; 
    }
</style>

<script type="text/javascript">

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

        $( "#set_list" ).dialog({
            autoOpen: false,
            width:650,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });

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

        
        $("#sheets_num").on("keyup",function(e){
            let max_num = parseInt("<?=$unlink_num?>");
            let sheets_num = parseInt(e.target.value);
            if(!sheets_num || sheets_num<0){
                $("#end_num").val("");
                $("#"+e.target.id).val("");
                return;
            }
            if(sheets_num>max_num){
                sheets_num = max_num;
                $("#"+e.target.id).val(sheets_num);
            }
            let end_num = parseInt($("#start_num").val())+sheets_num-1;
            $("#end_num").val(end_num);
        })

        $("#end_num").on("keyup",function(e){
            let max_num = parseInt("<?=$order_info["wiol_start_num"]+$order_info["wiol_o_number"]-1?>");
            let sheets_num = parseInt(e.target.value)-parseInt($("#start_num").val())+1;

            if(!sheets_num){
                $("#sheets_num").val("");
                $("#"+e.target.id).val("");
                return;
            }
            if(e.target.value>max_num){
                sheets_num=max_num-parseInt($("#start_num").val())+1;
                $("#"+e.target.id).val(max_num);
            }
            $("#sheets_num").val(sheets_num);
        })

        $("#itemcode").on("change",function(e){
            var ip_text = null;
            $('#item_list').find('option[value="'+e.target.value+'"]').filter(function(){
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

        var timer = false;
        $(window).resize(function() {
            if (timer !== false) {
                clearTimeout(timer);
            }
            timer = setTimeout(function() {
                table_resize();
            }, 100);
        });
    
    });

    function openFixRfid(){
        let url="/RFIDReport/RFIDRecycle?plant=<?=$sf_params->get("plant")?>&user=<?=$sf_params->get("user")?>";
        window.open(url);
    }

    function entrySetID(){
        var c={};
        c["開始番号"]=$("#start_num").val();
        c["終了番号"]=$("#end_num").val();
        c["品目コード"]=$("#itemcode").val();
        c["区分"]=$("#id_type").val();
        c["まるめ数"]=$("#around_num").val();
        
        msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
        var errc=0;

        $.each(c,function(key) {
            if($.trim(c[key])=="" || $.trim(c[key])==0){
                msg = msg +"<li>"+key+"</li>\n";
                errc++;
            }
        });

        if(errc>0){
            openAlert("確認",msg);
            return;
        }else{

            start_num
            if(parseInt($("#start_num").val())<parseInt("<?=$this_start?>")){
                openAlert("確認","RFID台帳開始より開始番号の方は小さいので、登録出来ません。");
                return;
            }else if(parseInt($("#end_num").val())<parseInt($("#start_num").val())){
                openAlert("確認","開始番号より終了番号の方は小さいので、登録出来ません。");
                return;
            }else{
                loadingView(true);
                $.ajax({
                    type: "GET",
                    url: "",
                    data: {
                        ac:"setId",
                        q:{
                            id:"<?=$sf_params->get("order_id")?>",
                            start_num:$("#start_num").val(),
                            end_num:$("#end_num").val(),
                            itemcode:$("#itemcode").val(),
                            id_type:$("#id_type").val(),
                            around_num:$("#around_num").val(),
                            user:"<?=$sf_params->get("user")?>"
                        }
                    },
                    dataType: "json",
                    success: function (d) {
                        loadingView(false);
                        if(d=="OK"){
                            openAlert(
                                "完了！",
                                "登録しました。",
                                [{ class:"btn-right",text:"CSV抽出",click :function(ev) {
                                    let url = "/RFIDReport/RfidManager?ac=getCSV&req[wiol_id]="+encodeURIComponent(<?=$sf_params->get("order_id")?>)+"&req[itemcode]="+encodeURIComponent($("#itemcode").val())+"&req[type]="+encodeURIComponent($("#id_type").val());
                                    window.open(url);
                                    $( this ).dialog( "close" );
                                    setTimeout(() => {
                                        location.reload();
                                    }, 0);
                                    return;
                                }},{ class:"btn-left",text:"閉じる",click :function(ev) {
                                    $( this ).dialog( "close" );
                                    location.reload();
                                    return;
                                }}]
                            );
                        }else{
                            openAlert("Error",d);
                            return;
                        }
                    }
                });
            }

        }
    }

    function getCSV(id,itemcode,type,created_at){
        $( "#set_list" ).dialog( "close" );
        let url = "/RFIDReport/RfidManager?ac=getCSV&req[wiol_id]="+encodeURIComponent(id)+"&req[itemcode]="+encodeURIComponent(itemcode)+"&req[type]="+encodeURIComponent(type)+"&req[created_at]="+encodeURIComponent(created_at);
        // console.log(url);
        // return;
        window.open(url);
    }

    function op_cl_gr(itemcode,type){
        let this_id = "group_"+itemcode+"_"+type;
        // console.log($("#"+this_id+" span").html());
        if($("#"+this_id+" span").html()=="開く"){
            $(".children_"+itemcode+"_"+type).show();
            $("#"+this_id+" span").html("閉じる")
        }else{
            $(".children_"+itemcode+"_"+type).hide();
            $("#"+this_id+" span").html("開く")
        }
        return;
    }

    function openListMenu(action,wiol_id,itemcode,type,set_date){
        let title = type+"　"+itemcode;
        let btn_id = "";
        if(action=="open_children"){
            console.log(type)
        }else if(action=="openlist"){
            btn_id="group_"+itemcode+"_"+type;
            title+="　紐付されたリスト表現"
        }else{
            btn_id="csv_"+itemcode+"_"+type;
            title+="　CSV出力"
        }

        var options = {
            "title":title,
            width: "auto",
            position:{ my: "left top", at: "left bottom", of: "#"+btn_id},
        };
        let msg="";
        $.each(set_date.split(","),function(k,v){
            if(action=="openlist"){
                msg+="<button class='menu_btn' onclick='openLinkedList(`"+wiol_id+"`,`"+itemcode+"`,`"+type+"`,`"+v+"`)'>"+v+"</button>";
            }else{
                msg+="<button class='menu_btn' onclick='getCSV(`"+wiol_id+"`,`"+itemcode+"`,`"+type+"`,`"+v+"`)'>"+v+"</button>";
            }
        })
        
        $("#set_list_message").html(msg);
        $("button").button();
        $( "#set_list" ).dialog( "option",options);
        $( "#set_list" ).dialog( "open" );

    }

    function openLinkedList(wiol_id,itemcode,type,created_at){
        $( "#set_list" ).dialog( "close" );
        loadingView(true);
        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getLinkedList",
                req:{
                    wiol_id:wiol_id,
                    itemcode:itemcode,
                    type:type,
                    created_at:created_at
                }
            },
            dataType: "json",
            success: function (res) {
                // console.log(res);
                loadingView(false);
                let linked_list = "";
                let it_name = res[0].itemname;
                let it_class = res[0].wim_class;
                $("#linked_list_label").html(it_name+"："+it_class);
                $.each(res, function (a, b) { 
                    let status = b.wim_status;
                    // console.log(status);
                    if(status==null){
                        status = "未使用";
                    }
                    let stt_color = "darkgreen";
                    if(status=="未使用"){
                        stt_color = "darkturquoise";
                    }else if(status=="再発行"){
                        stt_color = "darkorange";
                    }else if(status=="使用中"){
                        stt_color = "darkgreen";
                    }else{
                        stt_color = "red"; 
                    }
                    linked_list+=`<tr>
                        <td>`+(a+1)+`</td>
                        <td>`+b.rfid+`</td>
                        <td>`+b.wim_itemcode+`</td>
                        <td>`+b.itemname+`</td>
                        <td>`+b.wim_class+`</td>
                        <td style="text-align:right;">`+b.wim_number+`</td>
                        <td style="color:`+stt_color+`;">`+status+`</td>
                        <td>`+b.wim_username+`</td>
                        <td>`+b.wim_created_at+`</td>
                        <td><button id="" class="btn_csv" onclick="getFixForm('`+b.rfid+`','`+b.wim_itemcode+`','`+b.wim_class+`','`+b.wim_number+`','`+status+`','`+b.wim_created_at+`','`+b.wiol_id+`');" style="font-size:16px;">修正</button></td>
                    </tr>`;
                });
                $("#manager_linked").html(linked_list);
                $("#linked_list").show();
                table_resize();
            }
        });
    }

    function getFixForm(rfid,itemcode,type,number,status,created_at,wiol_id){
        let msg = `<table class='type03' style="font-size:85%">
        <thead>
            <th style="">RFID</th>
            <th style="">区分</th>
            <th style="">品目コード</th>
            <th style="">まるめ数</th>
            <th style="">状態</th>
        </thead>
        <tbody>`;
        msg+=`<tr id="check_row" class="add_item" style="background:#FFF;" >
            <td><input type="text" class="ip-rfid" name="rfid" value="`+rfid+`" readOnly="readOnly"  style="font-weight:bold;width:350px;text-align:left;" /></td>
            <td>
                <select id='change_type' class="ip-type" name="type" value="" style="">
                    <option value="仕掛ID">仕掛ID</option>
                    <option value="完成ID">完成ID</option>
                </select>
            </td>
            <td><input type="text" class="ip-itemcode" name="itemcode" value="`+itemcode+`" style="width:120px;text-align:center;" /></td>
            <td><input type="text" class="ip-number" name="number" value="`+number+`" style="width:80px;text-align:right;" /></td>
            <td>
                <select id='change_status' class="ip-status" name="status" value="" style="">
                    <option value="未使用">未使用</option>
                    <option value="使用中">使用中</option>
                    <option value="再発行">再発行</option>
                </select>
            </td>
        </tr>`;  
        msg+= "</tbody></table>";
        msg+= "<label style='float:left;margin-top:5px;'>*内容を再入力して【変更】ボタンで実行してください。</label>";
        var options = {"title":"紐付修正",
            position:["center",130],
            width: "auto",
            buttons: 
                [{ class:"btn-left",text:"閉じる",click :function(ev) {
                    $( this ).dialog( "close" );
                }},{ class:"btn-right",text:"変更",click :function(ev) {
                    // $( this ).dialog( "close" );
                    console.log($(".add_item"));
                    let add_data = {};
          
                    let r ={};
                    $.each($(".add_item")[0].cells,function(a,b){
                        let ip = b.children[0];
                        if(ip){
                            r[ip.name]=ip.value;
                        }
                    })
                    console.log(r);
                    // addUnknowData(list_rfid);
                    setTimeout(() => {
                        getUpdateRfid(r,created_at,wiol_id)
                    }, 0);
                }}],
            open: function() {
                $('.ui-dialog :input').blur();
                $(".ip-rfid").css({"direction": "rtl"});
            }
        };
        $("#message").html(msg);
        $("#change_type").val(type);
        $("#change_status").val(status);
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
    }

    function getUpdateRfid(d,created_at,wiol_id){
        $.ajax({
            type: "POST",
            url: "?ac=getUpdateRfid",
            data: {
                d,
                user:"<?=$sf_params->get("user")?>"
            },
            dataType: "json",
        })
        .done(function(res){
            // console.log(res)
            if(res=="OK"){
                openAlert("完了！！！","RFIDの紐づけを変更されました。");
                openLinkedList(wiol_id,d.itemcode,d.type,created_at);
            }else{
                openAlert("変更できません！！！",res)
            }
        });
    }

    function openAlert(title,msg,btn,callback){
        if(!btn){
            btn = [{ class:"btn-right",text:"閉じる",click :function(ev) {
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

    function table_resize(){
        let lh = $(window).height()-$("#top_section").height()-70;
        $("#linked_list").height(lh);
    }

</script>
<div id="top_section">
    <div style="float:left;margin-right:20px;"><label style="margin-right:3px;">発注日：<b><?=$order_info["wiol_o_date"]?></b></label></div>
    <div style="float:left;margin-right:20px;"><label style="margin-right:3px;">開始番号：<b><?=$order_info["wiol_start_num"]?></b></label></div>
    <div style="float:left;margin-right:20px;"><label style="margin-right:3px;">発注数：<b><?=$order_info["wiol_o_number"]?></b></label></div>
    <div style="float:left;margin-right:20px;"><label style="margin-right:3px;">開始：<b><?=$this_start?></b></label></div>
    <div style="float:left;margin-right:20px;"><label style="margin-right:3px;">終了：<b><?=$order_info["wiol_start_num"]+$order_info["wiol_o_number"]-1?></b></label></div>
    <div style="clear: both;"></div>

    <div style="float:left;padding:10px 0 0 10px;"><label style="margin-right:3px;">未設定RFID数：<b><?=$unlink_num?></b></label></div>
    <div style="clear: both;"></div>
    <div id="ip_section">    
        <div style="float:left;margin:8px 10px;"><label for="id_type" style="margin-right:3px;font-weight:bold;vertical-align: middle;">区分</label>
            <select id='id_type' class="main-ip" value ='' style='text-align:center;width:80px;height:32px;' >
                <option value=""></option>
                <option value="仕掛ID">仕掛ID</option>
                <option value="完成ID">完成ID</option>
            </select>
        </div>
        <div style="float:left;margin:8px 10px;"><label for="start_num" style="margin-right:3px;font-weight:bold;vertical-align: middle;">開始番号</label><input id='start_num' class="main-ip" value ='<?=$this_start?>' style='text-align:right;width:80px;' /></div>
        <div style="float:left;margin:8px 10px;"><label for="sheets_num" style="margin-right:3px;font-weight:bold;vertical-align: middle;">枚数</label><input id='sheets_num' class="main-ip" value ='' style='text-align:right;width:60px;' /></div>
        <div style="float:left;margin:8px 10px;"><label for="end_num" style="margin-right:3px;font-weight:bold;vertical-align: middle;">終了番号</label><input id='end_num' class="main-ip" value ='' style='text-align:right;width:80px;' /></div>
        <div style="float:left;margin:8px 10px;"><label for="itemcode" style="margin-right:3px;font-weight:bold;vertical-align: middle;">品目コード</label>
            <input id='itemcode' class="main-ip" list="item_list" value ='' style='text-align:left;width:120px;' />
            <datalist id="item_list">
                <?php foreach($item_list as $ilk=>$ilv){ ?>
                    <option value="<?=$ilv["itempprocord"]?>" text="<?=$ilv["itemname"]?>" roundnum="<?=$ilv["round_num"]?>" fprnum="<?=$ilv["fpr_num"]?>"><?=$ilv["itempprocord"]?></option>
                <?php } ?>   
            </datalist>
        </div>
        <div style="float:left;margin:8px 10px;">
            <label for="item_name_lable" style="margin-right:3px;font-weight:bold;vertical-align: middle;">品名</label><input id='item_name_lable' class="main-ip" value ='' style='text-align:left;width:200px;' readonly="readonly" />
        </div>
        <div style="float:left;margin:8px 10px;"><label for="around_num" style="margin-right:3px;font-weight:bold;vertical-align: middle;">まるめ数</label><input id='around_num' class="main-ip" value ='' style='text-align:right;width:70px;' /></div>
        <button id="btn-entry" type="button" onclick="entrySetID();" class="" style="float:right;margin:8px 10px;">設定</button>

    </div>
    <div style="clear: both;"></div>
    <label style="padding:0 10px;">設定済みRFID数：<b><?=$linked_num?></b></label>
    <div id="history_section" style="padding:0 10px 0 0;width:fit-content;max-height: 390px;overflow: scroll;border-top: 1px solid #ccc;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
        <table class="type03">
            <thead>
                <tr>
                    <th style="width:20px;">No.</th>
                    <th style="width:90px;">詳細</th>
                    <th style="width:110px;">品目コード</th>
                    <th style="">略称</th>
                    <th style="width:60px;">ID区分</th>
                    <th style="width:90px;">紐付日</th>
                    <th style="width:60px;">枚数</th>
                    <th style="width:60px;">まるめ数</th>
                    <th style="width:70px;">管理数</th>
                    <th style="width:100px;">CSV出力</th>
                </tr>
            </thead>
            <tbody id="history_content">
                <?php foreach($linked_group as $key=>$value){ ?>
                    <tr style="font-weight:bold;">
                        <td><?=($key+1)?></td>
                        <td><button id="group_<?=$value['wim_itemcode']?>_<?=$value['wim_class']?>" class="btn_csv" onclick="op_cl_gr(`<?=$value['wim_itemcode']?>`,`<?=$value['wim_class']?>`);">開く</button></td>
                        <td><?=$value["wim_itemcode"]?></td>
                        <td><?=$value["itemname"]?></td>
                        <td><?=$value["wim_class"]?></td>
                        <td><?=substr($value["last_set_date"],0,-9)?></td>
                        <td style="text-align:right;"><?=$value["sheets_num"]?></td>
                        <td style="text-align:right;"><?=$value["wim_number"]?></td>
                        <td style="text-align:right;"><?=$value["items_num"]?></td>
                        <td style="width:100px;"><button id="csv_<?=$value['wim_itemcode']?>_<?=$value['wim_class']?>"  class="btn_csv" onclick="getCSV(`<?=$value['wiol_id']?>`,`<?=$value['wim_itemcode']?>`,`<?=$value['wim_class']?>`,``);" style="font-size:16px;">全て出力</button></td>
                        <!-- <td style="width:100px;"></td> -->
                    </tr>
                    <?php foreach($value["children"] as $dk=>$dv){ ?>
                        <tr class='children_<?= $dv["wim_itemcode"] ?>_<?= $dv["wim_class"] ?>' style="display:none;">
                            <td></td>
                            <td><button id="cld_<?=$dv['wim_itemcode']?>_<?=$dv['wim_class']?>" class="btn_csv" onclick="openLinkedList(`<?=$dv['wiol_id']?>`,`<?=$dv['wim_itemcode']?>`,`<?=$dv['wim_class']?>`,`<?=$dv['wim_created_at']?>`);">表示</button></td>
                            <!-- <td><?=$dv["wim_itemcode"]?></td>
                            <td><?=$dv["itemname"]?></td>
                            <td><?=$dv["wim_class"]?></td> -->
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?=substr($dv["wim_created_at"],0,-9)?></td>
                            <td style="text-align:right;"><?=$dv["sheets_num"]?></td>
                            <td style="text-align:right;"><?=$dv["wim_number"]?></td>
                            <td style="text-align:right;"><?=$dv["items_num"]?></td>
                            <td style="width:100px;"><button id="csv_<?=$dv['wim_itemcode']?>_<?=$dv['wim_class']?>"  class="btn_csv" onclick="getCSV(`<?=$dv['wiol_id']?>`,`<?=$dv['wim_itemcode']?>`,`<?=$dv['wim_class']?>`,`<?=$dv['wim_created_at']?>`);" style="font-size:16px;">CSV出力</button></td>
                        </tr>
                    <?php } ?>
                    <tr class="tr_blank" style="background:#ccc;">
                        <td></td><td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<label id="linked_list_label" style="padding:0 20px;"></label>
<div id="linked_list" style="margin-left:20px;padding:0 10px 0 0;width:fit-content;min-height:300px;overflow:scroll;border-top: 1px solid #ccc;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;display:none;">
    <table class="type03" style="font-size: 85%;">
        <thead>
            <tr>
                <th style="">No.</th>
                <th style="">RFID</th>
                <th style="">品目コード</th>
                <th style="">略称</th>
                <th style="">ID区分</th>
                <th style="">丸め数</th>
                <th style="">ID状態</th>
                <th style="">担当者</th>
                <th style="">作成日時</th>
                <th style="">修正</th>
            </tr>
        </thead>
        <tbody id="manager_linked">

        </tbody>
    </table>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>

<div id="set_list">
    <div id="set_list_message" style="text-align: center;"></div>
</div>