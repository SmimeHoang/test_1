<?php

/**
 * MissingDefect actions.
 *
 * @package    sf_sandbox
 * @subpackage MissingDefect
 * @author     Norimasa Arima
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MissingDefectActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    if (empty($_SERVER['HTTPS'])) {
      header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
      exit;
    }
    $con = Doctrine_Manager::connection();
    $this->document_number = $request->getParameter('documentid');;
    $placeid = $request->getParameter('placeid');
    $list_place = array("undecided", "isolation", "reflected", "validity", "recognized", "rework", "quantity", "completion");
    $value = $request->getParameter('value');
    $processing_position = $request->getParameter('processing_position') + 1;
    $wbn_id = $request->getParameter('wbn_id');
    $input_das_person = $request->getParameter('input_das_person');
    $login_person = $request->getParameter('login_person');
    $data = $request->getParameter('info');
    $data_input = $request->getParameter('data_input');
    $obj_RFID_data = $request->getParameter('obj_RFID_data');
    $oh_palce = $request->getParameter("oh_palce");
    $fh_palce = $request->getParameter("fh_palce");
    $wip_palce = $request->getParameter("wip_palce");
    $vd_palce = $request->getParameter("vd_palce");
    $wicbn_data_insert = [];
    $wic_data_insert = [];
    $hgpd_id_insert = [];
    $json_list_data = [];
    $json_list_data['old'] = [];
    $json_list_data['new'] = [];
    $q_RFID = '';
    $evidence = '';
    $wbn_q = '';
    $wbn_rank = 2;
    $dept_array = array();
    if ($placeid != "") {
      $arrplace = [];
      $arrplace["1000073"] = "山崎工場";
      $arrplace["1000079"] = "野洲工場";
      $arrplace["1000085"] = "大阪工場";
      $arrplace["1000125"] = "NPG";
      $placename = $arrplace[$placeid];
      $this->placename = $placename;
      $arrdocument = [];
      $arrdocument["1000073"] = "山製-F";
      $arrdocument["1000079"] = "や品-A";
      $arrdocument["1000085"] = "大製-F";
      $arrdocument["1000125"] = "け製-F";
      $document = $arrdocument[$placeid];
      $stitle = '不具合連絡 ｜ ' . $placename;
      $this->placeid = $placeid;
      if ($request->getParameter('ac') == "判定") {
        $q_cav = "SELECT wbn_cavino FROM work.work_bug_notification WHERE wbn_id = '" . $wbn_id . "' AND wbn_placeid = '" . $placeid . "' ORDER BY created_at ASC ";
        $d_cav = $con->prepare($q_cav);
        $d_cav->execute();
        $d_cav = $d_cav->fetchall(PDO::FETCH_ASSOC);
        switch ($request->getParameter('processing_position')) {
          case 0:
            if ($request->getParameter('value') == 'OK') {
              $processing_position = 3;
              $evidence = $request->getParameter('evidence');
              $wbn_rank = 3;
            }
            $wbn_q =  "  wbn_cause                      = '" . $request->getParameter('cause') . "'";
            $wbn_q .= ", wbn_countermeasures            = '" . $request->getParameter('countermeasures') . "'";
            $wbn_q .= ", wbn_outflow_cause              = '" . $request->getParameter('outflow_cause') . "'";
            $wbn_q .= ", wbn_outflow_countermeasures    = '" . $request->getParameter('outflow_countermeasures') . "'";
            $wbn_q .= ", wbn_dept                       = '" . $request->getParameter('dept') . "'";
            $wbn_q .= ", wbn_deadline                   = '" . $request->getParameter('deadline') . "'";
            $wbn_q .= ", wbn_receipt_dt                 = '" . $request->getParameter('receipt_dt') . "'";
            $wbn_q .= ", wbn_due_dt                     = '" . $request->getParameter('due_dt') . "'";
            $wbn_q .= ", wbn_repair_sheet               = '" . $request->getParameter('repair_sheet') . "'";
            $wbn_q .= ", wbn_defect_type                = '" . $request->getParameter('defect_type') . "'";
            $wbn_q .= ", wbn_reason                     = '" . $request->getParameter('reason') . "'";
            $wbn_q .= ", wbn_decisive_evidence          = '" . $evidence . "'";
            $wbn_q .= ", wbn_decision_demand            = '" . $request->getParameter('demand') . "'";
            $wbn_q .= ", wbn_rank                       = '" . $wbn_rank . "'";
            $wbn_q .= ", wbn_decision_person            = '" . $login_person . "'";
            $wbn_q .= ", wbn_decision_date              = NOW() ";
            if ($request->getParameter('value') !='修正確定' && $request->getParameter('value') != '保存') {
              $wbn_q .= ", wbn_decision                   = '" . $request->getParameter('value') . "'";
            }
            break;
          case 1:
            $wbn_q =  "  wbn_cause                      = '" . $request->getParameter('cause') . "'";
            $wbn_q .= ", wbn_countermeasures            = '" . $request->getParameter('countermeasures') . "'";
            $wbn_q .= ", wbn_outflow_cause              = '" . $request->getParameter('outflow_cause') . "'";
            $wbn_q .= ", wbn_outflow_countermeasures    = '" . $request->getParameter('outflow_countermeasures') . "'";
            $wbn_q .= ", wbn_dept                       = '" . $request->getParameter('dept') . "'";
            $wbn_q .= ", wbn_deadline                   = '" . $request->getParameter('deadline') . "'";
            $wbn_q .= ", wbn_receipt_dt                 = '" . $request->getParameter('receipt_dt') . "'";
            $wbn_q .= ", wbn_due_dt                     = '" . $request->getParameter('due_dt') . "'";
            $wbn_q .= ", wbn_repair_sheet               = '" . $request->getParameter('repair_sheet') . "'";
            $wbn_q .= ", wbn_defect_type                = '" . $request->getParameter('defect_type') . "'";
            $wbn_q .= ", wbn_reason                     = '" . $request->getParameter('reason') . "'";
            $wbn_q .= ", wbn_defect_details             = '" . $request->getParameter('defect_details') . "'";
            $wbn_q .= ", wbn_isolation_decision_person  = '" . $input_das_person . "'";
            $wbn_q .= ", wbn_isolation_decision_login   = '" . $login_person . "'";
            $wbn_q .= ", wbn_isolation_decision_date    = NOW() ";
            if ($request->getParameter('value') == '次へ' || $request->getParameter('value') == '保存') {
              $wbn_qty_num = 0;
              if (count($obj_RFID_data) > 0) {
                foreach ($obj_RFID_data as $key => $value) {
                  $val_input_old= $value["input"]["old"];
                  $val_input_new = $value["input"]["new"];
                  $wbn_qty_num += $val_input_old["hgpd_qtycomplete"];
                  if (!$val_input_old["work_bug_notification_id"]) {
                    // 連携登録準備
                    $wicbn_data = [];
                    $wicbn_data[0] = $val_input_old["hgpd_id"];
                    $wicbn_data[1] = "'{$wbn_id}'";
                    $wicbn_data[2] = "'{$val_input_old["hgpd_rfid"]}'";
                    $wicbn_data_insert[] = "(" . join(",", $wicbn_data) . ")";
                    //  品質状態を変更するため収集
                    $hgpd_id_insert[] = $val_input_old["hgpd_id"];

                    //  事績データを登録
                    $val_input_old["key"] = "保留登録";
                    $val_input_old["wbn_id"] = $wbn_id;
                    $val_input_old["oh_palce"] = $oh_palce;
                    $val_input_old["xls_data"] = $data;
                    $val_input_old["hgpd_name"] = $data["username"];
                    $val_input_old["hgpd_namecode"] = $data["usercord"];
                    $val_input_old["hgpd_rfid_new"] = $val_input_old["hgpd_rfid"];
                    $val_input_old["hgpd_quantity"] = $val_input_old["hgpd_qtycomplete"];
                    $val_input_old["hgpd_difactive"] = 0;
                    $val_input_old["hgpd_start_at"] = date("Y/m/d h:m:s");
                    $val_input_old["hgpd_stop_at"] = date("Y/m/d h:m:s");
                    $val_input_old["hgpd_working_hours"] = 0;
                    $new_gh_id = $this->fb_insert_rfid($con, $val_input_old);

                    //  出庫在庫数計算
                    $q_wic="SELECT wic_inventry_num FROM work_inventory_control ";
                    $q_wic.="WHERE wic_itemcode = '{$val_input_old["hgpd_itemcode"]}'
                        AND wic_process = '{$val_input_old["hgpd_process"]}'
                        AND wic_process_key = '{$val_input_old["wic_process_key"]}'
                        AND wic_complete_flag = '0' ";
                    $q_wic.="AND wic_del_flg = '0' ";
                    $q_wic.="ORDER BY wic_id DESC LIMIT 1 ";
                    $st = $con->execute($q_wic);
                    $out_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                    //  在庫管理データを登録
                    $wic_data = [];
                    // 不具合が発生した工程出庫
                    $wic_data[0] = "'{$val_input_old["wic_wherhose"]}'";                                        // 場所
                    $wic_data[1] = "'{$val_input_old["hgpd_itemcode"]}'";                                       // 品目コード
                    $wic_data[2] = "'{$val_input_old["hgpd_itemform"]}'";                                       // 型番
                    $wic_data[3] = "'{$val_input_old["hgpd_process"]}'";                                        // 工程
                    $wic_data[4] = $new_gh_id;                                                                  // 実績連携ID
                    $wic_data[5] = "NOW()";                                                                     // 日付
                    $wic_data[6] = "'{$input_das_person}'";                                                     // 担当者
                    $wic_data[7] = "'{$val_input_old["hgpd_rfid"]}'";                                           // RFID
                    $wic_data[8] = "'{$val_input_old["wic_process_key"]}'";                                     // 末番
                    $wic_data[9] = "'{$val_input_old["hgpd_cav"]}'";                                            // キャビ番号
                    $wic_data[10] = 0;                                                                          // 入庫数
                    $wic_data[11] = $val_input_old["hgpd_qtycomplete"];                                         // 出庫数
                    $wic_data[12] = $out_inv_num["wic_inventry_num"] - $val_input_old["hgpd_qtycomplete"];      // 在庫数
                    $wic_data[13] = "'保留処理出庫'";                                                            // 備考
                    $wic_data[14] = "NOW()";                                                                     // 作成日時新
                    $wic_data[15] = $new_gh_id;                                                                  // 事績ID
                    $wic_data[16] = $val_input_old["hgpd_id"];                                                   // 前工程ID
                    $wic_data_insert[] = "(" . join(",", $wic_data) . ")"; // 出庫登録データ


                    //  入庫在庫数計算
                    $q_wic="SELECT wic_inventry_num FROM work_inventory_control ";
                    $q_wic.="WHERE wic_itemcode = '{$val_input_old["hgpd_itemcode"]}'
                        AND wic_process = '保留処理'
                        AND wic_process_key = '{$val_input_old["wic_process_key"]}'
                        AND wic_complete_flag = '0' ";
                    $q_wic.="AND wic_del_flg = '0' ";
                    $q_wic.="ORDER BY wic_id DESC LIMIT 1 ";
                    $st = $con->execute($q_wic);
                    $in_inv_num = $st->fetch(PDO::FETCH_ASSOC);

                    // 不具合入庫
                    $wic_data[0] = "'{$oh_palce}'";                                                             // 場所
                    $wic_data[3] = "'保留処理'";                                                                 // 工程
                    $wic_data[4] = $new_gh_id;                                                                  // 新事績ID
                    $wic_data[10] = $val_input_old["hgpd_qtycomplete"];                                         // 入庫数
                    $wic_data[11] = 0;                                                                          // 出庫数
                    $wic_data[12] = $in_inv_num["wic_inventry_num"] + $val_input_old["hgpd_qtycomplete"];       // 在庫数
                    $wic_data[13] = "'保留処理入庫'";                                                            // 備考
                    $wic_data[15] = $new_gh_id;                                                                 // 事績ID
                    $wic_data[16] = $val_input_old["hgpd_id"];                                                  // 前工程ID
                    $wic_data_insert[] = "(" . join(",", $wic_data) . ")";  // 入庫登録データ
                  }
                }
                if(count($hgpd_id_insert) > 0) $q_RFID .= "UPDATE work.hgpd_report SET hgpd_status = '異常' WHERE hgpd_id IN (" . join(",", $hgpd_id_insert) . ") ; ";  //  品質状態を変更するため収集
                if(count($wicbn_data_insert) > 0) $q_RFID .= "INSERT INTO work_inventory_control_bug_notification (hgpd_id, work_bug_notification_id, wic_rfid) VALUE " . join(",", $wicbn_data_insert) . "; ";
                if(count($wic_data_insert) > 0) {
                  $q_RFID .= "INSERT INTO work_inventory_control (
                                   wic_wherhose
                                 , wic_itemcode
                                 , wic_itemform
                                 , wic_process
                                 , wic_hgpd_id
                                 , wic_date
                                 , wic_name
                                 , wic_rfid
                                 , wic_process_key
                                 , wic_itemcav
                                 , wic_qty_in
                                 , wic_qty_out
                                 , wic_inventry_num
                                 , wic_remark
                                 , wic_created_at
                                 , wic_complete_id
                                 , wic_before_id) VALUE " . join(",", $wic_data_insert) . "; ";
                }
              } else {
                if ($request->getParameter('value') == '次へ') {
                  foreach ($d_cav as $k => $v) {
                    if (count($d_cav) == 1) {
                      $cav = '';
                    } else {
                      $cav = $v['wbn_cavino'];
                    }
                    $beforeprocess = $request->getParameter('oh_palce');
                    for ($i = 0; $i <= 3; $i++) {
                      $totalnum = 0;
                      switch ($i) {
                        case 0:
                          $totalnum = $data_input['fh_numberoftargets' . $cav];
                          $afterprocess = $request->getParameter('fh_palce');
                          $state = "0=";
                          break;
                        case 1:
                          $totalnum = $data_input['wip_numberoftargets' . $cav];
                          $afterprocess = $request->getParameter('wip_palce');
                          $state = "M=";
                          break;
                        case 2:
                          $totalnum = $data_input['vd_numberoftargets' . $cav];
                          $afterprocess = $request->getParameter('vd_palce');
                          $state = "J=";
                          break;
                        case 3:
                          $totalnum = $data_input['wip_numberoftargets_P' . $cav];
                          $afterprocess = $data_input['wip_storage_source' . $cav];
                          $state = "P=";
                          break;
                      }
                      if ($totalnum > 0) {
                        $wbn_qty_num += $totalnum;
                        try {
                          $workReport = new xlsWorkReport();
                          foreach ($data as $key => $value) {
                            $workReport->set($key, $value);
                          }
                          $workReport->set("totalnum", $totalnum);
                          $workReport->set("goodnum", $totalnum);
                          $workReport->set("pending_num", $totalnum);
                          //$workReport->set("defectivesitem", $request->getParameter('defect_item') . "=>" . $totalnum);
                          $workReport->set("remark", $wbn_id);
                          $workReport->set("measure", $v['wbn_cavino']);
                          $workReport->set("afterprocess", $afterprocess);
                          $workReport->set("place", $beforeprocess);
                          $workReport->set("state", $state);
                          $workReport->set("created_at", date("Y-m-d H:i:s"));
                          $workReport->save();
                          $get_id = $workReport->getId();
                          $qx = "INSERT INTO work.xls_work_report_sub ( xwid, putdata) VALUES ('" . $get_id . "', 0)";
                          $con->execute($qx);
                          $qwicbn = "INSERT INTO work.work_inventory_control_bug_notification ( xls_work_report_id, work_bug_notification_id) VALUES ('{$get_id}', '{$wbn_id}')";
                          $con->execute($qwicbn);
                        } catch (Exception $ex) {
                          echo ('ERORR');
                          exit;
                        }
                      }
                    }
                  }
                }
              }
              $q_RFID .= "UPDATE work.work_bug_notification SET wbn_qty=".$wbn_qty_num." WHERE wbn_id='{$wbn_id}' AND wbn_placeid='{$placeid}'; ";
            }
            break;
          case 2:
            $wbn_q = "   wbn_countermeasures            = '" . $request->getParameter('countermeasures') . "'";
            $wbn_q .= ", wbn_outflow_countermeasures    = '" . $request->getParameter('outflow_countermeasures') . "'";
            $wbn_q .= ", wbn_cause                      = '" . $request->getParameter('cause') . "'";
            $wbn_q .= ", wbn_outflow_cause              = '" . $request->getParameter('outflow_cause') . "'";
            if ($request->getParameter('corrective_input_person')) {
              $wbn_q .= ", wbn_corrective_input_person                  = '" . $request->getParameter('corrective_input_person') . "'";
            }
            $wbn_q .= ", wbn_production_system_person   = '" . $login_person . "'";
            $wbn_q .= ", wbn_production_system_date     = NOW() ";
            break;
          case 3:
            $wbn_q = "   wbn_due_details              = '" . $request->getParameter('due_details') . "'";
            $wbn_q .= ", wbn_BU_decision_person       = '" . $login_person . "'";
            $wbn_q .= ", wbn_BU_decision_date         = NOW() ";
            $wbn_q .= ", wbn_countermeasures          = '" . $request->getParameter('countermeasures') . "'";
            $wbn_q .= ", wbn_outflow_countermeasures  = '" . $request->getParameter('outflow_countermeasures') . "'";
            if (strpos($request->getParameter('outbreak_outflow'), '発生') > 0) {
              $wbn_q .= ", wbn_cause                  = '" . $request->getParameter('cause') . "入力必要'";
            } else {
              $wbn_q .= ", wbn_cause                  = '" . $request->getParameter('cause') . "'";
            }
            if (strpos($request->getParameter('outbreak_outflow'), '流出') > 0) {
              $wbn_q .= ", wbn_outflow_cause          = '" . $request->getParameter('outflow_cause') . "入力必要'";
            } else {
              $wbn_q .= ", wbn_outflow_cause          = '" . $request->getParameter('outflow_cause') . "'";
            }
            if ($request->getParameter('corrective_input_person')) {
              $wbn_q .= ", wbn_corrective_input_person                  = '" . $request->getParameter('corrective_input_person') . "'";
            }
            $wbn_q .= ", wbn_dept                       = '" . $request->getParameter('dept') . "'";
            $wbn_q .= ", wbn_deadline                   = '" . $request->getParameter('deadline') . "'";
            $wbn_q .= ", wbn_receipt_dt                 = '" . $request->getParameter('receipt_dt') . "'";
            $wbn_q .= ", wbn_due_dt                     = '" . $request->getParameter('due_dt') . "'";
            $wbn_q .= ", wbn_repair_sheet               = '" . $request->getParameter('repair_sheet') . "'";
            $wbn_q .= ", wbn_defect_type                = '" . $request->getParameter('defect_type') . "'";
            $wbn_q .= ", wbn_reason                     = '" . $request->getParameter('reason') . "'";
            if ($request->getParameter('value') == 'A1') {
              $processing_position = 7;
              $wbn_q .= ", wbn_rank                     = 1";
              $afterprocess = $oh_palce;
              $testtime = $request->getParameter('testtime');
              foreach ($d_cav as $k => $v) {
                if (count($d_cav) == 1) {
                  $cav = '';
                } else {
                  $cav = $v['wbn_cavino'];
                }
                $wbn_q_cav = " , wbn_fh_testnum            = NULL ";
                $wbn_q_cav .= ", wbn_wip_testnum           = NULL ";
                $wbn_q_cav .= ", wbn_vd_testnum            = NULL ";
                $wbn_q_cav .= ", wbn_wip_testnum_P         = NULL ";
                if ($wbn_q_cav != '') {
                  $q = 'UPDATE work.work_bug_notification SET wbn_treatment_date = NOW()' . $wbn_q_cav . ' WHERE wbn_id = ? AND wbn_cavino  = ? AND wbn_placeid = ? ';
                  $st = $con->prepare($q);
                  $st->execute(array($wbn_id, $v['wbn_cavino'], $placeid));
                }
              }
            }
            break;
          case 4:
            $wbn_q = "   wbn_content_confirmation     = '" . $request->getParameter('content_confirmation') . "'";
            $wbn_q .= ", wbn_dept                     = '" . $request->getParameter('dept') . "'";
            $wbn_q .= ", wbn_licensor_person          = '" . $login_person . "'";
            $wbn_q .= ", wbn_licensor_date            = NOW() ";
            $wbn_q .= ", wbn_countermeasures          = '" . $request->getParameter('countermeasures') . "'";
            $wbn_q .= ", wbn_outflow_countermeasures  = '" . $request->getParameter('outflow_countermeasures') . "'";
            if (strpos($request->getParameter('outbreak_outflow'), '発生') > 0) {
              $wbn_q .= ", wbn_cause                  = '" . $request->getParameter('cause') . "入力必要'";
            } else {
              $wbn_q .= ", wbn_cause                  = '" . $request->getParameter('cause') . "'";
            }
            if (strpos($request->getParameter('outbreak_outflow'), '流出') > 0) {
              $wbn_q .= ", wbn_outflow_cause          = '" . $request->getParameter('outflow_cause') . "入力必要'";
            } else {
              $wbn_q .= ", wbn_outflow_cause          = '" . $request->getParameter('outflow_cause') . "'";
            }
            if ($request->getParameter('corrective_input_person')) {
              $wbn_q .= ", wbn_corrective_input_person                  = '" . $request->getParameter('corrective_input_person') . "'";
            }
            break;
          case 5:
            $wbn_q = "   wbn_treatment                = '" . $request->getParameter('treatment') . "'";
            $wbn_q .= ", wbn_rework_instructions      = '" . $request->getParameter('rework_instructions') . "'";
            $wbn_q .= ", wbn_inventory_processing     = '" . $request->getParameter('inventory_processing') . "'";
            $wbn_q .= ", wbn_treatment_only           = '" . $request->getParameter('treatment_only') . "'";
            $wbn_q .= ", wbn_countermeasures          = '" . $request->getParameter('countermeasures') . "'";
            $wbn_q .= ", wbn_outflow_countermeasures  = '" . $request->getParameter('outflow_countermeasures') . "'";
            if ($request->getParameter('corrective_input_person')) {
              $wbn_q .= ", wbn_corrective_input_person                  = '" . $request->getParameter('corrective_input_person') . "'";
            }
            if ($request->getParameter('value') == '保存') {
              if (strpos($request->getParameter('outbreak_outflow'), '発生') > 0) {
                $wbn_q .= ", wbn_cause                  = '" . $request->getParameter('cause') . "入力必要'";
              } else {
                $wbn_q .= ", wbn_cause                  = '" . $request->getParameter('cause') . "'";
              }
              if (strpos($request->getParameter('outbreak_outflow'), '流出') > 0) {
                $wbn_q .= ", wbn_outflow_cause          = '" . $request->getParameter('outflow_cause') . "入力必要'";
              } else {
                $wbn_q .= ", wbn_outflow_cause          = '" . $request->getParameter('outflow_cause') . "'";
              }
            } elseif ($request->getParameter('value') == '次へ') {
              $wbn_q .= ", wbn_cause                    = '" . $request->getParameter('cause') . "'";
              $wbn_q .= ", wbn_outflow_cause            = '" . $request->getParameter('outflow_cause') . "'";
              $wbn_q .= ", wbn_treatment_login          = '" . $login_person . "'";
              $afterprocess = $oh_palce;
              if ($request->getParameter('processing_state') == "発生・流出書き直し") {
                $processing_position = 7;
              } elseif ($request->getParameter('rank') == 3) {
                $processing_position = 7;
                $wbn_q .= ", wbn_treatment_person         = '" . $input_das_person . "'";
                $wbn_q .= ", wbn_treatment_date           = NOW() ";
              } else {
                $wbn_q .= ", wbn_treatment_person         = '" . $input_das_person . "'";
                $wbn_q .= ", wbn_treatment_date           = NOW() ";
                if (count($obj_RFID_data) == 0){
                  $testtime = $request->getParameter('testtime');
                  foreach ($d_cav as $k => $v) {
                    $cav = count($d_cav) == 1 ? '' : $v['wbn_cavino'];
                    for ($i = 0; $i <= 3; $i++) {
                      $totalnum = 0;
                      $badnum = 0;
                      switch ($i) {
                        case 0:
                          $state = "0=";
                          $totalnum = $data_input['fh_numberoftargets' . $cav];
                          $badnum = $data_input['fh_adversenumber' . $cav];
                          $beforeprocess = $fh_palce;
                          $state = "0=";
                          $statebad = "0-";
                          break;
                        case 1:
                          $totalnum = $data_input['wip_numberoftargets' . $cav];
                          $badnum = $data_input['wip_adversenumber' . $cav];
                          $beforeprocess = $wip_palce;
                          $state = "M=";
                          $statebad = "M-";
                          break;
                        case 2:
                          $totalnum = $data_input['vd_numberoftargets' . $cav];
                          $badnum = $data_input['vd_adversenumber' . $cav];
                          $beforeprocess = $vd_palce;
                          $state = "J=";
                          $statebad = "J-";
                          break;
                        case 3:
                          $totalnum = $data_input['wip_numberoftargets_P' . $cav];
                          $badnum = $data_input['wip_adversenumber_P' . $cav];
                          $beforeprocess = $request->getParameter('wip_storage_source');
                          $state = "P=";
                          $statebad = "P-";
                          break;
                      }
                      if ($totalnum > 0) {
                        $goodnum = $totalnum - $badnum;
                        if ($badnum > 0) {
                          $workReport = new xlsWorkReport();
                          foreach ($data as $key => $value) {
                            $workReport->set($key, $value);
                          }
                          $workReport->set("totaltime", $testtime);
                          $workReport->set("totalnum", $badnum);
                          $workReport->set("badnum", $badnum);
                          $workReport->set("defectivesitem", $request->getParameter('defect_item') . "=>" . $badnum);
                          $workReport->set("remark", $wbn_id);
                          $workReport->set("measure", $v['wbn_cavino']);
                          $workReport->set("afterprocess", $afterprocess);
                          $workReport->set("place", $afterprocess);
                          $workReport->set("state", $statebad);
                          $workReport->set("created_at", date("Y-m-d H:i:s"));
                          $workReport->save();
                          $get_id = $workReport->getId();
                          $q = "INSERT INTO work.xls_work_report_defactiv (xwr_id,xwrd_ditem,xwrd_number) VALUES (".$get_id.",'".$request->getParameter('defect_item')."',".$badnum.")";
                          $con->execute($q);
                          $qx = "INSERT INTO work.xls_work_report_sub ( xwid, putdata) VALUES ('" . $get_id . "', 0)";
                          $con->execute($qx);
                          $qwicbn = "INSERT INTO work.work_inventory_control_bug_notification ( xls_work_report_id, work_bug_notification_id) VALUES ('{$get_id}', '{$wbn_id}')";
                          $con->execute($qwicbn);
                          $testtime = 0;
                        }
                        if ($goodnum > 0) {
                          $workReport = new xlsWorkReport();
                          foreach ($data as $key => $value) {
                            $workReport->set($key, $value);
                          }
                          $workReport->set("totaltime", $testtime);
                          $workReport->set("totalnum", $goodnum);
                          $workReport->set("goodnum", $goodnum);
                          $workReport->set("afterprocess", $afterprocess);
                          $workReport->set("remark", $wbn_id);
                          $workReport->set("measure", $v['wbn_cavino']);
                          $workReport->set("place", $beforeprocess);
                          $workReport->set("state", $state);
                          $workReport->set("created_at", date("Y-m-d H:i:s"));
                          $workReport->save();
                          $get_id = $workReport->getId();
                          $qx = "INSERT INTO work.xls_work_report_sub ( xwid, putdata) VALUES ('" . $get_id . "', 0)";
                          $con->execute($qx);
                          $qwicbn = "INSERT INTO work.work_inventory_control_bug_notification ( xls_work_report_id, work_bug_notification_id) VALUES ('{$get_id}', '{$wbn_id}')";
                          $con->execute($qwicbn);
                          $testtime = 0;
                        }
                      }
                    }
                    $wbn_q_cav = '';
                    if ($data_input['fh_testnum' . $cav]) {
                      $wbn_q_cav = ", wbn_fh_testnum            = " . $data_input['fh_testnum' . $cav];
                    }
                    if ($data_input['wip_testnum' . $cav]) {
                      $wbn_q_cav .= ", wbn_wip_testnum           = " . $data_input['wip_testnum' . $cav];
                    }
                    if ($data_input['vd_testnum' . $cav]) {
                      $wbn_q_cav .= ", wbn_vd_testnum            = " . $data_input['vd_testnum' . $cav];
                    }
                    if ($data_input['wip_testnum_P' . $cav]) {
                      $wbn_q_cav .= ", wbn_wip_testnum_P         = " . $data_input['wip_testnum_P' . $cav];
                    }
                    if ($wbn_q_cav != '') {
                      $q_RFID .= 'UPDATE work.work_bug_notification SET wbn_treatment_date = NOW()' . $wbn_q_cav . ' WHERE wbn_id = "' . $wbn_id . '" AND wbn_cavino  = "' . $v['wbn_cavino'] . '" AND wbn_placeid = ' . $placeid . ' ;';
                    }
                  }
                }else{
                  foreach ($obj_RFID_data as $key => $value) {
                    $hgpd_id_insert[] = $value["input"]["old"]["hgpd_id"];
                    $hgpd_id_insert[] = $value["input"]["new"]["hgpd_id"];
                    foreach ($value["output"] as $k => $val) {
                      $hgpd_id_insert[] = $val["hgpd_id"];
                    }
                  }
                  $q_RFID .= "UPDATE work.hgpd_report SET hgpd_status = '正常' WHERE hgpd_id IN (" . join(",", $hgpd_id_insert) . ") ; ";  //  品質状態を変更するため収集
                }
              }
            }
            break;
          case 6:
            $wbn_q =  "  wbn_quantity_person        = '" . $login_person . "'";
            $wbn_q .= ", wbn_quantity_date          = NOW() ";
            break;
          case 7:
            $wbn_q =  "  wbn_quality_control_person   = '" . $login_person . "'";
            $wbn_q .= ", wbn_quality_control_date     = NOW() ";
            $wbn_q .= ", wbn_restoration              = '" . $request->getParameter('restoration') . "'";
            $wbn_q .= ", wbn_confirmation             = '" . $request->getParameter('confirmation') . "'";
            if ($request->getParameter('inspection_total')) {
              $wbn_q .= ", wbn_inspection_total         = " . $request->getParameter('inspection_total');
            }
            if ($request->getParameter('inspection_bad')) {
              $wbn_q .= ", wbn_inspection_bad           = " . $request->getParameter('inspection_bad');
            }
            if ($request->getParameter('inspection_rate')) {
              $wbn_q .= ", wbn_inspection_rate          = " . $request->getParameter('inspection_rate');
            }
            $wbn_q .= ", wbn_effect                   = '" . $request->getParameter('effect') . "'";
            $wbn_q .= ", wbn_effect_NG_msg            = '" . $request->getParameter('effect_NG_msg') . "'";
            $wbn_q .= ", wbn_reissue_id               = '" . $request->getParameter('reissue_id') . "'";
            $wbn_q .= ", wbn_quality_control_comment  = '" . $request->getParameter('quality_control_comment') . "'";
            if ($request->getParameter('value') == '差戻') {
              $processing_position = 4;
              $wbn_q .= ", wbn_rejection_reason             = '" . $request->getParameter('rejection_reason') . "'";
              $wbn_q .= ", wbn_BU_decision                  = '発生・流出書き直し'";
            } else {
              $wbn_q .= ", wbn_completion_date        = NOW() ";
            }
            break;
        }
        if ($request->getParameter('value') != '修正確定' && $request->getParameter('value') != '保存') {
          $wbn_q .= ", wbn_processing_position = " . $processing_position;
        }
        $q = 'UPDATE work.work_bug_notification SET ' . $wbn_q . ' WHERE wbn_id = ? AND wbn_placeid = ? ; ' . $q_RFID;
        $st = $con->prepare($q);
        $st->execute(array($wbn_id, $placeid));
        echo 'OK';
        // echo $q;
        exit;
      }elseif ($request->getParameter('ac') == "削除") {
        $ajax_delete_data = $request->getParameter('ajax_delete_data');
        $wbn_q = " , wbn_bpartner                     = '" . $ajax_delete_data['login_person']. "' , updated_at                = NOW() ";
        $wbn_q .= ", wbn_rejection_reason             = '" . $ajax_delete_data['rejection_reason']. "'";
        if($ajax_delete_data['RFID_count'] > 0){
          $q_RFID .= " UPDATE hgpd_report SET hgpd_status = '正常' WHERE hgpd_id IN (" . join(",", $ajax_delete_data['hgpd_old_return']) . "); ";  //  品質状態を変更するため収集
          $q_RFID .= " UPDATE hgpd_report SET hgpd_del_flg = 1 WHERE hgpd_id IN (" . join(",", $ajax_delete_data['hgpd_new_delete']) . "); ";
          $q_RFID .= " UPDATE work_inventory_control SET wic_del_flg = 1 WHERE wic_hgpd_id IN (" . join(",", $ajax_delete_data['hgpd_new_delete']) . "); ";
          $q_RFID .= " UPDATE xls_work_report SET del_flg = 1 WHERE id IN (" . join(",", $ajax_delete_data['xls_new_delete']) . ") ; ";
          $q_RFID .= " UPDATE work_inventory_control_bug_notification SET wicbn_del_flg = 1 WHERE work_bug_notification_id = '{$ajax_delete_data['judgement_id']}';";
          $q_RFID .= " DELETE FROM `hgpd_report_sub` WHERE hgpd_complete_id IN (" . join(",", $ajax_delete_data['hgpd_new_delete']) . "); ";
          $q_RFID .= " DELETE FROM `hgpd_report_defectiveitem` WHERE hgpd_id IN (" . join(",", $ajax_delete_data['hgpd_new_delete']) . "); ";
        } else {
          $q_RFID .= " UPDATE xls_work_report SET del_flg = 1 WHERE id IN (SELECT xls_work_report_id FROM work_inventory_control_bug_notification WHERE work_bug_notification_id = '" . $ajax_delete_data['judgement_id'] . "' AND wicbn_del_flg = 0);";
        }
        $q_RFID .= " UPDATE xls_work_report SET del_flg = 1 WHERE remark = '{$ajax_delete_data['judgement_id']}'; ";
        $q = "UPDATE work.work_bug_notification SET wbn_processing_position = 9 " . $wbn_q . " WHERE wbn_id = '{$ajax_delete_data['judgement_id']}' AND wbn_placeid = {$placeid} ; " . $q_RFID;
        $st = $con->prepare($q);
        $st->execute();
        echo 'OK';
        exit;
      }elseif ($request->getParameter('ac') == "GetJson") {
        $sum_d = [];
        $dept_array = $request->getParameter("dept_array");
        $dept_bg = array('Blue', '#8470FF', 'Olive', 'Black', '#5BBD2B', '#8B4513', 'Gray', 'Green', 'Purple', 'Teal', 'Navy', 'Orange', '#C7C300', 'Magenta ', '#F4A460', '#1E90FF', '#FA8072', '#8B814C');
        $q = '  SELECT  WBN.* , DATE_FORMAT(WBN.created_at, "%Y-%m-%d")  AS date
                      , GROUP_CONCAT(WBN.wbn_cavino  ORDER BY WBN.wbn_cavino) AS "CAVno"
                      , XLS.remark
                      , XLS.moldplaceid
                      , SUM(XLS.fh_numberoftargets)     AS "fh_numberoftargets"
                      , SUM(XLS.fh_numberofgoods)       AS "fh_numberofgoods"
                      , SUM(XLS.fh_adversenumber)       AS "fh_adversenumber"
                      , SUM(XLS.wip_numberoftargets)    AS "wip_numberoftargets"
                      , SUM(XLS.wip_numberofgoods)      AS "wip_numberofgoods"
                      , SUM(XLS.wip_adversenumber)      AS "wip_adversenumber"
                      , SUM(XLS.wip_numberoftargets_P)  AS "wip_numberoftargets_P"
                      , SUM(XLS.wip_numberofgoods_P)    AS "wip_numberofgoods_P"
                      , SUM(XLS.wip_adversenumber_P)    AS "wip_adversenumber_P"
                      , SUM(XLS.vd_numberoftargets)     AS "vd_numberoftargets"
                      , SUM(XLS.vd_numberofgoods)       AS "vd_numberofgoods"
                      , SUM(XLS.vd_adversenumber)       AS "vd_adversenumber"
                      , SUM(WBN.wbn_wip_testnum)        AS "wbn_wip_testnum"
                      , SUM(WBN.wbn_wip_testnum_P)      AS "wbn_wip_testnum_P"
                      , SUM(WBN.wbn_fh_testnum)         AS "wbn_fh_testnum"
                      , SUM(WBN.wbn_vd_testnum)         AS "wbn_vd_testnum"
                      , SUM(XLS.totaltime)              AS "totaltime"
                      , XLS.afterprocess AS "afterprocess"
                      , MFC.mfc_count
                      , (SELECT COUNT(*) FROM work_inventory_control_bug_notification WHERE work_bug_notification_id = WBN.wbn_id AND wicbn_del_flg = 0 AND hgpd_id <> 0) AS count_RFID
                      , COUNT(*) AS wbn_count
                      , "" AS "error"
                  FROM work.work_bug_notification WBN
                  LEFT JOIN ( SELECT work_bug_notification_id as remark, measure, moldplaceid
                                    , SUM(CASE WHEN state = "0=" THEN pending_num END)                  AS "fh_numberoftargets"
                                    , SUM(CASE WHEN state = "0=" AND pending_num = 0 THEN goodnum END)  AS "fh_numberofgoods"
                                    , SUM(CASE WHEN state = "0-" THEN badnum END)                       AS "fh_adversenumber"
                                    , SUM(CASE WHEN state = "M=" THEN pending_num END)                  AS "wip_numberoftargets"
                                    , SUM(CASE WHEN state = "M=" AND pending_num = 0 THEN goodnum END)  AS "wip_numberofgoods"
                                    , SUM(CASE WHEN state = "M-" THEN badnum END)                       AS "wip_adversenumber"
                                    , SUM(CASE WHEN state = "P=" THEN pending_num END)                  AS "wip_numberoftargets_P"
                                    , SUM(CASE WHEN state = "P=" AND pending_num = 0 THEN goodnum END)  AS "wip_numberofgoods_P"
                                    , SUM(CASE WHEN state = "P-" THEN badnum END)                       AS "wip_adversenumber_P"
                                    , SUM(CASE WHEN state = "J=" THEN pending_num END)                  AS "vd_numberoftargets"
                                    , SUM(CASE WHEN state = "J=" AND pending_num = 0 THEN goodnum END)  AS "vd_numberofgoods"
                                    , SUM(CASE WHEN state = "J-" THEN badnum END)                       AS "vd_adversenumber"
                                    , SUM(totaltime)                                                    AS "totaltime"
                                    , CASE WHEN state = "P=" AND pending_num > 0 THEN afterprocess END  AS "afterprocess"
                                  FROM work.xls_work_report
                                  INNER JOIN work_inventory_control_bug_notification
                                  ON id = xls_work_report_id
                                  AND wicbn_del_flg = 0
                                  AND hgpd_id = 0
                                  WHERE moldplaceid ="' . $placeid . '"
                                    AND del_flg = 0
                                GROUP BY work_bug_notification_id, measure                                             ) XLS
                         ON WBN.wbn_id      = XLS.remark
                        AND WBN.wbn_cavino  = XLS.measure
                        AND WBN.wbn_placeid = XLS.moldplaceid
                  LEFT JOIN ( SELECT CONCAT("' . $document . '", REPLACE(mfc_extract,' . $placeid . ',"")) AS mfc_id, count(*) AS mfc_count
                                FROM manage_file_content
                                WHERE mfc_module = "MissingDefect"
                                GROUP BY mfc_extract, mfc_module) MFC
                         ON WBN.wbn_id      = MFC.mfc_id
                WHERE WBN.wbn_placeid = "' . $placeid . '"
                  AND WBN.wbn_processing_position < 8
                  AND WBN.wbn_defect_details <> "テスト用"
                  AND DATE_FORMAT(WBN.created_at, "%Y-%m-%d") BETWEEN "' . $request->getParameter("start_sel") . '"
                                                                  AND "' . $request->getParameter("end_sel") . '"';
        if ($request->getParameter('dept_sel') != '') {
          $q .= '       AND WBN.wbn_dept = "' . $request->getParameter("dept_sel") . '"';
        }
        if ($request->getParameter("documentid_sel")) {
          $q .= '      AND WBN.wbn_id = "' . $request->getParameter("documentid_sel") . '" ';
        }
        if ($request->getParameter("name_sel")) {
          $q .= '      AND WBN.wbn_item_code = "' . $request->getParameter("code_sel") . '" ';
          $q .= '      AND WBN.wbn_product_name = "' . $request->getParameter("name_sel") . '" ';
        }
        $q .= '     GROUP BY WBN.wbn_id
                    ORDER BY WBN.wbn_id DESC ';
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        foreach ($d as $k => &$v) {
          foreach ($v as $kk => &$vv) {
            if ($vv == '0000-00-00') {
              $vv = '';
            }
          }
          if ($v['wbn_processing_position'] > 3) {
            if ($v["fh_numberofgoods"] > 0 && $v["fh_adversenumber"] == '') {
              $v["fh_adversenumber"] = 0;
            }
            if ($v["wip_numberofgoods"] > 0 && $v["wip_adversenumber"] == '') {
              $v["wip_adversenumber"] = 0;
            }
            if ($v["vd_numberofgoods"] > 0 && $v["vd_adversenumber"] == '') {
              $v["vd_adversenumber"] = 0;
            }
            if ($v["wip_numberofgoods_P"] > 0 && $v["wip_adversenumber_P"] == '') {
              $v["wip_adversenumber_P"] = 0;
            }
            $sum_fh = (int) $v["fh_numberoftargets"] - (int) $v["fh_numberofgoods"] - (int) $v["fh_adversenumber"];
            $sum_wip = (int) $v["wip_numberoftargets"] - (int) $v["wip_numberofgoods"] - (int) $v["wip_adversenumber"];
            $sum_vd = (int) $v["vd_numberoftargets"] - (int) $v["vd_numberofgoods"] - (int) $v["vd_adversenumber"];
            $sum_wip_P = (int) $v["wip_numberoftargets_P"] - (int) $v["wip_numberofgoods_P"] - (int) $v["wip_adversenumber_P"];
            if (($sum_fh + $sum_wip + $sum_vd + $sum_wip_P) != 0 && $v["wbn_processing_position"] > 5) {
              $v["error"] = "error";
            }
          }
          $list_place_name = array("発見者上長", "隔離範囲決定", "生産システム反映", "BU担当", "発生部門認可", "発生部門処置担当", "生産管理係", "品質管理係");
          $position_array = array("未判定", "隔離などの数量を反映", "所要などに数量反映", "判定の妥当性", "在庫処置認可", "隔離製品の処置", "数量の反映", "効果確認");
          if ($v['wbn_alignment'] == '再発行' && !$v['wbn_BU_decision']) {
            $position_array[0] = $v['wbn_alignment'];
          } else if ($v['wbn_BU_decision'] == '再判定') {
            $position_array[0] = $v['wbn_BU_decision'];
            $position_array[1] = "再" . $position_array[1];
            $position_array[2] = "再" . $position_array[2];
            $position_array[3] = "再" . $position_array[3];
          } else if ($v['wbn_BU_decision'] == '発生・流出書き直し') {
            $position_array[4] = $v['wbn_BU_decision'];
            $position_array[5] = $v['wbn_BU_decision'];
            $position_array[6] = $v['wbn_BU_decision'];
            $position_array[7] = "再" . $position_array[7];
          }
          $v += array('position' => $position_array[$v['wbn_processing_position']]);
          $v += array('place_name' => $list_place_name[$v['wbn_processing_position']]);
          $i = array_search($v["wbn_dept"], $dept_array);
          if ($i) {
            $v += array('bg' => $dept_bg[$i]);
            $v += array('omit' => $this->fb_dept_omit($v["wbn_dept"]));
          } else {
            if (!$v["wbn_dept"] || $v["wbn_dept"] == '') {
              $v += array('bg' => '');
              $v += array('omit' => '');
            } else {
              $v += array('bg' => '');
              $v += array('omit' => $this->fb_dept_omit($v["wbn_dept"]));
            }
          }
          $sum_d[$list_place[$v['wbn_processing_position']]][] = $v;
        }
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($sum_d);
        exit;
      }elseif ($request->getParameter('ac') == "GetDataTab") {
        $sum_d = [];
        $mfc_module = $request->getParameter('mfc_module');
        $q = '  SELECT  WBN.wbn_id
                      , WBN.wbn_cavino
                      , WBN.wbn_fh_testnum
                      , WBN.wbn_wip_testnum
                      , WBN.wbn_vd_testnum
                      , WBN.wbn_wip_testnum_P
                      , WBN.wbn_processing_position
                      , XLS.*
                      , XLS_P.afterprocess AS "afterprocess"
                  FROM work.work_bug_notification WBN
                  LEFT JOIN ( SELECT work_bug_notification_id AS remark, measure, moldplaceid
                                    , SUM(CASE WHEN state = "0=" THEN pending_num END)                      AS "fh_numberoftargets"
                                    , SUM(CASE WHEN state = "0=" AND pending_num = 0 THEN goodnum END)      AS "fh_numberofgoods"
                                    , SUM(CASE WHEN state = "0-" THEN badnum END)                           AS "fh_adversenumber"
                                    , SUM(CASE WHEN state = "M=" THEN pending_num END)                      AS "wip_numberoftargets"
                                    , SUM(CASE WHEN state = "M=" AND pending_num = 0 THEN goodnum END)      AS "wip_numberofgoods"
                                    , SUM(CASE WHEN state = "M-" THEN badnum END)                           AS "wip_adversenumber"
                                    , SUM(CASE WHEN state = "P=" THEN pending_num END)                      AS "wip_numberoftargets_P"
                                    , SUM(CASE WHEN state = "P=" AND pending_num = 0 THEN goodnum END)      AS "wip_numberofgoods_P"
                                    , SUM(CASE WHEN state = "P-" THEN badnum END)                           AS "wip_adversenumber_P"
                                    , SUM(CASE WHEN state = "J=" THEN pending_num END)                      AS "vd_numberoftargets"
                                    , SUM(CASE WHEN state = "J=" AND pending_num = 0 THEN goodnum END)      AS "vd_numberofgoods"
                                    , SUM(CASE WHEN state = "J-" THEN badnum END)                           AS "vd_adversenumber"
                                  FROM work.xls_work_report
                                  INNER JOIN work_inventory_control_bug_notification
                                     ON id = xls_work_report_id
                                  WHERE moldplaceid ="' . $placeid . '"
                                    AND del_flg = 0
                                    AND work_bug_notification_id = "' . $wbn_id . '"
                                    AND wicbn_del_flg = 0
                                    AND hgpd_id = 0
                                GROUP BY work_bug_notification_id, measure) XLS
                         ON WBN.wbn_id      = XLS.remark
                        AND WBN.wbn_cavino  = XLS.measure
                        AND WBN.wbn_placeid = XLS.moldplaceid
                  LEFT JOIN ( SELECT afterprocess, remark, measure, moldplaceid
                                FROM work.xls_work_report
                               WHERE pending_num > 0
                                 AND state   = "P="
                                 AND del_flg = 0
                                 AND remark = "' . $wbn_id . '"
                            GROUP BY remark, measure, moldplaceid) XLS_P
                         ON WBN.wbn_id      = XLS_P.remark
                        AND WBN.wbn_cavino  = XLS_P.measure
                        AND WBN.wbn_placeid = XLS_P.moldplaceid
                WHERE WBN.wbn_placeid = "' . $placeid . '"
                  AND WBN.wbn_defect_details <> "テスト用"
                  AND WBN.wbn_id = "' . $wbn_id . '"
                  ORDER BY WBN.wbn_cavino ASC ';
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        foreach ($d as $k => &$v) {
          foreach ($v as $kk => &$vv) {
            if (is_null($vv)) {
              $vv = '';
            }
          }
        }
        $sum_d['data'] = $d;
        $mfc_extract = $placeid . mb_substr($wbn_id, mb_strlen($wbn_id) - 6, 6);
        $sql = ' SELECT mfc_id, mfc_type, mfc_name FROM manage_file_content WHERE mfc_module= :mfc_module AND mfc_extract= :mfc_extract ORDER BY mfc_id DESC ';
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':mfc_module', $mfc_module, PDO::PARAM_STR);
        $stmt->bindValue(':mfc_extract', $mfc_extract, PDO::PARAM_INT);
        $stmt->execute();
        $d_file = $stmt->fetchALL();
        if (count($d_file) > 0) {
          $sum_d['file'] = $d_file;
        }
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($sum_d);
        exit;
      }elseif ($request->getParameter('ac') == "Ajax_Find_List_RFID_Data") {
        $q = "SELECT HR.hgpd_id
                FROM hgpd_report HR
           LEFT JOIN work_inventory_control_bug_notification WICBN
                  ON HR.hgpd_id = WICBN.hgpd_id
                  AND WICBN.wicbn_del_flg = 0
               WHERE HR.hgpd_wherhose = '".$request->getParameter('placename')."'
                 AND HR.hgpd_itemcode = '".$request->getParameter('itemcode')."'
                 AND HR.hgpd_itemform = '".$request->getParameter('itemform')."'
                 AND HR.hgpd_moldlot  = '".$request->getParameter('lot_no')."'
                 AND HR.hgpd_status   = '正常'
                 AND HR.hgpd_process <> '保留処理'
                 AND HR.hgpd_del_flg = 0
                 AND HR.hgpd_rfid <> ''
                 AND HR.hgpd_rfid IS NOT NULL
                 AND (WICBN.wicbn_id IS NULL OR WICBN.work_bug_notification_id = '".$wbn_id."')
                 AND DATE_FORMAT(HR.hgpd_moldday, '%Y-%m-%d') BETWEEN '".$request->getParameter('find_input_start')."' AND '".$request->getParameter('find_input_end')."'
               ORDER BY HR.hgpd_id ASC ";
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchAll(PDO::FETCH_ASSOC);
        if(count($d) > 0){
          foreach ($d as $k => $v) {
            $json_list_data['old'][] = $v['hgpd_id'];
            $q = "WITH RECURSIVE temp1(id, cid, bid, cflag) AS (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_before_id = " . $v['hgpd_id'] . " UNION ALL SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_before_id = temp1.cid) SELECT * FROM temp1";
            $st = $con->prepare($q);
            $st->execute(array());
            $d_f = $st->fetchAll(PDO::FETCH_ASSOC);
            if(count($d_f) > 0){
              if (count($d_f) > 1) {
                foreach ($d_f as $k_f => $v_f) {
                  if ($v_f['bid'] != $v['hgpd_id']) {
                    if (!in_array($v_f, $json_list_data['new'])) {
                      $json_list_data['new'][] = $v_f['cid'];
                    }
                  }
                }
              } else if (count($d_f) == 1) {
                $json_list_data['new'][]  = $d_f[0]['cid'];
              }
            }else{
              $json_list_data['new'][] = $v['hgpd_id'];
            }
          }
          $array_json_list_data = join(",", $json_list_data['new']);
          $q = "SELECT HR.*
                     , HR.hgpd_id AS first_hgpd_id
                     , WIC.*
                     , 0 AS flg_insert
                FROM hgpd_report HR
           LEFT JOIN work_inventory_control WIC
                  ON HR.hgpd_rfid = WIC.wic_rfid
                 AND HR.hgpd_id = WIC.wic_hgpd_id
                 AND WIC.wic_id = (SELECT wic_id FROM work_inventory_control WHERE wic_hgpd_id = HR.hgpd_id AND wic_del_flg = 0 ORDER BY wic_id DESC LIMIT 1)
           LEFT JOIN work_inventory_control_bug_notification WICBN
                  ON HR.hgpd_id = WICBN.hgpd_id
                  AND WICBN.wicbn_del_flg = 0
              WHERE HR.hgpd_id IN ({$array_json_list_data})
                AND (WICBN.wicbn_id IS NULL OR WICBN.work_bug_notification_id = '{$wbn_id}' )
                AND HR.hgpd_del_flg = 0
                AND HR.hgpd_status = '正常'
                AND HR.hgpd_process <> '保留処理'
                ORDER BY HR.hgpd_id ASC ";
          $st = $con->prepare($q);
          $st->execute();
          $d = $st->fetchAll(PDO::FETCH_ASSOC);
          foreach ($d as $k => &$v) {
            if ($v['hgpd_process'] != '成形') {
              $q = " SELECT HR.*
                        , (SELECT wic_process FROM work_inventory_control WHERE wic_hgpd_id = HR.hgpd_id ORDER BY wic_id DESC LIMIT 1) AS wic_process
                FROM hgpd_report HR
                INNER JOIN (WITH RECURSIVE temp1(id, cid, bid, cflag) AS (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_complete_id = " . $v['hgpd_id'] . " UNION ALL SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_complete_id = temp1.bid) SELECT * FROM temp1 ) MM
                ON HR.hgpd_id = MM.bid
                WHERE HR.hgpd_del_flg = 0
                AND HR.hgpd_status = '正常'
                ORDER BY HR.hgpd_id ASC";
              $st = $con->prepare($q);
              $st->execute(array());
              $d_f = $st->fetchAll(PDO::FETCH_ASSOC);
              foreach ($d_f as $k_f => $v_f) {
                if($v_f['hgpd_process'] == '成形') $v['old_hgpd_id_data'][] = $v_f;
              }
            }else{
              $v['old_hgpd_id_data'] = [];
            }
          }
          $json_list_data['new']= $d;
        }
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($json_list_data);
        exit;
      }elseif ($request->getParameter('ac') == "Ajax_Get_RFID_Connect_Data") {
        $q = "SELECT HR.*
                   , WIC.*
                   , WICBN.wicbn_id
                   , WICBN.wic_rfid AS wicbn_rfid
                   , WICBN.xls_work_report_id AS wic_inspection
              FROM hgpd_report HR
              LEFT JOIN work_inventory_control WIC
                     ON WIC.wic_rfid = HR.hgpd_rfid
                    AND WIC.wic_hgpd_id = HR.hgpd_id
                    AND WIC.wic_del_flg = 0
              LEFT JOIN work_inventory_control_bug_notification WICBN
                     ON WICBN.hgpd_id = HR.hgpd_id
                    AND WICBN.wicbn_del_flg = 0
              WHERE HR.hgpd_del_flg = 0
              AND ( HR.hgpd_id IN (SELECT hgpd_complete_id FROM hgpd_report_sub HRS 
                                                     INNER JOIN hgpd_report HR ON HRS.`hgpd_complete_id` = HR.hgpd_id 
                                                     INNER JOIN work_inventory_control_bug_notification WICBN ON WICBN.hgpd_id = HRS.hgpd_before_id 
                                                     WHERE HR.hgpd_process = '保留処理' AND HR.hgpd_del_flg = 0 AND WICBN.wicbn_del_flg = 0 AND WICBN.work_bug_notification_id = '{$wbn_id}' )
                OR HR.hgpd_id IN (SELECT hgpd_id FROM work_inventory_control_bug_notification WHERE hgpd_id <> 0 AND work_bug_notification_id = '{$wbn_id}' AND wicbn_del_flg = 0))
              ORDER BY HR.hgpd_id ASC";
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchALL(PDO::FETCH_ASSOC);
        $json_list_data['new'] = $d;
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($json_list_data);
        exit;
      }elseif ($request->getParameter('ac') == "保留処理実行"){
        $val_input = $request->getParameter('input_data');
        $val_input_new = $val_input["rfid_data_new"];
        foreach ($val_input["inspectin_data"] as $value) {
          // データ更新
          $val_input_new["key"] = "保留処理";
          $val_input_new["oh_palce"] = $oh_palce;
          $val_input_new["xls_data"] = $data;
          $val_input_new["defect_item"] = $val_input["defect_item"];
          $val_input_new["hgpd_cav"] = $value["insert_cav"];
          $val_input_new["hgpd_id"] = $val_input_new["alignment_hgpd_id"];
          $val_input_new["hgpd_name"] = $val_input_new["xls_data"]["username"];
          $val_input_new["hgpd_namecode"] = $val_input_new["xls_data"]["usercord"];
          $val_input_new["hgpd_quantity"] = $value["result_qty"];
          $val_input_new["hgpd_qtycomplete"] = $value["result_good"];
          $val_input_new["hgpd_difactive"] = $value["result_bad"];
          $val_input_new["hgpd_remaining"] = $value["result_remaining"];
          $val_input_new["hgpd_start_at"] = $val_input["in_dt_start"];
          $val_input_new["hgpd_stop_at"] = $val_input["in_dt_end"];
          if($val_input["old_hgpd_process"] == "完成品処理" && $val_input_new["hgpd_qtycomplete"] > 0 && $val_input_new["hgpd_difactive"] > 0){
            $q="SELECT id, code, proccess_name, place_info,end_code FROM work_adempiere_item_ms WHERE `item_code` = (SELECT `befor_code` FROM work_adempiere_item_ms WHERE code = '{$val_input_new["hgpd_itemcode"]}' AND proccess_name = '完成品処理') ";
            $st = $con->execute($q);
            $back_proccess = $st->fetch(PDO::FETCH_ASSOC);
            $val_input_new["old_hgpd_process"] = $back_proccess["proccess_name"] ;
            $val_input_new["old_wic_wherhose"] = $back_proccess["place_info"];
            $val_input_new["old_wic_process_key"] = $back_proccess["end_code"];
            $val_input_new["hgpd_rfid_new"] = $value["new_rfid"];
          }else{
            $val_input_new["old_hgpd_process"] = $val_input["old_hgpd_process"];
            $val_input_new["old_wic_wherhose"] = $val_input["old_wic_wherhose"];
            $val_input_new["old_wic_process_key"] = $val_input_new["wic_process_key"];
            $val_input_new["hgpd_rfid_new"] = $val_input_new["hgpd_rfid"];
          }
          $new_gh_id = $this->fb_insert_rfid($con, $val_input_new);
          //  在庫管理データを登録
          $wic_data_insert = [];
          $wic_data = [];
          $wic_data[0] = "'{$val_input_new["oh_palce"]}'";                                            // 場所
          $wic_data[1] = "'{$val_input_new["hgpd_itemcode"]}'";                                       // 品目コード
          $wic_data[2] = "'{$val_input_new["hgpd_itemform"]}'";                                       // 型番
          $wic_data[3] = "'保留処理'";                                                                 // 工程
          $wic_data[4] = $new_gh_id;                                                                  // 実績連携ID
          $wic_data[5] = "NOW()";                                                                     // 日付
          $wic_data[6] = "'{$val_input_new["hgpd_name"] }'";                                          // 担当者
          $wic_data[7] = "'{$val_input_new["hgpd_rfid"]}'";                                           // RFID
          $wic_data[8] = "'{$val_input_new["wic_process_key"]}'";                                     // 末番
          $wic_data[9] = "'{$val_input_new["hgpd_cav"]}'";                                            // キャビ番号
          $wic_data[10] = 0;                                                                          // 入庫数
          $wic_data[11] = 0;                                                                          // 出庫数
          $wic_data[12] = 0;                                                                          // 在庫数
          $wic_data[13] = "'保留処理出庫'";                                                            // 備考
          $wic_data[14] = "NOW()";                                                                    // 作成日時新
          $wic_data[15] = $new_gh_id;                                                                 // 事績ID
          $wic_data[16] = $val_input_new["hgpd_id"];                                                  // 前工程ID

          //　　ーーーーーーーーーーー　保留処理後出庫　ーーーーーーーーーーー

          //  出庫在庫数計算
          $q_wic="SELECT wic_inventry_num FROM work_inventory_control ";
          $q_wic.="WHERE wic_itemcode = '{$val_input_new["hgpd_itemcode"]}'
              AND wic_process = '{$val_input_new["hgpd_process"]}'
              AND wic_process_key = '{$val_input_new["wic_process_key"]}'
              AND wic_complete_flag = '0' ";
          $q_wic.="AND wic_del_flg = '0' ";
          $q_wic.="ORDER BY wic_id DESC LIMIT 1 ";
          $st = $con->execute($q_wic);
          $out_inv_num = $st->fetch(PDO::FETCH_ASSOC);

          $wic_data[10] = 0;                                                                          // 入庫数
          $wic_data[11] = $val_input_new["hgpd_quantity"];                                            // 出庫数
          $wic_data[12] = $out_inv_num["wic_inventry_num"] - $val_input_new["hgpd_quantity"];         // 在庫数

          if($val_input_new["hgpd_quantity"] == $val_input_new["hgpd_difactive"]){
            //廃棄
            $wic_data[13] = "'廃棄'";                                                                  // 備考
          }else{
            //出庫
            $wic_data[13] = "'保留処理出庫'";                                                           // 備考
          }
          $wic_data_insert[] = "(" . join(",", $wic_data) . ")"; // 出庫登録データ
          //　　ーーーーーーーーーーー　保留処理後入庫　ーーーーーーーーーーー

          $wic_data[7] = "'{$val_input_new["hgpd_rfid_new"]}'";                                           // RFID
          $wic_data[11] = 0;                                                                              // 出庫数
          //残数
          if($val_input_new["hgpd_remaining"] > 0){
            $wic_data[12] = $out_inv_num["wic_inventry_num"] - $val_input_new["hgpd_quantity"] + $val_input_new["hgpd_remaining"];             // 在庫数
            $wic_data[10] = $val_input_new["hgpd_remaining"];                                            // 入庫数
            $wic_data[13] = "'保留処理残数'";                                                             // 備考
            $wic_data_insert[] = "(" . join(",", $wic_data) . ")"; // 出庫登録データ
          }else{
            $q="UPDATE work.hgpd_report SET hgpd_status = '正常' WHERE hgpd_id IN (
                    WITH RECURSIVE temp1(id, cid, bid, cflag) AS (SELECT * FROM hgpd_report_sub t1 WHERE t1.hgpd_before_id IN (SELECT hgpd_id FROM work_inventory_control_bug_notification WHERE hgpd_id <> 0 AND work_bug_notification_id = '{$val_input_new["wbn_id"]}' AND wicbn_del_flg = 0)
                    UNION ALL SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_before_id = temp1.cid)
                    SELECT DISTINCT(hgpd_id) FROM temp1 INNER JOIN hgpd_report ON hgpd_report.hgpd_id = temp1.cid OR hgpd_report.hgpd_id = temp1.bid)";
            $con->execute($q);
          }
          //処理済入庫
          if($val_input_new["hgpd_qtycomplete"] > 0){
            //  入庫在庫数計算
            $q_wic="SELECT wic_inventry_num FROM work_inventory_control ";
            $q_wic.="WHERE wic_itemcode = '{$val_input_new["hgpd_itemcode"]}'
                AND wic_process = '{$val_input_new["old_hgpd_process"]}'
                AND wic_process_key = '{$val_input_new["wic_process_key"]}'
                AND wic_complete_flag = '0' ";
            $q_wic.="AND wic_del_flg = '0' ";
            $q_wic.="ORDER BY wic_id DESC LIMIT 1 ";
            $st = $con->execute($q_wic);
            $in_inv_num = $st->fetch(PDO::FETCH_ASSOC);

            // 前の置き場・工程に入庫
            $wic_data[0] = "'{$val_input_new["old_wic_wherhose"]}'";                                     // 場所
            $wic_data[3] = "'{$val_input_new["old_hgpd_process"]}'";                                     // 工程
            $wic_data[8] = "'{$val_input_new["old_wic_process_key"]}'";                                  // 末番
            $wic_data[10] = $val_input_new["hgpd_qtycomplete"];                                          // 入庫数
            $wic_data[12] = $in_inv_num["wic_inventry_num"] + $val_input_new["hgpd_qtycomplete"];        // 在庫数
            $wic_data[13] = "'{$val_input_new["old_hgpd_process"]}入庫'";                                 // 備考
            $wic_data_insert[] = "(" . join(",", $wic_data) . ")"; // 出庫登録データ
          }

          $q_wic = "INSERT INTO work_inventory_control (
                            wic_wherhose
                          , wic_itemcode
                          , wic_itemform
                          , wic_process
                          , wic_hgpd_id
                          , wic_date
                          , wic_name
                          , wic_rfid
                          , wic_process_key
                          , wic_itemcav
                          , wic_qty_in
                          , wic_qty_out
                          , wic_inventry_num
                          , wic_remark
                          , wic_created_at
                          , wic_complete_id
                          , wic_before_id) VALUE " . join(",", $wic_data_insert) . "; ";
          $con->execute($q_wic);
          // 連携登録準備
          $q_wicbn = "INSERT INTO work_inventory_control_bug_notification (hgpd_id, work_bug_notification_id, wic_rfid, xls_work_report_id) VALUE ({$new_gh_id},'{$val_input_new["wbn_id"]}','{$val_input["old_hgpd_rfid"]}',{$value["result_in"]}); ";
          $con->execute($q_wicbn);
        }
        echo 'OK';
        exit;
      }elseif ($request->getParameter('ac') == "BU_差戻"){
        $ajax_BU_data = $request->getParameter('ajax_BU_data');
        $wbn_q = "   wbn_due_details              = '" . $ajax_BU_data['due_details'] . "'";
        $wbn_q .= ", wbn_BU_decision_person       = '" . $ajax_BU_data['login_person'] . "'";
        $wbn_q .= ", wbn_BU_decision_date         = NOW() ";
        $wbn_q .= ", wbn_countermeasures          = '" . $ajax_BU_data['countermeasures'] . "'";
        $wbn_q .= ", wbn_outflow_countermeasures  = '" . $ajax_BU_data['outflow_countermeasures'] . "'";
        $wbn_q .= ", wbn_dept                       = '" . $ajax_BU_data['dept'] . "'";
        $wbn_q .= ", wbn_deadline                   = '" . $ajax_BU_data['deadline'] . "'";
        $wbn_q .= ", wbn_receipt_dt                 = '" . $ajax_BU_data['receipt_dt'] . "'";
        $wbn_q .= ", wbn_repair_sheet               = '" . $ajax_BU_data['repair_sheet'] . "'";
        $wbn_q .= ", wbn_defect_type                = '" . $ajax_BU_data['defect_type'] . "'";
        $wbn_q .= ", wbn_reason                     = '" . $ajax_BU_data['reason'] . "'";
        $wbn_q .= ", wbn_rejection_reason           = '" . $ajax_BU_data['rejection_reason'] . "'";
        $wbn_q .= ", wbn_qty                        = 0 ";
        $wbn_q .= ", wbn_BU_decision                = '再判定'";
        $wbn_q .= ", wbn_processing_position = 0";
        if (strpos($ajax_BU_data['outbreak_outflow'], '発生') > 0) {
          $wbn_q .= ", wbn_cause                  = '" . $ajax_BU_data['cause'] . "入力必要'";
        } else {
          $wbn_q .= ", wbn_cause                  = '" . $ajax_BU_data['cause'] . "'";
        }
        if (strpos($ajax_BU_data['outbreak_outflow'], '流出') > 0) {
          $wbn_q .= ", wbn_outflow_cause          = '" . $ajax_BU_data['outflow_cause'] . "入力必要'";
        } else {
          $wbn_q .= ", wbn_outflow_cause          = '" . $ajax_BU_data['outflow_cause'] . "'";
        }
        if ($ajax_BU_data['corrective_input_person']) {
          $wbn_q .= ", wbn_corrective_input_person                  = '" . $ajax_BU_data['corrective_input_person'] . "'";
        }
        if($ajax_BU_data['RFID_count'] > 0){
        $q_RFID .= " UPDATE hgpd_report SET hgpd_status = '正常' WHERE hgpd_id IN (" . join(",", $ajax_BU_data['hgpd_old_return']) . "); ";  //  品質状態を変更するため収集
        $q_RFID .= " UPDATE hgpd_report SET hgpd_del_flg = 1 WHERE hgpd_id IN (" . join(",", $ajax_BU_data['hgpd_new_delete']) . "); ";
        $q_RFID .= " UPDATE work_inventory_control SET wic_del_flg = 1 WHERE wic_hgpd_id IN (" . join(",", $ajax_BU_data['hgpd_new_delete']) . "); ";
        $q_RFID .= " UPDATE xls_work_report SET del_flg = 1 WHERE id IN (" . join(",", $ajax_BU_data['xls_new_delete']) . ") ; ";
        $q_RFID .= " UPDATE work_inventory_control_bug_notification SET wicbn_del_flg = 1 WHERE work_bug_notification_id = '{$ajax_BU_data['judgement_id']}';";
        $q_RFID .= " DELETE FROM `hgpd_report_sub` WHERE hgpd_complete_id IN (" . join(",", $ajax_BU_data['hgpd_new_delete']) . "); ";
        } else {
          $q_RFID .= " UPDATE xls_work_report SET del_flg = 1 WHERE id IN (SELECT xls_work_report_id FROM work_inventory_control_bug_notification WHERE work_bug_notification_id = '" . $ajax_BU_data['judgement_id'] . "' AND wicbn_del_flg = 0);";
        }
        $q_RFID .= " UPDATE xls_work_report SET del_flg = 1 WHERE remark = '{$ajax_BU_data['judgement_id']}'; ";
        $q = "UPDATE work.work_bug_notification SET " . $wbn_q . " WHERE wbn_id = '{$ajax_BU_data['judgement_id']}' AND wbn_placeid = {$placeid} ; " . $q_RFID;
        $st = $con->prepare($q);
        $st->execute();
        echo 'OK';
        exit;
      }
      $file = Doctrine_Query::create()->from('msPerson p')->select('gp1,gp2,CAST(user AS CHAR) as sort')->Where('flg = ?', '0')->groupBy('gp1')->addgroupBy('gp2')->orderby('id');
      $gp2 = $file->fetchArray();
      array_push($dept_array, $placename);
      foreach ($gp2 as $itemgp) {
        if ($itemgp['gp1'] == mb_substr($placename, 0, 2) || strpos($itemgp['gp1'], "技術") > -1) {
          array_push($dept_array, $itemgp['gp2']);
        }
      }
      $this->dept_array = $dept_array;
      $this->deptlist = json_encode($dept_array, JSON_NUMERIC_CHECK);
      $q = 'SELECT DISTINCT wbn_id FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" AND wbn_processing_position < 8 ORDER BY created_at DESC;';
      $idlist = $con->prepare($q);
      $idlist->execute();
      $idlist = $idlist->fetchall(PDO::FETCH_ASSOC);
      $this->idlist = $idlist;
      $q = 'SELECT DISTINCT wbn_product_name, wbn_item_code FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" AND wbn_processing_position < 8 ORDER BY created_at DESC;';
      $namelist = $con->prepare($q);
      $namelist->execute();
      $namelist = $namelist->fetchall(PDO::FETCH_ASSOC);
      $this->namelist = $namelist;
      $q = 'SELECT DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d") AS date FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $sdate = $con->prepare($q);
      $sdate->execute();
      $sdate = $sdate->fetchall(PDO::FETCH_ASSOC);
      $this->sdate = $sdate;
      $this->list_place = $list_place;
      $this->list_place_name_H1 = array("発見者上長", "隔離範囲決定", "生産システム反映", "BU担当", "発生部門認可", "発生部門処置担当", "生産管理係", "品質管理係");
      $this->list_place_name_H4 = array("未判定", "隔離などの数量を反映", "所要などに数量反映", "判定の妥当性", "在庫処置認可", "隔離製品の処置", "数量の反映", "効果確認");
      $q = 'SELECT DISTINCT wbn_content_confirmation FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" AND wbn_content_confirmation <> "" ORDER BY created_at DESC;';
      $listevidence = $con->prepare($q);
      $listevidence->execute();
      $listevidence = $listevidence->fetchall(PDO::FETCH_ASSOC);
      $evidence_array = array();
      foreach ($listevidence as $item) {
        array_push($evidence_array, "@" . $item['wbn_content_confirmation']);
      }
      $this->listevidence = json_encode($evidence_array, JSON_NUMERIC_CHECK);
      //工程情報
      $q = "SELECT workitem_barcode,workitem_no,workitem_name,workitem_class,workitem_plant_name,workitem_user FROM ms_workitem_list WHERE workitem_plant_name = '" . $placename . "'";
      $st = $con->execute($q);
      $d = $st->fetchall(PDO::FETCH_ASSOC);
      $this->worklist = json_encode($d);
      //　2025年７月から、使用禁止になる為
      // //品目データ
      // $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
      // //$db["dsn"] = 'pgsql:dbname=adempiere host=modulesrv.nalux.local port=5432';
      // $db["user"] = 'adempiere';
      // $db["password"] = 'adempiere';
      // try {
      //   $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);
      //   $q = "SELECT m_locator_id,value,x,ad_org_id FROM m_locator  ORDER BY value ";
      //   $st = $dbh->query($q);
      //   $d = $st->fetchall(PDO::FETCH_ASSOC);
      //   $this->placelist = json_encode($d, JSON_NUMERIC_CHECK);
      // } catch (PDOException $e) {
      //   print('Error:' . $e->getMessage());
      //   die();
      // }
    } else {
      $stitle = '不具合連絡 ｜ Nalux';
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function fb_dept_omit($dept)
  {
    if (strlen($dept) <= 2) {
      return $dept;
    }
    $dept_omit = mb_substr($dept, 0, 1);
    for ($x = 0; $x < strlen($dept); $x++) {
      $omit = mb_substr($dept, $x, 1);
      if (is_numeric($omit)) {
        $dept_omit .= $omit;
      }
    }
    return $dept_omit;
  }
  public function executeRegister(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $placeid = $request->getParameter('placeid');
    $documentid = $request->getParameter('documentid');
    // $oh_palce = $request->getParameter("oh_palce");
    // $fh_palce = $request->getParameter("fh_palce");
    // $wip_palce = $request->getParameter("wip_palce");
    // $vd_palce = $request->getParameter("vd_palce");
    $this->documentid = $documentid;
    $fpath = "files/bug_notification/img/";
    if ($placeid != "") {
      $arrplace = [];
      $arrplace["1000073"] = "山崎工場";
      $arrplace["1000079"] = "野洲工場";
      $arrplace["1000085"] = "大阪工場";
      $arrplace["1000125"] = "NPG";
      $this->placeinfo = $arrplace;
      $placename = $arrplace[$placeid];
      $arrdocument = [];
      $arrdocument["1000073"] = "山製-F";
      $arrdocument["1000079"] = "や品-A";
      $arrdocument["1000085"] = "大製-F";
      $arrdocument["1000125"] = "け製-F";
      $document = $arrdocument[$placeid];
      $this->document = $document;
      $stitle = '不具合連絡 ｜ 登録 ｜ ' . $placename;
      $this->placeid = $placeid;
      $q = "SELECT MAX(wbn_id) FROM work.work_bug_notification WHERE wbn_placeid = '" . $placeid . "'";
      $idmax = $con->prepare($q);
      $idmax->execute();
      $idmax = $idmax->fetch(PDO::FETCH_ASSOC);
      $nummax = mb_substr($idmax['MAX(wbn_id)'], 4, 6);
      $yearmax = mb_substr($idmax['MAX(wbn_id)'], 4, 2);
      if ($yearmax  == date("y")) {
        $idmax = $document . ($nummax + 1);
        $next_wbn_id = $document  . ($nummax + 2);
      } else {
        $idmax = $document . date("y") . '0001';
        $next_wbn_id = $document . date("y") . '0002';
      }
      $this->idmax = $idmax;
      if ($request->getParameter('ac') == "del_img") {
        $mfc_id = $request->getParameter('mfc_id');
        $q = "DELETE FROM work.manage_file_content WHERE mfc_id = " . $mfc_id;
        $st = $con->prepare($q);
        $st->execute();
        echo '___OK___';
        exit;
      } else if ($request->getParameter('ac') == '登録更新') {
        $datas = $request->getParameter('data');
        $save_data = array();
        if ($request->getParameter('value') == '更新') {
          $q = "SELECT * FROM work.work_bug_notification WHERE wbn_id = '" . $datas[0] . "' AND wbn_placeid = '" . $placeid . "' ";
          $save_bug = $con->prepare($q);
          $save_bug->execute();
          $save_bug = $save_bug->fetchall(PDO::FETCH_ASSOC);
          foreach ($save_bug[0] as $key => $item) {
            if ($item == "" && $key != 'wbn_alignment') {
              array_push($save_data, "NULL");
            } else {
              array_push($save_data, "'{$item}'");
            }
          }
          $q = "DELETE FROM work.work_bug_notification WHERE wbn_id = '" . $datas[0] . "' AND wbn_placeid = '" . $placeid . "' ";
          $st = $con->prepare($q);
          $st->execute();
          $next_wbn_id = $idmax;
        } else if ($request->getParameter('value') == '再発行') {
          if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header("WWW-Authenticate: Basic realm='ReportData-Management'");
            header('HTTP/1.0 401 Unauthorized');
            //キャンセル時の表示
            echo 'なんで？...';
            exit;
          } else {
            $id = $this->Auth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            if ($id == false) {
              //認証エラーの処理
              //unset($_SERVER['PHP_AUTH_USER']);
              //unset($_SERVER['PHP_AUTH_PW']);
              //unset($_SERVER['PHP_AUTH_TYPE']);
              header("WWW-Authenticate: Basic realm='ReportData-Management'");
              header('HTTP/1.0 401 Unauthorized');
              //setcookie('co_username', 0, time() - 1800);
              //$this->redirect('/common/AuthenticationFailure');
              echo '__エラー...__ユーザー名かパスワードが間違っています。ブラウザを終了してやり直して下さい。';
              exit;
            }
          }
          if ($id == $request->getParameter('quality_control_person')) {
            $q = 'UPDATE work.work_bug_notification SET
                    wbn_processing_position = 0
                  , wbn_rejection_reason = "' . $request->getParameter('rejection_reason') . '"
                  , wbn_wip_testnum                  = NULL
                  , wbn_wip_testnum_P                = NULL
                  , wbn_fh_testnum                   = NULL
                  , wbn_vd_testnum                   = NULL
                  , wbn_BU_decision                  = NULL
                  , wbn_alignment                    = "再発行"
                  WHERE wbn_id = "' . $datas[0] . '" AND wbn_placeid = "' . $placeid . '"';
            $st = $con->prepare($q);
            $st->execute();
            $qx = 'UPDATE work.xls_work_report SET del_flg = 1 WHERE remark = "' . $datas[0] . '" AND moldplaceid = "' . $placeid . '"';
            $st = $con->prepare($qx);
            $st->execute();
            echo '再発行しました。|' . $datas[0];
          } else {
            echo '再発行をしたい場合「' . $request->getParameter('quality_control_person') . 'さん」に連絡して下さい。|' . $datas[0];
          }
          exit;
        } else {
          $datas[0] = $idmax;
        }
        $wbn_bug = $datas[3];
        $wbn_class = $datas[5];
        $wbn_cav = $datas[19];
        $list_classbug_check = array("ヒューマンエラー", "蒸着系", "組立不良", "ゴミ", "KS", "外注不良", "成形機系", "その他");
        $list_GCMbug_check = array("GCM異常", "設備調整不良", "検知不良", "吸着不良", "歯抜け");
        if (in_array($wbn_class, $list_classbug_check)) {
          //複数キャビ番号をそのまま処理
          $arr_cav = array($wbn_cav);
        } else if ($wbn_class == 'GCM系' && in_array($wbn_bug, $list_GCMbug_check)) {
          //複数キャビ番号をそのまま処理
          $arr_cav = array($wbn_cav);
        } else if ($wbn_class == '乾燥機' && $wbn_bug == '乾燥機異常') {
          //複数キャビ番号をそのまま処理
          $arr_cav = array($wbn_cav);
        } else {
          //複数キャビ番号を分かれて処理
          $arr_cav = explode(",", $wbn_cav);
        }
        $save_data_insert = array();
        if ($request->getParameter('value') == '更新') {
          foreach ($arr_cav as $key => $cav) {
            $save_data[1] = "'{$datas[1]}'";
            $save_data[2] = "'{$datas[2]}'";
            $save_data[9] = "'{$datas[3]}'";
            $save_data[21] = "'{$datas[4]}'";
            $save_data[22] = "'{$datas[5]}'";
            $save_data[10] = "'{$datas[6]}'";
            $save_data[12] = "'{$datas[7]}'";
            $save_data[11] = "'{$datas[8]}'";
            $save_data[24] = "'{$datas[9]}'";
            $save_data[14] = "'{$datas[10]}'";
            $save_data[15] = "'{$datas[11]}'";
            $save_data[16] = "'{$datas[12]}'";
            $save_data[7] = "'{$datas[13]}'";
            $save_data[17] = "'{$datas[14]}'";
            $save_data[18] = "'{$datas[15]}'";
            $save_data[19] = "'{$datas[16]}'";
            $save_data[20] = "'{$datas[17]}'";
            $save_data[4] = "'{$datas[18]}'";
            $save_data[6] = "'{$cav}'";
            $save_data_insert[] = "(" . join(",", $save_data) . ")";
          }
          $q = "INSERT INTO work.work_bug_notification  VALUE " . join(",", $save_data_insert);
          $st = $con->prepare($q);
          $st->execute();
        } else {
          $save_data = [];
          foreach ($datas as $key => $item) {
            array_push($save_data, "'{$item}'");
          }
          $save_data[20] = "'{$placeid}'";
          $save_data[21] = "NOW()";
          foreach ($arr_cav as $key => $cav) {
            $save_data[19] = "'{$cav}'";
            $save_data_insert[] = "(" . join(",", $save_data) . ")";
          }

          $q = "INSERT INTO work.work_bug_notification (
                                  wbn_id
                                , wbn_product_name
                                , wbn_item_code
                                , wbn_defect_item
                                , wbn_project
                                , wbn_classification
                                , wbn_lot_no
                                , wbn_vd_dt
                                , wbn_mold_dt
                                , wbn_defect_details
                                , wbn_insp_qty
                                , wbn_bad_qty
                                , wbn_bad_rate
                                , wbn_form_no
                                , wbn_discoverer
                                , wbn_usercord
                                , wbn_usergp1
                                , wbn_usergp2
                                , wbn_bu
                                , wbn_cavino
                                , wbn_placeid
                                , created_at
                                )
                                  VALUE " . join(",", $save_data_insert);
          $st = $con->prepare($q);
          $st->execute();
          //exec("python3 /var/www/symfony/web/track/python/DefectiveReportContact.py >> /dev/null &");
          // $this->fb_php_slack_send(null); // ホアン　2024/01/26　通信方法変更
          // 24.07.25 Teamsコネクタ廃止によりワークフローで対応するように変更 有馬
          file_get_contents("http://".$_SERVER['SERVER_NAME']."/MissingDefect/Powerautomate2Teams");
        }
        echo '__OK__|' . $datas[0] . '|' . $next_wbn_id;
        exit;
      } else if ($request->getParameter('ac') == "GetJsonXWR") {
        $sum_d = [];
        $wbn_id = $request->getParameter('wbn_id');
        $mfc_module = $request->getParameter('mfc_module');
        $q = '  SELECT  WBN.wbn_id
                      , WBN.wbn_cavino
                      , WBN.wbn_fh_testnum
                      , WBN.wbn_wip_testnum
                      , WBN.wbn_vd_testnum
                      , WBN.wbn_wip_testnum_P
                      , WBN.wbn_processing_position
                      , XLS.*
                  FROM work.work_bug_notification WBN
                  LEFT JOIN ( SELECT work_bug_notification_id AS remark, measure, moldplaceid
                                    , SUM(CASE WHEN state = "0=" THEN pending_num END)                    AS "fh_numberoftargets"
                                    , SUM(CASE WHEN state = "0=" AND pending_num = 0 THEN goodnum END)    AS "fh_numberofgoods"
                                    , SUM(CASE WHEN state = "0-" THEN badnum END)                         AS "fh_adversenumber"
                                    , SUM(CASE WHEN state = "M=" THEN pending_num END)                    AS "wip_numberoftargets"
                                    , SUM(CASE WHEN state = "M=" AND pending_num = 0 THEN goodnum END)    AS "wip_numberofgoods"
                                    , SUM(CASE WHEN state = "M-" THEN badnum END)                         AS "wip_adversenumber"
                                    , SUM(CASE WHEN state = "P=" THEN pending_num END)                    AS "wip_numberoftargets_P"
                                    , SUM(CASE WHEN state = "P=" AND pending_num = 0 THEN goodnum END)    AS "wip_numberofgoods_P"
                                    , SUM(CASE WHEN state = "P-" THEN badnum END)                         AS "wip_adversenumber_P"
                                    , SUM(CASE WHEN state = "J=" THEN pending_num END)                    AS "vd_numberoftargets"
                                    , SUM(CASE WHEN state = "J=" AND pending_num = 0 THEN goodnum END)    AS "vd_numberofgoods"
                                    , SUM(CASE WHEN state = "J-" THEN badnum END)                         AS "vd_adversenumber"
                                    , CASE WHEN state = "P=" AND pending_num > 0 THEN afterprocess END    AS "afterprocess"
                                  FROM work.xls_work_report
                            INNER JOIN work_inventory_control_bug_notification
                                    ON id = xls_work_report_id
                                 WHERE moldplaceid ="' . $placeid . '"
                                   AND del_flg = 0
                                   AND work_bug_notification_id = "' . $wbn_id . '"
                                   AND wicbn_del_flg = 0
                                   AND hgpd_id = 0
                                GROUP BY work_bug_notification_id, measure                                             ) XLS
                         ON WBN.wbn_id       = XLS.remark
                        AND WBN.wbn_cavino   = XLS.measure
                        AND WBN.wbn_placeid  = XLS.moldplaceid
                WHERE WBN.wbn_placeid = "' . $placeid . '"
                  AND WBN.wbn_id = "' . $wbn_id . '"
                  AND WBN.wbn_defect_details <> "テスト用"
                  GROUP BY WBN.wbn_cavino
                  ORDER BY WBN.wbn_cavino ASC ';
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        foreach ($d as $k => &$v) {
          foreach ($v as $kk => &$vv) {
            if (is_null($vv)) {
              $vv = '';
            }
          }
        }
        $sum_d['data'] = $d;
        $mfc_extract = $placeid . mb_substr($wbn_id, mb_strlen($wbn_id) - 6, 6);
        $sql = ' SELECT mfc_id, mfc_type, mfc_name FROM manage_file_content WHERE mfc_module= :mfc_module AND mfc_extract= :mfc_extract ORDER BY mfc_id DESC ';
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':mfc_module', $mfc_module, PDO::PARAM_STR);
        $stmt->bindValue(':mfc_extract', $mfc_extract, PDO::PARAM_INT);
        $stmt->execute();
        $d_file = $stmt->fetchALL();
        if (count($d_file) > 0) {
          $sum_d['file'] = $d_file;
        }
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($sum_d);
        exit;
      } else if ($request->getParameter('ac') == "GetJson") {
        $q = '  SELECT  WBN.* , DATE_FORMAT(WBN.created_at, "%Y-%m-%d")  AS date
                      , DATE_FORMAT(WBN.created_at, "%c月") AS "month"
                      , GROUP_CONCAT(WBN.wbn_cavino  ORDER BY WBN.wbn_cavino) AS "CAVno"
                      , XLS.remark
                      , XLS.moldplaceid
                      , SUM(XLS.fh_numberoftargets)     AS "fh_numberoftargets"
                      , SUM(XLS.fh_numberofgoods)       AS "fh_numberofgoods"
                      , SUM(XLS.fh_adversenumber)       AS "fh_adversenumber"
                      , SUM(XLS.wip_numberoftargets)    AS "wip_numberoftargets"
                      , SUM(XLS.wip_numberofgoods)      AS "wip_numberofgoods"
                      , SUM(XLS.wip_adversenumber)      AS "wip_adversenumber"
                      , SUM(XLS.wip_numberoftargets_P)  AS "wip_numberoftargets_P"
                      , SUM(XLS.wip_numberofgoods_P)    AS "wip_numberofgoods_P"
                      , SUM(XLS.wip_adversenumber_P)    AS "wip_adversenumber_P"
                      , SUM(XLS.vd_numberoftargets)     AS "vd_numberoftargets"
                      , SUM(XLS.vd_numberofgoods)       AS "vd_numberofgoods"
                      , SUM(XLS.vd_adversenumber)       AS "vd_adversenumber"
                      , SUM(WBN.wbn_wip_testnum)        AS "wbn_wip_testnum"
                      , SUM(WBN.wbn_wip_testnum_P)      AS "wbn_wip_testnum_P"
                      , SUM(WBN.wbn_fh_testnum)         AS "wbn_fh_testnum"
                      , SUM(WBN.wbn_vd_testnum)         AS "wbn_vd_testnum"
                      , SUM(XLS.totaltime)              AS "totaltime"
                      , WBN.wbn_qty                     AS "wbn_qty"
                      , XLS.afterprocess AS "afterprocess"
                      , MFC.mfc_count
                      , "" AS "error"
                      , (SELECT COUNT(*) FROM work_inventory_control_bug_notification WHERE work_bug_notification_id = WBN.wbn_id AND wicbn_del_flg = 0 AND hgpd_id <> 0) AS count_RFID
                  FROM work.work_bug_notification WBN
                  LEFT JOIN ( SELECT work_bug_notification_id AS remark, measure, moldplaceid
                                    , SUM(CASE WHEN state = "0=" THEN pending_num END)                  AS "fh_numberoftargets"
                                    , SUM(CASE WHEN state = "0=" AND pending_num = 0 THEN goodnum END)  AS "fh_numberofgoods"
                                    , SUM(CASE WHEN state = "0-" THEN badnum END)                       AS "fh_adversenumber"
                                    , SUM(CASE WHEN state = "M=" THEN pending_num END)                  AS "wip_numberoftargets"
                                    , SUM(CASE WHEN state = "M=" AND pending_num = 0 THEN goodnum END)  AS "wip_numberofgoods"
                                    , SUM(CASE WHEN state = "M-" THEN badnum END)                       AS "wip_adversenumber"
                                    , SUM(CASE WHEN state = "P=" THEN pending_num END)                  AS "wip_numberoftargets_P"
                                    , SUM(CASE WHEN state = "P=" AND pending_num = 0 THEN goodnum END)  AS "wip_numberofgoods_P"
                                    , SUM(CASE WHEN state = "P-" THEN badnum END)                       AS "wip_adversenumber_P"
                                    , SUM(CASE WHEN state = "J=" THEN pending_num END)                  AS "vd_numberoftargets"
                                    , SUM(CASE WHEN state = "J=" AND pending_num = 0 THEN goodnum END)  AS "vd_numberofgoods"
                                    , SUM(CASE WHEN state = "J-" THEN badnum END)                       AS "vd_adversenumber"
                                    , SUM(totaltime)                                                    AS "totaltime"
                                    , CASE WHEN state = "P=" AND pending_num > 0 THEN afterprocess END  AS "afterprocess"
                                  FROM work.xls_work_report
                            INNER JOIN work_inventory_control_bug_notification
                                    ON id = xls_work_report_id
                                    AND wicbn_del_flg = 0
                                    AND hgpd_id = 0
                                 WHERE moldplaceid ="' . $placeid . '"
                                   AND del_flg = 0
                                GROUP BY work_bug_notification_id, measure                                             ) XLS
                         ON WBN.wbn_id      = XLS.remark
                        AND WBN.wbn_cavino  = XLS.measure
                        AND WBN.wbn_placeid = XLS.moldplaceid
                  LEFT JOIN ( SELECT CONCAT("' . $document . '", REPLACE(mfc_extract,' . $placeid . ',"")) AS mfc_id, count(*) AS mfc_count
                                FROM manage_file_content
                                WHERE mfc_module = "MissingDefect"
                                GROUP BY mfc_extract, mfc_module) MFC
                         ON WBN.wbn_id      = MFC.mfc_id
                WHERE WBN.wbn_placeid = "' . $placeid . '"
                  AND WBN.wbn_defect_details <> "テスト用"
                  AND DATE_FORMAT(WBN.created_at, "%Y-%m-%d") BETWEEN "' . $request->getParameter("start_sel") . '"
                                                                  AND "' . $request->getParameter("end_sel") . '"';
        if ($request->getParameter('dept_sel') != '') {
          $q .= '       AND WBN.wbn_dept = "' . $request->getParameter("dept_sel") . '"';
        }
        if ($request->getParameter("documentid_sel")) {
          $q .= '      AND WBN.wbn_id = "' . $request->getParameter("documentid_sel") . '" ';
        }
        if ($request->getParameter("name_sel")) {
          $q .= '      AND WBN.wbn_item_code = "' . $request->getParameter("code_sel") . '" ';
          $q .= '      AND WBN.wbn_product_name = "' . $request->getParameter("name_sel") . '" ';
        }
        $q .= '     GROUP BY WBN.wbn_id
                    ORDER BY WBN.wbn_id DESC ';
        $st = $con->execute($q);
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        if (count($d) > 0) {
          $json = "[";
          foreach ($d as $key => &$v) {
            foreach ($v as $kk => &$vv) {
              if ($vv == '0000-00-00') {
                $vv = '';
              }
            }
            if($v["count_RFID"] > 0){
              $q = "SELECT HR.*
                          , WIC.*
                          , WICBN.wicbn_id
                          , WICBN.wic_rfid AS wicbn_rfid
                          , WICBN.xls_work_report_id AS wic_inspection
                    FROM hgpd_report HR
                    INNER JOIN (WITH RECURSIVE temp1(id, cid, bid, cflag) AS (SELECT * FROM hgpd_report_sub t1
                                WHERE t1.hgpd_before_id IN (SELECT hgpd_id FROM work_inventory_control_bug_notification WHERE hgpd_id <> 0 AND work_bug_notification_id = '{$v["wbn_id"]}' AND wicbn_del_flg = 0)
                                UNION ALL SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_before_id = temp1.cid)
                                SELECT DISTINCT(hgpd_id) FROM temp1 INNER JOIN hgpd_report ON hgpd_report.hgpd_id = temp1.cid OR hgpd_report.hgpd_id = temp1.bid) MM
                            ON HR.hgpd_id = MM.hgpd_id
                    LEFT JOIN work_inventory_control WIC
                            ON WIC.wic_rfid = HR.hgpd_rfid
                          AND WIC.wic_hgpd_id = HR.hgpd_id
                          AND WIC.wic_del_flg = 0
                    LEFT JOIN work_inventory_control_bug_notification WICBN
                            ON WICBN.hgpd_id = HR.hgpd_id
                          AND WICBN.wicbn_del_flg = 0
                    WHERE HR.hgpd_del_flg = 0
                    ORDER BY HR.hgpd_id ASC";
              $st = $con->execute($q);
              $d_rfid = $st->fetchALL(PDO::FETCH_ASSOC);
              $v["wip_numberoftargets"] = 0;
              foreach ($d_rfid as $key_rfid => $val_rfid ) {
                if($val_rfid["wicbn_id"]){
                  if($val_rfid["hgpd_process"]!="保留処理"){
                    switch ($val_rfid["wic_process_key"]) {
                      case "0":
                        $v["fh_numberoftargets"] += (int)$val_rfid["hgpd_qtycomplete"];
                        break;
                      case "P":
                        $v["wip_numberoftargets_P"] += (int)$val_rfid["hgpd_qtycomplete"];
                        break;
                      case "J":
                        $v["vd_numberoftargets"] += (int)$val_rfid["hgpd_qtycomplete"];
                        break;
                      default:
                      $v["wip_numberoftargets"] += (int)$val_rfid["hgpd_qtycomplete"];
                    }
                  }else{
                    switch ($val_rfid["wic_process_key"]) {
                      case "0":
                        $v["wbn_fh_testnum"] += (int)$val_rfid["wic_inspection"];
                        $v["fh_numberofgoods"] += (int)$val_rfid["hgpd_qtycomplete"];
                        $v["fh_adversenumber"] += (int)$val_rfid["hgpd_difactive"];
                        break;
                      case "P":
                        $v["wbn_wip_testnum"] += (int)$val_rfid["wic_inspection"];
                        $v["wip_numberofgoods_P"] += (int)$val_rfid["hgpd_qtycomplete"];
                        $v["wip_adversenumber_P"] += (int)$val_rfid["hgpd_difactive"];
                        break;
                      case "J":
                        $v["wbn_vd_testnum"] += (int)$val_rfid["wic_inspection"];
                        $v["vd_numberofgoods"] += (int)$val_rfid["hgpd_qtycomplete"];
                        $v["vd_adversenumber"] += (int)$val_rfid["hgpd_difactive"];
                        break;
                      default:
                      $v["wbn_wip_testnum"] += (int)$val_rfid["wic_inspection"];
                      $v["wip_numberofgoods"] += (int)$val_rfid["hgpd_qtycomplete"];
                      $v["wip_adversenumber"] += (int)$val_rfid["hgpd_difactive"];
                    }
                  }
                }
              }
            }
            $json .= "[";                                                                //
            $sum_fh = (int) $v["fh_numberoftargets"] - (int) $v["fh_numberofgoods"] - (int) $v["fh_adversenumber"];
            $sum_wip = (int) $v["wip_numberoftargets"] - (int) $v["wip_numberofgoods"] - (int) $v["wip_adversenumber"];
            $sum_vd = (int) $v["vd_numberoftargets"] - (int) $v["vd_numberofgoods"] - (int) $v["vd_adversenumber"];
            $sum_wip_P = (int) $v["wip_numberoftargets_P"] - (int) $v["wip_numberofgoods_P"] - (int) $v["wip_adversenumber_P"];
            $var_attached = '<div style="float:left"><span class="ui-icon ui-icon-print"></span></div>';
            if ($v["mfc_count"] > 0) {
              $var_attached .= '<div style="float:left"><span class="ui-icon ui-icon-document"></span></div>';     //
            } 
            if (($sum_fh + $sum_wip + $sum_vd + $sum_wip_P) != 0 && $v["wbn_processing_position"] > 5) {
              $var_attached .= '<div style="float:left"><span class="ui-icon ui-icon-alert"></span></div>';     //
            }
            $json .= json_encode($var_attached) . ",";     //
            $json .= json_encode($v["wbn_id"]) . ",";                                   // 資料番号
            $position_array = array("未判定", "隔離などの数量を反映", "所要などに数量反映", "判定の妥当性", "在庫処置認可", "隔離製品の処置", "数量の反映", "効果確認", "完了", "削除済");
            if ($v['wbn_alignment'] == '再発行' && !$v['wbn_BU_decision']) {
              $position_array[0] = $v['wbn_alignment'];
            } else if ($v['wbn_BU_decision'] == '再判定') {
              $position_array[0] = $v['wbn_BU_decision'];
              $position_array[1] = "再" . $position_array[1];
              $position_array[2] = "再" . $position_array[2];
              $position_array[3] = "再" . $position_array[3];
            } else if ($v['wbn_BU_decision'] == '発生・流出書き直し') {
              $position_array[4] = $v['wbn_BU_decision'];
              $position_array[5] = $v['wbn_BU_decision'];
              $position_array[6] = $v['wbn_BU_decision'];
              $position_array[7] = "再" . $position_array[7];
            }
            $json .= json_encode($position_array[$v["wbn_processing_position"]]) . ","; // 処理位置
            $json .= json_encode($v["wbn_discoverer"]) . ",";                           // 不良発見者
            $json .= json_encode($v["date"]) . ",";                                     // 作成日時
            $json .= json_encode($v["month"]) . ",";                                    // 月
            $json .= json_encode($v["wbn_product_name"]) . ",";                         // 品名
            $json .= json_encode($v["wbn_form_no"], JSON_NUMERIC_CHECK) . ",";          // 型番
            $json .= json_encode($v["wbn_item_code"]) . ",";                            // 品目コード
            $json .= json_encode($v["CAVno"]) . ",";                                    // キャビ番号
            $json .= json_encode($v["wbn_defect_item"]) . ",";                          // 不良内容
            $json .= json_encode($v["wbn_project"]) . ",";                              // 小分類
            $json .= json_encode($v["wbn_classification"]) . ",";                       // 分類
            $json .= json_encode($v["wbn_defect_details"]) . ",";                       // 不具合詳細
            $json .= json_encode($v["wbn_lot_no"], JSON_NUMERIC_CHECK) . ",";           // ロットNo.
            $json .= json_encode($v["wbn_vd_dt"]) . ",";                                // 蒸着ロット.
            $json .= json_encode($v["wbn_mold_dt"]) . ",";                              // 成形日）
            $json .= json_encode($v["wbn_qty"], JSON_NUMERIC_CHECK) . ",";              // 対象数（返却数
            $json .= json_encode($v["wbn_insp_qty"], JSON_NUMERIC_CHECK) . ",";         // 検査数
            $json .= json_encode($v["wbn_bad_qty"], JSON_NUMERIC_CHECK) . ",";          // 不良数
            if ($v["wbn_bad_rate"] > 0) {
              $json .= json_encode($this->floattostr($v["wbn_bad_rate"]) . "%") . ",";               // 不良率
            } else {
              $json .= json_encode($v["wbn_bad_rate"]) . ",";                    // 不良率
            }
            $json .= json_encode($v["wbn_rank"], JSON_NUMERIC_CHECK) . ",";             // ランク
            $json .= json_encode($v["wbn_bu"]) . ",";                                   // BU
            $json .= json_encode($v["wbn_decision"]) . ",";                             // 判定
            $json .= json_encode($v["wbn_decision_person"]) . ",";                      // 判定者
            $json .= json_encode($v["wbn_decision_date"]) . ",";                        // 判定日時
            $json .= json_encode($v["wbn_decisive_evidence"]) . ",";                    // 判定根拠
            $json .= json_encode($v["wbn_decision_demand"]) . ",";                      // 判定要望事項
            $json .= json_encode($v["wbn_dept"]) . ",";                                 // 宛先部署
            $json .= json_encode($v["wbn_deadline"]) . ",";                             // 処置期限
            $json .= json_encode($v["wbn_receipt_dt"]) . ",";                           // 受理日
            $json .= json_encode($v["wbn_defect_type"]) . ",";                          // 欠点分類
            $json .= json_encode($v["wbn_reason"]) . ",";                               // 発行理由
            $json .= json_encode($v["wbn_due_dt"]) . ",";                               // 処置回答指示日
            $json .= json_encode($v["wbn_due_details"]) . ",";                          // 要望事項
            $json .= json_encode($v["wbn_BU_decision_person"]) . ",";                   // BU担当者
            $json .= json_encode($v["wbn_BU_decision_date"]) . ",";                     // BU処理日時
            $json .= json_encode($v["wbn_repair_sheet"]) . ",";                         // 修理依頼書発行
            $json .= json_encode($v["wbn_repair_sheet_no"], JSON_NUMERIC_CHECK) . ",";  // 修理依頼書番号
            $json .= json_encode($v["wbn_product_details"]) . ",";                      // 現品確認詳細
            $json .= json_encode($v["wbn_treatment"]) . ",";                            // 処置内容(廃棄/特採（修理あり）/特採（修理なし）/選別/手直し/その他)
            $json .= json_encode($v["fh_numberoftargets"], JSON_NUMERIC_CHECK) . ",";   // 完成品(対象数)
            $json .= json_encode($v["wbn_fh_testnum"], JSON_NUMERIC_CHECK) . ",";       // 完成品(検査数)
            $json .= json_encode($v["fh_numberofgoods"], JSON_NUMERIC_CHECK) . ",";     // 完成品(良品数)
            $json .= json_encode($v["fh_adversenumber"], JSON_NUMERIC_CHECK) . ",";     // 完成品(不良数)
            $json .= json_encode($v["wip_numberoftargets"], JSON_NUMERIC_CHECK) . ",";  // 仕掛品(対象数)
            $json .= json_encode($v["wbn_wip_testnum"], JSON_NUMERIC_CHECK) . ",";      // 仕掛品(検査数)
            $json .= json_encode($v["wip_numberofgoods"], JSON_NUMERIC_CHECK) . ",";    // 仕掛品(良品数)
            $json .= json_encode($v["wip_adversenumber"], JSON_NUMERIC_CHECK) . ",";    // 仕掛品(不良数)
            $json .= json_encode($v["vd_numberoftargets"], JSON_NUMERIC_CHECK) . ",";   // 蒸着品(対象数)
            $json .= json_encode($v["wbn_vd_testnum"], JSON_NUMERIC_CHECK) . ",";       // 蒸着品(検査数)
            $json .= json_encode($v["vd_numberofgoods"], JSON_NUMERIC_CHECK) . ",";     // 蒸着品(良品数)
            $json .= json_encode($v["vd_adversenumber"], JSON_NUMERIC_CHECK) . ",";     // 蒸着品(不良数)
            $json .= json_encode($v["wip_numberoftargets_P"], JSON_NUMERIC_CHECK) . ","; // 仕掛品(P)(対象数)
            $json .= json_encode($v["wbn_wip_testnum_P"], JSON_NUMERIC_CHECK) . ",";    // 仕掛品(P)(検査数)
            $json .= json_encode($v["wip_numberofgoods_P"], JSON_NUMERIC_CHECK) . ",";  // 仕掛品(P)(良品数)
            $json .= json_encode($v["wip_adversenumber_P"], JSON_NUMERIC_CHECK) . ",";  // 仕掛品(P)(不良数)
            $json .= json_encode($v["wbn_treatment_person"]) . ",";                     // 処置担当者
            $json .= json_encode($v["wbn_treatment_date"]) . ",";                       // 処置完了日時
            $json .= json_encode($v["wbn_quantity_person"]) . ",";                      // 数量管理者
            $json .= json_encode($v["wbn_quantity_date"]) . ",";                        // 数量管理日時
            $json .= json_encode($v["wbn_licensor_person"]) . ",";                      // 処置部門管理者
            $json .= json_encode($v["wbn_licensor_date"]) . ",";                        // 処置部門管日時
            $json .= json_encode($v["wbn_content_confirmation"]) . ",";                 // 現品の内容確認
            $json .= json_encode($v["wbn_rework_instructions"]) . ",";                  // 手直し（修理）の場合は処置内容を記入
            $json .= json_encode($v["wbn_inventory_processing"]) . ",";                 // 在庫処置のみ及び対象在庫確定の根拠
            $json .= json_encode($v["wbn_treatment_only"]) . ",";                       // 処置のみ
            $json .= json_encode(str_replace("入力必要", "", $v["wbn_cause"])) . ",";                                // 発生・原因
            $json .= json_encode(str_replace("入力必要", "", $v["wbn_countermeasures"])) . ",";                      // 発生・対策
            $json .= json_encode(str_replace("入力必要", "", $v["wbn_outflow_cause"])) . ",";                        // 流出・原因
            $json .= json_encode(str_replace("入力必要", "", $v["wbn_outflow_countermeasures"])) . ",";              // 流出・対策
            $json .= json_encode($v["wbn_corrective_input_person"]) . ",";              // 発生.流出入力者
            $json .= json_encode($v["wbn_inspection_total"]) . ",";                     // 検証結果の検査数
            $json .= json_encode($v["wbn_inspection_bad"]) . ",";                       // 検証結果の不良数
            if ($v["wbn_inspection_rate"] > 0) {
              $json .= json_encode($this->floattostr($v["wbn_inspection_rate"]) . "%") . ",";               // 検証結果の不良率
            } else {
              $json .= json_encode($v["wbn_inspection_rate"]) . ",";                    // 検証結果の不良率
            }
            $json .= json_encode($v["wbn_restoration"]) . ",";                          // 復旧確認で可
            $json .= json_encode($v["wbn_confirmation"]) . ",";                         // 長期確認が必要
            $json .= json_encode($v["wbn_effect"]) . ",";                               // 効果
            $json .= json_encode($v["wbn_effect_NG_msg"]) . ",";                        // 効果なしのコメント
            $json .= json_encode($v["wbn_reissue_id"]) . ",";                           // 効果なしの再発行・資料No
            $json .= json_encode($v["wbn_countermeasure_person"]) . ",";                // 対策部門者
            $json .= json_encode($v["wbn_countermeasure_date"]) . ",";                  // 対策部門確認日時
            $json .= json_encode($v["wbn_reation_person"]) . ",";                       // 作成部門者
            $json .= json_encode($v["wbn_reation_date"]) . ",";                         // 作成部門確認日時
            $json .= json_encode($v["wbn_quality_control_person"]) . ",";               // 品管部門者
            $json .= json_encode($v["wbn_quality_control_date"]) . ",";                 // 品管部門確認日時
            $json .= json_encode($v["wbn_quality_control_comment"]) . ",";              // 確認コメント
            $json .= json_encode($v["wbn_similar_products_nowant"]) . ",";              // 類似製品(不要)
            $json .= json_encode($v["wbn_similar_products_want"]) . ",";                // 類似製品(必要)
            $json .= json_encode($v["wbn_similar_process_nowant"]) . ",";               // 類似プロセス(不要)
            $json .= json_encode($v["wbn_similar_process_want"]) . ",";                 // 類似プロセス(必要)
            $json .= json_encode($v["wbn_manufacturing_person"]) . ",";                 // 製造課長
            $json .= json_encode($v["wbn_manufacturing_date"]) . ",";                   // 製造課長確認日時
            $json .= json_encode($v["wbn_rejection_reason"]) . ",";                           // メッセージ
            if (($sum_fh + $sum_wip + $sum_vd + $sum_wip_P) != 0 && $v["wbn_processing_position"] > 5) {
              $json .= json_encode("error"). ",";                                   // エラーがあれば
            } else {
              $json .= json_encode(""). ",";                                   // エラーが無い
            }
            $json .= json_encode($v["count_RFID"]) ;                           // メッセージ
            if ($key < count($d) - 1) {
              $json .= "],";
            }
          }
          $json .= "]]";
          header('Content-type: application/json; charset=UTF-8');
          echo ($json);
        }
        exit;
      } else if ($request->getParameter('ac') == "ロット番号取得") {
        $q = "SELECT DISTINCT ロット№  FROM vi_lot_info WHERE 品目コード = '" . $request->getParameter('itemcode') . "' ORDER BY WID DESC";
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        $sum_d = array();
        if (count($d) > 0) {
          foreach ($d as $key => $v) {
            $sum_d["ロット№"][] = $v['ロット№'];
          }
        }
        $q = "SELECT DISTINCT 型番 FROM vi_lot_info WHERE 品目コード = '" . $request->getParameter('itemcode') . "' ORDER BY WID DESC";
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        if (count($d) > 0) {
          foreach ($d as $key => $v) {
            $sum_d["型番"][] = $v['型番'];
          }
          $q = "SELECT DISTINCT cav_items FROM ms_molditem_info WHERE itemcord = '" . $request->getParameter('itemcode') . "' AND form_num = '" . $d[0]['型番'] . "' ORDER BY ms_molditem_info_id  DESC";
          $st = $con->prepare($q);
          $st->execute();
          $d_cav = $st->fetchall(PDO::FETCH_ASSOC);
          if (count($d_cav) > 0) {
            foreach ($d_cav as $key_cav => $v_cav) {
              $sum_d["キャビ番号"][] = $v_cav['cav_items'];
            }
          }
        }
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($sum_d);
        exit;
      } else if ($request->getParameter('ac') == "キャビ番号取得") {
        $q = "SELECT DISTINCT cav_items FROM ms_molditem_info
              WHERE itemcord = '" . $request->getParameter('itemcode') . "'
                AND form_num = '" . $request->getParameter('modelnumber') . "'
                ORDER BY ms_molditem_info_id  DESC";
        $st = $con->prepare($q);
        $st->execute();
        $d_cav = $st->fetch(PDO::FETCH_ASSOC);
        if (count($d_cav) > 0) {
          echo $d_cav['cav_items'];
        }
        exit;
      }
      //　2025年７月から、使用禁止になる為
      // //品目データ
      // $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
      // //$db["dsn"] = 'pgsql:dbname=adempiere host=modulesrv.nalux.local port=5432';
      // $db["user"] = 'adempiere';
      // $db["password"] = 'adempiere';
      // try {
      //   $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);
      //   $q = "SELECT m_locator_id,value,x,ad_org_id FROM m_locator WHERE ad_org_id = '" . $placeid . "' ORDER BY value ";
      //   $st = $dbh->query($q);
      //   $d = $st->fetchall(PDO::FETCH_ASSOC);
      //   $this->placelist = json_encode($d, JSON_NUMERIC_CHECK);
      // } catch (PDOException $e) {
      //   print('Error:' . $e->getMessage());
      //   die();
      // }
      $q = 'SELECT DISTINCT itemcord FROM work.xls_work_report  WHERE workitem = "成形" AND YEAR(date) = YEAR(NOW())';
      $q .= ' ORDER BY itemcord DESC';
      $list_itemcode = $con->prepare($q);
      $list_itemcode->execute();
      $list_itemcode = $list_itemcode->fetchall(PDO::FETCH_ASSOC);
      $this->list_itemcode = $list_itemcode;
      $q = 'SELECT DISTINCT YEAR(date) as "year" FROM work.xls_work_report  WHERE workitem = "成形"';
      $q .= ' ORDER BY YEAR(date) DESC LIMIT 6';
      $list_year = $con->prepare($q);
      $list_year->execute();
      $list_year = $list_year->fetchall(PDO::FETCH_ASSOC);
      $this->list_year = $list_year;
      $q = 'SELECT DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d") AS date FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $slist = $con->prepare($q);
      $slist->execute();
      $sdate = $slist->fetchall(PDO::FETCH_ASSOC);
      $this->sdate = $sdate;
      $q = 'SELECT DISTINCT wbn_id FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $idlist = $con->prepare($q);
      $idlist->execute();
      $idlist = $idlist->fetchall(PDO::FETCH_ASSOC);
      $this->idlist = $idlist;
      $q = "SELECT DISTINCT IF(searchtag <> '' , searchtag, itemname)  AS 品名
                          , itempprocord AS 品目コード
                          , adm_nal_bu AS BU
                          , mold_plant AS 工場
            FROM work.ms_molditem
            WHERE searchtag NOT LIKE '%試作%'
              AND searchtag NOT LIKE '%金型%'
              AND searchtag NOT LIKE '%禁止%'
              AND itemname NOT LIKE '%試作%'
              AND itemname NOT LIKE '%金型%'
              AND itemname NOT LIKE '%禁止%'
              AND itemname <> ''
              AND itempprocord <> ''
              AND ( mold_plant LIKE '" . mb_substr($placename, 0, 2) . "%' OR mold_plant IS NULL )
            ORDER BY mold_plant DESC, 品名 ASC";
      $item1 = $con->prepare($q);
      $item1->execute();
      $item1 = $item1->fetchall(PDO::FETCH_ASSOC);
      $q = "SELECT DISTINCT IF(searchtag <> '' , searchtag, itemname)  AS 品名
                          , itempprocord AS 品目コード
                          , adm_nal_bu AS BU
                          , mold_plant AS 工場
            FROM work.ms_molditem
            WHERE searchtag NOT LIKE '%試作%'
              AND searchtag NOT LIKE '%金型%'
              AND searchtag NOT LIKE '%禁止%'
              AND itemname NOT LIKE '%試作%'
              AND itemname NOT LIKE '%金型%'
              AND itemname NOT LIKE '%禁止%'
              AND itemname <> ''
              AND itempprocord <> ''
              AND mold_plant NOT LIKE '" . mb_substr($placename, 0, 2) . "%'
              AND mold_plant IS NOT NULL
            ORDER BY mold_plant ASC, 品名 ASC";
      $item2 = $con->prepare($q);
      $item2->execute();
      $item2 = $item2->fetchall(PDO::FETCH_ASSOC);
      $item_merge = array_merge($item1, $item2);
      $d_item = array();
      foreach ($item_merge as $key => $val) {
        if (mb_substr($val["品目コード"], -3, -2) == "-" && !is_numeric(substr($val["品目コード"], 0, 2))) {
          $d_item[] = $val;
        }
      }
      $this->d_item = $d_item;
      $q = "SELECT id AS num ,defectivename AS item, plant_id FROM ms_defectivesitem WHERE plant_id = '" . $placeid . "' order by id ";
      $dbuglist = $con->prepare($q);
      $dbuglist->execute();
      $dbuglist = $dbuglist->fetchall(PDO::FETCH_ASSOC);
      $this->dbuglist = json_encode($dbuglist, JSON_NUMERIC_CHECK);
      $q = 'SELECT DISTINCT wbn_product_name, wbn_item_code FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $namelist = $con->prepare($q);
      $namelist->execute();
      $namelist = $namelist->fetchall(PDO::FETCH_ASSOC);
      $this->namelist = $namelist;
    } else {
      $stitle = '不具合連絡 ｜ 登録 ｜ Nalux';
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function floattostr($val)
  {
    preg_match("#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($val), $o);
    return $o[1] . sprintf('%d', $o[2]) . ($o[3] != '.' ? $o[3] : '');
  }
  public function executeJudgement(sfWebRequest $request)
  {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
      header("WWW-Authenticate: Basic realm='ReportData-Management'");
      header('HTTP/1.0 401 Unauthorized');
      //キャンセル時の表示
      echo 'なんで？...';
      exit;
    } else {
      $id = $this->Auth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
      if ($id == false) {
        //認証エラーの処理
        //unset($_SERVER['PHP_AUTH_USER']);
        //unset($_SERVER['PHP_AUTH_PW']);
        //unset($_SERVER['PHP_AUTH_TYPE']);
        header("WWW-Authenticate: Basic realm='ReportData-Management'");
        header('HTTP/1.0 401 Unauthorized');
        //setcookie('co_username', 0, time() - 1800);
        //$this->redirect('/common/AuthenticationFailure');
        echo '__エラー...__ユーザー名かパスワードが間違っています。ブラウザを終了してやり直して下さい。';
        exit;
      }
    }
    // setcookie('co_username', $id);
    echo '<input type="hidden" id="username" value = "' . $id . '">';
    $this->setlayout("diseble");
    //return sfView::NONE;
  }
  public function executeViewdata(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $placeid = $request->getParameter('placeid');
    $documentid = $request->getParameter('documentid');
    if ($placeid != "") {
      $arrplace = [];
      $arrplace["1000073"] = "山崎工場";
      $arrplace["1000079"] = "野洲工場";
      $arrplace["1000085"] = "大阪工場";
      $arrplace["1000125"] = "NPG";
      $this->placeinfo = $arrplace;
      $stitle = '不具合連絡 ｜ 一覧 ｜ ' . $arrplace[$placeid];
      $this->placeid = $placeid;
      $this->documentid = $documentid;
      if ($request->getParameter('ac') == "GetJsonXWR") {
        $wbn_id = $request->getParameter('wbn_id');
        $q = '  SELECT  WBN.wbn_id
                      , WBN.wbn_cavino
                      , WBN.wbn_fh_testnum
                      , WBN.wbn_wip_testnum
                      , WBN.wbn_vd_testnum
                      , WBN.wbn_wip_testnum_P
                      , WBN.wbn_processing_position
                      , XLS.*
                  FROM work.work_bug_notification WBN
                  LEFT JOIN ( SELECT work_bug_notification_id AS remark, measure, moldplaceid
                                    , SUM(CASE WHEN state = "0=" THEN pending_num END)                  AS "fh_numberoftargets"
                                    , SUM(CASE WHEN state = "0-" THEN badnum END)                       AS "fh_adversenumber"
                                    , SUM(CASE WHEN state = "M=" THEN pending_num END)                  AS "wip_numberoftargets"
                                    , SUM(CASE WHEN state = "M-" THEN badnum END)                       AS "wip_adversenumber"
                                    , SUM(CASE WHEN state = "P=" THEN pending_num END)                  AS "wip_numberoftargets_P"
                                    , SUM(CASE WHEN state = "P-" THEN badnum END)                       AS "wip_adversenumber_P"
                                    , SUM(CASE WHEN state = "J=" THEN pending_num END)                  AS "vd_numberoftargets"
                                    , SUM(CASE WHEN state = "J-" THEN badnum END)                       AS "vd_adversenumber"
                                    , CASE WHEN state = "P=" AND pending_num > 0 THEN afterprocess END  AS "afterprocess"
                                  FROM work.xls_work_report
                                  INNER JOIN work_inventory_control_bug_notification
                                    ON id = xls_work_report_id
                                 WHERE moldplaceid ="' . $placeid . '"
                                   AND del_flg = 0
                                   AND wicbn_del_flg = 0
                                   AND hgpd_id = 0
                                   AND work_bug_notification_id = "' . $wbn_id . '"
                                GROUP BY work_bug_notification_id, measure                                             ) XLS
                         ON WBN.wbn_id       = XLS.remark
                        AND WBN.wbn_cavino   = XLS.measure
                        AND WBN.wbn_placeid  = XLS.moldplaceid
                WHERE WBN.wbn_placeid = "' . $placeid . '"
                  AND WBN.wbn_id = "' . $wbn_id . '"
                  AND WBN.wbn_defect_details <> "テスト用"
                ORDER BY WBN.wbn_cavino ASC ';
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        foreach ($d as $k => &$v) {
          foreach ($v as $kk => &$vv) {
            if (is_null($vv)) {
              $vv = '';
            }
          }
        }
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($d);
        exit;
      }
      //　2025年７月から、使用禁止になる為
      // //品目データ
      // $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
      // //$db["dsn"] = 'pgsql:dbname=adempiere host=modulesrv.nalux.local port=5432';
      // $db["user"] = 'adempiere';
      // $db["password"] = 'adempiere';
      // try {
      //   $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);
      //   $q = "SELECT m_locator_id,value,x,ad_org_id FROM m_locator WHERE ad_org_id = '" . $placeid . "' ORDER BY value ";
      //   $st = $dbh->query($q);
      //   $d = $st->fetchall(PDO::FETCH_ASSOC);
      //   $this->placelist = json_encode($d, JSON_NUMERIC_CHECK);
      // } catch (PDOException $e) {
      //   print('Error:' . $e->getMessage());
      //   die();
      // }
      $q = 'SELECT DISTINCT DATE_FORMAT(created_at, "%Y-%m-%d") AS date FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $slist = $con->prepare($q);
      $slist->execute();
      $sdate = $slist->fetchall(PDO::FETCH_ASSOC);
      $this->sdate = $sdate;
      $q = 'SELECT DISTINCT wbn_id FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $idlist = $con->prepare($q);
      $idlist->execute();
      $idlist = $idlist->fetchall(PDO::FETCH_ASSOC);
      $this->idlist = $idlist;
    } else {
      $stitle = '不具合連絡 ｜ 一覧 ｜ Nalux';
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function executeGraphic(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $placeid = $request->getParameter('placeid');
    if ($placeid != "") {
      $arrplace = [];
      $arrplace["1000073"] = "山崎工場";
      $arrplace["1000079"] = "野洲工場";
      $arrplace["1000085"] = "大阪工場";
      $arrplace["1000125"] = "NPG";
      $placename = $arrplace[$placeid];
      $this->placeid = $placeid;
      $this->placename = $placename;
      $stitle = '不具合連絡 ｜ グラフ ｜ ' . $placename;
      $arrdocument = [];
      $arrdocument["1000073"] = "山製-F";
      $arrdocument["1000079"] = "や品-A";
      $arrdocument["1000085"] = "大製-F";
      $arrdocument["1000125"] = "け製-F";
      $document = $arrdocument[$placeid];
      $placename_substr = mb_substr($placename, 0, 1);
      if ($request->getParameter('ac') == "グラフ表示") {
        $sum_d = array();
        $q = ' SELECT wbn_item_code, wbn_product_name, wbn_form_no , XLS.*, DATE_FORMAT(WBN.created_at, "%Y-%m") AS YM
                FROM work.work_bug_notification WBN
                LEFT JOIN
                    ( SELECT work_bug_notification_id AS remark, measure, moldplaceid ,
                            SUM(CASE WHEN state = "0=" THEN pending_num END) AS "01"
                          , SUM(CASE WHEN state = "0=" AND pending_num = 0 THEN goodnum END) AS "02"
                          , SUM(CASE WHEN state = "0-" THEN badnum END) AS "03"
                          , SUM(CASE WHEN state = "M=" THEN pending_num END) AS "M1"
                          , SUM(CASE WHEN state = "M=" AND pending_num = 0 THEN goodnum END) AS "M2"
                          , SUM(CASE WHEN state = "M-" THEN badnum END) AS "M3"
                          , SUM(CASE WHEN state = "J=" THEN pending_num END) AS "J1"
                          , SUM(CASE WHEN state = "J=" AND pending_num = 0 THEN goodnum END) AS "J2"
                          , SUM(CASE WHEN state = "J-" THEN badnum END) AS "J3"
                          , SUM(CASE WHEN state = "P=" THEN pending_num END) AS "P1"
                          , SUM(CASE WHEN state = "P=" AND pending_num = 0 THEN goodnum END) AS "P2"
                          , SUM(CASE WHEN state = "P-" THEN badnum END) AS "P3"
                          FROM work.xls_work_report
                          INNER JOIN work_inventory_control_bug_notification
                              ON id = xls_work_report_id
                              
                            WHERE moldplaceid ="' . $placeid . '"
                              AND del_flg = 0
                              AND wicbn_del_flg = 0
                              AND hgpd_id = 0
                          GROUP BY work_bug_notification_id, measure) XLS
                 ON WBN.wbn_id       = XLS.remark
                AND WBN.wbn_cavino   = XLS.measure
                AND WBN.wbn_placeid  = XLS.moldplaceid
                  WHERE WBN.wbn_placeid = "' . $placeid . '"
                  ORDER BY YM';
                  // echo $q;
                  // exit;
        $st = $con->prepare($q);
        $st->execute();
        $d_wbn = $st->fetchall(PDO::FETCH_ASSOC);
        $sum_d["wbn"] = $d_wbn;
        $q = "SELECT HR.hgpd_process
                    , HR.hgpd_qtycomplete
                    , HR.hgpd_difactive
                    , WICBN.wicbn_id
                    , WICBN.wic_rfid AS wicbn_rfid
                    , WICBN.xls_work_report_id AS wic_inspection
                    , (SELECT DATE_FORMAT(created_at, '%Y-%m') FROM work_bug_notification WHERE wbn_id = WICBN.work_bug_notification_id LIMIT 1) AS YM
                    , (SELECT wbn_product_name FROM work_bug_notification WHERE wbn_id = WICBN.work_bug_notification_id LIMIT 1) AS wbn_product_name
                    , (SELECT wbn_form_no FROM work_bug_notification WHERE wbn_id = WICBN.work_bug_notification_id LIMIT 1) AS wbn_form_no
                    , (SELECT wbn_item_code FROM work_bug_notification WHERE wbn_id = WICBN.work_bug_notification_id LIMIT 1) AS wbn_item_code
              FROM hgpd_report HR
              INNER JOIN (WITH RECURSIVE temp1(id, cid, bid, cflag) AS (SELECT * FROM hgpd_report_sub t1
                          WHERE t1.hgpd_before_id IN (SELECT hgpd_id FROM work_inventory_control_bug_notification WHERE hgpd_id <> 0 AND wicbn_del_flg = 0)
                          UNION ALL SELECT t2.* FROM hgpd_report_sub t2, temp1 WHERE t2.hgpd_before_id = temp1.cid)
                          SELECT DISTINCT(hgpd_id) FROM temp1 INNER JOIN hgpd_report ON hgpd_report.hgpd_id = temp1.cid OR hgpd_report.hgpd_id = temp1.bid) MM
                      ON HR.hgpd_id = MM.hgpd_id
              LEFT JOIN work_inventory_control_bug_notification WICBN
                      ON WICBN.hgpd_id = HR.hgpd_id
              WHERE HR.hgpd_del_flg = 0
                AND WICBN.wicbn_del_flg = 0
              ORDER BY YM";
        $st = $con->prepare($q);
        $st->execute();
        $d_hr = $st->fetchall(PDO::FETCH_ASSOC);
        $sum_d["hr"] = $d_hr;
        $q = 'SELECT 品目コード, 集計タイプ, 型番
                , CASE WHEN SUM(不良数) IS NULL THEN 0 ELSE SUM(不良数) END AS "不良数"
                , CASE WHEN SUM(後不数) IS NULL THEN 0 ELSE SUM(後不数) END AS "後不数"
                , CASE WHEN SUM(不良金額) IS NULL THEN 0 ELSE SUM(不良金額) END AS "不良金額"
                , CASE WHEN SUM(後不金額) IS NULL THEN 0 ELSE SUM(後不金額) END AS "後不金額"
                , CASE WHEN AVG(単価) IS NULL THEN 0 ELSE AVG(単価) END AS "単価"
                , COUNT(*)
              FROM vi_actual_sammary
              WHERE 管理工場 = "' . $request->getParameter('placename') . '"
              GROUP BY 品目コード, 集計タイプ, 型番';
        $st = $con->prepare($q);
        $st->execute();
        $d_vas = $st->fetchall(PDO::FETCH_ASSOC);
        $sum_d["vas"] = $d_vas;

        $q = " SELECT wbn_classification, wbn_project, wbn_defect_item, wbn_product_name, DATE_FORMAT(created_at, '%Y-%m') AS YM
                        FROM work.work_bug_notification
                       WHERE wbn_processing_position < 9
                         AND wbn_placeid = '" . $placeid . "'
                         GROUP BY wbn_id";
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        $sum_d["bug"] = $d;
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($sum_d);
        exit;
      }
      $q = 'SELECT DISTINCT DATE_FORMAT(created_at, "%Y-%m") AS date FROM work.work_bug_notification WHERE wbn_placeid = "' . $placeid . '" ORDER BY created_at DESC;';
      $d = $con->execute($q);
      $list_date = $d->fetchall(PDO::FETCH_ASSOC);
      $this->list_date = $list_date;
      //　2025年７月から、使用禁止になる為
      // //品目データ
      // $db["dsn"] = 'pgsql:dbname=adempiere3 host=153.120.12.126 port=5432';
      // //$db["dsn"] = 'pgsql:dbname=adempiere host=modulesrv.nalux.local port=5432';
      // $db["user"] = 'adempiere';
      // $db["password"] = 'adempiere';
      // try {
      //   $dbh = new PDO($db["dsn"], $db["user"], $db["password"]);
      //   $q = "SELECT m_locator_id,value,x,ad_org_id FROM m_locator WHERE ad_org_id = '" . $placeid . "' ORDER BY value ";
      //   $st = $dbh->query($q);
      //   $d = $st->fetchall(PDO::FETCH_ASSOC);
      //   $this->placelist = json_encode($d, JSON_NUMERIC_CHECK);
      // } catch (PDOException $e) {
      //   print('Error:' . $e->getMessage());
      //   die();
      // }
    } else {
      $stitle = '不具合連絡 ｜ グラフ ｜ Nalux';
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function Auth($ldaprdn, $ldappass)
  {
    if ($_COOKIE['co_username']) {
      return $_COOKIE['co_username'];
    }
    // ldap バインドを使用する
    // ldap サーバーに接続する
    $ldapconn = ldap_connect('ldap://192.168.9.14') or die('Could not connect to LDAP server.');
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
    if ($ldapconn) {
      $ldapbind = ldap_bind($ldapconn, $ldaprdn . '@nalux.local', $ldappass);
    }
    if ($ldapbind) {
      $dn = "ou='nalux_user',dc=nalux, dc=local";
      $filter = "(|(uid=$ldaprdn))";
      $justthese = array('displayName', 'sAMAccountName', 'group', 'cn', 'sn');
      $sr = ldap_search($ldapconn, 'dc=nalux, dc=local', "(sAMAccountName=$ldaprdn)", $justthese);
      $info = ldap_get_entries($ldapconn, $sr);
      $vinfo['displayname'] = $info[0]['displayname'][0];
      $vinfo['sn'] = $info[0]['sn'][0];
      //$vinfo['givenname'] = $info[0]['givenname'][0];
      return str_replace(array(' ', '　'), '', $vinfo['displayname']);
    } else {
      return false;
    }
    return sfView::NONE;
  }
  //作業者選択用画面パーツ
  public function executeAjaxDeptSelect(sfWebRequest $request)
  {

    /*データベース接続変更 */
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work;charset=utf8;',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    //ユーザー情報の取り出し単独用 GETデータ

    $file = Doctrine_Query::create()->from('msPerson p')->select('gp1,CAST(user AS CHAR) as sort')->Where('flg = ?', '0')->groupBy('gp1')->orderby('id');

    $gp1 = $file->fetchArray();
    $file = Doctrine_Query::create()->from('msPerson')->select('*,CAST(user AS CHAR) as sort')->Where('user != ?', '')->AndWhere('flg = ?', '0')->orderby('id');
    $userlist = $file->fetchArray();
    $file = Doctrine_Query::create()->from('msPerson p')->select('gp1,gp2,CAST(user AS CHAR) as sort')->Where('flg = ?', '0')->groupBy('gp1')->addgroupBy('gp2')->orderby('id');
    $gp2 = $file->fetchArray();
    $userarray = array();
    //print_r($gp1);
    foreach ($gp2 as $itemgp) {
      foreach ($userlist as $user) {
        if ($itemgp["gp2"] == $user["gp2"]) {
          if ($user["gp2"] == "") {
            if (!in_array($user["user"], $userarray[$user["gp1"]])) {
              $userarray[$user["gp1"]][] = $user["user"];
            }
          } else {
            //print array_search($user["user"],$userarray[$user["gp1"]][$user["gp2"]])."<br>\n";
            //if(!array_search($user["user"],$userarray[$user["gp1"]][$user["gp2"]])){
            if (!in_array($user["user"], $userarray[$user["gp1"]][$user["gp2"]])) {
              $userarray[$user["gp1"]][$user["gp2"]][] = $user["user"];
            }
          }
        }
      }
    }

    //print_r($userlist);
    //print_r(array_unique($userarray));
    //print_r($userarray);

    $print = '<style type="text/css">';


    if (filter_input(1, "mode") === "touch") {
      $print .= '#user-list button{
                    font-size: 120%;
                    width:100%;
                    margin: 4px 0 4px 0;
                }

                #user-list hr{
                   margin: 2px 0 2px 0;
                   color :#ECEADB;
                }

                #user-list button p{
                    display:none;
                }
              ';
    } else {
      $print .= '#user-list button{
                    font-size: 80%;
                    padding: 4px;
                    height: 2em;
                    margin: 2px 1px 2px 1px;
                    text-justify: inter-ideograph;
                }
                #user-list hr{
                   margin: 2px 0 2px 0;
                   color :#ECEADB;
                }

                #user-list button p{
                    margin-top:3px;
                    font-size: 80%;
                    vertical-align: middle;
                    text-decoration:underline;
                    color : #bbb;
                }';
    }
    $print .= "   #user-list button.selected{
                      color : orange;
                  }
                  #user-list #charge button:hover{
                      color : orange;
                  }
                </style>";
    //$print = '<script type="text/javascript" src="/js/jquery/jquery-1.8.3.js"></script>'."\n";

    $print .= '<div id="user-list"><p>所属</p>' . "\n";

    $i = 0;

    foreach ($gp1 as $valgp1) {
      $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only gp1"  onclick="viewUserlist(\'' . $valgp1["gp1"] . '\');">' . $valgp1["gp1"] . '</button>' . "\n";

      $i++;
    }

    $print .= '<hr/>';
    $print .= '<p>係</p>';
    $print .= '<p id="charge">';


    foreach ($gp2 as $valgp2) {

      $print .= '<span id="sp_' . $valgp2["gp1"] . '" style="display:none;">';

      if ($valgp2["gp2"]) {

        $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"  onclick="fb_set_dept(\'' . $valgp2["gp1"] . '\',\'' . $valgp2["gp2"] . '\');">' . $valgp2["gp2"] . '</button>' . "\n";

        $i++;
      }

      $print .= "</span>";
    }

    $print .= "<hr />";

    $print .= "\n" . "</p>" . "\n";
    //$print .='<p id="namelist">';

    foreach ($userarray as $key => $user) {

      $print .= '<div id="' . $key . '">';

      $print .= "</div>";
    }

    //$print .="</p>";

    $print .= "</div>";


    $print .= "\n" . '<script type="text/javascript">' . "\n";

    $print .= 'function viewUserlist(gp1,gp2){
              $("button").removeClass("selected");
              var hid = $("#user-list .gp1");
                for (i = 0; i < hid.length; i++) {
                  if(hid[i].innerText==gp1){
                    $(hid[i]).addClass("selected")
                  }
                }
    		      var hid = $("#user-list div");
    		      var id2 =gp1+gp2;
                for (i = 0; i < hid.length; i++) {
                  if(hid[i].id==gp1 || hid[i].id==id2){
                    $("#"+hid[i].id).css({"display":""});
                    $("#sp_"+hid[i].id).css({"display":""});
                  }else{
                    $("#"+hid[i].id).css({"display":"none"});
                    $("#sp_"+hid[i].id).css({"display":"none"});
                    //$("#"+hid[i].id).hide();
                  }
                }
              }
              ';
    if (filter_input(1, "mode") === "touch") {
      $print .= 'function scroll(gp){
                  var speed = 200;
                  var href= $(this).attr(gp);
                  var target = $(href == "#" || href == "" ? "html" : href);
                  var position = target.offset().top;
                  $("html, body").animate({scrollTop:position}, speed, "swing");
                  return false;
              }' . "\n";
    }

    $print .= "viewUserlist('" . rawurldecode($_GET["gp1"]) . "','" . rawurldecode($_GET["gp2"]) . "');" . "\n";
    // $print .= "viewUserlist('".rawurldecode($_GET["gp1"])."');"."\n";

    $print .= "</script>\n";


    $allowed_origin = 'http://log.yasu.nalux.local';
    if (isset($_SERVER['HTTP_ORIGIN']) === True && $_SERVER['HTTP_ORIGIN'] == $allowed_origin) {
      session_start();
      header("Access-Control-Allow-Origin: $allowed_origin");
      header("Access-Control-Allow-Credentials: true");
      $response = array('is_login' => isset($_SESSION['user_id']));
    } else {
      $response = array('error' => '未対応のサービスです');
    }

    header("Content-Type: text/javascript; charset=utf-8");
    $print =  mb_convert_encoding($print, 'UTF-8', 'UTF-8');
    //print mb_detect_encoding($print);
    $print = json_encode($print, JSON_UNESCAPED_UNICODE);
    //echo json_last_error_msg();
    echo $print;

    return sfView::NONE;
  }
  //作業者選択用画面パーツ
  public function executeAjaxBugSelect(sfWebRequest $request)
  {
    /*データベース接続変更 */
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work;charset=utf8;',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    //ユーザー情報の取り出し単独用 GETデータ
    $q = "  SELECT D.id, D.defectivename, B.mbc_subcategory, B.mbc_classification
            FROM work.ms_defectivesitem D
            INNER JOIN work.ms_bug_class B
            ON D.id = B.mbc_id
            WHERE D.plant_id = '" . $_GET["placeid"] . "'";
    $allbuglist= $con->execute($q);
    $allbuglist = $allbuglist->fetchall(PDO::FETCH_ASSOC);
    $bugarray = array();
    foreach ($allbuglist as $value) {
        $bugarray[$value["mbc_classification"]][$value["mbc_subcategory"]][] = $value["defectivename"];
    }
    $print = '<style type="text/css">';
    if (filter_input(1, "mode") === "touch") {
      $print .= '#user-list button{
                    font-size: 120%;
                    width:100%;
                    margin: 4px 0 4px 0;
                }

                #user-list hr{
                   margin: 2px 0 2px 0;
                   color :#ECEADB;
                }
                #user-list button p{
                    display:none;
                }
              ';
    } else {
      $print .= '#user-list button{
                    font-size: 80%;
                    padding: 4px;
                    height: 2em;
                    margin: 2px 1px 2px 1px;
                    text-justify: inter-ideograph;
                }

                #user-list hr{
                   margin: 2px 0 2px 0;
                   color :#ECEADB;
                }

                #user-list button p{
                    margin-top:3px;
                    font-size: 80%;
                    vertical-align: middle;
                    text-decoration:underline;
                    color : #bbb;
                }';
    }
    $print .= "   #user-list button.selected{
                      color : orange;
                  }
                  #user-list button.gp3:hover{
                      color : orange;
                  }
                  </style>";
    //$print = '<script type="text/javascript" src="/js/jquery/jquery-1.8.3.js"></script>'."\n";

    $print .= '<div id="user-list"><p>分類</p>' . "\n";
    $i = 0;
    $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only btn_all_bug" accesskey="' . chr(97 + $i) . '" onclick="viewUserlist(\'all_bug\');">全部<p style="float: right;">(' . chr(97 + $i) . ')</p></button>' . "\n";
    $i++;
    foreach (array_keys($bugarray) as $keygp1) {
      $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only btn_'. $keygp1 . '" accesskey="' . chr(97 + $i) . '" onclick="viewUserlist(\'' . $keygp1 . '\');">' . $keygp1. '<p style="float: right;">(' . chr(97 + $i) . ')</p></button>' . "\n";
      $i++;
    }
    $print .= "\n" . "</p>" . "\n";
    $print .= '<hr/>';
    $print .= '<div class="list_all_bug">';
    foreach ($bugarray as $keygp1 => $arygp1) {
      $print .= '<div style="float: left; text-align: left; width:10%; padding: 5px 0 5px 0;">'.$keygp1.'</div>';
      $print .= '<div style="float: left; text-align: left; width:90%; padding: 5px 0 5px 0;">';
      foreach ($arygp1 as $keygp2 => $arygp2) {
        foreach ($arygp2 as $val) {
          $print .= '<button type="button" class="name button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="setBug(\'' . $keygp1 . '\',\'' . $keygp2 . '\',\'' . $val . '\');">' . $val . '</button>' . "\n";
          $i++;
        }
      }
      $print .= "</div>";
    }
    $print .= "</div>";
    $print .= '<div class="list_division_bug"><p>小分類</p>';
    foreach ($bugarray as $keygp1 => $arygp1) {
      $print .= '<p class="gp2_' . $keygp1 . ' gp2">';
      foreach (array_keys($arygp1) as $keygp2) {
        $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only btn_'. $keygp1. $keygp2 . '" accesskey="' . chr(97 + $i) . '" onclick="viewUserlist(\'' . $keygp1 . '\',\'' . $keygp2 . '\');">' . $keygp2 . '<p style="float: right;">(' . chr(97 + $i) . ')</p></button>' . "\n";
        $i++;
      }
      $print .= '</p>';
    }
    $print .= "<hr />";
    //$print .='<p id="namelist">';
    $print .= "<p>項目</p>" . "\n";
    foreach ($bugarray as $keygp1 => $arygp1) {
      foreach ($arygp1 as $keygp2 => $arygp2) {
        $print .= '<p class="gp3_' . $keygp1 .$keygp2. ' gp3">';
        foreach ($arygp2 as $val) {
          $print .= '<button type="button" class="name button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="setBug(\'' . $keygp1 . '\',\'' . $keygp2 . '\',\'' . $val . '\');">' . $val . '</button>' . "\n";
          $i++;
        }
        $print .= '</p>';
      }
    }
    $print .= "</div></div>";
    $print .= "\n" . '<script type="text/javascript">' . "\n";
              $print .= 'function viewUserlist(gp1, gp2){
                  $("button").removeClass("selected");
                  $(".btn_" + gp1).addClass("selected");
                  if(gp1 == "all_bug"){
                    $(".list_all_bug").show();
                    $(".list_division_bug").hide();
                  }else{
                    $(".list_all_bug").hide();
                    $(".list_division_bug").show();
                    $(".gp2").hide();
                    $(".gp3").hide();
                    $(".btn_" + gp1 + gp2).addClass("selected");
                    $(".gp2_" + gp1).show();
                    $(".gp3_" + gp1 + gp2).show();
                    }
                  }
                  '. "\n";
    if (filter_input(1, "mode") === "touch") {
      $print .= 'function scroll(gp){
                  var speed = 200;
                  var href= $(this).attr(gp);
                  var target = $(href == "#" || href == "" ? "html" : href);
                  var position = target.offset().top;
                  $("html, body").animate({scrollTop:position}, speed, "swing");
                  return false;
              }' . "\n";
    }

    $print .= "viewUserlist('" . rawurldecode($_GET["gp1"]) . "','" . rawurldecode($_GET["gp2"]) . "');" . "\n";
    // $print .= "viewUserlist('".rawurldecode($_GET["gp1"])."');"."\n";

    $print .= "</script>\n";


    $allowed_origin = 'http://log.yasu.nalux.local';
    if (isset($_SERVER['HTTP_ORIGIN']) === True && $_SERVER['HTTP_ORIGIN'] == $allowed_origin) {
      session_start();
      header("Access-Control-Allow-Origin: $allowed_origin");
      header("Access-Control-Allow-Credentials: true");
      $response = array('is_login' => isset($_SESSION['user_id']));
    } else {
      $response = array('error' => '未対応のサービスです');
    }

    header("Content-Type: text/javascript; charset=utf-8");
    $print =  mb_convert_encoding($print, 'UTF-8', 'UTF-8');
    //print mb_detect_encoding($print);
    $print = json_encode($print, JSON_UNESCAPED_UNICODE);
    //echo json_last_error_msg();
    echo $print;

    return sfView::NONE;
  }
  //作業者選択用画面パーツ
  public function executeAjaxUserSelect(sfWebRequest $request)
  {

    /*データベース接続変更 */
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work;charset=utf8;',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    //ユーザー情報の取り出し単独用 GETデータ

    $file = Doctrine_Query::create()->from('msPerson p')->select('gp1')->Where('flg = ?', '0')->groupBy('gp1')->orderby('gp1');
    $gp1 = $file->fetchArray();
    $file = Doctrine_Query::create()->from('msPerson')->select('*,CAST(user AS CHAR) as sort')->Where('user != ?', '')->AndWhere('flg = ?', '0')->orderby('gp1,gp2,id');
    $userlist = $file->fetchArray();
    $file = Doctrine_Query::create()->from('msPerson p')->select('gp1,gp2')->Where('flg = ?', '0')->groupBy('gp1')->addgroupBy('gp2')->orderby('gp1');
    $gp2 = $file->fetchArray();
    $print = '<style type="text/css">';
    if (filter_input(1, "mode") === "touch") {
      $print .= '#user-list button{
                    font-size: 120%;
                    width:100%;
                    margin: 4px 0 4px 0;
                }
                #user-list hr{
                   margin: 2px 0 2px 0;
                   color :#ECEADB;
                }
                #user-list button p{
                    display:none;
                }
              ';
    } else {
      $print .= '#user-list button{
                    font-size: 80%;
                    padding: 4px;
                    height: 2em;
                    margin: 2px 1px 2px 1px;
                    text-justify: inter-ideograph;
                }
                #user-list hr{
                   margin: 2px 0 2px 0;
                   color :#ECEADB;
                }
                #user-list button p{
                    margin-top:3px;
                    font-size: 80%;
                    vertical-align: middle;
                    text-decoration:underline;
                    color : #bbb;
                }';
    }
    $print .= "   #user-list button.selected{
                      color : orange;
                  }
                  #user-list button.gp3:hover{
                      color : orange;
                  }
                  </style>";
    //$print = '<script type="text/javascript" src="/js/jquery/jquery-1.8.3.js"></script>'."\n";

    $print .= '<div id="user-list"><p>所属</p>' . "\n";

    $i = 0;


    foreach ($gp1 as $valgp1) {

      $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only btn_'. $valgp1["gp1"] . '" accesskey="' . chr(97 + $i) . '" onclick="viewUserlist(\'' . $valgp1["gp1"] . '\');">' . $valgp1["gp1"] . '<p style="float: right;">(' . chr(97 + $i) . ')</p></button>' . "\n";
      $i++;
    }
    $print .= "\n" . "</p>" . "\n";
    $print .= '<hr/>';
    $print .= '<p>係</p>';
    $print .= '<p>';

    $last_gp1 = "";
    foreach ($gp2 as $key => $valgp2) {
      if ($valgp2["gp2"]) {
        if($key == 0){
          $print .= '<p class="gp2_' . $valgp2["gp1"] . ' gp2">';
        }else if($last_gp1!= $valgp2["gp1"]){
          $print .= '</p><p class="gp2_' . $valgp2["gp1"] . ' gp2">';
        }
        $print .= '<button type="button" class="button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only btn_'. $valgp2["gp1"]. $valgp2["gp2"] . '" accesskey="' . chr(97 + $i) . '" onclick="viewUserlist(\'' . $valgp2["gp1"] . '\',\'' . $valgp2["gp2"] . '\');">' . $valgp2["gp2"] . '<p style="float: right;">(' . chr(97 + $i) . ')</p></button>' . "\n";
        $i++;

        $last_gp1= $valgp2["gp1"];
      }
      }
      $print .= '</p>';
    $print .= "<hr />";
    //$print .='<p id="namelist">';
    $print .= "<p>氏名</p>" . "\n";
    $last_gp1_gp2 = "";
    foreach ($userlist as $key => $value) {
      if($value["gp2"]){
        if($key == 0){
          $print .= '<div class="gp3_' . $value["gp1"] . $value["gp2"] . ' gp3">';
        }else if($last_gp1_gp2 != $value["gp1"] . $value["gp2"]){
          $print .= '</div><div class="gp3_' . $value["gp1"] . $value["gp2"] . ' gp3">';
        }
        $print .= '<button type="button" class="name button ma5 ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="setUser(\'' . $value["user"] . '\',\'' . $value["ppro"]  . '\',\'' . $value["gp1"] . '\',\'' . $value["gp2"] . '\');">' . $value["user"] . '</button>' . "\n";
        $last_gp1_gp2 = $value["gp1"] . $value["gp2"];
      }
    }
    $print .= "</div>";
    $print .= "</div>";
    $print .= "\n" . '<script type="text/javascript">' . "\n";
              $print .= 'function viewUserlist(gp1, gp2){
                  $(".gp2").hide();
                  $(".gp3").hide();
                  $("button").removeClass("selected");
                  $(".btn_" + gp1).addClass("selected");
                  $(".btn_" + gp1 + gp2).addClass("selected");
                  $(".gp2_" + gp1).show();
                  $(".gp3_" + gp1 + gp2).show();
                  }
                  '. "\n";
    if (filter_input(1, "mode") === "touch") {
      $print .= 'function scroll(gp){
                  var speed = 200;
                  var href= $(this).attr(gp);
                  var target = $(href == "#" || href == "" ? "html" : href);
                  var position = target.offset().top;
                  $("html, body").animate({scrollTop:position}, speed, "swing");
                  return false;
              }' . "\n";
    }

    $print .= "viewUserlist('" . rawurldecode($_GET["gp1"]) . "','" . rawurldecode($_GET["gp2"]) . "');" . "\n";
    $print .= "</script>\n";


    $allowed_origin = 'http://log.yasu.nalux.local';
    if (isset($_SERVER['HTTP_ORIGIN']) === True && $_SERVER['HTTP_ORIGIN'] == $allowed_origin) {
      session_start();
      header("Access-Control-Allow-Origin: $allowed_origin");
      header("Access-Control-Allow-Credentials: true");
      $response = array('is_login' => isset($_SESSION['user_id']));
    } else {
      $response = array('error' => '未対応のサービスです');
    }

    header("Content-Type: text/javascript; charset=utf-8");
    $print =  mb_convert_encoding($print, 'UTF-8', 'UTF-8');
    //print mb_detect_encoding($print);
    $print = json_encode($print, JSON_UNESCAPED_UNICODE);
    //echo json_last_error_msg();
    echo $print;

    return sfView::NONE;
  }

  public function DitemGroup($ditem, $mode)
  {
    $item = array();
    $total = 0;
    $ex = explode(",", $ditem);
    foreach ($ex as $val) {
      $exd = explode("=>", $val);
      if ($exd[0] && $exd[1] > 0) {
        $ditemname = mb_convert_kana($exd[0], "ak");
        $item[$ditemname] += $exd[1];
        $total = $total + $exd[1];
      }
    }
    arsort($item);
    if ($mode == "array") {
      return $item;
    } elseif ($mode == "t_num") {
      return $total;
    } else {
      $d_items = "";
      foreach ($item as $key => $num) {
        $d_items .= $key . "=>" . $num . ",";
      }
      $d_items = substr($d_items, "0", "-1");
      return $d_items;
    }
  }
  public function executeUpload_OLD(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $placeid = $this->getRequestParameter('placeid');
    $wbn_id = $this->getRequestParameter('wbn_id');
    $fpath = "files/bug_notification/img/";
    if ($this->getRequestParameter('menu') == 'add') {
      /* Check file */
      if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
        $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $f_name = 'img_bug_' . $wbn_id;
        $file_tmp  = $_FILES["file"]["tmp_name"];
        $size = $_FILES["file"]["size"];
        $fullpath = $fpath . $f_name . "." . $ext;
        /* Upload file */
        if (move_uploaded_file($file_tmp, $fullpath)) {
          $q = 'UPDATE work.work_bug_notification SET wbn_bug_img_name = ?, wbn_bug_img_ext = ?, wbn_bug_img_size = ? WHERE wbn_id = ? AND wbn_placeid = ?';
          $st = $con->prepare($q);
          $st->execute(array($f_name, $ext, $size, $wbn_id, $placeid));
          echo 'OK';
        }
      }
    }
    return sfView::NONE;
  }
  public function executeUpload(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $wbn_id = $this->getRequestParameter('wbn_id');
    $placeid = $this->getRequestParameter('placeid');
    $module = $this->getRequestParameter('module');
    $count = $this->getRequestParameter('count');
    $extract = $placeid . mb_substr($wbn_id, mb_strlen($wbn_id) - 6, 6);
    if ($this->getRequestParameter('menu') == 'add') {
      /* Check file */
      for ($i = 0; $i < $count; $i++) {
        if (!empty($_FILES['file' . $i]['name'])) {
          $ext = pathinfo($_FILES["file" . $i]["name"], PATHINFO_EXTENSION);
          $name = $_FILES['file' . $i]['name'];
          $type = $_FILES['file' . $i]['type'];
          $content = file_get_contents($_FILES['file' . $i]['tmp_name']);
          $sql = 'INSERT INTO manage_file_content(mfc_name, mfc_ext, mfc_contents, mfc_type, mfc_module, mfc_extract, created_at)
                  VALUES (:mfc_name, :mfc_ext, :mfc_contents, :mfc_type, :mfc_module, :mfc_extract, now())';
          $stmt = $con->prepare($sql);
          $stmt->bindValue(':mfc_name', $name, PDO::PARAM_STR);
          $stmt->bindValue(':mfc_ext', $ext, PDO::PARAM_STR);
          $stmt->bindValue(':mfc_contents', $content, PDO::PARAM_STR);
          $stmt->bindValue(':mfc_type', $type, PDO::PARAM_STR);
          $stmt->bindValue(':mfc_module', $module, PDO::PARAM_STR);
          $stmt->bindValue(':mfc_extract', $extract, PDO::PARAM_STR);
          $stmt->execute();
        }
      }
      echo 'OK';
    }
    return sfView::NONE;
  }
  public function executeGetFile(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $mfc_id = $this->getRequestParameter('mfc_id');
    $mfc_extract = $this->getRequestParameter('mfc_extract');
    if ($this->getRequestParameter('menu') == 'count') {
      $sql = 'SELECT mfc_id FROM manage_file_content WHERE mfc_extract = :mfc_extract';
      $stmt = $con->prepare($sql);
      $stmt->bindValue(':mfc_extract', $mfc_extract, PDO::PARAM_INT);
      $stmt->execute();
      $d = $stmt->fetchALL();
      $json = "[";
      foreach ($d as $key => $v) {
        $json .= "[";
        $json .= json_encode($v["mfc_id"]);
        if ($key < count($d) - 1) {
          $json .= "],";
        }
      }
      $json .= "]]";
      header('Content-type: application/json; charset=UTF-8');
      echo ($json);
      exit;
    } else if ($this->getRequestParameter('menu') == 'img') {
      /* Check file */
      $sql = 'SELECT * FROM manage_file_content WHERE mfc_id = :mfc_id';
      $stmt = $con->prepare($sql);
      $stmt->bindValue(':mfc_id', $mfc_id, PDO::PARAM_INT);
      $stmt->execute();
      $image = $stmt->fetch();
      header('Content-type: ' . $image['mfc_type']);
      echo $image['mfc_contents'];
      exit;
    } else {
      /* Check file */
      $sql = 'SELECT * FROM manage_file_content WHERE mfc_id = :mfc_id';
      $stmt = $con->prepare($sql);
      $stmt->bindValue(':mfc_id', $mfc_id, PDO::PARAM_INT);
      $stmt->execute();
      $image = $stmt->fetch();
      header('Content-type: ' . $image['mfc_type']);
      header('Content-Disposition: attachment; filename=' . $image['mfc_name']);
      echo $image['mfc_contents'];
      exit;
    }
    return sfView::NONE;
  }
  public function fb_php_slack_send($send_wbn_id = null)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    if ($send_wbn_id) {
      $q = 'SELECT * FROM work_bug_notification WHERE wbn_id = "' . $send_wbn_id . '" LIMIT 1';
    } else {
      $q = 'SELECT * FROM work_bug_notification ORDER BY created_at DESC LIMIT 1';
    }
    $d = $con->prepare($q);
    $d->execute();
    $d = $d->fetch(PDO::FETCH_ASSOC);
    $message = [
      'channel' => '#チャンネル名',
      'username' => 'System',
      'text' => ' <div style="width: 50%; float:left; font-size:24px">' . $d['wbn_id'] . '</div>
                      <div style="width: 50%; float:left;"><center><a href="http://track.yasu.nalux.local/MissingDefect?placeid=' . $d['wbn_placeid'] . '&documentid=' . urlencode($d['wbn_id']) . '">不具合連絡書詳細</a></center></div><br>
                      <hr  width="30%" align="left" />
                            発見部署:　' . $d['wbn_usergp2'] . '<br>
                            <div style="width: 100px; float:left; margin-top: 10px">
                            <ul>
                              <li>品名</li>
                              <li>型番</li>
                              <li>ロットNo.</li>
                              <li>成形日</li>
                              <li>不良内容</li>
                            </ul></div>
                            <div style="float:left; margin-top: 10px">
                            <ul>
                              ' . $d['wbn_product_name'] . '<br>
                              ' . $d['wbn_form_no'] . '<br>
                              ' . $d['wbn_lot_no'] . '<br>
                              ' . $d['wbn_mold_dt'] . '<br>
                              ' . $d['wbn_defect_item'] . '<br>
                            </ul></div>',
    ];
    if ($d['wbn_placeid'] == 1000073) {
      $webhook_url = 'https://nalux.webhook.office.com/webhookb2/cd10d6b6-8d15-42f8-b94d-adac659a74c2@538a184f-22cd-4faf-96ef-b863be69591e/IncomingWebhook/20b1992957254eedb3ca0d342078bd24/e6c52d11-ccb8-49bc-81be-4b581bfb5881';
    } else if ($d['wbn_placeid'] == 1000079) {
      $webhook_url = 'https://nalux.webhook.office.com/webhookb2/cd10d6b6-8d15-42f8-b94d-adac659a74c2@538a184f-22cd-4faf-96ef-b863be69591e/IncomingWebhook/976487f6cd984ae389da1d6aa070ee59/e6c52d11-ccb8-49bc-81be-4b581bfb5881';
    } else {
      return;
    }
    $options = array(
      'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($message),
      )
    );
    $response = file_get_contents($webhook_url, false, stream_context_create($options));
  }

  // 24.07.23 Teamsのコネクタ廃止による処置によりロジック追加
  // public function Powerautomate2Teams($send_wbn_id = null)
  public function executePowerautomate2Teams(sfWebRequest $request)
  {
    // Teams各種ID設定 チーム名:DAS, チャネル: 不具合連絡-山崎、-野洲 -NPG 25.01.08 ADD Arima
    $pa_id['groupId']="cd10d6b6-8d15-42f8-b94d-adac659a74c2";
    $pa_id['channelId']['1000073']="19:ed9b879cc3ff4b468a9594399935ab31@thread.tacv2";
    $pa_id['channelId']['1000079']="19:8b77ba18c3614b45bc44903f6c5850c8@thread.tacv2";
    $pa_id['channelId']['1000125']="19:fbf185c0916a4a63b4a25161e1c9418f@thread.tacv2";

    // DAS通知テストチーム設定
    // $pa_id['groupId']="c6ed2fc2-e98c-4466-9797-b60fb35a000d";
    // $pa_id['channelId']['1000073']="19:63cdf1948ba4441e970fdd5f53d0e96c@thread.tacv2";
    // $pa_id['channelId']['1000079']="19:3dec1b16576f4c9a861739ce0ed4a0e6@thread.tacv2";

    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    $send_wbn_id = $request->getParameter("send_wbn_id");
    if ($send_wbn_id) {
      $q = 'SELECT * FROM work_bug_notification WHERE wbn_id = "' . $send_wbn_id . '" LIMIT 1';
    } else {
      $q = 'SELECT * FROM work_bug_notification ORDER BY created_at DESC LIMIT 1';
    }
    $d = $con->prepare($q);
    $d->execute();
    $d = $d->fetch(PDO::FETCH_ASSOC);
    if ($d['wbn_placeid'] == "") {
      return;
    }
    // 24.11.08 デザインが崩れた為テーブルデザインに変更
    $html="<div style='width:100%;'><p style='font-size:120%;font-weight:bold;margin-bottom:3px;'><a target='_blank' href='http://track.yasu.nalux.local/MissingDefect?placeid=".$d['wbn_placeid']."&documentid=".urlencode($d['wbn_id'])."'><u>".$d['wbn_id']."</u></a></p>";
    $html.="<table border='1'>";
    $html.="<tr><th>発見部署</th><td>".$d['wbn_usergp2']."</td></tr>";
    $html.="<tr><th>品名</th><td>".$d['wbn_product_name']."</td></tr>";
    $html.="<tr><th>型番</th><td>".$d['wbn_form_no']."</td></tr>";
    $html.="<tr><th>ロットNo.</th><td>".$d['wbn_lot_no']."</td></tr>";
    $html.="<tr><th>成形日</th><td>".$d['wbn_mold_dt']."</td></tr>";
    $html.="<tr><th>不良内容</th><td>".nl2br($d['wbn_defect_item'])."</td></tr>";
    $html.="</table></div>";
    $html = str_replace(array("\r\n", "\r", "\n", "\t"), '', $html);
    $message = [
      'groupId' => $pa_id['groupId'],
      'channelId' => $pa_id['channelId'][$d['wbn_placeid']],
      'poster'=>'Flowbot',
      'style'=>'message',
      'attachments' => [
        [
          'contentType'=> 'application/vnd.microsoft.card.adaptive',
          'content'=>$html
        ]
      ]
    ];
    // header('Content-Type: application/json; charset=utf-8');
    // print_r(json_encode($message));
    // print_r($message["attachments"][0]["content"]);
    // exit;

    // Teams投稿 PowerAutomate Workflow経由
    $url='https://prod-05.japaneast.logic.azure.com:443/workflows/57e1181fa9c94495baa9e0860c95d7ca/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=FfEvNBATDEvl2f1v-FkrJcctum7MkS5zjUe2wSNSLxw';

    $options = array(
      'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($message),
      )
    );
    $response = file_get_contents($url, false, stream_context_create($options));


    return sfView::NONE;
  }
  public function fb_insert_rfid($cnn, $rifd_value )
  {
    $created_at = date("Y-m-d H:i:s");
    $good_num = $rifd_value["hgpd_qtycomplete"];
    if($rifd_value["key"] == "保留登録"){
      $workReport = new xlsWorkReport();
      foreach ($rifd_value["xls_data"] as $key => $val) {
        $workReport->set($key, $val);
      }
      $good_num = $rifd_value["hgpd_qtycomplete"] + $rifd_value["hgpd_remaining"];
      $workReport->set("totalnum", $good_num);
      $workReport->set("goodnum", $good_num);
      $workReport->set("badnum", $rifd_value["hgpd_difactive"]);
      $workReport->set("pending_num", $good_num);
      $workReport->set("afterprocess", $rifd_value["wic_wherhose"]);
      $workReport->set("place", $rifd_value["oh_palce"]);
      $workReport->set("remark", $rifd_value["wbn_id"]);
      $workReport->set("measure", $rifd_value["hgpd_cav"]);
      $workReport->set("state", "-".$rifd_value["wic_process_key"]);
      $workReport->set("created_at", $created_at );
      $workReport->save();
      $get_id = $workReport->getId();
    }else{
      if($rifd_value["hgpd_qtycomplete"] > 0){
        $workReport = new xlsWorkReport();
        foreach ($rifd_value["xls_data"] as $key => $val) {
          $workReport->set($key, $val);
        }
        $workReport->set("totalnum", $rifd_value["hgpd_qtycomplete"]);
        $workReport->set("goodnum", $rifd_value["hgpd_qtycomplete"]);
        $workReport->set("badnum", 0);
        $workReport->set("pending_num", 0);
        $workReport->set("afterprocess", $rifd_value["oh_palce"]);
        $workReport->set("place", $rifd_value["old_wic_wherhose"]);
        $workReport->set("remark", $rifd_value["wbn_id"]);
        $workReport->set("measure", $rifd_value["hgpd_cav"]);
        $workReport->set("state", $rifd_value["wic_process_key"]);
        $workReport->set("created_at", $created_at );
        $workReport->save();
        $get_id = $workReport->getId();
      }
      if($rifd_value["hgpd_difactive"] > 0){
        $workReport = new xlsWorkReport();
        foreach ($rifd_value["xls_data"] as $key => $val) {
          $workReport->set($key, $val);
        }
        $workReport->set("totalnum", $rifd_value["hgpd_difactive"]);
        $workReport->set("goodnum", 0);
        $workReport->set("badnum", $rifd_value["hgpd_difactive"]);
        $workReport->set("defectivesitem", $rifd_value["defect_item"]. "=>" . $rifd_value["hgpd_difactive"]);
        $workReport->set("pending_num", 0);
        $workReport->set("afterprocess", $rifd_value["oh_palce"]);
        $workReport->set("place", $rifd_value["oh_palce"]);
        $workReport->set("remark", $rifd_value["wbn_id"]);
        $workReport->set("measure", $rifd_value["hgpd_cav"]);
        $workReport->set("state", "-".$rifd_value["wic_process_key"]);
        $workReport->set("created_at", $created_at );
        $workReport->save();
        $get_id = $workReport->getId();
        $q = "INSERT INTO work.xls_work_report_defactiv (xwr_id,xwrd_ditem,xwrd_number) VALUES (".$get_id.",'".$rifd_value["defect_item"]."',".$rifd_value["hgpd_difactive"].")";
        $cnn->execute($q);
      }
    }
    $qx = "INSERT INTO work.xls_work_report_sub ( xwid, putdata) VALUES ('" . $get_id . "', 0)";
    $st = $cnn->prepare($qx);
    $st->execute();

    $qhr = "INSERT INTO hgpd_report ";
    $qhr.= " (xwr_id, hgpd_wherhose, hgpd_process, hgpd_itemcode, hgpd_cav, ";
    $qhr.= " hgpd_itemform, hgpd_moldlot, hgpd_worklot, hgpd_checkday, hgpd_moldday,";
    $qhr.= " hgpd_quantity, hgpd_qtycomplete, hgpd_difactive, hgpd_remaining, hgpd_namecode,";
    $qhr.= " hgpd_name, hgpd_start_at, hgpd_stop_at, hgpd_exclusion_time, hgpd_working_hours,";
    $qhr.= " hgpd_volume, hgpd_cycle, hgpd_rfid, hgpd_materiall, hgpd_material_code,";
    $qhr.= " hgpd_material_lot, hgpd_status, created_at) ";
    $qhr.= " VALUES (";
    $qhr.= "'".$get_id."',";
    $qhr.= "'".$rifd_value["hgpd_wherhose"]."',";
    $qhr.= "'保留処理',";
    $qhr.= "'".$rifd_value["hgpd_itemcode"]."',";
    $qhr.= "'".$rifd_value["hgpd_cav"]."',";
    $qhr.= "'".$rifd_value["hgpd_itemform"]."',";
    $qhr.= "'".$rifd_value["hgpd_moldlot"]."',";
    $qhr.= "'".$rifd_value["hgpd_worklot"]."',";
    $qhr.= "'".$rifd_value["hgpd_checkday"]."',";
    $qhr.= "'".$rifd_value["hgpd_moldday"]."',";
    $qhr.= "'".$rifd_value["hgpd_quantity"]."',";
    $qhr.= "'".$good_num."',";
    $qhr.= "'".$rifd_value["hgpd_difactive"]."',";
    $qhr.= "'".$rifd_value["hgpd_remaining"]."',";
    $qhr.= "'".$rifd_value["hgpd_namecode"]."',";
    $qhr.= "'".$rifd_value["hgpd_name"]."',";
    $qhr.= "'".$rifd_value["hgpd_start_at"]."',";
    $qhr.= "'".$rifd_value["hgpd_stop_at"]."',";
    $qhr.= "'".$rifd_value["hgpd_exclusion_time"]."',";
    $qhr.= "'".$rifd_value["hgpd_working_hours"]."',";
    $qhr.= "'".$rifd_value["hgpd_volume"]."',";
    $qhr.= "'".$rifd_value["hgpd_cycle"]."',";
    $qhr.= "'".$rifd_value["hgpd_rfid_new"]."',";
    $qhr.= "'".$rifd_value["hgpd_materiall"]."',";
    $qhr.= "'".$rifd_value["hgpd_material_code"]."',";
    $qhr.= "'".$rifd_value["hgpd_material_lot"]."',";
    $qhr.= "'異常',";
    $qhr.= "'".$created_at."')";
    $st2 = $cnn->prepare($qhr);
    $st2->execute();

    try{
      $q="SELECT hgpd_id FROM hgpd_report ORDER BY hgpd_id DESC ";
      $st = $cnn->execute($q);
      $new_hgpd_id = $st->fetch(PDO::FETCH_ASSOC);

      //前工程実績連携
      $qc = "INSERT INTO hgpd_report_sub (hgpd_complete_id,hgpd_before_id) VALUES ('".$new_hgpd_id["hgpd_id"]."','".$rifd_value["hgpd_id"]."') ";
      $cnn->execute($qc);

      if($rifd_value["hgpd_difactive"] > 0){
        $qhrd = "INSERT INTO hgpd_report_defectiveitem ";
        $qhrd.= "(hgpd_id,hgpdd_ditem,hgpdd_qty,hgpdd_price,hgpdd_time) ";
        $qhrd.= "VALUES (";
        $qhrd.= "'".$new_hgpd_id["hgpd_id"]."',";
        $qhrd.= "'".$rifd_value["defect_item"]."',";
        $qhrd.= "'".$rifd_value["hgpd_difactive"]."',";
        $qhrd.= "'".($rifd_value["hgpd_difactive"] * $rifd_value["単価"])."',";
        $qhrd.= "'".$rifd_value["作業時間"]."')";
        $cnn->execute($qhrd);
      }
    }catch(Exception $e){
        $err_q2=$e->getMessage();
    }
    return $new_hgpd_id["hgpd_id"];
  }
}
