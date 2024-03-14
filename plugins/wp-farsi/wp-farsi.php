<?php
/**
Plugin Name: WP-Farsi
Plugin URI: http://wordpress.org/extend/plugins/wp-farsi
Description: مبدل تاریخ میلادی وردپرس به خورشیدی، فارسی ساز، مبدل اعداد انگلیسی به فارسی، رفع مشکل هاست با زبان و تاریخ، سازگار با افزونه‌های مشابه.
Author: Ali.Dbg 😉
Author URI: https://github.com/alidbg/wp-farsi
Version: 4.2.2
License: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)
*/

defined('ABSPATH')||die;
defined('WPLANG')||define('WPLANG', 'fa_IR');
define('WPFA_NUMS', get_option('wpfa_nums'));
define('WPFA_FILE', __FILE__);

require_once dirname( WPFA_FILE ) . '/includes/pdate.php';

function wpfa_activate() {
    update_option('WPLANG', 'fa_IR');
    update_option('start_of_week', '6');
    update_option('timezone_string', 'Asia/Tehran');
    if (WPFA_NUMS === false) add_option('wpfa_nums', 'on');
    $inc = ABSPATH . 'wp-admin/includes/translation-install.php';
    if (file_exists($inc)) {
        require_once($inc);
		if(function_exists('wp_download_language_pack'))
			wp_download_language_pack('fa_IR'); 
        update_option('WPLANG', 'fa_IR');
    }
	wpfa_patch_func();
}

function wpfa_file_put_contents($file = '', $str = '') {
	$r=0;$m=300; 
	if (($fp = fopen($file, "w")) === FALSE) return false; 
	do { 
		if ($r > 0) usleep(mt_rand(1, 50000)); 
		$r += 1; 
    } while (!flock($fp, LOCK_EX | LOCK_NB, $e) || $e); 
    if ($r == $m) return false; 
	$fw = fwrite($fp, $str);
	usleep(1000);
	flock($fp, LOCK_UN);
	fclose($fp);
	if ($fw === FALSE) 
		return false;
	else
		return true;
}

function wpfa_file_get_contents($file = '') {
	$c='';$r=0;$m=300; 
	if (($fp = fopen($file, "r")) === FALSE) return false;  
	do { 
		if ($r > 0) usleep(mt_rand(1, 50000)); 
		$r += 1; 
    } while (!flock($fp, LOCK_EX | LOCK_NB, $e) || $e); 
    if ($r == $m) return false; 
	$c = fread($fp, filesize($file));
	usleep(1000);
	flock($fp, LOCK_UN);
	fclose($fp);
	return $c;
}

function wpfa_patch_func() {
    $file = ABSPATH . 'wp-includes/functions.php';
    if (is_writable($file)) {
        $src = wpfa_file_get_contents($file);
        if ($src and preg_match_all('/else\s+return\s+(date.*)[(]/', $src, $match) === 1) {
			if (trim($match[1][0]) === 'date') {
				wpfa_file_put_contents($file, str_replace($match[0][0], "else\n\t\treturn date_i18n(", $src)); 
			}
        }
    } else {
    	@chmod($file, 0666); // change file mode
    }
}

function wpfa_unpatch_func() {
    $file = ABSPATH . 'wp-includes/functions.php';
    if (is_writable($file)) {
        $src = wpfa_file_get_contents($file);
        if ($src and preg_match_all('/else\s+return\s+(date_i18n.*)[(]/', $src, $match) === 1) {
			
			if (trim($match[1][0]) === 'date_i18n') {
				wpfa_file_put_contents($file, str_replace($match[0][0], "else\n\t\treturn date(", $src)); 
			}
        }
    }
}

function numbers_fa( $string ) {
    static $en_nums = array('0','1','2','3','4','5','6','7','8','9');
    static $fa_nums = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
    return str_replace($en_nums, $fa_nums, $string);
}

function numbers_fa2en( $string ) {
    static $en_nums = array('0','1','2','3','4','5','6','7','8','9');
    static $fa_nums = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
    return str_replace($fa_nums, $en_nums, $string);
}

function iss_uri($s = '') {
    if (isset($_SERVER['REQUEST_URI']) and trim($s) !== '') {
        $r = trim($_SERVER['REQUEST_URI']);
        if ($r !== '' or $r !== '/')
            if (strpos($r, $s) !== false)
                return true;
    }
    return false;
}

function exception_date() {
    if (iss_uri('/feed') or iss_uri('feed=') or defined('WXR_VERSION') or class_exists('wp_xmlrpc_server') or isset($valid_date))
        return true;
    return false;
}

function wpfa_date_i18n($g, $f, $t) {
    if (exception_date())
        return date($f, $t);
    else
        $d = wpfa_date($f, intval($t));
    return WPFA_NUMS === "on" ? numbers_fa($d) : $d;
}

function wpfa_apply_filters() {
    @ini_set('default_charset', 'UTF-8');
    @ini_set('date.timezone', 'UTC');
	@setlocale(LC_ALL, 'Persian_Iran.1256', 'fa_IR.utf8', 'fa_IR');
    if (@extension_loaded('mbstring')) {
        @mb_internal_encoding('UTF-8');
        @mb_language('neutral');
        @mb_http_output('UTF-8');
    }
    foreach (array(
        'date_i18n', 'get_post_time', 'get_comment_date', 'get_comment_time', 'get_the_date', 'the_date', 'get_the_time', 'the_time',
        'get_the_modified_date', 'the_modified_date', 'get_the_modified_time', 'the_modified_time', 'get_post_modified_time', 'number_format_i18n'
    ) as $i) remove_all_filters($i);
    add_filter('date_i18n', 'wpfa_date_i18n', 10, 3);
    if (WPFA_NUMS === "on")
        add_filter('number_format_i18n', 'numbers_fa');
}

function post_jalali2gregorian(){
    if (isset($_POST['aa'], $_POST['mm'], $_POST['jj']))
        if(substr(intval($_POST['aa']),0,1) == '1')
            list($_POST['aa'], $_POST['mm'], $_POST['jj']) = jalali2gregorian(zeroise(intval($_POST['aa']), 4), zeroise(intval($_POST['mm']), 2), zeroise(intval($_POST['jj']), 2));
}

function wpfa_init() {
    global $wp_locale;
    $wp_locale->number_format['thousands_sep'] = ",";
    $wp_locale->number_format['decimal_point'] = ".";
    if (trim(numbers_fa2en(mysql2date("Y", "2015-07-23 06:12:45", false))) == "2015") {
		wpfa_patch_func();
    }
    post_jalali2gregorian();
}

function wpfa_admin(){
    require_once dirname( WPFA_FILE ) . "/includes/wpfa_admin.php";
    wpfa_nums_field();
    wpfa_load_first();
}

function wpfa_remove_styles( $styles ) {
    //Author: https://git.io/v2fH5
    $styles->add( 'open-sans'           , '' ); // Backend
    $styles->add( 'googleFontsOpenSans' , '' ); // Backend
    $styles->add( 'raleway-font'		, '' ); // Backend
    $styles->add( 'twentytwelve-fonts'  , '' ); // Core themes ...
    $styles->add( 'twentythirteen-fonts', '' );
    $styles->add( 'twentyfourteen-lato' , '' );
    $styles->add( 'twentyfifteen-fonts' , '' );
    $styles->add( 'twentysixteen-fonts' , '' );
    $styles->add( 'gravity-forms-admin-yekan', '');
    $styles->add( 'ztjalali_reg_admin_style', '');
    $styles->add( 'ztjalali_reg_custom_admin_style', '');
    $styles->add( 'ztjalali_reg_theme_editor_style', '');
    if ( is_admin() ) {
        // Remove Google fonts injected into WP editor
        global $editor_styles;
        if ( function_exists( 'twentyfifteen_fonts_url' ) ) {
            unset( $editor_styles[ array_search( twentyfifteen_fonts_url(), $editor_styles ) ] );
        }
        if ( function_exists( 'twentysixteen_fonts_url' ) ) {
            unset( $editor_styles[ array_search( twentysixteen_fonts_url(), $editor_styles ) ] );
        }
    }
}

wpfa_apply_filters();
add_action('init', 'wpfa_init');
add_action('admin_init', 'wpfa_admin');
add_action('wp_default_styles', 'wpfa_remove_styles', 5 );
add_action('wp_loaded', 'wpfa_apply_filters', 900);
register_activation_hook( WPFA_FILE , 'wpfa_activate');
register_deactivation_hook( WPFA_FILE , 'wpfa_unpatch_func');
//End.