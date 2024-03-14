<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

class raysgrid_Form {

    public function rsgd_display_form() {
        
        $do         = isset( $_GET['do'] ) ? sanitize_text_field($_GET['do']) : '';
        $action     = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : '';
        $id         = isset( $_GET['id'] ) ? sanitize_text_field($_GET['id']) : '';
        $val        = isset( $_POST['rsgd_type_name'] ) ? sanitize_text_field($_POST['rsgd_type_name']) : 'raysgridpost';
        $rsgd_tbls  = new raysgrid_Tables();
                
        require_once(RSGD_DIR . 'includes/admin/views/header.php');
            
        if ( empty($do) ) {
            
            require_once(RSGD_DIR . 'includes/admin/views/grids-list.php');
            
        } elseif ( $do == 'create' ) {
            
            require_once(RSGD_DIR . 'includes/admin/views/main-form.php');
            
        }
        
        require_once(RSGD_DIR . 'includes/admin/views/footer.php');
        
        if ( $action == 'save' && empty($id) ) {
            $rsgd_tbls->rsgd_insert_update($id);
        }

        if ( $do == 'clone' && !empty($id) ) {
            $rsgd_tbls->rsgd_duplicate_row(RSGD_TBL, $id);
        }
        
        if (isset($_POST['rsgd_name_btn'])) {
            if ( get_option( 'rsgd_type_name' ) !== false ) {
                if ( $val == '' ) {
                    update_option( 'rsgd_type_name', 'raysgridpost' );
                } else {
                    update_option( 'rsgd_type_name', $val );
                }
            }else {
                add_option( 'rsgd_type_name', $val, '', 'yes' );
            }
        }
        
        if ( $do == 'delete' && !empty($id) ) {
            $rsgd_tbls->rsgd_delRow($id);
        }
              
    }

}

new raysgrid_Form();
