<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 18.09.17
 * Time: 12:00§
 */
session_start();
include "connection.php";
$error = "";
$headerAddress = "Location: /index.php";

//checking cookie
if ((array_key_exists('id', $_COOKIE) && $_COOKIE['id']) && (!array_key_exists('id', $_SESSION) && !$_SESSION['id'])) {
    $_SESSION['id'] = $_COOKIE['id'];
}
if (!array_key_exists('id',$_SESSION) && !$_SESSION['id']){
    if (headers_sent() === false) {
        header($headerAddress);
    } else {
        $error .= "<p>Headers have sent</p>";
        print_r($_SESSION);
    }
}
//saving profile info
if (array_key_exists('save',$_POST)){
    if ($_POST['password1']==$_POST['password2']){
        $hashedPassword = password_hash($_SESSION['id'] . $_POST['password1'], PASSWORD_DEFAULT);
        $query = "UPDATE Users SET Users.username = '" . mysqli_real_escape_string($link, $_POST['username']) . "', Users.email = '" . mysqli_real_escape_string($link, $_POST['email']) . "', Users.password = '" . mysqli_real_escape_string($link, $hashedPassword) . "'  WHERE Users.idUsers = '".$_SESSION['id']."' LIMIT 1";
        mysqli_query($link,$query) or die("Couldn't save changes");
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['email'] = $_POST['email'];
    }else{
        $error .= "<p>Passwords didn't match</p>";
    }
    if ($error) {
        $error = '<div class="alert alert-danger" role="alert">' . "<p>There were errors in form: </p>" . $error . '</div>';
    }
}

include 'header.php';?>

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
        <button class="navbar-toggler navbar-toggler-right " type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="/index.php">ENote</a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li style="margin-right: 5px" class="nav-item">
                    <a href='/index.php/?logout'>
                        <button class="btn btn-outline-warning btn-block my-2 my-sm-0">Logout</button>
                    </a>
                </li>
                <li  class="nav-item active">
                    <a href="/index.php">
                        <button class="btn btn-primary btn-block my-2 my-sm-0">< Back</button>
                    </a>
                </li>

            </ul>
            <form class="form-inline my-2 my-lg-0" method="post">
                <!--            <button name='deleteNote' class='btn btn-block btn-danger my-2 my-sm-0'>Delete</button>-->
            </form>
        </div>
    </nav>

    <div id="error"><p><?php echo $error; ?></p></div>
    <div class="container">
        <h1>Profile Info</h1>
        <form id="profileInfo"  method="post">
            <div class="form-group">
                <label for="email">Username</label>
                <input type="text" name="username" placeholder="new username" class="form-control" value="<?php echo $_SESSION['username']?>">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" placeholder="new email address" class="form-control" value="<?php echo $_SESSION['email']?>">
            </div>
            <div class="form-group">
                <label for="password">New password</label>
                <input type="password" name="password1" placeholder="new password" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Confirm new password</label>
                <input type="password" name="password2" placeholder="new password" class="form-control">
            </div>
            <button class="btn btn-success" name="save">Save changes</button>
        </form>
    </div>

<?php include 'footer.php'; ?>