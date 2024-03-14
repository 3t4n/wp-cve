<?php
/**
 * @package Admin
 * @sub-package Navigation
 */
 ?>
<h2 class="nav-tab-wrapper">
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="dashboard-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools' ); ?>"><?php esc_html_e( 'Dashboard', 'catch-web-tools' ); ?></a>
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-webmasters' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="webmaster-tool-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-webmasters' ); ?>"><?php esc_html_e( 'Webmaster Tools', 'catch-web-tools' ); ?></a>
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-catch-ids' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="catch-ids-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-catch-ids' ); ?>"><?php esc_html_e( 'Catch IDs', 'catch-web-tools' ); ?></a>
    <?php if ( !function_exists( 'wp_update_custom_css_post' ) ) { ?>
        <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-custom-css' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="custom-css-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-custom-css' ); ?>"><?php _e( 'Custom CSS', 'catch-web-tools' );?></a>
    <?php } ?>
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-social-icons' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="social-icons-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-social-icons' ); ?>"><?php esc_html_e( 'Social Icons', 'catch-web-tools' ); ?></a>
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-opengraph' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="opengraph-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-opengraph' ); ?>"><?php esc_html_e( 'Open Graph', 'catch-web-tools' ); ?></a>
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-seo' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="seo-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-seo' ); ?>"><?php esc_html_e( 'SEO', 'catch-web-tools' ); ?></a>
    <a class="nav-tab <?php echo ( isset( $_GET['page'] ) && 'catch-web-tools-to-top' == $_GET['page'] )? 'nav-tab-active':'' ?>" id="to-top-tab" href="<?php echo admin_url( 'admin.php?page=catch-web-tools-to-top' ); ?>"><?php esc_html_e( 'To Top', 'catch-web-tools' ); ?></a>
</h2>
