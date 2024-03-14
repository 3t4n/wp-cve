import { colord, extend } from "colord";
import namesPlugin from "colord/plugins/names";
import gradientParser from "gradient-parser";
import {
	DEFAULT_GRADIENT,
	DIRECTIONAL_ORIENTATION_ANGLE_MAP,
	HORIZONTAL_GRADIENT_ORIENTATION,
	MINIMUM_DISTANCE_BETWEEN_POINTS,
} from "./constants";
import { serializeGradient } from "./serializer";
import type { ControlPoint } from "./types";

extend([namesPlugin]);

export function getLinearGradientRepresentation(
	gradientAST: gradientParser.GradientNode
) {
	return serializeGradient({
		type: "linear-gradient",
		orientation: HORIZONTAL_GRADIENT_ORIENTATION,
		colorStops: gradientAST.colorStops,
	});
}

function hasUnsupportedLength(item: gradientParser.ColorStop) {
	return item.length === undefined || item.length.type !== "%";
}

export function getGradientAstWithDefault(value?: string | null) {
	let gradientAST: gradientParser.GradientNode | undefined;
	let hasGradient = !!value;

	const valueToParse = value ?? DEFAULT_GRADIENT;

	try {
		gradientAST = gradientParser.parse(valueToParse)[0];
	} catch (error) {
		gradientAST = gradientParser.parse(DEFAULT_GRADIENT)[0];
		hasGradient = false;
	}

	if (
		!Array.isArray(gradientAST.orientation) &&
		gradientAST.orientation?.type === "directional"
	) {
		gradientAST.orientation = {
			type: "angular",
			value: DIRECTIONAL_ORIENTATION_ANGLE_MAP[
				gradientAST.orientation.value
			].toString(),
		};
	}

	if (gradientAST.colorStops.some(hasUnsupportedLength)) {
		const { colorStops } = gradientAST;
		const step = 100 / (colorStops.length - 1);
		colorStops.forEach((stop, index) => {
			stop.length = {
				value: `${step * index}`,
				type: "%",
			};
		});
	}

	return { gradientAST, hasGradient };
}

export function getGradientAstWithControlPoints(
	gradientAST: gradientParser.GradientNode,
	newControlPoints: ControlPoint[]
) {
	return {
		...gradientAST,
		colorStops: newControlPoints.map(({ position, color }) => {
			const { r, g, b, a } = colord(color).toRgb();
			return {
				length: {
					type: "%",
					value: position?.toString(),
				},
				type: a < 1 ? "rgba" : "rgb",
				value:
					a < 1
						? [`${r}`, `${g}`, `${b}`, `${a}`]
						: [`${r}`, `${g}`, `${b}`],
			};
		}),
	} as gradientParser.GradientNode;
}

export function getStopCssColor(colorStop: gradientParser.ColorStop) {
	switch (colorStop.type) {
		case "hex":
			return `#${colorStop.value}`;
		case "literal":
			return colorStop.value;
		case "rgb":
		case "rgba":
			return `${colorStop.type}(${colorStop.value.join(",")})`;
		default:
			return "transparent";
	}
}

export function clampPercent(value: number) {
	return Math.max(0, Math.min(100, value));
}

export function isOverlapping(
	value: ControlPoint[],
	initialIndex: number,
	newPosition: number,
	minDistance: number = MINIMUM_DISTANCE_BETWEEN_POINTS
) {
	const initialPosition = value[initialIndex].position;
	const minPosition = Math.min(initialPosition, newPosition);
	const maxPosition = Math.max(initialPosition, newPosition);

	return value.some(({ position }, index) => {
		return (
			index !== initialIndex &&
			(Math.abs(position - newPosition) < minDistance ||
				(minPosition < position && position < maxPosition))
		);
	});
}

export function addControlPoint(
	points: ControlPoint[],
	position: number,
	color: ControlPoint["color"]
) {
	const nextIndex = points.findIndex((point) => point.position > position);
	const newPoint = { color, position };
	const newPoints = points.slice();
	newPoints.splice(nextIndex - 1, 0, newPoint);
	return newPoints;
}

export function removeControlPoint(points: ControlPoint[], index: number) {
	return points.filter((_point, pointIndex) => {
		return pointIndex !== index;
	});
}

export function updateControlPoint(
	points: ControlPoint[],
	index: number,
	newPoint: ControlPoint
) {
	const newValue = points.slice();
	newValue[index] = newPoint;
	return newValue;
}

export function updateControlPointPosition(
	points: ControlPoint[],
	index: number,
	newPosition: ControlPoint["position"]
) {
	if (isOverlapping(points, index, newPosition)) {
		return points;
	}
	const newPoint = {
		...points[index],
		position: newPosition,
	};
	return updateControlPoint(points, index, newPoint);
}

export function updateControlPointColor(
	points: ControlPoint[],
	index: number,
	newColor: ControlPoint["color"]
) {
	const newPoint = {
		...points[index],
		color: newColor,
	};
	return updateControlPoint(points, index, newPoint);
}

export function updateControlPointColorByPosition(
	points: ControlPoint[],
	position: ControlPoint["position"],
	newColor: ControlPoint["color"]
) {
	const index = points.findIndex((point) => point.position === position);
	return updateControlPointColor(points, index, newColor);
}

export function getHorizontalRelativeGradientPosition(
	mouseXcoordinate: number,
	containerElement: HTMLDivElement
): number;
export function getHorizontalRelativeGradientPosition(
	mouseXcoordinate: number,
	containerElement: null
): undefined;
export function getHorizontalRelativeGradientPosition(
	mouseXCoordinate: number,
	containerElement: HTMLDivElement | null
) {
	if (!containerElement) {
		return;
	}
	const { x, width } = containerElement.getBoundingClientRect();
	const absolutePositionValue = mouseXCoordinate - x;
	return Math.round(clampPercent((absolutePositionValue * 100) / width));
}
