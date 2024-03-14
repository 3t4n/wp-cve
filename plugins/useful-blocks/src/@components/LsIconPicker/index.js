/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState, RawHTML } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { chevronDown } from '@wordpress/icons';

/**
 * @Inner dependencies
 */
import LsIcon from '@components/LsIcon';
import LsIconModal from '@components/LsIconModal';

/**
 * TEXT
 */
const TEXTS = {
	search: __('Search', 'useful-blocks'),
	clear: __('Clear', 'useful-blocks'),
};

/**
 * LsIconPicker
 */
export default ({
	value = '',
	position = 'sidebar',
	onChange,
	svg = '',
	onSetSvg,
	clearable = true,
	// help = '',
}) => {
	const [isOpenIconPicker, setIsOpenIconPicker] = useState(false);

	/* eslint no-nested-ternary: 0 */
	return (
		<div className='ls-iconPicker'>
			<Button
				variant='secondary'
				iconPosition='right'
				icon={chevronDown}
				text={
					!!svg ? (
						<RawHTML className='ls-iconPicker__prev'>{svg}</RawHTML>
					) : !!value ? (
						<LsIcon icon={value} size='24px' className='ls-iconPicker__prev' />
					) : (
						<span className='ls-iconPicker__placeholder'>{TEXTS.search}</span>
					)
				}
				onClick={() => {
					setIsOpenIconPicker(true);
				}}
			/>
			{clearable && (
				<Button
					className='-clear'
					isSmall
					text={TEXTS.clear}
					onClick={() => {
						onChange('');
						if (svg && onSetSvg) {
							onSetSvg('');
						}
					}}
				/>
			)}
			{isOpenIconPicker && (
				<LsIconModal
					{...{ value, position, onChange, svg, onSetSvg }}
					onClose={() => {
						setIsOpenIconPicker(false);
					}}
				/>
			)}
		</div>
	);
};
