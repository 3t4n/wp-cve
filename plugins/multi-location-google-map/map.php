<script>

			function cloudlyupintiMap() {
				<?php
				$number = 1;
        global $post;
				$args = array(
				// Arguments for your query.
				'post_type' => 'clupmap'
				);
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
				?>

				<?php $meta = get_post_meta( $post->ID, 'cloudlyup_maulti_location_gmap_fields', true ); ?>

			  // The location of Uluru
			  var address<?php echo $number ?> = {lat: <?php echo $meta['latitude']; ?>, lng: <?php echo $meta['longitude']; ?>};

			  // The map, centered at Uluru
				<?php if( $number == 1){ ?>
			  var map = new google.maps.Map(
			      document.getElementById('map'), {zoom: <?php echo get_option('gmap_zoom'); ?>, center: address<?php echo $number ?>, gestureHandling: 'greedy'});
				<?php } ?>

				// window

				var window<?php echo $number ?> = new google.maps.InfoWindow;
        window<?php echo $number ?>.setContent('<?php echo'<h3>'; the_title(); echo'</h3><div class="addimage">'; the_post_thumbnail(); echo'</div><ul><li>'; echo $meta['lineone']; echo'</li><li>'; echo $meta['linetwo']; echo'</li><li>'; echo $meta['linethree']; echo'</li></ul>';?>');

			  // The marker, positioned at Uluru
			  var marker<?php echo $number ?> = new google.maps.Marker({position: address<?php echo $number ?>, map: map});
				marker<?php echo $number ?>.addListener('mouseover', function() {
          window<?php echo $number ?>.open(map, marker<?php echo $number ?>);
        });
				marker<?php echo $number ?>.addListener('mouseout', function() {
          window<?php echo $number ?>.close(map, marker<?php echo $number ?>);
        });

				<?php
				$number++;
				endwhile;
				wp_reset_postdata();
				endif;
				?>

			}
			    </script>

					<script async defer

					    src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option('gmap_api_key'); ?>&callback=cloudlyupintiMap&style=element:geometry%7Ccolor:0xf5f5f5">

					    </script>

			<div class="map_container">
			<div id="map"></div>
		  </div>
