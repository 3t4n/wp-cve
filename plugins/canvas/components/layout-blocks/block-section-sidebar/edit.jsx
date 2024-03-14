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
class SectionSidebarBlockEdit extends Component {
	render() {
        const {
            hasChildBlocks,
            attributes,
        } = this.props;

        let {
            className,
        } = this.props;

        className = classnames(
            'cnvs-block-section-sidebar',
            attributes.canvasClassName,
            className
        );

        return (
            <div className={ className }>
                <div className="cnvs-block-section-sidebar-inner">
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

const SectionSidebarBlockEditWithSelect = withSelect( ( select, ownProps ) => {
    const { clientId } = ownProps;
    const blockEditor = select( 'core/block-editor' );

    return {
        hasChildBlocks: blockEditor ? blockEditor.getBlockOrder( clientId ).length > 0 : false,
    };
} )( SectionSidebarBlockEdit );

export default SectionSidebarBlockEditWithSelect;
