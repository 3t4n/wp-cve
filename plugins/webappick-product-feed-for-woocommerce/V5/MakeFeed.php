<?php

namespace CTXFeed\V5;

/**
 * Class MakeFeed
 *
 * @package    disco
 * @subpackage CTXFeed\V5
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class MakeFeed {

	/**
	 * @var array $product_ids Product IDs
	 */
	private $product_ids;

	/**
	 * @var array $config Feed Config
	 */
	private $config;

	/**
	 * MakeFeed constructor.
	 *
	 * @param array $config      Feed Config.
	 * @param array $product_ids Product IDs.
	 */
	public function __construct( $config, $product_ids ) {
		$this->product_ids = $product_ids;
		$this->config      = $config;
	}

	public function get_structure() {
		return 'test';
	}

	public function get_content() {
		// Get the feed structure.
		// Loop through the product IDs.
		// Get the product object.
		// Get the product ID.
		// Get the product object.
		// Get the product type.
		// If not, Variable Product Return Product Object.
		// If Variable then gets the variation according to the config.
		// Validate the product.
		// If not an object, return false.
		// If not purchasable, return false.
		// If not published, return false.
		// Filter the product.
		// If the filter is different from the default filter, then call filter class to validate.
		// Advance filter the product.
		// If Advance filter set, then call advance filter class to validate.
		// Get the product info.
		// Return the feed content.  (FileFactory::get_file_data)
	}

	public function shipping() {
		$shipping[] = array(
			'class_name_placeholder' => array(
				'country'  => 'US',
				'region'   => 'US',
				'postcode' => '1234',
				'price'    => '10',
			),
			array(
				'country'  => 'US',
				'region'   => 'US:NY',
				'postcode' => '12345',
				'price'    => '12',
			),
		);

		return $shipping;
	}

}
