<?php
/**
 * Widget Reviews Posts
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/public
 */

/**
 * Widget Reviews Posts
 */
class ABR_Reviews_Posts_Widget extends WP_Widget {

	/**
	 * The default settings.
	 *
	 * @var array $default_settings The default settings.
	 */
	public $default_settings = array();

	/**
	 * Sets up a new widget instance.
	 */
	public function __construct() {

		$this->default_settings = apply_filters(
			'abr_widget_posts_settings', array(
				'title'                   => '',
				'template'                => 'list',
				'posts_per_page'          => 5,
				'review_type'             => 'all',
				'orderby'                 => 'date',
				'order'                   => 'desc',
				'time_frame'              => '',
				'category'                => false,
				'thumbnail'               => 'large',
				'post_meta'               => array( 'date' ),
				'post_meta_compact'       => false,
				'thumbnail_large'         => 'large',
				'post_meta_large'         => array( 'date' ),
				'post_meta_large_compact' => false,
				'thumbnail_small'         => 'large',
				'post_meta_small'         => array( 'date' ),
				'post_meta_small_compact' => false,
				'avoid_duplicate'         => false,
				'output'                  => 'widget',
			)
		);

		$widget_details = array(
			'classname'   => 'abr_reviews_posts_widget',
			'description' => esc_html__( 'Display a list of your reviews posts.', 'absolute-reviews' ),
		);
		parent::__construct( 'abr_reviews_posts_widget', esc_html__( 'Reviews Posts', 'absolute-reviews' ), $widget_details );
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		global $abr_posts;
		global $wp_query;

		if ( ! $abr_posts ) {
			$abr_posts = array();
		}

		$params = array_merge( $this->default_settings, $instance );

		$posts_args = array(
			'posts_per_page'      => $params['posts_per_page'],
			'order'               => $params['order'],
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		);

		if ( $params['category'] ) {
			$category          = $params['category'];
			$posts_args['cat'] = $category;
		}

		// Avoid Duplicate.
		if ( $params['avoid_duplicate'] ) {
			$main_posts = array();

			if ( isset( $wp_query->posts ) && $wp_query->posts ) {
				$main_posts = wp_list_pluck( $wp_query->posts, 'ID' );
			}

			if ( $main_posts ) {
				$posts_args['post__not_in'] = array_merge( $main_posts, $abr_posts );
			} else {
				$posts_args['post__not_in'] = $abr_posts;
			}
		}

		// Review type.
		if ( 'all' !== $params['review_type'] ) {
			$posts_args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'   => '_abr_review_type',
					'value' => $params['review_type'],
				),
			);
		}

		// Post order.
		if ( class_exists( 'Post_Views_Counter' ) && 'meta_value_num' === $params['orderby'] ) {
			// Post Views.
			$posts_args['orderby'] = 'post_views';
		} else {
			$posts_args['orderby'] = $params['orderby'];
		}

		if ( $params['time_frame'] ) {
			$posts_args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => $params['time_frame'] . ' ago',
				),
			);
		}

		$posts = new WP_Query( apply_filters_ref_array( 'abr_reviews_posts_widget_args', array( $posts_args, & $params ) ) );

		if ( $posts->have_posts() ) {

			// Before Widget.
			echo $args['before_widget']; // XSS.

			// Title.
			if ( $params['title'] ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $params['title'], $instance, $this->id_base ) . $args['after_title']; // XSS.
			}

			$review_id = get_the_ID();

			// Class Template.
			$class = sprintf( 'abr-posts-template-%s', $params['template'] );

			// Class Number of Posts.
			$class .= sprintf( ' abr-posts-per-page-%s', (int) $params['posts_per_page'] );
			?>

			<div class="widget-body abr-reviews-posts <?php echo esc_html( $class ); ?>">
				<div class="abr-reviews-posts-list">
					<?php
					$params['counter'] = 0;

					while ( $posts->have_posts() ) :
						$posts->the_post();

						$abr_posts[] = $review_id;

						$params['counter']++;
						?>
						<div class="abr-post-item">
							<?php abr_reviews_posts_widget_handler( $params['template'], $posts, $params, $instance ); ?>
						</div>
					<?php endwhile; ?>
				</div>
			</div>

			<?php

			// After Widget.
			echo $args['after_widget']; // XSS.
		}

		wp_reset_postdata();
	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $new_instance;

		// Post Meta.
		if ( ! isset( $instance['post_meta'] ) ) {
			$instance['post_meta'] = array();
		}

		// Compact Post Meta.
		if ( ! isset( $instance['post_meta_compact'] ) ) {
			$instance['post_meta_compact'] = false;
		}

		// Avoid duplicate posts.
		if ( ! isset( $instance['avoid_duplicate'] ) ) {
			$instance['avoid_duplicate'] = false;
		}

		return apply_filters( 'abr_reviews_posts_widget_update', $instance );
	}

	/**
	 * Outputs the widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$params = array_merge( $this->default_settings, $instance );

		$allowed_post_meta = abr_allowed_post_meta();

		$image_sizes = abr_get_list_available_image_sizes();

		$templates = apply_filters( 'abr_reviews_posts_templates', array() );
		?>
			<!-- Title -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'absolute-reviews' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $params['title'] ); ?>" /></p>

			<?php do_action( 'abr_reviews_posts_widget_form_before', $this, $params, $instance ); ?>

			<!-- Template -->
			<?php if ( $templates ) { ?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php esc_html_e( 'Template:', 'absolute-reviews' ); ?></label>
					<select name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>" class="widefat abr-template">
						<?php
						foreach ( $templates as $slug => $template ) {
							$name = isset( $template['name'] ) ? $template['name'] : $slug;
							?>
							<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $params['template'], $slug ); ?>><?php echo esc_html( $name ); ?></option>
						<?php } ?>
					</select>
				</p>
			<?php } ?>

			<!-- Number of Posts -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Number of posts:', 'absolute-reviews' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $params['posts_per_page'] ); ?>" /></p>

			<!-- Filter by Review Type -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'review_type' ) ); ?>"><?php esc_html_e( 'Filter by Review Type:', 'absolute-reviews' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'review_type' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'review_type' ) ); ?>" class="widefat">
					<option value="all" <?php selected( $params['review_type'], 'all' ); ?>><?php esc_html_e( 'All types', 'absolute-reviews' ); ?></option>
					<option value="percentage" <?php selected( $params['review_type'], 'percentage' ); ?>><?php esc_html_e( 'Percentage (1-100%)', 'absolute-reviews' ); ?></option>
					<option value="point-5" <?php selected( $params['review_type'], 'point-5' ); ?>><?php esc_html_e( 'Points (1-5)', 'absolute-reviews' ); ?></option>
					<option value="point-10" <?php selected( $params['review_type'], 'point-10' ); ?>><?php esc_html_e( 'Points (1-10)', 'absolute-reviews' ); ?></option>
					<option value="star" <?php selected( $params['review_type'], 'star' ); ?>><?php esc_html_e( 'Stars (1-5)', 'absolute-reviews' ); ?></option>
				</select>
			</p>

			<!-- Order by -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order by:', 'absolute-reviews' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
					<option value="date" <?php selected( $params['orderby'], 'date' ); ?>><?php esc_html_e( 'Date', 'absolute-reviews' ); ?></option>
					<option value="comment_count" <?php selected( $params['orderby'], 'comment_count' ); ?>><?php esc_html_e( 'Comments', 'absolute-reviews' ); ?></option>
					<option value="rand" <?php selected( $params['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random', 'absolute-reviews' ); ?></option>

					<?php if ( class_exists( 'Post_Views_Counter' ) ) { ?>
						<option value="meta_value_num" <?php selected( $params['orderby'], 'meta_value_num' ); ?>><?php esc_html_e( 'Views', 'absolute-reviews' ); ?></option>
					<?php } ?>
				</select>
			</p>

			<!-- Order -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'absolute-reviews' ); ?>:</label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
					<option value="desc" <?php selected( $params['order'], 'desc' ); ?>><?php esc_html_e( 'Descending', 'absolute-reviews' ); ?></option>
					<option value="asc" <?php selected( $params['order'], 'asc' ); ?>><?php esc_html_e( 'Ascending', 'absolute-reviews' ); ?></option>
				</select>
			</p>

			<!-- Time Frame -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'time_frame' ) ); ?>"><?php esc_html_e( 'Time frame:', 'absolute-reviews' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'time_frame' ) ); ?>" placeholder="<?php esc_html_e( '3 months', 'absolute-reviews' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'time_frame' ) ); ?>" type="text" value="<?php echo esc_attr( $params['time_frame'] ); ?>" /></p>

			<!-- Category -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'absolute-reviews' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="widefat" style="height: auto !important;" multiple="multiple" size="8">
					<?php
						$cat_args = array(
							'hide_empty'   => 0,
							'hierarchical' => 1,
							'selected'     => (array) $params['category'],
							'walker'       => new ABR_Add_Posts_Categories_Tree_Walker(),
						);

						$allowed_html = array(
							'option' => array(
								'class'    => true,
								'value'    => true,
								'selected' => true,
							),
						);

						echo wp_kses( walk_category_dropdown_tree( get_categories( $cat_args ), 0, $cat_args ), $allowed_html );
						?>
				</select>
			</p>

			<fieldset class="abr-simple-post">
				<!-- Image size -->
				<h4><?php esc_html_e( 'Image size:', 'absolute-reviews' ); ?></h4>

				<?php if ( $image_sizes ) { ?>
					<p><select name="<?php echo esc_attr( $this->get_field_name( 'thumbnail' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ) ); ?>">
						<?php foreach ( $image_sizes as $key => $caption ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $params['thumbnail'] ); ?>><?php echo esc_html( $caption ); ?></option>
						<?php } ?>
					</select></p>
				<?php } ?>

				<!-- Post meta -->
				<h4><?php esc_html_e( 'Post meta:', 'absolute-reviews' ); ?></h4>

				<?php foreach ( $allowed_post_meta  as $key => $caption ) { ?>
					<p><input id="<?php echo esc_attr( $this->get_field_id( 'post_meta' ) ); ?>-<?php echo esc_attr( $key ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'post_meta' ) ); ?>[]" type="checkbox" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $params['post_meta'], true ) ? true : false ); ?> />
					<label for="<?php echo esc_attr( $this->get_field_id( 'post_meta' ) ); ?>-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $caption ); ?></label></p>
				<?php } ?>

				<!-- Compact post meta -->
				<h4><?php esc_html_e( 'Compact post meta:', 'absolute-reviews' ); ?></h4>
				<p><input id="<?php echo esc_attr( $this->get_field_id( 'post_meta_compact' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'post_meta_compact' ) ); ?>" type="checkbox" <?php checked( (bool) $params['post_meta_compact'] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_meta_compact' ) ); ?>"><?php esc_html_e( 'Display compact post meta', 'absolute-reviews' ); ?></label></p>
			</fieldset>

			<fieldset class="abr-large-post">
				<legend><?php esc_html_e( 'Large Post', 'absolute-reviews' ); ?></legend>

				<!-- Image size -->
				<h4><?php esc_html_e( 'Image size:', 'absolute-reviews' ); ?></h4>

				<?php if ( $image_sizes ) { ?>
					<p><select name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_large' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_large' ) ); ?>-<?php echo esc_attr( $key ); ?>">
						<?php foreach ( $image_sizes  as $key => $caption ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $params['thumbnail_large'] ); ?>><?php echo esc_html( $caption ); ?></option>
						<?php } ?>
					</select></p>
				<?php } ?>

				<!-- Post meta -->
				<h4><?php esc_html_e( 'Post meta:', 'absolute-reviews' ); ?></h4>

				<?php foreach ( $allowed_post_meta  as $key => $caption ) { ?>
						<p><input id="<?php echo esc_attr( $this->get_field_id( 'post_meta_large' ) ); ?>-<?php echo esc_attr( $key ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'post_meta_large' ) ); ?>[]" type="checkbox" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $params['post_meta_large'], true ) ? true : false ); ?> />
						<label for="<?php echo esc_attr( $this->get_field_id( 'post_meta_large' ) ); ?>-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $caption ); ?></label></p>
				<?php } ?>

				<!-- Compact post meta -->
				<h4><?php esc_html_e( 'Compact post meta:', 'absolute-reviews' ); ?></h4>
				<p><input id="<?php echo esc_attr( $this->get_field_id( 'post_meta_large_compact' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'post_meta_large_compact' ) ); ?>" type="checkbox" <?php checked( (bool) $params['post_meta_large_compact'] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_meta_large_compact' ) ); ?>"><?php esc_html_e( 'Display compact post meta', 'absolute-reviews' ); ?></label></p>
			</fieldset>

			<fieldset class="abr-small-post">
				<legend><?php esc_html_e( 'Small Post', 'absolute-reviews' ); ?></legend>

				<!-- Image size -->
				<h4><?php esc_html_e( 'Image size:', 'absolute-reviews' ); ?></h4>

				<?php if ( $image_sizes ) { ?>
					<p><select name="<?php echo esc_attr( $this->get_field_name( 'thumbnail_small' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'thumbnail_small' ) ); ?>-<?php echo esc_attr( $key ); ?>">
						<?php foreach ( $image_sizes  as $key => $caption ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $params['thumbnail_small'] ); ?>><?php echo esc_html( $caption ); ?></option>
						<?php } ?>
					</select></p>
				<?php } ?>

				<!-- Post meta -->
				<h4><?php esc_html_e( 'Post meta:', 'absolute-reviews' ); ?></h4>

				<?php foreach ( $allowed_post_meta  as $key => $caption ) { ?>
					<p><input id="<?php echo esc_attr( $this->get_field_id( 'post_meta_small' ) ); ?>-<?php echo esc_attr( $key ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'post_meta_small' ) ); ?>[]" type="checkbox" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $params['post_meta_small'], true ) ? true : false ); ?> />
					<label for="<?php echo esc_attr( $this->get_field_id( 'post_meta_small' ) ); ?>-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $caption ); ?></label></p>
				<?php } ?>

				<!-- Compact Post Meta -->
				<h4><?php esc_html_e( 'Compact post meta:', 'absolute-reviews' ); ?></h4>
				<p><input id="<?php echo esc_attr( $this->get_field_id( 'post_meta_small_compact' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'post_meta_small_compact' ) ); ?>" type="checkbox" <?php checked( (bool) $params['post_meta_small_compact'] ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_meta_small_compact' ) ); ?>"><?php esc_html_e( 'Display compact post meta', 'absolute-reviews' ); ?></label></p>
			</fieldset>

			<!-- Avoid duplicate posts -->
			<h4><?php esc_html_e( 'Avoid duplicate posts:', 'absolute-reviews' ); ?></h4>
			<p><input id="<?php echo esc_attr( $this->get_field_id( 'avoid_duplicate' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'avoid_duplicate' ) ); ?>" type="checkbox" <?php checked( (bool) $params['avoid_duplicate'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'avoid_duplicate' ) ); ?>"><?php esc_html_e( 'Exclude duplicate posts', 'absolute-reviews' ); ?></label></p>

			<?php do_action( 'abr_reviews_posts_widget_form_after', $this, $params, $instance ); ?>

			<script>
				(function($) {
					let objTemplate = $( '#<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>' );

					$( objTemplate ).on( 'change', function() {
						$( this ).closest( '.widget' ).attr( 'template', $( this ).val() );
					});

					$( objTemplate ).change();

				})(jQuery);
			</script>
		<?php
	}
}

/**
 * Create HTML dropdown list of Categories.
 */
class ABR_Add_Posts_Categories_Tree_Walker extends Walker_CategoryDropdown {

	/**
	 * Starts the element output.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output   Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int    $depth    Depth of category. Used for padding.
	 * @param array  $args     Uses 'selected', 'show_count', and 'value_field' keys, if they exist.
	 *                         See wp_dropdown_categories().
	 * @param int    $id       Optional. ID of the current category. Default 0 (unused).
	 */
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad      = $depth > 0 ? '- ' . str_repeat( '&nbsp;', $depth * 3 ) : '';
		$selected = array_map( 'intval', $args['selected'] );
		$cat_name = apply_filters( 'list_cats', $category->name, $category );

		$output .= sprintf(
			'<option class="level-%1$s" value="%2$s" %4$s>%3$s</option>',
			esc_attr( $depth ),
			esc_attr( $category->term_id ),
			esc_html( $pad . $cat_name ),
			selected( in_array( $category->term_id, $selected, true ), true )
		);
		$output .= "\n";
	}
}

/**
 * Register Widget
 */
function abr_reviews_posts_init() {
	register_widget( 'ABR_Reviews_Posts_Widget' );
}
add_action( 'widgets_init', 'abr_reviews_posts_init' );
