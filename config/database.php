<?php
class Setup_db
{
//    private $DB_DSN = "mysql:host=mysql-hypertrading.alwaysdata.net;dbname=hypertrading_matcha;charset=utf8";
//    private $DB_USER = "121422";
//    private $DB_PASSWORD = "rootme42";
    
    private $DB_USER = 'root';
    private $DB_PASSWORD = '';
    private $DB_DSN = 'mysql:host=localhost;dbname=matcha;charset=utf8';

    function get_DSN()
    {
        return $this->DB_DSN;
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