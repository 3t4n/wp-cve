<div class="md:flex font-sans gap-4">
    <?php
        $featuredImage = get_the_post_thumbnail_url($fab_item->getID(), 'large');
        if($featuredImage):
    ?>
        <div class="featured-img-container flex-none w-1/2 relative">
            <img src="<?php echo esc_url($featuredImage); ?>"
                 alt="<?php echo $fab_item->getTitle() ?> Cover"
            />
        </div>
    <?php endif; ?>
    <div class="flex-auto">
        <?php echo do_shortcode( $content ); ?>
    </div>
</div>
<style>
    .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-box,
    .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .fab-modal-content {
        overflow: visible !important;
    }
</style>