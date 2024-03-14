<?php
/**
 * Plugin Name:       Nutrifox WP Connector
 * Plugin URI:        https://nutrifox.com/
 * Description:       Embed Nutrifox recipes in WordPress in one moment or less.
 * Author:            Nutrifox
 * Author URI:        https://nutrifox.com/
 * Text Domain:       nutrifox
 * Domain Path:       /languages
 * Version:           0.2.0
 * GitHub Plugin URI: https://github.com/pinchofyum/nutrifox-plugin
 *
 * @package           Nutrifox
 */

/**
 * Register the Nutrifox shortcode and oEmbed handler
 */
function nutrifox_action_init() {
	add_shortcode( 'nutrifox', 'nutrifox_shortcode' );
	wp_embed_register_handler( 'nutrifox', '#^https?://nutrifox\.com/(embed/label|recipes)/(?P<id>\d+)(/edit)?#', 'nutrifox_shortcode' );
	if ( function_exists( 'register_block_type' ) ) {
		$time = filemtime( dirname( __FILE__ ) . '/assets/js/nutrifox-resize.js' );
		wp_register_script(
			'nutrifox-resize',
			plugins_url( 'assets/js/nutrifox-resize.js?r=' . (int) $time, __FILE__ )
		);
		$time = filemtime( dirname( __FILE__ ) . '/assets/dist/block-editor.build.js' );
		wp_register_script(
			'nutrifox-block-editor',
			plugins_url( 'assets/dist/block-editor.build.js?r=' . (int) $time, __FILE__ ),
			array(
				'nutrifox-resize',
				'wp-blocks',
				'wp-editor',
				'wp-element',
				'wp-components',
				'wp-data',
				'wp-i18n',
			)
		);
		register_block_type(
			'nutrifox/nutrifox',
			array(
				'attributes'      => array(
					'id'  => array(
						'type' => 'number',
					),
					'url' => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'nutrifox-block-editor',
				'render_callback' => 'nutrifox_shortcode',
			)
		);
	}
}
add_action( 'init', 'nutrifox_action_init' );

/**
 * Render the Nutrifox shortcode
 *
 * @param array $attr Shortcode attributes.
 */
function nutrifox_shortcode( $attr ) {
	if ( empty( $attr['id'] ) && empty( $attr['url'] ) ) {
		return '';
	}
	if ( ! empty( $attr['url'] ) ) {
		if ( is_numeric( $attr['url'] ) ) {
			$attr['id'] = $attr['url'];
		} else {
			preg_match( '#^https?://nutrifox\.com/(embed/label|recipes)/(?P<id>\d+)(/edit)?#', $attr['url'], $matches );
			if ( empty( $matches ) ) {
				if ( current_user_can( 'edit_posts' ) ) {
					return __( 'Invalid Nutrifox URL provided.', 'nutrifox' );
				}
				return '';
			}
			$attr['id'] = $matches['id'];
		}
	}
	$nutrifox_id            = (int) $attr['id'];
	$nutrifox_iframe_url    = sprintf( 'https://nutrifox.com/embed/label/%d', $nutrifox_id );
	$nutrifox_resize_script = file_get_contents( dirname( __FILE__ ) . '/assets/js/nutrifox-resize.js' );
	ob_start(); ?>
	<script type="text/javascript">
		<?php echo $nutrifox_resize_script; ?>
	</script>
<iframe id="<?php echo esc_attr( 'nutrifox-label-' . $nutrifox_id ); ?>" src="<?php echo esc_url( $nutrifox_iframe_url ); ?>" style="width:100%;border-width:0;"></iframe>
	<?php
	return trim( ob_get_clean() );
}

/**
 * Transform Nutrifox embed codes into their shortcode
 *
 * @param string $content Content to search through.
 * @return string
 */
function nutrifox_filter_content_save_pre( $content ) {

	if ( false === stripos( $content, 'nutrifox-label' ) ) {
		return $content;
	}

	// $content comes through slashed
	$needle = '#<div class=\\\"nutrifox-label.+data-recipe-id=\\\"([^"]+)\\\".+\n*<script[^>]+src=\\\"https://nutrifox\.com/embed\.js[^>]+></script>#';
	if ( preg_match_all( $needle, $content, $matches ) ) {
		$replacements = array();
		foreach ( $matches[0] as $key => $value ) {
			$content = str_replace( $value, '[nutrifox id=\"' . (int) $matches[1][ $key ] . '\"]', $content );
		}
	}

	return $content;
}
add_action( 'content_save_pre', 'nutrifox_filter_content_save_pre' );
