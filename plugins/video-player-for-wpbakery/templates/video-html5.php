<div id="<?php echo $el_id; ?>" class="video-player-for-wpbakery <?php echo $el_class; ?>">
    <div class="video-player-for-wpbakery-container">
        <video width="<?php echo $width; ?>" height="<?php echo $height; ?>" <?php echo $controls; ?> <?php echo $autoplay; ?> <?php echo $loop; ?> <?php echo $muted; ?>>
            <source src="<?php echo $url; ?>" type="<?php echo $mime_type; ?>">
            <?php _e('Your browser does not support the video tag.', 'video-player-for-wpbakery'); ?>
        </video>
    </div>
</div>