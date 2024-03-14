<?php

namespace TotalContest\Shortcode;

/**
 * Shortcode base class
 *
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
abstract class Base {

	protected $attributes = [];
	protected $content    = null;


	/**
	 * Setup shortcode.
	 *
	 * @param      $attributes
	 * @param  null  $content
	 *
	 * @since 1.0.0
	 */
	public function __construct( $attributes, $content = null ) {
		$this->attributes = (array) $attributes;
		$this->content    = $content;
	}

	/**
	 * Get content.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Get contest.
	 *
	 * @return null|\TotalContest\Contest\Model
	 * @since 1.0.0
	 */
	public function getContest() {
		return TotalContest( 'contests.repository' )
			->getById( $this->getAttribute( 'contest' ), true );
	}

	/**
	 * Get attribute value.
	 *
	 * @param      $name
	 * @param  null  $default
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	public function getAttribute( $name, $default = null ) {
		return isset( $this->attributes[ $name ] ) ? $this->attributes[ $name ] : $default;
	}

	/**
	 * Get submission.
	 *
	 * @return null|\TotalContest\Submission\Model
	 * @since 1.0.0
	 */
	public function getSubmission() {
		$fallback             = null;
		$contestIdFromRequest = TotalContest( 'http.request' )->request( 'totalcontest.contestId' );

		if ( $contestIdFromRequest && $this->getAttribute( 'contest' ) != $contestIdFromRequest ) {
			return null;
		}

		if ( TotalContest( 'http.request' )->request( 'totalcontest.action' ) === 'submission' ):
			$fallback = TotalContest( 'http.request' )->request( 'totalcontest.submissionId' );
		endif;

		return TotalContest( 'submissions.repository' )->getById( $this->getAttribute( 'submission', $fallback ) );
	}

	/**
	 * To string.
	 *
	 * @return string
	 */
	public function __toString() {
		return (string) $this->handle();
	}

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	abstract public function handle();
}
