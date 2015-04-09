<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/24/15
 * Time: 7:07 PM
 */

include_once("lesson.php");
include_once("lessoncontroller.php");
include_once("../../templates/tabletemplate.php");


session_start();

$authenticated = false;
if (isset($_SESSION['persons_id'])) {
    $authenticated = true;
}

$_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];

$table = new TableTemplate("Lessons", $authenticated);
$table->set_table_header(LessonController::get_table_header());

$lessons = LessonController::get_all_lessons();

foreach ($lessons as $r) {
    $table->add_table_data($r->to_table_row($authenticated));
}

echo $table->get_final_page();

?>