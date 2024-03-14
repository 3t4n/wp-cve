import { DEVICES } from "../../constants";
import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const borderCSS = (props: GeneratorArgs) => {
	const { settingValue, settingDef, blockName, blockID } = props;
	const styles = settingDef?.style;
	const css: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};
	const tempCSS: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};

	if (!styles) {
		return css;
	}

	const { type = "none", size, radius, color } = settingValue;

	for (const style of styles) {
		if (!meetsConditions(props.settings, style)) {
			continue;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		if ("none" !== type) {
			tempCSS.desktop.push(
				`${
					color ? `border-color: ${color};` : ""
				} border-style: ${type};`
			);

			for (const device in DEVICES) {
				if (size?.[device]) {
					const sizeUnit = size[device]?.unit ?? "px";
					const sides = [
						`${size[device]?.top ?? 0}${sizeUnit}`,
						`${size[device]?.right ?? 0}${sizeUnit}`,
						`${size[device]?.bottom ?? 0}${sizeUnit}`,
						`${size[device]?.left ?? 0}${sizeUnit}`,
					];
					if (sides.every((side) => `0${sizeUnit}` === side))
						continue;
					tempCSS[device].push(`border-width: ${sides.join(" ")};`);
				}
			}
		}

		for (const device in DEVICES) {
			if (radius?.[device]) {
				const radiusUnit = radius[device]?.unit ?? "px";
				const sides = [
					`${radius[device]?.top ?? 0}${radiusUnit}`,
					`${radius[device]?.right ?? 0}${radiusUnit}`,
					`${radius[device]?.bottom ?? 0}${radiusUnit}`,
					`${radius[device]?.left ?? 0}${radiusUnit}`,
				];
				if (sides.every((side) => `0${radiusUnit}` === side)) continue;
				tempCSS[device].push(`border-radius: ${sides.join(" ")};`);
			}
		}

		Object.keys(css).forEach((type) => {
			if (tempCSS?.[type]?.length > 0) {
				css[type].push(`${selector}{ ${tempCSS[type].join("")} }`);
			}
		});
	}

	return css;
};

export default borderCSS;
