<?php

/**
 * Base template for setup wizard.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since   1.0.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(); ?>
<section class="ssgsw-wrapper" x-data="setup">

	<?php ssgsw()->load_template('setup/woocommerce'); ?>

	<!-- setup wizard  -->
	<div class="ssgs-admin setup">

		<?php ssgsw()->load_template('setup/welcome'); ?>

		<!-- setup tabs  -->
		<div class="ssgs-tab">
			<?php ssgsw()->load_template('setup/header'); ?>
			<div class="ssgs-tab__content">
				<?php
				ssgsw()->load_template('setup/step-1');
				ssgsw()->load_template('setup/step-2');
				ssgsw()->load_template('setup/step-3');
				ssgsw()->load_template('setup/step-4');
				ssgsw()->load_template('setup/step-5');
				?>
			</div><!-- /Tab Content Wrapper -->
			<?php ssgsw()->load_template('setup/footer'); ?>
		</div>
	</div>
</section>
