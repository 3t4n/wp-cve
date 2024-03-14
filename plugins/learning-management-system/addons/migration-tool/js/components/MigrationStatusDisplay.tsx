import {
	Badge,
	Box,
	Step,
	StepIcon,
	StepIndicator,
	StepNumber,
	StepSeparator,
	StepStatus,
	StepTitle,
	Stepper,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FaClock } from 'react-icons/fa';
import DisplayModal from '../../../../assets/js/back-end/components/common/DisplayModal';
import LargeAlert from '../../../../assets/js/back-end/components/common/LargeAlert';
import {
	ALL_MIGRATION_STEPS,
	MIGRATION_STEPS_RELATIVE_TO_COURSE,
} from '../constants/general';

interface MigrationStatusDisplayProps {
	isMigrationProcessCompleted: boolean;
	activeStep: number;
	lmsWatchedValue: string;
	currentlyActiveLms: { name: string; label: string };
	showMigrationStatus: boolean;
	onMigrationalStatusClose: () => void;
	migrationProcessInProgress: string | undefined;
}

const MigrationStatusDisplay: React.FC<MigrationStatusDisplayProps> = ({
	isMigrationProcessCompleted,
	activeStep,
	lmsWatchedValue,
	currentlyActiveLms,
	showMigrationStatus,
	onMigrationalStatusClose,
	migrationProcessInProgress,
}) => {
	return (
		<>
			<DisplayModal
				isOpen={showMigrationStatus}
				onClose={onMigrationalStatusClose}
				title={!isMigrationProcessCompleted ? currentlyActiveLms?.label : ''}
				showCloseOption={false}
				closeOnOverlayClick={false}
				applyPadding={!isMigrationProcessCompleted}
				extraInfo={
					migrationProcessInProgress && (
						<Badge colorScheme="green" ml={2}>
							In-Progress: {migrationProcessInProgress}
						</Badge>
					)
				}
			>
				{!isMigrationProcessCompleted ? (
					<Stepper index={activeStep} mt={2} colorScheme={'primary'}>
						{[
							...(MIGRATION_STEPS_RELATIVE_TO_COURSE[lmsWatchedValue] ||
								ALL_MIGRATION_STEPS),
						].map((step, index) => (
							<Step key={index}>
								<StepIndicator>
									<StepStatus
										complete={<StepIcon />}
										incomplete={<StepNumber />}
										active={<FaClock size={25} color={'green'} />}
									/>
								</StepIndicator>

								<Box flexShrink="0">
									<StepTitle>{step}</StepTitle>
								</Box>

								<StepSeparator />
							</Step>
						))}
					</Stepper>
				) : (
					<LargeAlert
						title={__(
							`${currentlyActiveLms?.label}'s data migrated successfully.`,
							'masteriyo',
						)}
						height={'130px'}
						varient={'left-accent'}
					/>
				)}
			</DisplayModal>
		</>
	);
};

export default React.memo(MigrationStatusDisplay);
