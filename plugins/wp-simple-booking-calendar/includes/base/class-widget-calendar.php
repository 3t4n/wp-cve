<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPSBC_Widget_Calendar extends WP_Widget
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        $widget_ops = array(
            'classname' => 'wpsbc_calendar',
            'description' => __('Insert a WP Simple Booking Calendar', 'wp-simple-booking-calendar'),
        );

        parent::__construct('wpsbc_calendar', 'WP Simple Booking Calendar', $widget_ops);

    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     *
     */
    public function widget($args, $instance)
    {

        // Remove the "wpsbc" prefix to have a cleaner code
        $instance = (!empty($instance) && is_array($instance) ? $instance : array());

        foreach ($instance as $key => $value) {

            $instance[str_replace('wpsbc_', '', $key)] = $value;
            unset($instance[$key]);

        }

        if(!isset($instance['select_calendar'])){
			return false;
		}

        $calendar = wpsbc_get_calendar(absint($instance['select_calendar']));

        $calendar_args = array(
            'show_title' => (!empty($instance['show_title']) && $instance['show_title'] == 'yes' ? 1 : 0),
            'show_legend' => (!empty($instance['show_legend']) && $instance['show_legend'] == 'yes' ? 1 : 0),
            'legend_position' => (!empty($instance['legend_position']) ? $instance['legend_position'] : 'top'),
            'language' => (!empty($instance['calendar_language']) ? ($instance['calendar_language'] == 'auto' ? wpsbc_get_locale() : $instance['calendar_language']) : 'en'),
        );

		echo $args['before_widget'];

        if (is_null($calendar)) {

            echo '<p>' . __('Calendar does not exist.', 'wp-simple-booking-calendar') . '</p>';

        } else {

            $calendar_outputter = new WPSBC_Calendar_Outputter($calendar, $calendar_args);

            echo $calendar_outputter->get_display();
        }

		echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     *
     */
    public function form($instance)
    {

        global $wpdb;

        $calendar_id = (!empty($instance['wpsbc_select_calendar']) ? $instance['wpsbc_select_calendar'] : 0);
        $show_title = (!empty($instance['wpsbc_show_title']) ? $instance['wpsbc_show_title'] : 'yes');
        $show_legend = (!empty($instance['wpsbc_show_legend']) ? $instance['wpsbc_show_legend'] : 'yes');
        $legend_position = (!empty($instance['wpsbc_legend_position']) ? $instance['wpsbc_legend_position'] : 'top');
        $calendar_language = (!empty($instance['wpsbc_calendar_language']) ? $instance['wpsbc_calendar_language'] : 'en');

        $calendars = wpsbc_get_calendars();

        ?>

        <!-- Calendar -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_select_calendar'); ?>"><?php echo __('Calendar', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_select_calendar'); ?>" id="<?php echo $this->get_field_id('wpsbc_select_calendar'); ?>" class="widefat">
				<?php foreach ($calendars as $calendar): ?>
					<option <?php echo ($calendar->get('id') == $calendar_id ? 'selected="selected"' : ''); ?> value="<?php echo $calendar->get('id'); ?>"><?php echo $calendar->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>

		<!-- Show Title -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_show_title'); ?>"><?php echo __('Display title', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_show_title'); ?>" id="<?php echo $this->get_field_id('wpsbc_show_title'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-simple-booking-calendar'); ?></option>
				<option value="no" <?php echo ($show_title == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-simple-booking-calendar'); ?></option>
			</select>
		</p>

		<!-- Show Legend -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_show_legend'); ?>"><?php echo __('Display legend', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_show_legend'); ?>" id="<?php echo $this->get_field_id('wpsbc_show_legend'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-simple-booking-calendar'); ?></option>
				<option value="no" <?php echo ($show_legend == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-simple-booking-calendar'); ?></option>
			</select>
		</p>

		<!-- Legend Position -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_legend_position'); ?>"><?php echo __('Legend Position', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_legend_position'); ?>" id="<?php echo $this->get_field_id('wpsbc_legend_position'); ?>" class="widefat">
				<option <?php echo ($legend_position == 'side' ? 'selected="selected"' : ''); ?> value="side"><?php echo __('Side', 'wp-simple-booking-calendar'); ?></option>
				<option <?php echo ($legend_position == 'top' ? 'selected="selected"' : ''); ?> value="top"><?php echo __('Top', 'wp-simple-booking-calendar'); ?></option>
				<option <?php echo ($legend_position == 'bottom' ? 'selected="selected"' : ''); ?> value="bottom"><?php echo __('Bottom', 'wp-simple-booking-calendar'); ?></option>
			</select>
		</p>

		<!-- Calendar Language -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_calendar_language'); ?>"><?php echo __('Language', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_calendar_language'); ?>" id="<?php echo $this->get_field_id('wpsbc_calendar_language'); ?>" class="widefat">
				<?php
                $settings = get_option('wpsbc_settings', array());
                $languages = wpsbc_get_languages();
                $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
                ?>

				<option value="auto"><?php echo __('Auto (let WP choose)', 'wp-simple-booking-calendar'); ?></option>

				<?php foreach ($active_languages as $code): ?>
					<option value="<?php echo esc_attr($code); ?>" <?php echo ($calendar_language == $code ? 'selected="selected"' : ''); ?>><?php echo (!empty($languages[$code]) ? $languages[$code] : ''); ?></option>
				<?php endforeach;?>
			</select>
		</p>

        <?php

    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     *
     */
    public function update($new_instance, $old_instance)
    {

        return $new_instance;

    }

}


// Old widget
class WpSimpleBookingCalendar_Widget extends WP_Widget
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        $widget_ops = array(
            'classname' => 'widget-sbc',
            'description' => __('Insert a WP Simple Booking Calendar', 'wp-simple-booking-calendar'),
        );

        parent::__construct(false, 'WP Simple Booking Calendar - Old Widget', $widget_ops);

    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     *
     */
    public function widget($args, $instance)
    {

        // Remove the "wpsbc" prefix to have a cleaner code
        $instance = (!empty($instance) && is_array($instance) ? $instance : array());

        foreach ($instance as $key => $value) {

            $instance[str_replace('wpsbc_', '', $key)] = $value;
            unset($instance[$key]);

        }

        $calendar = wpsbc_get_calendar(1);

        $calendar_args = array(
            'show_title' => (!empty($instance['show_title']) && $instance['show_title'] == 'yes' ? 1 : 0),
            'show_legend' => (!empty($instance['show_legend']) && $instance['show_legend'] == 'yes' ? 1 : 0),
            'legend_position' => (!empty($instance['legend_position']) ? $instance['legend_position'] : 'top'),
            'language' => (!empty($instance['calendar_language']) ? ($instance['calendar_language'] == 'auto' ? wpsbc_get_locale() : $instance['calendar_language']) : 'en'),
        );

        if (is_null($calendar)) {

            echo '<p>' . __('Calendar does not exist.', 'wp-simple-booking-calendar') . '</p>';

        } else {

            echo $args['before_widget'];

            $calendar_outputter = new WPSBC_Calendar_Outputter($calendar, $calendar_args);

            echo $calendar_outputter->get_display();

            echo $args['after_widget'];
        }
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     *
     */
    public function form($instance)
    {

        global $wpdb;

        $calendar_id = (!empty($instance['wpsbc_select_calendar']) ? $instance['wpsbc_select_calendar'] : 1);
        $show_title = (!empty($instance['wpsbc_show_title']) ? $instance['wpsbc_show_title'] : 'yes');
        $show_legend = (!empty($instance['wpsbc_show_legend']) ? $instance['wpsbc_show_legend'] : 'yes');
        $legend_position = (!empty($instance['wpsbc_legend_position']) ? $instance['wpsbc_legend_position'] : 'top');
        $calendar_language = (!empty($instance['wpsbc_calendar_language']) ? $instance['wpsbc_calendar_language'] : 'en');

        $calendars = wpsbc_get_calendars();

        ?>

        <!-- Calendar -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_select_calendar'); ?>"><?php echo __('Calendar', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_select_calendar'); ?>" id="<?php echo $this->get_field_id('wpsbc_select_calendar'); ?>" class="widefat">
				<?php foreach ($calendars as $calendar): ?>
					<option <?php echo ($calendar->get('id') == $calendar_id ? 'selected="selected"' : ''); ?> value="<?php echo $calendar->get('id'); ?>"><?php echo $calendar->get('name'); ?></option>
				<?php endforeach;?>
			</select>
		</p>

		<!-- Show Title -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_show_title'); ?>"><?php echo __('Display title', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_show_title'); ?>" id="<?php echo $this->get_field_id('wpsbc_show_title'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-simple-booking-calendar'); ?></option>
				<option value="no" <?php echo ($show_title == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-simple-booking-calendar'); ?></option>
			</select>
		</p>

		<!-- Show Legend -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_show_legend'); ?>"><?php echo __('Display legend', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_show_legend'); ?>" id="<?php echo $this->get_field_id('wpsbc_show_legend'); ?>" class="widefat">
				<option value="yes"><?php echo __('Yes', 'wp-simple-booking-calendar'); ?></option>
				<option value="no" <?php echo ($show_legend == 'no' ? 'selected="selected"' : ''); ?>><?php echo __('No', 'wp-simple-booking-calendar'); ?></option>
			</select>
		</p>

		<!-- Legend Position -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_legend_position'); ?>"><?php echo __('Legend Position', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_legend_position'); ?>" id="<?php echo $this->get_field_id('wpsbc_legend_position'); ?>" class="widefat">
				<option <?php echo ($legend_position == 'side' ? 'selected="selected"' : ''); ?> value="side"><?php echo __('Side', 'wp-simple-booking-calendar'); ?></option>
				<option <?php echo ($legend_position == 'top' ? 'selected="selected"' : ''); ?> value="top"><?php echo __('Top', 'wp-simple-booking-calendar'); ?></option>
				<option <?php echo ($legend_position == 'bottom' ? 'selected="selected"' : ''); ?> value="bottom"><?php echo __('Bottom', 'wp-simple-booking-calendar'); ?></option>
			</select>
		</p>

		<!-- Calendar Language -->
		<p>
			<label for="<?php echo $this->get_field_id('wpsbc_calendar_language'); ?>"><?php echo __('Language', 'wp-simple-booking-calendar'); ?></label>

			<select name="<?php echo $this->get_field_name('wpsbc_calendar_language'); ?>" id="<?php echo $this->get_field_id('wpsbc_calendar_language'); ?>" class="widefat">
				<?php
                $settings = get_option('wpsbc_settings', array());
                $languages = wpsbc_get_languages();
                $active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
                ?>

				<option value="auto"><?php echo __('Auto (let WP choose)', 'wp-simple-booking-calendar'); ?></option>

				<?php foreach ($active_languages as $code): ?>
					<option value="<?php echo esc_attr($code); ?>" <?php echo ($calendar_language == $code ? 'selected="selected"' : ''); ?>><?php echo (!empty($languages[$code]) ? $languages[$code] : ''); ?></option>
				<?php endforeach;?>
			</select>
		</p>

        <?php

    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     *
     */
    public function update($new_instance, $old_instance)
    {

        return $new_instance;

    }

}

add_action('widgets_init', function () {
    register_widget('WPSBC_Widget_Calendar');
    register_widget('WpSimpleBookingCalendar_Widget');
});