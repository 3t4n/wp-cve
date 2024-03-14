<?php
	/*
		Plugin name: Gallery Portfolio 
		Plugin URI: https://total-soft.com/wp-portfolio-gallery/
		Description: Portfolio plugin created for persons who like to show their photos in high quality with the best gallery design. Gallery & Portfolio plugin will help you more easily create image gallery, photo album, portfolio, grid image and slider portfolio.
		Version: 1.4.8
		Author: Total-Soft
		Author URI: https://total-soft.com
		License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	*/
	require_once(dirname(__FILE__) . '/Includes/Total-Soft-Portfolio-Widget.php');
	require_once(dirname(__FILE__) . '/Includes/Total-Soft-Portfolio-Ajax.php');
	add_action('wp_enqueue_scripts', 'ts_pg_widget_style');
	function ts_pg_widget_style()
	{
		wp_register_style('Total_Soft_Portfolio', plugins_url('/CSS/Total-Soft-Portfolio-Widget.css',__FILE__ ));
		wp_register_style('Total_Soft_Portfolio2', plugins_url('/CSS/Filt_popup.min.css',__FILE__ ));
		wp_enqueue_style('Total_Soft_Portfolio');
		wp_enqueue_style('Total_Soft_Portfolio2');
		wp_register_script('Total_Soft_Portfolio',plugins_url('/JS/Total-Soft-Portfolio-Widget.js',__FILE__),array('jquery','jquery-ui-core'));
		wp_localize_script('Total_Soft_Portfolio', 'ts_pg_object', array('ajaxurl' => admin_url('admin-ajax.php'),'ts_pg_nonce_field'      => wp_create_nonce( 'ts_pg_nonce_field' )));
		wp_enqueue_script('Total_Soft_Portfolio');
		wp_enqueue_script("jquery");
		wp_register_style('fontawesome-css', plugins_url('/CSS/totalsoft.css', __FILE__));
		wp_enqueue_style('fontawesome-css');
	}
	add_action('widgets_init', 'ts_pg_register_widget');
	function ts_pg_register_widget() 
	{
		register_widget('Total_Soft_Portfolio');
	}
	add_action("admin_menu", 'ts_pg_gallery_admin_menu');
	function ts_pg_gallery_admin_menu()
	{
		add_menu_page('Admin Menu','Portfolio', 'manage_options','Total_Soft_Portfolio', 'ts_pg_add_new_portfolio',plugins_url('/Images/admin.png',__FILE__));
		add_submenu_page('Total_Soft_Portfolio', 'Admin Menu', 'Portfolio Manager', 'manage_options', 'Total_Soft_Portfolio', 'ts_pg_add_new_portfolio');
		add_submenu_page('Total_Soft_Portfolio', 'Admin Menu', 'General Options', 'manage_options', 'Total_Soft_Portfolio_General', 'ts_pg_portfolio_options');
		add_submenu_page('Total_Soft_Portfolio', 'Admin Menu', 'Total Products', 'manage_options', 'Total_Soft_Products', 'ts_pg_products');
		add_submenu_page('Total_Soft_Portfolio', 'Admin Menu', '<span id="TS_Cal_Sup">Support</span>', 'manage_options', '', 'ts_pg_products');
	}
	add_action('admin_footer','ts_pg_support_link');
	function ts_pg_support_link() { ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#TS_Cal_Sup').parent().attr('target','_blank');
				jQuery('#TS_Cal_Sup').parent().attr('href','<?php echo esc_url("https://total-soft.com/contact-us/"); ?>');
			});
		</script>
	<?php }
	add_action('admin_init',  'ts_pg_admin_style');
	function ts_pg_admin_style() {
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_register_style('Total_Soft_Portfolio', plugins_url('/CSS/Total-Soft-Portfolio-Admin.css',__FILE__));
		wp_enqueue_style('Total_Soft_Portfolio' );
		wp_register_script('Total_Soft_Portfolio', plugins_url('/JS/Total-Soft-Portfolio-Admin.js',__FILE__),array('jquery','jquery-ui-core'));
		wp_localize_script('Total_Soft_Portfolio','ts_pg_object', array('ajaxurl'=>admin_url('admin-ajax.php'),'ts_pg_nonce_field'      => wp_create_nonce( 'ts_pg_nonce_field' )));
		wp_enqueue_script('Total_Soft_Portfolio');
		wp_register_style('fontawesome-css', plugins_url('/CSS/totalsoft.css', __FILE__));
		wp_enqueue_style('fontawesome-css');
	}
	function ts_pg_add_new_portfolio(){
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Portfolio-New.php');
	}
	function ts_pg_portfolio_options(){
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Portfolio-Settings.php');
	}
	function ts_pg_products(){
 		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Products.php');
	}
	function ts_pg_install(){
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Portfolio-Install.php');
	}
	register_activation_hook(__FILE__,'ts_pg_install');
	function ts_pg_shortcode_id($atts, $content = null){
		$atts=shortcode_atts(
			array(
				"id"=>"1"
			),$atts
		);
		return ts_pg_draw($atts['id']);
	}
	add_shortcode('Total_Soft_Portfolio', 'ts_pg_shortcode_id');
	function ts_pg_draw($Portfolio){
		ob_start();
			$args = shortcode_atts(array('name' => 'Widget Area','id'=>'','description'=>'','class'=>'','before_widget'=>'','after_widget'=>'','before_title'=>'','AFTER_TITLE'=>'','widget_id'=>'','widget_name'=>'Total Soft Portfolio'), $atts, 'Total_Soft_Portfolio' );
			$Total_Soft_Portfolio = new Total_Soft_Portfolio;
			$instance = array('Total_Soft_Portfolio'=>$Portfolio, 'TS_Portfolio_Theme_Name' => '');
			$Total_Soft_Portfolio->widget($args,$instance);
			$cont[] = ob_get_contents();
		ob_end_clean();
		return $cont[0];
	}
	function ts_pg_alpha_cp(){
		wp_enqueue_script(
			'alpha-color-picker',
			plugins_url('/JS/alpha-color-picker.js', __FILE__),
			array( 'jquery', 'wp-color-picker' ), // You must include these here.
			null,
			true
		);
		wp_enqueue_style(
			'alpha-color-picker',
			plugins_url('/CSS/alpha-color-picker.css', __FILE__),
			array( 'wp-color-picker' ) // You must include these here.
		);
	}
	add_action( 'admin_enqueue_scripts', 'ts_pg_alpha_cp' );
	function ts_pg_setting_links($links){
		$forum_link = sprintf(
			'
				<a target="_blank" href="%1$s"> %2$s </a>
			',
			esc_url("https://wordpress.org/support/plugin/gallery-portfolio"),
			esc_html("Support")
		);
		$premium_link = sprintf(
			'
				<a target="_blank" href="%1$s"> %2$s </a>
			',
			esc_url("https://total-soft.com/wp-portfolio-gallery/"),
			esc_html("Pro Version")
		);
		array_push($links, $forum_link);
		array_push($links, $premium_link);
		return $links; 
	}
	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", 'ts_pg_setting_links' );
	function ts_pg_media_btn() {
		$context = sprintf(
			'
			<a class="button thickbox" title="%1$s"	href="#TB_inline&inlineId=%2$s&width=400&height=240">
				<span class="wp-media-buttons-icon" style="background: url("%3$s"); background-repeat: no-repeat; background-position: left bottom;background-size: 18px 18px;">
				</span>
				%4$s
			</a>
			',
			esc_html("Select Total Soft Portfolio to insert into post"),
			esc_html("TSPortfolio"),
			esc_url(plugins_url("/Images/admin.png",__FILE__)),
			esc_html("TS Portfolio")
		);
		if(current_user_can('manage_options'))
		{
			echo $context;
		}
	}
	add_action( 'media_buttons', 'ts_pg_media_btn');
	add_action( 'admin_footer', 'ts_pg_media_btn_content');
	function ts_pg_media_btn_content() {
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Portfolio-Media.php');
	}
?>