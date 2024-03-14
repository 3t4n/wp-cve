import { SwitchProps } from "@chakra-ui/react";

export interface ToggleProps extends Omit<SwitchProps, "onChange"> {
	label?: string;
	checked?: boolean;
	onChange: (checked: boolean) => void;
}
