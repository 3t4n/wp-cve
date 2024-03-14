import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save();
	const { id, name, cols, rows, placeholder, label, showLabel, required } = attributes;

	return <>
		{ showLabel && <label htmlFor={ id }>
			<RichText.Content
				tagName="label"
				value={ label }
				htmlFor={ id }
			/>
			{ required && <sup className="wp-block-wpzoom-forms-required">{ __( '*', 'wpzoom-forms' ) }</sup> }
		</label> }

		<textarea
			name={ id }
			id={ id }
			cols={ cols }
			rows={ rows }
			placeholder={ placeholder }
			required={ !! required }
			{ ...blockProps }
		></textarea>
	</>;
};

export default Save;