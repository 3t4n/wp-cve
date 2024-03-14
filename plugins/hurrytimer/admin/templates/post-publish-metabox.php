<div class="misc-pub-section misc-pub-post-status misc-hurryt">
    <?php _e('Status:', "hurrytimer")?>
	<?php if ($isActive): ?>
        <b id="post-status-display" style="color:green"><?php _e('Active', "hurrytimer")?></b>
        <a href="<?php echo $deactivateUrl ?>"><?php _e('Deactivate', "hurrytimer")?></a>
	<?php else: ?>
        <b id="post-status-display" style="color:red"><?php _e('Inactive', "hurrytimer")?></b>
        <a href="<?php echo $activateUrl ?>"><?php _e('Activate', "hurrytimer")?></a>
	<?php endif;?>

</div>
