<?php
/**
 * Class Template
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */

namespace CTXFeed\V5\Template;

/**
 * Class Template
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */
class Template {

	/**
     * @var \CTXFeed\V5\Template\TemplateInterface $template Template Interface.
     */
	private $template;

	/**
	 * Template constructor.
	 *
	 * @param \CTXFeed\V5\Template\TemplateInterface $template Template Interface.
	 */
	public function __construct( TemplateInterface $template ) {
		$this->template = $template;
	}

	/**
	 * Get Feed Body.
	 *
	 * @return false|string
	 */
	public function get_feed() {
		return $this->template->get_feed();
	}

	/**
	 * Get Feed Header.
	 *
	 * @return mixed
	 */
	public function get_header() {
		return $this->template->get_header();
	}

	/**
	 * Get Feed Footer.
	 *
	 * @return mixed
	 */
	public function get_footer() {
		return $this->template->get_footer();
	}

}
