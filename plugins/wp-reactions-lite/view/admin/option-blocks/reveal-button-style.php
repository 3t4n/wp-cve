<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Select;
use WP_Reactions\Lite\FieldManager\Text;
?>
<div class="option-wrap">
	<div class="option-header">
		<h4>
            <span><?php _e( 'Button Design', 'wpreactions' ); ?></span>
			<?php Helper::tooltip('reveal-button-style'); ?>
		</h4>
		<small><?php _e( 'Choose your styling options to customize your button.', 'wpreactions' ); ?></small>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group mb-3">
				<?php
				( new Text )
					->setId( 'reveal_button_text_color' )
					->setType( 'color-chooser' )
					->setValue( $options['reveal_button']['text_color'] )
					->setLabel( __( 'Text color', 'wpreactions' ) )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-3">
				<?php
				( new Select )
					->setId( 'reveal_button_font_size' )
					->setLabel( __( 'Font size', 'wpreactions' ) )
					->setValues( Helper::pixels( 8 ) )
					->setDefault( $options['reveal_button']['font_size'] )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-3">
				<?php
				( new Select )
					->setId( 'reveal_button_font_weight' )
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
					->setDefault( $options['reveal_button']['font_weight'] )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-3">
				<?php
				( new Text )
					->setId( 'reveal_button_bgcolor' )
					->setLabel( __( 'Background color', 'wpreactions' ) )
					->setValue( $options['reveal_button']['bgcolor'] )
					->setType( 'color-chooser' )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-3">
				<?php
				( new Text )
					->setId( 'reveal_button_border_color' )
					->setLabel( __( 'Border color', 'wpreactions' ) )
					->setValue( $options['reveal_button']['border_color'] )
					->setType( 'color-chooser' )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-3">
				<?php
				( new Select )
					->setId( 'reveal_button_border_radius' )
					->setLabel( __( 'Border radius', 'wpreactions' ) )
					->setValues( Helper::pixels( 0 ) )
					->setDefault( $options['reveal_button']['border_radius'] )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-md-0 mb-3">
				<?php
				( new Text )
					->setId( 'reveal_button_hover_bgcolor' )
					->setLabel( __( 'Hover Background color', 'wpreactions' ) )
					->setValue( $options['reveal_button']['hover_bgcolor'] )
					->setType( 'color-chooser' )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group mb-md-0 mb-3">
				<?php
				( new Text )
					->setId( 'reveal_button_hover_text_color' )
					->setLabel( __( 'Hover text color', 'wpreactions' ) )
					->setValue( $options['reveal_button']['hover_text_color'] )
					->setType( 'color-chooser' )
					->build();
				?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<?php
				( new Text )
					->setId( 'reveal_button_hover_border_color' )
					->setLabel( __( 'Hover Border color', 'wpreactions' ) )
					->setValue( $options['reveal_button']['hover_border_color'] )
					->setType( 'color-chooser' )
					->build();
				?>
			</div>
		</div>
	</div>
</div>
