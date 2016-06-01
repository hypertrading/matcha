<?php
foreach (glob("models/*_model.php") as $filename) {
    include_once $filename;
}

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
}
?>