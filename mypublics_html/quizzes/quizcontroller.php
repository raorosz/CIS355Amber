<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 12:01 AM
 */

include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("quiz.php");

class QuizController {
    public static function get_quiz_by_id($quizzes_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM quizzes WHERE id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $quizzes_id);

        $statement->bind_result($id, $lessons_id, $attempts_allowed, $title, $description);

        $statement->fetch();

        $quiz = new Quiz($lessons_id, $attempts_allowed, $title, $description, $id);

        $statement->close();
        $db->close();

        return $quiz;
    }

    public static function get_quiz_by_lesson_id($lessons_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM quizzes WHERE lessons_id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $lessons_id);

        $statement->bind_result($id, $lessons_id, $attempts_allowed, $title, $description);

        $statement->fetch();

        $quiz = new Quiz($lessons_id, $attempts_allowed, $title, $description, $id);

        $statement->close();
        $db->close();

        return $quiz;
    }

    public static function add_quiz(Quiz $quiz) {
        $db = DBConnector::get_db_connection();
        $sql = "INSERT INTO quizzes (lessons_id, attempts_allowed, title, description) VALUES (?, ?, ?, ?)";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("iiss", $quiz->lessons_id, $quiz->attempts_allowed, $quiz->title, $quiz->description);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();

        return;
    }
}