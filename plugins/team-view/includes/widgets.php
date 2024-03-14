<?php
/**
 * Widgets.
 *
 * @package Team_View
 */

if ( ! function_exists( 'team_view_register_widgets' ) ) :

	/**
	 * Register widgets.
	 *
	 * @since 1.0.0
	 */
	function team_view_register_widgets() {

		// Team Grid widget.
		register_widget( 'Team_View_Grid_Widget' );

	}

endif;

add_action( 'widgets_init', 'team_view_register_widgets' );

if ( ! class_exists( 'Team_View_Grid_Widget' ) ) :

	/**
	 * Grid widget class.
	 *
	 * @since 1.0.0
	 */
	class Team_View_Grid_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'team_view_widget_grid',
				'description'                 => esc_html__( 'Display team in grid.', 'team-view' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'team-view-grid', esc_html__( 'Team View: Grid', 'team-view' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title         = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$limit         = ! empty( $instance['limit'] ) ? $instance['limit'] : 4;
			$column        = ! empty( $instance['column'] ) ? $instance['column'] : 4;
			$show_position = isset( $instance['show_position'] ) ? (bool)$instance['show_position'] : true;
			$show_social = isset( $instance['show_social'] ) ? (bool)$instance['show_social'] : true;

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$qargs = array(
				'limit'         => $limit,
				'column'        => $column,
				'show_position' => $show_position,
				'show_social'   => $show_social,
			);

			team_view_render_team_items( $qargs );

			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']         = sanitize_text_field( $new_instance['title'] );
			$instance['limit']         = absint( $new_instance['limit'] );
			$instance['column']        = absint( $new_instance['column'] );
			$instance['show_position'] = (bool)$new_instance['show_position'];
			$instance['show_social']   = (bool)$new_instance['show_social'];

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title'         => '',
				'limit'         => 4,
				'column'        => 4,
				'show_position' => 1,
				'show_social'   => 1,
			) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'team-view' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'No of Items:', 'team-view' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" min="1" value="<?php echo esc_attr( $instance['limit'] ); ?>" style="max-width:60px;" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'column' ) ); ?>"><?php esc_html_e( 'Columns:', 'team-view' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'column' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'column' ) ); ?>" type="number" min="1" max="4" value="<?php echo esc_attr( $instance['column'] ); ?>" style="max-width:60px;" />
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_position' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_position' ) ); ?>" type="checkbox" <?php checked( isset( $instance['show_position'] ) ? $instance['show_position'] : 0 ); ?> />&nbsp;<label for="<?php echo esc_attr( $this->get_field_id( 'show_position' ) ); ?>"><?php esc_html_e( 'Show Position', 'team-view' ); ?></label>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_social' ) ); ?>" type="checkbox" <?php checked( isset( $instance['show_social'] ) ? $instance['show_social'] : 0 ); ?> />&nbsp;<label for="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>"><?php esc_html_e( 'Show Social Links', 'team-view' ); ?></label>
			</p>
			<?php
		}
	}

endif;
