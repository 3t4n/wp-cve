<div class="directorypress-content-wrap">
	<div class="checkbox">
		<label class="switch">
			<input type="checkbox" name="is_claimable" value=1 <?php checked(1, $listing->is_claimable, true); ?> />
			<span class="slider"></span>
		</label>
	</div>
</div>
<?php do_action('directorypress_claim_metabox_html', $listing); ?>