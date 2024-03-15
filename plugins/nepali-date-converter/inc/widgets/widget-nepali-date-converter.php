<?php
/*Make sure we don't expose any info if called directly*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if ( ! class_exists( 'Widget_NDC' ) ) {
    /**
     * Class for adding widget
     *
     * @package Coder Customizer Framework
     * @since 1.0
     */
    class Widget_NDC extends WP_Widget {
        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'widget_nepali_date_converter',
                /*Widget name will appear in UI*/
                __('NDC: Nepali Date Converter', 'nepali-date-converter'),
                /*Widget description*/
                array( 'description' => __( 'Easily convert English Date to Nepali Date and Vice Versa', 'nepali-date-converter' ), )
            );
            /*load scripts for date converting*/
            if(is_active_widget(false, false, $this->id_base)) {
                add_action( 'wp_enqueue_scripts', 'ndc_widget_scripts' );
                add_action('wp_footer', 'ndc_widget_wp_footer');
            }
        }
        /*Widget Backend*/
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Nepali Date Converter', 'nepali-date-converter' );
            }

            if ( isset( $instance[ 'disable_ndc_convert_nep_to_eng' ] ) ) {
                $disable_ndc_convert_nep_to_eng = $instance[ 'disable_ndc_convert_nep_to_eng' ];
            }
            else {
                $disable_ndc_convert_nep_to_eng = '';
            }

            if ( isset( $instance[ 'disable_ndc_convert_eng_to_nep' ] ) ) {
                $disable_ndc_convert_eng_to_nep = $instance[ 'disable_ndc_convert_eng_to_nep' ];
            }
            else {
                $disable_ndc_convert_eng_to_nep = '';
            }

            if ( isset( $instance[ 'nepali_date_converter_result_format' ] ) ) {
                $nepali_date_converter_result_format = $instance[ 'nepali_date_converter_result_format' ];
            }
            else {
                $nepali_date_converter_result_format = 'D, F j, Y';
            }

            if ( isset( $instance[ 'nepali_date_lang' ] ) ) {
                $nepali_date_lang = $instance[ 'nepali_date_lang' ];
            }
            else {
                $nepali_date_lang = 'nep_char';
            }

            if ( isset( $instance[ 'nep_to_eng_button_text' ] ) ) {
                $nep_to_eng_button_text = $instance[ 'nep_to_eng_button_text' ];
            }
            else {
                $nep_to_eng_button_text = __('Nepali to English','nepali-date-converter');
            }

            if ( isset( $instance[ 'eng_to_nep_button_text' ] ) ) {
                $eng_to_nep_button_text = $instance[ 'eng_to_nep_button_text' ];
            }
            else {
                $eng_to_nep_button_text = __('English to Nepali','nepali-date-converter');
            }
            /*Widget admin form*/
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Enter title:' ,'nepali-date-converter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <input id="<?php echo $this->get_field_id('disable_ndc_convert_nep_to_eng'); ?>" name="<?php echo $this->get_field_name('disable_ndc_convert_nep_to_eng'); ?>" type="checkbox" value="1" <?php checked( 1, $disable_ndc_convert_nep_to_eng ); ?> />
                <label for="<?php echo $this->get_field_id('disable_ndc_convert_nep_to_eng'); ?>"><?php _e('Disable convert nep to eng', 'nepali-date-converter'); ?></label><br>

                <input id="<?php echo $this->get_field_id('disable_ndc_convert_eng_to_nep'); ?>" name="<?php echo $this->get_field_name('disable_ndc_convert_eng_to_nep'); ?>" type="checkbox" value="1" <?php checked( '1', $disable_ndc_convert_eng_to_nep ); ?> />
                <label for="<?php echo $this->get_field_id('disable_ndc_convert_eng_to_nep'); ?>"><?php _e('Disable convert eng to nep', 'nepali-date-converter'); ?></label><br>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'nepali_date_converter_result_format' ); ?>"><?php _e( 'Result format' ,'nepali-date-converter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'nepali_date_converter_result_format' ); ?>" name="<?php echo $this->get_field_name( 'nepali_date_converter_result_format' ); ?>" type="text" value="<?php echo esc_attr( $nepali_date_converter_result_format ); ?>" placeholder="<?php _e( '1,25,9' ,'nepali-date-converter'); ?>"/>
                <small><?php printf( esc_html__( 'By default the date for is %s. Other %sdate format%s also supported except "S"', 'nepali-date-converter' ), '<code style="background: #ddd;display: inline;padding: 5px">D, F j, Y</code>',"<a href='https://codex.wordpress.org/Formatting_Date_and_Time' target='_blank'>","</a>" ) ?></small>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'nepali_date_lang' ); ?>"><?php _e( 'Nepali date language' ,'nepali-date-converter'); ?></label>
                <select class="widefat" name="<?php echo $this->get_field_name( 'nepali_date_lang' ); ?>" id="<?php echo $this->get_field_id( 'nepali_date_lang' ); ?>">
                    <?php
                    $nepali_date_lang_array = array(
                      'nep_char' => __('Nepali', 'nepali-date-converter'),
                      'eng_char' => __('English', 'nepali-date-converter'),
                    );
                    foreach( $nepali_date_lang_array as $nepali_date_value=>$nepali_date_option ){
                        echo "<option value='".$nepali_date_value."' ".selected( $nepali_date_value, $nepali_date_lang, 0 ).">".$nepali_date_option."</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'nep_to_eng_button_text' ); ?>"><?php _e( 'Nepali to english button text' ,'nepali-date-converter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'nep_to_eng_button_text' ); ?>" name="<?php echo $this->get_field_name( 'nep_to_eng_button_text' ); ?>" type="text" value="<?php echo esc_attr( $nep_to_eng_button_text ); ?>" placeholder="<?php _e( '1,25,9' ,'nepali-date-converter'); ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'eng_to_nep_button_text' ); ?>"><?php _e( 'English to Nepali button text' ,'nepali-date-converter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'eng_to_nep_button_text' ); ?>" name="<?php echo $this->get_field_name( 'eng_to_nep_button_text' ); ?>" type="text" value="<?php echo esc_attr( $eng_to_nep_button_text ); ?>" placeholder="<?php _e( 'Select option' ,'nepali-date-converter'); ?>"/>
            </p>
            <?php
        }

        /**
         * Function to Updating widget replacing old instances with new
         *
         * @access public
         * @since 1.0
         *
         * @param array $new_instance new arrays value
         * @param array $old_instance old arrays value
         * @return array
         *
         */
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['disable_ndc_convert_nep_to_eng'] = ( ! empty( $new_instance['disable_ndc_convert_nep_to_eng'] ) ) ? strip_tags( $new_instance['disable_ndc_convert_nep_to_eng'] ) : '';
            $instance['disable_ndc_convert_eng_to_nep'] = ( ! empty( $new_instance['disable_ndc_convert_eng_to_nep'] ) ) ? strip_tags( $new_instance['disable_ndc_convert_eng_to_nep'] ) : '';
            $instance['nepali_date_converter_result_format'] = ( ! empty( $new_instance['nepali_date_converter_result_format'] ) ) ? sanitize_text_field( $new_instance['nepali_date_converter_result_format'] ) : '';
            $instance['nepali_date_lang'] = ( ! empty( $new_instance['nepali_date_lang'] ) ) ? sanitize_text_field( $new_instance['nepali_date_lang'] ) : '';
            $instance['nep_to_eng_button_text'] = ( ! empty( $new_instance['nep_to_eng_button_text'] ) ) ? sanitize_text_field( $new_instance['nep_to_eng_button_text'] ) : '';
            $instance['eng_to_nep_button_text'] = ( ! empty( $new_instance['eng_to_nep_button_text'] ) ) ? sanitize_text_field( $new_instance['eng_to_nep_button_text'] ) : '';

            return $instance;
        }
        /**
         * Function to Creating widget front-end. This is where the action happens
         *
         * @access public
         * @since 1.0
         *
         * @param array $args widget setting
         * @param array $instance saved values
         * @return array
         *
         */
        public function widget( $args, $instance ) {
	        if ( isset( $instance[ 'title' ] ) ) {
		        $title = $instance[ 'title' ];
	        }
	        else {
		        $title = __( 'Nepali Date Converter', 'nepali-date-converter' );
	        }

	        if ( isset( $instance[ 'disable_ndc_convert_nep_to_eng' ] ) ) {
		        $disable_ndc_convert_nep_to_eng = $instance[ 'disable_ndc_convert_nep_to_eng' ];
	        }
	        else {
		        $disable_ndc_convert_nep_to_eng = '';
	        }

	        if ( isset( $instance[ 'disable_ndc_convert_eng_to_nep' ] ) ) {
		        $disable_ndc_convert_eng_to_nep = $instance[ 'disable_ndc_convert_eng_to_nep' ];
	        }
	        else {
		        $disable_ndc_convert_eng_to_nep = '';
	        }

	        if ( isset( $instance[ 'nepali_date_converter_result_format' ] ) ) {
		        $nepali_date_converter_result_format = $instance[ 'nepali_date_converter_result_format' ];
	        }
	        else {
		        $nepali_date_converter_result_format = 'D, F j, Y';
	        }

	        if ( isset( $instance[ 'nepali_date_lang' ] ) ) {
		        $nepali_date_lang = $instance[ 'nepali_date_lang' ];
	        }
	        else {
		        $nepali_date_lang = 'nep_char';
	        }

	        if ( isset( $instance[ 'nep_to_eng_button_text' ] ) ) {
		        $nep_to_eng_button_text = $instance[ 'nep_to_eng_button_text' ];
	        }
	        else {
		        $nep_to_eng_button_text = __('Nepali to English','nepali-date-converter');
	        }

	        if ( isset( $instance[ 'eng_to_nep_button_text' ] ) ) {
		        $eng_to_nep_button_text = $instance[ 'eng_to_nep_button_text' ];
	        }
	        else {
		        $eng_to_nep_button_text = __('English to Nepali','nepali-date-converter');
	        }

	        $title = apply_filters( 'widget_title', $title);

	        ndc_frontend()->nepali_date_converter(array(
                'before'=> $args['before_widget'],
                'after'=> $args['after_widget'],
                'before_title'=> $args['before_title'],
                'after_title'=> $args['after_title'],
                'title'=> $title,
                'disable_ndc_convert_nep_to_eng'=> $disable_ndc_convert_nep_to_eng,
                'disable_ndc_convert_eng_to_nep'=> $disable_ndc_convert_eng_to_nep,
                'nepali_date_lang'=> $nepali_date_lang,
                'nep_to_eng_button_text'=> $nep_to_eng_button_text,
                'eng_to_nep_button_text'=> $eng_to_nep_button_text,
                'result_format'=> $nepali_date_converter_result_format,
            ));
        }
    } // Class Widget_NDC ends here

}

if ( ! function_exists( 'coder_widget_nepali_date_converter' ) ) :
    /**
     * Function to Register and load the widget
     *
     * @since 1.0
     *
     * @param null
     * @return null
     *
     */
    function coder_widget_nepali_date_converter() {
        register_widget( 'Widget_NDC' );
    }
endif;
add_action( 'widgets_init', 'coder_widget_nepali_date_converter' );