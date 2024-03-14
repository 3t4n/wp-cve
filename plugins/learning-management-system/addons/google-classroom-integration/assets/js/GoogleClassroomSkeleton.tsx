import {
	Box,
	Container,
	HStack,
	Skeleton,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Table, Tbody, Td, Th, Thead, Tr } from 'react-super-responsive-table';

export const GoogleClassroomSettingsSkeleton: React.FC = () => (
	<Stack direction="column" spacing="8" alignItems="center">
		<Container maxW="container.xl">
			<Stack direction={['column', 'column', 'column', 'row']} spacing="8">
				<Box bg="white" p="10" shadow="box" width="full">
					<Stack direction="column" spacing="6">
						<HStack spacing="5">
							<Skeleton height="40px" flex={1} />
							<Skeleton height="40px" flex={1} />
						</HStack>
						<Skeleton height="40px" />
					</Stack>
				</Box>
			</Stack>
		</Container>
	</Stack>
);
export const GoogleClassroomListSkeleton: React.FC = () => (
	<>
		<Table>
			<Thead>
				<Tr>
					<Th>{__('Class Name', 'masteriyo')}</Th>
					<Th>{__('Class Code', 'masteriyo')}</Th>
					<Th>{__('Status', 'masteriyo')}</Th>
					<Th>{__('Action', 'masteriyo')}</Th>
				</Tr>
			</Thead>
			<Tbody>
				{[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((index) => (
					<Tr key={index}>
						<Td>
							<SkeletonText noOfLines={1} />
						</Td>
						<Td>
							<SkeletonText noOfLines={1} />
						</Td>
						<Td>
							<SkeletonText noOfLines={1} />
						</Td>
						<Td>
							<SkeletonText noOfLines={1} />
						</Td>
					</Tr>
				))}
			</Tbody>
		</Table>
	</>
);
