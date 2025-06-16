<?php
    slot('h1','<h1 style="margin:0 auto;">リアルタイム検査実績データ | '.$_GET["plant"].' | Nalux</h1>');
// print "<pre>";
// print_r($data);
// print "</pre>";
?>
<style type="text/css">
    table.type03 { /* width:70%; */ font-size:13px; border-collapse: collapse; text-align: center; line-height: 1.5; border-top: 2px solid #ccc; border-left: 3px solid #369; table-layout: fixed; margin-right:0.2em; margin-left:1em; }
    table.type03 th { padding: 1px 2px; font-weight: bold; vertical-align: top; color: #153d73; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
    table.type03 td { padding: 1px 2px; vertical-align: top; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; white-space: nowrap; }
    table caption { font-size:16px; text-align: left; font-weight: bold; color:#0d2e59; }
    #loading { width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; /* 背景関連の設定 */ background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center; background-repeat: no-repeat; background-attachment: fixed; }
    #loading img{ max-width: 100%; height:auto; }
</style>


<script type="text/javascript">

    $(document).ready(function(){
        $("#date").datepicker();
        $( "#alert" ).dialog({
            autoOpen: false,
            buttons: [{ text: "閉じる", click: function() {
                $( this ).dialog( "close" );
            }}]
        });

    });

    function loadingView(flag) {
        $('#loading').remove();
        if(!flag) return;
        $('<div id="loading" />').appendTo('body');
    }

    function opend_user(){
        var gp1 = decodeURIComponent("<?php echo str_replace("工場","",$sf_params->get('plant'));?>");
        var gp2 = decodeURIComponent("製造係_2班");
        $.getJSON("/common/AjaxUserSelect?action=user&gp1="+gp1+"&gp2="+gp2/*+"&callback=?"*/,function(data){
            $("#message").html(data);
        });
        var options = {"title":"担当者を選択してください。",
            width: '900px',
            position:["centetr",50],
            modal:true,
            buttons: [],
            open:function(e,ui){ $(".ui-dialog-titlebar-close").hide(); }
        };
        $( "#alert" ).dialog( "option",options);
        $( "#alert" ).dialog( "open" );
    }
  
    function setUser(name){
        $("#username").val(name);
        $( "#alert" ).dialog( "close" );
        getList();
	}

    function getList(){
        if($("#date").val()==""){
            return;
        }
        loadingView(true);
        $.ajax({
            type: 'GET',
            url: "",
            dataType: 'json',
            data:{
                'ac':'getList',
                'date':$("#date").val(),
                'username':$("#username").val()
            },
            success: function(d) {
                $('#view_list').html(d);
            }
        });
        loadingView(false);
    }

    function childData(id){
        alert(id);
    }
</script>

<div>
    <label for="date">日付</label>
    <input type="text" value="" id="date" name="date" style="padding:3px;width:100px;"/>
    <label for="username">氏名</label>
    <input onclick="opend_user();" type="text" value="" id="username" name="username" style="padding:3px;width:200px;"/>
    
</div>
<div style='width:auto;min-height:40%;border:1px solid #ccc;box-shadow: 5px 5px 5px 0px #ccc;margin:10px auto;padding:10px 5px;'>
    <div id="view_list"></div>
</div>

<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>