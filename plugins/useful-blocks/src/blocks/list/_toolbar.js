/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ToolbarGroup } from '@wordpress/components';
import { formatListBullets, formatListNumbered } from '@wordpress/icons';

/**
 * Custom Component
 */
export default ({ listTag, setAttributes }) => {
	return (
		<>
			<ToolbarGroup
				controls={[
					{
						icon: formatListBullets,
						title: __('Convert to unordered list'),
						isActive: listTag === 'ul',
						onClick() {
							setAttributes({ listTag: 'ul' });
							setAttributes({ icon: 'check' });
						},
					},
					{
						icon: formatListNumbered,
						title: __('Convert to ordered list'),
						isActive: listTag === 'ol',
						onClick() {
							setAttributes({ listTag: 'ol' });
							setAttributes({ icon: 'circle' });
						},
					},
				]}
			/>
		</>
	);
};
