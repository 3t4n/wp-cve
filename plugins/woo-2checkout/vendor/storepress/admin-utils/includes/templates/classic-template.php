<?php

	/**
	 * StorePress Settings Template.
	 *
	 * @global \StorePress\AdminUtils\Settings $this - Settings Class Instance.
	 */

	defined( 'ABSPATH' ) || die( 'Keep Silent' );

?>
<div class="wrap storepress-settings-wrapper">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<?php
		$this->display_settings_messages();
	?>

	<nav class="nav-tab-wrapper storepress-nav-tab-wrapper" aria-label="Secondary menu">
		<?php $this->display_tabs(); ?>
	</nav>

	<div class="storepress-settings-content-wrapper">
		<div class="storepress-settings-main">
			<?php if ( $this->has_save_button() ) : ?>
				<form action="<?php echo esc_url( $this->get_action_uri() ); ?>" method="post">
					<?php $this->display_fields(); ?>
				</form>
			<?php else : ?>
				<?php $this->display_page(); ?>
			<?php endif; ?>
		</div>
		
		<?php if ( $this->has_sidebar() ) : ?>
			<div class="storepress-settings-sidebar">
				<?php $this->display_sidebar(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>