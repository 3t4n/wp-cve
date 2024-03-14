<?php

	// returns a list of requirements
	function wpc_get_ordering_list($post_type = 'wpc-requirement'){
		$data = '';
		$args = array(
	        'post_type'         => $post_type,
	        'orderby'           => 'menu_order',
	        'nopaging'          => true,
	        'order'             => 'ASC',
	        'posts_per_page'    => -1,
	    );
	    $query = new WP_Query($args);
	    if($query->have_posts()){
	    	$data .= '<ul class="lesson-list">';
	        while($query->have_posts()){
	            $query->the_post();
	            $id = get_the_ID();
	            $data .= '<li class="lesson-button wpc-order-lesson-list-lesson" data-post-type="' . esc_attr($post_type) . '" data-id="' . get_the_ID() . '"><i class="fa fa-bars wpc-grab"></i> ' . get_the_title() . '<a href="' . get_edit_post_link($id) . '" style="float:right;"> (Edit)</a></li>';
	        }
	        $data .= '</ul>';
	    } else {
	    	$data = esc_html( __('There is nothing to order', 'wp-courses') ) . '.';
	    }
	   	wp_reset_postdata();
	    return $data;
	}

	// get rule by ID
	function wpc_get_rule_by_ID($rule_id){
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpc_rules';
			$sql = "SELECT id, post_id, course_id, module_id, lesson_id, type, action, percent, times FROM {$table_name} WHERE id = {$rule_id}";
			$results = $wpdb->get_results($sql);
			return $results;
	}

	// return rules rows
	function wpc_get_rules($post_id){
		global $wpdb;
		$table = $wpdb->prefix . 'wpc_rules';
		$sql = "SELECT id, post_id, course_id, module_id, lesson_id, action, percent, type, times FROM {$table} WHERE post_id = {$post_id}";
		$results = $wpdb->get_results($sql);
		return $results;
	}

	// checks which rules have been completed and returns rule tracking
	function wpc_check_rules($post_id, $user_id){

		if(!is_user_logged_in()){
			return;
		}

		global $wpdb;

		// get published courses
		$table_name = $wpdb->prefix . 'posts';
		$sql = "SELECT ID FROM {$table_name} WHERE post_status = 'publish' AND post_type = 'course'";
		$courses = $wpdb->get_results($sql);

		// get published modules
		$mod_table_name = $wpdb->prefix . 'posts';
		$mod_sql = "SELECT ID FROM $mod_table_name WHERE post_type = 'wpc-module' AND post_status = 'publish'";
		$modules = $wpdb->get_results($mod_sql);

		$completed_lessons = wpc_get_tracked_lessons_by_user($user_id, 1);
		$viewed_lessons = wpc_get_tracked_lessons_by_user($user_id, 0);

        $posts_table = $wpdb->prefix . 'posts';
        $sql = "SELECT ID FROM $posts_table WHERE post_type = 'wpc-quiz' AND post_status = 'publish'";
        $quizzes = $wpdb->get_results($sql, ARRAY_N);
        $total_quiz_count = $wpdb->num_rows;
        $quiz_ids = array_map('end', $quizzes);
        $quiz_ids = array_map('strval', $quiz_ids);
        $quiz_ids = "'" . implode("', '", $quiz_ids) . "'";

        $table_name = $wpdb->prefix . "wpc_tracking";
        $sql = "SELECT post_id FROM $table_name WHERE post_id IN ( {$quiz_ids} ) AND user_id = $user_id";
        $viewed_quizzes =$wpdb->get_results($sql);
        $viewed_quizzes_count = $wpdb->num_rows;

        $quiz_table_name = $wpdb->prefix . "wpc_quiz_results";
        $sql = "SELECT id FROM $quiz_table_name WHERE user_ID = $user_id";
        $completed_quizzes = $wpdb->get_results($sql);
        $completed_quizzes_count = $wpdb->num_rows;

		$rule_tracking = array();
		$completed_rule = false;

		$rules = wpc_get_rules($post_id);

		foreach($rules as $rule) {
			if($rule->action == 'views'){

				if($rule->type == 'specific-lesson'){
					$completed_rule = wpc_has_done($rule->lesson_id, $viewed_lessons, 0) == true ? true : false;
				} elseif($rule->type == 'any-lesson'){
					$completed_rule = count($viewed_lessons) >= $rule->times ? true : false;
				} elseif($rule->type == 'specific-course'){
					$completed_rule = wpc_get_percent_done($rule->course_id, $user_id, 0) >= $rule->percent ? true : false;
				} elseif($rule->type == 'any-course'){
					$times = 0;
					foreach($courses as $course){
						if( wpc_get_percent_done($rule->course_id, $user_id, 0) >= $rule->percent ){
							$times++;
						}
					}

					$completed_rule = $times >= $rule->times ? true : false;

				} elseif($rule->type == 'specific-module'){
					$completed_rule = wpc_get_module_percent_done($rule->module_id, $user_id, 0) >= $rule->percent ? true : false;
				} elseif($rule->type == 'any-module'){

					$times = 0;

					if(!empty($modules)) {
						foreach($modules as $module) {
							if( wpc_get_module_percent_done($module->ID, $user_id, 0) >= $rule->percent ){
								$times++;
							}
						}
					}

					$completed_rule = $times >= $rule->times ? true : false;

				} elseif($rule->type == 'any-quiz'){

					$completed_rule = $viewed_quizzes_count >= $rule->times ? true : false;

				} elseif($rule->type == 'specific-quiz'){
					$completed_rule = wpc_has_done($rule->lesson_id, $viewed_lessons, 0) == true ? true : false;
				}

			} elseif($rule->action == 'completes'){

				if($rule->type == 'specific-lesson'){
					$completed_rule = wpc_has_done($rule->lesson_id, $completed_lessons, 1) == true ? true : false;
				} elseif($rule->type == 'any-lesson'){

					//var_dump($completed_lessons);

					$count = count($completed_lessons);
					$completed_rule = $count >= (int) $rule->times ? true : false;

				} elseif($rule->type == 'specific-course'){
					$completed_rule = (int) wpc_get_percent_done($rule->course_id, $user_id, 1) >= (int) $rule->percent ? true : false;
				} elseif($rule->type == 'any-course'){
					$times = 0;
					foreach($courses as $course){
						if( wpc_get_percent_done($course->ID, $user_id, 1) >= (int) $rule->percent ){
							$times++;
						}
					}

					$completed_rule = $times >= (int) $rule->times ? true : false;

				} elseif($rule->type == 'specific-module'){
					$completed_rule = wpc_get_module_percent_done($rule->module_id, $user_id, 1) >= (int) $rule->percent ? true : false;
				} elseif($rule->type == 'any-module'){

					$times = 0;

					if(!empty($modules)) {
						foreach($modules as $module) {
							//echo wpc_get_module_percent_done($module->ID, $user_id, 1);
							if( wpc_get_module_percent_done($module->ID, $user_id, 1) >= (int) $rule->percent ){
								$times++;
							}
						}
					}

					$completed_rule = $times >= $rule->times ? true : false;

				}  elseif($rule->type == 'any-quiz'){

					$completed_rule = $completed_quizzes_count >= $rule->times ? true : false;

				} elseif($rule->type == 'specific-quiz'){
					$completed_rule = wpc_has_done($rule->lesson_id, $completed_lessons, 1) == true ? true : false;
				}

			} elseif($rule->action == 'scores') {

				$quiz_table_name = $wpdb->prefix . 'wpc_quiz_results';

				if($rule->type == 'specific-quiz'){
					// check if user has met or exceeded score requirements for specific quiz
					$quiz_sql = "SELECT user_ID, quiz_ID, score_percent FROM {$quiz_table_name} WHERE user_ID = {$user_id} AND score_percent >= {$rule->percent} AND quiz_ID = {$rule->lesson_id}";
					$quiz_results = $wpdb->get_results($quiz_sql);
					$count = $wpdb->num_rows;
					$completed_rule = $count >= 1 ? true : false;
				} elseif($rule->type == 'any-quiz'){
					// check if user has met or exceeded score and times requirements for any quiz
					$quiz_sql = "SELECT user_ID, score_percent FROM {$quiz_table_name} WHERE user_ID = {$user_id} AND score_percent >= {$rule->percent}";
					$quiz_results = $wpdb->get_results($quiz_sql);
					$count = $wpdb->num_rows;
					$completed_rule = $count >= $rule->times ? true : false;
				}
			}

			// push rule results to tracking array
			array_unshift($rule_tracking, array(
				'rule_id'		=> $rule->id,
				'rule_status'	=> $completed_rule,
			));

		}

		return $rule_tracking;

	}

	function wpc_get_awards(){

		if(!is_user_logged_in()){
			return 'false';
		}

		$data = '';

		$award_badge = false;
		$award_certificate = false;
		$award_email = false;
		$new_status = null;

		// get all badges, certificates and emails
		$args = array(
			'post_type'			=> array('wpc-badge', 'wpc-certificate', 'wpc-email'),
			'posts_per_page'	=> -1,
			'paged'				=> false,
			'post_status'		=> array('publish'),
		);

		$query = new WP_Query($args);

		$user =  wp_get_current_user();
		$user_id = get_current_user_id();
		$requirement_tracking = get_user_meta($user_id, 'wpc-requirement-tracking', true);
		$tracking = array();
		$to_award = array();
		$home_url = home_url();

		if($query->have_posts()){
			while($query->have_posts()){
				$query->the_post();
				$post_id = get_the_ID();
				$rule_tracking = wpc_check_rules($post_id, $user_id);
				$old_status = false;
				$award_post_type = get_post_type();
				$oldTime = time();

				if(!empty($requirement_tracking)){
					foreach($requirement_tracking as $req) {
						if( $req['id'] == $post_id ){
							$old_status = $req['status'];
							$oldTime = $req['time'];
							break;
						}
					}
				}
				
				// check if all rules have been met
				foreach($rule_tracking as $rule) {
					if($rule['rule_status'] == true){
						$new_status = true;
					} else {
						$new_status = false;
						break;
					}
				}

				if($old_status == false && $new_status == true){
					// Requirements have been met.  Give award.

					$to_award[] = array(
						'id' 	=> $post_id,
						'type'	=> $award_post_type,
					);

					if($award_post_type == 'wpc-badge'){
						$award_badge = true;
					} elseif($award_post_type == 'wpc-certificate'){
						$award_certificate = true;
					} elseif($award_post_type == 'wpc-email'){
						$award_email = true;
					}

					$newTime = time();
				}

				$time = $newTime ? $newTime : $oldTime;

				// push results to array so we can store in user meta
				array_unshift($tracking, array(
					'id'		=> $post_id,
					'type'		=> $award_post_type,
					'status'	=> $new_status,
					'rules'		=> $rule_tracking,
					'time'		=> $time,
				));

			} // end while

			wp_reset_postdata();

			update_user_meta( $user_id, 'wpc-requirement-tracking', $tracking );

			$status = get_user_meta($user_id, 'wpc-email-status', true);

			$opt_in = get_option('wpc_opt_in');
			if($opt_in != 'true' && empty($status) || $status == 'false') {
				$send_email = 'false';
			} else{
				$send_email = 'true';
			}

			// check if we should send the email(s)

			if($award_email == true  && $send_email != 'false') {

				$global_name = get_option('wpc_email_from_name');
				$global_from = get_option('wpc_email_from');
				$global_cc = get_option('wpc_cc');
				$global_bcc = get_option('wpc_bcc');

				$headers = array('Content-Type: text/html; charset=UTF-8');
				$headers = apply_filters('wpc_email_headers', $headers);

				$name = get_option('wpc_business_name');
				$unit = get_option('wpc_unit_number');
				$address = get_option('wpc_physical_address');
				$city = get_option('wpc_city');
				$state = get_option('wpc_state');
				$zip = get_option('wpc_zip_code');
				$country = get_option('wpc_country');

				$address = '<p><br><b>' . $name . '</b><br>' . $unit . ' ' . $address . '<br>';
				$address .= $city . ' ' . $state . ' ' . $zip . '<br>';
				$address .= $country . '</p>';
				$address = apply_filters('wpc_email_signature', $address);
				$admin_email = get_bloginfo('admin_email');

				foreach($to_award as $email){
					if($email['type'] == 'wpc-email'){
						if(!empty($user->user_email)){

							// send the email

							$this_name = get_post_meta((int) $email['id'], 'wpc-email-from-name', true);
							$this_from = get_post_meta((int) $email['id'], 'wpc-email-from', true);
							$this_cc = get_post_meta((int) $email['id'], 'wpc-cc', true);
							$this_bcc = get_post_meta((int) $email['id'], 'wpc-bcc', true);

							$name = empty($this_name) ? ' ' . $global_name : ' ' . $global_name;

							if(!empty($this_from)){
								$header = !empty($name) ? 'From:' . $name . ' <' . $this_from . '>' : 'From:' . $this_from;
								$headers[] = $header;
							} elseif(!empty($global_from)){
								$header = !empty($name) ? 'From:' . $name . ' <' . $global_from . '>' : 'From:' . $global_from;
								$headers[] = $header;
							} elseif(!empty($admin_email)) {
								$header = !empty($name) ? 'From:' . $name . ' <' . $admin_email . '>' : 'From:' . $admin_email;
								$headers[] = $header;
							}

							if(!empty($this_cc)) {
								$header = 'CC: ' . $this_cc;
								$headers[] = $header;
							} elseif(!empty($global_cc)) {
								$header = 'CC: ' . $global_cc;
								$headers[] = $header;
							}

							if(!empty($this_bcc)) {
								$header = 'BCC: ' . $this_bcc;
								$headers[] = $header;
							} elseif(!empty($global_cc)) {
								$header = 'BCC: ' . $global_bcc;
								$headers[] = $header;
							}

							$unsub = '<p class="unsub"><a href="' . esc_url($home_url) . '?wpc-user-id=' . (int) $user_id . '&wpc-unsub=true">' . esc_html( __('Click here', 'wpc-emails') ) . '</a> ' . 'to unsubscribe from future emails from ' . esc_url($home_url) . '</p>';
							$unsub = apply_filters('wpc_email_unsub', $unsub);
							$content = wpautop(get_post_field('post_content', $email['id'])) . $address . $unsub;

							// replace placeholders with dynamic content
							$content = str_replace('{email}', $user->user_email, $content);
							$content = str_replace('{fname}', $user->first_name, $content);
							$content = str_replace('{lname}', $user->last_name, $content);
							$content = str_replace('{username}', $user->display_name, $content);

							$subject = html_entity_decode(get_the_title($email['id']));

							if(!empty($address) && !empty($city) && !empty($zip) && !empty($country)){
								wp_mail($user->user_email, $subject , wp_kses($content, 'post'), $headers);
							}
						}
					}
				}
			}

			// display awarded badges
			if($award_badge == true || $award_certificate == true){

				$data .= '<div class="wpc-award-lightbox-content wpc-award-slider">';
				
					foreach($to_award as $award){
						if($award['type'] == 'wpc-certificate'){
							$data .= '<div class="wpc-wiggle">' . wpc_render_tiny_certificate($user_id, $award['id']) . '</div>';
						} elseif($award['type'] == 'wpc-badge') {
							$data .= '<div class="wpc-single-awarded wpc-wiggle">';
								$data .= wpc_render_badge($award['id']);
							$data .= '</div>';
						}
					}

				$data .= '</div>';

				$fireworks = '<div class="wpc-pyro"><div class="wpc-before"></div><div class="wpc-after"></div></div>';
				return $data . $fireworks;
			} else {
				return 'false';
			}

		} else {
			return 'false'; // no post results
		} // end if
	}


	function wpc_has_requirement($post_id, $user_id = null){

		if($user_id === null) {
			$user_id = get_current_user_id();
		}

		$requirements = get_user_meta($user_id, 'wpc-requirement-tracking', true);

		if(empty($requirements)){
			return false;
		}

		$has = false;

		foreach($requirements as $requirement){
			if($post_id == $requirement['id']){
				if($requirement['status'] == true){
					$has = true;
					break;
				}
			} else {
				$has = false;
			}
		}

		return $has;

	}

?>