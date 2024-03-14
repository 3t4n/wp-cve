/**
 * WordPress dependencies
 */
const {
	Component,
} = wp.element;

const {
	ColorPalette,
} = wp.blockEditor;

/**
 * Component
 */
export default class ComponentColors extends Component {
	constructor() {
		super(...arguments);

		this.updateColors = this.updateColors.bind(this);
	}

	/**
	 * Update colors value.
	 *
	 * @param {String} slug - slug.
	 * @param {String} prefix - type prefix.
	 * @param {String} suffix - responsive suffix.
	 * @param {String} val - new value.
	 */
	updateColors(slug, suffix = '', val) {
		const {
			onChange,
		} = this.props;

		const updateAttrs = {
			[slug + suffix]: val,
		};

		onChange(updateAttrs);
	}

	render() {
		const {
			slug = '',
			val = '',
			suffix = '',
		} = this.props;

		return (
			<ColorPalette
				value={ val || '' }
				onChange={ (val) => this.updateColors(slug, suffix, val) }
			/>
		);
	}
}
