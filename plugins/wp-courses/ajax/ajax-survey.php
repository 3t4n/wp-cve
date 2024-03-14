<?php

add_action('wp_ajax_wpc_submit_deactivation_survey', 'wpc_submit_deactivation_survey');
function wpc_submit_deactivation_survey() {
    check_ajax_referer('wpc-deactivate-survey', 'security');

    $otherDetails = sanitize_textarea_field($_POST['otherDetails']);
    $businessType = sanitize_textarea_field($_POST['businessType']);
    $checked = $_POST['checkValues'];

    for($c = 0; $c < count($checked); $c++ ) {
        $checked[$c] = sanitize_title($checked[$c]);
    }

    $message = '<table>';

    $message .= '<thead>';

    $message .= '<tr><th></th><th>Content</th></tr>';

    $message .= '</thead>';

    $message .= '<tbody>';

    $message .= '<tr><td>Website</td><td>' . esc_url(home_url()) . '</td></tr>';
    $message .= '<tr><td>Other Reasons</td><td>' . esc_html($otherDetails) . '</td></tr>';
    $message .= '<tr><td>Business Type</td><td>' . esc_html($businessType) . '</td></tr>';

    $c = 1;
    foreach ($checked as $check) {
        $message .= '<tr><td>Reason ' . esc_html($c) . '</td><td>' . esc_html(str_replace('-', ' ', $check)) . '</td></tr>';
        $c++;
    }

    $message .= '</tbody>';

    $message .= '</table>';

    $to = 'info@wpcoursesplugin.com';
    $subject = 'WP Courses Deactivation Survey';

    $headers[] = 'Content-Type: text/html;';
    $headers[] = 'charset=UTF-8';

    $mail = wp_mail($to, $subject, wp_kses($message, 'post'), $headers);

    echo json_encode($mail);

    wp_die();
}

add_action('admin_footer', 'wpc_deactivate_survey_scripts');
function wpc_deactivate_survey_scripts() {
    $ajax_nonce = wp_create_nonce("wpc-deactivate-survey");
    ?>
    <script type="text/javascript">

		/*****************************************
		************ Deactivate Survey ***********
		*****************************************/

        (function ($) {
            $(function () {
                $(document).on('click', 'a#deactivate-wp-courses', function (e) {
                    var deactivateLink = $(this).attr('href');
                    e.preventDefault();
                    var form = '<div id="wpc-deactivate-survey-form" class="wpc-form">';
                    
			            form += '<div class="wpc-form-section">';
			            	form += '<p class="wpc-form-text">Would you mind letting us know why you\'re deactivating WP Courses so we can make improvements in future versions?  Select all that apply.</p>';
			            form += '</div>';

		            	form += '<div class="wpc-form-section">';
			                form += '<div class="wpc-form-item"><input class="wpc-form-check" id="wpc-deactivate-too-complex" type="checkbox" value="too-complex"/> <label for="wpc-deactivate-too-complex">Too complicated/confusing</label></div>';
			                form += '<div class="wpc-form-item"><input class="wpc-form-check" id="wpc-deactivate-missing-features" type="checkbox" value="missing-features"/> <label for="wpc-deactivate-missing-features">Missing features</label></div>';
			                form += '<div class="wpc-form-item"><input class="wpc-form-check" id="wpc-deactivate-found-another" type="checkbox" value="found-another-plugin"/> <label for="wpc-deactivate-found-another">I\'ve found another plugin</label></div>';
			                form += '<div class="wpc-form-item"><input class="wpc-form-check" id="wpc-deactivate-theme-integration" type="checkbox" value="theme-integration"/> <label for="wpc-deactivate-theme-integration">Doesn\'t work well with my theme</label></div>';
			                form += '<div class="wpc-form-item"><input class="wpc-form-check" id="wpc-deactivate-other" type="checkbox" value="other"/> <label for="wpc-deactivate-other">Other</label></div>';
			            form += '</div>';

		                form += '<div class="wpc-form-section">';
		                	form += '<label>What type of business were you thinking of using WP Courses for?</label><br> <input id="wpc-deactivate-business-type" class="wpc-form-input-text" type="text" value="" placeholder="Yoga lessons, corporate training, client sites, etc."/><br/>';
		               	form += '</div>';

		                form += '<div id="wpc-deactivate-survey-other-details-wrapper" class="wpc-form-section" style="display: none;"><label>Could you tell us why you\'re deactivating WP Courses?</label><br>';
		                form += '<textarea id="wpc-deactivate-survey-other-details" placeholder="I\'m deactivating WP Courses because..."></textarea></div>';

		                form += '<div class="wpc-form-section">';
			                form += '<button id="wpc-submit-deactivate-survey" class="wpc-btn" type="button" value="submit">Submit and Deactivate</button>';
			                form += '<a href="' + deactivateLink + '" class="wpc-btn wpc-btn-text" type="button" value="" style="float: right; margin-left: 10px;">Skip and Deactivate</a>';
			                form += '<button id="wpc-cancel-deactivate" class="wpc-btn wpc-btn-solid" type="button" value="cancel" style="float: right;">Cancel</button>';
		                form += '</div>';

                    form += '</div>';
                    $('.wpc-lightbox-title').html('Why are you deactivating WP Courses?');
                    $('.wpc-lightbox-content').html(form);
                    $('.wpc-lightbox-wrapper').fadeIn();
                
			    });

			    // SEND and deactivate
			    $(document).on('click', '#wpc-submit-deactivate-survey', function(){

			    	var deactivateLink = $('a#deactivate-wp-courses').attr('href');

			        var checkValues = [];

			        $('.wpc-form-check').each(function(){
			        	if($(this).prop('checked') === true) {
							checkValues.push($(this).val());
			        	}
			        });

			        var data = {
			            'action'        : 'wpc_submit_deactivation_survey',
			            'checkValues'   : checkValues,
			            'businessType'  : $('#wpc-deactivate-business-type').val(),
			            'otherDetails'  : $('#wpc-deactivate-survey-other-details').val(),
						'security'		: '<?php echo $ajax_nonce; ?>'
			        };

			        $('.wpc-lightbox-content').hide();
			        $('.wpc-lightbox-content').html('<i class="fa fa-spinner fa-spin"></i> Sending...');
			        $('.wpc-lightbox-content').fadeIn();

			        $.post(ajaxurl, data, function(response){

			            $('.wpc-lightbox-content').hide();
			            $('.wpc-lightbox-content').html('<div class="wpc-success-message">Thanks for your feedback!  WP Courses is now deactivating...</div>');
			            $('.wpc-lightbox-content').fadeIn();

			            window.location.href = deactivateLink;

			        });

			    });

			    $(document).on('click', '#wpc-deactivate-survey-form .wpc-form-check', function(){

			        if( $(this).prop('checked') === true && $(this).val() === 'other' ) {
			            $('#wpc-deactivate-survey-other-details-wrapper').fadeIn();
			        } else if($(this).prop('checked') === false && $(this).val() === 'other' ) {
			            $('#wpc-deactivate-survey-other-details-wrapper').fadeOut();
			        }

			    });

			});

		}(jQuery));

	</script>

<?php }

add_action('wp_ajax_wpc_submit_survey', 'wpc_submit_survey');
function wpc_submit_survey() {
    check_ajax_referer('wpc-survey', 'security');
    update_option('wpc_survey_sent', true);
    wp_die();
}

add_action('admin_footer', 'wpc_survey_scripts');
function wpc_survey_scripts() {
    $run = 'false';
    $post_type = get_post_type();
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    $run = ($post_type === 'course' || $post_type === 'lesson' || $post_type === 'wpc-quiz' || $post_type === 'teacher' || $post_type === 'manage_students' || $post_type === 'wpc-email' || $post_type === 'wpc-badge' || $post_type === 'wpc-certificate' || $page === 'wpc_settings' || $page === 'wpc_options' || $page === 'wpc_help') ? 'true' : 'false';
    $ajax_nonce = wp_create_nonce("wpc-survey");
    ?>
    <script type="text/javascript">
        
		function wpSurveyForm() {

			var rating = '<p class="wpc-form-text wpc-rating-title">Hi <?php echo get_user_and_fallback(); ?>,<img class="waving-hand" src="<?php echo WPC_PLUGIN_URL; ?>images/waving-hand.svg" alt="waving hand"></p>';
            rating += '<p class="wpc-form-text">Thank you for using our plugin!</p>';
			rating += '<p class="wpc-form-text">Will you be willing to write us a review?<br>We are constantly releasing new features and bug fixes.<br><a id="wpc-click-rating-link" target="_blank" href="https://wordpress.org/support/plugin/wp-courses/reviews/#new-post">LEAVE 5-STAR REVIEW NOW</a></p>';
			rating += '<p class="wpc-form-text">Thank you again for your time!</p>';
			rating += '<p class="wpc-form-text">Best Regards,<br>WP Courses team</p>';
			rating += '<style>.wpc-lightbox { max-width: 540px; }</style>';

            return rating;

		}

		/*****************************************
		************** General Survey ************
		*****************************************/

		(function ($) {
			$(function () {

				var runSurvey = <?php echo $run; ?>;
				var surveySent = <?php echo empty(get_option('wpc_survey_sent')) ? 'false' : 'true'; ?>;
				var dismissSurveyShort = <?php echo get_transient('wpc_dismiss_survey_short') === 'true' ? 'true' : 'false'; ?>;

	            if(runSurvey === true && surveySent != true && dismissSurveyShort === false) {
					// Inject rating message
					var pluginUrl = "<?php echo WPC_PLUGIN_URL; ?>";
					var rating = wpSurveyForm();

					$('.wpc-lightbox-title').html('<img src="' + pluginUrl + 'images/wpc-logo-sm.png"/>');
					$('.wpc-lightbox-content').html(rating);
					$('.wpc-lightbox-wrapper').fadeIn();

		            // Click rating link
				    $(document).on('click', '#wpc-click-rating-link', function() {
				        var data = {
				            'action': 'wpc_submit_survey',
							'security': '<?php echo $ajax_nonce; ?>'
				        };

				        $.post(ajaxurl, data, function(response){
				            $('.wpc-lightbox-content').hide();
				            $('.wpc-lightbox-content').html('<p><div class="wpc-success-message">Thank you for sharing your review!</div></p>');
				            $('.wpc-lightbox-content').fadeIn();
				        });
				    });
				}

			});

		}(jQuery));

    </script>
    <?php
// Only set the transient on certain WP Courses pages and survey modal can only show at most once every 24 hours
    $run == 'true' ? set_transient('wpc_dismiss_survey_short', 'true', 86400) : '';
?>

<?php } ?>