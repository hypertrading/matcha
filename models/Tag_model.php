<?php
include_once 'core/VK_Model.php';
class Tag_model extends VK_Model {
    function search_tag($tag){
        $query = "SELECT * FROM `tag` WHERE `nom` = '$tag'";
        if($result = $this->db->query($query))
            return $result->fetch();
        return FALSE;
    }
    function create_tag($tag){
        $query = "INSERT INTO `tag` (`nom`) VALUES ('$tag')";
        if($this->db->query($query))
            return TRUE;
        return FALSE;
    }
    function add_tag($uid, $tag){
        $query = "SELECT `id` FROM `tag` WHERE `nom` = '$tag'";
        $id = $this->db->query($query)->fetch();
        $query = "INSERT INTO `user_tag` (`user_id`, `tag_id`) VALUES ('$uid', '".$id['id']."')";
        $this->db->query($query);
    }
    function already_tag($uid, $tag){
        $query = "SELECT t.nom 
                  FROM `tag` AS t 
                  LEFT JOIN `user_tag` AS u_t 
                  ON u_t.tag_id = t.id
                  WHERE u_t.user_id = '$uid' 
                  AND t.nom = '$tag'";
        $result = $this->db->query($query)->fetch();
        if ($result)
            return TRUE;
        return FALSE;
    }
    function remove_tag($uid, $tag){
        $query = "DELETE u_t.* FROM `user_tag` AS u_t 
                  LEFT JOIN `tag` AS t 
                  ON u_t.tag_id = t.id 
                  WHERE u_t.user_id = '$uid'
                  AND t.nom = '$tag'";
        $this->db->exec($query);
    }
}
?>