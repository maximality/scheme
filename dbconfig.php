<?php
/**
 * Created by PhpStorm.
 * User: mmd
 * Date: 16.10.14
 * Time: 14:14
 */
try {
    $host = '127.0.0.1';
    $dbname = 'u0067206_building';
    $user = 'u0067_user';
    $pass = 'J4d038S1zuH5QgY';
    $DBH = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $user,
        $pass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
}
catch(PDOException $e) {
    echo $e->getMessage();
}
?>