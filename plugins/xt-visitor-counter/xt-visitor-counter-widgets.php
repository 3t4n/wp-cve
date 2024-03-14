<?php
class xt_visitor_counter extends WP_Widget{

	function __construct(){
		$params=array(
		'description' => __('Display Visitor Counter and Statistics Traffic', 'xt-visitor-counter'), //plugin description
		'name' => 'XT - Visitor Counter'  //title of plugin
		);

		parent::__construct('xt_visitor_counter', '', $params);
	}

	// extract($instance);
	public function form($instance)  {
	$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
	$title = $instance['title'];
	?>
	<div><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('font_color'); ?>">Font Color: <input class="widefat" id="<?php echo $this->get_field_id('font_color'); ?>" name="<?php echo $this->get_field_name('font_color'); ?>" type="text" value="<?php echo $instance['font_color']; ?>" /></label></div>
	<div><font size='2'>To change the font color, fill the field with the HTML color code. example: #333 </font></div>
	<div><font size='2'><a href="options-general.php?page=xtvc_options_general" target="_blank">Click here</a> to select another color variation.</font></div>
	<div><font size='3'><b>Widget Options</b></font></div>
	<!-- UPDATE PLAN -->
	<div><label for="<?php echo $this->get_field_id('count_start'); ?>">Counter Start: <input class="widefat" id="<?php echo $this->get_field_id('count_start'); ?>" name="<?php echo $this->get_field_name('count_start'); ?>" type="text" value="<?php echo $instance['count_start']; ?>" /></label></div>
	<div><font size='2'>Fill in with numbers to start the initial calculation of the counter, if the empty counter will start from 1</font></div>
	<div><label for="<?php echo $this->get_field_id('views_start'); ?>">views Start: <input class="widefat" id="<?php echo $this->get_field_id('views_start'); ?>" name="<?php echo $this->get_field_name('views_start'); ?>" type="text" value="<?php echo $instance['views_start']; ?>" /></label></div>
	<div><font size='2'>Fill in the numbers to start the initial calculation of the views, if the empty views will start from 1</font></div>
	<!-- END UPDATE -->
	<div><label for="<?php echo $this->get_field_id('today_view'); ?>"><?php _e('Enable Visit Today display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['today_view'], 'on' ); ?> id="<?php echo $this->get_field_id('today_view'); ?>" name="<?php echo $this->get_field_name('today_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('yesterday_view'); ?>"><?php _e('Enable Visit Yesterday display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['yesterday_view'], 'on' ); ?> id="<?php echo $this->get_field_id('yesterday_view'); ?>" name="<?php echo $this->get_field_name('yesterday_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('month_view'); ?>"><?php _e('Enable Month display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['month_view'], 'on' ); ?> id="<?php echo $this->get_field_id('month_view'); ?>" name="<?php echo $this->get_field_name('month_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('year_view'); ?>"><?php _e('Enable Year display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['year_view'], 'on' ); ?> id="<?php echo $this->get_field_id('year_view'); ?>" name="<?php echo $this->get_field_name('year_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('total_view'); ?>"><?php _e('Enable Total Visit display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['total_view'], 'on' ); ?> id="<?php echo $this->get_field_id('total_view'); ?>" name="<?php echo $this->get_field_name('total_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('views_view'); ?>"><?php _e('Enable views Today display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['views_view'], 'on' ); ?> id="<?php echo $this->get_field_id('views_view'); ?>" name="<?php echo $this->get_field_name('views_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('totalviews_view'); ?>"><?php _e('Enable Total views display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['totalviews_view'], 'on' ); ?> id="<?php echo $this->get_field_id('totalviews_view'); ?>" name="<?php echo $this->get_field_name('totalviews_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('online_view'); ?>"><?php _e('Enable Whos Online display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['online_view'], 'on' ); ?> id="<?php echo $this->get_field_id('online_view'); ?>" name="<?php echo $this->get_field_name('online_view'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('ip_display'); ?>"><?php _e('Enable IP address display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['ip_display'], 'on' ); ?> id="<?php echo $this->get_field_id('ip_display'); ?>" name="<?php echo $this->get_field_name('ip_display'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('server_time'); ?>"><?php _e('Enable Server Time display?', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" <?php checked( $instance['server_time'], 'on' ); ?> id="<?php echo $this->get_field_id('server_time'); ?>" name="<?php echo $this->get_field_name('server_time'); ?>" /></label></div>
	<div><label for="<?php echo $this->get_field_id('xtvc_align'); ?>"><?php _e('Plugins align?', 'xt-visitor-counter'); ?> 
	<select class="select" id="<?php echo $this->get_field_id('xtvc_align'); ?>" name="<?php echo $this->get_field_name('xtvc_align'); ?>" selected="<?php echo $instance['xtvc_align']; ?>">
	<option value="<?php echo $instance['xtvc_align']; ?>"><?php echo $instance['xtvc_align']; ?></option>
	<option value="Left">Left</option>
	<option value="Center">Center</option>
	<option value="Right">Right</option>
	</select></label></div>
	<div>Please go to <a href="options-general.php?page=xtvc_options_general">Settings -> XT Visitor Counter</a> to configure image counter</div>
	<div><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=9GW78JRGZAP8E&lc=ID&item_name=XT%20Visitor%20Counter&item_number=426267&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" alt="<?php _e('Donate', 'xt-visitor-counter')?>" /></a></div>
	<?php
	}

	public function widget($args, $instance){
		global $wpdb;
		extract($args, EXTR_SKIP);
		
		if (rand(0,9) == 4) $after_widget = xt_visitor_counter_default();

		$ipaddress = isset($instance['ip_display']) ? $instance['ip_display'] : false ; // display ip address
		$stime = isset($instance['server_time']) ? $instance['server_time'] : false ; // display server time
		$fontcolor= $instance['font_color'];
		$style = get_option ('xt_visitor_counter_style');
		$xtvc_attribution = get_option ('xt_visitor_counter_attribution');
		$align = $instance['xtvc_align'];
		$todayview = $instance ['today_view'];
		$yesview = $instance ['yesterday_view'];
		$monthview = $instance ['month_view'];
		$yearview = $instance ['year_view'];
		$totalview = $instance ['total_view'];
		$viewsview = $instance ['views_view'];
		$totalviewsview = $instance ['totalviews_view'];
		$onlineview = $instance ['online_view'];
		$count_start = $instance ['count_start'];
		$views_start = $instance ['views_start'];

		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);


	if (!empty($title))	echo $before_title . $title . $after_title;
	$ip = xt_getRealIpAddr(); // Getting the user's computer IP
	$date = date("Y-m-d"); // Getting the current date
	$tglk = date("Y-m-d",strtotime("-1 days")); // Getting the yesterday date
	$waktu = time();
	$blan = date("Y-m");
	$thn = date("Y");
	
	// Check your IP, whether the user has had access to today's
	$sql = $wpdb->query( "INSERT INTO `". XT_VC_TABLE_NAME . "`(`ip`, `date`, `views`, `online`) VALUES('$ip', '$date', '1', '$waktu') ON DUPLICATE KEY UPDATE `views` = `views` +1, `online` = '$waktu';" );
	
	//variable
	$kemarin = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "` WHERE `date` = '$tglk'" );
	$bulan = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "` WHERE `date` LIKE '$blan%'" );
	$tahunini = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "` WHERE `date` LIKE '$thn%'" );
	$pengunjung = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "` WHERE `date` = '$date'" );
	$totalpengunjung = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "`" );
	$views = $wpdb->get_var( "SELECT SUM(`views`) FROM `". XT_VC_TABLE_NAME . "` WHERE `date` = '$date'" );
	$totalviews = $wpdb->get_var( "SELECT SUM(`views`) FROM `". XT_VC_TABLE_NAME . "`" );
	$totviewsgbr = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "`" );
	$bataswaktu = time() - 300;
	$pengunjungonline = $wpdb->get_var( "SELECT COUNT(`views`) FROM `". XT_VC_TABLE_NAME . "` WHERE `online` > '$bataswaktu'" );


	$ext = ".gif";
	//image print
	// UPDATE PLAN
	if ($count_start==NULL) {
	$totviewsgbr = sprintf("%06d", $totviewsgbr);
	}else{
	$totviewsgbr = sprintf("%06d", $totviewsgbr + $count_start);

	}
	for ($i = 0; $i <= 9; $i++) {
	$totviewsgbr = str_replace($i, "<img src='". plugins_url ("styles/$style/$i$ext" , __FILE__ ) ."' alt='$i'>", $totviewsgbr);
	}
	//image
	$imgvisit= "<img src='".plugins_url ('counter/mvcvisit.png' , __FILE__ ). "'>";
	$yesterday="<img src='".plugins_url ('counter/mvcyesterday.png' , __FILE__ ). "'>";
	$month="<img src='".plugins_url ('counter/mvcmonth.png' , __FILE__ ). "'>";
	$year="<img src='".plugins_url ('counter/mvcyear.png' , __FILE__ ). "'>";
	$imgtotal="<img src='".plugins_url ('counter/mvctotal.png' , __FILE__ ). "'>";
	$imgviews="<img src='".plugins_url ('counter/mvctoday.png' , __FILE__ ). "'>";
	$imgtotalviews="<img src='".plugins_url ('counter/mvctotalviews.png' , __FILE__ ). "'>";
	$imgonline="<img src='" .plugins_url ('counter/mvconline.png' , __FILE__ ). "'>";
	//style and widgetne

	echo "<link rel='stylesheet' type='text/css' href='". plugins_url ("styles/css/default.css" , __FILE__ ) ."' />";
	
	if ($align) $align = "text-align: $align;";
	if ($fontcolor) $fontcolor = "color: $fontcolor;";
	if ($align || $fontcolor) $style = "style='$align $fontcolor'";
	
	?>
	<div id='mvcwid' <?php echo $style ?>>
	<div id="xtvccount"><?php echo $totviewsgbr ?></div>
	<div id="xtvctable">
	<?php if ($todayview) { ?>
	<div id="xtvcvisit" <?php echo $style ?>><?php echo $imgvisit ?> <?php _e('Users Today', 'xt-visitor-counter');?> : <?php echo $pengunjung ?></div>
	<?php } ?>
	<?php if ($yesview) { ?>
	<div id="xtvcyesterday" <?php echo $style ?>><?php echo $yesterday ?> <?php _e('Users Yesterday', 'xt-visitor-counter');?> : <?php echo $kemarin ?></div>
	<?php } ?>
	<?php if ($monthview) { ?>
	<div id="xtvcmonth" <?php echo $style ?>><?php echo $month ?> <?php _e('This Month', 'xt-visitor-counter');?> : <?php echo $bulan ?></div>
	<?php } ?>
	<?php if ($yearview) { ?>
	<div id="xtvcyear" <?php echo $style ?>><?php echo $year ?> <?php _e('This Year', 'xt-visitor-counter');?> : <?php echo $tahunini ?></div>
	<?php } ?>
	<?php if ($totalview) { ?>
	<div id="xtvctotal" <?php echo $style ?>><?php echo $imgtotal ?> <?php _e('Total Users', 'xt-visitor-counter');?> : <?php echo $totalpengunjung ?></div>
	<?php } ?>
	<?php if ($viewsview) { ?>
	<div id="xtvcviews" <?php echo $style ?>><?php echo $imgviews ?> <?php _e('Views Today', 'xt-visitor-counter');?> : <?php echo $views ?></div>
	<?php } ?>
	<?php if ($totalviewsview) { ?>
	<div id="xtvctotalviews" <?php echo $style ?>><?php echo $imgtotalviews ?> <?php _e('Total views', 'xt-visitor-counter');?> : <?php if ($views_start==NULL) {
	echo $totalviews ;
	}else{
	$totalviewsfake = $totalviews + $views_start;
	echo $totalviewsfake;
	}?></div>
	<?php } ?>
	<?php if ($onlineview) { ?>
	<div id="xtvconline" <?php echo $style ?>><?php echo $imgonline ?> <?php _e("Who's Online", 'xt-visitor-counter');?> : <?php echo $pengunjungonline ?></div>
	<?php } ?>
	</div>
	<?php if ($ipaddress) { ?>
	<div id="xtvcip"><?php _e('Your IP Address', 'xt-visitor-counter');?> : <?php echo $ip ?></div>
	<?php } ?>
	<?php if ($stime) { ?>
	<div id="xtvcdate"><?php _e('Server Time', 'xt-visitor-counter');?> : <?php echo $date ?></div>
	<?php } ?>	
	<?php if ($xtvc_attribution) { ?>
	<div id="xtvcattribution" <?php echo $style ?>><small>Powered By <a href="https://xtrsyz.org/" rel="nofollow">XT Visitor Counter</a></small></div>
	<?php } ?>
	</div>
	<?php
	echo $after_widget;
	}
}