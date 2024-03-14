<?php

namespace TotalContest\Submission;

use TotalContest\Contracts\Submission\Repository as SubmissionRepository;
use TotalContestVendors\TotalCore\Contracts\Http\Request;

/**
 * Class Controller
 *
 * @package TotalContest\Submission
 */
class Controller {
	/**
	 * @var Model $submission
	 */
	protected $submission;
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var SubmissionRepository $submissionRepository
	 */
	protected $submissionRepository;

	/**
	 * Controller constructor.
	 *
	 * @param  Request  $request
	 * @param  SubmissionRepository  $submissionRepository
	 */
	public function __construct( Request $request, SubmissionRepository $submissionRepository ) {
		$this->request              = $request;
		$this->submissionRepository = $submissionRepository;

		$submissionId = (int) $this->request->request( 'totalcontest.submissionId', null ) ?: $GLOBALS['post']->ID;

		if ( $submissionId ):
			$this->submission = $this->submissionRepository->getById( $submissionId );
		endif;

		if ( $this->submission ):
			add_action( 'totalcontest/actions/request/vote', [ $this, 'vote' ] );
			add_action( 'totalcontest/actions/request/view', [ $this, 'index' ] );
			add_action( 'totalcontest/actions/request/submission', [ $this, 'index' ] );

			add_action( 'totalcontest/actions/ajax-request', function () {
				echo $this->submission->render();
				wp_die();
			} );
		endif;
	}

	/**
	 * View.
	 */
	public function index() {
		if ( ! wp_doing_ajax() && defined( 'TC_ASYNC' ) && TC_ASYNC ) {
			return;
		}

		apply_filters( 'totalcontest/commands/submission/count:view', true, $this->submission );
	}

	/**
	 * Vote.
	 */
	public function vote() {
		if ( $this->submission->isAcceptingVotes() && $this->submission->getForm()->validate() ):
			$countVote = apply_filters( 'totalcontest/commands/submission/count:vote', true, $this->submission );
			$this->submission->setScreen( $countVote instanceof \WP_Error ? 'submission.failed' : 'submission.thankyou' );
			$this->submission->getRestrictions()->apply();
		endif;
	}

}
