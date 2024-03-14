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
elseif($wp->request === "attendance_report") {
    $title_parts['title'] = 'Attendance Report';
}
elseif($wp->request === "report_attendance_view") {
    $title_parts['title'] = 'Attendance Report';
}
elseif($wp->request === "edit_profile") {
    $title_parts['title'] = 'Edit Profile';
}
elseif($wp->request === "online_exams") {
    $title_parts['title'] = 'Online Exams';
}
elseif($wp->request === "view_exam_result") {
    $title_parts['title'] = 'Exam Result';
}
elseif($wp->request === "news_post") {
    $title_parts['title'] = 'News Posts';
}
elseif($wp->request === "event_post") {
    $title_parts['title'] = 'Event Posts';
}
elseif($wp->request === "marks") {
    $title_parts['title'] = 'Marks';
}
elseif($wp->request === "view_mark") {
    $title_parts['title'] = 'View Mark';
}