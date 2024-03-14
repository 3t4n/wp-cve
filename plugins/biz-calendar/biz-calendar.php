<?php
/*
 Plugin Name: Biz Calendar
Plugin URI: http://residentbird.main.jp/bizplugin/
Description: 営業日・イベントカレンダーをウィジェットに表示するプラグインです。
Version: 2.2.0
Author:Hideki Tanaka
Author URI: http://residentbird.main.jp/bizplugin/
*/

include_once ( dirname(__FILE__) . "/admin-ui.php" );
new BizCalendarPlugin();

class BC
{
	const VERSION = "2.2.0";
	const SHORTCODE = "showpostlist";
	const OPTIONS = "bizcalendar_options";
	const NATIONAL_HOLIDAY = "biz_national_holiday";

	public static function get_option(){
		return get_option(self::OPTIONS);
	}

	public static function get_national_holiday(){
		return get_option(self::NATIONAL_HOLIDAY);
	}

	public static function update_option( $options ){
		update_option(self::OPTIONS, $options);
	}

	public static function update_national_holiday( $options ){
		update_option(self::NATIONAL_HOLIDAY, $options);
	}

	public static function enqueue_css_js(){
		wp_enqueue_style('biz-cal-style', plugins_url('biz-cal.css', __FILE__ ), array(), self::VERSION);
		wp_enqueue_script('biz-cal-script', plugins_url('calendar.js', __FILE__ ), array('jquery'), self::VERSION );
	}

	public static function localize_js(){
		$option = self::get_option();
		$option["plugindir"] = plugin_dir_url( __FILE__ );
		$nh= self::get_national_holiday();
		$option["national_holiday"] = $nh["national_holiday"];
		wp_localize_script( 'biz-cal-script', 'bizcalOptions', $option );
	}

	public static function enqueue_admin_js(){
		wp_enqueue_script( 'biz-cal-admin-js', plugins_url('upload-holidays.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
		wp_localize_script( 'biz-cal-admin-js', 'bizcalAjax', array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'action' => 'upload_holidays',
		));
	}
}

/**
 * プラグイン本体
 */
class BizCalendarPlugin{

	var $option_name = 'bizcalendar_options';
	var $adminUi;

	public function __construct(){
		register_activation_hook(__FILE__, array(&$this,'on_activation'));
		add_action( 'admin_init', array(&$this,'on_admin_init') );
		add_action( 'admin_menu', array(&$this, 'on_admin_menu'));
		add_action( 'wp_enqueue_scripts', array(&$this,'on_enqueue_scripts'));
		add_action( 'wp_ajax_upload_holidays', array(&$this,'upload_holidays') );
		add_action( 'widgets_init', create_function( '', 'register_widget( "bizcalendarwidget" );' ) );
	}

	function on_activation() {
		$option = BC::get_option();
		if( !is_array( $option ) ){
			$arr = array(
					"holiday_title" => "定休日",
					"eventday_title" => "イベント開催日",
					"sun" => "on",
					"mon" => "",
					"tue" => "",
					"wed" => "",
					"thu" => "",
					"fri" => "",
					"sat" => "on",
					"holiday" => "on",
					"temp_holidays" =>"2013-01-02\n2013-01-03\n",
					"temp_weekdays" =>"",
					"eventdays" =>"",
					"event_url" =>"",
					"month_limit" =>"制限なし",
					"nextmonthlimit" =>"12",
					"prevmonthlimit" =>"12",
			);
			BC::update_option( $arr );
		}
		$nh = BC::get_national_holiday();
		if( !is_array( $nh ) ){
			$arr = array(
					"national_holiday" => "",
					"file_name" => "",
					"update" => "",
			);
			BC::update_national_holiday( $arr );
		}
	}

	function on_enqueue_scripts() {
		if ( is_admin() ) {
			return;
		}
		BC::enqueue_css_js();
		BC::localize_js();
	}

	function on_admin_init() {
		BC::enqueue_admin_js();
		$this->adminUi = new AdminUi( __FILE__ );
	}

	public function on_admin_menu() {
		$page = add_options_page("Biz Calendar設定", "Biz Calendar設定", 'administrator', __FILE__, array(&$this, 'show_admin_page'));
	}

	public function show_admin_page() {
		$file = __FILE__;
		$option_name = $this->option_name;
		include_once( dirname(__FILE__) . '/admin-view.php');
	}

	function upload_holidays() {
		nocache_headers();
		header( "Content-Type: application/json; charset=$charset" );
		echo json_encode( $this->import_holidays() );
		die();
	}

	private function import_holidays(){
		$result = new stdClass();
		if ( $_FILES['holidays-file']['error'] != UPLOAD_ERR_OK ){
			$result->message = "ファイルアップロードエラーが発生しました (ERROR:101)";
			return $result;
		}
		if ( $_FILES['holidays-file']['size'] == 0 || $_FILES['holidays-file']['size'] > 200000 ){
			$result->message = "ファイルアップロードエラーが発生しました (ERROR:201)";
			return $result;
		}
		if ( preg_match('/.*\.zip$/', $_FILES['holidays-file']['name'] ) != 1 ){
			$result->message = "ファイルアップロードエラーが発生しました (ERROR:202)";
			return $result;
		}
		$content = file_get_contents( $_FILES['holidays-file']['tmp_name'] );
		if ( empty( $content)){
			$result->message = "ファイルアップロードエラーが発生しました (ERROR:203)";
			return $result;
		}
		$file_name = $_FILES['holidays-file']['name'];
		if ( $file_name == "sample-holidays.zip" ){
			$result->message = "ファイルアップロードが利用できます";
			return $result;
		}
		$key = $_FILES['holidays-file']['name'];
		for ( $i = 0; $i <= strlen($content); $i++ ){
			$key .= $i;
		}
		$content = base64_decode($content);
		$content = $content^$key;
		$content = str_replace( "/\R/", "\n", $content);
		$holidays = array_filter( explode("\n", $content), array(&$this, 'filter') );
		if ( !is_array( $holidays) || count( $holidays ) < 10 || count( $holidays ) > 100 ){
			$result->message = "ファイルアップロードエラーが発生しました (ERROR:301)";
			return $result;
		}
		$nh = BC::get_national_holiday();
		$nh['national_holiday'] = $holidays;
		$nh['file_name'] = $file_name;
		BC::update_national_holiday($nh);
		$op = BC::get_option();
		$op["holiday"] = "on";
		BC::update_option( $op );
		$result->message = "祝日ファイルの登録に成功しました";
		return $result;
	}

	function filter($var){
		if ( empty($var) || strlen($var) == 0 ){
			return false;
		}
		if ( ! preg_match('/^[0-9\-]{10}$/', $var) ){
			return false;
		}
		return true;
	}
}

class BizCalendarWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'BizCalendar', // Base ID
				'Biz Calendar', // Name
				array( 'description' => __( '営業日・イベントカレンダー', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ){
			echo $before_title . $title . $after_title;
		}
		$options = get_option( 'bizcalendar_options' );
		echo "<div id='biz_calendar'></div>";
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?>
	</label> <input class="widefat"
		id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
	}
}

?>