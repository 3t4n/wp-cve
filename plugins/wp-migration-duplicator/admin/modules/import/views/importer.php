<?php
if (!defined('ABSPATH')) {
	exit;
}

$import_data_size_per_req = isset($advanced_import_settings['im_data_size_per_req']) ? $advanced_import_settings['im_data_size_per_req'] : '';
$import_im_db_file_per_req = isset($advanced_import_settings['im_db_file_per_req']) ? $advanced_import_settings['im_db_file_per_req'] : '';

?>

<style type="text/css">
    
	.wt_mgdp_import_log_main {
		display: none;
		font-weight: bold;
		padding-bottom: 5px;
                    margin-left: 15px;
	}

	.wt_mgdp_import_loglist_main {
		display: none;
		float: left;
		width: 95%;
		height: 175px;
		overflow: auto;
		padding: 10px 0px;
		margin-bottom: 20px;
		background: #fdfdfd;
		box-shadow: inset 0 0 3px #ccc;
                    margin-left: 15px;
	}

	.wt_mgdp_import_loglist_inner {
		float: left;
		width: 98%;
		height: auto;
		overflow: auto;
		margin: 0px 1%;
		font-style: italic;
	}
	.wt_mgdp_import_form label {
		font-weight: bold;
	}
            .wt_width{
        width:98%
    }
    .wt_restore_wrapper {
        background: #FFF;
        /*height: 200px;*/
        /* padding: 18px 33px; */
        padding: 0px 33px 33px 33px ;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
        .wf_import_loader {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 3px solid rgba(69,89,89,.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 1s ease-in-out infinite;
  -webkit-animation: spin 1s ease-in-out infinite;
  margin-top: -2%;
  margin-left: 8px;
}

@keyframes spin {
  to { -webkit-transform: rotate(360deg); }
}
@-webkit-keyframes spin {
  to { -webkit-transform: rotate(360deg); }
}


.wt_mgdp_dropzone {
    text-align: center;
    cursor: pointer;
    width: 70%;
    height: 200px;
    border: dashed 3px #ccc;
}

/**
*   Dropzone: Drag and Drop file upload
*/
.wt_mgdp_dropzone{ text-align:center; cursor:pointer; width:100%; height:200px; border:dashed 3px #ccc; }
.wt_mgdp_dropzone.wt_drag_start{ border-color:#92b0b3; background:#c8dadf; }
.dz-preview{ width:500px; display:inline-block; margin-top:70px;line-height: 24px; }
.dz-message{ font-size:1.5em; margin-top:95px; line-height:14px; }
.wt_mgdp_dropzone .dz-preview .dz-details{ font-size:14px}
.wt_mgdp_dropzone .dz-preview.dz-processing .dz-progress{ opacity:1;-webkit-transition:all .2s linear;-moz-transition:all .2s linear;-ms-transition:all .2s linear;-o-transition:all .2s linear;transition:all .2s linear}
.wt_mgdp_dropzone .dz-preview.dz-complete .dz-progress{opacity:0;-webkit-transition:opacity .4s ease-in;-moz-transition:opacity .4s ease-in;-ms-transition:opacity .4s ease-in;-o-transition:opacity .4s ease-in;transition:opacity .4s ease-in}
.wt_mgdp_dropzone .dz-preview:not(.dz-processing) .dz-progress{-webkit-animation:pulse 6s ease infinite;-moz-animation:pulse 6s ease infinite;-ms-animation:pulse 6s ease infinite;-o-animation:pulse 6s ease infinite;animation:pulse 6s ease infinite}
.wt_mgdp_dropzone .dz-preview .dz-progress{opacity:1;z-index:1000;pointer-events:none;position:absolute;height:16px;margin-top:-8px;margin-left:-40px;background:rgba(255,255,255,.9);-webkit-transform:scale(1);border-radius:8px;overflow:hidden}
.wt_mgdp_dropzone .dz-preview .dz-progress .dz-upload{background:#333;background:linear-gradient(to bottom,#666,#444);position:absolute;top:0;left:0;bottom:0;width:0;-webkit-transition:width .3s ease-in-out;-moz-transition:width .3s ease-in-out;-ms-transition:width .3s ease-in-out;-o-transition:width .3s ease-in-out;transition:width .3s ease-in-out}
.wt_mgdp_dropzone .dz-preview .dz-progress{width:300px;margin-left:100px;margin-top:5px}
.wt_mgdp_dropzone .dz-preview .dz-progress .dz-upload{background:#2092ea}
.wt_mgdp_dz_file_name, .wt_mgdp_dz_remove_link,.wt_mgdp_dz_file_success_msg, .wt_mgdp_dz_file_success{ display:inline-block; font-size:14px; }
.wt_mgdp_dz_remove_link{ cursor:pointer; color:#4289a9; }
.wt_mgdp_dz_file_success_msg,.wt_mgdp_dz_file_success{ line-height:20px; }
.wt_mgdp_dropzone .dz-preview .dz-progress .dz-upload-info{display:inline-block;margin-left: 100px;width:100%;}
.wt_mgdp_dropzone.dz-started .dz-message{ display:none;}
</style>
<?php include WT_MGDP_PLUGIN_PATH . 'admin/modules/import/views/_import_now.php'; ?>

<h3><?php _e('Restore', 'wp-migration-duplicator'); ?></h3>
<div class="postbox wt_width post-box-over-content">
  <?php  
/* scan the directory and make the zip list */
$zip_list = array();
if (is_dir(Wp_Migration_Duplicator::$backup_dir)) {
	foreach (new DirectoryIterator(Wp_Migration_Duplicator::$backup_dir) as $file) {
		if ($file->isFile()) {
			$file_name = $file->getFilename();
			$file_ext_arr = explode(".", $file_name);
			$file_ext = end($file_ext_arr);
			if ($file_ext == 'zip') {
				$zip_list[$file_name] = array(content_url() . Wp_Migration_Duplicator::$backup_dir_name . "/" . $file_name, $file->getSize());
			}
		}
	}
}
?>
    <div style="padding-left:33px;padding-top: 5px;border-bottom: 1px solif #b6b6b7;">
        <h3><?php _e('Recent Backups', 'wp-migration-duplicator'); ?> </h3></div>
    <div class="wt_restore_wrapper">
    <!--	<p>
        <?php _e('Lists the activity log of every export with the options to restore the backup or delete the logs that are no longer necessary.', 'wp-migration-duplicator'); ?>
            </p>-->
        <?php
        if ($total_list > 20) {
            ?>
            <div class="wt_warn_box">
            <?php _e('Your backups are getting larger. Deleting unwanted backups will save space.', 'wp-migration-duplicator'); ?>
            </div>
            <?php
        }
        do_action('wt_mgdp_backups_table_top', $backup_list, $offset);
        ?>
        <table class="wt_mgdp_list_table wt_mgdp_backup_list_table">
            <thead>
                <tr>
                    <th style="width:50px;height: 34px;">#</th>
                    <th><?php _e('File', 'wp-migration-duplicator'); ?></th>
                    <th><?php _e('Date', 'wp-migration-duplicator'); ?></th>
                    <th><?php _e('Size', 'wp-migration-duplicator'); ?></th>
                    <th><?php _e('Location', 'wp-migration-duplicator'); ?></th>
                    <th><?php _e('Status', 'wp-migration-duplicator'); ?></th>
                    <th><?php _e('Actions', 'wp-migration-duplicator'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = $offset;  
                //$backup_list = array();
                foreach ($backup_list as $backup) {
                    $log_data = json_decode($backup['log_data'], true);
                    $file_name = (isset($log_data['backup_file']) ? $log_data['backup_file'] : '');
                    $file_path = Wp_Migration_Duplicator::$backup_dir . '/' . $file_name;
                    $export_option =(isset($log_data['export_location']) ? $log_data['export_location'] : '');
                    $file_exists = (file_exists($file_path) && $file_name != "" ? true : false);
                    $file_url = '';
                    $num++;
                    if (isset($zip_list[$file_name])) {
                        unset($zip_list[$file_name]);
                    }
                    ?>
                    <tr>
                        <td>
                            <?php echo esc_html($num); ?>
                        </td>
                        <td>
                            <?php
                            if ($file_exists) {
                                $file_url = Wp_Migration_Duplicator_Admin::generate_backup_file_url($file_name);
                                ?>
                                <a href="<?php echo esc_url($file_url); ?>" target="_blank">
                                <?php echo wp_kses_post($file_name); ?>
                                </a>
                                <?php
                            } else {
                                echo esc_attr($file_name) . ' <span style="color:red; display:inline;">(' . __('File not found', 'wp-migration-duplicator') . ')</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo date('Y-m-d h:i:s A', esc_attr($backup['created_at'])); ?></td>
                        <td>
                            <?php
                            if ($file_exists) {
                                echo Wp_Migration_Duplicator::format_size_units(filesize($file_path));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo wp_kses_post($export_option);
                            ?>
                        </td>
                        <td>
                            <?php
                            echo Wp_Migration_Duplicator::get_status_label(esc_attr($backup['status']));
                            ?>
                        </td>
                        <td>
                            <button data-id="<?php echo esc_attr($backup['id_wtmgdp_log']); ?>" title="<?php _e('Delete', 'wp-migration-duplicator'); ?>" class="button button-secondary wt_mgdp_delete_backup" style="width:40px"><span class="" style="margin-top:4px;"><img src="<?php echo esc_url(plugins_url(basename(plugin_dir_path(WT_MGDP_PLUGIN_FILENAME))).'/admin/images/delete.svg'); ?>" style="width:12px"></img></span></button>

                            <?php
                            $file_url = content_url() . Wp_Migration_Duplicator::$backup_dir_name . "/" . $file_name;
                            do_action('wt_mgdp_backups_action_column', $backup, $file_exists, $file_url);
                         
                            ?>
                                                           
                        </td>
                    </tr>
                    <?php
                }
                $no_bckup_html = '<tr><td colspan="8" style="text-align:center; padding:10px">' . __('No backups found.', 'wp-migration-duplicator') . '</td></tr>';
                if (count($backup_list) == 0) {
                    echo wp_kses_post($no_bckup_html);
                }
                ?>
            </tbody>
        </table>
    </div>     


</div>

<div id='wt_import'>
    <div style="width:98%">
        <div style="display:inline-flex"><h3><?php _e('Quick Import', 'wp-migration-duplicator'); ?></h3></div> </div>
    <div class="postbox wt_width post-box-over-content" id="import_class">
        <div class="wt-migrator-accordion-tab wt-migrator-accordion-export-storage-settings" >
            <a  href="#"><?php echo esc_html__('Import', 'wp-migration-duplicator'); ?></a>
            <div class="wt-migrator-accordion-content" style ="border-top: 2px dotted #b6b6b7;">

                <p><?php _e('Select the location from where you want to import the zip file.'); ?></p>

                <table class="wf-form-table wt_mgdp_import_options" style="max-width:650px;">
                    <tr class="wt_mgdp_import_er" style="display:none;">
                        <td colspan="3" style="color:red;"></td>
                    </tr>
                    <tr>
                        <th style="font-weight: 400"><?php _e('Import From', 'wp-migration-duplicator') ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Import data using a zip file(containing files and database) from the server that is to be migrated.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                        <td>
                            <?php
                            $import_options = Wp_Migration_Duplicator_Import::get_possible_import_methods();
                            ?>
                            <div class="wt-migrator-select-container">
                                <?php
                                if (is_array($import_options)) {
                                    echo '<select name="wt_mgdb_import_option" data-option-type="import" >';
                                    foreach ($import_options as $value => $import_option) {
                                        echo '<option value="' . esc_attr($value) . '">' . esc_attr($import_option) . '</option>';
                                    }

                                    echo '</select>';
                                }
                                ?>
                                <span class="spinner"></span>
                            </div> 

                        </td>
                    </tr>
                </table>
                <?php do_action('mgdp_after_import_form'); ?>
                <div class="child-form-item child-wt_mgdb_import_option wt_mgdb_import_option_local" style="display:block">
                    <table class="wf-form-table wt_mgdp_import_form" style="max-width:650px;margin-bottom:20px; margin-top:10px;">
<!--                        <tr>
                            <th></th>
                            <td style="padding:0px 10px;">
                                <input style="text-align:center;" type="button" name="upload-btn" id="upload-btn" class="button button-primary" value="<?php _e('Upload backup file', 'wp-migration-duplicator') ?>"> 
                                <span class="wt_mgdp_import_attachment_url"></span>

                            </td>
                            
                        </tr>-->
                        <tr>
                            	<input type="hidden" id="local_file" name="wt_mgdp_local_file" value="" />
                                <input type="hidden" name="attachment_url" id="attachment_url">
						
						<div id="mgdp_import_dropzone" class="wt_mgdp_dropzone" wt_mgdp_dropzone_target="#local_file">
							<div class="dz-message">
								<?php _e('Drop files here or click to upload');?> <br /> 							
                                                                <br /><div class="wt_mgdp_dz_file_success"></div> <br />
                                                                <div class="wt_mgdp_dz_file_success_msg"></div> <br />
								<div class="wt_mgdp_dz_file_name"></div> <br />
								<div class="wt_mgdp_dz_remove_link"></div> <br />
							</div>
						</div>
                        </tr>
                    </table>
                    <div class='increase_upload_size' style=" width:98%;">
                        <a href="https://www.webtoffee.com/increase-maximum-upload-file-size-in-wordpress-migrator/" target="_blank"><?php _e('How to increase maximum upload file size', 'wp-migration-duplicator'); ?></a><br/><br/>
                    </div>

                </div>


                <div style="clear: both;"></div>
                <div class="wt-mgdp-plugin-toolbar bottom" style="padding-left: 16px;">
                    <div class="left">
                    </div>
                    <div class="right">
                        <input type="hidden" id="extension_zip_loaded_imp" name="extension_zip_loaded_imp" value=<?php $extension_zip_loaded = extension_loaded('zip') ? 'enabled' : 'disabled';
                echo esc_attr($extension_zip_loaded); ?>>
                        <input type="hidden" id="extension_zlib_loaded_imp" name="extension_zlib_loaded_imp" value=<?php $extension_zlib_loaded = extension_loaded('zlib') ? 'enabled' : 'disabled';
                echo esc_attr($extension_zlib_loaded); ?>>
                        <button name="wt_mgdp_import_btn" id="wt_mgdp_import_btn" class="button button-primary" style="float:right;width: 100px;"><?php _e('Import', 'wp-migration-duplicator'); ?></button>
                        <!--<button name="" class="button button-primary wt_mgdp__start_new_import" style="float:right; display:none;"><?php _e('wp-migration-duplicator', 'wf-woocommerce-packing-list'); ?></button>-->
                        <span class="spinner" style="margin-top:11px;"></span>
                    </div>
                </div>
                

					

            </div>


        </div>
    </div>
</div>

<div class="postbox wt_width post-box-over-content">
    <div class="wt-migrator-accordion-tab wt-migrator-accordion-export-storage-settings" >
        <a  href="#"><?php echo esc_html__('Advanced Options', 'wp-migration-duplicator'); ?></a>
        <div class="wt-migrator-accordion-content" style ="border-top: 2px dotted #b6b6b7;">
            <p style="font-size:14px"><?php _e('Advanced restore options.Fill up the fields as per your server performance. For high performance servers, you can specify a greater data size and bigger number of files. Each will be processed per request. ', 'wp-migration-duplicator'); ?></p>

            <table class="form-table" style="margin-left: 20px;">       
                <tr>
                    <th style="width:400px; font-weight: 400">
                        <label for="dta_size"><?php _e('Data Size Limit (mb)', 'wp-migration-duplicator'); ?></label>
                        <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The maximum data size in megabytes that the server will restore for every request. For servers with high performance, you can handle a greater data size. Defaulted to 1 mb.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                    </th>
                    <td>
                        <input type="text" name="im_data_size_per_req" id="data_size_per_req" placeholder="<?php _e('1', 'wp-migration-duplicator'); ?>" value="<?php echo esc_attr($import_data_size_per_req);   ?>" class="input-text text_width" /><?php _e(' mb', 'wp-migration-duplicator'); ?>
                    </td>
                </tr>
                <tr>
                    <th style="font-weight: 400">
                        <label for="db_record_per_req"><?php _e('Number of Database Files', 'wp-migration-duplicator'); ?></label>
                        <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The number of files that the server will restore per request. With high performance servers, you can handle a greater number of files. Defaulted to 5 records.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                    </th>
                    <td>
                        <input type="text" name="im_db_file_per_req" id="db_record_per_req"  value="<?php echo esc_attr($import_im_db_file_per_req);   ?>"  placeholder="<?php _e('5', 'wp-migration-duplicator'); ?>" class="input-text text_width" />
                    </td>
                </tr>
            </table>
            <div style="height:30px">
                <button name="wt_mgdp_save_import_settings_btn" id="wt_mgdp_save_settings_btn" class="button button-primary" style="float:right;width: 80px;"><?php _e('Save', 'wp-migration-duplicator'); ?></button>
                <span class="spinner spinner-save-import" style="margin-top:11px;margin: 6px 15px 0px 0px;"></span>
            </div>
        </div>
    </div>
</div>

<?php include WT_MGDP_PLUGIN_PATH . '/admin/partials/wt_migrator_upgrade_to_pro.php'; ?>



