<?php
/**
 * Copyright (c) 2011, cheshirewebsolutions.com, Ian Kennerley (info@cheshirewebsolutions.com).
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// namespace App; // this breaks tings

use App\Photocontroller;
use App\GooglePhotoService;

function cws_gpp_shortcode_images_in_album_google_photos( $atts ) {

    // if( isset($_GET['cws_debug'])){
        //     $cws_debug = $_GET[ 'cws_debug' ];
        // }

    // Safely grab debug param
    $cws_debug = ( isset( $_GET['cws_debug'] ) ) ? sanitize_text_field( $_GET['cws_debug'] ) : $cws_debug = null;     

    $show_title = '';
    $show_details = '';
    $crop = '';

    $albumId = '';

    // Grab options stored in db
    $options = get_option( 'cws_gpp_options' );

    // var_dump($options);

        // set some defaults...
        $num_pages = 0;
        // $options['crop'] = null;

        //$options['show_image_details'] = isset($options['show_image_details']) ? $options['show_image_details'] : "";
        $options['theme'] = isset($options['theme']) ? $options['theme'] : "";
        $options['id'] = isset($options['id']) ? $options['id'] : "";
        $options['results_page'] = isset($options['results_page']) ? $options['results_page'] : "";
        //$options['hide_albums'] = isset($options['hide_albums']) ? $options['hide_albums'] : "";
        $options['row_height'] = isset($options['row_height']) ? $options['row_height'] : "250";
    $options['crop']           = isset($options['crop']) ? $options['crop'] : "0";
        //$options['enable_download'] = isset($options['enable_download']) ? $options['enable_download'] : "";

    // check if Pro, user PhotocontrollerPro
    $plugin         = new Google_Photos_Albums_Gallery();


    // get options from shortcode and merge with defaults
    $args = shortcode_atts(array(
        'id'                => $options['id'],
        'theme'             => $options['theme'],
        'crop'              => $options['crop'],
        'thumb_size'        => $options['thumb_size'],
        'imgmax'            => $options['lightbox_image_size'],
        'num_results'       => $options['num_image_results'],
        'show_title'        => $options['show_image_title'],
        'show_details'      => $options['show_album_details'],
        'row_height'        => $options['row_height']
    ), $atts);

    if(isset($cws_debug) && $cws_debug === "1"){ 
        echo "<pre>";
         print_r($args);
        print_r($atts);
echo "</pre>";
    }

    // Grab safely if album id is passed via url
    if( isset($_GET['cws_album'])){
        $albumId = sanitize_text_field( $_GET['cws_album'] );
    }
    // Pro user can specifiy album id via shortcode
    elseif($plugin->get_isPro() == 1 ){
        // $albumId = '123'; // need this line when have invalid album id, why shortcode code stopping admin page render!?!?
        $albumId = $args['id'];
    }

    if ( $args['show_title'] === 'false' ) $show_title = false; // just to be sure...
    if ( $args['show_title'] === 'true' ) $show_title = true; // just to be sure...
    if ( $args['show_title'] === '0' ) $show_title = false; // just to be sure...
    if ( $args['show_title'] === '1' ) $show_title = true; // just to be sure...
    $show_title = ( bool ) $show_title; 

    if ( $args['show_details'] === 'false' ) $show_details = false; // just to be sure...
    if ( $args['show_details'] === 'true' ) $show_details = true; // just to be sure...
    if ( $args['show_details'] === '0' ) $show_details = false; // just to be sure...
    if ( $args['show_details'] === '1' ) $show_details = true; // just to be sure...
    $show_details = ( bool ) $show_details; 

    if ( $args['crop'] === 'false' ) $crop = false; // just to be sure...
    if ( $args['crop'] === 'true' ) $crop = true; // just to be sure...
    if ( $args['crop'] === '0' ) $crop = false; // just to be sure...
    if ( $args['crop'] === '1' ) $crop = true; // just to be sure...
    $args['crop'] = ( bool ) $crop;    

    // listAlbums
    // Check which Photocontroller to use
    if( $plugin->get_isPro() == 1 ){
      // Get album by ID
      $pc = new PhotocontrollerPro($args);
    } else {
    $pc = new Photocontroller($args);
    }

    $mediaItems = $pc->listMediaItems($albumId, $args);

    // Start new Markup
    $strOutput = "";

        // Decide which layout to use to display the albums
        switch( $args['theme'] ) {

            #----------------------------------------------------------------------------
            # Justified Image Grid Layout *** PRO ONLY ***
# Masonry for layout (Justified Image Grid AKA JIG)
            # Photoswipe for Lightbox
            #----------------------------------------------------------------------------
            case "projig":

                // $strOutput .= "projig view - need to require this as separate markup file!";
                if ($plugin->get_isPro() == 1 ) {
                    require_once 'partials_pro/pro_jig.php';
                } else {
                    $strOutput .= "This is a Pro version feature, please upgrade";
	}

                break;

            #----------------------------------------------------------------------------
            # Pro Grid Layout - inc Photoswipe lightbox *** PRO ONLY ***
            #----------------------------------------------------------------------------                
            case "progrid":

                if ($plugin->get_isPro() == 1 ) {
                    require_once 'partials_pro/pro_grid.php';
                } else {
                    $strOutput .= "This is a Pro version feature, please upgrade";
                }

                break;

            default:

                // TODO:
                /*
                    Use Photoswipe when Pro for better UX
                    think I need to change enqueu styles / scripts in public/class-google-photos-albums-gallery-public.php
                */

                // $strOutput .= '<div class="main">' . $args['theme'];
                $strOutput .= '<ul class="cards">';
            
                if (is_countable($mediaItems) && count($mediaItems) > 0){
                for($i = 0; $i < count($mediaItems); $i++){
                    $strOutput .= '<li class="cards_item">';
                    $strOutput .= '<div class="card">';
            
                    $strOutput .= '<div class="card_image">';
                        $strOutput .= '<a class="cws_card" href="' . $mediaItems[$i]['bigImgUrl'] .'" data-lightbox="result-set">';
                            $strOutput .= '<img src="' . $mediaItems[$i]['imgUrl'] .'">';
                        $strOutput .= '</a>';
                    $strOutput .= '</div>'; // End .card_image
            
                    if($show_title || $show_details){
                        $strOutput .= '<div class="card_content">';
                        if($show_title){
                            $strOutput .= '<p class="card_title">'.$mediaItems[$i]['filename'].'</p>';
                        }
                        if($show_details){
                            $strOutput .= '<p class="card_title">'.$mediaItems[$i]['description'].'</p>';
                            // $strOutput .= '<p class="card_text">Meta Data for Image??</p>';
                        }
                            
                        $strOutput .= '</div>'; // End .card_content
                    }
                    $strOutput .= '</div>'; // End .card
                }
                }
            
                $strOutput .= '</ul>';
                //$strOutput .= '</div>'; // End .main                
        }




        if (is_countable($mediaItems) && count($mediaItems) > 0){
    if($mediaItems[0]['nextPageToken']){
        $strOutput .= '<a href="?cws_album='.$albumId.'&cws_pagetoken='.$mediaItems[0]['nextPageToken'].'" class="btn">Next Page</a>';
    }
        }




    
   return $strOutput;
}