<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.webtoffee.com/
 * @since      1.0.0
 *
 * @package    Wp_Migration_Duplicator
 * @subpackage Wp_Migration_Duplicator/admin/partials
 */
$wf_admin_view_path=WT_MGDP_PLUGIN_PATH.'admin/views/';
$wf_img_path=WT_MGDP_PLUGIN_URL.'images/';
?>
<div class="wrap">
    <h2 class="wp-heading-inline mgdp_plugin_head">
	<?php _e('WordPress Backup & Migration','wp-migration-duplicator');?>
	</h2>
    <div class="nav-tab-wrapper wp-clearfix wt-mgdp-tab-head " style="width:96%;border:none !important;box-shadow: 0px 2px 16px rgba(0, 0, 0, 0.1) !important;background-color: #fff;margin-left: 15px;margin-right: 15px" >
        <!--style="width:96%;border:none;margin-left: 16px;height: 50px ; border:none !important;box-shadow: 0px 2px 16px rgba(0, 0, 0, 0.1) !important;"-->
		<?php
	    $tab_head_arr=array(
	        //'wt-mgdp-help'=>__('Help Guide','wp-migration-duplicator')
	    );
	    if(isset($_GET['debug']))
	    {
	        $tab_head_arr['wt-mgdp-debug']='Debug';
	    }
	    Wp_Migration_Duplicator_Admin::generate_settings_tabhead($tab_head_arr);
	    ?>
        <!--<input style="text-align:center; float : right;margin-right: 22px; margin-top: 3px;" type="button" name="feedback-btn"  class="wt-button-red wt_sidebar_feedback" value="<?php _e('Report issue', 'wp-migration-duplicator') ?>">-->
            <!--<div class="set_margin_top "><?php // include WT_MGDP_PLUGIN_PATH . '/admin/partials/wp-migration-duplicator-report-issue.php'; ?></div>-->

	</div>
	<div class="wt-mgdp-tab-container">
        <?php
        //inside the settings form
        $setting_views_a=array(
                      
        );

        //outside the settings form
        $setting_views_b=array(          
            //'wt-mgdp-help'=>'admin-settings-help.php',           
        );
        if(isset($_GET['debug']))
        {
            $setting_views_b['wt-mgdp-debug']='admin-settings-debug.php';
        }
        ?>
        <form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]);?>" class="wf_settings_form">
            <input type="hidden" value="plugin_settings" class="wt_mgdp_update_action" />
            <?php
            // Set nonce:
            if (function_exists('wp_nonce_field'))
            {
                wp_nonce_field(WT_MGDP_PLUGIN_FILENAME);
            }
            foreach ($setting_views_a as $target_id=>$value) 
            {
                $settings_view=$wf_admin_view_path.$value;
                if(file_exists($settings_view))
                {
                    include $settings_view;
                }
            }
            ?>
            <?php 
            //settings form fields for module
            do_action('wt_mgdp_plugin_settings_form');?>           
        </form>
        <?php
        foreach ($setting_views_b as $target_id=>$value) 
        {
            $settings_view=$wf_admin_view_path.$value;
            if(file_exists($settings_view))
            {
                include $settings_view;
            }
        }
        ?>
        <?php do_action('wt_mgdp_plugin_out_settings_form');?> 
    </div>
</div>
