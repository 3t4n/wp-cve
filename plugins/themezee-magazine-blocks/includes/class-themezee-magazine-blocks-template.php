<?php
/**
 * Display a single post.
 *
 * @package ThemeZee Magazine Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ThemeZee Magazine Blocks Magazine Grid Class
 */
class ThemeZee_Magazine_Blocks_Template {
	/**
	 * Get Grid Post.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post content.
	 */
	static function get_grid_post( $attributes, $image_size, $show_content = true ) {
		$post_content = '';

		// Add Featured Image.
		$post_content .= self::get_post_image( $image_size );

		// Add Post Header.
		$post_content .= self::get_post_header( $attributes );

		// Show Excerpt?
		if ( true === $show_content && $attributes['excerptLength'] > 0 ) {
			$post_content .= self::get_post_content( $attributes );
		}

		// Wrap Post Content.
		$post_wrap = self::get_post_wrap( $post_content );

		return $post_wrap;
	}

	/**
	 * Get List Post.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post content.
	 */
	static function get_list_post( $attributes, $image_size, $show_content = true ) {
		// Get Featured Image.
		$post_image = self::get_post_image( $image_size );

		// Wrap post image.
		$post_image = sprintf( '<div class="tz-post-image">%s</div>', $post_image );

		// Get Post Header.
		$post_content = self::get_post_header( $attributes );

		// Show Excerpt?
		if ( true === $show_content && $attributes['excerptLength'] > 0 ) {
			$post_content .= self::get_post_content( $attributes );
		}

		// Wrap post content.
		$post_content = sprintf( '<div class="tz-post-content">%s</div>', $post_content );

		// Wrap Post.
		$post = self::get_post_wrap( $post_image . $post_content );

		return $post;
	}

	/**
	 * Get Post Wrap.
	 *
	 * @param array $post_content The post.
	 *
	 * @return string Returns the post wrap.
	 */
	static function get_post_wrap( $post_content ) {
		// Get Post classes as string.
		$post_classes = join( ' ', get_post_class( 'tz-magazine-post' ) );

		// Prefix .type-post.
		$post_classes = str_replace( 'type-', 'tz-type-', $post_classes );

		// Wrap post content into <article> tag.
		$post_article = sprintf(
			'<article id="post-%1$s" class="%2$s">%3$s</article>',
			get_the_ID(),
			$post_classes,
			$post_content
		);

		// Add Wrapper Div for Post Article.
		$post_wrap = sprintf( '<div class="tz-post-wrap">%s</div>', $post_article );

		return $post_wrap;
	}

	/**
	 * Get Post Image.
	 *
	 * @param array $image_size The image size.
	 *
	 * @return string Returns the post image.
	 */
	static function get_post_image( $image_size = 'post-thumbnail' ) {
		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail( null, $image_size );
		} else {
			$image = '<img src="' . THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_URL . 'public/images/default-featured-image.png" class="attachment-full size-full wp-post-image" width="1600" height="1200" alt />';
		}

		$figure = sprintf(
			'<figure class="tz-entry-image entry-image"><a href="%1$s" rel="bookmark">%2$s</a></figure>',
			esc_url( get_permalink() ),
			$image
		);

		return $figure;
	}

	/**
	 * Get Post Header.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post header.
	 */
	static function get_post_header( $attributes ) {
		$header_content = '';

		$show_meta = $attributes['showDate'] || $attributes['showAuthor'] || $attributes['showCategories'] || $attributes['showComments'];

		// Show Post Meta?
		if ( 'above-title' === $attributes['metaPosition'] && true === $show_meta ) {
			$header_content .= self::get_post_meta( $attributes );
		}

		// Add Title.
		$header_content .= self::get_post_title( $attributes );

		// Show Post Meta?
		if ( 'below-title' === $attributes['metaPosition'] && true === $show_meta ) {
			$header_content .= self::get_post_meta( $attributes );
		}

		// Wrap header content.
		$header = sprintf( '<header class="tz-entry-header entry-header">%s</header>', $header_content );

		return $header;
	}

	/**
	 * Get Post Title.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post title.
	 */
	static function get_post_title( $attributes ) {
		$title = sprintf(
			'<h2 class="tz-entry-title entry-title"><a href="%1$s" rel="bookmark">%2$s</a></h2>',
			esc_url( get_permalink() ),
			esc_html( get_the_title() )
		);

		return $title;
	}

	/**
	 * Get Post Meta.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post meta.
	 */
	static function get_post_meta( $attributes ) {
		$meta_content = '';

		// Show Date?
		if ( true === $attributes['showDate'] ) {
			$meta_content .= self::get_post_date( $attributes );
		}

		// Show Author?
		if ( true === $attributes['showAuthor'] ) {
			$meta_content .= self::get_post_author( $attributes );
		}

		// Show Categories?
		if ( true === $attributes['showCategories'] ) {
			$meta_content .= self::get_post_categories( $attributes );
		}

		// Show Comments?
		if ( true === $attributes['showComments'] ) {
			$meta_content .= self::get_post_comments( $attributes );
		}

		// Wrap header content.
		$postmeta = sprintf( '<div class="tz-entry-meta entry-meta">%s</div>', $meta_content );

		return $postmeta;
	}

	/**
	 * Get Post Date.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post date.
	 */
	static function get_post_date( $attributes ) {

		// Create date string.
		$time_string = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="published updated" datetime="%3$s">%4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		// Wrap date.
		$date = sprintf( '<span class="tz-meta-date meta-date tz-meta-field">%s</span>', $time_string );

		return $date;
	}

	/**
	 * Get Post Author.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post author.
	 */
	static function get_post_author( $attributes ) {

		// Create author string.
		$author_string = sprintf( '<a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( esc_html__( 'View all posts by %s', 'themezee-magazine-blocks' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);

		// Wrap author.
		$author = sprintf( '<span class="tz-meta-author meta-author tz-meta-field author vcard">%s</span>', $author_string );

		return $author;
	}

	/**
	 * Get Post Categories.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post categories.
	 */
	static function get_post_categories( $attributes ) {

		// Return early if post has no category.
		if ( ! has_category() ) {
			return;
		}

		$category_list = get_the_category_list( '<span class="tz-meta-categories-sep">, </span> ' );

		// Wrap categories.
		$categories = sprintf( '<span class="tz-meta-categories meta-categories tz-meta-field">%s</span>', $category_list );

		return $categories;
	}

	/**
	 * Get Post Comments.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post comments.
	 */
	static function get_post_comments( $attributes ) {

		// Return early if post has no comment function.
		if ( ! ( comments_open() || get_comments_number() ) ) {
			return;
		}

		// Start Output Buffering.
		ob_start();

		// Get Comment String.
		comments_popup_link(
			esc_html__( 'Leave a comment', 'themezee-magazine-blocks' ),
			esc_html__( 'One comment', 'themezee-magazine-blocks' ),
			esc_html__( '% comments', 'themezee-magazine-blocks' )
		);
		$comment_string = ob_get_contents();

		// End Output Buffering.
		ob_end_clean();

		// Wrap comments.
		$comments = sprintf( '<span class="tz-meta-comments meta-comments tz-meta-field">%s</span>', $comment_string );

		return $comments;
	}

	/**
	 * Get Post Content.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post content.
	 */
	static function get_post_content( $attributes ) {
		$post_content = '';

		// Add Excerpt.
		$post_content .= self::get_post_excerpt( $attributes );

		// Show Read More link?
		if ( '' !== $attributes['moreText'] ) {
			$post_content .= self::get_post_read_more_link( $attributes );
		}

		// Wrap post content.
		$content = sprintf( '<div class="tz-entry-content entry-content">%s</div>', $post_content );

		return $content;
	}

	/**
	 * Get Post Excerpt.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post excerpt.
	 */
	static function get_post_excerpt( $attributes ) {
		$post = get_post();

		if ( empty( $post ) ) {
			return '';
		}

		$post_excerpt = $post->post_excerpt;

		if ( ! ( $post_excerpt ) ) {
			$post_excerpt = $post->post_content;
		}

		// Strip Shortcodes.
		$post_excerpt = strip_shortcodes( $post_excerpt );

		// Trim to excerpt length.
		$trimmed_excerpt = esc_html( wp_trim_words( $post_excerpt, $attributes['excerptLength'], '' ) );

		// Wrap Excerpt.
		$excerpt = sprintf( '<p>%s <span class="tz-excerpt-more">[&hellip;]</span></p>', $trimmed_excerpt );

		return $excerpt;
	}

	/**
	 * Get Post Read More link.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post read more link.
	 */
	static function get_post_read_more_link( $attributes ) {
		$link = sprintf(
			'<p class="tz-read-more read-more"><a href="%1$s" class="tz-more-link more-link" rel="bookmark">%2$s</a></p>',
			esc_url( get_permalink() ),
			esc_html( $attributes['moreText'] )
		);

		return $link;
	}
}
