<?php
/*
Plugin Name: No Cache AJAX Widgets
Version: 1.0
Plugin URI: http://magnigenie.com
Description: Add AJAX powered widgets to your site. Serve fresh and dynamic content from any and all widget areas. Resolves common caching related issues with dynamic content.
Author: MagniGenie
Author URI: http://magnigenie.com/contact
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!class_exists('AJAX_Text_Widget')) {

	// register widget
	add_action('widgets_init', '_ajax_text_widget');
	function _ajax_text_widget(){
		register_widget('AJAX_Text_Widget');
	}
	
	add_action( 'wp_enqueue_scripts', 'mg_add_ajax_script' );
	function mg_add_ajax_script() {

		wp_enqueue_script( 'mg-ajax-script', plugins_url( '/js/mg_ajax.js', __FILE__ ), array('jquery') );

		wp_localize_script( 'mg-ajax-script', 'mg_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	add_action( 'wp_ajax_mg_ajax_text', 'mg_ajax_text' );
	add_action( 'wp_ajax_nopriv_mg_ajax_text', 'mg_ajax_text' );
	function mg_ajax_text(){
		$data = $_POST['data'];
		$return = array();
		if( is_array( $data ) ){
			foreach ( $data as $key => $text ) {
				$return[$key] = do_shortcode( base64_decode( $text ) );
			}
		}
		echo json_encode( $return );
		exit;
	}

	// extend wp widget class
	class AJAX_Text_Widget extends WP_Widget {

		public function __construct() {
			// instantiate the parent object
			parent::__construct(
				'ajax_text', // Base ID
				'AJAX Widget', // Name
				array('description' => 'Any tex/shortcode which you want to load using ajax')
			);
		}

		public function widget($args, $instance) {
			extract($args);

			if (!$instance['title'] && !$instance['text']) return;

			$title = apply_filters('widget_title', $instance['title']);
			$text = $instance['text'];

			$text = base64_encode( $text );

			echo $before_widget; ?>

				<?php if ($title) echo $before_title . $title . $after_title; ?>
				<?php if ($text) : ?><div class="mg_ajax_widget" data-text="<?php echo $text; ?>"><img src="<?php echo plugins_url( '/img/loading.GIF', __FILE__ ); ?>" alt="loading" /></div><?php endif; ?>

			<?php echo $after_widget;
		}

		public function form($instance) {
			$instance = wp_parse_args((array)$instance, array('title' => '', 'text' => ''));
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'ajaxwp'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>">
			</p>
			<p>
				<textarea rows="10" class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $instance['text']; ?></textarea>
			</p>

		<?php
		}

		public function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['text'] = $new_instance['text'];
			return $instance;
		}
	}
}
?>