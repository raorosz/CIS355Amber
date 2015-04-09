<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/31/15
 * Time: 10:36 PM
 */

include_once("lesson.php");
include_once("lessoncontroller.php");
include_once("../persons/person.php");
include_once("../persons/personcontroller.php");
include_once("../../templates/basetemplate.php");

class ViewLesson extends BaseTemplate {
    private $header;
    private $footer;

    private $title;
    private $subject;
    private $description;
    private $date_created;
    private $author;
    private $resources;
    private $id;

    /**
     * @param Lesson $lesson
     */
    public function __construct(Lesson $lesson)
    {
        $this->header = parent::get_header($lesson->title);

        $this->footer = parent::get_footer();

        $person = PersonController::get_person_by_id($lesson->persons_id);
        $this->author = $person->first_name . " " . $person->last_name;

        $this->title = $lesson->title;
        $this->subject = $lesson->subject;
        $this->description = $lesson->description;
        $this->date_created = $lesson->date_created;
        $this->resources = $lesson->resources;
        $this->id = $lesson->id;
    }

    public function get_final_page()
    {
        return $this->header .
        "<div class='row'>
            <h2>$this->title</h2>
        </div>

        <div class='row'>
            <div class='col-md-10'>
                <label for='subject'>Subject</label>
                <p id='subject'>$this->subject</p>
            </div>
            <div class='col-md-2'>
                <label for='date'>Date Created</label>
                <p id='date'>$this->date_created</p>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-10'>
                <label for='description'>Description</label>
                <p id='subject'>$this->description</p>
            </div>
            <div class='col-md-2'>
                <label for='date'>Author</label>
                <p id='date'>$this->author</p>
            </div>
        </div>
        <div class='row'>
            <hr/>
            <div class='col-lg-12'>
                <label for='resources'>Lesson Resources</label>
                <div name='resources'>
                    $this->resources
                </div>
            </div>
        </div>
        <div class='row'>
            <hr/>
            <div class='col-md-2'>
                <a href='delete.php?id=$this->id' class='btn btn-danger'>Delete</a>
            </div>
            <div class='col-md-3 col-md-offset-7'>
                <a href='../reviews/view.php?lesson_id=$this->id' class='btn btn-default'>Reviews</a>
                <a href='../quizzes/take.php?lesson_id=$this->id' class='btn btn-primary'>Quiz</a>
            </div>
        </div>" . $this->footer;
    }
}

session_start();

//TODO: Only teachers can delete

//Assume the user is not logged in
$authenticated = false;

//Check if person is logged in
if (!isset($_SESSION['persons_id'])) {
    $authenticated = true;
}

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

//TODO: 404 and 500 errors.

//Check if this is even a lesson.
if ($lesson->id == 0) {
    echo 'No lesson found error here!';
}
else {
    $view_lesson = new ViewLesson($lesson);

    echo $view_lesson->get_final_page();
}
?>