<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 12:37 PM
 */

include_once("report.php");
include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");

class ReportController {
    public static function get_all_reports() {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM reports";
        $statement = SQLAction::get_all_query($db, $sql);

        $statement->bind_result($id, $reportname, $reportquery, $persons_id, $date_created);

        $reports = array();

        while ($statement->fetch()) {
            $reports[] = new Report($reportname, $reportquery, $persons_id, $date_created, $id);
        }

        $db->close();
        $statement->close();

        return $reports;
    }

    public static function get_report_by_id($id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM reports WHERE id=?";
        $statement = SQLAction::get_by_id_query($db, $sql, $id);

        $statement->bind_result($id, $reportname, $reportquery, $persons_id, $date_created);

        $statement->fetch();

        $report = new Report($reportname, $reportquery, $persons_id, $date_created, $id);

        $db->close();
        $statement->close();

        return $report;
    }
}