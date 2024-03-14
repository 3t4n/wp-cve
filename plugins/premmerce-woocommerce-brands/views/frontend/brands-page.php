<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?php if(! empty($brands) ): ?>
<div class="prm-brands-list">
    <?php foreach ($brands as $brand) :
        $imageURL = wp_get_attachment_image_url(get_term_meta($brand->term_id, 'thumbnail_id', true), 'medium');
        $imageURL = $imageURL ?: plugins_url('../../assets/frontend/img/placeholder.png', __FILE__);
        ?>
        <div class="prm-brands-list__col">
            <a class="prm-brands-list__item" href="<?= get_term_link($brand->slug, 'product_brand'); ?>">
                <div class="prm-brands-list__image">
                    <img src="<?= $imageURL; ?>" alt="<?= $brand->name; ?>">
                </div>
                <div class="prm-brands-list__title"><?= $brand->name; ?></div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <p class="woocommerce-info"><?php _e('No brands ware found.', 'premmerce-brands'); ?></p>
<?php endif;?>    