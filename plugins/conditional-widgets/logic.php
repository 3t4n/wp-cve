<?php
/**
 * @author    Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2011 - 2015 Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   CETS\Conditional_Widgets
 */

add_filter( 'widget_display_callback', 'conditional_widgets_widget' );

/**
 * Determine whether or not this widget should be displayed on this page request
 */
function conditional_widgets_widget( $instance ) {
	
	/* variables we have access to
	$instance['cw_home_enable_checkbox']
	$instance['cw_select_home_page']  //show == 1, hide == 0, show only on home == 2
	
	// pages
	$instance['cw_pages_enable_checkbox']
	$instance['cw_select_pages']
	$instance['cw_pages_sub_checkbox']
	$instance['cw_selected_pages']
	$instance['cw_pages_all'] // since 1.8
	
	// custom_taxes  - since 1.9
	$instance['cw_custom'] 
	$instance['cw_custom'][$type][$tax]['enable']
	$instance['cw_custom'][$type][$tax]['select']
	$instance['cw_custom'][$type][$tax]['all'] 
	$instance['cw_custom'][$type][$tax]['sub']
	$instance['cw_custom'][$type][$tax]['selected_ids']
	
	// utility
	$instance['cw_posts_page_hide']
	$instance['cw_404_hide']
	$instance['cw_search_hide']
	$instance['cw_date_archive_hide']
	$instance['cw_author_archive_hide']
	$instance['cw_tag_archive_hide']

	// utility - other
	$instance['cw_mobile_hide'] - since 2.2
	
	*/
	
	// First, process 'other' options which take precedence over the following

	/* is_mobile */
	if ( isset( $instance['cw_mobile_hide'] ) && $instance['cw_mobile_hide'] == 1 ) {
		if ( wp_is_mobile() ) {
			return false;
		}
	}

	/* is_NOT_mobile */
	if ( isset( $instance['cw_desktop_hide'] ) && $instance['cw_desktop_hide'] == 1 ) {
		if ( ! wp_is_mobile() ) {
			return false;
		}
	}


	global $wp_query;
	$qvars = $wp_query->query_vars;

	$type_tax_pairs = apply_filters( 'conditional_widgets_type_tax_pairs', array() );

	$instance = conditional_widgets_init_instance( $instance );
	
	if ( $instance['cw_home_enable_checkbox'] ) {
		//box checked for home page logic takes priority by processing first
		switch ( $instance['cw_select_home_page'] ) {
			case 0: //hide if front page
				if ( is_front_page() ) {
					return false;
				}
				break;
			case 1: //show if front page
				if ( is_front_page() ) {
					return $instance;
				}
				break;
			case 2: //only show on front, hide otherwise
				if ( is_front_page() ) {
					return $instance;
				} else {
					return false;
				}
				break;
		}
	}
	
	$arr_pages = $instance['cw_selected_pages'];
	if ( ! is_array( $arr_pages ) ) {
		$arr_pages = array();
	}

	if ( $instance['cw_pages_enable_checkbox'] && is_page() ) {
		
		// We care about pages and this is a page.
		


		// see if we are using same logic for ALL pages
		if ( isset( $instance['cw_pages_all'] ) && $instance['cw_pages_all'] == 1 ) {
			if ( $instance['cw_select_pages'] == 1 ) {
				// SHOW
				return $instance;
			} else {
				// HIDE
				return false;
			}
		}

		$current_page_id = $wp_query->post->ID;
		
		//see if we care about subpages
		if ( $instance['cw_pages_sub_checkbox'] == 1 ) {
			foreach ( $arr_pages as $page ) {
				$args     = 'child_of=' . $page;
				$newpages = get_pages( $args );
				foreach ( $newpages as $newpage ) {
					array_push( $arr_pages, $newpage->ID );
				}
			}
		} //if subpages count

		$match = false;
		if ( in_array( $current_page_id, $arr_pages ) ) {
			$match = true;
		}

		if ( $match ) {
			//we matched a page
			if ( $instance['cw_select_pages'] == 1 ) {
				//and we're showing on matched pages - SHOW
				return $instance;
			} else {
				//and we're hiding on matched pages - so HIDE
				return false;
			}
		} else {
			//we did NOT match a page
			if ( $instance['cw_select_pages'] == 1 ) {
				//and we're telling it to show on matched pages - so HIDE
				return false;
			} else {
				//we didn't match a page, and we told it to hide on those pages - so SHOW
				return $instance;
			}
		}
	} //is_page && we care
	

	// is individual post of any type (other than page)
	if ( is_single() ) {

		$current_post_id = $wp_query->post->ID;
		$type            = get_post_type();

		if ( isset( $instance['cw_custom'][$type] ) ) {

			$subdata_type = $instance['cw_custom'][$type];


			foreach ( $subdata_type as $tax => $subdata_type_tax ) {
				// loop over each taxonomy with CW logic associated with this type

				if ( $subdata_type_tax['enable'] != 1 ) {
					// we don't care about this post type, so just display.
					return $instance;
				}

				if ( $subdata_type_tax['all'] == 1 ) {
					// Don't care about specific terms, just the post type.
					if ( $subdata_type_tax['select'] == 1 ) {
						return $instance;
					} else {
						return false;
					}
				}

				// we DO care about specific terms, so see if we match a term or not
				$terms_to_match = $subdata_type_tax['selected_ids'];


				if ( !is_array( $terms_to_match ) ) {
					$terms_to_match = array();
				}


				if ( $subdata_type_tax['sub'] == 1 ) {
					if ( isset( $subdata_type_tax['selected_ids'] ) && is_array( $subdata_type_tax['selected_ids'] ) ) {
						foreach ( $subdata_type_tax['selected_ids'] as $term ) {
							$kiddos         = get_term_children( $term, $tax );
							$terms_to_match = array_merge( $terms_to_match, $kiddos );
						}
					}
				}

				if ( $terms_to_match ) {
					$match = is_object_in_term( $current_post_id, $tax, $terms_to_match );
				} else {
					$match = false;
				}

				if ( $match ) {
					if ( $subdata_type_tax['select'] == 1 ) {
						return $instance;
					} else {
						return false;
					}
				} else {
					if ( $subdata_type_tax['select'] == 1 ) {
						return false;
					} else {
						return $instance;
					}
				}

				//is_object_in_taxonomy - TODO?
			}
		} else {
			// we have no settings for this post type
			return $instance;
		}

	} // /is_single


	if ( is_category() ) {

		if ( isset( $instance['cw_custom']['post']['category'] ) ) {
			$cat_subdata = $instance['cw_custom']['post']['category'];
		} else {
			// no post specific logic here
			return $instance;
		}

		if ( $cat_subdata['enable'] != 1 ) {
			// we don't care about categories
			return $instance;
		}

		if ( $cat_subdata['all'] == 1 ) {
			// Don't care about specific terms, just the post type.
			if ( $cat_subdata['select'] == 1 ) {
				return $instance;
			} else {
				return false;
			}
		}

		// we DO care about specific terms, so see if we match a term or not
		$terms_to_match = $cat_subdata['selected_ids'];

		if ( $cat_subdata['sub'] == 1 ) {
			if ( isset( $cat_subdata['selected_ids'] ) && is_array( $cat_subdata['selected_ids'] ) ) {
				foreach ( $cat_subdata['selected_ids'] as $term ) {
					$kiddos         = get_term_children( $term, 'category' );
					$terms_to_match = array_merge( $terms_to_match, $kiddos );
				}
			}
		}

		if ( ! is_array( $terms_to_match ) ) {
			$terms_to_match = array();
		}

		$cat   = $qvars['cat'];
		$match = in_array( $cat, $terms_to_match );

		if ( $match ) {
			if ( $cat_subdata['select'] == 1 ) {
				return $instance;
			} else {
				return false;
			}
		} else {
			if ( $cat_subdata['select'] == 1 ) {
				return false;
			} else {
				return $instance;
			}
		}

	} // /is_category


	// TODO
	/*
	if (is_tax()) {
		
	}
	*/

	//since 1.4
	if ( is_home() ) {
		if ( $instance['cw_posts_page_hide'] == 1 ) {
			return false;
		}
	}

	// since 1.1
	if ( is_404() ) {
		if ( $instance['cw_404_hide'] == 1 ) {
			return false;
		}
	}  //if 404

	// since 1.1
	if ( is_search() ) {
		if ( $instance['cw_search_hide'] == 1 ) {
			return false;
		}
	} // if search

	// since 1.1
	if ( is_author() ) {
		if ( $instance['cw_author_archive_hide'] == 1 ) {
			return false;
		}
	}

	// since 1.1
	if ( is_date() ) {
		if ( $instance['cw_date_archive_hide'] == 1 ) {
			return false;
		}
	}

	//since 1.2
	if ( is_tag() ) {
		if ( $instance['cw_tag_archive_hide'] == 1 ) {
			return false;
		}
	}

	//default to showing
	return $instance;
	
} // /function conditional_widgets_widget()
