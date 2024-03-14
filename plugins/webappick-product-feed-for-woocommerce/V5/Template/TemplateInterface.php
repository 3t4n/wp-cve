<?php
/**
 * Class Template Factory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */

namespace CTXFeed\V5\Template;

/**
 * Class Template Factory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */
interface TemplateInterface {//phpcs:ignore

	/**
	 * Get Feed.
	 *
	 * @return mixed
	 */
	public function get_feed();

	/**
	 * Get Header.
	 *
	 * @return mixed
	 */
	public function get_header();

	/**
	 * Get Footer.
	 *
	 * @return mixed
	 */
	public function get_footer();

}
