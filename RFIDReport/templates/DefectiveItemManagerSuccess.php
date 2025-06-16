<?php
    use_javascript("Sortable.min.js");

    slot('h1', '<h1 style="margin:0 auto;font-size:100%;">工程別不具合設定 | Nalux | '.$sf_params->get("plant").'</h1>');
?>

<style type="text/css">
    #content{
        height: calc(100% - 60px);
    }
    #ip_section{
        font-size:18px;
        margin: 5px 0 0 10px;
    }
    #ditem_view{
        margin:10px;
        padding: 10px;
        background: antiquewhite; 
        width: fit-content;
    }

    #btn_search{
        position: absolute;
        margin-left: 10px;
    }

    #btn_search .ui-button-text{
        padding: 1px 20px;
    }
    .main-ip{
        height:28px;
        font-size:16px;
        font-weight:bold;
    }

    .ui-sortable-handle {
        cursor: grab;
    }

    .ui-sortable-handle:active {
        cursor: grabbing;
    }

    #sort{
        float: left;
        padding: 4px;
        border: solid 2px blue;
    }

    ul{
        width:220px;
        padding-left:0;
    }
    ul li{
        border:1px solid #ccc;
        padding:4px;
        display:flex;
        background:#fbfbfb;
        justify-content: space-between;
    }
    #ditemsetpop { padding-top: 2px;}
  	#ditemsetpop .set { line-height:1em;vertical-align: middle;display: inline-block;width: 228px; border: 1px #ccc solid;margin-left: -1px;margin-top: -1px;padding: 2px 3px ;background: #fbfbfb; }
  	#ditemsetpop input { float: left; }
  	#ditemsetpop label { float: left; font-size:18px; }

</style>

<script type="text/javascript">
    var plant_name = "<?=$sf_params->get("plant")?>";
    var manager = "NG";
    var username = $("#manager_name").html();
    if(username){
        $("#login").css({"display":"none"});
        $("#view_username").html(username);
        user_check = JSON.parse(localStorage.getItem("sManager"));
        $.each(user_check, function(user,mng){
            if (user.toLowerCase()==username.toLowerCase()){
                $.each(mng, function(k,m){
                    if(m == "DAS実績修正"){
                    manager = "OK";
                    }
                });
            }
        });
    }
    var old_ditem = "";
    $(document).ready(function(){
        $("button").button();

        $("#view_manager").css({"display":""});
        
        $( "#ditem_dialog" ).dialog({
            autoOpen: false,
            width:1000,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });

        $( "#alert" ).dialog({
            autoOpen: false,
            width:1000,
            modal:false,
            dialogClass: "no-close",
            position:["center", 100],
            buttons: [{ class:"btn-right",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
            }}]
        });

        $("#workitem").change(function(e){
            // console.log(e);
            if($("#workitem").val()!="" && $("#itemcode").val()!=""){
                searchDitem();
            }
        })
        $("#ditem_view").hide();
        // 24.01.11 Arima Add 
        $("#workitem").val("<?= $_GET["workitem"]?>");
        <?php if($_GET["itemcode"]!="" && $_GET["workitem"]){?>
            searchDitem();
        <?php }?>
        // 24.01.11 Arima Add End

    });

    function searchDitem(){
        let plant = "<?=$sf_params->get("plant")?>";
        var c={};
        var errc=0;
        c["品目コード"]=$("#itemcode").val();
        c["工程"]=$("#workitem").val();

        var msg ="<b style='color:red;'>確認！未入力の項目を入力してください。！</b>\n";
        $.each(c,function(key) {
            if($.trim(c[key])=="" || $.trim(c[key])=="0"){
                msg = msg +"<li>"+key+"</li>\n";
                errc++;
            }
        });

        if(errc>0){
            openAlert("確認",msg);
            return;
        }
        loadingView(true);

        $.ajax({
            type: "GET",
            url: "",
            data: {
                ac:"getItemDefec",
                itemcode:$("#itemcode").val(),
                workitem:$("#workitem").val(),
                plant:plant
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                if(res[0]=="OK"){
                    $("#update_code").html($("#itemcode").val());
                    $("#update_workitem").html($("#workitem").val());
                    $("#update_itemname").html(res[1]["itemname"]);

                    old_ditem=res[1].inidata;
                    ditemView(old_ditem.split(","));
                    defactitemadd();
                }else if(res[0]=="UNSET"){
                    $("#sort").html("");
                    $("#select_ditem").html("");
                    $("#update_code").html("");
                    $("#update_workitem").html("");
                    $("#ditem_view").hide();

                    var options = {"title":"確認",
                        width:600,
                        buttons: [{ class:"btn-right btn-confirm",text:"不良設定",click :function(ev) {
                            $( this ).dialog( "close" );
                            defactitemadd();
                            $("#update_code").html($("#itemcode").val());
                            $("#update_workitem").html($("#workitem").val());
                            $("#update_itemname").html(res[2]);
                        }},{ class:"btn-right",text:"閉じる",click :function(ev) {
                            $( this ).dialog( "close" );
                        }}]
                    };
                    var msg="工程の不具合が設定されてないです。";
                    $("#message").html(msg);
                    $( "#alert" ).dialog( "option",options);
                    $("#alert").dialog( "open" );
                }else{
                    $("#sort").html("");
                    $("#select_ditem").html("");
                    $("#update_code").html("");
                    $("#update_workitem").html("");
                    $("#ditem_view").hide();
                    openAlert("確認",res[1],[{ class:"btn-right",text:"閉じる",click :function(ev) {
                        $("#itemcode").select();
                        $( this ).dialog( "close" );
                    }}]);
                }
            }
        });
    }

    function defactitemadd(){
        loadingView(true);
        let plant = "<?=$sf_params->get("plant")?>";
        let plant_id="1000079";
        if(plant=="山崎工場"){
            plant_id="1000073";
        }
        let ww = $(window).width()-10;
        $.ajax({
            type: 'GET',
            url: "/LaborReport/Ditemset?num=0",
            // url: "/LaborReport/BadItemList",
            async : false,
            data:{
                itemcode:$("#itemcode").val(),
                workitem:encodeURIComponent($("#workitem").val()),
                plant_name:plant,
                plant_id:plant_id
            },
            dataType: 'html',
            success: function(d) {
                loadingView(false);
                $("#ditem_view").show();
                $("#select_ditem").html(d);
                $("#btn_entrydefactiv").button();
            }
        });
    }

    function entry_ditem(){
		var check = $('[class="dedit_item"]:checked').map(function(){
  		    return $(this).val();
		}).get();
        $("#update_code").html($("#itemcode").val());
        $("#update_workitem").html($("#workitem").val());
        $("#update_workitem").html($("#workitem").val());
        ditemView(check);
	}

    function ditemView(ditems){
        $("#ditem_view").show();
        let ditem_content = "";
        $.each(ditems, function (a, b) { 
            ditem_content+="<li id='"+a+"'>"+b+"</li>";
        });
        $("#sort").html(ditem_content);
        $(function () {
            $("#sort").sortable();
        });
        return false;
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

    function entrySort(){
        var c={};
        var errc=0;
        c["品目コード"]=$("#update_code").html();
        c["工程"]=$("#update_workitem").html();

        $.each(c,function(key) {
            if($.trim(c[key])=="" || $.trim(c[key])=="0"){
                errc++;
            }
        });

        if(errc>0){
            openAlert("確認","不具合をチェックして、「項目を反映」ボタンを押してください。");
            return;
        }

        let new_ditem = "";
        $.each($("#sort li"), function (a, b) { 
            new_ditem+=b.textContent+","; 
        });
        new_ditem=new_ditem.substr(0,new_ditem.length-1);

        // console.log(new_ditem);
        loadingView(true);
        $.ajax({
            type: "POST",
            url: "",
            data: {
                ac:"Entry",
                itemcode:$("#update_code").html(),
                workitem:$("#update_workitem").html(),
                user:$("#manager_view_name").val(),
                inidata:new_ditem,
                oldValue:old_ditem,
            },
            dataType: "json",
            success: function (res) {
                loadingView(false);
                old_ditem=new_ditem;
                openAlert("完了","不具合項目の設定が保存されました。");
                return;
            }
        });
    }

</script>

<div id="ip_section">
    <p>
        <label for="itemcode" style="margin-right:3px;font-weight:bold;">品目コード</label>
        <input id='itemcode' list="item_list" type="text" class="main-ip" value ='<?= $_GET["itemcode"];?>' style='text-align:center;width:135px;' />
        <datalist id="item_list">
            <?php foreach($item_list as $ilk=>$ilv){ ?>
                <option value="<?=$ilv["itempprocord"]?>" ><?=$ilv["itempprocord"]?></option>
            <?php } ?>   
        </datalist>
        <label for="workitem" style="margin:0 3px 0 10px;margin-right:3px;font-weight:bold;">工程選択</label>
        <select id='workitem' type="text" class="main-ip" value ='' style='text-align:left;height:32px;' >
            <option value="" >-</option>
            <?php foreach($work_list as $key=>$value){ ?>
                <option value="<?=$value["workitem_name"]?>" ><?=$value["workitem_name"]?></option>
            <?php } ?>
        </select>
        <button type="button" id="btn_search" onclick="searchDitem();" class="btn_ditemset" style="">検索</button>
        <!-- 24.01.11 Arima Add  -->
        <button type="btn" onclick="window.open('about:blank','_self').close();return false;" style="float:right;font-size:14px;margin-right: 10px;">閉じる</button>
        <!-- 24.01.11 Arima Add END -->
    </p>
    <p>

    </p>
</div>

<div id="ditem_view" style="float:left;overflow: hidden auto;height: calc(100% - 50px);">
    <div style="width:100%;">
        <label id="update_code" style="font-weight:bold;margin-right:5px;"></label>
        <label id="update_itemname" style="font-weight:bold;margin-right:5px;"></label>
        <label id="update_workitem" style="font-weight:bold;margin-right:5px;"></label>
    </div>
    <div style="float:left;height: calc(100% - 25px);">
        <div style="float:left;height: calc(100% - 45px);overflow: hidden scroll;">
            <ul id="sort" style="">
            </ul>
        </div>
        <div style="clear:both;"></div>
        <button type="button" id="btn_entry" onclick="entrySort();" class="" style="margin-top: 5px;">保存</button>
    </div>

    <div id="select_ditem" style="float:right;text-align: center;width: calc(100% - 250px);height: calc(100% - 25px);overflow:hidden auto;"></div>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>

