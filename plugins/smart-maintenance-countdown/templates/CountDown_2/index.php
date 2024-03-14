<?php
$mess_arr = array();
$mess_arr = get_custom_page_data();
?>
<!DOCTYPE html>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php echo $mess_arr['pageTitle']; ?></title>
    <!--<script src="http://code.jquery.com/jquery-latest.js"></script>-->
    <!--<script src="http://localhost:81/jquery/jquery.min.js"></script>-->
    <?php do_action('options_style'); ?>

<!--<script type="text/javascript" src="http://localhost:81/wordpress/wp-content/plugins/smart-maintenance-countdown/templates/TemplateWithCountDown_2/js/countdown.js"></script>-->

<!-- jquery countdown-->
<script type="text/javascript">
jQuery(function () {

var austDay = new Date("<?php echo $mess_arr['month'].' '.$mess_arr['day'].', '.$mess_arr['year'].' '.$mess_arr['hour'].':'.$mess_arr['minute'].':00'; ?>");
    jQuery('#defaultCountdown').countdown({until: austDay, layout: '{dn} {dl}, {hn} {hl}, {mn} {ml}, and {sn} {sl}'});
    jQuery('#year').text(austDay.getFullYear());
    });
</script>



</head>

<body>


<div class="container">
	
    <div id="header">
    
    	<div id="logo">
        	<h1><font style="font-size: 50px;">  <?php echo $mess_arr['companyName']; ?></font></h1>
        </div><!--end logo-->
            
        <div id="contact_details">
            <?php if($mess_arr['contactEmail'] !=""){ ?>
        	    <p><?php echo $mess_arr['contactEmail']; ?></p>
            <?php } if($mess_arr['contactNumber'] !=""){ ?>
			    <p>phone : <?php echo $mess_arr['contactNumber']; ?></p>
            <?php } ?>
		</div><!--end contact details-->     
                
	</div><!--end header-->
              <div style="clear:both"></div> 
              
	<div id="main">

		 <div id="content">
                    
              <div class="text">
              <h2><?php echo $mess_arr['message']; ?></h2>
              </div><!--end text-->
                  
              <div class="counter">
              <h3>Estimated Time Remaining Before Launch:</h3>
              <div id="defaultCountdown"></div>

         </div><!--end counter-->
                 
         <div class="details">

                  <div id="sliderwrap">
                  		<div id="slidertext"><!-- The slider -->
                            <ul>
                                <li>
                                    <?php if($mess_arr['facebookLink'] !="" || $mess_arr['twitterLink'] !="" || $mess_arr['googleLink'] !=""){ ?>
                                     <h3>You may find us below:</h3>
                                    <?php } ?>
                                     <div class="social">
                                    <?php if($mess_arr['facebookLink'] !=""){ ?>
                                     <a href="<?php echo $mess_arr['facebookLink']; ?>" class="facebook">Like Us on Facebook</a>
                                    <?php } if($mess_arr['twitterLink'] !=""){ ?>
                                     <a href="<?php echo $mess_arr['twitterLink']; ?>" class="twitter">Follow Us on Twitter</a>
                                    <?php } if($mess_arr['googleLink'] !=""){ ?>
                                     <a href="<?php echo $mess_arr['googlekLink']; ?>" class="google">Follow Us on Google</a>
                                    <?php } ?>
                                     </div>
                                </li><!-- Slider item -->
                            </ul>
                         </div><!-- End of slidertext -->
                      </div><!-- End of sliderwrap -->
                  </div><!--end details-->  
                </div><!--end content-->
            <p class="copyright">Copyright &copy; <?php echo $mess_arr['companyName']; ?>. All rights reserved</p>
</div><!--end main-->

</div><!--end class container-->

</body>

</html>
