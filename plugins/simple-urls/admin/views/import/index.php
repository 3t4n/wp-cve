<?php
/**
 * Import
 *
 * @package Import
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Helper;
?>
<?php Config::get_header(); ?>

<!-- IMPORT -->
<input id="total-posts" class="d-none" value="0" />
<section class="px-3 py-5">
	<div class="lite-container min-height">
		<?php require_once 'header.php'; ?>

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
<?php echo Helper::wrapper_js_render( 'default-template-notification', Helper::get_path_views_folder() . '/notifications/default-template-jsrender.html' )?>

<?php Config::get_footer(); ?>
