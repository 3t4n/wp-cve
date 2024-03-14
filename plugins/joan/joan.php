<?php
/*
Plugin Name:  Jock On Air Now
Plugin URI: https://wordpress.org/plugins/joan/
Description: Easily manage your station's on air schedule and share it with your website visitors and listeners using Jock On Air Now (JOAN). Use the widget to display the current show/Jock on air, display full station schedule by inserting the included shortcode into any post or page. Your site visitors can then keep track of your on air schedule.
Author: G &amp; D Enterprises, Inc.
Version: 5.7.9
Author URI: https://www.gandenterprisesinc.com
Text Domain: joan
Domain Path: /languages
*/

if (!defined('ABSPATH')) { 
	exit("Sorry, you are not allowed to access this page directly."); 
}
function joan_init_languages() {
    $plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages'; /* Relative to WP_PLUGIN_DIR */
    load_plugin_textdomain( 'joan', false, $plugin_rel_path );
}
add_action('plugins_loaded', 'joan_init_languages');
/* Set constant for plugin directory */
define( 'SS3_URL', WP_PLUGIN_URL.'/joan' );

$joan_db_version = "3.1.1";

if (!isset($wpdb)) 
	$wpdb = $GLOBALS['wpdb'];

$joanTable = $wpdb->prefix . "WPJoan";

//getting the default timezone
$get_tz = date_default_timezone_get();

if(!function_exists('get_current_timezone')){
	function get_current_timezone(){
		$tzstring = get_option( 'timezone_string' );
		if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists.
			$current_offset = get_option( 'gmt_offset' );		
			if ( 0 == $current_offset ) {
				$tzstring = 'Etc/GMT+0';
			}
			elseif ( $current_offset < 0 ) {
				$tzstring = 'Etc/GMT' . $current_offset;
			}
			else {
				$tzstring = 'Etc/GMT+' . $current_offset;
			}
			
			return $tzstring;

		}
		else{
			return $tzstring;
		}	
	}
}

if(!function_exists('wp_strtotime')){
	function wp_strtotime( $str ) {
		$tz_string = get_option('timezone_string');
		$tz_offset = get_option('gmt_offset', 0);
		
		if (!empty($tz_string)) {
			$timezone = $tz_string;
		}
		elseif ($tz_offset == 0) {
			$timezone = 'UTC';
		}
		else {
			$timezone = $tz_offset;
			if(substr($tz_offset, 0, 1) != "-" && substr($tz_offset, 0, 1) != "+" && substr($tz_offset, 0, 1) != "U") {
				$timezone = "+" . $tz_offset;
			}
		}
		
		$datetime = new DateTime($str, new DateTimeZone($timezone));
		return $datetime->format('U');
	}
}

if(!function_exists('get_joan_day_name')){
	function get_joan_day_name( $id = 0 ){
		$days = array(
			0 => 'Sunday',
			1 => 'Monday',
			2 => 'Tuesday',
			3 => 'Wednesday',
			4 => 'Thursday',
			5 => 'Friday',
			6 => 'Saturday'
		);
		
		return $days[ $id ];
	}
}

if ( ! function_exists('day_to_string') ) {
	function day_to_string( $dayName = '', $Time = 0 ){
		switch( $dayName ){
			case 'Sunday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 1, 1982");
			break;
			
			case 'Monday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 2, 1982");
			break;

			case 'Tuesday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 3, 1982");
			break;

			case 'Wednesday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 4, 1982");
			break;

			case 'Thursday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 5, 1982");
			break;

			case 'Friday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 6, 1982");
			break;

			case 'Saturday':
				$timestring = strtotime( $dayName . ", " . $Time . " August 7, 1982");
			break;

			default:
				$timestring = strtotime( "August 1, 1982" );
			break;
		}
		
		return $timestring;
	}
}

//Installation
function joan_install() {

   global $wpdb;
   global $joan_db_version;
   global $joanTable;

   $joanTable = $wpdb->prefix . "WPJoan";

   	if($wpdb->get_var("show tables like '$joanTable'") != $joanTable) {
      
      	$sql = "CREATE TABLE " . $joanTable . " (
		  id int(9) NOT NULL AUTO_INCREMENT,
		  dayOfTheWeek text NOT NULL,
		  startTime int(11) NOT NULL,
		  endTime int(11) NOT NULL,
		  startClock text not null,
		  endClock text not null,
		  showName text NOT NULL,
		  linkURL text NOT null,
		  imageURL text not null,
		  UNIQUE KEY id (id)
		);";

      	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      	dbDelta($sql);
 
      	add_option("joan_db_version", $joan_db_version);
   }
}

register_activation_hook(__FILE__,'joan_install'); 
/* uninstall function */
function joan_uninstall(){
	global $wpdb;
	$joanTable = $wpdb->prefix . "WPJoan";
	delete_option("joan_db_version");
   	$sql =  "DROP TABLE IF EXISTS $joanTable";
    $wpdb->query($sql);
}
register_uninstall_hook( __FILE__, 'joan_uninstall' );
//Register and create the Widget

class JoanWidget extends WP_Widget {
 	
 	/**
  	* Declares the JoanWidget class.
 	*
  	*/
    function __construct(){
    	$widget_ops = array('classname' => 'joan_widget', 'description' => __( "Display your schedule with style.",'joan') );
    	$control_ops = array('width' => 300, 'height' => 300);
		parent::__construct( 'Joan', __( 'Joan', 'joan' ), $widget_ops, $control_ops );
    }

  	/**
    * Displays the Widget
    *
    */
    function widget($args, $instance){
	      
	    extract($args);
	    $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

	    # Before the widget
	    echo $before_widget;

	    # The title
	    if ( $title ){
	    echo $before_title . $title . $after_title;
	    }
	    # Make the Joan widget
	    echo showme_joan();

	    # After the widget
	    echo $after_widget;
	}

  	/**
    * Saves the widgets settings.
    *
    */
    function update($new_instance, $old_instance){
      	$instance = $old_instance;
      	$instance['title'] = strip_tags(stripslashes($new_instance['title']));
      	$instance['lineOne'] = strip_tags(stripslashes($new_instance['lineOne']));
      	$instance['lineTwo'] = strip_tags(stripslashes($new_instance['lineTwo']));

    	return $instance;
  	}

  	/**
    * Creates the edit form for the widget.
    *
    */
    function form($instance){
      //Defaults
      $instance = wp_parse_args( (array) $instance, array('title'=>__('On Air Now','joan')) );

      $title = htmlspecialchars($instance['title']);

      # Output the options
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
    }
} //End of widget


function JoanInit() {
	register_widget('JoanWidget');
}
add_action('widgets_init', 'JoanInit'); 

function joan_default_options(){
    //==== OPTIONS ====
    add_option('joan_upcoming', 'yes');
    add_option('joan_use_images', 'yes');
    add_option('joanjoan_upcoming_shutitdown', 'no');
    add_option('off_air_message', __('We are currently off the air.','joan'));
    add_option('joan_css', '
		.joan-container h2{
		    font-family:Roboto;
		    font-size:24px;
		}
		.joan-schedule, .joan-schedule *{
			font-family:Roboto;
		    font-size:16px;
		}
		.joan-widget, .joan-widget *{
			font-family:Roboto;
		    font-size:16px;
		}
		.joan-now-playing {
			font-family:Roboto;
		    font-size:16px;
		}
 	
    	.joan-container * {
		  	font-family:Roboto;
		  	font-size:16px;
		}
    ');
    add_option('joan_shutitdown', 'no');
}
register_activation_hook(__FILE__,'joan_default_options');
function joan_deactivation_hook(){
	delete_option( 'joan_upcoming' );
	delete_option( 'joan_use_images' );
	delete_option( 'joanjoan_upcoming_shutitdown' );
	delete_option( 'off_air_message' );
	delete_option( 'joan_css' );
	delete_option( 'joan_shutitdown' );
}

register_deactivation_hook( __FILE__, 'joan_deactivation_hook' );

function elementor_joan_widget(){

		class Elementor_Joan_Widget extends \Elementor\Widget_Base {

			/**
			 * Get widget name.
			 *
			 * Retrieve oEmbed widget name.
			 *
			 * @since 1.0.0
			 * @access public
			 *
			 * @return string Widget name.
			 */
			public function get_name() {
				return 'joan';
			}

			/**
			 * Get widget title.
			 *
			 * Retrieve oEmbed widget title.
			 *
			 * @since 1.0.0
			 * @access public
			 *
			 * @return string Widget title.
			 */
			public function get_title() {
				return __( 'Joke On Air Widget', 'joan' );
			}

			/**
			 * Get widget icon.
			 *
			 * Retrieve oEmbed widget icon.
			 *
			 * @since 1.0.0
			 * @access public
			 *
			 * @return string Widget icon.
			 */
			public function get_icon() {
				return 'fa fa-bars';
			}

			/**
			 * Get widget categories.
			 *
			 * Retrieve the list of categories the oEmbed widget belongs to.
			 *
			 * @since 1.0.0
			 * @access public
			 *
			 * @return array Widget categories.
			 */
			public function get_categories() {
				return [ 'general' ];
			}

			/**
			 * Register oEmbed widget controls.
			 *
			 * Adds different input fields to allow the user to change and customize the widget settings.
			 *
			 * @since 1.0.0
			 * @access protected
			 */
			protected function _register_controls() {

				$this->start_controls_section(
					'content_section',
					[
						'label' => __( 'Content', 'joan' ),
						'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					]
				);

				$this->add_control(
					'title',
					[
						'label' => __( 'Title of the widget', 'joan' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'text',
						'placeholder' => __( 'On Air Now', 'joan' ),
					]
				);
				$this->add_control(
					'heading',
					[
						'label' => __( 'Heading', 'joan' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => [
							'H1' => __( 'H1', 'joan' ),
							'H2' => __( 'H2', 'joan' ),
							'H3' => __( 'H3', 'joan' ),
							'H4' => __( 'H4', 'joan' ),
							'H5' => __( 'H5', 'joan' ),
							'H6' => __( 'H6', 'joan' ),
						 
						],
						'default' => 'H1',
					]
				);
				$this->add_control(
					'text_align',
					[
						'label' => __( 'Alignment', 'joan' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'left' => [
								'title' => __( 'Left', 'joan' ),
								'icon' => 'fa fa-align-left',
							],
							'center' => [
								'title' => __( 'Center', 'joan' ),
								'icon' => 'fa fa-align-center',
							],
							'right' => [
								'title' => __( 'Right', 'joan' ),
								'icon' => 'fa fa-align-right',
							],
						],
						'default' => 'center',
						'toggle' => true,
					]
				);
				$this->end_controls_section();

			}

			/**
			 * Render oEmbed widget output on the frontend.
			 *
			 * Written in PHP and used to generate the final HTML.
			 *
			 * @since 1.0.0
			 * @access protected
			 */
			protected function render() {

				$settings = $this->get_settings_for_display();

				$title = htmlspecialchars( $settings['title'] );
				$heading = $settings['heading'];
				$title = !empty($title) ? $title : 'On Air Now';
				$startHeading = sprintf("<%s>",$heading);
				$endHeading = sprintf("</%s>",$heading);
				$text_align = $settings['text_align'];

				 
				# Before the widget
			    echo sprintf('<div class="joan-widget text-%s">',$text_align);

			    # The title
			    
			    echo $startHeading. $title . $endHeading;
			    # Make the Joan widget
			    echo showme_joan();

			    # After the widget
			    echo '</div>';

			}

		}
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Joan_Widget() );
}

add_action( 'elementor/widgets/widgets_registered','elementor_joan_widget' );
//add_action( 'elementor/frontend/after_enqueue_styles', 'widget_styles' );
add_action( 'elementor/frontend/after_register_scripts', 'joan_header_scripts' );

//==== SHORTCODES ====

function joan_schedule_handler($atts, $content=null, $code=""){
	
if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
	global $wpdb;
	global $joanTable;

	//Get the current schedule, divided into days
	$daysOfTheWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

	$schedule = array();

	$output = '';
    $output .= '<style>'.get_option('joan_css', '').'</style>';

	foreach ($daysOfTheWeek as $day) {
		if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
		//Add this day's shows HTML to the $output array
		$showsForThisDay =  $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM $joanTable WHERE dayOfTheWeek = %s ORDER BY startTime", $day ));

		//Check to make sure this day has shows before saving the header
		if ($showsForThisDay){
			$output .= '<div class="joan-container">';
			$output .= '<h2>'.__($day,'joan').'</h2>';
			$output .= '<ul class="joan-schedule">';
			foreach ($showsForThisDay as $show){
				$showName = $show->showName;
				$startClock = $show->startClock;
				$endClock = $show->endClock;
				$linkURL = $show->linkURL;
				$imageURL = $show->imageURL;
                   
				if ($linkURL){
					$showName = '<a href="'.$linkURL.'">'.$showName.'</a>';
				}
                 
				$output .= '<li><strong>'.$startClock.'</strong> - <strong>'.$endClock.'</strong>: '.$showName.'</li>';

			}
			$output .= '</ul>';
			$output .= '</div>';
		}
	}
	return $output;
}

add_shortcode('joan-schedule', 'joan_schedule_handler');

//Daily schedule
function joan_schedule_today($atts, $content=null, $code=""){

	global $wpdb;
	global $joanTable;

	//Get the current schedule, divided into days
	$daysOfTheWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

        $today = date('l');

	$schedule = array();

	$output = '';
    $output .= '<style>'.get_option('joan_css', '').'</style>';

	foreach ($daysOfTheWeek as $day) {
		//Add this day's shows HTML to the $output array
		$showsForThisDay =  $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM $joanTable WHERE dayOfTheWeek = %s ORDER BY startTime", $day ));

        if ($day == $today) {

		//Check to make sure this day has shows before saving the header
		if ($showsForThisDay){
			$output .= '<div class="joan-container">';
			$output .= '<h2 class="widget-title">Today - '.__($today,'joan').'</h2>';
			$output .= '<ul class="joan-schedule">';
			foreach ($showsForThisDay as $show){
				$showName = $show->showName;
				$startClock = $show->startClock;
				$endClock = $show->endClock;
				$linkURL = $show->linkURL;
				if ($linkURL){
					$showName = '<a href="'.$linkURL.'">'.$showName.'</a>';
				}
				$output .= '<li><span class="show-time">'.$startClock./*' - '.$endClock.*/':</span> <span class="show-name">'.$showName.'</span></li>';
				}
				$output .= '</ul>';
				$output .= '</div>';
			}
		}
	}
	return $output;
}

add_shortcode('schedule-today', 'joan_schedule_today');

//End daily schedule

function showme_joan(){

    $output = '<style>'.get_option('joan_css', '').'</style>';
    $output .= '<div class="joan-now-playing"></div>';
    return $output;

}

add_shortcode('joan-now-playing', 'showme_joan');

function joan_init(){
		add_action('admin_menu', 'joan_plugin_menu');
}
add_action( 'init', 'joan_init');

function joan_image_upload_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('my-upload');
}
 
function joan_image_upload_styles() {
	wp_enqueue_style('thickbox');
}
 
if (isset($_GET['page']) && $_GET['page'] == 'joan_settings') {
add_action('admin_print_scripts', 'joan_image_upload_scripts');
add_action('admin_print_styles', 'joan_image_upload_styles');
}

//==== ADMIN OPTIONS AND SCHEDULE PAGE ====

function joan_plugin_menu() {
	global $pagenow;
    // Add a new submenu under Options:
	add_menu_page('JOAN', 'JOAN', 'activate_plugins', 'joan_settings', 'joan_options_page');

	// Modified by PHP Stack
    if ($pagenow == 'admin.php' && isset($_GET['page'])) {
		if ($_GET['page'] == 'joan_settings') {
		    wp_enqueue_style( "joan-admin",  plugins_url('admin.css', __FILE__) );
		    wp_enqueue_script( "joan-admin", plugins_url('admin.js', __FILE__), array('jquery'), '1.0.0', true );
		}
	}
}

function joan_options_page(){
if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
	global $wpdb;
	global $joanTable;
	//Check to see if the user is upgrading from an old Joan database

	if (isset($_POST['upgrade-database'])){
		if (check_admin_referer('upgrade_joan_database', 'upgrade_joan_database_field')){

			if ($wpdb->get_var("show tables like '$joanTable'") != $joanTable){
				$sql = "CREATE TABLE " . $joanTable . " (
					  id int(9) NOT NULL AUTO_INCREMENT,
					  dayOfTheWeek text NOT NULL,
					  startTime int(11) NOT NULL,
					  endTime int(11) NOT NULL,
					  startClock text not null,
					  endClock text not null,
					  showName text NOT NULL,
					  linkURL text NOT null,
					  imageURL text not null,
					  UNIQUE KEY id (id)
					);";

				      $wpdb->query($sql);
			}
			
			$joanOldTable = $wpdb->prefix.'joan';

			$oldJoanShows = $wpdb->get_results($wpdb->prepare("SELECT id, showstart, showend, showname, linkUrl, imageUrl FROM $joanOldTable WHERE id != %d", -1));
			if ($oldJoanShows){
				foreach ($oldJoanShows as $show){
					$showname = $show->showname;
					$startTime = $show->showstart;
					$endTime = $show->showend;
					$startDay = date('l', $startTime);
					$startClock = date('g:i a', ($startTime));
					$endClock = date('g:i a', ($endTime));
					$linkURL = $show->linkUrl;
					if ($linkURL == 'No link specified.'){
						$linkURL = '';
					}
					$imageURL = $show->imageUrl;

					//Insert the new show into the New Joan Databse
					$wpdb->query( $wpdb->prepare("INSERT INTO $joanTable (dayOfTheWeek, startTime,endTime,startClock, endClock, showName,  imageURL, linkURL) VALUES (%s, %d, %d , %s, %s, %s, %s, %s)", $startDay, $startTime, $endTime, $startClock, $endClock, $showname, $imageURL, $linkURL )	);
				}
			}
		}
		//Remove the old Joan table if the new table has been created
		if($wpdb->get_var("show tables like '$joanTable'") == $joanTable) {
			$wpdb->query("DROP TABLE $joanOldTable");
		}
	}
        
	// echo '<script type="text/javascript" src="'.SS3_URL.'/admin.js" ></script>';

?>
<div id="joanp-header-upgrade-message">
    <p><span class="dashicons dashicons-info"></span>
        <?php _e('Thank you for choosing JOAN Lite, Jock On Air Now (JOAN). But, did you know that you could enjoy even more advanced features by upgrading to JOAN Premium? With JOAN Premium, you\'ll get access to a range of features that are not available in JOAN Lite, including the ability to edit a show\'s timeslot without having to delete the entire show. Moreover, you\'ll benefit from priority support and the ability to share your current show on social media. Don\'t miss out on these amazing features. <b>in JOAN Premium</b>. <a href="https://gandenterprisesinc.com/premium-plugins/" target="_blank"> Upgrade </a>  to JOAN Premium today! </p>','joan');
        ?>
</div>
<div class="wrap">
		<div class="joan-message-window"><?php _e('Message goes here.','joan'); ?></div>
		<h1><?php _e('Jock On Air Now'); ?></h1>
		<p><em><?php _e('Easily manage your station\'s on air schedule and share it with your website visitors and listeners using Jock On Air Now (JOAN). Use the widget to display the current show/Jock on air, display full station schedule by inserting the included shortcode into any post or page. Your site visitors can then keep track of your on air schedule.</em><br /><small>by <a href=\'https://www.gandenterprisesinc.com\' target=\'_blank\'>G &amp; D Enterprises, Inc.</a></small></p>','joan'); ?>
		
<p><style type="text/css">
.tableHeader
{
background: #000;
color: #fff;
display: table-row;
font-weight: bold;
}
.row
{
display: table-row;
}
.column
{
display: table-cell;
border: thin solid #000;
padding: 6px 6px 6px 6px;
}
</style><div class="tableHeader">
<div class="column"><?php _e('Advertisements','joan'); ?></div>
<div class="column"></div>
<div class="column"></div>
</div>
<div class="row">
<div class="column"><a href="https://radiovary.com" target="_blank"><img src="https://ganddservices.com/apps/joan/img/radiovary_ad.png"></a></div>
<div class="column"><a href="https://vouscast.com" target="_blank"><img src="https://ganddservices.com/apps/joan/img/vouscast.png"></a></div>
<div class="column"><a href="https://musidek.com" target="_blank"><img src="https://ganddservices.com/apps/joan/img/musidek.png"></a></div>
</div>
<div class="row">
<div class="column"></div>

</div></p>

		<?php
			//Check to see if Joan 2.0 is installed
		 	$table_name = $wpdb->prefix . "joan";
		   if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
		   	?>
		   		<div class="error">
		   			<form method="post" action="">
		   				<p><strong><?php _e('Previous version of Joan detected.</strong> Be sure to backup your database before performing this upgrade.','joan'); ?> <input type="submit" class="button-primary" value="<?php _e('Upgrade my Joan Database','joan'); ?>" /></p>
		   				<input type="hidden" name="upgrade-database" value=' ' />
						<?php wp_nonce_field('upgrade_joan_database', 'upgrade_joan_database_field'); ?>
		   			</form>
		   		</div>
		   	<?php
		   }

		?>

		<input type="hidden" class="script-src" readonly="readonly" value="<?= esc_url($_SERVER['PHP_SELF']); ?>?page=joan_settings" />
		<?php wp_nonce_field('delete_joan_entry', 'delete_entries_nonce_field'); ?>

		<ul class="tab-navigation">
			<li class="joan-scheudle"><img src="https://ganddservices.com/apps/joan/img/button_schedule.png"></li>
			<li class="joan-options"><img src="https://ganddservices.com/apps/joan/img/button_options.png"></li>
			<li class="shut-it-down"><img src="https://ganddservices.com/apps/joan/img/button_on-off.png"></li>
			<li class="joan-pre"><img src="https://ganddservices.com/apps/joan/img/button_get-premium.png"></li>
		</ul>
		<br /><br />
		<div class="joan-tabs">

			<div class="tab-container" id="joan-schedule">
						
				<h2><?php _e('Schedule: Add New Shows To Schedule or Edit Existing Show Schedule','joan'); ?></h2>				
				<div class="add-new-entry">

					<form id="add-joan-entry" method="post" action="<?php echo get_admin_url()?>admin-ajax.php">
						<input type='hidden' name='action' value='show-time-curd' />
						<div class="set-joan-show-deets">
				            <div class="show-time-container">
					            <h3><?php _e('Show Start','joan'); ?></h3>
					           <label for="start">
						           	<select class="startDay" name="sday">
						          	 	<option value="Sunday"><?php _e('Sunday','joan'); ?></option>
						            	<option value="Monday"><?php _e('Monday','joan'); ?></option>
						            	<option value="Tuesday"><?php _e('Tuesday','joan'); ?></option>
						            	<option value="Wednesday"><?php _e('Wednesday','joan'); ?></option>
						            	<option value="Thursday"><?php _e('Thursday','joan'); ?></option>
						            	<option value="Friday"><?php _e('Friday','joan'); ?></option>
						            	<option value="Saturday"><?php _e('Saturday','joan'); ?></option>
						            </select>
					        	</label> 
					            
					            <label for="starttime">
					            <input id="starttime" class="text" name="startTime" size="5" maxlength="5" type="text" value="00:00" /></label>
				            </div>
							
				            <div class="show-time-container">
					            <h3><?php _e('Show End','joan'); ?></h3> 
					           	<label for="endday">
						           	<select class="endDay" name="eday">
						           		<option value="Sunday"><?php _e('Sunday','joan'); ?></option>
						            	<option value="Monday"><?php _e('Monday','joan'); ?></option>
						            	<option value="Tuesday"><?php _e('Tuesday','joan'); ?></option>
						            	<option value="Wednesday"><?php _e('Wednesday','joan'); ?></option>
						            	<option value="Thursday"><?php _e('Thursday','joan'); ?></option>
						            	<option value="Friday"><?php _e('Friday','joan'); ?></option>
						            	<option value="Saturday"><?php _e('Saturday','joan'); ?></option>
						            </select>
						        </label> 

					            <label for="endtime">
					            <input id="endtime" class="text" name="endTime" size="5" maxlength="5" type="text" value="00:00" /></label>
				             </div>
				             <div class="clr"></div>
				             <p><strong><?php _e('Set WordPress clock to 24/hr time format (Military Time Format) (H:i) i.e. 01:00 = 1 AM, 13:00 = 1 PM','joan'); ?></strong></p>
				             <p>
								<!--Important, set your <a href="/wp-admin/options-general.php">timezone <strong>to a city</strong></a> which matches your local time.(Do NOT Use UTC)<br/>
				             	<small><em>Current timezone: <strong style="color:red;">Set your <a href="options-general.php">timezone</a> city now.</strong></em></small></p><?php echo get_option('timezone_string'); ?></em></small>-->
								<?php echo sprintf(__('Set Your %s based on your State, Province or country. Or use Manual Offset','joan'), '<a href="' . admin_url( 'options-general.php', is_ssl() ) . '">' . __('WordPress Timezone', 'joan') . '</a>');?>
								</p>
				            
						</div>
						
				        <div class="set-joan-show-deets">
				            <label for="showname"><h3><?php _e('Show Details','joan'); ?></h3></label> 
				            <p><?php _e('Name: ','joan'); ?><br/>
				           		<input id="showname" type="text" name="showname" class="show-detail" />
							</p>
							
				            <p><?php _e('Link URL (optional):','joan');?><br />
								<label for="linkUrl">
				            
								<input type="text" name="linkUrl" placeholder="<?php _e('No URL specified.','joan'); ?>" class="show-detail" />
								
							</p>
							
							<p id="primary-image"></p>
							<p><input class="image-url" type="hidden" name="imageUrl" data-target-field-name="new show" value=""/></p>
							<p><input type="button" class="upload-image button" data-target-field="new show" value="<?php _e('Set Jock Image','joan'); ?>" /></p>			
							<img src="" style="display:none;" data-target-field-name="new show" />
							<p><a id="remove-primary-image" href="#"><small><?php _e('Remove Image','joan'); ?></small></a></p>
							
								
				            <input type="submit" class="button-primary" style="cursor: pointer;" value="<?php _e('Add Show','joan'); ?>" />
				            <input type="hidden" name="crud-action" value="create" />
				            <?php wp_nonce_field('add_joan_entry', 'joan_nonce_field'); ?>

				        </div>
				    </form>

					<div class="clr"></div>

				</div>

				<h3><?php _e('Current Schedule','joan'); ?></h3>

				<p><?php _e('Edit Schedule:','joan'); ?> <a href="#" class="display-toggle full-display"><?php _e('Expand','joan'); ?></a> | <a href="#" class="display-toggle simple-display"><?php _e('Retract','joan'); ?></a></p>

				<form method="post" action="<?php echo get_admin_url()?>admin-ajax.php" class="joan-update-shows">
					<input type='hidden' name='action' value='show-time-curd' />
					<div class="joan-schedule loading">
						<div class="sunday-container"><h2><?php _e('Sunday','joan'); ?></h2></div><!-- end this day of the week -->
						<div class="monday-container"><h2><?php _e('Monday','joan'); ?></h2></div><!-- end this day of the week -->
						<div class="tuesday-container"><h2><?php _e('Tuesday','joan'); ?></h2></div><!-- end this day of the week -->
						<div class="wednesday-container"><h2><?php _e('Wednesday','joan'); ?></h2></div><!-- end this day of the week -->
						<div class="thursday-container"><h2><?php _e('Thursday','joan'); ?></h2></div><!-- end this day of the week -->
						<div class="friday-container"><h2><?php _e('Friday','joan'); ?></h2></div><!-- end this day of the week -->
						<div class="saturday-container"><h2><?php _e('Saturday','joan'); ?></h2></div><!-- end this day of the week -->
					</div>
					<input type="hidden" name="crud-action" value="update" />
					<?php wp_nonce_field('save_joan_entries', 'joan_entries_nonce_field'); ?>

				</form>
				
				<p><?php _e('Edit Schedule:','joan'); ?> <a href="#" class="display-toggle full-display"><?php _e('Expand','joan'); ?></a> | <a href="#" class="display-toggle simple-display"><?php _e('Retract','joan'); ?></a></p>
			</div>

			<div class="tab-container" id="joan-options">
				<h2><?php _e('Select Options Below','joan') ?></h2>
				
				<?php
				if (isset( $_POST['update_joan_options'] ) && wp_verify_nonce( $_POST['update_joan_options'], 'joan_options_action' )) {
					//Save posted options
					if (isset($_POST['joan_options'])){ 
						update_option('joan_upcoming', $_POST['joan_options']['showUpcoming']);
						update_option('joan_use_images', $_POST['joan_options']['imagesOn']);
						update_option('off_air_message', htmlentities(stripslashes($_POST['joan_options']['offAirMessage'])));
						update_option('joan_css', htmlentities(stripslashes($_POST['joan_options']['joan_css'])));

					}
					
					if (isset($_POST['shut-it-down'])) {					    
						update_option('joan_shutitdown', $_POST['shut-it-down']);
					}
				}
				
				//Set options variables
				$showUpcoming 	= get_option('joan_upcoming');
				$imagesOn 		= get_option('joan_use_images');
				$shutItDown 	= get_option('joan_shutitdown');
				$offAirMessage 	= get_option('off_air_message');
				$joanCSS 		= get_option('joan_css');

				?>

				<h3><?php _e('Display Images','joan');?></h3>
				<form id="option" method="post" action="">
					<?php wp_nonce_field('joan_options_action', 'update_joan_options'); ?>
					<p><?php _e('Show accompanying images with joans?','joan'); ?></p>
						<label><input type="radio"<?php if($imagesOn == 'yes') { ?> checked="checked"<?php } ?> name="joan_options[imagesOn]" value="yes" /> : <?php _e('Yes','joan'); ?></label><br/>
						<label><input type="radio"<?php if($imagesOn == 'no') { ?> checked="checked"<?php } ?> name="joan_options[imagesOn]" value="no" /> : <?php _e('No','joan'); ?></label><br/>


					<h3><?php _e('Upcoming Timeslot','joan'); ?></h3>
					    
					<p><?php _e('Show the name/time of the next timeslot?','joan'); ?></p>
						<label><input type="radio"<?php if($showUpcoming == 'yes') { ?> checked="checked"<?php } ?> name="joan_options[showUpcoming]" value="yes" /> : <?php _e('Yes','joan'); ?></label><br/>
						<label><input type="radio"<?php if($showUpcoming == 'no') { ?> checked="checked"<?php } ?> name="joan_options[showUpcoming]" value="no" /> : <?php _e('No','joan'); ?></label><br/>


					<h3><?php _e('Custom Message','joan');?></h3>
						<label><?php _e('Message:','joan'); ?><br /><input type="text" id="off-air-message" value="<?= $offAirMessage; ?>" name="joan_options[offAirMessage]" size="40" /></label>
					<h3><?php _e('Custom Style','joan');?></h3>
					<h4><label><?php _e('Use Custom CSS code to change the font, and font size and color of the displayed widget. See Readme for code.','joan'); ?><br /></h>
						<textarea rows="5" style="width: 350px" name="joan_options[joan_css]"><?php echo $joanCSS; ?></textarea>
					</label><p></p>
                    

</label>
	    
				    <p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Changes','joan'); ?>" />
					</p>
				</form>
				<h2><?php _e('Display Shortcodes','joan'); ?></h2>
			
				<h3>[joan-schedule]</h3>
				<p><?php _e('Display a list of the times and names of your events, broken down weekly, example:','joan');?></p>
				<div style="margin-left:30px;">
				<h4><?php _e('Mondays','joan'); ?></h4>
					<ul>
						<li><strong>5:00 am - 10:00 am</strong> - Morning Ride</li>
						<li><strong>10:00 am - 12:00 pm</strong> - The Vibe with MarkD</li>
					</ul>
					<h4><?php _e('Saturdays','joan'); ?></h4>
					<ul>
						<li><strong>10:00 am - 1:00 am</strong> - Drive Time</li>
						<li><strong>1:00 pm - 4:00 pm</strong> - Kool Jamz</li>
					</ul>
				</div>
				<h3>[joan-now-playing]</h3>
				<p><?php _e('Display the Current Show/jock widget.','joan'); ?></p>
				<h3>[schedule-today]</h3>
				<p><?php _e('Display your schedule for each day of the week.','joan'); ?></p>			
				
				<div class="clr"></div>
			</div>

			<div class="tab-container" id="joan-shut-it-down">
				
				<h2><?php _e('Suspend schedule','joan'); ?></h2>
				<form method="post" action="">
					<p><?php _e('You can temporarily take down your schedule for any reason, during schedule updates, public holidays or station off-air periods etc.','joan'); ?></p>
				    	<label><input type="radio"<?php echo ($shutItDown == 'yes' ? 'checked' : '' ); ?> name="shut-it-down" value="yes" /> : <?php _e('Schedule Off','joan'); ?></label><br/>
						<label><input type="radio"<?php echo ($shutItDown == 'no' ? 'checked' : '' ); ?> name="shut-it-down" value="no" /> : <?php _e('Schedule On (Default)','joan'); ?></label><br/>
				    
				    <p class="submit">
				    	<input type="submit" class="button-primary" value="<?php _e('Save changes'); ?>" />
					</p>
					<?php wp_nonce_field('joan_options_action', 'update_joan_options'); ?>
			 	</form>			

			</div>
			
			<div class="tab-container" id="joan-pre">
				
<style type="text/css">
.tableHeader
{
background: #000;
color: #fff;
display: table-row;
font-weight: bold;
}
.row
{
display: table-row;
}
.column
{
display: table-cell;
border: thin solid #000;
padding: 6px 6px 6px 6px;
}
</style><div class="tableHeader">
<div class="column"><?php _e('JOAN Premium, Premium Features, Priority Support.','joan'); ?></div>

</div>
<div class="row">
<div class="column"><p><a href="https://gandenterprisesinc.com/premium-plugins/" target="_blank"><h2><?php _e('Upgrade to JOAN PREMIUM Today','joan'); ?></h2></a></p>
				<p><img src="https://ganddservices.com/apps/joan/img/featured_img.jpg"></p></div>
</div>
   </div>	
      </div><!-- end the joan tabs -->

	</div>

<?php

}

function joan_header_scripts(){
	echo '<script>crudScriptURL = "'.get_admin_url().'admin-ajax.php"</script>';
	wp_enqueue_script( "joan-front", plugins_url('joan.js', __FILE__), array('jquery'), '1.2.0', true );
}
add_action("wp_head","joan_header_scripts");

function _handle_form_action(){
    
    include( plugin_dir_path( __FILE__ ) . 'crud.php' );

    die(); // this is required to return a proper result
}
add_action('wp_ajax_show-time-curd', '_handle_form_action');
add_action('wp_ajax_nopriv_show-time-curd', '_handle_form_action');