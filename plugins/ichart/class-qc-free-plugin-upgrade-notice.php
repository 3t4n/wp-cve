<?php

/*******************************************
 * QuantumCloud Plugin Upgrade Link for Free Plugins
 * Last Updated On: 05-24-2017
 *******************************************/

if( !class_exists('QciChartPluginUpgradeToProNotice') )
{
	class QciChartPluginUpgradeToProNotice {
		
		//Public variables, these can be overrides using instance callback

		public $upgrade_link = "https://www.quantumcloud.com";
		public $link_color = "#FCB214";
		public $link_text = "Upgrade to Pro";
		public $link_class = "";
		public $link_target = "_blank";
		
		public $plugin_slug = ""; //Exact plugin folder name
		public $plugin_main_file = ""; //Exact file name with extension of primary file
		public $plugin_menu_slug = ""; //Parent menu slug of the plugin
		
		public $plugin_slug_plus_file = "";

		//Turn on or off the hooks, use false to off
		public $show_with_action_links = true; //in plugin.php page
		public $show_with_meta_links = true; //in plugin.php page
		public $show_with_plugin_menu = true; //inside parent menu of the plugin
		
		//Contructor - Set defaults 
		function __construct()
		{
			add_action('admin_head', array(&$this, 'qc_upgrade_to_pro_heading_part') );
		}

		function qc_upgrade_to_pro_heading_part()
		{
			if( is_admin() ){
			?>
				<script>
					jQuery(document).ready(function($){
						$(".qc-up-pro-link-ichart").parent('a').on("click", function(e){
							e.preventDefault();
							var link = $(this).attr('href');
							window.open(link, '_blank');
						});
					});
				</script>
			<?php
			}
		}

		/*******************************
		 * Check if the current screen
		 * is the plugins.php page or not
		 *******************************/
		function check_if_plugin_page()
		{
			
			//Check if current page is plugins.php, otherwise return false
			global $pagenow;
			
			if( is_admin() && $pagenow == 'plugins.php' )
			{
			  return true;
			}

			return false;

		} //End of check_if_plugin_page
		
		/*******************************
		 * Put Link with Plugin Action Links
		 * Like Active, Edit etc.
		 * Ofcourse, in plugin.php page
		 *******************************/
		function hook_with_plugin_action_links(){
			
			$this->plugin_slug_plus_file = $this->plugin_slug .'/'. $this->plugin_main_file;
			
			if( $this->show_with_action_links && $this->plugin_slug_plus_file != "" )
			{
				add_action( 'plugin_action_links_' . $this->plugin_slug_plus_file, array(&$this, 'func_show_upgrade_link_with_action_links'), 95 );
			}
			else
			{
			  return;
			}
			
		}
		
		/*******************************
		 * Callback function for the above hook
		 * used inside hook_with_plugin_action_links
		 *******************************/
		function func_show_upgrade_link_with_action_links( $links ){

			$links = array_merge( $links, array(
				'<a title="Settings" class="'.$this->link_class.'" style="" href="' . esc_url( admin_url('admin.php?page=qcld-ichart-covid19-options') ) . '" target="">' . __( 'Settings', 'quantumcloud' ) . '</a>'
			) );

			$links = array_merge( $links, array(
				'<a title="Help" class="'.$this->link_class.'" style="" href="' . esc_url( admin_url('admin.php?page=qcopd_ichart_info_page') ) . '" target="">' . __( 'Help', 'quantumcloud' ) . '</a>'
			) );

			$links = array_merge( $links, array(
				'<a title="Support" class="'.$this->link_class.'" style="" href="' . esc_url( 'https://www.quantumcloud.com/resources/free-support/' ) . '" target="_blank">' . __( 'Support', 'quantumcloud' ) . '</a>'
			) );

			$links = array_merge( $links, array(
				'<a title="'.$this->link_text.'" class="'.$this->link_class.'" style="font-weight: bold; color: '.$this->link_color.';" href="' . esc_url( $this->upgrade_link ) . '" target="'.$this->link_target.'">' . __( $this->link_text, 'quantumcloud' ) . '</a>'
			) );
			
			return $links;
		}
		
		/*******************************
		 * Put Link with Plugin Meta Links
		 * Like Plugin Author, Details, etc.
		 * Ofcourse, in plugin.php page
		 *******************************/
		function hook_with_plugin_meta_links(){
			
			if( $this->show_with_meta_links && $this->plugin_main_file != "" )
			{
				add_action( 'plugin_row_meta', array(&$this, 'func_show_upgrade_link_with_meta_links'), 10, 2 );
			}
			else
			{
			  return;
			}
			
		}
		
		/*******************************
		 * Callback function for the above hook
		 * used inside hook_with_plugin_meta_links
		 *******************************/
		function func_show_upgrade_link_with_meta_links( $links, $file )
		{
			$links = array_merge( array(
				
			), $links );
			
			if ( strpos( $file, "$this->plugin_main_file" ) !== false ) {
			
				$new_links = array(
					'<a class="'.$this->link_class.'" style="font-weight: bold; color: '.$this->link_color.';" href="' . esc_url( $this->upgrade_link ) . '" title="'.$this->link_text.'" target="'.$this->link_target.'">' . __( $this->link_text, 'quantumcloud' ) . '</a>'
				);
				
				$links = array_merge( $links, $new_links );
			}
			
			return $links;
		}
		
		/*******************************
		 * Put Link inside WP Admin Menu
		 * Created by the respective plugin
		 *******************************/
		function hook_with_plugin_submenu()
		{
		   if( !$this->show_with_plugin_menu || $this->plugin_menu_slug == "" ){
		     return;
		   }
		   else
		   {
			  add_action( 'admin_menu' , array(&$this, 'func_qc_external_upgrade_link'), 20 );
		   }
		}
		
		/*******************************
		 * Callback function for the avbove function/hook
		 *******************************/
		function func_qc_external_upgrade_link()
		{
		    global $submenu;
			$current_user = wp_get_current_user();
			
			if( !$this->show_with_plugin_menu || $this->plugin_menu_slug == "" )
			{
		     return;
		    }
		   
		    $link_text = '<span class="qc-up-pro-link-ichart" style="font-weight: bold; padding: 5px; background: #2271B1; border-radius: 4px; color: '.$this->link_color.'">'.$this->link_text.'</span>';
			if($current_user->roles[0]!='subscriber')
				$submenu["$this->plugin_menu_slug"][300] = array( $link_text, 'activate_plugins' , $this->upgrade_link );
			
			return $submenu;
		}
		
		

	} //End of class QciChartPluginUpgradeToProNotice

} // End of class_exists


/*******************************
 * Create instance and call the 
 * appropriate worker/callback
 *******************************/
 
$instance_ichart = new QciChartPluginUpgradeToProNotice();

if( is_admin() )
{ 
	
	//Uncommnent and Set these instance variables as per the requirements

	$instance_ichart->upgrade_link = qcichart_upgrade_link ;
	//$instance_ichart->link_color = "#FCB214";
	//$instance_ichart->link_text = "Upgrade To Pro";
	//$instance_ichart->link_class = "";
	//$instance_ichart->link_target = "_blank";

	$instance_ichart->plugin_slug = 'ichart'; //Plugin Slug. i.e. Folder Name
	$instance_ichart->plugin_main_file = 'qcld_ichart_main.php'; //Primary file of the pluign
	$instance_ichart->plugin_menu_slug = 'qcopd_ichart_info_page'; //Main Menu Slug

	$instance_ichart->show_with_action_links = true; //show in plugin.php page
	$instance_ichart->show_with_meta_links = true; //show in plugin.php page
	$instance_ichart->show_with_plugin_menu = true; //show inside parent menu of the plugin

	if( $instance_ichart->check_if_plugin_page() )
	{
		$instance_ichart->hook_with_plugin_action_links();
		$instance_ichart->hook_with_plugin_meta_links();
	}
	
	$instance_ichart->hook_with_plugin_submenu();

}

