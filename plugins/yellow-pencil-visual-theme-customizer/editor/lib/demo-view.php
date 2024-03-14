<?php

// Don't run this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// to load editor for visitors on demo mode
add_action("template_redirect", 'wyp_theme_redirect');

function wyp_theme_redirect(){

	if(defined('YP_DEMO_MODE') && isset($_GET['yellow_pencil']) == true){
		$yellow_pencil = YP_PLUGIN_DIR . '/editor/lib/redirection.php';
		wyp_do_theme_redirect($yellow_pencil);
	}

}


// to load customize type iframe for visitors on demo mode
add_action("template_redirect", 'wyp_theme_redirect2');

function wyp_theme_redirect2(){

	if(defined('YP_DEMO_MODE') && isset($_GET['wyp_customize_type']) == true){
		$yellow_pencil = YP_PLUGIN_DIR . '/editor/lib/customization-popup.php';
		wyp_do_theme_redirect($yellow_pencil);
	}

}


function wyp_do_theme_redirect($url) {

	global $post, $wp_query;

	if (have_posts()) {

		include($url);
		die();

	}else{

		$wp_query->is_404 = true;

	}

}

function wyp_demo_editor_header(){

	echo '<style>
		.wyp-demo-link{
			font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Helvetica, Arial, sans-serif;
			text-transform: uppercase;
			position:fixed;
			top:18%;
			left:0px;
			width:auto;
			z-index:9999999;
		    padding: 9px 11px !important;
		    border-radius: 0px 3px 3px 0px !important;
		    font-size: 14px !important;
		    font-weight: 600 !important;
		    background: #27AE60 !important;
		    color: #FFF !important;
		    border-width:0 !important;
		    text-transform: none !important;

		    -webkit-transition: background-color 250ms ease;
		    -moz-transition: background-color 250ms ease;
		    -ms-transition: background-color 250ms ease;
		    -o-transition: background-color 250ms ease;
		    transition: background-color 250ms ease;
		    text-decoration: none !important;
		}

		.wyp-demo-link:hover{
		    background-color:#2ABC67 !important;
		}

		body.yp-yellow-pencil .theme-demo-options{display:none !important;}@media(max-width:992px){.wyp-demo-link{display:none !important;}}</style>';

}

add_action("wp_head","wyp_demo_editor_header");


function wyp_demo_editor_footer(){

	// get data
    $data = wyp_get_page_ids();

    // Getting page informations
    $page_id = $data[0];
    $page_type = $data[1];
    $edit_mode = $data[2];

    // URL OF Editor
    $yellow_pencil_uri = wyp_get_uri();

    // Getting current page
    $href = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $edit_link = add_query_arg(array(
        'href' => wyp_urlencode(esc_url($href)),
        'wyp_page_id' => $page_id,
        'wyp_page_type' => $page_type,
        'wyp_mode' => $edit_mode
    ),$yellow_pencil_uri);

	echo '<a href="'.$edit_link.'" class="wyp-demo-link wyp-live-editor-link yp-demo-link yp-live-editor-link">Live Editor</a>';

}

add_action("wp_footer","wyp_demo_editor_footer");
