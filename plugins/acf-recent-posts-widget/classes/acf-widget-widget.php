<?php

/**
 * Adds Foo_Widget widget.
 */
class ACF_Rpw_Widget extends Widget_Base {

	/**
	 * Limit the excerpt lenght
	 * @type INT
	 */
	public static $el = 55;

	/**
	 * Custom readmore text
	 * @type STRING
	 */
	public static $rt = '';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		$this->text_fields = array( 'css', 'tu', 'ex', 's', 'df', 'ds', 'de', 'aut', 'mk', 'meta_value', 'ltt', 'np', 'ns', 'thh', 'thw', 'dfth', 'el', 'rt', 'pass' );
		$this->text_areas = array( 'before', 'after', 'before_posts', 'after_posts', 'custom_css', 'no_posts' /* 'mq' */ );
		$this->checkboxes = array( 'is', 'ds', /* not needed without specific time'di', */ 'dd', 'dlm', 'dr', 'dth', 'pt', 'pf', 'ps', 'ltc', 'lttag', 'excerpt', 'rm', 'default_styles', 'hp', 'ep' );
		$this->select_fields = array( 'ord', 'orderby', 'ltto', 'tha', 'meta_compare' );

		parent::__construct(
				'acf-recent-posts-widget', // Base ID
				__( 'ACF Recent Posts Widget', 'acf-recent-posts-widget' ), // Name
				array(
			'description' => __( 'Advanced Recent Posts Widget with ACF and meta fields support.', 'acf-recent-posts-widget' ),
			'class' => 'acf-rpw', ), array(
			'width' => 750,
			'height' => 350
				) // Args
		);
	}

	/**
	 * Todo:
	 * @param type $slug
	 * @param type $name
	 */
	public static function acfrpw_get_template_part($slug, $name = '', $type = '') {
		$template = '';

		// Look in yourtheme/acfrpw/slug-name.php 
		if ( $name ) {
			$template = locate_template( array( "{$slug}-{$name}.php", 'acfrpw/' . "{$slug}-{$name}.php" ) );
		}

		// look in yourtheme/acfrpw-blog/slug-name.php
		if ( $name and $type == 'shortcode' ) {
			$template = locate_template( array( "{$slug}-{$name}.php", 'acfrpw-blog/' . "{$slug}-{$name}.php" ) );
		}

		// Get default slug-name.php
		if ( !$template && $name && file_exists( ACF_RWP_PATH . "/templates/{$slug}-{$name}.php" ) ) {
			$template = ACF_RWP_PATH . "/templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
		if ( !$template ) {
			$template = locate_template( array( "{$slug}.php", 'acfrpw/' . "{$slug}.php" ) );
		}

		// Allow 3rd party plugin filter template file from their plugin
		if ( (!$template ) || $template ) {
			$template = apply_filters( 'acf_rpw_get_template_part', $template, $slug, $name );
		}

		if ( $template ) {
			load_template( $template, false );
		}
	}

	/**
	 * @hooked
	 */
	public static function excerpt_length() {
		return ( int ) ACF_Rpw_Widget::$el;
	}

	/**
	 * @hooked
	 */
	public static function excerpt_more() {
		$link = '';
		if ( !empty( ACF_Rpw_Widget::$rt ) ) {
			$link = ' <a href="' . get_permalink() . '" class="more-link">' . esc_attr( ACF_Rpw_Widget::$rt ) . '</a>';
		}
		return $link;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {

		// obtain the initially sanitized variables
		$instance = $this->_sanitize_data( $instance, $instance );

		extract( $instance );
		// get the query args
		$query_args = self::_get_query_args( $instance, $this->id_base );

		$cache = array();
		if ( !$this->is_preview() ) {
			$cache = wp_cache_get( 'widget_recent_posts', 'widget' );
		}

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( !isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $tu, $instance, $this->id_base );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', $query_args ) );
		
		if ( $r->have_posts() ) {
			// enable global variables to be used in the templat functions
			global $acf_rpw_instance, $acf_rpw_args, $acf_rpw_title, $acf_rpw_id;
			$acf_rpw_id = $this->id_base;
			$acf_rpw_title = $title;
			$acf_rpw_instance = $instance;
			$acf_rpw_args = $args;
			$this->acfrpw_get_template_part( 'loop', 'before' );

			while ( $r->have_posts() ) {
				$r->the_post();
				$this->acfrpw_get_template_part( 'loop', 'inner' );
			}

			$this->acfrpw_get_template_part( 'loop', 'after' );

			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		}
		else {
			global $acf_rpw_instance, $acf_rpw_args, $acf_rpw_title, $acf_rpw_id;
			$acf_rpw_id = $this->id_base;
			$acf_rpw_title = $title;
			$acf_rpw_instance = $instance;
			$acf_rpw_args = $args;
			$this->acfrpw_get_template_part( 'loop', 'empty' );
		}

		if ( !$this->is_preview() ) {
			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		$this->form_instance = $instance;
		include( ACF_RWP_INC_PATH . 'form.php' );
	}

	/**
	 * Get query arguments for the WP_Query
	 * 
	 * @param ARRAY_A $instance
	 * @return ARRAY_A
	 */
	public static function _get_query_args($instance, $id) {

		extract( $instance );
		// number of posts to show
		if ( isset( $np ) and ! empty( $np ) ) {
			$query_args['posts_per_page'] = ( int ) $np;
		}

		// offset the posts 
		if ( isset( $ns ) and ! empty( $ns ) ) {
			$query_args['offset'] = ( int ) $ns;
		}

		// ignore sticky posts if checked
		if ( isset( $is ) ) {
			$query_args['ignore_sticky_posts'] = 1;
		}
		// search by keyword 
		if ( isset( $s ) and ! empty( $s ) ) {
			$query_args['s'] = $s;
		}

		// exclude certain posts
		if ( isset( $ex ) and ! empty( $ex ) ) {
			$query_args['post__not_in'] = explode( ',', $ex );
		}

		// show posts from certain authors only
		if ( isset( $aut ) and ! empty( $aut ) ) {
			$query_args['author__in'] = explode( ',', $aut );
		}

		// Date Related Parameters
		if ( isset( $ds ) and ! empty( $ds ) ) {
			$query_args['date_query'] = array(
				array(
					'after' => $ds,
				),
			);
		}

		if ( isset( $de ) and ! empty( $de ) ) {
			if ( isset( $ds ) and ! empty( $ds ) ) {
				// if the date start has already been set, merge the array with new parameters
				$query_args['date_query'][0] = array_merge(
						$query_args['date_query'][0], array(
					'before' => $de, )
				);
			} else {
				// create new parameters
				$query_args['date_query'] = array(
					array(
						'before' => $de,
					),
				);
			}
		}

		// Do we want to include the posts from the specified period?
		if ( isset( $query_args['date_query'] ) and ! empty( $query_args['date_query'] ) and isset( $di ) ) {
			$query_args['date_query'][0]['inclusive'] = true;
		}

		// password parameters
		if ( isset( $pass ) and ! empty( $pass ) ) {
			$query_args['post_password'] = $pass;
		}

		// exclude password protected posts
		if ( isset( $ep ) and ! empty( $ep ) ) {
			$query_args['has_password'] = false;
		}

		// render only password protected posts
		if ( isset( $hp ) and ! empty( $hp ) ) {
			$query_args['has_password'] = true;
		}

		// meta key parameter
		if ( isset( $mk ) and ! empty( $mk ) ) {
			$query_args['meta_key'] = $mk;
		}

		// obtain the meta compare parameter
		if ( isset( $meta_compare ) and $meta_compare != 'NONE' ) {
			$query_args['meta_compare'] = $meta_compare;
		}

		// obtain the meta value parameter
		if ( isset( $meta_value ) and ! empty( $meta_value ) ) {
			// check if we need to convert this to array
			if ( strpos( $meta_value, ';' ) !== false ) {
				$meta_value = explode( ';', $meta_value );
			}

			// apply the date filters here
			if ( is_array( $meta_value ) ) {
				foreach ( $meta_value as &$mv ) {
					$mv = apply_filters( 'acf_meta_value', $mv );
				}
			} else {
				$meta_value = apply_filters( 'acf_meta_value', $meta_value );
			}
			$query_args['meta_value'] = $meta_value;
		}


		// add post type parameters
		if ( isset( $pt ) ) {
			$query_args['post_type'] = array();
			foreach ( $pt as $post ) {
				$query_args['post_type'][] = $post;
			}
		}

		// add post format parameter
		if ( isset( $pf ) ) {
			$formats = array();
			// get all available formats
			$available_formats = get_theme_support( 'post-formats' );

			// if the standard post lies within the arguments
			// then run extra check as it's not defined inside the query arguments
			if ( in_array( 'standard', $pf ) ) {
				// which means that we have selected not only
				// standard post type
				$terms = array_diff( $available_formats[0], $pf );
				$terms = array_map( function($term) {
					return 'post-format-' . $term;
				}, $terms );

				$query_args['tax_query'] = array( array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => $terms,
						'operator' => 'NOT IN',
					) );
			} else {
				// let's simply output all post formats present
				$formats = array_map( function($term) {
					return 'post-format-' . $term;
				}, $pf );

				$query_args['tax_query'] = array( array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => $formats,
					) );
			}
		}

		// add post status parameter
		if ( isset( $ps ) and is_array( $ps ) ) {
			$query_args['post_status'] = array();
			foreach ( $ps as $status ) {
				$query_args['post_status'][] = $status;
			}
		}

		// add category parameter
		if ( isset( $ltc ) and is_array( $ltc ) ) {
			$query_args['category__in'] = array();
			foreach ( $ltc as $cat ) {
				$query_args['category__in'][] = $cat;
			}
		}

		// add post tag parameter
		if ( isset( $lttag ) and is_array( $lttag ) ) {
			$query_args['tag__in'] = array();
			foreach ( $lttag as $tag ) {
				$query_args['tag__in'][] = $tag;
			}
		}

		if ( isset( $ltt ) and ! empty( $ltt ) ) {
			$query_args['taxonomy'] = array();
		}

		/**
		 * Taxonomy query.
		 * Prop Miniloop plugin by Kailey Lampert.
		 * and Recent_Posts_Widget_Extended
		 */
		if ( isset( $ltt ) and ! empty( $ltt ) ) {

			parse_str( $ltt, $taxes );

			$operator = 'IN';
			if ( isset( $ltto ) ) {
				$operator = $ltto;
			}
			$tax_query = array();
			foreach ( array_keys( $taxes ) as $k => $slug ) {
				$ids = explode( ',', $taxes[$slug] );
				$tax_query[] = array(
					'taxonomy' => $slug,
					'field' => 'id',
					'terms' => $ids,
					'operator' => $operator
				);
			}

			$query_args['tax_query'] = $tax_query;
		}


		// add order parameter 
		if ( isset( $ord ) ) {
			$query_args['order'] = $ord;
		}

		// add orderby parameter
		if ( isset( $orderby ) ) {
			$query_args['orderby'] = $orderby;
		}

		return apply_filters( 'acf_rwp_query', $query_args, $instance, $id );
	}

}
