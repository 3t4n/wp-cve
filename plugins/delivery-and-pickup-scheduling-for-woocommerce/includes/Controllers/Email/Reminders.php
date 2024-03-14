<?php

/**
 * File responsible for defining methods that deal with Email reminders.
 *
 * Author:          Uriahs Victor
 * Created on:      21/08/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.0
 * @package Controllers
 */
namespace Lpac_DPS\Controllers\Email;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Order as OrderHelper ;
use  Lpac_DPS\Helpers\Utilities ;
use  Lpac_DPS\Models\Plugin_Settings\Emails ;
use  WC_Email ;
/**
 * Class responsible for handling E-mail Reminders feature.
 *
 * @package Lpac_DPS\Controllers\Email
 * @since 1.1.0
 */
class Reminders
{
    /**
     * Available Magic tags that can be used for Reminders email content.
     *
     * @param mixed $order_id
     * @return array
     * @since 1.1.0
     */
    private function getAvailableMagicTags( $order_id ) : array
    {
        $order = wc_get_order( $order_id );
        $order_helper = new OrderHelper( $order_id );
        $magic_tags = array(
            '{billing_first_name}'     => $order->get_billing_first_name(),
            '{billing_last_name}'      => $order->get_billing_last_name(),
            '{billing_full_name}'      => $order->get_formatted_billing_full_name(),
            '{billing_company}'        => $order->get_billing_company(),
            '{billing_address}'        => $order->get_formatted_billing_address(),
            '{billing_postcode}'       => $order->get_billing_postcode(),
            '{billing_phone}'          => $order->get_billing_phone(),
            '{shipping_first_name}'    => $order->get_shipping_first_name(),
            '{shipping_last_name}'     => $order->get_shipping_last_name(),
            '{shipping_full_name}'     => $order->get_formatted_shipping_full_name(),
            '{shipping_company}'       => $order->get_shipping_company(),
            '{shipping_address}'       => $order->get_formatted_shipping_address(),
            '{shipping_postcode}'      => $order->get_shipping_postcode(),
            '{shipping_phone}'         => $order->get_shipping_phone(),
            '{order_type}'             => $order_helper::getOrderType(),
            '{order_fulfillment_date}' => $order_helper::getOrderFulfillmentDate(),
            '{order_fulfillment_time}' => $order_helper::getOrderFulfillmentTime(),
        );
        return apply_filters( 'dps_reminders_available_magic_tags', $magic_tags, $order );
    }
    
    /**
     * Get an order details to include inside the reminder email.
     *
     * @param int $order_id
     * @return string
     * @since 1.1.0
     */
    private function getOrderDetails( int $order_id ) : string
    {
        $mailer = WC()->mailer();
        $order_obj = wc_get_order( $order_id );
        ob_start();
        $mailer->order_details( $order_obj );
        $mailer->customer_details( $order_obj );
        $mailer->email_addresses( $order_obj );
        return ob_get_clean();
    }
    
    /**
     * Send a reminder to the customer.
     *
     * @param mixed $reminder_data
     * @return void
     * @since 1.1.0
     */
    public function sendReminder( $reminder_data )
    {
        $mailer = WC()->mailer();
        $email = new WC_Email();
        $order_id = $reminder_data['order_id'];
        $order_type = $reminder_data['order_type'];
        $to = $reminder_data['customer_email'];
        $magic_tags = $this->getAvailableMagicTags( $order_id );
        $email_subject = Emails::{$order_type . 'ReminderEmailSubject'}();
        $email_subject = Utilities::replaceMagicTags( $magic_tags, $email_subject );
        $email_heading = Emails::{$order_type . 'ReminderEmailHeading'}();
        $email_heading = Utilities::replaceMagicTags( $magic_tags, $email_heading );
        $email_message = Emails::{$order_type . 'ReminderEmailBody'}();
        $email_message = Utilities::replaceMagicTags( $magic_tags, $email_message );
        $include_order_details = Emails::{$order_type . 'ReminderIncludeOrderDetails'}();
        if ( $include_order_details ) {
            $email_message = $email_message . $this->getOrderDetails( $order_id );
        }
        $message = $mailer->wrap_message( $email_heading, $email_message );
        $headers = apply_filters( 'dps_reminder_email_headers', $email->get_headers(), $reminder_data );
        $attachments = apply_filters( 'dps_reminder_email_attachments', $email->get_attachments(), $reminder_data );
        $email->send(
            $to,
            $email_subject,
            $message,
            $headers,
            $attachments
        );
    }

}