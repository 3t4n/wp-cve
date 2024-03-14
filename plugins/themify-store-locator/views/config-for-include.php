<?php
return array(
	array(
		'name' => 'themify_storelocator_address',
		'title' => __('Store Address', 'themify-store-locator'),
		'before' => '<div id="themify_sl_post_map" style="display:inline-block;width:50%;">',
		'after' => '</div><div class="themify_storelocator_map" style="float:right;"></div>',
		'type' => 'textarea',
		/* disable autocomplete to prevent Firefox from remembering input value on page refresh */
		'attr' => 'autocomplete="off"'
	),
	array(
		'name' => 'themify_storelocator_numbers',
		'title' => __('Contact Info', 'themify-store-locator'),
		'before' => '<table style="text-align:left"><thead><tr><th>Label (eg. phone, fax)</th><th>Info (eg. website, number)</th><th>Link</th></tr><tbody class="themify_sl_multi_num">',
		'after' => '</tbody></table><a href="#" id="themify_sl_add_num">+ Add</a>',
		'type' => 'textbox'
	),
	array(
		'name' => 'themify_storelocator_timing',
		'title' => __('Business Hours', 'themify-store-locator'),
		'before' => '<table style="text-align:left"><thead><tr><th>Day (eg. Mon - Sun):</th><th>Open Time:</th><th>Close Time:</th></tr><tbody class="themify_sl_multi_time">',
		'after' => '</tbody></table><a href="#" id="themify_sl_add_time">+ Add</a>',
		'type' => 'textbox'
	)
);