<?php
/**
 * Created by IntelliJ IDEA.
 * User: brad
 * Date: 4/3/15
 * Time: 12:49 PM
 */

include_once("reportcontroller.php");
include_once("report.php");
include_once("../../templates/basetemplate.php");

class ReportsView extends BaseTemplate {
    private $header;
    private $footer;
    private $report_options;

    /**
     * ReportsView constructor.
     */
    public function __construct() {
        $this->header = parent::get_header("Reports");
        $this->footer = parent::get_footer();
    }

    public function add_report_option($report_option) {
        $this->report_options = $this->report_options . $report_option;
    }

    public function get_final_page() {
        return $this->header . "
        <div class='row'>
			<form class='form-horizontal' action='run.php' method='post'>
				<fieldset>
                <div class='form-group'>
                    <label for='reports' class='col-lg-2 control-label'>Lesson Creator</label>
                    <div class='col-lg-10'>
                        <select class='form-control' name='reports' id='reports'>
                            " . $this->report_options . "
                        </select>
                    </div>
                </div>
                <div class='form-action'>
                    <div class='col-lg-10 col-lg-offset-2'>
                        <button type='submit' class='btn btn-primary'>Run</button>
                    </div>
                </div>
				</fieldset>
			</form>
		</div>
        " . $this->footer;
    }

}

session_start();

//Check if person is logged in.
if (!isset($_SESSION['persons_id'])) {
    $_SESSION['CALLING_PAGE'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
}

//Get all reports
$reports = ReportController::get_all_reports();

$reports_view = new ReportsView();

foreach ($reports as $r) {
    $reports_view->add_report_option($r->get_option());
}

echo $reports_view->get_final_page();