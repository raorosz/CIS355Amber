<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 3:54 PM
 */

include_once("review.php");
include_once("reviewcontroller.php");
include_once("../../templates/tabletemplate.php");

class ReviewTemplate extends TableTemplate {

    private $count;

    /**
     * ReviewTemplate constructor.
     */
    public function __construct($page_title="Teacherati", $authenticated=false, $count, $lesson_id) {
        parent::__construct($page_title, $authenticated);

        $this->formControls="
        <a class='btn btn-primary' href='add.php?lesson_id=$lesson_id'>Review this lesson</a>";

        $this->count = $count;
    }

    public function get_final_page() {
        if ($this->count == 0) {
            $this->tableTail = $this->tableTail . "<div class='alert alert-danger alert-info'>
                There are no reviews. Be the first person to review this lesson.</div>";
        }

        if ($this->authenticated) {
            return $this->heading . $this->table_header . $this->closeHeading . $this->table_data . $this->tableTail .
            $this->formControls . $this->footer;
        }
        return $this->heading . $this->table_header . $this->closeHeading . $this->table_data . $this->tableTail .
        $this->footer;
    }


}

session_start();

$authenticated = false;
$persons_id = 0;
if (isset($_SESSION['persons_id'])) {
    $persons_id = $_SESSION['persons_id'];
    $authenticated = true;
}

//The id of the lesson to view
$lesson_id = null;
if ( !empty($_GET['lesson_id'])) {
    $lesson_id = $_REQUEST['lesson_id'];
}

//If no ID set, just reroute them back to list
if ( $lesson_id == null ) {
    header("Location: ../lessons/index.php");
}

$_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];

$reviews = ReviewController::get_reviews_by_lessons_id($lesson_id);

$table = new ReviewTemplate("Reviews", $authenticated, count($reviews), $lesson_id);
$table->set_table_header(ReviewController::get_table_header());

foreach ($reviews as $r) {
    //$table->add_table_data($r->to_table_row($persons_id));
    $table->add_table_data($r->to_table_row($persons_id));
}

//echo $table->get_final_page();
echo $table->get_final_page();

?>