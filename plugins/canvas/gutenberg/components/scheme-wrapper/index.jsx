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

import ComponentSchemeDropdown from '../scheme-dropdown';

/**
 * Component
 */
class ComponentSchemeWrapper extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		const {
			scheme,
			children,
		} = this.props;

		const data = {
			schemeSuffix: '',
			scheme,
			ComponentSchemeDropdown,
		};

		if (scheme && 'default' !== scheme) {
			data.schemeSuffix = '_' + scheme;
		}

		return (
			<Fragment key={`scheme-wrapper-${scheme}`}>
				{children(data)}
			</Fragment>
		);
	}
}

export default compose([
	withSelect((select) => {
		const {
			getScheme,
		} = select('canvas/scheme');

		return {
			scheme: getScheme(),
		};
	}),
	withDispatch((dispatch) => {
		const {
			updateScheme,
		} = dispatch('canvas/scheme');

		return {
			updateScheme,
		};
	}),
])(ComponentSchemeWrapper);
