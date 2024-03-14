<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div>
    <div>
        <div class="emtmpl-option-label"><?php esc_html_e( 'Preview', '9mail-wp-email-templates-designer' ); ?></div>
        <div class="emtmpl-btn-group vi-ui buttons">
            <button type="button" class="emtmpl-preview-email-btn desktop vi-ui button mini attached"
                    title="<?php esc_html_e( 'Preview width device screen width > 380px', '9mail-wp-email-templates-designer' ); ?>">
                <i class="dashicons dashicons-laptop"> </i>
            </button>
            <button type="button" class="emtmpl-preview-email-btn mobile vi-ui button mini attached"
                    title="<?php esc_html_e( 'Preview width device screen width < 380px', '9mail-wp-email-templates-designer' ); ?>">
                <i class="dashicons dashicons-smartphone"> </i>
            </button>
        </div>
    </div>

    <div>
        <div class="emtmpl-option-label"><?php esc_html_e( 'Send to', '9mail-wp-email-templates-designer' ); ?></div>
        <div class="emtmpl-flex">
            <input type="text" class="emtmpl-to-email" value="<?php echo esc_html( get_bloginfo( 'admin_email' ) ) ?>">
            <button type="button" class="emtmpl-send-test-email-btn vi-ui button mini attached"
                    title="<?php esc_html_e( 'Send test email', '9mail-wp-email-templates-designer' ); ?>">
                <i class="dashicons dashicons-email"> </i>
            </button>
        </div>
        <div class="emtmpl-send-test-email-result"></div>
    </div>
</div>

<div class="vi-ui longer modal ">
    <i class="icon close dashicons dashicons-no-alt"></i>

    <div class="header">
		<?php esc_html_e( 'Preview', '9mail-wp-email-templates-designer' ); ?>
        <div class="emtmpl-view-btn-group vi-ui buttons">
            <button class="vi-ui button mini emtmpl-pc-view attached">
                <i class="dashicons dashicons-laptop "
                   title="<?php esc_html_e( 'Desktop & mobile (width >380px)', '9mail-wp-email-templates-designer' ); ?>"></i>
            </button>
            <button class="vi-ui button mini emtmpl-mobile-view attached">
                <i class="dashicons dashicons-smartphone"
                   title="<?php esc_html_e( 'View mobile version (width < 380px)', '9mail-wp-email-templates-designer' ); ?>"></i>
            </button>
            <button class="vi-ui button mini emtmpl-send-test-email-btn attached">
                <i class="dashicons dashicons-email "
                   title="<?php esc_html_e( 'Send test email', '9mail-wp-email-templates-designer' ); ?>"></i>
            </button>
        </div>
    </div>

    <div class="content scrolling">
        <div class="emtmpl-email-preview-content">

        </div>
    </div>

    <div class="actions">

    </div>
</div>
