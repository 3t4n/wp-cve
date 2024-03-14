const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { RangeControl } = wp.components;

export default function Margin( props ) {
	const {

		// Margin top
		marginTop,
		marginTopLabel,
		marginTopMin,
		marginTopMax,
		marginEnableTop,
		onChangeMarginTop = () => {},

		// Margin right
		marginRight,
		marginRightLabel,
		marginRightMin,
		marginRightMax,
		marginEnableRight,
		onChangeMarginRight = () => {},

		// Margin bottom
		marginBottom,
		marginBottomLabel,
		marginBottomMin,
		marginBottomMax,
		marginEnableBottom,
		onChangeMarginBottom = () => {},

		// Margin left
		marginLeft,
		marginLeftLabel,
		marginLeftMin,
		marginLeftMax,
		marginEnableLeft,
		onChangeMarginLeft = () => {},

		// Margin vertical
		marginVertical,
		marginVerticalLabel,
		marginEnableVertical,
		marginVerticalMin,
		marginVerticalMax,
		onChangeMarginVertical = () => {},

		// Margin horizontal
		marginHorizontal,
		marginHorizontalLabel,
		marginEnableHorizontal,
		marginHorizontalMin,
		marginHorizontalMax,
		onChangeMarginHorizontal = () => {}
	} = props;

	return (
		<Fragment>
			{ marginEnableTop && (
				<RangeControl
					label={ marginTopLabel ? marginTopLabel : __( 'Margin Top', 'socialproofslider' ) }
					value={ marginTop }
					min={ marginTopMin }
					max={ marginTopMax }
					onChange={ onChangeMarginTop }
				/>
			) }
			{ marginEnableRight && (
				<RangeControl
					label={ marginRightLabel ? marginRightLabel : __( 'Margin Right', 'socialproofslider' ) }
					value={ marginRight }
					min={ marginRightMin }
					max={ marginRightMax }
					onChange={ onChangeMarginRight }
				/>
			) }
			{ marginEnableBottom && (
				<RangeControl
					label={ marginBottomLabel ? marginBottomLabel : __( 'Margin Bottom', 'socialproofslider' ) }
					value={ marginBottom }
					min={ marginBottomMin }
					max={ marginBottomMax }
					onChange={ onChangeMarginBottom }
				/>
			) }
			{ marginEnableLeft && (
				<RangeControl
					label={ marginLeftLabel ? marginLeftLabel : __( 'Margin Left', 'socialproofslider' ) }
					value={ marginLeft }
					min={ marginLeftMin }
					max={ marginLeftMax }
					onChange={ onChangeMarginLeft }
				/>
			) }
			{ marginEnableVertical && (
				<RangeControl
					label={ marginVerticalLabel ? marginVerticalLabel : __( 'Margin Vertical', 'socialproofslider' ) }
					value={ marginVertical }
					min={ marginVerticalMin }
					max={ marginVerticalMax }
					onChange={ onChangeMarginVertical }
				/>
			) }
			{ marginEnableHorizontal && (
				<RangeControl
					label={ marginHorizontalLabel ? marginHorizontalLabel : __( 'Margin Horizontal', 'socialproofslider' ) }
					value={ marginHorizontal }
					min={ marginHorizontalMin }
					max={ marginHorizontalMax }
					onChange={ onChangeMarginHorizontal }
				/>
			) }
		</Fragment>
	);
}
