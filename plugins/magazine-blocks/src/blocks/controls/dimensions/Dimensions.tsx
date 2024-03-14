import {
	Box,
	FormControl,
	FormLabel,
	HStack,
	IconButton,
	NumberInput,
	NumberInputField,
	useId,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import useDevice from "../../hooks/useDevice";
import { deepClone, omit, _get, _set } from "../../utils";
import DevicePicker from "../common/DevicePicker";
import Reset from "../common/Reset";
import UnitPicker from "../common/UnitPicker";
import { Unit } from "../types/units";
import { DimensionsProps } from "./types";

const sides = ["top", "right", "bottom", "left"];

const STYLE_PROPS_MAP = {
	top: {
		borderTopColor: "primary.500",
	},
	bottom: {
		borderBottomColor: "primary.500",
	},
	right: {
		borderRightColor: "primary.500",
	},
	left: {
		borderLeftColor: "primary.500",
	},
};

const STYLE_PROPS_RADIUS_MAP = {
	top: {
		borderRight: "0",
		borderBottom: "0",
		left: 0,
		top: 0,
		borderBottomLeftRadius: 0,
		borderTopRightRadius: 0,
	},
	right: {
		right: "0",
		top: 0,
		borderLeft: 0,
		borderBottom: 0,
		borderBottomRightRadius: 0,
		borderTopLeftRadius: 0,
	},
	bottom: {
		right: 0,
		bottom: 0,
		borderLeft: 0,
		borderTop: 0,
		borderTopRightRadius: 0,
		borderBottomLeftRadius: 0,
	},
	left: {
		left: 0,
		bottom: 0,
		borderRight: 0,
		borderTop: 0,
		borderTopLeftRadius: 0,
		borderBottomRightRadius: 0,
	},
};

const Dimensions = (props: DimensionsProps) => {
	const {
		value,
		onChange,
		label,
		units = ["px", "rem", "em"],
		min = -Infinity,
		max = Infinity,
		step = 0.01,
		defaultUnit = "px",
		type = "",
		responsive,
		resetAttributeKey,
		isRadius,
	} = props;

	const id = useId();
	const { device, setDevice } = useDevice();

	const currentUnit =
		(responsive ? value?.[device]?.unit : value?.unit) ?? defaultUnit;

	const currentValue = responsive ? value?.[device] : value;

	const inputAttrs = ["%", "vh", "vw"].includes(currentUnit)
		? { min: "margin" === type ? -100 : 0, max: 100, step }
		: ["em", "rem"].includes(currentUnit)
		? { min: "margin" === type ? -20 : 0, max: 20, step }
		: "px" === currentUnit
		? { min: "margin" === type ? -max : 0, max, step: 1 }
		: { min, max, step };

	const currentMaxValue = Math.max(
		...(Object.values(omit(currentValue ?? {}, ["unit", "lock"])).filter(
			(v) => "number" === typeof v && isFinite(v)
		) as number[])
	);

	const toggleLock = () => {
		const temp: any = value ? deepClone(value) : {};
		if (!currentValue?.lock && isFinite(currentMaxValue)) {
			for (const side of sides) {
				_set(temp, responsive ? [device, side] : side, currentMaxValue);
			}
		}
		_set(temp, responsive ? [device, "lock"] : "lock", !currentValue?.lock);
		onChange(temp);
	};

	const update = (
		key: string,
		val: string | number | undefined | boolean
	) => {
		const temp: any = value ? deepClone(value) : {};
		if ("all" === key) {
			for (const side of sides) {
				_set(temp, responsive ? [device, side] : side, val);
			}
		} else {
			_set(temp, responsive ? [device, key] : key, val);
		}
		if (!_get(temp, responsive ? [device, "unit"] : "unit")) {
			_set(temp, responsive ? [device, "unit"] : "unit", currentUnit);
		}
		onChange(temp);
	};

	return (
		<FormControl>
			<HStack justify="space-between">
				<HStack>
					<FormLabel>{label}</FormLabel>
					{responsive && (
						<DevicePicker device={device} setDevice={setDevice} />
					)}
				</HStack>
				{resetAttributeKey && (
					<Reset
						resetKey={resetAttributeKey}
						saved={value}
						onReset={(v) => {
							onChange(v);
						}}
					/>
				)}
			</HStack>
			<HStack>
				{currentValue?.lock ? (
					<NumberInput
						{...inputAttrs}
						onChange={(_, num) =>
							update("all", isFinite(num) ? num : undefined)
						}
						value={
							isFinite(currentMaxValue)
								? currentMaxValue
								: undefined
						}
					>
						<NumberInputField
							borderColor="var(--chakra-colors-gray-200) !important"
							_focus={{
								boxShadow: "none !important",
							}}
						/>
					</NumberInput>
				) : (
					<HStack>
						{sides.map((side) => (
							<NumberInput
								key={`${id}${side}`}
								{...inputAttrs}
								border="1px"
								borderColor="gray.200"
								p="2px"
								borderRadius="base"
								position="relative"
								value={currentValue?.[side]}
								onChange={(_, n) =>
									update(side, isFinite(n) ? n : undefined)
								}
								sx={
									!isRadius
										? {
												...STYLE_PROPS_MAP[side],
										  }
										: undefined
								}
							>
								<NumberInputField
									border="none !important"
									_focus={{
										boxShadow: "none !important",
									}}
									zIndex="1"
									minHeight="35px"
									height="35px"
									p="0 !important"
									textAlign="center"
								/>
								{isRadius && (
									<Box
										w="50%"
										h="50%"
										position="absolute"
										borderRadius="base"
										border="1px"
										borderColor="primary.500"
										sx={{
											...STYLE_PROPS_RADIUS_MAP[side],
										}}
									/>
								)}
							</NumberInput>
						))}
					</HStack>
				)}
				<HStack
					py="6px"
					px="4px"
					border="1px"
					borderColor="gray.200"
					borderRadius="base"
					gap="0"
				>
					<IconButton
						isActive={currentValue?.lock}
						onClick={() => toggleLock()}
						aria-label={__("Lock / Unlock", "magazine-blocks")}
						bgColor="transparent"
						_hover={{
							bgColor: "transparent",
						}}
						h="28px"
						w="33px"
						_active={{
							bgColor: "transparent",
						}}
						icon={
							currentValue?.lock ? (
								<svg
									width="20"
									height="20"
									viewBox="0 0 20 20"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M15.8333 9.16675H4.16667C3.24619 9.16675 2.5 9.91294 2.5 10.8334V16.6667C2.5 17.5872 3.24619 18.3334 4.16667 18.3334H15.8333C16.7538 18.3334 17.5 17.5872 17.5 16.6667V10.8334C17.5 9.91294 16.7538 9.16675 15.8333 9.16675Z"
										stroke="#7E36F4"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
									<path
										d="M5.83203 9.16675V5.83341C5.83203 4.72835 6.27102 3.66854 7.05242 2.88714C7.83382 2.10573 8.89363 1.66675 9.9987 1.66675C11.1038 1.66675 12.1636 2.10573 12.945 2.88714C13.7264 3.66854 14.1654 4.72835 14.1654 5.83341V9.16675"
										stroke="#7E36F4"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
								</svg>
							) : (
								<svg
									width="20"
									height="20"
									viewBox="0 0 20 20"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M15.8333 9.16675H4.16667C3.24619 9.16675 2.5 9.91294 2.5 10.8334V16.6667C2.5 17.5872 3.24619 18.3334 4.16667 18.3334H15.8333C16.7538 18.3334 17.5 17.5872 17.5 16.6667V10.8334C17.5 9.91294 16.7538 9.16675 15.8333 9.16675Z"
										stroke="#374151"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
									<path
										d="M5.83203 9.16675V5.83341C5.83203 4.72835 6.27102 3.66854 7.05242 2.88714C7.83382 2.10573 8.89363 1.66675 9.9987 1.66675C11.1038 1.66675 12.1636 2.10573 12.945 2.88714C13.7264 3.66854 14.1654 4.72835 14.1654 5.83341"
										stroke="#374151"
										strokeLinecap="round"
										strokeLinejoin="round"
									/>
								</svg>
							)
						}
					/>
					<UnitPicker
						units={units}
						currentUnit={currentUnit as Unit}
						onChange={(unit) => update("unit", unit)}
					/>
				</HStack>
			</HStack>
		</FormControl>
	);
};

export default Dimensions;
