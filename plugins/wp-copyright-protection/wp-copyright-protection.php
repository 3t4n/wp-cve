<?php
/*
Plugin Name: WP-Copyright-Protection
Plugin URI: https://github.com/dligthart/wp-copyright-protection
Description: A simple way to add copyright protection to your website. Disables text copy, image copy and breaks out of iframe.
Author: dave.ligthart
Version: 1.7
Author URI: https://daveligthart.com
*/

/**
 * Main.
 *
 * @author dligthart
 * @package wpcp
 * @subpackage core
 * @version 1.7
 */

/** Back-End **/

if (is_admin()) { // admin actions
    add_action('admin_menu', 'wpcp_create_menu');
    add_action('admin_init', 'wpcp_register_options');
}
add_action('wp_head', 'wp_copyright_protection');


/**
 * wpcp_create_menu function.
 *
 * @access public
 * @return void
 */
function wpcp_create_menu()
{
    add_options_page('WP-Copyright-Protection', 'WP-Copyright-Protection',
        'manage_options', 'wpcp_options', 'wpcp_render_settings_page');
}


/**
 * wpcp_register_options function.
 *
 * @access public
 * @return void
 */
function wpcp_register_options()
{
    register_setting('wpcp-group', 'wpcp_exclude_pages');
    register_setting('wpcp-group', 'wpcp_disable_for_regusers');
}


/**
 * wpcp_render_settings_page function.
 *
 * @access public
 * @return void
 */
function wpcp_render_settings_page()
{
    wp_enqueue_style('custom-css', plugins_url('/assets/css/style.css', __FILE__));
    require('settings.php');
}


/**
 * wpcp_is_excluded function.
 *
 * @access public
 * @return boolean
 */
function wpcp_is_excluded()
{

    $excludedPages = explode(',', get_option('wpcp_exclude_pages'));

    if (is_array($excludedPages)) {

        foreach ($excludedPages as $pageId) {

            if (null != $pageId && is_page($pageId)) {

                return true;
            }
        }
    }

    return false;
}


/**
 * wpcp_is_disabled function.
 *
 * @access public
 * @return boolean
 */
function wpcp_is_disabled()
{

    $disabledForRegisteredUsers = get_option('wpcp_disable_for_regusers');

    if ($disabledForRegisteredUsers) {

        if (is_user_logged_in()) {

            return true;
        }
    }

    return false;
}


/** Front-End **/


/**
 * wp_copyright_protection function.
 *
 * @access public
 * @return void
 */
function wp_copyright_protection()
{

    if (!wpcp_is_excluded() && !wpcp_is_disabled()) {

        ?>
        <!-- Copyright protection script -->
        <meta http-equiv="imagetoolbar" content="no">
        <script>
            /*<![CDATA[*/
            document.oncontextmenu = function () {
                return false;
            };
            /*]]>*/
        </script>
        <script type="text/javascript">
            /*<![CDATA[*/
            document.onselectstart = function () {
                event = event || window.event;
                var custom_input = event.target || event.srcElement;

                if (custom_input.type !== "text" && custom_input.type !== "textarea" && custom_input.type !== "password") {
                    return false;
                } else {
                    return true;
                }

            };
            if (window.sidebar) {
                document.onmousedown = function (e) {
                    var obj = e.target;
                    if (obj.tagName.toUpperCase() === 'SELECT'
                        || obj.tagName.toUpperCase() === "INPUT"
                        || obj.tagName.toUpperCase() === "TEXTAREA"
                        || obj.tagName.toUpperCase() === "PASSWORD") {
                        return true;
                    } else {
                        return false;
                    }
                };
            }
            window.onload = function () {
                document.body.style.webkitTouchCallout = 'none';
                document.body.style.KhtmlUserSelect = 'none';
            }
            /*]]>*/
        </script>
        <script type="text/javascript">
            /*<![CDATA[*/
            if (parent.frames.length > 0) {
                top.location.replace(document.location);
            }
            /*]]>*/
        </script>
        <script>
            /*<![CDATA[*/
            document.ondragstart = function () {
                return false;
            };
            /*]]>*/
        </script>
        <style type="text/css">

            * {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            img {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            input, textarea, select {
	            -webkit-user-select: auto;
            }

        </style>
        <!-- End Copyright protection script -->

        <!-- Source hidden -->

        <?php
    }
}
