<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since 1.0
 */

class PWD_LOGIN_Plugin {

	public function __construct() {
		add_action( 'customize_register' , array( __CLASS__, 'register' ) );
		add_action( 'login_enqueue_scripts' , array( __CLASS__, 'header_output' ) );

		add_filter( 'login_headerurl', array( __CLASS__, 'pwd_login_login_headerurl' ) );
		add_filter( 'login_headertitle', array( __CLASS__, 'pwd_login_login_headertitle' ) );
	}

	public static function pwd_login_login_headerurl() {
		return get_bloginfo( 'url' );
	}

	public static function pwd_login_login_headertitle() {
		$title = get_bloginfo( 'name', 'display' );
		return $title;
	}

	public static function register( $wp_customize ) {

		if ( method_exists( 'WP_Customize_Manager', 'add_panel' ) ) {
			$wp_customize->add_panel( 'panel_custom_login', array(
				'priority'   => 35,
				'title'      => __( 'Login page', 'pwd-wp-login' ),
				'capability' => 'edit_theme_options',
			) );
		}
		$wp_customize->add_section( 'custom_login_section', array(
			'title'         => __( 'Login page', 'pwd-wp-login' ),
			'priority'  => 35,
			'panel'     => 'panel_custom_login',
			) );

		/*Login page*/
		$wp_customize->add_setting( 'pwd_login' );
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'pwd_login',
			array(
				'label'    => __( 'Add a logo for the login page of your website', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login',
			)
		));

		$wp_customize->add_setting( 'pwd_login_height', array('default' => '84' ) );
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'pwd_login_height',
			array(
				'label'    => __( 'Adjust the height of the logo', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login_height',
			)
		));

		$wp_customize->add_setting( 'pwd_login_width', array( 'default' => '84' ) );
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'pwd_login_width',
			array(
				'label'    => __( 'Adjust the width of the logo', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login_width',
			)
		));

		$wp_customize->add_setting( 'pwd_login_padding', array( 'default' => '5' ) );
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'pwd_login_padding',
			array(
				'label'    => __( 'Adjust the bottom margin of the logo', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login_padding',
			)
		));

		$wp_customize->add_setting( 'pwd_login_background', array( 'default' => '#f1f1f1' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'pwd_login_background',
			array(
				'label'    => __( 'Adjust the background color of the login page', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login_background',
			)
		));

		$wp_customize->add_setting( 'pwd_login_color', array( 'default' => '#999' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'pwd_login_color',
			array(
				'label'    => __( 'Adjust the color of the login page', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login_color',
			)
		));

		$wp_customize->add_setting( 'pwd_login_police', array( 'default' => '"Open Sans",sans-serif' ) );
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'pwd_login_police',
			array(
				'label'    => __( 'Adjust font login page', 'pwd-wp-login' ),
				'section'  => 'custom_login_section',
				'settings' => 'pwd_login_police',
				'type'     => 'select',
				'choices'  => array(
					'"Open Sans",sans-serif'								=> __( '"Open Sans",sans-serif' ),
					'"PT Sans",Arial' 										=> __( '"PT Sans",Arial' ),
					'Arial, Helvetica, sans-serif' 							=> __( 'Arial, Helvetica, sans-serif' ),
					'"Arial Black", Gadget, sans-serif'  					=> __( '"Arial Black", Gadget, sans-serif' ),
					'"Bookman Old Style", serif'  							=> __( '"Bookman Old Style", serif' ),
					'"Comic Sans MS", cursive'  							=> __( '"Comic Sans MS", cursive' ),
					'Courier, monospace'  									=> __( 'Courier, monospace' ),
					'Garamond, serif'  										=> __( 'Garamond, serif' ),
					'Georgia, serif'  										=> __( 'Georgia, serif' ),
					'Impact, Charcoal, sans-serif'  						=> __( 'Impact, Charcoal, sans-serif' ),
					'"Lucida Console", Monaco, monospace'  					=> __( '"Lucida Console", Monaco, monospace' ),
					'"Lucida Sans Unicode", "Lucida Grande", sans-serif'  	=> __( '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ),
					'"MS Sans Serif", Geneva, sans-serif'  					=> __( '"MS Sans Serif", Geneva, sans-serif' ),
					'"MS Serif", "New York", sans-serif'  					=> __( '"MS Serif", "New York", sans-serif' ),
					'"Palatino Linotype", "Book Antiqua", Palatino, serif'  => __( '"Palatino Linotype", "Book Antiqua", Palatino, serif' ),
					'Tahoma, Geneva, sans-serif'  							=> __( 'Tahoma, Geneva, sans-serif' ),
					'"Times New Roman", Times, serif'  						=> __( '"Times New Roman", Times, serif' ),
					'"Trebuchet MS", Helvetica, sans-serif'  				=> __( '"Trebuchet MS", Helvetica, sans-serif' ),
					'Verdana, Geneva, sans-serif'  							=> __( 'Verdana, Geneva, sans-serif' ),
				)
			)
		));

	}

	public static function header_output() {
		?>
		<style type="text/css">
			<?php self::generate_css( 'body.login div#login h1 a', 'background-image', 'pwd_login', 'url(', ')' ); ?>
			<?php self::generate_css( 'body.login div#login h1 a', 'height', 'pwd_login_height', '', 'px' ); ?>
			<?php self::generate_css( 'body.login div#login h1 a', 'width', 'pwd_login_width', '', 'px' ); ?>
			<?php self::generate_css( 'body.login div#login h1 a', 'padding-bottom', 'pwd_login_padding', '', 'px' ); ?>
			<?php self::generate_css( 'body.login div#login h1 a', 'padding-bottom', 'pwd_login_padding', '', 'px' ); ?>
			<?php self::generate_css( 'html, body.login', 'background', 'pwd_login_background' ); ?>
			<?php self::generate_css( '.login #backtoblog a, .login #nav a', 'color', 'pwd_login_color' ); ?>
			<?php self::generate_css( 'html, body', 'font-family', 'pwd_login_police' ); ?>

			<?php
			$logo_width = get_theme_mod( 'pwd_login_width' );
			$logo_height = get_theme_mod( 'pwd_login_height' );
			?>
			body.login div#login h1 a {
				background-size: <?php echo $logo_width.'px'; ?> <?php echo $logo_height.'px'; ?> !important;
			}
		</style>
		<?php
	}

	public static function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true ) {
		$return = '';
		$mod = get_theme_mod( $mod_name );
		if ( ! empty( $mod ) ) {
			$return = sprintf('%s { %s:%s; }',
				$selector,
				$style,
				$prefix.$mod.$postfix
			);
			if ( $echo ) {
				echo $return;
			}
		}
		return $return;
	}

}