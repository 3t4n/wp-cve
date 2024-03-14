<?php
$mess_arr = array();
$mess_arr = get_custom_page_data();
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php echo $mess_arr['pageTitle']; ?></title>

    <!-- JS -->
    <!--<script src="http://code.jquery.com/jquery-latest.js"></script>-->
    <!--<script src="http://localhost:81/jquery/jquery.min.js"></script>-->
    <?php do_action('options_style'); ?>

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script>
	
		jQuery(document).ready(function(){
			jQuery("#countdown").countdown({
				date: "<?php echo $mess_arr['day'].' '.$mess_arr['month'].' '.$mess_arr['year'].' '.$mess_arr['hour'].':'.$mess_arr['minute'].':00'; ?>",
				format: "on"
			},
			
			function() {
				// callback function
			});
		});
	
	</script>
</head>
<body>

	<!-- LOGO -->
	<header class="container">
        <h1><font style="font-size: 40px; padding-bottom: -20px;"><?php echo $mess_arr['companyName']; ?></font></h1>
	</header>
	
	
	<!-- TIMER -->
	<div class="timer-area">
		
		<h1><font style="margin-bottom: 1em; font-size: 1em; line-height: 1.5em; color: white;"><?php echo $mess_arr['message']; ?></font></h1>
		
		<ul id="countdown">
			<li>
				<span class="days">00</span>
				<p class="timeRefDays">days</p>
			</li>
			<li>
				<span class="hours">00</span>
				<p class="timeRefHours">hours</p>
			</li>
			<li>
				<span class="minutes">00</span>
				<p class="timeRefMinutes">minutes</p>
			</li>
			<li>
				<span class="seconds">00</span>
				<p class="timeRefSeconds">seconds</p>
			</li>
		</ul>
		
	</div> <!-- end timer-area -->
	
	
	
	<!-- SIGNUP -->
	<div class="container">

		<h2>
            <font style="margin-bottom: 1em; font-size: .5em; line-height: 1.5em;">
            <?php if($mess_arr['contactEmail'] !=""){ ?>
                Contact Email : <?php echo $mess_arr['contactEmail']; ?>
            <?php } if($mess_arr['contactNumber'] !=""){ ?>
                <br />
                Contact Number : <?php echo $mess_arr['contactNumber']; } ?>
            </font>
		</h2>
        <?php if($mess_arr['facebookLink'] !="" || $mess_arr['twitterLink'] !="" || $mess_arr['googleLink'] !=""){ ?>
            <p>But you can get in touch by following us</p>
            <?php if($mess_arr['facebookLink'] !=""){ ?>
                <a href="<?php echo $mess_arr['facebookLink']; ?>"><img src=<?php echo TEMPLATE_URL . "images/fb.png"; ?> /></a>
            <?php } if($mess_arr['twitterLink'] !=""){ ?>
                <a href="<?php echo $mess_arr['twitterLink']; ?>"><img src=<?php echo TEMPLATE_URL . "images/twitter.png"; ?> /></a>
            <?php } if($mess_arr['googleLink'] !=""){ ?>
                <a href="<?php echo $mess_arr['googleLink']; ?>"><img src=<?php echo TEMPLATE_URL . "images/google.png"; ?> /></a>
            <?php }} ?>
	</div>
    <!-- end container -->



	<!-- FOOTER -->
	<footer id="main-footer">
		<p>&copy; Copyright <?php echo $mess_arr['year'].' '.$mess_arr['companyName']; ?>. All rights reserved.</p>
	</footer>

</body>
</html>