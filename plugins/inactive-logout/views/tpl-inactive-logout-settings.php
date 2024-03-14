<div class="ina-settings-header">
    <div class="ina-settings-header--notices">
        <h1 style="display: none;">&nbsp;</h1>
		<?php do_action( 'ina_notices' ); ?>
    </div>
	<?php if ( ! \Codemanas\InactiveLogout\Helpers::get_option( 'ina_dismiss_like_notice' ) && ! \Codemanas\InactiveLogout\Helpers::is_pro_version_active() ) { ?>
        <div class="ina-settings-header--top ina-logout-like-dismiss-wrapper">
        <span>
		    <?php
		    printf( esc_html__( 'You\'re using Inactive Logout Free. To unlock more features, consider %1$sUpgrading to Pro%2$s', 'inactive-logout' ), '<a href="https://inactive-logout.com/buy/" target="_blank">', '</a>' );
		    ?>
            <a href="javascript:void(0);" id="ina-logout-like-dismiss"><span class="dashicons dashicons-no"></span></a>
        </span>
        </div>
	<?php } ?>
    <div class="ina-settings-header--inside">
        <div class="logo">
            <img src="<?php echo INACTIVE_LOGOUT_DIR_URI . "public/images/logo-purple-300x135.png"; ?>" alt="Logo"/>
        </div>
        <span class="separator">/</span>
        <span><?php esc_html_e( 'Settings', 'inactive-logout' ); ?></span>
    </div>
</div>

<?php
$message = \Codemanas\InactiveLogout\Helpers::getMessage();
if ( ! empty( $message ) ) {
	?>
    <div class="ina-toast visible" id="ina-toast">
        <div class="ina-toast-body">
            <span class="dashicons dashicons-yes-alt" style="color:#008000;"></span> <?php echo $message; ?>
        </div>
    </div>
	<?php
}
?>

<?php
$multi_role_enabled = \Codemanas\InactiveLogout\Helpers::get_option( '__ina_enable_timeout_multiusers' );
?>
<div class="ina-settings-nav-wrapper">
    <a href="?page=inactive-logout&tab=ina-basic" class="nav-tab-custom <?php echo ( ! empty( $active_tab ) && 'ina-basic' === $active_tab ) ? esc_attr( 'nav-tab-active' ) : ''; ?>">
        <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'General Settings', 'inactive-logout' ); ?>
    </a>
    <a href="?page=inactive-logout&tab=ina-advanced" class="nav-tab-custom <?php echo ( ! empty( $active_tab ) && 'ina-advanced' === $active_tab ) ? esc_attr( 'nav-tab-active' ) : ''; ?>">
        <span class="dashicons dashicons-admin-users"></span> <?php esc_html_e( 'Role Based Settings', 'inactive-logout' ); ?><?php echo ! empty( $multi_role_enabled ) ? ' <span class="dashicons dashicons-yes-alt" style="color:#008000;"></span>' : ''; ?>
    </a>
	<?php do_action( 'ina_settings_page_tabs_before' ); ?>
    <a href="?page=inactive-logout&tab=ina-support" class="nav-tab-custom <?php echo ( ! empty( $active_tab ) && 'ina-support' === $active_tab ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><span class="dashicons dashicons-admin-comments"></span> <?php esc_html_e( 'Support', 'inactive-logout' ); ?></a>
	<?php if ( ! \Codemanas\InactiveLogout\Helpers::is_pro_version_active() ) { ?>
        <a href="http://inactive-logout.com/" target="_blank" class="nav-tab-custom"><span style="color:#ffa500;" class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Go Pro', 'inactive-logout' ); ?></a>
	<?php } ?>
	<?php do_action( 'ina_settings_page_tabs_after' ); ?>
</div>