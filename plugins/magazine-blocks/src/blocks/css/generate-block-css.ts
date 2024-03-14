import { localized } from "@blocks/utils";
import { getBlockType } from "@wordpress/blocks";
import generator from "./generator";
import { ResponsiveCSS } from "./types";

const getSettingStyleGenerator = (settingName: string, settingValue: any) => {
	if (settingValue?.border) {
		return generator.border;
	} else if (settingValue?.background) {
		return generator.background;
	} else if (settingValue?.typography) {
		return generator.typography;
	} else if (settingValue?.boxShadow) {
		return generator.boxShadow;
	} else if (settingValue?.dimension) {
		return generator.dimension;
	} else if (settingValue?.topSeparator || settingValue?.bottomSeparator) {
		return generator.separator;
	} else if (settingValue?.positionProperty) {
		return generator.position;
	} else if (
		[
			"hideOnDesktop",
			"hideOnTablet",
			"hideOnMobile",
			"colReverseOnTablet",
			"colReverseOnMobile",
		].includes(settingName)
	) {
		return generator.advanced;
	}

	return generator.common;
};

type GenerateBlockCSSType = {
	settings: {
		[settingName: string]: any;
	};
	blockName: string;
	blockID: string;
	deviceType?: "desktop" | "tablet" | "mobile";
	context?: "editor" | "save";
};

export const generateBlockCSS = ({
	settings,
	blockName,
	blockID,
	deviceType = "desktop",
	context = "editor",
}: GenerateBlockCSSType): string => {
	const cssForDevices: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};
	const attributesDef = getBlockType(
		"magazine-blocks/" +
			("button" === blockName
				? "button-inner"
				: "buttons" === blockName
				? "button"
				: blockName)
	)?.attributes;

	if (!attributesDef) return "";

	const defaultSettings = Object.entries(attributesDef).reduce(
		(acc, [key, value]) => {
			if ((value as any)?.default) {
				acc[key] = (value as any).default;
			}
			return acc;
		},
		{}
	);

	for (const [settingName, settingValue] of Object.entries(settings)) {
		const attributeDef = attributesDef[settingName];
		const settingStyle = (attributeDef as any)?.style;

		if (!settingStyle) {
			continue;
		}

		const settingStyleGenerator = getSettingStyleGenerator(
			settingName,
			settingValue
		);

		if (!settingStyleGenerator) {
			continue;
		}

		const settingCSSForDevices = settingStyleGenerator({
			blockID,
			settingDef: attributeDef as any,
			settingName,
			settingValue,
			settings: { ...defaultSettings, ...settings },
			blockName,
			context,
		});

		if (!settingCSSForDevices) {
			continue;
		}

		cssForDevices.allDevice = cssForDevices.allDevice.concat(
			settingCSSForDevices.allDevice
		);
		cssForDevices.desktop = cssForDevices.desktop.concat(
			settingCSSForDevices.desktop
		);
		cssForDevices.tablet = cssForDevices.tablet.concat(
			settingCSSForDevices.tablet
		);
		cssForDevices.mobile = cssForDevices.mobile.concat(
			settingCSSForDevices.mobile
		);
	}

	if (cssForDevices.allDevice.length > 0) {
		cssForDevices.allDevice.forEach((str, index) => {
			if (str.includes("@import")) {
				cssForDevices.allDevice.splice(index, 1);

				if ("editor" === context) {
					cssForDevices.allDevice.unshift(str);
				}
			}
		});
	}

	let css = cssForDevices.allDevice.join("");
	css += cssForDevices.desktop.join("");
	if (context === "editor") {
		if (!localized.isNotPostEditor) {
			if ("desktop" !== deviceType) {
				css += cssForDevices.tablet.join("");
			}
			if ("mobile" === deviceType) {
				css += cssForDevices.mobile.join("");
			}
		}
		return css;
	}

	if (cssForDevices.tablet.length) {
		css += `@media (max-width: ${"62em"}) {${cssForDevices.mobile.join(
			""
		)}}`;
	}
	if (cssForDevices.mobile.length) {
		css += `@media (max-width: ${"48em"}) {${cssForDevices.tablet.join(
			""
		)}}`;
	}

	return css;
};
