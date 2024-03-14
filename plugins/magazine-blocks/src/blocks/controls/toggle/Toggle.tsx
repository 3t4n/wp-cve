import { FormControl, FormLabel, HStack, Switch } from "@chakra-ui/react";
import React from "react";
import { ToggleProps } from "./types";

const Toggle = (props: ToggleProps) => {
	const { checked, onChange, label, ...switchProps } = props;
	return (
		<FormControl>
			<HStack justify="space-between">
				<FormLabel>{label}</FormLabel>
				<Switch
					_focus={{ boxShadow: "none" }}
					size="sm"
					isChecked={checked}
					onChange={(v) => onChange(v.target.checked)}
					{...switchProps}
				/>
			</HStack>
		</FormControl>
	);
};

export default Toggle;
