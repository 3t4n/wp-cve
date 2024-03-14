
<script>
<?php if ($data['has_connected']): ?>
BN_target = '<?php echo BEACONBY_CREATE_TARGET . '/api/beacon/' . $data['has_connected']?>';
<?php else: ?>
BN_target = false;
<?php endif; ?>
</script>


</div>
<!-- .requires-login -->
</div>
<!-- .beacon-by-admin-wrap -->
