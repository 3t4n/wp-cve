<?php
/**
 * Settings
 *
 * @package     MailerLiteFIRSS\Settings
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MailerLite_FIRSS_Settings' ) ) {

	class MailerLite_FIRSS_Settings {

		public $options;

		/**
		 * MailerLite_RSS_Feed_Image_Settings constructor.
		 */
		public function __construct() {

			// Options
			$this->options = mailerlite_firss_get_options();

			// Initialize
			add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( &$this, 'init_settings' ) );
		}

		/**
		 * Register admin menu
		 */
		function add_admin_menu() {

			add_options_page(
				'Featured Image in RSS Feed by MailerLite', // Page title
				'Featured Image in RSS Feed', // Menu title
				'manage_options', // Capabilities
				'mailerlite_firss', // Menu slug
				array( &$this, 'options_page' ) // Callback
			);

		}

		/**
		 * Register settings
		 */
		function init_settings() {

			register_setting(
				'mailerlite_firss',
				'mailerlite_firss',
				array( &$this, 'validate_input_callback' )
			);

			add_settings_section(
				'mailerlite_firss',
				false,
				false,
				'mailerlite_firss'
			);

			add_settings_field(
				'mailerlite_firss_image_size',
				__( 'Image Size', 'mailerlite-featured-image-in-rss-feed' ),
				array( &$this, 'image_size_render' ),
				'mailerlite_firss',
                'mailerlite_firss',
				array('label_for' => 'mailerlite_firss_image_size')
			);

			add_settings_field(
				'mailerlite_firss_image_alignment',
				__( 'Image Alignment', 'mailerlite-featured-image-in-rss-feed' ),
				array( &$this, 'image_alignment_render' ),
				'mailerlite_firss',
				'mailerlite_firss',
				array('label_for' => 'mailerlite_firss_image_alignment')
			);

		}

		/**
         * Validate input callback
         *
		 * @param $input
		 *
		 * @return mixed
		 */
		function validate_input_callback( $input ) {

		    // Silence

			return $input;
		}

		/**
		 * Image size setting
		 */
		function image_size_render() {

			$image_sizes = get_intermediate_image_sizes();

			$image_size = ( isset ( $this->options['image_size'] ) ) ? $this->options['image_size'] : mailerlite_firss_get_option_default_value( 'image_size' );

			?>
            <select id="mailerlite_firss_image_size" name="mailerlite_firss[image_size]">
				<?php foreach ( $image_sizes as $image_size_name ) { ?>
                    <option value="<?php echo $image_size_name; ?>" <?php selected( $image_size, $image_size_name ); ?>><?php echo ucfirst( $image_size_name ); ?></option>
				<?php } ?>
            </select>
            <p class="description">
                <?php printf( __( 'Set the size of feed image. Customize image sizes in <a href="%1$s">media options</a>. Afterwards, don\'t forget to <a href="%2$s" target="_blank">regenerate thumbnails</a>.', 'mailerlite-featured-image-in-rss-feed' ), admin_url( 'options-media.php' ), 'http://wordpress.org/plugins/regenerate-thumbnails/' ); ?>
            </p>
			<?php
		}

		/**
		 * Image alignment setting
		 */
		function image_alignment_render() {

			$image_alignments = array(
                'left-above' => __( 'Image Left Above Text', 'mailerlite-featured-image-in-rss-feed' ),
                'centered-above' => __( 'Image Centered Above Text', 'mailerlite-featured-image-in-rss-feed' ),
                'left-wrap' => __( 'Image Left Text Wraps', 'mailerlite-featured-image-in-rss-feed' ),
                'right-wrap' => __( 'Image Right Text Wraps', 'mailerlite-featured-image-in-rss-feed' )
            );

			$image_alignment = ( isset ( $this->options['image_alignment'] ) ) ? $this->options['image_alignment'] : mailerlite_firss_get_option_default_value( 'image_alignment' );
			?>
            <select id="mailerlite_firss_image_alignment" name="mailerlite_firss[image_alignment]">
				<?php foreach ( $image_alignments as $key => $label ) { ?>
                    <option value="<?php echo $key; ?>" <?php selected( $image_alignment, $key ); ?>><?php echo $label; ?></option>
				<?php } ?>
            </select>
            <p class="description">
                <?php _e('Set alignment of feed image.', 'mailerlite-featured-image-in-rss-feed'); ?>
            </p>
			<?php
		}

		/**
		 * Output options page
		 */
		function options_page() {
			?>

            <div class="wrap">
                <h1><?php _e( 'Featured Image in RSS Feed by MailerLite', 'mailerlite-featured-image-in-rss-feed' ); ?></h1>

                <form action="options.php" method="post">
					<?php
					settings_fields( 'mailerlite_firss' );
					do_settings_sections( 'mailerlite_firss' );
					?>

                    <p><?php submit_button( 'Save Changes', 'button-primary', 'submit', false ); ?></p>
                </form>
            </div>
			<?php
		}
	}
}

new MailerLite_FIRSS_Settings();