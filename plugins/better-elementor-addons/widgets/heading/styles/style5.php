<div class="better-heading style-5 cont">
    <h5 class="clorful"><?php echo esc_html($settings['better_heading_title_1']); ?></h5>
    <h2><?php echo esc_html($settings['better_heading_title']); ?></h2>
    <p><?php echo esc_html($settings['better_heading_des']); ?></p>
    <?php if (!empty($settings['link']['url'])) { ?>
        <a href="<?php echo esc_url($settings['link']['url']); ?>" class="better-btn-skew btn-color btn-bg mt-30"><span><?php echo esc_html($settings['btn_text']); ?></span><i></i></a>
    <?php } ?>
</div>
