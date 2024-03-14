<?php
   /*
   Plugin Name: Shamor
   Plugin URI: https://wpshamor.com/
   description: A plugin to redirect user out of your site on Shabbat and Holiday.
   Version: 1.8.1
   Author: wpshamor.com
   Author URI: https://wpshamor.com/
   */

defined( 'ABSPATH' ) or die( 'No access' );

require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;

class Shamor {

	private const CANDLE_BEFORE_SUNSET = 18;
	private const HAVDALAH_AFTER_SUNSET = 50;

	private $http_status;

	function __construct(){
		if (! extension_loaded('calendar')){
			add_action('admin_notices', [$this, 'show_admin_error']);
			return;
		}
		
		add_filter('template_include', [$this, 'move_out_of_site'], 9999);
		add_filter('status_header', [$this, 'capture_status'], 10, 2);
		add_action('admin_menu', [$this, 'shamor_plugin_menu']);
		add_action('wp_enqueue_scripts', [$this, 'wp_shammor_enqueue']);
		add_action('wp_ajax_validate_wp_shammor', [$this, 'validate_wp_shammor']);
		add_action('wp_ajax_nopriv_validate_wp_shammor', [$this, 'validate_wp_shammor']);
		add_shortcode('wp_shammor_countdown', [$this, 'wp_shammor_countdown']);
		add_shortcode('wp_shamor_havdalah_hour', [$this, 'get_havdalah_hour']);
	}

	function show_admin_error(){
		printf('<div class="notice notice-error"><p>%s</p></div>', __('WP Shamor requires the PHP calendar extension to be activated on the server to work properly.', 'wp-shamor'));
	}

	function get_location_data_from_ip(){
		$ip = $this->get_client_ip();

		if (! $ip){
			return apply_filters('shamor_location_data_from_ip', false);
		}

		$reader = new Reader(__DIR__ . '/db/GeoLite2-City.mmdb');
		$record = $reader->city($ip);

		$this->country = $record->country->isoCode;

		return apply_filters('shamor_location_data_from_ip', $record->location);
	}

	function get_shabbat_times(){
		$this->location = $this->get_location_data_from_ip();

		if (! $this->location){
			return apply_filters('shamor_shabbat_times', false);
		}

		$dt = new DateTime("now", new DateTimeZone($this->location->timeZone));
		$this->location->weekday = $dt->format('l');

		$sunset = date_sun_info(strtotime($this->location->weekday), $this->location->latitude, $this->location->longitude)['sunset'];
		$candle_lighting = $sunset - SELF::CANDLE_BEFORE_SUNSET * 60;
		$havdalah = $sunset + SELF::HAVDALAH_AFTER_SUNSET * 60;

		$start_time = $this->get_time_option('shamor_start_time') ?: '0';
		$end_time = $this->get_time_option('shamor_end_time') ?: '0';

		$candle_lighting = strtotime("-$start_time min", $candle_lighting);
		$havdalah = strtotime("+$end_time min", $havdalah);

		$times = [
			'candle_lighting' => $candle_lighting,
			'havdalah' => $havdalah,
		];

		return apply_filters('shamor_shabbat_times', $times);
	}

	const YAMIM_TOVIM = [
		'טו ניסן',
		'כא ניסן',
		'ו סיון',
		'א תשרי',
		'ב תשרי',
		'י תשרי',
		'טו תשרי',
		'כב תשרי',
	];
	const ISRUCHAG = [
		'טז ניסן',
		'כב ניסן',
		'ז סיון',
		'טז תשרי',
		'כג תשרי',
	];

	function is_yom_tov(){
		$hebdate = $this->get_hebdate();
		return apply_filters('shamor_is_yom_tov', in_array($hebdate, $this->get_yamim_tovim()));
	}

	function is_erev_yom_tov($days = 0){
		$days++;
		$hebdate = $this->get_hebdate("+$days days");
		return apply_filters('shamor_is_erev_yom_tov', in_array($hebdate, $this->get_yamim_tovim()));
	}

	function get_yamim_tovim(){
		$yamim_tovim = SELF::YAMIM_TOVIM;
		
		if ($this->country != 'IL'){
			$yamim_tovim = array_merge($yamim_tovim, SELF::ISRUCHAG);
		}

		return apply_filters('shamor_yamim_tovim', $yamim_tovim);
	}

	function get_hebdate($str = 'now'){
		$juldate = gregoriantojd(...explode('/', date('m/d/Y', strtotime($this->location->weekday . " $str"))));
		$hebdate = jdtojewish($juldate, true);
		$hebdate = iconv("windows-1255", "UTF-8", $hebdate);

		$hebdate = explode(' ' , $hebdate);
		$hebdate = "{$hebdate[0]} {$hebdate[1]}";

		return apply_filters('shamor_get_hebdate', $hebdate);
	}

	function plugin_action_links($links) {
		$settings_link = '<a href="' . admin_url('admin.php?page=' . basename(__DIR__) . '/' . basename(__FILE__)) . '" title="' . __('הגדרות', 'wp-shamor') . '">' . __('הגדרות', 'wp-shamor') . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	function language_redirect($template) {
		global $q_config;
		$new_template = locate_template( array( 'page-'.$q_config['lang'].'.php' ) );
		if ( '' != $new_template ) {
			return $new_template ;
		}
		return $template;
	}

	function block_site($template){
		if(isset( $_GET['wp_shamor'] )){
			add_action( 'wp_enqueue_scripts', [$this, 'load_elementor_css']);
			return trailingslashit(plugin_dir_path(__FILE__)) . 'block_template.php';
		}

		return $template;
	}

	function move_out_of_site($template = false){

		$times = $this->get_shabbat_times();
		
		if (! empty($_GET['wp_shamor']) || (! $times) || (($this->location->weekday == 'Friday' || $this->is_erev_yom_tov()) && time() > $times['candle_lighting']) || (($this->location->weekday == 'Saturday' || $this->is_yom_tov()) && time() < $times['havdalah'])){

			if (wp_doing_ajax()) {
				return true;
			}

			$status = 'blocked';
			$this->shamor_site_get_headers_503($times['havdalah']);
			add_action( 'wp_enqueue_scripts', [$this, 'load_elementor_css']);
			$template = __DIR__ . '/block_template.php';
		}
		else {
			$status = 'opened';
		}

		$this->check_clean_cache($status);
		return $template;
	}

	function load_elementor_css(){
		$template_id = get_option('shamor_display_template');

		if (! $template_id){
			return;
		}
		if(class_exists('\Elementor\Plugin')){
			$elementor =  \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}
		if(class_exists('\ElementorPro\Plugin')){
			$elementor =  \ElementorPro\Plugin::instance();
			$elementor->enqueue_styles();
		}
		if(class_exists('\Elementor\Core\Files\CSS\Post')){
			$css_file = new \Elementor\Core\Files\CSS\Post($template_id);
			$css_file->enqueue();
		}
	}

	function get_client_ip()
	{
		$ipaddress = '';
		$params = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];
		foreach ($params as $param){
			if (isset($_SERVER[$param]) && rest_is_ip_address($_SERVER[$param])) {
				$ipaddress = filter_var($_SERVER[$param], FILTER_VALIDATE_IP);
				break;
			}
		}

		return $ipaddress;
	}

	function shamor_plugin_menu() {
		add_menu_page('WP Shamor', 'WP Shamor', 'administrator', __FILE__, [$this, 'shamor_plugin_settings_page']);
		add_action('admin_init', [$this, 'register_shamor_plugin_settings']);
		add_filter(
		'plugin_action_links_' . plugin_basename(__FILE__),
		[$this, 'plugin_action_links']
		);
	}

	function register_shamor_plugin_settings() {
		register_setting( 'shamor-plugin-settings-group', 'shamor_start_time' );
		register_setting( 'shamor-plugin-settings-group', 'shamor_end_time' );
		register_setting( 'shamor-plugin-settings-group', 'shamor_display_text' );
		register_setting( 'shamor-plugin-settings-group', 'shamor_display_template' );
	}

	function shamor_plugin_settings_page() {
	?>
		<div class="wrap">
		<h1>הגדרות WP Shamor</h1>
			<div>
				<a href="<?php home_url();?>/?wp_shamor=preview" target="_blank">לחצו כאן כדי לראות את דף החסימה שיוצג בשבתות וחגים</a>
			</div>
		<form method="post" action="options.php">
			<?php settings_fields( 'shamor-plugin-settings-group' ); ?>
			<?php do_settings_sections( 'shamor-plugin-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">הגדירו כמה דקות לפני שבת האתר יחסם:</th>
					<th scope="row">זמן כניסת שבת הוא 18 דקות לפני השקיעה, תוכלו להוסיף דקות נוספות על זמן זה</th>			
					<td><input type="number" name="shamor_start_time" size="3" value="<?php echo esc_attr( $this->get_time_option('shamor_start_time') ); ?>" /></td>
				</tr>			
				<tr valign="bottom">
					<th scope="row">הגדירו כמה דקות אחרי שבת האתר יפתח:</th>		
					<th scope="row">זמן יציאת שבת הוא 50 דקות אחרי השקיעה, תוכלו להוסיף דקות נוספות על זמן זה</th>				
					<td><input type="number" name="shamor_end_time" size="3" value="<?php echo esc_attr( $this->get_time_option('shamor_end_time') ); ?>" /></td>
				</tr>			
				<tr valign="bottom">
					<th scope="row">הגדירו את הטקסט היוצג בדף החסימה:</th>		
					<th scope="row">טקסט זה יוצג לגולשים בזמן שהאתר יהיה חסום. אם תשאירו שדה זה ריק - תופיע הודעת ברירת מחדל.</th>				
					<td><?php wp_editor(get_option('shamor_display_text'), 'shamor_display_text', ['textarea_name' => 'shamor_display_text', 'editor_height' => 190]);?></td>
				</tr>
				<?php if (shortcode_exists('elementor-template')):?>
				<tr valign="bottom">
					<th scope="row">או לחילופין בחרו את הטמפלייט  שיוצג בדף החסימה (ממאגר הטמפלייטים של אלמנטור הנמצאים באתר שלכם):</th>
					<th>הקפידו לבחור טמפלייט ללא אפשרות גלילה, וללא אפשרות שום פעולה כדי שלא יגרם חילול שבת ח"ו</th>
					<td><select name="shamor_display_template" id="shamor_display_template">
							<option value>--ללא--</option>
							<?php 
								$query_args = array(
									'posts_per_page' => '-1',
									'post_type' => 'elementor_library',
									'post_status' => 'publish'
								);
								$the_query = new WP_Query( $query_args );
								if ( $the_query->have_posts() ) {
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										echo '<option value="' . get_the_ID() .'"';
										if(get_option('shamor_display_template') == get_the_ID())
											echo 'selected';
										echo '>' . get_the_title() . '</option>';
									}
									wp_reset_postdata();
								} 
							?>
					</select></td>
				</tr>
				<?php endif;?>
				<tr>
					<td colspan="3">
						טיפ: בטמפלייט החסימה ניתן לשלב את השורטקוד <code>[wp_shammor_countdown]</code> כדי להציג סטופר המראה עוד כמה זמן יפתח האתר מחדש. או את השורטקוד <code>[wp_shamor_havdalah_hour]</code> להצגת שעת הפתיחה.
					</td>
				</tr>
			</table>
			
			<?php submit_button(); ?>

		</form>
		</div>
	<?php 
	}

	function _print_shammor_page() {
		?>
	<!DOCTYPE html>
	<html dir="rtl" lang="he-IL">
		<head>
			<title>אתר סגור בשבתות וחגים</title>
		</head>
		<body>
			<?php 
				if (empty(get_option('shamor_display_template'))) {
					echo '<div style="text-align: center; padding: 100px;"><h1>';
					echo esc_html(get_option('shamor_display_text')); 
					echo '</h1><div>';
				}
				else {
					echo do_shortcode('[elementor-template id="' . esc_html(get_option('shamor_display_template')) . '"]');
				}
			?>
		</body>
	</html>
		
		<?php
	}	

	function shamor_site_get_headers_503($date_end = ''){
		if ($this->http_status != 200){
			return;
		}
		nocache_headers();
		$protocol = 'HTTP/1.0';
		if (isset($_SERVER['SERVER_PROTOCOL']) && 'HTTP/1.1' === $_SERVER['SERVER_PROTOCOL']) {
			$protocol = 'HTTP/1.1';
		}
		header("$protocol 503 Service Unavailable", true, 503);
		if ($date_end != ''){
			header('Retry-After: ' . gmdate('D, d M Y H:i:s', $date_end));
		}
	}

	function wp_shammor_enqueue($hook) {
		wp_enqueue_script( 'ajax-script', plugins_url( 'script.js', __FILE__ ), array('jquery') );
		wp_localize_script( 'ajax-script', 'ajax_object',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	}

	function validate_wp_shammor() {
		$blocked = $this->move_out_of_site();
		wp_send_json_success(['blocked' => $blocked]);
	}

	function wp_shammor_countdown($atts) {
		$result = '<div class="shamor_countdown" style="direction:ltr; font-size: 60px;">';
		$havdalah = $this->get_havdalah_time();
		$result .= '<span id="shammor_countdown_hours">';
		$hours = $havdalah[0];
		if($hours < 0)
			$hours = 0;
		if($hours < 10)
			$result .= '0';
		$result .= $hours . '</span> : <span id="shammor_countdown_minutes">';
		$minutes = $havdalah[1];
		if($minutes < 0)
			$minutes = 0;
		if($minutes < 10)
			$result .= '0';
		$result .=  $minutes . '</span> : <span id="shammor_countdown_seconds">';
		$seconds = $havdalah[2];
		if($seconds < 0)
			$seconds = 0;
		if($seconds < 10)
			$result .= '0';
		$result .= $seconds . '</span>';
		$result .= '</div>
					<script>
						hours = ' . $hours .';
						minutes = ' . $minutes . ';
						seconds = ' . $seconds . ';
						setInterval(function() {
							if(seconds > 0) {
								seconds--;
							} else if(minutes > 0) {
								minutes --;
								seconds = 59;
							} else if(hours > 0) {
								hours --;
								minues = 59;
								seconds = 59;
							}
							document.getElementById("shammor_countdown_hours").innerHTML = ((hours < 10 ? "0" : "") + hours);
							document.getElementById("shammor_countdown_minutes").innerHTML = ((minutes < 10 ? "0" : "") + minutes);
							document.getElementById("shammor_countdown_seconds").innerHTML = ((seconds < 10 ? "0" : "") + seconds);
						}, 1000); 
					</script>';
		return $result;
	}

	function get_havdalah_time() {
		$times = $this->get_shabbat_times();
		$havdalah = $times['havdalah'];
		
		$seconds = $havdalah - time();
		
		$days = 0;
		while (date('l', strtotime("+$days days")) == 'Friday' || $this->is_erev_yom_tov($days)){
			$days++;
		}

		$hours = $days * 24 + intdiv($seconds ,3600);
		$hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
		$time = $hours . ':' . gmdate('i:s', $seconds);

		return apply_filters('shamor_havdalah_time', explode(':', $time));
	}

	function get_havdalah_hour(){
		$times = $this->get_shabbat_times();
		$havdalah = $times['havdalah'];
		$dt = new DateTime("@$havdalah");
		$dt->setTimezone(new DateTimeZone($this->location->timeZone));
		$havdalah_hour = $dt->format('H:i');

		return apply_filters('shamor_get_havdalah_hour', $havdalah_hour);
	}

	function get_time_option($name = 'shamor_start_time'){
		// support old time format
		$option = get_option($name);
		if ($option && strpos($option, ':')){
			$option = strtotime("1970-01-01 0:$option");
		}
		return $option;
	}

	function clear_all_caches(){
		do_action('shamor_clear_all_caches');
		$cache_functions = array(
			'wp_cache_clear_cache',           // WP Super Cache
			'w3tc_pgcache_flush',             // W3 Total Cache
			'rocket_clean_domain',            // WP Rocket
			'sg_cachepress_purge_cache',      // SG Optimizer (SiteGround)
			'breeze_clear_all_cache',         // Breeze (By Cloudways)
			'wphb_clear_cache'                // Hummingbird Cache
		);
	
		foreach ($cache_functions as $function){
			if (function_exists($function)) {
				$function();
			}
		}
	
		// For plugins where we need to check for class methods:
		if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
			$GLOBALS['wp_fastest_cache']->deleteCache();
		}
	
		if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
			LiteSpeed_Cache_API::purge_all();
		}
	
		if (class_exists('comet_cache')) {
			comet_cache::clear();
		}
	
		if (has_action('ce_clear_cache')) {
			do_action('ce_clear_cache');  // Cache Enabler
		}

		// uPress EzCache
		if (class_exists('Upress\EzCache\Cache') && method_exists('Upress\EzCache\Cache', 'clear_cache')) {
			Upress\EzCache\Cache::instance()->clear_cache();
		}

		// Cloudflare Cache Clearing
		if (class_exists('\CF\WordPress\Hooks')) {
			$cloudflareHooks = new \CF\WordPress\Hooks();
			$cloudflareHooks->purgeCacheEverything();
		}
	
		// Clear WordPress Internal Cache
		wp_cache_flush();
	}

	function check_clean_cache($status){
		// Get the currently saved status
		$saved_status = get_option('shamor_cache_status');

		if ($saved_status !== $status) {
			$this->clear_all_caches();
			update_option('shamor_cache_status', $status);
		}
	}

	function capture_status($status_header, $code) {
        $this->http_status = $code;
        return $status_header; // return unchanged
    }
	
}

$shamor = new Shamor();
