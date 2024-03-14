<?php

?>
<div id="iwpAdminWebPushTopicsCreateModal" class="iwp-modal-backdrop iwp-hide">
	<div class="iwp-modal">
		<div class="iwp-modal-header">
			<i id="iwpTimesTopicModal" class="iwp-close-icon"></i>
		</div>
		<div class="iwp-modal-body">
			<div class="iwp-modal-body-title"><?php _e('New interest group','iwp-text-domain'); ?></div>
			<div class="iwp-admin-form-group">
				<label for="topicName"><?php _e('Topic name','iwp-text-domain'); ?></label>
				<input id="topicName" name="topicName" class="" type="text" value="">
			</div>
			<div class="iwp-admin-form-group">
				<label for="topicCode"><?php _e('Topic code','iwp-text-domain'); ?></label>
				<input id="topicCode" name="topicCode" class="" type="text" value="">
			</div>
		</div>
		<div id="iwp-admin-topic-modal-error-box" class="iwp-admin-error-box iwp-hide"></div>
		<div class="iwp-modal-footer">
			<div class="iwp-admin-form-group-buttons">
				<button id="iwpAdminTopicCancel" class="iwp-btn iwp-btn-blue" type="button">
					<?php _e('Cancel','iwp-text-domain'); ?>
				</button>
				<button id="iwpAdminTopicCreate" class="iwp-btn iwp-btn-green" type="button">
					<?php _e('Add','iwp-text-domain'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div id="iwpAdminWebPushTopicsModalDelete" class="iwp-modal-backdrop iwp-hide">
	<div class="iwp-modal">
		<div class="iwp-modal-header">
			<i id="iwpTimesTopicDeleteModal" class="iwp-close-icon"></i>
		</div>
		<div class="iwp-modal-body">
			<div class="iwp-modal-body-title">
				<?php _e('Delete group','iwp-text-domain'); ?>
			</div>
			<div class="iwp-modal-body-question">
				<?php _e('Are you sure that you want to delete this interest group?','iwp-text-domain'); ?>
			</div>
			<div class="iwp-modal-body-subtitle">
				<?php _e("The group <span id='webPushTopicsModalDeleteTopicName'></span> will be removed from your interest groups list.",'iwp-text-domain'); ?>
			</div>
		</div>
		<div id="iwp-admin-topic-modal-error-box" class="iwp-admin-error-box iwp-hide"></div>
		<div class="iwp-modal-footer">
			<div class="iwp-admin-form-group-buttons">
				<input type="hidden" id="iwpAdminTopicDelete" value="">
				<button id="iwpAdminTopicDeleteCancel" class="iwp-btn iwp-btn-blue" type="button">
					<?php _e('Cancel','iwp-text-domain'); ?>
				</button>
				<button id="iwpAdminTopicDeleteSubmit" class="iwp-btn iwp-btn-red" type="button">
					<?php echo(strtoupper(__('Delete','iwp-text-domain'))); ?>
				</button>
			</div>
		</div>
	</div>
</div>