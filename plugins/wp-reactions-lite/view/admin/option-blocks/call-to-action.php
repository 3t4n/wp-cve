<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Radio;
use WP_Reactions\Lite\FieldManager\RadioItem;
use WP_Reactions\Lite\FieldManager\Text;
use WP_Reactions\Lite\FieldManager\Select;
?>
<div class="option-wrap">
	<div class="row">
		<div class="col-md-12">
			<div class="option-header">
				<h4>
                    <span><?php _e( 'Call to Action' ); ?></span>
                    <?php Helper::tooltip('call-to-action'); ?>
				</h4>
                <small><?php _e('Write a message located above your emojis.', 'wpreactions-lite'); ?></small>
			</div>
			<?php
			( new Radio() )
				->setName( 'show_title' )
				->addRadio(RadioItem::create()->setId('title_true')->setValue('true')->setLabel(__('Show CTA', 'wpreactions-lite' )))
				->addRadio(RadioItem::create()->setId('title_false')->setValue('false')->setLabel(__('Hide CTA', 'wpreactions-lite' )))
				->setChecked( $options['show_title'] )
				->addClasses( 'form-group-inline mb-3' )
				->build();
			?>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-12">
			<?php
			( new Text )
				->setId( 'title_text' )
				->setValue( $options['title_text'] )
				->build();
			?>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php
				( new Select )
					->setId( 'title_size' )
					->setLabel( __( 'Font size', 'wpreactions' ) )
					->setValues( Helper::pixels( 8 ) )
					->setDefault( $options['title_size'] )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php
				( new Select )
					->setId( 'title_weight' )
					->setLabel( __( 'Font weight', 'wpreactions' ) )
					->setValues( [
						'100' => '100',
						'200' => '200',
						'300' => '300',
						'400' => '400',
						'500' => '500',
						'600' => '600',
						'700' => '700',
					] )
					->setDefault( $options['title_weight'] )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<?php
			( new Text )
				->setId( 'title_color' )
				->setLabel( __( 'Color', 'wpreactions' ) )
				->setValue( $options['title_color'] )
				->setType( 'color-chooser' )
				->build();
			?>
		</div>
	</div>
</div>
