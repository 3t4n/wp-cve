<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $this->settings;

?>

<noscript>
	<div class='updated'>
		<p class="error"><?php _e('JavaScript appears to be disabled in your browser. For this plugin to work correctly, please enable JavaScript or switch to a more modern browser.', 'clp-custom-login');?></p>
	</div>
</noscript>



<div class="wrap clp-custom-login content-settings">
	
	<div id="icon-options-general" class="icon32">
		<br />
	</div>

	<div class="settings-wrap">
        <form method="post"	action="admin.php?page=clp-settings&status=settings-saved" id="clp-options">
            
            <?php wp_nonce_field('save_options','save_options_field'); ?>

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab active" href="<?php echo admin_url(); ?>admin.php?page=clp-settings#basic" data-tab="clp-basic-settings">
                    <span class="dashicons dashicons-admin-generic"></span> <?php _e('Basic Settings', 'clp-custom-login');?>
                </a>

                <?php do_action('clp_add_settings_tab'); ?>

                <a class="nav-tab login-preview" href="<?php echo esc_url( wp_login_url() ); ?>" target="_blank">
                    <span class="dashicons dashicons-external"></span> <?php _e('Preview', 'clp-custom-login');?>
                </a>

                <?php if (!class_exists( 'CLP_Custom_Login_Page_Pro' )): ?>
                <a class="nav-tab get-pro" href="https://customloginpage.com" target="_blank">
                    <span class="dashicons dashicons-megaphone"></span> <?php _e('GET PRO VERSION', 'clp-custom-login');?>
                </a>
                <?php endif; ?>

            </h2>

            <div class="clp-settings-wrapper">

                <div class="clp-inputs-wrapper" data-settings="clp-basic-settings">
                    <?php 
                    // Basic Settings
                    if ( file_exists(CLP_PLUGIN_DIR . 'includes/admin/setings-basic.php' ) ) {
                        require ( CLP_PLUGIN_DIR . 'includes/admin/setings-basic.php' );
                    } ?>

                </div> <!-- <div class="clp-settings-wrapper"> -->

                <?php do_action('clp_add_settings_table'); ?>

            </div> <!-- <div class="clp-inputs-wrapper"> -->

        </form>

        <?php 
        // get sidebar with "widgets"
        if ( file_exists(dirname(__FILE__) . '/sidebar.php') ) {
            require (dirname(__FILE__) . '/sidebar.php');
        }
        ?>
	</div>

</div> <!-- <div id="wrap"> -->

<?php 

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $serizalized_settings = maybe_serialize( $settings );
    update_option( 'clp_settings', $serizalized_settings);
}