<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 3:25 PM
 */

include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");
include_once("review.php");

class ReviewController {

    public static function get_review_by_id($id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM reviews WHERE id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $id);

        $statement->bind_result($id, $persons_id, $lessons_id, $title, $review, $date_submitted, $rating);

        $statement->fetch();

        $review = new Review($persons_id, $lessons_id, $title, $review, $date_submitted, $rating, $id);

        $statement->close();
        $db->close();

        return $review;
    }

    public static function get_reviews_by_lessons_id($lessons_id) {
        $db = DBConnector::get_db_connection();
        $sql = "SELECT * FROM reviews WHERE lessons_id=?";

        $statement = SQLAction::get_by_id_query($db, $sql, $lessons_id);

        return ReviewController::get_array_of_reviews($db, $statement);
    }

    public static function get_array_of_reviews($db, $statement){
        $statement->bind_result($id, $persons_id, $lessons_id, $title, $review, $date_submitted, $rating);

        $reviews = array();

        while ($statement->fetch()) {
            $reviews[] = new Review($persons_id, $lessons_id, $title, $review, $date_submitted, $rating, $id);
        }

        $db->close();
        $statement->close();

        return $reviews;
    }

    public static function add_review(Review $review) {
        //Check connection to the database
        $db = DBConnector::get_db_connection();

        $sql = "INSERT INTO reviews (persons_id, lessons_id, title, review, date_submitted, rating) VALUES (?, ?, ?, ?, ?, ?)";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("iisssi", $review->persons_id, $review->lessons_id, $review->title, $review->review, $review->date_submitted, $review->rating);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
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

    public static function update_review(Review $review){
        $db = DBConnector::get_db_connection();
        $sql = "UPDATE reviews SET title=?, review=?, rating=? WHERE id=?";

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("sssi", $review->title, $review->review, $review->rating, $review->id);

        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }

    public static function delete_review($review_id) {
        $sql = "DELETE FROM reviews WHERE id=?";
        SQLAction::delete_by_id_query($sql, $review_id);
    }

    public static function get_table_header(){
        return "<tr><th>ID</th><th>Reviewer</th><th>Title</th><th>Review</th><th>Date Submitted</th>" .
            "<th>Rating</th><th></th></tr>";
    }
}