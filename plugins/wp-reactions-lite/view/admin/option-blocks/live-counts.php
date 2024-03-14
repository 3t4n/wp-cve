<?php

use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Checkbox;
use WP_Reactions\Lite\FieldManager\Text;

$tooltip_content = Helper::is('global') ? 'live-counts-global' : 'live-counts-sgc';
?>

<div class="option-wrap">
    <div class="option-header">
        <h4>
            <span><?php _e('Badges', 'wpreaction'); ?></span>
            <?php Helper::tooltip($tooltip_content); ?>
        </h4>
        <small><?php _e('Set up and style your badges', 'wpreaction'); ?></small>
    </div>
    <div class="row align-items-center">
        <div class="col-md-6">
            <?php
            (new Checkbox())
                ->addCheckbox(
                    'show_count',
                    $options['show_count'],
                    __('Enable/Disable', 'wpreaction'),
                    'true',
                    '',
                    '<div class="wpra-pro-badge">PRO</div>',
                    false,
                    true
                )
                ->build();
            ?>
            <div class="row mt-3">
                <div class="col-md-6">
                    <?php
                    (new Text)
                        ->setId('count_color')
                        ->setType('color-chooser')
                        ->setValue($options['count_color'])
                        ->setLabel(__('Badge Color', 'wpreaction'))
                        ->build();
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    (new Text)
                        ->setId('count_text_color')
                        ->setType('color-chooser')
                        ->setValue($options['count_text_color'])
                        ->setLabel(__('Number Color', 'wpreaction'))
                        ->build();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
