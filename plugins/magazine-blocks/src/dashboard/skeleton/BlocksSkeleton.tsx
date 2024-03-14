import { Grid, HStack, Skeleton, SkeletonText, Stack } from "@chakra-ui/react";
import React from "react";

const BlocksSkeleton: React.FC = () => {
	return (
		<Stack px="24px" mt="32px">
			<HStack justify="space-between" align="center">
				<HStack>
					<Skeleton w="120px" h="40px" />
					<Skeleton w="216px" h="40px" />
				</HStack>
				<Skeleton w="232px" h="40px" />
			</HStack>
			<Grid
				gridTemplateColumns="repeat(auto-fill, minmax(290px, 1fr))"
				gridGap="16px"
				mt="23px"
			>
				{Array.from({ length: 20 }).map((_, i) => (
					<HStack
						key={i}
						p="12px"
						borderRadius="4px"
						border="1px solid"
						borderColor="gray.100"
						align="center"
						justify="space-between"
						bgColor="white"
					>
						<HStack>
							<Skeleton h="28px" w="28px" m="14px" />
							<SkeletonText noOfLines={2} width="80px" />
						</HStack>
						<Skeleton
							w="34px"
							h="20px"
							borderRadius="20px"
							mr="19px"
						/>
					</HStack>
				))}
			</Grid>
		</Stack>
	);
};

export default BlocksSkeleton;
