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

registerBlockType( 'cgb/block-ichart-free-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Quick iChart' ), // Block title.
	icon: 'chart-line', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Quick iChart' ),
		__( 'Quick iChart' ),

	],
	attributes: {
        shortcode: {
            type: 'string',
            default: ''
        }
    },

	edit: function( props ) {
		const { attributes: { shortcode }, setAttributes } = props;
 		

 		function showShortcodeModal(e){
			 jQuery('#ichart_shortcode_generator_free_meta_block').prop('disabled', true);
			 jQuery(e.target).addClass('currently_editing');
			jQuery('#ichart-qcld-chart-field-modal').show();
		}

		function insertShortCode(e){
			const shortcode = jQuery('#ichart_shortcode_container').val();
			setAttributes( { shortcode: shortcode } );
			//jQuery('#wpwrap').find('#sm-modal').remove();
			console.log({ shortcode });
		}

		jQuery(document).on('click','.ichart_copy_close', function(e){
			e.preventDefault();
			jQuery('.currently_editing').next('#insert_shortcode').trigger('click');
			jQuery(document).find( '.ichart-chart-field-modal-close').trigger('click');
			jQuery('#ichart-qcld-chart-field-modal').hide();
		});

		jQuery(document).on( 'click', '.ichart-chart-field-modal-close', function(){
			jQuery('.currently_editing').removeClass('currently_editing');
			jQuery('#ichart_shortcode_generator_free_meta_block').prop('disabled', false);
			jQuery('#ichart-qcld-chart-field-modal').hide();
		});


		
        return (
            <div className={ props.className }>
                <input type="button" id="ichart_shortcode_generator_free_meta_block" onClick={showShortcodeModal} className="button button-primary button-large" value="Generate iChart Shortcode" />
				<input type="button" id="insert_shortcode" onClick={insertShortCode} className="button button-primary button-large" value="Test iChart Shortcode" />
				<br />
				{ shortcode }
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
