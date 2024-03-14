<?php do_action( 'youzify_account_before_content' ); ?>

<div id="youzify">

	<div id="<?php echo apply_filters( 'youzify_account_template_id', 'youzify-bp' ); ?>" class="youzify youzify-page youzify-account-page">

		<?php do_action( 'youzify_account_before_main' ); ?>

		<main class="youzify-page-main-content">


			<aside class="youzify-sidebar youzify-settings-sidebar">

				<?php do_action( 'youzify_settings_menus' ); ?>

			</aside>

			<div class="youzify-main-content settings-main-content">

				<?php do_action( 'bp_before_member_settings_template' ); ?>

				<div id="template-notices" role="alert" aria-atomic="true">
					<?php

					/**
					 * Fires towards the top of template pages for notice display.
					 *
					 * @since 1.0.0
					 */
					do_action( 'template_notices' ); ?>

				</div>

				<div class="youzify-inner-content settings-inner-content">

	                <?php do_action( 'youzify_account_before_form'); ?>

	                <?php do_action( 'youzify_profile_settings' ); ?>

	                <?php do_action( 'youzify_account_after_form' ); ?>

				</div>

			</div>

		</main>

		<?php do_action( 'youzify_account_footer'); ?>

	</div>

</div>