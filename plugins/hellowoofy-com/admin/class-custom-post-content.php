<?php
/**
 * Custom Post Content.
 *
 * PHP version 7
 *
 * @package  Custom_Post_Content
 */

/**
 * Custom Post Content.
 *
 * Template Class
 *
 * @package  Custom_Post_Content
 */
class Custom_Post_Content {
	/** Max show Story */
	public function mws_show_story() {

		$post_id         = get_the_ID();

		$post_meta       = get_post_meta( $post_id, 'mws_webstory_pages', true );

		$story_meta      = get_post_meta( $post_id, 'story_meta', true );

		$pub_logo        = $story_meta['publisher-logo-src'];

		$pos_src         = $story_meta['poster-portrait-src'];

		$title           = get_the_title( $post_id );

		$story_permalink = get_permalink();

		$google_id = get_option( 'mws_google_analytic_id' );
		if ( empty( $google_id ) ) {
			$google_id = '';
		}

		// assets.
		$get_total_path   = dirname( plugin_dir_url( __FILE__ ) );
		$ampproject_v0    = 'https://cdn.ampproject.org/v0.js';
		$amp_video_scr    = 'https://cdn.ampproject.org/v0/amp-video-0.1.js';
		$amp_story_src    = 'https://cdn.ampproject.org/v0/amp-story-1.0.js';
		$amp_analytic_src = 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js';
		$amp_audio_src    = 'https://cdn.ampproject.org/v0/amp-audio-0.1.js';
		$poster_src       = $get_total_path . '/assets/img/icon.png';

		$urls = [];
        foreach ( $post_meta as $key => $value ) {
        	$this->media_src = '';
        	$this->media_arr = [];
        	$this->story_img = $value['page-image'];
			$this->story_vid = $value['page-video'];
			if ( ! empty( $this->story_img ) ) {
				 $this->media_src = $this->story_img;
			} elseif ( $this->story_vid ) {
				 $this->media_src = $this->story_vid;
			}
			$urls[] = '"' . $this->media_src . '"'; 
		} 
		$modified_date    =  get_the_modified_date('Y-m-d H:i:s', $post_id);
	    $creation_date    =  get_the_time('Y-m-d H:i:s', $post_id);
	    $mws_story_des    = get_post_field('post_content', $post_id);

		?>

	  <!doctype html>

		<html ⚡>

		  <head>

			<meta charset="utf-8">

			<title><?php echo esc_html( $title ); ?></title>
			<link rel="canonical" href="<?php echo esc_html( $story_permalink ); ?>">
			<meta name="viewport" content="width=device-width">
		    <link rel="preconnect" href="https://cdn.ampproject.org">
		    <meta name="amp-story-generator-name" content="HelloWoofy.com">
		    <meta name="amp-story-generator-version" content="1.0.0">    
		    <meta name="description" content="<?php echo $mws_story_des; ?>">
		    <meta name="robots" content="follow, index"> 
		    <meta property="og:locale" content="en_US">
		    <meta property="og:type" content="article">
		    <meta property="og:title" content="<?php echo $title; ?>"> 
		    <meta property="og:description" content="<?php echo $mws_story_des; ?>"> 
		    <meta property="og:url" content="<?php echo $story_permalink; ?>">      
		    <meta property="og:site_name" content="<?php echo get_site_url();?>">
		    <meta property="og:updated_time" content="<?php echo $modified_date; ?>">    
		    <meta property="article:published_time" content="<?php echo $creation_date; ?>">
		    <meta property="article:modified_time" content="<?php echo $modified_date; ?>">
			<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

			<script async 

				src="<?php echo esc_html( $ampproject_v0 ); ?>"

			></script>

			<script async custom-element="amp-video"

				src="<?php echo esc_html( $amp_video_scr ); ?>"></script>

			<script async custom-element="amp-story"

				src="<?php echo esc_html( $amp_story_src ); ?>"></script> 

	
			<script async custom-element="amp-analytics" 

			src="<?php echo esc_html( $amp_analytic_src ); ?>"></script>

			<script async custom-element="amp-audio" 

			src="<?php echo esc_html( $amp_audio_src ); ?>"></script>

 
			<style amp-custom>

			</style>
			<!-- AMP Structure data/ schema -->
			<script type="application/ld+json">
				{
				 "@context": "http://schema.org",                           
				 "@type": "Article",
				 "mainEntityOfPage":{
				   "@type":"WebPage",
				   "@id":"<?php echo !empty($story_permalink) ? $story_permalink : ''; ?>"               
				 },
				 "headline": "<?php echo !empty($title) ? $title : ''; ?>",   
				 "image": {
				   "@type": "ImageObject",
				   <?php  if(!empty($urls)){?>
				   "url": [
				      <?php echo implode(',', $urls); ?>			        
				      ]
					<?php } ?>
				 },
				 "datePublished": "<?php echo !empty($creation_date) ? $creation_date : ''; ?>",                        
				 "dateModified": "<?php echo !empty($modified_date) ? $modified_date : ''; ?>",                        
				 "author": {
				   "@type": "Person",                                                 
				   "name": "HelloWoofy.com",                                               
				   "url": "https://hellowoofy.com"                                   
				 },
				 "publisher": {
				   "@type": "Organization",                                         
				   "name": "⚡ AMP Times",                                         
				   "logo": {
				     "@type": "ImageObject",
				     "url": "<?php echo !empty($pub_logo) ? $pub_logo : '' ?>"           
				   }
				 },
				 "description": "<?php echo !empty($mws_story_des) ? $mws_story_des : ''; ?>"             
				}
			</script>

		  </head>

		  <body>
			<!-- Cover page -->
			<amp-story standalone 

				title="Joy of Pets"

				publisher="AMP tutorials"

				publisher-logo-src="<?php echo esc_attr( $pub_logo ); ?>"

				poster-portrait-src="<?php echo esc_attr( $pos_src ); ?>">
				<amp-analytics type="gtag" data-credentials="include">
					<script type="application/json">
					   {
						 "vars": {
						   "gtag_id": "<?php echo esc_html( $google_id ); ?>",
						   "config": {
							 "<?php echo esc_html( $google_id ); ?>": {
							   "groups": "default"
							 }
						   }
						 },
						 "triggers": {
						   "storyProgress": {
							 "on": "story-page-visible",
							 "vars": {
							   "event_name": "custom",
							   "event_action": "story_progress",
							   "event_category": "<?php echo esc_html( $title ); ?>",
							   "event_label": "<?php echo esc_html( $story_permalink ); ?>",
							   "send_to": ["<?php echo esc_html( $google_id ); ?>"]
							 }
						   },
						   "storyEnd": {
							 "on": "story-last-page-visible",
							 "vars": {
							   "event_name": "custom",
							   "event_action": "story_complete",
							   "event_category": "<?php echo esc_html( $title ); ?>",
							   "send_to": ["<?php echo esc_html( $google_id ); ?>"]
							 }
						   }
						 }
					   }
					</script>
				</amp-analytics>
			<?php
			$index = 0;
			$story_img = '';
			$story_vid = '';
			$get_products = '';
			$img_id = '';
			$vid_id = '';
			foreach ( $post_meta as $key => $value ) {
				global $wpdb;
				$this->img_id = '';
				$this->vid_id = '';
				$this->audio  = '';
				$this->story_img = $value['page-image'];
				$this->story_vid = $value['page-video'];
				$this->page_audio  = $value['page-audio'];

				if ( ! empty( $this->story_img ) ) {
					$this->img_id = $this->story_img;
				} elseif ( $this->story_vid ) {
					$this->vid_id = $this->story_vid;
				}
				$story_text = $value['page-title'];
				$story_desc = $value['page-description'];
				$story_btn = $value['button-info'];
				$btn_text = $story_btn['button-text'];
				$btn_link = $story_btn['button-link'];
				$btn_bg_color = $story_btn['button-bg-color'];
				$btn_color = $story_btn['button-color'];
				$btn_family = $story_btn['font-family'];
				$btn_size = ! empty( $story_btn['font-size'] ) ? $story_btn['font-size'] : '';
				?>
				<amp-story-page id="<?php echo esc_html( $index ); ?>" auto-advance-after="7s" >

					<amp-story-grid-layer template="fill">

						<?php

						if ( ! empty( $this->page_audio ) ) {
							?>
							<amp-audio autoplay
							  width="400"
							  height="300"
							  layout="nodisplay"
							  src="<?php echo esc_html( $this->page_audio ); ?>" style="width:400px; height: 300px;">
							  <div fallback>
								<p>Your browser doesn’t support HTML5 audio</p>
							  </div>
							</amp-audio>
							<?php
						}

						if ( ! empty( $this->img_id ) ) {
							list($width, $height, $type, $attr) = getimagesize( $this->img_id );
							if ( $width > 720 ) {
								?>
									<amp-img 
									src="<?php echo esc_html( $this->img_id ); ?>" 
									width="720" height="1280" layout="flex-item"    animate-in="pan-right" animate-in-duration="3s" animate-in-delay="1s" >
									</amp-img>
									<?php
							} elseif ( $height > 1280 ) {
								?>
									<amp-img src="<?php echo esc_html( $this->img_id ); ?>" width="720" height="1280" layout="responsive" animate-in="pan-down" >
									</amp-img>
									<?php
							} else {
								?>
									<amp-img src="<?php echo esc_html( $this->img_id ); ?>" width="720" height="1280" layout="responsive">
									</amp-img>
									<?php
							}
						} elseif ( ! empty( $this->vid_id ) ) {

							?>

							 <amp-video autoplay 

								width="640"

								height="360"

								layout="responsive"

								poster="<?php echo esc_html( $poster_src ); ?>"

								src="<?php echo esc_html( $this->vid_id ); ?>" />

								<div fallback>

								  <p>This browser does not support the video element.</p>

								</div>

							  </amp-video>

							<?php

						}

						?>

						

					</amp-story-grid-layer>

					<amp-story-grid-layer template="vertical">
				

					  
					  
					<?php
					if ( ! empty( $story_text['title-text'] ) ) {
						?>
							<h1 
							style="font-size: <?php echo esc_html( ! empty( $story_text['font-size'] ) ? $story_text['font-size'] : '32px' ); ?>;
							font-family: <?php echo esc_html( ! empty( $story_text['font-family'] ) ? $story_text['font-family'] : 'sans-serif' ); ?>;
							  text-align: center; color:<?php echo esc_html( ! empty( $story_text['title-color'] ) ? $story_text['title-color'] : '#FFFFFF' ); ?>"><?php echo esc_html( ! empty( $story_text['title-text'] ) ? $story_text['title-text'] : '' ); ?>
							</h1>
							<?php
					}
					if ( ! empty( $story_desc['description-text'] ) ) {
						?>
								<p style="font-size: <?php echo esc_html( ! empty( $story_desc['font-size'] ) ? $story_desc['font-size'] : '13px' ); ?>;
								font-family: <?php echo esc_html( ! empty( $story_desc['font-family'] ) ? $story_desc['font-family'] : 'sans-serif' ); ?>;  color:<?php echo esc_html( ! empty( $story_desc['description-color'] ) ? $story_desc['description-color'] : '#FFFFFF' ); ?>"><?php echo esc_html( ! empty( $story_desc['description-text'] ) ? $story_desc['description-text'] : '' ); ?></p>
								<?php

					}
					if ( ! empty( $btn_text ) ) {
						?>
							<div style="text-align: center;position: absolute;bottom: 100px;">
								  <a href="<?php echo esc_html( ! empty( $btn_link ) ? $btn_link : '' ); ?>" style="font-size: <?php echo esc_html( ! empty( $btn_size ) ? $btn_size : '13px' ); ?>;
								  font-family: <?php echo esc_html( ! empty( $btn_family ) ? $btn_family : 'sans-serif' ); ?>;    width:auto; border-radius: 40px;color:<?php echo esc_html( ! empty( $btn_color ) ? $btn_color : '#FFFFFF' ); ?>; text-decoration: none;padding:15px 30px; background-color: <?php echo esc_html( ! empty( $btn_bg_color ) ? $btn_bg_color : '#FFFFFF' ); ?>"><?php echo esc_html( $btn_text ); ?>
								  </a>
							</div>
							<?php
					}
					?>

					</amp-story-grid-layer>

				</amp-story-page>



				<?php

				$index++;

				// code...

			}

			?>

			</amp-story>





		  </body>

		</html>

		<?php

	}

}

$obj = new Custom_Post_Content();

$obj->mws_show_story();