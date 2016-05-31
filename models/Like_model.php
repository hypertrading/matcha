<?php
include_once 'core/VK_Model.php';
class Like_model extends VK_Model {
    function like($id, $user_liked){
        $query = "INSERT INTO `likes` (`user_like`, `user_liked`) VALUES ($id, $user_liked)";
        $this->db->query($query);
    }
    function unlike($id, $user_liked){
        $query = "DELETE FROM `likes` WHERE `user_like` = $id AND `user_liked` = $user_liked";
        $this->db->exec($query);
    }
    function is_like($uid, $pid){
        $query = "SELECT COUNT(*) FROM `likes` WHERE `user_like` = $uid AND `user_liked` = $pid";
        return $this->db->query($query)->fetchColumn();
    }
}
?>