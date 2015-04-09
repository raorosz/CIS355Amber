<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 12:23 AM
 */
include_once("question.php");
include_once("questioncontroller.php");
include_once("../../templates/basetemplate.php");

class UpdateQuestion extends BaseTemplate {
    private $header;
    private $footer;

    private $id;
    private $question;

    public function __construct(Question $question) {
        $this->id = $question->id;
        $this->question = $question->question;

        $this->header = parent::get_header("Update Question");
        $this->footer = parent::get_footer();
    }

    public function get_final_page() {
        return $this->header . "
            <form action='updatequestion.php' method='post'>
            <fieldset>
                <div class='row'>
                <input type=\"hidden\" name=\"id\" value='". $this->id ."'/>
                <div class='col-md-8 col-md-offset-2'>
					<div class='form-group'>
						<label for='question'>Question</label>
						<input type='text' name='question' class='form-control' value='$this->question'>
						</br>
					</div>
				</div>
				</div>
				<button type='submit' class='btn btn-primary' style='float:right;'>Submit</button>
			</fieldset>
			</form>" . $this->footer;
    }
}

session_start();

//TODO: Only teachers can update

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

//If the user clicked update.
if ( !empty($_POST)) {
    // Create a question object
    // We don't really care about the quizzes ID since that won't change.
    $question = new Question(0, $_POST['question'], $_POST['id']);

    //Update the lesson and return to main page
    QuestionController::update_question($question);
    header("Location: ../lessons/index.php");
}

//The id of the lesson to view
$id = null;
if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

//If no ID set, just reroute them back to list
if ( null==$id ) {
    //header("Location: ../lessons/index.php");
}

$question = QuestionController::get_question_by_id($id);

//TODO: 404 and 500 errors.

//Check if this is even a lesson.
if ($question->id == 0) {
    echo 'No lesson found error here!';
}
else {
    $update_question = new UpdateQuestion($question);

    echo $update_question->get_final_page();
}
?>