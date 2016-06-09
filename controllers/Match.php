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

                $mylat = $_SESSION['user']['lat'];
                $mylng = $_SESSION['user']['lng'];
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
        $this->like_model->like($_SESSION['user']['id'], $pid);
        $this->notification_model->add_notification($pid, 1);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    function unlike($pid){
        $this->like_model->unlike($_SESSION['user']['id'], $pid);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}