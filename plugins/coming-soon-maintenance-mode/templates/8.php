<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title><?php echo esc_html($csmm_title); ?></title>
    <meta name="description" content="<?php echo esc_html($csmm_description); ?>">
    <meta name="author" content="">
    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="<?php echo esc_url( CSMM_URL.'templates/css/base.css' ); ?>">
    <link rel="stylesheet" href="<?php echo esc_url( CSMM_URL.'templates/css/vendor.css' ); ?>">
    <link rel="stylesheet" href="<?php echo esc_url( CSMM_URL.'templates/css/main.css' ); ?>">
    <link rel="stylesheet" href="<?php echo esc_url( CSMM_URL.'templates/css/8.css' ); ?>">
    <link rel="stylesheet" href="<?php echo esc_url( CSMM_URL.'admin/assets/fontawesome-free-6.2.1-web/css/all.min.css' ); ?>">
    <!-- script
    ================================================== -->
    <script src="<?php echo esc_url( CSMM_URL.'templates/js/modernizr.js' ); ?>"></script>
    <script src="<?php echo esc_url( CSMM_URL.'templates/js/pace.min.js' ); ?>"></script>
    <!-- favicons
    ================================================== -->
    <link rel="shortcut icon" href="<?php echo esc_url( CSMM_URL.'templates/favicon.ico' ); ?>" type="image/x-icon">
    <link rel="icon" href="<?php echo esc_url( CSMM_URL.'templates/favicon.ico' ); ?>" type="image/x-icon">
</head>
<body>
    <!-- home
    ================================================== -->
    <main class="s-home s-home--static template-two">

        <div class="home-content">

            <div class="row home-content__main text-center">
				<div class="frame-ipad">
					
					<div class="col-six">
					</div>
					<div class="col-eight text-wrap">

						<?php if($csmm_logo_id) { ?>
						<div class="home-logo">
							<a class="logo-dark" href="<?php echo get_site_url(); ?>">
								<img src="<?php echo esc_url( $csmm_logo_url[0] ); ?>" alt="<?php echo esc_attr( $csmm_logo_alt ); ?>">
							</a>
						</div>
						<?php } ?>

						<div class="home-content__text">

							<h1><?php if($csmm_title != "") { echo esc_html( $csmm_title ); } ?></h1>
							<p><?php if($csmm_description != "") { echo esc_textarea( stripslashes( $csmm_description ) ); } ?></p>

							<?php if($csmm_countdown == 1) { ?>
							<div class="home-content__counter">
								<div class="home-content__clock">
									<div class="time days">
										325
										<span>D</span>
									</div>
									<div class="time hours">
										09
										<span>H</span>
									</div>
									<div class="time minutes">
										54
										<span>M</span>
									</div>
									<div class="time seconds">
										30
										<span>S</span>
									</div>
								</div>  <!-- end home-content__clock -->
							</div>  <!-- end home-content__counter -->
							<?php } ?>
						</div>  <!-- end home-content__text -->

						<ul class="home-social">
							<?php if(empty($csmm_sm_facebook) == false) { ?>
							<li><a href="<?php echo esc_url($csmm_sm_facebook); ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
							<?php } ?>
							<?php if(empty($csmm_sm_twitter) == false) { ?>
							<li><a href="<?php echo esc_url($csmm_sm_twitter); ?>" target="_blank"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a></li>
							<?php } ?>
							<?php if(empty($csmm_sm_instagram) == false) { ?>
							<li><a href="<?php echo esc_url($csmm_sm_instagram); ?>" target="_blank"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a></li>
							<?php } ?>
						</ul> <!-- end home-social -->
					</div>

				</div>  <!-- frame ipad -->
            </div>  <!-- end home-content__main -->

        </div> <!-- end home-content -->

    </main> <!-- end s-home -->

    <!-- Java Script
    ================================================== -->
    <?php 
    // Javascript
    $csmm_include_url = includes_url();
    $csmm_last = $csmm_include_url[strlen( $csmm_include_url )-1];
    if ( $csmm_last != '/' ) {
        $csmm_include_url = $csmm_include_url . '/';
    }
    ?>
	<script src="<?php echo esc_url($csmm_include_url); ?>js/jquery/jquery.js"></script>
    <script src="<?php echo esc_js(CSMM_URL.'templates/js/plugins.js'); ?>"></script>
    <script>
    jQuery( document ).ready(function() {
        // Add the User Agent to the <html>
        // will be used for IE10 detection (Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0))
        var doc = document.documentElement;
        doc.setAttribute('data-useragent', navigator.userAgent);
        // svg fallback
        if (!Modernizr.svg) {
            jQuery(".home-logo img").attr("src", "images/logo.png");
        }
      
        <?php if($csmm_countdown == 1) { ?>
       /* final countdown
        * ------------------------------------------------------ */
        var CsmmFinalCountdown = function() {
            var finalDate =  new Date("<?php echo esc_js($csmm_launch_dt); ?>").getTime(); // date format: March 25, 2024 15:37:25
            // updating countdown time start
            jQuery('.home-content__clock').countdown(finalDate)
            .on('update.countdown', function(event) {
                var str = '<div class=\"time days\">' +
                          '%D <span>D</span>' + 
                          '</div></div>' +
                          '<div class=\"time hours\">' +
                          '%H <span>H</span></div>' +
                          '<div class=\"time minutes\">' +
                          '%M <span>M</span></div>' +
                          '<div class=\"time seconds\">' +
                          '%S <span>S</span>';
                jQuery(this)
                .html(event.strftime(str));
            });
            // updating countdown time end
            
            // when countdown time finish start
            jQuery('.home-content__clock').countdown(finalDate)
            .on('finish.countdown', function(event) {
                // hide counter start
                jQuery( ".home-content__counter" ).fadeOut( "slow" );
                // hide counter end
            });
            // when countdown time finish end
        };
        <?php } ?>

       /* initialize
        * ----------------------------------------------- */
        (function ssInit() {
            <?php if($csmm_countdown == 1) { ?>
            CsmmFinalCountdown();
            <?php } ?>
        })();
    });
    </script>
</body>
</html>