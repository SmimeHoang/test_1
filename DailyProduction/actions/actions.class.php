<?php

/**
 * DailyProduction actions.
 *
 * @package    sf_sandbox
 * @subpackage hqk
 * @author     Norimasa Arima
 * @version    SVN: $Id: actions.class.php 23810 2022-02-10 11:07:44Z Kris.Wallsmith $
 */
class DailyProductionActions extends sfActions
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
    //稼働中の成形機情報を抽出
    $con = Doctrine_Manager::connection();
    if ($request->getParameter('placeid') == "") {
      $stitle = '生産情報入力 - Nalux';
    }else{
      $placeid = $request->getParameter('placeid');
      $this->placeid = $placeid;
      if ($request->getParameter('ac') == "生産情報") {
        $data = $request->getParameter('data');
        $d_data = array(
          "OK_ADD" => [],
          "ERROR_ADD" => [],
          "OK_UPDATE" => [],
          "ERROR_UPDATE" => [],
          "OK_DELETE" => [],
          "ERROR_DELETE" => [],
        );
        $add_key = array();
        $num = 0;
        if (count($data["ADD"]) > 0) {
          $add_data = "";
          foreach ($data["ADD"] as $key => $value) {
            if ($num > 0) {
              $add_data .= ",";
            }
            $value["xwrad_date"] = "'{$value["xwrad_date"]}'";
            $value["xwrad_ntab"] = ($value["xwrad_ntab"] == "" || !$value["xwrad_ntab"]) ? "NULL" : $value["xwrad_ntab"];
            $value["xwrad_piecis"] = ($value["xwrad_piecis"] == "" || !$value["xwrad_piecis"]) ? "NULL" : $value["xwrad_piecis"];
            $value["xwrad_cycle"] = ($value["xwrad_cycle"] == "" || !$value["xwrad_cycle"]) ? "NULL" : $value["xwrad_cycle"];
            $value["xwrad_start_at"] = "'{$value["xwrad_start_at"]}'";
            $value["xwrad_stop_at"] = "'{$value["xwrad_stop_at"]}'";
            $value["xwrad_name"] = "'{$value["xwrad_name"]}'";
            $value["created_at"] = "'{$value["created_at"]}'";
            $add_data .=  "(" . $value["xwrad_date"] . ","
                              . $value["xwrad_mno"] . ","
                              . $value["xwrad_ntab"] . ","
                              . $value["xwrad_piecis"] . ","
                              . $value["xwrad_placeid"] . ","
                              . $value["xwrad_cycle"] . ","
                              . $value["xwrad_start_at"] . ","
                              . $value["xwrad_stop_at"] . ","
                              . $value["lot_id"] . ","
                              . $value["xwrad_name"] . ","
                              . $value["created_at"] . ")";
            $num++;
            $add_key[]= $key;
          }
          $q = 'INSERT INTO work.xls_work_report_auxiliary_data
                          (
                              xwrad_date
                            , xwrad_mno
                            , xwrad_ntab
                            , xwrad_piecis
                            , xwrad_placeid
                            , xwrad_cycle
                            , xwrad_start_at
                            , xwrad_stop_at
                            , lot_id
                            , xwrad_name
                            , created_at
                          ) VALUE ' . $add_data . ';';
          try {
            echo "\n----登録開始---- \n";
            $st = $con->prepare($q);
            $st->execute();
            $d_data["OK_ADD"] = $add_key;
          } catch (Exception $e) {
            $d_data["ERROR_ADD"] = $add_key;
            echo "\n----登録エラー---- \n";
            echo $q;
          } finally {
            echo "\n----登録完了---- \n";
          }
          if (count($d_data["OK_ADD"]) > 0) {
            $q = "  SELECT xwrad_id AS xwrad_id
                            , CONCAT(xwrad_mno, '-', date_format(xwrad_date, '%Y%m%d'), date_format(created_at, '%Y%m%d%H%i%s')) AS xwrad_key
                         FROM xls_work_report_auxiliary_data
                        WHERE CONCAT(xwrad_mno, '-', date_format(xwrad_date, '%Y%m%d'), date_format(created_at, '%Y%m%d%H%i%s')) IN ('" .  join("','", $d_data["OK_ADD"]) . "')";
            try {
              echo "\n----登録しましたIDを取得開始ー---- \n";
              $st = $con->execute($q);
              $d = $st->fetchAll(PDO::FETCH_ASSOC);
              foreach ($d as $key => $val) {
                $add_id[$val["xwrad_key"]] = $val["xwrad_id"];
              }
              $d_data["ADD_ID"] = $add_id;
            } catch (Exception $e) {
              $d_data["ERROR_ID"] = $add_key;
              echo "\n----登録しましたIDを取得エラー---- \n";
            } finally {
              echo "\n----登録しましたIDを取得完了---- \n";
            }
          }
        }
        if (count($data["UPDATE"]) > 0) {
          $update_key = [];
          $q = "";
          foreach ($data["UPDATE"] as $key => $value) {
            $value["xwrad_date"] = "'{$value["xwrad_date"]}'";
            $value["xwrad_ntab"] = ($value["xwrad_ntab"] == "" || !$value["xwrad_ntab"]) ? "NULL" : $value["xwrad_ntab"];
            $value["xwrad_piecis"] = ($value["xwrad_piecis"] == "" || !$value["xwrad_piecis"]) ? "NULL" : $value["xwrad_piecis"];
            $value["xwrad_cycle"] = ($value["xwrad_cycle"] == "" || !$value["xwrad_cycle"]) ? "NULL" : $value["xwrad_cycle"];
            $value["xwrad_start_at"] = "'{$value["xwrad_start_at"]}'";
            $value["xwrad_stop_at"] = "'{$value["xwrad_stop_at"]}'";
            $value["xwrad_name"] = "'{$value["xwrad_name"]}'";
            $value["created_at"] = "'{$value["created_at"]}'";
            $q .= ' UPDATE work.xls_work_report_auxiliary_data SET
                          xwrad_ntab     = ' . $value["xwrad_ntab"] . '
                        , xwrad_piecis   = ' . $value["xwrad_piecis"] . '
                        , xwrad_cycle    = ' . $value["xwrad_cycle"] . '
                        , xwrad_start_at = ' . $value["xwrad_start_at"] . '
                        , xwrad_stop_at  = ' . $value["xwrad_stop_at"] . '
                        , lot_id         = ' . $value["lot_id"] . '
                        , xwrad_name     = ' . $value["xwrad_name"] . '
                        , created_at     = ' . $value["created_at"] . '
                    WHERE xwrad_id       = ' . $value["xwrad_id"] . '
                      AND xwrad_placeid  = ' . $value["xwrad_placeid"] . ';';
            $update_key[] = $key . "_" . $value["xwrad_id"];
          }
          try {
            echo "\n----更新開始---- \n";
            $st = $con->prepare($q);
            $st->execute();
            $d_data["OK_UPDATE"] = $update_key;
          } catch (Exception $e) {
            $d_data["ERROR_UPDATE"] = $update_key;
            echo "\n----更新エラー---- \n";
          } finally {
            echo "\n----更新完了---- \n";
          }
        }
        if (count($data["DELETE"]) > 0) {
          $q = "";
          $delete_key = [];
          $delete_id = [];
          foreach ($data["DELETE"] as $key => $value) {
            $delete_key[] = $key;
            $delete_id[] = $value["xwrad_id"];
          }
          $q .= ' DELETE FROM work.xls_work_report_auxiliary_data WHERE xwrad_id IN (' .  join(",", $delete_id) . ") AND xwrad_placeid = " . $placeid . ";";
          try {
            echo "\n----削除開始ー---- \n";
            $st = $con->prepare($q);
            $st->execute();
            $d_data["OK_DELETE"] = $delete_key;
          } catch (Exception $e) {
            $d_data["ERROR_DELETE"] = $delete_key;
            echo "\n----削除エラー---- \n";
          } finally {
            echo "\n----削除完了---- \n";
          }
        }
        //header('Content-type: application/json; charset=UTF-8');
        echo "_MERGE_OK_|";
        echo json_encode($d_data);
        exit;
      }
      $q = "  SELECT xwrad_mno FROM work.xls_work_report_auxiliary_data
              WHERE date_format(xwrad_stop_at, '%Y/%M/%d') = (SELECT date_format(MAX(xwrad_stop_at), '%Y/%M/%d')
                                                              FROM xls_work_report_auxiliary_data
                                                              WHERE xwrad_placeid = " . $placeid.")
                AND xwrad_placeid = " . $placeid ;
      $st = $con->execute($q);
      $listmno = $st->fetchAll(PDO::FETCH_ASSOC);
      $this->listmno = $listmno;
      $q = "SELECT *
                 , date_format(xwrad_date, '%Y/%m/%d') AS xwrad_date
                 , date_format(xwrad_date, '%w') AS xwrad_date_week_num
                 , date_format(xwrad_start_at, '%w') AS xwrad_start_week_num
                 , date_format(xwrad_start_at, '%H:%i') AS xwrad_start_time
                 , date_format(xwrad_start_at, '%m/%d') AS xwrad_start_date
                 , date_format(xwrad_start_at, '%Y/%m/%d %H:%i:%s') AS xwrad_start_at
                 , date_format(xwrad_start_at, '%Y/%m/%d') AS xwrad_start_fulldate
                 , date_format(xwrad_stop_at, '%w') AS xwrad_stop_week_num
                 , date_format(xwrad_stop_at, '%H:%i') AS xwrad_stop_time
                 , date_format(xwrad_stop_at, '%m/%d') AS xwrad_stop_date
                 , date_format(xwrad_stop_at, '%Y/%m/%d %H:%i:%s') AS xwrad_stop_at
                 , date_format(xwrad_stop_at, '%Y/%m/%d') AS xwrad_stop_fulldate
                 , CONCAT(date_format(xwrad_date, '%Y%m%d'),date_format(created_at, '%Y%m%d%H%i%s')) AS keyword
              FROM work.xls_work_report_auxiliary_data
             WHERE xwrad_placeid = " . $placeid . " ORDER BY xwrad_date DESC, created_at DESC ";
      $st = $con->execute($q);
      $d = $st->fetchAll(PDO::FETCH_ASSOC);
      $xwrad_data = array();
      foreach ($d as $key => $value) {
        if (count($xwrad_data[$value["xwrad_mno"]]["data"]) < 31){
          $xwrad_data[$value["xwrad_mno"]]["data"][$value["keyword"]] = $value;
        }
      }
      $q = " SELECT vi.*
                  , date_format(vi.開始日時, '%Y/%m/%d %H:%i') AS lot_start_date
                  , IF (vi.終了日時 IS NULL
                        , date_format(NOW(), '%Y/%m/%d %H:%i')
                        , date_format(vi.終了日時, '%Y/%m/%d %H:%i')
                        ) AS lot_stop_date
                  , IF (vi.終了日時 IS NULL
                        , CONCAT(date_format(vi.開始日時, '%Y/%m/%d'), ' ~ ...')
                        , CONCAT(date_format(vi.開始日時, '%Y/%m/%d'), ' ~ ', date_format(vi.終了日時, '%Y/%m/%d'))
                        ) AS lot_date
                  , ROUND(mi.cycle,2) as cycle
                  , if(m.searchtag <> '' , m.searchtag, m.itemname) AS '品名'
                  , mi.pieces
              FROM vi_lot_info vi
              LEFT JOIN ms_molditem_info mi
                      ON vi.品目コード = mi.itemcord
                    AND vi.型番 = mi.form_num
              LEFT JOIN work.ms_molditem m
                      ON vi.品目コード = m.itempprocord
              WHERE 工場ID = " . $placeid . " ORDER BY ロット№ DESC ";
      $st = $con->prepare($q);
      $st->execute();
      $d = $st->fetchall(PDO::FETCH_ASSOC);
      foreach ($d as $key => $value) {
        if (count($xwrad_data[$value["成形機№"]]["lot"]) < 5) {
          $xwrad_data[$value["成形機№"]]["lot"][] = $value;
        }
      }
      $this->xwrad_data = json_encode($xwrad_data);
      $q = "select number,position,placeid FROM work.ms_mold_machine ";
      $q .= "WHERE vflg = '0' ";
      $q .= "AND position NOT IN ('CZN') AND placeid LIKE '" . $placeid . "' ORDER BY  orders ASC";
      $st = $con->execute($q);
      $machine = $st->fetchAll(PDO::FETCH_ASSOC);
      $das = array();
      $installnum = array();
      foreach ($machine as $key => $value) {
        $das[$value["placeid"]][$value["position"]][$value["number"]]["status"] = $status[0]["lot_status"];
        $installnum[$value["placeid"]]["install"] += 1;
      }
      $this->machine = $das;
      $this->installnum = $installnum;
      $this->m_count = count($machine);
      $place["1000073"] = "山崎工場";
      $place["1000079"] = "野洲工場";
      $place["1000085"] = "大阪工場";
      $place["1000125"] = "NPG";
      $this->placeinfo = $place;
      $stitle = '生産情報入力 - ' . $place[$placeid];
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function executeDatetimeKeyBord(sfWebRequest $request)
	{
    $this->setdate = $request->getParameter('setdate');
    $this->id = $request->getParameter('id');
		$this->setlayout("diseble");
		//return sfView::NONE;
	}
  public function executeNumberKeyBord(sfWebRequest $request)
	{
    $this->setnumber = $request->getParameter('setnumber');
		$this->setlayout("diseble");
		//return sfView::NONE;
	}
  public function executeDailyReport(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux'
    ));
    $con = Doctrine_Manager::connection();
    if ($request->getParameter('placeid') == "") {
      $stitle = '生産情報入力 - Nalux';
    } else {
      $placeid = $request->getParameter('placeid');
      $this->placeid = $placeid;
      if ($request->getParameter('ac') == "転送") {
        $sendata = $request->getParameter('sendata');
        foreach ($sendata as $key => $value) {
          $workReport = new xlsWorkReport();
          foreach ($value as $k => $val) {
            $workReport->set($k, $val);
          }
          $workReport->save();
          $id = $workReport->getId();
          $q = "UPDATE work.xls_work_report_auxiliary_data
                   SET xwrad_ntab = " . $value['ntab'] . "
                     , xwrad_cycle = " . $value['cycle'] . "
                     , xwrad_start_at = '" . $value['xstart_time1'] . "'
                     , xwrad_stop_at = '" . $value['xend_time1'] . "'
                     , xwrad_t_flg = 1
                     , xls_id = " . $id . "
                 WHERE xwrad_id = " .  $key . "
                   AND xwrad_placeid = " . $placeid;
          $st = $con->prepare($q);
          $st->execute();
        }
        echo 'OK';
        exit;
      }
      if ($request->getParameter('ac') == "非表示") {
        $data = $request->getParameter('data');
        $q = " UPDATE work.xls_work_report_auxiliary_data
                  SET xwrad_t_flg = 1
                WHERE xwrad_id IN (" .  join(",", $data) . ")
                  AND xwrad_placeid = " . $placeid;
        $st = $con->prepare($q);
        $st->execute();
        echo 'OK';
        exit;
      }
      if ($request->getParameter('ac') == "GetJson") {
        $q = " SELECT rp.*
            , (rp.xwrad_ntab * rp.xwrad_piecis) as '生産数'
            , vi.品目コード as '品目コード'
            , vi.ロット№ as 'ロット№'
            , vi.原料名マスター as '原料名マスター'
            , vi.型番 as '型番'
            , vi.工場 as '工場名'
            , vi.工場ID as '工場コード'
            , if(m.searchtag <> '' , m.searchtag, m.itemname) AS '品名'
            , m.itemname AS '社外品名'
            , m.materials1used
            , ROUND(mi.cycle,2) AS 'サイクル'
            , 44 AS '作業コード'
            , '成形' AS '作業工程'
            FROM work.xls_work_report_auxiliary_data rp
            LEFT JOIN (SELECT 品目コード, ロット№, 原料名マスター, 型番, 工場, 工場ID, LotID FROM work.vi_lot_info GROUP BY 品目コード, ロット№, 原料名マスター, 型番, 工場, 工場ID, LotID ) vi
            ON rp.lot_id = vi.LotID
            LEFT JOIN (SELECT itemcord, form_num, AVG(cycle) as 'cycle' FROM work.ms_molditem_info GROUP BY itemcord, form_num) mi
            ON vi.品目コード = mi.itemcord
            AND vi.型番 = mi.form_num
            LEFT JOIN (SELECT itempprocord, searchtag, itemname, materials1used FROM work.ms_molditem GROUP BY itempprocord, searchtag, itemname, materials1used) m
            ON vi.品目コード = m.itempprocord
            WHERE rp.xwrad_placeid = " . $placeid . "
            AND rp.xwrad_t_flg = 0 ";
        if ($request->getParameter('start_sel') || $request->getParameter('end_sel')) {
          if ($request->getParameter('start_sel')) {
            $q .= 'AND  rp.xwrad_date >= "' . $request->getParameter('start_sel') . '"';
          }
          if ($request->getParameter('start_sel') && $request->getParameter('end_sel')) {
            $q .= ' AND ';
          }
          if ($request->getParameter('end_sel')) {
            $q .= ' rp.xwrad_date <= "' . $request->getParameter('end_sel') . '"';
          }
        }
        $q .= ' ORDER BY DATE_FORMAT(rp.created_at,"%Y/%m/%d") DESC, rp.xwrad_mno DESC, rp.xwrad_date ASC, rp.xwrad_id DESC';
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        $json = "[";
        if (count($d) > 0) {
          foreach ($d as $key => $v) {
            $min_cycle = $v['サイクル'] - $v['サイクル'] * 0.1;
            $max_cycle = $v['サイクル'] + $v['サイクル'] * 0.1;
            $cycle_now = 0;
            $is_NG = 'NG';
            $since_seconds = 0;
            $hour = 0;
            if ($v["xwrad_start_at"] && $v["xwrad_stop_at"]) {
              $start_date = new DateTime($v["xwrad_start_at"]);
              $since_start = $start_date->diff(new DateTime($v["xwrad_stop_at"]));
              $since_seconds = $since_start->d * 86400 + $since_start->h * 3600 + $since_start->i * 60 + $since_start->s;
              $cycle_now = round($since_seconds / $v["xwrad_ntab"], 2);
              $hour = round(($v["生産数"] / $since_seconds * 3600), 2);
              if (($min_cycle < $cycle_now) && ($cycle_now < $max_cycle)) {
                $is_NG = '';
              }
            }
            if ($v["xwrad_t_flg"] == 1) {
              $is_NG = '◎';
            }
            $json .= "[";
            $json .= json_encode($is_NG) . ",";
            $json .= json_encode($v["xwrad_id"]) . ",";
            $json .= json_encode(false) . ",";
            $json .= json_encode($v["xwrad_date"]) . ",";
            $json .= json_encode($v["xwrad_mno"]) . ",";
            $json .= json_encode($v["品目コード"]) . ",";
            $json .= json_encode($v["品名"]) . ",";
            $json .= json_encode($v["社外品名"]) . ",";
            $json .= json_encode($v["型番"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_ntab"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_cycle"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_piecis"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_start_at"]) . ",";
            $json .= json_encode($v["xwrad_stop_at"],) . ",";
            $json .= json_encode($since_seconds / 60, JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["生産数"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode("") . ",";  // 良品率(%)
            $json .= json_encode("") . ",";  // 不良率(%)
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";  // 良品率
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";  // 不良率
            $json .= json_encode("") . ",";  // 不良品
            $json .= json_encode("") . ",";  // 備考
            $json .= json_encode($v["xwrad_name"],) . ",";
            $json .= json_encode($v["created_at"],) . ",";
            $json .= json_encode("") . ",";  // 作業区分
            $json .= json_encode("") . ",";  // 作業コード
            $json .= json_encode("") . ",";  // 作業工程
            $json .= json_encode("") . ",";  // 転送者コード
            $json .= json_encode("") . ",";  // 転送者
            $json .= json_encode("") . ",";  // 所属
            $json .= json_encode("") . ",";  // 係セクション
            $json .= json_encode($v["工場コード"]) . ",";  // 工場コード
            $json .= json_encode($v["工場名"]) . ",";  // 工場名
            $json .= json_encode($v['ロット№']) . ",";  // ロットID
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // pending_num
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // missing_num
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // 過不足
            $json .= json_encode("") . ",";  // 過不足メモ
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // defact_check
            $json .= json_encode("") . ",";  // beforeprocess
            $json .= json_encode("") . ",";  // afterprocess
            $json .= json_encode("") . ",";  // cutmethod
            $json .= json_encode("") . ",";  // vapordepositionlot
            $json .= json_encode("") . ",";  // defect
            $json .= json_encode($v["xwrad_date"]) . ",";  // pdate
            $json .= json_encode("0000-00-00") . ",";  // dateuse
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // scheduled_number
            $json .= json_encode("") . ",";  // measure
            $json .= json_encode("") . ",";  // state
            $json .= json_encode($v['原料名マスター']) . ",";  // materialsname
            $json .= json_encode("") . ",";  // materialslot
            $json .= json_encode(round($v['materials1used'] * $v['xwrad_ntab'], 2), JSON_NUMERIC_CHECK) . ",";  // materialsused = materials1used(原料量1S) * 打数
            $json .= json_encode("00:00:00") . ",";  // lotstarttime
            $json .= json_encode("00:00:00") . ",";  // lotendtime
            $json .= json_encode($hour, JSON_NUMERIC_CHECK) . ",";  // hour (1時間で生産数)
            $json .= json_encode("", JSON_NUMERIC_CHECK) . ",";  // pproentry
            $json .= json_encode("") . ",";  // workplankind
            $json .= json_encode("") . ",";  // plankind
            $json .= json_encode("") . ",";  // plantime
            $json .= json_encode("1000101,山崎工場_仕掛品置場,1103") . ",";  // place
            $json .= json_encode("山崎工場工務係共有") . ",";  // updating_person
            $json .= json_encode(NULL) . ",";  // vap_m_no
            $json .= json_encode("無") . ",";  // vap_mix
            $json .= json_encode("生地") . ",";  // vap_befor_status
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // vap_ex_time
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // del_flg
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // 作業段取り時間
            $json .= json_encode(0, JSON_NUMERIC_CHECK) . ",";  // データ確認フラグ
            $json .= json_encode(1, JSON_NUMERIC_CHECK) . ",";  // CSV出力フラグ
            $json .= json_encode("") . ",";  // 指図№
            $json .= json_encode(1, JSON_NUMERIC_CHECK) . ",";  // セット取数
            $json .= json_encode("") . ",";  // 更新日時
            $json .= json_encode("") . ",";   // 作成日時
            $json .= json_encode($key) . "";   // 列番号
            if ($key < count($d) - 1) {
              $json .= "],";
            }
          }
          $json .= "]]";
          header('Content-type: application/json; charset=UTF-8');
          echo ($json);
        }
        exit;
      }
      $q = 'SELECT DISTINCT xwrad_date FROM work.xls_work_report_auxiliary_data WHERE xwrad_t_flg = 0 AND xwrad_placeid = ' . $placeid ;
      $q .= ' ORDER BY xwrad_date DESC';
      $slist = $con->prepare($q);
      $slist->execute();
      $dlist = $slist->fetchall(PDO::FETCH_ASSOC);
      $this->dlist = $dlist;
      $place["1000073"] = "山崎工場";
      $place["1000079"] = "野洲工場";
      $place["1000085"] = "大阪工場";
      $place["1000125"] = "NPG";
      $stitle = '生産情報入力 - ' . $place[$placeid];
    }
    $this->getResponse()->setTitle($stitle);
	}

  public function executeDailyView(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
      'name'=>'work',
      'dsn'=>'mysql:host=main-db;dbname=work',
      'username'=>'nalux-yasu',
      'password'=>'yasu-nalux'
    ));
    $cycle = 0;
    $pieces = 0;
    $select = true;
    $iserror = "";
    $mno = rawurldecode($_GET["mno"]);
    $day = rawurldecode($_GET["day"]);
    $placeid = $request->getParameter('placeid');
    $day=date_create($day);
    $next_continue = rawurldecode($_GET["next_continue"]);
    $week=["日","月","火","水","木","金","土"];
    $con = Doctrine_Manager::connection();
    $q =" SELECT vi.*
        , ROUND(mi.cycle,2) as cycle
        , if(m.searchtag <> '' , m.searchtag, m.itemname) AS '品名'
        , mi.pieces
          FROM vi_lot_info vi
          LEFT JOIN ms_molditem_info mi
          ON vi.品目コード = mi.itemcord
          AND vi.型番 = mi.form_num
          LEFT JOIN work.ms_molditem m
          ON vi.品目コード = m.itempprocord
          WHERE 成形機№ = " . $mno . "
          ORDER BY ロット№ DESC LIMIT 5";
    $st = $con->prepare($q);
    $st->execute();
    $dlot = $st->fetchall(PDO::FETCH_ASSOC);
    start_check:
    $q ='SELECT * FROM xls_work_report_auxiliary_data ';
    $q.= ' WHERE xwrad_mno = '. $mno .' AND xwrad_placeid = ' . $placeid;
    $q.=' ORDER BY xwrad_date DESC LIMIT 28';
    $st = $con->prepare($q);
    $st->execute();
    $d = $st->fetchall(PDO::FETCH_ASSOC);
    $array_date = [];
    foreach ($d as $key => $value) {
        $array_date[] = date_format(date_create($value['xwrad_date']), "Y/m/d");
    }
    $xwrad_cycle = '';
    if ($next_continue==1){
      recheck:
      $start_time = "";
      $start_time_jb = "";
      $tabindex = 2;
      foreach($d as $key => $value) {
        if ($key == 0){$xwrad_cycle = $value['xwrad_cycle'];}
        if (!$pieces){$pieces = $value['xwrad_piecis'];}
        if (date_format(date_create($value['xwrad_stop_at']),"md") == date_format($day,"md")){
          $start_time = date_format(date_create($value['xwrad_stop_at']),"H:i");
          $tabindex = 5;
        }
        if (date_format(date_create($value['xwrad_date']),"md") == date_format($day,"md"))
        {
          date_add($day,date_interval_create_from_date_string("1 days"));
          if (date("Y/m/d") <= date_format($day,"Y/m/d")){
            $next_continue=0;
            goto start_check;
          }
          goto recheck;
        }
      }
      $today = date_format($day,"j");
    }

    echo '<style>
    body {
      font-size: 18px;}
    table {
      border: 1px ;
      width: 100%;
      border-collapse: collapse;
    }
    input, select{
      font-size: 18px;
    }
    td, th {
      border: 1px solid white;
      padding: 5px;
      font-size: 18px;
    }
    th {
      text-align: center;
    }
    td.noborder, th.noborder {
      border: 1px solid transparent;
      width:2%;
      padding: 2px 0px 2px 3px;
    }
    .greenColor{
        background-color: #33CC33;
    }
    .redColor{
        background-color: #E60000;
    }
    </style>
    <html>
    <body>';
    $item=0;
    if ($_GET["update_id"]){
      $end_time = "";
      $start_time = "";
      $id = rawurldecode($_GET["update_id"]);
      $q ='SELECT * FROM xls_work_report_auxiliary_data ';
      $q.=' WHERE xwrad_id = '. $id .' AND xwrad_placeid = ' . $placeid;
      $st = $con->prepare($q);
      $st->execute();
      $d_up = $st->fetch(PDO::FETCH_ASSOC);
      $print = '<table style="text-align: left;">';
      $print .='<tr style="text-align: left;"><td>ロット管理ID</td><td>';
      $print .= '<select onchange ="fb_working_days_select(' . $mno . ')" id="working_days" >';
        for ($x = 0; $x <= 30; $x++) {
        $today = date("Y/m/d", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
        $weeknum = date("w", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
        $weekday = $week[$weeknum];
        if($today == date_format(date_create($d_up['xwrad_date']), "Y/m/d")){
          $print .= '<option selected value="'. $today . '" style="font-weight: bolder;">'.   $today . "(". $weekday . ")" . '</option>';
        }else{
          if (!in_array($today, $array_date)){
            if ($weeknum == 0) {
              $print .= '<option style="color:red;" value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            } else if ($weeknum == 6) {
              $print .= '<option style="color:blue;" value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            } else {
              $print .= '<option value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            }
          }
        }
      }
      $print .='</select></td></tr>';
      $print .= '<tr><td>ロット管理ID</td><td>';
      $print .= '<select onchange="lotid_select()" id="lotid" >';
      foreach ($dlot as $key => $value) {
        if (((int)date_format(date_create($d_up['xwrad_date']), "ymd") >= (int)$value['ロット№']) && $select) {
          if ($value['終了日時']) {
            if (date_format(date_create($d_up['xwrad_date']), "Y/m/d") > date_format(date_create($value['終了日時']), "Y/m/d")) {
              $iserror = date_format(date_create($d_up['xwrad_date']), "Y/m/d") . "には" . $mno . "号機が稼働しませんでした";
            }
          }
          $print .= "<option style='color:blue' selected value='" . $value['LotID'] . ',' . $value['cycle'] . ',' . $value['pieces'] . "'>" . $value['LotID'] . " [ " . $value['状態'] . " | " . $value['品名'] . " | " . date_format(date_create($value['開始日時']), "Y/m/d") . date_format(date_create($value['終了日時']), " ~ Y/m/d") . " ]</option>";
          $cycle = $value['cycle'];
          //$pieces = $value['pieces'];
          $select = false;
          $start_time_jb = $this->get_start_datetime($value);
        } else {
          $print .= "<option value='" . $value['LotID'] . ',' . $value['cycle'] . ',' . $value['pieces'] . "'>" . $value['LotID'] . " [ " . $value['状態'] . " | " . $value['品名'] . " | " . date_format(date_create($value['開始日時']), "Y/m/d") . date_format(date_create($value['終了日時']), " ~ Y/m/d") . " ]</option>";
        }
      }
      if ($start_time_jb != '') {
        $start_time_jb = ' 開始日時参照：' . $start_time_jb;
      }
      if ($start_time != '') {
        $start_time_jb = '';
      }
      $print .= '</select></td></tr>';
      $print .='<tr><td>打数</td><td><input type="number" id="at_bat" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" min="1" max="50000" value='.$d_up['xwrad_ntab'].' tabindex="1"><label id = "ﾏｽﾀｰサイクル"> サイクル(ﾏｽﾀｰ): '.$cycle.'</label></td></tr>';
      $print .= '<tr><td>取数</td><td><input type="number" id="number" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" tabindex="2" min="1" max="16" value="'.$d_up['xwrad_piecis'].'"><label id = "計算サイクル"></label></td></tr>';
      $print .= '<tr><td>サイクル</td><td><input type="text" id="xwrad_cycle" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);"tabindex="3"  value="' . $d_up['xwrad_cycle'] . '"/></td></tr>';
      if ($d_up['xwrad_start_at'] != ""){$start_time = date_format(date_create($d_up['xwrad_start_at']),'H:i');}
      if ($d_up['xwrad_stop_at'] != ""){$end_time = date_format(date_create($d_up['xwrad_stop_at']),'H:i');}
      if ($start_time_jb != ''){
        $start_time_jb = ' 開始日時参照：'. $start_time_jb;
      }
      if ($start_time != ''){
        $start_time_jb = '';}
      $print .= '<tr><td><label onclick="fb_refresh(this);">開始日時</label></td><td><input type="time" id="開始日時" onclick = "datetime(this.id)" onfocus = "datetime(this.id)" value="'.$start_time. '" tabindex="4">
                <label>'.$start_time_jb.'</label></td></tr>';
      $print .= '<tr><td><label onclick="fb_refresh(this);">終了日時</label></td><td><input type="time" id="終了日時" onclick = "datetime(this.id)" onfocus = "datetime(this.id)" value="'.$end_time .'" tabindex="5">';
      $print .= '<select id="end_date" style="margin-left:10px" >';
      while($item<7){
          if (date_format($day,'Y/m/d') == date_format(date_create($d_up['xwrad_stop_at']),'Y/m/d')){
            $print.= "<option selected value='".date_format($day,'Y/m/d')."'>".date_format($day,'Y/m/d')."</option>";
          }else{
            $print.= "<option value='".date_format($day,'Y/m/d')."'>".date_format($day,'Y/m/d')."</option>";
          }
          date_add($day,date_interval_create_from_date_string("1 days"));
          $item +=1;
      }
      $print .='</select></td></tr>';
      $print .='</table>';
      $print .= '<br>
                <center>
                  <button style="width: 35%; margin-left:200px;" onclick="fb_update(' . $id . ',' . $mno . ')" tabindex="6">更新</button>
                  <button style="width: 15%; margin-left:100px; font-size: 2vw;" onclick="fb_delete(' . $id . ',' . $mno . ',' . $d_up['xls_id'] . ')" >削除</button>
                </center>';
      $print .='<br>';
    } elseif ($_GET["add_insert"]) {
      $xwrad_cycle = rawurldecode($_GET["cycle"]);
      $pieces = rawurldecode($_GET['piecis']);
      $print  = '<table>';
      $print .= '<tr><td>勤務日</td><td><select onchange ="fb_working_days_select(' . $mno . ')" id="working_days" >';
      for ($x = 0; $x <= 30; $x++) {
        $today = date("Y/m/d", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
        $weeknum = date("w", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
        $weekday = $week[$weeknum];
        // if ($today == date_format(date_create($d[0]['xwrad_date']), "Y/m/d")) {
        //   break;
        // }
        if ($today == date_format($day, "Y/m/d")) {
          $print .= '<option selected value="' . $today . '" style="font-weight: bolder;">' .   $today . "(" . $weekday . ")" . '</option>';
        } else {
          if (!in_array($today, $array_date)) {
            if ($weeknum == 0) {
              $print .= '<option style="color:red;" value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            } else if ($weeknum == 6) {
              $print .= '<option style="color:blue;" value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            } else {
              $print .= '<option value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            }
          }
        }
      }
      $print .= '</td></tr>';
      $print .= '<tr><td>ロット管理ID</td><td>';
      $print .= '<select onchange="lotid_select()" id="lotid" >';
      foreach ($dlot as $key => $value) {
        if (((int)date_format($day, "ymd") >= (int)$value['ロット№']) && $select) {
          if ($value['終了日時']) {
            if (date_format($day, "Y/m/d") > date_format(date_create($value['終了日時']), "Y/m/d")) {
              $iserror = date_format($day, "Y/m/d") . "には" . $mno . "号機が稼働しませんでした";
            }
          }
          $print .= "<option style='color:blue' selected value='" . $value['LotID'] . ',' . $value['cycle'] . ',' . $value['pieces'] . "'>" . $value['LotID'] . " [ " . $value['状態'] . " | " . $value['品名'] . " | " . date_format(date_create($value['開始日時']), "Y/m/d") . date_format(date_create($value['終了日時']), " ~ Y/m/d")." ]</option>";
          $cycle = $value['cycle'];
          //$pieces = $value['pieces'];
          $select = false;
          $start_time_jb = $this->get_start_datetime($value);
        } else {
          $print .= "<option value='" . $value['LotID'] . ',' . $value['cycle'] . ',' . $value['pieces'] . "'>" . $value['LotID'] . " [ " . $value['状態'] . " | " . $value['品名'] . " | " . date_format(date_create($value['開始日時']), "Y/m/d") . date_format(date_create($value['終了日時']), " ~ Y/m/d") . " ]</option>";
        }
      }
      if ($start_time_jb != '') {
        $start_time_jb = ' 開始日時参照：' . $start_time_jb;
      }
      if ($start_time != '') {
        $start_time_jb = '';
      }
      $print .= '</select></td></tr>';
      $print .= '<tr><td>打数</td><td><input type="number" id="at_bat" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" min="1" max="50000" tabindex="1" /><label id = "ﾏｽﾀｰサイクル"> サイクル(ﾏｽﾀｰ): ' . $cycle . '</label></td></tr>';
      $print .= '<tr><td>取数</td><td><input type="number" id="number" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" min="1" max="16" tabindex="2" value="' . $pieces . '"/><label id = "計算サイクル"></label></td></tr>';
      $print .= '<tr><td>サイクル</td><td><input type="text" id="xwrad_cycle" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" tabindex="3" value="' . $xwrad_cycle . '"/></td></tr>';
      $print .= '<tr><td><label onclick="fb_refresh(this);">開始日時</label></td>
                     <td><input type="time" id="開始日時" onclick = "datetime(this.id)" onfocus = "datetime(this.id)" tabindex="4" value = "' . $start_time . '" required/>
                     <label id = "開始日時参照" onclick = "load_start_time()" >' . $start_time_jb . '</label></td>
                </tr>';
      $print .= '<tr><td><label onclick="fb_refresh(this);">終了日時</label></td><td><input type="time" id="終了日時" onclick = "datetime(this.id)" onfocus = "datetime(this.id)" tabindex="5" required/>';
      $print .= '<select  id="end_date" style="margin-left:10px">';
      while ($item < 7) {
        if ($item == 1) {
          $print .= "<option selected value='" . date_format($day, 'Y/m/d') . "'>" . date_format($day, 'Y/m/d') . "</option>";
        } else {
          $print .= "<option value='" . date_format($day, 'Y/m/d') . "'>" . date_format($day, 'Y/m/d') . "</option>";
        }
        date_add($day, date_interval_create_from_date_string("1 days"));
        $item += 1;
      }
      $print .= '</select></td></tr>';
      $print .= '</table>';
      $print .= '<br>
                    <center>
                      <button style="width: 35%; margin-left:200px;" onclick="fb_insert(' . $mno . ')" tabindex="6">登録</button>
                      <button style="width: 15%; margin-left:100px; font-size: 2vw;" onclick="fb_skip(' . $mno . ')" >スキップ</button>
                    </center>';
      $print .= '<br>';
    } elseif ($next_continue==1){
      $print  ='<table>';
      $print .= '<tr><td>勤務日</td><td><select onchange="fb_working_days_select(' . $mno . ')" id="working_days" >';
      for ($x = 0; $x <= 30; $x++) {
        $today = date("Y/m/d", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
        $weeknum = date("w", mktime(0, 0, 0, date("m"), (date("d") - $x),   date("Y")));
        $weekday = $week[$weeknum];
        // if ($today == date_format(date_create($d[0]['xwrad_date']), "Y/m/d")) {
        //   break;
        // }
        if ($today == date_format($day, "Y/m/d")) {
          $print .= '<option selected value="' . $today . '" style="font-weight: bolder;">' .   $today . "(" . $weekday . ")" . '</option>';
        } else {
          if (!in_array($today, $array_date)) {
            if ($weeknum == 0) {
              $print .= '<option style="color:red;" value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            } else if ($weeknum == 6) {
              $print .= '<option style="color:blue;" value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            } else {
              $print .= '<option value="' . $today . '" >' .   $today . "(" . $weekday . ")" . '</option>';
            }
          }
        }
      }
      $print .= '</select></td></tr>';
      $print .='<tr><td>ロット管理ID</td><td>';
      $print .= '<select onchange="lotid_select()" id="lotid" >';
      foreach($dlot as $key => $value) {
        if(((int)date_format($day,"ymd") >= (int)$value['ロット№']) && $select){
          if ($value['終了日時']) {
            if (date_format($day, "Y/m/d") > date_format(date_create($value['終了日時']), "Y/m/d")) {
              $iserror = date_format($day, "Y/m/d") . "には" . $mno . "号機が稼働しませんでした";
            }
          }
          $print.= "<option style='color:blue' selected value='".$value['LotID'].','.$value['cycle'].','.$value['pieces']."'>".$value['LotID']." [ ".$value['状態']." | ".$value['品名']." | " . date_format(date_create($value['開始日時']), "Y/m/d") . date_format(date_create($value['終了日時']), " ~ Y/m/d") ." ]</option>";
          $cycle = $value['cycle'];
          $pieces = $value['pieces'];
          $select = false;
          $start_time_jb = $this->get_start_datetime($value);
        }else{
          $print.= "<option value='".$value['LotID'].','.$value['cycle'].','.$value['pieces']."'>".$value['LotID']." [ ".$value['状態']." | ".$value['品名']." | ". date_format(date_create($value['開始日時']), "Y/m/d") . date_format(date_create($value['終了日時']), " ~ Y/m/d") ." ]</option>";
        }
      }
      if ($start_time_jb != ''){
        $start_time_jb = ' 開始日時参照：'. $start_time_jb;
      }
      if ($start_time != ''){
        $start_time_jb = "";
      }
      $print .='</select></td></tr>';
      $print .='<tr><td>打数</td><td><input type="number" id="at_bat" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" min="1" max="50000" tabindex="1" /><label id = "ﾏｽﾀｰサイクル"> サイクル(ﾏｽﾀｰ): '.$cycle. '</label></td></tr>';
      $print .= '<tr><td>取数</td><td><input type="number" id="number" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" min="1" tabindex="2" max="16" value="'.$pieces.'"/><label id = "計算サイクル"></label></td></tr>';
      $print .= '<tr><td>サイクル</td><td><input type="text" id="xwrad_cycle" onclick="inputnumber(this.id);" onfocus= "inputnumber(this.id);" tabindex="3" value="' . $xwrad_cycle . '"/></td></tr>';
      $print .= '<tr><td><label onclick="fb_refresh(this);">開始日時</label></td>
                     <td><input type="time" id="開始日時" onclick = "datetime(this.id)" onfocus = "datetime(this.id)" tabindex="4" value = "' . $start_time . '" required/>
                     <label id = "開始日時参照" onclick = "load_start_time()" >'.$start_time_jb.'</label></td>
                </tr>';
      $print .= '<tr><td><label onclick="fb_refresh(this);">終了日時</label></td><td><input type="time" id="終了日時" onclick = "datetime(this.id)" onfocus = "datetime(this.id)" tabindex="5" required />';
      $print .='<select  id="end_date" style="margin-left:10px">';
      while($item<7){
          if ($item == 1){
            $print.= "<option selected value='".date_format($day,'Y/m/d')."'>".date_format($day,'Y/m/d')."</option>";
          }else{
            $print.= "<option value='".date_format($day,'Y/m/d')."'>".date_format($day,'Y/m/d')."</option>";
          }
          date_add($day,date_interval_create_from_date_string("1 days"));
          $item +=1;
      }
      $print .='</select></td></tr>';
      $print .='</table>';
      $print .= '<br>
                    <center>
                      <button style="width: 35%; margin-left:200px;" onclick="fb_insert('.$mno. ')" tabindex="6">登録</button>
                      <button style="width: 15%; margin-left:100px; font-size: 90%;" onclick="fb_skip(' . $mno . ')" >スキップ</button>
                    </center>';
      $print .='<br>';
    }
    if ($iserror != "") {
      $print .= '<span id="iserror" style="color:red; margin-left:150px;">※注意： ' . $iserror . '</span><br><br> ';
    }
    echo ($print);
    echo "<table style='font-size: 9px;>
    <tbody style='font-size: 9px;'>
    <tr>
    <th>勤務日</th>
    <th>成形機</th>
    <th>打数</th>
    <th>取数</th>
    <th>サイクル</th>
    <th>開始日時</th>
    <th>終了日時</th>
    <th class='noborder'></th>
    </tr>";
    foreach($d as $key => $value) {
      echo "<tr id='". $value['xwrad_id'] . ",".$mno.",".date_format(date_create($value['xwrad_date']),"Y/m/d")."' onclick='update_view(this.id)'>";
      echo "<td style='text-align: right;'><span id='xwrad_date".date_format(date_create($value['xwrad_date']),"j")."'>" . date_format(date_create($value['xwrad_date']),"m/d ("). $week[date_format(date_create($value['xwrad_date']),"w")].")</span></td>";
      echo "<td style='text-align: center;'>" . $value['xwrad_mno'] . "</td>";
      echo "<td style='text-align: center;'>" . $value['xwrad_ntab'] . "</td>";
      echo "<td style='text-align: center;'><span id='number".$key."'>" . $value['xwrad_piecis'] . "</span></td>";
      echo "<td style='text-align: center;'>" . $value['xwrad_cycle'] . "</td>";
      if ($value['xwrad_start_at']){
        echo "<td>" . date_format(date_create($value['xwrad_start_at']),"m/d (". $week[date_format(date_create($value['xwrad_start_at']),"w")].") H:i"). "</td>";
      }else{
        echo "<td></td>";
      }
      if ($value['xwrad_stop_at']){
        echo "<td>" . date_format(date_create($value['xwrad_stop_at']),"m/d (". $week[date_format(date_create($value['xwrad_stop_at']),"w")].") ") ."<span id='end_date_sql".date_format(date_create($value['xwrad_stop_at']),"j")."'>" . date_format(date_create($value['xwrad_stop_at']),"H:i") . "</span></td>";
      }else{
        echo "<td></td>";
      }
      echo "<td class='noborder'><button id='" . $mno . "," . $value['xwrad_cycle'] . "," . date_format(date_create($value['xwrad_date']), "Y/m/d") . "," . $value['xwrad_piecis'] . "' onclick='fb_add_insert(event,this.id)'>+</button></td>";
        echo "</tr></tbody>";
    }
    echo "</table>";
    echo '</body>';
    echo '<input type="hidden" id="cycle" value="'.$cycle. '" />';
    echo '</html>
    ';
    mysqli_close($con);
    return sfView::NONE;
	}
  public function get_start_datetime($d)
  {
    new sfDoctrineDatabase(array(
      'name' => 'work',
      'dsn' => 'mysql:host=main-db;dbname=work',
      'username' => 'nalux-yasu',
      'password' => 'yasu-nalux',
    ));
      $con    = Doctrine_Manager::connection();
      //ロット情報の取得
      $q = "SELECT 状態,品目コード,品名,ロット№,成形機№,型番,DATE_FORMAT(開始日時,'%Y-%m-%d %H:%i') as 開始日時,";
      $q.= "DATE_FORMAT(終了日時,'%Y-%m-%d %H:%i') as 終了日時,原料名マスター,materials1used,tray_stok,pieces FROM vi_lot_info i ";
      $q.= "LEFT JOIN ms_molditem m ON i.品目コード = m.itempprocord ";
      $q.= "LEFT JOIN ms_molditem_info mi ON i.品目コード = mi.itemcord AND i.型番 = mi.form_num ";
      $q.= "WHERE LotID ='".$d['LotID']."' Group By 品目コード ";
      $st = $con->execute($q);
      $lotInfos = $st->fetchAll(PDO::FETCH_ASSOC);

      foreach ($lotInfos as $num => $lotInfo) {
          //品目コードから前回の工程情報を抜き取り
          $q = 'SELECT workitem,xlsnum,end_time1,moldlot,place  FROM xls_work_report LEFT JOIN xls_work_report_sub ON xls_work_report.id = xls_work_report_sub.xwid ';
          $q .= "WHERE itemcord = '".$lotInfo['品目コード']."' ";
          $q .="AND moldmachine = '".$lotInfo['成形機№']."' ";
          //$q .="AND moldlot = '".$lotInfo['ロット№']."' ";
          $q .= "AND workitem LIKE '%成形%' ";
          $q .= "AND del_flg = '0' ";
          $q .= "AND remark NOT LIKE '%不良処理登録%' ";
          $q .= 'ORDER BY end_time1 DESC LIMIT 1 ';
          $st       = $con->execute($q);
          $lasttime = $st->fetch(PDO::FETCH_ASSOC);
          if (time()-strtotime($lasttime["end_time1"]) < (3600*24*1.5)) {
              $day["end_time1"]=date("Y/m/d H:i", strtotime($lasttime["end_time1"]));
          } else {
              $day["end_time1"]="";
          }
          if ($lasttime["moldlot"]!=$lotInfo["ロット№"]) {
              $day["end_time1"]=date("Y/m/d H:i", strtotime($lotInfo["開始日時"]));
          }
          $day["place"]=$lasttime["place"];
      }
      return $day["end_time1"];
  }

  public function executeDailyDLView(sfWebRequest $request)
  {
    new sfDoctrineDatabase(array(
        'name'=>'work',
        'dsn'=>'mysql:host=main-db;dbname=work',
        'username'=>'nalux-yasu',
        'password'=>'yasu-nalux'
      ));
    $con = Doctrine_Manager::connection();
    if ($request->getParameter('placeid') == "") {
      $stitle = '生産情報入力 - Nalux';
    } else {
      $placeid = $request->getParameter('placeid');
      $this->placeid = $placeid;
      $week = ["日", "月", "火", "水", "木", "金", "土"];
      if ($request->getParameter('ac')=="GetJson")
      {
        $q = " SELECT rp.*
                    , xls.*
                    , rp.created_at as created_at
                    , xls.created_at as xls_created_at
                    , (rp.xwrad_ntab * rp.xwrad_piecis) as '生産数'
                    FROM work.xls_work_report_auxiliary_data rp
                    LEFT JOIN work.xls_work_report xls
                    ON rp.xls_id = xls.id
                    WHERE rp.xwrad_placeid = " . $placeid ;
        if ($request->getParameter('start_sel')){
          $q .=' AND rp.xwrad_date >= "'.$request->getParameter('start_sel').'"';
        }
        if ($request->getParameter('end_sel')){
          $q .=' AND rp.xwrad_date <= "'.$request->getParameter('end_sel').'"';
        }
        $q.= ' ORDER BY DATE_FORMAT(rp.created_at,"%Y/%m/%d") DESC, rp.xwrad_mno DESC, rp.xwrad_date ASC, rp.xwrad_id DESC';
        $st = $con->prepare($q);
        $st->execute();
        $d = $st->fetchall(PDO::FETCH_ASSOC);
        $json="[";
        if(count($d) > 0){
          foreach ($d as $key => $v) {
            $min_cycle = $v['xwrad_cycle'] - $v['xwrad_cycle'] * 0.1;
            $max_cycle = $v['xwrad_cycle'] + $v['xwrad_cycle'] * 0.1;
            $cycle_now = 0;
            $is_NG = '<label style="color: red;">NG</label>';
            $since_seconds = 0;
            $hour = 0;
            if ($v["xwrad_start_at"] && $v["xwrad_stop_at"]) {
              $start_date = new DateTime($v["xwrad_start_at"]);
              $since_start = $start_date->diff(new DateTime($v["xwrad_stop_at"]));
              $since_seconds = $since_start->d * 86400 + $since_start->h * 3600 + $since_start->i * 60 + $since_start->s;
              $cycle_now = round($since_seconds / $v["xwrad_ntab"], 2);
              $hour = round(($v["生産数"] / $since_seconds * 3600), 2);
              if (($min_cycle < $cycle_now) && ($cycle_now < $max_cycle)) {
                $is_NG = '';
              }
            }
            if ($v["xwrad_t_flg"] == 1) {
              $is_NG = '◎';
            }
            $haiki = "";
            $sample = "";
            $misitem = "";
            if ($v["defectivesitem"]){
              foreach(explode(",", $v["defectivesitem"]) as $kkk => $vvv){
                if(explode("=>", $vvv)[0] == "生産時廃棄"){
                  $haiki = explode("=>", $vvv)[1];
                } else if (explode("=>", $vvv)[0] == "サンプル") {
                  $sample = explode("=>", $vvv)[1];
                } else if (explode("=>", $vvv)[0] == "欠損品") {
                  $misitem = explode("=>", $vvv)[1];
                }
              }
            }
            $json .= "[";
            $json .= json_encode($is_NG) . ",";
            $json .= json_encode(date_format(date_create($v['xwrad_date']), "Y/m/d"). "(". $week[date_format(date_create($v['xwrad_date']), "w")] . ")") . ",";
            $json .= json_encode($v["xwrad_mno"]) . ",";
            $json .= json_encode($v["itemcord"]) . ",";
            $json .= json_encode($v["itemname"]) . ",";
            $json .= json_encode($v["itemform"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_ntab"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_cycle"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["xwrad_piecis"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode(date_format(date_create($v['xwrad_start_at']), "Y/m/d h:i")) . ",";
            $json .= json_encode(date_format(date_create($v['xwrad_stop_at']), "Y/m/d h:i")) . ",";
            $json .= json_encode($since_seconds / 60, JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["生産数"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["goodnum"], JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($haiki, JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($sample, JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($misitem, JSON_NUMERIC_CHECK) . ",";
            $json .= json_encode($v["badnum"], JSON_NUMERIC_CHECK) . ",";
            if($v["gootrate"]){
              $json .= json_encode($v["gootrate"]*100 . "%") . ",";  // 良品率
            }else{
              $json .= json_encode("") . ",";  // 良品率
            }
            if ($v["badrate"]) {
              $json .= json_encode($v["badrate"]*100 . "%") . ",";  // 不良率
            } else {
              $json .= json_encode("") . ",";  // 良品率
            }
            $json .= json_encode($v["defectivesitem"]) . ",";  // 不良品
            $json .= json_encode($v["remark"]) . ",";  // 備考
            $json .= json_encode($v["xwrad_name"]) . ",";
            $json .= json_encode($v["created_at"]) . ",";
            $json .= json_encode($v["workitem"]) . ",";  // 作業区分
            $json .= json_encode($v["xlsnum"]) . ",";  // 作業コード
            $json .= json_encode($v["workkind"]) . ",";  // 作業工程
            $json .= json_encode($v["usercord"]) . ",";  // 転送者コード
            $json .= json_encode($v["username"]) . ",";  // 転送者
            $json .= json_encode($v["usergp1"]) . ",";  // 所属
            $json .= json_encode($v["usergp2"]) . ",";  // 係セクション
            $json .= json_encode($v["moldplaceid"]) . ",";  // 工場コード
            $json .= json_encode($v["moldplace"]) . ",";  // 工場名
            $json .= json_encode($v['moldlot']) . ",";  // ロットID
            $json .= json_encode($v["pending_num"], JSON_NUMERIC_CHECK) . ",";  // pending_num
            $json .= json_encode($v["missing_num"], JSON_NUMERIC_CHECK) . ",";  // missing_num
            $json .= json_encode($v["e_or_d"], JSON_NUMERIC_CHECK) . ",";  // 過不足
            $json .= json_encode($v["e_or_d_memo"]) . ",";  // 過不足メモ
            $json .= json_encode($v["defact_check"], JSON_NUMERIC_CHECK) . ",";  // defact_check
            $json .= json_encode($v["beforeprocess"]) . ",";  // beforeprocess
            $json .= json_encode($v["afterprocess"]) . ",";  // afterprocess
            $json .= json_encode($v["cutmethod"]) . ",";  // cutmethod
            $json .= json_encode($v["vapordepositionlot"]) . ",";  // vapordepositionlot
            $json .= json_encode($v["defect"]) . ",";  // defect
            $json .= json_encode($v["pdate"]) . ",";  // pdate
            $json .= json_encode($v["dateuse"]) . ",";  // dateuse
            $json .= json_encode($v["scheduled_number"], JSON_NUMERIC_CHECK) . ",";  // scheduled_number
            $json .= json_encode($v["measure"]) . ",";  // measure
            $json .= json_encode($v["state"]) . ",";  // state
            $json .= json_encode($v['materialsname']) . ",";  // materialsname
            $json .= json_encode($v["materialslot"]) . ",";  // materialslot
            $json .= json_encode($v['materialsused'], JSON_NUMERIC_CHECK) . ",";  // materialsused = materials1used(原料量1S) * 打数
            $json .= json_encode($v["lotstarttime"]) . ",";  // lotstarttime
            $json .= json_encode($v["lotendtime"]) . ",";  // lotendtime
            $json .= json_encode($v["hour"], JSON_NUMERIC_CHECK) . ",";  // hour (1時間で生産数)
            $json .= json_encode($v["pproentry"], JSON_NUMERIC_CHECK) . ",";  // pproentry
            $json .= json_encode($v["workplankind"]) . ",";  // workplankind
            $json .= json_encode($v["plankind"]) . ",";  // plankind
            $json .= json_encode($v["plantime"]) . ",";  // plantime
            $json .= json_encode($v["place"]) . ",";  // place
            $json .= json_encode($v["updating_person"]) . ",";  // updating_person
            $json .= json_encode($v["vap_m_no"]) . ",";  // vap_m_no
            $json .= json_encode($v["vap_mix"]) . ",";  // vap_mix
            $json .= json_encode($v["vap_befor_status"]) . ",";  // vap_befor_status
            $json .= json_encode($v["vap_ex_time"], JSON_NUMERIC_CHECK) . ",";  // vap_ex_time
            $json .= json_encode($v["xsetuptime"], JSON_NUMERIC_CHECK) . ",";  // 作業段取り時間
            $json .= json_encode($v["d_check"], JSON_NUMERIC_CHECK) . ",";  // データ確認フラグ
            $json .= json_encode($v["xputdata"], JSON_NUMERIC_CHECK) . ",";  // CSV出力フラグ
            $json .= json_encode($v["documentno"]) . ",";  // 指図№
            $json .= json_encode($v["item_set_num"], JSON_NUMERIC_CHECK) . ",";  // セット取数
            $json .= json_encode($v["updated_at"]) . ",";  // 更新日時
            $json .= json_encode($v["xls_created_at"]) . "";   // 作成日時
            if ($key < count($d) - 1) {
              $json .= "],";
            }
          }
          $json .= "]]";
          header('Content-type: application/json; charset=UTF-8');
          echo ($json);
        }
        exit;
      }
      $q = 'SELECT DISTINCT xwrad_date FROM work.xls_work_report_auxiliary_data WHERE xwrad_placeid = ' . $placeid ;
      $q .= ' ORDER BY xwrad_date DESC';
      $slist = $con->prepare($q);
      $slist->execute();
      $dlist = $slist->fetchall(PDO::FETCH_ASSOC);
      $this->dlist = $dlist;
      $place["1000073"] = "山崎工場";
      $place["1000079"] = "野洲工場";
      $place["1000085"] = "大阪工場";
      $place["1000125"] = "NPG";
      $stitle = '生産情報入力 - ' . $place[$placeid];
    }
    $this->getResponse()->setTitle($stitle);
  }
}
