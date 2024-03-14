<?php
global $action, $page, $rsc;
wp_reset_vars( array( 'action', 'page' ) );
$page = sanitize_key( $_GET[ 'page' ] );

$tab = ( isset( $_GET[ 'tab' ] ) ) ? sanitize_key( $_GET[ 'tab' ] ) : '';
if ( empty( $tab ) ) {
    if ( rsc_iw_is_wl() ) {
        $tab = 'general';

    } else {
        $tab = 'welcome';
    }
}

$general_settings = new RC_Settings_General();
$section = $general_settings->get_settings_sections( $tab );
$section = isset( $section[ 0 ] ) ? $section[ 0 ] : array();

$menus = array();

if ( ! rsc_iw_is_wl() ) {
    $menus[ 'welcome' ] = __( 'Welcome', 'rsc' );
}

$menus[ 'general' ] = __( 'General', 'rsc' );

if ( class_exists( 'TC' ) ) {
    $menus[ 'tickera' ] = __( 'Tickera', 'rsc' );
}

if ( class_exists( 'WooCommerce' ) ) {
    $menus[ 'woocommerce' ] = __( 'WooCommerce', 'rsc' );
}

if ( class_exists( 'Easy_Digital_Downloads' ) ) {
    $menus[ 'edd' ] = __( 'Easy Digital Downloads', 'rsc' );
}

$menus = apply_filters( 'rc_settings_new_menus', $menus );

$rsc_heading_class = '';
if ( $tab !== 'welcome' ) {
    $rsc_heading_class = 'rsc-bold-heading';
}
?>
<div class="wrap rsc_outside_wrap nosubsub rsc_outside_<?php echo esc_attr( $tab ); ?>">
    <h1></h1>
    <div class="icon32 icon32-posts-page" id="icon-options-general"><br></div>
    <div class="rsc-settings-header">
        <?php if ( ! rsc_iw_is_wl() ) { ?>
            <div class="rcs-restrict-logo">
                <img src="<?php echo esc_url( $rsc->plugin_url ); ?>assets/images/restrict-logo@2x.png" width="70"/>
                <div class="rsc-version">v <?php echo rsc_esc_html( $rsc->version ); ?></div>
            </div>
        <?php } ?>
        <div class="rsc-header-title">
            <h1 class="<?php echo esc_attr( $rsc_heading_class ); ?>"><?php echo isset( $section[ 'title' ] ) ? rsc_esc_html( $section[ 'title' ] ) : ''; ?></h1>
            <span><?php echo isset( $section[ 'subtitle' ] ) ? rsc_esc_html( $section[ 'subtitle' ] ) : ''; ?></span>
        </div>
        <?php if ( ! rsc_iw_is_wl() ) { ?>
            <div class="rsc-documentation-link">
                <a href="<?php echo esc_attr( rsc_get_documentation_link( $tab ) ); ?>" target="_blank"
                   class="rsc-documentation-button"><?php echo rsc_esc_html( sprintf( __( 'View %s Documentation', 'rsc' ), ( isset( $menus[ $tab ] ) && ! empty( $menus[ $tab ] ) && $tab !== 'welcome' ) ? $menus[ $tab ] : '' ) ); ?></a>
            </div>
        <?php } ?>
    </div><!-- .rc-settings-header -->
    <?php
    if ( isset( $_POST[ 'submit' ] ) ) { ?>
        <div id="message" class="updated fade"><p><?php _e( 'Settings saved successfully.', 'rsc' ); ?></p></div><?php
    }
    if ( version_compare( phpversion(), '5.3', '<' ) ) { ?>
        <div id="rsc_php_53_version_error" class="error" style="">
            <p><?php echo sprintf( __( 'Your current version of PHP is %s and recommended version is at least 5.3. You should contact your hosting company and %sask for upgrade%s.', 'rsc' ), phpversion(), '<a href="https://wordpress.org/about/requirements/">', '</a>' ) ?></p>
        </div><?php
    } ?>
    <div class="rsc-nav-tab-wrapper">
        <div class="rsc-nav-inside">
            <ul>
                <?php foreach ( $menus as $key => $menu ) {
                    $tab_url = add_query_arg( array(
                        'page' => $page,
                        'tab' => $key,
                    ), admin_url( 'admin.php' ) );
                    ?>
                    <li>
                        <a class="nav-tab<?php if ( $tab == $key ) echo ' nav-tab-active'; ?>" href="<?php echo esc_url( $tab_url ); ?>"><?php echo rsc_esc_html( $menu ); ?></a>
                    </li>
                <?php } ?>
            </ul>
            <div class="rc-clear"></div>
        </div>
        <?php if ( ! rsc_iw_is_wl() ) { ?>
            <div class="rsc-review-wrap">
                <span><?php _e( 'Review Restrict at WordPress.org', 'rsc' ); ?></span>
                <a href="https://wordpress.org/support/plugin/restricted-content/reviews/#new-post" target="_blank" class="rsc-small-button"><?php _e( 'Add a Review', 'rsc' ); ?></a>
            </div><!-- .rsc-review-wrap -->
            <div class="rsc-documentation-link">
                <a href="https://restrict.io/documentation/" target="_blank" class="rsc-documentation-button"><?php _e( 'View Documentation', 'rsc' ); ?></a>
            </div>
        <?php } ?>
    </div>
    <?php $rsc->rsc_show_tabs( $tab ); ?>
</div>
