/**
 * BLOCK: ichart-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { RichText } = wp.editor;
import { ServerSideRender } from '@wordpress/components';

function Qcpd_ichart_Shortcode_Preview( { shortcode } ) {
	if( shortcode.length > 0 ){
	    return(
			<div>
				 {shortcode}
			</div>
	    )
	}else{
		return(
			''
	    )
	}
}
function Qcpd_ichart_Shortcode_Preview_edit( { shortcode } ) {
	if( shortcode.length > 0 ){
	    return(

			<textarea className="editor-plain-text input-control">
				{shortcode}
			</textarea>
	    )
	}else{
		return(
			''
	    )
	}
}

registerBlockType( 'cgb/block-ichart-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'iChart - Shortcode Maker' ), // Block title.
	icon: 'chart-line', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'iChart - Shortcode Maker' ),
		__( 'iChart - Shortcode Maker' ),

	],
	attributes: {
        shortcode: {
            type: 'string',
            default: 0
        }
    },

	edit: function( props ) {
		const { attributes: { shortcode }, setAttributes } = props;
 		jQuery(document).on('click', '#ichart_shortcode_generator_meta_block', function(event){			
			event.stopPropagation();
			jQuery('#ichart-qcld-chart-field-modal').show();
			
		})

		
		jQuery(document).on('click','.ichart_copy_close', function(e){
			const shortdata = jQuery(this).attr('short-data');
			setAttributes( { shortcode: shortdata } );
			console.log(shortcode);
		})
		
        return (
            <div className={ props.className }>
                <ServerSideRender
					block="qcpd-ichart/render-shortcode-button"
				/>
            	<textarea className="editor-plain-text input-control">
				{shortcode}
				</textarea>
            </div>
        );
	},


	save: function( props ) {
		const { attributes: { shortcode } } = props;
        return (
            <div>
            	<Qcpd_ichart_Shortcode_Preview shortcode = { shortcode } />
            </div>
        );
	},
} );
