<?php
/*
Plugin Name: Wordpress Hebrew Date
Plugin URI: https://hatul.info/hebdate/
Description: Convert dates in wordpress to Hebrew dates.
Version: 2.0.4.1
Author: Hatul
Author URI: https://hatul.info
License: GPL https://www.gnu.org/copyleft/gpl.html
*/
class Hebdate {

	function __construct(){
		load_plugin_textdomain('hebdate', false, dirname( plugin_basename( __FILE__ ) ) );
		
		if (! extension_loaded('calendar')){
			add_action('admin_notices', [$this, 'show_admin_error']);
			return;
		}

		add_shortcode('today_hebdate', [$this, 'return_today_hebdate']);
		add_action('admin_menu', [$this, 'hebdate_admin']);
		add_action( 'widgets_init', [$this, 'register_hebdate_widget']);

		register_activation_hook(__FILE__, [$this, 'init_hebdate']);
	
		add_filter('get_comment_date', [$this, 'comment_hebDate']);
		add_filter('the_date', [$this, 'the_hebdate']);
		add_filter('get_the_time', [$this, 'the_hebdate']);
		add_filter('get_the_date', [$this, 'the_hebdate']);
	}

	function show_admin_error(){
		printf('<div class="notice notice-error"><p>%s</p></div>', __('Wordpress Hebrew Date requires the PHP calendar extension to be activated on the server to work properly.', 'hebdate'));
	}

	function hebdate($date) {
		// Returns string of Hebrew Date by hebdate_lang

		// transfer date at the sunset
		if (strpos($date, ':') !== false && get_option('hebdate_sunset') == 1){
			$date = $this->sunset($date);
		}

		$lang = get_option('hebdate_lang');

		if ($lang == 'site'){
			if (get_locale() == 'he_IL'){
				$lang = 'hebrew';
			}
			else {
				$lang = 'english';
			}
		}

		$gregorian_date = mysql2date('m-d-Y', $date);
		list($mon, $day, $year) = explode('-', $gregorian_date);
		$juldate = gregoriantojd($mon, $day, $year);

		$hebdate = jdtojewish($juldate, $lang == 'hebrew', CAL_JEWISH_ADD_GERESHAYIM + CAL_JEWISH_ADD_ALAFIM_GERESH);

		if ($lang =='number'){
			return $hebdate;
		}

		if ($lang == 'english'){
			list($tmp, $enday, $enyear) = explode('/', $hebdate);
			$enmon = jdmonthname($juldate, 4);

			// replace Adar
			if ($enmon == "AdarI" && $this->hasLeapYear($juldate)){
				$enmon = 'Adar A';
			}
			elseif ($enmon == "AdarI" && !$this->hasLeapYear($juldate)){
				$enmon = 'Adar';
			}
			elseif ($enmon == "AdarII") {
				$enmon = 'Adar B';
			}

			return "$enday $enmon $enyear";
		}

		$hebdate = iconv("windows-1255", "UTF-8", $hebdate);

		$hebdate = str_replace(array('אדר ב', 'אדר א'), array('אדרב', 'אדרא'), $hebdate);
		list($heb_day, $heb_month, $heb_year) = explode(' ', $hebdate);
		
		switch ($heb_month) {
			case "אדר":
			case "אדרא'":
			case "'אדרא":
				if ($this->hasLeapYear($juldate)){
					$heb_month='אדר א׳';
				}
				break;

			case "אדרב":
			case "אדרב'":
			case "'אדרב":
				if ($this->hasLeapYear($juldate)){
					$heb_month='אדר ב׳';
				}
				break;

			case "חשון":
				$heb_month = "מרחשוון";
				break;
		}

		if (get_option('hebdate_hide_alafim') == 1){
			$heb_year= str_replace("ה'", '', $heb_year);
		}

		$hebdate = "$heb_day ב$heb_month $heb_year";
		
		// replace merchaot to hebrew
		$hebdate = str_replace(['"', '\''], ['״', '׳'], $hebdate);
		
		return apply_filters('hebdate', $hebdate);
	}

	function the_hebdate($date) {
		// return the hebrew date of post

		if ((! preg_match('/ |-|\/|\./', $date)) || strpos($date, ':') != false){
			return $date;
		}

		global $post;
		$post_date = $post->post_date;

		if (! $post_date){
			return $post_date; //if this draft not return hebrew date
		}

		$hebdate = $this->hebdate($post_date);
		return apply_filters('the_hebdate', $this->format($this->hebdate_format(), $hebdate, $date));
	}

	// formating Hebrew date by $str
	function format($str, $heb, $greg){
		$str = str_replace(['heb', 'greg'], [$heb, $greg] ,$str);

		return apply_filters('hebdate_do_format', $str);
	}

	// return hebdate_format. if it is custom return hebdate_format_custom
	function hebdate_format(){
		$format = get_option('hebdate_format');
		if ($format =='custom') {
			$format = get_option('hebdate_format_custom');
		}
		return apply_filters('hebdate_format', $format);
	}

	// return the hebrew date of comment
	function comment_hebdate($date) {
		if (strpos($date, ':') !== false) {
			return $date;
		}

		global $comment;
		$comment_date = $comment->comment_date;
		$hebdate = $this->hebdate($comment_date);
		return apply_filters('comment_hebdate', $this->format($this->hebdate_format(), $hebdate, $date));
	}

	// print hebrew date of today
	function today_hebdate(){
		echo $this->return_today_hebDate();
	}

	// return hebrew date of today
	function return_today_hebdate(){
		$today = current_time('mysql');
		return apply_filters('today_hebdate', $this->hebdate($today));
	}

	function hebdate_options() {
		// admin page

		$example = '1-4-2007';
		?><div class="wrap">
		<h2><?php _e('Hebrew date options', 'hebdate')?></h2>
		<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="4HTHWS3LGDDPJ">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/he_IL/i/scr/pixel.gif" width="1" height="1">
			</form>
	<?php _e('Please donate to me so I can continue developing this plugin', 'hebdate')?></p>
		<form method="post" action="options.php">
		<?php settings_fields('hebdate_settings'); ?>
		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('Hebrew date format', 'hebdate')?></th>
		<td>
		<?php
		$formats = array(
			"heb (greg)",
			"heb",
			"heb – greg",
			"greg – heb",
			);
		foreach ($formats as $format){
		?>
		<input type="radio" name="hebdate_format" value="<?php echo($format) ?>" <?php if ($this->hebdate_format() == $format) {echo('checked="checked"');} ?>/><?php echo $this->format($format, $this->hebdate($example), mysql2date(get_option('date_format'), $example));?><br/><?php } ?>
		<input type="radio" name="hebdate_format" value="custom" id="hebdate_format_custom_radio" <?php if (get_option('hebdate_format') == 'custom') {echo 'checked="checked"';}?>/><?php _e('Custom:')?>
	<input type="text" name="hebdate_format_custom" value="<?php echo $this->hebdate_format();?>" onfocus="hebdate_format_custom_radio.checked=true" size="10" dir="ltr"/> <?php echo $this->format($this->hebdate_format(), $this->hebdate($example), mysql2date(get_option('date_format'), $example));?><br/>
		<p><?php _e('Use "heb" for Hebrew date and "greg" for Gregorian date. Click &#8220;Save Changes&#8221; to update sample output.', 'hebdate')?><br/>
		<?php printf(__('The Gregorian date format able to change in %s.', 'hebdate'), '<a href="options-general.php">' . __('General Settings') . '</a>');?></p>
		</td>
		</tr>
		<tr valign="top">
		<th></th>
		<td>
		<input type="checkbox" name="hebdate_hide_alafim" value="1" <?php if (get_option('hebdate_hide_alafim') == 1) echo 'checked="checked"'; ?>/><?php _e('Hide the letter of Alafim', 'hebdate');?>
		</td></tr>
		<tr valign="top">
		<th scope="row"><?php _e('Hebrew date language','hebdate')?></th>
		<td>
		<input type="radio" name="hebdate_lang" value="hebrew" <?php checked(get_option('hebdate_lang') == 'hebrew');?>><?php _e('Hebrew', 'hebdate')?><br/>
		<input type="radio" name="hebdate_lang" value="english" <?php checked(get_option('hebdate_lang') == 'english');?> /><?php _e('English', 'hebdate')?><br/>
		<input type="radio" name="hebdate_lang" value="number" <?php checked(get_option('hebdate_lang') == 'number');?> /><?php _e('Number', 'hebdate')?><br/>
		<input type="radio" name="hebdate_lang" value="site" <?php checked(get_option('hebdate_lang') == 'site');?> /><?php _e('Site Language (For multi language site)', 'hebdate')?><br/>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php _e('Sunset', 'hebdate')?></th>
		<td>
		<input type="checkbox" name="hebdate_sunset" value="1" id="sunset" <?php if (get_option('hebdate_sunset') ==1 ) echo'checked="checked"'; ?>/><?php _e('Transfer hebrew date at sunset', 'hebdate');?>	–
		<?php _e('latitude', 'hebdate')?>: <input type="text" name="latitude" value="<?php echo get_option('latitude')?>" onfocus="sunset.checked=true" size="6">
		<?php _e('longitude', 'hebdate')?>: <input type="text" name="longitude" value="<?php echo get_option('longitude')?>" onfocus="sunset.checked=true" size="6">
		<p><?php printf(__('You can to find the longitude and the latitude via %s, via %s or via %s.', 'hebdate'),
			'<a href="https://maps.google.com/">'.__('Google maps','hebdate').'</a>',
			'<a href="https://whatsmylatlng.com/">whatsmylatlng</a>',
			'<a href="https://www.latlong.net/">LatLong.net</a>')?>
		</p>
		</td>
		<tr valign="top">
		<th scope="row"><?php _e('Current Hebrew date', 'hebdate')?></th>
		<td>
		<?php printf(__('To display today\'s Hebrew date, add the widget or shortcode %s', 'hebdate'), '<code>[today_hebdate]</code>');?>
		</td></tr>
		</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes');?>" />
			</p>
		</form>
		</div><?php
	}

	// get Julian date and return true if its happen in leap hebrew year
	function hasLeapYear($juldate) {
		$hebdate = jdtojewish($juldate);
		$hebyear = explode('/', $hebdate)[2];
		return apply_filters('hebdate_hasLeapYear', jewishtojd(6, 1, $hebyear) != jewishtojd(7, 1, $hebyear));
	}

	//if value is empty init its to default
	function init_hebdate(){
		if (get_option('hebdate_lang') == ''){
			update_option('hebdate_lang', 'hebrew');
		}
		if ($this->hebdate_format() == ''){
			update_option('hebdate_format', 'heb (greg)');
		}
		if (get_option('latitude')==''){
			update_option('latitude', '31.776804');
		}
		if (get_option('longitude')==''){
			update_option('longitude', '35.222282');
		}
	}

	//if the time after the sunset return tommrow
	function sunset($date){
		$date = mysql2date("H-i-s-j-n-Y", $date);
		list($hour, $min, $sec, $day, $mon, $year) = explode('-', $date);
		$suninfo = date_sun_info(mktime($hour, $min, 0, $day, $mon, $year), get_option('latitude'), get_option('longitude'));
		
		if (! $suninfo){
			return $date;
		}

		$sunset = date('H:i', $suninfo['sunset']);
		list($sunset_h, $sunset_m) = explode(':', $sunset);

		if ($hour > $sunset_h || ($hour == $sunset_h && $min > $sunset_m)){
			$date = gregoriantojd($mon, $day, $year) + 1;
			$date = jdtogregorian($date);
			list($mon, $day, $year) = explode('/', $date);
		}
		$date = $hour . ':' . $min . ':' . $sec . ' ' . $year . '-' . $mon . '-' . $day;
		
		return apply_filters('hebdate_sunset', $date);
	}

	// add options to menu
	function hebdate_admin() {
		add_options_page(__('Hebrew Date Options', 'hebdate'), __('Hebrew Date', 'hebdate'), 'manage_options', 'wordpress-hebrew-date', [$this, 'hebdate_options']);
		add_action( 'admin_init', [$this, 'register_settings']);
	}

	// register settings
	function register_settings(){
		$settings = array('hebdate_lang', 'hebdate_format', 'hebdate_format_custom', 'hebdate_hide_alafim', 'hebdate_sunset', 'latitude', 'longitude');
		foreach ($settings as $setting)
			register_setting('hebdate_settings', $setting);
	}

	function register_hebdate_widget() {
		register_widget( 'Hebdate_Widget' );
	}
}

class Hebdate_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'hebdate_widget',
			__( 'Hebrew Date', 'hebdate' ),
			array( 'description' => __( 'Show hebrew date of today', 'hebdate' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		echo format(hebdate_format(), return_today_hebDate(), date(get_option('date_format')));
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Today', 'hebdate' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}

$hebdate = new Hebdate();

require_once __DIR__ . '/old_functions.php';