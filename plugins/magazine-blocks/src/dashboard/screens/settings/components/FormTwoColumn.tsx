import { Box, FormLabel, HStack, Tooltip } from "@chakra-ui/react";
import React from "react";
import { QuestionCircleFill } from "../../../components/Icon";

const FormTwoColumn: React.FC<{
	label: string;
	children: React.ReactNode;
	description?: React.ReactNode;
	labelAlignStart?: boolean;
}> = ({ label, children, description, labelAlignStart }) => {
	return (
		<HStack justify="space-between">
			<FormLabel
				fontSize="14px"
				fontWeight="semibold"
				color="gray.700"
				flexBasis="38%"
				display="flex"
				alignItems="center"
				gap="2"
				alignSelf={labelAlignStart ? "flex-start" : undefined}
				my="1"
			>
				{label}
				{description && (
					<Tooltip
						label={description}
						fontSize="xs"
						hasArrow
						placement="top"
					>
						<QuestionCircleFill w="4" h="4" fill="gray.500" />
					</Tooltip>
				)}
			</FormLabel>
			<Box flexBasis="60%">{children}</Box>
		</HStack>
	);
};

export default FormTwoColumn;
