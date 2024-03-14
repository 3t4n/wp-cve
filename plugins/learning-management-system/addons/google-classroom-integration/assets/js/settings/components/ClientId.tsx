import {
	Box,
	FormControl,
	FormLabel,
	Icon,
	Input,
	InputGroup,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { infoIconStyles } from '../../../../../../assets/js/back-end/config/styles';
import FormControlTwoCol from '../../../../../../assets/js/back-end/components/common/FormControlTwoCol';

interface Props {
	defaultValue?: string;
}

const ClientId: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<FormControlTwoCol>
			<FormLabel>
				{__('Client ID', 'masteriyo')}
				<Tooltip
					label={__(
						'The unique ID to your registered application, which is used to authorize requests to the google classroom API.',
						'masteriyo',
					)}
					hasArrow
					fontSize="xs"
				>
					<Box as="span" sx={infoIconStyles}>
						<Icon as={BiInfoCircle} />
					</Box>
				</Tooltip>
			</FormLabel>
			<InputGroup>
				<Input
					defaultValue={defaultValue}
					{...register('client_id')}
					placeholder="Client Id"
					padding="inherit"
					autoComplete="off"
				/>
			</InputGroup>
		</FormControlTwoCol>
	);
};

export default ClientId;
