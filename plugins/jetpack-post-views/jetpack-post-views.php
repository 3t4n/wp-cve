<?php
/*
 * Plugin Name: Jetpack Post Views
 * Plugin URI: http://blog.sklambert.com/jetpack-post-views/
 * Description: Adds a widget that displays your most popular posts using Jetpack stats. <strong>NOTE:</strong> If the plugin does not work, visit the <a href="options-general.php?page=jetpack_post_views">Settings</a> page to enter a WordPress API Key.
 * Author: Steven Lambert
 * Version: 1.1.0
 * Author URI: http://sklambert.com
 * License: GPL2+
 */

/*  Copyright 2013  Steven Lambert  (email : steven@sklambert.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Define plugin version
if (!defined('JETPACK_POST_VIEWS_VERSION_KEY'))
    define('JETPACK_POST_VIEWS_VERSION_KEY', 'jetpack-post-views_version');

if (!defined('JETPACK_POST_VIEWS_VERSION_NUM'))
    define('JETPACK_POST_VIEWS_VERSION_NUM', '1.1.0');

add_option(JETPACK_POST_VIEWS_VERSION_KEY, JETPACK_POST_VIEWS_VERSION_NUM);

/**
 * Jetpack Post Views
 *
 * Queries the Jetpack API and adds a custom post_meta 'jetpack-post-views' that holds the total views of a
 * post as listed on Jetpack. Adds a widget to display the top posts for a site.
 */
class Jetpack_Post_Views extends WP_Widget {

    // Private variables
    var $version = '1.1.0';
    var $apiUrl  = 'http://stats.wordpress.com/csv.php';
    var $apiArgs = array(
        'table'     => 'postviews',
        'days'      => -1,
        'limit'     => -1,
        'summarize' =>  1,
        'format'    => 'json'
    );
    var $interval = array(
        'Unlimited' =>  -1,
        'Day'       =>   1,
        'Week'      =>   7,
        'Month'     =>  30,
        'Year'      => 365
    );

    // Update test data
    var $updateMessages = array();
    var $DEBUG          = false;

    /* CONSTRUCTOR */
    function __construct() {

        // Admin hooks
        add_action( 'init',                                  array( &$this, 'load_language_files' ) );
        add_action( 'admin_init',                            array( &$this, 'register_setting' ) );
        add_action( 'admin_menu',                            array( &$this, 'register_settings_page' ) );
        add_action( 'manage_posts_custom_column',            array( &$this, 'add_post_views_column_content' ), 10, 2);
        add_filter( 'plugin_action_links',                   array( &$this, 'settings_link' ), 10, 2 );
        add_filter( 'manage_posts_columns',                  array( &$this, 'add_post_views_column' ) );
        add_filter( 'manage_edit-post_sortable_columns',     array( &$this, 'post_views_column_sort') );
        add_filter( 'request',                               array( &$this, 'post_views_column_orderby' ) );

        // Scheduled hooks
        add_action( 'jetpack_post_views_scheduled_update',   array( &$this, 'get_post_views' ) );

        // Post hooks
        add_action( 'publish_post',                          array( &$this, 'add_jetpack_meta' ) );

        // Query the database for the blog_id
        global $wpdb;
        $options_table = $wpdb->base_prefix."options";
        $stats_options = $wpdb->get_var( "SELECT option_value
                                          FROM   $options_table
                                          WHERE  option_name = 'stats_options'" );
        $stats = unserialize($stats_options);

        // Set blog_id
        if ( $stats ) {
            $this->blog_id = $stats['blog_id'];
        }
        else { // Jetpack stats unavailable

            // Try another possibility to get the blog_id
            $jetpack_options = $wpdb->get_var( "SELECT option_value
                                                FROM   $options_table
                                                WHERE  option_name = 'jetpack_options'" );
            $jetpack = unserialize($jetpack_options);

            if ( $jetpack ) {
                $this->blog_id = $jetpack['id'];
            }
            else { // Jetpack stats really unavailable
                $this->blog_id = -1;
            }
        }

        // Get the api key if it already exists
        $api_key = get_option( 'jetpack_post_views_wp_api_key' ) != "" ? get_option( 'jetpack_post_views_wp_api_key' ) : "";

        // Default settings
        $this->defaultsettings = (array) apply_filters( 'jetpack_post_views_defaultsettings', array(
            'version'             => get_option(JETPACK_POST_VIEWS_VERSION_KEY),
            'api_key'             => $api_key,
            'blog_id'             => $this->blog_id,
            'blog_uri'            => home_url( '/' ),
            'changed'             => 0,
            'connect_blog_id'     => 0,
            'connect_blog_uri'    => 0,
            'display_total_views' => "",
            'use_stats_get_csv'   => "on",
            'use_blog_uri'        => "on"
        ) );

        // Create the settings array by merging the user's settings and the defaults
        $usersettings = (array) get_option('jetpack_post_views_settings');
        $this->settings = wp_parse_args( $usersettings, $this->defaultsettings );

        // Controls and options
        $widget_ops = array(
            'classname'   => 'jetpack-post-views',
            'description' => __( 'Your site\'s most popular posts using Jetpack stats', 'jetpack-post-views')
        );
        $control_ops = array(
            'id_base' => 'jetpack-post-views-widget'
        );

        // Set widget information
        $this->WP_Widget( 'jetpack-post-views-widget', __( 'Jetpack Post Views Widget', 'jetpack-post-views'), $widget_ops, $control_ops );
    }

    /* ADD LANGUGE FILES */
    function load_language_files() {
        load_plugin_textdomain('jetpack-post-views', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /* REGISTER SETTINGS PAGE */
    function register_settings_page() {
        add_options_page( __( 'Jetpack Post Views Settings', 'jetpack-post-views' ), __( 'Jetpack Post Views', 'jetpack-post-views' ), 'manage_options', 'jetpack_post_views', array( &$this, 'settings_page' ) );
    }

    /* REGISTER PLUGIN SETTINGS */
    function register_setting() {
        register_setting( 'jetpack_post_views_settings', 'jetpack_post_views_settings', array( &$this, 'validate_settings' ) );
    }


    /* ADD JETPACK POST META ON POST PUBLISH */
    function add_jetpack_meta() {
        global $post;
        add_post_meta( $post->ID, 'jetpack-post-views', 0, true );
    }

    /* ADD POST VIEWS TO POST ADMIN PAGE */
    function add_post_views_column( $defaults ) {
        if ( $this->settings['display_total_views'] ) {
            $defaults['post_views'] = __( 'Total Views', 'jetpack-post-views' );
        }
        return $defaults;
    }

    /* SHOW THE TOTAL NUMBER OF VIEWS FOR A POST */
    function add_post_views_column_content( $column_name, $post_ID ) {
        if ($column_name == 'post_views') {
            $views = get_post_meta( $post_ID, "jetpack-post-views", true );

            if ( $views ) {
                echo number_format_i18n( $views ).__( ' views', 'jetpack-post-views' );
            }
            else {
                echo '0 views';
            }
        }
    }

    /* ALLOW SORTING OF POST VIEW COLUMN */
    function post_views_column_sort( $columns ) {
        $columns['post_views'] = 'post_views';
        return $columns;
    }

    /* SORT POST VIEW COLUMN BY VIEWS */
    function post_views_column_orderby( $vars ) {
        if ( isset( $vars['orderby'] ) && 'post_views' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => 'jetpack-post-views',
                'orderby'  => 'meta_value_num'
            ) );
        }
        return $vars;
    }

    /* ADD A "SETTINGS" LINK TO PLUGINS PAGE */
    function settings_link( $links, $file ) {
        static $this_plugin;

        if( empty($this_plugin) )
            $this_plugin = plugin_basename(__FILE__);

        if ( $file == $this_plugin )
            $links[] = '<a href="' . admin_url( 'options-general.php?page=jetpack_post_views' ) . '">' . __( 'Settings', 'jetpack-post-views' ) . '</a>';

        return $links;
    }

    /* VALIDATE SETTING PAGE SETTINGS */
    function validate_settings( $settings ) {
        if ( !empty($_POST['jetpack-post-views-defaults']) ) {
            $settings = $this->defaultsettings;
            $_REQUEST['_wp_http_referer']    = add_query_arg( 'defaults', 'true', $_REQUEST['_wp_http_referer'] );
        } else {
            // Hidden fields
            $settings['connect_blog_uri']    = strip_tags( $settings['connect_blog_uri'] );
            $settings['connect_blog_id']     = strip_tags( $settings['connect_blog_id'] );

            // Settings
            $settings['api_key']             = ( !empty( $settings['api_key'] ) )             ? strip_tags( $settings['api_key'] )             : "";
            $settings['blog_uri']            = ( !empty( $settings['blog_uri'] ) )            ? strip_tags( $settings['blog_uri'] )            : home_url( '/' );
            $settings['display_total_views'] = ( !empty( $settings['display_total_views'] ) ) ? strip_tags( $settings['display_total_views'] ) : "";
            $settings['use_stats_get_csv']   = ( !empty( $settings['use_stats_get_csv'] ) )   ? strip_tags( $settings['use_stats_get_csv'] )   : "";
            $settings['use_blog_uri']        = ( !empty( $settings['use_blog_uri'] ) )        ? strip_tags( $settings['use_blog_uri'] )        : "";

            // Flag settings change
            if ( $settings['api_key'] != $this->settings['api_key'] || $settings['blog_uri'] != $this->settings['blog_uri'] ) {
                $settings['changed']         = 1;
            }
        }

        return $settings;
    }

    /* SETTINGS PAGE */
    function settings_page() { ?>

        <style>
            .light {
                height: 14px;
                width: 14px;
                border-radius: 14px;
                -webkit-border-radius: 14px;
                -moz-border-radius: 14px;
                -ms-border-radius: 14px;
                -o-border-radius: 14px;
                position: relative;
                top: 3px;
                box-shadow:
                    0 1px 2px #fff,
                    0 -1px 1px #666,
                    inset 1px -2px 6px rgba(0,0,0,0.5),
                    inset -1px 1px 6px rgba(255,255,255,0.8);
            }
                .green {
                    background-color: #00b028;
                }
                .red {
                    background-color: #d20406;
                }

            .inner-light {
                position: absolute;
                top: -1px;
                left: 5px;
            }
                .green .inner-light {
                    background-color: rgba(123,252,149,0.7);
                    border: 3px solid rgba(47,205,82,0.7);
                    width: 1px;
                    height: 1px;
                    border-radius: 1px;
                    -webkit-border-radius: 1px;
                    -moz-border-radius: 1px;
                    -ms-border-radius: 1px;
                    -o-border-radius: 1px;
                    filter: blur(1.5px);
                    -webkit-filter: blur(1.5px);
                    -moz-filter: blur(1.5px);
                    -ms-filter: blur(1.5px);
                    -o-filter: blur(1.5px);
                }
                .red .inner-light {
                    background-color: rgba(225,162,157,0.7);
                    border: 1px solid rgba(222,222,222,0.7);
                    width: 7px;
                    height: 7px;
                    border-radius: 7px;
                    -webkit-border-radius: 7px;
                    -moz-border-radius: 7px;
                    -ms-border-radius: 7px;
                    -o-border-radius: 7px;
                    filter: blur(2px);
                    -webkit-filter: blur(2px);
                    -moz-filter: blur(2px);
                    -ms-filter: blur(2px);
                    -o-filter: blur(2px);
                }
        </style>

        <div class="wrap">
        <?php if ( function_exists('screen_icon') ) screen_icon(); ?>

            <h2><?php _e( 'Jetpack Post Views Settings', 'jetpack-post-views' ); ?></h2>

            <form method="post" action="options.php">

            <?php settings_fields('jetpack_post_views_settings'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="jetpack-post-views-display-total-views"><?php _e( 'Total Views', 'jetpack-post-views' ); ?></label></th>
                    <td><label for="jetpack-post-views-display-total-views">
                            <input name="jetpack_post_views_settings[display_total_views]" class="checkbox" type="checkbox" <?php checked( $this->settings['display_total_views'], 'on' ); ?> id="jetpack-post-views-display-total-views" />
                            <span><?php _e( 'Display total views for each post on the "All Posts" page.', 'jetpack-post-views' ); ?></span>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="jetpack-post-views-use-stats-get-csv"><?php _e( 'Stats_get_csv', 'jetpack-post-views' ); ?></th>
                    <td><label for="jetpack-post-views-use-stats-get-csv">
                            <input name="jetpack_post_views_settings[use_stats_get_csv]" class="checkbox" type="checkbox" <?php checked( $this->settings['use_stats_get_csv'], 'on' ); ?> id="jetpack-post-views-use-stats-get-csv" />
                            <span><?php _e( 'Use the Jetpack function <code>stats_get_csv()</code> to update post views. Disable this option to use the Blog URI or Blog ID instead (for example, if you are using a different site\'s settings).', 'jetpack-post-views' ); ?></span>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="jetpack-post-views-use-blog-uri"><?php _e( 'Blog URI', 'jetpack-post-views' ); ?></th>
                    <td><label for="jetpack-post-views-use-blog-uri">
                            <input name="jetpack_post_views_settings[use_blog_uri]" class="checkbox" type="checkbox" <?php checked( $this->settings['use_blog_uri'], 'on' ); ?> id="jetpack-post-views-use-blog-uri" />
                            <span><?php _e( 'Use the Blog URI and WordPress API Key to update post views. If you are not using the Jetpack function <code>stats_get_csv()</code>, disabling this option may help correctly update post views.', 'jetpack-post-views' ); ?></span>
                        </label>
                    </td>
                </tr>
            </table>

            <h3><?php _e( 'Blog Information', 'jetpack-post-views' ); ?></h3>

            <p><?php _e( 'Use the settings below if the plugin is unable to connect to Jetpack using the function <code>stats_get_csv()</code>.', 'jetpack-post-views' ); ?></p>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="jetpack-post-views-api-key"><?php _e( 'WordPress API Key', 'jetpack-post-views' ); ?></label></th>
                    <td><input name="jetpack_post_views_settings[api_key]" type="text" id="jetpack-post-views-api-key" value="<?php echo esc_attr( $this->settings['api_key'] ); ?>" class="regular-text" placeholder="<?php _e("https://apikey.wordpress.com/", 'jetpack-post-views'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="jetpack-post-views-blog_uri"><?php _e( 'Blog URI', 'jetpack-post-views' ); ?></label></th>
                    <td><input name="jetpack_post_views_settings[blog_uri]" type="text" id="jetpack-post-views-blog-uri" value="<?php echo esc_attr( $this->settings['blog_uri'] ); ?>" class="regular-text" /></td>
                </tr>
            </table>

            <h3><?php _e( 'Connections', 'jetpack-post-views' ); ?></h3>

            <p><?php _e( 'Shows the status of connections to the Jetpack API. If at least one of the below connections shows green, the plugin should work properly.<br>Stats updated using the first connection available in the order that they are listed.', 'jetpack-post-views' ); ?></p>

            <?php
                // Check connections only when settings have changed
                if ( $this->settings['changed'] ) {
                    $args            = $this->apiArgs;
                    $args['limit']   = 1;
                    $args['api_key'] = $this->settings['api_key'];

                    // Test the URI connection by testing for results from the api
                    $args['blog_uri'] = $this->settings['blog_uri'];

                    $url              = add_query_arg( $args, $this->apiUrl );
                    $json             = wp_remote_get( $url );
                    $data             = ( is_array( $json ) && !empty( $json['body'] ) ? json_decode( $json['body'], true ) : array());

                    $this->settings['connect_blog_uri'] = ( !empty( $data[0]['postviews'] ) ? 1 : 0 );

                    // Test the Blog ID connection by testing for results from the api
                    unset( $args['blog_uri'] );
                    $args['blog_id']  = $this->settings['blog_id'];

                    $url              = add_query_arg( $args, $this->apiUrl );
                    $json             = wp_remote_get( $url );
                    $data             = ( is_array( $json ) && !empty( $json['body'] ) ? json_decode( $json['body'], true ) : array());

                    $this->settings['connect_blog_id'] = ( !empty( $data[0]['postviews'] ) ? 1 : 0 );

                    $this->get_post_views();
                }
            ?>

            <input type="hidden" name="jetpack_post_views_settings[connect_blog_uri]" id="jetpack-post-views-connect-blog-id" value="<?php echo esc_attr( $this->settings['connect_blog_uri'] ); ?>" />
            <input type="hidden" name="jetpack_post_views_settings[connect_blog_id]" id="jetpack-post-views-connect-blog-id" value="<?php echo esc_attr( $this->settings['connect_blog_id'] ); ?>" />
            <table class="form-table">
                <fieldset>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Function <code>stats_get_csv()</code> exists', 'jetpack-post-views' ); ?></th>
                        <td>
                            <div class="light <?php echo (function_exists('stats_get_csv') ? "green" : "red") ?>"><div class="inner-light"></div></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Can connect using Blog URI', 'jetpack-post-views' ); ?></th>
                        <td>
                            <div class="light <?php echo ($this->settings['connect_blog_uri'] ? "green" : "red") ?>"><div class="inner-light"></div></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Can connect using Blog ID', 'jetpack-post-views' ); ?></th>
                        <td>
                            <div class="light <?php echo ($this->settings['connect_blog_id'] ? "green" : "red") ?>"><div class="inner-light"></div></div>
                        </td>
                    </tr>
                </fieldset>
            </table>

            <p class="submit">
            <?php
                if ( function_exists( 'submit_button' ) ) {
                    submit_button( null, 'primary', 'jetpack-post-views-submit', false );
                } else {
                    echo '<input type="submit" name="jetpack-post-views-submit" class="button-primary" value="' . __( 'Save Changes', 'jetpack-post-views' ) . '" />' . "\n";
                }
            ?>
            </p>

            <p>
                <h3><?php _e( 'Donate to Help Support this Plugin', 'jetpack-post-views' ); ?></h3>
                <p><?php _e( 'Jetpack Post Views remains a free plugin thanks to donations from supporters like you. Donations like yours help me to continue supporting this plugin with regular updates and bug fixes. Thanks for being a supporter!', 'jetpack-post-views' ); ?></p>
                <p><?php _e( '- Steven Lambert', 'jetpack-post-views' ); ?></p>
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPUDV9EYETJYJ" title="Donate to this plugin!">
                    <img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" alt="" />
                </a>
            </p>

            <?php
                if ($this->DEBUG) {
                    echo "<div class='widefat'><h3>Plugin options</h3><pre>";
                    foreach ($this->settings as $key => $value) {
                        echo "[$key]: $value\n";
                    }
                    echo "</pre></div>";

                    if (function_exists('stats_get_csv')) {
                        echo "<br>stats_get_csv function exists<br>";
                    }
                    else {
                        echo "<br>stats_get_csv function does not exist<br>";
                    }

                    $this->get_post_views();
                    ksort($this->updateMessages);

                    echo "<br><div class='widefat'><h3>Update Messages by Post ID</h3><pre>";
                    foreach ($this->updateMessages as $key => $value) {
                        if ( gettype($value) == "array") {
                            echo "[$key]: ";
                            print_r($value);
                        }
                        else {
                            echo "[$key]: $value\n";
                        }
                    }
                    echo "</pre></div>";
                }
            ?>

        </form>

    </div>

    <?php
    }

    /* WIDGET OUTPUT */
    function widget( $args, $instance ) { ?>

        <style>
            .JPV_list {
                overflow: hidden;
            }

            .JPV_thumbnail {
                width: 45%;
            }

            .JPV_thumbnail > .JPV_thumbnail_img {
                max-width: 125px;
                height: auto;
            }

            .JPV_thumbnail + .JPV_text {
                width: 45%;
                padding-bottom: 10px;
                text-align: center;
                margin-top: -5px;
            }

            .JPV_thumbnail_title {
                float: left;
                width: 21.276596%;
            }

            .JPV_thumbnail_title > .JPV_thumbnail_img {
                max-width: 40px;
                height: auto;
            }

            .JPV_thumbnail_title + .JPV_text {
                float: right;
                width: 73.404255%;
                padding-bottom: 10px;
            }
        </style>

        <?php
        global $post;
        extract ( $args );

        $title         = apply_filters('widget_title', $instance['title'] );
        $results       = "";
        $exclude_posts = explode( ',', $instance['exclude_posts'] );

        echo $before_widget;

        // Print the title
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // Get all categories
        $categories = get_categories();

        // Get all post types
        $all_post_types         = array();
        $all_post_types         = get_post_types( array( '_builtin' => false ), 'names' );
        $all_post_types['post'] = 'post';

        // Filter which post types are displayed
        $post_types = array();
        foreach ( $all_post_types as $key => $value ) {
            if ( $instance['type_'.$key] ) {
                array_push($post_types, $key);
            }
        }
        // Filter results by category
        $cat_list = array();
        foreach ( $categories as $key => $value ) {
            if ( $instance['category_'.$value->slug] ) {
                array_push($cat_list, $value->cat_ID);
            }
        }
        $category = implode(',', $cat_list);

        // Get all posts
        $meta_key = 'jetpack-post-views';
        if ( $instance['days'] != $this->interval['Unlimited'] ) {
            $key       = array_search( $instance['days'], $this->interval );
            $meta_key .= '-'.$key;
        }
        $args = array(
            'numberposts' => $instance['num_posts'],
            'orderby'     => 'meta_value_num',
            'order'       => 'DESC',
            'exclude'     => $instance['exclude_posts'],
            'meta_key'    => $meta_key,
            'post_type'   => $post_types,
            'category'    => $category
        );
        $posts = get_posts( $args );

        // Print top posts in order
        $results = "<ul>";
        foreach( $posts as $post ) {
            $title     = get_the_title( $post->ID );
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
            $views     = number_format_i18n( get_post_meta( $post->ID, $meta_key, true ) ).__( ' views', 'jetpack-post-views' );

            $results .= '<li class="JPV_list"><a href="'.get_permalink( $post->ID ).'" title="'.$title.'" class="JPV_'.$instance["display_type"].'">';
            switch ( $instance["display_type"] ) {
                case "thumbnail":
                    $results .= '<img src="'.$thumbnail[0].'" class="JPV_thumbnail_img"/></a>';
                    if ( $instance["show_views"] )
                        $results .= '<div class="JPV_text">'.$views.'</div>';
                    break;
                case "thumbnail_title":
                    $results .= '<img src="'.$thumbnail[0].'" class="JPV_thumbnail_img"/></a><div class="JPV_text"><a href="'.get_permalink( $post->ID ).'" title="'.$title.'">'.$title.'</a>';
                    if ( $instance["show_views"] )
                        $results .= " - ".$views.'</div>';
                    break;
                default:
                    $results .= $title.'</a>';
                    if ( $instance["show_views"] )
                        $results .= " - ".$views;
            }
            $results .= '</li>';
        }
        $results .= "</ul>";

        echo $results;

        echo $after_widget;
    }

    /* UPDATE WIDGET OPTIONS */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        // Check nonce
        check_admin_referer('jetpack-post-views-widget-form-submission');

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['show_views'] = strip_tags( $new_instance['show_views'] );
        $instance['days'] = strip_tags( $new_instance['days'] );
        $instance['exclude_custom_types'] = strip_tags( $new_instance['exclude_custom_types'] );
        $instance['exclude_posts'] = strip_tags( $new_instance['exclude_posts'] );
        $instance['display_type'] = strip_tags( $new_instance['display_type'] );
        $instance['display_post_types'] = strip_tags( $new_instance['display_post_types'] );

        // Get all post types
        $post_types = get_post_types( array( '_builtin' => false ), 'names' );
        $post_types['post'] = 'post';
        foreach ( $post_types as $key => $value ) {
            if ( $value != 'safecss' ) {
                $instance['type_'.$key] = strip_tags( $new_instance['type_'.$key] );
            }
        }

        // Get all categories
        $categories = get_categories();
        foreach ( $categories as $key => $value ) {
            $instance['category_'.$value->slug] = strip_tags( $new_instance['category_'.$value->slug] );
        }

        // Set default number of posts to display if invalid option
        $num_posts = intval( strip_tags( $new_instance['num_posts'] ) );
        $instance['num_posts'] = ($num_posts > 0 ? $num_posts : 5);

        return $instance;
    }

    /* DISPLAY WIDGET OPTIONS */
    function form( $instance ) {

        // Default widget settings
        $defaults = array(
            'title'                => __( 'Most Popular Posts', 'jetpack-post-views' ),
            'error'                => '',
            'num_posts'            => 5,
            'days'                 => '-1',
            'show_views'           => false,
            'display_type'         => 'title',
            'exclude_posts'        => '',
            'display_post_types'   => ''
        );

        // Get all post types
        $post_types = get_post_types( array( '_builtin' => false ), 'names' );
        $post_types['post'] = 'post';
        foreach ( $post_types as $key => $value ) {
            if ( $value != 'safecss' ) { // I have no idea what this is but it's not a valid post type
                $defaults['type_'.$key] = 'on';
            }
        }

        // Get all categories
        $categories = get_categories();
        foreach ( $categories as $key => $value ) {
            $defaults['category_'.$value->slug] = 'on';
        }

        $instance = wp_parse_args( (array) $instance, $defaults );

        // Set nonce
        if ( function_exists('wp_nonce_field') ) {
            wp_nonce_field( 'jetpack-post-views-widget-form-submission' );
            echo "\n<!-- end of wp_nonce_field -->\n";
        }

        ?>

        <style>
            #JPV-display-content h3 {
                margin-top: 0;
            }
        </style>

        <div id="JPV-display-content" class="JPV-tab-content active">
            <h3>Display</h3>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'jetpack-post-views'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of posts to show:', 'jetpack-post-views'); ?></label>
                <input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" type="text" size="3" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'days' ); ?>"><?php _e('Time interval:', 'jetpack-post-views'); ?></label>
                <select id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>">
                    <option value="<?php echo $this->interval['Unlimited'] ?>" <?php echo ($instance['days'] == $this->interval['Unlimited'] ? 'selected' : '') ?> ><?php _e('Unlimited', 'jetpack-post-views'); ?></option>
                    <option value="<?php echo $this->interval['Day'] ?>"       <?php echo ($instance['days'] == $this->interval['Day']       ? 'selected' : '') ?> ><?php _e('Day',       'jetpack-post-views'); ?></option>
                    <option value="<?php echo $this->interval['Week'] ?>"      <?php echo ($instance['days'] == $this->interval['Week']      ? 'selected' : '') ?> ><?php _e('Week',      'jetpack-post-views'); ?></option>
                    <option value="<?php echo $this->interval['Month'] ?>"     <?php echo ($instance['days'] == $this->interval['Month']     ? 'selected' : '') ?> ><?php _e('Month',     'jetpack-post-views'); ?></option>
                    <option value="<?php echo $this->interval['Year'] ?>"      <?php echo ($instance['days'] == $this->interval['Year']      ? 'selected' : '') ?> ><?php _e('Year',      'jetpack-post-views'); ?></option>
                </select>
            </p>

            <p>
                <input class="checkbox" type="checkbox" <?php checked( $instance['show_views'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_views' ); ?>" name="<?php echo $this->get_field_name( 'show_views' ); ?>" />
                <label for="<?php echo $this->get_field_id( 'show_views' ); ?>"><?php _e('Display number of views?', 'jetpack-post-views'); ?></label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'display_type' ); ?>"><?php _e('Display as:', 'jetpack-post-views'); ?></label>
                <select id="<?php echo $this->get_field_id( 'display_type' ); ?>" name="<?php echo $this->get_field_name( 'display_type' ); ?>">
                    <option value="title"           <?php echo ($instance['display_type'] == 'title'           ? 'selected' : '') ?> ><?php _e('Title',             'jetpack-post-views'); ?></option>
                    <option value="thumbnail"       <?php echo ($instance['display_type'] == 'thumbnail'       ? 'selected' : '') ?> ><?php _e('Thumbnail',         'jetpack-post-views'); ?></option>
                    <option value="thumbnail_title" <?php echo ($instance['display_type'] == 'thumbnail_title' ? 'selected' : '') ?> ><?php _e('Thumbnail + Title', 'jetpack-post-views'); ?></option>
                </select>
            </p>
        </div>

        <div id="JPV-filter-content" class="JPV-tab-content">
            <h3>Filter</h3>

            <p>
                <label for="<?php echo $this->get_field_id( 'exclude_posts' ); ?>"><?php _e('Exclude Posts:', 'jetpack-post-views'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'exclude_posts' ); ?>" name="<?php echo $this->get_field_name( 'exclude_posts' ); ?>" value="<?php echo $instance['exclude_posts']; ?>" type="text" placeholder="<?php _e('Comma-separated list of post IDs', 'jetpack-post-views'); ?>" />
            </p>

            <p>
                <?php _e('Display only from selected post types:', 'jetpack-post-views'); ?>
                <br>
                <?php
                    foreach ( $post_types as $key => $value ) {
                        if ( $value != 'safecss' ) {
                ?>
                            <input class="checkbox" type="checkbox" <?php checked( $instance['type_'.$key], 'on' ); ?> id="<?php echo $this->get_field_id( 'type_'.$key ); ?>" name="<?php echo $this->get_field_name( 'type_'.$key ); ?>" />
                            <label for="<?php echo $this->get_field_id( 'type_'.$key ); ?>"><?php echo $key ?></label>
                            <br/>
                <?php
                        }
                    }
                ?>

            </p>

            <p>
                <?php _e('Display only from selected categories:', 'jetpack-post-views'); ?>
                <br>
                <?php
                    foreach ( $categories as $key => $value ) {
                ?>
                        <input class="checkbox" type="checkbox" <?php checked( $instance['category_'.$value->slug], 'on' ); ?> id="<?php echo $this->get_field_id( 'category_'.$value->slug ); ?>" name="<?php echo $this->get_field_name( 'category_'.$value->slug ); ?>" />
                        <label for="<?php echo $this->get_field_id( 'category_'.$value->slug ); ?>"><?php echo $value->name ?></label>
                        <br/>
                <?php
                    }
                ?>

            </p>
        </div>

    <?php
    }

    /*
    * SCHEDULED UPDATE
    * Update all posts 'jetpack-post-views' post meta
    */
    function get_post_views() {
        $post_stats = array();
        $postdata   = array();

        // Hack to break cache suggested by Glisse.
        // Since 'limit' is capped at 500, use it to break cache for each interval.
        // http://wordpress.org/support/topic/post-views-not-updating
        $random = rand( 500, 99999 );

        // Get stats using the stats_get_csv function
        if ( function_exists('stats_get_csv') && $this->settings['use_stats_get_csv'] ) {

            // Get post views for each interval: Unlimited, Day, Week, Month, Year
            foreach ($this->interval as $key => $value) {
                $args = array(
                    'days'      => $value,
                    'limit'     => $random,
                    'summarize' => 1
                );
                $post_stats[$key] = stats_get_csv( 'postviews', $args );
            }

            $this->updateMessages[-2] = "Stats being updated using the stats_get_csv function";
        }
        // Get the stats using the blog_uri or blog_id
        else {
            $args            = $this->apiArgs;
            $args['api_key'] = $this->settings['api_key'];
            $args['limit']   = $random;

            // Get the stats using the blog_uri
            if ( $this->settings['connect_blog_uri'] && $this->settings['use_blog_uri'] ) {
                $args['blog_uri'] = $this->settings['blog_uri'];
                $this->updateMessages[-2] = "Stats being updated using blog_uri";
            }
            // Get the stats using the blog_id
            else if ( $this->settings['connect_blog_id'] ) {
                $args['blog_id'] = $this->settings['blog_id'];
                $this->updateMessages[-2] = "Stats being updated using blog_id";
            }
            else {
                return;
            }

            // Get post views for each interval: Unlimited, Day, Week, Month, Year
            foreach ($this->interval as $key => $value) {
                $args['days']     = $value;
                $url              = add_query_arg( $args, $this->apiUrl );
                $json             = wp_remote_get( $url );
                $data             = json_decode( $json['body'], true );
                $post_stats[$key] = $data[0]['postviews'];
            }
        }

        $this->updateMessages[-1] = $post_stats;
        global $post;

        // Create an array indexed by interval and then post_id
        foreach ( $post_stats as $key => $value ) {
            $postdata[$key] = array();
            foreach ( $value as $postinfo ) {
                $postdata[$key][$postinfo['post_id']] = strip_tags( $postinfo['views'] );
            }
        }

        // Get all posts and update them
        $post_types         = get_post_types( array( '_builtin' => false ), 'names' );
        $post_types['post'] = 'post';

        $args = array(
            'numberposts' => -1,
            'post_type'   => $post_types,
            'post_status' => 'publish'
        );
        $allposts = get_posts( $args );
        foreach( $allposts as $post) {
            $message  = "'".get_the_title( $post->ID )."' updated with stats:";

            // Cannot save the interval data as an array in a meta key because WP cannot sort post queries by an array.
            // Have to store each interval data in it's own meta key.
            // http://wordpress.stackexchange.com/questions/99149/wp-query-meta-query-by-array-key
            foreach ( $this->interval as $key => $value ) {

                // Ensure that the $post-ID exists as a key in the array before trying to update post information.
                // This prevents the user from pulling data from another website and trying to add it to their own
                // posts.
                if ( array_key_exists( $post->ID, $postdata[$key] ) ) {
                    $newViews = intval( $postdata[$key][ $post->ID ] );

                    // Unlimited meta key different as to not break sites that used this previously
                    if ( $key == 'Unlimited' ) {
                        $oldViews = get_post_meta( $post->ID, 'jetpack-post-views', true );

                        // Only update posts with new stats. Prevents posts from being updated with '0' views when the API service is down.
                        if ( $newViews < $oldViews ) {
                            $newViews = $oldViews;
                        }

                        update_post_meta( $post->ID, 'jetpack-post-views', $newViews );
                    }
                    else {
                        // Always update non-unlimited intervals since they can fluctuate
                        update_post_meta( $post->ID, 'jetpack-post-views-'.$key, $newViews );
                    }

                    $message .= " $key($newViews)";
                }
                else {
                    $message .= " $key(not updated)";
                }
            }

            $this->updateMessages[ $post->ID ] = $message;
        }
    }

    /* UPGRADE PLUGIN TO NEW VERSION */
    function upgrade() {
        global $post;

        // Delete old options
        delete_option( 'jetpack-post-views_version' );
        delete_option( 'jetpack_post_views_wp_api_key' );
        delete_option( 'jetpack_post_views_stats_has_run' );

        // Add post meta for each post
        $post_types         = get_post_types( array( '_builtin' => false ), 'names' );
        $post_types['post'] = 'post';

        $args = array(
            'numberposts' => -1,
            'post_type'   => $post_types,
            'post_status' => 'publish'
        );
        $allposts = get_posts( $args );
        foreach( $allposts as $post) {
            foreach ( $this->interval as $key => $value ) {
                $meta_key = '';
                if ( $key == 'Unlimited' ) {
                    $meta_key = 'jetpack-post-views';
                }
                else {
                    $meta_key = 'jetpack-post-views-'.$key;
                }

                $post_meta = get_post_meta( $post->ID, $meta_key, true );

                // Only add post_meta if not already defined
                if (empty($post_meta)) {
                    add_post_meta( $post->ID, $meta_key, 0, true );
                }
            }
        }

        $this->get_post_views();

        update_option(JETPACK_POST_VIEWS_VERSION_KEY, $this->version);
    }

    // PHP4 compatibility
    function Jetpack_Post_Views() {
        $this->__construct();
    }

    // Print a message to debug.log (useful for testing purposes)
    function printError( $message ) {
        if ( WP_DEBUG === true ) {
            error_log("========================================================================================================");
            error_log("=================================== JETPACK_POST_VIEWS ERROR MESSAGE ===================================");
            error_log("========================================================================================================");
            if ( is_array($message) || is_object($message) ) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }
}

// Register the widget
function jetpack_post_views_register_widget() {
    register_widget( 'Jetpack_Post_Views' );
}
add_action( 'widgets_init', 'jetpack_post_views_register_widget' );

/* SET SCHEDULED EVENT */
function jetpack_post_views_on_activation() {
    wp_schedule_event( time() + 3600, 'hourly', 'jetpack_post_views_scheduled_update' );
    global $Jetpack_Post_Views;
    $Jetpack_Post_Views = new Jetpack_Post_Views();

    // Upgrade the plugin if necessary
    if (get_option(JETPACK_POST_VIEWS_VERSION_KEY) != $Jetpack_Post_Views->version) {
        $Jetpack_Post_Views->upgrade();
    }
    else {
        $Jetpack_Post_Views->get_post_views();
    }
}

/* UNSET SCHEDULED EVENT */
function jetpack_post_views_on_deactivation() {
    wp_clear_scheduled_hook( 'jetpack_post_views_scheduled_update' );
}
register_activation_hook( __FILE__, 'jetpack_post_views_on_activation' );
register_deactivation_hook( __FILE__, 'jetpack_post_views_on_deactivation' );

/*
 * DISPLAY TOP POSTS
 * Use the Jetpack stats_get_csv() function to create a list of the top posts
 * args: days                   - number of days of the desired time frame. '-1' means unlimited
 *       limit                  - number of posts to display. '-1' means unlimited. If days is -1, then limit is capped at 500
 *       exclude                - comma-separated list of post IDs to exclude from displaying
 *       excludeCustomPostTypes - flag to exclude custom post types from displaying
 *       displayViews           - flag to display the post views
 */
function JPV_display_top_posts( $args = array( 'days' => '-1', 'limit' => '5', 'exclude' => '', 'excludeCustomPostTypes' => fase, 'displayViews' => false ) ) {
    // Ensure that the stats_get_csv() function exists and returns posts
    if ( function_exists('stats_get_csv') && $posts = stats_get_csv('postviews', 'days='.($args['days'] ? $args['days'] : '-1').'&limit=-1' ) ) {
        $count = 0;
        $exclude_posts = explode( ',', $args['exclude'] );

        // Print top posts in order
        echo "<ul class='JVP-top-posts'>";
        foreach( $posts as $post ) {

            // Stop printing posts if we reach the limit
            if ( $args['limit'] && $count >= intval( $args['limit'] ) ) {
                break;
            }

            // Only display posts
            $post_types = array();
            if ( !$args['excludeCustomPostTypes'] ) {
                $post_types = get_post_types( array( '_builtin' => false ), 'names' );
            }
            $post_types['post'] = 'post';

            if ( $post['post_id'] && get_post( $post['post_id'] ) && in_array( get_post_type( $post['post_id'] ), $post_types ) && !in_array( $post['post_id'], $exclude_posts) ) { ?>
                <li><a href="<?php echo get_permalink( $post['post_id'] ) ?>"><?php echo get_the_title( $post['post_id'] ) ?></a>
                    <?php if ( $args['displayViews'] ) {
                        echo " - ".number_format_i18n( $post['views'] ? $post['views'] : 0 ).__( ' views', 'jetpack-post-views' );
                    } ?>
                </li>
            <?php
                $count++;
            }
        }
        echo "</ul>";
    }
}

/*
 * SHORTCODE
 * Use the Jetpack stats_get_csv() function to create a list of the top posts
 * atts: days                   - number of days of the desired time frame. '-1' means unlimited
 *       limit                  - number of posts to display. '-1' means unlimited. If days is -1, then limit is capped at 500
 *       exclude                - comma-separated list of post IDs to exclude from displaying
 *       excludeCustomPostTypes - flag to exclude custom post types from displaying
 *       displayviews           - flag to display the post views
 */
add_shortcode( 'jpv', 'JPV_shortcode' );
function JPV_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'days' => '-1',
        'limit' => '5',
        'exclude' => '',
        'excludeCustomPostTypes' => false,
        'displayviews' => false,
    ), $atts ) );

    JPV_display_top_posts( $args = array( 'days' => $days, 'limit' => $limit, 'exclude' => $exclude, 'excludeCustomPostTypes' => $excludeCustomPostTypes, 'displayViews' => $displayviews ) );
}