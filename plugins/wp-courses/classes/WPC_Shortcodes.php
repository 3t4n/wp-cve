<?php

class WPC_Shortcodes {

	public $user_id;

	public function __construct() {
		$this->user_id = get_current_user_id();

		add_shortcode('wpc_courses', array($this, 'renderAjaxView'));
		add_shortcode( 'courses', array($this, 'renderAjaxView') ); // legacy shortcode
		add_shortcode('wpc_profile', array($this, 'profilePage'));
		add_shortcode('lesson_count', array($this, 'lessonCount'));
		add_shortcode('course_count', array($this, 'courseCount'));

	}

	function lessonCount(){
		$args = array(
			'post_type' 		=> 'lesson',
			'post_status'		=> 'publish',
			'posts_per_page' 	=> -1
		);
		$query = new WP_Query($args);
		return $query->post_count;
	}

	function courseCount(){
		$args = array(
			'post_type' => 'course',
			'post_status'		=> 'publish',
			'posts_per_page' => -1
		);
		$query = new WP_Query($args);
		return $query->post_count;
	}

	function renderAjaxView() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if( is_plugin_active( 'wp-courses-woocommerce/wp-courses-woocommerce.php') !== false ) {
			return 'Courses have moved.' . '<br><a class="wpc-btn" href="' . get_post_type_archive_link( 'course' ) . '">' . __('View Courses', 'wp-courses') . '</a>';
		}

		$logged_in = is_user_logged_in() === true ? 'true' : 'false'; ?>

		<?php ob_start(); ?>

		<div id="wpc-course-app" class="wpc-main"></div>

		<script type="text/javascript">
			jQuery(document).ready(function(){
				new WPC_UI({
					'initView'			: 'course-archive',
					'loggedIn'			: <?php echo $logged_in; ?>,
					'userID'			: <?php echo get_current_user_id(); ?>,
					ajaxLinks 			: true,
					fixedToolbar 		: <?php echo get_option('wpc_fix_toolbar_top') == 'true' ? 'true' : 'false'; ?>,
					fixedToolbarOffset 	: <?php echo get_option('wpc_fixed_toolbar_offset') == 'true' ? 'true' : 'false'; ?>,
					adminBar 			: <?php echo is_admin_bar_showing() === true ? 'true' : 'false'; ?>,
				});
			});
		</script>

		<?php if(current_user_can('administrator')) {
			$post_id = get_the_ID();
			wpc_front_end_editor($post_id); // FEE injected via [wpc_courses] and [courses] shortcode
		} ?>

		<?php return ob_get_clean();

	}

	function profilePage($atts){

		ob_start();

	 	$args = shortcode_atts( array(
	        'user_id' => get_current_user_id(),
	    ), $atts );

		if(!is_user_logged_in() && !isset($args['user_id'])){
			return '<div class="wpc-msg">' . esc_html__('You must be logged in to view your profile', 'wp-courses') . '.</div>';
		} ?>

		<div id="wpc-profile-page"></div>

		<script>
		jQuery(document).ready(function($){
			var userID = <?php echo (int) $args['user_id']; ?>;

			// Used to overwrite window.wpcd.user.ID etc. in ui.js
			// And to load the profile window
			new WPC_UI({
				loggedIn 				: true,
				userID 					: userID,
				onLoad 					: false,
				ajaxLinks 				: false,
			});
		});
		</script>

		<?php return ob_get_clean(); ?>	

	<?php }

}

add_action('init', 'wpc_shortcodes_init');

function wpc_shortcodes_init(){
	new WPC_Shortcodes();
}


function wpc_get_main_shortcode_page_url()
{
    $pages = get_pages();
    $pattern = get_shortcode_regex(array('wpc_courses'));

    foreach ($pages as $page) {
        if (
            preg_match_all('/' . $pattern . '/s', $page->post_content, $matches)
            && array_key_exists(2, $matches)
            && in_array('wpc_courses', $matches[2])
        ) {
            return get_permalink( $page->ID );
        }
    }
}

function wpc_get_courses_hash($params) 
{
	$paramStr = '?';
	$index = 0;

	foreach ($params as $key => $value) {
		$paramStr .= ($index + 1) <= count($params) && $index !== 0 ? '&' : '';
		$paramStr .= $key . '=' . $value;

		$index++;
	}

	return '#' . base64_encode($paramStr);
}