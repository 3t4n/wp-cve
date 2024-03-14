<div class="wrap woocommerce">
	<?php
	require 'html-admin-tabs.php';
	
	if($tab=='export'){
		$this->admin_export_page();
	}
	else if($tab=='logs'){
		$this->admin_log_page($log_file);
	}
	else if($tab=='cron'){
		$this->admin_cron_page();			
	}
	else{
		$this->admin_import_page();
	}
	?>
</div>