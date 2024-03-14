<?php

/**
 * Holds Settings Panel Sections.
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
use  CSF ;
/**
 * Class SettingsSections.
 */
class SettingsSections
{
    /**
     * Create Pro feature sections as upsells in Lite version of plugin.
     *
     * @param string $order_type
     * @return void
     * @since 1.1.2
     */
    private function createProUpsellSubSections( string $order_type ) : void
    {
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => "{$order_type}_scheduling",
            'title'  => ( 'delivery' === $order_type ? __( 'Delivery Capacity', 'delivery-and-pickup-scheduling-for-woocommerce' ) : __( 'Pickup Capacity', 'delivery-and-pickup-scheduling-for-woocommerce' ) ),
            'fields' => SettingsSectionsDummyContent::createDummyCapacityFields( $order_type ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => "{$order_type}_scheduling",
            'title'  => __( 'Off Days', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsDummyContent::createDummyOffDaysFields( $order_type ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => "{$order_type}_scheduling",
            'title'  => __( 'User Roles', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsDummyContent::createDummyUserRolesFields( $order_type ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => "{$order_type}_scheduling",
            'title'  => __( 'Locations', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsDummyContent::createDummyLocationsFields( $order_type ),
        ) );
    }
    
    /**
     * Render the custom admin settings.
     *
     * @since 1.0.0
     */
    public function render_menu_sections()
    {
        /**
         * General Settings.
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'    => 'general_settings',
            'title' => __( 'General Settings', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'icon'  => 'fas fa-cogs',
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'general_settings',
            'title'  => __( 'Basics', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::general_settings__create_basic_fields(),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'general_settings',
            'title'  => __( 'Emails', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::general_settings__create_emails_fields(),
        ) );
        /**
         * Order Types
         *
         * Let admins set whether to enable delivery or pickups and also which one is enabled by default.
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'     => 'order-type',
            'title'  => __( 'Order Type', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'icon'   => 'fas fa-boxes',
            'fields' => SettingsSectionsContent::order_type__create_fields(),
        ) );
        /**
         * Delivery Scheduling
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'    => 'delivery_scheduling',
            'title' => __( 'Delivery Scheduling', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'icon'  => 'fas fa-truck',
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'delivery_scheduling',
            'title'  => __( 'Delivery Date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::scheduling__create_date_fields( 'delivery' ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'delivery_scheduling',
            'title'  => __( 'Delivery Time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::scheduling__create_time_fields( 'delivery' ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'delivery_scheduling',
            'title'  => __( 'Customer Note', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::scheduling__create_customer_note_fields( 'delivery' ),
        ) );
        if ( false === dps_fs()->can_use_premium_code() ) {
            $this->createProUpsellSubSections( 'delivery' );
        }
        /**
         * Pickup Scheduling
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'    => 'pickup_scheduling',
            'title' => __( 'Pickup Scheduling', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'icon'  => 'fas fa-store',
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'pickup_scheduling',
            'title'  => __( 'Pickup Date', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::scheduling__create_date_fields( 'pickup' ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'pickup_scheduling',
            'title'  => __( 'Pickup Time', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::scheduling__create_time_fields( 'pickup' ),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'pickup_scheduling',
            'title'  => __( 'Customer Note', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::scheduling__create_customer_note_fields( 'pickup' ),
        ) );
        if ( false === dps_fs()->can_use_premium_code() ) {
            $this->createProUpsellSubSections( 'pickup' );
        }
        /**
         * Misc
         *
         * Miscellaneous settings tab
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'     => 'google_calendar_addon',
            'title'  => __( 'Google Calendar', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::googleCalendarUpsellFields(),
            'icon'   => 'fab fa-google',
        ) );
        /**
         * Reminders
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'    => 'emails',
            'title' => __( 'Emails', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'icon'  => 'fas fa-bell',
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'emails',
            'title'  => __( 'Reminders', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::createEmailReminderFields(),
        ) );
        /**
         * Localization
         *
         * Allow admins to set custom strings for various plugin output.
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'    => 'localization',
            'title' => __( 'Localizaton', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'icon'  => 'fas fa-language',
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'localization',
            'title'  => __( 'Calendar', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::localization__create_calendar_fields(),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'localization',
            'title'  => __( 'Checkout Page', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::localization__create_checkout_fields(),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'localization',
            'title'  => __( 'Order Details', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::localization__create_order_details_page_fields(),
        ) );
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'parent' => 'localization',
            'title'  => __( 'Emails', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::createEmailLocalizationFields(),
        ) );
        /**
         * Misc
         *
         * Miscellaneous settings tab
         */
        CSF::createSection( LPAC_DPS_CSF_ID, array(
            'id'     => 'miscellaneous',
            'title'  => __( 'Misc', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'fields' => SettingsSectionsContent::misc__create_fields(),
            'icon'   => 'fas fa-random',
        ) );
        if ( false === dps_fs()->can_use_premium_code() ) {
            /**
             * PRO section
             *
             * PRO upsell section.
             */
            CSF::createSection( LPAC_DPS_CSF_ID, array(
                'id'     => 'pro-upsell',
                'title'  => __( 'PRO Version', 'delivery-and-pickup-scheduling-for-woocommerce' ),
                'fields' => SettingsSectionsContent::createProUpsellSection(),
                'icon'   => 'fas fa-star',
            ) );
        }
    }

}