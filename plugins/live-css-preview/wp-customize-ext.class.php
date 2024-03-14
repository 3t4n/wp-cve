<?php 

/**
 * Extends the WP_Customize_Control
 */
 class DojoDigitalLiveCSSPreview_Control extends WP_Customize_Control {
 
	public $type = 'DojoDigitalLiveCSSPreview';	
	
	public function __construct( $manager, $id, $args = array() ) {
	
		parent::__construct( $manager, $id, $args );
		
	}

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea <?php $this->link(); ?> id="<?php echo $this->id; ?>"><?php $this->value(); ?></textarea>
			<div id="lct-editor" style="width:100%;height:250px;"><?php $this->value(); ?></div>
		</label>
		<?php
	}
	
} // DojoDigitalLiveCSSPreview_Control()