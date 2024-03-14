<?php
/*
Plugin Name: Really Simple Under Construction Page
Plugin URI: https://wordpress.org/plugins/really-simple-under-construction/
Description: ...or Hide From Google functionality. Adds a really simple version of a Under Construction page. Use whitelisted IP och secret word to skip under construction page. Also good to have when you want to hide the site from search bots when developing a new site.
Version: 1.4.6
Author: jonashjalmarsson
Author URI: http://jonashjalmarsson.se
Text Domain: rsuc
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( '' );

/* ADD INIT ACTION */
// add_action( 'init', 'rsuc_init' );
add_action( 'plugins_loaded', 'rsuc_init' );
function rsuc_init() {
    
    // enable textdomain
    load_plugin_textdomain( 'rsuc', false, basename( dirname( __FILE__ ) ) . '/languages' ); 

    /* IGNORE RSUC IF FOLLOWING */

    // if enabled NOT is checked
    if (1 != get_option('rsuc-enable')) { return; } 
    
    // if logged in
    if (is_user_logged_in()) { return; } 

    $current_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $admin_url = get_admin_url();

    // if login page or admin page
    if ((strpos(wp_login_url(), $GLOBALS['pagenow']) !== false) || 
        (strpos($current_url, $admin_url) !== false) || 
        ($_SERVER['REQUEST_URI'] == '/admin') || 
        ($_SERVER['REQUEST_URI'] == '/admin/') || 
        ($_SERVER['REQUEST_URI'] == '/wp-admin') ||
        ($_SERVER['REQUEST_URI'] == '/wp-admin/') ) { 
            return; } 

    // if api call
    if (isset($_REQUEST['wc-api']) || (strpos($GLOBALS['PHP_SELF'], '/wp-json/') !== false)) { return; } 

    // if enable-homepage i enabled and is_front_page
    if (1 == get_option('rsuc-enable-homepage') && $_SERVER['REQUEST_URI'] == '/') { return; } 

    // if ip in whitelist
    $user_ip = rsucGetIPAddress();
    $ip_list = get_option('rsuc-ip');
    if (!empty($ip_list) && !empty($user_ip)) {
        // get whitelist array
        $ip_array = explode("\n", $ip_list);
        $clean_ip_array = [];
        foreach ($ip_array as $ip) {
            if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $ip, $ip_match)) {
                $clean_ip_array[] = $ip_match[0];
            }
        }
        
        // check if user ip in whitelist array
        if (in_array($user_ip, $clean_ip_array)) {
            return; // if user is whitelisted
        }
    }
    
    
    /* SHOW RSUC IF FOLLOWING */
    
    // if secret word is set
    $secretword = get_option('rsuc-secret-word');
    if ($secretword != '') {
        $rsuc_cookie_name = "rsuc_cookie";
        $user_url_rsuc_secret = isset($_GET[$secretword]) ? true : false; 
        
        // check cookie values
        $trusted_user = false;
        // $rsuc_cookie_value = get_option('rsuc-secret-word');
        if ( isset($_COOKIE[$rsuc_cookie_name]) && ($_COOKIE[$rsuc_cookie_name] == $secretword) ) {
            $trusted_user = true;
        }
        
        // check url argument
        else if ($user_url_rsuc_secret) {
            $trusted_user = true;
            // set cookie
            $cookie_time = 30;
            $option_cookie_time = get_option('rsuc-cookie-time');
            if (isset($option_cookie_time) && is_numeric($option_cookie_time) && $option_cookie_time > 0 && $option_cookie_time < 366) {
                $cookie_time = $option_cookie_time;
            }
            setcookie($rsuc_cookie_name, $secretword, time() + (86400 * $cookie_time), "/"); // 86400 = 1 day
        }


        // die if wrong secret word and wrong cookie word
        if (!$trusted_user) {
            die(get_option('rsuc-html'));                    
        }
    }
    // else die of no secret word is set
    else {
        die(get_option('rsuc-html'));
    }
}

/* ADD ADMIN PAGE */
add_action('admin_menu', 'rsuc_menu');
function rsuc_menu() {
    add_submenu_page('options-general.php',
        'Really Simple Under Construction',
        'Really Simple Under Construction',
        'manage_options',
        'rsuc-submenu-page',
        'rsuc_submenu_page_callback' );
    add_action( 'admin_init', 'rsuc_plugin_settings' );
}

/* REGISTER SETTINGS */
function rsuc_plugin_settings() {
	register_setting( 'rsuc-settings-group', 'rsuc-enable' );
	register_setting( 'rsuc-settings-group', 'rsuc-enable-homepage' );
	register_setting( 'rsuc-settings-group', 'rsuc-secret-word' );
	register_setting( 'rsuc-settings-group', 'rsuc-cookie-time' );
	register_setting( 'rsuc-settings-group', 'rsuc-html' );
	register_setting( 'rsuc-settings-group', 'rsuc-ip' );
}

/* SHOW ADMIN PAGE */
function rsuc_submenu_page_callback() {
?>
<style>
    form {
        max-width: 600px;
    }
    section {
        margin-bottom: 60px;
    }
    h3 {
        margin-top: 20px;
        font-size: 13px;
    }
    input[type='text'],
    input[type='number'] {

        margin-bottom:10px;
    }
</style>
<div class="wrap">
<h1>Really Simple Under Construction</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'rsuc-settings-group' ); ?>
    <?php do_settings_sections( 'rsuc-settings-group' ); ?>
    <section>
        <h2><?php _e('General Settings'); ?></h2>
        <p><?php _e("Block users from seeing your site by enabling the under construction page below. Logged in users still can see the website. Use secret word och IP whitelisting to grant access to users witout logging in.", 'rsuc'); ?></p>

        <input type="checkbox" name="rsuc-enable" value="1"<?php checked( 1 == get_option('rsuc-enable') ); ?> />
        <span><?php _e("Enable the 'Under Construction page'",'rsuc'); ?></span><br/>

        <input type="checkbox" name="rsuc-enable-homepage" value="1"<?php checked( 1 == get_option('rsuc-enable-homepage') ); ?> />
        <span><?php _e("Make Wordpress Static Homepage visible: ",'rsuc'); ?> <?php echo (get_option('page_on_front') != 0) ? sprintf( "<i><a href='%s'>%s</a></i>", get_edit_post_link( get_option('page_on_front'), 'edit'), get_the_title( get_option('page_on_front') ) ) : "Not set"; ?></span>

        <h3><?php _e('HTML to show as Under Construction page', 'rsuc'); ?></h3>
        <textarea name="rsuc-html" style="width: 600px; max-width: 100%; height: 200px;"><?php echo esc_attr( get_option('rsuc-html') ); ?></textarea>
    </section>
    
    <section>
        <h2><?php _e('Secret Word'); ?></h2>
        <p><?php _e("Add your Secret Word to create a link to use and by-pass the Under Construction page, a cookie is saved to remember that browser. Clear the secret word or uncheck the enable plugin to disable the Under Construction site. When you change the secret all previous cookies will be obsolete.", 'rsuc'); ?></p>
        <h3><?php _e("Secret Word to by-pass for one browser", 'rsuc'); ?></h3>
        <input type="text" name="rsuc-secret-word" value="<?php echo esc_attr( get_option('rsuc-secret-word') ); ?>" /><br />
        <?php if (get_option('rsuc-secret-word') != "") { ?>
            <p><i><?php printf(
                __( 'Use the URL %s to show the website.', 'rsuc' ),
                "<a href='" . get_home_url() . "?" . get_option('rsuc-secret-word') . "'>" . get_home_url() . "?" . get_option('rsuc-secret-word') . "</a>");
            ?></i></p>
        <?php } ?>
              
        <h3><?php _e('Set number of days the site should be remembered by the web browser.', 'rsuc'); ?></h3>
        <input type="number" name="rsuc-cookie-time" value="<?php echo esc_attr( get_option('rsuc-cookie-time') ); ?>" /><br />
        <i><?php _e('Default is 30 days if nothing is set. Can not be larger than 365 days. ', 'rsuc'); ?></i>
    </section>

    <section>
        <h2><?php _e('Whitelist Word'); ?></h2>

        <p><?php _e("Add a user IP to the whitelist, one IP per row. <br />Comment after the IP to remember what user or service is using the IP. We will find the first IP-address at every new row."); ?></p>

        <h3><?php _e('User IP addresses to whitelist', 'rsuc'); ?></h3>
        <textarea name="rsuc-ip" id="rsuc-ip" style="width: 600px; max-width: 100%; height: 200px;"><?php echo esc_attr( get_option('rsuc-ip') ); ?></textarea>
        <p><i>Add your IP address to whitelist. <span style='cursor: pointer; text-decoration: underline;' id='rsuc-append-link' href='#'><?= rsucGetIPAddress(); ?></span></i></p>
        <?php 
        ?>
        <script>
            document.getElementById('rsuc-append-link').addEventListener('click', function (ev) {
                ev.preventDefault();
                new_line = '';
                if (document.getElementById("rsuc-ip").value != '') {
                    new_line = '\n';
                }
                document.getElementById("rsuc-ip").value += new_line + '<?= rsucGetIPAddress() ?> // my ip'
            });            
        </script>
    </section>
    <?php submit_button(); ?>

</form>
</div>
<?php } 


/* get user ip address */
function rsucGetIPAddress() {  
    //whether ip is from the share internet  
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
		$ip = $_SERVER['HTTP_CLIENT_IP'];  
	}  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
	}  
	//whether ip is from the remote address  
	else{  
		$ip = $_SERVER['REMOTE_ADDR'];  
	}  
	return $ip;  
}  


/* add settingslink in plugin-list */
add_filter( 'plugin_action_links_really-simple-under-construction/really-simple-under-construction.php', 'rsuc_settings_link' );
function rsuc_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'rsuc-submenu-page',
		get_admin_url() . 'options-general.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}
