import { DEVICES } from "../../constants";
import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const typographyCSS = (props: GeneratorArgs) => {
	const { settingValue, settingDef, blockName, blockID } = props;
	const styles = settingDef?.style;
	const css: ResponsiveCSS = {
			allDevice: [],
			desktop: [],
			tablet: [],
			mobile: [],
		},
		tempCSS: ResponsiveCSS = {
			allDevice: [],
			desktop: [],
			tablet: [],
			mobile: [],
		};
	let font = "";

	if (!styles) {
		return css;
	}

	const {
		family = "Default",
		weight = 400,
		transform = "default",
		decoration = "default",
		type = "sans-serif",
		size,
		lineHeight,
		letterSpacing,
	} = settingValue;

	for (const style of styles) {
		if (!meetsConditions(props.settings, style)) {
			return;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		if ("Default" !== family) {
			font += `@import url('https://fonts.googleapis.com/css?family=${family.replace(
				" ",
				"+"
			)}:${weight}&display=swap');`;
			tempCSS.desktop.push(`font-family: ${family}, ${type};`);
		}

		if (weight) {
			tempCSS.desktop.push(`font-weight: ${weight};`);
		}

		if ("default" !== transform) {
			tempCSS.desktop.push(`text-transform: ${transform};`);
		}

		if ("default" !== decoration) {
			tempCSS.desktop.push(`text-decoration: ${decoration};`);
		}

		Object.keys(DEVICES).forEach((device) => {
			if (size?.[device]?.value) {
				tempCSS[device].push(
					`font-size: ${size[device].value}${
						size[device]?.unit ?? "px"
					};`
				);
			}

			if (lineHeight?.[device]?.value) {
				tempCSS[device].push(
					`line-height: ${lineHeight[device].value}${
						lineHeight[device]?.unit ?? "px"
					};`
				);
			}

			if (letterSpacing?.[device]?.value) {
				tempCSS[device].push(
					`letter-spacing: ${letterSpacing[device].value}${
						letterSpacing[device]?.unit ?? "px"
					}`
				);
			}
		});

		Object.keys(css).forEach((type) => {
			if (tempCSS[type].length > 0) {
				css[type].push(`${selector}{ ${tempCSS[type].join("")} }`);
			}
		});

		if (font) {
			css.allDevice.unshift(font);
		}
	}
	return css;
};

export default typographyCSS;
