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
            $data['chat'] = $chat;
        }
        $this->set($data);
        $this->views('messagerie');
    }
    function load_new_message($pid){
        $uid = $_SESSION['user']['id'];
        $chat = $this->messagerie_model->get_new_message($uid, $pid);
        foreach ($chat AS $msg){
            $this->messagerie_model->set_msg_read($msg['id']);
        }
        echo json_encode($chat);
    }

}