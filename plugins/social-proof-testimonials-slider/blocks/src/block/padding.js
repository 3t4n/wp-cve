const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { RangeControl } = wp.components;

export default function Padding( props ) {
	const {

		// Padding
		padding,
		paddingTitle,
		paddingHelp,
		paddingMin,
		paddingMax,
		paddingEnable,
		onChangePadding = () => {},

		// Padding top
		paddingTop,
		paddingTopMin,
		paddingTopMax,
		paddingEnableTop,
		onChangePaddingTop = () => {},

		// Padding right
		paddingRight,
		paddingRightMin,
		paddingRightMax,
		paddingEnableRight,
		onChangePaddingRight = () => {},

		// Padding bottom
		paddingBottom,
		paddingBottomMin,
		paddingBottomMax,
		paddingEnableBottom,
		onChangePaddingBottom = () => {},

		// Padding left
		paddingLeft,
		paddingLeftMin,
		paddingLeftMax,
		paddingEnableLeft,
		onChangePaddingLeft = () => {},

		// Padding vertical
		paddingVertical,
		paddingEnableVertical,
		paddingVerticalMin,
		paddingVerticalMax,
		onChangePaddingVertical = () => {},

		// Padding horizontal
		paddingHorizontal,
		paddingEnableHorizontal,
		paddingHorizontalMin,
		paddingHorizontalMax,
		onChangePaddingHorizontal = () => {}
	} = props;

	return (
		<Fragment>
			{ paddingEnable && (
				<RangeControl
					label={ paddingTitle ? paddingTitle : __( 'Padding', 'socialproofslider' ) }
					help={ paddingHelp ? paddingHelp : null }
					value={ padding }
					min={ paddingMin }
					max={ paddingMax }
					onChange={ onChangePadding }
				/>
			) }
			{ paddingEnableTop && (
				<RangeControl
					label={ __( 'Padding Top', 'socialproofslider' ) }
					value={ paddingTop }
					min={ paddingTopMin }
					max={ paddingTopMax }
					onChange={ onChangePaddingTop }
				/>
			) }
			{ paddingEnableRight && (
				<RangeControl
					label={ __( 'Padding Right', 'socialproofslider' ) }
					value={ paddingRight }
					min={ paddingRightMin }
					max={ paddingRightMax }
					onChange={ onChangePaddingRight }
				/>
			) }
			{ paddingEnableBottom && (
				<RangeControl
					label={ __( 'Padding Bottom', 'socialproofslider' ) }
					value={ paddingBottom }
					min={ paddingBottomMin }
					max={ paddingBottomMax }
					onChange={ onChangePaddingBottom }
				/>
			) }
			{ paddingEnableLeft && (
				<RangeControl
					label={ __( 'Padding Left', 'socialproofslider' ) }
					value={ paddingLeft }
					min={ paddingLeftMin }
					max={ paddingLeftMax }
					onChange={ onChangePaddingLeft }
				/>
			) }
			{ paddingEnableVertical && (
				<RangeControl
					label={ __( 'Padding Vertical', 'socialproofslider' ) }
					value={ paddingVertical }
					min={ paddingVerticalMin }
					max={ paddingVerticalMax }
					onChange={ onChangePaddingVertical }
				/>
			) }
			{ paddingEnableHorizontal && (
				<RangeControl
					label={ __( 'Padding Horizontal', 'socialproofslider' ) }
					value={ paddingHorizontal }
					min={ paddingHorizontalMin }
					max={ paddingHorizontalMax }
					onChange={ onChangePaddingHorizontal }
				/>
			) }
		</Fragment>
	);
}
