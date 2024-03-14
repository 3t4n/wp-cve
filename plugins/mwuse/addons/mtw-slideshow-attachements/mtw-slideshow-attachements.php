<?php
function mtw_greaty_seek_slides($dom)
{

	if( !is_admin() )
	{
		global $post;
		global $mtw_option;

		$images = array();

		if( is_singular() && @$mtw_option['mtw_slideshow_attachments'] == 'checked' )
		{
			$attached_medias = get_attached_media( 'image' );
			$images = $attached_medias;
		}
		
		$slides = mw_dom_getElementsByClass($dom, "SlideShowWidget");
		foreach ($slides as $key => $slide) 
		{
			$images = apply_filters( 'mtw_slide_show_images_filter', $images, $slide );
			if( empty($images) )
			{
				continue;
			}			
			//keep one slide && prepare copy
			$SSSlides = mw_dom_getElementsByClass( $dom, "SSSlide", $slide );
			foreach ($SSSlides as $key => $SSSlide) 
			{
				if( $key == 0 )
				{
					$parentNode = $SSSlide->parentNode;
					$to_copy = $SSSlide;
					$imageRef = $to_copy->getElementsByTagName('img')->item(0);
					$data_width = $imageRef->getAttribute('data-width');
					$data_height = $imageRef->getAttribute('data-height');
				}
				else
				{
					$SSSlide->parentNode->removeChild($SSSlide); 
				}
			}
			//keep one caption && prepare copy
			$SSSlideCaptions = mw_dom_getElementsByClass( $dom, "SSSlideCaption", $slide );
			foreach ($SSSlideCaptions as $key => $SSSlideCaption) 
			{
				if( $key == 0 )
				{
					$SSSlideCaption_parentNode = $SSSlideCaption->parentNode;
					$SSSlideCaption_to_copy = $SSSlideCaption;
				}
				else
				{
					$SSSlideCaption->parentNode->removeChild($SSSlideCaption); 
				}
			}
			//remove thumbs if exist
			$SSSlideLinks_container = mw_dom_getElementsByClass( $dom, "SSSlideLinks", $slide->parentNode );
			
			if( $SSSlideLinks_container->length > 0 )
			{
				$SSSlideLinks_container->item(0)->setAttribute('data-thumbs', json_encode( array_values( $images ) ) );
				$SSSlideLinks = mw_dom_getElementsByClass( $dom, "SSSlideLink", $slide->parentNode );
				foreach ($SSSlideLinks as $key => $SSSlideLink) 
				{
					//$SSSlideLink->parentNode->removeChild($SSSlideLink);
					//$SSSlideLink->setAttribute('class', 'SSSlideLink');
				}
				$SSSlideLinks_cols = $SSSlideLinks_container->item(0)->getElementsByTagName('div');
				/*echo "<pre>";
				print_r( htmlentities( mw_DOMinnerHTML( $SSSlideLinks_container->item(0) ) ) ); 
				print_r($SSSlideLinks_cols);*/
			}
			

			if($images)
			{
				if( $images['size'] )
				{
					$size = $images['size'];
					unset($images['size']);
				}
				else
				{
					$size = array( $data_width , $data_height, 1 );
				}
				$images = array_values($images);
				$clones = array();
				$clones_caption = array();
				$img_tags = array();
				
				global $slide_image;
				foreach ($images as $key => $image) 
				{
					$clones[$key] = $to_copy->cloneNode(true);
					if( $SSSlideCaption_to_copy )
					{
						$clones_caption[$key] = $SSSlideCaption_to_copy->cloneNode(true);
					}
					$img_tags[$key] = $clones[$key]->getElementsByTagName('img')->item(0);					
					$id = $image->ID;
					if(!$id)
					{
						$id = $image['ID'];
					}
					if( !$id )
					{
						continue;
					}
					$slide_image = $image;

					$src = wp_get_attachment_image_src( $id, 'thumbnail' );

					$diff_w =  ( $data_width - $src[1] ) / 2;
					$diff_h =  ( $data_height - $src[2] ) / 2;
					
					$img_tags[$key]->setAttribute('data-src_id', $id);
					mw_dom_add_class( $img_tags[$key], 'mtw-thumb');


					$img_tags[$key]->setAttribute('data-src',  $src[0]);
					$img_tags[$key]->setAttribute('src', '');
					$img_tags[$key]->setAttribute('data-height', $data_height);
					$img_tags[$key]->setAttribute('data-fixedheight','true');

					$parentNode->appendChild($clones[$key]);

					if( $SSSlideCaption_to_copy )
					{
						$caption = $SSSlideCaption_parentNode->appendChild($clones_caption[$key]);	
						@$caption->getElementsByTagName('p')->item(0)->nodeValue = $image->post_excerpt;						
					}
				}
			}
			$to_copy->parentNode->removeChild($to_copy);
			if( $SSSlideCaption_to_copy )
			{
				$SSSlideCaption_to_copy->parentNode->removeChild($SSSlideCaption_to_copy);
			}
		}
	}
}
add_filter( 'DOMDocument_loaded', 'mtw_greaty_seek_slides', 9 );

function mwuse_SSSlideLink_head()
{
	?>
	<style type="text/css">
		.SSSlide img.mtw-thumb
		{
			visibility: hidden;
		}
	</style>
	<script type="text/javascript">
		var base_html = '<div class="SSSlideLink clip_frame colelem" data-col-pos="0" role="tab" tabindex="0" style="height: 45px;"><img class="block" data-col-pos="0" id="u637_img" alt="" data-heightwidthratio="0.75" data-image-width="60" data-image-height="45" src="http://192.168.1.30/mtw-tests/mtw-official/mtw-themes/test/images/1340273569.jpg" data-widget-id="slideshowu633" style="height: 45px;"></div>';

		jQuery(document).ready(function($) {

				$('.SSSlideLinks').each(function(index, el) {
					if( $(el).attr('data-thumbs') )
					{						
						$(el).attr('data-image-width', $(el).find('img').attr('data-image-width') );
						$(el).find('.SSSlideLink').find('img').remove();
						$(el).find('.SSSlideLink').removeClass('SSSlideLink');
						
					}

					if( $(el).attr('data-thumbs') )
					{
						console.log( $.parseJSON( $(el).attr('data-thumbs') ) );

						$.each( $.parseJSON( $(el).attr('data-thumbs') ) , function(index, val) {

							new_node_SSSlideLink = $(base_html);
							new_node_SSSlideLink.attr('data-col-pos', index);
							new_node_SSSlideLink.find('img').first().attr('src', val.guid);



							new_node_SSSlideLink.css({
								'display': 'inline',
								'width' : '25%',
								'clear' : 'none'
							});

						 	$(el).append(new_node_SSSlideLink);
						 
						});
					}
				});

		});		

	</script>
	<?php
}
add_action( 'wp_head',  'mwuse_SSSlideLink_head' );

function mwuse_slideshow_thumbs()
{
	?>
	<script type="text/javascript">
		
		jQuery(document).ready(function($) {

			$(window).on('mtw_muse_bp_change', function(event) {				
				
				$('#page .SSSlide').each(function(index, el) {
					//console.log(  $(el).parent().height() );
					//$(el).attr('data-src', '' ); 
					//$(el).attr('src', '' ); 
					//$(el).remove();
					//$(el).css('opacity', '0');
					//$(el).removeClass('mtw-thumb');
					if( $(el).parent().height() > 0 )
					{
						$(el).css( 'height', $(el).parent().height() );
					}
				});

				$('#page .SSSlideLinks').each(function(index, el) {

					if( $(el).attr('data-thumbs') )
					{
						linewidth = $(el).outerWidth();
						colwidth = $(el).attr('data-image-width');
						
						max_cols = Math.round( linewidth / colwidth );
						row = 0;
						current_col = -1;

						//second chance cols
						cols = $(el).find('> div');
						if( cols.length > 1 && cols.eq(0).hasClass('SSSlideLink') == false )
						{
							colwidth = cols.eq(0).width();
							col_gutter = ( cols.eq(1).offset().left - $(el).offset().left ) - ( cols.eq(0).offset().left - $(el).offset().left ) - colwidth;
							percent_left_col_1 = ( ( cols.eq(0).offset().left - $(el).offset().left ) / linewidth ) * 100;
							count_col = 0;
							$(el).find('> div').each(function(index2, el2) {
								if( $(el2).hasClass('SSSlideLink') == false )
								{
									count_col++;
									$(el2).remove();
								}
							});
							max_cols = count_col;
						}

						$(el).removeAttr('data-thumbs');
						$(el).css({
							'text-align' : 'center'
						});

						if( col_gutter )
						{
							if( col_gutter > 0 )
							{
								col_gutter_perc = (col_gutter / linewidth) * 100 ;

								new_width = colwidth - ( ( col_gutter * (max_cols - 1) ) / max_cols );
								
								$(el).find('.SSSlideLink').css({
									'margin-left' : col_gutter_perc + '%',
									'width' : ( (new_width / linewidth) * 100 ) + "%",
									//'clear' : 'left',
								});
								$(el).find('.SSSlideLink:nth-child('+max_cols+'n+1)').css({
									'margin-left' : percent_left_col_1 + '%',
									'width' : ( (new_width / linewidth) * 100 ) + "%",
									'clear' : 'left'
								});
								$(el).find('.SSSlideLink:nth-child(n+'+ (max_cols+1) +')').css({
									'margin-top' : col_gutter_perc + '%'
								});
							}
						}
					}
				});

				/*$('.SlideShowWidget').each(function(index, el) {
					$(el).find('.wp-slideshow-clip, .SlideShowContentPanel, .SSSlide').css({
						'width': '100%',
					});
				});*/
			});
			
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'mwuse_slideshow_thumbs' );
?>