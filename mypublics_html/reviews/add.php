<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 6:06 PM
 */

include_once("review.php");
include_once("reviewcontroller.php");
include_once("../../templates/basetemplate.php");

class AddReview extends BaseTemplate {
    private $header;
    private $footer;

    private $lessons_id;
    private $persons_id;

    public function __construct($lessons_id, $persons_id) {
        $this->header = parent::get_header("Update Review");

        $this->footer = parent::get_footer();

        $this->lessons_id = $lessons_id;
        $this->persons_id = $persons_id;
    }


    public function get_final_page()
    {
        return $this->header .
        "<div class='row'>
			<form class='form-horizontal' action='add.php' method='post'>
				<fieldset>
					<legend>Update Your Review</legend>
					<div class='form-group'>
						<label for='title' class='col-lg-2 control-label'>Title</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='title' id='title' maxlength='49' placeholder='Review Title'/>
						</div>
					</div>
					<div class='form-group'>
						<label for='review' class='col-lg-2 control-label'>Review</label>
						<div class='col-lg-10'>
							<textarea class='form-control' rows='5' maxlength='1999' name='review' id='review'></textarea>
							<span class='help-block'>How did the lesson help you, or how can it be improved?</span>
						</div>
					</div>
					<div class='form-group'>
						<label for='rating' class='col-lg-2 control-label'>Rating</label>
						<div class='col-lg-10'>
							<input class='form-control' type='number' name='rating' id='rating' min='1' max='5' value='5'>
						</div>
					</div>
					<input type=\"hidden\" name=\"persons_id\" value=\"$this->persons_id\"/>
					<input type=\"hidden\" name=\"lessons_id\" value=\"$this->lessons_id\"/>
					<div class='form-action'>
						<div class='col-lg-10 col-lg-offset-2'>
							<button type='submit' class='btn btn-primary'>Review</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>" . $this->footer;
    }
}

session_start();

//Check if person is logged in.
$persons_id = 0;
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

//If the user clicked Add.
if ( !empty($_POST)) {
    // Create a review object
    $review = new Review($_POST['persons_id'], $_POST['lessons_id'], $_POST['title'], $_POST['review'],
        date('Y-m-d H:i:s'), $_POST['rating']);

    var_dump($review);

    //Add the review
    ReviewController::add_review($review);

    header("Location: ../lessons/index.php");
}
else {
    $persons_id = $_SESSION['persons_id'];

    //The id of the review to update
    $lesson_id = null;
    if ( !empty($_GET['lesson_id'])) {
        $lesson_id = $_REQUEST['lesson_id'];
    }

    //If no Lesson ID set, just reroute them back to list
    if ( null==$lesson_id ) {
        header("Location: ../lessons/index.php");
    }

    $add_review = new AddReview($lesson_id, $persons_id);

    echo $add_review->get_final_page();
}
?>