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
        $user['localisation'] = $this->geoloc->get_city_by_placeid($user['localisation']);
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
        if(!isset($_SESSION['user'])) {
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        if($this->user_model->is_report($_SESSION['user']['id'], $pid)[0] > 0) {
            $this->set(array('info' => 'Vous avez bloque cette personne. Ne vous infliger pas cela.'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        $uid = $_SESSION['user']['id'];
        if($pid == $uid) {
            header('Location: '.$this->base_url().'user/my_profil');
            exit;
        }
        if(!$this->user_model->already_visit($uid, $pid)){
            $this->user_model->log_visit($uid, $pid);
            $this->notification_model->add_notification($pid, 1, $uid);
        }
        $profil['profil'] = $this->user_model->get_profil($pid);

        $mylat = $_SESSION['user']['lat'];
        $mylng = $_SESSION['user']['lng'];
        $profil['distance'] = round($this->geoloc->get_distance_m($profil['profil']['lat'], $profil['profil']['lng'], $mylat, $mylng) / 1000, 1);
        $profil['profil']['localisation'] = $this->geoloc->get_city_by_placeid($profil['profil']['localisation']);

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
        $profil['profil']['pop'] = $this->calcul_pop($pid);
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
        $data['likes'] = $this->like_model->get_like($uid);
        foreach($data['likes'] AS $like){
            if($this->like_model->like_me($like['id'], $uid)){
                $data['connect'][] = $like;
            }
        }
        $data['visits'] = $visits;
        $_SESSION['notif'] = FALSE;
        $_SESSION['notif_like'] = TRUE;
        $this->notification_model->rm_notification($uid, 1);
        if(isset($this->notification_model->as_notification($uid)[0])){
            $_SESSION['notif'] = 1;
        }
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
            $this->set(array('info' => "Vous ne verrez plus cet personne."));
            header('Location: '.$this->base_url().'match/decouverte');
            exit;
        }
        else if($type == 2){
            $this->set(array('info' => "Merci de ce retour, ce cas sera examiné."));
            header('Location: '.$this->base_url().'match/decouverte');
            exit;
        }
    }
    function edit_position(){
        if(!isset($_SESSION['user'])){
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: '.$this->base_url());
            exit;
        }
        $uid = $_SESSION['user']['id'];
        $place_id = $_POST['place_id'];
        if($pos = $this->geoloc->get_coord_by_place_id($place_id)){
            $this->user_model->update_position($uid, $pos['lat'], $pos['lng'], $place_id);
            $_SESSION['user']['localisation'] = $place_id;
            $_SESSION['user']['lat']= $pos['lat'];
            $_SESSION['user']['lng'] = $pos['lng'];
            $this->set(array('info' => "Votre position à été mise à jour."));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else{
            $this->set(array('info' => "Une erreur c'est produite. Retentez ulterieurement."));
            header('Location: '.$this->base_url().'user/my_profil');
        }
    }
    function edit_profil(){
        $uid = $_SESSION['user']['id'];
        $inputs = array(
            'pseudo' => $_POST['pseudo'],
            'nom' => ucfirst(strtolower($_POST['nom'])),
            'prenom' => ucfirst(strtolower($_POST['prenom'])),
            'email' => $_POST['email'],
            'date_naissance' => $_POST['date_naissance'],
            'sexe' => $_POST['sexe'],
            'orientation' => $_POST['orientation']);

        if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['pseudo']) != 1 || !$this->user_model->value_unique('pseudo', $_POST['pseudo'], $uid)) {
            $this->set(array('info' => 'Le champ pseudo n\'est pas conforme ou déjà pris.'));
            header('Location: '.$this->base_url().'user/my_profil');
            exit;
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['nom']) != 1 ) {
            $this->set(array('info' => 'Le champ nom n\'est pas conforme.'));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else if (preg_match("/[A-Za-z _àèéùç-]/", $inputs['prenom']) != 1) {
            $this->set(array('info' => 'Le champ prénom n\'est pas conforme.'));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE || !$this->user_model->value_unique('email', $_POST['email'], $uid)) {
            $this->set(array('info' => 'Le champ email n\'est pas conforme ou déja utilisé.'));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else if (!$this->valid_date($inputs['date_naissance'])) {
            $this->set(array('info' => 'Le champ date de naissance n\'est pas conforme.'));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else if ($inputs['sexe'] != 1 && $inputs['sexe'] != 2) {
            $this->set(array('info' => 'Veullez nous dire si vous etes un homme ou une femme. Ca nous aidera ;)'));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else if ($inputs['orientation'] > 2 && $inputs['orientation'] < 0) {
            $this->set(array('info' => 'Mhhh, arretez de touchez au code svp...'));
            header('Location: '.$this->base_url().'user/my_profil');
        }
        else{
            if($this->user_model->update_profil($inputs,$uid)){
                $_SESSION['user']['pseudo'] = $inputs['pseudo'];
                $_SESSION['user']['nom'] = $inputs['nom'];
                $_SESSION['user']['prenom'] = $inputs['prenom'];
                $_SESSION['user']['email'] = $inputs['email'];
                $_SESSION['user']['sexe'] = $inputs['sexe'];
                $_SESSION['user']['orientation'] = $inputs['orientation'];
                $_SESSION['user']['date_naissance'] = $inputs['date_naissance'];
                $this->set(array('info' => 'Votre profil à bien été mise à jour.'));
                header('Location: '.$this->base_url().'user/my_profil');
            }
            else{
                $this->set(array('info' => 'Une erreur c\'est produit. Retentez ulterieurement.'));
                header('Location: '.$this->base_url().'user/my_profil');
            }
        }
    }
    function calcul_pop($pid){
        $likes = count($this->like_model->get_like($pid));
        $visits = count($this->user_model->get_visit($pid));
        $ratio = $likes != 0 && $visits != 0 ? $visits / $likes : 0;
        $pop = $likes + ($ratio * $likes);
        return $pop;
    }

}