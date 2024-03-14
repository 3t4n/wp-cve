import {
	FormControl,
	FormLabel,
	NumberInput,
	NumberInputField,
} from "@chakra-ui/react";
import React from "react";
import { useDebounceCallback } from "../../hooks";
import { inputStyles } from "./Input";
import { NumberInputProps } from "./types";

const InputNumber = (props: NumberInputProps) => {
	const { label, value, onChange, numberFieldProps, ...other } = props;
	const [localValue, setLocalValue] = React.useState<number | undefined>(
		value
	);
	const debouncedOnChange = useDebounceCallback(onChange, 400);
	return (
		<FormControl>
			<FormLabel mb="2">{label}</FormLabel>
			<NumberInput
				{...other}
				value={localValue}
				onChange={(_, n) => {
					const val = isFinite(n) ? n : undefined;
					debouncedOnChange(val);
					setLocalValue(val);
				}}
			>
				<NumberInputField {...inputStyles} {...numberFieldProps} />
			</NumberInput>
		</FormControl>
	);
};

export default InputNumber;
