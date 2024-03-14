<?php
/*
Plugin Name: Permalinks to Category/Permalinks
Plugin URI: http://orbisius.com/
Description: if you have an existing site and you want to add category to the links of your posts (e.g. by using this as permalinks /%category%/%postname%/) then you will into page not found errors. This plugin checks for such post links and redirects the visitor using 301 to the correct /Category/Permalink link.
Version: 1.0.2
Author: Svetoslav Marinov (Slavi)
Author URI: http://orbisius.com
*/

/*  Copyright 2012-2050 Svetoslav Marinov (Slavi) <slavi@orbisius.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Set up plugin
add_action('template_redirect', 'orbisius_perma2catperma');
add_action('admin_menu', 'orbisius_perma2catperma_setup_admin');
add_action('wp_footer', 'orbisius_perma2catperma_add_plugin_credits', 1000); // be the last in the footer

// when plugins are show add a settings link near my plugin for a quick access to the settings page.
add_filter('plugin_action_links', 'orbisius_perma2catperma_add_plugin_settings_link', 10, 2);

// Add the ? settings link in Plugins page very good
function orbisius_perma2catperma_add_plugin_settings_link($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $link = 'options-general.php?page=orbisius_perma2catperma_options_page';
        $link_html = "<a href='$link'>Settings</a>";
        array_unshift($links, $link_html);
	}

    return $links;
}

/**
 * @package Permalinks to Category/Permalinks
 * @since 1.0
 *
 * Searches through posts to see if any matches the REQUEST_URI.
 * Also searches tags
 */
function orbisius_perma2catperma() {
	if (!is_404()) {
		return;
	}

    // e.g. http://site.com/site
    $site_url = get_site_url();

    // i.e. no domain e.g. /site
    $wp_web_dir = preg_replace('#https?://.*?/#si', '/', $site_url);

	// /post-asfiafoijasfasf-asfalkjhflajsfasf-fajsfh -> /category/post-asfiafoijasfasf-asfalkjhflajsfasf-fajsfh
	$req_url = $_SERVER["REQUEST_URI"];
	$req_url = preg_replace('#\?.*#si', '', $req_url);
	$req_url = str_replace($wp_web_dir, '', $req_url);

    $slug = $req_url;

    // different structure. skip it
    if (!preg_match('#^/[\w-]+/?$#', $slug)) {
        return ;
    }

    // Search for a post with exact name (slig) and then redirect if one is found
    // See: http://codex.wordpress.org/Template_Tags/get_posts
    // limiting the search .. this should be fast instead of looking for all of the posts.
    $posts = get_posts(array("name" => $slug, "post_type" => "post" , 'post_status' => 'publish', 'numberposts' => 2));

    if (!empty($posts) && count($posts) == 1) {
        $post = $posts[0];
        $redir = get_permalink($post->ID); // will include the correct url IF the permalinks are setup

        wp_redirect($redir, 301);

        exit();
    }
}

/**
 * Set up administration
 *
 * @package Permalinks to Category/Permalinks
 * @since 0.1
 */
function orbisius_perma2catperma_setup_admin() {
	add_options_page( 'Permalinks to Category/Permalinks', 'Permalinks to Category/Permalinks', 'manage_options',
            'orbisius_perma2catperma_options_page', 'orbisius_perma2catperma_options_page' );
}

/**
 * Options page
 *
 * @package Permalinks to Category/Permalinks
 * @since 1.0
 */
function orbisius_perma2catperma_options_page() {
    $permalink_structure = get_option('permalink_structure');
	?>

    <div class="wrap">

        <div id="icon-options-general" class="icon32"></div>
        <h2>Permalinks to Category/Permalinks</h2>

        <div id="poststuff">

            <div id="post-body" class="metabox-holder columns-2">

                <!-- main content -->
                <div id="post-body-content">

                    <div class="meta-box-sortables ui-sortable">

                        <div class="postbox">

                            <!--<h3><span>Main Content Header</span></h3>-->
                            <div class="inside">
                                <p>Currently, the plugin does not require any configuration options.
                                    <br/>
                                    <br/> It will automatically correct and redirect your links e.g.
                                    <strong>/link-to-your-post/</strong> to <strong>/category/link-to-your-post/</strong></p>
                                
                                <?php if ($permalink_structure != '/%category%/%postname%/') : ?>
                                    <p class="error-message">Your current permalink settings do not match the expected format!</p>
                                    <p>Note: Please make sure that your permalinks (Settings &gt; Permalinks) are set to <strong>/%category%/%postname%/</strong></p>
                                    Current permalinks settings: <h2><?php echo $permalink_structure; ?></h2>
                                <?php endif; ?>
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <?php orbisius_perma2catperma_util::output_orb_widget('author'); ?>
                    </div> <!-- .meta-box-sortables .ui-sortable -->

                </div> <!-- post-body-content -->

                <!-- sidebar -->
                <div id="postbox-container-1" class="postbox-container">

                    <div class="meta-box-sortables">

                        <div class="postbox">
                            <h3><span>Hire Us</span></h3>
                            <div class="inside">
                                Hire us to create a plugin/web/mobile app for your business.
                                <br/><a href="http://orbisius.com/page/free-quote/?utm_source=orbisius-child-theme-creator&utm_medium=plugin-settings&utm_campaign=product"
                                   title="If you want a custom web/mobile app/plugin developed contact us. This opens in a new window/tab"
                                    class="button-primary" target="_blank">Get a Free Quote</a>
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <div class="postbox">
                            <h3><span>Newsletter</span></h3>
                            <div class="inside">
                                <!-- Begin MailChimp Signup Form -->
                                <div id="mc_embed_signup">
                                    <?php
                                        $current_user = wp_get_current_user();
                                        $email = empty($current_user->user_email) ? '' : $current_user->user_email;
                                    ?>

                                    <form action="http://WebWeb.us2.list-manage.com/subscribe/post?u=005070a78d0e52a7b567e96df&amp;id=1b83cd2093" method="post"
                                          id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
                                        <input type="hidden" value="settings" name="SRC2" />
                                        <input type="hidden" value="orbisius-child-theme-creator" name="SRC" />

                                        <span>Get notified about cool plugins we release</span>
                                        <!--<div class="indicates-required"><span class="app_asterisk">*</span> indicates required
                                        </div>-->
                                        <div class="mc-field-group">
                                            <label for="mce-EMAIL">Email <span class="app_asterisk">*</span></label>
                                            <input type="email" value="<?php echo esc_attr($email); ?>" name="EMAIL" class="required email" id="mce-EMAIL">
                                        </div>
                                        <div id="mce-responses" class="clear">
                                            <div class="response" id="mce-error-response" style="display:none"></div>
                                            <div class="response" id="mce-success-response" style="display:none"></div>
                                        </div>	<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button-primary"></div>
                                    </form>
                                </div>
                                <!--End mc_embed_signup-->
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <?php orbisius_perma2catperma_util::output_orb_widget(); ?>

                        <?php
                                        $plugin_data = get_plugin_data(__FILE__);
                                        $product_name = trim($plugin_data['Name']);
                                        $product_page = trim($plugin_data['PluginURI']);
                                        $product_descr = trim($plugin_data['Description']);
                                        $product_descr_short = substr($product_descr, 0, 50) . '...';

                                        $base_name_slug = basename(__FILE__);
                                        $base_name_slug = str_replace('.php', '', $base_name_slug);
                                        $product_page .= (strpos($product_page, '?') === false) ? '?' : '&';
                                        $product_page .= "utm_source=$base_name_slug&utm_medium=plugin-settings&utm_campaign=product";

                                        $product_page_tweet_link = $product_page;
                                        $product_page_tweet_link = str_replace('plugin-settings', 'tweet', $product_page_tweet_link);
                                    ?>

                        <div class="postbox">
                            <div class="inside">
                                <!-- Twitter: code -->
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                <!-- /Twitter: code -->

                                <!-- Twitter: Orbisius_Follow:js -->
                                    <a href="https://twitter.com/orbisius" class="twitter-follow-button"
                                       data-align="right" data-show-count="false">Follow @orbisius</a>
                                <!-- /Twitter: Orbisius_Follow:js -->

                                &nbsp;

                                <!-- Twitter: Tweet:js -->
                                <a href="https://twitter.com/share" class="twitter-share-button"
                                   data-lang="en" data-text="Checkout Orbisius Child Theme Creator #WordPress #plugin.Create Child Themes in Seconds"
                                   data-count="none" data-via="orbisius" data-related="orbisius"
                                   data-url="<?php echo $product_page_tweet_link;?>">Tweet</a>
                                <!-- /Twitter: Tweet:js -->


                                <br/>
                                 <a href="<?php echo $product_page; ?>" target="_blank" title="[new window]">Product Page</a>
                                    |
                                <span>Support: <a href="http://club.orbisius.com/forums/forum/community-support-forum/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-creator&utm_medium=plugin-settings&utm_campaign=product"
                                    target="_blank" title="[new window]">Forums</a>

                                    <!--|
                                     <a href="http://docs.google.com/viewer?url=https%3A%2F%2Fdl.dropboxusercontent.com%2Fs%2Fwz83vm9841lz3o9%2FOrbisius_LikeGate_Documentation.pdf" target="_blank">Documentation</a>
                                    -->
                                </span>
                            </div>

                            <h3><span>Troubleshooting</span></h3>
                            <div class="inside">
                                If your site becomes broken because of a child theme check:
                                <a href="http://club.orbisius.com/products/wordpress-plugins/orbisius-theme-fixer/?utm_source=orbisius-child-theme-creator&utm_medium=settings_troubleshooting&utm_campaign=product"
                                target="_blank" title="[new window]">Orbisius Theme Fixer</a>
                            </div>
                        </div> <!-- .postbox -->


                        <div class="postbox"> <!-- quick-contact -->
                            <?php
                            $current_user = wp_get_current_user();
                            $email = empty($current_user->user_email) ? '' : $current_user->user_email;
                            $quick_form_action = is_ssl()
                                    ? 'https://ssl.orbisius.com/apps/quick-contact/'
                                    : 'http://apps.orbisius.com/quick-contact/';

                            if (!empty($_SERVER['DEV_ENV'])) {
                                $quick_form_action = 'http://localhost/projects/quick-contact/';
                            }
                            ?>
                            <script>
                                var octc_quick_contact = {
                                    validate_form : function () {
                                        try {
                                            var msg = jQuery('#octc_msg').val().trim();
                                            var email = jQuery('#octc_email').val().trim();

                                            email = email.replace(/\s+/, '');
                                            email = email.replace(/\.+/, '.');
                                            email = email.replace(/\@+/, '@');

                                            if ( msg == '' ) {
                                                alert('Enter your message.');
                                                jQuery('#octc_msg').focus().val(msg).css('border', '1px solid red');
                                                return false;
                                            } else {
                                                // all is good clear borders
                                                jQuery('#octc_msg').css('border', '');
                                            }

                                            if ( email == '' || email.indexOf('@') <= 2 || email.indexOf('.') == -1) {
                                                alert('Enter your email and make sure it is valid.');
                                                jQuery('#octc_email').focus().val(email).css('border', '1px solid red');
                                                return false;
                                            } else {
                                                // all is good clear borders
                                                jQuery('#octc_email').css('border', '');
                                            }

                                            return true;
                                        } catch(e) {};
                                    }
                                };
                            </script>
                            <h3><span>Quick Question or Suggestion</span></h3>
                            <div class="inside">
                                <div>
                                    <form method="post" action="<?php echo $quick_form_action; ?>" target="_blank">
                                        <?php
                                            global $wp_version;
											$plugin_data = get_plugin_data(__FILE__);

                                            $hidden_data = array(
                                                'site_url' => site_url(),
                                                'wp_ver' => $wp_version,
                                                'first_name' => $current_user->first_name,
                                                'last_name' => $current_user->last_name,
                                                'product_name' => $plugin_data['Name'],
                                                'product_ver' => $plugin_data['Version'],
                                                'woocommerce_ver' => defined('WOOCOMMERCE_VERSION') ? WOOCOMMERCE_VERSION : 'n/a',
                                            );
                                            $hid_data = http_build_query($hidden_data);
                                            echo "<input type='hidden' name='data[sys_info]' value='$hid_data' />\n";
                                        ?>
                                        <textarea class="widefat" id='octc_msg' name='data[msg]' required="required"></textarea>
                                        <br/>Your Email: <input type="text" class=""
                                               id="octc_email" name='data[sender_email]' placeholder="Email" required="required"
                                               value="<?php echo esc_attr($email); ?>"
                                               />
                                        <br/><input type="submit" class="button-primary" value="<?php _e('Send Feedback') ?>"
                                                    onclick="return octc_quick_contact.validate_form();" />
                                        <br/>
                                        What data will be sent
                                        <a href='javascript:void(0);'
                                            onclick='jQuery(".octc_data_to_be_sent").toggle();'>(show/hide)</a>
                                        <div class="hide app_hide octc_data_to_be_sent">
                                            <textarea class="widefat" rows="4" readonly="readonly" disabled="disabled"><?php
                                            foreach ($hidden_data as $key => $val) {
                                                if (is_array($val)) {
                                                    $val = var_export($val, 1);
                                                }

                                                echo "$key: $val\n";
                                            }
                                            ?></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div> <!-- .inside -->
                         </div> <!-- .postbox --> <!-- /quick-contact -->

                    </div> <!-- .meta-box-sortables -->

                </div> <!-- #postbox-container-1 .postbox-container sidebar -->

            </div> <!-- #post-body .metabox-holder .columns-2 -->

            <br class="clear">
        </div> <!-- #poststuff -->

    </div> <!-- .wrap -->

	<?php
}

/**
* adds some HTML comments in the page so people would know that this plugin powers their site.
*/
function orbisius_perma2catperma_add_plugin_credits() {
    printf(PHP_EOL . PHP_EOL . '<!-- ' . PHP_EOL . ' Powered by Permalinks to Category/Permalinks Plugin | Author URL: http://orbisius.com ' . PHP_EOL . '-->' . PHP_EOL . PHP_EOL);
}

/**
 * Util funcs
 */
class orbisius_perma2catperma_util {
    /**
     * Loads news from Club Orbsius Site.
     * <?php orbisius_perma2catperma_util::output_orb_widget(); ?>
     */
    public static function output_orb_widget($obj = '', $return = 0) {
        $buff = '';
        ?>
        <!-- Orbisius JS Widget -->
            <?php
                $naked_domain = !empty($_SERVER['DEV_ENV']) ? 'orbclub.com.clients.com' : 'club.orbisius.com';

                if (!empty($_SERVER['DEV_ENV']) && is_ssl()) {
                    $naked_domain = 'ssl.orbisius.com/club';
                }

				// obj could be 'author'
                $obj = empty($obj) ? str_replace('.php', '', basename(__FILE__)) : sanitize_title($obj);
                $obj_id = 'orb_widget_' . sha1($obj);

                $params = '?' . http_build_query(array('p' => $obj, 't' => $obj_id, 'layout' => 'plugin', ));
                $buff .= "<div id='$obj_id' class='$obj_id orbisius_ext_content'></div>\n";
                $buff .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://$naked_domain/wpu/widget/$params';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'orbsius-js-$obj_id');</script>";
            ?>
            <!-- /Orbisius JS Widget -->
        <?php

        if ($return) {
            return $buff;
        } else {
            echo $buff;
        }
    }
}