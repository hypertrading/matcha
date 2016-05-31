<?php
class Welcome extends VK_Controller{
    function index(){

        function get_ip() {
            // IP si internet partagé
            if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            }
            // IP derrière un proxy
            elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            // Sinon : IP normale
            else {
                return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
            }
        }
        $ip = get_ip();

       // echo "votre ip : $ip";
        $this->views('home');
    }
    function page_not_found(){
        $this->views('error_404');
    }
    function notification(){
        $uid = $_SESSION['user']['id'];
        if($this->user_model->as_notification($uid) >= 1){
            echo 1;
        }
        else
            echo 0;
        exit;
    }
}
?>