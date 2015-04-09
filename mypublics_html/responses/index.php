<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 2/10/15
 * Time: 4:23 PM
 */
include_once("response.php");
include_once("../../templates/tabletemplate.php");

session_start();

if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: /../login.php");
    exit;
}

/*Example table stuff
$table = new TableTemplate("Heading!", "Lessons.php");
$table->set_table_header(Response::get_table_header());

$responses = Response::get_response_by_option(7);

foreach ($responses as $r) {
    $table->add_table_data($r->to_table_row());
}

echo $table->get_final_page();
*/
?>