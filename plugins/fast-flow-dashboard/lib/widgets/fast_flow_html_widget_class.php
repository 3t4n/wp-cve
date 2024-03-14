<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//session_start();



		add_filter('ff_set_widgets','fast_flow_register_html_widget',12,1);

		function fast_flow_register_html_widget($widget){

			$widget[] = new Fast_Flow_Html_Widget();

			return $widget;

		}






class Fast_Flow_Html_Widget extends WP_Widget {



    /**
     *
     * Sets up the widgets name etc
     */


    public function __construct() {

        $widget_ops = array(

            'classname' => 'fast_flow_html_widget',

            'description' => 'Html description'

        );



        parent::__construct( 'fast_flow_html_widget', 'HTML', $widget_ops );


    }





    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */

    public function widget( $args, $instance ) {
        $widget_id = $args['widget_id'];
        $editor_content = esc_attr($instance[ 'wp_editor_html' ]);
        echo '<div id="'.$widget_id.'">';
        echo $editor_content;
        echo '</div>';

    }





    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */

    public function form( $instance ) {

		//print "<pre>";print_r($instance);

        // outputs the options form on admin

        $title = ! empty( $instance['title'] ) ? esc_attr($instance['title']) : __( 'HTML', 'text_domain' );
        $wp_editor_html = ! empty( $instance['wp_editor_html'] ) ? esc_attr($instance['wp_editor_html']) : __( '', 'text_domain' );

		//$ff_from = ! empty( $instance['ff_from'] ) ? $instance['ff_from'] :'';

		//$ff_to = ! empty( $instance['ff_to'] ) ? $instance['ff_to'] :'';

        ?>

            <p>

				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>

				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

            </p>

            <p>

				<label for="<?php echo $this->get_field_id( 'wp_editor_html' ); ?>"><?php _e( 'Description:' ); ?></label>

				<textarea class="widefat" id="<?php echo $this->get_field_id( 'wp_editor_html' ); ?>" name="<?php echo $this->get_field_name( 'wp_editor_html' ); ?>" rows="5" cols="5"><?php echo esc_attr( $wp_editor_html ); ?></textarea>

            </p>



		<?php

    }





    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */

    public function update( $new_instance, $old_instance ) {

        // processes widget options to be saved


				
        foreach( $new_instance as $key => $value )

        {
		        $updated_instance[$key] = ($value);

        }




        return $updated_instance;

    }

}
