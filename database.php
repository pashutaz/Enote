<?php
/**
 * Created by PhpStorm.
 * User: pazay
 * Date: 27.07.2017
 * Time: 22:39
 */

class Database
{
    public $mysqli;
    private $host;
    private $username;
    private $password;
    private $database;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $this->host = 'localhost';
        $this->username = 'pashutaz';
        $this->password = 'qwerty123';
        $this->database = 'mydb';

        $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->database);
        if (mysqli_connect_error()) {
            die(mysqli_errno($this->mysqli) . mysqli_connect_error());
        }

        return $this->mysqli;
    }

    public function do_query($query)
    {
        return mysqli_query($this->mysqli, $query);
    }
}