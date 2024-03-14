import { __ } from '@wordpress/i18n';
import {useBlockProps,RichText} from '@wordpress/block-editor';
import { Disabled, ComboboxControl } from '@wordpress/components';

import './style.scss';
import { defaultOptions } from './default-options.js';

export const PickupPointBlockEdit = ( { attributes, setAttributes, metadata } ) => {
	const blockProps = useBlockProps();

	return (
		<div {...blockProps} style={{display: 'block'}}>
			<div>
				<div className="wc-block-components-combobox is-active">
				<Disabled>
					<ComboboxControl
						className={'wc-block-components-combobox-control'}
						label={__('Select pickup point', 'octolize-pickup-point-checkout-blocks')}
						value={''}
						options={defaultOptions}
						allowReset={ false }
					/>
				</Disabled>
				</div>
			</div>
		</div>
	);
};

export const PickupPointBlockSave = ( { attributes } ) => {
	const { text } = attributes;
	return (
		<div { ...useBlockProps.save() }>
		</div>
	);
};
