<?php
/**
 * Google Web Story Slider In Modal.
 *
 * This file display the google webstrory slider.
 *
 * @link       https://maxenius.com/
 * @since      1.0.3
 *
 * @package    Max_web_story
 * @subpackage Max_web_story/public/assets/partials
 */
?>
<style type="text/css">
	.mws_entry-point-card-img {
	  object-fit: cover;
	  width: 100%;
	  height: 100%;
	  box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
	  border-radius: 16px;
	}
	.mws_entry-point-card-container:after {
	  top: 0;
	  right: 0;
	  left: 0;
	  bottom: 0;
	  background: linear-gradient(
	    180deg,
	    rgba(0, 0, 0, 0.0001) 49.88%,
	    rgba(0, 0, 0, 0.9) 100%
	  );
	  height: 100%;
	  width: 100%;
	  position: absolute;
	  border-radius: 16px;
	  content: "";
	}
	.mws_entry-point-card-container {
	  flex-shrink: 0;
	  cursor: pointer;
	  position: relative;
	  width: 250px; 
	  height: 350px;
	  opacity: 1;
	  transform: scale(1);
	  visibility: visible;
	  transition: opacity 0.33s, transform 0.33s, visibility 0.33s;
		overflow:hidden;
	}
	.mws_entry-point-card-container.hidden {
	  opacity: 0;
	  visibility: hidden;
	}
	.mws_entry-point-card-container:hover .background-cards {
	  transform: translate3d(24px, 0px, 0px);
	}
	.mws_author-container {
	  position: absolute;
	  display: flex;
	  align-items: center;
	  top: 24px;
	  left: 24px;
	}
	.mws_card-headline-container {
	  position: absolute;
	  top: 50px;
	  text-align: left;
	  padding: 24px;
	  z-index: 1;
	}
	.mws_entry-point-card-headline {
	  color: #fff;
	  font-weight: 700;
	  font-family: "Poppins", sans-serif;
	  text-transform: uppercase;
	  line-height: 26px;
	}
	.mws_entry-point-card-subtitle {
	  color: #fff;
	  font-weight: 700;
	  font-family: "Noto Sans", sans-serif;
	  text-transform: uppercase;
	  font-size: 11px;
	  line-height: 15px;
	}
	.mws_logo-container {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  width: 38px;
	  height: 38px;
	  position: relative;
	  margin-right: 10px;
	}
	.mws_logo-ring {
	  width: 46px;
	  height: 46px;
	  background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 220 220' width='100%25' height='100%25' preserveAspectRatio='none'><defs><linearGradient id='gradient'><stop offset='0' style='stop-color:%232BAC95' /><stop offset='1' style='stop-color:%236EB6F9' /></linearGradient></defs><ellipse ry='100' rx='100' cy='110' cx='110' style='fill:none;stroke:url(%23gradient);stroke-width:6;' /></svg>");
	  border-radius: 100%;
	  position: absolute;
	}
	.mws_entry-point-card-logo {
	  border-radius: 100%;
	  width: 38px;
	  height: 38px;
	  filter: drop-shadow(0px 0px 20px rgba(255, 255, 255, 0.25));
	}
	.mws_main_cont{
	  display:flex; 
	  flex-wrap: wrap; width:100%;
	}
	.mws_single_story_cont {
	  width:25%;
	  padding-top:20px;
	  text-align: center;
	}
	.mws_single_story_cont a{
	  display: inline-block;
	}
	@media only screen and (max-width: 1280px) {
	  .mws_entry-point-card-container{
	    width: 234px !important;
	    height: 350px !important; 
	  }
	}
	@media only screen and (max-width: 1024px) {
	   .mws_entry-point-card-container{
	    width: 165px !important;
	    height: 230px !important; 
	   }
	   .mws_entry-point-card-headline {
		    font-size: 14px;
		}
	}

	@media only screen and (max-width: 920px) {
	  .mws_single_story_cont {
	    width: 33%;
	  }
	}

	@media only screen and (max-width: 600px) {
	  .mws_single_story_cont {
	    width: 49%;
	  }
	}

	@media only screen and (max-width: 420px) {
	  .mws_entry-point-card-container {
	    width: 125px !important;
	    height: 170px !important;
	  }
	  .mws_entry-point-card-headline{
	     display: none;
	  }
	}
	@media only screen and (hover: none) and (pointer: coarse) {
	  .mws_entry-point-card-container.hidden {
	    transform: scale(1.2);
	  }
	  .mws_author-container {
	    top: 16px;
	    left: 16px;
	  }
	  .mws_logo-container,
	  .mws_entry-point-card-logo {
	    width: 20px;
	    height: 20px;
	  }
	  .mws_logo-ring {
	    width: 26px;
	    height: 26px;
	  }
	  .mws_entry-point-card-subtitle {
	    font-size: 10px;
	    line-height: 14px;
	  }
	  .mws_entry-point-card-headline {
	    font-size: 12px;
	    line-height: 20px;
	  }
	  .mws_card-headline-container {
	    padding: 16px;
	  }
	  .background-cards {
	    display: none;
	  }
	}
	@media only screen and (max-width: 280px) {
	  .mws_entry-point-card-container {
	    width: 105px !important;
	    height: 150px !important;
	  }
	}
</style>
<div class="mws_main_cont" > 
		<?php
			$defaults     = array(
				'numberposts'      => 5,
				'category'         => 0,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => array(),
				'exclude'          => array(),
				'meta_key'         => '',
				'meta_value'       => '',
				'post_type'        => 'webstories',
				'suppress_filters' => true,
			);
			$parsed_args  = wp_parse_args( $defaults );
			if ( empty( $parsed_args['post_status'] ) ) {
				$parsed_args['post_status'] = ( 'attachment' === $parsed_args['post_type'] ) ? 'inherit' : 'publish';
			}
			$get_posts = new WP_Query();
			$get_posts = $get_posts->query( $parsed_args );
			foreach ( $get_posts as $key => $get_post ) {
				$get_post_id    = $get_post->ID;
				$get_title      = $get_post->post_title;
				$description    = $get_post->post_content;
				$permalink      = $get_post->guid;
				$story_meta     = get_post_meta( $get_post_id, 'story_meta', true );
				$slider_logo    = $story_meta['publisher-logo-src'];
				$slider_potrait = $story_meta['poster-portrait-src'];
				?>
				<div class="mws_single_story_cont" >
					<a href="<?php echo esc_html( $permalink ); ?>" >
						<div class="mws_entry-point-card-container  " >
							<img src="<?php echo esc_html( $slider_potrait ); ?>" class="mws_entry-point-card-img" alt="A cat">
							<div class="mws_author-container">
							  <div class="mws_logo-container">
								<div class="mws_logo-ring"></div>
								<img class="mws_entry-point-card-logo" src="<?php echo esc_html( $slider_logo ); ?>" alt="Publisher logo">
							  </div>
							  <span class="mws_entry-point-card-subtitle"><?php echo esc_html( $get_title ); ?> </span>
							</div>
							<div class="mws_card-headline-container">
							  <span class="mws_entry-point-card-headline"><?php echo esc_html( $description ); ?></span>
							</div>
						</div>
					</a>
				</div>
				<?php
			}
			?>
			
</div>

