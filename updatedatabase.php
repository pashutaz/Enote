<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 12.05.17
 * Time: 1:35
 */
session_start();
include 'notes.php';
if (array_key_exists('title', $_POST)) {

    include "connection.php";

    $query = "UPDATE `Notes` SET  Notes.`title` = '" . mysqli_real_escape_string($link, $_POST['title']) . "' WHERE Notes.`idNote` = " . mysqli_real_escape_string($link, $_SESSION['idNote']) . " LIMIT 1";
    mysqli_query($link, $query);
}

if (array_key_exists('content', $_POST)) {
    include "connection.php";

    $query = "UPDATE `Notes` SET  Notes.`content` = '" . mysqli_real_escape_string($link, $_POST['content']) . "' WHERE Notes.`idNote` = " . mysqli_real_escape_string($link, $_SESSION['idNote']) . " LIMIT 1";
    mysqli_query($link, $query);

//    if (mysqli_query($link, $query)){
//        print_r($_SESSION);
//    }else{
//        echo "shit";
//    }
}
