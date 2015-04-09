<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 12:02 AM
 */

class Quiz {
    private $id;
    private $lessons_id;
    private $attempts_allowed;
    private $title;
    private $description;

    /**
     * Quiz constructor.
     * @param $lessons_id
     * @param $attempts_allowed
     * @param $title
     * @param $description
     * @param $id
     */
    public function __construct($lessons_id, $attempts_allowed, $title, $description, $id=0) {
        $this->lessons_id = $lessons_id;
        $this->attempts_allowed = $attempts_allowed;
        $this->title = $title;
        $this->description = $description;
        $this->id = $id;
    }

    public function __get($name) {
        return $this->$name;
    }
}