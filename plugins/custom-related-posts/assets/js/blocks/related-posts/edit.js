const { __ } = wp.i18n;
const {
    PanelBody,
    TextControl,
    RadioControl,
    Disabled,
} = wp.components;
const { Component, Fragment } = wp.element;

// Backwards compatibility.
let InspectorControls;
if ( wp.hasOwnProperty( 'blockEditor' ) ) {
	InspectorControls = wp.blockEditor.InspectorControls;
} else {
	InspectorControls = wp.editor.InspectorControls;
}

let ServerSideRender;
if ( wp.hasOwnProperty( 'serverSideRender' ) ) {
    ServerSideRender = wp.serverSideRender;
} else {
    ServerSideRender = wp.components.ServerSideRender;
}

import '../../../css/public/output.scss';
import Data from '../data/helpers';

class RelatedPostsEdit extends Component {
    constructor() {
        super( ...arguments );
    }

    render() {
        const { attributes, setAttributes } = this.props;
        const { title, none_text, order_by, order } = attributes;

        const relations = Object.values( this.props.relations.to ).filter(
            (post) => 'publish' === post.status
        );
        const hasRelations = relations.length > 0;

        const sideBar = (
			<InspectorControls>
				<PanelBody title={ __( 'Custom Related Posts Settings' ) }>
                    <TextControl
                        label={ __( 'Title' ) }
                        value={ title }
                        onChange={ ( value ) => setAttributes( { title: value } ) }
                    />
                    <TextControl
                        label={ __( 'None Text' ) }
                        help={ __( 'Leave blank to hide when there are no related posts.' ) }
                        value={ none_text }
                        onChange={ ( value ) => setAttributes( { none_text: value } ) }
                    />
                    <RadioControl
                        label={ __( 'Order By' ) }
                        selected={ order_by }
                        options={ [
                            { label: __( 'Title' ), value: 'title' },
                            { label: __( 'Date' ), value: 'date' },
                            { label: __( 'Custom' ), value: 'custom' },
                            { label: __( 'Random' ), value: 'rand' },
                        ] }
                        onChange={ ( value ) => setAttributes( { order_by: value } ) }
                    />
                    <RadioControl
                        label={ __( 'Order' ) }
                        selected={ order }
                        options={ [
                            { label: __( 'Ascending' ), value: 'ASC' },
                            { label: __( 'Descending' ), value: 'DESC' },
                        ] }
                        onChange={ ( value ) => setAttributes( { order: value } ) }
                    />
				</PanelBody>
			</InspectorControls>
        );
        
        return (
            <Fragment>
                { sideBar }
                {
                    ! hasRelations && ! none_text
                    ?
                    <em>{ __( 'This block will be empty until you add a related post.' ) }</em>
                    :
                    <Disabled>    
                        <ServerSideRender
                            block="custom-related-posts/related-posts"
                            attributes={ {
                                ...attributes,
                                relations,
                            }}
                        />
                    </Disabled>
                }
            </Fragment>
        );
    }
}

export default Data.selectRelationsForCurrentPost( RelatedPostsEdit );