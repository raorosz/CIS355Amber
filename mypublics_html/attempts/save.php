<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 2:07 PM
 */
include_once("attempt.php");
include_once("attemptcontroller.php");
include_once("../responses/response.php");
include_once("../responses/responsecontroller.php");


session_start();

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

$attempt_number = AttemptController::get_attempt_count_by_person($_SESSION['persons_id'], $_POST['quiz_id']) + 1;

//TODO: Actually calculate score
$attempt = new Attempt($_SESSION['persons_id'], $_POST['quiz_id'], date('Y-m-d H:i:s'), $attempt_number, 100);

$attempt_id = AttemptController::add_attempt($attempt);

//Remove the quiz ID from post so iteration is easy.
unset($_POST['quiz_id']);

foreach ($_POST as $q => $r) {
    $response = new Response($q, $attempt_id, $r);
    ResponseController::add_response($response);
}

header("Location: ../lessons/index.php");