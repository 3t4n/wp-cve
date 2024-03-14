import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save();
	const { id, name, type, placeholder, label, showLabel, required, subject } = attributes;

	return <>
		{ showLabel && <label htmlFor={ id }>
			<RichText.Content
				tagName="label"
				value={ label }
				htmlFor={ id }
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
	</>;
};

export default Save;