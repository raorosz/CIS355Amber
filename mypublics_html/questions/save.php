<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 9:28 PM
 */

include_once("questioncontroller.php");
include_once("question.php");
include_once("../options/optioncontroller.php");
include_once("../options/option.php");
include_once("../../templates/basetemplate.php");

class SaveQuestion extends BaseTemplate {
    private $header;
    private $footer;

    /**
     * SaveQuestion constructor.
     */
    public function __construct() {
        $this->header = parent::get_header("Question Saved");
        $this->footer = parent::get_footer();
    }

    public function get_final_page() {
        return $this->header .
            "Question saved! Click below to go back to lessons. <br/>
            <a href='../lessons/index.php' class='btn btn-default'>Back to lessons</a>" . $this->footer;
    }
}

//If nothing was passed
if (empty($_GET)) {
    header("Location: ../lessons/index.php");
}

$question = new Question($_GET['id'], $_GET['question']);

//Insert the question
//The function returns the auto-generated ID.
$question_id = QuestionController::add_question($question);

//TODO: Make less janky
//TODO: Figure out correct option
//TODO: Authenticate this page
//Check if they filled in an option and save it if they did.
if ($_GET['option1'] != '') {
    $o = new Option($question_id, $_GET['option1'], 0);
    OptionController::add_option($o);
}
if ($_GET['option2'] != '') {
    $o = new Option($question_id, $_GET['option2'], 0);
    OptionController::add_option($o);
}
if ($_GET['option3'] != '') {
    $o = new Option($question_id, $_GET['option3'], 0);
    OptionController::add_option($o);
}
if ($_GET['option4'] != '') {
    $o = new Option($question_id, $_GET['option4'], 0);
    OptionController::add_option($o);
}
if ($_GET['option5'] != '') {
    $o = new Option($question_id, $_GET['option5'], 0);
    OptionController::add_option($o);
}

$save_page = new SaveQuestion();

echo $save_page->get_final_page();
