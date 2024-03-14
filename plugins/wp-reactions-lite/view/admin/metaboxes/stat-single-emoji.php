<?php
$count = 0;
$percentage = 0;
if (isset($data)) {
    extract($data);
}
?>
<div class="stat-single-emoji">
	<div class="stat-count"><?php echo $count; ?></div>
	<div class="stat-percentage"><?php echo $percentage; ?>%</div>
</div>