<?php
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
use WP_Reactions\Lite\Helper;
?>
<div class="option-wrap">
	<div class="row align-items-center">
		<div class="col-md-12">
			<div class="option-header">
				<h4>
                    <span><?php _e( 'Button Behavior', 'wpreactions' );?></span>
                    <?php Helper::tooltip('social-buttons-behavior'); ?>
				</h4>
				<small><?php _e( 'Choose how you want your users to engage with your social media buttons', 'wpreactions' ); ?></small>
			</div>
			<?php
			( new Radio() )
				->setName( 'enable_share_buttons' )
				->addRadio(RadioItem::create()->setId('onclick')->setValue('onclick')->setLabel(__( 'Button Reveal', 'wpreactions' )))
				->addRadio(RadioItem::create()->setId('always')->setValue('always')->setLabel(__( 'Show Buttons Always', 'wpreactions' )))
				->addRadio(RadioItem::create()
                                    ->setId('share_false')
                                    ->setValue('false')
                                    ->setLabel(__( 'Hide and Disable Buttons', 'wpreactions' ) )
                                    ->setTooltip('social-share-behavior-disable')
                )
				->setChecked( $options['enable_share_buttons'] )
				->addClasses( 'form-group-inline' )
				->build();
			?>
		</div>
	</div>
</div>
