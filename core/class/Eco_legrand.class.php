<?php

  /* This file is part of Jeedom.
  *
  * Jeedom is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
  * (at your option) any later version.
  *
  * Jeedom is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
  */

  require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class Eco_legrand extends eqLogic {

 

  public function preUpdate() {
    if ($this->getConfiguration('addr') == '') {
      throw new Exception(__('L\'adresse ne peut être vide',__FILE__));
    }
  }

  public function postUpdate() {

    $this->getInformations();
    $this->getData();
    $this->getConsoElec_jour();
    $this->getConsoElec_heure();
  }

  public function checkCmdOk($_type_data, $_subtype, $_name,$_logical_id, $_template,$_unite) {
    $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),$_logical_id);
    if (!is_object($Eco_legrandCmd)) {
      //log::add('stock', 'debug', 'Création de la commande ' . $_name);
      $Eco_legrandCmd = new Eco_legrandCmd();
      $Eco_legrandCmd->setName(__($_type_data . ' - ' . $_name, __FILE__));
      $Eco_legrandCmd->setEqLogic_id($this->getId());
      $Eco_legrandCmd->setEqType('Eco_legrand');
      $Eco_legrandCmd->setLogicalId($_logical_id);
      $Eco_legrandCmd->setType('info');
      $Eco_legrandCmd->setSubType($_subtype);
      $Eco_legrandCmd->setIsVisible('1');
      $Eco_legrandCmd->setDisplay('showNameOndashboard', 1);
      $Eco_legrandCmd->setDisplay('showIconAndNamedashboard', 1);
      $Eco_legrandCmd->setDisplay('showStatsOndashboard', 1);
      $Eco_legrandCmd->setConfiguration('lastvalue', 0);
      $Eco_legrandCmd->setIsHistorized(1);
      $Eco_legrandCmd->setConfiguration('type', $_type_data);
      $Eco_legrandCmd->setUnite($_unite);
      $Eco_legrandCmd->setConfiguration('repeatEventManagement', 'always');
      $Eco_legrandCmd->setConfiguration('historizeMode', 'none');
      $Eco_legrandCmd->setTemplate("mobile",'line' );
      $Eco_legrandCmd->setTemplate("dashboard",'line' );
      $Eco_legrandCmd->setDisplay('icon', $_template);
      $Eco_legrandCmd->save();
      // $Eco_legrandCmd->event(0);
    }
    $nom=$Eco_legrandCmd->getName();
    if($nom !=$_type_data . ' - ' . $_name){
      log::add('Eco_legrand', 'info', 'nom ' . $nom . "|||" .$_type_data . ' - ' . $_name);

      $Eco_legrandCmd->setName($_type_data . ' - ' . $_name);
      $Eco_legrandCmd->save();
    }
  }

  public function getInformations() {
    if(strpos($this->getConfiguration('addr', ''), "https://")===0 || strpos($this->getConfiguration('addr', ''), "http://")===0){
      $URL = $this->getConfiguration('addr', '');
    }else {
      $URL = "http://" .$this->getConfiguration('addr', '');
    }
    $devAddr = $URL . '/instant.json';

    $request_http = new com_http($devAddr);
    $devResult = $request_http->exec(30);
    log::add('Eco_legrand', 'info', 'getInformations ' . $devAddr);
    //log::add('Eco_legrand', 'debug', print_r($devResult, true));
    if ($devResult === false) {
      log::add('Eco_legrand', 'error', 'problème de connexion ' . $devAddr);
    } else {
      $devResbis = utf8_encode($devResult);
      $devList = json_decode($devResbis, true);
      //log::add('Eco_legrand', 'debug', print_r($devList, true));
      $i=1;
      foreach($devList as $name => $value) {
        if ($name === 'classe' || $name === 'minute') {
          // pas de traitement sur l'heure
        } else {
          $this->checkCmdOk('inst','numeric', trim($name), 'inst_circuit' . $i , '<i class="fas fa-bolt"></i>',"W");
          $this->checkCmdOk('csv','numeric', "Consommation " .trim($name) . ' par heure','conso_circuit'.$i ."_heure",'<i class="fas fa-bolt"></i>',"kW");
          $this->checkCmdOk('csv','numeric', "Consommation totale par heure ",'conso_totale_heure','<i class="fas fa-bolt"></i>',"kW");
          $this->checkCmdOk('csv','numeric', "Consommation Autre par heure " ,'conso_autre_heure','<i class="fas fa-bolt"></i>',"kW");
          $this->checkCmdOk('csv','numeric', 'Consommation ' .trim($name) . ' journalière','conso_circuit'.$i ."_jour",'<i class="fas fa-bolt"></i>',"kW");
          $this->checkCmdOk('csv','numeric', 'Consommation totale journalière ','conso_totale_jour','<i class="fas fa-bolt"></i>',"kW");
          $this->checkCmdOk('csv','numeric', 'Consommation Autre journalière ' ,'conso_autre_jour','<i class="fas fa-bolt"></i>',"kW");
          $this->checkAndUpdateCmd('inst_circuit' . $i, $value);
          $i=$i+1;
        }
      }
    }
    $this->refreshWidget();
  }

  public function getData() {
    if(strpos($this->getConfiguration('addr', ''), "https://")===0 || strpos($this->getConfiguration('addr', ''), "http://")===0){
      $URL = $this->getConfiguration('addr', '');
    }else {
      $URL = "http://" .$this->getConfiguration('addr', '');
    }
    $devAddr = $URL . '/ti.json';

    $request_http = new com_http($devAddr);
    $devResult = $request_http->exec(30);
    log::add('Eco_legrand', 'info', 'getInformations ' . $devAddr);

    if ($devResult === false) {
      log::add('Eco_legrand', 'error', 'problème de connexion ' . $devAddr);
    } else {
      $devResbis = utf8_encode($devResult);
      $corrected = preg_replace('/\s+/', '', $devResbis);
      $corrected = preg_replace('/\:0,/', ': 0,', $corrected);
      $corrected = preg_replace('/\:[0]+/', ":", $corrected);
      $devList = json_decode($corrected, true);
      //log::add('Eco_legrand', 'debug', print_r($devList, true));
      if (json_last_error() == JSON_ERROR_NONE) {
        foreach($devList as $name => $value) {
          if ($name === 'classe') {
            // pas de traitement sur ces données
          }else if ($name === 'OPTARIF'){
            $this->checkCmdOk('teleinfo','string', 'Option tarifaire',trim($name), '<i class="fas fa-info-circle"></i>',"");
            $this->checkAndUpdateCmd($name, str_replace('..','',$value) );
          }else if ($name === 'PTEC'){
            $this->checkCmdOk('teleinfo','string', 'Période Tarifaire en cours',trim($name), '<i class="fas fa-random"></i>',"");
            $this->checkAndUpdateCmd($name, str_replace('..','',$value) );
          } else {
            if ($value != 0){
              $this->checkCmdOk('teleinfo','numeric', str_replace('conso','index',$name),trim($name), '<i class="fas fa-bolt"></i>',"kWh");
              if ($value > 100){
                $this->checkAndUpdateCmd($name, round($value/1000,0));
              }else{
                $this->checkAndUpdateCmd($name,$value );
              }


            }
          }
        }
      }
    }
    $this->refreshWidget();
  }

  public function getConsoAll_heure() {
    foreach (eqLogic::byType('Eco_legrand',true) as $Eco_legrand) {
      $Eco_legrand->getConsoElec_heure();
    }
  }
  public function getInfos() {
    foreach (eqLogic::byType('Eco_legrand',true) as $Eco_legrand) {
      $Eco_legrand->getInformations();
      $Eco_legrand->getData();
    }
  }

  public function getConsoAll_jour() {
    foreach (eqLogic::byType('Eco_legrand',true) as $Eco_legrand) {
      $Eco_legrand->getConsoElec_jour();
    }
  }

  public function getConsoElec_heure() {
    if(strpos($this->getConfiguration('addr', ''), "https://")===0 || strpos($this->getConfiguration('addr', ''), "http://")===0){
      $URL = $this->getConfiguration('addr', '');
    }else {
      $URL = "http://" .$this->getConfiguration('addr', '');
    }
    $devAddr = $URL . '/LOG2.csv';

    $devResult = fopen($devAddr, "r");
    log::add('Eco_legrand', 'info', 'getConsoElec_heure ' . $devAddr);
    /*
      jour	mois	annee	heure	minute	energie_tele_info	prix_tele_info	energie_circuit1	prix_circuit1	energie_cirucit2	prix_circuit2	energie_circuit3	prix_circuit3	energie_circuit4	prix_circuit4	energie_circuit5	prix_circuit5	volume_entree1	volume_entree2	tarif	energie_entree1	energie_entree2	prix_entree1	prix_entree2
      17	8	15	20	2	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0	0.000	0.000	0.000	0.000
      17	8	15	21	2	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	11	0.000	0.000	0.000	0.000
      */
    if ($devResult === false) {
      log::add('Eco_legrand', 'error', 'problème de connexion ' . $devAddr);
    } else  {


      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_totale_heure');
      $valeur_precedente_conso_totale=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit1_heure');
      $valeur_precedente_conso_circuit1=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit2_heure');
      $valeur_precedente_conso_circuit2=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit3_heure');
      $valeur_precedente_conso_circuit3=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit4_heure');
      $valeur_precedente_conso_circuit4=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit5_heure');
      $valeur_precedente_conso_circuit5=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_autre_heure');
      $valeur_precedente_conso_autre=$Eco_legrandCmd->getConfiguration('lastvalue');


      while ( ($data = fgetcsv($devResult,1000,";") ) !== FALSE ) {

        $date = $data[2] . '-' . $data[1] . '-' .$data[0] .' ' . $data[3] .':00:00';
        $valeur=round($data[5],2);
        if($valeur < $valeur_precedente_conso_totale){
          $this->add_value('conso_totale_heure',round($valeur - $valeur_precedente_conso_totale,2),$date,"heure");
        }
        $valeur_precedente_conso_totale = $valeur;

        $valeur=round($data[7],2);
        if($valeur < $valeur_precedente_conso_circuit1){
          $this->add_value('conso_circuit1_heure',round($valeur- $valeur_precedente_conso_circuit1,2),$date,"heure");
        }
        $valeur_precedente_conso_circuit1 = $valeur;

        $valeur=round($data[9],2);
        if ($valeur < $valeur_precedente_conso_circuit2){
          $this->add_value('conso_circuit2_heure',round($valeur - $valeur_precedente_conso_circuit2,2),$date,"heure");
        }
        $valeur_precedente_conso_circuit2 = $valeur;

        $valeur=round($data[11],2);
        if($valeur < $valeur_precedente_conso_circuit3){
          $this->add_value('conso_circuit3_heure',round($valeur - $valeur_precedente_conso_circuit3,2),$date,"heure");
        }
        $valeur_precedente_conso_circuit3 = $valeur;

        $valeur=round($data[13],2);
        if($valeur < $valeur_precedente_conso_circuit4){
          $this->add_value('conso_circuit4_heure',round($valeur - $valeur_precedente_conso_circuit4,2),$date,"heure");
        }
        $valeur_precedente_conso_circuit4 = $valeur;

        $valeur=round($data[15],2);
        if($valeur < $valeur_precedente_conso_circuit5){
          $this->add_value('conso_circuit5_heure',round($valeur - $valeur_precedente_conso_circuit5,2),$date,"heure");
        }
        $valeur_precedente_conso_circuit5 = $valeur;

        $valeur=round($data[5]-$data[7]- $data[9]-$data[11]-$data[13]-$data[15],2);
        if($valeur < $valeur_precedente_conso_autre){
          $this->add_value('conso_autre_heure',round($valeur - $valeur_precedente_conso_autre,2),$date,"heure");
        }
        $valeur_precedente_conso_autre = $valeur;

      }
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_totale_heure');
      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_totale) {
        $Eco_legrandCmd->setConfiguration('lastvalue', $valeur_precedente_conso_totale );
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit1) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit1_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit1);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit2) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit2_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit2);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit3) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit3_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit3);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit4) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit4_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit4);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit5) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit5_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit5);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_autre) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_totale_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_autre);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_totale) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_autre_heure');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_totale);
        $Eco_legrandCmd->save();
      }

    }
  }
  public function getConsoElec_jour() {
    if(strpos($this->getConfiguration('addr', ''), "https://")===0 || strpos($this->getConfiguration('addr', ''), "http://")===0){
      $URL = $this->getConfiguration('addr', '');
    }else {
      $URL = "http://" .$this->getConfiguration('addr', '');
    }
    $devAddr = $URL . '/LOG1.csv';

    $devResult = fopen($devAddr, "r");
    if ($devResult === false) {
      log::add('Eco_legrand', 'error', 'problème de connexion ' . $devAddr);
    } else  {
      log::add('Eco_legrand', 'info', 'getConsoElec_jour ' . $devAddr);

      $valeur_precedente_conso_totale=0;
      $valeur_precedente_conso_circuit1=0;
      $valeur_precedente_conso_circuit2=0;
      $valeur_precedente_conso_circuit3=0;
      $valeur_precedente_conso_circuit4=0;
      $valeur_precedente_conso_circuit5=0;
      $valeur_precedente_conso_autre=0;
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_totale_jour');
      $valeur_precedente_conso_totale=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit1_jour');
      $valeur_precedente_conso_circuit1=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit2_jour');
      $valeur_precedente_conso_circuit2=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit3_jour');
      $valeur_precedente_conso_circuit3=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit4_jour');
      $valeur_precedente_conso_circuit4=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit5_jour');
      $valeur_precedente_conso_circuit5=$Eco_legrandCmd->getConfiguration('lastvalue');
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_autre_jour');
      $valeur_precedente_conso_autre=$Eco_legrandCmd->getConfiguration('lastvalue');

      while ( ($data = fgetcsv($devResult,1000,";") ) !== FALSE ) {
        //log::add('Eco_legrand', 'debug',$data);
        $date = $data[2] . '-' . $data[1] . '-' .$data[0] .' 23:59:59';
        $valeur=round($data[5],2);
        if ($valeur < $valeur_precedente_conso_totale){
          $this->add_value('conso_totale_jour',round($valeur - $valeur_precedente_conso_totale,2),$date,"jour");
        }
        $valeur_precedente_conso_totale=$valeur;

        $valeur=round($data[7],2);
        if ($valeur < $valeur_precedente_conso_circuit1){
          $this->add_value('conso_circuit1_jour',round($valeur- $valeur_precedente_conso_circuit1,2),$date,"jour");
        }
        $valeur_precedente_conso_circuit1 = $valeur;

        $valeur=round($data[9],2);

        if ($valeur < $valeur_precedente_conso_circuit2){
          $this->add_value('conso_circuit2_jour',round($valeur - $valeur_precedente_conso_circuit2,2),$date,"jour");
        }
        $valeur_precedente_conso_circuit2 = $valeur;


        $valeur=round($data[11],2);
        if ($valeur < $valeur_precedente_conso_circuit3){
          $this->add_value('conso_circuit3_jour',round($valeur - $valeur_precedente_conso_circuit3,2),$date,"jour");
        }
        $valeur_precedente_conso_circuit3 = $valeur;


        $valeur=round($data[13],2);
        if ($valeur < $valeur_precedente_conso_circuit4){
          $this->add_value('conso_circuit4_jour',round($valeur - $valeur_precedente_conso_circuit4,2),$date,"jour");
        }
        $valeur_precedente_conso_circuit4 =$valeur;


        $valeur=round($data[15],2);
        if ($valeur < $valeur_precedente_conso_circuit5){
          $this->add_value('conso_circuit5_jour',round($valeur - $valeur_precedente_conso_circuit5,2),$date,"jour");
        }
        $valeur_precedente_conso_circuit5 = $valeur;


        $valeur=round($data[5]-$data[7]- $data[9]-$data[11]-$data[13]-$data[15],2);
        if ($valeur < $valeur_precedente_conso_autre){
          $this->add_value('conso_autre_jour',round($valeur - $valeur_precedente_conso_autre,2),$date,"jour");
        }
        $valeur_precedente_conso_autre = $valeur;


      }
      $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_totale_jour');
      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_totale) {
        $Eco_legrandCmd->setConfiguration('lastvalue', $valeur_precedente_conso_totale );
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit1) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit1_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit1);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit2) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit2_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit2);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit3) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit3_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit3);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit4) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit4_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit4);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit5) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_circuit5_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_circuit5);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_autre) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_totale_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_autre);
        $Eco_legrandCmd->save();
      }

      if( $Eco_legrandCmd->getConfiguration('lastvalue') != $valeur_precedente_conso_totale) {
        $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),'conso_autre_jour');
        $Eco_legrandCmd->setConfiguration('lastvalue',$valeur_precedente_conso_totale);
        $Eco_legrandCmd->save();
      }
    }
  }
  public function add_value($cmdLogicalId,$value,$datetime,$type) {
    $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),$cmdLogicalId);

    $historys=[];
    if (!is_object($Eco_legrandCmd)){
      return;
    }
    //$Eco_legrandCmd->emptyHistory();
    //return;
    $historys=$Eco_legrandCmd->getHistory();

    if(count($historys) !=0){

      $last_history_datetime=$historys[count($historys)-1]->getDatetime();
      //$last_value=$history[count($history)-1]->getValue();
      //log::add('Eco_legrand', 'debug', '$last_value valeur ' . $last_value);
    }else{
      $last_history_datetime="1900-01-01 00:00:00";
    } 

    if($type == "heure"){
      $datetime_compare=strtotime(date('y-n-j G:00:00'));
    }else{
      $Date = date('y-n-j 00:00:00');
      $datetime_compare= strtotime(date('Y-m-d 23:59:59', strtotime($Date. ' - 1 days')));
      //$datetime_compare=strtotime(date('y-n-j 00:00:00 - 1 days') );
      //log::add('Eco_legrand', 'debug', strtotime($datetime). "*****".$datetime_compare);
    }

    if (strtotime($datetime) == $datetime_compare ){
      $collectDate = $Eco_legrandCmd->getCache(array('collectDate', 'valueDate', 'value'))['collectDate'];
      if(strtotime($collectDate) != strtotime($datetime)){
        log::add('Eco_legrand', 'debug', 'ajout valeur ' . $Eco_legrandCmd->getName().":".$value);
        $Eco_legrandCmd->addHistoryValue($value, $datetime);
        $this->checkAndUpdateCmd($cmdLogicalId,$value,$datetime);
      }
    }else{
      $existe = false;
      foreach ($historys as $history){
        if (strtotime($history->getDatetime()) == strtotime($datetime)){
          $existe = true;
          break;
        }
      }
      if(!$existe){
        log::add('Eco_legrand', 'debug', 'ajout historique ' .  $Eco_legrandCmd->getName()).":".$value ;
        $Eco_legrandCmd->addHistoryValue($value, $datetime);
      }
    }

  }

}

class Eco_legrandCmd extends cmd {

}

?>