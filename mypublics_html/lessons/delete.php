<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 11:18 AM
 */
include_once("lesson.php");
include_once("lessoncontroller.php");
include_once("../../templates/basetemplate.php");

class DeleteLesson extends BaseTemplate {
    private $header;
    private $footer;

    private $title;
    private $id;

    public function __construct(Lesson $lesson) {
        $this->title = $lesson->title;
        $this->id = $lesson->id;

        $this->header = parent::get_header("Delete Lesson?");
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
      <p class=\"alert alert-error\">Are you sure to delete this lessons, and all content associated with it?</p>
      <div class=\"form-actions\">
          <button type=\"submit\" class=\"btn btn-danger\">Yes</button>
          <a class=\"btn\" href=\"index.php\">No</a>
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

    $lesson = LessonController::get_lesson_by_id($id);

    if ($lesson->id != 0) {
        $delete_lesson = new DeleteLesson($lesson);
        echo $delete_lesson->get_final_page();
    }
    else {
        echo "No lesson found with this ID.";
    }
}
else {
    $lesson = LessonController::get_lesson_by_id($_POST['id']);

    if ($lesson->id != 0) {
        LessonController::delete_lesson($lesson->id);
    }
    header("Location: index.php");
}
?>