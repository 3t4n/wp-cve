<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
    'ProductId' => 'Product Id[ProductId]',
    'ProductName' => 'Product Title[ProductName]',
    'Description' => 'Product Description[Description]',
    'Url' => 'Product URL[Url]',
    'Category' => 'Product Categories[Category] ',
    'ImageUrl' => 'Main Image[ImageUrl]',
    'Condition' => 'Condition[condition]',
    'Price' => 'Price[Price]',
    'ShippingCost' => 'ShippingCost[ShippingCost]',
    'StockStatus' => 'StockStatus[StockStatus]',
    'LeadTime' => 'LeadTime[LeadTime]',
    'Brand' => 'Brand[Brand]',
    'Msku' => 'Msku[Msku]',
    'Ean' => 'Ean[Ean]',
    'AdultContent' => 'AdultContent[AdultContent]',
    'AgeGroup' => 'AgeGroup[AgeGroup]',
    'Bundled' => 'Bundled[Bundled]',
    'Multipack' => 'Multipack[Multipack]',
    'Pattern' => 'Pattern[Pattern]',
    'Size' => 'Size[Size]',
    'SizeSystem' => 'SizeSystem[SizeSystem]',
    'Color' => 'Color[Color]',
    'EnergyEfficiencyClass' => 'EnergyEfficiencyClass[EnergyEfficiencyClass]',
    'Gender' => 'Gender[Gender]',
    'Material' => 'Material[Material]',
    'GroupId' => 'GroupId[GroupId]',
);

return apply_filters('wt_pf_product_post_columns', $post_columns);

