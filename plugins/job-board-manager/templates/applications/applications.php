<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

$job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');
$job_bm_job_login_page_url = get_permalink($job_bm_job_login_page_id);

$job_bm_job_submit_page_id = get_option('job_bm_job_submit_page_id');
$job_bm_job_submit_page_url = get_permalink($job_bm_job_submit_page_id);

$job_bm_login_enable = get_option('job_bm_login_enable');
$job_bm_registration_enable = get_option('job_bm_registration_enable');
$job_bm_can_user_delete_application = get_option('job_bm_can_user_delete_application');
$job_bm_pagination_bg_color = get_option('job_bm_pagination_bg_color');
$job_bm_pagination_active_bg_color = get_option('job_bm_pagination_active_bg_color');
$job_bm_pagination_text_color = get_option('job_bm_pagination_text_color');

$date_format = get_option( 'date_format' );
$userid = get_current_user_id();

$current_user_job_ids = job_ids_by_user();

//var_dump($current_user_job_ids);

if(empty($current_user_job_ids)):
    echo sprintf(__('%s You haven\'t posted any jobs, please <a href="%s">post a job</a>', 'job-board-manager'), '<i class="fa fa-exclamation-circle" 
    aria-hidden="true"></i>', $job_bm_job_submit_page_url);

    return;
endif;

if(isset($_POST['comment_submit_hidden'])){
    $comment_text = isset($_POST['comment_text']) ? sanitize_text_field($_POST['comment_text']) : '';
    $application_id = isset($_POST['application_id']) ? sanitize_text_field($_POST['application_id']) : '';

    $time = current_time('mysql');

    $data = array(
        'comment_post_ID' => $application_id,
        'comment_content' => $comment_text,
        'user_id' => $userid,
        'comment_date' => $time,
    );

    $comment_id = wp_insert_comment($data);

    do_action('job_bm_application_post_comment', $comment_id);

}




?>

<div class="nav-head"><?php echo __('Applications', 'job-board-manager'); ?></div>
<div class="job-bm-application-list job-bm-applications">

    <?php

	if ( is_user_logged_in() ){

        $meta_query = array();
		$job_bm_list_per_page           = get_option('job_bm_list_per_page', 10);
		$job_bm_job_type_bg_color       = get_option('job_bm_job_type_bg_color');
		$job_bm_job_status_bg_color     = get_option('job_bm_job_status_bg_color');
		$job_bm_featured_bg_color       = get_option('job_bm_featured_bg_color');
		$job_bm_job_edit_page_id        = get_option('job_bm_job_edit_page_id');
		$job_bm_job_edit_page_url       = get_permalink($job_bm_job_edit_page_id);


			if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}


        $meta_query['relation'] = 'OR';

			$i=0;

//        if(!empty($current_user_job_ids))
//        foreach ($current_user_job_ids as $job_id){
//
//            $meta_query[] = array(

//                'relation'=>'OR',

//                array(
//                    'key'	 	=> 'job_bm_am_job_id',
//                    'value'	  	=> $job_id,
//                    'compare' 	=> '=',
//                ),
//                array(
//                    'key' => 'application_trash',
//                    'value'	  	=> 'yes',
//                    'compare' 	=> 'NOT EXISTS',
//                    'type' => 'CHAR',
//                )
//            );
//
//
//
//            $i++;
//        }


        $meta_query[] = array(
            array(
                'key'	 	=> 'job_bm_am_job_id',
                'value'	  	=> $current_user_job_ids,
                'compare' 	=> 'IN',
            ),
            array(
                'key' => 'application_trash',
                'value'	  	=> 'yes',
                'compare' 	=> 'NOT EXISTS',
                'type' => 'CHAR',
            )

        );

        //var_dump($current_user_job_ids);


        $wp_query_args['post_type'] = 'application';
        $wp_query_args['orderby'] = 'date';
        $wp_query_args['order'] = 'DESC';
        $wp_query_args['posts_per_page'] = $job_bm_list_per_page;
        $wp_query_args['paged'] = $paged;
        $wp_query_args['meta_query'] = $meta_query;


        //echo '<pre>'.var_export(($wp_query_args), true).'</pre>';

        //return;

        $wp_query = new WP_Query($wp_query_args);

		?>
        <div class="application-list">
        <?php

		$class_job_bm_functions = new class_job_bm_functions();

		$job_type_filters = $class_job_bm_functions->job_type_list();
		$job_level_filters = $class_job_bm_functions->job_level_list();
		$job_status_filters = $class_job_bm_functions->job_status_list(); 
	

		if ( $wp_query->have_posts() ) :
		    while ( $wp_query->have_posts() ) : $wp_query->the_post();

                $application_id = get_the_ID();
                $application_url = get_permalink($application_id);

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

                //var_dump($job_bm_am_job_id);

                ?>
                <div class="application-card application-card-<?php echo $application_id; ?> <?php if($application_trash =='yes') echo 'application-trash'; ?>">
                    <div class="card-top">
                        <a class="application-link" title="<?php echo __('Application ID.', 'job-board-manager'); ?>" class="" target="_blank" href="<?php echo $application_url; ?>" >#<?php echo $application_id; ?> </a>
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
                                        $user_id = $comment->user_id;
                                        $comment_content = $comment->comment_content;
                                        $comment_date = $comment->comment_date;


                                        $comment_author = get_user_by("ID", $user_id);

                                        //echo '<pre>'.var_export($current_user_job_ids, true).'</pre>';

                                        ?>
                                        <div class="comment">
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

			endwhile;
			
			?>
            </div>
            <div class="paginate">
            <?php
			$big = 999999999; // need an unlikely integer
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, $paged ),
				'total' => $wp_query->max_num_pages
				) );

			?>
            </div>
            <?php
	
			wp_reset_query();
            wp_reset_postdata();
			
			else:
			    echo sprintf(__('%s No application found.', 'job-board-manager'), '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>');
			endif;
	}
	else{
		echo sprintf(__('Please <a href="%s">login</a> to see your application list.', 'job-board-manager' ), $job_bm_job_login_page_url );
	}

	?>



        <style type="text/css">
            .job-bm-applications .paginate .page-numbers.current{
                background: <?php echo $job_bm_pagination_active_bg_color; ?>;
                color: <?php echo $job_bm_pagination_text_color; ?> ;
            }
            .job-bm-applications .paginate .page-numbers{
                background: <?php echo $job_bm_pagination_bg_color; ?>;
                color: <?php echo $job_bm_pagination_text_color; ?> ;
            }
        </style>

</div>