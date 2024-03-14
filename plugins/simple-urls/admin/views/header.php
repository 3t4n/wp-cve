<?php
/**
 * Header HTML
 *
 * @package Header
 */

use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\Setting;

$available_pages = Helper::available_pages();
$header_menu = array( Enum::PAGE_DASHBOARD );
$should_show_import_step = Helper::should_show_import_page();
if ( $should_show_import_step ) {
	$header_menu[] = Enum::PAGE_IMPORT;
}
$header_menu = array_merge( $header_menu, array( Enum::PAGE_OPPORTUNITIES, Enum::PAGE_GROUPS, Enum::PAGE_TABLES ) );

if (  Setting::get_setting( 'general_disable_tooltip' ) ) {
	echo '
		<style>
			[data-tooltip]:hover:before, [data-tooltip]:hover:after {visibility: hidden;}
			i.far.fa-info-circle {display: none;}
		</style>
		';
}

?>

<!-- REQUEST REVIEW -->
<?php 
if ( Helper::show_request_review() ) {
	echo Helper::include_with_variables( SIMPLE_URLS_DIR . '/admin/views/notifications/request-review.php' ); 
}
?>

<!-- HEADER -->
<div class="container-fluid">
	<header class="row align-items-center purple-bg p-3 shadow">

		<!-- LASSO LOGO -->
		<div class="col-lg-2">
			<a href="<?php echo Page::get_page_url( $available_pages[Enum::PAGE_DASHBOARD]->slug ) ?>" class="logo mx-auto mx-lg-0">
				<img src="<?php echo SIMPLE_URLS_URL; ?>/admin/assets/images/lasso-logo.svg">
			</a>
		</div>

		<!-- NAVIGATION -->
		<div class="col-lg py-lg-0 py-3 ml-5">
			<ul class="nav justify-content-center font-weight-bold">
			<?php foreach( $header_menu as $menu ): ?>
			<?php $page = $available_pages[$menu]; ?>
				<li class="nav-item mx-3">
					<a class="nav-link px-0 white <?php echo $page->active_class; ?>" 
						href="<?php echo Page::get_page_url( $page->slug ); ?>">
						<?php echo $page->title; ?>
					</a>
				</li>
			<?php endforeach; ?>
				<li class="nav-item mx-3">
					<a class="nav-link px-0 white" href="https://app.getlasso.co/performance/" target="_blank">
						Performance
					</a>
				</li>
			</ul>
		</div>
		<div class="col-lg-1 text-lg-right text-center pb-lg-0 pb-3 pl-1">
			<div id="wrapper-circle"></div>
		</div>
		<div class="col-lg-2 text-lg-right text-center pb-lg-0 pb-3 pl-1">
			<button class="btn" data-toggle="modal" data-target="#url-add">
				<i class="far fa-plus-circle large-screen-only"></i> Add New Link
			</button>
		</div>

	</header>

	<!-- ALERTS -->
	<div id="lasso_lite_notifications">
	</div>

	<!-- URL ADD MODAL -->
	<?php require SIMPLE_URLS_DIR . '/admin/views/modals/url-add.php'; ?>
	<!-- Enable support modal -->
	<?php require SIMPLE_URLS_DIR . '/admin/views/modals/enable-support.php'; ?>
</div>
