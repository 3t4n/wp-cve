<?php

namespace QuadLayers\STFT;

final class Plugin {

	protected static $instance;
	private $options;

	private function __construct() {
		/**
		 * Load plugin textdomain.
		 */
		load_plugin_textdomain( 'storefront-footer', false, QLSTFT_PLUGIN_DIR . '/languages/' );

		add_action( 'init', array( $this, 'options' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	public function options() {
		global $storefront_footer;

		$title       = get_bloginfo( 'title' );
		$url         = get_bloginfo( 'url' );
		$description = get_bloginfo( 'description' );

		$defaults = array(
			'footer_credit' => "© QuadLayers 2018 <br/> <a href='#' target='_blank' title='{$url}' rel='author'>{$title}</a> - {$description}",
		);

		$storefront_footer = $this->options = wp_parse_args( (array) get_option( 'storefront_footer' ), $defaults );
	}

	public function add_plugin_page() {
		add_options_page( 'Settings Admin', 'Storefront Footer', 'manage_options', 'storefront-footer', array( $this, 'create_admin_page' ) );
	}

	public function create_admin_page() {
		?>
		<div class="wrap about-wrap">

			<h1><?php echo esc_html( QLSTFT_PLUGIN_NAME ); ?></h1>

			<p class="about-text"><?php printf( esc_html__( 'Thanks for using %s! We will do our best to offer you the best and improved experience with our products.', 'storefront-footer' ), esc_html( QLSTFT_PLUGIN_NAME ) ); ?></p>

			<p class="about-text">
				<?php printf( '<a href="%s" target="_blank">%s</a>', 'https://quadlayers.com/?utm_source=qlstft_admin', esc_html__( 'About Us', 'storefront-footer' ) ); ?></a> |
				<?php printf( '<a href="%s" target="_blank">%s</a>', 'https://quadlayers.com/shop/?utm_source=qlstft_admin', esc_html__( 'Products', 'storefront-footer' ) ); ?></a>
			</p>

			<?php
			printf(
				'<a href="%s" target="_blank"><div style="
		 background: #006bff url(%s) no-repeat;
		 background-position: top center;
		 background-size: 130px 130px;
		 color: #fff;
		 font-size: 14px;
		 text-align: center;
		 font-weight: 600;
		 margin: 5px 0 0;
		 padding-top: 120px;
		 height: 40px;
		 display: inline-block;
		 width: 140px;
		 " class="wp-badge">%s</div></a>',
				'https://quadlayers.com/?utm_source=qlstft_admin',
				plugins_url( '/assets/quadlayers.jpg', QLSTFT_PLUGIN_FILE ),
				esc_html__( 'QuadLayers', 'storefront-footer' )
			);
			?>

		</div>
		<style>
			.about-wrap>form h2 {
				display: none;
			}
		</style>
		<div class="wrap about-wrap">
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'storefront_footer' );
				do_settings_sections( 'storefront-footer' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function page_init() {
		register_setting( 'storefront_footer', 'storefront_footer', array( $this, 'sanitize' ) );

		add_settings_section( 'setting_section_id', 'Settings', array( $this, 'print_section_info' ), 'storefront-footer' );

		add_settings_field( 'footer_credit', 'Footer Credit', array( $this, 'footer_credit_callback' ), 'storefront-footer', 'setting_section_id' );

		/*
		 *
		 * add_settings_field(
		'title', 'Title', array($this, 'title_callback'), 'storefront-footer', 'setting_section_id'
		); */
	}

	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['footer_credit'] ) ) {
			$new_input['footer_credit'] = wp_kses_post( $input['footer_credit'] );
		}

		/*
		 *
		 * if (isset($input['title']))
		$new_input['title'] = sanitize_text_field($input['title']); */

		return $new_input;
	}

	public function print_section_info() {
		print 'Enter your settings below:';
	}

	function footer_credit_callback() {
		wp_editor(
			$this->options['footer_credit'],
			'footer_credit',
			array(
				'media_buttons' => true,
				'textarea_rows' => 5,
				'textarea_name' => 'storefront_footer[footer_credit]',
			)
		);
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
