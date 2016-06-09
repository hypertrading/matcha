<?php
class Setup_db
{
    private $HOST = 'localhost';
    private $DB_USER = 'root';
    private $DB_PASSWORD = 'root42';
    private $DB_NAME = 'matcha';
    private $CHARSET = 'utf8';

    function get_DSN()
    {
        return "mysql:host=$this->HOST;dbname=$this->DB_NAME;charset=$this->CHARSET";
    }
    function get_USER()
    {
        return $this->DB_USER;
    }
    function get_PASSWORD()
    {
        return $this->DB_PASSWORD;
    }
}
?>