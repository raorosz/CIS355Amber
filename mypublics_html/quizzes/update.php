<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 11:12 PM
 */

include_once("../questions/question.php");
include_once("../questions/questioncontroller.php");
include_once("../lessons/lessoncontroller.php");
include_once("../lessons/lesson.php");
include_once("quizcontroller.php");
include_once("quiz.php");
include_once("../../templates/tabletemplate.php");

class UpdateQuestionsTable extends TableTemplate {

    /**
     * UpdateQuestion constructor.
     */
    public function __construct($page_title="Update Questions", $authenticated=false, $quiz_id) {
        parent::__construct($page_title, $authenticated);

        $this->formControls = "<a href='../questions/add.php?quiz_id=$quiz_id' class='btn btn-primary'>Add a Question</a>";
    }
}

session_start();

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

//Get the lesson_id
$quiz_id = null;
if ( !empty($_GET['quiz_id'])) {
    $quiz_id = $_REQUEST['quiz_id'];
}

//Verify it's a valid quiz ID.
if (($quiz_id == 0) or ($quiz_id == null)) {
    echo 'Not a valid quiz id.';
    //header("Location: ../lessons/index.php");
}

//Validate that the person adding questions is the lesson creator
$quiz = QuizController::get_quiz_by_id($quiz_id);
$lesson = LessonController::get_lesson_by_id($quiz->lessons_id);

//TODO: 401 error
//This also won't work if the quiz doesn't really exist, meaning we're good.
if ($lesson->id != $_SESSION['persons_id']) {
    echo 'Not authorized!';
    //header("Location: ../lessons/index.php");
}
else {

    $table = new UpdateQuestionsTable("Update " . $quiz->title, true, $quiz->id);
    $table->set_table_header(QuestionController::get_table_header());

    $questions = QuestionController::get_questions_by_quizzes_id($quiz->id);

    foreach ($questions as $q) {
        $table->add_table_data($q->get_table_row());
    }

    echo $table->get_final_page();
}
?>