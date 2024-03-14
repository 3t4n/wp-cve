<?php
/**
 * Settings header
 *
 * @package Settings header
 */

use LassoLite\Admin\Constant;

use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;

$page       = esc_html( $_GET['page'] );
$page       = str_replace( SIMPLE_URLS_SLUG . '-', '', $page );
$title      = '';
$learn_link = Constant::LASSO_SUPPORT_URL;

if ( Enum::PAGE_SETTINGS_DISPLAY === $page ) {
	$learn_link = 'https://support.getlasso.co/en/collections/2287037-customizing-displays';
} else if (Enum::PAGE_SETTINGS_AMAZON === $page ) {
	$learn_link = 'https://support.getlasso.co/en/collections/1858466-amazon-associates-integration';
}

$available_pages = Helper::available_pages();
$header_menu = array(
	Enum::PAGE_SETTINGS_DISPLAY,
	Enum::PAGE_SETTINGS_AMAZON,
	Enum::PAGE_SETTINGS_GENERAL,
);

?>

<div class="row align-items-center">
	<!-- TITLE -->
	<div class="col-lg-4 mb-4 text-lg-left text-center">
		<?php $page = $available_pages[ $page ]; ?>
		<h1 class="m-0 mr-2 d-inline-block align-middle"><?php echo $page->title; ?></h1>
		<a href="<?php echo $learn_link; ?>" target="_blank" class="btn btn-sm learn-btn">
			<i class="far fa-info-circle"></i> Learn
		</a>
	</div>

	<!-- SUB NAVIGATION -->
	<div class="col-lg">
		<ul class="nav justify-content-lg-end justify-content-center font-weight-bold mb-4">
		<?php foreach( $header_menu as $menu ): ?>
		<?php $page = $available_pages[$menu]; ?>
			<li class="nav-item ml-4 mr-lg-0 mr-3">
				<a class="nav-link purple hover-underline px-0 <?php echo $page->active_class; ?>" 
					href="<?php echo Page::get_page_url( $page->slug ); ?>">
					<?php echo $page->title; ?>
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>

<?php require Helper::get_path_views_folder() . 'modals/url-save-progress.php'; ?>
