import {useBlockProps} from '@wordpress/block-editor';

export default function save({ attributes }) {
	const blockProps = useBlockProps.save();
	console.log(blockProps);
	return (
		<div
			className="mobile-ads"
			data-size={attributes.adSize}
			data-width={attributes.width}
			data-height={attributes.height}>
		</div>
	);
}
