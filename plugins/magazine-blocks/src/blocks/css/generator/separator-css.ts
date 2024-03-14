import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const separatorCSS = (props: GeneratorArgs) => {
	const { settingValue, settingDef, blockName, blockID } = props;
	const styles = settingDef?.style;
	const css: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};

	if (!styles || !settingValue.enable) {
		return css;
	}

	const {
		color = "#fff",
		height = 160,
		width = 1,
		shadow_enable,
		shadow_color = "#fff",
		horizontalX = 0,
		verticalY = 0,
		blur = 0,
	} = settingValue;

	styles.forEach((style) => {
		if (!meetsConditions(props.settings, style)) {
			return;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		let _style = "";

		if (color) {
			_style += `fill: ${color};`;
		}
		if (height) {
			_style += `height: ${height}px;`;
		}
		if (width) {
			_style += `transform: scaleX(${width});`;
		}
		if (shadow_enable) {
			_style += `filter: drop-shadow(${horizontalX}px ${verticalY}px ${blur}px ${shadow_color});`;
		}

		if (_style) {
			css.allDevice.push(`${selector} { ${_style} }`);
		}
	});

	return css;
};

export default separatorCSS;
