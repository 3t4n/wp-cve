import { FormControl, FormLabel, Switch } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";

export const IconPickerSwitch = (props: {
	label?: string;
	onSwitchToggle: (val: boolean) => void;
	isChecked?: boolean;
}) => {
	return (
		<FormControl
			display="flex"
			justifyContent="space-between"
			alignItems="center"
			mb="4"
		>
			<FormLabel>
				{props?.label ?? __("Enable Icon", "magazine-blocks")}
			</FormLabel>
			<Switch
				isChecked={props.isChecked}
				onChange={(e) => props.onSwitchToggle(e.target.checked)}
			/>
		</FormControl>
	);
};
