<?php
foreach (glob("models/*_model.php") as $filename) {
    include_once $filename;
}
include_once "core/VK_Model.php";
date_default_timezone_set('Europe/Paris');
class VK_Controller {
    public $vars = array();
    function __construct() {
        $this->user_model = new User_model();
        $this->security_model = new Security_model();
        $this->tag_model = new Tag_model();
        $this->picture_model = new Picture_model();
        $this->like_model = new Like_model();
        $this->notification_model = new Notification_model();
        $this->messagerie_model = new Messagerie_model();
        $this->clean_user_log();
    }
    function __destruct()
    {
        unset($vars);
        $vars = get_object_vars($this);
        foreach($vars as $key => $val)
        {
            $this->$key = null;
        }
    }

    function set($data) {
         $this->vars = array_merge($this->vars, $data);
     }
    function views($filename){
        extract($this->vars);
        require (ROOT.'views/'.$filename.'.php');
    }
    function base_url() {
        return 'http://'.$_SERVER['SERVER_NAME'].'/matcha/';
    }
    function ping(){
        $uid = $_SESSION['user']['id'];
        $this->user_model->user_ping($uid);
    }
    function clean_user_log(){
        $this->user_model->clean_log();
    }
    function array_debug($array){
        echo "<pre>".print_r($array,TRUE)."</pre>";
    }
    function get_the_day($date)
    {
        $datetime1 = new DateTime("now");
        $datetime2 = new DateTime($date);
        $datetime1->setTime(0, 0, 0);
        $datetime2->setTime(0, 0, 0);

        $interval = $datetime1->diff($datetime2);

        $interval = $interval->format('%a');
        switch($interval)
        {
            case 0:
                return 'Aujourd\'huis';
                break;
            case 1:
                return 'Hier';
                break;
            default:
                return 'Il y a '.$interval.' jours';
        }
    }
    function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }
    function _get_ip() {
        $ip = json_decode(file_get_contents('https://api.ipify.org?format=json'))->ip;
        return $ip;
    }
    function _geoloc(){
        include_once("library/geoloc/geoipcity.inc");
        include_once("library/geoloc/geoipregionvars.php");

        $gi = geoip_open(realpath("library/geoloc/geolitecity.dat"), GEOIP_STANDARD);
        $ip = $this->_get_ip();
        $record = geoip_record_by_addr($gi, $ip);
        geoip_close($gi);
        $lat =  $record->latitude;
        $long = $record->longitude;

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long;
        if($json = file_get_contents($url)) {
            $data = json_decode($json, true);
            return $data['results'][0];
        }
        return FALSE;
    }
    function geoloc_get_place_id(){
        return $this->_geoloc()['place_id'];
    }
    function geoloc_get_adresse_formatted(){
        return $this->_geoloc()['formatted_address'];
    }
    function geoloc_get_city_by_placeid($place_id){
        $key_api = 'AIzaSyCNyQ1EdWYofXYBMoMNij3fkEUEak6TGtk';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?place_id='.$place_id.'&key='.$key_api;
        if($json = file_get_contents($url)) {
            $data = json_decode($json, true)['results'][0]['address_components'];
            foreach($data as $line)
            {
                if (in_array("locality", $line["types"])) {
                    return $line['long_name'];
                }
            }
            $this->array_debug($data);
        }
        return FALSE;
    }
}
?>