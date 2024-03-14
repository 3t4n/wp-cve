<?php
/*
Plugin Name: Genesis Simple Headers
Plugin URI: http://wpaffiliatemanager.com/
Description: Use the WordPress header functionality to upload custom logos or headers
Version: 2.4
Author: wp.insider, vegasgeek
Author URI: http://wpaffiliatemanager.com/
*/

// Define our constants
define('SIMPLEHEADERS_PLUGIN_DIR', dirname(__FILE__));

// Grab other files
require_once ( SIMPLEHEADERS_PLUGIN_DIR.'/functions.php' );

// Grab theme info
if(function_exists('wp_get_theme')) {
	$gsh_theme_info = wp_get_theme();
} else {
	$gsh_theme_info = get_theme_data( get_stylesheet_uri() );
}

// build array of 2.0 themes that require a centered banner
$gsh_centered = array( 'Education Child Theme', 'Mocha Child Theme' );

// Set theme name
$gsh_theme_name = $gsh_theme_info['Name'];

// Set theme version
$gsh_theme_version = $gsh_theme_info['Version'];

function simpleheaders_get_theme_customization( $gsh_theme_name = 'Genesis' ) {
	global $gsh_theme_version;
	// Set details for all themes
	
	if ($gsh_theme_version < 2 ) {
		$gsh_all_theme_details = array(
			'Genesis' => array(	
				'HEADER_IMAGE_WIDTH'	=> 300,
				'HEADER_IMAGE_HEIGHT'	=> 80,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '',
			),
			'Agency Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 400,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'AgentPress Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 570,
				'HEADER_IMAGE_HEIGHT'	=> 115,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Amped Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 440,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Associate Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 300,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Backcountry Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 350,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Balance Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 350,
				'HEADER_IMAGE_HEIGHT'	=> 135,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Bee Crafty Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 180,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Blissful Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Corporate Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Corporate Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Crystal Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '.header-image #header #title-area',
			),
			'Delicious Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '.header-image #header #title-area',
			),
			'Education Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 395,
				'HEADER_IMAGE_HEIGHT'	=> 110,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'eleven40 Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 265,
				'HEADER_IMAGE_HEIGHT'	=> 78,
				'CSS_STANDARD_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
			),
			'Enterprise Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Executive Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 400,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_EXTRA'	=> '#header .wrap { height: 120px; }',
			),
			'Expose Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 460,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Fabric Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Focus Child Theme ' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Freelance Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 320,
				'HEADER_IMAGE_HEIGHT'	=> 110,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Generate Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 450,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_EXTRA'	=> '#header .wrap { height: 120px; }',
			),
			'Going Green Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 435,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Landscape Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 400,
				'HEADER_IMAGE_HEIGHT'	=> 80,
				'CSS_STANDARD_LOC'		=> '.header-image #title-area, .header-image #title-area #title, .header-image #title-area #title a',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #title-area, .header-image #title-area #title, .header-image #title-area #title a',
			),
			'Lexicon Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 300,
				'HEADER_IMAGE_HEIGHT'	=> 111,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Lifestyle Child Theme ' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Magazine Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 420,
				'HEADER_IMAGE_HEIGHT'	=> 90,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Manhattan Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 235,
				'HEADER_IMAGE_HEIGHT'	=> 70,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_EXTRA'	=> '#header .wrap { height: 120px; }',
			),
			'Metric Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 340,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Midnight Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 140,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),  
			'Minimum Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Mocha Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 450,
				'HEADER_IMAGE_HEIGHT'	=> 125,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'News Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 420,
				'HEADER_IMAGE_HEIGHT'	=> 80,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Nitrous Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Outreach Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header .wrap',
				'CSS_STANDARD_HIDE'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '.header-image #header #title-area',
			),
			'Pixel Happy Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 465,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header .wrap #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap #title-area',
			),
			'Platinum Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 435,
				'HEADER_IMAGE_HEIGHT'	=> 80,
				'CSS_STANDARD_LOC'		=> '#header .wrap #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '#header .wrap #title-area',
			),
			'Pretty Young Thing Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 240,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Prose' => array(
				'HEADER_IMAGE_WIDTH'	=> 940,
				'HEADER_IMAGE_HEIGHT'	=> 150,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Scribble Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
			),
			'Serenity Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 435,
				'HEADER_IMAGE_HEIGHT'	=> 80,
				'CSS_STANDARD_LOC'		=> '#header .wrap #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '#header .wrap #title-area',
			),
			'Sleek Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 400,
				'HEADER_IMAGE_HEIGHT'	=> 70,
				'CSS_STANDARD_LOC'		=> '#header .wrap #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '#header .wrap #title-area',
			),
			'Social Eyes Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 150,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Streamline Child Theme ' => array(
				'HEADER_IMAGE_WIDTH'	=> 310,
				'HEADER_IMAGE_HEIGHT'	=> 80,
				'CSS_STANDARD_LOC'		=> '#header .wrap #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
				'CSS_ADVANCED_HIDE'		=> '#header .wrap #title-area',
			),
			'Tapestry Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Venture Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 400,
				'HEADER_IMAGE_HEIGHT'	=> 102,
				'CSS_STANDARD_LOC'		=> '#header .wrap #title-area',
				'CSS_ADVANCED_LOC'		=> '#header .wrap',
				'CSS_ADVANCED_HIDE'		=> '#header .wrap #title-area',
			),
		);
	} else {
		// version 2.0 or higher
		$gsh_all_theme_details = array(
			'Agency Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 115,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'AgentPress Two' => array(
				'HEADER_IMAGE_WIDTH'	=> 400,
				'HEADER_IMAGE_HEIGHT'	=> 130,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Corporate Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 130,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Education Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 1140,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Executive Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 1140,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Focus Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 1060,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Going Green Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 1100,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Lifestyle Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 920,
				'HEADER_IMAGE_HEIGHT'	=> 150,
				'CSS_STANDARD_LOC'		=> '.header-image #header #title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Magazine Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 490,
				'HEADER_IMAGE_HEIGHT'	=> 90,
				'CSS_STANDARD_LOC'		=> '#title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Mocha Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 500,
				'HEADER_IMAGE_HEIGHT'	=> 50,
				'CSS_STANDARD_LOC'		=> '#title-area',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Outreach Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 1060,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header .wrap #title-area',
			),
			'Minimum Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 1140,
				'HEADER_IMAGE_HEIGHT'	=> 100,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'News Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 110,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
			'Streamline Child Theme' => array(
				'HEADER_IMAGE_WIDTH'	=> 960,
				'HEADER_IMAGE_HEIGHT'	=> 120,
				'CSS_STANDARD_LOC'		=> '#header',
				'CSS_ADVANCED_LOC'		=> '#header',
			),
		);
	}
	
	// apply filters
	$gsh_clean_theme_info = apply_filters( 'simpleheaders_theme_details', $gsh_all_theme_details );

	if ( !isset( $gsh_clean_theme_info[$gsh_theme_name] ) )
		return array();

	$gsh_theme_details = $gsh_clean_theme_info[$gsh_theme_name];

	return $gsh_theme_details;
}

// make sure the site is using a defined theme
$gsh_theme_details = simpleheaders_get_theme_customization( $gsh_theme_name );

if ( isset( $gsh_theme_details['CSS_STANDARD_LOC'] ) ) {

	// define advanced headers
	if( esc_attr( get_theme_mod( 'gsh_type' ) ) == 'advanced' ) {
		define( 'HEADER_IMAGE_WIDTH', esc_attr( get_theme_mod( 'gsh_adv_header_width', '300' ) ) );
		define( 'HEADER_IMAGE_HEIGHT', esc_attr( get_theme_mod( 'gsh_adv_header_height', '100' ) ) );
	}

	// define all our variables
	foreach( $gsh_theme_details as $key => $value ) {
		if( !defined( $key ) ) {
			define( $key, $value );
		}
	}
	
	// turn on the headers
	add_theme_support( 'custom-header', array( 'textcolor' => 'ffffff', 'admin_header_callback' => 'gsh_admin_style'  ) );
	
	if ( get_theme_mod( 'gsh_type' ) == 'advanced' ) {
		add_action( 'wp_head', 'gsh_advanced_style', 10000 );
	} else {
		add_action( 'wp_head', 'gsh_standard_style', 10000 );
	}
}

// echo out advanced style
function gsh_advanced_style() {
	global $gsh_centered, $gsh_theme_name;
	
	// if this theme isn't in the centered array, display left
	if (!in_array ( $gsh_theme_name, $gsh_centered ) ) {
		$isleft = 'left';
	}

	if ( get_header_image() ) {
		?>
		<style type="text/css">
			<?php echo CSS_ADVANCED_LOC; ?> {
			background: url(<?php header_image(); ?>) <?php echo $isleft; ?> top no-repeat!important;
			}
		<?php
		if( defined( 'CSS_ADVANCED_HIDE' ) ) {
			?>
			<?php echo CSS_ADVANCED_HIDE; ?> {
				background: none;
			}
			<?php
		}
		if( defined( 'CSS_ADVANCED_EXTRA' ) ) {
			echo CSS_ADVANCED_EXTRA;
		}
		?>
		</style>
	<?php
	}
}

// echo out standard style
function gsh_standard_style() {
	global $gsh_centered, $gsh_theme_name;
	
// wp_die( $gsh_theme_name);
	if ( get_header_image() ) {
		// if this theme isn't in the centered array, display left
		if (!in_array ( $gsh_theme_name, $gsh_centered ) ) {
			$isleft = 'left';
		}
		
		?>
		<style type="text/css">
			<?php echo CSS_STANDARD_LOC; ?> {
				background: url(<?php header_image(); ?>) <?php echo $isleft; ?> top no-repeat!important;
			}
			<?php
			if( defined( 'CSS_STANDARD_HIDE' ) ) {
				?>
				<?php echo CSS_STANDARD_HIDE; ?> {
					background: none!important;
				}
				<?php
			}
			?>
		</style>
	<?php
	}
}

// gets included in the admin header
function gsh_admin_style() {
	echo '<style type="text/css">';
	echo '#headimg {';
		echo 'width: '. HEADER_IMAGE_WIDTH .'px;';
		echo 'height: '. HEADER_IMAGE_HEIGHT .'px;';
	echo '}';
	echo '</style>';
}
