<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 12:50 AM
 */

class Option {
    private $id;
    private $question_id;
    private $option_text;
    private $correct_option;

    /**
     * Options constructor.
     * @param $id
     * @param $question_id
     * @param $option_text
     * @param $correct_option
     */
    public function __construct($question_id, $option_text, $correct_option, $id=0) {
        $this->id = $id;
        $this->question_id = $question_id;
        $this->option_text = $option_text;
        $this->correct_option = $correct_option;
    }

    public function __get($name) {
        return $this->$name;
    }
}