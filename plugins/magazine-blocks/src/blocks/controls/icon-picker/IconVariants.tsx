import { Button, Stack } from "@chakra-ui/react";
import React from "react";
import { LABELS } from "./labels";

type Variant = "any" | "magazine-blocks" | "solid" | "regular" | "brands";

type Props = {
	variant: string;
	onVariantSelect: (string: Variant) => void;
};

export const IconVariants = ({ variant, onVariantSelect }: Props) => {
	return (
		<Stack
			w="25%"
			flexShrink={0}
			flexDir="column"
			alignItems="start"
			gap="0"
			pt="8"
			height="full"
			overflowY="auto"
		>
			{LABELS.map(({ label, value }) => (
				<Button
					isActive={value === variant}
					variant="unstyled"
					key={label}
					minH="40px"
					fontSize="sm"
					fontWeight="normal"
					onClick={() => onVariantSelect(value as Variant)}
					_hover={{
						color: "primary.400",
						position: "relative",
						bgColor: "primary.100",
					}}
					w="full"
					textAlign="left"
					pr="4"
					pl="8"
					py="3"
					borderRadius="none"
					_active={{
						color: "primary.500",
						position: "relative",
						bgColor: "primary.100",
						"&:after": {
							content: '""',
							width: "2px",
							position: "absolute",
							left: 0,
							top: 0,
							height: "full",
							bgColor: "primary.500 !important",
						},
					}}
				>
					{label}
				</Button>
			))}
		</Stack>
	);
};

export default IconVariants;
