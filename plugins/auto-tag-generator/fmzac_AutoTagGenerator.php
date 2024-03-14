<?php
/*
Plugin Name: Auto Tags Generator
Plugin URI: http://fmzac.blogspot.com/2013/10/auto-tag-generator.html
Description: Automatically creates tags from post title, on update or publish.
Version: 1.0
Author: fmzac
Author URI: http://fmzac.blogspot.com/
*/

/* Copyright 2013  Faisal Najeeb  (email : fmzac2012@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
  

new fmzac_AutoTagGenerator;

class fmzac_AutoTagGenerator {	
	
	// List of WP-specific words (draft, etc)
	private $wp_stop	= array('draft', 'auto');
	
	// Self-referencing constructor method method:
	function fmzac_AutoTagGenerator() {
		$this->__construct();
	}
	
	// Get out there and rock and roll the bones:
	function __construct() {
		add_action('admin_menu', array($this, 'fmzac_addMenu'));
		add_action('admin_init', array($this, 'fmzac_page_init'));
		add_action('save_post', array($this, 'fmzac_convert_titles_to_tags'));
	}
		

	// add menu under Settings
	function fmzac_addMenu() {
		add_options_page( 'Auto Tag Generator', 'Auto Tag Generator', 'manage_options', 'fmzac-auto-tag-generator', array($this, 'fmzac_option_page') );		
	}
	
	//register Field names:
	function fmzac_page_init() {
		register_setting( $option_group = 'fmzacAutoTagGenerator', $option_name = 'fmzac_ignore_words' );

	}
	
	// Display options page:
	function fmzac_option_page() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		?>
		
        <div class="wrap">
        <h2>Auto Tag Generator</h2>
        
        <form method="post" action="options.php">
        	<?php 
				//call setting fields
				settings_fields( 'fmzacAutoTagGenerator' ); 
				do_settings_fields( 'fmzacAutoTagGenerator' );
			?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Words to ignore:</th>
                    <td><textarea name="fmzac_ignore_words" id="fmzac_ignore_words" cols="120" rows="8" ><?php echo get_option('fmzac_ignore_words'); ?></textarea></td>
                </tr>
            

            </table>
            
            <?php submit_button(); ?>
        
        </form>
		<br/><br/>
        Your support can help us in making more free components. Even a single Dollor can count us big. <br/>
        Click "Donate" button for contributing your part.<br/>
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5MYLR7SCU5L5E" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate" /></a>
        <br/>
        Thanks for your love. <br/>        
        </div>
        
		<?php
		
	}	
	
	
	// Convert titles to tags on save:
	function fmzac_convert_titles_to_tags($post_id) {		
		//for avoiding infinite loop recomended by codex
		$post	= get_post(wp_is_post_revision($post_id));
		
		// No title? No point in going any further:
		if(isset($post->post_title)) :
			$title	= $post->post_title;
			// Only run if there are not already tags assigned to the post:
			if(!wp_get_post_tags($post_id)) :
				// Setup our tag data:
				$title_to_tags	= array();
				$wordstoignore		= $this->fmzac_getWordsToIgnore();
				$title_werdz	= explode(' ', $title);
				foreach ($title_werdz as $werd) :
					$werd = $this->fmzac_lowerNoPunc($werd);
					if(!in_array($werd, $wordstoignore) && !in_array($werd, $this->wp_stop)) :
						$title_to_tags[] = $werd;
					endif;
				endforeach;
				// Finally, add the tags to the post
				wp_add_post_tags($post_id, $title_to_tags);
			endif;
		endif;

	}


	//load ignore word
	function fmzac_IgnoreWords() {
		$values	= get_option('fmzac_ignore_words');
		if(strlen($values) < 1) :
			$values	= implode(', ', $this->fmzac_getWordsToIgnore());
		endif;
		echo '
		<p>These words will be ignored when generating tags from title (punctuation removed). <em>To reset, simply delete all values here and the default list will be restored.</em></p>
		<textarea rows="6" cols="100" name="fmzac_ignore_words" id="fmzac_ignore_words">' . $values . '</textarea>
		';
	}
	
	// Gets the ignore word list:
	private function fmzac_getWordsToIgnore() {
		$vals			= array();
		$file 			= dirname(__FILE__).'/fmzac_ignore_words.txt';
		$wordstoignore		= explode(',', file_get_contents($file));
		foreach($wordstoignore as $word) :
			$vals[]	= $this->fmzac_lowerNoPunc($word);
		endforeach;
		return $vals;
	}
	
	// Converts all words into lower-case words, sans punctuation or possessives.
	private function fmzac_lowerNoPunc($werd) {
		$werd = strtolower(trim(preg_replace('#[^\p{L}\p{N}]+#u', '', $werd)));
		return $werd;
	}

}
?>
