<section class="ms-default-stories">
    <h3><?php echo $term->name; ?></h3>
    <div class="ms-stories-group">
        <?php
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
        wp_reset_postdata();
        ?>
    </div>
</section>