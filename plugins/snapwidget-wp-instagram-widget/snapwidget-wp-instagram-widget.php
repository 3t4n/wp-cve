<?php
/**
 * Plugin Name: SnapWidget Social Photo Feed Widget
 * Plugin URI: https://snapwidget.com
 * Description: Used by more than 200,000 websites daily, SnapWidget is the best way to <strong>display your Instagram photos</strong> on your website or blog.
 * Version: 1.1.0
 * Author: SnapWidget
 * License: GPL2
 */

// Register shortcode
add_shortcode( 'snapwidget-instagram-widget', 'snapwidget_instagram_embed_shortcode' );

// Register the widget
add_action( 'widgets_init', 'snapwidget_wpiw_widget' );

// Handle widget widget
function snapwidget_wpiw_widget() {
    register_widget( 'null_snapwidget_instagram_widget' );
}

Class null_snapwidget_instagram_widget extends WP_Widget {

    function __construct() 
    {
        parent::__construct(
            'null-snapwidget-instagram-feed',
            __( 'Instagram', 'snapwidget-wp-instagram-widget' ),
            array(
                'classname' => 'null-snapwidget-instagram-feed',
                'description' => esc_html__( 'A WordPress widget by SnapWidget that lets you display your Instagram photos on your website or blog', 'snapwidget-wp-instagram-widget' ),
                'customize_selective_refresh' => true,
            )
        );
    }

    public function widget($args, $instance)
    {
        $title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
        $id = empty( $instance['id'] ) ? '' : $instance['id'];
        $width = empty( $instance['width'] ) ? '' : $instance['width'];
        $height = empty( $instance['height'] ) ? '' : $instance['height'];
        $lightbox = empty( $instance['lightbox'] ) ? '' : $instance['lightbox'];

        echo $args['before_widget'];

        if ( ! empty( $title ) ) { echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title']; };

        if ($id !== '') {
            echo render_snapwidget_instagram_widget(array(
                'id' => $id,
                'width' => $width,
                'height' => $height,
                'lightbox' => $lightbox
            ));
        }

        echo $args['after_widget'];
    }

    public function form($instance) 
    {
        $instance = wp_parse_args( (array) $instance, array(
            'title' => __( 'Instagram', 'snapwidget-wp-instagram-widget' ),
            'id' => '211514',
            'width' => '100%',
            'height' => '100%',
            'lightbox' => 'false',
        ) );
        $title = $instance['title'];
        $id = $instance['id'];
        $width = $instance['width'];
        $height = $instance['height'];
        $lightbox = $instance['lightbox'];
        ?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'snapwidget-wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_html_e( 'Widget ID', 'snapwidget-wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Width', 'snapwidget-wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height', 'snapwidget-wp-instagram-widget' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" /></label></p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'lightbox' ) ); ?>"><?php esc_html_e( 'Open photos in lightbox', 'snapwidget-wp-instagram-widget' ); ?>:</label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'lightbox' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lightbox' ) ); ?>" class="widefat">
                <option value="false" <?php selected( 'false', $lightbox ); ?>><?php esc_html_e( 'No', 'snapwidget-wp-instagram-widget' ); ?></option>
                <option value="true" <?php selected( 'true', $lightbox ); ?>><?php esc_html_e( 'Yes', 'snapwidget-wp-instagram-widget' ); ?></option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['id'] = $new_instance['id'];
        $instance['width'] = $new_instance['width'];
        $instance['height'] = $new_instance['height'];
        $instance['lightbox'] = $new_instance['lightbox'];
        
        return $instance;
    }
}

// Handle widget shortcode
function snapwidget_instagram_embed_shortcode($atts, $content = null)
{
    // Set defaults
    $values = shortcode_atts(
        array(
            'id' => '211514',
            'width' => '100%',
            'height' => '100%',
            'lightbox' => 'false'
        ), $atts);

    return render_snapwidget_instagram_widget($values);
}

function render_snapwidget_instagram_widget($values)
{
    if ($values['id']) {
        $url = 'https://snapwidget.com/embed/' . $values['id'];

        // For responsive widgets
        if ($values['width'] === '100%' && $values['height'] === '100%') {
            wp_enqueue_script('snapwidget-js', 'https://snapwidget.com/js/snapwidget.js');
        }

        // For pro widgets
        if ($values['lightbox'] === 'true') {
            wp_enqueue_style('snapwidget-lightbox-css', 'https://snapwidget.com/stylesheets/snapwidget-lightbox.css');   
            wp_enqueue_script('snapwidget-lightbox-js', 'https://snapwidget.com/js/snapwidget-lightbox.js');   
        }

        return '<iframe src="' . $url . '" class="snapwidget-widget" 
            allowtransparency="true" frameborder="0" scrolling="no" 
            style="border:none; overflow:hidden; 
            width:' . $values['width'] . ';
            height:' . $values['height'] . ';"></iframe>';    
    }

    return 'Widget ID not found';
}