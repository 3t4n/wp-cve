<?php
class ATBAnimatedText{
	public function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_register_style( 'animate', ATB_DIR_URL . 'assets/css/animate.min.css', [], '4.1.1' );
		wp_register_script( 'textillate', ATB_DIR_URL . 'assets/js/jquery.textillate.min.js', [ 'jquery' ], ATB_VERSION, true );
	}

	function onInit(){
		wp_register_style( 'atb-animated-text-style', ATB_DIR_URL . 'dist/style.css', [ 'animate' ], ATB_VERSION ); // Style
		wp_register_style( 'atb-animated-text-editor-style', ATB_DIR_URL . 'dist/editor.css', [ 'atb-animated-text-style' ], ATB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'atb-animated-text-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block
		
		wp_set_script_translations( 'atb-animated-text-editor-script', 'animated-text', ATB_DIR_PATH . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'atb-animated-text-style' );
		wp_enqueue_script( 'atb-animated-text-script', ATB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom', 'textillate' ], ATB_VERSION, true );
		wp_set_script_translations( 'atb-animated-text-script', 'animated-text', ATB_DIR_PATH . 'languages' ); // Translate

		$className = $className ?? '';
		$blockClassName = "wp-block-atb-animated-text $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='atbAnimatedText-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	}
}
new ATBAnimatedText();