<?php
require_once( dirname( __FILE__ ) . '/class-ib-options.php' );

require_once( dirname( __FILE__ ) . '/class-ib-yoast-generator.php' );
require_once( dirname( __FILE__ ) . '/class-ib-navxt-generator.php' );

class IB_Generator {
	private static $clazz = array( 'builtin' => 'IB_Generator', 'yoast' => 'IB_Yoast_Generator', 'navxt' => 'IB_Navxt_Generator' );
	private $queried_object;

	private static function term_hierarchy( $term ) {
		$terms = array( $term );
		while ( $term->parent != 0 ) {
			$term = get_term( $term->parent, $term->taxonomy );
			array_unshift( $terms, $term );
		}
		return $terms;
	}

	private static function option( $option, $name, $def ) {
		return isset( $option[ $name ] ) ? $option[ $name ] : $def;
	}

	public static function create() {
		$select = IB_Options::safe_selection( 'gen' );
		$gen    = self::named_generator( $select );
		if ( $gen ) return $gen;
		return new IB_Generator;
	}

	public static function named_generator( $select ) {
		if ( isset( self::$clazz[ $select ] ) ) {
			$tomake = self::$clazz[ $select ];
			$gen    = new $tomake;
			if ( $gen && $gen->is_supported() ) return $gen;
		}
		return null;
	}

	public function is_supported() {
		return TRUE;
	}
	
	public function init() {
		global $wp_query;
		$this->queried_object = $wp_query->get_queried_object();
	}

	private static function strip( $text ) {
		if ( IB_Options::safe_boolean( 'strip' ) ) {
			return strip_tags( $text );
		}
		return $text;
	}

	public function generate_crumbs() {
		global $wp, $wp_query;

		$currentUrl = home_url( add_query_arg( array(), $wp->request ) );
		// An issue detected by luoshiben: http://wordpress.org/support/topic/safe-string-title-settings
		// If show_on_front is set to page, then the title of that page should be used.
		$home_title = '';
		if ( get_option( 'show_on_front' ) == 'page' ) {
			$home_title = self::strip( get_the_title( get_option( 'page_on_front' ) ) );
		}
		$crumbs = array( array( 'url' => home_url(), 'text' => empty( $home_title ) ? IB_Options::safe_string( 'front' ) : $home_title, 'xclass' => array( 'menu-item-home' ) ) );
		if ( is_front_page() ) {
			// done
		} elseif ( is_home() ) {
			// special case, if we are on the posts list, and that's not the front page
			$crumbs[] = array( 'url' => $currentUrl, 'text' => IB_Options::safe_string( 'pages' ) );
		} elseif ( is_singular() ) {
			$post = $this->queried_object;
			// use the post type if it has one
			$postType = $post->post_type;
			$url = get_post_type_archive_link( $postType );
			if ( $url ) {
				$pto      = get_post_type_object( $postType );
				$label    = ( isset( $pto->label ) && $pto->label != '') ? $pto->label : $pto->labels->menu_name;
				$crumbs[] = array( 'url' => $url, 'text' => self::strip( $label ) );
			}
	
			// use ancestry if it has some
			$ancestors = get_post_ancestors( $post );
			if ( count( $ancestors ) > 0 ) {
				$ancestors = array_reverse( $ancestors );
				foreach ( $ancestors as $id ) {
					$ancestor = get_post( $id );
					$crumbs[] = array(
						'url' => get_permalink( $id ),
						'text' => self::strip( $ancestor->post_title ),
						'xclass' => array( 'menu-item-type-post_type', 'menu-item-object-' . $ancestor->post_type )
						);
				}
			}
			// otherwise use the category if it has one 			// OPTIONS!
			else {
				$taxonomy = 'category';
				$terms    = wp_get_object_terms( $post->ID, $taxonomy );
				if ( ! is_wp_error( $terms ) ) {
					// skip items which are parents of others
					$parents = array();
					foreach ( $terms as $term ) {
						$parents[$term->parent] = TRUE;
					}
					// there may be more than one, which one do you choose? And do we trace parents?
					// find the deepest one? And if there's a tie? Pick the one lowest in the order.
					$deepest   = array();
					$bestOrder = 0;
					$ds        = 0;
					foreach ( $terms as $term ) {
						if ( isset( $parents[$term->term_id] ) ) continue;
						$hierarchy = self::term_hierarchy( $term );
						$hs = count( $hierarchy );

						if ( ($hs > $ds) || ( ($hs == $ds) && ($term->count > $bestOrder) ) ) {
							$deepest   = $hierarchy;
							$bestOrder = $term->count;
							$ds        = $hs;
						}
					}
					foreach ( $deepest as $term ) {
						$crumbs[] = array( 
							'url' => get_term_link( $term ),
							'text' => self::strip( $term->name ),
							'xclass' => array( 'menu-item-type-taxonomy', 'menu-item-object-' . $term->taxonomy )
							);
					}
				}
			}
			// add the post itself
			$crumbs[] = array(
				'url' => $currentUrl,
				'text' => self::strip( $post->post_title ),
				'xclass' => array( 'menu-item-type-post_type', 'menu-item-object-' . $postType )
				);
		} elseif ( is_post_type_archive() ) {
			// add the page itself with a suitable name
			$postType = $wp_query->query['post_type'];
			$pto      = get_post_type_object( $postType );
			$label    = ( isset( $pto->label ) && $pto->label != '') ? $pto->label : $pto->labels->menu_name;
			$url      = get_post_type_archive_link( $postType );
			$crumbs[] = array( 'url' => $url, 'text' => self::strip( $label ) );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			// add the page itself with a suitable name
			$term = $this->queried_object;
			// it might have a hierarchy
			$terms = self::term_hierarchy( $term );
			foreach ( $terms as $term ) {
				$crumbs[] = array(
					'url' => get_term_link( $term ),
					'text' => self::strip( $term->name ),
					'xclass' => array( 'menu-item-type-taxonomy', 'menu-item-object-' . $term->taxonomy )
					);
			}
		} elseif ( is_date() ) {
			// add the page itself with a suitable name
			if ( is_day() ) {
				$crumbs[] = array( 'url' => $currentUrl, 'text' => self::strip( get_the_date() ) );
			} elseif ( is_month() ) {
				$crumbs[] = array( 'url' => $currentUrl, 'text' => self::strip( trim( single_month_title( ' ', FALSE ) ) ) );
			} elseif ( is_year() ) {
				$crumbs[] = array( 'url' => $currentUrl, 'text' => self::strip( get_query_var( 'year' ) ) );
			} else {
				$crumbs[] = array( 'url' => $currentUrl, 'text' => IB_Options::safe_string( 'archive' ) );
			}
		} elseif ( is_author() ) {
			// add the page itself with a suitable name
			$user     = $this->queried_object;
			$crumbs[] = array( 'url' => $currentUrl, 'text' => self::strip( $user->display_name ) );			
		} elseif ( is_search() ) {
			// add the page itself with a suitable name
			$crumbs[] = array( 'url' => $currentUrl, 'text' => self::strip( get_search_query() ) );			
		} elseif ( is_404() ) {
			// add the page itself with a suitable name
			$crumbs[] = array( 'url' => $currentUrl, 'text' => IB_Options::safe_string( 'notfound' ) );
		} else {
			// catchall, but I don't know what to call this page
			$crumbs[] = array( 'url' => $currentUrl, 'text' => IB_Options::safe_string( 'current' ) );
		}
		return $crumbs;
	}
}
