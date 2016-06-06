<?php
class User extends VK_Controller {
    function my_profil(){
        if(!isset($_SESSION['user'])){
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: '.$this->base_url());
            exit;
        }
        $id = $_SESSION['user']['id'];
        $user = $this->user_model->get_profil($id);
        $img = $this->picture_model->get_user_pict($id);
        $this->array_sort_by_column($img, 'avatar');
        for ($i = 0; isset($img[$i]); $i++){
            $user['images'][$i]['path'] = 'assets/img/user_photo/'.$img[$i]['id'].'.jpg';
            $user['images'][$i]['id'] = $img[$i]['id'];
        }
        $tag = $this->tag_model->get_tag($user['id']);
        $user['tag'] = $tag;
        $this->set($user);
        $this->views('user/my_profil');
    }
    function profil($pid){
        if(!isset($_SESSION['user']) || $this->user_model->is_report($_SESSION['user']['id'], $pid)[0] > 0) {
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: '.$this->base_url());
            exit;
        }
        $uid = $_SESSION['user']['id'];
        if($pid == $uid) {
            header('Location: '.$this->base_url().'user/my_profil');
            exit;
        }
        if(!$this->user_model->already_visit($uid, $pid)){
            $this->user_model->log_visit($uid, $pid);
            $this->notification_model->add_notification($pid, 1);
        }
        $profil['profil'] = $this->user_model->get_profil($pid);
        $profil['profil']['tag'] = $this->tag_model->get_tag($profil['profil']['id']);
        $img = $this->picture_model->get_user_pict($pid);
        $this->array_sort_by_column($img, 'avatar');
        for ($i = 0; isset($img[$i]); $i++) {
            $profil['images'][$i] = 'assets/img/user_photo/'.$img[$i]['id'].'.jpg';
        }
        if($this->user_model->is_online($pid)[0] == 1)
            $profil['profil']['date_last_login'] = '<span class="online"></span> En ligne';
        else
            $profil['profil']['date_last_login'] = $this->get_the_day($profil['profil']['date_last_login']);
        $profil['like'] = $this->like_model->is_like($uid, $pid) ? TRUE : FALSE;
        $profil['connected'] = FALSE;
        if($profil['like'])
            $profil['connected'] = $this->like_model->is_like($pid, $uid) ? TRUE : FALSE;
        $profil['profil']['age'] = round ((time() - strtotime($profil['profil']['date_naissance'])) / 3600 / 24 / 365);
        $this->set($profil);
        $this->views('user/profil');
    }
    function dashbord(){
        if(!isset($_SESSION['user'])){
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: '.$this->base_url());
            exit;
        }
        $uid = $_SESSION['user']['id'];
        $visits = $this->user_model->get_visit($uid);
        foreach($visits AS &$visit){
                $visit['visitor'] = $this->user_model->get_profil_min($visit['user_visit']);
        }
        $likes = $this->like_model->get_like($uid);
        $data['likes'] = $likes;
        $data['visits'] = $visits;
        $_SESSION['notif'] = FALSE;
        $this->notification_model->rm_notification($uid, 1);
        $this->set($data);
        $this->views('user/dashbord');
    }
    function edit_description() {
        if (preg_match("/[A-Za-z0-9 '\",.;:!?_àêèéùç-]/", $_POST['description']) != 1 ) {
            $this->set(array('info' => 'Le message contient des caracteres non autorisés.'));
            $this->my_profil();
            exit;
        }
        else {
            $description = htmlspecialchars(addslashes($_POST['description']));
            $this->user_model->edit_description($_SESSION['user']['id'], $description);
            $this->set(array('info' => 'Geute'));
            header('Location: my_profil');
            exit;
        }
    }
    function add_picture() {
        $uid = $_SESSION['user']['id'];
        $data = $_FILES['picture']['tmp_name'];
        $sizetmp = getimagesize($data);
        $tmp_image = imagecreatefromjpeg($data);
        $image = imagecreatetruecolor($sizetmp[0], $sizetmp[1]);
        imagecopyresampled($image, $tmp_image, 0, 0, 0, 0, $sizetmp[0], $sizetmp[1], $sizetmp[0], $sizetmp[1]);
        $picts = $this->picture_model->get_user_pict($uid);
        if(!isset($picts[0]))
            $pid = $this->picture_model->add_picture($uid, 1);
        else
            $pid = $this->picture_model->add_picture($uid, 0);
        $path = 'assets/img/user_photo/'.$pid.'.jpg';
        imagejpeg($image, $path);
        unset($_FILES['picture']);
        $this->set(array('info' => 'Geute'));
        header('Location: my_profil');
        exit;
    }
    function set_avatar($id){
        $uid = $_SESSION['user']['id'];
        $picts = $this->picture_model->get_user_pict($uid);
        for($i = 0; $picts[$i]; $i++) {
            if($picts[$i]['avatar'] == 1) {
                $this->picture_model->unset_avatar($picts[$i]['id']);
                break;
            }
        }
        $this->picture_model->set_avatar($id);
        $this->profil($uid);

    }
    function rm_picture($id) {
        $uid = $_SESSION['user']['id'];
        $pict = $this->picture_model->get_one_pict($id);
        if($pict['avatar'] == 0) {
            $this->picture_model->rm_pict($id);
            $path = 'assets/img/user_photo/'.$id.'.jpg';
            unlink($path);
        }
        else {
            $picts = $this->picture_model->get_user_pict($uid);
            if(isset($picts[1])) {
                $this->picture_model->rm_pict($id);
                $path = 'assets/img/user_photo/'.$id.'.jpg';
                unlink($path);
                for($i = 0; $picts[$i]; $i++) {
                    if($picts[$i]['id'] != $id) {
                        $this->picture_model->set_avatar($picts[$i]['id']);
                        break;
                    }
                }
            }
            else {
                $this->picture_model->rm_pict($id);
                $path = 'assets/img/user_photo/'.$id.'.jpg';
                unlink($path);
            }
        }
        $this->profil($uid);
    }
    function add_tag() {
        if (preg_match("/[A-Za-z0-9 '_àâêèéùûôç-]/", $_POST['tag']) != 1 ) {
            $this->set(array('info' => 'Le tag contient des caracteres non autorisés.'));
            $this->my_profil();
            exit;
        }
        else {
            if ($this->tag_model->search_tag($_POST['tag']) == FALSE)
                $this->tag_model->create_tag($_POST['tag']);
            if ($this->tag_model->already_tag($_SESSION['user']['id'], $_POST['tag']) == FALSE){
                $this->tag_model->add_tag($_SESSION['user']['id'], $_POST['tag']);
                $this->set(array('info' => 'Geute'));
                header('Location: my_profil');
                exit;
            }
            else {
                $this->set(array('info' => 'Vous avez deja se tag'));
                header('Location: my_profil');
                exit;
            }
        }
    }
    function remove_tag($tag){
        $this->tag_model->remove_tag($_SESSION['user']['id'], $tag);
        $this->set(array('info' => "Vous avez enlever le tag $tag"));
        header('Location: my_profil');
        exit;
    }
    function report($pid, $type){
        $uid = $_SESSION['user']['id'];
        $this->user_model->report($pid, $uid, $type);
        if($type == 1){
            $this->like_model->unlike($uid, $pid);
            header('Location: '.$this->base_url().'match/decouverte');
            exit;
        }
        else if($type == 2){
            header('Location: '.$this->base_url().'match/decouverte');
            exit;
        }
    }

}