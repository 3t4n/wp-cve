<?php
/**
 * URL links
 *
 * @package Lasso URL links
 */

use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;

$bulk_revert = $_GET['bulk-revert'] ?? '';
$page        = $_GET['page'] ?? '';
?>

<div class="row align-items-center">
	<!-- TITLE -->
	<div class="col-lg mb-4 text-lg-left text-center">
		<?php if ( Helper::add_prefix_page( Enum::PAGE_IMPORT ) === $page ): ?>
		<h1 class="m-0 mr-2 d-inline-block align-middle">Import</h1>

		<a href="https://support.getlasso.co/en/articles/4005802-how-to-import-link-from-another-plugin" target="_blank" class="btn btn-sm learn-btn">
			<i class="far fa-info-circle"></i> Learn
		</a>
		<?php endif; ?>

		<button id="btn-bulk-import" class="btn btn-sm">
			Bulk Import
		</button>

		<?php if ( ! empty( $bulk_revert ) ) { ?>
			<button class="btn btn-sm red-bg" data-toggle="modal" data-target="#revert-all-confirm">
				Bulk Revert
			</button>
		<?php } ?>
	</div>

    <div class="col-lg text-center large mb-4">
        <select name="filter_plugin" id="filter-plugin" class="form-control">
            <option value="">All Plugins</option>
        </select>
    </div>

	<!-- IMPORT SEARCH -->
	<div class="col-lg-4 mb-4">
		<form role="search" method="get" id="links-filter" autocomplete="off">
			<div id="search-links">
				<input type="search" id="link-search-input" name="link-search-input" class="form-control" placeholder="Search URLs to Import">
			</div>
		</form>
	</div>

</div>
