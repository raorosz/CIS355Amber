<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 2/8/15
 * Time: 3:41 PM
 */
include_once("attempt.php");
include_once("attemptcontroller.php");
include_once("../../templates/tabletemplate.php");

session_start();

//The id of the review to update
$id = null;
if ( !empty($_GET['quiz_id'])) {
    $id = $_REQUEST['quiz_id'];
}

//If no ID set, just reroute them back to list
if ( $id == null ) {
    $attempts = AttemptController::get_all_attempts();
}
else {
    $attempts = AttemptController::get_attempt_by_quiz($id);
}

if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
    exit;
}

$table = new TableTemplate("Attempts");
$table->set_table_header(AttemptController::get_table_header());

foreach ($attempts as $a) {
    $table->add_table_data($a->to_table_row());
}

echo $table->get_final_page();

?>