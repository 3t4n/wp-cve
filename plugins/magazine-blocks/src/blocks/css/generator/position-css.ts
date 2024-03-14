import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const positionCSS = (props: GeneratorArgs) => {
	const { settingValue, settingDef, blockName, blockID } = props;
	const styles = settingDef?.style;

	const css: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};

	const {
		position = "",
		top = 0,
		right = 0,
		bottom = 0,
		left = 0,
	} = settingValue;

	styles.forEach((style) => {
		if (!meetsConditions(props.settings, style)) {
			return;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		if (position) {
			css.allDevice.push(
				`${selector} { position: ${position}px; top: ${top}px; right: ${right}px; bottom: ${bottom}px; left: ${left}px; }`
			);
		}
	});

	return css;
};

export default positionCSS;
