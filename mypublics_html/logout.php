<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 11:49 AM
 */

session_start();
//If they deliberately went to the login page, allow them to log in as someone else.
if (isset($_SESSION['persons_id'])) {
    unset($_SESSION['persons_id']);
    header("Location: lessons/index.php");
}

//Not logged in, so just send them to the log in page.
header("Location: login.php");

?>