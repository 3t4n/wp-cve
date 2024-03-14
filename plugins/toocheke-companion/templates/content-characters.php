<?php
/**
 * Template part for displaying all characters
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$allowed_tags = array(
    'a' => array(
        'class' => array(),
        'href' => array(),
        'rel' => array(),
        'title' => array(),
    ),
    'abbr' => array(
        'title' => array(),
    ),
    'b' => array(),
    'blockquote' => array(
        'cite' => array(),
    ),
    'cite' => array(
        'title' => array(),
    ),
    'code' => array(),
    'del' => array(
        'datetime' => array(),
        'title' => array(),
    ),
    'dd' => array(),
    'div' => array(
        'class' => array(),
        'title' => array(),
        'style' => array(),
    ),
    'dl' => array(),
    'dt' => array(),
    'em' => array(),
    'h1' => array(),
    'h2' => array(),
    'h3' => array(),
    'h4' => array(),
    'h5' => array(),
    'h6' => array(),
    'i' => array(),
    'iframe' => array(
        'scrolling' => array(),
        'seamless' => array(),
        'height' => array(),
        'frameborder' => array(),
        'width' => array(),
    ),
    'img' => array(
        'alt' => array(),
        'class' => array(),
        'height' => array(),
        'src' => array(),
        'width' => array(),
    ),
    'li' => array(
        'class' => array(),
    ),
    'ol' => array(
        'class' => array(),
    ),
    'p' => array(
        'class' => array(),
    ),
    'q' => array(
        'cite' => array(),
        'title' => array(),
    ),
    'span' => array(
        'class' => array(),
        'title' => array(),
        'style' => array(),
    ),
    'strike' => array(),
    'strong' => array(),
    'ul' => array(
        'class' => array(),
    ),
);
$total_args = array(
    'taxonomy' => 'comic_characters',
);
$all_active_characters_list = get_categories($total_args);
$total_active_characters = count($all_active_characters_list);
//start paging
$character_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$characters_per_page = 60;
$total_number_of_pages = ceil($total_active_characters / $characters_per_page);
$paged_offset = ($character_paged - 1) * $characters_per_page;

//setup paginate args
$paginate_args = array(
    'taxonomy' => 'comic_characters',
    'style' => 'none',
    'hide_empty' => false,
    'show_count' => 0,
    'number' => $characters_per_page,
    'paged' => $character_paged,
    'offset' => $paged_offset,
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'character-order',
            'compare' => 'NOT EXISTS',
        ),
        array(
            'key' => 'character-order',
            'compare' => 'EXISTS',
        ),
    ),
);

$paged_characters_list = get_categories($paginate_args);

if ($paged_characters_list) {
    ?>
                <!-- START COMIC CHARACTERS LIST-->
                <div id="all-chapters-wrapper" class="grid-container grid-two-cols">




                <?php

    foreach ($paged_characters_list as $character) {
        $term_id = absint($character->term_id);
        $thumb_id = get_term_meta($term_id, 'character-image-id', true);
        $character_name = $character->name;
        $character_description = $character->description;
        ?>

<div class="card">

<!-- Card image -->
<?php

        if (!empty($thumb_id)) {
            $term_img = wp_get_attachment_url($thumb_id);
            printf(wp_kses_data('%1$s'), '<img src="' . esc_url($term_img) . '" />');
        } else {
            ?>
                                        <img
                                            src="<?php echo esc_attr(plugins_url('toocheke-companion' . '/img/default-thumbnail-image.png')); ?>"/>
                                        <?php
}
        ?>

<!-- Card content -->
<div class="card-body">

<!-- Title -->
<h4 class="card-title"><?php echo esc_html($character_name) ?></h4>
<!-- Text -->
<div class="card-text"><?php echo wp_kses($character_description, $allowed_tags) ?></div>


</div>

</div>
<!-- Card -->

<?php
}

// Reset Post Data
    wp_reset_postdata();

    ?>



                </div>
                <!--end chapters wrapper-->
                <div class="chapters-navigation">
                    <hr/>

<!-- Start Pagination -->
<?php
// Set up paginated links.
    $big = 999999999; // need an unlikely integer
    $links = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $total_number_of_pages,
        'prev_text' => wp_kses(__('<i class=\'fas fa-chevron-left\'></i>', 'toocheke'), array('i' => array('class' => array()))),
        'next_text' => wp_kses(__('<i class=\'fas fa-chevron-right\'></i>', 'toocheke'), array('i' => array('class' => array()))),
    ));

    if ($links):

    ?>

<div class="paginate-links">

         <?php echo wp_kses($links, array(
        'a' => array(
            'href' => array(),
            'class' => array(),
        ),
        'i' => array(
            'class' => array(),
        ),
        'span' => array(
            'class' => array(),
        ),
    )); ?>

     </div><!--/ .navigation -->
 <?php
endif;
    ?>
<!-- End Pagination -->
                    </div>
                    <!--end chapters-navigation-->
                <!-- END COMIC CHAPTER LIST-->
                <?php
}