<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
                
			'id'                           => 'Product Id[id]',
            'title'                        => 'Product Title[title]',
            'description'                  => 'Product Description[description]',
            'link'                         => 'Product URL[link]',
            'mobile_link'                  => 'Product URL[mobile_link]',
            'product_type'                 => 'Product Categories[product_type] ',
            'google_product_category'             => 'Google Product Category[google_product_category]',
            'image_link'                   => 'Main Image[image_link]',
            //'additional_image_link'        => 'Additional Images [additional_image_link]',
            'wtimages_1'                     => 'Additional Image 1 [additional_image_link]',
            'wtimages_2'                     => 'Additional Image 2 [additional_image_link]',
            'wtimages_3'                     => 'Additional Image 3 [additional_image_link]',
            'wtimages_4'                     => 'Additional Image 4 [additional_image_link]',
            'wtimages_5'                     => 'Additional Image 5 [additional_image_link]',
            'wtimages_6'                     => 'Additional Image 6 [additional_image_link]',
            'wtimages_7'                     => 'Additional Image 7 [additional_image_link]',
            'wtimages_8'                     => 'Additional Image 8 [additional_image_link]',
            'wtimages_9'                     => 'Additional Image 9 [additional_image_link]',
            'wtimages_10'                    => 'Additional Image 10 [additional_image_link]',
            'condition'                    => 'Condition[condition]',
);

return apply_filters('wt_pf_product_post_columns',$post_columns);


