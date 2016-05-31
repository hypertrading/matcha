<?php
class User extends VK_Controller {
    function my_profil(){
        $id = $_SESSION['user']['id'];
        $user = $this->user_model->get_profil($id);
        $img = $this->picture_model->get_user_pict($id);
        for ($i = 0; isset($img[$i]); $i++){
            $user['images'][$i] = 'assets/img/user_photo/'.$img[$i].'.jpg';
        }
        $tag = $this->tag_model->get_tag($user['id']);
        $user['tag'] = $tag;
        $this->set($user);
        $this->views('user/my_profil');
    }
    function profil($id){
        $profil['profil'] = $this->user_model->get_profil($id);
        $tag = $this->tag_model->get_tag($profil['profil']['id']);
        $img = $this->picture_model->get_user_pict($id);
        for ($i = 0; isset($img[$i]); $i++){
            $profil['images'][$i] = 'assets/img/user_photo/'.$img[$i].'.jpg';
        }
        $profil['profil']['tag'] = $tag;
        $this->set($profil);
        $this->views('user/profil');
    }
    function edit_description() {
        if (preg_match("/[A-Za-z0-9 '\",.;:!?_àêèéùç-]/", $_POST['description']) != 1 ) {
            $this->set(array('info' => 'Le message contient des caracteres non autorisés.'));
            $this->my_profil();
            exit;
        }
        else {
            $this->user_model->edit_description($_SESSION['user']['id'], $_POST['description']);
            $this->set(array('info' => 'Geute'));
            header('Location: my_profil');
            exit;
        }
    }
    function add_picture() {
        $data = $_FILES['picture']['tmp_name'];
        $sizetmp = getimagesize($data);
        $tmp_image = imagecreatefromjpeg($data);
        $image = imagecreatetruecolor($sizetmp[0], $sizetmp[1]);
        imagecopyresampled($image, $tmp_image, 0, 0, 0, 0, $sizetmp[0], $sizetmp[1], $sizetmp[0], $sizetmp[1]);
        $pid = $this->picture_model->add_picture($_SESSION['user']['id']);
        $path = 'assets/img/user_photo/'.$pid.'.jpg';
        imagejpeg($image, $path);
        unset($_FILES['picture']);
        $this->set(array('info' => 'Geute'));
        header('Location: my_profil');
        exit;
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
}