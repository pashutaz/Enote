<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 28.05.17
 * Time: 15:12
 */
session_start();
include "notes.php";
$headerAddress = "Location: /usernote.php";
$row = "";
$result = "";
$notes = [];


//checking cookie
if ((array_key_exists('id', $_COOKIE) && $_COOKIE['id']) && (!array_key_exists('id', $_SESSION) && !$_SESSION['id'])) {
    $_SESSION['id'] = $_COOKIE['id'];
}
if (array_key_exists('idNote', $_SESSION)) {
    unset ($_SESSION['idNote']);
}

//checking if user logged in
if (array_key_exists('id', $_SESSION) && $_SESSION['id']) {
    $db = new Database();
    $userId = $_SESSION['id'];
    $result = $db->do_query("SELECT * FROM Users WHERE idUsers = '" . $userId . "' LIMIT 1");
    $row = mysqli_fetch_array($result);
    $username = $row['username'];
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $row['email'];
    //getting all notes
    $result = $db->do_query("SELECT * FROM Notes JOIN Users ON Users.idUsers = Notes.idUsers WHERE Users.idUsers = '" . mysqli_real_escape_string($db->mysqli, $userId) . "'");

    while ($row = mysqli_fetch_array($result)) {
        $notes[] = new Note($row['idNote']);
    }
    rsort($notes);
    $_SESSION['allNotes'] = $notes;

    //create a new note
    if (array_key_exists("submit", $_POST)) {
        //        $query = "INSERT INTO Notes(title, content, idUsers) VALUES ('" . mysqli_real_escape_string($link, 'New note') . "','" . mysqli_real_escape_string($link, 'This is your new note') . "','" . mysqli_real_escape_string($link, $_SESSION['id']) . "')";
        //        mysqli_query($link, $query);
        //        $_SESSION['idNote'] = mysqli_insert_id($link);
        $notes[] = new Note('New note', 'This is your new note', $_SESSION['id']);
        $_SESSION['allNotes'] = $notes;
        $_SESSION['idNote'] = end($notes)->getId();
//        echo $notes[array_search(end($notes),$notes)]->getTitle();
        if (headers_sent() == false) {
            header($headerAddress);
            die();
        } else {
            echo "<p>header have sent</p>";
            print_r($_POST);
        }
    }

    //editing note
    if (array_key_exists("edit", $_POST)) {

        $_SESSION['idNote'] = $_POST['edit'];

        if (headers_sent() == false) {
            header($headerAddress);
            die();
        } else {
            echo "<p>header have sent</p>";
            print_r($_POST);
        }
    }

    //deleting a note
    if (array_key_exists("deleteNote", $_POST)) {
//         $_SESSION['idNote'] = $notes[$_POST['deleteNote']]->getId();
        $notes[$_POST['deleteNote']]->delete();
        header("Refresh:0");
//        if (headers_sent() == false) {
//            header("Refresh:0");
//            die();
//        } else {
//            echo "<p>header have sent</p>";
//            print_r($_POST);
//        }
    }
//    else{
//        $_SESSION['idNote'] = mysqli_insert_id($link);
////        $_SESSION['idNote'] = $row['idNote'];
//        if (headers_sent() === false){
//            header($headerAddress);
//            die();
//        }else{
//            echo "<p>header have sent</p>";
//            print_r($_POST);
//        }
//    }fffffsdsdfsdf
} else {
    //goes to login screen
    if (headers_sent() == false) {
        header($headerAddress);
        die();
    } else {
        $error .= "<p>headers have sent</p>";
        print_r($_SESSION);
    }
}
?>
<?php include "header.php"; ?>

<!--navigation top bar-->
<nav class="navbar  navbar-toggleable-md navbar-light bg-faded">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <a class="navbar-brand" href="/index.php">ENote</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href='/index.php/?logout'>
                    <button class="btn btn-outline-warning btn-block my-2 my-sm-0">Logout</button>
                </a>
            </li>
            <li class="nav-item">
                <a href="/profile.php"><button class="btn btn-outline-info btn-block"><?php if ($_SESSION['username']!=null){echo $_SESSION['username'];}else{echo $_SESSION['email'];}?></button></a>

            </li>
            <!--<li class="nav-item">
                <a class="nav-link" onclick="Share.vkontakte('http://pashutaz-com.stackstaging.com/usernote.php','Enote','https://goo.gl/VMfWSt','Store your ideas here')"><button class="btn btn-outline-success my-2 my-sm-0" id="shareVk" style="height: 38px; width: 100px; background: url('/images/vk_com.png') no-repeat center/cover;border: none"></button>
                </a>
            </li>-->
        </ul>
        <form class="form-inline my-2 my-lg-0" method="post">
            <input type="submit" name="submit" value="New Note" class="btn btn-success btn-block my-2 my-sm-0">
        </form>
    </div>
</nav>

<a onclick="Share.vkontakte('http://pashutaz-com.stackstaging.com','Enote','https://goo.gl/VMfWSt','Store your ideas here')">
    <button id="shareVk"
            style="height: 50px; width: 50px; background: url('/images/vk_com.png') no-repeat center/cover;border: none"></button>
</a>

<a href="#">
    <img src="/images/up-white-arrow-hi.png" alt="Top"
         style="bottom: 10px; right: 10px; position: fixed; width: 50px; height: 50px; background: no-repeat center/cover">
</a>

<!--all notes-->
<form method="post">
    <!--    <input style="position: fixed; top: 0; right: 0;" type="submit" name="submit" value="New Note"-->
    <!--           class="btn btn-success">-->
    <div style="margin-top: 10px">
        <?php
        $i = 0;
        foreach ($notes as $note) {
            $id = $note->getId();
            $noteTitle = $note->getTitle();
            $noteContent = $note->getContent();
            $noteDate = $note->getDate();

            echo "<div align='center' class='editNote'>
                <button class='noteButton' id='noteButton$i' style='
                background-color: white;
                border: 1px solid green;
                color: black;
                padding: 15px 32px;
                text-align: left;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                width: 80%;'
                name='edit' value='$id' ><b>" . $noteTitle . "</b><br> <small class= 'text-muted'>" . $noteDate . "</small> " . $noteContent . "</button><button id='deleteNoteButton$i' style= 'margin-left: -50px;' name='deleteNote' value='$i' class='deleteNoteButton btn btn-danger'>X</button></div>";
            $i++;
        }
        //        while ($row = mysqli_fetch_array($result)) {
        //            $id = $row['idNote'];
        //            echo "<div align='center' class='editNote'>
        //                <button style='
        //                background-color: white;
        //                border: 1px solid green;
        //                color: black;
        //                padding: 15px 32px;
        //                text-align: left;
        //                text-decoration: none;
        //                display: inline-block;
        //                font-size: 16px;
        //                cursor: pointer;
        //                width: 40%;'
        //                name='edit' value='$id' ><b>" . $noteTitle = $row['title'] . "</b><br>" . $noteContent = $row['content'] . "</button><button  name='deleteNote' value='$id' class='btn btn-danger'>X</button></div>";
        ////            echo "<div hidden>".$noteID = $row['idNote']."</div>";
        //        }
        ?>
    </div>
</form>
<div style="margin-bottom: 5%"></div>
<?php include "footer.php"; ?>
