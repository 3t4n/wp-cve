<ul class="wpmstabs wpms-nav-tab-wrapper">
    <!--Google Analytcis Tracking-->
    <li class="tab wpmstab col" style="min-width: 300px">
        <?php
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        if (empty($_GET['view'])) {
            ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=metaseo_google_analytics')) ?>" class="active">
                <?php esc_html_e('Google Analytics Tracking', 'wp-meta-seo') ?>
            </a>
            <?php
            echo '<div class="indicator" style="bottom: 0; left: 0;width:100%"></div>';
        } else {
            ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=metaseo_google_analytics')) ?>">
                <?php esc_html_e('Google Analytics Tracking', 'wp-meta-seo') ?>
            </a>
            <?php
        }
        ?>
    </li>
    <!--End Google Analytcis Tracking-->
    <!--Google Analytcis Data-->
    <li class="tab wpmstab col" style="min-width: 300px">
        <?php
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        if (isset($_GET['view']) && $_GET['view'] === 'wpms_gg_service_data') {
            ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data')) ?>"
               class="active">
                <?php esc_html_e('Google Analytics Data', 'wp-meta-seo') ?>
            </a>
            <?php
            echo '<div class="indicator" style="bottom: 0; left: 0;width:100%"></div>';
        } else {
            ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data')) ?>">
                <?php esc_html_e('Google Analytics Data', 'wp-meta-seo') ?>
            </a>
            <?php
        }
        ?>
    </li>
    <!--End Google Analytcis Data-->
</ul>