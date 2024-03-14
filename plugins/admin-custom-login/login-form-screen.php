<?php
error_reporting(0);
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

function acl_er_login_logo() {
	// Get all options from db
	$er_options          = get_option( 'plugin_erident_settings' );
	$top_page            = unserialize( get_option( 'Admin_custome_login_top' ) );
	$login_page          = unserialize( get_option( 'Admin_custome_login_login' ) );
	$text_and_color_page = unserialize( get_option( 'Admin_custome_login_text' ) );
	$logo_page           = unserialize( get_option( 'Admin_custome_login_logo' ) );
	$Social_page         = unserialize( get_option( 'Admin_custome_login_Social' ) );

	if ( isset( $login_page['login_custom_css'] ) ) {
		$login_custom_css = $login_page['login_custom_css'];
	} else {
		$login_custom_css = '';
	}
	if ( isset( $login_page['login_form_float'] ) ) {
		$login_form_float = $login_page['login_form_float'];
	} else {
		$login_form_float = 'center';
	}
	if ( isset( $login_page['login_form_position'] ) ) {
		$login_form_position = $login_page['login_form_position'];
	} else {
		$login_form_position = 'default';
	}

	if ( $top_page['top_bg_type'] == 'slider-background' ) {
		if ( $top_page['top_bg_slider_animation'] == 'slider-style1' ) {
			require_once 'css/slider-style1.php';
		} elseif ( $top_page['top_bg_slider_animation'] == 'slider-style2' ) {
			require_once 'css/slider-style2.php';
		} elseif ( $top_page['top_bg_slider_animation'] == 'slider-style3' ) {
			require_once 'css/slider-style3.php';
		} elseif ( $top_page['top_bg_slider_animation'] == 'slider-style4' ) {
			require_once 'css/slider-style4.php';
		}
	}

	if ( $text_and_color_page['enable_link_shadow'] == 'yes' ) {
		$link_shadow_color = $text_and_color_page['link_shadow_color'] . ' 0 1px 0';
	} else {
		$link_shadow_color = 'none';
	}
	if ( $login_page['login_enable_shadow'] == 'yes' ) {
		$login_shadow_color = '0 4px 10px -1px ' . $login_page['login_shadow_color'];
	} else {
		$login_shadow_color = 'none';
	}

	if ( isset( $login_page['login_enable_language_switcher'] ) && $login_page['login_enable_language_switcher'] == 'no' ) {
		$login_enable_language_switcher = 'none';
	} else {
		$login_enable_language_switcher = 'block';
	}

	// Check if opacity field is empty
	if ( $login_page['login_form_opacity'] == '10' ) {
		$login_form_opacity = '1';
	} else {
		$login_form_opacity = '0.' . $login_page['login_form_opacity'];
	}

	function weblizar_hex2rgb( $colour ) {
		if ( $colour[0] == '#' ) {
			$colour = substr( $colour, 1 );
		}
		if ( strlen( $colour ) == 6 ) {
			list($r, $g, $b) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
		} elseif ( strlen( $colour ) == 3 ) {
			list($r, $g, $b) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
		} else {
			return false;
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );
		return array(
			'red'   => $r,
			'green' => $g,
			'blue'  => $b,
		);
	}
	$btnrgba = weblizar_hex2rgb( $text_and_color_page['button_color'] );
	$loginbg = weblizar_hex2rgb( $login_page['login_bg_color'] );

	// require social icon css
	require_once 'css/socialcss.php';
	add_action( 'admin_print_scripts', 'acl_admin_font' );
	function acl_admin_font() {
		 wp_enqueue_script( 'wl-acl-font', WEBLIZAR_NALF_PLUGIN_URL . 'js/webfonts.js', array( 'jquery' ), true, false );
	}
	?>
	<?php
	$js = ' ';
	ob_start();
	?>
	WebFont.load({
	google: {
	families: ['<?php echo esc_attr( $text_and_color_page['heading_font_style'] ); ?>'] // saved value
	}
	});
	WebFont.load({
	google: {
	families: ["<?php echo esc_attr( $text_and_color_page['input_font_style'] ); ?>"] // saved value
	}
	});
	WebFont.load({
	google: {
	families: ["<?php echo esc_attr( $text_and_color_page['link_font_style'] ); ?>"] // saved value
	}
	});
	WebFont.load({
	google: {
	families: ["<?php echo esc_attr( $text_and_color_page['button_font_style'] ); ?>"] // saved value
	}
	});
	<?php $js .= ob_get_clean(); ?>
	<?php
	wp_register_script( 'wl-acl-font-config', 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js', array(), false, true );
	wp_enqueue_script( 'wl-acl-font-config' );
	wp_add_inline_script( 'wl-acl-font-config', $js );
	?>

	<?php
	/* Logo URL */
	function my_login_logo_url() {
		if ( get_option( 'Admin_custome_login_logo' ) ) {
			$logo_page = unserialize( get_option( 'Admin_custome_login_logo' ) );
			return esc_url( $logo_page['logo_url'] );
			// make get option varibles and use
		} else {
			return home_url();
			/*create default variables and use*/
		}
	}
	add_filter( 'login_headerurl', 'my_login_logo_url' );
	/* Logo URL Title*/
	function my_login_logo_url_title() {
		if ( get_option( 'Admin_custome_login_logo' ) ) {
			$logo_page = unserialize( get_option( 'Admin_custome_login_logo' ) );
			return $logo_page['logo_url_title'];
			// make get option varibles and use
		} else {
			return esc_html_e( 'Your Site Name and Info', WEBLIZAR_ACL );
			// create default variables and use
		}
	}
	add_filter( 'login_headertext', 'my_login_logo_url_title' );

	// Inline CSS For Login
	require 'includes/login-inline-css.php';
	/** Message Above Login Form ***/
	function acl_login_message( $message ) {
		$login_page = unserialize( get_option( 'Admin_custome_login_login' ) );
		if ( ! empty( $login_page['log_form_above_msg'] ) ) {
			$log_form_above_msg = $login_page['log_form_above_msg'];
			return "<p class='login-msg-above'>" . html_entity_decode( stripslashes( $log_form_above_msg ) ) . '</p>';
		} else {
			return $message;
		}
	}
	add_filter( 'login_message', 'acl_login_message' );

	if ( $_SERVER['QUERY_STRING'] == 'action=lostpassword' ) {
		add_filter( 'login_message', 'acl_reset_password_message' );
		function acl_reset_password_message( $message ) {
			$login_page = unserialize( get_option( 'Admin_custome_login_login' ) );
			if ( ! empty( $login_page['login_pass_reset_msg'] ) ) {
				$login_pass_reset_msg = $login_page['login_pass_reset_msg'];
			}
			if ( isset( $login_pass_reset_msg ) ) {
				$message = '<p class="message reset-pass">'
				. __( $login_pass_reset_msg )
				. '</p>';
			}
			return $message;
		}
	}
}
$dashboard_page   = unserialize( get_option( 'Admin_custome_login_dashboard' ) );
$dashboard_status = isset($dashboard_page['dashboard_status']) ? $dashboard_page['dashboard_status'] : 'disable';
if ( $dashboard_status == 'enable' ) {
	add_action( 'login_enqueue_scripts', 'acl_er_login_logo' );
}
?>