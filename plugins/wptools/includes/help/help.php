<?php /**
 * @author Bill Minozzi
 * @copyright 2021
 */
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
if (is_admin()) {
    add_action('current_screen', 'wptools_this_screen');
    function wptools_this_screen()
    {
        // removed add_filter('contextual_help', 'wptools_contextual_help_fields', 10, 3);
        // called functions...directly
        require_once ABSPATH . 'wp-admin/includes/screen.php';
        $current_screen = get_current_screen();
       // var_dump($current_screen->id);
        if (trim($current_screen->id) === "wp-tools_page_wptools_options31") {
            wptools_contextual_help_dashboard($current_screen);
        } elseif (trim($current_screen->id) === "toplevel_page_wp-tools") {
            wptools_contextual_help_settings($current_screen);
        } else {
            if (isset($_GET['page'])) {
                if (stripos(sanitize_text_field($_GET['page']), 'wptools_options') !== false) {
                    wptools_contextual_help_dashboard($current_screen);
                }
            }
        }
    }
}
function wptools_contextual_help_dashboard($screen)
{
    $site = WPTOOLSADMURL . "admin.php?page=wp-tools";
    $site2 = WPTOOLSADMURL . "admin.php?page=wptools_options31";
    $myhelp = '
    <br />';
    if (trim($screen->id) === "wp-tools_page_wptools_options31") 
       $myhelp .=   esc_attr__("In the DASHBOARD screen you will find useful information about your server.","wptools");
    else
       $myhelp .= '<a href="'.$site2.'" >'.
       esc_attr__("Here are the link to the Dashboard Plugin Page with more useful information","wptools").'<a>';
    $myhelp .= '
    <br />
    <br />'.
    esc_attr__("You will find also links to ","wptools").'
    <a href="'.$site.'" >'.
    esc_attr__("StartUp Guide","wptools").' '.
    esc_attr__("and Settings, Troubleshooting Guide Page and Support Page.","wptools").'
    </a> 
    <br /> <br /> 
    <a href="https://wptoolsplugin.com/blog/">'.
    esc_attr__("Here are the link to our free blog with a lot of tips and tricks and about how to use this tool.","wptools").'  
	</a>
    <br />';
    $screen->add_help_tab(array(
        'id' => 'wptools-overview-tab',
        'title' => esc_attr__('Overview', 'wptools'),
        'content' => '<p>' . $myhelp . '</p>',
    ));
    return;
}
function wptools_contextual_help_settings($screen)
{
    $site = WPTOOLSADMURL . "admin.php?page=wptools_options31";
    $myhelp = '<br />'.
    esc_attr__("In the SETTINGS screen you will find many tabs, with useful information as StartUP Guide.","wptools").'
    <br />
    <br />'.
    esc_attr__("You will find also fields and controls to configurate the plugin. Take some time to open each tab and mark that you want.","wptools").'
    <br />
    <br />  
    <a href="'.$site.'">'.
    esc_attr__("Here are the link to the Dashboard Plugin Page with more useful information.","wptools").' 
    </a> 
    <br />
    <br />';
    $screen->add_help_tab(array(
        'id' => 'wptools-overview-tab',
        'title' => esc_attr__('Overview', 'wptools'),
        'content' => '<p>' . $myhelp . '</p>',
    ));
    return;
}
/////////// Pointers ////////////////
/*
if (is_admin() or is_super_admin()) {
   if (get_option('wptools_was_activated', '0') == '1') {
        add_action('admin_enqueue_scripts', 'wptools_adm_enqueue_scripts2');
    }
}
*/
function wptools_adm_enqueue_scripts2()
{
    global $bill_current_screen;
    // wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script('wp-pointer');
    require_once ABSPATH . 'wp-admin/includes/screen.php';
    $myscreen = get_current_screen();
    $bill_current_screen = $myscreen->id;
    $dismissed_string = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
    // $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
   // if (in_array('plugins', $dismissed)) {  
    if ( !empty($dismissed_string))  {
        $r = update_option('wptools_was_activated', '0');
        if (!$r) {
            add_option('wptools_was_activated', '0');
        }
        return;
    }
    add_action('admin_print_footer_scripts', 'wptools_admin_print_footer_scripts');
}

function wptools_admin_print_footer_scripts()
{
    global $bill_current_screen;

    //$pointer_content = '<h3>'.esc_attr__("Open WP Tools Plugin Here!", "wptools").'</h3>';
    //$pointer_content = $pointer_content . '<p>'.esc_attr__("Just Click Over WP Tools, then Go To Settings=>StartUp Guide.","wptools").'<p>';

    $pointer_content = esc_attr__("Open WP Tools Plugin Here!", "wptools");
    $pointer_content2 = $pointer_content .esc_attr__("Just Click Over WP Tools, then Go To Settings=>StartUp Guide.","wptools");

 
 ?>
        <script type="text/javascript">
        //<![CDATA[
            // setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
        jQuery(document).ready( function($) {
            jQuery('#toplevel_page_wp-tools').pointer({
               
               
                content: '<?php echo '<h3>'.esc_attr($pointer_content).'</h3>'. '<div id="bill-pointer-body">'.esc_attr($pointer_content2).'</div>';?>',
    
               
                position: {
                        edge: 'left',
                        align: 'right'
                    },
                close: function() {
                    // Once the close button is hit
                    jQuery.post( ajaxurl, {
                            pointer: '<?php echo esc_attr($bill_current_screen); ?>',
                            action: 'dismiss-wp-pointer'
                        });
                }
            }).pointer('open');
            /* $('.wp-pointer-undefined .wp-pointer-arrow').css("right", "50px"); */
        });
        //]]>
        </script>
        <?php
}
?>