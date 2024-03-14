import {
	Flex,
	FormControl,
	FormLabel,
	InputGroup,
	InputRightAddon,
	NumberInput,
	NumberInputField,
	Select,
	Stack,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { LinearGradientNode, RadialGradientNode } from "gradient-parser";
import React from "react";
import { useDebounceCallback } from "../../hooks";
import ColorPicker from "../color-picker/ColorPicker";
import {
	DEFAULT_LINEAR_GRADIENT_ANGLE,
	HORIZONTAL_GRADIENT_ORIENTATION,
} from "./constants";
import { GradientBar } from "./GradientBar";
import { serializeGradient } from "./serializer";
import { GradientPickerProps } from "./types";
import {
	getGradientAstWithControlPoints,
	getGradientAstWithDefault,
	getLinearGradientRepresentation,
	getStopCssColor,
	updateControlPointColorByPosition,
} from "./utils";

export const GradientPicker = (props: GradientPickerProps) => {
	const [gradient, setGradient] = React.useState(
		() => getGradientAstWithDefault(props?.value).gradientAST
	);

	const background = getLinearGradientRepresentation(gradient);

	const controlPoints = gradient.colorStops.map((colorStop) => ({
		color: getStopCssColor(colorStop),
		// @ts-expect-error
		position: parseInt(colorStop.length.value),
	}));
	const [activeControlPointPosition, setActiveControlPointPosition] =
		React.useState(controlPoints[0].position);
	const debouncedOnChange = useDebounceCallback(props.onChange, 400);

	const onSetLinearGradient = () => {
		setGradient((prev) => {
			const newGradient = {
				...prev,
				orientation: gradient.orientation
					? undefined
					: HORIZONTAL_GRADIENT_ORIENTATION,
				type: "linear-gradient",
			} as LinearGradientNode;
			debouncedOnChange(serializeGradient(newGradient));
			return newGradient;
		});
	};

	const onSetRadialGradient = () => {
		setGradient((prev) => {
			const newGradient = {
				...prev,
				type: "radial-gradient",
				orientation: undefined,
			} as RadialGradientNode;
			debouncedOnChange(serializeGradient(newGradient));
			return newGradient;
		});
	};

	const handleOnTypeChange = (next: string) => {
		if (next === "linear-gradient") {
			onSetLinearGradient();
		}
		if (next === "radial-gradient") {
			onSetRadialGradient();
		}
	};

	const onAngleChange = (next: number) => {
		setGradient((prev) => {
			const newGradient = {
				...prev,
				orientation: {
					type: "angular",
					value: `${next}`,
				},
			} as LinearGradientNode;
			debouncedOnChange(serializeGradient(newGradient));
			return newGradient;
		});
	};

	return (
		<Stack w="254px" gap="16px">
			<GradientBar
				background={background}
				controlPoints={controlPoints}
				onChange={(nextControlPoints) => {
					setGradient(
						getGradientAstWithControlPoints(
							gradient,
							nextControlPoints
						)
					);
					debouncedOnChange(
						serializeGradient(
							getGradientAstWithControlPoints(
								gradient,
								nextControlPoints
							)
						)
					);
				}}
				activeControlPoint={activeControlPointPosition}
				setActiveControlPoint={setActiveControlPointPosition}
			/>
			<Flex justifyContent="space-between">
				<FormControl
					w="125px"
					sx={{
						".chakra-select": {
							h: "28px",
							minH: "28px",
							bg: "none",
							borderRadius: "2px",
							borderColor:
								"var(--chakra-colors-gray-400) !important",
							boxShadow: "none !important",
						},
					}}
				>
					<FormLabel>{__("Type", "magazine-blocks")}</FormLabel>
					<Select
						value={gradient.type}
						onChange={(e) => {
							handleOnTypeChange(e.target.value);
						}}
					>
						<option value="linear-gradient">
							{__("Linear", "magazine-blocks")}
						</option>
						<option value="radial-gradient">
							{__("Radial", "magazine-blocks")}
						</option>
					</Select>
				</FormControl>
				{gradient.type === "linear-gradient" && (
					<FormControl
						w="50px"
						sx={{
							".chakra-numberinput__field": {
								minH: "28px",
								h: "28px",
								borderRadius: "2px",
								borderRight: "none",
								borderColor:
									"var(--chakra-colors-gray-400) !important",
								fontSize: "13px",
								boxShadow: "none !important",
								outline: "none !important",
								pr: "0",
							},
						}}
					>
						<FormLabel>{__("Angle", "magazine-blocks")}</FormLabel>
						<InputGroup>
							<NumberInput
								value={
									gradient.orientation?.value ??
									DEFAULT_LINEAR_GRADIENT_ANGLE
								}
								onChange={(_, n) =>
									onAngleChange(isFinite(n) ? n : 0)
								}
							>
								<NumberInputField />
							</NumberInput>
							<InputRightAddon
								p="0"
								h="28px"
								borderRadius="2px"
								bg="transparent"
								border="1px"
								borderColor="gray.400"
								pr="8px"
								pl="3px"
							>
								°
							</InputRightAddon>
						</InputGroup>
					</FormControl>
				)}
			</Flex>
			{controlPoints.map((c) => {
				if (c.position === activeControlPointPosition) {
					return (
						<ColorPicker
							key={c.position}
							value={c.color}
							onChange={(next) => {
								setGradient(
									getGradientAstWithControlPoints(
										gradient,
										updateControlPointColorByPosition(
											controlPoints,
											activeControlPointPosition,
											next
										)
									)
								);
								debouncedOnChange(
									serializeGradient(
										getGradientAstWithControlPoints(
											gradient,
											updateControlPointColorByPosition(
												controlPoints,
												activeControlPointPosition,
												next
											)
										)
									)
								);
							}}
						/>
					);
				}
			})}
		</Stack>
	);
};

export default GradientPicker;
