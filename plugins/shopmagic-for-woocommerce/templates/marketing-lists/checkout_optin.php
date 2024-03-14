<?php
/**
 * Override this template by copying it to yourtheme/shopmagic/checkout_optin.php
 *
 * @var \WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceList $type
 * @var bool                                                              $opted_in True when is on the list false if not.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( ! $opted_in ) : ?>
	<p class="shopmagic-optin form-row">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">

			<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
				   name="shopmagic_optin[<?php echo esc_attr( $type->get_id() ); ?>]"
				   id="shopmagic_optin_<?php echo esc_attr( $type->get_id() ); ?>"
				   value="yes"/>

			<span class="shopmagic-optin__checkbox-text"><?php echo esc_html( $type->get_checkout_label() ); ?></span>
		</label>
		<?php echo wp_kses_post( $type->get_checkout_description() ); ?>
	</p>
<?php endif; ?>
