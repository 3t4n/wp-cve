<?php
/**
 * Override this template by copying it to yourtheme/shopmagic/communication_preferences.php
 *
 * @var \WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriber[] $signed_ups
 * @var string                                                             $action
 * @var string                                                             $email
 * @var string                                                             $email_display
 * @var ?bool                                                              $success
 */

use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\CommunicationListPersistence;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
if ( $success === true ) {
	?>
	<div class="woocommerce-message shopmagic-message" role="alert">
		<p class="success" style="margin: 0">
			<?php esc_html_e(
				'You have successfully updated your preferences.',
				'shopmagic-for-woocommerce'
			); ?>
		</p>
	</div>
	<?php
} elseif ( $success === false ) {
	?>
	<div class="woocommerce-message shopmagic-message" role="alert">
		<p class="error" style="margin: 0">
			<?php esc_html_e(
				'An error occurred during saving your preferences.',
				'shopmagic-for-woocommerce'
			); ?>
		</p>
	</div>
	<?php
}
?>

<h3><?php esc_html_e( 'You can choose to opt out of any of the following types of email communications we send.', 'shopmagic-for-woocommerce' ); ?></h3>

<p><?php esc_html_e( 'You are managing preferences for ', 'shopmagic-for-woocommerce' ); ?><?php echo esc_html( $email_display ); ?>
	.</p>

<form enctype="application/x-www-form-urlencoded" method="post"
	  action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>"/>
	<input type="hidden" name="email" value="<?php echo esc_attr( $email ); ?>"/>

	<p class="shopmagic-optin form-row">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">

			<input type="checkbox" class="shopmagic-communication-form__preference-checkbox"
				   checked="checked" disabled="disabled">

			<span
				class="shopmagic-optin__checkbox-text"><?php esc_html_e( 'Account and order information', 'shopmagic-for-woocommerce' ); ?></span>
		</label>

		<span><?php esc_html_e( 'Receive important information about your orders and account.', 'shopmagic-for-woocommerce' ); ?></span>
	</p>

	<?php foreach ( $signed_ups as $list_status ) { ?>
		<?php $persistence = new CommunicationListPersistence( $list_status->get_list_id() ); ?>
		<p class="shopmagic-optin form-row">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input type="checkbox"
					   class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
					   name="shopmagic_optin[<?php echo esc_attr( $list_status->get_list_id() ); ?>]"
					   id="shopmagic_optin_<?php echo esc_attr( $list_status->get_list_id() ); ?>"
					   value="yes"
					<?php checked( $list_status->is_active() ); ?>/>
				<span class="shopmagic-optin__checkbox-text">
					<?php
					if ( $persistence->has( CommunicationListPersistence::FIELD_CHECKBOX_LABEL_KEY ) && ! empty( $persistence->get( CommunicationListPersistence::FIELD_CHECKBOX_LABEL_KEY ) ) ) {
						echo esc_html( $persistence->get( CommunicationListPersistence::FIELD_CHECKBOX_LABEL_KEY ) );
					} else {
						printf( esc_html__( 'Communication list #%d', 'shopmagic-for-woocommerce' ), $list_status->get_id() );
					}
				   	?>
				</span>
			</label>
			<?php if ( $persistence->has( CommunicationListPersistence::FIELD_CHECKBOX_DESCRIPTION_KEY ) ) { ?>
			<span><?php echo esc_html( $persistence->get( CommunicationListPersistence::FIELD_CHECKBOX_DESCRIPTION_KEY ) ); ?></span>
			<?php } ?>
		</p>

	<?php } ?>

	<footer>
		<input type="submit"
			   value="<?php esc_html_e( 'Save preferences', 'shopmagic-for-woocommerce' ); ?>"/>
	</footer>
</form>
