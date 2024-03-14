<?php
/**
 * Main functions to create your page navigation
 * We use your settings to display page navigation as your expected
 *
 * @author Kang
 * @since  1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! function_exists( 'easy_wp_pagenavigation' ) ) {
	function easy_wp_pagenavigation( $custom_query = false ) {
		global $wp_query;
		// Get your options
		$options         = get_option( EWPN_ST );
		$text_first_page = ! empty ( $options['first_text'] ) ? $options['first_text'] : '';
		$text_last_page  = ! empty ( $options['last_text'] ) ? $options['last_text'] : '';
		$text_prev_page  = ! empty ( $options['prev_text'] ) ? $options['prev_text'] : '&laquo;';
		$text_next_page  = ! empty ( $options['next_text'] ) ? $options['next_text'] : '&raquo;';
		$style           = isset( $options['style'] ) ? ' style-' . esc_html( $options['style'] ) : ' style-default';
		$align           = ! empty ( $options['align'] ) ? $options['align'] : 'left';

		$before_page_number = '';
		$after_page_number = '';

		if ( ' style-diamond-square' == $style  ):
			$before_page_number = '<span>';
			$after_page_number = '</span>';
		endif;

		$current_page = get_query_var( 'paged' );

		if ( ! $custom_query )
			$custom_query = $wp_query;

		$big         = 999999999; // need an unlikely integer
		$paginations = paginate_links( array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $custom_query->max_num_pages,
			'type'      => 'array',
			'prev_text' => $text_prev_page,
			'next_text' => $text_next_page,
			'before_page_number' => $before_page_number,
			'after_page_number'  => $after_page_number
		) );

		if ( $paginations ) {
			$return = '';
			$return .= '<div class="easy-wp-page-navigation align-' . $align . $style . '"><ul class="easy-wp-page-nav">';
			if ( $current_page > 1 && ! empty( $text_first_page ) )
				$return .= '<li class="first-page"><a class="page-numbers first-page-link" href="' . esc_url( get_pagenum_link( 1 ) ) . '">' . $text_first_page . '</a></li>';
			foreach ( $paginations as $nav ) {
				$return .= '<li>' . $nav . '</li>';
			}
			if ( $current_page != $custom_query->max_num_pages && ! empty( $text_last_page ) )
				$return .= '<li class="last-page"><a class="page-numbers last-page-link" href="' . esc_url( get_pagenum_link( $custom_query->max_num_pages ) ) . '">' . $text_last_page . '</a></li>';
			$return .= '</ul></div>';

			return $return;
		}
		else return;
	}
}


/**
 * Hook to change posts per page default when template is loaded
 *
 * @author Kang
 * @since  1.0
 */

if ( ! function_exists( 'easy_wp_pagenavigation_set_posts_per_page' ) ) {
	add_filter( 'pre_get_posts', 'easy_wp_pagenavigation_set_posts_per_page' );
	function easy_wp_pagenavigation_set_posts_per_page( $query ) {
		if ( is_admin() )
			return;

		$options = get_option( EWPN_ST );
		// Get taxonomies custom posts per page
		$args = array(
			'public'  => true,
			'show_ui' => true
		);

		$taxonomies = get_taxonomies( $args );

		if ( ! empty ( $taxonomies ) ) {
			foreach ( $taxonomies as $key => $val ) {
				if ( isset( $options[$key] ) && ! empty( $options[$key] ) && is_numeric( $options[$key] ) ) {
					if ( $key == 'category' ) {
						if ( is_category() && $query->is_main_query() )
							$query->set( 'posts_per_page', $options[$key] );
					}
					elseif ( $key == 'post_tag' ) {
						if ( is_tag() && $query->is_main_query() )
							$query->set( 'posts_per_page', $options[$key] );
					}
					else {
						if ( is_tax( $key ) && $query->is_main_query() )
							$query->set( 'posts_per_page', $options[$key] );
					}
				}
			}
		}
	}
}