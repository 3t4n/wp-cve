<?php
/**
 * Plugin Name:       Category Archive Widget
 * Plugin URI:        https://kadalashvili.com
 * Description:       Widget Display an archive listing of one specific category.
 * Version:           1.2
 * Requires at least: 6
 * Tested up to:      6.2.2
 * Requires PHP:      7.4
 * Author:            Kazbek Kadalashvili
 * Author URI:        https://www.upwork.com/o/profiles/users/_~01800759f61b8ffa73/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

 class Category_Archive_Widget extends WP_Widget {
    public function __construct() {
        $widget_des = array("description" => "Display an archive listing of one specific category.");
        parent::__construct("category-archive-widget", "Category Archive", $widget_des);
    }

    public function create_url($interval, $year, $month, $category_id, $category) {
        global $wp_rewrite;
        $year_month_value = $year . $month;
        $year_month_path = $year . "/" . $month;
        if ($interval == "year") {
            $year_month_value = $year;
            $year_month_path = $year . "/00";
        }

        $url = get_option('home') . "/?m=" . $year_month_value . "&cat=" . $category_id;
        // or for category, get_category_link(), but not for both (get_category_month_link()).
        if ($wp_rewrite->using_permalinks()) {
            $cat_pos = strpos($wp_rewrite->permalink_structure, "%category%");
            if ($cat_pos !== false) {
                $year_pos = strpos($wp_rewrite->permalink_structure, "%year%");
                if ($year_pos !== false) {
                    $url = get_option('home') . "/";
                    if ($cat_pos < $year_pos) {
                        $url .= $category . "/" . $year_month_path . "/";
                    } else {
                        $url .=  $year_month_path . "/" . $category . "/";
                    }
                }
            }
        }
        return $url;
    }

    public function widget($args, $instance) {
        extract($args);
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $category_id = isset($instance['category_id']) ? intval($instance['category_id']) : 0;
        $category_info = get_term_by("id", $category_id, "category");
        $category = $category_info->slug;
        $display_style = isset($instance['display_style']) ? $instance['display_style'] : 'list';
        $interval = isset($instance['interval']) ? $instance['interval'] : 'month';
        $show_counts = isset($instance['show_counts']) ? intval($instance['show_counts']) : 0;
        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }
        $myposts = get_posts("numberposts=-1&offset=0&category=" . $category_id . "&orderby=date&order=DESC");
        $previous_year_month_display = "";
        $previous_year_month_value = "";
        $previous_year = "";
        $previous_month = "";
        $count = 0;

        $display_format = "F Y";
        $compare_format = "Ym";
        $select_str = __("Select Month");
        if ($interval == "year") {
            $display_format = "Y";
            $compare_format = "Y";
            $select_str = __("Select Year");
        }

        if ($display_style == "pulldown") {
            echo "<select name=\"wp-category-archive-dropdown\" onchange=\"document.location.href=this.options[this.selectedIndex].value;\">";
            echo " <option value=\"\">" . $select_str . "</option>";
        } elseif ($display_style == "list") {
            echo "<ul>";
        }

        foreach ($myposts as $post) {
            $post_date = strtotime($post->post_date);
            $current_year_month_display = date_i18n($display_format, $post_date);
            $current_year_month_value = date($compare_format, $post_date);
            $current_year = date("Y", $post_date);
            $current_month = date("m", $post_date);
            if ($previous_year_month_value !== $current_year_month_value) {
                if ($count > 0) {
                    $url = $this->create_url($interval, $previous_year, $previous_month, $category_id, $category);
                    if ($display_style == "pulldown") {
                        echo " <option value=\"" . $url . "\"";
                        if (isset($_GET['m']) && $_GET['m'] == $previous_year_month_value) {
                            echo " selected=\"selected\" ";
                        }
                        echo ">" . $previous_year_month_display;
                        if ($show_counts == 1) {
                            echo " (" . $count . ")";
                        }
                        echo "</option>";
                    } elseif ($display_style == "list") {
                        echo "<li><a href=\"" . $url . "\">" . $previous_year_month_display . "</a>";
                        if ($show_counts == 1) {
                            echo " (" . $count . ")";
                        }
                        echo "</li>";
                    } else {
                        echo "<a href=\"" . $url . "\">" . $previous_year_month_display . "</a>";
                        if ($show_counts == 1) {
                            echo " (" . $count . ")";
                        }
                        echo "<br/>";
                    }
                }
                $count = 0;
            }
            $count++;
            $previous_year_month_display = $current_year_month_display;
            $previous_year_month_value = $current_year_month_value;
            $previous_year = $current_year;
            $previous_month = $current_month;
        }

        if ($count > 0) {
            $url = $this->create_url($interval, $previous_year, $previous_month, $category_id, $category);
            if ($display_style == "pulldown") {
                echo " <option value=\"" . $url . "\">" . $previous_year_month_display;
                if ($show_counts == 1) {
                    echo " (" . $count . ")";
                }
                echo "</option>";
            } elseif ($display_style == "list") {
                echo "<li><a href=\"" . $url . "\">" . $previous_year_month_display . "</a>";
                if ($show_counts == 1) {
                    echo " (" . $count . ")";
                }
                echo "</li>";
            } else {
                echo "<a href=\"" . $url . "\">" . $previous_year_month_display . "</a>";
                if ($show_counts == 1) {
                    echo " (" . $count . ")";
                }
                echo "<br/>";
            }
        }

        if ($display_style == "pulldown") {
            echo "</select>";
        } elseif ($display_style == "list") {
            echo "</ul>";
        }

        echo $after_widget;
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance["title"] = isset($new_instance["title"]) ? strip_tags($new_instance["title"]) : '';
        $instance["category_id"] = isset($new_instance["category_id"]) ? intval($new_instance["category_id"]) : 0;
        $instance["display_style"] = isset($new_instance["display_style"]) ? strip_tags($new_instance["display_style"]) : 'list';
        $instance["interval"] = isset($new_instance["interval"]) ? strip_tags($new_instance["interval"]) : 'month';
        $instance["show_counts"] = isset($new_instance["show_counts"]) ? intval($new_instance["show_counts"]) : 1;
        return $instance;
    }

    public function form($instance) {
        global $wpdb;
        $defaults = array(
            "title" => "Category Archive",
            "category_id" => 0,
            "display_style" => "list",
            "interval" => "month",
            "show_counts" => 1
        );
        $instance = wp_parse_args((array)$instance, $defaults);
        $title = esc_attr($instance['title']);
        $category_id = intval($instance['category_id']);
        $display_style = $instance['display_style'];
        $interval = $instance['interval'];
        $show_counts = intval($instance['show_counts']);
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("title") . "\">Title:";
        echo "<input id=\"" . $this->get_field_id("title") . "\" " .
            "name=\"" . $this->get_field_name("title") . "\" " .
            "value=\"" . $title . "\" style=\"width:100%;\" />";
        echo "<label></p>";
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("category_id") . "\">Category:<br/>";
        echo "<select name=\"" . $this->get_field_name("category_id") .
            "\" id=\"" . $this->get_field_id("category_id") . "\">";
        $categories = get_categories("orderby=ID&order=asc");
        foreach ($categories as $cat) {
            $option = "<option value=\"" . $cat->cat_ID . "\"";
            if ($cat->cat_ID == $category_id) {
                $option .= "selected=\"selected\" ";
            }
            $option .= ">";
            $option .= $cat->cat_ID . " : " . $cat->cat_name . " (" . $cat->count . ")";
            $option .= "</option>";
            echo $option;
        }
        echo "</select>";
        echo "</label></p>";
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("display_style") . "\">Display Style:<br/>";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("display_style") . "\" value=\"lines\"";
        if ($display_style == "lines") {
            echo " checked=\"checked\" ";
        }
        echo "> Lines ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("display_style") . "\" value=\"list\"";
        if ($display_style == "list") {
            echo " checked=\"checked\" ";
        }
        echo "> List ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("display_style") . "\" value=\"pulldown\"";
        if ($display_style == "pulldown") {
            echo " checked=\"checked\" ";
        }
        echo "> Dropdown";
        echo "</label></p>";
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("interval") . "\">Group By:<br/>";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("interval") . "\" value=\"month\"";
        if ($interval == "month") {
            echo " checked=\"checked\" ";
        }
        echo "> Month ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("interval") . "\" value=\"year\"";
        if ($interval == "year") {
            echo " checked=\"checked\" ";
        }
        echo "> Year";
        echo "</label></p>";
        echo "<p>";
        echo "<label for=\"" . $this->get_field_id("show_counts") . "\">Show Counts:<br/>";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("show_counts") . "\" value=\"1\"";
        if ($show_counts == 1) {
            echo " checked=\"checked\" ";
        }
        echo "> Yes ";
        echo "<input type=\"radio\" name=\"" . $this->get_field_name("show_counts") . "\" value=\"0\"";
        if ($show_counts == 0) {
            echo " checked=\"checked\" ";
        }
        echo "> No";
        echo "</label></p>";
    }
}

// Register the widget
function register_category_archive_widget() {
    register_widget('Category_Archive_Widget');
}
add_action('widgets_init', 'register_category_archive_widget');

?>
