<?php

namespace TotalContest\Decorators;

use TotalContest\Contracts\Contest\Model;


/**
 * Class StructuredData
 * @package TotalContest\Decorators
 */
class StructuredData {
	/**
	 * StructuredData constructor.
	 */
	public function __construct() {
		add_filter( 'totalcontest/filters/render/vars', [ $this, 'appendSchema' ], 10, 3 );
	}

	/**
	 * Contest schema.
	 *
	 * @param array                                    $vars
	 * @param Model                                    $contest
	 * @param \TotalContest\Submission\Model $submission
	 *
	 * @return array
	 */
	public function appendSchema( $vars, $contest, $submission ) {
		$schema = [
			'@context' => 'http://schema.org',
		];

		if ( $contest->isSubmissionScreen() && $submission ):
			$schema['@type']                = 'CreativeWork';
			$schema['datePublished']        = $submission->getDate()->format( 'c' );
			$schema['potentialAction']      = [
				'@type'  => 'VoteAction',
				'target' => $submission->getUrl(),
			];
			$schema['interactionStatistic'] = [
				'@type'                => 'InteractionCounter',
				'interactionType'      => 'http://schema.org/VoteAction',
				'userInteractionCount' => $submission->getVotes(),
			];
		else:
			$schema['@type']                = 'Event';
			$schema['potentialAction']      = [
				'@type'  => 'CreateAction',
				'target' => $contest->getParticipateUrl(),
			];
			$schema['interactionStatistic'] = [
				'@type'                => 'InteractionCounter',
				'interactionType'      => 'http://schema.org/CreateAction',
				'userInteractionCount' => $contest->getSubmissionsCount(),
			];

			$startDate = $contest->getStartDate();
			if ( $startDate ):
				$schema['startDate'] = $startDate->format( 'c' );
			endif;

			$endDate = $contest->getEndDate();
			if ( $endDate ):
				$schema['endDate'] = $endDate->format( 'c' );
			endif;
		endif;

		$vars['before'] = sprintf( '<script type="application/ld+json">%s</script>', json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );

		return $vars;
	}
}
