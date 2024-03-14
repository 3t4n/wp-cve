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
export default class CollapsibleBlockSave extends Component {
	render() {
        return <InnerBlocks.Content />;
	}
}
