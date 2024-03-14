import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl } from '@wordpress/components';

const Edit = props => {
	const blockProps = useBlockProps();
	const { attributes, setAttributes, clientId } = props;
	const { id, name } = attributes;

	useEffect( () => {
		if ( ! id ) {
			setAttributes( { id: 'input_' + clientId.substr( 0, 8 ) } );
		}
	}, [] );

	return <>
		<InspectorControls>
			<PanelBody title={ __( 'Options', 'wpzoom-forms' ) }>
				<TextControl
					label={ __( 'Name', 'wpzoom-forms' ) }
					value={ name }
					onChange={ value => setAttributes( { name: value } ) }
				/>
			</PanelBody>
		</InspectorControls>

		<Fragment>
			<input
				type="submit"
				id={ id }
				value={ name }
				{ ...blockProps }
			/>
		</Fragment>
	</>;
};

export default Edit;