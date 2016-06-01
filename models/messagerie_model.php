<?php
include_once 'core/VK_Model.php';
class Messagerie_model extends VK_Model {
    function get_conv($uid, $pid){
        $query = "SELECT *
                  FROM `message`
                  WHERE (`from_id` = $uid OR `from_id` = $pid)
                  AND (`to_id` = $uid OR `to_id` = $pid)";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function get_new_message($uid, $pid){
        $query = "SELECT *
                  FROM `message`
                  WHERE  `from_id` = $pid
                  AND `to_id` = $uid
                  AND see = 0";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
    function set_msg_read($id_m, $uid){
        $query = "UPDATE `message` SET `see` = 1 WHERE `id` = $id_m AND `from_id` <> $uid";
        return $this->db->exec($query);
    }
    function send_msg($uid, $pid, $msg){
        $query = "INSERT INTO `message` (`from_id`, `to_id`, `message`) VALUES ($uid, $pid, '$msg')";
        return $this->db->exec($query);
    }
}
?>