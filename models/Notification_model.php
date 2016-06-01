<?php
include_once 'core/VK_Model.php';
class Notification_model extends VK_Model {
    function as_notification($uid) {
        $query = "SELECT count(*) FROM `notification` WHERE `user_id` = $uid";
        return $this->db->query($query)->fetchColumn();
    }
    function add_notification($pid) {
        $query = "INSERT INTO `notification` (`user_id`) VALUES ($pid)";
        if ($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function rm_notification($uid){
        $query = "DELETE FROM `notification` WHERE `user_id` = $uid";
        if ($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
}
?>