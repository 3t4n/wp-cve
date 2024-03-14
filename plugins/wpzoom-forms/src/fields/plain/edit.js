import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { Fragment, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl, ToggleControl, SelectControl } from '@wordpress/components';

const Edit = props => {
	const blockProps = useBlockProps();
	const { attributes, setAttributes, clientId } = props;
	const { id, name, type, placeholder, label, showLabel, required, subject } = attributes;

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
					placeholder={ __( 'e.g. My Text Field', 'wpzoom-forms' ) }
					onChange={ value => setAttributes( { name: value } ) }
				/>

				<SelectControl
					label={ __( 'Type', 'wpzoom-forms' ) }
					value={ type }
					options={ [
						{
							label: __( 'Text', 'wpzoom-forms' ),
							value: 'text'
						},
						{
							label: __( 'Number', 'wpzoom-forms' ),
							value: 'number'
						}
					] }
					onChange={ value => setAttributes( { type: value } ) }
				/>

				<TextControl
					label={ __( 'Placeholder', 'wpzoom-forms' ) }
					value={ placeholder }
					onChange={ value => setAttributes( { placeholder: value } ) }
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

				<ToggleControl
					label={ __( 'Is Subject', 'wpzoom-forms' ) }
					help={ __( 'Whether this text field should be used as the subject field in the form (useful for contact forms).', 'wpzoom-forms' ) }
					checked={ !! subject }
					onChange={ value => setAttributes( { subject: !! value } ) }
				/>
			</PanelBody>
		</InspectorControls>

		<Fragment>
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

			<input
				type={ type }
				name={ id }
				id={ id }
				placeholder={ placeholder }
				required={ !! required }
				data-subject={ !! subject }
				{ ...blockProps }
			/>
		</Fragment>
	</>;
};

export default Edit;