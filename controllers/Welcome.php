<?php
class Welcome extends VK_Controller{
    function index(){
        $this->views('home');
    }
    function page_not_found(){
        $this->views('error_404');
    }
}
?>