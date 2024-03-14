<?php

namespace Attire\Blocks\blocks\post_carousel;

use Attire\Blocks\Util;
use function Attire\Blocks\blocks\post_grid\atbs_excerpt;
use function Attire\Blocks\blocks\post_grid\atbs_pg_atc_css;
use function Attire\Blocks\blocks\post_grid\atbs_pg_category_css;
use function Attire\Blocks\blocks\post_grid\imageFullTemplate;
use function Attire\Blocks\blocks\post_grid\imageLeftTemplate;
use function Attire\Blocks\blocks\post_grid\imageTopTemplate;
use function Attire\Blocks\blocks\post_grid\categoryHtml;
use function Attire\Blocks\blocks\post_grid\excerptHtml;
use function Attire\Blocks\blocks\post_grid\metaHtml;
use function Attire\Blocks\blocks\post_grid\priceAndAddToCartHtml;
use function Attire\Blocks\blocks\post_grid\titleHtml;

add_action('plugins_loaded', __NAMESPACE__ . '\atbs_register_post_carousel');
/**
 * Register the post_carousel block.
 *
 * @return void
 * @since 1.0.0
 *
 */
function atbs_register_post_carousel()
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
        'postType' => [
            'type' => 'string',
            'default' => 'post'
        ],
        'categories' => [
            'type' => 'array',
            'default' => [],
        ],
        'excludeCategories' => [
            'type' => 'array',
            'default' => [],
        ],
        'tags' => [
            'type' => 'array',
            'default' => [],
        ],
        'excludeTags' => [
            'type' => 'array',
            'default' => [],
        ],
        'authors' => [
            'type' => 'array',
            'default' => [],
        ],
        'excludeAuthors' => [
            'type' => 'array',
            'default' => [],
        ],
        'posts' => [
            'type' => 'array',
            'default' => [],
        ],
        'excludePosts' => [
            'type' => 'array',
            'default' => [],
        ],
        'sortBy' => [
            'type' => 'string',
            'default' => 'modified,asc',
        ],
        'indicatorColor' => [
            'type' => 'string',
            'default' => 'grey',
        ],
        'numberSlide' => [
            'type' => 'number',
            'default' => 3
        ],
        'postsPerSlide' => [
            'type' => 'number',
            'default' => 3
        ],
        "wideContent" => [
            "type" => "boolean",
            "default" => true
        ],
        "date" => [
            "type" => "boolean",
            "default" => false
        ],
        "postTemplate" => [
            "type" => "string",
            "default" => "top"
        ],
        "featuredImage" => [
            "type" => "boolean",
            "default" => true
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
        "excerptLength" => [
            "type" => "number",
            "default" => 25
        ],
        "contentPosition" => [
            "type" => "string",
            "default" => "flex-end"
        ],
        "thumbnailWidth" => [
            "type" => 'number',
            "default" => 100
        ],
        "thumbnailWidthUnit" => [
            "type" => 'string',
            "default" => '%'
        ],
        "thumbnailHeight" => [
            "type" => 'number',
            "default" => 200
        ],
        "thumbnailHeightUnit" => [
            "type" => 'string',
            "default" => 'px'
        ],
        "thumbnailBGPosition" => [
            "type" => 'string',
            "default" => 'center center'
        ],
        "showCategory" => [
            "type" => 'boolean',
            "default" => true
        ],
        "showExcerpt" => [
            "type" => 'boolean',
            "default" => true
        ],
        "showMeta" => [
            "type" => 'boolean',
            "default" => true
        ],
        "showCommentMeta" => [
            "type" => 'boolean',
            "default" => false
        ],
        "showPostTitle" => [
            "type" => 'boolean',
            "default" => true
        ],
        "showProductPrice" => [
            "type" => 'boolean',
            "default" => true
        ],
        "showProductATCButton" => [
            "type" => 'boolean',
            "default" => true
        ],
        "indicator" => [
            "type" => 'array',
            "default" => ['fas fa-angle-left', 'fas fa-angle-right']
        ],
        "pagination" => [
            "type" => 'string',
            "default" => 'far fa-dot-circle'
        ],
        "prevNextSize" => [
            "type" => 'string',
            "default" => '1em'
        ],
        "slideIndicatorSize" => [
            "type" => 'string',
            "default" => '1em'
        ],
        "atcButtonAlign" => [
            "type" => "string",
            "default" => 'center'
        ],
        "contentTextAlign" => [
            "type" => 'string',
            "default" => 'left'
        ],
        "metaIconColor" => [
            "type" => 'string',
            "default" => '#00C16E'
        ],
        "slideInterval" => [
            "type" => 'number',
            "default" => 3
        ]
    ];

    $attributes = array_merge_recursive($attributes, Util::getBgAttributes());
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('content'));
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('prevNext'));
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('category'));
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('atcButton', array('ColorLeft' => '#3373DC', 'ColorRight' => '#3373DC', 'Alpha' => '75')));

    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('title', array('FontSize' => 20, 'FontWeight' => 700, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#1A264A')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('excerpt', array('FontSize' => 14, 'FontWeight' => 400, 'LineHeight' => 1.6, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#6F7982')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('category', array('FontSize' => 12, 'FontWeight' => 700, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'uppercase', 'TextColor' => '#00c16e')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('atcButton', array('FontSize' => 14, 'FontWeight' => 400, 'LineHeight' => 1.3, 'TextAlign' => 'center', 'TextTransform' => 'uppercase', 'TextColor' => '#ffffff')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('price', array('FontSize' => 15, 'FontWeight' => 400, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#00C16E')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('meta', array('FontSize' => 13, 'FontWeight' => 400, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#7D89AD')));

    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('', array('BorderWidth' => 1, 'BorderColor' => '#eee')));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('content'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('thumbnail'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('prevNext'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('category'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('atcButton', array('BorderRadius' => 4)));

    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('post', ['Margin' => array(0, 15, 0, 15), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('thumbnail', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('content', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(30, 30, 30, 30)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('atcButton', ['Margin' => array(18, 0, 0, 0), 'Padding' => array(10, 22, 10, 22)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('price', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(15, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('category', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 10, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('title', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('meta', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(10, 0, 15, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('excerpt', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('prevNext', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('slideIndicator', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 3, 0, 3)]));

    // Hook server side rendering into render callback
    register_block_type('attire-blocks/post-carousel', [
        'render_callback' => __NAMESPACE__ . '\atbs_render_post_carousel',
        'attributes' => $attributes,
    ]);

}

/**
 * Server rendering for post_carousel
 */
function atbs_render_post_carousel($attributes, $content)
{
    $sortBy = explode(',', $attributes['sortBy']);
    $post_type = $attributes['postType'];
    $posts = [];
    $excludePosts = [];
    $categories = [];
    $excludeCategories = [];
    $tags = [];
    $excludeTags = [];
    $authors = [];
    $excludeAuthors = [];
    $numPosts = $attributes['postsPerSlide'] * $attributes['numberSlide'];


    if ($attributes['posts'] && sizeof($attributes['posts']) !== 0) {
        foreach ($attributes['posts'] as $post) {
            array_push($posts, $post['value']);
        }
    }
    if ($attributes['excludePosts'] && sizeof($attributes['excludePosts']) !== 0) {
        foreach ($attributes['excludePosts'] as $post) {
            array_push($excludePosts, $post['value']);
        }
    }
    if ($attributes['categories'] && sizeof($attributes['categories']) !== 0) {
        foreach ($attributes['categories'] as $category) {
            array_push($categories, $category['value']);
        }
    }
    if ($attributes['excludeCategories'] && sizeof($attributes['excludeCategories']) !== 0) {
        foreach ($attributes['excludeCategories'] as $category) {
            array_push($excludeCategories, $category['value']);
        }
    }
    if ($attributes['tags'] && sizeof($attributes['tags']) !== 0) {
        foreach ($attributes['tags'] as $tag) {
            array_push($tags, $tag['value']);
        }
    }
    if ($attributes['excludeTags'] && sizeof($attributes['excludeTags']) !== 0) {
        foreach ($attributes['excludeTags'] as $tag) {
            array_push($excludeTags, $tag['value']);
        }
    }
    if ($attributes['authors'] && sizeof($attributes['authors']) !== 0) {
        foreach ($attributes['authors'] as $author) {
            array_push($authors, $author['value']);
        }
    }
    if ($attributes['excludeAuthors'] && sizeof($attributes['excludeAuthors']) !== 0) {
        foreach ($attributes['excludeAuthors'] as $author) {
            array_push($excludeAuthors, $author['value']);
        }
    }
    $args = array(
        'post_status' => 'publish',
        'post__in' => $posts,
        'post__not_in' => $excludePosts,
        'category__in' => $categories,
        'category__not_in' => $excludeCategories,
        'tag__in' => $tags,
        'tag__not_in' => $excludeTags,
        'author__in' => $authors,
        'author__not_in' => $excludeAuthors,
        'orderby' => $sortBy[0],
        'order' => $sortBy[1],
        'post_type' => $attributes['postType'],
        'ignore_sticky_posts' => true,
        'posts_per_page' => $numPosts,
        'page' => 1
    );

    $posts = new \WP_Query($args);

    if (empty($posts)) {
        return '<p>' . __('No posts', 'attire-blocks') . '</p>';
    }

    $posts = array_map(function ($post) use ($attributes) {
        $post->post_thumbnail = get_the_post_thumbnail_url($post->ID, [$attributes['thumbnailHeight'], $attributes['thumbnailWidth']]);
        $post->post_categories = get_the_category($post->ID);
        $post->post_url = get_the_permalink($post->ID);
        $post->post_author_url = esc_url(get_author_posts_url($post->post_author));

        $post->post_modified_date = get_the_modified_date('', $post->ID);;
        $post->post_comment_count = get_comments_number($post->ID);
        $post->post_excerpt = $post->post_excerpt !== "" ? $post->post_excerpt : atbs_excerpt($attributes['excerptLength'], $post);
        if ($attributes['postType'] === 'product') {
            $product = wc_get_product($post->ID);
            $post->sku = $product->get_sku();
            $post->product_id = $product->get_id();
            $post->product_name = $product->get_name();
            $post->price_html = $product->get_price_html();
        }

        return $post;
    }, $posts->posts);;


    $blockId = isset($attributes["blockId"]) ? $attributes["blockId"] : uniqid();

    $prevPadding = $attributes['prevNextPadding'][0] . 'px ' . $attributes['prevNextPadding'][1] . 'px ' . $attributes['prevNextPadding'][2] . 'px ' . $attributes['prevNextPadding'][3] . 'px';
    $prevMargin = $attributes['prevNextMargin'][0] . 'px ' . $attributes['prevNextMargin'][1] . 'px ' . $attributes['prevNextMargin'][2] . 'px ' . $attributes['prevNextMargin'][3] . 'px';
    $nextPadding = $attributes['prevNextPadding'][0] . 'px ' . $attributes['prevNextPadding'][3] . 'px ' . $attributes['prevNextPadding'][2] . 'px ' . $attributes['prevNextPadding'][1] . 'px';
    $nextMargin = $attributes['prevNextMargin'][0] . 'px ' . $attributes['prevNextMargin'][3] . 'px ' . $attributes['prevNextMargin'][2] . 'px ' . $attributes['prevNextMargin'][1] . 'px';

    $containerClass = isset($attributes['wideContent']) ? 'container-fluid' : 'container';
    $indicatorColor = isset($attributes['indicatorColor']) ? $attributes['indicatorColor'] : '#fff';
    $columnSize = isset($attributes['postsPerSlide']) ? (12 / $attributes['postsPerSlide']) : 6;

    $thumbHeight = $attributes['thumbnailHeight'] . $attributes['thumbnailHeightUnit'];
    $thumbWidth = $attributes['thumbnailWidth'] . $attributes['thumbnailWidthUnit'];
    $thumbnailBGPosition = $attributes['thumbnailBGPosition'];

    $classNames = isset($attributes['className']) ? esc_attr($attributes['className']) : '';

    $carousel = '<div class="' . $classNames . ' ' . $containerClass . ' attire-blocks atbs-post-carousel-wrapper "><style id="atbs-post-grid-css-' . $blockId . '">
                #atbs-post-grid-' . $blockId . ' .atbs-post-grid-img-full .atbs-post-grid-body{
                    right:0;
                    left:0;
                }
                #atbs-post-grid-' . $blockId . ' .atbs-post-grid-body{
                    ' . Util::getSpacingStyles($attributes, 'content') . '
                    background:' . $attributes['contentBgOverlay'] . ';
                    ' . Util::get_border_css($attributes, 'content') . '
                }
                #atbs-post-grid-' . $blockId . ' .atbs-post-grid-thumbnail{
                    ' . Util::getSpacingStyles($attributes, 'thumbnail') . '
                }
                #atbs-post-grid-' . $blockId . ' .atbs-post-grid-item{
                    ' . Util::getSpacingStyles($attributes, 'post') . '
                    overflow:hidden;
                    background: ' . $attributes['BgOverlay'] . ';
                    ' . Util::get_border_css($attributes, '') . '
                }
                    /* thumb css */
                #atbs-post-grid-' . $blockId . ' .atbs-post-thumb{
                    ' . Util::get_border_css($attributes, 'thumbnail') . '
                    background-position:' . $thumbnailBGPosition . ';
                    height:' . $thumbHeight . ';
                    width:' . $thumbWidth . ';
                    background-repeat: no-repeat;
                    background-size: cover;
                }
                /* excerpt css */
                #atbs-post-grid-' . $blockId . ' .post-content{
                    ' . Util::typographyCss($attributes, 'excerpt') . ' 
                    ' . Util::getSpacingStyles($attributes, 'excerpt') . '
                }
                /* title css */
                #atbs-post-grid-' . $blockId . ' .atbs-post-grid-title a{
                    display:block;
                    ' . Util::typographyCss($attributes, 'title') . '
                    ' . Util::getSpacingStyles($attributes, 'title') . '
                }
                /* category css */
                #atbs-post-grid-' . $blockId . ' .atbs-post-cat{
                    ' . Util::typographyCss($attributes, 'category') . '
                }
                #atbs-post-grid-' . $blockId . ' .atbs-post-cat span{
                    display:inline-block;
                    background:' . $attributes['categoryBgOverlay'] . ';
                    ' . atbs_pg_category_css($attributes) . '
                }
                /* price & add to cart css */
                #atbs-post-grid-' . $blockId . ' .price{
                    ' . Util::typographyCss($attributes, 'price') . '
                    ' . atbs_pg_category_css($attributes, 'price') . '
                }
                #atbs-post-grid-' . $blockId . ' .atcWrapper a{
                    ' . atbs_pg_atc_css($attributes) . '
                    ' . Util::typographyCss($attributes, 'atcButton') . '
                    display: inline-block;
                }
             </style>';

    // start: Main carousel element wrapper
    $carousel .= '<div id="atbs-post-grid-' . $blockId . '" class="carousel slide" data-ride="carousel" data-interval="' . ($attributes['slideInterval'] * 1000) . '">';
    //    start : .carousel-indicators
    $carousel_indicator = '<ol class="carousel-indicators">';
    //    start : .carousel-inner
    $carousel_inner = '<div class="carousel-inner" role="listbox">';

    foreach ($posts as $idx => $post) {
        $post_id = $post->ID;
        $activeClass = $idx === 0 ? "active " : " ";


        if ($idx % $attributes['postsPerSlide'] === 0) {
            $carousel_inner .= '<div class="row no-gutters carousel-item ' . $activeClass . '">';
            $carousel_indicator .= '<li style="' . Util::getSpacingStyles($attributes, 'slideIndicator') . '" class="' . $activeClass . '" data-target="#atbs-post-grid-' . $blockId . '"
                                       data-slide-to="' . ($idx / $attributes['postsPerSlide']) . '">
                                        <i style="color:' . $indicatorColor . '; 
                                        font-size : ' . $attributes['slideIndicatorSize'] . ';" 
                                        class="' . $attributes['pagination'] . '"></i></li>';

        }
        if (isset($attributes['featuredImage']) && $attributes['featuredImage']) {
            if ($attributes['postTemplate'] === 'top') {
                $carousel_inner .= imageTopTemplate($post, $attributes);
            } elseif ($attributes['postTemplate'] === 'left') {
                $carousel_inner .= imageLeftTemplate($post, $attributes);
            } elseif ($attributes['postTemplate'] === 'full') {
                $carousel_inner .= imageFullTemplate($post, $attributes);
            }
        } else {
            $borderCss = Util::get_border_css($attributes);
            $carousel_inner .= '<div class="col atbs-post-grid-item" style="' . $borderCss . '"><div class="atbs-post-grid-body" style="text-align:' . $attributes['contentTextAlign'] . ';">
                                    ' . categoryHtml($post, $attributes) . '
                                    ' . titleHtml($post, $attributes) . '
                                    ' . ($post_type !== 'product' ? metaHtml($post, $attributes) : '') . '
                                    ' . ($post_type !== 'product' ? excerptHtml($post, $attributes) : '') . '
                                    ' . ($post_type === 'product' ? priceAndAddToCartHtml($post, $attributes) : '') . '    
                                </div></div>';

        }
        if (($idx + 1) % $attributes['postsPerSlide'] === 0) {
            $carousel_inner .= '</div>';
        }
    }
    //    end: .carousel-indicators
    $carousel_indicator .= '</ol>';
    //    end : .carousel-inner
    $carousel_inner .= '</div>';
    $carousel_nav = '';
    if ($attributes['indicator'][0] !== 'none')
        $carousel_nav = '<a class="carousel-control-prev" href="#atbs-post-grid-' . $blockId . '" role="button"
		                           data-slide="prev">
		                            <span class="carousel-control-prev-icon w-100" aria-hidden="true">
		                                <i style="color:' . $indicatorColor . '; 
		                                background:' . $attributes['prevNextBgOverlay'] . ';
                                        ' . Util::get_border_css($attributes, 'prevNext') . '
                                        padding:' . $prevPadding . '; margin:' . $prevMargin . ';
		                                font-size:' . $attributes['prevNextSize'] . '" 
		                                class="' . $attributes['indicator'][0] . '"></i>
                                    </span>
		                            <span class="sr-only">' . __('Previous', 'attire-blocks') . '</span>
		                        </a>
		                        <a class="carousel-control-next" href="#atbs-post-grid-' . $blockId . '" role="button"
		                           data-slide="next">
		                            <span class="carousel-control-next-icon w-100" aria-hidden="true">
		                                <i style="color:' . $indicatorColor . '; 
		                                background:' . $attributes['prevNextBgOverlay'] . ';
                                        ' . Util::get_border_css($attributes, 'prevNext') . '
                                        padding:' . $nextPadding . '; margin:' . $nextMargin . ';
                                        font-size:' . $attributes['prevNextSize'] . '" 
		                                class="' . $attributes['indicator'][1] . '"></i>
                                    </span>
		                            <span class="sr-only">' . __('Next', 'attire-blocks') . '</span>
		                        </a>';
    $carousel .= $carousel_indicator . $carousel_inner . $carousel_nav;
    // end: Main carousel element wrapper
    $carousel .= '</div></div>';
    return $carousel;
}