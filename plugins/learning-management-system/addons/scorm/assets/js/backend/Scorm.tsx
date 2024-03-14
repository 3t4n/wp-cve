import React, { useState } from 'react';
import {
	Center,
	Button,
	useToast,
	Modal,
	ModalOverlay,
	ModalContent,
	ModalHeader,
	ModalFooter,
	ModalBody,
	ModalCloseButton,
	Input,
	useDisclosure,
	Spinner,
	Icon,
	Text,
	Flex,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { BiImport, BiFile } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import http from '../../../../../assets/js/back-end/utils/http';
import { urls } from './constants/urls';

interface Props {
	courseId: number;
}

interface ImportResponse {
	success: boolean;
	message: string;
	data?: any;
}

const Scorm: React.FC<Props> = (props) => {
	const { courseId } = props;
	const { isOpen, onOpen, onClose } = useDisclosure();
	const toast = useToast();
	const queryClient = useQueryClient();

	const importMutation = useMutation<ImportResponse, Error, File>(
		(selectedFile) => {
			const formData = new FormData();
			formData.append('file', selectedFile);
			formData.append('course_id', courseId.toString());

			return http({
				path: urls.scormImport,
				method: 'POST',
				body: formData,
			});
		},
		{
			onSuccess() {
				toast({
					title: __('Import Successful', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries('builder' + courseId.toString());
				onClose();
			},
			onError(error: any) {
				toast({
					title: __('Import Failed', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					status: 'error',
					isClosable: true,
				});
			},
		},
	);

	const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
		if (event.target.files) {
			importMutation.mutate(event.target.files[0]);
		}
	};

	return (
		<Center>
			<Button
				variant="outline"
				colorScheme="primary"
				width={['140px', '160px', '180px']}
				onClick={() => onOpen()}
				leftIcon={<BiImport size="18" />}
			>
				{__('Import SCORM', 'masteriyo')}
			</Button>

			<Modal isOpen={isOpen} onClose={onClose} size="xl" isCentered>
				<ModalOverlay />
				<ModalContent>
					<ModalHeader>{__('Import SCORM Package', 'masteriyo')}</ModalHeader>
					<ModalCloseButton />
					<ModalBody mb={6}>
						{importMutation.isLoading ? (
							<Flex direction="column" align="center" justify="center" p={6}>
								<Spinner size="xl" />
								<Text mt={4} fontWeight="bold">
									{__('Uploading...', 'masteriyo')}
								</Text>
							</Flex>
						) : (
							<Flex
								direction="column"
								align="center"
								justify="center"
								p={6}
								border="2px dashed #ccc"
								borderRadius="md"
							>
								<Icon as={BiFile} w={8} h={8} />
								<Text mt={4} fontWeight="bold">
									{__(
										'Choose a SCORM package file (.zip) to upload',
										'masteriyo',
									)}
								</Text>
								<Button
									as="label"
									variant="solid"
									colorScheme="blue"
									size="md"
									mt={4}
								>
									{__('Choose File', 'masteriyo')}
									<Input
										type="file"
										onChange={handleFileChange}
										accept=".zip"
										hidden
									/>
								</Button>
							</Flex>
						)}
					</ModalBody>
				</ModalContent>
			</Modal>
		</Center>
	);
};

export default Scorm;
