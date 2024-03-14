<div class="areoi-background-pattern areoi-background-pattern-<?php echo $has_pattern ?>">
	<div class="container h-100">
		<div class="row h-100">
			<?php 
			for ( $i = 1; $i <= 4; $i++ ) : 
				$style = 'border-left: 1px solid ' . $pattern_color . ';';
				if ( $i == 4 ) $style .= 'border-right: 1px solid ' . $pattern_color . ';';
			?>

				<div class="col h-100" style="<?php echo $style ?>"></div>
			<?php endfor; ?>
	
		</div>
	</div>
</div>