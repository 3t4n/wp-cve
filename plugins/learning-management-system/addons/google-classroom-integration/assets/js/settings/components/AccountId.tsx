import {
	Box,
	FormControl,
	FormLabel,
	Icon,
	Input,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { infoIconStyles } from '../../../../../../assets/js/back-end/config/styles';

interface Props {
	defaultValue?: string;
}
const AccountId: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<FormControl isInvalid={!!errors?.account_id}>
			<FormLabel>
				{__('Account ID', 'masteriyo')}
				<Tooltip
					label={__('Your zoom account ID to connect with.', 'masteriyo')}
					hasArrow
					fontSize="xs"
				>
					<Box as="span" sx={infoIconStyles}>
						<Icon as={BiInfoCircle} />
					</Box>
				</Tooltip>
			</FormLabel>
			<Input
				defaultValue={defaultValue}
				{...register('account_id', {
					required: __('This field is required', 'masteriyo'),
				})}
			/>
			{/* <FormErrorMessage>
				{errors?.account_id && errors?.account_id?.message}
			</FormErrorMessage> */}
		</FormControl>
	);
};

export default AccountId;
