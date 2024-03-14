<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
?>

<div class="option-wrap">
    <div class="option-header">
        <h4 class="mb-3">
            <span><?php _e( 'On-Page Placement Options', 'wpreactions' ); ?></span>
			<?php Helper::tooltip( 'placement' ); ?>
        </h4>
    </div>
    <div class="row">
        <div class="col-md-4">
            <p class="d-inline-block m-0 mb-3"><?php _e( 'Insert on:', 'wpreactions' ); ?></p>
			<?php
			( new Radio() )
				->setName( 'display_where' )
				->addRadio(RadioItem::create()->setId('display_post')->setValue('post')->setLabel(__( 'Posts', 'wpreactions-lite' )))
				->addRadio(RadioItem::create()->setId('display_page')->setValue('page')->setLabel(__( 'Pages', 'wpreactions-lite' )))
				->addRadio(RadioItem::create()->setId('display_both')->setValue('both')->setLabel(__( 'Both', 'wpreactions-lite' )))
				->addRadio(RadioItem::create()
                                    ->setId('display_manual')
                                    ->setValue('manual')
                                    ->setTooltip('placement-opt-manual')
                                    ->setLabel(__( 'Manual Mode', 'wpreactions-lite' ))
                )
                ->setChecked( $options['display_where'] )
				->addClasses( 'form-group' )
				->build();
			?>
        </div>
        <div class="col-md-4">
            <p class="d-inline-block m-0 mb-3"><?php _e( 'Display:', 'wpreactions' ); ?></p>
			<?php
			( new Radio() )
				->setName( 'content_position' )
                ->addRadio(RadioItem::create()->setId('before_content')->setValue('before')->setLabel(__( 'Before content', 'wpreactions' )))
                ->addRadio(RadioItem::create()->setId('after_content')->setValue('after')->setLabel(__( 'After content', 'wpreactions' )))
                ->addRadio(RadioItem::create()->setId('both_content')->setValue('both')->setLabel(__( 'Before & After content', 'wpreactions' )))
				->setChecked( $options['content_position'] )
				->addClasses( 'form-group' )
				->build();
			?>
        </div>
        <div class="col-md-4">
            <p class="d-inline-block m-0 mb-3"><?php _e( 'Align:', 'wpreactions' ); ?></p>
			<?php
			( new Radio() )
				->setName( 'align' )
				->addRadio(RadioItem::create()->setId('align_left')->setValue('left')->setLabel(__( 'Left', 'wpreactions' )))
				->addRadio(RadioItem::create()->setId('align_center')->setValue('center')->setLabel(__( 'Center', 'wpreactions' )))
				->addRadio(RadioItem::create()->setId('align_right')->setValue('right')->setLabel(__( 'Right', 'wpreactions' )))
				->setChecked( $options['align'] )
				->addClasses( 'form-group' )
				->build();
			?>
        </div>
    </div>
</div>
