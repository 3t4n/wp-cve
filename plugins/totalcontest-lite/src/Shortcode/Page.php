<?php

namespace TotalContest\Shortcode;

/**
 * Page shortcode class
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Page extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		return $this->getContest()
		            ->setMenuVisibility( false )
		            ->setScreen( 'contest.content' )
		            ->setCustomPageId( $this->getAttribute( 'page-id', 'home' ) )
		            ->render();
	}


}