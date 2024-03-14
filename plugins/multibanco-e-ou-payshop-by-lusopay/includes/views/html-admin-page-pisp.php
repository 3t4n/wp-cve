<?php global $woocommerce; ?>
<h3><?php echo esc_attr( $this->method_title ); ?>
			<span style="font-size: 75%;">v.<?php echo esc_attr( WC_Lusopay::VERSION ); ?></span></h3>
		<p>
			<b><?php esc_html_e( 'If you checked the send email box in the integration tab ignore this message. Follow the instructions to activate callback:', 'lusopaygateway' ); ?></b>
		</p>
		<ul class="lusopaygateway_list">
			<li>
				<?php
				echo __( "Sign up in our website, if you haven't.", 'lusopaygateway' ) . '<a href="https://www.lusopay.com" target="_blank">https://www.lusopay.com/</a>';
				?>
			</li>
			<li>
				<?php
				echo __( 'To activate "Callback" (automatically update the order status in your store to "processing", when the payments are made), you have to go to your LUSOPAY platform, go to the Menu Profile, click on Edit button and copy the URL callback below on "URL de callback Transferência Simplificada" field:', 'lusopaygateway' );
				?><br>
				<b><?php echo esc_attr( $this->notify_url ); ?></b>
			</li>
		</ul>
		<hr/>
		<script type="text/javascript">
			jQuery( document ).ready( function () {
				var $secret_key = jQuery( '#woocommerce_lusopaygateway_secret_key' );
				if ( $secret_key.val() === '' ) {
					$secret_key.val( '<?php echo esc_attr( $this->secret_key ); ?>' );
					jQuery( '#woocommerce_lusopaygateway_secret_key_label' ).html( '<?php echo esc_attr( $this->secret_key ); ?>' );
					jQuery( '#mainform' ).submit();
				}
			} );
		</script>
		<table class="form-table">
			<?php
			if ( trim( get_woocommerce_currency() ) === 'EUR' ) {
				$this->generate_settings_html();
			} else {
				?>
				<p>
					<b><?php esc_html_e( 'Error!', 'lusopaygateway' ); ?>
						<?php
						echo __( 'Select the currency "Euro" ', 'lusopaygateway' ) . '<a href="admin.php?page=woocommerce_settings&tab=general">' . __( 'Here', 'lusopaygateway' ) . '</a>.';
						?>
					</b>
				</p>
				<?php
			}
			?>
		</table>
		<style type="text/css">
			.lusopaygateway_list {
				list-style: disc inside;
			}

			.lusopaygateway_list li {
				margin-left: 1.5em;
			}
		</style>
