<?php

// Creating the widget 
class SharelinkWpWidget extends WP_Widget {
 
    function __construct() {
        parent::__construct(
            // Base ID of your widget
            'sharelink_widget', 
            
            // Widget name will appear in UI
            __('Sharelink Widget', 'sharelink_widget_domain'), 
            
            // Widget description
            array( 'description' => __( 'Put sharelink widget in a sidebar', 'sharelink_widget_domain' ), ) 
        );
    }
     
    // Creating widget front-end
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $sharelink_widget = $instance['sharelink_widget'];

        if (!SharelinkCore::isInstalled()) {
            return false;
        }
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
        ?>
            <div class="sharelink-widget">
                <?php echo do_shortcode('[sharelink '.$sharelink_widget.']'); ?>
            </div>
        <?php
        echo $args['after_widget'];
    }
             
    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }

        if ( isset( $instance[ 'sharelink_widget' ] ) ) {
            $sharelink_widget = $instance[ 'sharelink_widget' ];
        }
        else {
            $sharelink_widget = __( '', 'wpb_widget_domain' );
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'sharelink_widget' ); ?>"><?php _e( 'Share Link Widget:' ); ?></label> 
            <select id="<?php echo $this->get_field_id( 'sharelink_widget' ); ?>" name="<?php echo $this->get_field_name( 'sharelink_widget' ); ?>" type="text" value="<?php echo esc_attr( $sharelink_widget ); ?>" class="widefat">
                <option value=""> -- Select Widget -- </option>
                <?php
                    $api = new SharelinkApi;
                    $widgets = $api->getWidgets();

                    if ($widgets) {
                        foreach ($widgets as $widget) {
                            if (isset($widget['uuid'])) {
                                ?>
                                    <option value="<?php echo $widget['uuid']; ?>" <?php echo esc_attr($sharelink_widget) == $widget['uuid'] ? 'selected' : ''; ?>> <?php echo $widget['name']; ?></option>
                                <?php
                            }
                        }
                    }
                ?>
            </select>
        </p>
        <?php 
    }
         
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['sharelink_widget'] = ( ! empty( $new_instance['sharelink_widget'] ) ) ? strip_tags( $new_instance['sharelink_widget'] ) : '';
        return $instance;
    }
}