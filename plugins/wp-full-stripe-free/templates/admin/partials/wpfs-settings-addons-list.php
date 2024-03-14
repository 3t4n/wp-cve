<?php
    /** @var $addOnData */
    /** @var $pluginsUrl */
    /** @var $addOnsExploreUrl */
?>
<div class="wpfs-form">
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <div class="wpfs-typo-h2 wpfs-typo-h2--mb-24"><?php esc_html_e('Select an add-on to configure', 'wp-full-stripe-admin'); ?></div>
            <div class="wpfs-add-ons-list">
                <?php
                foreach ( $addOnData as $addOn ) {
                ?>
                <a href="<?php echo $addOn['settingsUrl']; ?>" class="wpfs-add-ons-list__item">
                    <div class="wpfs-add-ons-list__icon">
                        <img class="wpfs-addon-icon" src="<?php echo $addOn['iconUrl']; ?>">
                    </div>
                    <div class="wpfs-add-ons-list__title"><?php esc_html_e( $addOn['name'] ); ?></div>
                </a>
                <?php
                }
                ?>
            </div>
            <div class="wpfs-typo-body">
                <?php esc_html_e('Activate, deactivate, and delete your add-ons like any other WordPress plugin on your site.', 'wp-full-stripe-admin'); ?>&nbsp;<a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold" href="<?php echo $pluginsUrl; ?>"><?php esc_html_e('Manage WordPress plugins', 'wp-full-stripe-admin'); ?></a>
            </div>
        </div>
        <div class="wpfs-form__col">
            <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                <div class="wpfs-inline-message__inner">
                    <div class="wpfs-inline-message__icon">
                        <span class="wpfs-icon-add-ons"></span>
                    </div>
                    <div class="wpfs-inline-message__title wpfs-inline-message__title--bigger"><?php esc_html_e('Grow your business with your favorite tools', 'wp-full-stripe-admin'); ?></div>
                    <p><?php esc_html_e('Use add-ons to natively connect to tools such as email automation systems, affiliate- and membership applications. ', 'wp-full-stripe-admin'); ?></p>
                    <p>
                        <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold" href="<?php echo $addOnsExploreUrl; ?>" target="_blank"><?php esc_html_e('Explore add-ons', 'wp-full-stripe-admin'); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
