import {
	HStack,
	InputGroup,
	InputRightAddon,
	NumberInput,
	NumberInputField,
} from "@chakra-ui/react";
import React from "react";
import { HslaColor } from "react-colorful";

import { inputStyles } from "./styles";

type Props = {
	color: HslaColor;
	onChange: (val: HslaColor) => void;
};

const styles = {
	sx: {
		".chakra-numberinput__field": inputStyles,
	},
};

export const HslInput = ({ color, onChange }: Props) => {
	const update = (val: number, type: string) => {
		onChange({
			...color,
			[type]: val,
		});
	};
	return (
		<HStack gap="0">
			<NumberInput
				min={0}
				{...styles}
				max={359}
				value={color?.h}
				onChange={(_, n) => update(n, "h")}
			>
				<NumberInputField />
			</NumberInput>
			<NumberInput
				min={0}
				{...styles}
				max={100}
				value={color?.s}
				onChange={(_, n) => update(n, "s")}
			>
				<NumberInputField
					borderLeft="none !important"
					borderRight="none !important"
				/>
			</NumberInput>
			<NumberInput
				min={0}
				{...styles}
				max={100}
				value={color?.l}
				onChange={(_, n) => update(n, "l")}
			>
				<NumberInputField />
			</NumberInput>
			<InputGroup
				sx={{
					".chakra-numberinput__field": {
						textAlign: "right !important",
					},
				}}
			>
				<NumberInput
					value={color?.a * 100}
					min={0}
					max={100}
					step={1}
					{...styles}
					onChange={(_, n) => update(n / 100, "a")}
				>
					<NumberInputField
						borderLeft="none !important"
						borderRight="none !important"
						pr="0 !important"
					/>
				</NumberInput>
				<InputRightAddon
					height="32px"
					bg="transparent"
					flex="unset"
					p="0"
					px="2px"
					pr="6px"
					borderTopRightRadius="2px"
					borderBottomRightRadius="2px"
					fontSize="12px"
					borderColor="gray.400"
				>
					%
				</InputRightAddon>
			</InputGroup>
		</HStack>
	);
};

export default HslInput;
