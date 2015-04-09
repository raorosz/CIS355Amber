<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/26/15
 * Time: 10:41 PM
 */
include_once("lesson.php");
include_once("lessoncontroller.php");
include_once("../persons/person.php");
include_once("../persons/personcontroller.php");
include_once("../../templates/basetemplate.php");

class AddLesson extends BaseTemplate {
    private $header;
    private $person_options = "";
    private $footer;

    /**
     * AddLesson constructor.
     */
    public function __construct()
    {
        $this->header = parent::get_header("Add a Lesson");
        $options = PersonController::get_all_persons_options();

		//Get the options for the users to select.
		foreach ($options as $o) {
			$this->person_options = $this->person_options . $o;
		}

        $this->footer = parent::get_footer();
    }


    public function get_final_page()
    {
        return $this->header .
        "<div class='row'>
			<form class='form-horizontal' action='add.php' method='post'>
				<fieldset>
					<legend>Update a Lesson</legend>
					<div class='form-group'>
						<label for='title' class='col-lg-2 control-label'>Title</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='title' id='title' minlength='5' maxlength='49' placeholder='Lesson Title'/>
						</div>
					</div>
					<div class='form-group'>
						<label for='subject' class='col-lg-2 control-label'>Subject</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='subject' id='subject' minlength='5' maxlength='49' placeholder='Lesson Subject'/>
						</div>
					</div>
					<div class='form-group'>
						<label for='description' class='col-lg-2 control-label'>Description</label>
						<div class='col-lg-10'>
							<input type='text' class='form-control' name='description' id='description' minlength='5' maxlength='199' placeholder='Brief Description'/>
						</div>
					</div>
					<div class='form-group'>
						<label for='resources' class='col-lg-2 control-label'>Resources</label>
						<div class='col-lg-10'>
							<textarea class='form-control' rows='5' name='resources' id='resources'></textarea>
							<span class='help-block'>Any material relevant to the lesson, such as lecture notes, or links to videos.</span>
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
							<input type='text' class='form-control' name='search' id='search' maxlength='1999'/>
							<span class='help-block'>Key terms to help users find your lesson.</span>
						</div>
					</div>
					<div class='form-action'>
						<div class='col-lg-10 col-lg-offset-2'>
							<button type='submit' class='btn btn-primary'>Add</button>
						</div>
					</div>
				</fieldset>
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
	$add_lesson = new AddLesson();

	echo $add_lesson->get_final_page();
	exit;
}
else {
	//Create a lesson object
	$lesson = new Lesson($_POST['title'], $_POST['subject'], $_POST['description'], $_POST['resources'],
		$_POST['person'], date('Y-m-d H:i:s'), $_POST['search']);

	//Add them to the database.
	LessonController::add_lesson($lesson);
	header('Location: index.php');
}
?>