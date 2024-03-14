<?php

use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;

?>
<div class="option-wrap">
    <div class="option-header">
        <h4>
            <span><?php _e('Emoji Animation', 'wpreactions-lite'); ?></span>
            <?php Helper::tooltip('animation-state'); ?>
        </h4>
    </div>
    <?php
    (new Radio())
        ->setName('animation')
        ->addRadio(RadioItem::create()->setId('animation_false')->setValue('false')->setLabel(__('Static', 'wpreactions-lite' )))
        ->addRadio(RadioItem::create()->setId('animation_true')->setValue('true')->setLabel(__('Animated', 'wpreactions-lite' )))
        ->setChecked($options['animation'])
        ->addClasses('form-group-inline')
        ->build();
    ?>
</div>
