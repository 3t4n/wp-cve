/* block.js */


( function( blocks, element, serverSideRender ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        serverSideRender = serverSideRender;


		registerBlockType( 'dsgvo-all-in-one-for-wp/opt-in-out', {
			title: 'DSGVO Opt in & Out',
			icon: 'shield',
			category: 'common',
	 
			edit: function( props ) {
				return el( 'img', {
					src: dsgvoaio_blockparms.dsgvoaio_imgdir + 'service_control.png',
					alt: props.category
				} );
				},
		} );  
		registerBlockType( 'dsgvo-all-in-one-for-wp/show-user-data', {
			title: 'DSGVO Benutzerdatenauszug',
			icon: 'shield',
			category: 'common',
	 
			edit: function( props ) {
					return (
						el( serverSideRender, {
							block: 'dsgvo-all-in-one-for-wp/show-user-data',
							attributes: props.attributes,
						} )
					);
				},
		} ); 
		registerBlockType( 'dsgvo-all-in-one-for-wp/remove-user-data', {
			title: 'DSGVO  Benutzerdatenlöschung',
			icon: 'shield',
			category: 'common',
	 
			edit: function( props ) {
					return (
						el( serverSideRender, {
							block: 'dsgvo-all-in-one-for-wp/remove-user-data',
							attributes: props.attributes,
						} )
					);
				},
		} ); 
		registerBlockType( 'dsgvo-all-in-one-for-wp/privacy-policy', {
			title: 'DSGVO Datenschutzbedingungen',
			icon: 'shield',
			category: 'common',
	 
			edit: function( props ) {
					return (
						el( serverSideRender, {
							block: 'dsgvo-all-in-one-for-wp/privacy-policy',
							attributes: props.attributes,
						} )
					);
				},
		} );
		registerBlockType( 'dsgvo-all-in-one-for-wp/imprint', {
			title: 'DSGVO Impressum',
			icon: 'shield',
			category: 'common',
	 
			edit: function( props ) {
					return (
						el( serverSideRender, {
							block: 'dsgvo-all-in-one-for-wp/imprint',
							attributes: props.attributes,
						} )
					);
				},
		} );	
		registerBlockType( 'dsgvo-all-in-one-for-wp/facebook-like', {
			title: 'DSGVO Facebook Like Button',
			icon: 'facebook-alt',
			category: 'embed',
	 
			edit: function( props ) {
				
				return el( 'img', {
					src: dsgvoaio_blockparms.dsgvoaio_imgdir + 'fblike.png',
					alt: props.category
				} );
				
				}
		} );	
		registerBlockType( 'dsgvo-all-in-one-for-wp/facebook-comments', {
			title: 'DSGVO Facebook Kommentare',
			icon: 'facebook-alt',
			category: 'embed',
	 
			edit: function( props ) {
				
				return el( 'img', {
					src: dsgvoaio_blockparms.dsgvoaio_imgdir + 'fbcomments.png',
					alt: props.category
				} );
				
				}
		} );	
		registerBlockType( 'dsgvo-all-in-one-for-wp/shareaholic', {
			title: 'DSGVO Shareaholic',
			icon: 'megaphone',
			category: 'embed',
	 
			edit: function( props ) {
				
				return el( 'img', {
					src: dsgvoaio_blockparms.dsgvoaio_imgdir + 'shareaholic.png',
					alt: props.category
				} );
				
				}
		} );	
		registerBlockType( 'dsgvo-all-in-one-for-wp/twitter-tweet', {
			title: 'DSGVO Twitter Tweet',
			icon: 'twitter',
			category: 'embed',
	 
			edit: function( props ) {
				
				return el( 'img', {
					src: dsgvoaio_blockparms.dsgvoaio_imgdir + 'twitter.png',
					alt: props.category
				} );
				
				}
		} );		
		registerBlockType( 'dsgvo-all-in-one-for-wp/linkedin', {
			title: 'DSGVO LinkedIn',
			icon: 'megaphone',
			category: 'embed',
	 
			edit: function( props ) {
				
				return el( 'img', {
					src: dsgvoaio_blockparms.dsgvoaio_imgdir + 'linkedin.png',
					alt: props.category
				} );
				
				}
		} );		
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.serverSideRender,
) );