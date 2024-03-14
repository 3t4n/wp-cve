<?php

namespace Attire\Blocks\blocks\post_grid;


use Attire\Blocks\Util;

add_action('plugins_loaded', __NAMESPACE__ . '\atbs_register_post_grid');
/**
 * Register the post_grid block.
 *
 * @return void
 * @since 1.0.0
 *
 */
function atbs_register_post_grid()
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
        'leftRightNoMargin' => [
            'type' => 'boolean',
            'default' => true
        ],
        'postsPerRow' => [
            'type' => 'number',
            'default' => 3
        ],
        'rows' => [
            'type' => 'number',
            'default' => 1
        ],
        'sortBy' => [
            'type' => 'string',
            'default' => 'modified,desc'
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
            "default" => 18
        ],
        "postTemplate" => [
            "type" => "string",
            "default" => "top"
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
        "atcButtonAlign" => [
            "type" => "string",
            "default" => 'center'
        ],
        "metaIconColor" => [
            "type" => 'string',
            "default" => '#00c16e'
        ]
    ];
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('', array('ColorLeft' => '#ffffff', 'ColorRight' => '#ffffff')));
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('content'));
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('category'));
    $attributes = array_merge_recursive($attributes, Util::getBgAttributes('atcButton', array('ColorLeft' => '#3373DC', 'ColorRight' => '#3373DC', 'Alpha' => '75')));

    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('title', array('FontSize' => 20, 'FontWeight' => 700, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#1A264A')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('excerpt', array('FontSize' => 14, 'FontWeight' => 400, 'LineHeight' => 1.6, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#6F7982')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('atcButton', array('FontSize' => 14, 'FontWeight' => 400, 'LineHeight' => 1.3, 'TextAlign' => 'center', 'TextTransform' => 'uppercase', 'TextColor' => '#ffffff')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('price', array('FontSize' => 15, 'FontWeight' => 400, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#00C16E')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('category', array('FontSize' => 12, 'FontWeight' => 700, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'uppercase', 'TextColor' => '#00c16e')));
    $attributes = array_merge_recursive($attributes, Util::getTypographyProps('meta', array('FontSize' => 13, 'FontWeight' => 400, 'LineHeight' => 1.3, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#7D89AD')));

    $attributes = array_merge_recursive($attributes, Util::getReadMoreAttributes());

    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('', array('BorderWidth' => 1, 'BorderColor' => '#eee')));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('content'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('thumbnail'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('category'));
    $attributes = array_merge_recursive($attributes, Util::getBorderAttributes('atcButton', array('BorderRadius' => 4)));

    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('post', ['Margin' => array(0, 15, 30, 15), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('thumbnail', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('content', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(30, 30, 30, 30)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('atcButton', ['Margin' => array(18, 0, 0, 0), 'Padding' => array(10, 22, 10, 22)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('price', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(15, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('category', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 10, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('title', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('meta', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(10, 0, 15, 0)]));
    $attributes = array_merge_recursive($attributes, Util::getSpacingProps('excerpt', ['Margin' => array(0, 0, 0, 0), 'Padding' => array(0, 0, 0, 0)]));
    // Hook server side rendering into render callback
    register_block_type('attire-blocks/post-grid',
        array(
            'render_callback' => __NAMESPACE__ . '\atbs_render_post_grid',
            'attributes' => $attributes,
        )
    );
}

/**
 * Server rendering for post_grid
 *
 * @param $attributes
 * @param $content
 *
 * @return string
 */

function atbs_render_post_grid($attributes)
{
    $sortBy = explode(',', $attributes['sortBy']);
    $posts = [];
    $excludePosts = [];
    $categories = [];
    $excludeCategories = [];
    $tags = [];
    $excludeTags = [];
    $authors = [];
    $excludeAuthors = [];
    $numPosts = $attributes['postsPerRow'] * $attributes['rows'];

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

    $post_list = new \WP_Query($args);

    if (empty($post_list->posts)) {
        return '<p>' . __('No posts', 'attire-blocks') . '</p>';
    }

    $post_list = array_map(function ($post) use ($attributes) {
        $post->post_thumbnail = get_the_post_thumbnail_url($post->ID, [$attributes['thumbnailHeight'], $attributes['thumbnailWidth']]);
        $post->post_categories = get_the_category($post->ID);
        $post->post_url = get_the_permalink($post->ID);
        $post->post_author_url = esc_url(get_author_posts_url($post->post_author));

        $archive_year = get_the_time('Y');
        $archive_month = get_the_time('m');
        $archive_day = get_the_time('d');

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
    }, $post_list->posts);;

    return atbs_post_html($post_list, $attributes);
}

function atbs_post_html($post_list, $attributes)
{
    $blockId = isset($attributes["blockId"]) ? $attributes["blockId"] : uniqid();

    $thumbHeight = $attributes['thumbnailHeight'] . $attributes['thumbnailHeightUnit'];
    $thumbWidth = $attributes['thumbnailWidth'] . $attributes['thumbnailWidthUnit'];
    $thumbnailBGPosition = $attributes['thumbnailBGPosition'];

    $leftRightColMargin = $attributes['leftRightNoMargin'] ? '#atbs-post-grid-' . $blockId . ' :first-child{
                    margin-left:0;
                }
                #atbs-post-grid-' . $blockId . ' :last-child{
                    margin-right:0;
                }' : '';

    $html = '<div class="atbs-post-grid-container ' . (isset($attributes['className']) ? esc_attr($attributes['className']) : '') . '"><style id="atbs-post-grid-css-' . $blockId . '">
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
                    ' . Util::get_border_css($attributes) . '
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
                ' . $leftRightColMargin . '
             </style>';

    $html .= '<div id="atbs-post-grid-' . $blockId . '">';

    $html .= '<div class="row no-gutters">';
    $numPost = count($post_list);
    $i = 0;
    $loopEnd = false;
    foreach ($post_list as $key => $post) {
        if ($attributes['postTemplate'] === 'top') {
            $html .= imageTopTemplate($post, $attributes);
        } elseif ($attributes['postTemplate'] === 'left') {
            $html .= imageLeftTemplate($post, $attributes);
        } elseif ($attributes['postTemplate'] === 'full') {
            $html .= imageFullTemplate($post, $attributes);
        }
        if (++$i === $numPost) {
            $loopEnd = true;
        }
        if ((($key + 1) % $attributes['postsPerRow'] == 0) && !$loopEnd) {
            $html .= '</div><div class="row no-gutters">';
        }
    }
    $html .= '</div>'; //row

    if ($attributes['readMore']) {
        $html .= atbs_seeMoreHtml($attributes);
    }

    $html .= '</div></div>'; //atbs-post-grid-container and post grid

    return $html;
}

function imageTopTemplate($post, $attributes, $columnSize = '')
{
    $post_type = $attributes['postType'];
    $columnSize = $columnSize !== '' ? $columnSize : 'col-md';
    return '<div class="' . $columnSize . ' atbs-post-grid-item">
                <div class="atbs-post-grid-thumbnail">
                    ' . thumbHtml($post, $attributes) . '
                </div>
                <div class="atbs-post-grid-body">
                    ' . categoryHtml($post, $attributes) . '
                    ' . titleHtml($post, $attributes) . '
                    ' . ($post_type !== 'product' ? metaHtml($post, $attributes) : '') . '
                    ' . ($post_type !== 'product' ? excerptHtml($post, $attributes) : '') . '
                    ' . ($post_type === 'product' ? priceAndAddToCartHtml($post, $attributes) : '') . '
                </div>
            </div>';
}

function imageFullTemplate($post, $attributes, $columnSize = '')
{
    $post_type = $attributes['postType'];
    $columnSize = $columnSize !== '' ? $columnSize : 'col-md';
    if ($attributes['contentPosition'] === 'flex-start') $contentPositionCss = 'top: 0;';
    else if ($attributes['contentPosition'] === 'center') $contentPositionCss = 'top: 50%;transform: translate(0,-50%);';
    else $contentPositionCss = 'bottom: 0';
    return '<div class="' . $columnSize . ' atbs-post-grid-item atbs-post-grid-img-full" style="position: relative;">
                <div class="atbs-post-grid-thumbnail">
                    ' . thumbHtml($post, $attributes) . '
                </div>
                <div class="atbs-post-grid-body" style="position: absolute;' . $contentPositionCss . '">
                    ' . categoryHtml($post, $attributes) . '
                    ' . titleHtml($post, $attributes) . '
                    ' . ($post_type !== 'product' ? metaHtml($post, $attributes) : '') . '
                    ' . ($post_type !== 'product' ? excerptHtml($post, $attributes) : '') . '
                    ' . ($post_type === 'product' ? priceAndAddToCartHtml($post, $attributes) : '') . '
                </div>
            </div>';
}

function imageLeftTemplate($post, $attributes, $columnSize = '', $post_type = 'post')
{
    $columnSize = $columnSize !== '' ? $columnSize : 'col-md';
    return '<div class="' . $columnSize . ' atbs-post-grid-item">
                <div class="row no-gutters" style="align-items: ' . $attributes['contentPosition'] . '">
                    <div class="col-md-4 p-0 atbs-post-grid-thumbnail">
                        ' . thumbHtml($post, $attributes) . '
                    </div>
                    <div class="col-md-8 atbs-post-grid-body">
                        ' . categoryHtml($post, $attributes) . '
                        ' . titleHtml($post, $attributes) . '
                        ' . ($post_type !== 'product' ? metaHtml($post, $attributes) : '') . '
                        ' . ($post_type !== 'product' ? excerptHtml($post, $attributes) : '') . '
                        ' . ($post_type === 'product' ? priceAndAddToCartHtml($post, $attributes) : '') . '
                    </div>
                </div>
            </div>';
}

function metaHtml($post, $attributes)
{
    if (isset($attributes['showMeta']) && !$attributes['showMeta']) return '';

    $date = get_the_modified_date('', $post->ID);
    $author = get_the_author_meta("display_name", $post->post_author);
    $spacing = Util::getSpacingStyles($attributes, 'meta');
    $typography = 'font-size:' . $attributes['metaFontSize'] . $attributes['metaFontSizeUnit'] . ';line-height:' . $attributes['metaLineHeight'] . $attributes['metaLineHeightUnit'] . ';text-transform:' . $attributes['metaTextTransform'] . ';font-weight:' . $attributes['metaFontWeight'] . ';font-style:' . $attributes['metaFontStyle'] . ';text-align:' . $attributes['metaTextAlign'] . ';letter-spacing:' . $attributes['metaLetterSpacing'] . $attributes['metaLetterSpacingUnit'] . ';';
    $html = '<div class="meta-list">
                <div style="' . $spacing . $typography . '">
                    <span>
                        <i class="fas fa-pencil-alt" style="color: ' . $attributes['metaIconColor'] . '"></i>&nbsp;
                        <span style="color:' . $attributes['metaTextColor'] . '">By </span>
                        <a style="color:' . $attributes['metaTextColor'] . '" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . $author . '</a>
                    </span>&nbsp;&nbsp;
                    <span>  
                        <i class="fas fa-calendar-week" style="color: ' . $attributes['metaIconColor'] . '"></i>&nbsp;
                        <a style="color:' . $attributes['metaTextColor'] . '" href="' . $post->post_url. '">' . $date . '</a>
                    </span>';
    if (isset($attributes['showCommentMeta']) && $attributes['showCommentMeta']) {
        $html .= '&nbsp;&nbsp;<span>  
                    <i class="far fa-comments" style="color: ' . $attributes['metaIconColor'] . '"></i>&nbsp; 
                    <span style="color:' . $attributes['metaTextColor'] . '">' . get_comments_number($post->ID) . '</span>
                  </span>';
    }
    $html .= '    </div>			
            </div>';
    return $html;
}

function excerptHtml($post, $attributes)
{

    if (isset($attributes['showExcerpt']) && !$attributes['showExcerpt']) return '';
    return '<div class="post-content">
                ' . $post->post_excerpt . '
            </div>';
}

function titleHtml($post, $attributes)
{
    if (isset($attributes['showPostTitle']) && !$attributes['showPostTitle']) return '';
    return '<h3 class="atbs-post-grid-title post-title m-0"><a href="' . get_the_permalink($post->ID) . '">' . (get_the_title($post->ID) ? get_the_title($post->ID) : "(Untitled)") . '</a></h3>';
}

function categoryHtml($post, $attributes)
{
    if (isset($attributes['showCategory']) && !$attributes['showCategory']) return '';
    $categories = get_the_category($post->ID);
    $category = isset($categories[0]) ? $categories[0]->name : '';
    if ($category !== '')
        return '<h5 class="atbs-post-cat p-0 m-0"><span>' . $category . '</span></h5>';
    else
        return '';
}

function priceAndAddToCartHtml($post, $attributes)
{
    $product = wc_get_product($post->ID);
    $price_html = $product->get_price_html();
    $id = $product->get_id();
    $html = '';
    if ($attributes['showProductPrice']) $html .= '<p class="price">' . $price_html . '</p>';
    if ($attributes['showProductATCButton']) $html .= '<div class="atcWrapper text-' . $attributes['atcButtonAlign'] . '"><a type="button" href="?add-to-cart=' . $id . '" data-quantity="1" class="product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="' . $id . '" data-product_sku="' . $product->get_sku() . '" aria-label="Add “' . $product->get_name() . '” to your cart" rel="nofollow">Add to cart</a></div>';
    return $html;
}

function thumbHtml($post, $attributes)
{
    return '<div onclick="location.href= \''. $post->post_url . '\'" class="atbs-post-thumb" style="background-image:url(' . get_the_post_thumbnail_url($post->ID, [1000, 1000]) . ');"></div>';
}

function atbs_seeMoreHtml($attributes)
{
    $btnColor = isset($attributes['readMoreColor']) ? $attributes['readMoreColor'] : 'btn-primary';
    $label = isset($attributes['readMoreLabel']) ? $attributes['readMoreLabel'] : __('See more...', 'attire-blocks');
    $link = strpos($attributes['readMoreLink'], '//') ? $attributes['readMoreLink'] : '//' . $attributes['readMoreLink'];
    $btnPosition = (isset($attributes['readMoreAlignment']) && $attributes['readMoreAlignment'] == 'bl') ? 'float-left' : 'float-right';
	$readMorePadding = 'p-0';
	if ( !$attributes['leftRightNoMargin'] ) {
		$readMorePadding = '';
	}
    return '<div class="row atbs-read-more">
                <div class="col '.$readMorePadding.'">
                      <a class="btn btn-' . $attributes['readMoreSize'] . ' ' . $btnColor . ' ' . $btnPosition . '" href="' . $link . '">' . $label . '</a>
                 </div>
            </div>';
}

function atbs_excerpt($length, $post)
{
    $text = strip_shortcodes($post->post_content);
    $text = str_replace(']]>', ']]&gt;', $text);
//    $excerpt_length = apply_filters('excerpt_length', $length);
//    return $excerpt_length;
    $text = wp_trim_words($text, $length);

    return $text;
}

function atbs_pg_category_css($attributes, $prefix = 'category')
{
    $borderCss = '';
    if ($prefix !== 'price') {
        $borderCss = Util::get_border_css($attributes, $prefix);
    }
    return $borderCss . Util::getSpacingStyles($attributes, $prefix);
}

function atbs_pg_atc_css($attributes, $prefix = '')
{
    $background = $attributes['atcButtonBgOverlay'];
    $typography = Util::typographyCss($attributes, 'atcButton');

    return 'background:' . $background . ';' . Util::getSpacingStyles($attributes, 'atcButton') . 'color:' . $attributes['metaTextColor'] . ';' . Util::get_border_css($attributes, 'atcButton') . $typography;
}