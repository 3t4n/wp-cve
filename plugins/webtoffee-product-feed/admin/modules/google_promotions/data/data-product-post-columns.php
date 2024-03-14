<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
    'promotion_id' => 'Promotion Id[promotion_id]',
    'product_applicability' => 'Product applicability[product_applicability]',
    'offer_type' => 'Offer type[offer_type]',
    'long_title' => 'Long title[long_title]',
    'promotion_effective_dates' => 'Promotion Effective Date[promotion_effective_dates]',
    'promotion_destination' => 'Promotion destination[promotion_destination]',
    'redemption_channel' => 'Redemption channel[redemption_channel]',
    'item_group_id' => 'Item Group Id[item_group_id]',
);

return apply_filters('wt_pf_glpi_product_post_columns', $post_columns);

