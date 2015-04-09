<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 10:22 PM
 */

include_once("review.php");
include_once("reviewcontroller.php");
include_once("../../templates/basetemplate.php");

class UpdateReview extends BaseTemplate {
    private $header;
    private $footer;

    private $id;
    private $title;
    private $review;
    private $rating;

    public function __construct(Review $review) {
        $this->header = parent::get_header("Update Review");

        $this->footer = parent::get_footer();

        $this->id = $review->id;
        $this->title = $review->title;
        $this->review = $review->review;
        $this->rating = $review->rating;
    }


    public function get_final_page()
    {
        return $this->header .
        "<div class='row'>
			<form class='form-horizontal' action='update.php?id=$this->id' method='post'>
				<fieldset>
					<legend>Update Your Review</legend>
					<div class='form-group'>
						<label for='title' class='col-lg-2 control-label'>Title</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='title' id='title' maxlength='49' value=\"$this->title\"/>
						</div>
					</div>
					<div class='form-group'>
						<label for='review' class='col-lg-2 control-label'>Review</label>
						<div class='col-lg-10'>
							<textarea class='form-control' rows='5' maxlength='1999' name='review' id='review'>$this->review</textarea>
						</div>
					</div>
					<div class='form-group'>
						<label for='rating' class='col-lg-2 control-label'>Rating</label>
						<div class='col-lg-10'>
							<input class='form-control' type='number' name='rating' id='rating' min='1' max='5' value=\"$this->rating\">
						</div>
					</div>
					<div class='form-action'>
						<div class='col-lg-10 col-lg-offset-2'>
							<button type='submit' class='btn btn-primary'>Update</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>" . $this->footer;
    }
}

session_start();

//TODO: Only the original reviewer can update.

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

//The id of the review to update
$id = null;
if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

//If no ID set, just reroute them back to list
if ( $id == null ) {
    header("Location: ..lessons/index.php");
}

//If the user clicked update.
if ( !empty($_POST)) {
    // Create a lesson object
    $review = ReviewController::get_review_by_id($id);
    $review->setRating($_POST['rating']);
    $review->setReview($_POST['review']);
    $review->setTitle($_POST['title']);

    ReviewController::update_review($review);

    header("Location: ../lessons/index.php");
}

$review = ReviewController::get_review_by_id($id);

//TODO: 404 and 500 errors.

//Check if this is even a valid review.
if ($review->id == 0) {
    echo 'No review found error here!';
}
else {
    $update_review = new UpdateReview($review);

    echo $update_review ->get_final_page();
}
?>