<?php 
/**
 * Widget Class
 */
add_action('widgets_init', create_function('', 'return register_widget("Widget_Login");'));

class Widget_Login extends WP_Widget {
    /** constructor -- name this the same as the class above */
    function __construct() {
        parent::__construct(false, $name = 'CSH Login');	
    }


    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
        $title = "";
        if ( isset( $instance[ 'title' ] ) ) {
            $title  = esc_attr($instance['title']);
        }
        $login = "";
        if ( isset( $instance[ 'login' ] ) ) {
            $login  = esc_attr($instance['login']);
        }
        $logout = "";
        if ( isset( $instance[ 'logout' ] ) ) {
            $logout  = esc_attr($instance['logout']);
        }	

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('login'); ?>"><?php _e('Login text'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('login'); ?>" name="<?php echo $this->get_field_name('login'); ?>" type="text" value="<?php echo $login; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('logout'); ?>"><?php _e('Logout text'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('logout'); ?>" name="<?php echo $this->get_field_name('logout'); ?>" type="text" value="<?php echo $logout; ?>" />
        </p>
        <?php 
    }


    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
        $instance = $old_instance;
        $instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['login']  = ( ! empty( $new_instance['login'] ) ) ? strip_tags( $new_instance['login'] ) : '';
        $instance['logout'] = ( ! empty( $new_instance['logout'] ) ) ? strip_tags( $new_instance['logout'] ) : '';
        return $instance;
    }


    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) { 
        extract( $args );
        $title  = apply_filters('widget_title', $instance['title']);
        $login  = $instance['login'];
        $logout = $instance['logout'];
        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;

        if (is_user_logged_in()){
            ?>
            <ul>
            <li><a href="<?php echo wp_logout_url()?>"><?php echo $logout; ?></a></li>
            </ul>
            <?php
        }

        if (!is_user_logged_in()) {
            ?>
            <a class="go_to_login_link" href="<?php echo wp_login_url() ?>" ><?php echo $login; ?></a>
            <?php
        }
        echo $after_widget;
    }
} // end class Widget_Login

?>
