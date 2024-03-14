import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

const Edit = props => {
	const blockProps = useBlockProps();
	const { attributes, setAttributes, clientId } = props;
	const { id, name, defaultValue, label, showLabel, required } = attributes;

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
					placeholder={ __( 'e.g. My Checkbox Field', 'wpzoom-forms' ) }
					onChange={ value => setAttributes( { name: value } ) }
				/>

				<ToggleControl
					label={ __( 'Checked By Default', 'wpzoom-forms' ) }
					checked={ !! defaultValue }
					onChange={ value => setAttributes( { defaultValue: !! value } ) }
				/>

				<ToggleControl
					label={ __( 'Show Label', 'wpzoom-forms' ) }
					checked={ !! showLabel }
					onChange={ value => setAttributes( { showLabel: !! value } ) }
				/>

				{ showLabel && <TextControl
					label={ __( 'Label', 'wpzoom-forms' ) }
					value={ label }
					onChange={ value => setAttributes( { label: value } ) }
				/> }

				<ToggleControl
					label={ __( 'Required', 'wpzoom-forms' ) }
					checked={ !! required }
					onChange={ value => setAttributes( { required: !! value } ) }
				/>
			</PanelBody>
		</InspectorControls>

		<Fragment>
			<input
				type="checkbox"
				name={ id }
				id={ id }
				checked={ true == defaultValue }
				onChange={ e => {} }
				required={ !! required }
				{ ...blockProps }
			/>

			{ showLabel && <label htmlFor={ id }>
				<RichText
					tagName="label"
					placeholder={ __( 'Label', 'wpzoom-forms' ) }
					value={ label }
					htmlFor={ id }
					onChange={ value => setAttributes( { label: value } ) }
				/>
				{ required && <sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup> }
			</label> }
		</Fragment>
	</>;
};

export default Edit;