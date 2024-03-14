<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

require_once(RSGD_DIR . 'includes/admin/views/header.php');

echo '<ul class="rsgd_tabs">';
    echo '<li class="active"><a href="#export_gr" data-toggle="tab"><i class="dashicons dashicons-upload"></i>'.esc_html__('Export Grids', RSGD_SLUG).'</a></li>';
    echo '<li><a href="#import_gr" data-toggle="tab"><i class="dashicons dashicons-download"></i>'.esc_html__('Import Grids', RSGD_SLUG).'</a></li>';
echo '</ul>';

echo '<div class="rsgd_tab_content">';

    echo '<div class="tab-pane active" id="export_gr">';
        echo '<div class="x_content">';
            echo '<div class="item form-group">';
                echo '<div class="lbl"><label class="opt-lbl">Export Grids</label><small class="description">'.esc_html__('Click the button below to export all available grids.', RSGD_SLUG).'</small></div>';
                echo '<div class="control-input">';
                    echo '<button type="submit" name="export" class="btn btn-success rsgd_lg_btn">'.esc_html__('Export Grids', RSGD_SLUG).'</button>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';

    echo '<div class="tab-pane" id="import_gr">';
        echo '<div class="x_content">';
                echo '<div class="item form-group">';
                    echo '<div class="lbl"><label class="opt-lbl">'.esc_html__('Upload .json file:', RSGD_SLUG).'</label>
                        <small class="description">'.esc_html__('Click the file upload below to import a .json file from your PC.', RSGD_SLUG).'</small></div>';
                    echo '<div class="control-input">';
                        echo '<input type="file" class="form-control" name="importfile" id="impFile" />';
                    echo '</div>';
                echo '</div>';
                echo '<div class="item form-group">';
                    echo '<div class="lbl"><label class="opt-lbl">'.esc_html__('Upload', RSGD_SLUG).'</label><small class="description">'.esc_html__('Click the button below to import from the file you uploaded.', RSGD_SLUG).'</small></div>';
                    echo '<div class="control-input">';
                        echo '<button type="submit" name="import" class="btn btn-success imp_btn rsgd_lg_btn">'.esc_html__('Import Grids', RSGD_SLUG).'</button>';
                    echo '</div>';
                echo '</div>';
        echo '</div>';
    echo '</div>';

echo '</div>';

echo '<span class="hidden adm">'.esc_attr(admin_url()).'</span>';

require_once(RSGD_DIR . 'includes/admin/views/footer.php');

