<?php
/*
* ProtoMasonry.php
* Code to create a Masonry grid
* Returns the code for the masonry
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !class_exists( 'proto_masonry' ) ):
	/**
	 * proto_masonry class.
	 */
	class proto_masonry {
	/**
	 * proto_masonry_pro function
	 * Generate a masonry grid based on selected options
	 *
	 * @access public
	 * @param mixed   $attobj  - attachment object
	 * @param mixed   $options - plugin options
	 * @param mixed   $atts    - query attributes - only used for filters
	 * @return html string complete masonry grid
	 */
	public function __construct()
	{
		add_filter( 'proto_masonry_widget_classes', array( $this, 'proto_animation_class' ), 15, 2 );
		add_filter( 'proto_masonry_item_classes', array( $this, 'proto_masonry_classes' ), 15, 2 );
	}
	/**
	 * remove_filters function.
	 * Remove filters (only those specifically registered)
	 * @access public
	 * @return void
	 */
	public function remove_filters()
	{
		remove_filter( 'proto_masonry_widget_classes', array( $this, 'proto_animation_class' ), 15 );
		remove_filter( 'proto_masonry_item_classes', array( $this, 'proto_masonry_classes' ), 15 );
	}
	/**
	 * remove_all_filters function.
	 * Remove all filters
	 * @access public
	 * @return void
	 */
		public function remove_all_filters()
	{
		remove_all_filters( 'proto_masonry_widget_classes' );
		remove_all_filters( 'proto_masonry_item_classes' );
	}
	/**
	 * proto_masonry_pro_grid function.
	 *
	 * @access public
	 * @param object $attobj object containing posts and attached info
	 * @param array $options - plugin options
	 * @param array $atts  - this is used for ajax only
	 * @return html
	 */
	public function proto_masonry_pro_grid( $attobj, $options, $atts ) {
		//Check for empty objects
		if ( empty( $attobj->attachments ) ) {
			return;
		}
		$proto_posts = $attobj->attachments;
		if ( count( $proto_posts ) == 0 )
			return;
		//get the unique id
		$prefix = sanitize_text_field( $options['prefix'] );
		$uniqueId = $options['uniqueid'];
		//Create an id for the item
		$theid="proto-masonry-widget_$uniqueId";
		//Set up the navigation if paging is enabled
		$output = '';
		$before = '';
		$after = '';
		$beforeitems = '';
		$afteritems = '';
		$classes =  new stdClass();
		$gridclass = "$theid {$prefix}_masonry_gallery proto_masonry_gallery";
		$before = apply_filters( 'proto_masonry_before_grid', $before,  $attobj, $options, $atts );
		$after = apply_filters( 'proto_masonry_after_grid', $after, $attobj, $options, $atts );
		$beforeitems = apply_filters( 'proto_masonry_before_items', $beforeitems, $options );
		$afteritems = apply_filters( 'proto_masonry_after_items', $afteritems, $options );
		$gridclass = apply_filters( 'proto_masonry_grid_class', $gridclass, $options );
		$output .=  "<div id='$theid' class='$gridclass'  data-masonry-caption='#adv-custom-caption_$uniqueId'  >" . $beforeitems;
		$count = 0;
		$output .= $this->proto_masonry_pro_items( $proto_posts, $options);
		$output .= $afteritems;
		$output .= '</div><!--masonry-gallery-->';
		$this->remove_filters();
		return $before . $output . $after;  //If we echo this, it will display at the top of the page which is not what we want.
	}
	/**
	 * proto_masonry_styles function.
	 * return internal stylesheet
	 * @access public
	 * @param object $proto_post
	 * @param array $options
	 * @return void
	 */
	function proto_masonry_styles($proto_post, $options)
	{
		$imagewidth = isset( $options['imagewidth'] ) ? sanitize_text_field( $options['imagewidth'] ) : '';  //Global Image Width setting
		$imageheight = isset( $options['imageheight'] ) ?  sanitize_text_field( $options['imageheight'] ) : ''; //Global Image height setting
		$maxwidth = isset( $options['maxwidth'] ) ? sanitize_text_field( $options['maxwidth'] ) : '';  //Global Image Width setting
		$maxheight = isset( $options['maxheight'] ) ? sanitize_text_field( $options['maxheight'] ): ''; //Global Image height setting
		$captionheight = isset( $options['captionheight'] ) ? sanitize_text_field( $options['captionheight'] ) : ''; //Global fixed caption height

		$excerptheight = isset( $options['excerptheight'] ) ? sanitize_text_field( $options['excerptheight'] ) : ''; //ExcerptHeight
		$hovercaptions =  proto_boolval( $options['hovercaptions'] ); //HoverCaptions
		$itemwidth  = isset( $options['itemwidth'] ) ? sanitize_text_field( $options['itemwidth'] ): ''; // Grid item width
		if ($imagewidth == '' || $imagewidth == '0')
			$imagewidth = 'auto';
		if ($imageheight == '' || $imageheight == '0')
			$imageheight = 'auto';
		$imagewval = 'auto';
		// Unique Styles for item objects
		$styles = new stdClass();
		$styles->itemstyle = '';
		$styles->figstyle = '';
		$styles->imgstyle = '';
		$styles->excerptstyle='';
		$styles->subcaptionstyle='';
		$imagewactual = isset($proto_post->initialWidth) ? $proto_post->initialWidth : '';
		if ( isset( $proto_post->id ) && intval( $proto_post->id ) != 0 )
		{
			$imagehval = $imageheight != '' ? $imageheight : 'auto';
			$imagewval = $imagewidth != '' ? $imagewidth : 'auto';
			if ( is_numeric( $maxwidth ) || strpos( $maxwidth, 'px' ) > 0 )
			{
				$imagewactual = min(intval( $maxwidth) , $imagewactual);
			}
			$imagewactual .= 'px';
			if ( $imagewidth == 'auto' && $imageheight == 'auto' ) //If no global image width add styles for the image, caption and excerpt
				{
				$imagehval = 'auto';
				$capexcwidth = 'auto';
				$imagewval = 'auto';
			}
			elseif ($imagewidth == 'auto' &&  $imageheight != 'auto')
			{
				$capexcwidth = $proto_post->initialWidth . 'px';
				$imagewval = 'auto';
			}
			else
			{
				$imagewactual = $imagewidth != '' ? $imagewidth : 'auto';
				$capexcwidth = $imagewidth != ''? $imageheight : 'auto';
			}
			$styles->imgstyle =   "width:$imagewval;height:$imagehval;" ;  //Set image width to image width

			$styles->figstyle = "width:$imagewactual;";
		}
		$styles->itemstyle = 'visibility:hidden;';
		if ( $itemwidth ) {
			$styles->itemstyle .= "width:$itemwidth;";
			$capexcwidth = $itemwidth;
			if ( !$hovercaptions )

				$styles->capstyle = "width:$capexcwidth;" ;  //set caption width to item width
			else
				$styles->capstyle = "width:$imagewactual;";
			$styles->excerptstyle .= "width:$capexcwidth;";     //Set excerpt width to item width
			$styles->subcaptionstyle .= "width:$capexcwidth;";
		}

		else {
			$styles->capstyle = "width:$imagewactual;" ;  //set caption width to image width
			$styles->excerptstyle .= "width:$imagewactual;";     //Set excerpt width to image width
			$styles->subcaptionstyle .= "width:$imagewactual;";
		}
		if ( $options['excerptheight'] ) {
			$excerptheight = sanitize_text_field( $options['excerptheight'] );
			$styles->excerptstyle  .= "height:$excerptheight;";
		}
		$maxstyle = '';
		if ( $maxwidth ) {
			$maxstyle = "max-width:$maxwidth;";
			$styles->imgstyle .= $maxstyle;
			$styles->figstyle .= $maxstyle;
			if ( !$itemwidth ) {
				$styles->capstyle .= $maxstyle;
				$styles->itemstyle .= $maxstyle;
				$styles->excerptstyle .= $maxstyle;
				$styles->subcaptionstyle .= $maxstyle;
			}
		}
		if ( $maxheight ) {
			$styles->imgstyle .= "max-height:$maxheight;";
		}


		$styles->figstyle = apply_filters( 'proto_masonry_item_figure_style', $styles->figstyle, $options, $proto_post);
		$styles->itemstyle = apply_filters( 'proto_masonry_item_inline_style', $styles->itemstyle, $options, $proto_post );
		$styles->imgstyle = apply_filters( 'featured_image_pro_image_inline_style', $styles->imgstyle, $options, $proto_post );
		$styles->capstyle = apply_filters( 'featured_image_pro_caption_inline_style', $styles->capstyle, $options, $proto_post );
		$styles->excerptstyle = apply_filters( 'featured_image_pro_excerpt_inline_style', $styles->excerptstyle, $options, $proto_post );
		$styles->subcaptionstyle = apply_filters( 'featured_image_pro_subcaption_inline_style', $styles->excerptstyle, $options, $proto_post );

		return $styles;
	}
	/**
	 * proto_masonry_pro_items function.
	 * Html for masonry items
	 * @access public
	 * @param object $proto_posts proto - array of posts
	 * @param array $options - plugin options
	 * @return html
	 */
	function proto_masonry_pro_items( $proto_posts, $options ) {
		$output = '';
		$count = 0;
		if ( count( $proto_posts ) == 0 )
			return;
		$style = '';
		$cstyle = '';   //Style for the caption
		if ( isset( $options['target'] ) )
			$target = sanitize_text_field( $options['target'] );
		//Get the target for the links
		else
			$target = proto_boolval( $options['openwindow'] ) ? '_blank' : '';
		//Set the target to value (usually blank)
		$otarget = $target ? "target='$target'" : '';
		//The prefix for the widget ie: featured-image-pro, gallery-image-pro
		$prefix = sanitize_text_field( $options['prefix'] );
		//unique identification
		$uniqueId = $options['uniqueid'];
		$hovercaptions =  proto_boolval( $options['hovercaptions'] ); //HoverCaptions
		$showcaptions = proto_boolval( $options['showcaptions'] );  //Show Captions
		$excerpthr = proto_boolval( $options['excerpthr'] );   //Include a horizontal line under excerpt
		$captionhr = isset ( $options['captionhr'] ) ? proto_boolval ( $options['captionhr'] ) : false;
		if ( isset ($options['excerpt'] )  && !isset( $options['showexcerpts'] ) )
			$excerpt = proto_boolval( $options['excerpt'] );
		else
			$excerpt = proto_boolval( $options['showexcerpts'] );    //Display the Excerpt
		//Classes Object
		$classes = new stdClass();
		$classes->captionclass = '';
		$classes->subcaptionclass = '';
		$classes->excerptclass = '';
		$classes->itemclass = '';
		$classes->imageclass = '';
		$classes->linkclass = '';
		$classes=apply_filters( 'proto_masonry_item_classes', $classes, $options );
		if ($classes->linkclass != '')
			$classes->linkclass = 'class="' . $classes->linkclass . '"';
		foreach ( $proto_posts as $proto_post ) {
			++$count;
			$postexcerpt = $proto_post->excerpt; //post excerpt
			if ( isset( $proto_post->img_url ) )
				$img = $proto_post->img_url;   //image url
			else
				$img = '';
			$alt = $proto_post->alt;    //Image alt
			$title = apply_filters( 'proto_masonry_post_title',  $proto_post->title, $options ); //post title
			$title = apply_filters( 'proto_image_pro_post_title', $proto_post->title, $options );  // old
			$caption = apply_filters( 'proto_masonry_post_caption', $proto_post->caption, $options ); //post caption
			$caption = apply_filters( 'proto_image_after_caption' , $proto_post->caption, $options ); //deprecated

			$largeurl = isset( $proto_post->large_url ) ? $proto_post->large_url : ''; //link for large url
			$posturl = isset( $proto_post->link_url ) ? $proto_post->link_url : ''; //Link to the post
			$posturl = apply_filters('proto_masonry_posturl', $posturl, $options, $proto_post);
			$linkargs = '';
			$linkargs = apply_filters('proto_masonry_linkargs', $linkargs, $options, $proto_post ); //add to link arguments (like data arguments)
			$styles = $this->proto_masonry_styles($proto_post, $options);
			$capstyle = "style='{$styles->capstyle}'";
			$style="style='{$styles->imgstyle}'";
			$extra = $proto_post->extra;        //Extra stuff from the filter goes after the caption
			$tooltip = isset($options['tooltip']) && proto_boolval($options['tooltip']) ? "title='$title'" : '';
			/*=====Caption======*/
		    $rel = isset( $options['rel'] ) ? "rel='". sanitize_text_field( $options['rel'] ) . "'"  : '';
			$figurecaption = "
			<figcaption class='{$classes->captionclass}' {$capstyle}>
				<div class='proto_title_container'>
                    <p class='proto_title'>
                        <a href='$posturl' class='proto_caption_link_$uniqueId'  $otarget>{$caption}
                        </a>
                    </p>
				</div>
			</figcaption>";
			/*====Subcaptions===*/
			$linksubcaptions = isset($options['linksubcaptions']) ? proto_boolval($options['linksubcaptions']) : false;

			$subcaption = $this->proto_subcaptions_html( $proto_post, $posturl, $otarget, $uniqueId, $linksubcaptions, $classes->subcaptionclass, $styles->subcaptionstyle) ;
			/*==========Item==========*/
			$itemclass = apply_filters('proto_masonry_item_post_class', $classes->itemclass, $proto_post, $options ); //individual item class
			$output .= "
            <div id = 'masonry_id_{$uniqueId}_{$count}' style='$styles->itemstyle'  class='masonry_id_{$uniqueId} proto_masonry_item proto_masonry_item_$uniqueId {$prefix}_masonry_item {$prefix}_masonry_item_$uniqueId masonry_id_$count $itemclass'>";
			if ( $img ) {
				$imageoutput = "<img class='$classes->imageclass' alt='$alt'  $tooltip src='$img' $style >";
				$imageoutput = apply_filters( 'proto_masonry_image_output', $imageoutput, $proto_post, $options );   //Apply filters to the image
				$imageoutput = "<a href='$posturl' $classes->linkclass $rel $linkargs $otarget>$imageoutput</a>";
			}
			else
				$imageoutput = '';
			$figureoutput =  "
            <figure class='{$prefix}_masonry_figure proto_masonry_figure' style='$styles->figstyle'>$imageoutput";
			//Hover caption goes inside of the figure
			if ( $caption && $hovercaptions )
				$figureoutput .= $figurecaption;
			$figureoutput .= '</figure><!--figure-->';
			//All other captions go below the figure
			if ($caption && $showcaptions && !$hovercaptions)
				$figureoutput .= $figurecaption;
			$figureoutput .= $subcaption;
			if ( $captionhr ) //Insert the horizontal line
				$figureoutput .= "<hr class='proto_caption_hr'>";

			//Add the excerpt if there is one
			if ( $excerpt && $postexcerpt ) {
				//Add excerpt to the figure
				$excerptoutput = '';
					$excerptoutput .= "<div style='{$styles->excerptstyle}' class='$classes->excerptclass'>";
				$excerptoutput .= $postexcerpt;
				if ( $excerpthr ) //Insert the horizontal line
					$excerptoutput .= "<hr class='proto_excerpt_hr'/>";
				$excerptoutput .= '</div>';
				$excerptoutput = apply_filters( 'featured_image_excerpt', $excerptoutput, $options );
				$figureoutput .= $excerptoutput;
			}
			$figureoutput = apply_filters( 'feature_image_pro_item_figure', $figureoutput, array( $proto_post, $options ) ); //apply filters to the content
			if ( isset( $proto_post->custom_button ) && $proto_post->custom_button != '' )
				$figureoutput .= $proto_post->custom_button;
			$output .= $figureoutput;
			$output .= $extra;
			$output .= '</div><!--masonry-item-->';
		}
		$this->remove_filters(); //use remove_all_filters here to make sure that all of the filters registered in construct are removed
		return $output;
	}
	/**
	 * proto_animation_class function.
	 * return the animation class
	 * @access public
	 * @param stsring $class - animation class
	 * @param array $options - plugin options
	 * @return class
	 */
	function proto_animation_class( $class, $options )
	{
		$animationduration = isset( $options['animationduration'] ) ? sanitize_text_field( $options['animationduration'] )  : '';
		$class =   ( $animationduration != '' ) ? ' masonry-animate ' : '';
		return $class;
	}
	/**
	 * proto_masonry_classes function.
	 * Create classes for each item
	 * @access public
	 * @param object $classes - object of classes
	 * @param array $options - plutin options
	 * @return updated classes option
	 */
	function proto_masonry_classes( $classes, $options )
	{
		$excerptheight = isset( $options['excerptheight'] ) ? sanitize_text_field( $options['excerptheight'] ) : ''; //ExcerptHeight
		$captionheight = isset( $options['captionheight'] ) ? sanitize_text_field( $options['captionheight'] ) : ''; //ExcerptHeight
		$prefix = isset( $options['prefix'] ) ? sanitize_text_field( $options['prefix'] ) : 'featured_image_pro'; //prefix
		$hovercaptions = proto_boolval( $options['hovercaptions'] ) ; //HoverCaptions
		$hovclass = $hovercaptions ? ' featured_image_pro_hover_caption proto_hover_caption ':'';
		$classes->captionclass .=  " {$prefix}_masonry_caption proto_masonry_caption $hovclass";
		if ( $captionheight )
			$classes->captionclass .= $prefix . '_masonry_fixed_height_caption proto_masonry_fixed_height_caption ';
		if ( $excerptheight )
			$classes->excerptclass .= $prefix . '_masonry_fixed-height-excerpt proto_masonry-height_excerpt ';

		$classes->itemclass .= isset( $options['border'] ) && intval( $options['border'] ) > 0 ?  $prefix . '_masonry_border proto_masonry_border ' : '';
		$classes->itemclass .=  isset( $options['boxshadow'] ) && proto_boolval( $options['boxshadow'] ) ? ' proto_masonry_item_boxshadow ' : '';
		$classes->itemclass .= 'proto_masonry_load ';

		$classes->imageclass .= ' ' . $prefix . '_masonry_image proto_masonry_image ';
		$classes->excerptclass .=  $prefix . '_masonry_excerpt proto_masonry_excerpt ';
		if ( proto_boolval( $options['padimage'] ) ) {
			$classes->imageclass .= ' padimage ';
		}

		$classes->linkclass = '';
		$classes->captionclass = apply_filters('proto_masonry_caption_class', $classes->captionclass, $options);
		$classes->subcaptionclass = apply_filters('proto_masonry_subcaption_class', $classes->subcaptionclass, $options);
		$classes->excerptclass = apply_filters('proto_masonry_excerpt_class', $classes->excerptclass, $options);
		$classes->itemclass = apply_filters('proto_masonry_item_class', $classes->itemclass,  $options);
		$classes->imageclass = apply_filters('proto_masonry_image_class', $classes->imageclass, $options);
		$classes->linkclass = apply_filters('proto_masonry_link_class', $classes->linkclass, $options);

		return $classes;
	}
	/**
	 * proto_subcaptions_html function.
	 * Return html for the subcaptions
	 * @access public
	 * @param object $proto_post
	 * @return html
	 */
	function proto_subcaptions_html( $proto_post, $posturl,  $otarget, $uniqueid, $linksubcaptions, $subcaptionclass='', $subcaptionstyle = '' )
	{
		$count = 0;
		$nsubcaption = '';
		$style = $subcaptionstyle != ''? "style='$subcaptionstyle'" : '';
		if ( isset( $proto_post->subcaption ) )
		{
			foreach ( $proto_post->subcaption as $key=>$subval )
			{
				$asubcaption = '';
				$count++;
				if ( $subval != '' )
				{
					if ( is_array( $subval ) )
					{
						foreach ( $subval as $subcap )
						{
							if ( $linksubcaptions == true )
								$subcap = "<a href='$posturl' class='proto_subcaption_$uniqueid'  $otarget>$asubcaption</a>";
							else
								$subcap = "<span class='proto_subcaption_$uniqueid'>$asubcaption</span>";
							$asubcaption .= $asubcaption == '' ? $subcap : ' ' . $subcap;
						}
					}
					else
						if ($linksubcaptions)
							$asubcaption = "<a href='$posturl' class='proto_subcaption_$uniqueid'  $otarget>$subval</a>";
						else
							$asubcaption = "<span  class='proto_subcaption_$uniqueid'>$subval</a>";
				}
				if ($asubcaption != '')
				$nsubcaption .= "<p  $style class='proto_$key proto_subcaption proto_subcaption$count proto_subcaption_$key  $subcaptionclass'>$asubcaption</p>";
			}
		}
		return  $nsubcaption;
	}
}
endif;