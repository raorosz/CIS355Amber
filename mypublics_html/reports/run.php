<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 1:02 PM
 */

include_once("report.php");
include_once("reportcontroller.php");
include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("../../templates/tabletemplate.php");

session_start();

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

$report = ReportController::get_report_by_id($_POST['reports']);

$db = DBConnector::get_db_connection();
$sql = $report->reportquery;

if ($statement = $db->query($sql)) {
    $fields = $statement->fetch_fields();
    $tableheader = "<tr><th>";

    foreach ($fields as $f) {
        $tableheader = $tableheader . $f->name . "</th><th>";
    }

    $tableheader = $tableheader . "</th></tr>";

    $table = new TableTemplate($report->reportname);

    $table->set_table_header($tableheader);

    while ($row = $statement->fetch_assoc()) {
        $tablerow = "<tr><td>";
        foreach($row as $r) {
            $tablerow = $tablerow . $r . "</td><td>";
        }
        $tablerow = $tablerow . "</td></tr>";
        $table->add_table_data($tablerow);
    }

    echo $table->get_final_page();
}

$statement->close();
$db->close();
