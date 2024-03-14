<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class ShortCode extends Main {

	public    static function load(): void {

		\add_shortcode( self::pf . 'webmail', static function( /*string|array */$atts, ?string $content = null, ?string $tag = null ): string {
			self::init();

			if ( UAPI::has_features( [ 'webmail' ] ) ) {

				\extract( \shortcode_atts( [
					'email'         => \is_user_logged_in() ? \wp_get_current_user()->user_email : '',
					'target'        => \apply_filters( self::pf . 'shortcode_webmail_target',      '_blank', $atts ),
					'before_form'   => \apply_filters( self::pf . 'shortcode_webmail_before_form', '',       $atts ),
					'after_form'    => \apply_filters( self::pf . 'shortcode_webmail_after_form',  '',       $atts ),
					'form_class'    => \apply_filters( self::pf . 'shortcode_webmail_form_class',   [ self::$pf . 'webmail' ],    $atts ),
					'button_class'  => \apply_filters( self::pf . 'shortcode_webmail_button_class', [ 'open-webmail', 'button' ], $atts ),
					'not_logged_in' => \apply_filters( self::pf . 'shortcode_webmail_not_logged_in', _x( 'Not logged in',          'Shortcode fallback text' ), $atts ),
					'no_account'    => \apply_filters( self::pf . 'shortcode_webmail_no_account',    _x( 'No cPanel® account yet, or last session was used (wait)', 'Shortcode fallback text' ), $atts ),
					], (array) $atts )
				);

				if ( \sanitize_email( $email ) ) {
					$content = \apply_filters( self::pf . 'shortcode_webmail_button_label', $content ) ?: __( 'cPanel® Webmail', 'Button Label' );
					$user    = \explode( '@', $email )[0];
					$domain  = \explode( '@', $email )[1] ?? '';

					if ( \str_starts_with( self::$main_domain, $domain ) && $user === self::$cpanel_user ) {
						$session = UAPI::create_main_webmail_session();
					} else {
						$session = UAPI::create_webmail_session( $email );
					}

					if ( ( $session->token ?? '' ) && ( $session->session ?? '' ) ) {
						$action = 'https://' . $session->hostname . ':2096' . $session->token . '/login';
						return $before_form . \strip_tags( '<form method="post" action="' . $action . '" target="' . $target . '" class="' . \implode( ' ', $form_class ) . '"><input type="hidden" name="session" value="' . $session->session . '"/><button type="submit" class="' . \implode( ' ', $button_class ) .'">' . $content . '</button></form>', [ 'form', 'input', 'button' ] ) . $after_form;
					} else {
						return \wp_kses_post( $no_account );
					}
				} else {
					return \wp_kses_post( $not_logged_in );
				}
			} else {
				return _x( 'No Webmail feature available in cPanel®', 'Shortcode fallback text' );
			}
		} );

		\add_action( 'init', static function(): void {
			$block = 'webmail';
			self::init();

			\wp_register_script( self::$pf . $block,
				\plugins_url( 'includes/blocks/' . $block . '/block.js', PLUGIN_FILE ),
				[
					'wp-block-editor',
					'wp-blocks',
					'wp-element',
					'wp-i18n',
				],
				self::$plugin->Version,
			);
			\wp_set_script_translations( self::$pf . $block, self::$plugin->TextDomain );

			\register_block_type(
				PLUGIN_DIR . '/includes/blocks/' . $block, [
					'editor_script'   => self::$pf . $block,
					'render_callback' => static function( array $attributes ) use( $block ): string {
						$atts = '';

						foreach ( $attributes as $key => $attribute ) {
							$atts .= ' ' . $key . '="' . $attribute . '"';
						}
						return \apply_shortcodes( '[' . self::pf . 'webmail' . $atts . ']' );
					}
				],
			);
		} );
	}
}