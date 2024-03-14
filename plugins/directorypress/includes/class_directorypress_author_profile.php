<?php
class DirectoryPress_ProfilePage {
	function __construct() {
		add_filter( 'init',array($this,'wr_init'));
		add_filter( 'query_vars', array($this,'rewrite_add_var') );
		add_filter( 'template_include', array($this, 'template'), 99 );
	}
	
	function rewrite_add_var( $vars )
	{
		$vars[] = 'profile';
		return $vars;
	}
	
	function wr_init(){
		add_rewrite_tag( '%profile/%', '([^&]+)' );
		add_rewrite_rule(
			'^profile//([^/]*)/?',
			'index.php?profile/=$matches[1]',
			'top'
		);
	}
	
	function template( $template ) {
		if(is_author() &&  get_query_var('profile')){
			$new_template = directorypress_display_template('partials/templates/template-author.php');
			return $new_template;
		}

		return $template;

	}
}
