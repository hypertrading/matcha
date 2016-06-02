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
        $notifs = $this->notification_model->as_notification($uid);
        if(count($notifs) >= 1){
            foreach($notifs AS $notif){
                if($notif['type'] == 1){
                    $_SESSION['notif_like'] = TRUE;
                    echo 1;
                }
                else if ($notif['type'] == 2){
                    $_SESSION['notif_msg'] = TRUE;
                    echo 2;
                }
            }
            $_SESSION['notif'] = 1;
        }
        else {
            $_SESSION['notif'] = FALSE;
            $_SESSION['notif_like'] = FALSE;
            $_SESSION['notif_msg'] = FALSE;
            echo 0;
        }
    }
    function ping(){
        $uid = $_SESSION['user']['id'];
        echo $this->user_model->user_ping($uid);
    }
}
?>