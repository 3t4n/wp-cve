/**
 * Since Gutenberg keeps changing component locations,
 * we just manage all the components & classes here
 * and import this within all blocks to make maintenance easier.
 */

export const { registerBlockType, createBlock } = wp.blocks

export const { __ } = wp.i18n

export const {
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
	TabPanel,
	Dashicon,
	IconButton,
	Button,
	Toolbar,
	PanelBody,
	RadioControl,
} = wp.components

export const {
	InspectorControls,
	BlockControls,
	ColorPalette,
	AlignmentToolbar,
	RichText,
	URLInput,
	MediaUpload,
} = wp.editor.InspectorControls ? wp.editor : wp.wp.blockEditor

export const {
	PanelColorSettings,
	BlockAlignmentToolbar,
} = wp.editor

export const {
	Fragment,
	Component
} = wp.element

export const {
	omit,
	merge,
} = lodash

export const {
	doAction,
	addAction,
	applyFilters,
	addFilter,
} = wp.hooks
