<?php
class BICBBlock{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit() {
		wp_register_style( 'bicb-carousel-style', BICB_DIR_URL . 'dist/style.css', [], BICB_VERSION ); // Style
		wp_register_style( 'bicb-carousel-editor-style', BICB_DIR_URL . 'dist/editor.css', [ 'bicb-carousel-style' ], BICB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'bicb-carousel-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'bicb-carousel-editor-script', 'carousel-block', BICB_DIR_PATH . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'bicb-carousel-style' );
		wp_enqueue_script( 'bicb-carousel-script', BICB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom' ], BICB_VERSION, false );
		wp_set_script_translations( 'bicb-carousel-script', 'carousel-block', BICB_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$blockClassName = "wp-block-bicb-carousel $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bicbCarousel-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new BICBBlock;