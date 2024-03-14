<?php
/*  
 * Robo Maps            http://robosoft.co/wordpress-google-maps
 * Version:             1.0.6 - 19837
 * Author:              Robosoft
 * Author URI:          http://robosoft.co
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:                Thu, 18 May 2017 11:11:10 GMT
 */

class Robo_Maps_Public {


	private $robo_maps;
	private $version;

	public function __construct( $robo_maps, $version ) {
		$this->robo_maps = $robo_maps;
		$this->version = $version;
	}
	
	public function render_showmap(  $atts, $content = null ){
	
		$lng 	= 0;
		$lat 	= 0;
		$zoom 	= 0;
		$marker = 0;
		$scroll = 0;
		$street = 0;
		$zoomcontrol 	= 0;
		$pan 			= 0;
		$mapcontrol 	= 0;
		$overview 		= 0;
		
		$sizeh = $sizew = '';
		$address = $map = '';
		if(count($atts)){
			if(isset($atts['lat'])) $lat = $atts['lat'];
			if(isset($atts['lng'])) $lng = $atts['lng'];
			if(isset($atts['address'])) $address = htmlspecialchars($atts['address']);
			if(isset($atts['zoom'])) $zoom = (int) $atts['zoom'];
			if(isset($atts['marker'])) $marker = (int) $atts['marker'];
			if(isset($atts['caption'])) $caption = htmlspecialchars( $atts['caption'] );

			if(isset($atts['map'])) $map = $atts['map'];

			if(isset($atts['scroll'])) $scroll = (int) $atts['scroll'];
			if(isset($atts['street'])) $street = (int) $atts['street'];
			if(isset($atts['zoomcontrol'])) $zoomcontrol = (int) $atts['zoomcontrol'];
			if(isset($atts['pan'])) $pan = (int) $atts['pan'];
			if(isset($atts['mapcontrol'])) $mapcontrol = (int) $atts['mapcontrol'];
			if(isset($atts['overview'])) $overview = (int) $atts['overview'];

			if(isset($atts['sizeh'])) $sizeh = (int) $atts['sizeh'].'px';
			if(isset($atts['sizew'])) $sizew = $atts['sizew'];
			
			if(!$sizeh) $sizeh = get_option('robo-map-height', '400px');
			if(!$sizew) $sizew = get_option('robo-map-width', '100%');
				
		}

		$retHtml = '<div class="robo-maps-wrap" '
						.'data-gmap="1" '
						.($lng && $lat && !$address  ? 'data-lat="'.$lat.'" data-lng="'.$lng.'" ': '')
						.($address ? 'data-address="'.$address.'" ': '')
						.($zoom ? 'data-zoom="'.$zoom.'" ': '')
						.($marker ? 'data-marker="'.$marker.'" ': '')
						.($caption ? 'data-caption="'.$caption.' " ': '')
						//.($content ? 'data-address1="'.$content.'" ': '')
						.($map ? 'data-map="'.$map.'" ': '')

						.'data-scroll="'.$scroll.'" '
						.'data-street="'.$street.'" '
						.'data-zoomcontrol="'.$zoomcontrol.'" '
						.'data-pan="'.$pan.'" '
						.'data-mapcontrol="'.$mapcontrol.'" '
						.'data-overview="'.$overview.'" '
						.'style="width: '.$sizew.'; height: '.$sizeh.';" '
						.'>'
					.'</div>';
		return $retHtml;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->robo_maps, plugin_dir_url( __FILE__ ) . 'css/robo-maps-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {

		$key = get_option('robo-map-key', '');
		if($key) $key = '?key='.$key;
		
		wp_enqueue_script( $this->robo_maps.'_google', 'https://maps.google.com/maps/api/js'.$key , array( 'jquery' ), false, false );
		wp_enqueue_script( $this->robo_maps.'_map',  ROBO_MAPS_URL. 'js/jquery.ui.map.js', array( 'jquery' ), false, false );
		wp_enqueue_script( $this->robo_maps.'_pub',  ROBO_MAPS_URL. 'public/js/robo-maps-public.js', array( 'jquery' ), $this->version, false );
	}

}