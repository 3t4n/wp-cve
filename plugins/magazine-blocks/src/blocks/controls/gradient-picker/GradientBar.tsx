import { chakra } from "@chakra-ui/react";
import type { MouseEventHandler } from "react";
import React, { useReducer, useRef } from "react";
import { MINIMUM_DISTANCE_BETWEEN_INSERTER_AND_POINT } from "./constants";
import ControlPoints from "./ControlPoints";
import {
	gradientBarReducer,
	gradientBarReducerInitialState,
} from "./gradient-bar-reducer";
import type { CustomGradientBarProps } from "./types";
import { getHorizontalRelativeGradientPosition } from "./utils";

export const GradientBar = ({
	background,
	controlPoints,
	onChange,
	activeControlPoint,
	setActiveControlPoint,
}: CustomGradientBarProps) => {
	const gradientMarkersContainerDomRef = useRef<HTMLDivElement>(null);
	const [gradientBarState, gradientBarStateDispatch] = useReducer(
		gradientBarReducer,
		gradientBarReducerInitialState
	);

	const onMouseEnterAndMove: MouseEventHandler<HTMLDivElement> = (event) => {
		if (!gradientMarkersContainerDomRef.current) {
			return;
		}

		const insertPosition = getHorizontalRelativeGradientPosition(
			event.clientX,
			gradientMarkersContainerDomRef.current
		);

		if (
			controlPoints.some(({ position }) => {
				return (
					Math.abs(insertPosition - position) <
					MINIMUM_DISTANCE_BETWEEN_INSERTER_AND_POINT
				);
			})
		) {
			if (gradientBarState.id === "MOVING_INSERTER") {
				gradientBarStateDispatch({ type: "STOP_INSERTER_MOVE" });
			}
			return;
		}

		gradientBarStateDispatch({ type: "MOVE_INSERTER", insertPosition });
	};

	const onMouseLeave = () => {
		if (gradientBarState.id === "MOVING_INSERTER") {
			gradientBarStateDispatch({ type: "STOP_INSERTER_MOVE" });
		} else if (gradientBarState.id === "INSERTING_CONTROL_POINT") {
			gradientBarStateDispatch({ type: "CLOSE_INSERTER" });
		}
	};

	const isMovingInserter = gradientBarState.id === "MOVING_INSERTER";
	const isInsertingControlPoint =
		gradientBarState.id === "INSERTING_CONTROL_POINT";

	return (
		<chakra.div
			onMouseEnter={onMouseEnterAndMove}
			onMouseMove={onMouseEnterAndMove}
			onMouseLeave={onMouseLeave}
			position="relative"
			zIndex={1}
			height="8px"
			w="full"
			borderRadius="8px"
		>
			<chakra.div
				bg={background as string}
				position="absolute"
				inset="0"
				borderRadius="8px"
			></chakra.div>
			<chakra.div
				ref={gradientMarkersContainerDomRef}
				position="relative"
				w="calc(100% - 10px)"
				mx="auto"
			>
				{(isMovingInserter || isInsertingControlPoint) && (
					<ControlPoints.InsertPoint
						insertPosition={gradientBarState.insertPosition}
						value={controlPoints}
						onChange={onChange}
						setActiveControlPoint={setActiveControlPoint}
					/>
				)}
				<ControlPoints
					gradientPickerDomRef={gradientMarkersContainerDomRef}
					ignoreMarkerPosition={
						isInsertingControlPoint
							? gradientBarState.insertPosition
							: undefined
					}
					value={controlPoints}
					onChange={onChange}
					onStartControlPointChange={() => {
						gradientBarStateDispatch({
							type: "START_CONTROL_CHANGE",
						});
					}}
					onStopControlPointChange={() => {
						gradientBarStateDispatch({
							type: "STOP_CONTROL_CHANGE",
						});
					}}
					activeControlPoint={activeControlPoint}
					setActiveControlPoint={setActiveControlPoint}
				/>
			</chakra.div>
		</chakra.div>
	);
};
