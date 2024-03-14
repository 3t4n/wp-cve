<?php
/*
 * Plugin Name: Blog Post Calendar Widget
 * Plugin URI: http://presshive.com
 * Author: <a href="http://presshive.com/">Presshive</a>
 * Version: 1.1
 * Description: The Blog Posts Calendar Widget allows you to display your archived or future posts in a widget. 
 * Tags: posts, calendar, widget, post types, future posts, events calendar
 * License: GPLv2 or later
 */

/*  Copyright 2013  

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
define('WP_CALENDAR_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('WP_CALENDAR_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('WP_CALENDAR_PLUGIN_CSS_PATH', WP_CALENDAR_PLUGIN_DIR . '/css/wp_calendar.css');
define('WP_CALENDAR_PLUGIN_CSS_URL', WP_CALENDAR_PLUGIN_URL . '/css/wp_calendar.css');
define('WP_CALENDAR_THEME_CSS_PATH', get_stylesheet_directory() . '/wp_calendar.css');
define('WP_CALENDAR_THEME_CSS_URL', get_stylesheet_directory_uri() . '/wp_calendar.css');

if (!session_id()) {
    session_start();
}

/**
 * @name $WP_Calendar_get_regional_match
 * @return null
 */
function WP_Calendar_get_regional_match() {
    $locale = get_locale();

    $regionals = array(
        'ar' => 'Arabic',
        'fr' => 'French',
        'he' => 'Hebrew',
    );

    $key_match = array(
        substr($locale, 0, 2),
        str_replace('_', '-', $locale),
    );

    if ($key_match[1] != 'en') {
        foreach ($key_match as $key) {
            if (array_key_exists($key, $regionals)) {
                return $key;
            }
        }
    }

    return null;
}

/**
 * function register and enqueue scripts in front end
 */
function wp_calendar_enqueue_scripts() {
    wp_enqueue_script('jquery');
    $regional = WP_Calendar_get_regional_match();
    if (!empty($regional)) {
        wp_register_script('wp_calendar_datepicker-' . $regional, WP_CALENDAR_PLUGIN_URL . '/js/jquery.ui.datepicker-' . $regional . '.js');
    }

    wp_register_script('wp_calendar_datepicker', WP_CALENDAR_PLUGIN_URL . '/js/jquery.ui.datepicker.js');
    wp_register_script('wp_calendar_js', WP_CALENDAR_PLUGIN_URL . '/js/wp_calendar.js', array('jquery'));

    if (file_exists(WP_CALENDAR_THEME_CSS_PATH))
        wp_register_style('wp_calendar_css', WP_CALENDAR_THEME_CSS_URL);
    else
        wp_register_style('wp_calendar_css', WP_CALENDAR_PLUGIN_CSS_URL);

    wp_enqueue_script('wp_calendar_datepicker-' . $regional);
    wp_enqueue_script('wp_calendar_datepicker');
    wp_enqueue_style('wp_calendar_css');
}

add_action('wp_enqueue_scripts', 'wp_calendar_enqueue_scripts');

/**
 * function load custom css in site header
 */
function wp_calendar_load_custom_style_cb() {
    ?>
    <style>
        #calendar_wrap .ui-datepicker-prev span {background: url("<?php echo WP_CALENDAR_PLUGIN_URL; ?>/images/arrow-new.png") no-repeat scroll 0 0 transparent;}
        #calendar_wrap .ui-datepicker-prev span:hover {background: url("<?php echo WP_CALENDAR_PLUGIN_URL; ?>/images/arrow-prev-hover.png") no-repeat scroll 0 0 transparent;}
        #calendar_wrap .ui-datepicker-next span {background: url("<?php echo WP_CALENDAR_PLUGIN_URL; ?>/images/arrow-new2.png") no-repeat scroll 0 0 transparent;}
        #calendar_wrap .ui-datepicker-next span:hover {background: url("<?php echo WP_CALENDAR_PLUGIN_URL; ?>/images/arrow-next-hover.png") no-repeat scroll 0 0 transparent;}
    </style>
    <?php
}

add_action('wp_head', 'wp_calendar_load_custom_style_cb');

/**
 * function registers and enqueue scripts in admin end
 */
function wp_calendar_enqueue_admin_scripts() {
    wp_register_script('wp_calendar_admin_js', WP_CALENDAR_PLUGIN_URL . '/js/wp_calendar_admin.js', array('jquery'));
    wp_enqueue_script('wp_calendar_admin_js');
    wp_localize_script('wp_calendar_admin_js', 'wpCalancerAdminObj', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'wp_calendar_enqueue_admin_scripts');

/**
 * WP_Calander_Widget class for WP CALENDAR widget
 */
class WP_Calander_Widget extends WP_Widget {

    function WP_Calander_Widget() {
        // Load language textdomain
        load_plugin_textdomain('wp_calendar', false, basename(dirname(__FILE__)) . '/languages');

        $widget_ops = array('classname' => 'wp_calendar', 'description' => __('A calendar widget to show posts.', 'wp_calendar'));
        $control_ops = array('width' => 300, 'height' => 350);
        $this->WP_Widget('', __('Blog Post Calendar', 'wp_calendar'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        global $post;
        extract($args);

        $title = isset($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title);
        $catname = isset($instance['catname']) ? $instance['catname'] : '';
        $future = isset($instance['future']) ? $instance['future'] : '';
        $selected_taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : '';
        $selected_term = isset($instance['term']) ? $instance['term'] : '';
        $show_author = isset($instance['show_author']) ? $instance['show_author'] : '';
        $show_comment_count = isset($instance['show_comment_count']) ? $instance['show_comment_count'] : '';
        $calendar_size = isset($instance['calendar_size']) ? $instance['calendar_size'] : '';

        wp_enqueue_script('wp_calendar_js');
        wp_localize_script('wp_calendar_js', 'wpCalendarObj', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'plugin_url' => WP_CALENDAR_PLUGIN_URL,
            'wpCalPostname' => $catname,
            'author' => $show_author,
            'comment_count' => $show_comment_count,
            'future' => $future,
            'taxonomy' => $selected_taxonomy,
            'term' => $selected_term,
            'calendar_size' => $calendar_size
        ));
        $time = get_the_time("j n Y", $post->ID);
        $archive_time = intval(get_query_var('day')) . ' ' . intval(get_query_var('monthnum')) . ' ' . intval(get_query_var('year'));

        if ($title) {
            echo $before_title . $title . $after_title;
        }
        echo $before_widget;
        ?>
        <div class="widget_calendar_<?php echo $calendar_size; ?> widget widget_calendar">
            <div class="widget_inner">
                <div id="calendar_wrap">
                    <div id="wp-calendar"></div>
                    <div class="calendar-pagi">
                        <ul>
                            <li class="wp-cal-prev"><a onclick="jQuery.datepicker._adjustDate('#wp-calendar', -1, 'M');"><?php echo __("&laquo; Prev Month", 'wp_calendar'); ?></a></li>
                            <li class="wp-cal-next"><a onclick="jQuery.datepicker._adjustDate('#wp-calendar', +1, 'M');"><?php echo __("Next Month &raquo;", 'wp_calendar'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="calendar_wrap_loading calendar_wrap_loading_hide"><img src="<?php echo WP_CALENDAR_PLUGIN_URL; ?>/images/ajax-processing.gif"></div>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['catname'] = strip_tags($new_instance['catname']);
        $instance['future'] = strip_tags($new_instance['future']);
        $instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
        $instance['term'] = strip_tags($new_instance['term']);
        $instance['show_author'] = strip_tags($new_instance['show_author']);
        $instance['show_comment_count'] = strip_tags($new_instance['show_comment_count']);
        $instance['calendar_size'] = strip_tags($new_instance['calendar_size']);
        return $instance;
    }

    function form($instance) {
        $defaults = array('title' => '', 'catname' => 'post', 'future' => 'checked', 'taxonomy' => 'all', 'term' => 'all', 'show_author' => 'checked', 'show_comment_count' => 'checked', 'calendar_size' => 'medium_size');
        $instance = wp_parse_args((array) $instance, $defaults);
        $taxonomies = array();
        $terms_str = '';
        $selected_taxonomy = isset($instance['taxonomy']) ? $instance['taxonomy'] : '';
        $selected_term = isset($instance['term']) ? $instance['term'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp_calendar'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if ($instance['title']) echo $instance['title']; ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('catname'); ?>" ><?php _e('Select Post type:', 'wp_calendar'); ?></label>
            <select class="widefat" onchange="wp_calendar_check_taxonomy('<?php echo $this->get_field_id('cal_p'); ?>', '<?php echo $this->get_field_name('catname'); ?>')" id="<?php echo $this->get_field_id('catname'); ?>" name="<?php echo $this->get_field_name('catname'); ?>">
                <?php
                $post_types = get_post_types();
                foreach ($post_types as $post_type) {
                    $selected = '';
                    if ($post_type == $instance['catname']) {
                        $selected = 'selected="selected"';
                    }
                    echo '<option ' . $selected . ' value="' . $post_type . '">' . $post_type . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
        $taxo = get_object_taxonomies($instance['catname'], 'objects');
        $style = 'style="Display:none"';
        if ($taxo) {
            $style = '';
        }
        ?>
        <div id="<?php echo $this->get_field_id('cal_p'); ?>" <?php echo $style; ?>>
            <table>
                <?php
                echo '<tr>
                            <td>
                                <label for="' . $this->get_field_id('taxonomy') . '" >' . __('Select a category', 'wp_calendar') . '</label>
                            </td>
                            <td>
                                <select onchange="wp_calendar_check_terms(\'' . $this->get_field_id('cal_p') . '\', \'' . $this->get_field_name('taxonomy') . '\')"  id="' . $this->get_field_id('taxonomy') . '" name="' . $this->get_field_name('taxonomy') . '" rel="taxonomy">';
                $selected = '';
                if ($selected_taxonomy == 'all') {
                    $selected = 'selected="selected"';
                }
                echo '<option ' . $selected . ' value="all" >' . __('All', 'wp_calendar') . '</option>';
                if (!empty($instance['catname'])) {

                    foreach ($taxo as $key => $val) {
                        $selected = '';
                        if ($selected_taxonomy == $val->name) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option ' . $selected . ' value="' . $val->name . '" >' . $val->name . '</option>';
                    }
                }
                echo '      </select>
                                </td>
                            </tr>';

                echo '<tr ' . $style . '><td><label for="' . $this->get_field_id('term') . '" >' . __('Select a term', 'wp_calendar') . '</label></td>
                            <td><select  id="' . $this->get_field_id('term') . '" name="' . $this->get_field_name('term') . '" rel="term">';
                $selected = '';
                if ($selected_term == 'all') {
                    $selected = 'selected="selected"';
                }
                echo '<option ' . $selected . ' value="all" >' . __('All', 'wp_calendar') . '</option>';
                if (!empty($selected_term)) {
                    $terms = get_terms($selected_taxonomy, array('hide_empty' => false));
                    foreach ($terms as $key => $val) {
                        $selected = '';
                        if ($selected_term == $val->term_id) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option ' . $selected . ' value="' . $val->term_id . '" >' . $val->name . '</option>';
                    }
                }
                echo '</select></td></tr>';
                ?>
            </table>
        </div>


        <table>
            <tr>
                <td><label for="<?php echo $this->get_field_id('show_author'); ?>"><?php _e('Show Author:', 'wp_calendar'); ?></label></td>
                <td>
                    <?php
                    $checked = '';
                    if ($instance['show_author'] == 'checked') {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" id="<?php echo $this->get_field_id('show_author'); ?>" <?php echo $checked; ?> name="<?php echo $this->get_field_name('show_author'); ?>" value="checked" class="widefat" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="<?php echo $this->get_field_id('show_comment_count'); ?>"><?php _e('Show Comment Count:', 'wp_calendar'); ?></label>
                </td>
                <td>
                    <?php
                    $checked = '';
                    if ($instance['show_comment_count'] == 'checked') {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" id="<?php echo $this->get_field_id('show_comment_count'); ?>" <?php echo $checked; ?> name="<?php echo $this->get_field_name('show_comment_count'); ?>" value="checked" class="widefat" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="<?php echo $this->get_field_id('future'); ?>"><?php _e('Show future posts:', 'wp_calendar'); ?></label>
                </td>
                <td>
                    <?php
                    $checked = '';
                    if ($instance['future'] == 'checked') {
                        $checked = 'checked="checked"';
                    }
                    ?>
                    <input type="checkbox" id="<?php echo $this->get_field_id('future'); ?>" <?php echo $checked; ?> name="<?php echo $this->get_field_name('future'); ?>" value="checked" class="widefat" />
                </td>
            </tr>
            <tr>
                <td><label for="<?php echo $this->get_field_id('calendar_size'); ?>"><?php _e('Select Calendar size:', 'wp_calendar'); ?></label></td>
                <td>
                    <?php
                    echo '<select id="' . $this->get_field_id('calendar_size') . '" name="' . $this->get_field_name('calendar_size') . '" rel="calendar_size">';
                    echo '<option ' . (($instance['calendar_size'] == 'small_size') ? 'selected="selected"' : '') . ' value="small_size" >' . __('Small', 'wp_calendar') . '</option>';
                    echo '<option ' . (($instance['calendar_size'] == 'medium_size') ? 'selected="selected"' : '') . ' value="medium_size" >' . __('Medium', 'wp_calendar') . '</option>';
                    echo '<option ' . (($instance['calendar_size'] == 'large_size') ? 'selected="selected"' : '') . ' value="large_size" >' . __('Large', 'wp_calendar') . '</option>';
                    echo '</select>';
                    ?>
                </td>
            </tr>
        </table>
        <?php
    }

}

/**
 * Action to register widget
 */
add_action('widgets_init', create_function('', 'register_widget( "WP_Calander_Widget" );'));

/**
 * function to return taxonomies included with post type (for ajax request)
 */
function wp_calendar_get_taxonomy() {
    $post_type = $_REQUEST['post_type'];
    $taxonomies = array();
    if ($post_type) {
        $taxo = get_object_taxonomies($post_type, 'objects');
        if ($taxo) {
            $taxonomies['all'] = 'ALL';
            foreach ($taxo as $key => $val) {
                $taxonomies[$val->name] = $val->name;
            }
            die(json_encode($taxonomies));
        } else {
            die(json_encode('false'));
        }
    }
}

add_action('wp_ajax_wp_calendar_get_taxonomy', 'wp_calendar_get_taxonomy');

/**
 * function to return terms of a taxonomy(for ajax request)
 */
function wp_calendar_get_terms() {
    $taxonomy = $_REQUEST['taxonomy'];
    $terms_r = array();
    if ($taxonomy) {
        $terms = get_terms($taxonomy, array('hide_empty' => false));
        $terms_r['all'] = 'ALL';
        foreach ($terms as $key => $val) {
            $terms_r[$val->term_id] = $val->name;
        }
        die(json_encode($terms_r));
    } else {
        die('false');
    }
}

add_action('wp_ajax_wp_calendar_get_terms', 'wp_calendar_get_terms');

/**
 * function to return post data (in ajax request)
 */
function wp_calendar_get_posts() {
    $wp_cal_content = array();
    $ajax = $_POST['ajax'];
    $posttype = $_POST['post'];
    $future = $_POST['future'];
    $author = $_POST['author'];
    $show_comment_count = $_POST['comment_count'];
    $selected_month = $_POST['month'];
    $selected_year = $_POST['year'];

    if (isset($_POST['taxonomy']) && ($_POST['taxonomy'] != 'all')) {
        $taxonomy = $_POST['taxonomy'];
    } else {
        $taxonomy = false;
    }
    $term = false;
    if ($taxonomy) {
        if (isset($_POST['term']) && ($_POST['term'] != 'all')) {
            $term = $_POST['term'];
        } else {
            $term = false;
        }
    }

    if ($ajax == 'true') {
        $classes = array();
        $fpost = array();
        if (!$taxonomy) {
            $args = array();
            $args['posts_per_page'] = -1;
            $args['post_type'] = $posttype;
            $args['year'] = $selected_year;
            $args['monthnum'] = $selected_month;
            if ($future == 'checked') {
                $args['post_status'] = array('publish', 'future');
            } else {
                $args['post_status'] = array('publish');
            }
            $query = new WP_Query($args);
        } else {
            $args = array();
            $args['posts_per_page'] = '-1';
            $args['post_type'] = $posttype;
            $args['year'] = $selected_year;
            $args['monthnum'] = $selected_month;
            if ($future == 'checked') {
                $args['post_status'] = array('publish', 'future');
            } else {
                $args['post_status'] = array('publish');
            }
            if ($taxonomy && $term) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $term,
                        'operator' => 'IN'
                    )
                );
            } elseif ($taxonomy) {
                $terms_r = array();
                $terms = get_terms($taxonomy);
                foreach ($terms as $key => $val) {
                    $terms_r[] = $val->term_id;
                }
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $terms_r,
                        'operator' => 'IN'
                    )
                );
            }
            $query = new WP_Query($args);
        }
        $links = array();
        $permalink = array();
        $datel = array();
        $date_day = array();
        $date_month = array();
        $date_year = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_date = $query->post->post_date;
                $da = date("Y-n-j", strtotime($post_date));
                $classes[$da]++;
            }
        }
        $wp_cal_content['classes'] = $classes;

        $posts_div = array();
        foreach ($wp_cal_content['classes'] as $date => $val) {
            $date_array = explode('-', $date);
            $year = (int) $date_array[0];
            $month = (int) $date_array[1];
            $day = (int) $date_array[2];
            $args = array();
            $args['year'] = $year;
            $args['monthnum'] = $month;
            $args['day'] = $day;
            $args['post_type'] = $posttype;
            $args['order'] = 'DESC';
            $args['orderby'] = 'title';
            if ($future == 'checked') {
                $args['post_status'] = array('publish', 'future');
            } else {
                $args['post_status'] = array('publish');
            }
            if ($taxonomy && $term) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $term,
                        'operator' => 'IN'
                    )
                );
            } elseif ($taxonomy) {
                $terms_r = array();
                $terms = get_terms($taxonomy);
                foreach ($terms as $key => $val) {
                    $terms_r[] = $val->term_id;
                }
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'id',
                        'terms' => $terms_r,
                        'operator' => 'IN'
                    )
                );
            }

            $query = new WP_Query($args);
            $posts = $query->posts;

            $output = '<span class="date">' . WP_Cal_convertMonth($month) . ' ' . $day . ', ' . $year . '</span>';
            $output .= '<ul>';

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_title = get_the_title();

                    $author_id = get_the_author_meta('ID');
                    $post_author_url = get_author_posts_url($author_id);
                    $post_author_name = get_the_author();
                    if ($post_author_url) {
                        $post_author_link = '<a href="' . $post_author_url . '" title="' . esc_attr(sprintf(__("Visit %s&#8217;s website"), get_the_author())) . '" rel="author external" onclick="WP_Cal_open_link(\'' . stripslashes($post_author_url) . '\')">' . $post_author_name . '</a>';
                    } else {
                        $post_author_link = $post_author_name;
                    }

                    $c_count = 0;
                    $comment_count = get_comment_count(get_the_ID());
                    if (is_array($comment_count)) {
                        $c_count = $comment_count ['approved'];
                    }
                    if ($taxonomy) {
                        $terms = wp_get_post_terms(get_the_ID(), $taxonomy);
                    }

                    $post_terms = array();
                    $post_terms_str = '';
                    if (is_array($terms)) {
                        foreach ($terms as $term) {
                            $post_terms[] = $term->name;
                        }
                    }
                    if (!empty($post_terms)) {
                        $post_terms_str = implode(', ', $post_terms);
                    }

                    $output .= '<li>
                                    <a class="post_link" href="' . get_permalink() . '" onclick="WP_Cal_open_link(\'' . stripslashes(get_permalink()) . '\')">
                                        <span class="title">' . $post_title . '</span></a>';
                    if ($author == 'checked') {
                        $output .= '<span class="author">Posted by: ' . $post_author_link . '</span>';
                    }
                    if ($show_comment_count == 'checked') {
                        $output .= '<span class="comments">Total comments: ' . $c_count . '</span>';
                    }
                    if ($post_terms_str) {
                        $output .= '<span class="category"> Posted in: ' . $post_terms_str . '</span>';
                    }

                    $output .= '</li>';
                }
            } else {
                $output .= '';
            }
            $output .= '</ul>';
            $posts_div[$date] = $output;
        }
    }
    $wp_cal_content['posts'] = $posts_div;
    die(json_encode($wp_cal_content));
}

add_action('wp_ajax_wp_calendar_get_posts', 'wp_calendar_get_posts');
add_action('wp_ajax_nopriv_wp_calendar_get_posts', 'wp_calendar_get_posts');

/**
 * function returns month name by month number
 *
 * @return string
 */
function WP_Cal_convertMonth($month) {
    $m = array(
        __('January', 'wp_calendar'),
        __('February', 'wp_calendar'),
        __('March', 'wp_calendar'),
        __('April', 'wp_calendar'),
        __('May', 'wp_calendar'),
        __('June', 'wp_calendar'),
        __('July', 'wp_calendar'),
        __('August', 'wp_calendar'),
        __('September', 'wp_calendar'),
        __('October', 'wp_calendar'),
        __('November', 'wp_calendar'),
        __('December', 'wp_calendar')
    );
    if (array_key_exists(($month - 1), $m))
        return $m[($month - 1)];
//    foreach ($m as $key => $value) {
//        if (($key + 1) == $month) {
//            return $value;
//        }
//    }
}

/**
 * function to add configuration on pluign activation
 */
function WP_Cal_activate() {
    //Copy wp_calendar.css in current theme directory if not exists.
    if (!file_exists(WP_CALENDAR_THEME_CSS_PATH)) {
        $handle = @fopen(WP_CALENDAR_PLUGIN_CSS_PATH, "r");
        $contents = @fread($handle, filesize(WP_CALENDAR_PLUGIN_CSS_PATH));
        @fclose($handle);
        if (!( $handle = @fopen(WP_CALENDAR_THEME_CSS_PATH, 'w') ))
            return false;
        @fwrite($handle, $contents);
        @fclose($handle);
        chmod(WP_CALENDAR_THEME_CSS_PATH, octdec(644));
    }
}

/**
 * function to remove configuration on pluign deactivate
 */
function WP_Cal_deactivate() {
    
}

register_activation_hook(__FILE__, 'WP_Cal_activate'); // run WP_Cal_activate at plugin activation  
register_deactivation_hook(__FILE__, 'WP_Cal_deactivate'); // run WP_Cal_deactivate at plugin deactivation  
?>