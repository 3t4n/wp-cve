<?php
include_once('translations.php');

//UTILITY FUNCTIONS
function tred_remove_amp($string)
{
  $string = str_replace('&amp;', '&', $string);
  return $string;
}

function tred_get_plugin_basic_version($string_version)
{
  //get first two numbers
  $string_version = preg_replace('/[^0-9]/', '', $string_version);
  return substr($string_version, 0, 2);
}

function tred_year_month_numbers_to_string_month_slash_year($year_month)
{
  if (empty($year_month)) {
    return '';
  }
  $tredMonths = [
    1 => __('jan', 'learndash-easy-dash'),
    2 => __('feb', 'learndash-easy-dash'),
    3 => __('mar', 'learndash-easy-dash'),
    4 => __('apr', 'learndash-easy-dash'),
    5 => __('may', 'learndash-easy-dash'),
    6 => __('jun', 'learndash-easy-dash'),
    7 => __('jul', 'learndash-easy-dash'),
    8 => __('aug', 'learndash-easy-dash'),
    9 => __('sep', 'learndash-easy-dash'),
    10 => __('oct', 'learndash-easy-dash'),
    11 => __('nov', 'learndash-easy-dash'),
    12 => __('dec', 'learndash-easy-dash'),
  ];
  $array = explode('_', $year_month);
  $year = substr($array[0], -2);
  $month = intval($array[1]);
  $month_ext = $tredMonths[$month];
  return "$month_ext/$year";
}

function tred_check_mode_existent_widget($widget_title, $modes)
{
  $words = explode(' ', $widget_title);
  $mode = $words[0];
  return in_array(strtolower($mode), $modes);
}

//https://stackoverflow.com/questions/8230538/pass-extra-parameters-to-usort-callback
function tred_sort_desc(&$arrayToSort, $key)
{
  usort($arrayToSort, function ($a, $b) use ($key) {
    return $b[$key] - $a[$key];
  });
}

function tred_get_from_array_or_object($array, $key, $default_value = 0)
{
  if (is_array($array) && array_key_exists($key, $array)) {
    return $array[$key];
  }
  if (is_object($array) && isset($array->{$key})) {
    return $array->{$key};
  }
  return $default_value;
}

//Get this mont and last 12 months and year
function tred_get_last_12_months_and_year_array()
{
  $output = [];
  for ($i = 0; $i <= 12; $i++) {
    $item = [];
    $year_month = date("Y_m", strtotime(date('Y-m-01') . " -$i months"));
    $item['year_month'] = $year_month;
    $array = explode("_", $year_month);
    $item['year'] = $array[0];
    $item['month'] = $array[1];
    $output[] = $item;
  }
  return $output;
}

function tred_check_if_timestamp_belongs_to_year_month($date, $year_month)
{
  return date('Y_m', intval($date)) === $year_month;
}

function tred_percentage($partial, $total, $decimals = 2)
{
  if (empty($partial) || empty($total)) {
    return '';
  }
  return round(($partial / $total) * 100, $decimals) . "%";
}

function tred_timestamp($seconds, $unity = '')
{
  $not_availabe = __('N/A', 'learndash-easy-dash');
  if ((int) $seconds < 0) {
    return [
      'days' => $not_availabe,
      'hours' => $not_availabe,
      'minutes' => $not_availabe,
    ];
  }
  $output = [];
  $output['days'] = 0;
  $output['hours'] = 0;
  $output['minutes'] = 0;
  if (is_numeric($seconds)) {
    $output['days'] = round((int) $seconds / (60 * 60 * 24), 1);
    $output['hours'] = round((int) $seconds / (60 * 60), 1);
    $output['minutes'] = round((int) $seconds / 60, 1);
  }
  if (in_array($unity, ['days', 'hours', 'minutes'])) {
    return $output[$unity];
  }
  return $output;
}
// END - UTILITY FUNCTIONS

/*
 * NUMBER OF STUDENTS ENROLLED
 */
function tred_get_students_number($course_id, $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{
  $tred_get_students_number = get_transient('tred_get_students_number_' . $course_id);
  if (!$force_refresh && $tred_get_students_number) {
    return $tred_get_students_number;
  }

  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;

  $course_enrolled_students_number = get_transient('tred_get_students_number_' . $course_id);
  if (true === $force_refresh || false === $course_enrolled_students_number) {
    $members_arr = learndash_get_users_for_course($course_id, [], (bool) TRED_EXCLUDE_ADMINS_FROM_COURSE_USERS);
    if (($members_arr instanceof \WP_User_Query) && (property_exists($members_arr, 'total_users')) && (!empty($members_arr->total_users))) {
      $course_enrolled_students_number = $members_arr->total_users;
    } else {
      $course_enrolled_students_number = 0;
    }
    set_transient('tred_get_students_number_' . $course_id, $course_enrolled_students_number, $transient_expire_time);
  }
  return (int) $course_enrolled_students_number;
}

//Get all access modes existent by courses
function tred_get_access_modes_existent($only_published = true, $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{

  $tred_get_access_modes_existent = get_transient('tred_get_access_modes_existent');
  if (!$force_refresh && $tred_get_access_modes_existent) {
    return $tred_get_access_modes_existent;
  }
  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;
  $output = [];
  $args = [
    'post_type' => 'sfwd-courses',
    'fields' => 'ids',
    'numberposts' => -1
  ];
  if ($only_published) {
    $args['post_status'] = 'publish';
  }
  $courses_posts = get_posts($args);
  if (empty($courses_posts)) {
    return $output;
  }
  foreach ($courses_posts as $course_id) {
    $access_mode = get_post_meta($course_id, '_ld_price_type', true);
    if (!in_array($access_mode, $output)) {
      $output[] = $access_mode;
    }
  } //end foreach
  set_transient('tred_get_access_modes_existent', $output, $transient_expire_time);
  return $output;
}

//Get total number of students, courses and students in each course
function tred_get_students_number_all_courses($only_published = true, $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{
  $tred_get_students_number_all_courses = get_transient('tred_get_students_number_all_courses');
  if (!$force_refresh && $tred_get_students_number_all_courses) {
    return $tred_get_students_number_all_courses;
  }
  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;
  $output = [];
  $courses = [];
  $students = [];

  $args = [
    'post_type' => 'sfwd-courses',
    'fields' => 'ids',
    'numberposts' => -1
  ];
  if ($only_published) {
    $args['post_status'] = 'publish';
  }
  $courses_posts = get_posts($args);

  $courses['total'] = count($courses_posts);

  $items = [];
  $total_students = 0;
  foreach ($courses_posts as $course_id) {
    $access_mode = get_post_meta($course_id, '_ld_price_type', true);
    $c = [];
    $c['title'] = get_the_title($course_id);
    $c['students'] = (int) tred_get_students_number($course_id, 6, $force_refresh);
    $c['students_completed'] = (int) tred_get_users_completed_number($course_id);
    if (empty($total_students) && $access_mode === 'open' && !empty($c['students'])) {
      //if the course is open, all students can access it, so we have the total
      $total_students = $c['students'];
    }
    if (!isset($items[$access_mode])) {
      $items[$access_mode] = [];
    }
    $items[$access_mode][] = $c;
  } //end foreach

  $students['total'] = (!empty($total_students)) ? $total_students : learndash_students_enrolled_count();
  $courses['items'] = $items;
  $output['courses'] = $courses;
  $output['students'] = $students;
  set_transient('tred_get_students_number_all_courses', $output, $transient_expire_time);

  return $output;
}

//Get total number of lessons, topics and quizzes
function tred_get_lessons_topics_quizzes_number($only_published = true, $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{
  $tred_get_lessons_topics_quizzes_number = get_transient('tred_get_lessons_topics_quizzes_number');
  if (!$force_refresh && $tred_get_lessons_topics_quizzes_number) {
    return $tred_get_lessons_topics_quizzes_number;
  }

  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;
  $output = [];
  $output['lessons'] = 0;
  $output['topics'] = 0;
  $output['quizzes'] = 0;

  $args = [
    'post_type' => ['sfwd-lessons', 'sfwd-topic', 'sfwd-quiz'],
    'numberposts' => -1
  ];
  if ($only_published) {
    $args['post_status'] = 'publish';
  }
  $ld_posts = get_posts($args);
  if (empty($ld_posts)) {
    return $output;
  }
  foreach ($ld_posts as $post) {
    if ($post->post_type == 'sfwd-lessons') {
      $output['lessons'] += 1;
    } else if ($post->post_type == 'sfwd-topic') {
      $output['topics'] += 1;
    } else if ($post->post_type == 'sfwd-quiz') {
      $output['quizzes'] += 1;
    }
  }
  set_transient('tred_get_lessons_topics_quizzes_number', $output, $transient_expire_time);
  return $output;
}

//Get total number of students, groups and students in each group
function tred_get_students_number_all_groups($only_published = false, $force_refresh = false)
{
  $output = [];
  $groups = [];
  $students = [];

  $args = array(
    'post_type' => 'groups',
    'nopaging' => true,
    'post_status' => array('publish', 'pending', 'draft', 'future', 'private'),
    'fields' => 'ids'
  );

  if ($only_published) {
    $args['post_status'] = 'publish';
  }
  $groups_posts = get_posts($args);

  $groups['total'] = count($groups_posts);

  $items = [];
  $students_sum = 0;
  foreach ($groups_posts as $group_id) {
    $c = [];
    $c['title'] = get_the_title($group_id);
    $c['students'] = count(learndash_get_groups_user_ids($group_id, $force_refresh));
    $students_sum += $c['students'];
    $items[] = $c;
  }
  $students['total'] = $students_sum;
  $groups['items'] = $items;
  $output['groups'] = $groups;
  $output['students'] = $students;
  return $output;
}

//Get courses completions (all time)
//Pass course_id and get results just for one course
function tred_learndash_get_course_completions($course_id = 0, $days = 0, $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{
  if (!empty($course_id) && !is_numeric($course_id)) {
    return false;
  }
  if (!empty($days) && !is_numeric($days)) {
    return false;
  }
  $transient_name = (!empty($course_id)) ? 'tred_learndash_get_course_completions_' . $course_id : 'tred_learndash_get_course_completions';

  $tred_learndash_get_course_completions = get_transient($transient_name);
  if (!$force_refresh && $tred_learndash_get_course_completions) {
    return $tred_learndash_get_course_completions;
  }

  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;
  global $wpdb;
  $where_course_completed = 'activity_type = "course"';
  if (!empty($course_id)) {
    $where_course_completed .= ' AND course_id = ' . $course_id;
  }
  $where_course_completed .= ' AND activity_completed IS NOT NULL AND activity_completed != "" ';
  $where_course_completed .= ' AND activity_started IS NOT NULL AND activity_started != "" ';
  if ($days) {
    $where_course_completed .= 'AND DATEDIFF(NOW(), FROM_UNIXTIME(activity_completed)) < %d';
  }

  $sql_select = 'SELECT * FROM ' . esc_sql(LDLMS_DB::get_table_name('user_activity')) . ' WHERE ';
  $sql_select .= $where_course_completed;
  if ($days) {
    $sql_str = $wpdb->prepare($sql_select, $days);
    $activity = $wpdb->get_results($sql_str);
  } else {
    $activity = $wpdb->get_results($sql_select);
  }

  if ($activity) {
    set_transient($transient_name, $activity, $transient_expire_time);
    return $activity;
  }
  return false;
}

function tred_learndash_get_course_completions_stats($course_id = 0, $days = 0)
{
  $output = [];
  $output['negatives'] = 0;
  $output['zeros'] = 0;
  $output['total'] = 0;
  $output['total_seconds'] = 0;
  $output['average_seconds'] = 0;
  $output['average_minutes'] = 0;
  $output['average_hours'] = 0;
  $output['average_days'] = 0;
  $output['courses'] = [];

  $output['same_day'] = [];
  $output['same_day']['total'] = 0;
  $output['same_day']['total_seconds'] = 0;
  $output['same_day']['average_seconds'] = 0;
  $output['same_day']['average_minutes'] = 0;
  $output['same_day']['average_hours'] = 0;
  $output['same_day']['courses'] = [];

  $completions = tred_learndash_get_course_completions($course_id, $days);
  if (!$completions || !is_array($completions)) {
    return $output;
  }

  foreach ($completions as $comp) {

    $course_diff_seconds = $comp->activity_completed - $comp->activity_started;
    if ($course_diff_seconds == 0) {
      //started and completed at the same time? Impossible... Anyway, let's forget it
      $output['zeros'] += 1;
      continue;
    }
    if ($course_diff_seconds < 0) {
      //student must be restarting course...Let's forget it
      $output['negatives'] += 1;
      continue;
    }
    $course_title = get_the_title($comp->course_id);
    if (empty($course_title)) {
      //maybe the course was removed...Let's forget it
      continue;
    }

    $output['total'] += 1;
    $output['total_seconds'] += $course_diff_seconds;
    $course_diff_days = tred_timestamp($course_diff_seconds, 'days');

    if ($course_diff_days == 0) {
      //student started and completed course in the same day
      $output['same_day']['total'] += 1;
      $output['same_day']['total_seconds'] += $course_diff_seconds;
      if (empty($output['same_day']['courses'][$course_title])) {
        $output['same_day']['courses'][$course_title] = 0;
      }
      $output['same_day']['courses'][$course_title] += 1;
    }

    if (empty($output['courses'][$comp->course_id])) {
      $output['courses'][$comp->course_id]['id'] = $comp->course_id;
      $output['courses'][$comp->course_id]['title'] = $course_title;
      $output['courses'][$comp->course_id]['students'] = (int) tred_get_students_number($comp->course_id);
      $output['courses'][$comp->course_id]['mode'] = get_post_meta($comp->course_id, '_ld_price_type', true);
      $output['courses'][$comp->course_id]['total_completed'] = 0;
      $output['courses'][$comp->course_id]['total_seconds'] = 0;
      $output['courses'][$comp->course_id]['average_seconds'] = 0;
      $output['courses'][$comp->course_id]['user_data'] = [];
    }
    $user_data = [];
    $user_data['user_id'] = $comp->user_id;
    $user_data['started'] = $comp->activity_started;
    $user_data['completed'] = $comp->activity_completed;
    $user_data['seconds_to_complete'] = $course_diff_seconds;

    $output['courses'][$comp->course_id]['total_completed'] += 1;
    $output['courses'][$comp->course_id]['total_seconds'] += $course_diff_seconds;
    $output['courses'][$comp->course_id]['average_seconds'] = $output['courses'][$comp->course_id]['total_seconds'] / $output['courses'][$comp->course_id]['total_completed'];
    $output['courses'][$comp->course_id]['user_data'][] = $user_data;
  } //End foreach

  foreach ($output['courses'] as $course_id => $val) {
    $output['courses'][$course_id]['total_completed_percentage'] = tred_percentage($output['courses'][$course_id]['total_completed'], $output['courses'][$course_id]['students']);
    $output['courses'][$course_id]['average_days'] = tred_timestamp($output['courses'][$course_id]['average_seconds'], $unity = 'days');
  }

  $output['same_day']['average_seconds'] = (!empty($output['same_day']['total'])) ? $output['same_day']['total_seconds'] / $output['same_day']['total'] : 0;
  $converted = tred_timestamp($output['same_day']['average_seconds']);
  $output['same_day']['average_minutes'] = $converted['minutes'];
  $output['same_day']['average_hours'] = $converted['hours'];

  $output['average_seconds'] = (!empty($output['total'])) ? $output['total_seconds'] / $output['total'] : 0;
  $converted = tred_timestamp($output['average_seconds']);
  $output['average_minutes'] = $converted['minutes'];
  $output['average_hours'] = $converted['hours'];
  $output['average_days'] = $converted['days'];

  return $output;
}

//Get activity on the last $days
function tred_learndash_get_activity($days = TRED_LAST_X_DAYS, $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{
  $tred_learndash_get_activity = get_transient('tred_learndash_get_activity_' . $days . '_days');
  if (!$force_refresh && $tred_learndash_get_activity) {
    return $tred_learndash_get_activity;
  }

  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;

  global $wpdb;
  $sql_select = 'SELECT * FROM ' . esc_sql(LDLMS_DB::get_table_name('user_activity')) . ' WHERE activity_updated IS NOT NULL AND activity_updated != ""';
  $sql_prepared = $sql_select;
  if (!empty($days) && is_numeric($days) && $days > 0) {
    $sql_select .= ' AND DATEDIFF(NOW(), FROM_UNIXTIME(activity_updated)) < %d';
    $sql_prepared = $wpdb->prepare($sql_select, $days);
  }
  $activity = $wpdb->get_results($sql_prepared);
  if ($activity) {
    set_transient('tred_learndash_get_activity_' . $days . '_days', $activity, $transient_expire_time);
  }
  return $activity;
}

//Get all time activity for a LD item
function tred_learndash_get_item_all_time_activity($post_id, $hours = TRED_CACHE_X_HOURS, $force_refresh = false, $course = false)
{
  $tred_learndash_get_item_all_time_activity = get_transient('tred_learndash_get_item_all_time_activity_' . $post_id);
  if (!$force_refresh && $tred_learndash_get_item_all_time_activity) {
    return $tred_learndash_get_item_all_time_activity;
  }
  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;
  global $wpdb;
  $item = ($course) ? 'course_id' : 'post_id';
  $sql_select = 'SELECT * FROM ' . esc_sql(LDLMS_DB::get_table_name('user_activity')) . ' WHERE activity_updated IS NOT NULL AND activity_updated != "" AND ' . $item . ' = %d';
  $sql_str = $wpdb->prepare($sql_select, $post_id);
  $activity = $wpdb->get_results($sql_str);
  if ($activity) {
    set_transient('tred_learndash_get_item_all_time_activity_' . $post_id, $activity, $transient_expire_time);
  }
  return $activity;
}

function tred_dissec_item_activity_completed($activity)
{
  $output = [];
  $output['items_completed'] = ['total' => 0, 'items' => []];
  if (empty($activity)) {
    return $output;
  }
  $items_titles = [];
  $items = [];
  $items['completed'] = [];
  $output['items_completed']['total'] = count($activity['completed']);


  if ($output['items_completed']['total']) {
    foreach ($activity['completed'] as $item) {
      if (empty($items_titles[$item['id']])) {
        $items_titles[$item['id']] = get_the_title($item['id']);
      }
      if (empty($items['completed'][$item['id']])) {
        $items['completed'][$item['id']] = [];
        $items['completed'][$item['id']]['title'] = $items_titles[$item['id']];
        $items['completed'][$item['id']]['times'] = 1;
      } else {
        $items['completed'][$item['id']]['times']++;
      }

    } //end if

    //order array by subarray value
    // uasort($items['completed'], function ($a, $b) {
    //   return $a['times'] < $b['times'];
    // });

    //3.4.1 (above is deprecated)
    uasort($items['completed'], function ($a, $b) {
      // You should compare $a['times'] and $b['times'] here and return -1, 0, or 1.
      if ($a['times'] == $b['times']) {
        return 0; // They are equal
      }
      // If you want an ascending sort order, use the line below:
      // return ($a['times'] < $b['times']) ? -1 : 1;
      // For descending sort order (as it seems you are trying to compare from greatest to least):
      return ($a['times'] < $b['times']) ? 1 : -1;
    });

    $output['items_completed']['items'] = $items['completed'];
  } //end foreach

  return $output;
}


function tred_learndash_rank_courses_by_activity($activity)
{
  if (!is_array($activity)) {
    return false;
  }
  $keys = ['course_id', 'total', 'course', 'lesson', 'topic', 'quiz'];
  $actions = ['enrolls', 'starts', 'completions'];

  $courses = [];
  foreach ($activity as $act) {

    if (empty($act->activity_type) || empty($act->course_id)) {
      continue;
    }

    //Setting the course_id and its tree
    if (!isset($courses[$act->course_id])) {
      $courses[$act->course_id] = [];
      foreach ($keys as $t) {
        if ($t == 'total') {
          $courses[$act->course_id][$t] = 0;
        } else if ($t == 'course_id') {
          $courses[$act->course_id][$t] = $act->course_id;
        } else {
          $courses[$act->course_id][$t] = [];
          foreach ($actions as $a) {
            $courses[$act->course_id][$t][$a] = 0;
          } //end subinner foreach (actions)
        } //end if/else ($t == 'total') 
      } //end inner foreach (keys) 
    } //end if/else (!isset($courses[$act->course_id])) 

    $courses[$act->course_id]['total'] += 1;

    if ($act->activity_type === 'access') {
      $courses[$act->course_id]['course']['enrolls'] += 1;
      continue;
    }

    $key = (empty($act->activity_completed)) ? 'starts' : 'completions';
    $courses[$act->course_id][$act->activity_type][$key] += 1;
  } //end foreach

  usort($courses, function ($a, $b) {
    return $b['total'] - $a['total'];
  });

  return $courses;
}


function tred_learndash_rank_courses_items_by_completion($activity)
{
  if (!is_array($activity)) {
    return false;
  }

  $courses = [];
  $lessons = [];
  $topics = [];
  $quizzes = [];
  $keys = ['id', 'title', 'total'];


  foreach ($activity as $act) {

    if (empty($act->activity_type) || empty($act->activity_completed)) {
      continue;
    }

    $activity_type = $act->activity_type;

    if ($activity_type == 'course' && !empty($act->course_id)) {
      //Setting the course_id and its tree
      if (!isset($courses[$act->course_id])) {
        $courses[$act->course_id] = [];
        $courses[$act->course_id]['total'] = 0;
        $courses[$act->course_id]['id'] = $act->course_id;
        $courses[$act->course_id]['title'] = get_the_title($act->course_id);
      }
      $courses[$act->course_id]['total'] += 1;
    }

    if ($activity_type == 'lesson' && !empty($act->post_id)) {
      //Setting the post_id and its tree
      if (!isset($lessons[$act->post_id])) {
        $lessons[$act->post_id] = [];
        $lessons[$act->post_id]['total'] = 0;
        $lessons[$act->post_id]['id'] = $act->post_id;
        $lessons[$act->post_id]['title'] = get_the_title($act->post_id);
      }
      $lessons[$act->post_id]['total'] += 1;
    }

    if ($activity_type == 'topic' && !empty($act->post_id)) {
      //Setting the post_id and its tree
      if (!isset($topics[$act->post_id])) {
        $topics[$act->post_id] = [];
        $topics[$act->post_id]['total'] = 0;
        $topics[$act->post_id]['id'] = $act->post_id;
        $topics[$act->post_id]['title'] = get_the_title($act->post_id);
      }
      $topics[$act->post_id]['total'] += 1;
    }

    if ($activity_type == 'quiz' && !empty($act->post_id)) {
      //Setting the post_id and its tree
      if (!isset($quizzes[$act->post_id])) {
        $quizzes[$act->post_id] = [];
        $quizzes[$act->post_id]['total'] = 0;
        $quizzes[$act->post_id]['id'] = $act->post_id;
        $quizzes[$act->post_id]['title'] = get_the_title($act->post_id);
      }
      $quizzes[$act->post_id]['total'] += 1;
    }

  } //end foreach


  usort($courses, function ($a, $b) {
    return $b['total'] - $a['total'];
  });

  usort($lessons, function ($a, $b) {
    return $b['total'] - $a['total'];
  });

  usort($topics, function ($a, $b) {
    return $b['total'] - $a['total'];
  });

  usort($quizzes, function ($a, $b) {
    return $b['total'] - $a['total'];
  });

  $output = [
    'courses' => $courses,
    'lessons' => $lessons,
    'topics' => $topics,
    'quizzes' => $quizzes
  ];

  return $output;
}


function is_in_the_last_x_days($timestamp, $days = TRED_LAST_X_DAYS)
{
  if (!is_numeric($timestamp) || !is_numeric($days)) {
    return false;
  }
  if ($days <= 0) {
    //all time
    return true;
  }
  $startDate = new DateTime("-$days days");
  $dt = new DateTime();
  $dt->setTimestamp($timestamp);
  if ($dt > $startDate) {
    return true;
  }
  return false;
}


//Get user activities on the last $days
function tred_learndash_get_users_all_activities($activity)
{
  if (!is_array($activity)) {
    return false;
  }

  $users = [];
  $keys = ['user_id', 'total', 'course', 'lesson', 'topic', 'quiz'];
  $actions = ['enrolls', 'starts', 'completions'];

  foreach ($activity as $key => $act) {

    if (empty($act->activity_type) || empty($act->user_id)) {
      continue;
    }

    //Setting the user_id and its tree
    if (!isset($users[$act->user_id])) {
      $users[$act->user_id] = [];
      foreach ($keys as $t) {
        if ($t == 'total') {
          $users[$act->user_id][$t] = 0;
        } else if ($t == 'user_id') {
          $users[$act->user_id][$t] = $act->user_id;
        } else {
          $users[$act->user_id][$t] = [];
          foreach ($actions as $a) {
            $users[$act->user_id][$t][$a] = 0;
          } //end subinner foreach (actions)
        } //end if/else ($t == 'total') 
      } //end inner foreach (keys) 
    } //end if/else (!isset($users[$act->user_id])) 

    $users[$act->user_id]['total'] += 1;

    if ($act->activity_type == 'access') {
      if (isset($users[$act->user_id]['course']['enrolls'])) {
        $users[$act->user_id]['course']['enrolls'] += 1;
      } else {
        $users[$act->user_id]['course']['enrolls'] = 1;
      }
      continue;
    }

    if (!empty($act->activity_started) && is_in_the_last_x_days($act->activity_started)) {
      if (isset($users[$act->user_id][$act->activity_type]['starts'])) {
        $users[$act->user_id][$act->activity_type]['starts'] += 1;
      } else {
        $users[$act->user_id][$act->activity_type]['starts'] = 1;
      }
    }

    if (!empty($act->activity_completed) && is_numeric($act->activity_completed)) {
      if (isset($users[$act->user_id][$act->activity_type]['completions'])) {
        $users[$act->user_id][$act->activity_type]['completions'] += 1;
      } else {
        $users[$act->user_id][$act->activity_type]['completions'] = 1;
      }
    }
  } //end foreach
  return $users;
}


function tred_learndash_rank_users_all_activities($activity)
{
  $users = tred_learndash_get_users_all_activities($activity);

  if (empty($users) || !is_array($users)) {
    return false;
  }

  usort($users, function ($a, $b) {
    return $b['total'] - $a['total'];
  });

  $output = [
    'emails' => [],
    'enrolls' => [],
    'starts' => [],
    'completions' => [],
    'totals' => []
  ];

  $keys = ['course', 'lesson', 'topic', 'quiz'];
  foreach ($users as $data) {
    $user = get_user_by('id', $data['user_id']);

    if (!$user || $user->has_cap('edit_posts')) {
      continue;
    }

    $user_email = $user->user_email;
    $output['emails'][] = $user_email;
    $output['totals'][] = $data['total'];
    $output['enrolls'][] = $data['course']['enrolls'];
    $starts = 0;
    $completions = 0;
    foreach ($keys as $key) {
      $starts += $data[$key]['starts'];
      $completions += $data[$key]['completions'];
    }
    $output['starts'][] = $starts;
    //     $output['starts'][] = $data['lesson']['starts']+$data['topic']['starts']+$data['quiz']['starts'];
    $output['completions'][] = $completions;
    //     $output['totals'][] = $starts + $completions;
  } //end outter foreach

  return $output;
}


function tred_learndash_get_item_all_activities($activity, $item, $activities = [])
{
  if (!is_array($activity)) {
    return false;
  }
  $specified = !empty($activities);
  $item_activity = [];
  $completions = [];
  $starts = [];
  $enrolls = [];
  $types = [$item];
  if ($item == 'course') {
    $types[] = 'access';
  }
  foreach ($activity as $key => $act) {
    if (empty($act->post_id) && empty($act->course_id)) {
      //one of them had to be present...
      continue;
    }
    if (empty($act->activity_type) || !in_array($act->activity_type, $types)) {
      //if not related to item chosen, move on...
      continue;
    }
    if (!$specified) {
      $item_activity[] = $act;
      continue;
    }

    if (in_array('enrolls', $activities)) {
      if ($act->activity_type == 'access') {
        $enrolls[] = $act;
        continue;
      }
    }

    if (in_array('starts', $activities)) {
      if (!empty($act->activity_started)) {
        $starts[] = $act;
      }
    }

    if (in_array('completions', $activities)) {
      if (!empty($act->activity_completed) && !empty($act->activity_status)) {
        $completions[] = $act;
      }
    }

  } //end foreach

  if ($specified) {
    $item_activity['completions'] = $completions;
    $item_activity['starts'] = $starts;
    $item_activity['enrolls'] = $enrolls;
  }

  return $item_activity;
}


function tred_learndash_get_item_activities_number($activity, $item)
{

  $item_activities = tred_learndash_get_item_all_activities($activity, $item, ['completions', 'starts', 'enrolls']);
  $final_array = [];
  $final_array['completions'] = (is_array($item_activities)) ? count($item_activities['completions']) : 0;
  $final_array['starts'] = (is_array($item_activities)) ? count($item_activities['starts']) : 0;
  $final_array['enrolls'] = (is_array($item_activities)) ? count($item_activities['enrolls']) : 0;

  return $final_array;
}


function tred_get_courses_completed()
{
  global $wpdb;
  $count = $wpdb->get_results(
    "SELECT * FROM $wpdb->usermeta
		WHERE meta_key LIKE '%course_completed%';"
  );
  return $count;
}

function tred_get_users_completed_number($course_id = 0)
{
  if (!is_numeric($course_id)) {
    return "0";
  }
  global $wpdb;
  $value = ($course_id) ? "%course_completed_$course_id%" : "%course_completed_%";
  $select_statement = ($course_id) ? "SELECT COUNT(DISTINCT(user_id)) " : "SELECT COUNT(*) ";
  $select_statement .= "FROM $wpdb->usermeta WHERE meta_key LIKE %s";
  $count = $wpdb->get_var(
    $wpdb->prepare(
      $select_statement,
      $value
    )
  );
  return $count;
}

function tred_get_courses_completed_number()
{
  global $wpdb;
  $value = "%course_completed_%";
  $select_statement = "SELECT COUNT(DISTINCT(meta_key)) FROM $wpdb->usermeta WHERE meta_key LIKE %s";
  $count = $wpdb->get_var(
    $wpdb->prepare(
      $select_statement,
      $value
    )
  );
  return $count;
}


function tred_get_learndash_post_types_comments()
{

  $output = [];
  $args = array(
    'post_type' => ['sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz'],
    'status' => ['hold', 'approve'],
  );
  $comments = get_comments($args);
  $output['total'] = count($comments);
  $output['items'] = $comments;

  return $output;
}

//Todo: implement on the frontend
function tred_comments_by_user_id_or_email($user = '')
{

  if (!$user) {
    return 0;
  }
  $user_email = '';
  if (is_numeric($user)) {
    $user_data = get_user_by('id', $user);
    $user_email = $user_data->user_email;
  } else {
    $user_email = $user;
  }
  if (!$user_email) {
    return 0;
  }

  //find on transients
  $transient_name = 'tred_comments_by_course';
  $tred_comments_by_course = get_transient($transient_name);
  if ($tred_comments_by_course) {
    $comments = $tred_comments_by_course;
  } else {
    $comments = tred_comments_by_course(tred_get_learndash_post_types_comments()['items']);
  }
  if (!empty($tred_comments_by_course['users'])) {
    foreach ($tred_comments_by_course['users'] as $email => $number) {
      if ($user_email === $email) {
        return $number;
      }
    }
  }
  return 0;
}

//Todo: implement on the frontend
function tred_comments_by_course_id_or_title($course = '')
{

  if (!$course) {
    return [];
  }
  $course_title = '';
  if (is_numeric($course)) {
    $course_title = get_the_title($course);
  } else {
    $course_title = $course;
  }
  if (!$course_title) {
    return [];
  }

  //find on transients
  $transient_name = 'tred_comments_by_course';
  $tred_comments_by_course = get_transient($transient_name);
  if ($tred_comments_by_course) {
    $comments = $tred_comments_by_course;
  } else {
    $comments = tred_comments_by_course(tred_get_learndash_post_types_comments()['items']);
  }
  if (!empty($tred_comments_by_course['courses'])) {
    foreach ($tred_comments_by_course['courses'] as $key => $course) {
      if ($course['course_title'] == $course_title) {
        return $course;
      }
    }
  }
  return [];
}

function tred_comments_by_course($comment_items = [], $hours = TRED_CACHE_X_HOURS, $force_refresh = false)
{
  $output = [];
  $output['users'] = [];
  $output['courses'] = [];
  if (empty($comment_items) || !is_array($comment_items)) {
    return $output;
  }

  $transient_name = 'tred_comments_by_course';
  $tred_comments_by_course = get_transient($transient_name);
  if (!$force_refresh && $tred_comments_by_course) {
    return $tred_comments_by_course;
  }
  $transient_expire_time = (int) $hours * HOUR_IN_SECONDS;

  //if lesson or topic or quiz, check to which course belongs
  foreach ($comment_items as $com) {
    $post_id = $com->comment_post_ID;
    $post_type = get_post_type($post_id);
    $course_id = ('sfwd-courses' === $post_type) ? $post_id : learndash_get_course_id($post_id);
    if (empty($course_id)) {
      continue;
    }

    $title = get_the_title($course_id);
    if (empty($title)) {
      continue;
    }
    if (!isset($output['courses'][$course_id])) {
      $output['courses'][$course_id] = [];
      $output['courses'][$course_id]['course_title'] = $title;
      $output['courses'][$course_id]['total'] = 0;
      $output['courses'][$course_id]['approve'] = 0;
      $output['courses'][$course_id]['hold'] = 0;
    }
    $output['courses'][$course_id]['total'] += 1;
    if (!empty($com->comment_approved)) {
      $output['courses'][$course_id]['approve'] += 1;
    } else {
      $output['courses'][$course_id]['hold'] += 1;
    }
    $user_email = $com->comment_author_email;
    if (!isset($output['users'][$user_email])) {
      $output['users'][$user_email] = 0;
    }
    $output['users'][$user_email] += 1;
  } //end outter foreach

  arsort($output['users']);
  tred_sort_desc($output['courses'], 'total');

  set_transient($transient_name, $output, $transient_expire_time);

  return $output;
}

function tred_learndash_get_activity_last_12_months($post_id = 0)
{

  $output = [];
  $last_12_months = tred_get_last_12_months_and_year_array(); //[ [ "year_month" => "2021_10", "year" => "2021", "month" => "10" ] ... ]
  $where_date = '(';
  $where_inside = '';

  foreach ($last_12_months as $k => $array) {

    if (!is_numeric($array['year']) || !is_numeric($array['month'])) {
      continue;
    }
    $year = $array['year'];
    $month = $array['month'];
    $year_month = $array['year_month'];

    $output[$year_month] = [];

    //mount the SQL where condition with the year_month values remaining on the array
    if (!empty($where_inside)) {
      $where_inside .= ' OR ';
    }
    $where_inside .= '(YEAR(FROM_UNIXTIME(activity_updated)) = "' . $year . '" AND MONTH(FROM_UNIXTIME(activity_updated)) = "' . $month . '")';
  } //end foreach

  if (empty($output)) {
    return $output;
  }

  //query
  $where_date .= $where_inside . ')';
  global $wpdb;
  $where_post_id = (!empty($post_id)) ? ' AND post_id = "' . $post_id . '"' : '';
  $where_updated = '(activity_updated IS NOT NULL AND activity_updated != ""' . $where_post_id . ' AND ' . $where_date . ')';
  $sql_select = 'SELECT * FROM ' . esc_sql(LDLMS_DB::get_table_name('user_activity')) . ' WHERE ';
  $activity = $wpdb->get_results($sql_select .= $where_updated);

  foreach ($activity as $act) {
    $updated = $act->activity_updated;
    if (empty($updated)) {
      continue;
    }
    $m = date('m', $updated);
    $y = date('Y', $updated);
    $ym = $y . "_" . $m;
    $output[$ym][] = $act;
  } //end foreach

  return $output;
}


//TEMPLATES FUNCTIONS
function tred_template_wptrat_links($empty = false)
{
  //check if user can manage options
  if (!current_user_can('manage_options') || !is_admin()) {
    return;
  }

  if ($empty) { ?>
    <div
      style="background: #fff;border: 1px solid #c3c4c7;border-left-width: 4px;box-shadow: 0 1px 1px rgba(0,0,0,.04);margin: 5px 15px 2px;padding: 12px;border-left-color: #135e96;">
      <!-- <div class="notice notice-success is-dismissible"> -->
      <p>
        Custom tasks (from $100), contact the author at <span style='color:#135e96'><a
            href="mailto:luisrock@wptrat.com">luisrock@wptrat</a></span>.
      </p>
    </div>
  <?php //wp_die(); ?>
  <?php } else { ?>
    <div class="tred-wptrat-announcements">
      <h3>In need of a custom task?</h3>
      <ul>
        <li>
          ⇨ Fix a header on my site
        </li>
        <li>
          ⇨ New stats table
        </li>
        <li>
          ⇨ New stats chart
        </li>
        <li>
          ⇨ Fix the next button
        </li>
        <li>
          ⇨ Extra functionality for my LMS site
        </li>
        <li>
          ⇨ Custom plugin
        </li>
      </ul>
      <div class="tred-wptrat-arrows">
        <p>
          Starting at just $100
        </p>
        <p>
          Contact the author at <span style='color:#217cba'><a
              href="mailto:luisrock@wptrat.com">luisrock@wptrat.com</a></span>
        </p>
      </div>
    </div>
  <?php }
}

function tred_mount_widgets_shortcode_section_table_with_json($json)
{
  $table = '';
  if (!$json || !is_string($json)) {
    return $table;
  }
  $array = json_decode($json, true);
  if (!$array || !is_array($array)) {
    return $table;
  } ?>
  <table class="widefat">
    <tr>
      <th class="bg-blue-100 border text-left px-8 py-4">
        <?php _e('Number', 'learndash-easy-dash'); ?>
      </th>
      <th class="bg-blue-100 border text-left px-8 py-4">
        <?php _e('Name', 'learndash-easy-dash'); ?>
      </th>
      <th class="bg-blue-100 border text-left px-8 py-4">
        <?php _e('Type', 'learndash-easy-dash'); ?>
      </th>
    </tr>
    <?php foreach ($array as $w) { ?>
      <tr>
        <td class="border px-8 py-4">
          <strong>
            <?php echo esc_html($w['number']); ?>
          </strong>
        </td>
        <td class="border px-8 py-4">
          <?php echo esc_html($w['widget_name']); ?>
        </td>
        <td class="border px-8 py-4">
          <?php echo esc_html($w['widget_type']); ?>
        </td>
      </tr>
      <?php
    } ?>
  </table>
  <?php
}

function tred_display_content_area($mode, $header = true, $edit_buttons = true)
{
  $item = ($mode === 'global') ? $mode : '';
  ?>
  <div class="tred-content-area" id="tred-<?php echo esc_attr($mode); ?>-content-area"
    data-panel-type="<?php echo esc_attr($mode); ?>" data-panel-item="<?php echo esc_attr($item); ?>">

    <?php if ($header) { ?>

      <div class="flex flex-wrap justify-between tred-content-area-header"
        id="tred-content-area-header-<?php echo esc_attr($mode); ?>">
        <!-- TITLE -->
        <div class="tred-title" id="tred-title-<?php echo esc_attr($mode); ?>">
          <h2 class="tred-title-main">
            <!-- ajax -->
          </h2>
          <span class="tred-fillers" id="tred-fillers-<?php echo esc_attr($mode); ?>">
            <!-- ajax -->
          </span>
        </div>
        <!-- end TTITLE -->

        <?php if ($edit_buttons) { ?>
          <!-- EDIT BUTTONS -->
          <div class="tred-edit-panel">
            <button type="button" class="button tred-edit-panel-button" style="display:none">
              Edit mode
            </button>
            <button type="submit" class="button tred-save-panel-button" style="display:none" disabled>
              Save changes
            </button>
            <button type="submit" class="button tred-restore-panel-button" style="display:none" disabled>
              Restore all
            </button>
          </div>
          <!-- end EDIT BUTTONS -->
        <?php } ?>
      </div>
      <!-- end tred-content-area-header tred-content-area-header-filtered -->

    <?php } //end if header ?>

    <!-- TOP-BOXES -->
    <div class="flex flex-wrap tred-top-banners" id="tred-top-banners-<?php echo esc_attr($mode); ?>">

      <!-- ajax -->

    </div>
    <!-- end TOP-BOXES -->

    <!-- CHARTS -->
    <div class="flex flex-row flex-wrap flex-grow mt-2 tred-charts" id="tred-charts-<?php echo esc_attr($mode); ?>">

      <!-- ajax -->

    </div>
    <!-- CHARTS end -->

    <!-- TABLES -->
    <div class="flex flex-row flex-wrap flex-grow mt-2 tred-tables" id="tred-tables-<?php echo esc_attr($mode); ?>">

      <!-- ajax -->

    </div>
    <!-- TABLES end -->

  </div>
  <?php
}

function tred_display_filter_section($no_filter_kind = false)
{ ?>
  <div class="tred-filter-section">
    <form id="form-filter">
      <div class="tred-form-fields" style="border-style: none;">
        <div class="tred-settings-title">
          <?php esc_html_e('Easy Dash for LearnDash - Filter Center', 'learndash-easy-dash'); ?>
        </div>
        <div class="tred-form-fields-group">
          <div class="tred-form-fields-group">

            <div class="tred-form-div-select" style="min-width: 160px;">
              <?php if (!$no_filter_kind) { ?>
                <div class="tred-form-fields-label-filter">
                  Kind:
                </div>
                <label>
                  <select id="tred_filter_item" name="tred_filter_item" style="width: 160px;">
                    <option value="0">
                      <?php _e('select', 'learndash-easy-dash'); ?>
                    </option>
                    <option value="sfwd-courses">
                      <?php _e('course', 'learndash-easy-dash'); ?>
                    </option>
                    <option value="users">
                      <?php _e('user', 'learndash-easy-dash'); ?>
                    </option>
                    <!-- 2.3.1 -->
                    <option value="groups">
                      <?php _e('group', 'learndash-easy-dash'); ?>
                    </option>
                    <!-- <option value="sfwd-lessons"> lesson </option>
                                <option value="sfwd-topic"> topic  </option>
                                <option value="sfwd-quiz"> quiz  </option>
                                <option value="groups">  group  </option> -->
                  </select>
                </label>
              <?php } ?>
              <label id="tred_pick_parent">
                <select id="tred_pick" name="tred_pick" style="width: 350px%;">
                  <option value="0">
                    <?php esc_html_e('select', 'learndash-easy-dash'); ?>
                  </option>
                  <!-- ajax -->
                </select>
              </label>
              <label id="tred_pick_users_parent" style="display:none">
                <select id="tred_pick_users" name="tred_pick_users" style="width: 350px%;">
                  <option value="0">
                    <?php esc_html_e('select', 'learndash-easy-dash'); ?>
                  </option>
                  <!-- ajax -->
                </select>
              </label>
              <button type="submit" name="submit" id="submit-filter" class="button button-primary" disabled>
                <?php esc_html_e('go', 'learndash-easy-dash'); ?>
              </button>
            </div>
          </div>
        </div>
      </div> <!-- end form fields -->
    </form>
  </div>
  <?php
}