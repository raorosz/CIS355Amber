<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 12:37 PM
 */

class Report {
    private $id;
    private $reportname;
    private $reportquery;
    private $persons_id;
    private $date_created;

    /**
     * Report constructor.
     * @param $id
     * @param $reportname
     * @param $reportquery
     * @param $persons_id
     * @param $date_created
     */
    public function __construct($reportname, $reportquery, $persons_id, $date_created, $id=0) {
        $this->id = $id;
        $this->reportname = $reportname;
        $this->reportquery = $reportquery;
        $this->persons_id = $persons_id;
        $this->date_created = $date_created;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function get_option() {
        return "<option value='$this->id'>$this->reportname</option>";
    }
}