<?php
/**
 * Flex Posts Widget
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Flex Posts widget class
 */
class Flex_Posts_Widget extends WP_Widget {

	/**
	 * Enqueue scripts & styles
	 */
	public function enqueue() {
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue' ), 9 );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
	}

	/**
	 * Register the stylesheets for widget.
	 */
	public function wp_enqueue() {
		wp_enqueue_style( 'flex-posts' );
	}

	/**
	 * Register the stylesheets & javascripts for widget admin.
	 */
	public function admin_enqueue() {
		wp_enqueue_style(
			'flex-posts-admin',
			FLEX_POSTS_URL . 'admin/css/widget-admin.css',
			array(),
			FLEX_POSTS_VERSION
		);
		wp_enqueue_script(
			'flex-posts-admin',
			FLEX_POSTS_URL . 'admin/js/widget-admin.js',
			array( 'jquery' ),
			FLEX_POSTS_VERSION,
			true
		);
		wp_localize_script(
			'flex-posts-admin',
			'flex_posts_admin',
			array(
				'taxonomies' => flex_posts_get_taxonomies(),
			)
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$fields = $this->get_fields();
		if ( empty( $fields ) ) {
			return;
		}

		$sections = array();
		foreach ( $fields as $key => $field ) {
			if ( 'section' === $field['type'] ) {
				$sections[ $key ] = $field['label'];
			}
		}
		?>

		<?php if ( ! empty( $sections ) ) : ?>
			<ul class="fp-tabs">
				<?php foreach ( $sections as $name => $label ) : ?>
					<li>
						<a class="fp-tab-item" data-target="fp-tab-<?php echo esc_attr( $name ); ?>">
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php foreach ( $fields as $key => $field ) : ?>

			<?php if ( 'section' === $field['type'] ) : ?>

				<section class="fp-tab fp-tab-<?php echo esc_attr( $key ); ?> active">

			<?php elseif ( 'section-end' === $field['type'] ) : ?>

				</section>

			<?php else : ?>

				<?php
				$name    = $this->get_field_name( $key );
				$id      = $this->get_field_id( $key );
				$default = isset( $field['default'] ) ? $field['default'] : '';
				$value   = isset( $instance[ $key ] ) ? $instance[ $key ] : $default;
				$min     = isset( $field['min'] ) ? $field['min'] : 0;
				$max     = isset( $field['max'] ) ? $field['max'] : null;
				?>
				<p>
					<?php if ( ! in_array( $field['type'], array( 'checkbox' ), true ) ) : ?>
						<label for="<?php echo esc_attr( $id ); ?>">
							<?php echo esc_html( $field['label'] ); ?>:
						</label>
					<?php endif; ?>

					<?php if ( 'text' === $field['type'] ) : ?>

						<?php
						flex_posts_input(
							array(
								'type'  => 'text',
								'name'  => $name,
								'id'    => $id,
								'class' => isset( $field['class'] ) ? $field['class'] : 'widefat',
								'value' => $value,
							)
						);
						?>

					<?php elseif ( 'number' === $field['type'] ) : ?>

						<?php
						flex_posts_input(
							array(
								'type'  => 'number',
								'name'  => $name,
								'id'    => $id,
								'class' => isset( $field['class'] ) ? $field['class'] : 'small-text',
								'value' => $value,
								'min'   => $min,
								'max'   => $max,
							)
						);
						?>

					<?php elseif ( 'textarea' === $field['type'] ) : ?>

						<?php
						flex_posts_textarea(
							array(
								'name'  => $name,
								'id'    => $id,
								'class' => isset( $field['class'] ) ? $field['class'] : 'widefat',
								'value' => $value,
							)
						);
						?>

					<?php elseif ( 'select' === $field['type'] ) : ?>

						<?php
						flex_posts_select(
							array(
								'name'       => $name,
								'id'         => $id,
								'class'      => isset( $field['class'] ) ? $field['class'] : 'widefat',
								'options'    => $field['options'],
								'selected'   => $value,
								'first'      => isset( $field['first'] ) ? $field['first'] : null,
								'multiple'   => isset( $field['multiple'] ) ? $field['multiple'] : false,
								'array_name' => isset( $field['array_name'] ) ? $field['array_name'] : false,
								'size'       => isset( $field['size'] ) ? $field['size'] : false,
							)
						);
						?>

					<?php elseif ( 'checkbox' === $field['type'] ) : ?>

						<?php
						flex_posts_input(
							array(
								'type'    => 'checkbox',
								'name'    => $name,
								'id'      => $id,
								'class'   => isset( $field['class'] ) ? $field['class'] : 'checkbox',
								'value'   => 1,
								'checked' => $value,
							)
						);
						?>
						<label for="<?php echo esc_attr( $id ); ?>">
							<?php echo esc_html( $field['label'] ); ?>
						</label>

					<?php elseif ( 'category' === $field['type'] ) : ?>

						<?php
						wp_dropdown_categories(
							array(
								'hide_empty'      => 0,
								'name'            => $name,
								'id'              => $id,
								'class'           => isset( $field['class'] ) ? $field['class'] : 'widefat',
								'hierarchical'    => 1,
								'show_option_all' => esc_html__( 'All Categories', 'flex_posts' ),
								'selected'        => $value,
							)
						);
						?>

					<?php endif; ?>

					<?php if ( ! empty( $field['desc'] ) ) : ?>
						<br><small><?php echo esc_html( $field['desc'] ); ?></small>
					<?php endif; ?>
				</p>

			<?php endif; ?>

		<?php endforeach; ?>

		<?php
	}

	/**
	 * Get layouts options
	 *
	 * @param  int|null $layouts Number of layouts.
	 * @return array
	 */
	protected function get_layouts_options( $layouts = null ) {
		if ( null === $layouts ) {
			$layouts = flex_posts_get_layouts();
		}
		$options = array();
		for ( $i = 1; $i <= $layouts; $i++ ) {
			$options[ $i ] = esc_html__( 'Layout', 'flex-posts' ) . ' ' . $i;
		}
		return $options;
	}

	/**
	 * Get form fields
	 *
	 * @return array Form fields
	 */
	public function get_fields() {
		/* ================ GENERAL ================= */

		$fields['general'] = array(
			'type'  => 'section',
			'label' => esc_html__( 'General', 'flex-posts' ),
		);

		$fields['title'] = array(
			'type'  => 'text',
			'label' => esc_html__( 'Title', 'flex-posts' ),
		);

		$fields['title_url'] = array(
			'type'  => 'text',
			'label' => esc_html__( 'Title URL', 'flex-posts' ),
		);

		$fields['layout'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Layout', 'flex-posts' ),
			'options' => $this->get_layouts_options(),
			'class'   => 'fp-layout widefat',
			'default' => 1,
		);

		$fields['general_end'] = array(
			'type' => 'section-end',
		);

		/* ================ QUERY ================= */

		$fields['query'] = array(
			'type'  => 'section',
			'label' => esc_html__( 'Query', 'flex-posts' ),
		);

		$fields['post_type'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Post Type', 'flex-posts' ),
			'options' => flex_posts_get_post_types( false ),
			'default' => 'post',
			'class'   => 'fp-query-post-type widefat',
		);

		$fields['cat'] = array(
			'type'  => 'category',
			'label' => esc_html__( 'Category', 'flex-posts' ),
			'class' => 'fp-query-category widefat',
		);

		$fields['tag'] = array(
			'type'  => 'text',
			'label' => esc_html__( 'Tag(s)', 'flex-posts' ),
			'class' => 'fp-query-tag widefat',
		);

		$fields['order_by'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Order by', 'flex-posts' ),
			'options' => flex_posts_get_order_by( false ),
			'default' => 'newest',
		);

		$fields['number'] = array(
			'type'    => 'number',
			'label'   => esc_html__( 'Number of posts to show', 'flex-posts' ),
			'default' => 4,
			'min'     => 1,
		);

		$fields['skip'] = array(
			'type'    => 'number',
			'label'   => esc_html__( 'Number of posts to skip', 'flex-posts' ),
			'default' => 0,
			'min'     => 0,
		);

		$fields['exclude_current'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Exclude current post', 'flex-posts' ),
			'default' => 0,
		);

		$fields['query_end'] = array(
			'type' => 'section-end',
		);

		/* ================ DISPLAY ================= */

		$fields['display'] = array(
			'type'  => 'section',
			'label' => esc_html__( 'Display', 'flex-posts' ),
		);

		$fields['show_image'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Show image on', 'flex-posts' ),
			'options' => array(
				'all'   => esc_html__( 'All posts', 'flex-posts' ),
				'first' => esc_html__( 'First post only', 'flex-posts' ),
				'none'  => esc_html__( 'None', 'flex-posts' ),
			),
			'class'   => 'fp-show-image widefat',
			'default' => 'all',
		);

		$fields['image_size'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Thumbnail image size', 'flex-posts' ),
			'options' => flex_posts_get_image_sizes( false ),
			'class'   => 'fp-image-size widefat',
			'default' => '',
		);

		$fields['image_size2'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Medium image size', 'flex-posts' ),
			'options' => flex_posts_get_image_sizes( false ),
			'class'   => 'fp-image-size2 widefat',
			'default' => '',
		);

		$fields['show_title'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show post title', 'flex-posts' ),
			'default' => 1,
		);

		$fields['show_categories'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show categories', 'flex-posts' ),
			'default' => 0,
		);

		$fields['show_author'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show author', 'flex-posts' ),
			'default' => 0,
		);

		$fields['show_date'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show date', 'flex-posts' ),
			'default' => 1,
		);

		$fields['show_comments'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show comments number', 'flex-posts' ),
			'default' => 1,
		);

		$fields['show_excerpt'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show excerpt', 'flex-posts' ),
			'class'   => 'fp-show-excerpt checkbox',
			'default' => 0,
		);

		$fields['excerpt_length'] = array(
			'type'    => 'number',
			'label'   => esc_html__( 'Excerpt length', 'flex-posts' ),
			'class'   => 'fp-excerpt-length small-text',
			'default' => 15,
		);

		$fields['show_readmore'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show read more link', 'flex-posts' ),
			'class'   => 'fp-show-readmore checkbox',
			'default' => 0,
		);

		$fields['readmore_text'] = array(
			'type'    => 'text',
			'label'   => esc_html__( 'Read more text', 'flex-posts' ),
			'class'   => 'fp-readmore-text widefat',
			'default' => '',
		);

		$fields['pagination'] = array(
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show pagination', 'flex-posts' ),
			'default' => 0,
		);

		$fields['display_end'] = array(
			'type' => 'section-end',
		);

		/* ================ ADVANCED ================= */

		$fields['advanced'] = array(
			'type'  => 'section',
			'label' => esc_html__( 'Advanced', 'flex-posts' ),
		);

		$fields['block_title_el'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Block Title HTML element', 'flex-posts' ),
			'options' => flex_posts_get_title_elements_options( false ),
			'default' => '',
		);

		$fields['post_title_el'] = array(
			'type'    => 'select',
			'label'   => esc_html__( 'Post Title HTML element', 'flex-posts' ),
			'options' => flex_posts_get_title_elements_options( false ),
			'default' => '',
		);

		$fields['className'] = array(
			'type'    => 'text',
			'label'   => esc_html__( 'Additional CSS class(es)', 'flex-posts' ),
			'default' => '',
		);

		$fields['advanced_end'] = array(
			'type' => 'section-end',
		);

		return $fields;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = empty( $new_instance['title'] ) ? '' : sanitize_text_field( $new_instance['title'] );

		$fields = $this->get_fields();
		if ( ! empty( $fields ) ) {
			foreach ( $fields as $key => $val ) {
				if ( empty( $val['type'] ) ) {
					continue;
				}
				switch ( $val['type'] ) {
					case 'text':
						$instance[ $key ] = empty( $new_instance[ $key ] ) ? '' : sanitize_text_field( $new_instance[ $key ] );
						break;

					case 'textarea':
						$instance[ $key ] = empty( $new_instance[ $key ] ) ? '' : sanitize_textarea_field( $new_instance['title'] );
						break;

					case 'checkbox':
						$instance[ $key ] = empty( $new_instance[ $key ] ) ? 0 : 1;
						break;

					case 'number':
						$instance[ $key ] = intval( $new_instance[ $key ] );
						break;

					case 'select':
					case 'category':
						if ( empty( $new_instance[ $key ] ) ) {
							$instance[ $key ] = '';
						} else {
							if ( is_array( $new_instance[ $key ] ) ) {
								$instance[ $key ] = array_map( 'sanitize_key', $new_instance[ $key ] );
							} else {
								$instance[ $key ] = sanitize_key( $new_instance[ $key ] );
							}
						}
						break;
				}
			}
		}

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$additional_classes = '';
		if ( ! empty( $instance['className'] ) ) {
			$additional_classes = ' ' . esc_attr( $instance['className'] );
		}

		if ( ! empty( $instance['block_title_el'] ) ) {
			if ( in_array( $instance['block_title_el'], flex_posts_get_title_elements(), true ) ) {
				$el = $instance['block_title_el'];

				$args['before_title'] = '<' . sanitize_key( $el ) . ' class="widget-title">';
				$args['after_title']  = '</' . sanitize_key( $el ) . '>';
			}
		}

		$before_widget = $args['before_widget'];
		if ( ! empty( $additional_classes ) ) {
			$before_widget = str_replace( $this->option_name, $this->option_name . $additional_classes, $before_widget );
		}
		echo $before_widget;  // @codingStandardsIgnoreLine.
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		echo $args['before_title'] . $title . $args['after_title'];  // @codingStandardsIgnoreLine.
		$this->front( $instance );
		echo $args['after_widget'];  // @codingStandardsIgnoreLine.
	}
}
