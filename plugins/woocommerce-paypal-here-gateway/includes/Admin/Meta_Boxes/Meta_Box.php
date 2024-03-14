<?php
/**
 * WooCommerce PayPal Here Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace Automattic\WooCommerce\PayPal_Here\Admin\Meta_Boxes;

defined( 'ABSPATH' ) or exit;

/**
 * Abstract Meta Box Class.
 *
 * @since 1.0.0
 */
abstract class Meta_Box {


	/** @var string meta box title */
	protected $title;

	/** @var string priority of the meta box */
	protected $priority = 'default';

	/** @var string the context of the meta box */
	protected $context = 'normal';


	/**
	 * Outputs the meta box markup.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post the post object
	 */
	abstract public function output( $post );


	/**
	 * Saves the data inside this meta box.
	 *
	 * @since 1.0.0
	 */
	public function save() {}


	/**
	 * Gets the title of the meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {

		return $this->title;
	}


	/**
	 * Gets the priority of the meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_priority() {

		return $this->priority;
	}


	/**
	 * Gets the context of the meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_context() {

		return $this->context;
	}


}
