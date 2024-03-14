<?php
/*
	Plugin name: WooCommerce Pricing Table
	Plugin URI: http://total-soft.pe.hu/woocommerce-pricing-table
	Description: WooCommerce Pricing plugin is a powerful, amazing pricing plugin which helps you to make a table with your products and to give them beautiful design for each one. WooCommerce Pricing plugin offers a powerful tool to directly modify prices.
	Version: 1.0.9
	Author: Total-Soft
	Author URI: http://total-soft.pe.hu/
	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
	require_once(dirname(__FILE__) . '/Includes/Total-Soft-Pricing-Table-Widget.php');
	require_once(dirname(__FILE__) . '/Includes/Total-Soft-Pricing-Table-Ajax.php');

	add_action('widgets_init', 'TotalSoft_PTable_Widget_Reg');

	function TotalSoft_PTable_Widget_Reg() {
		register_widget('Total_Soft_Pricing_Table');
	}

	add_action("admin_menu", 'TotalSoft_PTable_Admin_Menu');

	function TotalSoft_PTable_Admin_Menu(){
		$complete_url = wp_nonce_url( '', 'edit-menu_', 'TS_PTable_Nonce' );
		add_menu_page('Admin Menu','Pricing Table', 'manage_options','Total_Soft_Pricing_Table' . $complete_url, 'Add_New_Pricing_Table', plugins_url('/Images/admin.png',__FILE__));
		add_submenu_page('Total_Soft_Pricing_Table' . $complete_url, 'Admin Menu', 'Table Manager', 'manage_options', 'Total_Soft_Pricing_Table' . $complete_url, 'Add_New_Pricing_Table');
		add_submenu_page('Total_Soft_Pricing_Table' . $complete_url, 'Admin Menu', '<span id="TS_Pricing_Sup">Support Forum</span>', 'manage_options', 'Total_Soft_Pricing_Support', 'TS_PTable_Support');
		add_submenu_page('Total_Soft_Pricing_Table' . $complete_url, 'Admin Menu', 'Total Products', 'manage_options', 'Total_Soft_Pricing_Table_Products' . $complete_url, 'Pricing_Table_Product');
	}

	add_action('admin_init', 'TotalSoft_PTable_Admin_Style');

	function TotalSoft_PTable_Admin_Style() {
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');

		wp_register_style('Total_Soft_Pricing_Table', plugins_url('/CSS/Total-Soft-Pricing-Table-Admin.css',__FILE__));
		wp_enqueue_style('Total_Soft_Pricing_Table' );
		wp_register_script('Total_Soft_Pricing_Table', plugins_url('/JS/Total-Soft-Pricing-Table-Admin.js',__FILE__),array('jquery','jquery-ui-core'));
		wp_localize_script('Total_Soft_Pricing_Table','object', array('ajaxurl'=>admin_url('admin-ajax.php')));
		wp_enqueue_script('Total_Soft_Pricing_Table');
		wp_enqueue_script("jquery");
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');

		wp_register_style('fontawesome-css', plugins_url('/CSS/totalsoft.css', __FILE__));
		wp_enqueue_style('fontawesome-css');
	}

	add_action ('wp_loaded', 'TS_Pricing_Support');

	function TS_Pricing_Support()
	{
        if(isset($_GET['page']) && $_GET['page'] == 'Total_Soft_Pricing_Support' ){
            $url = 'https://wordpress.org/support/plugin/woo-pricing-table';
            wp_redirect($url);
        }else{
            return false;
        }
        exit;
	}

	add_action( 'admin_footer', 'TS_PTable_Support_Blank' );
	function TS_PTable_Support_Blank()
	{
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#TS_Pricing_Sup').parent().attr('target','_blank');
			});
		</script>
		<?php
	}

	function Add_New_Pricing_Table()
	{
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Pricing-Table-New.php');
	}
	function Pricing_Table_Options()
	{
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Pricing-Table-Settings.php');
	}
	function TS_PTable_Support(){}
	function Pricing_Table_Product()
	{
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Products.php');
	}
	function TotalSoftPTableInstall()
	{
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Pricing-Table-Install.php');
	}
	register_activation_hook(__FILE__,'TotalSoftPTableInstall');

	function Total_SoftPTable_Short_ID($atts, $content = null)
	{
		$atts=shortcode_atts(
			array(
				"id"=>"1"
			),$atts
		);
		return Total_Soft_Draw_PTable($atts['id']);
	}
	add_shortcode('Total_Soft_Pricing_Table', 'Total_SoftPTable_Short_ID');
	function Total_Soft_Draw_PTable($PTable)
	{
		ob_start();
			$args = shortcode_atts(array('name' => 'Widget Area','id'=>'','description'=>'','class'=>'','before_widget'=>'','after_widget'=>'','before_title'=>'','AFTER_TITLE'=>'','widget_id'=>'','widget_name'=>'Total Soft Pricing Table'), $PTable, 'Total_Soft_Pricing_Table' );
			$Total_Soft_Pricing_Table = new Total_Soft_Pricing_Table;

			$instance = array('Pricing_Table'=>$PTable, 'Pricing_Table_T'=>'');
			$Total_Soft_Pricing_Table->widget($args,$instance);
			$cont[] = ob_get_contents();
		ob_end_clean();
		return $cont[0];
	}
	function TotalSoft_PTable_Color()
	{
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
	add_action( 'admin_enqueue_scripts', 'TotalSoft_PTable_Color' );

	function TS_PT_Media_Button()
	{
        $context = "";
		$img = plugins_url('/Images/admin.png',__FILE__);
		$container_id = 'TSPTable';
		$title = 'Select Total Soft Pricing Table to insert into post';
		$button_text = 'TS Pricing Table';
		$context .= '<a class="button thickbox" title="' . $title . '"	href="#TB_inline&inlineId=' . $container_id . '&width=400&height=240">
		<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;background-size: 18px 18px;"></span>' . $button_text . '</a>';

		echo $context;
	}
	add_action( 'media_buttons', 'TS_PT_Media_Button');
	add_action( 'admin_footer', 'TS_PT_Media_Button_Content');

	function TS_PT_Media_Button_Content()
	{
		require_once(dirname(__FILE__) . '/Includes/Total-Soft-Pricing-Table-Media.php');
	}

	if( isset($_GET['ts_pt_preview']) )
	{
		add_filter('the_content', 'TS_PT_theContenta');
		add_filter('template_include', 'TS_PT_templateIncludea');

		function TS_PT_theContenta()
		{
			if (!is_user_logged_in()) return 'Log In first in order to preview the Pricing Table.';

			ob_start();
				$args = shortcode_atts(array('name' => 'Widget Area','id'=>'','description'=>'','class'=>'','before_widget'=>'','after_widget'=>'','before_title'=>'','AFTER_TITLE'=>'','widget_id'=>'','widget_name'=>'Total Soft Pricing Table'), $_GET['ts_pt_preview'], 'Total_Soft_Pricing_Table' );
				$Total_Soft_Pricing_Table = new Total_Soft_Pricing_Table;

				$instance = array('Pricing_Table'=>$_GET['ts_pt_preview'], 'Pricing_Table_T'=>'');
				$Total_Soft_Pricing_Table->widget($args,$instance);
				$cont[] = ob_get_contents();
			ob_end_clean();
			return $cont[0];
		}
		function TS_PT_templateIncludea()
		{
			return locate_template(array('page.php', 'single.php', 'index.php'));
		}
	}

	if( isset($_GET['ts_pt_preview_set']) )
	{
		add_filter('the_content', 'TS_PT_theContent');
		add_filter('template_include', 'TS_PT_templateInclude');

		function TS_PT_theContent()
		{
			global $wpdb;
			$table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";

			$TS_PTable_Manager = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id > %d order by id", 0));

			if (!is_user_logged_in()) return 'Log In first in order to preview the Pricing Table.';

			ob_start();
				$args = shortcode_atts(array('name' => 'Widget Area','id'=>'','description'=>'','class'=>'','before_widget'=>'','after_widget'=>'','before_title'=>'','AFTER_TITLE'=>'','widget_id'=>'','widget_name'=>'Total Soft Pricing Table'), $TS_PTable_Manager[0]->id, 'Total_Soft_Pricing_Table' );
				$Total_Soft_Pricing_Table = new Total_Soft_Pricing_Table;

				$instance = array('Pricing_Table'=>$TS_PTable_Manager[0]->id, 'Pricing_Table_T'=>'true');
				$Total_Soft_Pricing_Table->widget($args,$instance);
				$cont[] = ob_get_contents();
			ob_end_clean();
			return $cont[0];
		}
		function TS_PT_templateInclude()
		{
			return locate_template(array('page.php', 'single.php', 'index.php'));
		}
	}
?>