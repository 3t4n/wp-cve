<?php

  if( !defined( 'ABSPATH' ) ){
      exit;
  }

	$content .='<script type="text/javascript">
            jQuery(document).ready(function($) {
              $("#cp-'.$postid.'").owlCarousel({
                autoplay: '.$autoplay.',
                autoplaySpeed: '.$autoplay_speed.',
                autoplayHoverPause: '.$stop_hover.',
                margin: '.$margin.',
                autoplayTimeout: '.$autoplaytimeout.',
                nav : '.$navigation.',
                navText:["<",">"],
                dots: '.$pagination.',
                smartSpeed: 450,
                clone:true,
                loop: '.$loop.',
                responsive:{
                    0:{
                      items:'.$itemsmobile.',
                    },
                    678:{
                      items:'.$itemsdesktopsmall.',
                    },
                    980:{
                      items:'.$itemsdesktop.',
                    },
                    1199:{
                      items:'.$item_no.',
                    }
                }
              });
            });
          </script>';