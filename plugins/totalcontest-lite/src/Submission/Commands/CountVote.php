<?php

namespace TotalContest\Submission\Commands;

use TotalContest\Contracts\Contest\Model as ContestModel;
use TotalContest\Contracts\Log\Model as LogModel;
use TotalContest\Contracts\Log\Repository as LogRepository;
use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContestVendors\TotalCore\Contracts\Helpers\Embed;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Command;

/**
 * Class CountVote
 * @package TotalContest\Submission\Commands
 */
class CountVote extends Command {
	/**
	 * @var SubmissionModel $submission
	 */
	protected $submission;
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var LogRepository $log
	 */
	protected $log;
	/**
	 * @var Embed $embed
	 */
	protected $embed;

	/**
	 * CountVote constructor.
	 *
	 * @param SubmissionModel $submission
	 * @param Request $request
	 * @param LogRepository $log
	 */
	public function __construct( SubmissionModel $submission, Request $request, LogRepository $log ) {
		$this->submission = $submission;
		$this->request    = $request;
		$this->log        = $log;
	}

	/**
	 * @return bool|mixed
	 */
	protected function handle() {
		$contest = $this->submission->getContest();
		/**
		 * Fires before counting the vote.
		 *
		 * @param ContestModel $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/before/submission/command/count-vote', $contest, $this->submission );

		// Count vote
		$this->submission->incrementVotes();

		// Count vote for contest (aggregation)
		$votes = $contest->getVotes();

		// Log initial array
		$log = apply_filters( 'totalcontest/filters/poll/command/count-vote/log/attributes', [
			'contest_id'    => $contest->getId(),
			'submission_id' => $this->submission->getId(),
			'action'        => 'vote',
			'status'        => 'accepted',
			'details'       => [ 'type' => $contest->getVoteType(), 'fields' => $this->submission->getForm()->toArray() ],
		] );

		if ( $contest->isRateVoting() ):
			// Rating
			$userValues = (array) $this->request->request( 'totalcontest.criterion', [] );
			$criteria   = $contest->getVoteCriteria();

			// Calculate and increment rate
			$this->submission->incrementRatings( $userValues );

			// Iterate over criteria
			foreach ( $criteria as $criterionIndex => $criterion ):
				// Add to log
				$log['details'][ $criterion['name'] ] = $userValues[ $criterionIndex ];
			endforeach;
		endif;

		// Log
		$log = $this->log->create( $log );
		static::share( 'log', $log );

		/**
		 * Fires after counting the vote.
		 *
		 * @param int $votes Votes count.
		 * @param ContestModel $contest Contest model object.
		 * @param SubmissionModel $submission Submission model object.
		 * @param LogModel $log Log model object.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/after/submission/command/count-vote', $votes, $contest, $this->submission, $log );

		return true;
	}

}
