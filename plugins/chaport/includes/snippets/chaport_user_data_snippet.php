<script type='text/javascript'>
	(function() {
		var visitorData = {};

		<?php if (!empty($this->user_email)): ?>
			visitorData.email = '<?php echo addslashes($this->user_email) ?>';
		<?php endif; ?>
		<?php if (!empty($this->user_name)): ?>
			visitorData.name = '<?php echo addslashes($this->user_name) ?>';
		<?php endif; ?>

		if (visitorData.name || visitorData.email) {
			window.chaport.q('setVisitorData', [visitorData]);
		}
	})();
</script>