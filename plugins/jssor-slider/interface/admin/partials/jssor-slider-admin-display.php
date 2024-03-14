<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.jssor.com
 * @since      1.0.0
 *
 * @package    WP_Jssor_Slider
 * @subpackage WP_Jssor_Slider/interface/admin/partials
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

global $wpdb;
$sliders_data = WP_Jssor_Slider_Admin_Page::get_all_slider_data();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div style="max-width:1002px;clear:both;">
    <div data-u="jssor-slider-admin-panel" class="wjssc-dash-section">
	    <div style="padding:0 15px;height:auto;min-height:50px;background-color:#fff;">
		    <div class="wjssc-ctrl-title">
			    <?php _ex("Jssor Sliders", "noun", 'jssor-slider'); ?>
		    </div>
		    <div style="float:right;line-height:50px;">
                <div data-u="wjssl-btn-grid-view" class="wjssc-btn wjssc-btn-grid-view"></div>
                <div data-u="wjssl-btn-list-view" class="wjssc-btn wjssc-btn-list-view" style="display:none;"></div>
				<span style="font-weight:600;color:#999;margin-right:10px;"><?php _e("Sort By:",'jssor-slider'); ?></span>
				<select data-u="wjssl-dropdown-sort-sliders" style="margin:-2px 0 0 0;max-width:105px;font-size:12px;height:24px;line-height:24px;">
					<option value="id" selected="selected"><?php _ex("By ID", "Sort by ID", 'jssor-slider'); ?></option>
					<option value="name"><?php _ex("By Name","Sort by name", 'jssor-slider'); ?></option>
				</select>
		    </div>
		    <div style="width:100%;height:1px;float:none;clear:both"></div>
	    </div>

        <div data-u="jssor-slider-grid-view" class="jssor-grid-view">
            <?php
                if(is_null($sliders_data)) {
                    $sliders_data = array();
                }

                foreach ($sliders_data as $slider_data) {
                    $slider_id = $slider_data['id'];
                    $slider_filename = $slider_data['file_name'];
                    $slider_filepath = $slider_data['file_path'];
                    $grid_thumb_url = Jssor_Slider_Bll::get_slider_grid_thumb_url($slider_data);

                    $shortcode_with_alias = Jssor_Slider_Bll::get_shortcode_with_alias($slider_filename);
                    
                    $slider_edit_url = '#';
                    $slider_preview_url = WP_Jssor_Slider_Globals::get_jssor_preview_slider_url($slider_id, $slider_filename);
            ?>
                <div data-u="slider-item" class="jssor-grid-item" data-slider-id="<?php echo esc_attr($slider_id); ?>" data-slider-name="<?php echo esc_attr($slider_filename); ?>" data-shortcode="<?php echo esc_attr($shortcode_with_alias); ?>" >
                    <div class="jgi-cover">
                        <div class="jgi-thumb-wrapper">
                            <img data-u="grid-thumb" class="grid-thumb" src="<?php echo esc_url($grid_thumb_url);?>" />
                        </div>
                    </div>
                    <div class="jgi-title">
                        <div data-u="index" class="jgi-title-index">#<?php echo esc_attr($slider_id); ?></div>
                        <div class="jgi-title-controls"></div>
                        <div class="jgi-title-content">
                            <div data-u="command" data-command="rename" class="jgi-title-text" title="<?php echo esc_attr($slider_filename) ?>"><?php echo esc_attr($slider_filename) ?></div>
                        </div>
                    </div>
                    <div class="jgi-shortcode">
                        <div data-u="command" data-command="shortcode" class="jgi-shortcode-text">[jssor-slider alias="<?php echo esc_attr($slider_filename) ?>"]</div>
                    </div>
                    <div class="jgi-footer">
                        <div data-u="command" data-command="dropdown" class="jgi-btn jgi-btn-dropdown"></div>
                        <a data-u="command" data-command="edit" class="jgi-btn jgi-btn-edit" href="<?php echo esc_url($slider_edit_url); ?>" target="_blank" title="edit"></a>
                        <a data-u="command" data-command="preview" class="jgi-btn jgi-btn-preview" href="<?php echo esc_url($slider_preview_url); ?>" target="_blank" title="preview"></a>
                        <div data-u="command" data-command="publish" class="jgi-btn jgi-btn-publish" title="publish"></div>
                        <div data-u="command" data-command="delete" class="jgi-btn jgi-btn-delete" title="delete"></div>
                        <div data-u="command" data-command="duplicate" class="jgi-btn jgi-btn-duplicate" title="duplicate"></div>
                    </div>
                </div>
            <?php
                }
            ?>
            <div class="jgi-command-item jgi-command-icon-new-slider">
                <div data-u="command" data-command="add" class="jgi-command-cover">
                    <div class="jgi-command-title"><span style="margin-left: 15px;">Create Slider</span></div>
                </div>
            </div>

            <div class="jgi-command-item jgi-command-icon-import-slider">
                <div data-u="command" data-command="import" class="jgi-command-cover">
                    <div class="jgi-command-title"><span style="margin-left: 15px;">Import Slider</span></div>
                </div>
            </div>
        </div>

        <div style="width:100%;height:30px"></div>

    </div>
</div>

<div class="wjssc-dash-section">
    <?php include WP_JSSOR_SLIDER_PATH . 'interface/admin/partials/jssor-slider-admin-requirements.php'; ?>
    <?php include WP_JSSOR_SLIDER_PATH . 'interface/admin/partials/jssor-slider-admin-version.php'; ?>
    <!-- activation view -->

    <?php
    $is_activated = get_option('wjssl_actcode', '') ? true : false;
    $purchase_code = get_option('wjssl_purchcode', '');
    $activation_status_class = $is_activated ? 'wjssc-status-ok' : 'wjssc-status-warning';
    ?>

    <div class="wjssc-dash-block <?php echo esc_attr($activation_status_class); ?>" data-u="jssor-slider-activation-view" style="display:none;">

        <!-- title -->
        <div data-u="title" class="wjssc-dash-title">
            <div class="wjssc-dash-title-text">
                <?php _ex("Plugin Upgrade","noun", 'jssor-slider'); ?>
            </div>
            <div class="wjssc-dash-title-signal wjssc-dash-title-signal-ok">
                <span class="wjssc-iconm wjssc-iconm-ok"></span>&nbsp;&nbsp;<span class="wjssc-dash-title-signal-text"><?php _ex("Pro Version", "noun", 'jssor-slider'); ?></span>
            </div>
            <div class="wjssc-dash-title-signal wjssc-dash-title-signal-problem">
                <span class="wjssc-iconm wjssc-iconm-inactive"></span>&nbsp;&nbsp;<span class="wjssc-dash-title-signal-text"><?php _ex("Upgrade Available", "noun", 'jssor-slider'); ?></span>
            </div>
        </div>
        

        <!-- content -->
        <div class="wjssc-dash-content">
            <div data-u="pro-version" style="width:440px;height:160px;<?php echo (!$is_activated ? 'display:none' : ''); ?>">
                <div class="wjssc-dash-icon-purchase-code" style="float:left;"></div>
                <div style="display:inline-block;height:42px;width:396px;overflow:hidden;">
                    <div class="wjssc-dash-emphasis wjssc-dash-caption-purchase-code"><?php _ex("Purchase Code", 'noun', 'jssor-slider'); ?></div>
                </div>
                <div style="height:1px;"></div>
                <div style="margin:1px;">
                    <input type="text" data-u="purchase-code" readonly style="width:100%;" value="<?php echo esc_attr($purchase_code); ?>" <?php echo ($is_activated ? 'readonly="readonly"' : '');?>>
                </div>
                <div style="height:14px;"></div>
                <div><?php _e("One purchase code for one website.", 'jssor-slider'); ?></div>
            </div>
            <div data-u="basic-version" style="width:440px;height:160px;<?php echo (!$is_activated ? '' : 'display:none'); ?>">
                <div style="display:inline-block;width:396px;overflow:hidden;">
                    <div class="wjssc-dash-emphasis wjssc-dash-caption-purchase-code">Benifits:</div>
                </div>
                <div style="height:1px;"></div>
                <ul style="margin-left:20px;list-style:square;">
                    <li><?php _ex("Premium resources", 'noun', 'jssor-slider'); ?></li>
                    <li><?php printf(__("Import custom slider from %s", 'jssor-slider'), '<a href="https://www.jssor.com" target="_blank">www.jssor.com</a>'); ?></li>
                </ul>
            </div>
        </div>
        

        <!-- footer-->
        <div class="wjssc-dash-footer">
            <span data-u="command" data-command="register" class="wjssc-dash-action-btn" style="<?php echo (!$is_activated ? '' : 'display:none'); ?>">
                <?php _ex('Upgrade Now', "verb", 'jssor-slider'); ?>
            </span>
            <span data-u="command" data-command="deregister" class="wjssc-dash-action-btn" style="<?php echo ($is_activated ? '' : 'display:none'); ?>">
                <?php _e('Deregister Purchase Code', 'jssor-slider'); ?>
            </span>
        </div>
        
    </div>
    

    <?php
        if(WP_JSSOR_SLIDER_BUILD_ENABLED) {
            include WP_JSSOR_SLIDER_PATH . 'interface/admin/partials/jssor-slider-admin-build.php'; 
        }
    ?>
</div>

<script type="text/html" data-u="wjssl-tmpl-slider-item">
    <div data-u="slider-item" class="jssor-grid-item">
        <div class="jgi-cover">
            <div class="jgi-thumb-wrapper">
                <img data-u="grid-thumb" class="grid-thumb" src="" />
                <!--<img data-u="list-thumb" class="list-thumb" src="" />-->
            </div>
        </div>
        <div class="jgi-title">
            <div data-u="index" class="jgi-title-index"></div>
            <div class="jgi-title-controls"></div>
            <div class="jgi-title-content">
                <div data-u="command" data-command="rename" class="jgi-title-text"></div>
            </div>
        </div>
        <div class="jgi-shortcode">
            <div data-u="command" data-command="shortcode" class="jgi-shortcode-text"></div>
        </div>
        <div class="jgi-footer">
            <div data-u="command" data-command="dropdown" class="jgi-btn jgi-btn-dropdown"></div>
            <a data-u="command" data-command="edit" class="jgi-btn jgi-btn-edit" href="#" target="_blank" title="edit"></a>
            <a data-u="command" data-command="preview" class="jgi-btn jgi-btn-preview" href="#" target="_blank" title="preview"></a>
            <div data-u="command" data-command="publish" class="jgi-btn jgi-btn-publish" title="publish"></div>
            <div data-u="command" data-command="delete" class="jgi-btn jgi-btn-delete" title="delete"></div>
            <div data-u="command" data-command="duplicate" class="jgi-btn jgi-btn-duplicate" title="duplicate"></div>
        </div>
    </div>
</script>

<script type="text/html" data-u="wjssl-tmpl-publish-slider-dialog-content">
    <div data-u="publish-slider-dialog-content" data-nodrag="1" style="padding: 2px;">
        <div style="font-size:14px;margin-bottom:10px;"><strong><?php _ex("Standard Embedding", "noun", 'jssor-slider'); ?></strong></div>
        <?php _e("For the <b>pages or posts editor</b> insert the shortcode:", 'jssor-slider'); ?><br />
        <div style="width:100%;height:10px"></div>
        <code data-u="shortcode" class="jssor-code">[jssor-slider alias="<span data-u="slider-name" class="jssor-code">filename.slider</span>"]</code><br />
        <div style="width:100%;height:8px"></div>
        <code data-u="shortcode" class="jssor-code">[jssor-slider id="<span data-u="slider-id" class="jssor-code">1</span>"]</code>
        <div style="width:100%;height:8px"></div>
        <code data-u="shortcode" class="jssor-code">[jssor-slider alias="<span data-u="slider-name" class="jssor-code">1</span>" show_on_pages="homepage"]</code>
        <div style="width:100%;height:8px"></div>
        <code data-u="shortcode" class="jssor-code">[jssor-slider id="<span data-u="slider-id" class="jssor-code">1</span>" show_on_pages="2,10"]</code>
        <!--<div style="width:100%;height:10px"></div>-->
        <!--From the <b>widgets panel</b> drag the "Jssor Slider" widget to the desired sidebar-->
        <div style="width:100%;height:25px"></div>
        <a data-u="advanced-emedding-link" href="#" style="display: inline-block; font-size:14px; margin-bottom:10px;"><strong><?php _e("Advanced Embedding ...", 'jssor-slider'); ?></strong></a>
        <div data-u="advanced-emedding-content" style="display: none;">
            <span><?php _e("From the <b>theme html</b> use:", 'jssor-slider'); ?></span><br />
            <div style="width:100%;height:10px"></div>
            <code data-u="shortcode" class="jssor-code">&lt;?php echo do_shortcode( '[jssor-slider alias="<span data-u="slider-name" class="jssor-code">1</span>"]' ); ?&gt;</code><br>
            <div style="width:100%;height:8px"></div>
            <code data-u="shortcode" class="jssor-code">&lt;?php putJssorSlider( '<span data-u="slider-name" class="jssor-code">1</span>' ); ?&gt;</code><br>
            <div style="width:100%;height:10px"></div>

            <span><?php _e("To add the slider only to <b>homepage use:</b>", 'jssor-slider'); ?></span><br />
            <div style="width:100%;height:10px"></div>
            <code data-u="shortcode" class="jssor-code">&lt;?php echo do_shortcode( '[jssor-slider alias="<span data-u="slider-name" class="jssor-code">1</span>" show_on_pages="homepage"]' ); ?&gt;</code><br>
            <div style="width:100%;height:8px"></div>
            <code data-u="shortcode" class="jssor-code">&lt;?php putJssorSlider( '<span data-u="slider-name" class="jssor-code">1</span>', 'homepage' ); ?&gt;</code><br>
            <div style="width:100%;height:10px"></div>
            <span><?php _e("To add the slider on <b>specific pages</b> or posts use:", 'jssor-slider'); ?></span><br />
            <div style="width:100%;height:10px"></div>
            <code data-u="shortcode" class="jssor-code">&lt;?php echo do_shortcode( '[jssor-slider alias="<span data-u="slider-name" class="jssor-code">1</span>" show_on_pages="2,10"]' ); ?&gt;</code><br>
            <div style="width:100%;height:8px"></div>
            <code data-u="shortcode" class="jssor-code">&lt;?php putJssorSlider( '<span data-u="slider-name" class="jssor-code">1</span>', '2,10' ); ?&gt;</code><br>
        </div>
    </div>
</script>

<script type="text/html" data-u="wjssl-tmpl-plugin-activation-alert-dialog-content">
    <div data-u="plugin-activation-alert-dialog-content" style="padding: 2px;font-size:13px;">
        <div><?php _e("Please upgrade to pro version to continue.", 'jssor-slider'); ?></div>
        <div style="height: 20px;"></div>
        <div>
            <span class="wjssc-dash-emphasis wjssc-dash-caption-purchase-code">Benifits:</span>
        </div>
        <ul style="margin-left:20px;list-style:square;">
            <li><?php printf(__("Import custom slider from %s", 'jssor-slider'), '<a href="https://www.jssor.com" target="_blank">www.jssor.com</a>'); ?></li>
        </ul>
    </div>
</script>

<script type="text/html" data-u="wjssl-tmpl-plugin-activation-dialog-content">
    <div data-u="plugin-activation-dialog-content" data-nodrag="1" style="padding:1px;">
        <div style="display:inline-block;width:396px;overflow:hidden;">
            <div class="wjssc-dash-emphasis wjssc-dash-caption-purchase-code"><?php _ex("Purchase Code", "noun", 'jssor-slider'); ?></div>
        </div>
        <div>
            <input type="text" data-u="purchase-code" placeholder="enter purchase code here" style="width:100%;">
        </div>
        <p class="wjssc-activation-purchase-guide"><?php printf(__('<a class="text-warning" href="%s" target="_blank">Purchase Jssor Slider WrodPress Plugin Pro</a> to get purchase code.', 'jssor-slider'), 'https://www.jssor.com/purchase/jssor-slider-wordpress-plugin-pro.aspx');?></p>
        <div style="height: 10px;"></div>
        <div>
            <span class="wjssc-dash-emphasis wjssc-dash-caption-purchase-code"><?php _ex("Benifits:", 'noun', 'jssor-slider'); ?></span>
        </div>
        <ul style="margin-left:20px;list-style:square;">
            <li><?php printf(__("Import custom slider from %s", 'jssor-slider'), '<a href="https://www.jssor.com" target="_blank">www.jssor.com</a>'); ?></li>
        </ul>
        <div style="height: 10px;"></div>
        <p><?php _e("One purchase code for one website.", 'jssor-slider'); ?><span class="wjssc-activation-problem-guide"> <?php _e("If registered elsewhere please deregister first.", 'jssor-slider'); ?> <a class="text-warning" href="https://www.jssor.com/purchase/jssor-slider-wordpress-verify-purchase.aspx" target="_blank"><?php _e("Having problem? Verify Purchase!", 'jssor-slider'); ?></a></span></p>
    </div>
</script>

<script type="text/html" data-u="wjssl-tmpl-plugin-fix-media-browser-accessibility-problem-dialog-content">
    <div data-u="plugin-fix-media-browser-accessibility-problem-dialog-content" style="padding: 2px;font-size:13px;">
        <strong><?php _ex("Problem:", "noun", 'jssor-slider'); ?></strong>
        <div style="margin: 5px 0 0 20px;"><span data-u="problem-description" style="color:#da3423;"></span></div>
        <div style="margin: 5px 0 0 20px;"><a data-u="check-again" href="#"><?php _ex("Check Again", "verb", 'jssor-slider'); ?></a></div>
        <div style="height: 20px;"></div>
        <div>
            <span class="wjssc-dash-emphasis"><?php _e("Fix the problem in the following way,", 'jssor-slider');?></span>
        </div>
        <ul style="margin-left:20px;list-style:square;">
            <li><?php _ex("Apache Server", "Name of the web server", 'jssor-slider'); ?>
            <p style="margin:0;">
                <?php printf(__('Open %1$s or %2$s (in the root folder of your website), remove the following line.', 'jssor-slider'), '<strong>.htaccess</strong>', '<strong>httpd.conf</strong>'); ?>
                <br />
                <code>Header always append X-Frame-Options SAMEORIGIN</code>
            </p>
            </li>
            <li><?php _ex("Nginx Server", "Name of the web server", 'jssor-slider'); ?>
            <p style="margin:0;">
                <?php printf(__('Open %s on your server, remove the following line.', 'jssor-slider'), '<strong>nginx.conf</strong>'); ?>
                <br />
                <code>add_header X-Frame-Options "SAMEORIGIN";</code>
            </p>
            </li>
            <li>
                <?php _ex("IIS Server", "Name of the web server", 'jssor-slider'); ?>
                <p style="margin:0;">
                    <?php printf(__('Open %s (in the root folder of your website), remove the following line.', 'jssor-slider'), '<strong>web.config</strong>'); ?>
                    <br />
                    <code>&lt;add name="X-Frame-Options" value="SAMEORIGIN" /></code>
                </p>
            </li>
        </ul>
    </div>
</script>

<!--slideo editor templates-->
<script data-u="tmpl-slideo-editor" type="text/html">
    <!--slideo editor window-->
    <table u="jd-slideo-editor-window" operation-area="ignore" class="se-fullfill jd-noselect" cellpadding="0" cellspacing="0" border="0" style="width:98%;height:98%;color:#000;">
        <tr>
            <td u="slide-panel-hsplit-cell1" width="175" height="1" style="width:175px; min-width: 30px;"></td>
            <td></td>
            <td u="slide-panel-hsplit-cell2" style="width:200px;"></td>
            <td></td>
            <td style="width: 280px; min-width: 280px;"></td>
        </tr>
        <tr>
            <td colspan="3" style="height: 25px;line-height:0px; border-bottom: 1px solid rgba(246, 142, 86, .5);background-color: #e5e5e5;">
                <!-- main toolbox -->
                <div u="main-menu-bar" class="se-tbx" style="margin-left: 6px;">
                    <div u="button-file" class="se-btn"><?php _ex("File", "noun", 'jssor-slider'); ?></div>
                    <div u="button-layout" class="se-btn"><?php _ex("Layout", "noun", 'jssor-slider'); ?></div>
                    <div u="button-options" class="se-btn"><?php _ex("Options", "noun", 'jssor-slider'); ?></div>
                    <div u="button-preview" class="se-btn"><?php _ex("Preview", "noun", 'jssor-slider'); ?></div>
                    <div u="button-view" class="se-btn" style="display: none;"><?php _ex("View", "noun", 'jssor-slider'); ?></div>
                    <div u="button-help" class="se-btn"><?php _ex("Help", "noun", 'jssor-slider'); ?></div>
                    <div u="button-undo" class="se-btn se-sprite se-btn-undo" title="Undo"></div>
                    <div u="button-redo" class="se-btn se-sprite se-btn-redo" title="Redo"></div>
                </div>
            </td>
            <td style="height: 25px; border-bottom: 1px solid rgba(246, 142, 86, .5); background-color: #e5e5e5; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center;">
                <div class="se-panel-outer" style="height: 25px;">
                    <div class="se-panel-inter" style="height: 25px; text-align: center; line-height: 25px; overflow: hidden; ">
                        <span class="se-title" style="display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><span u="file_name"></span><span u="save_state" style="color: red;">*</span></span>
                        <a u="repository_link" class="text-warning" href="/*" target="_blank" tabindex="-1" style="position: absolute; right: 20px; font-size: 12px;"></a>
                    </div>
                </div>
            </td>
            <td u="buttons" style="width:30px; border-bottom: 1px solid #a6a6a6;background-color:#e5e5e5;text-align:right;vertical-align:top;">
                <div u="button-close" class="jd-button jd-button-close"></div>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="height:25px;border-top:1px solid #fff;border-bottom:1px solid rgba(246, 142, 86, .8);background-color:#e5e5e5;">
                <div class="se-panel-outer">
                    <div class="se-panel-inter" style="overflow: hidden;">
                        <!-- design properties -->
                        <div u="toolbox-descriptor" class="je-toolbox-panel" style="padding-left:9px;height:100%;overflow:hidden;white-space:nowrap;">
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="background-color: #e5e5e5;">
                <div class="se-panel-outer">
                    <div class="se-panel-inter" operation-area="slide-list" style="overflow: hidden;">
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%; overflow: hidden;">
                            <tr>
                                <td valign="top" style="height:26px;background-color:#e5e5e5;border-bottom: 1px solid #a6a6a6;font-size:0px;line-height:0px;">
                                    <!-- slides toolbox -->
                                    <div u="slides-toolbox" class="se-tbx" style="margin-left: 6px;">
                                        <div u="button-slide-add" class="se-btn se-sprite se-btn-slide-add" title="Add Slide"></div>
                                        <div u="button-slide-delete" class="se-btn se-sprite se-btn-slide-delete" title="Delete Slide"></div>
                                        <a u="button-slide-help" class="se-btn se-sprite se-btn-help" href="https://www.jssor.com/help/add-slide.html" target="_blank" tabindex="-1" title="Help: Add Slide"></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="se-panel-outer">
                                        <div class="se-panel-inter" style="overflow: hidden;">
                                            <div style="position: relative; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; overflow-y: auto;">
                                                <div style="position: relative; top: 0px; left: 0px; width: 100%; height: 100%;">
                                                    <div u="slide-list" style="position: relative; top:0px; left: 0px; width: 100%;">
                                                        <!-- slide list -->
                                                    </div>
                                                    <div style="height: 30px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td operation-area="ignore" style="width: 5px;">
                <div u="slide-panel-hsplit" class="se-hsplit-bar"></div>
            </td>
            <td colspan="2">
                <!-- canvas layout -->
                <table class="se-canvas" cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%;">
                    <tr>
                        <td>
                            <!-- slide canvas -->
                            <div u="layout-canvas" class="se-panel-outer">
                                <div u="layout-panel" operation-area="layer-list" class="se-panel-inter" style="background-color: #d4d4d4; ">
                                    <div u="vertical-margin" style="height: 50%; min-height: 270px;"></div>
                                    <div class="se-canvas-panel">
                                        <div u="layout-container" style="display: block; position: relative; top: -150px; margin: 0px auto; width: 640px; height: 300px;">
                                        </div>
                                    </div>
                                    <div u="vertical-margin" style="height: 50%; min-height: 270px;"></div>
                                </div>
                                <div u="layout-pan" class="se-layout-pan" style="display: none;"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td operation-area="ignore" style="height: 5px;"><div u="layer-panel-vsplit" class="se-vsplit-bar"></div></td>
                    </tr>
                    <tr>
                        <td u="layer-panel-vsplit-cell" style="height: 240px;">
                            <!-- layer panel -->
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%;">
                                <tr>
                                    <td u="layer-panel-hsplit-cell" style="width: 230px; height: 100%; background-color: #e5e5e5;">
                                        <div class="se-panel-outer">
                                            <div class="se-panel-inter" operation-area="layer-list" style="overflow: hidden; cursor: default;">
                                                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%; overflow: hidden;">
                                                    <tr>
                                                        <td style="height:26px;border-bottom:1px solid #a6a6a6;font-size:0px;line-height:0px;">
                                                            <!-- layer toolbox -->
                                                            <div u="layers-toolbox" class="se-tbx" style="margin-left: 6px;">
                                                                <div u="button-layer-add" class="se-btn se-sprite se-btn-layer-add" title="Add Layer"></div>
                                                                <div u="button-layer-delete" class="se-btn se-sprite se-btn-layer-delete" title="Delete Layer"></div>
                                                                <a u="button-layer-help" class="se-btn se-sprite se-btn-help" href="https://www.jssor.com/tutorial/003-layer-animation-slider.html" target="_blank" title="Tutorial: Layer Animation" tabindex="-1"></a>
                                                            </div>
                                                            <div class="se-tbx" style="float: right;">
                                                                <div class="se-layer-btn se-sprite se-layer-btn-eye"></div><div class="se-layer-btn se-sprite se-layer-btn-frame"></div><div class="se-layer-btn se-sprite se-layer-btn-lock"></div><div class="se-layer-btn se-sprite se-layer-btn-condition"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <!-- design layers -->
                                                            <div u="design-layers" class="se-panel-outer">
                                                                <div class="se-panel-inter" style="background-color: #e5e5e5; overflow: hidden;">
                                                                    <div style="position: relative; top: 0px; left: 0px; width: 250px; height: 201px; overflow: hidden; overflow-y: scroll;">
                                                                        <div u="layer-list" style="position: relative; top:0px; left: 0px; width: 230px;">
                                                                            <!-- layer list -->
                                                                        </div>
                                                                        <div style="height: 18px;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                    <td operation-area="ignore" style="width: 5px;">
                                        <div u="layer-panel-hsplit" class="se-hsplit-bar"></div>
                                    </td>
                                    <td style="height: 100%; background-color: #e5e5e5">
                                        <div class="se-panel-outer">
                                            <div class="se-panel-inter" operation-area="frameline" style="overflow: hidden;">
                                                <table u="frameline-table" cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%; overflow: hidden;">
                                                    <tr>
                                                        <td style="height: 100%;">
                                                            <div class="se-panel-outer">
                                                                <div class="se-panel-inter" style="overflow-x: scroll; overflow-y: hidden;">
                                                                    <div u="frameline-panel" style="position: absolute; top: 0px; left: 0px; width: 2000px; height: 100%;">
                                                                        <div style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden;">
                                                                            <!-- timeline -->
                                                                            <div u="timeline" class="se-timeline" style="position: relative; top: 0px; left: 0px; width: 100%; height: 26px; background-color: #e5e5e5; border-bottom: 1px solid #a6a6a6; background-position: -41px 0px; z-index: 1;"></div>
                                                                            <!-- frameline -->
                                                                            <div style="position: relative; top: 0px; left: 0px; width: 100%; height: 196px;  overflow: hidden; overflow-y: scroll;">
                                                                                <div u="frameline-list" class="se-frameline" style="position: relative; top:0px; left: 0px; width: 100%; z-index: 1;">
                                                                                </div>
                                                                                <div style="height: 18px;"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td operation-area="ignore" style="width: 17px; vertical-align: top;">
                                                            <div style="height: 26px; border-bottom: 1px solid #a6a6a6;"></div>
                                                            <div u="v-scrollbar" style="position: relative; top: 0px; left: 0px; height: 196px; overflow-x: hidden; overflow-y: scroll;">
                                                                <div u="content" style="width: 2px;"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <!-- timeline toolbox -->
                                                <div class="se-tbx" style="position: absolute; top: 0px; left: auto; right: 0px; width: 26px; height: 26px; z-index: 1;">
                                                    <div u="button-timeline-play" class="se-btn se-sprite se-btn-timeline-play" title="Play"></div>
                                                    <div u="button-timeline-stop" class="se-btn se-sprite se-btn-timeline-stop" title="Stop" style="display: none;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="height: 100%; background-color: #e5e5e5; border-left: 1px solid #a6a6a6;">
                <div class="se-panel-outer">
                    <div operation-area="ignore" class="se-panel-inter" style="overflow: hidden;">
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 100%; overflow: hidden;">
                            <tr>
                                <td>
                                    <div class="se-panel-outer">
                                        <div class="se-panel-inter" style="overflow: hidden; overflow-y: auto;">
                                            <!-- design properties -->
                                            <div u="property-descriptor" class="je-properties-panel" style="height: auto;">
                                            </div>
                                            <!--<div u="static-descriptor" class="je-properties-panel" style="display: none; height: auto;">
                                            </div>-->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr style="display:none;">
                                <td style="height: 22px;">
                                    <div class="se-panel-outer">
                                        <div class="se-panel-inter" style="overflow: hidden; background-color: #4d6082;">
                                            <div u="property-descriptor-tab" class="jt-tab-property"><?php _ex("Properties", "noun", 'jssor-slider'); ?></div>
                                            <!--<div u="static-descriptor-tab" class="jt-tab-property">Static Properties</div>-->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    

    <!--layout window-->
    <table u="jd-layout-window" class="se-fullfill jd-window jd-noselect" style="top:1%;left:1%;width:98%;height:98%;color:#000;" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="width:420px;height:70px;background-color:#e5e5e5;">
                <div u="tabs" class="jt-tabbox">
                    <div u="tab-layout" class="jt-tab jt-tab-layout">
                        <div class="jt-thumb"></div>
                        <div class="jt-text">
                            <?php _ex("Layout", "noun", 'jssor-slider'); ?>
                        </div>
                    </div>
                    <div u="tab-bullets" class="jt-tab jt-tab-bullets">
                        <div class="jt-thumb"></div>
                        <div class="jt-text">
                            <?php _ex("Bullets", "noun", 'jssor-slider'); ?>
                        </div>
                    </div>
                    <div u="tab-arrows" class="jt-tab jt-tab-arrows">
                        <div class="jt-thumb"></div>
                        <div class="jt-text">
                            <?php _ex("Arrows", "noun", 'jssor-slider'); ?>
                        </div>
                    </div>
                    <div u="tab-thumbnails" class="jt-tab jt-tab-thumbnails">
                        <div class="jt-thumb"></div>
                        <div class="jt-text">
                            <?php _ex("Thumbnails", "noun", 'jssor-slider'); ?>
                        </div>
                    </div>
                </div>
            </td>
            <td style="background-color:#e5e5e5;"></td>
            <td u="buttons" style="width: 280px;background-color:#e5e5e5;text-align:right;vertical-align:top;">
                <div u="button-ok" class="jd-button" style="width:72px;margin:6px;"><?php _ex("OK", "Name of a button", 'jssor-slider'); ?></div>
                <div u="button-cancel" class="jd-button" style="width:72px;margin:6px 42px 6px 6px;"><?php _ex("Cancel", "Name of a button", 'jssor-slider'); ?></div>
                <div u="button-close" class="jd-button jd-button-close" style="margin:6px;float:none;"></div>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border-top:1px solid #a6a6a6;">
                <div class="se-panel-outer">
                    <div class="se-panel-inter" style="overflow:hidden;">
                        <!-- canvas layout -->
                        <table class="se-canvas" cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;">
                            <tr>
                                <td style="height: 100%;">
                                    <div class="se-panel-outer">
                                        <div u="layout-panel" class="se-panel-inter" style="background-color:#d4d4d4;">
                                            <div u="vertical-margin" style="height:50%;min-height:220px;"></div>
                                            <!-- canvas panel -->
                                            <div class="se-canvas-panel" style="display:block;">
                                                <div u="layout-container" style="display:block;position:relative;top:-150px;margin:0 auto;width:600px;height:300px;">
                                                </div>
                                            </div>
                                            <div u="vertical-margin" style="height:50%;min-height:220px;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td u="properties" style="width:280px;height:100%;background-color:#e5e5e5;vertical-align:top;">
                                    <div class="se-panel-outer">
                                        <div class="se-panel-inter" style="overflow:hidden;overflow-y:auto;">
                                            <div u="property-descriptor" class="je-properties-panel" style="height:auto;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    

    <!--color window-->
    <div data-u="jd-color-window" class="jd-window jd-noselect" style="width:541px;height:328px;color:#000;background-color:#eee;border:1px solid #bbb;position:fixed;top:100px;left:100px;">
        <div data-u="color-panel" data-dialog-drag="0" style="position:absolute;top:14px;left:14px;width:256px;height:256px;border:1px solid transparent;">
            <div data-u="fill" class="jd-fill" style="background-color: #f00;">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100%">
                    <defs>
                        <linearGradient id="jd-gradient-black-0" x1="0%" y1="100%" x2="0%" y2="0%"><stop offset="0%" stop-color="#000" stop-opacity="1"></stop><stop offset="100%" stop-color="#000" stop-opacity="0"></stop></linearGradient>
                        <linearGradient id="jd-gradient-white-0" x1="0%" y1="100%" x2="100%" y2="100%"><stop offset="0%" stop-color="#fff" stop-opacity="1"></stop><stop offset="100%" stop-color="#fff" stop-opacity="0"></stop></linearGradient>
                    </defs>
                    <rect x="0" y="0" width="100%" height="100%" fill="url(#jd-gradient-white-0)"></rect>
                    <rect x="0" y="0" width="100%" height="100%" fill="url(#jd-gradient-black-0)"></rect>
                </svg>
            </div>
            <div data-u="picker" style="margin:-6px;position:absolute;top:256px;left:0px;width:8px;height:8px;border:2px solid #fff;border-radius:6px;"></div>
        </div>

        <div data-u="huebar" data-dialog-drag="0" class="jd-hue-bar" style="position:absolute;top:15px;left:285px;width:25px;height:256px;">
            <div class="jd-fill">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100%">
                    <defs>
                        <linearGradient id="gradient-hsv-0" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#ff0000" stop-opacity="1"></stop>
                            <stop offset="16.667%" stop-color="#ff00ff" stop-opacity="1"></stop>
                            <stop offset="33.333%" stop-color="#0000ff" stop-opacity="1"></stop>
                            <stop offset="50%" stop-color="#00ffff" stop-opacity="1"></stop>
                            <stop offset="66.667%" stop-color="#00ff00" stop-opacity="1"></stop>
                            <stop offset="83.333%" stop-color="#ffff00" stop-opacity="1"></stop>
                            <stop offset="100%" stop-color="#ff0000" stop-opacity="1"></stop>
                        </linearGradient>
                    </defs>
                    <rect x="0" y="0" width="100%" height="100%" fill="url(#gradient-hsv-0)"></rect>
                </svg>
            </div>
            <div data-u="slide" style="position:absolute;top:256px;left:0;width:25px;height:10px;margin-top:-5px;">
                <div style="position:absolute;top:0;left:0;width:0;height:0;margin-left:-5px;border:5px solid;border-color:transparent transparent transparent #000;"></div>
                <div style="position:absolute;top:0;left:25px;width:0px;height:0px;margin-left:-5px;border:5px solid;border-color:transparent #000 transparent transparent;"></div>
            </div>
        </div>

        <div data-dialog-drag="0" class="jd-trans" title="new" style="position:absolute;top:15px;left:334px;width:88px;height:88px;">
            <div data-u="new" class="jd-fill" style="background:#000;"></div>
        </div>
        <div data-dialog-drag="0" class="jd-trans" title="current" style="position:absolute;top:15px;left:436px;width:88px;height:88px;">
            <div data-u="current" class="jd-fill" style="background:#000;"></div>
        </div>

        <div data-u="hsb" style="position:absolute;top:113px;left:334px;width:100px;">
        </div>

        <div data-u="rgb" style="position:absolute;top:179px;left:334px;width:100px;height:88px;">
        </div>

        <div data-u="cmyk" style="position:absolute;top:113px;left:436px;width:100px;height:88px;">
        </div>

        <div data-u="raw" style="position:absolute;top:251px;left:346px;width:180px;height:88px;">
        </div>

        <div data-u="alphabar" data-dialog-drag="0" class="jd-trans jd-alpha-bar" style="position:absolute;top:284px;left:15px;width:300px;height:24px;">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100%">
                <defs>
                    <linearGradient id="jd-gradient-alpha-0" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop data-u="stop-0" offset="0%" stop-color="#000" stop-opacity="0"></stop>
                        <stop data-u="stop-100" offset="100%" stop-color="#000" stop-opacity="1"></stop>
                    </linearGradient>
                </defs>
                <rect data-u="alpha-rect" x="0" y="0" width="100%" height="100%" fill="url(#jd-gradient-alpha-0)"></rect>
            </svg>
            <div data-u="slide" style="position:absolute;top:0;left:298px;width:10px;height:25px;margin-left:-5px;">
                <div style="position:absolute;top:0px;left:0;width:0;height:0; margin-top:-5px;border:5px solid;border-color:#000 transparent transparent transparent;"></div>
                <div style="position:absolute;top:25px;left:0;width:0;height:0; margin-top:-5px;border:5px solid;border-color:transparent transparent #000 transparent;"></div>
            </div>
        </div>

        <div data-u="ok" data-dialog-drag="0" class="jd-button" style="margin:0; position:absolute;top:289px;left:351px;width:75px;height:24px;line-height:22px;">OK</div>
        <div data-u="cancel" data-dialog-drag="0" class="jd-button" style="margin:0; position:absolute;top:289px;left:437px;width:75px;height:24px;line-height:22px;">Cancel</div>
    </div>
    
    <!--#region gradient window-->
    <div data-u="jd-gradient-window" class="jd-window je-white" style="width:430px;height:328px;background-color:#eee; border:1px solid #bbb;position:fixed;top:100px;left:100px;">
        <!-- gradient square -->
        <div data-dialog-drag="0" class="jd-trans-medium" title="new" style="position:absolute;top:15px;left:15px;width:120px;height:90px;">
            <div data-u="gradient-square" style="position:absolute;top:0;left:0;width:100%;height:100%;"></div>
        </div>

        <!-- presets square -->
        <div data-u="presets-square" data-dialog-drag="0" class="jd-loading" style="position:absolute;top:15px;left:145px;width:90px;height:90px;font-size:0px;border:6px solid #494949;box-sizing:border-box;">
        </div>

        <!-- presets dropdown -->
        <div data-u="presets-dropdown" data-dialog-drag="0" data-dialog-command="presets-dropdown" class="jd-button jd-button-naked" style="margin:0;position:absolute;top:15px;left:236px;width:18px;min-width:2px;height:90px;">
            <div style="display:inline-block;position:absolute;top:50%;left:3px;width:0;height:0; margin-top:-3px;border:5px solid;border-color:#000 transparent transparent transparent;"></div>
        </div>

        <!-- gradient properties -->
        <div data-u="gradient-properties" style="position:absolute;top:12px;left:275px;width:150px;height:100px;">
        </div>

        <!-- gradient bar -->
        <div class="jd-trans-medium" data-dialog-drag="0" style="position:absolute;top:135px;left:15px;width:400px;height:30px;">
            <div data-u="gradient-bar" style="position:absolute;top:0;left:0;width:100%;height:100%;"></div>
        </div>

        <!-- stops operation bar -->
        <div data-u="gradient-stops" data-dialog-drag="0" class="jd-gradient-stops" style="position:absolute;top:165px;left:15px;width:400px;height:20px;">
        </div>

        <!-- stop properties -->
        <div style="position:absolute;top:210px;left:15px;width:400px;height:24px;">
            <div data-u="stop-properties">
            </div>

            <!-- stop buttons -->
            <div data-u="reverse-button" data-dialog-drag="0" class="jd-button jd-button-naked jd-button-reverse" style="float:right;width:24px;height:24px;" title="reverse"></div>
            <div data-u="delete-button" data-dialog-drag="0" class="jd-button jd-button-naked jd-button-delete" style="float:right;width:24px;height:24px;" title="delete"></div>
        </div>

        <!-- input value -->
        <input data-u="raw-gradient" style="position:absolute;top:289px;left:15px;width:220px;height:24px" />

        <!-- dialog buttons -->
        <div data-u="ok" data-dialog-drag="0" class="jd-button" style="margin:0; position:absolute;top:289px;left:255px;width:75px;height:24px;line-height:22px;">OK</div>
        <div data-u="cancel" data-dialog-drag="0" class="jd-button" style="margin:0; position:absolute;top:289px;left:340px;width:75px;height:24px;line-height:22px;">Cancel</div>

        <!-- presets dialog -->
        <div data-u="presets-dialog" style="position:absolute;top:105px;left:60px;width:330px;height:170px;background-color:#494949;">
            <div data-u="preset-list" class="jd-loading" style="padding:1px;position:absolute;top:6px;left:6px;width:318px;height:158px;background-color:#eee;overflow-x:hidden;overflow-y:auto;font-size:0px;box-sizing:border-box;">
            </div>
        </div>
    </div>
    <!--#endregion-->
    
    <!--rich text editor window-->

    <div data-u="jd-rich-text-editor-window" class="jd-window-inner" style="display:flex;padding:0;height:100%;flex-direction:column;">
        <div style="padding:0 5px;height:30px;background-color:#000;">
            <div data-u="visual-mode" data-tab="visual" ja-pushable="1" class="je-rich-text-toolitem" title="text mode" style="color:#fff;">
                Text
            </div>
            <div data-u="html-mode" data-tab="html" ja-pushable="1" class="je-rich-text-toolitem" title="html mode" style="color:#fff;">
                HTML
            </div>
        </div>
        <div data-u="input-visual" data-dialog-drag="0" contenteditable="true" style="padding:10px;height:100%;line-height:1.3;background-color:#fff;overflow:auto;border:none;outline:none;cursor:text;" placeholder="input text here" title="edit text"></div>
        <textarea data-u="input-html" style="display:none;margin:0;padding:10px;width:100%;height:100%;line-height:1.3;color:#fff;background-color:#000;overflow:auto;resize:none;border:none;outline:none;box-sizing:border-box;" placeholder="input html code here" title="edit html code"></textarea>
    </div>

</script>


<script>
    new wp_jssor_slider_admin_init(<?php echo json_encode(WP_Jssor_Slider_Globals::get_jssor_wordpress_admin_info()); ?>, <?php echo json_encode(WP_Jssor_Slider_Utils::get_jssor_wordpress_status_info()); ?>, <?php echo json_encode(null); ?>);
</script>
