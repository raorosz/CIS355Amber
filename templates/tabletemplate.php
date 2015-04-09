<?php

include_once("basetemplate.php");

class TableTemplate extends BaseTemplate {
    protected $heading;
    protected $closeHeading;
    protected $tableTail;
    protected $formControls;
    protected $footer;
    protected $authenticated;

    protected $table_header;
    protected $table_data;

    public function __construct($page_title="Teacherati", $authenticated=false) {
        $this->authenticated = $authenticated;

        $this->heading = parent::get_header($page_title) . "
        <table class='table table-striped table-hover'>
            <thead>";

        $this->closeHeading="</thead>
            <tbody>";
        $this->tableTail="</tbody>
        </table>";
        $this->formControls="
        <a class='btn btn-primary' href='add.php'>Add New Entry</a>";
        $this->footer=parent::get_footer();
        $this->table_data = "";
    }

    public function set_table_header($table_header) {
        $this->table_header = $table_header;
    }

    public function add_table_data($table_data) {
        $this->table_data = $this->table_data . $table_data;
    }

    public function get_final_page() {
        if ($this->authenticated) {
            return $this->heading . $this->table_header . $this->closeHeading . $this->table_data . $this->tableTail .
                $this->formControls . $this->footer;
        }
        return $this->heading . $this->table_header . $this->closeHeading . $this->table_data . $this->tableTail .
            $this->footer;
    }
}
?>