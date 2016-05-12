<?php
//include "../models/Model_class.php";
function init_db()
{
    //todo cree variables query d'initiation de la db
    require_once '../config/database.php';
    $setup_db = new setup_db();
    $tmp = new PDO('mysql:host=localhost;charset=utf8', $setup_db->get_USER(), $setup_db->get_PASSWORD());
    if ($tmp->exec("CREATE DATABASE IF NOT EXISTS matcha") === FALSE) {
        echo "Error creation database<br>";
        exit ();
    }
    else
    {
        echo "Connection to database camagru OK<br><hr>";
        $i = 0;
        $tmp = NULL;
        $connection = new Model_class();
/*
        if ($connection->db->exec($create_table_users) !== FALSE)
        {
            echo "Table users OK<br>";
            if ($connection->db->exec($new_user_admin) === FALSE)
                echo "Error creation admin account<br>";
            else {
                echo "Admin account OK<br>";
                $i++;
            }
        }
        else
            echo "Error creation table users<br>";

        if($i == 4) {
            echo "<hr>Init website Matcha COMPLETED<br>";
            echo "<a href='../views/home.php?page=0'>Go home</a>";
        }
        else
            echo 'Init Failure. Sorry';*/
    }
}
init_db();
?>