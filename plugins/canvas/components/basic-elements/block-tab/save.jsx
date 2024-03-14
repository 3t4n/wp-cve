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
export default class TabBlockSave extends Component {
	render() {
        return (
            <div className="cnvs-block-tab">
                <InnerBlocks.Content />
            </div>
        );
	}
}
