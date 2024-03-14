<?php
/**
 * index.php
 *
 * Copyright (c) 2016-2020 "cubelinks" https://www.cuelinks.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.	
 *
 * This header and all notices must be kept intact.
 *
 * @author Cuelinks
 * @package cuelinks
 * @since cuelinks 1.0.2
 *
 * Plugin Name: Cuelinks - Affiliate Marketing Tool for Publishers
 * Plugin URI: https://www.cuelinks.com/affiliate-wordpress-plugin
 * Version: 1.0.2
 * Description: Cuelinks is a 2-minute Content Monetization tool for bloggers, deal site owners, coupon sites, forum owners or any publishers. We help publishers monetize their content in an effective manner by converting product links in your post into their equivalent affiliate links on-the-fly without affecting your users' experience.
 * Author: Cuelinks Technology Pvt Ltd
 * Author URI: https://www.cuelinks.com/
 */
add_action('admin_menu', 'cuelinks_script_page_create');
if (!function_exists('cuelinks_script_page_create')) {

    function cuelinks_script_page_create() {
        add_menu_page('Cuelinks Settings', 'Cuelinks Settings', 'edit_posts', 'cuelinks_settings', 'cuelinks_scriptoption_page_display', '', 74);
    }

}
register_deactivation_hook(__FILE__, 'cuelinksplugin_deactivate');
if (!function_exists('cuelinksplugin_deactivate')) {

    function cuelinksplugin_deactivate() {
        delete_option('cuelink_script_val'); //delete plugin specific options upon deactivation
    }

}
if (!function_exists('cuelinks_scriptoption_page_display')) {

    function cuelinks_scriptoption_page_display() {
        ?>  
        <div class="wrap">
            <?php
            if (isset($_POST['cuelink_script_val']) &&
        wp_verify_nonce($_POST['nonce'], 'wporg_options')) {

 
                if (count(explode(' ', sanitize_text_field($_POST['cuelink_script_val']))) > 1) {
                    echo '<div id="message" class="error notice notice-error is-dismissible"><p><strong>Please do not add any space in option value.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                } else {
                    update_option('cuelink_script_val', sanitize_text_field($_POST['cuelink_script_val']));
                    echo '<div id="message" class="updated notice notice-success is-dismissible"><p><strong>Publisher Id saved Successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                }
            }
            $cidvalue = get_option('cuelink_script_val');
            ?>
            <div class="scriptingbox">
                <div class="head"> 
                    <div class="leftside"> 
                    <?php echo '<img src="' . esc_url( plugins_url( 'images/cuelinks-logo.png', __FILE__ ) ) . '" > '; ?>

                        <span><?php  echo esc_html( __( '/ Settings', 'cuelinks' ) );?></span>
                    </div>
                    <div class="rightside">
                        <a href="<?php echo (filter_var('https://www.cuelinks.com', FILTER_VALIDATE_URL)? 'https://www.cuelinks.com' : '');?>" target="_blank"><?php  echo esc_html( __( 'Help', 'cuelinks' ) );?></a>
                    </div>
                </div>
                <p><?php  echo esc_html( __( 'Copy your Channel ID from Cuelinks.com and paste it below, you can check your Channel id', 'cuelinks' ) );?> <a href="<?php echo (filter_var('https://www.cuelinks.com/my-channels', FILTER_VALIDATE_URL)? 'https://www.cuelinks.com/my-channels' : '');?>" target="_blank"> <?php  echo esc_html( __( 'here ', 'cuelinks' ) );?></a></p>
                <form method="post">
                    <table class="form-table">
                        <tr valign="top"><th scope="row"><?php  echo esc_html( __( 'Cuelinks Status:', 'cuelinks' ) );?></th>
                            <td>
                                <?php
                                $cuelink_script_val = get_option('cuelink_script_val');
                                $cuelink_script_val = trim($cuelink_script_val);
                                $cidvalue = trim($cidvalue);
                                if ($cuelink_script_val && $cidvalue) {
                                    echo "<strong style='color:green'>Active</strong>";
                                } else {
                                    echo "<strong style='color:red'>Inactive</strong>";
                                }
                                ?>
                            </td>
                        </tr> 
                        <tr valign="top"><th scope="row"><?php  echo esc_html( __( 'Cuelinks cId:', 'cuelinks' ) );?></th>
                            <td><input required placeholder="Please Insert Value" type="text" name="cuelink_script_val" value="<?php echo $cidvalue; ?>" /><label><?php  echo esc_html( __('Required')); ?></label></td>
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wporg_options'); ?>" />
                        </tr>                                  
                    </table>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
                    </p>
                </form>
                <p><?php _e('If you have any questions or suggestions about this plugin, reach out to us at '); ?><a href="mailto:operations@cuelinks.com"><?php echo _e('operations@cuelinks.com'); ?></a></p>
                <style type="text/css">
                    div.scriptingbox div.head { clear: both; overflow: hidden; position: relative;}
                    div.scriptingbox div.head div.leftside {float: left;overflow: hidden;width: auto;}
                    div.scriptingbox div.head div.leftside > img {display: inline-block;float: left;}
                    div.scriptingbox div.head div.leftside > span { color: #7c7c7c;  display: inline-block;  float: left;  font-size: 29px;  padding: 20px;}
                    div.scriptingbox div.head div.rightside { background: #ccc;display: inline-block; float: right; padding: 5px;}
                    div.scriptingbox form .button-primary {  box-shadow: none !important;background: #30b026 !important;border: none !important; font-size: 15px !important; font-weight: bold;height: auto !important;padding: 5px 15px !important; text-shadow: none !important; text-transform: uppercase; }
                    div.scriptingbox form table.form-table tr td label {  padding-left: 20px;}
                    div.scriptingbox div.head div.rightside > a { color: #000; padding: 10px; text-decoration: none;}
                    div.scriptingbox > p {  color: #7c7c7c; font-size: 14px;}
                    div.scriptingbox a:focus .gravatar, div.scriptingbox a:focus, div.scriptingbox a:focus div.scriptingbox .media-icon img { box-shadow: none !important;}
                </style>
            </div>
        </div>      
        <?php
    }

}

// Creating the widget 
class cuelinks_cuelinkwidget extends WP_Widget {

    function __construct() {
        parent::__construct('cuelinks_cuelinkwidget', __('Cuelink Widget', 'cuelinks_cuelinkwidget_domain'), array('description' => __('Display Cuelinks Widget by adding your widget iFrame code here and multiply your earnings.', 'cuelinks_cuelinkwidget_domain'),));
    }

    // Creating widget front-end
    public function widget($args, $instance) {
        if (isset($instance) && isset($args)) {
            $title = apply_filters('widget_title', $instance['title']);
            $content = $instance['textarea'];
            echo $args['before_widget'];
            if (!empty($title))
                echo $args['before_title'] . $title . $args['after_title'];
            echo $content;
            echo $args['after_widget'];
        }
    }

    // Widget Backend 
    public function form($instance) {
        if (isset($instance)) {
            if (isset($instance['title'])) {
                $title = $instance['title'];
            } else {
                $title = __('Cuelink Widget', 'cuelinks_cuelinkwidget_domain');
            }
            $textarea = $instance['textarea'];
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Content:'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
            </p>
            <?php
        }
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['textarea'] = (!empty($new_instance['textarea']) ) ? $new_instance['textarea'] : '';
        return $instance;
    }

}

if (!function_exists('cuelinks_cuelinkwidget_load_widget')) {

// Register and load the widget
    function cuelinks_cuelinkwidget_load_widget() {
        register_widget('cuelinks_cuelinkwidget');
    }

}
add_action('widgets_init', 'cuelinks_cuelinkwidget_load_widget');


add_action('wp_footer', 'cuelinksscript', 100);

function cuelinksscript() {
    $cuelink_script_val = get_option('cuelink_script_val');
    $cuelink_script_val = trim($cuelink_script_val);
    ?>
    <!-- Cuelinks cId script Start-->
    <script type='text/javascript'>
        var cId = '<?php echo $cuelink_script_val ?>';

        (function (d, t) {
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = (document.location.protocol == 'https:' ?
                    'https://cdn0.cuelinks.com/js/' : 'http://cdn0.cuelinks.com/js/') +
                    'cuelinksv2.js';
            document.getElementsByTagName('body')[0].appendChild(s);
        }());
    </script>
    <!-- Cuelinks cId  script End-->  
    <?php
}

