<?php

namespace TotalContest\Helpers;


/**
 * Class Url
 * @package TotalContest\Helpers
 */
class Url {
	/**
	 * @var array $shortFormat
	 */
	protected $shortFormat = [
		'action'        => 'cac',
		'contestId'     => 'cid',
		'context'       => 'ctx',
		'menu'          => 'cm',
		'customPage'    => 'pid',
		'submissionId'  => 'sid',
		'page'          => 'cpn',
		'category'      => 'cci',
		'sortDirection' => 'sd',
		'sortBy'        => 'sb',
		'filterBy'      => 'fb',
		'filter'        => 'fv',
	];

	/**
	 * Url constructor.
	 */
	public function __construct() {

	}

	public function compactParameters( $parameters ) {
		foreach ( $parameters['totalcontest'] as $parameter => $value ):
			if ( isset( $this->shortFormat[ $parameter ] ) ):
				$parameters[ $this->shortFormat[ $parameter ] ] = $value;
				unset( $parameters['totalcontest'][ $parameter ] );
			endif;
		endforeach;

		if ( empty( $parameters['totalcontest'] ) ):
			unset( $parameters['totalcontest'] );
		endif;

		return $parameters;
	}

	public function extractParameters( $request ) {
		$totalcontest = empty( $request['totalcontest'] ) ? [] : $request['totalcontest'];

		foreach ( $request as $parameter => $value ):
			$originalName = array_search( $parameter, $this->shortFormat );

			if ( ! empty( $originalName ) ):
				$totalcontest[ $originalName ] = $value;
			endif;
		endforeach;

		if ( ! empty( $totalcontest ) ):
			$request['totalcontest'] = $totalcontest;
		else:
			unset( $request['totalcontest'] );
		endif;

		return $request;
	}
}
