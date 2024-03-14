<div class="notice notice-error is-dismissible">
	<p>
		<?php _ex('MemberSpace plugin could not be loaded, the following minimum requirements are not met:', 'plugin activation error header', 'memberspace'); ?>
		<?php
			foreach ($this->failures as $failure) {
				echo "<br>";
				echo htmlspecialchars( $failure );
			}
		?>
	</p>
</div>
