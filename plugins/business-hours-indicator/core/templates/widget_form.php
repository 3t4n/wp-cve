<?php

?>

<div class="widget-options">
	<?php
		foreach($option_manager->options as $option)
		{
			echo '<p>';
				echo '<label>' .$option->title. '</label>';
				echo '<div>';
					$option_manager->display_field( [ 'option' => $option ] );
				echo '</div>';
			echo '</p>';
		}
	?>
</div>
