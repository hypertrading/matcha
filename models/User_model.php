<?php
include_once 'core/VK_Model.php';
class User_model extends VK_Model {
    function get_one_user($pseudo){
        $query = "SELECT id, pseudo, nom, email, sexe, date_naissance, prenom, password, status, droits FROM `user` WHERE `pseudo` = '$pseudo'";
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
    public function update_last_login($id) {
        $this->db->query("UPDATE `user` SET `date_last_login`='".date('Y-m-d H:i:s')."' WHERE id=".$id);
    }
    function get_profil($pseudo) {
        $query = "SELECT id, pseudo, description, date_naissance, date_last_login  FROM `user` WHERE `pseudo` = '$pseudo'";
        if($result = $this->db->query($query))
            return $result->fetch();
        return FALSE;
    }
    function edit_description($id, $description) {
        $query = "UPDATE `user` SET `description`='$description' WHERE `id`='$id'";
        if($this->db->query($query))
            return TRUE;
        return FALSE;
    }
    function get_tag($uid){
        $query = "SELECT t.nom 
                  FROM `tag` AS t 
                  LEFT JOIN `user_tag` AS u_t 
                  ON u_t.tag_id = t.id
                  WHERE u_t.user_id = '$uid'";
        if($result = $this->db->query($query))
            return $result->fetchAll();
        return FALSE;
    }
}
?>