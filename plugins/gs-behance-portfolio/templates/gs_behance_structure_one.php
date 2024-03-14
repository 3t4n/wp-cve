<?php

namespace GSBEH;
?>
<div class="gs-containeer">
    <div class="gs-roow">
        <?php
        foreach ($gs_behance_shots as $gs_beh_single_shot) {
            $bfields = unserialize($gs_beh_single_shot['bfields']);

            if (!empty($atts['field'])) {

                if (in_array($atts['field'],  array_column($bfields, 'name'))) { ?>

                    <div class="<?php echo esc_attr($columnClasses); ?> beh-projects">
                        <a href="<?php echo esc_url($gs_beh_single_shot[ 'url' ]); ?>" target="<?php echo $shortcode_settings['link_target']; ?>">
                        <?php echo plugin()->helpers->get_shot_thumbnail($gs_beh_single_shot['thum_image'], ''); ?>
                        </a>
                    </div>
                <?php } // array
            } else { ?>
                <div class="<?php echo esc_attr($columnClasses); ?> beh-projects">
                    <a href="<?php echo esc_url($gs_beh_single_shot[ 'url' ]); ?>" target="<?php echo $shortcode_settings['link_target']; ?>"><?php echo plugin()->helpers->get_shot_thumbnail($gs_beh_single_shot['thum_image'], ''); ?>
                    </a>

                </div>
        <?php
            }
        } ?>

    </div><?php
            do_action('gs_behance_custom_css'); ?>
</div>