<?php
namespace ERocket\Widgets;

use WP_Widget;
use WP_Query;

class RecentPosts extends WP_Widget {
	private $defaults;

	public function __construct() {
		$this->defaults = [
			'title'      => __( 'Recent Posts', 'erocket' ),
			'category'   => '',
			'style'      => 'horizontal',
			'image_size' => 'thumbnail',
			'number'     => 5,
			'hide_date'  => false,
		];

		parent::__construct( 'erp', __( '[eRocket] Recent Posts', 'erocket' ), [
			'classname' => 'erp',
		] );

		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action( 'wp_head', [ $this, 'output_style' ] );
		}
	}

	public function output_style() {
		?>
		<style>
		.erp li {
			display: flex;
		}
		.erp li:not(:last-child) {
			margin-bottom: 16px;
		}
		.erp img {
			display: block;
		}
		.erp-vertical {
			flex-direction: column;
		}
		.erp-vertical > a {
			display: block;
			margin-bottom: 12px;
		}
		.erp-horizontal > a {
			display: block;
			margin-right: 12px;
		}
		.erp-horizontal img {
			width: 64px;
			height: 64px;
		}
		.erp-body {
			flex: 1;
			line-height: 1.25;
		}
		.erp a {
			font-weight: 700;
			color: inherit;
		}
		.erp time {
			display: block;
			margin-top: 4px;
			color: #a0aec0;
			font-size: .875em;
		}
		</style>
		<?php
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$query_args = [
			'posts_per_page'         => $instance['number'],
			'no_found_rows'          => true,
			'post_status'            => 'publish',
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		];
		if ( ! empty( $instance['category'] ) ) {
			$query_args['cat'] = $instance['category'];
		}

		$query = new WP_Query( $query_args );
		if ( ! $query->have_posts() ) {
			return;
		}

		echo $args['before_widget'];

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<ul>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<li class="<?php esc_attr_e( 'horizontal' === $instance['style'] ? 'erp-horizontal' : 'erp-vertical', 'erocket' ); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( $instance['image_size'] ); ?>
						</a>
					<?php endif; ?>
					<div class="erp-body">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<?php if ( ! $instance['hide_date'] ) : ?>
							<time class="time"><?= esc_html( get_the_date() ); ?></time>
						<?php endif; ?>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance               = $old_instance;
		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['category']   = absint( $new_instance['category'] );
		$instance['style']      = sanitize_text_field( $new_instance['style'] );
		$instance['image_size'] = sanitize_text_field( $new_instance['image_size'] );
		$instance['number']     = absint( $new_instance['number'] );
		$instance['hide_date']  = (bool) $new_instance['hide_date'];
		return $instance;
	}

	private function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = get_intermediate_image_sizes();

		$image_sizes = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
			$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		return array_merge( $image_sizes, $_wp_additional_image_sizes );
	}

	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'erocket' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Category:', 'erocket' ); ?></label>
			<?php
			wp_dropdown_categories( [
				'show_option_all' => __( 'All', 'erocket' ),
				'orderby'         => 'name',
				'order'           => 'ASC',
				'selected'        => $instance['category'],
				'hierarchical'    => true,
				'name'            => $this->get_field_name( 'category' ),
				'id'              => $this->get_field_id( 'category' ),
				'class'           => 'widefat',
			] );
			?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php esc_html_e( 'Style:', 'erocket' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
				<option <?php selected( 'horizontal', $instance['style'] ); ?> value="horizontal"><?php esc_html_e( 'Horizontal', 'erocket' ); ?></option>
				<option <?php selected( 'vertical', $instance['style'] ); ?> value="vertical"><?php esc_html_e( 'Vertical', 'erocket' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php esc_html_e( 'Image size:', 'erocket' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
				<?php
				$image_sizes = $this->get_image_sizes();
				foreach ( $image_sizes as $size_name => $size_atts ) :
					$name = ucwords( str_replace( [ '-', '_' ], ' ', $size_name ) );
					?>
					<option <?php selected( $size_name, $instance['image_size'] ); ?> value="<?php esc_html_e( $size_name, 'erocket' ); ?>"><?php printf( '%1s (%2sx%3s)', $name, $size_atts['width'], $size_atts['height'], 'erocket' ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3">
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts', 'erocket' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hide_date' ); ?>">
				<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_date' ); ?>" name="<?php echo $this->get_field_name( 'hide_date' ); ?>" value="1" <?php checked( $instance['hide_date'] ); ?>/>
				<?php esc_html_e( 'Hide post date', 'erocket' ); ?>
			</label>
		</p>
		<?php
	}
}
