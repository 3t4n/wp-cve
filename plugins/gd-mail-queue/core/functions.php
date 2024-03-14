<?php

if (!defined('ABSPATH')) { exit; }

function gdmaq_htmlfy_content($args = array(), $atts = array()) {
    $defaults = array(
        'subject' => '',
        'plain' => ''
    );

    $args = wp_parse_args($args, $defaults);

    return gdmaq_htmlfy()->htmlfy_content($args['plain'], $args['subject'], $atts);
}

function gdmaq_default_from() : array {
    $sitename = strtolower( $_SERVER['SERVER_NAME'] );

    if (substr( $sitename, 0, 4 ) == 'www.') {
        $sitename = substr($sitename, 4);
    }

    return array(
        'email' => 'wordpress@'.$sitename,
        'name' => get_option('blogname'));
}

function gdmaq_flat_email_from_array($in) : array {
    $out = array();

    foreach ($in as $email) {
        $out[] = $email[0].(!empty($email[1]) ? ' <'.$email[1].'>' : '');
    }

    return $out;
}

function gdmaq_normalize_email($in) : array {
    $con = array('email' => '', 'name' => '');

    if (is_string($in)) {
        $con['email'] = $in;
    } else if (is_array($in)) {
        $con['email'] = isset($in['email']) ? $in['email'] : $in[0];
        $con['name'] = isset($in['name']) ? $in['name'] : $in[1];
    }

    return $con;
}

function gdmaq_mail_to_queue($args = array()) : int {
	$defaults = array(
		'to' => array(),
		'from' => array(),
		'subject' => '',
		'plain' => '',
		'html' => '',
		'type' => 'mail',
		'headers' => array(),
		'attachments' => array(),
		'extras' => array()
	);

	$args = wp_parse_args($args, $defaults);

	if ( is_string($args['to']) ) {
		$args['to'] = explode(',', $args['to']);
	}

	$args = apply_filters('gdmaq_mail_to_queue_args', $args);

	$args['extras'] = (object)$args['extras'];

	if (empty($args['from']) && isset($args['extras']->From) && !empty($args['extras']->From)) {
		$args['from'] = array(
			'email' => $args['extras']->From
		);

		if (isset($args['extras']->FromName) && !empty($args['extras']->FromName)) {
			$args['from']['name'] = $args['extras']->FromName;
		}
	} else {
		if (empty($args['from'])) {
			$args['from'] = gdmaq_mailer()->get_from();
		}

		$args['extras']->From = $args['from']['email'];

		if (!empty($args['from']['name'])) {
			$args['extras']->FromName = $args['from']['name'];
		}
	}

	if (!isset($args['extras']->ContentType)) {
		$args['extras']->ContentType = empty($args['html']) ? 'text/plain' : 'text/html';
	}

	if (!isset($args['extras']->CharSet)) {
		$args['extras']->CharSet = 'UTF-8';
	}

	if (apply_filters('gdmaq_mail_to_queue_content_processing', true)) {
		$args['subject'] = wp_kses($args['subject'], 'strip');
		$args['plain'] = gdmaq_process_plain_content_for_html($args['plain']);
	}

	if (!empty($args['html']) && apply_filters('gdmaq_mail_to_queue_html_processing', false)) {
		$args['html'] = gdmaq_kses_max_allowed($args['html']);
	}

	$item = $args;
	$item['extras'] = json_encode($item['extras']);
	$item['headers'] = json_encode($item['headers']);
	$item['attachments'] = json_encode($item['attachments']);

	unset($item['to'], $item['from']);

	$added = 0;
	foreach ($args['to'] as $to) {
		$_em = gdmaq_normalize_email($to);

		$item['to_email'] = $_em['email'];
		$item['to_name'] = $_em['name'];

		if (!empty($item['to_email'])) {
			gdmaq_db()->add_mail_to_queue($item);

			$added++;
		}
	}

	gdmaq_settings()->update_statistics('mail_to_queue_calls', 1);
	gdmaq_settings()->update_statistics('mails_added_to_queue', $added);

	gdmaq_settings()->update_statistics_for_type($item['type'], 'mail_to_queue_calls', 1);
	gdmaq_settings()->update_statistics_for_type($item['type'], 'mails_added_to_queue', $added);

	gdmaq_settings()->save('statistics');

	return $added;
}

function gdmaq_allowed_tags_iframe_display() : array {
	return array_merge(d4p_kses_expanded_list_of_tags(), array(
		'html' => array(
			'class' => true,
			'lang' => true
		),
		'head' => array(),
		'title' => array(),
		'link' => array(
			'rel' => true,
			'href' => true,
			'media' => true
		),
		'style' => array(
			'type' => true,
			'media' => true
		),
		'meta' => array(
			'property' => true,
			'name' => true,
			'content' => true,
			'http-equiv' => true,
			'charset' => true
		),
		'body' => array(
			'class' => true,
			'style' => true
		),
	));
}

function gdmaq_kses_strip_everything() : array {
	return array(
		'a' => array(
			'href' => true
		),
		'br' => array()
	);
}

function gdmaq_process_plain_content_for_html($text, $preprocess = null) : string {
	if (!is_string($text)) {
		return '';
	}

	if (!gdmaq_has_html($text)) {
		return $text;
	}

	$preprocess = $preprocess ?? gdmaq_settings()->get('preprocess', 'htmlfy');

	$context = apply_filters( 'gdmaq_plain_text_strip_html', gdmaq_kses_strip_everything() );

	switch ($preprocess) {
		case 'kses_post':
			$context = 'post';
			break;
		case 'kses_basic':
			$context = 'user_description';
			break;
		case 'kses_expanded':
			$context = apply_filters( 'gdmaq_plain_text_preprocess_html', d4p_kses_expanded_list_of_tags() );
			break;
	}

	return wp_kses($text, $context);
}

function gdmaq_has_html($text) : bool {
	$result = preg_match('/<\s?[^\>]*\/?\s?>/i', $text);

	return $result === 1;
}

function gdmaq_kses_max_allowed($text) : string {
	return wp_kses($text, gdmaq_allowed_tags_iframe_display());
}

function gdmaq_load_phpmailer() {
	if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
		require_once( ABSPATH . WPINC . '/PHPMailer/PHPMailer.php' );
		require_once( ABSPATH . WPINC . '/PHPMailer/SMTP.php' );
		require_once( ABSPATH . WPINC . '/PHPMailer/Exception.php' );
	}
}
