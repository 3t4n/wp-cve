<?php
$mess_arr = array();
$mess_arr = get_custom_page_data();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php echo $mess_arr['pageTitle']; ?></title>
        <?php do_action('options_style'); ?>
    </head>
    <body>
		<div id="wrapper">
			<div id="content">
                <br /><br /><br /><br />
				<h1><?php echo $mess_arr['companyName']; ?></h1>
				<!--<p id="o">Our website is</p>-->
				<h2><?php echo $mess_arr['message']; ?></h2>
                <?php if($mess_arr['facebookLink'] !="" || $mess_arr['twitterLink'] !="" || $mess_arr['googleLink'] !=""){ ?>
				<p><br /><br /><br />But you can get in touch by following us</p>
                <?php if($mess_arr['facebookLink'] !=""){ ?>
				    <a href="<?php echo $mess_arr['facebookLink']; ?>"><img src=<?php echo TEMPLATE_URL . "images/fb.png"; ?> /></a>
                <?php } if($mess_arr['twitterLink'] !=""){ ?>
                    <a href="<?php echo $mess_arr['twitterLink']; ?>"><img src=<?php echo TEMPLATE_URL . "images/twitter.png"; ?> /></a>
                <?php } if($mess_arr['googleLink'] !=""){ ?>
                    <a href="<?php echo $mess_arr['googleLink']; ?>"><img src=<?php echo TEMPLATE_URL . "images/google.png"; ?> /></a>
                <?php }} ?>
<!--                <a href="#"><img src="images/linkedin.png" /></a>-->
				<p><?php if($mess_arr['contactEmail'] !=""){ ?>
                    Contact Email : <?php echo $mess_arr['contactEmail']; ?>
                    <?php } if($mess_arr['contactNumber'] !=""){ ?>
                    <br />
                    Contact Number : <?php echo $mess_arr['contactNumber']; } ?>
                </p>
<!--				<form method="post" action="#">-->
<!--				<div id="sub">-->
<!--					<input type="email" name="email" id="email" placeholder="your@email.com" onfocus="this.placeholder = ''" onblur="this.placeholder = 'your@email.com'" />-->
<!--					<input type="submit" value="Submit" />-->
<!--				</div>-->
<!--				</form>-->
			</div>
		</div>
	</body>
</html>




