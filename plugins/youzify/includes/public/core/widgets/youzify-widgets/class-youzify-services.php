<?php

class Youzify_Profile_Services_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get User Services.
        $services = get_the_author_meta( 'youzify_services', bp_displayed_user_id() );

        if ( empty( $services ) ) {
            return;
        }

        // Get Services Layout
        $services_layout = youzify_option( 'youzify_wg_services_layout', 'vertical-services-layout' );

        ?>

        <div class="youzify-services-content <?php echo $services_layout; ?>">

        <?php


            if ( 'horizontal-services-layout' != $services_layout ) {
                $limit_per_line = apply_filters( 'youzify_services_widget_max_items_per_line', 4 );
                $services_count = count( $services );
                $width = $services_count < $limit_per_line ? 100 / $services_count : 25;
                $width .= '%';
            } else {
                $width = 'initial';
            }


            // Show / Hide Services Elements
            $display_icon  = youzify_option( 'youzify_display_service_icon', 'on' );
            $display_text  = youzify_option( 'youzify_display_service_text', 'on' );
            $display_title = youzify_option( 'youzify_display_service_title', 'on' );
            $icon_border   = youzify_option( 'youzify_wg_service_icon_bg_format', 'circle' );

            foreach ( $services as $service ) :

                // Get Services Data .
                $service_icon = ! empty( $service['icon'] ) ? $service['icon'] : 'fas fa-globe';

                if ( ! $service['title'] ) {
                    continue;
                }

            ?>

            <div class="youzify-service-item" style="width: <?php echo $width; ?>;">

                <div class="youzify-service-inner">

                    <?php if ( 'on' == $display_icon && $service_icon ) : ?>
                        <div class="youzify-service-icon youzify-icons-<?php echo $icon_border; ?>">
                            <i class="<?php echo $service_icon ;?>"></i>
                        </div>
                    <?php endif; ?>

                    <div class="youzify-item-content">
                        <?php if ( 'on' == $display_title && $service['title'] ) : ?>
                            <h2 class="youzify-item-title"><?php echo sanitize_text_field( $service['title'] );?></h2>
                        <?php endif; ?>
                        <?php if ( 'on' == $display_text && $service['description'] ) : ?>
                            <p><?php echo sanitize_textarea_field( $service['description'] ); ?></p>
                         <?php endif; ?>
                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <?php
    }

}