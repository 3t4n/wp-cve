<?php

namespace WcMipConnector\Enum;

defined('ABSPATH') || exit;

class WooCommerceErrorCodes
{
    public const INVALID_REMOTE_URL = 'woocommerce_rest_invalid_remote_image_url';
    public const UPLOAD_PRODUCT_IMAGE_ERROR = 'woocommerce_product_image_upload_error';
    public const TERM_EXISTS = 'term_exists';
    public const CANNOT_CREATE = 'woocommerce_rest_cannot_create';
    public const DUPLICATE_TERM_SLUG = 'duplicate_term_slug';
    public const PRODUCT_INVALID_SKU = 'product_invalid_sku';
    public const PRODUCT_INVALID_ID = 'woocommerce_rest_product_invalid_id';
    public const INVALID_PRODUCT_ID = 'woocommerce_rest_invalid_product_id';
    public const INVALID_VARIATION_ID = 'woocommerce_rest_product_variation_invalid_id';
}