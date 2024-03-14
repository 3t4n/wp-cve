<div class="njba-admin-settings-section">
    <div class="njba-upgrade-section">
        <img src="https://www.ninjabeaveraddon.com/wp-content/plugins/ninja-beaver-pro/img/upgrade-banner.jpg">
    </div>
    <h1 class="njba-admin-settings-heading">
        <span><?php echo _x( 'Purchase More Addons', 'bb-njba' ); ?></span>
    </h1>
	<?php
	add_filter( 'njba_extensions_lists_filter_hook', 'njba_extensions_lists_filter' );
	function njba_extensions_lists_filter( $value ) {
		$get_option  = get_option( 'njba_extensions_lists' );
		$unserialize = unserialize( $get_option );
		if ( ! $unserialize == '' ) {
			$serialize = serialize( $value );
			update_option( 'njba_extensions_lists', $serialize );
		} else {
			$serialize = serialize( $value );
			update_option( 'njba_extensions_lists', $serialize );
		}

		return;
	}

	$pro_module = njbaProModulesList();

	$get_option  = get_option( 'njba_extensions_lists' );
	$unserialize = unserialize( $get_option );
	if ( ! $unserialize == '' ) {
		$diff_key = array_diff_key( $pro_module, $unserialize );
		if ( ! empty( $diff_key ) ) {
			apply_filters( 'njba_extensions_lists_filter_hook', $diff_key );
		}
	} else {
		apply_filters( 'njba_extensions_lists_filter_hook', $pro_module );
	}
	?>
	<?php
	if ( ! defined( 'WPINC' ) ) { // If this file is called directly, abort.
		die;
	}
	global $wpdb;
	?>
    <div class="njba-addons-list-sections">
		<?php
		$get_njba_extensions_lists = get_option( 'njba_extensions_lists' );
		$module_list               = unserialize( $get_njba_extensions_lists );
		$pro_module_name = njbaProModulesList();
		//$arr = array_intersect($pro_module_name,$module_list);
		/*if($module_list != ''){
		  $arr = array_intersect($pro_module_name,$module_list);
		}else{
		  $arr = $pro_module_name;
		}*/

		foreach ( $pro_module_name as $value ) {
			if ( $module_list != '' ) {
				foreach ( $module_list as $modules ) {
					if ( $value['module_slug'] == $modules['module_slug'] ) {

						$module_slug        = $modules['module_slug'];
						$module_slug_chnage = str_replace( '-', '_', $module_slug );
						$versions           = $module_slug_chnage . '_versions';
						$license_value      = get_option( $versions );
						if ( $license_value ) {
							?>
                            <div class="njba-columan-box">
                                <div class="njba-columan-box-sub">
                                    <div class="addons-purchase-list">
                                        <div class="njba-info">
                                            <div class="njba-info-image">
                                                <img class="njba-image-responsive"
                                                     src="<?php echo NJBA_MODULE_URL; ?>classes/admin/purchase/icons/<?php echo $module_slug; ?>-icon.png" alt="Icon">
                                                <h3><?php echo $modules['module_name']; ?></h3>
                                            </div>
                                            <div class="njba-info-dis">
                                                <p><?php echo $value['description']; ?></p>
                                                <h4 class="njba-already-purchase-btn">Install</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<?php
						} else {
							?>
                            <div class="njba-columan-box">
                                <div class="njba-columan-box-sub">
                                    <div class="addons-purchase-list">
                                        <div class="njba-info">
                                            <div class="njba-info-image">
                                                <img class="njba-image-responsive"
                                                     src="<?php echo NJBA_MODULE_URL; ?>classes/admin/purchase/icons/<?php echo $module_slug; ?>-icon.png" alt="Icon">
                                                <h3><?php echo $modules['module_name']; ?></h3>
                                            </div>
                                            <div class="njba-info-dis">
                                                <p><?php echo $value['description']; ?></p>
                                                <a href="https://www.ninjabeaveraddon.com/downloads/" class="njba-purchase-btn" target="_blank">Purchase</a>
                                            </div>
                                        </div>
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
