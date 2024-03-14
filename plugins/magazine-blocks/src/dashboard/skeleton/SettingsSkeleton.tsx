import {
	Box,
	HStack,
	Skeleton,
	SkeletonCircle,
	SkeletonText,
	Stack,
} from "@chakra-ui/react";
import React from "react";

const SettingsSkeleton: React.FC = () => {
	return (
		<Box m="32px 24px">
			<HStack gap="0">
				<Stack
					w="30%"
					gap="20px"
					py="40px"
					border="1px solid"
					borderColor="gray.200"
					alignSelf="stretch"
				>
					{Array.from({ length: 4 }).map((_, i) => (
						<HStack key={i} p="20px">
							<SkeletonCircle size="24px" />
							<SkeletonText noOfLines={2} flex="1" />
						</HStack>
					))}
				</Stack>
				<Stack
					w="full"
					p="40px 20px"
					border="1px solid"
					borderColor="gray.200"
					borderLeft="none"
				>
					{Array.from({ length: 5 }).map((_, i) => (
						<HStack key={i} p="20px">
							<Box flexBasis="38%">
								<Skeleton h="24px" w="200px" flexBasis="38%" />
							</Box>
							<Box flexBasis="60%">
								<SkeletonText noOfLines={2} />
							</Box>
						</HStack>
					))}
				</Stack>
			</HStack>
		</Box>
	);
};

export default SettingsSkeleton;
