<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="hmcabw-parent-container">

    <div class="hmcabw-image-container <?php esc_attr_e( $hmcabw_icon_shape ); ?>">
        <?php
        //echo get_the_author_meta('hmcabw_photograph');
        if ( intval( get_the_author_meta('hmcabw_photograph') ) > 0 ) {
            echo $hmcabwImage = wp_get_attachment_image( get_the_author_meta('hmcabw_photograph'), 'thumbnail', false, array( 'id' => 'hmcabw-preview-image' ) );
        } else {
            echo get_avatar( get_the_author_meta('user_email'), $hmcabw_photo_width );
        }
        ?>

        <div class="hmcabw-social-container">
            <?php
            $hmcabwSocialSettings = get_the_author_meta('cab_user_socials');

            foreach ( $hmcabwSocials as $hmcabwSocial ) {
                
                if ( isset( $hmcabwSocialSettings[$hmcabwSocial.'_enable'] ) ) {
                    
                    if ( filter_var( $hmcabwSocialSettings[$hmcabwSocial.'_enable'], FILTER_SANITIZE_NUMBER_INT) == 1) {
                        ?><a href="<?php echo esc_url( $hmcabwSocialSettings[$hmcabwSocial.'_link'] ); ?>" target="_blank" class="cab-front-social-icon <?php esc_attr_e( $hmcabw_icon_shape ); ?>">	
                            <i class="fa-brands fa-<?php esc_attr_e( $hmcabwSocial ); ?>" aria-hidden="true"></i>
                        </a><?php
                    }
                }
            } ?>
        </div>
        
    </div>

    <div class="hmcabw-info-container">

        <h3 class="hmcabw-name"><?php echo get_the_author_meta('display_name'); ?></h3>

        <?php 
        if ( $hmcabw_display_title ) { 
            ?>
            <span class="hmcabw-title"><?php echo get_the_author_meta('cab_title'); ?></span>
            <?php 
        } 
        ?>
        <div class="hmcab-name-border-main"></div>

        <p class="hmcabw-bio-info"><?php echo nl2br( get_the_author_meta('description') ); ?></p>

        <div class="hmcabw-email-url-container">
            <?php 
            if ( $hmcabw_display_email ) {
                ?>
                <span class="hmcabw-email">
                    <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php echo get_the_author_meta('user_email'); ?>
                </span>
                <?php 
            }

            if ( $hmcabw_display_web ) { 
                ?>
                <a href="<?php echo get_the_author_meta('user_url'); ?>" class="hmcabw-website">
                    <i class="fa fa-globe" aria-hidden="true"></i>&nbsp;<?php echo get_the_author_meta('user_url'); ?>
                </a>
                <?php 
            } 
            ?>
        </div>

    </div>
    
</div>