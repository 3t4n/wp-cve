<?PHP
/*********************************
* init event handler.
* Most spam prevention goes here
**********************************/
if (!defined('ABSPATH')) exit;
/* 
	all checking is on post 
	check only standard WP fields.
	Do not check logins.
	Check only registrations and comments.
	do not check non standard forms such as WooCommerce.
*/

function astound_init() {
	remove_action('init','astound_init');
	$options=astound_get_options();
	if ($options['astound_chksession']=='Y') {
		if (!isset($_POST) || empty($_POST)) {
			// cookie setting for session speed test
			setcookie( 'astound_prevention_time', strtotime("now"), strtotime('+1 min'));
		} else if (!isset($_COOKIE['astound_stop_spammers_time'])) {
			setcookie( 'astound_prevention_time', strtotime("now"), strtotime('+1 min'));
		}	
	}

	if(is_user_logged_in()) {
		if(current_user_can('manage_options')) {
			// this means we can install the options page on the network options page.
			astound_require('includes/astound-admin-options.php');
			add_action( 'admin_enqueue_scripts', 'astound_admin_scripts' );
			add_action( 'admin_menu', 'astound_admin_menu' );
		}
		return; /* don't need spam control if the user is logged in - it is way too late */
	}
	/* check to see if the red herring form is to be installed. */
	if ($options['astound_chkredherring']=='Y') {
		astound_require('includes/astound-redherring-setup.php');
		astound_install_redherring();
	}

	
	/*
		check for form submition. Test are only done on form submission
	*/
	if (!isset($_POST) || empty($_POST)) {
		return;
	}
	/*
		check the post for required fields so we don't load anything we don't need
	*/
	if ( (array_key_exists('comment',$_POST) && array_key_exists('email',$_POST)) ||
		(array_key_exists('user_login',$_POST) && array_key_exists('user_email',$_POST))
	) {
		/* load the post check */
		astound_require('includes/astound-post-checks.php');
		astound_post_checks();
	}
}
/*
* install hooks and filters.
*/















?>
