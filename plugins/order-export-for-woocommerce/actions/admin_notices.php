<?php 

function pmwoe_admin_notices() {
	// notify user if history folder is not writable		
	if ( ! class_exists( 'PMXE_Plugin' ) ) {
		?>
		<div class="error"><p>
			<?php printf(
					__('<b>%s Plugin</b>: WP All Export must be installed and activated.</a>', 'PMWOE_Plugin'),
					esc_html(PMWOE_Plugin::getInstance()->getName())
			) ?>
		</p></div>
		<?php
		
		deactivate_plugins( PMWOE_ROOT_DIR . '/wpae-woocommerce-order-add-on.php');
		return;
		
	}

    // If the pro wooco add-on is active
    if ( class_exists('PMWE_Plugin') ) {
        ?>
        <div class="error"><p>
                <?php echo 'The WooCommerce Export Add-On Pro is already activated. The WooCommerce Order Export Add-On can not be used at the same time and has been deactivated'; ?>
            </p></div>
        <?php

        deactivate_plugins( PMWOE_ROOT_DIR . '/wpae-woocommerce-order-add-on.php');
        return;

    }

    // If an unsupported WPAE version is activated
	if( class_exists('PMXE_Plugin')){
		if( (PMXE_EDITION == "free" && version_compare(PMXE_VERSION, '1.3.1', '<=')) || (PMXE_EDITION == "paid" && version_compare(PMXE_VERSION, '1.7.1', '<=')) ){

			?>
            <div class="error"><p>
					<?php printf(__('Update to the latest version of WP All Export to use the WooCommerce Order Export Add-On', 'wp_all_export_order_add_on'));
					?>
                </p></div>
			<?php

			deactivate_plugins( PMWOE_ROOT_DIR . '/wpae-woocommerce-order-add-on.php');
		}
	}

	$input = new PMWOE_Input();
	$messages = $input->get('pmwoe_nt', array());
	if ($messages) {
		is_array($messages) or $messages = array($messages);
		foreach ($messages as $type => $m) {
			in_array((string)$type, array('updated', 'error')) or $type = 'updated';
			?>
			<div class="<?php echo esc_attr($type); ?>"><p><?php echo wp_kses_post($m); ?></p></div>
			<?php 
		}
	}

	if ( ! empty($_GET['type']) and $_GET['type'] == 'user'){
		?>
		<script type="text/javascript">
			(function($){$(function () {
				$('#toplevel_page_pmxi-admin-home').find('.wp-submenu').find('li').removeClass('current');
				$('#toplevel_page_pmxi-admin-home').find('.wp-submenu').find('a').removeClass('current');
				$('#toplevel_page_pmxi-admin-home').find('.wp-submenu').find('li').eq(2).addClass('current').find('a').addClass('current');
			});})(jQuery);
		</script>
		<?php
	}
}
