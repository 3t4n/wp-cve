<?php

require_once ("paypalfunctions.php");

$PaymentOption = "PayPal";
if ( $PaymentOption == "PayPal")
{
        // ==================================
        // PayPal Express Checkout Module
        // ==================================

	
	        
        //'------------------------------------
        //' The paymentAmount is the total value of 
        //' the purchase.
        //'
        //' TODO: Enter the total Payment Amount within the quotes.
        //' example : $paymentAmount = "15.00";
        //'------------------------------------

        $paymentAmount = $price;
        
        
        //'------------------------------------
        //' The currencyCodeType  
        //' is set to the selections made on the Integration Assistant 
        //'------------------------------------
        $currencyCodeType = "CZK";
        $paymentType = "Sale";

        //'------------------------------------
        //' The returnURL is the location where buyers return to when a
        //' payment has been succesfully authorized.
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $returnURL = get_option('siteurl') . "/?pdckl=pp_confirm";

        //'------------------------------------
        //' The cancelURL is the location buyers are sent to when they hit the
        //' cancel button during authorization of payment during the PayPal flow
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $cancelURL = get_option('siteurl') . "/?pdckl=pp_canceled";

        //'------------------------------------
        //' Calls the SetExpressCheckout API call
        //'
        //' The CallSetExpressCheckout function is defined in the file PayPalFunctions.php,
        //' it is included at the top of this file.
        //'-------------------------------------------------

        
		$items = array();
		$items[] = array('name' => $item_name, 'amt' => $paymentAmount, 'qty' => 1);
	
		//::ITEMS::
		
		// to add anothe item, uncomment the lines below and comment the line above 
		// $items[] = array('name' => 'Item Name1', 'amt' => $itemAmount1, 'qty' => 1);
		// $items[] = array('name' => 'Item Name2', 'amt' => $itemAmount2, 'qty' => 1);
		// $paymentAmount = $itemAmount1 + $itemAmount2;
		
		// assign corresponding item amounts to "$itemAmount1" and "$itemAmount2"
		// NOTE : sum of all the item amounts should be equal to payment  amount 

		$resArray = SetExpressCheckoutDG( $paymentAmount, $currencyCodeType, $paymentType, 
												$returnURL, $cancelURL, $items );

    $ack = strtoupper($resArray["ACK"]);
    if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
      $token = urldecode($resArray["TOKEN"]);
      RedirectToPayPalDG($token);
    } else {
      //Display a user friendly Error on the page using any of the following error information returned by PayPal
      $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
      $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
      $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
      $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

      switch($ErrorLongMsg) {
        case 'You do not have permissions to make this API call':
          echo '
            <html>
              <head>
                <title>You do not have permissions to make this API call</title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8">
              </head>
              <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
                <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;"><strong><a href="http://www.copywriting.cz/napoveda/7" style="color: #a94442;" target="_blank">Neplatné PayPal API údaje</a></strong><br /><br />
                  <div style="text-align: left;">
                  Detaily problému:<br />
                  <code>';
              echo "SetExpressCheckout API call failed.<br />";
              echo "Detailed Error Message: " . $ErrorLongMsg . "<br />";
              echo "Short Error Message: " . $ErrorShortMsg . "<br />";
              echo "Error Code: " . $ErrorCode . "<br />";
              echo "Error Severity Code: " . $ErrorSeverityCode . "<br />";
            echo '</code></div>
                </div><br />
                <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">Zavřít okno</a></div>
              </body>
            </html>
          ';
        break;

        case 'Security header is not valid':
          echo '
            <html>
              <head>
                <title>Security header is not valid</title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8">
              </head>
              <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
                <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;"><strong><a href="http://www.copywriting.cz/napoveda/7" style="color: #a94442;" target="_blank">Prohozeny sandbox údaje s těmi live nebo opačně</a></strong><br /><br />
                  <div style="text-align: left;">
                  Detaily problému:<br />
                  <code>';
              echo "SetExpressCheckout API call failed.<br />";
              echo "Detailed Error Message: " . $ErrorLongMsg . "<br />";
              echo "Short Error Message: " . $ErrorShortMsg . "<br />";
              echo "Error Code: " . $ErrorCode . "<br />";
              echo "Error Severity Code: " . $ErrorSeverityCode . "<br />";
            echo '</code></div>
                </div><br />
                <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">Zavřít okno</a></div>
              </body>
            </html>
          ';
        break;

        default:
          echo '
            <html>
              <head>
                <title>' . $ErrorLongMsg . '</title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8">
              </head>
              <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
                <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;">
                  <div style="text-align: left;">
                  <code>';
            echo "SetExpressCheckout API call failed.<br />";
            echo "Detailed Error Message: " . $ErrorLongMsg . "<br />";
            echo "Short Error Message: " . $ErrorShortMsg . "<br />";
            echo "Error Code: " . $ErrorCode . "<br />";
            echo "Error Severity Code: " . $ErrorSeverityCode . "<br />";
            echo '</code></div>
                </div><br />
                <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">Zavřít okno</a></div>
              </body>
            </html>
          ';
        break;
      }
    }
}
?>
