    
<?php

    slot('h1', '<h1 class="header-text" style="margin:0 auto;">予実管理表 | Nalux '.$sf_request->getParameter('plant').' </h1>');
    $btn='<div style="float:right;" id="h_button"></div>';
    slot('cd',$btn);
?>

<style type="text/css">

    #content{height:calc(100% - 30px);}
    table.type03 { font-size:90%; border-collapse: collapse; text-align: center; line-height: 1.2;border-left: 4px solid #369; border-top: 1px solid #369;table-layout: fixed; margin-right:0.2em; }
    table.type03 th { padding: 1px 4px; font-weight: bold; vertical-align: middle; color: #153d73; border-right: 1px solid #000; border-bottom: 1px solid #000;position: sticky;top: 0;background-color: white;}
    table.type03 td { padding: 1px 4px; vertical-align: middle; border-right: 1px solid #000; border-bottom: 1px solid #000; white-space: nowrap; }
    table caption { font-size:16px; text-align: left; font-weight: bold; color:#0d2e59; }

</style>

<script type="text/javascript">
    $(document).ready(function(){
        $("button").button();

        getData();
    
    });

    function getData(){
        $.ajax({
            type: "GET",
            url: "?ac=cav_combi",
            data: {
                dm:"2025-02"
            },
            dataType: "json",
            success: function (res) {
                console.log(res);
                creattable(res);
            }
        });
    }

    function creattable(res){

        let tbody = "";
        $.each(res, function (a, b) { 
            tbody+="<tr>";
            tbody+="<td>"+(a+1)+"</td>";
            tbody+="<td>"+b.hgpd_checkday+"</td>";
            // let sp_part = b.part_class.split(",");
            let sp_part = sp_soft(b.part_class);
            // console.log(sp_part);

            for(n=0;n<sp_part.length;n++){
                let part_detail = sp_part[n].split("=>");
                let part_num = getInNum(part_detail[1],b.sum_wic_in);
                
                // tbody+="<td>"+part_detail[1]+"</td>";
                tbody+="<td>"+part_detail[2]+"</td>";
                tbody+="<td>"+part_detail[3]+"</td>";
                tbody+="<td>"+part_num+"</td>";
                tbody+="<td>"+(part_num-parseInt(b.sum_hr_good))+"</td>";
            }

            tbody+="<td style='font-weight:bold;'>"+b.hgpd_cav+"</td>";
            tbody+="<td style='font-weight:bold;'>"+b.sum_hr_in+"</td>";
            tbody+="<td style='font-weight:bold;'>"+b.sum_hr_good+"</td>";
            tbody+="<td style='font-weight:bold;'>"+b.sum_hr_bad+"</td>";
            tbody+="<td style='font-weight:bold;'>"+((b.sum_hr_good/b.sum_hr_in)*100).toFixed(2)+"</td>";
            tbody+="<td></td></tr>";

        });
        $("#tbody1").html(tbody);

    }

    function sp_soft(str){
        let res = [];
        let soft = ["front","back","mid"];
        let sp_str= str.split(",");
        $.each(soft, function (k, v) { 
           $.each(sp_str, function (a, b) { 
                if(b.indexOf(v)>-1){
                    res[k]=b;
                }
           }); 
        });
        return res;
    }

    function getInNum(item,str){
        let num = 0;
        let sp_str=str.split(",");
        $.each(sp_str, function (a, b) { 
            let arr = b.split("=>");
            if(arr[0]==item){
                num += parseInt(arr[1]);
            }
        });
        return num;
    }

</script>

<div>
    <label>予実管理リスト</label>
    <table class="type03" style="">
        <thead>
            <tr>
                <th>No</th>
                <th>日付</th>
                <!-- <th style="width:85px;background: #00dbff;">部品</th> -->
                <th style="width:60px;background: #00dbff;">成形<br>ロット</th>
                <th style="width:50px;background: #00dbff;">キャビ</th>
                <th style="width:50px;background: #00dbff;">投入数</th>
                <th style="width:50px;background: #00dbff;">廃棄数</th>
                <!-- <th style="width:85px;background: #1ee118;">部品</th> -->
                <th style="width:60px;background: #1ee118;">成形<br>ロット</th>
                <th style="width:50px;background: #1ee118;">キャビ</th>
                <th style="width:50px;background: #1ee118;">投入数</th>
                <th style="width:50px;background: #1ee118;">廃棄数</th>
                <th style="width:60px;">組合せ<br>キャビ</th>
                <th style="width:50px;" >生産数</th>
                <th style="width:50px;" >良品数</th>
                <th style="width:50px;" >廃棄数</th>
                <th style="width:50px;" >良品率</th>
                <th style="width:50px;" >サンプル</th>
            </tr>
        </thead>
        <tbody id="tbody1">
        </tbody>
    </table>

</div>