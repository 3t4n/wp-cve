<?php

add_action( 'admin_menu', 'srizon_mortgage_admin_menu' );


function srizon_mortgage_admin_menu() {
	$srizon_mortgage_menu_hook = add_menu_page( __( 'Reactive Mortgage Calc', 'reactive-mortgage-calculator' ), __( 'Mortgage Calc', 'reactive-mortgage-calculator' ), apply_filters( 'srizon_mortgage_admin_access', 'edit_posts' ), 'SrizonMortgage', 'srizon_mortgage_admin_page', 'dashicons-chart-bar' );

	add_action( "admin_print_scripts-{$srizon_mortgage_menu_hook}", 'srizon_mortgage_load_admin_resources' );
}

function srizon_mortgage_load_admin_resources() {
	wp_enqueue_script( 'wp-api' );
	wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700', null, '1.0' );
	wp_enqueue_style( 'material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', null, '1.0' );
	wp_enqueue_style( 'srizon-materialize', srizon_mortgage_get_resource_url( 'admin/resources/materialize.css' ), null, '1.0' );
	wp_enqueue_style( 'srizon-mortgage-admin', srizon_mortgage_get_resource_url( 'admin/resources/app.css' ), null, '1.0' );

	wp_enqueue_script( 'srizon-materialize', srizon_mortgage_get_resource_url( 'site/resources/materialize.js' ), [ 'jquery' ], '1.0', true );
	wp_enqueue_script( 'react', srizon_mortgage_get_resource_url( 'site/resources/react.min.js' ), null, '15.6.1' );
	wp_enqueue_script( 'react-dom', srizon_mortgage_get_resource_url( 'site/resources/react-dom.min.js' ), null, '15.6.1' );
	wp_enqueue_script( 'srizon-mortgage-admin', srizon_mortgage_get_resource_url( 'admin/resources/app.js' ), null, '1.0', true );
}

function srizon_mortgage_admin_page() {
	// render admin
	?>
	<div class="srizon">
		<div id="srizon-mortgage-admin"></div>
	</div>

	<?php
}
