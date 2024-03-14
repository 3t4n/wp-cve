import { colord } from "colord";
import { Format } from "colord/types";

export const serializeColor = (
	color: ReturnType<typeof parseColor>,
	format?: Format
) => {
	format = format ?? "hex";
	if (format === "hex") {
		return color.hex;
	} else if (format === "rgb") {
		return colord(color.rgb).toRgbString();
	} else if (format === "hsl") {
		return colord(color.hsl).toHslString();
	}
	return color.hex;
};

export const parseColor = (input: string) => {
	const color = colord(input);
	return {
		hex: color.toHex(),
		rgb: color.toRgb(),
		hsl: color.toHsl(),
	};
};
