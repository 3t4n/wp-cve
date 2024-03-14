<?php if ($this->has_events_in_cookie): ?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$.get("<?php echo add_query_arg('bdroppy_clear', 1); ?>", function(response) {  });
	});
	</script>
<?php endif; ?>
