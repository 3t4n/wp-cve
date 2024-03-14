<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

if ( ! class_exists( 'sfwa_credential' ) ) {
    class sfwa_credential extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
        public function __construct() {
            $widget_ops = array( 
                'classname' => 'sfwa_credential',
                'description' => __( 'Used in credential footer area', 'timewatch' ),
            );
            parent::__construct( 'sfwa_credential', __('SFWA Credential Footer', 'timewatch'), $widget_ops );
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
            echo '<strong class="sfwa-copyright">';
            echo $instance['credit'];
            echo '</strong>';
            if($instance['menu']){
            wp_nav_menu(
						array(
							'menu'              => $instance['menu'],
							'depth'             => 1,
							'container'         => '',
							'container_class'   => '',
							'menu_class' 		=> 'sfwa-creditibility-menu',
							'menu_id'			=> 'sfwa-credit-menu',
                            'fallback_cb' => '__return_false'
						)
					);
            }
            echo '<div id="sfwa-official"><a class="logo-icn" href="'.$instance['imagelink'].'">';
            echo '<img src="'.$instance['image'].'">';
            echo '</a></div>';

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
            $instance['credit'] 	= strip_tags( $new_instance['credit'] );
            $instance['menu'] 	= strip_tags( $new_instance['menu'] );
            $instance['image'] 	= strip_tags( $new_instance['image'] );
            $instance['imagelink'] 	= strip_tags( $new_instance['imagelink'] );
            return $instance;
        }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
        function form( $instance ) {

            /**
             * Sewf widget
             * get the all variable simple footer widget area
             * @since 2.0.5
             */
            $sewf_credit    = ( ! empty( $instance['credit'] ) ) ? wp_kses_post( $instance['credit'] ) : ''; 
            $sewf_menu      = ( ! empty( $instance['menu'] ) ) ? wp_kses_post( $instance['menu'] ) : ''; 
            $sewf_image     = ( ! empty( $instance['image'] ) ) ? wp_kses_post( $instance['image'] ) : ''; 
            $sewf_imagelink = ( ! empty( $instance['imagelink'] ) ) ? wp_kses_post( $instance['imagelink'] ) : ''; 
            
    ?>
        <div class="sfwa-widget-wrapper">
            <p>
                <label for="<?php echo $this->get_field_id('credit'); ?>"><?php _e('Copyright Text:',SFWA_TEXT_DOMAIN); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('credit'); ?>" name="<?php echo $this->get_field_name('credit'); ?>" value="<?php echo esc_html($sewf_credit); ?>" />
            </p>
            <p>
                <label><?php _e('Select menu:',SFWA_TEXT_DOMAIN); ?></label>
                <select name="<?php echo $this->get_field_name('menu'); ?>">
                    <option value="0">— Select —</option>
                <?php
                $menus = wp_get_nav_menus();
                foreach($menus as $menu){ ?>
                    <option value="<?php echo $menu->name; ?>" <?php selected($sewf_menu, $menu->name); ?>><?php echo $menu->name;  ?></option>
                <?php  } ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Logo URL:',SFWA_TEXT_DOMAIN); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo esc_url($sewf_image); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('imagelink'); ?>"><?php _e('Logo Link:',SFWA_TEXT_DOMAIN); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('imagelink'); ?>" name="<?php echo $this->get_field_name('imagelink'); ?>" value="<?php echo esc_url($sewf_imagelink); ?>" />
            </p>
            
        </div>

        <?php
        }
    }
}
?>