<?php
/**
 * The login form shortcode.
 *
 * @since      3.1
 *
 * @category   WordPress\Plugin
 * @package    Connections_Directory\Shortcode
 * @subpackage Connections_Directory\Shortcode\Login_Form
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2023, Steven A. Zahm
 * @link       https://connections-pro.com/
 */

declare( strict_types=1 );

namespace Connections_Directory\Shortcode;

use Connections_Directory\Form;
use Connections_Directory\Request;
use Connections_Directory\Shortcode;
use Connections_Directory\Utility\_array;

/**
 * Class Login_Form
 *
 * @package Connections_Directory\Shortcode
 */
final class Login_Form extends Shortcode {

	use Do_Shortcode;
	use Get_HTML;

	/**
	 * The shortcode tag.
	 *
	 * @since 3.1
	 */
	const TAG = 'connections_login';

	/**
	 * Login_Form constructor.
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
	 * @since 10.4.55
	 *
	 * @return array
	 */
	protected function getDefaultAttributes(): array {

		$request  = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$redirect = Request\Redirect::input()
									->setDefault( $request )
									->value();

		return array(
			'redirect'       => $redirect,
			'form_id'        => 'loginform',
			'label_username' => __( 'Username', 'connections_login' ),
			'label_password' => __( 'Password', 'connections_login' ),
			'label_remember' => __( 'Remember Me', 'connections_login' ),
			'label_log_in'   => __( 'Log In', 'connections_login' ),
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => null,
			'value_remember' => false,
		);
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

		_array::set( $this->attributes, 'remember', $this->attributes['remember'] );
		_array::set( $this->attributes, 'submit.id', $this->attributes['id_submit'] );
		_array::set( $this->attributes, 'submit.text', $this->attributes['label_log_in'] );

		$form = Form\User_Login::create( $this->attributes );

		$form->setFieldValue( 'log', $this->attributes['value_username'] );

		if ( empty( $this->attributes['redirect'] ) ) {

			$this->attributes['redirect'] = get_home_url();
		}

		$form->setRedirect( $this->attributes['redirect'] );

		if ( $this->attributes['value_remember'] ) {

			$form->setFieldValue( 'rememberme', '1' );
		}

		return $form->getHTML();
	}
}
