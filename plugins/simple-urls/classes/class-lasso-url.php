<?php
/**
 * Declare Class Lasso_URL
 *
 * @package LassoLite
 */

namespace LassoLite\Classes;

use LassoLite\Classes\Affiliate_Link as Lasso_Affiliate_Link;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Lasso_URL
 *
 * @package Lasso\Libraries
 */
class Lasso_URL {
	/**
	 * Lasso id
	 *
	 * @var $lasso_id
	 */
	public $lasso_id;

	/**
	 * Name
	 *
	 * @var $name
	 */
	public $name;

	/**
	 * Primary button text
	 *
	 * @var $primary_button_text
	 */
	public $primary_button_text;

	/**
	 * Primary link
	 *
	 * @var $primary_link
	 */
	public $primary_link;

	/**
	 * Image src
	 *
	 * @var $image_src
	 */
	public $image_src;

	/**
	 * Target url
	 *
	 * @var $target_url
	 */
	public $target_url;

	/**
	 * Price
	 *
	 * @var $price
	 */
	public $price;

	/**
	 * Html attributes
	 *
	 * @var $html_attribute
	 */
	public $html_attribute;

	/**
	 * Public link
	 *
	 * @var $public_link
	 */
	public $public_link;

	/**
	 * Description html text
	 *
	 * @var $description
	 */
	public $description;

	/**
	 * Badge Text
	 *
	 * @var $badge_text
	 */
	public $badge_text;

	/**
	 * Lasso_URL constructor.
	 *
	 * @param int|object $lasso_url Lasso URL object or Lasso post id.
	 */
	public function __construct( $lasso_url ) {
		$lasso_url = is_object( $lasso_url ) ? $lasso_url : Lasso_Affiliate_Link::get_lasso_url( $lasso_url );

		$this->lasso_id            = $lasso_url->id;
		$this->name                = esc_html( $lasso_url->name );
		$this->primary_button_text = esc_html( $lasso_url->display->primary_button_text );
		$this->primary_link        = $lasso_url->target_url;
		$this->image_src           = $lasso_url->image_src;
		$this->target_url          = $lasso_url->target_url;
		$this->price               = esc_html( $lasso_url->price );
		$this->html_attribute      = $lasso_url->html_attribute;
		$this->public_link         = $lasso_url->public_link;
		$this->description         = $lasso_url->description;
		$this->badge_text          = esc_html( $lasso_url->display->badge_text );
	}

	/**
	 * Get link detail
	 *
	 * @return string
	 */
	public function get_link_detail() {
		return 'edit.php?post_type=surl&page=surl-url-details&post_id=' . $this->lasso_id;
	}

	/**
	 * Get Lasso_URL
	 *
	 * @param int $lasso_id Lasso ID.
	 *
	 * @return Lasso_URL
	 */
	public static function get_by_lasso_id( $lasso_id ) {
		return new self( $lasso_id );
	}

	/**
	 * Get price.
	 *
	 * @return string
	 */
	public function get_price() {
		return ! empty( $this->price ) ? $this->price : 'N/A';
	}

	/**
	 * Get description.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get attributes
	 *
	 * @return array
	 */
	private function get_attrs() {
		$attrs = array(
			'target' => $this->html_attribute->target,
			'href'   => $this->public_link,
			'title'  => $this->name,
		);
		return $attrs;
	}

	/**
	 * Render attributes.
	 *
	 * @param string $url Custom Url.
	 * @return string
	 */
	public function render_attributes( $url = null ) {
		$attrs = $this->get_attrs();

		if ( ! empty( $url ) ) {
			$attrs['href'] = esc_url( $url );
		}

		foreach ( $attrs as $key => $attr ) {
			$attrs[ $key ] = esc_attr( $attr );
		}

		$attrs_str  = self::generate_attrs( $attrs );
		$attrs_str .= $this->html_attribute->rel;

		return $attrs_str;
	}

	/**
	 * Generate attributes html
	 *
	 * @param array $attrs An attributes array.
	 *
	 * @return string
	 */
	public static function generate_attrs( $attrs ) {
		$attrs_str = '';
		foreach ( $attrs as $key => $attr ) {
			$attrs_str .= $key . '="' . $attr . '" ';
		}
		return $attrs_str;
	}
}
