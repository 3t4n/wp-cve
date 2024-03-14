<?php
$tags = get_the_tags();

if ( $tags ) { ?>
	<div class="qodef-e-info-item qodef-e-info-tags">
		<?php the_tags( '', ', ', '' ); ?>
	</div>
<?php } ?>
