import {
	Box,
	Button,
	FormControl,
	FormLabel,
	HStack,
	InputGroup,
	InputRightAddon,
	NumberInput,
	NumberInputField,
	Select,
	Stack,
	Text,
} from "@chakra-ui/react";
import { FocalPointPicker } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import React from "react";
import useDevice from "../../../hooks/useDevice";
import DevicePicker from "../../common/DevicePicker";
import Slider from "../../slider/Slider";
import { BACKGROUND_IMAGE_SIZES } from "./constants";
import { focalInputStyles, selectStyles } from "./styles";
import { BackgroundImageProps } from "./types";
import {
	parseFocalPoint,
	parseSize,
	serializeFocalPoint,
	serializeSize,
} from "./utils";

type Props = {
	value: BackgroundImageProps["value"];
	openMediaFrame: () => void;
	onChange: (v: Record<string, any>) => void;
};

export const Image = ({ value, openMediaFrame, onChange }: Props) => {
	const { device, setDevice } = useDevice();
	const position = value?.position?.[device] ?? "default";
	const focalPoints = parseFocalPoint(position as string);

	const updateFocalPoints = (point: number, axis: "x" | "y") => {
		if (isFinite(point)) {
			point = Math.max(0, Math.min(100, point));
			onChange({
				position: {
					...(value?.position ?? {}),
					[device]: serializeFocalPoint({
						...focalPoints,
						[axis]: point / 100,
					}),
				},
			});
		}
	};

	const isCustomSize =
		value?.size?.[device] &&
		!BACKGROUND_IMAGE_SIZES.includes(value.size[device] as string);

	return (
		<Stack gap="8px">
			<Box>
				<Box
					position="relative"
					sx={{
						".focal-point-picker__controls": {
							display: "none !important",
						},
						".components-base-control__field": {
							marginBottom: "0",
						},
						...selectStyles,
					}}
				>
					<FocalPointPicker
						url={value?.image?.url as string}
						value={focalPoints}
						onChange={(points) => {
							onChange({
								position: {
									...(value?.position ?? {}),
									[device]: serializeFocalPoint(points),
								},
							});
						}}
					/>
					<Button
						variant="icon"
						position="absolute"
						bottom="45px"
						right="5px"
						onClick={() => onChange({ image: undefined })}
					>
						D
					</Button>
					<Text
						as="span"
						display="block"
						py="2"
						textAlign="center"
						onClick={openMediaFrame}
						mx="-4"
						borderBottom="1px"
						borderColor="gray.200"
					>
						{__("Change Image", "magazine-blocks")}
					</Text>
				</Box>
				<HStack gap="8px">
					<FormControl>
						<HStack>
							<FormLabel m="0">
								{__("Left", "magazine-blocks")}
							</FormLabel>
							<DevicePicker
								device={device}
								setDevice={setDevice}
							/>
						</HStack>

						<InputGroup sx={focalInputStyles}>
							<NumberInput
								min={0}
								max={100}
								step={1}
								value={(focalPoints.x * 100).toFixed(0)}
								onChange={(_, n) => {
									updateFocalPoints(n, "x");
								}}
							>
								<NumberInputField />
							</NumberInput>
							<InputRightAddon>%</InputRightAddon>
						</InputGroup>
					</FormControl>
					<FormControl>
						<HStack>
							<FormLabel m="0">
								{__("Left", "magazine-blocks")}
							</FormLabel>
							<DevicePicker
								device={device}
								setDevice={setDevice}
							/>
						</HStack>
						<InputGroup sx={focalInputStyles}>
							<NumberInput
								min={0}
								max={100}
								step={1}
								value={(focalPoints.y * 100).toFixed(0)}
								onChange={(_, n) => {
									updateFocalPoints(n, "y");
								}}
							>
								<NumberInputField />
							</NumberInput>
							<InputRightAddon>%</InputRightAddon>
						</InputGroup>
					</FormControl>
				</HStack>
			</Box>
			<FormControl>
				<HStack>
					<FormLabel m="0">{__("Size", "magazine-blocks")}</FormLabel>
					<DevicePicker device={device} setDevice={setDevice} />
				</HStack>
				<Select
					value={isCustomSize ? "custom" : value?.size?.[device]}
					onChange={(e) => {
						onChange({
							size: {
								...(value?.size ?? {}),
								[device]: e.target.value,
							},
						});
					}}
				>
					<option value="default">
						{__("Default", "magazine-blocks")}
					</option>
					<option value="contain">
						{__("Contain", "magazine-blocks")}
					</option>
					<option value="cover">
						{__("Cover", "magazine-blocks")}
					</option>
					<option value="fill">
						{__("Auto", "magazine-blocks")}
					</option>
					<option value="custom">
						{__("Custom", "magazine-blocks")}
					</option>
				</Select>
				{isCustomSize && (
					<Slider
						// @ts-ignore
						hiddenLabel
						value={parseSize(value?.size?.[device] as string)}
						onChange={(v: any) => {
							onChange({
								size: {
									...(value?.size ?? {}),
									[device]: serializeSize(v),
								},
							});
						}}
						units={["%", "px", "em", "rem"]}
						defaultUnit="%"
						label={__("Custom Size", "magazine-blocks")}
					/>
				)}
			</FormControl>

			<FormControl>
				<HStack>
					<FormLabel m="0">
						{__("Repeat", "magazine-blocks")}
					</FormLabel>
					<DevicePicker device={device} setDevice={setDevice} />
				</HStack>
				<Select
					value={value?.repeat?.[device]}
					onChange={(e) => {
						onChange({
							repeat: {
								...(value?.repeat ?? {}),
								[device]: e.target.value,
							},
						});
					}}
				>
					<option value="default">
						{__("Default", "magazine-blocks")}
					</option>
					<option value="no-repeat">
						{__("No Repeat", "magazine-blocks")}
					</option>
					<option value="repeat">
						{__("Repeat", "magazine-blocks")}
					</option>
					<option value="repeat-y">
						{__("Repeat Vertically", "magazine-blocks")}
					</option>
					<option value="repeat-x">
						{__("Repeat Horizontally", "magazine-blocks")}
					</option>
				</Select>
			</FormControl>
			<FormControl>
				<FormLabel>{__("Attachment", "magazine-blocks")}</FormLabel>
				<Select
					value={value?.attachment}
					onChange={(e) => {
						onChange({
							attachment: e.target.value,
						});
					}}
				>
					<option value="default">
						{__("Default", "magazine-blocks")}
					</option>
					<option value="scroll">
						{__("Scroll", "magazine-blocks")}
					</option>
					<option value="fixed">
						{__("Fixed", "magazine-blocks")}
					</option>
				</Select>
			</FormControl>
		</Stack>
	);
};

export const Placeholder = ({
	openMediaFrame,
}: {
	openMediaFrame: () => void;
}) => {
	return (
		<Box onClick={openMediaFrame} mx="-4">
			<Box minH="152px" bg="gray.100" />
			<Text
				as="span"
				textAlign="center"
				py="2"
				display="block"
				borderBottom="1px"
				borderColor="gray.200"
			>
				{__("Choose Image", "magazine-blocks")}
			</Text>
		</Box>
	);
};
