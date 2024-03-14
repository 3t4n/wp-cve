<?php
/**
 * Modal
 *
 * @package Modal
 */

// phpcs:ignore
?>

<!-- MONETIZE -->
<div id="lasso-display-add" class="lasso-modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content p-0">

			<!-- CHOOSE A DISPLAY TYPE -->
			<div id="lasso-display-type" class="text-center p-5">
				<h2 class="mb-4">Choose a Display Type</h2>
				<p><a href="https://getlasso.co/upgrade/" target="_blank">Unlock premium display types</a></p>
				<div class="row align-items-center">
					<div class="col-3">
						<a id="lasso-single" class="lasso-display-type hover-gray" data-tab="single" data-tab-container="lasso-urls">
							<i class="far fa-pager fa-7x"></i>
							<h3 class="mb-0">Single</h3>
						</a>
					</div>
					<div class="col-3 lasso-lite-disabled">
						<a id="lasso-grid" class="lasso-display-type hover-gray" data-tab="grid" data-tab-container="lasso-groups">
							<i class="far fa-border-all fa-7x"></i>
							<h3 class="mb-0">Grid</h3>
						</a>
					</div>
					<div class="col-3 lasso-lite-disabled">
						<a id="lasso-list" class="lasso-display-type hover-gray" data-tab="list" data-tab-container="lasso-groups">
							<i class="far fa-list fa-7x"></i>
							<h3 class="mb-0">List</h3>
						</a>
					</div>
					<div class="col-3 lasso-lite-disabled">
						<a id="lasso-table" class="lasso-display-type hover-gray" data-tab="table" data-tab-container="lasso-tables">
							<i class="far fa-columns fa-7x"></i>
							<h3 class="mb-0">Table</h3>
						</a>
					</div>
				</div>
				<div class="row align-items-center">
					<div class="col-3 lasso-lite-disabled">
						<a id="lasso-button" class="lasso-display-type hover-gray" data-tab="button" data-tab-container="lasso-urls">
							<i class="far fa-rectangle-wide fa-7x"></i>
							<h3 class="mb-0">Button</h3>
						</a>
					</div>
					<div class="col-3 lasso-lite-disabled">
						<a id="lasso-image" class="lasso-display-type hover-gray" data-tab="image" data-tab-container="lasso-urls">
							<i class="far fa-image fa-7x"></i>
							<h3 class="mb-0">Image</h3>
						</a>
					</div>
					<div class="col-3 lasso-lite-disabled">
						<a id="lasso-gallery" class="lasso-display-type hover-gray" data-tab="gallery" data-tab-container="lasso-groups">
							<i class="far fa-images fa-7x"></i>
							<h3 class="mb-0">Gallery</h3>
						</a>
					</div>
				</div>
			</div>

			<!-- CHOOSE A URL -->
			<div id="lasso-urls" class="tab-container d-none">
				<div class="row align-items-center px-5 pt-5 pb-4">
					<div class="col-lg-5">
						<h2>Choose a Link</h2>
					</div>
					<div class="col-lg-3">
						<button class="lasso-display-add-btn btn-create-link">
							Create a Link
						</button>
					</div>
					<div class="col-lg-4 search-keys">
						<input id="search-key-single" type="text" class="form-control" placeholder="Search URLs">
						<input id="search-key-button" type="text" class="form-control" placeholder="Search URLs">
						<input id="search-key-image" type="text" class="form-control" placeholder="Search URLs">
					</div>
				</div>

				<!-- SINGLE URL -->
				<div class="link_wrapper">
					<div id="all_links" class="text-break lasso-items">
						<div class="py-5"><div class="loader"></div></div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
