<?php

// Class for Tweet Widget
class Gs_Twitter_Tweet_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'gs_tweet_widget', // Base ID
            __( 'GS Twitter Feeds', 'gstwf' ), // Name
            array( 'description' => __( 'Display GS Twitter Tweets', 'gstwf' ), ) // Args
        );
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        extract($instance);
   
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Twitter Feeds', 'gstwf' );
        $tweet_nember= ! empty( $instance['tweet_number'] ) ? $instance['tweet_number'] :'3 ';
        $username = ! empty( $instance['user_name'] ) ? $instance['user_name'] : __( ' ', 'gstwf' );
        $hash_tag = ! empty( $instance['hash_tag'] ) ? $instance['hash_tag'] : __( ' ', 'gstwf' );
       
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Tweet Widget Title:' ); ?></label>
        <input
            class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
            name="<?php echo $this->get_field_name( 'title' ); ?>"
            type="text" value="<?php if( isset($title) ) echo esc_attr( $title ); ?>">
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'user_name' ); ?>"><?php _e( 'Twitter User name:' ); ?></label>
        <input
            class="widefat" id="<?php echo $this->get_field_id( 'user_name' ); ?>"
            name="<?php echo $this->get_field_name( 'user_name' ); ?>"
            type="text" value="<?php if( isset($user_name) ) echo esc_attr( $user_name ); ?>">
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'hash_tag' ); ?>"><?php _e( 'Twitter Hash Tag:' ); ?></label>
        <input
            class="widefat" id="<?php echo $this->get_field_id( 'hash_tag' ); ?>"
            name="<?php echo $this->get_field_name( 'hash_tag' ); ?>"
            type="text" value="<?php if( isset($hash_tag) ) echo esc_attr( $hash_tag ); ?>">
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'tweet_number' ); ?>"><?php _e( 'Number Of Tweet:' ); ?></label>
        <input
            class="widefat" id="<?php echo $this->get_field_id( 'tweet_number' ); ?>"
            name="<?php echo $this->get_field_name( 'tweet_number' ); ?>"
            type="text" value="<?php if( isset($tweet_number) ) echo esc_attr( $tweet_number ); ?>">
        </p>  

        <?php
    }


    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract($args);
        extract($instance);

        echo $before_widget;
        if(!empty($title)){
            echo $before_title . $title . $after_title;
        }
            
        if(!empty($instance['user_name'])) {
            echo do_shortcode( '[gs_tweet_widget username='.$instance['user_name'].' tweet_number ='.$instance['tweet_number'].']');
        }
        if(!empty($instance['hash_tag'])){
            echo do_shortcode( '[gs_tweet_widget hashtag='.$instance['hash_tag'].' tweet_number ='.$instance['tweet_number'].']');
        }
    
        echo $after_widget;
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['user_name'] = ( ! empty( $new_instance['user_name'] ) ) ? strip_tags( $new_instance['user_name'] ) : '';
        $instance['hash_tag'] = ( ! empty( $new_instance['hash_tag'] ) ) ? strip_tags( $new_instance['hash_tag'] ) : '';
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['tweet_number'] = ( ! empty( $new_instance['tweet_number'] ) ) ? strip_tags( $new_instance['tweet_number'] ) : '';
       
        return $instance;
    }
} 

function register_Gs_Twitter_Profile_CardWidget() {
    register_widget( 'Gs_Twitter_Tweet_Widget' );
}
add_action( 'widgets_init', 'register_Gs_Twitter_Profile_CardWidget' );