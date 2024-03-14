<?php 
@session_start();

//header('Cache-Control: No-Store');
header("Content-Type: text/css;charset=utf-8");

/***stylesheet for eazy ad unblocker****#eazy_ad_unblocker_dialog-parent**/?>

<?php echo '#'.$_SESSION['EAZY_AD_UNBLOCKER_DIALOG_PARENT_ID'];  ?> .ui-dialog-titlebar .ui-dialog-titlebar-close{ display: none !important;}
<?php /**refresh btn**/  ?>
<?php echo '#'.$_SESSION['EAZY_AD_UNBLOCKER_DIALOG_MESSAGE_ID'];  ?> <?php echo ".".$_SESSION['EAZY_AD_UNBLOCKER_REFRESH_BTN_CLASS'];  ?>{  float:right; }
<?php 
/**Nov 2020 responsive***/
/* Media Queries */

/* For example, older phones */
?>
@media only screen and (max-width: 360px){
}

<?php /* For example, newer phones */ ?>
@media only screen and (min-width: 361px) and (max-width: 480px){
}
<?php
/* For example, small computer screens and larger tablets */
?>
@media only screen and (min-width: 481px) and (max-width: 768px) {
    <?php echo '#'.$_SESSION['EAZY_AD_UNBLOCKER_DIALOG_MESSAGE_ID'];  ?>{ width: 100%; min-width: 481px; max-width: 768px; }
}
<?php
/* For example, typical desktop monitors or larger tablet devices */ ?>
@media only screen and (min-width: 992px){
    
}

/* Large Devices, for example large monitors and TVs */
@media only screen and (min-width: 1200px){
	
}