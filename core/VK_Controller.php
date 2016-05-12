<?php
include 'models/User_model.php';
class VK_Controller{
    public $user_model_class;
    public $vars = array();
    function __construct()
    {
        $this->user_model = new User_model();
    }
     function set($data){
         $this->vars = array_merge($this->vars, $data);
     }

    function views($filename){
        extract($this->vars);
        require (ROOT.'views/'.$filename.'.php');
    }
    function base_url(){
        return 'http://'.$_SERVER['SERVER_NAME'].'/matcha/';
    }
}
?>