<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/14/15
 * Time: 11:24 PM
 */

abstract class SQLAction {
    public static function get_by_id_query($db, $sql, $id) {

        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        $statement->bind_param("i", $id);

        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        return $statement;
    }

    public static function get_all_query($db, $sql) {
        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        return $statement;
    }

    public static function delete_by_id_query($sql, $id) {
        //Check connection to database
        $db = DBConnector::get_db_connection();

        //Prepare the statement so we can bind data to it
        if (!$statement = $db->prepare($sql)) {
            echo "Prepare failed: ($db->errno) $db->error";
        }

        //Bind the parameters to the insert statement
        $statement->bind_param("i", $id);

        //Run query
        if (!$statement->execute()) {
            echo "Execution of prepared statement failed: ($statement->errno) $statement->error";
        }

        $statement->close();
        $db->close();
    }

    abstract protected function to_table_row();
}