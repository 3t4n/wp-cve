import { useBlockProps } from '@wordpress/block-editor';

export default function save(props) {
  const blockProps = useBlockProps.save();

	return (
		<div {...blockProps } class="plz-gutenberg">
			{props.attributes.plezi_form && props.attributes.plezi_form.length > 0 &&
				<div>
					<form formid={ props.attributes.plezi_form } id={`plz-form-${ props.attributes.plezi_form }`}></form>
					<script async src={`https://brain.plezi.co/api/v1/web_forms/scripts?content_web_form_id=${ props.attributes.plezi_form }`}></script>
				</div>
			}
		</div>
	);
}
