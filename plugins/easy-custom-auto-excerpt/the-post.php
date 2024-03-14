<?php

class ECAE_The_Post {
	function __construct() {
		$options   = get_option( 'tonjoo_ecae_options' );
		$this->opt = tonjoo_ecae_load_default( $options );

		add_action( 'the_post', array( $this, 'the_post' ) );
		add_filter( 'get_post_metadata', array( $this, 'get_post_metadata' ), 10, 4 );
	}

	function the_post( $post_object ) {
		add_filter( 'ecae-post', array( $this, 'ecae_post' ) );
	}

	function default_args() {
		return array(
			'default-content' => false,
			'ecae-content'    => false,
		);
	}

	function ecae_post( $args ) {
		global $post;

		$this->args          = $this->default_args();
		$disabled_post_types = array( 'pjc_slideshow' );

		// On disabled post types.
		if ( in_array( get_post_type(), $disabled_post_types ) ) {
			$this->args['default-content'] = true;
		}

		// RSS FEED.
		if ( is_feed() && 'yes' === $this->opt['disable_on_feed'] ) {
			$this->args['default-content'] = true;
		}

		// Special method.
		if ( isset( $this->opt['special_method'] ) && 'yes' === $this->opt['special_method'] ) {
			global $is_main_query_ecae;

			if ( ! $is_main_query_ecae ) {
				$this->args['default-content'] = true;
			}
		}

		// setting location.
		if ( 'basic' === $this->opt['location_settings_type'] ) {
			$this->location_basic();
		} else {
			$this->location_advanced();
		}

			return $this->args;
	}

	function location_basic() {
		if ( 'yes' === $this->opt['home'] && is_home() ) {
			$this->args['ecae-content'] = true;
		} elseif ( 'yes' === $this->opt['front_page'] && is_front_page() ) {
			$this->args['ecae-content'] = true;
		} elseif ( 'yes' === $this->opt['search'] && is_search() ) {
			$this->args['ecae-content'] = true;
		} elseif ( 'yes' === $this->opt['archive'] && is_archive() ) {
			$this->args['ecae-content'] = true;
		}

		/**
		 * Excerpt in pages
		 */
		$excerpt_in_page = $this->opt['excerpt_in_page'];
		$excerpt_in_page = trim( $excerpt_in_page );

		if ( '' !== $excerpt_in_page ) {
			$excerpt_in_page = explode( '|', $excerpt_in_page );
		} else {
			$excerpt_in_page = array();
		}

		foreach ( $excerpt_in_page as $key => $value ) {
			if ( '' !== $value && is_page( $value ) ) {
				$this->args['ecae-content'] = true;
				break;
			}
		}
	}

	function location_advanced() {
		if ( is_home() ) {
			$this->advExcLoc_do_excerpt( 'advanced_home', 'home_post_type', 'home_category' );
		} elseif ( is_front_page() ) {
			$this->advExcLoc_do_excerpt( 'advanced_frontpage', 'frontpage_post_type', 'frontpage_category' );
		} elseif ( is_archive() ) {
			$this->advExcLoc_do_excerpt( 'advanced_archive', 'archive_post_type', 'archive_category' );
		} elseif ( is_search() ) {
			$this->advExcLoc_do_excerpt( 'advanced_search', 'search_post_type', 'search_category' );
		}

		/**
		 * Excerpt in pages
		 */
		if ( 'disable' !== $this->opt['advanced_page_main'] ) {
			$type            = 'advanced_page_main';
			$excerpt_in_page = $this->opt['excerpt_in_page_advanced'];

			if ( is_array( $excerpt_in_page ) ) {
				foreach ( $excerpt_in_page as $key => $value ) {
					if ( '' === $value || ! is_page( $value ) ) {
						continue;
					}

					$this->args['ecae-content'] = true;
					break;
				}
			}

			if ( 'selection' === $this->opt['advanced_page_main'] ) {
				$advanced_page  = $this->opt['advanced_page'];
				$page_post_type = $this->opt['page_post_type'];
				$page_category  = $this->opt['page_category'];

				if ( is_array( $advanced_page ) && count( $advanced_page ) > 0 ) {
					foreach ( $advanced_page as $key => $value ) {
						if ( '' === $value || ! is_page( $value ) ) {
							continue;
						}

						if ( isset( $page_post_type[ $key ] ) ) {
							$this->advExcLoc_excerpt_in_post_type( $page_post_type[ $key ] );
						}

						if ( false == $this->args['ecae-content'] && isset( $page_category[ $key ] ) ) {
							$this->advExcLoc_excerpt_in_category( $page_category[ $key ] );
						} else {
							$this->args['default-content'] = true;
						}
					}
				}
			} // end advanced_page_main
		} // end location_settings_type
	}

	function advExcLoc_do_excerpt( $type, $post_type, $category ) {
		switch ( $this->opt[ $type ] ) {
			case 'all':
				$this->args['ecae-content'] = true;
				break;

			case 'selection':
				$this->advExcLoc_excerpt_in_post_type( $this->opt[ $post_type ] );

				if ( false == $this->args['ecae-content'] ) {
					$this->advExcLoc_excerpt_in_category( $this->opt[ $category ] );
				}

			default:
				$this->args['default-content'] = true;
				break;
		}
	}

	function advExcLoc_excerpt_in_post_type( $opt_post_type ) {
		$current_post_type    = get_post_type( get_the_ID() );
		$excerpt_in_post_type = $opt_post_type;

		if ( is_array( $excerpt_in_post_type ) && in_array( $current_post_type, $excerpt_in_post_type ) ) {
			$this->args['ecae-content'] = true;
		}
	}

	function advExcLoc_excerpt_in_category( $opt_category ) {
		$taxonomies = get_the_taxonomies( get_the_ID() );

		foreach ( $taxonomies as $key => $value ) :

			$taxonomy = $key;
			$category = wp_get_post_terms( get_the_ID(), $taxonomy );

			foreach ( $category as $n ) {
				$current_category    = $n->term_id;
				$excerpt_in_category = $opt_category;

				if ( is_array( $excerpt_in_category ) && in_array( $current_category, $excerpt_in_category ) ) {
					$this->args['ecae-content'] = true;

					break 2;
				}
			}

				endforeach;
	}

	function get_post_metadata( $null, $object_id, $meta_key, $single ) {
		if ( '_thumbnail_id' === $meta_key && 'featured-image' === $this->opt['show_image'] ) :

			$is_enable = apply_filters( 'ecae-thumbnail-mode', false );
			$the_post  = apply_filters( 'ecae-post', array() );
			$is_ecae   = isset( $the_post['ecae-content'] ) ? $the_post['ecae-content'] : false;

			if ( $is_ecae && ! $is_enable ) {
				return false;
			}

			endif;
	}
}

$GLOBALS['ECAE_The_Post'] = new ECAE_The_Post();
