<?php

if(!class_exists('VBBBlock')){
	class VBBBlock{
		public function __construct(){
			add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
			add_action( 'init', [$this, 'onInit'] );
		}
	
		function enqueueBlockAssets(){
			wp_register_style( 'vbb-video-bg-style', VBB_DIR_URL . 'dist/style.css', [], VBB_VERSION ); // Style
		}
	
		function onInit(){
			wp_register_style( 'vbb-video-bg-editor-style', VBB_DIR_URL . 'dist/editor.css', [ 'vbb-video-bg-style' ], VBB_VERSION ); // Backend Style
	
			register_block_type( __DIR__, [
				'editor_style'		=> 'vbb-video-bg-editor-style',
				'render_callback'	=> [$this, 'render']
			] ); // Register Block
	
			wp_set_script_translations( 'vbb-video-bg-editor-script', 'model-viewer', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
		}
	
		function render( $attributes, $content ){
			extract( $attributes );
	
			wp_enqueue_style( 'vbb-video-bg-style' );
			// wp_enqueue_script( 'vbb-video-bg-script', VBB_DIR_URL . 'dist/script.js', [], VBB_VERSION, true ); // Not Needed
	
			$className = $className ?? '';
			$blockClassName = "wp-block-vbb-video-bg $className align$align";

			$mainSl = "#vbbVideoBG-$cId";
			$styles = "$mainSl{
				min-height: $minHeight;
			}
			$mainSl .vbbVideoContent{
				justify-content: $verticalAlign;
				text-align: $textAlign;
				min-height: $minHeight;
				padding: ". $this->getSpaceCSS( $padding ) .";
			}
			$mainSl .vbbVideoOverlay{
				". $this->getBackgroundCSS( $bgOverlay ) ."
			}";
	
			ob_start(); ?>
			<div class='<?php echo esc_attr( $blockClassName ); ?>' id='vbbVideoBG-<?php echo esc_attr( $cId ) ?>'>
				<style>
					<?php echo esc_html( $styles ); ?>
				</style>

				<video autoplay muted loop playsinline class='vbbVideoPlayer' poster='<?php echo esc_attr( $poster['url'] ) ?>'>
					<source src='<?php echo esc_attr( $video['url'] ) ?>' type='video/mp4' />

					Your browser does not support HTML5 video.
				</video>

				<div class='vbbVideoOverlay'></div>

				<div class='vbbVideoContent'>
					<?php echo wp_kses_post( $content ); ?>
				</div>
			</div>
	
			<?php return ob_get_clean();
		}

		function getBackgroundCSS( $bg, $isSolid = true, $isGradient = true, $isImage = true ) {
			extract( $bg );
			$type = $type ?? 'solid';
			$color = $color ?? '#000000b3';
			$gradient = $gradient ?? 'linear-gradient(135deg, #4527a4, #8344c5)';
			$image = $image ?? [];
			$position = $position ?? 'center center';
			$attachment = $attachment ?? 'initial';
			$repeat = $repeat ?? 'no-repeat';
			$size = $size ?? 'cover';
			$overlayColor = $overlayColor ?? '#000000b3';
		
			$gradientCSS = $isGradient ? "background: $gradient;" : '';
		
			$imgUrl = $image['url'] ?? '';
			$imageCSS = $isImage ? "background: url($imgUrl); background-color: $overlayColor; background-position: $position; background-size: $size; background-repeat: $repeat; background-attachment: $attachment; background-blend-mode: overlay;" : '';
		
			$solidCSS = $isSolid ? "background: $color;" : '';
		
			$styles = 'gradient' === $type ? $gradientCSS : ( 'image' === $type ? $imageCSS : $solidCSS );
		
			return $styles;
		}

		function getSpaceCSS( $space ) {
			extract( $space );
			$side = $side ?? 2;
			$vertical = $vertical ?? '0px';
			$horizontal = $horizontal ?? '0px';
			$top = $top ?? '0px';
			$right = $right ?? '0px';
			$bottom = $bottom ?? '0px';
			$left = $left ?? '0px';
		
			$styles = ( 2 === $side ) ? "$vertical $horizontal" : "$top $right $bottom $left";
	
			return $styles;
		}
	}
	new VBBBlock();
}