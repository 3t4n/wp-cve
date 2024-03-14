<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

$dbObj = new raysgrid_Tables();
$allTables = $dbObj->rsgd_select();
foreach ($allTables[1] as $i) {
    if (empty($i)) {
        echo '<div class="tbl no_grids"><i class="dashicons dashicons-no"></i>'.esc_html__('No Grids Were Found.', RSGD_SLUG).'</div>';
    } else {
        echo '<div class="x_content">';
            echo '<table class="rsgd_data_table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th class="t-center" style="width: 10px">'.esc_html__('ID', RSGD_SLUG).'</th>';
                        echo '<th>'.esc_html__('Name', RSGD_SLUG).'</th>';
                        echo '<th>'.esc_html__('Shortcode', RSGD_SLUG).'</th>';
                        echo '<th class="t-center lst-th">'.esc_html__('Settings', RSGD_SLUG).'</th>';
                    echo '</tr>';
                echo '</thead>';
                
                echo '<tbody>';
                foreach ($allTables[0] as $sel) { 
                    $getDb = $dbObj->rsgd_selectWithId($sel->id);
                    echo '<tr>';
                        echo '<td class="t-center">'. esc_html($sel->id) .'</td>';
                        echo '<td style="font-weight:bold">'. esc_html($sel->title) .'</td>';
                        echo '<td>'. esc_html($sel->shortcode) .'</td>';
                        echo '<td class="t-center nowrap inline-cell">';
	                        if (isset($sel->id)) {

	                            echo '<a class="edit_btn" href="'.admin_url().'admin.php?page='.esc_attr(RSGD_PFX).'&do=create&id='.esc_attr($sel->id).'" id="rg-edit-'.esc_attr($sel->id).'" title="'.esc_html__('Edit', RSGD_SLUG).'"><i class="dashicons dashicons-admin-generic"></i></a>';
	                            echo '<a class="clone_btn" href="#" id="rg-clone-'.esc_attr($sel->id).'" title="'.esc_html__('Duplicate', RSGD_SLUG).'"><i class="dashicons dashicons-admin-page"></i></a>';
	                            echo '<a class="delete_btn" href="#" id="rg-delete-'.esc_attr($sel->id).'" title="'.esc_html__('Remove', RSGD_SLUG).'"><i class="dashicons dashicons-trash"></i><span class="cs-lod dashicons dashicons-image-rotate"></span></a>';

	                        }
                        echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
            echo '</table>';
    }
}
