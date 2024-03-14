<?php
/*
  Plugin Name: Zoom Image
  Plugin URI: http://www.outsourcing-webdesign.com/wordpress-plugins/
  Description: Add zoom efect over featured image, thumbnails and variations on WooCommerce shops in a simple and elegant mode.
  Author: Alexandru Muscalu
  Version: 1.7.1
  Author URI: http://www.outsourcing-webdesign.com
  License: GPLv2 or later
 */

class TccZoom {

	var $pluginPath;
	var $pluginUrl;

	public function __construct() {
		// Set Plugin Path
		$this->pluginPath = dirname( __FILE__ );

		// Set Plugin URL
		$this->pluginUrl = WP_PLUGIN_URL . '/zoom-image';



		add_filter( 'woocommerce_single_product_image_html', array( &$this, 'apply_zoom_main_image' ), 11, 1 );
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( &$this, 'apply_zoom_thumbnails' ), 11, 1 );

		add_action( 'woocommerce_product_thumbnails', array( &$this, 'add_thumbnails_zoom_scription' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'add_scripts' ), 'jquery' );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( &$this, 'add_zoom_image_plugin_page' ) );
			add_action( 'admin_init', array( &$this, 'zoom_image_init' ) );

			add_action( 'admin_enqueue_scripts', array( &$this, 'wp_enqueue_color_picker' ) );
		}
	}

	static function install() {
		add_option( 'zoom_image_options', array( 'zoom_thumbnails' => '1' ) );
	}

	function wp_enqueue_color_picker() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-script', plugins_url( '/js/colorPicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}

	public function add_zoom_image_plugin_page() {
		// This page will be under "Settings"
		add_options_page( 'Zoom Image Settings', 'Zoom Image', 'manage_options', 'zoom-image', array( $this, 'create_zoom_image_page' ) );
	}

	public function create_zoom_image_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Zoom Image Settings</h2>		

			<p>
				Zoom Image is an WordPress plugin that adds zooming effect over WooCommerce shops on featured images, thumbnails and variations in a simple and elegant mode.
			</p>

			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'zoom_image_group' );
				do_settings_sections( 'zoom_image_settings' );
				?>
				<?php submit_button(); ?>
			</form>

			<p>
				For better understanding on how Zoom Image works please check the official page of the plugin: <a href="http://www.outsourcing-webdesign.com/wordpress-plugins/" target="_blank">http://www.outsourcing-webdesign.com/wordpress-plugins/</a>.
			</p>
		</div>
		<?php
	}

	public function zoom_image_init() {

		register_setting( 'zoom_image_group', 'zoom_image_options', array( $this, 'check_zoom_image_options' ) );

		add_settings_section(
				'general_zoom_settings', 'Zoom image options', array( $this, 'print_section_info' ), 'zoom_image_settings'
		);

		add_settings_field(
				'zoom_thumbnails', 'Zoom over thumbnails', array( $this, 'create_zoom_thumbnails_field' ), 'zoom_image_settings', 'general_zoom_settings'
		);
		/*
		  add_settings_field(
		  'zoom_level',
		  'Zoom level',
		  array($this, 'create_zoom_level_field'),
		  'zoom_image_settings',
		  'general_zoom_settings'
		  );
		 */
		add_settings_field(
				'zoom_type', 'Zoom effect style', array( $this, 'create_zoom_inner_field' ), 'zoom_image_settings', 'general_zoom_settings'
		);
		/*
		  add_settings_field(
		  'zoom_background_color',
		  'Background color',
		  array($this, 'create_zoom_color_field'),
		  'zoom_image_settings',
		  'general_zoom_settings'
		  );
		 */
	}

	public function check_zoom_image_options( $input ) {

		if ( ! in_array( $input['zoom_thumbnails'], array( 0, 1 ) ) ) {
			$input['zoom_thumbnails'] = '';
		}

		if ( ! in_array( $input['zoom_level'], array( 0.5, 1, 2 ) ) ) {
			$input['zoom_level'] = '';
		}

		if ( ! in_array( $input['zoom_type'], array( 'window', 'inner', 'lens' ) ) ) {
			$input['zoom_type'] = '';
		}

		return $input;
	}

	public function print_section_info() {
		//print 'Enter your setting below:';
	}

	public function create_zoom_thumbnails_field() {

		$options = get_option( 'zoom_image_options' );
		?>

		<table class="form-table" style="margin-top:0px;">
			<tr valign="top">
				<td style="padding:0px;">
					<input type="checkbox" id="zoom_over_thumbnails" name="zoom_image_options[zoom_thumbnails]" value="1" <?php checked( 1, $options['zoom_thumbnails'], true ); ?> />
				</td>
				<td style="border:1px solid #999; padding:5px 0 5px 5px; color:#999;">
					Thick the checkbox to add zoom effect over thumbnails.    
				</td>
			</tr>
		</table>


		<?php
	}

	public function create_zoom_inner_field() {

		$options = get_option( 'zoom_image_options' );
		?>


		<table class="form-table"  style="margin-top:0px;">
			<tr valign="top">
				<td style="padding:0px;">
					<select name="zoom_image_options[zoom_type]">
						<option value="window" <?php selected( 'window', $options['zoom_type'], true); ?>>Window</option>
						<option value="inner" <?php selected( 'inner', $options['zoom_type'], true); ?>>Inner</option>
					</select>
				</td>
				<td style="border:1px solid #999; padding:0 0 0 5px; color:#999;">
					Select one from the two options to change the zoom effect over the featured image.    
				</td>
			</tr>
		</table>

		<?php
	}

	public function create_zoom_level_field() {
		$options = get_option( 'zoom_image_options' );
		?>
		<select name="zoom_image_options[zoom_level]">
			<option value="1" <?php selected( 1, $options['zoom_level'], true); ?>>Normal zoom</option>
			<option value="2" <?php selected( 2, $options['zoom_level'], true); ?>>Twice as small</option>
		</select>
		<?php
	}

	public function create_zoom_color_field() {
		$options = get_option( 'zoom_image_options' );
		?>	
		<input type="text" id="zoom_background" class="wp-color-picker-field" name="zoom_image_options[zoom_background_color]" value="<?php echo esc_attr( $options['zoom_background_color'] ); ?>" />
		<br />
		<span>Available only for Zoom type: window</span>
		<?php
	}

	function apply_zoom_thumbnails( $thumbnails_html ) {
		$options = get_option( 'zoom_image_options' );


		$doc = new DOMDocument( '1.0', 'UTF-8' );
		$doc->loadHTML( $thumbnails_html );

		$links = $doc->getElementsByTagName( 'a' );
		if ( $links ) {
			foreach ( $links as $link ) {
				$classes = $link->getAttribute( 'class' );
				$link->setAttribute( 'class', $classes . ' thumb-image-add-zoom-image-feature' );
				$new_link = $doc->saveHTML();
			}
		}
		
		$html = preg_replace( '~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $new_link );

		return apply_filters( 'zoom_thumbnails_output', $html );
	}

	function add_thumbnails_zoom_scription( $html ) {

		$options = get_option( 'zoom_image_options' );

		ob_start();

		if ( ! empty( $options ) && $options['zoom_thumbnails'] == 1 ) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
				jQuery('.thumb-image-add-zoom-image-feature img').each(function(){
				jQuery(this).attr('data-zoom-image', jQuery(this).parent().attr('href'));
				});
						jQuery('.thumb-image-add-zoom-image-feature img').elevateZoom({
				zoomType  : "window",
						lensShape : "square",
						lensSize  :	20,
						zoomWindowPosition: 16,
						zoomWindowOffetx: 10,
			<?php if ( $options['zoom_level'] ) { ?>
					zoomLevel :	<?php echo strip_tags( trim( $options['zoom_level'] ) ); ?>,
				<?php
			}
			else {
				?>
					zoomLevel :	1,
			<?php } ?>
			<?php
			if ( strlen( trim( $options['zoom_background_color'] ) ) > 1 ) {
				?>
					tint:true,
							tintColour:'<?php echo esc_attr( $options['zoom_background_color'] ); ?>',
							tintOpacity:0.5
				<?php
			}
			?>
				});
				});</script>
			<?php
		}
		
		$html = ob_get_clean();
		
		echo apply_filters( 'zoom_thumbnails_scripts_output', $html );
	}

	function apply_zoom_main_image( $link_image ) {

		$doc = new DOMDocument( '1.0', 'UTF-8' );
		$doc->loadHTML( $link_image );

		$links = $doc->getElementsByTagName( 'a' );
		if ( $links ) {
			foreach ( $links as $link ) {
				$classes = $link->getAttribute( 'class' );
				$link->setAttribute( 'class', $classes . ' main-image-add-zoom-image-feature' );
				$new_link = $doc->saveHTML();
			}
		}


		//global $post;
		//$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' );

		$options = get_option( 'zoom_image_options' );

		ob_start();
		echo preg_replace( '~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $new_link );
		?>
		<script type="text/javascript">
			
			var $i = 0;
			var newSmallPicture = jQuery('.main-image-add-zoom-image-feature img').attr('src');
			
			/* variations scripting */
			jQuery(window).load(function($){
				function changePicture()
				{
					if( ( jQuery('.main-image-add-zoom-image-feature img').attr('src') != newSmallPicture ) || ( 0 == $i ) ) {
						
						var newLargePicture = jQuery('.main-image-add-zoom-image-feature').attr('href');
						newSmallPicture = jQuery('.main-image-add-zoom-image-feature img').attr('src');
						jQuery('.main-image-add-zoom-image-feature img').attr('data-zoom-image', newLargePicture);
						var ez = jQuery('.main-image-add-zoom-image-feature img').data('elevateZoom');
						ez.swaptheimage(newSmallPicture, newLargePicture);
						
						$i++;
					}
				}
				
				if( 0 == $i ) {
					changePicture();
				}
				
				jQuery(".main-image-add-zoom-image-feature img").load(function(){ changePicture(); });
			});
			
			/* end of variations */
			jQuery(document).ready(function($){
				jQuery('.main-image-add-zoom-image-feature img').attr('data-zoom-image', jQuery('.main-image-add-zoom-image-feature').attr('href'));
				jQuery('.main-image-add-zoom-image-feature img').elevateZoom({
			<?php if ( $options['zoom_level'] ) { ?>
					zoomLevel :	<?php echo strip_tags( trim( $options['zoom_level'] ) ); ?>,
				<?php
			}
			else {
				?>
					zoomLevel :	1,
			<?php } ?>

			<?php
			switch ( $options['zoom_type'] ) {
				case 'window':
					?>
						zoomType  : "window",
								lensShape : "square",
					<?php
					break;
				case 'lens':
					?>
						zoomType	: "lens",
								lensShape : "round",
					<?php
					break;
				case 'inner':
					?>
						zoomType : "inner",
								cursor : "crosshair",
					<?php
					break;
				default:
					?>
						zoomType  : "window",
								lensShape : "square",
					<?php
					break;
			}
			if ( strlen( trim( $options['zoom_background_color'] ) ) > 1 && $options['zoom_type'] == 'window' ) {
				?>
					tint:true,
							tintColour:'<?php echo esc_attr( $options['zoom_background_color'] ); ?>',
							tintOpacity:0.5
				<?php
			}
			?>
				});
			});
		</script>
		<?php
		
		$html = ob_get_clean();
		
		return apply_filters( 'zoom_main_image_output', $html );
	}

	function add_scripts() {
		wp_enqueue_script( 'tcc-magnifier-js', $this->pluginUrl . '/js/jquery.elevateZoom-3.0.8.min.js', array( 'jquery' ) );
	}

}

$tcczoom = new TccZoom;
register_activation_hook( __FILE__, array( 'TccZoom', 'install' ) );
