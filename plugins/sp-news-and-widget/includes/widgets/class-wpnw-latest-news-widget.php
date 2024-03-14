<?php
/**
 * Widget Class
 *
 * Handles latest News widget functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SP_News_Widget extends WP_Widget {

	var $defaults;

	/**
	 * Sets up a new widget instance.
	 *
	 * @since 1.0
	 */
	function __construct() {

		$widget_ops		= array( 'classname' => 'SP_News_Widget', 'description' => esc_html__( 'Displayed Latest News Items from the News  in a sidebar', 'sp-news-and-widget' ) );
		$control_ops	= array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_widget' );

		parent::__construct( 'sp_news_widget', esc_html__( 'Latest News Widget', 'sp-news-and-widget' ), $widget_ops, $control_ops );

		$this->defaults = array(
			'title'			=> __('Latest News', 'sp-news-and-widget'),
			'num_items'		=> 5,
			'date'			=> false, 
			'show_category'	=> false,
			'category'		=> 0,
		);
	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @since 1.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance					= $old_instance;

		$instance['title']			= isset( $new_instance['title'] ) 			? wpnw_clean( $new_instance['title'] ) 							: '';
		$instance['num_items']		= isset( $new_instance['num_items'] )		? wpnw_clean_number( $new_instance['num_items'], 5, 'number' )	: '';
		$instance['category']		= isset( $new_instance['category'] )		? wpnw_clean_number( $new_instance['category'] )				: '';
		$instance['date']			= ! empty( $new_instance['date'])			? 1	: 0;
		$instance['show_category']	= ! empty( $new_instance['show_category'] )	? 1	: 0;

		return $instance;
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0
	 */
	function form( $instance ) {

		$instance	= wp_parse_args( (array)$instance, $this->defaults );
		$num_items	= isset( $instance['num_items'] ) ? absint( $instance['num_items'] ) : 5;
	?>

		<div class="wpnw-widget-wrap">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'sp-news-and-widget' ); ?>:<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'num_items' ) ); ?>"><?php esc_html_e( 'Number of Items', 'sp-news-and-widget' ); ?>:<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_items' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_items' ) ); ?>" type="text" value="<?php echo esc_attr( $num_items ); ?>" /></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Display Date', 'sp-news-and-widget' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_category' ) ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>"><?php esc_html_e( 'Display Category', 'sp-news-and-widget' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category', 'sp-news-and-widget' ); ?>:</label>
				<?php
					$dropdown_args = array(
										'taxonomy'			=> WPNW_CAT,
										'show_option_none'	=> esc_html__( 'All Categories', 'sp-news-and-widget' ),
										'option_none_value'	=> '',
										'class'				=> 'widefat',
										'id'				=> esc_attr( $this->get_field_id( 'category' ) ),
										'name'				=> esc_attr( $this->get_field_name( 'category' ) ),
										'selected'			=> $instance['category'],
									);
					wp_dropdown_categories( $dropdown_args );
				?>
			</p>
		</div>
	<?php
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @since 1.0
	 */
	function widget( $news_args, $instance ) {

		// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
		if( isset( $_POST['action'] ) && ( $_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json' ) ) {
			esc_html_e( 'Latest News', 'sp-news-and-widget' );
			return;
		}

		// Taking some globals
		global $post;

		$atts = wp_parse_args( (array)$instance, $this->defaults );
		extract( $news_args, EXTR_SKIP );

		$title 							= apply_filters( 'widget_title', $atts['title'], $atts, $this->id_base );
		$atts['date'] 					= ( ! empty( $atts['date'] ) )			? true					: false;
		$atts['show_category'] 			= ( ! empty( $atts['show_category'] ) )	? true					: false;

		// Extract Widegt Var
		extract( $atts );

		// WP Query Parameter
		$news_args = array(
						'posts_per_page'	=> $num_items,
						'post_type'			=> WPNW_POST_TYPE,
						'post_status'		=> array( 'publish' ),
						'order'				=> 'DESC'
					);

		// Category Parameter
		if( ! empty( $category ) ) {
			$news_args['tax_query'] = array(
										array(
											'taxonomy'	=> WPNW_CAT,
											'field'		=> 'term_id',
											'terms'		=> $category,
										)
									);
		}

		// WP Query
		$cust_loop = new WP_Query( $news_args );

		echo $before_widget; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $before_title . wp_kses_post($title) . $after_title; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		if ( $cust_loop->have_posts() ) : 

		// Visual columns
		$no_p = '';
		if( empty( $date ) && empty( $show_category ) ) { $no_p = "no_p"; } ?>

		<div class="recent-news-items <?php echo esc_attr( $no_p ); ?>">
			<ul>
				<?php while ( $cust_loop->have_posts() ) : $cust_loop->the_post();
					
					$terms		= get_the_terms( $post->ID, WPNW_CAT );
					$news_links	= array();

					if( $terms ) {
						foreach ( $terms as $term ) {
							$term_link		= get_term_link( $term );
							$news_links[]	= '<a href="' . esc_url( $term_link ) . '">'.wp_kses_post( $term->name ).'</a>';
						}
					}
					$cate_name = join( ", ", $news_links ); ?>

					<li class="news_li">
						<a class="newspost-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<?php if( $date || $show_category ) { ?>
							<div class="widget-date-post">
								<?php
								echo ( $date )											? get_the_date()			: "";
								echo ( $date && $show_category && $cate_name != '' )	? ", "						: "";
								echo ( $show_category && $cate_name != '' )				? wp_kses_post($cate_name)	: "";
								?>
							</div>
						<?php } ?>
					</li>

				<?php endwhile; ?>
			</ul>
		</div>

		<?php endif;

		wp_reset_postdata();

		echo $after_widget; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}
}