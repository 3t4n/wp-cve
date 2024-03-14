import { Box, Input, InputGroup, InputLeftAddon } from "@chakra-ui/react";
import { colord } from "colord";
import React, { useEffect, useRef } from "react";
import { inputStyles } from "./styles";

type Props = {
	color: string;
	onChange: (color: string) => void;
};

export const HexInput = ({ color, onChange }: Props) => {
	const ref = useRef<HTMLInputElement>(null);
	const previous = useRef(color);

	useEffect(() => {
		if (previous.current === color || !ref.current) {
			return;
		}
		ref.current.value = color;
		previous.current = color;
	}, [color]);

	return (
		<InputGroup
			sx={{
				".chakra-input": {
					...inputStyles,
				},
			}}
		>
			<InputLeftAddon
				p="0"
				h="32px"
				pl="10px"
				bg="transparent"
				borderColor="gray.400"
				borderRight="0"
				borderTopLeftRadius="2px"
				borderBottomLeftRadius="2px"
			>
				<Box w="20px" borderRadius="base" h="20px" bg={color} />
			</InputLeftAddon>
			<Input
				ref={ref}
				minH="32px"
				borderLeft="0 !important"
				maxLength={9}
				defaultValue={color}
				onChange={(e) => {
					const nextValue = e.target.value;
					if (colord(nextValue).isValid()) {
						onChange(nextValue);
					}
				}}
			/>
		</InputGroup>
	);
};

export default HexInput;
