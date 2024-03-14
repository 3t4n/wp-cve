<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
?>
<div class="option-wrap">
    <div class="option-header">
        <h4>
            <span><?php _e( 'Shortcode Alignment', 'wpreactions-lite' ); ?></span>
            <?php Helper::tooltip('alignment'); ?>
        </h4>
        <small><?php _e('Set your emoji reactions to align with your content.', 'wpreactions-lite'); ?></small>
    </div>
	<?php
	( new Radio() )
		->setName( 'align' )
        ->addRadio(RadioItem::create()->setId('align_left')->setValue('left')->setLabel(__( 'Left-Aligned', 'wpreactions-lite' )))
        ->addRadio(RadioItem::create()->setId('align_center')->setValue('center')->setLabel(__( 'Center-Aligned', 'wpreactions-lite' )))
        ->addRadio(RadioItem::create()->setId('align_left')->setValue('left')->setLabel(__( 'Right-Aligned', 'wpreactions-lite' )))
		->setChecked( $options['align'] )
		->addClasses( 'form-group-inline' )
		->build();
	?>
</div>
