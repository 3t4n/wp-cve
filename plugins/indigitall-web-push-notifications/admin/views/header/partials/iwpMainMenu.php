<?php
	$channels = isset($channels) ? $channels : array();
	$channelEnabled = __('Activated', 'iwp-text-domain');
	$channelDisabled = __('Deactivated', 'iwp-text-domain');
?>

<div class="iwp-admin-main-menu">
	<?php
		foreach ($channels as $channel) {
			$warningClass = !empty($channel['warning']) ? ' iwp-color-warning ' : '';
			?>
			<a id="<?php echo($channel['id']); ?>" href="<?php echo($channel['link']); ?>" target="_self"
			   class="iwp-admin-main-menu-item <?php echo($channel['currentPage'] . ' ' . $warningClass); ?>">
				<div class="iwp-admin-main-menu-item-title">
					<?php echo($channel['warning']); ?>
					<?php echo($channel['name']); ?>
				</div>
				<div class="iwp-admin-main-menu-item-status <?php echo($channel['class']); ?>">
					<span class="enabled-channel"><?php echo($channelEnabled); ?></span>
					<span class="disabled-channel"><?php echo($channelDisabled); ?></span>
				</div>
			</a>
			<?php
		}
	?>
</div>
