<?php
include_once 'core/VK_Model.php';
class User_model extends VK_Model {
    function get_one_user($email){
        $query = "SELECT * FROM `user` WHERE `email` = '$email'";
        if($result = $this->db->query($query))
            return $result->fetch();
        return FALSE;
    }

    function insert_user($data)
    {
        extract($data);
        $password = hash('whirlpool', $password);
        $date_register = date("Y-m-d H:i:s");
        $query = "INSERT INTO `user`
                  (`nom`, `prenom`, `email`, `date_naissance`, `password`, `sexe`, `date_register`)
                  VALUES ('$nom', '$prenom', '$email', '$date_naissance', '$password', '$sexe', '$date_register')";
        if($this->db->query($query))
            return TRUE;
        return FALSE;
    }
}
?>