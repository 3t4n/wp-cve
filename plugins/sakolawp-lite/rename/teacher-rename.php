<?php
if(is_page( 'myaccount' ) || $wp->request === "myaccount" ) {
    $title_parts['title'] = 'My Account';
}
elseif($wp->request === "waiting") {
    $title_parts['title'] = 'User Waiting Approval';
}
elseif($wp->request === "class_routine") {
    $title_parts['title'] = 'Class Routine';
}
elseif($wp->request === "homework") {
    $title_parts['title'] = 'Class Homework';
}
elseif($wp->request === "homeworkroom") {
    $title_parts['title'] = 'Class Homework Room';
}
elseif($wp->request === "homeworkroom_edit") {
    $title_parts['title'] = 'Homework Room Edit';
}
elseif($wp->request === "homeworkroom_details") {
    $title_parts['title'] = 'Homework Room Details';
}
elseif($wp->request === "questions_bank") {
    $title_parts['title'] = 'Questions Bank';
}
elseif($wp->request === "add_new_question") {
    $title_parts['title'] = 'Add Question';
}
elseif($wp->request === "edit_bank_question") {
    $title_parts['title'] = 'Edit Question';
}
elseif($wp->request === "online_exams") {
    $title_parts['title'] = 'Online Exams';
}
elseif($wp->request === "marks") {
    $title_parts['title'] = 'Marks';
}
elseif($wp->request === "manage_attendance") {
    $title_parts['title'] = 'Manage Attendance';
}
elseif($wp->request === "manage_attendance_view") {
    $title_parts['title'] = 'Manage Attendance View';
}
elseif($wp->request === "attendance_report") {
    $title_parts['title'] = 'Attendance Report';
}
elseif($wp->request === "report_attendance_view") {
    $title_parts['title'] = 'Attendance Report View';
}
elseif($wp->request === "exam_questions") {
    $title_parts['title'] = 'Exam Questions';
}
elseif($wp->request === "exam_edit") {
    $title_parts['title'] = 'Edit Exam';
}
elseif($wp->request === "exam_results") {
    $title_parts['title'] = 'Exam Results';
}
elseif($wp->request === "view_exam_result") {
    $title_parts['title'] = 'View Exam Result';
}
elseif($wp->request === "view_exam_question") {
    $title_parts['title'] = 'View Exam Question';
}
elseif($wp->request === "online_exams_done") {
    $title_parts['title'] = 'Online Exams done';
}
elseif($wp->request === "edit_profile") {
    $title_parts['title'] = 'Edit Profile';
}
elseif($wp->request === "examroom") {
    $title_parts['title'] = 'Exam Room';
}
elseif($wp->request === "my_routines") {
    $title_parts['title'] = 'My Routines';
}
elseif($wp->request === "view_bank_question") {
    $title_parts['title'] = 'View Question';
}

elseif($wp->request === "news_post") {
    $title_parts['title'] = 'News Posts';
}
elseif($wp->request === "event_post") {
    $title_parts['title'] = 'Event Posts';
}
elseif($wp->request === "view_homework_student") {
    $title_parts['title'] = 'View Homework Student';
}