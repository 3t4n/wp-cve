<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Callbacks;

require_once \dirname(__FILE__,2) . '/Base/class-basecontroller.php';
use EDE\Inc\Base\BaseController;

class PostMetaCallback extends BaseController
{
    public function save_meta()
    {
        add_action( 'save_post', array($this,'saveMetaboxField') );
    }

    public function edeMetaboxForm( $post )
    {
        wp_nonce_field( 'ede_embedder_nonce_id', 'ede_embedder_nonce' );

        $ede_source_type = get_post_meta( $post->ID, 'ede_source_type', true );
        $ede_select_type = get_post_meta( $post->ID, 'ede_select_type', true );
        $ede_upload_file_url = get_post_meta( $post->ID, 'ede_upload_file_url', true );
        $ede_external_file_url = get_post_meta( $post->ID, 'ede_external_file_url', true );
        $ede_gdocs_file_url = get_post_meta( $post->ID, 'ede_gdocs_file_url', true );

        if (($post->post_type == "ede_embedder") && isset($_REQUEST['action']) && isset($_REQUEST['action']) == "edit" ) {
            ?>
            <div class="ede_shortcode">
                    <?php _e('Shortcode for this Document is : <code>[EDE id="'.$post->ID.'"]</code> (Insert it anywhere in your post/page and show your Document)','easy-document-embedder');?>
            </div>
            <?php 
        }
        
        if ($ede_source_type === "EL"  && isset($_REQUEST['action']) && isset($_REQUEST['action']) == "edit" ) {
            $el = "display:block";
            $ml = "display:none";
            $gdl = "display:none";
        } else if( $ede_source_type === "ML"  && isset($_REQUEST['action']) && isset($_REQUEST['action']) == "edit" ) {
            $el = "display:none";
            $ml = "display:block";
            $gdl = "display:none";
        } else if( $ede_source_type === "GDL"  && isset($_REQUEST['action']) && isset($_REQUEST['action']) == "edit" ) {
            $el = "display:none";
            $ml = "display:none";
            $gdl = "display:block";
        } else {
            $el = "display:none";
            $ml = "display:none";
            $gdl = "display:none";
        }

        ?>
        
        <div class="ede_metabox_div">
            <h3 class="text-center">Easy Embedder</h3>
            <table class="field_table">
                <tr>
                    <td>
                        <label for="ede_source_type">Select Source Type</label>
                    </td>
                    <td>
                        <select name="ede_source_type" id="ede_source_type">
                            <option  value="">Select Source Type</option>
                            <option <?php selected( $ede_source_type,'ML' )?> value="ML">Media Library</option>
                            <option <?php selected( $ede_source_type,'EL' )?> value="EL">External Link</option>
                            <option <?php selected( $ede_source_type,'GDL' )?> value="GDL">Google Docs Link</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="ede_select_type">Choose Format</label></td>
                    <td>
                    <select id="ede_select_type" name="ede_select_type">
                        <option value="">Select Format</option>
                        <option <?php selected( $ede_select_type,'pdf' )?> value="pdf">pdf</option>
                        <option <?php selected( $ede_select_type,'doc' )?> value="doc">doc</option>
                        <option <?php selected( $ede_select_type,'docx' )?> value="docx">docx</option>
                        <option <?php selected( $ede_select_type,'xls' )?> value="xls">xls</option>
                        <option <?php selected( $ede_select_type,'xlsx' )?> value="xlsx">xlsx</option>
                        <option <?php selected( $ede_select_type,'ppt' )?> value="ppt">ppt</option>
                        <option <?php selected( $ede_select_type,'pptx' )?> value="pptx">pptx</option>
                    </select>
                    </td>
                    <td></td>
                </tr>
            </table>


            <table class="field_table metaContent external-content" id="metaContent" style=<?php echo $el?>>
                
                <tr>
                    <td><label for="ede_external_file_url">External Link</label></td>
                    <td><input type="text" class="widefat" id="ede_external_file_url" name="ede_external_file_url" value="<?php echo esc_attr( $ede_external_file_url );?>"></td>
                    <td></td>
                </tr>
            </table>


            <table class="field_table metaContent media-content" id="metaContent" style=<?php echo $ml?>>
                <tr>
                    <td><label for="ede_upload_file_url">Media File</label></td>
                    <td>
                    <input type="text" class="widefat" id="ede_upload_file_url" name="ede_upload_file_url" value="<?php echo esc_attr( $ede_upload_file_url );?>">
                    </td>
                    <td><input type="button" class="button" name="ede_upload_file_btn" id="ede_upload_file_btn" value="Choose File"></td>
                </tr>
            </table>


            <table class="field_table metaContent gdoc-content" id="metaContent" style=<?php echo $gdl?>>
                <tr>
                    <td><label for="ede_gdocs_file_url">Google Docs Link</label></td>
                    <td><input type="text" class="widefat" id="ede_gdocs_file_url" name="ede_gdocs_file_url" value="<?php echo esc_attr( $ede_gdocs_file_url );?>"></td>
                    <td></td>
                </tr>
            </table>


        </div>
        <?php
        
    }

    public function edeMetaboxSettings($post)
    {
        wp_nonce_field( 'ede_embedder_nonce_id', 'ede_embedder_nonce' );
        $ede_width = get_post_meta( $post->ID, 'ede_width', true );
        $ede_height = get_post_meta( $post->ID, 'ede_height', true );
        $ede_download_enable = get_post_meta( $post->ID, 'ede_download_enable', true );
        $ede_download_btn_class = get_post_meta( $post->ID, 'ede_download_btn_class', true );
        ?>
            <div class="ede_metabox_div">
                <table>
                    <tr>
                        <td><label for="ede_width"><?php esc_html_e( 'Width: ', 'easy-document-embedder' )?></label></td>
                        <td><input type="text" id="ede_width" name="ede_width" placeholder="default 100%" value="<?php echo esc_attr( $ede_width )?>"></td>
                    </tr>
                    <tr>
                        <td><label for="ede_height"><?php esc_html_e( 'Height: ', 'easy-document-embedder' )?></label></td>
                        <td><input type="text" id="ede_height" name="ede_height" placeholder="default 600px" value="<?php echo esc_attr( $ede_height )?>"></td>
                    </tr>
                    <tr>
                        <td><label for="ede_download_enable"><?php esc_html_e( 'Enable Download Button: ', 'easy-document-embedder' )?></label></td>
                        <td>
                        <input type="checkbox" id="ede_download_enable" name="ede_download_enable" value="1" <?php checked( $ede_download_enable, '1' ); ?>></td>
                    </tr>
                    
                    <tr>
                        <td><label for="ede_download_btn_class"><?php esc_html_e( 'Download Button Classname: ', 'easy-document-embedder' )?></label></td>
                        <td>
                        <input type="text" id="ede_download_btn_class" class="widefat" name="ede_download_btn_class" value="<?php echo esc_attr( $ede_download_btn_class )?>" ></td>
                    </tr>

                </table>
            </div>
        <?php 
    }

    // save meta box method
    public function saveMetaboxField($post_id)
    {
        /**
         * check that nonce is set
         */
        if ( !isset( $_POST['ede_embedder_nonce'] ) ) {
            return;
        }

        if (!wp_verify_nonce( $_POST['ede_embedder_nonce'], 'ede_embedder_nonce_id' )) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        /** 
         * Check the user's permissions.
         */
        if ( isset( $_POST['post_type'] ) && 'ede_embedder' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }  
        if (isset($_POST['ede_source_type'])) {
            $ede_source_type = sanitize_text_field( $_POST['ede_source_type'] );
            update_post_meta( $post_id, 'ede_source_type', $ede_source_type );
        } else {
            delete_post_meta( $post_id, 'ede_source_type' );
        }

        if (isset($_POST['ede_select_type'])) {
            $ede_select_type = sanitize_text_field( $_POST['ede_select_type'] );
            update_post_meta( $post_id, 'ede_select_type', $ede_select_type );
        } else {
            delete_post_meta( $post_id, 'ede_select_type' );
        }

        if (isset($_POST['ede_upload_file_url']) && isset($_POST['ede_source_type']) && $_POST['ede_source_type'] === "ML") {
            $ede_upload_file_url = sanitize_text_field( $_POST['ede_upload_file_url'] );
            update_post_meta( $post_id, 'ede_upload_file_url', $ede_upload_file_url );
        } else {
            delete_post_meta( $post_id, 'ede_upload_file_url' );
        }

        if (isset($_POST['ede_downlaod_file_url']) && $_POST['ede_downlaod_file_url'] !== "" && isset($_POST['ede_source_type']) && $_POST['ede_source_type'] === "ML") {
            $ede_downlaod_file_url = sanitize_text_field( $_POST['ede_downlaod_file_url'] );
            update_post_meta( $post_id, 'ede_downlaod_file_url', $ede_downlaod_file_url );
        } else {
            delete_post_meta( $post_id, 'ede_downlaod_file_url' );
        }

        if (isset($_POST['ede_downlaod_file_url_el']) && $_POST['ede_downlaod_file_url_el'] !== "" && isset($_POST['ede_source_type']) && $_POST['ede_source_type'] === "EL") {
            $ede_downlaod_file_url_el = sanitize_text_field( $_POST['ede_downlaod_file_url_el'] );
            update_post_meta( $post_id, 'ede_downlaod_file_url_el', $ede_downlaod_file_url_el );
        } else {
            delete_post_meta( $post_id, 'ede_downlaod_file_url_el' );
        }

        if (isset($_POST['ede_external_file_url']) && $_POST['ede_external_file_url'] !== "" && isset($_POST['ede_source_type']) && $_POST['ede_source_type'] === "EL") {
            $ede_external_file_url = sanitize_text_field( $_POST['ede_external_file_url'] );
            update_post_meta( $post_id, 'ede_external_file_url', $ede_external_file_url );
        } else {
            delete_post_meta( $post_id, 'ede_external_file_url' );
        }
        
        if (isset($_POST['ede_gdocs_file_url']) && $_POST['ede_gdocs_file_url'] !== "" && isset($_POST['ede_source_type']) && $_POST['ede_source_type'] === "GDL") {
            $ede_gdocs_file_url = sanitize_text_field( $_POST['ede_gdocs_file_url'] );
            update_post_meta( $post_id, 'ede_gdocs_file_url', $ede_gdocs_file_url );
        } else {
            delete_post_meta( $post_id, 'ede_gdocs_file_url' );
        }

        if (isset($_POST['ede_width']) && $_POST['ede_width'] !== "") {
            $ede_width = sanitize_text_field( $_POST['ede_width'] );
            update_post_meta( $post_id, 'ede_width', $ede_width );
        } else {
            delete_post_meta( $post_id, 'ede_width' );
        }

        if (isset($_POST['ede_height']) && $_POST['ede_height'] !== "") {
            $ede_height = sanitize_text_field( $_POST['ede_height'] );
            update_post_meta( $post_id, 'ede_height', $ede_height );
        } else {
            delete_post_meta( $post_id, 'ede_height' );
        }

        if (isset($_POST['ede_download_enable']) && $_POST['ede_download_enable'] !== "") {
            $ede_download_enable = sanitize_text_field( $_POST['ede_download_enable'] );
            update_post_meta( $post_id, 'ede_download_enable', $ede_download_enable );
        }else {
            delete_post_meta($post_id, 'ede_download_enable');
        }
        if (isset($_POST['ede_download_btn_class']) && $_POST['ede_download_btn_class'] !== "") {
            $ede_download_btn_class = sanitize_text_field( $_POST['ede_download_btn_class'] );
            update_post_meta( $post_id, 'ede_download_btn_class', $ede_download_btn_class );
        }else {
            delete_post_meta($post_id, 'ede_download_btn_class');
        }

        
    }
}