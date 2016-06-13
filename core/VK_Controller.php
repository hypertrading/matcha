<?php
foreach (glob("models/*_model.php") as $filename) {
    include_once $filename;
}
include_once "core/VK_Model.php";
include_once "library/geoloc/Geoloc.php";
date_default_timezone_set('Europe/Paris');
class VK_Controller
{
    public $vars = array();
    function __construct() {
        $this->user_model = new User_model();
        $this->security_model = new Security_model();
        $this->tag_model = new Tag_model();
        $this->picture_model = new Picture_model();
        $this->like_model = new Like_model();
        $this->notification_model = new Notification_model();
        $this->messagerie_model = new Messagerie_model();
        $this->user_model->clean_log();
        $this->geoloc = new Geoloc();
        if(isset($_SESSION['data'])) {
            $this->vars = array_merge($this->vars, $_SESSION['data']);
            unset($_SESSION['data']);
        }
    }
    function __destruct() {
        unset($vars);
        $vars = get_object_vars($this);
        foreach ($vars as $key => $val) {
            $this->$key = null;
        }
    }
    function set($data) {
        $this->vars = array_merge($this->vars, $data);
        $_SESSION['data'] = $data;
    }
    function load_c($controller){
        $controller = ucfirst($controller);
        include_once 'controllers/'.$controller.'.php';
        return new $controller;
    }
    function views($filename) {
        extract($this->vars);
        require(ROOT . 'views/' . $filename . '.php');
    }
    function base_url() {
        return 'http://' . $_SERVER['SERVER_NAME'] . '/matcha/';
    }
    function array_debug($array) {
        echo "<pre>" . print_r($array, TRUE) . "</pre>";
    }
    function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }

    //Retourne le nombre de jours ecoule depuis cette date
    function get_the_day($date) {
        $datetime1 = new DateTime("now");
        $datetime2 = new DateTime($date);
        $datetime1->setTime(0, 0, 0);
        $datetime2->setTime(0, 0, 0);
        $interval = $datetime1->diff($datetime2);
        $interval = $interval->format('%a');
        switch ($interval) {
            case 0:
                return 'Aujourd\'huis';
                break;
            case 1:
                return 'Hier';
                break;
            default:
                return 'Il y a ' . $interval . ' jours';
        }
    }
    function valid_date($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
?>