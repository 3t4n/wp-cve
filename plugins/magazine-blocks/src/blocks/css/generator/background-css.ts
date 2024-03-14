import { DEVICES } from "../../constants";
import { GeneratorArgs, ResponsiveCSS } from "../types";
import { meetsConditions, replacePlaceholders } from "../utils";

const backgroundImageCSS = (data: any) => {
	const cssForDevices: ResponsiveCSS = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};

	if (data?.image?.url) {
		cssForDevices.desktop.push(
			"background-image: url(" + data.image.url + ");"
		);
	}

	if (data?.attachment && "default" !== data.attachment) {
		cssForDevices.desktop.push(
			"background-attachment: " + data.attachment + ";"
		);
	}

	Object.keys(DEVICES).forEach((device) => {
		const customSizeKey =
			"customSize" + (device.charAt(0).toUpperCase() + device.slice(1));

		if (
			data?.position &&
			data.position?.[device] &&
			"default" !== data.position[device]
		) {
			cssForDevices[device].push(
				`background-position: ${data.position[device]};`
			);
		}

		if (
			data?.repeat &&
			data?.repeat?.[device] &&
			"default" !== data.repeat[device]
		) {
			cssForDevices[device].push(
				`background-repeat: ${data.repeat[device]};`
			);
		}

		if (
			data?.size &&
			data.size?.[device] &&
			"default" !== data.size[device]
		) {
			if ("custom" !== data.size[device]) {
				cssForDevices[device].push(
					`background-size: ${data.size[device]};`
				);
			}

			if ("custom" === data.size[device] && data[customSizeKey]) {
				cssForDevices[device].push(
					`background-size: ${
						data[customSizeKey].value +
						(data[customSizeKey].unit || "")
					} auto;`
				);
			}
		}
	});

	return cssForDevices;
};

const backgroundCSS = (props: GeneratorArgs) => {
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

	const { type = "color", image, color } = settingValue;

	for (const style of styles) {
		if (!meetsConditions(props.settings, style)) {
			continue;
		}

		const selector = replacePlaceholders(style.selector, {
			WRAPPER: `.mzb-${blockName}-${blockID}`,
		});

		if (color) {
			css.allDevice.push(`${selector}{ background-color: ${color}; }`);
		}

		if ("image" === type) {
			const imageData = backgroundImageCSS(image ?? {});
			Object.keys(css).forEach((type) => {
				if (imageData[type].length > 0) {
					css[type].push(
						`${selector}{ ${imageData[type].join("")} }`
					);
				}
			});
		}
	}

	return css;
};

export default backgroundCSS;
