<?php

/**
 * Ultimate Client Dash Landing Page Template
 *
 * @package   ultimate-client-dash
 * @copyright Copyright (c) 2019, WP Codeus
 * @license   GPL2+
 */

?>

<!DOCTYPE html>
<html>
<head>


    <!-- Meta Data -->
    <?php do_action( 'ucd_landing_page_meta' ); ?>


	  <!-- Link Stylesheets -->
		<link rel="profile" href="http://gmpg.org/xfn/11">
	  <link href="https://fonts.googleapis.com/css?family=Lato|Montserrat|Muli|Open+Sans|Oswald|Poppins|Raleway|Roboto|Source+Sans+Pro|Ubuntu" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	  <link rel="stylesheet" href="<?php echo plugins_url( 'templates/ucd-landing-page.css', dirname( __FILE__ ) ); ?>">

			  <!-- Landing Page dynamic styling -->
			  <style type="text/css">
						body {
						    background-size: cover!important;
						    background-position: center center;
						}
					  .ucd-construction-body {
					      background-image: url(<?php echo get_option('ucd_under_construction_background_image') ?>) !important;
								background-color: <?php echo get_option('ucd_under_construction_background_color') ?> !important;
					  }
					  h1, .ucd-message, .ucd-connect-title  {
					      color: <?php echo get_option('ucd_under_construction_text_color') ?>;
					      font-family: <?php echo get_option('ucd_under_construction_font_family') ?>;
					  }
					  .ucd-construction-body:before {
					      content: " ";
					      width: 100%;
					      height: 100%;
					      position: fixed;
					      z-index: -1;
					      top: 0;
					      left: 0;
					      background: <?php echo get_option('ucd_under_construction_overlay_color') ?>;
					      opacity: <?php echo get_option('ucd_under_construction_overlay_opacity') ?>;
					  }
					  .ucd-logo {
					      padding-bottom: <?php echo get_option('ucd_construction_logo_padding_bottom') ?>;
					  }
					  .ucd-button {
					      background: <?php echo get_option('ucd_under_construction_button_color') ?>;
					      border-radius: <?php echo get_option('ucd_under_construction_button_radius') ?>!important;
					  }
						.ucd-social-links a {
					      color: <?php echo get_option('ucd_under_construction_text_color') ?>;
						}
						.ucd-social-links a:hover {
								color: <?php echo get_option('ucd_under_construction_button_color') ?>;
						}
						.ucd-construction-wrapper .ucd-title {
								margin-bottom: <?php echo get_option('ucd_under_construction_title_padding_bottom') ?> !important;
						}
						.ucd-button {
					      color: <?php echo get_option('ucd_under_construction_text_color') ?>;
								color: <?php echo get_option('ucd_under_construction_button_text_color') ?>!important;
						}
						.ucd-social-links {
						    margin-top: 60px;
								margin-top: <?php echo get_option('ucd_under_construction_social_padding') ?>!important;
						    vertical-align: bottom;
						}
            .ucd-logo {
                width: auto;
                width: <?php echo get_option('ucd_under_construction_login_logo_width') ?> !important;
                height: auto;
            }

            /* Landing Page Custom CSS */
            <?php do_action( 'ucd_landing_page_custom_css' ); ?>

			  </style>

		<!-- Ultimate Client Dashboard Google Analytics -->
		<?php $propertyID = get_option('ucd_tracking_google_analytics'); // GA Property ID ?>

		<script type="text/javascript">
    		var _gaq = _gaq || [];
    		_gaq.push(['_setAccount', '<?php echo $propertyID; ?>']);
    		_gaq.push(['_trackPageview']);

    		(function() {
    		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    		})();
		</script>

</head>


<!-- Landing page content -->
<body class="ucd-construction-body">
		<div class="ucd-overlay">
				<div class="ucd-construction-wrapper">

        <!-- Landing Page Logo -->
				<img class="ucd-logo" src="<?php echo get_option('ucd_under_construction_login_logo') ?>"/>

        <!-- Landing Page Title -->
				<h1 class="ucd-title"><?php echo get_option('ucd_under_construction_title') ?></h1>

            <!-- Landing Page Message -->
						<div class="ucd-message">
						<?php echo do_shortcode("". get_option('ucd_under_construction_body') .""); ?>
						<?php
						$ucd_button_text = get_option('ucd_under_construction_button_text');
						$ucd_button_link = get_option('ucd_under_construction_button_link');
								if (!empty($ucd_button_text)) {
										echo "<div class='ucd-button-holder'><a class='ucd-button' href=' ". $ucd_button_link ." ' target='_blank'>" . $ucd_button_text . "</a></div>";
								}
								else {
								// Do not show Facebook Icon
								}
						?>
						</div>


		        <!-- Social Section -->
						<div class="ucd-social-links">

						<?php
						$ucd_social_facebook = get_option('ucd_under_construction_facebook');
						$ucd_social_instagram = get_option('ucd_under_construction_instagram');
						$ucd_social_twitter = get_option('ucd_under_construction_twitter');
						$ucd_social_linkedin = get_option('ucd_under_construction_linkedin');
						$ucd_social_youtube = get_option('ucd_under_construction_youtube');
						$ucd_social_title = get_option('ucd_under_construction_social_title');

            // Begin gathering content for social section

						if (!empty($ucd_social_facebook) || !empty($ucd_social_instagram) || !empty($ucd_social_twitter) || !empty($ucd_social_linkedin) || !empty($ucd_social_youtube)) {
								if ($ucd_social_title == "") {
    								echo "<div class='ucd-connect-title'>Connect With Us</div>";
  							} else {
      							echo "<div class='ucd-connect-title'>$ucd_social_title</div>";
  							}
						} else {
						    // Do not display social title
						}

  
				    // Display Facebook Icon
						$ucd_social_facebook = get_option('ucd_under_construction_facebook');
				    if (!empty($ucd_social_facebook)) {
								echo "<a href=' ". $ucd_social_facebook ."' target='_blank'><i class='fa fa-facebook' aria-hidden='true'></i></a>";
				    } else {
						    // Do not display Facebook Icon
				    }

						// Display Instagram Icon
						$ucd_social_instagram = get_option('ucd_under_construction_instagram');
				    if (!empty($ucd_social_instagram)) {
								echo "<a href=' ". $ucd_social_instagram ."' target='_blank'><i class='fa fa-instagram' aria-hidden='true'></i></a>";
				    } else {
						    // Do not display Instagram Icon
				    }

						// Display Twitter Icon
						$ucd_social_twitter = get_option('ucd_under_construction_twitter');
				    if (!empty($ucd_social_twitter)) {
								echo "<a href=' ". $ucd_social_twitter ."' target='_blank'><i class='fa fa-twitter' aria-hidden='true'></i></a>";
				    }
				    else {
						    // Do not display Twitter Icon
				    }

						// Display LinkedIn Icon
						$ucd_social_linkedin = get_option('ucd_under_construction_linkedin');
				    if (!empty($ucd_social_linkedin)) {
								echo "<a href=' ". $ucd_social_linkedin ."' target='_blank'><i class='fa fa-linkedin' aria-hidden='true'></i></a>";
				    }
				    else {
						    // Do not show Linkedin Icon
				    }

						// Display Youtube Icon
						$ucd_social_youtube = get_option('ucd_under_construction_youtube');
				    if (!empty($ucd_social_youtube)) {
								echo "<a href=' ". $ucd_social_youtube ."' target='_blank'><i class='fa fa-youtube' aria-hidden='true'></i></a>";
				    }
				    else {
						    // Do not show Youtube Icon
				    }
						?>

						</div>

				</div>
		</div>
</body>
</html>
