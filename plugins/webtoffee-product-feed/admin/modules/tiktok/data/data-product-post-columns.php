<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
                
	    'sku_id'                       => 'Product Id[sku_id]',
            'title'                        => 'Product Title[title]',
            'description'                  => 'Product Description[description]',
            'availability'                 => 'Availability[availability]',     
            'link'                         => 'Product URL[link]',
            'mobile_link'                  => 'Product URL[mobile_link]',
            'product_type'                 => 'Product Categories[product_type] ',
            'google_product_category'      => 'Google Product Category[google_product_category]',
            'image_link'                   => 'Main Image[image_link]',
            'additional_image_link'        => 'Additional Images [additional_image_link]',
            'condition'                    => 'Condition[condition]',
);

return apply_filters('wt_pf_product_post_columns',$post_columns);


