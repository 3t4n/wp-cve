<?php
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
use WP_Reactions\Lite\Helper;
?>
<div class="option-wrap">
    <div class="option-header">
        <h4>
            <span><?php _e( 'Emoji Sizes', 'wpreactions' ); ?></span>
            <?php Helper::tooltip('emoji-size'); ?>
        </h4>
    </div>
	<?php
	( new Radio() )
		->setName( 'size' )
        ->addRadio(RadioItem::create()->setId('small')->setValue('small')->setLabel(__( 'Small', 'wpreactions' )))
        ->addRadio(RadioItem::create()->setId('medium')->setValue('medium')->setLabel(__( 'Medium', 'wpreactions' )))
        ->addRadio(RadioItem::create()->setId('large')->setValue('large')->setLabel(__( 'Large', 'wpreactions' )))
        ->addRadio(RadioItem::create()->setId('xlarge')->setValue('xlarge')->setLabel(__( 'X-Large', 'wpreactions' )))
		->setChecked( $options['size'] )
		->addClasses( 'form-group-inline' )
		->build();
	?>
</div>
