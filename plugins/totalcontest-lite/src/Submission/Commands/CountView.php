<?php

namespace TotalContest\Submission\Commands;

use TotalContest\Contracts\Contest\Model as ContestModel;
use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContestVendors\TotalCore\Contracts\Helpers\Embed;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Command;

/**
 * Class CountView
 * @package TotalContest\Submission\Commands
 */
class CountView extends Command {
	/**
	 * @var SubmissionModel $submission
	 */
	protected $submission;
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var $log
	 */
	protected $log;
	/**
	 * @var Embed $embed
	 */
	protected $embed;

	/**
	 * CountView constructor.
	 *
	 * @param SubmissionModel $submission
	 * @param Request         $request
	 */
	public function __construct( SubmissionModel $submission, Request $request ) {
		$this->submission = $submission;
		$this->request    = $request;
	}

	/**
	 * @return bool|mixed
	 */
	protected function handle() {
		/**
		 * Fires before counting the view.
		 *
		 * @param ContestModel    $contest    Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/submission/command/count-view', $this->submission->getContest(), $this->submission );

		// Count the view
		$views = (int) $this->submission->incrementViews();

		/**
		 * Fires after counting the view.
		 *
		 * @param int             $view       Views count.
		 * @param ContestModel    $contest    Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/submission/command/count-view', $views, $this->submission->getContest(), $this->submission );

		return true;
	}

}