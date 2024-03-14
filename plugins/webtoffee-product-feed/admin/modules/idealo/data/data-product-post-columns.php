<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
                
            'sku'                          => 'Product SKU[sku]',
            'title'                        => 'Product Title[title]',
            'description'                  => 'Product Description[description]',
            'url'                          => 'Product URL[url]',
            'categoryPath'                 => 'Product Categories[categoryPath] ',            
            'imageUrls'                    => 'Image URLs[imageUrls]',
);

return apply_filters('wt_pf_product_post_columns',$post_columns);


