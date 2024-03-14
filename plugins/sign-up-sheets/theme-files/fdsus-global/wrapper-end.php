<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/fdsus-global/wrapper-end.php.
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.1.4 (plugin version)
 * @version     1.0.0 (template file version)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$template = strtolower(get_option('template'));

switch ($template) {
    case 'twentyten':
        echo '</div></div>';
        break;
    case 'twentyeleven':
        echo '</div>';
        get_sidebar('shop');
        echo '</div>';
        break;
    case 'twentytwelve':
        echo '</div></div>';
        break;
    case 'twentythirteen':
        echo '</div></div>';
        break;
    case 'twentyfourteen':
        echo '</div></div></div>';
        get_sidebar('content');
        break;
    case 'twentyfifteen':
        echo '</div></div>';
        break;
    case 'twentysixteen':
        echo '</main></div>';
        get_sidebar();
        break;
    case 'twentyseventeen':
        echo '</main><!-- #main --></div><!-- #primary -->';
        get_sidebar();
        echo '</div><!-- .wrap -->';
        break;
    case 'twentytwenty':
        echo '</main><!-- #site-content -->';
        get_template_part('template-parts/footer-menus-widgets');
        break;

    // 3rd Party
    case 'divi':
        if (!et_builder_is_product_tour_enabled()):
            echo '</div> <!-- #left-area -->';
            get_sidebar();
            echo '</div> <!-- #content-area --></div> <!-- .container -->';
        endif;
        echo '</div><!-- #main-content -->';
        break;
    case 'petal':
        $blog_single_layout = apply_filters('petal_filter_single_is_boxed', petal_get_option('blog-single-layout', 'default'));
        $blog_single_is_boxed = $blog_single_layout == 'boxed' || $blog_single_layout == 'boxed-fullwidth';
        $blog_single_is_fullwidth = $blog_single_layout == 'fullwidth' || $blog_single_layout == 'boxed-fullwidth' || !is_active_sidebar('wheels-sidebar-primary');
        $blog_sidebar_left = petal_get_option('single-post-sidebar-left', false);
        echo '</div>';
        if (!$blog_sidebar_left && !$blog_single_is_fullwidth) : ?>
            <div class="<?php echo esc_attr(petal_class('sidebar')) ?>">
                <?php get_sidebar(); ?>
            </div>
        <?php endif;
        echo '</div></div>';
        break;
    case 'enfold':
        echo '</div><!-- .entry-content-wrapper -->';
        echo '</main>';
        $avia_config['currently_viewing'] = "blog";
        get_sidebar();
        echo '</main></div><!--end container--></div><!-- close default .container_wrap element -->';
        break;
    case 'virtue':
        do_action('virtue_sidebar');
        echo '</div><!-- .row -->';
        echo '</div><!-- #content -->';
        break;

    // Default
    default:
        echo '</main></div>';
        break;
}
