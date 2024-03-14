<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
if ( ! class_exists( 'sfwa_footer_Widget' ) ) {
class sfwa_footer_Widget extends WP_Widget {

/**
 * Sets up the widgets name etc
 */
    public function __construct() {
		$widget_ops = array( 
			'classname' => 'footer_widget',
			'description' => __( 'Use this widget to create footer content.', SFWA_TEXT_DOMAIN ),
		);
		parent::__construct( 'sfwa_footer_Widget', __('SFWA Footer Widget', SFWA_TEXT_DOMAIN), $widget_ops );
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

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
        if( $title ) echo $before_title . $title . $after_title;

        echo '<div class="footer_box">';
        if( $instance['image'] ){
            echo '<div class="image">';
            if($instance['imglink']){
                echo '<a href="'.$instance['imglink'].'">';
            }
            echo '<img class="img-responsive" src="'. $instance['image'] .'" alt="" />';
            if($instance['imglink']){
                echo '</a>';
            }
            echo '</div>';
        }
        if( $instance['text'] ){
            echo '<div class="text">';
            echo $instance['text'];
            echo '</div>';
        }
        echo '</div>';
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

        $instance['title'] 	= strip_tags( $new_instance['title'] );
        $instance['image'] 	= strip_tags( $new_instance['image'] );
        $instance['imglink'] 	= strip_tags( $new_instance['imglink'] );
        $instance['text'] 	= $new_instance['text'];
        return $instance;
    }

/**
 * Outputs the options form on admin
 *
 * @param array $instance The widget options
 */
    function form( $instance ) {
        $title 	= isset( $instance['title']) 	? esc_attr( $instance['title'] ) 	: '';
        $image 	= isset( $instance['image']) 	? esc_attr( $instance['image'] ) 	: '';
        $imglink 	= isset( $instance['imglink']) 	? esc_attr( $instance['imglink'] ) 	: '';
        $text 	= isset( $instance['text']) 	? esc_attr( $instance['text'] ) 	: '';
?>
    <div class="wordpress">
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <div class="image_selector">
            <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>">
                <?php esc_html_e( 'Image URL:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <!-- A hidden input to set and post the chosen image id -->
            <input class="custom-img-id widefat" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" />
            <!-- Your add & remove image links -->
            <p class="hide-if-no-js">
                <button class="upload-custom-img <?php if ( $image  ) { echo 'hidden'; } ?>">
                    <?php esc_html_e('Browse', SFWA_TEXT_DOMAIN) ?>
                </button>
                <button class="delete-custom-img <?php if ( ! $image  ) { echo 'hidden'; } ?>">
                    <?php esc_html_e('Remove', SFWA_TEXT_DOMAIN) ?>
                </button>
            </p>
        </div>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'imglink' ) ); ?>">
                <?php esc_html_e( 'Image Link:', SFWA_TEXT_DOMAIN ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'imglink' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'imglink' ) ); ?>" type="text" value="<?php echo esc_attr( $imglink ); ?>" />
        </p>
        <p>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" placeholder="<?php esc_html_e('Write your content with or without html code.', SFWA_TEXT_DOMAIN);?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" rows="7"><?php echo esc_attr( $text ); ?></textarea>
        </p>
    </div>

    <?php
    }
}
}
?>