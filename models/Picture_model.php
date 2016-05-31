<?php
include_once 'core/VK_Model.php';
class Picture_model extends VK_Model {
    function add_picture($uid){
        $query = "INSERT INTO `user_picture` (`user_id`) VALUES ($uid)";
        $this->db->query($query);
        $last_id = $this->db->lastInsertId();
        return $last_id;
    }
    function get_user_pict($id){
        $query = "SELECT `id` FROM `user_picture` WHERE `user_id` = $id";
        return $this->db->query($query)->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>