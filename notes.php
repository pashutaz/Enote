<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 26.07.2017
 * Time: 20:22
 */
include 'database.php';

class Note
{
    private $id;
    private $title;
    private $content;
    private $userId;
    private $date;

    public function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
    }

    public function __construct1($id)
    {
        $this->id = $id;
        $db = new Database();
        $result = $db->do_query("SELECT * FROM Notes WHERE idNote = $this->id");
        $row = mysqli_fetch_array($result);
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->date = $row['date'];
        $this->userId = $row['idUsers'];
    }

    public function __construct3($title, $content, $userId)
    {
        $this->title = $title;
        $this->content = $content;
        $this->userId = $userId;
        $this->date = date("d.m.y");

        $dateObj = new DateTime($this->date);

        $db = new Database();
        $db->do_query("INSERT INTO Notes(title,content,date,idUsers) VALUES ('" . mysqli_real_escape_string($db->mysqli, $this->title) . "','" . mysqli_real_escape_string($db->mysqli, $this->content) . "','" . mysqli_real_escape_string($db->mysqli, date_format($dateObj, 'y-m-d')) . "','" . mysqli_real_escape_string($db->mysqli, $userId) . "')");
        $this->id = mysqli_insert_id($db->mysqli);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $db = new Database();
        $db->do_query("UPDATE `Notes` SET  Notes.`title` = '" . mysqli_real_escape_string($db->mysqli, $title) . "' WHERE Notes.`idNote` = " . mysqli_real_escape_string($db->mysqli, $this->id) . " LIMIT 1");
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        $db = new Database();
        $db->do_query("UPDATE `Notes` SET  Notes.`title` = '" . mysqli_real_escape_string($db->mysqli, $content) . "' WHERE Notes.`idNote` = " . mysqli_real_escape_string($db->mysqli, $this->id) . " LIMIT 1");
    }

    public function getDate()
    {
        $date = new DateTime($this->date);
        return date_format($date, 'd.m.y');
    }

    public function delete()
    {
        $db = new Database();
        $db->do_query("DELETE FROM Notes WHERE Notes.idNote = '" . $this->id . "' ");
    }
}