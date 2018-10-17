<?php
/**
 * Created by PhpStorm.
 * User: pashutaz
 * Date: 07.05.17
 * Time: 23:02
 */
session_start();
include "notes.php";
$headerAddress = "Location: /index.php";
$error = "";
$diaryTitle = '';
$diaryContent = '';


if ((array_key_exists('id', $_COOKIE) && $_COOKIE['id']) && (!array_key_exists('id', $_SESSION) && !$_SESSION['id'])) {
    $_SESSION['id'] = $_COOKIE['id'];
}

if (!array_key_exists('idNote', $_SESSION)) {

    if (headers_sent() == false) {
        header($headerAddress);
        die();
    } else {
        $error .= "<p>headers have sent</p>";
        print_r($_SESSION);
    }

}

if ((array_key_exists('id', $_SESSION) && $_SESSION['id']) && (array_key_exists('idNote', $_SESSION) && $_SESSION['idNote'])) {
    $notes = $_SESSION['allNotes'];
//        print_r($_SESSION);

    $db = new Database();
    $query = "SELECT Notes.title,Notes.content FROM Notes WHERE Notes.idNote = '" . mysqli_real_escape_string($db->mysqli, $_SESSION['idNote']) . "' LIMIT 1";
    $row = mysqli_fetch_array($db->do_query($query));

    $diaryTitle = $row['title'];
    $diaryContent = $row['content'];

    if (array_key_exists('deleteNote', $_POST)) {
        //        include "notes.php";
        $db->do_query("DELETE FROM Notes WHERE Notes.idNote = '" . $_SESSION['idNote'] . "' ");
        if (headers_sent() == false) {
            header($headerAddress);
            die();
        } else {
            $error .= "<p>headers have sent</p>";
            print_r($_SESSION);
        }
        //      $notes[$_POST['deleteNote']]->delete();
    }
} else {
    if (headers_sent() == false) {
            header($headerAddress);
            die();
        }else {
            $error .= "<p>headers have sent</p>";
            print_r($_SESSION);
        }
    }
echo "<p id='hidden'>You logged in! <a href='/index.php/?logout=1'>logout?</a></p>";
?>
<?php include "header.php"; ?>

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
        <button class="navbar-toggler navbar-toggler-right " type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="/index.php">ENote</a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li style="margin-right: 5px" class="nav-item active">
                    <a href="/index.php">
                        <button class="btn btn-primary btn-block my-2 my-sm-0">< Back</button>
                    </a>
                </li>
                <li class="nav-item">
                    <a href='/index.php/?logout'>
                        <button class="btn btn-outline-warning btn-block my-2 my-sm-0">Logout</button>
                    </a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="post">
                <button name='deleteNote' class='btn btn-block btn-danger my-2 my-sm-0'>Delete</button>
            </form>
        </div>
    </nav>

<div class="container-fluid">
    <textarea id="title" name="diaryTitle" rows="1" cols="164"
              style="width: 90%;font-size: 40px;  margin: 5px auto 5px;" title="Your Title" placeholder="Title"
              class="form-control"><?php echo $diaryTitle; ?></textarea>
    <textarea id="diary" name="diaryText" title="Your Diary" placeholder="Your Note goes here"
              class="form-control"><?php echo $diaryContent; ?></textarea>
</div>

<?php include "footer.php"; ?>
