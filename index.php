<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 07.05.17
 * Time: 14:52
 */
session_start();
$error = "";
$headerAddress = "Location: /allnotes.php";

//user logout
if (array_key_exists('logout', $_GET)){
    setcookie('id', '', time() - 60*60*24*365,'/');
//    $_COOKIE['id'] = '';
//    unset($_COOKIE);
    // remove all session variables
    session_unset();
    // destroy the session
    //    session_destroy();
}
//echo '<p>session:'.$_SESSION['id']."</p>";
//echo '<p>cookie:'.$_COOKIE['id'].'</p>';

if ((array_key_exists('id', $_SESSION) and $_SESSION['id']) or (array_key_exists('id', $_COOKIE) and $_COOKIE['id'])) {

    if (headers_sent() === false) {
        header($headerAddress);
        die();
    } else {
        $error .= "<p>header had sent</p>";
        print_r($_POST);
    }

}

//form submitted
if (array_key_exists("submit",$_POST)){

    $email = mysqli_real_escape_string($link,$_POST['email']);

    if (!$_POST['email']){
        $error .= "<p>Enter email</p>";
    }

    if (!$_POST['password']){
        $error .= "<p>Enter password</p>";
    }

    if (!$error){

        include "connection.php";

        $query = "SELECT * FROM Users WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "' LIMIT 1";

        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $userID = $row['idUsers'];

//      sign in
        if (mysqli_num_rows($result) != 1 and $_POST['login'] == '0') {

            $verifyHash = password_hash($email.time(),PASSWORD_DEFAULT);
            $hashedPassword = password_hash($userID . $_POST['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO Users(email,password,verification) VALUES ('" . mysqli_real_escape_string($link, $_POST['email']) . "','" . mysqli_real_escape_string($link, $hashedPassword) . "', '". $verifyHash ."')";
            if(mysqli_query($link, $query)){
                $userID = mysqli_insert_id($link);
                $_SESSION['id'] = $userID;
                if (array_key_exists("stayLoggedIn", $_POST)){
                    setcookie('id','$userID',time() + 60*60*24*365,'/');
                }

                if (headers_sent() === false){
                    header($headerAddress);
                    die();
                }else{
                    $error .= "<p>header have sent</p>";
                    print_r($_POST);
                }

            }else{
                $error .= "<p>Could not sign you up</p>";
            }
//            login  email not found
        } elseif (mysqli_num_rows($result) != 1) {

            $error = "<p>There is no such email, please sign up first.</p>";

//            login
        } elseif ($_POST['login'] == '1') {

            if (password_verify($userID.$_POST['password'], $row['password'])) {

                $_SESSION['id'] = $userID;
                if (array_key_exists("stayLoggedIn", $_POST)) {
                    setcookie('id', '$userID', time() + 60 * 60 * 24 * 365,'/');
                }

                if (headers_sent() === false) {
                    header($headerAddress);
                    die();
                } else {
                    $error .= "<p>headers have sent</p>";
                    print_r($_POST);
                }
            } else {
                $error .= "<p>Wrong password</p>";
            }
//            sign in taken email
        }else{

            $error .= "<p>This email already taken, try to login</p>";

        }
    }

    //return errors
    if ($error){
        $error = '<div class="alert alert-danger" role="alert">' . "<p>There were errors in form: </p>" . $error . '</div>';
    }
}
?>

<?php include "header.php" ?>

<div class="container">
    <h1>ENote</h1>
    <p><strong>Store your ideas here</strong></p>
    <div id="error"><p><?php echo $error; ?></p></div>
    <form id="signUpForm" method="post">
        <p>Interested? Sign Up!</p>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" placeholder="email address" class="form-control">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="password" class="form-control">
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" name="stayLoggedIn" value="1">
                Stay logged in?
            </label>
        </div>
        <input type="hidden" name="login" value="0">
        <input type="submit" name="submit" value="Sign Up" class="btn btn-success">
        <!--            <p><a class="toggleForms">Log in?</a></p>-->
        <div class="form-group">
            <button type="button" class="btn btn-link toggleForms">Log In?</button>
        </div>
    </form>

    <form id="logInForm" method="post">
        <p>Log in using your Email below</p>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" placeholder="email address" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="password" class="form-control">
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" name="stayLoggedIn" value="1">
                Stay logged in?
            </label>
        </div>
        <input type="hidden" name="login" value="1">
        <input type="submit" name="submit" value="Log In" class="btn btn-success">
        <!--            <p><a class="toggleForms">Sign up?</a></p>-->
        <div class="form-group">
            <button type="button" class="btn btn-link toggleForms">Sign Up?</button>
        </div>

    </form>

</div>

<footer>
    <p style="margin-right: 10px">
        <small>Created by <a href="https://github.com/pashutaz">Pashutaz</a></small>
    </p>
</footer>

<?php include "footer.php" ?>


