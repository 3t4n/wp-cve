<?php
/*
Plugin Name: Linkable Title Html and Php Widget by PepLamb
Plugin URI: http://peplamb.com/linkable-title-html-and-php-widget/
Description: <a href="http://peplamb.com/linkable-title-html-and-php-widget/" target="_blank">Linkable Title Html and Php Widget</a> by <a href="http://peplamb.com/linkable-title-html-and-php-widget/" target="_blank">PepLamb</a>! Using this plugin you may have Text, HTML, Javascript, Flash and/or Php as content in this widget with linkable widget titles, so this is a plus compared to the default wordpress' text widget. This plugin is a personal open source project. Started in 2009, I have put considerable amount of hours adding, refining, tuning, fixing, maintaining the plugin with the goal to make it useful and add to Wordpress community. I need your help to achieve this. <a href='https://pledgie.com/campaigns/23211' target="_blank">Donate</a>
Version: 1.2.6
Author: PepLamb
Author URI: http://peplamb.com/
*/
/*
    Copyright 2009-2013  PepLamb  (email : peplamb@gmail.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class LinkableTitleHtmlAndPhpWidget extends WP_Widget {

    function LinkableTitleHtmlAndPhpWidget() {
        $widget_ops = array('classname' => 'widget_text', 'description' => __('Linkable Title Html and Php Widget by PepLamb'));
        $control_ops = array('width' => 400, 'height' => 350);
        $this->WP_Widget('LinkableTitleHtmlAndPhpWidget', __('Linkable Title Html and Php Widget'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
        $titleUrl = apply_filters('widget_title', empty($instance['titleUrl']) ? '' : $instance['titleUrl'], $instance);
        $titleColor = apply_filters('widget_title', empty($instance['titleColor']) ? '' : $instance['titleColor'], $instance);
        $forceTitleUnderline = $instance['forceTitleUnderline'] ? '1' : '0';
        $removeTextContentDivTag = $instance['removeTextContentDivTag'] ? '1' : '0';
        $newWindow = $instance['newWindow'] ? '1' : '0';
        $text = apply_filters( 'widget_text', $instance['text'], $instance );

        $titleStyle = ($forceTitleUnderline == '1' ? "text-decoration: underline !important;" : "");
        $titleStyle .= (strlen($titleColor) > 0 ? "color: {$titleColor};" : "");
        if(strlen($titleStyle) > 0) {
            $titleStyle = ' style="' . $titleStyle . '"';
        }

        echo $before_widget;
        if( $titleUrl && $title )
            $title = '<a href="' . $titleUrl . '"' . ($newWindow == '1'?' target="_blank"':'') . $titleStyle . ' title="'.$title.'">'.$title.'</a>';
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
        <?php echo ($removeTextContentDivTag == '1' ? '' : '<div class="textwidget">');?><?php if($instance['filter']) { ob_start(); eval("?>$text<?php "); $output = ob_get_contents(); ob_end_clean(); echo wpautop($output); } else eval("?>".$text."<?php "); ?><?php echo ($removeTextContentDivTag == '1' ? '' : '</div>');?>
        <?php
        echo $after_widget;
    }
    
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
        $instance['titleColor'] = $new_instance['titleColor'] ? $new_instance['titleColor'] : "";
        $instance['forceTitleUnderline'] = $new_instance['forceTitleUnderline'] ? 1 : 0;
        $instance['removeTextContentDivTag'] = $new_instance['removeTextContentDivTag'] ? 1 : 0;
        $instance['newWindow'] = $new_instance['newWindow'] ? 1 : 0;
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = wp_filter_post_kses( $new_instance['text'] );
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'titleUrl' => '', 'text' => '' ) );
        $title = strip_tags($instance['title']);
        $titleUrl = strip_tags($instance['titleUrl']);
        $titleColor = strip_tags($instance['titleColor']);
        $forceTitleUnderline = $instance['forceTitleUnderline'] ? 'checked="checked"' : '';
        $removeTextContentDivTag = $instance['removeTextContentDivTag'] ? 'checked="checked"' : '';
        $newWindow = $instance['newWindow'] ? 'checked="checked"' : '';
        $text = format_to_edit($instance['text']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('titleUrl'); ?>"><?php _e('Title Url:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo esc_attr($titleUrl); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('titleColor'); ?>"><?php _e('Title Color:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('titleColor'); ?>" name="<?php echo $this->get_field_name('titleColor'); ?>" type="text" value="<?php echo esc_attr($titleColor); ?>" /></p>
        <p><input class="checkbox" type="checkbox" <?php echo $forceTitleUnderline; ?> id="<?php echo $this->get_field_id('forceTitleUnderline'); ?>" name="<?php echo $this->get_field_name('forceTitleUnderline'); ?>" />
        <label for="<?php echo $this->get_field_id('forceTitleUnderline'); ?>"><?php _e('Force widget title underline'); ?></label></p>
        <p><input class="checkbox" type="checkbox" <?php echo $removeTextContentDivTag; ?> id="<?php echo $this->get_field_id('removeTextContentDivTag'); ?>" name="<?php echo $this->get_field_name('removeTextContentDivTag'); ?>" />
        <label for="<?php echo $this->get_field_id('removeTextContentDivTag'); ?>"><?php _e('Remove Text Content Div Tag'); ?></label></p>
        <p><input class="checkbox" type="checkbox" <?php echo $newWindow; ?> id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" />
        <label for="<?php echo $this->get_field_id('newWindow'); ?>"><?php _e('Open the link/url in a new window'); ?></label></p>
        
        <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text, Html, Javascript, Flash and/or Php:'); ?></label>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

        <p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs.'); ?></label></p>
          <p>
            <center>
                This plugin is a personal open source project. Started in 2009, I have put considerable amount of hours adding, refining, tuning, fixing, maintaining the plugin with the goal to make it useful and add to Wordpress community. I need your help to achieve this.<br />
                <a href='https://pledgie.com/campaigns/23211' target="_blank"><img alt='Click here to lend your support to: This Plugin and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/23211.png?skin_name=chrome' border='0' ></a>
            </center>
            <div id="sideblock" style="float:left;width:220px;margin-left:10px;">
              <h2>Information</h2>
              <div id="dbx-content" style="text-decoration:none;">
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/home.png"><a target="_blank" style="text-decoration:none;" href="http://peplamb.com/linkable-title-html-and-php-widget/"> Plugin Home</a><br />
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/rate.png"><a target="_blank" style="text-decoration:none;" href="http://wordpress.org/extend/plugins/linkable-title-html-and-php-widget/"> Rate this plugin</a><br />
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/help.png"><a target="_blank" style="text-decoration:none;" href="http://peplamb.com/linkable-title-html-and-php-widget/#respond"> Support and Help</a><br />
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/twit.png"><a target="_blank" style="text-decoration:none;" href="http://twitter.com/peplamb/">Follow me on Twitter</a><br />
                <strong>More plugins by PepLamb:</strong>
                <ul>
                    <li><a target="_blank" href="http://peplamb.com/google-analytics-visits/">Google Analytics Visits</a></li>
                    <li><a target="_blank" href="http://peplamb.com/custom-field-cookie/">Custom Field Cookie</a></li>
                </ul>
              </div>
              <div style="clear:both"></div>
              <?php //LinkableTitleHtmlAndPhpWidget_plugin_print_facebook_like_button(); ?>
            </div>
          </p><!-- Linkable Title Html and Php Widget (by PepLamb, http://PepLamb.com) -->
        <?php
    }
}
function LinkableTitleHtmlAndPhpWidgetInit() {
    register_widget('LinkableTitleHtmlAndPhpWidget');
}
add_action('widgets_init', 'LinkableTitleHtmlAndPhpWidgetInit');

/**
 *
 *@since 1.2.2
 */
if(isset($_POST['Submit']) && isset($_POST['LinkableTitleHtmlAndPhpWidget_save'])) {
    if($_POST['Submit'] == "Save" && $_POST['LinkableTitleHtmlAndPhpWidget_save'] == "Save") {
        update_option('LinkableTitleHtmlAndPhpWidget_disableUpdateNag' ,           $_POST['LinkableTitleHtmlAndPhpWidget_disableUpdateNag']);

        add_action( 'admin_notices', 'LinkableTitleHtmlAndPhpWidget_options_saved_notice', 9 );
    }
}
/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_options_saved_notice() {
    echo "<div class='updated'><p>Linkable Title Html And Php Widget options saved.</p></div>";
}

/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_donate_url() {
    return "https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TV873GDVX3MQC&lc=US&item_name=PepLamb&item_number=Linkable%20Title%20Html%20And%20Php%20Widget&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted";
}
/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_donate() {
    ?>
    <a href='https://pledgie.com/campaigns/23211'><img alt='Click here to lend your support to: Linkable Title Html and Php Widget and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/23211.png?skin_name=chrome' border='0' ></a>
    <?php
}
/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_currPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_activate() {
    if(!get_option('LinkableTitleHtmlAndPhpWidget_disableUpdateNag'))
        add_option('LinkableTitleHtmlAndPhpWidget_disableUpdateNag', 'yes');
}
register_activation_hook( __FILE__, 'LinkableTitleHtmlAndPhpWidget_activate' );
/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_deactivate() {
}
register_deactivation_hook( __FILE__, 'LinkableTitleHtmlAndPhpWidget_deactivate' );

/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_options() {
    ?>
<div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div>
    <h2 style="margin-top:0">Linkable Title Html And Php Widget <?php echo LinkableTitleHtmlAndPhpWidget_plugin_get_version(); ?> Options</h2>
    <p>
        <?php
        LinkableTitleHtmlAndPhpWidget_plugin_print_facebook_like_button();
        echo " ";
        LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_follow_button();
        echo " ";
        LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_share_a_link_button();
        echo " ";
        LinkableTitleHtmlAndPhpWidget_plugin_print_google_plus_1_button();
        ?>
    </p>

    <div style="width: 620px;">
        <div style="float:left;background-color:white;padding: 10px 10px 10px 10px;margin-right:15px;border: 1px solid #ddd;">
            <div style="width:300px;">
                <h3>Donate</h3>
                <p>This plugin is a personal open source project. Started in 2009, I have put considerable amount of hours adding, refining, tuning, fixing, maintaining the plugin with the goal to make it useful and add to Wordpress community. I need your help to achieve this.</p>
                <p><a href='https://pledgie.com/campaigns/23211' target="_blank"><img alt='Click here to lend your support to: This Plugin and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/23211.png?skin_name=chrome' border='0' ></a></p>
            </div>
        </div>
        <div id="sideblock" style="float:right;width:270px;margin-left:10px;">
            <h2 style="margin-top: -20px;">Information</h2>
            <div id="dbx-content" style="text-decoration:none;">
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/home.png" alt="Home Image"><a target="_blank" style="text-decoration:none;" href="http://peplamb.com/linkable-title-html-and-php-widget/">Linkable Title Html And Php Widget Home</a><br />
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/rate.png" alt="Rate Image"><a target="_blank" style="text-decoration:none;" href="http://wordpress.org/extend/plugins/linkable-title-html-and-php-widget/">Rate this plugin</a><br />
                <img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/help.png" alt="Help Image"><a target="_blank" style="text-decoration:none;" href="http://peplamb.com/linkable-title-html-and-php-widget/#respond">Support and Help</a><br />
                <br />
                <!--<img src="<?php echo WP_PLUGIN_URL; ?>/linkable-title-html-and-php-widget/images/twit.png" alt="Twitter Image"><a target="_blank" style="text-decoration:none;" href="http://twitter.com/peplamb/"> Follow updates on Twitter</a><br />-->
                <?php
                LinkableTitleHtmlAndPhpWidget_plugin_print_facebook_like_button();
                echo " ";
                LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_follow_button();
                echo " ";
                LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_share_a_link_button();
                echo " ";
                LinkableTitleHtmlAndPhpWidget_plugin_print_google_plus_1_button();
                echo "<br />";
                ?>
                <strong>More plugins by PepLamb:</strong>
                <ul>
                    <li><a target="_blank" href="http://peplamb.com/google-analytics-visits/">Google Analytics Visits</a></li>
                    <li><a target="_blank" href="http://peplamb.com/custom-field-cookie/">Custom Field Cookie</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>

    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><label for="LinkableTitleHtmlAndPhpWidget_disableUpdateNag">Disable Update Nag?</label></th>
                <td>
                    <fieldset id="LinkableTitleHtmlAndPhpWidget_disableUpdateNag"><legend class="hidden">Disable Update Nag?</legend>
                        <input type="radio" <?php echo get_option('LinkableTitleHtmlAndPhpWidget_disableUpdateNag')=='yes'         ?'checked="checked"':''; ?> value="yes"         name="LinkableTitleHtmlAndPhpWidget_disableUpdateNag"/> Yes
                        <input type="radio" <?php echo get_option('LinkableTitleHtmlAndPhpWidget_disableUpdateNag')=='no'          ?'checked="checked"':''; ?> value="no"          name="LinkableTitleHtmlAndPhpWidget_disableUpdateNag"/> No
                    </fieldset>
                    <span class="setting-description">Disabling update nag will no longer notify you of available updates via update nag.</span>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="hidden" value="Save" name="LinkableTitleHtmlAndPhpWidget_save"/>
            <input type="submit" value="Save" class="button-primary" name="Submit"/>
        </p>
    </form>
</div>
<?php
}
/**
 *
 *@since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_menu() {
    add_options_page('Linkable Title Html And Php Widget', 'Linkable Title Html And Php Widget', 8, __FILE__, 'LinkableTitleHtmlAndPhpWidget_options');
}
add_action('admin_menu', 'LinkableTitleHtmlAndPhpWidget_menu');


/**
 * This function gets the present plugin version.
 *
 * @since 1.0.9
 */
function LinkableTitleHtmlAndPhpWidget_plugin_get_version() {
    if ( ! function_exists( 'get_plugins' ) )
       require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Version'];
}
/**
 * This function gets the plugin name.
 *
 * @since 1.0.9
 */
function LinkableTitleHtmlAndPhpWidget_plugin_get_plugin_name() {
    if ( ! function_exists( 'get_plugins' ) )
       require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Name'];
}
/**
 * This function gets the plugin uri.
 *
 * @since 1.0.9
 */
function LinkableTitleHtmlAndPhpWidget_plugin_get_plugin_url() {
    if ( ! function_exists( 'get_plugins' ) )
       require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['PluginURI'];
}
/**
 * This function prints facebook like button.
 *
 * @since 1.1.3
 */
function LinkableTitleHtmlAndPhpWidget_plugin_print_facebook_like_button( $echo = true ) {
    $slug = "linkable-title-html-and-php-widget";

    $facebook_like = sprintf( __('<div id="fb-root"></div><script type="text/javascript">(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "http://connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>', $slug)) .
        sprintf( __('<div class="fb-like" data-href="http://peplamb.com/%s/" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>', $slug), $slug);

    if($echo) {
        printf( $facebook_like );
    }
    else {
        return $facebook_like;
    }
}

/**
 * This function prints twitter_share_a_link button.
 *
 * @since 1.2.3
 */
function LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_share_a_link_button( $echo = true ) {
    $slug = "linkable-title-html-and-php-widget";

    $twitter_share_a_link = sprintf( __('<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://peplamb.com/%s/" data-via="peplamb" data-related="peplamb" data-hashtags="peplamb">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>', $slug), $slug);

    if($echo) {
        printf( $twitter_share_a_link );
    }
    else {
        return $twitter_share_a_link;
    }
}

/**
 * This function prints google_plus_1 button.
 *
 * @since 1.2.3
 */
function LinkableTitleHtmlAndPhpWidget_plugin_print_google_plus_1_button( $echo = true ) {
    $slug = "linkable-title-html-and-php-widget";

    $google_plus_1 = sprintf( __('<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><div class="g-plusone" data-size="medium" data-href="peplamb.com/%s/"></div>', $slug), $slug);

    if($echo) {
        printf( $google_plus_1 );
    }
    else {
        return $google_plus_1;
    }
}

/**
 * This function prints twitter_follow button.
 *
 * @since 1.2.3
 */
function LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_follow_button( $echo = true ) {
    $slug = "linkable-title-html-and-php-widget";

    $twitter_follow = sprintf( __('<a href="https://twitter.com/peplamb" class="twitter-follow-button" data-show-count="false">Follow @peplamb</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>', $slug));

    if($echo) {
        printf( $twitter_follow );
    }
    else {
        return $twitter_follow;
    }
}

/**
 * This function displays the update nag at the top of the
 * dashboard if there is an plugin update available.
 *
 * @since 1.0.9
 */
function LinkableTitleHtmlAndPhpWidget_update_nag() {

    if(get_option('LinkableTitleHtmlAndPhpWidget_disableUpdateNag') == 'yes') {
        return false;
    }
    if(isset($_GET["action"]) && $_GET["action"] == "upgrade-plugin") {
        return false;
    }

    $slug = "linkable-title-html-and-php-widget";
    $file = "$slug/$slug.php";

    if(!function_exists('plugins_api'))
        include(ABSPATH . "wp-admin/includes/plugin-install.php");

    $info = plugins_api('plugin_information', array('slug' => $slug ));

    if ( !current_user_can('update_plugins') )
        return false;
    if ( stristr(trim($info->version), trim(LinkableTitleHtmlAndPhpWidget_plugin_get_version())) )
        return false;

    $plugin_name = LinkableTitleHtmlAndPhpWidget_plugin_get_plugin_name();
    $plugin_url = LinkableTitleHtmlAndPhpWidget_plugin_get_plugin_url();

    if(function_exists('self_admin_url')) {
        $update_url = wp_nonce_url( self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
    }
    else {// to support wp version < 3.1.0
        $update_url = wp_nonce_url( get_bloginfo('wpurl')."/wp-admin/".('update.php?action=upgrade-plugin&plugin=') . $file, 'upgrade-plugin_' . $file);
    }
    $donate_url = LinkableTitleHtmlAndPhpWidget_donate_url();

//    echo '<div id="update-nag">';
//    echo "<strong>$plugin_name</strong> ";
//    LinkableTitleHtmlAndPhpWidget_plugin_print_facebook_like_button();
//    echo "<br />";
//    printf( __('<a href="%s" target="_blank">%s %s</a> is available! <a href="%s">Please update now</a>. Please consider <a href="%s"><strong>donating</strong></a> to keep me going, Thanks muchly!', $slug), $plugin_url, $plugin_name, $info->version, $update_url, $donate_url );
//    echo '</div>';
    echo '<div id="update-nag">';
    printf( __('<a href="%s" target="_blank">%s %s</a> is available! <a href="%s">Please update now</a>. Please consider <a href="%s"><strong>donating</strong></a> to keep me going, thank you!', $slug), $plugin_url, $plugin_name, $info->version, $update_url, $donate_url );
    echo "<br />";
    printf( __("%s", $slug), LinkableTitleHtmlAndPhpWidget_donate());
    echo "<br />";
    LinkableTitleHtmlAndPhpWidget_plugin_print_facebook_like_button();
    echo " ";
    LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_follow_button();
    echo " ";
    LinkableTitleHtmlAndPhpWidget_plugin_print_twitter_share_a_link_button();
    echo " ";
    LinkableTitleHtmlAndPhpWidget_plugin_print_google_plus_1_button();
    echo '</div>';

    return true;
}
add_action('admin_notices', 'LinkableTitleHtmlAndPhpWidget_update_nag');

/**
 * Add FAQ and support information.
 *
 * @since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_filter_plugin_links($links, $file) {
    if ( $file == plugin_basename(__FILE__) ) {
        $links[] = '<a href="http://peplamb.com/linkable-title-html-and-php-widget/">' . __('FAQ', 'linkable-title-html-and-php-widget') . '</a>';
        $links[] = '<a href="http://peplamb.com/linkable-title-html-and-php-widget/">' . __('Support', 'linkable-title-html-and-php-widget') . '</a>';
        $links[] = '<a href="http://peplamb.com/donate/">' . __('Donate', 'linkable-title-html-and-php-widget') . '</a>';
    }

    return $links;
}
add_filter('plugin_row_meta', 'LinkableTitleHtmlAndPhpWidget_filter_plugin_links', 10, 2);

/**
 * Add settings option.
 *
 * @since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_filter_plugin_actions($links) {
    $new_links = array();
    $slug = "linkable-title-html-and-php-widget";
    if(function_exists('self_admin_url')) {
        $new_links[] = '<a href="'.self_admin_url('options-general.php?page='.$slug.'/'.$slug.'.php').'">' . __('Settings', $slug) . '</a>';
    }
    else {// to support wp version < 3.1.0
        $new_links[] = '<a href="'.get_bloginfo('wpurl')."/wp-admin/".('options-general.php?page='.$slug.'/'.$slug.'.php').'">' . __('Settings', $slug) . '</a>';
    }

    return array_merge($new_links, $links);
}
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'LinkableTitleHtmlAndPhpWidget_filter_plugin_actions');

/**
 * Footer hook.
 *
 * @since 1.2.2
 */
function LinkableTitleHtmlAndPhpWidget_footer_hook() {
    echo '<!-- This website uses Linkable Title Html And Php Widget v' . LinkableTitleHtmlAndPhpWidget_plugin_get_version() . ' Wordpress plugin developed by PepLamb (PepLamb.com) -->';
}
add_action('wp_footer', 'LinkableTitleHtmlAndPhpWidget_footer_hook');
?>