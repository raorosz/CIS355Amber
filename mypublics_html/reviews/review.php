<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 3:38 PM
 */

include_once("../persons/personcontroller.php");
include_once("../persons/person.php");

class Review {
    private $id;
    private $persons_id;
    private $lessons_id;
    private $title;
    private $review;
    private $date_submitted;
    private $rating;

    /**
     * Review constructor.
     * @param $id
     * @param $persons_id
     * @param $lessons_id
     * @param $title
     * @param $review
     * @param $date_submitted
     * @param $rating
     */
    public function __construct($persons_id, $lessons_id, $title, $review, $date_submitted, $rating, $id=0)
    {
        $this->id = $id;
        $this->persons_id = $persons_id;
        $this->lessons_id = $lessons_id;
        $this->title = $title;
        $this->review = $review;
        $this->date_submitted = $date_submitted;
        $this->rating = $rating;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function to_table_row($persons_id) {
        //Get the person who made this review.
        $reviewer = PersonController::get_person_by_id($this->persons_id);

        $table_row = "<tr><td>$this->id</td><td>$reviewer->first_name $reviewer->last_name</td><td>$this->title</td>" .
            "<td>$this->review</td><td>$this->date_submitted</td><td>$this->rating</td>";

        if ($this->persons_id == $persons_id) {
            return $table_row . "<td><a class='btn' href='update.php?id=$this->id'>Modify</a>" .
            "<a class='btn' href='delete.php?id=$this->id'>Delete</a></td></tr>";
        }

        return $table_row . "</tr>";
    }

    public function __get($name) {
        return $this->$name;
    }
}