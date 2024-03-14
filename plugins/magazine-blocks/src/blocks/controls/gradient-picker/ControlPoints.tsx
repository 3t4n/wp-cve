import { ButtonProps, chakra, useId, VisuallyHidden } from "@chakra-ui/react";
import { sprintf, __ } from "@wordpress/i18n";
import { colord } from "colord";
import React, { useEffect, useRef } from "react";
import {
	KEYBOARD_CONTROL_POINT_VARIATION,
	MINIMUM_SIGNIFICANT_MOVE,
} from "./constants";
import type {
	ControlPointButtonProps,
	ControlPointMoveState,
	ControlPointsProps,
	InsertPointProps,
} from "./types";
import {
	addControlPoint,
	clampPercent,
	getHorizontalRelativeGradientPosition,
	removeControlPoint,
	updateControlPointPosition,
} from "./utils";

function ControlPointButton({
	position,
	color,
	...additionalProps
}: ControlPointButtonProps & {
	onClick?: ButtonProps["onClick"];
	onKeyDown?: ButtonProps["onKeyDown"];
	onMouseDown?: ButtonProps["onMouseDown"];
	onDoubleClick?: ButtonProps["onDoubleClick"];
}) {
	const instanceId = useId();
	const descriptionId = `components-custom-gradient-picker__control-point-button-description-${instanceId}`;
	return (
		<chakra.button
			aria-label={sprintf(
				// translators: %1$s: gradient position e.g: 70, %2$s: gradient color code e.g: rgb(52,121,151).
				__(
					"Gradient control point at position %1$s%% with color code %2$s.",
					"magazine-blocks"
				),
				position,
				color
			)}
			aria-describedby={descriptionId}
			borderRadius="50%"
			p="0"
			boxShadow="0 2px 4px rgba(0,0,0,.2)"
			border="2px"
			borderColor="white"
			h="14px"
			w="14px"
			position="absolute"
			display="flex"
			top="-3px"
			zIndex="2"
			transform="translateX(-50%)"
			style={{
				left: `${position}%`,
				backgroundColor: color,
			}}
			{...additionalProps}
		>
			<VisuallyHidden id={descriptionId}>
				{__(
					"Use your left or right arrow keys or drag and drop with the mouse to change the gradient position. Press the button to change the color or remove the control point.",
					"magazine-blocks"
				)}
			</VisuallyHidden>
		</chakra.button>
	);
}

function ControlPoints({
	gradientPickerDomRef,
	ignoreMarkerPosition,
	value: controlPoints,
	onChange,
	onStartControlPointChange,
	onStopControlPointChange,
	setActiveControlPoint,
}: ControlPointsProps) {
	const controlPointMoveState = useRef<ControlPointMoveState>();

	const onMouseMove = (event: MouseEvent) => {
		if (
			controlPointMoveState.current === undefined ||
			gradientPickerDomRef.current === null
		) {
			return;
		}

		const relativePosition = getHorizontalRelativeGradientPosition(
			event.clientX,
			gradientPickerDomRef.current
		);

		const { initialPosition, index, significantMoveHappened } =
			controlPointMoveState.current;

		if (
			!significantMoveHappened &&
			Math.abs(initialPosition - relativePosition) >=
				MINIMUM_SIGNIFICANT_MOVE
		) {
			controlPointMoveState.current.significantMoveHappened = true;
		}

		const newControlPointPosition = updateControlPointPosition(
			controlPoints,
			index,
			relativePosition
		);

		onChange(newControlPointPosition);
		setActiveControlPoint(relativePosition);
	};

	const cleanEventListeners = () => {
		if (
			window &&
			window.removeEventListener &&
			controlPointMoveState.current &&
			controlPointMoveState.current.listenersActivated
		) {
			window.removeEventListener("mousemove", onMouseMove);
			window.removeEventListener("mouseup", cleanEventListeners);
			onStopControlPointChange();
			controlPointMoveState.current.listenersActivated = false;
		}
	};

	const cleanEventListenersRef = useRef<() => void>();
	cleanEventListenersRef.current = cleanEventListeners;

	useEffect(() => {
		return () => {
			cleanEventListenersRef.current?.();
		};
	}, []);

	return (
		<>
			{controlPoints.map((point, index) => {
				const initialPosition: number = point?.position;
				return (
					ignoreMarkerPosition !== initialPosition && (
						<ControlPointButton
							key={index}
							onClick={() => {
								if (
									controlPointMoveState.current &&
									controlPointMoveState.current
										.significantMoveHappened
								) {
									return;
								}
								onStartControlPointChange();
								setActiveControlPoint(initialPosition);
							}}
							onMouseDown={() => {
								if (window && window.addEventListener) {
									controlPointMoveState.current = {
										initialPosition,
										index,
										significantMoveHappened: false,
										listenersActivated: true,
									};
									onStartControlPointChange();
									window.addEventListener(
										"mousemove",
										onMouseMove
									);
									window.addEventListener(
										"mouseup",
										cleanEventListeners
									);
								}
							}}
							onKeyDown={(event) => {
								if (event.code === "ArrowLeft") {
									event.stopPropagation();
									onChange(
										updateControlPointPosition(
											controlPoints,
											index,
											clampPercent(
												point.position -
													KEYBOARD_CONTROL_POINT_VARIATION
											)
										)
									);
								} else if (event.code === "ArrowRight") {
									event.stopPropagation();
									onChange(
										updateControlPointPosition(
											controlPoints,
											index,
											clampPercent(
												point.position +
													KEYBOARD_CONTROL_POINT_VARIATION
											)
										)
									);
								}
							}}
							position={point.position}
							color={point.color}
							onDoubleClick={() => {
								if (
									(controlPointMoveState.current &&
										controlPointMoveState.current
											.significantMoveHappened) ||
									controlPoints.length <= 2
								) {
									return;
								}
								const newPoints = removeControlPoint(
									controlPoints,
									index
								);
								const closest = newPoints.reduce(
									(acc, curr) => {
										return Math.abs(
											curr.position - initialPosition
										) < Math.abs(acc - initialPosition)
											? curr.position
											: acc;
									},
									newPoints[0].position
								);
								onChange(newPoints);
								setActiveControlPoint(closest);
								onStopControlPointChange();
							}}
						/>
					)
				);
			})}
		</>
	);
}

function InsertPoint({
	value: controlPoints,
	onChange,
	insertPosition,
	setActiveControlPoint,
}: InsertPointProps) {
	return (
		<chakra.button
			onClick={() => {
				const newControls = addControlPoint(
					controlPoints,
					insertPosition,
					colord("#000").toRgbString()
				);
				onChange(newControls);
				setActiveControlPoint(insertPosition);
			}}
			style={
				insertPosition !== null
					? {
							left: `${insertPosition}%`,
							transform: "translateX( -50% )",
					  }
					: undefined
			}
			h="14px"
			w="14px"
			position="absolute"
			display="flex"
			minW="14px"
			borderRadius="50%"
			p="2px"
			color="#1e1e1e"
			top="-3px"
			zIndex="2"
			border="2px"
			borderColor="white"
			boxShadow="0 2px 4px rgba(0,0,0,.2)"
			bgColor="white"
		></chakra.button>
	);
}
ControlPoints.InsertPoint = InsertPoint;

export default ControlPoints;
