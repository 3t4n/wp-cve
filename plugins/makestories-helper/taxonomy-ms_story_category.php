<?php 
get_header(); 
$category = get_queried_object();
$category_id = $category->term_id;
$int_cat = (int)$category_id;

$args = [
    'post_type' => MS_POST_TYPE,
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'offset'=> 0,
    "tax_query" => array(
        array(
            "taxonomy" => MS_TAXONOMY,
            "field" => "term_id",
            "terms" =>  $int_cat
        ))
];

$loop = new WP_Query($args);
?>
<section class="ms-default-stories">
    <h3><?php $term = get_term($int_cat,MS_TAXONOMY); echo esc_html($term->name); ?></h3>
    <div class="ms-stories-group">
        <?php
        while ($loop->have_posts()) : $loop->the_post();
            include mscpt_getTemplatePath("prepare-story-vars.php");
            include mscpt_getTemplatePath("single-story.php");
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
</section>
<?php get_footer(); ?>