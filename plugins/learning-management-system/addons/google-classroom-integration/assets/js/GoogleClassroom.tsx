import {
	Box,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiBook, BiCog, BiDotsHorizontalRounded } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink } from 'react-router-dom';
import { Container } from '../../../../assets/js/back-end/components/common/Container';
import {
	Header,
	HeaderLeftSection,
	HeaderLogo,
	HeaderTop,
} from '../../../../assets/js/back-end/components/common/Header';
import {
	NavMenu,
	NavMenuItem,
	NavMenuLink,
} from '../../../../assets/js/back-end/components/common/Nav';
import {
	headerResponsive,
	navActiveStyles,
	navLinkStyles,
} from '../../../../assets/js/back-end/config/styles';
import routes from '../../../../assets/js/back-end/constants/routes';
import urls from '../../../../assets/js/back-end/constants/urls';
import API from '../../../../assets/js/back-end/utils/api';
import googleClassroomUrls from '../../constants/urls';
import { GoogleClassroomListSkeleton } from './GoogleClassroomSkeleton';
import GoogleClassroomCoursesList from './components/GoogleClassroomCoursesList';

export interface postData {
	google_classroom_course: {
		id: any;
		type: string;
	};
	name: any;
	google_course_url: any;
	google_classroom_enrollment_code: any;
	short_description: any;
	status: string;
}

export interface googleClassroomCourses {
	id: string;
	name: string;
	descriptionHeading: string;
	ownerId: string;
	creationTime: string;
	updateTime: string;
	enrollmentCode: string;
	courseState: string;
	alternateLink: string;
	teacherGroupEmail: string;
	courseGroupEmail: string;
	teacherFolder: {
		id: string;
		title: string;
		alternateLink: string;
	};
	guardiansEnabled: boolean;
	calendarId: string;
	gradebookSettings: {
		calculationType: string;
		displaySetting: string;
	};
	course_status?: string;
	course_id?: string;
	edit_post_link?: string;
	permalink?: string;
	students?: {
		courseId: string;
		userId: string;
		profile: {
			id: string;
			name: {
				givenName: string;
				familyName: string;
				fullName: string;
			};
			emailAddress: string;
		};
	}[];
}

export interface newData {
	google_classroom_course_id: number | string;
	name: string;
	google_course_url: string;
	google_classroom_enrollment_code: string;
	description: string;
	status: 'draft' | 'publish';
}

export type googleClassroomCoursesList = googleClassroomCourses[];

function GoogleClassroom() {
	const googleClassroomCoursesApi = new API(
		googleClassroomUrls.googleClassroom,
	);
	const googleClassroomUsersApi = new API(
		googleClassroomUrls.googleClassroomStudents,
	);

	const googleClassroomQuery = useQuery<googleClassroomCoursesList>(
		'googleClassroomCourseList',
		() => googleClassroomCoursesApi.list(),
	);
	const queryClient = useQueryClient();
	const courseAPI = new API(urls.courses);

	const addCourse = useMutation((data: newData) => courseAPI.store(data));

	const addStudents = useMutation((data: { students: any; course_id: any }) =>
		googleClassroomUsersApi.store(data),
	);

	const toast = useToast();
	// On Add Course
	const onImportClick = (data: googleClassroomCourses) => {
		const newData: newData = {
			google_classroom_course_id: data.id,
			name: data.name,
			google_course_url: data.alternateLink,
			google_classroom_enrollment_code: data.enrollmentCode,
			description: data.descriptionHeading,
			status: 'draft',
		};
		addCourse.mutate(newData, {
			onSuccess: () => {
				queryClient.invalidateQueries('googleClassroomCourseList');
				{
					toast({
						title: __(
							'Google Classroom course Imported in courses',
							'masteriyo',
						),
						isClosable: true,
						status: 'success',
					});
				}
			},
		});
	};

	const studentImportOnClick = (data: googleClassroomCourses) => {
		const newData: {
			students: any;
			course_id: any;
		} = {
			students: data.students,
			course_id: data.course_id,
		};

		addStudents.mutate(newData, {
			onSuccess: () => {
				{
					toast({
						title: __('Google Classroom course students Imported', 'masteriyo'),
						isClosable: true,
						status: 'success',
					});
				}
			},
		});
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
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
											sx={{ color: 'black', height: '20px' }}
											_activeLink={{ color: 'primary.500' }}
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
											to={routes.googleClassroom.setting}
											leftIcon={<BiCog />}
										>
											{__('Settings', 'masteriyo')}
										</NavMenuLink>
									</MenuItem>
								</MenuList>
							</Menu>
						</NavMenu>
					</HeaderLeftSection>
				</HeaderTop>
			</Header>
			<Container maxW="container.xl">
				<Stack direction={['column', 'column', 'column', 'row']} spacing={8}>
					<Box bg="white" p="10" shadow="box" gap="6" width="full">
						{googleClassroomQuery.isLoading ? (
							<GoogleClassroomListSkeleton />
						) : (
							<GoogleClassroomCoursesList
								googleClassroomQueryData={googleClassroomQuery.data}
								onImportClick={onImportClick}
								studentImportOnClick={studentImportOnClick}
								addCourse={addCourse}
							/>
						)}
					</Box>
				</Stack>
			</Container>
		</Stack>
	);
}

export default GoogleClassroom;
