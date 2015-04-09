<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 1:31 AM
 */

include_once("../lessons/lessoncontroller.php");
include_once("../lessons/lesson.php");
include_once("quiz.php");
include_once("quizcontroller.php");
include_once("../../templates/basetemplate.php");

class AddQuiz extends BaseTemplate {
    private $header;
    private $footer;

    private $lessons_id;

    /**
     * AddQuiz constructor.
     * @param Lesson $lesson
     */
    public function __construct(Lesson $lesson) {
        $this->lessons_id = $lesson->id;

        $this->header = parent::get_header($lesson->title);
        $this->footer = parent::get_footer();
    }

    public function get_final_page() {
        return $this->header . "
        <h1>Please add a quiz to this lesson.</h1>
		<hr>
		<form action='add.php' method='post'>
			<input type=\"hidden\" name=\"lessons_id\" value=\"$this->lessons_id\"/>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'>
						<label for='title'>Title</label>
						<input type='text' name='title' class='form-control' placeholder='Title'>
						</br>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'>
						<label for='description'>Description</label>
						<input type='text' name='description' class='form-control' placeholder='Description'>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<div class='form-group'>
						<label for='attempts'>Attempts Allowed</label>
						<span class='help-block'>0 = infinite attempts.</span>
						<input type='number' name='attempts' class='form-control' min='0' max='99'>
					</div>
				</div>
			</div>
			<hr/>
			<button type='submit' class='btn btn-primary' style='float:right;'>Submit</button>
		</form>" .$this->footer;
    }
}

session_start();

//Check if person is logged in.
$persons_id = 0;
if (isset($_SESSION['persons_id'])) {
    $persons_id = $_SESSION['persons_id'];
}

if (!empty($_POST)) {
	$lesson = LessonController::get_lesson_by_id($_POST['lessons_id']);

	if ($lesson->persons_id == $persons_id) {
		$quiz = new Quiz($_POST['lessons_id'], $_POST['attempts'], $_POST['title'], $_POST['description']);

		QuizController::add_quiz($quiz);

		header("Location: ../quizzes/take.php?lesson_id=$quiz->lessons_id");
	}
}
else {
	//Get the lesson_id
	$lesson_id = null;
	if ( !empty($_GET['lesson_id'])) {
		$lesson_id = $_REQUEST['lesson_id'];
	}

	//If no ID set, just reroute them back to list of lessons
	if ( $lesson_id == null ) {
		header("Location: ../lessons/index.php");
	}

	$lesson = LessonController::get_lesson_by_id($lesson_id);

	if ($lesson->persons_id == $persons_id) {
		$add_quiz = new AddQuiz($lesson);
		echo $add_quiz->get_final_page();
	}
	else {
		echo 'not authorized';
	}
}

?>