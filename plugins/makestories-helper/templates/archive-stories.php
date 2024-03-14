<?php get_header();
$default_posts_per_page = get_option( 'posts_per_page' );
$getAjaxUrl =  admin_url('admin-ajax.php');
$postCount = wp_count_posts(MS_POST_TYPE);
$postCount = $postCount->publish;
$config = ms_get_options();

$design = isset($config['ms_design']) ? $config['ms_design'] : "1";
$heading = isset($config['ms_heading']) ? $config['ms_heading'] : "";
$content = isset($config['ms_content']) ? $config['ms_content'] : "";
?>
<section class="ms-default-stories">
    <?php if ($postCount > 0) { ?>
    <?php if ($design == "2" && (!empty($heading) || !empty($content))) { ?>
    <div class="story-heading">
        <h2><?php echo $heading; ?></h2>
        <p><?php echo $content; ?></p>
    </div>
    <?php }?>
    <div id="ajax-posts" class="ms-stories-group row" data-posts="<?php echo esc_attr($default_posts_per_page); ?>" data-ajax="<?php echo esc_attr($getAjaxUrl); ?>" class="row">
        <?php
        $postsPerPage = $default_posts_per_page;

        $args = [
            'post_type' => MS_POST_TYPE,
            'posts_per_page' => $postsPerPage,
            'post_status' => 'publish',
        ];

        $loops = get_posts($args);
        $postChunks = array_chunk($loops,8);
        foreach($postChunks as $key=>$value) {
            ?>
            <div class="ms-grid stories-showcase-block <?php if ($design == "2") { echo "design-2"; } else { echo "design-1"; } ?>" id="listing-grid" data-design="<?php echo $design; ?>">
            <?php
                foreach($value as $index=>$post) {
                    include mscpt_getTemplatePath("prepare-story-vars.php");
                    if ($design == "2") {
                        include mscpt_getTemplatePath("listing-story-grid.php");
                    } else {
                        include mscpt_getTemplatePath("listing-story-masonry.php");
                    }
                }
            ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="d-one" style="display: none;"></div>
        <?php if ($postCount > $postsPerPage) { ?>
        <div class="ms-load-more-wrap">
            <span id="ms-loading-spinner"></span>
            <a id="more_posts">Load More</a>
        </div>
    <?php
        }
    ?>
    <div id="script-block">
        <?php require_once mscpt_getTemplatePath("story-player-model.php"); ?>
    </div>
    <?php } else { ?>
            <p class="no-stories">No Stories Found</p>
    <?php } ?>
</section>

<?php get_footer(); ?>