<?php
class authorsure_archive {
    
	static function init() {
		add_action('load-edit-tags.php', array(__CLASS__, 'load_page'));	
		add_action('edit_term', array(__CLASS__, 'save'), 10, 2 );	
	}

	static function load_page() {
		add_action( $_REQUEST['taxonomy'] . '_edit_form', array(__CLASS__, 'archive_panel'), 10, 2 );
		global $current_screen;
		if (method_exists($current_screen,'add_help_tab')) {
    		$current_screen->add_help_tab( array(
        		'id'	=> 'authorsure_instructions_tab',
        		'title'	=> __('AuthorSure Instructions'),
        		'content'	=> '<h3>AuthorSure Archive Instructions</h3>
<p>Notes on Google Authorship on archive pages</p><ul><li>Make sure you WordPress theme is not "noindexing" your category/tag pages</li>
<li>Make sure you include one or more paragraphs of keyword rich and compelling copy as the intro to the archive section: often your theme will allow 
you to do this but if not your site administrator can enable the "archive intro" which you can complete below.</li>
<li>In the intro section you can include images and videos as well as plain text</li>
<li>Authorsure will automatically add a rel-author link after any introductory section</li></ul>') );

	    	$current_screen->add_help_tab( array(
		        'id'	=> 'authorsure_help_tab',
    		    'title'	=> __('AuthorSure Archive'),
        		'content'	=> __(
'<h3>AuthorSure Archive Settings</h3><p>In the <b>AuthorSure Settings</b> section below you can specify which author you want to have feature as the
primary author for this category/tag. If enabled in AuthorSure global settings then there will also be a "Intro" that you can specify. .</p>')) );
		}
	}
		
	static function archive_panel($term, $tt_id) {
		$default_author_name = "";
		if ('publisher'==authorsure_options::get_option('archive_link')) 
			$default_author_name = "publisher";
		elseif ($default_author_id = authorsure_options::get_option('archive_author_id')) {
			$default_author = new WP_User($default_author_id);
			$default_author_name = $default_author->display_name;
        }
        
		$author = authorsure_options::get_archive_option($term->term_id,'author'); 
		
		$authors = wp_dropdown_users(array('who' => 'authors', 
			'selected' => $author, 'name' => 'authorsure_archive_options[author]', 'show_option_none' => __('(use default)'),
			'sort_column'=> 'display_name', 'echo' => 0));

		echo('<h3>AuthorSure Settings</h3>');
		if ( authorsure_options::get_option('archive_intro_enabled')) {
		    if ($intro = authorsure_options::get_archive_option($term->term_id,'intro')) 
		    	$intro = stripslashes($intro);
		    else
		    	$intro = '';
			$label = __('Archive Page Intro');
			$help = __('Supply an extended bio to go on your author page.');
			print <<< AUTHORSURE_ARCHIVE_INTRO
<table class="form-table">
<tr>
	<th><label>{$label}</label></th>
	<td><textarea name="authorsure_archive_options[intro]" rows="10" cols="60">{$intro}</textarea><br />
	<span class="description">{$help}</span></td>
</tr>
</table>
AUTHORSURE_ARCHIVE_INTRO;
		}
		$label = __('Archive Page Author');
		$help = __('Choose the author you want to appear on this archive page or leave the default setting: '.$default_author_name);
		print <<< AUTHORSURE_ARCHIVE_REL
<table class="form-table">
<tr>
	<th><label>{$label}</label></th>
	<td>{$authors}<br />
	<span class="description">{$help}</span></td>
</tr>
</table>
AUTHORSURE_ARCHIVE_REL;
    }
	
	static function save($term_id, $tt_id) {
		if (isset( $_POST['authorsure_archive_options'] ))
			return authorsure_options::save_archive_option ($term_id, (array) $_POST['authorsure_archive_options'] );
		else
			return false;
	}	

}
