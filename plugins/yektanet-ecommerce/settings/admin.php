<?php
defined( 'ABSPATH' ) || exit;

// create sub menu in admin settings
function yektanet_create_settings_menu() {
	add_submenu_page(
		'options-general.php',
		__( 'yektanet', 'yektanet-ecommerce' ),
		__( 'yektanet', 'yektanet-ecommerce' ),
		'activate_plugins',
		'yektanet_settings.php',
		'yektanet_settings_function'
	);
}

add_action( 'admin_menu', 'yektanet_create_settings_menu' );


// this function create page for setting
function yektanet_settings_function() {
	if ( isset( $_POST['submit'] ) ) {
		yektanet_process_admin_setting_form();
	}
	?>
    <h3>
		<?php echo __( 'yektanet settings', 'yektanet-ecommerce' ); ?>
    </h3>
    <form method="post" action="">
        <div class="yektanet__setting__main__div">
            <div>
                <label for="yektanet_app_id"
                       class="yektanet__settings__field__title"><?php echo __( 'yektanet app id', 'yektanet-ecommerce' ); ?></label>
                <input type="text" name="yektanet_app_id" class="yektanet__settings__field__input"
                       value="<?php echo get_option( 'yektanet_app_id', true ); ?>">
            </div>
        </div>
        <input type="submit" name="submit" value="<?php echo __( 'yektanet save setting data', 'yektanet-ecommerce' ); ?>"
               class="yektanet__setting__submit__btn button button-primary button-large">
    </form>
	<?php
}
