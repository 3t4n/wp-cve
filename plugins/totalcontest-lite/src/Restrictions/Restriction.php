<?php

namespace TotalContest\Restrictions;

use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Restrictions\Restriction as RestrictionBase;

/**
 * Base Restriction.
 * @package TotalContest\Restrictions
 */
abstract class Restriction extends RestrictionBase {
	use \TotalContestVendors\TotalCore\Traits\Cookies;

	/**
	 * @return bool
	 */
	public function isFullCheck() {
		return (bool) Arrays::getDotNotation( $this->args, 'fullCheck', false );
	}

	/**
	 * @return bool
	 */
	public function isCategoryAware() {
		return (bool) Arrays::getDotNotation( $this->args, 'categoryAware', false );
	}

	/**
	 * @return mixed
	 */
	public function getContestId() {
		return empty( $this->args['contest'] ) ? null : $this->args['contest']->getId();
	}

	/**
	 * @return mixed
	 */
	public function getSubmissionId() {
		return empty( $this->args['submission'] ) ? null : $this->args['submission']->getId();
	}

	/**
	 * @return mixed
	 */
	public function getCategoryId() {
		if ( ! empty( $this->args['submission'] ) && $this->isCategoryAware() ):
			$category = $this->args['submission']->getCategory();
			if ( ! empty( $category ) ):
				return $category->term_id;
			endif;
		endif;

		return null;
	}

	/**
	 * @param int $default
	 *
	 * @return int
	 */
	public function getTimeout( $default = 3600 ) {
		return absint( Arrays::getDotNotation( $this->args, 'timeout', 3600 ) );
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return (string) Arrays::getDotNotation( $this->args, 'action' );
	}

	/**
	 * @param int $default
	 *
	 * @return int
	 */
	public function getPerItem( $default = 1 ) {
		return absint( Arrays::getDotNotation( $this->args, 'perItem', $default ) );
	}

	/**
	 * @param int $default
	 *
	 * @return int
	 */
	public function getPerCategory( $default = 0 ) {
		return absint( Arrays::getDotNotation( $this->args, 'perCategory', $default ) );
	}

	/**
	 * @param int $default
	 *
	 * @return int
	 */
	public function getCount( $default = 1 ) {
		return absint( Arrays::getDotNotation( $this->args, 'count', $default ) );
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return empty( $this->args['message'] ) ? esc_html__( 'You cannot do that again.', 'totalcontest' ) : (string) $this->args['message'];
	}

	/**
	 * @param $prefix
	 *
	 * @return string
	 */
	public function getCookieName( $prefix = null ) {
		return $this->generateCookieName( ( $prefix ?: $this->getPrefix() ) . $this->getAction() );
	}

	/**
	 * @param $prefix
	 *
	 * @return string
	 */
	public function getContestCookieName( $prefix = null ) {
		return $this->generateCookieName( ( $prefix ?: $this->getPrefix() ) . $this->getAction() . '_' . $this->getContestId() );
	}


    public function getOwnershipCookieName( $prefix = 'totalcontest' ) {
        return ( $prefix ?: $this->getPrefix() ) . '_' . $this->getContestId(). '_token_' . $this->getSubmissionId() ;
    }

	/**
	 * @param $prefix
	 *
	 * @return string
	 */
	public function getSubmissionCookieName( $prefix = null ) {
		return $this->generateCookieName( ( $prefix ?: $this->getPrefix() ) . $this->getAction() . '_' . $this->getContestId() . '_' . $this->getSubmissionId() );
	}

	/**
	 * @param $prefix
	 *
	 * @return string
	 */
	public function getCategoryAwareSubmissionCookieName( $prefix = null ) {
		return $this->generateCookieName( ( $prefix ?: $this->getPrefix() ) . $this->getAction() . '_' . $this->getContestId() . '_' . $this->getSubmissionId() . '_c_' . $this->getCategoryId() );
	}

	abstract public function getPrefix();

	/**
	 * Generic
	 * @return bool|void
	 */
	public function apply() {
		$cookieTimeout = $this->getTimeout();

		if ( $this->getContestId() ):
			$cookieValue = $this->getCookie( $this->getContestCookieName(), 0 );
			$this->setCookie( $this->getContestCookieName(), (int) $cookieValue + 1, $cookieTimeout );
		endif;

		if ( $this->getSubmissionId() ):
			$cookieValue = $this->getCookie( $this->getSubmissionCookieName(), 0 );
			$this->setCookie( $this->getSubmissionCookieName(), (int) $cookieValue + 1, $cookieTimeout );
		endif;

		if ( $this->getCategoryId() ):
			$cookieValue = $this->getCookie( $this->getCategoryAwareSubmissionCookieName(), 0 );
			$this->setCookie( $this->getCategoryAwareSubmissionCookieName(), (int) $cookieValue + 1, $cookieTimeout );
		endif;
	}
}
