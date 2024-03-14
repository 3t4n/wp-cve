<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// if header is calling later
MJTC_includer::MJTC_getModel('majesticsupport')->checkIfMainCssFileIsEnqued();
$color1 = majesticsupport::$_colors['color1'];
$color2 = majesticsupport::$_colors['color2'];
$color3 = majesticsupport::$_colors['color3'];
$color4 = majesticsupport::$_colors['color4'];
$color5 = majesticsupport::$_colors['color5'];
$color6 = majesticsupport::$_colors['color6'];
$color7 = majesticsupport::$_colors['color7'];
$color8 = majesticsupport::$_colors['color8'];
$color9 = majesticsupport::$_colors['color9'];

$majesticsupport_css = '';

/*Code for Css*/
$majesticsupport_css .= '
	/*Annoucment Detail*/
	div.mjtc-support-knowledgebase-wrapper{float: left;width:100%;margin-top: 0px;}
	div.mjtc-support-knowledgebase-details{float: left;width: 100%;padding: 15px;}
	div.mjtc-support-top-search-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp{float: left;width: 100%;}
	div.mjtc-support-search-heading-wrp div.mjtc-support-heading-left{float: left;width: 100%;font-weight: 600;text-transform: capitalize;font-size: 26px;line-height: initial;}
	div.mjtc-support-knowledgebase-details {float: left;width: 100%;padding: 15px 0;line-height: 1.8;}
	div.mjtc-support-categories-wrp {float: left;width: 100%;margin-top: 25px;}
	div.mjtc-support-margin-bottom {margin-bottom: 20px;margin-top: 10px;}
	div.mjtc-support-categories-heading-wrp {float: left;font-weight:bold;width: 100%;padding: 15px;line-height: initial;}


';
/*Code For Colors*/
$majesticsupport_css .= '
	div.mjtc-support-search-heading-wrp{color:'.$color2.';}
	div.mjtc-support-knowledgebase-details{color:'.$color4.';}
	div.mjtc-support-categories-heading-wrp {background-color: '.$color3.';border: 1px solid '.$color5.';color: '.$color2.';}

';


wp_add_inline_style('majesticsupport-main-css',$majesticsupport_css);


?>
