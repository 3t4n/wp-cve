<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
use WP_Reactions\Lite\FieldManager\Text;
use WP_Reactions\Lite\FieldManager\Checkbox;
?>
<div class="option-wrap">
    <div class="option-header">
        <h4>
            <span><?php _e( 'Emoji Background Styling', 'wpreactions' ); ?></span>
            <?php Helper::tooltip('background'); ?>
        </h4>
    </div>
    <div class="row align-items-end">
        <div class="col-md-12">
			<?php
			( new Radio() )
				->setName( 'bgcolor_trans' )
                ->addRadio(RadioItem::create()->setId('bgcolor_trans_true')->setValue('true')->setLabel(__( 'Transparent Background', 'wpreactions' )))
                ->addRadio(RadioItem::create()->setId('bgcolor_trans_false')->setValue('false')->setLabel(__( 'Background with Color', 'wpreactions' )))
				->setChecked( $options['bgcolor_trans'] )
				->addClasses( 'form-group' )
				->build();
			?>
        </div>
        <div class="col-md-6 mt-3">
			<?php
			( new Text )
				->setId( 'bgcolor' )
				->setLabel( __( 'Background Color Picker', 'wpreactions' ) )
				->setValue( $options['bgcolor'] )
				->setType( 'color-chooser' )
				->addClasses( 'mb-3 m-md-0' )
				->build();
			?>
        </div>
        <div class="col-md-6">
			<?php
			( new Checkbox() )
				->addCheckbox( 'shadow', $options['shadow'], 'Enable shadow', 'true' )
				->build();
			?>
        </div>
    </div>
</div>
