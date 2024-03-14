<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }
$id          = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
$do          = isset($_GET['do']) ? sanitize_text_field( $_GET['do'] ) : '';
$rsgd_tbls   = new raysgrid_Tables();
$allTables   = $rsgd_tbls->rsgd_selectWithId($id);
$impexp      = ( isset($_GET["page"]) && trim($_GET["page"]) == 'raysgrid-exp' ) ? trim ( sanitize_text_field( $_GET["page"] ) ) : '';
$strs = $cl = $sync = '';
$post_type_name = ( get_option( 'rsgd_type_name' ) ) ? get_option( 'rsgd_type_name' ) : 'raysgridpost';
if( $impexp ){
    $cl = 'rsgd_import_form';
    $strs = '-exp&do=import';
    $sync = ' enctype="multipart/form-data"';
} else if (empty($id) && !empty($do) ) {
    $strs = '&do=create&action=save';
    $cl = 'rsgd_form';
} else if ( !empty($id) && !empty($do) ){
    $strs = '&do=create&action=edit&id='.esc_attr($id);
    $cl = 'rsgd_form';
} else {
    $cl = 'list_form';
}

echo '<div class="'.esc_attr(RSGD_SLUG).'-form">';
        
    echo '<form action="'.esc_url(admin_url().'admin.php?page='.esc_attr(RSGD_PFX).$strs).'"'.wp_kses($sync, true).' method="post" class="'.esc_attr($cl).'" novalidate>';

		wp_nonce_field( 'rsgd_nonce_fields' , 'rsgd_nonce_fields' );

        echo '<div class="rsgd_logo">';
            echo '<img alt="'.esc_html__('RAYS Grid', RSGD_SLUG).'" src="'.esc_attr(RSGD_URI) .'assets/admin/images/logo.png" />';
            
            echo '<div class="rsgd_popup_settings">';
                echo '<h3 class="titl">General Settings <a class="rsgd_close_settings" href="#"><i class="dashicons dashicons-no"></i></a></h3>';
                echo '<div class="setings_content"><label>'.esc_html__('Post Type Slug:', RSGD_SLUG).'</label>';
                echo '<input type="text" value="'.esc_attr($post_type_name).'" placeholder="'.esc_attr($post_type_name).'" name="rsgd_type_name" id="rsgd_type_name" class="form-control" />';
                echo '<i class="imp_hint"><b class="rsgdred">Important:</b> If you changed the slug, Old portfolio posts will not be available anymore, you will have to add them again.</i>';
                echo '<button type="submit" name="rsgd_name_btn" id="rsgd_name_btn" class="btn-success rsgd_name_btn">'.esc_html__('Save', RSGD_SLUG).'</button></div>';
            echo '</div>';
            
            echo '<div class="top-btns">';
                if ( empty($id) && !empty($do) ) {
                    echo '<span class="rsgd_error_list"></span>';
                    echo '<button type="submit" name="rsgd_save_btn" id="rsgd_save_btn" class="btn-success rsgd_save_btn"><i class="dashicons dashicons-thumbs-up"></i> '.esc_html__('Save', RSGD_SLUG).'</button>';
                    echo '<a href="'. esc_url(admin_url()) .'admin.php?page='.esc_attr(RSGD_PFX).'" id="rsgd_save_btn" class="rsgd_cancel_btn"><i class="dashicons dashicons-no-alt"></i> '.esc_html__('Cancel', RSGD_SLUG).'</a>';
                } else if ( !empty($id) && !empty($do) ){
                    echo '<span class="rsgd_error_list"></span>';
                    echo '<button type="submit" name="rsgd_edit_btn" id="rsgd_edit_btn" class="btn-success rsgd_edit_btn"><i class="dashicons dashicons-edit"></i>'.esc_html__('Save', RSGD_SLUG).'</button>';
                    echo '<a href="'. esc_url(admin_url()) .'admin.php?page='.esc_attr(RSGD_PFX).'" id="rsgd_save_btn" class="rsgd_cancel_btn"><i class="dashicons dashicons-no-alt"></i> '.esc_html__('Cancel', RSGD_SLUG).'</a>';
                } else {
                    echo '<a href="'. esc_url(admin_url()) .'admin.php?page='.esc_attr(RSGD_PFX).'&do=create" name="rsgd_add_new" id="rsgd_add_new" class="btn-success add_new"><i class="dashicons dashicons-plus-alt"></i>'.esc_html__('New', RSGD_SLUG).'</a>';
                    echo '<a class="top_exp" href="'. esc_url(admin_url()) .'admin.php?page='.esc_attr(RSGD_PFX).'-exp"><i class="dashicons dashicons-download"></i>'.esc_html__('Import / Export', RSGD_SLUG).'</a>';
                    echo '<a class="top_help" href="http://www.it-rays.net/docs/raysgrid/" target="_blank"><i class="dashicons dashicons-info"></i>'.esc_html__('Help', RSGD_SLUG).'</a>';
                    echo '<a class="top_settings" href="#" target="_blank"><i class="dashicons dashicons-admin-settings"></i>'.esc_html__('Settings', RSGD_SLUG).'</a>';
                }
                
            echo '</div>';
            
        echo '</div>';
        
        echo '<div class="rsgd_form_title">';
            echo '<h2>';
                if( $impexp ){
                    echo '<i class="dashicons dashicons-admin-tools"></i>'.esc_html__('Import / Export Grids', RSGD_SLUG); 
                } else if ( empty($do) ) {
                    echo '<i class="dashicons dashicons-dashboard"></i>'.esc_html__('Grids', RSGD_SLUG).' <small>'.esc_html__('List of available grids', RSGD_SLUG).'</small>';
                } else {
                     if (empty($id)) {
                        echo '<i class="dashicons dashicons-menu"></i>' . __('Create New Grid', RSGD_SLUG) . '<small>' . __('Choose from the following options', RSGD_SLUG) . '</small>';
                    } else {
                        echo '<i class="dashicons dashicons-edit"></i>' . __('Edit Grid', RSGD_SLUG) . '<small>'. esc_html($allTables[0]->title) .'</small>';
                    }
                }
            echo '</h2>';
        echo '</div>';
        
        echo '<div class="x_panel">';
