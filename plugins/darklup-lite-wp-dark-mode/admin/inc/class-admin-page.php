<?php
namespace DarklupLite;
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
 
class Admin_Page_Components {

	public static function logo() {
		?>
<div class="darkluplite-logo">
    <div class="darkluplite-logo-inner">
        <img class="logo-light" src="<?php echo esc_url( DARKLUPLITE_DIR_ADMIN_ASSETS_URL.'img/darklup-logo.png' ); ?>"
            alt="<?php esc_attr_e( 'plugin logo', 'darklup-lite' ); ?>">
        <img class="logo-dark"
            src="<?php echo esc_url( DARKLUPLITE_DIR_ADMIN_ASSETS_URL.'img/daklup-dark-logo.png' ); ?>"
            alt="<?php esc_attr_e( 'plugin logo', 'darklup-lite' ); ?>">
        <span class="darkluplite--version">
            <span class="darklup-version-inner">
                <?php echo DARKLUPLITE_VERSION; ?>
            </span>
        </span>
    </div>

</div>
<?php
	}

	public static function formArea() {
		?>
<div class="darkluplite-admin-wrap">
    <form class="admin-darklup" method="post" action="options.php">
        <?php
            $settingsGorup = 'darkluplite-settings-group';
            $nonce = wp_create_nonce( 'darluplitenonce' );
            settings_fields( $settingsGorup ); 
            do_settings_sections( $settingsGorup );
        ?>
        
        <input type="hidden" id="darluplitenonce" name="darluplitenonce" value="<?php echo $nonce; ?>">
        <div class="darkluplite-main-area darkluplite-admin-settings-area">
            <div class="darkluplite-row">
                <div class="darkluplite-col-sm-3 darkluplite-col-md-3 darkluplite-col-12 padding-0 darkluplite-menu-column">
                    
                    <div class="darkluplite-menu-area">
                        <?php
                        self::logo();
                        // Tab menu
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-tabs.php';
                        ?>
                    </div>
                </div>

                <div class="darkluplite-col-sm-9 darkluplite-col-md-9 darkluplite-col-12 padding-0 darkluplite-content-column">
                    <div class="darkluplite-settings-area darkluplite-admin-dark-ignore">
                    <?php
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-home-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-advance-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-style-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-color-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-image-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-woocommerce-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-inc-exc-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-trigger-settings.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-custom-css.php';
                        require DARKLUPLITE_DIR_ADMIN .'admin-templates/template-analytics-settings.php';

                    ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
	}

}