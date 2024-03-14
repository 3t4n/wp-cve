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
				echo __( 'Cofidis Pay is a fractional payment solution, which allows customers to pay up to 12 installments in amounts tailored to them with total control, flexibility and security. The maximum amount of each payment is € 1.000,00.', 'lusopaygateway' );
				?><br>
				<b><?php echo esc_attr( $this->notify_url ); ?></b>
			</li>
		</ul>
		<h3><?php echo 'How it works'; ?></h3>
		<p>
			<b><?php esc_html_e( 'Simple explanation of how the Cofidis Pay works:', 'lusopaygateway' ); ?></b>
		</p>
		<ul class="lusopaygateway_list">
			<li>
				<?php
				echo __( "<b>1</b> - This payment method is available only for Portuguese merchants.
				", 'lusopaygateway' );
				?>
			</li>
			<li>
				<?php
				echo __( "<b>2</b> - You have to create a cofidis pay account and wait to be accepted. While creating the Cofidis Pay account, you have to inform them that you came through lusopay. If you do not do so, this payment method will not work.", 'lusopaygateway');
				?>
			</li>
			<li>
				<?php
				echo __( "<b>3</b> - Once the payment is confirmed, you have 24 hours to send the invoice through lusopay. If you do not do this, you will not receive the money from Cofidis Pay.", 'lusopaygateway');
				?>
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
