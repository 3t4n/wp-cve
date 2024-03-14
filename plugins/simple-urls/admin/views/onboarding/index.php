<?php
/**
 * Onbroarding
 *
 * @package Onbroarding
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;
use LassoLite\Classes\Lasso_DB;

// ? Set flag to know the Welcome page was visited
Helper::update_option( Enum::IS_VISITED_WELCOME_PAGE, 1 );

$lasso_options = Setting::get_settings();
$should_show_import_step = Helper::should_show_import_page();
?>

<section class="purple-bg pt-3 pb-5 min-vh-116">
	<div id="onboarding_container" class="container container-sm">
		<!-- LOGO -->
		<div class="pb-5">
			<div class="logo-large mx-auto">
				<img src="<?php echo SIMPLE_URLS_URL; ?>/admin/assets/images/lasso-logo.svg">
			</div>
		</div>

		<div class="mt-5 mx-auto white-bg shadow rounded p-5">
			<!-- WELCOME -->
			<?php echo Helper::include_with_variables( Helper::get_path_views_folder() . 'onboarding/welcome-step.php',
				array(
					'should_show_import_step' => $should_show_import_step
				) ); ?>

			<!-- CUSTOMIZE DISPLAY -->
			<?php echo Helper::include_with_variables( Helper::get_path_views_folder() . 'onboarding/display-step.php', array(
					'lasso_options' => $lasso_options,
					'should_show_import_step' => $should_show_import_step
			) ); ?>

			<!-- AMAZON ASSOCIATES INFO -->
			<?php echo Helper::include_with_variables( Helper::get_path_views_folder() . 'onboarding/amazon-step.php', array(
				'lasso_options'           => $lasso_options,
				'should_show_import_step' => $should_show_import_step
			) ); ?>

			<!-- ENABLE SUPPORT -->
			<?php echo Helper::include_with_variables( Helper::get_path_views_folder() . 'onboarding/enable-support-step.php', array(
				'lasso_options'           => $lasso_options,
				'should_show_import_step' => $should_show_import_step
			) ); ?>

			<!-- IMPORT -->
			<?php if ( $should_show_import_step ) : ?>
				<?php echo Helper::include_with_variables( Helper::get_path_views_folder() . 'onboarding/import-step.php' ); ?>
			<?php endif; ?>

			<!-- GET STARTED -->
			<?php echo Helper::include_with_variables( Helper::get_path_views_folder() . 'onboarding/done-step.php', array(
				'should_show_import_step' => $should_show_import_step
			)  ); ?>
		</div>
	</div>
</section>

<!-- URL ADD MODAL -->
<?php require SIMPLE_URLS_DIR . '/admin/views/modals/url-add.php'; ?>
<?php Config::get_footer(); ?>