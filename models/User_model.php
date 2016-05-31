<?php
include_once 'core/VK_Model.php';
class User_model extends VK_Model {
    function get_one_user($pseudo){
        $query = "SELECT id, pseudo, nom, email, sexe, orientation, date_naissance, prenom, password, status, droits FROM `user` WHERE `pseudo` = '$pseudo'";
        if($result = $this->db->query($query))
            return $result->fetch();
        return FALSE;
    }

    function insert_user($data) {
        extract($data);
        $password = hash('whirlpool', $password);
        $date_register = date("Y-m-d H:i:s");
        $query = "INSERT INTO `user`
                  (`nom`, `prenom`, `pseudo`, `email`, `date_naissance`, `password`, `sexe`, `date_register`)
                  VALUES ('$nom', '$prenom', '$pseudo', '$email', '$date_naissance', '$password', '$sexe', '$date_register')";
        if($this->db->query($query))
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
        $query = "SELECT id, pseudo, nom, prenom, description, date_naissance, date_last_login  FROM `user` WHERE `id` = $id";
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
    function log_visit($uid, $id_visited){
        $query = "INSERT INTO `visit` (`user_visited`, `user_visit`) VALUES ($id_visited, $uid)";
        $this->db->query($query);
    }
    function get_visit($id){
        $query = "SELECT v.date, v.user_visit
                  FROM `visit` AS v
                  LEFT JOIN `user` AS u
                  ON v.user_visited = u.id
                  WHERE u.id = $id";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function as_notification($uid){
        $query = "SELECT count(*) FROM `notification` WHERE `user_id` = $uid";
        return $this->db->query($query)->fetchColumn();
    }
}
?>