<?php
use WP_Reactions\Lite\FieldManager\Select;
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
use WP_Reactions\Lite\FieldManager\Checkbox;
use WP_Reactions\Lite\FieldManager\Text;
use WP_Reactions\Lite\Helper;
?>
<div class="option-wrap">
	<div class="option-header">
		<h4>
            <span><?php _e( 'Button Design', 'wpreaction' ); ?></span>
			<?php Helper::tooltip('social-button-style'); ?>
		</h4>
	</div>
	<div class="row align-items-end">
        <div class="col-md-3">
            <?php
            ( new Select )
                ->setId( 'social_border_radius' )
                ->setLabel( __( 'Border Radius', 'wpreaction' ) )
                ->setValues( Helper::pixels( 0 ) )
                ->setDefault( $options['social']['border_radius'] )
                ->build();
            ?>
        </div>
        <div class="col-md-9">
            <?php
            ( new Radio() )
                ->setName( 'social_button_type' )
	            ->addRadio(RadioItem::create()->setId('solid')->setValue('solid')->setLabel(__( 'Solid Buttons', 'wpreactions' )))
	            ->addRadio(RadioItem::create()->setId('bordered')->setValue('bordered')->setLabel(__( 'Button with Border Only', 'wpreactions' )))
                ->setChecked( $options['social']['button_type'] )
                ->addClasses( 'form-group-inline mb-2' )
                ->build();
            ?>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <?php
            ( new Checkbox() )
                ->addCheckbox(
                    'social_style_buttons',
                    $options['social_style_buttons'],
                    __( 'Check to override classic social media colors', 'wpreaction' ),
                    'true',
                    '',
                    '<div class="wpra-pro-badge">PRO</div>',
                    false,
                    true
                    )
                ->addClasses('form-group')
                ->build();
            ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-3">
            <?php
            ( new Text )
                ->setId( 'social_border_color' )
                ->setType( 'color-chooser' )
                ->setLabel( __( 'Border Color', 'wpreaction' ) )
                ->setValue( $options['social']['border_color'] )
                ->setDisabled( true )
                ->build();
            ?>
        </div>
        <div class="col-md-3">
            <?php
            ( new Text )
                ->setId( 'social_bg_color' )
                ->setType( 'color-chooser' )
                ->setLabel( __( 'Background Color', 'wpreaction' ) )
                ->setValue( $options['social']['bg_color'] )
                ->setDisabled( true )
                ->build();
            ?>
        </div>
        <div class="col-md-3">
            <?php
            ( new Text )
                ->setId( 'social_text_color' )
                ->setType( 'color-chooser' )
                ->setLabel( __( 'Button Text Color', 'wpreaction' ) )
                ->setValue( $options['social']['text_color'] )
                ->setDisabled( true )
                ->build();
            ?>
        </div>
    </div>
</div>
