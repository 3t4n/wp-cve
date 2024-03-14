<?php if ( ! defined( 'ABSPATH' ) ) exit; 

$auto_play = $data->settings['auto_play'];
$slide_duration = $data->settings['slide_duration'];
$numOfImages = $data->settings['numOfImages'];
?>

<style type="text/css">
	.owl-wrapper{width: 100%; height: auto; overflow: hidden;}
	.item
	{
		height: auto;
	}

</style>

<div class="owl-wrapper">
		<div class="owl-carousel owl-theme">

			<?php 
			

			foreach ( $data->images as $image ): ?>
					<?php 
						
						$image_object = get_post( $image['id'] );
						if ( is_wp_error( $image_object ) || get_post_type( $image_object ) != 'attachment' ) {
							continue;
						}

						// Create array with data in order to send it to image template
						$item_data = array(
							/* Item Elements */
							'title'            => Img_Slider_Helper::get_title( $image, $data->settings['wp_field_title'] ),
							'description'      => Img_Slider_Helper::get_description( $image, $data->settings['wp_field_caption'] ),
							/*'lightbox'         => $data->settings['lightbox'],*/

							/* What to show from elements */
							'hide_navigation'  => boolval( $data->settings['hide_navigation'] ) ? true : false,
							'hide_title'       => boolval( $data->settings['hide_title'] ) ? true : false,
							'hide_description' => boolval( $data->settings['hide_description'] ) ? true : false,
						

							/* Item container attributes & classes */
							'item_classes'     => array( 'img-slider-item' ),
							'item_attributes'  => array(),

							/* Item link attributes & classes */
							'link_classes'     => array( 'tile-inner' ),
							'link_attributes'  => array(),

							/* Item img attributes & classes */
							'img_classes'      => array( 'pic' ),
							'img_attributes'   => array(
								'data-valign' => esc_attr( $image['valign'] ),
								'data-halign' => esc_attr( $image['halign'] ),
								'alt'         => esc_attr( $image['alt'] ),
							),
						);

						// Create array with data in order to send it to image template
						$image = apply_filters( 'img_slider_shortcode_image_data', $image, $data->settings );

						$item_data = apply_filters( 'img_slider_shortcode_item_data', $item_data, $image, $data->settings, $data->images );
						
						/*--image cropping--*/
						$id=$image['id'];
						$url = wp_get_attachment_image_src($id, 'rpg_image_thumbnail', true);
						/*--------------------------*/

						$data->loader->set_template_data( $item_data ); ?>		
					
						<div class="item"><h4><img src="<?php echo $url['0']; ?>" style="height: 500px;"></h4></div>
			<?php endforeach; ?>
	</div>	
			<?php if (  $data->settings['hide_navigation'] ): ?>
				<style type="text/css">
					.owl-dots, .owl-nav{
						display: none;
					}
				</style>
			<?php endif ?>

</div>

<script type="text/javascript">
var auto_play = '<?php echo $auto_play; ?>';
var slide_duration = '<?php echo $slide_duration; ?>';
var numOfImages = '<?php echo $numOfImages; ?>';

auto_play = (auto_play==1) ? true : false;

jQuery('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true, //for prev next arrow
    autoplay: auto_play,
    autoplayTimeout: slide_duration,
	navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:numOfImages
        }
    }

});


</script>