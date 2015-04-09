<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 11:25 PM
 */

include_once("review.php");
include_once("reviewcontroller.php");
include_once("../../templates/basetemplate.php");

class DeleteReview extends BaseTemplate {
    private $header;
    private $footer;

    private $title;
    private $id;

    public function __construct(Review $review) {
        $this->title = $review->title;
        $this->id = $review->id;

        $this->header = parent::get_header("Delete Review?");
        $this->footer = parent::get_footer();
    }


    public function get_final_page() {
        return $this->header . "
        <div class=\"span10 offset1\">
    <div class=\"row\">
        <h3>Delete $this->title</h3>
    </div>

    <form class=\"form-horizontal\" action=\"delete.php\" method=\"post\">
      <input type=\"hidden\" name=\"id\" value=\"$this->id\"/>
      <p class=\"alert alert-error\">Are you sure to delete your review?</p>
      <div class=\"form-actions\">
          <button type=\"submit\" class=\"btn btn-danger\">Yes</button>
          <a class=\"btn\" href=\"../lessons/index.php\">No</a>
        </div>
    </form>
</div>" . $this->footer;
    }
}

session_start();

//TODO: Only work for the original reviewer.

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

//If post empty, then display the page defined above
if (empty($_POST)) {
    //The id of the lesson to view
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }

    //If no ID set, just reroute them back to list
    if ( null==$id ) {
        header("Location: index.php");
    }

    $review = ReviewController::get_review_by_id($id);

    if ($review->id != 0) {
        $delete_review = new DeleteReview($review);
        echo $delete_review->get_final_page();
    }
    else {
        echo "No lesson found with this ID.";
    }
}
else {
    $review = ReviewController::get_review_by_id($_POST['id']);

    if ($review->id != 0) {
        ReviewController::delete_review($review->id);
    }
    header("Location: ../lessons/index.php");
}