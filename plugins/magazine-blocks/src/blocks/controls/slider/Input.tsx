import {
	Flex,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
} from "@chakra-ui/react";
import React from "react";

export const Input = (props: {
	value: number;
	onChange: (value: number | string) => void;
}) => {
	return (
		<Flex>
			<NumberInput
				maxW="100px"
				mr="2rem"
				value={props.value}
				onChange={props.onChange}
			>
				<NumberInputField />
				<NumberInputStepper>
					<NumberIncrementStepper />
					<NumberDecrementStepper />
				</NumberInputStepper>
			</NumberInput>
			<Slider
				flex="1"
				focusThumbOnChange={false}
				value={props.value}
				onChange={props.onChange}
			>
				<SliderTrack>
					<SliderFilledTrack />
				</SliderTrack>
				<SliderThumb fontSize="sm" boxSize="32px">
					{props.value}
				</SliderThumb>
			</Slider>
		</Flex>
	);
};
