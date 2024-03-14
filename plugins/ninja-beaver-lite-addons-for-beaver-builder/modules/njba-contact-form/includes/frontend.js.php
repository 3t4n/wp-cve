(function($) {
	$(function() {
		new NJBAContactForm({
			id: '<?php echo $id ?>',
			njba_ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
			first_name_required: '<?php echo $settings->first_name_required; ?>',
			email_required: '<?php echo $settings->email_required; ?>',
			last_name_required: '<?php echo $settings->last_name_required; ?>',
			subject_required: '<?php echo $settings->subject_required; ?>',
			phone_required: '<?php echo $settings->phone_required; ?>',
			msg_required: '<?php echo $settings->msg_required; ?>'
		});
	});
})(jQuery); 