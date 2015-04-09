<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 11:56 PM
 */

include_once("quizcontroller.php");
include_once("quiz.php");
include_once("../questions/questioncontroller.php");
include_once("../lessons/lessoncontroller.php");
include_once("../lessons/lesson.php");
include_once("../options/option.php");
include_once("../options/optioncontroller.php");
include_once("../../templates/quiztemplate.php");

//TODO: Add attempts logic to make sure a user only took it the allow number of times

//TODO: Check if a quiz exist for this lesson, and create one if it doesn't.


session_start();

//Check if the user is logged in
$authenticated = false;
$persons_id = 0;
if (isset($_SESSION['persons_id'])) {
    $persons_id = $_SESSION['persons_id'];
    $authenticated = true;
}

//Get the lesson_id
$lesson_id = null;
if ( !empty($_GET['lesson_id'])) {
    $lesson_id = $_REQUEST['lesson_id'];
}

//If no ID set, just reroute them back to list of lessons
if ( $lesson_id == null ) {
    header("Location: ../lessons/index.php");
}

//Get the quiz for this lesson.
$quiz = QuizController::get_quiz_by_lesson_id($lesson_id);

$lesson = LessonController::get_lesson_by_id($lesson_id);

//If the person tied to this lesson is the person logged in
if ($persons_id == $lesson->persons_id) {
    //If no quiz exist yet
    if ($quiz->id == 0) {
        header("Location: ../quizzes/add.php?lesson_id=$lesson->id");
    }

    //Start rendering the quiz page with edit options
    $quiz_page = new QuizTemplate($quiz, true);
}
else {
    //If no quiz exist yet
    if ($quiz->id == 0) {
        echo "No quiz found, yet!";
    }
    //Start rendering the quiz page.
    $quiz_page = new QuizTemplate($quiz);
}

//Get an array of questions
$questions = QuestionController::get_questions_by_quizzes_id($quiz->id);

//For each question, get an array of options, and render the page.
foreach ($questions as $q) {
    $options = OptionController::get_options_by_question_id($q->id);

    $quiz_page->add_question($q, $options);
}

echo $quiz_page->get_final_page();