<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 20 Apr 2011
 *
 * @file WordpressConnectEditorButtons.php
 *
 * This class provides functionality for adding wordpress connect
 * buttons (for shortcodes) to the editor
 */
class WordpressConnectEditorButtons {

	/**
	 * Creates a new instance of the WordpressConnectEditorButtons object
	 */
	public function WordpressConnectEditorButtons(){

		add_action( 'media_buttons', array( &$this, 'addButtons' ) );

	}

	/**
	 * Adds wordpress connect buttons to the editor
	 */
	function addButtons(){

		$editor_url = WP_PLUGIN_URL . '/wordpress-connect/editor/';

		$like_text =  __( 'Add Like Button', WPC_TEXT_DOMAIN );
		$comments_text =  __( 'Add Comments', WPC_TEXT_DOMAIN );

		_e( 'Wordpress Connect', WPC_TEXT_DOMAIN );

		global $post_ID;

		$general_options = get_option( WPC_OPTIONS );
		$colorscheme = $options[ WPC_OPTIONS_THEME ];

		$comments_options = get_option( WPC_OPTIONS_COMMENTS );
		$number_of_posts = $comments_options[ WPC_OPTIONS_COMMENTS_NUMBER ];
		$comments_width = $comments_options[ WPC_OPTIONS_COMMENTS_NUMBER ];

		$like_options = get_option( WPC_OPTIONS_LIKE_BUTTON );

		$send_button = $like_option[ WPC_OPTIONS_LIKE_BUTTON_SEND ];
		$layout = $like_option[ WPC_OPTIONS_LIKE_BUTTON_LAYOUT ];
		$width = $like_option[ WPC_OPTIONS_LIKE_BUTTON_WIDTH ];
		$show_faces = $like_option[ WPC_OPTIONS_LIKE_BUTTON_FACES ];
		$verb = $like_option[ WPC_OPTIONS_LIKE_BUTTON_VERB ];
		$font = $like_option[ WPC_OPTIONS_LIKE_BUTTON_FONT ];

		$comments_settings = sprintf( 'number_of_posts=%s&038;width=%s&038;colorscheme=%s', $number_of_posts, $comments_width, $colorscheme );
		$like_settings = sprintf( 'number_of_posts=%s&038;width=%s&038;colorscheme=%s', $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font );

		?> <a href="<?php echo $editor_url; ?>like.php?post_id=<?php echo $post_ID?>&#038;TB_iframe=1&#038;=<?php echo $like_settings; ?>" id="add_like_button" class="thickbox" style="padding: 0 0 0 5px;"><img src="<?php echo $editor_url; ?>editor-icon-like.png" alt="<?php echo $like_text; ?>" title="<?php echo $like_text; ?>" onclick="return false;" style="margin-top: -10px;" /></a><?php
		?> <a href="<?php echo $editor_url; ?>comments.php?post_id=<?php echo $post_ID?>&#038;TB_iframe=1&#038;=<?php echo $comments_settings; ?>" id="add_comments" class="thickbox" style="padding: 0 10px 0 0;"><img src="<?php echo $editor_url; ?>editor-icon-comments.png" alt="<?php echo $comments_text; ?>" title="<?php echo $comments_text; ?>" onclick="return false;" style="margin-top: -10px;" /></a><?php
		?> <?php
	}

}

?>
