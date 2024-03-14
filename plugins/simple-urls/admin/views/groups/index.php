<?php
/**
 * Import
 *
 * @package Import
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Group;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Page;

?>
<?php Config::get_header(); ?>

<!-- GROUP -->
<input id="total-posts" class="d-none" value="0" />
<section class="px-3 py-5">
	<div class="lite-container min-height">

		<!-- TITLE BAR -->
		<div class="row align-items-center">

			<!-- TITLE -->
			<div class="col-lg-4 text-lg-left text-center mb-4">
				<h1 class="m-0 mr-2 d-inline-block align-middle">Groups</h1>
				<a href="https://support.getlasso.co/en/articles/3943430-how-to-use-link-groups" target="_blank" class="btn btn-sm learn-btn">
					<svg class="svg-inline--fa fa-info-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="far" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm0-338c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg><!-- <i class="far fa-info-circle"></i> Font Awesome fontawesome.com --> Learn
				</a>
				<a href="<?php echo Page::get_page_url( Helper::add_prefix_page(Enum::PAGE_GROUP_DETAIL) ) . '&subpage=' . Enum::SUB_PAGE_GROUP_ADD  ?>" class="btn ml-1 btn-sm">
					<svg class="svg-inline--fa fa-plus-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="far" data-icon="plus-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M384 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm120 16c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-48 0c0-110.5-89.5-200-200-200S56 145.5 56 256s89.5 200 200 200 200-89.5 200-200z"></path></svg><!-- <i class="far fa-plus-circle"></i> Font Awesome fontawesome.com --> Add New Group
				</a>
			</div>

			<!-- FILTERS -->
			<div class="col-lg text-center large mb-4">
			</div>

			<!-- SEARCH -->
			<div class="col-lg-4 mb-4">
				<form role="search" method="GET" id="links-filter" autocomplete="off">
					<div id="search-links">
						<input type="search" id="link-search-input" name="link-search-input" class="form-control"
							   value="" placeholder="Search All <?php echo esc_html(Group::total()) ?> Groups">
					</div>
				</form>
			</div>
		</div>

		<!-- TABLE -->
		<div class="white-bg rounded shadow">

			<!-- TABLE HEADER -->
			<div class="px-4 pt-4 pb-2 font-weight-bold dark-gray d-lg-block">
				<div class="row align-items-center">
					<div class="col-lg">Name</div>
					<div class="col-lg">Description</div>
					<div class="col-lg-1">Links</div>
				</div>
			</div>

			<div id="report-content"></div>
		</div>

		<!-- PAGINATION -->
		<div class="pagination row align-items-center no-gutters pb-3 pt-0 dashboard-pagination"></div>
	</div>

</section>
<?php echo Helper::wrapper_js_render( 'group-list', Helper::get_path_views_folder() . Enum::PAGE_GROUPS. '/list-jsrender.html' )?>
<?php Config::get_footer(); ?>
