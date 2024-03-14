<?php
/**
 * Main file for handling public logic and conditions.
 *
 * @package Omnipress\Public
 */

namespace Omnipress\Publics;

use Omnipress\Helpers;
use Omnipress\Models\FontsModel;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Init {

	/**
	 * Current object instance.
	 *
	 * @var Init
	 */
	protected static $instance;

	/**
	 * Current object instance.
	 *
	 * @return Init
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'load_font_faces' ) );
	}

	public function load_font_faces() {
		$post_id = get_the_ID();

		$template_fonts      = get_option( 'omnipress_global_wp_template_fonts', array() );
		$template_part_fonts = get_option( 'omnipress_global_wp_template_part_fonts', array() );
		$post_fonts          = get_post_meta( $post_id, 'omnipress_post_type_fonts', true );

		if ( ! is_array( $post_fonts ) ) {
			$post_fonts = array();
		}

		$fonts = array_merge( $template_fonts, $template_part_fonts, $post_fonts );

		if ( ! $fonts ) {
			return;
		}

		$fontsmodel = new FontsModel();

		$font_faces = array();

		if ( ! empty( $fonts ) && is_array( $fonts ) ) {
			foreach ( $fonts as $font_args ) {

				$font_url = $fontsmodel->get_font_fileurl( $font_args['fontFamily'], $font_args['fontWeight'], $font_args['fontStyle'] );

				if ( ! $font_url ) {
					continue;
				}

				$font_faces[ Helpers::generate_font_id( $font_args['fontFamily'], $font_args['fontWeight'], $font_args['fontStyle'] ) ] = array(
					"font-family: {$font_args['fontFamily']};",
					"font-weight: {$font_args['fontWeight']};",
					"font-style: {$font_args['fontStyle']};",
					"src: url({$font_url});",
				);
			}
		}

		?>
		<style id="omnipress-load-font-faces">
			<?php
			if ( ! empty( $font_faces ) && is_array( $font_faces ) ) {
				foreach ( $font_faces as $font_face ) {
					?>
					@font-face { <?php echo esc_html( implode( ' ', $font_face ) ); ?> }
					<?php
				}
			}
			?>
		</style>
		<?php
	}
}
