<div class="sm:flex font-sans gap-4">
    <?php
        $featuredImage = get_the_post_thumbnail_url($fab_item->getID(), 'large');
        if($featuredImage):
    ?>
        <div class="featured-img-container flex-none relative">
            <img src="<?php echo esc_url($featuredImage); ?>"
                 alt="<?php echo $fab_item->getTitle() ?> Cover"
                 class="w-full sm:w-auto"
            />
        </div>
    <?php endif; ?>
    <div class="flex-auto">
        <?php echo do_shortcode( $content ); ?>
    </div>
</div>
