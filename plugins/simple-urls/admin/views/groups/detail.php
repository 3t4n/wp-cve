<?php
/**
 * Group Detail
 *
 * @package Group Detail
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Enum;
?>
<?php Config::get_header(); ?>
<!-- GROUP -->
<input id="total-posts" class="d-none" value="0" />
<section class="px-3 py-5">
	<div class="lite-container">
		<!-- TITLE & NAVIATION -->
		<?php require 'header.php'; ?>
		<?php $is_detail_page = ( ! empty( $post_id ) && Enum::SUB_PAGE_GROUP_DETAIL === $subpage ) || Enum::SUB_PAGE_GROUP_ADD === $subpage || empty( $subpage ); ?>
		<input type="hidden" id="post_id" name="" value="<?php echo $group_id ?>">
		<input type="hidden" id="url_count" name="" value="<?php echo $url_count ?>">
		<?php if( $is_detail_page ): ?>
		<form method="post" class="lasso-admin-settings-form" autocomplete="off" action="">
			<!-- EDIT DETAILS -->
			<div class="white-bg rounded shadow p-5 mb-5">
				<div class="row">
					<div class="col-lg-6">
						<div class="row">
							<div class="col-lg">
								<input type="hidden" id="grp_id" name="grp_id" value="<?php echo $group_id ?>">
								<!-- NAME -->
								<div class="form-group mb-4">
									<label><strong>Name</strong></label>
									<input type="text" class="form-control" id="grp_name" name="grp_name" value="<?php echo esc_html( $name ) ?>" required placeholder="Group Name Goes Here">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<!-- DESCRIPTION -->
						<div class="form-group">
							<label><strong>Description</strong></label>
							<textarea type="text" class="form-control" id="grp_desc" name="grp_desc" rows="3"><?php echo esc_html( $description ) ?></textarea>
						</div>
					</div>
				</div>
			</div>

			<!-- SAVE & DETELE -->
			<div class="row align-items-center">
				<!-- SAVE CHANGES -->
				<div class="col-lg order-lg-2 text-lg-right text-center mb-4">
					<button type="submit" class="btn btn-save" id="lasso-lite-group-save">Save Changes</button>
				</div>
				<?php if( $group_id ): ?>
				<div class="col-lg text-lg-left text-center mb-4">
					<a id="group_delete_pop" href="#" class="red hover-red-text" data-toggle="modal"><i class="far fa-trash-alt"></i> Delete This Group</a>
				</div>
				<?php endif; ?>
			</div>
		</form>
		<?php elseif ( Enum::SUB_PAGE_GROUP_URLS === $subpage ): ?>
			<!-- TABLE -->
			<div class="white-bg rounded shadow mb-5">

				<!-- TABLE HEADER -->
				<div class="px-4 pt-4 pb-2 font-weight-bold dark-gray d-lg-block">
					<div class="row align-items-center">
						<div class="col-lg-1">Image</div>
						<div class="col-lg">Link Name</div>
						<div class="col-lg">Permalink</div>
					</div>
				</div>

				<div id="report-content"></div>
			</div>

			<!-- DETELE -->
			<div class="row align-items-center">
				<?php if( $group_id ): ?>
				<div class="col-lg text-lg-left text-center mb-4">
					<a id="group_delete_pop" href="#" class="red hover-red-text" data-toggle="modal"><i class="far fa-trash-alt"></i> Delete This Group</a>
				</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

</section>

<!-- DELETE GROUP MODALS -->
<?php require_once SIMPLE_URLS_DIR . '/admin/views/modals/group-delete.php'; ?>

<div class="modal fade" id="group_not_delete" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content text-center shadow p-5 rounded">
			<h2>Hold Up</h2>
			<p>You can't delete a Group if it has associated Links. Remove all Links using this Group first.</p>
			<div>
				<button type="button" class="btn" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>

<?php echo Helper::wrapper_js_render( 'group-urls', Helper::get_path_views_folder() . Enum::PAGE_GROUPS . '/urls-jsrender.html' )?>
<?php echo Helper::wrapper_js_render( 'default-template-notification-group', Helper::get_path_views_folder() . '/notifications/default-template-jsrender.html' )?>
<?php Config::get_footer(); ?>
