<?php
    slot('h1','<h1 style="margin:0 auto;">Adempiere在庫連携 | '.$_GET["plant"].' | Nalux</h1>');
// print "<pre>";
// print_r($data);
// print "</pre>";
?>
<style type="text/css">
    html {
        /* overflow: hidden; Hide scrollbars */
    }
    #content{
        width: auto;
        height: calc( 100% - 30px);
        padding:30px 5px 0 5px;
    }
    table.type03 { font-size:90%; border-collapse: collapse; text-align: center; line-height: 1.5;border-left: 3px solid #369; border-top: 1px solid #369;table-layout: fixed; margin-right:0.2em; }
    table.type03 th { padding: 1px 4px; font-weight: bold; vertical-align: top; color: #153d73; border-right: 1px dotted #000; border-bottom: 1px solid #000;background-color: white;position: sticky;top: 0;background: #ddefff; }
    table.type03 td { padding: 1px 4px; vertical-align: top; border-right: 1px dotted #000; border-bottom: 1px dotted #000; white-space: nowrap; text-align:left; }
    table caption { font-size:16px; text-align: left; font-weight: bold; color:#0d2e59; }
    #loading { width: 100%; height: 100%; z-index: 9999; position: fixed; top: 0; left: 0; /* 背景関連の設定 */ background-color: #ccc; filter: alpha(opacity=85); -moz-opacity: 0.85; -khtml-opacity: 0.85; opacity: 0.85; background-image: url(/images/loading-1.gif); background-position: center center; background-repeat: no-repeat; background-attachment: fixed; }
    #loading img{ max-width: 100%; height:auto; }
    input{
        font-weight: bold;
        font-size: 100%;
        text-align:center;
    }
    .add_item input{
        font-weight: normal;
        border: none;
    }
    /* .ip-rfid{font-weight: bold;width: 330px;} */
    #csvFile{
        padding:0;
        background: #00F;
        color: #FFF;
    }
    /* .header_row{
        font-weight:bold;
        color:#153d73;
    } */
    .scaned_row{
        background:#8ce6f0;
    }
    .unscaned_row{
        background:#ff5b5b;
    }
    .unknow_row{
        background:#e6e6e6;
    }
    .complete_row{
        background:#aae6aa;
    }
    .disable{
        background:#dbdbdb;
    }
    .need-fix{
        background:#fffdb8;
    }
    #check_all_label:hover{
        color:blue;
    }
    .num-view{
        text-align:right;
    }
</style>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/encoding-japanese/1.0.28/encoding.min.js"></script> -->
<script type="text/javascript">

    $(document).ready(function(){
        $("button").button();
        $('#check_all_control').removeAttr('checked');
        getAdmInventory();
        var mode = "<?= $sf_params->get("mode") ?>";
        if(mode=="view"){
            $("#download_csv").hide();
        }
    });

    var formatThousands = function(n,flg){
        var s = ''+(Math.floor(n)), d = n % 1, i = s.length, r = '';
        if(n<0 && i==4){
            return n;
        }else{
            while ( (i -= 3) > 0 ) { r = ',' + s.substr(i, 3) + r; }
            if(n>0 && flg=="plus"){
                return "+"+s.substr(0, i + 3) + r + (d ? '.' + Math.round(d * Math.pow(10, 2)) : '');
            }else{
                return s.substr(0, i + 3) + r + (d ? '.' + Math.round(d * Math.pow(10, 2)) : '');
            }
        }
     
    };

    function getAdmInventory(){
        // let inventory_cacl = JSON.parse(localStorage.getItem("inventory_cacl"))
        // console.log(inventory_cacl);
        loadingView(true);
        // let post_data=[];
        // if(inventory_cacl){
        //     $.each(inventory_cacl,function(a,b){
        //         post_data.push(b);
        //     })
        // }
        $.ajax({
            type: "GET",
            url: "AdempiereInventoryUpdate?ac=getAdmInventory",
            data: {
                plant:"<?= $sf_params->get("plant") ?>",
                itemcodes:"<?= $sf_params->get("itemcodes") ?>",
                maxid:"<?= $sf_params->get("maxid") ?>",
                mode:"<?= $sf_params->get("mode") ?>",
                // post_data:post_data
            },
            dataType: "json",
        })
        .done(function(res){    
            console.log(res);
            let table=``;
            $.each(res, function(key,val){
                let adm_inv_num = 0;
                if(val.adm_inv){
                    adm_inv_num = val.adm_inv;
                }
                
                let fix_flag = "do-not-fix"
                if(!val.adm_product_id){
                    fix_flag="disable";
                }else{
                    if(parseInt(adm_inv_num)!=parseInt(val.das_inv_num)){
                        fix_flag = "need-fix";
                    }
                }

                table+=`<tr id="check_row_`+val.adm_search_key+`" class="`+fix_flag+`">`;
                    table+=`<td>`+(key+1)+`</td>`;
                    // if(fix_flag=="need-fix"){
                    //     table+=`<td style="text-align:center;"><input type="checkbox" class="process_check" value="`+val.adm_search_key+`" checked="checked" /></td>`;
                    // }else{
                    //     table+=`<td></td>`;
                    // }        
                    table+=`<td>`+val.adm_product_id+`</td>`;
                    table+=`<td>`+val.adm_search_key+`</td>`;
                    // table+=`<td>`+val.wic_itemform+`</td>`;
                    table+=`<td>`+val.tag_name+`</td>`;
                    // table+=`<td>`+val.proccess_name+`</td>`;
                    table+=`<td style="display:none;">`+val.place_id+`</td>`;
                    let sp_place = val.wird_wherhose.split(",");
                    // $.each(sp_place, function (a, b) { 
                    //     table+=`<td>`+b+`</td>`;
                    // });
                    table+=`<td>`+sp_place[2]+`</td>`;
                    table+=`<td>`+sp_place[1]+`</td>`;
                    // table+=`<td>`+sp_place[0]+`</td>`;
                    table+=`<td class="num-view" style="text-align:right;">`+formatThousands(adm_inv_num)+`</td>`;
                    table+=`<td class="num-view" style="text-align:right;">`+formatThousands(val.das_inv_num)+`</td>`;
                    if(fix_flag=="need-fix"){
                        table+=`<td class="num-view" style="font-weight:bold;text-align:right;">`+formatThousands(parseInt(val.das_inv_num) - parseInt(adm_inv_num),"plus")+`</td>`;
                    }else{
                        table+=`<td class="num-view" style="text-align:center;"></td>`;
                    }
                    table+=`<td style="display:none;">`+val.m_product_category+`</td>`;
                table+=`</tr>`
            })
            $("#view_content").html(table);
            $('.process_check').removeAttr('checked');
            $(".process_check").on("change",function(e){
                if(e.target.checked){
                    $("#check_row_"+e.target.value).css({"background":"#cfefff"});
                }else{
                    $("#check_row_"+e.target.value).css({"background":"#fffdb8"});
                }
            });

            $("#check_all_control").on("change",function(e){
                if(e.target.checked){
                    $('.process_check').attr('checked', true);
                    $(".need-fix").css({"background":"#cfefff"});
                }else{
                    $(".process_check").removeAttr('checked');
                    $(".need-fix").css({"background":"#fffdb8"});
                }
            });

        }) 
        .fail(function(res, textStatus, errorThrown){
            console.log("fail");
            console.log(res);
            console.log(textStatus);
            console.log(errorThrown);
        })
        .always(function(res, textStatus, errorThrown){
            console.log(textStatus);
            loadingView(false);
        })


    }

    function get_table_all_val(id,name_list){
        return new Promise((resolve) => {
            const table = $("#"+id); 
            var data = [];
            // data.push("m_product_id,value,ad_org_id,warehousevalue,locatorvalue,x,qtybook,qtycount,description");
            data.push("明細,検索キー,名称,保管場所キー,保管場所,帳簿上数量,棚卸数量,説明,品目カテゴリ");
            
            $.each(table,function(k,v){
                var rows = Array.from(v.rows);
                var cells = rows.map(row => Array.from(row.cells).map(cell => cell.textContent));
                console.log(cells)
                $.each(cells,function(a,b){
                    let r =[];
                    r.push("");     //明細
                    let category ="";
                    $.each(name_list,function(k,v){
                        if(k>0 && k!=1 && k!=4 && k!=9 && k!=10){
                            // r.push(b[k].replace(/,/g,""));
                            // if(k==10){
                            //     let sum_val = b[k]
                            //     if(sum_val==""){
                            //         r.push(0);
                            //         r.push(0);
                            //     }else{
                            //         sum_val=parseInt(b[k].replace(/,/g,""));
                            //         if(sum_val>0){
                            //             r.push(0); //out?
                            //             r.push(sum_val); //in?
                            //         }else{
                            //             r.push(Math.abs(sum_val));
                            //             r.push(0);
                            //         }
                            //     }
                            // }else{
                            //     r.push(b[k]);
                            // }
                            if(k==7 || k== 8){
                                r.push(parseInt(b[k].replace(/,/g,"")));
                            }else{
                                r.push(b[k]);
                            }
                        }
                        if(k==10){
                            category=b[k];
                        }
                    })
                    // r.push("棚卸修正");
                    r.push("");
                    r.push(category);
                    data.push(r);
                });
            });
            resolve(data);
        });
    }

    async function downloadCSV() {
        //CSVデータ
        const filename = nowDT("csv_name")+"_Adempiere転送_棚卸結果.csv"
        let name_list = ["No","m_product_id","value","品名","warehousevalue","locatorvalue","x","das_inv","adm_inv","sum_val","category"];
        const data = await get_table_all_val("view_content",name_list);
        console.log(data);
        // return;
        //BOMを付与
        const bom = new Uint8Array([0xEF, 0xBB, 0xBF]) 
        const blob = new Blob([ bom, '"'+data.join('"\r\n"').replace(/,/g,'","').replace(/=>/g,',')+'"' ], { type : 'text/csv' }) 

        // // SHIFT-JISにエンコード
        // const sjisData = Encoding.convert(data, {to: 'SJIS', from: 'UNICODE', type: 'arraybuffer'});
        // // 先にUint16ArrayからUint8Arrayに変換する
        // const uint8Array = new Uint8Array(sjisData);
        
        // //BlobからオブジェクトURLを作成
        // const blob = new Blob([uint8Array], { type: "text/csv;charset=shift-jis;" });

        // ダウンロードリンクを作成
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;

        // ダウンロードリンクをクリック
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // 後処理
        URL.revokeObjectURL(link.href);
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
            return year+"/"+month+"/"+day+" "+hour+":"+min;
        }
        if(str=="date"){
            return year+"/"+month+"/"+day;
        }
        if(str=="ti"){
            return hour+":"+min;
        }
        if(str=="csv_name"){
            return year+"_"+month+"_"+day;
        }
    }

    function openAlert(title,msg,alert_btn,callback){
        if(!alert_btn){
            alert_btn = [{ class:"btn-left",text:"閉じる",click :function(ev) {
                $( this ).dialog( "close" );
                if(callback){
                    callback();
                }
                return;
            }}]
        }
        var options = {"title":title,
            position:["center", 170],
            width: 600,
            buttons:alert_btn
        };
        $("#msg").html(msg);
        $( "#openAlert" ).dialog( "option",options);
        $( "#openAlert" ).dialog( "open" );
        return false;
    }

</script>
<!-- 
<div style="margin-top:6px;font-weight: bold;">
    <label for="date">作業日</label>
    <input type="text" value="" id="date" name="date" style="padding:3px;width:100px;"/>
    <label for="username">氏名</label>
    <input onclick="opend_user();" type="text" value="" id="username" name="username" style="padding:3px;width:150px;"/>
</div> -->
<div style="clear:both;"></div>
<!-- <pre id="CSVout"><p>File contents will appear here</p></pre> -->
<div style="width:fit-content;">
    <div id="view_list" style="width:fit-content;max-height: calc(100% - 110px);margin:10px 0;overflow: scroll;border: 1px solid #369;">
        <table id="view_table" class="type03"  >
            <thead>
                <th style="width:30px">No</th>
                <!-- <th style="width:65px">ADM転送</th> -->
                <th style="width:80px">ADM製品ID</th>
                <th style="width:115px">品目コード</th>
                <!-- <th style="width:60px">型番</th> -->
                <th style="width:100px">品名</th>
                <!-- <th style="width:100px">工程</th> -->
                <th style="display:none;">工場ID</th>
                <th style="width:50px">保管場所キー</th>
                <th style="width:180px">保管場所</th>
                <th style="width:80px">ADM数量</th>
                <th style="width:80px">DAS数量</th>
                <th style="width:80px">判断結果</th>
                <th style="display:none;">品目カテゴリ</th>
            </thead>
            <tbody id="view_content">
            </tbody>
        </table>
    </div>
    <div id="cmd_btn" style="display: block;margin-bottom:5px;">
        <!-- <input type="checkbox" id="check_all_control" value="" checked="checked" style="margin: 0 10px 0 20px;" /><label for="check_all_control" id="check_all_label" >すべてチェックする</label> -->
        <!-- <button onclick="adempiereUpdate();" style="float:right;" >実行</button> -->
        <button id="download_csv" onclick="downloadCSV();" style="float:right;margin-right:20px;" >Adempiere転送CSV出力</button>

        <!-- <button onclick="downloadCSV();" style="margin-right:20px;" >CSV出力</button> -->
    </div>
</div>
<div id="alert">
    <div id="message" style="text-align: center;"></div>
</div>

<div id="openAlert">
    <div id="msg" style="text-align: center;"></div>
</div>