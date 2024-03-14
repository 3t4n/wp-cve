<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
/**
* Script styles to run jQuery on pages
*/
add_action( 'wp_enqueue_scripts', 'outofstock_setup_scripts' );

function outofstock_setup_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
}

	/**
	* Enqueue scripts
	*/
	//add_action('wp_footer','outofstock_scripts',5);

	function outofstock_scripts() { ?>

	<?php $outofstock_image_url = get_option('outofstock_image_url'); ?>

	<?php if ( isset( $outofstock_image_url ) ) { ?>

		<script type="text/javascript">

			jQuery(document).ready(function($){

				$(".outofstock a img").each(function() {

				});

		  	});

		</script>

		<?php } ?>

	<?php }

	/**
	* Enqueue styles
	*/
	add_action('wp_footer','outofstock_styles',10);

	function outofstock_styles() { ?>

	<?php $outofstock_image_url = get_option('outofstock_image_url'); ?>

	<?php if (get_option('outofstock_image_url')) { ?>

		<style type="text/css">

			<?php $background_image = "background-image: url(" . esc_url( $outofstock_image_url ) . ");" ?>

				.outofstock .images a:before {
				<?php echo $background_image; ?>
				/*background-color: rgba(255,255,255,.3);*/
				background-repeat: no-repeat;
				background-position: center top;
				display: inherit !important; 
    			opacity: .8;
    			z-index: 1 !important;
    			float: none;
    			clear: both;
			}

			.products .outofstock a:before {
				<?php echo $background_image; ?>
				/*background-color: rgba(255,255,255,.3);*/
				background-repeat: no-repeat;
				background-position: center top;
				display: inherit !important;
    			opacity: .8;
    			z-index: 1 !important;
    			float: none;
    			clear: both;
			}
			.products .outofstock .button:before {
				background:none !important;
				display: inherit !important;
				}
			.outofstock .images .thumbnails a:before {
				background:none !important;
				display: inherit !important;
			}

			</style>

		<?php } else {
			$outofstock_image_url = plugins_url('assets/sign-pin.png', dirname(__FILE__)); ?>

			<style type="text/css">

			<?php $background_image = "background-image: url(" . $outofstock_image_url . ");" ?>

				.outofstock .images a:before {
				<?php echo $background_image; ?>
				/*background-color: rgba(255,255,255,.3);*/
				background-repeat: no-repeat;
				background-position: center top;
				display: inherit !important; 
    			opacity: .8;
    			z-index: 1 !important;
			}

			.products .outofstock a:before {
				<?php echo $background_image; ?>
				/*background-color: rgba(255,255,255,.3);*/
				background-repeat: no-repeat;
				background-position: center top;
				display: inherit !important;
    			opacity: .8;
    			z-index: 1 !important;
			}
			.products .outofstock .button:before {
				background:none !important;
				display: inherit !important;
				}
			.outofstock .images .thumbnails a:before {
				background:none !important;
				display: inherit !important;
			}

			</style>
		<?php }
	}
