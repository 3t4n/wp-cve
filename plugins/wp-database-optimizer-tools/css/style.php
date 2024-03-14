body{
font-family:Arial, Helvetica, sans-serif; 
font-size:13px;
}
#info, #success, #warning, #error, #validation {
border: 1px solid;
margin: 10px 0px;
padding:15px 10px 15px 50px;
background-repeat: no-repeat;
background-position: 10px center;
margin-top: 25px;
}
#info {
color: #00529B;
background-color: #BDE5F8;
background-image: url('<?php echo get_option('siteurl').'/wp-content/plugins/wp-database-optimizer-tools/images/info.png'; ?>');
}
#success {
color: #4F8A10;
background-color: #DFF2BF;
background-image:url('<?php echo get_option('siteurl').'/wp-content/plugins/wp-database-optimizer-tools/images/good.png';?>');
}
#warning {
color: #9F6000;
background-color: #FEEFB3;
background-image: url('<?php echo get_option('siteurl').'/wp-content/plugins/wp-database-optimizer-tools/images/warning.png';?>');
}
#error {
color: #D8000C;
background-color: #FFBABA;
background-image: url('<?php echo get_option('siteurl').'/wp-content/plugins/wp-database-optimizer-tools/images/error.png';?>');
}
#tableForm{
	float: left;
}
#infoHelp{
	float: right;
	margin-top: -15px;
	border: 1px solid;
	padding:15px 15px 15px 15px;

}

#optimizeTable{
	clear:both;
}
