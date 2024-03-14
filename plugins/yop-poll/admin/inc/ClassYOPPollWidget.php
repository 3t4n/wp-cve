<?php
class YOP_Poll_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'yop_poll_widget',
			__( 'YOP Poll', 'yop-poll' ),
			array(
				'description' => esc_html__( 'Add a poll to your site', 'yop-poll' )
			)
		);
	}
	public function form( $instance ) {
		if ( true === isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = '';
		}
		$poll_id = ( isset( $instance['poll_id'] ) ) ? intval( $instance['poll_id'] ) : 0;
		$tracking_id = ( isset( $instance['tracking_id'] ) ) ? $instance['tracking_id'] : '';
		$show_results = ( isset( $instance['show_results'] ) ) ? $instance['show_results'] : '';
		$polls = YOP_Poll_Polls::get_names();
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'yop-poll' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for = "<?php echo esc_attr( $this->get_field_id( 'poll_id' ) ); ?>">
				<?php
				esc_attr_e( 'Poll to display', 'yop-poll' );
				?>
			</label>
			<select id = "<?php echo esc_attr( $this->get_field_id( 'poll_id' ) ); ?>" name = "<?php echo esc_attr( $this->get_field_name( 'poll_id' ) ); ?>" class = "widefat">
				<option value = "0" <?php selected( 0, $poll_id ); ?>>
					<?php esc_attr_e( 'Don\'t Display Poll (Disable)', 'yop-poll' ); ?>
				</option>
				<option value = "-1" <?php selected( -1, $poll_id ); ?>>
					<?php esc_attr_e( 'Display Current Active Poll', 'yop-poll' ); ?>
				</option>
				<option value = "-2" <?php selected( -2, $poll_id ); ?>>
					<?php esc_attr_e( 'Display Latest Poll', 'yop-poll' ); ?>
				</option>
				<option value = "-3" <?php selected( -3, $poll_id ); ?>>
					<?php esc_attr_e( 'Display Random Poll', 'yop-poll' ); ?>
				</option>
				<?php
				if (
					( true === is_array( $polls ) ) &&
					( count( $polls ) > 0 )
				) {
					foreach ( $polls as $poll ) {
						?>
						<option value="<?php echo esc_attr( $poll->id ); ?>" <?php selected( $poll->id, $poll_id ); ?>>
							<?php echo esc_html( $poll->name ); ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tracking_id' ) ); ?>">
				<?php esc_attr_e( 'Tracking Id:', 'yop-poll' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tracking_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tracking_id' ) ); ?>" type="text" value="<?php echo esc_attr( $tracking_id ); ?>" />
		</p>
		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['poll_id'] = ( ! empty( $new_instance['poll_id'] ) ) ? strip_tags( $new_instance['poll_id'] ) : '';
		$instance['tracking_id'] = ( ! empty( $new_instance['tracking_id'] ) ) ? strip_tags( $new_instance['tracking_id'] ) : '';
		return $instance;
	}
	public function widget( $args, $instance ) {
		$title = ( isset( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : esc_html__( '' );
		$poll_id = ( isset( $instance['poll_id'] ) ) ? intval( $instance['poll_id'] ) : -1;
		$tracking_id = ( isset( $instance['tracking_id'] ) ) ? $instance['tracking_id'] : '';
		$public = new YOP_Poll_Public();
		$poll_output = $public->generate_poll(
			array(
				'id' => $poll_id,
				'tracking_id' => $tracking_id,
                'results' => '',
                'show_results' => '',
				'page_id' => get_the_ID(),
			)
		);
		echo wp_kses_post( $args['before_widget'] );
		echo wp_kses_post( $args['before_title'] );
		print( wp_kses_post( $title ) );
		echo wp_kses_post( $args['after_title'] );
		print( $poll_output );
		echo wp_kses_post( $args['after_widget'] );
	}
}
