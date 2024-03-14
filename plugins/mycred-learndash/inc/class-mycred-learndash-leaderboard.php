<?php

/**
 * Description of class-mycred-learndash-leaderboard
 *
 * @author soha
 */
class MyCred_Learndash_Leaderboard {

    protected $options;

    public function __construct() {
        $this->options = get_option('learndash_allow_leaderboard');
        add_action('admin_enqueue_scripts', array($this, 'mycred_learndash_leaderboard_scripts'),999);
        add_action( 'admin_enqueue_scripts', array($this, 'load_admin_assets') );
        if ($this->options) {
            add_action('init', array($this, 'mycred_learndash_leaderboard_cpt'));
            add_action('add_meta_boxes', array($this, 'mycred_learndash_metabox'));
            add_action('save_post', array($this, 'mycred_learndash_leaderboard_save'), 10, 3);
            add_shortcode('route', array($this, 'mycred_leaderboard_generate_shortcode'));
            add_action('wp_ajax_mycred_course_based_options', array($this, 'mycred_learndash_course_options'));
            add_action('wp_ajax_mycred_select_a_course', array($this, 'mycred_learndash_select_a_course_ajax'));
            add_action('wp_ajax_mycred_select_a_lesson', array($this, 'mycred_learndash_select_a_lesson_ajax'));
            add_action('wp_ajax_mycred_show_topic', array($this, 'mycred_leaderboard_based_topic'));
            add_action('wp_ajax_mycred_select_lesson_topic', array($this, 'mycred_learndash_select_a_quiz_ajax'));
            add_action('wp_ajax_mycred_show_quiz', array($this, 'mycred_learndash_show_based_quiz'));
        }
    }

    public function mycred_learndash_leaderboard_scripts() {
        $current_screen = get_current_screen();

        if ($current_screen->id == 'leaderboard_cpt') {
            //ajax localize
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('script', plugin_dir_url(__FILE__) . '/assets/js/learndash-mycred-leaderboard.js', array('jquery'));

            wp_enqueue_style('jquery-ui-datepicker', plugin_dir_url(__FILE__) . '/assets/css/jquery-ui.css');
            
            wp_localize_script('script', 'mycred_learndash', array('ajax_url' => admin_url('admin-ajax.php')));
        }
        wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . '/assets/css/mycred-leaderboard-admin-style.css');
    }

    public function load_admin_assets( ) {
       
        wp_enqueue_script( 
            'mycred_learndash_admin_script', 
            plugin_dir_url(__FILE__) . '/assets/js/script.js', 
            array( 'jquery' ));

        
    }

    // Register Custom Post Type
    public function mycred_learndash_leaderboard_cpt() {
        $labels = array(
            'name' => _x('Leaderboards', 'Post Type General Name', 'mycred'),
            'singular_name' => _x('Leaderboard', 'Post Type Singular Name', 'mycred'),
            'menu_name' => __('', 'mycred'),
            'name_admin_bar' => __('', 'mycred'),
            'archives' => __('Item Archives', 'mycred'),
            'attributes' => __('Item Attributes', 'mycred'),
            'parent_item_colon' => __('Parent Item:', 'mycred'),
            'all_items' => __('Leaderboard', 'mycred'),
            'add_new_item' => __('Add New Item', 'mycred'),
            'add_new' => __('Add Leaderboard', 'mycred'),
            'new_item' => __('New Item', 'mycred'),
            'edit_item' => __('Edit Item', 'mycred'),
            'update_item' => __('Update Item', 'mycred'),
            'view_item' => __('View Item', 'mycred'),
            'view_items' => __('View Items', 'mycred'),
            'search_items' => __('Search Item', 'mycred'),
            'not_found' => __('Not found', 'mycred'),
            'not_found_in_trash' => __('Not found in Trash', 'mycred'),
            'featured_image' => __('Featured Image', 'mycred'),
            'set_featured_image' => __('Set featured image', 'mycred'),
            'remove_featured_image' => __('Remove featured image', 'mycred'),
            'use_featured_image' => __('Use as featured image', 'mycred'),
            'insert_into_item' => __('Insert into item', 'mycred'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'mycred'),
            'items_list' => __('Items list', 'mycred'),
            'items_list_navigation' => __('Items list navigation', 'mycred'),
            'filter_items_list' => __('Filter items list', 'mycred'),
        );
        $args = array(
            'label' => __('Leaderboard', 'mycred'),
            'description' => __('Leaderboard Description', 'mycred'),
            'labels' => $labels,
            'supports' => array('title'),
            'taxonomies' => array('category', 'post_tag'),
            'hierarchical' => false,
            'show_in_menu' => 'learndash-lms',
            'show_in_admin_bar' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'page',
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
        );
        register_post_type('leaderboard_cpt', $args);
    }

    public function mycred_learndash_metabox() {
        add_meta_box(
                'leaderboard', __('Leaderboard', 'mycred'), array($this, 'mycred_learndash_leaderboard_mb'), 'leaderboard_cpt'
        );
    }

    public function mycred_learndash_leaderboard_mb($post) {
        $default = get_post_meta($post->ID, 'leaderboard_default', true);

        $type = (!empty($default['leaderboard_type'])) ? $default['leaderboard_type'] : '';

        echo '<input type="hidden" name="mycred_leaderboard_box_nonce" value="', esc_attr(wp_create_nonce(basename(__FILE__))), '" />';
        ?>
        <div class="custom-option">
            <label>
                <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                <?php esc_html_e('Create Leaderboard of ', 'mycred-learndash'); ?> </label>
            <div class="option-wrapper">
                <div class="form-element">
                    <select name="leaderboard_setting[leaderboard_type]" id="leaderboard_type"
                            data-id="<?php echo get_the_ID(); ?>">
                        <option value="course" <?php echo $type == 'course' ? 'selected' : '' ?>>
                            <?php esc_html_e('Courses', 'mycred'); ?></option>
                        <option value="lesson" <?php echo $type == 'lesson' ? 'selected' : '' ?>>
                            <?php esc_html_e('Lessons', 'mycred'); ?></option>
                        <option value="topic" <?php echo $type == 'topic' ? 'selected' : '' ?>><?php esc_html_e('Topics', 'mycred'); ?>
                        </option>
                        <option value="quiz" <?php echo $type == 'quiz' ? 'selected' : '' ?>><?php esc_html_e('Quizes', 'mycred'); ?>
                        </option>
                    </select>
                </div>

                <div id="based_type" class="form-element">
                </div>

                <div id="based_on_lesson" class="based-option">
                    <?php echo esc_html($this->mycred_leaderboard_associated_course('lesson')); ?>
                </div>

                <div id="based_on_topic" class="based-option">
                    <?php echo esc_html($this->mycred_leaderboard_associated_course('topic')); ?>
                </div>

                <div id="based_on_quiz" class="based-option">
                    <?php echo esc_html($this->mycred_leaderboard_associated_course('quiz')); ?>
                </div>

                <div class="help-text" style="display: none;">Select the type for which you want to create a leaderboard.</div>
            </div>
        </div>


        <?php


        echo esc_html($this->leaderboard_other_settings($post, $default));

 
    }

    public function mycred_learndash_course_options() {
        
        $post_id  = isset($_POST['leaderboard_id']) ? absint($_POST['leaderboard_id']) : 0;
        $course_based_meta = get_post_meta($post_id, 'leaderboard_course_based', true);
        $based_course = isset($course_based_meta['leaderboard_based_course']) ? $course_based_meta['leaderboard_based_course'] : '';
        $cat = isset($course_based_meta['leaderboard_taxonomy']) ? $course_based_meta['leaderboard_taxonomy'] : '';
        $postmeta = isset($course_based_meta['leaderboard_posts']) ? $course_based_meta['leaderboard_posts'] : '';

        $select_all = (!empty($course_based_meta['all'])) ? $course_based_meta['all'] : '';


        $opt = array(
            'post_type' => 'sfwd-courses',
            'numberposts' => -1,
        );
        if (get_posts($opt)) {
            foreach (get_posts($opt) as $post) {
                $courses_ids[] = $post->ID;
            }
        }
        ?>
        <div id="leaderboard_based_on_course">
            <label class="based-title"><?php esc_html_e('Create Leaderboard based on ', 'mycred-learndash'); ?></label>

            <div>
                <input type="radio" id="course_category" value="course_category"
                       name="leaderboard_course_based[leaderboard_based_course]"
                       <?php checked($based_course, 'course_category'); ?> />
                <label for="course_category"><?php esc_html_e('Course Category', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="radio" id="specific_course" value="specific_course"
                       name="leaderboard_course_based[leaderboard_based_course]"
                       <?php checked($based_course, 'specific_course'); ?> />
                <label for="specific_course"><?php esc_html_e('Specific Course(s)', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="checkbox" id="all_courses" value="all" class="all" name="leaderboard_course_based[all]"
                       <?php checked($select_all, 'all'); ?> />
                <label for="all_courses"><?php esc_html_e('Select All', 'mycred-learndash'); ?></label>
            </div>


            <div id="leaderboard_based_category"
                 style="<?php echo $based_course != 'course_category' ? 'display: none;' : 'display:flex;' ?>">
                     <?php
                     $course_cat_terms = '';
                     if ($courses_ids) {
                         foreach ($courses_ids as $courses_id) {
                             foreach (wp_get_post_terms($courses_id, 'ld_course_category') as $course_cat) {
                                 $course_cat_terms[$course_cat->term_id] = $course_cat->name;
                             }
                         }
                         if ($course_cat_terms) {
                             foreach (array_unique($course_cat_terms) as $cat_id => $course_cat_term) {
                                 if (is_array($cat) && in_array($cat_id, $cat)) {
                                     $checked = 'checked="checked"';
                                 } else {
                                     $checked = null;
                                 }

                                 

                                 ?>
                            <label class="label-text"><input type="checkbox" name="leaderboard_course_based[leaderboard_taxonomy][]"
                                                             <?php echo (is_array($cat) && in_array($cat_id, $cat)) ? 'checked="checked"' : null; ?> value="<?php echo esc_attr($cat_id) ?>" /><?php echo esc_html($course_cat_term); ?></label>
                                <?php
                            }
                        }
                    } else {
                        echo '<span class="no-lesson">No Category</span>';
                    }
                    ?>
            </div>
            <div id="leaderboard_based_courses"
                 style="<?php echo $based_course != 'specific_course' ? 'display: none;' : 'display:flex;' ?>">
                     <?php
                     if ($courses_ids) {
                         foreach ($courses_ids as $courses_id) {
                             ?>
                             <?php
                             if (is_array($postmeta) && in_array($courses_id, $postmeta)) {
                                 $checked = 'checked="checked"';
                             } else {
                                 $checked = null;
                             }

                            
                             ?>
                        <label class="label-text"><input type="checkbox" name="leaderboard_course_based[leaderboard_posts][]"
                            <?php echo  (is_array($postmeta) && in_array($courses_id, $postmeta)) ? 'checked="checked"' : null ?>
                                                         value="<?php echo esc_attr($courses_id) ?>" /><?php echo esc_html(get_the_title($courses_id)) ?></label>
                            <?php
                        }
                    } else {
                        echo '<span class="no-lesson">No Courses</span>';
                    }
                    ?>
            </div>
        </div>
        <?php
        die;
    }

    public function mycred_leaderboard_associated_course($type) {
        $lwms = new SFWD_LMS();
        $lesson_based = get_post_meta(get_the_ID(), 'leaderboard_lesson_based', true);
        $course_id = isset($lesson_based['leaderboard_associated_course_lesson']) ? $lesson_based['leaderboard_associated_course_lesson'] : '';

        $topic_based = get_post_meta(get_the_ID(), 'leaderboard_topic_based', true);
        $course_id_topic = isset($topic_based['leaderboard_associated_course_topic']) ? $topic_based['leaderboard_associated_course_topic'] : '';

        $quiz_based = get_post_meta(get_the_ID(), 'leaderboard_quiz_based', true);
        $course_id_quiz = isset($quiz_based['leaderboard_associated_course_quiz']) ? $quiz_based['leaderboard_associated_course_quiz'] : '';

        if ($type == 'lesson') {
            if ($lwms->select_a_course(('sfwd-lessons'))) {
                ?>
                <label class="based-title"><?php esc_html_e('Associated Course', 'mycred-learndash'); ?></label>
                <select name="leaderboard_lesson_based[leaderboard_associated_course_lesson]" id="leaderboard_associated_course">
                    <?php foreach ($lwms->select_a_course('sfwd-lessons') as $k => $value) { ?>
                        <option value="<?php echo esc_attr($k); ?>" <?php echo $course_id == $k ? "selected" : "" ?>><?php echo esc_html($value); ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
            }
        } else if ($type == 'topic') {
            if ($lwms->select_a_course('sfwd-topic')) {
                ?>
                <label class="based-title"><?php esc_html_e('Associated Course', 'mycred-learndash'); ?></label>
                <select name="leaderboard_topic_based[leaderboard_associated_course_topic]" id="leaderboard_associated_course_topic"
                        data-id="<?php echo get_the_ID(); ?>">
                            <?php foreach ($lwms->select_a_course('sfwd-topic') as $k => $value): ?>
                        <option value="<?php echo esc_attr($k); ?>" <?php echo $course_id_topic == $k ? "selected" : "" ?>><?php echo esc_html($value); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="lesson_topic"></div>

                <?php
            }
        }else if ($type == 'quiz') {
            if ($lwms->select_a_course('sfwd-quiz')) {
                ?>
                <label class="based-title"><?php esc_html_e('Associated Course', 'mycred-learndash'); ?></label>
                <select name="leaderboard_quiz_based[leaderboard_associated_course_quiz]" id="leaderboard_associated_course_quiz"
                        data-id="<?php echo get_the_ID(); ?>">
                            <?php foreach ($lwms->select_a_course('sfwd-quiz') as $k => $value): ?>
                        <option value="<?php echo esc_attr($k); ?>" <?php echo $course_id_quiz == $k ? "selected" : "" ?>><?php echo esc_html($value); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="lesson_quiz"></div>
                <?php
            }
        }
        ?>
        <div id="course_selection"></div>
        <?php
    }

    function mycred_learndash_select_a_course_ajax() {

        $this->mycred_leaderboard_based_lesson(isset($_POST['leaderboard_id']) ? absint($_POST['leaderboard_id']) : 0, isset($_POST['course_id']) ? absint($_POST['course_id']) : 0 );
        die;
    }

    public function mycred_leaderboard_based_lesson($post_id, $course_id) {
        $lesson_based = get_post_meta($post_id, 'leaderboard_lesson_based', true);
        $based_lesson = isset($lesson_based['leaderboard_based_lesson']) ? $lesson_based['leaderboard_based_lesson'] : '';
        $lesson_cat = isset($lesson_based['leaderboard_course_lesson_cat']) ? $lesson_based['leaderboard_course_lesson_cat'] : '';

        $select_all = (!empty($lesson_based['all'])) ? $lesson_based['all'] : '';

        $opt = array(
            'post_type' => 'sfwd-lessons',
            'numberposts' => -1,
            'meta_key' => 'course_id',
            'meta_value' => $course_id
        );
        $lesson_ids = array();
        if (get_posts($opt)) {
            foreach (get_posts($opt) as $post) {
                $lesson_ids[] = $post->ID;
            }
        }
        ?>
        <div id="leaderboard_based_on_lessons">
            <label class="based-title"><?php esc_html_e('Create Leaderboard based on ', 'mycred-learndash'); ?></label>
            <div>
                <input type="radio" id="lesson_category" value="lesson_category"
                       name="leaderboard_lesson_based[leaderboard_based_lesson]"
                       <?php echo $based_lesson == 'lesson_category' ? 'checked' : '' ?> />
                <label for="lesson_category"><?php esc_html_e('Lesson Category', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="radio" id="specific_lesson" value="specific_lesson"
                       name="leaderboard_lesson_based[leaderboard_based_lesson]"
                       <?php echo $based_lesson == 'specific_lesson' ? 'checked' : '' ?> />
                <label for="specific_lesson"><?php esc_html_e('Specific Lesson(s)', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="checkbox" id="all_lessons" class="all" value="all" name="leaderboard_lesson_based[all]"
                       <?php echo $select_all == 'all' ? 'checked' : '' ?> />
                <label for="all_lessons"><?php esc_html_e('Select All', 'mycred-learndash'); ?></label>
            </div>
        </div>

        <div id="leaderboard_based_category_lesson"
             style="<?php echo $based_lesson != 'lesson_category' ? 'display: none;' : 'display:flex;' ?>">
                 <?php
                 if ($lesson_ids) {
                     foreach ($lesson_ids as $lesson_id) {
                         if (get_the_terms($lesson_id, 'ld_lesson_category')) {
                             foreach (get_the_terms($lesson_id, 'ld_lesson_category') as $cat) {
                                 $lesson_cats[$cat->term_id] = $cat->name;
                             }
                         }
                     }
                     if ($lesson_cats) {
                         foreach (array_unique($lesson_cats) as $term_id => $lesson_category) {
                             ?>
                             <?php
                             if (is_array($lesson_cat) && in_array($term_id, $lesson_cat)) {
                                 $checked = 'checked="checked"';
                             } else {
                                 $checked = null;
                             }

                            

                             ?>
                        <label class="label-text"><input type="checkbox" value="<?php echo esc_attr($term_id) ?>" <?php echo  (is_array($lesson_cat) && in_array($term_id, $lesson_cat)) ? 'checked="checked"' : ''; ?>
                                                         name="leaderboard_lesson_based[leaderboard_course_lesson_cat][]" /><?php echo esc_html($lesson_category); ?></label>
                            <?php
                        }
                    } else {
                        echo '<span class="no-lesson">No Category</span>';
                    }
                } else {
                    echo '<span class="no-lesson">No Category</span>';
                }
                ?>
        </div>
        <div id="leaderboard_based_lessons"
             style="<?php echo $based_lesson != 'specific_lesson' ? 'display: none;' : 'display:flex;' ?>">
                 <?php
                 $lessonmeta = isset($lesson_based['leaderboard_lessons']) ? $lesson_based['leaderboard_lessons'] : '';
                 if ($lesson_ids) {
                     ?>
                     <?php
                     foreach ($lesson_ids as $lesson_id) {
                         ?>
                         <?php
                         if (is_array($lessonmeta) && in_array($lesson_id, $lessonmeta)) {
                             $checked = 'checked="checked"';
                         } else {
                             $checked = null;
                         }

                         
                         ?>
                    <label class="label-text"><input type="checkbox" value="<?php echo esc_attr($lesson_id); ?>" <?php echo (is_array($lessonmeta) && in_array($lesson_id, $lessonmeta)) ? 'checked="checked"' : ''; ?>
                                                     name="leaderboard_lesson_based[leaderboard_lessons][]" /><?php echo esc_html(get_the_title($lesson_id)); ?></label>
                        <?php
                    }
                } else {
                    echo '<span class="no-lesson">No Lesson</span>';
                }
                ?>
        </div>
        </div>
        <?php
    }

    function mycred_learndash_select_a_lesson_ajax() {
        $lwms = new SFWD_LMS();

      
        $post_array = $lwms->select_a_lesson(isset($_POST['course_id']) ? absint($_POST['course_id']) : 0);

        $lesson_meta = get_post_meta(isset($_POST['leaderboard_id']) ? absint($_POST['leaderboard_id']) : 0, 'leaderboard_topic_based', true);
        $learndash_lesson = (!empty($lesson_meta['learndash_lesson'])) ? $lesson_meta['learndash_lesson'] : '';
        if ($post_array) {
            ?>
            <label class="based-title"><?php esc_html_e('Associated Lesson', 'mycred-learndash'); ?></label>
            <select name="leaderboard_topic_based[learndash_lesson]" id="learndash_lesson">
                <?php foreach ($post_array as $k => $value): ?>
                    <option value="<?php echo esc_attr($k) ?>" <?php echo $learndash_lesson == $k ? "selected" : "" ?>><?php echo esc_html($value); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php
        }
        ?>
        <div id="learndash_topic"></div>
        <?php
        die;
    }

    function mycred_learndash_select_a_quiz_ajax() {
        $lwms = new SFWD_LMS();
        $lessons_topics = $lwms->select_a_lesson_or_topic(isset($_POST['course_id']) ? absint($_POST['course_id']) : 0);
        $learndash_leaderboard_id = isset($_POST['course_id']) ? absint($_POST['course_id']) : 0;
        $lesson_topic_meta = get_post_meta($learndash_leaderboard_id, 'leaderboard_quiz_based', true);
        $learndash_topic_quiz = (!empty($lesson_topic_meta['learndash_lesson_topic'])) ? $lesson_topic_meta['learndash_lesson_topic'] : '';
        if ($lessons_topics) {
            ?>
            <label class="based-title"><?php esc_html_e('Associated Lesson or topic', ''); ?></label>
            <select name="leaderboard_quiz_based[learndash_lesson_topic]" id="learndash_lesson_topic">
                <?php foreach ($lessons_topics as $k => $value): ?>
                    <option value="<?php echo esc_attr($k) ?>" <?php echo $learndash_topic_quiz == $k ? "selected" : "" ?>><?php echo esc_html($value); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        <?php }
        ?>
        <div id="learndash_quiz">

        </div>
        <?php
        die;
    }

    public function mycred_leaderboard_based_topic() {

        $learndash_leaderboard_id = isset($_POST['leaderboard_id']) ? absint($_POST['leaderboard_id']) : 0;
        $topic_based = get_post_meta( $learndash_leaderboard_id, 'leaderboard_topic_based', true);
        $based_topic = (!empty($topic_based['leaderboard_based_topic'])) ? $topic_based['leaderboard_based_topic'] : '';
        $select_topic_meta = isset($topic_based['leaderboard_select_topic']) ? $topic_based['leaderboard_select_topic'] : '';
        $topic_cat = isset($topic_based['leaderboard_select_topic_cat']) ? $topic_based['leaderboard_select_topic_cat'] : '';
        $select_all = (!empty($topic_based['all'])) ? $topic_based['all'] : '';
        $learndash_course_id = isset($_POST['course_id']) ? absint($_POST['course_id']) : 0;
        $learndash_lesson_id = isset($_POST['lesson_id']) ? absint($_POST['lesson_id']) : 0;
        $topic_ids = array();
        $opt = array(
            'post_type' => 'sfwd-topic',
            'numberposts' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'course_id',
                    'value' => $learndash_course_id,
                    'compare' => '==',
                ),
                array(
                    'key' => 'lesson_id',
                    'value' => $learndash_lesson_id,
                    'compare' => '==',
                ),
            )
        );
        if (get_posts($opt)) {
            foreach (get_posts($opt) as $topic) {
                $topic_ids[] = $topic->ID;
            }
        }
        ?>
        <div id="leaderboard_based_on_topics">
            <label class="based-title"><?php esc_html_e('Create Leaderboard based on ', 'mycred-learndash'); ?></label>
            <div>
                <input type="radio" id="topic_category" value="topic_category"
                       name="leaderboard_topic_based[leaderboard_based_topic]"
                       <?php echo $based_topic == 'topic_category' ? 'checked' : '' ?> />
                <label for="topic_category"><?php esc_html_e('Topic Category', 'mycred'); ?></label>
            </div>

            <div>
                <input type="radio" id="specific_topic" value="specific_topic"
                       name="leaderboard_topic_based[leaderboard_based_topic]"
                       <?php echo $based_topic == 'specific_topic' ? 'checked' : '' ?> />
                <label for="specific_topic"><?php esc_html_e('Specific Topic(s)', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="checkbox" id="all_topics" class="all" value="all" name="leaderboard_topic_based[all]"
                       <?php echo $select_all == 'all' ? 'checked' : '' ?> />
                <label for="all_topics"><?php esc_html_e('Select All', 'mycred-learndash'); ?></label>
            </div>

            <div id="leaderboard_based_topics_cat"
                 style="<?php echo $based_topic != 'topic_category' ? 'display: none;' : 'display:flex;' ?>">
                     <?php
                     if ($topic_ids) {
                         foreach ($topic_ids as $topic_id) {
                             foreach (get_the_terms($topic_id, 'ld_topic_category') as $term) {
                                 $topic_cats[$term->term_id] = $term->name;
                             }
                         }
                         if ($topic_cats) {
                             foreach (array_unique($topic_cats) as $term_id => $term_name) {
                                 ?>
                                 <?php
                                 if (is_array($topic_cat) && in_array($term_id, $topic_cat)) {
                                     $checked = 'checked="checked"';
                                 } else {
                                     $checked = null;
                                 }

                                 

                                 ?>
                            <label class="label-text"><input type="checkbox" name="leaderboard_topic_based[leaderboard_select_topic_cat][]"
                                                             <?php echo (is_array($topic_cat) && in_array($term_id, $topic_cat)) ? 'checked="checked"' : ''; ?> value="<?php echo esc_attr($term_id) ?>" /><?php echo esc_html($term_name) ?></label>

                            <?php
                        }
                    } else {
                        echo '<span class="no-lesson">No Category</span>';
                    }
                } else {
                    echo '<span class="no-lesson">No Category</span>';
                }
                ?>
                <?php ?>
            </div>

            <div id="leaderboard_based_topics"
                 style="<?php echo $based_topic != 'specific_topic' ? 'display: none;' : 'display:flex;' ?>">
                     <?php
                     if ($topic_ids) {
                         foreach ($topic_ids as $topic_id):
                             ?>
                             <?php
                             if (is_array($select_topic_meta) && in_array($topic_id, $select_topic_meta)) {
                                 $checked = 'checked="checked"';
                             } else {
                                 $checked = null;
                             }

                            


                             ?>
                        <label class="label-text"><input type="checkbox" value="<?php echo esc_attr($topic_id) ?>" <?php echo  (is_array($select_topic_meta) && in_array($topic_id, $select_topic_meta)) ? 'checked="checked"' : ''; ?>
                                                         name="leaderboard_topic_based[leaderboard_select_topic][]" /><?php echo esc_html(get_the_title($topic_id)); ?></label>
                            <?php
                        endforeach;
                    } else {
                        echo '<span class="no-lesson">No Topic</span>';
                    }
                    ?>
            </div>
        </div>
        <?php
        die;
    }

    public function mycred_learndash_show_based_quiz() {

        
        $learndash_leaderboard_id = isset($_POST['leaderboard_id']) ? absint($_POST['leaderboard_id']) : 0;
        $quiz_based = get_post_meta( $learndash_leaderboard_id, 'leaderboard_quiz_based', true);

        $based_quiz = (!empty($quiz_based['leaderboard_based_quiz'])) ? $quiz_based['leaderboard_based_quiz'] : '';
        $select_quiz_meta = isset($quiz_based['leaderboard_select_quiz']) ? $quiz_based['leaderboard_select_quiz'] : '';
        $quiz_cat = isset($quiz_based['leaderboard_select_quiz_cat']) ? $quiz_based['leaderboard_select_quiz_cat'] : '';

        $select_all = (!empty($quiz_based['all'])) ? $quiz_based['all'] : '';

        $learndash_course_id = isset($_POST['course_id']) ? absint($_POST['course_id']) : 0;

        $learndash_lesson_id = isset($_POST['lesson_id']) ? absint($_POST['lesson_id']) : 0;
        $quiz_ids = array();
     

        $opt = array(
            'post_type' => 'sfwd-quiz',
            'numberposts' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'course_id',
                    'value' => $learndash_course_id,
                    'compare' => '==',
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'lesson_id',
                        'value' => $learndash_lesson_id,
                        'compare' => '==',
                    ),
                    array(
                        array(
                            'key' => 'topic_id',
                            'value' =>  $learndash_lesson_id,
                            'compare' => '==',
                        ),
                    )
                ),
            )
        );
        if (get_posts($opt)) {
            foreach (get_posts($opt) as $quiz) {
                $quiz_ids[] = $quiz->ID;
            }
        }
        ?>
        <div id="leaderboard_based_on_quizes">
            <label class="based-title"><?php esc_html_e('Create Leaderboard based on ', 'mycred-learndash'); ?></label>
            <div>
                <input type="radio" id="quiz_category" value="quiz_category"
                       name="leaderboard_quiz_based[leaderboard_based_quiz]"
                       <?php echo $based_quiz == 'quiz_category' ? 'checked' : '' ?> />
                <label for="quiz_category"><?php esc_html_e('Quiz Category', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="radio" id="specific_quiz" value="specific_quiz"
                       name="leaderboard_quiz_based[leaderboard_based_quiz]"
                       <?php echo $based_quiz == 'specific_quiz' ? 'checked' : '' ?> />
                <label for="specific_quiz"><?php esc_html_e('Specific Quiz(es)', 'mycred-learndash'); ?></label>
            </div>

            <div>
                <input type="checkbox" id="all_quizes" class="all" value="all" name="leaderboard_quiz_based[all]"
                       <?php echo $select_all == 'all' ? 'checked' : '' ?> />
                <label for="all_quizes"><?php esc_html_e('Select All', 'mycred-learndash'); ?></label>
            </div>

            <div id="leaderboard_based_quizes_cat"
                 style="<?php echo $based_quiz != 'quiz_category' ? 'display: none;' : 'display:flex;' ?>">
                     <?php
                     if ($quiz_ids) {
                         foreach ($quiz_ids as $quiz_id) {
                             foreach (get_the_terms($quiz_id, 'category') as $term) {
                                 $quiz_cats[$term->term_id] = $term->name;
                             }
                         }
                         if ($quiz_cats) {
                             foreach (array_unique($quiz_cats) as $term_id => $term_name) {
                                 ?>
                                 <?php
                                 if (is_array($quiz_cat) && in_array($term_id, $quiz_cat)) {
                                     $checked = 'checked="checked"';
                                 } else {
                                     $checked = null;
                                 }

                                 
                                 ?>
                            <div>
                                <label class="label-text"><input type="checkbox"
                                                                 name="leaderboard_quiz_based[leaderboard_select_quiz_cat][]" <?php echo (is_array($quiz_cat) && in_array($term_id, $quiz_cat)) ? 'checked="checked"' : ''; ?>
                                                                 value="<?php echo esc_attr($term_id) ?>" /><?php echo esc_html($term_name) ?></label>
                            </div>

                            <?php
                        }
                    } else {
                        echo '<span class="no-lesson">No Category</span>';
                    }
                } else {
                    echo '<span class="no-lesson">No Category</span>';
                }
                ?>
                <?php ?>
            </div>

            <div id="leaderboard_based_quizes"
                 style="<?php echo $based_quiz != 'specific_quiz' ? 'display: none;' : 'display:flex;' ?>">
                     <?php
                     if ($quiz_ids) {
                         foreach ($quiz_ids as $quiz_id):
                             ?>
                             <?php
                             if (is_array($select_quiz_meta) && in_array($quiz_id, $select_quiz_meta)) {
                                 $checked = 'checked="checked"';
                             } else {
                                 $checked = null;
                             }

                             


                             ?>
                        <label class="label-text"><input type="checkbox" value="<?php echo esc_attr($quiz_id) ?>"
                                                         name="leaderboard_quiz_based[leaderboard_select_quiz][]"
                                                         <?php echo (is_array($select_quiz_meta) && in_array($quiz_id, $select_quiz_meta)) ? 'checked="checked"' : ''; ?> /><?php echo esc_html(get_the_title($quiz_id)); ?></label>
                            <?php
                        endforeach;
                    } else {
                        echo '<span class="no-lesson">No Quiz</span>';
                    }
                    ?>
            </div>
        </div>
        <?php
        die;
    }

    public function mycred_learndash_leaderboard_save( $post_id, $post, $update ) {

        if ( isset( $_POST['leaderboard_setting'] ) ) {

            $leaderboard_setting = array();

            $learndash_leaderboard_setting = $_POST['leaderboard_setting'] ?? null;

            $leaderboard_selected_point_type = $_POST['leaderboard_setting']['leaderboard_pt_type'];
            $leaderboard_point_types = array();

            foreach ($leaderboard_selected_point_type as $key => $value) {

                $leaderboard_point_types[ $key ] = sanitize_text_field( $value );

               
            }

            update_post_meta( $post_id, "mycred_learndash_leaderboard_point_type", $leaderboard_point_types );

            
            foreach ( $learndash_leaderboard_setting  as $key => $value ) {      
               
                if ( 
                    
                    ( $key == 'leaderboard_timefilter' && $value == 'custom_time' ) ||
                    ( $key == 'leaderboard_pagination' && $value == 1 )
                ) {

                    if ( ! is_array( $value ) ) {
                        $leaderboard_setting[ $key ] = sanitize_text_field( $value );

                    }
                    else {
                        $leaderboard_setting[ $key ] = $this->sanitize_array_data( $value );

                    }
                }
            }



            update_post_meta( $post_id, 'leaderboard_default', $leaderboard_setting );


        }

        if ( isset( $_POST['leaderboard_course_based'] ) ) {

            if ( isset( $_POST['leaderboard_course_based']['leaderboard_based_course'] ) ) {
                if ( $_POST['leaderboard_course_based']['leaderboard_based_course'] == 'course_category' ) {
                    unset( $_POST['leaderboard_course_based']['leaderboard_posts'] );
                }
                else {
                    unset( $_POST['leaderboard_course_based']['leaderboard_taxonomy'] );
                }
            }

            $leaderboard_course_based = array();

            $learndash_leaderboard_course_based_setting = $_POST['leaderboard_course_based'] ?? null;

            $leaderboard_course_based_values = (!empty($learndash_leaderboard_course_based_setting)) ? $learndash_leaderboard_course_based_setting : 0;

            foreach ( $leaderboard_course_based_values as $key => $value ) {
                if ( ! is_array( $value ) ) {
                    $leaderboard_course_based[ $key ] = sanitize_text_field( $value );
                }
                else {
                    $leaderboard_course_based[ $key ] = $this->sanitize_array_data( $value );
                }
            }

            update_post_meta( $post_id, 'leaderboard_course_based', $leaderboard_course_based );
        }


        if ( isset( $_POST['leaderboard_topic_based'] ) ) {

            if ( isset( $_POST['leaderboard_topic_based']['leaderboard_based_topic'] ) ) {
                if ( $_POST['leaderboard_topic_based']['leaderboard_based_topic'] == 'specific_topic' ) {
                    unset( $_POST['leaderboard_topic_based']['leaderboard_select_topic_cat'] );
                }
                else {
                    unset( $_POST['leaderboard_topic_based']['leaderboard_select_topic'] );
                }
            }

            $leaderboard_topic_based = array();

            $leaderboard_topic_based_values = $_POST['leaderboard_topic_based'] ?? null;

            foreach ( $leaderboard_topic_based_values as $key => $value ) {
                if ( ! is_array( $value ) ) {
                    $leaderboard_topic_based[ $key ] = sanitize_text_field( $value );
                }
                else {
                    $leaderboard_topic_based[ $key ] = $this->sanitize_array_data( $value );
                }
            }

            update_post_meta( $post_id, 'leaderboard_topic_based', $leaderboard_topic_based );
        }


        if ( isset( $_POST['leaderboard_quiz_based'] ) ) {

            if ( isset( $_POST['leaderboard_quiz_based']['leaderboard_based_quiz'] ) ) {
                if ( $_POST['leaderboard_quiz_based']['leaderboard_based_quiz'] == 'specific_quiz' ) {
                    unset( $_POST['leaderboard_quiz_based']['leaderboard_select_quiz_cat'] );
                }
                else {
                    unset( $_POST['leaderboard_quiz_based']['leaderboard_select_quiz'] );
                }
            }

            $leaderboard_quiz_based = array();

            $leaderboard_quiz_based_values = $_POST['leaderboard_quiz_based'] ?? null;

            foreach ( $leaderboard_quiz_based_values as $key => $value ) {
                if ( ! is_array( $value ) ) {
                    $leaderboard_quiz_based[ $key ] = sanitize_text_field( $value );
                }
                else {
                    $leaderboard_quiz_based[ $key ] = $this->sanitize_array_data( $value );
                }
            }

            update_post_meta( $post_id, 'leaderboard_quiz_based', $leaderboard_quiz_based );
        }

        if ( isset( $_POST['leaderboard_lesson_based'] ) ) {

            if ( isset( $_POST['leaderboard_lesson_based']['leaderboard_based_lesson'] ) ) {
                if ( $_POST['leaderboard_lesson_based']['leaderboard_based_lesson'] == 'lesson_category' ) {
                    unset( $_POST['leaderboard_lesson_based']['leaderboard_lessons'] );
                }
                else {
                    unset( $_POST['leaderboard_lesson_based']['leaderboard_course_lesson_cat'] );
                }
            }

            $leaderboard_lesson_based = array();
            $leaderboard_lesson_based_values = $_POST['leaderboard_lesson_based'] ?? null;

            foreach ( $leaderboard_lesson_based_values as $key => $value ) {
                if ( ! is_array( $value ) ) {
                    $leaderboard_lesson_based[ $key ] = sanitize_text_field( $value );
                }
                else {
                    $leaderboard_lesson_based[ $key ] = $this->sanitize_array_data( $value );
                }
            }
            update_post_meta( $post_id, 'leaderboard_lesson_based', $leaderboard_lesson_based );
        }

        $this->mycred_leaderboard_generate_shortcode( $post_id );
    }

    function mycred_leaderboard_generate_shortcode( $post_id ) {

        $atts = array(
            'number' => '',
            'pt_types' => '',
            'type' => '',
            'based_on' => '',
            'ids' => '',
            'timeframe' => '',
            'start_date' => '',
            'end_date' => '',
            'per_page' => '',
            'show_pagination' => '',
            'associated_course_id' => '',
            'associated_lesson_topic_id' => '',
        );

        $settings = get_post_meta($post_id, 'leaderboard_default', false);

        $text_atts = '';
        foreach ($settings as $k => $setting) {

            if ($k == 'leaderboard_type') {
                $atts['type'] = 'learndash_'. $setting;
            } elseif ($k == 'leaderboard_users') {
                $atts['number'] = $setting;
            } elseif ($k == 'leaderboard_timefilter') {
                $atts['timeframe'] = $setting;
            } elseif ($k == 'leaderboard_pt_type') {
                $atts['pt_types'] = implode(",", $setting);
            } elseif ($k == 'mycred_leaderboard_custom_date') {
                $atts['start_date'] = $setting[0];
                $atts['end_date'] = $setting[1];
            } elseif ($k == 'leaderboard_pagination_value') {
                $atts['per_page'] = $setting;
            } elseif ($k == 'leaderboard_pagination') {
                $atts['show_pagination'] = $setting;
            } elseif ($k == 'leaderboard_associated_course_lesson') {
                $atts['associated_course_id'] = $setting;
            }
        }

        $message = '';

        if (!$atts['pt_types']) {
            $message .= __('Please select point type', 'mycred');
        }

        if ($atts['timeframe'] == 'custom_time') {
            if (empty($atts['start_date']) || empty($atts['end_date'])) {
                if ($message) {
                    $message .= __(' & ', 'mycred');
                }
                $message .= __('Please fill Custom Date', 'mycred');
            }
        }


        $type = (!empty($settings['leaderboard_type'])) ? $settings['leaderboard_type'] : '';

        
        foreach (get_post_meta($post_id, "leaderboard_{$type}_based", false) as $k => $value) {

            if ($k == 'learndash_lesson' || $k == 'learndash_lesson_topic') {
                $atts['associated_lesson_topic_id'] = $value;
            }

            if ($k == "leaderboard_associated_course_{$type}") {
                $atts['associated_course_id'] = $value;
            }
            if ($k == "leaderboard_based_{$type}") {
                $atts['based_on'] = $value;
            } elseif ($k == 'leaderboard_taxonomy' || $k == 'leaderboard_posts' || $k == 'leaderboard_course_lesson_cat' || $k == 'leaderboard_lessons' || $k == 'leaderboard_select_topic' || $k == 'leaderboard_select_topic_cat' || $k == 'leaderboard_select_quiz' || $k == 'leaderboard_select_quiz_cat') {
                $atts['ids'] = implode(",", $value);
            }
        }

        if (empty($atts['based_on'])) {
            if ($message) {
                $message .= __(' & ', 'mycred');
            }
            $message .= __("Please fill $type based on", 'mycred');
        }
        if (empty($atts['ids'])) {
            if ($message) {
                $message .= __(' & ', 'mycred');
            }
            $message .= __("Please add $type to select", 'mycred');
        }
        if ($message) {
            update_post_meta($post_id, 'leaderboard_shortcode', $message);
            return;
        }
        foreach ($atts as $k => $att) {
            $text_atts .= "$k='{$att}' ";
        }
        $shortcode = "[mycred_leaderboard_custom $text_atts ]";
        update_post_meta( $post_id, 'leaderboard_shortcode', $shortcode );
    }

    public function leaderboard_other_settings($post, $default) {?>
        <div class="leaderboard-settings">
            <div class="custom-option">
                <label>
                    <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                    <?php esc_html_e('Leaderboard for which point type?', 'mycred-learndash'); ?></label>
                <div class="option-wrapper">
                    <div class="form-element">
                        <div class="which-point">
                            <div>
                                <?php
                                if (mycred_get_types()) {

                                    $pt_meta = (!empty($default['leaderboard_pt_type'])) ? $default['leaderboard_pt_type'] : '';

                                    $leaderboard_point_type = explode(" ",$pt_meta);



                                    foreach (mycred_get_types() as $key => $pt_type) {

                                       

                                        ?>
                                        <div class="points-wrapper">
                                            <div class="type-point">
                                                <?php
                                                if (is_array($leaderboard_point_type)) {
                                                    $checked = 'checked="checked"';
                                                } else {
                                                    $checked = null;
                                                }



                                                
                                                ?>

                                                <input type="checkbox" name="leaderboard_setting[leaderboard_pt_type][]"
                                                       value="<?php echo esc_attr($key); ?>" <?php echo (is_array($leaderboard_point_type) ) ? 'checked="checked"' : '' ?> />
                                                       <?php echo esc_html($pt_type); ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="help-text" style="display: none;">Select the point type for which you want to create a leaderboard.</div>
                </div>
            </div>
            <div>
                <div class="custom-option">
                    <label>
                        <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                        <?php esc_html_e('Timing Filter of Leaderboard', 'mycred-learndash'); ?>
                    </label>
                    <div class="option-wrapper">
                        <div class="form-element">
                            <div class="timing-wrapper">
                                <div class="timing-filter">
                                    <select name="leaderboard_setting[leaderboard_timefilter]" id="timefilter">
                                        <?php 

                                            $leaderboard_timefilter = (!empty($default['leaderboard_timefilter'])) ? $default['leaderboard_timefilter'] : '';

                                         ?>
                                        <option value="<?php echo 'daily' ?>"
                                                <?php echo $leaderboard_timefilter == 'daily' ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Daily', 'mycred-learndash'); ?></option>
                                        <option value="<?php echo 'weekly' ?>"
                                                <?php echo $leaderboard_timefilter == 'weekly' ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Weekly', 'mycred-learndash'); ?></option>
                                        <option value="<?php echo 'monthly' ?>"
                                                <?php echo $leaderboard_timefilter == 'monthly' ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Monthly', 'mycred-learndash'); ?></option>
                                        <option value="<?php echo 'annually' ?>"
                                                <?php echo $leaderboard_timefilter == 'annually' ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Annually', 'mycred-learndash'); ?></option>
                                        <option value="<?php echo 'custom_time' ?>"
                                                <?php echo $leaderboard_timefilter == 'custom_time' ? 'selected' : ''; ?>>
                                            <?php esc_html_e('Custom Duration', 'mycred-learndash'); ?></option>
                                    </select>
                                    <?php
                                    echo 'balance';
                                    $custom_date = (isset($default['mycred_leaderboard_custom_date'])) ? $default['mycred_leaderboard_custom_date'] : '';

                                    ?>
                                </div>
                                <div id="custom-picker">
                                    <label for="from">From</label> <input type="text" id="from"
                                                                          name="leaderboard_setting[mycred_leaderboard_custom_date][]"
                                                                          value="<?php echo esc_attr($custom_date); ?>" />
                                    <label for="to">to</label> <input type="text" id="to"
                                                                      name="leaderboard_setting[mycred_leaderboard_custom_date][]"
                                                                      value="<?php echo esc_attr($custom_date); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="help-text" style="display: none;">Select a filter duration (Daily, Weekly, Monthly, Annually, Custom) and display your leaderboard data accordingly.</div>
                    </div>
                </div>
            </div>

            <?php
            if (count(get_users()) >= 1) :
                ?>

                <div class="custom-option">
                    <label>
                        <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                             <?php esc_html_e('Show Top', 'mycred-learndash'); ?>
                    </label>

                    <?php

                     $leaderboard_users =  (!empty($default['leaderboard_users'])) ? $default['leaderboard_users'] : '';

                    ?>



                    <div class="option-wrapper">
                        <div class="form-element">
                            <div class="user-count">
                                <input type="number" name="leaderboard_setting[leaderboard_users]"
                                       value="<?php echo esc_attr($leaderboard_users) ? esc_attr($leaderboard_users) : 10; ?>" /><?php esc_html_e('Users', 'mycred-learndash'); ?>
                            </div>
                        </div>
                        <div class="help-text" style="">Enter the number of entries you want to display on your leaderboard.</div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="custom-option">
                <label>
                    <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                         <?php esc_html_e('Show Pagination', 'mycred-learndash'); ?>
                </label>

                <?php 
                $leaderboard_pagination =  (!empty($default['leaderboard_pagination'])) ? $default['leaderboard_pagination'] : '';


                 $leaderboard_pagination_value =  (!empty($default['leaderboard_pagination_value'])) ? $default['leaderboard_pagination_value'] : '';
                ?>

                <div class="option-wrapper">
                    <div class="form-element">
                        <div class="show-pag">
                            <input type="checkbox" value="1" id="show-pagination" name="leaderboard_setting[leaderboard_pagination]"
                                   <?php echo $leaderboard_pagination ? 'checked' : '' ?> /><?php esc_html_e('Yes', 'mycred-learndash'); ?>
                            <div id="pagination-number">
                                <input type="number" name="leaderboard_setting[leaderboard_pagination_value]"
                                       value="<?php echo esc_attr($leaderboard_pagination_value); ?>" /><?php esc_html_e('Users per page', ',mycred-learndash'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="help-text" style="">Enter the number of users you want to display per page on your leaderboard.</div>
                </div>
            </div>

            <div class="custom-option">
                <label>
                    <img class="help-button" alt="" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/assets/images/question.png'); ?>">
                         <?php esc_html_e('Shortcode', 'mycred-learndash'); ?>
                </label>

                <div class="option-wrapper">
                    <div class="form-element">
                        <input type="text" size="100" readonly
                               value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'leaderboard_shortcode', true)); ?>"
                               name="leaderboard_shortcode" />
                    </div>
                    <div class="help-text" style="">Copy the shortcode and display the leaderboard on your desired page or post.</div>
                </div>
            </div>


            <?php
        }

        public function sanitize_array_data( $data ) {

            $sanitized_data = array();

            foreach ( $data as $key => $value ) {
                $sanitized_data[ $key ] = sanitize_text_field( $value );
            }

            return $sanitized_data;
        }

    }

    new MyCred_Learndash_Leaderboard();
    