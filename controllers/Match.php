<?php
class Match extends VK_Controller {
    function decouverte() {
        if(!isset($_SESSION['user'])) {
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: '.$this->base_url());
            exit;
        }
        $pref_s = $_SESSION['user']['orientation'];
        $uid = $_SESSION['user']['id'];
        $usexe = $_SESSION['user']['sexe'];
        $c_user = $this->load_c('user');
        $mylat = $_SESSION['user']['lat'];
        $mylng = $_SESSION['user']['lng'];

        //Recupere les profil en focntion de l'orientation
        if($pref_s == 0) {
            if ($usexe == 1) {
                $profils = $this->user_model->get_profils_for($uid, 1, 1);
                $profils = array_merge($profils, $this->user_model->get_profils_for($uid, 2, 2));
            }
            else {
                $profils = $this->user_model->get_profils_for($uid, 1, 2);
                $profils = array_merge($profils, $this->user_model->get_profils_for($uid, 2, 1));
            }
        }
        else if ($pref_s == 1) {
            if ($usexe == 1)
                $profils = $this->user_model->get_profils_for($uid, 2, 2);
            else
                $profils = $this->user_model->get_profils_for($uid, 1, 2);
        }
        else if ($pref_s == 2) {
            if ($usexe == 1)
                $profils = $this->user_model->get_profils_for($uid, 1, 1);
            else
                $profils = $this->user_model->get_profils_for($uid, 2, 1);
        }

        foreach ($profils as &$profil) {
            $pid = $profil['id'];
            if($this->user_model->is_report($uid, $pid)[0] > 0){
                $profil = NULL;
            }
            else {
                $occurencetag = 0;
                $ptag = $this->tag_model->get_tag($pid);
                $mytag = $this->tag_model->get_tag($uid);
                foreach ($mytag as $key => $tmptag) {
                    foreach ($ptag as $key2 => $tmptag2) {
                        if ($tmptag['nom'] == $tmptag2['nom'])
                            $occurencetag++;
                    }
                }
                $profil['pop'] = $c_user->calcul_pop($pid);
                $profil['like'] = $this->like_model->is_like($uid, $pid) ? TRUE : FALSE;
                $profil['age'] = round ((time() - strtotime($profil['date_naissance'])) / 3600 / 24 / 365);

                $img = $this->picture_model->get_user_pict($pid);
                $this->array_sort_by_column($img, 'avatar');
                $profil['images'] = isset($img[0]) ? 'assets/img/user_photo/'.$img[0]['id'].'.jpg' : 'assets/img/user_photo/defaultprofil.gif';

                $profil['distance'] = round($this->geoloc->get_distance_m($profil['lat'], $profil['lng'], $mylat, $mylng) / 1000, 1);

                $visit_me = $this->user_model->already_visit($pid, $uid) ? 1 : 0;
                $like_me = $this->like_model->like_me($uid, $pid) ? 5 : 0;
                $score_dist = round(20 - $profil['distance'], 0);
                $score_dist = $score_dist < 0 ? 0 : $score_dist;

                $profil['score'] = $occurencetag + $visit_me + $like_me + $score_dist / 2;

            }
        }
        //Clean deleted profil and order other by score
        $profils = array_filter($profils);
        $this->array_sort_by_column($profils, 'score');

        $data['profils'] = $profils;
        $this->set($data);
        $this->views('decouverte');
    }
    function like($pid){
        if($_SESSION['user']['status'] == 1){
            $this->set(array('info' => 'Vous devez avoir une photo pour effectué cet action.'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        $this->like_model->like($_SESSION['user']['id'], $pid);
        $this->notification_model->add_notification($pid, 1, $_SESSION['user']['id']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    function unlike($pid){
        if($_SESSION['user']['status'] == 1){
            $this->set(array('info' => 'Vous devez avoir une photo pour effectué cet action.'));
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        $this->like_model->unlike($_SESSION['user']['id'], $pid);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    function search_page(){
        $this->views('search');
    }
    function search(){
        if(!isset($_SESSION['user'])) {
            echo '<p><strong>Vous devez etre connecter pour effectuer une recherche</strong></p>';
            exit;
        }
        if(is_numeric($_POST['age_min']) && is_numeric($_POST['age_max'])){
            if(is_numeric($_POST['dist_min']) && is_numeric($_POST['dist_max'])){
                if(is_numeric($_POST['pop_min']) && is_numeric($_POST['pop_max'])){
                    if(preg_match("/[A-Za-z0-9 ',_àâêèéùûôç-]/", $_POST['tag']) == 1 || $_POST['tag'] == NULL){
                        $pref_s = $_SESSION['user']['orientation'];
                        $uid = $_SESSION['user']['id'];
                        $usexe = $_SESSION['user']['sexe'];
                        $mylat = $_SESSION['user']['lat'];
                        $mylng = $_SESSION['user']['lng'];
                        $c_user = $this->load_c('user');

                        $date_min = (new DateTime('now -'.$_POST["age_min"].' year'))->format('Y-m-d');
                        $date_max = (new DateTime('now -'.$_POST["age_max"].' year'))->format('Y-m-d');

                        //Recupere les profil en focntion de l'orientation
                        if($pref_s == 0) {
                            if ($usexe == 1) {
                                $profils = $this->user_model->search_profils($uid, 1, 1, $date_min, $date_max);
                                $profils = array_merge($profils, $this->user_model->search_profils($uid, 2, 2, $date_min, $date_max));
                            }
                            else {
                                $profils = $this->user_model->search_profils($uid, 1, 2, $date_min, $date_max);
                                $profils = array_merge($profils, $this->user_model->search_profils($uid, 2, 1, $date_min, $date_max));
                            }
                        }
                        else if ($pref_s == 1) {
                            if ($usexe == 1)
                                $profils = $this->user_model->search_profils($uid, 2, 2, $date_min, $date_max);
                            else
                                $profils = $this->user_model->search_profils($uid, 1, 2, $date_min, $date_max);
                        }
                        else if ($pref_s == 2) {
                            if ($usexe == 1)
                                $profils = $this->user_model->search_profils($uid, 1, 1, $date_min, $date_max);
                            else
                                $profils = $this->user_model->search_profils($uid, 2, 1, $date_min, $date_max);
                        }
                        foreach($profils AS &$profil) {
                            $pid = $profil['id'];
                            $distance = round($this->geoloc->get_distance_m($profil['lat'], $profil['lng'], $mylat, $mylng) / 1000, 1);
                            $profil['distance'] = $distance;
                            $profil['age'] = round ((time() - strtotime($profil['date_naissance'])) / 3600 / 24 / 365);
                            $profil['pop'] = $c_user->calcul_pop($pid);
                            $img = $this->picture_model->get_user_pict($pid);
                            $this->array_sort_by_column($img, 'avatar');
                            $profil['images'] = isset($img[0]) ? 'assets/img/user_photo/'.$img[0]['id'].'.jpg' : 'assets/img/user_photo/defaultprofil.gif';

                            if($distance < $_POST['dist_min'] || $distance > $_POST['dist_max']) {
                                $profil = NULL;
                            }
                            else {
                                $profil['distance'] = $distance;
                                $pop = $c_user->calcul_pop($pid);
                                if ($pop > $_POST['pop_max'] || $pop < $_POST['pop_min']){
                                    $profil = NULL;
                                }
                                else {
                                    if($_POST['tag'] != NULL) {
                                        $ptags = $this->tag_model->get_tags($pid);
                                        $tags = array_filter(explode(',', strtolower($_POST['tag'])));
                                        //echo print_r($ptags);
                                        for($i = 0; isset($tags[$i]); $i++){
                                            if($ptags == NULL || in_array(strtolower(trim($tags[$i])), $ptags) == FALSE) {
                                                $profil = NULL;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //Clean deleted profil and order other by score
                        $profils = array_filter($profils);
                        $data = json_encode(array_values($profils));
                        echo $data;
                        exit;
                    }
                }
            }
        }
        echo json_encode(FALSE);
    }
}