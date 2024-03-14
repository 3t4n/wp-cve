<?php
function better_breadcrumb()
{
    $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = esc_html( '/' ); // delimiter between crumbs
    $home = esc_html__( 'Home', 'better-el-addons' ); // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
    

    global $post;
    $homeLink = esc_url( home_url() );
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<div id="crumbs"><a href="' . esc_url($homeLink) . '">' . $home . '</a><span>' . $delimiter .'</span> '. $slug = $the_page->post_name .'</div>';
        }
    } else {
        echo '<div id="crumbs"><a href="' . esc_url($homeLink) . '">' . $home . '</a> <span>' . esc_html($delimiter) . '</span> ';
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                echo get_category_parents($thisCat->parent, true, ' <span>' . esc_html($delimiter) . '</span> ');
            }
            echo '<a class="active">' . esc_html__('Archive by category "', 'better-el-addons') . single_cat_title('', false) . '"</a>';

        } elseif (is_search()) {
            echo '<a class="active">' . esc_html__('Search results for "', 'better-el-addons') . get_search_query() . '"</a>';

        } elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> <span>' . esc_html($delimiter) . '</span> ';
            echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> <span>' . esc_html($delimiter) . '</span> ';
            echo '<a class="active">' . get_the_time('d') . '</a>';

        } elseif (is_month()) {

            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> <span>' . esc_html($delimiter) . '</span> ';
            echo '<a class="active">' . get_the_time('F') . '</a>';

        } elseif (is_year()) {
            echo '<a class="active">' . get_the_time('Y') . '</a>';
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                if ($showCurrent == 1) {
                    echo '<span>' . $delimiter . '</span>' . '<a class="active">' . get_the_title() . '</a>';
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, '<span>' . $delimiter . '</span>');
                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo wp_kses_post($cats);
                if ($showCurrent == 1) {
                    echo '<a class="active">' . get_the_title() . '</a>';
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo '<a class="active">' . $post_type->labels->singular_name . '</a>';
        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            echo get_category_parents($cat, true, ' <span>' . esc_html($delimiter) . '</span> ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
            
            if ($showCurrent == 1) {
                echo '<span>' . $delimiter . '</span>' . '<a class="active">' . get_the_title() . '</a>';
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo '<a class="active">' . get_the_title() . '</a>';
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo wp_kses_post($breadcrumbs[$i]);
                if ($i != count($breadcrumbs)-1) {
                    echo '<span>' . $delimiter . '</span>';
                }
            }
            if ($showCurrent == 1) {
                echo '<span>' . $delimiter . '</span>' . '<a class="active">' . get_the_title() . '</a>';
            }
        } elseif (is_tag()) {
            echo '<a class="active">' . 'Posts tagged "' . single_tag_title('', false) . '"' . '</a>';
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo '<a class="active">' . 'Articles posted by ' . $userdata->display_name . '</a>';
        } elseif (is_404()) {
            echo '<a class="active">' . 'Page Not Found' . '</a>';
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo esc_html__('Page', 'better-el-addons' ) . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</div>';
    }
}
?>

<div class="better-breadcrumbs style1">
    <div class="path">
    <?php if (function_exists('better_breadcrumb')) better_breadcrumb(); ?>
    </div>
</div>