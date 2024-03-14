<?php
use LassoLite\Classes\Helper;
?>

<div class="tab-item d-none text-center" data-step="import">
	<div class="progressbar_container">
		<ul class="progressbar">
			<li class="step-get-started complete">Welcome</li>
			<li class="step-display-design complete" data-step="display">Display Designer</li>
			<li class="step-amazon-info complete" data-step="amazon">Amazon Associates</li>
			<li class="step-enable-support complete" data-step="enable-support">Enable Support</li>
			<li class="step-import active">Imports</li>
			<li class="step-get-started">Done</li>
		</ul>
	</div>

	<div class="onboarding_header text-center">
		<h1 class="font-weight-bold">Imports</h1>
		&nbsp;<a href="https://support.getlasso.co/en/articles/4005802-how-to-import-link-from-another-plugin" target="_blank" class="btn btn-sm learn-btn">
			<i class="far fa-info-circle"></i> Learn
		</a>
	</div>

	<!-- IMPORT -->
	<input id="total-posts" class="d-none" value="0" />
	<section class="px-3">
		<div class="lite-container">
			<?php require_once SIMPLE_URLS_DIR . '/admin/views/import/header.php'; ?>

			<!-- LINKS TO IMPORT -->
			<div class="white-bg rounded shadow">
				<div class="px-4 pt-4 pb-2 font-weight-bold dark-gray d-lg-block">
					<div class="row align-items-center">
						<div class="col-4">Link Title</div>
						<div class="col">Import Target</div>
						<div class="col-1">Plugin</div>
						<div class="col-1 text-center"></div>
						<div class="col-1 text-center"></div>
					</div>
				</div>

				<div id="report-content"></div>
			</div>

			<!-- PAGINATION -->
			<div class="pagination row align-items-center no-gutters pb-3 pt-0"></div>
		</div>

	</section>

	<!-- MODALS -->
	<?php require_once SIMPLE_URLS_DIR . '/admin/views/modals/import-all-confirm.php'; ?>
	<?php require_once SIMPLE_URLS_DIR . '/admin/views/modals/revert-all-confirm.php'; ?>
	<?php require_once SIMPLE_URLS_DIR . '/admin/views/modals/import-confirm.php'; ?>
	<?php require_once SIMPLE_URLS_DIR . '/admin/views/modals/revert-confirm.php'; ?>
	<?php require_once SIMPLE_URLS_DIR . '/admin/views/modals/url-save.php'; ?>

	<?php
	$page = $_GET['page'] ?? '';
	$template_variables = array( 'page' => $page );
	Helper::include_with_variables( SIMPLE_URLS_DIR . '/admin/assets/js/import-js.php', $template_variables, false );
	?>

	<!-- SAVE CHANGES -->
	<div class="row align-items-center mt-4">
		<div class="col-lg text-lg-right text-center">
			<button class="btn next-step">Continue &rarr;</button>
		</div>
	</div>
</div>
