<?php
/**
 * Dashboard
 *
 * @package Dashboard
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Enum;
use LassoLite\Classes\SURL;
use LassoLite\Classes\Helper;

$dashboard_link_count = SURL::total();
$link_search_txt = esc_html( $_GET['link-search-input'] ?? '' );
?>

<?php Config::get_header(); ?>

<!-- DASHBOARD -->
<input id="total-posts" class="d-none" value="0" />
<section class="px-3 py-5">
	<div class="lite-container min-height">

		<!-- TITLE BAR -->
		<div class="row align-items-center">

			<!-- TITLE -->
			<div class="col-lg-4 text-lg-left text-center mb-4">
				<h1 class="m-0 mr-2 d-inline-block align-middle">Dashboard</h1>
			</div>

			<!-- FILTERS -->
			<div class="col-lg text-center large mb-4">
			</div>

			<!-- SEARCH -->
			<div class="col-lg-4 mb-4">
				<form role="search" method="GET" id="links-filter" autocomplete="off">
					<div id="search-links">
						<input type="search" id="link-search-input" name="link-search-input" class="form-control"
							value="<?php echo $link_search_txt; ?>" placeholder="Search All <?php echo $dashboard_link_count; ?> Links">
					</div>
				</form>
			</div>
		</div>

		<!-- TABLE -->
		<div class="white-bg rounded shadow">

			<!-- TABLE HEADER -->
			<div class="px-4 pt-4 pb-2 font-weight-bold dark-gray d-lg-block">
				<div class="row align-items-center">
					<div class="col-lg-1 text-center">Image</div>
					<div class="col-lg-4">Title</div>
					<div class="col-lg-5">Link</div>
					<div class="col-lg-2">Groups</div>
				</div>
			</div>

			<div id="report-content"></div>
		</div>

		<!-- PAGINATION -->
		<div class="pagination row align-items-center no-gutters pb-3 pt-0 dashboard-pagination"></div>
	</div>
</section>

<?php echo Helper::wrapper_js_render( 'dashboard-list', Helper::get_path_views_folder() . Enum::PAGE_DASHBOARD . '/list-jsrender.html' )?>
<?php Config::get_footer(); ?>
