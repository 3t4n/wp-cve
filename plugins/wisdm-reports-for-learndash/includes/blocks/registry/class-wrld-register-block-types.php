<?php
/**
 * This file contains a class with constructor which registers all the blocks
 *
 * @package learndash-reports-by-wisdmlabs
 */

namespace WisdmReportsLearndashBlockRegistry;

require_once 'class-wrld-register-block.php';
if ( ! class_exists( '\WisdmReportsLearndashBlockRegistry\WRLD_Register_Block_Types' ) ) {
	/**
	 * This class registers all the blocks listed in the constructor
	 */
	class WRLD_Register_Block_Types {
		/**
		 * Constructor
		 */
		public function __construct() {
			// Date Filters.
			include_once 'class-wrld-date-filters.php';
			new WRLD_Date_Filters( 'date-filters', 'Duration Selectors', 'A block with duration selectors for the Learndash reports' );

			// Total Revenue Earned.
			include_once 'class-wrld-total-revenue-earned.php';
			new WRLD_Total_Revenue_Earned();

			// Total Courses.
			include_once 'class-wrld-total-courses.php';
			new WRLD_Total_Courses();

			// Total Learners.
			include_once 'class-wrld-total-learners.php';
			new WRLD_Total_Learners();

			// Pending Assignments.
			include_once 'class-wrld-pending-assignments.php';
			new WRLD_Pending_Assignments();

			// Revenue From Courses.
			include_once 'class-wrld-revenue-from-courses.php';
			new WRLD_Revenue_From_Courses();

			// Daily Enrollments.
			include_once 'class-wrld-daily-enrollments.php';
			new WRLD_Daily_Enrollments();

			// Report Filters.
			include_once 'class-wrld-report-filters.php';
			new WRLD_Report_Filters();

			// Time Spent on a course.
			include_once 'class-wrld-time-spent-on-course.php';
			new WRLD_Time_Spent_On_Course();

			// Quiz Completion time per course.
			include_once 'class-wrld-quiz-completion-time-per-course.php';
			new WRLD_Quiz_Completion_Time_Per_Course();

			// Quiz Completion Rate per Course.
			include_once 'class-wrld-quiz-completion-rate-per-course.php';
			new WRLD_Quiz_Completion_Rate_Per_Course();

			// Learners Pass Fail Rate per course.
			include_once 'class-wrld-learners-pass-fail-rate-per-course.php';
			new WRLD_Learners_Pass_Fail_Rate_Per_Course();

			// Course List Table.
			include_once 'class-wrld-course-list.php';
			new WRLD_Course_List();

			// Course Completion Rate.
			include_once 'class-wrld-course-completion-rate.php';
			new WRLD_Course_Completion_Rate();

			// Course Progress Rate.
			include_once 'class-wrld-course-progress-rate.php';
			new WRLD_Course_Progress_Rate();

			// Average Quiz Attempts.
			include_once 'class-wrld-average-quiz-attempts.php';
			new WRLD_Average_Quiz_Attempts();

			// Quiz Reports View.
			include_once 'class-wrld-quiz-reports.php';
			new WRLD_Quiz_Reports();

			// Inactive Users.
			include_once 'class-wrld-inactive-users.php';
			new WRLD_Inactive_Users();

			// Learner Activity Log.
			include_once 'class-wrld-learner-activity-log.php';
			new WRLD_Learner_Activity_Log();

			// Student Dashboard.
			include_once 'class-wrld-student-profile.php';
			new WRLD_Student_Profile();

			// Student Dashboard.
			// include_once 'class-wrld-student-filters.php';
			// new WRLD_Student_Filters();

			// includes/blocks/registry/class-wrld-student-table.php
			include_once 'class-wrld-student-table.php';
			new WRLD_Student_Table();
		}
	}
}
