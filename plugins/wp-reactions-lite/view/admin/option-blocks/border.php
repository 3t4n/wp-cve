<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager\Select;
use WP_Reactions\Lite\FieldManager\Text;
?>
<div class="option-wrap">
	<div class="option-header">
		<h4>
            <span><?php _e( 'Background Border Styling', 'wpreactions' ); ?></span>
            <?php Helper::tooltip('border'); ?>
        </h4>
	</div>
	<div class="row align-items-end">
		<div class="col-md-6">
			<?php
			( new Select )
				->setId( 'border_radius' )
				->setLabel( __( 'Border Radius', 'wpreactions' ) )
				->setValues( Helper::pixels( 0 ) )
				->setDefault( $options['border_radius'] )
				->addClasses( 'form-group mb-3' )
				->build();
			?>
		</div>
		<div class="col-md-6">
			<?php
			( new Select )
				->setId( 'border_width' )
				->setLabel( __( 'Border Width', 'wpreactions' ) )
				->setValues( Helper::pixels( 0 ) )
				->setDefault( $options['border_width'] )
				->addClasses( 'form-group mb-3' )
				->build();
			?>
		</div>
		<div class="col-md-6">
			<?php
			( new Text() )
				->setId( 'border_color' )
				->setLabel( __( 'Border Color', 'wpreactions' ) )
				->setType( 'color-chooser' )
				->setValue( $options['border_color'] )
				->addClasses( 'form-group mb-md-0 mb-3' )
				->build();
			?>
		</div>
		<div class="col-md-6">
			<?php
			( new Select )
				->setId( 'border_style' )
				->setLabel( __( 'Border Style', 'wpreactions' ) )
				->setValues( [
					'dotted' => 'dotted',
					'dashed' => 'dashed',
					'solid'  => 'solid',
					'double' => 'double',
					'groove' => 'groove',
					'ridge'  => 'ridge',
					'none'   => 'none',
				] )
				->setDefault( $options['border_style'] )
				->addClasses( 'form-group' )
				->build();
			?>
		</div>
	</div>
</div>
