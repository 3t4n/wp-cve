<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } 
global $foxtool_options;
# hook vao js css login admin
if ( isset($foxtool_options['custom-ad1'])){
function foxtool_custom_login_admin() { 
	global $foxtool_options; 
	ob_start(); ?>
	<style type="text/css">
		.login form input:focus, .login form button:focus, .login form select:focus, body a:focus, .login form a{
			border:none !important;
			box-shadow: none !important;
		}
		.login form input:hover, .login form button:hover, body a:hover, .login form a:hover{
			opacity:0.6;
		}
		#language-switcher {
			position: absolute;
			right: 0;
			left: 0;
			bottom: 0;
			padding:10px !important;
			background:none !important;
		}
		#login h1 a {
			<?php if(!empty($foxtool_options['custom-logo1'])){echo 'background-image: url('. $foxtool_options['custom-logo1'] .');';} ?>
			background-size: 180px;
			width: 280px;
			height:80px;
			background-size: contain;
			margin: 0 auto 10px;
			pointer-events: none;
		}
		.login form .input, .login input[type=password], .login input[type=text]{
			padding:7px 10px !important;
			font-size:15px !important;
		}
		label[for="user_login"], label[for="user_pass"], label[for="user_email"]{
			display:none !important;
		}
		.login label {
			margin-bottom: 10px !important;
		}
		.login form  input.button.button-primary.button-large{
			width:100%;
			padding:5px;
			margin-bottom:0px !important;
			margin-top:10px !important;
			font-weight: bold;
		}
		.login #backtoblog, .login #nav{
			text-align: center;
		}
		.login #login_error, .login .message{
			text-align: center;
		}
		.login .language-switcher select, .login .language-switcher .button{
			font-size:10px !important;
			min-height: 30px !important;
			opacity:0.5;
		}
		.login .language-switcher select{
			width:100px !important;
		}
		.login .language-switcher .button{
			display: inline-block !important;
			margin: auto !important;
		}
		label[for="language-switcher-locales"]{display:none !important}
		@media (max-width:700px){
		#language-switcher {position: relative;}	
		}
		<?php 
		if(isset($foxtool_options['custom-bg1']) && $foxtool_options['custom-bg1'] != 'None'){ ?>
		body {
			<?php
			if(isset($foxtool_options['custom-bg1']) && $foxtool_options['custom-bg1'] == 'Auto'){
				echo 'background-image: url(https://source.unsplash.com/1600x900/?landscape) !important;background-size:cover !important;';
			}
			else if(isset($foxtool_options['custom-bg1']) && $foxtool_options['custom-bg1'] == 'Color' && !empty($foxtool_options['custom-bg11'])){
				echo 'background:'. $foxtool_options['custom-bg11'] .'!important;';
			}
			else if(isset($foxtool_options['custom-bg1']) && $foxtool_options['custom-bg1'] == 'Upload' && !empty($foxtool_options['custom-bg12'])){
				echo 'background-image:url('. $foxtool_options['custom-bg12'] .') !important;background-size:cover !important;';
			}
			?>
		}
		@media (max-width:700px){
			body {
				background-attachment: fixed !important;
				margin: 0; 
				height: 100vh; 
			}	
		}
		<?php }
		if ( isset($foxtool_options['custom-main1'])){ ?>
		.login form, .login #login_error, .login .message{
			<?php
			if (!empty($foxtool_options['custom-main11'])){
			echo 'background:'. $foxtool_options['custom-main11'] .'!important;border:none !important';
			}?>
		}
		.login form input, .login select{
			<?php
			if (!empty($foxtool_options['custom-main12'])){
			echo 'background:'. $foxtool_options['custom-main12'] .'!important;border:none !important';
			}?>
		}
		.login form input, .login select, .wp-core-ui .button, .wp-core-ui .button-secondary{
			<?php
			if (!empty($foxtool_options['custom-main13'])){
			echo 'color:'. $foxtool_options['custom-main13'] .'!important;';
			}?>
		}
		.login form  input.button.button-primary.button-large, .login .language-switcher .button{
			<?php
			if (!empty($foxtool_options['custom-main14'])){
			echo 'background:'. $foxtool_options['custom-main14'] .'!important;border:none !important;';
			}?>
		}
		.login form  input.button.button-primary.button-large, .login .language-switcher .button{
			<?php
			if (!empty($foxtool_options['custom-main15'])){
			echo 'color:'. $foxtool_options['custom-main15'] .'!important;';
			}?>
		}
		#backtoblog{
			padding:0px !important;
		}
		#backtoblog a, a.button.button-large{
			display: block;
			padding: 10px !important;
			text-align: center;
			<?php
			if (!empty($foxtool_options['custom-main14'])){
			echo 'background:'. $foxtool_options['custom-main14'] .'!important;';
			}
			if (!empty($foxtool_options['custom-main15'])){
			echo 'color:'. $foxtool_options['custom-main15'] .'!important;';
			}
			if (!empty($foxtool_options['custom-main18'])){
			echo 'border-radius:'. $foxtool_options['custom-main18'] .'px !important;';
			} ?>
		}
		body{
			<?php
			if (!empty($foxtool_options['custom-main16'])){
			echo 'color:'. $foxtool_options['custom-main16'] .'!important;';
			}?>
		}
		body a{
			<?php
			if (!empty($foxtool_options['custom-main17'])){
			echo 'color:'. $foxtool_options['custom-main17'] .'!important;';
			}?>
		}
		.login #login_error, .login .message, .login form input, .login select, .login form  input.button.button-primary.button-large, .login .language-switcher .button{
			<?php
			if (!empty($foxtool_options['custom-main18'])){
			echo 'border-radius:'. $foxtool_options['custom-main18'] .'px !important;';
			}?>
		}
		.login form{
			<?php
			if (!empty($foxtool_options['custom-main18'])){
			echo 'border-radius:'. $foxtool_options['custom-main18'] .'px '. $foxtool_options['custom-main18'] .'px 0px 0px !important;';
			}?>
		}
		.login #nav {
			margin: -2px 0px 0px 0px !important;
			<?php
			if (!empty($foxtool_options['custom-main11'])){
			echo 'background:'. $foxtool_options['custom-main11'] .'!important;';
			}
			if (!empty($foxtool_options['custom-main18'])){
			echo 'border-radius:0px 0px '. $foxtool_options['custom-main18'] .'px '. $foxtool_options['custom-main18'] .'px !important;';
			}?>
			padding: 10px !important;
			border-top: 1px solid #0000002e;
		}
		#backtoblog{
			<?php
			if (isset($foxtool_options['custom-main19'])){
			echo 'display:none !important;';
			}?>
		}
		#language-switcher{
			<?php
			if (isset($foxtool_options['custom-main20'])){
			echo 'display:none !important;';
			}?>
		}
		<?php } ?>
	</style>
	<?php
	echo ob_get_clean();  
}
add_action('login_enqueue_scripts', 'foxtool_custom_login_admin');
# add placeholder vào user pass
function foxtool_custom_login_scripts(){
    echo '<script>
            document.addEventListener("DOMContentLoaded", function(event) { 
                if (document.getElementById("user_login")) {
					document.getElementById("user_login").placeholder = "'.__("Account", "foxtool").'";
				}
				if (document.getElementById("user_pass")) {
					document.getElementById("user_pass").placeholder = "'.__("Password", "foxtool").'";
				}
				if (document.getElementById("user_email")) {
					document.getElementById("user_email").placeholder = "'.__("Email", "foxtool").'";
				}
            })
        </script>';
}
add_action('login_head', 'foxtool_custom_login_scripts', 1);
}
# thay đổi chân trang admin wp
if ( isset($foxtool_options['custom-foo1'])){
function foxtool_custom_admin_footer() {
	global $foxtool_options;
	if (!empty($foxtool_options['custom-foo11'])){
		echo $foxtool_options['custom-foo11'];
	}
}
add_filter('admin_footer_text', 'foxtool_custom_admin_footer');
}

# tắt những widget mặc định ở bảng tin
function foxtool_remove_dashboard_widgets() {
	global $foxtool_options;
	if(isset($foxtool_options['custom-home1'])){
		remove_meta_box('dashboard_right_now','dashboard', 'normal'); // Widget thống kê
	}
	if(isset($foxtool_options['custom-home2'])){
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // Widget thông tin WordPress
	}
	if(isset($foxtool_options['custom-home3'])){
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Widget tin nhanh
	}
	if(isset($foxtool_options['custom-home4'])){
		remove_meta_box('dashboard_activity','dashboard', 'normal'); // Widget bài viết mới
	}
	if(isset($foxtool_options['custom-home5'])){
		remove_action('welcome_panel','wp_welcome_panel'); // Widget chào mừng
	}
	if(isset($foxtool_options['custom-home6'])){
		remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); // Widget sức khỏe
	}
}
add_action('wp_dashboard_setup', 'foxtool_remove_dashboard_widgets');
# widget tuy chinh
if (isset($foxtool_options['custom-wid1'])){
function foxtool_custom_welcome() {
	global $wp_meta_boxes, $foxtool_options;
	$widget_name = !empty($foxtool_options['custom-wid11']) ? $foxtool_options['custom-wid11'] : __('Welcome to Foxtool', 'foxtool');
	wp_add_dashboard_widget('custom_help_widget', $widget_name, 'foxtool_widtit');
	}
	function foxtool_widtit() {
	global $foxtool_options;	
	?>
	<h3><?php _e('Welcome to', 'foxtool'); ?> <?php echo bloginfo( 'name' ); ?></h3>
	<p><b><?php _e('WEBSITE INFORMATION', 'foxtool') ?></b></p>
	<?php
	if (!empty($foxtool_options['custom-wid12'])){
		echo $foxtool_options['custom-wid12'];
	}
}
add_action('wp_dashboard_setup', 'foxtool_custom_welcome');
}
# custom link đăng nhap wordpress
if (isset($foxtool_options['custom-chan1'])){
function foxtool_get_option($option_name){
	global $foxtool_options;
    if ($option_name === 'login_url' && !empty($foxtool_options['custom-chan11'])) {
        return $foxtool_options['custom-chan11'];
    }
    return false; 
}
class foxtool_Logins {
    public function __construct() {
        if (foxtool_get_option('login_url')) {
            add_action('login_init', [$this, 'login_head'], 1);
            add_action('login_form', [$this, 'login_form_field']);
            add_action('init', [$this, 'hide_login_init']);
            add_filter('lostpassword_url', [$this, 'login_lostpassword'], 10, 2);
            add_action('lostpassword_form', [$this, 'login_form_field']);
            add_filter('lostpassword_redirect', [$this, 'login_lostpassword_redirect'], 100, 1);
            add_filter('register_url', [$this, 'custom_register_url']);
        }
    }
    public function custom_register_url($url) {
        $geturl = foxtool_get_option('login_url');
        return site_url("wp-login.php?action=register&{$geturl}&redirect=false");
    }
    public function login_lostpassword_redirect($lostpassword_redirect)
    {
        return 'wp-login.php?checkemail=confirm&redirect=false&' . foxtool_get_option('login_url');
    }
    public function login_lostpassword()
    {
        $geturl = foxtool_get_option('login_url');
        return site_url("wp-login.php?action=lostpassword&{$geturl}&redirect=false");
    }
    private function get_login_geturl()
    {
        return foxtool_get_option('login_url');
    }
    public function hide_login_init()
    {
        $geturl = $this->get_login_geturl();
        if (basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']) == $geturl) {
            wp_safe_redirect(home_url("wp-login.php?{$geturl}&redirect=false"));
            exit();
        }
    }
    public function login_form_field()
    {
        ?>
        <input type="hidden" name="redirect_login" value="<?php echo esc_attr(foxtool_get_option('login_url')) ?>"/>
        <?php
    }
    public function login_head()
    {
        $geturl = $this->get_login_geturl();
        if (isset($_POST['redirect_login']) && $_POST['redirect_login'] == $geturl) {
            return false;
        }
        if (strpos($_SERVER['REQUEST_URI'], 'action=logout') !== false) {
            check_admin_referer('log-out');
            $user = wp_get_current_user();
            wp_logout();
            wp_safe_redirect(home_url(), 302);
            die;
        }
        if ((strpos($_SERVER['REQUEST_URI'], $geturl) === false) && (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], $geturl) === false) && (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false)) {
            wp_safe_redirect(home_url(), 302);
            die();
        }
    }
}
$foxtool_logins = new Foxtool_Logins();
}
# thay đoi logo wordpress admin bar
# ẩn logo
if (isset($foxtool_options['custom-logbar1'])){
function foxtool_logo_admin_bar_remove() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'foxtool_logo_admin_bar_remove', 0);
}
if (isset($foxtool_options['custom-logbar2'])){
// thay doi logo
function foxtool_change_admin_logo() {
	global $foxtool_options;
	if(!empty($foxtool_options['custom-logbar21'])){
	echo '<style type="text/css">
			#wpadminbar #wp-admin-bar-wp-logo>.ab-item {
				padding: 0 7px;
				background-image: url('. $foxtool_options['custom-logbar21'] .') !important;
				background-size: 70%;
				background-position: center;
				background-repeat: no-repeat;
				opacity: 0.8;
			}
			#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before {
				content: " ";
				top: 2px;
			}
	</style>'; 
	}
}
add_action('wp_before_admin_bar_render', 'foxtool_change_admin_logo');
}

