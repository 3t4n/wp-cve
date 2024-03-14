<?php
/**
 * Licence Manager Comment
 *
 * @category  Views
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<h2 class="gdpr-tt gdpr-tt-licence">
	<?php esc_html_e( 'Licence Manager', 'gdpr-cookie-compliance' ); ?>
	<?php do_action( 'gdpr_cc_licence_manager_action_button', false ); ?>		
</h2>
<hr />
<?php

$is_bulk_view = false;
if ( function_exists('is_multisite') && is_multisite() ) :
	$is_bulk_view 	= isset( $_GET['view'] );
endif;

$gdpr_default_content = new Moove_GDPR_Content();
$option_name          = $gdpr_default_content->moove_gdpr_get_option_name();
$gdpr_options         = get_option( $option_name );
$gdpr_options         = is_array( $gdpr_options ) ? $gdpr_options : array();
$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );

if ( $is_bulk_view ) : ?>
	<div class="gdpr-msba-wrap">
    <?php 
      wp_nonce_field( 'gdpr_tab_licence_bulk', 'gdpr_lt_nonce_bulk' );
      if ( function_exists('is_multisite') && is_multisite() ) :
        if ( ! class_exists('Moove_GDPR_Content') ) :
          ?>
            <div class="notice notice-error is-dismissible">
              <p><?php _e( 'Please activate the GDPR Cookie Compliance base plugin first.', 'gdpr-cc-ms-activator' ); ?></p>
            </div>
          <?php
        else :
           $args  = array(
            'fields'        => 'ids',
            'number'        => 2000
            );
            $sites = get_sites( $args );
            if ( $sites && ! empty( $sites ) && count( $sites ) >= 1 ) :

              $table_cnt            = '';
              $licence_key          = '';
              $gdpr_default_content = new Moove_GDPR_Content();
              $option_key           = $gdpr_default_content->moove_gdpr_get_key_name();                        
              ob_start();
              if ( $sites && ! empty( $sites ) && count( $sites ) >= 1 ) :
                foreach ( $sites as $blogid ) :
                  $blog_details = get_blog_details( $blogid );
                  switch_to_blog( $blogid );
                  $gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );
                  ?>                 
                    <tr class="<?php echo isset( $gdpr_key['activation'] ) ? '' : 'msba-na' ?>">
                      <td data-bid="<?php echo $blogid ?>">                                
                        <input name="gdpr_msb_licence_activator_tool[]" type="checkbox" <?php echo isset( $gdpr_key['activation'] ) ? '' : 'checked="checked"' ?> value="<?php echo esc_attr( $blogid ); ?>" id="gdpr_msb_licence_activator_tool_<?php echo esc_attr( $blogid ); ?>" class="on-off"> 
                      </td>
                      <td>
                        <label for="gdpr_msb_licence_activator_tool_<?php echo esc_attr( $blogid ); ?>">
                        	<?php 
                        	$name = esc_attr( $blog_details->blogname );
                        	$name = $name ? $name : get_home_url( $blogid );
                        	echo $name; 
                        	?>
                        
                        </label>
                      </td>
                      <td class="msba-licence_k">
                        <?php 
                        echo isset( $gdpr_key['key'] ) ? apply_filters( 'gdpr_licence_key_visibility', $gdpr_key['key'] ) : 'N/A'; 
                        $licence_key = $licence_key ? $licence_key : ( isset( $gdpr_key['key'] ) ? $gdpr_key['key'] : '' );
                        ?>
                      </td>
                      <td class="msba-status">
                        <?php echo isset( $gdpr_key['activation'] ) ? '<span class="gdpr-admin-lbl gdpr-active">Active</span>' : '<span class="gdpr-admin-lbl gdpr-inactive">Not Active</span>'; ?>
                      </td>
                    </tr>
                  <?php
                  restore_current_blog();
                endforeach; 
              endif; 
              $table_cnt = ob_get_clean();
              ?>
                <div class="licence-key-wrap-m">
                  <form action="gdpr_msba_bulk_activate" class="licence-key-wrap" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="moove_gdpr_tab_cd_settings">
                    <label for="" style="margin: 0 15px 0 0; line-height: 30px;">Licence Key</label>
                    <div class="input-wrap">
                      <input type="text" style="margin: 0;" class="form-control" name="gdpr_msba_licence_key" value="<?php echo $licence_key; ?>" required>
                    </div>
                    <!-- .input-wrap -->
                    <button type="submit" class="button button-primary gdpr-msba-bulk-activate" style="margin-left: 15px;">Bulk Activate</button>
                  </form>
                  <div class="msba-progress-bar" style="display: none;">
                    <div class="msba-progress" style="height:12px;width:0%"></div>
                  </div>
                </div>
                <!-- .licence-key-wrap -->
                <table class="wp-list-table widefat fixed striped table-view-list posts">
                  <thead>
                    <tr>
                      <td class="manage-column column-cb check-column" style="width: 40px;">
                        <input name="gdpr_msb_licence_activator_tool_all" type="checkbox"> 
                      </td>
                      <th>
                        Install Name
                      </th>
                      <th>
                        Licence Key
                      </th>
                      <th>
                        Status
                      </th>
                    </tr>
                    
                  </thead>
                  <tbody>
                    <?php echo $table_cnt; ?>
                  </tbody>
                </table>
              <?php
            endif;
          endif;
       
      else : 
        ?>
          <div class="notice notice-error is-dismissible">
            <p><?php _e( 'This tool supports only WordPress Multisite installs. !', 'gdpr-cc-ms-activator' ); ?></p>
          </div>
        <?php
      endif; 
    ?>
  </div>
  <!-- .wrap -->
	<?php
else : ?>	
	<form action="<?php echo esc_url( admin_url( 'admin.php?page=moove-gdpr_licence&tab=licence' ) ); ?>" method="post" id="moove_gdpr_license_settings">
		<table class="form-table">
			<tbody>
				<tr>
					<td colspan="2" class="gdpr_license_log_alert" style="padding: 0;">
						<?php
						$is_valid_license = false;
						$nonce = isset( $_POST['gdpr_lt_nonce_k'] ) ? sanitize_key( wp_unslash( $_POST['gdpr_lt_nonce_k'] ) ) : false;
							;
						if ( isset( $_POST['moove_gdpr_license_key'] ) && isset( $_POST['gdpr_activate_license'] ) && wp_verify_nonce( $nonce, 'gdpr_tab_licence_v' ) ) :
							$license_key = sanitize_text_field( wp_unslash( $_POST['moove_gdpr_license_key'] ) );
							if ( $license_key ) :
								$license_manager  = new Moove_GDPR_License_Manager();
								$is_valid_license = $license_manager->get_premium_add_on( $license_key, 'activate' );
								if ( $is_valid_license && isset( $is_valid_license['valid'] ) && true === $is_valid_license['valid'] ) :
									update_option(
										$option_key,
										array(
											'key'        => $is_valid_license['key'],
											'activation' => $is_valid_license['data']['today'],
										)
									);
									// VALID.
									$gdpr_key = $gdpr_default_content->gdpr_get_activation_key( $option_key );
									$messages = isset( $is_valid_license['message'] ) && is_array( $is_valid_license['message'] ) ? implode( '<br>', $is_valid_license['message'] ) : '';
									do_action( 'gdpr_get_alertbox', 'success', $is_valid_license, $license_key );
								else :
									// INVALID.
									do_action( 'gdpr_get_alertbox', 'error', $is_valid_license, $license_key );
								endif;
							endif;
						elseif ( isset( $_POST['gdpr_deactivate_license'] ) && wp_verify_nonce( $nonce, 'gdpr_tab_licence_v' ) ) :
							$gdpr_default_content = new Moove_GDPR_Content();
							$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
							$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );
							
							if ( $gdpr_key && isset( $gdpr_key['key'] ) && isset( $gdpr_key['activation'] ) ) :
								$license_manager  = new Moove_GDPR_License_Manager();
								$is_valid_license = $license_manager->premium_deactivate( $gdpr_key['key'] );

								update_option(
									$option_key,
									array(
										'key'          => $gdpr_key['key'],
										'deactivation' => strtotime( 'now' ),
									)
								);

								$gdpr_key = $gdpr_default_content->gdpr_get_activation_key( $option_key );

								if ( $is_valid_license && isset( $is_valid_license['valid'] ) && true === $is_valid_license['valid'] ) :
									// VALID.
									do_action( 'gdpr_get_alertbox', 'success', $is_valid_license, $gdpr_key );
								else :
									// INVALID.
									do_action( 'gdpr_get_alertbox', 'error', $is_valid_license, $gdpr_key );
								endif;
							endif;
						elseif ( $gdpr_key && isset( $gdpr_key['key'] ) && isset( $gdpr_key['activation'] ) ) :
							$license_manager  = new Moove_GDPR_License_Manager();
							$is_valid_license = $license_manager->get_premium_add_on( $gdpr_key['key'], 'check' );
							$gdpr_key         = $gdpr_default_content->gdpr_get_activation_key( $option_key );
							if ( $is_valid_license && isset( $is_valid_license['valid'] ) && true === $is_valid_license['valid'] ) :
								// VALID.
								do_action( 'gdpr_get_alertbox', 'success', $is_valid_license, $gdpr_key );
							else :
								// INVALID.
								do_action( 'gdpr_get_alertbox', 'error', $is_valid_license, $gdpr_key );
							endif;
						endif;
						?>
					</td>
				</tr>
				<?php do_action( 'gdpr_licence_input_field', $is_valid_license, $gdpr_key ); ?>
			</tbody>
		</table>		
		<?php do_action( 'gdpr_licence_action_button', $is_valid_license, $gdpr_key ); ?>
		<?php do_action( 'gdpr_cc_general_buttons_settings' ); ?>
		<?php wp_nonce_field( 'gdpr_tab_licence_v', 'gdpr_lt_nonce_k' ); ?>
	</form>

	<?php do_action( 'gdpr_cc_licence_manager_action_button', true ); ?>
<?php endif; ?>
<div class="gdpr-admin-popup gdpr-admin-popup-deactivate" style="display: none;">
	<span class="gdpr-popup-overlay"></span>
	<div class="gdpr-popup-content">
		<div class="gdpr-popup-content-header">
			<a href="#" class="gdpr-popup-close"><span class="dashicons dashicons-no-alt"></span></a>
		</div>
		<!--  .gdpr-popup-content-header -->
		<div class="gdpr-popup-content-content">
			<h4><strong><?php esc_html_e( 'Please confirm that you would like to de-activate this licence.', 'gdpr-cookie-compliance' ); ?> </strong></h4><p><strong><?php esc_html_e( 'This action will remove all of the premium features from your website.', 'gdpr-cookie-compliance' ); ?></strong></p>
			<button class="button button-primary button-deactivate-confirm">
				<?php esc_html_e( 'Deactivate Licence', 'gdpr-cookie-compliance' ); ?>
			</button>
		</div>
		<!--  .gdpr-popup-content-content -->    
	</div>
	<!--  .gdpr-popup-content -->
</div>
<!--  .gdpr-admin-popup -->
