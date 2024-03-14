<?php
if (!class_exists('Tred_Filtered_User')) {
    /**
     * Class for filtering user and gettings its stats.
     */
    class Tred_Filtered_User
    {
        protected $id;
        protected $data;
        protected $courses;
        protected $groups;



        public function __construct($id)
        {
            $this->id = $id;
            $this->data = get_userdata($id);
            $this->courses = learndash_get_user_course_access_list($id); //check open courses using user 815 as reference
            $this->groups = learndash_get_users_group_ids($id);
        }
        public function get_data()
        {
            return $this->data;
            //object(WP_User)#23896 (8) { ["data"]=> object(stdClass)#23842 (10) { ["ID"]=> string(3) "815" ["user_login"]=> string(6) "MMuniz" ["user_pass"]=> string(34) "$P$B4JDQE6P.iclgzZF4zsMkJ1LPPIXw20" ["user_nicename"]=> string(6) "mmuniz" ["user_email"]=> string(21) "marcopmuniz@gmail.com" ["user_url"]=> string(0) "" ["user_registered"]=> string(19) "2020-03-21 19:05:14" ["user_activation_key"]=> string(0) "" ["user_status"]=> string(1) "0" ["display_name"]=> string(6) "mmuniz" } ["ID"]=> int(815) ["caps"]=> array(2) { ["s2member_level3"]=> bool(true) ["bbp_participant"]=> bool(true) } ["cap_key"]=> string(15) "wp_capabilities" ["roles"]=> array(2) { [0]=> string(15) "s2member_level3" [1]=> string(15) "bbp_participant" } ["allcaps"]=> array(16) { ["read"]=> bool(true) ["level_0"]=> bool(true) ["access_s2member_level0"]=> bool(true) ["access_s2member_level1"]=> bool(true) ["access_s2member_level2"]=> bool(true) ["access_s2member_level3"]=> bool(true) ["spectate"]=> bool(true) ["participate"]=> bool(true) ["read_private_forums"]=> bool(true) ["publish_topics"]=> bool(true) ["edit_topics"]=> bool(true) ["publish_replies"]=> bool(true) ["edit_replies"]=> bool(true) ["assign_topic_tags"]=> bool(true) ["s2member_level3"]=> bool(true) ["bbp_participant"]=> bool(true) } ["filter"]=> NULL ["site_id":"WP_User":private]=> int(1) }
        }

        public function get_courses()
        {
            return learndash_user_get_enrolled_courses($this->id);
            //array(7) { [0]=> int(53392) [1]=> int(35165) [2]=> int(29175) [3]=> int(28398) [4]=> int(27596) [5]=> int(27504) [6]=> int(25448) }
        }

        public function get_groups_and_its_users()
        {
            $output = [];
            $groups = $this->groups;
            if (empty($groups)) {
                return $output;
            }
            foreach ($groups as $group) {
                $g = [];
                $g['group'] = $group;
                $g['users'] = count(learndash_get_groups_user_ids($group));
                $output[] = $g;
            }

            return $output;
            //array(2) { [0]=> array(2) { ["group"]=> int(25726) ["users"]=> int(2) } [1]=> array(2) { ["group"]=> int(25724) ["users"]=> int(2) } }
        }

        public function get_courses_from_meta()
        {
            return learndash_get_user_courses_from_meta($this->id);
            //array(6) { [0]=> int(25448) [1]=> int(27504) [2]=> int(28398) [3]=> int(29175) [4]=> int(27596) [5]=> int(35165) }
        }

        public function get_courses_from_groups()
        {
            return learndash_get_user_groups_courses_ids($this->id);
            //array(0) { }
        }

        public function get_course_points()
        {
            return learndash_get_user_course_points($this->id);
            //float(0)
        }

        public function get_quiz_attempts()
        {
            return learndash_get_user_quiz_attempts($this->id);
            //array(0) { } ???
        }

        public function get_courses_progress()
        {
            $keys = ['completed', 'total', 'last_id', 'status'];
            $courses_ids = $this->courses;
            $output = ['progress_numbers' => [], 'courses_data' => []];

            foreach ($courses_ids as $course_id) {
                $course_progress = learndash_user_get_course_progress($this->id, $course_id);
                //cast to array
                $course_progress = (array) $course_progress;
                $progress = array_intersect_key($course_progress, array_flip($keys));
                $output['progress_numbers'][$course_id] = $progress;
                $output['courses_data'][$course_id] = $course_progress;
            }

            return $output;
        }

        public function get_courses_activity()
        {
            $args = [
                // 'post_types'        => 'sfwd-courses',
                // 'activity_types'	=>	'course',
                // 'course_ids'        => $course_ids, 
                'user_ids' => [$this->id],
                'per_page' => 100,
            ];
            $activity = learndash_reports_get_activity($args, $this->id);
            if (!empty($activity['results'])) {
                return $activity['results'];
            }
            return [];
        }



        public function get_activity()
        {
            $output = [];
            $activity = learndash_report_get_activity_by_user_id($this->id);
            foreach ($activity as $a) {
                $output[$a] = learndash_get_activity_meta_fields($a);
            }
            return $output;
            // return learndash_get_user_activity( [ 'user_id' => $this->id ] );
        }

    } //end Class 
} //end if class exists