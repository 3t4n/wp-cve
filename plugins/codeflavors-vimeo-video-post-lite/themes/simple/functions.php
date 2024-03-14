<?php
/**
 * @author  CodeFlavors
 */

namespace Vimeotheque\Theme\Simple;

use Vimeotheque\Themes\Helper;

/**
 * Outputs the post thumbnail image.
 *
 * @private
 * @ignore
 */
function the_post_thumbnail() {

	if ( has_post_thumbnail() ) {
		\the_post_thumbnail();
	} else {
		printf(
			'<img src="%s" />',
			end( Helper::current_video_post()->thumbnails )
		);
	}
}