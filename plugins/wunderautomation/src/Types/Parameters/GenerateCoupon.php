<?php

namespace WunderAuto\Types\Parameters;

use WC_Coupon;
use WunderAuto\Resolver;
use WunderAuto\Types\Internal\FieldDescriptor;

/**
 * Class GenerateCoupon
 */
class GenerateCoupon extends BaseParameter
{
    /**
     * @var bool
     */
    public $usesCouponFields = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'create_coupon';
        $this->description = __(
            'Use this parameter to create a coupon based on an existing coupon as template',
            'wunderauto'
        );
        $this->objects     = '*';

        $this->usesDefault = true;

        add_filter('wunderauto/parameters/editorfields', [$this, 'editorFields'], 10, 1);
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $wunderAuto = wa_wa();

        $resolver   = $wunderAuto->getCurrentResolver();
        $templateId = isset($modifiers->template) ? (int)$modifiers->template : 0;
        $expires    = isset($modifiers->expires) ? (int)$modifiers->expires : 0;
        $prefix     = isset($modifiers->prefix) ? $modifiers->prefix : '';
        $usageLimit = isset($modifiers->limit) ? (int)$modifiers->limit : 0;
        $allowed    = isset($modifiers->allowed) ? $modifiers->allowed : '';
        $length     = isset($modifiers->length) ? (int)$modifiers->length : 12;

        if ($templateId === 0) {
            return '[NOTEMPLATE]';
        }

        $coupon = $this->generateCoupon($templateId, $resolver, $expires, $prefix, $usageLimit, $allowed, $length);
        return $this->formatField($coupon, $modifiers);
    }

    /**
     * @param int      $templateId
     * @param Resolver $resolver
     * @param int      $expires
     * @param string   $prefix
     * @param int      $usageLimit
     * @param string   $allowed
     * @param int      $length
     *
     * @return string
     */
    private function generateCoupon($templateId, $resolver, $expires, $prefix, $usageLimit, $allowed, $length)
    {
        if (strlen($prefix) === 0) {
            $prefix = 'wa-';
        }

        $code = $this->generateCode($prefix, $length);

        $templateCoupon = new WC_Coupon($templateId);
        if ($templateCoupon->get_id() === 0) {
            return '[INVALIDTEMPLATE]';
        }

        $newCoupon = new WC_Coupon();

        $newCoupon->set_code($code);
        $newCoupon->set_discount_type($templateCoupon->get_discount_type());
        $newCoupon->set_amount($templateCoupon->get_amount());
        $newCoupon->set_individual_use($templateCoupon->get_individual_use());
        $newCoupon->set_product_ids($templateCoupon->get_product_ids());
        $newCoupon->set_excluded_product_ids($templateCoupon->get_excluded_product_ids());
        $newCoupon->set_usage_limit($templateCoupon->get_usage_limit());
        $newCoupon->set_usage_limit_per_user($templateCoupon->get_usage_limit_per_user());
        $newCoupon->set_limit_usage_to_x_items($templateCoupon->get_limit_usage_to_x_items());
        $newCoupon->set_free_shipping($templateCoupon->get_free_shipping());
        $newCoupon->set_exclude_sale_items($templateCoupon->get_exclude_sale_items());
        $newCoupon->set_product_categories($templateCoupon->get_product_categories());
        $newCoupon->set_excluded_product_categories($templateCoupon->get_excluded_product_categories());
        $newCoupon->set_minimum_amount($templateCoupon->get_minimum_amount());
        $newCoupon->set_maximum_amount($templateCoupon->get_maximum_amount());
        $newCoupon->set_date_expires($templateCoupon->get_date_expires());

        if ($expires) {
            $newCoupon->set_date_expires((int)strtotime("+{$expires} day"));
        }

        if ($usageLimit) {
            $newCoupon->set_usage_limit($usageLimit);
        }

        if (strlen($allowed) > 0) {
            $arrAllowed = explode(',', $allowed);
            foreach ($arrAllowed as &$part) {
                if (!$resolver->isParameter($part)) {
                    continue;
                }
                $part = $resolver->resolveField("{{ $part }}");
            }
            $newCoupon->set_email_restrictions($arrAllowed);
        }

        $newCoupon->save();
        $newCoupon = new WC_Coupon($code);
        if ($id = $newCoupon->get_id()) {
            update_post_meta($id, '_wa_generated', 1);
        }

        return $code;
    }

    /**
     * Return a unique coupon code
     *
     * @param string $prefix
     * @param int    $length
     *
     * @return string
     */
    private function generateCode($prefix, $length)
    {
        $available    = 'ABCDEFGHIJKLMNOPQRSTUVXYZ123456789';
        $key          = '';
        $availableLen = strlen($available);

        for ($i = 0; $i < $length; $i++) {
            $key .= substr($available, wp_rand(0, $availableLen - 1), 1);
        }

        $key = $prefix . $key;

        return $key;
    }

    /**
     * @param array<int, FieldDescriptor> $editorFields
     *
     * @return array<int, FieldDescriptor>
     */
    public function editorFields($editorFields)
    {
        $newFields = [
            new FieldDescriptor(
                [
                    'label'       => __('Template', 'wunderauto'),
                    'description' => '',
                    'type'        => 'dynamic-select2',
                    'options'     => 'item in $root.shared.shopCoupons',
                    'model'       => 'couponTemplate',
                    'variable'    => 'template',
                    'condition'   => "parameters[editor.phpClass].usesCouponFields",
                    'prio'        => 10,
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Expires after', 'wunderauto'),
                    'description' => __(
                        'Days until coupon expires (leave blank to use value from template coupon)',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'expiresAfter',
                    'variable'    => 'expires',
                    'condition'   => "parameters[editor.phpClass].usesCouponFields",
                    'prio'        => 11,
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Prefix', 'wunderauto'),
                    'description' => __(
                        'Prefixes the coupon code. Makes it easier to identify generated coupons.  (leave blank to ' .
                        'use prefix = "wa-")',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'prefix',
                    'variable'    => 'prefix',
                    'condition'   => "parameters[editor.phpClass].usesCouponFields",
                    'prio'        => 12,
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Usage limit', 'wunderauto'),
                    'description' => __(
                        'Nr of times the coupon can be used (leave blank to use value from template coupon)',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'limit',
                    'variable'    => 'limit',
                    'condition'   => "parameters[editor.phpClass].usesCouponFields",
                    'prio'        => 13,
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Allowed emails', 'wunderauto'),
                    'description' => __(
                        'Whitelist of allowed emails. Use asterisk (*) to match part of an address i.e *@example.com ' .
                        'To use a parameter, type parameter name without curly brackets, i.e billing.email or user.' .
                        'email ',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'allowed',
                    'variable'    => 'allowed',
                    'condition'   => "parameters[editor.phpClass].usesCouponFields",
                    'prio'        => 14,
                ]
            ),
        ];

        return array_merge($editorFields, $newFields);
    }
}
