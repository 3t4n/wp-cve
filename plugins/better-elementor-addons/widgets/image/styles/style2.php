<div class="better-image style-2 <?php if ( ! empty( $settings['title'] ) ) { echo 'better-tooltip';}?>  ">
    <div class="img2 wow imago" data-tooltip-tit="<?php echo esc_attr($settings['title']); ?>" data-tooltip-sub="<?php echo esc_attr($settings['subtitle']); ?>">
        <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="" class="imago wow">
    </div> 
    <div class="div-tooltip-tit"><?php echo esc_html($settings['title']); ?></div>
</div>
