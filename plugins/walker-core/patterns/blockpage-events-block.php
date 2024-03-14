<?php

/**
 * Title: Blockpage PRO: Events List
 * Slug: walker-core/blockpage-events-block
 * Categories: blockpage-events
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"style":{"border":{"bottom":{"color":"var:preset|color|heading-color","width":"2px"}},"spacing":{"padding":{"bottom":"24px"}}}} -->
    <div class="wp-block-columns" style="border-bottom-color:var(--wp--preset--color--heading-color);border-bottom-width:2px;padding-bottom:24px"><!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('Time', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"40%"} -->
        <div class="wp-block-column" style="flex-basis:40%"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('Schedule Title', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('Speaker', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('Location', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"10%"} -->
        <div class="wp-block-column" style="flex-basis:10%"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('Price', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"15%"} -->
        <div class="wp-block-column" style="flex-basis:15%"></div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"verticalAlignment":"center","style":{"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"}},"spacing":{"padding":{"bottom":"40px","top":"40px"},"margin":{"top":"0","bottom":"0"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:40px;padding-bottom:40px"><!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('02 Jan, 2024', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"200"}},"fontSize":"small"} -->
                <h4 class="wp-block-heading has-small-font-size" style="font-style:normal;font-weight:200"><?php echo esc_html_e('11:00 - 18:00', 'walker-core') ?></h4>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('Alex Fadnis', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('Kathmandu, Nepal', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"10%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:10%"><!-- wp:heading {"level":4,"textColor":"foreground"} -->
            <h4 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('$49', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Now', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"verticalAlignment":"center","style":{"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"}},"spacing":{"padding":{"bottom":"40px","top":"40px"},"margin":{"top":"0","bottom":"0"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:40px;padding-bottom:40px"><!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('05 Jan, 2024', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"200"}},"fontSize":"small"} -->
                <h4 class="wp-block-heading has-small-font-size" style="font-style:normal;font-weight:200"><?php echo esc_html_e('10:00 - 20:00', 'walker-core') ?></h4>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('Moxley Good', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('New York, USA', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"10%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:10%"><!-- wp:heading {"level":4,"textColor":"foreground"} -->
            <h4 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('$149', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Now', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"verticalAlignment":"center","style":{"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"}},"spacing":{"padding":{"bottom":"40px","top":"40px"},"margin":{"top":"0","bottom":"0"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:40px;padding-bottom:40px"><!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('20 Jan, 2024', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"200"}},"fontSize":"small"} -->
                <h4 class="wp-block-heading has-small-font-size" style="font-style:normal;font-weight:200"><?php echo esc_html_e('8:00 - 12:40', 'walker-core') ?></h4>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('Johnathon L', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('New York, USA', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"10%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:10%"><!-- wp:heading {"level":4,"textColor":"foreground"} -->
            <h4 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('$100', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Now', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"verticalAlignment":"center","style":{"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"}},"spacing":{"padding":{"bottom":"40px","top":"40px"},"margin":{"top":"0","bottom":"0"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:40px;padding-bottom:40px"><!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('09 Feb, 2024', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"200"}},"fontSize":"small"} -->
                <h4 class="wp-block-heading has-small-font-size" style="font-style:normal;font-weight:200"><?php echo esc_html_e('9:00 - 1:40', 'walker-core') ?></h4>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:heading {"level":5,"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('Lona Tett', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300"><?php echo esc_html_e('New York, USA', 'walker-core') ?></h5>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"10%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:10%"><!-- wp:heading {"level":4,"textColor":"foreground"} -->
            <h4 class="wp-block-heading has-foreground-color has-text-color"><?php echo esc_html_e('$200', 'walker-core') ?></h4>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Now', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->