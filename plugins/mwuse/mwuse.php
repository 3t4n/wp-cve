<?php
/*
Plugin Name: MWuse
Description: Design your WordPress theme easily in Adobe Muse. Get the best of the both.
Author: MWuse
Author URI: http://mwuse.com
Text Domain: mwuse
Domain Path: /languages
Version: 1.2.17006
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function muse_to_wordpress_set_textdomain() {
    load_plugin_textdomain( 'mwuse', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'muse_to_wordpress_set_textdomain' );

if (version_compare(PHP_VERSION, '5.5.9', '>=')) 
{
	libxml_use_internal_errors( true );
	libxml_clear_errors();

	global $is_mtw_plugin;
	global $mtw_version;
	$is_mtw_plugin = true;


	define("TTR_MW_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );
	define("TTR_MW_PLUGIN_URL", plugin_dir_url( __FILE__ ) );
	$mtw_version = '1.2.17006';
	

	define("TTR_MW_TEMPLATES_PATH", ABSPATH . 'mtw-themes/' );
	define("TTR_MW_TEMPLATES_URL", network_site_url() . '/mtw-themes/' );

	define('FS_METHOD', 'direct');

	global $museUrl;
	global $html;
	global $tempDOMDocument;
	global $folderName;
	global $projectName;
	global $deviceType;
	global $mtwQuery;
	global $load_header;
	global $load_footer;
	global $load_header_mtw;
	global $load_footer_mtw;
	global $do_shortcode;
	global $mtw_page;
	
	global $mtw_server;
	global $mtw_ssl;
	
	if( !( $mtw_contact_server = get_transient( 'mtw_contact_server' ) ) )
	{
		$mtw_contact_server = wp_remote_get( "https://mwuse.com/?try-to-connect=1" );
		set_transient( 'mtw_contact_server', $mtw_contact_server );
	}

	if( !is_wp_error( $mtw_contact_server ) )
	{
		$mtw_server = "https://mwuse.com/";
		$mtw_ssl = 1;
	}
	else
	{
		$mtw_server = "http://mwuse.com/";
		$mtw_ssl = 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	if( !class_exists( 'Mobile_Detect' ) )
	{
		require_once TTR_MW_PLUGIN_DIR . "inc/Mobile-Detect-2.8.15/Mobile_Detect.php";
	}


	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');



	foreach ( glob( TTR_MW_PLUGIN_DIR . "functions/*.php" ) as $file ) { 
		if( substr( basename($file) , 0, 1 ) != "#" )
		{
			include_once $file; 
		}
	}
	foreach ( glob( TTR_MW_PLUGIN_DIR . "class/*.php" ) as $file ) { 
		if( substr( basename($file) , 0, 1 ) != "#" )
		{
			include_once $file; 
		}
	}
	foreach ( glob( TTR_MW_PLUGIN_DIR . "settings/*.php" ) as $file ) { 
		if( substr( basename($file) , 0, 1 ) != "#" )
		{
			include_once $file; 
		}
	}
	foreach ( glob( TTR_MW_PLUGIN_DIR . "extend/*.php" ) as $file ) { 
		if( substr( basename($file) , 0, 1 ) != "#" )
		{
			include_once $file; 
		}
	}



	$html = new DOMDocument;
	$tempDOMDocument = new DOMDocument;
	$detect = new Mobile_Detect;
	$do_shortcode = true;
	$load_header = true;
	$load_footer = true;
	$load_header_mtw = true;
	$load_footer_mtw = true;


	/* Actions */
	add_action( 'admin_enqueue_scripts', 'TTR_MW_load_admin_head' );
	add_action( 'admin_menu', 'mtw_register_page_linker' );


	/* Filters */
	add_filter( 'template_include', 'ttr_template_filter', 99 );
	add_filter( 'mtw_query_filter', 'mtw_category__in_by_slug', 10, 3 );



	function mtw_enqueue_front_style()
	{
		global $museUrl;
		if ( $museUrl ) 
		{
			wp_enqueue_style( "mtw-front-style", TTR_MW_PLUGIN_URL . 'front-style.css' );
			wp_enqueue_script('jquery');
		}
	}
	add_action( 'wp_enqueue_scripts' , 'mtw_enqueue_front_style' );

	function mtw_enqueue_admin_style()
	{
		wp_enqueue_style( "mtw-admin-style", TTR_MW_PLUGIN_URL . 'admin.css' );
	}
	add_action( 'admin_enqueue_scripts', 'mtw_enqueue_admin_style' );
	


	function create_mtw_custom_code_dir()
	{

		global $wp_filesystem;
		WP_Filesystem();

		$mtw_theme_path = ABSPATH . 'mtw-themes';
		
		if ( !file_exists($mtw_theme_path) ) 
		{
		
		$wp_filesystem->mkdir( $mtw_theme_path , 0755, true);

		$txt = "This forlder is for your html muse export \n
	Create a folder by project \n
	- mtw-themes/myProject1/index.html \n
	- mtw-themes/myProject2/index.html \n";
		$wp_filesystem->put_contents($mtw_theme_path."/readme.txt", $txt);
		
		}
		
		

		$mtw_content_path = ABSPATH . 'mtw-codes';
		
		if ( !file_exists($mtw_content_path) ) 
		{
		
		$wp_filesystem->mkdir( $mtw_content_path , 0755, true);
		
		//$readmeCodes = fopen($mtw_content_path."/readme.txt", "w") or die("Unable to open file!");
		$txt = "This forlder is for your custom code .css .php .js \n
	For exemples \n
	- mtw-themes/mystyle.css \n
	- mtw-themes/myeffect.js \n
	- mtw-themes/mycode.php \n
	\n
	\n
	.js\n 
	your files is linked in the footer\n
	you can use jquery\n
	use: jQuery( document ).ready( function($) {  } );\n
	\n
	.php\n
	you can use all wordpress function
	included in a init action of Wordpress
	";
		$wp_filesystem->put_contents($mtw_content_path."/readme.txt", $txt, FS_CHMOD_FILE);

		}

		$ver = '2.7';
		
		foreach ( glob( $mtw_content_path . "/*.php" ) as $file ) 
		{ 
			include_once $file;
		} 

		if( !is_admin() )
		{
			foreach ( glob( $mtw_content_path . "/*.css" ) as $file ) 
			{ 
				$ver = urlencode( filemtime( $file ) );
				$css_src = str_replace(ABSPATH, site_url().'/', $file);
				wp_enqueue_style( basename($file), $css_src, array(), $ver );
			} 

			foreach ( glob( $mtw_content_path . "/*.js" ) as $file ) 
			{ 
				$ver = urlencode( filemtime( $file ) );
				$js_src = str_replace(ABSPATH, site_url().'/', $file);
				wp_enqueue_script( basename($file), $js_src, array('jquery'), $ver, true );
			} 
		}
	}
	add_action('init' , 'create_mtw_custom_code_dir' );


	function mtw_load_custom_admin_styles() {
		wp_register_style( 'mtw_dashicons', TTR_MW_PLUGIN_URL . 'dashicon/style.css', false, '1.0.0' );
		wp_enqueue_style( 'mtw_dashicons' );
	}
	add_action( 'admin_enqueue_scripts', 'mtw_load_custom_admin_styles' );

	function exclude_template_link_and_script($html)
	{
		global $is_mtw_plugin;
		if( $is_mtw_plugin )
		{
			$link_script = array(
				'link' => 'href',
				'script' => 'src',
				);
			$remove_items = array();
			foreach ($link_script as $tag => $type) 
			{
				$links = $html->getElementsByTagName( $tag );
				foreach ($links as $link) 
				{
					if( $link->getAttribute( $type ) && strpos( $link->getAttribute( $type ) , get_template_directory_uri() ) !== false )
					{
						$remove_items[] =  $link;
					}

				}	
			}
			foreach ($remove_items as $item) {
				$item->parentNode->removeChild($item); 
			}
		}
	}

	require_once TTR_MW_PLUGIN_DIR . 'addons/mtw-comment-zone/mtw-comment-zone.php';
	require_once TTR_MW_PLUGIN_DIR . 'addons/mtw-custom-post-type/mtw-custom-post-type.php';
	require_once TTR_MW_PLUGIN_DIR . 'addons/mtw-grid-list/mtw-grid-list.php';
	require_once TTR_MW_PLUGIN_DIR . 'addons/mtw-shortcodes/mtw-shortcodes.php';
	require_once TTR_MW_PLUGIN_DIR . 'addons/mtw-slideshow-attachements/mtw-slideshow-attachements.php';
}
else
{
	function mtw_php_support()
	{
	?>
    <div class="error notice">
    	 <p><?php _e( 'Muse to WordPress is not working.', 'mwuse' ); ?></p>
        <p><?php _e( 'Your PHP version is not supported, minimum require: 5.5.9', 'mwuse' ); ?></p>
    </div>
    <?php
	}
	add_action( 'admin_notices', 'mtw_php_support' );
}
?>