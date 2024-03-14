<?php
/**
 * Plugin Name: Editor Full Width Gutenberg
 * Plugin URI: https://ardid.com.ar/gutenberg-full-width-editor/
 * Description: Fix the Gutenberg editor width to full size
 * Author: Anibal Ardid
 * Author URI: https://ardid.com.ar
 * Version: 1.0.5
 * Text Domain: editorfullwidthgutenberg
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Editor Full Width Gutenberg
 */

/**
 * Add inline css editor width
 */
add_action('admin_head', 'editor_full_width_gutenberg');

function editor_full_width_gutenberg() {
  echo '<style>
    body.gutenberg-editor-page .editor-post-title__block, body.gutenberg-editor-page .editor-default-block-appender, body.gutenberg-editor-page .editor-block-list__block {
		max-width: none !important;
	}
    .block-editor__container .wp-block {
        max-width: none !important;
    }
    /*code editor*/
    .edit-post-text-editor__body {
    	max-width: none !important;	
    	margin-left: 2%;
    	margin-right: 2%;
    }
  </style>';
}
