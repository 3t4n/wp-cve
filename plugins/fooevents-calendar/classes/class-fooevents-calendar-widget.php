<?php
/**
 * FooEvents Calendar Widget Class
 *
 * @file    Class responsible for outputing calendar widget
 * @link    https://www.fooevents.com
 * @since   1.0.0
 * @package fooevents-calendar
 */
class Fooevents_Calendar_Widget extends WP_Widget {

	/**
	 * Initialize widget
	 */
	public function __construct() {
		parent::__construct(
			false,
			__( 'FooEvents Calendar', 'fooevents-calendar' ),
			array( 'description' => __( "A calendar or list view of your site's Events.", 'fooevents-calendar' ) )
		);
	}

	/**
	 * Output widget to screen
	 *
	 * @param array $args widget arguments.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {

		$type = '';
		if ( ! empty( $instance['type'] ) ) {

			$type = esc_attr( $instance['type'] );

		}

		$title = '';
		if ( ! empty( $instance['title'] ) ) {

			$title = esc_attr( $instance['title'] );

		}

		$number_of_events = 5;
		if ( ! empty( $instance['number_of_events'] ) ) {

			$number_of_events = esc_attr( $instance['number_of_events'] );

		}

		$sort = 'asc';
		if ( ! empty( $instance['sort'] ) ) {

			$sort = esc_attr( $instance['sort'] );

		}

		$start_date = '';
		if ( ! empty( $instance['start_date'] ) ) {

			$start_date = esc_attr( $instance['start_date'] );

		}

		echo wp_kses_post( ( $args['before_widget'] ) );

		if ( ! empty( $title ) ) {

			echo '<h2 class="widget-title">' . esc_attr( $title ) . '</h2>';

		}

		$default_date_output = '';
		if ( ! empty( $start_date ) ) {

			$default_date_output = 'defaultDate="' . esc_attr( $start_date ) . '"';

		}

		if ( 'Calendar' === $type ) {

			$id = wp_rand( 1111, 9999 );
			do_shortcode( '[fooevents_calendar id="' . $id . '"  header="left: \'title\'; center: \'\'; right: \'prev,next\'" ' . $default_date_output . ' type="' . $type . '"]' );

		}

		if ( 'List' === $type ) {

			do_shortcode( '[fooevents_events_list  num="' . $number_of_events . '" type="' . $type . '" sort="' . $sort . '"]' );

		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Update widget options
	 *
	 * @param array $new_instance new instance.
	 * @param array $old_instance old instance.
	 */
	public function update( $new_instance, $old_instance ) {

		return $new_instance;

	}

	/**
	 * Admin update form
	 *
	 * @param array $instance instance.
	 */
	public function form( $instance ) {

		$type = '';
		if ( ! empty( $instance['type'] ) ) {

			$type = esc_attr( $instance['type'] );

		}

		$title = '';
		if ( ! empty( $instance['title'] ) ) {

			$title = esc_attr( $instance['title'] );

		}

		$number_of_events = '';
		if ( ! empty( $instance['number_of_events'] ) ) {

			$number_of_events = esc_attr( $instance['number_of_events'] );

		}

		$start_date = '';
		if ( ! empty( $instance['start_date'] ) ) {

			$start_date = esc_attr( $instance['start_date'] );

		}

		$sort = '';
		if ( ! empty( $instance['sort'] ) ) {

			$sort = esc_attr( $instance['sort'] );

		}
		?>  
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title', 'fooevents-calendar' ); ?>:</label><br />
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_attr_e( 'Layout Type', 'fooevents-calendar' ); ?>:</label><br />
			<select name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
				<?php if ( ! empty( $type ) ) { ?>
					<option value="<?php echo esc_attr( $type ); ?>">Current: <?php echo esc_attr( $type ); ?></option>
				<?php } ?>                
				<option value="Calendar"><?php esc_attr_e( 'Calendar', 'fooevents-calendar' ); ?></option>
				<option value="List"><?php esc_attr_e( 'List view', 'fooevents-calendar' ); ?></option>
			</select>
		</p>
		<?php if ( 'Calendar' === $type ) : ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'start_date' ) ); ?>"><?php esc_attr_e( 'Default date of calendar view', 'fooevents-calendar' ); ?>(optional):</label>
				<textarea placeholder="<?php esc_attr_e( 'Example: 2016-09-01', 'fooevents-calendar' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'start_date' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'start_date' ) ); ?>"><?php echo esc_attr( $start_date ); ?></textarea>
				<span class="description"><?php esc_attr_e( 'If empty, calendar will default to current date.', 'fooevents-calendar' ); ?></span>
			</p>   
		<?php endif; ?>
		<?php if ( 'List' === $type ) : ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_events' ) ); ?>"><?php esc_attr_e( 'Number of events to display', 'fooevents-calendar' ); ?>:</label><br />
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'number_of_events' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'number_of_events' ) ); ?>" value="<?php echo esc_attr( $number_of_events ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'sort' ) ); ?>"><?php esc_attr_e( 'Sort', 'fooevents-calendar' ); ?>:</label><br />
				<select name="<?php echo esc_attr( $this->get_field_name( 'sort' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'sort' ) ); ?>">
					<?php if ( ! empty( $sort ) ) : ?>
						<option value="<?php echo esc_attr( $sort ); ?>"><?php esc_attr_e( 'Current', 'fooevents-calendar' ); ?>: <?php echo esc_attr( strtoupper( $sort ) ); ?></option>
					<?php endif; ?>                
					<option value="asc"><?php esc_attr_e( 'ASC', 'fooevents-calendar' ); ?></option>
					<option value="desc"><?php esc_attr_e( 'DESC', 'fooevents-calendar' ); ?></option>
				</select>
			</p>
		<?php endif; ?>	
		<?php
	}

}

register_widget( 'Fooevents_Calendar_Widget' );
