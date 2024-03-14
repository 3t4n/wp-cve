<?php
/**
 * Logo template
 */
$is_linked = $this->__is_linked();
$settings  = $this->get_settings();
?>
<div class="<?php echo $this->__get_logo_classes(); ?>">
<?php
if( $settings['logo_type'] == 'site_default' ) {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
     
    if ( has_custom_logo() ) {
        if ( $is_linked ) {
            printf( '<a href="%1$s" class="soft-template-logo__link">', esc_url( home_url( '/' ) ) );
                echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
            echo '</a>';
        } else {
            echo '<div class="soft-template-logo__link">';
            echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
            echo '</div>';
        }
    } else {
        if ( $is_linked ) {
            echo '<h1 class="soft-template-logo__text">';
                printf( '<a href="%1$s" class="soft-template-logo__link">', esc_url( home_url( '/' ) ) );
                    echo get_bloginfo('name');
                echo '</a>';
            echo '</h1>';
        } else {
            echo '<h1 class="soft-template-logo__text">';
            echo get_bloginfo('name');
            echo '</h1>';
        }
    }
} else {
    if ( $is_linked ) {
        printf( '<a href="%1$s" class="soft-template-logo__link">', esc_url( home_url( '/' ) ) );
    } else {
        echo '<div class="soft-template-logo__link">';
    }
    
    echo $this->__get_logo_image();
    echo $this->__get_logo_text();
    
    if ( $is_linked ) {
        echo '</a>';
    } else {
        echo '</div>';
    }
}
?>
</div>
