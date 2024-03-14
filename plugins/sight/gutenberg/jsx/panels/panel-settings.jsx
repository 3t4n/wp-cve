/**
 * Components dependencies
 */
import PostsSelectorControl from '../components/posts-selector-control';
import GalleryControl from '../components/gallery-control';

/**
 * Internal dependencies
 */
import './style-panel-settings.scss';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	addFilter,
} = wp.hooks;

const {
	ToggleControl,
	SelectControl,
	RangeControl,
} = wp.components;

/**
 * Add fields to Block Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setBlockSettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	return (
		<div>
			{ ( isFieldVisible('source', config, attributes) ) ? (
				<SelectControl
					label={__("Source")}
					value={attributes['source']}
					options={
						[
							{ value: 'projects', label: __('Projects') },
							{ value: 'custom', label: __('Images') },
							{ value: 'categories', label: __('Categories') },
							{ value: 'post', label: __('Post Attachments') },
						]
					}
					onChange={function (val) {
						setAttributes({ 'source': val });
					}}
				/>
			) : ( null ) }


			{/* Type Projects */}

			{ ( isFieldVisible('video', config, attributes) ) ? (
				<SelectControl
					label={__("Video Background")}
					value={attributes['video']}
					options={
						[
							{ value: 'none', label: __('None') },
							{ value: 'always', label: __('Always') },
							{ value: 'hover', label: __('On Hover') },
						]
					}
					onChange={function (val) {
						setAttributes({ 'video': val });
					}}
				/>
			) : ( null ) }

			{ ( isFieldVisible('video_controls', config, attributes) ) ? (
				<ToggleControl
					label={__("Enable video controls")}
					checked={attributes['video_controls']}
					onChange={function (val) {
						setAttributes({ 'video_controls': val });
					}}
				/>
			) : ( null ) }

			{/* Post */}

			{ ( isFieldVisible('custom_post', config, attributes) ) ? (
				<PostsSelectorControl
					label={__("Post")}
					isMulti={true}
					value={attributes['custom_post']}
					onChange={function (val) {
						setAttributes({ 'custom_post': val });
					}}
				/>
			) : ( null ) }


			{/* Type Custom */}

			{ ( isFieldVisible('custom_images', config, attributes) ) ? (
				<GalleryControl
					label={__("Images")}
					val={attributes['custom_images']}
					onChange={function (val) {
						setAttributes({ 'custom_images': val });
					}}
				/>
			) : ( null ) }

			{/* Common end */}

			{ ( isFieldVisible('number_items', config, attributes) ) ? (
				<RangeControl
					label={__("Number of Items")}
					value={attributes['number_items']}
					min={1}
					max={100}
					onChange={function (val) {
						setAttributes({ 'number_items': val });
					}}
				/>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.blockSettings.fields', 'sight/blockSettings/set/fields', setBlockSettings, 10);
