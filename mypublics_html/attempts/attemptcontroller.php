<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 1:49 PM
 */
include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("attempt.php");

class AttemptController {
    public static function add_attempt(Attempt $attempt) {
        //Check connection to database
        $db = DBConnector::get_db_connection();

        //Prepare the statement so we can bind data to it
        $sql = "INSERT INTO attempts (persons_id, quizzes_id, attempt_date, attempt_number, score) VALUES (?, ?, ?, ?, ?)";
        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        //Bind the parameters to the insert statement
        $statement->bind_param("iisii", $attempt->persons_id, $attempt->quizzes_id, $attempt->attempt_date, $attempt->attempt_number, $attempt->score);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $attempt_id = $db->insert_id;

        $statement->close();
        $db->close();

        return $attempt_id;
    }

    public static function update_attempt(Attempt $attempt) {
        //Check connection to database
        $db = DBConnector::get_db_connection();

        //TODO: bind this
        $sql = "SELECT * FROM attempts WHERE id=" . $attempt->id;

        $result = $db->query($sql);

        if (!($result->num_rows == 1)) {
            die("Attempt not found in the database!");
        }

        //Prepare the statement so we can bind data to it
        $sql = "UPDATE attempts SET attempt_date = ?, attempt_number = ?, score = ? WHERE id=?;";
        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        //Bind the parameters to the insert statement
        $statement->bind_param("siii", $attempt->attempt_date, $attempt->attempt_number, $attempt->score, $attempt->id);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }

    public static function delete_attempt($attempt_id) {
        //Prepare the statement so we can bind data to it
        $sql = "DELETE FROM attempts WHERE id=?;";
        SQLAction::delete_by_id_query($sql, $attempt_id);
    }

    public static function get_all_attempts() {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM attempts";

        $statement = SQLAction::get_all_query($db, $sql);

        return AttemptController::get_array_of_attempts($statement, $db);
    }

    public static function get_attempt_by_id($attempt_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM attempts WHERE id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $attempt_id);

        return AttemptController::get_array_of_attempts($statement, $db);
    }

    public static function get_attempt_by_person($person_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM attempts WHERE persons_id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $person_id);

        return AttemptController::get_array_of_attempts($statement, $db);
    }

    public static function get_attempt_count_by_person($persons_id, $quiz_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT COUNT(*) FROM attempts WHERE persons_id=? AND quizzes_id = ?";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("ii", $persons_id, $quiz_id);

        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->bind_result($count);

        $statement->fetch();

        $statement->close();
        $db->close();

        return $count;

    }

    public static function get_attempt_by_quiz($quiz_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM attempts WHERE quizzes_id=?;";

        $statement = SQLAction::get_by_id_query($db, $sql, $quiz_id);

        return AttemptController::get_array_of_attempts($statement, $db);
    }

    private static function get_array_of_attempts($statement, $db) {
        $statement->bind_result($id, $person_id, $quiz_id, $attempt_date, $attempt_number, $score);

        $attempts = array();

        while ($statement->fetch()) {
            $attempts[] = new Attempt($person_id, $quiz_id, $attempt_date, $attempt_number, $score, $id);
        }

        $db->close();
        $statement->close();

        return $attempts;
    }

    public static function get_table_header() {
        return "<tr><th>ID</th><th>Person ID</th><th>Quiz ID</th><th>Attempt Date</th>" .
        "<th>Attempt Number</th><th>Score</th></tr>";
    }
}