<?php
global $wpdb;

$common = new BSI_Common();
?>
<div class="php_info bsi"> 
    <h1 class="">System Information > File Permission </h1>
    <div>
   
    <table class="" align="center" cellspacing="0" >
			<thead>
				<th><?php echo esc_attr(__('Permissions', 'bsi')); ?></th>
				<th><?php echo esc_attr(__('Type', 'bsi')); ?></th>
				<th><?php echo esc_attr(__('Files / Folders', 'bsi')); ?></th>
			</thead>
			<?php
			$ctdf = 0;
			$ctdd = 0;
            
			for ($i = 0; $i < count($files); $i++) {
				if (is_dir($files[$i])) {
					if ($files[$i] == 'wp-config.php') {
						$ctdd++;
						continue;
					}
					if (decoct(fileperms($files[$i]) & 0777) != '755') {
						$ctdd++;
						if ($ctdd < 51) {
							echo '<tr>';
							echo '<td>';
							echo decoct(fileperms($files[$i]) & 0777);
							echo '</td>';
							echo '<td>';
							echo  "Folder";
							echo '</td>';
                            echo '<td>';
							echo esc_attr($files[$i]);
							echo '</td>';
							echo '<tr>';
						}
					}
				} else {
					if (@decoct(fileperms($files[$i]) & 0777) != '644') {
						$ctdf++;
						if ($ctdf < 51) {
							echo '<tr>';
							echo '<td>';
							try {
								echo @decoct(fileperms($files[$i]) & 0777);
							} catch (exception $e) {
							}
							// echo decoct(fileperms($files[$i]) & 0777);
							echo '</td>';
                            echo '<td>';
							echo  "File";
							echo '</td>';
							echo '<td>';
							echo esc_attr($files[$i]);
							echo '</td>';
							echo '<tr>';
						}
					}
				}
			} ?>
		</table>
        
    </div>
</div>

