
		<?php
		if ( is_plugin_active( 'checkout-mestres-wp/checkout-woocommerce-mestres-wp.php' ) ) { ?>

			<div class="mwp-box">
				<div class="col-1">
				<h3><?php echo __( 'License', 'checkout-mestres-wp'); ?></h3>
				
				<?php if(get_option('cwmp_license_cwmwp_active')==true){ ?>
				<p><?php echo __( 'Your license is active for email:', 'checkout-mestres-wp'); ?> <strong><?php echo esc_html(get_option('cwmp_license_cwmwp_email')); ?></strong></p>
				<a name="Submit" class="action" id="cwmp_license_cwmwp_button_remove" style="cursor:pointer;" /><?php echo __( 'Disconnect License', 'checkout-mestres-wp'); ?></a>
				<?php }else{ ?>
				<p><?php echo __( 'Fill in the email field using the same one provided at the time of purchase and click connect license.', 'checkout-mestres-wp'); ?></p>
				<a name="Submit" class="action" id="cwmp_license_cwmwp_button" style="cursor:pointer;" /><?php echo __( 'Connect License', 'checkout-mestres-wp'); ?></a>
				<?php } ?>
				<a href="https://docs.mestresdowp.com.br"><?php echo __( 'Help? See the documentation', 'checkout-mestres-wp'); ?></a>
				</div>
				<div class="col-2">
				<?php if(get_option('cwmp_license_cwmwp_active')==true){ ?>
				<?php }else{ ?>
				<strong><?php echo __( 'Plan', 'checkout-mestres-wp'); ?></strong>
				<select name="cwmp_license_cmwp_tipo" class="input-150" value="<?php echo esc_html(get_option('cwmp_license_cwmwp_tipo')); ?>">
					<option value='1' <?php if(get_option('cwmp_license_cwmwp_tipo')=="1"){ echo "selected"; } ?>><?php echo __( 'lifetime', 'checkout-mestres-wp'); ?></option>
					<option value='2' <?php if(get_option('cwmp_license_cwmwp_tipo')=="2"){ echo "selected"; } ?>><?php echo __( 'assinatura', 'checkout-mestres-wp'); ?></option>
				</select>
				</p>
				<p class="col col-1-2">
					<strong><?php echo __( 'E-mail', 'checkout-mestres-wp'); ?></strong>
					<input type="text" name="cwmp_license_cmwp_email" class="input-150" placeholder="E-mail" value="<?php echo esc_html(get_option('cwmp_license_cwmwp_email')); ?>" />
					<input type="hidden" name="cwmp_license_cmwp_product" placeholder="E-mail" value="5676" />
					<input type="hidden" name="cwmp_license_cmwp_url" placeholder="E-mail" value="<?php echo bloginfo('url'); ?>" />
				</p>
				<?php } ?>
				</div>
			</div>
		<?php 			include "config.copyright.php";
		}
 ?>
