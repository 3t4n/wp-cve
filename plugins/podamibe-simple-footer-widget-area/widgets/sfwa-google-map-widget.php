<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
if ( ! class_exists( 'sfwa_google_map_Widget' ) ) {
    class sfwa_google_map_Widget extends WP_Widget {
        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            parent::__construct(
                'sfwa_google_map_Widget', // Base ID
                'SFWA Google Map', // Widget Name
                array(
                    'classname' => 'google-map',
                    'description' => 'Google Map with the link to your profile.',
                ),
                array(
                    'width' => 600,
                )
            );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */

        function widget( $args, $instance ) {
        global $google_map_accounts;
            if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
            extract( $args, EXTR_SKIP );

            echo $before_widget;

            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
            $google_map_embed = ( ! empty( $instance['google_map_embed'] ) ) ? ( $instance['google_map_embed'] ) : ''; 
            
            if( $title ) echo $before_title . $title . $after_title;
            if( !empty( $google_map_embed ) ){
                ?>
                <div class="sfwa-google-map-wraper">
                    <div class="sfwa-google-map">
                        <?php echo $google_map_embed; ?>
                    </div>
                </div>
                <?php
            }
            
            echo $after_widget;
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = $new_instance['title'];
            $instance['google_map_embed'] = $new_instance['google_map_embed'];

            return $instance;
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        function form( $instance ) {
            if(!isset($instance['title'])) { $instance['title'] = ''; }
            
            $google_map_embed = ( ! empty( $instance['google_map_embed'] ) ) ? ( $instance['google_map_embed'] ) : ''; 
    ?>
        <div class="wordpress">
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',SFWA_TEXT_DOMAIN); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
            </p>
            <h3><?php _e('Google Map Embed',SFWA_TEXT_DOMAIN);?></h3>
            <div class="gogle-map-textarea">
                <input class="widefat" type="textarea" id="<?php echo $this->get_field_id('google_map_embed'); ?>" name="<?php echo $this->get_field_name('google_map_embed'); ?>" value='<?php echo  $google_map_embed; ?>' />
            </div>
        </div>
        <?php
        }
    }
}
?>