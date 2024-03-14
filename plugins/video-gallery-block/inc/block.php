<?php
class VGBBlock{
    function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'wp_enqueue_scripts', [$this, 'wpEnqueueScripts'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_register_script( 'isotope', VGB_ASSETS_DIR . 'js/isotope.pkgd.min.js', [], '3.0.6', true );
	}

	function wpEnqueueScripts(){
		wp_register_script( 'fancybox3', VGB_ASSETS_DIR . 'js/fancybox.min.js', [], '3.5.7', true );
		wp_register_style( 'fancybox3', VGB_ASSETS_DIR . 'css/fancybox.min.css', [], '3.5.7' );

		wp_register_script( 'plyr', VGB_ASSETS_DIR . 'js/plyr.js', [], '3.7.2', true );
		wp_register_style( 'plyr', VGB_ASSETS_DIR . 'css/plyr.css', [], '3.7.2' );
	}

	function onInit() {
		wp_register_style( 'vgb-video-gallery-style', VGB_DIR_URL . 'dist/style.css', [], VGB_PLUGIN_VERSION ); // Style
		wp_register_style( 'vgb-video-gallery-editor-style', VGB_DIR_URL . 'dist/editor.css', [ 'vgb-video-gallery-style' ], VGB_PLUGIN_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'vgb-video-gallery-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'vgb-video-gallery-editor-script', 'video-gallery', VGB_DIR_PATH . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'fancybox3' );
		wp_enqueue_style( 'plyr' );
		wp_enqueue_style( 'vgb-video-gallery-style' );
		wp_enqueue_script( 'vgb-video-gallery-script', VGB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom', 'jquery', 'lodash', 'isotope', 'fancybox3', 'plyr' ], VGB_PLUGIN_VERSION, true );
		wp_set_script_translations( 'vgb-video-gallery-script', 'video-gallery', VGB_DIR_PATH . 'languages' );

		// Block classes
		$className = $className ?? '';
		$blockClassName = "wp-block-vgb-video-gallery $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='vgbVideoGallery-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new VGBBlock;