<?php
class CSBBlock{
    function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

    function onInit() {
		wp_register_style( 'csb-content-slider-block-style', CSB_DIR_URL . 'dist/style.css', [], CSB_VERSION ); // Style
		wp_register_style( 'csb-content-slider-block-editor-style', CSB_DIR_URL . 'dist/editor.css', [ 'csb-content-slider-block-style' ], CSB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'csb-content-slider-block-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'csb-content-slider-block-editor-script', 'content-slider-block', CSB_DIR_PATH . 'languages' );
	}
	
	// Render
	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'csb-content-slider-block-style' );
		wp_enqueue_script( 'csb-content-slider-block-script', CSB_DIR_URL . 'dist/script.js', [ 'wp-util', 'react', 'react-dom' ], CSB_VERSION, true );
		wp_set_script_translations( 'csb-content-slider-block-script', 'content-slider-block', CSB_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$extraClass = csbIsPremium() ? 'premium' : 'free';
		$blockClassName = "wp-block-csb-content-slider-block $extraClass $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='csbContentSlider-<?php echo esc_attr( $cId ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	}
}
new CSBBlock;