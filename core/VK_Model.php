<?php
class VK_Model {
    public $db;
    function __construct()
    {
        try
        {
            require_once 'config/database.php';
            $setup_db = new setup_db();
            $this->db = new PDO($setup_db->get_DSN(), $setup_db->get_USER(), $setup_db->get_PASSWORD());
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $e)
        {
            die('PDO Erreur : '.$e->getMessage());
        }
    }
}
?>