/**
 * WordPress dependencies
 */
const { Component } = wp.element;

const {
    InnerBlocks,
} = wp.blockEditor;

/**
 * Component
 */
export default class ColumnBlockSave extends Component {
	render() {
        return <InnerBlocks.Content />;
	}
}
