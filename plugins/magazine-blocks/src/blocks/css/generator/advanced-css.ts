import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const advancedCSS = (props: GeneratorArgs) => {
	const { settingValue, settingDef, blockName, blockID, context } = props;
	const styles = settingDef?.style;
	const css: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};

	if (!styles) {
		return css;
	}

	for (const style of styles) {
		if (!meetsConditions(props.settings as any, style)) {
			return;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		if (settingValue && "save" === context) {
			css.allDevice.push(selector);
		}
	}

	return css;
};

export default advancedCSS;
