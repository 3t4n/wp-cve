<style>
.new_btn:hover { 

				background-color:<?php echo $gen_settings["btn_hov_col"]; ?> !important;
					
				}
				.new_btn{  white-space: wrap !important; border:<?php echo $gen_settings["btn_border_style"]; ?> <?php echo $gen_settings["btn_border"]; ?>px <?php echo $gen_settings["btn_bor_col"]; ?> !important; border-radius: <?php echo $gen_settings["btn_rad"]; ?>px !important; 
				
				<?php if(($gen_settings["topmargin"]!='0')&&($gen_settings["rightmargin"]!='0')&&($gen_settings["bottommargin"]!='0')&&($gen_settings["leftmargin"]!='0'))
				{ ?>
				
				padding: <?php echo $gen_settings["topmargin"]; ?>px <?php echo $gen_settings["rightmargin"]; ?>px <?php echo $gen_settings["bottommargin"]; ?>px <?php echo $gen_settings["leftmargin"]; ?>px !important; <?php } ?>  }
</style>