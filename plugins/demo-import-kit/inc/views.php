<?php
// Block direct access to the main plugin file.
defined('ABSPATH') or die('No script kiddies please!');
?>
<?php

$import_files_array = $this->import_files;

$primary_cat_array = $this->primary_cat;
$secondary_cat_array = $this->secondary_cat;
$upgrade_pro = $this->upgrade_pro;


if (!$import_files_array) { ?>
    <div class="import-kit-page import-kit-no-option">
        <header class="dik-default-header">
            <h1>
                <?php echo esc_html__('Welcome to the Demo Import Kit for ', 'demo-import-kit') . esc_html(wp_get_theme()); ?>
            </h1>
            <div class="dik-default-subheading">
                <?php
                esc_html_e('Thank you for choosing the ', 'demo-import-kit');
                echo esc_html(wp_get_theme()); ?>
                <?php esc_html_e('theme. This quick demo import setup will help you configure your new website like theme demo. You may also require Demo Import Kit Companion Plugin. It should only take less than 5 minutes.', 'demo-import-kit') ?>
            </div>
        </header>
        <div class="dik-default-main">
            <h3><?php esc_html_e('Upload a ZIP file containing demo content', 'demo-import-kit'); ?></h3>
            <div class="media-frame wp-core-ui mode-grid mode-edit hide-menu dik-drag-drop">
                <div class="uploader-inline">
                    <div class="uploader-inline-content no-upload-message">
                        <div class="upload-ui">
                            <h3 class="upload-instructions drop-instructions"><?php esc_html_e('Drop and Drop ZIP file to Import Data', 'demo-import-kit') ?></h3>
                            <p class="upload-instructions drop-instructions">or</p>
                            <div class="twp-upload-btn-wrapper">
                                <button type="button" class="browser button button-hero twp-btn-uploader"
                                        id="__wp-uploader-id-1"
                                        style="display: inline-block; position: relative; z-index: 1;"><?php esc_html_e('Select Files', 'demo-import-kit') ?></button>
                                <input type="file" class="twp-content-upload-default" name="twp-content-file-upload"
                                       id="twp-content-file-upload">
                            </div>
                        </div>
                        <div class="upload-inline-status"></div>
                        <span class="two-upload-status"></span>
                        <div class="post-upload-ui">
                            <?php
                            $max_upload_size = wp_max_upload_size();
                            if (!$max_upload_size) {
                                $max_upload_size = 0;
                            }
                            ?>
                            <p class="max-upload-size">
                                <?php
                                printf(
                                /* translators: %s: Maximum allowed file size. */
                                    esc_html__('Maximum upload file size: %s.', 'demo-import-kit'),
                                    esc_html(size_format($max_upload_size))
                                );
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dik-no-grid-view">
                <button class="button button-primary"
                        id="twp-zip-file-upload"><?php esc_html_e('Import', 'demo-import-kit') ?></button>
                <div class="dik-header-download">
                    <form method="post" id="demo-import-kit-filters" action="">
                        <input type="hidden" name="demo-import-kit-download" value="true"/>
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e('Download Export File', 'demo-import-kit') ?>">
                    </form>
                </div>
            </div>
        </div>

    </div>
    		
<?php } else {

//    Converting import Data Array to Object for Premium Themes

    if( is_array($import_files_array) ){
        if( is_array($import_files_array[0]) ){
            $import_files_array = json_decode( json_encode($import_files_array) );
        }
    }
    $this->import_files = $import_files_array;

    ?>

    <div class="import-kit-page import-kit-has-option">
        <?php Demo_Import_Kit_Base_Class::demo_import_kit_primary_tab_render($primary_cat_array, $import_files_array); ?>
        <?php if ($import_files_array || $secondary_cat_array) { ?>
            <div class="dik-plugin-content">

                <div class="dik-plugin-contentpanel">
                    <div class="dik-plugin-contentrow">
                        <div class="dik-content-wrapper <?php if (!$secondary_cat_array) { echo 'dik-no-secondary-cat'; } ?>">
                            <?php if ($import_files_array) {
                                Demo_Import_Kit_Base_Class::demo_import_kit_secondary_tab_render($secondary_cat_array);
                            } ?>
                            <div class="dik-main-content">
                                <div class="dik-wrapper">
                                    <?php if ($import_files_array) { ?>
                                        <div class="dik-row dik-grid-panel dik-grid-main">
                                            <?php foreach ($import_files_array as $key => $import_file) {
                                                $class = '';
                                                if (isset($import_file->secondary_category_id)) {
                                                    $cat_in_1 = $import_file->secondary_category_id;
                                                    if ($cat_in_1) {
                                                        foreach ($cat_in_1 as $cat_1) {
                                                            $class .= $cat_1 . ' ';
                                                        }
                                                    }
                                                }
                                                Demo_Import_Kit_Base_Class::demo_import_kit_content_render($class, $import_file, $key, $upgrade_pro);
                                            } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php Demo_Import_Kit_Base_Class::demo_import_kit_upgrade_to_pro($upgrade_pro); ?>
        <?php } ?>
    </div>
<?php } ?>