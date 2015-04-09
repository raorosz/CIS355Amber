<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 3/14/15
 * Time: 3:55 PM
 */

include_once("../../templates/basetemplate.php");

class QuizTemplate extends BaseTemplate {
    private $heading;
    private $quiz_data;
    private $footer;

    private $editable;
    private $title;
    private $id;

    private $question_number = 1;

    //TODO: Modify this to use basetemplate
    public function __construct(Quiz $quiz, $editable=false)
    {
        $this->id = $quiz->id;
        $this->title = $quiz->title;
        $this->editable = $editable;
        $this->heading = parent::get_header("Quiz - $this->title") .
		"<h1>$this->title</h1>
		<hr>
		<form action='../attempts/save.php' method='post'>";
        $this->footer = "<hr>
			<button type='submit' class='btn btn-primary' style='float:right;'>Submit</button>
		</form>" . parent::get_footer();
    }

    public function add_question($question, $options) {
        $question_text = $this->question_number . ") " . $question->question;
        $question_html = "<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<legend>$question_text</legend>
					<div class='form-group'>
					<input type=\"hidden\" name=\"quiz_id\" value=\"$this->id\"/>";
        foreach ($options as $o) {
            $question_html = $question_html . "<label>
							<input type='radio' name='$question->id' id='$o->id' value='$o->id'>
							 $o->option_text
						</label>
						</br>";
        }
        $question_html = $question_html . "</div>
				</div>
			</div>";
        $this->quiz_data = $this->quiz_data . $question_html;
        $this->question_number += 1;
    }

    public function get_final_page() {
        if ($this->editable) {
            $this->footer = "<div class='row'><div class='col-md-4 col-md-offset-8'>
                <a href='../attempts/index.php?quiz_id=$this->id' class='btn btn-default'>View Attempts</a>
                <a href='../quizzes/update.php?quiz_id=$this->id' class='btn btn-default'>Update</a>
                <a href='../questions/add.php?quiz_id=$this->id' class='btn btn-primary'>Add Question</a>
                </form>
            </div>
        </div>
	</div>
</body>
</html";
        }

        return $this->heading . $this->quiz_data . $this->footer;
    }
}