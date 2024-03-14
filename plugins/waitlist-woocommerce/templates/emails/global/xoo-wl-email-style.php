<?php
/**
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/emails/global/xoo-wl-email-style.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/waitlist-for-woocommerce/
 * @version 2.4
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


?>


<style type="text/css">

  body, td, input, textarea, select{
    font-family: Tahoma, sans-serif;
  }
  
  /* CLIENT-SPECIFIC STYLES ------------------- */

  #outlook a {
    padding: 0; /* Force Outlook to provide a "view in browser" message */
  } 

  .ReadMsgBody {
    width: 100%; /* Force Hotmail to display emails at full width */
  } 

  .ExternalClass {
    width:100%; /* Force Hotmail to display emails at full width */
  }

  .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
    line-height: 100%; /* Force Hotmail to display normal line spacing */
  }

  body, table, td, a { /* Prevent WebKit and Windows mobile changing default text sizes */
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
  }

  table, td { /* Remove spacing between tables in Outlook 2007 and up */
    mso-table-lspace: 0pt;
    mso-table-rspace:0pt;
  }

  img { /* Allow smoother rendering of resized image in Internet Explorer */
    -ms-interpolation-mode: bicubic;
  }

  /* RESET STYLES --------------------------- */

  body { 
    height: 100% !important;
    margin: 0;
    padding: 0;
    width: 100% !important;
  }

  img { 
    border: 0;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
  }

  table {
    border-collapse: collapse!important;
  }

  /* iOS BLUE LINKS */

  .apple-links a { 
    color: #999999;
    text-decoration: none;
  }

  /* MOBILE STYLES ------------------------ */

  @media screen and (max-width: 600px){

    table.xoo-wl-table-full{
      width: 100%!important;
    }

    table.xoo-wl-bist-content{
      margin-top: 20px;
      margin-bottom: 20px;
    }

  }
</style>