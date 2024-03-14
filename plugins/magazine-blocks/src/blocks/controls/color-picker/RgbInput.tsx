import {
	HStack,
	InputGroup,
	InputRightAddon,
	NumberInput,
	NumberInputField,
} from "@chakra-ui/react";
import React from "react";
import { RgbaColor } from "react-colorful";

import { inputStyles } from "./styles";

type Props = {
	color: RgbaColor;
	onChange: (v: RgbaColor) => void;
};

const styles = {
	sx: {
		".chakra-numberinput__field": inputStyles,
	},
};

export const RgbInput = ({ color, onChange }: Props) => {
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
				max={255}
				value={color?.r}
				onChange={(_, n) => update(n, "r")}
			>
				<NumberInputField />
			</NumberInput>
			<NumberInput
				min={0}
				{...styles}
				max={255}
				value={color?.g}
				onChange={(_, n) => update(n, "g")}
			>
				<NumberInputField
					borderLeft="none !important"
					borderRight="none !important"
				/>
			</NumberInput>
			<NumberInput
				min={0}
				{...styles}
				max={255}
				value={color?.b}
				onChange={(_, n) => update(n, "b")}
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

export default RgbInput;
