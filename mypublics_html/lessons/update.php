<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/1/15
 * Time: 12:50 AM
 */
include_once("lesson.php");
include_once("lessoncontroller.php");
include_once("../persons/person.php");
include_once("../persons/personcontroller.php");
include_once("../../templates/basetemplate.php");

class UpdateLesson extends BaseTemplate {
    private $header;
    private $person_options = "";
    private $footer;

	private $title;
	private $subject;
	private $description;
	private $resources;
	private $search_field;
	private $id;

    /**
     * AddLesson constructor.
     */
    public function __construct(Lesson $lesson)
	{
        $this->header = parent::get_header("Add a Lesson");
        $options = PersonController::get_all_persons_options($lesson->persons_id);

        //Get the options for the users to select.
        foreach ($options as $o) {
            $this->person_options = $this->person_options . $o;
        }

        $this->footer = parent::get_footer();

		$this->title = $lesson->title;
		$this->subject = $lesson->subject;
		$this->description = $lesson->description;
		$this->resources = $lesson->resources;
		$this->search_field = $lesson->search_field;
		$this->id = $lesson->id;
    }


    public function get_final_page()
    {
        return $this->header .
        "<div class='row'>
			<form class='form-horizontal' action='update.php?id=$this->id' method='post'>
				<fieldset>
					<legend>Update a Lesson</legend>
					<div class='form-group'>
						<label for='title' class='col-lg-2 control-label'>Title</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='title' id='title' minlength='5' maxlength='49' value=\"$this->title\"/>
						</div>
					</div>
					<div class='form-group'>
						<label for='subject' class='col-lg-2 control-label'>Subject</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='subject' id='subject' minlength='5' maxlength='49' value=\"$this->subject\"/>
						</div>
					</div>
					<div class='form-group'>
						<label for='description' class='col-lg-2 control-label'>Description</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='description' id='description' minlength='5' maxlength='199' value=\"$this->description\"/>
						</div>
					</div>
					<div class='form-group'>
						<label for='resources' class='col-lg-2 control-label'>Resources</label>
						<div class='col-lg-10'>
							<textarea class='form-control' rows='5' name='resources' id='resources'>$this->resources</textarea>
							<span class='help-block'>Any material relevant to the lesson, such as lecture notes, or links to videos.
						</div>
					</div>
					<div class='form-group'>
						<label for='person' class='col-lg-2 control-label'>Lesson Creator</label>
						<div class='col-lg-10'>
							<select class='form-control' name='person' id='person'>
							    " . $this->person_options . "
							</select>
						</div>
					</div>
					<div class='form-group'>
						<label for='search' class='col-lg-2 control-label'>Key terms</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='search' id='search' maxlength='1999' value=\"$this->search_field\"/>
							<span class='help-block'>Key terms to help users find your lesson.</span>
						</div>
					</div>
					<div class='form-action'>
						<div class='col-lg-10 col-lg-offset-2'>
							<button type='submit' class='btn btn-primary'>Update</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>" . $this->footer;
    }
}

session_start();

//TODO: Only teachers can update

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
	$_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
	header("Location: ../login.php");
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

//If the user clicked update.
if ( !empty($_POST)) {
	// Create a lesson object
	$lesson = new Lesson($_POST['title'], $_POST['subject'], $_POST['description'], $_POST['resources'], $_POST['person'], "", $_POST['search'], $id);
	LessonController::update_lesson($lesson);
	header("Location: index.php");
}

$lesson = LessonController::get_lesson_by_id($id);

//TODO: 404 and 500 errors.

//Check if this is even a lesson.
if ($lesson->id == 0) {
	echo 'No lesson found error here!';
}
else {
	$view_lesson = new UpdateLesson($lesson);

	echo $view_lesson->get_final_page();
}
?>