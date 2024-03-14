<?php namespace BDroppy\Services\WooCommerce;


class Resource
{

    public function __construct()
    {

    }


    public function getImages()
    {
        return [
            [
                'id'      => '0',
                'name'    => 'No Picture',
            ],
            [
                'id'      => '1',
                'name'    => '1 Picture',
            ],
            [
                'id'      => '2',
                'name'    => '2 Picture',
            ],
            [
                'id'      => '3',
                'name'    => '3 Picture',
            ],
            [
                'id'      => '4',
                'name'    => '4 Picture',
            ],
            [
                'id'      => 'all',
                'name'    => 'All Picture',
            ],
        ];
    }

    public function GetProductSkuTypes()
    {
        return [
            [
                'id'=> 0,
                'name'=> 'Barcode',
            ],
            [
                'id'=> 1,
                'name'=> 'Product name + size',
            ]
        ] ;
    }


    public function getAttributes() {
        $taxonomies = wc_get_attribute_taxonomies();
        $attributes = [
            [
                'id'   => 0,
                'name' => 'No Attribute'
            ]
        ];
        foreach ( $taxonomies as $taxonomy ) {
            $attributes[] = [
                'id'   => (int) $taxonomy->attribute_id,
                'name' => $taxonomy->attribute_label
            ];
        }

        return $attributes;
    }

    public function getCategoryStructure() {
        return [
            [
                'id'   => 0,
                'name' => __( 'Category > Subcategory', 'brands' ),
            ],
            [
                'id'   => 1,
                'name' => __( 'Gender > Category > Subcategory', 'brands' ),
            ],
            [
                'id'   => 2,
                'name' => __( 'Category > Subcategory + Gender > Category > Subcategory', 'brands' ),
            ],
        ];
    }
}