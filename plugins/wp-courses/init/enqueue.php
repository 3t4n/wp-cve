<?php

    add_action( 'wp_print_scripts', 'wpc_dequeue_premium', 100 );

    function wpc_dequeue_premium(){
        wp_dequeue_script( 'wpcp-script' ); // js files no longer stored in WP Courses Premium after version 3.07
    }

    // enqueue scripts
    function wpc_enqueue_scripts(){
        wp_enqueue_script('jquery');
        wp_enqueue_style( 'font-awesome-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' );
        wp_enqueue_script('jquery-ui-js');
        wp_enqueue_script('jquery-ui-css');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script("jquery-ui-tabs");
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_style('wpc-spectrum-css', plugins_url('../css/spectrum.min.css', __FILE__));
        wp_enqueue_script('wpc-spectrum', plugins_url('../js/spectrum/spectrum.min.js', __FILE__ ), 'jquery', null, true);
        wp_enqueue_style( 'wpc-style-ajax', plugins_url('../css/style.css',  __FILE__ ), array(), 1.1);
        wp_enqueue_style( 'bx-slider', plugins_url('../css/jquery.bxslider.css',  __FILE__ ));
        wp_enqueue_style( 'wpc-spinners', plugins_url('../css/spinners.css',  __FILE__ ));
        wp_enqueue_script('bx-slider', plugins_url('../js/jquery.bxslider.min.js',  __FILE__ ), 'jquery', null, false);
        wp_enqueue_script('wpc-script', plugins_url('../js/wpc-js.js',  __FILE__ ), 'jquery', null, false);
        wp_enqueue_script('wpc-ui', plugins_url('../js/ui.js',  __FILE__ ), 'jquery', null, false);

        $post_type = get_post_type();
        $single = is_single();

        wp_enqueue_script('wpc-quizzes', plugins_url('../js/quizzes.js',  __FILE__ ), 'jquery', null, false);

        if($post_type === 'wpc-certificate' && is_single()){
            wp_enqueue_script('wpc-certificates', plugins_url('../js/certificates.js',  __FILE__ ), 'jquery', null, false);
        }
        
    }
    add_action( 'wp_enqueue_scripts', 'wpc_enqueue_scripts');

    function wpc_enqueue_admin_scripts(){
        wp_enqueue_script('wpc-charts', plugins_url('../js/chartjs/dist/chart.js',  __FILE__ ));
        wp_enqueue_script('wpc-admin-js', plugins_url('../js/wpc-admin.js', __FILE__), 'jquery');
        wp_enqueue_style( 'font-awesome-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' );
        wp_enqueue_script('wpc-select2-js', plugins_url('../js/select2.min.js', __FILE__ ), 'jquery', null, true);
        wp_enqueue_style('wpc-select2-css', plugins_url('../css/select2.min.css', __FILE__));
        wp_enqueue_script('jquery-ui-js');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script("jquery-ui-tabs");
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' ); 
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_style('wpc-spectrum-css', plugins_url('../css/spectrum.min.css', __FILE__));
        wp_enqueue_script('wpc-spectrum', plugins_url('../js/spectrum/spectrum.min.js', __FILE__ ), 'jquery', null, true);
        wp_enqueue_style( 'wpc-spinners', plugins_url('../css/spinners.css',  __FILE__ ));
        wp_enqueue_style( 'wpc-admin-style-ajax', plugins_url('../css/style.css',  __FILE__ ), array(), 1.1);
        wp_enqueue_script('wpc-script', plugins_url('../js/wpc-js.js',  __FILE__ ), 'jquery', null, false);
        wp_enqueue_script('wpc-ui', plugins_url('../js/ui.js',  __FILE__ ), 'jquery', null, false);

        $post_type = get_post_type();
        $single = is_single();

        wp_enqueue_script('wpc-quizzes', plugins_url('../js/quizzes.js',  __FILE__ ), 'jQuery', null, false);

        if($post_type === 'wpc-certificate'){
            wp_enqueue_script('wpc-certificates', plugins_url('../js/certificates.js',  __FILE__ ), 'jQuery', null, false);
        }
        
        if($post_type === 'lesson'){
            wp_enqueue_script('wpc-attachments', plugins_url('../js/attachments.js',  __FILE__ ), 'jQuery', null, false);
        }
        
    }
    add_action( 'admin_enqueue_scripts', 'wpc_enqueue_admin_scripts' );

    function wpc_localize_scripts(){

        wp_localize_script( 'wpc-ui', 'wpc_ajax', array( 'url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('wpc_nonce') ) );
        wp_localize_script( 'wpc-quizzes', 'wpc_ajax', array( 'url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('wpc_nonce') ) );
        wp_localize_script( 'wpc-admin-js', 'wpc_ajax', array( 'url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('wpc_nonce') ) );

        // FRONT END Translations
        $translation_array = array(
            'completed'             => esc_html(__( 'Completed', 'wp-courses' )),
            'notCompleted'          => esc_html(__( 'Mark Completed', 'wp-courses' )),
            'emptyTable'            => esc_html(__( 'No data available in table', 'wp-courses' )),
            'infoEmpty'             => esc_html(__( 'There are 0 entries', 'wp-courses' )),
            'infoFiltered'          => esc_html(__( 'Filtered from a total entry count of', 'wp-courses' )),
            'lengthMenu'            => esc_html(__( 'Entries', 'wp-courses' )),
            'loadingRecords'        => esc_html(__( 'Loading...', 'wp-courses' )),
            'processing'            => esc_html(__( 'Processing...', 'wp-courses' )),
            'search'                => esc_html(__( 'Search', 'wp-courses' )),
            'zeroRecords'           => esc_html(__( 'No matching records found', 'wp-courses' )),
            'first'                 => esc_html(__( 'First', 'wp-courses' )),
            'last'                  => esc_html(__( 'Last', 'wp-courses' )),
            'next'                  => esc_html(__( 'Next', 'wp-courses' )),
            'previous'              => esc_html(__( 'Previous', 'wp-courses' )),
            'sortAscending'         => esc_html(__( 'activate to sort column ascending', 'wp-courses' )),
            'sortDescending'        => esc_html(__( 'activate to sort column descending', 'wp-courses' )),
        );

        wp_localize_script( 'wpc-script', 'WPCTranslations', $translation_array );

        // ADMIN Translations
        $translation_array = array(
            'whenSomeone'           => esc_html(__( 'When Someone', 'wp-courses' )),
            'views'                 => esc_html(__( 'Views', 'wp-courses' )),
            'completes'             => esc_html(__( 'Completes', 'wp-courses' )),
            'scores'                => esc_html(__( 'Scores', 'wp-courses' )),
            'anyCourse'             => esc_html(__( 'Any Course', 'wp-courses' )),
            'aSpecificCourse'       => esc_html(__( 'A Specific Course', 'wp-courses' )),
            'anyLesson'             => esc_html(__( 'Any Lesson', 'wp-courses' )),
            'aSpecificLesson'       => esc_html(__( 'A Specific Lesson', 'wp-courses' )),
            'anyModule'             => esc_html(__( 'Any Module', 'wp-courses' )),
            'aSpecificModule'       => esc_html(__( 'A Specific Module', 'wp-courses' )),
            'anyQuiz'               => esc_html(__( 'Any Quiz', 'wp-courses' )),
            'aSpecificQuiz'         => esc_html(__( 'A Specific Quiz', 'wp-courses' )),
            'none'                  => esc_html(__( 'none', 'wp-courses' )),
            'percent'               => esc_html(__( 'Percent', 'wp-courses' )),
            'times'                 => esc_html(__( 'Times', 'wp-courses' )),
            'deleteRequirement'     => esc_html(__( 'Delete Requirement', 'wp-courses' )),
        );

        wp_localize_script( 'wpc-admin-js', 'WPCAdminTranslations', $translation_array );

        // Localize the script with new data
        $translation_array = array(
            'lesson'                => __( 'lesson', 'wp-courses-premium' ),
            'lessons'               => __( 'lessons', 'wp-courses-premium' ),
            'module'                => __( 'module', 'wp-courses-premium' ),
            'modules'               => __( 'modules', 'wp-courses-premium' ),
            'course'                => __( 'course', 'wp-courses-premium' ),
            'courses'               => __( 'courses', 'wp-courses-premium' ),
            'quiz'                  => __( 'quiz', 'wp-courses-premium' ),
            'quizzes'               => __( 'quizzes', 'wp-courses-premium' ),
            'view'                  => __( 'view', 'wp-courses-premium' ),
            'complete'              => __( 'complete', 'wp-courses-premium' ),
            'score'                 => __( 'score', 'wp-courses-premium' ),
            'any'                   => __( 'any', 'wp-courses-premium' ),
            'in'                    => __( 'in', 'wp-courses-premium' ),
            'of'                    => __( 'of', 'wp-courses-premium' ),
            'on'                    => __( 'on', 'wp-courses-premium' ),
            'or'                    => __( 'or', 'wp-courses-premium' ),
            'requirements'          => __( 'Requirements', 'wp-courses-premium' ),
            'onAnyQuiz'             => __( 'on any quiz', 'wp-courses-premium' ),
            'uniqueQuizzes'         => __( 'unique quizzes', 'wp-courses-premium' ),
        );

        wp_localize_script( 'wpc-script', 'WPCBadgesTranslations', $translation_array );

        // Localize the script with new data
        $quiz_translation_array = array(
            'question'              => __( 'Question', 'wp-courses' ),
            'yourAnswer'            => __( 'Your Answer', 'wp-courses' ),
            'addAnswer'             => __( 'Add Answer', 'wp-courses' ),
            'answers'               => __( 'Answers', 'wp-courses' ),
            'correctAnswer'         => __( 'Correct Answer', 'wp-courses' ),
            'selectAnswer'          => __('Please select an answer for question', 'wp-courses'),
            'youScored'             => __( 'You Scored', 'wp-courses' ),
            'twoAnswers'            => __('You must have at least 2 possible answers', 'wp-courses'),
            'twelveAnswers'         => __('No more than 12 possible answers per question are allowed', 'wp-courses'),
            'quizSaved'             => __('Quiz Successfully Saved', 'wp-courses'),
            'attemptsRemaining'     => __('Attempts Remaining', 'wp-courses'),
            'noAttemptsRemaining'   => __('You have no attempts remaining.', 'wp-courses'),
            'startQuiz'             => __('Start Quiz', 'wp-courses'),
            'restartQuiz'           => __('Restart Quiz', 'wp-courses'),
            'submitQuiz'            => __('Submit Quiz', 'wp-courses'),
            'nextQuestion'          => __('Next Question', 'wp-courses'),
            'prevQuestion'          => __('Prev Question', 'wp-courses'),
            'backToQuiz'            => __('Back to Quiz', 'wp-courses'),
            'continue'              => __('Continue', 'wp-courses'),
            'emptyAnswers'          => __("You haven't filled in every answer.  Are you sure you want to submit this quiz?", 'wp-courses'),
            'areYouSure'            => __("Are you sure you'd like to submit this quiz?  You cannot undo this action.", 'wp-courses'),
            'answerAllQuestions'    => __('You must answer every question before submitting this quiz.', 'wp-courses'),
            'back'                  => __('Back', 'wp-courses'),
            'quizResults'           => __('Quiz Results', 'wp-courses'),
        );

        wp_localize_script( 'wpc-script', 'WPCQuizTranslations', $quiz_translation_array );
        wp_localize_script( 'wpc-quizzes', 'WPCQuizTranslations', $quiz_translation_array );
    }

    add_action('admin_enqueue_scripts', 'wpc_localize_scripts');
    add_action('wp_enqueue_scripts', 'wpc_localize_scripts');
?>