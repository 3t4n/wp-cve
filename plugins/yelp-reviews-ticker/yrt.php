<?php
/*
Plugin Name:  Yelp Reviews Ticker
Plugin URI:   http://wordpress.org/extend/plugins/yelp-reviews-ticker/
Description:  This reviews ticker allows you to show your business yelp reviews and also customize its display to your taste in a easy manner.
Version:      2.1
Author:       Flavio Domeneck Jr
Author URI:   https://plus.google.com/107297937804029082934/
License: GPL2

Copyright 2013  FDJ  (email : contactflavio@gmail.com )

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

// Create  Widget Class
class yrtWidget extends WP_Widget {
	function yrtWidget() {
		parent::__construct( 
			false, 
			'Yelp Reviews Ticker',
			array( 'description' => "Yelp Reviews Ticker shows your yelp reviews cleanly and pain free" ) 
		);
	}

// Title
	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		echo $before_title.$instance[ 'title' ].$after_title;

// Set instance values
$speed = $instance[ 'speed' ];
$pause = $instance[ 'pause' ];
$showitems = $instance[ 'showitems' ];
$animation = $instance[ 'animation' ];
$mousepause = $instance[ 'mousepause' ];
$direction = $instance[ 'direction' ];
$iid = $this->id;
$ywsid = $instance[ 'ywsid' ];
$biz_phone = $instance[ 'biz_phone' ];
$biz_phone = preg_replace( '[\D]', '', $biz_phone ); // clean up phone number

// Call values
$yelp_url = 'http://api.yelp.com/phone_search?phone=' . $biz_phone . '&ywsid=' . $ywsid;

// Send Yelp API Call
$api_request = wp_remote_get( $yelp_url );
$api_response = wp_remote_retrieve_body( $api_request );

// Handle Yelp response data
$obj = json_decode( $api_response );// Convert JSON from yelp return string
$arr = (array) $obj;


// Start diplay code
// Check is is array
if ( is_array( $arr[ 'businesses' ] ) ) {

// Select the business
	foreach ( $obj->businesses as $business ) {
		
// Images variables
$minimaplogo = plugins_url( 'images/miniMapLogo.png' , __FILE__ );
$ratingimg = plugins_url( 'images/rating.png' , __FILE__ );
$yelp_logo = plugins_url( 'images/yelp_logo_50x25.png' , __FILE__ );

// Business variables
$business_url = $business->url;
$business_name = $business->name;
$business_avg_rating = $business->avg_rating;
$business_review_count = $business->review_count;
if ( $business_review_count == 1 ) {
	$business_review_count_var = "$business_review_count review";
	}
	else {
	$business_review_count_var = "$business_review_count reviews";
	}
if ( ( $business_review_count == 2 ) && ( $showitems > 2 ) ) {
	$showitems = "2";
	}
if ( ( $business_review_count == 1 ) && ( $showitems > 1 ) ) {
	$showitems = "1";
	}

// CSS Function
if ( $business_avg_rating == "0" ) { $business_avg_rating_css = 'yrtstars_0_l'; }
if ( $business_avg_rating == "1" ) { $business_avg_rating_css = 'yrtstars_1_l'; }
if ( $business_avg_rating == "1.5" ) { $business_avg_rating_css = 'yrtstars_1h_l'; }
if ( $business_avg_rating == "2" ) { $business_avg_rating_css = 'yrtstars_2_l'; }
if ( $business_avg_rating == "2.5" ) { $business_avg_rating_css = 'yrtstars_2h_l'; }
if ( $business_avg_rating == "3" ) { $business_avg_rating_css = 'yrtstars_3_l'; }
if ( $business_avg_rating == "3.5" ) { $business_avg_rating_css = 'yrtstars_3h_l'; }
if ( $business_avg_rating == "4" ) { $business_avg_rating_css = 'yrtstars_4_l'; }
if ( $business_avg_rating == "4.5" ) { $business_avg_rating_css = 'yrtstars_4h_l'; }
if ( $business_avg_rating == "5" ) { $business_avg_rating_css = 'yrtstars_5_l'; }

// Business Header HTML HEREDOC
$yrt_header = <<<HTML
<div>
	<h2><a href="{$business_url}" title="{$business_url}">{$business_name}</a></h2>
	<i title="{$business_avg_rating}">
		<img style="vertical-align:middle" alt="{$business_avg_rating} star rating" src="{$ratingimg}" class="{$business_avg_rating_css}">
	</i>
	{$business_review_count_var}
</div>
<br />
<!-- Start Yelp Reviews Ticker jQuery -->
<script type="text/javascript">
	jQuery(function(){
		jQuery('#ticker_{$iid}').vTicker({ 
			speed: {$speed},
			pause: {$pause},
			animation: '{$animation}',
			mousePause: {$mousepause},
			direction: '{$direction}',
			showItems: {$showitems}
		});
	});
</script>
<!-- End Yelp Reviews Ticker jQuery -->

<div id="yrtcssmarkup">
	<div id="ticker_{$iid}">
		<ul>
	
HTML;

echo $yrt_header;

// foreach review
foreach( $obj->businesses as $key => $bus ){

// Declare array call for the review
$review = $bus->reviews;

//Create loop
for ( $i = 0; $i<count( $review ); $i++ ) {

// Review variables
$ruser_name = $review[$i]->user_name;
$ruser_photo_url = $review[$i]->user_photo_url;
$rrating = $review[$i]->rating;
$rtext_excerpt = $review[$i]->text_excerpt;
$review_id = $review[$i]->id;
$rdate = $review[$i]->date;

// Review CSS conditionals
if ( $rrating == "0" ) { $review_css = 'yrtstars_0_s'; }
if ( $rrating == "1" ) { $review_css = 'yrtstars_1_s'; }
if ( $rrating == "1.5" ) { $review_css = 'yrtstars_1h_s'; }
if ( $rrating == "2" ) { $review_css = 'yrtstars_2_s'; }
if ( $rrating == "2.5" ) { $review_css = 'yrtstars_2h_s'; }
if ( $rrating == "3" ) { $review_css = 'yrtstars_3_s'; }
if ( $$rrating == "3.5" ) { $review_css = 'yrtstars_3h_s'; }
if ( $rrating == "4" ) { $review_css = 'yrtstars_4_s'; }
if ( $rrating == "4.5" ) { $review_css = 'yrtstars_4h_s'; }
if ( $rrating == "5" ) { $review_css = 'yrtstars_5_s'; }

// Review HTML HEREDOC
$review_html = <<<HTML
			<li>
				<div class="yrtTable">
					<div class="yrtRow">
						<div class="yrtCell1">
							<img alt="{$ruser_name}" src="{$ruser_photo_url}" width="60"/><br />
							{$ruser_name}<br />
							<img alt="{$rrating} star" src="{$ratingimg}" class="{$review_css}">
						</div>
						<div class="yrtCell2">
							<p>{$rtext_excerpt}</p>
						</div>
					</div>
				</div>
				<div class="yrtYelp">
					<a href="{$business_url}#hrid:{$review_id}" target="_blank" title="Read the review in full at Yelp.com">
						{$rdate} read the full review at
						<img style="vertical-align:middle" alt="Yelp" src="{$minimaplogo}"/>
					</a>
				</div>
			</li>
HTML;
// Review loop
echo $review_html;
	} // End Loop $i
} // End foreach review

// Review HTML HEREDOC
$review_footer = <<<HTML
		</ul>
	</div>
	<div class="yrtFoot">
		<a href="http://www.yelp.com" title="www.Yelp.com" target="_blank">
			Reviews powered by <img alt="Yelp" style="vertical-align:middle" src="{$yelp_logo}" />
		</a>
	</div>
</div>
HTML;
echo $review_footer;

	} // End foreach "Select the business"
} // End check is is array

// Display error if settings incorrect

if ( empty( $arr[ 'businesses' ] ) ) { //check if business exists
	$arr_error = array($obj->message->text);
	//var_dump($obj);
	if ( is_array( $arr_error ) && $arr_error[0] == 'Invalid YWSID' ) { //check for YWSID key
		echo "<p>The YWSID (API v1.0 Key) you've entered is not correct.<br />Please check the Yelp Reviews Ticker Widget settings and comfirm the YWSID (API v1.0 Key) is correct. </p>";
	}
	if ( empty( $arr[ 'businesses' ] ) && $arr_error[0] == 'OK' ) { //check for business
		echo "<br />The Business Phone you've entered is not linked to any Yelp Business Page.<br />Please check the Yelp Reviews Ticker Widget settings and comfirm the Phone number is correct. <br />Or check the Yelp.com Business page for the correct phone number";
	}
}

echo $after_widget;

} // End function widget.

function form( $instance ) { //<- set default parameters of widget

	//title
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
    } else {
        $title = "Reviews"; //default
    }
	//speed
	if ( isset( $instance[ 'speed' ] ) ) {
		$speed = $instance[ 'speed' ];
    } else {
        $speed = "2500"; //default
    }
	//pause
	if ( isset( $instance[ 'pause' ] ) ) {
		$pause = $instance[ 'pause' ];
    } else {
        $pause = "6000"; //default
    }
	//showitems
	if ( isset( $instance[ 'showitems' ] ) ) {
		$showitems = $instance[ 'showitems' ];
    } else {
        $showitems = "2"; //default
    }
	//animation
	if ( isset( $instance[ 'animation' ] ) ) {
		$animation = $instance[ 'animation' ];
    } else {
        $animation = "fade"; //default
    }
	//mousepause
	if ( isset( $instance[ 'mousepause' ] ) ) {
		$mousepause = $instance[ 'mousepause' ];
    } else {
        $mousepause = "true"; //default
    }
	//direction
	if ( isset( $instance[ 'direction' ] ) ) {
		$direction = $instance[ 'direction' ];
    } else {
        $direction = "up"; //default
    }
	
	//ywsid
	if ( isset( $instance[ 'ywsid' ] ) ) {
		$ywsid = $instance[ 'ywsid' ];
    } else {
        $ywsid = "mising"; //default
    }	

	//Business Phone number
	if ( isset( $instance[ 'biz_phone' ] ) ) {
			$biz_phone = $instance[ 'biz_phone' ];
		}
		else {
			$biz_phone = "missing";
		}
		
// Settings variables

$ii_title = esc_attr( $this->get_field_id( 'title' ) );
$in_title = esc_attr( $this->get_field_name( 'title' ) );
$ii_speed = esc_attr( $this->get_field_id( 'speed' ) );
$in_speed = esc_attr( $this->get_field_name( 'speed' ) );
$ii_pause = esc_attr( $this->get_field_id( 'pause' ) );
$in_pause = esc_attr( $this->get_field_name( 'pause' ) );
$ii_showitems = esc_attr( $this->get_field_id( 'showitems' ) );
$in_showitems = esc_attr( $this->get_field_name( 'showitems' ) );
$ii_animation = esc_attr( $this->get_field_id( 'animation' ) );
$in_animation = esc_attr( $this->get_field_name( 'animation' ) );
$ii_mousepause = esc_attr( $this->get_field_id( 'mousepause' ) );
$in_mousepause = esc_attr( $this->get_field_name( 'mousepause' ) );
$ii_direction = esc_attr( $this->get_field_id( 'direction' ) );
$in_direction = esc_attr( $this->get_field_name( 'direction' ) );
$ii_ywsid = esc_attr( $this->get_field_id( 'ywsid' ) );
$in_ywsid = esc_attr( $this->get_field_name( 'ywsid' ) );
$ii_biz_phone = esc_attr( $this->get_field_id( 'biz_phone' ) );
$in_biz_phone =  esc_attr( $this->get_field_name( 'biz_phone' ) );

// Conditionals
if ( $showitems == '1' ) { $showitems1 = "checked"; }
if ( $showitems == '2' ) { $showitems2 = "checked"; }
if ( $showitems == '3' ) { $showitems3 = "checked"; }
if ( $animation == 'fade' ) { $animation1 = "checked"; }
if ( $animation !== 'fade' ) { $animation2 = "checked"; }
if ( $mousepause == 'true' ) { $mousepause1 = "checked"; }
if ( $mousepause == 'false' ) { $mousepause2 = "checked"; }
if ( $direction == 'up' ) { $direction1 = "checked"; }
if ( $direction == 'down' ) { $direction2 = "checked"; }

// Settings HTML HEREDOC
$settings_display = <<<HTML
		<p>
			<label for="{$ii_title}">Widget Title</label><br />
			<input id="{$ii_title}" name="{$in_title}" type="text" value="{$title}"/>
		</p>
		<p>
			<label for="{$ii_speed}">Speed</label><br />
			<input id="{$ii_speed}" name="{$in_speed}" type="text" value="{$speed}"/>
		</p>
		<p>
			<label for="{$ii_pause}">Pause</label><br />
			<input id="{$ii_pause}" name="{$in_pause}" type="text" value="{$pause}"/>
		</p>
		<p>
			<label for="{$ii_showitems}"># of reviews</label><br />
			1 <input id="{$ii_showitems}" name="{$in_showitems}" type="radio" {$showitems1} value="1"/>
			2 <input id="{$ii_showitems}" name="{$in_showitems}" type="radio" {$showitems2} value="2"/>
			3 <input id="{$ii_showitems}" name="{$in_showitems}" type="radio" {$showitems3} value="3"/>
		</p>
		<p>
			<label for="{$ii_animation}">Fade</label><br />
			Yes <input id="{$ii_animation}" name="{$in_animation}" type="radio" {$animation1} value="fade"/>
			No <input id="{$ii_animation}" name="{$in_animation}" type="radio" {$animation2} value=""/>
		</p>
		<p>
			<label for="{$ii_mousepause}">Mouse Pause</label><br />
			Yes <input id="{$ii_mousepause}" name="{$in_mousepause}" type="radio" {$mousepause1} value="true"/>
			No <input id="{$ii_mousepause}" name="{$in_mousepause}" type="radio" {$mousepause2} value="false"/>
		</p>
		<p>
			<label for="{$ii_direction}">Direction</label><br />
			Up <input id="{$ii_direction}" name="{$in_direction}" type="radio" {$direction1} value="up"/>
			Down <input id="{$ii_direction}" name="{$in_direction}" type="radio" {$direction2} value="down"/>
		</p>
		<p>
			<label for="{$ii_ywsid}">API v1.0 Key (YWSID)</label><br/>
			<input id="{$ii_ywsid}" name="{$in_ywsid}" type="text" value="{$ywsid}"/>
		</p>
		<p>
			<label for="{$ii_biz_phone}">Business Phone Number</label><br />
			<input id="{$ii_biz_phone}" name="{$in_biz_phone}" type="text" value="{$biz_phone}"/>
		</p>
HTML;

// Start Settings (display)
if ( current_user_can( 'manage_options' ) ) {
    echo $settings_display;
} else {
    echo "You don't have enough privileges to make changes here";
}

	} // end function form

// Updates the settings.
	function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	
	//Strip tags from title and name to remove HTML 
	$instance['speed'] = strip_tags( $new_instance[ 'speed' ] );
	$instance['pause'] = strip_tags( $new_instance[ 'pause' ] );
	$instance['showitems'] = strip_tags( $new_instance[ 'showitems' ] );
	$instance['animation'] = strip_tags( $new_instance[ 'animation' ] );
	$instance['mousepause'] = strip_tags( $new_instance[ 'mousepause' ] );
	$instance['direction'] = strip_tags( $new_instance[ 'direction' ] );
	$instance['ywsid'] = strip_tags( $new_instance[ 'ywsid' ] );
	$instance['biz_phone'] = strip_tags( $new_instance[ 'biz_phone' ] );
	$instance['title'] = strip_tags( $new_instance[ 'title' ] );

	return $instance;
	}

} // end class

// Register the widget.
function yrtw_register() {
	register_widget( 'yrtWidget' );
}


// Add scripts & styling
function yrt_scripts() {
	wp_enqueue_script(
		'jquery'
	);
	// jQuery vTicker from
	// http://www.jugbit.com/jquery-vticker-vertical-news-ticker/
	wp_enqueue_script( 
		'yrt_js',
		plugins_url( 'lib/jquery.vticker-min.js' , __FILE__ )
	);
	wp_enqueue_style( 
		'yrt_style',
		plugins_url( 'css/yelprt.css', __FILE__ )
	);
	wp_enqueue_style(
		'yrtstars',
		plugins_url( 'css/yrtstars.css', __FILE__ )
	);
}    
// Load scripts & styling
add_action( 'wp_enqueue_scripts', 'yrt_scripts' );

// Register widget
add_action( 'widgets_init', 'yrtw_register' );
?>