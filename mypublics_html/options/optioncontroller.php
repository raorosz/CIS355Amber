<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 12:53 AM
 */

include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("option.php");

class OptionController {
    public static function get_option_by_id($option_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM options WHERE id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $option_id);

        $statement->bind_result($id, $question_id, $option_text, $correct_option);

        $statement->fetch();

        $option = new Option($question_id, $option_text, $correct_option, $id);

        $statement->close();
        $db->close();

        return $option;
    }

    public static function get_options_by_question_id($question_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM options WHERE question_id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $question_id);

        $statement->bind_result($id, $questions_id, $option_text, $correct_option);

        $options = array();

        while ($statement->fetch()) {
            $options[] = new Option($questions_id, $option_text, $correct_option, $id);
        }

        $statement->close();
        $db->close();

        return $options;
    }

    //Do not define this one as taking class type Option so it doesn't blow up!
    //Kablam!!!*($%*(RGU VW$TUIDF)***
    public static function add_option($o) {
        $db = DBConnector::get_db_connection();
        $sql = "INSERT INTO options (question_id, `option_text`, correct_option) VALUES (?, ?, ?)";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("iss", $o->question_id, $o->option_text, $o->correct_option);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }
}