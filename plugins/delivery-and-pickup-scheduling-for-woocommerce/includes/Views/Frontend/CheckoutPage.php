<?php

/**
 * Handles checkout page view related logic.
 *
 * Author:          Uriahs Victor
 * Created on:      13/10/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */
namespace Lpac_DPS\Views\Frontend;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Functions ;
use  Lpac_DPS\Models\Plugin_Settings\Localization as LocalizationSettings ;
use  Lpac_DPS\Views\BaseView ;
use  Lpac_DPS\Models\Plugin_Settings\OrderType as OrderTypeSettings ;
use  Lpac_DPS\Models\Plugin_Settings\Scheduling as SchedulingSettings ;
/**
 * Class CheckoutPage.
 */
class CheckoutPage extends BaseView
{
    /**
     * Create our Switcher element.
     *
     * @return void
     * @since 1.0.0
     */
    private function createOrderTypeSwitch() : void
    {
        // If neither delivery nor pickup scheduling isn't enabled on the site then don't show order type switch options.
        if ( OrderTypeSettings::isDeliveryEnabled() === false || OrderTypeSettings::isPickupEnabled() === false ) {
            return;
        }
        $pickup_text = LocalizationSettings::getCheckoutPickupText();
        $delivery_text = LocalizationSettings::getCheckoutDeliveryText();
        $change_order_to_text = LocalizationSettings::getCheckoutChangeOrderToText();
        $default_order_type = OrderTypeSettings::getDefaultOrderType();
        $default_text = ( $this->delivery === $default_order_type ? $pickup_text : $delivery_text );
        ?>
		<h3>
			<!-- Section header on checkout page. -->
			<?php 
        echo  esc_html( LocalizationSettings::getCheckoutOrderTypeText() ) ;
        ?>
			<span class='lpac-dps-current-order-type'>
				<?php 
        /**
         * Free doesn't have user role logic so just check which order type is default.
         */
        
        if ( $this->delivery === $default_order_type ) {
            echo  esc_html( $delivery_text ) ;
        } else {
            echo  esc_html( $pickup_text ) ;
        }
        
        ?>
			</span>
		</h3>
		<div style='display: flex; align-items: center; margin-bottom: 20px'>
			<span style='margin-right: 10px'>
				<?php 
        echo  esc_html( $change_order_to_text ) ;
        ?> <strong><?php 
        echo  esc_html( $default_text ) ;
        ?></strong>
			</span>
			<label class='lpac-dps-switch'>
				<input id='lpac-dps-order-type-switch' type='checkbox'>
				<span class='lpac-dps-slider round'>
				</span>
			</label>
		</div>
		<?php 
    }
    
    /**
     * Create buttons version of switcher.
     *
     * @return void
     * @since 1.2.2
     */
    private function createOrderTypeBtns()
    {
        // If neither delivery nor pickup scheduling isn't enabled on the site then don't show order type switch options.
        if ( OrderTypeSettings::isDeliveryEnabled() === false || OrderTypeSettings::isPickupEnabled() === false ) {
            return;
        }
        $default_order_type = OrderTypeSettings::getDefaultOrderType();
        $delivery_btn_opacity = ( $this->pickup === $default_order_type ? 'opacity: 0.3' : '' );
        $pickup_btn_opacity = ( $this->delivery === $default_order_type ? 'opacity: 0.3' : '' );
        ?>
		<div style='width: 100%; text-align: center; margin: 40px 0'>
			<h3><?php 
        echo  esc_html( LocalizationSettings::getCheckoutOrderTypeText() ) ;
        ?></h3>
			<a style="<?php 
        echo  esc_attr( $delivery_btn_opacity ) ;
        ?>" class="dps-order-type-btn dps-order-type-btn-delivery btn button" data-fulfillment-type='delivery' href="#"><?php 
        echo  esc_html( LocalizationSettings::getCheckoutDeliveryText() ) ;
        ?></a>
			<a style="<?php 
        echo  esc_attr( $pickup_btn_opacity ) ;
        ?>" class="dps-order-type-btn dps-order-type-btn-pickup btn button" data-fulfillment-type='pickup' href="#"><?php 
        echo  esc_html( LocalizationSettings::getCheckoutPickupText() ) ;
        ?></a>
		</div>
		<style>
			.dps-order-type-btn:hover {
				opacity: 1 !important;
			}
		</style>
		<script>
			document.querySelectorAll('.dps-order-type-btn').forEach(function(el) {

				el.addEventListener('click', function(el) {

					el.preventDefault();

					const deliveryFields = [
						"#lpac_dps_delivery_date_field",
						"#lpac_dps_delivery_time_field",
						"#delivery-customer-note",
						"#lpac_dps_delivery_location_field",
					];

					const pickupFields = [
						"#lpac_dps_pickup_date_field",
						"#lpac_dps_pickup_time_field",
						"#pickup-customer-note",
						"#lpac_dps_pickup_location_field",
					];

					// Toggle fields when clicked order type is not the current selected one. This needs to take place before updating the order type.
					if (document.querySelector('#lpac_dps_order_type').value !== el.target.dataset.fulfillmentType) {

						deliveryFields.forEach((field) => {
							const el = document.querySelector(field);
							if (el) {
								el.classList.toggle("hidden");
							}
						});

						pickupFields.forEach((field) => {
							const el = document.querySelector(field);
							if (el) {
								el.classList.toggle("hidden");
							}
						});

					}

					el.target.style.opacity = 1;
					if (el.target.dataset.fulfillmentType === 'delivery') {
						document.querySelector('.dps-order-type-btn-pickup').style.opacity = 0.3;
						document.querySelector('#lpac_dps_order_type').value = 'delivery';
					} else {
						document.querySelector('.dps-order-type-btn-delivery').style.opacity = 0.3;
						document.querySelector('#lpac_dps_order_type').value = 'pickup';
					}

					jQuery(document.body).trigger("update_checkout");
				});
			})
		</script>
		<?php 
    }
    
    /**
     * Create delivery scheduling fields on checkout page.
     *
     * @param array $field_class The class(es) to add to fields.
     * @return void
     * @since 1.0.0
     */
    private function createDeliveryFields( array $field_class = array() ) : void
    {
        $delivery_enabled = OrderTypeSettings::isDeliveryEnabled();
        if ( false === $delivery_enabled ) {
            return;
        }
        $delivery_settings = new SchedulingSettings( $this->delivery );
        $delivery_date_enabled = $delivery_settings->orderDateFieldEnabled();
        $delivery_time_enabled = $delivery_settings->orderTimeFieldEnabled();
        $label_delivery_date = $delivery_settings->get_date_selector_label();
        $delivery_date_required = $delivery_settings->is_date_required();
        $label_delivery_time = $delivery_settings->get_timefield_label();
        $delivery_time_required = $delivery_settings->is_time_required();
        $delivery_times = $delivery_settings->getSavedTimeslots();
        $delivery_customer_note = $delivery_settings->get_customer_note();
        $delivery_customer_font_size = $delivery_settings->get_customer_note_font_size();
        do_action( 'lpac_dps_before_delivery_fields', $delivery_settings );
        if ( $delivery_date_enabled ) {
            woocommerce_form_field( 'lpac_dps_delivery_date', array(
                'type'     => 'text',
                'label'    => $label_delivery_date,
                'required' => $delivery_date_required,
                'priority' => PHP_INT_MAX,
                'class'    => $field_class,
            ), '' );
        }
        if ( $delivery_time_enabled && !empty($delivery_times) ) {
            woocommerce_form_field( 'lpac_dps_delivery_time', array(
                'type'     => 'text',
                'label'    => $label_delivery_time,
                'required' => $delivery_time_required,
                'priority' => PHP_INT_MAX,
                'class'    => array_merge( $field_class, array( 'dps-time-field' ) ),
            ), '' );
        }
        
        if ( !empty($delivery_customer_note) ) {
            $class = ( $this->pickup === OrderTypeSettings::getDefaultOrderType() ? 'hidden' : '' );
            ?>
			<p id="delivery-customer-note" class="<?php 
            echo  esc_attr( $class ) ;
            ?>" style="font-style: italic; font-size: <?php 
            echo  esc_attr( $delivery_customer_font_size ) ;
            ?>"><?php 
            echo  esc_html( $delivery_customer_note ) ;
            ?></p>
			<?php 
        }
        
        do_action( 'lpac_dps_after_delivery_fields', $delivery_settings );
    }
    
    /**
     * Create pickup scheduling fields on checkout page.
     *
     * @return void
     * @since 1.0.0
     */
    private function createPickupFields( $field_class = array() ) : void
    {
        $pickup_enabled = OrderTypeSettings::isPickupEnabled();
        if ( false === $pickup_enabled ) {
            return;
        }
        $pickup_settings = new SchedulingSettings( $this->pickup );
        $pickup_date_enabled = $pickup_settings->orderDateFieldEnabled();
        $pickup_time_enabled = $pickup_settings->orderTimeFieldEnabled();
        $label_pickup_date = $pickup_settings->get_date_selector_label();
        $pickup_date_required = $pickup_settings->is_date_required();
        $label_pickup_time = $pickup_settings->get_timefield_label();
        $pickup_time_required = $pickup_settings->is_time_required();
        $pickup_times = $pickup_settings->getSavedTimeslots();
        $pickup_customer_note = $pickup_settings->get_customer_note();
        $pickup_customer_font_size = $pickup_settings->get_customer_note_font_size();
        do_action( 'lpac_dps_before_pickup_fields', $pickup_settings );
        if ( $pickup_date_enabled ) {
            woocommerce_form_field( 'lpac_dps_pickup_date', array(
                'type'     => 'text',
                'label'    => $label_pickup_date,
                'required' => $pickup_date_required,
                'priority' => PHP_INT_MAX,
                'class'    => $field_class,
            ), '' );
        }
        if ( $pickup_time_enabled && !empty($pickup_times) ) {
            woocommerce_form_field( 'lpac_dps_pickup_time', array(
                'type'     => 'text',
                'label'    => $label_pickup_time,
                'required' => $pickup_time_required,
                'priority' => PHP_INT_MAX,
                'class'    => array_merge( $field_class, array( 'dps-time-field' ) ),
            ), '' );
        }
        
        if ( !empty($pickup_customer_note) ) {
            $class = ( $this->delivery === OrderTypeSettings::getDefaultOrderType() ? 'hidden' : '' );
            ?>
			<p id="pickup-customer-note" class="<?php 
            echo  esc_attr( $class ) ;
            ?>" style="font-style: italic; font-size: <?php 
            echo  esc_html( $pickup_customer_font_size ) ;
            ?>"><?php 
            echo  esc_html( $pickup_customer_note ) ;
            ?></p>
			<?php 
        }
        
        do_action( 'lpac_dps_after_pickup_fields', $pickup_settings );
    }
    
    /**
     * Create our Delivery and Pickup input fields.
     *
     * @return void
     * @since 1.0.0
     */
    private function createDpsFields() : void
    {
        do_action( 'lpac_dps_before_fields' );
        $default_order_type = OrderTypeSettings::getDefaultOrderType();
        $pickup_field_class = ( $this->pickup === $default_order_type ? array() : array( 'hidden' ) );
        $delivery_field_class = ( $this->delivery === $default_order_type ? array() : array( 'hidden' ) );
        // Free version.
        woocommerce_form_field( 'lpac_dps_order_type', array(
            'type'              => 'text',
            'label'             => 'Order Type',
            'priority'          => PHP_INT_MAX,
            'required'          => true,
            'class'             => ( LPAC_DPS_DEBUG ? array() : array( 'hidden' ) ),
            'custom_attributes' => array(
            'readonly' => true,
        ),
        ), $default_order_type );
        $this->createDeliveryFields( $delivery_field_class );
        $this->createPickupFields( $pickup_field_class );
        // if ( Misc::showCurrentTime() ) {
        // echo "<p id='dps-current-time'>" . Misc::currentTimeText() . ' <span></span></p>';
        // }
        do_action( 'lpac_dps_after_fields' );
    }
    
    /**
     * Create our custom checkout fields.
     *
     * @return void
     * @since 1.0.0
     */
    public function add_checkout_fields() : void
    {
        // If both delivery and pickups are disabled on the site then don't show any fields.
        if ( OrderTypeSettings::isDeliveryEnabled() === false && OrderTypeSettings::isPickupEnabled() === false ) {
            return;
        }
        $default_order_type = OrderTypeSettings::getDefaultOrderType();
        /**
         * Its very important that we have the expected value for the default order type or else our logic can break.
         * The default_order_type is being set as the default value for our order type field in createDpsFields()
         * This value is later consumed in PHP $_POST logic to save the order type and run further logic when viewing the order.
         *
         * @see Lpac_DPS\Views\Admin\Order::create_metabox()
         */
        
        if ( $this->delivery !== $default_order_type && $this->pickup !== $default_order_type ) {
            return;
            // Something is wrong, bail.
        }
        
        ?>
		<div id='dps-datetime-picker'>
		<?php 
        
        if ( OrderTypeSettings::getOrderTypeSelector() === 'buttons' ) {
            $this->createOrderTypeBtns();
        } else {
            $this->createOrderTypeSwitch();
        }
        
        $this->createDpsFields();
        ?>
		</div>
		<?php 
    }

}