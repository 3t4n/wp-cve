<?php

if( ! function_exists('bizcor_header_topbar_section') ){
	function bizcor_header_topbar_section(){
		bc_bizcor_get_template_part('template-parts/header/section','topbar');
	}
	add_action('bizcor_header_navigation','bizcor_header_topbar_section', 5 );
}