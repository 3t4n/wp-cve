<?php
/**
 * Renderer for slides created from posts data.
 * 
 * @since 4.0.0
 */
class BQW_SP_Posts_Slide_Renderer extends BQW_SP_Dynamic_Slide_Renderer {

	/**
	 * Initialize the renderer by declaring the supported tags.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->registered_tags = array(
			'image' => array( $this, 'render_image' ),
			'image_src' => array( $this, 'render_image_src' ),
			'image_alt' => array( $this, 'render_image_alt' ),
			'image_title' => array( $this, 'render_image_title' ),
			'image_description' => array( $this, 'render_image_description' ),
			'image_caption' => array( $this, 'render_image_caption' ),
			'title' => array( $this, 'render_title' ),
			'link' => array( $this, 'render_link' ),
			'link_url' => array( $this, 'render_link_url' ),
			'date' => array( $this, 'render_date' ),
			'excerpt' => array( $this, 'render_excerpt' ),
			'content' => array( $this, 'render_content' ),
			'category' => array( $this, 'render_category' ),
			'custom' => array( $this, 'render_custom' )
		);

		$this->registered_tags = apply_filters( 'sliderpro_posts_tags', $this->registered_tags );
	}

	/**
	 * Return the final HTML markup of the slide.
	 *
	 * @since  1.0.0
	 *
	 * @return string The slide HTML.
	 */
	public function render() {
		parent::render();
		
		$result = $this->query();
		$this->html_output = $this->replace_tags( $result );

		return do_shortcode( $this->html_output );
	}

	/**
	 * Query the database based on the specified slide settings
	 * using WP_Query.
	 *
	 * @since 4.0.0
	 * 
	 * @return object The wp_query object.
	 */
	protected function query() {
		$query_args = array();

		$post_types = $this->get_setting_value( 'posts_post_types' );

		if ( ! empty( $post_types ) ) {
			$query_args['post_type'] = $post_types;
		}

		$taxonomies = $this->get_setting_value( 'posts_taxonomies' );

		if ( ! empty( $taxonomies ) ) {
			$tax_query = array();

			foreach ( $taxonomies as $taxonomy_term_raw ) {
				$taxonomy_term = explode( '|', $taxonomy_term_raw );
				
				$tax_item['taxonomy'] = $taxonomy_term[0];
				$tax_item['terms'] = $taxonomy_term[1];
				$tax_item['field'] = 'slug';
				
				$tax_item['operator'] = $this->get_setting_value( 'posts_operator' );
				
				array_push( $tax_query, $tax_item );
			}
			
			if ( count( $taxonomies ) > 1 ) {
				$tax_query['relation'] = $this->get_setting_value( 'posts_relation' );
			}

			$query_args['tax_query'] = $tax_query;
		}
		
		$query_args['posts_per_page'] = $this->get_setting_value( 'posts_maximum' );
		$query_args['orderby'] = $this->get_setting_value( 'posts_order_by' );
		$query_args['order'] = $this->get_setting_value( 'posts_order' );

		$query_args = apply_filters( 'sliderpro_posts_query_args' , $query_args, $this->slider_id, $this->slide_index );
		
		$query = new WP_Query( $query_args );

		return $query;
	}

	/**
	 * Replace the registered tags with actual content
	 * and return the final HTML markup of the slide.
	 *
	 * @since 4.0.0
	 *
	 * @param  $query The wp_query object.
	 * @return string The slide's HTML markup.
	 */
	protected function replace_tags( $query ) {
		$final_html = '';

		$tags = $this->get_slide_tags();

		while ( $query->have_posts() ) {
			$query->the_post();

			global $post;

			$content = $this->html_output;

			foreach ( $tags as $tag ) {
				$result = $this->render_tag( $tag['name'], $tag['arg'], $post );
				$content = str_replace( $tag['full'], $result, $content );
			}

			$final_html .= $content;
		}

		wp_reset_postdata();

		return $final_html;
	}

	/**
	 * Return the featured image of the post as an HTML image element.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The image size.
	 * @param  object $post    The current post.
	 * @return string          The image HTML.
	 */
	protected function render_image( $tag_arg, $post ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}

		$image_size = $tag_arg !== false ? $tag_arg : 'full';
		$image_full = get_the_post_thumbnail( $post->ID, $image_size, array( 'class' => '' ) );

		return $image_full;
	}

	/**
	 * Return the URL of the post's featured image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The image size.
	 * @param  object $post    The current post.
	 * @return string          The image URL.
	 */
	protected function render_image_src( $tag_arg, $post ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}

		$image_size = $tag_arg !== false ? $tag_arg : 'full';
		$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $image_size );

		return $image_src[0];
	}

	/**
	 * Return the alt text of the post's featured image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The image alt.
	 */
	protected function render_image_alt( $tag_arg, $post ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}

		$image_alt = get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt' );
		$image_alt_value = ! empty ( $image_alt ) ? $image_alt[0] : '';

		return $image_alt_value;
	}

	/**
	 * Return the title of the post's featured image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The image title.
	 */
	protected function render_image_title( $tag_arg, $post ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}

		$image_data = get_post( get_post_thumbnail_id(), ARRAY_A );

		return $image_data['post_title'];
	}

	/**
	 * Return the description of post's the featured image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The image description.
	 */
	protected function render_image_description( $tag_arg, $post ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}

		$image_data = get_post( get_post_thumbnail_id(), ARRAY_A );

		return $image_data['post_content'];
	}

	/**
	 * Return the caption of the post's featured image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The image caption.
	 */
	protected function render_image_caption( $tag_arg, $post ) {
		if ( ! has_post_thumbnail( $post->ID ) ) {
			return;
		}

		$image_data = get_post( get_post_thumbnail_id(), ARRAY_A );

		return $image_data['post_excerpt'];
	}

	/**
	 * Return the title of the post.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The title.
	 */
	protected function render_title( $tag_arg, $post ) {
		return get_the_title();
	}

	/**
	 * Return the link of the post as an HTML anchor element.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The link HTML.
	 */
	protected function render_link( $tag_arg, $post ) {
		$link = '<a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a>';

		return $link;
	}

	/**
	 * Return the link of the post.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The link.
	 */
	protected function render_link_url( $tag_arg, $post ) {
		return get_permalink( $post->ID );
	}

	/**
	 * Return the date of the post.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The date format.
	 * @param  object $post    The current post.
	 * @return string          The date.
	 */
	protected function render_date( $tag_arg, $post ) {
		$date_format = $tag_arg !== false ? $tag_arg : get_option( 'date_format' );
		
		return get_post_time( $date_format, false, $post->ID, true );
	}

	/**
	 * Return the post's excerpt.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The excerpt.
	 */
	protected function render_excerpt( $tag_arg, $post ) {
		return $post->post_excerpt;
	}

	/**
	 * Return the post's content.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The content.
	 */
	protected function render_content( $tag_arg, $post ) {
		return $post->post_content;
	}

	/**
	 * Return the category of the post.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $post    The current post.
	 * @return string          The category.
	 */
	protected function render_category( $tag_arg, $post ) {
		$categories = get_the_category( $post->ID );
		$category = ! empty( $categories ) ? $categories[0]->name : '';

		return $category;
	}

	/**
	 * Return the value specified in the custom field.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. Name of the custom field.
	 * @param  object $post    The current post.
	 * @return string          The custom field value.
	 */
	protected function render_custom( $tag_arg, $post ) {
		$value = '';

		if ( $tag_arg !== false ) {
			$values = get_post_meta( $post->ID, $tag_arg );
			$value = ! empty( $values ) ? $values[0] : '';
		}

		return $value;
	}
}