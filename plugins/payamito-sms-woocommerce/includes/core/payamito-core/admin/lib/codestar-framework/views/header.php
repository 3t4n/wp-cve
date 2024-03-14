<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

$demo    = get_option( 'kianfr_demo_mode', false );
$text    = ( ! empty( $demo ) ) ? 'Deactivate' : 'Activate';
$status  = ( ! empty( $demo ) ) ? 'deactivate' : 'activate';
$class   = ( ! empty( $demo ) ) ? ' kianfr-warning-primary' : '';
$section = ( ! empty( $_GET['section'] ) ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : 'about';
$links   = [
	'about'           => 'About',
	'quickstart'      => 'Quick Start',
	'documentation'   => 'Documentation',
	'free-vs-premium' => 'Free vs Premium',
	'support'         => 'Support',
	'relnotes'        => 'Release Notes',
];

?>
<div class="kianfr-welcome kianfr-welcome-wrap">

    <h1>Welcome to Codestar Framework v<?php
		echo esc_attr( KIANFR::$version ); ?></h1>

    <p class="kianfr-about-text">A Simple and Lightweight WordPress Option Framework for Themes and Plugins</p>

    <p class="kianfr-demo-button"><a href="<?php
		echo esc_url( add_query_arg( [ 'kianfr-demo' => $status ] ) ); ?>" class="button button-primary<?php
		echo esc_attr( $class ); ?>"><?php
			echo esc_attr( $text ); ?> Demo</a></p>

    <div class="kianfr-logo">
        <div class="kianfr--effects"><i></i><i></i><i></i><i></i></div>
        <div class="kianfr--wp-logos">
            <div class="kianfr--wp-logo"></div>
            <div class="kianfr--wp-plugin-logo"></div>
        </div>
        <div class="kianfr--text">Codestar Framework</div>
        <div class="kianfr--text kianfr--version">v<?php
			echo esc_attr( KIANFR::$version ); ?></div>
    </div>

    <h2 class="nav-tab-wrapper wp-clearfix">
		<?php

		foreach ( $links as $key => $link ) {
			if ( KIANFR::$premium && $key === 'free-vs-premium' ) {
				continue;
			}

			$activate = ( $section === $key ) ? ' nav-tab-active' : '';

			echo '<a href="' . esc_url( add_query_arg( [
					'page'    => 'kianfr-welcome',
					'section' => $key,
				], admin_url( 'tools.php' ) ) ) . '" class="nav-tab' . esc_attr( $activate ) . '">' . esc_attr( $link ) . '</a>';
		}

		?>
    </h2>
