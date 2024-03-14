<div class="widget-eli eli-logo" id="eli_<?php echo esc_html($this->get_id_int());?>">
    <div class="eli_container">
        <?php if (!empty($settings['custom_logo_image']['url']) ) : ?>
            <a href="<?php echo esc_url( $link ); ?>" <?php if($link_new_window):?> target="_blank" <?php endif;?> class="custom-logo-link">
                <img src="<?php echo esc_url( $settings['custom_logo_image']['url'] ) ?>" alt="<?php bloginfo( 'name' ); ?>">
            </a>
        <?php elseif (has_custom_logo() ) : ?>
            <?php if($settings['logo_image_footer_enable'] == 'yes' && get_theme_mod( 'footer_logo' )) :?>
                <a href="<?php echo esc_url( $link ); ?>" <?php if($link_new_window):?> target="_blank" <?php endif;?> class="custom-logo-link">
                    <img src="<?php echo esc_url( get_theme_mod( 'footer_logo' ) ) ?>" alt="<?php bloginfo( 'name' ); ?>">
                </a>
            <?php else:?>
                <?php 
                    $custom_logo__url = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ); 
                ?>
                <a  href="<?php echo esc_url( $link ); ?>" <?php if($link_new_window):?> target="_blank" <?php endif;?>  class="custom-logo-link">
                    <img src="<?php echo esc_url( $custom_logo__url[0]);?>" class="custom-logo"  />
                </a>
            <?php endif;?>
        <?php else : ?>
            <a href="<?php echo esc_url( $link ); ?>" <?php if($link_new_window):?> target="_blank" <?php endif;?> class="eli-logo-link-text" title="<?php bloginfo( 'name' ); ?>">
                <?php bloginfo( 'name' ); ?>
            </a>
        <?php endif; ?>
    </div>
</div>