<?php

namespace ShopWP\API\Admin;

if (!defined('ABSPATH')) {
    exit();
}

// Convenience wrappers for consuming the Storefront API
class Variants
{
    public function __construct($GraphQL, $Admin_Variant_Queries)
    {
        $this->GraphQL = $GraphQL;
        $this->Admin_Variant_Queries = $Admin_Variant_Queries;
    }

}