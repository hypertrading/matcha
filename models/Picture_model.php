<?php
include_once 'core/VK_Model.php';
class Picture_model extends VK_Model {
    function add_picture($uid, $avatar){
        $query = "INSERT INTO `user_picture` (`user_id`, `avatar`) VALUES ($uid, $avatar)";
        $this->db->query($query);
        $last_id = $this->db->lastInsertId();
        return $last_id;
    }
    function get_user_pict($id){
        $query = "SELECT `id`, `avatar` FROM `user_picture` WHERE `user_id` = $id";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function get_one_pict($id){
        $query = "SELECT * FROM `user_picture` WHERE `id` = $id";
        return $this->db->query($query)->fetch(PDO::FETCH_ASSOC);
    }
    function rm_pict($id){
        $query = "DELETE FROM `user_picture` WHERE `id` = $id";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function set_avatar($id){
        $query = "UPDATE `user_picture` SET `avatar` = 1 WHERE `id` = $id";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function unset_avatar($id){
        $query = "UPDATE `user_picture` SET `avatar` = 0 WHERE `id` = $id";
        if($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
}
?>