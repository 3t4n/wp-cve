<script type="text/javascript">
<?php foreach($this->events_queue as $event): ?>
	<?php if($event['method'] == 'track'): ?>
    bdroppy.event("<?php echo $event['event']; ?>", <?php echo json_encode($event['params']); ?>);
	<?php endif; ?>
	<?php if($event['method'] == 'pageview'): ?>
    bdroppy.pageview();
	<?php endif; ?>
<?php endforeach; ?>
</script>
