<?php
	function wps_add_visitor_counter() {
		global $wpdb;
		$wps_option_data = wps_visitor_option_data(1);

        		$wps_display_field = $wps_option_data['display_field'];
        		$wps_display_field_arr = explode(",",$wps_display_field);
        		

	$fontcolor= $wps_option_data["font_color"];
	$style = $wps_option_data["style"];
	$align = $wps_option_data["visitor_wpsvc_align"];

	

	$user_start = $wps_option_data["user_start"];
	$views_start = $wps_option_data["views_start"];


	
	$ip = wps_getRealIpAddr(); // Getting the user's computer IP
	$date = date("Y-m-d"); // Getting the current date
	$date_year_month = date("Y-m");
	$date_year = date("Y");
    $yesterday_date = date('Y-m-d',strtotime("-1 days"));
	
	
	$timeBefore = time() - 300;


	$ext = ".gif";
	//image print
	// UPDATE PLAN
	$user_total = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "`" );
	if ($user_start==NULL) {
	$total_user_views = sprintf("%06d", $user_total);
	}else{
	$total_user_views = sprintf("%06d", $user_total + $user_start);

	}
	$wps_style = explode('/', $style);
	if ($wps_style[0] == 'image') {
		for ($i = 0; $i <= 9; $i++) {
		$total_user_views = str_replace($i, "<img src='". plugins_url ("styles/$style/$i$ext" , __FILE__ ) ."' alt='$i'>", $total_user_views);
		}
	}elseif ($wps_style[0] == 'text') {
	/*$total_user_views = intval($total_user_views);*/
	/*return $total_user_views;*/
    $total_user_views_array  = array_map('intval', str_split($total_user_views));
    $total_user_views = '<div class="wps_text_glowing '.$wps_style[1].'">
    						<span>'.$total_user_views_array[0].'</span>
							<span>'.$total_user_views_array[1].'</span>
							<span>'.$total_user_views_array[2].'</span>
							<span>'.$total_user_views_array[3].'</span>
							<span>'.$total_user_views_array[4].'</span>
							<span>'.$total_user_views_array[5].'</span>
							
						</div>';
		?>

						

		<?php
	}
	
	//image
	$imgvisit= "<img src='".plugins_url ('counter/user_today.png' , __FILE__ ). "'>";
	$img_visit_yesterday="<img src='".plugins_url ('counter/user_yesterday.png' , __FILE__ ). "'>";
	$img_visit_7days="<img src='".plugins_url ('counter/7days_user.png' , __FILE__ ). "'>";
	$img_visit_30days="<img src='".plugins_url ('counter/30days_user.png' , __FILE__ ). "'>";
	$img_visit_month="<img src='".plugins_url ('counter/user_month.png' , __FILE__ ). "'>";
	$img_visit_year="<img src='".plugins_url ('counter/user_year.png' , __FILE__ ). "'>";
	$img_visit_total="<img src='".plugins_url ('counter/user_total.png' , __FILE__ ). "'>";
	$imgviews="<img src='".plugins_url ('counter/views_today.png' , __FILE__ ). "'>";
	$img_views_yesterday="<img src='".plugins_url ('counter/views_yesterday.png' , __FILE__ ). "'>";
	$img_views_7days="<img src='".plugins_url ('counter/7days_views.png' , __FILE__ ). "'>";
	$img_views_30days="<img src='".plugins_url ('counter/30days_views.png' , __FILE__ ). "'>";
	$img_views_month="<img src='".plugins_url ('counter/views_month.png' , __FILE__ ). "'>";
	$img_views_year="<img src='".plugins_url ('counter/views_year.png' , __FILE__ ). "'>";
	$imgtotalviews="<img src='".plugins_url ('counter/views_total.png' , __FILE__ ). "'>";
	$imgonline="<img src='" .plugins_url ('counter/whos_online.png' , __FILE__ ). "'>";
	//style and widgetne
	
	if ($align) $align = "text-align: $align;";
	if ($fontcolor) $fontcolor = "color: $fontcolor;";
	if ($align || $fontcolor) $style = "style='$align $fontcolor'";










	$wps_return = "<div id='mvcwid'".$style.">";
	
	?>
	
	
	<?php if ($wps_option_data['visitor_title'] != ""){
		$visitor_title = "<h3 class='wps_visitor_title'>".esc_html($wps_option_data['visitor_title'], 'wps-visitor-counter')."</h3>";

		$wps_return = $wps_return.$visitor_title;
	}
	$wps_return = $wps_return."<div id=\"wpsvccount\">".$total_user_views."</div>
	<div id=\"wpsvctable\">";


	?>
	

	
	<?php if (in_array("today_user", $wps_display_field_arr)) { 
		$user_today = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` = '$date'" );

		$wps_return = $wps_return."<div id=\"wpsvcvisit\" ".$style.">".$imgvisit." ".esc_html__('Users Today', 'wps-visitor-counter')." : ".$user_today."</div>";

		?>
		
	<?php } ?>
	<?php if (in_array("yesterday_user", $wps_display_field_arr)) { 
		$user_yesterday = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` = '$yesterday_date'" );


		$wps_return = $wps_return."<div id=\"wpsvcyesterday\" ".$style.">".$img_visit_yesterday." ".esc_html__('Users Yesterday', 'wps-visitor-counter')." : ".$user_yesterday."</div>";


		?>
	<?php } ?>

	<?php if (in_array("last7_day_user", $wps_display_field_arr)) { 
		$user_last_7days = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` >= DATE(NOW()) - INTERVAL 7 DAY" );


		$wps_return = $wps_return."<div id=\"wpsvcyesterday\" ".$style.">".$img_visit_7days." ".esc_html__('Users  Last 7 days', 'wps-visitor-counter')." : ".$user_last_7days."</div>";


		?>
	<?php } ?>
	<?php if (in_array("last30_day_user", $wps_display_field_arr)) { 
		$user_last_30days = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` >= DATE(NOW()) - INTERVAL 30 DAY" );


		$wps_return = $wps_return."<div id=\"wpsvcyesterday\" ".$style.">".$img_visit_30days." ".esc_html__('Users Last 30 days', 'wps-visitor-counter')." : ".$user_last_30days."</div>";


		?>
	<?php } ?>
	<?php if (in_array("month_user", $wps_display_field_arr)) { 
		$user_month = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` LIKE '$date_year_month%'" );


		$wps_return = $wps_return."<div id=\"wpsvcmonth\" ".$style.">".$img_visit_month." ".esc_html__('Users This Month', 'wps-visitor-counter')." : ".$user_month."</div>";


		?>
	<?php } ?>
	<?php if (in_array("year_user", $wps_display_field_arr)) { 
		$user_year = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` LIKE '$date_year%'" );


		$wps_return = $wps_return."<div id=\"wpsvcyear\" ".$style.">".$img_visit_year." ".esc_html__('Users This Year', 'wps-visitor-counter')." : ".$user_year."</div>";


		?>
	<?php } ?>
	<?php if (in_array("total_user", $wps_display_field_arr)) { 
		if ($user_start==NULL) {
		$total_user = $user_total;
		}else{
		$total_user = $user_total + $user_start;

		}


		$wps_return = $wps_return."<div id=\"wpsvctotal\" ".$style.">".$img_visit_total." ".esc_html__('Total Users', 'wps-visitor-counter')." : ".$total_user."</div>";



		?>
	<?php } ?>
	<?php if (in_array("today_view", $wps_display_field_arr)) { 
		$views_today= $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` = '$date'" );


		$wps_return = $wps_return."<div id=\"wpsvcviews\" ".$style.">".$imgviews." ".esc_html__('Views Today', 'wps-visitor-counter')." : ".$views_today."</div>";


		?>
	<?php } ?>
	<?php if (in_array("yesterday_view", $wps_display_field_arr)) { 
		$views_yesterday = $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` = '$yesterday_date'" );
		if ($views_yesterday=="") {
			$views_yesterday==0;
		}

		$wps_return = $wps_return."<div id=\"wpsvcviews\" ".$style.">".$img_views_yesterday." ".esc_html__('Views Yesterday', 'wps-visitor-counter')." : ".$views_yesterday."</div>";


		?>
	<?php } ?>
	<?php if (in_array("last7_day_view", $wps_display_field_arr)) { 
		$views_last7_days = $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` >= DATE(NOW()) - INTERVAL 7 DAY" );


		$wps_return = $wps_return."<div id=\"wpsvcyesterday\" ".$style.">".$img_views_7days." ".esc_html__('Views Last 7 days', 'wps-visitor-counter')." : ".$views_last7_days."</div>";


		?>
	<?php } ?>
	<?php if (in_array("last30_day_view", $wps_display_field_arr)) { 
		$views_last30_days = $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` >= DATE(NOW()) - INTERVAL 30 DAY" );


		$wps_return = $wps_return."<div id=\"wpsvcyesterday\" ".$style.">".$img_views_30days." ".esc_html__('Views Last 30 days', 'wps-visitor-counter')." : ".$views_last30_days."</div>";


		?>
	<?php } ?>
	<?php if (in_array("month_view", $wps_display_field_arr)) { 
		$views_month = $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` LIKE '$date_year_month%'" );


		$wps_return = $wps_return."<div id=\"wpsvcviews\" ".$style.">".$img_views_month." ".esc_html__('Views This Month', 'wps-visitor-counter')." : ".$views_month."</div>";


		?>
	<?php } ?>

	
	<?php if (in_array("year_view", $wps_display_field_arr)) { 
		$views_year = $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `date` LIKE '$date_year%'" );


		$wps_return = $wps_return."<div id=\"wpsvcviews\" ".$style.">".$img_views_year." ".esc_html__('Views This Year', 'wps-visitor-counter')." : ".$views_year."</div>";


		?>
	<?php } ?>
	<?php if (in_array("total_view", $wps_display_field_arr)) { 
		$totalviews = $wpdb->get_var( "SELECT SUM(`views`) FROM `". WPS_VC_TABLE_NAME . "`" );
		 if ($views_start!=NULL) {
				$totalviews = $totalviews + $views_start;
			}

		$wps_return = $wps_return."<div id=\"wpsvctotalviews\" ".$style.">".$imgtotalviews." ".esc_html__('Total views', 'wps-visitor-counter')." : ".$totalviews."</div>";


		?>
	<?php } ?>
	<?php if (in_array("online_view", $wps_display_field_arr)) { 
		$total_online = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". WPS_VC_TABLE_NAME . "` WHERE `online` > '$timeBefore'" );


		$wps_return = $wps_return."<div id=\"wpsvconline\" ".$style.">".$imgonline." ".esc_html__('Who\'s Online', 'wps-visitor-counter')." : ".$total_online."</div>";


		?>
	<?php } 
	$wps_return = $wps_return."</div>";
	?>
	
	<?php if (in_array("ip_display", $wps_display_field_arr)) { 


		$wps_return = $wps_return."<div id=\"wpsvcip\">".$img_visit_year." ".esc_html__('Your IP Address', 'wps-visitor-counter')." : ".$ip."</div>";


		?>
	<?php } ?>
	<?php if (in_array("server_time", $wps_display_field_arr)) { 


		$wps_return = $wps_return."<div id=\"wpsvcdate\">".$img_visit_year." ".esc_html__('Server Time', 'wps-visitor-counter')." : ".$date."</div>";


		?>
	<?php } ?>	
	<?php if ($wps_option_data['show_powered_by'] == 1) { 


		$wps_return = $wps_return."<div id=\"wpsvcattribution\" ".$style."><small>Powered By <a href=\"https://techmix.xyz/\" rel=\"nofollow\">WPS Visitor Counter</a></small></div>";


		?>
	<?php } 

	$wps_return = $wps_return."</div>";
	return $wps_return;
	?>

	
	<?php

	}




	
?>