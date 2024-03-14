<?php
if ( ! defined('ABSPATH')) exit;  // if direct access



function job_bm_post_type_template_application($content) {

	global $post;

	if (is_singular('application') &&  $post->post_type == 'application'){

		ob_start();
		include( job_bm_plugin_dir . 'templates/application-single/application-single.php');

        wp_localize_script('job-bm-application-single', 'job_bm_ajax', array( 'job_bm_ajaxurl' => admin_url( 'admin-ajax.php')));
        wp_enqueue_script('job-bm-application-single');

        wp_enqueue_style('job_bm_application_single');
        wp_enqueue_style('font-awesome-5');
        wp_enqueue_script('job-bm-notice');
        wp_enqueue_style('job-bm-notice');

		return ob_get_clean();
	}
	else{
		return $content;
	}

}
add_filter( 'the_content', 'job_bm_post_type_template_application' );



function job_bm_application_comment_template( $comment_template ) {
    global $post;

    if(is_singular('application') && $post->post_type == 'application'){ // assuming there is a post type called business
        return job_bm_plugin_dir . 'templates/application-single/application-single-comments.php';
    }else{
        return $comment_template;
    }
}

add_filter( "comments_template", "job_bm_application_comment_template", 99 );





add_action('job_bm_single_application_main', 'job_bm_single_application_main_notice', 0);

if(!function_exists('job_bm_single_application_main_notice')){
    function job_bm_single_application_main_notice(){

        /**
         * by default "job-bm-notice" class hidden
         * add class "has-notice" to display
         * status class:  success, fail, error
         */
        ?>
        <div id="job-bm-notice" class="job-bm-notice <?php echo apply_filters('job_bm_notice_classes',''); ?>"><?php echo apply_filters('job_bm_notice_message',''); ?></div>

        <?php
    }
}



add_action( 'job_bm_single_application_main', 'job_bm_single_application_main_preview', 5 );
if ( ! function_exists( 'job_bm_single_application_main_preview' ) ) {
    function job_bm_single_application_main_preview(){

        if(is_preview()):
            ?>
            <div class="preview-notice"><?php echo __('This is preview of application, please do not share link.','job-board-manager'); ?></div>
        <?php
        endif;

    }
}

add_action( 'job_bm_single_application_main_no_access', 'job_bm_single_application_main_no_access', 5 );
if ( ! function_exists( 'job_bm_single_application_main_no_access' ) ) {
    function job_bm_single_application_main_no_access(){


            ?>
            <div class="preview-notice"><?php echo __('Sorry! you dont have permission to access this application.','job-board-manager'); ?></div>
        <?php


    }
}



add_action( 'job_bm_single_application_main', 'job_bm_single_application_main_card', 5 );
if ( ! function_exists( 'job_bm_single_application_main_card' ) ) {
    function job_bm_single_application_main_card(){
        $date_format = get_option( 'date_format' );

        $userid = get_current_user_id();

        $application_id = get_the_ID();
        $job_title = get_the_title();
        $post_date = get_the_date('Y-m-d');

        $content = get_the_content();

        $user_id = get_post_meta($application_id, 'user_id', true);
        $applicant_name = get_post_meta($application_id, 'applicant_name', true);

        $job_bm_am_user_email = get_post_meta($application_id, 'job_bm_am_user_email', true);
        $job_bm_am_job_id = get_post_meta($application_id, 'job_bm_am_job_id', true);
        $job_bm_am_apply_method = get_post_meta($application_id, 'job_bm_am_apply_method', true);
        $job_bm_am_attachment = get_post_meta($application_id, 'job_bm_am_attachment', true);
        $job_bm_am_resume_id = get_post_meta($application_id, 'job_bm_am_resume_id', true);



        $application_hired = get_post_meta($application_id, 'application_hired', true);
        $application_trash = get_post_meta($application_id, 'application_trash', true);
        $application_rating = (int) get_post_meta($application_id, 'application_rating', true);


        $applicant_name = !empty($applicant_name) ? $applicant_name : __('Anonymous','job-board-manager');
        $application_hired_text = ($application_hired =='yes') ?__('Hired','job-board-manager') : __('Hire','job-board-manager');


        $job_label = get_post_meta(get_the_ID(), 'job_bm_job_level',true);



        if(isset($_POST['comment_submit_hidden'])){
            $comment_text = isset($_POST['comment_text']) ? sanitize_text_field($_POST['comment_text']) : '';
            $application_id = isset($_POST['application_id']) ? sanitize_text_field($_POST['application_id']) : '';

            if(!empty($comment_text)):

                $time = current_time('mysql');

                $data = array(
                    'comment_post_ID' => $application_id,
            //        'comment_author' => '',
            //        'comment_author_email' => '',
            //        'comment_author_url' => '',
                    'comment_content' => $comment_text,
                    'user_id' => $userid,
                    'comment_date' => $time,
                );

                $comment_id = wp_insert_comment($data);

                do_action('job_bm_application_post_comment', $comment_id);
            endif;



            ?>
            <script>
                jQuery(document).ready(function($){

                    $('.comments').addClass('active');
                    $('.application-comments').fadeIn();

                })
            </script>
            <?php

        }











        ?>
        <div class="application-card application-card-<?php echo $application_id; ?> <?php if($application_trash =='yes') echo 'application-trash'; ?>">
            <div class="card-top">
                <span class="application-link" title="<?php echo __('Application ID.', 'job-board-manager'); ?>" class="" >#<?php echo $application_id; ?></span>
                <div class="application-action">
                    <span title="<?php echo $application_hired_text; ?>" class="hire <?php if($application_hired =='yes') echo 'hired'; ?>" application-id="<?php echo $application_id; ?>"><i class="fas fa-medal"></i></span>
                    <span title="<?php echo __('Trash','job-board-manager'); ?>" class="trash <?php if($application_trash =='yes') echo 'trashed'; ?>" application-id="<?php echo $application_id; ?>"><i class="far fa-trash-alt" ></i></span>
                    <span application-id="<?php echo $application_id; ?>" title="<?php echo __('Comments','job-board-manager'); ?>" class="comments"><i class="far fa-comments"></i></span>
                </div>

                <div current-rate="<?php echo $application_rating; ?>" application_id="<?php echo $application_id; ?>" title="<?php echo __('Ratings','job-board-manager'); ?>"  class="application-rate star">

                    <?php

                    for ($i=1; $i<=5; $i++){
                        if($i>$application_rating){
                            ?>
                            <i data-count="<?php echo $i; ?>" class="far fa-star"></i>
                            <?php
                        }else{
                            ?>
                            <i data-count="<?php echo $i; ?>" class="fas fa-star"></i>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
            <div class="card-body">
                <div class="applicant-content"><?php echo $content; ?></div>
                <div class="application-comments">

                    <div class="replies"><?php echo __('Conversations','job-board-manager'); ?></div>
                    <div class="comment-list">
                        <?php
                        $args = array(
                            'post_id' => $application_id, // use post_id, not post_ID
                            //'count' => true //return only the count
                            'number' => 5,

                        );
                        $comments = get_comments($args);


                        if(!empty($comments)):
                            foreach ($comments as $comment){

                                //var_dump($comment);


                                $user_id = $comment->user_id;
                                $comment_content = $comment->comment_content;
                                $comment_date = $comment->comment_date;


                                $comment_author = get_user_by("ID", $user_id);

                                //echo '<pre>'.var_export($current_user_job_ids, true).'</pre>';

                                ?>
                                <div class="comment">
                                    <div class="comment-author-avatar"><?php echo get_avatar($user_id, '60'); ?></div>

                                    <div class="comment-author"><?php echo $comment_author->display_name; ?></div>
                                    <div class="comment-date"><?php echo $comment_date; ?></div>

                                    <div class="comment-content"><?php echo $comment_content; ?></div>

                                </div>
                                <?php
                            }
                        else:
                            ?>
                            <div class="comment no-comment"><?php echo __('No reply yet. ','job-board-manager'); ?></div>

                        <?php
                        endif;

                        ?>

                    </div>
                    <div class="comment-form-wrap">
                        <div class="write-reply"><?php echo __('Write a reply','job-board-manager'); ?></div>

                        <form method="post" action="#">
                            <textarea class="comment-text" name="comment_text"></textarea>
                            <input class="comment-submit" type="submit" value="Submit" >
                            <input class="comment-submit-hidden" name="comment_submit_hidden" type="hidden" value="Y" >
                            <input class="comment-application-id" name="application_id" type="hidden" value="<?php echo esc_attr($application_id); ?>" >
                        </form>
                    </div>
                </div>

                <div title="<?php echo __('Applicant name','job-board-manager'); ?>" class="applicant-name"><i class="fas fa-user-graduate"></i> <?php echo $applicant_name; ?></div>
                <div class="applicant-assets">
                    <?php if(!empty($job_bm_am_user_email)):?>
                        <div title="<?php echo __('Email','job-board-manager'); ?>" class="applicant-email"><i class="far fa-envelope"></i> <a href="mailto:<?php echo $job_bm_am_user_email; ?>"><?php echo __('Email','job-board-manager'); ?></a> </div>
                    <?php endif; ?>

                    <?php if(!empty($job_bm_am_attachment)):?>
                        <div title="<?php echo __('Attachment','job-board-manager'); ?>" class="applicant-attachment"><i class="fas fa-paperclip"></i> <a href="<?php echo $job_bm_am_attachment; ?>"><?php echo __('Attachment','job-board-manager'); ?></a> </div>
                    <?php endif; ?>

                    <?php if(!empty($job_bm_am_resume_id)):?>
                        <div title="<?php echo __('Resume','job-board-manager'); ?>" class="applicant-resume"><i class="far fa-address-card"></i> <a href="<?php echo get_permalink($job_bm_am_resume_id); ?>"><?php echo __('Resume','job-board-manager'); ?></a> </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-bottom">
                <span title="<?php echo __('Date','job-board-manager'); ?>" class="post-date"><i class="far fa-calendar-alt"></i> <?php echo date_i18n($date_format,strtotime($post_date)); ?></span>
                <a title="<?php echo __('Job link','job-board-manager'); ?>" class="job-link" title="Job link" href="<?php echo get_permalink($job_bm_am_job_id); ?>"><i class="fas fa-external-link-square-alt"></i> <?php echo get_the_title($job_bm_am_job_id); ?></a>
            </div>

        </div>


        <?php


    }
}