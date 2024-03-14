<?php
/**
 * POST_GRID
 *
 *
 * @since   1.0.0
 * @package gpl
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


if( !class_exists('GPL_POST_GRID') ){
    class GPL_POST_GRID{

        public function __construct() {
            add_action('init', array($this, 'register'));
        }

        public function get_attributes($default = false){
            $attributes = array(
                'postBlockWidth' => array(
                    'type' => 'string',
                ),
                'align' => array(
                    'type' => 'string',
                    'default' => 'left',
                ),
				'additionalCssClasses' => array(
					'type' => 'string',
					'default' => '',
				),
                'post_type' => array(
                    'type' => 'string',
                    'default' => 'post'
                ),
                'categories' => array(
                    'type' => 'array',
                    'default' => [],
                    'items' => array(
                        'type'=> 'integer'
                    )
                ),
                'team_cats' => array(
                    'type' => 'string',
                ),
                'postscount' => array(
                    'type' => 'number',
                    'default' => 5,
                ),
                'taxonomyName' => array(
                    'type' => 'string',
                ),
                'order' => array(
                    'type' => 'string',
                    'default' => 'desc',
                ),
                'orderBy'  => array(
                    'type' => 'string',
                    'default' => 'date',
                ),
                // post offset
                'postOffset' => array(
                    'type' => 'number',
                    'default' => 0
                ),
                'equalHeight' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'columns' => array(
                    'type' => 'number',
                    'default' => 2
                ),
                'columnGap' => array(
                    'type' => 'number',
                    'default' => 15
                ),
                'imageHeight' => array(
                    'type' => 'number',
                    'default' => '',
                ),
                'postLayout' => array(
                    'type' => 'string',
                    'default' => 'grid',
                ),
                'carouselLayoutStyle' => array(
                    'type' => 'string',
                    'default' => 'skin1',
                ),
                'slidesToShow' => array(
                    'type' => 'number',
                    'default' => 2,
                ),
                'autoPlay' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'navigation' => array(
                    'type' => 'string',
                    'default' => 'dots'
                ),
                'gridLayoutStyle' => array(
                    'type' => 'string',
                    'default' => 'g_skin1',
                ),
                'postImageSizes' => array(
                    'type' => 'string',
                    'default' => 'full',
                ),
                'displayPostImage' => array(
                    'type' => 'boolean',
                    'default' => true
                ),
                'displayPostDate' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayPostAuthor' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayPostExcerpt' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayPostReadMoreButton' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'postReadMoreButtonText' => array(
                    'type' => 'string',
                    'default' => 'Read More',
                ),
                'linkTarget' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),

                'displayPostCtaButton' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'postCtaButtonStyle' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'postCtaButtonText' => array(
                    'type' => 'string',
                    'default' => 'View All',
                ),
                'postCtaButtonLink' => array(
                    'type' => 'string',
                    'default' => '#',
                ),
                'CtaLinkTarget' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'displayCtaButtonIcon' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'postCtaButtonAlign' => array(
                    'type' => 'string',
                    'default' => 'center',
                ),

                // heading attrs
                'displayPostHeading' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'postHeadingStyle' => array(
                    'type' => 'string',
                    'default' => 'style1',
                ),
                'postHeadingText' => array(
                    'type' => 'string',
                    'default' => 'Post Layout',
                ),
                'postHeadingLink' => array(
                    'type' => 'string',
                    'default' => '#',
                ),
                'postHeadingLinkTarget' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'postHeadingAlign' => array(
                    'type' => 'string',
                    'default' => 'center',
                ),

                // sub heading attrs
                'displayPostSubHeading' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'postSubHeadingText' => array(
                    'type' => 'string',
                    'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley',
                ),

                // pagination attrs
                'displayPagination' =>  array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'paginationType' => array(
                    'type' => 'string',
                    'default' => 'gpl-pagination',
                ),
                'paginationStyle' => array(
                    'type' => 'string',
                    'default' => 'gpl-only-arrow',
                ),
                'navigationPosition' => array(
                    'type' => 'string',
                    'default' => 'gpl-nav-top-right',
                ),
                'paginationAlign' => array(
                    'type' => 'string',
                    'default' => 'left',
                ),


                // filter attrs
                'queryTaxonomy' => [
                    'type' => 'string',
                    'default' => ''
                ],
                'queryCat' => [
                    'type' => 'string',
                    'default' => '[]',
                ],
                'queryTag' => [
                    'type' => 'string',
                    'default' => '[]',
                ],

                'displayFilter' =>  array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'displayAllButton' =>  array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'allButtonText' => array(
                    'type' => 'string',
                    'default' => 'All',
                ),

                'filterBy' => array(
                    'type' => 'string',
                    'default' => 'categories',
                ),
                'filterCats' => array(
                    'type' => 'array',
                    'default' => [],
                    'items' => array(
                        'type'=> 'string'
                    )
                ),
                'filterTags' => array(
                    'type' => 'array',
                    'default' => [],
                    'items' => array(
                        'type'=> 'string'
                    )
                ),
                'maxTaxDisplay' => array(
                    'type' => 'number',
                    'default' => 5
                ),
            );

            if( $default ){
                $default_attrs = array();
                foreach ($attributes as $key => $value) {
                    if( isset($value['default']) ){
                        $default_attrs[$key] = $value['default'];
                    }
                }
                return $default_attrs;
            }else{
                return $attributes;
            }
        }

        public function register() {

            if( !function_exists('register_block_type') ){
                return;
            }

            register_block_type( 'guten-post-layout/post-grid',
                array(
                    'attributes' => $this->get_attributes(),
                    'render_callback' =>  array($this, 'content'),
                )
            );

           // $this->register_rest_orderby_fields();
        }

        public function content( $attributes, $has_ajax = false ){

            if( $attributes['postLayout'] === 'slides' ) {
                wp_enqueue_script( 'slick' );
            }

            if( $attributes['postLayout'] === 'slides' ||  $attributes['displayFilter'] ) {
                wp_enqueue_script( 'guten-post-layout-custom' );
            }

           $paged = isset($attributes['paged']) ? $attributes['paged'] : 1;

            $args = array(
                'post_type' => $attributes['post_type'],
                'post_status' => $attributes['post_type'] === 'attachment' ? 'inherit' : 'publish',
                'order'       => $attributes['order'],
                'orderby'     => $attributes['orderBy'],
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $attributes['postscount'],
                'paged' => $paged,
            );

            if(!$has_ajax){
                if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; }
                $args['paged'] = $paged;
            }

            if( isset($attributes['postOffset']) && $attributes['postOffset'] && ! ($args['paged'] > 1 ) ){
                $args['offset'] = isset($attributes['postOffset']) ? $attributes['postOffset'] : 0;
            }

            $taxonomyName = isset($attributes['taxonomyName']) ? $attributes['taxonomyName'] : '';

            if( isset($attributes['categories']) && !empty($attributes['categories']) ){

                $args['tax_query'] = array( array('taxonomy' => $taxonomyName, 'field' => 'term_id', 'terms' => isset($attributes['categories']) ? $attributes['categories'] : '' ) );

            }


            if( isset($attributes['queryTaxonomy']) && !empty($attributes['queryTaxonomy']) ) {
                if (isset($attributes['queryCat'])) {
                    if ($attributes['queryTaxonomy'] == 'categories' && !empty($attributes['queryCat'])) {
                        if( $attributes['queryCat'] != '[]' ) {
                            $args['tax_query'] = array(array('taxonomy' => 'category', 'field' => 'slug', 'terms' => $attributes['queryCat']));
                        }
                    }
                }

                if (isset($attributes['queryTag'])) {

                    if ($attributes['queryTaxonomy'] == 'tags' && !empty($attributes['queryTag'])) {
                        if( $attributes['queryTag'] != '[]' ) {
                            $args['tax_query'] = array(array('taxonomy' => 'post_tag', 'field' => 'slug', 'terms' => $attributes['queryTag']));
                        }
                    }
                }
            }

            $query = new WP_Query($args);

            $recent_posts = array_map(
                function( $post ) {
                    return (array) $post;
                },
                $query->posts
            );


			$total_pages = $this->get_page_number($attributes, $query->found_posts);

            if ( count( $recent_posts ) === 0 ) {
                return;
            }

            $markup = $markup_before = $item_markup = $markup_after = '';
            $blockName = 'guten-post-layout_post-grid';

            $target = isset($attributes['linkTarget']) && !empty($attributes['linkTarget']) ? '_blank' : '_self';
            $widthClass = (isset($attributes['postBlockWidth']) && $attributes['postBlockWidth'] ) ? 'align'.$attributes['postBlockWidth'] : '';
            $postItemHeight = (isset($attributes['equalHeight']) && $attributes['equalHeight'] ) ? 'equal-height' : '';
            $_gpl_row = (isset($attributes['postBlockWidth']) && $attributes['postBlockWidth'] ) ? 'gpl-row' : '';
            if( $attributes['postLayout'] === 'slides'){

                $random_number = rand(10, 1000);
                $markup .= sprintf('<div id="gpl-slick-slider-'.esc_attr($random_number).'" class="gpl-slick-slider '.esc_attr($widthClass).'" data-count="%1$d" data-slides-to-show="%2$s" data-autoplay="%3$s" data-navigation="%4$s">', count( $recent_posts ), $attributes['slidesToShow'], $attributes['autoPlay'], $attributes['navigation']);

                if( isset($attributes['displayPostHeading']) && $attributes['displayPostHeading'] && isset($attributes['postHeadingText']) && $attributes['postHeadingText'] ) {

                    $sub_heading = isset($attributes['displayPostSubHeading']) && $attributes['displayPostSubHeading'] ? '<p>'.$attributes['postSubHeadingText'].'</p>' : '';
                    $markup .= '<div  class="gpl-row gpl-d-flex gpl-flex-wrap">';
                    $markup .= sprintf( '<div class="gpl-post-heading-wrapper gpl-post-heading-%4$s gpl-post-heading-%5$s">
				<h3><a class="gpl-post-heading" href="%1$s" target="%2$s" rel="bookmark">%3$s</a></h3>
				'.$sub_heading.'
				</div>',
                        esc_url( $attributes['postHeadingLink'] ),
                        $attributes['postHeadingLinkTarget'],
                        esc_html( $attributes['postHeadingText'] ),
                        $attributes['postHeadingAlign'],
                        $attributes['postHeadingStyle']
                    );
                    $markup .= '</div>';
                }

                $markup .= sprintf('<div  class="gpl-post-slider-one wp-block-guten-post-layout-post-grid post-grid-view gpl-d-flex gpl-flex-wrap %1$s %2$s" data-layout="%1$s" style="--item-padding-left-right : '.$attributes['columnGap'].'px; --item-margin-bottom : '.($attributes['columnGap']*2).'px; --item-height : '.(300-$attributes['columnGap']).'px; --image-height:'.($attributes['imageHeight']).'px;" >', $attributes['carouselLayoutStyle'], $attributes['additionalCssClasses']);

                foreach ( $recent_posts as $post ){
                    $post_id = $post['ID'];
                    $post_thumbnail_id = get_post_thumbnail_id($post_id);

                    $gridView = $attributes['postLayout'] === 'grid' ? 'post-item gpl-mb-30 gpl-column-'.$attributes['columns'].'' : 'post-item gpl-mb-30';

                    $image_src = $post_thumbnail_id ? wp_get_attachment_image_src( $post_thumbnail_id, '' . $attributes['postImageSizes'] . '', false)[0] : '';
                    $image = $attributes['post_type'] === 'attachment' ? wp_get_attachment_image_url($post_id, 'full') : $image_src;

                    $hasImage = $image ? 'has-image' : '';
                    $contentHasImage = $image ? 'content-has-image' : '';

                    // start the post-item wrap
                    $markup .= sprintf( '<article class="%1$s %2$s">', esc_attr($gridView), $attributes['carouselLayoutStyle'] );
                    // start the post content wrap
                    $markup .= '<div class="post-content-area ' . $attributes['align'] . ' '.$hasImage.'">';

                    if ( $attributes['carouselLayoutStyle'] === 'skin1') {
                        $markup .= '<a class="active-post-link" target="'.$target.'" href=' . esc_url( get_permalink( $post_id ) ) . '></a>';
                    }

                    if( $attributes['displayPostImage'] && $image ) {
                        $markup .= sprintf( '<div class="post-image"><a href="%1$s" target="%3$s" rel="bookmark"><img src="%2$s" alt="%4$s"/></a></div>',
                            esc_url( get_permalink( $post_id ) ),
                            $image,
                            $target,
                           get_the_title($post_id)
                        );
                    }

                    // start the inner post content wrap
                    $markup .= '<div class="gpl-inner-post-content '.$contentHasImage.'">';

                    // start the post meta wrap
                    $markup .= '<div class="post-meta">';

                    if( isset($attributes['displayPostAuthor']) && $attributes['displayPostAuthor'] && $attributes['carouselLayoutStyle'] !== 'g_skin2' ) {
                        $markup .= sprintf(
                            '<a target="_blank" href="%2$s">%1$s</a>',
                            esc_html( get_the_author_meta( 'display_name', $post['post_author'] ) ),
                            esc_url( get_author_posts_url($post['post_author']) )
                        );
                    }

                    if( isset($attributes['displayPostDate']) && $attributes['displayPostDate'] ) {
                        $markup .= sprintf(
                            '<time datetime="%1$s">%2$s</time>',
                            esc_attr( get_the_date( 'c', $post_id ) ),
                            esc_html( get_the_date( '', $post_id ) )
                        );
                    }

                    $markup .= '</div>';
                    // close the post meta wrap

                    // start the post title wrap
                    $markup .= sprintf( '<h2 class="post-title"><a href="%1$s" target="%3$s" rel="bookmark">%2$s</a></h2>',
                        esc_url( get_permalink($post_id) ),
                        esc_html( get_the_title($post_id)),
                        $target
                    );
                    // close the post title wrap

                    // start the post excerpt wrap
                    $content = get_the_excerpt( $post_id );
                    if( $content && $attributes['displayPostExcerpt'] && $attributes['carouselLayoutStyle'] !== 'g_skin1' && $attributes['carouselLayoutStyle'] !== 'g_skin2' ) {
                        $markup .= sprintf( ' <div class="post-excerpt"><div><p>%1$s</p></div></div>',
                            wp_kses_post( $content )
                        );
                    }
                    // close the post excerpt wrap

                    // start the post read more wrap
                    if( isset($attributes['displayPostReadMoreButton']) && $attributes['displayPostReadMoreButton'] && $attributes['carouselLayoutStyle'] !== 'g_skin1' && $attributes['gridLayoutStyle'] !== 'g_skin2') {
                        $markup .= sprintf( '<div><a class="post-read-moore" href="%1$s" target="%3$s" rel="bookmark">%2$s</a></div>', esc_url( get_permalink( $post_id ) ),esc_html( $attributes['postReadMoreButtonText'] ), $target );
                    }
                    // close the post read more wrap

                    $markup .= '</div>';
                    $markup .= '<div class="gpl-overlay-effect"></div>';
                    $markup .= '</div>';
                    // close the post content wrap

                    $markup .= '</article>';
                    // close the post-item wrap
                }

                $markup .= '</div>';
                $markup .= '</div>';
            }


            if( $attributes['postLayout'] !== 'slides') {

                $columnGap = isset($attributes['columnGap']) ? $attributes['columnGap'] : '';
                $imageHeight = isset($attributes['imageHeight']) && is_numeric($attributes['imageHeight']) ? $attributes['imageHeight'].'px' : null;
                $itemHeight = (int)$imageHeight && (int)$columnGap ? ((int)$imageHeight/2) - $columnGap .'px' : '285px';

                $firstPostItem = count($recent_posts) > 0 && $attributes['gridLayoutStyle'] === 'g_skin2' ? array_splice($recent_posts, 0, 1) : '';

                $gridViewWrapper = $attributes['postLayout'] === 'list' ? 'wp-block-guten-post-layout-post-grid post-grid-view gpl-d-flex gpl-flex-wrap list-layout' : 'wp-block-guten-post-layout-post-grid post-grid-view gpl-d-flex gpl-flex-wrap';


                $markup_before .= sprintf( '<div class="%1$s %2$s %3$s %4$s %5$s" style="--item-padding-left-right : '.$columnGap.'px; --item-minus-padding-left-right : -'.($columnGap).'px; --item-margin-bottom : '.($columnGap*2).'px; --item-height: '.$itemHeight.'; --image-height:'.($imageHeight).';">', esc_attr( $gridViewWrapper ), $attributes['gridLayoutStyle'], $widthClass, $_gpl_row, $attributes['additionalCssClasses'] );


                $markup_before .= '<div class="gpl-reverse-spinner"></div>';

                // start the post heading
                if( (isset($attributes['displayPostHeading']) && $attributes['displayPostHeading']) || (isset($attributes['displayFilter']) && $attributes['displayFilter']) ) {
                    $has_filter = isset($attributes['displayFilter']) && $attributes['displayFilter'] ? 'gpl-has-filter' : '';

                    $markup_before .= '<div class="gpl-post-heading-wrapper gpl-post-heading-' . $attributes['postHeadingAlign'] . ' gpl-post-heading-' . $attributes['postHeadingStyle'] . ' ' . $has_filter . ' ">';

                    $markup_before .= $has_filter ? '<div>' : '';

                    if (isset($attributes['displayPostHeading']) && $attributes['displayPostHeading'] && isset($attributes['postHeadingText']) && $attributes['postHeadingText']) {

                        $sub_heading = isset($attributes['displayPostSubHeading']) && $attributes['displayPostSubHeading'] ? '<p>' . $attributes['postSubHeadingText'] . '</p>' : '';

                        $markup_before .= sprintf('<h3><a class="gpl-post-heading" href="%1$s" target="%2$s" rel="bookmark">%3$s</a></h3>' . $sub_heading . '',
                            esc_url($attributes['postHeadingLink']),
                            $attributes['postHeadingLinkTarget'],
                            esc_html($attributes['postHeadingText'])
                        );
                    }
                    $markup_before .= $has_filter ? '</div>' : '';

                    if (isset($attributes['displayFilter']) && $attributes['displayFilter']) {
                        $markup_before .= '<div class="gpl-post-filter" data-filtertype="' . $attributes['filterBy'] . '" data-postid="' . get_the_ID() . '" data-blockname="' . $blockName . '">';

                        $markup_before .= $this->filter($attributes['filterBy'], $attributes['displayAllButton'], $attributes['allButtonText'], $attributes['filterCats'], $attributes['filterTags'], $attributes['maxTaxDisplay']);

                        $markup_before .= '</div>';
                    }


                    $markup_before .= '</div>';
                    // end the post heading
                }

                $markup_before .= $attributes['gridLayoutStyle'] === 'g_skin2' ? '<div class="gpl-all-posts gpl-d-flex gpl-flex-wrap gpl-column-12">' : '';

                if ( $firstPostItem ) {
                    $post_id           = $firstPostItem[0]['ID'];
                    $post_thumbnail_id = get_post_thumbnail_id( $post_id );
                    $post              = $firstPostItem[0];

                    // start the post-item wrap
                    $item_markup .= '<div class="gpl-column-4">';

                    $gridView = $attributes['postLayout'] === 'grid' ? 'post-item gpl-mb-30 gpl-column-'
                        . $attributes['columns'] . ''
                        : 'post-item gpl-mb-30';

                    $item_markup .= sprintf( '<article class="%1$s %2$s">', esc_attr( $gridView ), $attributes['gridLayoutStyle'] );

                    // start the post content wrap
                    $item_markup .= '<div class="post-content-area ' . $attributes['align'] . '">';
                    if ( $attributes['gridLayoutStyle'] === 'g_skin2' || $attributes['gridLayoutStyle'] === 'g_skin1') {
                        $item_markup .= '<a class="active-post-link" target="'.$target.'" href=' . esc_url( get_permalink( $post_id ) ) . '></a>';
                    }

                    $image_src = $post_thumbnail_id ? wp_get_attachment_image_src( $post_thumbnail_id, '' . $attributes['postImageSizes'] . '', false)[0] : '';
                    $image = $attributes['post_type'] === 'attachment' ? wp_get_attachment_image_url($post_id, 'full') : $image_src;

                    if ( $attributes['displayPostImage'] && $image ) {
                        $item_markup .= sprintf( '<div class="post-image"><a href="%1$s" target="%3$s" rel="bookmark"><img src="%2$s" alt="%4$s"/></a></div>',
                            esc_url( get_permalink( $post_id ) ),
                            $image,
                            $target,
                            get_the_title($post_id)
                        );
                    }

                    // start the inner post content wrap
                    $item_markup .= '<div class="gpl-inner-post-content">';

                    // start the post meta wrap
                    $item_markup .= '<div class="post-meta">';

                    if ( isset( $attributes['displayPostAuthor'] ) && $attributes['displayPostAuthor']
                        && $attributes['gridLayoutStyle'] !== 'g_skin2'
                    ) {
                        $item_markup .= sprintf(
                            '<a target="_blank" href="%2$s">%1$s</a>',
                            esc_html( get_the_author_meta( 'display_name', $post['post_author'] ) ),
                            esc_url( get_author_posts_url( $post['post_author'] ) )
                        );
                    }

                    if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
                        $item_markup .= sprintf(
                            '<time datetime="%1$s">%2$s</time>',
                            esc_attr( get_the_date( 'c', $post_id ) ),
                            esc_html( get_the_date( '', $post_id ) )
                        );
                    }

                    $item_markup .= '</div>';
                    // close the post meta wrap

                    // start the post title wrap
                    $item_markup .= sprintf( '<h2 class="post-title"><a href="%1$s" target="%3$s" rel="bookmark">%2$s</a></h2>',
                        esc_url( get_permalink( $post_id ) ),
                        esc_html( get_the_title( $post_id ) ),
                        $target
                    );
                    // close the post title wrap

                    // start the post excerpt wrap
                    $content = get_the_excerpt( $post_id );
                    if ( $content && $attributes['displayPostExcerpt'] && $attributes['gridLayoutStyle'] !== 'g_skin2') {
                        $item_markup .= sprintf( ' <div class="post-excerpt"><div><p>%1$s</p></div></div>',
                            wp_kses_post( $content )
                        );
                    }
                    // close the post excerpt wrap

                    // start the post read more wrap
                    if ( isset( $attributes['displayPostReadMoreButton'] ) && $attributes['displayPostReadMoreButton'] && $attributes['gridLayoutStyle'] !== 'g_skin2') {
                        $item_markup .= sprintf( '<div><a class="post-read-moore" href="%1$s" target="%3$s" rel="bookmark">%2$s</a></div>',
                            esc_url( get_permalink( $post_id ) ), esc_html( $attributes['postReadMoreButtonText'] ), $target );
                    }
                    // close the post read more wrap

                    $item_markup .= '</div>';
                    $item_markup .= '<div class="gpl-overlay-effect"></div>';
                    $item_markup .= '</div>';
                    // close the post content wrap

                    $item_markup .= '</article>';
                    // close the post-item wrap
                    $item_markup .= '</div>';
                }

                $parentClasses = $attributes['gridLayoutStyle'] === 'g_skin2' ? 'gpl-column-8 gpl-d-flex gpl-flex-wrap' : 'gpl-all-posts gpl-column-12 gpl-d-flex gpl-flex-wrap';

                if( $attributes['gridLayoutStyle'] === 'g_skin2' ){
                    $item_markup .= '<div class="'.esc_attr($parentClasses).'">';
                } else {
                    $markup_before .= '<div class="'.esc_attr($parentClasses).'">';
                }

                foreach ( $recent_posts as $post ) {
                    $post_id = $post['ID'];
                    $post_thumbnail_id = get_post_thumbnail_id( $post_id );

                    $gridView = $attributes['postLayout'] === 'grid' ? 'post-item gpl-mb-30 gpl-column-'
                        . $attributes['columns'] . ''
                        : 'post-item gpl-mb-30';

                    // start the post-item wrap
                    $item_markup .= sprintf( '<article class="%1$s %2$s">', esc_attr( $gridView ), $attributes['gridLayoutStyle'] );

                    // start the post item wrapper
                    $item_markup .= '<div class="post-item-wrapper '.$postItemHeight.'">';

                    // start the post content wrap
                    $item_markup .= '<div class="post-content-area ' . $attributes['align'] . '">';

                    if ( $attributes['gridLayoutStyle'] === 'g_skin2' || $attributes['gridLayoutStyle'] === 'g_skin1') {
                        $item_markup .= '<a class="active-post-link" target="'.$target.'" href=' . esc_url( get_permalink( $post_id ) ) . '></a>';
                    }

                    $image_src = $post_thumbnail_id ? wp_get_attachment_image_src( $post_thumbnail_id, '' . $attributes['postImageSizes'] . '', false)[0] : '';
                    $image = $attributes['post_type'] === 'attachment' ? wp_get_attachment_image_url($post_id, 'full') : $image_src;

                    if ( $attributes['displayPostImage'] && $image ) {
                        $item_markup .= sprintf( '<div class="post-image"><a href="%1$s" target="%3$s" rel="bookmark"><img src="%2$s" alt="%4$s"/></a></div>',
                            esc_url( get_permalink( $post_id ) ),
                            $image,
                            $target,
                            get_the_title($post_id)
                        );
                    }

                    // start the inner post content wrap
                    $item_markup .= '<div class="gpl-inner-post-content">';

                    // start the post meta wrap
                    $item_markup .= '<div class="post-meta">';

                    if ( isset( $attributes['displayPostAuthor'] ) && $attributes['displayPostAuthor']
                        && $attributes['gridLayoutStyle'] !== 'g_skin2'
                    ) {
                        $item_markup .= sprintf(
                            '<a target="_blank" href="%2$s">%1$s</a>',
                            esc_html( get_the_author_meta( 'display_name', $post['post_author'] ) ),
                            esc_url( get_author_posts_url( $post['post_author'] ) )
                        );
                    }

                    if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
                        $item_markup .= sprintf(
                            '<time datetime="%1$s">%2$s</time>',
                            esc_attr( get_the_date( 'c', $post_id ) ),
                            esc_html( get_the_date( '', $post_id ) )
                        );
                    }

                    $item_markup .= '</div>';
                    // close the post meta wrap

                    // start the post title wrap
                    $item_markup .= sprintf( '<h2 class="post-title"><a href="%1$s" target="%3$s" rel="bookmark">%2$s</a></h2>',
                        esc_url( get_permalink( $post_id ) ),
                        esc_html( get_the_title( $post_id ) ) ,
                        $target
                    );
                    // close the post title wrap

                    // start the post excerpt wrap
                    $content = get_the_excerpt( $post_id );
                    if ( $content && $attributes['displayPostExcerpt'] && $attributes['gridLayoutStyle'] !== 'g_skin1'
                        && $attributes['gridLayoutStyle'] !== 'g_skin2'
                    ) {
                        $item_markup .= sprintf( ' <div class="post-excerpt"><div><p>%1$s</p></div></div>',
                            wp_kses_post( $content )
                        );
                    }
                    // close the post excerpt wrap

                    // start the post read more wrap
                    if ( isset( $attributes['displayPostReadMoreButton'] ) && $attributes['displayPostReadMoreButton']
                        && $attributes['gridLayoutStyle'] !== 'g_skin1'
                        && $attributes['gridLayoutStyle'] !== 'g_skin2'
                    ) {
                        $item_markup .= sprintf( '<div><a class="post-read-moore" href="%1$s" target="%3$s" rel="bookmark">%2$s</a></div>',
                            esc_url( get_permalink( $post_id ) ), esc_html( $attributes['postReadMoreButtonText'] ), $target);
                    }
                    // close the post read more wrap

                    $item_markup .= '</div>';
                    $item_markup .= '<div class="gpl-overlay-effect"></div>';
                    $item_markup .= '</div>';
                    // close the post content wrap
                    $item_markup .= '</div>';
                    // close the post item wrapper

                    $item_markup .= '</article>';
                    // close the post-item wrap
                }

                if( $attributes['gridLayoutStyle'] === 'g_skin2' ) {
                    $item_markup .= '</div>';
                } else {
                    $markup_after .= '</div>';
                }

                $markup_after .= $attributes['gridLayoutStyle'] === 'g_skin2' ? '</div>' : '';

                if( isset($attributes['displayPagination']) && $attributes['displayPagination'] && isset($attributes['paginationType']) && $attributes['paginationType'] === 'gpl-pagination' ) {
                    $pagiAlign = isset($attributes['paginationAlign']) ? $attributes['paginationAlign'] : '';

                    if( $total_pages > 1 ):
                        $markup_after .= '<div class="gpl-post-pagination '.$pagiAlign.'">';
                        $markup_after .= $this->gpl_pagination( $paged, $attributes['paginationStyle'] ,$total_pages );
                        $markup_after .= '</div>';
                    endif;
                }


                if( isset($attributes['displayPagination']) && $attributes['displayPagination'] && isset($attributes['paginationType']) && $attributes['paginationType'] === 'gpl-navigation' ) {
                    if( $total_pages > 1 ):
                    $navigationPosition = isset($attributes['navigationPosition']) ? $attributes['navigationPosition'] : '';
                    $pagiAlign = isset($attributes['paginationAlign']) && $navigationPosition != 'gpl-nav-top-right' ? $attributes['paginationAlign'] : '';

                    $markup_after .= '<div class="gpl-post-navigation ' . esc_attr($navigationPosition) . ' '.$pagiAlign.'">';

                    $links = [];
                    $prev_next = $this->get_posts_nav_link($total_pages, $attributes['paginationStyle'] );
                    array_unshift($links, $prev_next['prev']);
                    $links[] = $prev_next['next'];
                    $markup_after .= implode(PHP_EOL, $links);

                    $markup_after .= '</div>';
                    endif;
                }



                if( isset($attributes['displayPostCtaButton']) && $attributes['displayPostCtaButton'] && isset($attributes['postCtaButtonLink']) && $attributes['postCtaButtonLink'] ) {
                    $icon = isset($attributes['displayCtaButtonIcon']) && $attributes['displayCtaButtonIcon'] ? '<i class="gpl-blocks-icon-long-arrow-right"></i>' : '';
                    $markup_after .= sprintf( '<div class="gpl-cta-wrapper %6$s %7$s">
				<a class="gpl-cta-btn %1$s" href="%2$s" target="%3$s" rel="bookmark">%4$s %5$s</a>
				</div>',
                        $attributes['postCtaButtonStyle'] ? 'gpl-cta-fill-btn' : '',
                        esc_url( $attributes['postCtaButtonLink'] ),
                        $attributes['CtaLinkTarget'],
                        esc_html( $attributes['postCtaButtonText'] ),
                        $icon,
                        $attributes['postCtaButtonAlign'],
                        $widthClass
                    );
                }


                $markup_after .= '</div>';

                wp_reset_query();
            }


            if( $attributes['postLayout'] !== 'slides' ) {
                return $has_ajax ? $item_markup : $markup_before . $item_markup . $markup_after;
            } else {
                return $markup;
            }
        }

        public function get_current_page() {

            return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
        }

        private function get_wp_link_page( $i ) {
            if ( ! is_singular() || is_front_page() ) {
                return get_pagenum_link( $i );
            }

            // Based on wp-includes/post-template.php:957 `_wp_link_page`.
            global $wp_rewrite;
            $post = get_post();
            $query_args = [];
            $url = get_permalink();

            if ( $i > 1 ) {
                if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
                    $url = add_query_arg( 'page', $i, $url );
                } elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
                    $url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
                } else {
                    $url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
                }
            }

            if ( is_preview() ) {
                if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
                    $query_args['preview_id'] = wp_unslash( $_GET['preview_id'] );
                    $query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] );
                }

                $url = get_preview_post_link( $post, $query_args, $url );
            }

            return $url;
        }

        public function get_posts_nav_link( $page_limit = null, $paginationStyle = '') {
//            if ( ! $page_limit ) {
//                $page_limit = $this->query->max_num_pages;
//            }

            $return = [];

            $paged = $this->get_current_page();

            $link_template = '<a class="page-numbers %s" href="%s">%s</a>';

            $disabled_template = '<span class="page-numbers gpl-disable %s">%s</span>';


            $prevText = $paginationStyle == 'gpl-text-arrow' ? '<i class="gpl-arrow-icon gpl-left"></i>'.__('Previous', 'guten-post-layout') : '<i class="gpl-arrow-icon gpl-left"></i>';
            $nextText = $paginationStyle == 'gpl-text-arrow' ? __('Next', 'guten-post-layout').'<i class="gpl-arrow-icon gpl-right"></i>' : '<i class="gpl-arrow-icon gpl-right"></i>';

            if ( $paged > 1 ) {
                $next_page = intval( $paged ) - 1;
                if ( $next_page < 1 ) {
                    $next_page = 1;
                }

                $return['prev'] = sprintf( $link_template, 'gpl-prev', $this->get_wp_link_page( $next_page ), $prevText );
            } else {
                $return['prev'] = sprintf( $disabled_template, 'gpl-prev', $prevText );
            }

            $next_page = intval( $paged ) + 1;

            if ( $next_page <= $page_limit ) {
                $return['next'] = sprintf( $link_template, 'gpl-next', $this->get_wp_link_page( $next_page ), $nextText );
            } else {
                $return['next'] = sprintf( $disabled_template, 'gpl-next', $nextText );
            }

            return $return;
        }

        public function filter( $filterBy = '', $displayAllButton = false, $allButtonText = '', $filterCats = '[]', $filterTags = '[]', $maxTaxDisplay = 5 ) {

            if( !empty($filterTags) || !empty($filterCats) ) {


                $html = '';
                $html .= '<ul>';

                if ($displayAllButton && $allButtonText) {
                    $html .= '<li><a data-taxonomy="" href="#">' . $allButtonText . '</a></li>';
                }

                if ($filterBy == 'categories') {

                    $filter_menu_cat = array_slice($filterCats, 0, $maxTaxDisplay, true);

                    foreach ($filter_menu_cat as $val) {
                        $html .= '<li><a data-taxonomy="' . $val . '" href="#">' . get_category_by_slug($val)->name . '</a></li>';
                    }

                    $filter_more_menu_cat = array_slice($filterCats, $maxTaxDisplay, 5, true);

                    if ($filter_more_menu_cat) {
                        $html .= '<li class="gpl-filter-more"><a data-taxonomy="" href="#">' . __('More', 'guten-post-layout') . '</a><i class="gpl-arrow-icon gpl-down"></i>';

                        $html .= '<ul>';
                        foreach ($filter_more_menu_cat as $val) {
                            $html .= '<li><a data-taxonomy="' . $val . '" href="#"> ' . get_category_by_slug($val)->name . '</a></li>';
                        }
                        $html .= '</ul></li>';
                    }


                } else {

                    $filter_menu_tag = array_slice($filterTags, 0, $maxTaxDisplay, true);

                    foreach ($filter_menu_tag as $val) {
                        $tag = get_term_by('slug', $val, 'post_tag');
                        $html .= '<li><a data-taxonomy="' . $val . '" href="#">' . $tag->name . '</a></li>';
                    }

                    $filter_more_menu_tag = array_slice($filterTags, $maxTaxDisplay, 5, true);

                    if ($filter_more_menu_tag) {
                        $html .= '<li class="gpl-filter-more"><a data-taxonomy="" href="#">' . __('More', 'guten-post-layout') . '</a><i class="gpl-arrow-icon gpl-down"></i>';

                        $html .= '<ul>';
                        foreach ($filter_more_menu_tag as $val) {
                            $tag = get_term_by('slug', $val, 'post_tag');
                            $html .= '<li><a data-taxonomy="' . $val . '" href="#"> ' . $tag->name . '</a></li>';
                        }
                        $html .= '</ul></li>';
                    }

                }

                $html .= '</ul>';

                return $html;
            }

        }


        /**
         * Pagination
         *
         * @param int $pages number of pages.
         * @param int $range number of links to show of lest and right from current post.
         *
         * @return html Returns the pagination html block.
         */
        public function gpl_pagination($paged, $paginationStyle, $pages = '') {
            // init paged
	        $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
	        $paged = $paged ? intval($paged) : 1;

            // init pages
            if($pages == '') {
                global $wp_query;
                $pages = $wp_query->max_num_pages;

                if(!$pages){
                    $pages = 1;
                }
            }

            $pages_data = ($paged >= 3 ? [($paged-1), $paged, $paged+1] : [1,2,3]);

            $html = '';


            // if $pages more then one post
            if( 1 != $pages ) {
                $display_none = 'style="display:none"';

                $pagination_style = $paginationStyle == 'gpl-text-arrow' ? 'gpl-pagination-text-arrow' : '';

                $html .= '<ul class="gpl-pagination '.$pagination_style.'">';
                //$html .= '<span>Page ' . $paged . ' of ' . $pages . '</span>';

                // First link
//                if($paged > 2 && $paged > $range+1 && $showitems < $pages){
//                    $html .= '<li><a data-current="1" href="' . get_pagenum_link(1) . '"><< First</a></li>';
//                }

                // Previous link
                if( $pages > 4){

                    $html .= '<li class="gpl-prev-page-numbers" '.( $paged == 1 ? $display_none:  "" ).'><a href="'.get_pagenum_link($paged - 1).'"><i class="gpl-arrow-icon gpl-left"></i>'.($paginationStyle == 'gpl-text-arrow' ? __('Previous', 'guten-post-layout') : "").'</a></li>';
                }

                if($pages > 4){
                    $current = $paged == 1 ? 'current' : '';
                    $html .= '<li class="gpl-first-page '.$current.'" data-current="1" '.( $paged < 2 ? $display_none : "" ).' ><a href="'.get_pagenum_link(1).'">1</a></li>';
                }


                if( $pages > 4 ) {
                    $html .= '<li class="gpl-first-dots" '. ($paged < 2 ? $display_none : "").'><a href="#">...</a></li>';
                }

                // Links of pages
                foreach( $pages_data as $i ){

                    if($pages >= $i ){
                        $html .= ($paged == $i) ? '<li class="gpl-center-item current" data-current="'.$i.'"><a href="' . get_pagenum_link($i) . '">' . $i .
                            '</a></li>' : '<li class="gpl-center-item" data-current="'.$i.'"><a href="' . get_pagenum_link($i) . '">' . $i .
                            '</a></li>';
                    }
                }

                if($pages > 4){
                    $html .= '<li class="gpl-last-dots" '.( $pages <= $paged+1 ? $display_none : "").'><a href="#">...</a></li>';
                }


                if($pages > 4){
                    $html .= '<li class="gpl-last-page" data-current="'.$pages.'" '.( $pages <= $paged+1 ? $display_none : "").'><a href="'.get_pagenum_link($pages).'">'.$pages.'</a></li>';
                }

                // Next link
                if ($paged < $pages ){
                    $html .= '<li class="gpl-next-page-numbers"><a href="' . get_pagenum_link($paged + 1) . '">'.($paginationStyle == 'gpl-text-arrow' ? __('Next', 'guten-post-layout') : "").'<i class="gpl-arrow-icon gpl-right"></i></a></li>';
                }


                // Last link
//                if ($paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages){
//                    $html .= '<li><a data-current="'.$pages.'" href="' . get_pagenum_link($pages) . '">Last >></a></li>';
//                }


                $html .= '</ul>';
            }

            return $html;


        }

		public function get_page_number($attr, $post_number){
			if ($post_number > 0) {
				if (isset($attr['postOffset']) && $attr['postOffset']) {
					$post_number = $post_number - (int)$attr['postOffset'];
				}
				$post_per_page = isset($attr['postscount']) ? ($attr['postscount'] ? $attr['postscount'] : 1) : 5;
				$pages = ceil($post_number/$post_per_page);
				return $pages;
			}
			return 1;
		}

    }
}




