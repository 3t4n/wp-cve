<?php
if (!class_exists('Tred_Filtered_Ld_Group')) {
    /**
     * Class for filtering LD Groups methods and gettings its stats.
     */
    class Tred_Filtered_Ld_Group
    {
        protected $id;
        protected $title;

        public function __construct($id, $title)
        {
            $this->id = $id;
            $this->title = $title;
        }

        public function get_id()
        {
            return $this->id;
        }

        public function get_title()
        {
            return $this->title;
        }

        public function get_group_users()
        {
            $output = [];
            $users = learndash_get_groups_users($this->id);
            if (!is_array($users)) {
                $output['error'] = __('Groups is not an array...', 'learndash-easy-dash');
                return $output;
            }
            $output['number'] = count($users);
            $group_users = [];
            if ($output['number'] > 0) {
                foreach ($users as $user_object) {
                    $user_display_name = $user_object->display_name;
                    $user_id = $user_object->ID;
                    $user_email = $user_object->user_email;
                    $user_firstname = $user_object->user_firstname;
                    $user_lastname = $user_object->user_lastname;
                    // $user_progress = learndash_get_user_group_progress($this->id, $user_id);
                    $group_users[] = [
                        'id' => $user_id,
                        'email' => $user_email,
                        'display_name' => $user_display_name,
                        'first_name' => $user_firstname,
                        'last_name' => $user_lastname,
                        // 'progress' => $user_progress,
                    ];
                }
            }
            $output['users'] = $group_users;
            return $output;
        }

        public function get_group_courses()
        {
            $output = [];
            $courses = learndash_group_enrolled_courses($this->id);
            if (!is_array($courses)) {
                $output['error'] = __('Courses is not an array...', 'learndash-easy-dash');
                return $output;
            }
            $output['number'] = count($courses);
            $group_courses_users = [];
            if ($output['number'] > 0) {
                foreach ($courses as $course_id) {
                    $course_title = get_the_title($course_id);
                    if (!$course_title) {
                        $course_title = 'no title';
                    }
                    $group_courses[] = [
                        'title' => $course_title,
                        'id' => $course_id,
                        'mode' => learndash_get_setting($course_id, "course_price_type"),
                    ];
                }
            }
            $output['courses'] = $group_courses;
            return $output;
        }

        public function get_group_quizzes()
        {
            $output = [];
            $quizzes = learndash_get_group_course_quiz_ids($this->id);
            if (!is_array($quizzes)) {
                $output['error'] = __('Quizzes is not an array...', 'learndash-easy-dash');
                return $output;
            }
            $output['number'] = count($quizzes);
            $output['quizzes'] = $quizzes;

            return $output;
        }

        public function get_group_administrators()
        {
            $output = [];
            //get group admins
            $group_admins = learndash_get_groups_administrators($this->id);
            if (!is_array($group_admins)) {
                $output['error'] = __('Group admins is not an array...', 'learndash-easy-dash');
                return $output;
            }
            $output['number'] = count($group_admins);
            $group_admins_users = [];
            if ($output['number'] > 0) {
                foreach ($group_admins as $user_object) {
                    $user_id = $user_object->ID;
                    $user_email = $user_object->user_email;
                    $group_admins_users[] = [
                        'id' => $user_id,
                        'email' => $user_email,
                        'display_name' => $user_object->display_name,
                    ];
                }
            }
            $output['admins'] = $group_admins_users;
            return $output;
        }

    } //end Class 
} //end if class exists
