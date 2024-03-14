<?php

namespace FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Emails;

use FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Helpers\EmailHelper;
class EmailRefundRequestedAdmin extends \FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Emails\AbstractRefundEmail
{
    const ID = 'fr_email_refund_admin_requested';
    public function __construct()
    {
        $this->title = \esc_html__('[Flexible Refund] New Refund Request', 'flexible-refund-and-return-order-for-woocommerce');
        $this->description = \esc_html__('New refund request', 'flexible-refund-and-return-order-for-woocommerce');
        parent::__construct();
        $this->customer_email = \false;
        $this->enabled = 'yes';
        $this->recipient = $this->get_option('recipient', \get_option('admin_email'));
    }
    public function get_default_subject()
    {
        return \esc_html__('[{shop_title}] New refund request #{order_number}', 'flexible-refund-and-return-order-for-woocommerce');
    }
    public function get_default_heading()
    {
        return \esc_html__('The new order refund request has been requested!', 'flexible-refund-and-return-order-for-woocommerce');
    }
    public function get_default_additional_content()
    {
        return \wpautop(\wp_kses(\__("Hi Admin,\n\nA new refund request for the order {order_number} appeared in {shop_url}\n\n<a href=\"{admin_order_url}\" target=\"_blank\">Click here to go to the order.</a>\n<a href=\"{admin_refunds_url}\" target=\"_blank\">Or click here to go to refund requests list.</a>", 'flexible-refund-and-return-order-for-woocommerce'), \FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Helpers\EmailHelper::allowed_tags()));
    }
}
