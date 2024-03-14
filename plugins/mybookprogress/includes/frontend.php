<?php

function mbp_init_frontend() {
	add_action('wp_head', 'mbp_enable_frontend_ajax');
	add_action('wp_enqueue_scripts', 'mbp_enqueue_frontend_resources');
	add_action('wp_enqueue_scripts', 'mbp_enqueue_stylepack_resources', 20);
	add_action('wp_ajax_mbp_email_subscribe', 'mbp_email_subscribe_callback');
	add_action('wp_ajax_nopriv_mbp_submit_simple_subscribe_form', 'mbp_submit_simple_subscribe_form_callback');
	add_action('wp_ajax_mbp_submit_simple_subscribe_form', 'mbp_submit_simple_subscribe_form_callback');
	add_image_size('mbp-cover-image', 200, 9999, false);
}
add_action('mbp_init', 'mbp_init_frontend');

function mbp_verify_subscribe_enabled() {
	if(mbp_get_setting('mailinglist_type') == 'mailchimp') {
		$mailchimp_apikey = mbp_get_setting('mailchimp_apikey');
		$mailchimp_list = mbp_get_setting('mailchimp_list');
		$mailchimp_subscribe_url = mbp_get_setting('mailchimp_subscribe_url');
		if(!empty($mailchimp_apikey) and !empty($mailchimp_list) and !empty($mailchimp_subscribe_url)) {
			return true;
		}
	} else if(mbp_get_setting('mailinglist_type') == 'other') {
		$subscribe_url = mbp_get_setting('other_subscribe_url');
		if(!empty($subscribe_url)) {
			return true;
		}
	}
	return false;
}

function mbp_frontend_wrapper($input, $options) {
	$options = wp_parse_args($options, array(
		'showsubscribe' => true,
		'simplesubscribe' => true,
		'location' => '',
	));

	$output = '<div class="mbp-container">';

	$output .= $input;

	if($options['showsubscribe']) {
		if(mbp_get_setting('mailinglist_type') == 'mailchimp') {
			$mailchimp_apikey = mbp_get_setting('mailchimp_apikey');
			$mailchimp_list = mbp_get_setting('mailchimp_list');
			$mailchimp_subscribe_url = mbp_get_setting('mailchimp_subscribe_url');
			if(!empty($mailchimp_apikey) and !empty($mailchimp_list) and !empty($mailchimp_subscribe_url)) {
				$output .= '<div class="mbp-subscribe-container">';
				$output .= '<a class="mbp-subscribe" href="'.$mailchimp_subscribe_url.'" target="_blank"'.($options['simplesubscribe'] ? ' onclick="return mybookprogress.simple_subscribe_form(this);"' : '').'>'.__('Get Book Updates', 'mybookprogress').'</a>';
				$output .= '</div>';
			}
		} else if(mbp_get_setting('mailinglist_type') == 'other') {
			$subscribe_url = mbp_get_setting('other_subscribe_url');
			if(!empty($subscribe_url)) {
				$output .= '<div class="mbp-subscribe-container">';
				$output .= '<a class="mbp-subscribe" href="'.$subscribe_url.'" target="_blank">'.__('Get Book Updates', 'mybookprogress').'</a>';
				$output .= '</div>';
			}
		}
	}

	if(mbp_get_setting('enable_linkback')) { $output .= '<div class="mbp-linkback-container"><a class="mbp-linkback" href="http://www.mybookprogress.com">'.__('MyBookProgress by Author Media', 'mybookprogress').'</a></div>'; }

	$output .= '</div>';

	return $output;
}

function mbp_format_books_progress($options = '', $books = null) {
	$options = wp_parse_args($options, array(
		'showsubscribe' => true,
		'simplesubscribe' => true,
		'location' => '',
		'include_wrapper' => true,
	));

	$output = '<div class="mbp-books">';
	if($books === null) { $books = mbp_get_books(); }
	if(!empty($books)) {
		$book_options = array_merge($options, array('include_wrapper' => false));
		foreach($books as $book) {
			$output .= mbp_format_book_progress($book, $book_options);
		}
	} else {
		$output .= '<div class="mbp-no-books">';
		$output .= __('No books currently in progress. Come back soon for updates!', 'mybookprogress');
		$output .= '</div>';
	}
	$output .= '</div>';

	if($options['include_wrapper']) { $output = mbp_frontend_wrapper($output, $options); }

	return apply_filters('mbp_format_books_progress', $output, $options, $books);
}

function mbp_format_book_buttons($book) {
	$book = mbp_get_book($book);
	$buttons = apply_filters('mbp_get_book_buttons', array(), $book);
	$output = '';

	foreach($buttons as $slug => $button) {
		$class = 'mbp-book-button mbp-book-button-'.$slug;
		if(!empty($button['attrs']['class'])) { $class .= ' '.$button['attrs']['class']; }
		$attrs = '';
		if(!empty($button['attrs']) and is_array($button['attrs'])) {
			foreach($button['attrs'] as $key => $value) {
				if($key == 'class') { continue; }
				$attrs .= ' '.$key.'="'.$value.'"';
			}
		}
		$content = !empty($button['content']) ? $button['content'] : '';
		$output .= '<div class="'.$class.'"'.$attrs.'>'.$content.'</div>';
	}

	return $output;
}

function mbp_format_book_progress($book, $options = '', $progress_data = null) {
	$book = mbp_get_book($book);
	if(!$book) { return ''; }

	$options = wp_parse_args($options, array(
		'location' => '',
		'show_image' => true,
		'show_buttons' => true,
		'include_wrapper' => true,
	));

	$show_image = ($book['display_cover_image'] and $options['show_image']) ? true : false;

	if(!$progress_data) { $progress_data = mbp_get_book_current_progress_data($book); }
	$progress = $progress_data['progress'];
	$phase_name = $progress_data['phase_name'];
	$deadline = $progress_data['deadline'];

	$progress = max(0, min(1, $progress));
	if($progress > 0 and $progress < 0.001) { $progress = 0.001; }
	$progress = number_format($progress*100, 1);
	if(substr($progress, strlen($progress)-2) == '.0') { $progress = substr($progress, 0, strlen($progress)-2); }

	$output = '<div class="mbp-book" data-book-id="'.$book['id'].'">';
	if($show_image) {
		$output .= '<div class="mbp-book-wrap">';
		$output .= '<div class="mbp-book-image">'.wp_get_attachment_image($book['display_cover_image'], 'mbp-cover-image').'</div>';
		$output .= '<div class="mbp-book-content">';
	}
	$output .= '<div class="mbp-book-title">';
	if($options['show_buttons']) {
		$output .= '<div class="mbp-book-buttons">';
		$output .= mbp_format_book_buttons($book);
		$output .= '</div>';
	}
	$output .= mbp_get_book_title($book);
	$output .= '</div>';
	$metas = '';
	if($phase_name) {
		$metas .= '<div class="mbp-book-meta mbp-book-phase"><span class="mbp-book-meta-label">'.__('Phase', 'mybookprogress').':</span>'.$phase_name.'</div>';
	}
	if($deadline) {
		$time = human_time_diff(current_time('timestamp'), intval($deadline));
		$past = current_time('timestamp') > intval($deadline);
		if($past) { $time .= ' '.__('ago', 'mybookprogress'); }
		$metas .= '<div class="mbp-book-meta mbp-book-duedate '.($past ? 'mbp-book-duedate-past' : '').'"><span class="mbp-book-meta-label">'.__('Due', 'mybookprogress').':</span>'.$time.'</div>';
	}
	if(!empty($metas)) { $output .= '<div class="mbp-book-metas">'.$metas.'</div>'; }
	$output .= '<div class="mbp-book-progress">';
	$output .= '<div class="mbp-book-progress-barbg">';
	$output .= '<div class="mbp-book-progress-bar" style="background-color:#'.$book['display_bar_color'].';width:'.$progress.'%;">';
	$output .= '<div class="mbp-book-progress-label">'.$progress.'%</div>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
	if($show_image) {
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div style="clear:both"></div>';
	}
	$output .= '</div>';

	if($options['include_wrapper']) { $output = mbp_frontend_wrapper($output, $options); }

	return apply_filters('mbp_format_book_progress', $output, $book, $options, $progress);
}

function mbp_enable_frontend_ajax() {
?>
	<script type="text/javascript">
		window.ajaxurl = "<?php echo(admin_url('admin-ajax.php')); ?>";
	</script>
<?php
}

function mbp_enqueue_frontend_resources() {
	wp_enqueue_script('mbp-frontend-script', plugins_url('js/frontend.js', dirname(__FILE__)), array('jquery'), MBP_VERSION);
	wp_enqueue_style('mbp-frontend-style', plugins_url('css/frontend.css', dirname(__FILE__)), array(), MBP_VERSION);
}

function mbp_enqueue_stylepack_resources() {
	$style_pack = mbp_get_current_style_pack();
	if(!empty($style_pack) and !empty($style_pack['style_dir_url'])) {
		wp_enqueue_style('mbp-style-pack-css', $style_pack['style_dir_url'].'/style.css', array('mbp-frontend-style'), MBP_VERSION.'.'.$style_pack['version']);
		if(file_exists($style_pack['style_dir'].'/style.js')) { wp_enqueue_script('mbp-style-pack-js', $style_pack['style_dir_url'].'/style.js', array('jquery'), MBP_VERSION.'.'.$style_pack['version']); }
	}
}

function mbp_submit_simple_subscribe_form_callback() {
	if(mbp_get_setting('mailinglist_type') == 'mailchimp') {
		$mailchimp_apikey = mbp_get_setting('mailchimp_apikey');
		$mailchimp_list = mbp_get_setting('mailchimp_list');
		if(empty($mailchimp_apikey) or empty($mailchimp_list)) { die(); }
		$response = mbp_do_mailchimp_subscribe($mailchimp_apikey, $mailchimp_list, $_POST['email']);
		if(!empty($response) and isset($response->error) and $response->code === 214) {
			_e('You\'re already subscribed!', 'mybookprogress');
		} else if(empty($response) or (!empty($response) and isset($response->error))) {
			_e('Sorry, something went wrong while trying to subscribe you. Please try again.', 'mybookprogress');
		} else {
			_e('Subscribed - look for the confirmation email!', 'mybookprogress');
		}
	} else {
		_e('Mailing list error!', 'mybookprogress');
	}
	die();
}
