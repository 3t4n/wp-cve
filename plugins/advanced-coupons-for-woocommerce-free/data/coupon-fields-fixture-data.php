<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$general            = include __DIR__ . '/coupon-fields/general.php';
$usage_restrictions = include __DIR__ . '/coupon-fields/usage-restrictions.php';
$usage_limits       = include __DIR__ . '/coupon-fields/usage-limits.php';
$role_restrictions  = include __DIR__ . '/coupon-fields/role-restrictions.php';
$scheduler          = include __DIR__ . '/coupon-fields/scheduler.php';
$url_coupons        = include __DIR__ . '/coupon-fields/url-coupons.php';
$one_click_apply    = include __DIR__ . '/coupon-fields/one-click-apply.php';
$auto_apply         = include __DIR__ . '/coupon-fields/auto-apply.php';

return array_merge(
    $general,
    $usage_restrictions,
    $usage_limits,
    $role_restrictions,
    $scheduler,
    $url_coupons,
    $one_click_apply,
    $auto_apply
);
