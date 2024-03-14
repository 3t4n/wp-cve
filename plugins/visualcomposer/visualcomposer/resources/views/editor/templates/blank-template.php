<?php

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    vcevent('vcv:resources:views:editor:templates:blankTemplate:wpHead:before');
    wp_head();
    vcevent('vcv:resources:views:editor:templates:blankTemplate:wpHead:after');

    $customLayoutWidth = vchelper('Options')->get('custom-page-templates-section-layout-width', '1140');
    $customLayoutWidth = (int)rtrim($customLayoutWidth, 'px');
    if (empty($customLayoutWidth)) {
        $customLayoutWidth = '1140';
    }

    ?>
    <!-- Override the main container width styles -->
    <style>
        @media (min-width: 1200px) {
            div.vcv-content--boxed .entry-content [data-vce-boxed-width="true"],
            .vcv-content--boxed .vcv-layouts-html [data-vce-boxed-width="true"],
            div.vcv-content--boxed  > [data-vce-boxed-width="true"],
            div.vcv-content--boxed .entry-content  > [data-vce-boxed-width="true"],
            div.vcv-editor-theme-hf .vcv-layouts-html > [data-vce-boxed-width="true"],
            div.vcv-header > [data-vce-boxed-width="true"],
            .vcv-content--boxed .entry-content .vce-layouts-wp-content-area-container .vce-row-container > .vce-row[data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > .vce-row-content,
            div.vcv-footer > [data-vce-boxed-width="true"],
            div.vcv-content--boxed .entry-content > * > [data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > [data-vce-element-content="true"],
            .vcv-content--boxed > .vce-row-container > .vce-row[data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > .vce-row-content,
            div.vcv-content--boxed  > * > [data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > [data-vce-element-content="true"],
            div.vcv-editor-theme-hf .vcv-layouts-html > * > [data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > [data-vce-element-content="true"],
            div.vcv-header > * > [data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > [data-vce-element-content="true"],
            div.vcv-footer > * > [data-vce-full-width="true"]:not([data-vce-stretch-content="true"]) > [data-vce-element-content="true"] {
                max-width: <?php echo esc_attr($customLayoutWidth) . 'px' ?> !important;
                margin-right: auto;
                margin-left: auto;
            }
        }
    </style>
</head>
<body <?php body_class(); ?>>
<?php
if (function_exists('wp_body_open')) {
    wp_body_open();
}

while (have_posts()) :
    the_post();
    ?>
    <div class="vcv-content--blank vcv-content--boxed">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    </div>
    <?php
endwhile;
vcevent('vcv:resources:views:editor:templates:blankTemplate:wpFooter:before');
wp_footer();
vcevent('vcv:resources:views:editor:templates:blankTemplate:wpFooter:after');
?>
</body>
</html>
