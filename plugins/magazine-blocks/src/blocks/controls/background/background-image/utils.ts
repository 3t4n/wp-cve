import { BACKGROUND_IMAGE_POSITIONS } from "./constants";

export const parseFocalPoint = (position: string) => {
	if (position === "default") {
		position = "center center";
	}

	if (BACKGROUND_IMAGE_POSITIONS[position]) {
		position = BACKGROUND_IMAGE_POSITIONS[position];
	}

	const points = position.replaceAll("%", "").split(" ");

	return {
		x: parseFloat(points[0]) / 100,
		y: parseFloat(points[1]) / 100,
	};
};

export const serializeFocalPoint = (points: { x: number; y: number }) =>
	`${(points.x * 100).toFixed(2)}% ${(points.y * 100).toFixed(2)}%`;

export const serializeSize = ({
	value,
	unit,
}: {
	value: number;
	unit: string;
}) => `${value}${unit}`;

export const parseSize = (size?: string) => {
	if (!size) return undefined;
	const regex = /(\d+(?:\.\d*)?)\s*([a-z%]+)/i;
	const matches = size.match(regex);
	if (matches)
		return {
			value: parseFloat(matches[1]),
			unit: matches[2],
		};
	return undefined;
};
