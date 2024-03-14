<?php 

/*

Plugin Name: Podcast Feed Player Widget and Shortcode
Plugin URI: https://dknewmedia.com
Description: The default RSS widget didn't provide an audio player nor the image for the episode, so I built one. It also has a shortcode: [podcastfeed feedurl="" quantity="" imgsize="" imgclass="" itunes="" google="" soundcloud="" icons=""]Here are our latest podcasts. The image is resized and cached via free service <a href="https://images.weserv.nl">Weserv</a> for faster serving.[/podcastfeed]
Version: 2.2.0
Author: Douglas Karr 
Author URI: https://dknewmedia.com/
License: GPL2
Text Domain: dkpp_podcast_feed

	CircuPress Copyright 2018 Douglas Karr (email: info@dknewmedia.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

include_once( ABSPATH . WPINC . '/feed.php' );

class WP_Widget_Podcast_Feed_Player extends WP_Widget {

  // Set up the widget name and description.
  public function __construct() {
    $widget_options = array( 'classname' => 'dkpp_podcast_widget', 'description' => 'Display an iTunes compliant podcast feed with the WordPress Audio Player' );
    parent::__construct( 'dkpp_podcast_widget', 'Podcast Feed Player Widget', $widget_options );
  }

  // Create the widget output.
  public function widget( $args, $instance ) {
  	$title = apply_filters( 'dkpp_podcast_feed', $instance[ 'title' ] );
    $feedurl = apply_filters( 'dkpp_podcast_feed', $instance[ 'feed' ] );
    $itunes = apply_filters( 'dkpp_podcast_feed', $instance[ 'itunes' ] );
    $google = apply_filters( 'dkpp_podcast_feed', $instance[ 'google' ] );
    $soundcloud = apply_filters( 'dkpp_podcast_feed', $instance[ 'soundcloud' ] );
    $quantity = apply_filters( 'dkpp_podcast_feed', $instance[ 'quantity' ] );
    $size = apply_filters( 'dkpp_podcast_feed', $instance[ 'size' ] );
    $message = apply_filters( 'dkpp_podcast_feed', $instance[ 'message' ] );
    
    if( strpos($feedurl, ',') !== false ) {
        $feedurls = explode(',', $feedurl);
    } else {
    	$feedurls = $feedurl;
    }
    
    $feed = fetch_feed($feedurls);
    $maxitems = 0;
    
    if ( ! is_wp_error( $feed ) ) :
        $maxitems = $feed->get_item_quantity( $quantity ); 
        $feed_items = $feed->get_items( 0, $maxitems ); 
        $channel = $feed->channel;
    endif;

    echo $args['before_widget'] . $args['before_title'];
    
    $title .= ' <a href="'. $feedurl . '" title="Subscribe to the '. $channel["title"] . ' Feed"><i class="fa fa-feed"></i></a>';
    if(strlen($itunes)>7) {
    	$title .= ' <a href="'. $itunes . '" title="Subscribe to '. $channel["title"] . ' on iTunes"><i class="fa fa-microphone"></i></a>';
    }
    if(strlen($google)>7) {
    	$title .= ' <a href="'. $google . '" title="Subscribe to '. $channel["title"] . 'on Google Play"><i class="fa fa-play"></i></a>';
    }
    if(strlen($soundcloud)>7) {
    	$title .= ' <a href="'. $soundcloud . '" title="Subscribe to '. $channel["title"] . 'on SoundCloud"><i class="fa fa-soundcloud"></i></a>';
    }
    echo $title;
    echo $args['after_title'];
    
    ?>
    
	<ul class="podcast_feed">
	    <?php if ( $maxitems == 0 ) : ?>
	        <li><?php _e( 'No items', 'dkpp-domain' ); ?></li>
	    <?php else : ?>
	        <?php foreach ( $feed_items as $item ) : 
	        
	        	$image = $item->get_item_tags('http://www.itunes.com/dtds/podcast-1.0.dtd', 'image');
	        	$image_exists = $image['0']['attribs']['']['href'];
	        	$image_src = $image['0']['attribs']['']['href'];
	        	$image_src = str_replace(array('http://','https://'), '', $image_src);
	        	$image_src = "//images.weserv.nl/?url=".$image_src."&w=".$size;
	        	
	        	if ($enclosure = $item->get_enclosure()) {
	        		$audio = $enclosure->get_link();
	        		// Strip querystring variables since WordPress Audio Player doesn't handle them
	        		$audio = preg_replace('/\?.*/', '', $audio);
	        	}
	        	
	        ?>
	            <li>
	                <h5><a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank"
	                    title="<?php printf( __( 'Posted %s', 'dkpp-domain' ), $item->get_date('F j, Y | g:i a') ); ?>">
	                    <?php echo esc_html( $item->get_title() ); ?>
	                </a></h5>
	                <p class="podcast_desc">
	        <?php if((!empty($image_exists)) && ($size != "0" )) { ?>
	                <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank"
	                    title="<?php printf( __( 'Posted %s', 'dkpp-domain' ), $item->get_date('F j, Y | g:i a') ); ?>">
	                	<img src="<?php echo $image_src; ?>" alt="Listen to <?php echo esc_html( $item->get_title() ); ?>" height="<?php echo $size; ?>" width="<?php echo $size; ?>" class="alignleft podcast_image" />
	                </a>
	        <?php } ?>
	                <?php echo esc_html( wp_trim_words(strip_tags($item->get_description(), 10))); ?></p>
	                <p class="podcast_player"><?php $attr = array( 'src' => $audio, 'loop' => '', 'autoplay' => '', 'preload' => 'none' ); echo wp_audio_shortcode( $attr ); ?></p>
	            </li>
	        <?php endforeach; ?>
	    <?php endif; ?>
	</ul>

    <?php echo $args['after_widget'];
  }

  
  // Create the admin area widget settings form.
  public function form( $instance ) {
  	$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Our Latest Podcasts';
    $feed = ! empty( $instance['feed'] ) ? $instance['feed'] : '';
    $itunes = ! empty( $instance['itunes'] ) ? $instance['itunes'] : '';
    $google = ! empty( $instance['google'] ) ? $instance['google'] : '';
    $quantity = ! empty( $instance['quantity'] ) ? $instance['quantity'] : '';
    $message = ! empty( $instance['message'] ) ? $instance['message'] : ''; ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
	</p>
    <p>
      <label for="<?php echo $this->get_field_id( 'feed' ); ?>">Podcast Feed:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'feed' ); ?>" name="<?php echo $this->get_field_name( 'feed' ); ?>" value="<?php echo esc_attr( $feed ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'quantity' ); ?>">Number of Podcasts to Display:</label>
      <input type="number" id="<?php echo $this->get_field_id( 'quantity' ); ?>" name="<?php echo $this->get_field_name( 'quantity' ); ?>" value="<?php if(is_numeric($quantity)) { echo esc_attr( $quantity ); } else { echo "5"; } ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'size' ); ?>">Size of Thumbnail (0 for no image):</label>
      <input type="number" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" value="<?php if(ctype_digit($size)) { echo esc_attr( $size ); } else { echo "100"; } ?>" />
    </p>
    <p>
    	<label for="<?php echo $this->get_field_id( 'itunes' ); ?>"><a href="https://itunesconnect.apple.com/" target="_blank">iTunes</a>:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'itunes' ); ?>" name="<?php echo $this->get_field_name( 'itunes' ); ?>" value="<?php echo esc_attr( $itunes ); ?>" />
    </p>
    <p>
    	<label for="<?php echo $this->get_field_id( 'google' ); ?>"><a href="https://play.google.com/music/podcasts/publish" target="_blank">Google Play</a>:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>" value="<?php echo esc_attr( $google ); ?>" />
    </p>
	<p>
	  <label for="<?php echo $this->get_field_id( 'soundcloud' ); ?>">SoundCloud:</label>
	  <input type="text" id="<?php echo $this->get_field_id( 'soundcloud' ); ?>" name="<?php echo $this->get_field_name( 'soundcloud' ); ?>" value="<?php echo esc_attr( $soundcloud ); ?>" />
	</p>
    <?php if( strlen($message) > 0 ) { ?>
    	<div style="color: red; font-weight: bold;"><?php echo $message; ?></div>
    <?php } ?>
    <hr>
    <p><small>The Podcast Feed Player Widget is brought to you by <a href="https://dknewmedia.com" target="_blank">DK New Media</a> and <a href="https://martech.zone" target="_blank">MarTech</a>. Please support us by <a href="https://wordpress.org/plugins/podcast-feed-player-widget/" target="_blank">reviewing the plugin on WordPress</a> and letting us know how you like it!</small></p>
    <?php
  }


  // Apply settings to the widget instance.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    $instance[ 'feed' ] = strip_tags( $new_instance[ 'feed' ] );
    $instance[ 'itunes' ] = strip_tags( $new_instance[ 'itunes' ] );
    $instance[ 'google' ] = strip_tags( $new_instance[ 'google' ] );
    $instance[ 'soundcloud' ] = strip_tags( $new_instance[ 'soundcloud' ] );
    $instance[ 'quantity' ] = strip_tags( $new_instance[ 'quantity' ] );
    $instance[ 'size' ] = strip_tags( $new_instance[ 'size' ] );
    
    $feedurl = $instance[ 'feed' ];
    $feedurls = explode(',', $feedurl);
    
    $message = "";
    $nonssl = "";
    $ssltest = 0;
    
    if (is_ssl()) {
    	foreach ($feedurls as $url) {
    		if(!preg_match( '|^https ?://|', $url )) {
    		 	$ssltest = $ssltest + 1;
    		 	$nonssl .= $url. ", ";
    		}
    	}
    	
    	if( $ssltest > 0 ) {
    		$message .= "<p>Your site is secure but the podcast feed you entered is not. You must have a secure feed or else the audio files will not play and the images will not be displayed. (Feed: ".rtrim($nonssl, ", ").")</p>";
    	}
    }
    
    $urltest = 0;
    $nonurl = "";
    
    foreach ($feedurls as $url) {
		$response = wp_remote_get($url);
		if ( wp_remote_retrieve_response_code( $response ) != 200 || ( empty( wp_remote_retrieve_body( $response ) ) ) ){
			$urltest = $urltest + 1;
			$nonurl .= $url. ", ";
		}
	
		if( $urltest > 0 ) {
			$message .= "<p>There was a problem reaching your podcast feed. (Feed: ".rtrim($nonurl, ", ").")</p>";
		}
    }
    
    $instance[ 'message' ] = $message;
    
    return $instance;
  }

}

function dkpp_podcastfeed( $atts, $dkpp_content = null ) {
	$atts = shortcode_atts( array(
			'feedurl' => '',
			'quantity' => '10',
			'imgsize' => '250',
			'imgclass' => 'alignleft',
			'itunes' => '',
			'google' => '',
			'soundcloud' => '',
			'icons' => 'true'
		), $atts, 'podcastfeed' );
		
    $feedurl = $atts['feedurl'];
    $itunes = $atts['itunes'];
    $google = $atts['google'];
    $soundcloud = $atts['soundcloud'];
    $quantity = $atts['quantity'];
    $imgsize = $atts['imgsize'];
    $imgclass = $atts['imgclass'];
    $showicons = $atts['icons'];
    
    if( strpos($feedurl, ',') !== false ) {
        $feedurls = explode(',', $feedurl);
    } else {
    	$feedurls = $feedurl;
    }
    
	if (is_ssl()) {
		foreach ($feedurls as $url) {
			if(!preg_match( '|^https ?://|', $url )) {
			 	$ssltest = $ssltest + 1;
			 	$nonssl .= $url. ", ";
			}
		}
		
		if( $ssltest > 0 ) {
			echo "<p>Your site is secure but the podcast feed you entered is not. You must have a secure feed or else the audio files will not play and the images will not be displayed. (Feed: ".rtrim($nonssl, ", ").")</p>";
		}
	}
    
    $feed = fetch_feed($feedurls);
    $maxitems = 0;
    
    if ( ! is_wp_error( $feed ) ) :
        $maxitems = $feed->get_item_quantity( $quantity ); 
        $feed_items = $feed->get_items( 0, $maxitems ); 
        $channel = $feed->channel;
    endif;
    
    echo $dkpp_content;
	    
	if ( ! is_feed() ) {
	    
	    if ($showicons != "false") {
	    
	    echo ' <a href="'. $feedurl . '" title="Subscribe to the '. $channel["title"] . ' Feed"><i class="fa fa-feed"></i></a>';
	    if(strlen($itunes)>7) {
	    	echo ' <a href="'. $itunes . '" title="Subscribe to '. $channel["title"] . ' on iTunes"><i class="fa fa-microphone"></i></a>';
	    }
	    if(strlen($google)>7) {
	    	echo ' <a href="'. $google . '" title="Subscribe to '. $channel["title"] . 'on Google Play"><i class="fa fa-play"></i></a>';
	    }
	    if(strlen($soundcloud)>7) {
	    	echo ' <a href="'. $soundcloud . '" title="Subscribe to '. $channel["title"] . 'on SoundCloud"><i class="fa fa-soundcloud"></i></a>';
	    }
	    echo '</p>';
	    
	    }
	    
	    if ( $maxitems == 0 ) : 
	    
	    	echo '<p><?php _e( "No items", "dkpp-domain" ); ?></p>';
	    	
	    else : 
	    
	    	foreach ( $feed_items as $item ) : 
	        
	        	$image = $item->get_item_tags('http://www.itunes.com/dtds/podcast-1.0.dtd', 'image');
	        	$image_src = $image['0']['attribs']['']['href'];
	        	$image_src = str_replace(array('http://','https://'), '', $image_src);
	        	$image_src = "//images.weserv.nl/?url=".$image_src."&w=".$imgsize;
	        	
	        	if ($enclosure = $item->get_enclosure()) {
	        		$audio = $enclosure->get_link();
	        		// Strip querystring variables since WordPress Audio Player doesn't handle them
	        		$audio = preg_replace('/\?.*/', '', $audio);
	        	}
	                
	            echo '<p class="podcast_title">';
	            echo '<strong><a href="'.$item->get_permalink().'" target="_blank" title="Published: '.$item->get_date("F j, Y | g:i a").'">';
	            echo $item->get_title();
	            echo '</a></strong>';
	            echo '</p>';
	            
	            echo '<div class="podcast_desc">';
	    		if((!empty($image_src)) && ($imgsize != "0" )) {
	                echo '<a href="'.$item->get_permalink().'" target="_blank" title="Published '.$item->get_date('F j, Y | g:i a').'">';
	                echo '<img src="'.$image_src.'" alt="Listen to '.$item->get_title().'" height="'.$imgsize.'" width="'.$imgsize.'" class="'.$imgclass.'" /></a>';
	    		}
	            echo $item->get_description();
	            echo '</div>';
	            echo '<p class="podcast_player">';
	            $attr = array( 'src' => $audio, 'loop' => '', 'autoplay' => '', 'preload' => 'none' ); 
	            echo wp_audio_shortcode( $attr );
	            echo '</p>';
	            
	        endforeach;
	        
	    endif;
    
    }
}
add_shortcode('podcastfeed', 'dkpp_podcastfeed');

// Register the widget.
function dkpp_register_podcast_widget() { 
  register_widget( 'WP_Widget_Podcast_Feed_Player' );
}
add_action( 'widgets_init', 'dkpp_register_podcast_widget' );
 
function dkpp_styles() {
    wp_register_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.2.0' );
    wp_enqueue_style( 'font-awesome' );
}
add_action( 'wp_enqueue_scripts', 'dkpp_styles' );

?>