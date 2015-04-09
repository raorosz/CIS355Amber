<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/24/15
 * Time: 4:09 PM
 */

include_once("../../mysqlconnector.php");
include_once("../../templates/sqlaction.php");

class Lesson {
    private $id;
    private $title;
    private $subject;
    private $description;
    private $resources;
    private $persons_id;
    private $date_created;
    private $search_field;

    function __construct($title, $subject, $description, $resources, $persons_id, $date_created, $search_field, $id=0)
    {
        $this->id = $id;
        $this->title = $title;
        $this->subject = $subject;
        $this->description = $description;
        $this->resources = $resources;
        $this->persons_id = $persons_id;
        $this->date_created = $date_created;
        $this->search_field = $search_field;
    }

    public function __destruct() {}

    public function __get($name) {
        return $this->$name;
    }

    public function to_table_row($authenticated=false) {
        $table_row = "<tr><td>$this->id</td><td>$this->title</td><td>$this->subject</td><td>$this->description</td>" .
            "<td>$this->date_created</td>";

        if ($authenticated) {
            return $table_row . "<td><a class='btn' href='update.php?id=$this->id'>Modify</a>" .
                "<a class='btn' href='delete.php?id=$this->id'>Delete</a>" .
                "<a class='btn btn-primary' href='view.php?id=$this->id'>View</a></td></tr>";
        }

        return $table_row . "</tr>";
    }
}