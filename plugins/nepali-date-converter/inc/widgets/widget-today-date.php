<?php
/*Make sure we don't expose any info if called directly*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if ( ! class_exists( 'Widget_NDC_Today' ) ) {
    /**
     * Class for adding widget
     *
     * @package Coder Customizer Framework
     * @since 1.0
     */
    class Widget_NDC_Today extends WP_Widget {
        function __construct() {
            parent::__construct(
            /*Base ID of your widget*/
                'widget_ndc_today',
                /*Widget name will appear in UI*/
                __('NDC: Today Date', 'nepali-date-converter'),
                /*Widget description*/
                array( 'description' => __( 'Show Today English Date / Nepali Date', 'nepali-date-converter' ), )
            );
        }
        /*Widget Backend*/
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'Date', 'nepali-date-converter' );
            }

            if ( isset( $instance[ 'disable_today_nep_date' ] ) ) {
                $disable_today_nep_date = $instance[ 'disable_today_nep_date' ];
            }
            else {
                $disable_today_nep_date = '';
            }

            if ( isset( $instance[ 'disable_today_eng_date' ] ) ) {
                $disable_today_eng_date = $instance[ 'disable_today_eng_date' ];
            }
            else {
                $disable_today_eng_date = '';
            }

            if ( isset( $instance[ 'ndc_today_result_format' ] ) ) {
                $ndc_today_result_format = $instance[ 'ndc_today_result_format' ];
            }
            else {
                $ndc_today_result_format = 'D, F j, Y';
            }

            if ( isset( $instance[ 'nepali_date_lang' ] ) ) {
                $nepali_date_lang = $instance[ 'nepali_date_lang' ];
            }
            else {
                $nepali_date_lang = 'nep_char';
            }

            /*Widget admin form*/
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Enter title:' ,'nepali-date-converter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <input id="<?php echo $this->get_field_id('disable_today_nep_date'); ?>" name="<?php echo $this->get_field_name('disable_today_nep_date'); ?>" type="checkbox" value="1" <?php checked( 1, $disable_today_nep_date ); ?> />
                <label for="<?php echo $this->get_field_id('disable_today_nep_date'); ?>"><?php _e('Disable today nep date', 'nepali-date-converter'); ?></label><br>

                <input id="<?php echo $this->get_field_id('disable_today_eng_date'); ?>" name="<?php echo $this->get_field_name('disable_today_eng_date'); ?>" type="checkbox" value="1" <?php checked( '1', $disable_today_eng_date ); ?> />
                <label for="<?php echo $this->get_field_id('disable_today_eng_date'); ?>"><?php _e('Disable today eng date', 'nepali-date-converter'); ?></label><br>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'ndc_today_result_format' ); ?>"><?php _e( 'Result format' ,'nepali-date-converter'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'ndc_today_result_format' ); ?>" name="<?php echo $this->get_field_name( 'ndc_today_result_format' ); ?>" type="text" value="<?php echo esc_attr( $ndc_today_result_format ); ?>" placeholder="<?php _e( '1,25,9' ,'nepali-date-converter'); ?>"/>
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
            $instance['disable_today_nep_date'] = ( ! empty( $new_instance['disable_today_nep_date'] ) ) ? strip_tags( $new_instance['disable_today_nep_date'] ) : '';
            $instance['disable_today_eng_date'] = ( ! empty( $new_instance['disable_today_eng_date'] ) ) ? strip_tags( $new_instance['disable_today_eng_date'] ) : '';
            $instance['ndc_today_result_format'] = ( ! empty( $new_instance['ndc_today_result_format'] ) ) ? sanitize_text_field( $new_instance['ndc_today_result_format'] ) : '';
            $instance['nepali_date_lang'] = ( ! empty( $new_instance['nepali_date_lang'] ) ) ? sanitize_text_field( $new_instance['nepali_date_lang'] ) : '';

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
		        $title = __( 'Date', 'nepali-date-converter' );
	        }

	        if ( isset( $instance[ 'disable_today_nep_date' ] ) ) {
		        $disable_today_nep_date = $instance[ 'disable_today_nep_date' ];
	        }
	        else {
		        $disable_today_nep_date = '';
	        }

	        if ( isset( $instance[ 'disable_today_eng_date' ] ) ) {
		        $disable_today_eng_date = $instance[ 'disable_today_eng_date' ];
	        }
	        else {
		        $disable_today_eng_date = '';
	        }

	        if ( isset( $instance[ 'ndc_today_result_format' ] ) ) {
		        $ndc_today_result_format = $instance[ 'ndc_today_result_format' ];
	        }
	        else {
		        $ndc_today_result_format = 'D, F j, Y';
	        }

	        if ( isset( $instance[ 'nepali_date_lang' ] ) ) {
		        $nepali_date_lang = $instance[ 'nepali_date_lang' ];
	        }
	        else {
		        $nepali_date_lang = 'nep_char';
	        }

	        $title = apply_filters( 'widget_title', $title);

	        ndc_frontend()->today_date(array(
                'before'=> $args['before_widget'],
                'after'=> $args['after_widget'],
                'before_title'=> $args['before_title'],
                'after_title'=> $args['after_title'],
                'title'=> $title,
                'disable_today_nep_date'=> $disable_today_nep_date,
                'disable_today_eng_date'=> $disable_today_eng_date,
                'nepali_date_lang'=> $nepali_date_lang,
                'result_format'=> $ndc_today_result_format,
            ));
        }
    } // Class Widget_NDC_Today ends here

}


if ( ! function_exists( 'coder_widget_ndc_today' ) ) :
    /**
     * Function to Register and load the widget
     *
     * @since 1.0
     *
     * @param null
     * @return null
     *
     */
    function coder_widget_ndc_today() {
        register_widget( 'Widget_NDC_Today' );
    }
endif;
add_action( 'widgets_init', 'coder_widget_ndc_today' );