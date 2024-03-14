<?php
use WP_Reactions\Lite\Helper;
$heading = $subheading = $tooltip = '';
$align = 'center';
extract($data);
?>

<div class="wpra-option-heading heading-<?php echo $align; ?>">
	<h4>
        <span><?php echo $heading; ?></span>
		<?php if (!empty($tooltip)) { Helper::tooltip( $tooltip ); } ?>
    </h4>
    <p><?php echo $subheading; ?></p>
</div>
