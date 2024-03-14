<?php
if(Wpil_Base::show_review_notice()){?>
    <div class="wpil-review-offer notice notice-info is-dismissible">
        <img class="email-signup-logo" src="<?php echo esc_url(WP_INTERNAL_LINKING_PLUGIN_URL . 'images/lw-icon.png'); ?>">
        <span class="wpil-review-shoutout"><?php _e('You\'ve been using Link Whisper Free for a while now, would you mind giving us a review? It would mean a lot to us and would encourage us in development.', 'wpil'); ?> 😊</span>
        <div class="wpil-review-action-panel">
            <a id="wpil-review-plugin" href="https://wordpress.org/support/plugin/link-whisper/reviews/#new-post" class="button-primary notice-perm-dismiss"><?php _e('Absolutely!', 'wpil'); ?></a>
            <a id="wpil-dont-review-plugin" href="#" class="button-primary notice-perm-dismiss"><?php _e('No, I don\'t think so.', 'wpil'); ?></a>
            <a id="wpil-review-plugin-later" href="#" class="button-primary notice-temp-dismiss"><?php _e('Maybe later.', 'wpil'); ?></a>
        </div>
    </div>
    <?php
}
?>
