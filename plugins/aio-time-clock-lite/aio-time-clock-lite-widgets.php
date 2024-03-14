<?php
class AIO_Time_Clock_Lite_Widgets extends WP_Widget
{

    public function __construct()
    {
        // Instantiate the parent object
        parent::__construct(false, 'Time Clock Widget');
    }

    public function widget($args, $instance)
    {
        // Widget output
        $current_user = wp_get_current_user();
        $user = new WP_User($current_user->ID);
        $these_roles = $user->roles;

        $tc = new AIO_Time_Clock_Lite_Actions();

        if (function_exists('is_plugin_active')) {
            if (is_plugin_active('aio-timeclock-google-analytics/aio-timeclock-google-analytics.php')) {
                aio_google_show_code();
            }
        }

        //load options
        $widgettitle = get_option('aio_tc_lite_widgettitle');

        //widget output
        echo esc_attr($args['before_widget']);

        echo esc_attr($args['before_title']);
        echo esc_attr($widgettitle);
        echo esc_attr($args['after_title']);

        echo '<style>';
        if (get_option("aio_timeclock_text_align") != "none" && get_option("aio_timeclock_text_align") != null && get_option("aio_timeclock_text_align") != '') {
            echo '#aio_time_clock{ text-align:' . get_option("aio_timeclock_text_align") . ';}';
            echo '#avatar{ float:inherit; }';
        }
        echo '</style>';
        echo '<div id="aio_time_clock_widget">';
        if (is_user_logged_in()) {
            if ($tc->checkPermission($these_roles)) {
                $this->getTimeClockWidget();
            } else {
                echo '<p><i> ' . esc_attr_x('Your user group does not have permission to view the Time Clock.', 'aio-time-clock') . '</i></p>';
            }
        } else {
            echo '<p><i>' . esc_attr_x('Time Clock is only available for users that are logged in', 'aio-time-clock') . '</i></p>';
            echo '<a class="aio_tc_widget_button" href="' . esc_url(wp_login_url) . '">' . esc_attr_x('Login', 'aio-time-clock') . '</a>';
        }
        echo '</div><!--#aio_time_clock_widget-->';

        echo '</div>'; //close div.textwidget
        echo esc_attr($args['after_widget']);
    }

    public function update($new_instance, $old_instance)
    {
        // Save widget options
        // Output admin widget options form
        if (isset($_POST['aio_widget_submitted'])) {
            update_option('aio_time_clock_lite_widget_title', santize_text_field($_POST['aio_tc_lite_widgettitle']));
        }
    }

    public function form($instance)
    {
        $widgettitle = sanitize_text_field(get_option('aio_time_clock_lite_widget_title'));
        ?>
        <div class="form-group" style="padding: 10px;">
            <strong><?php echo esc_attr_x('Time Clock Widget Title', 'aio-time-clock'); ?>:</strong>
            <input type="text" class="widefat" name="aio_tc_widgettitle" value="<?php echo esc_attr($widgettitle); ?>" />
        </div>
        <hr>
        <input type="hidden" name="aio_widget_submitted" value="1" />
        <?php
}

    public function getTimeClockWidget()
    {
        include "templates/widget-content.php";
    }
}