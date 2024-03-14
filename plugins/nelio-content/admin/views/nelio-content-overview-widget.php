<?php

namespace Nelio_Content\Admin\Views\Overview_Dashboard_Widget;

use Nelio_Content_Settings;
use function Nelio_Content\Helpers\key_by;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

function add_widget() {
	wp_add_dashboard_widget(
		'nelio-content-dashboard-overview',
		_x( 'Nelio Content Overview', 'text', 'nelio-content' ),
		__NAMESPACE__ . '\render_widget'
	);

	// Move our widget to top.
	global $wp_meta_boxes;

	$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$ours      = array(
		'nelio-content-dashboard-overview' => $dashboard['nelio-content-dashboard-overview'],
	);

	$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $ours, $dashboard ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}//end add_widget()
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\add_widget' );

function fetch_news() {
	$news = get_news( 'fetch' );
	if ( empty( $news ) ) {
		echo '';
		die();
	}//end if

	printf( '<h3>%s</h3>', esc_html_x( 'News & Updates', 'text', 'nelio-content' ) );
	echo '<ul>';
	array_walk( $news, __NAMESPACE__ . '\render_single_news' );
	echo '</ul>';
	die();
}//end fetch_news()
add_action( 'wp_ajax_nelio_content_fetch_news', __NAMESPACE__ . '\fetch_news' );

function render_widget() {
	render_title();
	render_posts();
	render_news();
	render_actions();
}//end render_widget()

function render_title() {
	$icon = file_get_contents( nelio_content()->plugin_path . '/assets/dist/images/logo.svg' );
	$icon = str_replace( 'fill="inherit"', 'fill="currentcolor"', $icon );
	$icon = str_replace( 'width="20"', '', $icon );
	$icon = str_replace( 'height="20"', '', $icon );
	printf(
		'<div class="nelio-content-header"><div class="nelio-content-header__icon">%s</div><div class="nelio-content-header__version"><p>%s</p><p>%s</p></div></div>',
		$icon, // phpcs:ignore
		esc_html( 'Nelio Content v' . nelio_content()->plugin_version ),
		/**
		 * Filters the extra version in overview widget.
		 *
		 * @param string $version Extra version. Default: empty string.
		 *
		 * @since 6.2.0
		 */
		esc_html( apply_filters( 'nelio_content_extra_version_in_overview_widget', '' ) )
	);
}//end render_title()

function render_posts() {
	$posts = get_last_posts();
	if ( empty( $posts ) ) {
		return;
	}//end if
	echo '<div class="nelio-content-posts">';
	printf( '<h3>%s</h3>', esc_html_x( 'Recently Updated', 'text (tests)', 'nelio-content' ) );
	echo '<ul>';
	array_walk( $posts, __NAMESPACE__ . '\render_post' );
	echo '</ul>';
	echo '</div>';
}//end render_posts()

function render_news() {
	$news = get_news( 'cache' );
	if ( empty( $news ) ) {
		echo '<div class="nelio-content-news"><div class="spinner is-active"></div></div>';
		printf(
			'<script type="text/javascript">fetch(%s).then((r)=>r.text()).then((d)=>{document.querySelector(".nelio-content-news").innerHTML=d;})</script>',
			wp_json_encode( add_query_arg( 'action', 'nelio_content_fetch_news', admin_url( 'admin-ajax.php' ) ) )
		);
		return;
	}//end if

	echo '<div class="nelio-content-news">';
	printf( '<h3>%s</h3>', esc_html_x( 'News & Updates', 'text', 'nelio-content' ) );
	echo '<ul>';
	array_walk( $news, __NAMESPACE__ . '\render_single_news' );
	echo '</ul>';
	echo '</div>';
}//end render_news()

function render_actions() {
	echo '<div class="nelio-content-actions">';
	if ( nc_can_current_user_use_plugin() ) {
		printf(
			'<span><a href="%s">%s</a></span>',
			esc_url( add_query_arg( 'page', 'nelio-content', admin_url( 'admin.php' ) ) ),
			esc_html_x( 'Editorial Calendar', 'text', 'nelio-content' )
		);
	}//end if

	printf(
		'<span><a href="%s" target="_blank">%s <span class="dashicons dashicons-external"></span></a></span>',
		esc_url(
			add_query_arg(
				array(
					'utm_source'   => 'nelio-content',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'support',
					'utm_content'  => 'overview-widget',
				),
				'https://neliosoftware.com/blog'
			)
		),
		esc_html_x( 'Blog', 'text', 'nelio-content' )
	);

	printf(
		'<span><a href="%s" target="_blank">%s <span class="dashicons dashicons-external"></span></a></span>',
		esc_url(
			add_query_arg(
				array(
					'utm_source'   => 'nelio-content',
					'utm_medium'   => 'plugin',
					'utm_campaign' => 'support',
					'utm_content'  => 'overview-widget',
				),
				'https://neliosoftware.com/content/help'
			)
		),
		esc_html_x( 'Help', 'text', 'nelio-content' )
	);
	echo '</div>';
}//end render_actions()

function get_last_posts() {
	$settings   = Nelio_Content_Settings::instance();
	$post_types = $settings->get( 'calendar_post_types' );
	$post_types = is_array( $post_types ) ? $post_types : array();

	$statuses = array_flatten( array_map( 'nelio_content_get_post_statuses', $post_types ) );
	$statuses = wp_list_pluck( $statuses, 'slug' );
	$statuses = array_values( array_unique( $statuses ) );

	return get_posts(
		array(
			'post_type'   => $post_types,
			'count'       => 5,
			'post_status' => $statuses,
		)
	);
}//end get_last_posts()

function render_post( \WP_Post $p ) {
	$title  = trim( $p->post_title );
	$title  = empty( $title ) ? esc_html_x( 'Unnamed post', 'text', 'nelio-content' ) : $title;
	$format = esc_html_x( 'M d, h:ia', 'PHP datetime format', 'nelio-content' );
	$date   = get_the_modified_date( $format, $p->ID );

	$post_type = get_post_type_object( $p->post_type );
	$post_type = empty( $post_type ) ? '' : $post_type->labels->singular_name;
	$post_type = is_string( $post_type ) && 0 < strlen( $post_type ) ? "| {$post_type}" : '';

	$default_icon = 'publish' === $p->post_status ? 'visibility' : 'edit';
	$statuses     = \nelio_content_get_post_statuses( $p->post_status );
	$statuses     = key_by( $statuses, 'slug' );
	$icon         = ! empty( $statuses[ $p->post_status ]['icon'] )
		? $statuses[ $p->post_status ]['icon']
		: $default_icon;

	echo '<li class="nelio-content-post">';

	if ( 'publish' === $p->post_status ) {
		printf(
			'<a href="%s">%s</a>',
			esc_url( get_permalink( $p ) ),
			esc_html( $title )
		);
	} elseif ( current_user_can( 'edit_post', $p ) ) {
		printf(
			'<a href="%s">%s</a>',
			esc_url( get_edit_post_link( $p ) ),
			esc_html( $title )
		);
	} else {
		printf( '<span>%s</span>', esc_html( $title ) );
	}//end if

	printf(
		' <span class="nelio-content-post__type">%s</span> <span class="dashicons dashicons-%s"></span> <span class="nelio-content-post__date">%s</span>',
		esc_html( $post_type ),
		esc_attr( $icon ),
		esc_html( $date )
	);

	echo '</li>';
}//end render_post()

function get_news( $mode ) {
	if ( 'fetch' === $mode ) {
		$rss = fetch_feed( 'https://neliosoftware.com/overview-widget/?tag=nelio-content' );
		if ( is_wp_error( $rss ) ) {
			return array();
		}//end if
		$news = $rss->get_items( 0, 3 );
		$news = array_map(
			function( $n ) {
				return array(
					'title'   => $n->get_title(),
					'link'    => $n->get_permalink(),
					'type'    => $n->get_description(),
					'excerpt' => $n->get_content(),
				);
			},
			$news
		);
		set_transient( 'nelio_content_news', $news, WEEK_IN_SECONDS );
	}//end if

	$news = get_transient( 'nelio_content_news' );
	return empty( $news ) ? array() : $news;
}//end get_news()

function render_single_news( $n ) {
	echo '<div class="nelio-content-single-news">';

	echo '<div class="nelio-content-single-news__header">';
	printf(
		'<span class="nelio-content-single-news__type nelio-content-single-news__type--is-%s">%s</span> ',
		esc_attr( $n['type'] ),
		esc_html(
			'release' === $n['type']
				? esc_html_x( 'NEW', 'text', 'nelio-content' )
				: esc_html_x( 'INFO', 'text', 'nelio-content' )
		)
	);
	printf(
		'<a class="nelio-content-single-news__title" href="%s" target="_blank">%s</a>',
		esc_url( $n['link'] ),
		esc_html( $n['title'] )
	);
	echo '</div>';

	printf(
		'<div class="nelio-content-single-news__excerpt">%s</div>',
		esc_html( $n['excerpt'] )
	);

	echo '</div>';
}//end render_single_news()

function array_flatten( array $array_of_arrays ) {
	$result = array();
	foreach ( $array_of_arrays as $array ) {
		$result = array_merge( $result, $array );
	}//end foreach
	return $result;
}//end array_flatten()
