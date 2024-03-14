<div class="font-sans gap-4" style="">
    <?php echo do_shortcode( $content ); ?>
</div>
<style>
    .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-box,
    .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .fab-modal-content {
        overflow: visible !important;
    }
    .jconfirm-fab-modal-<?php echo esc_attr( $fab_item->getID() ); ?> .jconfirm-content-pane {
        <?php
            $featuredImage = get_the_post_thumbnail_url($fab_item->getID(), 'large');
            echo ($featuredImage) ? esc_attr( sprintf('background: url(%s) repeat center center;', $featuredImage) ) : '';
        ?>
    }
</style>