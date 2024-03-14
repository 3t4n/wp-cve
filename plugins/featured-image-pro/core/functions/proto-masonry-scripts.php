<?php
/* Generate Basic Proto Masonry Scripts & Styles
  @category  utility
  @package featured-image-pro
  @author  Adrian Jones <adrian@shooflysolutions.com>
  @license MIT
  @link http:://www.shooflysolutions.com
 Version 1.0
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


require_once 'proto-global.php'; //Utilities
if ( !class_exists( 'proto_masonry_scripts' ) ):
	/**
	 * proto_masonry_scripts class.
	 */
	class proto_masonry_scripts {
	/**
	 * dostyle function.
	 * Create styles for the current grid
	 *
	 * @access public
	 * @static
	 * @param array   $options - plugin options
	 * @return styles
	 */


	 function masonry_dostyle( $style, $options ) {
		$uniqueId = $options['uniqueid'];
		//$style = ''; //complete style
		$imagestyle = ''; //Image styles
		$captionstyle = ''; //Caption styles
		$subcaptionstyle = ''; //subcaption styles
		$excerptstyle = ''; //Excerpt styles
        $item_color = isset( $options['item_color'] ) && $options['item_color'] ? $options['item_color']:false;
        $item_bgcolor = isset( $options['item_bgcolor'] ) && $options['item_bgcolor'] ? $options['item_bgcolor']:false;
        $link_color = isset( $options['link_color'] )  && $options['link_color'] ? $options['link_color']:false;
        $link_hovercolor = isset( $options['link_hovercolor'] ) && $options['link_hovercolor'] ? $options['link_hovercolor']:false;
        $item_colortext = false;
        $link_colortext = false;
        $link_hovercolortext = false;
        $item_bgcolortext = false;
		$prefix = sanitize_text_field( $options['prefix'] ); //prefix, ie - featured_image_pro media_gallery_pro
		$container = "#{$prefix}_masonry_container_$uniqueId"; //the container for the widget
		$itemid = ".{$prefix}_masonry_item_$uniqueId"; //an item in the widget
		$widget = ".proto-masonry-widget_$uniqueId";
		if ( isset( $noptions['excerpt'] ) && !isset( $noptions['showexcerpts'] ) )
			$excerpt = proto_boolval( $options['excerpt'] );
		else
			$excerpt = proto_boolval( $options['showexcerpts'] );

		$fitwidth = proto_boolval( $options['fitwidth'] );


		if ( $excerpt && isset ( $options['excerptalign'] ) ) {
	    	$excerptalign = sanitize_text_field( $options['excerptalign'] );

	    		$excerptstyle .= $excerptalign != '' ?  "text-align:$excerptalign;" : "text-align:left";
	    }
		//excerpt style
		$excerptstyle = apply_filters( 'proto_masonry_excerptstyles', $excerptstyle, $options );
		if ( $excerptstyle ) {
			$style .= "
                /* Excerpt Style*/
                    $itemid .{$prefix}_masonry_excerpt {
                        $excerptstyle;
                }";
		}
		// Caption Styles
		if ( isset($options['captionheight'] )) {
			$captionheight = sanitize_text_field( $options['captionheight'] );
			$captionstyle .= $captionheight != '' ? "height:$captionheight;" : '';

		}
		if ( isset( $options['captionalign'] ) )
		{
			$captionalign = sanitize_text_field( $options['captionalign'] );
			$captionstyle .= $captionalign != '' ? "text-align:$captionalign": 'textalign:center';
		}
		$captionstyle = apply_filters( 'proto_masonry_captionstyles', $captionstyle, $options );

		if ( $captionstyle ) {
			$style .=
				"/*Masonry Caption Styles*/
                $itemid
                .{$prefix}_masonry_caption,
		$itemid
		.{$prefix}_masonry_caption .proto_title
                {
                    $captionstyle
                }";
		}
		if ( isset( $options['subcaptionalign'] ) )
		{
			$subcaptionalign = sanitize_text_field( $options['subcaptionalign'] );
			$subcaptionstyle =  $subcaptionalign != '' ?  "text-align:$subcaptionalign" : 'textalign:center';

		}

		$subcaptionstyle = apply_filters( 'proto_masonry_subcaptionstyles', $subcaptionstyle, $options );

		if ( $subcaptionstyle ) {
			$style .=
				"/*Masonry Sub Caption Styles*/
                $itemid
                .proto_subcaption

                {
                    $subcaptionstyle
                }";
		}

		//Add a border
		if ( isset( $options['border'] ) ) {
			if ( intval( $options['border'] ) > 0 ) {
				$border = intval( $options['border'] );
				$style .=
					"
                /* Item Border */
                $itemid.{$prefix}_masonry_border
                {
                    border-width:{$border}px;
                }";
			}
		}

		//Item Style
		$mstyle = "";
		if ( isset( $options['marginbottom'] ) ) {
			$marginbottom = sanitize_text_field( $options['marginbottom'] );
			$mstyle .= "margin-bottom:$marginbottom;";
		}
		$itemstyle =  $mstyle;
		$itemstyle = apply_filters( 'proto_masonry_itemstyles', $itemstyle, $options );
		$style .= "
            $itemid
            {
                /* grid item styles*/
                $itemstyle
            }";
            if ($item_bgcolor) {
                $item_bgcolortext = "background-color:{$item_bgcolor};";
            }
            if ($item_color) {
                $item_colortext = "color:{$item_color};";
            }
            if ($link_color) {
                $link_colortext = "color:{$link_color};";
            }
            if ($link_hovercolor) {
                $link_hovercolortext = "color:{$link_hovercolor};";
            }

            if ($item_bgcolortext || $item_colortext) {
                $style .= "
                    {$itemid} {
                        {$item_bgcolortext};
                        {$item_colortext};
                    }
                ";
            }

            if ($link_colortext) {
                $style .= "
                {$itemid} a {
                    {$link_colortext};
                }
                ";
            }

            if ($link_hovercolortext) {
                $style .= "
                {$itemid} a:hover {
                    {$link_hovercolortext};
                }
                ";
            }



		//**grid style**
		$gridwidth = sanitize_text_field( $options['gridwidth'] );

		$gridalign = strtolower( sanitize_text_field( $options['gridalign'] ) );
		$gridalign = $gridalign == "center" ? "margin:auto;" : "float:$gridalign;";

		$gridstyle = $gridwidth ? "max-width:$gridwidth;width:100%;$gridalign" : "width:100%;$gridalign";
		$gridstyle = apply_filters( 'proto_masonry_gridstyles', $gridstyle, $options );

		if ( $fitwidth )

			$style .= "
            $container { $gridstyle }";
		else
			$style .= "$widget { $gridstyle } ";


		$imagestyle = apply_filters( 'proto_masonry_imagestyles', $imagestyle, $options );
		if ( $imagestyle != '' )
			$style .= "
                /* Image Style*/
                $itemid
                .{$prefix}_masonry_image {
                $imagestyle;
            }";
		$animate = isset($options['animate']) ? proto_boolval($options['animate']) : false;

		$animationduration = isset( $options['animationduration'] )  ? sanitize_text_field( $options['animationduration'] )  : '';

		if ( $animate )
		{
			$style .= "
			$widget,
			$itemid {
			  -webkit-transition-duration: $animationduration;
			  -moz-transition-duration: $animationduration;
			  -ms-transition-duration: $animationduration;
			  -o-transition-duration: $animationduration;
			  transition-duration: $animationduration;
			  }";
		}
		return $style;
	}
	/**
	 * doscript function.
	 * Create js scripts for the current grid
	 *
	 * @access public
	 * @static
	 * @param array   $options - plugin options
	 * @return script
	 */
	 function masonry_doscript( $script, $options ) { //Scripts for masonry
		$uniqueId = $options['uniqueid'];
		$thisid = "#proto-masonry-widget_$uniqueId";
		$prefix = sanitize_text_field( $options['prefix'] );
		$itemId = ".{$prefix}_masonry_item_{$uniqueId}";
		$additionalscript = '';
		$morescript = '';
		$loadedscript = proto_masonry_scripts::loaded_script( $thisid, $itemId, $options );
		$hovercaptions = isset( $options['hovercaptions'] ) ? proto_boolval( $options['hovercaptions'] ) : '';
		$masonryscript = proto_masonry_scripts::get_masonry_options(  $options );
		$additionalscript = apply_filters( 'proto_masonry_insert_masonry_scripts', $additionalscript,  $options, $thisid); //insert to masonry script code
		$morescript = apply_filters('proto_masonry_more_masonry_scripts', $morescript, $thisid, $options); //deprecated
		$morescript = apply_filters('proto_masonry_append_masonry_scripts', $morescript,  $options, $thisid); //append to script
		$script .= "
            (function ($) {
                $( '$thisid' ).parent().on( 'updatemasonry', function( event ) {
                    $loadedscript
                    $masonryscript
					$additionalscript
                });/* end update masonry*/
                $('$thisid').parent().trigger( 'updatemasonry' );
                $morescript
            })(jQuery); /*end function*/";
		return $script;
	}

	/**
	 * loaded_script function.
	 * return script that determines what happens when the grid is loaded
	 *
	 * @access public
	 * @static
	 * @param string  $thisid  - unique widgetid
	 * @param string  $itemId  - unique itemid
	 * @param array   $options - plugin options
	 * @return script
	 */
	static function loaded_script( $thisid, $itemId, $options ) {
		$displayas = isset($options['displayas']) ? $options['displayas'] : 'masonry';
		$fadeintime = isset( $options['fadeintime'] ) ? intval( $options['fadeintime'] ) : 500;
		$fction = str_replace( '-', '_', str_replace('#', '', $thisid) . '_myevent');

		$loadedscript = "
              $('$thisid').imagesLoaded(function()
              {
                $('$thisid').on( 'layoutComplete', function() {

				var $fction = function() {
				     $('$thisid .proto_masonry_load').hide().css('visibility', 'visible').fadeIn($fadeintime)
				};
				$.when( $fction() ).done( function() {
				     $('$thisid .proto_masonry_load').removeClass('proto_masonry_load');
				} );

                }); /*end layout complete */
                $('$thisid').$displayas('layout');
            ";

        if ( $options['captionheight'] ) {
           /* $loadedscript.= "
            $('$thisid .proto_masonry_fixed_height_caption .proto_title').dotdotdot();
            ";*/
           $loadedscript .= "document.addEventListener( 'DOMContentLoaded', () => {
			   let wrapper = document.querySelector( '$thisid .proto_masonry_fixed_height_caption .proto_title' );

			   new Dotdotdot( wrapper, options );
			});";
        }

        $loadedscript.= "
            });/*end images loaded */
        ";

		return $loadedscript;
	}

		/**
	 * masonry_script_options function.
	 * Get the script options in string format
	 * @access public
	 * @static
	 * @param string $masonryscriptoptions - existing script options
	 * @param array $options - plugin options
	 * @return string script options
	 */
	 function masonry_script_options( $masonryscriptoptions, $options)
	{

        $defaults = proto_masonry_scripts::masonry_defaults();

		$options['columnwidth']  = proto_functions::calculate_column_width($options); //override columnwidth default
		$scriptvalues = proto_functions:: proto_masonry_prefix_key_value($defaults, $options, '', true);
		//fix the cases because this is an old version of masonry
		$settings = get_option( 'featured_image_pro_settings' );
		$version = isset( $settings['proto_masonry_version'] ) ? $settings['proto_masonry_version'] : '3';
		if ( $version == '3' )
		{
			if ( array_key_exists( 'fitWidth', $scriptvalues ) )
			{
				$scriptvalues['isFitWidth'] = $scriptvalues['fitWidth'];
				unset($scriptvalues['fitwidth']);
			}


			if ( array_key_exists( 'initLayout', $scriptvalues ) )

			{
				$scriptvalues['isInitLayout'] = $scriptvalues['initLayout'];
				unset($scriptvalues['initlayout']);
			}
		}

		return $scriptvalues;
	}

	/**
	 * masonry_default_script function.
	 * create the javascript
	 * @access public
	 * @static
	 * @param string $script - in this specific case, script should be blank
	 * @param string $masonryscriptoptions  - javascript options
	 * @param array $options - plugin options
	 * @return javascript
	 */
	 function masonry_default_script( $script,  $masonryscriptoptions, $options )
	{
		$uniqueId = sanitize_text_field( $options['uniqueid'] );
		$prefix = sanitize_text_field( $options['prefix'] );
		$thisid = "#proto-masonry-widget_$uniqueId";
		$displayas = isset($options['displayas']) ? sanitize_text_field( $options['displayas'] ) : 'masonry';
		$uid = sanitize_text_field( $options['uid'] );
		$itemId = ".{$prefix}_masonry_item_{$uniqueId}";
		$masonryscript = proto_functions::generate_script($masonryscriptoptions);

		return "var grid_$uid = $('$thisid').$displayas({itemSelector:  '$itemId'," . $masonryscript . '});';
	}
	/**
	 * get_masonry_options function.
	 * create the full js script for the masonry
	 *
	 * @access public
	 * @static
	 * @param array   $options  - plugin options
	 * @return script
	 */
	static function get_masonry_options(  $options ) {

		$masonryscriptoptions = array();
		$masonryscriptoptions = apply_filters( 'proto_masonry_script_options', $masonryscriptoptions, $options );
		$full_script = '';
		$full_script = apply_filters('proto_masonry_full_script', $full_script, $masonryscriptoptions, $options); //masonry grid script
		$displayas = isset($options['displayas']) ? $options['displayas'] : 'masonry';
		$uniqueId = sanitize_text_field( $options['uniqueid'] );
		$uid = sanitize_text_field( $options['uid'] );
		$noimage_posts = isset( $options['show_noimage_posts'] ) ?  proto_boolval( $options['show_noimage_posts'] ) : false;
		$resizetimer  = isset($options['resizetimer']) ? intval( $options['resizetimer'] ) : 0;
		$showexcerpts = isset($options['showexcerpts']) ? proto_boolval($options['showexcerpts']) : false;
		$layoutonresize = isset($options['resizeonload']) ? proto_boolval($options['resizeonload']) : false;
		$layoutonresize = ($options['imageheight'] == '' || $options['imageheight'] == 'auto' || $options['imageheight'] == '0' || $noimage_posts || $layoutonresize || $showexcerpts);
        if ( $resizetimer > 0 && $layoutonresize )
        {
	      $full_script .= "
	      var resizeTimer_$uid;
			$(window).on('resize', function(e) {
			    clearTimeout(resizeTimer_$uid);
			    resizeTimer_$uid = setTimeout(function() {
			  	    grid_$uid.$displayas('layout');
			    }, $resizetimer);
			}); /*end resize*/
			";
        }
		return $full_script;
	}
	static function masonry_defaults()
	{
		$defaults = array('columnwidth' =>  0,
					'fitWidth' => false,
					'gutter' => 0,
					'transitionDuration'=>null,
					'resize' => true,
					'initLayout' => true,
					'columnWidth' => 150,
					);

		$defaults = apply_filters('masonry_defaults', $defaults);

		return $defaults;
	}
}
endif;