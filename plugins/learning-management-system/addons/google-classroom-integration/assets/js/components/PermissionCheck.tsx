import { Button, ButtonGroup } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { AiFillGoogleCircle } from 'react-icons/ai';
import localized from '../../../../../assets/js/back-end/utils/global';
import { GoogleClassroomSettingsSchema } from '../GoogleClassroomSetting';

interface Props {
	googleSettingCredentials: GoogleClassroomSettingsSchema;
}
function PermissionCheck(props: Props) {
	const redirectUrl = `${localized.home_url}/wp-admin/admin.php?page=masteriyo`;
	const { googleSettingCredentials } = props;

	return (
		<ButtonGroup gap="4" margin="auto">
			<Button
				size="sm"
				leftIcon={<AiFillGoogleCircle size="24" color="green.400" />}
				colorScheme="primary"
				textDecoration="none"
				color="white"
				onClick={() => {
					window.location.href = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${googleSettingCredentials.client_id}&redirect_uri=${redirectUrl}&response_type=code&access_type=offline&scope=https://www.googleapis.com/auth/classroom.courses.readonly+https://www.googleapis.com/auth/classroom.rosters.readonly+https://www.googleapis.com/auth/classroom.profile.emails&state=masteriyo_google_classroom&prompt=consent`;
				}}
				isDisabled={
					!googleSettingCredentials.client_id ||
					!googleSettingCredentials.client_secret
				}
			>
				{__('Connect Google Account', 'masteriyo')}
			</Button>
		</ButtonGroup>
	);
}

export default PermissionCheck;
