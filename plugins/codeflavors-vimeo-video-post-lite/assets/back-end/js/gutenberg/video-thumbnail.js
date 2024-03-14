;(function($){
    $(document).ready( function(){
        var el = wp.element.createElement,
            __ = wp.i18n.__,
            elDescription = __( 'Imports Vimeo image as post featured image. If image already exists it will be duplicated.', 'codeflavors-vimeo-video-post-lite' );

        function wrapPostFeaturedImage( OriginalComponent ) { 
            return function( props ) {                
                return (
                    el(
                        wp.element.Fragment,
                        {}, 
                        null,
                        el( OriginalComponent, props ),
                        el( 'hr' ),
                        el( 'a', {
                                href: '#',
                                id: 'cvm-import-video-thumbnail',
                                onClick: function( event ){
                                    importFeaturedImage( event, props );
                                }
                            },
                            __( 'Import Vimeo image', 'codeflavors-vimeo-video-post-lite' )
                        ),
                        el( 'p', { className: 'description', 'id': 'cvm-thumb-response' },
                            elDescription
                        )  
                    )
                );
            } 
        } 
        
        /*
            Click event callback of image import button
        */
        function importFeaturedImage( e, props ){
            e.preventDefault();
			
			var $this = e.target,
			    html = $($this).html();
				
			if( $($this).hasClass('loading') ){
				$($this).html( __('... still loading', 'codeflavors-vimeo-video-post-lite' ) );
				return;
			}
			
			var data = {
			    refresh : props.featuredImageId,
			    post: props.currentPostId
			};
			data.action = 	'cvm_import_video_thumbnail';
			data.gutenberg = 1;
			
			$($this).addClass('loading').html( __( '... loading', 'codeflavors-vimeo-video-post-lite' ) );
			
			$.ajax({
				type 	: 'post',
				url 	: ajaxurl,
				data	: data,
				success	: function( response ){
					if( true == response.success ){					
						// update Gutenberg featured image component 
						wp.data.dispatch( 'core/editor' ).editPost( { featured_media: response.data.attachment_id } );	
						$($this).removeClass('loading').html( __( 'Import Vimeo image', 'codeflavors-vimeo-video-post-lite' ) );
						$('#cvm-thumb-response').addClass('cvm-success').html( __( 'Image imported successfully.', 'codeflavors-vimeo-video-post-lite' ) );
					}else{
						$($this).html(html).removeClass('loading');
						$('#cvm-thumb-response').addClass('cvm-error').html( response.data );
					}	
					setTimeout( function(){
                        $('#cvm-thumb-response').removeClass('cvm-error cvm-success').html( elDescription );
                    }, 3000);
				}
			});	
        }


        wp.hooks.addFilter( 
            'editor.PostFeaturedImage', 
            'vimeo-video-post/post-featured-image', 
            wrapPostFeaturedImage
        );

    });
})(jQuery);