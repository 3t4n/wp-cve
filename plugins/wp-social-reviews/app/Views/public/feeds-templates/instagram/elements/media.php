<div class="wpsr-ig-post-media wpsr-animated-background <?php echo esc_attr($media_type); ?>" id="wpsr-video-play-<?php echo esc_attr($index); ?>" >
    <?php if ($media_type === 'VIDEO' && !empty($media_url)) { ?>
        <video class="wpsr-ig-post-video" poster="<?php echo esc_url($thumbnail_url); ?>" <?php echo ($template_meta["post_settings"]["display_mode"] === 'inline') ? 'controls=controls preload="false" loop="" webkit-playsinline="" playsinline=""' : ''?>>
            <source src="<?php echo esc_url($media_url); ?>" type="video/mp4">
        </video>
    <?php } ?>

    <?php if ($media_type === 'IMAGE' && !empty($media_url)) { ?>
        <img class="wpsr-ig-post-img <?php echo esc_attr($placeholder_img_class); ?>"  src="<?php echo esc_url($media_url); ?>" alt="<?php echo isset($feed['caption']) ? esc_attr($feed['caption']) : ''; ?>" loading="lazy">
    <?php } ?>

    <?php if ($media_name !== 'IMAGE') { ?>
        <div class="wpsr-ig-post-type-icon wpsr-ig-post-type-<?php echo esc_attr($media_name); ?>"></div>
    <?php } ?>
</div>