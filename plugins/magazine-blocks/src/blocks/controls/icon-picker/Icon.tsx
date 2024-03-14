import { Box, Button, Text } from "@chakra-ui/react";
import React from "react";
import { IconType } from "./types";

type Props = {
	icon: IconType;
	isSelected?: boolean;
	onSelectIcon?: (val: string) => void;
};

const Icon = ({ icon, onSelectIcon, isSelected }: Props) => {
	return (
		<Button
			isActive={!!isSelected}
			key={icon.id}
			display="flex"
			flexDir="column"
			variant="unstyled"
			flex={1}
			height="full"
			gap="4"
			border="1px"
			borderColor="gray.100"
			borderRadius="base"
			_hover={{
				color: "primary.500",
				bgColor: "primary.100",
			}}
			_active={{
				color: "primary.500",
				borderColor: "primary.500",
				bgColor: "primary.100",
			}}
			onClick={() => onSelectIcon?.(icon.id)}
		>
			<Box
				as="span"
				sx={{
					svg: {
						w: 6,
						h: 6,
						fill: "currentColor",
					},
				}}
				dangerouslySetInnerHTML={{
					__html: icon.svg,
				}}
			/>
			<Text
				as="span"
				fontSize="xs"
				fontWeight="normal"
				w="full"
				overflow="hidden"
				textOverflow="ellipsis"
				px="4"
			>
				{icon.label}
			</Text>
		</Button>
	);
};

export default Icon;
