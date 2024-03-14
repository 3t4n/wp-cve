<?php

namespace TotalContest\Migrations\Contest;

use TotalContest\Contracts\Log\Model as LogModel;
use TotalContest\Contracts\Migrations\Contest\Template\Contest;
use TotalContest\Contracts\Migrations\Contest\Template\LogEntry;
use TotalContest\Contracts\Migrations\Contest\Template\Options;
use TotalContest\Contracts\Migrations\Contest\Template\Submission;
use TotalContest\Contracts\Migrations\Contest\Template\Submission as SubmissionModel;

/**
 * Load Contests.
 * @package TotalContest\Migrations\Contests
 */
class Load implements \TotalContest\Contracts\Migrations\Contest\Load {

	/**
	 * @param Contest $contest
	 *
	 * @return Contest
	 */
	public function loadContest( Contest $contest ) {
		$contest['presetUid'] = md5( $contest->getId() );

		$model = $contest->toArray();
		$id    = wp_update_post(
			[
				'ID'           => $contest->getId(),
				'post_title'   => $contest->getTitle(),
				'post_content' => wp_slash( json_encode( $model, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ),
				'post_type'    => TC_CONTEST_CPT_NAME,
			]
		);

		if ( is_int( $id ) ):
			$contest->setId( $id );
		endif;

		$contest->setNewId( $contest->getId() );

		update_post_meta( $contest->getNewId(), '_migrated', 'migrated' );

		return $contest;
	}

	/**
	 * @param Options $options
	 *
	 * @return array
	 */
	public function loadOptions( Options $options ) {
		return TotalContest( 'options' )->setOptions( $options->toArray() );
	}

	/**
	 * @param Contest  $contest
	 * @param LogEntry $logEntry
	 *
	 * @return LogModel
	 */
	public function loadLogEntry( Contest $contest, LogEntry $logEntry ) {
		return TotalContest( 'log.repository' )->create( $logEntry->toArray() );
	}

	/**
	 * @param Contest    $contest
	 * @param Submission $submission
	 *
	 * @return SubmissionModel
	 */
	public function loadSubmission( Contest $contest, Submission $submission ) {
		$contest['presetUid'] = md5( $contest->getId() );

		$id = wp_update_post(
			[
				'ID'           => $submission->getId(),
				'post_content' => wp_slash( json_encode( $submission->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) ),
			]
		);

		if ( is_int( $id ) ):
			$submission->setId( $id );
		endif;

		$submission->setNewId( $submission->getId() );

		update_post_meta( $submission->getNewId(), '_migrated', 'migrated' );

		return $submission;
	}
}
