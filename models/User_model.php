<?php
include_once 'core/VK_Model.php';
class User_model extends VK_Model {
    function get_one_user($pseudo){
        $query = "SELECT id, pseudo, nom, prenom, password, email, sexe, orientation, date_naissance, status, droits FROM `user` WHERE `pseudo` = '$pseudo'";
        if($result = $this->db->query($query))
            return $result->fetch(PDO::FETCH_ASSOC);
        return FALSE;
    }
    function insert_user($data) {
        extract($data);
        $password = hash('whirlpool', $password);
        $date_register = date("Y-m-d H:i:s");
        $query = "INSERT INTO `user`
                  (`nom`, `prenom`, `pseudo`, `email`, `date_naissance`, `password`, `sexe`, `date_register`)
                  VALUES ('$nom', '$prenom', '$pseudo', '$email', '$date_naissance', '$password', '$sexe', '$date_register')";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function update_last_login($id) {
        $this->db->query("UPDATE `user` SET `date_last_login`='".date('Y-m-d H:i:s')."' WHERE id=".$id);
    }
    function get_profil_min($id){
        $query = "SELECT nom, prenom FROM `user` WHERE `id` = $id";
        if($result = $this->db->query($query))
            return $result->fetch(PDO::FETCH_ASSOC);
        return FALSE;
    }
    function get_profil($id) {
        $query = "SELECT id, pseudo, nom, prenom, description, localisation, date_naissance, date_last_login  FROM `user` WHERE `id` = $id";
        if($result = $this->db->query($query))
            return $result->fetch(PDO::FETCH_ASSOC);
        return FALSE;
    }
    function edit_description($id, $description) {
        $query = "UPDATE `user` SET `description`='$description' WHERE `id`='$id'";
        if($this->db->query($query))
            return TRUE;
        return FALSE;
    }
    function get_profils_for($uid, $sexe, $orientation){
        $query = "SELECT id, prenom, nom, date_naissance
                  FROM `user`
                  WHERE status = 1
                  AND id <> $uid
                  AND `sexe`= $sexe
                  AND `orientation` <> $orientation";
        $result = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function already_visit($uid, $id_visited){
        $query = "SELECT * FROM `visit` WHERE `user_visited` = $id_visited AND `user_visit` = $uid";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function log_visit($uid, $id_visited){
        $query = "INSERT INTO `visit` (`user_visited`, `user_visit`) VALUES ($id_visited, $uid)";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function get_visit($id){
        $query = "SELECT v.date, v.user_visit
                  FROM `visit` AS v
                  LEFT JOIN `user` AS u
                  ON v.user_visited = u.id
                  WHERE u.id = $id
                  ORDER BY `date` DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function user_ping($uid){
        $time = time();
        print_r($time);
        $query = "REPLACE INTO `user_log`(`user_id`) VALUES ($uid)";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function is_online($pid){
        $query = "SELECT EXISTS (SELECT 1 FROM `user_log` WHERE `user_id` = $pid)";
        return $this->db->query($query)->fetch(PDO::FETCH_NUM);
    }
    function set_offline($uid){
        $query = "DELETE FROM `user_log` WHERE `user_id` = $uid";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function report($pid, $uid, $type){
        $query = "INSERT INTO `user_report` (`user_reported`, `user_report`, `type`) VALUES ($pid, $uid, $type)";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function is_report($uid, $pid){
        $query = "SELECT COUNT(*) FROM `user_report` WHERE `user_report` = $uid AND `user_reported` = $pid";
        return $this->db->query($query)->fetch();
    }
}
?>