<?php
class CTBBlock{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}
	
	function onInit() {
		wp_register_style( 'ctb-countdown-time-style', CTB_DIR_URL . 'dist/style.css', [], CTB_VERSION );
		wp_register_style( 'ctb-countdown-time-editor-style', CTB_DIR_URL . 'dist/editor.css', [ 'ctb-countdown-time-style' ], CTB_VERSION );

		register_block_type( __DIR__, [
			'editor_style'		=> 'ctb-countdown-time-editor-style',
			'render_callback'	=> [$this, 'render']
		] );

		wp_set_script_translations( 'ctb-countdown-time-editor-script', 'countdown-time', CTB_DIR_PATH . '/languages' );
	}

	function render( $attributes, $content ){
		extract( $attributes );

		wp_enqueue_style( 'ctb-countdown-time-style' );
		wp_enqueue_script( 'ctb-countdown-time-script', CTB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom' ], CTB_VERSION, true );
		wp_set_script_translations( 'ctb-countdown-time-script', 'countdown-time', CTB_DIR_PATH . '/languages' );

		$className = $className ?? '';
		$extraClass = ctbIsPremium() ? 'premium' : 'free';
		$blockClassName = "wp-block-ctb-countdown-time $extraClass $className align$align";

		ob_start(); ?>
		<div
			class='<?php echo esc_attr( $blockClassName ); ?>'
			id='ctbCountdownTime-<?php echo esc_attr( $cId ) ?>' 
			data-nonce='<?php echo esc_attr( wp_json_encode( wp_create_nonce( 'wp_ajax' ) ) ); ?>'
			data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'
			data-content='<?php echo esc_attr( wp_json_encode( $content ) ); ?>'
		></div>

		<?php return ob_get_clean();
	} // Render
}
new CTBBlock;