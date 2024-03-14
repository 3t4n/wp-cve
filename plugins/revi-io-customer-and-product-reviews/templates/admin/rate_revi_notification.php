<div id="message" class="updated notice is-dismissible revi-review really-simple-plugins" style="border-left:4px solid #333">
    <div class="revi-container" style="display:flex">
        <div class="revi-review-image" style="padding:20px 10px"><img width="80px" src="https://revi.io/assets/images/logos/logo-revi.svg" alt="review-logo">
        </div>
        <div style="margin-left:30px">
            <p>
                <?= esc_html_e('Congratulations to our newest family member! But wait...to help us keep growing this family, please leave a review and tell us about your experience.','revi-io-customer-and-product-reviews') ?>
            </p>
            <div class="revi-buttons-row">
                <a class="button button-primary" target="_blank" href="https://es.wordpress.org/plugins/revi-io-customer-and-product-reviews/"><?= esc_html_e('Leave your comment here','revi-io-customer-and-product-reviews') ?></a>

                <div class="dashicons dashicons-no-alt"></div>
                <a href="<?= add_query_arg('revi_dismiss_notification', 'true' ) ?>"><?= esc_html_e('Do not show again', 'revi-io-customer-and-product-reviews') ?></a>
            </div>
        </div>
    </div>
<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?= esc_html_e( 'Dismiss this notice' , 'revi-io-customer-and-product-reviews') ?></span></button></div>
