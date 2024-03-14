import {
	Alert,
	AlertIcon,
	Box,
	Button,
	ButtonGroup,
	Container,
	FormLabel,
	Icon,
	IconButton,
	Input,
	InputGroup,
	InputRightElement,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Switch,
	Text,
	Tooltip,
	useClipboard,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import {
	BiBook,
	BiCog,
	BiDotsHorizontalRounded,
	BiInfoCircle,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink } from 'react-router-dom';
import FormControlTwoCol from '../../../../assets/js/back-end/components/common/FormControlTwoCol';
import {
	Header,
	HeaderLeftSection,
	HeaderLogo,
	HeaderRightSection,
	HeaderTop,
} from '../../../../assets/js/back-end/components/common/Header';
import {
	NavMenu,
	NavMenuItem,
	NavMenuLink,
} from '../../../../assets/js/back-end/components/common/Nav';
import {
	headerResponsive,
	infoIconStyles,
	navActiveStyles,
	navLinkStyles,
} from '../../../../assets/js/back-end/config/styles';
import routes from '../../../../assets/js/back-end/constants/routes';
import API from '../../../../assets/js/back-end/utils/api';
import localized from '../../../../assets/js/back-end/utils/global';
import googleClassroomUrls from '../../constants/urls';
import { GoogleClassroomSettingsSkeleton } from './GoogleClassroomSkeleton';
import PermissionCheck from './components/PermissionCheck';
import ClientId from './settings/components/ClientId';
import ClientSecret from './settings/components/ClientSecret';

export interface GoogleClassroomSettingsSchema {
	client_id: string;
	client_secret: string;
	access_code?: boolean;
	refresh_token?: string;
	access_token?: string;
	token_available?: boolean;
}

const GoogleClassroomSetting = () => {
	const methods = useForm<GoogleClassroomSettingsSchema>();
	const googleClassroomSetting = new API(googleClassroomUrls.settings);
	const toast = useToast();
	const queryClient = useQueryClient();
	const [errorMessage, setErrorMessage] = useState('');
	const { hasCopied: googleCopy, onCopy: clipToCopyForGoogle } = useClipboard(
		`${localized.home_url}/wp-admin/admin.php?page=masteriyo`,
	);
	const googleClassroomCoursesApi = new API(
		googleClassroomUrls.googleClassroom,
	);

	const googleClassroomSettingQuery = useQuery('settings', () =>
		googleClassroomSetting.list(),
	);
	const updateGoogleClassroomSettingsMutation = useMutation(
		(data: GoogleClassroomSettingsSchema) => googleClassroomSetting.store(data),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('settings');
				toast({
					title: __('Google Classroom Settings Updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
			onError: (error: any) => {
				const message: any = error?.message
					? error?.message
					: error?.data?.message;

				toast({
					title: __(
						'Could not update the google classroom settings.',
						'masteriyo',
					),
					description: message || '',
					status: 'error',
					isClosable: true,
				});
			},
		},
	);

	const items = useMutation<any, unknown, void>(
		() => googleClassroomCoursesApi.list('forced=true'),
		{
			onSuccess(data) {
				queryClient.invalidateQueries('googleClassroomCourseList');
				toast({
					title: __('Synced Successfully.', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				setErrorMessage('');
			},
			onError: (err: any) => {
				setErrorMessage(err?.message);
				toast({
					title: err?.message,
					status: 'error',
					isClosable: true,
				});
			},
		},
	);

	const onSubmit = (data: GoogleClassroomSettingsSchema) => {
		updateGoogleClassroomSettingsMutation.mutate(data);
	};

	const onClearData = () => {
		let data: GoogleClassroomSettingsSchema = {
			client_id: '',
			client_secret: '',
			access_token: '',
			refresh_token: '',
		};
		updateGoogleClassroomSettingsMutation.mutate(data);
		methods.setValue('client_id', '');
		methods.setValue('client_secret', '');
	};

	return (
		<>
			<Stack direction="column" spacing={8} alignItems="center">
				<Header>
					<HeaderTop>
						<HeaderLeftSection>
							<Stack direction={['column', 'column', 'column', 'row']}>
								<HeaderLogo />
							</Stack>

							<NavMenu sx={headerResponsive.larger} color={'gray.600'}>
								<NavMenuItem key={routes.googleClassroom.list} display="flex">
									<NavMenuLink
										as={NavLink}
										_hover={{ textDecoration: 'none' }}
										_activeLink={navActiveStyles}
										to={routes.googleClassroom.list}
										leftIcon={<BiBook />}
									>
										{__('Google Courses', 'masteriyo')}
									</NavMenuLink>
								</NavMenuItem>

								<NavMenuLink
									as={NavLink}
									sx={{ ...navLinkStyles, borderBottom: '2px solid white' }}
									_hover={{ textDecoration: 'none' }}
									_activeLink={navActiveStyles}
									to={routes.googleClassroom.setting}
									leftIcon={<BiCog />}
								>
									{__('Settings', 'masteriyo')}
								</NavMenuLink>
							</NavMenu>

							<NavMenu sx={headerResponsive.smaller} color={'gray.600'}>
								<Menu>
									<MenuButton
										as={IconButton}
										icon={<BiDotsHorizontalRounded style={{ fontSize: 25 }} />}
										style={{
											background: '#FFFFFF',
											boxShadow: 'none',
										}}
										py={'35px'}
										color={'primary.500'}
									/>
									<MenuList color={'gray.600'}>
										<MenuItem>
											<NavMenuLink
												as={NavLink}
												_hover={{ textDecoration: 'none' }}
												_activeLink={navActiveStyles}
												to={routes.googleClassroom.list}
												leftIcon={<BiBook />}
											>
												{__('Google Courses', 'masteriyo')}
											</NavMenuLink>
										</MenuItem>

										<MenuItem>
											<NavMenuLink
												as={NavLink}
												sx={{ color: 'black', height: '20px' }}
												_activeLink={{ color: 'primary.500' }}
												// to={routes}
												leftIcon={<BiCog />}
											>
												{__('Settings', 'masteriyo')}
											</NavMenuLink>
										</MenuItem>
									</MenuList>
								</Menu>
							</NavMenu>
						</HeaderLeftSection>

						{googleClassroomSettingQuery.data && (
							<HeaderRightSection>
								<PermissionCheck
									googleSettingCredentials={googleClassroomSettingQuery.data}
								/>
							</HeaderRightSection>
						)}
					</HeaderTop>
				</Header>
				<Container maxW="container.xl">
					<Stack direction="column" spacing={5}>
						<Stack shadow="box">
							<Alert status="info">
								<AlertIcon />
								<Stack spacing={1} direction="row">
									<Text>
										{__(
											'Please see the following documentation page for instructions on how to obtain the credentials.',
											'masteriyo',
										)}
									</Text>
									<Link
										href={'https://console.cloud.google.com'}
										target="_blank"
										sx={{ textDecoration: 'underline' }}
									>
										{__('Documentation', 'masteriyo')}
									</Link>
								</Stack>
							</Alert>
						</Stack>

						{googleClassroomSettingQuery.isSuccess ? (
							<Stack direction="column" spacing="6">
								<FormProvider {...methods}>
									<form onSubmit={methods.handleSubmit(onSubmit)}>
										<Stack
											direction={['column', 'column', 'column', 'row']}
											spacing={8}
										>
											<Box bg="white" p="10" shadow="box" gap="6" width="full">
												<Stack direction="column" spacing="6" pb="6">
													<ClientId
														defaultValue={
															googleClassroomSettingQuery?.data?.client_id
														}
													/>

													<ClientSecret
														defaultValue={
															googleClassroomSettingQuery?.data?.client_secret
														}
													/>

													<FormControlTwoCol>
														<FormLabel>
															{__('Redirect Url', 'masteriyo')}
															<Tooltip
																label={__(
																	'You must Copy the redirect url and paste it in the google console credentials otherwise it will not work.',
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
																type={'text'}
																disabled
																_placeholder={{ fontSize: '12px' }}
																defaultValue={`${localized.home_url}/wp-admin/admin.php?page=masteriyo`}
															/>
															<InputRightElement width="11">
																<Button
																	variant="solid"
																	onClick={clipToCopyForGoogle}
																	rounded="true"
																	boxShadow="none"
																	colorScheme="primary"
																>
																	{googleCopy
																		? __('Copied', 'masteriyo')
																		: __('Copy', 'masteriyo')}
																</Button>
															</InputRightElement>
														</InputGroup>
													</FormControlTwoCol>

													{localized.isCurrentUserAdmin === 'yes' ? (
														<FormControlTwoCol flexDirection={'row'}>
															<FormLabel
																display={'flex'}
																alignItems={'flex-start'}
															>
																{__(
																	'Code For Logged In Users Only',
																	'masteriyo',
																)}
																<Tooltip
																	label={__(
																		'When enabled, this option restricts the access to the classroom invite code. For free classrooms, only logged-in users can see the code.',
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
															<Switch
																w="100%"
																{...methods.register('access_code')}
																defaultChecked={
																	googleClassroomSettingQuery?.data?.access_code
																}
															/>
														</FormControlTwoCol>
													) : null}

													{googleClassroomSettingQuery?.data?.refresh_token ? (
														<FormControlTwoCol flexDirection={'row'}>
															<FormLabel
																display={'flex'}
																alignItems={'flex-start'}
															>
																{__('Clear Course Cache', 'masteriyo')}
																<Tooltip
																	label={__(
																		'Deletes the existing caches data and requests for new course data from google classroom',
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
															<Box width={'100px'}>
																<Button
																	onClick={() => items.mutate()}
																	isLoading={items.isLoading}
																	rounded="xl"
																	loadingText={__('Syncing...', 'masteriyo')}
																	variant="outline"
																	height={'24px'}
																>
																	{__('Sync', 'masteriyo')}
																</Button>
															</Box>
														</FormControlTwoCol>
													) : null}

													{googleClassroomSettingQuery?.data?.refresh_token ? (
														<FormControlTwoCol flexDirection={'row'}>
															<FormLabel
																display={'flex'}
																alignItems={'flex-start'}
															>
																{__('Clears All Data', 'masteriyo')}
																<Tooltip
																	label={__(
																		'Deletes the all the data, so user have to sign in again to sync the data to google classroom',
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
															<Box width={'100px'}>
																<IconButton
																	onClick={() => onClearData()}
																	isLoading={
																		updateGoogleClassroomSettingsMutation.isLoading
																	}
																	icon={<BiTrash size="20px" />}
																	_hover={{ color: 'red' }}
																	rounded="xl"
																	border={'none'}
																	variant="outline"
																	aria-label="Delete All data"
																/>
															</Box>
														</FormControlTwoCol>
													) : null}
												</Stack>

												<ButtonGroup>
													<Button
														colorScheme="primary"
														type="submit"
														isLoading={
															updateGoogleClassroomSettingsMutation.isLoading
														}
													>
														{__('Save Settings', 'masteriyo')}
													</Button>
												</ButtonGroup>
											</Box>
										</Stack>
									</form>
								</FormProvider>
							</Stack>
						) : (
							<GoogleClassroomSettingsSkeleton />
						)}
					</Stack>
				</Container>
			</Stack>
		</>
	);
};

export default GoogleClassroomSetting;
