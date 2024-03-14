/**
 * Javascript function to hook into Gutenberg.
 *
 * @package wp-monalisa
 */

/**
 * Function to create the popover to show the smilies in the RichEditor.
 */
function wpml_gutenberg_popover(){
	var ppo = document.createElement( "div" );
	ppo.className = "wpml-gutenberg-popover";
	var out = "<div class='wpml-gutenberg-popover-cancel'></div><b>wp-monalisa Smilies</b><br/>";
	var spr = 7; // smilies per row.

	var i = 1;
	window._wpml_richedit_smilies.forEach(
		function(smiley){
			out += '<a href="#" title="' + smiley[1] + '"><img src="' + smiley[2] + '"></a>';
			if ( i % spr == 0 ) {
				out += "<br/>";
			}
			i = i + 1;
		}
	);

	ppo.innerHTML = out;
	document.body.appendChild( ppo );

	ppo.querySelector( ".wpml-gutenberg-popover-cancel" ).addEventListener( "click",function(){ ppo.parentNode.removeChild( ppo ); } )
	return ppo;
}

( function( wp ) {
	var wpml_gutenbergbutton = function( props ) {
		return wp.element.createElement(
			wp.blockEditor.RichTextToolbarButton,
			{
				icon: 'smiley',
				title: 'Smilies',
				onClick: function() {
					var ppo = wpml_gutenberg_popover();

					ppo.querySelectorAll( "a" ).forEach(
						function(smlink){
							smlink.addEventListener(
								"click",
								function(){
									var emoticon = smlink.getAttribute( "title" );
									var emoticon_link = smlink.firstChild.getAttribute( "src" );
									var smiley_inline = '<img src="' + emoticon_link + '" alt="' + emoticon + '" class="wpml_ico">';
									var smiley_richtext = wp.richText.create( { html: smiley_inline } );

									props.onChange( wp.richText.insert( props.value, smiley_richtext ) );
									ppo.parentNode.removeChild( ppo );
								}
							);
						}
					)

				},
				isActive: props.isActive,
			}
		);
	}
	wp.richText.registerFormatType(
		'wp-monalisa/smiley',
		{
			title: 'Smilies',
			tagName: 'samp',
			className: null,
			edit: wpml_gutenbergbutton,
		}
	);
} )( window.wp );
