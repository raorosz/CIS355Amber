<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 12:29 AM
 */

include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("question.php");

class QuestionController {
    public static function get_question_by_id($questions_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM questions WHERE id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $questions_id);

        $statement->bind_result($id, $quizzes_id, $question);

        $statement->fetch();

        $question = new Question($quizzes_id, $question, $id);

        return $question;
    }

    public static function get_questions_by_quizzes_id($quiz_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM questions WHERE quizzes_id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $quiz_id);

        $statement->bind_result($id, $quizzes_id, $question);

        $questions = array();

        while ($statement->fetch()) {
            $questions[] = new Question($quizzes_id, $question, $id);
        }

        $statement->close();
        $db->close();

        return $questions;
    }

    public static function add_question(Question $question) {
        $db = DBConnector::get_db_connection();
        $sql = "INSERT INTO questions (quizzes_id, question) VALUES (?, ?)";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("is", $question->quizzes_id, $question->question);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        //Get the auto-created ID
        $questions_id = $db->insert_id;

        $statement->close();
        $db->close();

        return $questions_id;
    }

    public static function update_question(Question $question) {
        $db = DBConnector::get_db_connection();
        $sql = "UPDATE questions SET question=? WHERE id=?";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        //Bind the parameters to the insert statement
        $statement->bind_param("si", $question->question, $question->id);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }

    public static function delete_question($questions_id){
        $sql = "DELETE FROM questions WHERE id=?";

        SQLAction::delete_by_id_query($sql, $questions_id);
    }

    public static function get_table_header() {
        return "<tr><th>Question</th><th>Actions</th></tr>";
    }
}