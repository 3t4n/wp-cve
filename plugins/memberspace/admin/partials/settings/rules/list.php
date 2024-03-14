<ul class="memberspace-protected-pages-list">
	<?php
		foreach ( $rules as $rule ) {
			include( plugin_dir_path( __FILE__ ) . 'list_row.php' );
		}
	?>
</ul>