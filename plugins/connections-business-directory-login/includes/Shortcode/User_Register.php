<?php
/**
 * The user registration form shortcode.
 *
 * @since      3.1
 *
 * @category   WordPress\Plugin
 * @package    Connections_Directory\Shortcode
 * @subpackage Connections_Directory\Shortcode\User_Register
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2023, Steven A. Zahm
 * @link       https://connections-pro.com/
 */

declare( strict_types=1 );

namespace Connections_Directory\Shortcode;

use Connections_Directory\Form;
use Connections_Directory\Shortcode;

/**
 * Class User_Register
 *
 * @package Connections_Directory\Shortcode
 */
final class User_Register extends Shortcode {

	use Do_Shortcode;
	use Get_HTML;

	/**
	 * The shortcode tag.
	 *
	 * @since 3.1
	 */
	const TAG = 'connections_user_registration';

	/**
	 * Generate the shortcode HTML.
	 *
	 * @since 3.1
	 *
	 * @param array  $untrusted The shortcode arguments.
	 * @param string $content   The shortcode content.
	 * @param string $tag       The shortcode tag.
	 */
	public function __construct( array $untrusted, string $content = '', string $tag = self::TAG ) {

		$this->tag = $tag;

		$defaults  = $this->getDefaultAttributes();
		$untrusted = shortcode_atts( $defaults, $untrusted, $tag );

		$this->attributes = $this->prepareAttributes( $untrusted );
		$this->content    = $content;
		$this->html       = is_user_logged_in() ? '' : $this->generateHTML();
	}

	/**
	 * The shortcode attribute defaults.
	 *
	 * @since 3.1
	 *
	 * @return array
	 */
	protected function getDefaultAttributes(): array {
		return array();
	}

	/**
	 * Parse and prepare the shortcode attributes.
	 *
	 * @since 3.1
	 *
	 * @param array $attributes The shortcode arguments.
	 *
	 * @return array
	 */
	protected function prepareAttributes( array $attributes ): array {
		return $attributes;
	}

	/**
	 * Generate the shortcode HTML.
	 *
	 * @since 3.1
	 *
	 * @return string
	 */
	protected function generateHTML(): string {
		return Form\User_Register::create()->getHTML();
	}
}
