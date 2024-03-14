<?php
/**
 * Instatiate plugin
 */
 
$rbkeypluginurl = plugin_dir_path( __FILE__ );
add_action('admin_menu', 'rbkeyword_sidebar_menu');


function rbkeyword_sidebar_menu() {
	 
	add_menu_page( 'RB KEYWORD', 'RB KEYWORD', 'administrator', 'rbkeyword_research', 'rbkeyword_research', plugins_url( '/glass.png' , __FILE__ ), 500 );
	//$rbmenusub = add_submenu_page( 'rbkeyword', 'Keyword Research', 'Research', 'administrator', 'rbkeyword_research', 'rbkeyword_research' );
	
	//add_action('admin_head-'.$rbdashsub, 'rbkeyword_admin_head_dashboard');
	//add_action('admin_head-'.$rbmenusub , 'rbkeyword_admin_head_research');
	add_action('admin_head-rb' , 'rbkeyword_admin_head_research');

}

function rbkeyword_admin_head_research(){

	//ui dialog
	wp_enqueue_style ( 'rb-jquery-ui-dialog' );
	wp_enqueue_script ( 'jquery-ui-dialog' );
	
	
	wp_enqueue_script('rbkeyword-gcomplete',plugins_url( '/js/jquery.gcomplete.0.1.2.js' , __FILE__ ));
	wp_enqueue_script('rbkeyword-main',plugins_url( '/js/rbkeyword_main.js' , __FILE__ ));
	wp_enqueue_script('rbkeyword-main',plugins_url( '/css/rbkeyword_style.css' , __FILE__ ));

}

