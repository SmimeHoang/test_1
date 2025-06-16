<?php

/**
 * EquipmentManagement actions.
 *
 * @package    sf_sandbox
 * @subpackage EquipmentManagement
 * @author     Norimasa Arima
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EquipmentManagementActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function Database_Connect()
  {
    new sfDoctrineDatabase(array(
      'name' => 'test',
      'dsn' => 'mysql:host=127.0.0.1;dbname=test',
      'username' => 'root',
      'password' => ''
    ));
    $con = Doctrine_Manager::connection();
    return $con;
  }
  public function executeIndex(sfWebRequest $request)
  {
    $placeid = $request->getParameter('placeid');
    if ($placeid == '') {
      $stitle = '設備管理システム - Nalux';
    } else {
      $stitle = '設備管理システム - Nalux';
      $this->placeid = $placeid;
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function executeView(sfWebRequest $request)
  {
    $con = $this->Database_Connect();
    $pr_placeid = $request->getParameter('placeid');
    $pr_host  = $request->getParameter('host');
    $pr_no = $request->getParameter('no');
    $pr_run_time = $request->getParameter('run_time');
    $pr_name = $request->getParameter('name');
    if ($pr_placeid != "") {
      $arrplace = [];
      $arrplace["1000073"] = "山崎工場";
      $arrplace["1000079"] = "野洲工場";
      $arrplace["1000085"] = "大阪工場";
      $arrplace["1000125"] = "NPG";
      $this->placeid = $pr_placeid;
      $this->placename = $arrplace[$pr_placeid];
      $stitle = '温度・湿度監視システム|'. $arrplace[$pr_placeid];
      if ($pr_host  != "") {
        $this->host = $pr_host ;
        $this->manager_password = "nalux@kannri";

        if ($request->getParameter('ac') == "Ajax_GetData"){
          $q = ' SELECT * FROM environment_data WHERE emd_host  = "'.$pr_host.'" AND emd_no  = '.$pr_no.' ORDER BY emd_dt ASC';
          $st = $con->prepare($q);
          $st->execute();
          $d = $st->fetchALL(PDO::FETCH_ASSOC);
          header('Content-type: application/json; charset=UTF-8');
          echo json_encode($d);
          exit;
        } elseif ($request->getParameter('ac') == "Ajax_Set") {
          $q = 'UPDATE environment_setting SET ems_no = ?, ems_name = ?, ems_run_time = ?, updated_at = NOW() WHERE ems_host = ?';
          $d = $con->prepare($q);
          $d->execute(array($pr_no, $pr_name, $pr_run_time, $pr_host ));
          echo "OK";
          exit;
        } elseif ($request->getParameter('ac') == "Ajax_GetSetting") {
          $q = 'SELECT * FROM environment_setting WHERE ems_host = ?';
          $st = $con->prepare($q);
          $st->execute(array($pr_host));
          $d = $st->fetch(PDO::FETCH_ASSOC);
          header('Content-type: application/json; charset=UTF-8');
          echo json_encode($d);
          exit;
        }
        $q = 'SELECT * FROM environment_setting WHERE ems_host = ?';
        $st = $con->prepare($q);
        $st->execute(array($pr_host ));
        $ems_data = $st->fetch(PDO::FETCH_ASSOC);
        $this->ems_data = json_encode($ems_data, JSON_NUMERIC_CHECK);
        $q = ' SELECT DISTINCT emd_no, emd_name FROM environment_data WHERE emd_host = "'.$pr_host.'" AND emd_no <> "'.$ems_data["ems_no"].'" ORDER BY emd_dt ASC';
        $st = $con->prepare($q);
        $st->execute();
        $emd_data = $st->fetchALL(PDO::FETCH_ASSOC);
        array_unshift($emd_data, ["emd_no"=> $ems_data["ems_no"], "emd_name"=> $ems_data["ems_name"]]);
        $this->emd_data = $emd_data;
      } else {
        $q = 'SELECT * FROM environment_setting';
        $setting = $con->prepare($q);
        $setting->execute();
        $setting = $setting->fetchALL(PDO::FETCH_ASSOC);
        $this->setting = $setting;
      }
    }else{
      $stitle = '温度・湿度監視システム - Nalux';
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function executeElectricity(sfWebRequest $request)
  {
    $con = $this->Database_Connect();
    $pr_host  = $request->getParameter('host');
    $pr_no = $request->getParameter('no');
    $pr_run_time = $request->getParameter('run_time');
    if ($pr_host  == '') {
      $stitle = '静電気監視システム - Nalux';
      $q = 'SELECT * FROM environment_setting';
      $setting = $con->prepare($q);
      $setting->execute();
      $setting = $setting->fetchALL(PDO::FETCH_ASSOC);
      $this->setting = $setting;
    } else {
      $stitle = '静電気監視システム - ' . str_replace("ESP32-", "", $pr_host);
      $this->host = $pr_host;
      $this->manager_password = "nalux@kannri";
      $this->manager_password = "no_password";
      if ($request->getParameter('ac') == "Ajax_GetData") {
        $q = ' SELECT * FROM work_environment_electricity WHERE wee_host  = ? AND wee_no  = ? ORDER BY wee_date ASC';
        $st = $con->prepare($q);
        $st->execute(array($pr_host, $pr_no));
        $d = $st->fetchALL(PDO::FETCH_ASSOC);
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($d);
        exit;
      } elseif ($request->getParameter('ac') == "Ajax_Set") {
        $q = 'UPDATE environment_setting SET ems_no = ?, ems_run_time = ? WHERE ems_host = ?';
        $d = $con->prepare($q);
        $d->execute(array($pr_no, $pr_run_time, $pr_host));
        echo "OK";
        exit;
      } elseif ($request->getParameter('ac') == "Ajax_GetSetting") {
        $q = 'SELECT * FROM environment_setting WHERE ems_host = ?';
        $st = $con->prepare($q);
        $st->execute(array($pr_host));
        $d = $st->fetch(PDO::FETCH_ASSOC);
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($d);
        exit;
      }
      $q = 'SELECT * FROM environment_setting WHERE ems_host = ?';
      $st = $con->prepare($q);
      $st->execute(array($pr_host));
      $d = $st->fetch(PDO::FETCH_ASSOC);
      $this->ems_setting = json_encode($d, JSON_NUMERIC_CHECK);
      $q = 'SELECT WE.wee_no, ES.ems_run_time
				              FROM (SELECT wee_no, wee_host, MAX(emd_dt ) as max_emd_dt
                              FROM work_environment_electricity
                          GROUP BY wee_no, wee_host) WE
                  LEFT JOIN environment_setting ES
                         ON WE.wee_host = ES.ems_host
                        AND WE.wee_no = ES.ems_no
                      WHERE WE.wee_host  = ?
                   ORDER BY ES.wms_run_time DESC, WE.max_emd_dt  DESC;';
      $st = $con->prepare($q);
      $st->execute(array($pr_host));
      $pr_no = $st->fetchALL(PDO::FETCH_ASSOC);
      $this->pr_no = $pr_no;
      if (count($pr_no) > 0) {
        $q = ' SELECT WE.* FROM work_environment_electricity AS WE
                  INNER JOIN environment_setting ES
                          ON WE.wee_host = ES.ems_host
                       WHERE WE.wee_host  = ?
                    ORDER BY WE.emd_dt  ASC';
        $st = $con->prepare($q);
        $st->execute(array($pr_host));
        $d = $st->fetchALL(PDO::FETCH_ASSOC);
        $this->work_environment_electricity = json_encode($d, JSON_NUMERIC_CHECK);
      }
    }
    $this->getResponse()->setTitle($stitle);
  }
  public function executeGetSetting(sfWebRequest $request)
  {
    $con = $this->Database_Connect();
    if (!empty($_POST)) {
      $setting_data = '';
      $trim_rp_host = trim($_POST['rp_host']);
      $q = 'SELECT * FROM environment_setting WHERE ems_host = "'.$trim_rp_host.'"';
      $setting = $con->prepare($q);
      $setting->execute();
      $setting = $setting->fetch(PDO::FETCH_ASSOC);
      if($setting['ems_run_time'] > 0){
        $setting_data = $setting['ems_no'] . "," . $setting['ems_host'] . "," . $setting['ems_run_time'] . "," . $setting['ems_flg_effect'] . ",SET_OK,".date("Y-m-d");;
        if (trim($_POST['temperature']) > 0 & trim($_POST['humidity']) > 0){
          $array_data = array();
          $array_data[] = trim($_POST['temperature']);
          $array_data[] = trim($_POST['humidity']);
          $array_data[] = trim($_POST['mc_1p0']);
          $array_data[] = trim($_POST['mc_2p5']);
          $array_data[] = trim($_POST['mc_4p0']);
          $array_data[] = trim($_POST['mc_10p0']);
          $array_data[] = trim($_POST['nc_0p5']);
          $array_data[] = trim($_POST['nc_1p0']);
          $array_data[] = trim($_POST['nc_2p5']);
          $array_data[] = trim($_POST['nc_4p0']);
          $array_data[] = trim($_POST['nc_10p0']);
          $array_data[] = trim($_POST['typical_particle_size']);
          $array_data[] = $trim_rp_host;
          $q = ' UPDATE environment_setting
                    SET ems_temp = ?
                      , ems_hum = ?
                      , ems_PM_1p0 = ?
                      , ems_PM_2p5 = ?
                      , ems_PM_4p0 = ?
                      , ems_PM_10p0 = ?
                      , ems_NC_0p5 = ?
                      , ems_NC_1p0 = ?
                      , ems_NC_2p5 = ?
                      , ems_NC_4p0 = ?
                      , ems_NC_10p0 = ?
                      , ems_typical_particle_size = ?
                      , updated_at = NOW()
                  WHERE ems_host = ?';
          $d = $con->prepare($q);
          $d->execute($array_data);
        }
        echo ($setting_data);
      }else{
        echo ("NOT_OK");
      }
    }
    return sfView::NONE;
  }

  public function executeInsertData(sfWebRequest $request)
  {
    $con = $this->Database_Connect();
    if (!empty($_POST)) {
      $trim_rp_host = trim($_POST['rp_host']);
      $q = 'SELECT * FROM environment_setting WHERE ems_host = "'.$trim_rp_host.'"';
      $ems_data = $con->prepare($q);
      $ems_data->execute();
      $ems_data = $ems_data->fetch(PDO::FETCH_ASSOC);
      if ($_POST['send_model'] == 1) {
        try{
          $array_data = array();
          $array_data[] = $ems_data['ems_host'];
          $array_data[] = $ems_data['ems_no'];
          $array_data[] = $ems_data['ems_name'];
          $array_data[] = trim($_POST['temperature']);
          $array_data[] = trim($_POST['humidity']);
          $array_data[] = trim($_POST['mc_1p0']);
          $array_data[] = trim($_POST['mc_2p5']);
          $array_data[] = trim($_POST['mc_4p0']);
          $array_data[] = trim($_POST['mc_10p0']);
          $array_data[] = trim($_POST['nc_0p5']);
          $array_data[] = trim($_POST['nc_1p0']);
          $array_data[] = trim($_POST['nc_2p5']);
          $array_data[] = trim($_POST['nc_4p0']);
          $array_data[] = trim($_POST['nc_10p0']);
          $array_data[] = trim($_POST['typical_particle_size']);
          $q = 'INSERT INTO environment_data (
                            emd_host
                          , emd_no
                          , emd_name
                          , emd_dt
                          , emd_temp
                          , emd_hum
                          , emd_PM_1p0
                          , emd_PM_2p5
                          , emd_PM_4p0
                          , emd_PM_10p0
                          , emd_NC_0p5
                          , emd_NC_1p0
                          , emd_NC_2p5
                          , emd_NC_4p0
                          , emd_NC_10p0
                          , emd_typical_particle_size
                          , created_at
                          ) VALUES (?,?,?,NOW(),?,?,?,?,?,?,?,?,?,?,?,?, NOW())';
          $d = $con->prepare($q);
          $d->execute($array_data);
          echo ('INSERT_OK');
        }catch (Exception $e) {
            echo 'Error exception: ',  $e->getMessage(), "\n";
        }
      } elseif ($_POST['send_model'] == 2) {
        try{
          $array_data_insert = array();
          $saved_data = trim($_POST['saved_data']);
          if (strpos($saved_data, ',') !== false) {
            $array_saved_data = explode("/", $saved_data);
            foreach ($array_saved_data as $key => $value) {
              if(strpos($value, ',') !== false){
                $array_value_saved_data = explode(",", $value);
                $array_data = array();
                $emd_dt = trim($array_value_saved_data[0]);
                $array_data[] = "'{$ems_data['ems_host']}'";
                $array_data[] = "'{$ems_data['ems_no']}'";
                $array_data[] = "'{$ems_data['ems_name']}'";
                $array_data[] = "'{$emd_dt}'";
                $array_data[] = trim($array_value_saved_data[1]);
                $array_data[] = trim($array_value_saved_data[2]);
                $array_data[] = trim($array_value_saved_data[3]);
                $array_data[] = trim($array_value_saved_data[4]);
                $array_data[] = trim($array_value_saved_data[5]);
                $array_data[] = trim($array_value_saved_data[6]);
                $array_data[] = trim($array_value_saved_data[7]);
                $array_data[] = trim($array_value_saved_data[8]);
                $array_data[] = trim($array_value_saved_data[9]);
                $array_data[] = trim($array_value_saved_data[10]);
                $array_data[] = trim($array_value_saved_data[11]);
                $array_data[] = trim($array_value_saved_data[12]);
                $array_data[] = "NOW()";
                $array_data_insert[] = "(" . join(",", $array_data) . ")";
              }
            }
            $q = 'INSERT INTO environment_data (
                              emd_host
                            , emd_no
                            , emd_name
                            , emd_dt
                            , emd_temp
                            , emd_hum
                            , emd_PM_1p0
                            , emd_PM_2p5
                            , emd_PM_4p0
                            , emd_PM_10p0
                            , emd_NC_0p5
                            , emd_NC_1p0
                            , emd_NC_2p5
                            , emd_NC_4p0
                            , emd_NC_10p0
                            , emd_typical_particle_size
                            , created_at
                            ) VALUES ' . join(",", $array_data_insert);
            $d = $con->prepare($q);
            $d->execute();
            echo ('INSERT_OK');
          }
        }catch (Exception $e) {
          echo 'Error exception: ',  $e->getMessage(), "\n";
        }
      } elseif ($_POST['send_model'] == 3) {
        $trim_rp_ip = trim($_POST['rp_ip']);
        $q = 'UPDATE environment_setting SET ems_ip = ? WHERE ems_host = ?';
        $d = $con->prepare($q);
        $d->execute(array($trim_rp_ip, $trim_rp_host));
        echo ('UPDATE_SETTING_OK');
      }
    }
    return sfView::NONE;
  }
  public function executeSettingWee(sfWebRequest $request)
  {
    $con = $this->Database_Connect();
    if (!empty($_POST)) {
      $setting_data = '';
      $trim_rp_host = trim($_POST['rp_host']);
      $trim_rp_host_rq = $request->getParameter('rp_host');
      $trim_electricity = trim($_POST['wee_electricity']);
      $q = 'SELECT * FROM environment_setting WHERE ems_host = ?';
      $setting = $con->prepare($q);
      $setting->execute(array($trim_rp_host));
      $setting = $setting->fetch(PDO::FETCH_ASSOC);
      if ($setting['ems_run_time'] > 0) {
        $setting_data = $setting['ems_no'] . "," . $setting['ems_host'] . "," . $setting['ems_run_time'] . "," . $setting['ems_flg_effect'] . ",SET_OK". (string) $trim_rp_host_rq;
        if ($trim_electricity > 0) {
          $array_data = array();
          $array_data[] = $trim_electricity;
          $array_data[] = $trim_rp_host;
          $q = ' UPDATE environment_setting
                    SET ems_electricity  = ?
                  WHERE ems_host = ?';
          $d = $con->prepare($q);
          $d->execute($array_data);
        }
        echo ($setting_data);
      } else {
        echo ("NOT_OK");
      }
    }
    return sfView::NONE;
  }

  public function executeInsertWee(sfWebRequest $request)
  {
    $con = $this->Database_Connect();
    if (!empty($_POST)) {
      if ($_POST['send_model'] == 1) {
        $array_data = array();
        $array_data[] = trim($_POST['rp_no']);
        $array_data[] = trim($_POST['rp_host']);
        $array_data[] = trim($_POST['electricity']);
        $q = 'INSERT INTO work_environment_electricity (
                          wee_no
                        , wee_host
                        , wee_date
                        , wee_electricity
                        , emd_dt
                        ) VALUES (?,?, NOW(),?, NOW())';
        $d = $con->prepare($q);
        $d->execute($array_data);
        echo ('INSERT_OK');
      } elseif ($_POST['send_model'] == 2) {
        $array_data_insert = array();
        $saved_data = trim($_POST['saved_data']);
        if (strpos($saved_data, ',') !== false) {
          $array_saved_data = explode("/", $saved_data);
          foreach ($array_saved_data as $key => $value) {
            if (strpos($value, ',') !== false) {
              $array_value_saved_data = explode(",", $value);
              $array_data = array();
              $trim_rp_no = trim($_POST['rp_no']);
              $trim_rp_host = trim($_POST['rp_host']);
              $trim_data_date = trim($array_value_saved_data[0]);
              $array_data[] = "'{$trim_rp_no}'";
              $array_data[] = "'{$trim_rp_host}'";
              $array_data[] = "'{$trim_data_date}'";
              $array_data[] = trim($array_value_saved_data[1]);
              $array_data[] = "NOW()";
              $array_data_insert[] = "(" . join(",", $array_data) . ")";
            }
          }
          $q = 'INSERT INTO work_environment_electricity (
                            wee_no
                          , wee_host
                          , wee_date
                          , wee_electricity
                          , emd_dt
                          ) VALUES ' . join(",", $array_data_insert);
          $d = $con->prepare($q);
          $d->execute();
          echo ('INSERT_OK');
        }
      } elseif ($_POST['send_model'] == 3) {
        $trim_rp_host = trim($_POST['rp_host']);
        $trim_rp_ip = trim($_POST['rp_ip']);
        $q = 'UPDATE environment_setting SET ems_ip = ? WHERE ems_host = ?';
        $d = $con->prepare($q);
        $d->execute(array($trim_rp_ip, $trim_rp_host));
        echo ('UPDATE_SETTING_OK');
      }
    }
    return sfView::NONE;
  }

}
