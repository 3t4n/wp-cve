<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php 
wp_enqueue_style( "img_slider_carouesel", IMG_SLIDER_ASSETS. "css/layout-design.css",'');

$auto_play = $data->settings['auto_play'];
$slide_duration = $data->settings['slide_duration'];

$slide_duration = ($auto_play==1) ? $slide_duration : 0 ;
//echo $slide_duration;die;
?>

<style type="text/css">

	 /*custom css for full-width*/
    /*.post-inner {
      padding-top: 0rem; 
    }

    body.template-full-width .entry-content > *:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide), body.template-full-width [class*="__inner-container"] > *:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide)
    {
      max-width: max-content;
    }*/

	.carousel-inner img {
      width: 100%;
      height: 100% !important;
 	}

	.img-slider-carousel-caption {
	     bottom: 50px !important; 
	}
 
	 #img-slider-demo h5{
	  margin: 12px;
	 } 

	 #img-slider-demo .carousel-indicators{
	    position:absolute;
	    bottom:10px;
	    left:50%;
	    z-index:15;
	    width:60%;
	    padding-left:0;
	    margin-left:-30%;
	    text-align:center;
	    list-style:none
	}
	#img-slider-demo .carousel-indicators li{
	   /* display:inline-block;
	    width:10px;
	    height:10px;
	    margin:1px;
	    text-indent:-999px;
	    cursor:pointer;
	    background-color:#000\9;
	    background-color:rgba(0,0,0,0);
	    border:1px solid #fff;
	    border-radius:10px;*/

	    display:inline-block;
	    position: relative;
	    width: 30px;
	    height: 10px;
	    background-color: #fff;
	    border-radius: 10px;
	    margin:0px 3px !important;
	}
	#img-slider-demo .carousel-indicators .active{
		/*width:12px;
		height:12px;*/
		/*margin:0;*/
		background-color:#43baff !important;

		width: 30px;
	    height: 10px;
	}


    .img-slider-swiper-button-prev {
        position: relative;
        width: auto;
        height: auto;
        padding: 5px 20px;
        background: <?php echo $data->settings['controlsBgColor'] ?>; 
        border-radius: <?php echo $data->settings['contorlsBgBorderRadius'] ?>%; 
        background-position: -82px -22px;
        cursor: pointer;
        top: 45%;
        left: 30px;
        margin-top: -25px;
        position: absolute;
        z-index: 110;
    }

    .img-slider-swiper-button-next {
        position: relative;
        right: 30px;
        left: auto;
        background-position: -81px -99px;
        padding: 5px 20px;
        width: auto;
        height: auto;
        background: <?php echo $data->settings['controlsBgColor'] ?>; 
        border-radius: <?php echo $data->settings['contorlsBgBorderRadius'] ?>%;
        cursor: pointer;
        top: 45%;
        margin-top: -25px;
        position: absolute;
        z-index: 110;
    }

    #img-slider-demo .carousel-item:after{
    	background: none !important;
    }

    .img-slider-swiper-button-next:after, .img-slider-swiper-button-prev:after{
        font-size: <?php echo $data->settings['contorlsFontSize'] ?>px; 
        font-weight: bold;
        color: <?php echo $data->settings['controlsColor'] ?>;  
    }

    .img-slider-swiper-button-prev:hover,
    .img-slider-swiper-button-next:hover {
        background: <?php echo $data->settings['controlsBgColorOnHover'] ?>;
    }

    .carousel-control-prev:hover .img-slider-swiper-button-prev:after,
    .carousel-control-next:hover .img-slider-swiper-button-next:after{
        color: <?php echo $data->settings['controlsColorOnHover'] ?>;
    }

</style>

<?php
$img_count = 0;

foreach ( $data->images as $image ): ?>
			<?php 
				$img_count++;
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
				$url = wp_get_attachment_image_src($id, 'rpg_image_slider', true);
				/*--------------------------*/

				$data->loader->set_template_data( $item_data ); ?>		
	
<?php endforeach; ?>


  
 

<div id="img-slider-demo" class="carousel slide" data-ride="carousel" data-interval="<?php echo $slide_duration; ?>">
	  <!-- Indicators -->
		<?php if ( ! $data->settings['hide_navigation'] ): ?>
		  <ul class="carousel-indicators">
		    <li data-target="#img-slider-demo" data-slide-to="0" class="active"></li>
		    <?php for($x=1;$x<=$img_count-1;$x++)  { ?>
		    	<li data-target="#img-slider-demo" data-slide-to="<?php echo $x; ?>"></li>
		    <?php } ?> 
		  </ul>
		<?php endif ?>

	  <div class="carousel-inner">
	    

	    <!-- ========================================================================== -->
	    <?php 
			//wp_enqueue_style( "img_slider_index_style_7", IMG_SLIDER_ASSETS. "css/layout-design.css",'');

			$img_count = 0;

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
						$url = wp_get_attachment_image_src($id, 'rpg_image_slider', true);
						/*--------------------------*/

						$data->loader->set_template_data( $item_data ); 

						if($img_count==0)
						{ ?>
							<div class="carousel-item active">
						      <img src="<?php echo $url['0']; ?>" >
								<div class="img-slider-carousel-caption d-none d-md-block">
									<?php if( ! $data->settings['hide_title']  ): ?>
	                                    <h5 style="font-family: <?php echo $data->settings['font_family'] ?>;
	                                    
	                                    "><span style="
	                                    font-size: <?php echo $data->settings['titleFontSize'] ?>px;
	                                    color: <?php echo $data->settings['titleColor'] ?>;
	                                    background-color: <?php echo $data->settings['titleBgColor'] ?>;
	                                    			   padding: 5px;
	                                    			   border-radius: 3px;
	                                    "><?php echo $item_data['title']; ?></span></h5>
	                                <?php endif ?>

	                                <?php if( ! $data->settings['hide_description']  ): ?>
										<p style="font-family: <?php echo $data->settings['font_family'] ?>;
	                                   
										">
										<span style="line-height: normal;
										font-size: <?php echo $data->settings['captionFontSize'] ?>px;
										color: <?php echo $data->settings['captionColor'] ?>;
										background-color: <?php echo $data->settings['captionBgColor'] ?>;
													 padding: 5px;
													 border-radius: 3px;
										"><?php echo $item_data['description']; ?></span></p>
									<?php endif ?>
						  		</div>
						    </div>
						<?php } else { ?>
							<div class="carousel-item">
						      <img src="<?php echo $url['0']; ?>" >
						      <div class="img-slider-carousel-caption d-none d-md-block">
								    <?php if( ! $data->settings['hide_title'] && !empty($item_data['title']) ): ?>
	                                    <h5 style="font-family: <?php echo $data->settings['font_family'] ?>;">
	                                    <span style="
	                                    font-size: <?php echo $data->settings['titleFontSize'] ?>px;
	                                    color: <?php echo $data->settings['titleColor'] ?>;
	                                    background-color: <?php echo $data->settings['titleBgColor'] ?>;
	                                    padding: 5px;
	                                    border-radius: 3px;
	                                    "><?php echo $item_data['title']; ?></span></h5>
	                                <?php endif ?>

	                                <?php if( ! $data->settings['hide_description'] && !empty($item_data['description']) ): ?>
										<p style="font-family: <?php echo $data->settings['font_family'] ?>;">
										<span style="line-height: normal;
										font-size: <?php echo $data->settings['captionFontSize'] ?>px;
										color: <?php echo $data->settings['captionColor'] ?>;
										background-color: <?php echo $data->settings['captionBgColor'] ?>;
														padding: 5px;
														border-radius: 3px;
										"><?php echo $item_data['description']; ?></span></p>
									<?php endif ?>
						  		</div>
						    </div>
						<?php }
						$img_count++;
						?>		
				
						
					    

			<?php endforeach; ?>

	    <!-- ========================================================================== -->

	    
	  </div>
	  

	<!-- Left and right controls -->
	<?php if ( ! $data->settings['hide_navigation'] ): ?>
	  <a class="carousel-control-prev" href="#img-slider-demo" data-slide="prev">
	    <span class="img-slider-swiper-button-prev"></span>
	  </a>
	  <a class="carousel-control-next" href="#img-slider-demo" data-slide="next">
	    <span class="img-slider-swiper-button-next"></span>
	  </a>
	<?php endif ?>
</div><!-- img-slider-demo -->

<script type="text/javascript">
	/*$('#carousel').carousel({
	  data-interval="1000"
	});*/

	/*var auto_play = '<php echo $auto_play; ?>';
	auto_play = (auto_play==1) ? true : false;


	if(auto_play==false){
	console.log(auto_play);
		jQuery(window).load(function() {

	    	jQuery('.carousel').carousel('pause');
	 
		});
	}*/
</script>
