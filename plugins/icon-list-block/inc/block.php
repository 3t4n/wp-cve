<?php
class ILBBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_register_style( 'fontAwesome', ILB_DIR_URL . 'assets/css/font-awesome.min.css', [], '6.4.2' ); // Icon
	}

	function onInit() {
		wp_register_style( 'ilb-icon-list-style', ILB_DIR_URL . 'dist/style.css', [ 'fontAwesome' ], ILB_VERSION ); // Style
		wp_register_style( 'ilb-icon-list-editor-style', ILB_DIR_URL . 'dist/editor.css', [ 'ilb-icon-list-style' ], ILB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'ilb-icon-list-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'ilb-icon-list-editor-script', 'icon-list', ILB_DIR_PATH . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'ilb-icon-list-style' );
		wp_enqueue_script( 'ilb-icon-list-script', ILB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom' ], ILB_VERSION, true );
		wp_set_script_translations( 'ilb-icon-list-script', 'icon-list', ILB_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$blockClassName = "wp-block-ilb-icon-list $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='ilbIconList-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new ILBBlock();