<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'widgets_init', 'wtpsw_post_list_widget' );

/**
 * Register trending post vertical slider widget
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */
function wtpsw_post_list_widget() {
	register_widget( 'Wtpsw_Post_List_Widget' );
}

/**
 * Vertical Scrolling Post Widget Class
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' )) exit;

class Wtpsw_Post_List_Widget extends WP_Widget {

	var $model, $defaults;

	function __construct() {

		global $wtpsw_model;
		$this->model = $wtpsw_model;

		// Widget settings
		$widget_ops = array( 'classname' => 'wtpsw_post_list_widget', 'description' => __( 'Display most popular trending post on your blog.', 'wtpsw' ));

		// Create the widget
		parent::__construct( 'wtpsw-post-list-widget', __( 'Trending Posts', 'wtpsw' ), $widget_ops );

		$this->defaults = array( 
				'title'						=> __( 'Trending Posts', 'wtpsw' ),
				'limit'						=> 5,
				'post_type'					=> 'post',
				'show_content'				=> 0,
				'show_thumb'				=> 1,
				'show_author'				=> 1,
				'show_date'					=> 1,
				'order'						=> 'DESC',
				'view_by'					=> 'views',
				'content_length'			=> 20,
				'show_comment_count'		=> 1,
				'hide_empty_comment_count'	=> 0,
			);
	}

	/**
	 * Updates the widget control options
	 *
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// Input fields
		$instance['post_type']					= $new_instance['post_type'];
		$instance['view_by']					= $new_instance['view_by'];
		$instance['title']						= strip_tags( $new_instance['title'] );
		$instance['limit']						= ( empty($new_instance['limit'] ) || ( $new_instance['limit'] < -1 ) )		? 5 	: $new_instance['limit'];
		$instance['order']						= ( isset( $new_instance['order'] ) && $new_instance['order'] == 'ASC' )	? 'ASC' : 'DESC';
		$instance['content_length']				= ! empty( $new_instance['content_length'] )			? $new_instance['content_length'] : 20;
		$instance['show_author']				= ! empty( $new_instance['show_author'] )				? 1	: 0;
		$instance['show_comment_count']			= ! empty( $new_instance['show_comment_count'] )		? 1	: 0;
		$instance['show_content']				= ! empty( $new_instance['show_content'] )				? 1	: 0;
		$instance['show_thumb']					= ! empty( $new_instance['show_thumb'] )				? 1	: 0;
		$instance['show_date']					= ! empty( $new_instance['show_date'] )					? 1	: 0;
		$instance['hide_empty_comment_count']	= ! empty( $new_instance['hide_empty_comment_count'] )	? 1	: 0;

		return $instance;
	}

	/**
	 * Displays the widget form in widget area
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		$instance			= wp_parse_args( (array) $instance, $this->defaults );
		$post_types			= wtpsw_get_post_types();
		$support_post_types	= wtpsw_get_option( 'post_types', array() );
		$sel_post_type		= ( ! empty( $instance['post_type'] ) && in_array( $instance['post_type'], $support_post_types ) ) ? $instance['post_type']	: '';
	?>

	<div class="wtpsw-widget-wrap">

		<!-- Title Field -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'wtpsw' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<!-- Post type  -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php esc_html_e( 'Post Type', 'wtpsw' ); ?></label>
			<select class="widefat ftpp-reg-post-types" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' )); ?>" >
				<option value="" selected ><?php esc_html_e( 'Select Post Type', 'wtpsw'); ?></option>
				<?php
				if( !empty($post_types) ) {
					foreach ($post_types as $post_key => $post_value) {
						if(in_array($post_key, $support_post_types)) {
							echo '<option value="'.esc_attr( $post_key ).'" '.selected( $post_key, $instance['post_type'] ).'>'.esc_attr( $post_value ).'</option>';
						}
					}
				}
				?>
			</select>
		</p>

		<!-- View By Field -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'view_by' )); ?>"><?php esc_html_e( 'Post List By', 'wtpsw'); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_by' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'view_by' )); ?>">
				<option value="views" <?php selected( $instance['view_by'], 'views' ); ?> ><?php esc_html_e('Total Views', 'wtpsw') ?></option>
				<option value="comment" <?php selected( $instance['view_by'], 'comment' ); ?>><?php esc_html_e( 'Comments Count', 'wtpsw' ); ?></option>
			</select>
		</p>

		<!-- Number of Items Field -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'limit' )); ?>"><?php esc_html_e( 'Number of Items', 'wtpsw'); ?></label> 
			<input class="widefat" min="-1" id="<?php echo esc_attr($this->get_field_id( 'limit' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'limit' )); ?>" type="number" value="<?php echo esc_attr($instance['limit']); ?>" />
		</p>

		<!-- Order Field -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' )); ?>"><?php esc_html_e( 'Order', 'wtpsw' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' )); ?>">
				<option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?> ><?php esc_html_e('ASC', 'wtpsw') ?></option>
				<option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php esc_html_e('DESC', 'wtpsw'); ?></option>
			</select>
		</p>

		<!-- Show Content Field -->
		<p>
			<input type="checkbox" value="1" id="<?php echo esc_attr($this->get_field_id( 'show_content' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_content' )); ?>" <?php checked( $instance['show_content'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_content' )); ?>"><?php esc_html_e( 'Display Short Content', 'wtpsw' ); ?></label><br/>
			<span class="description"><em><?php esc_html_e( 'If your post has excerpt then it will take it else post content will be taken.', 'wtpsw' ); ?></em></span>
		</p>

		<!-- Show Post Content Word Limit -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'content_length' )); ?>"><?php esc_html_e( 'Post Content Length', 'wtpsw'); ?></label> 
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'content_length' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'content_length' )); ?>" type="number" min="1" value="<?php echo esc_attr( $instance['content_length'] ); ?>" />
			<span class="description"><em><?php esc_html_e( 'Enter number of words to display in post content.', 'wtpsw' ); ?></em></span>
		</p>

		<!-- Show Thumbnail Field -->
		<p>
			<input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_thumb' )); ?>" <?php checked( $instance['show_thumb'], 1 ); ?> />
			<label for="<?php echo esc_attr($this->get_field_id( 'show_thumb' )); ?>"><?php esc_html_e( 'Show Thumbnail', 'wtpsw'); ?></label>
		</p>

		<!-- Show Author Field -->
		<p>
			<input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_author' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_author' )); ?>" <?php checked( $instance['show_author'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_author' )); ?>"><?php esc_html_e( 'Show Author', 'wtpsw'); ?></label>
		</p>

		<!-- Show Date Field -->
		<p>
			<input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' )); ?>" <?php checked( $instance['show_date'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' )); ?>"><?php esc_html_e( 'Show Date', 'wtpsw'); ?></label>
		</p>

		<!-- Show Comment Field -->
		<p>
			<input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_comment_count' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_comment_count' )); ?>" <?php checked( $instance['show_comment_count'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_comment_count' )); ?>"><?php esc_html_e( 'Show Comment Count', 'wtpsw' ); ?></label>
		</p>

		<!-- Show Comment Field -->
		<p>
			<input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty_comment_count' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty_comment_count' )); ?>" <?php checked( $instance['hide_empty_comment_count'], 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty_comment_count' )); ?>"><?php esc_html_e( 'Hide Empty Comment Count', 'wtpsw' ); ?></label><br/>
			<span class="description"><em><?php esc_html_e( 'Hide comment count if it is empty.', 'wtpsw' ); ?></em></span>
		</p>

	</div>
	<?php
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {

		// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
		if( isset( $_POST['action'] ) && ( $_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json' ) ) {
		echo "<div class='wtpsw-builder-shrt-prev'><div class='wtpsw-builder-shrt-title'><span>".esc_html__('Trending Post List - Widget', 'wtpsw')."</span></div>WPOS - Trending Post List</div>";
			return;
		}

		global $wtpsw_options, $wtpsw_view_by;

		$instance = wp_parse_args( (array) $instance, $this->defaults );
		extract( $args );

		$prefix						= WTPSW_META_PREFIX;
		$support_post_types			= wtpsw_get_option('post_types', array());
		$title						= $instance['title'];
		$limit						= $instance['limit'];
		$order						= $instance['order'];
		$view_by					= $instance['view_by'];
		$content_length				= $instance['content_length'];
		$post_type					= ( ! empty( $instance['post_type'] ) && in_array( $instance['post_type'], $support_post_types ) )	? $instance['post_type']	: '';
		$post_type					= ( ! empty( $instance['post_type'] ) && in_array( $instance['post_type'], $support_post_types ) )	? $instance['post_type']	: '';
		$show_date					= ! empty( $instance['show_date'] )					? true					: false;
		$show_author				= ! empty( $instance['show_author'] )				? true					: false;
		$show_comment_count			= ! empty( $instance['show_comment_count'] )		? true					: false;
		$show_thumb					= ! empty( $instance['show_thumb'] )				? true					: false;
		$show_content				= ! empty( $instance['show_content'] )				? true					: false;
		$hide_empty_comment_count	= ! empty( $instance['hide_empty_comment_count'] )	? true					: false;

		// If no valid post type is found
		if( empty( $post_type ) ) {
			return;
		}

		// Order By
		if( $view_by == 'comment' ) {
			$orderby = 'comment_count';
		} elseif ( $view_by == 'views' ) {
			$orderby = 'meta_value_num';
		}

		$wtpsw_view_by = $orderby; // Assign to global variable for query filter

		$post_args	= array(
							'post_type'			=> $post_type,
							'posts_per_page'	=> $limit,
							'order'				=> $order,
							'orderby'			=> $orderby
						);

		if( $view_by == 'views' ) {
			$post_args['meta_key'] = $prefix.'views';
		}

		// Filter to change query where condition
		add_filter( 'posts_where', array( $this->model, 'wtpsw_query_where' ));

		// Query to get post
		$wtpsw_posts = $this->model->wtpsw_get_posts( $post_args );

		// Remove Filter for change query where condition
		remove_filter( 'posts_where', array( $this->model, 'wtpsw_query_where' ));

		echo $before_widget;

		if ( $title ) {
			echo $before_title . wp_kses_post($title) . $after_title;
		}

		if( $wtpsw_posts->have_posts() ) : ?>
			<div class="wtpsw-post-items">
				<ul>
					<?php while ( $wtpsw_posts->have_posts() )	: $wtpsw_posts->the_post();

						global $post;
						$wtpsw_stats	= array(); // Need to flush
						$comment_text	= wtpsw_get_comments_number( $post->ID, $hide_empty_comment_count );

						// Design file
						include( WTPSW_DIR . '/templates/wtpsw-post-lists.php' );

					endwhile; ?>
				</ul>
			</div>
		<?php
		endif;
		wp_reset_postdata(); // Reset WP Query
		echo $after_widget;
	}
}