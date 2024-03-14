<?php

if(!function_exists('preloader_awesome_default_loader_css')) {
	function preloader_awesome_default_loader_css() {

	}
}

if(!function_exists('preloader_awesome_custom_loader_css_global')) {
	function preloader_awesome_custom_loader_css_global() {
		$preloader_awesome_loader_css_type_global = carbon_get_theme_option( 'preloader_awesome_loader_css_type_global' );

		if($preloader_awesome_loader_css_type_global == 'loader2') {
			echo ' <div id="ta-gif" class="loading-spinner-2">
			<div class="ta-css-load-2">
			<div>			</div>
			<div>			</div>
			<div>			</div>
			<div>			</div>
			</div>
			</div>';
		}

		if($preloader_awesome_loader_css_type_global == 'loader3') {
			echo '<div id="ta-gif" class="loading-spinner-3"><div class="ta-css-load-3">
			<div style="left:38px;top:38px;animation-delay:0s"></div><div style="left:80px;top:38px;animation-delay:0.125s"></div><div style="left:122px;top:38px;animation-delay:0.25s"></div><div style="left:38px;top:80px;animation-delay:0.875s"></div><div style="left:122px;top:80px;animation-delay:0.375s"></div><div style="left:38px;top:122px;animation-delay:0.75s"></div><div style="left:80px;top:122px;animation-delay:0.625s"></div><div style="left:122px;top:122px;animation-delay:0.5s"></div>
			</div></div>';
		}
		if($preloader_awesome_loader_css_type_global == 'loader5') {
			echo '<div id="ta-gif" class="loadingio-spinner-chunk-ta-css-load-5"><div class="ta-css-load-5">
			<div><div><div></div><div></div><div></div><div></div></div></div>
			</div></div>';
		}
		if($preloader_awesome_loader_css_type_global == 'loader6') {
			echo '<div id="ta-gif" class="loading-spinner-6"><div class="ta-css-load-6">
			<div></div><div></div><div></div><div></div>
			</div></div>';
		}

	}
}

if(!function_exists('preloader_awesome_custom_loader_css_page')) {
	function preloader_awesome_custom_loader_css_page() {
		global $post;
		$preloader_awesome_loader_css_type = carbon_get_post_meta( $post->ID, 'preloader_awesome_loader_css_type' );

		if($preloader_awesome_loader_css_type == 'loader2') {
			echo ' <div id="ta-gif" class="loading-spinner-2">
			<div class="ta-css-load-2">
			<div>			</div>
			<div>			</div>
			<div>			</div>
			<div>			</div>
			</div>
			</div>';
		}

		if($preloader_awesome_loader_css_type == 'loader3') {
			echo '<div id="ta-gif" class="loading-spinner-3"><div class="ta-css-load-3">
			<div style="left:38px;top:38px;animation-delay:0s"></div><div style="left:80px;top:38px;animation-delay:0.125s"></div><div style="left:122px;top:38px;animation-delay:0.25s"></div><div style="left:38px;top:80px;animation-delay:0.875s"></div><div style="left:122px;top:80px;animation-delay:0.375s"></div><div style="left:38px;top:122px;animation-delay:0.75s"></div><div style="left:80px;top:122px;animation-delay:0.625s"></div><div style="left:122px;top:122px;animation-delay:0.5s"></div>
			</div></div>';
		}
		if($preloader_awesome_loader_css_type == 'loader5') {
			echo '<div id="ta-gif" class="loadingio-spinner-chunk-ta-css-load-5"><div class="ta-css-load-5">
			<div><div><div></div><div></div><div></div><div></div></div></div>
			</div></div>';
		}
		if($preloader_awesome_loader_css_type == 'loader6') {
			echo '<div id="ta-gif" class="loading-spinner-6"><div class="ta-css-load-6">
			<div></div><div></div><div></div><div></div>
			</div></div>';
		}
	}
}