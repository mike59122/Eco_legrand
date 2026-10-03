<?php
require_once dirname(__FILE__) . '/../../core/php/Eco_legrand.inc.php';
class Eco_legrand extends eqLogic {

 
  public static function add_log($level = 'debug',$Log){
    if (is_array($Log)) $Log = json_encode($Log);
      $ligne = debug_backtrace(false, 2)[0]['line'];
    if (isset(debug_backtrace(false, 2)[1]['function'])) {
      $function_name = debug_backtrace(false, 2)[1]['function'];
      $msg =  $function_name .' (' . $ligne . '): '.$Log;
  
    }else{
      $msg =  '(' . $ligne . '): '.$Log;
    }
    log::add('Eco_legrand' , $level,$msg);
  
  }

  public function preUpdate() {
    if ($this->getConfiguration('addr') == '') {
      throw new Exception(__('L\'adresse ne peut être vide',__FILE__));
    }
   
  }
  public function preRemove() {
       
		$sql="DELETE FROM Eco_legrand_teleinfo where eqLogicID = " . $this->getId();
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
    $sql="DELETE FROM Eco_legrand_prix where eqLogicID = " . $this->getId();
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
    $sql="DELETE FROM Eco_legrand_jour where eqLogicID = " . $this->getId();
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	}
   public function checkCmdOk($_type_data, $_subtype, $_name,$_logical_id, $_template,$_unite) {
    $Eco_legrandCmd = Eco_legrandCmd::byEqLogicIdAndLogicalId($this->getId(),$_logical_id);
    if (!is_object($Eco_legrandCmd)) {
      $Eco_legrandCmd = new Eco_legrandCmd();
      $Eco_legrandCmd->setName($_name);
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
    if($Eco_legrandCmd->getName() != $_name){
      $Eco_legrandCmd->setConfiguration('type', $_type_data);
      $Eco_legrandCmd->setName($_name);
      $Eco_legrandCmd->save();
  }
  }
  /*function Synchro_datas($eqLogic){
    if(strpos($eqLogic->getConfiguration('addr', ''), "https://")===0 || strpos($eqLogic->getConfiguration('addr', ''), "http://")===0){
      $URL = $eqLogic->getConfiguration('addr', '');
    }else {
      $URL = "http://" .$eqLogic->getConfiguration('addr', '');
    }
    
  }*/
  public static function get_all_datas($eqLogic){
    if(strpos($eqLogic->getConfiguration('addr', ''), "https://")===0 || strpos($eqLogic->getConfiguration('addr', ''), "http://")===0){
      $URL = $eqLogic->getConfiguration('addr', '');
    }else {
      $URL = "http://" .$eqLogic->getConfiguration('addr', '');
    }
  
    $nom_fichier = "ti.json";
    $jour_heure = "jour";
    $conso_hc = 0;
    $conso_hp = 0;
    $ppap=0;
    $date=date_create(date("Y-m-d H:i:00"));
    $date=date("Y-m-d H:i:00");
    
    $Eco_legrand_teleinfo = new Eco_legrand_teleinfo();
    $Eco_legrand_teleinfo->set_value('timestamp',date_create($date)->getTimestamp());
    $Eco_legrand_teleinfo->set_value('date',date_format(date_create($date),"Y-m-d"));
    $Eco_legrand_teleinfo->set_value('heure',date_format(date_create($date),"H:i:00"));
    $Eco_legrand_teleinfo->set_value('eqLogicId',$eqLogic->getId());
    $Eco_legrand_teleinfo->set_value("temperature",jeedom::evaluateExpression($eqLogic->getConfiguration('température extérieure',0)));
    redo:
    $devAddr = $URL . '/' . $nom_fichier; 
    try {
    
      if (substr($nom_fichier , -4) == 'json'){
        $request_http = new com_http($devAddr);
        $devResult = $request_http->exec(30);
        
        
        if ($devResult === false) {
        
        } else {
          $devResbis = utf8_encode($devResult);
          $corrected = preg_replace('/\s+/', '', $devResbis);
          $corrected = preg_replace('/\:0,/', ': 0,', $corrected);
          $corrected = preg_replace('/\:[0]+/', ":", $corrected);
          $devList = json_decode($devResbis, true);
          $i=1;
          $conso_hp=0;
          $conso_hc=0;
          foreach($devList as $name => $value) {
            if ($name === 'classe') {
            }else if ($name === 'OPTARIF'){
              $eqLogic->checkCmdOk('teleinfo','string', 'Option tarifaire',trim($name), '<i class="fas fa-info-circle"></i>',"");
              $eqLogic->checkAndUpdateCmd($name, str_replace('..','',$value),$date);
            }else if ($name === 'PTEC'){
              $eqLogic->checkCmdOk('teleinfo','string', 'Période Tarifaire en cours',trim($name), '<i class="fas fa-random"></i>',"");
              $Eco_legrand_teleinfo->set_value("ptec",str_replace('..','',$value));
              $eqLogic->checkAndUpdateCmd($name, str_replace('..','',$value),$date);
            }else if ($name === 'ISOUC'){
              $eqLogic->checkCmdOk('teleinfo','numeric', 'ISOUC',trim($name), '<i class="fas fa-info-circle"></i>',"");
              $eqLogic->checkAndUpdateCmd($name,$value,$date);
            }else {
              if ($nom_fichier  == "ti.json"){
                if ($value != 0){
                  
                  $eqLogic->checkCmdOk('teleinfo','numeric', trim(str_replace('conso','index',$name)),trim(str_replace('conso','index',$name)), '<i class="fas fa-bolt"></i>',"Wh");
                  $eqLogic->checkCmdOk('teleinfo','numeric', ucfirst( $name),$name, '<i class="fas fa-bolt"></i>',"W");
                  
                  if (str_replace('conso','index',$name) == "index_hc"){
                   
                    $cmd_index_hc=Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),"index_hc");
                    if (is_object($cmd_index_hc)){
                      $conso_hc = ($value - $cmd_index_hc->execCmd());
                      if ($cmd_index_hc->execCmd() <= $value){
                        $eqLogic->checkAndUpdateCmd('conso_hc', $conso_hc*60,$date);
                        $eqLogic->checkAndUpdateCmd(trim(str_replace('conso','index',$name)), $value,$date);
                        $Eco_legrand_teleinfo->set_value(trim(str_replace('conso','index',$name)),$value);
                        $ppap += $conso_hc*60;
                      }else{
                       
                        $Eco_legrand_teleinfo->set_value(trim(str_replace('conso','index',$name)),$cmd_index_hc->execCmd());
                        $ppap += 0;
                      }
                    }
                  }
                   
                  if (str_replace('conso','index',$name) == "index_hp"){
                    
                    $cmd_index_hp=Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),"index_hp");
                    if (is_object($cmd_index_hp)){
                      self::add_log("info",'value: ' . $value);
                      self::add_log("info",'cmd_index_hp: ' . $cmd_index_hp->execCmd());
                      $conso_hp = ($value - $cmd_index_hp->execCmd());
                       self::add_log("info",'conso_hp: ' . $conso_hp);
                      if ($cmd_index_hp->execCmd() <= $value){
                        $eqLogic->checkAndUpdateCmd('conso_hp', $conso_hp*60,$date);
                        $eqLogic->checkAndUpdateCmd(trim(str_replace('conso','index',$name)), $value,$date);
                        $Eco_legrand_teleinfo->set_value(trim(str_replace('conso','index',$name)),$value);
                        $ppap+=$conso_hp*60;
                      }else{
                        $Eco_legrand_teleinfo->set_value(trim(str_replace('conso','index',$name)),$cmd_index_hp->execCmd());
                        $ppap +=0;
                      }                 
                    }
                  }
                  $Eco_legrand_teleinfo->set_value("puissance_totale",$ppap);
                  if ($ppap>0 && $ppap< 999){
                    $Eco_legrand_teleinfo->set_value("int_instant",round($ppap/230),0);
                  }else{
                    $Eco_legrand_teleinfo->set_value("int_instant",0);
                  }
                }
                
              }else{
                $eqLogic->checkCmdOk('inst','numeric', trim($name), 'inst_circuit' . $i , '<i class="fas fa-bolt"></i>',"W");
              
                $eqLogic->checkCmdOk('csv','numeric', "Consommation " .trim($name) . " par heure","conso_circuit".$i ."_heure",'<i class="fas fa-bolt"></i>',"kW");
              
                $eqLogic->checkCmdOk('csv','numeric', "Consommation totale par heure","conso_totale_heure",'<i class="fas fa-bolt"></i>',"kW");
               
                $eqLogic->checkCmdOk('csv','numeric', "Consommation Autre par heure" ,"conso_autre_heure",'<i class="fas fa-bolt"></i>',"kW");
                
                $eqLogic->checkCmdOk('csv','numeric', "Consommation " .trim($name) . " journalière","conso_circuit".$i ."_jour",'<i class="fas fa-bolt"></i>',"kW");
              
                $eqLogic->checkCmdOk('csv','numeric', "Consommation totale journalière",'conso_totale_jour','<i class="fas fa-bolt"></i>',"kW");
               
                $eqLogic->checkCmdOk('csv','numeric', "Consommation Autre journalière" ,'conso_autre_jour','<i class="fas fa-bolt"></i>',"kW");
              
                $eqLogic->checkAndUpdateCmd('inst_circuit' . $i, $value,$date);
                $Eco_legrand_teleinfo->set_value('puissance_circuit' . $i,$value);
              
                $i=$i+1;

              }
              
            }
          }
          
          
        }
      
        
      }elseif(substr($nom_fichier , -3) == 'csv' || substr($nom_fichier , -3) == 'old'){
        $devResult = fopen($devAddr, "r");
        //self::add_log( 'debug','nom_fichier: ' . $devAddr);
      
        /*
        jour	mois	annee	heure	minute	energie_tele_info	prix_tele_info	energie_circuit1	prix_circuit1	energie_cirucit2	prix_circuit2	energie_circuit3	prix_circuit3	energie_circuit4	prix_circuit4	energie_circuit5	prix_circuit5	volume_entree1	volume_entree2	tarif	energie_entree1	energie_entree2	prix_entree1	prix_entree2
        17	   8	  15	   20	   2	     0.000	         0.000	          0.000	             0.000	      0.000	            0.000	         0.000	           0.000	       0.000	          0.000	        0.000	              0.000	          0.000	            0.000	     0	   0.000	          0.000	         0.000	        0.000
        17	8	15	21	2	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	0.000	11	0.000	0.000	0.000	0.000
        */

        if ($devResult === false) {
         
        } else {
          $Eco_legrandCmd_conso_totale = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_totale_'.$jour_heure);
          $valeur_precedente_conso_totale=$Eco_legrandCmd_conso_totale->getConfiguration('lastvalue');
          $Eco_legrandCmd_conso_circuit1 = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_circuit1_'.$jour_heure);
          $valeur_precedente_conso_circuit1=$Eco_legrandCmd_conso_circuit1->getConfiguration('lastvalue');
          $Eco_legrandCmd_conso_circuit2 = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_circuit2_'.$jour_heure);
          $valeur_precedente_conso_circuit2=$Eco_legrandCmd_conso_circuit2->getConfiguration('lastvalue');
          $Eco_legrandCmd_conso_circuit3 = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_circuit3_'.$jour_heure);
          $valeur_precedente_conso_circuit3=$Eco_legrandCmd_conso_circuit3->getConfiguration('lastvalue');
          $Eco_legrandCmd_conso_circuit4 = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_circuit4_'.$jour_heure);
          $valeur_precedente_conso_circuit4=$Eco_legrandCmd_conso_circuit4->getConfiguration('lastvalue');
          $Eco_legrandCmd_conso_circuit5 = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_circuit5_'.$jour_heure);
          $valeur_precedente_conso_circuit5=$Eco_legrandCmd_conso_circuit5->getConfiguration('lastvalue');
          $Eco_legrandCmd_conso_autre = Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogic->getId(),'conso_autre_'.$jour_heure);
          $valeur_precedente_conso_autre=$Eco_legrandCmd_conso_autre->getConfiguration('lastvalue');
          while(!feof($devResult)){
            $line = fgets($devResult);
            $data= explode(";",$line);
            $jour = $data[0];
            $mois = $data[1];
            $annee = $data[2];
            $heure = $data[3];
            
            if(substr($nom_fichier,0,4) == 'log1'){
              $date = date('Y-m-d H:i:s', strtotime($annee . '-' . $mois . '-' . $jour .' ' . $heure .':00:00'. ' + 1 hours'));
            }else{
              $date =  $annee . '-' . $mois . '-' . $jour .' ' . $heure .':00:00';
            }
                        
            $valeur=round($data[5],2);
            if($valeur > $valeur_precedente_conso_totale){
              if ($valeur_precedente_conso_totale == 0 ){
                $valeur_precedente_conso_totale = $valeur;
              }
              $valeur=round($data[5],2);
              //self::add_log('debug', 'ajout valeur ' . $Eco_legrandCmd_conso_totale->getName() . ":" . $date . "=>". round($valeur - $valeur_precedente_conso_totale,2));
              $eqLogic->checkAndUpdateCmd('conso_totale_'.$jour_heure,round($valeur - $valeur_precedente_conso_totale,2),$date);
              $Eco_legrand_teleinfo->set_value('index_total_circuits' ,$valeur*1000);
              $valeur_precedente_conso_totale = $valeur;
            }else{
              $Eco_legrand_teleinfo->set_value('index_total_circuits' ,$valeur_precedente_conso_totale*1000);
            }
           
          










            $valeur=round($data[7],2);
            if($valeur > $valeur_precedente_conso_circuit1){
              if ($valeur_precedente_conso_circuit1 == 0 ){
                $valeur_precedente_conso_circuit1 = $valeur;
              }
              //self::add_log('debug', 'ajout valeur ' . $Eco_legrandCmd_conso_circuit1->getName().":" . $date . "=>". round($valeur- $valeur_precedente_conso_circuit1,2));
              $eqLogic->checkAndUpdateCmd('conso_circuit1_'.$jour_heure,round($valeur- $valeur_precedente_conso_circuit1,2),$date);
              $Eco_legrand_teleinfo->set_value('index_circuit1' ,$valeur*1000);
              $valeur_precedente_conso_circuit1 = $valeur;
            }else{
              $Eco_legrand_teleinfo->set_value('index_circuit1' ,$valeur_precedente_conso_circuit1*1000);
            }
          

            $valeur=round($data[9],2);
            if ($valeur > $valeur_precedente_conso_circuit2){
              if ($valeur_precedente_conso_circuit2 == 0 ){
                $valeur_precedente_conso_circuit2 = $valeur;
              }
              //self::add_log( 'debug', 'ajout valeur ' . $Eco_legrandCmd_conso_circuit2->getName().":" . $date . "=>". round($valeur - $valeur_precedente_conso_circuit2,2));
              $eqLogic->checkAndUpdateCmd('conso_circuit2_'.$jour_heure,round($valeur - $valeur_precedente_conso_circuit2,2),$date);
              $Eco_legrand_teleinfo->set_value('index_circuit2' ,$valeur*1000);
              $valeur_precedente_conso_circuit2 = $valeur;
            }else{
              $Eco_legrand_teleinfo->set_value('index_circuit2' ,$valeur_precedente_conso_circuit2*1000);
            }
           

            $valeur=round($data[11],2);
            if($valeur > $valeur_precedente_conso_circuit3){
              if ($valeur_precedente_conso_circuit3 == 0 ){
                $valeur_precedente_conso_circuit3 = $valeur;
              }
              //self::add_log( 'debug', 'ajout valeur ' . $Eco_legrandCmd_conso_circuit3->getName().":" . $date . "=>". round($valeur - $valeur_precedente_conso_circuit3,2));
              $eqLogic->checkAndUpdateCmd('conso_circuit3_'.$jour_heure,round($valeur - $valeur_precedente_conso_circuit3,2),$date);
              $Eco_legrand_teleinfo->set_value('index_circuit3' ,$valeur*1000);
              $valeur_precedente_conso_circuit3 = $valeur;
            }else{
              $Eco_legrand_teleinfo->set_value('index_circuit3' ,$valeur_precedente_conso_circuit3*1000);
            }

            $valeur=round($data[13],2);
            if($valeur > $valeur_precedente_conso_circuit4){
              if ($valeur_precedente_conso_circuit4 == 0 ){
                $valeur_precedente_conso_circuit4 = $valeur;
              }
              //self::add_log( 'debug', 'ajout valeur ' . $Eco_legrandCmd_conso_circuit4->getName().":" . $date . "=>". round($valeur - $valeur_precedente_conso_circuit4,2));
              $eqLogic->checkAndUpdateCmd('conso_circuit4_'.$jour_heure,round($valeur - $valeur_precedente_conso_circuit4,2),$date);
              $Eco_legrand_teleinfo->set_value('index_circuit4' ,$valeur*1000);
              $valeur_precedente_conso_circuit4 = $valeur;
            }else{
              $Eco_legrand_teleinfo->set_value('index_circuit4' ,$valeur_precedente_conso_circuit4*1000);
            }

            $valeur=round($data[15],2);
            if($valeur > $valeur_precedente_conso_circuit5){
              if ($valeur_precedente_conso_circuit5 == 0 ){
                $valeur_precedente_conso_circuit5 = $valeur;
              }
              self::add_log( 'debug', 'ajout valeur ' . $Eco_legrandCmd_conso_circuit5->getName().":" . $date . "=>". round($valeur - $valeur_precedente_conso_circuit5,2));
              $eqLogic->checkAndUpdateCmd('conso_circuit5_'.$jour_heure,round($valeur - $valeur_precedente_conso_circuit5,2),$date);
              $Eco_legrand_teleinfo->set_value('index_circuit5' ,$valeur*1000);
              self::add_log( 'debug', 'ajout valeur index_circuit5: ' . $date . "=>". $valeur*1000);
            
              $valeur_precedente_conso_circuit5 = $valeur;
            }else{
              $Eco_legrand_teleinfo->set_value('index_circuit5' ,$valeur_precedente_conso_circuit5*1000);
        
              
              //self::add_log( 'debug', '2ajout valeur index_circuit5: ' . $date . "=>". $valeur*1000 . 'ancienne valeur: ' . $valeur_precedente_conso_circuit5*1000);
            }
            

            $valeur=round($data[5]-$data[7]- $data[9]-$data[11]-$data[13]-$data[15],2);
            if($jour_heure == "heure"){
              //self::add_log( 'debug', 'valeur ' . $Eco_legrandCmd_conso_autre->getName().":" . $date . "=>". round($valeur - $valeur_precedente_conso_autre,2));
             // self::add_log( 'debug', 'valeur ' . $Eco_legrandCmd_conso_autre->getName().":" . $date . "=>".$valeur );
             // self::add_log( 'debug', 'valeur ' . $Eco_legrandCmd_conso_autre->getName().":" . $date . "=>".  $valeur_precedente_conso_autre);
   
            }
            if($valeur > $valeur_precedente_conso_autre){
              if ($valeur_precedente_conso_autre == 0 ){
                $valeur_precedente_conso_autre = $valeur;
              }
              //self::add_log( 'debug', 'ajout valeur ' . $Eco_legrandCmd_conso_autre->getName().":" . $date . "=>". round($valeur - $valeur_precedente_conso_autre,2));
              $eqLogic->checkAndUpdateCmd('conso_autre_'.$jour_heure,round($valeur - $valeur_precedente_conso_autre,2),$date);
              $valeur_precedente_conso_autre = $valeur;
            }
          }
      
          if($Eco_legrandCmd_conso_totale->getConfiguration('lastvalue') != $valeur_precedente_conso_totale) {
          $Eco_legrandCmd_conso_totale->setConfiguration('lastvalue', $valeur_precedente_conso_totale );
          $Eco_legrandCmd_conso_totale->save();
          }

          if($Eco_legrandCmd_conso_circuit1->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit1) {
          $Eco_legrandCmd_conso_circuit1->setConfiguration('lastvalue',$valeur_precedente_conso_circuit1);
          $Eco_legrandCmd_conso_circuit1->save();
          }

          if($Eco_legrandCmd_conso_circuit2->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit2) {
          $Eco_legrandCmd_conso_circuit2->setConfiguration('lastvalue',$valeur_precedente_conso_circuit2);
          $Eco_legrandCmd_conso_circuit2->save();
          }

          if($Eco_legrandCmd_conso_circuit3->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit3) {
          $Eco_legrandCmd_conso_circuit3->setConfiguration('lastvalue',$valeur_precedente_conso_circuit3);
          $Eco_legrandCmd_conso_circuit3->save();
          }

          if($Eco_legrandCmd_conso_circuit4->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit4) {
          $Eco_legrandCmd_conso_circuit4->setConfiguration('lastvalue',$valeur_precedente_conso_circuit4);
          $Eco_legrandCmd_conso_circuit4->save();
          }

          if($Eco_legrandCmd_conso_circuit5->getConfiguration('lastvalue') != $valeur_precedente_conso_circuit5) {
          $Eco_legrandCmd_conso_circuit5->setConfiguration('lastvalue',$valeur_precedente_conso_circuit5);
          $Eco_legrandCmd_conso_circuit5->save();
          }

          if($Eco_legrandCmd_conso_autre->getConfiguration('lastvalue') != $valeur_precedente_conso_autre) {
          $Eco_legrandCmd_conso_autre->setConfiguration('lastvalue',$valeur_precedente_conso_autre);
          $Eco_legrandCmd_conso_autre->save();
          }

        }
       
        
        fclose($devResult);

      }
    
      if ($nom_fichier  == "ti.json"){
        $nom_fichier  = "instant.json";
        goto redo;
      }
      if ($nom_fichier  == "instant.json"){
        $jour_heure = "jour";
        $nom_fichier  = "log1.old";
        goto redo;
      }
      if ($nom_fichier  == "log1.old"){
        $jour_heure = "jour";
        $nom_fichier  = "log1.csv";
        goto redo;
      }
      if ($nom_fichier  == "log1.csv"){
        $jour_heure = "heure";
        $nom_fichier  = "log2.old";
        goto redo;
      }
      if ($nom_fichier  == "log2.old"){
        $jour_heure = "heure";
        $nom_fichier  = "log2.csv";
        goto redo;
      }
    
      $Eco_legrand_teleinfo->save();
      
      $eqLogic->setConfiguration("Importation en cours",0);
      $eqLogic->save(true);
      $eqLogic->refreshWidget();
    } catch (Exception $e) { 
      self::add_log("debug","erreur get_all_datas");
      self::add_log("error",$e->getMessage());
      self::add_log("error", 
      $Eco_legrand_teleinfo->get_value('timestamp').
      $Eco_legrand_teleinfo->get_value('date').
      $Eco_legrand_teleinfo->get_value('heure').
      $Eco_legrand_teleinfo->get_value('index_hp').
      $Eco_legrand_teleinfo->get_value("index_hc").
      $Eco_legrand_teleinfo->get_value("ptec").
      $Eco_legrand_teleinfo->get_value("int_instant").
      $Eco_legrand_teleinfo->get_value("puissance_totale")
    );
    }
  }
	public static function cron_minute(){
		Eco_legrand_teleinfo::crontabJour();
	}

	public static function cron_jour(){
		Eco_legrand_teleinfo::crontabAllJour(true);
	}
  public static function getDateAbo($eqLogicId){

    /*ABONNEMENT FACTURE*/
    $date = new DateTime();
    $eqLogic=eqLogic::byId($eqLogicId);
    $c=$eqLogic->getConfiguration("date_abo","01/01");
    $d_annee=explode("/",$c)[1] . "-" .explode("/",$c)[0];
    $d_mois=explode("/",$c)[0];
    $date_debut_mois=$date->format('Y-m-' . $d_mois);
    $date_fin_mois = date("Y-m-d", strtotime('+1 month -1 day', strtotime($date_debut_mois)));
    
    $date_debut_annee = $date->format('Y-' . $d_annee);
    //$date_debut_graph = date("Y-m-d", strtotime('-11 month', strtotime($date->format('Y-m-' . '01'))));
    $date_fin_annee = date("Y-m-d", strtotime('+1 year -1 day', strtotime($date_debut_annee)));
    $date_maintenant = date("Y").'-'.date("m").'-'.date("j");

    if(strtotime($date_debut_annee) > strtotime($date_maintenant)){

      $date_debut_annee = date("Y-m-d", strtotime('-1 year', strtotime($date->format('Y-' . $d_annee))));
      $date_fin_annee = date("Y-m-d", strtotime('+1 year -1 day', strtotime($date_debut_annee)));
    }
    if(strtotime($date_debut_mois) > strtotime($date_maintenant)){

      $date_debut_mois = date("Y-m-d", strtotime('-1 month', strtotime($date->format('Y-m-' . $d_mois))));
      $date_fin_mois = date("Y-m-d", strtotime('+1 month -1 day', strtotime($date_debut_mois)));
    }
   	//$date_debut_old = date("Y-m-d", strtotime('-1 year', strtotime($date->format($date_debut))));
	  //$date_fin_old = date("Y-m-d", strtotime('-1 year', strtotime($date->format($date_fin))));


	
    return  array(

      'date_debut_fact' => $date_debut_annee, /*debut de l'abonnement (onglet outils)*/
      'date_fin_fact' => $date_fin_annee,/*fin de l'abonnement (onglet outils)*/
      'date_debut_mois' => $date_debut_mois, /*debut de l'abonnement (onglet outils)*/
      'date_fin_mois' => $date_fin_mois,/*fin de l'abonnement (onglet outils)*/
      //'date_debut_graph' => $date_debut_graph,
      //'date_debut_fact_old' => $date_debut_old, /*debut de l'abonnement prevision année -1 (onglet outils)*/
      //'date_fin_fact_old' => $date_fin_old/*fin de l'abonnement prevision année -1  (onglet outils)*/

    );

	}

  public static function get_type_abo($eqLogicId){
 
    $cmd_type_abo=Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogicId,"OPTARIF");
    if (is_object($cmd_type_abo)){
      $type_abo=$cmd_type_abo->execCmd();
    }
		

		return $type_abo;
	}
  
  public static function get_intensite_max($eqLogicId){
    
    $cmd_ISOUC_=Eco_legrandCmd::byEqLogicIdAndLogicalId($eqLogicId,"ISOUC");
    if (is_object($cmd_ISOUC_)){
      $ISOUC=$cmd_ISOUC_->execCmd();
    }
		

		return $ISOUC;
	}
  
  public static function CheckOptionIsValid(){

		$txt = '';
		$sql = "SELECT COUNT(*) AS nb FROM  Eco_legrand_prix WHERE IFNULL(`type`,'') = ''";
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		if ($result['nb'] > 0) {
				$txt .= '<li>Configuration Prix  -> Merci de mettre a jour le type dans le prix ( Editer puis re-sauvegarder les prix ) </li>';
		}
				
		$sql = "SELECT COUNT(*) AS nb FROM  Eco_legrand_jour WHERE IFNULL(eqlogicID,0) = 0 ";
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		if ($result['nb'] > 0) {
			$sql = "delete FROM  Eco_legrand_jour WHERE IFNULL(id_eq,0) = 0 ";
			$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			Eco_legrand_teleinfo::crontabJour(false,2);
				}

		
		
		$sql = "SELECT COUNT(*) AS nb FROM  Eco_legrand_prix WHERE current_date() between date_debut and date_fin ";
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		if ($result['nb'] < 1) {
			$txt .= '<li>Configuration Prix  -> Aucun prix n\'est configuré sur la periode en cours . Merci de renseigner un prix dans onglet Configuration Prix </li>';
		}

		return $txt;
	}

  public static function deamon_info() {
		$return = array();
		$return['log'] = 'Eco_legrand';
		$return['state'] = 'nok';
		$pid_file = jeedom::getTmpFolder('Eco_legrand') . '/Eco_legrand.pid';
		if (file_exists($pid_file)) {
      
			if (@posix_getsid(trim(file_get_contents($pid_file)))) {
				$return['state'] = 'ok';
			} else {
				shell_exec(system::getCmdSudo() . 'rm -rf ' . $pid_file . ' 2>&1 > /dev/null;rm -rf ' . $pid_file . ' 2>&1 > /dev/null;');
			}
		}
		$return['launchable'] = 'ok';
		return $return;
	}
	
	public static function deamon_start() {
    self::install_sql();
		self::deamon_stop();
		self::deamon_info();
		$cmd = 'sudo /usr/bin/php ' . realpath(dirname(__FILE__) . '/../..') . '/resources/Eco_legrand.php start';
		exec($cmd . ' >> ' . log::getPathToLog('Eco_legrand') . ' 2>&1 &');
		return true;
	}
	
	public static function deamon_stop() {
		$pid_file = jeedom::getTmpFolder('Eco_legrand') . '/Eco_legrand.pid';
		if (file_exists($pid_file)) {
			$pid = intval(trim(file_get_contents($pid_file)));
			system::kill($pid);
		}
		system::kill('Eco_legrand.php');
	}

  public static function install_sql(){
 
    $sql  = "CREATE TABLE IF NOT EXISTS `Eco_legrand_jour` (
          `eqlogicID` int(11) NOT NULL DEFAULT 0,
          `timestamp` bigint(10) DEFAULT NULL,
          `date` date NOT NULL,
          `conso_totale_hp` float NOT NULL DEFAULT 0,
          `conso_totale_hc` float NOT NULL DEFAULT 0,
          `conso_circuit1` float NOT NULL DEFAULT 0,
          `conso_circuit2` float NOT NULL DEFAULT 0,
          `conso_circuit3` float NOT NULL DEFAULT 0,
          `conso_circuit4` float NOT NULL DEFAULT 0,
          `conso_circuit5` float NOT NULL DEFAULT 0,
          `conso_autre` float NOT NULL DEFAULT 0,
          `temperature_max` float DEFAULT NULL,
          `temperature_min` float DEFAULT NULL,
          `temperature_moy` float DEFAULT NULL,
      PRIMARY KEY (`date`,`eqlogicID`));";
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
   
    $sql="CREATE TABLE IF NOT EXISTS `Eco_legrand_prix` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `hc` float DEFAULT NULL,
      `hp` float DEFAULT NULL,
      `date_debut` date ,
      `date_fin` date ,
      `type` varchar(255) DEFAULT 'électricité',
      `eqlogicID` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`));";
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
      
      $sql="CREATE TABLE IF NOT EXISTS `Eco_legrand_teleinfo` (
        `timestamp` int(10) NOT NULL DEFAULT 0,
        `date` date NOT NULL,
        `heure` time NOT NULL DEFAULT '00:00:00',
        `index_hp` int(8) NOT NULL DEFAULT 0,
        `index_hc` int(8) NOT NULL DEFAULT 0,
        `ptec` set('HC','HP') NOT NULL,
        `int_instant` tinyint(3) NOT NULL DEFAULT 0,
        `puissance_circuit1` mediumint(5) NOT NULL,
        `puissance_circuit2` mediumint(5) NOT NULL,
        `puissance_circuit3` mediumint(5) NOT NULL,
        `puissance_circuit4` mediumint(5) NOT NULL,
        `puissance_circuit5` mediumint(5) NOT NULL,
        `puissance_totale` mediumint(5) NOT NULL DEFAULT 0,
        `index_total_circuits` int(8) NOT NULL DEFAULT 0,
        `index_circuit1` int(8) NOT NULL DEFAULT 0,
        `index_circuit2` int(8) NOT NULL DEFAULT 0,
        `index_circuit3` int(8) NOT NULL DEFAULT 0,
        `index_circuit4` int(8) NOT NULL DEFAULT 0,
        `index_circuit5` int(8) NOT NULL DEFAULT 0,
        `index_pulse1` int(8) NOT NULL DEFAULT 0,
        `index_pulse2` int(8) NOT NULL DEFAULT 0,
        `eqLogicID` int(11) NOT NULL DEFAULT 0,
        `temperature` float DEFAULT NULL,
        PRIMARY KEY (`timestamp`,`eqlogicID`),
        KEY `date` (`date`),
        KEY `date_heure` (`date`,`heure`),
        KEY `heure` (`heure`),
        KEY `index_hc` (`index_hc`));";
    DB::Prepare($sql,array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }
}

class Eco_legrandCmd extends cmd {
  public function dontRemoveCmd() {
    return true;
  }
}

?>