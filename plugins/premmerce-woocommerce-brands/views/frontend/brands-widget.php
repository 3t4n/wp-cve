<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?= $args['before_widget']; ?>

<?php if ($title) : ?>
    <?= $args['before_title'] . $title . $args['after_title']; ?>
<?php endif ?>

<div class="prm-brands-widget">
    <?php foreach ($brands as $brand) : ?>
        <a class="prm-brands-widget__item" href="<?= get_term_link($brand->slug, 'product_brand'); ?>">
            <?php if ($imageURL = wp_get_attachment_image_url(get_term_meta($brand->term_id, 'thumbnail_id', true), 'thumbnail')) : ?>
                <img class="prm-brands-widget__image" src="<?= $imageURL; ?>">
            <?php endif ?>

            <div class="prm-brands-widget__title">
                <?= $brand->name ?>
            </div>
        </a>
    <?php endforeach ?>
</div>

<?= $args['after_widget']; ?>
