<?php
class Match extends VK_Controller {
    function decouverte()
    {
        $pref_s = $_SESSION['user']['orientation'];
        $uid = $_SESSION['user']['id'];
        $usexe = $_SESSION['user']['sexe'];
        if($pref_s == 0) {
            if ($usexe == 1) {
                $profils = $this->user_model->get_profils_for($uid, 1, 1);
                $profils .= $this->user_model->get_profils_for($uid, 2, 2);
            }
            else
                $profils = $this->user_model->get_profils_for($uid, 1, 2);
                $profils .= $this->user_model->get_profils_for($uid, 2, 1);
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
            $occurencetag = 0;
            $ptag = $this->tag_model->get_tag($profil['id']);
            $mytag = $this->tag_model->get_tag($uid);
            foreach ($mytag as $key => $tmptag) {
                foreach ($ptag as $key2 => $tmptag2) {
                    if ($tmptag['nom'] == $tmptag2['nom']) {
                        $occurencetag++;
                    }
                }
            }
            $img = $this->picture_model->get_user_pict($profil['id']);
            if(isset($img[0]))
                $profil['images'] = 'assets/img/user_photo/'.$img[0].'.jpg';
            else
                $profil['images'] = 'assets/img/user_photo/defaultprofil.gif';
            $profil['score'] = $occurencetag;
        }
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
}