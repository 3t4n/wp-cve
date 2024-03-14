import { Box, Collapse } from "@chakra-ui/react";
import React from "react";
import IconModal from "./IconModal";
import { IconPickerSwitch } from "./IconPickerSwitch";
import { IconPickerProps } from "./types";

const IconPicker = (props: IconPickerProps) => {
	return (
		<Box>
			{props.hasToggle && (
				<IconPickerSwitch
					onSwitchToggle={(v) =>
						props.onChange({
							icon: props.value?.icon,
							enable: v,
						})
					}
				/>
			)}
			<Box
				as={Collapse}
				in={props.hasToggle ? !!props.value?.enable : true}
			>
				<IconModal
					onChange={(v) => {
						if (props.hasToggle) {
							props.onChange({
								enable: true,
								icon: v,
							});
						} else {
							props.onChange(v);
						}
					}}
					value={props.hasToggle ? props.value?.icon : props.value}
				/>
			</Box>
		</Box>
	);
};

export default IconPicker;
