<?php
class Welcome extends VK_Controller{
    function index(){
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
                }
                else if ($notif['type'] == 2){
                    $_SESSION['notif_msg'] = TRUE;
                }
            }
            echo 1;
            $_SESSION['notif'] = TRUE;
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