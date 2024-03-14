<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once(plugin_dir_path( __FILE__ ).'header/plugin-header.php');
?>
<div class="whsm-section-left mmqw-get-started-table">
    <h2>
        <?php esc_html_e( 'Getting Started', 'woo-hide-shipping-methods' ); ?>
    </h2>
	<table class="table-mastersettings table-outer form-table" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
			<td class="fr-2">
				<p class="block gettingstarted textgetting"><b><?php esc_html_e( 'Using this plugin you can hide shipping based on below options :-', 'woo-hide-shipping-methods' ); ?></b></p>
				<p class="block gettingstarted textgetting">
					<span class='desc_text'>
						<?php
						echo sprintf( wp_kses( __( '<strong>Option 1: </strong>Hide all other shipping method and when "Free Shipping" available on the cart page.'
								, 'woo-hide-shipping-methods' )
							, array( 'strong' => array() ) ) );
						?>
					</span>
					<span class="gettingstarted images_sqn">
	                    <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-1.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-2.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn"> 
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-3.png' ); ?>">
					</span>
				</span>
				</p>
				<p class="block gettingstarted textgetting">
					<span class='desc_text'>
						<?php
						echo sprintf( wp_kses( __( '<strong>Option 2: </strong>Hide all other shipping method and when "Free Shipping" or "Local Pickup" available on the cart page'
								, 'woo-hide-shipping-methods' )
							, array( 'strong' => array() ) ) );
						?>
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-2-1.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
	                    <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-2.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-2-3.png' ); ?>">
					</span>
				</p>
				<p class="block gettingstarted textgetting">
					<span class='desc_text'>
						<?php
						echo sprintf( wp_kses( __( '<strong>Option 3: </strong>Hide specific shipping method when "Free Shipping" available on the cart page'
								, 'woo-hide-shipping-methods' )
							, array( 'strong' => array() ) ) );
						?>
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-3-1.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-2.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-3-3.png' ); ?>">
					</span>
				</p>
				<p class="block gettingstarted textgetting">
					<span class='desc_text'>
						<?php
						echo sprintf( wp_kses( __( '<strong>Option 4: </strong>Conditional Hide shipping method Rules (With Compatible Shipping Plugin)'
								, 'woo-hide-shipping-methods' )
							, array( 'strong' => array() ) ) );
						?>
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-4-1.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-4-2.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-2.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-4-3.png' ); ?>">
					</span>
				</p>
				<p class="block gettingstarted textgetting">
					<span class='desc_text'>
						<?php
						echo sprintf( wp_kses( __( '<strong>Option 4: </strong>Conditional Hide shipping method Rules (With None Compatible Shipping Plugin)'
								, 'woo-hide-shipping-methods' )
							, array( 'strong' => array() ) ) );
						?>
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-4-4.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-4-5.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-1-2.png' ); ?>">
					</span>
					<span class="gettingstarted images_sqn">
		                <img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/getting-started/option-4-6.png' ); ?>">
					</span>
				</p>
				<p class="block gettingstarted textgetting">
					<span class='desc_text'>
						<?php
						echo sprintf( wp_kses( __( '<strong>Important Note: </strong>This plugin is only compatible with WooCommerce version 3.0 and more.'
								, 'woo-hide-shipping-methods' )
							, array( 'strong' => array() ) ) );
						?>
					</span>
				</p>
			</td>
		</tr>
		</tbody>
	</table>
</div>
</div>
</div>
</div>
</div>
<?php
