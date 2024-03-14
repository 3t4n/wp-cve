<?php

class FFWDModelBlog_style extends FFWDModelMain {

  public function __construct() {
  }

  /**
  * @param $max_limit int maximum number of posts
  */
  public function get_ffwd_data($id, $objects_per_page, $sort_by, $ffwd, $max_limit, $sort_direction = ' DESC ') {
    global $wpdb;
	  $sort_direction=' DESC ';
    if (isset($_REQUEST['page_number_' . $ffwd]) && $_REQUEST['page_number_' . $ffwd]) {
      $limit = ((int) $_REQUEST['page_number_' . $ffwd] - 1) * $objects_per_page;
    }
    else {
      $limit = 0;
    }
    $objects_per_page = (($limit+$objects_per_page) < $max_limit) ? $objects_per_page : ($max_limit-$limit);

    if ($objects_per_page) {
      $limit_str = 'LIMIT ' . $limit . ',' . $objects_per_page;
    }
    else {
      $limit_str = '';
    }

    $fb_info=$wpdb->get_row($wpdb->prepare('SELECT content,upcoming_events,event_order FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', $id));

	 $events_str='';
	 if($fb_info->content=='events' && $fb_info->upcoming_events==1)
	 {
		 $events_str=' AND created_time_number>='.strtotime(date('Y-m-d'));
	 }


	 if($fb_info->content=='events' && $fb_info->event_order==1)
	 {
		 $sort_direction=' ASC ';
	 }




    $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" '.$events_str.' ORDER BY created_time   '.$sort_direction . $limit_str, $id));
		// Store ids ans object_ids
		$id_object_id_json = array();
		foreach($results as $row) {
			$attachments=json_decode(str_replace("'", esc_html("'"), $row->attachments));

			$object = new stdClass();
			$object->id = $row->id;
			$object->type = $row->type;
			$object->object_id = $row->object_id;
			$object->from = $row->from;
			$object->story = str_replace("'", esc_html("'"), $row->story);
			$object->place = json_decode(str_replace("'", esc_html("'"), $row->place));
			$object->message_tags = json_decode(str_replace("'", esc_html("'"), $row->message_tags));
			$object->with_tags = json_decode(str_replace("'", esc_html("'"), $row->with_tags));
			$object->story_tags = json_decode(str_replace("'", esc_html("'"), $row->story_tags));
			$object->comments = json_decode(str_replace("'", esc_html("'"), $row->comments));
			$object->attachments = new stdClass();

			if(isset($attachments->data[0]->media))
				$object->attachments->media=$attachments->data[0]->media;
			if(isset($attachments->data[0]->subattachments))
				$object->attachments->subattachments=$attachments->data[0]->subattachments;
			if(isset($attachments->data[0]->type))
				$object->attachments->type=$attachments->data[0]->type;

			$object->shares = json_decode(str_replace("'", esc_html("'"), $row->shares));
			$object->who_post = json_decode(str_replace("'", esc_html("'"), $row->who_post));
			array_push($id_object_id_json, $object);
		}
		$this->id_object_id_json = $id_object_id_json;
    // Set graph url
    $this->graph_url = str_replace (
      array('{ACCESS_TOKEN}', '{LIMIT}'),
      array('access_token=' . $this->access_token . '&', ''),
      $this->graph_url
    );
    return $results;
  }
  public function humanTiming ($time) {
    $time = time() - $time;
		$tokens = array (
        31536000 => __('year','ffwd'),
        2592000 => __('month','ffwd'),
        604800 => __('week','ffwd'),
        86400 =>  __('day','ffwd'),
        3600 => __('hour','ffwd'),
        60 => __('minute','ffwd'),
        1 => __('second','ffwd')
    );

	  $tokens_s = array (
        31536000 => __('years','ffwd'),
        2592000 => __('months','ffwd'),
        604800 => __('weeks','ffwd'),
        86400 =>  __('days','ffwd'),
        3600 => __('hours','ffwd'),
        60 => __('minutes','ffwd'),
        1 => __('seconds','ffwd')
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
		if($numberOfUnits>1)
        return $numberOfUnits.' '.$tokens_s[$unit] . __(' ago','ffwd');

		return $numberOfUnits.' '.$text. __(' ago','ffwd');
    }
  }
	public function ffwd_time($object_row, $ffwd,$event_date) {
		$event_date_format = $this->options->event_date_format;
		$post_date_format = $this->options->post_date_format;
		$date_timezone = wp_timezone_string();
		$date_create = new DateTime($object_row->created_time);
		$date_create->setTimezone(new DateTimeZone($date_timezone));
		$date_update = new DateTime($object_row->updated_time);
		$date_update->setTimezone(new DateTimeZone($date_timezone));
		if($object_row->type == "events") {
			if($event_date) {
				?>
				<div class="ffwd_from_time_event_<?php echo $ffwd; ?>" style="">
					<?php
						echo $date_create->format($event_date_format) . '<br>';
					?>
				</div>
				<?php
		  }
		}
		else {
			?>
			<div class="ffwd_from_time_post_<?php echo $ffwd; ?>" style="">
			<i class=" ffwd ffwd-time"></i>
			<span>
				<?php
				if($post_date_format == 'ago') {
					$time = strtotime($object_row->created_time);
					echo $this->humanTiming($time);
				}
				else
					echo $date_create->format($post_date_format);
				?>
			</span>
			</div>
			<?php
		}
	}
	public function fill_tags($string, $message_tags, $ffwd) {
		$message_tags = json_decode(str_replace("'", esc_html("'"), $message_tags));
		if($message_tags)
			foreach($message_tags as $message_tag) {
				$type = gettype ( $message_tag );
				$tag = ($type == "object") ? $message_tag : $message_tag["0"];
				$tag_name = ($tag->name != NULL && $tag->name != "") ? $tag->name : false;
				if($tag_name && strpos($string, $tag_name) !== false) {
					$string = str_replace($tag_name, '<a class="ffwd_message_tag_'.$ffwd.'" target="_blank" href="https://www.facebook.com/' . $tag->id . '" >'.$tag_name.'</a>', $string);
				}
			}
		return $string;
  }
  public function fill_hashtags ($str, $ffwd) {
    $str = preg_replace("/\n/", " \n", $str);
    $str = explode(' ', $str);
    for($i=0; $i<count($str); $i++) {
      if(strpos($str[$i], '#') === 0) {
        $hashtag = str_replace('#', '<a class="ffwd_hashtag_'.$ffwd.'" target="_blank" href="https://www.facebook.com/hashtag/', $str[$i]);
        $word = explode('#', $str[$i]);
        $word = '#' . $word[1];
        $hashtag .= '">' . $word . '</a>';
        $str[$i] = $hashtag;
      }
    }
    $str = implode(' ', $str);
    return $str;
  }

	public function see_less_more($string, $type, $row_type,$post_text_length=200,$event_desp_length=200) {
    $string = strip_tags($string);
    $new_string = $string;
    $hide_text_paragraph = '';
    $length = strlen($string);
		if($row_type == 'events')
      $text_length = $event_desp_length;
		else
      $text_length = $post_text_length;


		if ($length > $text_length) {
      // Truncate string
      $stringCut = substr($string, 0, $text_length);
      // Make sure it ends in a word so football doesn't footba ass...
      $last_whitespace_in_string_cut = strrpos($stringCut, ' ');
			$last_whitespace_in_string_cut = ($last_whitespace_in_string_cut === false) ? 0 : $last_whitespace_in_string_cut;
      // Get hide text
      $hide_text_length = $length - $last_whitespace_in_string_cut;
      $hide_text = substr($string, $last_whitespace_in_string_cut, $hide_text_length);
      $hide_text_paragraph = ' <span style="display:none" class="ffwd_blog_style_object_'.$type.'_hide" >' . $hide_text . ' </span>';

      $new_string = substr($stringCut, 0, $last_whitespace_in_string_cut) . $hide_text_paragraph . ' <span class="more_dotes" > ... </span> <a href="" class="ffwd_see_more_'.$type.'">'.__('See more','ffwd').'</a>';
    }
    return $new_string;
  }
}
