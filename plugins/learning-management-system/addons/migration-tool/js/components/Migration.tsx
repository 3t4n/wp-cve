import {
	Box,
	Button,
	FormLabel,
	HStack,
	Icon,
	Select,
	Stack,
	Tooltip,
	useSteps,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useCallback, useEffect, useMemo, useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { IoIosMove } from 'react-icons/io';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import FormControlTwoCol from '../../../../assets/js/back-end/components/common/FormControlTwoCol';
import { infoIconStyles } from '../../../../assets/js/back-end/config/styles';
import API from '../../../../assets/js/back-end/utils/api';
import { deepClean, isEmpty } from '../../../../assets/js/back-end/utils/utils';
import {
	ALL_MIGRATION_STEPS,
	COMPLETED,
	COURSES,
	MIGRATING,
	ORDERS,
	REVIEWS,
} from '../constants/general';
import { urls } from '../constants/urls';
import MigrationStatusDisplay from './MigrationStatusDisplay';
interface LMS {
	name: string;
	label: string;
}

interface MigrationData {
	type?: 'courses' | 'orders' | 'reviews';
	lms_name: string;
}

function useLMSsQuery() {
	const LMSsAPI = new API(urls.migrationLMSs);
	return useQuery(['migrationLMSsList'], () => LMSsAPI.list());
}

function useMigrateMutation() {
	const migrationAPI = new API(urls.migrations);
	return useMutation((data: MigrationData) => migrationAPI.store(data));
}

const Migration: React.FC = () => {
	const { activeStep, setActiveStep } = useSteps({
		index: 0,
		count: ALL_MIGRATION_STEPS.length,
	});

	const [showMigrationStatus, setShowMigrationStatus] =
		useState<boolean>(false);
	const toast = useToast();
	const queryClient = useQueryClient();
	const {
		register,
		handleSubmit,
		getValues,
		formState: { errors },
		watch,
	} = useForm();

	const lmsWatchedValue = watch('lms_name');
	const migrationLMSsQuery = useLMSsQuery();
	const migrate = useMigrateMutation();

	const [migrationStatus, setMigrationStatus] = useState({
		courses: 'not_started',
		orders: 'not_started',
		reviews: 'not_started',
	});

	const updateMigrationStatus = useCallback(
		(response: any) => {
			setMigrationStatus((prevStatus) => {
				let newStatus = { ...prevStatus };

				if (response.remainingCourses && !isEmpty(response.remainingCourses)) {
					newStatus.courses = MIGRATING;
				} else if (
					response.remainingOrders &&
					!isEmpty(response.remainingOrders)
				) {
					newStatus = {
						...newStatus,
						courses: COMPLETED,
						orders: MIGRATING,
					};
				} else if (
					response.remainingReviews &&
					!isEmpty(response.remainingReviews)
				) {
					newStatus = {
						...newStatus,
						courses: COMPLETED,
						orders: COMPLETED,
						reviews: MIGRATING,
					};
				} else {
					newStatus = {
						courses: COMPLETED,
						orders: COMPLETED,
						reviews: COMPLETED,
					};
				}

				const allCompleted =
					newStatus.courses === COMPLETED &&
					newStatus.orders === COMPLETED &&
					newStatus.reviews === COMPLETED;
				if (allCompleted) {
					queryClient.invalidateQueries('courseList');
				}

				return newStatus;
			});
		},
		[setMigrationStatus, toast, queryClient],
	);

	const onSubmit = (data: any) => {
		setActiveStep(0);
		setMigrationStatus({
			...migrationStatus,
			courses: MIGRATING,
			orders: 'not_started',
			reviews: 'not_started',
		});
		migrate.mutate(deepClean(data), {
			onSuccess: (response) => {
				updateMigrationStatus(response);
			},
			onError: (error: any) => {
				const message =
					error?.message ||
					error?.data?.message ||
					__('Failed to migrate.', 'masteriyo');
				toast({
					title: __('Failed to migrate.', 'masteriyo'),
					description: message,
					status: 'error',
					isClosable: true,
				});
			},
		});
	};

	const renderLMSsOption = () => {
		if (migrationLMSsQuery.isLoading) {
			return <option disabled>{__('Loading...', 'masteriyo')}</option>;
		}

		if (migrationLMSsQuery.isError) {
			return (
				<option disabled>{__('Error loading options', 'masteriyo')}</option>
			);
		}

		const lmsOptions = migrationLMSsQuery.data?.data || [];

		return lmsOptions.map((lms: LMS) => (
			<option value={lms.name} key={lms.name}>
				{lms.label}
			</option>
		));
	};

	const onMigrationalStatusClose = useCallback(() => {
		return setShowMigrationStatus(false);
	}, [showMigrationStatus]);

	const currentlyActiveLms = useMemo(() => {
		return migrationLMSsQuery.data?.data.find(
			(d: any) => d.name === getValues('lms_name'),
		);
	}, [lmsWatchedValue]);

	const migrationProcessInProgress = useMemo(() => {
		return Object.keys(migrationStatus).find(
			(key) => migrationStatus[key] === MIGRATING,
		);
	}, [migrationStatus]); // This will return the step that is migrating

	const isMigrationProcessCompleted = useMemo(() => {
		return Object.keys(migrationStatus).every(
			(key) => migrationStatus[key] === COMPLETED,
		);
	}, [migrationStatus]); // This will return true if the process is completed

	useEffect(() => {
		if (migrationStatus[COURSES] === COMPLETED) {
			setActiveStep(1);
			if (migrationStatus[ORDERS] === COMPLETED) {
				setActiveStep(2);

				if (migrationStatus[REVIEWS] === COMPLETED) {
					setActiveStep(3);
				}
			}
		}
	}, [migrationStatus]);

	useEffect(() => {
		let statusTimeOut: NodeJS.Timeout;
		if (
			!showMigrationStatus &&
			Object.keys(migrationStatus).some(
				(key) =>
					migrationStatus[key] === MIGRATING ||
					migrationStatus[key] === COMPLETED,
			)
		) {
			setShowMigrationStatus(true);
		}
		if (
			showMigrationStatus &&
			Object.keys(migrationStatus).every(
				(key) => migrationStatus[key] === COMPLETED,
			)
		) {
			statusTimeOut = setTimeout(() => {
				setShowMigrationStatus(false);
			}, 2000);
		}

		return () => {
			clearTimeout(statusTimeOut);
		};
	}, [migrationStatus]);

	useEffect(() => {
		if (errors.lms_name) {
			toast({
				title: __(String(errors.lms_name.message), 'masteriyo'),
				status: 'error',
				isClosable: true,
			});
		}
	}, [errors.lms_name]);

	useEffect(() => {
		const allCompleted =
			migrationStatus.courses === COMPLETED &&
			migrationStatus.orders === COMPLETED &&
			migrationStatus.reviews === COMPLETED;

		if (allCompleted) {
			return;
		}
		if (!migrate.isLoading) {
			const lsmName = getValues('lms_name');
			(
				[COURSES, ORDERS, REVIEWS] as Array<'courses' | 'orders' | 'reviews'>
			).forEach((type) => {
				if (migrationStatus[type] === MIGRATING) {
					migrate.mutate(
						{ lms_name: lsmName, type },
						{
							onSuccess: (response) => {
								updateMigrationStatus(response);
							},
							onError: (error: any) => {
								const message =
									error?.message ||
									error?.data?.message ||
									__('Failed to migrate.', 'masteriyo');
								toast({
									title: __('Failed to migrate.', 'masteriyo'),
									description: message,
									status: 'error',
									isClosable: true,
								});
							},
						},
					);
				}
			});
		}
	}, [migrationStatus, updateMigrationStatus, getValues, migrate, toast]);

	return (
		<Stack direction="column" spacing="6">
			<form onSubmit={handleSubmit(onSubmit)}>
				<FormControlTwoCol isInvalid={!!errors?.lms_name}>
					<FormLabel htmlFor="lms_name">
						{__('Migration From', 'masteriyo')}
						<Tooltip
							label={__('Choose an LMS from the list to migrate.', 'masteriyo')}
							hasArrow
							fontSize="xs"
						>
							<Box as="span" sx={infoIconStyles}>
								<Icon as={BiInfoCircle} />
							</Box>
						</Tooltip>
					</FormLabel>
					<HStack>
						<Select
							id="lms_name"
							isDisabled={migrate.isLoading}
							placeholder={__('Select an LMS', 'masteriyo')}
							{...register('lms_name', {
								required: __('Select an LMS.', 'masteriyo'),
							})}
						>
							{renderLMSsOption()}
						</Select>
						<Button
							colorScheme="blue"
							type="submit"
							isLoading={migrate.isLoading}
							isDisabled={migrate.isLoading}
							loadingText={__('Migrating...', 'masteriyo')}
							size="md"
							rightIcon={<IoIosMove size={15} />}
						>
							{__('Migrate', 'masteriyo')}
						</Button>
					</HStack>
				</FormControlTwoCol>

				{/* Migration status modal */}
				<MigrationStatusDisplay
					activeStep={activeStep}
					currentlyActiveLms={currentlyActiveLms}
					lmsWatchedValue={lmsWatchedValue}
					isMigrationProcessCompleted={isMigrationProcessCompleted}
					migrationProcessInProgress={migrationProcessInProgress}
					onMigrationalStatusClose={onMigrationalStatusClose}
					showMigrationStatus={showMigrationStatus}
				/>
			</form>
		</Stack>
	);
};

export default Migration;
