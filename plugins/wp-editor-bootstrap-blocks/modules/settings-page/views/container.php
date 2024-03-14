<?php
/**
 * Bootstrap Blocks for WP Editor Settings page.
 *
 * @package Bootstrap Blocks for WP Editor
 *  @version 1.0.1
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'GtbBootstrapSettingsPage', false ) ) exit;

require_once dirname( __FILE__ ) . '/../formfields.php';
global $gtb_options;

?>
<div class="gtb-header"><div class="logo"></div></div>
<div class="gtb-payoff"><?php _e( 'The essential WP Editor Plugin for Bootstrap websites', 'gtb-auth' ); ?></div>
<div class="gtb-wrap">
	<h1><?php _e('Bootstrap Blocks for WP Editor',GUTENBERGBOOTSTRAP_SLUG)?> <small><?php _e( 'version', 'gtb-auth' ); ?> <?php echo GUTENBERGBOOTSTRAP_VERSION; ?></small>
	<?php if ( ! defined( 'GTBBOOTSTRAP_DESIGN_LC' ) ) : ?>
	<span class="version-label"><?php _e( 'Free version', 'gtb-auth' ); ?></span>
<?php else : ?>
	<span class="version-label green"><?php _e( 'Design Package', 'gtb-auth' ); ?></span>
<?php endif; ?>
</h1>
<?php if(is_network_admin()): ?>
	<?php _e('You can only manage your preferences per site.',GUTENBERGBOOTSTRAP_SLUG); ?>
	<?php else: ?>
	<form method="post" action="?page=<?php echo GtbBootstrapSettingsPage::MENU_SLUG; ?>">
    <?php wp_nonce_field('update_gtb','wpnonce_gtb'); ?>
		<p>
        <?php _e('If you\'re familiar with Bootstrap, you can manage your Bootstrap Blocks for WP Editor preferences below.',GUTENBERGBOOTSTRAP_SLUG); ?>
		</p>
		<div class="nav-tab-wrapper"></div>
		<div class="spacer"></div>
		<div class="sections-wrapper">
		<?php
			// This prints out all hidden setting fields.
            settings_fields( 'gtbbootstrap_option_group' );
            GtbBootstrapSettingsPage::print_sections();
		?>
		</div>
		<div class="clear"></div>
	</form>
<?php endif; ?>
</div>