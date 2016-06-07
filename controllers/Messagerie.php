<?php
class Messagerie extends VK_Controller {
    function index($pid = NULL){
        $uid = $_SESSION['user']['id'];
        $likes = $this->like_model->get_like($uid);
        $connected = array();
        foreach ($likes AS $like){
            if($this->like_model->is_like($uid, $like['id'])){
                $connected[] = $like;
            }
        }
        $data['connected'] = $connected;

        if($pid){
            $chat = $this->messagerie_model->get_conv($uid, $pid);
            foreach ($chat AS $msg){
                $this->messagerie_model->set_msg_read($msg['id'], $uid);
            }
            $data['chat'] = $chat;
            $this->notification_model->rm_notification($uid, 2, $pid);
        }

        $this->set($data);
        $this->views('messagerie');
    }
    function load_new_message($pid){
        $uid = $_SESSION['user']['id'];
        $chat = $this->messagerie_model->get_new_message($uid, $pid);
        foreach ($chat AS $msg){
            $this->messagerie_model->set_msg_read($msg['id'], $uid);
        }
        $this->notification_model->rm_notification($uid, 2, $pid);
        echo json_encode($chat);
    }
    function send_msg(){
        $uid = $_SESSION['user']['id'];
        if (preg_match("/[A-Za-z0-9 '\",.;:!?_àêèéùç-]/", $_POST['msg']) != 1 ) {
            $this->set(array('info' => 'Le message contient des caracteres non autorisés.'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if (!is_numeric($_POST['to'])){
            $this->set(array('info' => 'Mais bordel arretez de touchez au code !'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        $pid = $_POST['to'];
        $msg = htmlspecialchars(addslashes($_POST['msg']));
        $this->messagerie_model->send_msg($uid, $pid, $msg);
        $this->notification_model->add_notification($pid, 2, $uid);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

}