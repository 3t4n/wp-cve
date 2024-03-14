<?php
/*
Plugin Name: Branded Login Screen
Plugin URI: http://brandedlogin.kerrywebster.com
Description: <strong>Bring Your Brand Forward.</strong> Customize and enhance the WordPress Login screen. <a href="http://brandedlogin.kerrywebster.com"><strong>Get the PRO version</strong></a>
Author: Kerry Webster
Version: 3.2
Author URI: http://brandedlogin.kerrywebster.com
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'This plugin cannot be called directly.';
	exit;
}

if ( class_exists( 'Branded_Login_Screen_Pro' ) ) {
				$msg  = '<a href="/wp-admin/plugins.php"><img src="/wp-content/plugins/branded-login-screen/assets/i/err-header.png" border="0" /></a><br/>';
				$msg .= '<b>Branded Login Screen</b> cannot be activated with \'Branded Login Screen Pro\' active.<br/>';
                $msg .= 'Please deactivate \'Branded Login Screen Pro\' before activating <b>Branded Login Screen.</b><br/>';
                $msg .= '<b>Branded Login Screen</b> has not been activated.<br/>';
                $msg .= '<hr><p>';
                $msg .= '<a href="/wp-admin/plugins.php">Back to the plugins page.</a>';
                $msg .= '</p>';
				wp_die( __( $msg ) );
}

// constant definition
if( !defined( 'IS_ADMIN' ) )
    define( 'IS_ADMIN', is_admin() );

define( 'BLS_VERSION',      '3.2' );
define( 'BLS_DIR',          WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) );
define( 'BLS_URL',          rtrim( plugin_dir_url( __FILE__ ), '/' ) . '/' );
define( 'BLS_UPLOAD_URL',   BLS_URL . 'assets/i' );
define( 'BLS_REDIRECT',     '' );
define( 'BLS_SITE_URL',     get_site_url() ); //wordpress function
define( 'BLS_SITE_NAME',    get_bloginfo() ); //wordpress function
define( 'BLS_BG_IMG_DIR',   BLS_UPLOAD_URL.'/background_image/' );
define( 'BLS_HDR_DIR',      BLS_UPLOAD_URL.'/logo_main' );
define( 'BLS_RHDR_DIR',     BLS_UPLOAD_URL.'/logo_responsive' );

/* --------------------------------------------------------------------------------------------- */
/* you can edit the variables below to make changes to the login form and the images used for    */
/* the background. Please refer to the plugins documentation for examples and explanation        */
/* --------------------------------------------------------------------------------------------- */

define( 'BLS_LOCATION',     2 ); //used to change location of login form on the page. See documentation for value meanings

define( 'BLS_FULL_SCREEN',  true ); // if using a repeatable background image, set this to false.

define( 'BLS_BG_IMG',       'default-img.jpg' ); // place your 'background'      image in the '/assets/i/background_image' folder.
define( 'BLS_HDR_LOGO',     'header.png' );      // place your 'header logo'     image in the '/assets/i/logo_main'        folder.
define( 'BLS_RHDR_LOGO',    'header-sm.png' );   // place your 'responsive logo' image in the '/assets/i/logo_responsive'  folder.

/* --------------------------------------------------------------------------------------------- */

class Branded_Login_Screen
{

	private $plugin_name    = 'Branded Login Screen';
	private $plugin_version = '3.2';


	public function __construct() {

		register_activation_hook( __FILE__,      array( __CLASS__, 'branded_login_screen_activate' ) );
		register_uninstall_hook(  __FILE__,      array( __CLASS__, 'uninstall' ) );

		add_action( 'login_enqueue_scripts',     array( __CLASS__, 'branded_login_screen_styles' ) );
		add_action( 'login_enqueue_scripts',     array( __CLASS__, 'branded_login_screen_scripts' ) );
		add_action( 'login_enqueue_scripts',     array( __CLASS__, 'branded_login_screen_vars' ) );
		add_filter( 'plugin_row_meta',           array( __CLASS__, 'branded_login_screen_plugin_meta_links'), 10, 2 );
		add_action( 'login_footer',              array( __CLASS__, 'branded_login_screen_footer' ) );

		if( IS_ADMIN ) {
			add_action( 'admin_init',            array( __CLASS__, 'branded_login_screen_environment_check' ) );
		}

	} //End of Branded_Login_Screen_Pro __construct


    /**
     * Css additions for login page.
     *
     * Css for login form location on page. Header/responsive header images media queries. Admin Css link.
     *
     * @param
     * @return void
     * @author Kerry Webster
     */
	public function branded_login_screen_styles() {

		switch ( BLS_LOCATION ) {
			case 0: //middle-center ?>
				<style>div#login { top: 0; right: 0; bottom: 0; left: 0; padding: 0; }</style>
				<?php
				break;
			case 1: //middle-left ?>
				<style>div#login { top: 0; right:auto; bottom: 0; left: 0; padding: 0 0 0 40px; }</style>
				<?php
				break;
			case 2: //middle-right ?>
				<style>div#login { top: 0; right: 0; bottom: 0; left:auto; padding: 0 40px 0 0; }</style>
				<?php
				break;
			case 3: //top-center ?>
				<style>div#login { top: 10px; right: 0; bottom: auto; left: 0; padding: 0; }</style>
				<?php
				break;
			case 4: //top-left ?>
				<style>div#login { top: 10px; right:auto; bottom: auto; left: 0; padding: 0 0 0 40px; }</style>
				<?php
				break;
			case 5: //top-right ?>
				<style>div#login { top: 10px; right: 0; bottom: auto; left:auto; padding: 0 40px 0 0; }</style>
				<?php
				break;
			default: //middle-center ?>
				<style>div#login { top: 0; right: 0; bottom: 0; left: 0; padding: 0; }</style>
				<?php
		}
		?>

		<style> .login h1 a { background:url(<?php echo BLS_HDR_DIR . '/' . BLS_HDR_LOGO; ?>) no-repeat scroll center top transparent; } </style>

		<style> @media all and (max-width: 360px), (max-height: 465px){ .login h1 a { background:url(<?php echo BLS_RHDR_DIR . '/' . BLS_RHDR_LOGO; ?>) no-repeat scroll center top transparent; width: 260px; height: 67px; } } </style>
		<link rel="stylesheet" id="custom_button_css"  href="<?php echo plugins_url( '/assets/c/custom-button.css', __FILE__ ); ?>" type="text/css" media="all" />
		<link rel="stylesheet" id="branded_login_css"  href="<?php echo plugins_url( '/assets/c/branded-login-screen.css', __FILE__ ); ?>" type="text/css" media="all" />
		<?php
	}

    /**
     * Plugin javacsript enqueue
     *
     * Add the plugins javascript file
     *
     * @param
     * @return void
     * @author Kerry Webster
     */
	public function branded_login_screen_scripts() {
		wp_enqueue_script( 'branded-login-screen', plugins_url('/assets/j/branded-login-screen.js', __FILE__ ), array( 'jquery' ), '1.0',   TRUE );
	}

    /**
     * Get header url and title
     *
     * Header URL and title used for redirection after the user clicks the login logo
     *
     * @param
     * @return void
     * @author Kerry Webster
     */
	public function branded_login_screen_vars() {
		add_filter( 'login_headerurl',   array( __CLASS__, 'bls_login_header_url' ) );
		add_filter( 'login_headertitle', array( __CLASS__, 'bls_login_site_name' ) );
	}

    /**
     * Fullscreen or repeatable background image
     *
     * Inserts the proper .css for the selected background image type
     *
     * @param
     * @return void
     * @author Kerry Webster
     */
	public function branded_login_screen_footer(){

		$imageName = BLS_BG_IMG_DIR.BLS_BG_IMG;

		if ( BLS_FULL_SCREEN ) {
			?>

			<style>
			body.login { background:#fff url('<?php echo $imageName; ?>') no-repeat fixed top center;

			margin:                  0;
			padding:                 0;

			background-size:         cover;
			-moz-background-size:    cover;
			-webkit-background-size: cover;
			}
			</style>

			<?php
		} else {
			?>

			<style> body.login { background:#fff url('<?php echo $imageName; ?>') repeat fixed top; width:100%; height:100%; } </style>

			<?php
		} ;
	}

    /**
     * Pro version link
     *
     * Link to get the Pro version of the plugin
     *
     * @param $links
     * @param $file
     * @return $links
     * @author Kerry Webster
     */
	public function branded_login_screen_plugin_meta_links( $links, $file ) {
		$plugin = plugin_basename( __FILE__ );

		// create link
		if ( $file == $plugin ) {
			return array_merge(
				$links,
				array( '<strong><a href="http://brandedlogin.kerrywebster.com">Get the PRO Version</a></strong>' )
			);
		}
		return $links;
	}

/*------------------------------------------------------------------------------------*/

    /**
     * Check for proper WordPress and PHP versions
     *
     * Fails plugin load due to version limitations. Suggests upgrades where appropriate.
     *
     * @return void or die
     * @author Kerry Webster
     */
    function branded_login_screen_environment_check()
    {
        $wp_version = get_bloginfo( 'version' );
        $php_ver_needed = '5.2.4';
        $wp_ver_needed = '3.3';
        $php_ok = version_compare( PHP_VERSION, $php_ver_needed, '>=' );
        $wp_ok = version_compare( $wp_version, $wp_ver_needed, '>=' );

        $php_ok_status = $php_ok ? '<span style="color: #00cc00;">OK</span>' : '<span style="color: #ff0000;"><a href="http://us.php.net/downloads.php">Please upgrade your PHP install.</a></span>';
        $wp_ok_status =  $wp_ok  ? '<span style="color: #00cc00;">OK</span>' : '<span style="color: #ff0000;"><a href="http://wordpress.org/download/">Please upgrade your WordPress install.</a></span>';

        if( !$php_ok || !$wp_ok )
        {
            if( IS_ADMIN && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
            {
                require_once ABSPATH.'/wp-admin/includes/plugin.php';
                deactivate_plugins( __FILE__ );
				$msg  = '<a href="/wp-admin/plugins.php"><img src="/wp-content/plugins/branded-login-screen/assets/i/err-header.png" border="0" /></a><br/>';
				$msg  .= '<b>Branded Login Screen</b> requires WordPress version ';
                $msg .= $wp_ver_needed . '+';
                $msg .= ' and PHP version ';
                $msg .= $php_ver_needed . '+';
                $msg .= '. <br/>It has been automatically deactivated.<br/>';
                $msg .= '<hr><p>';
                $msg .= 'Your current installed versions: <br/>';
                $msg .= 'WordPress: ' . $wp_version . ' - ' . $wp_ok_status . '<br/>';
                $msg .= 'PHP: ' . PHP_VERSION . ' - ' . $php_ok_status . '</p>';
                $msg .= '<a href="/wp-admin/plugins.php">Back to the plugins page.</a>';
				wp_die( __( $msg ) );
            }
            else
            {
                return;
            }
        }
    }

	/**
	 *	Plugin activation
	 *
	 *	Set plugin defaults
	 *
	 *	@author		Kerry Webster
	 *	@since		3.0
	 */
	public function branded_login_screen_activate()
	{

	}




/**
* CLASS PRIVATE FUNCTIONS
*/


	function bls_login_header_url() {
		if ( BLS_REDIRECT != NULL ) {
			return BLS_REDIRECT;
		} else {
			return BLS_SITE_URL;
		}
	}

	function bls_login_site_name() {
		if ( BLS_REDIRECT != NULL ) {
			return BLS_REDIRECT;
		} else {
			return 'Back to ' . BLS_SITE_NAME . ' >> Home Page';
		}
	}

} // end of class Branded_Login_Screen

// bootstrap
new Branded_Login_Screen();









?>