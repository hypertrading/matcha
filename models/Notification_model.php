<?php
include_once 'core/VK_Model.php';
class Notification_model extends VK_Model {
    function as_notification($uid) {
        $query = "SELECT * FROM `notification` WHERE `user_id` = $uid";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function add_notification($pid, $type, $from = -1) {
        $query = "INSERT INTO `notification` (`user_id`, `type`, `from_id`) VALUES ($pid, $type, '$from')";
        if ($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
    function rm_notification($uid, $type, $from = NULL){
        if ($from)
            $query = "DELETE FROM `notification` WHERE `user_id` = $uid AND `type` = $type AND `from_id` = $from";
        else
            $query = "DELETE FROM `notification` WHERE `user_id` = $uid AND `type` = $type";
        if ($this->db->exec($query))
            return TRUE;
        return FALSE;
    }
}
?>