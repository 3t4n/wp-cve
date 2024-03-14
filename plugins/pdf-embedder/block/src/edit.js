import { __ } from '@wordpress/i18n';
import { RawHTML } from '@wordpress/element';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button, TextControl, SelectControl, PanelBody } from '@wordpress/components';

import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( props ) {
	const { attributes, setAttributes, className } = props;
	const blockProps = useBlockProps();

	const onSelectPDF = function( media ) {
		return setAttributes( {
			url: media.url,
			pdfID: Number( media.id ),
		} );
	};

	const whDesc1 = '<p>' + __( 'Change the rendered size of the PDF.', 'pdf-embedder' ) + '</p>';
	const whDesc2 = '<p>' + __( 'Enter <code>max</code> or an integer number of pixels.', 'pdf-embedder' ) + '</p>'

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={__( 'Width & Height', 'pdf-embedder' )} initialOpen={true}>

					<RawHTML>
						{ whDesc1 }
						{ whDesc2 }
					</RawHTML>

					<TextControl
						label={__( 'Width', 'pdf-embedder' )}
						value={attributes.width}
						onChange={( width ) => setAttributes( { width: width } )}
					/>

					<TextControl
						label={__( 'Height', 'pdf-embedder' )}
						value={attributes.height}
						onChange={( height ) => setAttributes( { height: height } )}
					/>
				</PanelBody>

				<PanelBody title={__( 'Toolbar', 'pdf-embedder' )}>
					<SelectControl
						label={ __('Location', 'pdf-embedder') }
						value={ attributes.toolbar }
						options={ [
							{ label: __('Top', 'pdf-embedder'), value: 'top' },
							{ label: __('Bottom', 'pdf-embedder'), value: 'bottom' },
							{ label: __('Both', 'pdf-embedder'), value: 'both' },
							{ label: __('None', 'pdf-embedder'), value: 'none' }
						] }
						onChange={ ( location ) => setAttributes( { toolbar: location } ) }
						__nextHasNoMarginBottom
					/>

					<SelectControl
						label={ __('Visibility', 'pdf-embedder') }
						value={ attributes.toolbarfixed }
						options={ [
							{ label: __('Display on hover', 'pdf-embedder'), value: 'off' },
							{ label: __('Always visible', 'pdf-embedder'), value: 'on' }
						] }
						onChange={ ( visibility ) => setAttributes( { toolbarfixed: visibility } ) }
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>

			<MediaUploadCheck>
				<MediaUpload
					onSelect={ ( media ) => onSelectPDF(media) }
					allowedTypes={ ["application/pdf"] }
					value={ attributes.pdfID }
					render={ ( { open } ) => (
						<Button onClick={ open }>
							{ attributes.url ? 'PDF: ' + attributes.url : __('PDF: Click here to open Media library to select a PDF file to embed.', 'pdf-embedder') }
						</Button>
					) }
				/>
			</MediaUploadCheck>
		</div>
	);
}
