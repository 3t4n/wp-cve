<?php

// Admin settings for "Specify Missing Image Dimensions" Plugin

add_action( 'admin_menu', 'SMID_plugin_settings_page' );

function SMID_plugin_settings_page() {

    $page_title = 'Specify Missing Image Dimensions Settings Page';
    $menu_title = 'SMID';
    $capability = 'manage_options';
    $slug = 'SMID-plugin';
    $callback = 'SMID_plugin_settings_page_content';

    add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
}

function SMID_plugin_settings_page_content() {
    
    if( isset( $_POST['updated']) && $_POST['updated'] === 'true' ) {
            SMID_handle_options_form();
    } ?>
    
    <div class="wrap">
		<h2><?php _e( "Specify Missing Image Dimensions Settings Page", "specify-missing-image-dimensions" );?></h2>
		<form method="POST">
            <input type="hidden" name="updated" value="true" />
            <?php wp_nonce_field( 'SMID_form_update', 'SMID_options_form' ); ?>
            <table class="form-table">
            	<tbody>
                	
                	<tr>
                		<th><label for="data_Src_Value"><?php _e( "Enter Lazy load attribute (if applicable)", "specify-missing-image-dimensions" ); ?></label></th>
                		<td>
                		    <input placeholder="data-src" name="SMID_data_Src_Value" id="data_Src_Value" type="text" value="<?php echo get_option('SMID_data_Src_Value'); ?>" class="regular-text" />
                		    <div><?php _e( "In case of using image lazy load plugin, enter lazy load attribute added by lazy load plugin. Default is 'data-src'", "specify-missing-image-dimensions" ); ?></div>
                		</td>
                	</tr>
                	
                	
                	<tr>
                		<th><label><h2><?php _e( "Exclusion options", "specify-missing-image-dimensions" ); ?></h2></label></th>
                	</tr>
                	
                    <tr>
                		<th><label for="excluded_Image_Classes"><?php _e( "Exclude images by classes", "specify-missing-image-dimensions" ); ?></label></th>
                		<td>
                		    <input placeholder=".first-img, .second-img" name="SMID_excluded_Image_Classes" id="excluded_Image_Classes" type="text" value="<?php echo get_option('SMID_excluded_Image_Classes'); ?>" class="regular-text" />
                		    <div><?php _e( "Find and enter image class name. If more than 1 seprate each class name by a comma (,)", "specify-missing-image-dimensions" ); ?></div>
                		</td>
                	</tr>
                	<tr>
                		<th><label for="excluded_Image_ID"><?php _e( "Exclude images by ID's", "specify-missing-image-dimensions" ); ?></label></th>
                		<td>
                		    <input placeholder="#first-img, #second-img" name="SMID_excluded_Image_ID" id="excluded_Image_ID" type="text" value="<?php echo get_option('SMID_excluded_Image_ID'); ?>" class="regular-text" />
                		    <div><?php _e( "Find and enter image ID. If more than 1 seprate each ID by a comma (,)", "specify-missing-image-dimensions" ); ?></div>
                		</td>
                	</tr>
                	<tr>
                		<th><label for="excluded_Image_Name"><?php _e( "Exclude images by name", "specify-missing-image-dimensions" ); ?></label></th>
                		<td>
                		    <input placeholder="image-1, image-2" name="SMID_excluded_Image_Name" id="excluded_Image_Name" type="text" value="<?php echo get_option('SMID_excluded_Image_Name'); ?>" class="regular-text" />
                		    <div><?php _e( "Enter image name. If more than 1 seprate each name by a comma (,)", "specify-missing-image-dimensions" ); ?></div>
                		</td>
                	</tr>
                	<tr>
                		<th><label for="excluded_Image_Extension"><?php _e( "Exclude images by extension name", "specify-missing-image-dimensions" ); ?></label></th>
                		<td>
                		    <input placeholder=".svg, .jpg" name="SMID_excluded_Image_Extension" id="excluded_Image_Extension" type="text" value="<?php echo get_option('SMID_excluded_Image_Extension'); ?>" class="regular-text" />
                		    <div><?php _e( "Enter image extension. If more than 1 seprate each extension name by a comma (,)", "specify-missing-image-dimensions" ); ?></div>
                		</td>
                	</tr>
            	</tbody>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Update Settings">
            </p>
		</form>
	</div> <?php
}

function SMID_handle_options_form() {
    if( ! isset( $_POST['SMID_options_form'] ) || ! wp_verify_nonce( $_POST['SMID_options_form'], 'SMID_form_update' ) ){ ?>
       <div class="error">
           <p><?php _e( "Security Issue!", "specify-missing-image-dimensions" ); ?></p>
       </div> <?php
       exit;
    } else {
        
        if( isset($_POST['SMID_excluded_Image_Classes']) && !empty($_POST['SMID_excluded_Image_Classes']) ) {
            
            $excluded_Image_Classes = sanitize_text_field( $_POST['SMID_excluded_Image_Classes'] ); 
            
            update_option( 'SMID_excluded_Image_Classes', $excluded_Image_Classes );
            
        }
        else {
            update_option( 'SMID_excluded_Image_Classes', "" );
        }

        if( isset($_POST['SMID_excluded_Image_Name']) && !empty($_POST['SMID_excluded_Image_Name']) ) {
            
            $excluded_Image_Name = sanitize_text_field( $_POST['SMID_excluded_Image_Name'] ); 
            
            update_option( 'SMID_excluded_Image_Name', $excluded_Image_Name );
            
        }
        else {
            update_option( 'SMID_excluded_Image_Name', "" );
        }

        if( isset($_POST['SMID_excluded_Image_ID']) && !empty($_POST['SMID_excluded_Image_ID']) ) {
            
            $excluded_Image_ID = sanitize_text_field( $_POST['SMID_excluded_Image_ID'] ); 
            
            update_option( 'SMID_excluded_Image_ID', $excluded_Image_ID );
            
        }
        else {
            update_option( 'SMID_excluded_Image_ID', "" );
        }

        if( isset($_POST['SMID_excluded_Image_Extension']) && !empty($_POST['SMID_excluded_Image_Extension']) ) {
            
            $excluded_Image_Extension = sanitize_text_field( $_POST['SMID_excluded_Image_Extension'] ); 
            
            update_option( 'SMID_excluded_Image_Extension', $excluded_Image_Extension );
            
        }
        else {
            update_option( 'SMID_excluded_Image_Extension', "" );
        }

        if( isset($_POST['SMID_data_Src_Value']) && !empty($_POST['SMID_data_Src_Value']) ) {
            
            $data_Src_Value = sanitize_text_field( $_POST['SMID_data_Src_Value'] ); 
            
            update_option( 'SMID_data_Src_Value', $data_Src_Value );
            
        }
        else {
            update_option( 'SMID_data_Src_Value', "" );
        }
        
?>
        <div class="updated">
            <p><?php _e( "New settings saved successfully", "specify-missing-image-dimensions" ); ?></p>
        </div>
<?php    }

}