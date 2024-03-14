import {
	Badge,
	Box,
	Button,
	IconButton,
	Text,
	useClipboard,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiCheckCircle, BiCopy } from 'react-icons/bi';
import { UseMutationResult } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../../../assets/js/back-end/constants/routes';
import { googleClassroomCourses, newData } from '../GoogleClassroom';

import Buttons from './Buttons';

interface Props {
	course: googleClassroomCourses;
	onImportClick: (data: any) => void;
	addCourse: UseMutationResult<any, unknown, newData, unknown>;
	courseKey: string;
	studentImportOnClick: (data: any) => void;
}

const GoogleClassroomCoursesRow: React.FC<Props> = (props) => {
	const { course, onImportClick, addCourse, courseKey, studentImportOnClick } =
		props;
	const { onCopy, setValue, hasCopied } = useClipboard(
		course.enrollmentCode || '',
	);

	const [courseId, setCourseId] = useState(course.id || '');

	return (
		<Tr key={course.id}>
			<Td>
				{course.course_id ? (
					<RouterLink
						to={routes.courses.edit.replace(
							':courseId',
							course.course_id.toString(),
						)}
					>
						<Text>{course.name}</Text>
					</RouterLink>
				) : (
					<Text>{course.name}</Text>
				)}
			</Td>
			<Td>
				<Box>
					<Badge colorScheme="yellow">
						<Text marginRight={'2px'}>{course.enrollmentCode}</Text>
					</Badge>
					{course.enrollmentCode && (
						<IconButton
							onClick={() => {
								setCourseId(course.id);
								setValue(course.enrollmentCode);
								onCopy();
							}}
							aria-label="Copy google classroom code"
							icon={
								hasCopied && courseId === course.id ? (
									<BiCheckCircle />
								) : (
									<BiCopy />
								)
							}
							boxShadow="none"
							size="xs"
							ml="1"
						/>
					)}
					{!course.enrollmentCode && (
						<Badge colorScheme="red">
							<Text marginRight={'2px'}>{__('None', 'masteriyo')}</Text>
						</Badge>
					)}
				</Box>
			</Td>
			<Td>
				{course.course_status ? (
					<Badge
						textTransform="uppercase"
						colorScheme={'draft' === course.course_status ? 'yellow' : 'green'}
					>
						{course.course_status}
					</Badge>
				) : (
					<Badge textTransform="uppercase" colorScheme="blue">
						{__('Not imported')}
					</Badge>
				)}
			</Td>
			<Td>
				{course.course_id ? (
					<Buttons
						course={course}
						onImportClick={onImportClick}
						addCourse={addCourse}
						courseKey={courseKey}
						studentImportOnClick={studentImportOnClick}
					/>
				) : (
					<Button
						colorScheme="primary"
						onClick={() => onImportClick(course)}
						size="xs"
						isLoading={
							courseId === addCourse.variables?.google_classroom_course_id
								? addCourse.isLoading
								: false
						}
						marginRight="70px"
					>
						{__('Import', 'masteriyo')}
					</Button>
				)}
			</Td>
		</Tr>
	);
};

export default GoogleClassroomCoursesRow;
