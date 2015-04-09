<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 2/10/15
 * Time: 4:08 PM
 */
class Response extends SQLAction {
    private $id;
    private $questions_id;
    private $attempts_id;
    private $options_id;

    public function __construct($questions_id, $attempts_id, $options_id, $id=0)
    {
        $this->id = $id;
        $this->questions_id = $questions_id;
        $this->attempts_id = $attempts_id;
        $this->options_id = $options_id;
    }

    public function __destruct() {
    }

    public function __get($name) {
        return $this->$name;
    }

    public function to_table_row() {
        return "<tr><td>$this->id</td><td>$this->questions_id</td><td>$this->attempts_id</td>" .
        "<td>$this->options_id</td></tr>";
    }
}

?>