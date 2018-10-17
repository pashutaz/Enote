<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 07.05.17
 * Time: 22:29
 */
define('host', 'localhost');
define('username', 'pashutaz');
define('password', 'qwerty123');
define('database', 'mydb');

$link = mysqli_connect(host, username, password, database);

if (mysqli_connect_error()){

    die(mysqli_errno($link).mysqli_connect_error());

}
//echo 'connect successful';

