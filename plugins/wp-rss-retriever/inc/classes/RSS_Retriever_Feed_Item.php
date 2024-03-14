<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class RSS_Retriever_Feed_Item {	
	public $title 		= ''; // title
	public $permalink 	= ''; // url of post
	public $date 		= ''; // date
	public $content 	= ''; // excerpt
	public $thumbnail 	= ''; // image src url
	public $source 		= ''; // feed source title


	function __construct($item, $settings) {
		if(!is_object($item)) {
			wp_rss_retriever_error("Unable to construct " . get_class($this) . " with variable type: " . gettype($item));
		} else if (!empty($item)) {
			$this->get_data_from_feed_object($item, $settings);
		} else {
			wp_rss_retriever_error("Not enough data.");
		}
	}

	// construct all of the pertinent item data
	private function get_data_from_feed_object($item, $settings) {
		$this->title 		= $item->get_title();
		$this->permalink 	= $item->get_permalink();
		$this->date 		= $this->convert_timezone(@$item->get_date()); // suppress "non-numeric value warning for Date"
		$this->content 		= $this->format_content($item->get_description(), $settings['excerpt']);
		$this->thumbnail 	= $this->get_thumbnail($item);
		$this->source 		= $this->get_source($item);
	}

	private function get_source($item) {
		return $item->get_feed()->get_title();
	}

	private function get_thumbnail($item) {
		$enclosure = $item->get_enclosure();
		$content = $item->get_content();

		if ($enclosure->get_thumbnail()) {
			return $enclosure->get_thumbnail();
		} elseif ($this->get_first_image($content)) {
			return $this->get_first_image($content);
		} elseif ($this->get_image_tag_data($item)) {
			return $this->get_image_tag_data($item);
		// special case for itunes:image
		} elseif (isset($item->data['child'][SIMPLEPIE_NAMESPACE_ITUNES]['image'][0]['attribs']['']['href'])) {
			return $item->data['child'][SIMPLEPIE_NAMESPACE_ITUNES]['image'][0]['attribs']['']['href'];
		} elseif ($enclosure->get_link()) {
			return $enclosure->get_link();
		} else {
			return null;
		}
	}

	private function get_image_tag_data($item) {
		$image = $item->get_item_tags('', 'image');

		if (isset($image[0]['data'])) {
			return $image[0]['data'];
		} else {
			return false;
		}
	}

	private function get_first_image($content) {
		require_once(plugin_dir_path( __FILE__ ) . 'SimpleHTML.php');
		$post_html = wp_rss_retriever_str_get_html($content);
		if ($post_html) {
			$first_img = $post_html->find('img', 0);
			if($first_img !== null) {
				return $first_img->src;
			}
		}
		return null;
	}

	private function format_content($content, $excerpt_length) {
		$content_no_tags = wp_strip_all_tags($content);
		if ($excerpt_length > 0) {
			return wp_trim_words($content_no_tags, $excerpt_length);
		} else {
			return $content_no_tags;
		}
	}


	private function convert_timezone($timestamp) {
	    $date = new DateTime($timestamp);

	    // Timezone string set (ie: America/New York)
	    if (get_option('timezone_string')) {
	        $timezone = get_option('timezone_string');
	    // GMT offset string set (ie: -5). Convert value to timezone string
	    } elseif (get_option('gmt_offset')) {
	        $timezone = timezone_name_from_abbr('', get_option('gmt_offset') * 3600, 0 );
	    } else {
	        $timezone = 'GMT';
	    }

	    try {
	        $date->setTimezone(new DateTimeZone($timezone)); 
	    } catch (Exception $e) {
	        $date->setTimezone(new DateTimeZone('GMT')); 
	    }

	    return date_i18n(get_option('date_format') .' - ' . get_option('time_format'), strtotime($date->format('Y-m-d H:i:s')));
	}
}