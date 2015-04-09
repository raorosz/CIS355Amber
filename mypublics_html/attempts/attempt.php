<?php
require_once('../../mysqlconnector.php');
require_once('../../templates/sqlaction.php');
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 2/4/15
 * Time: 9:28 PM
 */

class Attempt {
    private $id;
    protected $persons_id;
    protected $quizzes_id;
    protected $attempt_date;
    protected $attempt_number;
    protected $score;

    public function __construct($persons_id, $quizzes_id, $attempt_date, $attempt_number, $score, $id = 0)
    {
        $this->id = $id;
        $this->persons_id = $persons_id;
        $this->quizzes_id = $quizzes_id;
        $this->attempt_date = $attempt_date;
        $this->attempt_number = $attempt_number;
        $this->score = $score;
    }

    public function __destruct() {
    }

    /**
     * @param mixed $attempt_number
     */
    public function set_attempt_number($attempt_number)
    {
        $this->attempt_number = $attempt_number;
    }

    /**
     * @param mixed $score
     */
    public function set_score($score)
    {
        $this->score = $score;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function to_table_row() {
        return "<tr><td>$this->id</td><td>$this->persons_id</td><td>$this->quizzes_id</td>" .
        "<td>$this->attempt_date</td><td>$this->attempt_number</td><td>$this->score</td></tr>";
    }

}

?>