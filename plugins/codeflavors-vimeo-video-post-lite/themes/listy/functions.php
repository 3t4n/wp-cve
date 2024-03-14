<?php

namespace Vimeotheque\Theme\Listy;

/**
 * Display the post content.
 *
 * @private
 * @return void
 * @ignore
 */
function the_video_content() {

	add_filter(
		'vimeotheque\post_content_embed',
		'__return_false',
		9870234
	);

	the_content();

	remove_filter(
		'vimeotheque\post_content_embed',
		'__return_false',
		9870234
	);

}