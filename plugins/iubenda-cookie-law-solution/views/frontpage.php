<?php
/**
 * Frontpage - global - page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Including partial header.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/header.php';
?>
<div class="main-box main-box__bg text-center">
	<div id="frontpage-main-box" class="p-5">
		<?php
		// Including partial frontpage-main-box.
		require_once IUBENDA_PLUGIN_PATH . '/views/partials/frontpage-main-box.php';
		?>
	</div>
	<hr>
	<div class="welcome-screen-footer p-5">
		<h3 class="text-md text-normal m-0 mb-3"><?php esc_html_e( "Let's configure your website for compliance.", 'iubenda' ); ?></h3>
		<a class="btn btn-green-primary btn-lg show-modal"  data-modal-name="#modal-setup-screen" href="javascript:void(0)"><?php esc_html_e( 'Help me get compliant!', 'iubenda' ); ?></a>
	</div>
</div>

<?php
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
	<div id="modal-setup-screen" class="modal">
		<div class="modal__window modal__window--md p-4 p-lg-5">
			<?php
			// Including partial modal-sync.
			require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-sync.php';
			?>
		</div>
	</div>

	<!-- Modal pp created-->
	<div id="modal_pp_created" class="modal">
		<div class="modal__window modal__window--md p-4 p-lg-5">
			<?php
			// Including partial modal-pp-created.
			require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-pp-created.php';
			?>
		</div>
	</div>

	<!-- Modal Almost There -->
	<div id="modal-have-existing-products" class="modal modal--xs">
		<div class="modal__window modal__window--md p-4 p-lg-5">
			<?php
			// Including partial modal-almost-there.
			require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-almost-there.php';
			?>
		</div>
	</div>

	<!-- Modal Select language -->
	<div id="modal-select-language" class="modal modal--xs">
		<div class="modal__window modal__window--md p-4 p-lg-5">
			<?php
			// Including partial modal-select-language.
			require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-select-language.php';
			?>
		</div>
	</div>

<?php
// Including partial modal-no-website-found.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-no-website-found.php';
wp_enqueue_script( 'iubenda-quick-generator-loader', 'https://cdn.iubenda.com/quick_generator/loader.js', array(), iubenda()->version, false );
?>
