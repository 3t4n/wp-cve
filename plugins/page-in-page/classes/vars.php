<?php

class TWL_Page_In_Page_Vars {

	public static function facebookFeedItem($feed, $params = array()) {
		$vars = array();
		if (!is_array($feed)) {
			return $vars;
		}

		// gather vars for feed item template
		$vars['fb_logo'] = plugins_url('inc/fb_logo.jpg', dirname(__FILE__));
		$vars['item_page_name'] = $feed['from']['name'];
		$vars['item_page_link'] = '//www.facebook.com/' . $params['page_id'];
		$vars['item_page_logo'] = '//graph.facebook.com/'.$params['page_id'].'/picture';
		$vars['item_type'] = $feed['type'];
		$vars['item_date'] = date('D, j M Y \a\t H:i:s', strtotime($feed['created_time']));
		$vars['item_message'] = !empty($feed['message']) ? str_replace("\n", "<br />", $feed['message']) : '';
		$vars['item_picture'] = !empty($feed['picture']) ? $feed['picture'] : '';
		$vars['item_link_name'] = !empty($feed['name']) ? $feed['name'] : '';
		$vars['item_link_link'] = !empty($feed['link']) ? $feed['link'] : '';
		$vars['item_link_caption'] = !empty($feed['caption']) ? $feed['caption'] : '';
		$vars['item_link_description'] = !empty($feed['description']) ? $feed['description'] : '';
		$vars['item_id'] = $feed['id'];
		$vars['item_fb_link'] = '';

		$item_fb_link_parts = explode('_', $vars['item_id']);
		if (!empty($item_fb_link_parts[1])) {
			$vars['item_fb_link'] = '//www.facebook.com/' . $item_fb_link_parts[0] . '/posts/' . $item_fb_link_parts[1];
		}

		if (!$vars['item_link_name']) {
			$vars['item_link_name'] = $vars['item_page_name'];
		}

		if (!$vars['item_link_caption']) {
			$vars['item_link_caption'] = $vars['item_page_name'];
		}

		if (!$vars['item_link_link']) {
			$vars['item_link_link'] = $vars['item_fb_link'];
		}

		if (!$vars['item_message']) {
			$vars['item_message'] = !empty($feed['story']) ? $feed['story'] : '';
		}

		return $vars;
	}

	public static function twitterFeedItem($feed, $params = array()) {

		$vars = array();
		if (!is_array($feed)) {
			return $vars;
		}

		$time = strtotime($feed['created_at']);
		$vars['twitter_logo'] = plugins_url('inc/twitter_logo.png', dirname(__FILE__));
		$vars['item_user_page_url'] = '//twitter.com/' . $feed['user']['screen_name'];
		$vars['item_avatar'] = str_replace('http:', '', $feed['user']['profile_image_url']);
		$vars['item_name'] = $feed['user']['name'];
		$vars['item_username'] = '@' . $feed['user']['screen_name'];
		$vars['item_status_link'] = '//twitter.com/' . $feed['user']['screen_name'] .'/status/' . $feed['id_str'];
		$vars['item_short_date'] = date('d M', $time);
		$vars['item_long_date'] = date('h:i A - d M y', $time);
		$vars['item_message'] = $feed['text'];
		$vars['item_id'] = $feed['id_str'];
				
		return $vars;
	}

}
