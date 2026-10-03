<?php

require_once dirname(__FILE__) . '/../../core/php/Eco_legrand.inc.php';

class Eco_legrand_prix{
    public $id ;
    public $hc ;
    public $hp;
    public $date_debut;
    public $date_fin;
    public $type='électricité';
    public $eqLogicId;
    

    /*RECUPERATION*/
    public function getId() {return $this->id;}
    public function getHC() {return $this->hc;}
    public function getHP() {return $this->hp;}
    public function getDate_debut() {return $this->date_debut;}
    public function getDate_fin() {	return $this->date_fin;}
    public function gettype(){ 	return $this->type; }

    /*ENVOI*/
    public function setId($id) {	$this->id = $id;}
    public function setHP($data) {	$this->hp = str_replace(",", ".", $data);}
    public function setHC($data) {	$this->hc= str_replace(",", ".", $data);}
    //public function setDate_debut($data) {	$this->date_debut = self::convert_date_to_sql_date($data);}
    //public function setDate_fin($data) {	$this->date_fin = self::convert_date_to_sql_date($data);}
    public function setDate_debut($data) {	$this->date_debut = $data;}
    public function setDate_fin($data) {	$this->date_fin = $data;}
    public function settype($data){ $this->type = $data; }
    public function setEqLogicId($data){ $this->eqLogicId = $data; }

    public static function all() {//OK

        $sql = 'SELECT ' . DB::buildField(__CLASS__) . ' FROM Eco_legrand_prix ORDER BY  type ASC, date_debut';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
    public static function byEqLogicId($eqLogicID){//OK
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . ' FROM Eco_legrand_prix WHERE EqLogicId ='.$eqLogicID.' ORDER BY  type ASC, date_debut';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    } 

    public static function convert_date_to_sql_date($date){//ok
        $dt = DateTime::createFromFormat('d/m/yy', $date);
        return $dt->format('Y-m-d');
    }
    public static function dateFr($date) {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt->format('d/m/Y');
        //return strftime('%d/%m/%Y', strtotime($date));
    }
 
    public function save() {//ok
        if ($this->hc == 0) {$this->hc = $this->hp; }
        //$this->id =0;
        $this->hp =  number_format((float)$this->hp,5,'.','');
        $this->hc = number_format((float)$this->hc,5,'.','');
        $this->date_debut = self::convert_date_to_sql_date($this->date_debut);
        $this->date_fin = self::convert_date_to_sql_date($this->date_fin);
        return DB::save($this);
    }
   
    public static function byId($_id) {//OK
        $values = array(
            'id' => $_id
            );
        $sql = "SELECT *  FROM Eco_legrand_prix WHERE id=:id";
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    /*public function remove() {
        return DB::remove($this);
    }*/

    
    /*Aremplacer par byId*/
    /*function getprice($id_price=false){
        $this->byId($id_price);
        //	$sql= 'select * from Eco_legrand_prix'	 .(!$id_price ? '' : ' where  id = '.(int)$id_price).' order by id ASC' ;
        //	$row = Tools::getRow($sql);
        //	return $row;
    }*/

   /* static public function getPriceByperiode($date_debut, $date_fin,$type="électricité"){
        conso_teleinfo::checkdateMysql();
        $sql = 'SELECT
                hp AS prix_hp,
                hc AS prix_hc
            FROM
            Eco_legrand_prix px
                where 		(UNIX_TIMESTAMP(DATE_FORMAT("' . $date_debut . '", "%Y-%m-%d"))
                    BETWEEN
                        UNIX_TIMESTAMP(DATE_FORMAT(px.date_debut,"%Y-%m-%d")) and UNIX_TIMESTAMP(DATE_FORMAT(px.date_fin,"%Y-%m-%d"))
                    OR
                UNIX_TIMESTAMP(DATE_FORMAT("' . $date_fin . '", "%Y-%m-%d"))
                    BETWEEN  UNIX_TIMESTAMP(DATE_FORMAT(px.date_debut,"%Y-%m-%d")) and UNIX_TIMESTAMP(DATE_FORMAT(px.date_fin,"%Y-%m-%d"))
                    or (UNIX_TIMESTAMP(DATE_FORMAT("' . $date_debut . '", "%Y-%m-%d")) <= UNIX_TIMESTAMP(DATE_FORMAT(px.date_debut,"%Y-%m-%d")) and UNIX_TIMESTAMP(DATE_FORMAT("' . $date_fin . '", "%Y-%m-%d")) >= UNIX_TIMESTAMP(DATE_FORMAT(px.date_fin,"%Y-%m-%d"))))
                    AND type = "'.$type.'"';

        $tab = array();
        $tab_prix_hp = array();
        $tab_prix_hc = array();
        //log::add('Eco_legrand', 'debug', 'getPriceByperiode requete:'. $sql);
        $result =  DB::Prepare($sql,  array(), DB::FETCH_TYPE_ALL);
        $nbenreg = count($result); //mysql_num_rows($result2);

        if ((int)$nbenreg==0) return false;

        foreach ($result as $row2){
            $tab_prix_hp[] = $row2['prix_hp'];
            $tab_prix_hc[] = $row2['prix_hc'];
        }

            $tab['prix_hp'] = implode("/ ", array_unique($tab_prix_hp));
            $tab['prix_hc'] = implode("/ ",array_unique($tab_prix_hc));

        return $tab;

    }*/

   /* static function CurrentPrice(){
            $sql = 'SELECT *
            FROM
            Eco_legrand_prix px
                where 		UNIX_TIMESTAMP("' . date("Y-m-d") . '")
                    BETWEEN
                        UNIX_TIMESTAMP(DATE_FORMAT(px.date_debut,"%Y-%m-%d")) and UNIX_TIMESTAMP(DATE_FORMAT(px.date_fin,"%Y-%m-%d")) limit 1';
            $result =  DB::Prepare($sql,  array(), DB::FETCH_TYPE_ROW);
            $nbenreg = count($result);
            if($nbenreg>0)
                return $result;

            return false;

    }*/
   /* static function getPriceTotal($date_debut, $date_fin,$id_ecq=0,$type="électricité"){
        conso_teleinfo::checkdateMysql();
        $eqLogics = eqLogic::byId($id_ecq);
        $pulse = (!$eqLogics->getConfiguration('pulse') ? 1 : (float)$eqLogics->getConfiguration('pulse'));
        
        $sql = 'SELECT CASE when "'.$type.'" = "gaz" THEN ROUND(px.hc*ROUND(SUM(s.hp/1000*'.$pulse.'),0),0) ELSE ROUND(SUM(s.hp*'.$pulse.'),0) END  as hp, 
                CASE when "'.$type.'" = "gaz" THEN 0 ELSE ROUND(SUM(s.hc),0) END as hc,s.rec_date,
                (
                SELECT  CASE WHEN "'.$type.'" in ("water") THEN 5.5 ELSE valeur END
                    FROM conso_tva
                    where
                    UNIX_TIMESTAMP(DATE_FORMAT(rec_date, "%Y-%m-%d"))  BETWEEN UNIX_TIMESTAMP( DATE_FORMAT(date_debut,  "%Y-%m-%d" ) ) AND UNIX_TIMESTAMP( DATE_FORMAT( date_fin,  "%Y-%m-%d"))
                    AND
                    UNIX_TIMESTAMP(DATE_FORMAT(rec_date, "%Y-%m-%d")) BETWEEN UNIX_TIMESTAMP( DATE_FORMAT( date_debut,  "%Y-%m-%d" ) ) AND UNIX_TIMESTAMP( DATE_FORMAT( date_fin,  "%Y-%m-%d"))
                    and global = 1
                    limit 0,1
                ) as prix_tva,
                px.hp AS prix_hp,
                px.hc AS prix_hc,
                max(idx_max_hp)*'.$pulse.' as idx_max_hp,
                max(idx_max_hc)*'.$pulse.' as idx_max_hc,
                min(idx_min_hp)*'.$pulse.' as idx_min_hp,
                min(idx_min_hc)*'.$pulse.' as idx_min_hc,
                case when "'.$type.'" in ("water","oil") then px.hp*ROUND(SUM(s.hp/1000*'.$pulse.'),0) when "'.$type.'" = "gaz" THEN px.hp*px.hc*ROUND(SUM(s.hp/1000*'.$pulse.'),0) else px.hp*ROUND(SUM(s.hp*'.$pulse.'),0) end as total_ht_hp,
                case when "'.$type.'" in ("water","oil","gaz") then 0 else px.hc*ROUND(SUM(s.hc*'.$pulse.'),0) end as total_ht_hc
            FROM
            conso_jour s
                LEFT JOIN conso_price px ON

                UNIX_TIMESTAMP(DATE_FORMAT(rec_date, "%Y-%m-%d"))
                    BETWEEN
                        UNIX_TIMESTAMP(DATE_FORMAT(px.date_debut,"%Y-%m-%d")) and UNIX_TIMESTAMP(DATE_FORMAT(px.date_fin,"%Y-%m-%d"))
                    AND
                UNIX_TIMESTAMP(DATE_FORMAT(rec_date, "%Y-%m-%d"))
                    BETWEEN  UNIX_TIMESTAMP(DATE_FORMAT(px.date_debut,"%Y-%m-%d")) and UNIX_TIMESTAMP(DATE_FORMAT(px.date_fin,"%Y-%m-%d"))
                AND type = "'.$type.'"
            WHERE id_eq = '.(int)$id_ecq.' AND  `rec_date` BETWEEN
                    "' . $date_debut . '"
                    AND "' . $date_fin . '"
            group by prix_hp, prix_hc, prix_tva
            ORDER BY rec_date';

        //log::add('conso', 'debug', 'getPriceTotal requete:'. $sql);
        $tab = array();
        $tab_prix_hp = array();
        $tab_prix_hc = array();
        $tab_tva  = array();

        $result =  DB::Prepare($sql,  array(), DB::FETCH_TYPE_ALL);
        $nbenreg = count($result); //mysql_num_rows($result2);

        if ((int)$nbenreg==0) return false;

        $tab['total_ht_hp']= 0;
        $tab['total_ht_hc']= 0;
        $tab['total_ttc_hp']= 0;
        $tab['total_ttc_hc'] =0;
        $tab['total_watt_hp']= 0;
        $tab['total_watt_hc']= 0;

        $tab['idx_max_hp'] = -1;
        $tab['idx_max_hc'] = -1;
        $tab['idx_min_hp'] = -1;
        $tab['idx_min_hc'] = -1;
        $Date_cours = '';

        foreach ($result as $row2){
            //	while($row2 = mysql_fetch_array($result2)){

            //print $row2['hp'].'<br>';

            $tab['total_ht_hp'] += $row2['total_ht_hp'];
            $tab['total_ht_hc'] += $row2['total_ht_hc'];

            //print $tab['total_ht_hp']. ' | ' .$tab['total_ht_hc'].'<br>';

            is_numeric($row2['total_ht_hp']) ? $temp_total_ht_hp = $row2['total_ht_hp'] : $temp_total_ht_hp = 0;
            is_numeric($row2['total_ht_hc']) ? $temp_total_ht_hc = $row2['total_ht_hc'] : $temp_total_ht_hc = 0;
            is_numeric($row2['hp']) ? $temp_hp = $row2['hp'] : $temp_hp = 0;
            is_numeric($row2['hc']) ? $temp_hc = $row2['hc'] : $temp_hc = 0;
            is_numeric($row2['prix_hp']) ? $temp_prix_hp = $row2['prix_hp'] : $temp_prix_hp = 0;
            is_numeric($row2['prix_hc']) ? $temp_prix_hc = $row2['prix_hc'] : $temp_prix_hc = 0;
            is_numeric($row2['prix_tva']) ? $temp_prix_tva = $row2['prix_tva'] : $temp_prix_tva = 0;

            $tab['total_ttc_hp'] += (($temp_total_ht_hp * $temp_prix_tva) / 100) + ($temp_hp * $temp_prix_hp);
            $tab['total_ttc_hc'] += (($temp_total_ht_hc * $temp_prix_tva) / 100) + ($temp_hc * $temp_prix_hc);


            if($tab['idx_max_hp'] < $row2['idx_max_hp'] || $tab['idx_max_hp'] < 0) $tab['idx_max_hp'] = $row2['idx_max_hp'];
            if($tab['idx_max_hc'] < $row2['idx_max_hc'] || $tab['idx_max_hc'] < 0) $tab['idx_max_hc'] = $row2['idx_max_hc'];

            if($tab['idx_min_hp'] > $row2['idx_min_hp'] || $tab['idx_min_hp'] < 0) $tab['idx_min_hp'] = $row2['idx_min_hp'];
            if($tab['idx_min_hc'] > $row2['idx_min_hc'] || $tab['idx_min_hc'] < 0) $tab['idx_min_hc'] = $row2['idx_min_hc'];


            $tab_tva[] = $row2['prix_tva'];

            $tab_prix_hp[] = $row2['prix_hp'];
            $tab_prix_hc[] = $row2['prix_hc'];
            $tab_coef[] = $row2['prix_hc'];
            
            $tab['prix_hp'] = implode("/", array_unique($tab_prix_hp));
            $tab['prix_hc'] = implode("/",array_unique($tab_prix_hc));
            $tab['tva'] = implode("/", array_unique($tab_tva));	
            $tab['coef'] = implode("/",array_unique($tab_coef));
            $tab['total_watt_hp'] += $row2['hp'];
            

        }
        
        $sql = 'SELECT ROUND(SUM(s.hp*'.$pulse.'),0) as hp, ROUND(SUM(s.hc*'.$pulse.'),0) as hc from conso_jour s WHERE id_eq = '.(int)$id_ecq.' AND  `rec_date` BETWEEN
                    "' . $date_debut . '"
                    AND "' . $date_fin . '"';
        $result =  DB::Prepare($sql,  array(), DB::FETCH_TYPE_ALL);
        foreach ($result as $row2){
            if ($type == 'gaz') {
                $tab['total_m3'] = $row2['hp']/1000;
                //$tab['total_watt_hp'] = $row2['hp']/1000*$tab['coef'];
                $tab['total_watt_hc'] = 0;
            }
            else {
                $tab['total_watt_hp'] = $row2['hp'];
                $tab['total_watt_hc'] = $row2['hc'];
            }

            //log::add('conso', 'debug', 'getPriceTotal HP:'. $row2['hp']. ' Total_watthHP:'.$tab['total_watt_hp']);
        }
            if (stripos($type, 'elect') !== false) {
                $tab['total_ht'] = $tab['total_ht_hp'] + $tab['total_ht_hc'];
            }
            else {
                $tab['total_ht'] = $tab['total_ht_hp'];
            }
            $tab['total_ttc'] = $tab['total_ttc_hp'] + $tab['total_ttc_hc'];
            //print_r($tab);

        return $tab;
    }*/

   

   /* function ajouter_prix($hp,$hc, $date_debut ,$date_fin){
        $sql = 'insert into Eco_legrand_prix  (hp,hc,date_debut,date_fin) value ( "'.$hp.'","'.$hc.'", "' . $date_debut . '", "' .  $date_fin . '")';
        $row = Tools::Execute($sql);
        //$result = mysql_query($sql) or die ("<b>Erreur</b> dans la requète <b>" . $sql . "</b> : " . mysql_error() . " !<br>");
        return true;
    }*/


   /* function updateprice($id_price,$hp,$hc, $date_debut, $date_fin){

        $sql = 'update Eco_legrand_prix set hp = "'.$hp.'",hc = "'.$hc.'",date_debut = "' . $date_debut . '",date_fin = "' . $date_fin . '"  where id='.(int)$id_price;
        $row = Tools::Execute($sql);
        //	$result = mysql_query($sql) or die ("<b>Erreur</b> dans la requète <b>" . $sql . "</b> : " . mysql_error() . " !<br>");
        return true;
    }*/

    
}