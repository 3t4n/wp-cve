import React, { useState } from 'react';
import {
	Box,
	Text,
	Flex,
	IconButton,
	HStack,
	Spacer,
	Tooltip,
	useColorModeValue,
	useToken,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { FaFileAlt, FaTrash } from 'react-icons/fa';
import { useMutation, useQueryClient } from 'react-query';
import API from '../../../../../assets/js/back-end/utils/api';
import { urls } from './constants/urls';

interface Props {
	courseId: number;
	scorm_package: {
		path: string;
		url: string;
		scorm_version: string;
		file_name: string;
	};
}

const ScormBuilder: React.FC<Props> = (props) => {
	const { courseId, scorm_package } = props;

	const toast = useToast();
	const queryClient = useQueryClient();

	const deleteAPI = new API(urls.scormDelete);

	const { mutate: deleteMutate, isLoading: isDeleting } = useMutation(
		(id: number) => deleteAPI.delete(id),
		{
			onSuccess() {
				toast({
					title: __('SCORM Deleted Successfully.', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries('builder' + courseId.toString());
			},
			onError(error: any) {
				toast({
					title: __('Delete failed!', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					status: 'error',
					isClosable: true,
				});
			},
		},
	);

	const iconColor = useColorModeValue('blue.500', 'blue.200');
	const [deleteButtonColor, deleteButtonColorHover] = useToken('colors', [
		'red.500',
		'red.600',
	]);

	return (
		<Box
			p="4"
			borderWidth="1px"
			borderRadius="lg"
			my="4"
			bg="white"
			transition="box-shadow 0.3s"
			_hover={{
				boxShadow: '0 4px 8px rgba(0, 0, 0, 0.2)',
			}}
		>
			<Flex alignItems="center">
				<Box
					as={FaFileAlt}
					size="40px"
					color={iconColor}
					mr="3"
					aria-label={__('SCORM Course', 'masteriyo')}
				/>
				<Flex direction="column">
					<Text fontSize="lg" fontWeight="bold">
						{__('SCORM Course', 'masteriyo')}
					</Text>
					<Text fontSize="sm" color="gray.600" mt="1">
						{__(
							'SCORM packages offer pre-built content with fixed structures, unlike the customizable lessons, assignments, or quizzes created within an LMS.',
							'masteriyo',
						)}
					</Text>
				</Flex>
				<Spacer />
				<HStack spacing="2">
					<IconButton
						colorScheme="red"
						size="sm"
						aria-label={__('Delete', 'masteriyo')}
						isLoading={isDeleting ? true : false}
						onClick={() => deleteMutate(courseId)}
						icon={<FaTrash />}
					/>
				</HStack>
			</Flex>
			<Text fontSize="sm" color="gray.600" mt="2">
				{__('SCORM Package Name:', 'masteriyo')} {scorm_package.file_name}
			</Text>
		</Box>
	);
};

export default ScormBuilder;
