/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { ToolbarButton, ToolbarGroup, Popover } from '@wordpress/components';
import { Icon, link } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import LinkControls from './components/LinkControls';

/**
 * External dependencies
 */
// import classnames from 'classnames';

/**
 * Custom Component
 */
export default ({ attributes, setAttributes, siblingsImageId }) => {
	const [isURLPickerOpen, setIsURLPickerOpen] = useState(false);

	return (
		<>
			<ToolbarGroup>
				<ToolbarButton
					name='link'
					icon={<Icon icon={link} />}
					title={__('Link')}
					onClick={() => {
						setIsURLPickerOpen(true);
					}}
				/>
			</ToolbarGroup>

			{/* リンク設定用のポップオーバー */}
			{isURLPickerOpen && (
				<Popover position='bottom center' onClose={() => setIsURLPickerOpen(false)}>
					<div className='block-editor-link-control pb-link-popover'>
						<LinkControls {...{ attributes, setAttributes, siblingsImageId }} />
					</div>
				</Popover>
			)}
		</>
	);
};
