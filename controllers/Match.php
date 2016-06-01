<?php
class Match extends VK_Controller {
    function decouverte()
    {
        if(!isset($_SESSION['user'])){
            $this->set(array('info' => 'Vous devez etre connecter pour acceder à cet page'));
            header('Location: '.$this->base_url());
            exit;
        }
        $pref_s = $_SESSION['user']['orientation'];
        $uid = $_SESSION['user']['id'];
        $usexe = $_SESSION['user']['sexe'];

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

        //Pour chaque profil, calcule le score de match et ajoute des infos (img, like)
        foreach ($profils as &$profil) {
            $pid = $profil['id'];
            $occurencetag = 0;

            $ptag = $this->tag_model->get_tag($pid);
            $mytag = $this->tag_model->get_tag($uid);
            foreach ($mytag as $key => $tmptag) {
                foreach ($ptag as $key2 => $tmptag2) {
                    if ($tmptag['nom'] == $tmptag2['nom']) {
                        $occurencetag++;
                    }
                }
            }
            $visit = $this->user_model->already_visit($pid, $uid) ? 2 : 0;
            $like_me = $this->like_model->like_me($uid, $pid) ? 5 : 0;
            $img = $this->picture_model->get_user_pict($pid);
            $profil['images'] = isset($img[0]) ? 'assets/img/user_photo/'.$img[0].'.jpg' : 'assets/img/user_photo/defaultprofil.gif';
            $profil['like'] = $this->like_model->is_like($uid, $pid) ? TRUE : FALSE;
            $profil['score'] = $occurencetag + $visit + $like_me;

            //echo $profil['nom'].' '.$profil['score'].'<br>';

        }

        //ordonne les profils pour afficher les meilleurs en premiers
        function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
            $sort_col = array();
            foreach ($arr as $key=> $row) {
                $sort_col[$key] = $row[$col];
            }
            array_multisort($sort_col, $dir, $arr);
        }
        array_sort_by_column($profils, 'score');
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