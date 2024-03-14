<?php
// Register and load the widget
function ecsa_load_widget() {
	register_widget( 'EventsCalendarSearchAddonWidget' );
}

add_action( 'widgets_init', 'ecsa_load_widget' );

// Creating the widget
class EventsCalendarSearchAddonWidget extends WP_Widget {

	// this function registers widget with WordPress
	function __construct() {
		parent::__construct(
		// Base ID of your widget
			'EventsCalendarSearchAddonWidget',
			// Widget name will appear in UI
			__( 'Events Search Addon', 'ecsa' ),
			// Widget description
			array( 'description' => __( 'Events Search Addon For The Events Calendar', 'ecsa' ) )
		);
	}


	// Creating widget front-end
	public function widget( $args, $instance ) {
		$show_events         = ( ! empty( $instance['show_events'] ) ) ? ( $instance['show_events'] ) : '5';
		$disable_past_events = ( isset( $instance['disable_past_events'] ) ) ? $instance['disable_past_events'] : 'false';
		$layout              = ( isset( $instance['layout'] ) ) ? ( $instance['layout'] ) : 'small';
		$title               = apply_filters( 'widget_title', $instance['title'] );
		$placeholder         = ( ! empty( $instance['placeholder'] ) ) ? ( $instance['placeholder'] ) : 'Search Events';
		$style_full         = ( ! empty( $instance['content_type'] ) ) ? ( $instance['content_type'] ) : 'advance';
		// before and after widget arguments are defined by themes
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		$allowed_html = array(
			'input'  => array(
				'type'        => array(),
				'class'       => array(),
				'name'        => array(),
				'value'       => array(),
				'id'          => array(),
				'placeholder' => array(),
				'readonly'    => array(),
			),

			'div'    => array(
				'class'                => array(),
				'id'                   => array(),
				'data-no-up-result'    => array(),
				'data-no-past-result'  => array(),
				'data-show-events'     => array(),
				'data-disable-past'    => array(),
				'data-up-ev-heading'   => array(),
				'data-past-ev-heading' => array(),
				'data-sug-style-full' => array(),

			),
			'span'   => array(
				'class' => array(),
				'id'    => array(),
			),
			'img'    => array(
				'src'   => array(),
				'class' => array(),
				'id'    => array(),
			),
			'script' => array(
				'class' => array(),
				'id'    => array(),
				'type'  => array(),
			),
			'a'      => array(
				'class' => array(),
				'id'    => array(),
				'href'  => array(),
			),

		);
		// echo "5";
		echo wp_kses( ecsa_generate_html( $placeholder, $show_events, $disable_past_events, $content_type,$layout ), $allowed_html );
		// echo "6";
		echo wp_kses_post( $args['after_widget'] );
	}

	// Widget Backend
	public function form( $instance ) {
		// Load css for widget only
		wp_enqueue_style( 'ecsa-widget-style', ECSA_URL . 'assets/css/ecsa-widgets.css' );

		if ( ! isset( $instance['placeholder'] ) || empty( $instance['placeholder'] ) ) {
			$placeholder = 'Search Events';
		} else {
			$placeholder = $instance['placeholder'];
		}
		if ( ! isset( $instance['show_events'] ) || empty( $instance['show_events'] ) ) {
			$show_events = '5';
		} else {
			$show_events = $instance['show_events'];
		}

		if ( ! isset( $instance['disable_past_events'] ) ) {
			$instance['disable_past_events'] = 'false'; } else {
			$instance['disable_past_events'] = $instance['disable_past_events'];
			}

			if ( ! isset( $instance['layout'] ) ) {
				$instance['layout'] = 'small'; } else {
				$instance['layout'] = $instance['layout'];
				}
				if ( ! isset( $instance['content_type'] ) ) {
					$instance['content_type'] = 'advance'; } else {
					$instance['content_type'] = $instance['content_type'];
					}

				if ( isset( $instance['title'] ) ) {
					$title = $instance['title'];
				} else {
					$title = __( 'Events Search', 'ecsa' );
				}

				// Widget admin form
				?>

		<p>
			<label class="ecsa-label" for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title :' ); ?></label> 
			<input class="ecsa-input" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label class="ecsa-label" for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"><?php esc_html_e( 'Placeholder :' ); ?></label>
			<input class="ecsa-input" type="text" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>" value="<?php echo esc_attr( $placeholder ); ?>">
		</p>
		   
		<p>
			<label class="ecsa-label" for="<?php echo esc_attr( $this->get_field_id( 'show_events' ) ); ?>"><?php esc_html_e( 'Show Events :' ); ?></label>
			<input class="ecsa-input"  type="text" id="<?php echo esc_attr( $this->get_field_id( 'show_events' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_events' ) ); ?>" value="<?php echo esc_attr( $show_events ); ?>" >		
		</p> 

		<p>
			<label class="ecsa-label" for="<?php echo esc_attr( $this->get_field_id( 'disable_past_events' ) ); ?>"><?php esc_attr_e( 'Disable Past Events :' ); ?></label>
			<select class="ecsa-input" id="<?php echo esc_attr( $this->get_field_id( 'disable_past_events' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'disable_past_events' ) ); ?>" >
				<option <?php selected( $instance['disable_past_events'], 'false' ); ?> value="false">False</option>
				<option <?php selected( $instance['disable_past_events'], 'true' ); ?> value="true">True</option>	
			</select>
		</p>

		<p>
			<label class="ecsa-label" for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_attr_e( 'Layout :' ); ?></label>
			<select class="ecsa-input" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" >
				<option <?php selected( $instance['layout'], 'small' ); ?> value="small">Small</option>
				<option <?php selected( $instance['layout'], 'medium' ); ?> value="medium">Medium</option>
				<option <?php selected( $instance['layout'], 'large' ); ?> value="large">Large</option>
			</select>
		</p>
				
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance                        = array();
		$instance['title']               = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['placeholder']         = strip_tags( $new_instance['placeholder'] );
		$instance['show_events']         = strip_tags( $new_instance['show_events'] );
		$instance['disable_past_events'] = strip_tags( $new_instance['disable_past_events'] );
		$instance['layout']              = strip_tags( $new_instance['layout'] );
		$instance['content_type']              = strip_tags( $new_instance['content_type'] );
		return $instance;
	}


} // Class wpb_widget ends here

