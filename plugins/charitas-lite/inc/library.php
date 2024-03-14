<?php

/**
* Library
*
* @since 1.0.0
* @access public
*/


/*-----------------------------------------------------------
	Display Navigation for post, pages, search
-----------------------------------------------------------*/

if ( ! function_exists( 'charitas_lite_content_navigation' ) ) {

	function charitas_lite_content_navigation( $nav_id ) {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo $nav_id; ?>">
				<div class="nav-previous"><?php previous_posts_link( __( '<span>&larr;</span>  <span class="mobile-nav">Newer</span>', 'charitas-lite' ) ); ?></div>
				<div class="nav-next"><?php next_posts_link( __( '<span class="mobile-nav">Older</span> <span>&rarr;</span>', 'charitas-lite' ) ); ?></div>
				<div class="clear"></div>
			</nav><!-- #nav -->
		<?php endif;
	}

}


/*-----------------------------------------------------------
	Add Single Page Template for Projects, Causes, Staff
-----------------------------------------------------------*/

function charitas_lite_get_custom_post_type_template( $single_template ) {
	global $post;

	if ( 'post_projects' === $post->post_type ) {
		$single_template = CHARITAS_LITE_DIR . 'inc/templates/single-post_projects.php';
	} elseif( 'post_causes' === $post->post_type ) {
		$single_template = CHARITAS_LITE_DIR . 'inc/templates/single-post_causes.php';
	} elseif ( 'post_staff' === $post->post_type  ){
		$single_template = CHARITAS_LITE_DIR . 'inc/templates/single-post_staff.php';
	} else {
		// none
	}

	return $single_template;
}
add_filter( 'single_template', 'charitas_lite_get_custom_post_type_template' );


/*-----------------------------------------------------------
	Add Project, Causes & Staff Templates to Page Attributes
-----------------------------------------------------------*/

function charitas_lite_add_page_template_to_dropdown($templates) {
	$templates[plugin_dir_path(__FILE__) . 'inc/templates/template-projects.php'] = __('Projects List', 'charitas-lite');
	$templates[plugin_dir_path(__FILE__) . 'inc/templates/template-causes.php'] = __('Causes List', 'charitas-lite');
	$templates[plugin_dir_path(__FILE__) . 'inc/templates/template-staff.php'] = __('Staff List', 'charitas-lite');
	return $templates;
}
add_filter('theme_page_templates', 'charitas_lite_add_page_template_to_dropdown');


/*-----------------------------------------------------------
	Add Page Template to display a list of projects, Causes and Staff
-----------------------------------------------------------*/

function charitas_lite_get_projects_page_template ($template) {

	$post = get_post();
	$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

	if ('template-projects.php' == basename ($page_template)) {
		$template = CHARITAS_LITE_DIR . 'inc/templates/template-projects.php';
	} elseif ('template-causes.php' == basename ($page_template)) {
		$template = CHARITAS_LITE_DIR . 'inc/templates/template-causes.php';
	} elseif ('template-staff.php' == basename ($page_template)) {
		$template = CHARITAS_LITE_DIR . 'inc/templates/template-staff.php';
	} else {

	}

	return $template;

}
add_filter ('page_template', 'charitas_lite_get_projects_page_template');


/*-----------------------------------------------------------------------------------*/
/*	BE Dashbord Widget
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'charitas_lite_dashboard_widgets' ) ) {

	function charitas_lite_dashboard_widgets() {
		global $wp_meta_boxes;
		unset(
			$wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
			$wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
			$wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']
		);
			wp_add_dashboard_widget( 'dashboard_custom_feed', '<a href="https://wplook.com?utm_source=Our-Themes&utm_medium=rss&utm_campaign=Charitas_lite_plugin">WPlook News</a>' , 'charitas_lite_dashboard_custom_feed_output' );
	}
	add_action('wp_dashboard_setup', 'charitas_lite_dashboard_widgets');

}


if ( ! function_exists( 'charitas_lite_dashboard_custom_feed_output' ) ) {

	function charitas_lite_dashboard_custom_feed_output() {
		echo '<div class="rss-widget rss-wplook">';
		wp_widget_rss_output(array(
			'url' => 'http://feeds.feedburner.com/wplook',
			'title' => '',
			'items' => 4,
			'show_summary' => 1,
			'show_author' => 0,
			'show_date' => 0
			));
		echo '</div>';
	}

}

?>
