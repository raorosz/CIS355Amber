<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 8:07 PM
 */

include_once("question.php");
include_once("questioncontroller.php");
include_once("../lessons/lessoncontroller.php");
include_once("../lessons/lesson.php");
include_once("../quizzes/quizcontroller.php");
include_once("../quizzes/quiz.php");
include_once("../../templates/basetemplate.php");

class AddQuestion extends BaseTemplate {
    private $header;
    private $footer;
    private $quiz_id;

    public function __construct($quiz_id) {
        $this->quiz_id = $quiz_id;
        $this->header = parent::get_header("Add Question");
        $this->footer = parent::get_footer();
    }

    public function get_final_page() {
        return $this->header . "<h1>Add a Question to this Quiz</h1>
		<hr>
		<form action='../questions/save.php'>
			<input type=\"hidden\" name=\"id\" value=\"$this->quiz_id\"/>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<span class='help-block'>Enter a clear question that actually covers material you link to, or mention in your lesson.</span>
						<input type='text' name='question' class='form-control' placeholder='Question'>
						</br>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<span class='help-block'>Answers to the questions below. You can have up to five answers. Not all of them have to be filled in.</span>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<input type='text' name='option1' class='form-control' placeholder='Answer 1'>
						</br>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<input type='text' name='option2' class='form-control' placeholder='Answer 2'>
						</br>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<input type='text' name='option3' class='form-control' placeholder='Answer 3'>
						</br>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<input type='text' name='option4' class='form-control' placeholder='Answer 4'>
						</br>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'><label>
						<input type='text' name='option5' class='form-control' placeholder='Answer 5'>
						</br>
					</div>
				</div>
			</div>
			<hr/>
			<button type='submit' class='btn btn-primary' style='float:right;'>Submit</button>
		</form>" . $this->footer;
    }
}

session_start();

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
	$_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
	header("Location: ../login.php");
}

//Get the quiz_id
$quiz_id = null;
if ( !empty($_GET['quiz_id'])) {
	$quiz_id = $_REQUEST['quiz_id'];
}

if (($quiz_id == 0) or ($quiz_id == null)) {
	echo 'Not a valid quiz id.';
	//header("Location: ../lessons/index.php");
}

//Validate that the person adding questions is the lesson creator
$quiz = QuizController::get_quiz_by_id($quiz_id);
$lesson = LessonController::get_lesson_by_id($quiz->lessons_id);

//TODO: 401 error
if ($lesson->persons_id != $_SESSION['persons_id']) {
	echo 'Not authorized!';
	//header("Location: ../lessons/index.php");
}
else {
	$add_questions = new AddQuestion($quiz_id);
	echo $add_questions->get_final_page();
}
?>