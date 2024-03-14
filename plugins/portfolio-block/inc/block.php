<?php
class PFBBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_register_script( 'isotope', PFB_DIR_URL . 'assets/js/isotope.pkgd.min.js', [], '3.0.6', false );
	}

	function onInit() {
		wp_register_style( 'pfb-portfolio-style', PFB_DIR_URL . 'dist/style.css', PFB_PLUGIN_VERSION );
		wp_register_style( 'pfb-portfolio-editor-style', PFB_DIR_URL . 'dist/editor.css', [ 'pfb-portfolio-style' ], PFB_PLUGIN_VERSION );

		register_block_type( __DIR__, [
			'editor_style'		=> 'pfb-portfolio-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'pfb-portfolio-editor-script', 'portfolio-block', PFB_DIR_PATH . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'pfb-portfolio-style' );
		wp_enqueue_script( 'pfb-portfolio-script', PFB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom', 'isotope' ], false );
		wp_set_script_translations( 'pfb-portfolio-script', 'portfolio-block', PFB_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$blockClassName = "wp-block-pfb-portfolio $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='pfbPortfolio-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new PFBBlock;