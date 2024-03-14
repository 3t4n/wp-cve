<?php

namespace TotalContest\Contest;

use TotalContest\Contracts\Contest\Repository as ContestRepository;
use TotalContestVendors\TotalCore\Contracts\Http\Request;

/**
 * Class Controller
 *
 * @package TotalContest\Contest
 */
class Controller {
	/**
	 * @var Model
	 */
	protected $contest;
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var ContestRepository
	 */
	protected $repository;

	/**
	 * Controller constructor.
	 *
	 * @param  Request  $request
	 * @param  ContestRepository  $repository
	 */
	public function __construct( Request $request, ContestRepository $repository ) {
		$this->request    = $request;
		$this->repository = $repository;

		$contestId = (int) $this->request->request( 'totalcontest.contestId', get_the_ID() );
		if ( $contestId ):
			$this->contest = $this->repository->getById( $contestId );
		endif;

		if ( $this->contest ):
			add_action( 'totalcontest/actions/request/landing', [ $this, 'landing' ] );
			add_action( 'totalcontest/actions/request/submissions', [ $this, 'submissions' ] );
			add_action( 'totalcontest/actions/request/get/participate', [ $this, 'participate' ] );
			add_action( 'totalcontest/actions/request/post/participate', [ $this, 'postParticipate' ] );
			add_action( 'totalcontest/actions/request/content', [ $this, 'content' ] );

			add_action( 'totalcontest/actions/ajax-request', function () {
				echo $this->contest->render();
				wp_die();
			} );
		endif;
	}

	/**
	 * Landing.
	 */
	public function landing() {
		$this->contest->setScreen( 'contest.landing' );
	}

	/**
	 * Content pages.
	 */
	public function content() {
		$this->contest->setScreen( 'contest.content' );
	}

	/**
	 * Submissions.
	 */
	public function submissions() {
		$this->contest->setScreen( 'contest.submissions' );
	}

	/**
	 * Upload.
	 */
	public function participate() {
		$this->contest->setScreen( 'contest.participate' );
	}

	/**
	 * Post upload.
	 */
	public function postParticipate() {
		$this->contest->setScreen( 'contest.participate' );
		if ( $this->contest->isAcceptingSubmissions() && $this->contest->getForm()->validate() ):
			$createSubmission = apply_filters( 'totalcontest/commands/contest/submission:create',
			                                   true,
			                                   $this->contest );
			$this->contest->setScreen( $createSubmission instanceof \WP_Error ? 'contest.failed' : 'contest.thankyou' );
			$this->contest->getRestrictions()->apply();
		endif;
	}

}
