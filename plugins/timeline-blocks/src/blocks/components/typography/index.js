/**
 * WordPress dependencies
 */
const { __ } = wp.i18n

/**
 * Internal dependencies
 */
import FontFamilyControl from "./font-typography"
import TypographyStyles from "./inline-styles"
import "./editor.scss"

const {
	Button,
	Dashicon
} = wp.components


// Extend component
const { Component, Fragment } = wp.element

// Export for ease of importing in individual blocks.
export {
	TypographyStyles,
}

class TypographyControl extends Component {

	constructor() {
		super( ...arguments )
		this.onAdvancedControlClick  = this.onAdvancedControlClick.bind( this )
		this.onAdvancedControlReset  = this.onAdvancedControlReset.bind( this )
	}

	onAdvancedControlClick() {

		let control = true
		let label = __( "Hide Advanced" )

		if( this.state !== null && this.state.showAdvancedControls === true ) {
			control = false
			label = __( "Advanced" )
		}

		this.setState(
			{
				showAdvancedControls: control,
				showAdvancedControlsLabel: label
			}
		)
	}

	onAdvancedControlReset() {

		const { setAttributes } = this.props

		// Reset Font family to default.
		setAttributes( { [ this.props.fontFamily.label ]: "" } )
		setAttributes( { [ this.props.fontWeight.label ]: "" } )
		setAttributes( { [ this.props.fontSubset.label ]: "" } )

	}

	render() {

		let fontSize
		let fontWeight
		let fontFamily
		let fontAdvancedControls
		let showAdvancedFontControls
		let resetFontAdvancedControls

		const {
			disableFontFamily,
			disableFontSize,
			disableLineHeight,
			disableAdvancedOptions = false
		} = this.props

		if( true !== disableFontFamily ) {
			fontFamily = (
				<FontFamilyControl
					{ ...this.props }
				/>
			)
		}

		if( true !== disableFontFamily && true !== disableFontSize ) {
			fontAdvancedControls =  (
				<Button
					className="tb-size-btn tb-typography-control-btn"
					isSmall
					aria-pressed={ ( this.state !== null ) }
					onClick={ this.onAdvancedControlClick }
				><Dashicon icon="admin-tools" /></Button>
			)

			resetFontAdvancedControls =  (
				<Button
					className="tb-size-btn tb-typography-reset-btn"
					isSmall
					aria-pressed={ ( this.state !== null ) }
					onClick={ this.onAdvancedControlReset }
				><Dashicon icon="image-rotate" /></Button>
			)
		} else {
			showAdvancedFontControls = (
				<Fragment>
					{ fontFamily }
					{ fontWeight }
				</Fragment>
			)
		}


		if( this.state !== null && this.state.showAdvancedControls === true ) {

			showAdvancedFontControls = (
				<div className="tb-advanced-typography">
					{ fontFamily }
					{ fontWeight }
				</div>
			)
		}

		return (
			<div className="tb-typography-options">
				{ fontSize }
				{ !disableAdvancedOptions &&
					<Fragment>
						<div className="tb-typography-option-actions">
							<strong>{ __( "Advanced Typography" ) }</strong>
							{ fontAdvancedControls }
							{ resetFontAdvancedControls }
						</div>
						{ showAdvancedFontControls }
					</Fragment>
				}
			</div>
		)
	}
}

export default TypographyControl
