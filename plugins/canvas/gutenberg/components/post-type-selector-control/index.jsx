/**
 * WordPress dependencies
 */
const {
    Component,
} = wp.element;

const {
    withSelect,
} = wp.data;

const {
    SelectControl,
} = wp.components;

const {
	applyFilters,
} = wp.hooks;

/**
 * Component
 */
class ComponentPostTypeSelectorControl extends Component {
	constructor() {
        super( ...arguments );
    }

    render() {
        const {
            value,
            label,
            help,
            onChange,
            postTypes,
        } = this.props;

		return (
            <SelectControl
                label={ label }
                help={ help }
                value={ value }
                options={ applyFilters( 'canvas.selector.postTypes', postTypes ) }
                onChange={ ( val ) => {
                    onChange( val );
                } }
            />
		);
    }
}

export default withSelect( ( select, props ) => {
    const {
        getPostTypes,
    } = select( 'core' );

    const {
        value,
    } = props;

    const postTypes = getPostTypes();

	return {
        postTypes: postTypes ? (
            postTypes
                .filter( ( postType ) => {
                    return postType.viewable;
                } )
                .map( ( postType ) => {
                    return {
                        label: postType.name,
                        value: postType.slug,
                    };
                } )
        ) : [
            {
                label: value,
                value: value,
            },
        ],
	};
})( ComponentPostTypeSelectorControl );
