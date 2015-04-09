<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/2/15
 * Time: 11:54 PM
 */

include_once("question.php");
include_once("questioncontroller.php");
include_once("../../templates/basetemplate.php");

class DeleteQuestion extends BaseTemplate {
    private $header;
    private $footer;

    private $question;
    private $id;

    public function __construct(Question $question) {
        $this->question = $question->question;
        $this->id = $question->id;

        $this->header = parent::get_header("Delete Lesson?");
        $this->footer = parent::get_footer();
    }


    public function get_final_page() {
        return $this->header . "
        <div class=\"span10 offset1\">
    <div class=\"row\">
        <h3>Delete \"$this->question\" Question?</h3>
    </div>

    <form class=\"form-horizontal\" action=\"delete.php\" method=\"post\">
      <input type=\"hidden\" name=\"id\" value=\"$this->id\"/>
      <p class=\"alert alert-error\">Are you sure to delete this and all associated answers?</p>
      <div class=\"form-actions\">
          <button type=\"submit\" class=\"btn btn-danger\">Yes</button>
          <a class=\"btn\" href=\"../lessons/index.php\">No</a>
        </div>
    </form>
</div>" . $this->footer;
    }
}

session_start();

//TODO: Only work for teachers.

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

    //Get associated question
    $question = QuestionController::get_question_by_id($id);

    //If question is valid
    if ($question->id != 0) {
        $delete_question = new DeleteQuestion($question);
        echo $delete_question->get_final_page();
    }
    else {
        echo "No lesson found with this ID.";
    }
}
else {
    $question = QuestionController::get_question_by_id($_POST['id']);

    if ($question->id != 0) {
        QuestionController::delete_question($question->id);
    }
    header("Location: index.php");
}
?>