<?php
/**
 * Template Name: Campaign Single Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wpfilm-studio
 */

get_header();?>
<div class="page-wrapper clear">
	<?php
		while ( have_posts() ) : the_post();

			$postid = get_the_ID();
			$relatedtitle = wpfilm_get_option( 'wpfilm_readmore_text', 'settings' );
			$campaign_location_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_location_title', true );
			$campaign_loaction_details  = get_post_meta( get_the_ID(),'_wpfilm_campaign_loaction_details', true );
			$campaign_details_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_details_title', true );
			$campaign_date_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_date_title', true );
			$campaign_date  = get_post_meta( get_the_ID(),'_wpfilm_campaign_date', true );
			$campaign_time_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_time_title', true );
			$campaign_time  = get_post_meta( get_the_ID(),'_wpfilm_campaign_time', true );
			$campaign_map  = get_post_meta( get_the_ID(),'_wpfilm_campaign_map', true );		
			$campaign_map_lat  = get_post_meta( get_the_ID(),'_wpfilm_campaign_map_lat', true );	
			$campaign_map_lng  = get_post_meta( get_the_ID(),'_wpfilm_campaign_map_lng', true );
			$campaign_website_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_website_title', true );
			$campaign_website  = get_post_meta( get_the_ID(),'_wpfilm_campaign_website', true );
			$campaign_phone_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_phone_title', true );
			$campaign_phone  = get_post_meta( get_the_ID(),'_wpfilm_campaign_phone', true );
			$campaign_organizer_title  = get_post_meta( get_the_ID(),'_wpfilm_campaign_organizer_title', true );

			$names  = get_post_meta( get_the_ID(),'_wpfilm_name_list_campaign', true );	

if( \Elementor\Plugin::$instance->db->is_built_with_elementor( $postid ) ){
	  the_content();
}else{


	?>
            <section class="events-details-ara ptb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
							<div class="campaign_img">
								<?php 
									if(has_post_thumbnail() ){
										the_post_thumbnail( 'politic_blog_single_image', array( 'class' => 'img-responsive' ) );
									} ?>
							</div>                        	
							<div class="wpfilm_campaign_content">
								<h3><?php echo  get_the_title(); ?></h3>
								<h6 class="campaign_de_date"><?php echo esc_html( $campaign_date ); ?></h6>					
								<?php the_content(); ?>
							</div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="event-details-sidebar">
								<?php if(!empty($campaign_map)){?>
								<div class="wpfilm_map_wrapper">
									<div id="wpfilm_googleMap"></div>
								</div>
								<?php } ?>                            	
                                <div class="enother-event-details">
                                	<?php if(!empty($campaign_loaction_details)){?>
                                    <div class="wpfilm_event-list">
                                    	
                                    	<h5><?php echo esc_html( $campaign_location_title ); ?></h5>

											<p><?php echo esc_html( $campaign_loaction_details ); ?></p>

                                    </div>
                                    <?php }?>
									<?php if( !empty( $campaign_details_title ) ){ ?>
                                    <div class="wpfilm_event-list">
                                    	<h5><?php echo esc_html( $campaign_details_title ); ?></h5>
                                        <ul>
										<?php if(!empty($campaign_date_title)){ ?>
											<li><span><?php echo esc_html( $campaign_date_title ); ?></span> <?php echo esc_html( $campaign_date ); ?></li>
										<?php }?>
										<?php if(!empty($campaign_phone_title)){ ?>
											<li><span><?php echo esc_html( $campaign_phone_title ); ?></span> <?php echo esc_html( $campaign_phone ); ?></li>
										<?php }?>
										<?php if(!empty($campaign_website_title)){?>
											<li><span><?php echo esc_html( $campaign_website_title ); ?></span> <?php echo esc_html( $campaign_website ); ?></li>
										<?php }?>

										<?php if(!empty($campaign_time_title)){?>
											<li><span><?php echo esc_html( $campaign_time_title ); ?> </span><?php echo esc_html( $campaign_time ); ?></li>
										<?php } ?>
                                        </ul>
                                    </div>
									<?php } ?>
									<?php if( !empty( $campaign_organizer_title ) ){ ?>
                                    <div class="wpfilm_event-list">
                                    	<h5><?php echo esc_html( $campaign_organizer_title ); ?></h5>
                                        <ul>
										<?php if(is_array( $names )){
											foreach( $names as $name_a ){ ?>
												<li><span><?php echo esc_html($name_a['campaign_name']); ?> </span> 
												<?php echo esc_html($name_a['campaign_add']); ?></li>
											<?php  }
										}?>
                                        </ul>
                                    </div>
                                	<?php } ?>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

		<!-- Related Movie Area Start -->
		<?php
          
          } // end Content section
		$related = array(
		    'post_type'  => 'wpcampaign',
		    'post__not_in' =>array(get_the_ID()),
		);
		$relatedd = new WP_Query($related);

		if($relatedd){
		?>
		<div class="related-area-movie">
			<div class="container">
				<div class="related-title">
					<h3><?php echo esc_html__('Upcomming Campaign','wpfilm-studio');?> </h3>
				</div>
                <div class="related-trailer-active indicator-style-two">
					<?php
                        while($relatedd->have_posts()): $relatedd->the_post();
                             $campaign_date  = get_post_meta( get_the_ID(),'_wpfilm_campaign_date', true );
                             $short_des = get_post_meta( get_the_ID(),'_wpfilm_campaign_short_des', true );
                              ?> 
                        <div class="wp-campaign-box">
                            <div class="wp-campaign-content">
                                <h5><?php echo esc_html($campaign_date);?></h5>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>     
                                <?php echo '<p>'.wp_trim_words( $short_des, 30, '' ).'</p>';?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
		</div>
		<!-- Related Movie AreaArea End -->
	<?php
}

?>





<?php if (!empty($campaign_map) && !\Elementor\Plugin::$instance->db->is_built_with_elementor( $postid ) ) { ?>

		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( $campaign_map ); ?>"></script>
		<script>
			// When the window has finished loading create our google map below
			google.maps.event.addDomListener(window, 'load', init);

			function init() {
				// Basic options for a simple Google Map
				// For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
				var mapOptions = {
					// How zoomed in you want the map to start at (always required)
					zoom: 11,

					scrollwheel: false,

					// The latitude and longitude to center the map (always required)
					center: new google.maps.LatLng(<?php echo esc_html( $campaign_map_lat ); ?>, <?php echo esc_html( $campaign_map_lng ); ?>), // New York

					// How you would like to style the map. 
					// This is where you would paste any style found on Snazzy Maps.
					styles: [
								{
									"featureType": "administrative",
									"elementType": "labels.text.fill",
									"stylers": [
										{
											"color": "#444444"
										}
									]
							    },
							    {
							        "featureType": "administrative.country",
							        "elementType": "geometry.fill",
							        "stylers": [
							            {
							                "hue": "#ff0000"
							            },
							            {
							                "saturation": "-10"
							            },
							            {
							                "visibility": "simplified"
							            }
							        ]
							    },
							    {
							        "featureType": "landscape",
							        "elementType": "all",
							        "stylers": [
							            {
							                "color": "#f2f2f2"
							            }
							        ]
							    },
							    {
							        "featureType": "poi",
							        "elementType": "all",
							        "stylers": [
							            {
							                "visibility": "off"
							            }
							        ]
							    },
							    {
							        "featureType": "road",
							        "elementType": "all",
							        "stylers": [
							            {
							                "saturation": -100
							            },
							            {
							                "lightness": 45
							            }
							        ]
							    },
							    {
							        "featureType": "road.highway",
							        "elementType": "all",
							        "stylers": [
							            {
							                "visibility": "simplified"
							            }
							        ]
							    },
							    {
							        "featureType": "road.arterial",
							        "elementType": "labels.icon",
							        "stylers": [
							            {
							                "visibility": "off"
							            }
							        ]
							    },
							    {
									"featureType": "transit",
									"elementType": "all",
									"stylers": [
										{
											"visibility": "off"
										}
									]
								},
								{
									"featureType": "water",
									"elementType": "all",
									"stylers": [
										{
										"color": "#ec464b"
										},
										{
											"visibility": "on"
										}
									]
								}
							]
				};

				// Get the HTML DOM element that will contain your map 
				// We are using a div with id="map" seen below in the <body>
				var mapElement = document.getElementById('wpfilm_googleMap');

				// Create the Google Map using our element and options defined above
				var map = new google.maps.Map(mapElement, mapOptions);

				// Let's also add a marker while we're at it
				var marker = new google.maps.Marker({
				position: new google.maps.LatLng(<?php echo esc_html( $campaign_map_lat ); ?>, <?php echo esc_html( $campaign_map_lng ); ?>),
				map: map,
				title: 'Film Studio!'
			});
		}
		</script>

		<?php } ?>
			<?php
		endwhile; // End of the loop.
	?>
</div><!-- #primary -->
<?php
get_footer();