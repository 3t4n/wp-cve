<?php
if (!class_exists('Tred_Filtered_Ld_Item')) {
    /**
     * Class for filtering LD items methods and gettings its stats.
     */
    class Tred_Filtered_Ld_Item
    {
        protected $id;
        protected $type;
        protected $title;
        protected $all_time_activity;

        public function __construct($type, $id, $title)
        {
            $this->type = $type;
            $this->id = $id;
            $this->title = $title;
            $this->all_time_activity = $this->get_all_time_activity();
        }

        public function get_id()
        {
            return $this->id;
        }

        public function get_type()
        {
            return $this->type;
        }

        public function get_title()
        {
            return $this->title;
        }

        public function get_course_content_numbers()
        {
            $output = [];
            if ($this->type !== 'sfwd-courses') {
                $output['error'] = __('This method is only available for courses...', 'learndash-easy-dash');
                return $output;
            }
            $output['lessons'] = 0;
            $output['topics'] = 0;
            $output['quizzes'] = 0;
            $steps = learndash_get_course_steps($this->id, ['sfwd-lessons', 'sfwd-topic', 'sfwd-quiz']);
            if (!is_array($steps)) {
                $output['error'] = __('Steps is not an array...', 'learndash-easy-dash');
                return $output;
            }
            foreach ($steps as $step) {
                $pt = get_post_type($step);
                if ($pt === 'sfwd-lessons') {
                    $output['lessons']++;
                } else if ($pt === 'sfwd-topic') {
                    $output['topics']++;
                } else if ($pt === 'sfwd-quiz') {
                    $output['quizzes']++;
                }
            }
            $output['total'] = $output['lessons'] + $output['topics'];
            return $output;
        }

        public function get_course_groups()
        {
            $output = [];
            if ($this->type !== 'sfwd-courses') {
                $output['error'] = __('This method is only available for courses...', 'learndash-easy-dash');
                return $output;
            }
            $groups = learndash_get_course_groups($this->id);
            if (!is_array($groups)) {
                $output['error'] = __('Groups is not an array...', 'learndash-easy-dash');
                return $output;
            }
            $output['number'] = count($groups);
            $course_groups_users = [];
            if ($output['number'] > 0) {
                foreach ($groups as $group_id) {
                    $group_title = get_the_title($group_id);
                    if (!$group_title) {
                        $group_title = 'no title';
                    }
                    $group_members_count = count(learndash_get_groups_user_ids($group_id));
                    $course_groups_users[] = [
                        'title' => $group_title,
                        'users' => $group_members_count
                    ];
                }
            }
            $output['groups'] = $course_groups_users;
            return $output;
        }





        public function get_course_users()
        //not beeing used at the moment 
        {
            $output = [];
            if ($this->type !== 'sfwd-courses') {
                $output['error'] = __('This method is only available for courses...', 'learndash-easy-dash');
                return $output;
            }
            $users = tred_get_students_number($this->id);
            if (!is_numeric($users)) {
                $output['error'] = __('Users var is not numeric...', 'learndash-easy-dash');
                return $output;
            }
            $output['number'] = intval($users);
            return $output;
        }

        protected function get_all_time_activity()
        {
            $post_id = $this->id;
            $type = $this->type;
            $is_course = $type === 'sfwd-courses';
            $activity = tred_learndash_get_item_all_time_activity($post_id, $hours = TRED_CACHE_X_HOURS, $force_refresh = false, $is_course);
            return $activity;
        }

        public function get_course_activity()
        {
            $output = [];
            if ($this->type !== 'sfwd-courses') {
                $output['error'] = __('This method is only available for courses...', 'learndash-easy-dash');
                return $output;
            }
            $types = ['course', 'lesson', 'topic', 'quiz'];
            foreach ($types as $type) {
                $output[$type . '_started'] = [];
                $output[$type . '_started']['completed'] = [];
                $output[$type . '_started']['uncompleted'] = [];
            }
            // return $output;

            $activity = $this->all_time_activity;

            foreach ($activity as $obj) {
                // return $obj;
                //stdClass Object ( [activity_id] => 21807 [user_id] => 2237 [post_id] => 35626 [course_id] => 35626 [activity_type] => access [activity_status] => [activity_started] => 1588944865 [activity_completed] => [activity_updated] => 1588944865 )
                //rank lessons, topics, quizzes
                $type = $obj->activity_type;
                if (in_array($type, $types) && !empty($obj->activity_started)) {

                    $item_array = [
                        'id' => intval($obj->post_id),
                        'started_at' => intval($obj->activity_started),
                        'user_id' => intval($obj->user_id),
                        'completed_at' => 0,
                    ];
                    if ($obj->activity_status == 1 && !empty($obj->activity_completed)) {
                        $item_array['completed_at'] = intval($obj->activity_completed);
                        $output[$type . '_started']['completed'][] = $item_array;
                    } else {
                        $output[$type . '_started']['uncompleted'][] = $item_array;
                    }
                }
            } //end foreach

            return $output;
        }

        public function dissec_course_lessons_by_activity_completed()
        {
            if ($this->type !== 'sfwd-courses') {
                $output['error'] = __('This method is only available for courses...', 'learndash-easy-dash');
                return $output;
            }
            $activity = $this->get_course_activity();
            return tred_dissec_item_activity_completed($activity['lesson_started']);
        }


        public function get_stats_over_time()
        {
            $output = tred_ld_items_stats_over_time($this->id);
            if (!empty($output['id'])) {
                $output['id'] = 'chart-filtered-item-stats-over-time';
            } else {
                $output = [];
                $output['error'] = __('No data found...', 'learndash-easy-dash');
            }
            return $output;
        }

        private function get_students_array()
        {
            $users_query = learndash_get_users_for_course($this->id, [], (bool) TRED_EXCLUDE_ADMINS_FROM_COURSE_USERS);
            if (empty($users_query)) {
                return [];
            }
            return $users_query->results;
        }


        //TODO: TRY TO GET EVERYTHING FOR THE FILTER PAGE FROM THIS FUNCTION
        public function get_students_stats_for_course()
        {
            $output = [];

            if ($this->type !== 'sfwd-courses') {
                $output['error'] = __('This method is only available for courses...', 'learndash-easy-dash');
                return $output;
            }
            $students_array = $this->get_students_array();

            //get wp user meta value
            foreach ($students_array as $user_id) {
                $p = (array) learndash_user_get_course_progress($user_id, $this->id);
                //get user email by id
                $user = get_user_by('id', $user_id);
                $user_data = [
                    'id' => intval($user_id),
                    'email' => $user->user_email,
                    'first_name' => $user->user_firstname,
                    'last_name' => $user->user_lastname,
                    'status' => $p['status'] ?? '',
                    //TODO: see learndash_course_status function and find a way to get the translated status
                    'completed' => intval($p['completed']),
                    'total' => intval($p['total']),
                    'percentage' => tred_percentage($p['completed'], $p['total'], 2)
                ];
                $time_spent_in_course = ($user_data['completed'] > 0) ? learndash_get_user_course_attempts_time_spent($user_id, $this->id) : 0;
                $user_data['time_spent_in_course'] = $time_spent_in_course;

                $output[] = $user_data;

            }
            return $output;
        }

        public function get_course_completion_stats()
        {
            //todo: take to the boxes
            $output = [];
            $stats = tred_ld_courses_completions_stats($this->id);
            if (!empty($stats['courses']) && !empty($stats['courses'][$this->id])) {
                $output['students'] = (!empty($stats['courses'][$this->id]['students'])) ? $stats['courses'][$this->id]['students'] : (int) tred_get_students_number($this->id);
                $output['mode'] = (!empty($stats['courses'][$this->id]['mode'])) ? $stats['courses'][$this->id]['mode'] : learndash_get_setting($this->id, 'course_price_type');
                $output['total_completed'] = (!empty($stats['courses'][$this->id]['total_completed'])) ? $stats['courses'][$this->id]['total_completed'] : 0;
                $output['total_completed_percentage'] = (!empty($stats['courses'][$this->id]['total_completed_percentage'])) ? $stats['courses'][$this->id]['total_completed_percentage'] : 0;
                $output['average_days'] = (!empty($stats['courses'][$this->id]['average_days'])) ? $stats['courses'][$this->id]['average_days'] : 0;
            } else {
                $output['students'] = (int) tred_get_students_number($this->id);
                $output['mode'] = learndash_get_setting($this->id, 'course_price_type');
                $output['total_completed'] = 0;
                $output['total_completed_percentage'] = 0;
                $output['average_days'] = 0;
            }

            if (!empty($stats['same_day'])) {
                $output['same_day'] = (!empty($stats['same_day']['total'])) ? intval($stats['same_day']['total']) : 0;
                $output['same_day_average_minutes'] = (!empty($stats['same_day']['average_minutes'])) ? intval($stats['same_day']['average_minutes']) : 0;
            } else {
                $output['same_day'] = 0;
                $output['same_day_average_minutes'] = 0;
            }
            return $output;
        }

    } //end Class 
} //end if class exists
