<?php
class authorsure_post {
    
	static function init() {
		$author_rel = authorsure_options::get_option('author_rel');
		if (('box'==$author_rel) || ('footnote'==$author_rel)) 
			add_action( 'do_meta_boxes', array( __CLASS__, 'do_meta_boxes'), 20, 2 );
		add_action( 'save_post', array( __CLASS__, 'save'));
	}

	static function do_meta_boxes( $post_type, $context) {
		$post_types=get_post_types();
		if ( in_array($post_type, $post_types ) && 'advanced' === $context ) {
			$vars = array( 'post_type' => $post_type);
			add_meta_box( 'authorsure-visibility', 'AuthorSure Settings', array( __CLASS__, 'visibility_panel' ), $post_type, 'advanced', 'low' ,$vars);
			global $current_screen;
			if (method_exists($current_screen,'add_help_tab')) {
	    		$current_screen->add_help_tab( array(
			        'id'	=> 'authorsure_help_tab',
    			    'title'	=> __('AuthorSure Settings'),
        			'content'	=> __(
'<h3>AuthorSure Settings</h3><p>In the <b>AuthorSure Settings</b> section below you can choose whether to enable or disable AuthorSure links on this page. 
For example, you might want to disable the author links on contact, privacy statement and terms and conditions pages, and on posts with recipe microformats.</p>')) );
			}
		}
	}
		
	static function visibility_panel($post,$metabox) {
		global $post;
		$post_type = $metabox['args']['post_type'];
		switch ($post_type) {
			case 'post' : $showtime = false; break; //option is always to hide on posts	as it is present by default
			case 'page' : $showtime = authorsure_options::get_option('hide_box_on_pages') ; break;//option on page depends on default
			default: {
				$key = strtolower($post_type).'s';
				if (($custom = authorsure_options::get_option('show_box_on_custom'))
				&& is_array($custom) && array_key_exists($key,$custom) && $custom[$key])
					$showtime = false; 
				else
					$showtime = true; 
			}
		}
		$key = $showtime ? authorsure_options::get_show_author_box_key() : authorsure_options::get_hide_author_box_key();
		$toggle = get_post_meta($post->ID, $key, true);
		$author_box_toggle = $toggle?' checked="checked"':'';		
		$action = $showtime ? 'show' : 'hide'; 
		$label =  __($showtime ? 'enable author links on this page (in footnote or author box)' : 'disable author link  on this page (in footnote or author box)');
		print <<< AUTHORSURE_VISIBILITY
<p class="meta-otions"><input type="hidden" name="authorsure_toggle_action" value="{$action}" />
<label><input class="valinp" type="checkbox" name="{$key}" id="{$key}" {$author_box_toggle} value="1" />&nbsp;{$label}</label></p>
AUTHORSURE_VISIBILITY;
    }
	
	static function save($post_id) {
		if (array_key_exists('authorsure_toggle_action', $_POST)) {
			$key = 'show'==$_POST['authorsure_toggle_action'] ? authorsure_options::get_show_author_box_key() : authorsure_options::get_hide_author_box_key();	
			$val = array_key_exists($key, $_POST) ? $_POST[$key] : false;
			update_post_meta( $post_id, $key, $val );
		}
		update_post_meta( $post_id, authorsure_options::get_include_css_key(), 
			strpos(get_post_field('post_content', $post_id),'[authorsure') !== FALSE);		
	}	

}
