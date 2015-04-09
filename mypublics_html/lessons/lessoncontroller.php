<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/31/15
 * Time: 10:06 PM
 */

include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("lesson.php");

class LessonController {
    public static function get_lesson_by_id($id) {
        //Connect to database
        $db = DBConnector::get_db_connection();
        $sql = "SELECT id, title, subjects, description, resources, date_created, persons_id, search_field FROM lessons WHERE id = ?";

        $statement = SQLAction::get_by_id_query($db, $sql, $id);

        $statement->bind_result($lessons_id, $title, $subject, $description, $resources, $date_created, $persons_id, $search_field);

        $statement->fetch();

        $lesson = new Lesson($title, $subject, $description, $resources, $persons_id, $date_created, $search_field, $lessons_id);

        return $lesson;
    }

    public static function get_all_lessons() {
        //Get DB Connection
        $db = DBConnector::get_db_connection();
        //Cannot return everything here because of memory limitations.
        $sql = "SELECT id, title, subjects, description, date_created, persons_id, search_field FROM lessons";
        $statement = SQLAction::get_all_query($db, $sql);

        return LessonController::get_array_of_lessons($db, $statement);
    }

    public static function add_lesson(Lesson $lesson) {
        //Check connection to database
        $db = DBConnector::get_db_connection();

        //Prepare the statement so we can bind data to it
        $sql = "INSERT INTO lessons (title, subjects, description, resources, persons_id, date_created, search_field)  VALUES (?, ?, ?, ?, ?, ?, ?)";
        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        //Bind the parameters to the insert statement
        $statement->bind_param("ssssiss", $lesson->title, $lesson->subject, $lesson->description,
            $lesson->resources, $lesson->persons_id, $lesson->date_created, $lesson->search_field);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }

    public static function update_lesson(Lesson $lesson) {
        $db = DBConnector::get_db_connection();
        $sql = "UPDATE lessons SET title=?, subjects=?, description=?, resources=?, persons_id=?, search_field=? WHERE id=?;";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        //Bind the parameters to the insert statement
        $statement->bind_param("ssssisi", $lesson->title, $lesson->subject, $lesson->description,
            $lesson->resources, $lesson->persons_id, $lesson->search_field, $lesson->id);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }

    public static function delete_lesson($lesson_id) {
        //Prepare the statement so we can bind data to it
        $sql = "DELETE FROM lessons WHERE id=?;";
        SQLAction::delete_by_id_query($sql, $lesson_id);
    }

    public static function get_array_of_lessons($db, $statement) {
        $statement->bind_result($id, $title, $subject, $description, $date_created, $persons_id, $search_field);

        $lessons = array();

        while ($statement->fetch()) {
            $lessons[] = new Lesson($title, $subject, $description, "", $persons_id, $date_created, $search_field, $id);
        }

        $db->close();
        $statement->close();

        return $lessons;
    }

    public static function get_table_header(){
        return "<tr><th>ID</th><th>Title</th><th>Subject</th><th>Description</th><th>Date Created</th><th></th></tr>";
    }
}