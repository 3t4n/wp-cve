<?php
class ETFTwitterFeeds{
	public function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit() {
		wp_register_style( 'etf-twitter-feed-style', ETF_DIR_URL . 'dist/style.css', [], ETF_VERSION ); // Style
		wp_register_style( 'etf-twitter-feed-editor-style', ETF_DIR_URL . 'dist/editor.css', [ 'etf-twitter-feed-style' ], ETF_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'etf-twitter-feed-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'etf-twitter-feed-editor-script', 'twitter-feed', ETF_DIR_PATH . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'etf-twitter-feed-style' );
		wp_enqueue_script( 'etf-twitter-feed-script', ETF_DIR_URL . 'dist/script.js', [ 'wp-util', 'react', 'react-dom' ], ETF_VERSION, true );
		wp_set_script_translations( 'etf-twitter-feed-script', 'twitter-feed', ETF_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$extraClass = etfIsPremium() ? 'premium' : 'free';
		$blockClassName = "wp-block-etf-twitter-feed $extraClass $className align$align";

		ob_start(); ?>
<div class='<?php echo esc_attr( $blockClassName ); ?>' id='etfTwitterFeed-<?php echo esc_attr( $cId ) ?>'
    data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

<?php return ob_get_clean();
	}
}
new ETFTwitterFeeds();