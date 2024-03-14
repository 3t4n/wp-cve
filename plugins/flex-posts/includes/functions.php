<?php
/**
 * Functions used in widgets and blocks
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register style sheet
 */
function flex_posts_register_style() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_register_style(
		'flex-posts',
		FLEX_POSTS_URL . 'public/css/flex-posts' . $suffix . '.css',
		array(),
		FLEX_POSTS_VERSION
	);
}
add_action( 'init', 'flex_posts_register_style' );

/**
 * Get args for WP Query
 *
 * @param  array $instance Attributes.
 * @return array Args
 */
function flex_posts_get_query_args( $instance ) {
	$args['ignore_sticky_posts'] = true;
	$args['post_status']         = 'publish';
	$args['orderby']             = 'date';
	$args['order']               = 'desc';

	if ( ! empty( $instance['order_by'] ) ) {
		switch ( $instance['order_by'] ) {
			case 'oldest':
				$args['order'] = 'asc';
				break;
			case 'title':
				$args['orderby'] = 'title';
				$args['order']   = 'asc';
				break;
			case 'comments':
				$args['orderby'] = 'comment_count';
				break;
			case 'random':
				$args['orderby'] = 'rand';
				break;
			case 'modified':
				$args['orderby'] = 'modified';
				break;
			case 'menu_order':
				$args['orderby'] = 'menu_order';
				$args['order']   = 'asc';
				break;
		}
	}

	if ( ! empty( $instance['post_type'] ) ) {
		$args['post_type'] = $instance['post_type'];
	} else {
		$args['post_type'] = 'post';
	}

	if ( ! empty( $instance['cat'] ) ) {
		$args['cat'] = absint( $instance['cat'] );
	}

	if ( ! empty( $instance['tag'] ) ) {
		$tags = explode( ',', sanitize_text_field( wp_unslash( $instance['tag'] ) ) );

		$include_tag_id = array();
		$exclude_tag_id = array();
		foreach ( $tags as $tag ) {
			$tag = trim( $tag );
			if ( strpos( $tag, '-' ) === 0 ) {
				$tag  = substr( $tag, 1 );
				$term = get_term_by( 'slug', $tag, 'post_tag' );
				if ( ! empty( $term ) ) {
					$exclude_tag_id[] = $term->term_id;
				}
			} else {
				$term = get_term_by( 'slug', $tag, 'post_tag' );
				if ( ! empty( $term ) ) {
					$include_tag_id[] = $term->term_id;
				}
			}
		}

		if ( ! empty( $include_tag_id ) ) {
			$args['tag__in'] = $include_tag_id;
		}

		if ( ! empty( $exclude_tag_id ) ) {
			$args['tag__not_in'] = $exclude_tag_id;
		}
	}

	if ( isset( $instance['number'] ) ) {
		$args['posts_per_page'] = intval( $instance['number'] );
	}

	if ( ! empty( $instance['skip'] ) ) {
		$args['offset'] = absint( $instance['skip'] );
		if ( 'rand' === $args['orderby'] ) {
			// Make offset and order by random working together.
			$args2 = $args;
			unset( $args2['offset'] );

			$args2['posts_per_page'] = $args['offset'];
			$args2['orderby']        = 'date';
			$args2['order']          = 'desc';
			$args2['fields']         = 'ids';

			$query2 = new WP_Query( $args2 );

			if ( ! empty( $query2->posts ) ) {
				$args['post__not_in'] = $query2->posts;
				unset( $args['offset'] );
			}
		}
	}

	if ( ! empty( $instance['exclude_current'] ) ) {
		$args['post__not_in'][] = get_the_ID();
	}

	if ( empty( $instance['pagination'] ) ) {
		$args['no_found_rows'] = true;
	} else {
		$args['paged'] = flex_posts_get_current_page();
		if ( ! empty( $args['offset'] ) ) {
			// Modify offset value if pagination is active.
			$old_offset         = $args['offset'];
			$new_offset         = $old_offset + ( ( $args['paged'] - 1 ) * $args['posts_per_page'] );
			$args['offset']     = $new_offset;
			$args['old_offset'] = $old_offset;
		}
	}

	return $args;
}

/**
 * Get allowed html tags
 *
 * @return array
 */
function flex_posts_get_allowed_html() {
	$attr = array(
		'class' => array(),
		'title' => array(),
	);

	$allowed_html = apply_filters(
		'flex_posts_allowed_html',
		array(
			'a'    => $attr + array( 'href' => array() ),
			'ul'   => $attr,
			'li'   => $attr,
			'span' => $attr + array( 'aria-current' => array() ),
		)
	);
	return $allowed_html;
}

/**
 * Get current page for pagination
 *
 * @return int
 */
function flex_posts_get_current_page() {
	if ( get_query_var( 'paged' ) ) {
		$current_page = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$current_page = get_query_var( 'page' );
	} else {
		$current_page = 1;
	}
	return $current_page;
}

/**
 * Display pagination
 *
 * @param int $total The total amount of pages.
 */
function flex_posts_pagination( $total ) {
	if ( $total > 1 ) {
		$links = paginate_links(
			apply_filters(
				'flex_posts_pagination_args',
				array(
					'total'     => $total,
					'current'   => flex_posts_get_current_page(),
					'mid_size'  => 1,
					'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'flex-posts' ) . '</span> <span aria-hidden="true">&laquo;</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'flex-posts' ) . '</span> <span aria-hidden="true">&raquo;</span>',
				)
			)
		);

		if ( $links ) {
			echo '<div class="fp-pagination">';
			echo '<span class="screen-reader-text">';
			echo esc_html__( 'Page', 'flex-posts' );
			echo ': </span>';
			echo wp_kses( $links, flex_posts_get_allowed_html() );
			echo '</div>';
		}
	}
}

/**
 * Front-end display of widget.
 *
 * @param array  $instance Attributes.
 * @param string $type     Widget type.
 */
function flex_posts_display( $instance, $type = 'list' ) {
	$args = apply_filters( 'flex_posts_' . $type . '_args', flex_posts_get_query_args( $instance ), $instance );

	$query = new WP_Query( $args );

	$layout = 1;
	if ( ! empty( $instance['layout'] ) ) {
		$layout = absint( $instance['layout'] );
	}

	$thumbnail_size = apply_filters( 'flex_posts_thumbnail_size', 'thumbnail', $instance );
	$medium_size    = apply_filters( 'flex_posts_medium_size', '400x250-crop', $instance );

	if ( ! empty( $instance['image_size'] ) ) {
		$thumbnail_size = $instance['image_size'];
	}

	if ( ! empty( $instance['image_size2'] ) ) {
		$medium_size = $instance['image_size2'];
	}

	if ( ! isset( $instance['show_image'] ) ) {
		$instance['show_image'] = 'all';
	}

	if ( ! isset( $instance['show_title'] ) ) {
		$instance['show_title'] = true;
	}

	$excerpt_length = 15;
	if ( ! empty( $instance['excerpt_length'] ) ) {
		$excerpt_length = absint( $instance['excerpt_length'] );
	}

	$readmore_text = __( 'Read more', 'flex-posts' );
	if ( ! empty( $instance['readmore_text'] ) ) {
		$readmore_text = $instance['readmore_text'];
	}

	$file      = "flex-posts-{$type}-{$layout}.php";
	$directory = apply_filters( 'flex_posts_template_directory', '' );
	$template  = locate_template( $directory . $file );
	if ( empty( $template ) ) {
		$template = apply_filters( 'flex_posts_template', FLEX_POSTS_DIR . 'public/' . $file, $type, $layout );
	}

	if ( ! file_exists( $template ) ) {
		return;
	}

	if ( $query->have_posts() ) {
		if ( ! empty( $instance['show_excerpt'] ) ) {
			add_filter( 'excerpt_more', '__return_null' );
		}

		include $template;
		do_action( 'flex_posts_end', $instance, $args, $query->max_num_pages, $query->found_posts );
		wp_reset_postdata();

		if ( ! empty( $instance['show_excerpt'] ) ) {
			remove_filter( 'excerpt_more', '__return_null' );
		}
	}
}

/**
 * Add pagination at the end of widget
 *
 * @param array $instance      Widget settings.
 * @param array $args          Query arguments.
 * @param int   $max_num_pages Total number of pages.
 * @param int   $found_posts   Total number of posts found.
 */
function flex_posts_end( $instance, $args, $max_num_pages, $found_posts ) {
	if ( ! empty( $instance['pagination'] ) ) {
		if ( ! empty( $args['old_offset'] ) ) {
			// Modify max_num_pages value if offset is set.
			$found_posts   = $found_posts - $args['old_offset'];
			$max_num_pages = ceil( $found_posts / $args['posts_per_page'] );
		}
		flex_posts_pagination( $max_num_pages );
	}
}
add_action( 'flex_posts_end', 'flex_posts_end', 10, 4 );

/**
 * Render block
 *
 * @param  array  $attributes Attributes.
 * @param  string $type       Block type.
 * @return string
 */
function flex_posts_render( $attributes, $type = 'list' ) {
	ob_start();
	$class = '';
	if ( ! empty( $attributes['align'] ) ) {
		$class .= ' align' . $attributes['align'];
	}
	if ( ! empty( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}
	echo '<section class="widget widget_flex-posts-' . esc_attr( $type ) . esc_attr( $class ) . '">';
	if ( ! empty( $attributes['title'] ) ) {
		$title = apply_filters( 'flex_posts_block_title', $attributes['title'], $attributes, 'flex-posts-' . $type );

		$el = 'h2';
		if ( ! empty( $attributes['block_title_el'] ) ) {
			if ( in_array( $attributes['block_title_el'], flex_posts_get_title_elements(), true ) ) {
				$el = $attributes['block_title_el'];
			}
		}

		echo '<' . sanitize_key( $el ) . ' class="widget-title">';
		echo wp_kses( $title, flex_posts_get_allowed_html() );
		echo '</' . sanitize_key( $el ) . '>';
	}

	flex_posts_display( $attributes, $type );

	echo '</section>';
	$display = ob_get_clean();
	return $display;
}

/**
 * Get number of layouts
 *
 * @return int
 */
function flex_posts_get_layouts() {
	return apply_filters( 'flex_posts_layouts', 4 );
}

/**
 * Get post types
 *
 * @param  string $block Block.
 * @return array
 */
function flex_posts_get_post_types( $block = true ) {
	$post_types['post'] = __( 'Post', 'flex-posts' );
	$post_types['page'] = __( 'Page', 'flex-posts' );

	$get_post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => false,
		),
		'objects'
	);

	foreach ( $get_post_types as $post_type ) {
		$post_types[ $post_type->name ] = $post_type->labels->singular_name;
	}

	$post_types['any'] = __( 'Any', 'flex-posts' );

	if ( ! $block ) {
		return $post_types;
	}

	foreach ( $post_types as $value => $label ) {
		$post_types2[] = array(
			'label' => $label,
			'value' => $value,
		);
	}
	return $post_types2;
}

/**
 * Get taxonomies
 *
 * @return array
 */
function flex_posts_get_taxonomies() {
	$post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => false,
		),
		'names'
	);

	$taxonomies['post'] = get_object_taxonomies( 'post' );
	$taxonomies['page'] = get_object_taxonomies( 'page' );
	foreach ( $post_types as $post_type ) {
		$taxonomies[ $post_type ] = get_object_taxonomies( $post_type );
	}
	return $taxonomies;
}

/**
 * Get order by data
 *
 * @param  string $block Block.
 * @return array
 */
function flex_posts_get_order_by( $block = true ) {
	$data = apply_filters(
		'flex_posts_order_by',
		array(
			'newest'     => esc_html__( 'Newest', 'flex-posts' ),
			'oldest'     => esc_html__( 'Oldest', 'flex-posts' ),
			'comments'   => esc_html__( 'Most commented', 'flex-posts' ),
			'title'      => esc_html__( 'Alphabetical', 'flex-posts' ),
			'random'     => esc_html__( 'Random', 'flex-posts' ),
			'modified'   => esc_html__( 'Modified date', 'flex-posts' ),
			'menu_order' => esc_html__( 'Page order', 'flex-posts' ),
		)
	);

	if ( ! $block ) {
		return $data;
	}

	$data2 = array();
	foreach ( $data as $value => $label ) {
		$data2[] = array(
			'label' => $label,
			'value' => $value,
		);
	}
	return $data2;
}

/**
 * Get image sizes data
 *
 * @param  string $block Block.
 * @return array
 */
function flex_posts_get_image_sizes( $block = true ) {
	$image_sizes[''] = __( 'Default', 'flex-posts' );

	foreach ( get_intermediate_image_sizes() as $size ) {
		$image_sizes[ $size ] = $size;
	}

	if ( ! $block ) {
		return $image_sizes;
	}

	$image_sizes2 = array();
	foreach ( $image_sizes as $value => $label ) {
		$image_sizes2[] = array(
			'label' => $label,
			'value' => $value,
		);
	}
	return $image_sizes2;
}

/**
 * Add link to widget title
 *
 * @param  string $title    Title.
 * @param  array  $instance Instance.
 * @param  string $id_base  ID Base.
 * @return string
 */
function flex_posts_widget_title( $title, $instance = array(), $id_base = '' ) {
	if ( strpos( $id_base, 'flex-posts' ) === 0 && ! empty( $instance['title_url'] ) ) {
		$title = '<a href="' . esc_url( $instance['title_url'] ) . '">' . $title . '</a>';
	}
	return $title;
}
add_filter( 'widget_title', 'flex_posts_widget_title', 10, 3 );
add_filter( 'flex_posts_block_title', 'flex_posts_widget_title', 10, 3 );

/**
 * Get html elements allowed for title
 *
 * @return array
 */
function flex_posts_get_title_elements() {
	$elements = apply_filters(
		'flex_posts_allowed_title_elements',
		array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span' )
	);
	return $elements;
}

/**
 * Get title elements options
 *
 * @param  string $block Block.
 * @return array
 */
function flex_posts_get_title_elements_options( $block = true ) {
	$options[''] = __( 'Default', 'flex-posts' );

	foreach ( flex_posts_get_title_elements() as $el ) {
		$options[ $el ] = $el;
	}

	if ( ! $block ) {
		return $options;
	}

	$options2 = array();
	foreach ( $options as $value => $label ) {
		$options2[] = array(
			'label' => $label,
			'value' => $value,
		);
	}
	return $options2;
}
