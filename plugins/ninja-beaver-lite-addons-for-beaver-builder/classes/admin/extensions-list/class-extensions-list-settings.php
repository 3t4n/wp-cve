<div class="njba-admin-settings-section">
    <div class="njba-upgrade-section">
        <img src="https://www.ninjabeaveraddon.com/wp-content/plugins/ninja-beaver-pro/img/upgrade-banner.jpg" alt="Upgrade Banner Img">
    </div>
    <h1 class="njba-admin-settings-heading">
        <span><?php echo _x( 'License For Ninja Beaver Addons', 'bb-njba' ); ?></span>
    </h1>
	<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	global $wpdb;
	?>
    <div class="njba-extensions-list-settings">
		<?php
		$get_njba_extensions_lists = get_option( 'njba_extensions_lists' );
		$module_list               = unserialize( $get_njba_extensions_lists );
		$pro_module_name           = njbaProModulesList();
		// $arr = array_intersect($pro_module_name,$module_list);
		foreach ( $pro_module_name as $value ) {
			if ( $module_list != '' ) {
				foreach ( $module_list as $modules ) {
					if ( $value['module_slug'] == $modules['module_slug'] ) {
						$module_slug        = $modules['module_slug'];
						$module_slug_chnage = str_replace( '-', '_', $module_slug );
						$versions           = $module_slug_chnage . '_versions';
						$license_value      = get_option( $versions );
						if ( $license_value ) { ?>
                            <div class="njba-columan-box">
                                <div class="njba-columan-box-sub">
                                    <div class="wrap">
										<?php
										$license_key = $modules['module_license_key'];
										$status_key  = $modules['module_license_status'];
										$license     = get_option( $license_key );
										$status      = get_option( $status_key );

										$nonce      = $module_slug_chnage . '_pro_nonce';
										$deactivate = $module_slug_chnage . '_license_deactivate';
										$activate   = $module_slug_chnage . '_license_activate';
										?>
                                        <form name="<?php echo $license_key; ?>_frm" method="post">
                                            <h3><?php echo $modules['module_name']; ?></h3>
											<?php settings_fields( 'ninja_beaver_license' ); ?>
											<?php if ( false !== $license ) {
												if ( $status !== false && $status == 'valid' ) {
													?>
                                                    <h3 class="njba-license-not-active"><span style="color: #00FF00;">Active!</span></h3>
													<?php wp_nonce_field( $nonce, $nonce ); ?>
                                                    <input type="submit" class="button-secondary" name="<?php echo $deactivate; ?>"
                                                           value="<?php _e( 'Deactivate License', 'bb-njba' ); ?>"/>
													<?php
												} else {
													?>
                                                    <h3 class="njba-license-not-active"><span style="color: #FF0000;">Not Active!</span></h3>
													<?php wp_nonce_field( $nonce, $nonce ); ?>
                                                    <input type="submit" class="button-secondary" name="<?php echo $activate; ?>"
                                                           value="<?php _e( 'Activate License', 'bb-njba' ); ?>"/>
													<?php
												}
											}
											?>
                                            <p>Enter your <a href="https://www.ninjabeaveraddon.com/downloads/" target="_blank">license key</a> to enable updates.</p>
                                            <input type="text" placeholder="Enter your license key.." class="regular-text" id="<?php echo $license_key; ?>"
                                                   name="<?php echo $license_key; ?>" value="<?php esc_attr_e( $license ); ?>">
                                        </form>
                                    </div>
                                </div>
                            </div>
							<?php
						}
					}
				}
			}
		}
		?>
    </div>
</div>
<?php 
