import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const boxShadowCSS = (props: GeneratorArgs) => {
	let { settingValue, settingDef, blockName, blockID } = props;
	const styles = settingDef?.style;
	const css: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};
	const {
		enable = false,
		position = "outline",
		horizontalX = 0,
		verticalY = 0,
		blur = 10,
		spread = 0,
		color = "rgba(0,0,0, 0.5)",
	} = settingValue;

	if (!styles || !enable) {
		return css;
	}

	for (const style of styles) {
		if (!meetsConditions(props.settings, style)) {
			continue;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		css.allDevice.push(
			selector +
				"{ box-shadow:" +
				(position && "inset" === position ? position : "") +
				" " +
				horizontalX +
				"px " +
				verticalY +
				"px " +
				blur +
				"px " +
				spread +
				"px " +
				color +
				"; }"
		);
	}

	return css;
};

export default boxShadowCSS;
