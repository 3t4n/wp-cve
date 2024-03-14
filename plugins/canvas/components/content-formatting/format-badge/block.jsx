/**
 * Internal dependencies
 */
import {
	replaceClass,
	getActiveClass,
} from '../../../gutenberg/utils/classes-replacer';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	RichTextToolbarButton,
} = wp.blockEditor;

const {
	RadioControl,
} = wp.components;

const {
	registerFormatType,
} = wp.richText;

const { URLPopover } = wp.blockEditor;

function getSelectedBadge() {
	const selection = window.getSelection();

	// Unlikely, but in the case there is no selection, return empty styles so
	// as to avoid a thrown error by `Selection#getRangeAt` on invalid index.
	if ( selection.rangeCount === 0 ) {
		return false;
	}

	const range = selection.getRangeAt( 0 );

	let $selectedNode = range.startContainer;

	// If the caret is right before the element, select the next element.
	$selectedNode = $selectedNode.nextElementSibling || $selectedNode;

	while ( $selectedNode.nodeType !== window.Node.ELEMENT_NODE ) {
		$selectedNode = $selectedNode.parentNode;
	}

	const $badge = $selectedNode.closest( '.cnvs-badge' );

	return $badge;
}

/**
 * Returns a style object for applying as `position: absolute` for an element
 * relative to the bottom-center of the current selection. Includes `top` and
 * `left` style properties.
 *
 * @return {Object} Style object.
 */
function getCurrentCaretPositionStyle() {
	const $badge = getSelectedBadge();

	if ( ! $badge ) {
		return {};
	}

	return $badge.getBoundingClientRect();
}

/**
 * Component which renders itself positioned under the current caret selection.
 * The position is calculated at the time of the component being mounted, so it
 * should only be mounted after the desired selection has been made.
 *
 * @type {WPComponent}
 */
class BadgePopover extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			rect: getCurrentCaretPositionStyle(),
		};
	}

	render() {
		const { children } = this.props;
		const { rect } = this.state;

		return (
			<URLPopover
				focusOnMount={ false }
				anchorRect={ rect }
			>
				<div style={ { padding: 20 } }>{ children }</div>
			</URLPopover>
		);
	}
}

export const name = 'canvas/badge';

export const settings = {
	title: __( 'Badge' ),
	tagName: 'span',
	className: 'cnvs-badge',
	attributes: {
		class: 'class',
	},
	edit: class BadgeFormat extends Component {
		constructor() {
			super( ...arguments );

			this.state = {
				currentColor: '',
			};

			this.toggleFormat = this.toggleFormat.bind( this );
			this.getColorStyle = this.getColorStyle.bind( this );
			this.updateColorStyle = this.updateColorStyle.bind( this );
		}

		componentDidUpdate() {
			const {
				isActive,
			} = this.props;

			if ( ! this.state.currentColor && isActive ) {
				const $badge = getSelectedBadge();

				if ( $badge ) {
					let currentColor = this.getColorStyle( $badge.className );

					if ( currentColor ) {
						currentColor = currentColor.replace( /^is-cnvs-badge-color-/, '' );
						this.setState( { currentColor } );
					}
				}
			} else if ( this.state.currentColor && ! isActive ) {
				this.setState( { currentColor: '' } );
			}
		}

		toggleFormat( color, toggle = true ) {
			const {
				value,
				onChange,
			} = this.props;

			const attributes = {};

			if ( color ) {
				attributes.class = `is-cnvs-badge-color-${ color }`;

				this.setState( { currentColor: color } );
			}

			const toggleFormat = toggle ? wp.richText.toggleFormat : wp.richText.applyFormat;

			onChange( toggleFormat(
				value,
				{
					type: name,
					attributes: attributes,
				}
			) );
		}

		/**
		 * Get color style from classname on the block.
		 *
		 * @return {String}
		 */
		getColorStyle( className ) {
			return getActiveClass( className, 'is-cnvs-badge-color' );
		}

		/**
		 * Update color classname on the block.
		 *
		 * @param {String} colorName name of color style
		 * @memberof NewEdit
		 */
		updateColorStyle( colorName ) {
			const {
				attributes,
				onChangeClassName,
			} = this.props;

			const updatedClassName = replaceClass( attributes.className, 'is-cnvs-badge-color', colorName );

			onChangeClassName( updatedClassName );
		}

		render() {
			const {
				value,
				isActive,
			} = this.props;

			return (
				<Fragment>
					<RichTextToolbarButton
						icon="tag"
						title={ __( 'Badge' ) }
						onClick={ () => {
							this.toggleFormat();
						} }
						isActive={ isActive }
					/>
					{ isActive ? (
						<BadgePopover
							value={ value }
							name={ name }
						>
							<RadioControl
								label={ __( 'Color' ) }
								selected={ this.state.currentColor }
								options={ [
									{
										label: __( 'Default' ),
										value: 'default'
									}, {
										label: __( 'Primary' ),
										value: 'primary'
									}, {
										label: __( 'Secondary' ),
										value: 'secondary'
									}, {
										label: __( 'Success' ),
										value: 'success'
									}, {
										label: __( 'Info' ),
										value: 'info'
									}, {
										label: __( 'Warning' ),
										value: 'warning'
									}, {
										label: __( 'Danger' ),
										value: 'danger'
									}, {
										label: __( 'Light' ),
										value: 'light'
									}, {
										label: __( 'Dark' ),
										value: 'dark'
									},
								] }
								onChange={ ( color ) => {
									this.toggleFormat( color, false );
								} }
							/>
						</BadgePopover>
					) : '' }
				</Fragment>
			);
		}
	},
};

registerFormatType( name, settings );
