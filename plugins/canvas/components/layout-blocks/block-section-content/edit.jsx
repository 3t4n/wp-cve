/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { Component } = wp.element;

const {
    InnerBlocks,
} = wp.blockEditor;

const {
    withSelect,
} = wp.data;

/**
 * Component
 */
class SectionContentBlockEdit extends Component {
	render() {
        const {
            hasChildBlocks,
            attributes,
        } = this.props;

        let {
            className,
        } = this.props;

        className = classnames(
            'cnvs-block-section-content',
            attributes.canvasClassName,
            className
        );

        return (
            <div className={ className }>
                <div className="cnvs-block-section-content-inner">
                    <InnerBlocks
                        templateLock={ false }
                        renderAppender={ (
                            hasChildBlocks ?
                                undefined :
                                () => <InnerBlocks.ButtonBlockAppender />
                        ) }
                    />
                </div>
            </div>
        );
    }
}

const SectionContentBlockEditWithSelect = withSelect( ( select, ownProps ) => {
    const { clientId } = ownProps;
    const blockEditor = select( 'core/block-editor' );

    return {
        hasChildBlocks: blockEditor ? blockEditor.getBlockOrder( clientId ).length > 0 : false,
    };
} )( SectionContentBlockEdit );

export default SectionContentBlockEditWithSelect;
