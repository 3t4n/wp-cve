<?php 
/***************
 * Author: Rahul Negi
 * Team: InfoTheme
 * Date: 30-6-2022
 * Desc: Addon Loader , Loading as per requirement or request
 * Happy Coding.....
 **************/

if ( ! function_exists('it_epoll_admin_menus') ){

    add_action( 'admin_menu' , 'it_epoll_admin_menus' );

    /**
     * Generate sub menu page for settings
     *
     * @uses rushhour_projects_options_display()
     */
    function it_epoll_admin_menus()
    {

		add_menu_page(
			__('Epoll Dashboard', 'it_epoll'),
			__('ePoll', 'it_epoll'),
			'administrator',
			'epoll_dashboard',
			'show_it_epoll_dashboard',
			plugins_url( 'assets/imgs/epoll_logo.svg', dirname(__FILE__)),
			5
		);

		add_submenu_page('epoll_dashboard', 
		__('Epoll Dashboard', 'it_epoll'),
		__('Dashboard', 'it_epoll'),
		'manage_options', 
		'epoll_dashboard',
		null,
		0);

		add_submenu_page('epoll_dashboard', 
		__('Epoll Templates', 'it_epoll'),
		__('Templates', 'it_epoll'),
		'manage_options', 
		'epoll_templates',
		'show_it_epoll_dashboard_template',
		4);

		add_submenu_page('epoll_dashboard', 
		__('Epoll AddOns', 'it_epoll'),
		__('Add-ons', 'it_epoll'),
		'manage_options', 
		'epoll_addons',
		'show_it_epoll_dashboard_addons',
		5);
		add_submenu_page('epoll_dashboard', 
		__('Epoll Options', 'it_epoll'),
		__('Options', 'it_epoll'),
		'manage_options',
		'epoll_options',
		'show_it_epoll_dashboard_options',
		6);
		add_action( 'admin_init', 'it_epoll_options_settings' );

    
		add_submenu_page('epoll_dashboard', 
		__('Epoll How to Guide', 'it_epoll'),
		__('How To Guide', 'it_epoll'),
		'manage_options', 
		'epoll_docs',
		'show_it_epoll_dashboard_guide',
		7);
		add_submenu_page('epoll_dashboard', 
		__('FAQs', 'it_epoll'),
		__('Support & Faqs', 'it_epoll'),
		'manage_options', 
		'epoll_faq',
		'show_it_epoll_dashboard_faq',
		8);
		
    }
}

if(!function_exists('show_it_epoll_dashboard_template')){
	function show_it_epoll_dashboard_template(){
		include_once('admin/themes.php');
	}
}


if(!function_exists('show_it_epoll_dashboard_guide')){
	function show_it_epoll_dashboard_guide(){
		include_once('admin/guide.php');
	}
}

if(!function_exists('show_it_epoll_dashboard_faq')){
	function show_it_epoll_dashboard_faq(){
		include_once('admin/faq.php');
	}
}



if(!function_exists('show_it_epoll_dashboard_options')){
	function show_it_epoll_dashboard_options(){
	
		include_once('admin/options.php');
	}
}



if(!function_exists('show_it_epoll_dashboard_addons')){
	function show_it_epoll_dashboard_addons(){
		include_once('admin/addons.php');
	}
}


if(!function_exists('show_it_epoll_dashboard')){

    function show_it_epoll_dashboard(){
        include_once('admin/dashboard.php');
    }
}


if(!function_exists('it_epoll_options_settings')){
	function it_epoll_options_settings(){
		include_once('admin/option_save.php');
		do_action('it_epoll_options_save_extra_settings');
	} 
}



if (!function_exists('it_epoll_poll_create_voting_post_type') ) {
	function it_epoll_poll_create_voting_post_type() {
	
		$labels = array(
			'name'                => _x( 'Voting Contests', 'Post Type General Name', 'it_epoll' ),
			'singular_name'       => _x( 'Voting Contest', 'Post Type Singular Name', 'it_epoll' ),
			'menu_name'           => __( 'Voting Contests', 'it_epoll' ),
			'name_admin_bar'      => __( 'Voting Contest', 'it_epoll' ),
			'parent_item_colon'   => __( 'Parent Contest:', 'it_epoll' ),
			'all_items'           => __( 'Voting', 'it_epoll' ),
			'add_new_item'        => __( 'Create Contest', 'it_epoll' ),
			'add_new'             => __( 'Create Contest', 'it_epoll' ),
			'new_item'            => __( 'New Contest', 'it_epoll' ),
			'edit_item'           => __( 'Edit Contest', 'it_epoll' ),
			'update_item'         => __( 'Update Contest', 'it_epoll' ),
			'view_item'           => __( 'View Contest', 'it_epoll' ),
			'search_items'        => __( 'Search Contests', 'it_epoll' ),
			'not_found'           => __( 'Not found', 'it_epoll' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'it_epoll' ),
		);
		$args = array(
			'label'               => __( 'Voting Contest', 'it_epoll' ),
			'description'         => __( 'Voting Contest Description', 'it_epoll' ),
			'labels'              => $labels,
			'supports'            => array( 'title','thumbnail','revisions','comments'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        =>'epoll_dashboard',
			'menu_icon'			  => 'dashicons-chart-pie',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'menu_position'		  => 2,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite' 			  => array('slug' => 'contest'),
			'capability_type'     => 'page',
		);
		register_post_type( 'it_epoll_poll', $args );
		flush_rewrite_rules(true);
	}
	
	// Hook into the 'init' action
	add_action( 'init', 'it_epoll_poll_create_voting_post_type', 2 );
}
	

if (!function_exists('it_epoll_poll_create_poll_post_type') ) {
function it_epoll_poll_create_poll_post_type() {

	$labels = array(
		'name'                => _x( 'Polls', 'Post Type General Name', 'it_epoll' ),
		'singular_name'       => _x( 'Poll', 'Post Type Singular Name', 'it_epoll' ),
		'menu_name'           => __( 'Polls', 'it_epoll' ),
		'name_admin_bar'      => __( 'Poll', 'it_epoll' ),
		'parent_item_colon'   => __( 'Parent Poll:', 'it_epoll' ),
		'all_items'           => __( 'Poll', 'it_epoll' ),
		'add_new_item'        => __( 'Create Poll', 'it_epoll' ),
		'add_new'             => __( 'Create Poll', 'it_epoll' ),
		'new_item'            => __( 'New Poll', 'it_epoll' ),
		'edit_item'           => __( 'Edit Poll', 'it_epoll' ),
		'update_item'         => __( 'Update Poll', 'it_epoll' ),
		'view_item'           => __( 'View Poll', 'it_epoll' ),
		'search_items'        => __( 'Search Polls', 'it_epoll' ),
		'not_found'           => __( 'Not found', 'it_epoll' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'it_epoll' ),
	);
	$args = array(
		'label'               => __( 'Poll', 'it_epoll' ),
		'description'         => __( 'Poll Description', 'it_epoll' ),
		'labels'              => $labels,
		'supports'            => array( 'title','thumbnail','revisions','comments'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        =>'epoll_dashboard',
		'menu_icon'			  => 'dashicons-chart-pie',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'menu_position'		  => 2,		
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite' 			  => array('slug' => 'poll'),
		'capability_type'     => 'page',
	);
	register_post_type( 'it_epoll_opinion', $args );
	flush_rewrite_rules(true);
}

// Hook into the 'init' action
add_action( 'init', 'it_epoll_poll_create_poll_post_type', 3 );
}