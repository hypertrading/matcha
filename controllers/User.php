<?php
class User extends VK_Controller {
    function profil($pseudo){
        $user = $this->user_model->get_profil($pseudo);
        if(file_exists('assets/img/user_photo/'.$user['id'].'.jpg'))
            $user['images'][0] = 'assets/img/user_photo/'.$user['id'].'.jpg';
        $tag = $this->user_model->get_tag($user['id']);
        $user['tag'] = $tag;
        $this->set($user);
        $this->views('user/profil');
    }
    function edit_description() {
        if (preg_match("/[A-Za-z0-9 '\",.;:!?_àêèéùç-]/", $_POST['description']) != 1 ) {
            $this->set(array('info' => 'Le message contient des caracteres non autorisés.'));
            $this->profil($_SESSION['user']['pseudo']);
            exit;
        }
        else {
            $this->user_model->edit_description($_SESSION['user']['id'], $_POST['description']);
            $this->set(array('info' => 'Geute'));
            $this->profil($_SESSION['user']['pseudo']);
            exit;
        }
    }
    function add_picture() {
        $data = $_FILES['picture']['tmp_name'];
        $sizetmp = getimagesize($data);
        $tmp_image = imagecreatefromjpeg($data);
        $image = imagecreatetruecolor($sizetmp[0], $sizetmp[1]);
        imagecopyresampled($image, $tmp_image, 0, 0, 0, 0, $sizetmp[0], $sizetmp[1], $sizetmp[0], $sizetmp[1]);
        $path = 'assets/img/user_photo/'.$_SESSION['user']['id'].'.jpg';
        imagejpeg($image, $path);
        $this->set(array('info' => 'Geute'));
        $this->profil($_SESSION['user']['pseudo']);
        exit;
    }
    function add_tag() {
        if (preg_match("/[A-Za-z0-9 '_àâêèéùûôç-]/", $_POST['tag']) != 1 ) {
            $this->set(array('info' => 'Le tag contient des caracteres non autorisés.'));
            $this->profil($_SESSION['user']['pseudo']);
            exit;
        }
        else {
            if ($this->tag_model->search_tag($_POST['tag']) == FALSE)
                $this->tag_model->create_tag($_POST['tag']);
            if ($this->tag_model->already_tag($_SESSION['user']['id'], $_POST['tag']) == FALSE){
                $this->tag_model->add_tag($_SESSION['user']['id'], $_POST['tag']);
                $this->set(array('info' => 'Geute'));
                $this->profil($_SESSION['user']['pseudo']);
                exit;

            }
            else {
                $this->set(array('info' => 'Vous avez deja se tag'));
                $this->profil($_SESSION['user']['pseudo']);
                exit;
            }
        }
    }
    function remove_tag($tag){
        $this->tag_model->remove_tag($_SESSION['user']['id'], $tag);
        $this->set(array('info' => "Vous avez enlever le tag $tag"));
        $this->profil($_SESSION['user']['pseudo']);
        exit;
    }
}