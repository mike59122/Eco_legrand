<?php

try {
    require_once dirname(__FILE__) . '/../../core/php/Eco_legrand.inc.php';
    include_file('core', 'authentification', 'php');

    
    
    ajax::init();
	if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    if (!function_exists('is_countable')) {
		function is_countable($var) {
			return (is_array($var) || $var instanceof Countable);
		}
	}
	 function get_lundi_dimanche_from_week($week,$year,$format="d/m/Y") {
		//log::add('conso', 'debug', 'week:'.$week. ' year:'.$year));
		$firstDayInYear=date("N",mktime(0,0,0,1,1,$year));
		if ($firstDayInYear<5)
			$shift=-($firstDayInYear-1)*86400;
		else
			$shift=(8-$firstDayInYear)*86400;

		if ($week>1) $weekInSeconds=($week-1)*604800; else $weekInSeconds=0;
		$timestamp=mktime(0,0,0,1,1,$year)+$weekInSeconds+$shift;
		$timestamp_vendredi=mktime(0,0,0,1,5,$year)+$weekInSeconds+$shift;
		$dimanche = $timestamp + (6 * 60 * 60 * 24);

		return  date($format,$timestamp).'<br>'. date($format,$dimanche);

	}

	function checkPrice($value){
		if((float)$value>0)
			return (float)number_format(str_replace(",", ".", $value),2,'.','');
		return 0;
	}

	function NbJours($debut, $fin) {

		$tDeb = explode("-", $debut);
		$tFin = explode("-", $fin);

		$diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
				mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

		return(round($diff / 86400,0)+1);

	}
    
    
    if (init('action') == 'Supp_Prix') {
        // $prix->id = init('id');
         $sql = 'DELETE FROM `Eco_legrand_prix` WHERE `id`='.init('id');
         $row =  DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
         ajax::success();
    }

    if (init('action') == 'Récup_Prix') {
        //$sql = 'SELECT * FROM Eco_legrand_prix ORDER BY  type ASC, date_debut';
        //return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
        $prix = Eco_legrand_prix::byEqLogicId(init('id'));
        foreach ( $prix as $Eco_legrand_prix){
            $Eco_legrand_prix->setDate_debut(Eco_legrand_prix::dateFr($Eco_legrand_prix->getDate_debut()));
            $Eco_legrand_prix->setDate_fin(Eco_legrand_prix::dateFr($Eco_legrand_prix->getDate_fin()));
            }
        ajax::success($prix);
    }
  
 	if (init('action') == 'Ajout_MAJPrix') {
        $prixSave = json_decode(init('event'), true);
        $prix = null;
		
        if ($prixSave['id']!="") {
            $prix = Eco_legrand_prix::byId($prixSave['id']);
			$prix->setId($prixSave['id']);
			$prix->setEqLogicId(init('eqlogic_id'));
        }
        if (!is_object($prix)) {
            $prix = new Eco_legrand_prix();
            $prix->setEqLogicId(init('eqlogic_id'));
        }
        utils::a2o($prix, jeedom::fromHumanReadable($prixSave));
		
        $prix->save();

        ajax::success($prix);
    }
    
    if (init('action') == 'TabConso' && init('eqlogic_id')) {

		$conso_jour = Eco_legrand_teleinfo::get_conso("jour",init('eqlogic_id'));
		$conso_hier = Eco_legrand_teleinfo::get_conso("hier",init('eqlogic_id'));
		$conso_semaine = Eco_legrand_teleinfo::get_conso("semaine",init('eqlogic_id'));
		$conso_mois = Eco_legrand_teleinfo::get_conso("mois",init('eqlogic_id'));
		$conso_mois_préc = Eco_legrand_teleinfo::get_conso("mois_prec",init('eqlogic_id'));
		$conso_année = Eco_legrand_teleinfo::get_conso("annee",init('eqlogic_id'));
		
		
		$tab_data = array();
		$tab_data['conso_jour'] = $conso_jour;
		$tab_data['conso_hier'] = $conso_hier;
		$tab_data['conso_semaine'] = $conso_semaine;
		$tab_data['conso_mois'] = $conso_mois;
		$tab_data['conso_mois_précédent'] = $conso_mois_préc;
		$tab_data['conso_année'] = $conso_année;
		
		$d = Eco_legrand::getDateAbo(init('eqlogic_id'));
		$tab_data['title_année'] = 'Du '.$d['date_debut_fact'].' au '.$d['date_fin_fact'].' : vous pouvez changer cette date dans la configuration de l\'équipement ';
		$tab_data['title_mois'] = 'Du '.$d['date_debut_mois'].' au '.$d['date_fin_mois'].' : vous pouvez changer cette date dans la configuration de l\'équipement ';
		
		 ajax::success($tab_data);

	}
   
  	if (init('action') == 'loadingDash' && init('eqlogic_id')) {
		
		$date_debut = init('date_debut',false);
		log::add('a', 'debug', "loadingDash");
		log::add('a', 'debug', $date_debut);
		$current_trame = Eco_legrand_teleinfo::get_trame_actuelle(false,false,$date_debut,init('eqlogic_id')); /*Retourne les valeur d aujourd hui*/
		$nom_circuit1 = Cmd::byEqLogicIdAndLogicalId(init('eqlogic_id'),"inst_circuit1")->getName();
		$nom_circuit2 = Cmd::byEqLogicIdAndLogicalId(init('eqlogic_id'),"inst_circuit2")->getName();
		$nom_circuit3 = Cmd::byEqLogicIdAndLogicalId(init('eqlogic_id'),"inst_circuit3")->getName();
		$nom_circuit4 = Cmd::byEqLogicIdAndLogicalId(init('eqlogic_id'),"inst_circuit4")->getName();
		$nom_circuit5 = Cmd::byEqLogicIdAndLogicalId(init('eqlogic_id'),"inst_circuit5")->getName();
		$yesterday_trame = array();
		if(!$date_debut ){
			$yesterday_trame = Eco_legrand_teleinfo::get_trame_actuelle(false,true,$date_debut,init('eqlogic_id')); /*Retourne les valeurs d hier*/
		}

		$eqLogics = eqLogic::byId(init('eqlogic_id'));
		
		$tab_data = array();
	
		
		$tab_data['nb_trame'] = count($current_trame);
		$tab_data['trame_du_jour'] = $current_trame;
		$tab_data['trame_hier'] = $yesterday_trame;
		$tab_data['nom_circuit1'] = $nom_circuit1;
		$tab_data['nom_circuit2'] = $nom_circuit2;
		$tab_data['nom_circuit3'] = $nom_circuit3;
		$tab_data['nom_circuit4'] = $nom_circuit4;
		$tab_data['nom_circuit5'] = $nom_circuit5;

		$tab_data['isous'] = Eco_legrand::get_intensite_max(init('eqlogic_id'));
		$tab_data['type_abo'] = Eco_legrand::get_type_Abo(init('eqlogic_id'));
		

		/* Déterminer s'il y a plusieurs équipements électrique dépendant d'un total*/
		$sql = 'select distinct eqLogicID from Eco_legrand_jour where eqLogicID > 0';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		if ($result) {
			$id_eq = init('eqlogic_id');
			$nb_equipement = 1;
			foreach ($result as $key => $val) {
				$eqLogics = eqLogic::byId($id_eq);
				if($eqLogics) {					
					$nb_equipement += 1;
				}
			}
		}else {
			$nb_equipement = 1;
		}
		
		$tab_data['nb_equipement'] = $nb_equipement;
       
		ajax::success($tab_data);

	}
	
    //if (init('action') == 'crontabAllJour') {
		/*insert table jours a faire 1 fois par jours a 00h00 */
		
		//	conso_teleinfo::crontabJour(true);
		//	ajax::success();
	//}

	//if (init('action') == 'crontabJour') {
		/*Uniquement la date du jour  */
		//conso_teleinfo::deleteNullValue();
		//conso_teleinfo::crontabJour();
		//ajax::success();

	//}
	if (init('action') == 'get_date_actuelle' && init('eqlogic_id')) {

			
		$sept_derniers_jours['debut'] = date('Y-m-d', strtotime('-6 day'));
		$sept_derniers_jours['fin'] = date("Y-m-d");
		$tab_data['sept_derniers_jours'] = $sept_derniers_jours;

		
		$date = new DateTime();
		
		$quatre_dernières_semaine['debut']  = Eco_legrand_teleinfo::get_lundi_vendredi_from_week(date('W', strtotime('-3 weeks')),date('Y-m-d', strtotime('-3 weeks')));
		$quatre_dernières_semaine['fin']  = Eco_legrand_teleinfo::get_lundi_vendredi_from_week(date('W'),date('Y-m-d'),"Y-m-d",'fin');
		$tab_data['quatre_dernières_semaine'] = $quatre_dernières_semaine;

		$d = Eco_legrand::getDateAbo(init('eqlogic_id'));
		$douze_derniers_mois['debut'] = $d['date_debut_fact'];
		$douze_derniers_mois['fin'] = $date->format('Y-m-d');
		$tab_data['douze_derniers_mois'] = $douze_derniers_mois;

		$douze_derniers_mois_année_précédente['debut'] = date('Y-m-d', strtotime('-1 year', strtotime($d['date_debut_fact'])));
		$douze_derniers_mois_année_précédente['fin'] =date('Y-m-d', strtotime('-1 year', strtotime($d['date_fin_fact'])));
		$tab_data['douze_derniers_mois_année_précédente'] = $douze_derniers_mois_année_précédente;
		

		$cinq_dernières_années['debut'] = date('Y-m-d', strtotime('-5 year', strtotime($d['date_debut_fact'])));
		$cinq_dernières_années['fin'] = $d['date_fin_fact'];
		$tab_data['cinq_dernières_années'] = $cinq_dernières_années;
		$tab_data['date_abo']=eqLogic::byId(init('eqlogic_id'))->getConfiguration("date_abo","01/01");;
		
		

		ajax::success($tab_data);
	}
  
	if (init('action') == 'Trame_actuelle' && init('eqlogic_id')) {
		$data = Eco_legrand_teleinfo::get_trame_actuelle(init('limit'),false,false,init('eqlogic_id'));
		if (init('yesterday',false) && init('yesterday') != 'false') {
				$yesterday= Eco_legrand_teleinfo::get_trame_actuelle(init('limit'),true,false,init('eqlogic_id'));
				$data['yesterday_papp'] =  $yesterday['papp'];
		}
			

		ajax::success($data);
	}
  
	if (init('action') == 'Graphique' && init('eqlogic_id')) {
		$data_year_old = false;
		$xlabel = 'du '. init('debut') .' au '.init('fin');
		$pdate_debut = init('debut');
		$pdate_fin = init('fin');
		$timestampdebut = strtotime($pdate_debut);
		$timestampfin = strtotime($pdate_fin);
		$pdate_debut_old = init('debut_old');
		$pdate_fin_old = init('fin_old');
		$timestampdebut_old = strtotime($pdate_debut_old);
		$timestampfin_old = strtotime($pdate_fin_old);
		$type_graph =  init('graph_type',false);
		$eqLogic = eqLogic::byId(init('eqlogic_id'));
		$tva=$eqLogic->getConfiguration("tva",1);
		$result=[];
		$result_old=[];
		if($type_graph == "mois" ){
			$kwhhp_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$kwhhc_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hp_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hc_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$timestp_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hp_ttc_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hc_ttc_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$temp_moy_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$temp_min_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$temp_max_old=[0,0,0,0,0,0,0,0,0,0,0,0];
			$date_debut=$pdate_debut;			
			$date_fin= date("Y-m-d", strtotime($date_debut." +1 Month -1 Day"));
		
			while (strtotime($date_fin) < $timestampfin) {
				$result_temp = Eco_legrand_teleinfo::get_calcul_prix($date_debut,$date_fin,$type_graph,init('eqlogic_id'),false,true);
				$date_debut=date("Y-m-d", strtotime($date_debut." +1 Month"))	;		
				$date_fin= date("Y-m-d", strtotime($date_debut." +1 Month -1 Day"));
				$result = array_merge($result,$result_temp);
			}
			$result_temp = Eco_legrand_teleinfo::get_calcul_prix($date_debut,$date_fin,$type_graph,init('eqlogic_id'),false,true);
			$result = array_merge($result,$result_temp);

			$date_debut_old=$pdate_debut_old;	
			$date_fin_old= date("Y-m-d", strtotime($date_debut_old." +1 Month -1 Day"));
			$sql="SELECT min(date) as date FROM `Eco_legrand_teleinfo`";
			$result_date_min = DB::Prepare($sql, null, DB::FETCH_TYPE_ROW);
			
			while(strtotime($result_date_min["date"]) > strtotime($date_debut_old)){
				$date_debut_old=date("Y-m-d", strtotime($date_debut_old." +1 Month"))	;		
				$date_fin_old= date("Y-m-d", strtotime($date_debut_old." +1 Month -1 Day"));
			}

			
			
			
			while (strtotime($date_fin_old) <= $timestampfin_old) {
						
				$result_old_temp = Eco_legrand_teleinfo::get_calcul_prix($date_debut_old,$date_fin_old,$type_graph,init('eqlogic_id'),false,true);
				$date_debut_old=date("Y-m-d", strtotime($date_debut_old." +1 Month"))	;		
				$date_fin_old= date("Y-m-d", strtotime($date_debut_old." +1 Month -1 Day"));
				$result_old = array_merge($result_old,$result_old_temp);
				
				
			}
			//log::add("atest","debug", strtotime($date_fin_old)."<". $timestampfin_old);
				//log::add("atest","debug", $result_old_temp);
			//$result_old_temp = Eco_legrand_teleinfo::get_calcul_prix($date_debut,$date_fin,$type_graph,init('eqlogic_id'),false,true);
			//$result_old = array_merge($result_old,$result_old_temp);
			//Eco_legrand::add_log("debug", 'year_old date deb:'.$pdate_debut.' date fin:'.$pdate_fin);
		}else{
			$result = Eco_legrand_teleinfo::get_calcul_prix($pdate_debut,$pdate_fin,$type_graph,init('eqlogic_id'),false,true);
		
		}
			
		
		$mois=["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"] ;
		
		$no=0;
		if($type_graph == "mois" ){
			$kwhhp=[0,0,0,0,0,0,0,0,0,0,0,0];
			$kwhhc=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hp=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hc=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hp_ttc=[0,0,0,0,0,0,0,0,0,0,0,0];
			$total_hc_ttc=[0,0,0,0,0,0,0,0,0,0,0,0];
			$temp_moy=[null,null,null,null,null,null,null,null,null,null,null,null];
			$temp_min=[null,null,null,null,null,null,null,null,null,null,null,null];
			$temp_max=[null,null,null,null,null,null,null,null,null,null,null,null];
			$timestp=[0,0,0,0,0,0,0,0,0,0,0,0];
		}else if($type_graph == "jours" ){
			$kwhhp=[0,0,0,0,0,0,0];
			$kwhhc=[0,0,0,0,0,0,0];
			$total_hp=[0,0,0,0,0,0,0];
			$total_hc=[0,0,0,0,0,0,0];
			$total_hp_ttc=[0,0,0,0,0,0,0];
			$total_hc_ttc=[0,0,0,0,0,0,0];
			$temp_moy=[null,null,null,null,null,null,null];
			$temp_min=[null,null,null,null,null,null,null];
			$temp_max=[null,null,null,null,null,null,null];
			$timestp=[0,0,0,0,0,0,0];
		}else if($type_graph == "semaines" ){
			$kwhhp=[0,0,0,0];
			$kwhhc=[0,0,0,0];
			$total_hp=[0,0,0,0];
			$total_hc=[0,0,0,0];
			$total_hp_ttc=[0,0,0,0];
			$total_hc_ttc=[0,0,0,0];
			$temp_moy=[null,null,null,null];
			$temp_min=[null,null,null,null];
			$temp_max=[null,null,null,null];
			$timestp=[0,0,0,0];
		}else if($type_graph == "year" ){
			$kwhhp=[];
			$kwhhc=[];;
			$total_hp=[];
			$total_hc=[];
			$total_hp_ttc=[];
			$total_hc_ttc=[];
			$temp_moy=[];
			$temp_min=[];
			$temp_max=[];
			$timestp=[];
		}
		foreach  ($result as  $row){
					
				
		
			/*conversion semaine en date*/
			if (substr($row["categorie"], 0, 3) == 'sem'){
				$year = substr($row["categorie"],-2);
				$week = substr($row["categorie"], 4,2);
				$row["categorie"] = get_lundi_dimanche_from_week($week, (int)$year+2000);
			}
			if($type_graph == "mois" ){
				$no=intval($row["mois"]-1);	
				$timestp_old[$no] = $mois[$row["mois"]-1];
			}
			$timestp[$no] = $row["categorie"];
			
			/*Temperature*/
			$temp_moy[$no] =   ($row["temp_moy"] != 0 ? (float)number_format(str_replace(",", ".", $row["temp_moy"]),2,'.','') : null);
			$temp_min[$no] =  ($row["temp_min"] != 0 ?   (float)number_format(str_replace(",", ".", $row["temp_min"]),2,'.','') : null);
			$temp_max[$no] =  ($row["temp_max"] != 0 ? (float)number_format(str_replace(",", ".", $row["temp_max"]),2,'.','') : null);
					
			$kwhhp[$no] = (checkPrice($row["hp"]) > 0 ? checkPrice($row["hp"]) : 0);
			$kwhhc[$no] = (checkPrice($row["hc"]) > 0 ? checkPrice($row["hc"]) : 0);
		
			$total_hp[$no] =  (checkPrice($row["total_hp"]) > 0 ? checkPrice($row["total_hp"]) : 0);
			$total_hc[$no] =  (checkPrice($row["total_hc"]) > 0 ? checkPrice($row["total_hc"]) : 0);
		
			$total_hp_ttc[$no] = (checkPrice($row["total_hp"]) * $tva / 100 + checkPrice($row["total_hp"]) > 0 ?  checkPrice($row["total_hp"]) * $tva / 100 + checkPrice($row["total_hp"]) : 0);
			$total_hc_ttc[$no] = (checkPrice($row["total_hc"]) * $tva / 100 + checkPrice($row["total_hc"]) > 0 ?  checkPrice($row["total_hc"]) * $tva / 100 + checkPrice($row["total_hc"])  : 0);
			$no++ ;
		}
		
		foreach  ($result_old as  $row_old){
			//log::add("atest","debug",implode("|",$row_old));
			$i=intval($row_old["mois"]-1);		
			//$date_old[$i] = $row_old["date"] ;
			//$timestp_old[$i] = $row_old["categorie"];

			$timestp_old[$i] = $mois[$row_old["mois"]-1];
			//$mois_old[$i] = $row_old["mois"];
			//$annee_old[$i] = $row_old["annee"];
		
			
		
			$hp = checkPrice($row_old["hp"]);
			$hc = checkPrice($row_old["hc"]);
			$val_total_hp =  checkPrice($row_old["total_hp"]);
			$val_total_hc = checkPrice($row_old["total_hc"]);
			
			$temp_moy_old[$i] =   ($row_old["temp_moy"] != 0 ? (float)number_format(str_replace(",", ".", $row_old["temp_moy"]),2,'.','') : 0);
			$temp_min_old[$i] =  ($row_old["temp_min"] != 0 ?   (float)number_format(str_replace(",", ".", $row_old["temp_min"]),2,'.','') : 0);
			$temp_max_old[$i] =  ($row_old["temp_max"] != 0 ? (float)number_format(str_replace(",", ".", $row_old["temp_max"]),2,'.','') : 0);
		
			
			$ttc_hp =  (($val_total_hp * $tva) / 100) + $val_total_hp;
			$ttc_hc =  (($val_total_hc * $tva) / 100) + $val_total_hc;
		
			$kwhhp_old[$i] = ($hp > 0 ? $hp : 0);
			$kwhhc_old[$i] = ($hc > 0 ? $hc : 0);
		
			$total_hp_old[$i] =  ($val_total_hp > 0 ? $val_total_hp : 0);
			$total_hc_old[$i] =  ($val_total_hc > 0 ? $val_total_hc : 0);
		
			$total_hp_ttc_old[$i] = (checkPrice($ttc_hp) > 0 ?  checkPrice($ttc_hp) : 0);
			$total_hc_ttc_old[$i] = (checkPrice($ttc_hc) > 0 ?  checkPrice($ttc_hc)  : 0);
						
			$i++ ;
		} 
		
		
	
		
		$arrayTEMP_moy = (isset($temp_moy) ? array_filter($temp_moy, function($var){ return (!($var == '' || is_null($var)));}) : false);
		$arrayTEMP_max =  (isset($temp_max) ? array_filter($temp_max, function($var){ return (!($var == '' || is_null($var)));}): false);
		$arrayTEMP_min = (isset($temp_min) ?  array_filter($temp_min, function($var){ return (!($var == '' || is_null($var)));}): false);
		/*
		* TEMPERATURES
		* test si le tableau contient que des null*/
		$arrayTEMP_moy_old = (isset($temp_moy_old) ? array_filter($temp_moy_old, function($var){ return (!($var == '' || is_null($var)));}) : false);
		$arrayTEMP_max_old =  (isset($temp_max_old) ? array_filter($temp_max_old, function($var){ return (!($var == '' || is_null($var)));}): false);
		$arrayTEMP_min_old = (isset($temp_min_old) ?  array_filter($temp_min_old, function($var){ return (!($var == '' || is_null($var)));}): false);
		
		
		
		if($type_graph == "mois" ){
			$data =  array(
			/*HEURE PLEINE*/
			'HP_name' => 'Heures Pleines',
			'HP_data_prix' => (!isset($total_hp) ? null : (array) $total_hp),
			'HP_data_prix_ttc' => (!isset($total_hp_ttc) ? null : (array) $total_hp_ttc),
			'HP_data' => (!isset($kwhhp) ? null : (array) $kwhhp),
			
			/*HEURE PLEINE PREC*/
			'HP_name_old' =>   'Heures Pleines année précédente',
			'HP_data_prix_old' => (!isset($total_hp_old) ? null :(array) $total_hp_old),
			'HP_data_prix_ttc_old' => (!isset($total_hp_ttc_old) ? null :(array) $total_hp_ttc_old),
			'HP_data_old' => (!isset($kwhhp_old) ? null :(array) $kwhhp_old),
			
			/*HEURE CREUSE*/
			'HC_name' =>   'Heures Creuses',
			'HC_data_prix' => (!isset($total_hc) ? null :  (array) $total_hc),
			'HC_data_prix_ttc' =>  (!isset($total_hc_ttc) ? null :  (array) $total_hc_ttc),
			'HC_data' => (!isset($kwhhc) ? null :  (array)$kwhhc),
			/*HEURE CREUSE PREC*/
			'HC_name_old' =>   'Heures Creuses année précédente',
			'HC_data_prix_old' => (!isset($total_hc_old) ? null :  (array) $total_hc_old),
			'HC_data_prix_ttc_old' =>  (!isset($total_hc_ttc) ? null :  (array) $total_hc_ttc_old),
			'HC_data_old' => (!isset($kwhhc_old) ? null :  (array) $kwhhc_old),
			/*TEMPERATURES*/
			'TEMP_moy' => (is_array($arrayTEMP_moy) && count($arrayTEMP_moy) > 0 && $arrayTEMP_moy!==false ? (array) $temp_moy : false),
			'TEMP_max' => (is_array($arrayTEMP_max) && count($arrayTEMP_max) > 0 && $arrayTEMP_max!==false ? (array) $temp_max : false),
			'TEMP_min' => (is_array($arrayTEMP_min) && count($arrayTEMP_min) > 0 && $arrayTEMP_min!==false ? (array) $temp_min : false),
			/*TEMPERATURES PREC*/
			'TEMP_moy_old' => (is_array($arrayTEMP_moy_old) && count($arrayTEMP_moy_old) > 0 && $arrayTEMP_moy_old!==false ? (array) $temp_moy_old : false),
			'TEMP_max_old' => (is_array($arrayTEMP_max_old) && count($arrayTEMP_max_old) > 0 && $arrayTEMP_max_old!==false ? (array) $temp_max_old : false),
			'TEMP_min_old' => (is_array($arrayTEMP_min_old) && count($arrayTEMP_min_old) > 0 && $arrayTEMP_min_old!==false ? (array) $temp_min_old : false),
			'show_old' => init('old',false) ,
			//'categories' => (!isset($timestp) ? null :  $timestp),
			'Categories' => (!isset($timestp_old) ? (array)$timestp :  (array)$timestp_old),
			'Libellé'=> init('libelle',false) 
			);
		}else{
			$data =  array(
				/*HEURE PLEINE*/
				'HP_name' => 'Heures Pleines',
				'HP_data_prix' => (!isset($total_hp) ? null : (array) $total_hp),
				'HP_data_prix_ttc' => (!isset($total_hp_ttc) ? null : (array) $total_hp_ttc),
				'HP_data' => (!isset($kwhhp) ? null : (array) $kwhhp),
											
				/*HEURE CREUSE*/
				'HC_name' =>   'Heures Creuses',
				'HC_data_prix' => (!isset($total_hc) ? null :  (array) $total_hc),
				'HC_data_prix_ttc' =>  (!isset($total_hc_ttc) ? null :  (array) $total_hc_ttc),
				'HC_data' => (!isset($kwhhc) ? null :  (array) $kwhhc),
				
				/*TEMPERATURES*/
				'TEMP_moy' => (is_array($arrayTEMP_moy) && count($arrayTEMP_moy) > 0 && $arrayTEMP_moy!==false ? (array) $temp_moy : false),
				'TEMP_max' => (is_array($arrayTEMP_max) && count($arrayTEMP_max) > 0 && $arrayTEMP_max!==false ? (array) $temp_max : false),
				'TEMP_min' => (is_array($arrayTEMP_min) && count($arrayTEMP_min) > 0 && $arrayTEMP_min!==false ? (array) $temp_min : false),
				
				
				'show_old' => init('old',false) ,
				'Categories' => (!isset($timestp) ? null :   (array)$timestp),
				'Libellé'=> init('libelle',false) 
			);
		}
	

		ajax::success($data);
	}
  
	if (init('action') == 'VerifParam') {
		$result = Eco_legrand::CheckOptionIsValid();
        ajax::success($result);
    }
  
	if (init('action') == 'Synchroniser_teleinfo' && init('eqlogic_id')) {
		ajax::success(Eco_legrand_teleinfo::get_conso_tores_hp_hc(init('eqlogic_id')));
		//$result = Eco_legrand::CheckOptionIsValid();
		//$erreurs = Eco_legrand_teleinfo::get_erreur(init('eqlogic_id'));
		
        //ajax::success($erreurs);
    }
  
	if (init('action') == 'loadingPie' && init('eqlogic_id')) {
		if(init('date_debut')){
			log::add("atest","debug",init('date_debut'));
			$res = Eco_legrand_teleinfo::GetPie(init('eqlogic_id',false),'perso',init('date_debut'));
		}else{
			$res = Eco_legrand_teleinfo::GetPie(init('eqlogic_id',false),init('type',false));
		}
		ajax::success($res);
	}
	if (init('action') == 'synthese' && init('eqlogic_id')) {
		$data = Eco_legrand_teleinfo::getSynthese( init('eqlogic_id'),init('type')); 
		
		$eqLogic = eqLogic::byId(init('eqlogic_id'));
		$tva=$eqLogic->getConfiguration("tva",1);
		
		ajax::success(array(
			'TVA' => $tva, 
			'data' => $data,
		  ));
		
	}
	if (init('action') == 'Détecter_Périodes' && init('eqlogic_id')) {
		$data=Eco_legrand_teleinfo::get_périodes_hc_hp(date('Y-m-d'),init('eqlogic_id'));
		ajax::success($data);
	};
	
	
	
    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>