import { Button, Menu, MenuButton, MenuItem, MenuList } from "@chakra-ui/react";
import React from "react";
import { Unit } from "../types/units";

type Props = {
	units: Array<Unit>;
	currentUnit: Unit;
	onChange: (unit: Unit) => void;
};

export const UnitPicker = (props: Props) => {
	const { onChange, units, currentUnit } = props;
	return (
		<Menu gutter={0} matchWidth>
			<MenuButton
				as={Button}
				variant="icon"
				fontSize="13px"
				color="primary.500"
				fontWeight="normal"
				_active={{
					color: "white",
					backgroundColor: "primary.500",
					border: "1px",
					borderColor: "primary.500",
					borderTopRightRadius: "sm",
					borderTopLeftRadius: "sm",
				}}
				h="28px"
				w="35px"
				py="4px"
				borderRadius="0"
				borderBottomLeftRadius="none"
				borderBottomRightRadius="none"
			>
				{currentUnit}
			</MenuButton>
			<MenuList
				py="0"
				border="1px"
				borderColor="primary.500"
				borderRadius="none"
				borderBottomLeftRadius="sm"
				borderBottomRightRadius="sm"
				minW="35px"
				w="35px"
			>
				{units
					.filter((u) => u !== currentUnit)
					.map((unit) => (
						<MenuItem
							key={unit}
							onClick={() => onChange(unit)}
							justifyContent="center"
							_hover={{
								bgColor: "transparent",
							}}
							fontSize="13px"
							color="primary.500"
							fontWeight="normal"
							h="28px"
							w="35px"
							py="4px"
							borderRadius="none"
							bg="transparent"
						>
							{unit}
						</MenuItem>
					))}
			</MenuList>
		</Menu>
	);
};

export default UnitPicker;
