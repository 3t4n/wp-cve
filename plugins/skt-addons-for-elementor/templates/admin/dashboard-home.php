<?php
/**
 * Dashboard home tab template
 */

defined( 'ABSPATH' ) || die();
?>
<div class="skt-dashboard-panel">
    <div class="skt-home-banner-row">
        <div class="skt-home-banner"><img src="<?php echo esc_url(SKT_ADDONS_ELEMENTOR_ASSETS); ?>imgs/admin/skt-addons-banner.jpg" alt=""></div>
        <div class="skt-home-banner"><a href="https://www.sktthemes.org/themes/" target="_blank"><img src="<?php echo esc_url(SKT_ADDONS_ELEMENTOR_ASSETS); ?>imgs/admin/all-themes-banner.jpg" alt=""></a></div>
    </div>
    <div class="skt-home-body">
        <div class="skt-row skt-py-5 skt-align-items-center">
            <div class="skt-col skt-col-6">
                <i aria-hidden="true" class="skti skti-code-browser" style="color: #282828; font-size: 42px;"></i>
                <h3 class="skt-feature-title"><?php esc_html_e('Documentation', 'skt-addons-elementor'); ?></h3>
                <p class="f18"><?php esc_html_e('We have created documentation for you. It will help you to understand how our plugin works.', 'skt-addons-elementor'); ?></p>
                <a class="skt-btn skt-btn-primary" target="_blank" rel="noopener" href="https://sktthemesdemo.net/documentation/skt-addons-elementor-documentation"><?php esc_html_e('View Documentation', 'skt-addons-elementor'); ?></a>
            </div>
            <div class="skt-col skt-col-6">
                <img class="skt-img-fluid" src="<?php echo esc_url(SKT_ADDONS_ELEMENTOR_ASSETS); ?>imgs/admin/documentation-img.png" alt="">
            </div>
        </div>
    </div>
</div>