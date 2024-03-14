<?php if ( ! defined( 'WPINC' ) ) die; ?>

<span class="product-brand tagged_as">
    <?php _e('Brand', 'premmerce-brands'); ?>:

    <a href="<?= get_term_link($brand->slug, 'product_brand'); ?>"><?= $brand->name; ?></a>
</span>