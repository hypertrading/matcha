<?php
include_once 'core/VK_Model.php';
class Security_model extends VK_Model {
   function insert_token($user_id, $token){
       $this->db->exec("INSERT INTO `token` (`token`) VALUES ('$token')");
   }
    function token_match($token)
    {
        if($this->db->query("SELECT COUNT(id) FROM `token` WHERE token = '$token'"))
            return TRUE;
        return FALSE;
    }
    function rm_token($token){
        $this->db->exec("DELETE FROM `token` WHERE `token` = '$token'");
    }
    function valid_acount($id)
    {
        $query = "UPDATE `user` SET `status` = 1 WHERE `id`='$id'";
        if ($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
}
?>