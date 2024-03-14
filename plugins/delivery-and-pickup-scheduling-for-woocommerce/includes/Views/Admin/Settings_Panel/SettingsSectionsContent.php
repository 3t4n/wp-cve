<?php

/**
 * Holds Settings Panel Section contents.
 *
 * Author:          Uriahs Victor
 * Created on:      13/10/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */
namespace Lpac_DPS\Views\Admin\Settings_Panel;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Models\BaseModel ;
use  Lpac_DPS\Models\Plugin_Settings\GeneralSettings ;
/**
 * Class SettingsSectionsContent.
 */
class SettingsSectionsContent
{
    /**
     * Get the days of the week.
     *
     * @return array
     * @since 1.0.0
     */
    private static function get_days_of_the_week() : array
    {
        return array(
            'monday'    => __( 'Monday' ),
            'tuesday'   => __( 'Tuesday' ),
            'wednesday' => __( 'Wednesday' ),
            'thursday'  => __( 'Thursday' ),
            'friday'    => __( 'Friday' ),
            'saturday'  => __( 'Saturday' ),
            'sunday'    => __( 'Sunday' ),
        );
    }
    
    /**
     * Normalize our delivery and pickup dates for the day_of_the_week dropdown by getting an array with key => value pairs
     * By comparing our available dates against days of the week.
     *
     * @param string $order_type The order type, whether delivery or pickup.
     * @return array
     */
    private static function get_available_days_w_values( string $order_type ) : array
    {
        $available_days = BaseModel::get_setting( $order_type . '__available_days', array() );
        return array_filter( self::get_days_of_the_week(), function ( $key ) use( $available_days ) {
            if ( in_array( $key, $available_days, true ) ) {
                return true;
            }
        }, ARRAY_FILTER_USE_KEY );
    }
    
    /**
     * Get the possible date formats and allow filtering.
     *
     * @return array
     * @since 1.0.0
     */
    private static function get_date_formats() : array
    {
        $default_formats = array(
            'l, F j, Y' => date( 'l, F j, Y' ),
            'l, F j'    => date( 'l, F j' ),
            'F j, Y'    => date( 'F j, Y' ),
            'j F, Y'    => date( 'j F Y' ),
            'd/m/Y'     => date( 'd/m/Y' ),
            'd.m.Y'     => date( 'd.m.Y' ),
            'd-m-Y'     => date( 'd-m-Y' ),
            'm/d/Y'     => date( 'm/d/Y' ),
            'm.d.Y'     => date( 'm.d.Y' ),
            'm-d-Y'     => date( 'm-d-Y' ),
        );
        return apply_filters( 'lpac_dps_date_formats', $default_formats );
    }
    
    /**
     * The available locations where our date and time fields can appear.
     *
     * @return array
     * @since 1.0.0
     */
    private static function get_available_hooks() : array
    {
        $hooks = array(
            'woocommerce_before_order_notes'            => __( 'Before order notes', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'woocommerce_review_order_before_payment'   => __( 'Before payment options', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'woocommerce_before_checkout_billing_form'  => __( 'Billing address area - Top', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'woocommerce_after_checkout_billing_form'   => __( 'Billing address area - Bottom', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'woocommerce_before_checkout_shipping_form' => __( 'Shipping address area - Top', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'woocommerce_after_checkout_shipping_form'  => __( 'Shipping address area - Bottom', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        );
        return apply_filters( 'lpac_dps_available_location_hooks', $hooks );
    }
    
    /**
     * Create General Settings fields.
     *
     * @return array
     * @since 1.0.0
     */
    public static function general_settings__create_basic_fields() : array
    {
        return array(
            array(
            'id'       => 'general__enable_dps_plugin',
            'type'     => 'switcher',
            'title'    => __( 'Enable Plugin', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => true,
        ),
            array(
            'id'       => 'general__fields_display_location',
            'title'    => __( 'Fields display location', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Choose where the date and time fields should appear on the checkout page.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'     => 'select',
            'options'  => self::get_available_hooks(),
            'default'  => 'woocommerce_review_order_before_payment',
        ),
            array(
            'id'       => 'general__site_timezone',
            'title'    => __( 'Select timezone', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Set the timezone of your store. The timezone set in WordPress settings will be used if none is selected here.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'     => 'select',
            'options'  => Timezones::getTimezones(),
            'default'  => wp_timezone_string(),
        ),
            array(
            'id'          => 'general__date_format',
            'title'       => __( 'Date format', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'        => 'select',
            'placeholder' => __( 'Select an option', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'options'     => self::get_date_formats(),
            'default'     => 'F j, Y',
        ),
            array(
            'id'       => 'general__datetime_format',
            'type'     => 'select',
            'title'    => __( 'Select time format', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'This is the time format that will be used when creating your time schedules as well as what will be shown on the checkout page. A refresh might be required to see your changes on the settings page.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'options'  => array(
            '12hr' => '12hr',
            '24hr' => '24hr',
        ),
            'default'  => '12hr',
        ),
            array(
            'id'          => 'general__first_day_of_week',
            'title'       => __( 'Week starts on', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'        => 'select',
            'placeholder' => __( 'Select an option', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'options'     => self::get_days_of_the_week(),
            'default'     => 'monday',
        )
        );
    }
    
    /**
     * Create General Settings fields.
     *
     * @return array
     * @since 1.0.0
     */
    public static function general_settings__create_emails_fields() : array
    {
        return array( array(
            'id'       => 'general__enable_datetime_in_emails',
            'type'     => 'switcher',
            'title'    => __( 'Include the fulfillment date/time inside emails.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => false,
        ), array(
            'id'       => 'general__datetime_location_in_email',
            'title'    => __( 'Where should the date/time fields appear', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Choose where the date and time fields should appear in the emails.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'     => 'select',
            'options'  => array(
            'woocommerce_email_before_order_table' => __( 'Before Order Table', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'woocommerce_email_customer_details'   => __( 'Before Customer Details', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            'default'  => 'woocommerce_email_customer_details',
        ), array(
            'id'       => 'general__datetime_included_emails',
            'title'    => __( 'Show in the following emails', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Select which emails should include the selected date/time selected by the customer.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'     => 'select',
            'chosen'   => true,
            'multiple' => true,
            'options'  => array(
            'new_order'                 => __( 'New Order (Admin)', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'customer_processing_order' => __( 'Processing Order', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'customer_on_hold_order'    => __( 'Order on Hold', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'customer_note'             => __( 'Customer Note', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'customer_completed_order'  => __( 'Completed Order', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'customer_invoice'          => __( 'Customer Invoice', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
        ) );
    }
    
    /**
     * Create Order Type settings fields.
     *
     * @return array
     * @since 1.0.0
     */
    public static function order_type__create_fields() : array
    {
        $learn_more = __( 'Learn more', 'delivery-and-pickup-scheduling-for-woocommerce' );
        /**
         * This is for backwards compatibility to not set the default option as buttons for old users who might have made custom changes to the switch.
         * The ability to change order type selector came in v1.2.2.
         * A better alternative would be to create a migration task to change the setting directly in the DB array and then later remove that migration
         * task once we're confident most users have updated to the version where we added the migration task.
         */
        $installed_at = get_option( 'lpac_dps_installed_at_version' );
        $default_selector_type = ( version_compare( $installed_at, '1.2.2', '>=' ) ? 'buttons' : 'switch' );
        return array(
            array(
            'id'       => 'order_type__selector_type',
            'type'     => 'select',
            'title'    => __( 'Selector type', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'The type of element customers will use to select their order type.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'options'  => array(
            'buttons' => __( 'Buttons', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'switch'  => __( 'Switch', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            'default'  => $default_selector_type,
        ),
            array(
            'id'       => 'order_type__default',
            'type'     => 'select',
            'title'    => __( 'Set default order type', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'The default order type that will be selected when the customer lands on the checkout page.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'options'  => array(
            'delivery' => __( 'Delivery', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'pickup'   => __( 'Pickup', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            'default'  => 'delivery',
        ),
            array(
            'id'       => 'order_type__enable_delivery',
            'type'     => 'switcher',
            'title'    => __( 'Enable the delivery features of the plugin', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'This option needs to be enabled for Delivery Scheduling features to take effect on the checkout page.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => true,
        ),
            array(
            'id'       => 'order_type__enable_pickup',
            'type'     => 'switcher',
            'title'    => __( 'Enable the pickup features of the plugin', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'This option needs to be enabled for Pickup Scheduling features to take effect on the checkout page.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => true,
        ),
            array(
            'id'       => 'order_type__filter_shipping_methods',
            'type'     => 'switcher',
            'title'    => __( 'Show only applicable shipping methods', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Show only the shipping methods that apply to the order type selected by the customer.', 'delivery-and-pickup-scheduling-for-woocommerce' ) . " <a href='https://chwazidatetime.com/docs/order-type/#show-only-applicable-shipping-methods' target='_blank' rel='noreferrer'>{$learn_more}</a>",
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => true,
        )
        );
    }
    
    /**
     * Create Delivery Date settings fields.
     *
     * @param string $order_type The type of setting, whether delivery or pickup.
     * @return array
     * @since 1.0.0
     */
    public static function scheduling__create_date_fields( string $order_type ) : array
    {
        return array(
            array(
            'type'    => 'heading',
            'content' => ( 'delivery' === $order_type ? __( 'Configure delivery related settings', 'delivery-and-pickup-scheduling-for-woocommerce' ) : __( 'Configure pickup related settings', 'delivery-and-pickup-scheduling-for-woocommerce' ) ),
        ),
            array(
            'id'       => $order_type . '__enable_date_feature',
            'type'     => 'switcher',
            'title'    => __( 'Enable date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => true,
        ),
            array(
            'id'         => $order_type . '__choose_date_field_label',
            'type'       => 'text',
            'title'      => __( 'Date field label', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'    => __( 'Choose date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'dependency' => array( $order_type . '__enable_date_feature', '==', 'true' ),
            'validate'   => 'csf_validate_required',
        ),
            array(
            'id'         => $order_type . '__available_days',
            'type'       => 'checkbox',
            'title'      => ( 'delivery' === $order_type ? __( 'Available delivery days', 'delivery-and-pickup-scheduling-for-woocommerce' ) : __( 'Available pickup days', 'delivery-and-pickup-scheduling-for-woocommerce' ) ),
            'options'    => self::get_days_of_the_week(),
            'dependency' => array( $order_type . '__enable_date_feature', '==', 'true' ),
            'default'    => array_keys( self::get_days_of_the_week() ),
        ),
            array(
            'id'         => $order_type . '__date_required',
            'type'       => 'switcher',
            'title'      => __( 'Make date field required', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'    => __( 'Yes' ),
            'text_off'   => __( 'No' ),
            'dependency' => array( $order_type . '__enable_date_feature', '==', 'true' ),
        ),
            array(
            'id'         => $order_type . '__no_date_selected_notice_text',
            'type'       => 'text',
            'title'      => __( 'Notice text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Text that shows if a date is not selected.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'    => __( 'Please select a date for your order.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'dependency' => array( "{$order_type}__enable_date_feature|{$order_type}__date_required", '==|==', 'true|true' ),
            'validate'   => 'csf_validate_required',
        ),
            array(
            'id'         => $order_type . '__minimum_days_in_future',
            'type'       => 'text',
            'title'      => __( 'Minimum days in future', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Set the minimum number of days in the future (from the current day) that customers will be able to schedule an order for. Useful if you do not want customers placing an order for the current day. Enter 0 if you want customers to be able to place an order for the current day.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'    => '0',
            'dependency' => array( $order_type . '__enable_date_feature', '==', 'true' ),
            'validate'   => 'csf_validate_numeric',
        ),
            array(
            'id'         => $order_type . '__maximum_days_in_future',
            'type'       => 'text',
            'title'      => __( 'Maximum days in future', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Set the maximum number of days in the future (from the current day) that customers will be able to schedule an order. Enter 0 if you want unlimited days.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'    => '120',
            'dependency' => array( $order_type . '__enable_date_feature', '==', 'true' ),
            'validate'   => 'csf_validate_numeric',
        )
        );
    }
    
    /**
     * Remove duplicated days from our list of time slots.
     *
     * @param array $time_slots The entered time slots.
     * @return array
     */
    public static function drop_duplicate_days( $time_slots ) : array
    {
        if ( !is_array( $time_slots ) ) {
            return array();
        }
        $time_slots = array_unique( $time_slots, SORT_REGULAR );
        $hold_days = array();
        $cleaned = array_filter( $time_slots, function ( $item ) use( &$hold_days ) {
            $day_of_the_week = $item['day_of_the_week'];
            // If the currently looped day is not already in our hold array, then grab it.
            
            if ( !in_array( $day_of_the_week, $hold_days, true ) ) {
                $hold_days[] = $day_of_the_week;
                return true;
            }
            
            $hold_days[] = $day_of_the_week;
        } );
        unset( $hold_days );
        return array_values( $cleaned );
    }
    
    /**
     * Create fields for time slot feature.
     *
     * @param string $order_type
     * @return array
     * @since 1.2.2
     */
    private static function createTimeslotFields( string $order_type ) : array
    {
        $time_format = GeneralSettings::get_preferred_time_format();
        $fee_types = array(
            'standard'  => __( 'Standard flat fee', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'standard2' => __( 'Number of items in cart', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' (' . __( 'flat fee', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ')' . ' [PRO]',
            'standard3' => __( 'Number of unique items in cart', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' (' . __( 'flat fee', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ')' . ' [PRO]',
            'standard4' => __( 'Cart subtotal', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' (' . __( 'flat fee', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ')' . ' [PRO]',
            'standard5' => __( 'Number of items in cart', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' (%)' . ' [PRO]',
            'standard6' => __( 'Number of unique items in cart', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' (%)' . ' [PRO]',
            'standard7' => __( 'Cart subtotal', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' (%)' . ' [PRO]',
            'standard8' => __( 'Custom condition (advanced)', 'delivery-and-pickup-scheduling-for-woocommerce' ) . ' [PRO]',
        );
        return array( array(
            'id'       => 'time_range',
            'type'     => 'datetime',
            'title'    => __( 'Slot', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'from_to'  => true,
            'settings' => array(
            'noCalendar' => true,
            'enableTime' => true,
            'dateFormat' => ( '12hr' === $time_format ? 'h:i K' : 'H:i' ),
            'time_24hr'  => boolval( '24hr' === $time_format ),
        ),
        ), array(
            'id'         => 'time_slot_fee',
            'type'       => 'accordion',
            'title'      => 'Fee',
            'accordions' => array( array(
            'title'  => 'Settings',
            'fields' => array( array(
            'id'      => 'fee_name',
            'type'    => 'text',
            'title'   => __( 'Fee name', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => __( 'Additional Charge', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ), array(
            'id'      => 'fee_type',
            'title'   => __( 'Fee type', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'    => 'select',
            'options' => $fee_types,
        ), array(
            'id'          => 'fee_amount',
            'type'        => 'text',
            'title'       => __( 'Fee amount', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'placeholder' => '0.50',
        ) ),
        ) ),
            'dependency' => array(
            $order_type . '__time_slot_fee',
            '==',
            'true',
            'all'
        ),
        ) );
    }
    
    /**
     * Create Delivery Time settings fields.
     *
     * @return array
     * @param string $order_type The type of setting, whether delivery or pickup.
     * @since 1.0.0
     */
    public static function scheduling__create_time_fields( string $order_type ) : array
    {
        $available_days = BaseModel::get_setting( $order_type . '__available_days', array() );
        $learn_more = __( 'Learn more', 'delivery-and-pickup-scheduling-for-woocommerce' );
        if ( empty($available_days) ) {
            return array( array(
                'type'    => 'notice',
                'style'   => 'danger',
                'content' => __( 'No operation days detected. Please select your operating days, save your changes, refresh the page and then come back to this tab to set your times.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            ) );
        }
        $fields = array(
            array(
            'id'       => $order_type . '__enable_time_feature',
            'type'     => 'switcher',
            'title'    => __( 'Enable time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
            'default'  => true,
        ),
            array(
            'id'         => $order_type . '__time_required',
            'type'       => 'switcher',
            'title'      => __( 'Make time field required', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'    => __( 'Yes' ),
            'text_off'   => __( 'No' ),
            'dependency' => array( $order_type . '__enable_time_feature', '==', 'true' ),
        ),
            array(
            'id'         => $order_type . '__no_time_selected_notice_text',
            'type'       => 'text',
            'title'      => __( 'Notice text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Text that shows if a time is not selected.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'    => ( 'delivery' === $order_type ? __( 'Please select a time for delivery.', 'delivery-and-pickup-scheduling-for-woocommerce' ) : __( 'Please select a time for pickup.', 'delivery-and-pickup-scheduling-for-woocommerce' ) ),
            'dependency' => array( "{$order_type}__enable_time_feature|{$order_type}__time_required", '==|==', 'true|true' ),
            'validate'   => 'csf_validate_required',
        ),
            array(
            'id'         => $order_type . '__choose_time_field_label',
            'type'       => 'text',
            'title'      => __( 'Time field label', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'    => __( 'Choose time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'dependency' => array( $order_type . '__enable_time_feature', '==', 'true' ),
        ),
            array(
            'id'         => $order_type . '__drop_passed_time_slots',
            'type'       => 'switcher',
            'title'      => __( 'Remove passed time slots from dropdown', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Enable this option to only show time slots that have not passed for a specific day.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'    => __( 'Yes' ),
            'text_off'   => __( 'No' ),
            'dependency' => array( $order_type . '__enable_time_feature', '==', 'true' ),
        ),
            array(
            'id'         => $order_type . '__order_placement_buffer',
            'type'       => 'number',
            'title'      => __( 'Order Placement Buffer', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Enter the amount of time (in minutes) you want to block off before a customer can place an order for a specific time slot.', 'delivery-and-pickup-scheduling-for-woocommerce' ) . " <a href='https://chwazidatetime.com/docs/delivery-pickup-scheduling/#order-placement-buffer' target='_blank' rel='noreferrer'>{$learn_more}</a>",
            'dependency' => array( $order_type . '__enable_time_feature', '==', 'true' ),
        ),
            array(
            'id'         => $order_type . '__time_slot_fee',
            'type'       => 'switcher',
            'title'      => __( 'Enable time slot fees', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Turn on this option to enable the setting of custom fees for time slots.', 'delivery-and-pickup-scheduling-for-woocommerce' ) . " <a href='https://chwazidatetime.com/docs/delivery-pickup-scheduling/#enable-time-slot-fees' target='_blank' rel='noreferrer'>{$learn_more}</a>",
            'text_on'    => __( 'Yes' ),
            'text_off'   => __( 'No' ),
            'dependency' => array( $order_type . '__enable_time_feature', '==', 'true' ),
        ),
            array(
            'id'         => $order_type . '__time_slot_fee_upsell',
            'type'       => 'submessage',
            'style'      => 'info',
            'content'    => sprintf( __( '%1$sUnlock more powerful and dynamic checkout fee options. All fee types will be treated as "Standard flat fee" in the Free version of the plugin.%2$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<span style="font-size: 12px; line-height: 1.5">', '</span>' ) . '<br/><br/><a href="https://chwazidatetime.com/docs/delivery-pickup-scheduling/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell#enable-time-slot-fees" rel="noreferrer" target="_blank" style="font-size: 12px">' . $learn_more . '&nbsp<i class="fas fa-external-link-alt"></i></a>',
            'dependency' => array( array( $order_type . '__enable_time_feature', '==', 'true' ), array( $order_type . '__time_slot_fee', '==', 'true' ) ),
        ),
            array(
            'id'         => $order_type . '__time_slots_nested_repeater',
            'type'       => 'repeater',
            'title'      => __( 'Create your times', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Not setting timeslots for an available day will allow the customer to select their own time for that day.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'max'        => count( $available_days ),
            'fields'     => array( array(
            'id'      => 'day_of_the_week',
            'type'    => 'select',
            'title'   => __( 'Day of the week', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'options' => self::get_available_days_w_values( $order_type ),
        ), array(
            'id'       => 'time_slots',
            'type'     => 'repeater',
            'title'    => __( 'Time slots', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => sprintf( __( 'Leave %1$sto%2$s field blank if time slot is not a range.', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<strong>', '</strong>' ),
            'fields'   => self::createTimeslotFields( $order_type ),
        ) ),
            'sanitize'   => array( new self(), 'drop_duplicate_days' ),
            'dependency' => array( $order_type . '__enable_time_feature', '==', 'true' ),
        )
        );
        return $fields;
    }
    
    /**
     * Create customer notes fields.
     *
     * @return array
     * @param string $order_type The type of setting, whether delivery or pickup.
     * @since 1.0.0
     */
    public static function scheduling__create_customer_note_fields( string $order_type ) : array
    {
        return array( array(
            'id'         => $order_type . '__customer_note',
            'type'       => 'textarea',
            'title'      => __( 'Customer Note', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle'   => __( 'Enter a message that appears below the scheduling fields on the checkout page.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'attributes' => array(
            'style' => 'min-height: 50px;',
        ),
        ), array(
            'id'       => $order_type . '__font_size',
            'type'     => 'text',
            'title'    => __( 'Font size', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Without "px"', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => 14,
            'validate' => 'csf_validate_numeric',
        ) );
    }
    
    /**
     * Create upsell section for Google Calendar Addon
     *
     * @return array
     * @since 1.2.5
     */
    public static function googleCalendarUpsellFields() : array
    {
        return array( array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => sprintf(
            __( '%1$sStay organized and on schedule with seamless order management by syncing orders directly to your Google Calendar. Utilize Google\'s built-in alerting features to receive timely notifications when orders are due %2$s %3$sLEARN MORE%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            '<span style="font-size: 18px; line-height: 1.5">',
            '</span>',
            '<br/><br/><a href="https://chwazidatetime.com/google-calendar-add-on/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell-addon" target="_blank" style="font-size: 20px">',
            '&nbsp<i class="fas fa-external-link-alt"></i></a>'
        ),
        ) );
    }
    
    /**
     * Create fields for Email Reminders feature.
     *
     * @return array
     * @since 1.1.0
     */
    public static function createEmailReminderFields() : array
    {
        return array( array(
            'type'    => 'notice',
            'style'   => 'info',
            'content' => __( 'Reminders will only be scheduled when customers enter a delivery/pickup time. Make those fields required if you want reminders to always be sent.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ), array(
            'id'         => 'emails__delivery_reminders',
            'type'       => 'accordion',
            'title'      => __( 'Delivery reminders', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'accordions' => array( array(
            'title'  => __( 'Settings', 'default' ),
            'fields' => array(
            array(
            'id'       => 'enable_delivery_reminder_feature',
            'type'     => 'switcher',
            'title'    => __( 'Enable delivery reminders', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
        ),
            array(
            'id'       => 'time_before',
            'type'     => 'text',
            'title'    => __( 'Time before', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'How long before the delivery should a reminder email be sent. Enter value in minutes.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_numeric',
        ),
            array(
            'id'       => 'include_order_details',
            'type'     => 'switcher',
            'title'    => __( 'Include order details', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Enable option to include original order details inside the reminder email.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
        ),
            array(
            'id'       => 'email_subject',
            'type'     => 'text',
            'title'    => __( 'E-mail subject', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Enter the subject to be used for the reminder email', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => __( 'Your order will soon be on the way!', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'email_heading',
            'type'     => 'text',
            'title'    => __( 'E-mail heading', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'The heading text to display', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => __( 'Upcoming Order', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'email_body',
            'type'     => 'wp_editor',
            'title'    => __( 'E-mail body', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Add some text to the email body.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => __( 'Hi {billing_first_name}, Thank you for your order! It will soon be on the way.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => sprintf(
            __( 'There are some available Magic Tags that you can use in your email such as: %1$s %2$sRead this document%3$s for a full list of available tags.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            '<code>{billing_first_name}</code><code>{billing_last_name}</code>',
            "<a href='https://chwazidatetime.com/docs/reminders-available-magic-tags/' target='_blank'>",
            '</a>'
        ),
        )
        ),
        ) ),
            'default'    => array(),
        ), array(
            'id'         => 'emails__pickup_reminders',
            'type'       => 'accordion',
            'title'      => __( 'Pickup reminders', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'accordions' => array( array(
            'title'  => __( 'Settings' ),
            'fields' => array(
            array(
            'id'       => 'enable_pickup_reminder_feature',
            'type'     => 'switcher',
            'title'    => __( 'Enable pickup reminders', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
        ),
            array(
            'id'       => 'time_before',
            'type'     => 'text',
            'title'    => __( 'Time before', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'How long before the delivery should a reminder email be sent. Enter value in minutes.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_numeric',
        ),
            array(
            'id'       => 'include_order_details',
            'type'     => 'switcher',
            'title'    => __( 'Include order details', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Enable option to include original order details inside the reminder email.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => __( 'Yes' ),
            'text_off' => __( 'No' ),
        ),
            array(
            'id'       => 'email_subject',
            'type'     => 'text',
            'title'    => __( 'E-mail subject', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Enter the subject to be used for the reminder email', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => __( 'Its almost {order_fulfillment_time}! You can soon come in for your order.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'email_heading',
            'type'     => 'text',
            'title'    => __( 'E-mail heading', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'The heading text to display', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => __( 'Upcoming Order', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'email_body',
            'type'     => 'wp_editor',
            'title'    => __( 'E-mail body', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => __( 'Add some text to the email body.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default'  => __( 'Hi {billing_first_name}, Thank you for your order! You can soon come in for pickup.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => sprintf(
            __( 'There are some available Magic Tags that you can use in your email such as: %1$s %2$sRead this document%3$s for a full list of available tags.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            '<code>{billing_first_name}</code><code>{billing_last_name}</code>',
            "<a href='https://chwazidatetime.com/docs/reminders-available-magic-tags/' target='_blank'>",
            '</a>'
        ),
        )
        ),
        ) ),
            'default'    => array(),
        ) );
    }
    
    /**
     * Create calendar localization fields.
     *
     * @return array
     * @since 1.0.0
     */
    public static function localization__create_calendar_fields() : array
    {
        return array( array(
            'id'         => 'localization__weekdays',
            'type'       => 'accordion',
            'title'      => __( 'Weekdays', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'accordions' => array(
            /**
             * The order of the days of the week inside our saved array is important!
             * The week always needs to start on sunday (index 0) in code because flatpickr expects it to be so.
             * Not having sunday as the index 0 in our array would cause flatpickr to show the wrong order of days in the calendar
             * It would also affect the disabling of dates in flatpickr
             *
             * @see Lpac_DPS\Models\Plugin_Settings::get_weekdays();
             * @see prepareDisabledDates() in checkout-page.js script
             */
            array(
                'title'  => __( 'Shorthand', 'delivery-and-pickup-scheduling-for-woocommerce' ),
                'fields' => array(
                array(
                'id'       => 'sun',
                'type'     => 'text',
                'title'    => __( 'Sunday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'mon',
                'type'     => 'text',
                'title'    => __( 'Monday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'tue',
                'type'     => 'text',
                'title'    => __( 'Tuesday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'wed',
                'type'     => 'text',
                'title'    => __( 'Wednesday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'thu',
                'type'     => 'text',
                'title'    => __( 'Thursday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'fri',
                'type'     => 'text',
                'title'    => __( 'Friday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'sat',
                'type'     => 'text',
                'title'    => __( 'Saturday' ),
                'validate' => 'csf_validate_required',
            )
            ),
            ),
            array(
                'title'  => __( 'Longhand', 'delivery-and-pickup-scheduling-for-woocommerce' ),
                'fields' => array(
                array(
                'id'       => 'sunday',
                'type'     => 'text',
                'title'    => __( 'Sunday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'monday',
                'type'     => 'text',
                'title'    => __( 'Monday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'tuesday',
                'type'     => 'text',
                'title'    => __( 'Tuesday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'wednesday',
                'type'     => 'text',
                'title'    => __( 'Wednesday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'thursday',
                'type'     => 'text',
                'title'    => __( 'Thursday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'friday',
                'type'     => 'text',
                'title'    => __( 'Friday' ),
                'validate' => 'csf_validate_required',
            ),
                array(
                'id'       => 'saturday',
                'type'     => 'text',
                'title'    => __( 'Saturday' ),
                'validate' => 'csf_validate_required',
            )
            ),
            ),
        ),
            'default'    => array(
            'sun'       => 'Sun',
            'mon'       => 'Mon',
            'tue'       => 'Tue',
            'wed'       => 'Wed',
            'thu'       => 'Thu',
            'fri'       => 'Fri',
            'sat'       => 'Sat',
            'sunday'    => 'Sunday',
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
        ),
        ), array(
            'id'         => 'localization__months',
            'type'       => 'accordion',
            'title'      => __( 'Months', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'accordions' => array( array(
            'title'  => __( 'Shorthand', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => array(
            array(
            'id'       => 'jan',
            'type'     => 'text',
            'title'    => __( 'January' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'feb',
            'type'     => 'text',
            'title'    => __( 'February' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'mar',
            'type'     => 'text',
            'title'    => __( 'March' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'apr',
            'type'     => 'text',
            'title'    => __( 'April' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'may',
            'type'     => 'text',
            'title'    => __( 'May' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'jun',
            'type'     => 'text',
            'title'    => __( 'June' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'jul',
            'type'     => 'text',
            'title'    => __( 'July' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'aug',
            'type'     => 'text',
            'title'    => __( 'August' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'sep',
            'type'     => 'text',
            'title'    => __( 'September' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'oct',
            'type'     => 'text',
            'title'    => __( 'October' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'nov',
            'type'     => 'text',
            'title'    => __( 'November' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'dec',
            'type'     => 'text',
            'title'    => __( 'December' ),
            'validate' => 'csf_validate_required',
        )
        ),
        ), array(
            'title'  => __( 'Longhand', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => array(
            array(
            'id'       => 'january',
            'type'     => 'text',
            'title'    => __( 'January' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'february',
            'type'     => 'text',
            'title'    => __( 'February' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'march',
            'type'     => 'text',
            'title'    => __( 'March' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'april',
            'type'     => 'text',
            'title'    => __( 'April' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'may_long',
            'type'     => 'text',
            'title'    => __( 'May' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'june',
            'type'     => 'text',
            'title'    => __( 'June' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'july',
            'type'     => 'text',
            'title'    => __( 'July' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'august',
            'type'     => 'text',
            'title'    => __( 'August' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'september',
            'type'     => 'text',
            'title'    => __( 'September' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'october',
            'type'     => 'text',
            'title'    => __( 'October' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'november',
            'type'     => 'text',
            'title'    => __( 'November' ),
            'validate' => 'csf_validate_required',
        ),
            array(
            'id'       => 'december',
            'type'     => 'text',
            'title'    => __( 'December' ),
            'validate' => 'csf_validate_required',
        )
        ),
        ) ),
            'default'    => array(
            'jan'       => 'Jan',
            'feb'       => 'Feb',
            'mar'       => 'Mar',
            'apr'       => 'Apr',
            'may'       => 'May',
            'jun'       => 'Jun',
            'jul'       => 'Jul',
            'aug'       => 'Aug',
            'sep'       => 'Sep',
            'oct'       => 'Oct',
            'nov'       => 'Nov',
            'dec'       => 'Dec',
            'january'   => 'January',
            'february'  => 'February',
            'march'     => 'March',
            'april'     => 'April',
            'may_long'  => 'May',
            'june'      => 'June',
            'july'      => 'July',
            'august'    => 'August',
            'september' => 'September',
            'october'   => 'October',
            'november'  => 'November',
            'december'  => 'December',
        ),
        ) );
    }
    
    /**
     * Create checkout localization fields.
     *
     * @return array
     * @since 1.0.6
     */
    public static function localization__create_checkout_fields() : array
    {
        $fields = array(
            array(
            'id'      => 'localization__checkout_order_type_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Order Type text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Order Type:', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__checkout_delivery_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Delivery text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Delivery', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__checkout_pickup_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Pickup text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Pickup', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__checkout_change_order_to_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Change order type to text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Change order type to:', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        )
        );
        return $fields;
    }
    
    /**
     * Create order details localization settings fields.
     *
     * @return array
     * @since 1.0.6
     */
    public static function localization__create_order_details_page_fields() : array
    {
        $fields = array(
            array(
            'id'      => 'localization__order_details_page_delivery_details_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Delivery details" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Delivery details', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__order_details_page_pickup_details_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Pickup details" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Pickup details', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__order_details_page_delivery_date_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Delivery "Date" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__order_details_page_delivery_time_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Delivery "Time" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__order_details_page_pickup_date_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Pickup "Date" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__order_details_page_pickup_time_text',
            'type'    => 'text',
            'title'   => esc_html__( 'Pickup "Time" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        )
        );
        return $fields;
    }
    
    /**
     * Create Emails localization settings fields.
     *
     * @return array
     * @since 1.1.0
     */
    public static function createEmailLocalizationFields() : array
    {
        $fields = array(
            array(
            'id'      => 'localization__emails_order_type_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Order Type" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Order Type', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__emails_delivery_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Delivery" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Delivery', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__emails_pickup_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Pickup" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Pickup', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__emails_date_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Date" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        ),
            array(
            'id'      => 'localization__emails_time_text',
            'type'    => 'text',
            'title'   => esc_html__( '"Time" text', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'default' => esc_html__( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
        )
        );
        return $fields;
    }
    
    /**
     * Create miscellaneous settings fields.
     *
     * @return array
     * @since 1.0.0
     */
    public static function misc__create_fields() : array
    {
        return array( array(
            'id'       => 'misc__delete_settings_uninstall',
            'type'     => 'switcher',
            'title'    => esc_html__( 'Housekeeping', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => esc_html__( 'Delete all plugin settings on uninstall.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'text_on'  => esc_html__( 'Yes' ),
            'text_off' => esc_html__( 'No' ),
            'default'  => false,
        ), array(
            'id'       => 'export',
            'title'    => esc_html__( 'Export settings', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'subtitle' => esc_html__( 'Export settings.', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'type'     => 'backup',
        ) );
    }
    
    /**
     * Create content for PRO upsell tab.
     *
     * @return array
     * @since 1.0.14
     */
    public static function createProUpsellSection() : array
    {
        return array( array(
            'id'      => 'misc__pro',
            'type'    => 'content',
            'content' => sprintf(
            __( '%1$sCapacity Restrictions, Off Days, Delivery/Pickup Locations, User Roles. The PRO version of Chwazi - Delivery & Pickup Scheduling comes with more great features! Check them out %2$shere%3$s%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            '<span style="font-size: 18px; line-height: 1.5">',
            '<a href="https://chwazidatetime.com/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell" target="_blank">',
            '&nbsp<i class="fas fa-external-link-alt"></i></a>',
            '</span>'
        ),
        ) );
    }

}