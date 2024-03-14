<?php
/*
Plugin Name: WP Custom CSS
Plugin URI: http://pie-solutions.com/wp-custom-cssjs/
Description: Add HTML, CSS, Javascript, Jquery and Tracking Pixels right from the dashboard :)

Author: Johnibom
Version: 1.4.1
Author URI: http://www.pie-solutions.com/
Text Domain: wpcssjs
CHANGELOG
See readme.txt
*/
$wpccj_dir_path = dirname(__FILE__);
define('WPCCJ_DIR_NAME',$wpccj_dir_path);
if(!defined('WPCCJ_DB_VERSION'))
	define('WPCCJ_DB_VERSION','1.0');

define('LOG_FILE', '.wpccj.log');

class pieCsutomCJ{
	var $wpccj_option;
	var $message;
	var $error;
	var $success;
	function __construct(){
		$this->wpccj_option = get_option("wpccj_option");
		add_action( 'admin_menu',  array($this,'addPanel') );
		add_action('init',array($this,'main'));
		
	}
	function addPanel(){
		add_theme_page( __('Add CSS, JS or Tracking Pixel on the site right from your dashboard',"wpcssjs"), __('Custom CSS/JS',"wpcssjs"), 'edit_theme_options', 'wp_custom_css_js', array($this, 'backendConf') );
	}
	
	
	function hook_pie_html_box_header(){
		echo apply_filters( 'pie_header_scripts', $this->wpccj_option['pie_html_box_header']);
	}
	
	function hook_pie_css_box_header(){
		?>
        <style>
			<?php echo $this->wpccj_option['pie_css_box_header'];?>
		</style>
        <?php
	}
	
	function hook_pie_js_box_header(){
		
		?>
        <script>
			/* <![CDATA[ */
			<?php echo $this->wpccj_option['pie_js_box_header'];?>
			/* ]]> */
		</script>
        <?php
	}
	
	function hook_pie_html_box_footer(){
		echo apply_filters( 'pie_footer_scripts', $this->wpccj_option['pie_html_box_footer']);
	}
	function hook_pie_css_box_footer(){
		?>
        <style>
			<?php echo $this->wpccj_option['pie_css_box_footer'];?>
		</style>
        <?php
	}
	
	function hook_pie_js_box_footer(){
		
		?>
        <script>
			/* <![CDATA[ */
			<?php echo $this->wpccj_option['pie_js_box_footer'];?>
			/* ]]> */
		</script>
        <?php
	}
	
	function main(){
		/////Backend/////
		if(isset($_POST['wpccj_update_cssjs']) && $_POST['wpccj_update_cssjs'] != ''){
			$this->saveConf();
		}
		add_action( 'admin_notices', array($this,'wpccj_admin_notice') );
		/////Frontend/////
		
		//$this->wpccj_option['pie_wpccj_enable'];
		if($this->wpccj_option['pie_html_box_header'] != ''){
			add_filter( 'pie_header_scripts', 'do_shortcode' );
			add_action('wp_head',array($this,'hook_pie_html_box_header'));
		}
		if($this->wpccj_option['pie_css_box_header'] != ''){
			add_action('wp_head',array($this,'hook_pie_css_box_header'));
		}
		if($this->wpccj_option['pie_js_box_header'] != ''){
			add_action('wp_head',array($this,'hook_pie_js_box_header'));
		}
		if($this->wpccj_option['pie_html_box_footer'] != ''){
			add_filter( 'pie_footer_scripts', 'do_shortcode' );
			add_action('wp_footer',array($this,'hook_pie_html_box_footer'));
		}
		if($this->wpccj_option['pie_css_box_footer'] != ''){
			add_action('wp_footer',array($this,'hook_pie_css_box_footer'));
		}
		if($this->wpccj_option['pie_js_box_footer'] != ''){
			add_action('wp_footer',array($this,'hook_pie_js_box_footer'));
		}
	}
	function wpccj_admin_notice() {
		if($this->error != ''){
		?>
		<div class="updated">
			<p><?php echo $this->message; ?></p>
		</div>
		<?php
		}elseif($this->success){
		?>
		<div class="updated">
			<p><?php echo $this->message; ?></p>
		</div>
		<?php
		}	
	}

	function backendConf(){
		if($this->success != '' && $this->error != ''){
		?>
        
        <?php
		}
		require_once(WPCCJ_DIR_NAME.'/admin-settings.php');
	}
	function saveConf(){
		//wpblk_update_ip_list
		if(isset($_REQUEST['wpccj_update_cssjs'])){
			$nonce = $_REQUEST['_wpnonce'];
			if ( wp_verify_nonce( $nonce, 'update_pie_cssjs' ) ) {
				//// Save the IPs
				$options = $this->wpccj_option;
				$options['pie_html_box_header'] = ($_REQUEST['pie_html_box_header'])?:'';
				$options['pie_css_box_header'] = ($_REQUEST['pie_css_box_header'])?:'';
				$options['pie_js_box_header'] = ($_REQUEST['pie_js_box_header'])?:'';
				$options['pie_html_box_footer'] = ($_REQUEST['pie_html_box_footer'])?:'';
				$options['pie_css_box_footer'] = ($_REQUEST['pie_css_box_footer'])?:'';
				$options['pie_js_box_footer'] = ($_REQUEST['pie_js_box_footer'])?:'';
				$options['pie_wpccj_enable'] = 1;
				update_option("wpccj_option",stripslashes_deep($options));
				$this->wpccj_option = get_option("wpccj_option");
				$this->success = true;
				$this->message = __('All the changes have been updated!','wpcssjs');
			}else{
				$this->error = true;
				$this->message = __('No Changes were made!','wpcssjs','wpcssjs');
			}
		}
	}//end function
}//end class
$pieCsutomCJ = new pieCsutomCJ();