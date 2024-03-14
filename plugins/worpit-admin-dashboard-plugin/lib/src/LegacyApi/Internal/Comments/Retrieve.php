<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Comments;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Retrieve extends LegacyApi\Internal\Base {

	public function process() :LegacyApi\ApiResponse {

		$params = $this->getActionParam( 'retrieve_params' );

		//cater for multiple comment statuses and multiple comment types
		$aCommentStatusToLookup = \explode( ',', $params[ 'status' ] ?? '' );
		$aCommentTypesToLookup = \explode( ',', $params[ 'type' ] ?? '' );

		$results = [];
		foreach ( $aCommentStatusToLookup as $status ) {
			$params[ 'status' ] = $status;
			$results = \array_merge(
				$results,
				$this->loadWpCommentsProcessor()->getCommentsOfTypes( $aCommentTypesToLookup, $params )
			);
		}

		//Get Post IDs / Titles
		$aPostTitles = [];
		foreach ( $results as &$aComment ) {
			if ( !in_array( $aComment[ 'comment_post_ID' ], $aPostTitles ) ) {
				$aPostTitles[ $aComment[ 'comment_post_ID' ] ] = get_the_title( $aComment[ 'comment_post_ID' ] );
			}
			$aComment[ 'post_title' ] = $aPostTitles[ $aComment[ 'comment_post_ID' ] ];
		}

		return $this->success( [
			'comments' => $results
		] );
	}
}