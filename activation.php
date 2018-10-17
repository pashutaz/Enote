<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 25.09.17
 * Time: 11:13
 */
$msg = '';
if (isset($_GET['code']) && !empty($_GET['code'])){
    include 'database.php';
    $db = new Database();
    $query = "SELECT * FROM Users WHERE verification = '". mysqli_real_escape_string($db->mysqli, $_GET['code']) ."' ";
    $result = $db->do_query($query);
    if (!empty(mysqli_num_rows($result))){
        $row = mysqli_fetch_array($result);
        if (!$row['status'] ){
            $query = "UPDATE Users SET status = TRUE WHERE verification = '". mysqli_real_escape_string($db->mysqli, $_GET['code']) ."'";
            $db->do_query($query);
            $msg .= "<p>Email verified</p>";
        }else{
          $msg .= "<p>This Email already verified</p>";
        }
    }else{
        $msg .= "<p>Wrong activation link</p>";
    }
}
include "header.php";
?>

<?php if (!empty($msg)){echo "<div class=\"alert alert-info\" role=\"alert\">".$msg."</div>";}?>

<?php include "footer.php";?>