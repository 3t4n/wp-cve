<div style="clear: both"></div>
<div id="twl-fb-feed-container">
	<?php 
		// you can call print_r($page_vars) to see what variables/vaues are passed to this file
		if (!empty($page_vars['feeds'])): 
			foreach ($page_vars['feeds'] as $feed) {
				// extract vars from current $feed to that are called in item template
				$item_id = false;
				extract(twl_pip_get_vars($feed, $page_vars, 'facebook'));

				// if facebook post id ($item_id) is not present then something went wrong
				if (empty($item_id)) {
					continue;
				}

				// call facebook item template for current $feed
				include $page_vars['fb_feed_item_template'];
			}
		endif;
	?>
</div>
<div style="clear: both"></div>