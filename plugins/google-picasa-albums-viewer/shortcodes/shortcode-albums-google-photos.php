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

function cws_gpp_shortcode_albums_google_photos( $atts ) {

  // Safely grab debug param
  $cws_debug = ( isset( $_GET['cws_debug'] ) ) ? sanitize_text_field( $_GET['cws_debug'] ) : $cws_debug = null; 

  $show_title = '';
  $show_details = '';

    // Grab options stored in db
    $options = get_option( 'cws_gpp_options' );

    // var_dump($options);

    // set some defaults...
    $options['results_page']    = isset($options['results_page']) ? $options['results_page'] : "";
    $options['hide_albums']     = isset($options['hide_albums']) ? $options['hide_albums'] : "";
    $options['theme']           = isset($options['theme']) ? $options['theme'] : "";
    $options['row_height']      = isset($options['row_height']) ? $options['row_height'] : "150";
    $options['crop']           = isset($options['crop']) ? $options['crop'] : "0";

    // check if Pro, user PhotocontrollerPro
    $plugin         = new Google_Photos_Albums_Gallery();

    // if( $plugin->get_isPro() == 1 ){
    //   echo ('markup inc masonry here...');
    // } 


    $args = shortcode_atts( array(
'theme'             => $options['theme'],
        'crop'              => $options['crop'],
        'thumb_size'        => $options['thumb_size'],
        'album_thumb_size'  => $options['album_thumb_size'], 
        'show_title'        => $options['show_album_title'],
        'show_details'      => $options['show_album_details'],
        'num_results'       => $options['num_album_results'],
                'results_page'      => $options['results_page'],
        'hide_albums'       => $options['hide_albums'],
        'theme'             => $options['theme'],
        'imgmax'            => $options['lightbox_image_size'],
        //'enable_cache'      => $options['enable_cache'], 
        'fx'                => NULL, 
        'access'                => NULL,                     

                                ), $atts );

    // Map albums names to hide to array and trim white space    
    if( $args['hide_albums'] !== NULL ) {
        $args['hide_albums'] = array_map( 'trim', explode( ',', $args['hide_albums'] ) );
    }

    if(isset($cws_debug) && $cws_debug === "1"){ 
        echo "<pre>";
         print_r($args);
         print_r($atts);
        echo "</pre>";
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

    // check if Pro version, use PhotocontrollerPro
    $plugin         = new Google_Photos_Albums_Gallery();
    if( $plugin->get_isPro() == 1 ){
            // Get album by ID
      $pc = new PhotocontrollerPro($args);
    } else {
    $pc = new Photocontroller($args);
    }

    // listAlbums
    $albums = $pc->listAlbums();
    // $pc = new Photocontroller($args);
    
/*
  <ul class="cards">
    <li class="cards_item">
      <div class="card">
        <div class="card_image"><img src="https://picsum.photos/500/300/?image=10"></div>
        <div class="card_content">
          <h2 class="card_title">Card Grid Layout</h2>
          <p class="card_text">Demo of pixel perfect pure CSS simple responsive card grid layout</p>
          <button class="btn card_btn">Read More</button>
        </div>
      </div>
    </li>
  </ul>

*/

    // Start new Markup
    $strOutput = "";

    $strOutput .= '<div class="main">';
    $strOutput .= '<ul class="cards">';

    for($i = 0; $i < count($albums); $i++){
        $strOutput .= '<li class="cards_item">';
        $strOutput .= '<div class="card">';

        $strOutput .= '<div class="card_image">';
            $strOutput .= '<a class="cws_card" href="/'.$args['results_page'].'?cws_album='.$albums[$i]['id'].'">';
                $strOutput .= '<img src="' . $albums[$i]['imgUrl'] .'">';
            $strOutput .= '</a>';
        $strOutput .= '</div>'; // End .card_image

        if($show_title || $show_details){
          $strOutput .= '<div class="card_content">';
            if($show_title){
              $strOutput .= '<p class="card_title"><strong>'.$albums[$i]['title'].'</strong></p>';
            }
            if($show_details){
              $strOutput .= '<p class="card_title">Contains '.$albums[$i]['mediaItems'].' Images</p>';
            }
            // $strOutput .= '<p class="card_text">Meta Data for Image?</p>';
            $strOutput .= '</div>'; // End .card_content
        }


        $strOutput .= '</div>'; // End .card
    }

    $strOutput .= '</ul>';
    $strOutput .= '</div>'; // End .main
    // End New MArkup



    if($albums[0]['nextPageToken']){
        $strOutput .= '<a href="?cws_pagetoken='.$albums[0]['nextPageToken'].'" class="btn">Next Page</a>';
    }



   return $strOutput;
}