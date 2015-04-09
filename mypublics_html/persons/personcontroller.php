<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/31/15
 * Time: 10:19 PM
 */
include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");

class PersonController {
    public static function get_all_persons_options($selected_id=0){
        $db = DBConnector::get_db_connection();
        $sql = "SELECT id, first_name, last_name FROM persons";
        $statement = SQLAction::get_all_query($db, $sql);

        $statement->bind_result($id, $first_name, $last_name);

        $persons_options = array();

        while ($statement->fetch()) {
            if ($id == $selected_id) {
                $persons_options[] = "<option selected value='$id'>" . $first_name . " " . $last_name . "</option>";
            }
            else {
                $persons_options[] = "<option value='$id'>" . $first_name . " " . $last_name . "</option>";
            }
        }

        $db->close();
        $statement->close();

        return $persons_options;
    }

    public static function get_person_by_id($person_id){
        //Create a database connection
        $db = DBConnector::get_db_connection();
        $sql = "SELECT id, role, first_name, last_name, email, school FROM persons WHERE id = ?";

        $statement = SQLAction::get_by_id_query($db, $sql, $person_id);

        $statement->bind_result($id, $role, $first_name, $last_name, $email, $school);

        $statement->fetch();

        $person = new Person($role, $first_name, $last_name, $email, $school, "", $id);

        $statement->close();
        $db->close();
        return $person;
    }
}