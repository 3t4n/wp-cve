<?php

/**
 * Abstract class for seo related functionalities
 *
 * @since 1.9.4
 *
 */
abstract class Abstract_Seo {
	/**
	 * Singleton instance.
	 */
	protected static $instance;

	/**
	 * Contain data for changing a meta value.
	 */
	protected $data = array();

	/**
	 * This function for getting, seo instance
	 * (singleton).
	 */
	abstract public static function getInstance();

	/**
	 * This function for saving a postMata data.
	 *
	 * @param $post_id
	 */
	abstract public function saveMetaForPost( $post_id );

	/**
	 * Checks weather sitemap requested
	 */
	abstract public function isSitemapRequested();
}