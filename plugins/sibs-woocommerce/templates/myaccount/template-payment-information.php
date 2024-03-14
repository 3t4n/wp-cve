<?php
/**
 * Sibs My Payment Information
 *
 * The file is for displaying the Sibs My Payment Information
 * Copyright (c) SIBS
 *
 * @package     Sibs/Templates
 * @located at  /template/ckecmyaccountkout/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}
?>
<h2 class="header-title"><?php echo esc_attr( __( 'FRONTEND_MC_INFO', 'wc-sibs' ) ); ?></h2>

<?php if ( isset( WC()->session->sibs_myaccount_error ) ) : ?>
	<ul class="response-box error-message">
		<li><?php echo esc_attr( WC()->session->sibs_myaccount_error ); ?></li>
	</ul>
<?php
unset( WC()->session->sibs_myaccount_error );
endif;
?>

<?php if ( isset( WC()->session->sibs_myaccount_success ) ) : ?>
	<ul class="response-box success-message">
		<li><?php echo esc_attr( WC()->session->sibs_myaccount_success ); ?></li>
	</ul>
<?php
unset( WC()->session->sibs_myaccount_success );
endif;
?>

<?php if ( $recurring ) : ?>

	<?php if ( $is_active_cc ) : ?>
	<div class="group"><?php echo esc_attr( __( 'FRONTEND_MC_CC', 'wc-sibs' ) ); ?></div>
	<?php if ( $registered_payments['CC'] ) : ?>
		<?php foreach ( $registered_payments['CC'] as $payment ) : ?>
		<div class="group-list">
			<div class="group-img">
				<img src="<?php echo esc_attr( $plugin_url ); ?>assets/images/<?php echo esc_attr( strtolower( $payment['brand'] ) ); ?>.png" class="card_logo" alt="<?php echo esc_attr( $payment['brand'] ); ?>">
			</div>
			<div class="card_info">
				<?php
				echo esc_attr( __( 'FRONTEND_MC_ENDING', 'wc-sibs' ) ) . ' ' . esc_attr( $payment['last4digits'] ) . '; ' .
				esc_attr( __( 'FRONTEND_MC_VALIDITY', 'wc-sibs' ) ) . ' ' . esc_attr( $payment['expiry_month'] ) . '/' . esc_attr( substr( $payment['expiry_year'], -2 ) )
				?>
			</div>
			<div class="group-button">
				<?php if ( $payment['payment_default'] ) : ?>
					<button class="btnCustom btnDefault button-primary"><?php echo esc_attr( __( 'FRONTEND_MC_BT_DEFAULT', 'wc-sibs' ) ); ?></button>
				<?php else : ?>
					<form action="<?php echo esc_attr( $current_url ); ?>page=wc-default" method="post">
						<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
						<input type="hidden" name="section" value="sibs_ccsaved"/>
						<button class="btnCustom btnDefault button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_SETDEFAULT', 'wc-sibs' ) ); ?></button>
					</form>
				<?php endif; ?>
				<form action="<?php echo esc_attr( $current_url ); ?>page=wc-reregister" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
					<input type="hidden" name="section" value="sibs_ccsaved"/>
					<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_CHANGE', 'wc-sibs' ) ); ?></button>
				</form>
				<form action="<?php echo esc_attr( $current_url ); ?>page=wc-deregister" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
					<input type="hidden" name="section" value="sibs_ccsaved"/>
					<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_DELETE', 'wc-sibs' ) ); ?></button>
				</form>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="group-add">
		<form action="<?php echo esc_attr( $current_url ); ?>page=wc-register" method="post">
			<input type="hidden" name="section" value="sibs_ccsaved"/>
			<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_ADD', 'wc-sibs' ) ); ?></button>
		</form>
	</div>
	<div class="clear"></div>
	<?php endif; ?>

	<?php if ( $is_active_dd ) : ?>
	<div class="group"><?php echo esc_attr( __( 'FRONTEND_MC_DD', 'wc-sibs' ) ); ?></div>
	<?php if ( $registered_payments['DD'] ) : ?>
		<?php foreach ( $registered_payments['DD'] as $payment ) : ?>
		<div class="group-list">
			<div class="group-img">
				<img src="<?php echo esc_attr( $plugin_url ); ?>assets/images/sepa.png" class="card_logo" alt="sepa">
			</div>
			<div class="card_info">
				<?php echo esc_attr( __( 'FRONTEND_MC_ACCOUNT', 'wc-sibs' ) ) . ' ' . esc_attr( $payment['last4digits'] ); ?>
			</div>
			<div class="group-button">
				<?php if ( $payment['payment_default'] ) : ?>
					<button class="btnCustom btnDefault button-primary"><?php echo esc_attr( __( 'FRONTEND_MC_BT_DEFAULT', 'wc-sibs' ) ); ?></button>
				<?php else : ?>
					<form action="<?php echo esc_attr( $current_url ); ?>page=wc-default" method="post">
						<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
						<input type="hidden" name="section" value="sibs_ddsaved"/>
						<button class="btnCustom btnDefault button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_SETDEFAULT', 'wc-sibs' ) ); ?></button>
					</form>
				<?php endif; ?>
				<form action="<?php echo esc_attr( $current_url ); ?>page=wc-reregister" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
					<input type="hidden" name="section" value="sibs_ddsaved"/>
					<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_CHANGE', 'wc-sibs' ) ); ?></button>
				</form>
				<form action="<?php echo esc_attr( $current_url ); ?>page=wc-deregister" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
					<input type="hidden" name="section" value="sibs_ddsaved"/>
					<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_DELETE', 'wc-sibs' ) ); ?></button>
				</form>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="group-add">
		<form action="<?php echo esc_attr( $current_url ); ?>page=wc-register" method="post">
			<input type="hidden" name="section" value="sibs_ddsaved"/>
			<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_ADD', 'wc-sibs' ) ); ?></button>
		</form>
	</div>
	<div class="clear"></div>
	<?php endif; ?>

	<?php if ( $is_active_paypal ) : ?>
	<div class="group"><?php echo esc_attr( __( 'FRONTEND_MC_PAYPAL', 'wc-sibs' ) ); ?></div>
	<?php if ( $registered_payments['PAYPAL'] ) : ?>
		<?php foreach ( $registered_payments['PAYPAL'] as $payment ) : ?>
		<div class="group-list">
			<div class="group-img">
				<img src="<?php echo esc_attr( $plugin_url ); ?>assets/images/paypal.png" class="card_logo" alt="paypal">
			</div>
			<div class="card_info">
				<?php echo esc_attr( __( 'FRONTEND_MC_EMAIL', 'wc-sibs' ) ) . ' ' . esc_attr( $payment['email'] ); ?>
			</div>
			<div class="group-button">
				<?php if ( $payment['payment_default'] ) : ?>
					<button class="btnCustom btnDefault button-primary"><?php echo esc_attr( __( 'FRONTEND_MC_BT_DEFAULT', 'wc-sibs' ) ); ?></button>
				<?php else : ?>
					<form action="<?php echo esc_attr( $current_url ); ?>page=wc-default" method="post">
						<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
						<input type="hidden" name="section" value="sibs_paypalsaved"/>
						<button class="btnCustom btnDefault button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_SETDEFAULT', 'wc-sibs' ) ); ?></button>
					</form>
				<?php endif; ?>
				<form action="<?php echo esc_attr( $current_url ); ?>page=wc-reregister" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
					<input type="hidden" name="section" value="sibs_paypalsaved"/>
					<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_CHANGE', 'wc-sibs' ) ); ?></button>
				</form>
				<form action="<?php echo esc_attr( $current_url ); ?>page=wc-deregister" method="post">
					<input type="hidden" name="id" value="<?php echo esc_attr( $payment['id'] ); ?>"/>
					<input type="hidden" name="section" value="sibs_paypalsaved"/>
					<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_DELETE', 'wc-sibs' ) ); ?></button>
				</form>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="group-add">
		<form action="<?php echo esc_attr( $current_url ); ?>page=wc-register" method="post">
			<input type="hidden" name="section" value="sibs_paypalsaved"/>
			<button class="btnCustom button-primary" type="submit" value="submit"><?php echo esc_attr( __( 'FRONTEND_MC_BT_ADD', 'wc-sibs' ) ); ?></button>
		</form>
	</div>
	<div class="clear"></div>
	<?php endif; ?>

<?php endif; ?>
