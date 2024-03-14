<?php
/**
 * Plugin Name: Yandex Share
 * Description: Add a Yandex Share block to your WordPress posts and pages.
 * Author: Konstantin Kovshenin
 * Version: 0.7.1
 * License: GPLv2
 * Text Domain: yandex-share
 * Domain Path: /languages
 */

class Yandex_Share_Plugin {
	public $services;
	public $types;

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	function init() {
		$this->options = array_merge( array(
			'services' => 'yaru,vkontakte,facebook,twitter',
			'type' => 'icon',
		), (array) get_option( 'yandex-share', array() ) );

		load_plugin_textdomain( 'yandex-share', false, basename( dirname( __FILE__ ) ) . '/languages' );

		$this->services = array(
			'yaru' => __( 'Ya.ru', 'yandex-share' ),
			'vkontakte' => __( 'Vkontakte', 'yandex-share' ),
			'facebook' => __( 'Facebook', 'yandex-share' ),
			'twitter' => __( 'Twitter', 'yandex-share' ),
			'odnoklassniki' => __( 'Odnoklassniki', 'yandex-share' ),
			'moimir' => __( 'Moi Mir', 'yandex-share' ),
			'lj' => __( 'Livejournal', 'yandex-share' ),
			'friendfeed' => __( 'Friendfeed', 'yandex-share' ),
			'moikrug' => __( 'Moi Krug', 'yandex-share' ),
			'gplus' => __( 'Google+', 'yandex-share' ),
		);

		$this->types = array(
			'button' => __( 'Button', 'yandex-share' ),
			'link' => __( 'Link', 'yandex-share' ),
			'icon' => __( 'Icons and menu', 'yandex-share' ),
			'none' => __( 'Icons only', 'yandex-share' ),

			'counter' => __( 'Counter', 'yandex-share' ),
		);

		if ( ! empty( $this->options['services'] ) ) {
			add_filter( 'the_content', array( $this, 'the_content' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	function enqueue_scripts() {
		wp_register_script( 'yandex-share', '//yandex.st/share/share.js', array(), false, true );
	}

	function the_content( $content ) {
		$post = get_post();

		$language = 'ru';
		$theme = ( 'counter' == $this->options['type'] ) ? 'counter' : 'default';
		$share = sprintf( '<div class="yandex-share yashare-auto-init" data-yashareL10n="%s" data-yashareTheme="%s" data-yashareType="%s" data-yashareQuickServices="%s" data-yashareTitle="%s" data-yashareLink="%s"></div>',
			esc_attr( $language ),
			esc_attr( $theme ),
			esc_attr( $this->options['type'] ),
			esc_attr( $this->options['services'] ),
			esc_attr( get_the_title() ),
			esc_url( get_permalink() )
		);

		static $enqueued = false;
		if ( ! $enqueued ) {
			wp_enqueue_script( 'yandex-share' );
			$enqueued = true;
		}

		return $content . "\n\n" . $share;
	}

	function admin_init() {
		register_setting( 'yandex-share', 'yandex-share', array( $this, 'sanitize' ) );
		add_settings_section( 'general', '', '', 'yandex-share' );
		add_settings_field( 'services', __( 'Services', 'yandex-share' ), array( $this, 'field_services' ), 'yandex-share', 'general' );
		add_settings_field( 'type', __( 'Type', 'yandex-share' ), array( $this, 'field_type' ), 'yandex-share', 'general' );
	}

	function sanitize( $input ) {
		$output = $this->options;

		if ( isset( $input['services-submit'] ) && empty( $input['services'] ) )
			$output['services'] = '';

		if ( isset( $input['services-submit'] ) && ! empty( $input['services'] ) ) {
			$services = array();
			foreach ( $this->services as $key => $value )
				if ( ! empty( $input['services'][ $key ] ) )
					$services[] = $key;

			$output['services'] = implode( ',', $services );
		}

		if ( isset( $input['type'] ) && array_key_exists( $input['type'], $this->types ) )
			$output['type'] = $input['type'];

		return $output;
	}

	function field_services() {
		$selected_services = explode( ',', $this->options['services'] );
		?>
		<input type="hidden" name="yandex-share[services-submit]" value="1" />
		<?php foreach ( $this->services as $key => $label ) : ?>
			<label><input
				type="checkbox"
				name="yandex-share[services][<?php echo esc_attr( $key ); ?>]"
				value="1"
				<?php checked( in_array( $key, $selected_services ) ); ?>
			/> <?php echo esc_html( $label ); ?></label><br />
		<?php endforeach; ?>
		<?php
	}

	function field_type() {
		?>
		<select name="yandex-share[type]">
		<?php foreach ( $this->types as $value => $label ) : ?>
			<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $this->options['type'] ); ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach; ?>
		</select>
		<?php
	}

	function admin_menu() {
		add_options_page( __( 'Yandex Share', 'yandex-share' ), __( 'Yandex Share', 'yandex-share' ), 'manage_options', 'yandex-share', array( $this, 'render_options' ) );
	}

	function render_options() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
	        <h2><?php _e( 'Yandex Share', 'yandex-share' ); ?></h2>
	        <p><?php _e( 'Yandex Share allows your visitors to share a link to your post or page in various social networks and blogs, which will increase your visibility and traffic. Please select the services you would like to support below.', 'yandex-share' ); ?>
	        <form action="options.php" method="POST">
	            <?php settings_fields( 'yandex-share' ); ?>
	            <?php do_settings_sections( 'yandex-share' ); ?>
	            <?php submit_button(); ?>
	        </form>
	    </div>
		<?php
	}
}
$GLOBALS['yandex_share_plugin'] = new Yandex_Share_Plugin;