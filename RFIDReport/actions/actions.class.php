<?php

/**
 * RFIDReport actions.
 *
 * @package    sf_sandbox
 * @subpackage RFIDReport
 * @author     Norimasa Arima
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RFIDReportActions extends sfActions
{
    /**
     * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('default', 'module');
    }

    public function executeMoldingCounter(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('成形工程 | Nalux');

        $client_ip = $_SERVER["REMOTE_ADDR"];
        $this->client_ip=$client_ip;

        if(strpos($_SERVER["HTTP_USER_AGENT"],"Windows")!==false){
            $client_os="パソコン";
        }elseif(strpos($_SERVER["HTTP_USER_AGENT"],"Mac OS")!==false){
            $client_os="iPad";
        }else{
            $client_os="その他";
        }
        $this->client_os=$client_os;

        if($request->getParameter("ac")=="getPrinterIp"){
            $plant = $request->getParameter("plant");
            $q="SELECT * FROM work_ipaddr_manarger WHERE wim_type LIKE '%ipad用プリンター%' AND wim_plant = '".$plant."' ";
            $st = $con->execute($q);
            $printer_list = $st->fetchall(PDO::FETCH_ASSOC);

            echo json_encode($printer_list);
            exit;
        }
    
        if($request->getParameter("ac")=="data_entry"){
            $data= $request->getParameter("entry_data");
            $item_list=explode(",",$data["item_list"]);
            $s=$data["基本"];

            $mold_day = date("Y/m/d",strtotime($s["終了"]));
            $mh = date("G",strtotime($s["終了"]));
            if($mh<8){
                $mold_day = date("Y/m/d",strtotime("-1 day ",strtotime($s["終了"])));
            }
            $wl[0] = substr(date("y",strtotime($mold_day)) , -1);
            $wl[1] = date("n",strtotime($mold_day));
            if($wl[1]=="10"){
                $wl[1] ="X";
            }elseif($wl[1]=="11"){
                $wl[1] = "Y";
            }elseif($wl[1]=="12"){
                $wl[1] = "Z";
            }
            $wl[2] = date("d",strtotime($mold_day));
            $worklot = implode("",$wl); 

            $q = "SELECT * FROM ms_person WHERE user = '".$s["担当者"]."' ";
            $st = $con->execute($q);
            $user = $st->fetch(PDO::FETCH_ASSOC);

            $q = "SELECT * FROM ms_workitem_list WHERE workitem_name = '".$s["作業工程"]."' AND workitem_plant_name = '".$s["工場"]."' ";
            $st = $con->execute($q);
            $proccess = $st->fetch(PDO::FETCH_ASSOC);

            $created_at = date("Y-m-d H:i:s");

            $re_print_flag = false;

            foreach($item_list as $key=>$item_code){
                //実績登録
                $d = $data[$item_code];

                $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$item_code."' ";
                $st = $con->execute($q);
                $ms_mold = $st->fetch(PDO::FETCH_ASSOC);

                $q= "INSERT INTO xls_work_report(date, itemcord, itemname, xlsnum, workkind,";
                $q.=" workitem, usercord, username, usergp1, usergp2, moldplaceid,moldplace,";
                $q.=" itemform, moldlot, moldmachine, totalnum, badnum, goodnum,pending_num,missing_num,e_or_d,e_or_d_memo,";
                $q.=" totaltime, remark, defectivesitem, beforeprocess, afterprocess,";
                $q.=" cutmethod, vapordepositionlot, defect, pdate, dateuse,";
                $q.=" scheduled_number, measure, state, ntab, cycle, materialsname,";
                $q.=" materialslot, materialsused, lotstarttime, lotendtime, gootrate,";
                $q.=" hour, badrate, pproentry, workplankind, plankind,";
                $q.=" plantime, place, updating_person, del_flg, xstart_time1, xend_time1, xsetuptime, item_set_num, created_at, updated_at) VALUES (";
                $q.="'".$mold_day."',";
                $q.="'".$item_code."',";
                $q.="'".$d["item_name"]."',";
                $q.="'".$proccess["workitem_no"]."',";
                $q.="'直接作業',";
                $q.="'".$s["作業工程"]."',";
                $q.="'".$user["ppro"]."',";
                $q.="'".$user["user"]."',";
                $q.="'".$user["gp1"]."',";
                $q.="'".$user["gp2"]."',";
                $q.="'".$s["工場ID"]."',";
                $q.="'".$s["工場"]."',";
                $q.="'".$d["form_num"]."',";
                $q.="'".$s["LotNo"]."',";
                $q.="'".$s["mold"]."',";
                $q.="'".$d["生産数"]."',";
                $q.="'".$d["不良数"]."',";
                $q.="'".$d["良品数"]."',";
                $q.="'".$d["pending_num"]."',";
                $q.="'".$d["員数ミス"]."',";
                $q.="'".$d["e_or_d"]."',";
                $q.="'".$d["e_or_d_memo"]."',";
                $q.="'".($s["time_mold"]/60)."',";
                $q.="'".$s["LotId"]."',";              //remark:LotID追加
                $q.="'".$d["all_ditem"]."',";
                $q.="'".$d["beforeprocess"]."',";
                $q.="'".$d["afterprocess"]."',";
                $q.="'".$d["cutmethod"]."',";
                $q.="'".$d["vapordepositionlot"]."',";
                $q.="'".$d["defect"]."',";
                $q.="'".$mold_day."',";
                $q.="'".$d["dateuse"]."',";
                $q.="'".$d["scheduled_number"]."',";
                $q.="'".$d["measure"]."',";
                $q.="'".$d["state"]."',";
                $q.="'".$s["打数"]."',";
                $q.="'".$s["time_mold"] / $d["生産数"]."',";
                $q.="'".$d["matername"]."',";
                $q.="'".$d["materialslot"]."',"; 
                $q.="'".($d["materoneshot"]*$s["打数"])."',";
                $q.="'".$d["開始ロット"]."',";
                $q.="'".$d["lotendtime"]."',";
                $q.="'".(($d["良品数"] / $d["生産数"])*100)."',";
                $q.="'".($s["time_mold"]/3600)."',";
                $q.="'".(($d["不良数"] / $d["生産数"])*100)."',";
                $q.="'".$d["pproentry"]."',";
                $q.="'".$d["workplankind"]."',";
                $q.="'".$d["plankind"]."',";
                $q.="'".$d["plantime"]."',";
                $q.="'".$s['置き場所']."',";    
                $q.="'".$s["担当者"]."',";
                $q.="'0',"; // del flg is OFF
                $q.="'".$s["開始"]."',";
                $q.="'".$s["終了"]."',";
                $q.="'',";
                $q.="'".count($item_list)."',";
                $q.="'".$created_at."',";
                $q.="'".$created_at."')";
                $con->execute($q);

                //実績ID取得
                $q='SELECT id FROM xls_work_report WHERE moldmachine = "'.$s["mold"].'" ORDER BY id DESC LIMIT 1 ';
                $st = $con->execute($q);
                $lot_xls_id = $st->fetch(PDO::FETCH_ASSOC);
                $xwr_id = $lot_xls_id["id"];
                $sq='INSERT INTO xls_work_report_sub (xwid,start_time1,end_time1) VALUES ("'.$xwr_id.'","'.$s["開始"].'","'.$s["終了"].'") ';
                $con->execute($sq);

                $cavs=explode(",",$d["cavs"]);
                $print_data = array();
                foreach($cavs as $ck=>$val){
                    //キャビごと登録
                    $q2 = "INSERT INTO hgpd_report (xwr_id,hgpd_wherhose,hgpd_process,hgpd_itemcode,hgpd_cav,hgpd_itemform,hgpd_moldlot,hgpd_worklot,hgpd_checkday,hgpd_moldday,hgpd_quantity,hgpd_qtycomplete,hgpd_difactive,hgpd_remaining,";
                    $q2.= "hgpd_namecode,hgpd_name,hgpd_start_at,hgpd_stop_at,hgpd_exclusion_time,hgpd_working_hours,hgpd_volume,hgpd_cycle,hgpd_rfid,hgpd_materiall,hgpd_material_code,hgpd_material_lot,created_at) ";
                    $q2.= "VALUES ";
                    $q2.= "('".$xwr_id."',";
                    $q2.= "'".$s["工場"]."',";          //new
                    $q2.= "'".$s["作業工程"]."',";      //new
                    $q2.= "'".$item_code."',";
                    $q2.= "'".$val."',";
                    $q2.= "'".$d["form_num"]."',";
                    $q2.= "'".$s["LotNo"]."',";
                    $q2.= "'".$worklot."',";
                    $q2.= "'".date("Y-m-d")."',";
                    $q2.= "'".$mold_day."',";
                    $q2.= "'".$d[$val]["生産数"]."',";
                    $q2.= "'".$d[$val]["良品数"]."',";
                    $q2.= "'".$d[$val]["不良数"]."',";
                    $q2.= "'0',";
                    $q2.= "'".$user["ppro"]."',";
                    $q2.= "'".$user["user"]."',";
                    $q2.= "'".$s["開始"]."',";
                    $q2.= "'".$s["終了"]."',";
                    $q2.= "'".$d["exclusion_time"]."',";
                    $q2.= "'".floatval($s["time_mold"]/3600)."',";
                    $q2.= "'".($d[$val]["生産数"]/($s["time_mold"]/3600))."',";
                    $q2.= "'".($s["time_mold"]/$d[$val]["良品数"])."',";
                    // $q2.= "'".$d[$val]["qrcode"]."',";             //削除
                    $q2.= "'".$d[$val]["rfid"]."',";                  //hgpd_rfid
                    // $q2.= "'',";         //hgpd_complete_rfid　　　　削除
                    $q2.= "'".$d["matername"]."',";
                    $q2.= "'".$d["materialcode"]."',";
                    $q2.= "'".$d["materialslot"]."',";
                    // $q2.= "null,";                                 //削除
                    $q2.= "'".$created_at."'), ";
                    $q2 = substr($q2, 0,-2);
                    $con->execute($q2);

                    //IDの状態の更新
                    $q= "UPDATE work_id_manager SET wim_status = '使用中' WHERE rfid = '".$d[$val]["rfid"]."' ";
                    $con->execute($q);

                    //完成集計ID取得
                    $q = 'SELECT hgpd_id FROM hgpd_report WHERE hgpd_rfid = "'.$d[$val]["rfid"].'" ORDER BY hgpd_id DESC LIMIT 1 ';
                    $st = $con->execute($q);
                    $lot_werut_id = $st->fetch(PDO::FETCH_ASSOC);
                    $hgpd_id = $lot_werut_id["hgpd_id"];

                    $qu = "UPDATE hgpd_report_unit_tray SET hgpd_id = '".$hgpd_id."', hrut_xls_flg = 1 WHERE hrut_lot_id = '".$s["LotId"]."' AND hrut_cav = '".$val."' AND hrut_serial_num = '".$d["serial"]."' ";
                    // print_r($qu);
                    $con->execute($qu);

                    // 印刷データ
                    if($d[$val]["rfid"]){
                        $print_data[] = array(
                            "form"=>$d["form_num"],
                            "cav"=>$val,
                            "lot"=>$s["LotNo"],
                            "id"=>$hgpd_id,
                            "m_date"=>$mold_day,
                            "start_at"=>$s["開始"],
                            "stop_at"=>$s["終了"],
                            "serial_num"=>$d["serial"],
                        );
                    }

                    $item=array();
                    $ex = explode(",", $d[$val]["tditem"]);
                    foreach ($ex as $dval) {
                        $exd = explode("=>", $dval);
                        if($exd[0] && $exd[1] > 0){
                            $item[$exd[0]] = $exd[1];
                        }
                    }
                    if($item){
                        // 不具合内容保存
                        $qd = "INSERT INTO hgpd_report_defectiveitem ";
                        $qd.= "(hgpd_id,hgpdd_ditem,hgpdd_qty,hgpdd_price,hgpdd_time) ";
                        $qd.= "VALUES (";
                        foreach($item as $dkey=>$dvalue){
                            $qd.= "'".$hgpd_id."',";
                            $qd.= "'".$dkey."',";
                            $qd.= "'".$dvalue."',";
                            $qd.= "'".($dvalue*$d["price"])."',";
                            $qd.= "'".($s["time_mold"] / $d["生産数"])*$dvalue."'),(";
                        }
                        $qd = substr($qd, 0,-2);
                        $con->execute($qd);
                    }

                    if($d[$val]["rfid"]!="" || intval($d[$val]["良品数"])>0){
                        $q="SELECT wic_inventry_num FROM work_inventory_control 
                        WHERE wic_itemcode = '".$item_code."' AND wic_process = '".$s["作業工程"]."' AND wic_process_key = 'M' AND wic_complete_flag = '0' AND wic_del_flg = '0' 
                        ORDER BY wic_id DESC LIMIT 1 ";
                        $st = $con->execute($q);
                        $sum_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                        $qwic_in = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                        $qwic_in.= "VALUES ( ";
                        $qwic_in.= "'".$hgpd_id."',";
                        $qwic_in.= "'".$hgpd_id."',";
                        $qwic_in.= "'0',";
                        $qwic_in.= "'".date("Y-m-d")."',";
                        $qwic_in.= "'".$user["user"]."',";
                        $qwic_in.= "'".$s["置き場所"]."',";
                        $qwic_in.= "'".$d[$val]["rfid"]."',";
                        $qwic_in.= "'".$item_code."',";
                        $qwic_in.= "'M',";
                        $qwic_in.= "'".$s["作業工程"]."',";
                        $qwic_in.= "'".$d["form_num"]."',";
                        $qwic_in.= "'".$val."',";
                        $qwic_in.= "'".$d[$val]["良品数"]."',";
                        $qwic_in.= "'0',";
                        $qwic_in.= "'".(intval($sum_inv_num["wic_inventry_num"])+intval($d[$val]["良品数"]))."',";
                        $qwic_in.= "'成形入庫',";          //remark
                        $qwic_in.= "'0',";          //完成品flag
                        $qwic_in.= "'".$created_at."')";
                        $con->execute($qwic_in);

                        //成形未検出荷の対応
                        if($ms_mold["moldet_undetected_load"]=="1"){
                            //成形未検
                            $q="SELECT wic_inventry_num FROM work_inventory_control 
                            WHERE wic_itemcode = '".$item_code."' AND wic_process = '".$s["作業工程"]."' AND wic_process_key = 'M' AND wic_complete_flag = '0' AND wic_del_flg = '0' 
                            ORDER BY wic_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $sum_inv_num = $st->fetch(PDO::FETCH_ASSOC);
        
                            $qwic_out = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                            $qwic_out.= "VALUES ( ";
                            $qwic_out.= "'".$hgpd_id."',";
                            $qwic_out.= "'".$hgpd_id."',";
                            $qwic_out.= "'".$hgpd_id."',";
                            $qwic_out.= "'".date("Y-m-d")."',";
                            $qwic_out.= "'".$user["user"]."',";
                            $qwic_out.= "'".$s["置き場所"]."',";
                            $qwic_out.= "'".$d[$val]["rfid"]."',";
                            $qwic_out.= "'".$item_code."',";
                            $qwic_out.= "'M',";
                            $qwic_out.= "'".$s["作業工程"]."',";
                            $qwic_out.= "'".$d["form_num"]."',";
                            $qwic_out.= "'".$val."',";
                            $qwic_out.= "'0',";
                            $qwic_out.= "'".$d[$val]["良品数"]."',";
                            $qwic_out.= "'".(intval($sum_inv_num["wic_inventry_num"])-intval($d[$val]["良品数"]))."',";
                            $qwic_out.= "'完成品処理',";          //remark
                            $qwic_out.= "'0',";          //完成品flag
                            $qwic_out.= "'".$created_at."')";
                            $con->execute($qwic_out);

                            $q="SELECT wic_inventry_num FROM work_inventory_control 
                            WHERE wic_itemcode = '".$item_code."' AND wic_process = '完成品処理' AND wic_process_key = '0' AND wic_complete_flag = '1' AND wic_del_flg = '0' 
                            ORDER BY wic_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $sum_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                            $qwic_in = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                            $qwic_in.= "VALUES ( ";
                            $qwic_in.= "'".$hgpd_id."',";
                            $qwic_in.= "'".$hgpd_id."',";
                            $qwic_in.= "'".$hgpd_id."',";
                            $qwic_in.= "'".date("Y-m-d")."',";
                            $qwic_in.= "'".$user["user"]."',";
                            $qwic_in.= "'".$s["置き場所"]."',";
                            $qwic_in.= "'".$d[$val]["rfid"]."',";
                            $qwic_in.= "'".$item_code."',";
                            $qwic_in.= "'0',";
                            $qwic_in.= "'完成品処理',";
                            $qwic_in.= "'".$d["form_num"]."',";
                            $qwic_in.= "'".$val."',";
                            $qwic_in.= "'".$d[$val]["良品数"]."',";
                            $qwic_in.= "'0',";
                            $qwic_in.= "'".(intval($sum_inv_num["wic_inventry_num"])+intval($d[$val]["良品数"]))."',";
                            $qwic_in.= "'成形未検出荷',";          //remark
                            $qwic_in.= "'1',";                    //完成品flag
                            $qwic_in.= "'".$created_at."')";
                            $con->execute($qwic_in);
                        }
                    }

                }

                if($s["printer_ip"]){
                    //プリンターのパラメータの設定
                    $json_print["ac"]="print";
                    $json_print["d"]["ip"]=$s["printer_ip"];
                    //4桁ロット印刷判定 ⁼ judge_p = 0(印刷品目), 1(印刷無し）
                    $json_print["d"]["judge_p"]=$ms_mold["dateto4"];
                    //完成かんばん、仕掛かんばん判定 : judge_card_type =1(仕掛り）＝０（完成)
                    $json_print["d"]["judge_card_type"]="1";
                    $json_print["d"]["kanban"]=$print_data;
                    $json_print["d"]["xwr_id"]=$xwr_id;
                    //生産時間の追加
                    $json_print["d"]["judge_cmt_dt"]=$ms_mold["mold_datetime_print"];
                    $json_print["d"]["judge_cmt_serial"]=$ms_mold["mold_serial_print"];

                    // $q="INSERT INTO hgpd_report_event_log (hrel_item,hrel_username,hrel_plant,hrel_recode,hrel_ipaddr,hrel_remark,hrel_created_at) VALUES 
                    // ('カンバン','".$user["user"]."','".$s["工場"]."','".json_encode($print_data)."','','印刷ログ','".date("Y-m-d H:i:s")."') ";
                    // $con->execute($q);

                    $url = 'http://'.$_SERVER['SERVER_NAME'].'/RFIDReport/Printkanban?' . http_build_query($json_print);
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $html = curl_exec($ch);
                    // echo "<pre>";
                    // print_r(http_build_query($html));
                    // exit;
                    if($html!="OK"){
                        $re_print_flag = true;
                    }
                }

                //実績データ集計
                $url = "http://".$_SERVER['SERVER_NAME']."/LaborReport/LotAggregatecalculation?mode=SingleItem&code=".$item_code."&form=".$d["form_num"]."&lot=".$s["LotNo"];
                if( PHP_OS == 'WINNT'){
                    file_get_contents($url);
                }else{
                    exec("curl -s '".$url."' >> /dev/null 2>&1 &");
                }

            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            // echo json_encode(["OK",$json_print]);
            // if($re_print_flag===false){
            //     //実績登録:OK 印刷:OK
            //     echo json_encode(["OK","OK"]);
            // }else{
            //     //実績登録できたが、印刷できない場合
            //     echo json_encode(["OK",$json_print]);
            // }
            exit;
        }

        if($request->getParameter("ac")=="children_Entry"){
            $d = $request->getParameter("c_data");
            $k = $d["基本"];
            $v = $d[$k["itemcode"]];
            $cavs = explode(",",$k["cavs"]);
            // トレー||束単位 の登録
            $created_at = date("Y-m-d H:i:s");

            $q3 = 'INSERT INTO hgpd_report_unit_tray (hrut_lot_id,hgpd_id,hrut_type,hrut_code,hrut_qrcode,hrut_work_date,hrut_mold_date,hrut_user,hrut_serial_num,hrut_stages,hrut_cav, ';
            $q3.= 'hrut_m_num,hrut_g_num,hrut_d_num,hrut_d_contents,hrut_1h,hrut_ntab,hrut_run_time,hrut_s_datetime,hrut_e_datetime,hrut_cycle,created_at) ';
            $q3.= 'VALUES ';
            foreach($v as $cav=>$value){
                foreach($value as $dan=>$vd){
                    $total_num = $vd["gnum"]+$vd["bnum"];

                    $q3.='("'.$k["LotId"].'",';
                    $q3.='"",';
                    $q3.='"'.$request->getParameter("tray_type").'",';
                    $q3.='"'.$k["itemcode"].'",';
                    $q3.='"'.$k["lt_qr_code"].'",';
                    $q3.='"'.date("Y-m-d").'",';
                    $q3.='"'.date("Y-m-d").'",';
                    $q3.='"'.$k["tuser"].'",';
                    $q3.='"'.$k["serial"].'",';     //通しNo.
                    $q3.='"'.$dan.'",';             //段数
                    $q3.='"'.$cav.'",';             //キャビ
                    $q3.='"'.$total_num.'",';
                    $q3.='"'.$vd["gnum"].'",';
                    $q3.='"'.$vd["bnum"].'",';
                    $q3.='"'.$vd["ditem"].'",';

                    $stime = $k["開始"];
                    $etime = $k["終了"];
                    $time1 = new DateTime($stime);
                    $time2 = new DateTime($etime);
                    $rtime = $time1->diff($time2);
                    $totaltime = (new DateTime())->setTimeStamp(0)->add($rtime)->getTimeStamp();

                    $q3.='"'.($total_num/($totaltime/3600)).'",';
                    $q3.='"'.($total_num*count($cavs)/intval($k["pieces"])).'",';
                    $q3.='"'.($totaltime/60).'",';
                    $q3.='"'.$k["開始"].'",';
                    $q3.='"'.$k["終了"].'",';
                    $q3.='"'.$totaltime/$total_num.'",';
                    $q3.='"'.$created_at.'"), ';
                }
            }
            $q3 = substr($q3, 0,-2);
            
            try{
                $con->execute($q3);
            }catch(Exception $e){
                $err_q=$e->getMessage();
            }
            header("Content-Type: application/json; charset=utf-8");
            if(!$err_q){
                echo json_encode("OK");
            }else{
                echo json_encode($err_q);
            }
            // print_r($q3);
            return sfView::NONE;
            exit;
        }

        if($request->getParameter("ac")=="children_Update"){
            $d = $request->getParameter("c_data");
            $k = $d["基本"];
            $v = $d[$k["itemcode"]];
            $cavs = explode(",",$k["cavs"]);
            // トレー||束単位 の登録
            foreach($v as $cav=>$value){
                foreach($value as $dan=>$vd){
                    $total_num = $vd["gnum"]+$vd["bnum"];

                    $qcu = 'UPDATE hgpd_report_unit_tray SET ';
                    $qcu.='hrut_user = "'.$k["tuser"].'",';
                    $qcu.='hrut_m_num = "'.$total_num.'",';
                    $qcu.='hrut_g_num = "'.$vd["gnum"].'",';
                    $qcu.='hrut_d_num = "'.$vd["bnum"].'",';
                    $qcu.='hrut_d_contents = "'.$vd["ditem"].'",';

                    $stime = $k["開始"];
                    $etime = $k["終了"];
                    $time1 = new DateTime($stime);
                    $time2 = new DateTime($etime);
                    $rtime = $time1->diff($time2);
                    $totaltime = (new DateTime())->setTimeStamp(0)->add($rtime)->getTimeStamp();

                    $qcu.='hrut_1h = "'.($total_num/($totaltime/3600)).'",';
                    $qcu.='hrut_ntab = "'.($total_num*count($cavs)/intval($k["pieces"])).'",';
                    $qcu.='hrut_run_time = "'.($totaltime/60).'",';
                    $qcu.='hrut_s_datetime = "'.$stime.'",';
                    $qcu.='hrut_e_datetime = "'.$etime.'",';
                    $qcu.='hrut_cycle = "'.$totaltime/$total_num.'" ';
                    $qcu.='WHERE hrut_id = "'.$vd["hrut_id"].'" ';

                    try{
                        $con->execute($qcu);
                    }catch(Exception $e){
                        $err_q=$e->getMessage();
                    }
                }
            }
            header("Content-Type: application/json; charset=utf-8");
            if(!$err_q){
                echo json_encode("OK");
            }else{
                echo json_encode($err_q);
            }
            exit;
        }

        if($request->getParameter("ac")=="check_rfid"){
            //RFIDカード利用状態を確認
            $q="SELECT SUM(wic_qty_in) as in_num, SUM(wic_qty_out) as out_num, SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control ";
            $q.=" WHERE wic_rfid='".$request->getParameter("rfid")."' AND wic_del_flg = '0' AND wic_rfid <> '' AND wic_remark <> '員数不足' AND wic_rfid IS NOT NULL ";
            $q.=" ORDER BY wic_created_at DESC,wic_id DESC ";
            $st = $con->execute($q);
            $lot_rfid = $st->fetch(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            if($lot_rfid){
                if(intval($lot_rfid["inv_num"])==0){
                    //在庫に残ってない場合
                    echo json_encode(true);
                    return sfView::NONE;
                }else{
                    echo json_encode(false);
                    return sfView::NONE;
                }
            }else{
                echo json_encode(true);
                return sfView::NONE;
            }
            return sfView::NONE;
            exit;
        }
  
        //Lot情報取得
        $mno = $request->getParameter("lot_mno");
        if (empty($_SERVER['HTTPS'])) {
            header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            exit;
        }
        $q = 'SELECT
            lm.lot_id
            , lm.lot_mno
            , lm.lot_num
            , xwr.itemcord as item_code
            , xwr.itemform as form
            , mc.place
            , mc.placeid
            , lm.lot_start_datatime
        FROM
            manage_lot_mno lm 
            LEFT JOIN ms_mold_machine mc
                ON lm.lot_mno = mc.number
            LEFT JOIN xls_work_report xwr
                ON lm.lot_mno = xwr.moldmachine
                AND lm.lot_num = xwr.moldlot
        WHERE
            lm.lot_mno = "'.$mno.'" AND xwr.del_flg = "0" AND xwr.workitem in ("段取替え", "試作", "製造試作", "技術試作") 
            AND xwr.workplankind = "通常" ';
        if($request->getParameter("lot_past")!=""){
            $q.='AND lm.lot_id = "'.$request->getParameter("lot_past").'" ';
        }else{
            $q.='AND lm.lot_status != "停止中" ';
        }
        $q.='GROUP BY lot_id,item_code,lot_num ORDER BY lot_id DESC ';
        $st = $con->execute($q);
        $all_lot_info = $st->fetchall(PDO::FETCH_ASSOC);

        $check_item = [];$lot_info=[];
        if(count($all_lot_info)==0){
            $lot_info["info"]=[];
        }else{
            foreach($all_lot_info as $key => $value){ 
                if(!in_array($value["item_code"],$check_item)){
                    $check_item[] = $value["item_code"];
                    $lot_info["info"][]=$value;
                }
            }
        }

        $this->lot_info = $lot_info["info"];
        // print_r($lot_info);
        // exit;
        $plant=array();
        if ($lot_info["info"][0]["place"]=="山崎工場") {
            $plant[0]["name"] = "山崎工場";
            $plant[0]["view_name"] = "山崎工場_仕掛品置場" ;
            $plant[0]["val"]="1000101,山崎工場_仕掛品置場,1103";
            $lot_info["last_witem"]="成形";
        }
        if ($lot_info["info"][0]["place"]=="野洲工場") {
            $plant[0]["name"] = "野洲工場";
            $plant[0]["view_name"] = "野洲工場_仕掛品置場" ;
            $plant[0]["val"]="1000072,野洲工場_仕掛品置場,3104";
            $plant[1]["name"] = "野洲工場";
            $plant[1]["view_name"] = "野洲工場_保留品倉庫" ;
            $plant[1]["val"]="1000105,野洲工場_保留品倉庫,3199";
            $lot_info["last_witem"]="成形+GC";
        }
        if ($lot_info["info"][0]["place"]=="NPG") {
            $plant[0]["name"] = "NPG";
            $plant[0]["view_name"] = "野洲工場_仕掛品置場" ;
            $plant[0]["val"]="1000111,ガラス成形置場,3010";
            $lot_info["last_witem"]=null;
        }
        if ($lot_info["info"][0]["place"]=="test") {
            $plant[0]["name"] = "test";
            $plant[0]["view_name"] = "test工場_仕掛品置場" ;
            $plant[0]["val"]="test工場";
            $lot_info["last_witem"]=null;
        }
        $this->plant = $plant;

        if($request->getParameter("ac")=="lot_info"){
            $item_list = "";
            foreach($lot_info["info"] as $key => $value){
                $item_list.=$value["item_code"].",";

                $q = "SELECT * FROM ms_molditem WHERE itempprocord = '".$value["item_code"]."' ";
                $st = $con->execute($q);
                $MsitemInfo=$st->fetch(PDO::FETCH_ASSOC);
                $lot_info[$value["item_code"]]["ms_item_info"] = $MsitemInfo;

                // $q = "SELECT s.id,m.itemcord, m.cav_items, m.form_num, m.cav_tray_input, s.tray_num, s.tray_stok as tray_stock, s.searchtag, s.itemname, s.materials1used, s.materialcode, s.materialsname, m.pieces, m.cycle, s.adm_price_std as price ";
                $q = "SELECT s.id,m.itemcord, m.cav_items, m.form_num, m.cav_tray_input, s.tray_num, s.tray_stok as tray_stock, s.bundle_flg, s.searchtag, s.itemname, s.materials1used, s.materialcode, s.materialsname, m.pieces, m.cycle, s.adm_price_std as price, moldet_undetected_load ";
                $q.= "FROM ms_molditem_info m ";
                $q.= "LEFT JOIN ms_molditem s ON m.itemcord = s.itempprocord ";
                $q.= "WHERE m.itemcord = '".$value["item_code"]."' AND m.form_num = '".$value["form"]."' ";
                // $q.= "WHERE m.itemcord = '1486' AND m.form_num = '1' ";
                $st = $con->execute($q);
                $item_info = $st->fetchall(PDO::FETCH_ASSOC);
                $lot_info[$value["item_code"]]["item_info"] = $item_info[0];
                // $lot_info[$value["item_code"]]["item_info"]["Std_digitization"] = $this->Std_digitization($MsitemInfo["client_folder"],$MsitemInfo["itempprocord"],"成形");
                // $lot_info[$value["item_code"]]["item_info"]["Std_digitization"] = $MsitemInfo["client_folder"]."_".$MsitemInfo["itempprocord"]."_成形.pdf";
                // $lot_info[$value["item_code"]]["item_info"]["Std_digitization"] = $this->Std_digitization($MsitemInfo["client_folder"],$MsitemInfo["itempprocord"],"成形");
                // $lot_info[$value["item_code"]]["item_info"]["Std_digitization"] = $MsitemInfo["client_folder"]."_".$MsitemInfo["itempprocord"]."_成形.pdf";
                //次回の開始時間の判断
                $q  = "SELECT * FROM hgpd_report hr ";
                $q .= "LEFT JOIN xls_work_report xwr ON hr.xwr_id = xwr.id ";
                $q .= "LEFT JOIN hgpd_report_unit_tray hrut ON hr.hgpd_id = hrut.hgpd_id ";
                $q .= "WHERE hgpd_itemcode = '".$value["item_code"]."' AND hgpd_moldlot = '".$value["lot_num"]."' AND hgpd_process IN ('成形', '成形+GC') AND xwr_id <> '' AND hrut_lot_id = '".$value["lot_id"]."' AND hr.hgpd_del_flg = '0' ";
                $q .= 'ORDER BY hr.hgpd_id DESC, hrut_id DESC LIMIT 1 ';
                $st = $con->execute($q);
                $lasttime = $st->fetch(PDO::FETCH_ASSOC);

                if($lasttime){
                    $lot_info["lasttime"]=substr($lasttime["hgpd_stop_at"],0,-3);
                    if($lasttime["hrut_e_datetime"]){
                        $lot_info["lasttime"]=substr($lasttime["hrut_e_datetime"],0,-3);
                    }
                    $lot_info["last_witem"]=$lasttime["workitem"];
                }else{
                    $lot_info["lasttime"]=date("Y/m/d H:i", strtotime($value["lot_start_datatime"]));
                }
         
                $lot_info["lastplace"] = $lasttime["place"];

                //原料ロット抽出
                $q="SELECT * FROM work_material_input mi LEFT JOIN work_material_input_item mii ON mi.id = mii.wmi_id AND mii.wmi_id IS NOT NULL WHERE mi.input_mno = '".$mno."' ORDER BY mi.id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $metarial_lot = $st->fetch(PDO::FETCH_ASSOC);
                $lot_info["lastmetalot"] = $metarial_lot["input_item_lot"];

                //稼働状態(トレー)情報収得  今のLotIDの生産履歴あるかどうかの確認
                $q= 'SELECT * FROM hgpd_report_unit_tray WHERE hrut_lot_id = "'.$value["lot_id"].'" ORDER BY hrut_id DESC LIMIT 1 ';
                $st = $con->execute($q);
                $last_tray = $st->fetch(PDO::FETCH_ASSOC);
                // $serial=0;
                if($last_tray){
                    //履歴がある場合
                    $q= 'SELECT * FROM hgpd_report_unit_tray WHERE hrut_lot_id = "'.$value["lot_id"].'" ORDER BY hrut_id DESC ';
                    $st = $con->execute($q);
                    $res = $st->fetchall(PDO::FETCH_ASSOC);
                    $serial = intval($last_tray["hrut_serial_num"]);
                    $running = [];
                    //RT実績の登録フラグのチェック
                    if($res[0]["hrut_xls_flg"]==0){
                        //登録していないの処理
                        $serial = intval($last_tray["hrut_serial_num"]);
                        $q= 'SELECT * FROM hgpd_report_unit_tray WHERE hrut_lot_id = "'.$value["lot_id"].'" AND hrut_xls_flg = "0" ORDER BY hrut_id DESC ';
                        $st = $con->execute($q);
                        $running = $st->fetchall(PDO::FETCH_ASSOC);
                    }else{
                        //登録したの処理
                        $serial = intval($last_tray["hrut_serial_num"])+1;
                        $running=[];
                    }
                }else{
                    //履歴が無い場合:通しNoは1にセットする
                    $serial = 1;
                    $running = [];
                }
                $lot_info[$value["item_code"]]["running"]=[];
                foreach($running as $kr=>$vr){
                    if($vr["hrut_code"]==$value["item_code"] ){
                        $lot_info[$value["item_code"]]["running"][] = $vr;
                    }
                }
                $lot_info[$value["item_code"]]["serial"] = $serial;
            }
            $item_list = substr($item_list,0,-1);
            $lot_info["item_list"] =$item_list;
            $encode = json_encode($lot_info);
            header("Content-Type: application/json; charset=utf-8");
            echo $encode;
            exit;
        
            return sfView::NONE;
        }
    }

    public function executeGetPrintLog(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();

        $q="SELECT * FROM hgpd_report_event_log WHERE hrel_item = 'カンバン' ORDER BY hrel_id DESC ";
        $st = $con->execute($q);
        $res = $st->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        foreach($res as $key=>$value){
            print_r(json_decode($value["hrel_recode"]));
        }
        exit;

    }

    public function executeDataDeletedList(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();

        $q="SELECT * FROM correction_log cl LEFT JOIN xls_work_report xwr ON xwr.id = cl.xwr_id WHERE clt_new_value = '1' AND clt_col_name = 'del_flg' AND xwr.id IS NOT NULL ORDER BY clt_id DESC ";
        $st = $con->execute($q);
        $parent_res = $st->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // print_r($parent_res);
        // echo "<br>";
        // echo "<br>";

        $q="SELECT * FROM correction_log cl LEFT JOIN hgpd_report hr ON hr.hgpd_id = cl.xwr_id WHERE clt_new_value = '1' AND clt_col_name = 'hgpd_del_flg' AND hr.hgpd_id IS NOT NULL ORDER BY clt_id DESC ";
        $st = $con->execute($q);
        $child_res = $st->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // print_r($child_res);
        // exit;
        // return sfView::NONE;

    }
    
    public function executeSetBase(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();
        
        if (empty($_SERVER['HTTPS'])) {
            header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            exit;
        }
        $this->getResponse()->setTitle('不良検査｜Nalux');

        $client_app="";
        if(strpos($_SERVER["HTTP_USER_AGENT"],"Safari")!==false){
            $client_app = "Safari";
        }
        $this->client_app = $client_app;

        // print_r($_POST);exit;

        //実績登録
        if($request->getParameter("ac")=="XlsEntry"){
            if($_POST["data"]!=""){
                $created_at = date("Y-m-d H:i:s");
                $d = $_POST["data"];
                // print_r($d);
                // exit;
                $q = "SELECT * FROM ms_person WHERE user = '".$d["基本"]["担当者"]."'";
                $st = $con->execute($q);
                $user = $st->fetchall(PDO::FETCH_ASSOC);

                $diff_time = strtotime($d["終了"])-strtotime($d["開始"]);
                $exclusion_time = $diff_time - $d["基本"]["作業時間"];
                if($exclusion_time>0){
                    $d["exclusion_time"] = $exclusion_time/60;
                }else{
                    $d["exclusion_time"] = 0;
                }
                $sp_wi = explode(":",$d["基本"]["作業工程"]);

                $q = "SELECT workitem_no,workitem_name,workitem_class FROM ms_workitem_list WHERE workitem_name = '".$sp_wi[1]."' AND workitem_plant_name = '".$d["基本"]["工場"]."' ";
                $st = $con->execute($q);
                $wi = $st->fetch(PDO::FETCH_ASSOC);

                $q= "INSERT INTO xls_work_report( ";
                $q.="date, itemcord, itemname, xlsnum, workkind, ";                           //5
                $q.="workitem, usercord, username, usergp1, usergp2, ";                       //10
                $q.="moldplaceid, moldplace, itemform, moldlot, moldmachine, ";               //15
                $q.="totalnum, badnum, goodnum, pending_num, missing_num, ";                  //20
                $q.="e_or_d, e_or_d_memo, totaltime, remark, defectivesitem, ";               //25
                $q.="beforeprocess, afterprocess, cutmethod, vapordepositionlot, defect, ";   //30
                $q.="pdate, dateuse, scheduled_number, measure, state, ";                     //35
                $q.="ntab, cycle, materialsname, materialslot, materialsused, ";              //40
                $q.="lotstarttime, lotendtime, gootrate, hour, badrate, ";                    //45
                $q.="pproentry, workplankind, plankind, plantime, place, ";                   //50
                $q.="updating_person, del_flg, xstart_time1, xend_time1, xsetuptime, ";       //55
                $q.="created_at, updated_at) VALUES ( ";                                      //57

                $q.="'".date("Y-m-d")."',";
                $q.="'".$d["基本"]["案件コード"]."',";
                $q.="'".$d["基本"]["品名"]."',";
                $q.="'".$wi["workitem_no"]."',";
                $q.="'".$wi["workitem_class"]."',";                                //5
                $q.="'".$wi["workitem_name"]."',";
                $q.="'".$user[0]["ppro"]."',";
                $q.="'".$user[0]["user"]."',";
                $q.="'".$user[0]["gp1"]."',";
                $q.="'".$user[0]["gp2"]."',";                      //10
                $q.="'".$d["基本"]["工場ID"]."',";
                $q.="'".$d["基本"]["工場"]."',";
                $q.="'".$d["基本"]["型番"]."',";
                $q.="'".$d["基本"]["Lotコード"]."',";
                $q.="'".$d["基本"]["号機"]."',";                           //15
                $q.="'".$d["受入数"]."',";
                $q.="'".$d["廃棄数"]."',";
                $q.="'".$d["完成数"]."',";
                $q.="'0',";
                $q.="'".$d["missing_num"]."',";                       //20
                $q.="'".$d["員数ミス"]."',";
                $q.="'".$d["e_or_d_memo"]."',";
                $q.="'".($d["基本"]["作業時間"]/60)."',";
                $q.="'".$d["備考"]."',";
                $q.="'".$d["不良"]."',"; //ok                      //25
                $q.="'".$d["beforeprocess"]."',";
                $q.="'".$d["afterprocess"]."',";
                $q.="'".$d["cutmethod"]."',";
                $q.="'".$d["vapordepositionlot"]."',";
                $q.="'".$d["defect"]."',";                        //30
                $q.="'".$d["成形日"]."',";
                $q.="'".$d["dateuse"]."',";
                $q.="'".$d["scheduled_number"]."',";
                $q.="'".$d["measure"]."',";
                $q.="'".$d["state"]."',";                                 //35
                $q.="'0',"; //ok
                $q.="'".$d["基本"]["作業時間"] / $d["完成数"]."',";
                $q.="'".$d["materialsname"]."',";
                $q.="'".$d["materialslot"]."',";
                $q.="'".$d["materialsused"]."',";                         //40
                $q.="'".$d["lotstarttime"]."',";
                $q.="'".$d["lotendtime"]."',";
                $q.="'".(($d["完成数"] / ($d["受入数"]-$d["員数ミス"]))*100)."',";
                $q.="'".($d["受入数"] / ($d["基本"]["作業時間"]/3600))."',";
                $q.="'".(($d["廃棄数"] / ($d["受入数"]-$d["員数ミス"]))*100)."',";         //45
                $q.="'".$d["pproentry"]."',";
                $q.="'".$d["workplankind"]."',";
                $q.="'".$d["plankind"]."',";
                $q.="'".$d["plantime"]."',";
                $q.="'".$d["place"]."',";                                //50
                $q.="'タブレット端末',";
                $q.="'0',"; // del flg is OFF
                $q.="'".$d["開始"]."',";
                $q.="'".$d["終了"]."',";
                $q.="'".$d["exclusion_time"]."',";                       //55
                $q.="'".$created_at."',";
                $q.="'".$created_at."')";
                // print_r($q);
                // exit;
                try{
                    $con->execute($q);
                    $qs="SELECT id FROM xls_work_report ORDER BY id DESC ";
                    $st = $con->execute($qs);
                    $xls_id = $st->fetch(PDO::FETCH_ASSOC);

                    //レアルタイム実績IDとwlsIDの連携
                    $qu="UPDATE hgpd_report SET xwr_id = '".$xls_id["id"]."' WHERE hgpd_id IN (".$d["hgpd_id"].") ";
                    $con->execute($qu);
                }catch(Exception $e){
                    $err_q=$e->getMessage();
                }
                try{
                    //不良登録処理
                    $defectives[]=array('id'=>$xls_id['id'],'defectivesitem'=>$d["不良"],'badnum'=>$d["廃棄数"]);
                    $this->DitemSplitEntry($defectives);
                }catch(Exception $e){
                    $err_ditem=$e->getMessage();
                }
                if(!$err_q && !$err_ditem){

                    //実績データ集計
                    $url = "http://".$_SERVER['SERVER_NAME']."/LaborReport/LotAggregatecalculation?mode=SingleItem&code=".$d["基本"]["案件コード"]."&form=".$d["基本"]["型番"]."&lot=".$d["基本"]["Lotコード"];
                    if( PHP_OS == 'WINNT'){
                        file_get_contents($url);
                    }else{
                        exec("curl -s '".$url."' >> /dev/null 2>&1 &");
                    }

                    echo json_encode("OK");
                }else{
                    echo json_encode($err_q."<br>".$err_ditem);
                }
            }
            exit;
        }

        if($request->getParameter("ac")=="getLastHgpdData"){
            $q="SELECT * FROM hgpd_report WHERE hgpd_name = '".$request->getParameter("user")."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC LIMIT 10 ";
            $st=$con->execute($q);
            $list_data = $st->fetchall(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($list_data);
            exit;
        }

        if($request->getParameter("ac")=="getAssemblyInfo"){

            $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$request->getParameter("item_code")."' ";
            $st=$con->execute($q);
            $item_info = $st->fetch(PDO::FETCH_ASSOC);

            $q="SELECT * FROM work_adempiere_item_ms aim LEFT JOIN ms_molditem msm ON aim.befor_code = msm.itempprocord
            WHERE code = '".$request->getParameter("item_code")."' AND proccess_name = '".$request->getParameter("process")."' ";
            $st=$con->execute($q);
            $assembly_info = $st->fetchAll(PDO::FETCH_ASSOC);
            
            $response["item_info"] = $item_info;
            $response["assembly_info"] = $assembly_info;

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($response);
            exit;
        }


        if($request->getParameter("ac")=="getPartsRfid"){
            $rfid = $request->getParameter("code");
            $process = $request->getParameter("process");

            //Rfid情報収得
            $q="SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' AND hgpd_process != '出荷・在庫:梱包' ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $last_id = $st->fetch(PDO::FETCH_ASSOC);

            //BOMの確認
            $q="SELECT aim.code as code,aim.befor_code as befor_code 
            FROM work_adempiere_item_ms aim  
            WHERE aim.befor_code = '".$last_id["hgpd_itemcode"]."' AND aim.proccess_name = '".$process."' ";
            $st = $con->execute($q);
            $bom_data = $st->fetch(PDO::FETCH_ASSOC);

            print_r($bom_data);
            exit;
        }

        if($request->getParameter("ac")=="getRfid"){
            $rfid = $request->getParameter("code");
            $process = $request->getParameter("process");

            $q="SELECT hr.*,wic.wic_complete_flag FROM hgpd_report hr 
            LEFT JOIN work_inventory_control wic 
            ON wic.wic_hgpd_id = hr.hgpd_id 
            WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' AND hgpd_process != '出荷・在庫:梱包' 
            GROUP BY hgpd_id ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $last_id = $st->fetch(PDO::FETCH_ASSOC);

            if(!$last_id["hgpd_id"]){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["NG","入力したのRFIDは未登録です。<br>生産情報がありません。"]);
                exit; 
            }

            if($request->getParameter("bom_mode")=="on"){
                //完成品のbom SetBase
                $q="SELECT aim.code as code,aim.befor_code as befor_code FROM hgpd_report hr LEFT JOIN work_adempiere_item_ms aim ON hr.hgpd_itemcode = aim.code WHERE hr.hgpd_rfid = '".$rfid."' AND aim.proccess_name = '".$process."' AND hr.hgpd_del_flg = '0' ";
                $st = $con->execute($q);
                $bom_data = $st->fetch(PDO::FETCH_ASSOC);
                // print_r($bom_data);
                // exit;
                $befor_endcode = substr($bom_data["befor_code"],strlen($bom_data["code"]),2);

                $qc="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv
                FROM work_inventory_control WHERE wic_rfid = '".$rfid."' AND wic_process_key = '".$befor_endcode."' AND wic_complete_flag = '0' AND wic_remark <> '員数不足' AND wic_del_flg = '0' 
                GROUP BY wic_rfid, wic_itemcode, wic_itemform, wic_itemcav 
                ORDER BY sum_inv DESC ";
                $st = $con->execute($qc);
                $res_check = $st->fetch(PDO::FETCH_ASSOC);

                if($res_check["sum_inv"]>0){
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                    (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_id["hgpd_id"].") 
                    UNION ALL 
                    SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_sub["all_ids"]!=""){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $last_id["hgpd_id"];
                    }

                    $q="SELECT kb.xwr_id as id, hgpd_itemcode as itemcord, hgpd_itemform as itemform, i.itemname,hgpd_process as workitem, searchtag,hgpd_moldlot as moldlot, hgpd_wherhose as moldplace, cav_items, cav_tray_input, m.pieces, i.tray_num, i.tray_stok, i.materialsname, xwr.moldmachine, 
                    i.materialcode, hgpd_material_lot as materialslot, adm_price_std, kb.hgpd_id, kb.xwr_id, kb.hgpd_cav, wic.wic_itemcav, kb.hgpd_moldday, kb.hgpd_qtycomplete, kb.hgpd_rfid AS search_rfid ,wic_id, wic_process, wic_process_key, SUM(wic_qty_in) - SUM(wic_qty_out) as wic_qty_in,SUM(wic_qty_out) as wic_qty_out 
                    FROM hgpd_report kb
                        LEFT JOIN work_inventory_control wic ON kb.hgpd_id = wic.wic_hgpd_id 
                        LEFT JOIN ms_molditem i ON hgpd_itemcode = i.itempprocord 
                        LEFT JOIN ms_molditem_info m ON i.itempprocord = m.itemcord AND hgpd_itemform = m.form_num
                        LEFT JOIN xls_work_report xwr ON kb.xwr_id = xwr.id  
                    WHERE kb.hgpd_rfid='".$request->getParameter("code")."' AND wic_complete_flag = '0' AND wic_process_key = '".$befor_endcode."' AND kb.hgpd_id IN (".$list_ids.") AND wic_remark <> '員数不足' AND wic.wic_del_flg = '0' 
                    ORDER BY wic_id DESC ";
                    $st = $con->execute($q);
                    $lot_rfid = $st->fetch(PDO::FETCH_ASSOC); 
                    $lot_rfid["hgpd_id"]=$last_id["hgpd_id"];
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["OK",[$lot_rfid]]);
                }else if($res_check["sum_inv"]=="0"){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","入力したのRFIDは検査済です。"]);
                }else{
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","BOM情報の確認をお願い致します。"]);
                }
                exit;  
            }else{
                //BOM利用しない　SetBase
                $qc="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv
                FROM work_inventory_control WHERE wic_rfid = '".$rfid."' AND wic_process = '".$last_id["hgpd_process"]."' AND wic_remark <> '員数不足' AND wic_del_flg = '0' 
                GROUP BY wic_rfid, wic_itemcode, wic_itemform, wic_itemcav 
                ORDER BY sum_inv DESC ";
                // print_r($qc);
                // exit;
                $st = $con->execute($qc);
                $res_check = $st->fetch(PDO::FETCH_ASSOC);

                if($res_check["sum_inv"]>0){
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                    (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_id["hgpd_id"].") 
                    UNION ALL 
                    SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_sub["all_ids"]!="" && $last_id["hgpd_process"]=="完成品処理"){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $last_id["hgpd_id"];
                    }

                    $q="SELECT kb.xwr_id as id, hgpd_itemcode as itemcord, hgpd_itemform as itemform, i.itemname,hgpd_process as workitem, xwr.moldmachine, 
                    searchtag,hgpd_moldlot as moldlot, hgpd_wherhose as moldplace, cav_items, cav_tray_input, m.pieces, i.tray_num, i.tray_stok, 
                    i.materialsname, i.materialcode, hgpd_material_lot as materialslot, adm_price_std, kb.hgpd_id, kb.xwr_id, kb.hgpd_cav, wic.wic_itemcav, kb.hgpd_moldday, 
                    kb.hgpd_qtycomplete, kb.hgpd_rfid AS search_rfid ,wic_id, wic_process, wic_process_key, SUM(wic_qty_in) - SUM(wic_qty_out) as wic_qty_in,SUM(wic_qty_out) as wic_qty_out 
                    FROM hgpd_report kb
                        LEFT JOIN work_inventory_control wic ON kb.hgpd_id = wic.wic_hgpd_id 
                        LEFT JOIN ms_molditem i ON hgpd_itemcode = i.itempprocord 
                        LEFT JOIN ms_molditem_info m ON i.itempprocord = m.itemcord AND hgpd_itemform = m.form_num 
                        LEFT JOIN xls_work_report xwr ON kb.xwr_id = xwr.id 
                    WHERE kb.hgpd_rfid = '".$rfid."' AND kb.hgpd_id IN (".$list_ids.") AND wic_process = '".$last_id["hgpd_process"]."' AND wic_remark <> '員数不足' AND kb.hgpd_del_flg = '0' AND wic.wic_del_flg = '0' ";
                    if($last_id["wic_complete_flag"]=="1"){
                        $q.="GROUP BY wic.wic_itemcav,hgpd_process ORDER BY wic_id DESC ";
                    }else{
                        $q.="ORDER BY wic_id DESC ";
                    }
                    $st = $con->execute($q);
                    $lot_rfid = $st->fetchall(PDO::FETCH_ASSOC);
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["OK",$lot_rfid]);
                }else if($res_check["sum_inv"]=="0"){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","入力したのRFIDは製品連携してないです。"]);
                }else{
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","入力したのRFIDは未登録また完成IDです。<br>検査作業できません。"]);
                }
                exit;
            }
        }

        if($request->getParameter("ac")=="getCheckedSetData"){
            $q = "SELECT * FROM work_inventory_control wic LEFT JOIN hgpd_report hr ON wic.wic_hgpd_id = hr.hgpd_id WHERE wic_rfid = '".$request->getParameter('rfid')."' AND hr.hgpd_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $checked_list = $st->fetch(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($checked_list);
            exit;
        }

        if($request->getParameter("ac")=="checkBomInfo"){
            $itemcode = $request->getParameter("itemcode");
            $process = $request->getParameter("process");
            $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$itemcode."' AND proccess_name = '".$process."' ";
            $st = $con->execute($q);
            $bom_data = $st->fetch(PDO::FETCH_ASSOC);
            // print_r($bom_data);
            header("Content-Type: application/json; charset=utf-8");
            if(!$bom_data){
                echo json_encode("BOM連携が見つかりません。工程別マスターを確認してください。");
            }elseif($bom_data["end_code"]=="" || $bom_data["place_info"]==""){
                echo json_encode("工程別マスター情報の齟齬を確認してください。");
            }else{
                echo json_encode("OK");
            }
            exit;
        }

        //工程の一覧
        $plant=$request->getParameter("plant");
        $q="SELECT DISTINCT workitem_barcode,workitem_no,workitem_name FROM ms_workitem_list WHERE workitem_plant_name LIKE '%".$plant."%' ";
        $st = $con->execute($q);
        $workitem_list= $st->fetchall(PDO::FETCH_ASSOC);

        $this->workitem_list = json_encode($workitem_list);

        if($request->getParameter("ac")=="getInfo"){
            if($request->getParameter("mode")=="getPlotData"){
                $table ="xlsWorkReport";
                $date["ed"]=date("Y-m-d");
                $date["sd"]= date("Y-m-d",strtotime("-1 year"));

                $itemcode=$request->getParameter("itemcode");
                if($itemcode > "11"){
                    $itemcode = substr($itemcode,0,11);
                }
                if($this->getRequestParameter('lot')==""){
                    $lot = "%";
                    $data['lot'] = "全データ";
                    $data['all_button'] ="";
                }else{
                    $lot = $this->getRequestParameter('lot');
                    $data['lot'] = $this->getRequestParameter('lot');
                    $data['all_button'] ='<input type="button" value="全データ集計" onclick="javascript:location.href=\'/common/Analytical?q='.$this->getRequestParameter('q').'&t=xlsWorkReport\'">';
                }
                $moldmachine =$this->getRequestParameter('mno');
                if(!$moldmachine) { $moldmachine ="%"; }
                    $username=$this->getRequestParameter('username');
                if(!$username){
                    $username ="%";
                }
                $form = "%";
                if($this->getRequestParameter("form")!=""){
                    $form = urldecode($this->getRequestParameter("form"));;
                }
                
                $q="SELECT *,DATE_FORMAT(date, '%Y/%c/%e') as day FROM work.xls_work_report r ";
                $q.="WHERE itemcord LIKE '".$itemcode."%' and moldmachine LIKE '".$moldmachine."' and moldlot LIKE '".$lot."' ";
                $q.="AND del_flg <> '1' AND username LIKE '".$username."' AND date BETWEEN '".$date["sd"]."' AND '".$date["ed"]."' ";
                $q.="AND itemform LIKE '".$form."' ";
                $q.="ORDER BY date ASC,moldlot ASC,xlsnum ASC ";
                $st = $con->execute($q);
                $item = $st->fetchall(PDO::FETCH_ASSOC);

                $data["itemname"]    = $item[0]["itemname"];
                $data["itemcord"]    = $item[0]["itemcord"];
                $data["itemform"]    = $item[0]["itemform"];

                $q="SELECT searchtag FROM work.ms_molditem WHERE itempprocord = '".$item[0]["itemcord"]."' ";
                $st = $con->execute($q);
                $msitem = $st->fetchall(PDO::FETCH_ASSOC);

                if($msitem->searchtag){
                    $data["itemname"] .= "(".$msitem->searchtag.")";
                }
                foreach($item as $value){

                    //金型メンテと段取替えデータは不良数を0に設定しグラフを非表示にする
                    $data["total"][$value["workitem"]]["count"]     = $data["total"][$value["workitem"]]["count"] + 1;
                    if($value["workitem"]=="段取替え" or $value["workitem"]=="金型メンテ"){
                        $data["total"][$value["workitem"]]["totalnum"]  = "0";
                        $data["total"][$value["workitem"]]["goodnum"]   = "0";
                    }else{
                        $data["total"][$value["workitem"]]["totalnum"]  = $data["total"][$value["workitem"]]["totalnum"] + $value["totalnum"];
                        $data["total"][$value["workitem"]]["goodnum"]   = $data["total"][$value["workitem"]]["goodnum"] + $value["goodnum"];
                    }

                    $data["total"][$value["workitem"]]["badnum"]    = $data["total"][$value["workitem"]]["badnum"] + $value["badnum"];

                    $data["total"][$value["workitem"]]["totaltime"] = $data["total"][$value["workitem"]]["totaltime"] + $value["totaltime"];
                    $data["total"][$value["workitem"]]["xlsnum"]    = $value["xlsnum"];
                    $data['all_bt'] = $data['all_bt'] . "id".$value["xlsnum"].",";

                    //担当者の羅列　重複チェック
                    if(substr_count($data['total'][$value["workitem"]]['username'], $value["username"]) =="0"){
                        $data['total'][$value["workitem"]]['usercount'] = $data['total'][$value["workitem"]]['usercount'] +1;
                        $data['total'][$value["workitem"]]['username'] .=$value["username"]." ";
                        $data['total'][$value["workitem"]]['usernameArray'][] =$value["username"];
                    }

                    //成形機の羅列　重複チェック
                    if($value["moldmachine"] !="" && $value["moldmachine"] !="0" &&substr_count($data['moldmachine']['moldmachine'], trim($value["moldmachine"])) =="0"){
                        $data['moldmachine']['count'] = $data['moldmachine']['count'] +1;
                        $data['moldmachine']['moldmachine'] .=trim($value["moldmachine"])."号機";
                        $data['moldmachinearray'][] =$value["moldmachine"];
                        $data['moldmachinearray_form'][] =$value["itemform"];
                    }

                    $dedata = explode(",", $value["defectivesitem"]);

                    foreach($dedata as $res){
                        $expl = explode("=>", $res);
                        if($expl[0] !=""){
                            $data["total"][$value["workitem"]]["defectivesitem"][$expl[0]] = $data["total"][$value["workitem"]]["defectivesitem"][$expl[0]] + $expl[1];
                        }
                    }
                }
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($data);
                exit;
            }else{
                $code=$request->getParameter('code');
                $reslt="OK";
                if(substr_count($code,"=>")===3){
                    $sp_code= explode("=>",$code);
                }elseif(substr_count($code,"^>")===3){
                    $sp_code= explode("^>",$code);
                }else{
                    $res["生産情報"] = array("データ数"=>0,"データ"=>null);
                    $reslt="NG";
                }
                if(count($sp_code)!="4"){
                    $res["生産情報"] = array("データ数"=>0,"データ"=>null);
                    $reslt="NG";
                }
            
                if($reslt=="NG"){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode($reslt);
                    exit;
                }

                //ロット管理データ取得-品目情報
                $q="SELECT itemname,itemcord,itemform,moldmachine,moldplaceid,moldplace FROM xls_work_report WHERE itemcord =? AND moldlot =? LIMIT 1";
                $st = $con->prepare($q);
                $st->execute(array($request->getParameter("itemcord"),$request->getParameter("lot")));
                $data["info"] = $st->fetch(PDO::FETCH_ASSOC);
                $this->tabs = $data["info"]["itemcord"];

                // 実績の工程を取得　工程の絞り込み
                $q="SELECT workitem_barcode,workitem_no,workitem_name FROM xls_work_report s LEFT JOIN ms_workitem_list w ON w.workitem_name = s.workitem WHERE workitem_barcode !='' AND itemcord =? GROUP BY s.workitem";
                $st = $con->prepare($q);
                $st->execute(array($data["info"]["itemcord"]));
                $data["workitem"] = $st->fetchall(PDO::FETCH_ASSOC);

                // 型番キャビ内容から品目マスターを取得
                $q="SELECT i.tray_num,i.tray_stok,i.adm_price_std FROM ms_molditem i LEFT JOIN ms_molditem_info f ON i.itempprocord = f.itemcord AND f.form_num =?
                /* LEFT JOIN work_adempiere_item_ms a ON i.itempprocord = a.code */ WHERE i.itempprocord =? ";
                $st = $con->prepare($q);
                $st->execute(array($data["info"]["itemform"],$data["info"]["itemcord"]));
                $data["item"] = $st->fetchall(PDO::FETCH_ASSOC);

                //キャビ内訳から品目マスターを取得
                $q="SELECT cav_items,pieces FROM ms_molditem_info WHERE itemcord =? AND form_num=? ";
                $st = $con->prepare($q);
                $st->execute(array($data["info"]["itemcord"],$data["info"]["itemform"]));
                $data["item_info"] = $st->fetch(PDO::FETCH_ASSOC);
            
                $encode = json_encode($data);
                header("Content-Type: application/json; charset=utf-8");
                echo $encode;
                return sfView::NONE;
            }
            exit;
        }

        $today = time();
        $data["日付"]=date("n/j");
        $data['勤務'] = '昼勤';
        if (date('G', $today) > '19' || date('G', $today) < '8') {
            $data['日付'] = date('n/j', strtotime('-1 day'));
            $data['勤務'] = '夜勤';
        }

        $this->data = $data;
        $this->setlayout("none");

    }

    public function executeTallyCounter(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();
        if (empty($_SERVER['HTTPS'])) {
            header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            exit;
        }
        $this->getResponse()->setTitle('不良カウンター ｜ Nalux');

        if($request->getParameter("ac")=="Entry"){
            if($_POST["data"]!=""){
                $created_at = date("Y-m-d H:i:s");
                $d = $_POST["data"];
                $q = "SELECT * FROM ms_person WHERE user = '".$d["基本"]["担当者"]."'";
                $st = $con->execute($q);
                $user = $st->fetchall(PDO::FETCH_ASSOC);

                $diff_time = strtotime($d["終了"])-strtotime($d["開始"]);
                $exclusion_time = $diff_time - $d["基本"]["作業時間"];
                if($exclusion_time>0){
                    $d["exclusion_time"] = $exclusion_time/60;
                }else{
                    $d["exclusion_time"] = 0;
                }
                $sp_wi = explode(":",$d["基本"]["作業工程"]);

                $workdate=date("Y-m-d",strtotime($d["終了"]));
                if(date("H",strtotime($d["終了"]))<8){
                    $workdate=date("Y-m-d",strtotime("-1 day ".$workdate));
                }
                $wl[0] = substr(date("y",strtotime($workdate)) , -1);
                $wl[1] = date("n",strtotime($workdate));
                if($wl[1]=="10"){
                    $wl[1] ="X";
                }elseif($wl[1]=="11"){
                    $wl[1] = "Y";
                }elseif($wl[1]=="12"){
                    $wl[1] = "Z";
                }
                $wl[2] = date("d",strtotime($workdate));
                $worklot = implode("",$wl);        

                $q2 = "INSERT INTO hgpd_report (xwr_id, hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_cav, hgpd_itemform,hgpd_moldlot,hgpd_worklot,hgpd_checkday,hgpd_moldday,hgpd_quantity,hgpd_qtycomplete,hgpd_difactive,hgpd_remaining,";
                $q2.= "hgpd_namecode,hgpd_name,hgpd_start_at,hgpd_stop_at,hgpd_exclusion_time,hgpd_working_hours,hgpd_volume,hgpd_cycle,hgpd_rfid,hgpd_materiall,hgpd_material_code,hgpd_material_lot,hgpd_status,created_at) ";
                $q2.= "VALUES ";
                $tabs = $d["キャビ"];
                if($tabs){
                    $tabs = explode(",", $tabs);
                    foreach($tabs as $key => $value){
                        //重複データのチェック
                        if($d["RFID"]!=""){
                            $q = "SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$d["RFID"]."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $cd = $st->fetch(PDO::FETCH_ASSOC);
                            if($cd["hgpd_process"]==$sp_wi[1] && $cd["hgpd_itemcode"]==$d["基本"]["案件コード"] && $cd["hgpd_moldday"]==date("Y-m-d",strtotime(str_replace('/','-',$d["数基-本"]["成形日"]))) && $cd["hgpd_quantity"]==$d["数基-本"][$value]["受入数"] && $cd["hgpd_qtycomplete"]==$d["数基-本"][$value]["完成数"]){
                                $res_json =["OK","hgpd_id"=>$cd["hgpd_id"]];
                                echo json_encode($res_json);
                                exit;
                            }
                        }

                        $q2.= "('',";
                        $q2.= "'".$d["基本"]["工場"]."',";
                        $q2.= "'".$sp_wi[1]."',";
                        $q2.= "'".$d["基本"]["案件コード"]."',";
                        $q2.= "'".$value."',";
                        $q2.= "'".$d["基本"]["型番"]."',";
                        $q2.= "'".$d["基本"]["Lotコード"]."',";
                        $q2.= "'".$worklot."',";
                        $q2.= "'".$workdate."',";
                        $q2.= "'".$d["数基-本"]["成形日"]."',";
                        $q2.= "'".$d["数基-本"][$value]["受入数"]."',";
                        $q2.= "'".$d["数基-本"][$value]["完成数"]."',";
                        $q2.= "'".$d["数基-本"][$value]["廃棄数"]."',";
                        $q2.= "'".$d["数基-本"][$value]["残数"]."',";
                        $q2.= "'".$user[0]["ppro"]."',";
                        $q2.= "'".$d["基本"]["担当者"]."',";
                        $q2.= "'".$d["開始"]."',";
                        $q2.= "'".$d["終了"]."',";
                        $q2.= "'".$d["exclusion_time"]."',";
                        $q2.= "'".floatval($d["基本"]["作業時間"]/3600)."',";
                        $q2.= "'".($d["数基-本"][$value]["完成数"]/($d["基本"]["作業時間"]/3600))."',";
                        $q2.= "'".($d["基本"]["作業時間"]/$d["数基-本"][$value]["完成数"])."',";
                        // $q2.= "'".$d["qrcode"]."',";                  //QRcode
                        $q2.= "'".$d["RFID"]."',";                  //hgpd_rfid
                        // $q2.= "'".$d["rfid_complete"]."',";         //hgpd_complete_rfid-削除
                        $q2.= "'".$d["基本"]["原料名"]."',";
                        $q2.= "'".$d["基本"]["原料コード"]."',";
                        $q2.= "'".$d["基本"]["原料ロット"]."',";
                        if($sp_wi[1]!="保留処理"){
                            $q2.= "'正常',";
                        }else{
                            $q2.= "'異常',";
                        }
                        // $q2.= "'".$d["基本"]["前工程ID"]."',";       //削除
                        $q2.= "'".$created_at."'), ";
                    }
                }else{
                    //重複データのチェック
                    if($d["RFID"]!=""){
                        $q = "SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$d["RFID"]."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC LIMIT 1 ";
                        $st = $con->execute($q);
                        $cd = $st->fetch(PDO::FETCH_ASSOC);
                        if($cd["hgpd_process"]==$sp_wi[1] && $cd["hgpd_itemcode"]==$d["基本"]["案件コード"] && $cd["hgpd_moldday"]==date("Y-m-d",strtotime(str_replace('/','-',$d["成形日"]))) && $cd["hgpd_quantity"]==$d["受入数"] && $cd["hgpd_qtycomplete"]==$d["完成数"]){
                            $res_json =["OK","hgpd_id"=>$cd["hgpd_id"]];
                            echo json_encode($res_json);
                            exit;
                        }
                    }

                    $now_hgpd_process = $sp_wi[1];

                    $rfid = $d["RFID"];
                    if($d["仕掛ID連携"]!=""){
                        $rfid = $d["仕掛ID連携"];

                        //最新の在庫データ工程チェック
                        $q="SELECT * FROM hgpd_report hr LEFT JOIN work_inventory_control wic ON hr.hgpd_id = wic.wic_hgpd_id  WHERE wic_rfid = '".$d["RFID"]."' AND hgpd_id = '".$d["前工程ID"]."' AND wic.wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
                        $st = $con->execute($q);
                        $check_last_proc = $st->fetch(PDO::FETCH_ASSOC);

                        $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND end_code = '".$check_last_proc["wic_process_key"]."' ";
                        $st = $con->execute($q);
                        $hgpd_bm_zero = $st->fetch(PDO::FETCH_ASSOC);

                        $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND item_code = '".$hgpd_bm_zero["befor_code"]."' ";
                        $st = $con->execute($q);
                        $hgpd_bm = $st->fetch(PDO::FETCH_ASSOC);

                        $now_hgpd_process = $hgpd_bm["proccess_name"];

                    }else{
                        $rfid = $d["RFID"];
                    }

                    $q2.= "('',";
                    $q2.= "'".$d["基本"]["工場"]."',";
                    $q2.= "'".$now_hgpd_process."',";
                    $q2.= "'".$d["基本"]["案件コード"]."',";
                    $q2.= "'".$d["基本"]["キャビ"]."',";
                    $q2.= "'".$d["基本"]["型番"]."',";
                    $q2.= "'".$d["基本"]["Lotコード"]."',";
                    $q2.= "'".$worklot."',";
                    $q2.= "'".$workdate."',";
                    $q2.= "'".$d["成形日"]."',";
                    $q2.= "'".$d["受入数"]."',";
                    $q2.= "'".$d["完成数"]."',";
                    $q2.= "'".$d["廃棄数"]."',";
                    $q2.= "'".$d["残数"]."',";
                    $q2.= "'".$user[0]["ppro"]."',";
                    $q2.= "'".$d["基本"]["担当者"]."',";
                    $q2.= "'".$d["開始"]."',";
                    $q2.= "'".$d["終了"]."',";
                    $q2.= "'".$d["exclusion_time"]."',";
                    $q2.= "'".floatval($d["基本"]["作業時間"]/3600)."',";
                    $q2.= "'".($d["完成数"]/($d["基本"]["作業時間"]/3600))."',";
                    $q2.= "'".($d["基本"]["作業時間"]/$d["完成数"])."',";
                    // $q2.= "'".$d["qrcode"]."',";          //QRcode-削除
                    $q2.= "'".$rfid."',";              //hgpd_rfid
                    // $q2.= "'".$d["rfid_complete"]."',";          //hgpd_complete_rfid-削除
                    $q2.= "'".$d["基本"]["原料名"]."',";
                    $q2.= "'".$d["基本"]["原料コード"]."',";
                    $q2.= "'".$d["原料ロット"]."',";
                    if($sp_wi[1]!="保留処理"){
                        $q2.= "'正常',";
                    }else{
                        $q2.= "'異常',";
                    }
                    // $q2.= "'".$d["前工程ID"]."',";               //削除
                    $q2.= "'".$created_at."'), ";
                }

                $q2 = substr($q2, 0,-2);
                try{
                    $con->execute($q2);
                    $q="SELECT hgpd_id FROM hgpd_report WHERE hgpd_rfid = '".$rfid."' ORDER BY hgpd_id DESC ";
                    $st = $con->execute($q);
                    $hgpd_id = $st->fetch(PDO::FETCH_ASSOC);

                    //前工程実績連携
                    if($d["前工程ID"] && ($hgpd_id["hgpd_id"] != $d["前工程ID"])){
                        $qc = "INSERT INTO hgpd_report_sub (hgpd_complete_id,hgpd_before_id) VALUES ('".$hgpd_id["hgpd_id"]."','".$d["前工程ID"]."') ";
                        $con->execute($qc);
                    }
                }catch(Exception $e){
                    $err_q2=$e->getMessage();
                }

                $item=array();
                $ex = explode(",", $d["不良"]);
                foreach ($ex as $val) {
                    $exd = explode("=>", $val);
                    if($exd[0] && $exd[1] > 0){
                        $item[$exd[0]] = $exd[1];
                    }
                }
                if($item){
                    $q3 = "INSERT INTO hgpd_report_defectiveitem ";
                    $q3.= "(hgpd_id,hgpdd_ditem,hgpdd_qty,hgpdd_price,hgpdd_time) ";
                    $q3.= "VALUES (";
                    foreach($item as $key=>$value){
                        $q3.= "'".$hgpd_id["hgpd_id"]."',";
                        $q3.= "'".$key."',";
                        $q3.= "'".$value."',";
                        $q3.= "'".($value*$d["単価"])."',";
                        $q3.= "'".($d["基本"]["作業時間"] / $d["完成数"])*$value."'),(";
                    }
                    $q3 = substr($q3, 0,-2);
                    try{
                        $con->execute($q3);
                    }catch(Exception $e){
                        $err_q3=$e->getMessage();
                    }
                }

                $move_date = date("Y/").$d["基本"]["作業日"];
                
                //RFIDが無い製品は在庫入力しないように
                if($d["RFID"]!=""){
                    //最新の在庫データチェック
                    $q="SELECT * FROM hgpd_report hr 
                    LEFT JOIN work_inventory_control wic 
                    ON hr.hgpd_id = wic.wic_hgpd_id  
                    WHERE wic_rfid = '".$d["RFID"]."' AND hgpd_id = '".$d["前工程ID"]."' AND wic.wic_del_flg = '0' 
                    ORDER BY wic_id DESC ";
                    $st = $con->execute($q);
                    $check_last_wic = $st->fetch(PDO::FETCH_ASSOC);

                    //保留処理場合は出入在庫の記録をしないように
                    // 2024/09/11 浜崎係長依頼：保留処理を追加
                    // if($check_last_wic["wic_process"] != "保留処理"){
                        //行程の情報収得 TallyCounter
                        $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND proccess_name = '".$sp_wi[1]."' ";
                        $st = $con->execute($q);
                        $now_res = $st->fetchall(PDO::FETCH_ASSOC);
                        if(count($now_res)==0){
                            //bomがない場合:BOM利用しない
                            $q="SELECT workitem as proccess_name, place as place_info, SUBSTRING(state, 1, 1) as end_code FROM xls_work_report WHERE moldplace = '".$d["基本"]["工場"]."' AND workitem = '".$sp_wi[1]."' ORDER BY id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $now_process_info = $st->fetch(PDO::FETCH_ASSOC);
                            if(!$now_process_info["proccess_name"]){
                                $now_process_info["place_info"]="";
                                $now_process_info["proccess_name"]="";
                                $now_process_info["end_code"]="";
                            }

                            if($sp_wi[1]=="成形"){
                                //成形工程戻す
                                $now_process_info["befor_place_info"] = $check_last_wic["wic_wherhose"];
                                $now_process_info["befor_proccess_name"] = "保留処理";
                                $now_process_info["befor_end_code"] = "M";
                                $now_process_info["end_code"] = "M";
                            }else{
                                $now_process_info["befor_place_info"] = $check_last_wic["wic_wherhose"];
                                $now_process_info["befor_proccess_name"] = $check_last_wic["wic_process"];
                                $now_process_info["befor_end_code"] = $check_last_wic["wic_process_key"];

                                if($sp_wi[1]=="保留処理"){
                                    if($check_last_wic["wic_complete_flag"]=="1"){
                                        //完成品の不具合発見の保留処理
                                        if($check_last_wic["wic_process"] == "保留処理" ){
                                            //2回目保留処理
                                            $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND end_code = '".$check_last_wic["wic_process_key"]."' ";
                                            $st = $con->execute($q);
                                            $bm_res_zero = $st->fetch(PDO::FETCH_ASSOC);
        
                                            $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND item_code = '".$bm_res_zero["befor_code"]."' ";
                                            $st = $con->execute($q);
                                            $bm_res = $st->fetch(PDO::FETCH_ASSOC);
        
                                            if($bm_res && $d["仕掛ID連携"]!=""){
                                                //完成品の保留処理の仕掛ID発行の処理
                            
                                                $now_process_info["place_info"]=$bm_res["place_info"];
                                                $now_process_info["proccess_name"] = $bm_res["proccess_name"];
                                                $now_process_info["end_code"] = $bm_res["end_code"];
                                            }
                                        }else{
                                            //1回目保留処理
                                            $now_process_info["place_info"] = $check_last_wic["wic_wherhose"];
                                            $now_process_info["proccess_name"] = "保留処理";
                                            $now_process_info["end_code"] = $check_last_wic["wic_process_key"];
                                        }
    
                                    }else{
                                        $now_process_info["place_info"] = $check_last_wic["wic_wherhose"];
                                        $now_process_info["proccess_name"] = $sp_wi[1];
                                        $now_process_info["end_code"] = $check_last_wic["wic_process_key"];
                                    }
                                }else{
                                    //仕掛品の不具合発見の保留処理
                                    $now_process_info["place_info"] = $check_last_wic["wic_wherhose"];
                                    $now_process_info["proccess_name"] = "保留処理";
                                    $now_process_info["end_code"] = $check_last_wic["wic_process_key"];
                                }
                 
                            }
                        }else if(count($now_res)==1){
                            if($request->getParameter("bom_mode")=="on"){
                                $now_process_info=$now_res[0];
                                $before_end_code=substr($now_process_info["befor_code"],strlen($now_process_info["code"]),1);
                                $now_process_info["befor_end_code"]=$before_end_code;

                                //前行程の情報収得
                                $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND item_code = '".$now_process_info["befor_code"]."' ";
                                $st = $con->execute($q);
                                $before_res = $st->fetch(PDO::FETCH_ASSOC);
                                
                                $now_process_info["befor_proccess_name"] = $before_res["proccess_name"];

                            }else{
                                //BOM利用しない
                                $now_process_info=$now_res[0];
                                // $before_end_code=substr($now_process_info["befor_code"],strlen($now_process_info["code"]),1);
                                // $now_process_info["befor_end_code"]=$before_end_code;
                                if($sp_wi[1]=="成形"){
                                    $now_process_info["befor_place_info"] = $check_last_wic["wic_wherhose"];
                                    $now_process_info["befor_proccess_name"] = "保留処理";
                                    $now_process_info["befor_end_code"] = "M";
                                }else{
                                    $now_process_info["befor_place_info"] = $check_last_wic["wic_wherhose"];
                                    $now_process_info["befor_proccess_name"] = $check_last_wic["wic_process"];
                                    $now_process_info["befor_end_code"] = $check_last_wic["wic_process_key"];
                                }
                            }
                        }else{
                            //組立の処理-原料連携（組立作業仕組みは利用してないので、無効になる）
                            // foreach($now_res as $key=>$value){
                            //   // $now_process_info["befor_code"].=$value["befor_code"].',';
                            //   // $now_process_info["place_info"].=$value["place_info"].',';
                            //   // $now_process_info["befor_place_info"].=$value["befor_place_info"].',';
                            //   // $now_process_info["item_code"]=$value["item_code"];
                            //   $now_process_info=$value;
                    
                            //   //前行程の情報収得
                            //   $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$value["befor_code"]."' AND item_code = '".$value["befor_code"]."' ";
                            //   $st = $con->execute($q);
                            //   $before_res = $st->fetch(PDO::FETCH_ASSOC);
                            //   $now_process_info["befor_end_code"]=$before_res["end_code"];

                            //   //出在庫の計算
                            //   $q="SELECT wic_inventry_num FROM work_inventory_control ";
                            //   $q.="WHERE wic_itemcode = '".$value["befor_code"]."' AND wic_wherhose = '".$now_process_info["befor_place_info"]."' AND wic_process_key = '".$now_process_info["befor_end_code"]."' "; 
                            //   $q.="ORDER BY wic_created_at DESC,wic_id DESC LIMIT 1 ";
                            //   $st = $con->execute($q);
                            //   $out_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                            //   //検査前に仕掛品移動の記録
                            //   $qwic_out = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_created_at) ";
                            //   $qwic_out.= "VALUES ( ";
                            //   $qwic_out.= "'".$hgpd_id["hgpd_id"]."',";
                            //   $qwic_out.= "'".$move_date."',";
                            //   $qwic_out.= "'".$d["基本"]["担当者"]."',";
                            //   $qwic_out.= "'".$now_process_info["befor_place_info"]."',";
                            //   $qwic_out.= "'".$d["RFID"]."',";
                            //   $qwic_out.= "'".$value["befor_code"]."',";
                            //   $qwic_out.= "'".$now_process_info["befor_end_code"]."',";
                            //   $qwic_out.= "'".$now_process_info["proccess_name"]."',";
                            //   $qwic_out.= "'".$d["基本"]["型番"]."',";
                            //   $qwic_out.= "'".$d["基本"]["キャビ"]."',";
                            //   $qwic_out.= "'',";
                            //   $qwic_out.= "'".$d["受入数"]."',";
                            //   $qwic_out.= "'".(intval($out_inv_num["wic_inventry_num"])-intval($d["受入数"]))."',";
                            //   $qwic_out.= "'".$d["備考"]."',";          //remark
                            //   $qwic_out.= "'".date("Y-m-d H:i:s")."')";
                            //   $con->execute($qwic_out);
                            // }
                        }

                        if(intval($d["員数ミス"])<0){
                            //員数ミス入出在庫の計算
                            $q="SELECT wic_inventry_num FROM work_inventory_control ";
                            $q.="WHERE wic_itemcode = '".$d["基本"]["案件コード"]."' 
                                AND wic_wherhose = '".$now_process_info["befor_place_info"]."' 
                                AND wic_process = '".$now_process_info["befor_proccess_name"]."' 
                                AND wic_process_key = '".$now_process_info["befor_end_code"]."' 
                                AND wic_complete_flag = '0' "; 
                            // $q.="GROUP BY wic_process_key,wic_complete_flag ";
                            $q.="AND wic_del_flg = '0' "; 
                            $q.="ORDER BY wic_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $out_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                            $qwic_fix = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                            $qwic_fix.= "VALUES ( ";
                            $qwic_fix.= "'".$hgpd_id["hgpd_id"]."',";
                            $qwic_fix.= "'".$hgpd_id["hgpd_id"]."',";
                            $qwic_fix.= "'".$d["前工程ID"]."',";
                            $qwic_fix.= "'".$move_date."',";
                            $qwic_fix.= "'".$d["基本"]["担当者"]."',";
                            $qwic_fix.= "'".$now_process_info["befor_place_info"]."',";
                            $qwic_fix.= "'".$d["RFID"]."',";
                            $qwic_fix.= "'".$d["基本"]["案件コード"]."',";
                            $qwic_fix.= "'".$now_process_info["befor_end_code"]."',";
                            $qwic_fix.= "'".$now_process_info["befor_proccess_name"]."',";
                            $qwic_fix.= "'".$d["基本"]["型番"]."',";
                            $qwic_fix.= "'".$d["基本"]["キャビ"]."',";
                            $qwic_fix.= "'".abs($d["員数ミス"])."',";
                            $qwic_fix.= "'0',";
                            $qwic_fix.= "'".(intval($out_inv_num["wic_inventry_num"])+abs($d["員数ミス"]))."',";
                            $qwic_fix.= "'員数過多​',";          //remark
                            $qwic_fix.= "'0',";
                            $qwic_fix.= "'".$created_at."')";
                            try{
                                $con->execute($qwic_fix);
                            }catch(Exception $e){
                                $err_qf=$e->getMessage();
                            }
                        }

                        //出在庫の計算
                        $q="SELECT wic_inventry_num FROM work_inventory_control ";
                        $q.="WHERE wic_itemcode = '".$d["基本"]["案件コード"]."' 
                            AND wic_wherhose = '".$now_process_info["befor_place_info"]."' 
                            AND wic_process = '".$now_process_info["befor_proccess_name"]."' 
                            AND wic_process_key = '".$now_process_info["befor_end_code"]."' ";
                        if($now_process_info["befor_proccess_name"]=="完成品処理" || ($now_process_info["befor_proccess_name"]=="保留処理" && $check_last_wic["wic_process"]=="保留処理")){
                            $q.="AND wic_complete_flag = '1' "; 
                        }else{
                            $q.="AND wic_complete_flag = '0' "; 
                        }
                        $q.="AND wic_del_flg = '0' "; 
                        // $q.="GROUP BY wic_process_key,wic_complete_flag ";
                        $q.="ORDER BY wic_id DESC LIMIT 1 ";
                        $st = $con->execute($q);
                        $out_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                        //検査前に仕掛品移動の記録
                        $qwic_out = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                        $qwic_out.= "VALUES ( ";
                        $qwic_out.= "'".$hgpd_id["hgpd_id"]."',";
                        $qwic_out.= "'".$hgpd_id["hgpd_id"]."',";
                        $qwic_out.= "'".$d["前工程ID"]."',";
                        $qwic_out.= "'".$move_date."',";
                        $qwic_out.= "'".$d["基本"]["担当者"]."',";
                        $qwic_out.= "'".$now_process_info["befor_place_info"]."',";
                        $qwic_out.= "'".$d["RFID"]."',";
                        $qwic_out.= "'".$d["基本"]["案件コード"]."',";
                        $qwic_out.= "'".$now_process_info["befor_end_code"]."',";
                        $qwic_out.= "'".$now_process_info["befor_proccess_name"]."',";
                        $qwic_out.= "'".$d["基本"]["型番"]."',";
                        $qwic_out.= "'".$d["基本"]["キャビ"]."',";
                        $qwic_out.= "'0',";
                        $qwic_out.= "'".$d["受入数"]."',";
                        $qwic_out.= "'".(intval($out_inv_num["wic_inventry_num"])-intval($d["受入数"]))."',";
                        if($d["完成数"]==0 && $now_process_info["proccess_name"]=="保留処理" && $check_last_wic["wic_process"]!="保留処理"){
                            //不具合発見→保留処理（1回目）
                            $qwic_out.= "'廃棄',";                                                //remark
                        }else{
                            if($check_last_wic["wic_process"]=="保留処理"){
                                //保留処理（2回目）
                                $qwic_out.= "'保留処理出庫',";          //remark
                            }else{
                                $qwic_out.= "'".$now_process_info["proccess_name"]."出庫',";          //remark
                            }
                        }
                        if($now_process_info["befor_proccess_name"]=="完成品処理" || ($now_process_info["befor_proccess_name"]=="保留処理" && $check_last_wic["wic_process"]=="保留処理")){
                            $qwic_out.= "'1',";
                        }else{
                            $qwic_out.= "'0',";
                        }
                        $qwic_out.= "'".$created_at."')";
                        try{
                            $con->execute($qwic_out);
                        }catch(Exception $e){
                            $err_qo=$e->getMessage();
                        }

                        if($d["残数"]>0){
                            $q="SELECT wic_inventry_num FROM work_inventory_control 
                            WHERE wic_itemcode = '".$d["基本"]["案件コード"]."' 
                                AND wic_wherhose = '".$now_process_info["befor_place_info"]."' 
                                AND wic_process = '".$now_process_info["befor_proccess_name"]."' 
                                AND wic_process_key = '".$now_process_info["befor_end_code"]."' 
                                AND wic_complete_flag = '0' 
                                AND wic_del_flg = '0' 
                            ORDER BY wic_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $in_inv_num = $st->fetch(PDO::FETCH_ASSOC);
                            
                            $qwic_back = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                            $qwic_back.= "VALUES ( ";
                            $qwic_back.= "'".$hgpd_id["hgpd_id"]."',";
                            $qwic_back.= "'".$hgpd_id["hgpd_id"]."',";
                            $qwic_back.= "'".$d["前工程ID"]."',";
                            $qwic_back.= "'".$move_date."',";
                            $qwic_back.= "'".$d["基本"]["担当者"]."',";
                            $qwic_back.= "'".$now_process_info["befor_place_info"]."',";
                            $qwic_back.= "'".$d["RFID"]."',";
                            $qwic_back.= "'".$d["基本"]["案件コード"]."',";
                            $qwic_back.= "'".$now_process_info["befor_end_code"]."',";
                            $qwic_back.= "'".$now_process_info["befor_proccess_name"]."',";
                            $qwic_back.= "'".$d["基本"]["型番"]."',";
                            $qwic_back.= "'".$d["基本"]["キャビ"]."',";
                            $qwic_back.= "'".$d["残数"]."',";
                            $qwic_back.= "'0',";
                            $qwic_back.= "'".(intval($in_inv_num["wic_inventry_num"])+intval($d["残数"]))."',";
                            $qwic_back.= "'".$now_process_info["proccess_name"]."残数',";          //remark
                            $qwic_back.= "'0',";
                            $qwic_back.= "'".$created_at."')";
                            try{
                                $con->execute($qwic_back);
                            }catch(Exception $e){
                                $err_qb=$e->getMessage();
                            }
                        }
                        
                        //検査終わるの移動の記録
                        if($d["完成数"]>0){  
                            //次の行程の情報収得
                            $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$d["基本"]["案件コード"]."' AND befor_code = '".$now_process_info["item_code"]."' ";
                            // print_r($q);
                            // exit;
                            $st = $con->execute($q);
                            $next_res = $st->fetch(PDO::FETCH_ASSOC);
                    
                            //入在庫の計算
                            $q="SELECT wic_inventry_num FROM work_inventory_control ";
                            $q.="WHERE wic_itemcode = '".$d["基本"]["案件コード"]."' 
                                AND wic_wherhose = '".$now_process_info["place_info"]."' 
                                AND wic_process = '".$now_process_info["proccess_name"]."' 
                                AND wic_process_key = '".$now_process_info["end_code"]."' 
                                AND wic_complete_flag = '0' "; 
                            $q.="AND wic_del_flg = '0' ";
                            // $q.="GROUP BY wic_process_key,wic_complete_flag ";
                            $q.="ORDER BY wic_id DESC LIMIT 1 ";
                            // print_r($q);
                            // exit;
                            $st = $con->execute($q);
                            $in_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                            if(intval($d["員数ミス"])>0){
                                $qwic_fix = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                                $qwic_fix.= "VALUES ( ";
                                $qwic_fix.= "'".$hgpd_id["hgpd_id"]."',";
                                $qwic_fix.= "'".$hgpd_id["hgpd_id"]."',";
                                $qwic_fix.= "'".$d["前工程ID"]."',";
                                $qwic_fix.= "'".$move_date."',";
                                $qwic_fix.= "'".$d["基本"]["担当者"]."',";
                                $qwic_fix.= "'".$now_process_info["place_info"]."',";
                                $qwic_fix.= "'".$d["RFID"]."',";
                                $qwic_fix.= "'".$d["基本"]["案件コード"]."',";
                                $qwic_fix.= "'".$now_process_info["end_code"]."',";
                                $qwic_fix.= "'".$now_process_info["proccess_name"]."',";
                                $qwic_fix.= "'".$d["基本"]["型番"]."',";
                                $qwic_fix.= "'".$d["基本"]["キャビ"]."',";
                                $qwic_fix.= "'0',";
                                $qwic_fix.= "'".abs($d["員数ミス"])."',";
                                $qwic_fix.= "'".intval($in_inv_num["wic_inventry_num"])."',";
                                $qwic_fix.= "'員数不足​',";          //remark
                                $qwic_fix.= "'0',";
                                $qwic_fix.= "'".$created_at."')";
                                try{
                                    $con->execute($qwic_fix);
                                }catch(Exception $e){
                                    $err_qf=$e->getMessage();
                                }
                            }

                            //完成品の不具合保留処理対応の追加
                            $qwic_in = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                            $qwic_in.= "VALUES ( ";
                            $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
                            $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
                            $qwic_in.= "'".$d["前工程ID"]."',";
                            $qwic_in.= "'".$move_date."',";
                            $qwic_in.= "'".$d["基本"]["担当者"]."',";
                            $qwic_in.= "'".$now_process_info["place_info"]."',";
                            $qwic_in.= "'".$rfid."',";
                            $qwic_in.= "'".$d["基本"]["案件コード"]."',";
                            $qwic_in.= "'".$now_process_info["end_code"]."',";
                            $qwic_in.= "'".$now_process_info["proccess_name"]."',";
                            $qwic_in.= "'".$d["基本"]["型番"]."',";
                            $qwic_in.= "'".$d["基本"]["キャビ"]."',";
                            $qwic_in.= "'".$d["完成数"]."',";
                            $qwic_in.= "'0',";
                            $qwic_in.= "'".(intval($in_inv_num["wic_inventry_num"])+intval($d["完成数"]))."',";
                            if($sp_wi[1]=="保留処理"){
                                if($check_last_wic["hgpd_process"]=="保留処理"){
                                    $qwic_in.= "'仕掛ID再発行',";
                                }else{
                                    $qwic_in.= "'保留処理入庫',";          //remark
                                }
                            }else{
                                $qwic_in.= "'".$now_process_info["proccess_name"]."入庫',";          //remark
                            }
                            if($sp_wi[1]=="保留処理" && $check_last_wic["hgpd_process"]=="完成品処理"){
                                //完成品の不具合処理の場合
                                $qwic_in.= "'1',";
                            }else{
                                $qwic_in.= "'0',";
                            }
                            $qwic_in.= "'".$created_at."')";
                            try{
                                $con->execute($qwic_in);
                            }catch(Exception $e){
                                $err_qin=$e->getMessage();
                            }
                        }
                    // }
                }

                if(!$err_q2 && !$err_q3 && !$err_qin && !$err_qb && !$err_qo && !$err_qf){
                    $res_json =["OK","hgpd_id"=>$hgpd_id["hgpd_id"]];
                    echo json_encode($res_json);
                }else{
                    $color_id = "green";
                    $place=urldecode($d["基本"]["工場"]);
                    if ($place=="野洲工場") {
                        $project_id = 11;
                        $creator_id = 2;
                        $column_id = 59;
                        $swimlane_id =137;
                        $owner_id =366;
                    }
                
                    $title="【動作確認依頼】".date("n/j").":担当:".$d["基本"]["担当者"]."さん:リアルタイムタブレット検査画面の確認";
                    $description="- リアルタイムタブレット検査画面はエーラーが発生しました。動作確認お願い致します。<br>エーラー発生日時：".date("Y-m-d H:i:s")."<br>エーラー:";
                    if($err_q2){
                        $description.="<br> + ".$err_q2;
                    }
                    if($err_q3){
                        $description.="<br> + ".$err_q3;
                    }
                    if($err_qin){
                        $description.="<br> + ".$err_qin;
                    }
                    if($err_qb){
                        $description.="<br> + ".$err_qb;
                    }
                    if($err_qo){
                        $description.="<br> + ".$err_qo;
                    }
                    if($err_qf){
                        $description.="<br> + ".$err_qf;
                    }
                    
                    $reference=$user[0]["gp2"];
                
                    $json=array();
                    $json["jsonrpc"]="2.0";
                    $json["method"]="createTask";
                    $json["id"]=1;
                    $json["params"]["title"]=$title;
                    $json["params"]["project_id"]=$project_id;
                    $json["params"]["creator_id"]=$creator_id;
                    $json["params"]["column_id"]=$column_id;
                    $json["params"]["swimlane_id"]=$swimlane_id;
                    $json["params"]["owner_id"]=$owner_id;
                    $json["params"]["color_id"]=$color_id;
                    $json["params"]["description"]=$description;
                    $json["params"]["reference"]=$reference;
                    
                    $json = json_encode($json);
                    /*print "<pre>";
                    print_r($json);
                    print "</pre>";*/
                    $url="http://dev.yasu.nalux.local/kanboard/jsonrpc.php";
                    $USERNAME = "jsonrpc";
                    $PASSWORD = "4ed5acc146d91c63f60fc430241f3f1afef1dbb8d5d6b6571be924596ea2";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERPWD, $USERNAME . ":" . $PASSWORD);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    $res = curl_exec($ch);
                    curl_close($ch);
                    echo json_encode($err_q2."<br>".$err_q3);
                }
            }else{
                echo json_encode(["NG"]);
            }
            return sfView::NONE;
        }

        $this->setlayout("none");
    }

    public function executeItemCompleteProcess(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();

        if (empty($_SERVER['HTTPS'])) {
            header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            exit;
        }
        $this->getResponse()->setTitle('完成品処理｜Nalux');
        // $this->setlayout("none");

        if($request->getParameter("ac")=="getCheckedData"){
            //$q="SELECT *,GROUP_CONCAT(hgpd_id) as hgpd_ids FROM hgpd_report WHERE hgpd_rfid = '".$request->getParameter('rfid')."' GROUP BY  hgpd_itemcode, hgpd_rfid, hgpd_process, hgpd_moldlot, hgpd_moldday, hgpd_itemform, hgpd_cav ORDER BY hgpd_id DESC ";
            $q="SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$request->getParameter('rfid')."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $last_rfid = $st->fetchall(PDO::FETCH_ASSOC);
            if(count($last_rfid)==0){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["RFIDが未登録です。"]);
                exit;
            }
            $last_ids="";
            foreach($last_rfid as $lk=>$lv){
                if($lk == 0){
                    $last_proc=$lv["hgpd_process"];
                    $last_ids.=$lv["hgpd_id"].",";
                }elseif($last_proc==$lv["hgpd_process"]){
                    $last_ids.=$lv["hgpd_id"].",";
                }else{
                    break;
                }
            }
            $last_ids=substr($last_ids,0,-1);
      
            if($request->getParameter("bom_mode")=="on"){
                //マスター情報収得
                $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$last_rfid[0]["hgpd_itemcode"]."' ";
                $st = $con->execute($q);
                $ms_data = $st->fetch(PDO::FETCH_ASSOC);

                //完成品のbom
                $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$last_rfid[0]["hgpd_itemcode"]."' AND end_code = '0' ";
                $st = $con->execute($q);
                $bom_data = $st->fetch(PDO::FETCH_ASSOC);

                $before_end_code=substr($bom_data["befor_code"],strlen($bom_data["code"]),2);

                if($before_end_code==""){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["工程別マスターを確認してください。"]);
                    exit;
                }

                //RFID在庫の数チェック
                // $q = "SELECT wic_hgpd_id, SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv FROM work_inventory_control 
                // WHERE wic_rfid = '".$request->getParameter('rfid')."' AND wic_complete_flag = '0' AND wic_remark <> '員数不足' 
                // GROUP BY wic_hgpd_id 
                // ORDER BY wic_id DESC, wic_hgpd_id DESC ";
                // $st = $con->execute($q);
                // $check_num = $st->fetchall(PDO::FETCH_ASSOC);

                // print_r($q);
                // exit;

                $q = "SELECT *, SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv FROM hgpd_report hr
                LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id 
                LEFT JOIN ms_molditem mi ON wic.wic_itemcode = mi.itempprocord 
                WHERE wic_rfid = '".$request->getParameter('rfid')."' 
                AND hr.hgpd_process = '".$last_proc."' 
                AND wic_hgpd_id IN (".$last_ids.") ";
                if($ms_data["id_issued_not_checked"] == '0'){
                    $q.="AND wic_process_key NOT IN ('M','J','H') ";
                }
                $q.="AND wic_complete_flag = '0' AND wic_remark <> '員数不足' AND hr.hgpd_del_flg = '0' AND wic_del_flg = '0' ";
                $q.="GROUP BY wic_hgpd_id 
                ORDER BY wic_id DESC, wic_hgpd_id DESC ";
                $st = $con->execute($q);
                $checked_list = $st->fetchall(PDO::FETCH_ASSOC);
       
                $res_list=[];$check_hgpd_id=[];$sum_inv=0;
                foreach($checked_list as $key=>$value){
                    $sum_inv += intval($value["sum_inv"]);
                    if(!in_array($value["hgpd_id"],$check_hgpd_id)){
                        $check_hgpd_id[]=$value["hgpd_id"];
                        $sum_value = $value;
                        $sum_value["wic_qty_in"]=$value["sum_inv"];
                        if($value["sum_inv"]>0){
                            $res_list[]=$sum_value;
                        }
                    }
                }
                // header("Content-Type: application/json; charset=utf-8");
                // echo json_encode(["OK",$res_list]);
                // exit;
                header("Content-Type: application/json; charset=utf-8");
                if(count($checked_list)==0){
                    echo json_encode(["前の工程のデータがありません。"]);
                }else{
                    if(($sum_inv)==0){
                        echo json_encode(["<span style='color:darkorange'>再発行待ち</span>です。"]);
                    }elseif($sum_inv > 0){
                        echo json_encode(["OK",$res_list]);
                    }else{
                        echo json_encode(["NG","員数不合！！！"]);
                    }
                }
                exit;
            }else{
                //BOM利用しない
                $q = "SELECT *, SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv FROM hgpd_report hr
                LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id 
                LEFT JOIN ms_molditem mi ON wic.wic_itemcode = mi.itempprocord 
                WHERE wic_rfid = '".$request->getParameter('rfid')."' AND wic_process = '".$last_proc."' AND wic_hgpd_id IN (".$last_ids.") AND wic_process_key <> 'M' AND wic_complete_flag = '0' AND wic_remark NOT IN ('員数不足') AND hr.hgpd_del_flg = '0' AND wic_del_flg = '0' 
                GROUP BY wic_hgpd_id 
                ORDER BY wic_id DESC, wic_hgpd_id DESC ";
                $st = $con->execute($q);
                $checked_list = $st->fetchall(PDO::FETCH_ASSOC);

                $res_list=[];$check_hgpd_id=[];$sum_inv=0;
                foreach($checked_list as $key=>$value){
                    $sum_inv += intval($value["sum_inv"]);
                    if(!in_array($value["hgpd_id"],$check_hgpd_id)){
                        $check_hgpd_id[]=$value["hgpd_id"];
                        $sum_value = $value;
                        $sum_value["wic_qty_in"]=$value["sum_inv"];
                        if($value["sum_inv"]>0){
                            $res_list[]=$sum_value;
                        }
                    }
                }

                header("Content-Type: application/json; charset=utf-8");
                if(count($checked_list)==0){
                    echo json_encode(["前の工程のデータがありません。"]);
                }else{
                    if(($sum_inv)==0){
                        echo json_encode(["<span style='color:darkorange'>再発行待ち</span>です。"]);
                    }else{
                        echo json_encode(["OK",$res_list]);
                    }
                }
                exit;
            }
        }

        if($request->getParameter("ac")=="checkBomInfo"){
            $q="SELECT *,GROUP_CONCAT(hgpd_id) as hgpd_ids FROM hgpd_report WHERE hgpd_rfid = '".$request->getParameter('rfid')."' AND hgpd_del_flg = '0' GROUP BY  hgpd_itemcode, hgpd_rfid, hgpd_process, hgpd_moldlot, hgpd_itemform, hgpd_cav  ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $last_rfid = $st->fetch(PDO::FETCH_ASSOC);

            //完成品のbom
            $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$last_rfid["hgpd_itemcode"]."' AND end_code = '0' ";
            $st = $con->execute($q);
            $bom_data = $st->fetch(PDO::FETCH_ASSOC);
            
            header("Content-Type: application/json; charset=utf-8");
            if($last_rfid["hgpd_itemcode"]){
                if(!$bom_data){
                    echo json_encode("BOM連携が見つかりません。工程別マスターを確認してください。");
                }elseif($bom_data["end_code"]=="" || $bom_data["place_info"]==""){
                    echo json_encode("工程別マスター情報の齟齬を確認してください。");
                }else{
                    echo json_encode("OK");
                }
            }else{
                echo json_encode("RFIDは未登録です。");
            }
      
            exit;
        }

        if($request->getParameter("ac")=="entryData"){
            $out_data = $request->getParameter("out_data");
            $in_data = $request->getParameter("in_data");
            $username = $request->getParameter("user");
            $printer_ip = $request->getParameter("printer_ip");
            $plant_name = $request->getParameter("plant");
            $start_time = $request->getParameter("start_time");
            if($start_time==""){
                $start_time = date("Y-m-d H:i:s");
            }

            $created_at = date("Y-m-d H:i:s");

            $work_time = strtotime($created_at) - strtotime($start_time);
            
            $plant_id="1000079";
            if($plant_name==""){
                $plant_name="野洲工場";
            }elseif($plant_name=="山崎工場"){
                $plant_id="1000073";
            }

            $q = "SELECT * FROM ms_person WHERE user = '".$username."'";
            $st = $con->execute($q);
            $user = $st->fetch(PDO::FETCH_ASSOC);

            foreach($out_data as $ok=>$ov){
                if($ov["hgpd_id"]!=""){
                    $xls_data=$ov;
                }
            }
        
            $xls_num=0;
            foreach($in_data as $ik=>$iv){
                $brl = explode(",",$iv["proc_ids"]);
                foreach($brl as $bk=>$bv){
                    $sp_v = explode("=>",$bv);
                    $xls_num+=intval($sp_v[1]);
                }
            }

            //集計XLS実績登録
            $qxls= "INSERT INTO xls_work_report( ";
            $qxls.="date, itemcord, itemname, xlsnum, workkind, ";                          //5
            $qxls.="workitem, usercord, username, usergp1, usergp2, ";                      //10
            $qxls.="moldplaceid, moldplace, itemform, moldlot, moldmachine, ";              //15
            $qxls.="totalnum, badnum, goodnum,totaltime, gootrate, hour,";                            //21
            $qxls.="updating_person, del_flg, created_at, updated_at) VALUES ";             //25
            $qxls.="('".date("Y-m-d")."',";
            $qxls.="'".$xls_data["itemcode"]."',";
            $qxls.="'".$xls_data["itemname"]."',";
            $qxls.="'',";
            $qxls.="'直接作業',";                                          //5
            $qxls.= "'完成品処理',";
            $qxls.="'".$user["ppro"]."',";
            $qxls.="'".$user["user"]."',";
            $qxls.="'".$user["gp1"]."',";
            $qxls.="'".$user["gp2"]."',";                              //10
            $qxls.="'".$plant_id."',";
            $qxls.="'".$plant_name."',";
            $qxls.="'".$xls_data["itemform"]."',";
            $qxls.="'".$xls_data["moldlot"]."',";
            $qxls.="'',";                                               //15
            $qxls.="'".$xls_num."',";
            $qxls.="'0',";
            $qxls.="'".$xls_num."',";
            $qxls.="'".($work_time/60)."',";
            $qxls.="'100',";
            $qxls.="'0',";                                              //21
            $qxls.="'タブレット端末',";
            $qxls.="'0',"; // del flg is OFF
            $qxls.="'".$created_at."',";
            $qxls.="'".$created_at."')";
            // $con->execute($qxls);

            $qs="SELECT id FROM xls_work_report ORDER BY id DESC ";
            // $st = $con->execute($qs);
            // $xls_id = $st->fetch(PDO::FETCH_ASSOC);

            $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$out_data[0]["itemcode"]."' ";
            $st = $con->execute($q);
            $ms_mold = $st->fetch(PDO::FETCH_ASSOC);

            $print_data=[];
            foreach($in_data as $key=>$value){
                $before_rfid_list = explode(",",$value["proc_ids"]);
                $sum_this_id = 0;
                $last_device_id="";
                foreach($before_rfid_list as $k=>$v){
                    $sp_v = explode("=>",$v);
                    if($sp_v[0]!=""){
                        $last_device_id.=$sp_v[0].",";
                    }else{
                        $last_device_id.="0,";
                    }
                    $sum_this_id+=intval($sp_v[1]);
                }
                $last_device_id=substr($last_device_id,0,-1);
                $q="SELECT *,GROUP_CONCAT(DISTINCT hgpd_cav ORDER BY hgpd_cav ASC SEPARATOR '-') as cavs, GROUP_CONCAT(DISTINCT hgpd_itemform) as forms FROM hgpd_report WHERE hgpd_id IN (".$last_device_id.") AND hgpd_del_flg = '0' ";
                $st = $con->execute($q);
                $last_id_info = $st->fetch(PDO::FETCH_ASSOC);

                if($last_id_info["hgpd_id"]==""){
                    $last_id_info["hgpd_wherhose"] = $plant_name;
                    $last_id_info["hgpd_itemcode"] = $out_data[0]["itemcode"];
                    $last_id_info["forms"] = $out_data[0]["itemform"];
                }
                
                $workdate=date("Y-m-d",strtotime(date("Y-m-d H:i:s")));
                if(date("H",date("Y-m-d H:i:s"))<8){
                    $workdate=date("Y-m-d",strtotime("-1 day ".$workdate));
                }
                $wl[0] = substr(date("y",strtotime($workdate)) , -1);
                $wl[1] = date("n",strtotime($workdate));
                if($wl[1]=="10"){
                    $wl[1] ="X";
                }elseif($wl[1]=="11"){
                    $wl[1] = "Y";
                }elseif($wl[1]=="12"){
                    $wl[1] = "Z";
                }
                $wl[2] = date("d",strtotime($workdate));
                $worklot = implode("",$wl);  

                //紐づけ情報記録
                $qhre = "INSERT INTO hgpd_report (xwr_id,hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_itemform, hgpd_cav, hgpd_moldlot,
                hgpd_worklot, hgpd_checkday, hgpd_moldday, hgpd_quantity, hgpd_qtycomplete, 
                hgpd_difactive, hgpd_remaining, hgpd_namecode, hgpd_name, hgpd_rfid, 
                hgpd_start_at, hgpd_stop_at, hgpd_exclusion_time, hgpd_working_hours, hgpd_volume, created_at) ";
                $qhre.= "VALUES ";
                $qhre.= "('0',";
                $qhre.= "'".$last_id_info["hgpd_wherhose"]."',";
                $qhre.= "'完成品処理',";
                $qhre.= "'".$last_id_info["hgpd_itemcode"]."',";
                $qhre.= "'".$last_id_info["forms"]."',";
                $qhre.= "'".$last_id_info["cavs"]."',";
                $qhre.= "'".$last_id_info["hgpd_moldlot"]."',";
                $qhre.= "'".$worklot."',";
                $qhre.= "'".$workdate."',";
                $qhre.= "'".$last_id_info["hgpd_moldday"]."',";
                $qhre.= "'".$sum_this_id."',";
                $qhre.= "'".$sum_this_id."',";
                $qhre.= "'0',";
                $qhre.= "'0',";
                $qhre.= "'".$user["ppro"]."',";
                $qhre.= "'".$username."',";
                $qhre.= "'".$value["cpl_id"]."',";              //hgpd_rfid
                $qhre.= "'".$start_time."',";
                $qhre.= "'".$created_at."',";
                $qhre.= "'0',";
                $qhre.= "'".($work_time/3600)."',";
                $qhre.= "'0',";
                $qhre.= "'".$created_at."') ";
                $con->execute($qhre);

                //IDの状態の更新
                $q= "UPDATE work_id_manager SET wim_status = '使用中' WHERE rfid = '".$value["cpl_id"]."' ";
                $con->execute($q);

                $q="SELECT hgpd_id FROM hgpd_report WHERE hgpd_rfid = '".$value["cpl_id"]."' ORDER BY hgpd_id DESC ";
                $st = $con->execute($q);
                $hgpd_id = $st->fetch(PDO::FETCH_ASSOC);

                foreach($before_rfid_list as $comkey => $id_num){
                    $this_id = explode("=>",$id_num);

                    foreach($out_data as $bfkey=>$bfval){
                        if($bfval["hgpd_id"]==$this_id[0]){

                            if($bfval["hgpd_id"]!="" && $this_id[0] != "0"){
                                //出在庫の計算
                                $q="SELECT wic_inventry_num FROM work_inventory_control 
                                WHERE wic_itemcode = '".$bfval["itemcode"]."' 
                                    AND wic_process_key = '".$bfval["wic_process_key"]."' 
                                    AND wic_process = '".$bfval["process_name"]."' 
                                    AND wic_complete_flag = '0' ";
                                $q.="AND wic_del_flg = '0' ";
                                // $q.="GROUP BY wic_process_key,wic_complete_flag ";
                                $q.="ORDER BY wic_id DESC LIMIT 1 ";
                                
                                $st = $con->execute($q);
                                $out_inv_num = $st->fetch(PDO::FETCH_ASSOC);
            
                                // $sum_out_num=intval($out_inv_num["wic_inventry_num"])-intval($bfval["num"]);
                                $sum_out_num=intval($out_inv_num["wic_inventry_num"])-intval($this_id[1]);
            
                                $qo="INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_complete_flag,wic_remark,wic_created_at) VALUES ";
                                $qo.= "('".$bfval["hgpd_id"]."',";
                                $qo.= "'".$hgpd_id["hgpd_id"]."',";
                                $qo.= "'".$bfval["hgpd_id"]."',";
                                $qo.= "'".date("Y-m-d")."',";
                                $qo.= "'".$username."',";
                                $qo.= "'".$bfval["wic_wherhose"]."',";
                                $qo.= "'".$bfval["rfid"]."',";
                                $qo.= "'".$bfval["itemcode"]."',";
                                $qo.= "'".$bfval["wic_process_key"]."',";
                                $qo.= "'".$bfval["process_name"]."',";
                                $qo.= "'".$bfval["itemform"]."',";
                                $qo.= "'".$bfval["cav"]."',";
                                $qo.= "'0',";
                                $qo.= "'".$this_id[1]."',";
                                $qo.= "'".$sum_out_num."',";
                                $qo.= "'0',";
                                $qo.= "'完成品処理',";          //remark
                                $qo.= "'".$created_at."')";
                                $con->execute($qo);
            
                                if($bfval["has"]!="0"){
                                    // 2024-09-05　必要数だけで出庫なので、戻す処理をしない
                                    //入在庫の計算
                                    // $q="SELECT wic_inventry_num as inv_num FROM work_inventory_control 
                                    // WHERE wic_itemcode = '".$bfval["itemcode"]."' 
                                    //     AND wic_process_key = '".$bfval["wic_process_key"]."' 
                                    //     AND wic_process = '".$bfval["process_name"]."' 
                                    //     AND wic_complete_flag = '0' 
                                    // ORDER BY wic_id DESC LIMIT 1 ";
                                    // $st = $con->execute($q);
                                    // $in_back_inv_num = $st->fetch(PDO::FETCH_ASSOC);
            
                                    // // 残る分を戻す
                                    // $qi="INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_complete_flag,wic_remark,wic_created_at) VALUES ";
                                    // $qi.= "('".$bfval["hgpd_id"]."',";
                                    // $qi.= "'".$hgpd_id["hgpd_id"]."',";
                                    // $qi.= "'".date("Y-m-d")."',";
                                    // $qi.= "'".$username."',";
                                    // $qi.= "'".$bfval["wic_wherhose"]."',";
                                    // $qi.= "'".$bfval["rfid"]."',";
                                    // $qi.= "'".$bfval["itemcode"]."',";
                                    // $qi.= "'".$bfval["wic_process_key"]."',";
                                    // $qi.= "'".$bfval["process_name"]."',";
                                    // $qi.= "'".$bfval["itemform"]."',";
                                    // $qi.= "'".$bfval["cav"]."',";
                                    // $qi.= "'".$bfval["has"]."',";
                                    // $qi.= "'0',";
                                    // $qi.= "'".(intval($in_back_inv_num["inv_num"])+intval($bfval["has"]))."',";
                                    // $qi.= "'0',";
                                    // $qi.= "'完成品残数',";          //remark
                                    // $qi.= "'".$created_at."')";
                                    // $con->execute($qi);
                                }else{
                                    //IDの状態の更新
                                    $q= "UPDATE work_id_manager SET wim_status = '再発行' WHERE rfid = '".$bfval["rfid"]."' ";
                                    $con->execute($q);
                                }
                            }

                            //完成品のbom
                            $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$bfval["itemcode"]."' AND end_code = '0' ";
                            $st = $con->execute($q);
                            $bom_data = $st->fetch(PDO::FETCH_ASSOC);

                            //入在庫の計算
                            $q="SELECT wic_inventry_num FROM work_inventory_control 
                            WHERE wic_itemcode = '".$bfval["itemcode"]."' 
                                AND wic_process_key = '0' 
                                AND wic_process = '完成品処理' 
                                AND wic_complete_flag = '1' 
                                AND wic_del_flg = '0' 
                            ORDER BY wic_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $in_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                            //完成品登録
                            $qii="INSERT INTO work_inventory_control (wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_hgpd_id,wic_complete_id,wic_before_id,wic_complete_flag,wic_created_at) VALUES ";
                            $qii.= "('".date("Y-m-d")."',";
                            $qii.= "'".$username."',";
                            $qii.= "'".$bom_data["place_info"]."',";
                            $qii.= "'".$value["cpl_id"]."',";
                            $qii.= "'".$bfval["itemcode"]."',";
                            $qii.= "'0',";
                            $qii.= "'完成品処理',";
                            $qii.= "'".$bfval["itemform"]."',";
                            $qii.= "'".$bfval["cav"]."',";
                            $qii.= "'".$this_id[1]."',";
                            $qii.= "'0',";
                            $qii.= "'".(intval($in_inv_num["wic_inventry_num"])+intval($this_id[1]))."',";
                            if($bfval["hgpd_id"]==""){
                                $qii.= "'完成品処理ID無混合',";   //remark
                            }else{
                                $qii.= "'完成品処理',";          //remark
                            }
                            $qii.= "'".$hgpd_id["hgpd_id"]."',";
                            $qii.= "'".$hgpd_id["hgpd_id"]."',";
                            $qii.= "'".$bfval["hgpd_id"]."',";
                            $qii.= "'1',";
                            $qii.= "'".$created_at."')";
                            $con->execute($qii);

                            $q="SELECT wic_id FROM work_inventory_control ORDER BY wic_id DESC LIMIT 1 ";
                            $st = $con->execute($q);
                            $last_wic_id = $st->fetch(PDO::FETCH_ASSOC);

                            $qc = "INSERT INTO hgpd_report_sub (hgpd_complete_id,hgpd_before_id,hrs_complete_wic_id) VALUES ('".$hgpd_id["hgpd_id"]."','".$this_id[0]."','".$last_wic_id["wic_id"]."') ";
                            $con->execute($qc);

                            // 印刷データ
                            if($bfval["itemform"] != '' && strpos($itemform, $bfval["itemform"])===false){
                                $itemform=$bfval["itemform"].",";
                            }
                            if($bfval["cav"] != '' && strpos($cav, $bfval["cav"])===false){
                                $cav.=$bfval["cav"].",";
                            }       
                            if($bfval["moldlot"] != '' && strpos($moldlot, $bfval["moldlot"])===false){
                                $moldlot.=$bfval["moldlot"].",";
                            }       
                            // if(strpos($gr_id, $bfval["hgpd_id"])===false){
                            //     $gr_id=$hgpd_id["hgpd_id"];
                            // }
                            $gr_id=$hgpd_id["hgpd_id"];  
                            if($bfval["moldday"] != '' && strpos($moldday, $bfval["moldday"])===false){
                                $moldday.=$bfval["moldday"].",";
                            }

                        }
                    }
                }
                // 印刷データ
                $print_data[] = array(
                    "form"=>substr($itemform,0,-1),
                    "cav"=>substr($cav,0,-1),
                    "lot"=>substr($moldlot,0,-1),
                    "id"=>$gr_id,
                    "m_date"=>substr($moldday,0,-1),
                    "start_at"=>"",
                    "stop_at"=>"",
                    "serial_num"=>"",
                );
            }

            //プリンターのパラメータの設定
            $json_print["ac"]="print";
            $json_print["d"]["ip"]=$printer_ip;
            //4桁ロット印刷判定 ⁼ judge_p = 0(印刷品目), 1(印刷無し）
            $json_print["d"]["judge_p"]=$ms_mold["dateto4"];
            //完成かんばん、仕掛かんばん判定 : judge_card_type =1(仕掛り）＝０（完成)
            $json_print["d"]["judge_card_type"]="0";
            $json_print["d"]["kanban"]=$print_data;
            //完成かんばんの生産時間なし
            $json_print["d"]["judge_cmt_dt"]="0";
            $json_print["d"]["judge_cmt_serial"]="0";

            // $url = 'http://'.$_SERVER['SERVER_NAME'].'/RFIDReport/Printkanban';
            // $ch = curl_init($url);
            // curl_setopt($ch, CURLOPT_POST, TRUE);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($json_print));
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            // $html=curl_exec($ch);
            // print_r($html);

            echo json_encode("OK");
            exit;
        }

        //毎日８時に前日の完成品処理データの集計
        if($request->getParameter("ac")=="xlsDataEntry"){
            $curent_date = date("Y-m-d");
            $workdate=date("Y-m-d",strtotime("-1 day ".$curent_date));

            $sdate = $workdate." 08:00:00";
            $edate = $curent_date." 07:59:59";

            $q="SELECT
                hgpd_checkday as 作業日
                , hgpd_itemcode as 品目コード
                , mi.itemname as 品名
                , GROUP_CONCAT(DISTINCT hgpd_itemform) as 型番
                , GROUP_CONCAT(DISTINCT hgpd_cav) as キャビ
                , hgpd_wherhose as 工場
                , SUM(hgpd_quantity) as 数量
                , mp.ppro as user_id
                , mp.gp1 as 所属1
                , mp.gp2 as 所属2
                , hgpd_name as 作業者
                , SUM(hgpd_working_hours) as 作業時間
                , MIN(hgpd_start_at) as min_time
                , MAX(hgpd_stop_at) as max_time
                , GROUP_CONCAT(hgpd_id) as ids 
            FROM
                hgpd_report hr 
                LEFT JOIN ms_molditem mi 
                    ON mi.itempprocord = hr.hgpd_itemcode 
                LEFT JOIN ms_person mp 
                    ON mp.user = hr.hgpd_name 
            WHERE
                hgpd_process = '完成品処理' AND hr.xwr_id = '0' AND hr.hgpd_del_flg = '0' ";
            if($request->getParameter("mode")=="daily_report"){
                $q.="AND hr.created_at BETWEEN '".$sdate."' AND '".$edate."' ";
            }else{
                print_r($q);
                exit;
            }
            $q.="GROUP BY
                hgpd_wherhose
                , hgpd_checkday
                , hgpd_itemcode
                , hgpd_itemform
                , hgpd_name 
            ORDER BY
                hgpd_checkday ASC
                , hgpd_itemcode ASC
                , min_time ASC
            ";
            $st = $con->execute($q);
            $all_data = $st->fetchall(PDO::FETCH_ASSOC);

            foreach($all_data as $key=>$value){
                //集計XLS実績登録
                $qxls= "INSERT INTO xls_work_report( ";
                $qxls.="date, itemcord, itemname, xlsnum, workkind, ";                          //5
                $qxls.="workitem, usercord, username, usergp1, usergp2, ";                      //10
                $qxls.="moldplaceid, moldplace, itemform, moldlot, moldmachine, ";              //15
                $qxls.="totalnum, badnum, goodnum, totaltime, gootrate, hour,";                            //21
                $qxls.="updating_person, del_flg, xstart_time1, xend_time1, created_at, updated_at) VALUES ";             //27

                if($value["工場"]=="山崎工場"){
                    $plant_id="1000073";
                }else{
                    $plant_id="1000079";
                }
                $sum_work_time = $value["作業時間"]*60;
                if($sum_work_time<1){
                    $sum_work_time = 1;
                }

                $qxls.="('".$value["作業日"]."',";
                $qxls.="'".$value["品目コード"]."',";
                $qxls.="'".$value["品名"]."',";
                $qxls.="'',";
                $qxls.="'直接作業',";                                          //5
                $qxls.= "'完成品処理',";
                $qxls.="'".$value["user_id"]."',";
                $qxls.="'".$value["作業者"]."',";
                $qxls.="'".$value["所属1"]."',";
                $qxls.="'".$value["所属2"]."',";                              //10
                $qxls.="'".$plant_id."',";
                $qxls.="'".$value["工場"]."',";
                $qxls.="'".$value["型番"]."',";
                $qxls.="'',";
                $qxls.="'',";                                               //15
                $qxls.="'".$value["数量"]."',";
                $qxls.="'0',";
                $qxls.="'".$value["数量"]."',";
                $qxls.="'".$sum_work_time."',";
                $qxls.="'100',";
                $qxls.="'0',";                                              //20
                $qxls.="'DAS自動集計',";
                $qxls.="'0',"; // del flg is OFF
                $qxls.="'".$value["min_time"]."',";
                $qxls.="'".$value["max_time"]."',";
                $qxls.="'".date("Y-m-d H:i:s")."',";
                $qxls.="'".date("Y-m-d H:i:s")."')";
                $con->execute($qxls);

                $qs="SELECT id FROM xls_work_report ORDER BY id DESC ";
                $st = $con->execute($qs);
                $xls_id = $st->fetch(PDO::FETCH_ASSOC);

                $qu="UPDATE hgpd_report SET xwr_id = '".$xls_id["id"]."' WHERE hgpd_id IN (".$value["ids"].") ";
                $con->execute($qu);
            }
            exit;
        }

    }

    public function executeItemCompleteXls(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();

        $curent_date = date("Y-m-d");
        $workdate=date("Y-m-d",strtotime("-1 day ".$curent_date));

        $sdate = $workdate." 08:00:00";
        $edate = $curent_date." 07:59:59";

        $q="SELECT
            hgpd_checkday as 作業日
            , hgpd_itemcode as 品目コード
            , mi.itemname as 品名
            , GROUP_CONCAT(DISTINCT hgpd_itemform) as 型番
            , GROUP_CONCAT(DISTINCT hgpd_cav) as キャビ
            , hgpd_wherhose as 工場
            , SUM(hgpd_quantity) as 数量
            , mp.ppro as user_id
            , mp.gp1 as 所属1
            , mp.gp2 as 所属2
            , hgpd_name as 作業者
            , SUM(hgpd_working_hours) as 作業時間
            , MIN(hgpd_start_at) as min_time
            , MAX(hgpd_stop_at) as max_time
            , GROUP_CONCAT(hgpd_id) as ids 
        FROM
            hgpd_report hr 
            LEFT JOIN ms_molditem mi 
                ON mi.itempprocord = hr.hgpd_itemcode 
            LEFT JOIN ms_person mp 
                ON mp.user = hr.hgpd_name 
        WHERE
            hgpd_process = '完成品処理' AND hr.xwr_id = '0' AND hgpd_del_flg = '0' ";
        if($request->getParameter("mode")=="daily_report"){
            $q.="AND hr.created_at BETWEEN '".$sdate."' AND '".$edate."' ";
        }else{
            print_r($q);
            exit;
        }
        $q.="GROUP BY
            hgpd_wherhose
            , hgpd_checkday
            , hgpd_itemcode
            , hgpd_itemform
            , hgpd_name 
        ORDER BY
            hgpd_checkday ASC
            , hgpd_itemcode ASC
            , min_time ASC
        ";
        $st = $con->execute($q);
        $all_data = $st->fetchall(PDO::FETCH_ASSOC);

        foreach($all_data as $key=>$value){
            //集計XLS実績登録
            $qxls= "INSERT INTO xls_work_report( ";
            $qxls.="date, itemcord, itemname, xlsnum, workkind, ";                          //5
            $qxls.="workitem, usercord, username, usergp1, usergp2, ";                      //10
            $qxls.="moldplaceid, moldplace, itemform, moldlot, moldmachine, ";              //15
            $qxls.="totalnum, badnum, goodnum, totaltime, gootrate, hour,";                            //21
            $qxls.="updating_person, del_flg, xstart_time1, xend_time1, created_at, updated_at) VALUES ";             //27

            if($value["工場"]=="山崎工場"){
                $plant_id="1000073";
            }else{
                $plant_id="1000079";
            }
            $sum_work_time = $value["作業時間"]*60;
            if($sum_work_time<1){
                $sum_work_time = 1;
            }

            $qxls.="('".$value["作業日"]."',";
            $qxls.="'".$value["品目コード"]."',";
            $qxls.="'".$value["品名"]."',";
            $qxls.="'',";
            $qxls.="'直接作業',";                                          //5
            $qxls.= "'完成品処理',";
            $qxls.="'".$value["user_id"]."',";
            $qxls.="'".$value["作業者"]."',";
            $qxls.="'".$value["所属1"]."',";
            $qxls.="'".$value["所属2"]."',";                              //10
            $qxls.="'".$plant_id."',";
            $qxls.="'".$value["工場"]."',";
            $qxls.="'".$value["型番"]."',";
            $qxls.="'',";
            $qxls.="'',";                                               //15
            $qxls.="'".$value["数量"]."',";
            $qxls.="'0',";
            $qxls.="'".$value["数量"]."',";
            $qxls.="'".$sum_work_time."',";
            $qxls.="'100',";
            $qxls.="'0',";                                              //20
            $qxls.="'DAS自動集計',";
            $qxls.="'0',"; // del flg is OFF
            $qxls.="'".$value["min_time"]."',";
            $qxls.="'".$value["max_time"]."',";
            $qxls.="'".date("Y-m-d H:i:s")."',";
            $qxls.="'".date("Y-m-d H:i:s")."')";
            $con->execute($qxls);

            $qs="SELECT id FROM xls_work_report ORDER BY id DESC ";
            $st = $con->execute($qs);
            $xls_id = $st->fetch(PDO::FETCH_ASSOC);

            $qu="UPDATE hgpd_report SET xwr_id = '".$xls_id["id"]."' WHERE hgpd_id IN (".$value["ids"].") ";
            $con->execute($qu);
        }
        exit;

    }

    public function executeInsertCompleteLinked(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();
        $q="SELECT *, GROUP_CONCAT(wic_id) as wic_ids, GROUP_CONCAT(wic_hgpd_id) as ids, SUM(wic_qty_in) as sum 
        FROM work_inventory_control 
        WHERE wic_complete_flag = 1 AND wic_remark <> '出荷処理出庫' AND wic_remark <> '成形未検出荷' AND wic_id >= '19000' AND wic_id < '20312'
        GROUP BY wic_rfid, wic_date ORDER BY wic_id ASC ";
        $st=$con->execute($q);
        $cpl_table = $st->fetchall(PDO::FETCH_ASSOC);
        $entry_num = 0;

        foreach($cpl_table as $key=>$value){
            $workdate=date("Y-m-d",strtotime($value["wic_date"]));
            $wl[0] = substr(date("y",strtotime($workdate)) , -1);
            $wl[1] = date("n",strtotime($workdate));
            if($wl[1]=="10"){
                $wl[1] ="X";
            }elseif($wl[1]=="11"){
                $wl[1] = "Y";
            }elseif($wl[1]=="12"){
                $wl[1] = "Z";
            }
            $wl[2] = date("d",strtotime($workdate));
            $worklot = implode("",$wl); 

            $zaiko=$value["wic_wherhose"];
            $spz=explode(",",$zaiko);
            $plant="野洲工場";
            if($spz[0]=="1000104"){
                $plant="山崎工場";
            }

            //紐づけ情報記録
            $qhre = "INSERT INTO hgpd_report (hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_itemform,hgpd_worklot,hgpd_checkday,hgpd_moldday,hgpd_quantity,hgpd_qtycomplete,hgpd_difactive,hgpd_remaining,hgpd_namecode,hgpd_name,hgpd_rfid,created_at) ";
            $qhre.= "VALUES ";
            $qhre.= "('".$plant."',";
            $qhre.= "'完成品処理',";
            $qhre.= "'".$value["wic_itemcode"]."',";
            $qhre.= "'".$value["wic_itemform"]."',";
            $qhre.= "'".$worklot."',";
            $qhre.= "'".$workdate."',";
            $qhre.= "'',";
            $qhre.= "'".$value["sum"]."',";
            $qhre.= "'".$value["sum"]."',";
            $qhre.= "'0',";
            $qhre.= "'0',";
            $qhre.= "'',";
            $qhre.= "'".$value["wic_name"]."',";
            $qhre.= "'".$value["wic_rfid"]."',";              //hgpd_rfid
            $qhre.= "'".date("Y-m-d H:i:s")."') ";
            // $con->execute($qhre);

            $q="SELECT hgpd_id FROM hgpd_report WHERE hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
            // $st = $con->execute($q);
            // $hgpd_id = $st->fetch(PDO::FETCH_ASSOC);

            $before_rfid_list = explode(",",$value["ids"]);

            foreach($before_rfid_list as $k=>$v){
                if($v!="0"){
                    $qc = "INSERT INTO hgpd_report_sub (hgpd_complete_id,hgpd_before_id) VALUES ('".$hgpd_id["hgpd_id"]."','".$v."') ";
                    $con->execute($qc);
                }
            }
            $entry_num++;
        }
        print_r($entry_num." データ登録しました。");
        exit;
    }
    public function executeInventoryControlAdm(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        if (empty($_SERVER['HTTPS'])) {
            header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            exit;
        }

        if($request->getParameter("mode")=="picking"){
            $this->getResponse()->setTitle('出荷紐づけ | Nalux');

            // $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
            // $db["user"] = 'adempiere';
            // $db["password"] = 'adempiere';
            // $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);

            // $sd =date("Y-m-d",strtotime("7 days ago",time()));
            // $ed =date("Y-m-d",strtotime("+7 days",time()));

            // // ADM出荷予定テブルから出荷リスト収得
            // $q= "SELECT documentno,movementdate,bp_value,pl_name FROM rv_inoutdetails ";
            // $q.="WHERE movementdate BETWEEN '".$sd."' AND '".$ed."' AND ad_org_id IN ('1000073','1000079') AND docstatus = 'IP' ";
            // $q.="GROUP BY documentno,movementdate,bp_value,pl_name ";
            // $q.="ORDER BY movementdate ASC ";
            // $st = $dbh->query($q);
            // $st->execute();
            // $ship_list = $st->fetchAll(PDO::FETCH_ASSOC);

            $this->ship_list=json_encode([]);
            // exit;
        }

        if($request->getParameter("ac")=="getShipping"){
            $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
            $db["user"] = 'adempiere';
            $db["password"] = 'adempiere';
            $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);
            
            // ADM出荷予定テブルから出荷リスト収得
            $q= "SELECT documentno,movementdate,bp_value,pl_name FROM rv_inoutdetails ";
            $q.="WHERE documentno = '".$request->getParameter("documentno")."' AND ad_org_id IN ('1000073','1000079') ";
            $q.="GROUP BY documentno,movementdate,bp_value,pl_name ";
            $q.="ORDER BY movementdate ASC ";
            $st = $dbh->query($q);
            $st->execute();
            $ship_list = $st->fetch(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($ship_list); 
            exit;
        }

        if($request->getParameter("mode")=="packing"){
            $this->getResponse()->setTitle('梱包作業実績入力 | Nalux');
            $this->ship_list=json_encode([]);
        }

        if($request->getParameter("ac")=="getItem"){
            $q = "SELECT * FROM hgpd_report 
            WHERE hgpd_rfid = '".$request->getParameter('rfid')."' AND hgpd_process NOT LIKE '%梱包%' AND hgpd_process <> '' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $last_id = $st->fetch(PDO::FETCH_ASSOC);
            
            if(!$last_id){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["NG","RFIDは未登録です。"]);
            }else{
                //未検査出荷製品をcheck
                $q = "SELECT * FROM ms_molditem WHERE itempprocord = '".$last_id["hgpd_itemcode"]."' ";
                $st = $con->execute($q);
                $ms_res = $st->fetch(PDO::FETCH_ASSOC);

                $qc="SELECT t1.*, mi.itemname, (CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as tag_name, 
                GROUP_CONCAT( DISTINCT t1.wic_hgpd_id ORDER BY t1.wic_hgpd_id ASC) AS hgpd_ids, GROUP_CONCAT(t1.wic_id) AS wic_ids,
                GROUP_CONCAT(DISTINCT NULLIF(t1.wic_itemcav,'') SEPARATOR '-') AS itemcavs, 
                SUM(t1.wic_qty_in) - SUM(t1.wic_qty_out) as sum_inv, SUM(t1.wic_qty_in) - SUM(t1.wic_qty_out) AS complete_num FROM 
                (SELECT DISTINCT wic.* FROM work_inventory_control wic ";
                if($ms_res["moldet_undetected_load"]=="0"){
                    $qc.="LEFT JOIN hgpd_report_sub hs ON wic.wic_hgpd_id = hs.hgpd_before_id OR wic.wic_hgpd_id = hs.hgpd_complete_id ";
                }
                $qc.="WHERE wic_complete_id = '".$last_id["hgpd_id"]."' AND wic_rfid = '".$request->getParameter('rfid')."' ";
                $qc.="AND wic_complete_flag = '1' AND wic_del_flg = '0' ";
                $qc.=") t1 
                    LEFT JOIN ms_molditem mi ON t1.wic_itemcode = mi.itempprocord 
                ";
                $st = $con->execute($qc);
                $pick_item = $st->fetch(PDO::FETCH_ASSOC);

                if($pick_item["sum_inv"]>0){
                    if($pick_item){
                        $pick_item["hgpd_complete_id"]=$last_id["hgpd_id"];
                        // if(!in_array($pick_item["wic_itemcode"],$checked_list)){
                        //     $std_digital=$this->Std_digitization(substr($pick_item["wic_itemcode"],0,4),$pick_item["wic_itemcode"],"梱包");
                        //     $pick_item["std_digital"]=$std_digital["std_full_path"];
                        // }
                        header("Content-Type: application/json; charset=utf-8");
                        echo json_encode(["OK",$pick_item]);
                    }else{
                        header("Content-Type: application/json; charset=utf-8");
                        echo json_encode(["NG","スキャンしたのRFIDは仕掛品ID状態です。出荷準備出来ない。"]);
                    }
                }elseif($checked_inv["sum_inv"]==0){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","スキャンしたのRFIDは出荷処理済です。"]);
                }else{
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","システムの例外です。担当者に連絡してください。"]); 
                }
            }
            exit;
        }

        //出荷登録
        if($request->getParameter("ac")=="PickingEntry"){
            $data=$request->getParameter("d");

            $created_at = date("Y-m-d H:i:s");

            $qa= "INSERT INTO work_inventory_control_adm (wica_documentno,wica_code,wica_delivery,wica_moving_day,wica_user,wica_created_at) ";
            $qa.="VALUES ('".$data["documentno"]."','".$data["wica_code"]."','".$data["wica_delivery"]."','".$data["movement_date"]."','".$data["user"]."','".date("Y-m-d H:i:s")."')";
            $con->execute($qa);

            $si= "SELECT wica_id FROM work_inventory_control_adm ORDER BY wica_id DESC LIMIT 1 ";
            $st = $con->execute($si);
            $res = $st->fetch(PDO::FETCH_ASSOC);

            //在庫履歴
            $qo ="INSERT INTO work_inventory_control (wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_hgpd_id,wic_complete_id,wic_before_id,wic_complete_flag,wic_created_at) VALUES ";
            //出荷リスト紐づけ
            $qr="INSERT INTO work_inventory_control_report (wicr_wica_id,wicr_report_id,wicr_rfid) VALUES ";
            
            foreach($data["list_item"] as $k => $v) {
                $gr_data[$v["itemcode"]][]=$v;
            }
            foreach($gr_data as $ke=>$val){
                //在庫数の計算
                $q="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control WHERE wic_itemcode = '".$val[0]["itemcode"]."' AND wic_process_key = '".$val[0]["wic_process_key"]."' AND wic_complete_flag = '1' ORDER BY wic_id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $inv_num = $st->fetch(PDO::FETCH_ASSOC);

                $last_inv_num = intval($inv_num["inv_num"]);

                foreach($val as $key=>$value){
                    $now_inv_num = $last_inv_num-intval($value["num"]);
                    $last_inv_num = $now_inv_num;
                    $list_rfid.="'".$value["complete_code"]."',";
                    
                    $qo.= "('".date("Y-m-d")."',";
                    $qo.= "'".$data["user"]."',";
                    $qo.= "'".$value["wic_wherhose"]."',";
                    $qo.= "'".$value["complete_code"]."',";
                    $qo.= "'".$value["itemcode"]."',";
                    $qo.= "'0',";
                    $qo.= "'完成品処理',";
                    $qo.= "'".$value["formnum"]."',";
                    $qo.= "'".$value["itemcav"]."',";
                    $qo.= "'0',";
                    $qo.= "'".$value["num"]."',";
                    $qo.= "'".$now_inv_num."',";
                    $qo.= "'出荷処理出庫',";          //remark
                    $qo.= "'".$value["hgpd_complete_id"]."',";
                    $qo.= "'".$value["hgpd_complete_id"]."',";
                    $qo.= "'".$value["hgpd_complete_id"]."',";
                    $qo.= "'1',";
                    $qo.= "'".$created_at."'),";

                    $qr.="('".$res["wica_id"]."','".$value["hgpd_complete_id"]."','".$value["complete_code"]."'),";
                    // $list_ids = explode(",",$value["hgpd_ids"]);
                    // foreach($list_ids as $k=>$v){
                    //     $qr.="('".$res["wica_id"]."','".$v."','".$value["complete_code"]."'),";
                    // }
                }
            }
            try{
                //在庫履歴登録実行
                $qo=substr($qo,0,-1);
                $con->execute($qo);
                //出荷リスト紐づけ登録実行
                $qr=substr($qr,0,-1);
                $con->execute($qr);
                //IDの状態の更新
                $list_rfid=substr($list_rfid,0,-1);
                $qs= "UPDATE work_id_manager SET wim_status = '再発行' WHERE rfid IN (".$list_rfid.") ";
                $con->execute($qs);
            }catch(Exception $e){
                $err.=$e->getMessage();
            }
            if($err){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($err);
                exit;
            }else{
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode("OK");
                exit;
            }
        }

        //梱包登録
        if($request->getParameter("ac")=="PackingEntry"){
            $data=$request->getParameter("d");

            $created_at = date("Y-m-d H:i:s");

            foreach ($data["list_item"] as $key => $value) {
                $gr_data[$value["itemcode"]][]=$value;
            }

            $q = "SELECT * FROM ms_person WHERE user = '".$data["user"]."' ";
            $st = $con->execute($q);
            $user = $st->fetch(PDO::FETCH_ASSOC);
            
            $mold_day = date("Y/m/d",strtotime($data["end_time"]));
            $mh = date("G",strtotime($data["end_time"]));
            if($mh<8){
                $mold_day = date("Y/m/d",strtotime("-1 day ",strtotime($data["end_time"])));
            }
            $wl[0] = substr(date("y",strtotime($mold_day)) , -1);
            $wl[1] = date("n",strtotime($mold_day));
            if($wl[1]=="10"){
                $wl[1] ="X";
            }elseif($wl[1]=="11"){
                $wl[1] = "Y";
            }elseif($wl[1]=="12"){
                $wl[1] = "Z";
            }
            $wl[2] = date("d",strtotime($mold_day));
            $worklot = implode("",$wl); 

            foreach($gr_data as $key=>$value){
                $num = 0;$time=0;$list_hgpd_id="";

                //BOM情報を収得
                $q="SELECT * FROM work_adempiere_item_ms WHERE code = '".$value[0]["itemcode"]."' AND proccess_name LIKE '%梱包%' ";
                $st = $con->execute($q);
                $bom = $st->fetch(PDO::FETCH_ASSOC);

                foreach ($value as $rkey => $rvalue) {
                    $num+=intval($rvalue["num"]);
                    $time+=floatval($rvalue["time"]);

                    $qr ="INSERT INTO hgpd_report (xwr_id, hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_cav, hgpd_itemform,hgpd_moldlot,hgpd_worklot,hgpd_checkday,hgpd_moldday,hgpd_quantity,hgpd_qtycomplete,";
                    $qr.= "hgpd_namecode,hgpd_name,hgpd_start_at,hgpd_stop_at,hgpd_exclusion_time,hgpd_working_hours,hgpd_volume,hgpd_cycle,hgpd_rfid,created_at) ";
                    $qr.= "VALUES ";
                    $qr.= "('',";
                    $qr.= "'".$data["plant_name"]."',";
                    if($bom){
                        $qr.= "'".$bom["proccess_name"]."',";
                    }else{
                        $qr.= "'出荷・在庫:梱包',";
                    }
                    $qr.= "'".$rvalue["itemcode"]."',";
                    $qr.= "'".$rvalue["itemcav"]."',";
                    $qr.= "'".$rvalue["formnum"]."',";
                    $qr.= "'',";
                    $qr.= "'".$worklot."',";
                    $qr.= "'".date("Y-m-d H:i:s")."',";
                    $qr.= "'',";
                    $qr.= "'".$rvalue["num"]."',";
                    $qr.= "'".$rvalue["num"]."',";
                    $qr.= "'".$user["ppro"]."',";
                    $qr.= "'".$user["user"]."',";
                    $qr.= "'".$data["start_time"]."',";
                    $qr.= "'".$data["end_time"]."',";
                    $qr.= "'',";
                    $qr.= "'".(intval($rvalue["time"])/60)."',";
                    $qr.= "'".(intval($rvalue["num"])/(intval($rvalue["time"])/60))."',";
                    $qr.= "'".((intval($rvalue["time"])*60)/intval($rvalue["num"]))."',";
                    $qr.= "'".$rvalue["complete_code"]."',";                  //hgpd_rfid
                    $qr.= "'".$created_at."') ";
                    $con->execute($qr);

                    $qs="SELECT hgpd_id FROM hgpd_report WHERE hgpd_rfid = '".$rvalue["complete_code"]."' ORDER BY hgpd_id DESC LIMIT 1 ";
                    $st = $con->execute($qs);
                    $hgpd_id = $st->fetch(PDO::FETCH_ASSOC);
                    
                    $list_hgpd_id.= $hgpd_id["hgpd_id"].",";
                }
                $q= "INSERT INTO xls_work_report( ";
                $q.="date, itemcord, itemname, xlsnum, workkind, ";                         //5
                $q.="workitem, usercord, username, usergp1, usergp2, ";                     //10
                $q.="moldplaceid, moldplace, itemform, totalnum, badnum, goodnum, ";                  //15
                $q.="totaltime, remark, cycle, gootrate, hour, ";                           //20
                $q.="updating_person, del_flg, xstart_time1, xend_time1, created_at, ";     //25
                $q.="updated_at) VALUES ";
                $q.="('".date("Y-m-d")."',";
                $q.="'".$value[0]["itemcode"]."',";
                $q.="'".$value[0]["itemname"]."',";
                $q.="'36',";
                $q.="'直接作業',";                                          //5
                if($bom){
                    $q.= "'".$bom["proccess_name"]."',";
                }else{
                    $q.= "'出荷・在庫:梱包',";
                }
                $q.="'".$user["ppro"]."',";
                $q.="'".$user["user"]."',";
                $q.="'".$user["gp1"]."',";
                $q.="'".$user["gp2"]."',";                              //10
                $q.="'".$data["plant_id"]."',";
                $q.="'".$data["plant_name"]."',";
                $q.="'".$value[0]["formnum"]."',";
                $q.="'".$num."',";
                $q.="'0',";
                $q.="'".$num."',";                             //15
                $q.="'".$time."',";
                $q.="'',";
                $q.="'".(($time)*60)/intval($num)."',";
                $q.="'100',";
                $q.="'".(intval($num) / ($time/60))."',";     //20
                $q.="'タブレット端末',";
                $q.="'0',"; // del flg is OFF
                $q.="'".$data["start_time"]."',";
                $q.="'".$data["end_time"]."',";
                $q.="'".$created_at."',";                       //25
                $q.="'".$created_at."')";
                $con->execute($q);

                $qs="SELECT id FROM xls_work_report ORDER BY id DESC LIMIT 1 ";
                $st = $con->execute($qs);
                $xls_id = $st->fetch(PDO::FETCH_ASSOC);

                //レアルタイム実績IDとwlsIDの連携
                $list_hgpd_id=substr($list_hgpd_id,0,-1);
                $qu="UPDATE hgpd_report SET xwr_id = '".$xls_id["id"]."' WHERE hgpd_id IN (".$list_hgpd_id.") ";
                $con->execute($qu); 
            }

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            exit;
        }
        
    }
    
    public function executeRfidCheckStatus(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();
        
        header("Content-Type: application/json; charset=utf-8");

        if($request->getParameter("rfid")==""){
            echo json_encode([false,"RFIDをスキャンしてください。"]);
            exit;
        }else{
            //24.10.11　ID管理台帳でタグをチェック機能　NQV追加
            if(strlen($request->getParameter("rfid"))=="32" && $request->getParameter("item")!=""){
                $qc="SELECT * FROM work_id_manager WHERE rfid = '".$request->getParameter("rfid")."' ";
                $st = $con->execute($qc);
                $tag_check = $st->fetch(PDO::FETCH_ASSOC);
                
                if(!$tag_check){
                    echo json_encode([false,"RFIDが未登録です。"]);
                    exit;
                }elseif($tag_check && $tag_check["wim_itemcode"]!=$request->getParameter("item")){
                    echo json_encode([false,"製品が違います。"]);
                    exit;
                }
            }

            if($request->getParameter("id_type")=="completeId"){
                //完成RFID利用状態を確認
                if($tag_check && $tag_check["wim_class"]!="完成ID"){
                    echo json_encode([false,"仕掛IDと連携が出来ません。"]);
                    exit;
                }
                $q ="SELECT SUM(wic_qty_in) as sum_in, SUM(wic_qty_out) as sum_out FROM work_inventory_control 
                WHERE wic_rfid='".$request->getParameter("rfid")."' AND wic_rfid <> '' AND wic_rfid IS NOT NULL AND wic_del_flg = '0' ";
                $q.="GROUP BY wic_rfid,wic_itemcode,wic_hgpd_id ORDER BY wic_created_at DESC,wic_id DESC ";
            }else{
                //仕掛RFID利用状態を確認
                if(strlen($request->getParameter("rfid"))=="32" && $request->getParameter("item")!=""){
                    $q ="SELECT SUM(wic_qty_in) as sum_in, SUM(wic_qty_out) as sum_out FROM work_inventory_control 
                    WHERE wic_rfid='".$request->getParameter("rfid")."' AND wic_rfid <> '' AND wic_rfid IS NOT NULL AND wic_remark NOT IN ('員数不足') AND wic_del_flg = '0' ";
                    $q.="GROUP BY wic_rfid,wic_itemcode ORDER BY wic_created_at DESC,wic_id DESC ";
                }else{
                    $q ="SELECT SUM(wic_qty_in) as sum_in, SUM(wic_qty_out) as sum_out FROM work_inventory_control 
                    WHERE wic_rfid='".$request->getParameter("rfid")."' AND wic_rfid <> '' AND wic_rfid IS NOT NULL AND wic_remark NOT IN ('員数不足') AND wic_del_flg = '0' ";
                    $q.="GROUP BY wic_rfid,wic_itemcode,wic_itemform,wic_itemcav ORDER BY wic_created_at DESC,wic_id DESC ";
                }
            }

            $st = $con->execute($q);
            $lot_rfid = $st->fetch(PDO::FETCH_ASSOC);

            if($lot_rfid["sum_in"]){
                if((intval($lot_rfid["sum_in"])-intval($lot_rfid["sum_out"]))==0){
                    //在庫で残ってない場合 再利用できる
                    echo json_encode(true);
                }else{
                    echo json_encode([false,"RFIDが使用中です。"]);
                }
            }else{
                echo json_encode(true);
            }
            exit;
        }
    }

    public function executeShippingListSearch(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('出荷リストから検索 | Nalux');

        if($request->getParameter("ac")=="getCustomer"){
            $customer_code = $request->getParameter("customer");
            $moving_day =  $request->getParameter("moving_day");
            $wica_documentno = $request->getParameter("wica_documentno");
            if($wica_documentno==""){
                $wica_documentno='%';
            }

            $q= "SELECT *, GROUP_CONCAT(DISTINCT wica_id) as wica_ids, GROUP_CONCAT(DISTINCT wicr_id) as wicr_ids 
            FROM work_inventory_control_adm wa 
            LEFT JOIN work_inventory_control_report wr ON wr.wicr_wica_id = wa.wica_id 
            LEFT JOIN hgpd_report hr ON wr.wicr_report_id = hr.hgpd_id ";
            // LEFT JOIN work_inventory_control wc ON wr.wicr_report_id = wc.wic_hgpd_id AND wr.wicr_rfid = wc.wic_rfid 
            $q.="WHERE wica_code LIKE '%".$customer_code."%' AND wica_moving_day = '".$moving_day."' AND wica_documentno LIKE '%".$wica_documentno."%' AND wicr_id IS NOT NULL AND hgpd_id IS NOT NULL AND hgpd_del_flg = '0' 
            GROUP BY wica_documentno, wica_code, hgpd_itemcode ORDER BY wicr_id ASC ";
            // $q= "SELECT * FROM work_inventory_control_adm sl LEFT JOIN work_inventory_control_report ir ON sl.wica_id = ir.wicr_wica_id WHERE wica_code LIKE '%".$customer_code."%' ORDER BY wica_id DESC ";
            // $q= "SELECT * FROM work_inventory_control_adm sl LEFT JOIN work_inventory_control_report ir ON sl.wica_id = ir.wicr_wica_id WHERE wica_code LIKE 'ISE5' ORDER BY wica_id DESC ";
            $st = $con->execute($q);
            $customer_list = $st->fetchall(PDO::FETCH_ASSOC);
    
            // echo "<pre>";
            // print_r($customer_list);
            // exit;
            header("Content-Type: application/json; charset=utf-8");
            if(count($customer_list)==0){
                echo json_encode("NG");
            }else{
                echo json_encode($customer_list);
            }
            exit;
        }


        if($request->getParameter("ac")=="getCompleteItem"){
            $ship_id = $request->getParameter("ship_id");
        
            $q= "SELECT DISTINCT wr.*, GROUP_CONCAT(DISTINCT wicr_report_id) as rtl_id, SUM(wic_qty_out) as snum, ms.tray_num, ms.tray_stok,ms.fpr_num, ms.moldet_undetected_load 
            FROM work_inventory_control_report wr 
            LEFT JOIN work_inventory_control wc ON wc.wic_hgpd_id = wr.wicr_report_id 
            LEFT JOIN ms_molditem ms ON wc.wic_itemcode = ms.itempprocord 
            WHERE wicr_id IN(".$ship_id.") AND wic_complete_flag = '1' GROUP BY wicr_rfid ORDER BY wicr_id ASC ";
            $st = $con->execute($q);
            $complete_list = $st->fetchall(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($complete_list);
            exit;
        }

        if($request->getParameter("ac")=="getInfoData"){
            $last_id = $request->getParameter("last_id");
            $rfid = $request->getParameter("rfid");
            $moldet_undetected_load = $request->getParameter("moldet_undetected_load");

            if($moldet_undetected_load=="0"){
                $q="SELECT * FROM hgpd_report_sub hs 
                LEFT JOIN hgpd_report hr ON hs.hgpd_complete_id = hr.hgpd_id
                LEFT JOIN work_inventory_control wic ON wic.wic_id = hs.hrs_complete_wic_id 
                LEFT JOIN ms_molditem mi ON hr.hgpd_itemcode = itempprocord 
                WHERE hs.hgpd_complete_id IN (".$last_id.") AND wic_rfid = '".$rfid."' AND hr.hgpd_del_flg = '0' ";
            }else{
                $q="SELECT * FROM hgpd_report hr 
                LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id AND wic.wic_date = hr.hgpd_checkday 
                LEFT JOIN ms_molditem mi ON wic_itemcode = itempprocord 
                WHERE hr.hgpd_id IN (".$last_id.") AND wic_rfid = '".$rfid."' AND wic_complete_flag = '1' AND hr.hgpd_del_flg = '0' ";
            }
            $st = $con->execute($q);
            $list_rfid = $st->fetchall(PDO::FETCH_ASSOC);
            $json["cpl_process"]=$list_rfid;

            if($list_rfid){
                foreach($list_rfid as $key=>$value){
                    //連携情報収得
                    $before_id = $value["hgpd_before_id"];
                    if($moldet_undetected_load=="1"){
                        $before_id = $value["wic_hgpd_id"];
                    }
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                    (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$before_id.") 
                    UNION ALL 
                    SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
     
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_sub["all_ids"]!=""){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $before_id;
                    }
     
                    $q= "SELECT hr.*, xwr.moldmachine, GROUP_CONCAT(CONCAT(hd.hgpdd_ditem, '=>',hd.hgpdd_qty)) as hgpd_def, hr.hgpd_id as hgpd_id 
                    FROM hgpd_report hr 
                        LEFT JOIN xls_work_report xwr ON hr.xwr_id = xwr.id 
                        LEFT JOIN hgpd_report_defectiveitem hd ON hr.hgpd_id = hd.hgpd_id 
                    WHERE hr.hgpd_id IN(".$list_ids.") AND hr.hgpd_del_flg = '0' 
                    GROUP BY hr.hgpd_id 
                    ORDER BY hr.hgpd_id ASC "; 
                    // print_r($q);
                    // exit;
                    $st = $con->execute($q);
                    $before_list = $st->fetchall(PDO::FETCH_ASSOC);
                    foreach($before_list as $kh => $kv){
                        if(strpos($kv["hgpd_process"],"成形")!==false){
                            $q="SELECT GROUP_CONCAT(DISTINCT id) as ids FROM manege_stopsmall WHERE stop_mno = '".$kv["moldmachine"]."' AND stop_sdate >= '".$kv["hgpd_start_at"]."' AND stop_redate <= '".$kv["hgpd_stop_at"]."' ";
                            $st=$con->execute($q);
                            $stopsmall = $st->fetch(PDO::FETCH_ASSOC);
                            if(count($stopsmall)>0){
                                $before_list[$kh]["stopsmall"]=$stopsmall["ids"];
                            }
                        }
                        $work_second = strtotime($kv["hgpd_stop_at"])-strtotime($kv["hgpd_start_at"]);
                        $before_list[$kh]["work_time"]=round($work_second/3600)."時".round(($work_second%3600)/60)."分";
                    }
                    $json["device"][$value["wic_id"]]=$before_list;
                }
            }else{
                $json["cpl_process"]=[];
                $json["device"]=[];
            }

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($json);
            exit;
        }
    }

    public function executeInventoryFix(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        // レアルタイムの出来高修正
        // $q="UPDATE hgpd_report SET hgpd_volume = hgpd_quantity/hgpd_working_hours ";

        if($request->getParameter("ac")=="update_all"){
            // $q="UPDATE work_inventory_control main_table 
            //     INNER JOIN 
            //         (SELECT 
            //             t0.wic_id , 
            //             (SELECT SUM(tm.wic_qty_in) - SUM(tm.wic_qty_out) as inv_num 
            //             FROM work_inventory_control tm 
            //             WHERE tm.wic_itemcode = t0.wic_itemcode AND tm.wic_process = t0.wic_process AND tm.wic_process_key = t0.wic_process_key AND tm.wic_complete_flag = t0.wic_complete_flag AND tm.wic_id <= t0.wic_id 
            //             GROUP BY tm.wic_process_key , tm.wic_complete_flag ) as sum_inv 
            //         FROM work_inventory_control t0) calc_table 
            //     ON main_table.wic_id = calc_table.wic_id 
            // SET main_table.wic_inventry_num = calc_table.sum_inv ";
            // $con->execute($q);

            $q="SELECT
                wic_id
                , wic_itemcode
                , wic_process_key
                , wic_process
                , wic_qty_in
                , wic_qty_out
                , wic_inventry_num
                , wic_complete_flag 
            FROM
                work_inventory_control 
            WHERE 
                wic_name <> '山崎TEST' 
                AND wic_name <> '野洲TEST' 
                AND wic_del_flg <> '1' 
            GROUP BY
                wic_itemcode
                , wic_process_key
                , wic_complete_flag 
            ORDER BY
                wic_itemcode ASC";
            $st=$con->execute($q);
            $gr_item = $st->fetchall(PDO::FETCH_ASSOC);

            foreach($gr_item as $gk=>$gv){
                $q="SELECT
                    wic_id
                    , wic_process
                    , wic_process_key
                    , wic_qty_in
                    , wic_qty_out
                    , wic_inventry_num 
                FROM
                    work_inventory_control
                WHERE
                    wic_complete_flag = '".$gv["wic_complete_flag"]."' 
                    AND wic_itemcode = '".$gv["wic_itemcode"]."' 
                    AND wic_process_key = '".$gv["wic_process_key"]."' 
                    AND wic_name <> '山崎TEST' 
                    AND wic_name <> '野洲TEST' 
                    AND wic_remark <> '員数不足​' 
                    AND wic_del_flg <> '1' 
                ORDER BY
                    wic_id ASC";
                $st = $con->execute($q);
                $list = $st->fetchall(PDO::FETCH_ASSOC);

                $update = "";
                $num=0;
                $no = 0;
                foreach($list as $key=>$value){
                    // if($value["wic_id"]>=$request->getParameter("wicid")){
                        if($key==0){
                            $num=$value["wic_qty_in"]-$value["wic_qty_out"];
                        }else{
                            if($no==0){
                                $num = $list[$key-1]["wic_inventry_num"]+$value["wic_qty_in"]-$value["wic_qty_out"];
                            }else{
                                $num = $num+$value["wic_qty_in"]-$value["wic_qty_out"];
                            }
                        }
                        $update.="('".$value["wic_id"]."','".$num."'),";
                        $no++;
                    // }
                }
                $update=substr($update,0,-1);

                //一時テーブルの作成
                $q = "DROP TABLE IF EXISTS temp1 ";
                $con->execute($q);

                $q="CREATE TEMPORARY TABLE temp1 (wic_id BIGINT(20), inventry_num BIGINT(20));";
                $std = $con->execute($q);

                $q = "INSERT INTO temp1 (wic_id,inventry_num) VALUES ".$update." ";
                $con->execute($q);

                $q="UPDATE work_inventory_control main_table 
                    INNER JOIN temp1 t1
                    ON main_table.wic_id = t1.wic_id
                    SET main_table.wic_inventry_num = t1.inventry_num ";
                $con->execute($q);
                $q="DROP TEMPORARY TABLE temp1";
                $con->exec($q);
                print_r("Updated：".$update);
                echo "<br>";
            }

            print_r("Updated All");
            exit;
        }

        if($request->getParameter("ac")=="update_item"){
            //品目コード、末番、管理IDが必要
            $q="SELECT
                wic_id
                , wic_itemcode
                , wic_process_key
                , wic_process
                , wic_qty_in
                , wic_qty_out
                , wic_inventry_num
                , wic_complete_flag 
            FROM
                work_inventory_control 
            WHERE 
                wic_itemcode = '".$request->getParameter("itemcode")."' 
                AND wic_del_flg <> '1' 
            GROUP BY
                wic_process_key
                , wic_complete_flag 
            ORDER BY
                wic_itemcode ASC";
            $st=$con->execute($q);
            $gr_process = $st->fetchall(PDO::FETCH_ASSOC);

            foreach($gr_process as $gk=>$gv){
                $q="SELECT
                    wic_id
                    , wic_process
                    , wic_process_key
                    , wic_qty_in
                    , wic_qty_out
                    , wic_inventry_num 
                FROM
                    work_inventory_control
                WHERE
                    wic_complete_flag = '".$gv["wic_complete_flag"]."' 
                    AND wic_itemcode = '".$gv["wic_itemcode"]."' 
                    AND wic_process_key = '".$gv["wic_process_key"]."' 
                    AND wic_name <> '山崎TEST' 
                    AND wic_name <> '野洲TEST' 
                    AND wic_remark <> '員数不足​' 
                    AND wic_del_flg <> '1' 
                ORDER BY
                    wic_id ASC";
                $st = $con->execute($q);
                $list = $st->fetchall(PDO::FETCH_ASSOC);

                if(count($list)==0){
                    print_r("データが0件！");
                    exit;
                }

                $update = "";
                $num=0;
                $no = 0;

                foreach($list as $key=>$value){
                    if($value["wic_id"]>=$request->getParameter("wicid")){
                        if($key==0){
                            $num=$value["wic_qty_in"]-$value["wic_qty_out"];
                        }else{
                            if($no==0){
                                $num = $list[$key-1]["wic_inventry_num"]+$value["wic_qty_in"]-$value["wic_qty_out"];
                            }else{
                                $num = $num+$value["wic_qty_in"]-$value["wic_qty_out"];
                            }
                        }
                        $update.="('".$value["wic_id"]."','".$num."'),";
                        $no++;
                    }
                }
                if($no==0){
                    continue;
                }
                $update=substr($update,0,-1);

                //一時テーブルの作成
                $q = "DROP TABLE IF EXISTS temp1 ";
                $con->execute($q);

                $q="CREATE TEMPORARY TABLE temp1 (wic_id BIGINT(20), inventry_num BIGINT(20));";
                $std = $con->execute($q);

                $q = "INSERT INTO temp1 (wic_id,inventry_num) VALUES ".$update." ";
                $con->execute($q);

                $q="UPDATE work_inventory_control main_table 
                    INNER JOIN temp1 t1
                    ON main_table.wic_id = t1.wic_id
                    SET main_table.wic_inventry_num = t1.inventry_num ";
                $con->execute($q);
                $q="DROP TEMPORARY TABLE temp1";
                $con->exec($q);
                print_r("Updated：".$update);
                echo "<br>";
            }
            print_r("END");
            exit;  
        }
    }

    public function InOutInventoryController($entryData,$in_out_flag){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        // entryData = [plant_name,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_remark,wic_hgpd_id,wic_complete_flag]
        // in_out_flag : "in" || "out";

        //在庫の計算
        $q="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control 
        WHERE wic_itemcode = '".$entryData["wic_itemcode"]."' AND wic_process_key = '".$entryData["wic_process_key"]."' AND wic_complete_flag = '".$entryData["wic_complete_flag"]."' AND wic_remark <> '員数不足' 
        ORDER BY wic_id DESC LIMIT 1 ";
        $st = $con->execute($q);
        $sum_inv_num = $st->fetch(PDO::FETCH_ASSOC);

        //完成品登録
        $qwic="INSERT INTO work_inventory_control (wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_hgpd_id,wic_complete_id,wic_before_id,wic_complete_flag,wic_created_at) VALUES ";
        $qwic.= "('".date("Y-m-d")."',";
        $qwic.= "'".$entryData["wic_name"]."',";
        $qwic.= "'".$entryData["wic_wherhose"]."',";
        $qwic.= "'".$entryData["wic_rfid"]."',";
        $qwic.= "'".$entryData["wic_itemcode"]."',";
        $qwic.= "'".$entryData["wic_process_key"]."',";
        $qwic.= "'".$entryData["wic_process"]."',";
        $qwic.= "'".$entryData["wic_itemform"]."',";
        $qwic.= "'".$entryData["wic_itemcav"]."',";
        $qwic.= "'".$entryData["wic_qty_in"]."',";
        $qwic.= "'".$entryData["wic_qty_out"]."',";
        if($in_out_flag=="in"){
            $qwic.= "'".(intval($sum_inv_num["inv_num"])+intval($entryData["wic_qty_in"]))."',";
        }else{
            $qwic.= "'".(intval($sum_inv_num["inv_num"])-intval($entryData["wic_qty_out"]))."',";
        }
        $qwic.= "'".$entryData["wic_remark"]."',";          //remark
        $qwic.= "'".$entryData["wic_hgpd_id"]."',";
        $qwic.= "'".$entryData["wic_hgpd_id"]."',";
        $qwic.= "'".$entryData["wic_hgpd_id"]."',";
        $qwic.= "'".$entryData["wic_complete_flag"]."',";
        $qwic.= "'".date("Y-m-d H:i:s")."')";
        $con->execute($qwic);
        return;
    }

    // QRコードを利用して最新情報をリスト化し棚卸に利用する Add 23.11.28 Arima
    public function executeQRInventory(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();
        $this->getResponse()->setTitle('QR棚卸 | Nalux');

        $ac = $request->getParameter("ac");
        if($ac=="getRFID"){
            // 受け取ったRFIDから最新の情報を取得
            $comp_list=array();
            $array = array( '　', "\r\n", "\r", "\n", "\t" );
            $html=array();
            foreach($request->getParameter("rfid_value") as $rfid){
                // 仕掛品の検索
                $q = "SELECT *,
                (CASE WHEN searchtag<>'' THEN searchtag ELSE itemname END) as itemname
                FROM work_inventory_control c LEFT JOIN ms_molditem m ON c.wic_itemcode = m.itempprocord
                LEFT JOIN hgpd_report r ON c.wic_hgpd_id = hgpd_id
                WHERE wic_rfid = ? AND wic_process <> ? AND wic_qty_out = 0 AND r.hgpd_del_flg = '0' ORDER BY c.wic_created_at DESC LIMIT 1";
                $st =$con->prepare($q);
                $st->execute(array($rfid,"完成品処理"));
                if($st -> rowCount() > 0){
                    $comp_list[] = $st->fetch(PDO::FETCH_ASSOC);
                }

                // 完成品の検索
                $q = "SELECT *,SUM(wic_qty_in) as wic_qty_in ,
                (CASE WHEN searchtag<>'' THEN searchtag ELSE itemname END) as itemname
                FROM work_inventory_control c LEFT JOIN ms_molditem m ON c.wic_itemcode = m.itempprocord
                LEFT JOIN hgpd_report r ON c.wic_hgpd_id = hgpd_id
                WHERE wic_rfid = ? AND wic_process = ? AND wic_qty_out = 0 AND hgpd_del_flg = '0' GROUP BY wic_rfid, wic_process, wic_created_at ";
                $st =$con->prepare($q);
                $st->execute(array($rfid,"完成品処理"));
                if($st -> rowCount() > 0){
                    $comp_list[] = $st->fetch(PDO::FETCH_ASSOC);
                }
                
            }
            
            $comp_array=array();
            foreach($comp_list as $res){
                $comp_array[$res["itemname"]][$res["wic_process"]]["list"][]=$res;
            }
            
            // 返却するHTMLテーブルを作成
            foreach($comp_array as $itemname => $item){
                foreach($item as $process => $res){
                    // $t.="<tr>";
                    $t = "<table class='type03'> 
                    <tr> 
                    <th width='60'>データID</th> 
                    <th width='200'>RFID</th> 
                    <th width='50'>作業日</th> 
                    <th width='180'>担当者</th> 
                    <th width='50'>成形日</th> 
                    <th width='120'>工程</th> 
                    <th width='30'>型番</th> 
                    <th width='50'>キャビ</th> 
                    <th width='180'>原料名</th> 
                    <th width='80'>原料ロット</th> 
                    <th width='40'>生産数</th> 
                    <th width='40'>員数</th> 
                    <th width='40'>累計</th> 
                    <th width='40'>Edit</th> 
                    </tr> <tbody>";
                
                    $sum=0;
                    foreach($res["list"] as $data){
                        $sum+= $data["wic_qty_in"];
                        $t.="<tr> <td>".$data["wic_id"]."</td>
                            <td>".$data["wic_rfid"]."</td>
                            <td>".$data["wic_date"]."</td> 
                            <td>".$data["wic_name"]."</td> 
                            <td>".$data["hgpd_moldday"]."</td> 
                            <td>".$data["wic_process"]."</td> 
                            <td>".$data["wic_itemform"]."</td> 
                            <td>".$data["wic_itemcav"]."</td> 
                            <td>".$data["hgpd_materiall"]."</td> 
                            <td>".$data["hgpd_material_lot"]."</td> 
                            <td>".$data["hgpd_quantity"]."</td> 
                            <td>".$data["wic_qty_in"]."</td>
                            <td>".$sum."</td>
                            <td><button class='del_btn' type='button' value='".$data["wic_rfid"]."' onclick='del_line(this.value);'>×</button></td></tr>";
                    }
                    $t.="<caption>品目コード:[".$data["wic_itemcode"]."], 品名: ".$itemname.", 工程: ".$process." ,合計: [$sum] 個</caption></tbody></table>";
                
                    // $t = str_replace( $array, '', $t );
                    $html[]=$t;
                }
                
            }

            // print "<pre>";
            // print_r($html);
            // print "</pre>";exit;
            
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($html);
            exit;
            return sfView::NONE;
        }

    }

    //RFID品目紐付
    public function executeRfidOrderList(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();
       
        $this->getResponse()->setTitle('ID発注台帳 | Nalux');
        
        if($request->getParameter("ac")=="getHistory"){
            $q="SELECT * FROM work_id_order_list ORDER BY wiol_id DESC LIMIT 1 ";
            $st=$con->execute($q);
            $last_item = $st->fetchall(PDO::FETCH_ASSOC);

            $json["last_item"]=$last_item;

            // $q="SELECT * FROM work_id_order_list WHERE wiol_plant = '".$request->getParameter("plant")."' ORDER BY wiol_id  ASC ";
            $q="SELECT * FROM work_id_order_list ORDER BY wiol_id DESC ";
            $st=$con->execute($q);
            $history_list = $st->fetchall(PDO::FETCH_ASSOC);

            $json["history_list"]=$history_list;
    
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($json);
            exit;
        }

        if($request->getParameter("ac")=="entryData"){
            $param = $request->getParameter("q");
            $qo="INSERT INTO work_id_order_list (wiol_o_date,wiol_start_num,wiol_o_number,wiol_plant,wiol_username,wiol_created_at) 
            VALUES (
                '".$param["work_day"]."',
                '".$param["start_num"]."',
                '".$param["add_num"]."',
                '".$param["plant"]."',
                '".$param["user"]."',
                '".date("Y-m-d H:i:s")."'
            ) ";
            try{
                $con->execute($qo);
            }catch(Exception $e){
                $err_qo=$e->getMessage();
            }
            
            if(!$err_qo){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode("OK");
            }else{
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($err_qo);
            }
            exit;
        }

    }

    //RFID品目紐付
    public function executeRfidManager(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('RFID品目紐付 | Nalux');

        $id=$request->getParameter("order_id");

        $q="SELECT * FROM work_id_order_list WHERE wiol_id = '".$id."' ";
        $st=$con->execute($q);
        $order_info = $st->fetch(PDO::FETCH_ASSOC);
        $this->order_info = $order_info;

        if($request->getParameter("ac")=="setId"){
            $param=$request->getParameter("q");

            $company="NALUX";

            //NALUX->ASCII化
            // for($i==0;$i<strlen($company);$i++){
            //     $ascii_arr[] = ord($company[$i]);
            // }
            // $company_code = implode($ascii_arr);

            //NALUX->HEX化(大文字)
            $company_code=strtoupper(bin2hex($company));

            //次の14ケタ(未確定)
            $mid_digit = "00000000000000";

            $start_num = intval($param["start_num"]);
            $end_num = intval($param["end_num"]);

            // $max_str = 32;
            $max_str = 8;

            $rfid_start_num = sprintf($company_code.$mid_digit.'%0'.$max_str.'d', $start_num);
            $rfid_end_num = sprintf($company_code.$mid_digit.'%0'.$max_str.'d', $end_num);
            $qc="SELECT * FROM work_id_manager WHERE rfid IN ('".$rfid_start_num."','".$rfid_end_num."') ";
            $st=$con->execute($qc);
            $check = $st->fetchall(PDO::FETCH_ASSOC);
            if(count($check)>0){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode("RFIDが重複です。もう一度確認してください。");
                exit;
            }

            $qm="INSERT INTO work_id_manager (rfid,wim_class,wim_itemcode,wim_number,wim_username,wim_created_at,wiol_id,wim_status) VALUES ";
            for($i=$start_num;$i<=$end_num;$i++){
                $rfid_num = sprintf($company_code.$mid_digit.'%0'.$max_str.'d', $i);
                $qm.="('".$rfid_num."','".$param["id_type"]."','".$param["itemcode"]."','".$param["around_num"]."','".$param["user"]."','".date("Y-m-d H:i:s")."','".$param["id"]."','未使用'),";
            }
            $qm=substr($qm,0,-1);
    
            try{
                $con->execute($qm);
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode("OK");
            }catch(Exception $e){
                $err_qo=$e->getMessage();
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($err_qo);
            }
            return sfView::NONE;
            exit;
        }

        if($request->getParameter("ac")=="getLinkedList"){
            $req = $request->getParameter("req");
            $q="SELECT wim.*, (CASE WHEN mm.searchtag<>'' THEN mm.searchtag ELSE mm.itemname END) as itemname 
            FROM work_id_manager wim LEFT JOIN ms_molditem mm ON wim.wim_itemcode = mm.itempprocord ";
            $q.="WHERE wiol_id = '".$req["wiol_id"]."' AND wim_class = '".$req["type"]."' AND wim_itemcode = '".$req["itemcode"]."' AND wim_created_at = '".$req["created_at"]."' ORDER BY rfid ASC ";
            // $q.="WHERE wiol_id = '".$req["wiol_id"]."' AND wim_class = '".$req["type"]."' AND wim_itemcode = '".$req["itemcode"]."' ORDER BY rfid ASC ";
            $st=$con->execute($q);
            $manager_linked = $st->fetchall(PDO::FETCH_ASSOC);
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($manager_linked);
            return sfView::NONE;
            exit;
        }

        
        if($request->getParameter("ac")=="getUpdateRfid"){
            $d = $request->getParameter("d");
            $user = $request->getParameter("user");

            $q= "UPDATE work_id_manager SET ";
            $q.="wim_itemcode = '".$d["itemcode"]."', ";
            $q.="wim_class = '".$d["type"]."', ";
            $q.="wim_number = '".$d["number"]."', ";
            if($user!=""){
                $q.="wim_username = '".$user."', ";
            }
            $q.="wim_status = '".$d["status"]."' ";
            $q.="WHERE rfid = '".$d["rfid"]."' ";

            $rpq="NG";
            try{
                $con->execute($q);
                $rpq="OK";
            }catch(Exception $e){
                $rpq=$e->getMessage();
            }
            
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($rpq);
            exit;
        }

        if($request->getParameter("ac")=="getCSV"){
            $req = $request->getParameter("req");

            // $q="SELECT wim.*, (CASE WHEN mm.searchtag<>'' THEN mm.searchtag ELSE mm.itemname END) as item_name 
            $q="SELECT wim.*, mm.searchtag as searchtag, mm.itemname as item_name 
            FROM work_id_manager wim LEFT JOIN ms_molditem mm ON wim.wim_itemcode = mm.itempprocord 
            WHERE wiol_id = '".$req["wiol_id"]."' AND wim_class = '".$req["type"]."' AND wim_itemcode = '".$req["itemcode"]."' ";
            if($req["created_at"]!=""){
                $q.="AND wim_created_at = '".$req["created_at"]."' ";
            }
            $q.="ORDER BY rfid ASC ";
            $st=$con->execute($q);
            $res = $st->fetchall(PDO::FETCH_ASSOC);

            $csv = "";
            $last_date = "";
            foreach($res as $key=>$val){
                if($val["searchtag"]==""){
                    $val["searchtag"]=$val["item_name"];
                }
                $csv.='"'.$val["searchtag"].'","'.$val["wim_itemcode"].'","'.$val["wim_number"].'","'.$val['rfid'].'","'.$val["wim_class"].'","'.$val["item_name"].'"'."\n";
                // $csv.='"'.$val["item_name"].'","'.$val["wim_itemcode"].'",="'.$val['rfid'].'","'.$val["wim_number"].'","'.$val["wim_class"].'"'."\n";
                $last_date = substr($val["wim_created_at"],0,10);
            }
            // print_r($csv);
            // exit;
            $filename = $res[0]["item_name"]."_".$req["itemcode"]."_".$req["type"]."_".$last_date.".csv";
            $filename = mb_convert_encoding($filename,"SJIS", "UTF-8");
            $lines = mb_convert_encoding($csv,"SJIS", "UTF-8");
            $response = $this->getContext()->getResponse();
            $response->setHttpHeader('Pragma', '');
            $response->setHttpHeader('Cache-Control', '');
            $response->setHttpHeader('Content-Type', 'application/vnd.ms-excel');
            $response->setHttpHeader('Content-Disposition', 'attachment; filename="'.$filename.'"');
            $response->setContent($lines);
            return sfView::NONE;
            exit;
        }

        $q="SELECT wim.*,GROUP_CONCAT(DISTINCT wim_created_at ORDER BY wim_created_at DESC) as set_date, COUNT(wim.rfid) as sheets_num, (COUNT(wim.rfid)*wim.wim_number) as items_num, (CASE WHEN mm.searchtag<>'' THEN mm.searchtag ELSE mm.itemname END) as itemname 
        FROM work_id_manager wim LEFT JOIN ms_molditem mm ON wim.wim_itemcode = mm.itempprocord 
        WHERE wim.wiol_id = '".$id."' AND (wim.wim_itemcode IS NOT NULL OR wim.wim_itemcode <> '') ";
        // $q.="GROUP BY  wim_created_at, wim.wim_itemcode, wim.wim_class ORDER BY wim_created_at DESC, wim.wim_itemcode ASC, wim.wim_class ASC ";
        $q.="GROUP BY wim.wim_itemcode, wim.wim_class ORDER BY wim.wim_itemcode ASC, wim.wim_class ASC ";
        $st=$con->execute($q);
        $linked_group = $st->fetchall(PDO::FETCH_ASSOC);

        foreach($linked_group as $key=>$value){
            $gr_date=explode(",",$value["set_date"]);
            $q="SELECT wim.*, COUNT(wim.rfid) as sheets_num, (COUNT(wim.rfid)*wim.wim_number) as items_num, (CASE WHEN mm.searchtag<>'' THEN mm.searchtag ELSE mm.itemname END) as itemname 
            FROM work_id_manager wim LEFT JOIN ms_molditem mm ON wim.wim_itemcode = mm.itempprocord 
            WHERE wim.wiol_id = '".$value["wiol_id"]."' AND wim_class = '".$value["wim_class"]."' AND wim_itemcode = '".$value["wim_itemcode"]."' AND (wim.wim_itemcode IS NOT NULL OR wim.wim_itemcode <> '') ";
            $q.="GROUP BY wim.wim_itemcode, wim.wim_class, wim.wim_created_at ORDER BY wim.wim_created_at DESC, wim.wim_itemcode ASC, wim.wim_class ASC ";
            $st=$con->execute($q);
            $manager_linked = $st->fetchall(PDO::FETCH_ASSOC);
            $linked_group[$key]["children"]=$manager_linked;
            $linked_group[$key]["last_set_date"]=$gr_date[0];
        }
        $this->linked_group = $linked_group;

        $q="SELECT * FROM work_id_manager WHERE wiol_id = '".$id."' AND (wim_itemcode IS NOT NULL OR wim_itemcode <> '') ";
        $st=$con->execute($q);
        $linked_list = $st->fetchall(PDO::FETCH_ASSOC);
        $this->linked_list = $linked_list;

        $linked_num = count($linked_list);
        $this->linked_num = $linked_num;
        $this->this_start = $linked_num+$order_info["wiol_start_num"];

        $unlink_num = $order_info["wiol_o_number"]-$linked_num;
        $this->unlink_num = $unlink_num;

        $q="SELECT itempprocord, tray_num * tray_stok as round_num, fpr_num,(CASE WHEN searchtag<>'' THEN searchtag ELSE itemname END) as itemname FROM ms_molditem ORDER BY id DESC ";
        $st=$con->execute($q);
        $item_list = $st->fetchall(PDO::FETCH_ASSOC);
        $this->item_list = $item_list;

    }

    //利用中のRFID 登録
    public function executeEntryUsingId(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();
    
        if($request->getParameter('ac') == 'Entry') {
            $q="SELECT wic_rfid, wic_itemcode, wic_complete_flag, LENGTH(wic_rfid) as len, COUNT(DISTINCT wic_rfid) as id_num, 
            CASE WHEN wic_complete_flag = 0 THEN '仕掛ID' ELSE '完成ID' END as cpl_flag, mi.tray_num * mi.tray_stok as around_num, mi.fpr_num, '山崎工場' as place 
            FROM work_inventory_control wic LEFT JOIN ms_molditem mi ON wic.wic_itemcode = mi.itempprocord 
            WHERE wic_rfid <> '' AND wic_itemcode <> '1486' AND wic_wherhose LIKE '%山崎工場%'  GROUP BY wic_itemcode, wic_complete_flag 
            HAVING len = 24 ORDER BY wic_itemcode, wic_complete_flag ASC, wic_rfid ASC";
            $st=$con->execute($q);
            $item_link_list = $st->fetchall(PDO::FETCH_ASSOC);

            foreach($item_link_list as $key=> $value){
                $q="SELECT wic_rfid, wic_itemcode, wic_complete_flag, SUM(wic_qty_in) - SUM(wic_qty_out) as stt FROM work_inventory_control 
                WHERE wic_rfid <> '' AND wic_itemcode = '".$value["wic_itemcode"]."' AND wic_complete_flag = '".$value["wic_complete_flag"]."' AND wic_remark <> '員数不足' 
                GROUP BY wic_rfid, wic_complete_flag 
                ORDER BY wic_itemcode, wic_complete_flag ASC, wic_rfid ASC";
                $st=$con->execute($q);
                $list_manager = $st->fetchall(PDO::FETCH_ASSOC);

                $qi="INSERT INTO work_id_manager (rfid,wim_class,wim_itemcode,wim_number,wim_username,wim_created_at,wiol_id,wim_status) VALUES ";

                if($value["wic_complete_flag"]==0){
                    foreach($list_manager as $k=> $v){
                        $status = "使用中";
                        if($v["stt"]>0){
                            $status = "再発行";
                        }
                        $qi.="('".$v["wic_rfid"]."','".$value["cpl_flag"]."','".$v["wic_itemcode"]."','".$value["around_num"]."','ゴ クアン ビン','".date("Y-m-d H:i:s")."','2','".$status."'),";
                    }
                }else{
                    $around = $value["around_num"];
                    if($value["fpr_num"]>0){
                        $around = $value["fpr_num"];
                    }
                    foreach($list_manager as $k=> $v){
                        $status = "使用中";
                        if($v["stt"]>0){
                            $status = "再発行";
                        }
                        $qi.="('".$v["wic_rfid"]."','".$value["cpl_flag"]."','".$v["wic_itemcode"]."','".$around."','ゴ クアン ビン','".date("Y-m-d H:i:s")."','2','".$status."'),";
                    } 
                }
                $qi = substr($qi,0,-1);
                // echo "<pre>"
                // print_r($qi);
                // exit;
                $con->execute($qi);
            }
            print_r("OK");
            exit;
        }

        if($request->getParameter('ac') == 'StatusUpdate') {
            $q="SELECT wic_rfid, wic_itemcode, LENGTH(wic_rfid) as len, LENGTH(GROUP_CONCAT(DISTINCT wic_complete_flag)) as def, SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control 
            WHERE wic_itemcode <> '1486' AND wic_remark <> '員数不足' GROUP BY wic_rfid ORDER BY wic_itemcode, wic_rfid ";
            $st=$con->execute($q);
            $list_id_status = $st->fetchall(PDO::FETCH_ASSOC); 
            
            $q_recycle="UPDATE work_id_manager SET wim_status = '再発行' WHERE rfid IN(";
            foreach($list_id_status as $key=>$value){
                if($value["inv_num"]==0){
                    $q_recycle.="'".$value["wic_rfid"]."',";
                }
            }
            $q_recycle = substr($q_recycle,0,-1);
            $q_recycle.=")";
            // print_r($q_recycle);
            $con->execute($q_recycle);
            print_r("OK");
            exit;
        }

    }

    //不具合項目の工程と品目設定
    public function executeDefectiveItemManager(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('工程別不具合設定 | Nalux');

        if(isset($_SESSION["auth"]) && isset($_SESSION['start']) && (time() - $_SESSION['start'] < 43200)){
            $this->username = $_SESSION["auth"];
        }else{
            $url = $_SERVER["REQUEST_URI"];
            $this->redirect('/DomainCheck?url='.urlencode($url));
        }

        $q="SELECT * FROM ms_workitem_list WHERE workitem_plant_name = '".$request->getParameter("plant")."' ";
        $st=$con->execute($q);
        $work_list = $st->fetchall(PDO::FETCH_ASSOC);
        $this->work_list=$work_list;

        $q="SELECT itempprocord FROM ms_molditem ";
        $st=$con->execute($q);
        $item_list = $st->fetchall(PDO::FETCH_ASSOC);
        $this->item_list=$item_list;

        if($request->getParameter("ac")=="getItemDefec"){
            $q="SELECT (CASE WHEN searchtag<>'' THEN searchtag ELSE itemname END) as itemname FROM ms_molditem WHERE itempprocord = '".$request->getParameter("itemcode")."' ";
            $st=$con->execute($q);
            $msitem = $st->fetch(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            if($msitem){
                $q="SELECT * FROM work_ini_entry WHERE items = '".$request->getParameter("itemcode")."' AND kind = '".$request->getParameter("workitem")."' ";
                $st=$con->execute($q);
                $ditem_list = $st->fetch(PDO::FETCH_ASSOC);
    
                if($ditem_list){
                    $ditem_list["itemname"] = $msitem["itemname"];
                    echo json_encode(["OK",$ditem_list]);
                }else{
                    echo json_encode(["UNSET","品目コードを確認してください。",$msitem["itemname"]]);
                }
            }else{
                echo json_encode(["NG","品目コードを確認してください。"]);
            }
            exit;
        }
        
        if ($request->getParameter('ac') == 'Entry') {
            $params=$_REQUEST;
     
            $q="SELECT * FROM work_ini_entry WHERE items = '".$params["itemcode"]."' AND kind = '".$params["workitem"]."' ";
            $st=$con->execute($q);
            $wini = $st->fetch(PDO::FETCH_ASSOC);

            $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$params["itemcode"]."' ";
            $st=$con->execute($q);
            $msitem = $st->fetch(PDO::FETCH_ASSOC);

            $update_flag=true;
            if (!$wini) {
                $update_flag=false;
                $q="INSERT INTO work_ini_entry (items,kind,inidata) VALUES (?,?,?) ";
                $st = $con->prepare($q);
                $st->execute(array($params["itemcode"],$params["workitem"],$params["inidata"]));

                $q = "SELECT * FROM work_ini_entry ORDER BY id DESC LIMIT 1 ";
                $st=$con->execute($q);
                $lastini = $st->fetch(PDO::FETCH_ASSOC);
            }else{
                $q="UPDATE work_ini_entry SET inidata = '".$params["inidata"]."' WHERE items = '".$params["itemcode"]."' AND kind = '".$params["workitem"]."' ";
                $con->execute($q);
            }

            //記録
            $q="INSERT INTO ms_update_log (ms_item_id,ms_user,update_table,update_table_id,change_type,column_name,old_value,new_value,update_at) VALUES ";
            $q.="(?,?,?,?,?,?,?,?,?)";
            $st = $con->prepare($q);
            if($update_flag){
                //UPDATE
                $st->execute(array($msitem["id"],$params["user"],"work_ini_entry",$wini["id"],"update","inidata",$params["oldValue"],$params["inidata"],date("Y-m-d H:i:s")));
            }else{
                //INSERT
                $st->execute(array($msitem["id"],$params["user"],"work_ini_entry",$lastini["id"],"insert","inidata","",$params["inidata"],date("Y-m-d H:i:s")));
            }
           
            return sfView::NONE;
            exit;
        }

    }

    public function executeCompleteRfidLinked(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('RFIDの連携確認 | Nalux');

        if($request->getParameter("ac")=="getInfoData"){
            $rfid = $request->getParameter("rfid");
            $page_num = intval($request->getParameter("page_num"));
            $find_id = $request->getParameter("find_id");

            if($page_num==""){
                $page_num = 0;
            }
            // $id_type = $request->getParameter("id_type");
            $json=[];
            $q="SELECT (CASE WHEN wic_complete_flag = '1' OR wic_process = '完成品処理' THEN 'complete_id' ELSE 'device_id' END) as id_type, ms.moldet_undetected_load 
            FROM work_inventory_control LEFT JOIN ms_molditem ms ON wic_itemcode = ms.itempprocord 
            WHERE wic_rfid = '".$rfid."' AND wic_del_flg = '0' ";
            if($find_id){
                $q.="AND wic_complete_id = '".$find_id."' ";
            }
            $q.="ORDER BY wic_id DESC ";

            $st = $con->execute($q);
            $id_type = $st->fetch(PDO::FETCH_ASSOC);
            $json["id_type"] = $id_type["id_type"];
            if($id_type["id_type"]=="device_id"){
                //仕掛ID
                $q="SELECT wic_id FROM work_inventory_control WHERE wic_rfid = '".$rfid."' AND wic_qty_in > '0' AND wic_complete_flag = '0' AND wic_del_flg = '0' ORDER BY wic_id ASC LIMIT 1 ";
                $st = $con->execute($q);
                $check_id = $st->fetchall(PDO::FETCH_ASSOC);
                if(count($check_id)!==0){
                    //最新の生産データ

                    $q="SELECT COUNT(hgpd_id) as cc FROM hgpd_report WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
                    $st = $con->execute($q);
                    $check_count = $st->fetch(PDO::FETCH_ASSOC);

                    $q="SELECT hgpd_id,hgpd_process FROM hgpd_report WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
                    $st = $con->execute($q);
                    $last_id = $st->fetchall(PDO::FETCH_ASSOC);
                    // print_r($last_id);
                    // exit;
                    foreach($last_id as $key=>$value){
                        $gr_id[]=$value["hgpd_id"];
                        if($find_id && $find_id == $value["hgpd_id"]){
                            $page_num = floor($key/10);
                        }
                        // if(strpos($value["hgpd_process"],"成形")!==false || strpos($value["hgpd_process"],"保留処理")!==false){
                        if(strpos($value["hgpd_process"],"成形")!==false || strpos($value["hgpd_process"],"組立")!==false){
                            $all_id[] = implode($gr_id,",");
                            $gr_id=[];
                        }
                    }
                    
                    $max_one_page = 10;
                    $json["max_one_page"]=$max_one_page;

                    $json["page"]=ceil(count($all_id)/$max_one_page);
                    $json["now_page"]=$page_num;
                    $all_cycle=count($all_id);
                    $json["all_cycle"]=$all_cycle;

                    if(count($all_id)>$max_one_page){
                        $all_page_id = array_chunk($all_id, $max_one_page);
                        $list_id = $all_page_id[$page_num];
                    }else{
                        $list_id = $all_id;
                    }
                    // print_r($all_id);
                    // exit;
                    foreach($list_id as $k=>$ids){
                        $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                        (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_before_id IN(".$ids.") 
                        UNION ALL 
                        SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_before_id = temp1.cid) 
                        SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                        $st = $con->execute($q);
                        $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                        if($check_sub["all_ids"]!=""){
                            $list_ids = $check_sub["all_ids"];
                        }else{
                            $list_ids = $ids;
                        }

                        //RFID状態
                        $q="SELECT wic_rfid, SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num 
                        FROM work_inventory_control WHERE wic_hgpd_id IN (".$list_ids.") AND wic_remark <> '員数不足​' AND wic_del_flg = '0' 
                        GROUP BY wic_rfid ";
                        // print_r($q);
                        // exit;
                        $st = $con->execute($q);
                        $status = $st->fetch(PDO::FETCH_ASSOC);
                        if(intval($status["inv_num"])==0){
                            if($k==0){
                                $json["status"][]="再発行待ち";
                            }else{
                                $json["status"][]="再発行済み";
                            }
                        }elseif(intval($status["inv_num"])<0){
                            $json["status"][]="員数不合";
                        }else{
                            $json["status"][]="次工程待ち";
                        }

                        $q= "SELECT wic.*,hr.*,ms.itemname,ms.searchtag,xwr.moldmachine, GROUP_CONCAT(CONCAT(hd.hgpdd_ditem, '=>',hd.hgpdd_qty)) as hgpd_def 
                        FROM work_inventory_control wic
                            LEFT JOIN hgpd_report_sub hrs ON wic.wic_id = hrs.hrs_complete_wic_id 
                            LEFT JOIN hgpd_report hr ON wic.wic_hgpd_id = hr.hgpd_id 
                            LEFT JOIN ms_molditem ms ON wic.wic_itemcode = ms.itempprocord 
                            LEFT JOIN hgpd_report_defectiveitem hd ON wic.wic_hgpd_id = hd.hgpd_id 
                            LEFT JOIN xls_work_report as xwr ON hr.xwr_id = xwr.id 
                        WHERE wic.wic_hgpd_id IN (".$list_ids.") 
                        AND (hgpd_before_id IN (".$list_ids.") OR hgpd_before_id IS NULL) AND (wic.wic_qty_in > 0 || wic.wic_remark = '端数寄せ' || wic.wic_remark LIKE '保留処理入庫%' || wic.wic_remark = '廃棄') AND wic.wic_remark <> '完成品残数' AND hr.hgpd_del_flg = '0' AND wic.wic_del_flg = '0' ";
                        $q.="GROUP BY wic.wic_id ORDER BY wic_date ASC, wic.wic_id ASC ";
                        // print_r($q);       
                        // exit;
                        $st = $con->execute($q);
                        $history = $st->fetchall(PDO::FETCH_ASSOC);

                        foreach($history as $kh => $kv){
                            if(strpos($kv["wic_process"],"成形")!==false){
                                $q="SELECT GROUP_CONCAT(id) as ids FROM manege_stopsmall WHERE stop_mno = '".$kv["moldmachine"]."' AND stop_sdate >= '".$kv["hgpd_start_at"]."' AND stop_redate <= '".$kv["hgpd_stop_at"]."' ";
                                $st=$con->execute($q);
                                $stopsmall = $st->fetch(PDO::FETCH_ASSOC);
                                if(count($stopsmall)>0){
                                    $history[$kh]["stopsmall"]=$stopsmall["ids"];
                                }
                            }
                            if($kv["wic_remark"]=="端数寄せ"){
                                $q="SELECT wic_rfid FROM work_inventory_control WHERE wic_created_at = '".$kv["wic_created_at"]."' AND wic_rfid <> '".$kv["wic_rfid"]."' AND wic_del_flg = '0' ";
                                $st=$con->execute($q);
                                $round_rfid = $st->fetch(PDO::FETCH_ASSOC);
                                if(count($round_rfid)>0){
                                    $history[$kh]["round_rfid"]=$round_rfid["wic_rfid"];
                                }
                            }
                            $work_second = strtotime($kv["hgpd_stop_at"])-strtotime($kv["hgpd_start_at"]);
                            $history[$kh]["work_time"]=round($work_second/3600)."時".round(($work_second%3600)/60)."分";
                        }

                        $json["info"]["data"][]=$history;
                        $json["cycle"][]=$all_cycle - ($page_num*10+$k);
                    }
                }else{
                    $json["info"]=[];
                }
            }elseif($id_type["id_type"]=="complete_id"){
                //完成ID----------------------------------------------------------------------------
                $cq = "完成品処理";
                if($id_type["moldet_undetected_load"]=="1"){
                    $cq = "成形";
                }
                $q="SELECT hgpd_id FROM hgpd_report
                WHERE hgpd_rfid = '".$rfid."' AND (hgpd_process LIKE '%".$cq."%') AND hgpd_del_flg = '0' 
                GROUP BY hgpd_id ORDER BY hgpd_id DESC ";
                $st = $con->execute($q);
                $res = $st->fetchall(PDO::FETCH_ASSOC);
                $last_cpl_id = $res[0]["hgpd_id"];

                $max_one_page = 10;
                $json["max_one_page"]=$max_one_page;

                $all_cycle=count($res);
                $json["page"]=ceil($all_cycle/$max_one_page);
                $json["now_page"]=$page_num;
                $json["all_cycle"]=$all_cycle;

                foreach($res as $ck => $cv){
                    $count_rfid[$cv["hgpd_id"]] = $ck;
                    $count_cpl_id[] = $cv["hgpd_id"];
                    $json["cycle"][]=$all_cycle - ($page_num*$max_one_page+$ck);
                }

                $all_page_id = array_chunk($count_cpl_id, $max_one_page);

                $max_id = $all_page_id[$page_num][0];

                //最新IDの保留チェック
                $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_before_id IN(".$max_id.") 
                UNION ALL 
                SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_before_id = temp1.cid) 
                SELECT * FROM temp1 ORDER BY cid DESC ";
                $st = $con->execute($q);
                $check_last_sub = $st->fetch(PDO::FETCH_ASSOC);
                if($check_last_sub["cid"]){
                    $max_id = $check_last_sub["cid"];
                }

                $min_id = $all_page_id[$page_num][0];
                foreach($all_page_id[$page_num] as $bk => $bv){
                    if($bv<$min_id){
                        $min_id = $bv;
                    }
                }
              
                // print_r($count_rfid);
                $q="SELECT *, GROUP_CONCAT(CONCAT(hrd.hgpdd_ditem, '=>',hrd.hgpdd_qty)) as hgpd_def 
                FROM work_inventory_control 
                LEFT JOIN hgpd_report_sub ON wic_id = hrs_complete_wic_id 
                LEFT JOIN hgpd_report hr ON hr.hgpd_id = wic_hgpd_id 
                LEFT JOIN hgpd_report_defectiveitem hrd ON hrd.hgpd_id = hr.hgpd_id
                LEFT JOIN ms_molditem ON wic_itemcode = itempprocord 
                WHERE wic_rfid = '".$rfid."' AND wic_complete_id >= '".$min_id."' ";
                $q.="AND wic_complete_id <= '".$max_id."' ";
                $q.="AND hgpd_del_flg = '0' AND wic_del_flg = '0' ";
                if($id_type["moldet_undetected_load"]=="1"){
                    // $q.="GROUP BY wic_complete_id ORDER BY wic_id DESC ";
                    $q.="AND wic_remark <> '完成品処理' AND wic_remark <> '成形未検出荷' GROUP BY wic_id ORDER BY wic_id DESC ";
                }else{
                    $q.="GROUP BY wic_id ORDER BY wic_id DESC ";
                }
                $st = $con->execute($q);
                $list_rfid = $st->fetchall(PDO::FETCH_ASSOC);
                // echo "<pre>";
                // print_r($list_rfid);
                // exit;

                $gr_key="";
                $ship_no = 0;
                $json["cpl_item"]=[];
                $json["ship_all"]=[];
                $json["process_return"]=[];
                $json["status"]="出荷待ち";
                $defult_count_cpl=$page_num*$max_one_page;
                $count_cpl=$defult_count_cpl;

                foreach($list_rfid as $key=>$value){
                    //RFIDの回数の数え
                    if($count_rfid[$value["wic_complete_id"]] && $value["wic_process"]=="完成品処理"){
                        $defult_count_cpl = intval($count_rfid[$value["wic_complete_id"]]);
                        $count_cpl=$defult_count_cpl;
                        $last_cpl_id = $value["wic_complete_id"];
                    }else{
                        if($key==0 && $value["wic_process"]=="保留処理"){
                            $count_cpl=$defult_count_cpl;
                            $last_cpl_id = $value["wic_complete_id"];
                            $last_cpl_proc = $value["wic_process"];
                        }elseif(intval($value["wic_complete_id"])<intval($last_cpl_id) && $last_cpl_proc != "保留処理"){
                            $count_cpl=$defult_count_cpl+1;
                            $last_cpl_proc = $value["wic_process"];
                        }
                    }

                    if($value["moldet_undetected_load"]=="1"){
                        $ids = $value["wic_hgpd_id"];
                    }else{
                        $ids = $value["hgpd_before_id"];
                    }

                    if($key==0 && $value["wic_remark"]!="完成品処理"){
                    // if($key==0){
                        //一番最新データの備考で状態判断する
                        if(strpos($value["wic_remark"],"棚卸出庫")!==false){
                            //棚卸処理場合
                            $json["status"]="棚卸出庫";
                        }elseif(strpos($value["wic_remark"],"廃棄")!==false){
                            //廃棄された場合
                            $json["status"]="廃棄";
                            $json["ship_all"][$count_cpl][]=$value;
                            continue;
                        }elseif(strpos($value["wic_remark"],"保留処理")!==false){
                            //保留処理された場合
                            $json["status"]="再発行待ち";
                            if($value["wic_qty_in"]>0){
                                $json["process_return"][$count_cpl]["rt_time"]=$value;
                                continue;
                            }else{
                                $q="SELECT *, GROUP_CONCAT(DISTINCT CONCAT(hrd.hgpdd_ditem, '=>',hrd.hgpdd_qty)) as hgpd_def 
                                FROM work_inventory_control 
                                LEFT JOIN hgpd_report_sub ON wic_id = hrs_complete_wic_id 
                                LEFT JOIN hgpd_report hr ON hr.hgpd_id = wic_complete_id 
                                LEFT JOIN hgpd_report_defectiveitem hrd ON hrd.hgpd_id = wic_complete_id
                                LEFT JOIN ms_molditem ON wic_itemcode = itempprocord 
                                WHERE hr.hgpd_id = '".$value["wic_complete_id"]."' AND hgpd_del_flg = '0' AND wic_del_flg = '0' GROUP BY hr.hgpd_id ";
                                // print_r($q);
                                // exit;
                                $st = $con->execute($q);
                                $hold_item = $st->fetch(PDO::FETCH_ASSOC);
    
                                $work_second = strtotime($hold_item["hgpd_stop_at"])-strtotime($hold_item["hgpd_start_at"]);
                                $hold_item["work_time"]=round($work_second/3600)."時".round(($work_second%3600)/60)."分";
    
                                $holding_item[$value["wic_id"]] = $hold_item;
                                $json["process_return"][$count_cpl]["next_process"]=$holding_item;
                                continue; 
                            }

                        }elseif(strpos($value["wic_remark"],"出荷処理出庫")!==false){
                            //出荷された
                            $json["status"]="出荷リスト紐付済み";
                            $json["ship_all"][$count_cpl][]=$value;
                        }else{
                            $json["cpl_item"][$value["wic_date"]][]=$value;
                            $json["cpl_item_all"][$value["wic_date"]][]=$value;
                        }
                        if($value["moldet_undetected_load"]=="1"){
                            $json["device"][$value["wic_id"]][]=$value;
                            $json["device_all"][$value["wic_id"]][]=$value;
                        }
                    }else{
                        if(strpos($value["wic_remark"],"出荷処理出庫")!==false){
                            //+cycle
                            $ship_no++;
                            $json["ship_all"][$count_cpl][]=$value;
                            continue;
                        }

                        if($value["wic_process"]=="保留処理"){
                            $json["status"]="再発行待ち";
                            if($value["wic_qty_in"]>0){
                                //不具合連絡発生時
                                $json["process_return"][$count_cpl]["rt_time"]=$value;
                                continue;
                            }else{
                                $q="SELECT *, GROUP_CONCAT(CONCAT(hrd.hgpdd_ditem, '=>',hrd.hgpdd_qty)) as hgpd_def 
                                FROM work_inventory_control 
                                LEFT JOIN hgpd_report_sub ON wic_id = hrs_complete_wic_id 
                                LEFT JOIN hgpd_report hr ON hr.hgpd_id = wic_complete_id 
                                LEFT JOIN hgpd_report_defectiveitem hrd ON hrd.hgpd_id = wic_complete_id
                                LEFT JOIN ms_molditem ON wic_itemcode = itempprocord 
                                WHERE hr.hgpd_id = '".$value["wic_complete_id"]."' AND hgpd_del_flg = '0' AND wic_del_flg = '0' GROUP BY hr.hgpd_id ";
                                // print_r($q);
                                // exit;
                                $st = $con->execute($q);
                                $hold_item = $st->fetch(PDO::FETCH_ASSOC);
    
                                $work_second = strtotime($hold_item["hgpd_stop_at"])-strtotime($hold_item["hgpd_start_at"]);
                                $hold_item["work_time"]=round($work_second/3600)."時".round(($work_second%3600)/60)."分";
    
                                $holding_item[$value["wic_id"]] = $hold_item;
                                // $holding_item[] = $hold_item;
                                // sort($holding_item);
                                $json["process_return"][$count_cpl]["next_process"]=$holding_item;
                                continue;
                            }
                        }

                        
                        if($value["wic_remark"]=="廃棄"){
                            $ship_no++;
                            // $json["status"]="再発行待ち";
                            $json["ship_all"][$count_cpl][]=$value;
                            continue;
                        }

                        if($value["wic_remark"]=="棚卸出庫"){
                            $ship_no++;
                            $json["ship_all"][$count_cpl][]=$value;
                            continue;
                        }

                        if($ids==""){
                            continue;
                        }    
                        //連携情報収得
                        $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                        (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$ids.") 
                        UNION ALL 
                        SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid) 
                        SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";

                        $st = $con->execute($q);
                        $check_sub = $st->fetch(PDO::FETCH_ASSOC);
                        if($check_sub["all_ids"]!=""){
                            $list_ids = $check_sub["all_ids"];
                        }else{
                            $list_ids = $value["hgpd_before_id"];
                            if($value["moldet_undetected_load"]=="1"){
                                $list_ids = $value["wic_hgpd_id"];
                            }
                        }

                        //仕掛の連携データ
                        if($list_ids!=""){
                            $q= "SELECT hr.*, wic.*, xwr.moldmachine, GROUP_CONCAT(DISTINCT CONCAT(hd.hgpdd_ditem, '=>',hd.hgpdd_qty)) as hgpd_def 
                            FROM hgpd_report hr 
                                LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id 
                                LEFT JOIN xls_work_report xwr ON hr.xwr_id = xwr.id 
                                LEFT JOIN hgpd_report_defectiveitem hd ON hr.hgpd_id = hd.hgpd_id 
                            WHERE hr.hgpd_id IN(".$list_ids.") AND hgpd_del_flg = '0' AND wic.wic_del_flg = '0' 
                            GROUP BY hr.hgpd_id
                            ORDER BY hr.hgpd_id ASC ";
                            $st = $con->execute($q);
                            $before_list = $st->fetchall(PDO::FETCH_ASSOC);

                            $q= "SELECT hr.*, wic.*, xwr.moldmachine, GROUP_CONCAT(DISTINCT CONCAT(hd.hgpdd_ditem, '=>',hd.hgpdd_qty)) as hgpd_def 
                            FROM hgpd_report hr 
                                LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id 
                                LEFT JOIN xls_work_report xwr ON hr.xwr_id = xwr.id 
                                LEFT JOIN hgpd_report_defectiveitem hd ON hr.hgpd_id = hd.hgpd_id 
                            WHERE hr.hgpd_id IN(".$list_ids.") AND wic.wic_remark = '端数寄せ' AND hgpd_del_flg = '0' AND wic.wic_del_flg = '0' 
                            GROUP BY wic.wic_id 
                            ORDER BY hr.hgpd_id ASC ";
                            $st = $con->execute($q);
                            $before_round_list = $st->fetchall(PDO::FETCH_ASSOC);

                            if(count($before_round_list)>0){
                                foreach($before_round_list as $brlk => $brlv){
                                    $before_list[] = $brlv;
                                }
                            }

                            foreach($before_list as $kh => $kv){
                                if(strpos($kv["hgpd_process"],"成形")!==false){
                                    $q="SELECT GROUP_CONCAT(id) as ids FROM manege_stopsmall WHERE stop_mno = '".$kv["moldmachine"]."' AND stop_sdate >= '".$kv["hgpd_start_at"]."' AND stop_redate <= '".$kv["hgpd_stop_at"]."' ";
                                    $st=$con->execute($q);
                                    $stopsmall = $st->fetch(PDO::FETCH_ASSOC);
                                    if(count($stopsmall)>0){
                                        $before_list[$kh]["stopsmall"]=$stopsmall["ids"];
                                    }
                                }
                                if($kv["wic_remark"]=="端数寄せ"){
                                    $q="SELECT wic_rfid FROM work_inventory_control WHERE wic_created_at = '".$kv["wic_created_at"]."' AND wic_rfid <> '".$kv["wic_rfid"]."' AND wic_del_flg = '0' ";
                                    $st=$con->execute($q);
                                    $round_rfid = $st->fetch(PDO::FETCH_ASSOC);
                                    if(count($round_rfid)>0){
                                        $before_list[$kh]["round_rfid"]=$round_rfid["wic_rfid"];
                                    }
                                }
                                $work_second = strtotime($kv["hgpd_stop_at"])-strtotime($kv["hgpd_start_at"]);
                                $before_list[$kh]["work_time"]=round($work_second/3600)."時".round(($work_second%3600)/60)."分";
                            }
                        }else{
                            $before_list=[]; 
                        }

                        $json["cpl_item_all"][$value["wic_date"]][]=$value;
                        $json["device_all"][$value["wic_id"]]=$before_list;

                        if($count_cpl==0){
                            $json["cpl_item"][$value["wic_date"]][]=$value;
                            $json["device"][$value["wic_id"]]=$before_list;
                        }
                    }
                }
            }else{
                $json["id_type"]="none";
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($json);
            exit;
        }
    }

    public function executeRFIDStatusCheck(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();
        $this->getResponse()->setTitle('RFIDの状態確認 | Nalux');

        if($request->getParameter("ac")=="getIds"){
            $ids = $request->getParameter("ids");
            $site = $request->getParameter("site");
            $list_res = [];
            foreach($ids as $key=>$value){
                $rfid = $value;

                $q="SELECT (CASE WHEN wic_complete_flag = '1' OR wic_process = '完成品処理' THEN 'complete_id' ELSE 'device_id' END) as id_type, ms.moldet_undetected_load 
                FROM work_inventory_control LEFT JOIN ms_molditem ms ON wic_itemcode = ms.itempprocord 
                WHERE wic_rfid = '".$rfid."' AND wic_del_flg = '0' ORDER BY wic_id DESC ";
                $st = $con->execute($q);
                $id_type = $st->fetch(PDO::FETCH_ASSOC);

                $st = $con->execute($q);
                $id_type = $st->fetch(PDO::FETCH_ASSOC);
                $json["id_type"] = $id_type["id_type"];
                if($id_type["id_type"]=="device_id"){
                    //仕掛ID
                    $q="SELECT * FROM work_inventory_control WHERE wic_rfid = '".$rfid."' AND wic_complete_flag = '0'  AND wic_del_flg = '0' ORDER BY wic_id ASC ";
                    $st = $con->execute($q);
                    $check_id = $st->fetchall(PDO::FETCH_ASSOC);
                    // print_r($check_id);
                    // exit;
                    $number_used=0;
                    foreach($check_id as $nuk=>$nuv){
                        if($nuv["wic_process_key"]=="M" && $nuv["wic_remark"]=="成形入庫"){
                            $number_used++;
                        }
                    }
    
                    if(count($check_id)!==0){
                        $q="SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
                        $st = $con->execute($q);
                        $last_id = $st->fetch(PDO::FETCH_ASSOC);
             
                        $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                        (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_id["hgpd_id"].") 
                        UNION ALL 
                        SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid) 
                        SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                        $st = $con->execute($q);
                        $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                        if($check_sub["all_ids"]!=""){
                            $list_ids = $check_sub["all_ids"];
                        }else{
                            $list_ids = $last_id["hgpd_id"];
                        }
                        

                        // print_r($list_ids);
                        // exit;

                        $q="SELECT *,SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv, GROUP_CONCAT(wic_id) as wic_ids, GROUP_CONCAT(DISTINCT wic_hgpd_id) as hgpd_ids 
                        FROM work_inventory_control wic 
                        LEFT JOIN ms_molditem ms ON wic.wic_itemcode = ms.itempprocord 
                        WHERE wic_rfid = '".$rfid."' ";
                        if($last_id["hgpd_process"]!="保留処理" && $last_id["hgpd_remaining"]==0){
                            $q.="AND wic_process = '".$last_id["hgpd_process"]."' ";
                        }
                        $q.="AND wic_hgpd_id IN (".$list_ids.") AND wic_remark <> '員数不足​'  AND wic_del_flg = '0' ";
                        $q.="ORDER BY wic_id ASC ";

                        // print_r($q);
                        // exit;
                        $st = $con->execute($q);
                        $list_rfid = $st->fetch(PDO::FETCH_ASSOC);
    
                        $list_rfid["hgpd_moldday"]=$last_id["hgpd_moldday"];
                        $list_rfid["number_used"]=$number_used;

                        if(intval($list_rfid["sum_inv"])==0){
                            $json["status"]="再発行待ち";
                            $list_rfid["check_val"]="NG";
                            if($site=="ItemComplete"){
                                $list_rfid["check_val"]="OK";
                            }
                        }else{
                            //BOM連携情報を収得
                            $q="SELECT code, end_code, item_code, proccess_name, befor_code FROM work_adempiere_item_ms WHERE code = '".$last_id["hgpd_itemcode"]."' ";
                            $st = $con->execute($q);
                            $bom_data = $st->fetchall(PDO::FETCH_ASSOC);
                            foreach($bom_data as $bk=>$bv){
                                if(str_replace(["　"," "]," ",$last_id["hgpd_process"])==str_replace(["　"," "]," ",$bv["proccess_name"])){
                                    $after_code = $bv["code"].$bv["end_code"];
                                }
                            }
                     
                            $nameArray = array_column($bom_data, 'befor_code');
                            if($after_code){
                                $after_process_key =  array_search($after_code, $nameArray);
                                if($after_process_key){
                                    $after_process=$bom_data[$after_process_key]["proccess_name"];
                                }else{
                                    if(strpos($last_id["hgpd_process"],'成形')!==false || $last_id["hgpd_remaining"]>0){
                                        $after_process="検査";
                                    }else{
                                        $after_process="完成処理";
                                    } 
                                }
                            }else{
                                if(strpos($last_id["hgpd_process"],'成形')!==false || $last_id["hgpd_remaining"]>0){
                                    $after_process="検査";
                                }else{
                                    $after_process="完成処理";
                                } 
                            }
                          
                            $json["status"]=$after_process."待ち";
                            $list_rfid["check_val"]="OK";
                            if($site=="MoldingCounter"){
                                if(strpos($last_id["hgpd_process"],'最終')!==false){
                                    $list_rfid["check_val"]="NG";
                                    $json["status"]="完成処理待ち";
                                }
                            }elseif($site=="ItemComplete"){
                                if(strpos($last_id["hgpd_process"],'最終')!==false){
                                    $json["status"]="完成処理待ち";
                                }else{
                                    $list_rfid["check_val"]="NG";
                                }
                            }else{
                                if(strpos($last_id["hgpd_process"],'最終')!==false && $last_id["hgpd_remaining"]==0){
                                    $json["status"]="完成処理待ち";
                                }
                            }
                            // if($site=="ItemComplete"){
                            //     if(strpos($last_id["hgpd_process"],'成形')===false){
                            //         $json["status"]="完成処理待ち";
                            //     }else{
                            //         $list_rfid["check_val"]="NG";
                            //         $json["status"]="検査待ち";
                            //     }
                            // }elseif($site=="MoldingCounter"){
                            //     if(strpos($last_id["hgpd_process"],'成形')!==false){
                            //         $json["status"]="検査待ち";
                            //     }else{
                            //         $list_rfid["check_val"]="NG";
                            //         $json["status"]="完成処理待ち";
                            //     }
                            // }else{
                            //     if(strpos($last_id["hgpd_process"],'成形')!==false){
                            //         $json["status"]="検査待ち";
                            //     }else{
                            //         $json["status"]="完成処理待ち";
                            //     } 
                            // }
                        }
    
                        $json["info"]=$list_rfid;
                        $json["info"]["xwr_id"]=$last_id["xwr_id"];
                    }else{
                        $json["info"]=[];
                    }
                }elseif($id_type["id_type"]=="complete_id"){
                    //完成ID
                    $q="SELECT * FROM hgpd_report 
                    WHERE hgpd_rfid = '".$rfid."' AND hgpd_process <> '出荷・在庫:梱包' AND hgpd_del_flg = '0' 
                    ORDER BY hgpd_checkday DESC, hgpd_id DESC ";
                    $st = $con->execute($q);
                    $last_id = $st->fetch(PDO::FETCH_ASSOC);

                    //在庫残数チェック
                    $q="SELECT *,(SUM(wic_qty_in) - SUM(wic_qty_out)) as inv_num FROM work_inventory_control ";
                    $q.="WHERE wic_rfid = '".$rfid."' AND wic_complete_flag = '1' AND wic_del_flg = '0' ";
                    $q.="ORDER BY wic_id DESC ";
                    $st = $con->execute($q);
                    $inv_check = $st->fetch(PDO::FETCH_ASSOC);

                    $q="SELECT * FROM work_inventory_control LEFT JOIN ms_molditem ON wic_itemcode = itempprocord ";
                    if($last_id["hgpd_id"]!=""){
                        $q.="WHERE wic_rfid = '".$rfid."' AND wic_hgpd_id IN (".$last_id["hgpd_id"].") AND wic_complete_flag = '1' AND wic_del_flg = '0' ";
                    }else{
                        $q.="WHERE wic_rfid = '".$rfid."' AND wic_complete_flag = '1' AND wic_del_flg = '0' ";
                    }
                    $q.="ORDER BY wic_id DESC ";
                    $st = $con->execute($q);
                    $list_rfid = $st->fetchall(PDO::FETCH_ASSOC);
    
                    //回数数え
                    // $number_used=1;
                    // foreach($list_rfid as $key=>$value){
                    //     if($value["wic_remark"]=="出荷処理出庫"){
                    //         $number_used++;
                    //     }
                    // }
                    $q="SELECT COUNT(wic_id) as number_used FROM work_inventory_control WHERE wic_rfid = '".$rfid."' AND wic_complete_flag = '1' AND (wic_remark = '出荷処理出庫' || wic_remark = '廃棄' || wic_remark LIKE '保留処理出庫%' ) AND wic_del_flg = '0' GROUP BY wic_rfid ";
                    $st = $con->execute($q);
                    $count_used = $st->fetch(PDO::FETCH_ASSOC);
                    $number_used=intval($count_used["number_used"])+1;
                    
                    if(intval($inv_check["inv_num"])==0){
                        $json["status"]="再発行待ち";
                        $list_rfid[0]["wic_process"] = $last_id["hgpd_process"];

                        $json["info"]=$list_rfid[0];

                        $json["info"]["sum_inv"] = 0;
                        $json["info"]["check_val"]="NG";
                        $json["info"]["number_used"]=$number_used-1;
                        $json["info"]["hgpd_id"]=$last_id["hgpd_id"];
                        $json["info"]["wic_ids"]=$list_rfid[0]["wigit_id"];
                        $json["info"]["xwr_id"]=$last_id["xwr_id"];
                    }else{
                        $json["status"]="出荷待ち";
                        $sum_inv = 0;
                        $check_val = "";
                        $cpl_ids = "";
                        foreach($list_rfid as $key=>$value){
                            if($key==0 && ($value["wic_remark"]=="出荷処理出庫" || $value["wic_remark"]=="棚卸出庫" || $value["wic_remark"]=="廃棄" || strpos($value["wic_remark"],"保留処理出庫")!==false)){
                                $number_used--;
                                // $json["status"]="出荷リスト紐付済み";
                                $json["status"]="再発行待ち";
                                $value["sum_inv"]=0;
                                if($value["wic_remark"]=="出荷処理出庫"){
                                    $value["wic_process"]="出荷";
                                }elseif(strpos($value["wic_remark"],"保留処理出庫")!==false){
                                    $value["wic_process"]="保留処理";
                                }else{
                                    $value["wic_process"]==$value["wic_remark"];
                                }
                                $json["info"]=$value;
                                $check_val="NG";
                                $cpl_ids.=$value["wic_id"].",";
                                break;
                            }else{
                                if($value["wic_remark"]=="出荷処理出庫"){
                                    break;
                                }else{
                                    $sum_inv+= intval($value["wic_qty_in"]);
                                    $cpl_ids.=$value["wic_id"].",";
                                    if($check_val==""){
                                        $check_val="OK";
                                    }
                                    $value["sum_inv"]=$sum_inv;
                                    $json["info"]=$value;
                                }
                            }
                        }

                        $json["info"]["check_val"]=$check_val;
                        $json["info"]["number_used"]=$number_used;
                        $json["info"]["hgpd_id"]=$last_id["hgpd_id"];
                        $json["info"]["wic_ids"]=substr($cpl_ids,0,-1);
                        $json["info"]["xwr_id"]=$last_id["xwr_id"];
                    }
                }else{
                    $json["id_type"]="none";
                    $json["info"]["wic_rfid"]=$rfid;
                    $json["info"]["check_val"]="NG";
                }
                $list_res[]=$json;
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($list_res);
            exit;

        }

        if($request->getParameter("ac")=="entryCheckResult"){
            $data = $request->getParameter("data");
            $user = $request->getParameter("user");
            $created_at = date("Y-m-d H:i:s");
            $q="INSERT INTO hgpd_report_process_check (hgpd_id,hrpc_user,hrpc_process,hrpc_reslt,hrpc_created_at) VALUES ";
            foreach($data as $key=>$val){
                if($val["hgpd_id"]){
                    $list_hgpd_id=explode(",",$val["hgpd_id"]);
                    foreach($list_hgpd_id as $k=>$v){
                        $q.="('".$v."',";
                        $q.="'".$user."',";
                        $q.="'".$val["process"]."',";
                        $q.="'".$val["result"]."',";
                        $q.="'".$created_at."'),";
                    }
                }
            }
            $q=substr($q,0,-1);
            try{
                $con->execute($q);
                echo json_encode("OK");
            }catch(Exception $e){
                // echo json_encode($q);
                echo json_encode($e->getMessage());
            }
            exit;
        }
    }

    //仕掛ID寄せ
    public function executeRoundingDeviceID(sfWebRequest $request)
    {
        /*データベース接続変更 */
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('仕掛ID端数寄せ作業 ｜ Nalux');

        if($request->getParameter("ac")=="getRfid"){
            $rfid=$request->getParameter("rfid");
            $json=[];
            //仕掛ID
            $q="SELECT * FROM work_inventory_control WHERE wic_rfid = '".$rfid."' ORDER BY wic_id DESC ";
            $st = $con->execute($q);
            $check_id = $st->fetchall(PDO::FETCH_ASSOC);
            if(count($check_id)!==0){
                //使ってるデータ
                if($check_id[0]["wic_complete_flag"]==1){
                    //完成ID
                    $json["status"]="完成IDが端数寄せできません。";
                    $json["data"]=[];
                }else{
                    //最新の生産データ
                    $q="SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
                    $st = $con->execute($q);
                    $last_id = $st->fetch(PDO::FETCH_ASSOC);

                    //チェック前の工程情報
                    // $q="SELECT GROUP_CONCAT(CONCAT(hgpd_complete_id,',',hgpd_before_id)) as ids FROM hgpd_report_sub WHERE hgpd_complete_id = '".$last_id["hgpd_id"]."' OR hgpd_before_id = '".$last_id["hgpd_id"]."' ORDER BY hgpd_complete_id DESC ";
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                        (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id = '".$last_id["hgpd_id"]."' 
                        UNION ALL 
                        SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_sub["all_ids"]!=""){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $last_id["hgpd_id"];
                    }
                    $q="SELECT hr.*,GROUP_CONCAT(DISTINCT wic.wic_hgpd_id) as hgpd_ids, ms.tray_num,ms.tray_stok, SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num 
                    FROM hgpd_report hr 
                    LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id
                    LEFT JOIN ms_molditem ms ON hr.hgpd_itemcode = ms.itempprocord
                    WHERE wic_rfid = '".$rfid."' AND wic_complete_flag = '0' AND wic.wic_hgpd_id IN (".$list_ids.") AND wic_remark <> '員数不足' AND hr.hgpd_del_flg = '0' AND wic.wic_del_flg = '0' ";
                    $q.="ORDER BY hr.hgpd_id DESC ";
                    // print_r($q);
                    // exit;
                    $st = $con->execute($q);
                    $check_id = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_id["inv_num"]==0){
                        $json["data"]=[];
                        $json["status"]="再発行待ち";
                    }else{
                        $json["data"]=$check_id;
                        $json["data"]["last_id"]=$last_id["hgpd_id"];
                        $json["data"]["last_process"]=$last_id["hgpd_process"];
                        if(strpos($last_id["hgpd_process"],'成形')!==false){
                            $json["status"]="検査待ち";
                        }else{
                            $json["status"]="完成処理待ち";
                        }
                    }
                }
            }else{
                //使ってないデータ
                $json["status"]="未使用";
                $json["data"]=[];
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($json);
            exit;
        }

        if($request->getParameter("ac")=="entry"){
            $data=$request->getParameter("d");
            if($data["rounding_num"]=="" || $data["rounding_num"]==0){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode("計算結果を確認してください。");
                exit; 
            }

            $move_date = date("Y-m-d");
            $created_time = date("Y-m-d H:i:s");

            $q="SELECT * FROM hgpd_report hr LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id WHERE hr.hgpd_id = '".$data["sub_hgpd_id"]."' AND wic.wic_complete_flag = '0' AND hr.hgpd_del_flg = '0' ORDER BY wic_id DESC ";
            $st = $con->execute($q);
            $sub_item = $st->fetch(PDO::FETCH_ASSOC);

            $q="SELECT wic_inventry_num FROM work_inventory_control WHERE wic_itemcode = '".$sub_item["wic_itemcode"]."' AND wic_process_key = '".$sub_item["wic_process_key"]."' AND wic_complete_flag = '0' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $sub_inv_num = $st->fetch(PDO::FETCH_ASSOC);

            $qwic_sub = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
            $qwic_sub.= "VALUES ( ";
            $qwic_sub.= "'".$sub_item["hgpd_id"]."',";
            $qwic_sub.= "'".$sub_item["hgpd_id"]."',";
            $qwic_sub.= "'".$sub_item["hgpd_id"]."',";
            $qwic_sub.= "'".$move_date."',";
            $qwic_sub.= "'".$data["user"]."',";
            $qwic_sub.= "'".$sub_item["wic_wherhose"]."',";
            $qwic_sub.= "'".$sub_item["hgpd_rfid"]."',";
            $qwic_sub.= "'".$sub_item["hgpd_itemcode"]."',";
            $qwic_sub.= "'".$sub_item["wic_process_key"]."',";
            $qwic_sub.= "'".$sub_item["wic_process"]."',";
            $qwic_sub.= "'".$sub_item["wic_itemform"]."',";
            $qwic_sub.= "'".$sub_item["wic_itemcav"]."',";
            $qwic_sub.= "'0',";
            $qwic_sub.= "'".$data["rounding_num"]."',";
            $qwic_sub.= "'".(intval($sub_inv_num["wic_inventry_num"])-intval($data["rounding_num"]))."',";
            $qwic_sub.= "'端数寄せ',";          //remark
            $qwic_sub.= "'0',";
            $qwic_sub.= "'".$created_time."')";

            try{
                $con->execute($qwic_sub);
            }catch(Exception $e){
                $err_q.=$e->getMessage();
            }

            $q="SELECT * FROM hgpd_report hr LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id WHERE hr.hgpd_id = '".$data["main_hgpd_id"]."' AND wic.wic_complete_flag = '0' AND hr.hgpd_del_flg = '0' ORDER BY wic_id DESC ";
            $st = $con->execute($q);
            $main_item = $st->fetch(PDO::FETCH_ASSOC);

            $q="SELECT wic_inventry_num FROM work_inventory_control WHERE wic_itemcode = '".$main_item["wic_itemcode"]."' AND wic_process_key = '".$main_item["wic_process_key"]."' AND wic_complete_flag = '0' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $main_inv_num = $st->fetch(PDO::FETCH_ASSOC);

            //在庫調整
            $qwic_main = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
            $qwic_main.= "VALUES ( ";
            $qwic_main.= "'".$main_item["hgpd_id"]."',";
            $qwic_main.= "'".$main_item["hgpd_id"]."',";
            $qwic_main.= "'".$main_item["hgpd_id"]."',";
            $qwic_main.= "'".$move_date."',";
            $qwic_main.= "'".$data["user"]."',";
            $qwic_main.= "'".$main_item["wic_wherhose"]."',";
            $qwic_main.= "'".$main_item["hgpd_rfid"]."',";
            $qwic_main.= "'".$main_item["hgpd_itemcode"]."',";
            $qwic_main.= "'".$main_item["wic_process_key"]."',";
            $qwic_main.= "'".$main_item["wic_process"]."',";
            $qwic_main.= "'".$main_item["wic_itemform"]."',";
            $qwic_main.= "'".$main_item["wic_itemcav"]."',";
            $qwic_main.= "'".$data["rounding_num"]."',";
            $qwic_main.= "'0',";
            $qwic_main.= "'".(intval($main_inv_num["wic_inventry_num"])+intval($data["rounding_num"]))."',";
            $qwic_main.= "'端数寄せ',";          //remark
            $qwic_main.= "'0',";
            $qwic_main.= "'".$created_time."')";

            try{
                $con->execute($qwic_main);
            }catch(Exception $e){
                $err_q.=$e->getMessage();
            }

            if(!$err_q){
                $json="OK";
            }else{
                $json=$err_q;
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($json);
            exit;
        }
    }

    //在庫移動
    public function executeMovingItem(sfWebRequest $request)
    {
        /*データベース接続変更 */
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db;dbname=work',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('在庫移動 ｜ Nalux');

        // 置き場情報収得
        $q="SELECT place_info FROM work_adempiere_item_ms WHERE place_info <> '' AND place_info GROUP BY place_info";
        $st = $con->execute($q);
        $place_info = $st->fetchall(PDO::FETCH_ASSOC);
        $this->all_place = $place_info;
        
        foreach($place_info as $key=>$value){
            if(strpos($value["place_info"],"野洲工場")!==false){
                $stock_place["野洲工場"][] = $value;
            }
            if(strpos($value["place_info"],"山崎工場")!==false){
                $stock_place["山崎工場"][] = $value;
            } 
        }
        // print_r($stock_place);
        // exit;
        $this->stock_place = json_encode($stock_place);

        // RFIDの連携情報収得
        if($request->getParameter("ac")=="getInfoItem"){
            header("Content-Type: application/json; charset=utf-8");

            $q="SELECT * FROM hgpd_report WHERE hgpd_rfid = '".$request->getParameter('rfid')."' AND hgpd_rfid <> '' AND hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $last_rfid = $st->fetch(PDO::FETCH_ASSOC);

            if(!$last_rfid["hgpd_id"]){
                echo json_encode(["NG","RFIDが未登録です。"]);
                exit;
            }
     
            //工程連携ID収得
            $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
            (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_rfid["hgpd_id"].") 
            UNION ALL 
            SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
            SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
            $st = $con->execute($q);
            $check_sub = $st->fetch(PDO::FETCH_ASSOC);
    
            if($check_sub["all_ids"]!=""){
                $list_ids = $check_sub["all_ids"];
            }else{
                $list_ids = $last_rfid["hgpd_id"];
            }
            
            if(!$list_ids){
                echo json_encode(["NG","RFIDが未登録です。"]);
                exit;
            }
    
            //紐づいた数のチェック
            $q = "SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv FROM hgpd_report hr
            LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id 
            WHERE wic_rfid = '".$request->getParameter('rfid')."' AND wic_hgpd_id IN (".$list_ids.") AND wic_complete_flag = '0' AND wic_remark <> '員数不足' AND hr.hgpd_del_flg = '0' 
            ORDER BY wic_id DESC, wic_hgpd_id DESC ";
            $st = $con->execute($q);
            $check_status = $st->fetch(PDO::FETCH_ASSOC);
    
            if($check_status["sum_inv"]>0){
                $q = "SELECT hr.*, wic.*, GROUP_CONCAT(DISTINCT hgpd_id) as hgpd_ids, GROUP_CONCAT(DISTINCT wic_id) as wic_ids,SUM(wic_qty_in) - SUM(wic_qty_out) as now_inventory, (CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as tag_name 
                FROM hgpd_report hr
                    LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id 
                    LEFT JOIN ms_molditem mi ON hr.hgpd_itemcode = mi.itempprocord 
                WHERE wic_rfid = '".$request->getParameter('rfid')."' AND wic_hgpd_id IN (".$list_ids.") AND wic_process = '".$last_rfid["hgpd_process"]."' AND wic_complete_flag = '0' AND wic_remark <> '員数不足' AND hr.hgpd_del_flg = '0' 
                GROUP BY wic_hgpd_id,wic_wherhose 
                HAVING now_inventory > 0 
                ORDER BY wic_id DESC, wic_hgpd_id DESC ";
                $st = $con->execute($q);
                $info_data = $st->fetchall(PDO::FETCH_ASSOC);

                echo json_encode(["OK",$info_data]);
            }elseif($check_status["sum_inv"]==0){
                echo json_encode(["NG","再発行待ち！RFIDが製品と連携されてない状態です。"]);
            }else{
                //紐づいた数<0
                echo json_encode(["NG","データがおかしい！システムの管理者に連絡してください。"]);
            }
            exit;
        }

        //在庫移動の記録
        if($request->getParameter("ac")=="Entry"){

            $pr=$request->getParameter("d");

            foreach ($pr["list_item"] as $grk => $grv) {
                $gr_data[$grv["itemcode"]][]=$grv;
            }
            $created_at = date("Y-m-d H:i:s");
            $move_date = date("Y-m-d");

            foreach($gr_data as $ke=>$val){
                $q="SELECT wic_inventry_num FROM work_inventory_control 
                WHERE wic_wherhose LIKE '%".$pr["plant_name"]."%' AND wic_itemcode = '".$ke."' AND wic_process = '".$val[0]["process"]."' AND wic_process_key = '".$val[0]["process_key"]."' 
                AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
                // print_r($q);
                // exit;
                $st = $con->execute($q);
                $from_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                $q="SELECT wic_inventry_num FROM work_inventory_control 
                WHERE wic_wherhose LIKE '%".$pr["ariver_to"]."%' AND wic_itemcode = '".$ke."' AND wic_process = '".$val[0]["process"]."' AND wic_process_key = '".$val[0]["process_key"]."' AND wic_del_flg = '0' 
                ORDER BY wic_id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $to_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                // print_r($from_inv_num);
                // echo "<br>";
                // print_r($to_inv_num);
                // exit;
                $fin = $from_inv_num["wic_inventry_num"];
                $tin = $to_inv_num["wic_inventry_num"];

                $qwic = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) VALUES ";
                foreach($val as $key=>$value){
                    $fin = intval($fin)-intval($value["num"]);
                    $tin = intval($tin)+intval($value["num"]);
                    $qwic.= "( ";
                    $qwic.= "'".$value["hgpd_id"]."',";
                    $qwic.= "'".$value["hgpd_id"]."',";
                    $qwic.= "'".$value["hgpd_id"]."',";
                    $qwic.= "'".$move_date."',";
                    $qwic.= "'".$pr["user"]."',";
                    $qwic.= "'".$value["from"]."',";
                    $qwic.= "'".$value["rfid"]."',";
                    $qwic.= "'".$value["itemcode"]."',";
                    $qwic.= "'".$value["process_key"]."',";
                    $qwic.= "'".$value["process"]."',";
                    $qwic.= "'".$value["itemform"]."',";
                    $qwic.= "'".$value["itemcav"]."',";
                    $qwic.= "'0',";
                    $qwic.= "'".$value["num"]."',";
                    $qwic.= "'".$fin."',";
                    $qwic.= "'在庫移動出庫',";          //remark
                    $qwic.= "'0',";
                    $qwic.= "'".$created_at."'),";
                    $qwic.= "( ";
                    $qwic.= "'".$value["hgpd_id"]."',";
                    $qwic.= "'".$move_date."',";
                    $qwic.= "'".$pr["user"]."',";
                    $qwic.= "'".$pr["ariver_to"]."',";
                    $qwic.= "'".$value["rfid"]."',";
                    $qwic.= "'".$value["itemcode"]."',";
                    $qwic.= "'".$value["process_key"]."',";
                    $qwic.= "'".$value["process"]."',";
                    $qwic.= "'".$value["itemform"]."',";
                    $qwic.= "'".$value["itemcav"]."',";
                    $qwic.= "'".$value["num"]."',";
                    $qwic.= "'0',";
                    $qwic.= "'".$tin."',";
                    $qwic.= "'在庫移動入庫',";          //remark
                    $qwic.= "'0',";
                    $qwic.= "'".$created_at."'),";
                }
                $qwic=substr($qwic,0,-1);
                $con->execute($qwic);
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            exit;
        }

    }

    public function DitemGroup($ditem, $mode)
    {
        $item=array();
        $total=0;
        $ex = explode(",", $ditem);
        foreach ($ex as $val) {
            $exd = explode("=>", $val);
            if ($exd[0] && $exd[1]>0) {
                $ditemname= mb_convert_kana($exd[0], "ak");
                $item[$ditemname]+=$exd[1];
                $total = $total + $exd[1];
            }
        }
        arsort($item);
        if ($mode=="array") {
            return $item;
        } elseif ($mode=="t_num") {
            return $total;
        } else {
            $d_items="";
            foreach ($item as $key => $num) {
                $d_items .=$key."=>".$num.",";
            }
            $d_items = substr($d_items, "0", "-1");
            return $d_items;
        }
    }

    public function DitemSplitEntry($d)
    {
        new sfDoctrineDatabase(array(
        'name'=>'work',
        'dsn'=>'mysql:host=main-db;',
        'username'=>'nalux-yasu',
        'password'=>'yasu-nalux'
      ));
        $con = Doctrine_Manager::getInstance()->getCurrentConnection();
        $total=0;
        foreach ($d as $item) {
            $id=$item["id"];
            // ホアン 追加 -->
            $price_std=$item["price_std"];
            $single_time  = $item["single_time"];
            // <---
            $defact =$this->DitemGroup($item["defectivesitem"], "array");
            $del_id.=$id.",";
            foreach ($defact as $key=> $ditem) {
                $key = mb_convert_kana($key, 'aKHV');
                $price = $ditem*$price_std; // ホアン　追加
                $time = $ditem*$single_time; // ホアン　追加
                //$arrayValues[] = "('{$id}','{$key}','{$ditem}')";
                $arrayValues[] = "('{$id}','{$key}','{$ditem}','{$price}','{$time}')"; //　ホアン　修正
                $total= $total+ $ditem;
            }

            if ($item["badnum"]!=$total) {
                $q="UPDATE work.xls_work_report SET defact_check=".($item["badnum"]-$total)."WHERE = '$id' ";
                //$st =$con->prepare($q);
                //$st->execute();
            }
        }

        $del_id = substr($del_id, 0, -1);
        $q="DELETE FROM work.xls_work_report_defactiv WHERE xwr_id IN (".$del_id.")";
        $st =$con->prepare($q);
        $st->execute();
        if (count($arrayValues)>0){
            // $q = "INSERT INTO work.xls_work_report_defactiv (xwr_id,xwrd_ditem,xwrd_number,xwrd_price,xwrd_time) VALUES ".join(",", $arrayValues);
            $q = "INSERT INTO work.xls_work_report_defactiv (xwr_id,xwrd_ditem,xwrd_number,xwrd_price,xwrd_time) VALUES ".join(",", $arrayValues); // ホアン　修正
            $st =$con->prepare($q);
            $st->execute();
        }
    }

    // 日付ロットを4桁に変換
    function Dateto4Conv($date)
    {
        $d[0] = substr(date("y",strtotime($date)) , -1);
        $d[1] = date("n",strtotime($date));
        if($d[1]=="10"){
            $d[1] ="X";
        }elseif($d[1]=="11"){
            $d[1] = "Y";
        }elseif($d[1]=="12"){
            $d[1] = "Z";
        }
        $d[2] = date("d",strtotime($date));
        $res = implode("",$d);
        return $res;
    }

    // かんばんPDF作成
    public function executeKanbanPDF(sfWebRequest $request)
    {
        $ac = $request->getParameter("ac");
        $mode = $request->getParameter("mode");
        $p = $request->getParameter("p");

        require '../../lib/vendor/TCPDF/tcpdf.php';
        $h = round(58/0.35);
        $w = round(42/0.35);
        $pageLayout = array($h, $w);
        // 用紙サイズ:58㎜ / 0.35pt =  165.714pt, 50mm /0.35pt = 142.857pt
        $pdf = new TCPDF('l', 'pt', $pageLayout, true, 'UTF-8', false, false);
        // $pdf->SetFont("kozgopromedium", "", 10); // デフォルトで用意されている日本語フォント 

        foreach($p as $item){
            // $pdf->SetFont("meiryo01", "", 10); 
            $pdf->SetMargins(2,2,0);
            $pdf->SetAutoPageBreak(false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $pdf->AddPage(); // 新しいpdfページを追加

            $cellBorder = array(
                'R' => array('width' => 1.2, 'cap' => 'round', 'dash' => 0, 'color' => array(0, 0, 0)),
                'L' => array('width' => 1.2, 'cap' => 'round', 'dash' => 0, 'color' => array(0, 0, 0)),
                'T' => array('width' => 1.2, 'cap' => 'round', 'dash' => 0, 'color' => array(0, 0, 0)),
                'B' => array('width' => 1.2, 'cap' => 'round', 'dash' => 0, 'color' => array(0, 0, 0)),
            );
            
            $row_w = $w-5;
            $lw = 40;
            $rw = $row_w - $lw;
            $row_h = $h;
            $fh = round(20/0.35);
            if($mode=="lot4"){
                $rh = round(($row_h - $fh) / 6);
            }else{
                $rh = round(($row_h - $fh) / 5);
            }
            // $rh = 18;
            // $pdf->writeHTML($html);
            // $pdf->SetFont("meiryo01", "", 11);
            // $pdf->writeHTML($title);
            $pdf->SetFont("meiryo01", "", 11); 
            $pdf->StartTransform();
            $pdf->Rotate(90,($h/2)-23,($w/2)-1);
            // $pdf->MultiCell(1, 2, 3, 4, 5, 6, 7, 8, 9, 10,=> 11, 12, 13, 14, 15, 16);
            $pdf->MultiCell($lw, $rh, '型番', $cellBorder, 'C', 0, 0, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($rw, $rh, $item["form"], $cellBorder, 'C', 0, 1, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($lw, $rh, 'Cav', $cellBorder, 'C', 0, 0, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($rw, $rh, $item["cav"], $cellBorder, 'C', 0, 1, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($lw, $rh, 'Lot№', $cellBorder, 'C', 0, 0, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($rw, $rh, $item["mlot"], $cellBorder, 'C', 0, 1, '', '', true, 0, false, '', '', 'M','M');
            $pdf->SetFont("meiryo01", "", 10);
            $pdf->MultiCell($lw, $rh, '管理ID', $cellBorder, 'C', 0, 0, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($rw, $rh, $item["mid"], $cellBorder, 'C', 0, 1, '', '', true, 0, false, '', '', 'M','M');
            $pdf->SetFont("meiryo01", "", 11); 
            $pdf->MultiCell($lw, $rh, '成形日', $cellBorder, 'C', 0, 0, '', '', true, 0, false, '', '', 'M','M');
            $pdf->MultiCell($rw, $rh, $item["m_date"], $cellBorder, 'C', 0, 1, '', '', true, 0, false, '', '', 'M','M');
            // $pdf->MultiCell($lw, $rh, '数量', $cellBorder, 'C', 0, 0, '', '', true);
            // $pdf->MultiCell($rw, $rh, $item["number"], $cellBorder, 'C', 0, 1, '', '', true);
            if($mode=="lot4"){
                $pdf->MultiCell($lw, $rh, '4桁Lot', $cellBorder, 'C', 0, 0, '', '', true, 0, false, '', '', 'M','M');
                $pdf->MultiCell($rw, $rh, $this->Dateto4Conv($item["m_date"]), $cellBorder, 'C', 0, 1, '', '', true, 0, false, '', '', 'M','M');
            }
            $pdf->Cell($rw+$lw, 50, "", $cellBorder, 'C');
            
            if($mode=="Complete"){
            // QRコード
            $style = array(
                'border'        => false,
                'vpadding'      => 0,
                'hpadding'      => 0,
                'fgcolor'       => array(0,0,0),
                'bgcolor'       => false,
                'module_width'  => 1,
                'module_height' => 1
            );
            // $pdf->writeHTML('&nbsp;ID[QR]');
            // $pdf->write2DBarcode($item["mid"], 'QRCODE,M', 6, 118, 30, 30, $style, 'N');
            }
            $pdf->StopTransform();
        }
        ob_end_clean();
        if($ac=="Print"){
            $base64String = rawurlencode(base64_encode($pdf->Output('kanban.pdf', 'S')));
            echo $base64String;
        }else{
            $pdf->Output('kanban.pdf', 'I');
        }
        return sfView::NONE;
    }

    // ZPL 生産かんばん印刷ロジック
    public function executePrintkanban(sfWebRequest $request)
    {
        // print "<pre>";
        // print_r($_REQUEST);
        // print "</pre>";
        /*データベース接続変更 */
        new sfDoctrineDatabase(array(
            'name' => 'work',
            'dsn' => 'mysql:host=main-db',
            'username' => 'nalux-yasu',
            'password' => 'yasu-nalux',
        ));
        $con = Doctrine_Manager::connection();
        $ac = $request->getParameter("ac");
        $d = $request->getParameter("d");
        $ip = $d["ip"];
        $judge_p = $d["judge_p"];
        $judge_card_type = $d["judge_card_type"];
        $judge_cmt_dt = $d["judge_cmt_dt"];
        $judge_cmt_serial = $d["judge_cmt_serial"];
        foreach($d["kanban"] as $data){
            $form = $data["form"];
            $cav = $data["cav"];
            $lot = $data["lot"];
            $id = $data["id"];
            $m_date = $data["m_date"];
            $m4_lot=$this->Dateto4Conv($m_date);
            $LL="320";
            $FO2="226";
            $LH="0";
            $zpl = "^XA
                ~TA000
                ~JSO
                ^LT0
                ^MNY,0
                ^MFN,N
                ^PW416
                ^LL0400
                ^MTT
                ^POI
                ^PMN
                ^JMA
                ~SD30
                ^LRN
                ^MMT
                ^PW416
                ^LL0400
                ^LS0
                ^LH$LH,0";
                
            if($judge_p=="1"){
                $zpl.="^FO0,220^GB300,0,2^FS";
            }else{
                $zpl.="^FO0,220^GB250,0,2^FS";
            }
            $zpl .="
                ^FO0,0^GB416,$LL,2^FS
                ^FO50,0^GB0,$LL,2^FS
                ^FO100,0^GB0,$LL,2^FS
                ^FO150,0^GB0,$LL,2^FS
                ^FO200,0^GB0,$LL,2^FS
                ^FO250,0^GB0,$LL,2^FS

                ^LH$LH,0
                ^LW0,0
                ^CC|
                |FO10,$FO2|AKB,3,2|CI15|FD型番|FS
                |CC^
                ^FO60,$FO2^AKB,3,2^CI15^FDCav^FS
                ^FO110,$FO2^AKB,3,2^CI15^FDLotNo.^FS
                ^FO160,$FO2^AKB,3,2^CI15^FD管理ID^FS
                ^FO210,$FO2^AKB,3,2^CI15^FD成形日^FS
        
                ^LH$LH,0^LW0,0
                ^FO10,30^AKB,3,2^CI15^FD$form^FS
                ^FO60,30^AKB,3,2^CI15^FD$cav^FS
                ^FO110,30^AKB,3,2^CI15^FD$lot^FS
                ^FO160,30^AKB,3,2^CI15^FD$id^FS
                ^FO210,30^AKB,3,2^CI15^FD$m_date^FS
            ";
            
            if($judge_p=="1"){
                $zpl.="
                    ^FO300,0^GB0,$LL,2^FS
                    ^FO260,$FO2^AKB,3,2^CI15^FD4桁Lot.^FS
                    ^FO260,30^AKB,3,2^CI15^FD$m4_lot^FS
                ";
            }

            if($judge_card_type=="0"){
                // 完成品かんばんにQRコード
                $zpl.="^FO315,220^BQN,2,4^FDMA,$id^FS";
            }else{
                if($judge_cmt_serial=="1"){
                    //仕掛IDの通しNo.の転記
                    $zpl.="
                        ^FO310,$FO2^AKB,3,2^CI15^FD通しNo.^FS
                        ^FO310,30^AKB,3,2^CI15^FD".$data["serial_num"]."^FS
                    ";
                }
                if($judge_cmt_dt=="1"){
                    //仕掛IDの生産時間の転記
                    $zpl.="
                        ^FO350,230^AKB,3,2^CI15^FD開始^FS
                        ^FO380,230^AKB,3,2^CI15^FD終了^FS
                        ^FO350,10^AKB,3,2^CI15^FD".str_replace(" ","_",$data["start_at"])."^FS
                        ^FO380,10^AKB,3,2^CI15^FD".str_replace(" ","_",$data["stop_at"])."^FS
                    ";
                }
            }
            
            $zpl .= "^XZ";

            try{
                // 改行空白削除
                $array = array( ' ', '　', "\r\n", "\r", "\n", "\t" );
                $zpl = str_replace( $array, '', $zpl );
                $fp=pfsockopen($ip,9100);
                $res = fputs($fp,mb_convert_encoding($zpl,"SJIS","UTF-8"));
                fclose($fp);
                echo "OK";
            }catch (Exception $e){
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
            // print "<pre>";
            // print $zpl;
            // print "</pre>";
            sleep(0.2);
        }
        return sfView::NONE;
    }

    // 標準類デジタル化 ファイル検索ロジック
    
    // public function executeStd_digitization(sfWebRequest $request)
    public function Std_digitization($folder, $itemcode, $workitem)
    {
        // 標準類デジタル化対応 Start
        // 新ロジック 検索結果のループが消えて処理の高速化
        $s_key = $folder."_".$itemcode."_". $workitem;
        // $s_key = "KCM5_KCM50052-00_成形.pdf";
        $std_dir="./files/yasu-fs01/DAS連携/PDFデータ/pdf標準類";
        $res = glob("$std_dir/$s_key*");
        if(count($res)===1){
            $ret = array("std_full_path"=>substr($res[0], 1));
        }else{
            $ret = array("std_full_path"=>"err");
        }

        return $ret;
        // return sfView::NONE;
    }

    //プリンター切り替わりの記録
    public function executeUserEventLog(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $pr = $request->getParameter("d");
        $q="INSERT INTO hgpd_report_event_log (hrel_item,hrel_username,hrel_plant,hrel_recode,hrel_ipaddr,hrel_remark,hrel_created_at) VALUES ('".$pr["item"]."','".$pr["user"]."','".$pr["plant"]."','".$pr["log"]."','".$pr["client_ip"]."','".$pr["remark"]."','".date("Y-m-d H:i:s")."') ";
        $con->execute($q);
        exit;
    }

    public function executeRePrintKanban(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('再印刷 ｜ Nalux');

        if($request->getParameter("ac")=="kanban_reprint"){
            $rfid = $request->getParameter("rfid");
            $printer_ip = $request->getParameter("printer_ip");
    
            // 最新RFID情報を抽出
            $q="SELECT * FROM hgpd_report 
            WHERE hgpd_rfid = '".$rfid."' AND hgpd_rfid <> '' AND (hgpd_process LIKE '%成形%' OR hgpd_process = '完成品処理') AND hgpd_del_flg = '0' 
            ORDER BY hgpd_id DESC LIMIT 1";
            $st = $con->execute($q);
            $last_id = $st->fetch(PDO::FETCH_ASSOC);
      
            if($last_id){
                if($last_id["hgpd_process"]!="完成品処理"){
                    // 仕掛品IDの場合:同じの通しNo.データを抽出
                    $q="SELECT hr.*, ut.hrut_serial_num, mi.dateto4, mi.mold_datetime_print, mi.mold_serial_print 
                    FROM hgpd_report hr 
                    LEFT JOIN hgpd_report_unit_tray ut ON ut.hgpd_id = hr.hgpd_id 
                    LEFT JOIN ms_molditem mi ON mi.itempprocord = hr.hgpd_itemcode  
                    WHERE hr.xwr_id = '".$last_id["xwr_id"]."' AND hr.hgpd_del_flg = '0' 
                    GROUP BY hr.hgpd_id ";
                    $st = $con->execute($q);
                    $res = $st->fetchAll(PDO::FETCH_ASSOC);
                }else{
                    //成形日抽出
                    // $qs="SELECT GROUP_CONCAT(DISTINCT hr.hgpd_moldday) as hgpd_molddays 
                    // FROM hgpd_report_sub hs LEFT JOIN hgpd_report hr ON hr.hgpd_id = hs.hgpd_before_id 
                    // WHERE  hs.hgpd_complete_id = '".$last_id["hgpd_id"]."' ORDER BY hgpd_id ASC ";
                    // $st = $con->execute($qs);
                    // $mold_days = $st->fetch(PDO::FETCH_ASSOC);

                    // 完成品IDの場合:最新データを抽出
                    $last_id["hrut_serial_num"]="-";
                    // $last_id["hgpd_moldday"]=$mold_days["hgpd_molddays"];
                    $res[] = $last_id;
                }
            }else{
                $res = []; 
            }
    
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($res);
            exit;
        }

        $plant = $request->getParameter("plant");
        $q="SELECT * FROM work_ipaddr_manarger WHERE wim_type LIKE '%ipad用プリンター%' AND wim_plant = '".$plant."' ";
        $st = $con->execute($q);
        $printer_list = $st->fetchall(PDO::FETCH_ASSOC);
        $this->printer_list=$printer_list;

    }

    //棚卸CSV出力
    public function executeInventoryCheckCSV(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $plant_name = $request->getParameter("plant");
        $ed = $request->getParameter("ed");
        $time = $request->getParameter("time");
        if($ed==""){
            $ed=date("Y-m-d");
        }
        if($time==""){
            $time = "00:00:00";
        }
        $datetime = $ed." ".$time;

        //DASから棚卸リスト作成(今回テストの為2製品しかない：KCM50037-00、KCM50049-00、KCM50059-00)
        // $list_inventory_itemcode = "'KCM50037-00','KCM50049-00','KCM50059-00'";
        //2024-09-27　棚卸製品を追加 ('KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00')
        //2024-10-29　棚卸製品を追加 ('APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00')
        // $list_inventory_itemcode = "'KCM50037-00','KCM50049-00','KCM50059-00','KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00','APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00','APH50002-00'";
        //2022-02-11　棚卸製品を追加:山崎→全製品対応
        $list_inventory_itemcode = "'KDN50010-00'";

        $q="SELECT * FROM work_inventory_control WHERE wic_wherhose LIKE '%".$plant_name."%' AND wic_created_at <= '".$datetime."' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
        $st = $con->execute($q);
        $last_inventory = $st->fetch(PDO::FETCH_ASSOC);
        
        $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant_name."' ORDER BY wir_id DESC LIMIT 1 ";
        $st = $con->execute($q);
        $wir_id = $st->fetch(PDO::FETCH_ASSOC);

        if($wir_id && $wir_id["wir_process_lot"]==date("Ym")){
            $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant_name."' AND wir_process_lot < '".date("Ym")."' ORDER BY wir_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $wir_id = $st->fetch(PDO::FETCH_ASSOC);
        }
        
        if($wir_id){
            $last_max_id = $wir_id["wir_max_wic_id"];
        }else{
            $last_max_id = 0;
        }
        // $last_max_id = 0;
        // $wir_id=null;

        //DASから在庫の最終誤差作成
        if(!$wir_id){
            $q="SELECT
                *
                , SUM(t2.proc_inv_num) as sum_inv_num 
            FROM
                ( 
                    SELECT
                        MIN(wic_id) as min_wic_id
                        , MAX(wic_id) as max_wic_id
                        , MAX(wic_date) as last_wic_date
                        , wic_rfid
                        , wic_itemcode
                        , wic_process_key
                        , wic_process
                        , wic_name
                        , SUM(wic_qty_in) - SUM(wic_qty_out) as proc_inv_num
                        , ( 
                            CASE 
                                WHEN mi.searchtag <> '' 
                                    THEN mi.searchtag 
                                ELSE mi.itemname 
                                END
                        ) as tag_name
                        , ( 
                            CASE 
                                WHEN wic_process LIKE '%成形%' 
                                    THEN '1' 
                                WHEN wic_process LIKE '%最終%' 
                                    THEN '2' 
                                WHEN wic_process LIKE '%完成%' 
                                    THEN '3' 
                                END
                        ) as order_num
                        , wic_wherhose 
                    FROM
                        work_inventory_control wic 
                        LEFT JOIN ms_molditem mi 
                            ON mi.itempprocord = wic.wic_itemcode 
                    WHERE
                        wic_wherhose LIKE '%".$plant_name."%' 
                        AND wic_remark <> '員数不足​' 
                        AND wic_id > '".$last_max_id."' 
                        AND wic_id <= '".$last_inventory["wic_id"]."' 
                        AND wic_name NOT LIKE '%TEST%'
                        AND LENGTH(wic.wic_rfid) = 32 ";
                        if($plant_name=="野洲工場"){
                            $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
                        }
                        $q.="AND wic_remark NOT LIKE '%棚卸修正%' 
                        AND wic_del_flg = '0' 
                    GROUP BY
                        wic_rfid
                        , wic_process_key 
                    HAVING
                        proc_inv_num <> '0' 
                    ORDER BY
                        wic_itemcode ASC
                        , wic_id DESC
                ) t2 
            GROUP BY
                t2.wic_rfid, t2.wic_process_key 
            ORDER BY
                t2.wic_itemcode ASC
                , t2.order_num ASC 
                , t2.max_wic_id ASC";
        }else{
            $q="WITH RECURSIVE temp1 AS ( 
                ( 
                    SELECT
                        t1.wic_rfid
                        , t1.wic_itemcode
                        , t1.wic_process_key
                        , t1.wic_process
                        , t1.tag_name
                        , t1.order_num
                        , t1.wic_wherhose
                        , SUM(t1.proc_inv_num) as inv_num 
                    FROM
                        ( 
                            SELECT
                                wic_rfid
                                , wic_itemcode
                                , wic_process_key
                                , wic_process
                                , ( 
                                    CASE 
                                        WHEN mi.searchtag <> '' 
                                            THEN mi.searchtag 
                                        ELSE mi.itemname 
                                        END
                                ) as tag_name
                                , ( 
                                    CASE 
                                        WHEN wic_process LIKE '%成形%' 
                                            THEN '1' 
                                        WHEN wic_process LIKE '%最終%' 
                                            THEN '2' 
                                        WHEN wic_process LIKE '%完成%' 
                                            THEN '3' 
                                        END
                                ) as order_num
                                , wic_wherhose
                                , SUM(wic_qty_in) - SUM(wic_qty_out) as proc_inv_num 
                            FROM
                                work_inventory_control wic 
                                LEFT JOIN ms_molditem mi 
                                    ON mi.itempprocord = wic.wic_itemcode 
                            WHERE
                                wic_wherhose LIKE '%".$plant_name."%' 
                                AND wic_remark <> '員数不足​'
                                AND wic_id > '".$last_max_id."' 
                                AND wic_id <= '".$last_inventory["wic_id"]."' 
                                AND wic_name NOT LIKE '%TEST%' 
                                AND LENGTH(wic.wic_rfid) = 32 ";
                                if($plant_name=="野洲工場"){
                                    $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
                                }
                                $q.="AND wic_remark NOT LIKE '%棚卸修正%' 
                                AND wic_del_flg = '0' 
                            GROUP BY
                                wic_rfid
                                , wic_process_key 
                            HAVING
                                proc_inv_num <> 0 
                            ORDER BY
                                wic_itemcode ASC
                                , wic_id DESC
                        ) t1 
                    GROUP BY
                        t1.wic_rfid 
                        , t1.wic_process_key 
                    ORDER BY
                        t1.wic_itemcode ASC
                        , t1.order_num ASC
                ) 
                UNION ALL 
                SELECT
                    t2.wird_rfid as wic_rfid
                    , t2.wird_itemcode as wic_itemcode
                    , t2.wird_process_key as wic_process_key
                    , t2.wird_process as wic_process
                    , t2.wird_tag_name as tag_name
                    , t2.wird_order_num as order_num
                    , t2.wird_wherhose as wic_wherhose
                    , t2.wird_inv_num as proc_inv_num 
                FROM
                    work_inventory_result_details t2 
                WHERE
                    t2.wird_wir_id = '".$wir_id["wir_id"]."' 
            ) 
            SELECT
                *
                , SUM(inv_num) as sum_inv_num 
            FROM
                temp1 
            WHERE 
                LENGTH(wic_rfid) = 32 ";
                if($plant_name=="野洲工場"){
                    $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
                }
            $q.="GROUP BY
                wic_rfid, wic_process_key 
            HAVING 
                sum_inv_num <> '0'
            ORDER BY
                wic_itemcode ASC
                , order_num ASC ";
        }
        // print_r($q);
        // exit;
        $st = $con->execute($q);
        $res = $st->fetchAll(PDO::FETCH_ASSOC);

        if(count($res)==0){
            print_r("データがありません。日時を再確認してください。");
            exit;
        }
   
        
        $csv='"資産No","資産名","資産バーコード","資産タグ","保管場所コード","保管場所名","読取保管場所コード","棚卸状態","棚卸日時","ユーザID"'."\n";
        // $max_wic_id=0;
        foreach($res as $key=>$val){
            // if($val["wic_id"]>$max_wic_id){
            //     $max_wic_id = $val["wic_id"];
            // }
            if($val["wic_process_key"]=="0"){
                $itemcode = $val["wic_itemcode"];
            }else{
                $itemcode = $val["wic_itemcode"].$val["wic_process_key"];
            }
            $csv.='"'.$itemcode.'","'.$val["tag_name"].'","","'.$val["wic_rfid"].'","'.$val["wic_wherhose"].'","'.$val["sum_inv_num"].'","","","",""'."\n";
        }

        // print_r($csv);
        // exit;

        $filename = "棚卸DAS出力_".$plant_name."_".$last_inventory["wic_id"]."_".date("ymd").".csv";
        $filename = mb_convert_encoding($filename,"SJIS", "UTF-8");
        $lines = mb_convert_encoding($csv,"SJIS", "UTF-8");
        $response = $this->getContext()->getResponse();
        $response->setHttpHeader('Pragma', '');
        $response->setHttpHeader('Cache-Control', '');
        $response->setHttpHeader('Content-Type', 'application/vnd.ms-excel');
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="'.$filename.'"');
        $response->setContent($lines);
        return sfView::NONE;

        exit;
    }

    public function executeInventoryProcessingCSV(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('棚卸結果処理 ｜ Nalux');

        if($request->getParameter("ac")=="getNoRfid"){
            $plant=$request->getParameter("plant");
            $max_id=$request->getParameter("max_id");
            // print_r($max_id);
            // exit;
            
            //2024-09-27　棚卸製品を追加 ('KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00')
            //2024-10-29　棚卸製品を追加 ('APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00')
            // $list_inventory_itemcode = "'KCM50037-00','KCM50049-00','KCM50059-00','KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00','APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00','APH50002-00'";
            //2022-02-11　棚卸製品を追加:山崎→全製品対応
            $list_inventory_itemcode = "'KDN50010-00'";

            //DASの数量を抽出
            $q="SELECT wic.*, mi.itemname, (CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as search_tag, (CASE WHEN wic_complete_flag = '1' THEN '完成品' ELSE '仕掛品' END) as item_type "; 
            $q.=",SUM(wic_qty_in) as sum_in, SUM(wic_qty_out) as sum_out, SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num ";
            $q.=",(CASE WHEN wic_process_key <> '0' THEN CONCAT(wic_itemcode,wic_process_key) ELSE wic_itemcode END) as 品目コード ";
            $q.=",GROUP_CONCAT(DISTINCT wic_hgpd_id) as hgpd_ids ";
            $q.="FROM work_inventory_control wic LEFT JOIN ms_molditem mi ON wic.wic_itemcode = mi.itempprocord ";
            $q.="WHERE wic.wic_wherhose LIKE '%".$plant."%' ";
            // $q.="AND wic_itemcode LIKE '%' ";
            if($plant=="野洲工場"){
                $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
            }
            $q.="AND wic_remark <> '員数不足​' AND length(wic_rfid) = '24' AND wic_name NOT LIKE '%TEST%' AND wic_del_flg = '0' ";
            $q.="GROUP BY wic_itemcode,wic_process, wic_process_key, wic_complete_flag ";
            $q.="HAVING inv_num > 0 ";
            $q.="ORDER BY wic_itemcode ASC, wic_complete_flag ASC, wic_process_key DESC";
            // print_r($q);
            // exit;
            $st=$con->execute($q);
            $list_item = $st->fetchall(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($list_item);

            exit;
        }
        
        if($request->getParameter("ac")=="getDeff"){
            $rfids=$request->getParameter("rfids");

            $rfids = implode("','",explode(",",$rfids));
            // print_r($rfids);
            // exit;

            $q="SELECT *,SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num 
            FROM work_inventory_control 
            WHERE wic_rfid IN('".$rfids."') AND wic_remark <> '員数不足​' AND wic_del_flg = '0' 
            GROUP BY wic_rfid 
            ORDER BY wic_id DESC ";
            // print_r($q);
            // exit;
            $st = $con->execute($q);
            $list_deff = $st->fetchall(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            if($request->getParameter("mode")=="unknow"){
                if($list_deff){
                    foreach($list_deff as $k=>$v){
                        $list_has[] = $v["wic_rfid"] ;
                    }
                }else{
                    $list_has=[]; 
                }
       
                echo json_encode($list_has);
            }else{
                echo json_encode($list_deff);
            }
            exit;

        }

        if($request->getParameter("ac")=="asyncAdempiere"){
            $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
            $db["user"] = 'adempiere';
            $db["password"] = 'adempiere';
            $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);
            
            // ADM在庫データ抽出
            $q= "SELECT * FROM m_inventory iv LEFT JOIN m_inventoryline il ON il.m_inventory_id = iv.m_inventory_id LEFT JOIN m_product prd ON prd.m_product_id = il.m_product_id ";
            $q.="WHERE iv.documentno = '646529' AND iv.ad_org_id IN ('1000073','1000079') ";
            // $q.="GROUP BY documentno,movementdate,bp_value,pl_name ";
            // $q.="ORDER BY movementdate ASC ";

            // print_r($q);
            // exit;
            $st = $dbh->query($q);
            $ship_list = $st->fetchall(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($ship_list);
            exit;
        }

        //DAS在庫データ減らす
        if($request->getParameter("ac")=="inventoryUpdate"){
            $rfids=$request->getParameter("rfids");
            $user = $request->getParameter("user");
            $created_at = date("Y-m-d H:i:s");
            // $str_rfids = implode("','",$rfids);

            $qo ="INSERT INTO work_inventory_control (wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_hgpd_id,wic_complete_id,wic_before_id,wic_complete_flag,wic_created_at) VALUES ";
            foreach($rfids as $key => $value){
                $q="SELECT hr.hgpd_id, wic.wic_complete_flag FROM hgpd_report hr LEFT JOIN work_inventory_control wic ON wic.wic_hgpd_id = hr.hgpd_id WHERE hgpd_rfid = '".$value."' AND hr.hgpd_del_flg = '0' AND wic.wic_del_flg = '0' ORDER BY hgpd_id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $last_id = $st->fetch(PDO::FETCH_ASSOC);

                if($last_id["wic_complete_flag"] == "0"){
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                    (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_id["hgpd_id"].") 
                    UNION ALL 
                    SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);
    
                    if($check_sub["all_ids"]!=""){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $last_id["hgpd_id"];
                    }
                }else{
                    $list_ids = $last_id["hgpd_id"];
                }

                $q="SELECT *, SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control WHERE wic_hgpd_id IN (".$list_ids.") AND wic_del_flg = '0' GROUP BY wic_process_key HAVING inv_num > 0";
                // print_r($q);
                // exit;
                $st = $con->execute($q);
                $out_list = $st->fetchall(PDO::FETCH_ASSOC);

                foreach($out_list as $k=>$val){
                    //在庫数の計算
                    $q="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control WHERE wic_itemcode = '".$val["wic_itemcode"]."' AND wic_process_key = '".$val["wic_process_key"]."' AND wic_complete_flag = '".$val["wic_complete_flag"]."' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
                    $st = $con->execute($q);
                    $inv_num = $st->fetch(PDO::FETCH_ASSOC);

                    $now_inv_num = intval($inv_num["inv_num"])-intval($val["inv_num"]);

                    $qo.= "('".date("Y-m-d")."',";
                    $qo.= "'".$user."',";
                    $qo.= "'".$val["wic_wherhose"]."',";
                    $qo.= "'".$val["wic_rfid"]."',";
                    $qo.= "'".$val["wic_itemcode"]."',";
                    $qo.= "'".$val["wic_process_key"]."',";
                    $qo.= "'".$val["wic_process"]."',";
                    $qo.= "'".$val["wic_itemform"]."',";
                    $qo.= "'".$val["wic_itemcav"]."',";
                    $qo.= "'0',";
                    $qo.= "'".$val["inv_num"]."',";
                    $qo.= "'".$now_inv_num."',";
                    $qo.= "'棚卸修正',";          //remark
                    $qo.= "'".$val["wic_hgpd_id"]."',";
                    $qo.= "'".$val["wic_hgpd_id"]."',";
                    $qo.= "'".$val["wic_hgpd_id"]."',";
                    $qo.= "'".$val["wic_complete_flag"]."',";
                    $qo.= "'".$created_at."'),";

                    $list_rfid.="'".$val["wic_rfid"]."',";
                }
            }
            // print_r($qo);
            // exit;

            header("Content-Type: application/json; charset=utf-8");
            try{
                //在庫履歴登録実行
                $qo=substr($qo,0,-1);
                $con->execute($qo);

                //IDの状態の更新
                $list_rfid=substr($list_rfid,0,-1);
                $qs = "UPDATE work_id_manager SET wim_status = '再発行' WHERE rfid IN (".$list_rfid.") ";
                $con->execute($qs);
            }catch (Exception $e){
                $err.=$e->getMessage();
            }

            if($err){
                echo json_encode($err);
            }else{
                echo json_encode("OK");
            }
            exit;
        }

        if($request->getParameter("ac")=="addUnknowData"){
            $data = $_POST;
            $plant=$request->getParameter("plant");
            $created_at = date("Y-m-d H:i:s");
            
            $res_data = [];

            $qr ="INSERT INTO hgpd_report (xwr_id, hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_cav, hgpd_itemform,hgpd_moldlot,hgpd_worklot,hgpd_checkday,hgpd_moldday,hgpd_quantity,hgpd_qtycomplete,";
            $qr.= "hgpd_namecode,hgpd_name,hgpd_start_at,hgpd_stop_at,hgpd_exclusion_time,hgpd_working_hours,hgpd_volume,hgpd_cycle,hgpd_rfid,created_at) ";
            $qr.= "VALUES ";
            foreach($data as $key=>$value){
                $qr.= "('',";
                $qr.= "'".$plant."',";
                $qr.= "'".$value["workitem"]."',";
                $qr.= "'".$value["itemcode"]."',";
                $qr.= "'".$value["itemcav"]."',";
                $qr.= "'".$value["formnum"]."',";
                $qr.= "'".$value["moldlot"]."',";
                $qr.= "'".$value["hgpd_worklot"]."',"; //???
                $qr.= "'".$value["workdate"]."',";
                $qr.= "'".$value["molddate"]."',";
                $qr.= "'".$value["allnum"]."',";
                $qr.= "'".$value["goodnum"]."',";
                $qr.= "'".$value["ppro"]."',";
                $qr.= "'".$value["user_name"]."',";
                $qr.= "'".$value["start_time"]."',";
                $qr.= "'".$value["end_time"]."',";
                $qr.= "'',";
                $qr.= "'".(intval($value["worktime"])/60)."',";
                $qr.= "'".(intval($value["allnum"])/(intval($value["worktime"])/60))."',";
                $qr.= "'".((intval($value["worktime"])*60)/intval($value["allnum"]))."',";
                $qr.= "'".$value["rfid"]."',";                  //hgpd_rfid
                $qr.= "'".$created_at."'),";

                $qr=substr($qr,0,-1);
                // echo "<pre>";
                // print_r($qr);
                // exit;
                $con->execute($qr);

                $q="SELECT hgpd_id FROM hgpd_report WHERE hgpd_rfid = '".$value["rfid"]."' ORDER BY hgpd_id DESC ";
                $st = $con->execute($q);
                $hgpd_id = $st->fetch(PDO::FETCH_ASSOC);

                //不良内容記録
                $item=array();
                $ex = explode(",", $value["badinfo"]);
                foreach ($ex as $val) {
                    $exd = explode("=>", $val);
                    if($exd[0] && $exd[1] > 0){
                        $item[$exd[0]] = $exd[1];
                    }
                }
                if($item){
                    $q3 = "INSERT INTO hgpd_report_defectiveitem ";
                    $q3.= "(hgpd_id,hgpdd_ditem,hgpdd_qty,hgpdd_price,hgpdd_time) ";
                    $q3.= "VALUES (";
                    foreach($item as $k=>$v){
                        $q3.= "'".$hgpd_id["hgpd_id"]."',";
                        $q3.= "'".$k."',";
                        $q3.= "'".$v."',";
                        $q3.= "'',";
                        $q3.= "''),(";
                    }
                    $q3 = substr($q3, 0,-2);
                    $con->execute($q3);
                }

                $q="SELECT wic_inventry_num FROM work_inventory_control 
                WHERE wic_itemcode = '".$value["itemcode"]."' AND wic_process = '".$value["workitem"]."' AND wic_process_key = '".$value["workitem_key"]."' AND wic_complete_flag = '0' AND wic_del_flg = '0' 
                ORDER BY wic_id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $sum_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                //入出庫記録
                $qwic_in = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                $qwic_in.= "VALUES ( ";
                $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
                $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
                $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
                $qwic_in.= "'".$value["workdate"]."',";
                $qwic_in.= "'".$value["user_name"]."',";
                $qwic_in.= "'".$plant."',";
                $qwic_in.= "'".$value["rfid"]."',";
                $qwic_in.= "'".$value["itemcode"]."',";
                $qwic_in.= "'".$value["workitem_key"]."',";
                $qwic_in.= "'".$value["workitem"]."',";
                $qwic_in.= "'".$value["formnum"]."',";
                $qwic_in.= "'".$value["itemcav"]."',";
                $qwic_in.= "'".$value["goodnum"]."',";
                $qwic_in.= "'0',";
                $qwic_in.= "'".(intval($sum_inv_num["wic_inventry_num"])+intval($value["goodnum"]))."',";
                $qwic_in.= "'".$value["workitem"]."入庫(棚卸修正)',";          //remark
                $qwic_in.= "'0',";
                $qwic_in.= "'".$created_at."')";
    
                // echo "<pre>";
                // print_r($qwic_in);
                // exit;
                $con->execute($qwic_in);

                $qs = "UPDATE work_id_manager SET wim_status = '使用中' WHERE rfid = '".$value["rfid"]."' ";
                $con->execute($qs);

                $res_data[]=["rfid"=>$value["rfid"],"itemcode"=>$value["itemcode"].$value["workitem_key"],"itemname"=>$value["itemname"],"position"=>$plant,"num"=>$value["goodnum"]];

            }

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode(["OK",$res_data]);
            exit;
        }

    }

    //棚卸結果保存
    public function executeInventoryResults(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $ac =  $request->getParameter("ac");
        $mode =  $request->getParameter("mode");

        $max_id =  $request->getParameter("max_id");    //75630
        if($max_id==""){
            print_r("最大IDが無い！");
            exit;
        }
        $plant_name = $request->getParameter("plant");
        if($plant_name==""){
            $plant_name="山崎工場";
        }
        $user_name = $request->getParameter("username");

        $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant_name."' ORDER BY wir_id DESC LIMIT 1 ";
        $st = $con->execute($q);
        $wir_id = $st->fetch(PDO::FETCH_ASSOC);

        if($wir_id && $wir_id["wir_process_lot"]==date("Ym")){
            $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant_name."' AND wir_process_lot < '".date("Ym")."' ORDER BY wir_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $wir_id = $st->fetch(PDO::FETCH_ASSOC);
        }

        //2024-09-27　棚卸製品を追加 ('KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00')
        //2024-10-29　棚卸製品を追加 ('APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00')
        // $list_inventory_itemcode = "'KCM50037-00','KCM50049-00','KCM50059-00','KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00','APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00','APH50002-00'";
        //2022-02-11　棚卸製品を追加:山崎→全製品対応
        $list_inventory_itemcode = "'KDN50010-00'";

        //DASから在庫の最終誤差作成
        if(!$wir_id){
            $q="SELECT
                *
                , SUM(t2.proc_inv_num) as sum_inv_num 
            FROM
                ( 
                    SELECT
                        MIN(wic_id) as min_wic_id
                        , MAX(wic_id) as max_wic_id
                        , MAX(wic_date) as last_wic_date
                        , wic_rfid
                        , wic_itemcode
                        , wic_process_key
                        , wic_process
                        , wic_name
                        , SUM(wic_qty_in) - SUM(wic_qty_out) as proc_inv_num
                        , ( 
                            CASE 
                                WHEN mi.searchtag <> '' 
                                    THEN mi.searchtag 
                                ELSE mi.itemname 
                                END
                        ) as tag_name
                        , ( 
                            CASE 
                                WHEN wic_process LIKE '%成形%' 
                                    THEN '1' 
                                WHEN wic_process LIKE '%最終%' 
                                    THEN '2' 
                                WHEN wic_process LIKE '%完成%' 
                                    THEN '3' 
                                END
                        ) as order_num
                        , wic_wherhose 
                    FROM
                        work_inventory_control wic 
                        LEFT JOIN ms_molditem mi 
                            ON mi.itempprocord = wic.wic_itemcode 
                    WHERE
                        wic_wherhose LIKE '%".$plant_name."%' 
                        AND wic_remark <> '員数不足​' 
                        AND wic_name NOT LIKE '%TEST%' 
                        AND wic_del_flg = '0' ";
                        $q.="AND (wic_id <= '".$max_id."' OR wic.wic_remark LIKE '%棚卸修正%') ";
                        if($mode=="getNoRfid"){
                            $q.="AND length(wic_rfid) = '24' ";
                        }
                    $q.="GROUP BY
                        wic_rfid
                        , wic_process_key 
                    HAVING
                        proc_inv_num <> 0 
                    ORDER BY
                        wic_itemcode ASC
                        , wic_id DESC
                ) t2 ";
            if($ac=="entry"){
                $q.="GROUP BY t2.wic_rfid, t2.wic_process_key ";
            }else{
                if($mode=="getNoRfid"){
                    $q.="WHERE length(t2.wic_rfid) = '24' ";
                    if($plant_name=="野洲工場"){
                        $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
                    }
                }
                $q.="GROUP BY t2.wic_itemcode, t2.wic_process_key ";
            }
            $q.="ORDER BY
                t2.wic_itemcode ASC
                , t2.order_num ASC 
                , t2.max_wic_id ASC ";
        }else{
            $q="WITH RECURSIVE temp1 AS ( 
                ( 
                    SELECT
                        t1.wic_rfid
                        , t1.wic_itemcode
                        , t1.wic_process_key
                        , t1.wic_process
                        , t1.tag_name
                        , t1.order_num
                        , t1.wic_wherhose
                        , SUM(t1.proc_inv_num) as inv_num 
                    FROM
                        ( 
                            SELECT
                                wic_rfid
                                , wic_itemcode
                                , wic_process_key
                                , wic_process
                                , ( 
                                    CASE 
                                        WHEN mi.searchtag <> '' 
                                            THEN mi.searchtag 
                                        ELSE mi.itemname 
                                        END
                                ) as tag_name
                                , ( 
                                    CASE 
                                        WHEN wic_process LIKE '%成形%' 
                                            THEN '1' 
                                        WHEN wic_process LIKE '%最終%' 
                                            THEN '2' 
                                        WHEN wic_process LIKE '%完成%' 
                                            THEN '3' 
                                        END
                                ) as order_num
                                , wic_wherhose
                                , SUM(wic_qty_in) - SUM(wic_qty_out) as proc_inv_num 
                            FROM
                                work_inventory_control wic 
                                LEFT JOIN ms_molditem mi 
                                    ON mi.itempprocord = wic.wic_itemcode 
                            WHERE
                                wic_wherhose LIKE '%".$plant_name."%' 
                                AND wic_remark <> '員数不足​'
                                AND wic_name NOT LIKE '%TEST%' 
                                AND wic_del_flg = '0' ";
                                $q.="AND wic_id > '".$wir_id["wir_max_wic_id"]."' ";
                                $q.="AND (wic_id <= '".$max_id."' OR wic.wic_remark LIKE '%棚卸修正%') ";
                                if($mode=="getNoRfid"){
                                    $q.="AND length(wic_rfid) = '24' ";
                                }

                            $q.="GROUP BY
                                wic_rfid
                                , wic_process_key 
                            HAVING
                                proc_inv_num <> 0 
                            ORDER BY
                                wic_itemcode ASC
                                , wic_id DESC
                        ) t1 
                    GROUP BY
                        t1.wic_rfid 
                        , t1.wic_process_key 
                    ORDER BY
                        t1.wic_itemcode ASC
                        , t1.order_num ASC
                ) 
                UNION ALL 
                SELECT
                    t2.wird_rfid as wic_rfid
                    , t2.wird_itemcode as wic_itemcode
                    , t2.wird_process_key as wic_process_key
                    , t2.wird_process as wic_process
                    , t2.wird_tag_name as tag_name
                    , t2.wird_order_num as order_num
                    , t2.wird_wherhose as wic_wherhose
                    , t2.wird_inv_num as proc_inv_num 
                FROM
                    work_inventory_result_details t2 
                WHERE
                    t2.wird_wir_id = '".$wir_id["wir_id"]."' 
            ) 
            SELECT
                *
                , SUM(inv_num) as sum_inv_num 
            FROM
                temp1 ";
            if($ac=="entry"){
                $q.="GROUP BY wic_rfid, wic_process_key ";
            }else{
                if($mode=="getNoRfid"){
                    $q.="WHERE length(wic_rfid) = '24' ";
                    if($plant_name=="野洲工場"){
                        $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
                    }else{
                        $q.="AND wic_itemcode IN ('') ";
                    }
                }
                $q.="GROUP BY wic_itemcode, wic_process_key ";
            }

            $q.="HAVING 
                sum_inv_num <> 0
            ORDER BY
                wic_itemcode ASC
                , order_num ASC ";
        }

        if($ac=="entry"){
            $st = $con->execute($q);
            $res = $st->fetchAll(PDO::FETCH_ASSOC);

            // echo json_encode("OK");
            // exit;

            $created_at=date("Y-m-d H:i:s");

            $qs="INSERT INTO work_inventory_results (wir_process_lot,wir_date,wir_plant,wir_max_wic_id,wir_tag_num,wir_username,wir_created_at) VALUES ";
            $qs.="('".date("Ym")."','".date("Y-m-d")."','".$plant_name."','".$max_id."','".count($res)."','".$user_name."','".$created_at."')";
            $con->execute($qs);

            $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant_name."' ORDER BY wir_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $last_wir_id = $st->fetch(PDO::FETCH_ASSOC);

            $qi="INSERT INTO work_inventory_result_details (wird_wir_id,wird_rfid,wird_itemcode,wird_process_key,wird_process,wird_tag_name,wird_order_num,wird_wherhose,wird_inv_num,wird_created_at) VALUES ";
            foreach($res as $key=>$val){
                $qi.= "('".$last_wir_id["wir_id"]."',";
                $qi.= "'".$val["wic_rfid"]."',";
                $qi.= "'".$val["wic_itemcode"]."',";
                $qi.= "'".$val["wic_process_key"]."',";
                $qi.= "'".$val["wic_process"]."',";
                $qi.= "'".$val["tag_name"]."',";
                $qi.= "'".$val["order_num"]."',";
                $qi.= "'".$val["wic_wherhose"]."',";
                $qi.= "'".$val["sum_inv_num"]."',";
                $qi.= "'".$created_at."'),";
            }

            $qi=substr($qi,0,-1);
            $con->execute($qi);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            exit;

        }elseif($ac=="getInfo"){
            $st = $con->execute($q);
            $res = $st->fetchAll(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($res);

            exit;
        }else{
            print_r($ac);
            echo "<br>";
            print_r($mode);
            echo "<br>";
            print_r($q);
            exit;
        }

        exit;

    }
    
    public function executeAdempiereInventoryUpdate(sfWebRequest $request)
    {
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $db=array();
        $db["dsn"] = 'pgsql:dbname=adempiere3 host=naps-nalux.net port=5432';
        $db["user"] = 'adempiere';
        $db["password"] = 'adempiere';
        $dbh = new PDO($db["dsn"] , $db["user"], $db["password"]);

        $this->getResponse()->setTitle('Adempiere在庫連携 ｜ Nalux');

        if($request->getParameter("ac")=="admUpdate"){
            //ADM在庫更新

            //ADM入出庫更新

        }

        if($request->getParameter("ac")=="getAdmInventory"){
            $plant = $request->getParameter("plant");
            $list_item = $request->getParameter("itemcodes");
            $max_id = $request->getParameter("maxid");
            // $d = $request->getParameter("post_data");
            // print_r($d);
            // exit;
            // print_r($max_id);
            // exit;
            $sp_list_item=explode(",",$list_item);
            $list_search=implode("','",$sp_list_item);

            // DAS在庫データ収得(wic_inventry_num)
            // $q="SELECT t2.*,adm_ms.m_product_id,adm_ms.proccess_name,(CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as tag_name, 
            // (CASE WHEN t2.wic_process_key <> '0' THEN CONCAT(t2.wic_itemcode,t2.wic_process_key) ELSE t2.wic_itemcode END) as adm_search_key, 
            // SUM(wic_inventry_num) as das_inv_num ";
            // // $q.="FROM (SELECT MAX(wic_id) as max_inv_id FROM work_inventory_control GROUP BY wic_itemcode, wic_process_key,wic_complete_flag ORDER BY max_inv_id ASC) t1 ";
            // $q.="FROM (SELECT MAX(wic_id) as max_inv_id FROM work_inventory_control WHERE wic_id <= '".$max_id."'  GROUP BY wic_itemcode, wic_process_key,wic_complete_flag ORDER BY max_inv_id ASC) t1 ";
            // $q.="LEFT JOIN work_inventory_control t2 ON t2.wic_id = t1.max_inv_id
            // LEFT JOIN work_adempiere_item_ms adm_ms ON adm_ms.code = t2.wic_itemcode AND adm_ms.end_code = t2.wic_process_key 
            // LEFT JOIN ms_molditem mi ON mi.itempprocord = t2.wic_itemcode ";
            // // $q.="WHERE t2.wic_itemcode IN ('".$list_search."') AND LENGTH(t2.wic_rfid) = '32' AND t2.wic_wherhose LIKE '%".$plant."%' AND t2.wic_name NOT LIKE '%TEST%' ";
            // $q.="WHERE t2.wic_itemcode IN ('".$list_search."') AND t2.wic_name NOT LIKE '%TEST%' ";
            // $q.="GROUP BY t2.wic_itemcode, t2.wic_process_key ";
            // $q.="ORDER BY t2.wic_itemcode ASC, t2.wic_complete_flag ASC, t2.wic_process_key DESC ";

            // DAS在庫データ収得(SUM in - out)
            // $q="SELECT *, SUM(t2.proc_inv_num) as das_inv_num, COUNT(t2.wic_rfid) as idx, (CASE WHEN t2.wic_process_key <> '0' THEN CONCAT(t2.wic_itemcode,t2.wic_process_key) ELSE t2.wic_itemcode END) as adm_search_key 
            // FROM (
            //     SELECT *, 
            //     SUM(wic_qty_in) - SUM(wic_qty_out) as proc_inv_num, 
            //     (CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as tag_name, 
            //     (CASE WHEN wic_process LIKE '%成形%' THEN '1' WHEN wic_process LIKE '%最終%' THEN '2' WHEN wic_process LIKE '%完成%' THEN '3' END) as order_num 
            //     FROM work_inventory_control wic 
            //     LEFT JOIN ms_molditem mi ON mi.itempprocord = wic.wic_itemcode 
            //     WHERE ";
            //     if($max_id!=""){
            //         $q.="(wic_id <= '".$max_id."' OR wic_remark LIKE '%棚卸修正%') AND ";
            //     }
            //     // $q.="wic_remark <> '員数不足​' AND LENGTH(wic.wic_rfid) = 32 ";
            //     $q.="wic_itemcode IN ('".$list_search."') AND wic_remark <> '員数不足​' ";
            //     $q.="GROUP BY wic_rfid, wic_process_key 
            //     HAVING proc_inv_num <> 0 
            //     ORDER BY wic_itemcode ASC, wic_id DESC) t2 
            // LEFT JOIN work_adempiere_item_ms adm_ms ON adm_ms.code = t2.wic_itemcode AND adm_ms.end_code = t2.wic_process_key 
            // GROUP BY t2.wic_itemcode, t2.wic_process_key 
            // ORDER BY t2.wic_itemcode ASC, t2.order_num ASC, t2.wic_id ASC";

            //2024-09-27　棚卸製品を追加 ('KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00')
            //2024-10-29　棚卸製品を追加 ('APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00')
            // $list_inventory_itemcode = "'KCM50037-00','KCM50049-00','KCM50059-00','KCM50060-00','PL520314-00','PL520315-00','APH50001-00','PL500466-00','KCM50052-00','KDN50010-00','APH50003-00','KCM50057-00','SPN50025-00','ZST50002-00','APH50002-00'";
            //2022-02-11　棚卸製品を追加:山崎→全製品対応
            $list_inventory_itemcode = "'KDN50010-00'";

            // DAS在庫データ収得(最終誤差テーブルから抽出)
            $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant."' ORDER BY wir_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $wir_id = $st->fetch(PDO::FETCH_ASSOC);

            $last_max_id = $wir_id["wir_max_wic_id"];

            if($request->getParameter("mode")=="view"){
                if($wir_id && $wir_id["wir_process_lot"]==date("Ym")){
                    $q="SELECT * FROM work_inventory_results WHERE wir_plant = '".$plant."' AND wir_process_lot < '".date("Ym")."' ORDER BY wir_id DESC LIMIT 1 ";
                    $st = $con->execute($q);
                    $wir_id = $st->fetch(PDO::FETCH_ASSOC);
                }
                $last_max_id = $wir_id["wir_max_wic_id"];

                $q="WITH RECURSIVE temp1 AS ( 
                    ( 
                        SELECT
                            t1.wic_rfid as wird_rfid
                            , t1.wic_itemcode as wird_itemcode
                            , t1.wic_process_key as wird_process_key
                            , t1.wic_process as wird_process
                            , t1.tag_name as wird_tag_name
                            , t1.order_num as wird_order_num
                            , t1.wic_wherhose as wird_wherhose
                            , SUM(t1.proc_inv_num) as wird_inv_num 
                        FROM
                            ( 
                                SELECT
                                    wic_rfid
                                    , wic_itemcode
                                    , wic_process_key
                                    , wic_process
                                    , ( 
                                        CASE 
                                            WHEN mi.searchtag <> '' 
                                                THEN mi.searchtag 
                                            ELSE mi.itemname 
                                            END
                                    ) as tag_name
                                    , ( 
                                        CASE 
                                            WHEN wic_process LIKE '%成形%' 
                                                THEN '1' 
                                            WHEN wic_process LIKE '%最終%' 
                                                THEN '2' 
                                            WHEN wic_process LIKE '%完成%' 
                                                THEN '3' 
                                            END
                                    ) as order_num
                                    , wic_wherhose
                                    , SUM(wic_qty_in) - SUM(wic_qty_out) as proc_inv_num 
                                FROM
                                    work_inventory_control wic 
                                    LEFT JOIN ms_molditem mi 
                                        ON mi.itempprocord = wic.wic_itemcode 
                                WHERE
                                    wic_wherhose LIKE '%".$plant."%' 
                                    AND wic_remark <> '員数不足​'
                                    AND wic_id > '".$last_max_id."' 
                                    AND wic_id <= '".$max_id."' 
                                    AND wic_name NOT LIKE '%TEST%' ";
                                    if($plant=="野洲工場"){
                                        $q.="AND wic_itemcode IN (".$list_inventory_itemcode.") ";
                                    }
                                    $q.="AND wic_remark NOT LIKE '%棚卸修正%' 
                                    AND wic_del_flg = '0' 
                                GROUP BY
                                    wic_rfid
                                    , wic_process_key 
                                HAVING
                                    proc_inv_num <> 0 
                                ORDER BY
                                    wic_itemcode ASC
                                    , wic_id DESC
                            ) t1 
                        GROUP BY
                            t1.wic_rfid 
                            , t1.wic_process_key 
                        ORDER BY
                            t1.wic_itemcode ASC
                            , t1.order_num ASC
                    ) 
                    UNION ALL 
                    SELECT
                        t2.wird_rfid
                        , t2.wird_itemcode
                        , t2.wird_process_key
                        , t2.wird_process
                        , t2.wird_tag_name
                        , t2.wird_order_num
                        , t2.wird_wherhose
                        , t2.wird_inv_num
                    FROM
                        work_inventory_result_details t2 
                    WHERE
                        t2.wird_wir_id = '".$wir_id["wir_id"]."' 
                ) 
                SELECT
                    *, 
                    (CASE WHEN wird_process_key <> '0' THEN CONCAT(wird_itemcode,wird_process_key) ELSE wird_itemcode END) as adm_search_key, 
                    (CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as tag_name, 
                    SUM(wird_inv_num) as das_inv_num 
                FROM
                    temp1 LEFT JOIN ms_molditem mi ON mi.itempprocord = temp1.wird_itemcode ";
                if($plant=="野洲工場"){
                    $q.="WHERE wird_itemcode IN (".$list_inventory_itemcode.") ";
                }
                $q.="GROUP BY
                    wird_itemcode, wird_process_key 
                HAVING 
                    das_inv_num <> 0
                ORDER BY
                    wird_itemcode ASC
                    , wird_order_num ASC ";
            }else{
                $q="SELECT wird.*, 
                    (CASE WHEN wird_process_key <> '0' THEN CONCAT(wird_itemcode,wird_process_key) ELSE wird_itemcode END) as adm_search_key, 
                    (CASE WHEN mi.searchtag <> '' THEN mi.searchtag ELSE mi.itemname END) as tag_name,
                    SUM(wird_inv_num) as das_inv_num 
                FROM work_inventory_result_details wird 
                LEFT JOIN ms_molditem mi ON mi.itempprocord = wird.wird_itemcode 
                WHERE wird_wir_id = '".$wir_id["wir_id"]."' AND wird_itemcode IN ('".$list_search."')
                GROUP BY wird_itemcode, wird_process_key 
                ORDER BY wird_itemcode ASC, wird_order_num ASC ";
            }

            // print_r($q);
            // exit;

            $st = $con->execute($q);
            $das_inv = $st->fetchall(PDO::FETCH_ASSOC);

            // $das_inv=$d;
            
            $product_id_arr = [];
            foreach($das_inv as $key=>$value){
                // if($value["m_product_id"]){
                    $product_id_arr[]=$value["adm_search_key"];
                    // $das_inv[$key]["adm_inv"]=0;
                // }
            }
            $product_id_str = implode("','",$product_id_arr);

            // Adempiere在庫データ収得
            $q="SELECT r.m_product_id, r.value AS 品目コード, SUM(sumqtyonhand) AS 在庫数量, CONCAT(pc.value, '-', pc.name) as 品目カテゴリ 
            FROM rv_storage_per_product r 
            LEFT JOIN m_product_category pc ON r.m_product_category_id = pc.m_product_category_id 
            WHERE r.value IN ('".$product_id_str."') 
            GROUP BY r.m_product_id, r.value, pc.value, pc.name 
            ORDER BY 品目コード ASC";
            $st = $dbh->query($q);
            $adm_inv = $st->fetchall(PDO::FETCH_ASSOC); 
            
            $marge_arr = [];
            foreach($das_inv as $dk=>$dv){
                $add_item=$dv;
                foreach($adm_inv as $ak=>$av){
                    if($av["品目コード"]==$dv["adm_search_key"]){
                        $add_item["adm_inv"]=$av["在庫数量"];
                        $add_item["adm_product_id"]=$av["m_product_id"];
                        $add_item["m_product_category"]=$av["品目カテゴリ"];
                        if($plant=="野洲工場"){
                            $add_item["place_id"]="1000079";
                        }else{
                            $add_item["place_id"]="1000073";
                        }

                        // $das_inv[$dk]["adm_inv"]=$av["在庫数量"];

                        // $das_inv[$dk]["sum_in"]=$av["sum_in"];
                        // $das_inv[$dk]["sum_out"]=$av["sum_out"];
                        // $das_inv[$dk]["adm_inv"]= intval($av["sum_in"]) - intval($av["sum_out"]);
                    }
                }
                $marge_arr[]=$add_item;
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($marge_arr);
            exit;

        }

        // return sfView::NONE;
    }

    public function executeInventoryNoRfidOut(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('RFIDチップ非搭載IDの出庫処理 | Nalux');

        $q="SELECT
            *
            , MAX(wic_id) as max_wic_id
            , MAX(wic_date) as max_date
            , MAX(wic_complete_id) as max_cpl_id
            , SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num
            , LENGTH(wic_rfid) as len 
        FROM
            work_inventory_control 
        WHERE
            wic_wherhose LIKE '%山崎工場%' 
            AND wic_itemcode LIKE '%' 
            AND wic_del_flg = '0' 
        GROUP BY
            wic_rfid
            , wic_itemcode
            , wic_process_key 
        HAVING
            inv_num > 0 
            AND len = 24 
        ORDER BY
            wic_itemcode ASC
            , wic_id ASC
        ";
        $st = $con->execute($q);
        $out_list = $st->fetchall(PDO::FETCH_ASSOC);

        $qo ="INSERT INTO work_inventory_control (wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_hgpd_id,wic_complete_id,wic_before_id,wic_complete_flag,wic_created_at) VALUES ";

        $n=0;

        $created_at=date("Y-m-d H:i:s");

        foreach($out_list as $k=>$val){
            //在庫数の計算
            $q="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control WHERE wic_itemcode = '".$val["wic_itemcode"]."' AND wic_process_key = '".$val["wic_process_key"]."' AND wic_complete_flag = '".$val["wic_complete_flag"]."' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $inv_num = $st->fetch(PDO::FETCH_ASSOC);

            $now_inv_num = intval($inv_num["inv_num"])-intval($val["inv_num"]);

            $qo.= "('".date("Y-m-d")."',";
            $qo.= "'ゴ クアン ビン',";
            $qo.= "'".$val["wic_wherhose"]."',";
            $qo.= "'".$val["wic_rfid"]."',";
            $qo.= "'".$val["wic_itemcode"]."',";
            $qo.= "'".$val["wic_process_key"]."',";
            $qo.= "'".$val["wic_process"]."',";
            $qo.= "'".$val["wic_itemform"]."',";
            $qo.= "'".$val["wic_itemcav"]."',";
            $qo.= "'0',";
            $qo.= "'".$val["inv_num"]."',";
            $qo.= "'".$now_inv_num."',";
            $qo.= "'棚卸修正',";          //remark
            $qo.= "'".$val["max_cpl_id"]."',";
            $qo.= "'".$val["max_cpl_id"]."',";
            $qo.= "'".$val["max_cpl_id"]."',";
            $qo.= "'".$val["wic_complete_flag"]."',";
            $qo.= "'".$created_at."'),";

            $n++;
        }
        $qo=substr($qo,0,-1);
        $con->execute($qo);

        print_r("<br>処理した：".$n."件");
        exit;
    
    }

    public function executeRFIDRecycle(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('RFID解放処理 | Nalux');


        if($request->getParameter("ac")=="getItem"){
            //在庫でRFID状態チェック
            $q="SELECT (SUM(wic_qty_in) - SUM(wic_qty_out)) as inv_num FROM work_inventory_control WHERE wic_rfid = '".$request->getParameter("rfid")."' AND wic_del_flg = '0' ";
            $st = $con->execute($q);
            $wic_check = $st->fetch(PDO::FETCH_ASSOC);

            // echo "<pre>";
            // print_r($wic_check);
            // exit;

            header("Content-Type: application/json; charset=utf-8");
            if(intval($wic_check["inv_num"])==0){
                //RFIDと品目連携情報収得
                $q="SELECT wim.*, (CASE WHEN ms.searchtag <> '' THEN ms.searchtag ELSE itemname END) as itemname 
                FROM work_id_manager wim 
                LEFT JOIN ms_molditem ms 
                ON ms.itempprocord = wim.wim_itemcode 
                WHERE rfid = '".$request->getParameter("rfid")."' ";
                $st = $con->execute($q);
                $rfid = $st->fetchall(PDO::FETCH_ASSOC);

                // echo "<pre>";
                // print_r($rfid);
                // exit;
                if(count($rfid)>0){
                    echo json_encode(["OK",$rfid[0]]);
                }else{
                    echo json_encode(["NG","RFID管理台帳に存在してないIDなので、解放出来ません。"]);
                }
            }else{
                echo json_encode(["NG","現物製品と紐付しています。解放出来ません。"]);
            }
            exit;
        }

        if($request->getParameter("ac")=="update"){
            $post = $request->getParameter("d");
            $list_rfid = $post["list_item"];
            foreach($list_rfid as $key=>$val){
                $rfids.="'".$val["rfid"]."',";
            }
            $rfids=substr($rfids,0,-1);

            $q="UPDATE work_id_manager SET 
                wim_class = '".$post["new_id_type"]."', 
                wim_itemcode = '".$post["new_itemcode"]."', 
                wim_number = '".$post["new_around_num"]."', 
                wim_username = '".$post["new_user"]."',
                wim_created_at = '".date("Y-m-d H:i:s")."',
                wim_status = '未使用' 
            WHERE rfid IN(".$rfids.") ";
           
            header("Content-Type: application/json; charset=utf-8");
            try{
                $con->execute($q);
                echo json_encode("OK");
            }catch (Exception $e){
                echo json_encode($e->getMessage());
            }
            exit;
        }

        $q="SELECT itempprocord, tray_num * tray_stok as round_num, fpr_num,(CASE WHEN searchtag<>'' THEN searchtag ELSE itemname END) as itemname FROM ms_molditem ORDER BY id DESC ";
        $st=$con->execute($q);
        $product_list = $st->fetchall(PDO::FETCH_ASSOC);
        $this->product_list = $product_list;

    }

    
    public function executeWeldingCombination(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('溶着組み合わせ | Nalux');

        if($request->getParameter("ac")=="combinationEntry"){
            $data = $request->getParameter("data");

            // echo "<pre>";
            // print_r($data);
            // exit;

            foreach($data as $key=>$value){

                $main=$value["main"];
                $parts=$value["parts"];
              
                $qs = "SELECT wwc_id FROM work_welding_combinate ORDER BY wwc_id DESC LIMIT 1 ";
                $st=$con->execute($qs);
                $res = $st->fetch(PDO::FETCH_ASSOC);
      
                $wwc_id = $res["wwc_id"];
                if(!$wwc_id){
                    $wwc_id = 1; 
                }else{
                    $wwc_id++;
                }
        
                $q="INSERT INTO work_welding_combinate (wwc_wel_machine,wwc_item,wwc_work_day,wwc_work_lot,wwc_user,wwc_created_at,wwc_updated_at) VALUES ";
                $qp="INSERT INTO work_welding_combinate_detail (wwcd_class,wwcd_itemcode,wwcd_cav,wwcd_mold_lot,wwc_id) VALUES ";

                $wel_lot = substr(str_replace("-","",$main["work_date"]),2,8);
                $rfid_tag_num=intval($main["rfid_tag_num"]);
                if($rfid_tag_num>0){
                    for($n=0;$n<$rfid_tag_num;$n++){
                        $q.="('".$main["machine"]."',
                        '".$main["code"]."',
                        '".$main["work_date"]."',
                        '".$wel_lot."',
                        '".$main["user"]."',
                        '".date("Y-m-d H:i:s")."',
                        '".date("Y-m-d H:i:s")."'),";

                        foreach($parts as $k => $v){
                            $qp.="('".$k."',
                            '".$v["code"]."',
                            '".$v["cav"]."',
                            '".$v["lot"]."',
                            '".$wwc_id."'),";
                        }
                        $wwc_id++;
                    }
                }

                $q=substr($q,0,-1);
                $con->execute($q);

                $qp=substr($qp,0,-1);
                $con->execute($qp);

            }

            // echo "<pre>";
            // print_r($q);
            // echo "<br>";
            // print_r($qp);
            // exit;
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            exit;
            
            // try{
            //     $con->execute($q);
            //     echo json_encode("OK");
            // }catch (Exception $e){
            //     echo json_encode($e->getMessage());
            // }
            // exit;
        }

        if($request->getParameter("ac")=="lot_info"){
            header("Content-Type: application/json; charset=utf-8");

            $welding_item = $request->getParameter("item_code");
            $mold_machine = $request->getParameter("lot_mno");
            
            //部品情報収得
            $q="SELECT *, (CASE WHEN (itemname LIKE '%FR%' OR itemname LIKE '%FRONT%') THEN 'FRONT' WHEN (itemname LIKE '%RR%' OR itemname LIKE '%BACK%') THEN 'BACK' ELSE 'MID' END) as part_name 
            FROM work_adempiere_item_ms bom LEFT JOIN ms_molditem mm ON mm.itempprocord = bom.befor_code 
            WHERE code = '".$welding_item."' AND proccess_name = '組立' AND mm.itempprocord != '' ";
            // echo "<pre>";
            // print_r($q);
            // exit;
            $st=$con->execute($q);
            $res_parts = $st->fetchall(PDO::FETCH_ASSOC);


            //TEST
            // $res_parts[0]["befor_code"]="KCM50058-00";
            // $res_parts[1]["befor_code"]="KCM50059-00";

            // echo "<pre>";
            // print_r($res_parts);
            // exit;
            // "front","PL500448-00","back","PL500447-00","mid","PL500449-00"

            $parts_list["wel_info"]=$res_parts;
            if(COUNT($res_parts)>0){

                foreach($res_parts as $k=>$val){
                    $q="SELECT t1.*, SUM(sum_tag) as sum_inv, COUNT(hgpd_rfid) as tag_num FROM 
                    (SELECT *, SUM(wic_qty_in)-SUM(wic_qty_out) as sum_tag FROM hgpd_report hr 
                    LEFT JOIN work_inventory_control wic ON wic.wic_complete_id = hr.hgpd_id ";
                    $q.="WHERE hr.hgpd_itemcode = '".$val["befor_code"]."' AND hgpd_process LIKE '%成形%' AND hgpd_del_flg = '0' AND wic_del_flg = '0' ";
                    // if($k==0){
                    //     $q.="WHERE hr.hgpd_itemcode = 'KCM50058-00' AND wic_process LIKE '%成形%' AND hgpd_del_flg = '0' AND wic_del_flg = '0' ";
                    // }else{
                    //     $q.="WHERE hr.hgpd_itemcode = 'KCM50059-00' AND wic_process LIKE '%成形%' AND hgpd_del_flg = '0' AND wic_del_flg = '0' ";
                    // }
                    $q.="GROUP BY hgpd_itemcode, hgpd_moldlot, hgpd_itemform, hgpd_cav, hgpd_rfid HAVING sum_tag > 0 ) t1 ";
                    $q.="GROUP BY hgpd_itemcode, hgpd_moldlot, hgpd_itemform, hgpd_cav ";
                    $q.="ORDER BY hgpd_moldlot ASC,hgpd_itemform ASC,hgpd_cav ASC ";
                    // print_r($q);
                    // exit;
                    $st=$con->execute($q);
                    $res = $st->fetchall(PDO::FETCH_ASSOC);
                    $parts_list["items"][$val["befor_code"]]=$res;
                }

                // echo "<pre>";
                // print_r($parts_list);
                // exit;

                echo json_encode($parts_list);
            }else{
                echo json_encode("NG");
            }
            exit;

        }

        $q="SELECT bom.*, (CASE WHEN searchtag <> '' THEN searchtag ELSE itemname END) as itemname 
        FROM work_adempiere_item_ms bom LEFT JOIN ms_molditem mm ON mm.itempprocord = bom.code 
        WHERE proccess_name = '組立' AND mm.itempprocord != '' 
        GROUP BY bom.code ";
        $st=$con->execute($q);
        $list_items = $st->fetchall(PDO::FETCH_ASSOC);

        $process = "組立";
        $plant = "野洲工場";
        $plant_id="";

        $this->list_items=$list_items;
        $this->process=$process;
        $this->plant=$plant;
    }

    public function executeWeldingCounter(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('溶着工程実績入力 | Nalux');

        if($request->getParameter("ac")=="welCounterEntry"){
            $data = $request->getParameter("post_data");
            // echo "<pre>";
            // print_r($data);
            // exit;
            $main = $data["main"];
            $parts = $data["parts"];

            $work_date=date("Y-m-d");

            $wl[0] = substr(date("y",strtotime($work_date)) , -1);
            $wl[1] = date("n",strtotime($work_date));
            if($wl[1]=="10"){
                $wl[1] ="X";
            }elseif($wl[1]=="11"){
                $wl[1] = "Y";
            }elseif($wl[1]=="12"){
                $wl[1] = "Z";
            }
            $wl[2] = date("d",strtotime($work_date));
            $worklot = implode("",$wl); 
            
            $mold_lot=substr(str_replace('-','',$main["date"]),2,8);
            $created_at=date("Y-m-d H:i:s");

            //溶着済みデータ記録
            $work_second = strtotime($main["weldet"])-strtotime($main["weldst"]);

            //担当者情報
            $q = "SELECT * FROM ms_person WHERE user = '".$main["user"]."' ";
            $st = $con->execute($q);
            $user = $st->fetch(PDO::FETCH_ASSOC);

            //製品マスター情報
            $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$main["combi_item"]."' ";
            $st = $con->execute($q);
            $ms_mold = $st->fetch(PDO::FETCH_ASSOC);

            $qhre = "INSERT INTO hgpd_report (xwr_id,hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_itemform, hgpd_cav, hgpd_moldlot,
            hgpd_worklot, hgpd_checkday, hgpd_moldday, hgpd_quantity, hgpd_qtycomplete, 
            hgpd_difactive, hgpd_remaining, hgpd_namecode, hgpd_name, hgpd_rfid, 
            hgpd_start_at, hgpd_stop_at, hgpd_exclusion_time, hgpd_working_hours, hgpd_volume, created_at) ";
            $qhre.= "VALUES ";
            $qhre.= "('0',";
            $qhre.= "'野洲工場',";
            $qhre.= "'組立',";
            $qhre.= "'".$main["combi_item"]."',";
            $qhre.= "'1',"; //form
            $qhre.= "'".$main["cav"]."',";
            $qhre.= "'".$mold_lot."',";
            $qhre.= "'".$work_lot."',";
            $qhre.= "'".$work_date."',";
            $qhre.= "'".$work_date."',";
            $qhre.= "'".$main["allnum"]."',";
            $qhre.= "'".$main["goodnum"]."',";
            $qhre.= "'".$main["badnum"]."',";
            $qhre.= "'0',";
            $qhre.= "'".$user["ppro"]."',";
            $qhre.= "'".$main["user"]."',";
            $qhre.= "'".$main["weldedrfid"]."',";              //hgpd_rfid
            $qhre.= "'".$main["weldst"]."',";
            $qhre.= "'".$main["weldet"]."',";
            $qhre.= "'0',";
            $qhre.= "'".($work_second/3600)."',";
            $qhre.= "'".(($main["allnum"]*3600)/$work_second)."',";
            $qhre.= "'".$created_at."') ";
            $con->execute($qhre);

            // print_r($qhre);
            // exit;
            
            $q="SELECT hgpd_id FROM hgpd_report WHERE hgpd_del_flg = '0' ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $hgpd_id = $st->fetch(PDO::FETCH_ASSOC);

            $diff_item=array();
            $ex = explode(",", $data["main"]["badtext"]);
            foreach ($ex as $dval) {
                $exd = explode("=>", $dval);
                if($exd[0] && $exd[1] > 0){
                    $diff_item[$exd[0]] = $exd[1];
                }
            }

            if($diff_item){
                // 不具合内容保存
                $qd = "INSERT INTO hgpd_report_defectiveitem ";
                $qd.= "(hgpd_id,hgpdd_ditem,hgpdd_qty,hgpdd_price,hgpdd_time) ";
                $qd.= "VALUES (";
                foreach($diff_item as $dk=>$dval){
                    $qd.= "'".$hgpd_id["hgpd_id"]."',";
                    $qd.= "'".$dk."',";
                    $qd.= "'".$dval."',";
                    $qd.= "'".($dval*$ms_mold["adm_price_std"])."',";
                    $qd.= "'".($work_second / $main["allnum"])*$dval."'),(";
                }
                $qd = substr($qd, 0,-2);
                $con->execute($qd);
            }
            //出庫データ登録
            foreach($parts as $part_name=>$pv){
                //不良カウンター
                // $diff_item[$pv["code"]]["投入数不合"]=intval($pv["num"])-intval($main["allnum"]);
                // $diff_item[$pv["code"]]["溶着不良"]=intval($main["allnum"])-intval($main["goodnum"]);

                // $diff

                //在庫数の計算
                $q="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control WHERE wic_itemcode = '".$pv["code"]."' AND wic_process_key = 'M' AND wic_complete_flag = '0' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $inv_num = $st->fetch(PDO::FETCH_ASSOC);
                $now_inv_num = intval($inv_num["inv_num"])-intval($pv["num"]);

                $qwic_out = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
                $qwic_out.= "VALUES ( ";
                $qwic_out.= "'".$hgpd_id["hgpd_id"]."',";
                $qwic_out.= "'".$hgpd_id["hgpd_id"]."',";
                $qwic_out.= "'".$pv["hgpd_id"]."',";
                $qwic_out.= "'".date("Y-m-d")."',";
                $qwic_out.= "'".$main["user"]."',";
                $qwic_out.= "'1000072,野洲工場_仕掛品置場,3104',";
                $qwic_out.= "'".$pv["rfid"]."',";
                $qwic_out.= "'".$pv["code"]."',";
                $qwic_out.= "'M',";
                $qwic_out.= "'".$pv["bf_work"]."',";
                $qwic_out.= "'".$pv["form"]."',";
                $qwic_out.= "'".$pv["cav"]."',";
                $qwic_out.= "'0',";
                $qwic_out.= "'".$pv["num"]."',";
                $qwic_out.= "'".$now_inv_num."',";
                $qwic_out.= "'溶着出庫',";          //remark
                $qwic_out.= "'0',";          //仕掛品
                $qwic_out.= "'".$created_at."')";
                $con->execute($qwic_out);

                $q="SELECT wic_id FROM work_inventory_control ORDER BY wic_id DESC LIMIT 1 ";
                $st = $con->execute($q);
                $last_wic_id = $st->fetch(PDO::FETCH_ASSOC);

                $qc = "INSERT INTO hgpd_report_sub (hgpd_complete_id,hgpd_before_id,hrs_complete_wic_id) VALUES ('".$hgpd_id["hgpd_id"]."','".$pv["hgpd_id"]."','".$last_wic_id["wic_id"]."') ";
                $con->execute($qc);

            }
            //入庫データ登録
            //在庫数の計算
            $q="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as inv_num FROM work_inventory_control WHERE wic_itemcode = '".$main["combi_item"]."' AND wic_process_key = 'M' AND wic_complete_flag = '0' AND wic_del_flg = '0' ORDER BY wic_id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $inv_num = $st->fetch(PDO::FETCH_ASSOC);
            $now_inv_num = intval($inv_num["inv_num"])+intval($pv["num"]);

            $qwic_in = "INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_remark,wic_complete_flag,wic_created_at) ";
            $qwic_in.= "VALUES ( ";
            $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
            $qwic_in.= "'".$hgpd_id["hgpd_id"]."',";
            $qwic_in.= "'0',";
            $qwic_in.= "'".date("Y-m-d")."',";
            $qwic_in.= "'".$main["user"]."',";
            $qwic_in.= "'1000072,野洲工場_仕掛品置場,3104',";
            $qwic_in.= "'".$main["weldedrfid"]."',";
            $qwic_in.= "'".$main["combi_item"]."',";
            $qwic_in.= "'Y',";
            $qwic_in.= "'組立',";
            $qwic_in.= "'1',";
            $qwic_in.= "'".$main["cav"]."',";
            $qwic_in.= "'".$main["goodnum"]."',";
            $qwic_in.= "'0',";
            $qwic_in.= "'".$now_inv_num."',";
            $qwic_in.= "'溶着入庫',";          //remark
            $qwic_in.= "'0',";          //仕掛品
            $qwic_in.= "'".$created_at."')";
            $con->execute($qwic_in);

            $qu="UPDATE work_welding_combinate SET wwc_hgpd_id = '".$hgpd_id["hgpd_id"]."' WHERE wwc_id = '".$main["wwc_id"]."' ";
            $con->execute($qu);

            //不良登録
            // print_r($diff_item);
            // exit;
            
            header("Content-Type: application/json; charset=utf-8");

            echo json_encode(["OK",$hgpd_id["hgpd_id"]]);
            exit;

        }

        if($request->getParameter("ac")=="entryXlsReport"){
        
            $post_data = $request->getParameter("data");

            $machine = $request->getParameter("machine");

            // $data = [];
            // foreach($post_data as $key=>$value){
            //     // if($value["main"]["user"])
            //     $data[$value["main"]["combi_item"]][$value["main"]["user"]][]=$value;
            // }
            // echo "<pre>";
            // print_r(implode(',',$post_data));

            // exit;

            // 実績集計
            $q="SELECT *,GROUP_CONCAT(hr.hgpd_id) as hgpd_ids, SUM(hgpd_quantity) as quantity, SUM(hgpd_qtycomplete) as qtycomplete, SUM(hgpd_difactive) as difactive, GROUP_CONCAT(CONCAT(hd.hgpdd_ditem,'=>',hd.hgpdd_qty)) as str_diff, 
            SUM(hgpd_working_hours) as sum_time, COUNT(hr.hgpd_id) as id_num, MIN(created_at) as start_time, MAX(created_at) as end_time 
            FROM hgpd_report hr LEFT JOIN hgpd_report_defectiveitem hd ON hd.hgpd_id = hr.hgpd_id 
            WHERE hr.hgpd_id IN (".implode(',',$post_data).") 
            GROUP BY hr.hgpd_itemcode, hr.hgpd_moldlot, hr.hgpd_itemform, hr.hgpd_worklot, hr.hgpd_name 
            ORDER BY hr.hgpd_id ASC ";
            $st = $con->execute($q);
            $res = $st->fetchall(PDO::FETCH_ASSOC);
         
            $created_at=date("Y-m-d H:i:s");

            foreach($res as $key=>$value){

                //担当者情報
                $q = "SELECT * FROM ms_person WHERE user = '".$value["hgpd_name"]."' ";
                $st = $con->execute($q);
                $user = $st->fetch(PDO::FETCH_ASSOC);

                $q="SELECT * FROM ms_molditem WHERE itempprocord = '".$value["hgpd_itemcode"]."' ";
                $st = $con->execute($q);
                $ms_mold = $st->fetch(PDO::FETCH_ASSOC);

                $plalce_id="1000079";
                if($value["hgpd_wherhose"]=="山崎工場"){
                    $plalce_id="1000073";
                }

                $q= "INSERT INTO xls_work_report(date, itemcord, itemname, xlsnum, workkind,";  //5
                $q.=" workitem, usercord, username, usergp1, usergp2,";  //10
                $q.=" moldplaceid,moldplace, itemform, moldlot, moldmachine,";  //15
                $q.=" totalnum, badnum, goodnum,pending_num,";   //20
                $q.=" missing_num,e_or_d,e_or_d_memo,totaltime, remark, defectivesitem,"; //25
                $q.=" beforeprocess, afterprocess, cutmethod, vapordepositionlot, defect,";  //30
                $q.=" pdate, dateuse, scheduled_number, measure, state,";   //35
                $q.=" ntab, cycle, materialsname, materialslot, materialsused,";    //40
                $q.=" lotstarttime, lotendtime, gootrate, hour, badrate,";   //45
                $q.=" pproentry, workplankind, plankind, plantime, place,"; //50
                $q.=" updating_person, del_flg, xstart_time1, xend_time1, xsetuptime,"; //55
                $q.=" item_set_num, created_at, updated_at) VALUES (";  //58
                $q.="'".$value["hgpd_moldday"]."',";
                $q.="'".$value["hgpd_itemcode"]."',";
                $q.="'".$ms_mold["itemname"]."',";
                $q.="'',";
                $q.="'直接作業',";  //5
                $q.="'".$value["hgpd_process"]."',";
                $q.="'".$user["ppro"]."',";
                $q.="'".$user["user"]."',";
                $q.="'".$user["gp1"]."',";
                $q.="'".$user["gp2"]."',";  //10
                $q.="'".$plalce_id."',";
                $q.="'".$value["hgpd_wherhose"]."',";
                $q.="'".$value["hgpd_itemform"]."',";
                $q.="'".$value["hgpd_moldlot"]."',";
                $q.="'".$machine."',";  //15
                $q.="'".$value["quantity"]."',";
                $q.="'".$value["difactive"]."',";
                $q.="'".$value["qtycomplete"]."',";
                $q.="'0',";
                $q.="'0',"; //20
                $q.="'0',";
                $q.="'',";
                $q.="'".($value["sum_time"]*60/$value["id_num"])."',";
                $q.="'".$value["LotId"]."',";              //remark:LotID追加
                $q.="'".$value["all_ditem"]."',";   //25
                $q.="'".$value["beforeprocess"]."',";
                $q.="'".$value["afterprocess"]."',";
                $q.="'".$value["cutmethod"]."',";
                $q.="'".$value["vapordepositionlot"]."',";
                $q.="'".$value["str_diff"]."',";    //30
                $q.="'".$value["hgpd_moldday"]."',";
                $q.="'".$value["dateuse"]."',";
                $q.="'".$value["scheduled_number"]."',";
                $q.="'".$value["measure"]."',";
                $q.="'".$value["state"]."',";   //35
                $q.="'',";
                $q.="'".($value["sum_time"]/$value["quantity"])."',";
                $q.="'".$value["matername"]."',";
                $q.="'".$value["materialslot"]."',"; 
                $q.="'',";  //40
                $q.="'".$value["開始ロット"]."',";
                $q.="'".$value["lotendtime"]."',";
                $q.="'".(($value["qtycomplete"] / $value["quantity"])*100)."',";
                $q.="'".($value["time_mold"]/3600)."',";
                $q.="'".(($value["difactive"] / $value["quantity"])*100)."',";  //45
                $q.="'".$value["pproentry"]."',";
                $q.="'".$value["workplankind"]."',";
                $q.="'".$value["plankind"]."',";
                $q.="'".$value["plantime"]."',";
                $q.="'".$value['hgpd_wherhose']."',";    //50
                $q.="'".$value["担当者"]."',";
                $q.="'0',";                          //del flg is OFF
                $q.="'".$value["start_time"]."',";
                $q.="'".$value["end_time"]."',";
                $q.="'',";  //55
                $q.="'1',";
                $q.="'".$created_at."',";
                $q.="'".$created_at."')";   //58
                $con->execute($q);

                // echo "<br>";
                // print_r($q);
                // exit;
    
                //実績ID取得
                $sq='SELECT id FROM xls_work_report WHERE moldmachine = "'.$machine.'" ORDER BY id DESC LIMIT 1 ';
                $st = $con->execute($sq);
                $lot_xls_id = $st->fetch(PDO::FETCH_ASSOC);
                $xwr_id = $lot_xls_id["id"];
                
                //レアルタイム更新
                $qu="UPDATE hgpd_report SET xwr_id = '".$xwr_id."' WHERE hgpd_id IN (".$value["hgpd_ids"].")";
                $con->execute($qu);

                //組み合わせデータ更新
                $qu2="UPDATE work_welding_combinate SET wwc_complete_flag = '1' WHERE wwc_hgpd_id IN (".$value["hgpd_ids"].")";
                $con->execute($qu2);

                //不良登録処理
                if($value["str_diff"]){
                    $defectives[]=array('id'=>$xwr_id,'defectivesitem'=>$value["str_diff"],'badnum'=>$value["difactive"]);
                    // print_r($defectives);
                    // exit;
                    $this->DitemSplitEntry($defectives);
    
                }
             
            }
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            exit;
        
        }

        if($request->getParameter("ac")=="fixDefective"){
            $data = $request->getParameter("difective");
            $wwc_id = $request->getParameter("wwc_id");
            $hgpd_id = $request->getParameter("hgpd_id");

            //処理前のレアルタイム実績データ情報
            $q="SELECT hr.*, ms.moldet_undetected_load, ms.adm_price_std, GROUP_CONCAT(CONCAT(hgpdd_ditem,'=>',hgpdd_qty)) as old_ditem 
            FROM hgpd_report hr LEFT JOIN ms_molditem ms ON ms.itempprocord = hr.hgpd_itemcode 
            LEFT JOIN hgpd_report_defectiveitem hd ON hd.hgpd_id = hr.hgpd_id 
            WHERE hr.hgpd_id = '".$hgpd_id."' 
            GROUP BY hr.hgpd_id ";
            $st = $con->execute($q);
            $old_hr_info=$st->fetch(PDO::FETCH_ASSOC);
            // echo "<pre>";
            // print_r($old_hr_info);
            // exit;

            //元データの不良数削除
            $q="DELETE FROM hgpd_report_defectiveitem WHERE hgpd_id = '".$hgpd_id."' ";
            $con->execute($q);

            if(count($data)>0){
                //更新
                $newValue="";
                $qi="INSERT INTO hgpd_report_defectiveitem (hgpd_id,hgpdd_ditem,hgpdd_qty,hgpdd_price,hgpdd_time) VALUES ";
                foreach($data as $key=>$value){
                    $qi.="('".$hgpd_id."','".$key."','".$value."','".(intval($value)*$old_hr_info["adm_price_std"])."','".(intval($value)*(intval($old_hr_info["hgpd_working_hours"])*3600)/intval($old_hr_info["hgpd_quantity"]))."'),";
                    $newValue.= $key."=>".$value.",";
                }
                $qi=substr($qi,0,-1);
                $newValue=substr($newValue,0,-1);
                $con->execute($qi);

                //子データ更新
                $sum_child_old_ditem=$this->DitemGroup($old_hr_info["old_ditem"], "t_num");   //処理前の不良数
                $sum_child_new_ditem=$this->DitemGroup($newValue, "t_num");   //処理後の不良数
                $diff_child = $sum_child_old_ditem - $sum_child_new_ditem;          //処理後の違う数
            }else{
                $diff_child = $sum_child_old_ditem;          //処理後の違う数
            }
        
            //新しい良品数
            $new_child_goodnum = $old_hr_info["hgpd_qtycomplete"] + $diff_child;

            //レアルタイム実績の更新
            $q="UPDATE hgpd_report SET hgpd_cycle = '".(60*$pr["work_min"]/$new_child_goodnum)."' 
                , hgpd_qtycomplete = '".$new_child_goodnum."' 
                , hgpd_difactive = '".$sum_child_new_ditem."' 
                , hgpd_volume = '".($new_child_goodnum/($old_hr_info["hgpd_working_hours"]/60))."' 
                WHERE hgpd_id = '".$hgpd_id."' ";
            $con->execute($q);

            //最新在庫情報を収得
            $q= "SELECT * FROM work_inventory_control WHERE wic_hgpd_id = '".$hgpd_id."' AND wic_rfid = '".$old_hr_info["hgpd_rfid"]."' ";
            if($old_hr_info["moldet_undetected_load"]=="0"){
                $q.="AND wic_process = '".$old_hr_info["hgpd_process"]."' ";
            }
            $q.="ORDER BY wic_id DESC ";
            $st = $con->execute($q);
            $old_wic_info=$st->fetch(PDO::FETCH_ASSOC);
            
            // 違う数!=0場合：在庫の数も調整
            if($diff_child!=0){
                $q_fix="INSERT INTO work_inventory_control (wic_hgpd_id,wic_complete_id,wic_before_id,wic_date,wic_name,wic_wherhose,wic_rfid,wic_itemcode,wic_process_key,wic_process,wic_itemform,wic_itemcav,wic_qty_in,wic_qty_out,wic_inventry_num,wic_complete_flag,wic_remark,wic_created_at) VALUES ";
                $q_fix.= "('".$hgpd_id."',";
                $q_fix.= "'".$hgpd_id."',";
                $q_fix.= "'".$hgpd_id."',";
                $q_fix.= "'".date("Y-m-d")."',";
                $q_fix.= "'".$old_wic_info["wic_name"]."',";
                $q_fix.= "'".$old_wic_info["wic_wherhose"]."',";
                $q_fix.= "'".$old_wic_info["wic_rfid"]."',";
                $q_fix.= "'".$old_wic_info["wic_itemcode"]."',";
                $q_fix.= "'".$old_wic_info["wic_process_key"]."',";
                $q_fix.= "'".$old_wic_info["wic_process"]."',";
                $q_fix.= "'".$old_wic_info["wic_itemform"]."',";
                $q_fix.= "'".$old_wic_info["wic_itemcav"]."',";
                if($diff_child>0){
                    $q_fix.= "'".$diff_child."',";
                    $q_fix.= "'0',";
                }elseif($diff_child<0){
                    $q_fix.= "'0',";
                    $q_fix.= "'".abs($diff_child)."',";
                }
                $q_fix.= "'".(intval($old_wic_info["wic_inventry_num"])+intval($diff_child))."',";
                $q_fix.= "'".$old_wic_info["wic_complete_flag"]."',";
                if(abs($diff_child)==$old_wic_info["wic_qty_in"]){
                    $q_fix.= "'廃棄',";          //remark
                }else{
                    $q_fix.= "'実績修正',";          //remark
                }
                $q_fix.= "'".date("Y-m-d H:i:s")."')";
                $con->execute($q_fix);
            }

            //在庫の数の調整
            $url = "http://".$_SERVER['SERVER_NAME']."/RFIDReport/InventoryFix?ac=update_item&wicid=".$old_wic_info["wic_id"]."&itemcode=".$old_wic_info["wic_itemcode"];
            if( PHP_OS == 'WINNT'){
                file_get_contents($url);
            }else{
                exec("curl -s '".$url."' >> /dev/null 2>&1 &");
            }

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode("OK");
            exit;
        }
        
        if($request->getParameter("ac")=="getWelCombi"){
            $mc = $request->getParameter("wel_mc");
            $date = $request->getParameter("date");
            if($date==""){
                $date=date("Y-m-d");
            }

            $q = "SELECT xend_time1 FROM xls_work_report WHERE moldmachine = '".$mc."' ORDER BY id DESC LIMIT 1 ";
            $st = $con->execute($q);
            $last_id = $st->fetch(PDO::FETCH_ASSOC);
            $data["last_time"] = $last_id["xend_time1"];
            if(!$data["last_time"]){
                $data["last_time"] = date("Y-m-d H:i");
            }

            $q="SELECT wwc.*, wwcd.*, hr.*, wic.wic_qty_out, GROUP_CONCAT(DISTINCT CONCAT(hrd.hgpdd_ditem, '=>',hrd.hgpdd_qty)) as hgpd_def  
            FROM work_welding_combinate wwc 
            LEFT JOIN work_welding_combinate_detail wwcd ON wwcd.wwc_id = wwc.wwc_id 
            LEFT JOIN hgpd_report hr ON hr.hgpd_id = wwc.wwc_hgpd_id
            LEFT JOIN work_inventory_control wic ON wic.wic_complete_id = hr.hgpd_id AND wic.wic_itemcode = wwcd.wwcd_itemcode
            LEFT JOIN hgpd_report_defectiveitem hrd ON hrd.hgpd_id = hr.hgpd_id 
            WHERE wwc_wel_machine = '".$mc."' AND ((wwc_work_day = '".$date."' AND wwc_complete_flag != '1') OR (wwc_work_day <= '".$date."' AND wwc_complete_flag != '1') )
            GROUP BY wwcd.wwcd_id
            ORDER BY wwc.wwc_id ASC ";

            header("Content-Type: application/json; charset=utf-8");
            try{
                // print_r($q);
                // exit;
                $st = $con->execute($q);
                $res = $st->fetchall(PDO::FETCH_ASSOC);
                
                $data["combi"]=[];

                $rid=0;
                foreach($res as $key=>$val){
                    if($rid!=$val["wwc_id"]){
                        $data["combi"][$val["wwc_id"]]=$val;
                        $data["combi"][$val["wwc_id"]][$val["wwcd_class"]]=$val;
                        $rid=$val["wwc_id"];
                    }else{
                        $data["combi"][$val["wwc_id"]][$val["wwcd_class"]]=$val;
                    }
                }

                echo json_encode($data);
            }catch (Exception $e){
                echo json_encode($e->getMessage());
            }
            exit;
        }

        if($request->getParameter("ac")=="getPartsRfid"){
            $rfid = $request->getParameter("rfid");

            $q="SELECT hr.*,wic.wic_complete_flag FROM hgpd_report hr 
            LEFT JOIN work_inventory_control wic 
            ON wic.wic_hgpd_id = hr.hgpd_id 
            WHERE hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' AND wic_del_flg = '0' AND hgpd_process != '出荷・在庫:梱包' 
            GROUP BY hgpd_id ORDER BY hgpd_id DESC ";
            $st = $con->execute($q);
            $last_id = $st->fetch(PDO::FETCH_ASSOC);

            if(!$last_id["hgpd_id"]){
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode(["NG","入力したのRFIDは未登録です。<br>生産情報がありません。"]);
                exit;
            }

            if($request->getParameter("bom_mode")=="on"){
                //次の工程のBOM情報
                $q="SELECT aim.code as code,aim.befor_code as befor_code, aim.end_code as end_code 
                FROM hgpd_report hr LEFT JOIN work_adempiere_item_ms aim ON hr.hgpd_itemcode = aim.befor_code 
                WHERE hr.hgpd_rfid = '".$rfid."' AND aim.befor_code = '".$last_id["hgpd_itemcode"]."' AND hr.hgpd_del_flg = '0' ";
                $st = $con->execute($q);
                $bom_data = $st->fetch(PDO::FETCH_ASSOC);
     
                // print_r($q);
                // exit;
                $befor_endcode = substr($bom_data["befor_code"],strlen($bom_data["code"]),2);

                $qc="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv
                FROM work_inventory_control WHERE wic_rfid = '".$rfid."' AND wic_complete_flag = '0' AND wic_remark <> '員数不足' AND wic_del_flg = '0' 
                GROUP BY wic_rfid, wic_itemcode  
                ORDER BY sum_inv DESC ";
             
                $st = $con->execute($qc);
                $res_check = $st->fetch(PDO::FETCH_ASSOC);
                // print_r($qc);
                // exit;
                if($res_check["sum_inv"]>0){
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                    (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_id["hgpd_id"].") 
                    UNION ALL 
                    SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_sub["all_ids"]!=""){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $last_id["hgpd_id"];
                    }

                    $q="SELECT kb.xwr_id as id, hgpd_itemcode as itemcord, hgpd_itemform as itemform, i.itemname,hgpd_process as workitem, searchtag,hgpd_moldlot as moldlot, hgpd_wherhose as moldplace, cav_items, cav_tray_input, m.pieces, i.tray_num, i.tray_stok, i.materialsname, xwr.moldmachine, 
                    i.materialcode, hgpd_material_lot as materialslot, adm_price_std, kb.hgpd_id, kb.xwr_id, kb.hgpd_cav, wic.wic_itemcav, kb.hgpd_moldday, kb.hgpd_qtycomplete, kb.hgpd_rfid AS search_rfid ,wic_id, wic_process, wic_process_key, SUM(wic_qty_in) - SUM(wic_qty_out) as wic_qty_in,SUM(wic_qty_out) as wic_qty_out 
                    FROM hgpd_report kb
                        LEFT JOIN work_inventory_control wic ON kb.hgpd_id = wic.wic_hgpd_id 
                        LEFT JOIN ms_molditem i ON hgpd_itemcode = i.itempprocord 
                        LEFT JOIN ms_molditem_info m ON i.itempprocord = m.itemcord AND hgpd_itemform = m.form_num
                        LEFT JOIN xls_work_report xwr ON kb.xwr_id = xwr.id  
                    WHERE kb.hgpd_rfid='".$request->getParameter("rfid")."' AND wic_complete_flag = '0' AND wic_process_key = 'M' AND kb.hgpd_id IN (".$list_ids.") AND wic_remark <> '員数不足' AND wic.wic_del_flg = '0' 
                    ORDER BY wic_id DESC ";
                    // print_r($q);
                    // exit;
                    $st = $con->execute($q);
                    $lot_rfid = $st->fetch(PDO::FETCH_ASSOC); 
                    $lot_rfid["hgpd_id"]=$last_id["hgpd_id"];
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["OK",[$lot_rfid]]);
                }else if($res_check["sum_inv"]=="0"){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","入力したのRFIDは検査済です。"]);
                }else{
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","BOM情報の確認をお願い致します。"]);
                }
                exit;  
            }else{
                //BOM利用しない　SetBase
                $qc="SELECT SUM(wic_qty_in) - SUM(wic_qty_out) as sum_inv
                FROM hgpd_report hr LEFT JOIN work_inventory_control wic ON hr.hgpd_id = wic.wic_hgpd_id 
                WHERE hr.hgpd_rfid = '".$rfid."' AND hgpd_del_flg = '0' 
                ORDER BY hr.hgpd_id DESC, wic_id DESC ";
                // print_r($qc);
                // exit;
                $st = $con->execute($qc);
                $res_check = $st->fetch(PDO::FETCH_ASSOC);

                if($res_check["sum_inv"]>0){
                    $q="WITH RECURSIVE temp1(id, cid, bid, cflag) AS 
                    (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id IN(".$last_id["hgpd_id"].") 
                    UNION ALL 
                    SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid)
                    SELECT *, CONCAT(GROUP_CONCAT(DISTINCT cid), ',', GROUP_CONCAT(DISTINCT bid)) as all_ids FROM temp1 ";
                    $st = $con->execute($q);
                    $check_sub = $st->fetch(PDO::FETCH_ASSOC);

                    if($check_sub["all_ids"]!=""){
                        $list_ids = $check_sub["all_ids"];
                    }else{
                        $list_ids = $last_id["hgpd_id"];
                    }

                    $q="SELECT kb.xwr_id as id, hgpd_itemcode as itemcord, hgpd_itemform as itemform, i.itemname,hgpd_process as workitem, xwr.moldmachine, 
                    searchtag,hgpd_moldlot as moldlot, hgpd_wherhose as moldplace, cav_items, cav_tray_input, m.pieces, i.tray_num, i.tray_stok, 
                    i.materialsname, i.materialcode, hgpd_material_lot as materialslot, adm_price_std, kb.hgpd_id, kb.xwr_id, kb.hgpd_cav, wic.wic_itemcav, kb.hgpd_moldday, 
                    kb.hgpd_qtycomplete, kb.hgpd_rfid AS search_rfid ,wic_id, wic_process, wic_process_key, SUM(wic_qty_in) - SUM(wic_qty_out) as wic_qty_in,SUM(wic_qty_out) as wic_qty_out 
                    FROM hgpd_report kb
                        LEFT JOIN work_inventory_control wic ON kb.hgpd_id = wic.wic_hgpd_id 
                        LEFT JOIN ms_molditem i ON hgpd_itemcode = i.itempprocord 
                        LEFT JOIN ms_molditem_info m ON i.itempprocord = m.itemcord AND hgpd_itemform = m.form_num 
                        LEFT JOIN xls_work_report xwr ON kb.xwr_id = xwr.id 
                    WHERE kb.hgpd_rfid = '".$rfid."' AND kb.hgpd_id IN (".$list_ids.") AND wic_remark <> '員数不足' AND kb.hgpd_del_flg = '0' AND wic.wic_del_flg = '0' ";
                    if($last_id["wic_complete_flag"]=="1"){
                        $q.="GROUP BY wic.wic_itemcav,hgpd_process ORDER BY wic_id DESC ";
                    }else{
                        $q.="ORDER BY wic_id DESC ";
                    }

                    // print_r($q);
                    // exit;

                    $st = $con->execute($q);
                    $lot_rfid = $st->fetchall(PDO::FETCH_ASSOC);
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["OK",$lot_rfid]);
                }else if($res_check["sum_inv"]=="0"){
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","入力したのRFIDは製品連携してないです。"]);
                }else{
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode(["NG","入力したのRFIDは未登録また完成IDです。<br>検査作業できません。"]);
                }
                exit;
            }
        }

        $mno = $request->getParameter("mno");
        $mno = "Wel1";
        $process = "組立";
        $plant = "野洲工場";

        $this->mno=$mno;
        $this->process=$process;
        $this->plant=$plant;

    }

    
    public function executeActualManagement(sfWebRequest $request){
        new sfDoctrineDatabase(array(
            'name'=>'work',
            'dsn'=>'mysql:host=main-db;dbname=work',
            'username'=>'nalux-yasu',
            'password'=>'yasu-nalux'
        ));
        $con = Doctrine_Manager::connection();

        $this->getResponse()->setTitle('予実管理表 | Nalux');

        if($request->getParameter("ac")=="cav_combi"){
            $q="SELECT
                *
                , COUNT(DISTINCT t1.wwc_id) as tag_num
                , GROUP_CONCAT(t1.wic_out_class) as sum_wic_in
                , SUM(t1.hgpd_quantity) as sum_hr_in
                , SUM(t1.hgpd_qtycomplete) as sum_hr_good
                , SUM(t1.hgpd_difactive) as sum_hr_bad 
            FROM
                ( 
                    SELECT
                        wwc.*
                        , hr.*
                        , wic.*
                        , GROUP_CONCAT( 
                            DISTINCT CONCAT( 
                                wwcd.wwcd_class
                                , '=>'
                                , wwcd.wwcd_itemcode
                                , '=>'
                                , wwcd.wwcd_mold_lot
                                , '=>'
                                , wwcd.wwcd_cav
                            )
                        ) as part_class
                        , GROUP_CONCAT( 
                            DISTINCT CONCAT(wwcd.wwcd_itemcode, '=>', wic.wic_qty_out)
                        ) as wic_out_class 
                    FROM
                        work_welding_combinate wwc 
                        LEFT JOIN work_welding_combinate_detail wwcd 
                            ON wwcd.wwc_id = wwc.wwc_id 
                        LEFT JOIN hgpd_report hr 
                            ON hr.hgpd_id = wwc.wwc_hgpd_id 
                        LEFT JOIN work_inventory_control wic 
                            ON wic.wic_complete_id = hr.hgpd_id 
                            AND wic.wic_itemcode = wwcd.wwcd_itemcode 
                            AND wic.wic_itemcav = wwcd.wwcd_cav 
                            WHERE wwc.wwc_hgpd_id > 0
                    GROUP BY
                        wwc.wwc_id,
                        hr.hgpd_checkday 
                ) t1 
            GROUP BY
                part_class
                , t1.hgpd_checkday
            ORDER BY 
                t1.hgpd_checkday ASC
            ";

            $st = $con->execute($q);
            $res = $st->fetchall(PDO::FETCH_ASSOC);

            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($res);
            exit;

        }

    }

}
