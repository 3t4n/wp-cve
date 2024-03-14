<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-mycred-learndash-lb-functions
 *
 * @author soha
 */
class myCred_Learndash_lb {
    public function get_course_ref_id($type_based, $ids) {
        if ($ids) {
            $ids = explode(',', $ids);
            if ($type_based == 'specific_course') {
                $ref_ID = join(',', $ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            } else {
                $courses = get_posts(
                        array(
                            'post_type' => 'sfwd-courses',
                            'numberposts' => -1,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'ld_course_category',
                                    'field' => 'term_id',
                                    'terms' => $ids,
                                )
                            )
                        )
                );
                foreach ($courses as $course) {
                    $course_ids[] = $course->ID;
                }
                $ref_ID = join(',', $course_ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            }
        }
        return $ref_id;
    }

    public function get_lesson_ref_id($type_based, $ids, $assoc_course_id) {
        if ($ids) {
            $ids = explode(',', $ids);
            if ($type_based == 'specific_lesson') {
                $ref_ID = join(',', $ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            } elseif ($type_based == 'lesson_category') {
                $lessons = get_posts(
                        array(
                            'post_type' => 'sfwd-lessons',
                            'numberposts' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => 'course_id',
                                    'value' => $assoc_course_id,
                                    'compare' => '=',
                                ),
                            ),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'ld_lesson_category',
                                    'field' => 'term_id',
                                    'terms' => $ids,
                                )
                            )
                        )
                );
                foreach ($lessons as $lesson) {
                    $lesson_ids[] = $lesson->ID;
                }
                $ref_ID = join(',', $lesson_ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            }
        }
        return $ref_id;
    }

    public function get_topic_ref_id($type_based, $ids, $assoc_course_id, $assoc_lesson_id) {
        if ($ids) {
            $ids = explode(',', $ids);
            if ($type_based == 'specific_topic') {
                $ref_ID = join(',', $ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            } elseif ($type_based == 'topic_category') {
                $topics = get_posts(
                        array(
                            'post_type' => 'sfwd-topic',
                            'numberposts' => -1,
                            'meta_query' => array(
                                'relation' => 'and',
                                array(
                                    'key' => 'course_id',
                                    'value' => $assoc_course_id,
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'lesson_id',
                                    'value' => $assoc_lesson_id,
                                    'compare' => '=',
                                )
                            ),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'ld_topic_category',
                                    'field' => 'term_id',
                                    'terms' => $ids,
                                )
                            )
                        )
                );
                foreach ($topics as $topic) {
                    $topic_ids[] = $topic->ID;
                }
                $ref_ID = join(',', $topic_ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            }
        }
        return $ref_id;
    }

    public function get_quiz_ref_id($type_based, $ids, $assoc_course_id, $assoc_lesson_id) {
        if ($ids) {
            $ids = explode(',', $ids);
            if ($type_based == 'specific_quiz') {
                $ref_ID = join(',', $ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            } elseif ($type_based == 'quiz_category') {
                $quizes = get_posts(
                        array(
                            'post_type' => 'sfwd-quiz',
                            'numberposts' => -1,
                            'meta_query' => array(
                                'relation' => 'and',
                                array(
                                    'key' => 'course_id',
                                    'value' => $assoc_course_id,
                                    'compare' => '=',
                                ),
                                array(
                                    'relation' => 'or',
                                    array(
                                        'key' => 'lesson_id',
                                        'value' => $assoc_lesson_id,
                                        'compare' => '=',
                                    ),
                                    array(
                                        'key' => 'topic_id',
                                        'value' => $assoc_lesson_id,
                                        'compare' => '=',
                                    ),
                                )
                            ),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field' => 'term_id',
                                    'terms' => $ids,
                                )
                            )
                        )
                );
                foreach ($quizes as $quiz) {
                    $quiz_ids[] = $quiz->ID;
                }
                $ref_ID = join(',', $quiz_ids);
                if ($ref_ID) {
                    $ref_id = "AND ref_id IN ({$ref_ID})";
                } else {
                    $ref_id = '';
                }
            }
        }
        return $ref_id;
    }

    public function get_timefilter($timeframe, $start, $end) {
        global $wpdb;
        $now = current_time('timestamp');

        $week_starts = get_option('start_of_week');
        
        if ($week_starts == 0) {
            $week_starts = 'sunday';
        } elseif ($week_starts == 1) {
            $week_starts = 'monday';
        } elseif ($week_starts == 2) {
            $week_starts = 'tuesday';
        } elseif ($week_starts == 3) {
            $week_starts = 'wednesday';
        } elseif ($week_starts == 4) {
            $week_starts = 'thursday';
        } elseif ($week_starts == 5) {
            $week_starts = 'friday';
        } elseif ($week_starts == 6) {
            $week_starts = 'saturday';
        }

        if ($timeframe == 'daily') {
            //daily 
            $query = $wpdb->prepare("AND l.time BETWEEN %d AND %d", strtotime('today midnight', $now), $now);
        } elseif ($timeframe == 'weekly') {
            //weekly
            $query = $wpdb->prepare("AND l.time BETWEEN %d AND %d", strtotime($week_starts . ' this week', $now), $now);
        } elseif ($timeframe == 'monthly') {
            //monthly
            $query = $wpdb->prepare("AND l.time BETWEEN %d AND %d", strtotime(date('Y-m-01', $now)), $now);
        } elseif ($timeframe == 'annually') {
            //annually
            $query = $wpdb->prepare("AND l.time BETWEEN %d AND %d", strtotime(date('Y-01-01'), $now), $now);
        } elseif ($timeframe == 'custom_time') {
            //custom     
            $start_date = strtotime($start);
            $end_date = strtotime($end . ' 23:59:59');
            if ($start_date == $end_date) {
                $end_date = strtotime($end . ' 23:59:59');
            }
            
            $query = $wpdb->prepare("AND l.time BETWEEN %d AND %d", $start_date, $end_date);
        }
        return $query;
    }

}
