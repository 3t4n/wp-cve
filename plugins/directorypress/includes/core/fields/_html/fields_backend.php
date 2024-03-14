<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="row">
				<div class="col-md-12">
					<div class="directorypress-box">
						<div class="directorypress-box-head">
							<h1><?php _e('DirectoryPress Fields', 'DIRECTORYPRESS'); ?></h1>
							<p><?php _e('You can create unlimited fields as per your requirements', 'DIRECTORYPRESS'); ?></p>
							<?php echo '<a class="dp-admin-btn dp-success create" data-toggle="modal" data-target="#create_new_field" href="#">' . __('Create New Field', 'directorypress-extended-locations') . '</a>'; ?>
							<?php echo '<a class="dp-admin-btn dp-success create_group" data-toggle="modal" data-target="#create_new_group" href="#">' . __('Create New Group', 'directorypress-extended-locations') . '</a>'; ?>
						</div>
						<div class="directorypress-box-content wp-clearfix">
							<div class="directorypress-manager-page-wrap">
								<?php $itab_id = uniqid(); ?>
								<ul class="nav nav-tabs" id="tabContent">
									<li class="active"><a href="#fields_list" data-toggle="tab"><?php _e('Fields', 'DIRECTORYPRESS'); ?></a></li>
									<li><a href="#fields_group" data-toggle="tab"><?php _e('Groups', 'DIRECTORYPRESS'); ?></a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade active in" id="fields_list">
										</br>
										<p class="alert alert-info"><?php _e('Fields order can be changed by drag & drop.', 'DIRECTORYPRESS'); ?></p>
										<form method="POST" action="">
											<div id="fields-ajax-response"></div>
											<input type="hidden" id="fields_order" name="fields_order" value="" />
											<div class="fields_list_wrapper"><?php echo $items_list; ?></div>
											
										</form>
									</div>
									<div class="tab-pane fade" id="fields_group">
										<form method="POST" action="">
											<div id="groups-ajax-response"></div>
											<div class="fields_group_list_wrapper"><?php echo $group_items_list; ?></div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="create_new_field" class="modal fade directorypress-admin-modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="topline"></div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary new-field-action-button"><?php echo esc_html__('Create', 'directorypress-extended-locations'); ?></button>
				<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal"><?php echo esc_html__('Cancel', 'directorypress-extended-locations'); ?></button>
			</div>
		</div>
	</div>
</div>
<div id="fields_configure" class="modal fade directorypress-admin-modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="topline"></div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal"><?php echo esc_html__('Cancel', 'directorypress-extended-locations'); ?></button>
			</div>
		</div>
	</div>
</div>

<div id="create_new_group" class="modal fade directorypress-admin-modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="topline"></div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary new-group-action-button"><?php echo esc_html__('Create', 'directorypress-extended-locations'); ?></button>
				<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal"><?php echo esc_html__('Cancel', 'directorypress-extended-locations'); ?></button>
			</div>
		</div>
	</div>
</div>
<div id="groups_configure" class="modal fade directorypress-admin-modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="topline"></div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal"><?php echo esc_html__('Cancel', 'directorypress-extended-locations'); ?></button>
			</div>
		</div>
	</div>
</div>