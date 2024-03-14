import { DEVICES } from "../../constants";
import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const dimensionCSS = (props: GeneratorArgs) => {
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

		if (settingValue) {
			if (
				Object.keys(settingValue).some((v) =>
					Object.keys(DEVICES).includes(v)
				)
			) {
				Object.keys(DEVICES).forEach((device) => {
					if (!settingValue[device]) {
						return;
					}

					if (
						!Object.keys(settingValue[device]).some((v) =>
							["top", "right", "bottom", "left", "unit"].includes(
								v
							)
						)
					) {
						return;
					}
					const dimensionUnit = settingValue[device].unit || "px";
					const sides = [
						`${settingValue[device]?.top || 0}${dimensionUnit}`,
						`${settingValue[device]?.right || 0}${dimensionUnit}`,
						`${settingValue[device]?.bottom || 0}${dimensionUnit}`,
						`${settingValue[device]?.left || 0}${dimensionUnit}`,
					];

					css[device].push(
						replacePlaceholders(selector, {
							VALUE: sides.join(" "),
						})
					);
				});
			} else {
				if (
					!Object.keys(settingValue).some((v) =>
						["top", "right", "bottom", "left", "unit"].includes(v)
					)
				) {
					return;
				}
				const dimensionUnit = settingValue.unit || "px";
				const sides = [
					`${settingValue?.top || 0}${dimensionUnit}`,
					`${settingValue?.right || 0}${dimensionUnit}`,
					`${settingValue?.bottom || 0}${dimensionUnit}`,
					`${settingValue?.left || 0}${dimensionUnit}`,
				];

				css.allDevice.push(
					replacePlaceholders(selector, {
						VALUE: sides.join(" "),
					})
				);
			}
		}
	}

	return css;
};

export default dimensionCSS;
