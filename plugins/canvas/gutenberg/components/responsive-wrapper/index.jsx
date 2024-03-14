/**
 * WordPress dependencies
 */
const {
    Component,
    Fragment,
} = wp.element;

const {
    compose,
} = wp.compose;

const {
    withSelect,
    withDispatch,
} = wp.data;

import ComponentResponsiveDropdown from '../responsive-dropdown';

/**
 * Component
 */
class ComponentResponsiveWrapper extends Component {
	constructor() {
        super( ...arguments );
    }

    render() {
        const {
            breakpoint,
            children,
		} = this.props;

		const data = {
			responsiveSuffix: '',
			breakpoint,
			ComponentResponsiveDropdown,
		};

		if ( breakpoint && 'desktop' !== breakpoint ) {
			data.responsiveSuffix = '_' + breakpoint;
		}

        return (
            <Fragment key={ `responsive-wrapper-${ breakpoint }` }>
                { children( data ) }
            </Fragment>
        );
    }
}

export default compose( [
    withSelect( ( select ) => {
        const {
            getBreakpoint,
        } = select( 'canvas/breakpoint' );

        return {
            breakpoint: getBreakpoint(),
        };
    } ),
    withDispatch( ( dispatch ) => {
        const {
            updateBreakpoint,
        } = dispatch( 'canvas/breakpoint' );

        return {
            updateBreakpoint,
        };
    } ),
] )( ComponentResponsiveWrapper );
