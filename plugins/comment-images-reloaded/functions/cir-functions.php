<?php

class CIR_Functions{


	public function support_comment_list() {

		$themesupport = get_theme_support( 'html5' );
		$themesupport = ( is_array($themesupport[0]) ) ? $themesupport[0] : $themesupport;

		$support_comment_list = ( is_array($themesupport) && in_array('comment-list', $themesupport) )
			? true : false;

		// add fix for xhtml comments
		if ( false === $support_comment_list ) {
			add_action('comment_text', array( $this, 'get_html5_comment_content' ) );
		}
	}

	/**
	 * This function sets the comments_array working fine
	 *
	 * @param 	string 	$comment_text
	 * @return 	string 	comment text with comment images
	 *
	 */
	function get_html5_comment_content( $comment_text ){

		$cid = intval(get_comment_ID());
		if ( is_numeric($cid) ) {
			$new_commtext = get_comment_text();
			preg_match_all( '%(<p[^>]*class=["|\']comment-image-reloaded["|\'][^>]*>)(.*?)(<\/p>)%', $new_commtext, $matches_in_new );
			preg_match_all( '%(<p[^>]*class=["|\']comment-image-reloaded["|\'][^>]*>)(.*?)(<\/p>)%', $comment_text, $matches_in_old );
// echo '<pre>';
// var_dump($matches_in_new[0]);
// echo '<hr>';
//var_dump($matches_in_old);
// echo '</pre>';

			// if in filtered contentent image not exists and it exists in get_comment_text()
			if ( $this->is_empty_array($matches_in_old) && !empty($matches_in_new) ) {
				foreach($matches_in_new[0] as $new_match){
					//var_dump($comment_text);
					$comment_text .= $new_match;
				}
				// print_r($matches_in_new);
			}
		}

		return $comment_text;

	}


	private function is_empty_array($old){
		foreach($old as $o){
			if(!empty($o)){
				return false;
			}
		}
		return true;
	}





}