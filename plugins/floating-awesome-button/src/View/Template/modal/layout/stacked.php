<div class="font-sans">
    <?php
        $featuredImage = get_the_post_thumbnail_url($fab_item->getID(), 'large');
        if($featuredImage):
    ?>
        <div class="block">
            <img src="<?php echo esc_url($featuredImage); ?>" alt="<?php echo $fab_item->getTitle() ?> Cover" class="w-full h-full rounded-lg" />
        </div>
    <?php endif; ?>
    <div class="block">
        <?php echo do_shortcode( $content ); ?>
    </div>
</div>
