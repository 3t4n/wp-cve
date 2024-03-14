<?php
/**
 * Hester Core: Posts List widget.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */
class Hester_Core_Posts_List_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Widget defaults.
		$this->defaults = array(
			'title'         => '',
			'number'        => 5,
			'show_category' => false,
			'show_thumb'    => true,
			'show_date'     => true,
			'orderby'       => 'date',
		);

		// Widget Slug.
		$widget_slug = 'hester-core-posts-list-widget';

		// Widget basics.
		$widget_ops = array(
			'classname'   => $widget_slug,
			'description' => _x( 'Displays a configurable list of your site’s posts.', 'Widget', 'hester-core' ),
		);

		// Widget controls.
		$control_ops = array(
			'id_base' => $widget_slug,
		);

		// Load widget.
		parent::__construct( $widget_slug, _x( '[Hester] Posts List', 'Widget', 'hester-core' ), $widget_ops, $control_ops );

		// Hook into dynamic styles.
		add_filter( 'hester_dynamic_styles', array( $this, 'dynamic_styles' ) );

	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @since 1.0.0
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo wp_kses_post( $args['before_widget'] );

		do_action( 'hester_before_posts_list_widget', $instance );

		// Title.
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}

		$query_args = array(
			'posts_per_page'      => $instance['number'],
			'post_type'           => 'post',
			'status'              => 'publish',
			'orderby'             => $instance['orderby'],
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		);

		$query_args = apply_filters( 'hester_core_widget_posts_list_query_args', $query_args, $args, $instance );

		$posts = new WP_Query( $query_args );

		$theme = wp_get_theme();
		preg_match("/^([\w]+)/", $theme->name, $match);
		$theme_name = strtolower( $match[0] );

		if ( $posts->have_posts() ) :
			echo '<div class="hester-posts-list-widget-init-s1">';
			while ( $posts->have_posts() ) :
				$posts->the_post();
				$posts_count = wp_count_posts( 'post' )->publish;

				echo '<div class="hester-posts-list-widget">';

				if ( $instance['show_thumb'] ) {
					$hester_get_post_thumbnail = $theme_name . '_get_post_thumbnail';
					$post_thumbnail = $hester_get_post_thumbnail( get_the_ID(), array( 75, 75 ), true );
					$post_thumbnail = apply_filters( 'hester_core_opsts_list_widget_thumbnail', $post_thumbnail, get_the_ID() );

					if ( ! empty( $post_thumbnail ) ) {
						$hester_get_allowed_html_tags = $theme_name . '_get_allowed_html_tags';
						echo '<div class="hester-posts-list-widget-thumb"><a href="' . esc_url( get_permalink() ) . '">' . wp_kses( $post_thumbnail, $hester_get_allowed_html_tags( 'basic' ) ) . '</a></div>';
					}
				}

				echo '<div class="hester-posts-list-widget-details">';

				if ( $instance['show_category'] ) {

					/*$category_icon = hester()->icons->get_svg( 'bookmark' );
					wp_kses( $category_icon, hester_get_allowed_html_tags( 'svg' ) )*/
					$hester_entry_meta_category = $theme_name . '_entry_meta_category';
					echo '<div class="hester-posts-list-widget-categories">' . $hester_entry_meta_category( ', ', true, true ) . '</div>';
				}

				echo '<div class="hester-posts-list-widget-title">';

				echo '<a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '">' . wp_trim_words( wp_kses_post( get_the_title() ), 10, '&hellip;' ) . '</a>';

				echo '</div>';

				$post_meta = '';

				$hester_get_allowed_html_tags =  hester_core()->theme_name . '_get_allowed_html_tags';

				if ( $instance['show_date'] ) {
					
					$hester_function =  hester_core()->theme_name;

					$date_icon = $hester_function()->icons->get_svg( 'clock' );

					$post_meta .= '<span class="hester-posts-list-widget-date hester-flex-center">' . wp_kses( $date_icon, $hester_get_allowed_html_tags( 'svg' ) ) . get_the_time( get_option( 'date_format' ) ) . '</span>';
				}

				$post_meta = apply_filters( 'hester_core_posts_list_widget_meta', $post_meta, get_the_ID() );

				if ( ! empty( $post_meta ) ) {
					echo '<div class="hester-posts-list-widget-meta">' . wp_kses( $post_meta, $hester_get_allowed_html_tags() ) . '</div>';
				}

				echo '</div>';

				echo '</div>';
			endwhile;
			echo '</div>';
			wp_reset_postdata();
		endif;

		do_action( 'hester_after_posts_list_widget', $instance );

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @since 1.0.0
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 * @return array The validated and (if necessary) amended settings
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['title']         = ! empty( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['number']        = ! empty( $new_instance['number'] ) ? intval( $new_instance['number'] ) : 3;
		$instance['show_category'] = ! empty( $new_instance['show_category'] ) ? true : false;
		$instance['show_thumb']    = ! empty( $new_instance['show_thumb'] ) ? true : false;
		$instance['show_date']     = ! empty( $new_instance['show_date'] ) ? true : false;
		$instance['orderby']       = ! empty( $new_instance['orderby'] ) ? sanitize_text_field( $new_instance['orderby'] ) : 'date';

		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @since 1.0.0
	 * @param array $instance An array of the current settings for this widget.
	 * @return void
	 */
	public function form( $instance ) {

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>
		<div class="hester-posts-list-widget hester-widget">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php echo esc_html_x( 'Title:', 'Widget', 'hester-core' ); ?>
				</label>
				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"/>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
					<?php echo esc_html_x( 'Number of posts to show:', 'Widget', 'hester-core' ); ?>
				</label>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" <?php checked( $instance['show_thumb'], true ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php echo esc_html_x( 'Display thumbnail', 'hester-core' ); ?></label>
				<br/>
				<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" <?php checked( $instance['show_date'], true ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php echo esc_html_x( 'Display post date', 'hester-core' ); ?></label>
				<br/>
				<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_category' ) ); ?>" <?php checked( $instance['show_category'], true ); ?>>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>"><?php echo esc_html_x( 'Display post categories', 'hester-core' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php echo esc_html_x( 'Sort by:', 'hester-core' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
					<option value="date" <?php selected( $instance['orderby'], 'date' ); ?>><?php echo esc_html_x( 'Date (Latest posts)', 'Widget', 'hester-core' ); ?></option>
					<option value="modified" <?php selected( $instance['orderby'], 'modified' ); ?>><?php echo esc_html_x( 'Modified (Recently updated)', 'Widget', 'hester-core' ); ?></option>
					<option value="comment_count" <?php selected( $instance['orderby'], 'comment_count' ); ?>><?php echo esc_html_x( 'Comment count (Most popular)', 'Widget', 'hester-core' ); ?></option>
					<option value="menu_order" <?php selected( $instance['orderby'], 'menu_order' ); ?>><?php echo esc_html_x( 'Menu Order (Custom order)', 'Widget', 'hester-core' ); ?></option>
				</select>
			</p>

			<?php
			if ( function_exists( 'hester_help_link' ) ) {
				hester_help_link( array( 'link' => 'http://docs.peregrine-themes.com/' ) );
			}
			?>

		</div>
		<?php
	}

	/**
	 * Hook into Hester dynamic styles.
	 *
	 * @param  string $css Generated CSS code.
	 * @return string Modified CSS code.
	 */
	function dynamic_styles( $css ) {
		$css .= '#main .hester-posts-list-widget-meta {
			color: ' . hester_hex2rgba( hester_option( 'content_text_color' ), 0.75 ) . ';
		}';

		return $css;
	}
}
