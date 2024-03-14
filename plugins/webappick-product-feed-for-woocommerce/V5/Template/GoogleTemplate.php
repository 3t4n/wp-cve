<?php
/**
 * Class Google Template
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */

namespace CTXFeed\V5\Template;

use CTXFeed\V5\File\FileFactory;
use CTXFeed\V5\Product\ProductFactory;

/**
 * Class Google Template
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Template
 * @category   MyCategory
 */
class GoogleTemplate implements TemplateInterface {

	/**
	 * @var \CTXFeed\V5\Utility\Config $config Contain Feed Config.
	 */
	private $config;

	/**
	 * @var array $ids Contain Product Ids.
	 */
	private $ids;

	/**
	 * @var array $structure Contain Feed Structure.
	 */
	private $structure;

	/**
	 * GoogleTemplate constructor.
	 *
	 * @param array                      $ids       Contain Product Ids.
	 * @param \CTXFeed\V5\Utility\Config $config    Contain Feed Config.
	 * @param array                      $structure Contain Feed Structure.
	 */
	public function __construct( $ids, $config, $structure ) {
		$this->ids       = $ids;
		$this->config    = $config;
		$this->structure = $structure;
	}

	/**
	 * Get Feed Body.
	 *
	 * @return false|string
	 * @throws \Exception Exception.
	 */
	public function get_feed() {
		$feed = ProductFactory::get_content( $this->ids, $this->config, $this->structure );

		return $feed->make_body();
	}

	/**
	 * Get Feed Header.
	 *
	 * @return mixed
	 */
	public function get_header() {
		$feed = FileFactory::get_file_data( $this->structure, $this->config );
		$feed = $feed->make_header_footer();

		return $feed['header'];
	}

	/**
	 * Get Feed Footer.
	 *
	 * @return mixed
	 */
	public function get_footer() {
		$feed = FileFactory::get_file_data( $this->ids, $this->config );
		$feed = $feed->make_header_footer();

		return $feed['footer'];
	}

}
