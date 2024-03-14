<?php
class PCLBBlock{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit() {
		wp_register_style( 'pclb-price-calculator-style', PCLB_DIR_URL . 'dist/style.css', [], PCLB_VERSION );
		wp_register_style( 'pclb-price-calculator-editor-style', PCLB_DIR_URL . 'dist/editor.css', [ 'pclb-price-calculator-style' ], PCLB_VERSION );

		register_block_type( __DIR__, [
			'editor_style'		=> 'pclb-price-calculator-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'pclb-price-calculator-editor-script', 'price-calculator', PCLB_DIR_PATH . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'pclb-price-calculator-style' );
		wp_enqueue_script( 'pclb-price-calculator-script', PCLB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom', 'lodash' ], true );
		wp_set_script_translations( 'pclb-price-calculator-script', 'price-calculator', PCLB_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$blockClassName = "wp-block-pclb-price-calculator $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='pclbPriceCalculator-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new PCLBBlock;