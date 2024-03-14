import { isUndefined } from "lodash";
import { DEVICES } from "../../constants";
import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const commonCSS = (props: GeneratorArgs) => {
	const { settingValue, settingDef, blockName, blockID } = props;
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
		if (!meetsConditions(props.settings, style)) {
			continue;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		if ("object" === typeof settingValue && settingValue) {
			if (
				["desktop", "tablet", "mobile"].some((d) =>
					Object.keys(settingValue).includes(d)
				)
			) {
				Object.keys(DEVICES).forEach((device) => {
					if (!isUndefined(settingValue[device])) {
						if ("object" === typeof settingValue[device]) {
							if (
								!isUndefined(settingValue[device].value) ||
								"" !== settingValue[device].value
							) {
								css[device].push(
									replacePlaceholders(selector, {
										VALUE:
											settingValue[device].value +
												settingValue[device].unit ||
											"px",
									})
								);
							}
						} else {
							if (
								!isUndefined(settingValue[device]) ||
								"" !== settingValue[device]
							) {
								css[device].push(
									replacePlaceholders(selector, {
										VALUE: settingValue[device],
									})
								);
							}
						}
					}
				});
			} else {
				const { value, unit } = settingValue as any;
				if (!isUndefined(value) || "" !== value) {
					css.allDevice.push(
						replacePlaceholders(selector, {
							VALUE: (value ?? 0) + (unit ?? "px"),
						})
					);
				}
			}
		} else {
			if (!isUndefined(settingValue) || "" !== settingValue) {
				css.allDevice.push(
					replacePlaceholders(selector, {
						VALUE: settingValue as string,
					})
				);
			}
		}
	}

	return css;
};

export default commonCSS;
