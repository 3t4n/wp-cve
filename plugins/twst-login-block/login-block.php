<?php
/**
 * Plugin Name:     TWST Login Block
 * Description:     Easily insert a login form block into your post or page!
 * Version:         1.0.1
 * Author:          TWST
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     twst-login-block
 */

namespace TWST\WordPressBlock\Login;
const SLUG  = 'twst-login-block';
const BLOCK = 'twst/login';
const DIR   = __DIR__ . '/';

function init() {
	$script_asset = require( DIR . 'build/index.asset.php' );
	wp_register_script(
		SLUG . '-editor',
		plugins_url( 'build/index.js', __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( SLUG . '-editor', 'twst-login-block' );

	wp_register_style(
		SLUG . '-editor',
		plugins_url( 'build/index.css', __FILE__ ),
		[],
		filemtime( DIR . 'build/index.css' )
	);

	register_block_type( BLOCK, array(
		'editor_script'   => SLUG . '-editor',
		'editor_style'    => SLUG . '-editor',
		'uses_context'    => [ 'postId' ],
		'render_callback' => __NAMESPACE__ . '\render',
	) );
}
add_action( 'init', __NAMESPACE__ . '\init' );

function render( $attributes, $rendered_html, $block ) {

	$output = '<div class="twst-login login-block">';

	if ( is_user_logged_in() ) {

		$what_to_do = 'hide';
		if ( ! empty( $attributes['loggedInBehaviour'] ) ) {
			$what_to_do = $attributes['loggedInBehaviour'];
		}

		switch ( $what_to_do ) {
			case 'user':
				$output .= sprintf( __( 'Logged in as %s.', 'twst-login-block' ), wp_get_current_user()->display_name );

				$output .= ' ';
				// Add logout link.
			case 'logout':
				$redirect_to = false;
				if ( ! empty( $block->context['postId'] ) ) {
					$redirect_to = get_permalink( $block->context['postId'] );
				}

				$output .= wp_loginout( $redirect_to, false );
				$output .= '</div>';
				return $output;

			case 'hide':
			default:
				return '';

			case 'login':
				$attributes['defaultUsername'] = wp_get_current_user()->user_login;
				// Intentionally fall through.
		}
	}

	$attribute_to_form_arg = [
		'labelUsername'     => 'label_username',
		'defaultUsername'   => 'value_username',
		'labelPassword'     => 'label_password',
		'labelRememberMe'   => 'label_remember',
		'showRememberMe'    => 'remember',
		'defaultRememberMe' => 'value_remember',
		'labelLogIn'        => 'label_log_in',
	];

	$args = [
		'form_id' => SLUG,
		'echo'    => false,
	];

	if ( ! empty( $block->context['postId'] ) ) {
		$args['redirect'] = get_permalink( $block->context['postId'] );
	}

	foreach ( $attribute_to_form_arg as $attribute => $arg ) {
		if ( isset( $attributes[ $attribute ] ) && '' !== $attributes[ $attribute ] ) {
			$args[ $arg ] = $attributes[ $attribute ];
		}
	}

	/* Not all themes have proper form styling, do the minimal work to make labels work */
	$output .= '<style>.twst-login form label { display: block; }</style>';

	$output .= wp_login_form( $args );

	/* Most themes don't style submit buttons, style is like the Gutenberg Button Block */
	$output = preg_replace( '/(<input type="submit" [^>]+ class=")/', '$1 wp-block-button__link ', $output );

	$output .= '</div>';

	return $output;
}
