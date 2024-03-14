<?php
    /** @var $addOnsExploreUrl string */
?>
<div class="wpfs-empty-state">
    <div class="wpfs-empty-state__icon">
        <span class="wpfs-icon-add-ons"></span>
    </div>
    <div class="wpfs-empty-state__title"><?php esc_html_e( 'No add-ons added yet.', 'wp-full-stripe-admin' ); ?></div>
    <div class="wpfs-empty-state__message"><?php esc_html_e( 'Grow your business with your favorite tools.', 'wp-full-stripe-admin' ); ?></div>
    <a class="wpfs-btn wpfs-btn-primary" href="<?php echo $addOnsExploreUrl; ?>" target="_blank"><?php esc_html_e( 'Explore add-ons', 'wp-full-stripe-admin' ); ?></a>
</div>
