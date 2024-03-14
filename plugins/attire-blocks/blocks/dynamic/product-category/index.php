<?php

namespace Attire\Blocks\blocks\product_categories;


use Attire\Blocks\Util;

add_action('plugins_loaded', __NAMESPACE__ . '\atbs_register_product_categories');
/**
 * Register the product_categories block.
 *
 * @return void
 * @since 1.0.0
 *
 */
function atbs_register_product_categories()
{

    // Only load if Gutenberg is available.
    if (!function_exists('register_block_type')) {
        return;
    }
    $attributes = [
        'blockId' => [
            'type' => 'string',
            'default' => ''
        ],
        'categories' => [
            'type' => 'array',
            'default' => [],
            'items' => [
                'type' => 'object'
            ]
        ],
        'catsPerRow' => [
            'type' => 'number',
            'default' => 4
        ],
        'itemHeight' => [
            'type' => 'number',
            'default' => 150
        ],
        'itemHeightUnit' => [
            'type' => 'string',
            'default' => 'px'
        ],
        'titleVisibilityHover' => [
            'type' => 'boolean',
            'default' => false
        ],
        'useIcon' => [
            'type' => 'boolean',
            'default' => true
        ],
        'iconPos' => [
            'type' => 'string',
            'default' => 'top'
        ],
        'showCategoryImage' => [
            'type' => 'boolean',
            'default' => true
        ],
        "hasCustomCSS" => [
            "type" => "boolean",
            "default" => false
        ],
        "customCSS" => [
            "type" => "string",
            "default" => ""
        ],
        "className" => [
            "type" => "string",
            "default" => ""
        ],
    ];
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('', array('ColorLeft' => '#3373DC', 'ColorRight' => '#3373DC', 'Alpha' => '75')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('', array('FontSize' => 16, 'FontWeight' => 400, 'TextAlign' => 'center')));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('', array('BorderWidth' => 1, 'BorderRadius' => 3)));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('text'));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('card'));

    // Hook server side rendering into render callback
    register_block_type('attire-blocks/product-categories',
        array(
            'render_callback' => __NAMESPACE__ . '\atbs_render_product_categories',
            'attributes' => $attributes,
        )
    );
}

/**
 * Server rendering for product_categories
 */
function atbs_render_product_categories($attributes, $content)
{
    $classNames = isset($attributes['className']) ? esc_attr($attributes['className']) : '';
    $height = $attributes['itemHeightUnit'] === 'auto' ? 'auto' : ($attributes['itemHeight'] . $attributes['itemHeightUnit']);
    $titleOpacity = $attributes['titleVisibilityHover'] ? 0 : 1;
    $bg = !$attributes['showCategoryImage'] ? $attributes['BgOverlay'] : 'none';
    $html = '<div class="attire-blocks atbs-product-cats ' . $classNames . '"><style>
                 #atbs-product-cats-' . $attributes['blockId'] . ' .card{
                    background:' . $bg . ';
                    height:' . $height . ';
                    ' . Util::get_border_css($attributes) . '
                    ' . Util::getSpacingStyles($attributes, 'card') . '
                 }    
                 #atbs-product-cats-' . $attributes['blockId'] . ' p{
                    ' . Util::typographyCss($attributes) . '
                    ' . Util::getSpacingStyles($attributes, 'card') . '
                 }  
                 #atbs-product-cats-' . $attributes['blockId'] . ' .card .title{
                    opacity:' . $titleOpacity . ';
                 }
                 #atbs-product-cats-' . $attributes['blockId'] . ' .card:hover .title{
                    opacity:1;
                 }         
             </style>';
    $html .= '<div class="row" id="atbs-product-cats-' . $attributes['blockId'] . '">';
    $isRow = $attributes['iconPos'] === 'right' || $attributes['iconPos'] === 'left' ? 'row' : '';
    foreach ($attributes['categories'] as $key => $value) {
        $thumbnail_id = get_term_meta($value['id'], 'thumbnail_id', true);
        $image = wp_get_attachment_url($thumbnail_id);

        $link = "'" . explode(":", get_term_link($value['slug'], 'product_cat'))[1] . "'";
        $imgSrc = $image ?: ATTIRE_BLOCKS_DIR_URL . '/assets/static/images/placeholder.svg';

        $html .= '<div class="col">';
        $html .= '<div onclick="location.href=' . $link . '" class="card"">';
        if ($attributes['showCategoryImage']) $html .= '<img src="' . $imgSrc . '" class="card-img-top" alt="cat-' . $value['id'] . '-thumb" style="object-fit:cover;height:' . $height . ';">';
        $html .= '<div class="title ' . $isRow . '">';
        if ($attributes['useIcon'] && ($attributes['iconPos'] === 'top' || $attributes['iconPos'] === 'left')) $html .= $content;
        $html .= '<p>' . $value['name'] . '</p>';
        if ($attributes['useIcon'] && ($attributes['iconPos'] === 'bottom' || $attributes['iconPos'] === 'right')) $html .= $content;
        $html .= ' </div>'; //End .title
        $html .= ' </div>'; //End .card
        $html .= ' </div>'; //End .col

        if ((($key + 1) % $attributes['catsPerRow']) === 0) {
            $html .= '<div class="w-100"></div>';
        }
    }
    $html .= '</div>'; //End row
    $html .= '</div>'; //End wrapper

    return $html;
}