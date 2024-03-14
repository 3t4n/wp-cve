<?php
/**
 * Plugin Name:       Microsoft Clarity
 * Plugin URI:        https://clarity.microsoft.com/
 * Description:       With data and session replay from Clarity, you'll see how people are using your site — where they get stuck and what they love.
 * Version:           0.9.4
 * Author:            Microsoft
 * Author URI:        https://www.microsoft.com/en-us/
 * License:           MIT
 * License URI:       https://docs.opensource.microsoft.com/content/releasing/license.html
 */

require_once plugin_dir_path(__FILE__).'/clarity_page.php';


/**
* Runs when Clarity Plugin is activated
**/
register_activation_hook(__FILE__, 'clarity_on_activation');
function clarity_on_activation() {
    $clarity_wordpress_site_id = get_option('clarity_wordpress_site_id');
    // generate an identifier for this wordpress site to Clarity
    if (empty($clarity_wordpress_site_id)) {
        update_option(
            'clarity_wordpress_site_id', /* option */
            wp_generate_uuid4() /* value */
            /* autoload */
        );
    };
}

/**
* Runs when Clarity Plugin is deactivated
**/
register_deactivation_hook(__FILE__, 'clarity_on_deactivation');
function clarity_on_deactivation() {
    update_option(
        'clarity_project_id', /* option */
        '' /* value */
        /* autoload */
        );
    update_option(
        'clarity_wordpress_site_id', /* option */
        '' /* value */
        /* autoload */
        );
  	return;
}

/**
* Runs when Clarity Plugin is uninstalled
**/
register_uninstall_hook('uninstall.php', 'clarity_on_uninstall');
function clarity_on_uninstall() {
	delete_option(
        'clarity_project_id' /* option */
    );
  	return;
}

/**
* escapes the plugin id characters
**/
function escape_value_for_script($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
* Adds the script to run clarity
**/
add_action('wp_head', 'clarity_add_script_to_header');
function clarity_add_script_to_header(){
    $p_id_option = get_option(
        'clarity_project_id' /* option */
        /* default */
    );
	if (!empty($p_id_option)) {
		?>
		<script type="text/javascript">
				(function(c,l,a,r,i,t,y){
					c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;
					t.src="https://www.clarity.ms/tag/"+i+"?ref=wordpress";y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
				})(window, document, "clarity", "script", "<?php echo escape_value_for_script($p_id_option); ?>");
		</script>
		<?php
	}
}

/**
* Adds the page link to the Microsoft Clarity block on installed plugin page
**/
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'clarity_page_link');
function clarity_page_link($links) {
	$url = get_admin_url() . 'admin.php?page=microsoft-clarity';
	$clarity_link = "<a href='$url'>" . __('Clarity Dashboard') . '</a>';
	array_unshift($links, $clarity_link);
	return $links;
}
