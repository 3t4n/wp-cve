import {
	InputProps as ChakraInputProps,
	NumberInputFieldProps,
	NumberInputProps as ChakraNumberInputProps,
} from "@chakra-ui/react";
interface BaseInputProps {
	label: string;
	placeholder?: string;
	hiddenLabel?: boolean;
}

export interface InputProps
	extends BaseInputProps,
		Omit<ChakraInputProps, "onChange"> {
	onChange: (val: string) => void;
	value?: string;
}

export interface NumberInputProps
	extends BaseInputProps,
		Omit<ChakraNumberInputProps, "onChange"> {
	onChange: (val: number | undefined) => void;
	value?: number;
	numberFieldProps?: NumberInputFieldProps;
}
