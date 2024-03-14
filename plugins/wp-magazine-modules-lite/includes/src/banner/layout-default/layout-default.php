<?php
/**
 * Banner layout default - render
 */
?>

<div class="cvmm-banner-content-wrap">
    <div class="cvmm-banner-content">
        <?php
            if( !empty( $imageUrl ) ) {
        ?>
                <figure class="cvmm-banner-thumb">
                    <img src="<?php echo esc_url( $imageUrl ); ?>">
                </figure>
        <?php
            }
        ?>
            <div class="cvmm-banner-meta-wrap">
            <?php
                if( $titleOption ) {
                    echo '<h2 class="cvmm-banner-title"><a href="'.esc_url( $bannerTitleLink ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $title ).'</a></h2>';
                }

                if( $descOption ) {
                    echo '<div class="cvmm-banner-desc">'.wp_kses_post( $description ).'</div>';
                }

                if( $button1Option || $button2Option ) {
                    echo '<div class="banner-button-wrap">';
                        if( $button1Option ) {
                            echo '<a href="'.esc_url( $button1Link ).'" target="'.esc_html( $permalinkTarget ).'" class="cvmm-banner-button-one">'.esc_html( $button1Label ).'</a>';
                        }
                        if( $button2Option ) {
                            echo '<a href="'.esc_url( $button2Link ).'" target="'.esc_html( $permalinkTarget ).'" class="cvmm-banner-button-two">'.esc_html( $button2Label ).'</a>';
                        }
                    echo '</div>';    
                }
            ?>
            </div><!-- .cvmm-banner-meta-wrap -->
    </div>
</div><!-- .cvmm-banner-content-wrap -->