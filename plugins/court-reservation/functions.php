<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * get array of current date with summer time (DST) correction
 *
 * @return array
 */
function getCurrentDateTime() {
	$timezone_string = get_option( 'timezone_string' );
	// error_log('timezone_string1: ' . $timezone_string);
	if ( empty( $timezone_string ) ) {
		$offset          = get_option( 'gmt_offset' );
		$hours           = (int) $offset;
		$minutes         = abs( ( $offset - (int) $offset ) * 60 );
		$timezone_string = sprintf( '%+03d:%02d', $hours, $minutes );
	}
	// error_log('timezone_string2: ' . $timezone_string);

	// get current date time data with summer time (DST) correction
	// $thisDate = new DateTime("now", new DateTimeZone(get_option('timezone_string')));
	$thisDate = new DateTime( 'now', new DateTimeZone( $timezone_string ) );

	// check if daylight saving time now || 0. Standard time begins on: October 25, 2020 2:00 am
	// if($thisDate->format('I')){
	// $thisDate->add(new DateInterval('PT1H'));
	// }
	$theTime = array(
		'DateTime' => $thisDate,
		'date'     => $thisDate->format( 'Y-m-d 00:00:00' ),
		'hour'     => $thisDate->format( 'H' ),
		'minute'   => $thisDate->format( 'i' ),
		'datetime' => $thisDate->format( 'Y-m-d H:i:s' ),
		'offset'   => $timezone_string,
	);
	return $theTime;
}

function email_message($message1,$option_email_3,$option_email_4,$option_email_5,$option_email_6,$option_email_7,$option_email_8)
{

	$message='
		<div id="wrapper" dir="ltr" style="background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%; -webkit-text-size-adjust: none;" bgcolor="#f7f7f7" width="100%">

			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">

				<tr>

					<td align="center" valign="top">

						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #fff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,.1); border-radius: 3px;" bgcolor="#fff">

							<tr>

								<td align="center" valign="top">

									<!-- Header -->

									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style=\'background: ' . esc_attr($option_email_5) . '; color: #fff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; border-radius: 3px 3px 0 0;\' bgcolor="#2b87da">

										<tr>

											<td id="header_wrapper" style="display: table-cell !important; padding: 0 0 0 48px; text-align: left; height: 100px;">

												<h1 style=\'font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #2b87da; color: #fff; background-color: inherit;\' bgcolor="inherit">' . __( 'New reservation', 'court-reservation' ) . '</h1>

											</td>
											<td id="header_wrapper2" style="padding: 10px 25px 10px; display: table-cell !important; text-align: right;">

												<img src="' . sanitize_url($option_email_3) . '" style="max-width: 200px; max-height: 80px;">

											</td>

										</tr>

									</table>

									<!-- End Header -->

								</td>

							</tr>

							<tr>

								<td align="center" valign="top">

									<!-- Body -->

									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">

										<tr>

											<td valign="top" id="body_content" style="background-color: ' . esc_attr($option_email_6) . ';" bgcolor="' . esc_attr($option_email_6) . '">

												<!-- Content -->

												<table border="0" cellpadding="20" cellspacing="0" width="100%">

													<tr>

														<td valign="top" style="padding: 48px 48px 32px;">

															<div id="body_content_inner" style=\'color: ' . esc_attr($option_email_8) . '; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 14px; line-height: 150%; text-align: left;\' align="left">

																<p style="margin: 0 0 16px;">' . wp_kses_post($message1) . '</p>

															</div>

														</td>

													</tr>

												</table>

												<!-- End Content -->

											</td>

										</tr>

									</table>

									<!-- End Body -->

								</td>

							</tr>

							<tr>

								<td align="center" valign="top">

									<!-- Footer -->

									<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer" style="background-color: ' . esc_attr($option_email_7) . '">

										<tr>

											<td valign="top" style="padding: 0; border-radius: 6px;">

												<table border="0" cellpadding="10" cellspacing="0" width="100%">

													<tr>

														<td colspan="2" valign="middle" id="credit" style=\'border-radius: 6px; border: 0; color: #7a7a7a; font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;\' align="center">

															<p style="margin: 0 0 16px;">' . wp_kses_post($option_email_4) . '</p>

														</td>

													</tr>

												</table>

											</td>

										</tr>

									</table>

									<!-- End Footer -->

								</td>

							</tr>

						</table>

					</td>

				</tr>

			</table>

		</div>

';

	return $message;
}

function get_court_calendar($courtID,$nowTZTS,$fromDay,$tillDay,$type)
{ ?>

		<div class="navigator" id="prvi_kal_<?php echo esc_attr( $courtID ); ?>" <?php if (isset($_POST['sljedeci'])) { echo " style='display: none;' "; } ?>>
			<a class="button" id="cr-days-prev-<?php echo esc_attr( $courtID ); ?>" data-navigator="prev">
				<span>&lt;</span>
			</a>
			<a class="button" id="cr-days-today-<?php echo esc_attr( $courtID ); ?>" data-navigator="today">
				<span><?php echo esc_html__( 'Today', 'court-reservation' ); ?></span>
			</a>
			<a class="button" id="cr-days-next-<?php echo esc_html( $courtID ); ?>" data-navigator="next">
				<span>&gt;</span>
			</a>
			<a class="button" class="button" style="" onclick="document.getElementById('prvi_kal_<?php echo esc_attr( $courtID ); ?>').style.display='none'; document.getElementById('strelice_<?php echo esc_attr( $courtID ); ?>').style.display='block'; document.getElementById('drugi_kal_<?php echo esc_attr( $courtID ); ?>').style.display='block';">
				<img src="<?php echo plugin_dir_url( __FILE__ ).'public/images/kalendar.png'; ?>" style="width:20px;">
			</a>
			<div id="cr-today-my"><?php echo esc_html(date_i18n( 'F', strtotime( '+0 day', $nowTZTS ) )) . ' ' . esc_html(date_i18n( 'Y', strtotime( '+0 day', $nowTZTS ) )); ?></div>

		</div>
<?php 


				$dani_tjedna=array("","M","T","W","T","F","S","S");
				$mjeseci=array("01"=>"January", "02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"August", "09"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
				$odabrani_dan=date('Y-m-d');
				$danas=date('Y-m-d');
				if (isset($_SESSION['cr_from']) && is_numeric($_SESSION['cr_from']))
				{
					$buducnost=$_SESSION['cr_from'] . " days";
					$odabrani_dan = date('Y', strtotime($buducnost, strtotime($danas)));
					if ($odabrani_dan<2022) { $odabrani_dan=date('Y-m-d'); } else { $odabrani_dan = date('Y-m-d', strtotime($buducnost, strtotime($danas))); }
				}
				if (isset($_POST['datum']))
				{
					$odabrani_dan = $_POST['datum'];
					$odabrani_dan_ = strtotime($odabrani_dan);
					$danas_ = strtotime($danas);
					$razlika =  round( ($odabrani_dan_-$danas_) / (60 * 60 * 24) );
					$_SESSION['cr_from']=$razlika;
					$fromDay                   = isset( $_SESSION['cr_from'] ) ? intval( $_SESSION['cr_from'] ) : 0; // $court->days;
					$tillDay                   = $fromDay === 0 ? $court->days : $fromDay + $court->days;
				}
				// $danas=date('2023-06-05');
				$danas_bez1=explode("-",$odabrani_dan);
				$danas_bez=$danas_bez1[0] . "-" . $danas_bez1[1] . "-";
				$danas_prvi=$danas_bez . "01";
				$danas_zadnji=$danas_bez . date('t', strtotime($danas));
				$danas_zadnji1=date('t', strtotime($odabrani_dan));
				$prosli_zadnji1=date('t', strtotime("last month", strtotime($odabrani_dan)));
				$danas_dan=$danas_bez1[2];
				$danas_mjesec=$danas_bez1[1];
				$danas_godina=$danas_bez1[0];

				if ($danas_dan<10) { $danas_dan1=explode("0",$danas_dan); $danas_dan=$danas_dan1[1]; }

				$odabrani_dan_ = strtotime($odabrani_dan);
				$danas_ = strtotime($danas);
				$razlika =  round( ($odabrani_dan_-$danas_) / (60 * 60 * 24) ); ?>


		<div id='strelice_<?php echo esc_html($courtID); ?>' style='display: none; position: relative; height: 0; width: 100%; max-width: 197px;'>
			<div id='cr-days-prev-<?php echo esc_html($courtID); ?>' data-navigator='prev-month' style='position: absolute; cursor: pointer; border: none; height: 20px; left: 0px; top: 58px; z-index: 10; font-size: 22px; width: 30px; padding: 0; line-height: 20px; text-align: center; background: transparent; color: inherit; box-sizing: border-box;' data-day='<?php echo esc_html($prosli_zadnji1); ?>'><</div>
			<div id='cr-days-next-<?php echo esc_html($courtID); ?>' data-navigator='next-month' style='position: absolute; cursor: pointer; border: none; height: 20px; right: 0px; top: 58px; z-index: 10; font-size: 22px; width: 30px; padding: 0; line-height: 20px; text-align: center; background: transparent; color: inherit; box-sizing: border-box;' data-day='<?php echo esc_html($danas_zadnji1); ?>'>></div>
		</div>

		<div style="width: 100%; <?php if (!isset($_POST['sljedeci'])) { echo " display: none;"; } ?>" id="drugi_kal_<?php echo esc_attr( $courtID ); ?>">
			<div style="backwidth: 100%; max-width: 197px; background: #f9fafb; margin-bottom: 10px;">

				<form action='' method='POST' name='kalendar'>

					<div id='cr_calendar' style='cursor: pointer; position: relative; width: 197px; margin-top: 10px; text-align: left;'>
						<input name='datum' value='YYYY-MM-DD' type='text' style='outline: none; padding: 8px 10px 6px; width: 110px; background: transparent; font-size: 14px; color: lightgray; border: none;' onfocus='this.value=""; this.style.color="inherit"; this.style.border="0px solid black";'>
						<div name='ponisti' class='button' style='padding-top: 3px; width: 28px; height: 24px; margin-top: 2px; position: absolute; right: 0; top: 0; color: inherit;' onclick='document.getElementById("strelice_<?php echo esc_html($courtID); ?>").style.display="none"; document.getElementById("drugi_kal_<?php echo esc_html($courtID); ?>").style.display="none"; document.getElementById("prvi_kal_<?php echo esc_html($courtID); ?>").style.display="flex";'>
							<img src="<?php echo plugin_dir_url( __FILE__ ).'public/images/kalendar.png'; ?>" style="width:18px;">
						</div>
					</div>

					<div style='width: 197px; box-sizing: border-box; border-top: 3px solid #2273d7; padding: 10px; position: relative; text-align: center;'>
						<?php echo esc_html($mjeseci[$danas_mjesec]) . " &nbsp; " . esc_html($danas_godina); ?>
					</div>

				</form> <?php


				$prvi2=date("N",strtotime($danas_prvi));
	
				for ($x=1;$x<=7;$x++)
				{ ?>

					<div style="text-align: center; padding-top: 2px; float: left; width: 28px; height: 28px;">
						<?php echo esc_html($dani_tjedna[$x]); ?>
					</div> 

				<?php } ?>

				<div style="float: left; width: 0px; height: 28px; ">
					&nbsp;
				</div>  <?php

				$tjedan=0;
				for ($x=1;$x<=$prvi2-1;$x++)
				{ 
					$tjedan++; ?>

				<div style="text-align: center; float: left; width: 28px; height: 28px; ">
					&nbsp;
				</div> 

				<?php } 
				for ($x=1;$x<=$danas_zadnji1;$x++)
				{ 
					$tjedan++; 
					if ($tjedan==8)
					{ 
						$tjedan=1; ?>

				<div style="float: left; width: 0px; height: 28px;">
					&nbsp;
				</div>  <?php

					} 

					if ($x<10) { $trenutni_dan=$danas_bez . "0" . $x; } else { $trenutni_dan=$danas_bez . $x; }
					$trenutni_dan_ = strtotime($trenutni_dan);
					$razlika_ =  round( ($trenutni_dan_-$danas_) / (60 * 60 * 24) ); ?>

				<div id="cr_calendar_<?php echo esc_html($x); ?>_<?php echo esc_html($courtID); ?>" data-day="<?php echo esc_html($razlika_); ?>" class="kalendar-dani" style="cursor: pointer; <?php if ($x==$danas_dan) { echo "color: black; font-weight: bold;"; } else { echo "color: darkgray; "; } ?>text-align: center; padding-top: 3px; float: left; width: 28px; height: 28px;">
					<?php echo esc_html($x); ?>
				</div> <?php

				} 

				for ($x=$tjedan;$x<7;$x++)
				{  ?>

				<div style="text-align: center; float: left; width: 28px; height: 28px; ">
					&nbsp;
				</div>  <?php

				} ?>	

				<div style="float: left; width: 0px; height: 28px;">
					&nbsp;
				</div>  

				<div style="clear: both;"> </div>
			</div>
		</div> <?php
}
