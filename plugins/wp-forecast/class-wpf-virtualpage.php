<?php
/**
 * This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2016  Hans Matzen  (email : webmaster at tuxlog.de)
 *
 * @package VirtualPage
 */

if ( ! class_exists( 'Wpf_VirtualPage' ) ) {
	/**
	 * Define class for creating virtual posts.
	 *
	 * This is a base class for creating virtual pages in WordPress
	 *
	 * @package wp-forecast
	 */
	class Wpf_VirtualPage {
		/**
		 * The Slug.
		 *
		 * @var string
		 */
		private $slug = null;
		/**
		 * The Title.
		 *
		 * @var string
		 */
		private $title = null;
		/**
		 * The Content.
		 *
		 * @var string
		 */
		private $content = null;
		/**
		 * The Author.
		 *
		 * @var string
		 */
		private $author = null;
		/**
		 * Date of publish
		 *
		 * @var date
		 */
		private $date = null;
		/**
		 * Typo of post
		 *
		 * @var string
		 */
		private $type = null;

		/**
		 * Constructor.
		 *
		 * @param array $args Constructor.
		 * @throws Exception Nothing special.
		 */
		public function __construct( $args ) {
			if ( ! isset( $args['slug'] ) ) {
				throw new Exception( 'Slug missing. Can not create virtual page' );
			}

			$this->slug    = $args['slug'];
			$this->title   = isset( $args['title'] ) ? $args['title'] : '';
			$this->content = isset( $args['content'] ) ? $args['content'] : '';
			$this->author  = isset( $args['author'] ) ? $args['author'] : 1;
			$this->date    = isset( $args['date'] ) ? $args['date'] : current_time( 'mysql' );
			$this->dategmt = isset( $args['date'] ) ? $args['date'] : current_time( 'mysql', 1 );
			$this->type    = isset( $args['type'] ) ? $args['type'] : 'page';

			add_filter( 'the_posts', array( &$this, 'virtual_page' ) );
		}

		/**
		 * Filter to create virtual page content.
		 *
		 * @param array $posts Parameter array for the virtual page.
		 */
		public function virtual_page( $posts ) {
			global $wp, $wp_query;

			if ( count( $posts ) === 0 &&
				( strcasecmp( $wp->request, $this->slug ) === 0 || $wp->query_vars['page_id'] === $this->slug ) ) {
				// create a fake post intance.
				$post = new stdClass();
				// fill properties of $post with everything a page in the database would have.
				$post->ID                    = -1;                  // use an illegal value for page ID.
				$post->post_author           = $this->author;       // post author id.
				$post->post_date             = $this->date;         // date of post.
				$post->post_date_gmt         = $this->dategmt;
				$post->post_content          = $this->content;
				$post->post_title            = $this->title;
				$post->post_excerpt          = '';
				$post->post_status           = 'publish';
				$post->comment_status        = 'closed';        // mark as closed for comments, since page doesn't exist.
				$post->ping_status           = 'closed';        // mark as closed for pings, since page doesn't exist.
				$post->post_password         = '';              // no password.
				$post->post_name             = $this->slug;
				$post->to_ping               = '';
				$post->pinged                = '';
				$post->modified              = $post->post_date;
				$post->modified_gmt          = $post->post_date_gmt;
				$post->post_content_filtered = '';
				$post->post_parent           = 0;
				$post->guid                  = get_home_url( '/' . $this->slug );
				$post->menu_order            = 0;
				$post->post_tyle             = $this->type;
				$post->post_mime_type        = '';
				$post->comment_count         = 0;

				// set filter results.
				$posts = array( $post );

				// reset wp_query properties to simulate a found page.
				$wp_query->is_page     = true;
				$wp_query->is_singular = true;
				$wp_query->is_home     = false;
				$wp_query->is_archive  = false;
				$wp_query->is_category = false;
				unset( $wp_query->query['error'] );
				$wp_query->query_vars['error'] = '';
				$wp_query->is_404              = false;
			}

			return ( $posts );
		}
	}
}
