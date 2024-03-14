import { useDebounceCallback } from "@blocks/hooks";
import {
	FormControl,
	FormLabel,
	Input as ChakraInput,
	VisuallyHidden,
} from "@chakra-ui/react";
import React from "react";
import { InputProps } from "./types";

export const inputStyles = {
	borderColor: "#949494 !important",
	borderRadius: "2px !important",
	fontSize: "13px ",
	fontWeight: "var(--chakra-fontWeights-normal) !important",
	color: "#222222 !important",
	minH: "40px !important",
	_focus: {
		borderColor: "primary.500 !important",
		outline: "none !important",
		boxShadow: "none !important",
	},
};
const Input = (props: InputProps) => {
	const { label, onChange, value, hiddenLabel, ...other } = props;
	const debouncedOnChange = useDebounceCallback(onChange, 400);
	const [localValue, setLocalValue] = React.useState(value);

	const LabelComponent = hiddenLabel ? VisuallyHidden : FormLabel;

	return (
		<FormControl>
			<LabelComponent>{label}</LabelComponent>
			<ChakraInput
				value={localValue}
				onChange={(v) => {
					setLocalValue(v.target.value);
					debouncedOnChange(v.target.value);
				}}
				{...inputStyles}
				{...other}
			/>
		</FormControl>
	);
};

export default Input;
