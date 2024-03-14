<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

class sfwa_button_Widget extends WP_Widget {

/**
 * Sets up the widgets name etc
 */
    public function __construct() {
		$widget_ops = array( 
			'classname' => 'sfwa_button_widget',
			'description' => __( 'Use this widget to add button link to footer', SFWA_TEXT_DOMAIN ),
		);
		parent::__construct( 'sfwa_button_widget', __('SFWA Button', SFWA_TEXT_DOMAIN), $widget_ops );
	}

/**
 * Outputs the content of the widget
 *
 * @param array $args
 * @param array $instance
 */

    function widget( $args, $instance ) {

        if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
        extract( $args, EXTR_SKIP );

        echo $before_widget;
        
        if( $instance['text'] ){
            echo '<button class="'.$instance['class'].'"><a href="'.$instance['link'] .'">'.$instance['text'].'</a></button>';
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
        $instance = $old_instance;
        $instance['link'] 	= strip_tags( $new_instance['link'] );
        $instance['text'] 	= $new_instance['text'];
        $instance['class'] 	= $new_instance['class'];
        return $instance;
    }

/**
 * Outputs the options form on admin
 *
 * @param array $instance The widget options
 */
    function form( $instance ) {
        $link 	= isset( $instance['link']) 	? esc_attr( $instance['link'] ) 	: '';
        $text 	= isset( $instance['text']) 	? esc_attr( $instance['text'] ) 	: '';
        $class 	= isset( $instance['class']) 	? esc_attr( $instance['class'] ) 	: '';
?>
    <div class="wordpress">
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>">
                <?php esc_html_e( 'Button Text:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" rows="7" value="<?php echo esc_attr( $text ); ?>"/>
        </p>
        <p class="link">
            <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>">
                <?php esc_html_e( 'Button Link:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
        </p>
        <p class="class">
            <label for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>">
                <?php esc_html_e( 'Button Class: ', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>" type="text" value="<?php echo esc_attr( $class ); ?>" placeholder="<?php _e('Optional',SFWA_TEXT_DOMAIN) ?>"/>
        </p>
    </div>

    <?php
    }
}
?>