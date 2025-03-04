<?php
/**
 * Plugin Name:  WordPress Related Posts Thumbnails
 * Plugin URI:   https://wpbrigade.com/wordpress/plugins/related-posts/?utm_source=related-posts-lite&utm_medium=plugin-uri&utm_campaign=pro-upgrade-rp
 * Description:  Showing related posts thumbnails under the posts.
 * Version:      3.0.2
 * Author:       WPBrigade
 * Author URI:   https://WPBrigade.com/?utm_source=related-posts-lite&utm_medium=author-link&utm_campaign=pro-upgrade-rp
 */

/*
Copyright 2010 - 2023 WPBrigade.com

This product was first developed by Maria I Shaldybina and later on maintained and developed by Adnan (WPBrigade.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
class RelatedPostsThumbnails {
	/* Default values. 
	 * PHP 8.0 compatible
	 * */
	public $single_only        = '1';
	public $auto               = '1';
	public $top_text           = '<h3>Related posts:</h3>';
	public $number             = 3;
	public $relation           = 'categories';
	public $poststhname        = 'thumbnail';
	public $background         = '#ffffff';
	public $hoverbackground    = '#eeeeee';
	public $border_color       = '#dddddd';
	public $font_color         = '#333333';
	public $font_family        = 'Arial';
	public $font_size          = '12';
	public $text_length        = '100';
	public $excerpt_length     = '0';
	public $custom_field       = '';
	public $custom_height      = '100';
	public $custom_width       = '100';
	public $text_block_height  = '75';
	public $thsource           = 'post-thumbnails';
	public $categories_all     = '1';
	public $devmode            = '0';
	public $format             = 'j F, Y';
	public $output_style       = 'div';
	public $post_types         = array( 'post' );
	public $custom_taxonomies  = array();
	public $default_image      = '';
	public $wp_version         = '';

	protected $wp_kses_rp_args = array( 'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(), 'strong' => array() );
	protected static $instance = null;

	/**
	 * Function Constructor 
	 */
	function __construct() {

		$this->constant();

		// Load text domain for translation
		load_plugin_textdomain( 'related-posts-thumbnails', false, basename( dirname( __FILE__ ) ) . '/locale' );
		$this->default_image = esc_url( plugins_url( 'img/default.png', __FILE__ ) );

		include_once RELATED_POSTS_THUMBNAILS_PLUGIN_DIR . '/lib/wpb-sdk/init.php';
		new RPT_WPB_SDK\Logger( array(
			'name'	=> 'Related Posts Thumbnails Plugin for WordPress',
			'slug'	=> 'related-posts-thumbnails',
			'path'	=> __FILE__,
			'version'	=> RELATED_POSTS_THUMBNAILS_VERSION,
			'license'	=> '',
			'settings'	=> array(
				'relpoststh_default_image'      => false,
				'rpt_active_time'               => false,
				'relpoststh_single_only'        => false,
				'relpoststh_mobile_view'        => false,
				'relpoststh_post_types'         => false,
				'relpoststh_onlywiththumbs'     => false,
				'relpoststh_output_style'       => false,
				'relpoststh_cleanhtml'          => false,
				'relpoststh_auto'               => false,
				'relpoststh_top_text'           => false,
				'relpoststh_number'             => false,
				'relpoststh_relation'           => false,
				'relpoststh_poststhname'        => false,
				'relpoststh_background'         => false,
				'relpoststh_hoverbackground'    => false,
				'relpoststh_bordercolor'        => false,
				'relpoststh_fontcolor'          => false,
				'relpoststh_fontsize'           => false,
				'relpoststh_fontfamily'         => false,
				'relpoststh_textlength'         => false,
				'relpoststh_excerptlength'      => false,
				'relpoststh_thsource'           => false,
				'relpoststh_customfield'        => false,
				'relpoststh_theme_resize_url'   => false,
				'relpoststh_customwidth'        => false,
				'relpoststh_customheight'       => false,
				'relpoststh_textblockheight'    => false,
				'rpt_post_sort'                 => false,
				'relpoststh_categories'         => false,
				'relpoststh_categoriesall'      => false,
				'relpoststh_show_categoriesall' => false,
				'relpoststh_show_categories'    => false,
				'relpoststh_devmode'            => false,
				'relpoststh_startdate'          => false,
				'relpoststh_custom_taxonomies'  => false,
				'relpoststh_show_taxonomy'           => false,

			),
		) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Compatibility for old default image path.
		if ( $this->is_old_default_img() )
		update_option( 'relpoststh_default_image', $this->default_image );

		if ( get_option( 'relpoststh_auto', $this->auto ) ) {
			$priority = apply_filters( 'rpt_content_prioirty', 10 ); //    Alter priority of the related post content				

			if ( $this->prevent_on_editors() ) {
				return;
			}

			// Disable related posts on mobile view.
			if ( wp_is_mobile() && '1' ==  get_option( 'relpoststh_mobile_view', '0' ) ) {
				return;
			}

			add_filter( 'the_content', array( $this, 'auto_show' ), $priority );
		}

		add_action( 'admin_menu', array( $this, 'admin_menu'  ) );

		$this->wp_version = get_bloginfo( 'version' );

		add_action( 'admin_init', array( $this, 'review_notice' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );

		add_action( 'wp_head', array( $this, 'head_style' ) );

		add_shortcode( 'related-posts-thumbnails', array( $this, 'related_posts_shortcode' ) );

	}

	/**
	* Prevent related posts on editor screen (builders).
	* 
	* @return bool $return
	*/
	function prevent_on_editors() {
		$return = false;
		$prevent_on_edit = apply_filters( 'rpt_prevent_on_edit', array(  'divi' => false, ) );

		foreach ( $prevent_on_edit as $key => $value ) {
			switch ( $key ) {
				case 'divi':
					if ( $value && isset( $_GET['et_fb'] ) ) {
						$return = true;
					}
				break;
			}
		}

		return $return;
	}

	/**
	* callback for shortcode related_posts_shortcode
	*
	* @param array $atts attributes of shortcode

	* @version 1.9.0
	*/
	function related_posts_shortcode( $atts ) {

		$atts = shortcode_atts( array(
			'posts_number'	=> '3',
			'posts_sort'	=> 'random',
			'main_title'	=> '',
			'exclude_post'		=> '' ), 
			$atts, 'related-posts-thumbnails'
		);

		$number = $atts['posts_number'];

		if ( $atts['posts_sort'] == 'random' ) {
			$sort = 'rand()';
		} elseif ( $atts['posts_sort'] == 'latest' ) {
			$sort = 'post_date';
		}

		//sanitization through regex expression to know if a string is consisting of numeric values.
		$regex = '/^\d+(?:,\d+)*$/';
		$excluded_posts_array =  preg_match( $regex, $atts['exclude_post'] ) ? $atts['exclude_post'] : array();

		if ( !is_numeric( $number ) ) {
			$number = 3;
		}

		$main_title = str_replace( '_', ' ', $atts['main_title'] );

		return $this->get_thumbnails( true, $number, $sort, $main_title, $excluded_posts_array );

	}

	/**
	* Function to enqueue admin styles and scripts.
	*
	* @param $page
	* @return void
	* @since 1.7.0
	* @version 1.9.0
	* 
	*/
	function admin_scripts( $page ) {
		if ( 'toplevel_page_related-posts-thumbnails' === $page ) {
			wp_enqueue_media();
			wp_enqueue_style( 'rpt_admin_css', plugins_url( 'assets/css/admin.css', __FILE__ ), false, RELATED_POSTS_THUMBNAILS_VERSION );
			wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
			// Enqueue Chosen CSS
			wp_enqueue_style('rpt-chosen', plugins_url('assets/css/chosen.min.css', __FILE__), array(), RELATED_POSTS_THUMBNAILS_VERSION);

			// Enqueue jQuery (if not already included by WordPress)
			if (!wp_script_is('jquery', 'enqueued')) {
				wp_enqueue_script('jquery');
			}
			
			// Enqueue Chosen JS
			wp_enqueue_script('rpt-chosen', plugins_url('assets/js/chosen.jquery.min.js', __FILE__), array('jquery'), RELATED_POSTS_THUMBNAILS_VERSION, true);
			
			// Enqueue other scripts
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( 'assets/js/wp-color-picker-alpha.js',  __FILE__ ), array( 'wp-color-picker' ), RELATED_POSTS_THUMBNAILS_VERSION, true );
		
			wp_enqueue_script( 'rpt_admin_js', plugins_url( 'assets/js/admin.js', __FILE__ ), 
				array(
					'jquery',
					'wp-color-picker',
					'jquery-ui-datepicker',
					'rpt-chosen'
				), RELATED_POSTS_THUMBNAILS_VERSION 
			);
		}
	}

	/**
	* Function to enqueue front styles and scripts.
	*
	* @param $page
	* @return void
	* @since 1.7.0
	* @version 1.9.0
	* 
	*/
	function front_scripts() {
		wp_enqueue_style( 'rpt_front_style', plugins_url( 'assets/css/front.css', __FILE__ ), false, RELATED_POSTS_THUMBNAILS_VERSION );
	}

	/**
	* Function to define plugin Constants
	* 
	* @return void
	*/
	function constant() {
		define( 'RELATED_POSTS_THUMBNAILS_VERSION', '1.9.0' );
		define( 'RELATED_POSTS_THUMBNAILS_FEEDBACK_SERVER', 'https://wpbrigade.com/' );
		define( 'RELATED_POSTS_THUMBNAILS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	* Check either to show notice or not.
	*
	* @since 1.8.2
	*/
	public function review_notice() {
		$this->review_dismissal();
		$this->review_prending();

		$review_dismissal = get_option( 'rpt_review_dismiss' );

		if ( 'yes' == $review_dismissal ) {
			return;
		}

		$activation_time = get_option( 'rpt_active_time' );

		if ( !$activation_time ) {
			$activation_time = time();
			add_option( 'rpt_active_time', $activation_time );
		}

		// 1296000 = 15 Days in seconds.
		if ( time() - $activation_time > 1296000 ) {
			add_action( 'admin_notices', array( $this, 'review_notice_message' ) );
		}
	}

	/**
	* Show review Message After 15 days.
	*
	* @since 1.8.2
	*/
	public function review_notice_message() {
		$scheme      = ( parse_url( $_SERVER[ 'REQUEST_URI' ], PHP_URL_QUERY ) ) ? '&' : '?';
		$url         = $_SERVER[ 'REQUEST_URI' ] . $scheme . 'rpt_review_dismiss=yes';
		$dismiss_url = wp_nonce_url( $url, 'rpt-review-nonce' );

		$_later_link = $_SERVER[ 'REQUEST_URI' ] . $scheme . 'ssb_review_later=yes';
		$later_url   = wp_nonce_url( $_later_link, 'rpt-review-nonce' ); ?>

		<style media="screen">
			.rpt-review-notice { padding: 15px 0; background-color: #fff; border-radius: 3px; margin: 20px 20px 0 0; border-left: 4px solid transparent; } .rpt-review-notice:after { content: ''; display: table; clear: both; }
			.rpt-review-thumbnail { float: left; line-height: 80px; text-align: center; width: 117px; }
			.rpt-review-thumbnail img { width: 118px; vertical-align: middle; }
			.rpt-review-text { overflow: hidden; }
			.rpt-review-text h3 { font-size: 24px; margin: 0 0 5px; font-weight: 400; line-height: 1.3; }
			.rpt-review-text p { font-size: 13px; margin: 0 0 5px; }
			.rpt-review-ul { margin: 0; padding: 0; }
			.rpt-review-ul li { display: inline-block; margin-right: 15px; }
			.rpt-review-ul li a { display: inline-block; color: #10738B; text-decoration: none; padding-left: 26px; position: relative; }
			.rpt-review-ul li a span { position: absolute; left: 0; top: -2px; }
		</style>

		<div class="rpt-review-notice">
			<div class="rpt-review-thumbnail">
				<img src="<?php echo plugins_url( 'assets/images/rpt-logo.png', __FILE__ ); ?>" alt="">
			</div>
			<div class="rpt-review-text">

				<h3>
					<?php _e( 'Leave A Review?', 'related-posts-thumbnails' ); ?>
				</h3>

				<p>
					<?php _e( 'We hope you\'ve enjoyed using Related Post Thumbnails! Would you consider leaving us a review on WordPress.org?', 'related-posts-thumbnails' ); ?>
				</p>

				<ul class="rpt-review-ul">
					<li>
						<a href="https://wordpress.org/support/plugin/related-posts-thumbnails/reviews/?filter=5" target="_blank"><span class="dashicons dashicons-external"></span>
							<?php _e( 'Sure! I\'d love to!', 'related-posts-thumbnails' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo $dismiss_url; ?>">
							<span class="dashicons dashicons-smiley"></span>
							<?php _e( 'I\'ve already left a review', 'related-posts-thumbnails' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo $later_url; ?>">
							<span class="dashicons dashicons-calendar-alt"></span>
							<?php _e( 'Maybe Later', 'related-posts-thumbnails' ); ?>
						</a>
					</li>

					<li>
						<a href="<?php echo $dismiss_url; ?>">
							<span class="dashicons dashicons-dismiss"></span>
							<?php _e( 'Never show again', 'related-posts-thumbnails' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<?php 
	}

	/**
	* Set time to current so review notice will popup after 15 days
	*
	* @since 1.8.2
	*/
	function review_prending() {
		// delete_site_option( 'rpt_review_dismiss' );
		if ( !is_admin() || !current_user_can( 'manage_options' ) || !isset( $_GET[ '_wpnonce' ] ) || !wp_verify_nonce( sanitize_key( wp_unslash( $_GET[ '_wpnonce' ] ) ), 'rpt-review-nonce' ) || !isset( $_GET[ 'ssb_review_later' ] ) ) {

			return;
		}

		// Reset Time to current time.
		update_option( 'rpt_active_time', time() );
	}

	/**
	* Check and Dismiss review message.
	*
	* @since 1.8.2
	*/
	private function review_dismissal() {
		//delete_option( 'rpt_review_dismiss' );
		if ( !is_admin() || !current_user_can( 'manage_options' ) || !isset( $_GET[ '_wpnonce' ] ) || !wp_verify_nonce( sanitize_key( wp_unslash( $_GET[ '_wpnonce' ] ) ), 'rpt-review-nonce' ) || !isset( $_GET[ 'rpt_review_dismiss' ] ) ) {

			return;
		}

		add_option( 'rpt_review_dismiss', 'yes' );
	}

	/**
	* [is_old_default_img Check the compatibility for old default image path.]
	* 
	* @return boolean Return true if path is old.
	*/
	function is_old_default_img() {
		if ( get_option( 'relpoststh_default_image' ) !== $this->default_image ) {

			$chunks = explode( '/', get_option( 'relpoststh_default_image' ) );
			if ( in_array( 'related-posts-thumbnails', $chunks ) ) {
				return true;
			}
		}
	}

	/**
	* Automatically displaying related posts under post body
	*
	* @param $content
	* 
	* @return void
	*/
	function auto_show( $content ) {
		return $content . $this->get_html( true );
	}

	/**
	* Getting related posts HTML
	*
	* @param boolean $show_top
	* 
	*/
	function get_html( $show_top = false ) {
		if ( $this->is_relpoststh_show() ) {
			return $this->get_thumbnails( $show_top );
		}

		return '';
	}

	/**
	 * Function responsible for Thumbnail creation.
	 *
	 * @param boolean $show_top Position of the thumbnails.
	 * @param string $posts_number Number of posts to display.
	 * @param string $sort_by sort The thumbnails by some filter.
	 * @param string $main_title Thumbnail title.
	 * @param string $exclude post_ids To exclude from related posts thumbnails.
	 * 
	 * @since 1.0.0
	 * @version 3.0.2
	 *
	 * @return void
	 */
	function get_thumbnails( $show_top = false, $posts_number = '', $sort_by = '', $main_title = '', $exclude ='' ) {
		$output       = '';
		$debug        = 'Developer mode initialization; Version: 1.2.9;';
		$time         = microtime( true );

		$amp_endpoint = ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ? true : false;
		
		$show_category = get_option( 'relpoststh_show_taxonomy' );

		// Stop execution if RPT is disabled in AMP view
		if ( ( $amp_endpoint ) && ( 'disable' === apply_filters( 'rpth_amp', true ) ) ) {

			$debug .= 'AMP view disabled'; 
			return $this->finish_process( '', $debug, $time );
		}

		$posts_number_opt = get_option( 'relpoststh_number', $this->number );
		$posts_number = !empty( $posts_number ) ? $posts_number : $posts_number_opt;
		$height       = '';
		$width        = '';
		// $date         = '';
		$order_by = 'DESC';

		$sort_by_opt  = get_option( 'rpt_post_sort') == 'latest' ? 'post_date '.$order_by.'': 'rand()';
		$sort_by      = !empty( $sort_by ) ? $sort_by : $sort_by_opt;

		// rpt_content_align: add content allignment class; clases are: relpost-align-left, relpost-align-right and relpost-align-center
		$output       = '<!-- relpost-thumb-wrapper -->';
		$output       .= '<div class="relpost-thumb-wrapper">';
		$output       .= '<!-- filter-class -->';
		$output       .='<div class="relpost-thumb-container'. apply_filters( 'rpt_content_align', '' ) . '">';
		$alt          = '';

		if ( $posts_number <= 0 ) { // return nothing if this parameter was set to <= 0
			$output = '';
			return $this->finish_process( $output, $debug . 'Posts number is 0;', $time );
		}

		$id                  = get_the_ID();
		$relation            = get_option( 'relpoststh_relation', $this->relation );
		$poststhname         = get_option( 'relpoststh_poststhname', $this->poststhname );
		$text_length         = get_option( 'relpoststh_textlength', $this->text_length );
		$excerpt_length      = get_option( 'relpoststh_excerptlength', $this->excerpt_length );
		$thsource            = get_option( 'relpoststh_thsource', $this->thsource );
		$categories_show_all = get_option( 'relpoststh_show_categoriesall', get_option( 'relpoststh_categoriesall', $this->categories_all ) );
		$onlywiththumbs      = ( current_theme_supports( 'post-thumbnails' ) && $thsource == 'post-thumbnails' ) ? get_option( 'relpoststh_onlywiththumbs', false ) : false;
		$post_type           = get_post_type();

		global $wpdb;

		/* Get taxonomy terms */
		$debug .= "Relation: $relation; All categories: $categories_show_all;";
		$use_filter = ( $categories_show_all != '1' || $relation != 'no' );

		if ( $use_filter ) {
			$query_objects = "SELECT distinct object_id FROM $wpdb->term_relationships WHERE 1=1 ";

			if ( $relation != 'no' ) {
				/* Get object terms */
				if ( $relation == 'categories' ) {
					$taxonomy = array(
						'category'
					);
				} elseif ( $relation == 'tags' ) {
					$taxonomy = array(
						'post_tag'
					);
				} elseif ( $relation == 'custom' ) {
					$taxonomy = get_option( 'relpoststh_custom_taxonomies', $this->custom_taxonomies );
				} else {
					$taxonomy = array(
						'category',
						'post_tag'
					);
				}
				$object_terms = wp_get_object_terms( $id, $taxonomy, array(
					'fields' => 'ids'
				) );
				
				if ( empty( $object_terms ) || !is_array( $object_terms ) ) { // no terms to get taxonomy
					$output = '';
					return $this->finish_process( $output, $debug . __( 'No taxonomy terms to get posts;' ), $time );
				}

				$query             = "SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id in ('" . implode( "', '", $object_terms ) . "')";
				$object_taxonomy   = $wpdb->get_results( $query );
				$object_taxonomy_a = array();

				if ( count( $object_taxonomy ) > 0 ) {
					foreach ( $object_taxonomy as $item ) {
						$object_taxonomy_a[] = $item->term_taxonomy_id;
					}
				}

				$query_objects .= " AND term_taxonomy_id IN ('" . implode( "', '", $object_taxonomy_a ) . "') ";
			}

			if ( $categories_show_all != '1' ) {
				/* Get filter terms */
				$select_terms = get_option( 'relpoststh_show_categories', get_option( 'relpoststh_categories' ) );
				if ( empty( $select_terms ) || !is_array( $select_terms ) ) { // if no categories were specified intentionally return nothing
					$output = '';
					return $this->finish_process( $output, $debug . __( 'No categories were selected;' ), $time );
				}

				$query             = "SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id in ('" . implode( "', '", $select_terms ) . "')";
				$taxonomy          = $wpdb->get_results( $query );
				$filter_taxonomy_a = array();
				if ( count( $taxonomy ) > 0 ) {
					foreach ( $taxonomy as $item ) {
						$filter_taxonomy_a[] = $item->term_taxonomy_id;
					}
				}
				if ( $relation != 'no' ) {
					$query_objects .= " AND object_id IN (SELECT distinct object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN ('" . implode( "', '", $filter_taxonomy_a ) . "') )";
				} else {
					$query_objects .= " AND term_taxonomy_id IN ('" . implode( "', '", $filter_taxonomy_a ) . "')";
				}
			}

			$relationships   = $wpdb->get_results( $query_objects );
			$related_objects = array();
			if ( count( $relationships ) > 0 ) {
				foreach ( $relationships as $item ) {
					$related_objects[] = $item->object_id;
				}
			}
		}

		$selected_post_type = '';
		/**
		 * Filter the post type to get posts from multiple Custom post types
		 *
		 * @since 1.9.3
		 */
		$custom_relations = apply_filters( 'rpt_custom_relationship', array( get_post_type() ) );
		
		if ( ! is_array( $custom_relations ) ) {
			$custom_relations = array( $custom_relations );
		}

		foreach( $custom_relations as $checked_post_type ) {
			if ( in_array( $checked_post_type, get_post_types() ) ) {
				$selected_post_type .= "'" . esc_html( $checked_post_type ) . "',";
			}
		}

		$checked_post_type = rtrim( $selected_post_type, ',' );

		// $query     = "SELECT distinct ID FROM $wpdb->posts ";
		$query     = "SELECT ID FROM $wpdb->posts ";
		// $where     = " WHERE post_type = '" . $post_type . "' AND post_status = 'publish' AND ID<>" . $id; // not the current post
		$where     = " WHERE post_type IN (" . $checked_post_type . ") AND post_status = 'publish' AND ID<>" . $id; // not the current post
		$startdate = get_option( 'relpoststh_startdate' );
		
		if ( !empty( $startdate ) && preg_match( '/^\d\d\d\d-\d\d-\d\d$/', $startdate ) ) { // If startdate was set
			$debug .= "Startdate: $startdate;";
			$where .= " AND post_date >= '" . $startdate . "'";
		}

		if ( $use_filter ) {
			$where .= " AND ID IN ('" . implode( "', '", $related_objects ) . "')";
		}

		$join = '';

		if ( $onlywiththumbs ) {
			$debug .= 'Only with thumbnails;';
			$join = " INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)";
			$where .= " AND $wpdb->postmeta.meta_key = '_thumbnail_id'";
		}

		$order_query = ' ORDER BY ' . $sort_by;
		$limit_order = $order_query . ' LIMIT ' . $posts_number;    
		$random_posts = $wpdb->get_results( $query . $join . $where . $limit_order );

		/* Get posts by their IDs */
		if ( !is_array( $random_posts ) || count( $random_posts ) < 1  ) {
			$output = '';
			return $this->finish_process( $output, $debug . __( 'No posts matching relationships criteria;' ), $time );
		}

		$posts_in = array();

		foreach ( $random_posts as $random_post ) {
			$posts_in[] = $random_post->ID;
		}

		if( $exclude == ! "" ) {

			$exclude_post_ids = explode( ",", $exclude );
			$posts_in = array_diff( $posts_in, $exclude_post_ids );

		}
		/**
		* 
		* Filter rpt_exclude_post to exclude post from RPT thumbnails
		* 
		* @since 1.9.0
		* 
		*/
		$exclude_by_filter = apply_filters( 'rpt_exclude_post', '' );

		if( $exclude_by_filter !== '' ) {
			$regex = '/^\d+(?:,\d+)*$/';
			$exclude_by_filter =  preg_match( $regex, $exclude_by_filter ) ? $exclude_by_filter : array();
			$exclude_by_filter_ids = explode( ",", $exclude_by_filter );
			$posts_in = array_diff( $posts_in, $exclude_by_filter_ids );

		}

		$query 	= "SELECT ID, post_content, post_excerpt, post_title FROM $wpdb->posts WHERE ID IN ('" . implode( "', '", $posts_in ) . "') $order_query ";
		$posts 	= $wpdb->get_results( $query );

		if ( !( is_array( $posts ) && count( $posts ) > 0 ) ) { // no posts
			$debug .= 'No posts found;';
			$output = '';
			return $this->finish_process( $output, $debug, $time );
		} else {
			$debug .= 'Found ' . count( $posts ) . ' posts;';
		}

		/* Calculating sizes */
		if ( $thsource == 'custom-field' ) {
			$debug .= 'Custom sizes;';
			$width  = get_option( 'relpoststh_customwidth', $this->custom_width );
			$height = get_option( 'relpoststh_customheight', $this->custom_height );
		} else { 
			// post-thumbnails source
			if ( $poststhname == 'thumbnail' || $poststhname == 'medium' || $poststhname == 'large' ) { // get thumbnail size for basic sizes
				$debug .= 'Basic sizes;';
				$width  = get_option( "{$poststhname}_size_w" );
				$height = get_option( "{$poststhname}_size_h" );
			} elseif ( current_theme_supports( 'post-thumbnails' ) ) { // get sizes for theme supported thumbnails
				global $_wp_additional_image_sizes;
				if ( isset( $_wp_additional_image_sizes[ $poststhname ] ) ) {
					$debug .= 'Additional sizes;';
					$width  = $_wp_additional_image_sizes[ $poststhname ][ 'width' ];
					$height = $_wp_additional_image_sizes[ $poststhname ][ 'height' ];
				} else {
					$debug .= 'No additional sizes;';
				}
			}
		}

		// displaying square if one size is not cropping
		if ( $height == 9999 ) {
			$height = $width;
		}

		if ( $width == 9999 ) {
			$width = $height;
		}
		// theme is not supporting but settings were not changed
		if ( empty( $width ) ) {
			$debug .= 'Using default width;';
			$width = get_option( 'thumbnail_size_w' );
		}
		if ( empty( $height ) ) {
			$debug .= 'Using default height;';
			$height = get_option( 'thumbnail_size_h' );
		}

		$debug .= 'Got sizes ' . $width . 'x' . $height . ';';

		// rendering related posts HTML
		if ( $show_top ) {
			if ( ! empty( $main_title ) ) {
				$output .= '<div class="relpoststh-block-title">' . esc_html( $main_title ) . '</div>';
			} else {
				$top_text = stripslashes( get_option( 'relpoststh_top_text', $this->top_text ) );
				$output .= stripslashes ( apply_filters( 'rpt_top_text', $top_text ) );
			}
		}

		$relpoststh_output_style = get_option( 'relpoststh_output_style', $this->output_style );
		$relpoststh_show_date    = get_option( 'relpoststh_show_date', '0' );
		$relpoststh_date_format  = get_option( 'relpoststh_date_format', $this->format );

		$relpoststh_cleanhtml    = get_option( 'relpoststh_cleanhtml', 0 );
		$text_height             = get_option( 'relpoststh_textblockheight', $this->text_block_height );


		if ( $relpoststh_output_style == 'list' ) {
			$output .= '<!-- related_posts_thumbnails -->';
			$output .= '<ul id="related_posts_thumbnails"';
			if ( ! $relpoststh_cleanhtml ) {
				$output .= ' style="list-style-type:none; list-style-position: inside; padding: 0; margin:0"';
			}
				$output .= '>';
		} else {

			$output .= '<div style="clear: both"></div>';

			// $output .= '<!-- related-posts-nav -->';
			// $output .=  '<ul class="related-posts-nav">'; //open blocks ul

			$output .= '<div style="clear: both"></div>';

			$output .= '<!-- relpost-block-container -->';
			$output .= '<div class="relpost-block-container">'; // open relpost-block-container div
		}

		foreach ( $posts as $post ) {
			$image         = '';
			$url           = '';
			$alt           = '';
			$category_list = '';

			$taxonomies = get_object_taxonomies( $post_type );

			/**
			 * Show the Categories names of a post in related post thumbnails.
			 *
			 * @since 2.2.0
			 */
			if ( $show_category ) {
				$category_list = $this->relpoststh_category_list( $post->ID, $taxonomies[0], $post_type );
			}
			
			if ( $thsource == 'custom-field' ) {
				$custom_field = get_option( 'relpoststh_customfield', $this->custom_field );
				$custom_field_meta = get_post_meta( $post->ID, $custom_field  );
				if ( empty( $custom_field ) ) {
					$debug .= 'No custom field specifield, using default thumbnail image;';
					$url = $this->default_image;
				} elseif ( empty( $custom_field_meta ) && apply_filters( 'rpt_remove_empty_cfield', false ) ) {
					$debug .= 'Custom field meta is empty, using rpt_remove_empty_cfield filter;';
					continue;
				} elseif ( empty( $custom_field_meta ) ) {
					$debug .= 'Custom field meta is empty, using default thumbnail image;';
					$url = $this->default_image;
				} else {
					$debug .= 'Using custom field;';

					/**
					 * Fix the single URL or Image object.
					 *
					 * @since 2.0.3
					 * @version 2.0.4
					 */
					if ( is_array( $custom_field_meta ) && isset( $custom_field_meta[0] ) ) {
						// If your post meta has an attachment ID instead of string URL.
						$url = wp_get_attachment_image_src( $custom_field_meta[0] ) ? wp_get_attachment_image_src( $custom_field_meta[0] )[0] : $this->default_image;

						/**
						 * Check if the custom field has string or int saved in it.
						 * Upon this condition serve the image accordingly.
						 *
						 * @since 2.0.4
						 */
						$url = isset( $custom_field_meta[0] ) && (int) $custom_field_meta[0] ? $url : $custom_field_meta[0];
					} else {
						$url = isset( $custom_field_meta ) && ! empty( $custom_field_meta ) ? $custom_field_meta : $this->default_image;
					}

					$theme_resize_url = get_option( 'relpoststh_theme_resize_url', '' );

					if ( strpos( $url, '/wp-content' ) !== false ) {
						$url = substr( $url, strpos( $url, '/wp-content' ) );
					}

					if ( !empty( $theme_resize_url ) ) {
						$url = $theme_resize_url . '?src=' . $url . '&w=' . $width . '&h=' . $height . '&zc=1&q=90';
					}
				}
			} else {
				$from_post_body = true;

				// using built in WordPress Thumbnails Feature
				if ( current_theme_supports( 'post-thumbnails' ) ) { 

					$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
					$debug .= 'Post-thumbnails enabled in theme;';

					if ( !( empty( $post_thumbnail_id ) || $post_thumbnail_id === false ) ) { // post has thumbnail
						$debug .= 'Post has thumbnail ' . $post_thumbnail_id . ';';
						$debug .= 'Postthname: ' . $poststhname . ';';
						$image          = wp_get_attachment_image_src( $post_thumbnail_id, $poststhname );
						$alt            = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
						$url            = $image[ 0 ];
						$from_post_body = false;
					} else {
						$debug .= 'Post has no thumbnail;';
					}
				}

				// Theme does not support post-thumbnails, or post does not have assigned thumbnail
				if ( $from_post_body ) {
				$debug .= 'Getting image from post body;';
				$wud = wp_upload_dir();

				// search the first uploaded image in content
				preg_match_all( '|<img.*?src=[\'"](' . $wud[ 'baseurl' ] . '.*?)[\'"].*?>|i', $post->post_content, $matches ); 

				if ( isset( $matches ) && isset( $matches[ 1 ][ 0 ] ) ) {
					$image = $matches[ 1 ][ 0 ];
					$html  = $matches[ 0 ][ 0 ];

					if ( !empty( $html ) ) {
						preg_match( '/alt="([^"]*)"/i', $html, $array );

						if ( !empty( $array ) && is_array( $array ) ) {
							$explode_tag = explode( '"', $array[ 0 ] );
							$alt         = $explode_tag[ 1 ];
						}
					}

					} else {
						$debug .= 'No image was found;';
					}

					if ( strlen( trim( $image ) ) > 0 ) {

						$image_sizes = @getimagesize( $image );

						if ( $image_sizes === false ) {
							$debug .= 'Unable to determine parsed image size';
						}

						if ( ( $image_sizes !== false && isset( $image_sizes[ 0 ] ) ) && $image_sizes[ 0 ] == $width ) { 
						// if this image is the same size
							$debug .= 'Image used is the required size;';
							$url = $image;
						} elseif ( apply_filters( 'rpt_prevent_img_size_check', false ) && $image_sizes[ 0 ] < $width ) {
							// if this image is samll than required size
							$debug .= 'Image used is smaller than the required size, rpt_prevent_img_size_check filter is active;';
							$url = $image;
						} else { 
							// search for resized thumbnail according to Wordpress thumbnails naming function
							$debug .= 'Changing image according to Wordpress standards;';
							$url = preg_replace( '/(-[0-9]+x[0-9]+)?(\.[^\.]*)$/', '-' . $width . 'x' . $height . '$2', $image );
						}

					} else {
						$debug .= 'Found wrong formatted image: ' . $image . ';';
					}

				}
			}

			if ( strpos( $url, '/' ) === 0 ) {
				$url = get_bloginfo( 'url' ) . $url;
				$debug .= 'Relative url: ' . $url . ';';
			}

			// parsed URL is empty or no image found
			if ( empty( $url ) ) { 
				$debug .= 'Image URL: ' . $url . ';';
				$debug .= 'Image is empty or no file. Using default image;';
				$url = get_option( 'relpoststh_default_image', $this->default_image );
			}

			$title        = $this->process_text_cut( $post->post_title, $text_length );
			$post_excerpt = ( empty( $post->post_excerpt ) ) ? $post->post_content : $post->post_excerpt;
			$excerpt      = $this->process_text_cut( $post_excerpt, $excerpt_length );
			$aria_label   = 'aria-label="' . esc_attr( $alt ) . '"';

			if ( empty( $alt ) ) {
				$alt        = str_replace('"', '', $title);
				$aria_label = 'aria-hidden="true"';
			}

			if ( !empty( $title ) && !empty( $excerpt ) ) {
				$title   = '<h2 class="relpost_card_title">' . esc_html( $title ) . '</h2>';
				$excerpt = '<div class="relpost_card_exerpt">' . $excerpt . '</div>';
			}

			$fontface = str_replace( '"', "'", stripslashes( get_option( 'relpoststh_fontfamily', $this->font_family ) ) );
			$debug .= 'Using title with size ' . $text_length . '. Using excerpt with size ' . $excerpt_length . ';';
			$after_content = apply_filters( 'rpth_after_content', '', $post );

			if ( ( $amp_endpoint ) && ( true === apply_filters( 'rpth_amp', true ) ) ) {
				$debug .= 'AMP view enabled';
				$output .= '<li style="' . apply_filters( 'rpth_amp_list_style', 'margin: 5px 20px;' ) . '">';
				$output .= '<a href="' . get_permalink( $post->ID ) . '" class="relpost_content" font-family: ' . $fontface . '>';
				$output .= '<span class="rpth_amp_list_content">' . $title . $excerpt . '</span>' . $after_content . '</a></li>';
			} else {

				$date_output = '';

				/**
				 * Get the date format from the settings.
				 *
				 * @since 1.9.3
				 */
				if ( '0' !== $relpoststh_show_date ) {
					$date   = get_the_date( $relpoststh_date_format, $post->ID );
					$date_output = '<span class="rpth_list_date">' . $date . '</span>';
				}

				if ( $show_category ) {
					$category_list = $this->relpoststh_category_list( $post->ID, $taxonomies[0], $post_type );
				}

				if ( $relpoststh_output_style == 'list' ) {
					$link   = get_permalink( $post->ID );
					$output .= '<li ';

					// if ( !$relpoststh_cleanhtml ) {
					// $output .= ' onmouseout="this.style.backgroundColor=\'' . get_option( 'relpoststh_background', $this->background ) . '\'"';
					// }

					$output .= '>';
					$output .= '<a href="' . $link . '" ><img class="relpost-post-image" alt="' . esc_attr( $alt ) . '" src="' . esc_url( $url ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" ';

					// if ( !$relpoststh_cleanhtml ) {
					// $output .= 'style="padding: 0px; margin: 0px; border: 0pt none;"';
					// }

					$output .= '/></a>';

					if ( $text_height != '0' ) {
						$output .= '<a href="' . $link . '" class="relpost_content"';

						if ( !$relpoststh_cleanhtml ) {
							$output .= ' style="width: ' . $width . 'px;height: ' . $text_height . 'px; font-family: ' . $fontface . '; "';
						}


						// $output .= '><span class="rpth_list_content">' . $title . $excerpt . '</span>' . $date . '</a></li>';
						$output .= '><span class="rpth_list_content">' . $title . $excerpt . $date_output . $category_list . '</span>' . $after_content . '</a></li>';
					}
				} else {
					//if lazy-load is not activated
					$rpt_single_background = apply_filters( 'rpt-single-background', 'background: transparent url(' . $url . ') no-repeat scroll 0% 0%; width: ' . $width . 'px; height: ' . $height . 'px;' );

					//if lazy-load is activated
					$rpt_lazy_single_background = apply_filters( 'rpt-lazy-loading', false );

					$rpt_anchor_attrs = array(
						'class'  => '',
						'target' => false,
					);
					
					/**
					 * Filter to enhance the related post thumbnail anchor attribute such as open post in a new tab. 
					 *
					 * @param int $post->ID Current post ID.
					 * @param array $rpt_anchor_attrs array of the attributes.
					 *
					 * @since 1.9.2
					 */
					$rpt_anchor_attr_filter = (array) apply_filters( 'relpost_anchor_attr', $post->ID, $rpt_anchor_attrs );

					/**
					 * Array Containing Allowed attributes.
					 */
					$allowed_anchor_attrs = array( 'title', 'class', 'target' );

					// Pattern for data-* attribute.
					$data_attr_ptrn = "/data-/i";

					$relpost_attributes = 'class="relpost-block-single';

					// Attr sets for class including default class.
					if ( array_key_exists( 'class', $rpt_anchor_attr_filter ) ) {

						// Class attributes in string.
						$class_attrs_str = esc_attr( wp_unslash( $rpt_anchor_attr_filter['class'] ) );
						// Class attributes in array.
						$class_attrs = explode( " ", $class_attrs_str );

						foreach ( $class_attrs as $value ) {
							if( 'relpost-block-single' !== $value && ! empty( $value ) ) {
								$relpost_attributes .=  ' ' . $value ;
							}
						}
					} 
					$relpost_attributes .= '" ';

					foreach ( $rpt_anchor_attr_filter as $rel_post_a_attr => $value ) {

						$value           = esc_attr( wp_unslash( $value ) );
						$rel_post_a_attr = esc_attr( wp_unslash( $rel_post_a_attr ) );

						if ( false !== $value && ! empty( $value ) && $rel_post_a_attr !== 'class' && ( in_array( $rel_post_a_attr, $allowed_anchor_attrs ) || preg_match( $data_attr_ptrn, $rel_post_a_attr ) ) ) {
							$relpost_attributes .= $rel_post_a_attr . '="' . $value . '"';
						}
					}

					$output .= '<a href="' . get_permalink( $post->ID ) . '"' . $relpost_attributes . '>';

					$output .= '<div class="relpost-custom-block-single" style="width: ' . $width . 'px; height: ' . ( $height + $text_height ) . 'px;">';
					if( $rpt_lazy_single_background ) {
						$output .= '<img loading="lazy" class="relpost-block-single-image" alt="' . esc_attr( $alt ) . '"  src="'.  esc_url( $url ) . '">' . $date_output . $category_list . '</img>';
					} else {
						$output .= '<div class="relpost-block-single-image" ' . $aria_label . ' role="img" style="'.  esc_attr( $rpt_single_background ) . '"></div>';
					}
					$output .= '<div class="relpost-block-single-text"  style="font-family: ' . $fontface . ';  font-size: ' . get_option( 'relpoststh_fontsize', $this->font_size ) . 'px;  color: ' . get_option( 'relpoststh_fontcolor', $this->font_color ) . ';">' . $title . $excerpt . $date_output . $category_list . '</div>';
					$output .= $after_content;
					// $output .= $date;
					$output .= '</div>';
					$output .= '</a>';
				}
			}

		} // end foreach

		if ( $relpoststh_output_style == 'list' ) {
			$output .= '</ul>';
			$output .= '<!-- close related_posts_thumbnails -->';
		} else {
			$output .= '</div>';
			$output .= '<!-- close relpost-block-container -->';
			// $output .= '</ul>';
			// $output .= '<!-- close related-posts-nav -->';
		}

		$output .= '<div style="clear: both"></div>';

		$output .= '</div>';
		$output .= '<!-- close filter class -->';

		$output .= '</div>';
		$output .= '<!-- close relpost-thumb-wrapper -->';

		return $this->finish_process( $output, $debug, $time );
	}

	/**
	 * Show Related Post Categories.
	 *
	 * @param int $id The ID of post.
	 *
	 * @return string $category_list The structure of category list.
	 *
	 * @since 2.2.0
	 */
	function relpoststh_category_list( $id, $taxonomy, $post_type ) {
		
		$category_list_struct = '';

		$category_list = $this->relpoststh_get_featured_category( $id, $taxonomy, $post_type );

		// Bail early if category list is not found.
		if ( false === $category_list ) {
			return $category_list_struct;
		}

		/**
	     * Modifiy the category listings.
	     *
	     * @param int     $id The ID of post.
	     * @return string $category_list The category list of current post at hand.
	     * @return string $taxonomy The taxonomy of a post.
		 *
		 * @since 2.2.0
		 */
		$category_list  = apply_filters( 'relpoststh_show_all_categories', $category_list, $id, $taxonomy, $post_type );
		$category_names = array();

		if ( is_array( $category_list ) ) {
			foreach ( $category_list[0] as $category_list_item ) {
				$category_names[] = $category_list_item->name;
			}
		} else {
			$category_names[] = $category_list->name;
		}

		$html = '';

		if ( ! empty( $category_names ) ) {
			$category_names = implode( ', ', $category_names );
			$html          .=  '<div class="relpoststh_front_cat">';
			$html          .= '<span>' . esc_html( $category_names ) . '</span>';
			$html          .= '</div>';
		}

		return $html;
	}

	/**
	 * Returns the featured category of a post based on YOAST SEO's meta-data.
	 *
	 * @param int $post_ID The post id.
	 * @param int $taxonomy_name The taxonomy name.
	 *
	 * @return object
	 * @since 2.2.0
	 * @version 3.0.1
	 */
	function relpoststh_get_featured_category ( $post_ID, $taxonomy_name = 'category', $post_type = 'post' ) {

		// if the ID is not set, get the global ID.
		if ( ! is_numeric( $post_ID ) ) {
			$post_ID = get_the_ID();
		}

		$args = array(
			'post_type'  => $post_type,
		);

		$taxonomy_name = is_array( $taxonomy_name ) ? $taxonomy_name[0] : $taxonomy_name;
		$categories    = wp_get_object_terms( $post_ID, $taxonomy_name, $args );

		if ( ! empty( $categories ) && is_wp_error( $categories ) ) {
			return false;
		}

		// get the yoast 'primary_category' from meta-data.
		$yoast_primary_category = get_post_meta( $post_ID, '_yoast_wpseo_primary_' . $taxonomy_name, true );

		if ( $yoast_primary_category ) {

			// if meta-data exists, find the primary category.
			foreach ( $categories as $category ) {

				if ( $yoast_primary_category == $category->term_id ) {
					return $category;
				}
			}
		}

		// return the first category.
		if ( isset( $categories ) && ! empty( $categories[0] ) ) {
			return $categories[0];
		}
	}

	// function is_url_404( $url ) {
	//   $response = wp_remote_request( $url,
	//     array(
	//       'method'     => 'GET'
	//     )
	//   );

	//   return $response['response']['code']; 
	// }

	/**
	* This will add debugging information in HTML source
	*
	* @param $output
	* @param  $debug 
	* @param int $time time took to create the thumbnails 
	* 
	* @return $output debugged information 
	*/
	function finish_process( $output, $debug, $time ) {
		
		$devmode = get_option( 'relpoststh_devmode', $this->devmode );
		
		if ( $devmode ) {
			$time = microtime( true ) - $time;
			$debug .= "Plugin execution time: $time sec;";
			$output .= '<!-- ' . $debug . ' -->';
		}
		
		return $output;
	}

		function process_text_cut( $text, $length ) {
		
		if ( $length == 0 ) {
			return '';
		} else {
			$text = strip_tags( strip_shortcodes( $text ) );

			if ( function_exists( 'mb_strlen' ) ) {
				return ( ( mb_strlen( $text ) > $length ) ? mb_substr( $text, 0, $length ) . '...' : $text );
			} else {
				return ( ( strlen( $text ) > $length ) ? substr( $text, 0, $length ) . '...' : $text );
			}
		}
	}

	/**
	* Function to check th options to show the thumbnails according to the post types, categories etc.
	*
	* @return boolean
	*/
	function is_relpoststh_show() {
		// Checking display options
		if ( !is_single() && get_option( 'relpoststh_single_only', $this->single_only ) ) { // single only
			return false;
		}
		// Check post type 
		$post_types = get_option( 'relpoststh_post_types', $this->post_types );
		$post_type  = get_post_type();

		if ( !in_array( $post_type, $post_types ) ) {
			return false;
		}
		// Check categories
		$id             = get_the_ID();
		$categories_all = get_option( 'relpoststh_categoriesall', $this->categories_all );

		if ( $categories_all != '1' ) { // only specific categories were selected

			$post_categories       = wp_get_object_terms( $id, array( 'category' ), array( 'fields' => 'ids' ) );
			$relpoststh_categories = get_option( 'relpoststh_categories' );
			
			if ( !is_array( $relpoststh_categories ) || !is_array( $post_categories ) ) { // no categories were selcted or post doesn't belong to any
				return false;
			}

			$common_categories = array_intersect( $relpoststh_categories, $post_categories );
			
			if ( empty( $common_categories ) ) { // post doesn't belong to specified categories
				return false;
			}
		}

		return true;
	}

	/**
	* Admin Menu page
	*
	* @return void
	*/
	function admin_menu() {
		$page = add_menu_page( __( 'Related Posts Thumbnails', 'related-posts-thumbnails' ), __( 'Related Posts', 'related-posts-thumbnails' ), 'administrator', 'related-posts-thumbnails', array( $this, 'admin_interface' ), 'dashicons-screenoptions' );
	}

	/**
	* Related post thumbnail settings page load
	*
	* @return void
	*/
	function admin_interface() {
		include_once RELATED_POSTS_THUMBNAILS_PLUGIN_DIR . '/inc/rpt-settings.php';
	}

	/**
	* Category List in Settings
	*
	* @param  $categoriesall 
	* @param  $categories
	* @param  $selected_categories
	* @param  $all_name
	* @param  $specific_name
	*/
	function display_categories_list($categoriesall, $categories, $selected_categories, $all_name, $specific_name) { ?>
		<input style="display:none"  id="<?php echo esc_attr( $all_name ) . '_check'; ?>" class="select_all" type="checkbox" name="<?php echo esc_attr( $all_name ); ?>" value="1" <?php
			if ( $categoriesall == '1' ) {
				echo 'checked="checked"';
			} ?>
		/>
		<label style="display:none"  for="<?php echo esc_attr( $all_name ); ?>">
			<?php _e( 'All', 'related-posts-thumbnails' ); ?>
		</label>
		<div class="select_specific" <?php echo esc_attr( $specific_name );
			if ( $categoriesall == '1' ): ?> 
				style="display:none" 
			<?php endif; ?> >
		</div>
		<select class="chosen-select <?php echo esc_attr( $all_name ); ?>" data-placeholder="<?php _e( 'Select Categories', 'related-posts-thumbnails' ); ?>" id="<?php echo esc_attr( $all_name ); ?>" name="<?php echo esc_attr( $specific_name ); ?>[]" multiple>
			<option value="0" <?php if ( $categoriesall == '1' ) { echo 'selected="selected"'; } ?>><?php _e('All', 'related-posts-thumbnails'); ?></option>
			<?php foreach ( $categories as $category ): ?>
				<option value="<?php echo esc_attr( $category->cat_ID ); ?>" <?php
					if ( $categoriesall !== '1' && in_array( $category->cat_ID, (array) $selected_categories ) ) {
						echo 'selected="selected"';
					}
				?>><?php echo esc_html( $category->cat_name ); ?></option>
			<?php endforeach; ?>
		</select>
	<?php }
	/**
	* Related posts Thumbnails styling.
	*
	* @return void
	*/
	function head_style() { ?>
			<style>
			#related_posts_thumbnails li{
				border-right: 1px solid <?php echo get_option( 'relpoststh_bordercolor', $this->border_color );  ?>;
				background-color: <?php echo get_option( 'relpoststh_background', $this->background ); ?>
			}
			#related_posts_thumbnails li:hover{
				background-color: <?php echo get_option( 'relpoststh_hoverbackground', $this->hoverbackground ); ?>;
			}
			.relpost_content{
				font-size:	<?php echo get_option( 'relpoststh_fontsize', $this->font_size ) . 'px'; ?>;
				color: 		<?php echo get_option( 'relpoststh_fontcolor', $this->font_color ); ?>;
			}
			.relpost-block-single{
				background-color: <?php echo get_option( 'relpoststh_background', $this->background ); ?>;
				border-right: 1px solid  <?php echo get_option( 'relpoststh_bordercolor', $this->border_color ); ?>;
				border-left: 1px solid  <?php echo get_option( 'relpoststh_bordercolor', $this->border_color ); ?>;
				margin-right: -1px;
			}
			.relpost-block-single:hover{
				background-color: <?php echo get_option( 'relpoststh_hoverbackground', $this->hoverbackground ); ?>;
			}
		</style>

	<?php
	}
	
	/**
	 * Main Instance
	 *
	 * @since 2.0.1
	 * @static
	 * @see related_posts_thumbnails_loader()
	 * @return Main instance
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

/**
* Returns the main instance of WP to prevent the need to use globals.
*
* @since  2.0.1
* @return RelatedPostsThumbnails instance
*/
if ( ! function_exists('related_posts_thumbnails_loader') ) {
    function related_posts_thumbnails_loader() {
        return RelatedPostsThumbnails::instance();
    }
}

// Call the function.
global $related_posts_thumbnails;
$related_posts_thumbnails = related_posts_thumbnails_loader();


// Include Widget File.
include_once plugin_dir_path( __FILE__ ) . 'inc/rpt-widget.php';
// Include Blocks File.
include_once plugin_dir_path( __FILE__ ) . 'inc/rpt-blocks.php';

?>
