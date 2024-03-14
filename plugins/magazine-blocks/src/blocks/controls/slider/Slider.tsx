import {
	FormControl,
	FormLabel,
	HStack,
	InputGroup,
	InputRightAddon,
	NumberInput,
	NumberInputField,
	Slider as ChakraSlider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	VisuallyHidden,
} from "@chakra-ui/react";
import React from "react";
import { useDebounceCallback } from "../../hooks";
import useDevice from "../../hooks/useDevice";
import { deepClone, _set } from "../../utils";
import DevicePicker from "../common/DevicePicker";
import Reset from "../common/Reset";
import UnitPicker from "../common/UnitPicker";
import { Unit } from "../types/units";
import { Measurement, Responsive } from "../types/utils";
import { DEFAULT_UNIT, SIZE_UNITS } from "./constants";
import { SliderProps } from "./types";

const Slider = (props: SliderProps) => {
	const {
		label,
		value,
		responsive,
		resetAttributeKey,
		onChange,
		units,
		min = 0,
		max = 1000,
		step = 1,
		defaultUnit,
		forceShowUnit,
	} = props;

	const { device, setDevice } = useDevice();

	const getValue = React.useCallback(
		(val: any): number | undefined => {
			if (responsive && units) {
				return val?.[device]?.value;
			}
			if (responsive && !units) {
				return val?.[device];
			}
			if (!responsive && units) {
				return (val as Measurement)?.value;
			}

			return val as number | undefined;
		},
		[device, responsive, units]
	);

	const setValue = (val: number | undefined) => {
		if (!responsive && !units) {
			return val;
		}
		let temp = value ? deepClone(value) : {};
		if (responsive && units) {
			_set(temp, `${device}.value`, val);
			!value?.[device]?.unit &&
				_set(temp, [device, "unit"], defaultUnit ?? DEFAULT_UNIT);
		} else if (!responsive && units) {
			_set(temp, "value", val);
			!value?.unit && _set(temp, "unit", defaultUnit ?? DEFAULT_UNIT);
		} else if (responsive && !units) {
			_set(temp, `${device}`, val);
		}

		return temp;
	};

	const [localValue, setLocalValue] = React.useState(getValue(value));
	const debouncedOnChange = useDebounceCallback(onChange);

	React.useEffect(() => {
		setLocalValue(getValue(value));
	}, [value]); // eslint-disable-line

	const updateUnit = (val: Unit) => {
		let temp = value ? deepClone(value) : {};
		if (responsive && units) {
			temp = _set(temp, [device, "unit"], val);
			onChange(temp as Responsive<Measurement>);
		} else if (!responsive && !!units) {
			temp = _set(temp, "unit", val);
			onChange(temp as Measurement);
		}
	};

	const currentUnit = units
		? ((responsive ? value?.[device]?.unit : value?.unit) as Unit) ??
		  defaultUnit ??
		  DEFAULT_UNIT
		: DEFAULT_UNIT;

	const inputAttrs = {
		min,
		max,
		step,
		...(SIZE_UNITS[currentUnit] ?? {}),
	};

	React.useEffect(() => {
		setLocalValue(getValue(value) as any);
	}, [device]); // eslint-disable-line react-hooks/exhaustive-deps

	const SliderLabel = props.hiddenLabel ? VisuallyHidden : FormLabel;

	return (
		<FormControl>
			<HStack justify="space-between" mb="2">
				<HStack>
					<SliderLabel>{label}</SliderLabel>
					{responsive && (
						<DevicePicker device={device} setDevice={setDevice} />
					)}
				</HStack>
				<HStack>
					{resetAttributeKey && (
						<Reset
							saved={value}
							resetKey={resetAttributeKey}
							onReset={(v) => {
								onChange(v);
								setLocalValue(getValue(v));
							}}
						/>
					)}
				</HStack>
			</HStack>
			<HStack gap={4}>
				<ChakraSlider
					focusThumbOnChange={false}
					value={localValue}
					{...inputAttrs}
					onChange={(v) => {
						setLocalValue(v);
						debouncedOnChange(setValue(v) as any);
					}}
				>
					<SliderTrack bg="#E0E0E0">
						<SliderFilledTrack bgColor="primary.500" />
					</SliderTrack>
					<SliderThumb bgColor="primary.500" />
				</ChakraSlider>
				<InputGroup
					isolation="auto"
					border="1px"
					borderColor="gray.200"
					borderRadius="sm"
					maxW="90px"
					px="4px"
				>
					<NumberInput
						{...inputAttrs}
						value={localValue}
						onChange={(_, vn) => {
							const val = isNaN(vn) ? undefined : vn;
							setLocalValue(val);
							debouncedOnChange(setValue(val) as any);
						}}
						flex="1"
					>
						<NumberInputField
							fontSize="xs"
							border="none !important"
							_focus={{
								boxShadow: "none !important",
							}}
						/>
					</NumberInput>
					{units ? (
						<InputRightAddon
							p="0"
							bg="white"
							borderRadius="0"
							border="0"
						>
							<UnitPicker
								units={units}
								currentUnit={currentUnit}
								onChange={updateUnit}
							/>
						</InputRightAddon>
					) : forceShowUnit ? (
						<InputRightAddon
							p="0"
							px="1"
							fontSize="xs"
							bg="white"
							borderRadius="0"
							border="0"
						>
							{defaultUnit ?? DEFAULT_UNIT}
						</InputRightAddon>
					) : null}
				</InputGroup>
			</HStack>
		</FormControl>
	);
};

export default Slider;
