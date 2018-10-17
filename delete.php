<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 30.05.17
 * Time: 22:30
 */
session_start();
require 'connection.php';

$query = "DELETE FROM Notes WHERE Notes.idNote = '" . $_SESSION['idNote'] . "' LIMIT 1";

mysqli_query($link, $query) or die("Could not delete this note.");

header("Location: index.php");