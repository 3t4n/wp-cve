<?php
/**
 * Modal
 *
 * @package Modal
 */
?>

<?php if ( ! isset( $is_from_editor ) ) : ?>
<!-- URL ADD -->
<div class="modal fade" id="url-add" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content shadow p-5 rounded text-center">

			<div id="add_new_form">
				<!-- TARGET URL -->
				<h2>Add A New Link</h2>
				<p>Enter the affiliate link you would like to track.</p>
				<div class="form-group mb-4">
					<input type="text" name="url" id="add-new-url-box" class="form-control" placeholder="https://www.example.com/affiliate-id">
					<input type="hidden" name="post_type" value="lasso-urls">
					<input type="hidden" name="page" value="url-details">
					<p class="js-error text-danger my-3"></p>
				</div>
				<div class="text-center">
					<button id="btn-lasso-add-new-link" class="btn">
						<i class="far fa-plus-circle"></i> Add Link
					</button>
				</div>
			</div>

		</div>
	</div>
</div>
<?php else: ?>
	<!-- Post Editor -->
	<!-- URL ADD -->
	<div class="lasso-modal fade url-add" id="url-add" tabindex="-1" role="dialog" data-is-from-editor="1">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content shadow p-5 rounded text-center">

				<div id="add_new_form">
					<!-- TARGET URL -->
					<h2>Add A New Link</h2>
					<p>Enter the destination URL your Lasso link will redirect to.</p>
					<div class="form-group mb-4">
						<input type="text" name="url" id="add-new-url-box" class="form-control" placeholder="https://www.example.com/affiliate-id">
						<input type="hidden" name="post_type" value="lasso-urls">
						<input type="hidden" name="page" value="url-details">
						<p class="js-error text-danger my-3"></p>
					</div>
					<div class="text-center">
						<span id="btn-lasso-add-new-link" class="btn btn-lasso-add-link" data-disabled="0">
							<i class="far fa-plus-circle"></i> Add Link
						</span>
					</div>
				</div>

			</div>
		</div>
	</div>
<?php endif; ?>
