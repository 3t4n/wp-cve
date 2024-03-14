<?php
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	include("backend/cwmp-woocommerce-mestres-wp.php");
}