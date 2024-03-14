<?php 

function mpsp_slider_rec_plugins(){
	
	$cfb = 'contact-form-add';
  	$cfb_install_link =  esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $cfb . '&TB_iframe=true&width=950&height=800' ) );
	$pba = 'page-builder-add';
  	$pba_install_link =  esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $pba . '&TB_iframe=true&width=950&height=800' ) );

  	$fbf = 'add-facebook';
  	$fbf_install_link =  esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $fbf . '&TB_iframe=true&width=950&height=800' ) );


	?>
	<div style="font-size: 16px; line-height: 2em;" >
	<br>
	<img src="https://ps.w.org/contact-form-add/assets/icon-250x250.png?rev=1424882" >
	<br>
	<h3>Drag & Drop Form Builder (Free)</h3>
	<a style='text-decoration: none;' href="<?php  echo $cfb_install_link; ?>" target='_blank'><div id='rate_button' style=' border: 0; padding: 3% 10% 3% 10%; background: #2ecc71; '>Install Now</div></a>
	<br>
	<hr>
	<br>
	<img src="https://ps.w.org/page-builder-add/assets/icon-250x250.png?rev=1424882" >
	<br>
	<h3> Drag & Drop Page Builder (Free) </h3>
	<a style='text-decoration: none;' href="<?php  echo $pba_install_link; ?>" target='_blank'><div id='rate_button' style='border: 0; padding: 3% 10% 3% 10%; background: #2ecc71;'>Install Now</div></a>
	<br>
	<hr>
	<br>
	<img src="https://ps.w.org/add-facebook/assets/icon-250x250.png?rev=1424882" >
	<br>
	<h3> Facebook Stream (Free) </h3>
	<a style='text-decoration: none;' href="<?php  echo $fbf_install_link; ?>" target='_blank'><div id='rate_button' style='border: 0; padding: 3% 10% 3% 10%; background: #2ecc71;'>Install Now</div></a>
	</div>
	<?php

}

?>