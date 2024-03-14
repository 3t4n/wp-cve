<?php
class CTRBBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_register_style( 'fontAwesome', CTRB_DIR_URL . 'assets/css/fontAwesome.min.css', [], '5.15.4' );
	}

	function onInit() {
		wp_register_style( 'ctrb-counters-style', CTRB_DIR_URL . 'dist/style.css', [ 'fontAwesome' ], CTRB_VERSION ); // Style
		wp_register_style( 'ctrb-counters-editor-style', CTRB_DIR_URL . 'dist/editor.css', [ 'ctrb-counters-style' ], CTRB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'ctrb-counters-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block
		
		wp_set_script_translations( 'ctrb-counters-editor-script', 'counters-block', CTRB_DIR_PATH . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'ctrb-counters-style' );
		wp_enqueue_script( 'ctrb-counters-script', CTRB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom' ], CTRB_VERSION, true );
		wp_set_script_translations( 'ctrb-counters-script', 'counters-block', CTRB_DIR_PATH . 'languages' ); // Translate

		$className = $className ?? '';
		$blockClassName = "wp-block-ctrb-counters $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='ctrbCounters-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ) ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new CTRBBlock;