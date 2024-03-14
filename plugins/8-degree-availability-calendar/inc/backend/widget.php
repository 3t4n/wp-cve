<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 * Adds AccessPress Social Icons Widget
 */
class EDAC_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'edac_widget', // Base ID
                __('Availability Calendar', 'edac-plugin'), // Name
                array('description' => __('Availability Calendar Widget', 'edac-plugin')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {

        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        if(isset($instance['layout']) && $instance['layout']!='')
        {
            echo do_shortcode('[edac-availability layout="'.$instance['layout'].'"]');
        }
        else
        {
            echo do_shortcode('[edac-availability]');   
        }
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = isset($instance['title'])?$instance['title']:'';
        $layout = isset($instance['layout'])?$instance['layout']:'';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'edac-plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Layout:', 'edac-plugin'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>" >
                <option value="">Default</option>
                <?php for($i=1;$i<=2;$i++){
                    ?>
                    <option value="<?php echo $i;?>" <?php selected($layout,$i);?>>Layout <?php echo $i;?></option>
                    <?php
                }?>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        //die(print_r($new_instance));
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['layout'] = (!empty($new_instance['layout']) ) ? strip_tags($new_instance['layout']) : '';
        return $instance;
    }

} // class EDAC_Widget
